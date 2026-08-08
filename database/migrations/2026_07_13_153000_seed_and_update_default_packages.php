<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedAndUpdateDefaultPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Seed missing custom features
        $missingFeatures = [
            'Free .in Domain',
            'Orders Individual Reports',
            'Good Customer Support',
            'Priority WhatsApp Support',
            '24/7 Call & WhatsApp Support',
            'Staff Accounts'
        ];

        if (Schema::hasTable('package_features')) {
            $maxSerial = DB::table('package_features')->max('serial_number') ?? 0;

            foreach ($missingFeatures as $index => $name) {
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

        // 2. Define Features Lists
        $basicFeatures = [
            "Subdomain",
            "QR Builder",
            "Blog",
            "Advanced Report Admin Panel",
            "Mail Notifications",
            "Wishlist Features",
            "Themes",
            "Offline Payment Options",
            "Bank Transfer Integrations",
            "Inventory management",
            "Product Individual Reports",
            "Good Customer Support"
        ];

        $standardFeatures = [
            "Subdomain",
            "Custom Domain",
            "QR Builder",
            "Blog",
            "Custom Page",
            "Google Login",
            "Google Analytics",
            "Google Recaptcha",
            "WhatsApp Chat Button",
            "Advanced Report Admin Panel",
            "Mail Notifications",
            "Wishlist Features",
            "Themes",
            "New Themes",
            "GST Billing",
            "Offline Payment Options",
            "Bank Transfer Integrations",
            "Razor Pay Payment Integration",
            "Inventory management",
            "Product Individual Reports",
            "Orders Individual Reports",
            "Priority WhatsApp Support"
        ];

        $premiumFeatures = [
            "Free .in Domain",
            "Subdomain",
            "Custom Domain",
            "QR Builder",
            "Blog",
            "Custom Page",
            "Google Login",
            "Google Analytics",
            "Facebook Pixel",
            "Google Recaptcha",
            "WhatsApp Chat Button",
            "Tawk to",
            "Disqus",
            "Advanced Report Admin Panel",
            "Mail Notifications",
            "Push Notification Updates",
            "Wishlist Features",
            "Themes",
            "New Themes",
            "GST Billing",
            "Offline Payment Options",
            "Bank Transfer Integrations",
            "Phonepe Payment Options",
            "Razor Pay Payment Integration",
            "Inventory management",
            "Product Individual Reports",
            "Orders Individual Reports",
            "Shipping Integration",
            "Order Tracking Feature",
            "24/7 Call & WhatsApp Support",
            "Staff Accounts"
        ];

        // 3. Update Packages
        if (Schema::hasTable('packages')) {
            // Update Monthly Basic
            DB::table('packages')
                ->where('title', 'Basic')
                ->where('term', 'monthly')
                ->update([
                    'product_limit' => 30,
                    'categories_limit' => 10,
                    'order_limit' => 100,
                    'post_limit' => 5,
                    'number_of_custom_page' => 0,
                    'language_limit' => 1,
                    'features' => json_encode($basicFeatures)
                ]);

            // Update Yearly Basic
            DB::table('packages')
                ->where('title', 'Basic')
                ->where('term', 'yearly')
                ->update([
                    'product_limit' => 30,
                    'categories_limit' => 10,
                    'order_limit' => 999999, // Unlimited
                    'post_limit' => 5,
                    'number_of_custom_page' => 0,
                    'language_limit' => 1,
                    'features' => json_encode($basicFeatures)
                ]);

            // Update Monthly Standard
            DB::table('packages')
                ->where('title', 'Standard')
                ->where('term', 'monthly')
                ->update([
                    'product_limit' => 250,
                    'categories_limit' => 50,
                    'order_limit' => 999999, // Unlimited
                    'post_limit' => 30,
                    'number_of_custom_page' => 10,
                    'language_limit' => 2, // 1 additional + 1 default
                    'features' => json_encode($standardFeatures)
                ]);

            // Update Yearly Standard
            DB::table('packages')
                ->where('title', 'Standard')
                ->where('term', 'yearly')
                ->update([
                    'product_limit' => 250,
                    'categories_limit' => 50,
                    'order_limit' => 999999, // Unlimited
                    'post_limit' => 30,
                    'number_of_custom_page' => 10,
                    'language_limit' => 2, // 1 additional + 1 default
                    'features' => json_encode($standardFeatures)
                ]);

            // Update Monthly Premium
            DB::table('packages')
                ->where('title', 'Premium')
                ->where('term', 'monthly')
                ->update([
                    'product_limit' => 600,
                    'categories_limit' => 150,
                    'order_limit' => 999999, // Unlimited
                    'post_limit' => 999999, // Unlimited
                    'number_of_custom_page' => 40,
                    'language_limit' => 4, // 3 additional + 1 default
                    'features' => json_encode($premiumFeatures)
                ]);

            // Update Yearly Premium
            DB::table('packages')
                ->where('title', 'Premium')
                ->where('term', 'yearly')
                ->update([
                    'product_limit' => 600,
                    'categories_limit' => 150,
                    'order_limit' => 999999, // Unlimited
                    'post_limit' => 999999, // Unlimited
                    'number_of_custom_page' => 40,
                    'language_limit' => 4, // 3 additional + 1 default
                    'features' => json_encode($premiumFeatures)
                ]);
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
