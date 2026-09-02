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
        // 1. wb_landing_settings
        if (!Schema::hasTable('wb_landing_settings')) {
            Schema::create('wb_landing_settings', function (Blueprint $table) {
                $table->id();
                $table->string('hero_badge')->default('⚡ No-coding required');
                $table->string('hero_title')->default('Build Your Website in Just Few Minutes');
                $table->text('hero_subtitle')->nullable();
                $table->string('cta_primary_text')->default('Get Started Free');
                $table->string('cta_primary_url')->default('#pricing');
                $table->string('cta_secondary_text')->default('View Templates');
                $table->string('cta_secondary_url')->default('#templates');
                $table->string('primary_color')->default('#6366f1');
                $table->string('secondary_color')->default('#8b5cf6');
                $table->json('trust_badges')->nullable();
                $table->json('features_data')->nullable();
                $table->json('process_data')->nullable();
                $table->json('testimonials_data')->nullable();
                $table->json('faq_data')->nullable();
                $table->string('contact_email')->default('hello@websitebuilder.com');
                $table->string('contact_phone')->default('+1 (800) 123-4567');
                $table->text('contact_address')->nullable();
                $table->text('footer_text')->nullable();
                $table->text('custom_css')->nullable();
                $table->timestamps();
            });

            // Seed default row
            DB::table('wb_landing_settings')->insert([
                'hero_badge'        => '⚡ No-coding required',
                'hero_title'        => 'Build Your Website in Just Few Minutes',
                'hero_subtitle'     => 'Create beautiful, professional websites in minutes with our intuitive drag-and-drop builder and AI-powered features.',
                'cta_primary_text'  => 'Get Started Free',
                'cta_primary_url'   => '#pricing',
                'cta_secondary_text'=> 'View Templates',
                'cta_secondary_url' => '#templates',
                'primary_color'     => '#6366f1',
                'secondary_color'   => '#8b5cf6',
                'trust_badges'      => json_encode([
                    ['icon' => 'shield-check', 'text' => 'No Technical Skills Required'],
                    ['icon' => 'zap',          'text' => 'Instant Setup'],
                    ['icon' => 'layers',       'text' => '10k+ Business Templates'],
                ]),
                'features_data'     => json_encode([
                    ['icon' => 'smartphone',    'title' => 'Mobile Optimized',       'desc' => 'Looks perfect on every screen size.'],
                    ['icon' => 'search',        'title' => 'SEO Ready',             'desc' => 'Built to rank high on Google search.'],
                    ['icon' => 'globe',         'title' => 'Custom Domain',          'desc' => 'Connect your custom .com domain instantly.'],
                    ['icon' => 'zap',           'title' => 'Fast Hosting',           'desc' => 'Lightning-fast load times globally.'],
                    ['icon' => 'lock',          'title' => 'Secure SSL',             'desc' => 'Free security certificate included.'],
                    ['icon' => 'bar-chart-3',   'title' => 'Analytics',              'desc' => 'Track your visitors and traffic easily.'],
                    ['icon' => 'sparkles',      'title' => 'AI Page Rewriter',      'desc' => 'Regenerate content anytime with AI.'],
                    ['icon' => 'award',         'title' => 'Client-Ready White Label','desc' => 'Create & manage websites under your own brand.'],
                ]),
                'process_data'      => json_encode([
                    ['step' => '01', 'title' => 'Choose a Template', 'desc' => 'Select from our gallery of professionally designed templates.'],
                    ['step' => '02', 'title' => 'Customize Content', 'desc' => 'Use our visual editor to update text, images, and colors.'],
                    ['step' => '03', 'title' => 'Publish to World',  'desc' => 'Connect your custom domain and go live with a single click.'],
                ]),
                'contact_email'     => 'hello@websitebuilder.com',
                'contact_phone'     => '+1 (800) 123-4567',
                'footer_text'       => 'The easiest way to build professional websites. No coding required.',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // 2. wb_templates
        if (!Schema::hasTable('wb_templates')) {
            Schema::create('wb_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('category')->default('Portfolio');
                $table->text('description')->nullable();
                $table->string('preview_image')->default('images/hero-section.png');
                $table->string('demo_url')->nullable();
                $table->decimal('price', 10, 2)->default(0.00);
                $table->boolean('is_free')->default(true);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            // Default templates
            DB::table('wb_templates')->insert([
                [
                    'name' => 'Business Classic',
                    'slug' => 'business-classic',
                    'category' => 'Agency',
                    'description' => 'Professional business website template with clean design.',
                    'price' => 0.00,
                    'is_free' => true,
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Startup Launch',
                    'slug' => 'startup-launch',
                    'category' => 'Startup',
                    'description' => 'Modern startup template with problem-solution structure.',
                    'price' => 49.00,
                    'is_free' => false,
                    'is_active' => true,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Creative Agency',
                    'slug' => 'creative-agency',
                    'category' => 'Portfolio',
                    'description' => 'Bold and creative template for digital agencies and studios.',
                    'price' => 27.00,
                    'is_free' => false,
                    'is_active' => true,
                    'sort_order' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 3. wb_packages
        if (!Schema::hasTable('wb_packages')) {
            Schema::create('wb_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->decimal('monthly_price', 10, 2)->default(0.00);
                $table->decimal('yearly_price', 10, 2)->default(0.00);
                $table->integer('max_websites')->default(1);
                $table->integer('storage_limit_mb')->default(5000);
                $table->boolean('custom_domain_allowed')->default(true);
                $table->boolean('white_label_allowed')->default(false);
                $table->boolean('ai_tools_allowed')->default(true);
                $table->boolean('is_popular')->default(false);
                $table->boolean('is_active')->default(true);
                $table->json('features_list')->nullable();
                $table->timestamps();
            });

            // Default packages
            DB::table('wb_packages')->insert([
                [
                    'name' => 'Starter',
                    'slug' => 'starter',
                    'monthly_price' => 9.00,
                    'yearly_price' => 90.00,
                    'max_websites' => 1,
                    'storage_limit_mb' => 5000,
                    'is_popular' => false,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Pro',
                    'slug' => 'pro',
                    'monthly_price' => 19.00,
                    'yearly_price' => 190.00,
                    'max_websites' => 10,
                    'storage_limit_mb' => 50000,
                    'is_popular' => true,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Business',
                    'slug' => 'business',
                    'monthly_price' => 39.00,
                    'yearly_price' => 390.00,
                    'max_websites' => 100,
                    'storage_limit_mb' => 500000,
                    'is_popular' => false,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 4. wb_customers
        if (!Schema::hasTable('wb_customers')) {
            Schema::create('wb_customers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('password');
                $table->string('company_name')->nullable();
                $table->string('subdomain')->nullable();
                $table->string('custom_domain')->nullable();
                $table->unsignedBigInteger('package_id')->nullable();
                $table->integer('status')->default(1);
                $table->string('sso_token')->nullable();
                $table->timestamp('sso_token_expires_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 5. wb_staff
        if (!Schema::hasTable('wb_staff')) {
            Schema::create('wb_staff', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('role')->default('Support Agent');
                $table->json('permissions')->nullable();
                $table->boolean('is_active')->default(true);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 6. wb_pages
        if (!Schema::hasTable('wb_pages')) {
            Schema::create('wb_pages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->default(1);
                $table->string('title');
                $table->string('slug');
                $table->string('seo_title')->nullable();
                $table->text('seo_description')->nullable();
                $table->boolean('is_home')->default(false);
                $table->boolean('is_published')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 7. wb_sections
        if (!Schema::hasTable('wb_sections')) {
            Schema::create('wb_sections', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('page_id');
                $table->string('type')->default('hero');
                $table->json('content')->nullable();
                $table->json('styles')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_visible')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wb_sections');
        Schema::dropIfExists('wb_pages');
        Schema::dropIfExists('wb_staff');
        Schema::dropIfExists('wb_customers');
        Schema::dropIfExists('wb_packages');
        Schema::dropIfExists('wb_templates');
        Schema::dropIfExists('wb_landing_settings');
    }
};
