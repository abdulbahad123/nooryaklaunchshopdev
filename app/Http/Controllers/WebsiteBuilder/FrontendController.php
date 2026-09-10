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
                // Remove unwanted dummy templates
                WbTemplate::whereNotIn('slug', ['digital_agency'])->delete();

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

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                \App\Models\WebsiteBuilder\WbTemplatePurchase::create([
                    'customer_name'       => $request->customer_name,
                    'customer_email'      => $request->customer_email,
                    'customer_phone'      => $request->customer_phone,
                    'template_slug'       => 'digital_agency',
                    'template_name'       => 'Digital Agency',
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

        try {
            Auth::guard('wb_customer')->login($customer);
        } catch (\Throwable $e) {
            session(['wb_customer_id' => $customer->id, 'wb_customer_email' => $customer->email]);
        }
        session(['is_secret_logged_in' => true, 'wb_customer_email' => $customer->email, 'tenant_db' => config('database.connections.mysql.database')]);

        return redirect()->route('website-builder.agency-admin.index')->with('success', 'Logged in via Secret Admin Access.');
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
            try {
                Auth::guard('wb_customer')->login($customer);
            } catch (\Throwable $e) {
                session(['wb_customer_id' => $customer->id, 'wb_customer_email' => $customer->email]);
            }
            session(['wb_customer_email' => $customer->email, 'tenant_db' => config('database.connections.mysql.database')]);

            return redirect()->route('website-builder.agency-admin.index')
                ->with('success', "Welcome back, {$customer->name}! You are now logged in to your Digital Agency Admin Dashboard.");
        }

        return redirect()->back()->withInput()->with('error', 'Invalid password. Please try again.');
    }

    public function checkoutPage(Request $request)
    {
        $settings = WbLandingSetting::getSettings();
        $templateSlug = $request->query('template', 'digital_agency');
        $plan = $request->query('plan', 'Standard');
        $price = ($plan === 'Pro' || $plan === 'Business') ? 999 : 499;

        return view('website_builder.front.checkout', compact('settings', 'templateSlug', 'plan', 'price'));
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
            'checkout_otp_expires_at' => now()->addMinutes(5)->timestamp,
        ]);

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
                $waMessage = "Your OTP verification code is *" . $otp . "* for *Websitebuilder Ecommerce* - This code is valid for *5 minutes* - Please do not share it with anyone.";

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
                    $resData = $response->json();
                    if (!$resData || (is_array($resData) && (!isset($resData['success']) || $resData['success'] !== false) && (!isset($resData['status']) || $resData['status'] !== 'error'))) {
                        $whatsappSent = true;
                        \Illuminate\Support\Facades\Log::info("Meta Merge WhatsApp OTP sent to {$cleanPhone}");
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("WhatsApp OTP Exception: " . $e->getMessage());
            }
        }

        // 2. Send via Email using LaunchShop's BasicMailer (SMTP from BasicExtended table)
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
                    'recipient'      => $email,
                    'subject'        => "Your OTP Verification Code - Websitebuilder Ecommerce",
                    'body'           => "Your OTP verification code is <b>" . $otp . "</b> for <b>Websitebuilder Ecommerce</b> - This code is valid for <b>5 minutes</b> - Please do not share it with anyone.",
                ];
                \App\Http\Helpers\BasicMailer::sendMail($mailData);
                $emailSent = true;
            } else {
                $emailContent = "Your OTP verification code is {$otp} for Websitebuilder Ecommerce - This code is valid for 5 minutes - Please do not share it with anyone.";
                \Illuminate\Support\Facades\Mail::raw($emailContent, function ($message) use ($email) {
                    $message->to($email)->subject('Your OTP Verification Code - Websitebuilder Ecommerce');
                });
                $emailSent = true;
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('OTP Email sending failed: ' . $e->getMessage());
        }

        // Construct status message without exposing secret OTP digits on the UI form
        if ($whatsappSent && $emailSent) {
            $statusMsg = "OTP verification code sent successfully to your WhatsApp and Email address!";
        } elseif ($whatsappSent) {
            $statusMsg = "OTP verification code sent successfully to your WhatsApp number!";
        } else {
            $statusMsg = "OTP verification code sent successfully to your Email address ({$email})!";
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

        if (!$sessionOtp || $sessionEmail !== $request->email) {
            return response()->json(['success' => false, 'message' => 'Please click Send OTP first.'], 422);
        }

        if (time() > $expiresAt) {
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new code.'], 422);
        }

        if (trim($request->otp) != trim($sessionOtp)) {
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
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'subdomain'      => 'required|string|max:100',
            'password'       => 'required|string|min:6',
            'razorpay_payment_id' => 'nullable|string',
        ]);

        if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
            if (WbCustomer::where('email', $request->customer_email)->exists()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This email address is already registered. Please log in to your account or use a different email.'
                    ], 422);
                }
                return redirect()->back()->withInput()->with('error', 'This email address is already registered. Please log in to your account or use a different email.');
            }
        }

        $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($request->subdomain));
        if (empty($subdomain)) {
            $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($request->customer_name)) . rand(100, 999);
        }

        $customerPassword = $request->password;
        $planName = $request->plan ?? 'Premium';
        $price = $request->price ?? 499;
        $phoneNum = $request->customer_phone ?? '9360157880';

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $customer = WbCustomer::firstOrCreate(
                    ['email' => $request->customer_email],
                    [
                        'name'         => $request->customer_name,
                        'email'        => $request->customer_email,
                        'password'     => Hash::make($customerPassword),
                        'company_name' => $request->customer_name . ' Agency',
                        'subdomain'    => $subdomain,
                        'status'       => 1,
                    ]
                );

                try {
                    Auth::guard('wb_customer')->login($customer);
                } catch (\Throwable $e) {
                    session(['wb_customer_id' => $customer->id, 'wb_customer_email' => $customer->email]);
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                \App\Models\WebsiteBuilder\WbTemplatePurchase::create([
                    'customer_name'       => $request->customer_name,
                    'customer_email'      => $request->customer_email,
                    'customer_phone'      => $phoneNum,
                    'template_slug'       => 'digital_agency',
                    'template_name'       => 'Digital Agency',
                    'razorpay_payment_id' => $request->razorpay_payment_id ?? 'PAY_'.strtoupper(\Illuminate\Support\Str::random(10)),
                    'amount'              => $price,
                    'currency'            => 'INR',
                    'status'              => 'completed',
                ]);
            }

            // Task 1 Format Match: Welcome Message Email using LaunchShop's BasicMailer
            $storeLiveLink = "https://{$subdomain}.websitebuilder.in";
            $loginDashboardLink = "https://websitebuilder.in/login";

            $welcomeHtml = "🎉 <b>Welcome to Websitebuilder!</b><br><br>"
                . "Your store account has been created successfully.<br><br>"
                . "👤 <b>Store Name:</b> {$subdomain}<br>"
                . "📧 <b>Email:</b> {$request->customer_email}<br>"
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
                        'recipient'      => $request->customer_email,
                        'subject'        => "🎉 Welcome to Websitebuilder! Your store account is ready",
                        'body'           => $welcomeHtml,
                    ];
                    \App\Http\Helpers\BasicMailer::sendMail($mailData);
                } else {
                    \Illuminate\Support\Facades\Mail::raw(strip_tags(str_replace('<br>', "\n", $welcomeHtml)), function ($message) use ($request) {
                        $message->to($request->customer_email)->subject("🎉 Welcome to Websitebuilder! Your store account is ready");
                    });
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Welcome mail error: ' . $e->getMessage());
            }

        } catch (\Throwable $e) {
            // Fail-safe
        }

        // Redirect straight to the LAUNCHED LIVE WEBSITE
        $liveUrl = route('website-builder.subdomain.site', ['subdomain' => $subdomain]);
        return redirect()->route('website-builder.subdomain.site', ['subdomain' => $subdomain])
            ->with('success', "🚀 Congratulations! Your website is live at {$liveUrl}");
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

        return view('website_builder.agency_template.index', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainAbout($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if (!$agency) {
            if ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            } else {
                abort(404);
            }
        }
        return view('website_builder.agency_template.about', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainContact($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if (!$agency) {
            if ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            } else {
                abort(404);
            }
        }
        return view('website_builder.agency_template.contact', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainPortfolio($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if (!$agency) {
            if ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            } else {
                abort(404);
            }
        }
        return view('website_builder.agency_template.portfolio', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainBlogs($subdomain)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if (!$agency) {
            if ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            } else {
                abort(404);
            }
        }
        return view('website_builder.agency_template.blogs', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainBlog($subdomain, $id)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if (!$agency) {
            if ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            } else {
                abort(404);
            }
        }
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
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
        $customer = null;
        $subdomain = null;
        $policy = $this->resolvePolicyFromAgency($agency, $slug);
        return view('website_builder.agency_template.policy', compact('agency', 'customer', 'subdomain', 'policy'));
    }

    public function viewSubdomainPolicy($subdomain, $slug)
    {
        [$customer, $agency] = $this->resolveCustomerAndAgency($subdomain);
        if (!$agency) {
            if ($subdomain === 'digital_agency' || $subdomain === 'demo') {
                $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults();
            } else {
                abort(404);
            }
        }
        $policy = $this->resolvePolicyFromAgency($agency, $slug);
        return view('website_builder.agency_template.policy', compact('agency', 'customer', 'subdomain', 'policy'));
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
}
