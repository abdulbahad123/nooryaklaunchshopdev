<?php

namespace App\Http\Controllers\UserFront;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\Common;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Banner;
use App\Models\User\BasicSetting;
use App\Models\User\Blog;
use App\Models\User\BlogCategory;
use App\Models\User\Faq;
use App\Models\User\HeroSlider;
use App\Models\User\Language as UserLanguage;
use App\Models\BasicExtended as BE;
use App\Models\User\BasicExtende;
use App\Models\User\AboutUs;
use App\Models\User\AboutUsFeatures;
use App\Models\User\AdditionalSection;
use App\Models\User\BlogContent;
use App\Models\User\CounterInformation;
use App\Models\User\CounterSection;
use App\Models\User\HowitWorkSection;
use App\Models\User\ProductHeroSlider;
use App\Models\User\SEO;
use App\Models\User\StaticHeroSection;
use App\Models\User\Tab;
use App\Models\User\Testimonial;
use App\Models\User\UserContact;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserItemCategory;
use App\Models\User\UserOrder;
use App\Models\User\UserOrderItem;
use App\Models\User\UserSection;
use App\Models\User\UserShopSetting;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class HomeController extends Controller
{
    public function userDetailView($domain)
    {
        $user = app('user');
        $userCurrentLang = app('userCurrentLang');

        if (empty($userCurrentLang)) {
            $userCurrentLang = UserLanguage::where('user_id', $user->id)->orderBy('id', 'asc')->first();
        }

        if (empty($userCurrentLang)) {
            abort(404);
        }

        $uLang = $userCurrentLang->id;
        $data['uLang'] = $userCurrentLang->id;
        $data['ubs'] = app('userBs');

        $data['sliders'] = HeroSlider::where('language_id', $userCurrentLang->id)
            ->where('user_id', $user->id)
            ->get();
        $data['hero_slider'] = BasicExtende::where('user_id', $user->id)
            ->where('language_id', $userCurrentLang->id)
            ->select('hero_section_background_image')
            ->first();

        $allow_how_it_work_section = [
            'fashion',
            'electronics',
            'vegetables',
            'manti',
            'pet',
            'skinflow',
            'jewellery',
            'clothing'
        ];
        if (in_array($data['ubs']->theme, $allow_how_it_work_section)) {
            $data['how_work_steps'] = HowitWorkSection::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->get();
        }


        if ($data['ubs']->theme == 'fashion') {
            $product_sliders = ProductHeroSlider::where('user_id', $user->id)->select('products')->first();
            $added_products = [];
            if ($product_sliders) {
                $added_products = json_decode($product_sliders->products, true);
                if (!is_array($added_products)) {
                    $added_products = [];
                }
            }

            $data['hero_product_sliders'] = DB::table('user_items')->where('user_items.user_id', $user->id)
                ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
                ->whereIn('user_items.id', $added_products)
                ->where('user_item_categories.status', '=', 1)
                ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.title', 'user_item_contents.slug', 'user_item_contents.summary')
                ->orderBy('user_items.id', 'DESC')
                ->where('user_item_contents.language_id', '=', $userCurrentLang->id)
                ->get();


            $data['banners'] = Banner::where('language_id', $userCurrentLang->id)
                ->where([['user_id', $user->id], ['position', 'middle']])
                ->orderBy('serial_number', 'asc')
                ->limit(3)
                ->get();
        } elseif ($data['ubs']->theme == 'pet' || $data['ubs']->theme == 'jewellery' || $data['ubs']->theme == 'clothing') {
            $data['static_hero_section'] = StaticHeroSection::where('language_id', $userCurrentLang->id)
                ->where('user_id', $user->id)
                ->first();
        } else {
            $data['hero_sliders'] = HeroSlider::where('language_id', $userCurrentLang->id)
                ->where('user_id', $user->id)
                ->orderBy('serial_number', 'asc')
                ->get();
        }
        $data['banners'] = Banner::where('language_id', $userCurrentLang->id)
            ->where('user_id', $user->id)
            ->orderBy('serial_number', 'asc')
            ->get();

        if ($data['ubs']->theme == 'manti') {
            $data['hero_banners'] = Banner::where('language_id', $userCurrentLang->id)
                ->where([['user_id', $user->id], ['position', 'hero_banner']])
                ->orderBy('serial_number', 'asc')
                ->get();
        }
        $shop_settings =  app('shop_settings');
        $shopSet = $shop_settings;
        $data['shopSet'] = $shopSet;

        $flash_query = DB::table('user_items')->where('user_items.user_id', $user->id)
            ->where('user_items.flash', 1)
            ->where('user_items.status', 1)
            ->join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->leftJoin('user_item_categories', function ($join) use ($userCurrentLang) {
                $join->on('user_item_contents.category_id', '=', 'user_item_categories.id')
                    ->where('user_item_categories.language_id', '=', $userCurrentLang->id);
            })
            ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.*', 'user_item_categories.name AS category', 'user_item_categories.slug AS category_slug')
            ->orderBy('user_items.id', 'DESC')
            ->where('user_item_contents.language_id', '=', $userCurrentLang->id);

        $data['flash_items'] = (clone $flash_query)->get();

        if ($data['flash_items']->isEmpty()) {
            $data['flash_items'] = DB::table('user_items')->where('user_items.user_id', $user->id)
                ->where('user_items.status', 1)
                ->join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                ->leftJoin('user_item_categories', function ($join) use ($userCurrentLang) {
                    $join->on('user_item_contents.category_id', '=', 'user_item_categories.id')
                        ->where('user_item_categories.language_id', '=', $userCurrentLang->id);
                })
                ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.*', 'user_item_categories.name AS category', 'user_item_categories.slug AS category_slug')
                ->orderBy('user_items.id', 'DESC')
                ->where('user_item_contents.language_id', '=', $userCurrentLang->id)
                ->take(3)
                ->get();
        }


        $data['keywords'] = json_decode($userCurrentLang->keywords, true);

        if ($data['ubs']->theme == 'electronics' || $data['ubs']->theme == 'kids' || $data['ubs']->theme == 'clothing' || $data['ubs']->theme == 'grocery2') {
            $data['latest_items'] = UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
                ->where('user_items.user_id', $user->id)
                ->where('user_items.status', 1)
                ->where('user_item_categories.status', 1)
                ->with(['itemContents' => function ($q) use ($uLang) {
                    $q->where('language_id', '=', $uLang);
                }, 'sliders'])
                ->orderBy('user_items.updated_at', 'DESC')
                ->select('user_items.*')
                ->distinct()
                ->take($data['ubs']->theme == 'electronics' ? 4 : 20)
                ->get();
        }

        $data['tabs'] = Tab::where('language_id', $userCurrentLang->id)
            ->where([['user_id', $user->id], ['status', 1]])
            ->orderBy('serial_number', 'ASC')
            ->get();

        $data['item_categories'] = UserItemCategory::where('language_id', $userCurrentLang->id)
            ->where([['user_id', $user->id], ['status', 1]])
            ->orderBy('serial_number', 'ASC')
            ->get();
        $data['featuredCategories'] = $data['item_categories']->where('is_feature', 1)->take(8);
        if ($data['featuredCategories']->isEmpty()) {
            $data['featuredCategories'] = $data['item_categories']->take(8);
        }

        if (in_array($data['ubs']->theme, ['manti', 'vegetables', 'grocery', 'grocery2', 'furniture', 'pet', 'skinflow', 'clothing'])) {
            $data['top_rated'] = UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
                ->where('user_items.status', 1)
                ->where('user_items.user_id', $user->id)
                ->where('user_item_categories.status', 1)
                ->with(['itemContents' => function ($q) use ($uLang) {
                    $q->where('language_id', '=', $uLang);
                }])
                ->where('user_items.rating', '>', 0)
                ->orderBy('user_items.rating', 'desc')
                ->take($shop_settings->top_rated_count)
                ->distinct()
                ->select('user_items.*')
                ->get();

            if ($data['top_rated']->isEmpty()) {
                $data['top_rated'] = UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                    ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
                    ->where('user_items.status', 1)
                    ->where('user_items.user_id', $user->id)
                    ->where('user_item_categories.status', 1)
                    ->with(['itemContents' => function ($q) use ($uLang) {
                        $q->where('language_id', '=', $uLang);
                    }])
                    ->orderBy('user_items.id', 'desc')
                    ->take($shop_settings->top_rated_count ?? 6)
                    ->distinct()
                    ->select('user_items.*')
                    ->get();
            }
        } else {
            $data['top_rated'] = collect();
        }

        if (in_array($data['ubs']->theme, ['vegetables', 'grocery', 'furniture', 'jewellery', 'clothing'])) {
            $data['top_selling'] = UserOrderItem::with(['item', 'item.itemContents' => function ($q) use ($uLang, $user) {
                $q->where('language_id', '=', $uLang);
            }])
                ->where('user_id', $user->id)
                ->groupBy(DB::raw('item_id'))
                ->take($shop_settings->top_selling_count)
                ->select(DB::raw('item_id, sum(qty) as quantity'))
                ->orderBy('quantity', 'desc')
                ->get();
        } else {
            $data['top_selling'] = collect();
        }

        $data['user'] = $user;
        $data['userSec'] = UserSection::where('user_id',  $user->id)->where('language_id', $userCurrentLang->id)->first();

        $data['seo'] = SEO::where('language_id', $uLang)->where('user_id', $user->id)
            ->select('home_meta_description', 'home_meta_keywords')
            ->first();
        $data['shop_settings'] = $shop_settings;

        //custom section
        $sections = [
            'hero_section',
            'category_section',
            'middle_banner_section',
            'flash_section',
            'featuers_section',
            'tab_section',
            'call_to_action_section',
            'top_rate_top_selling_section'
        ];
        if ($data['ubs']->theme == 'manti') {
            $sections[] = 'featured_section';
            $sections[] = 'call_to_action_section';
        }
        if ($data['ubs']->theme == 'fashion' || $data['ubs']->theme == 'kids' || $data['ubs']->theme == 'furniture') {
            $sections[] = 'video_section';
        }
        if ($data['ubs']->theme == 'electronics' || $data['ubs']->theme == 'kids') {
            $sections[] = 'category_product_section';
            $sections[] = 'latest_product_section';
        }
        if ($data['ubs']->theme == 'kids' || $data['ubs']->theme == 'furniture') {
            $sections[] = 'banners_section';
        }
        $allow_after_featured = ['pet', 'skinflow', 'jewellery', 'clothing'];
        if (in_array($data['ubs']->theme, $allow_after_featured)) {
            $sections[] = 'featured_section';
        }
        $allow_after_top_rated = ['skinflow', 'jewellery', 'clothing'];
        if (in_array($data['ubs']->theme, $allow_after_top_rated)) {
            $sections[] = 'top_rated_section';
            $sections[] = 'product_section';
        }
        if ($data['ubs']->theme == 'pet') {
            $sections[] = 'category_section';
            $sections[] = 'product_section';
            $sections[] = 'top_rated_section';
        }
        if ($data['ubs']->theme == 'jewellery') {
            $sections[] = 'top_selling_section';
        }

        $pageType = 'home';
        $allSections = AdditionalSection::whereIn('possition', $sections)
            ->where('page_type', $pageType)
            ->orderBy('serial_number', 'asc')
            ->get()
            ->groupBy('possition');
        foreach ($sections as $section) {
            $data["after_" . str_replace('_section', '', $section)] = $allSections->get($section, collect());
        }

        return themeView('index', $data);
    }

    public function checkCurrentUser() {}

    public function changeUserLanguage($domain, $code): \Illuminate\Http\RedirectResponse
    {
        $user = getUser();
        session()->put('user_lang_' . $user->username, $code);
        return redirect()->back();
    }

    public function changeUserCurrency($domain, $id): \Illuminate\Http\RedirectResponse
    {
        $user = app('user');
        $previous_currency = session()->get('user_curr_' . $user->username);
        $carts = session()->get('cart_' . $user->username);
        if (!empty($carts)) {
            foreach ($carts as $ckey => $cvalue) {
                if (is_array($cvalue)) {
                    $carts[$ckey]['product_price'] = change_curreny_value($cvalue['product_price'], $id, $previous_currency);
                    $carts[$ckey]['total'] = change_curreny_value($cvalue['total'], $id, $previous_currency);
                    //if has variation then update price
                    if (isset($cvalue['variations']) && is_array($cvalue['variations'])) {
                        foreach ($cvalue['variations'] as $variation_key => $variation_value) {
                            if (is_array($variation_value) && isset($variation_value['price'])) {
                                $carts[$ckey]['variations'][$variation_key]['price'] = change_curreny_value($variation_value['price'], $id, $previous_currency);
                            }
                        }
                    }
                }
            }
            Session::forget('cart_' . $user->username);
            Session::put('cart_' . $user->username, $carts);
        }
        session()->put('user_curr_' . $user->username, $id);

        return redirect()->back();
    }

    public function invoice()
    {
        $user = getUser();
        $data['userBs'] = BasicSetting::where('user_id', $user->id)->first();
        $data['order']  = UserOrder::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        $data['user'] = $user;
        $file_name = 'fahad' . ".pdf";
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('pdf.item', $data);
        return $pdf->stream('membership.pdf');
        
        $output = $pdf->output();
        $dir = public_path('assets/front/invoices/');
        @mkdir($dir, '0775', true);
        @file_put_contents($dir . $file_name, $output);
        
        return view('pdf.item', $data);
    }


    public function contactView()
    {
        $user = app('user');
        $userCurrentLang = app('userCurrentLang');

        $basic_settings = BasicSetting::where('user_id', $user->id)->select('is_recaptcha', 'google_recaptcha_site_key', 'google_recaptcha_secret_key')->first();
        if ($basic_settings->is_recaptcha == 1) {
            Config::set('captcha.sitekey', $basic_settings->google_recaptcha_site_key);
            Config::set('captcha.secret', $basic_settings->google_recaptcha_secret_key);
        }

        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);
        $uLang = $userCurrentLang->id;
        $data['uLang'] = $userCurrentLang->id;

        $data['seo'] = SEO::where('language_id', $uLang)->where('user_id', $user->id)->first();
        $data['contact'] = UserContact::where('language_id', $uLang)->where('user_id', $user->id)->first();


        return themeView('contact', $data);
    }

    //contactMessage
    public function contactMessage(Request $request, $domain)
    {
        $user = getUser();
        $keywords = Common::get_keywords();
        $use_bs = BasicSetting::where('user_id', $user->id)->first();
        $current_package = UserPermissionHelper::currentPackagePermission($user->id);
        if ($current_package) {
            $features = json_decode($current_package->features, true);
        } else {
            $features = [];
        }
        if ($use_bs->is_recaptcha == 1 && in_array('Google Recaptcha', $features)) {
            Config::set('captcha.sitekey', $use_bs->google_recaptcha_site_key);
            Config::set('captcha.secret', $use_bs->google_recaptcha_secret_key);
        }

        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
        ];
        $messages = [
            'name.required' => __('The name field is required.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('Please enter a valid email address.'),
            'subject.required' => __('The subject field is required.'),
        ];

        if ($use_bs->is_recaptcha == 1 && in_array('Google Recaptcha', $features)) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
        $messages = [
            'g-recaptcha-response.required' => $keywords['Please verify that you are not a robot'] ?? __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => $keywords['Captcha error! try again later or contact site admin'] ?? __('Captcha error! try again later or contact site admin'),
        ];
        $request->validate($rules, $messages);

        if (!is_null($user->email)) {
            $be =  BE::firstOrFail();
            if ($be->is_smtp == 1 && !is_null($use_bs->email)) {
                $subject = $request->subject;
                $message = '<p><strong>Enquirer Name: </strong>' . $request->name . '<br/><strong>Enquirer Mail: </strong>' . $request->email . '</p> <p>Message : ' . $request->message . '</p>';
                /******** Send mail to user ********/
                $data = [];
                $data['smtp_status'] = $be->is_smtp;
                $data['smtp_host'] = $be->smtp_host;
                $data['smtp_port'] = $be->smtp_port;
                $data['encryption'] = $be->encryption;
                $data['smtp_username'] = $be->smtp_username;
                $data['smtp_password'] = $be->smtp_password;

                //mail info in array
                $data['from_mail'] = $be->from_mail;
                $data['recipient'] = !is_null($use_bs->email) ? $use_bs->email : $user->email;
                $data['subject'] = $subject;
                $data['body'] = $message;
                BasicMailer::sendMail($data);
            }

            Session::flash('success', $keywords['Message sent successfully'] ?? __('Message sent successfully'));
            return back();
        } else {
            Session::flash('success', $keywords['Receipent Mail not set yet'] ?? __('Receipent Mail not set yet'));
            return back();
        }
    }

    public function faqs()
    {
        $user = app('user');
        $userCurrentLang = app('userCurrentLang');
        $currentLanguage = app('userCurrentLang');
        $data['pageHeading'] = $this->getUserPageHeading($currentLanguage);

        $uLang = $userCurrentLang->id;
        $data['uLang'] = $userCurrentLang->id;


        $data['seo'] = SEO::where('language_id', $uLang)->where('user_id', $user->id)->first();

        $lang_id =  $userCurrentLang->id;
        $data['faqs'] = Faq::where('language_id', $lang_id)
            ->where('user_id', $user->id)
            ->orderBy('serial_number', 'ASC')
            ->get();
        return themeView('faq', $data);
    }


    public function userBlogs(Request $request, $domain)
    {
        $term = $catid = null;
        $user = app('user');

        $current_package = UserPermissionHelper::currentPackagePermission($user->id);
        $features = json_decode($current_package->features);
        if (is_array($features) && !in_array('Blog', $features)) {
            return view('errors.404');
        }

        $id = $user->id;
        $data['user'] = $user;
        $catid = $request->category;
        $term = $request->term;

        $userCurrentLang = app('userCurrentLang');
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);

        // Auto-seed blogs if this tenant currently has 0 blogs in database
        $blogCount = DB::table('user_blogs')->where('user_id', $id)->count();
        if ($blogCount == 0) {
            $this->seedTenantBlogs($id, $userCurrentLang->id);
        }

        $data['blogs'] = DB::table('user_blogs')
            ->join('user_blog_contents', 'user_blogs.id', '=', 'user_blog_contents.blog_id')
            ->leftJoin('user_blog_categories', 'user_blog_categories.id', '=', 'user_blog_contents.category_id')
            ->where('user_blogs.user_id', $id)
            ->where('user_blogs.status', 1)
            ->when($catid, function ($query, $catid) {
                return $query->where('user_blog_contents.category_id', $catid);
            })
            ->when($term, function ($query, $term) {
                return $query->where('user_blog_contents.title', 'LIKE', '%' . $term . '%');
            })
            ->select('user_blogs.*', 'user_blog_contents.*', 'user_blog_categories.name as categoryName', 'user_blog_categories.id as categoryId')
            ->orderBy('user_blogs.serial_number', 'ASC')
            ->paginate(6);


        $data['latestBlogs'] = DB::table('user_blogs')
            ->join('user_blog_contents', 'user_blogs.id', '=', 'user_blog_contents.blog_id')
            ->leftJoin('user_blog_categories', 'user_blog_categories.id', '=', 'user_blog_contents.category_id')
            ->where('user_blogs.user_id', $id)
            ->where('user_blogs.status', 1)
            ->select('user_blogs.*', 'user_blog_contents.title', 'user_blog_contents.slug', 'user_blog_categories.name as categoryName')
            ->orderBy('user_blogs.id', 'DESC')
            ->limit(3)->get();

        $data['blog_categories'] = BlogCategory::query()
            ->where('status', 1)
            ->orderBy('serial_number', 'ASC')
            ->where('user_id', $id)
            ->get();

        $data['allCount'] = DB::table('user_blogs')
            ->where('user_id', $id)
            ->count();

        $data['seo'] = SEO::where('user_id', $user->id)
            ->select('blogs_meta_keywords', 'blogs_meta_description')
            ->first();

        return themeView('blogs', $data);
    }

    public function seedTenantBlogs($userId, $langId)
    {
        $catId = DB::table('user_blog_categories')->where('user_id', $userId)->where('name', 'Style Guide')->value('id');
        if (!$catId) {
            $catId = DB::table('user_blog_categories')->insertGetId([
                'user_id' => $userId,
                'language_id' => $langId,
                'name' => 'Style Guide',
                'status' => 1,
                'serial_number' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $blogsData = [
            [
                'title' => 'Top 5 Capsule Wardrobe Essentials for Every Season',
                'image' => 'urban_blog_1.png',
                'content' => 'Building a timeless capsule wardrobe allows you to express effortless elegance while simplifying your morning routine. Focus on premium linen shirts, tailored trousers, dark denim jackets, and versatile leather footwear that transition seamlessly between seasons.',
            ],
            [
                'title' => 'The Rise of Sustainable Fashion & Eco-Friendly Textiles',
                'image' => 'urban_blog_2.png',
                'content' => 'Sustainable fashion is redefining modern retail. Discover how organic cotton, recycled linen, and eco-friendly dye processes are helping conscious shoppers curate stunning outfits while reducing environmental impact.',
            ],
            [
                'title' => 'How to Layer Clothing Like a Professional Stylist',
                'image' => 'urban_blog_3.png',
                'content' => 'Mastering texture and proportion is the key to mastering cold weather layering. Pair lightweight knits under heavy wool overcoats, experiment with earthy color accents, and add structured scarves for an elevated look.',
            ],
            [
                'title' => 'Mastering Color Harmony in Everyday Urban Outfits',
                'image' => 'urban_blog_4.png',
                'content' => 'Neutral tones like terracotta, warm beige, cream, and olive green create an effortlessly sophisticated palette. Learn how to combine subtle earth tones for versatile outfit combinations that stand out tastefully.',
            ],
            [
                'title' => 'Garment Care Guide: How to Make Quality Clothes Last',
                'image' => 'urban_blog_1.png',
                'content' => 'Investing in high quality apparel is only half the secret. Proper washing, air drying, steamer maintenance, and natural cedar storage keep your favorite wardrobe pieces looking brand new for years to come.',
            ]
        ];

        foreach ($blogsData as $index => $bData) {
            $slug = \Illuminate\Support\Str::slug($bData['title']);
            $existing = DB::table('user_blog_contents')->where('user_id', $userId)->where('slug', $slug)->first();
            if ($existing) continue;

            $blogId = DB::table('user_blogs')->insertGetId([
                'user_id' => $userId,
                'image' => $bData['image'],
                'status' => 1,
                'serial_number' => $index + 1,
                'created_at' => now()->subDays(rand(1, 10)),
                'updated_at' => now()
            ]);

            DB::table('user_blog_contents')->insert([
                'user_id' => $userId,
                'blog_id' => $blogId,
                'language_id' => $langId,
                'category_id' => $catId,
                'title' => $bData['title'],
                'slug' => $slug,
                'content' => '<p>' . $bData['content'] . '</p>',
                'meta_keywords' => 'urban, fashion, capsule wardrobe, style',
                'meta_description' => $bData['content'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function userBlogDetail($domain, $slug)
    {
        $user = app('user');
        $userId = $user->id;
        $userCurrentLang = app('userCurrentLang');
        $blog_id = BlogContent::where([['user_blog_contents.user_id', $userId], ['user_blog_contents.slug', $slug]])->pluck('blog_id')->first();
        $data['blog'] = BlogContent::where([['user_blog_contents.user_id', $userId], ['user_blog_contents.blog_id', $blog_id], ['user_blog_contents.language_id', $userCurrentLang->id]])
            ->join('user_blog_categories', 'user_blog_categories.id', '=', 'user_blog_contents.category_id')
            ->select('user_blog_contents.*', 'user_blog_categories.name as categoryName')
            ->first();
        if (is_null($data['blog'])) {
            abort(404);
        }

        $data['latestBlogs'] = BlogContent::query()
            ->where([['user_id', $userId], ['language_id', $userCurrentLang->id], ['blog_id', '!=', $data['blog']->blog_id]])
            ->orderBy('id', 'DESC')
            ->limit(5)->get();


        $data['blog_categories'] = BlogCategory::query()
            ->where('status', 1)
            ->orderBy('serial_number', 'ASC')
            ->where('language_id', $userCurrentLang->id)
            ->where('user_id', $userId)
            ->get();

        $data['allCount'] = BlogContent::query()
            ->where('user_id', $userId)
            ->where('language_id', $userCurrentLang->id)
            ->count();

        $userId = $data['blog']->user_id;

        return themeView('blog-details', $data);
    }

    public function userAbout($domain)
    {
        $user = app('user');
        $userCurrentLang = app('userCurrentLang');

        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);

        $data['how_work_steps'] = HowitWorkSection::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->get();

        $data['aboutInfo'] = AboutUs::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->first();
        $data['aboutFeatures'] = AboutUsFeatures::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->get();

        $data['counterSection'] = CounterSection::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->first();
        $data['counterInformations'] = CounterInformation::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->get();

        $data['testimonial_info'] = UserSection::where([['user_id', $user->id], ['language_id', $userCurrentLang->id]])->first();

        $data['testimonials'] = Testimonial::where([['user_id', $user->id], ['language_id', $userCurrentLang->id]])->get();
        $data['uLang'] = $userCurrentLang->id;

        $data['seo'] = SEO::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)
            ->select('about_page_meta_keywords', 'about_page_meta_description')
            ->first();

        //custom section
        $sections = [
            'about_info_section',
            'features_section',
            'counter_section',
            'testimonial_section'
        ];

        $pageType = 'about';
        foreach ($sections as $section) {
            $data["after_" . str_replace('_section', '', $section)] = AdditionalSection::where('possition', $section)
                ->where('page_type', $pageType)
                ->orderBy('serial_number', 'asc')
                ->get();
        }

        return themeView('about', $data);
    }

    public function removeMaintenance($domain, $token)
    {
        Session::put('user-bypass-token', $token);
        return redirect()->route('front.user.detail.view', getParam());
    }

    public function tenantPolicyPage($domain, $slug)
    {
        $user = app('user');
        $userCurrentLang = app('userCurrentLang');
        $id = $user->id;

        $slugMap = [
            'privacy-policy' => ['title' => __('Privacy Policy'), 'default_text' => 'Welcome to ' . ($user->shop_name ?? $user->username) . '. Your privacy is important to us.'],
            'terms-and-conditions' => ['title' => __('Terms & Conditions'), 'default_text' => 'Welcome to ' . ($user->shop_name ?? $user->username) . '. Please read these terms and conditions carefully before using our website.'],
            'refund-policy' => ['title' => __('Cancellation & Refund Policy'), 'default_text' => 'Welcome to ' . ($user->shop_name ?? $user->username) . '. Please review our cancellation and refund policies.'],
            'shipping-policy' => ['title' => __('Shipping & Delivery Policy'), 'default_text' => 'Welcome to ' . ($user->shop_name ?? $user->username) . '. Here you can find all shipping and delivery guidelines.'],
        ];

        $pageInfo = $slugMap[$slug] ?? ['title' => ucfirst(str_replace('-', ' ', $slug)), 'default_text' => 'Policy content'];

        $pageContent = DB::table('user_page_contents')
            ->join('user_pages', 'user_pages.id', '=', 'user_page_contents.page_id')
            ->where('user_page_contents.user_id', $id)
            ->where('user_pages.status', 1)
            ->where(function($q) use ($slug) {
                $q->where('user_page_contents.slug', $slug)
                  ->orWhere('user_page_contents.slug', 'like', '%' . $slug . '%');
            })
            ->select('user_page_contents.*')
            ->first();

        if ($pageContent) {
            $data['page'] = $pageContent;
            $data['title'] = $pageContent->title;
        } else {
            $data['page'] = (object) [
                'title' => $pageInfo['title'],
                'body' => '<h3>' . $pageInfo['title'] . '</h3><p>' . $pageInfo['default_text'] . '</p>',
            ];
            $data['title'] = $pageInfo['title'];
        }

        $data['pageHeading'] = $data['title'];

        return themeView('custom-page', $data);
    }

    public function tenantPrivacyPolicy($domain) { return $this->tenantPolicyPage($domain, 'privacy-policy'); }
    public function tenantTermsConditions($domain) { return $this->tenantPolicyPage($domain, 'terms-and-conditions'); }
    public function tenantRefundPolicy($domain) { return $this->tenantPolicyPage($domain, 'refund-policy'); }
    public function tenantShippingPolicy($domain) { return $this->tenantPolicyPage($domain, 'shipping-policy'); }
}
