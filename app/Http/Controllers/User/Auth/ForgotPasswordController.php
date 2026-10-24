<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\Language;
use App\Models\Seo;
use App\Models\User;
use DB;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return
     */
    public function showLinkRequestForm()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $bs = $currentLang->basic_setting;

        Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
        Config::set('captcha.secret', $bs->google_recaptcha_secret_key);

        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        return view('front.auth.passwords.email', $data);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $input = trim($request->email ?? $request->input('phone', ''));
        if (empty($input)) {
            return redirect()->back()->withInput()->with('err', __('Please enter your registered email address or phone number.'));
        }

        // 1. Lookup user in LaunchShop users table
        $user = User::where('email', $input)
            ->orWhere('phone', $input)
            ->orWhere('username', $input)
            ->first();

        if (!$user && preg_replace('/[^0-9]/', '', $input)) {
            $digits = preg_replace('/[^0-9]/', '', $input);
            $user = User::where('phone', $digits)
                ->orWhere('phone', 'LIKE', '%' . substr($digits, -10))
                ->first();
        }

        // 2. Cross-suite fallback: Check Website Builder wb_customers table
        $isWbUser = false;
        $wbCustomer = null;
        if (!$user) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                    $wbCustomer = \App\Models\WebsiteBuilder\WbCustomer::where('email', $input)
                        ->orWhere('phone', $input)
                        ->first();
                    if ($wbCustomer) {
                        $isWbUser = true;
                    }
                }
            } catch (\Throwable $e) {}
        }

        if (!$user && !$wbCustomer) {
            return redirect()->back()->withInput()->with('err', __('We could not find an account associated with that email or phone number.'));
        }

        // 3. Generate reset URL
        if ($isWbUser && $wbCustomer) {
            $token = \Illuminate\Support\Str::random(60);
            $wbCustomer->reset_token = $token;
            $wbCustomer->reset_token_expires_at = now()->addMinutes(60);
            $wbCustomer->save();
            $resetUrl = route('website-builder.password.reset', ['token' => $token, 'email' => $wbCustomer->email]);
            $recipientEmail = $wbCustomer->email;
            $customerName = $wbCustomer->name ?: 'Customer';
            $customerPhone = $wbCustomer->phone ?? null;
            $productName = 'Website Builder';
        } else {
            $token = Password::broker('users')->createToken($user);
            $resetUrl = route('user.reset.password.form', ['token' => $token, 'email' => $user->email]);
            $recipientEmail = $user->email;
            $customerName = $user->username ?: ($user->first_name ?: 'Customer');
            $customerPhone = $user->phone ?? null;
            $productName = 'LaunchShop';
        }

        // 4. Primary Dispatch: WhatsApp to registered mobile number
        $waSent = false;
        if (!empty($customerPhone)) {
            $waMessage = "🔐 *Password Reset Request - {$productName}*\n\n" .
                         "Hello *{$customerName}*,\n\n" .
                         "We received a request to reset your password. Click the link below to set your new password:\n\n" .
                         "🔗 {$resetUrl}\n\n" .
                         "⏱️ This reset link is valid for 60 minutes.\n" .
                         "If you did not request a password reset, you can safely ignore this message.\n\n" .
                         "– Team {$productName} 🚀";

            try {
                $res = \App\Services\WhatsAppNotificationService::sendMessage($customerPhone, $waMessage);
                if (!empty($res['success'])) {
                    $waSent = true;
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("LaunchShop WhatsApp reset dispatch error: " . $e->getMessage());
                $waSent = false;
            }
        }

        // 5. Email Dispatch: Always send or fallback if WhatsApp failed/crashed
        $mailSent = false;
        $emailBody = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff;'>
                <div style='text-align: center; margin-bottom: 24px;'>
                    <h2 style='color: #0e1b3d; margin: 0;'>{$productName}</h2>
                    <p style='color: #64748b; font-size: 14px;'>Account Recovery & Password Reset</p>
                </div>
                <h3 style='color: #1e293b;'>Hello {$customerName},</h3>
                <p style='color: #475569; font-size: 15px; line-height: 1.6;'>
                    We received a request to reset the password for your {$productName} account. Click the button below to choose a new password:
                </p>
                <div style='text-align: center; margin: 32px 0;'>
                    <a href='{$resetUrl}' style='background: #ff5a2c; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block;'>Reset Your Password</a>
                </div>
                <p style='color: #64748b; font-size: 13px; line-height: 1.6;'>
                    Or copy and paste this link into your browser:<br>
                    <a href='{$resetUrl}' style='color: #ff5a2c; word-break: break-all;'>{$resetUrl}</a>
                </p>
                <hr style='border: none; border-top: 1px solid #f1f5f9; margin: 24px 0;'>
                <p style='color: #94a3b8; font-size: 12px; margin: 0;'>
                    This reset link will expire in 60 minutes. If you did not request this, please ignore this email.
                </p>
            </div>
        ";

        try {
            $info = DB::table('basic_extendeds')->first();
            $smtpHost = ($info && !empty($info->smtp_host) && !str_contains($info->smtp_host, 'metroshop.in') && !str_contains($info->smtp_host, 'nooryak.in'))
                ? $info->smtp_host
                : env('MAIL_HOST', 'mail.saasreselling.com');
            $smtpPort = ($info && !empty($info->smtp_port)) ? $info->smtp_port : env('MAIL_PORT', 465);
            $smtpEnc  = ($info && !empty($info->encryption)) ? $info->encryption : env('MAIL_ENCRYPTION', 'ssl');
            $smtpUser = ($info && !empty($info->smtp_username) && !str_contains($info->smtp_username, 'metroshop.in'))
                ? $info->smtp_username
                : env('MAIL_USERNAME', 'admin@saasreselling.com');
            $smtpPass = ($info && !empty($info->smtp_password)) ? $info->smtp_password : env('MAIL_PASSWORD', 'Admin@nooryak');
            $fromMail = ($info && !empty($info->from_mail) && !str_contains($info->from_mail, 'metroshop.in'))
                ? $info->from_mail
                : env('MAIL_FROM_ADDRESS', 'admin@saasreselling.com');
            $fromName = ($info && !empty($info->from_name)) ? $info->from_name : $productName;

            $mailData = [
                'smtp_status'   => $info->is_smtp ?? 1,
                'smtp_host'     => $smtpHost,
                'smtp_port'     => $smtpPort,
                'encryption'    => $smtpEnc,
                'smtp_username' => $smtpUser,
                'smtp_password' => $smtpPass,
                'from_mail'     => $fromMail,
                'from_name'     => $fromName,
                'recipient'     => $recipientEmail,
                'subject'       => "Reset Password - {$productName}",
                'body'          => $emailBody,
            ];
            $mailSent = BasicMailer::sendMail($mailData);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("LaunchShop BasicMailer dispatch error: " . $e->getMessage());
        }

        // Native PHP mail() fail-safe fallback (essential on cPanel servers)
        if (!$mailSent) {
            try {
                $info = DB::table('basic_extendeds')->first();
                $fromMail = ($info && !empty($info->from_mail) && !str_contains($info->from_mail, 'metroshop.in'))
                    ? $info->from_mail
                    : env('MAIL_FROM_ADDRESS', 'admin@saasreselling.com');
                $fromName = ($info && !empty($info->from_name)) ? $info->from_name : $productName;

                $headers  = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                $headers .= "From: {$fromName} <{$fromMail}>\r\n";
                $headers .= "Reply-To: {$fromMail}\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                if (@mail($recipientEmail, "Reset Password - {$productName}", $emailBody, $headers)) {
                    $mailSent = true;
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("LaunchShop native mail() fallback error: " . $e->getMessage());
            }
        }

        // 6. Flash user-friendly status message adhering to prompt
        if ($waSent && $mailSent) {
            $flashMsg = __('Password reset link has been sent to your registered mobile and email.');
        } elseif ($waSent) {
            $flashMsg = __('Password reset link has been sent to your registered WhatsApp mobile (+:number).', ['number' => $customerPhone]);
        } else {
            $flashMsg = __('Password reset link has been sent to your registered email (:email).', ['email' => $recipientEmail]);
        }

        return redirect()->back()->with('status', $flashMsg)->with('message', $flashMsg);
    }

    public function forgetPasswordMail(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('users');
    }
}
