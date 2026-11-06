<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\Admin\Heading;
use App\Models\BasicExtended;
use App\Models\BasicSetting;
use App\Models\Language;
use App\Models\Page;
use App\Models\Seo;
use App\Models\Timezone;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Purifier;
use Validator;

class BasicController extends Controller
{
    public function generalSetting()
    {
        if (session()->has('admin_lang')) {
            $lang_code = str_replace('admin_', '', session()->get('admin_lang'));
            $language = Language::where('code', $lang_code)->first();
            if (empty($language)) {
                $language = Language::where('is_default', 1)->first();
            }
        } else {
            $language = Language::where('is_default', 1)->first();
        }
        $data['language'] = $language;
        $data['abs'] = BasicSetting::firstOrFail();
        $data['abe'] = BasicExtended::firstOrFail();
        $data['timezones'] = Timezone::all();
        return view('admin.basic.general-settings', $data);
    }

    public function updateGeneralSetting(Request $request)
    {
        $allowedExts = array('jpg', 'png', 'jpeg', 'ico');
        $allowedExts2 =  array('jpg', 'png', 'jpeg');
        $allowedExts3 = array('jpg', 'png', 'jpeg', 'gif', 'svg');
        $img = $request->file('favicon');
        $img2 = $request->file('logo');
        $img3 = $request->file('preloader');
        $rules = [
            'website_title' => 'required',
            'timezone' => 'required',
            'base_color' => 'required',
            'base_color_2' => 'required',
            'base_currency_symbol' => 'required',
            'base_currency_symbol_position' => 'required',
            'base_currency_text' => 'required',
            'base_currency_text_position' => 'required',
            'base_currency_rate' => 'required|numeric',
            'favicon' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
            'logo' => [
                function ($attribute, $value, $fail) use ($img2, $allowedExts2) {
                    if (!empty($img2)) {
                        $ext = $img2->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts2)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
            'preloader_status' => 'required',
            'preloader' => [
                function ($attribute, $value, $fail) use ($img3, $allowedExts3) {
                    if (!empty($img3)) {
                        $ext = $img3->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts3)) {
                            return $fail(__("Only gif, png, jpg, jpeg image is allowed"));
                        }
                    }
                },
            ]
        ];

        $request->validate($rules);

        $dir = public_path('assets/front/img/');
        if ($request->hasFile('favicon')) {
            $favicon_name = Uploader::upload_picture($dir, $img);
        }

        if ($request->hasFile('logo')) {
            $logoname = Uploader::upload_picture($dir, $img2);
        }

        if ($request->hasFile('preloader')) {
            $preloadername = Uploader::upload_picture($dir, $img3);
        }

        try {
            if (!Schema::hasColumn('basic_settings', 'is_gemini')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_gemini TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}
        try {
            if (!Schema::hasColumn('basic_settings', 'is_openai')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_openai TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}

        $bss = BasicSetting::all();
        foreach ($bss as $key => $bs) {
            $bs->website_title = $request->website_title;
            $bs->base_color = $request->base_color;
            $bs->time_format = $request->time_format;
            $bs->base_color_2 = $request->base_color_2;
            $bs->favicon = $request->hasFile('favicon') ? $favicon_name : $bs->favicon;
            $bs->logo = $request->hasFile('logo') ? $logoname : $bs->logo;
            $bs->preloader = $request->hasFile('preloader') ? $preloadername : $bs->preloader;
            $bs->save();
        }

        $bes = BasicExtended::all();
        foreach ($bes as $key => $be) {
            $be->base_currency_symbol = $request->base_currency_symbol;
            $be->base_currency_symbol_position = $request->base_currency_symbol_position;
            $be->base_currency_text = $request->base_currency_text;
            $be->base_currency_text_position = $request->base_currency_text_position;
            $be->base_currency_rate = $request->base_currency_rate;
            $be->timezone = $request->timezone;
            $be->save();
        }
        // set timezone in .env
        if ($request->has('timezone') && $request->filled('timezone')) {
            $arr = ['TIMEZONE' => $request->timezone];
            setEnvironmentValue($arr);
            \Artisan::call('config:clear');
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function removeImage($language_id, Request $request)
    {
        $data = BasicSetting::where('language_id', $language_id)->firstOrFail();;

        switch ($request->name) {
            case 'favicon':
                @unlink(public_path("assets/front/img/") . $data->favicon);
                $data->favicon = null;
                break;

            case 'logo':
                @unlink(public_path("assets/front/img/") . $data->logo);
                $data->logo = null;
                break;

            case 'preloader':
                @unlink(public_path("assets/front/img/") . $data->preloader);
                $data->preloader = null;
                break;
        }
        $data->save();
        Session::flash('success', __('Removed successfully'));
        return response()->json(['status' => 'success']);
    }


    public function updateslider(Request $request, $lang)
    {
        $be = BasicExtended::where('language_id', $lang)->firstOrFail();

        $dir = public_path('assets/front/img/');
        @mkdir($dir, 0775, true);
        if ($request->hasFile('slider_shape_img')) {
            @unlink($dir . $be->slider_shape_img);
            $filename = Uploader::upload_picture($dir, $request->slider_shape_img);
            $be->slider_shape_img = $filename;
        }

        if ($request->hasFile('slider_bottom_img')) {
            @unlink($dir . $be->slider_bottom_img);
            $filename = Uploader::upload_picture($dir, $request->slider_bottom_img);
            $be->slider_bottom_img = $filename;
        }

        $be->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function breadcrumb(Request $request)
    {
        $data['abs'] = BasicSetting::firstOrFail();
        return view('admin.basic.breadcrumb', $data);
    }

    public function updatebreadcrumb(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'breadcrumb']);
        }
        if ($request->hasFile('file')) {
            $dir = public_path('assets/front/img/');
            @mkdir($dir, 0775, true);
            $filename = Uploader::upload_picture($dir, $img);

        $bss = BasicSetting::all();
            foreach ($bss as $key => $bs) {
                @unlink($dir . $bs->breadcrumb);
                $bs->breadcrumb = $filename;
                $bs->save();
            }
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }


    public function script()
    {
        try {
            if (!Schema::hasColumn('basic_settings', 'is_gemini')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_gemini TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}
        try {
            if (!Schema::hasColumn('basic_settings', 'is_openai')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_openai TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}

        $data = BasicSetting::first();
        if ($data) {
            $dirty = false;
            if (!empty($data->openai_api_key) && (str_contains($data->openai_api_key, 'XXXX') || str_contains($data->openai_api_key, 'DUMMY') || str_contains(strtolower($data->openai_api_key), 'dummy'))) {
                $data->openai_api_key = null;
                if (Schema::hasColumn('basic_settings', 'is_openai')) {
                    $data->is_openai = 0;
                }
                $dirty = true;
            }
            if (!empty($data->gemini_api_key) && (str_contains($data->gemini_api_key, 'XXXX') || str_contains($data->gemini_api_key, 'DUMMY') || str_contains(strtolower($data->gemini_api_key), 'dummy'))) {
                $data->gemini_api_key = null;
                if (Schema::hasColumn('basic_settings', 'is_gemini')) {
                    $data->is_gemini = 0;
                }
                $dirty = true;
            }
            if ($dirty) {
                try {
                    $data->save();
                } catch (\Throwable $e) {
                }
            }
        }
        return view('admin.basic.scripts', ['data' => $data]);
    }

    public function updatescript(Request $request)
    {

        try {
            if (!Schema::hasColumn('basic_settings', 'is_gemini')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_gemini TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}
        try {
            if (!Schema::hasColumn('basic_settings', 'is_openai')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_openai TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}

        $bss = BasicSetting::all();

        foreach ($bss as $bs) {
            $bs->tak_to_property_id = $request->tak_to_property_id;
            $bs->tak_to_widget_id = $request->tak_to_widget_id;
            $bs->is_tawkto = $request->is_tawkto;

            $bs->is_disqus = $request->is_disqus;
            $bs->is_user_disqus = $request->is_user_disqus;
            $bs->disqus_shortname = $request->disqus_shortname;

            $bs->is_recaptcha = $request->is_recaptcha;
            $bs->google_recaptcha_site_key = $request->google_recaptcha_site_key;
            $bs->google_recaptcha_secret_key = $request->google_recaptcha_secret_key;

            $bs->is_whatsapp = $request->is_whatsapp;
            $bs->whatsapp_number = $request->whatsapp_number;
            $bs->whatsapp_header_title = $request->whatsapp_header_title;
            $bs->whatsapp_popup_message = Purifier::clean($request->whatsapp_popup_message);
            $bs->whatsapp_popup = $request->whatsapp_popup;

            // OpenAI Credentials and Status
            $openaiKey = trim((string)$request->openai_api_key);
            if (str_contains($openaiKey, 'XXXX') || str_contains($openaiKey, 'DUMMY') || str_contains(strtolower($openaiKey), 'dummy')) {
                $openaiKey = '';
            }
            $bs->is_openai = $request->has('is_openai') ? (int)$request->is_openai : 0;
            $bs->openai_api_key = !empty($openaiKey) ? $openaiKey : null;
            $bs->openai_text_model = $request->openai_text_model;
            $bs->openai_image_model = $request->openai_image_model;

            // Google Gemini Credentials and Status
            $geminiKey = trim((string)$request->gemini_api_key);
            if (str_contains($geminiKey, 'XXXX') || str_contains($geminiKey, 'DUMMY') || str_contains(strtolower($geminiKey), 'dummy')) {
                $geminiKey = '';
            }
            $bs->is_gemini = $request->has('is_gemini') ? (int)$request->is_gemini : 0;
            $bs->gemini_api_key = !empty($geminiKey) ? $geminiKey : null;
            $bs->gemini_text_model = $request->gemini_text_model;
            $bs->gemini_image_model = $request->gemini_image_model;

            $pollinationsKey = trim((string)$request->pollinations_secret_key);
            if (str_contains($pollinationsKey, 'XXXX') || str_contains($pollinationsKey, 'DUMMY') || str_contains(strtolower($pollinationsKey), 'dummy')) {
                $pollinationsKey = '';
            }
            $bs->pollinations_secret_key = !empty($pollinationsKey) ? $pollinationsKey : null;
            $bs->pollinations_text_model = $request->pollinations_text_model;
            $bs->pollinations_image_model = $request->pollinations_image_model;

            $bs->save();
        }

        $envUpdates = [
            'OPENAI_API_KEY' => '',
            'OPENAI_TEXT_MODEL' => $request->openai_text_model ?? '',
            'OPENAI_IMAGE_MODEL' => $request->openai_image_model ?? '',
            'GEMINI_API_KEY' => '',
            'GEMINI_TEXT_MODEL' => $request->gemini_text_model ?? '',
            'GEMINI_IMAGE_MODEL' => $request->gemini_image_model ?? '',
            'POLLINATIONS_SECRET_KEY' => '',
            'POLLINATIONS_TEXT_MODEL' => $request->pollinations_text_model ?? '',
            'POLLINATIONS_IMAGE_MODEL' => $request->pollinations_image_model ?? '',
        ];
        setEnvironmentValue($envUpdates);
        Artisan::call('config:clear');

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function maintainance()
    {
        $data = BasicSetting::select('maintainance_mode', 'maintenance_img', 'maintenance_status', 'maintainance_text', 'secret_path')
            ->first();

        return view('admin.basic.maintainance', ['data' => $data]);
    }

    public function updatemaintainance(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
            'maintenance_status' => 'required',
            'maintainance_text' => 'required'
        ];

        $message = [
            'maintainance_text.required' => __('The maintenance message field is required')
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $bs = BasicSetting::first();

        $dir = public_path("assets/front/img/");
        @mkdir($dir, 0775, true);
        // first, get the maintenance image from db
        if ($request->hasFile('file')) {
            @unlink($dir . $bs->maintenance_img);
            $filename = Uploader::upload_picture($dir, $request->file('file'));
        }

        $down = "down";
        if ($request->filled('secret_path')) {
            $down .= " --secret=" . $request->secret_path;
        }
        if ($request->maintenance_status == 1) {
            @unlink(storage_path('framework/down'));
            Artisan::call($down);
        } else {
            Artisan::call('up');
        }
        $bs->update([
            'maintenance_img' => $request->hasFile('file') ? $filename : $bs->maintenance_img,
            'maintenance_status' => $request->maintenance_status,
            'maintainance_text' => Purifier::clean($request->maintainance_text, 'youtube'),
            'secret_path' => $request->secret_path
        ]);
        Session::flash('success', __('Updated Successfully'));
        return redirect()->back();
    }

    public function sections(Request $request)
    {
        $data['abs'] = BasicSetting::first();
        if (!is_null($data['abs']->additional_section_status) && $data['abs']->additional_section_status != "null") {
            $data['additional_section_statuses']  = json_decode($data['abs']->additional_section_status, true);
        } else {
            $data['additional_section_statuses'] = [];
        }
        $language = Language::where('is_default', 1)->first();
        $data['languge_id'] = $language->id;
        return view('admin.home.sections', $data);
    }

    public function updatesections(Request $request)
    {
        try {
            if (!Schema::hasColumn('basic_settings', 'is_gemini')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_gemini TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}
        try {
            if (!Schema::hasColumn('basic_settings', 'is_openai')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_openai TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}

        $bss = BasicSetting::all();
        $in = $request->all();
        $in['additional_section_status'] = json_encode($request->additional_sections, true);
        unset($in['additional_sections']);
        foreach ($bss as $key => $bs) {
            $bs->update($in);
        }

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function aboutSectionInfo()
    {
        $data['abs'] = BasicSetting::first();
        if (!is_null($data['abs']->about_additional_section_status) && $data['abs']->about_additional_section_status != "null") {
            $data['additional_section_statuses']  = json_decode($data['abs']->about_additional_section_status, true);
        } else {
            $data['additional_section_statuses'] = [];
        }
        $language = Language::where('is_default', 1)->first();
        $data['languge_id'] = $language->id;
        return view('admin.about.sections', $data);
    }

    public function aboutSectionInfoUpdate(Request $request)
    {
        try {
            if (!Schema::hasColumn('basic_settings', 'is_gemini')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_gemini TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}
        try {
            if (!Schema::hasColumn('basic_settings', 'is_openai')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE basic_settings ADD COLUMN is_openai TINYINT(1) NOT NULL DEFAULT 0");
            }
        } catch (\Throwable $e) {}

        $bss = BasicSetting::all();
        $in = $request->all();
        $in['about_additional_section_status'] = json_encode($request->additional_sections, true);
        unset($in['additional_sections']);
        foreach ($bss as $key => $bs) {
            $bs->update($in);
        }

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function cookiealert(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abe'] = $lang->basic_extended;

        return view('admin.basic.cookie', $data);
    }

    public function updatecookie(Request $request, $langid)
    {
        $request->validate([
            'cookie_alert_status' => 'required',
            'cookie_alert_text' => 'required',
            'cookie_alert_button_text' => 'required|max:25',
        ]);

        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $be->cookie_alert_status = $request->cookie_alert_status;
        $be->cookie_alert_text = Purifier::clean($request->cookie_alert_text);
        $be->cookie_alert_button_text = $request->cookie_alert_button_text;
        $be->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function seo(Request $request)
    {
        $language = Language::where('code', $request->language)->firstOrFail();
        $langId = $language->id;
        $seo = Seo::where('language_id', $langId);

        if ($seo->count() == 0) {
            Seo::create($request->except('language_id') + [
                'language_id' => $langId
            ]);
        }

        $information['language'] = $language;
        $information['data'] = $seo->first();
        $information['langs'] = Language::all();

        $information['decodedKeywords'] = isset($information['data']->custome_page_meta_keyword) ? json_decode($information['data']->custome_page_meta_keyword, true) : '';
        $information['decodedDescriptions'] = isset($information['data']->custome_page_meta_description) ? json_decode($information['data']->custome_page_meta_description, true) : '';

        $information['pages'] = Page::where([['language_id', $langId], ['status', 1]])->get();

        return view('admin.basic.seo', $information);
    }

    public function updateSEO(Request $request)
    {
        $language = Language::where('code', $request->language)->firstOrFail();
        $langId = $language->id;
        $seo = SEO::where('language_id', $langId)->first();
        $seo->update($request->all());
        Session::flash('success', __('Updated Sccessfully'));
        return redirect()->back();
    }

    public function heading(Request $request)
    {
        $data['language'] = Language::where('code', $request->language)->firstOrFail();
        $data['heading'] = Heading::where('language_id', $data['language']->id)->first();
        $data['decodedHeadings'] = isset($data['heading']->custom_page_heading) ? json_decode($data['heading']->custom_page_heading, true) : '';
        $data['pages'] = Page::where([['language_id', $data['language']->id], ['status', 1]])->get();
        return view('admin.heading.index', $data);
    }
    public function update_heading(Request $request)
    {
        $data = [
            'template_title' => request('template_title'),
            'pricing_title' => request('pricing_title'),
            'shop_title' => request('shop_title'),
            'faq_title' => request('faq_title'),
            'contact_title' => request('contact_title'),
            'blog_title' => request('blog_title'),
            'login_title' => request('login_title'),
            'reset_password_title' => request('reset_password_title'),
            'signup_title' => request('signup_title'),
            'checkout_title' => request('checkout_title'),
            'custom_page_heading' => request('custom_page_heading'),
            'not_found_title' => request('not_found_title'),
            'language_id' => $request->language_id
        ];

        Heading::updateOrInsert(['language_id' => $request->language_id], $data);

        session()->flash('success', __('Updated successfully'));
        return 'success';
    }

    public static function ensureExpiryWarningColumnsExist()
    {
        try {
            $table = 'basic_settings';
            $columns = [
                'recurring_warning_first_days'        => ['type' => 'string', 'default' => '30'],
                'recurring_warning_second_days'       => ['type' => 'string', 'default' => '7'],
                'recurring_warning_daily_days'        => ['type' => 'string', 'default' => '3'],
                'recurring_warning_status'            => ['type' => 'string', 'default' => '1'],
                'recurring_warning_whatsapp_template' => ['type' => 'text', 'default' => null],
                'recurring_warning_email_subject'     => ['type' => 'string', 'default' => 'Subscription Expiry Notice: {store_name} ({plan_name})'],
                'recurring_warning_email_template'    => ['type' => 'text', 'default' => null],
                'usage_pricing_status'                => ['type' => 'string', 'default' => '1'],
                'usage_increase_threshold'            => ['type' => 'string', 'default' => '40'],
                'usage_price_multiplier'              => ['type' => 'string', 'default' => '2.0'],
                'usage_baseline_orders'               => ['type' => 'string', 'default' => '100'],
                'usage_baseline_products'             => ['type' => 'string', 'default' => '50'],
            ];

            foreach ($columns as $column => $def) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn($table, $column)) {
                    \Illuminate\Support\Facades\Schema::table($table, function (\Illuminate\Database\Schema\Blueprint $t) use ($column, $def) {
                        if ($def['type'] === 'text') {
                            $t->text($column)->nullable();
                        } else {
                            $t->string($column)->nullable()->default($def['default']);
                        }
                    });
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('ensureExpiryWarningColumnsExist: ' . $e->getMessage());
        }
    }

    public static function calculateRenewalPricing($user, $membership = null, $basic = null)
    {
        if (!$basic) {
            $basic = \App\Models\BasicSetting::first();
        }
        if (!$membership && $user) {
            $membership = $user->memberships()->where('status', '<>', 2)->orderBy('id', 'DESC')->first();
        }

        $basePrice = 0;
        $currencySymbol = '₹';
        if ($membership) {
            $basePrice = (float)($membership->price ?? 0);
            $currencySymbol = $membership->currency_symbol ?? '₹';
            if ($basePrice <= 0 && $membership->package) {
                $basePrice = (float)($membership->package->price ?? 0);
            }
        }

        // Fetch usage metrics
        $orderCount = \App\Models\User\UserOrder::where('user_id', $user->id)->count();
        $productCount = \App\Models\User\UserItem::where('user_id', $user->id)->count();

        // Baseline settings
        $baselineOrders = max(1, (int)($basic->usage_baseline_orders ?? 100));
        $baselineProducts = max(1, (int)($basic->usage_baseline_products ?? 50));
        $thresholdPercent = (float)($basic->usage_increase_threshold ?? 40); // 40%
        $multiplier = max(1.0, (float)($basic->usage_price_multiplier ?? 2.0)); // 2x

        // Calculate usage percent over baseline
        $ordersRatio = ($orderCount / $baselineOrders) * 100;
        $productsRatio = ($productCount / $baselineProducts) * 100;
        $effectiveUsageRatio = max($ordersRatio, $productsRatio);

        // Usage increase percentage over 100% baseline
        $increasePercent = max(0, $effectiveUsageRatio - 100);

        $pricingEnabled = ($basic->usage_pricing_status ?? '1') == '1';
        $isSurge = false;
        $renewalPrice = $basePrice;

        if ($pricingEnabled && $increasePercent >= $thresholdPercent && $basePrice > 0) {
            $isSurge = true;
            $renewalPrice = round($basePrice * $multiplier, 2);
        }

        return [
            'base_price'        => $basePrice,
            'renewal_price'     => $renewalPrice,
            'currency_symbol'   => $currencySymbol,
            'order_count'       => $orderCount,
            'product_count'     => $productCount,
            'baseline_orders'   => $baselineOrders,
            'baseline_products' => $baselineProducts,
            'increase_percent'  => round($increasePercent, 1),
            'threshold_percent' => $thresholdPercent,
            'multiplier'        => $multiplier,
            'is_surge'          => $isSurge,
            'pricing_enabled'   => $pricingEnabled,
        ];
    }

    public function recurringWarning()
    {
        static::ensureExpiryWarningColumnsExist();

        $data['basic'] = \App\Models\BasicSetting::first();

        // Default WhatsApp template if empty
        if (empty($data['basic']->recurring_warning_whatsapp_template)) {
            $data['basic']->recurring_warning_whatsapp_template = "⚠️ *LaunchShop Subscription Expiry Notice*\n\nHello *{user_name}*,\n\nYour *LaunchShop* store (*{store_name}*) subscription for plan *{plan_name}* will expire in *{days_left}* on *{expire_date}*.\n\n📊 *Usage Details:* {usage_summary}\n💰 *Base Package Price:* {base_price}\n🏷️ *Renewal Amount:* *{renewal_price}*{surge_badge}\n\nTo prevent storefront downtime and retain all your store settings, renew your subscription now:\n🔗 *Renew Here:*\n{renewal_url}\n\nNeed assistance? Reply to this message anytime.\n– Team LaunchShop 🚀";
        }

        // Default Email Subject & Template if empty
        if (empty($data['basic']->recurring_warning_email_subject)) {
            $data['basic']->recurring_warning_email_subject = 'Subscription Expiry Notice: {store_name} ({plan_name})';
        }
        if (empty($data['basic']->recurring_warning_email_template)) {
            $data['basic']->recurring_warning_email_template = "<p>Hello {user_name},</p><p>Your subscription for store <strong>{store_name}</strong> ({plan_name}) will expire on <strong>{expire_date}</strong> (<strong>{days_left}</strong>).</p><p><strong>Store Usage Summary:</strong> {usage_summary}</p><p><strong>Base Plan Price:</strong> {base_price}<br><strong>Renewal Price:</strong> <span style='font-size: 18px; color: #059669; font-weight: bold;'>{renewal_price}</span> {surge_badge}</p><p><a href='{renewal_url}' style='background-color:#059669;color:#ffffff;padding:12px 24px;border-radius:6px;text-decoration:none;display:inline-block;font-weight:bold;'>Renew Subscription Now</a></p><p>Best regards,<br>LaunchShop Team</p>";
        }

        // Fetch registered merchants with their latest membership and calculate dynamic metrics
        $users = \App\Models\User::with(['memberships' => function ($q) {
            $q->where('status', '<>', 2)->orderBy('id', 'DESC');
        }, 'memberships.package'])->get();

        $subscribers = [];
        $now = \Carbon\Carbon::now();

        foreach ($users as $user) {
            $membership = $user->memberships->first();
            if (!$membership) {
                continue;
            }

            $expireDate = \Carbon\Carbon::parse($membership->expire_date);
            $daysLeft = $now->diffInDays($expireDate, false);
            $isLifetime = ($membership->package && $membership->package->term === 'lifetime') || $expireDate->year >= 9999;
            $pricing = static::calculateRenewalPricing($user, $membership, $data['basic']);

            $subscribers[] = [
                'user'         => $user,
                'membership'   => $membership,
                'package'      => $membership->package,
                'expire_date'  => $expireDate,
                'days_left'    => $daysLeft,
                'is_lifetime'  => $isLifetime,
                'is_expired'   => $daysLeft < 0,
                'pricing'      => $pricing,
            ];
        }

        // Sort by expiry date ascending
        usort($subscribers, function ($a, $b) {
            return $a['expire_date']->timestamp <=> $b['expire_date']->timestamp;
        });

        $data['subscribers'] = $subscribers;

        return view('admin.basic.recurring_warning', $data);
    }

    public function updateRecurringWarning(\Illuminate\Http\Request $request)
    {
        static::ensureExpiryWarningColumnsExist();

        $validated = $request->validate([
            'recurring_warning_first_days'  => 'nullable|integer|min:1|max:365',
            'recurring_warning_second_days' => 'nullable|integer|min:1|max:365',
            'recurring_warning_daily_days'  => 'nullable|integer|min:1|max:365',
            'recurring_warning_message'     => 'nullable|string',
        ]);

        $validated['recurring_warning_status'] = $request->has('recurring_warning_status') ? '1' : '0';

        $basic = \App\Models\BasicSetting::first();
        if ($basic) {
            $basic->update($validated);
        }

        \Illuminate\Support\Facades\Session::flash('success', __('Payment recurring warning timing updated successfully!'));
        return back();
    }

    public function updateRecurringTemplate(\Illuminate\Http\Request $request)
    {
        static::ensureExpiryWarningColumnsExist();

        $validated = $request->validate([
            'recurring_warning_whatsapp_template' => 'nullable|string',
            'recurring_warning_email_subject'     => 'nullable|string|max:255',
            'recurring_warning_email_template'    => 'nullable|string',
            'recurring_warning_first_days'        => 'nullable|integer|min:1|max:365',
            'recurring_warning_second_days'       => 'nullable|integer|min:1|max:365',
            'recurring_warning_daily_days'        => 'nullable|integer|min:1|max:365',
        ]);

        $validated['recurring_warning_status'] = $request->has('recurring_warning_status') ? '1' : '0';

        $basic = \App\Models\BasicSetting::first();
        if ($basic) {
            $basic->update($validated);
        }

        \Illuminate\Support\Facades\Session::flash('success', __('Expiry alert notification format updated successfully!'));
        return back();
    }

    public function updateUsagePricing(\Illuminate\Http\Request $request)
    {
        static::ensureExpiryWarningColumnsExist();

        $validated = $request->validate([
            'usage_increase_threshold' => 'required|numeric|min:1|max:500',
            'usage_price_multiplier'   => 'required|numeric|min:1.0|max:10.0',
            'usage_baseline_orders'    => 'required|integer|min:1',
            'usage_baseline_products'  => 'required|integer|min:1',
        ]);

        $validated['usage_pricing_status'] = $request->has('usage_pricing_status') ? '1' : '0';

        $basic = \App\Models\BasicSetting::first();
        if ($basic) {
            $basic->update($validated);
        }

        \Illuminate\Support\Facades\Session::flash('success', __('Usage-based dynamic renewal pricing rules saved successfully!'));
        return back();
    }

    public function sendExpiryAlert(\Illuminate\Http\Request $request, $id)
    {
        static::ensureExpiryWarningColumnsExist();

        $user = \App\Models\User::findOrFail($id);
        $channel = $request->input('channel', 'both');

        $basic = \App\Models\BasicSetting::first();
        $membership = $user->memberships()->where('status', '<>', 2)->orderBy('id', 'DESC')->first();
        if (!$membership) {
            session()->flash('warning', __('No active subscription found for this user.'));
            return back();
        }

        $pricing = static::calculateRenewalPricing($user, $membership, $basic);
        $currSign = $pricing['currency_symbol'];
        $basePriceFormatted = $currSign . number_format($pricing['base_price'], 2);
        $renewalPriceFormatted = $currSign . number_format($pricing['renewal_price'], 2);

        $package = $membership->package;
        $planName = $package ? $package->title : 'Store Plan';
        $expireDateObj = \Carbon\Carbon::parse($membership->expire_date);
        $daysLeft = \Carbon\Carbon::now()->diffInDays($expireDateObj, false);
        $daysLeftStr = $daysLeft > 0 ? "{$daysLeft} days" : ($daysLeft == 0 ? "Today" : abs($daysLeft) . " days ago (Expired)");
        $surgeBadge = $pricing['is_surge'] ? " (Usage surge: +{$pricing['increase_percent']}%)" : "";
        $usageSummary = "{$pricing['order_count']} orders, {$pricing['product_count']} products";

        $host = request()->getHost();
        $cleanHost = str_replace('www.', '', $host);
        $renewalUrl = 'https://' . $cleanHost . '/membership/checkout';

        // 1. WhatsApp Dispatch
        $waSent = false;
        if (in_array($channel, ['whatsapp', 'both']) && !empty($user->phone)) {
            $waTemplate = $basic->recurring_warning_whatsapp_template ?: "⚠️ *LaunchShop Subscription Expiry Notice*\n\nHello *{user_name}*,\n\nYour *LaunchShop* store (*{store_name}*) subscription for plan *{plan_name}* will expire in *{days_left}* on *{expire_date}*.\n\n📊 *Usage Details:* {usage_summary}\n💰 *Base Package Price:* {base_price}\n🏷️ *Renewal Amount:* *{renewal_price}*{surge_badge}\n\nTo prevent storefront downtime and retain all your store settings, renew your subscription now:\n🔗 *Renew Here:*\n{renewal_url}\n\nNeed assistance? Reply to this message anytime.\n– Team LaunchShop 🚀";

            $waMsg = str_replace(
                ['{user_name}', '{store_name}', '{plan_name}', '{product_name}', '{expiry_date}', '{expire_date}', '{days_left}', '{base_price}', '{renewal_price}', '{surge_badge}', '{usage_summary}', '{renewal_url}'],
                [$user->first_name ?: $user->username, $user->shop_name ?: $user->username, $planName, 'LaunchShop', $expireDateObj->format('d M Y'), $expireDateObj->format('d M Y'), $daysLeftStr, $basePriceFormatted, $renewalPriceFormatted, $surgeBadge, $usageSummary, $renewalUrl],
                $waTemplate
            );

            $formattedPhone = \App\Services\WhatsAppNotificationService::formatNumber($user->phone);
            if (!empty($formattedPhone)) {
                try {
                    \App\Services\WhatsAppNotificationService::sendMessage($formattedPhone, $waMsg);
                    $waSent = true;
                } catch (\Throwable $e) {}

                try {
                    \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => 'Bearer a09a0ee3aae408f843020cbd6bccf590',
                        'Content-Type'  => 'application/json',
                    ])->withoutVerifying()->post('https://app.metamerged.com/api/send', [
                        'number'  => $formattedPhone,
                        'type'    => 'text',
                        'message' => $waMsg,
                    ]);
                    $waSent = true;
                } catch (\Throwable $e) {}
            }
        }

        // 2. Email Dispatch
        $emailSent = false;
        if (in_array($channel, ['email', 'both']) && !empty($user->email)) {
            $emailSubject = $basic->recurring_warning_email_subject ?: 'Subscription Expiry Notice: {store_name} ({plan_name})';
            $emailSubject = str_replace(
                ['{user_name}', '{store_name}', '{plan_name}', '{expire_date}', '{renewal_price}'],
                [$user->first_name ?: $user->username, $user->shop_name ?: $user->username, $planName, $expireDateObj->format('d M Y'), $renewalPriceFormatted],
                $emailSubject
            );

            $emailBodyTemplate = $basic->recurring_warning_email_template ?: "<p>Hello {user_name},</p><p>Your subscription for store <strong>{store_name}</strong> ({plan_name}) will expire on <strong>{expire_date}</strong> (<strong>{days_left}</strong>).</p><p><strong>Store Usage Summary:</strong> {usage_summary}</p><p><strong>Base Plan Price:</strong> {base_price}<br><strong>Renewal Price:</strong> <span style='font-size: 18px; color: #059669; font-weight: bold;'>{renewal_price}</span> {surge_badge}</p><p><a href='{renewal_url}' style='background-color:#059669;color:#ffffff;padding:12px 24px;border-radius:6px;text-decoration:none;display:inline-block;font-weight:bold;'>Renew Subscription Now</a></p><p>Best regards,<br>LaunchShop Team</p>";

            $emailBody = str_replace(
                ['{user_name}', '{store_name}', '{plan_name}', '{expire_date}', '{days_left}', '{base_price}', '{renewal_price}', '{surge_badge}', '{usage_summary}', '{renewal_url}'],
                [$user->first_name ?: $user->username, $user->shop_name ?: $user->username, $planName, $expireDateObj->format('d M Y'), $daysLeftStr, $basePriceFormatted, $renewalPriceFormatted, $surgeBadge, $usageSummary, $renewalUrl],
                $emailBodyTemplate
            );

            $mailData = [
                'recipient' => $user->email,
                'subject'   => $emailSubject,
                'body'      => $emailBody,
                'from_name' => 'LaunchShop Admin',
            ];
            try {
                \App\Http\Helpers\BasicMailer::sendMail($mailData);
                $emailSent = true;
            } catch (\Throwable $e) {}
        }

        \Illuminate\Support\Facades\Session::flash('success', __('Expiry alert dispatched successfully! (WhatsApp: :wa, Email: :email)', [
            'wa'    => $waSent ? 'Delivered' : 'Skipped/Failed',
            'email' => $emailSent ? 'Delivered' : 'Skipped/Failed'
        ]));
        return back();
    }

    public function bulkSendExpiryAlert(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('user_ids', []);
        if (empty($ids) || !is_array($ids)) {
            session()->flash('warning', __('Please select at least one merchant to send expiry alert.'));
            return back();
        }

        $count = 0;
        foreach ($ids as $id) {
            try {
                $subRequest = new \Illuminate\Http\Request(['channel' => 'both']);
                $this->sendExpiryAlert($subRequest, $id);
                $count++;
            } catch (\Throwable $e) {}
        }

        session()->flash('success', __(':count expiry alerts queued/sent successfully with dynamic pricing!', ['count' => $count]));
        return back();
    }
}