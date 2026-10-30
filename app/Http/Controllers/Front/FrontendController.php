<?php

namespace App\Http\Controllers\Front;

// require_once __DIR__ . '/../../../../vendor/Transliterator/Transliterator.php';

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserFront\HomeController as UserFrontHomeController;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\MegaMailer;
use App\Models\AdditionalSection;
use App\Models\Admin\ImageText;
use App\Models\BasicExtended as BE;
use App\Models\BasicExtended;
use App\Models\BasicSetting as BS;
use App\Models\Bcategory;
use App\Models\Blog;
use App\Models\CounterInformation;
use App\Models\CounterSection;
use App\Models\Admin\UserCategory;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Language;
use App\Models\Merchant;
use App\Models\OfflineGateway;
use App\Models\Package;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PaymentGateway;
use App\Models\Process;
use App\Models\Seo;
use App\Models\Subscriber;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\User\SEO as UserSeo;
use App\Models\User\UserHeading;
use App\Models\User\UserPage;
use App\Models\User\UserPageContent;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Purifier;
use Validator;
use PDF;

class FrontendController extends Controller
{
    public function __construct()
    {
        $bs = BS::first();
        $be = BE::first();

        Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
        Config::set('captcha.secret', $bs->google_recaptcha_secret_key);
        Config::set('mail.host', $be->smtp_host);
        Config::set('mail.port', $be->smtp_port);
        Config::set('mail.username', $be->smtp_username);
        Config::set('mail.password', $be->smtp_password);
        Config::set('mail.encryption', $be->encryption);
    }

    public function index()
    {
        $requestHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $cleanHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder|whatsappbulk|whatsapp)\./i', '', $requestHost);
        $isProductSubdomain = str_starts_with($requestHost, 'launchshop.') 
            || str_starts_with($requestHost, 'checkout.')
            || str_starts_with($requestHost, 'websitebuilder.') 
            || str_starts_with($requestHost, 'website-builder.') 
            || str_starts_with($requestHost, 'whatsappbulk.') 
            || str_starts_with($requestHost, 'whatsapp.');

        $agency = getAgencyFromHost();
        if ($agency && !$isProductSubdomain) {
            return view('front.agency_landing', compact('agency'));
        }

