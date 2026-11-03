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
        if (!Schema::hasTable('wb_agency_settings')) {
            return;
        }

        $columns = [
            'portfolio_badge'            => 'string',
            'portfolio_title'            => 'string',
            'portfolio_subtitle'         => 'text',
            'portfolio_hero_image'       => 'string',
            'portfolio_primary_btn_text' => 'string',
            'portfolio_primary_btn_url'  => 'string',
            'cta_banner_badge'           => 'string',
            'cta_banner_title'           => 'string',
            'cta_banner_subtitle'        => 'text',
            'cta_banner_btn_text'        => 'string',
            'cta_banner_btn_url'         => 'string',
            'cta_banner_image'           => 'string',
            'testimonials_badge'         => 'string',
            'testimonials_title'         => 'string',
            'testimonials_subtitle'      => 'text',
            'working_hours'              => 'text',
            'events_data'                => 'json',
            'footer_quick_links'         => 'json',
            'footer_legal_links'         => 'json',
            'whatsapp_btn_position'      => 'string',
            'whatsapp_default_msg'       => 'text',
            'whatsapp_header_title'      => 'string',
            'whatsapp_popup_message'     => 'text',
            'whatsapp_avatar_image'      => 'string',
            'whatsapp_icon'              => 'string',
            'whatsapp_presets_data'      => 'json',
        ];

        foreach ($columns as $column => $type) {
            try {
                if (!Schema::hasColumn('wb_agency_settings', $column)) {
                    Schema::table('wb_agency_settings', function (Blueprint $table) use ($column, $type) {
                        if ($type === 'text') {
                            $table->text($column)->nullable();
                        } elseif ($type === 'json') {
                            $table->json($column)->nullable();
                        } else {
                            $table->string($column)->nullable();
                        }
                    });
                }
            } catch (\Throwable $e) {
                try {
                    $sqlType = ($type === 'text' || $type === 'json') ? 'TEXT' : 'VARCHAR(255)';
                    DB::statement("ALTER TABLE `wb_agency_settings` ADD COLUMN `{$column}` {$sqlType} NULL");
                } catch (\Throwable $e2) {}
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('wb_agency_settings')) {
            return;
        }

        $columns = [
            'portfolio_badge',
            'portfolio_title',
            'portfolio_subtitle',
            'portfolio_hero_image',
            'portfolio_primary_btn_text',
            'portfolio_primary_btn_url',
            'cta_banner_badge',
            'cta_banner_title',
            'cta_banner_subtitle',
            'cta_banner_btn_text',
            'cta_banner_btn_url',
            'cta_banner_image',
            'testimonials_badge',
            'testimonials_title',
            'testimonials_subtitle',
            'working_hours',
            'events_data',
            'footer_quick_links',
            'footer_legal_links',
            'whatsapp_btn_position',
            'whatsapp_default_msg',
            'whatsapp_header_title',
            'whatsapp_popup_message',
            'whatsapp_avatar_image',
            'whatsapp_icon',
            'whatsapp_presets_data',
        ];

        foreach ($columns as $column) {
            try {
                if (Schema::hasColumn('wb_agency_settings', $column)) {
                    Schema::table('wb_agency_settings', function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                }
            } catch (\Throwable $e) {}
        }
    }
};

