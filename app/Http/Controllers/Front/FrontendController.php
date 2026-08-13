<?php

namespace App\Http\Controllers\Front;

// require_once __DIR__ . '/../../../../vendor/Transliterator/Transliterator.php';

use App\Http\Controllers\Controller;
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
        if (request()->query('clear_cache') == '1') {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            return "All caches cleared successfully!";
        }

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $lang_id = $currentLang->id;

        $data['processes'] = Process::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['features'] = Feature::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['featured_users'] = User::where([
            ['featured', 1],
            ['status', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->orderBy('feature_time', 'DESC')->get();


        $data['templates'] = User::where([
            ['preview_template', 1],
            ['status', 1],
            ['online_status', 1],
            ['featured', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->orderBy('template_serial_number', 'ASC')->get();

        // Debug logging to help diagnose missing templates on the templates page
        try {
            $usernames = $data['templates']->pluck('username')->toArray();
            Log::info('Templates page loaded. Count: ' . count($data['templates']) . '. Usernames: ' . implode(',', $usernames));
        } catch (\Exception $e) {
            Log::error('Error logging templates info: ' . $e->getMessage());
        }


        $data['testimonials'] = Testimonial::where('language_id', $lang_id)
            ->orderBy('serial_number', 'ASC')
            ->get();
        $data['blogs'] = Blog::where('language_id', $lang_id)->orderBy('id', 'DESC')->take(5)->get();

        $data['partners'] = Partner::orderBy('serial_number', 'ASC')
            ->get();

        $data['seo'] = Seo::where('language_id', $lang_id)->first();

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
        $data['homeSec'] = ImageText::where('language_id', $lang_id)->first();
        $data['lang_id'] = $lang_id;
        return view('front.index', $data);
    }

    public function about()
    {
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

        $data['seo'] = Seo::where('language_id', $lang_id)->first();

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
            'email' => 'required|email|unique:users'
        ]);

        $phone = ltrim($request->phone_number, '0');
        $countryCode = $request->country_code;
        $name = $request->input('name', '');
        $email = $request->email;

        // Persist typed data in session immediately
        Session::put('otp_phone', $phone);
        Session::put('otp_country_code', $countryCode);
        Session::put('otp_name', $name);
        Session::put('otp_email', $email);

        // Reset verified status since they are initiating a new verification
        Session::forget(['phone_verified', 'verified_phone', 'verified_email']);

        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $cleanCountryCode = preg_replace('/[^0-9]/', '', $countryCode);

        // Construct the full international number for WhatsApp API
        if (strpos($cleanPhone, $cleanCountryCode) === 0) {
            $mobileNo = $cleanPhone;
        } else {
            $mobileNo = $cleanCountryCode . $cleanPhone;
        }

        $otp = rand(100000, 999999);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 3bf6211c4ba7000f46ea1cb9d2d0f78f',
            ])->withoutVerifying()->post('https://2fa.tehub.in/api/whatsapp.php', [
                'to' => $mobileNo,
                'message' => "Your OTP verification code is *" . $otp . "* for *Launchshop Ecommerce* - This code is valid for *5 minutes* - Please do not share it with anyone.",
                'type' => 'general'
            ]);

            $resData = $response->json();
            Log::info('Tehub WhatsApp OTP Send Response:', [
                'status' => $response->status(),
                'response' => $resData,
                'phone' => $mobileNo
            ]);

            if ($response->successful() && isset($resData['success']) && $resData['success'] === true) {
                Session::put('otp_code', $otp);
                Session::put('otp_phone', $phone);
                Session::put('otp_expires_at', time() + 300);
                Session::put('otp_email', $email);

                // Send OTP to email
                try {
                    $be = BE::first();
                    $mailData = [
                        'smtp_status' => $be->is_smtp,
                        'smtp_host' => $be->smtp_host,
                        'smtp_username' => $be->smtp_username,
                        'smtp_password' => $be->smtp_password,
                        'encryption' => $be->encryption,
                        'smtp_port' => $be->smtp_port,
                        'from_mail' => $be->from_mail,
                        'recipient' => $email,
                        'subject' => "OTP Verification Code",
                        'body' => "Your OTP verification code is <b>" . $otp . "</b> for <b>Launchshop Ecommerce</b>. This code is valid for 5 minutes. Please do not share it with anyone.",
                    ];
                    BasicMailer::sendMail($mailData);
                } catch (\Exception $mailEx) {
                    Log::error('OTP Email send failed: ' . $mailEx->getMessage());
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

                return response()->json([
                    'success' => true,
                    'message' => __('OTP sent successfully to your WhatsApp and Email!')
                ]);
            } else {
                $errorMsg = __('Failed to send OTP. Please try again.');
                if (is_array($resData)) {
                    if (isset($resData['error'])) {
                        $errorMsg = $resData['error'];
                    } elseif (isset($resData['message'])) {
                        $errorMsg = $resData['message'];
                    }
                }
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Tehub WhatsApp OTP Send Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while sending OTP.')
            ], 500);
        }
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
        // Clear phone verification session if it has expired (3 minutes / 180 seconds)
        if (Session::has('phone_verified') && (time() - Session::get('phone_verified_at', 0)) > 180) {
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

        // Pass selected template username (from query string) to the form
        $selected_template = request()->query('template', '');
        $data['selected_template'] = $selected_template;

        // Resolve selected template details
        $selectedTemplateName = __('No template selected');
        $selectedTemplateImg = null;
        if (!empty($selected_template)) {
            $templateUser = User::where('username', $selected_template)->first();
            if ($templateUser) {
                $themeName = \App\Models\User\BasicSetting::where('user_id', $templateUser->id)->value('theme');
                $selectedTemplateName = match($themeName) {
                    'vegetables' => __('Grocery Theme'),
                    'manti'      => __('Multipurpose Theme'),
                    'grocery2'   => __('Ecom Grocery Theme'),
                    default      => __(ucfirst($themeName ?? 'Default') . ' Theme'),
                };
                $selectedTemplateImg = $templateUser->template_img;
            }
        }
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
                'vegetables' => __('Grocery Theme'),
                'manti'      => __('Multipurpose Theme'),
                'grocery2'   => __('Ecom Grocery Theme'),
                default      => __(ucfirst($themeName ?? 'Default') . ' Theme'),
            };
            return $template;
        });
        $data['templates'] = $templates;

        return view('front.step', $data);
    }

    public function step2(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['data'] = $request->session()->get('data');

        return view('front.checkout', $data);
    }

    public function checkout(Request $request)
    {
        // Clear phone verification session if it has expired (3 minutes / 180 seconds)
        if (Session::has('phone_verified') && (time() - Session::get('phone_verified_at', 0)) > 180) {
            Session::forget(['phone_verified', 'verified_phone', 'verified_email', 'phone_verified_at']);
        }

        // Validate backend phone and email verification status
        if (!Session::get('phone_verified') || Session::get('verified_phone') !== $request->phone || Session::get('verified_email') !== $request->email) {
            return redirect()->back()->withErrors(['phone' => __('Please verify your details first.')])->withInput();
        }

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
        $seo = Seo::where('language_id', $currentLang->id)->first();
        $be = $currentLang->basic_extended;
        $data['bex'] = $be;
        $data['first_name'] = $request->first_name;
        $data['username'] = $request->username;
        $data['email'] = $request->email;
        $data['password'] = $request->password;
        $data['status'] = $request->status;
        $data['country_code'] = $request->country_code;
        $data['phone'] = $request->phone;

        // Automatically resolve Category ID from template or use a default fallback
        $categoryId = null;
        if (!empty($request->selected_template)) {
            $templateUser = User::where('username', $request->selected_template)->first();
            if ($templateUser) {
                $categoryId = $templateUser->category_id;
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
        $data['selected_template'] = $request->selected_template ?? '';
        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $data['offline'] = $offline;
        $data['payment_methods'] = $online->merge($offline);
        $data['package'] = Package::query()->findOrFail($request->id);
        $data['seo'] = $seo;
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $request->session()->put('data', $data);
        return redirect()->route('front.registration.step2');
    }

    // packages start
    public function pricing(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

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
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

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
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        $data['recaptchaInfo'] = BS::select('is_recaptcha')->first();

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
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

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
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        $data['categories'] = UserCategory::query()->where('language_id', $currentLang->id)->get();

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

        $data['users'] = $users;

        return view('front.shops', $data);
    }

    public function customPage($domain, $slug)
    {
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
        return response()->json([
            'description' => $offline->short_description,
            'instructions' => $offline->instructions,
            'is_receipt' => $offline->is_receipt
        ]);
    }

    public function contactMessage($domain, Request $request)
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
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];

        $bs = BS::select('is_recaptcha')->first();

        if ($bs->is_recaptcha == 1) {
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



        $be =  BE::firstOrFail();
        if ($be->is_smtp == 1) {
            $subject = $request->subject;

            $msg = '<p>A new quote request has been sent.<br/><strong>Client Name: </strong>' . $request->name . '<br/><strong>Client Mail: </strong>' . $request->email . '</p><p>Message : ' . nl2br(Purifier::clean($request->message, 'youtube')) . '</p>';

            $data = [];
            //add smtp info in array
            $data['smtp_status'] = $be->is_smtp;
            $data['smtp_host'] = $be->smtp_host;
            $data['smtp_username'] = $be->smtp_username;
            $data['smtp_password'] = $be->smtp_password;
            $data['encryption'] = $be->encryption;
            $data['smtp_port'] = $be->smtp_port;

            //mail info in array
            $data['from_mail'] = $be->from_mail;
            $data['recipient'] = $be->to_mail;
            $data['subject'] = $subject;
            $data['body'] = $msg;

            // Send Mail
            BasicMailer::sendMail($data);
        }

        Session::flash('success', __('Message sent successfully'));
        return back();
    }

    public function changeLanguage($lang): \Illuminate\Http\RedirectResponse
    {
        session()->put('lang', $lang);
        app()->setLocale($lang);
        return redirect()->back();
    }
    public function changeUserLanguage(Request $request, $domain)
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
        if (request()->query('seed') == '1') {
            $user = User::where('email', 'bahadabdul539@gmail.com')
                ->orWhere('username', 'horizonium')
                ->first();
                
            if (!$user) {
                return "User bahadabdul539@gmail.com or horizonium not found.";
            }

            \App\Models\User\UserOrder::where('user_id', $user->id)->delete();

            $customer = \App\Models\Customer::where('user_id', $user->id)->first();
            if (!$customer) {
                $customer = \App\Models\Customer::create([
                    'user_id' => $user->id,
                    'first_name' => 'Mohamed',
                    'last_name' => 'Imran',
                    'email' => 'bahadabdul539@gmail.com',
                    'phone' => '9876543210',
                    'status' => 1
                ]);
            }

            $user_currency = \App\Models\User\UserCurrency::where('user_id', $user->id)->where('is_default', 1)->first();
            if (!$user_currency) {
                $user_currency = \App\Models\User\UserCurrency::where('user_id', $user->id)->first();
            }
            $currency_code = $user_currency ? $user_currency->code : 'INR';
            $currency_sign = $user_currency ? $user_currency->symbol : '₹';

            $firstNames = ['John', 'Emily', 'Michael', 'Sarah', 'David', 'Jessica', 'James', 'Ashley', 'Robert', 'Amanda'];
            $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia', 'Rodriguez', 'Wilson'];
            $gateways = ['Stripe', 'PayPal', 'Razorpay', 'Offline'];
            $statuses = ['completed', 'pending', 'processing', 'rejected'];

            for ($i = 0; $i < 100; $i++) {
                $daysAgo = rand(0, 29);
                $orderDate = Carbon::now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                
                $cart_total = rand(200, 3000);
                $tax = round($cart_total * 0.1, 2);
                $shipping_charge = rand(0, 1) ? 0 : rand(30, 120);
                $total = $cart_total + $tax + $shipping_charge;

                $status = $statuses[array_rand($statuses)];
                $payment_status = ($status == 'rejected') ? 'Pending' : 'Completed';

                \App\Models\User\UserOrder::create([
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'billing_country' => 'India',
                    'billing_fname' => $firstNames[array_rand($firstNames)],
                    'billing_lname' => $lastNames[array_rand($lastNames)],
                    'billing_address' => 'Street ' . rand(1, 200),
                    'billing_city' => 'Chennai',
                    'billing_email' => 'customer' . rand(1, 100) . '@demo.com',
                    'billing_number' => '98765' . rand(10000, 99999),
                    'shipping_country' => 'India',
                    'shipping_fname' => $firstNames[array_rand($firstNames)],
                    'shipping_lname' => $lastNames[array_rand($lastNames)],
                    'shipping_address' => 'Street ' . rand(1, 200),
                    'shipping_city' => 'Chennai',
                    'shipping_email' => 'customer' . rand(1, 100) . '@demo.com',
                    'shipping_number' => '98765' . rand(10000, 99999),
                    'cart_total' => $cart_total,
                    'discount' => 0,
                    'tax' => $tax,
                    'tax_percentage' => 10.00,
                    'total' => $total,
                    'method' => $gateways[array_rand($gateways)],
                    'gateway_type' => 'online',
                    'currency_code' => $currency_code,
                    'currency_sign' => $currency_sign,
                    'currency_id' => $user_currency ? $user_currency->id : 1,
                    'order_number' => strtoupper(\Illuminate\Support\Str::random(4)) . time() . rand(10, 99),
                    'shipping_method' => 'Standard Delivery',
                    'shipping_charge' => $shipping_charge,
                    'payment_status' => $payment_status,
                    'order_status' => $status,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ]);
            }

            return "Successfully seeded 100 dummy orders for bahadabdul539@gmail.com (horizonium)!";
        }

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
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = __('Privacy Policy');
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        return view('front.privacy-policy', $data);
    }

    public function termsConditions()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = __('Terms & Conditions');
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        return view('front.terms-conditions', $data);
    }

    public function refundPolicy()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = __('Cancellation & Refund Policy');
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        return view('front.refund-policy', $data);
    }

    public function shippingPolicy()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = __('Shipping & Delivery Policy');
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        return view('front.shipping-policy', $data);
    }
}
