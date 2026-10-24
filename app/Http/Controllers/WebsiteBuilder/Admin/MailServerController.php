<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbLandingSetting;
use App\Models\BasicExtended;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailServerController extends Controller
{
    public function edit()
    {
        $setting = WbLandingSetting::getSettings();
        return view('website_builder.admin.mail_server', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'smtp_host'         => 'required|string|max:255',
            'smtp_port'         => 'required|numeric',
            'smtp_encryption'   => 'required|string|max:20',
            'smtp_username'     => 'required|string|max:255',
            'smtp_password'     => 'required|string|max:255',
            'smtp_from_address' => 'required|email|max:255',
            'smtp_from_name'    => 'required|string|max:255',
        ]);

        $setting = WbLandingSetting::getSettings();
        $setting->is_smtp           = $request->has('is_smtp') ? '1' : ($request->input('is_smtp', '1'));
        $setting->smtp_host         = trim($request->input('smtp_host', 'mail.saasreselling.com'));
        $setting->smtp_port         = trim($request->input('smtp_port', '465'));
        $setting->smtp_encryption   = trim($request->input('smtp_encryption', 'ssl'));
        $setting->smtp_username     = trim($request->input('smtp_username', 'admin@saasreselling.com'));
        $setting->smtp_password     = trim($request->input('smtp_password', 'Admin@nooryak'));
        $setting->smtp_from_address = trim($request->input('smtp_from_address', 'admin@saasreselling.com'));
        $setting->smtp_from_name    = trim($request->input('smtp_from_name', 'Website Builder'));
        $setting->save();

        // Also sync to basic_extendeds table if available
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('basic_extendeds')) {
                $be = BasicExtended::first();
                if ($be) {
                    $be->is_smtp        = (int)$setting->is_smtp;
                    $be->smtp_host      = $setting->smtp_host;
                    $be->smtp_port      = $setting->smtp_port;
                    $be->encryption     = $setting->smtp_encryption;
                    $be->smtp_username  = $setting->smtp_username;
                    $be->smtp_password  = $setting->smtp_password;
                    $be->from_mail      = $setting->smtp_from_address;
                    $be->from_name      = $setting->smtp_from_name;
                    $be->save();
                }
            }
        } catch (\Throwable $e) {
            Log::warning("BasicExtended sync notice: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Mail Server (SMTP) credentials updated successfully!');
    }

    public function testMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        $toEmail = $request->input('test_email');
        $setting = WbLandingSetting::getSettings();

        $smtpConfig = [
            'transport'  => 'smtp',
            'host'       => $setting->smtp_host ?: 'mail.saasreselling.com',
            'port'       => (int)($setting->smtp_port ?: 465),
            'encryption' => $setting->smtp_encryption ?: 'ssl',
            'username'   => $setting->smtp_username ?: 'admin@saasreselling.com',
            'password'   => $setting->smtp_password ?: 'Admin@nooryak',
            'timeout'    => 10,
        ];

        Config::set('mail.mailers.wb_test_smtp', $smtpConfig);
        Config::set('mail.from.address', $setting->smtp_from_address ?: 'admin@saasreselling.com');
        Config::set('mail.from.name', $setting->smtp_from_name ?: 'Website Builder');

        $subject = 'Website Builder SMTP Test Mail - ' . date('Y-m-d H:i:s');
        $body = "<h2>Website Builder Mail Server Test</h2>"
              . "<p>This is a test email sent from Website Builder Super Admin Mail Server configuration.</p>"
              . "<p><strong>SMTP Host:</strong> {$smtpConfig['host']}<br>"
              . "<strong>SMTP Port:</strong> {$smtpConfig['port']}<br>"
              . "<strong>Encryption:</strong> {$smtpConfig['encryption']}<br>"
              . "<strong>Username:</strong> {$smtpConfig['username']}<br>"
              . "<strong>From Name:</strong> " . ($setting->smtp_from_name ?: 'Website Builder') . "<br>"
              . "<strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>"
              . "<p style='color:#16a34a;font-weight:bold;'>✔ Your mail server is functioning properly!</p>";

        $sentViaSmtp = false;
        $errorMsg = null;

        try {
            Mail::mailer('wb_test_smtp')->html($body, function ($m) use ($toEmail, $subject, $setting) {
                $m->to($toEmail)
                  ->subject($subject)
                  ->from($setting->smtp_from_address ?: 'admin@saasreselling.com', $setting->smtp_from_name ?: 'Website Builder');
            });
            $sentViaSmtp = true;
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            Log::warning("Website Builder test email SMTP error: " . $errorMsg);
        }

        // Native PHP mail() fallback
        $sentViaNative = false;
        if (!$sentViaSmtp) {
            $headers = "MIME-Version: 1.0\r\n"
                     . "Content-type: text/html; charset=UTF-8\r\n"
                     . "From: " . ($setting->smtp_from_name ?: 'Website Builder') . " <" . ($setting->smtp_from_address ?: 'admin@saasreselling.com') . ">\r\n"
                     . "Reply-To: " . ($setting->smtp_from_address ?: 'admin@saasreselling.com') . "\r\n"
                     . "X-Mailer: PHP/" . phpversion();
            $sentViaNative = @mail($toEmail, $subject, $body, $headers);
        }

        if ($sentViaSmtp) {
            return redirect()->back()->with('success', "✔ Test email successfully sent to {$toEmail} via SMTP ({$smtpConfig['host']})!");
        } elseif ($sentViaNative) {
            return redirect()->back()->with('success', "✔ Test email successfully sent to {$toEmail} via local mail engine (SMTP Notice: " . substr($errorMsg, 0, 80) . ")!");
        } else {
            return redirect()->back()->with('error', "Failed to send test email. Reason: " . ($errorMsg ?: 'Unknown mail failure'));
        }
    }
}
