<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbTemplate extends Model
{
    use HasFactory;

    protected $table = 'wb_templates';

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'preview_image',
        'demo_url',
        'price',
        'is_free',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price'       => 'float',
        'is_free'     => 'boolean',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];
}
