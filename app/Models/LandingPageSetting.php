<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class LandingPageSetting extends Model
{
    protected $table = 'landing_page_settings';

    protected $fillable = [
        'language_id',
        'hero_data',
        'stack_data',
        'modules_data',
        'pricing_data',
        'testimonials_data',
        'faqs_data',
        'cta_footer_data',
    ];

    /**
     * Self-healing: ensure table exists and has default content matching reference images
     */
    public static function ensureTableExistsAndSeeded(): self
    {
        try {
            if (!Schema::hasTable('landing_page_settings')) {
                Schema::create('landing_page_settings', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->integer('language_id')->default(1);
                    $table->longText('hero_data')->nullable();
                    $table->longText('stack_data')->nullable();
                    $table->longText('modules_data')->nullable();
                    $table->longText('pricing_data')->nullable();
                    $table->longText('testimonials_data')->nullable();
                    $table->longText('faqs_data')->nullable();
                    $table->longText('cta_footer_data')->nullable();
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {}

        $record = self::first();
        if (!$record) {
            $record = self::create([
                'language_id' => 1,
                'hero_data' => json_encode(self::defaultHeroData(), JSON_UNESCAPED_UNICODE),
                'stack_data' => json_encode(self::defaultStackData(), JSON_UNESCAPED_UNICODE),
                'modules_data' => json_encode(self::defaultModulesData(), JSON_UNESCAPED_UNICODE),
                'pricing_data' => json_encode(self::defaultPricingData(), JSON_UNESCAPED_UNICODE),
                'testimonials_data' => json_encode(self::defaultTestimonialsData(), JSON_UNESCAPED_UNICODE),
                'faqs_data' => json_encode(self::defaultFaqsData(), JSON_UNESCAPED_UNICODE),
                'cta_footer_data' => json_encode(self::defaultCtaFooterData(), JSON_UNESCAPED_UNICODE),
            ]);
        }

        return $record;
    }

    public static function getSettings($langId = 1): array
    {
        $rec = self::ensureTableExistsAndSeeded();
        return [
            'hero'         => json_decode($rec->hero_data ?? '{}', true) ?: self::defaultHeroData(),
            'stack'        => json_decode($rec->stack_data ?? '{}', true) ?: self::defaultStackData(),
            'modules'      => json_decode($rec->modules_data ?? '{}', true) ?: self::defaultModulesData(),
            'pricing'      => json_decode($rec->pricing_data ?? '{}', true) ?: self::defaultPricingData(),
            'testimonials' => json_decode($rec->testimonials_data ?? '{}', true) ?: self::defaultTestimonialsData(),
            'faqs'         => json_decode($rec->faqs_data ?? '{}', true) ?: self::defaultFaqsData(),
            'cta_footer'   => json_decode($rec->cta_footer_data ?? '{}', true) ?: self::defaultCtaFooterData(),
        ];
    }

    public static function defaultHeroData(): array
    {
        return [
            'badge'            => 'ALL-IN-ONE WHATSAPP MARKETING TOOL',
            'title'            => 'WhatsApp operations,<br><span style="color: #059669;">campaigns,</span> and<br>automation',
            'subtitle_bold'    => 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.',
            'subtitle_desc'    => 'Built around the modules teams actually use every day: bulk outreach, smart replies, auto replies, chatbots, contacts, participant export, profile lookups, and reporting.',
            'subtitle'         => 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports. Built around the modules teams actually use every day: bulk outreach, smart replies, auto replies, chatbots, contacts, participant export, profile lookups, and reporting.',
            'btn1_text'        => 'Create your workspace',
            'btn1_url'         => '/pricing',
            'btn2_text'        => 'Explore modules',
            'btn2_url'         => '#modules',
            'stat1_val'        => '8',
            'stat1_lbl'        => 'Operational modules included',
            'stat2_val'        => '1',
            'stat2_lbl'        => 'Unified WhatsApp workflow',
            'stat3_val'        => '24/7',
            'stat3_lbl'        => 'Automation & reply coverage',
            'total_messages'   => '125,890',
            'delivered'        => '118,420',
            'replied'          => '28,460',
            'active_contacts'  => '96,350',
            'delivery_rate'    => '95.8%',
        ];
    }

    public static function defaultStackData(): array
    {
        return [
            'badge'    => 'LIVE MODULE STACK',
            'title'    => 'Whatsappbulk Workspace',
            'subtitle' => 'Everything you need to run WhatsApp at scale',
            'cards'    => [
                [
                    'tag'   => 'CAMPAIGN CONTROL',
                    'title' => 'Bulk Campaigns',
                    'desc'  => 'Segment contacts, send at scale, and track response flow.',
                    'icon'  => 'fas fa-bullhorn',
                    'url'   => '#pricing',
                ],
                [
                    'tag'   => 'INBOUND HANDLING',
                    'title' => 'AI + Auto Reply',
                    'desc'  => 'Combine automation with faster human response quality.',
                    'icon'  => 'fas fa-comments',
                    'url'   => '#pricing',
                ],
                [
                    'tag'   => 'LEAD FLOW',
                    'title' => 'Chatbot + Contacts',
                    'desc'  => 'Capture, qualify, and manage audience data in one place.',
                    'icon'  => 'fas fa-users',
                    'url'   => '#pricing',
                ],
                [
                    'tag'   => 'VISIBILITY',
                    'title' => 'Reports + Export',
                    'desc'  => 'Export participants and measure what the team actually ships.',
                    'icon'  => 'fas fa-chart-bar',
                    'url'   => '#pricing',
                ],
            ],
        ];
    }

    public static function defaultModulesData(): array
    {
        return [
            'badge'    => 'PRODUCT MODULES',
            'title'    => 'Built from the WhatsApp features teams actually need',
            'subtitle' => 'Every module supports a real operational job, from outreach and automation to contact management and reporting.',
            'items'    => [
                [
                    'title' => 'AI Smart Reply',
                    'desc'  => 'Generate fast, context-aware WhatsApp replies so agents can handle conversations with less manual effort.',
                    'icon'  => 'fas fa-sparkles',
                    'color' => '#10b981',
                ],
                [
                    'title' => 'Auto Reply',
                    'desc'  => 'Trigger automatic responses for inbound messages, off-hours support, lead capture, and common request handling.',
                    'icon'  => 'fas fa-clock',
                    'color' => '#f97316',
                ],
                [
                    'title' => 'Bulk Campaigns',
                    'desc'  => 'Launch high-volume WhatsApp campaigns to segmented audiences while keeping timing and response flow under control.',
                    'icon'  => 'fas fa-bullhorn',
                    'color' => '#ef4444',
                ],
                [
                    'title' => 'Chatbot',
                    'desc'  => 'Build conversational flows that qualify leads, answer questions, and move contacts into the next step automatically.',
                    'icon'  => 'fas fa-robot',
                    'color' => '#f59e0b',
                ],
                [
                    'title' => 'Contacts',
                    'desc'  => 'Manage contact lists, segments, and campaign targets from one structured database.',
                    'icon'  => 'fas fa-address-book',
                    'color' => '#ea580c',
                ],
                [
                    'title' => 'Export Participants',
                    'desc'  => 'Extract participants from WhatsApp groups for outreach, qualification, migration, and audience building workflows.',
                    'icon'  => 'fas fa-users',
                    'color' => '#10b981',
                ],
                [
                    'title' => 'Profile Info',
                    'desc'  => 'Retrieve WhatsApp profile information quickly to enrich lead context and improve handoff quality.',
                    'icon'  => 'fas fa-id-card',
                    'color' => '#f97316',
                ],
                [
                    'title' => 'Reports',
                    'desc'  => 'Track campaign output, reply activity, and workflow performance with clear reporting for real operational decisions.',
                    'icon'  => 'fas fa-chart-line',
                    'color' => '#ef4444',
                ],
            ],
        ];
    }

    public static function defaultPricingData(): array
    {
        return [
            'badge'    => 'PRICING',
            'title'    => 'Simple and transparent pricing',
            'subtitle' => 'Choose the plan that fits your business needs. Upgrade or downgrade anytime.',
            'plans'    => [
                [
                    'name'     => 'Starter',
                    'sub'      => 'For individuals and small businesses',
                    'price'    => '$19',
                    'term'     => '/month',
                    'featured' => false,
                    'badge'    => '',
                    'btn_text' => 'Get Started',
                    'btn_url'  => '/register',
                    'features' => [
                        '1 WhatsApp number',
                        '5,000 messages/month',
                        'Basic automation',
                        'Standard support',
                    ],
                ],
                [
                    'name'     => 'Professional',
                    'sub'      => 'For growing teams',
                    'price'    => '$49',
                    'term'     => '/month',
                    'featured' => true,
                    'badge'    => 'Most Popular',
                    'btn_text' => 'Get Started',
                    'btn_url'  => '/register',
                    'features' => [
                        '3 WhatsApp numbers',
                        '25,000 messages/month',
                        'All modules included',
                        'Advanced automation',
                        'Priority support',
                    ],
                ],
                [
                    'name'     => 'Business',
                    'sub'      => 'For established businesses',
                    'price'    => '$99',
                    'term'     => '/month',
                    'featured' => false,
                    'badge'    => '',
                    'btn_text' => 'Get Started',
                    'btn_url'  => '/register',
                    'features' => [
                        '10 WhatsApp numbers',
                        '100,000 messages/month',
                        'All modules included',
                        'Team collaboration',
                        'Advanced reporting',
                        'Priority support',
                    ],
                ],
                [
                    'name'     => 'Enterprise',
                    'sub'      => 'For large organizations',
                    'price'    => 'Custom',
                    'term'     => '',
                    'featured' => false,
                    'badge'    => '',
                    'btn_text' => 'Contact Sales',
                    'btn_url'  => '/contact',
                    'features' => [
                        'Unlimited numbers',
                        'Custom message volume',
                        'All modules included',
                        'Dedicated manager',
                        'Custom integrations',
                        'SLA & premium support',
                    ],
                ],
            ],
        ];
    }

    public static function defaultTestimonialsData(): array
    {
        return [
            'badge'    => 'TESTIMONIALS',
            'title'    => 'What our customers say',
            'subtitle' => 'Trusted by businesses worldwide to streamline their WhatsApp operations.',
            'reviews'  => [
                [
                    'quote'  => 'Whatsappbulk has completely transformed how we handle customer communication. Our response time improved by 70%!',
                    'name'   => 'Priya Sharma',
                    'role'   => 'Marketing Manager, RetailHub',
                    'stars'  => 5,
                    'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=120&auto=format&fit=crop&q=80',
                ],
                [
                    'quote'  => 'The bulk campaign feature is a game-changer. We can now reach thousands of customers in minutes with excellent delivery rates.',
                    'name'   => 'Rahul Mehta',
                    'role'   => 'Founder, GrowKart',
                    'stars'  => 5,
                    'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=120&auto=format&fit=crop&q=80',
                ],
                [
                    'quote'  => 'Easy to use, powerful features, and great support. Highly recommended for any business using WhatsApp.',
                    'name'   => 'Sneha Patel',
                    'role'   => 'Operations Head, Foodies',
                    'stars'  => 5,
                    'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=120&auto=format&fit=crop&q=80',
                ],
            ],
        ];
    }

    public static function defaultFaqsData(): array
    {
        return [
            'badge'    => 'FAQS',
            'title'    => 'Frequently asked questions',
            'subtitle' => 'Get answers to common questions about Whatsappbulk.',
            'items'    => [
                [
                    'q' => 'What is Whatsappbulk?',
                    'a' => 'Whatsappbulk is an all-in-one WhatsApp marketing tool that helps you manage campaigns, automate replies, handle contacts, and track performance — all in one workspace.',
                ],
                [
                    'q' => 'Do you offer a free trial?',
                    'a' => 'Yes, we offer a 7-day risk-free trial on all plans so you can test all features and message automations before committing.',
                ],
                [
                    'q' => 'Is it safe to use?',
                    'a' => 'Yes, our platform implements smart rate limiting, human-like sending intervals, and official WhatsApp protocols to safeguard your phone numbers and compliance.',
                ],
                [
                    'q' => 'What payment methods do you accept?',
                    'a' => 'We accept all major credit and debit cards, UPI, net banking, PayPal, and international wire transfers.',
                ],
                [
                    'q' => 'Can I upgrade my plan later?',
                    'a' => 'Absolutely. You can upgrade, downgrade, or cancel your subscription at any time directly from your billing dashboard with prorated billing.',
                ],
                [
                    'q' => 'Can I use multiple WhatsApp numbers?',
                    'a' => 'Yes, depending on your selected tier (Professional, Business, or Enterprise), you can connect multiple WhatsApp numbers simultaneously.',
                ],
                [
                    'q' => 'Do you provide customer support?',
                    'a' => 'Yes, our dedicated team offers 24/7 support via live chat, WhatsApp, and email to assist you with onboarding and workflows.',
                ],
            ],
        ];
    }

    public static function defaultCtaFooterData(): array
    {
        return [
            'cta_title'    => 'Ready to scale your WhatsApp marketing?',
            'cta_subtitle' => 'Connect your WhatsApp account in seconds. Start sending bulk campaigns, automating customer replies, and converting more leads with zero coding required.',
            'cta_btn1_txt' => 'Get Started Free',
            'cta_btn1_url' => '/pricing',
            'cta_btn2_txt' => 'View Pricing Plans',
            'cta_btn2_url' => '#pricing',
            'brand_name'   => 'Whatsappbulk',
            'tagline'      => 'WhatsApp Marketing & Automation Tool',
            'copyright'    => '© 2026 Whatsappbulk. All Rights Reserved.',
        ];
    }
}