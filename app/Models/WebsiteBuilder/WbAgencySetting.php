<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbAgencySetting extends Model
{
    use HasFactory;

    protected $table = 'wb_agency_settings';

    protected $fillable = [
        'customer_id',
        'site_title',
        'site_logo',
        'top_announcement',
        'email',
        'phone',
        'address',
        'hero_badge',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'primary_btn_text',
        'primary_btn_url',
        'secondary_btn_text',
        'secondary_btn_url',
        'stats_data',
        'services_data',
        'portfolio_data',
        'testimonials_data',
        'about_hero_title',
        'about_hero_subtitle',
        'story_title',
        'story_text',
        'mission_vision_data',
        'team_members_data',
        'contact_title',
        'contact_subtitle',
        'faqs_data',
        'social_links',
        'footer_text',
    ];

    protected $casts = [
        'stats_data'          => 'array',
        'services_data'       => 'array',
        'portfolio_data'      => 'array',
        'testimonials_data'   => 'array',
        'mission_vision_data' => 'array',
        'team_members_data'   => 'array',
        'faqs_data'           => 'array',
        'social_links'        => 'array',
    ];

    public static function getDefaults($customerId = null): self
    {
        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                if ($customerId) {
                    $setting = self::where('customer_id', $customerId)->first();
                    if (!$setting) {
                        $baseSetting = self::whereNull('customer_id')->first() ?? self::first();
                        if ($baseSetting) {
                            $setting = $baseSetting->replicate();
                            $setting->customer_id = $customerId;
                            try {
                                $setting->save();
                            } catch (\Throwable $ex) {}
                        }
                    }
                } else {
                    $setting = self::whereNull('customer_id')->first() ?? self::first();
                }
            }
        } catch (\Throwable $e) {
            $setting = null;
        }


        if (!$setting) {
            $setting = new self();
            $setting->site_title = 'DesignAGENCY';
            $setting->top_announcement = 'We help businesses grow with creative digital solutions.';
            $setting->email = 'info@designagency.com';
            $setting->phone = '+1 (234) 567-890';
            $setting->address = '123 Design Street, Creative City, CA 90403';
            $setting->hero_badge = 'Creative Digital Solutions';
            $setting->hero_title = "Increase Your\nCustomers Loyalty\nand Satisfaction";
            $setting->hero_subtitle = 'We help businesses like yours earn more customers, stand out from competitors, and grow your revenue.';
            $setting->hero_image = 'assets/website_builder/Templates/Digital_agency/hero_banner.png';
            $setting->primary_btn_text = 'Get Started';
            $setting->primary_btn_url = '#contact';
            $setting->secondary_btn_text = 'View Our Work';
            $setting->secondary_btn_url = '#portfolio';
            $setting->stats_data = [
                ['number' => '8+',   'label' => 'Years of Experience'],
                ['number' => '120+', 'label' => 'Projects Completed'],
                ['number' => '98%',  'label' => 'Client Satisfaction'],
                ['number' => '24/7', 'label' => 'Support Available'],
            ];
            $setting->services_data = [
                ['icon' => 'fa-laptop-code',     'title' => 'Web Design',       'desc' => 'Beautiful, modern, and responsive websites that drive results.'],
                ['icon' => 'fa-layer-group',     'title' => 'UI/UX Design',     'desc' => 'User-centered designs that create seamless digital experiences.'],
                ['icon' => 'fa-bezier-curve',    'title' => 'Branding',         'desc' => 'Unique brand identities that make your business memorable.'],
                ['icon' => 'fa-bullhorn',        'title' => 'Digital Marketing','desc' => 'Data-driven marketing strategies that boost your visibility.'],
                ['icon' => 'fa-magnifying-glass','title' => 'SEO Optimization', 'desc' => 'Improve your search rankings and drive organic traffic.'],
                ['icon' => 'fa-mobile-screen',   'title' => 'App Development',  'desc' => 'Powerful and scalable apps for iOS & Android platforms.'],
            ];
            $setting->portfolio_data = [
                ['title' => 'Fintech Website Redesign', 'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_agency.png'],
                ['title' => 'E-commerce Skincare Store', 'category' => 'Web Design',   'image' => 'assets/website_builder/wb_card_ecommerce.png'],
                ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
                ['title' => 'Brand Identity Design',    'category' => 'Branding',      'image' => 'assets/website_builder/wb_card_portfolio.png'],
                ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_restaurant.png'],
                ['title' => 'Travel Website',           'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_events.png'],
                ['title' => 'Fitness App Design',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
                ['title' => 'Digital Marketing Campaign','category' => 'Marketing',    'image' => 'assets/website_builder/wb_card_agency.png'],
            ];
            $setting->testimonials_data = [
                ['name' => 'John Smith',    'role' => 'CEO, Fineva',       'rating' => 5, 'comment' => 'DesignAGENCY transformed our website and brand identity. The team is professional, creative, and results-driven!'],
                ['name' => 'Sarah Johnson', 'role' => 'Marketing Director, Digitech', 'rating' => 5, 'comment' => 'Amazing experience from start to finish. They understood our needs and delivered beyond our expectations.'],
                ['name' => 'David Brown',   'role' => 'Founder, Shopious', 'rating' => 5, 'comment' => 'Their designs are modern, clean, and user-friendly. Our customers love the new experience!'],
            ];
            $setting->about_hero_title = 'We Are A Creative Digital Solutions Agency';
            $setting->about_hero_subtitle = 'We help brands thrive in the digital world through innovative design, smart strategy, and cutting-edge technology.';
            $setting->story_title = 'Our Journey Started With A Simple Idea';
            $setting->story_text = "DesignAGENCY was founded in 2016 with a mission to empower businesses with smart digital solutions. What began as a small team of creatives has grown into a full-service agency trusted by clients worldwide.\n\nWe believe in building long-term relationships with our clients by delivering measurable results and exceptional experiences.";
            $setting->mission_vision_data = [
                ['title' => 'Our Mission', 'desc' => 'To deliver innovative digital solutions that help businesses grow, connect, and succeed in a competitive world.', 'icon' => 'fa-crosshairs'],
                ['title' => 'Our Vision',  'desc' => 'To be a global leader in digital innovation, known for creativity, reliability, and measurable impact.',  'icon' => 'fa-eye'],
                ['title' => 'Our Values',  'desc' => 'Client Success First, Innovation & Creativity, Integrity & Transparency, Quality & Excellence.',       'icon' => 'fa-gem'],
            ];
            $setting->team_members_data = [
                ['name' => 'Michael Roberts', 'role' => 'Founder & CEO',        'image' => 'assets/website_builder/team_1.jpg'],
                ['name' => 'Sarah Johnson',   'role' => 'Creative Director',     'image' => 'assets/website_builder/team_2.jpg'],
                ['name' => 'Daniel Smith',    'role' => 'Head of Development',  'image' => 'assets/website_builder/team_3.jpg'],
                ['name' => 'Jessica Brown',   'role' => 'Marketing Manager',     'image' => 'assets/website_builder/team_4.jpg'],
            ];
            $setting->contact_title = "Let's Build Something Amazing Together!";
            $setting->contact_subtitle = "Have a project in mind or just want to say hello? We'd love to hear from you.";
            $setting->faqs_data = [
                ['q' => 'How soon can we start our project?', 'a' => 'Once we understand your requirements, we can typically start within 2–3 business days.'],
                ['q' => 'What information do you need to get started?', 'a' => 'We will need your brand assets, project goals, target audience, and any content guidelines.'],
                ['q' => 'Do you offer ongoing support?', 'a' => 'Yes! We offer comprehensive maintenance, updates, and ongoing digital strategy support.'],
                ['q' => 'How do I know if my project is a good fit?', 'a' => 'Feel free to send us a quick message or book a discovery call, and our team will evaluate your needs!'],
            ];
            $setting->footer_text = 'We are a creative digital agency helping businesses grow with modern design, development & marketing solutions.';
        }
        return $setting;
    }
}
