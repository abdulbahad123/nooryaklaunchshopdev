<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbAgencySetting extends Model
{
    use HasFactory;

    protected $table = 'wb_agency_settings';

    protected $fillable = [
        'customer_id',
        'template_type',
        'site_title',
        'site_logo',
        'top_announcement',
        'email',
        'phone',
        'address',
        'hero_badge',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'primary_btn_text',
        'primary_btn_url',
        'secondary_btn_text',
        'secondary_btn_url',
        'stats_data',
        'services_data',
        'portfolio_data',
        'testimonials_data',
        'about_hero_title',
        'about_hero_subtitle',
        'about_hero_image',
        'story_title',
        'story_text',
        'mission_vision_data',
        'team_members_data',
        'contact_title',
        'contact_subtitle',
        'contact_image',
        'faqs_data',
        'social_links',
        'footer_text',
        'footer_quick_links',
        'footer_legal_links',
        'custom_domain',
        'custom_domain_status',
        'blogs_data',
        'logo_type',
        'header_logo',
        'footer_logo',
        'fare_calculator_data',
        'construction_data',
        'services_badge',
        'services_title',
        'services_subtitle',
        'portfolio_badge',
        'portfolio_title',
        'portfolio_subtitle',
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
        'working_hours',
        'helpline_title',
        'helpline_desc',
        'helpline_btn_text',
        'helpline_btn_url',
        'cta_banner_badge',
        'cta_banner_title',
        'cta_banner_subtitle',
        'cta_banner_btn_text',
        'cta_banner_btn_url',
        'cta_banner_image',
        'team_badge',
        'team_title',
        'team_subtitle',
        'testimonials_badge',
        'testimonials_title',
        'testimonials_subtitle',
        'trust_bar_data',
        'impact_features_data',
        'events_data',
        'contact_bullets_data',
        'header_nav_links',
        'enable_call_btn',
        'call_phone_number',
        'call_btn_position',
        'enable_whatsapp_btn',
        'whatsapp_number',
        'whatsapp_btn_position',
        'whatsapp_default_msg',
    ];

    protected $casts = [
        'stats_data'           => 'array',
        'services_data'        => 'array',
        'portfolio_data'       => 'array',
        'testimonials_data'    => 'array',
        'mission_vision_data'  => 'array',
        'team_members_data'    => 'array',
        'faqs_data'            => 'array',
        'social_links'         => 'array',
        'footer_quick_links'   => 'array',
        'footer_legal_links'   => 'array',
        'blogs_data'           => 'array',
        'fare_calculator_data' => 'array',
        'construction_data'    => 'array',
        'trust_bar_data'       => 'array',
        'impact_features_data' => 'array',
        'events_data'          => 'array',
        'contact_bullets_data' => 'array',
        'header_nav_links'     => 'array',
    ];

    public static function ensureColumnsExist(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                \Illuminate\Support\Facades\Schema::table('wb_agency_settings', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'template_type')) {
                        $table->string('template_type')->default('digital_agency')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'site_title')) {
                        $table->string('site_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'site_logo')) {
                        $table->string('site_logo')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'top_announcement')) {
                        $table->text('top_announcement')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'email')) {
                        $table->string('email')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'phone')) {
                        $table->string('phone')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'address')) {
                        $table->text('address')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'hero_badge')) {
                        $table->string('hero_badge')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'hero_title')) {
                        $table->text('hero_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'hero_subtitle')) {
                        $table->text('hero_subtitle')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'hero_image')) {
                        $table->string('hero_image')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'primary_btn_text')) {
                        $table->string('primary_btn_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'primary_btn_url')) {
                        $table->string('primary_btn_url')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'secondary_btn_text')) {
                        $table->string('secondary_btn_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'secondary_btn_url')) {
                        $table->string('secondary_btn_url')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'stats_data')) {
                        $table->json('stats_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'services_data')) {
                        $table->json('services_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'portfolio_data')) {
                        $table->json('portfolio_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'testimonials_data')) {
                        $table->json('testimonials_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'about_hero_title')) {
                        $table->string('about_hero_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'about_hero_subtitle')) {
                        $table->text('about_hero_subtitle')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'story_title')) {
                        $table->string('story_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'story_text')) {
                        $table->text('story_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'mission_vision_data')) {
                        $table->json('mission_vision_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'team_members_data')) {
                        $table->json('team_members_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'contact_title')) {
                        $table->string('contact_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'contact_subtitle')) {
                        $table->text('contact_subtitle')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'faqs_data')) {
                        $table->json('faqs_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'social_links')) {
                        $table->json('social_links')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'footer_text')) {
                        $table->text('footer_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'blogs_data')) {
                        $table->json('blogs_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'custom_domain')) {
                        $table->string('custom_domain')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'custom_domain_status')) {
                        $table->tinyInteger('custom_domain_status')->default(0);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'footer_quick_links')) {
                        $table->json('footer_quick_links')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'footer_legal_links')) {
                        $table->json('footer_legal_links')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'about_hero_image')) {
                        $table->string('about_hero_image')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'contact_image')) {
                        $table->string('contact_image')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'logo_type')) {
                        $table->string('logo_type')->default('image');
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'header_logo')) {
                        $table->string('header_logo')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'footer_logo')) {
                        $table->string('footer_logo')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'fare_calculator_data')) {
                        $table->json('fare_calculator_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'construction_data')) {
                        $table->json('construction_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'services_badge')) {
                        $table->string('services_badge')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'services_title')) {
                        $table->string('services_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'services_subtitle')) {
                        $table->text('services_subtitle')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'portfolio_badge')) {
                        $table->string('portfolio_badge')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'portfolio_title')) {
                        $table->string('portfolio_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'portfolio_subtitle')) {
                        $table->text('portfolio_subtitle')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'about_primary_btn_text')) {
                        $table->string('about_primary_btn_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'about_primary_btn_url')) {
                        $table->string('about_primary_btn_url')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'about_secondary_btn_text')) {
                        $table->string('about_secondary_btn_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'about_secondary_btn_url')) {
                        $table->string('about_secondary_btn_url')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'mission_title')) {
                        $table->string('mission_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'mission_text')) {
                        $table->text('mission_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'vision_title')) {
                        $table->string('vision_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'vision_text')) {
                        $table->text('vision_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'values_title')) {
                        $table->string('values_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'values_text')) {
                        $table->text('values_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'working_hours')) {
                        $table->text('working_hours')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'helpline_title')) {
                        $table->string('helpline_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'helpline_desc')) {
                        $table->text('helpline_desc')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'helpline_btn_text')) {
                        $table->string('helpline_btn_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'helpline_btn_url')) {
                        $table->string('helpline_btn_url')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'cta_banner_badge')) {
                        $table->string('cta_banner_badge')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'cta_banner_title')) {
                        $table->string('cta_banner_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'cta_banner_subtitle')) {
                        $table->text('cta_banner_subtitle')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'cta_banner_btn_text')) {
                        $table->string('cta_banner_btn_text')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'cta_banner_btn_url')) {
                        $table->string('cta_banner_btn_url')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'cta_banner_image')) {
                        $table->string('cta_banner_image')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'team_badge')) {
                        $table->string('team_badge')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'team_title')) {
                        $table->string('team_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'team_subtitle')) {
                        $table->text('team_subtitle')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'testimonials_badge')) {
                        $table->string('testimonials_badge')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'testimonials_title')) {
                        $table->string('testimonials_title')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'testimonials_subtitle')) {
                        $table->text('testimonials_subtitle')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'trust_bar_data')) {
                        $table->json('trust_bar_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'impact_features_data')) {
                        $table->json('impact_features_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'events_data')) {
                        $table->json('events_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'contact_bullets_data')) {
                        $table->json('contact_bullets_data')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'header_nav_links')) {
                        $table->json('header_nav_links')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'enable_call_btn')) {
                        $table->boolean('enable_call_btn')->default(true);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'call_phone_number')) {
                        $table->string('call_phone_number')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'call_btn_position')) {
                        $table->string('call_btn_position')->default('left');
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'enable_whatsapp_btn')) {
                        $table->boolean('enable_whatsapp_btn')->default(true);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'whatsapp_number')) {
                        $table->string('whatsapp_number')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'whatsapp_btn_position')) {
                        $table->string('whatsapp_btn_position')->default('right');
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'whatsapp_default_msg')) {
                        $table->text('whatsapp_default_msg')->nullable();
                    }
                });
            }
        } catch (\Throwable $e) {}
    }

    public function isFeatureEnabled(string $featureName, $customer = null): bool
    {
        if (!$customer && $this->customer_id) {
            $customer = \App\Models\WebsiteBuilder\WbCustomer::find($this->customer_id);
        }

        if ($customer) {
            $packageId = $customer->package_id;
            if (!$packageId && \Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                $purch = \App\Models\WebsiteBuilder\WbTemplatePurchase::where('customer_email', $customer->email)->latest()->first();
                if ($purch) {
                    if (!empty($purch->package_id)) {
                        $packageId = $purch->package_id;
                    } elseif (!empty($purch->template_name) || !empty($purch->amount)) {
                        // Match tier by amount or tier name if package_id isn't directly populated
                        $amt = (float)$purch->amount;
                        $pkg = \App\Models\WebsiteBuilder\WbPackage::where('monthly_price', $amt)->orWhere('yearly_price', $amt)->first();
                        if ($pkg) {
                            $packageId = $pkg->id;
                        }
                    }
                }
            }

            if (!$packageId) {
                // Default to first tier (Tier 1) if customer is registered
                $firstPkg = \App\Models\WebsiteBuilder\WbPackage::orderBy('id', 'asc')->first();
                if ($firstPkg) {
                    $packageId = $firstPkg->id;
                }
            }

            if ($packageId) {
                $package = \App\Models\WebsiteBuilder\WbPackage::find($packageId);
                if ($package) {
                    switch ($featureName) {
                        case 'contact_form':
                            return (bool) ($package->contact_form_allowed ?? true);
                        case 'map_section':
                            return (bool) ($package->map_section_allowed ?? true);
                        case 'custom_domain':
                            return (bool) ($package->custom_domain_allowed ?? true);
                        case 'call_whatsapp':
                            return (bool) ($package->call_whatsapp_allowed ?? true);
                        case 'blog':
                            return (bool) ($package->blog_allowed ?? true);
                    }
                }
            }
        }

        return true;
    }

    public static function getDemoDefaults($templateType = 'digital_agency'): self
    {
        self::ensureColumnsExist();
        if (!in_array($templateType, ['digital_agency', 'interior', 'texigo', 'construction', 'evently'])) {
            $templateType = 'digital_agency';
        }

        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                $setting = self::where(function($q) {
                    $q->whereNull('customer_id')->orWhere('customer_id', 0);
                })->where('template_type', $templateType)->first();
            }
        } catch (\Throwable $e) {}

        if (!$setting) {
            if ($templateType === 'interior') {
                $setting = self::createInteriorDefaultInstance(null);
            } elseif ($templateType === 'texigo') {
                $setting = self::createTexigoDefaultInstance(null);
            } elseif ($templateType === 'construction') {
                $setting = self::createConstructionDefaultInstance(null);
            } elseif ($templateType === 'evently') {
                $setting = self::createEventlyDefaultInstance(null);
            } else {
                $setting = self::createDefaultInstance(null);
            }
            try {
                $setting->save();
            } catch (\Throwable $e) {}
        }
        return $setting;
    }

    public static function getDefaults($customerId = null): self
    {
        self::ensureColumnsExist();
        if (!$customerId) {
            return self::getDemoDefaults('digital_agency');
        }

        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                $setting = self::where('customer_id', $customerId)->first();

                // If setting exists, verify if customer recently purchased a specific theme (e.g., Evently) that needs auto-syncing
                if ($setting && \Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                    $cust = WbCustomer::find($customerId);
                    if ($cust && !empty($cust->email) && \Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                        $purchase = WbTemplatePurchase::where('customer_email', $cust->email)->latest()->first();
                        if ($purchase && !empty($purchase->template_slug)) {
                            $pslug = strtolower(trim($purchase->template_slug));
                            if (in_array($pslug, ['interior', 'interiorcraft', 'interior_template'])) $pslug = 'interior';
                            elseif (in_array($pslug, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi', 'tex'])) $pslug = 'texigo';
                            elseif (in_array($pslug, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) $pslug = 'construction';
                            elseif (in_array($pslug, ['evently', 'evently_theme', 'event', 'events'])) $pslug = 'evently';
                            else $pslug = 'digital_agency';

                            if ($setting->template_type !== $pslug) {
                                $setting->applyTemplateDefaults($pslug, true);
                                $setting->template_type = $pslug;
                                try { $setting->save(); } catch (\Throwable $ex) {}
                            }
                        }
                    }
                }

                if (!$setting) {
                    $targetTemplate = 'digital_agency';
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                            $cust = WbCustomer::find($customerId);
                            if ($cust) {
                                $sub = strtolower($cust->subdomain ?? '');
                                if (str_contains($sub, 'interior')) {
                                    $targetTemplate = 'interior';
                                } elseif (str_contains($sub, 'texigo') || str_contains($sub, 'taxi')) {
                                    $targetTemplate = 'texigo';
                                } elseif (str_contains($sub, 'construction') || str_contains($sub, 'build')) {
                                    $targetTemplate = 'construction';
                                } elseif (str_contains($sub, 'evently') || str_contains($sub, 'event')) {
                                    $targetTemplate = 'evently';
                                }

                                if (!empty($cust->email) && \Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                                    $purchase = WbTemplatePurchase::where('customer_email', $cust->email)->latest()->first();
                                    if ($purchase && !empty($purchase->template_slug)) {
                                        $pslug = strtolower($purchase->template_slug);
                                        if (in_array($pslug, ['interior', 'interiorcraft', 'interior_template'])) $targetTemplate = 'interior';
                                        elseif (in_array($pslug, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) $targetTemplate = 'texigo';
                                        elseif (in_array($pslug, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) $targetTemplate = 'construction';
                                        elseif (in_array($pslug, ['evently', 'evently_theme', 'event'])) $targetTemplate = 'evently';
                                    }
                                }
                            }
                        }
                    } catch (\Throwable $ex) {}

                    if ($targetTemplate === 'interior') {
                        $setting = self::createInteriorDefaultInstance($customerId);
                    } elseif ($targetTemplate === 'texigo') {
                        $setting = self::createTexigoDefaultInstance($customerId);
                    } elseif ($targetTemplate === 'construction') {
                        $setting = self::createConstructionDefaultInstance($customerId);
                    } elseif ($targetTemplate === 'evently') {
                        $setting = self::createEventlyDefaultInstance($customerId);
                    } else {
                        $demo = self::whereNull('customer_id')->where('template_type', 'digital_agency')->first() ?? self::whereNull('customer_id')->first();
                        if ($demo) {
                            $setting = $demo->replicate();
                            $setting->customer_id = $customerId;
                        } else {
                            $setting = self::createDefaultInstance($customerId);
                        }
                    }
                    try {
                        $setting->save();
                    } catch (\Throwable $ex) {}
                }
            }
        } catch (\Throwable $e) {
            $setting = null;
        }

        if (!$setting) {
            $setting = self::createDefaultInstance($customerId);
        }

        return $setting;
    }

    public static function createDefaultInstance($customerId = null): self
    {
        $setting = new self();
        if ($customerId) {
            $setting->customer_id = $customerId;
            try {
                $cust = WbCustomer::find($customerId);
                if ($cust) {
                    $setting->site_title = $cust->company_name ?: ($cust->name . ' Agency');
                    $setting->email = $cust->email ?: 'info@designagency.com';
                    $setting->phone = $cust->phone ?: '+1 (234) 567-890';
                }
            } catch (\Throwable $e) {}
        }
        if (empty($setting->site_title)) {
            $setting->site_title = 'DesignAGENCY';
        }
        if (empty($setting->email)) {
            $setting->email = 'info@designagency.com';
        }
        if (empty($setting->phone)) {
            $setting->phone = '+1 (234) 567-890';
        }
        $setting->address = '123 Design Street, Creative City, CA 90403';
        $setting->hero_badge = 'Creative Digital Solutions';
        $setting->hero_title = "Increase Your\nCustomers Loyalty\nand Satisfaction";
        $setting->hero_subtitle = 'We help businesses like yours earn more customers, stand out from competitors, and grow your revenue.';
        $setting->hero_image = 'assets/website_builder/Templates/Digital_agency/hero_banner.png';
        $setting->primary_btn_text = 'Get Started';
        $setting->primary_btn_url = '#contact';
        $setting->secondary_btn_text = 'View Our Work';
        $setting->secondary_btn_url = '#portfolio';
        $setting->stats_data = [
            ['number' => '8+',   'label' => 'Years of Experience'],
            ['number' => '120+', 'label' => 'Projects Completed'],
            ['number' => '98%',  'label' => 'Client Satisfaction'],
            ['number' => '24/7', 'label' => 'Support Available'],
        ];
        $setting->services_data = [
            ['icon' => 'fa-laptop-code',     'title' => 'Web Design',       'desc' => 'Beautiful, modern, and responsive websites that drive results.', 'image' => 'assets/website_builder/wb_card_agency.png'],
            ['icon' => 'fa-layer-group',     'title' => 'UI/UX Design',     'desc' => 'User-centered designs that create seamless digital experiences.', 'image' => 'assets/website_builder/wb_card_portfolio.png'],
            ['icon' => 'fa-bezier-curve',    'title' => 'Branding',         'desc' => 'Unique brand identities that make your business memorable.', 'image' => 'assets/website_builder/wb_card_ecommerce.png'],
            ['icon' => 'fa-bullhorn',        'title' => 'Digital Marketing','desc' => 'Data-driven marketing strategies that boost your visibility.', 'image' => 'assets/website_builder/wb_card_startup.png'],
            ['icon' => 'fa-magnifying-glass','title' => 'SEO Optimization', 'desc' => 'Improve your search rankings and drive organic traffic.', 'image' => 'assets/website_builder/wb_card_events.png'],
            ['icon' => 'fa-mobile-screen',   'title' => 'App Development',  'desc' => 'Powerful and scalable apps for iOS & Android platforms.', 'image' => 'assets/website_builder/wb_card_restaurant.png'],
        ];
        $setting->portfolio_data = [
            ['title' => 'Fintech Website Redesign', 'category' => 'Web Design • UI/UX',          'image' => 'assets/website_builder/wb_card_agency.png',    'link' => '#'],
            ['title' => 'E-commerce Website',       'category' => 'Web Design • E-commerce',      'image' => 'assets/website_builder/wb_card_ecommerce.png', 'link' => '#'],
            ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png',   'link' => '#'],
            ['title' => 'Brand Identity Design',    'category' => 'Branding • Graphic Design',   'image' => 'assets/website_builder/wb_card_portfolio.png', 'link' => '#'],
            ['title' => 'Travel Website',           'category' => 'Web Design • UI/UX',          'image' => 'assets/website_builder/wb_card_events.png',    'link' => '#'],
            ['title' => 'Fitness App Design',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png',   'link' => '#'],
            ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design • Web App',      'image' => 'assets/website_builder/wb_card_restaurant.png','link' => '#'],
            ['title' => 'Digital Marketing Campaign','category' => 'Marketing • Social Media',   'image' => 'assets/website_builder/wb_card_agency.png',    'link' => '#'],
            ['title' => 'Restaurant Website',       'category' => 'Web Design • E-commerce',      'image' => 'assets/website_builder/wb_card_ecommerce.png', 'link' => '#'],
        ];
        $setting->testimonials_data = [
            ['name' => 'John Smith',    'role' => 'CEO, Fineva',       'rating' => 5, 'comment' => 'DesignAGENCY transformed our website and brand identity. The team is professional, creative, and results-driven!'],
            ['name' => 'Sarah Johnson', 'role' => 'Marketing Director, Digitech', 'rating' => 5, 'comment' => 'Amazing experience from start to finish. They understood our needs and delivered beyond our expectations.'],
            ['name' => 'David Brown',   'role' => 'Founder, Shopious', 'rating' => 5, 'comment' => 'Their designs are modern, clean, and user-friendly. Our customers love the new experience!'],
        ];
        $setting->about_hero_title = 'We Are A Creative Digital Solutions Agency';
        $setting->about_hero_subtitle = 'We help brands thrive in the digital world through innovative design, smart strategy, and cutting-edge technology.';
        $setting->about_hero_image = 'assets/website_builder/agency_team_meeting.png';
        $setting->story_title = 'Our Journey Started With A Simple Idea';
        $setting->story_text = "DesignAGENCY was founded in 2016 with a mission to empower businesses with smart digital solutions. What began as a small team of creatives has grown into a full-service agency trusted by clients worldwide.\n\nWe believe in building long-term relationships with our clients by delivering measurable results and exceptional experiences.";
        $setting->mission_vision_data = [
            ['title' => 'Our Mission', 'desc' => 'To deliver innovative digital solutions that help businesses grow, connect, and succeed in a competitive world.', 'icon' => 'fa-crosshairs'],
            ['title' => 'Our Vision',  'desc' => 'To be a global leader in digital innovation, known for creativity, reliability, and measurable impact.',  'icon' => 'fa-eye'],
            ['title' => 'Our Values',  'desc' => 'Client Success First, Innovation & Creativity, Integrity & Transparency, Quality & Excellence.',       'icon' => 'fa-gem'],
        ];
        $setting->team_members_data = [
            ['name' => 'Michael Roberts', 'role' => 'Founder & CEO',        'image' => 'assets/website_builder/team_1.jpg'],
            ['name' => 'Sarah Johnson',   'role' => 'Creative Director',     'image' => 'assets/website_builder/team_2.jpg'],
            ['name' => 'Daniel Smith',    'role' => 'Head of Development',  'image' => 'assets/website_builder/team_3.jpg'],
            ['name' => 'Jessica Brown',   'role' => 'Marketing Manager',     'image' => 'assets/website_builder/team_4.jpg'],
        ];
        $setting->contact_title = "Ready to Grow Your Business?";
        $setting->contact_subtitle = "Let's work together to create something amazing for your brand.";
        $setting->contact_image = "assets/website_builder/Templates/Digital_agency/contact_footer.png";
        $setting->faqs_data = [
            ['q' => 'How soon can we start our project?', 'a' => 'Once we understand your requirements, we can typically start within 2–3 business days.'],
            ['q' => 'What information do you need to get started?', 'a' => 'We will need your brand assets, project goals, target audience, and any content guidelines.'],
            ['q' => 'Do you offer ongoing support?', 'a' => 'Yes! We offer comprehensive maintenance, updates, and ongoing digital strategy support.'],
            ['q' => 'How do I know if my project is a good fit?', 'a' => 'Feel free to send us a quick message or book a discovery call, and our team will evaluate your needs!'],
        ];
        $setting->footer_text = 'We are a creative digital agency helping businesses grow with modern design, development & marketing solutions.';
        $setting->footer_quick_links = [
            ['title' => 'Home',       'url' => '#'],
            ['title' => 'About Us',   'url' => '#about'],
            ['title' => 'Portfolio',  'url' => '#portfolio'],
            ['title' => 'Contact Us', 'url' => '#contact'],
        ];
        $setting->footer_legal_links = [
            ['title' => 'Privacy Policy',     'url' => '#privacy'],
            ['title' => 'Terms & Conditions', 'url' => '#terms'],
            ['title' => 'Disclaimer',         'url' => '#disclaimer'],
            ['title' => 'Refund Policy',      'url' => '#refund'],
        ];
        $setting->blogs_data = [
            [
                'id'          => 1,
                'title'       => '10 Modern UI/UX Trends Shaping Digital Products in 2026',
                'category'    => 'Design & Tech',
                'author'      => 'Michael Roberts',
                'date'        => 'Sep 04, 2026',
                'image'       => 'assets/website_builder/wb_card_agency.png',
                'excerpt'     => 'Discover the top design trends driving higher customer engagement and conversions for digital platforms.',
                'content'     => 'In 2026, user experience design continues to evolve at a breakneck pace. Modern audiences expect seamless performance, vibrant dark-mode aesthetics, micro-interactions, and instant accessibility.',
            ],
            [
                'id'          => 2,
                'title'       => 'How Strategic Branding Drives Revenue Growth for Startups',
                'category'    => 'Branding',
                'author'      => 'Sarah Johnson',
                'date'        => 'Aug 28, 2026',
                'image'       => 'assets/website_builder/wb_card_portfolio.png',
                'excerpt'     => 'Learn how a cohesive brand identity instills trust and establishes a strong competitive advantage.',
                'content'     => 'Branding is far more than just a logo or a color scheme. It is the emotional and psychological connection your business establishes with every client.',
            ],
            [
                'id'          => 3,
                'title'       => 'Maximizing Search Visibility with Data-Driven SEO Tactics',
                'category'    => 'SEO & Marketing',
                'author'      => 'Jessica Brown',
                'date'        => 'Aug 15, 2026',
                'image'       => 'assets/website_builder/wb_card_startup.png',
                'excerpt'     => 'A complete guide to optimizing site speed, technical SEO, and organic ranking strategies.',
                'content'     => 'Organic search traffic remains one of the highest-converting marketing channels available today. By focusing on technical site architecture, keyword relevance, and high-quality informative content, businesses can secure reliable long-term visibility.',
            ],
        ];

        return $setting;
    }

    public static function getInteriorDefaults($customerId = null): self
    {
        self::ensureColumnsExist();
        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                if ($customerId) {
                    $setting = self::where('customer_id', $customerId)->where('template_type', 'interior')->first();
                } else {
                    $setting = self::whereNull('customer_id')->where('template_type', 'interior')->first();
                }
            }
        } catch (\Throwable $e) {}

        if (!$setting) {
            $setting = self::createInteriorDefaultInstance($customerId);
            try {
                $setting->save();
            } catch (\Throwable $e) {}
        }
        return $setting;
    }

    public static function createInteriorDefaultInstance($customerId = null): self
    {
        $setting = new self();
        $setting->template_type = 'interior';
        if ($customerId) {
            $setting->customer_id = $customerId;
            try {
                $cust = WbCustomer::find($customerId);
                if ($cust) {
                    $setting->site_title = $cust->company_name ?: ($cust->name . ' Studio');
                    $setting->email = $cust->email ?: 'hello@interiorcraft.com';
                    $setting->phone = $cust->phone ?: '+1 (800) 456-7890';
                }
            } catch (\Throwable $e) {}
        }

        if (empty($setting->site_title)) {
            $setting->site_title = 'InterioCRAFT';
        }
        if (empty($setting->email)) {
            $setting->email = 'hello@interiocraft.com';
        }
        if (empty($setting->phone)) {
            $setting->phone = '+1 (234) 567-890';
        }

        $setting->top_announcement = 'Designing spaces. Creating better lives.';
        $setting->address = '123 Design Street, Creative City, CA 94043';
        $setting->hero_badge = 'Our Home';
        $setting->hero_title = "Spaces We Design,\nStories We Create";
        $setting->hero_subtitle = 'Explore our latest interior design projects and see how we turn ideas into beautiful, functional spaces.';
        $setting->hero_image = 'assets/website_builder/Templates/Interior_agency/homepage_hero.png';
        $setting->about_hero_image = 'assets/website_builder/Templates/Interior_agency/aboutus_hero.png';
        $setting->contact_image = 'assets/website_builder/Templates/Interior_agency/contact_footer.png';
        $setting->header_logo = 'assets/website_builder/Templates/Interior_agency/header_logo.png';
        $setting->footer_logo = 'assets/website_builder/Templates/Interior_agency/footer_logo.png';
        $setting->primary_btn_text = 'Start Your Project';
        $setting->primary_btn_url = '#contact';
        $setting->secondary_btn_text = 'Watch Our Story';
        $setting->secondary_btn_url = '#video';

        $setting->stats_data = [
            ['number' => '250+', 'label' => 'Projects Completed', 'icon' => 'fa-house'],
            ['number' => '98%',  'label' => 'Client Satisfaction',  'icon' => 'fa-star'],
            ['number' => '120+', 'label' => 'Happy Homeowners',   'icon' => 'fa-users'],
        ];

        $setting->services_data = [
            [
                'title' => 'Residential Design',
                'desc'  => 'Bespoke living rooms, luxury master suites, modern kitchens, and private estate interiors.',
                'image' => 'assets/website_builder/Templates/Interior_agency/service_residential.png',
                'icon'  => 'fa-couch'
            ],
            [
                'title' => 'Commercial Architecture',
                'desc'  => 'Sophisticated office spaces, luxury retail boutiques, hospitality suites, and corporate lounges.',
                'image' => 'assets/website_builder/Templates/Interior_agency/service_commercial.png',
                'icon'  => 'fa-building'
            ],
            [
                'title' => 'Space Planning & Layout',
                'desc'  => 'Optimizing spatial ergonomics, natural light flow, structural layouts, and functional zoning.',
                'image' => 'assets/website_builder/Templates/Interior_agency/service_planning.png',
                'icon'  => 'fa-ruler-combined'
            ],
            [
                'title' => 'Custom Furniture & Styling',
                'desc'  => 'Handcrafted timber pieces, curated textiles, custom lighting fixtures, and art curation.',
                'image' => 'assets/website_builder/Templates/Interior_agency/service_styling.png',
                'icon'  => 'fa-pen-ruler'
            ],
            [
                'title' => 'Lighting & Smart Home Design',
                'desc'  => 'Architectural lighting plans, automated ambient controls, and smart space integrations.',
                'image' => 'assets/website_builder/Templates/Interior_agency/service_smart_home.png',
                'icon'  => 'fa-lightbulb'
            ],
            [
                'title' => 'Landscape & Outdoor Living',
                'desc'  => 'Luxury patio concepts, terrace styling, outdoor lounges, and biophilic garden designs.',
                'image' => 'assets/website_builder/Templates/Interior_agency/service_landscape.png',
                'icon'  => 'fa-tree'
            ],
        ];

        $setting->portfolio_data = [
            [
                'title'    => 'Modern Living Room',
                'category' => 'Residential',
                'desc'     => 'A perfect blend of comfort and style.',
                'image'    => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-house'
            ],
            [
                'title'    => 'Elegant Modular Kitchen',
                'category' => 'Residential',
                'desc'     => 'Functional design for modern homes.',
                'image'    => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-utensils'
            ],
            [
                'title'    => 'Modern Office Space',
                'category' => 'Commercial',
                'desc'     => 'Productive spaces for growing businesses.',
                'image'    => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-building'
            ],
            [
                'title'    => 'Luxury Bedroom',
                'category' => 'Residential',
                'desc'     => 'A peaceful retreat for your everyday life.',
                'image'    => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-bed'
            ],
            [
                'title'    => 'Stylish Restaurant',
                'category' => 'Hospitality',
                'desc'     => 'Inviting spaces that leave a lasting impression.',
                'image'    => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-utensils'
            ],
            [
                'title'    => 'Retail Store Design',
                'category' => 'Commercial',
                'desc'     => 'Creative interiors for modern brands.',
                'image'    => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-bag-shopping'
            ],
            [
                'title'    => 'Bathroom Makeover',
                'category' => 'Renovation',
                'desc'     => 'Transforming spaces with elegant details.',
                'image'    => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-shower'
            ],
            [
                'title'    => 'Home Styling',
                'category' => 'Interior Styling',
                'desc'     => 'Thoughtful details that make a difference.',
                'image'    => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-couch'
            ],
            [
                'title'    => 'Outdoor Living Space',
                'category' => 'Space Planning',
                'desc'     => 'Beautiful spaces beyond your walls.',
                'image'    => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=800&auto=format&fit=crop',
                'icon'     => 'fa-tree'
            ],
        ];

        $setting->testimonials_data = [
            [
                'name'    => 'Eleanor Vance',
                'role'    => 'Homeowner, Manhattan Penthouse',
                'comment' => 'InterioCRAFT transformed our raw penthouse shell into a warm, breathtaking sanctuary. Their attention to custom wood detailing and lighting flow is unparalleled.',
                'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'
            ],
            [
                'name'    => 'Marcus Sterling',
                'role'    => 'Founder, Sterling Capital',
                'comment' => 'From initial 3D renderings to final furniture delivery, the execution was flawless. Our corporate headquarters now radiates prestige and ergonomic comfort.',
                'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'
            ],
        ];

        $setting->contact_title = 'Ready to Transform Your Space?';
        $setting->contact_subtitle = "Let's work together to create a space that reflects your style and enhances your everyday life.";
        $setting->footer_text = 'We create beautiful, functional spaces that reflect your style and improve your everyday living.';
        
        $setting->blogs_data = [
            [
                'id'          => 1,
                'title'       => '10 Architectural & Interior Design Trends for 2026',
                'category'    => 'Interior & Styling',
                'author'      => 'Eleanor Vance',
                'date'        => 'Sep 14, 2026',
                'image'       => 'assets/website_builder/Templates/Interior_agency/service_residential.png',
                'excerpt'     => 'Discover spatial layouts, organic textures, and minimalist luxury concepts transforming modern residential homes.',
                'content'     => 'Interior design in 2026 emphasizes spatial harmony, natural lighting, and sustainable timber materials. Combining ergonomic furniture layouts with warm ambient tones creates serene living environments.',
            ],
            [
                'id'          => 2,
                'title'       => 'How Space Planning Enhances Ergonomic Office Environments',
                'category'    => 'Commercial Spatial',
                'author'      => 'Marcus Sterling',
                'date'        => 'Aug 30, 2026',
                'image'       => 'assets/website_builder/Templates/Interior_agency/service_commercial.png',
                'excerpt'     => 'Learn how thoughtful layout zoning and acoustic partitions improve employee productivity and corporate prestige.',
                'content'     => 'Corporate workplace design is shifting towards fluid hybrid spaces. Strategic zoning and acoustic walling balance privacy with collaborative team spaces.',
            ],
            [
                'id'          => 3,
                'title'       => 'Maximizing Natural Light & Mood Lighting in Living Spaces',
                'category'    => 'Lighting Design',
                'author'      => 'InterioCRAFT Design Team',
                'date'        => 'Aug 18, 2026',
                'image'       => 'assets/website_builder/Templates/Interior_agency/service_smart_home.png',
                'excerpt'     => 'A complete guide to architectural lighting layers, warm LED dimming, and window placement.',
                'content'     => 'Lighting dictates spatial mood and visual scale. Layering ambient downlights with accent LED strips brings depth and elegance to any room.',
            ],
        ];

        return $setting;
    }

    public static function getTexigoDefaults($customerId = null): self
    {
        self::ensureColumnsExist();
        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                if ($customerId) {
                    $setting = self::where('customer_id', $customerId)->where('template_type', 'texigo')->first();
                } else {
                    $setting = self::whereNull('customer_id')->where('template_type', 'texigo')->first();
                }
            }
        } catch (\Throwable $e) {}

        if (!$setting) {
            $setting = self::createTexigoDefaultInstance($customerId);
            try {
                $setting->save();
            } catch (\Throwable $e) {}
        }
        return $setting;
    }

    public static function createTexigoDefaultInstance($customerId = null): self
    {
        $setting = new self();
        $setting->template_type = 'texigo';
        if ($customerId) {
            $setting->customer_id = $customerId;
            try {
                $cust = WbCustomer::find($customerId);
                if ($cust) {
                    $setting->site_title = $cust->company_name ?: ($cust->name . ' TaxiGo');
                    $setting->email = $cust->email ?: 'hello@taxigo.com';
                    $setting->phone = $cust->phone ?: '+1 (234) 567-890';
                }
            } catch (\Throwable $e) {}
        }

        if (empty($setting->site_title)) {
            $setting->site_title = 'TaxiGo';
        }
        if (empty($setting->email)) {
            $setting->email = 'hello@taxigo.com';
        }
        if (empty($setting->phone)) {
            $setting->phone = '+1 (234) 567-890';
        }

        $setting->top_announcement = '🚖 #1 Trusted Taxi Service';
        $setting->address = '123 Mobility Way, City Center, NY 10001';
        $setting->hero_badge = '🚖 #1 Trusted Taxi Service';
        $setting->hero_title = "Your Journey\nOur Priority";
        $setting->hero_subtitle = 'Reliable. Safe. Affordable. Get where you need to go with comfort and peace of mind.';
        $setting->hero_image = 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png';
        $setting->about_hero_image = 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png';
        $setting->contact_image = 'https://images.unsplash.com/photo-1511527656417-089b6a6f5d1e?q=80&w=1200&auto=format&fit=crop';
        $setting->header_logo = '';
        $setting->footer_logo = '';
        $setting->primary_btn_text = 'Book Your Ride';
        $setting->primary_btn_url = '#book';
        $setting->secondary_btn_text = 'Explore Services';
        $setting->secondary_btn_url = '#services';

        $setting->stats_data = [
            ['number' => '8+',    'label' => 'Years of Experience',   'icon' => 'fa-users'],
            ['number' => '250K+', 'label' => 'Rides Completed',       'icon' => 'fa-car'],
            ['number' => '98%',   'label' => 'Customer Satisfaction', 'icon' => 'fa-star'],
            ['number' => '50+',   'label' => 'Professional Drivers',  'icon' => 'fa-user-tie'],
        ];

        $setting->services_data = [
            [
                'title' => 'City Rides',
                'desc'  => 'Quick and affordable rides within the city.',
                'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_city_rides.png',
                'icon'  => 'fa-city'
            ],
            [
                'title' => 'Airport Transfers',
                'desc'  => 'On-time pickups and drop-offs for stress-free travel.',
                'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_airport_transfers.png',
                'icon'  => 'fa-plane-departure'
            ],
            [
                'title' => 'Outstation Trips',
                'desc'  => 'Comfortable long-distance rides to any destination.',
                'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_outstation_trips.png',
                'icon'  => 'fa-route'
            ],
            [
                'title' => 'Corporate Travel',
                'desc'  => 'Reliable and executive rides for business professionals.',
                'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_corporate_travel.png',
                'icon'  => 'fa-briefcase'
            ],
            [
                'title' => 'Parcel Delivery',
                'desc'  => 'Fast, express, and secure local delivery service.',
                'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_parcel_delivery.png',
                'icon'  => 'fa-box'
            ],
            [
                'title' => 'Luxury Chauffeur Service',
                'desc'  => 'Premium high-end vehicles with professional chauffeurs for VIP travel.',
                'image' => 'assets/website_builder/Templates/Texigo_agency/service_luxury.png',
                'icon'  => 'fa-user-tie'
            ],
        ];

        $setting->portfolio_data = [
            [
                'title'    => 'Hatchback',
                'category' => 'Economical',
                'desc'     => 'Ideal for solo riders or small quick city commutes.',
                'image'    => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=800&auto=format&fit=crop',
                'seats'    => '4 Seats',
                'bags'     => '2 Bags',
                'icon'     => 'fa-car-side'
            ],
            [
                'title'    => 'Sedan',
                'category' => 'Comfort',
                'desc'     => 'Spacious and smooth rides for daily travel.',
                'image'    => 'https://images.unsplash.com/photo-1550355291-bbee04a92027?q=80&w=800&auto=format&fit=crop',
                'seats'    => '4 Seats',
                'bags'     => '3 Bags',
                'icon'     => 'fa-car'
            ],
            [
                'title'    => 'SUV',
                'category' => 'Family & XL',
                'desc'     => 'Extra space for big families and luggage.',
                'image'    => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=800&auto=format&fit=crop',
                'seats'    => '6 Seats',
                'bags'     => '4 Bags',
                'icon'     => 'fa-truck-monster'
            ],
            [
                'title'    => 'Premium',
                'category' => 'Luxury',
                'desc'     => 'Executive luxury cars for VIP corporate travel.',
                'image'    => 'https://images.unsplash.com/photo-1563720223185-11003d516935?q=80&w=800&auto=format&fit=crop',
                'seats'    => '4 Seats',
                'bags'     => '3 Bags',
                'icon'     => 'fa-car-rear'
            ],
        ];

        $setting->testimonials_data = [
            [
                'name'    => 'Emily Carter',
                'role'    => 'Frequent Traveler',
                'comment' => 'TaxiGo made my airport transfer so easy and stress-free. Drivers are always punctual and polite!',
                'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'
            ],
            [
                'name'    => 'James Walker',
                'role'    => 'Business Executive',
                'comment' => 'Reliable, professional, and affordable. The best taxi mobility service in the city!',
                'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'
            ],
            [
                'name'    => 'Sophia Lee',
                'role'    => 'Regular Customer',
                'comment' => 'Great service and very clean cars. I always choose TaxiGo for my family trips!',
                'avatar'  => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop'
            ],
        ];

        $setting->contact_title = 'Ready to Book Your Next Ride?';
        $setting->contact_subtitle = 'Safe Rides. Happy Journeys. Always.';
        $setting->footer_text = 'Providing safe, reliable, and comfortable transportation for everyone, anytime, anywhere.';

        $setting->fare_calculator_data = [
            'badge'    => 'CAB FARE CALCULATOR',
            'title'    => 'Estimate Your Trip Fare',
            'subtitle' => 'Instant, transparent pricing with no hidden charges. Select your route and vehicle.',
            'vehicles' => [
                ['name' => 'Sedan',     'rate' => 20, 'base_fare' => 50, 'seats' => '4 Seats', 'bags' => '3 Bags', 'icon' => 'fa-car'],
                ['name' => 'SUV',       'rate' => 30, 'base_fare' => 50, 'seats' => '6 Seats', 'bags' => '4 Bags', 'icon' => 'fa-truck-monster'],
                ['name' => 'Premium',   'rate' => 50, 'base_fare' => 50, 'seats' => '4 Seats', 'bags' => '3 Bags', 'icon' => 'fa-crown'],
                ['name' => 'Hatchback', 'rate' => 15, 'base_fare' => 50, 'seats' => '4 Seats', 'bags' => '2 Bags', 'icon' => 'fa-car-side'],
            ],
        ];

        $setting->blogs_data = [
            [
                'id'          => 1,
                'title'       => '5 Essential Safety Tips for Night Cab Rides',
                'category'    => 'Safety & Security',
                'author'      => 'TaxiGo Team',
                'date'        => 'Sep 10, 2026',
                'image'       => 'assets/website_builder/Templates/Texigo_agency/services/service_city_rides.png',
                'excerpt'     => 'Discover how TaxiGo ensures passengers remain safe and secure during late-night city transfers.',
                'content'     => 'Passenger safety is our highest priority. All TaxiGo vehicles undergo weekly safety inspections, GPS route tracking, and vetted background-checked drivers.',
            ],
            [
                'id'          => 2,
                'title'       => 'How to Book Airport Transfers Stress-Free',
                'category'    => 'Travel Guide',
                'author'      => 'Airport Operations',
                'date'        => 'Aug 29, 2026',
                'image'       => 'assets/website_builder/Templates/Texigo_agency/services/service_airport_transfers.png',
                'excerpt'     => 'Plan your flight departures with on-time cab dispatch and transparent luggage capacity estimates.',
                'content'     => 'Never miss a flight again with automated flight-tracking ride dispatching. Our drivers monitor real-time flight arrival times for seamless pickups.',
            ],
            [
                'id'          => 3,
                'title'       => 'Why Electric Vehicles are the Future of Urban Fleet',
                'category'    => 'Mobility Tech',
                'author'      => 'Fleet Manager',
                'date'        => 'Aug 18, 2026',
                'image'       => 'assets/website_builder/Templates/Texigo_agency/services/service_corporate_travel.png',
                'excerpt'     => 'Transitioning to eco-friendly electric rides to reduce carbon footprints and lower ride fares.',
                'content'     => 'Clean mobility is transforming city transit. Electric vehicle fleets deliver whisper-quiet rides while significantly lowering operating costs.',
            ],
        ];
        
        return $setting;
    }

    // =========================================================
    // CONSTRUCTION THEME METHODS
    // =========================================================

    public static function getConstructionDefaults($customerId = null): self
    {
        self::ensureColumnsExist();
        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                if ($customerId) {
                    $setting = self::where('customer_id', $customerId)->where('template_type', 'construction')->first();
                } else {
                    $setting = self::whereNull('customer_id')->where('template_type', 'construction')->first();
                }
            }
        } catch (\Throwable $e) {}

        if (!$setting) {
            $setting = self::createConstructionDefaultInstance($customerId);
            try {
                $setting->save();
            } catch (\Throwable $e) {}
        }
        return $setting;
    }

    public static function createConstructionDefaultInstance($customerId = null): self
    {
        $setting = new self();
        $setting->template_type = 'construction';
        if ($customerId) {
            $setting->customer_id = $customerId;
            try {
                $cust = WbCustomer::find($customerId);
                if ($cust) {
                    $setting->site_title = $cust->company_name ?: ($cust->name . ' Construction');
                    $setting->email = $cust->email ?: 'hello@buildcraft.com';
                    $setting->phone = $cust->phone ?: '+1 (800) BUILD-IT';
                }
            } catch (\Throwable $e) {}
        }

        if (empty($setting->site_title)) $setting->site_title = 'BuildCraft Construction';
        if (empty($setting->email))      $setting->email      = 'info@buildcraft.com';
        if (empty($setting->phone))      $setting->phone      = '+1 (234) 567-890';

        $setting->top_announcement  = '🏗️ Constructing a Better Tomorrow';
        $setting->address           = '123 Construction Avenue, New York, NY 10001';
        $setting->hero_badge        = '🛡️ Trusted Construction Partner';
        $setting->hero_title        = "Building Stronger Futures";
        $setting->hero_subtitle     = 'Reliable construction, renovation, and infrastructure solutions built on quality, safety, and trust. We turn visions into extraordinary spaces.';
        $setting->hero_image        = 'assets/website_builder/Templates/Construction_agency/construction_herobanner.png';
        $setting->about_hero_image  = 'assets/website_builder/Templates/Construction_agency/construction_herobanner.png';
        $setting->contact_image     = 'assets/website_builder/Templates/Construction_agency/construction_footercta.png';
        $setting->header_logo       = 'assets/website_builder/Templates/Construction_agency/header_logo.png';
        $setting->footer_logo       = 'assets/website_builder/Templates/Construction_agency/footer_logo.png';
        $setting->logo_type         = 'image';
        $setting->primary_btn_text  = 'Get a Quote';
        $setting->primary_btn_url   = '#contact';
        $setting->secondary_btn_text = 'Explore Our Work';
        $setting->secondary_btn_url  = '#projects';

        $setting->stats_data = [
            ['number' => '15+',   'label' => 'Years of Experience', 'icon' => 'fa-users'],
            ['number' => '320+',  'label' => 'Projects Completed', 'icon' => 'fa-file-lines'],
            ['number' => '98%',   'label' => 'Client Satisfaction', 'icon' => 'fa-star'],
            ['number' => '24/7',  'label' => 'Project Support',     'icon' => 'fa-headset'],
        ];

        $setting->services_data = [
            [
                'title' => 'Residential Construction',
                'desc'  => 'Dream homes built with precision.',
                'image' => 'assets/website_builder/Templates/Construction_agency/service_residential.png',
                'icon'  => 'fa-house'
            ],
            [
                'title' => 'Commercial Buildings',
                'desc'  => 'Functional spaces for growing businesses.',
                'image' => 'assets/website_builder/Templates/Construction_agency/service_commercial.png',
                'icon'  => 'fa-building'
            ],
            [
                'title' => 'Road & Infrastructure',
                'desc'  => 'Building stronger communities.',
                'image' => 'assets/website_builder/Templates/Construction_agency/service_infra.png',
                'icon'  => 'fa-road'
            ],
            [
                'title' => 'Renovation & Remodeling',
                'desc'  => 'Transforming spaces for a better tomorrow.',
                'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-hammer'
            ],
            [
                'title' => 'Project Management',
                'desc'  => 'On-time. On-budget. Beyond expectations.',
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-clipboard-check'
            ],
            [
                'title' => 'Structural Engineering & Design',
                'desc'  => 'Advanced structural calculations, foundation design, and architectural engineering.',
                'image' => 'assets/website_builder/Templates/Construction_agency/service_structural.png',
                'icon'  => 'fa-drafting-compass'
            ],
        ];

        $setting->portfolio_data = [
            [
                'title'    => 'Skyline Residences',
                'category' => 'Residential',
                'desc'     => 'Luxury residential towers with panoramic city views and eco-friendly design.',
                'image'    => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=800&auto=format&fit=crop',
                'year'     => '2024',
                'location' => 'New York, USA'
            ],
            [
                'title'    => 'Harmony Office Tower',
                'category' => 'Commercial',
                'desc'     => 'Modern 30-story commercial tower featuring smart energy management.',
                'image'    => 'https://images.unsplash.com/photo-1486325212027-8081e485255e?q=80&w=800&auto=format&fit=crop',
                'year'     => '2023',
                'location' => 'Chicago, USA'
            ],
            [
                'title'    => 'Riverside Bridge',
                'category' => 'Infrastructure',
                'desc'     => 'Iconic suspension bridge connecting key transit corridors.',
                'image'    => 'assets/website_builder/Templates/Construction_agency/service_infra.png',
                'year'     => '2023',
                'location' => 'Austin, USA'
            ],
            [
                'title'    => 'Modern Family Villa',
                'category' => 'Luxury',
                'desc'     => 'Bespoke luxury estate with custom architectural finishes and private gardens.',
                'image'    => 'assets/website_builder/Templates/Construction_agency/service_residential.png',
                'year'     => '2022',
                'location' => 'Miami, USA'
            ],
            [
                'title'    => 'GreenTech Factory',
                'category' => 'Industrial',
                'desc'     => 'Sustainable high-tech manufacturing plant built to LEED Gold standards.',
                'image'    => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=800&auto=format&fit=crop',
                'year'     => '2022',
                'location' => 'Dallas, USA'
            ],
        ];

        $setting->testimonials_data = [
            [
                'name'    => 'James Anderson',
                'role'    => 'Commercial Client',
                'comment' => 'BuildCraft made our commercial tower project so easy and stress-free. Highly recommended!',
                'avatar'  => 'assets/website_builder/Templates/Construction_agency/team_1.png'
            ],
            [
                'name'    => 'Sophia Martinez',
                'role'    => 'Project Director',
                'comment' => 'Reliable, affordable, and always on time. The best construction partner in the country!',
                'avatar'  => 'assets/website_builder/Templates/Construction_agency/team_2.png'
            ],
            [
                'name'    => 'Robert Wilson',
                'role'    => 'Real Estate Developer',
                'comment' => 'Professional engineers and excellent project delivery. Truly a great experience!',
                'avatar'  => 'assets/website_builder/Templates/Construction_agency/team_3.png'
            ],
        ];

        $setting->team_members_data = [
            [
                'name'   => 'Michael Carter',
                'role'   => 'Founder & CEO',
                'image'  => 'assets/website_builder/Templates/Construction_agency/team_1.png',
                'social' => ['linkedin' => '#', 'facebook' => '#', 'twitter' => '#', 'instagram' => '#']
            ],
            [
                'name'   => 'Sarah Mitchell',
                'role'   => 'Chief Operating Officer',
                'image'  => 'assets/website_builder/Templates/Construction_agency/team_2.png',
                'social' => ['linkedin' => '#', 'facebook' => '#', 'twitter' => '#', 'instagram' => '#']
            ],
            [
                'name'   => 'David Thompson',
                'role'   => 'Head of Engineering',
                'image'  => 'assets/website_builder/Templates/Construction_agency/team_3.png',
                'social' => ['linkedin' => '#', 'facebook' => '#', 'twitter' => '#', 'instagram' => '#']
            ],
            [
                'name'   => 'Emily Davis',
                'role'   => 'Chief Architect',
                'image'  => 'assets/website_builder/Templates/Construction_agency/team_4.png',
                'social' => ['linkedin' => '#', 'facebook' => '#', 'twitter' => '#', 'instagram' => '#']
            ],
        ];

        $setting->about_hero_title    = 'More Than Just Construction We Build Better Lives';
        $setting->about_hero_subtitle = 'BuildCraft committed to delivering exceptional construction solutions for residential, commercial, and infrastructure projects.';
        $setting->story_title         = 'Building Excellence Since 2008';
        $setting->story_text          = 'BuildCraft was founded with a single mission: to redefine construction standards through safety, precision, and architectural innovation. Today, we stand as an industry leader delivering landmark residential towers, commercial headquarters, and essential infrastructure across the country.';
        $setting->contact_title       = 'Let’s Build Something Great Together';
        $setting->contact_subtitle    = 'Have a project in mind? Contact our engineering and project management team today.';
        $setting->footer_text         = 'We create spaces that inspire, strengthen communities, and build a brighter tomorrow.';

        $setting->construction_data = [
            'project_types' => ['Residential', 'Commercial', 'Infrastructure', 'Luxury', 'Industrial'],
            'specializations' => [
                ['icon' => 'fa-shield-halved', 'title' => 'Safety First',      'desc' => 'Strict zero-hazard safety protocols on all construction sites.'],
                ['icon' => 'fa-award',         'title' => 'Integrity & Transparency', 'desc' => 'Clear contracts, upfront pricing, and open communication.'],
                ['icon' => 'fa-clock',         'title' => 'On-Time Delivery',  'desc' => 'Consistently meeting completion deadlines with precision.'],
                ['icon' => 'fa-star',          'title' => 'Quality in Every Detail', 'desc' => 'Rigorous quality assurance from foundation to final inspection.'],
                ['icon' => 'fa-handshake',     'title' => 'Customer Satisfaction', 'desc' => 'Dedicated project manager for seamless client collaboration.'],
                ['icon' => 'fa-leaf',          'title' => 'Sustainable Growth', 'desc' => 'Eco-friendly building materials and energy-efficient designs.'],
            ],
        ];

        $setting->blogs_data = [
            [
                'id'          => 1,
                'title'       => 'Modern Sustainable Building Materials for 2026',
                'category'    => 'Green Construction',
                'author'      => 'BuildCraft Team',
                'date'        => 'Sep 12, 2026',
                'image'       => 'assets/website_builder/Templates/Construction_agency/service_commercial.png',
                'excerpt'     => 'Exploring eco-friendly concrete, solar roofs, and smart insulation materials for commercial projects.',
                'content'     => 'Sustainable engineering is transforming modern commercial developments. Eco-friendly concrete mixtures and solar cladding reduce energy footprint during construction.',
            ],
            [
                'id'          => 2,
                'title'       => 'Key Steps in Commercial Building Project Management',
                'category'    => 'Project Planning',
                'author'      => 'Lead Engineer',
                'date'        => 'Aug 30, 2026',
                'image'       => 'assets/website_builder/Templates/Construction_agency/service_infra.png',
                'excerpt'     => 'From initial site surveys to structural compliance: how we deliver multi-million projects on schedule.',
                'content'     => 'Strict project milestones, BIM structural modeling, and daily safety inspections ensure complex skyscraper builds finish on time and within budget.',
            ],
            [
                'id'          => 3,
                'title'       => 'Safety Protocols Every Site Supervisor Must Follow',
                'category'    => 'Site Safety',
                'author'      => 'Safety Director',
                'date'        => 'Aug 19, 2026',
                'image'       => 'assets/website_builder/Templates/Construction_agency/service_residential.png',
                'excerpt'     => 'Maintaining zero-accident site safety with equipment checks and daily compliance protocols.',
                'content'     => 'Safety is non-negotiable on any construction job site. Protective gear enforcement and structural scaffolding checks protect crew and visitors.',
            ],
        ];

        return $setting;
    }

    // =========================================================
    // EVENTLY THEME METHODS
    // =========================================================

    public static function getEventlyDefaults($customerId = null): self
    {
        self::ensureColumnsExist();
        $setting = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                if ($customerId) {
                    $setting = self::where('customer_id', $customerId)->where('template_type', 'evently')->first();
                } else {
                    $setting = self::whereNull('customer_id')->where('template_type', 'evently')->first();
                }
            }
        } catch (\Throwable $e) {}

        if (!$setting) {
            $setting = self::createEventlyDefaultInstance($customerId);
            try {
                $setting->save();
            } catch (\Throwable $e) {}
        }
        return $setting;
    }

    public static function createEventlyDefaultInstance($customerId = null): self
    {
        $setting = new self();
        $setting->template_type = 'evently';
        if ($customerId) {
            $setting->customer_id = $customerId;
            try {
                $cust = WbCustomer::find($customerId);
                if ($cust) {
                    $setting->site_title = $cust->company_name ?: ($cust->name . ' Events');
                    $setting->email = $cust->email ?: 'hello@evently.com';
                    $setting->phone = $cust->phone ?: '+1 (800) EVENT-LY';
                }
            } catch (\Throwable $e) {}
        }

        if (empty($setting->site_title)) $setting->site_title = 'Evently';
        if (empty($setting->email))      $setting->email      = 'info@evently.com';
        if (empty($setting->phone))      $setting->phone      = '+1 (234) 567-890';

        $setting->top_announcement  = '🎉 Creating Unforgettable Moments & Celebrations';
        $setting->address           = '789 Celebration Boulevard, Grand City, CA 90210';
        $setting->hero_badge        = '✨ Premium Event Planners';
        $setting->hero_title        = "Crafting Experiences That Inspire";
        $setting->hero_subtitle     = 'From grand corporate galas and luxury weddings to music festivals and summits, we bring your vision to life.';
        $setting->hero_image        = 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1200&auto=format&fit=crop';
        $setting->about_hero_image  = 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=1200&auto=format&fit=crop';
        $setting->contact_image     = 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1200&auto=format&fit=crop';
        $setting->primary_btn_text  = 'Book Consultation';
        $setting->primary_btn_url   = '#contact';
        $setting->secondary_btn_text = 'Explore Events';
        $setting->secondary_btn_url  = '#events';

        $setting->stats_data = [
            ['number' => '500+',  'label' => 'Events Organized', 'icon' => 'fa-calendar-check'],
            ['number' => '100%',  'label' => 'Client Satisfaction', 'icon' => 'fa-heart'],
            ['number' => '15+',   'label' => 'Years Experience', 'icon' => 'fa-trophy'],
            ['number' => '50k+',  'label' => 'Happy Guests', 'icon' => 'fa-face-smile'],
        ];

        $setting->services_data = [
            [
                'title' => 'Corporate Galas & Summits',
                'desc'  => 'Flawless execution for high-profile business conferences and award galas.',
                'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-building-columns'
            ],
            [
                'title' => 'Luxury Weddings',
                'desc'  => 'Bespoke wedding planning, floral design, lighting, and guest experiences.',
                'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-gem'
            ],
            [
                'title' => 'Concerts & Festivals',
                'desc'  => 'Stage production, sound engineering, artist management, and crowd logistics.',
                'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-music'
            ],
            [
                'title' => 'Private Parties & VIP Lounge',
                'desc'  => 'Exclusive birthday bashes, anniversary galas, and VIP private dining.',
                'image' => 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?q=80&w=600&auto=format&fit=crop',
                'icon'  => 'fa-champagne-glasses'
            ],
            [
                'title' => 'Exhibitions & Trade Shows',
                'desc'  => 'Custom booth designs, interactive displays, and high-footfall event coordination.',
                'image' => 'assets/website_builder/Templates/Evently/service_exhibition.png',
                'icon'  => 'fa-display'
            ],
            [
                'title' => 'Catering & Gourmet Dining',
                'desc'  => 'Curated multi-course banquet menus, mixology bars, and gourmet dining experiences.',
                'image' => 'assets/website_builder/Templates/Evently/service_catering.png',
                'icon'  => 'fa-utensils'
            ],
        ];

        $setting->portfolio_data = [
            [
                'title'    => 'Annual Global Tech Summit 2024',
                'category' => 'Corporate',
                'desc'     => '3-day International Technology Conference for 2,500+ attendees.',
                'image'    => 'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=800&auto=format&fit=crop',
                'location' => 'San Francisco, CA'
            ],
            [
                'title'    => 'The Royal Estate Wedding',
                'category' => 'Luxury Wedding',
                'desc'     => 'Opulent outdoor fairy-tale wedding with custom glass marquee and orchid arrangements.',
                'image'    => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop',
                'location' => 'Napa Valley, CA'
            ],
            [
                'title'    => 'Summer Beats Music Fest',
                'category' => 'Festival',
                'desc'     => 'Open-air music festival featuring top international artists and laser show.',
                'image'    => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=800&auto=format&fit=crop',
                'location' => 'Austin, TX'
            ],
        ];

        $setting->testimonials_data = [
            [
                'name'    => 'Victoria Sterling',
                'role'    => 'Bride',
                'comment' => 'Evently made our wedding day absolute perfection. Every detail from ceremony lighting to reception music exceeded our dreams!',
                'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'
            ],
            [
                'name'    => 'Alexander Vance',
                'role'    => 'VP Marketing, Nexus Global',
                'comment' => 'Flawless organization for our corporate summit. The team is professional, creative, and extremely organized.',
                'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'
            ],
        ];

        $setting->about_hero_title    = 'We Turn Extraordinary Concepts Into Unforgettable Reality';
        $setting->about_hero_subtitle = 'Evently is a premier full-service event production and management company delivering iconic moments worldwide.';
        $setting->story_title         = 'A Decade of Creating Magic';
        $setting->story_text          = 'Founded with a passion for creative storytelling and precision logistics, Evently has grown into an industry leader in luxury event production.';
        $setting->contact_title       = 'Ready to Plan Your Next Masterpiece Event?';
        $setting->contact_subtitle    = 'Let’s collaborate to design an extraordinary experience for your guests.';
        $setting->footer_text         = 'Creating memorable events, luxury celebrations, and inspiring experiences worldwide.';

        $setting->blogs_data = [
            [
                'id'          => 1,
                'title'       => '10 Wedding Planning Secrets for an Unforgettable Day',
                'category'    => 'Wedding Tips',
                'author'      => 'Evently Team',
                'date'        => 'Sep 15, 2026',
                'image'       => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop',
                'excerpt'     => 'From venue lighting to live music scheduling: expert tips for seamless luxury weddings.',
                'content'     => 'Planning a dream wedding requires meticulous coordination. Securing top-tier sound, floral arrangements, and guest seating early guarantees peace of mind.',
            ],
            [
                'id'          => 2,
                'title'       => 'How to Host Impactful Corporate Summits & Galas',
                'category'    => 'Corporate Events',
                'author'      => 'Event Director',
                'date'        => 'Aug 28, 2026',
                'image'       => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800&auto=format&fit=crop',
                'excerpt'     => 'Engage stakeholders and attendees with stage production, keynote audio, and live streaming.',
                'content'     => 'Corporate conferences set the tone for company vision. Cutting-edge AV production and interactive attendee lounges deliver memorable brand experiences.',
            ],
            [
                'id'          => 3,
                'title'       => 'Trending Event Decor & Lighting Styles in 2026',
                'category'    => 'Decor & Styling',
                'author'      => 'Creative Stylist',
                'date'        => 'Aug 14, 2026',
                'image'       => 'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?q=80&w=800&auto=format&fit=crop',
                'excerpt'     => 'Discover ambient uplighting, floral arches, and immersive ceiling installations.',
                'content'     => 'Event styling relies on dramatic lighting and tactile floral textures. Custom stage backdrops transform standard banquet halls into magical settings.',
            ],
        ];

        return $setting;
    }

    public function applyTemplateDefaults($templateType = 'digital_agency', $force = false): self
    {
        $templateType = strtolower(trim($templateType));
        if (in_array($templateType, ['interior', 'interiorcraft', 'interior_template', 'interior_agency'])) {
            $templateType = 'interior';
        } elseif (in_array($templateType, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi', 'tex'])) {
            $templateType = 'texigo';
        } elseif (in_array($templateType, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) {
            $templateType = 'construction';
        } elseif (in_array($templateType, ['evently', 'evently_theme', 'event', 'events'])) {
            $templateType = 'evently';
        } elseif (in_array($templateType, ['digital_agency', 'agency', 'design_agency', 'agency_template'])) {
            $templateType = 'digital_agency';
        } else {
            $templateType = 'digital_agency';
        }

        if (!$force && $this->template_type === $templateType && !empty($this->hero_image)) {
            return $this;
        }

        $dummy = null;
        if ($templateType === 'interior') {
            $dummy = self::createInteriorDefaultInstance($this->customer_id);
        } elseif ($templateType === 'texigo') {
            $dummy = self::createTexigoDefaultInstance($this->customer_id);
        } elseif ($templateType === 'construction') {
            $dummy = self::createConstructionDefaultInstance($this->customer_id);
        } elseif ($templateType === 'evently') {
            $dummy = self::createEventlyDefaultInstance($this->customer_id);
        } else {
            $dummy = self::createDefaultInstance($this->customer_id);
        }

        $this->template_type       = $templateType;
        $this->hero_badge          = $dummy->hero_badge;
        $this->hero_title          = $dummy->hero_title;
        $this->hero_subtitle       = $dummy->hero_subtitle;
        $this->hero_image          = $dummy->hero_image;
        $this->about_hero_image    = $dummy->about_hero_image;
        $this->contact_image       = $dummy->contact_image;
        if (!empty($dummy->header_logo)) $this->header_logo = $dummy->header_logo;
        if (!empty($dummy->footer_logo)) $this->footer_logo = $dummy->footer_logo;
        $this->logo_type           = $dummy->logo_type ?? 'image';
        $this->primary_btn_text    = $dummy->primary_btn_text;
        $this->primary_btn_url     = $dummy->primary_btn_url;
        $this->secondary_btn_text  = $dummy->secondary_btn_text;
        $this->secondary_btn_url   = $dummy->secondary_btn_url;
        $this->stats_data          = $dummy->stats_data;
        $this->services_data       = $dummy->services_data;
        $this->portfolio_data      = $dummy->portfolio_data;
        $this->testimonials_data   = $dummy->testimonials_data;
        $this->team_members_data   = $dummy->team_members_data;
        $this->blogs_data          = $dummy->blogs_data;
        $this->about_hero_title    = $dummy->about_hero_title;
        $this->about_hero_subtitle = $dummy->about_hero_subtitle;
        $this->story_title         = $dummy->story_title;
        $this->story_text          = $dummy->story_text;
        $this->contact_title       = $dummy->contact_title;
        $this->contact_subtitle    = $dummy->contact_subtitle;
        $this->footer_text         = $dummy->footer_text;
        if (!empty($dummy->construction_data)) {
            $this->construction_data = $dummy->construction_data;
        }
        if (!empty($dummy->fare_calculator_data)) {
            $this->fare_calculator_data = $dummy->fare_calculator_data;
        }

        return $this;
    }
}
