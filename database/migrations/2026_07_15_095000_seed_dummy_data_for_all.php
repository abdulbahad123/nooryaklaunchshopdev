<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SeedDummyDataForAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $targets = [
            [
                'email' => 'sathikaqiq121@gmail.com',
                'products' => 600,
                'categories' => 150,
                'blogs' => 100,
                'pages' => 40,
                'coupons' => 150,
                'orders' => 0
            ],
            [
                'email' => 'farkana7999000@gmail.com',
                'products' => 600,
                'categories' => 150,
                'blogs' => 300,
                'pages' => 40,
                'coupons' => 150,
                'orders' => 300
            ]
        ];

        foreach ($targets as $target) {
            $user = DB::table('users')->where('email', $target['email'])->first();
            if (!$user) {
                continue;
            }

            // 1. Resolve default user language
            $defaultLang = null;
            if (Schema::hasTable('user_languages')) {
                $defaultLang = DB::table('user_languages')
                    ->where('user_id', $user->id)
                    ->where('is_default', 1)
                    ->first();
                if (!$defaultLang) {
                    $defaultLang = DB::table('user_languages')
                        ->where('user_id', $user->id)
                        ->first();
                }
            }
            $langId = $defaultLang ? $defaultLang->id : 1;

            // 2. Resolve user currency
            $userCurrency = null;
            if (Schema::hasTable('user_currencies')) {
                $userCurrency = DB::table('user_currencies')
                    ->where('user_id', $user->id)
                    ->where('is_default', 1)
                    ->first();
                if (!$userCurrency) {
                    $userCurrency = DB::table('user_currencies')
                        ->where('user_id', $user->id)
                        ->first();
                }
            }
            $currencyId = $userCurrency ? $userCurrency->id : 1;

            // 3. Seed Categories
            if ($target['categories'] > 0 && Schema::hasTable('user_item_categories')) {
                $currentCatCount = DB::table('user_item_categories')->where('user_id', $user->id)->count();
                if ($currentCatCount < $target['categories']) {
                    $categoriesToInsert = [];
                    for ($i = $currentCatCount + 1; $i <= $target['categories']; $i++) {
                        $categoriesToInsert[] = [
                            'unique_id' => uniqid() . '_' . $i,
                            'name' => 'Category ' . $i,
                            'color' => '1c2d3d',
                            'language_id' => $langId,
                            'status' => 1,
                            'slug' => 'category-' . $i . '-' . uniqid(),
                            'user_id' => $user->id,
                            'is_feature' => 1,
                            'serial_number' => $i,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                    DB::table('user_item_categories')->insert($categoriesToInsert);
                }
            }

            // Fetch user category IDs for product association
            $categoryIds = [];
            if (Schema::hasTable('user_item_categories')) {
                $categoryIds = DB::table('user_item_categories')
                    ->where('user_id', $user->id)
                    ->pluck('id')
                    ->toArray();
            }

            // 4. Seed Products
            if ($target['products'] > 0 && Schema::hasTable('user_items') && Schema::hasTable('user_item_contents')) {
                $currentItemCount = DB::table('user_items')->where('user_id', $user->id)->count();
                if ($currentItemCount < $target['products']) {
                    $contentsToInsert = [];
                    for ($i = $currentItemCount + 1; $i <= $target['products']; $i++) {
                        $itemId = DB::table('user_items')->insertGetId([
                            'user_id' => $user->id,
                            'stock' => 100,
                            'sku' => 'SKU-' . uniqid() . '-' . $i,
                            'status' => 1,
                            'current_price' => 10.00 + ($i % 50),
                            'previous_price' => 15.00 + ($i % 50),
                            'currency_id' => $currencyId,
                            'type' => 'physical',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        $catId = !empty($categoryIds) ? $categoryIds[($i - 1) % count($categoryIds)] : null;

                        $contentsToInsert[] = [
                            'item_id' => $itemId,
                            'user_id' => $user->id,
                            'language_id' => $langId,
                            'category_id' => $catId,
                            'title' => 'Product ' . $i,
                            'slug' => 'product-' . $i . '-' . uniqid(),
                            'summary' => 'Summary of product ' . $i,
                            'description' => 'Description of product ' . $i,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        if (count($contentsToInsert) >= 100) {
                            DB::table('user_item_contents')->insert($contentsToInsert);
                            $contentsToInsert = [];
                        }
                    }
                    if (!empty($contentsToInsert)) {
                        DB::table('user_item_contents')->insert($contentsToInsert);
                    }
                }
            }

            // 5. Seed Custom Pages
            if ($target['pages'] > 0 && Schema::hasTable('user_pages') && Schema::hasTable('user_page_contents')) {
                $currentPageCount = DB::table('user_pages')->where('user_id', $user->id)->count();
                if ($currentPageCount < $target['pages']) {
                    for ($i = $currentPageCount + 1; $i <= $target['pages']; $i++) {
                        $pageId = DB::table('user_pages')->insertGetId([
                            'user_id' => $user->id,
                            'status' => 1,
                            'serial_number' => $i,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        DB::table('user_page_contents')->insert([
                            'page_id' => $pageId,
                            'user_id' => $user->id,
                            'language_id' => $langId,
                            'title' => 'Custom Page ' . $i,
                            'slug' => 'custom-page-' . $i . '-' . uniqid(),
                            'body' => 'Body content for Custom Page ' . $i,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            // Resolve blog category
            $blogCategoryId = null;
            if (Schema::hasTable('user_blog_categories')) {
                $blogCategory = DB::table('user_blog_categories')
                    ->where('user_id', $user->id)
                    ->first();
                if (!$blogCategory) {
                    $blogCategoryId = DB::table('user_blog_categories')->insertGetId([
                        'user_id' => $user->id,
                        'name' => 'General',
                        'status' => 1,
                        'serial_number' => 1,
                        'language_id' => $langId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $blogCategoryId = $blogCategory->id;
                }
            }

            // 6. Seed Blogs
            if ($target['blogs'] > 0 && Schema::hasTable('user_blogs') && Schema::hasTable('user_blog_contents')) {
                $currentBlogCount = DB::table('user_blogs')->where('user_id', $user->id)->count();
                if ($currentBlogCount < $target['blogs']) {
                    for ($i = $currentBlogCount + 1; $i <= $target['blogs']; $i++) {
                        $blogId = DB::table('user_blogs')->insertGetId([
                            'user_id' => $user->id,
                            'status' => 1,
                            'serial_number' => $i,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        DB::table('user_blog_contents')->insert([
                            'blog_id' => $blogId,
                            'user_id' => $user->id,
                            'language_id' => $langId,
                            'category_id' => $blogCategoryId,
                            'title' => 'Blog Post ' . $i,
                            'slug' => 'blog-post-' . $i . '-' . uniqid(),
                            'content' => 'Full article body text for blog post number ' . $i,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            // 7. Seed Coupons
            if ($target['coupons'] > 0 && Schema::hasTable('user_coupons')) {
                $currentCouponCount = DB::table('user_coupons')->where('user_id', $user->id)->count();
                if ($currentCouponCount < $target['coupons']) {
                    $couponsToInsert = [];
                    for ($i = $currentCouponCount + 1; $i <= $target['coupons']; $i++) {
                        $couponsToInsert[] = [
                            'user_id' => $user->id,
                            'name' => 'Coupon ' . $i,
                            'code' => 'COUPON' . $i . '-' . strtoupper(Str::random(4)),
                            'type' => 'percentage',
                            'value' => 10,
                            'currency_id' => $currencyId,
                            'minimum_spend' => 50,
                            'start_date' => now()->toDateString(),
                            'end_date' => now()->addYear()->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                    DB::table('user_coupons')->insert($couponsToInsert);
                }
            }

            // 8. Seed Orders
            if ($target['orders'] > 0 && Schema::hasTable('user_orders')) {
                $currentOrderCount = DB::table('user_orders')->where('user_id', $user->id)->count();
                if ($currentOrderCount < $target['orders']) {
                    // Get or create customer for user
                    $customerId = null;
                    if (Schema::hasTable('customers')) {
                        $customer = DB::table('customers')->where('user_id', $user->id)->first();
                        if (!$customer) {
                            $customerId = DB::table('customers')->insertGetId([
                                'user_id' => $user->id,
                                'first_name' => 'Demo',
                                'last_name' => 'Customer',
                                'email' => 'customer@demo.com',
                                'contact_number' => '1234567890',
                                'status' => 1,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        } else {
                            $customerId = $customer->id;
                        }
                    }

                    $firstNames = ['John', 'Emily', 'Michael', 'Sarah', 'David', 'Jessica', 'James', 'Ashley'];
                    $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia'];
                    $gateways = ['Stripe', 'PayPal', 'Razorpay', 'Offline'];
                    $statuses = ['completed', 'pending', 'processing', 'rejected'];

                    $ordersToInsert = [];
                    for ($i = $currentOrderCount + 1; $i <= $target['orders']; $i++) {
                        $status = $statuses[$i % count($statuses)];
                        $payment_status = ($status == 'rejected') ? 'Pending' : 'Completed';
                        $cart_total = rand(250, 2500);
                        $tax = round($cart_total * 0.1, 2);
                        $shipping_charge = rand(0, 1) ? 0 : rand(20, 100);
                        $total = $cart_total + $tax + $shipping_charge;

                        $ordersToInsert[] = [
                            'customer_id' => $customerId,
                            'user_id' => $user->id,
                            'billing_country' => 'India',
                            'billing_fname' => $firstNames[$i % count($firstNames)],
                            'billing_lname' => $lastNames[$i % count($lastNames)],
                            'billing_address' => 'Demo Address ' . $i,
                            'billing_city' => 'Delhi',
                            'billing_email' => 'customer' . $i . '@demo.com',
                            'billing_number' => '98765000' . $i,
                            'shipping_country' => 'India',
                            'shipping_fname' => $firstNames[$i % count($firstNames)],
                            'shipping_lname' => $lastNames[$i % count($lastNames)],
                            'shipping_address' => 'Demo Address ' . $i,
                            'shipping_city' => 'Delhi',
                            'shipping_email' => 'customer' . $i . '@demo.com',
                            'shipping_number' => '98765000' . $i,
                            'cart_total' => $cart_total,
                            'discount' => 0,
                            'tax' => $tax,
                            'tax_percentage' => 10.00,
                            'total' => $total,
                            'method' => $gateways[$i % count($gateways)],
                            'gateway_type' => 'online',
                            'currency_code' => $userCurrency ? $userCurrency->text : 'INR',
                            'currency_sign' => $userCurrency ? $userCurrency->symbol : '₹',
                            'currency_id' => $currencyId,
                            'order_number' => 'ORD' . $i . '-' . strtoupper(Str::random(6)),
                            'shipping_method' => 'Standard Shipping',
                            'shipping_charge' => $shipping_charge,
                            'payment_status' => $payment_status,
                            'order_status' => $status,
                            'created_at' => now()->subDays(rand(0, 30)),
                            'updated_at' => now()
                        ];

                        if (count($ordersToInsert) >= 100) {
                            DB::table('user_orders')->insert($ordersToInsert);
                            $ordersToInsert = [];
                        }
                    }
                    if (!empty($ordersToInsert)) {
                        DB::table('user_orders')->insert($ordersToInsert);
                    }
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
