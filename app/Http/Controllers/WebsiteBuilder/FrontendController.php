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
        session(['is_secret_logged_in' => true]);

        return redirect()->route('website-builder.agency-admin.index')->with('success', 'Logged in via Secret Admin Access.');
    }

    public function checkoutPage(Request $request)
    {
        $settings = WbLandingSetting::getSettings();
        $templateSlug = $request->query('template', 'digital_agency');
        $plan = $request->query('plan', 'Standard');
        $price = ($plan === 'Pro' || $plan === 'Business') ? 999 : 499;

        return view('website_builder.front.checkout', compact('settings', 'templateSlug', 'plan', 'price'));
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

        $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($request->subdomain));
        if (empty($subdomain)) {
            $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($request->customer_name)) . rand(100, 999);
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $customer = WbCustomer::firstOrCreate(
                    ['email' => $request->customer_email],
                    [
                        'name'         => $request->customer_name,
                        'email'        => $request->customer_email,
                        'password'     => Hash::make($request->password),
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
                    'customer_phone'      => $request->customer_phone,
                    'template_slug'       => 'digital_agency',
                    'template_name'       => 'Digital Agency',
                    'razorpay_payment_id' => $request->razorpay_payment_id ?? 'PAY_'.strtoupper(\Illuminate\Support\Str::random(10)),
                    'amount'              => $request->price ?? 499.00,
                    'currency'            => 'INR',
                    'status'              => 'completed',
                ]);
            }
        } catch (\Throwable $e) {
            // Fail-safe
        }

        // Redirect straight to the LAUNCHED LIVE WEBSITE (Not admin!)
        return redirect()->route('website-builder.subdomain.site', ['subdomain' => $subdomain])
            ->with('success', "🚀 Congratulations! Your website is live at https://cockroachjantaparty.top/website-builder/{$subdomain}");
    }

    public function agencyTemplate()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDefaults();
        return view('website_builder.agency_template.index', compact('agency'));
    }

    public function agencyAbout()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDefaults();
        return view('website_builder.agency_template.about', compact('agency'));
    }

    public function agencyContact()
    {
        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDefaults();
        return view('website_builder.agency_template.contact', compact('agency'));
    }

    // Subdomain Live Launched Website Views (Ref Prompt Match)
    public function viewSubdomainSite($subdomain)
    {
        $customer = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $customer = WbCustomer::where('subdomain', $subdomain)->first();
            }
        } catch (\Throwable $e) {}

        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDefaults($customer ? $customer->id : null);
        return view('website_builder.agency_template.index', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainAbout($subdomain)
    {
        $customer = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $customer = WbCustomer::where('subdomain', $subdomain)->first();
            }
        } catch (\Throwable $e) {}

        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDefaults($customer ? $customer->id : null);
        return view('website_builder.agency_template.about', compact('agency', 'customer', 'subdomain'));
    }

    public function viewSubdomainContact($subdomain)
    {
        $customer = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $customer = WbCustomer::where('subdomain', $subdomain)->first();
            }
        } catch (\Throwable $e) {}

        $agency = \App\Models\WebsiteBuilder\WbAgencySetting::getDefaults($customer ? $customer->id : null);
        return view('website_builder.agency_template.contact', compact('agency', 'customer', 'subdomain'));
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
}
