<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\PackageFeature;
use App\Models\Package;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Add Staff Management feature to package_features table
        if (Schema::hasTable('package_features')) {
            $exists = DB::table('package_features')->where('name', 'Staff Management')->orWhere('keyword', 'Staff Management')->exists();
            if (!$exists) {
                $maxSerial = DB::table('package_features')->max('serial_number') ?? 0;
                DB::table('package_features')->insert([
                    'name' => 'Staff Management',
                    'type' => 'standard',
                    'keyword' => 'Staff Management',
                    'limit_key' => null,
                    'serial_number' => $maxSerial + 10,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 2. Enable Staff Management feature for Premium package(s)
        $premiumPackages = Package::where('title', 'LIKE', '%Premium%')->get();
        if ($premiumPackages->isEmpty()) {
            // If no package explicitly named "Premium", enable for the highest price package
            $premiumPackages = Package::orderBy('price', 'desc')->take(1)->get();
        }

        foreach ($premiumPackages as $pkg) {
            $features = !empty($pkg->features) ? json_decode($pkg->features, true) : [];
            if (is_array($features) && !in_array('Staff Management', $features)) {
                $features[] = 'Staff Management';
                $pkg->features = json_encode($features);
                $pkg->save();
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
        if (Schema::hasTable('package_features')) {
            DB::table('package_features')->where('name', 'Staff Management')->delete();
        }
    }
};
