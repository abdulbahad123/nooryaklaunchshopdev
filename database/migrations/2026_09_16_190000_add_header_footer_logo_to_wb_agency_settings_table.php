<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('wb_agency_settings')) {
            Schema::table('wb_agency_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('wb_agency_settings', 'header_logo')) {
                    $table->string('header_logo')->nullable();
                }
                if (!Schema::hasColumn('wb_agency_settings', 'footer_logo')) {
                    $table->string('footer_logo')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('wb_agency_settings')) {
            Schema::table('wb_agency_settings', function (Blueprint $table) {
                if (Schema::hasColumn('wb_agency_settings', 'header_logo')) {
                    $table->dropColumn('header_logo');
                }
                if (Schema::hasColumn('wb_agency_settings', 'footer_logo')) {
                    $table->dropColumn('footer_logo');
                }
            });
        }
    }
};
