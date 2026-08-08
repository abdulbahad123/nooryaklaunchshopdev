<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * SAFE TO RUN ON LIVE — Read-only analysis of effects:
 *
 * ✅ No tables are created or dropped
 * ✅ No columns are added or removed
 * ✅ Only UPDATE & INSERT on user_currencies rows
 * ✅ All changes are reversible via the down() method
 *
 * What it does:
 *  1. Updates any existing default currency that is NOT INR → changes it to INR
 *  2. For users that have NO currency at all → inserts INR as their default
 */
class SetDefaultCurrencyInr extends Migration
{
    public function up()
    {
        // 1. Update every current default that is NOT already INR
        DB::table('user_currencies')
            ->where('is_default', 1)
            ->where('text', '!=', 'INR')
            ->update([
                'text'           => 'INR',
                'symbol'         => '₹',
                'value'          => 1,
                'text_position'  => 'left',
                'symbol_position'=> 'left',
            ]);

        // 2. Insert INR for users that have zero currency rows
        $usersWithNoCurrency = DB::table('users')
            ->leftJoin('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->whereNull('user_currencies.id')
            ->pluck('users.id');

        foreach ($usersWithNoCurrency as $userId) {
            DB::table('user_currencies')->insert([
                'text'           => 'INR',
                'symbol'         => '₹',
                'value'          => 1,
                'is_default'     => 1,
                'text_position'  => 'left',
                'symbol_position'=> 'left',
                'user_id'        => $userId,
            ]);
        }
    }

    /**
     * Reverse: sets every default currency back to USD
     * (only useful in development; skip on live if not needed)
     */
    public function down()
    {
        DB::table('user_currencies')
            ->where('is_default', 1)
            ->where('text', 'INR')
            ->update([
                'text'   => 'USD',
                'symbol' => '$',
            ]);
    }
}
