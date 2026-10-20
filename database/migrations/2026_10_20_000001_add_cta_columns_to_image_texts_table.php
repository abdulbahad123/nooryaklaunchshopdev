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
            $colsDefinition = array (
  'cta_global_status' => 'string',
  'cta_global_title' => 'string',
  'cta_global_subtitle' => 'text',
  'cta_global_btn_text' => 'string',
  'cta_global_btn_url' => 'string',
  'cta_global_feat1' => 'string',
  'cta_global_feat2' => 'string',
  'cta_global_feat3' => 'string',
  'cta_global_image' => 'string',
  'cta_home_status' => 'string',
  'cta_home_title' => 'string',
  'cta_home_subtitle' => 'text',
  'cta_home_btn_text' => 'string',
  'cta_home_btn_url' => 'string',
  'cta_home_feat1' => 'string',
  'cta_home_feat2' => 'string',
  'cta_home_feat3' => 'string',
  'cta_home_image' => 'string',
  'cta_pricing_status' => 'string',
  'cta_pricing_title' => 'string',
  'cta_pricing_subtitle' => 'text',
  'cta_pricing_btn_text' => 'string',
  'cta_pricing_btn_url' => 'string',
  'cta_pricing_feat1' => 'string',
  'cta_pricing_feat2' => 'string',
  'cta_pricing_feat3' => 'string',
  'cta_pricing_image' => 'string',
  'cta_templates_status' => 'string',
  'cta_templates_title' => 'string',
  'cta_templates_subtitle' => 'text',
  'cta_templates_btn_text' => 'string',
  'cta_templates_btn_url' => 'string',
  'cta_templates_feat1' => 'string',
  'cta_templates_feat2' => 'string',
  'cta_templates_feat3' => 'string',
  'cta_templates_image' => 'string',
  'cta_shops_status' => 'string',
  'cta_shops_title' => 'string',
  'cta_shops_subtitle' => 'text',
  'cta_shops_btn_text' => 'string',
  'cta_shops_btn_url' => 'string',
  'cta_shops_feat1' => 'string',
  'cta_shops_feat2' => 'string',
  'cta_shops_feat3' => 'string',
  'cta_shops_image' => 'string',
  'cta_about_status' => 'string',
  'cta_about_title' => 'string',
  'cta_about_subtitle' => 'text',
  'cta_about_btn_text' => 'string',
  'cta_about_btn_url' => 'string',
  'cta_about_feat1' => 'string',
  'cta_about_feat2' => 'string',
  'cta_about_feat3' => 'string',
  'cta_about_image' => 'string',
  'cta_contact_status' => 'string',
  'cta_contact_title' => 'string',
  'cta_contact_subtitle' => 'text',
  'cta_contact_btn_text' => 'string',
  'cta_contact_btn_url' => 'string',
  'cta_contact_feat1' => 'string',
  'cta_contact_feat2' => 'string',
  'cta_contact_feat3' => 'string',
  'cta_contact_image' => 'string',
);

            Schema::table('image_texts', function (Blueprint $table) use ($colsDefinition) {
                foreach ($colsDefinition as $col => $type) {
                    if (!Schema::hasColumn('image_texts', $col)) {
                        if ($type === 'text') {
                            $table->text($col)->nullable();
                        } else {
                            $table->string($col)->nullable();
                        }
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('image_texts')) {
            $cols = array_keys(array (
  'cta_global_status' => 'string',
  'cta_global_title' => 'string',
  'cta_global_subtitle' => 'text',
  'cta_global_btn_text' => 'string',
  'cta_global_btn_url' => 'string',
  'cta_global_feat1' => 'string',
  'cta_global_feat2' => 'string',
  'cta_global_feat3' => 'string',
  'cta_global_image' => 'string',
  'cta_home_status' => 'string',
  'cta_home_title' => 'string',
  'cta_home_subtitle' => 'text',
  'cta_home_btn_text' => 'string',
  'cta_home_btn_url' => 'string',
  'cta_home_feat1' => 'string',
  'cta_home_feat2' => 'string',
  'cta_home_feat3' => 'string',
  'cta_home_image' => 'string',
  'cta_pricing_status' => 'string',
  'cta_pricing_title' => 'string',
  'cta_pricing_subtitle' => 'text',
  'cta_pricing_btn_text' => 'string',
  'cta_pricing_btn_url' => 'string',
  'cta_pricing_feat1' => 'string',
  'cta_pricing_feat2' => 'string',
  'cta_pricing_feat3' => 'string',
  'cta_pricing_image' => 'string',
  'cta_templates_status' => 'string',
  'cta_templates_title' => 'string',
  'cta_templates_subtitle' => 'text',
  'cta_templates_btn_text' => 'string',
  'cta_templates_btn_url' => 'string',
  'cta_templates_feat1' => 'string',
  'cta_templates_feat2' => 'string',
  'cta_templates_feat3' => 'string',
  'cta_templates_image' => 'string',
  'cta_shops_status' => 'string',
  'cta_shops_title' => 'string',
  'cta_shops_subtitle' => 'text',
  'cta_shops_btn_text' => 'string',
  'cta_shops_btn_url' => 'string',
  'cta_shops_feat1' => 'string',
  'cta_shops_feat2' => 'string',
  'cta_shops_feat3' => 'string',
  'cta_shops_image' => 'string',
  'cta_about_status' => 'string',
  'cta_about_title' => 'string',
  'cta_about_subtitle' => 'text',
  'cta_about_btn_text' => 'string',
  'cta_about_btn_url' => 'string',
  'cta_about_feat1' => 'string',
  'cta_about_feat2' => 'string',
  'cta_about_feat3' => 'string',
  'cta_about_image' => 'string',
  'cta_contact_status' => 'string',
  'cta_contact_title' => 'string',
  'cta_contact_subtitle' => 'text',
  'cta_contact_btn_text' => 'string',
  'cta_contact_btn_url' => 'string',
  'cta_contact_feat1' => 'string',
  'cta_contact_feat2' => 'string',
  'cta_contact_feat3' => 'string',
  'cta_contact_image' => 'string',
));
            Schema::table('image_texts', function (Blueprint $table) use ($cols) {
                foreach ($cols as $col) {
                    if (Schema::hasColumn('image_texts', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};