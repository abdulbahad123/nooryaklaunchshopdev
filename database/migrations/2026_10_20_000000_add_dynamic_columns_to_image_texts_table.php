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
                'hero_badge1_title' => 'string',
                'hero_badge1_subtitle' => 'text',
                'hero_badge2_title' => 'string',
                'hero_badge2_subtitle' => 'text',
                'hero_badge3_title' => 'string',
                'hero_badge3_subtitle' => 'text',
                // Work Process
                'work_process_section_subtitle' => 'text',
                'work_process_image' => 'string',
                'work_process_btn_text' => 'string',
                'work_process_btn_url' => 'string',
                // Features
                'dashboard_image' => 'string',
                'features_bullet1' => 'text',
                'features_bullet2' => 'text',
                'features_bullet3' => 'text',
                'features_bullet4' => 'text',
                // Page Hero Banners
                'about_hero_badge' => 'string',
                'about_hero_title' => 'string',
                'about_hero_subtitle' => 'text',
                'about_hero_image' => 'string',
                'about_hero_btn_text' => 'string',
                'about_hero_btn_url' => 'string',
                'about_hero_video_url' => 'string',
                'shops_hero_badge' => 'string',
                'shops_hero_title' => 'string',
                'shops_hero_subtitle' => 'text',
                'shops_hero_image' => 'string',
                'templates_hero_badge' => 'string',
                'templates_hero_title' => 'string',
                'templates_hero_subtitle' => 'text',
                'templates_hero_image' => 'string',
                'pricing_hero_badge' => 'string',
                'pricing_hero_title' => 'string',
                'pricing_hero_subtitle' => 'text',
                'contact_hero_badge' => 'string',
                'contact_hero_title' => 'string',
                'contact_hero_subtitle' => 'text',
                'contact_hero_image' => 'string',
                // Stores Launched Counter Stats
                'shops_stat1_value' => 'string',
                'shops_stat1_label' => 'string',
                'shops_stat1_icon' => 'string',
                'shops_stat2_value' => 'string',
                'shops_stat2_label' => 'string',
                'shops_stat2_icon' => 'string',
                'shops_stat3_value' => 'string',
                'shops_stat3_label' => 'string',
                'shops_stat3_icon' => 'string',
                'shops_stat4_value' => 'string',
                'shops_stat4_label' => 'string',
                'shops_stat4_icon' => 'string',
                // Stores Launched Trust Points
                'shops_hero_feat1_text' => 'text',
                'shops_hero_feat2_text' => 'text',
                'shops_hero_feat3_text' => 'text',
                // Custom VIP Popup
                'popup_status' => 'string',
                'popup_ribbon_text' => 'string',
                'popup_title' => 'string',
                'popup_text' => 'text',
                'popup_timer_hours' => 'string',
                'popup_timer_mins' => 'string',
                'popup_timer_secs' => 'string',
                'popup_giftbox_img' => 'string',
                'popup_std_name' => 'string',
                'popup_std_tagline' => 'string',
                'popup_std_pill' => 'string',
                'popup_std_orig_price' => 'string',
                'popup_std_save_tag' => 'string',
                'popup_std_price' => 'string',
                'popup_std_billed' => 'string',
                'popup_std_features' => 'text',
                'popup_std_btn_text' => 'string',
                'popup_std_btn_url' => 'string',
                'popup_pre_badge' => 'string',
                'popup_pre_name' => 'string',
                'popup_pre_tagline' => 'string',
                'popup_pre_pill' => 'string',
                'popup_pre_orig_price' => 'string',
                'popup_pre_save_tag' => 'string',
                'popup_pre_price' => 'string',
                'popup_pre_billed' => 'string',
                'popup_pre_free_domain' => 'string',
                'popup_pre_features' => 'text',
                'popup_pre_btn_text' => 'string',
                'popup_pre_btn_url' => 'string',
                'popup_dismiss_text' => 'string',
                // Floating Contact Buttons (Call & WhatsApp)
                'floating_call_status' => 'string',
                'floating_call_number' => 'string',
                'floating_call_label' => 'string',
                'floating_whatsapp_status' => 'string',
                'floating_whatsapp_number' => 'string',
                'floating_whatsapp_header_title' => 'string',
                'floating_whatsapp_online_status' => 'string',
                'floating_whatsapp_welcome_msg' => 'text',
                'floating_whatsapp_default_send_msg' => 'text',
                'floating_whatsapp_prompt1_text' => 'string',
                'floating_whatsapp_prompt1_url' => 'string',
                'floating_whatsapp_prompt2_text' => 'string',
                'floating_whatsapp_prompt2_msg' => 'text',
                'floating_whatsapp_prompt3_text' => 'string',
                'floating_whatsapp_prompt3_url' => 'string',
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