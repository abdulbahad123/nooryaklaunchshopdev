<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RestoreTemplateMemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all template users
        $templateUsers = DB::table('users')->where('preview_template', 1)->get();
        
        // Find the first active package to assign to the templates
        $firstPackage = DB::table('packages')->where('status', 1)->first();

        if ($firstPackage && count($templateUsers) > 0) {
            // Get currency text & symbol from settings
            $currency = DB::table('basic_settings')->value('base_currency_text') ?: 'INR';
            $currencySymbol = DB::table('basic_settings')->value('base_currency_symbol') ?: '₹';

            foreach ($templateUsers as $tu) {
                // Check if the template user already has a valid active membership
                $hasActiveMemb = DB::table('memberships')
                    ->where('user_id', $tu->id)
                    ->where('status', 1)
                    ->where('expire_date', '>=', date('Y-m-d'))
                    ->exists();

                if (!$hasActiveMemb) {
                    DB::table('memberships')->insert([
                        'price' => $firstPackage->price,
                        'currency' => $currency,
                        'currency_symbol' => $currencySymbol,
                        'payment_method' => 'Offline',
                        'transaction_id' => 'template_' . uniqid(),
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
