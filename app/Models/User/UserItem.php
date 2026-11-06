<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'user_items';

    public function itemContents()
    {
        return $this->hasMany(UserItemContent::class, 'item_id', 'id');
    }

    public function getItemContentsAttribute()
    {
        if ($this->relationLoaded('itemContents')) {
            $contents = $this->getRelation('itemContents');
            if ($contents && (is_array($contents) ? count($contents) > 0 : $contents->isNotEmpty())) {
                return $contents;
            }
        } else {
            $contents = $this->getRelationValue('itemContents');
            if ($contents && (is_array($contents) ? count($contents) > 0 : $contents->isNotEmpty())) {
                return $contents;
            }
        }

        $fallback = UserItemContent::where('item_id', $this->id)->get();
        $this->setRelation('itemContents', $fallback);
        return $fallback;
    }
    public function sliders()
    {
        return $this->hasMany(UserItemImage::class, 'item_id', 'id');
    }

    public function getThumbnailUrlAttribute()
    {
        return user_item_image_url($this->thumbnail, 'thumbnail');
    }
    public function variations()
    {
        return $this->hasMany(UserItemVariation::class, 'item_id');
    }
    public function currency()
    {
        return $this->belongsTo(UserCurrency::class);
    }


}
