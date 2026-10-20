<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageSetting;
use App\Models\Language;
use Illuminate\Http\Request;
use Session;

class LandingPageController extends Controller
{
    /**
     * Display the dynamic landing page editor.
     */
    public function index(Request $request)
    {
        $langCode = $request->input('language', 'en');
        $lang = Language::where('code', $langCode)->first() ?? Language::where('is_default', 1)->first() ?? Language::first();
        $langId = $lang ? $lang->id : 1;

        $settings = LandingPageSetting::getSettings($langId);

        $data['language'] = $lang;
        $data['lang_id'] = $langId;
        $data['settings'] = $settings;

        return view('admin.landing_page.index', $data);
    }

    /**
     * Save changes to dynamic landing page sections.
     */
    public function update(Request $request, $langid)
    {
        $rec = LandingPageSetting::ensureTableExistsAndSeeded();

        $hero = [
            'badge'            => $request->input('hero_badge', 'ALL-IN-ONE WHATSAPP MARKETING TOOL'),
            'title'            => $request->input('hero_title', 'WhatsApp operations, campaigns, and automation'),
            'subtitle'         => $request->input('hero_subtitle'),
            'btn1_text'        => $request->input('hero_btn1_text', 'Create your workspace'),
            'btn1_url'         => $request->input('hero_btn1_url', '/pricing'),
            'btn2_text'        => $request->input('hero_btn2_text', 'Explore modules'),
            'btn2_url'         => $request->input('hero_btn2_url', '#modules'),
            'stat1_val'        => $request->input('hero_stat1_val', '8'),
            'stat1_lbl'        => $request->input('hero_stat1_lbl', 'Operational modules included'),
            'stat2_val'        => $request->input('hero_stat2_val', '1'),
            'stat2_lbl'        => $request->input('hero_stat2_lbl', 'Unified WhatsApp workflow'),
            'stat3_val'        => $request->input('hero_stat3_val', '24/7'),
            'stat3_lbl'        => $request->input('hero_stat3_lbl', 'Automation & reply coverage'),
            'total_messages'   => $request->input('hero_total_messages', '125,890'),
            'delivered'        => $request->input('hero_delivered', '118,420'),
            'replied'          => $request->input('hero_replied', '28,460'),
            'active_contacts'  => $request->input('hero_active_contacts', '96,350'),
            'delivery_rate'    => $request->input('hero_delivery_rate', '95.8%'),
        ];

        $stackCards = [];
        $rawCards = $request->input('stack_cards', []);
        if (is_array($rawCards)) {
            foreach ($rawCards as $c) {
                if (!empty($c['title'])) {
                    $stackCards[] = [
                        'tag'   => $c['tag'] ?? '',
                        'title' => $c['title'] ?? '',
                        'desc'  => $c['desc'] ?? '',
                        'icon'  => $c['icon'] ?? 'fas fa-check-circle',
                        'url'   => $c['url'] ?? '#pricing',
                    ];
                }
            }
        }
        if (empty($stackCards)) {
            $stackCards = LandingPageSetting::defaultStackData()['cards'];
        }

        $stack = [
            'badge'    => $request->input('stack_badge', 'LIVE MODULE STACK'),
            'title'    => $request->input('stack_title', 'Whatsappbulk Workspace'),
            'subtitle' => $request->input('stack_subtitle', 'Everything you need to run WhatsApp at scale'),
            'cards'    => $stackCards,
        ];

        $moduleItems = [];
        $rawModules = $request->input('modules_items', []);
        if (is_array($rawModules)) {
            foreach ($rawModules as $m) {
                if (!empty($m['title'])) {
                    $moduleItems[] = [
                        'title' => $m['title'] ?? '',
                        'desc'  => $m['desc'] ?? '',
                        'icon'  => $m['icon'] ?? 'fas fa-check',
                        'color' => $m['color'] ?? '#10b981',
                    ];
                }
            }
        }
        if (empty($moduleItems)) {
            $moduleItems = LandingPageSetting::defaultModulesData()['items'];
        }

        $modules = [
            'badge'    => $request->input('modules_badge', 'PRODUCT MODULES'),
            'title'    => $request->input('modules_title', 'Built from the WhatsApp features teams actually need'),
            'subtitle' => $request->input('modules_subtitle'),
            'items'    => $moduleItems,
        ];

        $pricingPlans = [];
        $rawPlans = $request->input('pricing_plans', []);
        if (is_array($rawPlans)) {
            foreach ($rawPlans as $p) {
                if (!empty($p['name'])) {
                    $feats = is_array($p['features'] ?? null) 
                           ? $p['features'] 
                           : array_filter(array_map('trim', explode("\n", str_replace("\r", "", $p['features'] ?? ''))));
                    $pricingPlans[] = [
                        'name'     => $p['name'] ?? '',
                        'sub'      => $p['sub'] ?? '',
                        'price'    => $p['price'] ?? '',
                        'term'     => $p['term'] ?? '',
                        'featured' => !empty($p['featured']),
                        'badge'    => $p['badge'] ?? '',
                        'btn_text' => $p['btn_text'] ?? 'Get Started',
                        'btn_url'  => $p['btn_url'] ?? '/register',
                        'features' => $feats,
                    ];
                }
            }
        }
        if (empty($pricingPlans)) {
            $pricingPlans = LandingPageSetting::defaultPricingData()['plans'];
        }

        $pricing = [
            'badge'    => $request->input('pricing_badge', 'PRICING'),
            'title'    => $request->input('pricing_title', 'Simple and transparent pricing'),
            'subtitle' => $request->input('pricing_subtitle'),
            'plans'    => $pricingPlans,
        ];

        $reviews = [];
        $rawReviews = $request->input('testimonials_reviews', []);
        if (is_array($rawReviews)) {
            foreach ($rawReviews as $r) {
                if (!empty($r['name'])) {
                    $reviews[] = [
                        'quote'  => $r['quote'] ?? '',
                        'name'   => $r['name'] ?? '',
                        'role'   => $r['role'] ?? '',
                        'stars'  => intval($r['stars'] ?? 5),
                        'avatar' => $r['avatar'] ?? '',
                    ];
                }
            }
        }
        if (empty($reviews)) {
            $reviews = LandingPageSetting::defaultTestimonialsData()['reviews'];
        }

        $testimonials = [
            'badge'    => $request->input('testimonials_badge', 'TESTIMONIALS'),
            'title'    => $request->input('testimonials_title', 'What our customers say'),
            'subtitle' => $request->input('testimonials_subtitle'),
            'reviews'  => $reviews,
        ];

        $faqItems = [];
        $rawFaqs = $request->input('faqs_items', []);
        if (is_array($rawFaqs)) {
            foreach ($rawFaqs as $f) {
                if (!empty($f['q'])) {
                    $faqItems[] = [
                        'q' => $f['q'] ?? '',
                        'a' => $f['a'] ?? '',
                    ];
                }
            }
        }
        if (empty($faqItems)) {
            $faqItems = LandingPageSetting::defaultFaqsData()['items'];
        }

        $faqs = [
            'badge'    => $request->input('faqs_badge', 'FAQS'),
            'title'    => $request->input('faqs_title', 'Frequently asked questions'),
            'subtitle' => $request->input('faqs_subtitle'),
            'items'    => $faqItems,
        ];

        $ctaFooter = [
            'cta_title'    => $request->input('cta_title', 'Ready to scale your WhatsApp marketing?'),
            'cta_subtitle' => $request->input('cta_subtitle'),
            'cta_btn1_txt' => $request->input('cta_btn1_txt', 'Get Started Free'),
            'cta_btn1_url' => $request->input('cta_btn1_url', '/pricing'),
            'cta_btn2_txt' => $request->input('cta_btn2_txt', 'View Pricing Plans'),
            'cta_btn2_url' => $request->input('cta_btn2_url', '#pricing'),
            'brand_name'   => $request->input('brand_name', 'Whatsappbulk'),
            'tagline'      => $request->input('tagline', 'WhatsApp Marketing & Automation Tool'),
            'copyright'    => $request->input('copyright', '© 2026 Whatsappbulk. All Rights Reserved.'),
        ];

        $rec->update([
            'hero_data'         => json_encode($hero, JSON_UNESCAPED_UNICODE),
            'stack_data'        => json_encode($stack, JSON_UNESCAPED_UNICODE),
            'modules_data'      => json_encode($modules, JSON_UNESCAPED_UNICODE),
            'pricing_data'      => json_encode($pricing, JSON_UNESCAPED_UNICODE),
            'testimonials_data' => json_encode($testimonials, JSON_UNESCAPED_UNICODE),
            'faqs_data'         => json_encode($faqs, JSON_UNESCAPED_UNICODE),
            'cta_footer_data'   => json_encode($ctaFooter, JSON_UNESCAPED_UNICODE),
        ]);

        Session::flash('success', __('Landing Page Sections Updated Successfully'));
        return "success";
    }
}