<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Feature;
use App\Models\Process;

class ImageText extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->unique_id)) {
                $model->unique_id = 12345;
            }
        });
    }

    public static function ensureColumnsExist(): void
    {
        try {
            if (!Schema::hasTable('image_texts')) {
                return;
            }

            $colsDefinition = [
                // Hero Badges
                'hero_badge1_title' => 'VARCHAR(255)',
                'hero_badge1_subtitle' => 'TEXT',
                'hero_badge2_title' => 'VARCHAR(255)',
                'hero_badge2_subtitle' => 'TEXT',
                'hero_badge3_title' => 'VARCHAR(255)',
                'hero_badge3_subtitle' => 'TEXT',
                // Work Process
                'work_process_section_subtitle' => 'TEXT',
                'work_process_image' => 'VARCHAR(255)',
                'work_process_btn_text' => 'VARCHAR(255)',
                'work_process_btn_url' => 'VARCHAR(255)',
                // Features
                'dashboard_image' => 'VARCHAR(255)',
                'features_bullet1' => 'TEXT',
                'features_bullet2' => 'TEXT',
                'features_bullet3' => 'TEXT',
                'features_bullet4' => 'TEXT',
                // Page Hero Banners
                'about_hero_badge' => 'VARCHAR(255)',
                'about_hero_title' => 'VARCHAR(255)',
                'about_hero_subtitle' => 'TEXT',
                'about_hero_image' => 'VARCHAR(255)',
                'about_hero_btn_text' => 'VARCHAR(255)',
                'about_hero_btn_url' => 'VARCHAR(255)',
                'about_hero_video_url' => 'VARCHAR(255)',
                'shops_hero_badge' => 'VARCHAR(255)',
                'shops_hero_title' => 'VARCHAR(255)',
                'shops_hero_subtitle' => 'TEXT',
                'shops_hero_image' => 'VARCHAR(255)',
                'templates_hero_badge' => 'VARCHAR(255)',
                'templates_hero_title' => 'VARCHAR(255)',
                'templates_hero_subtitle' => 'TEXT',
                'templates_hero_image' => 'VARCHAR(255)',
                'pricing_hero_badge' => 'VARCHAR(255)',
                'pricing_hero_title' => 'VARCHAR(255)',
                'pricing_hero_subtitle' => 'TEXT',
                'contact_hero_badge' => 'VARCHAR(255)',
                'contact_hero_title' => 'VARCHAR(255)',
                'contact_hero_subtitle' => 'TEXT',
                'contact_hero_image' => 'VARCHAR(255)',
                // Stores Launched Counter Stats
                'shops_stat1_value' => 'VARCHAR(255)',
                'shops_stat1_label' => 'VARCHAR(255)',
                'shops_stat1_icon' => 'VARCHAR(255)',
                'shops_stat2_value' => 'VARCHAR(255)',
                'shops_stat2_label' => 'VARCHAR(255)',
                'shops_stat2_icon' => 'VARCHAR(255)',
                'shops_stat3_value' => 'VARCHAR(255)',
                'shops_stat3_label' => 'VARCHAR(255)',
                'shops_stat3_icon' => 'VARCHAR(255)',
                'shops_stat4_value' => 'VARCHAR(255)',
                'shops_stat4_label' => 'VARCHAR(255)',
                'shops_stat4_icon' => 'VARCHAR(255)',
                // Stores Launched Trust Points
                'shops_hero_feat1_text' => 'TEXT',
                'shops_hero_feat2_text' => 'TEXT',
                'shops_hero_feat3_text' => 'TEXT',
                // Custom VIP Popup
                'popup_status' => 'VARCHAR(50)',
                'popup_ribbon_text' => 'VARCHAR(255)',
                'popup_title' => 'VARCHAR(255)',
                'popup_text' => 'TEXT',
                'popup_timer_hours' => 'VARCHAR(50)',
                'popup_timer_mins' => 'VARCHAR(50)',
                'popup_timer_secs' => 'VARCHAR(50)',
                'popup_giftbox_img' => 'VARCHAR(255)',
                'popup_std_name' => 'VARCHAR(255)',
                'popup_std_tagline' => 'VARCHAR(255)',
                'popup_std_pill' => 'VARCHAR(255)',
                'popup_std_orig_price' => 'VARCHAR(255)',
                'popup_std_save_tag' => 'VARCHAR(255)',
                'popup_std_price' => 'VARCHAR(255)',
                'popup_std_billed' => 'VARCHAR(255)',
                'popup_std_features' => 'TEXT',
                'popup_std_btn_text' => 'VARCHAR(255)',
                'popup_std_btn_url' => 'VARCHAR(255)',
                'popup_pre_badge' => 'VARCHAR(255)',
                'popup_pre_name' => 'VARCHAR(255)',
                'popup_pre_tagline' => 'VARCHAR(255)',
                'popup_pre_pill' => 'VARCHAR(255)',
                'popup_pre_orig_price' => 'VARCHAR(255)',
                'popup_pre_save_tag' => 'VARCHAR(255)',
                'popup_pre_price' => 'VARCHAR(255)',
                'popup_pre_billed' => 'VARCHAR(255)',
                'popup_pre_free_domain' => 'VARCHAR(255)',
                'popup_pre_features' => 'TEXT',
                'popup_pre_btn_text' => 'VARCHAR(255)',
                'popup_pre_btn_url' => 'VARCHAR(255)',
                'popup_dismiss_text' => 'VARCHAR(255)',
                // Floating Contact Buttons (Call & WhatsApp)
                'floating_call_status' => 'VARCHAR(50)',
                'floating_call_number' => 'VARCHAR(255)',
                'floating_call_label' => 'VARCHAR(255)',
                'floating_whatsapp_status' => 'VARCHAR(50)',
                'floating_whatsapp_number' => 'VARCHAR(255)',
                'floating_whatsapp_header_title' => 'VARCHAR(255)',
                'floating_whatsapp_online_status' => 'VARCHAR(255)',
                'floating_whatsapp_welcome_msg' => 'TEXT',
                'floating_whatsapp_default_send_msg' => 'TEXT',
                'floating_whatsapp_prompt1_text' => 'VARCHAR(255)',
                'floating_whatsapp_prompt1_url' => 'VARCHAR(255)',
                'floating_whatsapp_prompt2_text' => 'VARCHAR(255)',
                'floating_whatsapp_prompt2_msg' => 'TEXT',
                'floating_whatsapp_prompt3_text' => 'VARCHAR(255)',
                'floating_whatsapp_prompt3_url' => 'VARCHAR(255)',
            ];

            $existingCols = Schema::getColumnListing('image_texts');
            $existingMap = array_flip($existingCols);

            foreach ($colsDefinition as $col => $type) {
                if (!isset($existingMap[$col])) {
                    try {
                        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `image_texts` ADD COLUMN `{$col}` {$type} NULL");
                        $existingMap[$col] = true;
                    } catch (\Throwable $e) {
                        try {
                            Schema::table('image_texts', function (Blueprint $table) use ($col, $type) {
                                if (str_contains($type, 'TEXT')) {
                                    $table->text($col)->nullable();
                                } else {
                                    $table->string($col)->nullable();
                                }
                            });
                            $existingMap[$col] = true;
                        } catch (\Throwable $ex) {}
                    }
                }
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Feed and ensure default content from Screenshot 3 (5 Steps) & Screenshot 4 (Dashboard & 4 Cards)
     */
    public static function seedDefaultContent($lang_id): void
    {
        try {
            self::ensureColumnsExist();

            // 1. Ensure ImageText record has Screenshot 3 & Screenshot 4 copy
            $imgText = self::where('language_id', $lang_id)->first();
            $updates = [];

            if (!$imgText || empty($imgText->work_process_section_subtitle)) {
                $updates['work_process_section_subtitle'] = 'SIMPLE STEPS. BIG RESULTS.';
            }
            if (!$imgText || empty($imgText->work_process_section_title)) {
                $updates['work_process_section_title'] = 'Launch your dream store in five simple steps.';
            }
            if (!$imgText || empty($imgText->work_process_btn_text)) {
                $updates['work_process_btn_text'] = 'Start Your Journey';
            }
            if (!$imgText || empty($imgText->work_process_btn_url)) {
                $updates['work_process_btn_url'] = '/pricing';
            }

            if (!$imgText || empty($imgText->features_section_subtitle)) {
                $updates['features_section_subtitle'] = 'EVERYTHING UNDER ONE ROOF';
            }
            if (!$imgText || empty($imgText->features_section_title)) {
                $updates['features_section_title'] = 'Powerful Dashboard Built for Growth';
            }
            if (!$imgText || empty($imgText->features_section_text)) {
                $updates['features_section_text'] = 'From analytics to orders, products to customers, manage every part of your business from one smart dashboard.';
            }
            if (!$imgText || empty($imgText->features_section_btn_text)) {
                $updates['features_section_btn_text'] = 'Explore Dashboard';
            }
            if (!$imgText || empty($imgText->features_section_btn_url)) {
                $updates['features_section_btn_url'] = '/pricing';
            }
            if (!$imgText || empty($imgText->features_bullet1)) {
                $updates['features_bullet1'] = 'Real-time sales & analytics';
            }
            if (!$imgText || empty($imgText->features_bullet2)) {
                $updates['features_bullet2'] = 'Manage products & inventory';
            }
            if (!$imgText || empty($imgText->features_bullet3)) {
                $updates['features_bullet3'] = 'Track orders, payments & shipping';
            }
            if (!$imgText || empty($imgText->features_bullet4)) {
                $updates['features_bullet4'] = 'Customers, coupons & reports';
            }

            // About Us default hero values (Screenshot 5)
            if (!$imgText || empty($imgText->about_hero_badge)) {
                $updates['about_hero_badge'] = 'OUR STORY';
            }
            if (!$imgText || empty($imgText->about_hero_title)) {
                $updates['about_hero_title'] = 'Building the Future of eCommerce';
            }
            if (!$imgText || empty($imgText->about_hero_subtitle)) {
                $updates['about_hero_subtitle'] = 'We empower entrepreneurs worldwide with state-of-the-art tools, high-conversion storefront templates, and lightning-fast web infrastructure to launch and scale their dream stores instantly.';
            }

            // Shops default hero values
            if (!$imgText || empty($imgText->shops_hero_badge)) {
                $updates['shops_hero_badge'] = 'Customer Stores';
            }
            if (!$imgText || empty($imgText->shops_hero_title)) {
                $updates['shops_hero_title'] = 'Discover Successful Stores Built with Ecom Builder';
            }
            if (!$imgText || empty($imgText->shops_hero_subtitle)) {
                $updates['shops_hero_subtitle'] = 'Explore live, high-converting eCommerce stores launched by our merchants across fashion, electronics, groceries, and more.';
            }

            // Templates default hero values
            if (!$imgText || empty($imgText->templates_hero_badge)) {
                $updates['templates_hero_badge'] = 'PREMIUM STORE THEMES';
            }
            if (!$imgText || empty($imgText->templates_hero_title)) {
                $updates['templates_hero_title'] = 'Pick Your Perfect Theme & Launch Today';
            }
            if (!$imgText || empty($imgText->templates_hero_subtitle)) {
                $updates['templates_hero_subtitle'] = 'Explore high-converting, fully responsive templates engineered specifically for eCommerce conversion and speed.';
            }

            // Pricing default hero values
            if (!$imgText || empty($imgText->pricing_hero_badge)) {
                $updates['pricing_hero_badge'] = 'TRANSPARENT PLANS';
            }
            if (!$imgText || empty($imgText->pricing_hero_title)) {
                $updates['pricing_hero_title'] = 'Choose the Perfect Plan for Your Business';
            }
            if (!$imgText || empty($imgText->pricing_hero_subtitle)) {
                $updates['pricing_hero_subtitle'] = 'Everything you need to launch, grow, and scale your online business. No hidden fees, instant activation.';
            }

            // Contact default hero values
            if (!$imgText || empty($imgText->contact_hero_badge)) {
                $updates['contact_hero_badge'] = 'GET IN TOUCH';
            }
            if (!$imgText || empty($imgText->contact_hero_title)) {
                $updates['contact_hero_title'] = 'Get in touch with our team';
            }
            if (!$imgText || empty($imgText->contact_hero_subtitle)) {
                $updates['contact_hero_subtitle'] = 'Have questions about our platform, themes, or custom setup? Our team is here to help you launch smoothly.';
            }

            if (!empty($updates)) {
                self::updateOrInsert(
                    ['unique_id' => 12345, 'language_id' => $lang_id],
                    $updates
                );
            }

            // 2. Feed Screenshot 4: 4 Feature Cards (Analytics & Reports, Inventory Management, Secure Payments, Customer Insights)
            if (Schema::hasTable('features')) {
                $targetCards = [
                    1 => [
                        'title' => 'Analytics & Reports',
                        'text' => 'Make data-driven decisions',
                        'icon' => 'fas fa-chart-pie'
                    ],
                    2 => [
                        'title' => 'Inventory Management',
                        'text' => 'Never run out of stock',
                        'icon' => 'fas fa-boxes'
                    ],
                    3 => [
                        'title' => 'Secure Payments',
                        'text' => 'Track all transactions in one place',
                        'icon' => 'fas fa-shield-alt'
                    ],
                    4 => [
                        'title' => 'Customer Insights',
                        'text' => 'Understand your customers better',
                        'icon' => 'fas fa-user-friends'
                    ]
                ];

                $currentFeatures = Feature::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
                $shouldSeedFeatures = false;

                if ($currentFeatures->count() < 4) {
                    $shouldSeedFeatures = true;
                } else {
                    // Check if it still has old generic titles (like "Easy to Use Dashboard" from Screenshot 1)
                    $firstTitle = strtolower($currentFeatures->first()->title ?? '');
                    if (str_contains($firstTitle, 'easy to use') || str_contains($firstTitle, 'scalable for growth')) {
                        $shouldSeedFeatures = true;
                    }
                }

                if ($shouldSeedFeatures) {
                    Feature::where('language_id', $lang_id)->delete();
                    foreach ($targetCards as $sNum => $c) {
                        Feature::create([
                            'language_id' => $lang_id,
                            'title' => $c['title'],
                            'text' => $c['text'],
                            'icon' => $c['icon'],
                            'serial_number' => $sNum,
                        ]);
                    }
                }
            }

            // 3. Feed Screenshot 3: 5 Simple Steps (Select Your Plan, Choose Your Theme, Customize & Setup, Configure Payments, Launch Your Store)
            if (Schema::hasTable('processes')) {
                $targetSteps = [
                    1 => [
                        'title' => 'Select Your Plan',
                        'text' => 'Choose the perfect pricing plan that fits your business needs.',
                        'icon' => 'fas fa-clipboard-list',
                        'color' => '7c3aed'
                    ],
                    2 => [
                        'title' => 'Choose Your Theme',
                        'text' => 'Pick a professional theme that matches your brand.',
                        'icon' => 'fas fa-store-alt',
                        'color' => 'ea580c'
                    ],
                    3 => [
                        'title' => 'Customize & Setup',
                        'text' => 'Add your products, customize design & settings.',
                        'icon' => 'fas fa-cog',
                        'color' => 'db2777'
                    ],
                    4 => [
                        'title' => 'Configure Payments',
                        'text' => 'Set up payment methods and shipping options.',
                        'icon' => 'fas fa-credit-card',
                        'color' => '16a34a'
                    ],
                    5 => [
                        'title' => 'Launch Your Store',
                        'text' => 'Your store is live! Start selling and growing.',
                        'icon' => 'fas fa-rocket',
                        'color' => '2563eb'
                    ]
                ];

                $currentProcesses = Process::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
                $shouldSeedProcesses = false;

                if ($currentProcesses->count() < 5) {
                    $shouldSeedProcesses = true;
                } else {
                    $firstProcTitle = strtolower($currentProcesses->first()->title ?? '');
                    if (!str_contains($firstProcTitle, 'plan') && !str_contains($firstProcTitle, 'select')) {
                        $shouldSeedProcesses = true;
                    }
                }

                if ($shouldSeedProcesses) {
                    Process::where('language_id', $lang_id)->delete();
                    foreach ($targetSteps as $sNum => $st) {
                        $proc = new Process();
                        $proc->language_id = $lang_id;
                        $proc->title = $st['title'];
                        $proc->text = $st['text'];
                        $proc->icon = $st['icon'];
                        $proc->color = $st['color'];
                        $proc->serial_number = $sNum;
                        $proc->save();
                    }
                }
            }

        } catch (\Throwable $e) {}
    }
}