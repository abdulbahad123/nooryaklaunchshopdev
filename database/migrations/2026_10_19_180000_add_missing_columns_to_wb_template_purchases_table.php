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
        if (Schema::hasTable('wb_template_purchases')) {
            Schema::table('wb_template_purchases', function (Blueprint $table) {
                if (!Schema::hasColumn('wb_template_purchases', 'customer_id')) {
                    $table->unsignedBigInteger('customer_id')->nullable();
                }
                if (!Schema::hasColumn('wb_template_purchases', 'package_id')) {
                    $table->unsignedBigInteger('package_id')->nullable();
                }
                if (!Schema::hasColumn('wb_template_purchases', 'payment_method')) {
                    $table->string('payment_method')->default('Razorpay');
                }
                if (!Schema::hasColumn('wb_template_purchases', 'transaction_id')) {
                    $table->string('transaction_id')->nullable();
                }
                if (!Schema::hasColumn('wb_template_purchases', 'payment_proof')) {
                    $table->string('payment_proof')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('wb_template_purchases')) {
            Schema::table('wb_template_purchases', function (Blueprint $table) {
                $colsToDrop = [];
                foreach (['customer_id', 'package_id', 'payment_method', 'transaction_id', 'payment_proof'] as $col) {
                    if (Schema::hasColumn('wb_template_purchases', $col)) {
                        $colsToDrop[] = $col;
                    }
                }
                if (!empty($colsToDrop)) {
                    $table->dropColumn($colsToDrop);
                }
            });
        }
    }
};