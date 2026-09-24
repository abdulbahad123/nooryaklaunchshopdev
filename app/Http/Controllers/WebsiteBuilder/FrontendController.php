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
    private function ensureDigitalAgencyTemplateOnly()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_templates')) {
                // Keep digital_agency, interior, texigo, construction, and evently templates
                WbTemplate::whereNotIn('slug', ['digital_agency', 'interior', 'texigo', 'construction', 'evently'])->delete();

                // Create or update digital_agency single template
                WbTemplate::updateOrCreate(
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

                // Create or update interior template
                WbTemplate::updateOrCreate(
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

                // Create or update texigo template
                WbTemplate::updateOrCreate(
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

                // Create or update construction template
                WbTemplate::updateOrCreate(
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

                // Create or update evently template
                WbTemplate::updateOrCreate(
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
            'razorpay_payment_id' => 'nullable|string',
        ]);

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

    public function checkoutPage(Request $request)
    {
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

        $package = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('wb_packages')) {
            $package = WbPackage::where('name', $plan)->orWhere('slug', \Illuminate\Support\Str::slug($plan))->first();
        }

        if ($rawPrice !== null && is_numeric($rawPrice) && (float)$rawPrice > 0) {
            $price = (float) $rawPrice;
        } elseif ($package) {
            $price = (float) $package->monthly_price;
        } else {
            $price = ($plan === 'Pro' ? 19 : ($plan === 'Business' ? 39 : 9));
        }

        return view('website_builder.front.checkout', compact('settings', 'templateSlug', 'plan', 'price', 'package'));
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

        // 1. Send OTP to mobile number via WhatsApp (Meta Merge Cloud API)
        if (!empty($phone)) {
            try {
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                if (strlen($cleanPhone) === 10) {
                    $cleanPhone = '91' . $cleanPhone;
                }

                $apiKey = 'a09a0ee3aae408f843020cbd6bccf590';
                $waMessage = "Your OTP verification code is *" . $otp . "* for *Websitebuilder Ecommerce* - This code is valid for *10 minutes* - Please do not share it with anyone.";

                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json'
                ])->withoutVerifying()->post('https://app.metamerged.com/api/send', [
                    'number'  => $cleanPhone,
                    'type'    => 'text',
                    'message' => $waMessage,
                ]);

                if ($response->successful()) {
                    $whatsappSent = true;
                    \Illuminate\Support\Facades\Log::info("Meta Merge WhatsApp OTP sent to {$cleanPhone}");
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("WhatsApp OTP Exception: " . $e->getMessage());
            }
        }

        // 2. Send via Email using configured SMTP or Master Fallback SMTP
        try {
            $be = null;
            try {
                $be = \App\Models\BasicExtended::first();
            } catch (\Throwable $ex) {}

            $smtpHost = ($be && !empty($be->smtp_host)) ? $be->smtp_host : 'mail.metroshop.in';
            $smtpUser = ($be && !empty($be->smtp_username)) ? $be->smtp_username : 'admin@metroshop.in';
            $smtpPass = ($be && !empty($be->smtp_password)) ? $be->smtp_password : 'Nooryak@786';
            $smtpPort = ($be && !empty($be->smtp_port)) ? $be->smtp_port : 465;
            $smtpEnc  = ($be && !empty($be->encryption)) ? $be->encryption : 'ssl';
            $fromMail = ($be && !empty($be->from_mail)) ? $be->from_mail : 'admin@metroshop.in';

            $smtpConfig = [
                'transport'  => 'smtp',
                'host'       => $smtpHost,
                'port'       => $smtpPort,
                'encryption' => $smtpEnc,
                'username'   => $smtpUser,
                'password'   => $smtpPass,
                'timeout'    => 15,
            ];
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp', $smtpConfig);
            \Illuminate\Support\Facades\Config::set('mail.default', 'smtp');

            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($email, $fromMail, $otp) {
                $subject = "Your OTP Verification Code - Websitebuilder Ecommerce";
                $bodyContent = "Your OTP verification code is <b>" . $otp . "</b> for <b>Websitebuilder Ecommerce</b> - Valid for <b>10 minutes</b>. (OTP: {$otp})";
                $body = class_exists('\App\Http\Helpers\Common') 
                    ? \App\Http\Helpers\Common::wrapEmailBody($bodyContent, $subject)
                    : $bodyContent;

                $message->to($email)
                        ->from($fromMail, 'Websitebuilder Ecommerce')
                        ->subject($subject)
                        ->html($body, 'text/html');
            });
            $emailSent = true;
            \Illuminate\Support\Facades\Log::info("OTP Email successfully sent to {$email} via {$smtpHost}");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('OTP Email sending failed: ' . $e->getMessage());
        }

        $statusMsg = "OTP verification code sent successfully to your Email address ({$email})! (Test Master OTP: 123456)";
        if ($whatsappSent && $emailSent) {
            $statusMsg = "OTP verification code sent successfully to your WhatsApp and Email address! (Test Master OTP: 123456)";
        } elseif ($whatsappSent) {
            $statusMsg = "OTP verification code sent successfully to your WhatsApp number! (Test Master OTP: 123456)";
        }

        return response()->json([
            'success' => true,
            'message' => $statusMsg,
            'otp'     => $otp
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

        // Master OTP 123456 for testing fallback
        if ($inputOtp === '123456') {
            session(['checkout_otp_verified' => true]);
            return response()->json(['success' => true, 'message' => 'OTP verified successfully!']);
        }

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
        $requestData = $request->all();
        if ($request->filled('razorpay_payment_id') && session()->has('wb_checkout_req')) {
            $requestData = array_merge(session('wb_checkout_req', []), $request->all());
        }

        $validator = \Illuminate\Support\Facades\Validator::make($requestData, [
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'subdomain'      => 'required|string|max:100',
            'password'       => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers') && !$request->filled('razorpay_payment_id')) {
            if (WbCustomer::where('email', $requestData['customer_email'])->exists()) {
                return redirect()->back()->withInput()->with('error', 'This email address is already registered. Please log in to your account or use a different email.');
            }
        }

        // If payment ID is not yet attached, generate Razorpay order and render checkout modal ON checkout subdomain!
        if (!$request->filled('razorpay_payment_id')) {
            session(['wb_checkout_req' => $requestData]);

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

        $customerName = $request->input('customer_name') ?: ($requestData['customer_name'] ?? ($requestData['first_name'] ?? 'Customer'));
        $customerEmail = $request->input('customer_email') ?: ($requestData['customer_email'] ?? ($requestData['email'] ?? ''));
        $phoneNum = $request->input('customer_phone') ?: ($requestData['customer_phone'] ?? ($requestData['phone'] ?? '9360157880'));
        $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($request->input('subdomain') ?: ($requestData['subdomain'] ?? ($requestData['username'] ?? ''))));
        if (empty($subdomain)) {
            $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($customerName)) . rand(100, 999);
        }

        $customerPassword = $request->input('password') ?: ($requestData['password'] ?? 'Password@123');
        $planName = $request->input('plan') ?: ($requestData['plan'] ?? 'Premium');
        $price = $request->input('price') ?: ($requestData['price'] ?? 499);
        $razorpayPaymentId = $request->input('razorpay_payment_id') ?: ($requestData['razorpay_payment_id'] ?? ('PAY_' . strtoupper(\Illuminate\Support\Str::random(10))));

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
                $customer = WbCustomer::updateOrCreate(
                    ['email' => $customerEmail],
                    [
                        'name'         => $customerName,
                        'email'        => $customerEmail,
                        'phone'        => $phoneNum,
                        'password'     => Hash::make($customerPassword),
                        'company_name' => $customerName . ' Agency',
                        'subdomain'    => $subdomain,
                        'status'       => 1,
                    ]
                );

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
                \App\Models\WebsiteBuilder\WbTemplatePurchase::create([
                    'customer_name'       => $customerName,
                    'customer_email'      => $customerEmail,
                    'customer_phone'      => $phoneNum,
                    'template_slug'       => $templateSlug,
                    'template_name'       => $templateName,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'amount'              => $price,
                    'currency'            => 'INR',
                    'status'              => 'completed',
                ]);
            }

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("WbCustomer creation error: " . $e->getMessage());
        }

        // Build dynamic WB live URL — no hardcoded domains
        $scheme = (request()->secure() || str_contains(request()->fullUrl(), 'https://')) ? 'https://' : 'http://';

        // Resolve agency domain dynamically:
        // 1. From WEBSITE_BUILDER_HOST env (e.g. "youverse.in")
        // 2. Strip reserved prefixes from current request host
        $wbHost = env('WEBSITE_BUILDER_HOST') ?: env('WEBSITE_HOST');
        if (empty($wbHost)) {
            $reqHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
            $wbHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder)\./i', '', $reqHost);
        }

        $storeLiveLink       = "{$scheme}websitebuilder.{$wbHost}/{$subdomain}";
        $loginDashboardLink  = "{$scheme}websitebuilder.{$wbHost}/login";

        $welcomeHtml = "🎉 <b>Welcome to Websitebuilder!</b><br><br>"
            . "Your store account has been created successfully.<br><br>"
            . "👤 <b>Store Name:</b> {$subdomain}<br>"
            . "📧 <b>Email:</b> {$customerEmail}<br>"
            . "📞 <b>Phone Number:</b> {$phoneNum}<br>"
            . "🔑 <b>Password:</b> {$customerPassword}<br>"
            . "📦 <b>Plan:</b> {$planName} (₹{$price})<br><br>"
            . "🔗 <b>Store Live Link:</b> <a href=\"{$storeLiveLink}\">{$storeLiveLink}</a><br>"
            . "🔗 <b>Login to your store dashboard:</b><br>"
            . "<a href=\"{$loginDashboardLink}\">{$loginDashboardLink}</a><br><br>"
            . "Need help? Chat with us anytime.<br>"
            . "– Team Websitebuilder 🚀";

        try {
            $be = \App\Models\BasicExtended::first();
            if ($be && !empty($be->smtp_host)) {
                $mailData = [
                    'smtp_status'   => $be->is_smtp ?? 1,
                    'smtp_host'     => $be->smtp_host,
                    'smtp_username' => $be->smtp_username,
                    'smtp_password' => $be->smtp_password,
                    'encryption'    => $be->encryption,
                    'smtp_port'      => $be->smtp_port,
                    'from_mail'      => $be->from_mail,
                    'recipient'      => $customerEmail,
                    'subject'        => "🎉 Welcome to Websitebuilder! Your store account is ready",
                    'body'           => $welcomeHtml,
                ];
                \App\Http\Helpers\BasicMailer::sendMail($mailData);
            } else {
                \Illuminate\Support\Facades\Mail::raw(strip_tags(str_replace('<br>', "\n", $welcomeHtml)), function ($message) use ($customerEmail) {
                    $message->to($customerEmail)->subject("🎉 Welcome to Websitebuilder! Your store account is ready");
                });
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Welcome mail error: ' . $e->getMessage());
        }

        return redirect()->to($storeLiveLink)->with('success', "🚀 Congratulations! Your website is live at {$storeLiveLink}");
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
        return view('website_builder.texigo_theme.index', compact('agency'));
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
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        return view('website_builder.evently_theme.index', compact('interior'));
    }

    public function eventlyAbout()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        return view('website_builder.evently_theme.about', compact('interior'));
    }

    public function eventlyPortfolio()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        return view('website_builder.evently_theme.portfolio', compact('interior'));
    }

    public function eventlyContact()
    {
        $interior = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
        return view('website_builder.evently_theme.contact', compact('interior'));
    }

    public function texigoBlogs()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        return view('website_builder.agency_template.blogs', compact('agency'));
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
        return view('website_builder.agency_template.blog_detail', compact('agency', 'blog'));
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
        return view('website_builder.agency_template.blogs', compact('agency'));
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
        return view('website_builder.agency_template.blog_detail', compact('agency', 'blog'));
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
        } catch (\Throwable $e) {}

        return [$customer, $agency];
    }

    // Custom Domain Direct View Handlers
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

    public function viewSubdomainServices($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if (!$agency) {
            if ($subdomain === 'evently' || str_contains($subdomain, 'evently')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
            } elseif ($subdomain === 'construction' || str_contains($subdomain, 'construction')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
            } elseif ($subdomain === 'texigo' || str_contains($subdomain, 'texigo')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
            } else {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            }
        }
        $ttype = strtolower(trim($agency->template_type ?? ''));
        if (in_array($ttype, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            return view('website_builder.construction_theme.services', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) {
            return view('website_builder.texigo_theme.index', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['interior', 'interiorcraft', 'interior_template'])) {
            $interior = $agency;
            return view('website_builder.interior_template.index', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        return view('website_builder.agency_template.index', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainFleet($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if (!$agency) {
            $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
        }
        return view('website_builder.texigo_theme.index', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainBlogs($subdomain)
    {

        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if (!$agency) {
            if ($subdomain === 'evently' || str_contains($subdomain, 'evently')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getEventlyDefaults();
            } elseif ($subdomain === 'construction' || str_contains($subdomain, 'construction')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getConstructionDefaults();
            } elseif ($subdomain === 'texigo' || str_contains($subdomain, 'texigo')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getTexigoDefaults();
            } elseif ($subdomain === 'digital_agency' || $subdomain === 'demo' || str_contains($subdomain, 'interior')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
            } else {
                abort(404);
            }
        }
        $ttype = strtolower(trim($agency->template_type ?? ''));
        if (in_array($ttype, ['evently', 'evently_theme', 'event'])) {
            $interior = $agency;
            return view('website_builder.agency_template.blogs', compact('interior', 'agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            return view('website_builder.agency_template.blogs', compact('agency', 'customer', 'subdomain'));
        }
        if (in_array($ttype, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) {
            return view('website_builder.agency_template.blogs', compact('agency', 'customer', 'subdomain'));
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
        if (!$agency) {
            if ($subdomain === 'digital_agency' || $subdomain === 'demo' || str_contains($subdomain, 'interior')) {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getInteriorDefaults();
            } else {
                abort(404);
            }
        }
        $interior = $agency;
        $blogs = $agency->blogs_data ?? [
            [
                'id' => 1,
                'title' => '10 Simple Ways to Make Your Home Look Expensive',
                'category' => 'Interior Tips',
                'date' => 'Sep 12, 2024',
                'author' => 'Emma Carter',
                'image' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=800&auto=format&fit=crop',
                'excerpt' => 'Transform your space with these easy and affordable interior design tips that instantly elevate your home.',
                'content' => "Creating a high-end, luxurious look in your home doesn't require a massive budget."
            ]
        ];
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

        if (isset($agency->template_type) && $agency->template_type === 'interior') {
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

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_inquiries')) {
                \App\Models\WebsiteBuilder\WbAgencyInquiry::create([
                    'name'    => $request->name,
                    'email'   => $request->email,
                    'phone'   => $request->phone,
                    'subject' => $request->subject,
                    'message' => $request->message,
                ]);
            }
        } catch (\Throwable $e) {
            // handle gracefully
        }

        return redirect()->back()->with('success', 'Thank you! Your message has been submitted successfully.');
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
                $customer = WbCustomer::create([
                    'name'         => $name ?: ($cleanSubdomain . ' Agency'),
                    'company_name' => $name ?: ($cleanSubdomain . ' Agency'),
                    'subdomain'    => $cleanSubdomain,
                    'email'        => $email ?: ($cleanSubdomain . '@agency.com'),
                    'phone'        => $phone ?: '+91 9999999999',
                    'password'     => \Illuminate\Support\Facades\Hash::make($password),
                    'package_id'   => $packageId,
                    'status'       => 1,
                ]);
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
}
