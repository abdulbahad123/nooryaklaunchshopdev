<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ensures impact_features_data, trust_bar_data, and floating action button settings
     * exist with direct SQL fallback on all environments and whitelabel tenant databases.
     */
    public function up(): void
    {
        if (!Schema::hasTable('wb_agency_settings')) {
            return;
        }

        $columns = [
            'impact_features_data'   => ['type' => 'longText', 'sql' => 'LONGTEXT NULL'],
            'trust_bar_data'         => ['type' => 'longText', 'sql' => 'LONGTEXT NULL'],
            'call_phone_number'      => ['type' => 'string',   'sql' => 'VARCHAR(191) NULL'],
            'call_btn_position'      => ['type' => 'string',   'sql' => "VARCHAR(50) NULL DEFAULT 'left'"],
            'enable_call_btn'        => ['type' => 'boolean',  'sql' => 'TINYINT(1) NOT NULL DEFAULT 1'],
            'whatsapp_number'        => ['type' => 'string',   'sql' => 'VARCHAR(191) NULL'],
            'whatsapp_btn_position'  => ['type' => 'string',   'sql' => "VARCHAR(50) NULL DEFAULT 'right'"],
            'enable_whatsapp_btn'    => ['type' => 'boolean',  'sql' => 'TINYINT(1) NOT NULL DEFAULT 1'],
            'whatsapp_default_msg'   => ['type' => 'text',     'sql' => 'TEXT NULL'],
            'whatsapp_header_title'  => ['type' => 'string',   'sql' => 'VARCHAR(191) NULL'],
            'whatsapp_popup_message' => ['type' => 'text',     'sql' => 'TEXT NULL'],
            'whatsapp_icon'          => ['type' => 'string',   'sql' => "VARCHAR(100) NULL DEFAULT 'fa-brands fa-whatsapp'"],
            'whatsapp_avatar_image'  => ['type' => 'string',   'sql' => 'VARCHAR(191) NULL'],
            'whatsapp_presets_data'  => ['type' => 'longText', 'sql' => 'LONGTEXT NULL'],
        ];

        foreach ($columns as $column => $def) {
            try {
                if (!Schema::hasColumn('wb_agency_settings', $column)) {
                    Schema::table('wb_agency_settings', function (Blueprint $table) use ($column, $def) {
                        $type = $def['type'];
                        if ($type === 'boolean') {
                            $table->boolean($column)->default(true);
                        } elseif ($type === 'text') {
                            $table->text($column)->nullable();
                        } elseif ($type === 'longText') {
                            $table->longText($column)->nullable();
                        } else {
                            $table->string($column, 191)->nullable();
                        }
                    });
                }
            } catch (\Throwable $e) {
                // Raw SQL fallback for MySQL/MariaDB
                try {
                    $sql = "ALTER TABLE wb_agency_settings ADD COLUMN `{$column}` " . $def['sql'];
                    DB::statement($sql);
                } catch (\Throwable $inner) {
                    // Already exists or unsupported engine
                }
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
            'impact_features_data',
            'trust_bar_data',
            'call_phone_number',
            'call_btn_position',
            'enable_call_btn',
            'whatsapp_number',
            'whatsapp_btn_position',
            'enable_whatsapp_btn',
            'whatsapp_default_msg',
            'whatsapp_header_title',
            'whatsapp_popup_message',
            'whatsapp_icon',
            'whatsapp_avatar_image',
            'whatsapp_presets_data',
        ];

        foreach ($columns as $col) {
            try {
                if (Schema::hasColumn('wb_agency_settings', $col)) {
                    Schema::table('wb_agency_settings', function (Blueprint $table) use ($col) {
                        $table->dropColumn($col);
                    });
                }
            } catch (\Throwable $e) {}
        }
    }
};
