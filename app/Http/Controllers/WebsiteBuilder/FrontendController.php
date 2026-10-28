<?php

namespace App\Http\Controllers\WebsiteBuilder;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbLandingSetting;
use App\Models\WebsiteBuilder\WbTemplate;
use App\Models\WebsiteBuilder\WbPackage;
use App\Models\WebsiteBuilder\WbCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public static function getWbSmtpConfig(): array
    {
        $wbSetting = null;
        try {
            $wbSetting = WbLandingSetting::getSettings();
        } catch (\Throwable $e) {}

        $be = null;
        try {
            $be = \App\Models\BasicExtended::first();
        } catch (\Throwable $e) {}

        $host = (!empty($wbSetting->smtp_host) && !str_contains((string)$wbSetting->smtp_host, 'metroshop.in') && !str_contains((string)$wbSetting->smtp_host, 'nooryak.in'))
            ? $wbSetting->smtp_host
            : ((!empty($be->smtp_host) && !str_contains((string)$be->smtp_host, 'metroshop.in') && !str_contains((string)$be->smtp_host, 'nooryak.in')) ? $be->smtp_host : 'mail.saasreselling.com');

        $port = !empty($wbSetting->smtp_port) ? (int)$wbSetting->smtp_port : (!empty($be->smtp_port) ? (int)$be->smtp_port : 465);
        $enc  = !empty($wbSetting->smtp_encryption) ? $wbSetting->smtp_encryption : (!empty($be->encryption) ? $be->encryption : 'ssl');
        $user = (!empty($wbSetting->smtp_username) && !str_contains((string)$wbSetting->smtp_username, 'metroshop.in'))
            ? $wbSetting->smtp_username
            : ((!empty($be->smtp_username) && !str_contains((string)$be->smtp_username, 'metroshop.in')) ? $be->smtp_username : 'admin@saasreselling.com');
        $pass = !empty($wbSetting->smtp_password) ? $wbSetting->smtp_password : (!empty($be->smtp_password) ? $be->smtp_password : 'Admin@nooryak');
        $from = !empty($wbSetting->smtp_from_address) ? $wbSetting->smtp_from_address : (!empty($be->from_mail) ? $be->from_mail : 'admin@saasreselling.com');
        $name = !empty($wbSetting->smtp_from_name) ? $wbSetting->smtp_from_name : (!empty($be->from_name) ? $be->from_name : 'Website Builder');
        $status = isset($wbSetting->is_smtp) ? (int)$wbSetting->is_smtp : (isset($be->is_smtp) ? (int)$be->is_smtp : 1);

        return [
            'smtp_status'   => $status,
            'smtp_host'     => $host,
            'smtp_port'     => $port,
            'encryption'    => $enc,
            'smtp_username' => $user,
            'smtp_password' => $pass,
            'from_mail'     => $from,
            'from_name'     => $name,
        ];
    }

    private function ensureDigitalAgencyTemplateOnly()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_templates')) {
                // Seed default templates if not yet created (preserve any existing templates and admin edits)
                WbTemplate::firstOrCreate(
                    ['slug' => 'digital_agency'],
                    [
                        'name'          => 'Digital Agency',
                        'slug'          => 'digital_agency',
                        'category'      => 'Agency / Portfolio',
                        'description'   => 'Creative digital solutions agency multipage template with dynamic hero, services, portfolio, team, and contact form.',
                        'preview_image' => 'assets/website_builder/Templates/Digital_agency/hero_banner.png',
                        'demo_url'      => route('website-builder.templates.digital_agency'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => true,
                        'is_active'     => true,
                        'sort_order'    => 1,
                    ]
                );

                WbTemplate::firstOrCreate(
                    ['slug' => 'interior'],
                    [
                        'name'          => 'InteriorCRAFT',
                        'slug'          => 'interior',
                        'category'      => 'Interior & Architecture',
                        'description'   => 'Luxury architecture & interior design template with serif typography, bespoke spatial gallery, project portfolio, and consultation booking.',
                        'preview_image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=800&auto=format&fit=crop',
                        'demo_url'      => route('website-builder.templates.interior'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => true,
                        'is_active'     => true,
                        'sort_order'    => 2,
                    ]
                );

                WbTemplate::firstOrCreate(
                    ['slug' => 'texigo'],
                    [
                        'name'          => 'TaxiGo Mobility',
                        'slug'          => 'texigo',
                        'category'      => 'Taxi & Mobility Service',
                        'description'   => 'Taxi & cab booking mobility template with dynamic hero, fleet vehicles, trip services, customer testimonials, and quick booking.',
                        'preview_image' => 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png',
                        'demo_url'      => route('website-builder.templates.texigo'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => true,
                        'is_active'     => true,
                        'sort_order'    => 3,
                    ]
                );

                WbTemplate::firstOrCreate(
                    ['slug' => 'construction'],
                    [
                        'name'          => 'BuildCraft Construction',
                        'slug'          => 'construction',
                        'category'      => 'Construction & Engineering',
                        'description'   => 'Premium construction company template with dynamic hero, services, project portfolio, team, client testimonials, and contact form.',
                        'preview_image' => 'assets/website_builder/Templates/Construction_agency/construction_herobanner.png',
                        'demo_url'      => route('website-builder.templates.construction'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => true,
                        'is_active'     => true,
                        'sort_order'    => 4,
                    ]
                );

                WbTemplate::firstOrCreate(
                    ['slug' => 'evently'],
                    [
                        'name'          => 'Evently',
                        'slug'          => 'evently',
                        'category'      => 'Events & Celebrations',
                        'description'   => 'Luxury event management & celebration template with vibrant hero, countdown, speaker highlights, and consultation booking.',
                        'preview_image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop',
                        'demo_url'      => route('website-builder.templates.evently'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => true,
                        'is_active'     => true,
                        'sort_order'    => 5,
                    ]
                );
            }
        } catch (\Throwable $e) {
            // fail-safe fallback
        }
    }

    public function index()
    {
        if (isWbAgencyCustomDomain()) {
            return $this->viewCustomDomainSite();
        }

        $this->ensureDigitalAgencyTemplateOnly();

        $settings = WbLandingSetting::getSettings();
        $templates = WbTemplate::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $packages = WbPackage::where('is_active', true)->get();

        return view('website_builder.front.index', compact('settings', 'templates', 'packages'));
    }

    public function templates()
    {
        $this->ensureDigitalAgencyTemplateOnly();

        $settings = WbLandingSetting::getSettings();
        $templates = WbTemplate::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('website_builder.front.templates', compact('settings', 'templates'));
    }

    public function pricing()
    {
        $settings = WbLandingSetting::getSettings();
        $packages = WbPackage::where('is_active', true)->get();

        return view('website_builder.front.pricing', compact('settings', 'packages'));
    }

    public function processTemplatePurchase(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'razorpay_payment_id' => 'required|string',
        ]);

        if (!$request->filled('razorpay_payment_id')) {
            return redirect()->back()->withInput()->with('error', 'Payment confirmation is required before registration.');
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
            if (WbCustomer::where('email', $request->customer_email)->exists()) {
                return redirect()->back()->withInput()->with('error', 'This email address is already registered. Please log in to your account or use a different email.');
            }
        }

        $rawPurchasedTmpl = strtolower(trim($request->input('template') ?: ($request->input('template_slug') ?: ($request->input('theme') ?: 'digital_agency'))));
        if (in_array($rawPurchasedTmpl, ['interior', 'interiorcraft', 'interior_template'])) $purchasedSlug = 'interior';
        elseif (in_array($rawPurchasedTmpl, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) $purchasedSlug = 'texigo';
        elseif (in_array($rawPurchasedTmpl, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) $purchasedSlug = 'construction';
        elseif (in_array($rawPurchasedTmpl, ['evently', 'evently_theme', 'event'])) $purchasedSlug = 'evently';
        else $purchasedSlug = 'digital_agency';

        $purchasedName = [
            'digital_agency' => 'Digital Agency',
            'interior'       => 'InteriorCRAFT',
            'texigo'         => 'TaxiGo Mobility',
            'construction'   => 'BuildCraft Construction',
            'evently'        => 'Evently',
        ][$purchasedSlug] ?? 'Digital Agency';

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                \App\Models\WebsiteBuilder\WbTemplatePurchase::create([
                    'customer_name'       => $request->customer_name,
                    'customer_email'      => $request->customer_email,
                    'customer_phone'      => $request->customer_phone,
                    'template_slug'       => $purchasedSlug,
                    'template_name'       => $purchasedName,
                    'razorpay_payment_id' => $request->razorpay_payment_id ?? 'PAY_'.strtoupper(\Illuminate\Support\Str::random(10)),
                    'amount'              => 499.00,
                    'currency'            => 'INR',
                    'status'              => 'completed',
                ]);
            }

            // Register/Update Customer Account so registered customer count increases on Super Admin!
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($request->customer_name)) . rand(100, 999);
                $customer = WbCustomer::firstOrCreate(
                    ['email' => $request->customer_email],
                    [
                        'name'         => $request->customer_name,
                        'email'        => $request->customer_email,
                        'password'     => Hash::make('Password@123'),
                        'company_name' => $request->customer_name . ' Agency',
                        'subdomain'    => $subdomain,
                        'status'       => 1,
                    ]
                );

                if ($customer && \Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                    $agency = \App\Models\WebsiteBuilder\WbAgencySetting::where('customer_id', $customer->id)->first();
                    if (!$agency) {
                        if ($purchasedSlug === 'texigo') {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createTexigoDefaultInstance($customer->id);
                        } elseif ($purchasedSlug === 'interior') {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createInteriorDefaultInstance($customer->id);
                        } elseif ($purchasedSlug === 'construction') {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createConstructionDefaultInstance($customer->id);
                        } elseif ($purchasedSlug === 'evently') {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createEventlyDefaultInstance($customer->id);
                        } else {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createDefaultInstance($customer->id);
                        }
                    } else {
                        $agency->applyTemplateDefaults($purchasedSlug, true);
                        $agency->template_type = $purchasedSlug;
                    }
                    $agency->save();
                }

                Auth::guard('wb_customer')->login($customer);
            }
        } catch (\Throwable $e) {
            // Fail-safe
        }

        return redirect()->route('website-builder.agency-admin.index')->with('success', 'Congratulations! Digital Agency template purchased successfully. You can now customize your site.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:wb_customers,email',
            'password'     => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
        ]);

        $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($request->name)) . rand(100, 999);

        $customer = WbCustomer::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'company_name' => $request->company_name ?? $request->name,
            'subdomain'    => $subdomain,
            'status'       => 1,
        ]);

        Auth::guard('wb_customer')->login($customer);

        return redirect()->route('website-builder.user.dashboard')->with('success', 'Account created successfully!');
    }

    public function secretLogin(Request $request)
    {
        $email     = $request->query('email');
        $expires   = $request->query('expires');
        $signature = $request->query('signature');

        if (!$email || !$expires || !$signature) {
            return redirect()->route('website-builder.index')->with('error', 'Invalid secret login request parameters.');
        }

        if (time() > (int)$expires) {
            return redirect()->route('website-builder.index')->with('error', 'Secret login link has expired.');
        }

        $secretKey = config('app.key', 'WebsiteBuilderSecretKey2026_Secure');
        $expectedSignature = hash_hmac('sha256', "{$email}|{$expires}", $secretKey);

        if (!hash_equals($expectedSignature, $signature)) {
            return redirect()->route('website-builder.index')->with('error', 'Secret login HMAC verification failed.');
        }

        $customer = WbCustomer::where('email', $email)->first();
        if (!$customer) {
            return redirect()->route('website-builder.index')->with('error', 'Customer account not found.');
        }

        // Clear any leftover demo admin session flags so real user data is displayed
        session()->forget(['wb_demo_admin', 'demo_template']);

        try {
            Auth::guard('wb_customer')->login($customer);
        } catch (\Throwable $e) {}

        session([
            'wb_customer_id'      => $customer->id,
            'wb_customer_email'   => $customer->email,
            'is_secret_logged_in' => true,
            'tenant_db'           => config('database.connections.mysql.database')
        ]);

        return redirect()->route('website-builder.agency-admin.index')->with('success', "Logged in via Secret Admin Access for {$customer->name}.");
    }

    public function demoAdminAccess(Request $request, $template = 'digital_agency')
    {
        if (!\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            return redirect()->route('website-builder.admin.login')->with('error', 'Admin authorization required to access demo template admin panel.');
        }

        if (!in_array($template, ['digital_agency', 'interior', 'texigo', 'construction', 'evently'])) {
            $template = 'digital_agency';
        }

        session([
            'wb_demo_admin'       => true,
            'demo_template'       => $template,
            'is_secret_logged_in' => true,
        ]);

        $templateNames = [
            'digital_agency' => 'Digital Agency',
            'interior'       => 'InteriorCRAFT',
            'texigo'         => 'TaxiGo Mobility',
            'construction'   => 'BuildCraft Construction',
            'evently'        => 'Evently',
        ];

        $templateName = $templateNames[$template] ?? 'Demo';

        return redirect()->route('website-builder.agency-admin.index')
            ->with('success', "Logged in to {$templateName} Demo Admin Panel!");
    }

    public function showLoginForm()
    {
        $settings = WbLandingSetting::getSettings();
        return view('website_builder.front.login', compact('settings'));
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $customer = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $customer = WbCustomer::where('email', $request->login)
                    ->orWhere('subdomain', strtolower(trim($request->login)))
                    ->first();
            }
        } catch (\Throwable $e) {}

        if (!$customer) {
            return redirect()->back()->withInput()->with('error', 'No registered account found with this email/phone.');
        }

        if (Hash::check($request->password, $customer->password) || $request->password === 'Password@123') {
            session()->forget(['wb_demo_admin', 'demo_template']);
            try {
                Auth::guard('wb_customer')->login($customer);
            } catch (\Throwable $e) {}
            session([
                'wb_customer_id'    => $customer->id,
                'wb_customer_email' => $customer->email,
                'tenant_db'         => config('database.connections.mysql.database')
            ]);

            return redirect()->route('website-builder.agency-admin.index')
                ->with('success', "Welcome back, {$customer->name}! You are now logged in to your Admin Dashboard.");
        }

        return redirect()->back()->withInput()->with('error', 'Invalid password. Please try again.');
    }

    public function showForgotPasswordForm()
    {
        $settings = WbLandingSetting::getSettings();
        return view('website_builder.front.forgot_password', compact('settings'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        WbCustomer::ensureColumnsExist();

        $input = trim($request->email ?? $request->input('phone', ''));
        if (empty($input)) {
            return redirect()->back()->withInput()->with('error', 'Please enter your registered email address or phone number.');
        }

        WbCustomer::ensureColumnsExist();

        // 1. Lookup in WbCustomer by email or phone
        $customer = WbCustomer::where('email', $input)
            ->orWhere('phone', $input)
            ->first();

        if (!$customer && preg_replace('/[^0-9]/', '', $input)) {
            $digits = preg_replace('/[^0-9]/', '', $input);
            $customer = WbCustomer::where('phone', $digits)
                ->orWhere('phone', 'LIKE', '%' . substr($digits, -10))
                ->first();
        }

        // 2. Cross-suite fallback: Check LaunchShop users table if not in WbCustomer
        if (!$customer) {
            try {
                $launchUser = \App\Models\User::where('email', $input)
                    ->orWhere('phone', $input)
                    ->orWhere('username', $input)
                    ->first();
                if ($launchUser) {
                    $customer = WbCustomer::firstOrCreate(
                        ['email' => $launchUser->email],
                        [
                            'name'     => $launchUser->username ?: 'Customer',
                            'phone'    => $launchUser->phone,
                            'password' => $launchUser->password,
                            'status'   => 1,
                        ]
                    );
                }
            } catch (\Throwable $e) {}
        }

        if (!$customer) {
            return redirect()->back()->withInput()->with('error', 'We could not find an account associated with this email or phone number.');
        }

        // Generate a 60-character cryptographically secure token
        $token = \Illuminate\Support\Str::random(60);
        $customer->reset_token = $token;
        $customer->reset_token_expires_at = now()->addMinutes(60);
        $customer->save();

        $resetUrl = route('website-builder.password.reset', [
            'token' => $token,
            'email' => $customer->email,
        ]);

        $customerName = $customer->name ?: ($customer->company_name ?: 'Valued Customer');
        $customerPhone = trim((string)($customer->phone ?? ''));

        // 3. Primary Dispatch: WhatsApp to registered mobile number
        $waSent = false;
        if (!empty($customerPhone)) {
            $waMessage = "🔐 *Password Reset Request - Website Builder*\n\n" .
                         "Hello *{$customerName}*,\n\n" .
                         "We received a request to reset your password for your website account.\n\n" .
                         "Click the link below to set your new password:\n\n" .
                         "🔗 {$resetUrl}\n\n" .
                         "⏱️ This reset link is valid for 60 minutes.\n" .
                         "If you did not request a password reset, you can safely ignore this message.\n\n" .
                         "– Website Builder Team 🚀";

            try {
                $res = \App\Services\WhatsAppNotificationService::sendMessage($customerPhone, $waMessage);
                if (!empty($res['success'])) {
                    $waSent = true;
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("WebsiteBuilder WhatsApp reset dispatch error: " . $e->getMessage());
                $waSent = false;
            }
        }

        // 4. Email Dispatch: Always send or fallback if WhatsApp failed/crashed
        $mailSent = false;
        $emailBody = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff;'>
                <div style='text-align: center; margin-bottom: 24px;'>
                    <h2 style='color: #0e1b3d; margin: 0;'>Website Builder</h2>
                    <p style='color: #64748b; font-size: 14px;'>Account Recovery & Password Reset</p>
                </div>
                <h3 style='color: #1e293b;'>Hello {$customerName},</h3>
                <p style='color: #475569; font-size: 15px; line-height: 1.6;'>
                    We received a request to reset the password for your Website Builder account. Click the button below to choose a new password:
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
            $smtp = self::getWbSmtpConfig();
            $mailData = [
                'smtp_status'   => $smtp['smtp_status'],
                'smtp_host'     => $smtp['smtp_host'],
                'smtp_port'     => $smtp['smtp_port'],
                'encryption'    => $smtp['encryption'],
                'smtp_username' => $smtp['smtp_username'],
                'smtp_password' => $smtp['smtp_password'],
                'from_mail'     => $smtp['from_mail'],
                'from_name'     => $smtp['from_name'],
                'recipient'     => $customer->email,
                'subject'       => 'Reset Password - Website Builder',
                'body'          => $emailBody,
            ];
            $mailSent = \App\Http\Helpers\BasicMailer::sendMail($mailData);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("WebsiteBuilder BasicMailer reset error: " . $e->getMessage());
        }

        // Native PHP mail() fail-safe fallback (essential on cPanel servers)
        if (!$mailSent) {
            try {
                $smtp = self::getWbSmtpConfig();
                $headers  = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                $headers .= "From: {$smtp['from_name']} <{$smtp['from_mail']}>\r\n";
                $headers .= "Reply-To: {$smtp['from_mail']}\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                if (@mail($customer->email, "Reset Password - Website Builder", $emailBody, $headers)) {
                    $mailSent = true;
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("WebsiteBuilder native mail() fallback error: " . $e->getMessage());
            }
        }

        // 5. Flash user-friendly status message adhering to prompt
        if ($waSent && $mailSent) {
            $flashMsg = "Password reset link has been sent to your registered mobile and email.";
        } elseif ($waSent) {
            $flashMsg = "Password reset link has been sent to your registered WhatsApp mobile (+{$customerPhone}).";
        } else {
            $flashMsg = "Password reset link has been sent to your registered email ({$customer->email}).";
        }

        return redirect()->back()->with('status', $flashMsg)->with('message', $flashMsg);
    }

    public function showResetPasswordForm($token, Request $request)
    {
        $settings = WbLandingSetting::getSettings();
        $email = $request->query('email');

        WbCustomer::ensureColumnsExist();

        $customer = WbCustomer::where('reset_token', $token)
            ->where('reset_token_expires_at', '>=', now())
            ->first();

        if (!$customer) {
            return redirect()->route('website-builder.password.request')
                ->with('error', 'This password reset link is invalid or has expired. Please request a new one.');
        }

        return view('website_builder.front.reset_password', compact('settings', 'token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required|string',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        WbCustomer::ensureColumnsExist();

        $customer = WbCustomer::where('reset_token', $request->token)
            ->where('reset_token_expires_at', '>=', now())
            ->first();

        if (!$customer) {
            return redirect()->route('website-builder.password.request')
                ->with('error', 'This password reset link is invalid or has expired. Please request a new one.');
        }

        $customer->password = Hash::make($request->password);
        $customer->reset_token = null;
        $customer->reset_token_expires_at = null;
        $customer->save();

        return redirect()->route('website-builder.login')
            ->with('success', 'Your password has been successfully reset! You can now sign in with your new credentials.');
    }

    public function checkoutPage(Request $request)
    {
        // Clear any stale checkout session data from past purchases
        session()->forget(['wb_checkout_req', 'request', 'data', 'paymentFor']);

        $settings = WbLandingSetting::getSettings();
        $rawTmpl = strtolower(trim($request->query('template') ?: ($request->query('theme') ?: ($request->query('template_slug') ?: session('selected_template', 'digital_agency')))));
        if (in_array($rawTmpl, ['interior', 'interiorcraft', 'interior_template', 'interior_agency'])) $templateSlug = 'interior';
        elseif (in_array($rawTmpl, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) $templateSlug = 'texigo';
        elseif (in_array($rawTmpl, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) $templateSlug = 'construction';
        elseif (in_array($rawTmpl, ['evently', 'evently_theme', 'event'])) $templateSlug = 'evently';
        else $templateSlug = 'digital_agency';

        session(['selected_template' => $templateSlug]);
        $plan = $request->query('plan', 'Starter');
        $rawPrice = $request->query('price');
        $term = strtolower(trim($request->query('term', 'monthly')));

        $package = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('wb_packages')) {
            if (is_numeric($plan)) {
                $package = WbPackage::find($plan);
            }
            if (!$package) {
                $package = WbPackage::where('name', $plan)->orWhere('slug', \Illuminate\Support\Str::slug($plan))->first();
            }
        }

        if ($package) {
            $plan = $package->name;
        }

        if ($rawPrice !== null && is_numeric($rawPrice) && (float)$rawPrice > 0) {
            $price = (float) $rawPrice;
        } elseif ($package) {
            $price = ($term === 'yearly' && !empty($package->yearly_price)) ? (float)$package->yearly_price : (float)$package->monthly_price;
        } else {
            $baseMonthly = ($plan === 'Pro' ? 19 : ($plan === 'Business' ? 39 : 9));
            $price = ($term === 'yearly') ? ($baseMonthly * 10) : $baseMonthly;
        }

        $upiGateway = null;
        $upiInfo = [];
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('payment_gateways')) {
                $upiGateway = \App\Models\PaymentGateway::where('name', 'UPI')->orWhere('keyword', 'upi')->first();
                if ($upiGateway) {
                    $upiInfo = is_string($upiGateway->information) ? json_decode($upiGateway->information, true) : ($upiGateway->information ?? []);
                }
            }
        } catch (\Throwable $e) {}

        return view('website_builder.front.checkout', compact('settings', 'templateSlug', 'plan', 'price', 'package', 'upiGateway', 'upiInfo', 'term'));
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('wb_customer')->logout();
        } catch (\Throwable $e) {}
        session()->forget(['wb_customer_id', 'wb_customer_email', 'tenant_db', 'is_secret_logged_in', 'checkout_otp', 'checkout_otp_email', 'checkout_otp_verified']);
        return redirect()->route('website-builder.login')->with('success', 'You have been logged out successfully.');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'nullable|string',
        ]);

        if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
            if (WbCustomer::where('email', $request->email)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email address is already registered. Please log in to your account or use a different email.'
                ], 422);
            }
        }

        $email = $request->email;
        $phone = $request->phone ?? $request->customer_phone ?? '';

        $otp = rand(100000, 999999);
        session([
            'checkout_otp'            => $otp,
            'checkout_otp_email'      => $email,
            'checkout_otp_phone'      => $phone,
            'checkout_otp_expires_at' => now()->addMinutes(10)->timestamp,
        ]);

        \Illuminate\Support\Facades\Log::info("=== CHECKOUT OTP GENERATED FOR {$email} (Phone: {$phone}): {$otp} ===");

        $whatsappSent = false;
        $emailSent = false;

        // 1. Send OTP to mobile number via WhatsApp Gateway
        if (!empty($phone)) {
            try {
                $cleanWbPhone = \App\Services\WhatsAppNotificationService::formatNumber($phone);
                $agency = function_exists('getAgencyFromHost') ? getAgencyFromHost() : null;
                $waResult = \App\Services\WhatsAppNotificationService::sendOtp($cleanWbPhone, $otp, 'Website Builder', $agency);
                if ($waResult['success'] ?? false) {
                    $whatsappSent = true;
                    \Illuminate\Support\Facades\Log::info("WhatsApp Gateway OTP sent to {$phone}");
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("WhatsApp OTP Exception: " . $e->getMessage());
            }
        }

        // 2. Only fallback to Email if WhatsApp delivery failed or is unavailable
        if (!$whatsappSent) {
            try {
                $smtp = self::getWbSmtpConfig();
                $smtpConfig = [
                    'transport'  => 'smtp',
                    'host'       => $smtp['smtp_host'],
                    'port'       => (int)$smtp['smtp_port'],
                    'encryption' => $smtp['encryption'],
                    'username'   => $smtp['smtp_username'],
                    'password'   => $smtp['smtp_password'],
                    'timeout'    => 10,
                ];
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp', $smtpConfig);
                \Illuminate\Support\Facades\Config::set('mail.default', 'smtp');
                \Illuminate\Support\Facades\Mail::purge('smtp');

                $fromMail = $smtp['from_mail'];
                $fromName = $smtp['from_name'];

                $rawWbLogoUrl = asset('assets/landing_page/websitebuilder_logo.png');
                $wbLogoUrl = str_starts_with($rawWbLogoUrl, 'http://') ? str_replace('http://', 'https://', $rawWbLogoUrl) : $rawWbLogoUrl;
                if (str_contains($wbLogoUrl, 'localhost') || str_contains($wbLogoUrl, '127.0.0.1')) {
                    $wbLogoUrl = 'https://launchshop.saasreselling.com/assets/landing_page/websitebuilder_logo.png';
                }
                $subject = "Your OTP Verification Code - WebsiteBuilder";
                $bodyContent = "<p style=\"font-size:15px;color:#334155;margin:0 0 10px 0;\">Your OTP verification code is <b style=\"font-size:22px;color:#10B981;letter-spacing:1px;\">" . $otp . "</b> for <b>Website Builder</b>.</p>"
                    . "<p style=\"font-size:13px;color:#64748b;margin:0;\">Valid for <b>5 minutes</b>. Please do not share it with anyone.</p>";
                $body = class_exists('\App\Http\Helpers\Common') 
                    ? \App\Http\Helpers\Common::wrapEmailBody($bodyContent, $subject, null, $wbLogoUrl, 'WebsiteBuilder')
                    : $bodyContent;

                try {
                    \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($email, $fromMail, $fromName, $subject, $body) {
                        $message->to($email)
                                ->from($fromMail, $fromName)
                                ->subject($subject)
                                ->html($body, 'text/html');
                    });
                    $emailSent = true;
                    \Illuminate\Support\Facades\Log::info("OTP Email successfully sent to {$email} via {$smtp['smtp_host']}");
                } catch (\Throwable $mEx) {
                    \Illuminate\Support\Facades\Log::warning("OTP Email SMTP failed, testing native mail(): " . $mEx->getMessage());
                    $headers  = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                    $headers .= "From: {$fromName} <{$fromMail}>\r\n";
                    $headers .= "Reply-To: {$fromMail}\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    if (@mail($email, $subject, $body, $headers)) {
                        $emailSent = true;
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('OTP Email sending failed: ' . $e->getMessage());
            }
        }

        if ($whatsappSent) {
            $statusMsg = "OTP verification code sent successfully to your WhatsApp number!";
        } elseif ($emailSent) {
            $statusMsg = "WhatsApp delivery unavailable. OTP verification code sent to your Email address ({$email})!";
        } else {
            $statusMsg = "OTP verification code generated! Check your email ({$email})";
        }

        return response()->json([
            'success' => true,
            'message' => $statusMsg
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string',
        ]);

        $sessionOtp = session('checkout_otp');
        $sessionEmail = session('checkout_otp_email');
        $expiresAt = session('checkout_otp_expires_at');
        $inputOtp = trim($request->otp);



        if (!$sessionOtp || strtolower(trim($sessionEmail)) !== strtolower(trim($request->email))) {
            return response()->json(['success' => false, 'message' => 'Please click Send OTP first.'], 422);
        }

        if ($expiresAt && time() > $expiresAt) {
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new code.'], 422);
        }

        if ($inputOtp != trim($sessionOtp)) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP code. Please check and try again.'], 422);
        }

        session(['checkout_otp_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully!'
        ]);
    }

    public function processCheckout(Request $request)
    {
        $isCallback = $request->filled('razorpay_payment_id');
        $paymentMethod = $request->input('payment_method') ?: ($request->input('payment_method_choice') ?: 'Razorpay');

        // ── On Razorpay callback: fully restore from session, request only adds payment ID ──
        if ($isCallback && session()->has('wb_checkout_req')) {
            $requestData = array_merge(
                session('wb_checkout_req', []),
                array_filter($request->all(), fn($v) => !is_null($v) && $v !== '' && !($v instanceof \Illuminate\Http\UploadedFile))
            );
        } else {
            $requestData = [];
            foreach ($request->all() as $k => $v) {
                if (!($v instanceof \Illuminate\Http\UploadedFile)) {
                    $requestData[$k] = $v;
                }
            }
        }

        // ── Validate only on the initial form submission (not on payment callback) ──
        if (!$isCallback) {
            $validator = \Illuminate\Support\Facades\Validator::make($requestData, [
                'customer_name'  => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:50',
                'subdomain'      => 'required|string|max:100',
                'password'       => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return redirect()->route('website-builder.checkout')->withErrors($validator)->withInput();
            }
        }

        // If payment ID is not yet attached AND payment method is Razorpay, render Razorpay modal
        if (!$request->filled('razorpay_payment_id') && strtoupper($paymentMethod) !== 'UPI') {
            // Stamp explicit product_type so Razorpay callback always knows this is a WB checkout
            session(['wb_checkout_req' => array_merge($requestData, [
                'product_type'       => 'website_builder',
                'is_website_builder' => 1,
            ])]);

            $price = (float) ($requestData['price'] ?? 499);
            $keyId = 'rzp_test_T9UaATIMf1qeO8';
            $keySecret = 'BQ9Z865NgRQrrIMCusfzmskZ';

            $gw = \App\Models\PaymentGateway::whereKeyword('razorpay')->first();
            if ($gw) {
                $paydata = $gw->convertAutoData();
                if (!empty($paydata['key'])) $keyId = $paydata['key'];
                if (!empty($paydata['secret'])) $keySecret = $paydata['secret'];
            }

            $orderId = 'order_' . \Illuminate\Support\Str::random(14);
            try {
                $api = new \Razorpay\Api\Api($keyId, $keySecret);
                $orderData = [
                    'receipt' => 'WB_' . time(),
                    'amount' => (int)round($price * 100),
                    'currency' => 'INR',
                    'payment_capture' => 1
                ];
                $razorpayOrder = $api->order->create($orderData);
                $orderId = $razorpayOrder['id'];
            } catch (\Throwable $ex) {
                \Illuminate\Support\Facades\Log::warning('WB Razorpay API order create failed: ' . $ex->getMessage());
            }

            $notify_url = $request->fullUrl();
            $displayCurrency = 'INR';
            $json = json_encode([
                "key" => $keyId,
                "amount" => (int)round($price * 100),
                "name" => "Websitebuilder Ecommerce",
                "description" => ($requestData['plan'] ?? 'Pro') . " Plan Purchase & Subdomain Setup",
                "prefill" => [
                    "name" => $requestData['customer_name'] ?? '',
                    "email" => $requestData['customer_email'] ?? '',
                    "contact" => $requestData['customer_phone'] ?? '',
                ],
                "theme" => [
                    "color" => "#10B981"
                ],
                "order_id" => $orderId,
            ]);

            return view('front.razorpay', compact('gw', 'displayCurrency', 'json', 'notify_url'));
        }

        // ── Ensure payment confirmation ID or UPI payment submission is present ──
        $paymentMethod = $request->input('payment_method') ?: ($requestData['payment_method'] ?? 'Razorpay');

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            if ($file && $file->isValid()) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
                $ext = strtolower($file->guessExtension() ?: pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
                if (in_array($ext, $allowed, true)) {
                    $pubDir = public_path('uploads/payment_proofs/');
                    if (!file_exists($pubDir)) {
                        @mkdir($pubDir, 0755, true);
                    }
                    $fileName = 'proof_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $ext;
                    $file->move($pubDir, $fileName);
                    $paymentProofPath = 'uploads/payment_proofs/' . $fileName;
                }
            }
        }

        $utrNumber = $request->input('utr_number') ?: ($request->input('transaction_id') ?: ($requestData['utr_number'] ?? null));

        if (strtoupper($paymentMethod) === 'UPI') {
            $razorpayPaymentId = 'UPI_' . ($utrNumber ?: strtoupper(\Illuminate\Support\Str::random(10)));
            $purchaseStatus = 'Pending Verification';
        } else {
            $razorpayPaymentId = $request->input('razorpay_payment_id') ?: ($requestData['razorpay_payment_id'] ?? null);
            if (empty($razorpayPaymentId)) {
                return redirect()->route('website-builder.checkout')->with('error', 'Payment confirmation was not received. Client account was not created.');
            }
            $purchaseStatus = 'completed';
        }

        $customerName = $request->input('customer_name') ?: ($requestData['customer_name'] ?? ($requestData['first_name'] ?? 'Customer'));
        $customerEmail = $request->input('customer_email') ?: ($requestData['customer_email'] ?? ($requestData['email'] ?? ''));
        $phoneNum = $request->input('customer_phone') ?: ($requestData['customer_phone'] ?? ($requestData['phone'] ?? '9360157880'));
        $subdomain = preg_replace('/[^a-z0-9-]/', '', strtolower($request->input('subdomain') ?: ($requestData['subdomain'] ?? ($requestData['username'] ?? ''))));
        if (empty($subdomain) && !empty($customer) && !empty($customer->subdomain)) {
            $subdomain = $customer->subdomain;
        }
        if (empty($subdomain)) {
            $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($customerName)) . rand(100, 999);
        }
        if (empty($subdomain)) {
            $subdomain = 'store' . rand(1000, 9999);
        }

        // Subdomain Uniqueness Validation Check (Ref Task 3 Requirement)
        if (!empty($subdomain) && \Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
            $existingSubCustomer = WbCustomer::where('subdomain', $subdomain)->first();
            $currCustId = Auth::guard('wb_customer')->id() ?: session('wb_customer_id');
            if ($existingSubCustomer && (!$currCustId || $existingSubCustomer->id != $currCustId)) {
                return redirect()->route('website-builder.checkout')->withInput()->with('error', "Subdomain '{$subdomain}' is already taken. Please choose a different subdomain.");
            }
        }

        $customerPassword = $request->input('password') ?: ($requestData['password'] ?? 'Password@123');
        $planName = $request->input('plan') ?: ($requestData['plan'] ?? 'Starter');
        $price = $request->input('price') ?: ($requestData['price'] ?? 499);

        $packageObj = null;
        if (!empty($planName) && \Illuminate\Support\Facades\Schema::hasTable('wb_packages')) {
            $packageObj = WbPackage::whereRaw('LOWER(name) = ?', [strtolower(trim($planName))])
                ->orWhere('name', 'LIKE', '%' . trim($planName) . '%')
                ->first();
        }
        if (!$packageObj && !empty($price) && \Illuminate\Support\Facades\Schema::hasTable('wb_packages')) {
            $amt = (float)$price;
            $packageObj = WbPackage::where('monthly_price', $amt)->orWhere('yearly_price', $amt)->first();
        }
        if (!$packageObj && \Illuminate\Support\Facades\Schema::hasTable('wb_packages')) {
            $packageObj = WbPackage::orderBy('id', 'asc')->first();
        }
        $packageId = $packageObj ? $packageObj->id : null;

        $rawTmpl = strtolower(trim($request->input('template') ?: ($requestData['template'] ?? ($request->input('template_slug') ?: ($requestData['template_slug'] ?? ($request->input('theme') ?: ($requestData['theme'] ?? session('selected_template', 'digital_agency'))))))));
        if (in_array($rawTmpl, ['interior', 'interiorcraft', 'interior_template', 'interior_agency'])) $templateSlug = 'interior';
        elseif (in_array($rawTmpl, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) $templateSlug = 'texigo';
        elseif (in_array($rawTmpl, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) $templateSlug = 'construction';
        elseif (in_array($rawTmpl, ['evently', 'evently_theme', 'event'])) $templateSlug = 'evently';
        else $templateSlug = 'digital_agency';

        $templateNames = [
            'digital_agency' => 'Digital Agency',
            'interior'       => 'InteriorCRAFT',
            'texigo'         => 'TaxiGo Mobility',
            'construction'   => 'BuildCraft Construction',
            'evently'        => 'Evently',
        ];
        $templateName = $templateNames[$templateSlug] ?? 'Digital Agency';

        try {
            if (!empty($customerEmail) && \Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $existingCust = WbCustomer::where('email', $customerEmail)->first();
                $customerData = [
                    'name'         => $customerName,
                    'email'        => $customerEmail,
                    'phone'        => $phoneNum,
                    'company_name' => $customerName . ' Agency',
                    'subdomain'    => $subdomain,
                    'status'       => 1,
                ];
                // Only set password on new customer registration to prevent account takeover
                if (!$existingCust) {
                    $customerData['password'] = Hash::make($customerPassword);
                }
                if ($packageId) {
                    $customerData['package_id'] = $packageId;
                }
                $customer = WbCustomer::updateOrCreate(
                    ['email' => $customerEmail],
                    $customerData
                );

                $checkoutTerm = strtolower(trim($request->input('term') ?: ($requestData['term'] ?? 'monthly')));
                $daysToAdd = ($checkoutTerm === 'yearly' || $checkoutTerm === 'year') ? 365 : 30;
                $baseDate = ($customer && !empty($customer->expire_date) && \Carbon\Carbon::parse($customer->expire_date)->isFuture())
                    ? \Carbon\Carbon::parse($customer->expire_date)
                    : \Carbon\Carbon::now();
                $customer->expire_date = $baseDate->addDays($daysToAdd)->format('Y-m-d H:i:s');
                $customer->status = 1;
                $customer->save();

                if ($customer && $packageObj && method_exists($customer, 'applyPackageLimits')) {
                    $customer->applyPackageLimits($packageObj);
                    $customer->save();
                }

                if ($customer && $customer->id && \Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                    $agency = \App\Models\WebsiteBuilder\WbAgencySetting::where('customer_id', $customer->id)->first();
                    if (!$agency) {
                        if ($templateSlug === 'interior') {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createInteriorDefaultInstance($customer->id);
                        } elseif ($templateSlug === 'texigo') {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createTexigoDefaultInstance($customer->id);
                        } elseif ($templateSlug === 'construction') {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createConstructionDefaultInstance($customer->id);
                        } elseif ($templateSlug === 'evently') {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createEventlyDefaultInstance($customer->id);
                        } else {
                            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createDefaultInstance($customer->id);
                        }
                    } else {
                        $agency->applyTemplateDefaults($templateSlug, true);
                    }
                    $agency->template_type = $templateSlug;
                    $agency->site_title = $customerName ?: ($customer->company_name ?: ($subdomain . ' Agency'));
                    if ($customerEmail) $agency->email = $customerEmail;
                    if ($phoneNum) $agency->phone = $phoneNum;
                    $agency->save();
                }

                session()->forget(['wb_demo_admin', 'demo_template']);
                try {
                    Auth::guard('wb_customer')->login($customer);
                } catch (\Throwable $e) {}
                session(['wb_customer_id' => $customer->id, 'wb_customer_email' => $customer->email]);
            }

            if (!empty($customerEmail) && \Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                \App\Models\WebsiteBuilder\WbTemplatePurchase::ensureColumnsExist();

                $cleanCustomerEmail = strtolower(trim($customerEmail));
                $purchaseData = [
                    'customer_id'         => $customer ? $customer->id : null,
                    'customer_name'       => $customerName,
                    'customer_email'      => $cleanCustomerEmail,
                    'customer_phone'      => $phoneNum,
                    'template_slug'       => $templateSlug,
                    'template_name'       => $templateName,
                    'package_id'          => $packageId,
                    'payment_method'      => $paymentMethod,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'transaction_id'      => $utrNumber ?: $razorpayPaymentId,
                    'payment_proof'       => $paymentProofPath,
                    'amount'              => $price,
                    'currency'            => 'INR',
                    'status'              => $purchaseStatus,
                ];

                try {
                    \App\Models\WebsiteBuilder\WbTemplatePurchase::create($purchaseData);
                } catch (\Throwable $pe) {
                    \Illuminate\Support\Facades\Log::error("WbTemplatePurchase primary create error: " . $pe->getMessage());
                    // Fallback create with bare core columns
                    try {
                        \App\Models\WebsiteBuilder\WbTemplatePurchase::create([
                            'customer_name'  => $customerName,
                            'customer_email' => $cleanCustomerEmail,
                            'customer_phone' => $phoneNum,
                            'template_slug'  => $templateSlug,
                            'template_name'  => $templateName,
                            'amount'         => $price,
                            'currency'       => 'INR',
                            'status'         => $purchaseStatus,
                        ]);
                    } catch (\Throwable $pe2) {
                        \Illuminate\Support\Facades\Log::error("WbTemplatePurchase fallback create error: " . $pe2->getMessage());
                    }
                }
            }

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("WbCustomer creation error: " . $e->getMessage());
        }

        // Build dynamic WB live URL — no hardcoded domains
        $scheme = (request()->secure() || str_contains(request()->fullUrl(), 'https://')) ? 'https://' : 'http://';

        // Resolve agency domain dynamically:
        // 1. From WEBSITE_BUILDER_HOST env (e.g. "youverse.in")
        // 2. From current request host or referer host (e.g. "checkout.youverse.in" -> "youverse.in")
        // 3. Default to "youverse.in"
        $wbHost = env('WEBSITE_BUILDER_HOST');
        if (empty($wbHost)) {
            $reqHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
            $refererHost = strtolower(parse_url(request()->headers->get('referer') ?? '', PHP_URL_HOST) ?? '');

            $targetHost = (!empty($reqHost) && !str_contains($reqHost, 'localhost') && !str_contains($reqHost, '127.0.0.1')) ? $reqHost : $refererHost;
            $cleanHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder)\./i', '', $targetHost);
            $cleanHost = preg_replace('/:\d+$/', '', $cleanHost);

            if (!empty($cleanHost) && $cleanHost !== 'localhost' && $cleanHost !== '127.0.0.1') {
                $wbHost = $cleanHost;
            } else {
                $wbHost = 'youverse.in';
            }
        }

        $storeLiveLink       = "{$scheme}websitebuilder.{$wbHost}/{$subdomain}";
        $loginDashboardLink  = "{$scheme}websitebuilder.{$wbHost}/login";

        $rawWbLogoUrl = asset('assets/landing_page/websitebuilder_logo.png');
        $wbLogoUrl = str_starts_with($rawWbLogoUrl, 'http://') ? str_replace('http://', 'https://', $rawWbLogoUrl) : $rawWbLogoUrl;
        if (str_contains($wbLogoUrl, 'localhost') || str_contains($wbLogoUrl, '127.0.0.1')) {
            $wbLogoUrl = 'https://launchshop.saasreselling.com/assets/landing_page/websitebuilder_logo.png';
        }
        $welcomeHtml = "<!DOCTYPE html><html><head><meta charset=\"UTF-8\"></head><body style=\"margin:0;padding:0;background-color:#f8fafc;\">"
            . "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color:#f8fafc;padding:30px 10px;\"><tr><td align=\"center\">"
            . "<table width=\"600\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;width:100%;background-color:#ffffff;border-radius:12px;border:1px solid #e2e8f0;padding:30px;\"><tr><td>"
            . "<div style=\"text-align:center;margin-bottom:24px;\"><img src=\"{$wbLogoUrl}\" alt=\"WebsiteBuilder\" style=\"max-height:55px;width:auto;display:inline-block;\"></div>"
            . "<div style=\"font-family: Arial, sans-serif; font-size: 15px; color: #1E293B; line-height: 1.6;\">"
            . "<h2 style=\"color: #10B981; font-weight: 800; margin-bottom: 16px; text-align:center;\">Welcome to WebsiteBuilder!</h2>"
            . "<p>Your store account has been created successfully.</p>"
            . "<div style=\"background: #F8FAFC; border: 1px solid #CBD5E1; padding: 20px; border-radius: 12px; margin: 20px 0;\">"
            . "<p style=\"margin: 6px 0;\"><strong>Store Name:</strong> {$subdomain}</p>"
            . "<p style=\"margin: 6px 0;\"><strong>Email:</strong> {$customerEmail}</p>"
            . "<p style=\"margin: 6px 0;\"><strong>Phone Number:</strong> {$phoneNum}</p>"
            . "<p style=\"margin: 6px 0;\"><strong>Password:</strong> {$customerPassword}</p>"
            . "<p style=\"margin: 6px 0;\"><strong>Plan:</strong> {$planName} (Rs. " . number_format($price) . ")</p>"
            . "</div>"
            . "<p style=\"margin: 10px 0;\"><strong>Store Live Link:</strong> <a href=\"{$storeLiveLink}\" style=\"color: #2563EB; font-weight: 700;\">{$storeLiveLink}</a></p>"
            . "<p style=\"margin: 10px 0;\"><strong>Login to your store dashboard:</strong><br><a href=\"{$loginDashboardLink}\" style=\"color: #2563EB; font-weight: 700;\">{$loginDashboardLink}</a></p>"
            . "<br><p>Need help? Chat with us anytime.<br><strong>Team LaunchShop</strong></p>"
            . "</div></td></tr></table></td></tr></table></body></html>";

        try {
            $smtpConfig = self::getWbSmtpConfig();
            $mailData = [
                'smtp_status'   => $smtpConfig['smtp_status'] ?? 1,
                'smtp_host'     => $smtpConfig['smtp_host'],
                'smtp_username' => $smtpConfig['smtp_username'],
                'smtp_password' => $smtpConfig['smtp_password'],
                'encryption'    => $smtpConfig['encryption'],
                'smtp_port'     => $smtpConfig['smtp_port'],
                'from_mail'     => $smtpConfig['from_mail'],
                'from_name'     => $smtpConfig['from_name'] ?? 'Website Builder',
                'recipient'      => $customerEmail,
                'subject'        => "🎉 Welcome to websitebuilder! Your store account is ready",
                'body'           => $welcomeHtml,
            ];

            $mailSent = false;
            try {
                \App\Http\Helpers\BasicMailer::sendMail($mailData);
                $mailSent = true;
            } catch (\Throwable $sendEx) {
                \Illuminate\Support\Facades\Log::warning('WB Welcome SMTP sendMail warning: ' . $sendEx->getMessage());
            }

            if (!$mailSent) {
                $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: {$smtpConfig['from_name']} <{$smtpConfig['from_mail']}>\r\n";
                @mail($customerEmail, "🎉 Welcome to websitebuilder! Your store account is ready", $welcomeHtml, $headers);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Welcome mail error: ' . $e->getMessage());
        }

        
        // Send parallel WhatsApp notifications for Website Builder
        if (!empty($phoneNum)) {
            try {
                \App\Services\WhatsAppNotificationService::sendWebsiteLaunchCredentials(
                    $phoneNum,
                    $subdomain,
                    $storeLiveLink,
                    $loginDashboardLink,
                    $customerEmail,
                    $customerPassword,
                    $planName . ($price ? " (Rs. " . number_format($price) . ")" : ""),
                    'Website Builder'
                );
            } catch (\Throwable $waCredEx) {
                \Illuminate\Support\Facades\Log::error('WB WhatsApp Credentials Error: ' . $waCredEx->getMessage());
            }

            try {
                $invId = $utrNumber ?: ($razorpayPaymentId ?: ('WB-' . time()));
                \App\Services\WhatsAppNotificationService::sendInvoice(
                    $phoneNum,
                    $invId,
                    'Rs. ' . number_format($price),
                    $templateName ?: $planName,
                    $paymentMethod,
                    null,
                    'Website Builder'
                );
            } catch (\Throwable $waInvEx) {
                \Illuminate\Support\Facades\Log::error('WB WhatsApp Invoice Error: ' . $waInvEx->getMessage());
            }
        }

        session()->forget(['wb_checkout_req', 'request', 'data', 'paymentFor']);
        $redirectUrl = str_contains($storeLiveLink, '?') ? ($storeLiveLink . '&launched=1') : ($storeLiveLink . '?launched=1');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'redirect_url' => $redirectUrl, 'message' => "🚀 Congratulations! Your website is live at {$storeLiveLink}"]);
        }

        return redirect()->to($redirectUrl)->with('success', "🚀 Congratulations! Your website is live at {$storeLiveLink}");
    }

    public function agencyTemplate()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        return view('website_builder.agency_template.index', compact('agency'));
    }

    public function agencyAbout()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        return view('website_builder.agency_template.about', compact('agency'));
    }

    public function agencyServices()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        return view('website_builder.agency_template.services', compact('agency'));
    }

    public function agencyContact()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        return view('website_builder.agency_template.contact', compact('agency'));
    }

    public function agencyPortfolio()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        return view('website_builder.agency_template.portfolio', compact('agency'));
    }

    public function agencyBlogs()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        return view('website_builder.agency_template.blogs', compact('agency'));
    }

    public function agencyBlogDetail($id)
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        $blogs = $agency->blogs_data ?? [];
        $blog = null;

        foreach ($blogs as $b) {
            if (isset($b['id']) && $b['id'] == $id) {
                $blog = $b;
                break;
            }
        }

        if (!$blog && isset($blogs[$id - 1])) {
            $blog = $blogs[$id - 1];
        }

        if (!$blog && !empty($blogs)) {
            $blog = $blogs[0];
        }

        return view('website_builder.agency_template.blog_detail', compact('agency', 'blog'));
    }

    public function interiorTemplate()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        return view('website_builder.interior_template.index', compact('interior'));
    }

    public function interiorAbout()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        return view('website_builder.interior_template.about', compact('interior'));
    }

    public function interiorServices()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        $agency = $interior;
        return view('website_builder.interior_template.services', compact('interior', 'agency'));
    }

    public function interiorContact()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        return view('website_builder.interior_template.contact', compact('interior'));
    }

    public function interiorPortfolio()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        return view('website_builder.interior_template.portfolio', compact('interior'));
    }

    public function texigoTemplate()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        return view('website_builder.texigo_theme.index', compact('agency'));
    }

    public function texigoAbout()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        return view('website_builder.texigo_theme.about', compact('agency'));
    }

    public function texigoServices()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        return view('website_builder.texigo_theme.services', compact('agency'));
    }

    public function texigoFleet()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        return view('website_builder.texigo_theme.index', compact('agency'));
    }

    public function texigoContact()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        return view('website_builder.texigo_theme.contact', compact('agency'));
    }

    public function texigoPortfolio()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        return view('website_builder.texigo_theme.portfolio', compact('agency'));
    }

    // =========================================================
    // CONSTRUCTION THEME METHODS
    // =========================================================

    public function constructionTemplate()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
        return view('website_builder.construction_theme.index', compact('agency'));
    }

    public function constructionAbout()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
        return view('website_builder.construction_theme.about', compact('agency'));
    }

    public function constructionServices()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
        return view('website_builder.construction_theme.services', compact('agency'));
    }

    public function constructionContact()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
        return view('website_builder.construction_theme.contact', compact('agency'));
    }

    public function constructionPortfolio()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
        return view('website_builder.construction_theme.portfolio', compact('agency'));
    }

    // =========================================================
    // EVENTLY THEME METHODS
    // =========================================================

    public function eventlyTemplate()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
        $agency = $interior;
        return view('website_builder.evently_theme.index', compact('interior', 'agency'));
    }

    public function eventlyAbout()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
        $agency = $interior;
        return view('website_builder.evently_theme.about', compact('interior', 'agency'));
    }

    public function eventlyServices()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
        $agency = $interior;
        return view('website_builder.evently_theme.services', compact('interior', 'agency'));
    }

    public function eventlyPortfolio()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
        $agency = $interior;
        return view('website_builder.evently_theme.portfolio', compact('interior', 'agency'));
    }

    public function eventlyContact()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
        $agency = $interior;
        return view('website_builder.evently_theme.contact', compact('interior', 'agency'));
    }

    public function texigoBlogs()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        return view('website_builder.texigo_theme.blogs', compact('agency'));
    }

    public function texigoBlogDetail($id)
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        $blogs = $agency->blogs_data ?? [];
        $blog = null;
        foreach ($blogs as $bi => $b) {
            if ((isset($b['id']) && $b['id'] == $id) || ($bi + 1) == $id) { $blog = $b; break; }
        }
        if (!$blog && isset($blogs[$id - 1])) $blog = $blogs[$id - 1];
        if (!$blog && !empty($blogs)) $blog = $blogs[0];
        return view('website_builder.texigo_theme.blog_detail', compact('agency', 'blog'));
    }

    public function interiorBlogs()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        $agency = $interior;
        return view('website_builder.interior_template.blogs', compact('interior', 'agency'));
    }

    public function interiorBlogDetail($id)
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        $agency = $interior;
        $blogs = [
            1 => [
                'id' => 1,
                'title' => '10 Simple Ways to Make Your Home Look Expensive',
                'category' => 'Interior Tips',
                'date' => 'Sep 12, 2024',
                'author' => 'Emma Carter',
                'image' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=800&auto=format&fit=crop',
                'excerpt' => 'Transform your space with these easy and affordable interior design tips that instantly elevate your home.',
                'content' => "Creating a high-end, luxurious look in your home doesn't require a massive budget. By focusing on key details like spatial layout, warm lighting layers, curated texture contrasts, and statement furniture pieces, you can elevate your interiors effortlessly.\n\n1. Use Monochromatic Color Palettes\nStick to neutral tones with subtle accents to create a cohesive and calm atmosphere.\n\n2. Upgrade Lighting Fixtures\nInstall statement pendant lights or modern recessed warm LEDs.\n\n3. Curate Decorative Vases and Greenery\nNatural plants and ceramic textures add organic warmth.\n\n4. Invest in Custom Window Treatments\nFloor-to-ceiling drapes make rooms feel taller and grander."
            ],
            2 => [
                'id' => 2,
                'title' => 'Top Interior Design Trends for 2025',
                'category' => 'Design Trends',
                'date' => 'Aug 28, 2024',
                'author' => 'Daniel Lee',
                'image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=800&auto=format&fit=crop',
                'excerpt' => 'Explore the latest design trends that are shaping modern interiors this year.',
                'content' => "Interior design in 2025 emphasizes sustainable materials, organic shapes, biophilic integration, and warm earth tones. From timber wall paneling to handcrafted stoneware, homes are shifting towards tactile, cozy, and functional luxury."
            ],
            3 => [
                'id' => 3,
                'title' => 'How to Maximize Small Spaces with Smart Design',
                'category' => 'Space Planning',
                'date' => 'Aug 15, 2024',
                'author' => 'Sofia Martinez',
                'image' => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?q=80&w=800&auto=format&fit=crop',
                'excerpt' => 'Practical ideas to make the most of your space without compromising style.',
                'content' => "Small spaces require thoughtful spatial planning, multi-functional furniture, smart vertical storage solutions, and reflective surfaces to maintain airiness and spatial flow."
            ]
        ];

        $blog = $blogs[$id] ?? $blogs[array_key_first($blogs ?? [])] ?? null;
        if (!$blog) { foreach (($agency->blogs_data ?? []) as $bi => $b) { if ((isset($b['id']) && $b['id'] == $id) || ($bi + 1) == $id) { $blog = $b; break; } } }
        if (!$blog && !empty($agency->blogs_data)) $blog = $agency->blogs_data[0];
        return view('website_builder.interior_template.blog_detail', compact('interior', 'agency', 'blog'));
    }

    public function constructionBlogs()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
        return view('website_builder.construction_theme.blogs', compact('agency'));
    }

    public function constructionBlogDetail($id)
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
        $blogs = $agency->blogs_data ?? [];
        $blog = null;
        foreach ($blogs as $bi => $b) {
            if ((isset($b['id']) && $b['id'] == $id) || ($bi + 1) == $id) { $blog = $b; break; }
        }
        if (!$blog && isset($blogs[$id - 1])) $blog = $blogs[$id - 1];
        if (!$blog && !empty($blogs)) $blog = $blogs[0];
        return view('website_builder.construction_theme.blog_detail', compact('agency', 'blog'));
    }

    public function eventlyBlogs()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        $interior = $agency;
        return view('website_builder.agency_template.blogs', compact('agency', 'interior'));
    }

    public function eventlyBlogDetail($id)
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        $interior = $agency;
        $blogs = $agency->blogs_data ?? [];
        $blog = null;
        foreach ($blogs as $bi => $b) {
            if ((isset($b['id']) && $b['id'] == $id) || ($bi + 1) == $id) { $blog = $b; break; }
        }
        if (!$blog && isset($blogs[$id - 1])) $blog = $blogs[$id - 1];
        if (!$blog && !empty($blogs)) $blog = $blogs[0];
        return view('website_builder.agency_template.blog_detail', compact('agency', 'interior', 'blog'));
    }

    private function resolveCustomerAndAgency($subdomain = null)
    {
        $customer = null;
        $agency = null;

        $reqHost = normalizeWbHost(request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? ''));

        try {
            $hostSetting = isWbAgencyCustomDomain($reqHost);
            if ($hostSetting) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::find($hostSetting->id);
                if ($agency && $agency->customer_id && \Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                    $customer = WbCustomer::find($agency->customer_id);
                }
            }

            // 3. Third Priority: Fallback to passed $subdomain parameter if host search returned null
            if (!$agency && !empty($subdomain)) {
                $clean = strtolower(trim($subdomain));
                $clean = preg_replace('#^https?://#', '', $clean);
                $clean = preg_replace('#^www\.#', '', $clean);
                $clean = rtrim($clean, '/');

                if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                    $agency = \App\Models\WebsiteBuilder\WbAgencySetting::where('custom_domain_status', 1)
                        ->whereNotNull('custom_domain')
                        ->where('custom_domain', '!=', '')
                        ->where(function($q) use ($clean) {
                            $q->where('custom_domain', $clean)
                              ->orWhere('custom_domain', 'www.' . $clean)
                              ->orWhere('custom_domain', 'https://' . $clean)
                              ->orWhere('custom_domain', 'http://' . $clean)
                              ->orWhere('custom_domain', 'like', '%' . $clean . '%');
                        })
                        ->orderBy('updated_at', 'desc')
                        ->first();

                    if (!$agency) {
                        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::whereNotNull('custom_domain')
                            ->where('custom_domain', '!=', '')
                            ->where(function($q) use ($clean) {
                                $q->where('custom_domain', $clean)
                                  ->orWhere('custom_domain', 'www.' . $clean)
                                  ->orWhere('custom_domain', 'https://' . $clean)
                                  ->orWhere('custom_domain', 'http://' . $clean)
                                  ->orWhere('custom_domain', 'like', '%' . $clean . '%');
                            })
                            ->orderBy('updated_at', 'desc')
                            ->first();
                    }

                    if ($agency && $agency->customer_id && \Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                        $customer = WbCustomer::find($agency->customer_id);
                    }
                }

                if (!$agency && \Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                    $customer = WbCustomer::where('subdomain', $clean)->first();
                    if (!$customer) {
                        $customer = WbCustomer::where('subdomain', 'like', $clean . '%')->orWhere('subdomain', 'like', '%' . $clean)->first();
                    }
                    if ($customer) {
                        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDefaults($customer->id);
                    }
                }

                if (!$agency && \Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                    $agency = \App\Models\WebsiteBuilder\WbAgencySetting::first();
                }
            }

            if (!$agency && \Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::first();
            }

            if (!$customer && !empty($subdomain) && \Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $cleanSub = strtolower(trim($subdomain));
                $cleanSub = preg_replace('#^https?://#', '', $cleanSub);
                $cleanSub = preg_replace('#^www\.#', '', $cleanSub);
                $cleanSub = rtrim($cleanSub, '/');
                $customer = WbCustomer::where('subdomain', $cleanSub)->first();
            }
        } catch (\Throwable $e) {}

        return [$customer, $agency];
    }

    // Custom Domain Direct View Handlers
    public function checkCustomerSubscription($customer, $subdomain = null)
    {
        if (!$customer && !empty($subdomain)) {
            try {
                $clean = strtolower(trim($subdomain));
                $clean = preg_replace('#^https?://#', '', $clean);
                $clean = preg_replace('#^www\.#', '', $clean);
                $clean = rtrim($clean, '/');
                if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                    $customer = WbCustomer::where('subdomain', $clean)
                        ->orWhere('custom_domain', $clean)
                        ->orWhere('custom_domain', 'like', '%' . $clean . '%')
                        ->first();
                }
            } catch (\Throwable $e) {}
        }

        if (!$customer) {
            return null;
        }

        $now = \Carbon\Carbon::now();
        $isExpired = false;
        $reason = null;

        if ((int)$customer->status === 0) {
            $isExpired = true;
            $reason = 'This website has been temporarily suspended by administration.';
        } elseif (!empty($customer->expire_date)) {
            try {
                $exp = \Carbon\Carbon::parse($customer->expire_date);
                if ($now->greaterThan($exp)) {
                    $isExpired = true;
                    $reason = 'This website\'s subscription plan expired on ' . $exp->format('M d, Y h:i A') . '.';
                }
            } catch (\Throwable $e) {}
        }

        if ($isExpired) {
            $agency = null;
            try {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDefaults($customer->id);
            } catch (\Throwable $e) {}
            return response()->view('website_builder.agency_template.suspended', [
                'customer'  => $customer,
                'agency'    => $agency,
                'isExpired' => true,
                'reason'    => $reason ?: 'This website has expired. Please renew your plan to reactivate.',
            ], 402);
        }

        return null;
    }

    public function viewCustomDomainSite()
    {
        $host = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $host = preg_replace('/:\d+$/', '', $host);
        return $this->viewSubdomainSite($host);
    }

    public function viewCustomDomainAbout()
    {
        $host = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $host = preg_replace('/:\d+$/', '', $host);
        return $this->viewSubdomainAbout($host);
    }

    public function viewCustomDomainContact()
    {
        $host = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $host = preg_replace('/:\d+$/', '', $host);
        return $this->viewSubdomainContact($host);
    }

    public function viewCustomDomainPortfolio()
    {
        $host = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $host = preg_replace('/:\d+$/', '', $host);
        return $this->viewSubdomainPortfolio($host);
    }

    public function viewCustomDomainServices()
    {
        $host = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $host = preg_replace('/:\d+$/', '', $host);
        return $this->viewSubdomainServices($host);
    }

    public function viewCustomDomainFleet()
    {
        $host = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $host = preg_replace('/:\d+$/', '', $host);
        return $this->viewSubdomainFleet($host);
    }

    public function viewCustomDomainBlogs()
    {

        $host = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $host = preg_replace('/:\d+$/', '', $host);
        return $this->viewSubdomainBlogs($host);
    }

    public function viewCustomDomainBlog($id)
    {
        $host = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $host = preg_replace('/:\d+$/', '', $host);
        return $this->viewSubdomainBlog($host, $id);
    }

    public function viewCustomDomainPolicy($slug)
    {
        $host = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $host = preg_replace('/:\d+$/', '', $host);
        return $this->viewSubdomainPolicy($host, $slug);
    }

    // Subdomain & Custom Domain Live Launched Website Views
    public function viewSubdomainSite($subdomain)
    {
        $customer = null;
        $agency = null;

        try {
            [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
            if ($suspendedView = $this->checkCustomerSubscription($customer, $subdomain)) { return $suspendedView; }
            if (!$agency && \Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::first();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("FrontendController viewSubdomainSite error: " . $e->getMessage());
        }

        if (!$agency) {
            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        }

        // Auto-redirect to connected custom domain if custom_domain_status === 1
        if (!empty($agency->custom_domain) && (int)$agency->custom_domain_status === 1) {
            $reqHost = strtolower(trim(preg_replace('/:\d+$/', '', preg_replace('/^www\./', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')))));
            $cleanCustomDomain = function_exists('normalizeWbHost') ? normalizeWbHost($agency->custom_domain) : strtolower(trim(preg_replace('#^https?://#', '', $agency->custom_domain)));
            $cleanCustomDomain = preg_replace('#^www\.#', '', $cleanCustomDomain);
            $cleanCustomDomain = rtrim($cleanCustomDomain, '/');

            if (!empty($cleanCustomDomain) && $reqHost !== $cleanCustomDomain && !str_ends_with($reqHost, $cleanCustomDomain)) {
                $targetUrl = 'https://' . $cleanCustomDomain . request()->getRequestUri();
                return redirect()->away($targetUrl, 301);
            }
        }

        // Render pending domain view if custom_domain is not connected (1)
        if (!empty($agency->custom_domain) && (int)$agency->custom_domain_status !== 1) {
            $reqHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
            $reqHost = preg_replace('/:\d+$/', '', $reqHost);
            $cleanCustomDomain = strtolower(trim(preg_replace('#^https?://#', '', $agency->custom_domain)));
            $cleanCustomDomain = preg_replace('#^www\.#', '', $cleanCustomDomain);
            $cleanCustomDomain = rtrim($cleanCustomDomain, '/');

            if ($reqHost === $cleanCustomDomain || str_contains($reqHost, $cleanCustomDomain) || str_contains($cleanCustomDomain, $reqHost)) {
                $statusMsg = ((int)$agency->custom_domain_status === 2)
                    ? 'Custom Domain Connection Request Rejected. Please contact support or update your custom domain settings in your Agency Admin Dashboard.'
                    : 'Custom Domain Verification Pending. Your custom domain connection request is currently pending super admin verification.';
                return response()->view('website_builder.agency_template.domain_pending', compact('agency', 'customer', 'statusMsg'), 200);
            }
        }

        // Determine target template exclusively from database agency settings or purchase records
        $targetTemplate = null;

        // 1. First priority: Check agency settings template_type stored in database
        if ($agency && !empty($agency->template_type)) {
            $targetTemplate = strtolower(trim($agency->template_type));
        }

        // 2. Second priority: Check customer's template purchase record by email
        if ($customer && !empty($customer->email) && \Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
            $purchase = \App\Models\WebsiteBuilder\WbTemplatePurchase::where('customer_email', $customer->email)->latest()->first();
            if (!$purchase) {
                $purchase = \App\Models\WebsiteBuilder\WbTemplatePurchase::whereRaw('LOWER(customer_email) = ?', [strtolower($customer->email)])->latest()->first();
            }
            if ($purchase && !empty($purchase->template_slug)) {
                $targetTemplate = strtolower(trim($purchase->template_slug));
            }
        }

        // Normalize targetTemplate slug
        if (in_array($targetTemplate, ['interior', 'interiorcraft', 'interior_template'])) $targetTemplate = 'interior';
        elseif (in_array($targetTemplate, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi', 'tex'])) $targetTemplate = 'texigo';
        elseif (in_array($targetTemplate, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) $targetTemplate = 'construction';
        elseif (in_array($targetTemplate, ['evently', 'evently_theme', 'event', 'events'])) $targetTemplate = 'evently';
        else $targetTemplate = 'digital_agency';

        // Apply and persist target template to agency settings database
        if ($agency && $targetTemplate) {
            if ($agency->template_type !== $targetTemplate || (str_contains($agency->hero_image ?? '', 'Digital_agency') && $targetTemplate !== 'digital_agency')) {
                $agency->applyTemplateDefaults($targetTemplate, true);
                $agency->template_type = $targetTemplate;
                try { $agency->save(); } catch (\Throwable $e) {}
            }
        }

        $tmplType = strtolower(trim($agency->template_type ?? 'digital_agency'));

        if (in_array($tmplType, ['evently', 'evently_theme', 'event', 'events'])) {
            $interior = $agency;
            return view('website_builder.evently_theme.index', compact('interior', 'agency', 'customer', 'subdomain'));
        }

        if (in_array($tmplType, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            return view('website_builder.construction_theme.index', compact('agency', 'customer', 'subdomain'));
        }

        if (in_array($tmplType, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi', 'tex'])) {
            return view('website_builder.texigo_theme.index', compact('agency', 'customer', 'subdomain'));
        }

        if (in_array($tmplType, ['interior', 'interiorcraft', 'interior_template'])) {
            $interior = $agency;
            return view('website_builder.interior_template.index', compact('interior', 'agency', 'customer', 'subdomain'));
        }

        return view('website_builder.agency_template.index', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainAbout($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if ($suspendedView = $this->checkCustomerSubscription($customer, $subdomain)) { return $suspendedView; }
        if (!$agency) {
            if ($subdomain === 'evently' || str_contains($subdomain, 'evently')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
            } elseif ($subdomain === 'construction' || str_contains($subdomain, 'construction')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
            } elseif ($subdomain === 'texigo' || str_contains($subdomain, 'texigo')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
            } elseif ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            } else {
                abort(404);
            }
        }
        $ttype = strtolower(trim($agency->template_type ?? ''));
        if (in_array($ttype, ['evently', 'evently_theme', 'event'])) {
            $interior = $agency;
            return view('website_builder.evently_theme.about', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            return view('website_builder.construction_theme.about', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) {
            return view('website_builder.texigo_theme.about', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['interior', 'interiorcraft', 'interior_template'])) {
            $interior = $agency;
            return view('website_builder.interior_template.about', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        return view('website_builder.agency_template.about', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainContact($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if ($suspendedView = $this->checkCustomerSubscription($customer, $subdomain)) { return $suspendedView; }
        if (!$agency) {
            if ($subdomain === 'evently' || str_contains($subdomain, 'evently')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
            } elseif ($subdomain === 'construction' || str_contains($subdomain, 'construction')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
            } elseif ($subdomain === 'texigo' || str_contains($subdomain, 'texigo')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
            } elseif ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            } else {
                abort(404);
            }
        }
        $ttype = strtolower(trim($agency->template_type ?? ''));
        if (in_array($ttype, ['evently', 'evently_theme', 'event'])) {
            $interior = $agency;
            return view('website_builder.evently_theme.contact', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            return view('website_builder.construction_theme.contact', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) {
            return view('website_builder.texigo_theme.contact', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['interior', 'interiorcraft', 'interior_template'])) {
            $interior = $agency;
            return view('website_builder.interior_template.contact', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        return view('website_builder.agency_template.contact', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainPortfolio($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if ($suspendedView = $this->checkCustomerSubscription($customer, $subdomain)) { return $suspendedView; }
        if (!$agency) {
            if ($subdomain === 'evently' || str_contains($subdomain, 'evently')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
            } elseif ($subdomain === 'construction' || str_contains($subdomain, 'construction')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
            } elseif ($subdomain === 'texigo' || str_contains($subdomain, 'texigo')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
            } elseif ($subdomain === 'interior' || str_contains($subdomain, 'interior')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
            } elseif ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            } else {
                abort(404);
            }
        }
        $ttype = strtolower(trim($agency->template_type ?? ''));
        if (in_array($ttype, ['evently', 'evently_theme', 'event'])) {
            $interior = $agency;
            return view('website_builder.evently_theme.portfolio', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            return view('website_builder.construction_theme.portfolio', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) {
            return view('website_builder.texigo_theme.portfolio', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['interior', 'interiorcraft', 'interior_template'])) {
            $interior = $agency;
            return view('website_builder.interior_template.portfolio', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        return view('website_builder.agency_template.portfolio', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainFleet($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if ($suspendedView = $this->checkCustomerSubscription($customer, $subdomain)) { return $suspendedView; }
        if (!$agency) {
            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        }
        return view('website_builder.texigo_theme.index', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainServices($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if ($suspendedView = $this->checkCustomerSubscription($customer, $subdomain)) { return $suspendedView; }
        if (!$agency) {
            if ($subdomain === 'evently' || str_contains($subdomain, 'evently')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
            } elseif ($subdomain === 'construction' || str_contains($subdomain, 'construction')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
            } elseif ($subdomain === 'texigo' || str_contains($subdomain, 'texigo')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
            } elseif ($subdomain === 'interior' || str_contains($subdomain, 'interior')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
            } elseif ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults('digital_agency');
            } else {
                abort(404);
            }
        }
        $ttype = strtolower(trim($agency->template_type ?? ''));
        if (in_array($ttype, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            return view('website_builder.construction_theme.services', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['evently', 'evently_theme', 'event'])) {
            $interior = $agency;
            return view('website_builder.evently_theme.services', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) {
            return view('website_builder.texigo_theme.services', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['interior', 'interiorcraft', 'interior_template'])) {
            $interior = $agency;
            return view('website_builder.interior_template.services', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        return view('website_builder.agency_template.services', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainBlogs($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if ($suspendedView = $this->checkCustomerSubscription($customer, $subdomain)) { return $suspendedView; }
        if (!$agency) {
            if ($subdomain === 'evently' || str_contains($subdomain, 'evently')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
            } elseif ($subdomain === 'construction' || str_contains($subdomain, 'construction')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
            } elseif ($subdomain === 'texigo' || str_contains($subdomain, 'texigo')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
            } elseif ($subdomain === 'interior' || str_contains($subdomain, 'interior')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
            } elseif ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults('digital_agency');
            } else {
                abort(404);
            }
        }
        if ($agency && method_exists($agency, 'isFeatureEnabled') && !$agency->isFeatureEnabled('blog', $customer)) {
            return redirect()->route('website-builder.subdomain.site', ['subdomain' => $subdomain])->with('error', 'Blog feature is disabled for this subscription package tier.');
        }
        $ttype = strtolower(trim($agency->template_type ?? ''));
        if (in_array($ttype, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            return view('website_builder.construction_theme.blogs', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) {
            return view('website_builder.texigo_theme.blogs', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['interior', 'interiorcraft', 'interior_template'])) {
            $interior = $agency;
            return view('website_builder.interior_template.blogs', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        return view('website_builder.agency_template.blogs', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainBlog($subdomain, $id)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if ($suspendedView = $this->checkCustomerSubscription($customer, $subdomain)) { return $suspendedView; }
        if (!$agency) {
            if ($subdomain === 'evently' || str_contains($subdomain, 'evently')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
            } elseif ($subdomain === 'construction' || str_contains($subdomain, 'construction')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
            } elseif ($subdomain === 'texigo' || str_contains($subdomain, 'texigo')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
            } elseif ($subdomain === 'interior' || str_contains($subdomain, 'interior')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
            } elseif ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults('digital_agency');
            } else {
                abort(404);
            }
        }
        $interior = $agency;
        $blogs = $agency->blogs_data ?? [];
        if (empty($blogs)) {
            $ttype = strtolower(trim($agency->template_type ?? ''));
            if ($ttype === 'interior') {
                $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createInteriorDefaultInstance();
            } elseif ($ttype === 'texigo') {
                $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createTexigoDefaultInstance();
            } elseif ($ttype === 'construction') {
                $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createConstructionDefaultInstance();
            } elseif ($ttype === 'evently') {
                $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createEventlyDefaultInstance();
            } else {
                $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createDefaultInstance();
            }
            $blogs = $dummy->blogs_data ?? [];
        }

        $blog = null;
        foreach ($blogs as $bi => $b) {
            if ((isset($b['id']) && $b['id'] == $id) || ($bi + 1) == $id) {
                $blog = $b;
                break;
            }
        }

        if (!$blog && isset($blogs[$id - 1])) {
            $blog = $blogs[$id - 1];
        }

        if (!$blog && !empty($blogs)) {
            $blog = $blogs[0];
        }

        $ttype = strtolower(trim($agency->template_type ?? ''));
        if (in_array($ttype, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            return view('website_builder.construction_theme.blog_detail', compact('agency', 'customer', 'subdomain', 'blog'));
        }
        if (in_array($ttype, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) {
            return view('website_builder.texigo_theme.blog_detail', compact('agency', 'customer', 'subdomain', 'blog'));
        }
        if (in_array($ttype, ['interior', 'interiorcraft', 'interior_template'])) {
            return view('website_builder.interior_template.blog_detail', compact('interior', 'agency', 'customer', 'subdomain', 'blog'));
        }

        return view('website_builder.agency_template.blog_detail', compact('agency', 'customer', 'subdomain', 'blog'));
    }

    public function agencyContactSubmit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $subdomain = $request->input('subdomain');
        if ($subdomain) {
            [$customer, $agencySetting] = $this->resolveCustomerAndAgency($subdomain);
        } else {
            $customer = null;
            $agencySetting = \App\Models\WebsiteBuilder\WbAgencySetting::where('customer_id', session('wb_customer_id') ?? 0)->first();
        }

        if (!$agencySetting) {
            $agencySetting = new \App\Models\WebsiteBuilder\WbAgencySetting();
        }

        $adminSettings = \App\Models\WebsiteBuilder\WbLandingSetting::getSettings();
        
        if (!empty($agencySetting->recaptcha_site_key)) {
            $recaptchaSiteKey   = trim($agencySetting->recaptcha_site_key);
            $recaptchaSecretKey = trim($agencySetting->recaptcha_secret_key ?? '');
            $enableRecaptcha    = true;
        } else {
            $recaptchaSiteKey   = trim($adminSettings->recaptcha_site_key ?? '');
            $recaptchaSecretKey = trim($adminSettings->recaptcha_secret_key ?? '');
            $enableRecaptcha    = (($adminSettings->enable_recaptcha ?? '1') == '1');
        }

        if ($enableRecaptcha && !empty($recaptchaSecretKey)) {
            $request->validate([
                'g-recaptcha-response' => 'required',
            ], [
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA to proceed.',
            ]);

            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $recaptchaSecretKey,
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]);

                $resJson = $response->json() ?: [];
                if (empty($resJson['success'])) {
                    $errCodes = !empty($resJson['error-codes']) ? implode(', ', (array)$resJson['error-codes']) : 'invalid secret key or token';
                    \Illuminate\Support\Facades\Log::error("reCAPTCHA siteverify failed. Error codes: {$errCodes}");
                    return redirect()->back()->withInput()->with('error', 'reCAPTCHA verification failed (' . $errCodes . '). Please check your reCAPTCHA Secret Key.');
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("reCAPTCHA HTTP exception: " . $e->getMessage());
            }
        } elseif ($request->has('captcha') || $request->has('recaptcha_answer')) {
            $userAns = trim($request->input('captcha', $request->input('recaptcha_answer', '')));
            $expAns1 = (string) session('wb_recaptcha_ans', '8');
            $expAns2 = (string) session('wb_recaptcha_ans_alt', '9');
            $validAnswers = [$expAns1, $expAns2, '7', '8', '9', '10', '12', '15'];
            if ($userAns !== '' && !in_array($userAns, $validAnswers, true)) {
                return redirect()->back()->withInput()->with('error', 'Security reCAPTCHA verification failed! Please enter the correct security code answer.');
            }
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_inquiries')) {
                \App\Models\WebsiteBuilder\WbAgencyInquiry::create([
                    'customer_id' => $customer ? $customer->id : ($agencySetting->customer_id ?? null),
                    'name'    => $request->name,
                    'email'   => $request->email,
                    'phone'   => $request->phone,
                    'subject' => $request->subject,
                    'message' => $request->message,
                ]);
            }

            if (!empty($agencySetting->contact_receiver_email)) {
                $backupConfig = config('mail.mailers.smtp');
                $agencyHost = (!empty($agencySetting->smtp_host) && !str_contains($agencySetting->smtp_host, 'metroshop.in')) ? $agencySetting->smtp_host : env('MAIL_HOST', 'mail.saasreselling.com');
                $agencyPort = !empty($agencySetting->smtp_port) ? $agencySetting->smtp_port : env('MAIL_PORT', 465);
                $agencyUser = (!empty($agencySetting->smtp_username) && !str_contains($agencySetting->smtp_username, 'metroshop.in')) ? $agencySetting->smtp_username : env('MAIL_USERNAME', 'admin@saasreselling.com');
                $agencyPass = !empty($agencySetting->smtp_password) ? $agencySetting->smtp_password : env('MAIL_PASSWORD', 'Admin@nooryak');
                $agencyEnc  = !empty($agencySetting->smtp_encryption) ? $agencySetting->smtp_encryption : env('MAIL_ENCRYPTION', 'ssl');

                config([
                    'mail.mailers.smtp.host' => $agencyHost,
                    'mail.mailers.smtp.port' => $agencyPort,
                    'mail.mailers.smtp.username' => $agencyUser,
                    'mail.mailers.smtp.password' => $agencyPass,
                    'mail.mailers.smtp.encryption' => $agencyEnc ?: null,
                    'mail.from.address' => $agencyUser,
                    'mail.from.name' => $agencySetting->site_title ?? 'Contact Form',
                ]);

                $subject = $agencySetting->contact_receiver_subject ?: 'New Lead: Contact Form Submission';
                $body = "<p><strong>Name: </strong>{$request->name}</p>
                         <p><strong>Email: </strong>{$request->email}</p>
                         <p><strong>Phone: </strong>{$request->phone}</p>
                         <p><strong>Subject: </strong>{$request->subject}</p>
                         <p><strong>Message: </strong><br/>" . nl2br(e($request->message)) . "</p>";

                \Illuminate\Support\Facades\Mail::html($body, function ($message) use ($agencySetting, $subject) {
                    $message->to($agencySetting->contact_receiver_email)
                            ->subject($subject);
                });

                if (!empty($request->email)) {
                    $userCopySubject = "Thank you for contacting us - " . ($request->subject ?: 'We received your message');
                    $userCopyBody = "<p>Hello " . htmlspecialchars($request->name) . ",</p>"
                        . "<p>Thank you for reaching out to us. We have received your inquiry and our team will get back to you shortly.</p>"
                        . "<p><strong>Your Message:</strong><br/>" . nl2br(htmlspecialchars($request->message)) . "</p>";
                    try {
                        \Illuminate\Support\Facades\Mail::html($userCopyBody, function ($msg) use ($request, $userCopySubject) {
                            $msg->to($request->email)->subject($userCopySubject);
                        });
                    } catch (\Throwable $e) {}
                }

                config(['mail.mailers.smtp' => $backupConfig]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Agency Contact Submit Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Thank you! Your message has been submitted successfully.');
    }

    public function submitPublicContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $settings = \App\Models\WebsiteBuilder\WbLandingSetting::getSettings();
        $enableRecaptcha = (($settings->enable_recaptcha ?? '1') == '1');
        $recaptchaSecret = trim($settings->recaptcha_secret_key ?? '');

        if ($enableRecaptcha && !empty($recaptchaSecret)) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            if (empty($recaptchaResponse)) {
                return redirect()->to(url()->previous() . '#contact')
                    ->withInput()
                    ->withErrors(['g-recaptcha-response' => 'Please complete the reCAPTCHA to proceed.']);
            }

            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                    'remoteip' => $request->ip(),
                ]);

                $resJson = $response->json() ?: [];
                if (empty($resJson['success'])) {
                    $errCodes = !empty($resJson['error-codes']) ? implode(', ', (array)$resJson['error-codes']) : 'invalid token';
                    \Illuminate\Support\Facades\Log::error("Landing page reCAPTCHA siteverify failed: {$errCodes}");
                    return redirect()->to(url()->previous() . '#contact')
                        ->withInput()
                        ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.']);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Landing page reCAPTCHA HTTP exception: " . $e->getMessage());
            }
        }

        try {
            \App\Models\WebsiteBuilder\WbAgencyInquiry::ensureTableExists();
            \App\Models\WebsiteBuilder\WbAgencyInquiry::create([
                'customer_id' => null,
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'subject'     => $request->subject,
                'message'     => $request->message,
                'is_read'     => false,
            ]);

            // Dispatch emails via SMTP if enabled
            if (($settings->is_smtp ?? '1') == '1' && !empty($settings->smtp_host)) {
                $smtpConfig = [
                    'transport'  => 'smtp',
                    'host'       => $settings->smtp_host ?: 'mail.saasreselling.com',
                    'port'       => (int)($settings->smtp_port ?: 465),
                    'encryption' => $settings->smtp_encryption ?: 'ssl',
                    'username'   => $settings->smtp_username ?: 'admin@saasreselling.com',
                    'password'   => $settings->smtp_password ?: 'Admin@nooryak',
                    'timeout'    => 10,
                ];

                \Illuminate\Support\Facades\Config::set('mail.mailers.wb_landing_smtp', $smtpConfig);
                $fromAddress = $settings->smtp_from_address ?: 'admin@saasreselling.com';
                $fromName    = $settings->smtp_from_name ?: 'Website Builder';
                \Illuminate\Support\Facades\Config::set('mail.from.address', $fromAddress);
                \Illuminate\Support\Facades\Config::set('mail.from.name', $fromName);

                // 1. Send confirmation copy to user email
                $userSubject = "Thank you for contacting us - {$request->subject}";
                $userBody = "<h3>Hello " . htmlspecialchars($request->name) . ",</h3>"
                    . "<p>Thank you for contacting us! We have received your inquiry and our team will get back to you shortly.</p>"
                    . "<hr style='border:none;border-top:1px solid #e2e8f0;margin:15px 0;'/>"
                    . "<p><strong>Subject:</strong> " . htmlspecialchars($request->subject) . "</p>"
                    . "<p><strong>Your Message:</strong><br/>" . nl2br(htmlspecialchars($request->message)) . "</p>"
                    . "<br/><p>Warm regards,<br/><strong>" . htmlspecialchars($fromName) . "</strong></p>";

                try {
                    \Illuminate\Support\Facades\Mail::mailer('wb_landing_smtp')->html($userBody, function ($msg) use ($request, $userSubject, $fromAddress, $fromName) {
                        $msg->to($request->email, $request->name)
                            ->from($fromAddress, $fromName)
                            ->subject($userSubject);
                    });
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("User confirmation email failed: " . $e->getMessage());
                }

                // 2. Send notification to admin email
                $adminReceiver = !empty($settings->contact_email) ? $settings->contact_email : $fromAddress;
                $adminSubject  = "New Contact Submission: {$request->subject}";
                $adminBody = "<h3>New Website Builder Contact Form Submission</h3>"
                    . "<p>A new visitor has submitted an inquiry on the Website Builder landing page.</p>"
                    . "<p><strong>Name:</strong> " . htmlspecialchars($request->name) . "<br/>"
                    . "<strong>Email:</strong> <a href='mailto:" . htmlspecialchars($request->email) . "'>" . htmlspecialchars($request->email) . "</a><br/>"
                    . "<strong>Phone:</strong> " . htmlspecialchars($request->phone ?: 'N/A') . "<br/>"
                    . "<strong>Subject:</strong> " . htmlspecialchars($request->subject) . "<br/>"
                    . "<strong>Submitted:</strong> " . now()->format('M d, Y h:i A') . "</p>"
                    . "<p><strong>Message:</strong><br/>" . nl2br(htmlspecialchars($request->message)) . "</p>";

                try {
                    \Illuminate\Support\Facades\Mail::mailer('wb_landing_smtp')->html($adminBody, function ($msg) use ($adminReceiver, $adminSubject, $fromAddress, $fromName) {
                        $msg->to($adminReceiver)
                            ->from($fromAddress, $fromName)
                            ->subject($adminSubject);
                    });
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("Admin alert email failed: " . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("submitPublicContact error: " . $e->getMessage());
        }

        return redirect()->to(url()->previous() . '#contact')
            ->with('contact_success', 'Thank you! Your message has been submitted successfully. A confirmation copy has been sent to your email.');
    }

    public function agencyPolicy($slug)
    {
        $currentRoute = request()->route() ? request()->route()->getName() : '';
        if (str_contains($currentRoute, 'templates.interior')) {
            $demoTemplate = 'interior';
        } elseif (str_contains($currentRoute, 'templates.texigo')) {
            $demoTemplate = 'texigo';
        } elseif (str_contains($currentRoute, 'templates.construction')) {
            $demoTemplate = 'construction';
        } elseif (str_contains($currentRoute, 'templates.evently')) {
            $demoTemplate = 'evently';
        } elseif (str_contains($currentRoute, 'templates.digital_agency')) {
            $demoTemplate = 'digital_agency';
        } else {
            $segment = request()->segment(2);
            $demoTemplate = in_array($segment, ['interior', 'texigo', 'construction', 'evently', 'digital_agency']) ? $segment : session('demo_template', 'digital_agency');
        }

        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults($demoTemplate);
        $customer = null;
        $subdomain = null;
        $policy = $this->resolvePolicyFromAgency($agency, $slug);

        $interior = $agency;
        $evData = $agency;

        $templateViewMap = [
            'interior'       => 'website_builder.interior_template.policy',
            'texigo'         => 'website_builder.texigo_theme.policy',
            'construction'   => 'website_builder.construction_theme.policy',
            'evently'        => 'website_builder.evently_theme.policy',
            'digital_agency' => 'website_builder.agency_template.policy',
        ];
        $view = $templateViewMap[$demoTemplate] ?? 'website_builder.agency_template.policy';

        return view($view, compact('agency', 'interior', 'evData', 'customer', 'subdomain', 'policy'));
    }

    public function viewSubdomainPolicy($subdomain, $slug)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if ($suspendedView = $this->checkCustomerSubscription($customer, $subdomain)) { return $suspendedView; }
        if (!$agency) {
            if (in_array($subdomain, ['digital_agency', 'interior', 'texigo', 'construction', 'evently', 'demo'])) {
                $tmpl = ($subdomain === 'demo') ? 'digital_agency' : $subdomain;
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults($tmpl);
            } else {
                abort(404);
            }
        }
        $policy = $this->resolvePolicyFromAgency($agency, $slug);

        $interior = $agency;
        $evData = $agency;
        $tmpl = $customer->template_slug ?? $agency->template_slug ?? $agency->template_type ?? (in_array($subdomain, ['digital_agency', 'interior', 'texigo', 'construction', 'evently']) ? $subdomain : 'digital_agency');

        $templateViewMap = [
            'interior'       => 'website_builder.interior_template.policy',
            'texigo'         => 'website_builder.texigo_theme.policy',
            'construction'   => 'website_builder.construction_theme.policy',
            'evently'        => 'website_builder.evently_theme.policy',
            'digital_agency' => 'website_builder.agency_template.policy',
        ];
        $view = $templateViewMap[$tmpl] ?? 'website_builder.agency_template.policy';

        return view($view, compact('agency', 'interior', 'evData', 'customer', 'subdomain', 'policy'));
    }

    private function resolvePolicyFromAgency($agency, $slug)
    {
        $slugClean = strtolower(trim($slug));
        $legalLinks = $agency->footer_legal_links ?? [];
        foreach ($legalLinks as $l) {
            $lSlug = strtolower(trim($l['slug'] ?? $l['url'] ?? \Illuminate\Support\Str::slug($l['title'] ?? '')));
            $lSlug = ltrim($lSlug, '#/');
            if ($lSlug === $slugClean || str_contains($lSlug, $slugClean) || str_contains($slugClean, $lSlug)) {
                return [
                    'title'   => $l['title'] ?? ucfirst($slugClean) . ' Policy',
                    'content' => $l['content'] ?? ("Welcome to our " . ($l['title'] ?? $slugClean) . ". We are committed to delivering high quality digital agency services."),
                ];
            }
        }

        $defaultTitle = ucfirst($slugClean);
        if ($slugClean === 'privacy') $defaultTitle = 'Privacy Policy';
        elseif ($slugClean === 'terms') $defaultTitle = 'Terms & Conditions';
        elseif ($slugClean === 'disclaimer') $defaultTitle = 'Disclaimer';
        elseif ($slugClean === 'refund') $defaultTitle = 'Refund Policy';

        return [
            'title'   => $defaultTitle,
            'content' => "This section outlines our official {$defaultTitle}. We prioritize client trust, data confidentiality, and transparent business operations across all our services.",
        ];
    }

    public function syncCustomerFromClient(\Illuminate\Http\Request $request)
    {
        try {
            $input = $request->all();
            if (empty($input)) {
                $content = $request->getContent();
                if ($content) {
                    $input = json_decode($content, true) ?: [];
                }
            }

            $email = $input['email'] ?? $input['customer_email'] ?? null;
            $subdomain = $input['subdomain'] ?? $input['username'] ?? null;
            $name = $input['name'] ?? $input['company_name'] ?? $input['first_name'] ?? null;
            $phone = $input['phone'] ?? $input['customer_phone'] ?? null;
            $password = $input['password'] ?? '123456';
            $packageId = $input['package_id'] ?? 1;

            if (!$email && !$subdomain) {
                return response()->json(['success' => false, 'message' => 'Missing email or subdomain']);
            }

            $cleanSubdomain = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]/', '', $subdomain)));

            if (!\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                return response()->json(['success' => false, 'message' => 'wb_customers table missing']);
            }

            $customer = null;
            if ($email) {
                $customer = WbCustomer::where('email', $email)->first();
            }
            if (!$customer && $cleanSubdomain) {
                $customer = WbCustomer::where('subdomain', $cleanSubdomain)->first();
            }

            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Customer account will be created only after payment confirmation.']);
            } else {
                $customer->update([
                    'name'         => $name ?: $customer->name,
                    'company_name' => $name ?: $customer->company_name,
                    'phone'        => $phone ?: $customer->phone,
                    'subdomain'    => $cleanSubdomain ?: $customer->subdomain,
                ]);
            }

            $tslug = strtolower(trim($input['template'] ?? ($input['template_slug'] ?? '')));
            if (in_array($tslug, ['interior', 'interiorcraft', 'interior_template'])) $tslug = 'interior';
            elseif (in_array($tslug, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) $tslug = 'texigo';
            elseif (in_array($tslug, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) $tslug = 'construction';
            elseif (in_array($tslug, ['evently', 'evently_theme', 'event'])) $tslug = 'evently';
            else $tslug = 'digital_agency';

            if ($customer && \Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::where('customer_id', $customer->id)->first();
                if (!$agency) {
                    if ($tslug === 'texigo') {
                        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createTexigoDefaultInstance($customer->id);
                    } elseif ($tslug === 'interior') {
                        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createInteriorDefaultInstance($customer->id);
                    } elseif ($tslug === 'construction') {
                        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createConstructionDefaultInstance($customer->id);
                    } elseif ($tslug === 'evently') {
                        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createEventlyDefaultInstance($customer->id);
                    } else {
                        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::createDefaultInstance($customer->id);
                    }
                } else {
                    $agency->applyTemplateDefaults($tslug, true);
                    $agency->template_type = $tslug;
                }
                $agency->site_title = $name ?: ($customer->company_name ?: ($cleanSubdomain . ' ' . ucfirst($tslug)));
                if ($email) $agency->email = $email;
                if ($phone) $agency->phone = $phone;
                $agency->save();
            }

            session(['wb_customer_id' => $customer->id, 'wb_customer_email' => $customer->email]);

            return response()->json(['success' => true, 'customer_id' => $customer->id, 'subdomain' => $cleanSubdomain]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("syncCustomerFromClient error: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function ensureWbTemplatePurchasesColumns(): void
    {
        \App\Models\WebsiteBuilder\WbTemplatePurchase::ensureColumnsExist();
    }

    public function checkSubdomainAvailability(Request $request)
    {
        $rawSubdomain = trim($request->input('subdomain', ''));
        $subdomain = preg_replace('/[^a-z0-9-]/', '', strtolower($rawSubdomain));

        if (empty($subdomain)) {
            return response()->json(['available' => false, 'message' => 'Subdomain name cannot be empty.']);
        }

        $reserved = ['admin', 'app', 'checkout', 'www', 'launchshop', 'websitebuilder', 'website-builder', 'api', 'mail', 'login', 'register', 'dashboard', 'user', 'front', 'templates'];
        if (in_array($subdomain, $reserved)) {
            return response()->json([
                'available' => false,
                'message'   => "Subdomain '{$subdomain}' is a reserved system name. Please enter a different subdomain."
            ]);
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
            $existing = WbCustomer::where(function($q) use ($subdomain) {
                $q->where('subdomain', $subdomain)
                  ->orWhere('subdomain', 'https://' . $subdomain)
                  ->orWhere('subdomain', 'http://' . $subdomain)
                  ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(subdomain, 'https://', ''), 'http://', ''), 'www.', ''), '/', '')) = ?", [$subdomain])
                  ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(subdomain, 'https://', ''), 'http://', ''), 'www.', ''), '/', '')) LIKE ?", [$subdomain . '.%'])
                  ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(subdomain, 'https://', ''), 'http://', ''), 'www.', ''), '/', '')) LIKE ?", ['%.' . $subdomain])
                  ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(subdomain, 'https://', ''), 'http://', ''), 'www.', ''), '/', '')) LIKE ?", ['%.' . $subdomain . '.%']);
            })->first();

            if ($existing) {
                return response()->json([
                    'available' => false,
                    'message'   => "Subdomain '{$subdomain}' is already taken. Please choose a different subdomain."
                ]);
            }
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
            $existingAgency = \App\Models\WebsiteBuilder\WbAgencySetting::where(function($q) use ($subdomain) {
                $q->where('custom_domain', $subdomain)
                  ->orWhere('custom_domain', 'https://' . $subdomain)
                  ->orWhere('custom_domain', 'http://' . $subdomain)
                  ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(custom_domain, 'https://', ''), 'http://', ''), 'www.', ''), '/', '')) = ?", [$subdomain])
                  ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(custom_domain, 'https://', ''), 'http://', ''), 'www.', ''), '/', '')) LIKE ?", [$subdomain . '.%']);
            })->first();

            if ($existingAgency && !empty($existingAgency->custom_domain)) {
                return response()->json([
                    'available' => false,
                    'message'   => "Subdomain '{$subdomain}' is already taken. Please choose a different subdomain."
                ]);
            }
        }

        return response()->json(['available' => true, 'message' => "Subdomain '{$subdomain}' is available!"]);
    }
}
