<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbTemplatePurchase extends Model
{
    use HasFactory;

    protected $table = 'wb_template_purchases';

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'template_slug',
        'template_name',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'amount',
        'currency',
        'status',
    ];
}
