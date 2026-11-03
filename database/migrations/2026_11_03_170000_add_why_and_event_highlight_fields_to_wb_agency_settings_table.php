<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ensures all Why Evently showcase and Event Highlight card fields exist with direct SQL fallback.
     */
    public function up(): void
    {
        if (!Schema::hasTable('wb_agency_settings')) {
            return;
        }

        $columns = [
            'why_eyebrow'              => ['type' => 'string',   'sql' => "VARCHAR(191) NULL DEFAULT 'WHY CHOOSE EVENTLY'"],
            'why_script_text'          => ['type' => 'string',   'sql' => "VARCHAR(191) NULL DEFAULT 'Your Vision\\nOur Passion'"],
            'why_title'                => ['type' => 'string',   'sql' => "VARCHAR(255) NULL DEFAULT 'More Than Events,\\nWe Create Experiences'"],
            'why_desc'                 => ['type' => 'text',     'sql' => "TEXT NULL"],
            'why_image'                => ['type' => 'string',   'sql' => "VARCHAR(255) NULL"],
            'why_bullets_data'         => ['type' => 'longText', 'sql' => "LONGTEXT NULL"],
            'event_highlight_badge'    => ['type' => 'string',   'sql' => "VARCHAR(191) NULL DEFAULT 'UPCOMING EVENT HIGHLIGHT'"],
            'event_highlight_title'    => ['type' => 'string',   'sql' => "VARCHAR(255) NULL DEFAULT 'Global Business Summit 2024'"],
            'event_highlight_location' => ['type' => 'string',   'sql' => "VARCHAR(191) NULL DEFAULT 'New York, USA'"],
            'event_highlight_date'     => ['type' => 'string',   'sql' => "VARCHAR(191) NULL DEFAULT '24 - 26 Nov 2024'"],
            'event_highlight_desc'     => ['type' => 'text',     'sql' => "TEXT NULL"],
            'event_highlight_btn_text' => ['type' => 'string',   'sql' => "VARCHAR(191) NULL DEFAULT 'Register Now'"],
            'event_highlight_btn_url'  => ['type' => 'string',   'sql' => "VARCHAR(255) NULL DEFAULT '/contact'"],
            'event_highlight_image'    => ['type' => 'string',   'sql' => "VARCHAR(255) NULL"],
            'event_highlight_attendees'=> ['type' => 'string',   'sql' => "VARCHAR(191) NULL DEFAULT '500+ Attendees Expected'"],
        ];

        foreach ($columns as $column => $def) {
            try {
                if (!Schema::hasColumn('wb_agency_settings', $column)) {
                    Schema::table('wb_agency_settings', function (Blueprint $table) use ($column, $def) {
                        $type = $def['type'];
                        if ($type === 'text') {
                            $table->text($column)->nullable();
                        } elseif ($type === 'longText') {
                            $table->longText($column)->nullable();
                        } else {
                            $table->string($column, 255)->nullable();
                        }
                    });
                }
            } catch (\Throwable $e) {
                // Fallback to direct raw SQL ALTER TABLE
                try {
                    $sql = "ALTER TABLE `wb_agency_settings` ADD COLUMN `{$column}` {$def['sql']}";
                    DB::statement($sql);
                } catch (\Throwable $ex) {
                    // Column already exists or error ignored
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

        $cols = [
            'why_eyebrow', 'why_script_text', 'why_title', 'why_desc', 'why_image', 'why_bullets_data',
            'event_highlight_badge', 'event_highlight_title', 'event_highlight_location', 'event_highlight_date',
            'event_highlight_desc', 'event_highlight_btn_text', 'event_highlight_btn_url', 'event_highlight_image',
            'event_highlight_attendees',
        ];

        Schema::table('wb_agency_settings', function (Blueprint $table) use ($cols) {
            foreach ($cols as $col) {
                if (Schema::hasColumn('wb_agency_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
