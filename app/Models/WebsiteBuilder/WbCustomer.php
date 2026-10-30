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
        'reset_token',
        'reset_token_expires_at',
        'company_name',
        'subdomain',
        'custom_domain',
        'package_id',
        'expire_date',
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
        'reset_token',
        'sso_token',
    ];

    protected $casts = [
        'sso_token_expires_at'   => 'datetime',
        'reset_token_expires_at' => 'datetime',
        'status'                 => 'integer',
        'extra_portfolio_limit'  => 'integer',
        'extra_services_limit'   => 'integer',
        'extra_blog_limit'       => 'integer',
    ];

    public static function ensureColumnsExist(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                \Illuminate\Support\Facades\Schema::table('wb_customers', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'phone')) {
                        $table->string('phone')->nullable()->after('email');
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'reset_token')) {
                        $table->string('reset_token')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'reset_token_expires_at')) {
                        $table->dateTime('reset_token_expires_at')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'extra_portfolio_limit')) {
                        $table->integer('extra_portfolio_limit')->default(0);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'extra_services_limit')) {
                        $table->integer('extra_services_limit')->default(0);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'extra_blog_limit')) {
                        $table->integer('extra_blog_limit')->default(0);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_customers', 'expire_date')) {
                        $table->dateTime('expire_date')->nullable();
                    }
                });
            }
            WbTemplatePurchase::ensureColumnsExist();
        } catch (\Throwable $e) {}
    }

    public function getPortfolioLimitAttribute(): int
    {
        $defaultLimit = 100;
        try {
            $lSetting = \App\Models\WebsiteBuilder\WbLandingSetting::first();
            if ($lSetting && $lSetting->default_portfolio_limit !== null && $lSetting->default_portfolio_limit !== '') {
                $defaultLimit = (int)$lSetting->default_portfolio_limit;
            }
        } catch (\Throwable $e) {}
        $pkgLimit = ($this->package && $this->package->portfolio_limit !== null) ? (int)$this->package->portfolio_limit : $defaultLimit;
        return max(0, (int)$pkgLimit + (int)($this->extra_portfolio_limit ?? 0));
    }

    public function getServicesLimitAttribute(): int
    {
        $defaultLimit = 100;
        try {
            $lSetting = \App\Models\WebsiteBuilder\WbLandingSetting::first();
            if ($lSetting && $lSetting->default_services_limit !== null && $lSetting->default_services_limit !== '') {
                $defaultLimit = (int)$lSetting->default_services_limit;
            }
        } catch (\Throwable $e) {}
        $pkgLimit = ($this->package && $this->package->services_limit !== null) ? (int)$this->package->services_limit : $defaultLimit;
        return max(0, (int)$pkgLimit + (int)($this->extra_services_limit ?? 0));
    }

    public function getBlogLimitAttribute(): int
    {
        $defaultLimit = 300;
        try {
            $lSetting = \App\Models\WebsiteBuilder\WbLandingSetting::first();
            if ($lSetting && $lSetting->default_blog_limit !== null && $lSetting->default_blog_limit !== '') {
                $defaultLimit = (int)$lSetting->default_blog_limit;
            }
        } catch (\Throwable $e) {}
        $pkgLimit = ($this->package && $this->package->blog_limit !== null) ? (int)$this->package->blog_limit : $defaultLimit;
        return max(0, (int)$pkgLimit + (int)($this->extra_blog_limit ?? 0));
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
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('wb_template_purchases', 'customer_id')) {
                return $this->hasOne(\App\Models\WebsiteBuilder\WbTemplatePurchase::class, 'customer_id')->latestOfMany();
            }
        } catch (\Throwable $e) {}
        return $this->hasOne(\App\Models\WebsiteBuilder\WbTemplatePurchase::class, 'customer_email', 'email')->latestOfMany();
    }

    public function getLatestPurchaseAttribute()
    {
        // 1. If relation already loaded and present, return it
        if ($this->relationLoaded('latestPurchase') && $this->getRelationValue('latestPurchase')) {
            return $this->getRelationValue('latestPurchase');
        }

        // 2. Query by customer_id if table has column
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('wb_template_purchases', 'customer_id')) {
                $purch = \App\Models\WebsiteBuilder\WbTemplatePurchase::where('customer_id', $this->id)->latest()->first();
                if ($purch) return $purch;
            }
        } catch (\Throwable $e) {}

        // 3. Robust match by email (case-insensitive & trimmed)
        $cleanEmail = strtolower(trim($this->email ?? ''));
        if (!empty($cleanEmail)) {
            try {
                $purch = \App\Models\WebsiteBuilder\WbTemplatePurchase::whereRaw('LOWER(TRIM(customer_email)) = ?', [$cleanEmail])->latest()->first();
                if ($purch) {
                    // Auto-link customer_id if column exists and empty
                    if (\Illuminate\Support\Facades\Schema::hasColumn('wb_template_purchases', 'customer_id') && empty($purch->customer_id)) {
                        try {
                            $purch->customer_id = $this->id;
                            $purch->save();
                        } catch (\Throwable $te) {}
                    }
                    return $purch;
                }
            } catch (\Throwable $e) {}
        }

        // 4. Fallback: match by name
        if (!empty($this->name)) {
            try {
                $purch = \App\Models\WebsiteBuilder\WbTemplatePurchase::whereRaw('LOWER(TRIM(customer_name)) = ?', [strtolower(trim($this->name))])->latest()->first();
                if ($purch) return $purch;
            } catch (\Throwable $e) {}
        }

        return null;
    }
}
