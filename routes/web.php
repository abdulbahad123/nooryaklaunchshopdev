<?php

use App\Models\User;

$domain = env('WEBSITE_HOST');

if (!app()->runningInConsole()) {
    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
        $domain = 'www.' . env('WEBSITE_HOST');
    }
}
Route::fallback(function () {
    return view('errors.404');
})->middleware('setlang');

Route::get('/midtrans/bank-notify', 'MidtransBankNotifyController@bank_notify')->name('midtrans.bank_notify');
Route::get('/check-payment', 'CronJobController@check_payment')->name('cron.check_payment');

Route::get('/myfatoorah/callback', 'MyFatoorahController@callback')->name('myfatoorah.success');
Route::get('myfatoorah/cancel', 'MyFatoorahController@cancel')->name('myfatoorah.cancel');

Route::get('/manifest.json', 'PwaController@manifest');
Route::get('/pwa-icon/{size}', 'PwaController@icon')->where('size', '[0-9]+');

Route::get('/invoice', 'Front\FrontendController@invoice')
    ->name('front.invoice');

Route::domain($domain)->group(function () {
    Route::get('/update-admin-credentials', function() {
        $admin = \App\Models\Admin::first();
        if ($admin) {
            $admin->username = 'Admin1@Launchshop';
            $admin->password = \Illuminate\Support\Facades\Hash::make('Admin1@Launchshop_999');
            $admin->save();
            return "Admin credentials updated successfully!";
        }
        return "Admin user not found!";
    });

    Route::get('/seed-abdulbahad-data', function() {
        // Increase time and memory limits for large seeding
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '512M');

        $user = \App\Models\User::where('email', 'abdulbahad.dev@gmail.com')
            ->orWhere('username', 'store')
            ->first();
            
        if (!$user) {
            return "User abdulbahad.dev@gmail.com or username 'store' not found.";
        }

        // 1. Upgrade user's active membership to Standard Package
        $package = DB::table('packages')->where('title', 'like', '%Standard%')->first();
        if ($package) {
            $activeMembership = \App\Models\Membership::where('user_id', $user->id)
                ->where('status', 1)
                ->first();
                
            if ($activeMembership) {
                $activeMembership->update([
                    'package_id' => $package->id,
                    'start_date' => \Carbon\Carbon::now()->subDays(5)->toDateString(),
                    'expire_date' => \Carbon\Carbon::now()->addYear()->toDateString(),
                    'price' => $package->price,
                ]);
            } else {
                \App\Models\Membership::create([
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'start_date' => \Carbon\Carbon::now()->subDays(5)->toDateString(),
                    'expire_date' => \Carbon\Carbon::now()->addYear()->toDateString(),
                    'package_price' => $package->price,
                    'price' => $package->price,
                    'currency' => 'INR',
                    'currency_symbol' => '₹',
                    'payment_method' => 'Offline',
                    'transaction_id' => 'SEED_TXN_' . time(),
                    'status' => 1,
                ]);
            }
        }

        // 2. Clear old data
        \App\Models\User\UserOrder::where('user_id', $user->id)->delete();
        \App\Models\User\UserOrderItem::where('user_id', $user->id)->delete();
        \App\Models\User\UserItem::where('user_id', $user->id)->delete();
        \App\Models\User\UserItemContent::where('user_id', $user->id)->delete();
        \App\Models\User\UserItemCategory::where('user_id', $user->id)->delete();
        \App\Models\User\UserCoupon::where('user_id', $user->id)->delete();
        \App\Models\User\Blog::where('user_id', $user->id)->delete();
        \App\Models\User\BlogContent::where('user_id', $user->id)->delete();
        \App\Models\User\BlogCategory::where('user_id', $user->id)->delete();
        \App\Models\User\UserPage::where('user_id', $user->id)->delete();
        \App\Models\User\UserPageContent::where('user_id', $user->id)->delete();

        // 3. Get Default Language ID
        $default_lang = \App\Models\User\Language::where('user_id', $user->id)->where('is_default', 1)->first()
            ?? \App\Models\User\Language::where('user_id', $user->id)->first();
        $lang_id = $default_lang ? $default_lang->id : 1;

        // 4. Seed 50 categories
        $categoryIds = [];
        for ($i = 1; $i <= 50; $i++) {
            $cat = \App\Models\User\UserItemCategory::create([
                'unique_id' => 1000 + $i,
                'serial_number' => $i,
                'user_id' => $user->id,
                'language_id' => $lang_id,
                'name' => "Category $i",
                'slug' => "category-$i",
                'status' => 1,
                'is_feature' => 1,
            ]);
            $categoryIds[] = $cat->id;
        }

        // 5. Seed 250 products
        $itemsForOrders = [];
        for ($i = 1; $i <= 250; $i++) {
            $price = rand(150, 4500);
            $prevPrice = $price + rand(50, 500);
            $item = \App\Models\User\UserItem::create([
                'user_id' => $user->id,
                'stock' => rand(20, 500),
                'sku' => "SKU-" . str_pad($i, 5, '0', STR_PAD_LEFT),
                'current_price' => $price,
                'previous_price' => $prevPrice,
                'is_feature' => rand(0, 1),
                'status' => 1,
                'type' => 'physical',
            ]);
            
            \App\Models\User\UserItemContent::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'language_id' => $lang_id,
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'title' => "Premium Product $i",
                'slug' => "premium-product-$i",
                'summary' => "High-quality item for everyday use.",
                'description' => "This is a detailed description for premium product number $i.",
            ]);
            
            $itemsForOrders[] = [
                'id' => $item->id,
                'title' => "Premium Product $i",
                'sku' => "SKU-" . str_pad($i, 5, '0', STR_PAD_LEFT),
                'price' => $price,
                'prevPrice' => $prevPrice,
            ];
        }

        // 6. Seed 50 coupons
        for ($i = 1; $i <= 50; $i++) {
            \App\Models\User\UserCoupon::create([
                'user_id' => $user->id,
                'name' => "Discount Coupon $i",
                'code' => "PROMO" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'type' => rand(0, 1) ? 'percentage' : 'fixed',
                'value' => rand(0, 1) ? rand(5, 30) : rand(20, 200),
                'start_date' => \Carbon\Carbon::now()->subDays(5)->toDateString(),
                'end_date' => \Carbon\Carbon::now()->addDays(90)->toDateString(),
                'minimum_spend' => rand(100, 1000),
            ]);
        }

        // 7. Seed 30 blogs
        $blogCategoryIds = [];
        for ($i = 1; $i <= 5; $i++) {
            $bcat = \App\Models\User\BlogCategory::create([
                'name' => "News Category $i",
                'status' => 1,
                'language_id' => $lang_id,
                'user_id' => $user->id,
                'serial_number' => $i,
                'unique_id' => 2000 + $i,
            ]);
            $blogCategoryIds[] = $bcat->id;
        }

        for ($i = 1; $i <= 30; $i++) {
            $blog = \App\Models\User\Blog::create([
                'user_id' => $user->id,
                'status' => 1,
                'serial_number' => $i,
            ]);
            
            \App\Models\User\BlogContent::create([
                'blog_id' => $blog->id,
                'user_id' => $user->id,
                'language_id' => $lang_id,
                'category_id' => $blogCategoryIds[array_rand($blogCategoryIds)],
                'title' => "Ecommerce Growth Strategy Chapter $i",
                'slug' => "ecommerce-growth-strategy-chapter-$i",
                'author' => "Store Admin",
                'content' => "This is a dummy blog content for post number $i.",
            ]);
        }

        // 8. Seed 100 additional pages
        for ($i = 1; $i <= 100; $i++) {
            $page = \App\Models\User\UserPage::create([
                'user_id' => $user->id,
                'status' => 1,
                'serial_number' => $i,
            ]);
            
            \App\Models\User\UserPageContent::create([
                'page_id' => $page->id,
                'user_id' => $user->id,
                'language_id' => $lang_id,
                'title' => "Information Page $i",
                'slug' => "information-page-$i",
                'body' => "This is custom page number $i.",
            ]);
        }

        // 9. Seed 300 dummy orders
        $customer = \App\Models\Customer::where('user_id', $user->id)->first();
        if (!$customer) {
            $customer = \App\Models\Customer::create([
                'user_id' => $user->id,
                'first_name' => 'Mohamed',
                'last_name' => 'Imran',
                'email' => 'abdulbahad.dev@gmail.com',
                'phone' => '9876543210',
                'status' => 1
            ]);
        }

        $user_currency = \App\Models\User\UserCurrency::where('user_id', $user->id)->where('is_default', 1)->first()
            ?? \App\Models\User\UserCurrency::where('user_id', $user->id)->first();
        $currency_code = $user_currency ? $user_currency->code : 'INR';
        $currency_sign = $user_currency ? $user_currency->symbol : '₹';

        $firstNames = ['John', 'Emily', 'Michael', 'Sarah', 'David', 'Jessica', 'James', 'Ashley', 'Robert', 'Amanda'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia', 'Rodriguez', 'Wilson'];
        $gateways = ['Stripe', 'PayPal', 'Razorpay', 'Offline'];
        $statuses = ['completed', 'pending', 'processing', 'rejected'];

        for ($i = 1; $i <= 300; $i++) {
            $daysAgo = rand(0, 29);
            $orderDate = \Carbon\Carbon::now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            $numItems = rand(1, 3);
            $cart_total = 0;
            $orderItemsData = [];
            
            for ($j = 0; $j < $numItems; $j++) {
                $itemInfo = $itemsForOrders[array_rand($itemsForOrders)];
                $qty = rand(1, 3);
                $itemSubtotal = $itemInfo['price'] * $qty;
                $cart_total += $itemSubtotal;
                $orderItemsData[] = [
                    'item_id' => $itemInfo['id'],
                    'title' => $itemInfo['title'],
                    'sku' => $itemInfo['sku'],
                    'price' => $itemInfo['price'],
                    'previous_price' => $itemInfo['prevPrice'],
                    'qty' => $qty,
                ];
            }
            
            $tax = round($cart_total * 0.1, 2);
            $shipping_charge = rand(0, 1) ? 0 : rand(30, 120);
            $total = $cart_total + $tax + $shipping_charge;

            $status = $statuses[array_rand($statuses)];
            $payment_status = ($status == 'rejected') ? 'Pending' : 'Completed';

            $order = \App\Models\User\UserOrder::create([
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'billing_country' => 'India',
                'billing_fname' => $firstNames[array_rand($firstNames)],
                'billing_lname' => $lastNames[array_rand($lastNames)],
                'billing_address' => 'Street ' . rand(1, 200),
                'billing_city' => 'Chennai',
                'billing_email' => 'customer' . rand(1, 300) . '@demo.com',
                'billing_number' => '98765' . rand(10000, 99999),
                'shipping_country' => 'India',
                'shipping_fname' => $firstNames[array_rand($firstNames)],
                'shipping_lname' => $lastNames[array_rand($lastNames)],
                'shipping_address' => 'Street ' . rand(1, 200),
                'shipping_city' => 'Chennai',
                'shipping_email' => 'customer' . rand(1, 300) . '@demo.com',
                'shipping_number' => '98765' . rand(10000, 99999),
                'cart_total' => $cart_total,
                'discount' => 0,
                'tax' => $tax,
                'tax_percentage' => 10.00,
                'total' => $total,
                'method' => $gateways[array_rand($gateways)],
                'gateway_type' => 'online',
                'currency_code' => $currency_code,
                'currency_sign' => $currency_sign,
                'currency_id' => $user_currency ? $user_currency->id : 1,
                'order_number' => strtoupper(\Illuminate\Support\Str::random(4)) . time() . rand(10, 99),
                'shipping_method' => 'Standard Delivery',
                'shipping_charge' => $shipping_charge,
                'payment_status' => $payment_status,
                'order_status' => $status,
                'created_at' => $orderDate,
                'updated_at' => $orderDate
            ]);
            
            foreach ($orderItemsData as $oItem) {
                DB::table('user_order_items')->insert([
                    'user_order_id' => $order->id,
                    'user_id' => $user->id,
                    'customer_id' => $customer->id,
                    'item_id' => $oItem['item_id'],
                    'title' => $oItem['title'],
                    'sku' => $oItem['sku'],
                    'qty' => $oItem['qty'],
                    'price' => $oItem['price'],
                    'previous_price' => $oItem['previous_price'],
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ]);
            }
        }

        return "Successfully seeded 250 products, 300 orders (with items), 50 categories, 50 coupons, 30 blogs, and 100 pages for abdulbahad.dev@gmail.com!";
    });

    Route::get('/changelanguage/{lang}', 'Front\FrontendController@changeLanguage')->name('changeLanguage');

    // cron job for sending expiry mail
    Route::get('/subcheck', 'CronJobController@expired')->name('cron.expired');
    Route::post('/push', 'Front\PushController@store')->name('push-notification.store_endpoint');

    Route::group(['middleware' => 'setlang'], function () {
        Route::get('/', 'Front\FrontendController@index')->name('front.index');
        Route::post('/subscribe', 'Front\FrontendController@subscribe')->name('front.subscribe');
        Route::get('/shops', 'Front\FrontendController@shops')->name('front.user.view');
        Route::get('/templates', 'Front\FrontendController@templates')->name('front.templates.view');
        Route::get('/templates/autologin/{username}', 'Front\FrontendController@autoLoginTemplate')->name('front.templates.autologin');
        Route::get('/contact', 'Front\FrontendController@contactView')->name('front.contact');
        Route::post('/admin/contact-msg', 'Front\FrontendController@adminContactMessage')->name('front.admin.contact.message')->middleware('Demo');
        Route::get('/faqs', 'Front\FrontendController@faqs')->name('front.faq.view');
        Route::get('/blog', 'Front\FrontendController@blogs')->name('front.blogs');
        Route::get('/blog/{slug}/{id}', 'Front\FrontendController@blogdetails')->name('front.blogdetails');
        Route::get('/pricing', 'Front\FrontendController@pricing')->name('front.pricing');
        Route::get('/registration/select-template/{status}/{id}', 'Front\FrontendController@selectTemplate')->name('front.select.template');
        Route::get('/registration/step-1/{status}/{id}', 'Front\FrontendController@step1')->name('front.register.view');
        Route::get('/check/{username}/username', 'Front\FrontendController@checkUsername')->name('front.username.check');
        Route::post('/otp/send', 'Front\FrontendController@sendOtp')->name('front.otp.send')->middleware('throttle:5,1');
        Route::post('/otp/verify', 'Front\FrontendController@verifyOtp')->name('front.otp.verify');
        Route::get('/p/{slug}', 'Front\FrontendController@dynamicPage')->name('front.dynamicPage');
        Route::get('/success', 'Front\CheckoutController@paymentSuccess')->name('success.page');
        Route::get('/about', 'Front\FrontendController@about')->name('front.about');
        Route::get('/privacy-policy', 'Front\FrontendController@privacyPolicy')->name('front.privacy-policy');
        Route::get('/terms-conditions', 'Front\FrontendController@termsConditions')->name('front.terms-conditions');
        Route::get('/refund-policy', 'Front\FrontendController@refundPolicy')->name('front.refund-policy');
        Route::get('/shipping-policy', 'Front\FrontendController@shippingPolicy')->name('front.shipping-policy');
    });

    Route::group(['middleware' => ['web', 'guest', 'setlang']], function () {
        Route::get('/registration/final-step', 'Front\FrontendController@step2')->name('front.registration.step2');
        Route::post('/checkout', 'Front\FrontendController@checkout')->name('front.checkout.view');

        Route::get('/login', 'User\Auth\LoginController@showLoginForm')->name('user.login');
        Route::post('/login', 'User\Auth\LoginController@login')->name('user.login.submit');
        Route::post('/login/otp', 'User\Auth\LoginController@loginWithOtp')->name('user.login.otp.submit');
        Route::post('/register/submit', 'User\Auth\RegisterController@register')->name('user-register-submit')->middleware('Demo');
        Route::get('/register/mode/{mode}/verify/{token}', 'User\Auth\RegisterController@token')->name('user-register-token');

        Route::post('/password/email', 'User\Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.forgot.password.submit')->middleware('Demo');

        Route::get('/password/reset', 'User\Auth\ForgotPasswordController@showLinkRequestForm')->name('user.forgot.password.form');
        Route::post('/password/reset', 'User\Auth\ResetPasswordController@reset')->name('user.reset.password.submit')->middleware('Demo');
        Route::get('/password/reset/{token}/email/{email}', 'User\Auth\ResetPasswordController@showResetForm')->name('user.reset.password.form');

        // Route::get('/forgot', 'User\ForgotController@showforgotform')->name('user-forgot');
        Route::post('/forgot', 'User\Auth\ForgotPasswordController@forgetPasswordMail')->name('user-forgot-submit')->middleware('Demo');
    });

    /*=======================================================
    ******************** Admin Dashboard Routes **********************
    =======================================================*/

    Route::group(['prefix' => 'X9_AdMiN-Portal_V7', 'middleware' => 'guest:admin'], function () {
        Route::get('/', 'Admin\LoginController@login')->name('admin.login');
        Route::post('/login', 'Admin\LoginController@authenticate')->name('admin.auth');

        Route::get('/mail-form', 'Admin\ForgetController@mailForm')->name('admin.forget.form');
        Route::post('/sendmail', 'Admin\ForgetController@sendmail')->name('admin.forget.mail')->middleware('Demo');
    });

    Route::group(['middleware' => ['web', 'setlang']], function () {

        Route::post('/membership/checkout', 'Front\CheckoutController@checkout')->name('front.membership.checkout')->middleware('Demo');
        Route::post('/payment/instructions', 'Front\FrontendController@paymentInstruction')->name('front.payment.instructions');
        //checkout payment gateway routes
        Route::prefix('membership')->group(function () {
            Route::get('paypal/success', "Payment\PaypalController@successPayment")->name('membership.paypal.success');
            Route::get('paypal/cancel', "Payment\PaypalController@cancelPayment")->name('membership.paypal.cancel');
            Route::get('stripe/cancel', "Payment\StripeController@cancelPayment")->name('membership.stripe.cancel');
            Route::post('paytm/payment-status', "Payment\PaytmController@paymentStatus")->name('membership.paytm.status');
            Route::get('paystack/success', 'Payment\PaystackController@successPayment')->name('membership.paystack.success');
            Route::post('mercadopago/cancel', 'Payment\MercadopagoController@cancelPayment')->name('membership.mercadopago.cancel');
            Route::get('mercadopago/success', 'Payment\MercadopagoController@successPayment')->name('membership.mercadopago.success');
            Route::post('razorpay/success', 'Payment\RazorpayController@successPayment')->name('membership.razorpay.success');
            Route::post('razorpay/cancel', 'Payment\RazorpayController@cancelPayment')->name('membership.razorpay.cancel');
            Route::get('instamojo/success', 'Payment\InstamojoController@successPayment')->name('membership.instamojo.success');
            Route::post('instamojo/cancel', 'Payment\InstamojoController@cancelPayment')->name('membership.instamojo.cancel');
            Route::post('flutterwave/success', 'Payment\FlutterWaveController@successPayment')->name('membership.flutterwave.success');

            Route::get('/mollie/success', 'Payment\MollieController@successPayment')->name('membership.mollie.success');
            Route::post('mollie/cancel', 'Payment\MollieController@cancelPayment')->name('membership.mollie.cancel');
            Route::get('anet/cancel', 'Payment\AuthorizenetController@cancelPayment')->name('membership.anet.cancel');

            Route::get('yoco/success', 'Payment\YocoController@successPayment')->name('membership.yoco.success');
            Route::get('xendit/success', 'Payment\XenditController@successPayment')->name('membership.xendit.success');
            Route::get('perfect_money/success', 'Payment\PerfectMoneyController@successPayment')->name('membership.perfect_money.success');
            Route::get('midtrans/success', 'Payment\MidtransController@successPayment')->name('membership.midtrans.success');
            Route::post('iyzico/success', 'Payment\IyzicoController@successPayment')->name('membership.iyzico.success');
            Route::get('toyyibpay/success', 'Payment\ToyyibpayController@successPayment')->name('membership.toyyibpay.success');
            Route::post('paytabs/success', 'Payment\PaytabsController@successPayment')->name('membership.paytabs.success');
            Route::post('phonepe/success', 'Payment\PaytabsController@successPayment')->name('membership.phonepe.success');
            Route::post('phonepe/success', 'Payment\PaytabsController@successPayment')->name('membership.phonepe.success');

            Route::get('/offline/success', 'Front\CheckoutController@offlineSuccess')->name('membership.offline.success');
            Route::get('/trial/success', 'Front\CheckoutController@trialSuccess')->name('membership.trial.success');
        });

        Route::any('membership/cancel', 'Front\CheckoutController@cancelPayment')->name('membership.cancel');
    });
});
