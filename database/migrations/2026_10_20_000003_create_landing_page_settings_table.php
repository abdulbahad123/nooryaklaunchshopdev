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
        if (!Schema::hasTable('landing_page_settings')) {
            Schema::create('landing_page_settings', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('language_id')->default(1);
                $table->longText('hero_data')->nullable();
                $table->longText('stack_data')->nullable();
                $table->longText('modules_data')->nullable();
                $table->longText('pricing_data')->nullable();
                $table->longText('testimonials_data')->nullable();
                $table->longText('faqs_data')->nullable();
                $table->longText('cta_footer_data')->nullable();
                $table->timestamps();
            });
        }

        // Auto seed
        \App\Models\LandingPageSetting::ensureTableExistsAndSeeded();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_settings');
    }
};