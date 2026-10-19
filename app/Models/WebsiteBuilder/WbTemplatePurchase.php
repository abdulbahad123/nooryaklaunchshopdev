<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbTemplatePurchase extends Model
{
    use HasFactory;

    protected $table = 'wb_template_purchases';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'template_slug',
        'template_name',
        'package_id',
        'payment_method',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'transaction_id',
        'payment_proof',
        'amount',
        'currency',
        'status',
    ];

    public static function ensureColumnsExist(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                \Illuminate\Support\Facades\Schema::table('wb_template_purchases', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_template_purchases', 'customer_id')) {
                        $table->unsignedBigInteger('customer_id')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_template_purchases', 'package_id')) {
                        $table->unsignedBigInteger('package_id')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_template_purchases', 'payment_method')) {
                        $table->string('payment_method')->default('Razorpay');
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_template_purchases', 'transaction_id')) {
                        $table->string('transaction_id')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('wb_template_purchases', 'payment_proof')) {
                        $table->string('payment_proof')->nullable();
                    }
                });
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("ensureColumnsExist for wb_template_purchases failed: " . $e->getMessage());
        }
    }

    public function customer()
    {
        return $this->belongsTo(WbCustomer::class, 'customer_id');
    }

    public function package()
    {
        return $this->belongsTo(WbPackage::class, 'package_id');
    }
}