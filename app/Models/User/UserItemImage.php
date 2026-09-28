<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItemImage extends Model
{
    use HasFactory;
    protected $table = 'user_item_images';

    protected $guarded = [];


    public function item()
    {
        return $this->belongsTo(UserItem::class, 'item_id', 'id');
    }

    public function getImageUrlAttribute()
    {
        return user_item_image_url($this->image, 'slider');
    }
}
