<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Default constants and fallbacks
     */
    const DEFAULT_GATEWAY_URL = 'http://localhost:8000';
    const DEFAULT_ACCESS_TOKEN = '6005c0f6de385fe7';
    const DEFAULT_INSTANCE_ID = 'E351BB42DC82';
    const DEFAULT_DB_NAME = 'nooryak_whatautomation';

    const DEFAULT_OTP_TEMPLATE = "Your OTP verification code is *{otp}* for *{product_name}* - This code is valid for *{validity_minutes} minutes* - Please do not share it with anyone.";
    
    const DEFAULT_CREDENTIALS_TEMPLATE = "🎉 *Congratulations! Your {product_name} store is live!*\n\nYour store account has been created successfully.\n\n👤 *Store Name:* {store_name}\n📧 *Email / Username:* {email}\n🔑 *Password:* {password}\n📦 *Plan:* {plan_name}\n\n🔗 *Store Live URL:*\n{store_url}\n\n🔐 *Dashboard Login:*\n{login_url}\n\nNeed help? Reply to this message anytime.\n– Team {product_name} 🚀";

    const DEFAULT_INVOICE_TEMPLATE = "🧾 *Payment Receipt / Invoice Confirmation*\n\nThank you for your payment with *{product_name}*!\n\n📄 *Invoice / Order ID:* #{invoice_id}\n📦 *Plan / Product:* {plan_title}\n💰 *Amount Paid:* {amount}\n💳 *Payment Method:* {payment_method}\n\n🔗 *View / Download Invoice:*\n{invoice_url}\n\nThank you for choosing *{product_name}*! 🚀";

    /**
     * Get the default WhatsApp gateway endpoint base URL.
     */
    public static function getEndpoint(): string
    {
        return env('WHATSAPP_SERVER_URL', self::DEFAULT_GATEWAY_URL);
    }

    /**
     * Get the default WhatsApp API token.
     */
    public static function getAccessToken(): string
    {
        return env('WHATSAPP_ACCESS_TOKEN', self::DEFAULT_ACCESS_TOKEN);
    }

    /**
     * Get the default WhatsApp active instance ID.
     */
    public static function getInstanceId(): string
    {
        return env('WHATSAPP_INSTANCE_ID', self::DEFAULT_INSTANCE_ID);
    }

    /**
     * Get the target database for Universal 1-Node Server.
     */
    public static function getDatabaseName(): string
    {
        return env('WHATSAPP_DB', self::DEFAULT_DB_NAME);
    }

    /**
     * Connect to Sass Admin DB via PDO to query settings and agency configs.
     */
    protected static function getSassAdminPdo(): ?\PDO
    {
        static $pdo = null;
        if ($pdo !== null) {
            return $pdo;
        }

        $host = env('SASS_ADMIN_DB_HOST', env('DB_HOST', '127.0.0.1'));
        $port = env('SASS_ADMIN_DB_PORT', env('DB_PORT', '3306'));
        $cpanelUser = env('CPANEL_USER', 'nooryak');

        $userPairs = array_values(array_filter([
            ['user' => env('SASS_ADMIN_DB_USER') ?: env('DB_USERNAME_admin'), 'pass' => env('SASS_ADMIN_DB_PASS') ?: env('DB_PASSWORD_admin', '')],
            ['user' => config('database.connections.mysql.username'), 'pass' => config('database.connections.mysql.password')],
            ['user' => env('DB_USERNAME'), 'pass' => env('DB_PASSWORD', '')],
            ['user' => 'root', 'pass' => ''],
        ], function ($item) {
            return !empty($item['user']) || $item['user'] === '';
        }));

        $dbCandidates = array_values(array_unique(array_filter([
            env('SASS_ADMIN_DB'),
            env('DB_DATABASE_admin'),
            "{$cpanelUser}_sass_admindb",
            "{$cpanelUser}_Sass_admindb",
            'nooryak_sass_admindb',
            'nooryak_Sass_admindb',
            'sass_admin',
        ])));

        foreach ($dbCandidates as $dbName) {
            foreach ($userPairs as $pair) {
                try {
                    $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
                    $conn = new \PDO($dsn, $pair['user'], $pair['pass'], [
                        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_SILENT,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                        \PDO::ATTR_TIMEOUT            => 2,
                    ]);
                    $pdo = $conn;
                    return $pdo;
                } catch (\Throwable $e) {}
            }
        }

        return null;
    }

    /**
     * Dynamically resolve WhatsApp Gateway configuration:
     * 1. Check if current request belongs to a WhiteLabel Agency
     * 2. If agency has custom gateway/template settings, use them
     * 3. Fallback to SuperAdmin global settings in Sass_admin settings table
     * 4. Fallback to defaults
     */
    public static function getWhatsAppConfig(?object $agency = null): array
    {
        // 1. Resolve agency from host first if not passed
        if (!$agency && function_exists('getAgencyFromHost')) {
            try {
                $agency = getAgencyFromHost();
            } catch (\Throwable $e) {}
        }

        $agencyId = ($agency && isset($agency->id)) ? $agency->id : 'global';
        $cacheKey = 'whatsapp_gateway_config_' . $agencyId;
        $cached = Cache::get($cacheKey);
        if ($cached && is_array($cached)) {
            return $cached;
        }

        $pdo = self::getSassAdminPdo();
        $globalSettings = [];

        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT `key`, `value` FROM settings WHERE `key` LIKE 'whatsapp_%'");
                if ($stmt) {
                    $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);
                    foreach ($rows as $r) {
                        $globalSettings[$r->key] = $r->value;
                    }
                }
            } catch (\Throwable $e) {}
        }

        // Base configuration from global SuperAdmin settings
        $config = [
            'is_active' => isset($globalSettings['whatsapp_is_active']) ? (int)$globalSettings['whatsapp_is_active'] : 1,
            'otp_active' => isset($globalSettings['whatsapp_otp_active']) ? (int)$globalSettings['whatsapp_otp_active'] : 1,
            'credentials_active' => isset($globalSettings['whatsapp_credentials_active']) ? (int)$globalSettings['whatsapp_credentials_active'] : 1,
            'invoice_active' => isset($globalSettings['whatsapp_invoice_active']) ? (int)$globalSettings['whatsapp_invoice_active'] : 1,
            
            'endpoint' => !empty($globalSettings['whatsapp_gateway_url']) ? $globalSettings['whatsapp_gateway_url'] : self::getEndpoint(),
            'access_token' => !empty($globalSettings['whatsapp_access_token']) ? $globalSettings['whatsapp_access_token'] : self::getAccessToken(),
            'instance_id' => !empty($globalSettings['whatsapp_instance_id']) ? $globalSettings['whatsapp_instance_id'] : self::getInstanceId(),
            'db' => !empty($globalSettings['whatsapp_database']) ? $globalSettings['whatsapp_database'] : self::getDatabaseName(),

            'otp_template' => !empty($globalSettings['whatsapp_otp_template']) ? $globalSettings['whatsapp_otp_template'] : self::DEFAULT_OTP_TEMPLATE,
            'credentials_template' => !empty($globalSettings['whatsapp_credentials_template']) ? $globalSettings['whatsapp_credentials_template'] : self::DEFAULT_CREDENTIALS_TEMPLATE,
            'invoice_template' => !empty($globalSettings['whatsapp_invoice_template']) ? $globalSettings['whatsapp_invoice_template'] : self::DEFAULT_INVOICE_TEMPLATE,
            'agency_id' => $agency->id ?? null,
            'agency_name' => $agency->name ?? null,
        ];

        // Overlay agency custom settings if configured
        if ($agency) {
            $config['is_active'] = (isset($agency->is_whatsapp_active) && $agency->is_whatsapp_active !== null && $agency->is_whatsapp_active !== '') ? (int)$agency->is_whatsapp_active : 1;
            $config['otp_active'] = (isset($agency->is_whatsapp_otp_active) && $agency->is_whatsapp_otp_active !== null && $agency->is_whatsapp_otp_active !== '') ? (int)$agency->is_whatsapp_otp_active : 1;
            $config['credentials_active'] = (isset($agency->is_whatsapp_credentials_active) && $agency->is_whatsapp_credentials_active !== null && $agency->is_whatsapp_credentials_active !== '') ? (int)$agency->is_whatsapp_credentials_active : 1;
            $config['invoice_active'] = (isset($agency->is_whatsapp_invoice_active) && $agency->is_whatsapp_invoice_active !== null && $agency->is_whatsapp_invoice_active !== '') ? (int)$agency->is_whatsapp_invoice_active : 1;

            if (!empty($agency->whatsapp_gateway_url) && $agency->whatsapp_gateway_url !== 'http://localhost:8000') {
                $config['endpoint'] = $agency->whatsapp_gateway_url;
            } else {
                $config['endpoint'] = env('WHATSAPP_SERVER_URL', 'http://127.0.0.1:8000');
            }

            if (!empty($agency->whatsapp_access_token)) {
                $config['access_token'] = trim((string)$agency->whatsapp_access_token);
            }
            if (!empty($agency->whatsapp_instance_id)) {
                $config['instance_id'] = trim((string)$agency->whatsapp_instance_id);
            }

            $cpanelUser = env('CPANEL_USER', 'nooryak');
            $agencySlug = str_replace(['-', ' '], '_', strtolower($agency->slug ?? preg_replace('/[^a-zA-Z0-9]/', '', $agency->name ?? '')));
            $autoAgencyDb = "{$cpanelUser}_ps_{$agencySlug}_whatsapp";

            if (!empty($agency->whatsapp_database) && !in_array($agency->whatsapp_database, ['nooryak_whatautomation', 'replix_automation'])) {
                $config['db'] = $agency->whatsapp_database;
            } else {
                $config['db'] = $autoAgencyDb;
            }

            if (!empty($agency->whatsapp_otp_template)) {
                $config['otp_template'] = $agency->whatsapp_otp_template;
            }
            if (!empty($agency->whatsapp_credentials_template)) {
                $config['credentials_template'] = $agency->whatsapp_credentials_template;
            }
            if (!empty($agency->whatsapp_invoice_template)) {
                $config['invoice_template'] = $agency->whatsapp_invoice_template;
            }
            $config['agency_id'] = $agency->id ?? null;
            $config['agency_name'] = $agency->name ?? null;
        }

        try {
            Cache::put($cacheKey, $config, 30);
        } catch (\Throwable $e) {}

        return $config;
    }

    /**
     * Dynamically resolve currently active instance & access token:
     * 1. Asks the local Node server (/active_instance)
     * 2. Checks WhatsApp MySQL database (accounts table with status=1)
     * 3. Falls back to config or default
     */
    public static function resolveActiveCredentials(?string $customDb = null, ?string $customEndpoint = null): array
    {
        $resolvedDb = $customDb ?: self::getDatabaseName();
        $endpointBase = rtrim($customEndpoint ?: self::getEndpoint(), '/');

        $cacheKey = 'dynamic_whatsapp_credentials_' . md5($resolvedDb . $endpointBase);
        $cached = Cache::get($cacheKey);
        if (!empty($cached['instance_id']) && !empty($cached['access_token'])) {
            return $cached;
        }

        $resolvedInstance = null;
        $resolvedToken = null;

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

        try {
            Cache::put($cacheKey, $credentials, 60);
        } catch (\Throwable $cEx) {}

        return $credentials;
    }

    /**
     * Clean and format phone number into international numeric format.
     */
    public static function formatNumber($phone, string $defaultCountryCode = '91'): string
    {
        if (empty($phone)) {
            return '';
        }

        $digits = preg_replace('/[^0-9]/', '', (string) $phone);
        if ($digits === '') {
            return '';
        }

        $cleanCode = preg_replace('/[^0-9]/', '', $defaultCountryCode) ?: '91';

        if (strpos($digits, $cleanCode . '0') === 0 && strlen($digits) === (strlen($cleanCode) + 11)) {
            $digits = $cleanCode . substr($digits, strlen($cleanCode) + 1);
        }

        $trimmed = ltrim($digits, '0');

        if (strlen($trimmed) === 10) {
            return $cleanCode . $trimmed;
        }

        if (strpos($trimmed, $cleanCode) === 0 && strlen($trimmed) === (strlen($cleanCode) + 10)) {
            return $trimmed;
        }

        if (strlen($digits) === 11 && strpos($digits, '0') === 0) {
            return $cleanCode . substr($digits, 1);
        }

        return $trimmed ?: $digits;
    }

    /**
     * Send arbitrary message or media via WhatsApp Gateway.
     */
    public static function sendMessage(
        $phone,
        string $message,
        ?string $mediaUrl = null,
        ?string $filename = null,
        ?array $customConfig = null
    ): array {
        $formattedNumber = self::formatNumber($phone);
        if (empty($formattedNumber)) {
            Log::warning("WhatsAppNotificationService: Cannot send message, phone number is empty or invalid. Input: {$phone}");
            return [
                'success' => false,
                'message' => 'Invalid or empty phone number.',
            ];
        }

        $config = $customConfig ?: self::getWhatsAppConfig();

        if (empty($config['is_active'])) {
            Log::info("WhatsAppNotificationService: WhatsApp notifications are toggled OFF in settings. Skipping.");
            return [
                'success' => false,
                'skipped' => true,
                'message' => 'WhatsApp gateway is disabled in settings.',
            ];
        }

        $configuredEndpoint = rtrim($config['endpoint'] ?: self::getEndpoint(), '/');
        $endpointCandidates = array_values(array_unique(array_filter([
            $configuredEndpoint,
            'http://127.0.0.1:8000',
            'http://localhost:8000',
            env('WHATSAPP_SERVER_URL', 'http://127.0.0.1:8000'),
        ])));

        // Check if explicit instance and token were supplied by agency
        $accessToken = $config['access_token'] ?: self::getAccessToken();
        $instanceId = $config['instance_id'] ?: self::getInstanceId();
        $dbName = $config['db'] ?: self::getDatabaseName();

        // If instance was default or empty, attempt dynamic discovery
        if (empty($instanceId) || $instanceId === self::DEFAULT_INSTANCE_ID) {
            $activeCreds = self::resolveActiveCredentials($dbName, $configuredEndpoint);
            $accessToken = $activeCreds['access_token'] ?: $accessToken;
            $instanceId = $activeCreds['instance_id'] ?: $instanceId;
            $dbName = $activeCreds['db'] ?: $dbName;
        }

        $tokensToTry = array_values(array_unique(array_filter([
            $accessToken,
            str_starts_with($accessToken, 'token_') ? substr($accessToken, 6) : 'token_' . $accessToken,
        ])));

        $instancesToTry = array_values(array_unique(array_filter([
            $instanceId,
        ])));
        if (empty($instancesToTry)) {
            $instancesToTry = [self::getInstanceId(), 'E351BB42DC82'];
        }

        $lastResult = [
            'success' => false,
            'message' => 'No delivery attempt completed.',
        ];

        foreach ($endpointCandidates as $candEndpoint) {
            $sendPath = rtrim($candEndpoint, '/') . '/send_message';
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
        }

        return $lastResult;
    }

    /**
     * Send OTP Verification Code via WhatsApp using dynamic templates and placeholders.
     */
    public static function sendOtp($phone, $otp, string $productName = 'Ecom Builder', ?object $agency = null): array
    {
        $cleanProductName = trim($productName);
        $norm = strtolower($cleanProductName);
        if ($norm === 'ecombuilder' || $norm === 'launchshop' || $norm === 'ecom builder' || $norm === 'launchshop ecommerce') {
            $cleanProductName = 'Ecom Builder';
        } elseif ($norm === 'websitebuilder' || $norm === 'website builder' || $norm === 'websitebuilder ecommerce') {
            $cleanProductName = 'Website Builder';
        }

        $config = self::getWhatsAppConfig($agency);

        if (empty($config['is_active']) || empty($config['otp_active'])) {
            Log::info("WhatsAppNotificationService: OTP notifications toggled OFF in settings. Skipping.");
            return [
                'success' => false,
                'skipped' => true,
                'message' => 'WhatsApp OTP notifications are disabled.',
            ];
        }

        $template = !empty($config['otp_template']) ? $config['otp_template'] : self::DEFAULT_OTP_TEMPLATE;

        $productDisplayName = ($agency && !empty($agency->name)) ? $agency->name : (!empty($config['agency_name']) ? $config['agency_name'] : $cleanProductName);
        $message = str_replace(
            ['{otp}', '{product_name}', '{validity_minutes}'],
            [$otp, $productDisplayName, '5'],
            $template
        );

        return self::sendMessage($phone, $message, null, null, $config);
    }

    /**
     * Send Website Launch / Store Credentials via WhatsApp using dynamic templates and placeholders.
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
        $config = self::getWhatsAppConfig();

        if (empty($config['is_active']) || empty($config['credentials_active'])) {
            Log::info("WhatsAppNotificationService: Website Launch credentials notifications toggled OFF in settings. Skipping.");
            return [
                'success' => false,
                'skipped' => true,
                'message' => 'WhatsApp Store Credentials notifications are disabled.',
            ];
        }

        $template = !empty($config['credentials_template']) ? $config['credentials_template'] : self::DEFAULT_CREDENTIALS_TEMPLATE;

        $message = str_replace(
            ['{user_name}', '{store_name}', '{email}', '{password}', '{store_url}', '{login_url}', '{product_name}', '{plan_name}'],
            [$storeName, $storeName, $email, $password, $storeLiveLink, $loginLink, $productName, $planName ?: 'Standard Plan'],
            $template
        );

        return self::sendMessage($phone, $message, null, null, $config);
    }

    /**
     * Send Payment Receipt / Invoice Confirmation via WhatsApp using dynamic templates and placeholders.
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
        $config = self::getWhatsAppConfig();

        if (empty($config['is_active']) || empty($config['invoice_active'])) {
            Log::info("WhatsAppNotificationService: Invoice notifications toggled OFF in settings. Skipping.");
            return [
                'success' => false,
                'skipped' => true,
                'message' => 'WhatsApp Invoice notifications are disabled.',
            ];
        }

        $template = !empty($config['invoice_template']) ? $config['invoice_template'] : self::DEFAULT_INVOICE_TEMPLATE;

        $message = str_replace(
            ['{user_name}', '{invoice_id}', '{amount}', '{plan_title}', '{payment_method}', '{invoice_url}', '{product_name}'],
            ['Customer', $invoiceId, $amount, $planTitle ?: $productName, $paymentMethod ?: 'Online Payment', $invoiceUrl ?: '', $productName],
            $template
        );

        $filename = !empty($invoiceId) ? "Invoice_{$invoiceId}.pdf" : "Invoice.pdf";

        return self::sendMessage($phone, $message, $invoiceUrl, $filename, $config);
    }
}
