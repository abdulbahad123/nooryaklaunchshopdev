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
            Schema::create('wb_agency_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->string('site_title')->default('DesignAGENCY');
                $table->string('top_announcement')->nullable();
                $table->string('email')->default('info@designagency.com');
                $table->string('phone')->default('+1 (234) 567-890');
                $table->text('address')->nullable();
                $table->string('hero_badge')->nullable();
                $table->text('hero_title')->nullable();
                $table->text('hero_subtitle')->nullable();
                $table->string('hero_image')->nullable();
                $table->string('primary_btn_text')->default('Get Started');
                $table->string('primary_btn_url')->default('#contact');
                $table->string('secondary_btn_text')->default('View Our Work');
                $table->string('secondary_btn_url')->default('#portfolio');
                $table->json('stats_data')->nullable();
                $table->json('services_data')->nullable();
                $table->json('portfolio_data')->nullable();
                $table->json('testimonials_data')->nullable();
                $table->string('about_hero_title')->nullable();
                $table->text('about_hero_subtitle')->nullable();
                $table->string('story_title')->nullable();
                $table->text('story_text')->nullable();
                $table->json('mission_vision_data')->nullable();
                $table->json('team_members_data')->nullable();
                $table->string('contact_title')->nullable();
                $table->text('contact_subtitle')->nullable();
                $table->json('faqs_data')->nullable();
                $table->json('social_links')->nullable();
                $table->text('footer_text')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wb_agency_settings');
    }
};
