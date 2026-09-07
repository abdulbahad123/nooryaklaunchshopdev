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
        if (Schema::hasTable('wb_agency_settings')) {
            Schema::table('wb_agency_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('wb_agency_settings', 'footer_quick_links')) {
                    $table->json('footer_quick_links')->nullable();
                }
                if (!Schema::hasColumn('wb_agency_settings', 'footer_legal_links')) {
                    $table->json('footer_legal_links')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('wb_agency_settings')) {
            Schema::table('wb_agency_settings', function (Blueprint $table) {
                if (Schema::hasColumn('wb_agency_settings', 'footer_quick_links')) {
                    $table->dropColumn('footer_quick_links');
                }
                if (Schema::hasColumn('wb_agency_settings', 'footer_legal_links')) {
                    $table->dropColumn('footer_legal_links');
                }
            });
        }
    }
};
