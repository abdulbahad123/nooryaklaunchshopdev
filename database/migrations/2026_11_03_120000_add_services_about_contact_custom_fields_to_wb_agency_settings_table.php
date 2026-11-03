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
        if (!Schema::hasTable('wb_agency_settings')) {
            return;
        }

        $columns = [
            'services_badge'             => 'string',
            'services_title'             => 'string',
            'services_subtitle'          => 'text',
            'about_primary_btn_text'     => 'string',
            'about_primary_btn_url'      => 'string',
            'about_secondary_btn_text'   => 'string',
            'about_secondary_btn_url'    => 'string',
            'mission_title'              => 'string',
            'mission_text'               => 'text',
            'vision_title'               => 'string',
            'vision_text'                => 'text',
            'values_title'               => 'string',
            'values_text'                => 'text',
            'helpline_title'             => 'string',
            'helpline_desc'              => 'text',
            'helpline_btn_text'          => 'string',
            'helpline_btn_url'           => 'string',
            'team_badge'                 => 'string',
            'team_title'                 => 'string',
            'team_subtitle'              => 'text',
            'contact_bullets_data'       => 'json',
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
                // Direct SQL fallback if Schema::table fails or encounters engine restriction
                try {
                    $sqlType = ($type === 'text' || $type === 'json') ? 'TEXT' : 'VARCHAR(255)';
                    \Illuminate\Support\Facades\DB::statement("ALTER TABLE `wb_agency_settings` ADD COLUMN `{$column}` {$sqlType} NULL");
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
            'services_badge',
            'services_title',
            'services_subtitle',
            'about_primary_btn_text',
            'about_primary_btn_url',
            'about_secondary_btn_text',
            'about_secondary_btn_url',
            'mission_title',
            'mission_text',
            'vision_title',
            'vision_text',
            'values_title',
            'values_text',
            'helpline_title',
            'helpline_desc',
            'helpline_btn_text',
            'helpline_btn_url',
            'team_badge',
            'team_title',
            'team_subtitle',
            'contact_bullets_data',
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
