<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RemoveLifetimeAndUpdatePackagePrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Get lifetime package IDs
        $lifetimePackageIds = DB::table('packages')->where('term', 'lifetime')->pluck('id')->toArray();

        if (!empty($lifetimePackageIds)) {
            // Delete memberships associated with lifetime packages
            DB::table('memberships')->whereIn('package_id', $lifetimePackageIds)->delete();
            
            // Delete lifetime packages
            DB::table('packages')->whereIn('id', $lifetimePackageIds)->delete();
        }

        // 2. Update monthly and yearly prices to valid/premium prices
        // Basic: ₹199 monthly, ₹1999 yearly
        DB::table('packages')->where('title', 'Basic')->where('term', 'monthly')->update(['price' => 199]);
        DB::table('packages')->where('title', 'Basic')->where('term', 'yearly')->update(['price' => 1999]);

        // Standard: ₹599 monthly, ₹5999 yearly
        DB::table('packages')->where('title', 'Standard')->where('term', 'monthly')->update(['price' => 599]);
        DB::table('packages')->where('title', 'Standard')->where('term', 'yearly')->update(['price' => 5999]);

        // Premium: ₹999 monthly, ₹9999 yearly
        DB::table('packages')->where('title', 'Premium')->where('term', 'monthly')->update(['price' => 999]);
        DB::table('packages')->where('title', 'Premium')->where('term', 'yearly')->update(['price' => 9999]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This is a data migration and is not fully reversible without original data backups.
    }
}
