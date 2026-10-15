<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Get the default WhatsApp gateway endpoint.
     */
    public static function getEndpoint(): string
    {
        return env('WHATSAPP_SERVER_URL', 'http://localhost:8000/send_message');
    }

    /**
     * Get the default WhatsApp API token.
     */
    public static function getAccessToken(): string
    {
        return env('WHATSAPP_ACCESS_TOKEN', 'token_6005c0f6de385fe7');
    }

    /**
     * Get the default WhatsApp active instance ID.
     */
    public static function getInstanceId(): string
    {
        return env('WHATSAPP_INSTANCE_ID', '5B5B74F98319');
    }

    /**
     * Clean and format phone number into international numeric format (e.g. 918667702473).
     */
    public static function formatNumber($phone, string $defaultCountryCode = '91'): string
    {
        if (empty($phone)) {
            return '';
        }

        $clean = preg_replace('/[^0-9]/', '', (string) $phone);
        if ($clean === '') {
            return '';
        }

        // Remove leading single zero (e.g. 08667702473 -> 8667702473)
        $clean = ltrim($clean, '0');

        $cleanDefaultCode = preg_replace('/[^0-9]/', '', $defaultCountryCode) ?: '91';

        // 10 digits standard Indian phone number without country code
        if (strlen($clean) === 10) {
            $clean = $cleanDefaultCode . $clean;
        }

        return $clean;
    }

    /**
     * Core method to send text or media message via WhatsApp Gateway.
     */
    public static function sendMessage($number, string $message, ?string $mediaUrl = null, ?string $filename = null): array
    {
        $formattedNumber = self::formatNumber($number);
        if (empty($formattedNumber) || strlen($formattedNumber) < 8) {
            return [
                'success' => false,
                'message' => 'Invalid or missing phone number.',
            ];
        }

        $endpoint    = self::getEndpoint();
        $accessToken = self::getAccessToken();
        $instanceId  = self::getInstanceId();

        $payload = [
            'access_token' => $accessToken,
            'instance_id'  => $instanceId,
            'number'       => $formattedNumber,
            'message'      => $message,
        ];

        if (!empty($mediaUrl)) {
            $payload['media_url'] = $mediaUrl;
            if (!empty($filename)) {
                $payload['filename'] = $filename;
            }
        }

        $logPrefix = "[" . date('Y-m-d H:i:s') . "] [WhatsAppGateway] Number: {$formattedNumber} | ";

        try {
            $response = Http::asForm()
                ->timeout(12)
                ->withoutVerifying()
                ->post($endpoint, $payload);

            $status = $response->status();
            $body = $response->json();
            $bodyStr = $response->body();

            $isSuccess = $response->successful() && (
                ($body['status'] ?? '') === 'success' ||
                ($body['status'] ?? '') === 1 ||
                ($body['success'] ?? false) === true
            );

            $logLine = $logPrefix . "Status: {$status} | Success: " . ($isSuccess ? 'YES' : 'NO') . " | Response: {$bodyStr}\n";

            try {
                @file_put_contents(storage_path('logs/whatsapp_gateway.log'), $logLine, FILE_APPEND);
            } catch (\Throwable $fileEx) {}

            Log::info("WhatsApp Gateway Response for {$formattedNumber}: {$bodyStr}");

            return [
                'success'  => $isSuccess,
                'status'   => $status,
                'response' => $body,
                'body'     => $bodyStr,
            ];
        } catch (\Throwable $e) {
            $errLine = $logPrefix . "EXCEPTION: " . $e->getMessage() . "\n";
            try {
                @file_put_contents(storage_path('logs/whatsapp_gateway.log'), $errLine, FILE_APPEND);
            } catch (\Throwable $fileEx) {}

            Log::error("WhatsApp Gateway Exception for {$formattedNumber}: " . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
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