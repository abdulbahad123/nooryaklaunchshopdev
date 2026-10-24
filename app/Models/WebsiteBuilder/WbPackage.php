<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbPackage extends Model
{
    use HasFactory;

    protected $table = 'wb_packages';

    protected $fillable = [
        'name',
        'slug',
        'monthly_price',
        'yearly_price',
        'max_websites',
        'theme_limit',
        'storage_limit_mb',
        'contact_form_allowed',
        'map_section_allowed',
        'custom_domain_allowed',
        'call_whatsapp_allowed',
        'blog_allowed',
        'white_label_allowed',
        'ai_tools_allowed',
        'portfolio_limit',
        'services_limit',
        'blog_limit',
        'is_popular',
        'is_active',
        'features_list',
    ];

    protected $casts = [
        'monthly_price'         => 'float',
        'yearly_price'          => 'float',
        'max_websites'          => 'integer',
        'theme_limit'           => 'integer',
        'storage_limit_mb'      => 'integer',
        'contact_form_allowed'  => 'boolean',
        'map_section_allowed'    => 'boolean',
        'custom_domain_allowed' => 'boolean',
        'call_whatsapp_allowed' => 'boolean',
        'blog_allowed'          => 'boolean',
        'white_label_allowed'   => 'boolean',
        'ai_tools_allowed'      => 'boolean',
        'portfolio_limit'       => 'integer',
        'services_limit'        => 'integer',
        'blog_limit'           => 'integer',
        'is_popular'            => 'boolean',
        'is_active'             => 'boolean',
        'features_list'         => 'array',
    ];

    public function getPortfolioLimitAttribute($value)
    {
        $default = 100;
        try {
            $landing = \App\Models\WebsiteBuilder\WbLandingSetting::first();
            if ($landing && $landing->default_portfolio_limit !== null && $landing->default_portfolio_limit !== '') {
                $default = (int)$landing->default_portfolio_limit;
            }
        } catch (\Throwable $e) {}

        if ($value === null || $value === '') {
            return $default;
        }
        return (int)$value;
    }

    public function getServicesLimitAttribute($value)
    {
        $default = 100;
        try {
            $landing = \App\Models\WebsiteBuilder\WbLandingSetting::first();
            if ($landing && $landing->default_services_limit !== null && $landing->default_services_limit !== '') {
                $default = (int)$landing->default_services_limit;
            }
        } catch (\Throwable $e) {}

        if ($value === null || $value === '') {
            return $default;
        }
        return (int)$value;
    }

    public function getBlogLimitAttribute($value)
    {
        $default = 300;
        try {
            $landing = \App\Models\WebsiteBuilder\WbLandingSetting::first();
            if ($landing && $landing->default_blog_limit !== null && $landing->default_blog_limit !== '') {
                $default = (int)$landing->default_blog_limit;
            }
        } catch (\Throwable $e) {}

        if ($value === null || $value === '') {
            return $default;
        }
        return (int)$value;
    }

    public static function ensureColumnsExist(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_packages')) {
                \Illuminate\Support\Facades\Schema::table('wb_packages', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_packages', 'theme_limit')) {
                        $table->integer('theme_limit')->default(10)->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_packages', 'contact_form_allowed')) {
                        $table->boolean('contact_form_allowed')->default(true);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_packages', 'map_section_allowed')) {
                        $table->boolean('map_section_allowed')->default(true);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_packages', 'call_whatsapp_allowed')) {
                        $table->boolean('call_whatsapp_allowed')->default(true);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_packages', 'blog_allowed')) {
                        $table->boolean('blog_allowed')->default(true);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_packages', 'portfolio_limit')) {
                        $table->integer('portfolio_limit')->default(10)->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_packages', 'services_limit')) {
                        $table->integer('services_limit')->default(10)->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_packages', 'blog_limit')) {
                        $table->integer('blog_limit')->default(10)->nullable();
                    }
                });
            }
        } catch (\Throwable $e) {}
    }

    public static function ensureDefaultPackages(): void
    {
        self::ensureColumnsExist();
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('wb_packages')) {
                return;
            }

            // Ensure Starter exists and is active
            $starter = self::whereRaw('LOWER(name) LIKE ?', ['%starter%'])->first();
            if (!$starter) {
                $starter = self::create([
                    'name' => 'Starter',
                    'slug' => 'starter',
                    'monthly_price' => 299,
                    'yearly_price' => 2990,
                    'portfolio_limit' => 20,
                    'services_limit' => 100,
                    'blog_limit' => 300,
                    'is_active' => true,
                ]);
            } else {
                $starter->is_active = true;
                $starter->save();
            }

            $starterMonthly = $starter ? (float)$starter->monthly_price : 299;
            $starterYearly  = $starter ? (float)$starter->yearly_price : 2990;

            // Ensure Agency Pro exists and is strictly priced higher than Starter
            $pro = self::whereRaw('LOWER(name) LIKE ?', ['%pro%'])->first();
            $proMonthly = max(499, $starterMonthly + 200);
            $proYearly  = max(4990, $starterYearly + 2000);
            if (!$pro) {
                $pro = self::create([
                    'name' => 'Agency Pro',
                    'slug' => 'agency-pro',
                    'monthly_price' => $proMonthly,
                    'yearly_price' => $proYearly,
                    'portfolio_limit' => 50,
                    'services_limit' => 100,
                    'blog_limit' => 300,
                    'is_active' => true,
                    'is_popular' => true,
                ]);
            } else {
                if ((float)$pro->monthly_price <= $starterMonthly) {
                    $pro->monthly_price = $proMonthly;
                    $pro->yearly_price  = $proYearly;
                }
                $pro->is_active = true;
                $pro->save();
            }

            // Ensure Enterprise Unlimited exists and is strictly priced higher than Pro
            $currProMonthly = (float)$pro->monthly_price;
            $currProYearly  = (float)$pro->yearly_price;
            $entMonthly = max(799, $currProMonthly + 300);
            $entYearly  = max(7990, $currProYearly + 3000);

            $enterprise = self::whereRaw('LOWER(name) LIKE ? OR LOWER(name) LIKE ?', ['%enterprise%', '%unlimited%'])->first();
            if (!$enterprise) {
                self::create([
                    'name' => 'Enterprise Unlimited',
                    'slug' => 'enterprise-unlimited',
                    'monthly_price' => $entMonthly,
                    'yearly_price' => $entYearly,
                    'portfolio_limit' => 100,
                    'services_limit' => 100,
                    'blog_limit' => 300,
                    'is_active' => true,
                ]);
            } else {
                if ((float)$enterprise->monthly_price <= $currProMonthly) {
                    $enterprise->monthly_price = $entMonthly;
                    $enterprise->yearly_price  = $entYearly;
                }
                $enterprise->is_active = true;
                $enterprise->save();
            }
        } catch (\Throwable $e) {}
    }
}
