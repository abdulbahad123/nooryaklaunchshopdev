<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbSection extends Model
{
    use HasFactory;

    protected $table = 'wb_sections';

    protected $fillable = [
        'page_id',
        'type',
        'content',
        'styles',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'content'    => 'array',
        'styles'     => 'array',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];
}