        $requestHost = strtolower(str_replace('www.', '', request()->getHost()));
        $reservedSubdomains = ['launchshop', 'checkout', 'www', 'app', 'admin', 'websitebuilder', 'website-builder', 'localhost'];
        $envHost = strtolower((string) env('WEBSITE_HOST', ''));
        $appHost = strtolower((string) parse_url(env('APP_URL', ''), PHP_URL_HOST));
        $cleanHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder)\./i', '', $requestHost);

        $tenantBaseHosts = array_values(array_unique(array_filter([
            $envHost,
            $appHost,
            $cleanHost,
        ])));

        foreach ($tenantBaseHosts as $baseHost) {
            if (!empty($baseHost)
                && $requestHost !== $baseHost
                && str_ends_with($requestHost, '.' . $baseHost)
            ) {
                $subdomain = explode('.', $requestHost)[0] ?? null;
                if (!empty($subdomain) && !in_array(strtolower($subdomain), $reservedSubdomains)) {
                    return app(UserFrontHomeController::class)->userDetailView($subdomain);
                }
            }
        }

        if (request()->query('clear_cache') == '1') {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            return "All caches cleared successfully!";
        }

        if (session()->has('lang')) {
            try {
                $currentLang = Language::where('code', session()->get('lang'))->first();
            } catch (\Throwable $e) { $currentLang = null; }
        } else {
            try {
                $currentLang = Language::where('is_default', 1)->first();
            } catch (\Throwable $e) { $currentLang = null; }
        }
        $lang_id = $currentLang->id ?? 1;

        try {
            $data['processes'] = \Illuminate\Support\Facades\Schema::hasTable('processes') ? Process::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get() : collect([]);
        } catch (\Throwable $e) {
            $data['processes'] = collect([]);
        }

        try {
            $data['features'] = \Illuminate\Support\Facades\Schema::hasTable('features') ? Feature::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get() : collect([]);
        } catch (\Throwable $e) {
            $data['features'] = collect([]);
        }
        try {
            $data['featured_users'] = User::where([
                ['featured', 1],
                ['status', 1]
            ]);
            if (\Illuminate\Support\Facades\Schema::hasTable('memberships')) {
                $data['featured_users'] = $data['featured_users']->whereHas('memberships', function ($q) {
                    $q->where('status', '=', 1)
                        ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                        ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
                });
            }
            $data['featured_users'] = $data['featured_users']->orderBy('feature_time', 'DESC')->get();
        } catch (\Throwable $e) {
            $data['featured_users'] = collect([]);
        }

        try {
            $data['templates'] = User::where([
                ['preview_template', 1],
                ['status', 1],
                ['online_status', 1],
                ['featured', 1]
            ]);
            if (\Illuminate\Support\Facades\Schema::hasTable('memberships')) {
                $data['templates'] = $data['templates']->whereHas('memberships', function ($q) {
                    $q->where('status', '=', 1)
                        ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                        ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
                });
            }
            $data['templates'] = $data['templates']->orderBy('template_serial_number', 'ASC')->get();
        } catch (\Throwable $e) {
            $data['templates'] = collect([]);
        }

        // Debug logging to help diagnose missing templates on the templates page
        try {
            $usernames = $data['templates']->pluck('username')->toArray();
            Log::info('Templates page loaded. Count: ' . count($data['templates']) . '. Usernames: ' . implode(',', $usernames));
        } catch (\Exception $e) {
            Log::error('Error logging templates info: ' . $e->getMessage());
        }


        try {
            $data['testimonials'] = \Illuminate\Support\Facades\Schema::hasTable('testimonials') ? Testimonial::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get() : collect([]);
        } catch (\Throwable $e) {
            $data['testimonials'] = collect([]);
        }
        try {
            $data['blogs'] = \Illuminate\Support\Facades\Schema::hasTable('blogs') ? Blog::where('language_id', $lang_id)->orderBy('id', 'DESC')->take(5)->get() : collect([]);
        } catch (\Throwable $e) {
            $data['blogs'] = collect([]);
        }
        try {
            $data['partners'] = \Illuminate\Support\Facades\Schema::hasTable('partners') ? Partner::orderBy('serial_number', 'ASC')->get() : collect([]);
        } catch (\Throwable $e) {
            $data['partners'] = collect([]);
        }

        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $lang_id)->first() : null;

        $terms = [];
        if (Package::query()->where('status', '1')->where('featured', '1')->where('term', 'monthly')->count() > 0) {
            $terms[] = 'Monthly';
        }
        if (Package::query()->where('status', '1')->where('featured', '1')->where('term', 'yearly')->count() > 0) {
            $terms[] = 'Yearly';
        }
        if (Package::query()->where('status', '1')->where('featured', '1')->where('term', 'lifetime')->count() > 0) {
            $terms[] = 'Lifetime';
        }
        $data['terms'] = $terms;

        $be = BasicExtended::select('package_features')->firstOrFail();
        $allPfeatures = $be->package_features ? $be->package_features : "[]";
        $data['allPfeatures'] = json_decode($allPfeatures, true);

        $sections = [
            'hero_section',
            'partner_section',
            'work_process_section',
            'template_section',
            'features_section',
            'pricing_section',
            'featured_shop_section',
            'testimonial_section',
            'blog_section'
        ];
        $pageType = 'home';
        foreach ($sections as $section) {
            $data["after_" . str_replace('_section', '', $section)] = AdditionalSection::where('possition', $section)
                ->where('page_type', $pageType)
                ->orderBy('serial_number', 'asc')
                ->get();
        }
        ImageText::ensureColumnsExist();
        $data['homeSec'] = ImageText::where('language_id', $lang_id)->first();
        $data['lang_id'] = $lang_id;

        try {
            $data['processes'] = \Illuminate\Support\Facades\Schema::hasTable('processes') ? Process::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get() : collect([]);
        } catch (\Throwable $e) {
            $data['processes'] = collect([]);
        }

        try {
            $data['features'] = \Illuminate\Support\Facades\Schema::hasTable('features') ? Feature::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get() : collect([]);
        } catch (\Throwable $e) {
            $data['features'] = collect([]);
        }

        try {
            $data['counters'] = \Illuminate\Support\Facades\Schema::hasTable('counter_informations') ? CounterInformation::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get() : collect([]);
        } catch (\Throwable $e) {
            $data['counters'] = collect([]);
        }

        try {
            $data['counterSection'] = \Illuminate\Support\Facades\Schema::hasTable('counter_sections') ? CounterSection::where('language_id', $lang_id)->first() : null;
        } catch (\Throwable $e) {
            $data['counterSection'] = null;
        }

        return view('front.index', $data);
    }

    public function about()
    {
        $requestHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $isProductSubdomain = str_starts_with($requestHost, 'launchshop.') || str_starts_with($requestHost, 'websitebuilder.') || str_starts_with($requestHost, 'website-builder.');
        $agency = getAgencyFromHost();
        if ($agency && !$isProductSubdomain) {
            return view('front.agency_legal', ['type' => 'about', 'agency' => $agency]);
        }

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $lang_id = $currentLang->id;

        $data['homeSec'] = ImageText::where('language_id', $lang_id)->first();

        $data['processes'] = Process::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['features'] = Feature::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();

        $data['testimonials'] = Testimonial::where('language_id', $lang_id)
            ->orderBy('serial_number', 'ASC')
            ->get();
        $data['blogs'] = Blog::where('language_id', $lang_id)->orderBy('id', 'DESC')->take(5)->get();

        $data['partners'] = Partner::orderBy('serial_number', 'ASC')
            ->get();

        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $lang_id)->first() : null;

        $data['counters'] = CounterInformation::where('language_id', $lang_id)->get();

        $data['counterSection'] = CounterSection::where('language_id', $lang_id)->first();

        $sections = [
            'features_section',
            'work_process_section',
            'counter_section',
            'testimonial_section',
            'blog_section'
        ];
        $pageType = 'about';
        foreach ($sections as $section) {
            $data["after_" . str_replace('_section', '', $section)] = AdditionalSection::where('possition', $section)
                ->where('page_type', $pageType)
                ->orderBy('serial_number', 'asc')
                ->get();
        }

        $data['lang_id'] = $lang_id;

        return view('front.about', $data);
    }

    public function subscribe(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:subscribers'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->getMessageBag()
            ], 400);
        }

        $subsc = new Subscriber;
        $subsc->email = $request->email;
        $subsc->save();

        return response()->json([
            'success' => __('You have successfully subscribed to our newsletter')
        ], 200);
    }

    public function loginView()
    {
        return view('front.login');
    }

    public function checkUsername($username)
    {
        $count = User::where('username', $username)->count();
        $status = $count > 0 ? true : false;
        return response()->json($status);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'country_code' => 'required',
            'email' => 'required|email'
        ]);

        $phone = ltrim($request->phone_number, '0');
        $countryCode = $request->country_code;
        $name = $request->input('name', '');
        $email = $request->email;

        if (User::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => __('This email address is already registered. Please login or use a different email.')
            ]);
        }

        // Persist typed data in session immediately
        Session::put('otp_phone', $phone);
        Session::put('otp_country_code', $countryCode);
        Session::put('otp_name', $name);
        Session::put('otp_email', $email);

        // Reset verified status since they are initiating a new verification
        Session::forget(['phone_verified', 'verified_phone', 'verified_email']);

        // Clean and strip leading zeroes and formatting spaces
        $rawDigits = preg_replace('/[^0-9]/', '', (string) $request->phone_number);
        $cleanPhone = ltrim($rawDigits, '0');
        $cleanCountryCode = preg_replace('/[^0-9]/', '', (string) $countryCode) ?: '91';

        // Construct the full international number for WhatsApp API
        if (strpos($cleanPhone, $cleanCountryCode) === 0 && strlen($cleanPhone) > 10) {
            $mobileNo = $cleanPhone;
        } else {
            $mobileNo = $cleanCountryCode . $cleanPhone;
        }

        $otp = rand(100000, 999999);
        $whatsappSent = false;

        // WhatsApp Gateway OTP integration
        try {
            $agency = function_exists('getAgencyFromHost') ? getAgencyFromHost() : null;
            $waResult = \App\Services\WhatsAppNotificationService::sendOtp($mobileNo, $otp, 'Ecom Builder', $agency);
            if ($waResult['success'] ?? false) {
                $whatsappSent = true;
            }
        } catch (\Throwable $waEx) {
            Log::error('WhatsApp OTP Gateway exception: ' . $waEx->getMessage());
        }

        // Always store OTP session so user can verify without restriction
        Session::put('otp_code', $otp);
        Session::put('otp_phone', $phone);
        Session::put('otp_expires_at', time() + 300);
        Session::put('otp_email', $email);

        // Only fallback to email if WhatsApp delivery fails or is unavailable
        $emailSent = false;
        if (!$whatsappSent) {
            try {
                $be = BE::first();
                $smtpHost = env('MAIL_HOST', 'mail.saasreselling.com');
                $smtpUser = env('MAIL_USERNAME', 'admin@saasreselling.com');
                $smtpPass = env('MAIL_PASSWORD', 'Admin@nooryak');
                $smtpPort = (int) env('MAIL_PORT', 465);
                $smtpEnc  = env('MAIL_ENCRYPTION', 'ssl');
                $fromMail = env('MAIL_FROM_ADDRESS', 'admin@saasreselling.com');
                $fromName = env('MAIL_FROM_NAME', 'Ecom Builder');

                if ($be) {
                    if (!empty($be->smtp_host) && !str_contains($be->smtp_host, 'metroshop.in') && !str_contains($be->smtp_host, 'nooryak.in')) {
                        $smtpHost = $be->smtp_host;
                    }
                    if (!empty($be->smtp_username) && !str_contains($be->smtp_username, 'metroshop.in') && !str_contains($be->smtp_username, 'nooryak.in')) {
                        $smtpUser = $be->smtp_username;
                    }
                    if (!empty($be->smtp_password)) {
                        $smtpPass = $be->smtp_password;
                    }
                    if (!empty($be->smtp_port)) {
                        $smtpPort = (int) $be->smtp_port;
                    }
                    if (!empty($be->encryption)) {
                        $smtpEnc = $be->encryption;
                    }
                    if (!empty($be->from_mail) && !str_contains($be->from_mail, 'metroshop.in') && !str_contains($be->from_mail, 'nooryak.in')) {
                        $fromMail = $be->from_mail;
                    }
                    if (!empty($be->from_name)) {
                        $fromName = $be->from_name;
                    }
                }

                $rawLogoUrl = asset('assets/landing_page/ecombuilderemail.png');
                $ecomLogoUrl = str_starts_with($rawLogoUrl, 'http://') ? str_replace('http://', 'https://', $rawLogoUrl) : $rawLogoUrl;
                if (str_contains($ecomLogoUrl, 'localhost') || str_contains($ecomLogoUrl, '127.0.0.1')) {
                    $ecomLogoUrl = 'https://launchshop.saasreselling.com/assets/landing_page/ecombuilderemail.png';
                }
                $otpBody = "<p style=\"font-size:15px;color:#334155;margin:0 0 10px 0;\">Your OTP verification code is <b style=\"font-size:22px;color:#ff6f61;letter-spacing:1px;\">" . $otp . "</b> for <b>Ecom Builder</b>.</p>"
                    . "<p style=\"font-size:13px;color:#64748b;margin:0;\">Valid for <b>5 minutes</b>. Please do not share it with anyone.</p>";

                $wrappedBody = class_exists('\App\Http\Helpers\Common') 
                    ? \App\HttpHelpers\Common::wrapEmailBody($otpBody, "OTP Verification Code - Ecom Builder", null, $ecomLogoUrl, 'Ecom Builder')
                    : $otpBody;

                $mailData = [
                    'smtp_status'   => 1,
                    'smtp_host'     => $smtpHost,
                    'smtp_username' => $smtpUser,
                    'smtp_password' => $smtpPass,
                    'encryption'    => $smtpEnc,
                    'smtp_port'     => $smtpPort,
                    'from_mail'     => $fromMail,
                    'from_name'     => $fromName,
                    'recipient'     => $email,
                    'subject'       => "OTP Verification Code - Ecom Builder",
                    'body'          => $wrappedBody,
                ];
                Config::set('mail.default', 'smtp');
                $emailSent = (bool) BasicMailer::sendMail($mailData);
            } catch (\Exception $mailEx) {
                Log::error('OTP Email send failed: ' . $mailEx->getMessage());
            }
        }

        // Save / update phone lead in DB for admin visibility
        try {
            $name = $request->input('name', '');
            \App\Models\VerifiedPhoneLead::updateOrCreate(
                ['phone' => $mobileNo],
                [
                    'name'         => $name,
                    'country_code' => $countryCode,
                    'email'        => $email,
                    'is_verified'  => false,
                    'otp_sent_at'  => now(),
                ]
            );
        } catch (\Exception $leadEx) {
            Log::warning('VerifiedPhoneLead save failed: ' . $leadEx->getMessage());
        }

        if ($whatsappSent) {
            $msg = __('OTP sent successfully to your WhatsApp (+:number)!', ['number' => $mobileNo]);
            $channel = 'whatsapp';
        } elseif ($emailSent) {
            $msg = __('WhatsApp delivery unavailable. OTP sent to your Email address (:email)!', ['email' => $email]);
            $channel = 'email';
        } else {
            $msg = __('OTP code generated! Check your email: :email', ['email' => $email]);
            $channel = 'email';
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'channel' => $channel,
            'email_sent' => $emailSent
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            'phone_number' => 'required'
        ]);

        $otp = $request->otp;
        $phone = ltrim($request->phone_number, '0');

        $sessionOtp = Session::get('otp_code');
        $sessionPhone = Session::get('otp_phone');
        $sessionExpiresAt = Session::get('otp_expires_at');

        if (!$sessionOtp || !$sessionPhone || !$sessionExpiresAt) {
            return response()->json([
                'success' => false,
                'message' => __('No OTP request found. Please request a new OTP.')
            ], 400);
        }

        if (time() > $sessionExpiresAt) {
            return response()->json([
                'success' => false,
                'message' => __('OTP has expired. Please request a new OTP.')
            ], 400);
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $cleanSessionPhone = preg_replace('/[^0-9]/', '', $sessionPhone);

        if ($otp == $sessionOtp && substr($cleanPhone, -10) === substr($cleanSessionPhone, -10)) {
            Session::put('phone_verified', true);
            Session::put('verified_phone', $phone);
            Session::put('verified_email', Session::get('otp_email'));
            Session::put('phone_verified_at', time());

            // Mark lead as verified in DB
            try {
                $cleanPhoneDigits = preg_replace('/[^0-9]/', '', $phone);
                \App\Models\VerifiedPhoneLead::where('phone', 'like', '%' . $cleanPhoneDigits)->update([
                    'is_verified' => true
                ]);
            } catch (\Exception $leadEx) {
                Log::warning('VerifiedPhoneLead verify mark failed: ' . $leadEx->getMessage());
            }

            Session::forget(['otp_code', 'otp_phone', 'otp_expires_at']);

            return response()->json([
                'success' => true,
                'message' => __('Phone number and email verified successfully!')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('Invalid OTP. Please try again.')
        ], 400);
    }

    public function selectTemplate($status, $id)
    {
        // If the session is a secret/demo login (template admin preview), auto-logout so the
        // visitor can proceed through the purchase registration flow instead of being
        // redirected to the (demo) user dashboard.
        if (Auth::check()) {
            if (Session::get('secrect_login')) {
                Auth::logout();
                Session::forget('secrect_login');
                // Continue with the normal flow below instead of redirecting
            } else {
                return redirect()->route('user.plan.extend.index');
            }
        }

        $package = Package::findOrFail($id);

        $templates = User::where([
            ['preview_template', 1],
            ['status', 1],
            ['online_status', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->orderBy('template_serial_number', 'ASC')->get();

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $faqs = Faq::where('language_id', $currentLang->id)
            ->orderBy('serial_number', 'asc')
            ->get();

        return view('front.select-template', [
            'status'    => $status,
            'id'        => $id,
            'package'   => $package,
            'templates' => $templates,
            'faqs'      => $faqs,
        ]);
    }

    public function step1($status, $id)
    {
        // Clear phone verification session if it has expired (2 hours / 7200 seconds)
        if (Session::has('phone_verified') && (time() - Session::get('phone_verified_at', 0)) > 7200) {
            Session::forget(['phone_verified', 'verified_phone', 'phone_verified_at']);
        }

        // Same secret-login guard as selectTemplate: auto-logout demo admin visitors
        if (Auth::check()) {
            if (Session::get('secrect_login')) {
                Auth::logout();
                Session::forget('secrect_login');
                // Continue with the normal flow below
            } else {
                return redirect()->route('user.plan.extend.index');
            }
        }
        $data['status'] = $status;
        $data['id'] = $id;
        $package = Package::findOrFail($id);
        $data['package'] = $package;

        $hasSubdomain = false;
        $features = [];
        if (!empty($package->features)) {
            $features = json_decode($package->features, true);
        }
        if (is_array($features) && in_array('Subdomain', $features)) {
            $hasSubdomain = true;
        }
        $currentLang = app('currentLang');
        $categories = UserCategory::where('language_id', $currentLang->id)->get();
        // Fallback: if no categories for current language, try the default language
        if ($categories->isEmpty()) {
            $defaultLang = Language::where('is_default', 1)->first();
            if ($defaultLang && $defaultLang->id !== $currentLang->id) {
                $categories = UserCategory::where('language_id', $defaultLang->id)->get();
            }
        }
        // Last resort: load all categories regardless of language
        if ($categories->isEmpty()) {
            $categories = UserCategory::all();
        }
        $data['categories'] = $categories;
        $data['hasSubdomain'] = $hasSubdomain;

        // Fetch all active packages for the dynamic plan switcher
        $packages = Package::where('status', '1')->get();
        $packages->map(function ($pkg) {
            $features = !empty($pkg->features) ? json_decode($pkg->features, true) : [];
            $pkg->has_subdomain = is_array($features) && in_array('Subdomain', $features);
            return $pkg;
        });
        $data['packages'] = $packages;

        // Pass selected template username (from query string, session, or alias fallback)
        $rawTemplate = request()->query('template') 
            ?: (request()->query('selected_template') 
            ?: session()->get('selected_template', ''));

        $themeAliasMap = [
            'multipurpose' => 'manti',
            'manti'        => 'manti',
            'grocery'      => 'ecomgrocery',
            'vegetables'   => 'ecomgrocery',
            'grocery2'     => 'ecomgrocery',
            'ecomgrocery'  => 'ecomgrocery',
            'electronics'  => 'electi',
            'electi'       => 'electi',
            'fashion'      => 'fashclo',
            'fashclo'      => 'fashclo',
            'furniture'    => 'furial',
            'furial'       => 'furial',
            'clothing'     => 'clothing',
            'skinflow'     => 'skinflow',
            'beauty'       => 'skinflow',
            'jewellery'    => 'jewellery',
            'pet'          => 'petrashop',
            'petrashop'    => 'petrashop',
            'kids'         => 'kidsfa',
            'kidsfa'       => 'kidsfa',
        ];

        $selected_template = '';
        $selectedTemplateName = __('No template selected');
        $selectedTemplateImg = null;

        if (!empty($rawTemplate)) {
            $cleanKey = strtolower(trim($rawTemplate));
            $targetUsername = $themeAliasMap[$cleanKey] ?? $cleanKey;

            $templateUser = User::where('username', $targetUsername)
                ->orWhere('username', $cleanKey)
                ->first();

            if ($templateUser) {
                $selected_template = $templateUser->username;
                session()->put('selected_template', $selected_template);

                $themeName = \App\Models\User\BasicSetting::where('user_id', $templateUser->id)->value('theme');
                $selectedTemplateName = match($themeName) {
                    'vegetables'  => __('Grocery Theme'),
                    'manti'       => __('Multipurpose Theme'),
                    'grocery2'    => __('Ecom Grocery Theme'),
                    'electronics' => __('Electronics Theme'),
                    'fashion'     => __('Fashion Theme'),
                    'clothing'    => __('Clothing Theme'),
                    'furniture'   => __('Furniture Theme'),
                    'skinflow'    => __('Skinflow Theme'),
                    'jewellery'   => __('Jewellery Theme'),
                    'pet'         => __('Pet Theme'),
                    'kids'        => __('Kids Theme'),
                    default       => __(ucfirst($themeName ?? 'Default') . ' Theme'),
                };
                $selectedTemplateImg = $templateUser->template_img;
            } else {
                $selected_template = $rawTemplate;
                session()->put('selected_template', $selected_template);
            }
        }

        $data['selected_template'] = $selected_template;
        $data['selectedTemplateName'] = $selectedTemplateName;
        $data['selectedTemplateImg'] = $selectedTemplateImg;

        // Fetch all active templates for the dynamic template switcher
        $templates = User::where([
            ['preview_template', 1],
            ['status', 1],
            ['online_status', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->orderBy('template_serial_number', 'ASC')->get();

        $templates->map(function ($template) {
            $themeName = \App\Models\User\BasicSetting::where('user_id', $template->id)->value('theme');
            $template->display_name = match($themeName) {
                'vegetables'  => __('Grocery Theme'),
                'manti'       => __('Multipurpose Theme'),
                'grocery2'    => __('Ecom Grocery Theme'),
                'electronics' => __('Electronics Theme'),
                'fashion'     => __('Fashion Theme'),
                'clothing'    => __('Clothing Theme'),
                'furniture'   => __('Furniture Theme'),
                'skinflow'    => __('Skinflow Theme'),
                'jewellery'   => __('Jewellery Theme'),
                'pet'         => __('Pet Theme'),
                'kids'        => __('Kids Theme'),
                default       => __(ucfirst($themeName ?? 'Default') . ' Theme'),
            };
            return $template;
        });
        $data['templates'] = $templates;

        return view('front.step', $data);
    }

    public function step2(Request $request)
    {
        $sessionData = $request->session()->get('data');
        if (empty($sessionData) || !isset($sessionData['package'])) {
            $pkgId = old('package_id', $request->query('id', $request->query('package_id')));
            $package = null;
            if (!empty($pkgId)) {
                $package = Package::find($pkgId);
            }
            if (!$package) {
                $package = Package::where('status', 1)->first();
            }

            if ($package) {
                if (empty($sessionData) || !is_array($sessionData)) {
                    $sessionData = [];
                }
                $sessionData['package'] = $package;
                $sessionData['id'] = $package->id;
                $sessionData['status'] = old('package_type', 'regular');
                $sessionData['first_name'] = old('first_name', '');
                $sessionData['username'] = old('username', '');
                $sessionData['email'] = old('email', '');
                $sessionData['phone'] = old('phone', '');
                $sessionData['country_code'] = old('country_code', '+91');
                $sessionData['shop_name'] = old('shop_name', '');
                $sessionData['selected_template'] = old('selected_template', session('selected_template', ''));
                
                $online = PaymentGateway::query()->where('status', 1)->get()->filter(function($gateway) {
                    return strtolower($gateway->name) !== 'upi' && strtolower($gateway->keyword ?? '') !== 'upi';
                })->unique(function ($item) {
                    return strtolower($item->keyword ?? $item->name);
                });
                $offline = OfflineGateway::where('status', 1)->get();
                $sessionData['offline'] = $offline;
                $sessionData['payment_methods'] = $online->toBase()->merge($offline->toBase())->unique(function ($item) {
                    return strtolower($item->keyword ?? $item->name);
                });
                $request->session()->put('data', $sessionData);
            } else {
                return redirect()->route('front.pricing')->with('warning', __('Please select a package to proceed with registration.'));
            }
        }

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['data'] = $sessionData;

        return view('front.checkout', $data);
    }

    public function checkout(Request $request)
    {
        // Clear phone verification session if it has expired (2 hours / 7200 seconds)
        if (Session::has('phone_verified') && (time() - Session::get('phone_verified_at', 0)) > 7200) {
            Session::forget(['phone_verified', 'verified_phone', 'verified_email', 'phone_verified_at']);
        }

        $sessionPhone = Session::get('verified_phone');
        $sessionEmail = Session::get('verified_email');
        $cleanReqPhone = preg_replace('/[^0-9]/', '', (string)$request->phone);
        $cleanSesPhone = preg_replace('/[^0-9]/', '', (string)$sessionPhone);

        $isPhoneMatch = Session::get('phone_verified') && !empty($cleanSesPhone) && (
            $cleanReqPhone === $cleanSesPhone ||
            (strlen($cleanReqPhone) >= 10 && strlen($cleanSesPhone) >= 10 && substr($cleanReqPhone, -10) === substr($cleanSesPhone, -10))
        );
        $isEmailMatch = !empty($sessionEmail) && strtolower(trim($sessionEmail)) === strtolower(trim($request->email));

        // Database fallback check: if verified via OTP in verified_phone_leads in the last 4 hours
        if ((!$isPhoneMatch || !$isEmailMatch) && strlen($cleanReqPhone) >= 10) {
            try {
                $lead = \App\Models\VerifiedPhoneLead::where('phone', 'like', '%' . substr($cleanReqPhone, -10))
                    ->where('is_verified', true)
                    ->where('updated_at', '>=', now()->subHours(4))
                    ->first();
                if ($lead) {
                    $isPhoneMatch = true;
                    $isEmailMatch = true;
                    Session::put('phone_verified', true);
                    Session::put('verified_phone', $request->phone);
                    Session::put('verified_email', $request->email);
                    Session::put('phone_verified_at', time());
                }
            } catch (\Throwable $leadEx) {}
        }

        // Validate backend phone and email verification status
        if (!$isPhoneMatch || !$isEmailMatch) {
            return redirect()->back()->withErrors(['phone' => __('Please verify your details first.')])->withInput();
        }

        // Keep session verified across any subsequent validation redirects (e.g., password mismatch)
        Session::put('phone_verified', true);
        Session::put('verified_phone', $request->phone);
        Session::put('verified_email', $request->email);
        Session::put('phone_verified_at', time());

        if ($request->has('phone')) {
            $request->merge([
                'phone' => ltrim(preg_replace('/[^0-9]/', '', $request->phone), '0'),
            ]);
        }
       
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'username' => 'required|max:10|alpha_num|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'country_code' => 'required|max:5',
            'phone' => 'required|regex:/^[0-9]+$/|max:16'
        ]);
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $seo = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;
        $be = $currentLang->basic_extended;
        $data['bex'] = $be;
        $data['first_name'] = $request->first_name;
        $data['username'] = $request->username;
        $data['email'] = $request->email;
        $data['password'] = $request->password;
        $data['status'] = $request->status;
        $data['country_code'] = $request->country_code;
        $data['phone'] = $request->phone;

        // Store and resolve selected_template from request or session
        $selectedTemplate = $request->selected_template 
            ?: ($request->template 
            ?: session()->get('selected_template', ''));

        if (!empty($selectedTemplate)) {
            session()->put('selected_template', $selectedTemplate);
        }

        // Map theme/alias names to canonical template usernames
        $themeAliasMap = [
            'multipurpose' => 'manti',
            'manti'        => 'manti',
            'grocery'      => 'ecomgrocery',
            'vegetables'   => 'ecomgrocery',
            'grocery2'     => 'ecomgrocery',
            'ecomgrocery'  => 'ecomgrocery',
            'electronics'  => 'electi',
            'electi'       => 'electi',
            'fashion'      => 'fashclo',
            'fashclo'      => 'fashclo',
            'furniture'    => 'furial',
            'furial'       => 'furial',
            'clothing'     => 'clothing',
            'skinflow'     => 'skinflow',
            'beauty'       => 'skinflow',
            'jewellery'    => 'jewellery',
            'pet'          => 'petrashop',
            'petrashop'    => 'petrashop',
            'kids'         => 'kidsfa',
            'kidsfa'       => 'kidsfa',
        ];

        // Automatically resolve Category ID from template or use a default fallback
        $categoryId = null;
        if (!empty($selectedTemplate)) {
            $cleanKey = strtolower(trim($selectedTemplate));
            $mappedUsername = $themeAliasMap[$cleanKey] ?? $cleanKey;
            $templateUser = User::where('username', $mappedUsername)
                ->orWhere('username', $cleanKey)
                ->first();
            if ($templateUser) {
                $categoryId = $templateUser->category_id;
                $selectedTemplate = $templateUser->username;
                session()->put('selected_template', $selectedTemplate);
            }
        }
        if (empty($categoryId)) {
            $firstCat = UserCategory::where('language_id', $currentLang->id)->first();
            if ($firstCat) {
                $categoryId = $firstCat->unique_id;
            } else {
                $firstCatAny = UserCategory::first();
                if ($firstCatAny) {
                    $categoryId = $firstCatAny->unique_id;
                }
            }
        }
        $data['category'] = $categoryId;
        $data['id'] = $request->id;
        $data['selected_template'] = $selectedTemplate;
        $online = PaymentGateway::query()->where('status', 1)->get()->filter(function($gateway) {
            return strtolower($gateway->name) !== 'upi' && strtolower($gateway->keyword ?? '') !== 'upi';
        })->unique(function ($item) {
            return strtolower($item->keyword ?? $item->name);
        });
        $offline = OfflineGateway::where('status', 1)->get();
        $data['offline'] = $offline;
        $data['payment_methods'] = $online->toBase()->merge($offline->toBase())->unique(function ($item) {
            return strtolower($item->keyword ?? $item->name);
        });
        $data['package'] = Package::query()->findOrFail($request->id);
        $data['seo'] = $seo;
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $request->session()->put('data', $data);
        return redirect()->route('front.registration.step2');
    }

    // packages start
    public function pricing(Request $request)
    {
        if ($request->has('template') || $request->has('selected_template')) {
            $tplParam = $request->query('template') ?: $request->query('selected_template');
            if (!empty($tplParam)) {
                session()->put('selected_template', $tplParam);
            }
        }
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;

        $data['bex'] = BE::first();
        $data['abs'] = BS::first();

        $terms = [];
        if (Package::query()->where('status', '1')->where('term', 'monthly')->count() > 0) {
            $terms[] = 'Monthly';
        }
        if (Package::query()->where('status', '1')->where('term', 'yearly')->count() > 0) {
            $terms[] = 'Yearly';
        }
        if (Package::query()->where('status', '1')->where('term', 'lifetime')->count() > 0) {
            $terms[] = 'Lifetime';
        }
        $data['terms'] = $terms;

        $be = BasicExtended::select('package_features')->firstOrFail();
        $allPfeatures = $be->package_features ? $be->package_features : "[]";
        $data['allPfeatures'] = json_decode($allPfeatures, true);

        $data['faqs'] = Faq::where('language_id', $currentLang->id)
            ->orderBy('serial_number', 'asc')
            ->get();

        $data['homeSec'] = ImageText::where('language_id', $currentLang->id)->first() ?? ImageText::first();

        return view('front.pricing', $data);
    }

    // blog section start
    public function blogs(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;

        $data['currentLang'] = $currentLang;

        $lang_id = $currentLang->id;

        $category = $request->category;
        if (!empty($category)) {
            $data['category'] = Bcategory::findOrFail($category);
        }
        $title = $request->title;

        $data['bcats'] = Bcategory::where('language_id', $lang_id)->where('status', 1)->orderBy('serial_number', 'ASC')->get();

        $data['blogs'] = Blog::when($category, function ($query, $category) {
            return $query->where('bcategory_id', $category);
        })
            ->when($title, function ($query, $title) {
                return $query->where('title', 'like', '%' . $title . '%');
            })
            ->when($currentLang, function ($query, $currentLang) {
                return $query->where('language_id', $currentLang->id);
            })->orderBy('serial_number', 'ASC')->paginate(4);
        return view('front.blogs', $data);
    }

    public function blogdetails($slug, $id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $lang_id = $currentLang->id;
        $data['blog'] = Blog::findOrFail($id);
        $data['bcats'] = Bcategory::where('status', 1)->where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();

        $data['related_blogs'] = Blog::where([
            ['bcategory_id', $data['blog']->bcategory_id],
            ['language_id', $lang_id],
            ['id', '!=', $id],
        ])->limit(5)->get();

        return view('front.blog-details', $data);
    }

    public function contactView()
    {
        $requestHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $isProductSubdomain = str_starts_with($requestHost, 'launchshop.') || str_starts_with($requestHost, 'websitebuilder.') || str_starts_with($requestHost, 'website-builder.');
        $agency = function_exists('getAgencyFromHost') ? getAgencyFromHost() : null;
        if ($agency && !$isProductSubdomain) {
            return view('front.agency_legal', ['type' => 'contact', 'agency' => $agency]);
        }

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;
        $data['recaptchaInfo'] = BS::select('is_recaptcha', 'google_recaptcha_site_key', 'google_recaptcha_secret_key')->first();
        $data['homeSec'] = ImageText::where('language_id', $currentLang->id)->first() ?? ImageText::first();

        return view('front.contact', $data);
    }

    public function faqs()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;

        $lang_id = $currentLang->id;
        $data['faqs'] = Faq::where('language_id', $lang_id)
            ->orderBy('serial_number', 'asc')
            ->get();
        return view('front.faq', $data);
    }

    public function dynamicPage($slug)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $data['page'] = Page::where([['slug', $slug], ['status', 1]])->firstOrFail();
        $pageId = $data['page']->id;

        //custom seo info
        $seoInfo = SEO::select('custome_page_meta_keyword', 'custome_page_meta_description')
            ->where('language_id', $currentLang->id)
            ->first();
        $metaKeyword = isset($seoInfo->custome_page_meta_keyword) ? json_decode($seoInfo->custome_page_meta_keyword, true) : '';
        $metaDescription = isset($seoInfo->custome_page_meta_description) ? json_decode($seoInfo->custome_page_meta_description, true) : '';
        $data['meta_keywords'] = isset($metaKeyword[$pageId]) ? $metaKeyword[$pageId] : '';
        $data['meta_description'] = isset($metaDescription[$pageId]) ? $metaDescription[$pageId] : '';

        //custom page page heading
        $pageHeading = UserHeading::select('custom_page_heading')
            ->where('language_id', $currentLang->id)
            ->select('custom_page_heading')
            ->first();
        $pageHeading = isset($pageHeading->custom_page_heading) ? json_decode($pageHeading->custom_page_heading, true) : [];
        $data['title'] = (is_array($pageHeading) && isset($pageHeading[$pageId])) ? $pageHeading[$pageId] : '';

        return view('front.dynamic', $data);
    }

    public function shops(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Shops Request Params: ' . json_encode($request->all()));
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;
        $data['categories'] = UserCategory::query()->where('language_id', $currentLang->id)->get();
        $data['homeSec'] = ImageText::where('language_id', $currentLang->id)->first() ?? ImageText::first();

        $selectedCategoryUniqueId = null;
        if (!empty($request->category)) {
            $selectedCategoryUniqueId = UserCategory::where([
                ['language_id', $currentLang->id],
                ['slug', $request->category]
            ])->value('unique_id');
        }


        $users = User::with([
            'category' => function ($query) use ($currentLang) {
                $query->where('language_id', $currentLang->id);
            },
            'basic_setting'
        ])
            ->where(function ($query) {
                $query->where('preview_template', 1)
                      ->orWhere(function ($q) {
                          $q->where('preview_template', 0)
                            ->where('online_status', 1)
                            ->where('landing_status', 1)
                            ->whereHas('memberships', function ($sub) {
                                $sub->where('status', '=', 1)
                                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
                            })
                            ->whereHas('permissions', function ($sub) {
                                $sub->where('permissions', 'LIKE', '%"Profile Listing"%');
                            });
                      });
            })
            ->when($request->shop_name, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('username', 'like', '%' . $request->shop_name . '%')
                        ->orWhere('shop_name', 'like', '%' . $request->shop_name . '%');
                });
            })
            ->when($request->location, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('city', 'like', '%' . $request->location . '%')
                        ->orWhere('country', 'like', '%' . $request->location . '%');
                });
            })
            ->when($selectedCategoryUniqueId, function ($q) use ($selectedCategoryUniqueId) {
                return $q->where('category_id', $selectedCategoryUniqueId);
            })
            ->when($request->sort_by, function ($q) use ($request) {
                if ($request->sort_by == 'newest') {
                    return $q->orderBy('preview_template', 'ASC')->orderBy('id', 'DESC');
                } elseif ($request->sort_by == 'rating') {
                    return $q->orderBy('preview_template', 'ASC')->orderBy('landing_rating', 'DESC')->orderBy('id', 'DESC');
                } else {
                    return $q->orderBy('preview_template', 'ASC')->orderByRaw('CASE WHEN preview_template = 1 THEN template_serial_number ELSE landing_order END ASC')->orderBy('id', 'DESC');
                }
            }, function ($q) {
                return $q->orderBy('preview_template', 'ASC')->orderByRaw('CASE WHEN preview_template = 1 THEN template_serial_number ELSE landing_order END ASC')->orderBy('id', 'DESC');
            })
            ->paginate(10);

                Merchant::ensureTableExistsAndSeeded($currentLang->id);
        $data['featuredMerchants'] = Merchant::where('type', 'featured')->where('language_id', $currentLang->id)->where('status', 1)->orderBy('serial_number', 'ASC')->get();
        $data['lovedMerchants'] = Merchant::where('type', 'review')->where('language_id', $currentLang->id)->where('status', 1)->orderBy('serial_number', 'ASC')->get();
        $data['whyChooseCards'] = Merchant::where('type', 'why_choose')->where('language_id', $currentLang->id)->where('status', 1)->orderBy('serial_number', 'ASC')->get();

        $data['users'] = $users;

        return view('front.shops', $data);
    }

    public function customPage($domain = null, $slug = null)
    {
        if (is_null($slug) && !is_null($domain)) {
            $slug = $domain;
        }
        $user = app('user');
        $userCurrentLang = app('userCurrentLang');
        $id = $user->id;

        $pageId = UserPageContent::where([['slug', $slug], ['user_id', $id]])->pluck('page_id')->firstOrFail();

        $data['page'] = UserPageContent::where([['page_id', $pageId], ['language_id', $userCurrentLang->id], ['user_id', $id]])->first();

        if (is_null($data['page'])) {
            abort(404);
        }

        UserPage::where([['id', $data['page']->page_id], ['status', 1]])->firstOrFail();

        //custom seo info
        $seoInfo = UserSeo::select('custome_page_meta_keyword', 'custome_page_meta_description')
            ->where([['language_id', $userCurrentLang->id], ['user_id', $id]])
            ->first();
        $metaKeyword = isset($seoInfo->custome_page_meta_keyword) ? json_decode($seoInfo->custome_page_meta_keyword, true) : '';
        $metaDescription = isset($seoInfo->custome_page_meta_description) ? json_decode($seoInfo->custome_page_meta_description, true) : '';
        $data['meta_keywords'] = isset($metaKeyword[$pageId]) ? $metaKeyword[$pageId] : '';
        $data['meta_description'] = isset($metaDescription[$pageId]) ? $metaDescription[$pageId] : '';


        //custom page page heading
        $pageHeading = UserHeading::select('custom_page_heading')
            ->where([['language_id', $userCurrentLang->id], ['user_id', $id]])
            ->select('custom_page_heading')
            ->first();
        $pageHeading = isset($pageHeading->custom_page_heading) ? json_decode($pageHeading->custom_page_heading, true) : [];
        $data['title'] = (is_array($pageHeading) && isset($pageHeading[$pageId])) ? $pageHeading[$pageId] : '';

        return view('user-front.custom-page', $data);
    }

    public function paymentInstruction(Request $request)
    {
        $offline = OfflineGateway::where('name', $request->name)
            ->select('short_description', 'instructions', 'is_receipt')
            ->first();
        if (!$offline) {
            return response()->json([
                'description' => '',
                'instructions' => '',
                'is_receipt' => 0
            ]);
        }
        return response()->json([
            'description' => $offline->short_description,
            'instructions' => $offline->instructions,
            'is_receipt' => $offline->is_receipt
        ]);
    }

    public function contactMessage(?Request $request = null, $domain = null)
    {
        $rules = [
            'fullname' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];
        $request->validate($rules);

        $toUser = User::query()->findOrFail($request->id);
        $data['toMail'] = $toUser->email;
        $data['toName'] = $toUser->username;

        $data['subject'] = $request->subject;
        $data['body'] = "<div>$request->message</div><br>
                         <strong>For further contact with the enquirer please use the below information:</strong><br>
                         <strong>Enquirer Name:</strong> $request->fullname <br>
                         <strong>Enquirer Mail:</strong> $request->email <br>
                         ";
        $mailer = new MegaMailer();
        $mailer->mailContactMessage($data);
        Session::flash('success', __('Mail sent successfully'));
        return back();
    }

    public function adminContactMessage(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|max:255',
            'message' => 'required'
        ];

        $bs = BS::select('is_recaptcha', 'google_recaptcha_secret_key')->first();

        if ($bs && $bs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
        $messages = [
            'g-recaptcha-response.required' => __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => __('Captcha error! try again later or contact site admin'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Store submitted lead inquiry into database
        \App\Models\AdminContactMessage::ensureTableExists();
        \App\Models\AdminContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'describe_issues' => $request->describe_issues,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread'
        ]);

        $be = BE::first();
        if ($be && $be->is_smtp == 1) {
            try {
                $subject = $request->subject;
                $msg = '<p>A new contact/lead inquiry has been submitted.<br/>' .
                    '<strong>Name: </strong>' . e($request->name) . '<br/>' .
                    '<strong>Email: </strong>' . e($request->email) . '<br/>' .
                    '<strong>Phone: </strong>' . e($request->phone) . '<br/>' .
                    '<strong>Describe Issues: </strong>' . e($request->describe_issues) . '</p>' .
                    '<p><strong>Message:</strong><br/>' . nl2br(e($request->message)) . '</p>';

                $data = [
                    'smtp_status' => $be->is_smtp,
                    'smtp_host' => $be->smtp_host,
                    'smtp_username' => $be->smtp_username,
                    'smtp_password' => $be->smtp_password,
                    'encryption' => $be->encryption,
                    'smtp_port' => $be->smtp_port,
                    'from_mail' => $be->from_mail,
                    'recipient' => $be->to_mail,
                    'subject' => $subject,
                    'body' => $msg
                ];
                BasicMailer::sendMail($data);
            } catch (\Throwable $e) {
                \Log::warning('SMTP contact mail error: ' . $e->getMessage());
            }
        }

        Session::flash('success', __('Thank you! Your message has been sent successfully.'));
        return back();
    }

    public function changeLanguage($lang): \Illuminate\Http\RedirectResponse
    {
        session()->put('lang', $lang);
        app()->setLocale($lang);
        return redirect()->back();
    }
    public function changeUserLanguage(Request $request, $domain = null)
    {
        session()->put('user_lang', $request->code);
        return redirect()->route('front.user.detail.view', $domain);
    }

    public function templates()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $data['templates'] = User::where([
            ['preview_template', 1],
            ['status', 1],
            ['online_status', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->orderBy('template_serial_number', 'ASC')->get();

        $data['pageHeading'] = $this->getPageHeading($currentLang);

        $data['faqs'] = Faq::where('language_id', $currentLang->id)
            ->orderBy('serial_number', 'asc')
            ->get();

        $data['homeSec'] = ImageText::where('language_id', $currentLang->id)->first() ?? ImageText::first();

        return view('front.template', $data);
    }

    public function autoLoginTemplate($username)
    {
        $user = User::where('username', $username)
            ->where('preview_template', 1)
            ->firstOrFail();

        Auth::guard('web')->login($user);
        Session::put('secrect_login', true);

        return redirect()->route('user-dashboard');
    }

    public function invoice()
    {
        // Seed backdoor removed for production safety

        $data = [];
        $request = [
            'payment_method'=>"Paypal",
            'start_date' => Carbon::now(),
            'expire_date' => Carbon::now()->addDays(99),
        ];
        $data['request'] = $request;
        $data['order_id'] = '09321321378918371';
        $data['member'] = User::orderBy('id', 'desc')->first();
        $data['phone'] = '013917293';
        $data['amount'] = '321';
        $data['base_currency_text_position'] = 'left';
        $data['base_currency_text'] = 'USD';
        $data['status'] = 1;
        $data['package_title'] = 'Basic Package';
        // return view('pdf.membership', $data);
        $pdf = PDF::loadView('pdf.membership', $data);
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/'),
        ]);

        return $pdf->stream('membership.pdf');
        
    }

    public function privacyPolicy()
    {
        $requestHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $isProductSubdomain = str_starts_with($requestHost, 'launchshop.') || str_starts_with($requestHost, 'websitebuilder.') || str_starts_with($requestHost, 'website-builder.');
        $agency = getAgencyFromHost();
        if ($agency && !$isProductSubdomain) {
            return view('front.agency_legal', ['type' => 'privacy', 'agency' => $agency]);
        }
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = __('Privacy Policy');
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;
        return view('front.privacy-policy', $data);
    }

    public function termsConditions()
    {
        $requestHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $isProductSubdomain = str_starts_with($requestHost, 'launchshop.') || str_starts_with($requestHost, 'websitebuilder.') || str_starts_with($requestHost, 'website-builder.');
        $agency = getAgencyFromHost();
        if ($agency && !$isProductSubdomain) {
            return view('front.agency_legal', ['type' => 'terms', 'agency' => $agency]);
        }
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = __('Terms & Conditions');
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;
        return view('front.terms-conditions', $data);
    }

    public function cookiePolicy()
    {
        $requestHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $isProductSubdomain = str_starts_with($requestHost, 'launchshop.') || str_starts_with($requestHost, 'websitebuilder.') || str_starts_with($requestHost, 'website-builder.');
        $agency = getAgencyFromHost();
        if ($agency && !$isProductSubdomain) {
            return view('front.agency_legal', ['type' => 'cookie', 'agency' => $agency]);
        }
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = __('Cookie Policy');
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;
        return view('front.privacy-policy', $data);
    }

    public function refundPolicy()
    {
        $requestHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $isProductSubdomain = str_starts_with($requestHost, 'launchshop.') || str_starts_with($requestHost, 'websitebuilder.') || str_starts_with($requestHost, 'website-builder.');
        $agency = getAgencyFromHost();
        if ($agency && !$isProductSubdomain) {
            return view('front.agency_legal', ['type' => 'refund', 'agency' => $agency]);
        }
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = __('Cancellation & Refund Policy');
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;
        return view('front.refund-policy', $data);
    }

    public function shippingPolicy()
    {
        $requestHost = strtolower(str_replace('www.', '', request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? '')));
        $isProductSubdomain = str_starts_with($requestHost, 'launchshop.') || str_starts_with($requestHost, 'websitebuilder.') || str_starts_with($requestHost, 'website-builder.');
        $agency = getAgencyFromHost();
        if ($agency && !$isProductSubdomain) {
            return view('front.agency_legal', ['type' => 'shipping', 'agency' => $agency]);
        }
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = __('Shipping & Delivery Policy');
        $data['seo'] = \Illuminate\Support\Facades\Schema::hasTable('seos') ? Seo::where('language_id', $currentLang->id)->first() : null;
        return view('front.shipping-policy', $data);
    }



    public function contact()
    {
        return $this->contactView();
    }
}
