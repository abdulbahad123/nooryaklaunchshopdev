<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $activeKeywords = ['paytm', 'razorpay', 'stripe', 'phonepe', 'instamojo'];

        // 1. Get all Demo Template accounts
        $demoUserIds = User::where('preview_template', 1)->pluck('id')->toArray();

        if (!empty($demoUserIds)) {
            // Deactivate all non-standard gateways for demo templates
            DB::table('user_payment_gateways')
                ->whereIn('user_id', $demoUserIds)
                ->whereNotIn('keyword', $activeKeywords)
                ->update(['status' => 0]);

            // Ensure Paytm, Razorpay, Stripe, PhonePe, Instamojo are active for demo templates
            DB::table('user_payment_gateways')
                ->whereIn('user_id', $demoUserIds)
                ->whereIn('keyword', $activeKeywords)
                ->update(['status' => 1]);
        }

        // 2. Global cleanup: Deactivate obscure/unconfigured gateways that were seeded with status=1
        $obscureGateways = [
            'myfatoorah', 'paytabs', 'perfect_money', 'bkash', 'mollie',
            'flutterwave', 'mercadopago', 'yoco', 'xendit', 'toyyibpay',
            'midtrans', 'iyzico', 'authorize.net', 'anet'
        ];

        DB::table('user_payment_gateways')
            ->whereIn('keyword', $obscureGateways)
            ->where(function ($q) {
                $q->whereNull('information')
                  ->orWhere('information', '')
                  ->orWhere('information', 'LIKE', '%""%')
                  ->orWhere('information', 'LIKE', '%"key":""%');
            })
            ->update(['status' => 0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No revert needed
    }
};
