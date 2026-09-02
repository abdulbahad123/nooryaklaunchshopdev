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
        'storage_limit_mb',
        'custom_domain_allowed',
        'white_label_allowed',
        'ai_tools_allowed',
        'is_popular',
        'is_active',
        'features_list',
    ];

    protected $casts = [
        'monthly_price'         => 'float',
        'yearly_price'          => 'float',
        'max_websites'          => 'integer',
        'storage_limit_mb'      => 'integer',
        'custom_domain_allowed' => 'boolean',
        'white_label_allowed'   => 'boolean',
        'ai_tools_allowed'      => 'boolean',
        'is_popular'            => 'boolean',
        'is_active'              => 'boolean',
        'features_list'         => 'array',
    ];
}
