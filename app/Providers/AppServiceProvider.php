<?php

namespace App\Providers;

use App\Http\Helpers\UserPermissionHelper;
use App\Models\BasicExtended;
use App\Models\CustomerWishList;
use App\Models\User\UserContact;
use App\Models\User\UserCurrency;
use App\Models\User\UserFooter;
use App\Models\User\UserHeader;
use App\Models\User\UserItemCategory;
use App\Models\User\UserMenu;
use App\Models\User\UserUlink;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\Social;
use App\Models\Language;
use App\Models\User\Language as UserLanguage;
use App\Models\Menu;
use App\Models\User\BasicSetting;
use App\Models\User\BasicExtende;
use App\Models\User\SEO;
use App\Models\User\UserPermission;
use App\Models\User\UserShopSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\SimpleSoftwareIO\QrCode\Generator::class, function () {
            return new \App\Libraries\QrCodeGenerator();
        });

        $this->app->bind('qrcode', function () {
            return new \App\Libraries\QrCodeGenerator();
        });

        $this->app->singleton('user', function () {
            $user = getUser();
            if (!empty($user) && is_object($user)) {
                return $user;
            }

            $fallback = new User();
            $fallback->id = 0;
            $fallback->username = 'guest';
            $fallback->email = '';
            $fallback->phone = '';
            $fallback->whatsapp_status = 0;
            $fallback->preview_template = 0;
            $fallback->status = 1;
            return $fallback;
        });

        //user front current langauge
        $this->app->singleton('userCurrentLang', function () {
            $user = app('user');
            if (!empty($user) && is_object($user) && !empty($user->id)) {
                if (session()->has('user_lang_' . $user->username)) {
                    $userCurrentLang = UserLanguage::where('code', session()->get('user_lang_' . $user->username))->where('user_id', $user->id)->first();
                    if (empty($userCurrentLang)) {
                        $userCurrentLang = UserLanguage::where('code', 'en')->where('user_id', $user->id)->first();
                        if (empty($userCurrentLang)) {
                            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                        }
                        if (empty($userCurrentLang)) {
                            $userCurrentLang = UserLanguage::where('user_id', $user->id)->orderBy('id', 'asc')->first();
                        }

                        if (!empty($userCurrentLang)) {
                            session()->put('user_lang_' . $user->username, $userCurrentLang->code);
                        }
                    }
                } else {
                    $userCurrentLang = UserLanguage::where('code', 'en')->where('is_default', 1)->where('user_id', $user->id)->first();
                    if (empty($userCurrentLang)) {
                        $userCurrentLang = UserLanguage::where('code', 'en')->where('user_id', $user->id)->first();
                    }
                    if (empty($userCurrentLang)) {
                        $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                    }
                    if (empty($userCurrentLang)) {
                        $userCurrentLang = UserLanguage::where('user_id', $user->id)->orderBy('id', 'asc')->first();
                    }

                    if (!empty($userCurrentLang)) {
                        session()->put('user_lang_' . $user->username, $userCurrentLang->code);
                    }
                }
                if (!empty($userCurrentLang)) {
                    return $userCurrentLang;
                }
            }

            return null;
        });

        //user basic-settings
        $this->app->singleton('userBs', function () {
            $user = app('user');
            if (!empty($user) && is_object($user) && !empty($user->id)) {
                $userBs = BasicSetting::where('user_id', $user->id)->first();
                if ($userBs && app()->bound('theme.service')) {
                    $userBs->theme = app('theme.service')->getActiveTheme();
                }
                if (!empty($userBs)) {
                    return $userBs;
                }
            }

            $fallback = new BasicSetting();
            $fallback->theme = 'grocery';
            $fallback->logo = 'logo.png';
            $fallback->favicon = 'favicon.png';
            $fallback->website_title = 'Store';
            $fallback->base_currency_symbol = '₹';
            $fallback->base_currency_symbol_position = 'left';
            $fallback->base_currency_text = 'INR';
            $fallback->base_currency_text_position = 'left';
            return $fallback;
        });

        //user theme-service
        $this->app->singleton('theme.service', function () {
            return new \App\Services\ThemeService();
        });

        //user basic-extend
        $this->app->singleton('userBe', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            if (!empty($user) && is_object($user) && !empty($userCurrentLang) && isset($userCurrentLang->id)) {
                $userBe = BasicExtende::where([
                    ['user_id', $user->id],
                    ['language_id', $userCurrentLang->id]
                ])->first();
                if (!empty($userBe)) {
                    return $userBe;
                }
            }

            $fallback = new BasicExtende();
            $fallback->base_currency_symbol = '₹';
            $fallback->base_currency_symbol_position = 'left';
            $fallback->base_currency_text = 'INR';
            $fallback->base_currency_text_position = 'left';
            return $fallback;
        });

        //user item-categories
        $this->app->singleton('categories', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            if (empty($user) || !is_object($user) || empty($userCurrentLang)) {
                return collect([]);
            }
            $categories = UserItemCategory::with([
                'subcategories' => function ($query) {
                    return $query->where('status', 1);
                }
            ])->where('language_id', $userCurrentLang->id)
                ->where([['user_id', $user->id], ['status', 1]])
                ->orderBy('serial_number', 'ASC')
                ->get();
            if ($categories->isEmpty()) {
                $enLangId = UserLanguage::where('user_id', $user->id)->where('code', 'en')->value('id') ?? $userCurrentLang->id;
                $categories = UserItemCategory::with([
                    'subcategories' => function ($query) {
                        return $query->where('status', 1);
                    }
                ])->where([['user_id', $user->id], ['status', 1]])
                    ->where('language_id', $enLangId)
                    ->orderBy('serial_number', 'ASC')
                    ->get();
                if ($categories->isEmpty()) {
                    $categories = UserItemCategory::with([
                        'subcategories' => function ($query) {
                            return $query->where('status', 1);
                        }
                    ])->where([['user_id', $user->id], ['status', 1]])
                        ->orderBy('serial_number', 'ASC')
                        ->get();
                }
            }

            return $categories;
        });
        //user header-content
        $this->app->singleton('header', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            if (!empty($user) && is_object($user) && !empty($userCurrentLang) && isset($userCurrentLang->id)) {
                $header = UserHeader::where('language_id', $userCurrentLang->id)
                    ->where('user_id', $user->id)
                    ->first();
                if (!empty($header)) {
                    return $header;
                }
                $enLangId = UserLanguage::where('user_id', $user->id)->where('code', 'en')->value('id') ?? $userCurrentLang->id;
                $header = UserHeader::where('language_id', $enLangId)->where('user_id', $user->id)->first();
                if (!empty($header)) {
                    return $header;
                }
                $header = UserHeader::where('user_id', $user->id)->first();
                if (!empty($header)) {
                    return $header;
                }
            }
            return new UserHeader();
        });
        //user usefull links
        $this->app->singleton('ulinks', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            if (empty($user) || !is_object($user) || empty($userCurrentLang)) {
                return collect([]);
            }
            $ulinks = UserUlink::where('language_id', $userCurrentLang->id)
                ->where('user_id', $user->id)
                ->get();
            if ($ulinks->isEmpty()) {
                $enLangId = UserLanguage::where('user_id', $user->id)->where('code', 'en')->value('id') ?? $userCurrentLang->id;
                $ulinks = UserUlink::where('language_id', $enLangId)->where('user_id', $user->id)->get();
            }
            if (!empty($userCurrentLang) && $userCurrentLang->code === 'en') {
                $ulinks = $ulinks->reject(function ($link) {
                    return (bool) preg_match('/\p{Arabic}/u', $link->name ?? '');
                });
            }
            return $ulinks;
        });
        //user footer content
        $this->app->singleton('footer', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            if (!empty($user) && is_object($user) && !empty($userCurrentLang) && isset($userCurrentLang->id)) {
                $footer = UserFooter::where('language_id', $userCurrentLang->id)
                    ->where('user_id', $user->id)
                    ->first();
                if (!empty($footer)) {
                    if (!empty($userCurrentLang) && $userCurrentLang->code === 'en' && preg_match('/\p{Arabic}/u', ($footer->footer_text ?? '') . ($footer->useful_links_title ?? ''))) {
                        $footer = null;
                    } else {
                        return $footer;
                    }
                }
                $enLangId = UserLanguage::where('user_id', $user->id)->where('code', 'en')->value('id') ?? $userCurrentLang->id;
                $footer = UserFooter::where('language_id', $enLangId)->where('user_id', $user->id)->first();
                if (!empty($footer)) {
                    if (!empty($userCurrentLang) && $userCurrentLang->code === 'en' && preg_match('/\p{Arabic}/u', ($footer->footer_text ?? '') . ($footer->useful_links_title ?? ''))) {
                        $footer = null;
                    } else {
                        return $footer;
                    }
                }
            }
            return new UserFooter();
        });
        //user currency
        $this->app->singleton('userCurrency', function () {
            $user = app('user');
            if (empty($user) || !is_object($user) || empty($user->id)) {
                return collect([]);
            }
            $userCurrency = UserCurrency::where('user_id', $user->id)
                ->get();
            if ($userCurrency->isEmpty()) {
                $fallback = new UserCurrency();
                $fallback->id = 999999;
                $fallback->user_id = $user->id;
                $fallback->is_default = 1;
                $fallback->symbol = '₹';
                $fallback->text = 'INR';
                $fallback->value = 1;
                $fallback->symbol_position = 'left';
                $userCurrency = collect([$fallback]);
            }
            return $userCurrency;
        });
        //user languages
        $this->app->singleton('userLangs', function () {
            $user = app('user');
            if (empty($user) || !is_object($user) || empty($user->id)) {
                return collect([]);
            }
            $userLangs = UserLanguage::where('user_id', $user->id)->get();
            return $userLangs;
        });
        //user Contact info
        $this->app->singleton('userContact', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            if (!empty($user) && is_object($user) && !empty($userCurrentLang) && isset($userCurrentLang->id)) {
                $userContact = UserContact::where([
                    ['user_id', $user->id],
                    ['language_id', $userCurrentLang->id]
                ])->first();
                if (!empty($userContact)) {
                    return $userContact;
                }
            }
            return new UserContact();
        });
        //user shoping settings
        $this->app->singleton('shop_settings', function () {
            $user = app('user');
            if (!empty($user) && is_object($user) && isset($user->id)) {
                $shop_settings = UserShopSetting::where('user_id', $user->id)->first();
                if (!$shop_settings) {
                    try {
                        $shop_settings = UserShopSetting::firstOrCreate([
                            'user_id' => $user->id
                        ], [
                            'catalog_mode' => 0,
                            'item_rating_system' => 1,
                            'top_rated_count' => 5,
                            'top_selling_count' => 5
                        ]);
                    } catch (\Throwable $e) {
                        $shop_settings = new UserShopSetting();
                        $shop_settings->catalog_mode = 0;
                        $shop_settings->item_rating_system = 1;
                        $shop_settings->top_rated_count = 5;
                        $shop_settings->top_selling_count = 5;
                    }
                }
                return $shop_settings;
            }

            $defaultSettings = new UserShopSetting();
            $defaultSettings->catalog_mode = 0;
            $defaultSettings->item_rating_system = 1;
            $defaultSettings->top_rated_count = 5;
            $defaultSettings->top_selling_count = 5;
            return $defaultSettings;
        });
        //user social_medias
        $this->app->singleton('social_medias', function () {
            $user = app('user');
            if (empty($user) || !is_object($user) || empty($user->id)) {
                return collect([]);
            }
            $social_medias = $user->social_media()->get() ?? collect([]);
            return $social_medias;
        });

        //admin all languages
        $this->app->singleton('langs', function () {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('languages')) {
                    return Language::all();
                }
            } catch (\Throwable $e) {}
            return collect([]);
        });
        //admin front current language
        $this->app->singleton('currentLang', function () {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('languages')) {
                    if (session()->has('lang')) {
                        $currentLang = Language::where('code', session()->get('lang'))->first();
                    } else {
                        $currentLang = Language::where('is_default', 1)->first();
                    }
                    if ($currentLang) return $currentLang;
                }
            } catch (\Throwable $e) {}
            $fallback = new Language();
            $fallback->id = 1;
            $fallback->code = 'en';
            $fallback->name = 'English';
            $fallback->is_default = 1;
            $fallback->rtl = 0;
            return $fallback;
        });
        //selected currency for currency converter helper
        $this->app->singleton('userCurrentCurr', function () {
            if (Session::has('myfatoorah_user')) {
                $user = Session::get('myfatoorah_user');
            } else {
                $user = app('user');
            }

            if (empty($user) || !is_object($user) || empty($user->id)) {
                // Return a safe fallback so blade templates never crash on null
                $fallback = new UserCurrency();
                $fallback->id = 0;
                $fallback->user_id = 0;
                $fallback->is_default = 1;
                $fallback->symbol = '₹';
                $fallback->text = 'INR';
                $fallback->value = 1;
                $fallback->symbol_position = 'left';
                return $fallback;
            }


            if (session()->has('user_curr_' . $user->username)) {
                $userCurrentCurr = UserCurrency::where('id', session()->get('user_curr_' . $user->username))->first();

                if (empty($userCurrentCurr)) {
                    $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
                    if (empty($userCurrentCurr)) {
                        $userCurrentCurr = UserCurrency::where('user_id', $user->id)->orderBy('id', 'asc')->first();
                    }

                    if (!empty($userCurrentCurr)) {
                        session()->put('user_curr_' . $user->username, $userCurrentCurr->id);
                    }
                }
            } else {
                $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
                if (empty($userCurrentCurr)) {
                    $userCurrentCurr = UserCurrency::where('user_id', $user->id)->orderBy('id', 'asc')->first();
                }

                if (!empty($userCurrentCurr)) {
                    session()->put('user_curr_' . $user->username, $userCurrentCurr->id);
                }
            }

            if (empty($userCurrentCurr)) {
                $fallback = new UserCurrency();
                $fallback->id = 999999;
                $fallback->user_id = $user->id;
                $fallback->is_default = 1;
                $fallback->symbol = '₹';
                $fallback->text = 'INR';
                $fallback->value = 1;
                $fallback->symbol_position = 'left';
                $userCurrentCurr = $fallback;
            }

            session()->put('user_curr_' . $user->username, $userCurrentCurr->id);
            session()->put('user_curr_sign_' . $user->username, $userCurrentCurr->symbol);

            return $userCurrentCurr;
        });
        //selected currency for currency converter helper
        $this->app->singleton('userDefaultCurrency', function () {
            if (Session::has('myfatoorah_user')) {
                $user = Session::get('myfatoorah_user');
            } else {
                $user = app('user');
            }

            $userDefaultCurrency = UserCurrency::where('is_default', 1)
                ->where('user_id', $user->id)
                ->first();

            //if currency is not set as default then set the first currency as default
            if (is_null($userDefaultCurrency)) {
                $userDefaultCurrency = UserCurrency::where('user_id', $user->id)->first();

                if ($userDefaultCurrency) {
                    $userDefaultCurrency->update(['is_default' => 1]);
                }
            }

            if (empty($userDefaultCurrency)) {
                $fallback = new UserCurrency();
                $fallback->id = 999999;
                $fallback->user_id = $user->id;
                $fallback->is_default = 1;
                $fallback->symbol = '₹';
                $fallback->text = 'INR';
                $fallback->value = 1;
                $fallback->symbol_position = 'left';
                $userDefaultCurrency = $fallback;
            }

            return $userDefaultCurrency;
        });
    }

    public function changePreferences($userId)
    {
        $currentPackage = UserPermissionHelper::currentPackagePermission($userId);

        $preference = UserPermission::where([
            ['user_id', $userId]
        ])->first();

        // if current package does not match with 'package_id' of 'user_permissions' table, then change 'package_id' in 'user_permissions'
        if (!empty($currentPackage) && ($currentPackage->id != $preference->package_id)) {
            $preference->package_id = $currentPackage->id;

            $features = !empty($currentPackage->features) ? json_decode($currentPackage->features, true) : [];
            $features[] = "Contact";
            $features[] = "Footer Mail";
            $features[] = "Profile Listing";
            $preference->permissions = json_encode($features);
            $preference->package_id = $currentPackage->id;
            $preference->save();
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (request()->secure() || request()->header('X-Forwarded-Proto') === 'https' || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || request()->server('HTTP_X_FORWARDED_PROTO') === 'https' || env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            $be = BasicExtended::first();
            $tz = (!empty($be) && !empty($be->timezone)) ? $be->timezone : 'Asia/Kolkata';
            date_default_timezone_set($tz);
            config(['app.timezone' => $tz]);
        } catch (\Exception $e) {
            date_default_timezone_set('Asia/Kolkata');
            config(['app.timezone' => 'Asia/Kolkata']);
        }

        User::created(function ($user) {
            if (app()->runningInConsole()) {
                return;
            }

            // skip if this is a template user
            if (!empty($user->template_serial_number) && (int) $user->template_serial_number === 2) {
                return;
            }

                // Seeding happens later when the tenant theme/template is known.
        });

        Paginator::useBootstrap();

        if (!app()->runningInConsole()) {
            try {
                $socials = \Illuminate\Support\Facades\Schema::hasTable('socials') ? Social::orderBy('serial_number', 'ASC')->get() : collect([]);
            } catch (\Throwable $e) {
                $socials = collect([]);
            }
            $langs = app('langs');

            View::composer('*', function ($view) {
                $currentLang = app('currentLang');
                $bs = null;
                $be = null;
                if (is_object($currentLang)) {
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('basic_settings')) {
                            $bs = $currentLang->basic_setting;
                        }
                    } catch (\Throwable $e) {
                        $bs = null;
                    }
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('basic_extendeds')) {
                            $be = $currentLang->basic_extended;
                        }
                    } catch (\Throwable $e) {
                        $be = null;
                    }
                }

                if (!$bs) {
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('basic_settings')) {
                            $bs = DB::table('basic_settings')->first();
                        }
                    } catch (\Throwable $e) {
                        $bs = null;
                    }
                }

                if (!$bs) {
                    $bs = (object)[
                        'website_title'                     => 'LaunchShop',
                        'favicon'                           => 'favicon.png',
                        'logo'                              => 'logo.png',
                        'feature_section'                   => 1,
                        'process_section'                   => 1,
                        'templates_section'                 => 1,
                        'additional_section_status'         => json_encode([]),
                        'about_additional_section_status'   => json_encode([]),
                        'time_format'                       => '12',
                        'maintenance_img'                   => 'maintenance.png',
                        'theme'                             => 'default',
                        'base_color'                        => '007bff',
                    ];
                }

                if (!$be) {
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('basic_extendeds')) {
                            $be = DB::table('basic_extendeds')->first();
                        }
                    } catch (\Throwable $e) {
                        $be = null;
                    }
                }

                if (!$be) {
                    $be = (object)[
                        'base_currency_symbol'              => '₹',
                        'base_currency_symbol_position'     => 'left',
                        'base_currency_text'                => 'INR',
                        'base_currency_rate'                => 1,
                        'hero_section_title'                => '',
                        'hero_section_text'                 => '',
                        'cookie_alert_status'               => 0,
                        'cookie_alert_text'                 => '',
                        'cookie_alert_button_text'          => '',
                        'contact_addresses'                 => '',
                        'contact_numbers'                   => '7200770351',
                        'contact_mails'                     => '',
                        'package_features'                  => json_encode([]),
                        'cname_record_section_title'        => '',
                        'cname_record_section_text'         => '',
                        'testimonial_img'                   => '',
                        'default_language_direction'        => 'ltr',
                        'is_smtp'                           => 0,
                        'smtp_host'                         => '',
                        'smtp_port'                         => '',
                        'encryption'                        => '',
                        'smtp_username'                     => '',
                        'smtp_password'                     => '',
                        'from_mail'                         => '',
                    ];
                }

                $view->with('bs', $bs);
                $view->with('be', $be);
                $view->with('currentLang', $currentLang);
            });

            View::composer(['front.*'], function ($view) {
                $currentLang = app('currentLang');
                $menus = json_encode([]);
                $rtl = 0;
                if ($currentLang && is_object($currentLang) && isset($currentLang->id)) {
                    $menuObj = Menu::where('language_id', $currentLang->id)->first();
                    if ($menuObj && !empty($menuObj->menus)) {
                        $menus = $menuObj->menus;
                    }
                    if (isset($currentLang->rtl) && $currentLang->rtl == 1) {
                        $rtl = 1;
                    }
                }

                $decodedMenus = !empty($menus) ? json_decode($menus, true) : [];
                if (empty($decodedMenus)) {
                    $defaultLangObj = Language::where('is_default', 1)->first() ?? Language::where('code', 'en')->first();
                    $defaultMenuObj = $defaultLangObj ? Menu::where('language_id', $defaultLangObj->id)->first() : null;

                    if ($defaultMenuObj && !empty($defaultMenuObj->menus)) {
                        $menus = $defaultMenuObj->menus;
                    } else {
                        $defaultNav = [
                            ["text" => "Home", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "home"],
                            ["text" => "Store Themes", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "pricing"],
                            ["text" => "Pricing", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "pricing"],
                            ["text" => "Blog", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "blog"],
                            ["text" => "FAQ", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "faq"],
                            ["text" => "Contact", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "contact"]
                        ];
                        $menus = json_encode($defaultNav);
                    }
                }

                $view->with('menus', $menus);
                $view->with('rtl', $rtl);
            });

            View::composer(['user.*'], function ($view) {
                if (Auth::check()) {
                    $userId = Auth::user()->id;
                    // change package_id in 'user_permissions'
                    $this->changePreferences($userId);
                    $userBs = DB::table('user_basic_settings')->where('user_id', $userId)->first();
                  
                    $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($userId);
                    if (!empty($package)) {
                        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($userId);
                        $permissions = json_decode($permissions, true);
                        $view->with(['permissions' => $permissions]);
                    }

                    //for translate tenant dashboard start
                    $cookieName = 'userDashboardLang_' . $userId;
                    $userDashboardLang = null;
                    if (Cookie::has($cookieName)) {
                        $isLang = UserLanguage::where([['code', Cookie::get($cookieName)], ['user_id', Auth::guard('web')->user()->id]])->exists();

                        if ($isLang == true) {
                            $userDashboardLang = UserLanguage::where('code', Cookie::get($cookieName))
                                ->where('user_id', $userId)
                                ->first();
                        } else {
                            $userDashboardLang = UserLanguage::where([['dashboard_default', 1], ['user_id', Auth::guard('web')->user()->id]])->first();
                            // Set all to 0 first
                            UserLanguage::where('user_id', $userId)->update(['dashboard_default' => 0]);

                            // Then set the default one to 1
                            UserLanguage::where([
                                ['user_id', $userId],
                                ['is_default', 1]
                            ])->update(['dashboard_default' => 1]);
                            if ($userDashboardLang) {
                                Cookie::queue('userDashboardLang', $userDashboardLang->code, 60 * 24 * 30);
                            }
                        }
                    } else {
                        $userDashboardLang = UserLanguage::where('dashboard_default', 1)
                            ->where('user_id', $userId)
                            ->first();
                    }

                    if (empty($userDashboardLang)) {
                        $userDashboardLang = UserLanguage::where('code', 'en')->where('user_id', $userId)->first();
                    }
                    if (empty($userDashboardLang)) {
                        $userDashboardLang = UserLanguage::where('is_default', 1)->where('user_id', $userId)->first();
                    }
                    if (empty($userDashboardLang)) {
                        $userDashboardLang = UserLanguage::where('user_id', $userId)->first();
                    }
                    if (empty($userDashboardLang)) {
                        $userDashboardLang = new UserLanguage();
                        $userDashboardLang->id = 0;
                        $userDashboardLang->user_id = $userId;
                        $userDashboardLang->name = 'English';
                        $userDashboardLang->code = 'en';
                        $userDashboardLang->is_default = 1;
                        $userDashboardLang->dashboard_default = 1;
                        $userDashboardLang->rtl = 0;
                    }

                    if ($userDashboardLang && isset($userDashboardLang->code)) {
                        Session::put('user_lang', 'user_' . $userDashboardLang->code);
                        app()->setLocale('user_' . $userDashboardLang->code);
                    }

                    $uLang = null;
                    if ($userDashboardLang && isset($userDashboardLang->code)) {
                        $uLang = Language::where('code', $userDashboardLang->code)->first();
                    }
                    if (is_null($uLang)) {
                        $uLang = Language::where('is_default', 1)->first() ?? Language::first();
                    }

                    $shopSetting = UserShopSetting::where('user_id', $userId)->select('time_format')->first();

                    $be = null;
                    if ($uLang && isset($uLang->id)) {
                        $be = BasicExtended::where('language_id', $uLang->id)->select('package_features', 'cname_record_section_text', 'cname_record_section_title')->first();
                    }
                    if (!$be) {
                        $be = BasicExtended::first();
                    }
                    
                    $adminLngLanguage = null;
                    if ($userDashboardLang && isset($userDashboardLang->code)) {
                        $adminLngLanguage = Language::where('code', $userDashboardLang->code)->first();
                    }
                    if (is_null($adminLngLanguage)) {
                        $adminLngLanguage = Language::where('dashboard_default', 1)->first() ?? Language::first();
                    }
                    $bss = is_object($adminLngLanguage) ? $adminLngLanguage->basic_setting : null;

                    $view->with([
                        'userBs' => $userBs,
                        'bs' => $bss,
                        'dashboard_language' => $userDashboardLang,
                        'defaultLang' => is_object($userDashboardLang) ? $userDashboardLang->code : 'en',
                        'shopSetting' => $shopSetting,
                        'package_features' => $be ? $be->package_features : json_encode([]),
                        'package' => $package,
                        'cname_record_section_text' => $be ? $be->cname_record_section_text : '',
                        'cname_record_section_title' => $be ? $be->cname_record_section_title : ''
                    ]);
                }
            });
            View::composer(['admin.*'], function ($view) {
                if (session()->has('admin_lang')) {
                    $lang_code = str_replace('admin_', '', session()->get('admin_lang'));
                    $language = Language::where('code', $lang_code)->first();
                    if (empty($language)) {
                        $language = Language::where('dashboard_default', 1)->first();
                    }
                } else {
                    $language = Language::where('dashboard_default', 1)->first();
                }
                if (!$language) {
                    $language = Language::first() ?? (object)['code' => 'en', 'rtl' => 0];
                }
                if (is_object($language) && isset($language->code)) {
                    app()->setLocale('admin_' . $language->code);
                }
                $bss = is_object($language) && isset($language->basic_setting) ? $language->basic_setting : null;
                $bs  = $bss ?? (DB::table('basic_settings')->first() ?? (object)['website_title' => 'LaunchShop Admin', 'favicon' => 'favicon.png', 'logo' => 'logo.png']);
                View::share(['default' => $language, 'bss' => $bss, 'bs' => $bs]);
            });

            View::composer(['user-front.*'], function ($view) {
                $user = app('user');
                if (empty($user) || !is_object($user) || empty($user->id)) {
                    // Share safe non-null defaults so blade templates never crash with "Attempt to read property on null"
                    $view->with('userLangs', app('userLangs') ?? collect([]));
                    $view->with('userCurrentLang', app('userCurrentLang'));
                    $view->with('keywords', []);
                    $view->with('userMenus', json_encode([]));
                    $view->with('userCurrency', app('userCurrency') ?? collect([]));
                    $view->with('social_medias', app('social_medias') ?? collect([]));
                    $view->with('userCurrentCurr', app('userCurrentCurr'));
                    $view->with('categories', app('categories') ?? collect([]));
                    $view->with('header', app('header'));
                    $view->with('footer', app('footer'));
                    $view->with('userBs', app('userBs'));
                    $view->with('userBe', app('userBe'));
                    $view->with('userContact', app('userContact'));
                    $view->with('ulinks', app('ulinks') ?? collect([]));
                    $view->with('wishListCount', 0);
                    $view->with('cartCount', 0);
                    $view->with('compareCount', 0);
                    $view->with('rtl', 0);
                    $view->with('user', null);
                    $view->with('packagePermissions', []);
                    $view->with('ubs', app('userBs'));
                    $view->with('shop_settings', app('shop_settings'));
                    return;
                }
                // change package_id in 'user_permissions'
                $this->changePreferences($user->id);

                $userCurrentLang = app('userCurrentLang');
                $keywords = (!empty($userCurrentLang) && !empty($userCurrentLang->keywords)) ? json_decode($userCurrentLang->keywords, true) : [];

                if (!empty($userCurrentLang) && isset($userCurrentLang->id) && UserMenu::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->count() > 0) {
                    $userMenus = UserMenu::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->first()->menus;
                } else {
                    $uMenu = UserMenu::where('user_id', $user->id)->first();
                    $userMenus = $uMenu ? $uMenu->menus : json_encode([]);
                }
                $userBs = app('userBs');
                $userBe = app('userBe');
                $userContact = app('userContact');
                $userCurrency = app('userCurrency');
                $userLangs = app('userLangs') ?? collect([]);
                $social_medias = app('social_medias') ?? collect([]);
                $userCurrentCurr = app('userCurrentCurr');

                $currSign = is_object($userCurrentCurr) ? ($userCurrentCurr->symbol ?? '₹') : '₹';
                $currId = is_object($userCurrentCurr) ? ($userCurrentCurr->id ?? 999999) : 999999;

                if (session()->has('user_curr_' . $user->username)) {
                    session()->put('user_curr_' . $user->username, session()->get('user_curr_' . $user->username));
                    session()->put('user_curr_sign_' . $user->username, $currSign);
                } else {
                    $defaultCurr = UserCurrency::where('user_id', $user->id)->where('is_default', 1)->first();
                    if (is_null($defaultCurr)) {
                        $defaultCurr = UserCurrency::where('user_id', $user->id)->first();
                    }

                    $defCurrId = $defaultCurr ? $defaultCurr->id : $currId;
                    $defCurrSign = $defaultCurr ? $defaultCurr->symbol : $currSign;

                    session()->put('user_curr_' . $user->username, $defCurrId);
                    session()->put('user_curr_sign_' . $user->username, $defCurrSign);
                }

                $ulinks = app('ulinks') ?? collect([]);
                $header = app('header');
                $footer = app('footer');
                $categories = app('categories') ?? collect([]);
                $shop_settings = app('shop_settings');

                $packagePermissionsRaw = UserPermissionHelper::packagePermission($user->id);
                $packagePermissions = !empty($packagePermissionsRaw) ? json_decode($packagePermissionsRaw, true) : [];
                $ubs = app('userBs');

                $rtl = (!empty($userCurrentLang) && isset($userCurrentLang->rtl) && $userCurrentLang->rtl == 1) ? 1 : 0;

                $user_id = $user->id;
                $compareCount = 0;
                if (Session::get('compare')) {
                    $compare = Session::get('compare');
                    if (!is_null($compare) && is_array($compare)) {
                        $compare = array_filter($compare, function ($item) use ($user_id) {
                            return isset($item['user_id']) && $item['user_id'] == $user_id;
                        });
                    }
                    $compareCount = is_array($compare) || $compare instanceof \Countable ? count($compare) : 0;
                }

                if (!empty($user)) {
                    if (Auth::guard('customer')->check()) {
                        $wishListCount = CustomerWishList::where([['customer_id', Auth::guard('customer')->user()->id], ['user_id', $user->id]])
                            ->count();
                    } else {
                        $wishListCount = 0;
                    }
                } else {
                    $wishListCount = 0;
                }

                $cart = Session::get('cart_' . $user->username);

                $cartCount = 0;
                if ($cart) {
                    if (!is_null($cart) && is_array($cart)) {
                        $cart = array_filter($cart, function ($item) use ($user_id) {
                            return isset($item['user_id']) && $item['user_id'] == $user_id;
                        });
                    }
                    $cartCount = is_array($cart) || $cart instanceof \Countable ? count($cart) : 0;
                }

                $view->with('wishListCount', $wishListCount);
                $view->with('cartCount', $cartCount);
                $view->with('compareCount', $compareCount);
                $view->with('rtl', $rtl);
                $view->with('user', $user);
                $view->with('userBs', $userBs);
                $view->with('userBe', $userBe);
                $view->with('userContact', $userContact);
                $view->with('footer', $footer);
                $view->with('header', $header);
                $view->with('categories', $categories);
                $view->with('ulinks', $ulinks);
                $view->with('userMenus', $userMenus);
                $view->with('userCurrency', $userCurrency);
                $view->with('social_medias', $social_medias);
                $view->with('userCurrentLang', $userCurrentLang);
                $view->with('userLangs', $userLangs);
                $view->with('keywords', $keywords);
                $view->with('packagePermissions', $packagePermissions);
                $view->with('ubs', $ubs);
                $view->with('shop_settings', $shop_settings);
                $view->with('userCurrentCurr', $userCurrentCurr);
            });

            View::share('langs', $langs);
            View::share('socials', $socials);
        }
    }
}
