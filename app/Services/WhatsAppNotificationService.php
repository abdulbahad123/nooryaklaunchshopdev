<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Get the default WhatsApp gateway endpoint base URL.
     */
    public static function getEndpoint(): string
    {
        return env('WHATSAPP_SERVER_URL', 'http://localhost:8000');
    }

    /**
     * Get the default WhatsApp API token (team token).
     */
    public static function getAccessToken(): string
    {
        return env('WHATSAPP_ACCESS_TOKEN', '6005c0f6de385fe7');
    }

    /**
     * Get the default WhatsApp active instance ID.
     */
    public static function getInstanceId(): string
    {
        return env('WHATSAPP_INSTANCE_ID', 'E351BB42DC82');
    }

    /**
     * Get the target database for Universal 1-Node Server.
     */
    public static function getDatabaseName(): string
    {
        return env('WHATSAPP_DB', 'nooryak_whatautomation');
    }

    /**
     * Dynamically resolve currently active instance & access token:
     * 1. Asks the local Node server (/active_instance)
     * 2. Checks WhatsApp MySQL database (accounts table with status=1)
     * 3. Falls back to .env configuration or default
     */
    public static function resolveActiveCredentials(): array
    {
        $cacheKey = 'dynamic_whatsapp_credentials';
        $cached = Cache::get($cacheKey);
        if (!empty($cached['instance_id']) && !empty($cached['access_token'])) {
            return $cached;
        }

        $endpointBase = rtrim(self::getEndpoint(), '/');
        $resolvedInstance = null;
        $resolvedToken = null;
        $resolvedDb = self::getDatabaseName();

        // Method 1: Ask Node engine on port 8000
        try {
            $nodeUrl = $endpointBase . '/active_instance?db=' . urlencode($resolvedDb);
            $res = Http::timeout(2)->withoutVerifying()->get($nodeUrl);
            if ($res->successful()) {
                $data = $res->json();
                if (($data['status'] ?? '') === 'success' && !empty($data['instance_id'])) {
                    $resolvedInstance = $data['instance_id'];
                    $resolvedToken = $data['access_token'] ?: self::getAccessToken();
                    $resolvedDb = $data['db'] ?: $resolvedDb;
                }
            }
        } catch (\Throwable $e) {}

        // Method 2: Query MySQL database directly for active account (status = 1)
        if (empty($resolvedInstance)) {
            $dbNames = [
                $resolvedDb,
                'nooryak_whatautomation',
                'replix_automation',
            ];

            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');
            $user = env('DB_USERNAME_admin', env('DB_USERNAME', 'root'));
            $pass = env('DB_PASSWORD_admin', env('DB_PASSWORD', ''));

            foreach ($dbNames as $dbName) {
                try {
                    $pdo = new \PDO("mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4", $user, $pass, [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_SILENT,
                        \PDO::ATTR_TIMEOUT => 2,
                    ]);

                    $stmt = $pdo->query("SELECT token, team_id, name FROM accounts WHERE status = 1 AND social_network = 'whatsapp_unofficial' AND category = 'profile' ORDER BY id DESC LIMIT 1");
                    $acc = $stmt ? $stmt->fetch(\PDO::FETCH_ASSOC) : null;

                    if ($acc && !empty($acc['token'])) {
                        $resolvedInstance = $acc['token'];
                        $resolvedDb = $dbName;

                        if (!empty($acc['team_id'])) {
                            $tStmt = $pdo->prepare("SELECT id_secure FROM teams WHERE id = ? LIMIT 1");
                            $tStmt->execute([$acc['team_id']]);
                            $tRow = $tStmt->fetch(\PDO::FETCH_ASSOC);
                            if ($tRow && !empty($tRow['id_secure'])) {
                                $resolvedToken = $tRow['id_secure'];
                            }
                        }
                        break;
                    }
                } catch (\Throwable $ex) {}
            }
        }

        // Method 3: Fall back to .env configuration or default constants
        if (empty($resolvedInstance)) {
            $resolvedInstance = self::getInstanceId();
        }
        if (empty($resolvedToken)) {
            $resolvedToken = self::getAccessToken();
        }

        $credentials = [
            'instance_id'  => $resolvedInstance,
            'access_token' => $resolvedToken,
            'db'           => $resolvedDb,
        ];

        // Cache for 60 seconds so subsequent requests are lightning fast
        try {
            Cache::put($cacheKey, $credentials, 60);
        } catch (\Throwable $cEx) {}

        return $credentials;
    }

    /**
     * Clean and format phone number into international numeric format (e.g. 919095111682).
     * Correctly handles leading zeros, spaces, international codes, and country prefixes.
     */
    public static function formatNumber($phone, string $defaultCountryCode = '91'): string
    {
        if (empty($phone)) {
            return '';
        }

        // 1. Strip all non-numeric characters
        $digits = preg_replace('/[^0-9]/', '', (string) $phone);
        if ($digits === '') {
            return '';
        }

        $cleanCode = preg_replace('/[^0-9]/', '', $defaultCountryCode) ?: '91';

        // 2. Fix cases where number has country code followed by a leading zero (e.g. 9109095111682 -> 919095111682)
        if (strpos($digits, $cleanCode . '0') === 0 && strlen($digits) === (strlen($cleanCode) + 11)) {
            $digits = $cleanCode . substr($digits, strlen($cleanCode) + 1);
        }

        // 3. Remove any leading zero from the remaining number
        $trimmed = ltrim($digits, '0');

        // 4. Standard 10-digit mobile number (e.g. 9095111682) -> prepend country code
        if (strlen($trimmed) === 10) {
            return $cleanCode . $trimmed;
        }

        // 5. Already has country code + 10 digits (e.g. 919095111682)
        if (strpos($trimmed, $cleanCode) === 0 && strlen($trimmed) === (strlen($cleanCode) + 10)) {
            return $trimmed;
        }

        // 6. 11 digits starting with zero (e.g. 09095111682)
        if (strlen($digits) === 11 && strpos($digits, '0') === 0) {
            return $cleanCode . substr($digits, 1);
        }

        return $trimmed ?: $digits;
    }

    /**
     * Core method to send text or media message via WhatsApp Gateway.
     * Uses dynamic instance discovery, query params, and auto-retry for token prefixes.
     */
    public static function sendMessage($number, string $message, ?string $mediaUrl = null, ?string $filename = null): array
    {
        $formattedNumber = self::formatNumber($number);
        if (empty($formattedNumber) || strlen($formattedNumber) < 8) {
            return [
                'success' => false,
                'message' => 'Invalid or missing phone number: ' . $number,
            ];
        }

        $endpointBase = rtrim(self::getEndpoint(), '/');
        $sendPath     = str_contains($endpointBase, '/send_message') ? $endpointBase : ($endpointBase . '/send_message');

        // Dynamically resolve active instance and token
        $activeCreds  = self::resolveActiveCredentials();
        $accessToken  = $activeCreds['access_token'];
        $instanceId   = $activeCreds['instance_id'];
        $dbName       = $activeCreds['db'];

        // Build tokens to try (try raw token and token_ prefix)
        $tokensToTry = [$accessToken];
        if (str_starts_with($accessToken, 'token_')) {
            $tokensToTry[] = substr($accessToken, 6);
        } else {
            $tokensToTry[] = 'token_' . $accessToken;
        }

        // Instances to try: dynamically resolved first, then default fallback
        $instancesToTry = array_unique(array_filter([
            $instanceId,
            self::getInstanceId(),
            'E351BB42DC82'
        ]));

        $lastResult = [
            'success' => false,
            'message' => 'No delivery attempt completed.',
        ];

        foreach ($instancesToTry as $currentInstance) {
            foreach ($tokensToTry as $currentToken) {
                $queryUrl = $sendPath . '?' . http_build_query([
                    'access_token' => $currentToken,
                    'instance_id'  => $currentInstance,
                    'db'           => $dbName,
                ]);

                $payload = [
                    'access_token' => $currentToken,
                    'instance_id'  => $currentInstance,
                    'number'       => $formattedNumber,
                    'message'      => $message,
                    'caption'      => $message,
                    'db'           => $dbName,
                ];

                if (!empty($mediaUrl)) {
                    $payload['media_url'] = $mediaUrl;
                    if (!empty($filename)) {
                        $payload['filename'] = $filename;
                    }
                }

                $logPrefix = "[" . date('Y-m-d H:i:s') . "] [WhatsAppGateway] Number: {$formattedNumber} | Instance: {$currentInstance} | ";

                try {
                    $response = Http::asForm()
                        ->timeout(12)
                        ->withoutVerifying()
                        ->post($queryUrl, $payload);

                    $status  = $response->status();
                    $body    = $response->json();
                    $bodyStr = $response->body();

                    $isSuccess = $response->successful() && (
                        ($body['status'] ?? '') === 'success' ||
                        ($body['status'] ?? '') === 1 ||
                        ($body['success'] ?? false) === true
                    );

                    $logLine = $logPrefix . "Status: {$status} | Success: " . ($isSuccess ? 'YES' : 'NO') . " | Response: {$bodyStr}\n";

                    try {
                        @file_put_contents(storage_path('logs/whatsapp_gateway.log'), $logLine, FILE_APPEND);
                    } catch (\Throwable $fEx) {}

                    Log::info("WhatsApp Gateway Response for {$formattedNumber}: {$bodyStr}");

                    if ($isSuccess) {
                        return [
                            'success'     => true,
                            'status'      => $status,
                            'response'    => $body,
                            'body'        => $bodyStr,
                            'instance_id' => $currentInstance,
                        ];
                    }

                    $lastResult = [
                        'success'     => false,
                        'status'      => $status,
                        'response'    => $body,
                        'message'     => $body['message'] ?? 'Gateway returned failure.',
                        'body'        => $bodyStr,
                        'instance_id' => $currentInstance,
                    ];

                    // If error is not authentication or invalid instance, don't keep cycling
                    $msgLower = strtolower($body['message'] ?? '');
                    if (!str_contains($msgLower, 'token') && !str_contains($msgLower, 'instance') && !str_contains($msgLower, 'auth')) {
                        return $lastResult;
                    }
                } catch (\Throwable $e) {
                    $errLine = $logPrefix . "EXCEPTION: " . $e->getMessage() . "\n";
                    try {
                        @file_put_contents(storage_path('logs/whatsapp_gateway.log'), $errLine, FILE_APPEND);
                    } catch (\Throwable $fEx) {}

                    Log::error("WhatsApp Gateway Exception for {$formattedNumber}: " . $e->getMessage());

                    $lastResult = [
                        'success' => false,
                        'message' => $e->getMessage(),
                    ];
                }
            }
        }

        return $lastResult;
    }

    /**
     * Send OTP Verification Code via WhatsApp.
     * Formats with WhatsApp bold markdown (*word*) as requested:
     * "Your OTP verification code is *{otp}* for *{productName}* - This code is valid for *5 minutes* - Please do not share it with anyone."
     */
    public static function sendOtp($phone, $otp, string $productName = 'Ecom Builder'): array
    {
        $cleanProductName = trim($productName);
        $norm = strtolower($cleanProductName);
        if ($norm === 'ecombuilder' || $norm === 'launchshop' || $norm === 'ecom builder' || $norm === 'launchshop ecommerce') {
            $cleanProductName = 'Ecom Builder';
        } elseif ($norm === 'websitebuilder' || $norm === 'website builder' || $norm === 'websitebuilder ecommerce') {
            $cleanProductName = 'Website Builder';
        }

        $message = "Your OTP verification code is *{$otp}* for *{$cleanProductName}* - This code is valid for *5 minutes* - Please do not share it with anyone.";

        return self::sendMessage($phone, $message);
    }

    /**
     * Send Website Launch / Store Credentials via WhatsApp.
     */
    public static function sendWebsiteLaunchCredentials(
        $phone,
        string $storeName,
        string $storeLiveLink,
        string $loginLink,
        string $email,
        string $password,
        ?string $planName = null,
        string $productName = 'Ecombuilder'
    ): array {
        $message = "🎉 *Congratulations! Your {$productName} store is live!*\n\n"
            . "Your store account has been created successfully.\n\n"
            . "👤 *Store Name:* {$storeName}\n"
            . "📧 *Email / Username:* {$email}\n"
            . "🔑 *Password:* {$password}\n"
            . (!empty($planName) ? "📦 *Plan:* {$planName}\n" : "")
            . "\n"
            . "🔗 *Store Live URL:*\n{$storeLiveLink}\n\n"
            . "🔐 *Dashboard Login:*\n{$loginLink}\n\n"
            . "Need help? Reply to this message anytime.\n"
            . "– Team {$productName} 🚀";

        return self::sendMessage($phone, $message);
    }

    /**
     * Send Payment Receipt / Invoice Confirmation via WhatsApp.
     */
    public static function sendInvoice(
        $phone,
        $invoiceId,
        $amount,
        ?string $planTitle = null,
        ?string $paymentMethod = null,
        ?string $invoiceUrl = null,
        string $productName = 'Ecombuilder'
    ): array {
        $message = "🧾 *Payment Receipt / Invoice Confirmation*\n\n"
            . "Thank you for your payment with *{$productName}*!\n\n"
            . "📄 *Invoice / Order ID:* #{$invoiceId}\n"
            . (!empty($planTitle) ? "📦 *Plan / Product:* {$planTitle}\n" : "")
            . "💰 *Amount Paid:* {$amount}\n"
            . (!empty($paymentMethod) ? "💳 *Payment Method:* {$paymentMethod}\n" : "")
            . (!empty($invoiceUrl) ? "\n🔗 *View / Download Invoice:*\n{$invoiceUrl}\n" : "")
            . "\n"
            . "Thank you for choosing *{$productName}*! 🚀";

        $filename = !empty($invoiceId) ? "Invoice_{$invoiceId}.pdf" : "Invoice.pdf";

        return self::sendMessage($phone, $message, $invoiceUrl, $filename);
    }
}