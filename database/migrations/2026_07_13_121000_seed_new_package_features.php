<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedNewPackageFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $newFeatures = [
            'Advanced Report Admin Panel',
            'Mail Notifications',
            'Push Notification Updates',
            'Wishlist Features',
            'Themes',
            'New Themes',
            'GST Billing',
            'Offline Payment Options',
            'Bank Transfer Integrations',
            'Phonepe Payment Options',
            'Razor Pay Payment Integration',
            'Inventory management',
            'Product Individual Reports',
            'Shipping Integration',
            'Order Tracking Feature',
            '2 Months WhatsApp Support'
        ];

        $maxSerial = DB::table('package_features')->max('serial_number') ?? 0;

        foreach ($newFeatures as $index => $name) {
            $exists = DB::table('package_features')->where('name', $name)->exists();
            if (!$exists) {
                DB::table('package_features')->insert([
                    'name' => $name,
                    'keyword' => $name,
                    'type' => 'custom',
                    'limit_key' => null,
                    'serial_number' => $maxSerial + (($index + 1) * 10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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
