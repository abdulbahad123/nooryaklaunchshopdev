<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('image_texts')) {
            $colsDefinition = [
                // Hero Badges
                'hero_badge1_title' => 'text',
                'hero_badge1_subtitle' => 'text',
                'hero_badge2_title' => 'text',
                'hero_badge2_subtitle' => 'text',
                'hero_badge3_title' => 'text',
                'hero_badge3_subtitle' => 'text',
                // Work Process
                'work_process_section_subtitle' => 'text',
                'work_process_image' => 'text',
                'work_process_btn_text' => 'text',
                'work_process_btn_url' => 'text',
                // Features
                'dashboard_image' => 'text',
                'features_bullet1' => 'text',
                'features_bullet2' => 'text',
                'features_bullet3' => 'text',
                'features_bullet4' => 'text',
                // Page Hero Banners
                'about_hero_badge' => 'text',
                'about_hero_title' => 'text',
                'about_hero_subtitle' => 'text',
                'about_hero_image' => 'text',
                'about_hero_btn_text' => 'text',
                'about_hero_btn_url' => 'text',
                'about_hero_video_url' => 'text',
                'shops_hero_badge' => 'text',
                'shops_hero_title' => 'text',
                'shops_hero_subtitle' => 'text',
                'shops_hero_image' => 'text',
                'templates_hero_badge' => 'text',
                'templates_hero_title' => 'text',
                'templates_hero_subtitle' => 'text',
                'templates_hero_image' => 'text',
                'pricing_hero_badge' => 'text',
                'pricing_hero_title' => 'text',
                'pricing_hero_subtitle' => 'text',
                'contact_hero_badge' => 'text',
                'contact_hero_title' => 'text',
                'contact_hero_subtitle' => 'text',
                'contact_hero_image' => 'text',
                // Stores Launched Counter Stats
                'shops_stat1_value' => 'text',
                'shops_stat1_label' => 'text',
                'shops_stat1_icon' => 'text',
                'shops_stat2_value' => 'text',
                'shops_stat2_label' => 'text',
                'shops_stat2_icon' => 'text',
                'shops_stat3_value' => 'text',
                'shops_stat3_label' => 'text',
                'shops_stat3_icon' => 'text',
                'shops_stat4_value' => 'text',
                'shops_stat4_label' => 'text',
                'shops_stat4_icon' => 'text',
                // Stores Launched Trust Points
                'shops_hero_feat1_text' => 'text',
                'shops_hero_feat2_text' => 'text',
                'shops_hero_feat3_text' => 'text',
                // Custom VIP Popup
                'popup_status' => 'text',
                'popup_ribbon_text' => 'text',
                'popup_title' => 'text',
                'popup_text' => 'text',
                'popup_timer_hours' => 'text',
                'popup_timer_mins' => 'text',
                'popup_timer_secs' => 'text',
                'popup_giftbox_img' => 'text',
                'popup_std_name' => 'text',
                'popup_std_tagline' => 'text',
                'popup_std_pill' => 'text',
                'popup_std_orig_price' => 'text',
                'popup_std_save_tag' => 'text',
                'popup_std_price' => 'text',
                'popup_std_billed' => 'text',
                'popup_std_features' => 'text',
                'popup_std_btn_text' => 'text',
                'popup_std_btn_url' => 'text',
                'popup_pre_badge' => 'text',
                'popup_pre_name' => 'text',
                'popup_pre_tagline' => 'text',
                'popup_pre_pill' => 'text',
                'popup_pre_orig_price' => 'text',
                'popup_pre_save_tag' => 'text',
                'popup_pre_price' => 'text',
                'popup_pre_billed' => 'text',
                'popup_pre_free_domain' => 'text',
                'popup_pre_features' => 'text',
                'popup_pre_btn_text' => 'text',
                'popup_pre_btn_url' => 'text',
                'popup_dismiss_text' => 'text',
                // Floating Contact Buttons (Call & WhatsApp)
                'floating_call_status' => 'text',
                'floating_call_number' => 'text',
                'floating_call_label' => 'text',
                'floating_whatsapp_status' => 'text',
                'floating_whatsapp_number' => 'text',
                'floating_whatsapp_header_title' => 'text',
                'floating_whatsapp_online_status' => 'text',
                'floating_whatsapp_welcome_msg' => 'text',
                'floating_whatsapp_default_send_msg' => 'text',
                'floating_whatsapp_prompt1_text' => 'text',
                'floating_whatsapp_prompt1_url' => 'text',
                'floating_whatsapp_prompt2_text' => 'text',
                'floating_whatsapp_prompt2_msg' => 'text',
                'floating_whatsapp_prompt3_text' => 'text',
                'floating_whatsapp_prompt3_url' => 'text',
                // Task 1: Home Hero Counter Section (4 Stats)
                'hero_stat1_value' => 'text',
                'hero_stat1_label' => 'text',
                'hero_stat1_icon' => 'text',
                'hero_stat2_value' => 'text',
                'hero_stat2_label' => 'text',
                'hero_stat2_icon' => 'text',
                'hero_stat3_value' => 'text',
                'hero_stat3_label' => 'text',
                'hero_stat3_icon' => 'text',
                'hero_stat4_value' => 'text',
                'hero_stat4_label' => 'text',
                'hero_stat4_icon' => 'text',
                // Task 2: Work Process Counter Section (4 Bottom Stats)
                'process_stat1_value' => 'text',
                'process_stat1_label' => 'text',
                'process_stat1_icon' => 'text',
                'process_stat2_value' => 'text',
                'process_stat2_label' => 'text',
                'process_stat2_icon' => 'text',
                'process_stat3_value' => 'text',
                'process_stat3_label' => 'text',
                'process_stat3_icon' => 'text',
                'process_stat4_value' => 'text',
                'process_stat4_label' => 'text',
                'process_stat4_icon' => 'text',
                // Task 4: Bottom CTA & Pricing 5 Trust Badges
                'bottom_feat1_text' => 'text',
                'bottom_feat1_icon' => 'text',
                'bottom_feat2_text' => 'text',
                'bottom_feat2_icon' => 'text',
                'bottom_feat3_text' => 'text',
                'bottom_feat3_icon' => 'text',
                'bottom_feat4_text' => 'text',
                'bottom_feat4_icon' => 'text',
                'bottom_feat5_text' => 'text',
                'bottom_feat5_icon' => 'text',
                // Contact Page Support Agent Card (Issue 2)
                'contact_support_title' => 'text',
                'contact_support_subtitle' => 'text',
                'contact_support_btn_text' => 'text',
                'contact_support_btn_number' => 'text',
                'contact_support_btn_msg' => 'text',
            ];

            foreach ($colsDefinition as $col => $type) {
                if (!Schema::hasColumn('image_texts', $col)) {
                    try {
                        Schema::table('image_texts', function (Blueprint $table) use ($col, $type) {
                            if ($type === 'text') {
                                $table->text($col)->nullable();
                            } else {
                                $table->string($col)->nullable();
                            }
                        });
                    } catch (\Throwable $e) {}
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};