<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('wb_template_purchases')) {
            Schema::create('wb_template_purchases', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name');
                $table->string('customer_email');
                $table->string('customer_phone')->nullable();
                $table->string('template_slug')->default('digital_agency');
                $table->string('template_name')->default('Digital Agency');
                $table->string('razorpay_payment_id')->nullable();
                $table->string('razorpay_order_id')->nullable();
                $table->string('razorpay_signature')->nullable();
                $table->decimal('amount', 10, 2)->default(499.00);
                $table->string('currency')->default('INR');
                $table->string('status')->default('completed');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wb_template_purchases');
    }
};
