<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCouponLimitToPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('packages')) {
            if (!Schema::hasColumn('packages', 'coupon_limit')) {
                Schema::table('packages', function (Blueprint $table) {
                    $table->integer('coupon_limit')->default(0);
                });
            }
        }

        if (Schema::hasTable('memberships')) {
            if (!Schema::hasColumn('memberships', 'coupon_limit')) {
                Schema::table('memberships', function (Blueprint $table) {
                    $table->integer('coupon_limit')->default(0);
                });
            }
        }

        // Set default coupon limits for seeded packages
        if (Schema::hasTable('packages')) {
            DB::table('packages')->where('title', 'Basic')->update(['coupon_limit' => 10]);
            DB::table('packages')->where('title', 'Standard')->update(['coupon_limit' => 50]);
            DB::table('packages')->where('title', 'Premium')->update(['coupon_limit' => 150]);
        }
        
        // Also update memberships coupon_limit to match their package
        if (Schema::hasTable('memberships') && Schema::hasTable('packages')) {
            $packages = DB::table('packages')->get();
            foreach ($packages as $pkg) {
                DB::table('memberships')->where('package_id', $pkg->id)->update(['coupon_limit' => $pkg->coupon_limit]);
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
        if (Schema::hasTable('packages')) {
            if (Schema::hasColumn('packages', 'coupon_limit')) {
                Schema::table('packages', function (Blueprint $table) {
                    $table->dropColumn('coupon_limit');
                });
            }
        }

        if (Schema::hasTable('memberships')) {
            if (Schema::hasColumn('memberships', 'coupon_limit')) {
                Schema::table('memberships', function (Blueprint $table) {
                    $table->dropColumn('coupon_limit');
                });
            }
        }
    }
}
