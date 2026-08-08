<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBasicPackageLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function run()
    {
        // Update Monthly Basic (ID 10)
        \DB::table('packages')->where('id', 10)->update([
            'post_limit' => 5,
            'product_limit' => 20,
            'categories_limit' => 10,
            'order_limit' => 100,
            'language_limit' => 1,
            'number_of_custom_page' => 0,
            'features' => json_encode(["Subdomain", "QR Builder", "AI Content & Image Generator", "Blog"])
        ]);

        // Update Yearly Basic (ID 11)
        \DB::table('packages')->where('id', 11)->update([
            'post_limit' => 5,
            'product_limit' => 20,
            'categories_limit' => 10,
            'order_limit' => 999999, // Unlimited
            'language_limit' => 1,
            'number_of_custom_page' => 0,
            'features' => json_encode(["Subdomain", "QR Builder", "AI Content & Image Generator", "Blog"])
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Not strictly necessary to rollback this data migration
    }
}
