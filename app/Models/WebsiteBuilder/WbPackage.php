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
}
