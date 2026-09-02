<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbPage extends Model
{
    use HasFactory;

    protected $table = 'wb_pages';

    protected $fillable = [
        'customer_id',
        'title',
        'slug',
        'seo_title',
        'seo_description',
        'is_home',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_home'      => 'boolean',
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
    ];

    public function sections()
    {
        return $this->hasMany(WbSection::class, 'page_id')->orderBy('sort_order', 'asc');
    }
}
