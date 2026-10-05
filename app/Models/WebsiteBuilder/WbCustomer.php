<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class WbCustomer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'wb_customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'company_name',
        'subdomain',
        'custom_domain',
        'package_id',
        'status',
        'extra_portfolio_limit',
        'extra_services_limit',
        'extra_blog_limit',
        'sso_token',
        'sso_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'sso_token',
    ];

    protected $casts = [
        'sso_token_expires_at'  => 'datetime',
        'status'                => 'integer',
        'extra_portfolio_limit' => 'integer',
        'extra_services_limit'  => 'integer',
        'extra_blog_limit'      => 'integer',
    ];

    public static function ensureColumnsExist(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                \Illuminate\Support\Facades\Schema::table('wb_customers', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'extra_portfolio_limit')) {
                        $table->integer('extra_portfolio_limit')->default(0);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'extra_services_limit')) {
                        $table->integer('extra_services_limit')->default(0);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'extra_blog_limit')) {
                        $table->integer('extra_blog_limit')->default(0);
                    }
                });
            }
        } catch (\Throwable $e) {}
    }

    public function getPortfolioLimitAttribute(): int
    {
        $pkgLimit = $this->package ? ($this->package->portfolio_limit ?? 10) : 10;
        return max(1, (int)$pkgLimit + (int)($this->extra_portfolio_limit ?? 0));
    }

    public function getServicesLimitAttribute(): int
    {
        $pkgLimit = $this->package ? ($this->package->services_limit ?? 10) : 10;
        return max(1, (int)$pkgLimit + (int)($this->extra_services_limit ?? 0));
    }

    public function getBlogLimitAttribute(): int
    {
        $pkgLimit = $this->package ? ($this->package->blog_limit ?? 10) : 10;
        return max(1, (int)$pkgLimit + (int)($this->extra_blog_limit ?? 0));
    }

    public function package()
    {
        return $this->belongsTo(WbPackage::class, 'package_id');
    }

    public function agencySetting()
    {
        return $this->hasOne(\App\Models\WebsiteBuilder\WbAgencySetting::class, 'customer_id');
    }

    public function latestPurchase()
    {
        return $this->hasOne(\App\Models\WebsiteBuilder\WbTemplatePurchase::class, 'customer_email', 'email')->latestOfMany();
    }
}
