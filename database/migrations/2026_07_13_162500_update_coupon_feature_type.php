<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateCouponFeatureType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Update the feature in package_features table to match standard limits layout
        if (Schema::hasTable('package_features')) {
            DB::table('package_features')
                ->where('name', 'Coupon code')
                ->orWhere('keyword', 'Coupon code')
                ->update([
                    'name' => '{limit} Coupons limit',
                    'keyword' => 'Coupon code',
                    'type' => 'standard',
                    'limit_key' => 'coupon_limit'
                ]);
        }

        // 2. Add 'Coupon code' to the features list of all packages
        if (Schema::hasTable('packages')) {
            $packages = DB::table('packages')->get();
            foreach ($packages as $pkg) {
                $feats = json_decode($pkg->features, true) ?? [];
                if (!in_array('Coupon code', $feats)) {
                    $feats[] = 'Coupon code';
                    DB::table('packages')
                        ->where('id', $pkg->id)
                        ->update(['features' => json_encode($feats)]);
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
