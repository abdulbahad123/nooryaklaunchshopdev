<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ForceRestoreTemplateMemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Get all template users
        $templateUsers = DB::table('users')->where('preview_template', 1)->get();
        if (count($templateUsers) === 0) {
            return;
        }

        // 2. Find the first package or create one if empty
        $firstPackage = DB::table('packages')->first();
        if (!$firstPackage) {
            $packageId = DB::table('packages')->insertGetId([
                'title' => 'Default Plan',
                'slug' => 'default-plan',
                'price' => 0,
                'term' => 'monthly',
                'status' => 1,
                'features' => json_encode(['Subdomain']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $firstPackage = DB::table('packages')->where('id', $packageId)->first();
        }

        if ($firstPackage) {
            // 3. Get currency settings from basic_extendeds table
            $currency = DB::table('basic_extendeds')->value('base_currency_text') ?: 'INR';
            $currencySymbol = DB::table('basic_extendeds')->value('base_currency_symbol') ?: '₹';

            foreach ($templateUsers as $tu) {
                // Check if they already have an active membership
                $hasActiveMemb = DB::table('memberships')
                    ->where('user_id', $tu->id)
                    ->where('status', 1)
                    ->where('expire_date', '>=', date('Y-m-d'))
                    ->exists();

                if (!$hasActiveMemb) {
                    // Create template user's membership to make them visible
                    DB::table('memberships')->insert([
                        'price' => $firstPackage->price,
                        'currency' => $currency,
                        'currency_symbol' => $currencySymbol,
                        'payment_method' => 'Offline',
                        'transaction_id' => 'template_force_' . uniqid(),
                        'status' => 1,
                        'is_trial' => 0,
                        'trial_days' => 0,
                        'package_id' => $firstPackage->id,
                        'user_id' => $tu->id,
                        'start_date' => date('Y-m-d'),
                        'expire_date' => '2036-12-31', // Far future date
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
