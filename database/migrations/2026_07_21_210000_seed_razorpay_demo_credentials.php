<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        $key = 'rzp_test_T9UaATIMf1qeO8';
        $secret = 'BQ9Z865NgRQrrIMCusfzmskZ';
        $infoJson = json_encode([
            'key' => $key,
            'secret' => $secret,
            'text' => 'Pay via your Razorpay account.'
        ]);

        // 1. Update Admin Payment Gateway for Razorpay (check updated_at existence)
        $adminUpdateData = [
            'information' => $infoJson,
            'status' => 1,
        ];
        if (Schema::hasColumn('payment_gateways', 'updated_at')) {
            $adminUpdateData['updated_at'] = now();
        }

        DB::table('payment_gateways')
            ->where('keyword', 'razorpay')
            ->update($adminUpdateData);

        // 2. Get all Demo Template accounts
        $demoUserIds = User::where('preview_template', 1)->pluck('id')->toArray();

        $userGatewayData = [
            'title' => 'Razorpay',
            'name' => 'Razorpay',
            'type' => 'automatic',
            'information' => $infoJson,
            'status' => 1,
        ];
        if (Schema::hasColumn('user_payment_gateways', 'updated_at')) {
            $userGatewayData['updated_at'] = now();
        }

        if (!empty($demoUserIds)) {
            foreach ($demoUserIds as $userId) {
                DB::table('user_payment_gateways')
                    ->updateOrInsert(
                        ['user_id' => $userId, 'keyword' => 'razorpay'],
                        $userGatewayData
                    );
            }
        }

        // Also update any existing user_payment_gateways for razorpay with empty keys
        $userUpdateData = [
            'information' => $infoJson,
            'status' => 1,
        ];
        if (Schema::hasColumn('user_payment_gateways', 'updated_at')) {
            $userUpdateData['updated_at'] = now();
        }

        DB::table('user_payment_gateways')
            ->where('keyword', 'razorpay')
            ->where(function ($q) {
                $q->whereNull('information')
                  ->orWhere('information', '')
                  ->orWhere('information', 'LIKE', '%""%');
            })
            ->update($userUpdateData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No revert needed for test credentials
    }
};
