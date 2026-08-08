<?php

namespace App\Models\User;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function orderitems()
    {
        return $this->hasMany(UserOrderItem::class);
    }

    public function currency()
    {
        return $this->belongsTo(UserCurrency::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    protected static function booted()
    {
        static::saving(function ($order) {
            $userId = $order->user_id;
            if ($userId) {
                $timeZone = \DB::table('user_basic_settings')->where('user_id', $userId)->value('timezone');
                if (!empty($timeZone)) {
                    $order->updated_at = \Carbon\Carbon::now($timeZone);
                    if (empty($order->id) && empty($order->created_at)) {
                        $order->created_at = \Carbon\Carbon::now($timeZone);
                    }
                }
            }
        });
    }
}
