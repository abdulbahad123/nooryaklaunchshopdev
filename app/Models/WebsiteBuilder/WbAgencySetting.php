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
        'template_type',
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
        'about_hero_image',
        'story_title',
        'story_text',
        'mission_vision_data',
        'team_members_data',
        'contact_title',
        'contact_subtitle',
        'contact_image',
        'faqs_data',
        'social_links',
        'footer_text',
        'footer_quick_links',
        'footer_legal_links',
        'custom_domain',
        'custom_domain_status',
        'blogs_data',
        'logo_type',
        'fare_calculator_data',
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
        'footer_quick_links'  => 'array',
        'footer_legal_links'  => 'array',
        'blogs_data'          => 'array',
        'fare_calculator_data'=> 'array',
    ];

    public static function ensureColumnsExist(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                \Illuminate\Support\Facades\Schema::table('wb_agency_settings', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'template_type')) {
                        $table->string('template_type')->default('digital_agency')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'blogs_data')) {
                        $table->json('blogs_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'custom_domain')) {
                        $table->string('custom_domain')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'custom_domain_status')) {
                        $table->tinyInteger('custom_domain_status')->default(0);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'footer_quick_links')) {
                        $table->json('footer_quick_links')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'footer_legal_links')) {
                        $table->json('footer_legal_links')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'about_hero_image')) {
                        $table->string('about_hero_image')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'contact_image')) {
                        $table->string('contact_image')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'logo_type')) {
                        $table->string('logo_type')->default('image');
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'fare_calculator_data')) {
                        $table->json('fare_calculator_data')->nullable();
                    }
                });
            }
        } catch (\Throwable $e) {}
    }

    public static function getDemoDefaults($templateType = 'digital_agency'): self
    {
        self::ensureColumnsExist();
        if (!in_array($templateType, ['digital_agency', 'interior', 'texigo'])) {
            $templateType = 'digital_agency';
        }

        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                $setting = self::whereNull('customer_id')->where('template_type', $templateType)->first();
            }
        } catch (\Throwable $e) {}

        if (!$setting) {
            if ($templateType === 'interior') {
                $setting = self::createInteriorDefaultInstance(null);
            } elseif ($templateType === 'texigo') {
                $setting = self::createTexigoDefaultInstance(null);
            } else {
                $setting = self::createDefaultInstance(null);
            }
            try {
                $setting->save();
            } catch (\Throwable $e) {}
        }
        return $setting;
    }

    public static function getDefaults($customerId = null): self
    {
        self::ensureColumnsExist();
        if (!$customerId) {
            return self::getDemoDefaults('digital_agency');
        }

        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                $setting = self::where('customer_id', $customerId)->first();
                if (!$setting) {
                    $isInteriorCustomer = false;
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                            $cust = WbCustomer::find($customerId);
                            if ($cust) {
                                if (str_contains(strtolower($cust->subdomain ?? ''), 'interior')) {
                                    $isInteriorCustomer = true;
                                }
                                if (!$isInteriorCustomer && !empty($cust->email) && \Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                                    $purchase = WbTemplatePurchase::where('customer_email', $cust->email)->latest()->first();
                                    if ($purchase && in_array(strtolower($purchase->template_slug ?? ''), ['interior', 'interiorcraft'])) {
                                        $isInteriorCustomer = true;
                                    }
                                }
                            }
                        }
                    } catch (\Throwable $ex) {}

                    if ($isInteriorCustomer) {
                        $setting = self::createInteriorDefaultInstance($customerId);
                    } else {
                        $demo = self::whereNull('customer_id')->where('template_type', 'digital_agency')->first() ?? self::whereNull('customer_id')->first();
                        if ($demo) {
                            $setting = $demo->replicate();
                            $setting->customer_id = $customerId;
                        } else {
                            $setting = self::createDefaultInstance($customerId);
                        }
                    }
                    try {
                        $setting->save();
                    } catch (\Throwable $ex) {}
                }
            }
        } catch (\Throwable $e) {
            $setting = null;
        }

        if (!$setting) {
            $setting = self::createDefaultInstance($customerId);
        }

        return $setting;
    }

    public static function createDefaultInstance($customerId = null): self
    {
        $setting = new self();
        if ($customerId) {
            $setting->customer_id = $customerId;
            try {
                $cust = WbCustomer::find($customerId);
                if ($cust) {
                    $setting->site_title = $cust->company_name ?: ($cust->name . ' Agency');
                    $setting->email = $cust->email ?: 'info@designagency.com';
                    $setting->phone = $cust->phone ?: '+1 (234) 567-890';
                }
            } catch (\Throwable $e) {}
        }
        if (empty($setting->site_title)) {
            $setting->site_title = 'DesignAGENCY';
        }
        if (empty($setting->email)) {
            $setting->email = 'info@designagency.com';
        }
        if (empty($setting->phone)) {
            $setting->phone = '+1 (234) 567-890';
        }
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
            ['title' => 'Fintech Website Redesign', 'category' => 'Web Design • UI/UX',          'image' => 'assets/website_builder/wb_card_agency.png',    'link' => '#'],
            ['title' => 'E-commerce Website',       'category' => 'Web Design • E-commerce',      'image' => 'assets/website_builder/wb_card_ecommerce.png', 'link' => '#'],
            ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png',   'link' => '#'],
            ['title' => 'Brand Identity Design',    'category' => 'Branding • Graphic Design',   'image' => 'assets/website_builder/wb_card_portfolio.png', 'link' => '#'],
            ['title' => 'Travel Website',           'category' => 'Web Design • UI/UX',          'image' => 'assets/website_builder/wb_card_events.png',    'link' => '#'],
            ['title' => 'Fitness App Design',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png',   'link' => '#'],
            ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design • Web App',      'image' => 'assets/website_builder/wb_card_restaurant.png','link' => '#'],
            ['title' => 'Digital Marketing Campaign','category' => 'Marketing • Social Media',   'image' => 'assets/website_builder/wb_card_agency.png',    'link' => '#'],
            ['title' => 'Restaurant Website',       'category' => 'Web Design • E-commerce',      'image' => 'assets/website_builder/wb_card_ecommerce.png', 'link' => '#'],
        ];
        $setting->testimonials_data = [
            ['name' => 'John Smith',    'role' => 'CEO, Fineva',       'rating' => 5, 'comment' => 'DesignAGENCY transformed our website and brand identity. The team is professional, creative, and results-driven!'],
            ['name' => 'Sarah Johnson', 'role' => 'Marketing Director, Digitech', 'rating' => 5, 'comment' => 'Amazing experience from start to finish. They understood our needs and delivered beyond our expectations.'],
            ['name' => 'David Brown',   'role' => 'Founder, Shopious', 'rating' => 5, 'comment' => 'Their designs are modern, clean, and user-friendly. Our customers love the new experience!'],
        ];
        $setting->about_hero_title = 'We Are A Creative Digital Solutions Agency';
        $setting->about_hero_subtitle = 'We help brands thrive in the digital world through innovative design, smart strategy, and cutting-edge technology.';
        $setting->about_hero_image = 'assets/website_builder/agency_team_meeting.png';
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
        $setting->contact_title = "Ready to Grow Your Business?";
        $setting->contact_subtitle = "Let's work together to create something amazing for your brand.";
        $setting->contact_image = "assets/website_builder/Templates/Digital_agency/contact_footer.png";
        $setting->faqs_data = [
            ['q' => 'How soon can we start our project?', 'a' => 'Once we understand your requirements, we can typically start within 2–3 business days.'],
            ['q' => 'What information do you need to get started?', 'a' => 'We will need your brand assets, project goals, target audience, and any content guidelines.'],
            ['q' => 'Do you offer ongoing support?', 'a' => 'Yes! We offer comprehensive maintenance, updates, and ongoing digital strategy support.'],
            ['q' => 'How do I know if my project is a good fit?', 'a' => 'Feel free to send us a quick message or book a discovery call, and our team will evaluate your needs!'],
        ];
        $setting->footer_text = 'We are a creative digital agency helping businesses grow with modern design, development & marketing solutions.';
        $setting->footer_quick_links = [
            ['title' => 'Home',       'url' => '#'],
            ['title' => 'About Us',   'url' => '#about'],
            ['title' => 'Portfolio',  'url' => '#portfolio'],
            ['title' => 'Contact Us', 'url' => '#contact'],
        ];
        $setting->footer_legal_links = [
            ['title' => 'Privacy Policy',     'url' => '#privacy'],
            ['title' => 'Terms & Conditions', 'url' => '#terms'],
            ['title' => 'Disclaimer',         'url' => '#disclaimer'],
            ['title' => 'Refund Policy',      'url' => '#refund'],
        ];
        $setting->blogs_data = [
            [
                'id'          => 1,
                'title'       => '10 Modern UI/UX Trends Shaping Digital Products in 2026',
                'category'    => 'Design & Tech',
                'author'      => 'Michael Roberts',
                'date'        => 'Sep 04, 2026',
                'image'       => 'assets/website_builder/wb_card_agency.png',
                'excerpt'     => 'Discover the top design trends driving higher customer engagement and conversions for digital platforms.',
                'content'     => 'In 2026, user experience design continues to evolve at a breakneck pace. Modern audiences expect seamless performance, vibrant dark-mode aesthetics, micro-interactions, and instant accessibility.',
            ],
            [
                'id'          => 2,
                'title'       => 'How Strategic Branding Drives Revenue Growth for Startups',
                'category'    => 'Branding',
                'author'      => 'Sarah Johnson',
                'date'        => 'Aug 28, 2026',
                'image'       => 'assets/website_builder/wb_card_portfolio.png',
                'excerpt'     => 'Learn how a cohesive brand identity instills trust and establishes a strong competitive advantage.',
                'content'     => 'Branding is far more than just a logo or a color scheme. It is the emotional and psychological connection your business establishes with every client.',
            ],
            [
                'id'          => 3,
                'title'       => 'Maximizing Search Visibility with Data-Driven SEO Tactics',
                'category'    => 'SEO & Marketing',
                'author'      => 'Jessica Brown',
                'date'        => 'Aug 15, 2026',
                'image'       => 'assets/website_builder/wb_card_startup.png',
                'excerpt'     => 'A complete guide to optimizing site speed, technical SEO, and organic ranking strategies.',
                'content'     => 'Organic search traffic remains one of the highest-converting marketing channels available today. By focusing on technical site architecture, keyword relevance, and high-quality informative content, businesses can secure reliable long-term visibility.',
            ],
        ];

        return $setting;
    }

    public static function getInteriorDefaults($customerId = null): self
    {
        self::ensureColumnsExist();
        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                if ($customerId) {
                    $setting = self::where('customer_id', $customerId)->where('template_type', 'interior')->first();
                } else {
                    $setting = self::whereNull('customer_id')->where('template_type', 'interior')->first();
                }
            }
        } catch (\Throwable $e) {}

        if (!$setting) {
            $setting = self::createInteriorDefaultInstance($customerId);
            try {
                $setting->save();
            } catch (\Throwable $e) {}
        }
        return $setting;
    }

    public static function createInteriorDefaultInstance($customerId = null): self
    {
        $setting = new self();
        $setting->template_type = 'interior';
        if ($customerId) {
            $setting->customer_id = $customerId;
            try {
                $cust = WbCustomer::find($customerId);
                if ($cust) {
                    $setting->site_title = $cust->company_name ?: ($cust->name . ' Studio');
                    $setting->email = $cust->email ?: 'hello@interiorcraft.com';
                    $setting->phone = $cust->phone ?: '+1 (800) 456-7890';
                }
            } catch (\Throwable $e) {}
        }

        if (empty($setting->site_title)) {
            $setting->site_title = 'InterioCRAFT';
        }
        if (empty($setting->email)) {
            $setting->email = 'hello@interiocraft.com';
        }
        if (empty($setting->phone)) {
            $setting->phone = '+1 (234) 567-890';
        }

        $setting->top_announcement = 'Designing spaces. Creating better lives.';
        $setting->address = '123 Design Street, Creative City, CA 94043';
        $setting->hero_badge = 'Our Home';
        $setting->hero_title = "Spaces We Design,\nStories We Create";
        $setting->hero_subtitle = 'Explore our latest interior design projects and see how we turn ideas into beautiful, functional spaces.';
        $setting->hero_image = 'assets/website_builder/Templates/Interior_agency/homepage_hero.png';
        $setting->about_hero_image = 'assets/website_builder/Templates/Interior_agency/aboutus_hero.png';
        $setting->contact_image = 'assets/website_builder/Templates/Interior_agency/contact_footer.png';
        $setting->header_logo = 'assets/website_builder/Templates/Interior_agency/header_logo.png';
        $setting->footer_logo = 'assets/website_builder/Templates/Interior_agency/footer_logo.png';
        $setting->primary_btn_text = 'Start Your Project';
        $setting->primary_btn_url = '#contact';
        $setting->secondary_btn_text = 'Watch Our Story';
        $setting->secondary_btn_url = '#video';

        $setting->stats_data = [
            ['number' => '250+', 'label' => 'Projects Completed', 'icon' => 'fa-house'],
            ['number' => '98%',  'label' => 'Client Satisfaction',  'icon' => 'fa-star'],
            ['number' => '120+', 'label' => 'Happy Homeowners',   'icon' => 'fa-users'],
        ];

        $setting->services_data = [
            [
                'title' => 'Residential Design',
                'desc'  => 'Bespoke living rooms, luxury master suites, modern kitchens, and private estate interiors.',
                'image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=600&auto=format&fit=crop'
            ],
            [
                'title' => 'Commercial Architecture',
                'desc'  => 'Sophisticated office spaces, luxury retail boutiques, hospitality suites, and corporate lounges.',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600&auto=format&fit=crop'
            ],
            [
                'title' => 'Space Planning & Layout',
                'desc'  => 'Optimizing spatial ergonomics, natural light flow, structural layouts, and functional zoning.',
                'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop'
            ],
            [
                'title' => 'Custom Furniture & Styling',
                'desc'  => 'Handcrafted timber pieces, curated textiles, custom lighting fixtures, and art curation.',
                'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=600&auto=format&fit=crop'
            ],
        ];

        $setting->portfolio_data = [
            [
                'title'    => 'Modern Living Room',
                'category' => 'Residential',
                'desc'     => 'A perfect blend of comfort and style.',
                'image'    => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-house'
            ],
            [
                'title'    => 'Elegant Modular Kitchen',
                'category' => 'Residential',
                'desc'     => 'Functional design for modern homes.',
                'image'    => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-utensils'
            ],
            [
                'title'    => 'Modern Office Space',
                'category' => 'Commercial',
                'desc'     => 'Productive spaces for growing businesses.',
                'image'    => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-building'
            ],
            [
                'title'    => 'Luxury Bedroom',
                'category' => 'Residential',
                'desc'     => 'A peaceful retreat for your everyday life.',
                'image'    => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-bed'
            ],
            [
                'title'    => 'Stylish Restaurant',
                'category' => 'Hospitality',
                'desc'     => 'Inviting spaces that leave a lasting impression.',
                'image'    => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-utensils'
            ],
            [
                'title'    => 'Retail Store Design',
                'category' => 'Commercial',
                'desc'     => 'Creative interiors for modern brands.',
                'image'    => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-bag-shopping'
            ],
            [
                'title'    => 'Bathroom Makeover',
                'category' => 'Renovation',
                'desc'     => 'Transforming spaces with elegant details.',
                'image'    => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-shower'
            ],
            [
                'title'    => 'Home Styling',
                'category' => 'Interior Styling',
                'desc'     => 'Thoughtful details that make a difference.',
                'image'    => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-couch'
            ],
            [
                'title'    => 'Outdoor Living Space',
                'category' => 'Space Planning',
                'desc'     => 'Beautiful spaces beyond your walls.',
                'image'    => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-tree'
            ],
        ];

        $setting->testimonials_data = [
            [
                'name'    => 'Eleanor Vance',
                'role'    => 'Homeowner, Manhattan Penthouse',
                'comment' => 'InterioCRAFT transformed our raw penthouse shell into a warm, breathtaking sanctuary. Their attention to custom wood detailing and lighting flow is unparalleled.',
                'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'
            ],
            [
                'name'    => 'Marcus Sterling',
                'role'    => 'Founder, Sterling Capital',
                'comment' => 'From initial 3D renderings to final furniture delivery, the execution was flawless. Our corporate headquarters now radiates prestige and ergonomic comfort.',
                'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'
            ],
        ];

        $setting->contact_title = 'Ready to Transform Your Space?';
        $setting->contact_subtitle = "Let's work together to create a space that reflects your style and enhances your everyday life.";
        $setting->footer_text = 'We create beautiful, functional spaces that reflect your style and improve your everyday living.';
        
        return $setting;
    }

    public static function getTexigoDefaults($customerId = null): self
    {
        self::ensureColumnsExist();
        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                if ($customerId) {
                    $setting = self::where('customer_id', $customerId)->where('template_type', 'texigo')->first();
                } else {
                    $setting = self::whereNull('customer_id')->where('template_type', 'texigo')->first();
                }
            }
        } catch (\Throwable $e) {}

        if (!$setting) {
            $setting = self::createTexigoDefaultInstance($customerId);
            try {
                $setting->save();
            } catch (\Throwable $e) {}
        }
        return $setting;
    }

    public static function createTexigoDefaultInstance($customerId = null): self
    {
        $setting = new self();
        $setting->template_type = 'texigo';
        if ($customerId) {
            $setting->customer_id = $customerId;
            try {
                $cust = WbCustomer::find($customerId);
                if ($cust) {
                    $setting->site_title = $cust->company_name ?: ($cust->name . ' TaxiGo');
                    $setting->email = $cust->email ?: 'hello@taxigo.com';
                    $setting->phone = $cust->phone ?: '+1 (234) 567-890';
                }
            } catch (\Throwable $e) {}
        }

        if (empty($setting->site_title)) {
            $setting->site_title = 'TaxiGo';
        }
        if (empty($setting->email)) {
            $setting->email = 'hello@taxigo.com';
        }
        if (empty($setting->phone)) {
            $setting->phone = '+1 (234) 567-890';
        }

        $setting->top_announcement = '🚖 #1 Trusted Taxi Service';
        $setting->address = '123 Mobility Way, City Center, NY 10001';
        $setting->hero_badge = '🚖 #1 Trusted Taxi Service';
        $setting->hero_title = "Your Journey\nOur Priority";
        $setting->hero_subtitle = 'Reliable. Safe. Affordable. Get where you need to go with comfort and peace of mind.';
        $setting->hero_image = 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png';
        $setting->about_hero_image = 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png';
        $setting->contact_image = 'https://images.unsplash.com/photo-1511527656417-089b6a6f5d1e?q=80&w=1200&auto=format&fit=crop';
        $setting->header_logo = '';
        $setting->footer_logo = '';
        $setting->primary_btn_text = 'Book Your Ride';
        $setting->primary_btn_url = '#book';
        $setting->secondary_btn_text = 'Explore Services';
        $setting->secondary_btn_url = '#services';

        $setting->stats_data = [
            ['number' => '8+',    'label' => 'Years of Experience',   'icon' => 'fa-users'],
            ['number' => '250K+', 'label' => 'Rides Completed',       'icon' => 'fa-car'],
            ['number' => '98%',   'label' => 'Customer Satisfaction', 'icon' => 'fa-star'],
            ['number' => '50+',   'label' => 'Professional Drivers',  'icon' => 'fa-user-tie'],
        ];

        $setting->services_data = [
            [
                'title' => 'City Rides',
                'desc'  => 'Quick and affordable rides within the city.',
                'image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-city'
            ],
            [
                'title' => 'Airport Transfers',
                'desc'  => 'On-time pickups and drop-offs for stress-free travel.',
                'image' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-plane-departure'
            ],
            [
                'title' => 'Outstation Trips',
                'desc'  => 'Comfortable long-distance rides to any destination.',
                'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-route'
            ],
            [
                'title' => 'Corporate Travel',
                'desc'  => 'Reliable and executive rides for business professionals.',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-briefcase'
            ],
            [
                'title' => 'Parcel Delivery',
                'desc'  => 'Fast, express, and secure local delivery service.',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-box'
            ],
        ];

        $setting->portfolio_data = [
            [
                'title'    => 'Hatchback',
                'category' => 'Economical',
                'desc'     => 'Ideal for solo riders or small quick city commutes.',
                'image'    => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=800&auto=format&fit=crop',
                'seats'    => '4 Seats',
                'bags'     => '2 Bags',
                'icon'     => 'fa-car-side'
            ],
            [
                'title'    => 'Sedan',
                'category' => 'Comfort',
                'desc'     => 'Spacious and smooth rides for daily travel.',
                'image'    => 'https://images.unsplash.com/photo-1550355291-bbee04a92027?q=80&w=800&auto=format&fit=crop',
                'seats'    => '4 Seats',
                'bags'     => '3 Bags',
                'icon'     => 'fa-car'
            ],
            [
                'title'    => 'SUV',
                'category' => 'Family & XL',
                'desc'     => 'Extra space for big families and luggage.',
                'image'    => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=800&auto=format&fit=crop',
                'seats'    => '6 Seats',
                'bags'     => '4 Bags',
                'icon'     => 'fa-truck-monster'
            ],
            [
                'title'    => 'Premium',
                'category' => 'Luxury',
                'desc'     => 'Executive luxury cars for VIP corporate travel.',
                'image'    => 'https://images.unsplash.com/photo-1563720223185-11003d516935?q=80&w=800&auto=format&fit=crop',
                'seats'    => '4 Seats',
                'bags'     => '3 Bags',
                'icon'     => 'fa-car-rear'
            ],
        ];

        $setting->testimonials_data = [
            [
                'name'    => 'Emily Carter',
                'role'    => 'Frequent Traveler',
                'comment' => 'TaxiGo made my airport transfer so easy and stress-free. Drivers are always punctual and polite!',
                'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'
            ],
            [
                'name'    => 'James Walker',
                'role'    => 'Business Executive',
                'comment' => 'Reliable, professional, and affordable. The best taxi mobility service in the city!',
                'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'
            ],
            [
                'name'    => 'Sophia Lee',
                'role'    => 'Regular Customer',
                'comment' => 'Great service and very clean cars. I always choose TaxiGo for my family trips!',
                'avatar'  => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop'
            ],
        ];

        $setting->contact_title = 'Ready to Book Your Next Ride?';
        $setting->contact_subtitle = 'Safe Rides. Happy Journeys. Always.';
        $setting->footer_text = 'Providing safe, reliable, and comfortable transportation for everyone, anytime, anywhere.';
        
        return $setting;
    }
}
