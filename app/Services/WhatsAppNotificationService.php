<?php

namespace App\Services;

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
        // Try WHATSAPP_ACCESS_TOKEN or fallback to default team token
        return env('WHATSAPP_ACCESS_TOKEN', '6005c0f6de385fe7');
    }

    /**
     * Get the default WhatsApp active instance ID.
     */
    public static function getInstanceId(): string
    {
        // Default to the active instance ID connected to profile (e.g. E351BB42DC82)
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
     * Passes credentials via both query parameters and post body, with automatic retry for token prefixes.
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
        $accessToken  = self::getAccessToken();
        $instanceId   = self::getInstanceId();
        $dbName       = self::getDatabaseName();

        // Build tokens to try (try both raw token and token_ prefix if needed)
        $tokensToTry = [$accessToken];
        if (str_starts_with($accessToken, 'token_')) {
            $tokensToTry[] = substr($accessToken, 6);
        } else {
            $tokensToTry[] = 'token_' . $accessToken;
        }

        // Instances to try (configured instance first, then fallback to E351BB42DC82)
        $instancesToTry = [$instanceId];
        if ($instanceId !== 'E351BB42DC82') {
            $instancesToTry[] = 'E351BB42DC82';
        }

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
     */
    public static function sendOtp($phone, $otp, string $productName = 'Ecombuilder'): array
    {
        $message = "🔐 *OTP Verification Code*\n\n"
            . "Your verification OTP code is *{$otp}* for *{$productName}*.\n\n"
            . "⏱ This code is valid for *5 minutes*.\n"
            . "⚠️ Please do not share this code with anyone.";

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