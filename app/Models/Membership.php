<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Membership extends Model
{
    public $table = "memberships";

    protected $fillable = [
        'package_price',
        'discount',
        'coupon_code',
        'price',
        'currency',
        'currency_symbol',
        'payment_method',
        'transaction_id',
        'is_trial',
        'trial_days',
        'receipt',
        'transaction_details',
        'settings',
        'package_id',
        'user_id',
        'start_date',
        'expire_date',
        'modified',
        'conversation_id',
        'status',
        'ai_engine',
        'ai_token_limit',
        'ai_image_limit',
        'ai_used_tokens',
        'ai_used_images',
        'ai_token_purchased',
        'ai_image_purchased'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
