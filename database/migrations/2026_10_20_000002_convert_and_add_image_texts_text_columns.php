<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('image_texts')) {
            $allCols = [
                // Hero Badges
                'hero_badge1_title', 'hero_badge1_subtitle',
                'hero_badge2_title', 'hero_badge2_subtitle',
                'hero_badge3_title', 'hero_badge3_subtitle',
                // Work Process
                'work_process_section_subtitle', 'work_process_image',
                'work_process_btn_text', 'work_process_btn_url',
                // Features
                'dashboard_image',
                'features_bullet1', 'features_bullet2', 'features_bullet3', 'features_bullet4',
                // Page Hero Banners
                'about_hero_badge', 'about_hero_title', 'about_hero_subtitle', 'about_hero_image', 'about_hero_btn_text', 'about_hero_btn_url', 'about_hero_video_url',
                'shops_hero_badge', 'shops_hero_title', 'shops_hero_subtitle', 'shops_hero_image',
                'templates_hero_badge', 'templates_hero_title', 'templates_hero_subtitle', 'templates_hero_image',
                'pricing_hero_badge', 'pricing_hero_title', 'pricing_hero_subtitle',
                'contact_hero_badge', 'contact_hero_title', 'contact_hero_subtitle', 'contact_hero_image',
                // Stores Launched Counter Stats
                'shops_stat1_value', 'shops_stat1_label', 'shops_stat1_icon',
                'shops_stat2_value', 'shops_stat2_label', 'shops_stat2_icon',
                'shops_stat3_value', 'shops_stat3_label', 'shops_stat3_icon',
                'shops_stat4_value', 'shops_stat4_label', 'shops_stat4_icon',
                // Stores Launched Trust Points
                'shops_hero_feat1_text', 'shops_hero_feat2_text', 'shops_hero_feat3_text',
                // Custom VIP Popup
                'popup_status', 'popup_ribbon_text', 'popup_title', 'popup_text',
                'popup_timer_hours', 'popup_timer_mins', 'popup_timer_secs', 'popup_giftbox_img',
                'popup_std_name', 'popup_std_tagline', 'popup_std_pill', 'popup_std_orig_price', 'popup_std_save_tag', 'popup_std_price', 'popup_std_billed', 'popup_std_features', 'popup_std_btn_text', 'popup_std_btn_url',
                'popup_pre_badge', 'popup_pre_name', 'popup_pre_tagline', 'popup_pre_pill', 'popup_pre_orig_price', 'popup_pre_save_tag', 'popup_pre_price', 'popup_pre_billed', 'popup_pre_free_domain', 'popup_pre_features', 'popup_pre_btn_text', 'popup_pre_btn_url', 'popup_dismiss_text',
                // Floating Contact Buttons (Call & WhatsApp)
                'floating_call_status', 'floating_call_number', 'floating_call_label',
                'floating_whatsapp_status', 'floating_whatsapp_number', 'floating_whatsapp_header_title',
                'floating_whatsapp_online_status', 'floating_whatsapp_welcome_msg', 'floating_whatsapp_default_send_msg',
                'floating_whatsapp_prompt1_text', 'floating_whatsapp_prompt1_url',
                'floating_whatsapp_prompt2_text', 'floating_whatsapp_prompt2_msg',
                'floating_whatsapp_prompt3_text', 'floating_whatsapp_prompt3_url',
                // Task 1: Home Hero Counter Section (4 Stats)
                'hero_stat1_value', 'hero_stat1_label', 'hero_stat1_icon',
                'hero_stat2_value', 'hero_stat2_label', 'hero_stat2_icon',
                'hero_stat3_value', 'hero_stat3_label', 'hero_stat3_icon',
                'hero_stat4_value', 'hero_stat4_label', 'hero_stat4_icon',
                // Task 2: Work Process Counter Section (4 Bottom Stats)
                'process_stat1_value', 'process_stat1_label', 'process_stat1_icon',
                'process_stat2_value', 'process_stat2_label', 'process_stat2_icon',
                'process_stat3_value', 'process_stat3_label', 'process_stat3_icon',
                'process_stat4_value', 'process_stat4_label', 'process_stat4_icon',
                // Task 4: Bottom CTA & Pricing 5 Trust Badges
                'bottom_feat1_text', 'bottom_feat1_icon',
                'bottom_feat2_text', 'bottom_feat2_icon',
                'bottom_feat3_text', 'bottom_feat3_icon',
                'bottom_feat4_text', 'bottom_feat4_icon',
                'bottom_feat5_text', 'bottom_feat5_icon',
                // Contact Page Support Agent Card (Issue 2)
                'contact_support_title', 'contact_support_subtitle', 'contact_support_btn_text',
                'contact_support_btn_number', 'contact_support_btn_msg',
            ];

            $existingCols = Schema::getColumnListing('image_texts');
            $existingMap = array_flip($existingCols);

            // 1. Modify existing dynamic columns to TEXT NULL to eliminate row size bloat
            foreach ($allCols as $col) {
                if (isset($existingMap[$col])) {
                    try {
                        DB::statement("ALTER TABLE `image_texts` MODIFY COLUMN `{$col}` TEXT NULL");
                    } catch (\Throwable $e) {}
                }
            }

            // 2. Add any missing columns as TEXT NULL
            foreach ($allCols as $col) {
                if (!isset($existingMap[$col])) {
                    try {
                        DB::statement("ALTER TABLE `image_texts` ADD COLUMN `{$col}` TEXT NULL");
                        $existingMap[$col] = true;
                    } catch (\Throwable $e) {
                        try {
                            Schema::table('image_texts', function (Blueprint $table) use ($col) {
                                $table->text($col)->nullable();
                            });
                            $existingMap[$col] = true;
                        } catch (\Throwable $ex) {}
                    }
                }
            }
        }

        // Clean up global CTA row and migrate data to specific pages if needed
        try {
            if (Schema::hasTable('page_ctas')) {
                $globalRow = DB::table('page_ctas')->where('page', 'global')->first();
                if ($globalRow) {
                    foreach (['home', 'pricing', 'templates', 'shops', 'about', 'contact'] as $p) {
                        $existingP = DB::table('page_ctas')->where('page', $p)->first();
                        if (!$existingP && !empty($globalRow->title)) {
                            DB::table('page_ctas')->insert([
                                'language_id' => $globalRow->language_id ?? 1,
                                'page'        => $p,
                                'status'      => $globalRow->status ?? '1',
                                'title'       => $globalRow->title,
                                'subtitle'    => $globalRow->subtitle,
                                'btn_text'    => $globalRow->btn_text,
                                'btn_url'     => $globalRow->btn_url,
                                'feat1'       => $globalRow->feat1,
                                'feat2'       => $globalRow->feat2,
                                'feat3'       => $globalRow->feat3,
                                'image'       => $globalRow->image,
                                'created_at'  => now(),
                                'updated_at'  => now(),
                            ]);
                        }
                    }
                    DB::table('page_ctas')->where('page', 'global')->delete();
                }
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};