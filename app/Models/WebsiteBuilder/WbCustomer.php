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
        'sso_token',
        'sso_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'sso_token',
    ];

    protected $casts = [
        'sso_token_expires_at' => 'datetime',
        'status'               => 'integer',
    ];

    public function package()
    {
        return $this->belongsTo(WbPackage::class, 'package_id');
    }
}
