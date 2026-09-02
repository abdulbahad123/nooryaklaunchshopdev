<?php

use Illuminate\Support\Facades\Route;

// Register supported base hosts for tenant subdomain routing.
$requestHost = isset($_SERVER['HTTP_HOST'])
    ? strtolower(str_replace('www.', '', $_SERVER['HTTP_HOST']))
    : strtolower(str_replace('www.', '', (string) env('WEBSITE_HOST', 'localhost')));

$hostParts = explode('.', $requestHost);
$dynamicRootHost = count($hostParts) > 1 ? implode('.', array_slice($hostParts, 1)) : $requestHost;

$tenantBaseHosts = array_values(array_unique(array_filter([
    strtolower((string) env('WEBSITE_HOST', '')),
    $dynamicRootHost,
    'youverse.in',
    'nooryak.in',
    'launchshop.in',
    'maturednature.com',
    'cockroachjantaparty.top',
])));

// ─────────────────────────────────────────────────────────────────
// Shared tenant route definitions (inline closure)
// ─────────────────────────────────────────────────────────────────
$tenantRoutes = function () {
    Route::get('/', 'UserFront\HomeController@userDetailView')
        ->name('front.user.detail.view');
    Route::get('/invoice', 'UserFront\HomeController@invoice')
        ->name('front.user.detail.invoice');

    Route::get('/changelanguage/{code}', 'UserFront\HomeController@changeUserLanguage')->name('front.user.changeUserLanguage');
    Route::get('/changecurrency/{id}', 'UserFront\HomeController@changeUserCurrency')->name('front.user.changeUserCurrency');
    Route::get('apply/{token}', 'UserFront\HomeController@removeMaintenance')->name('front.user.remove')->withoutMiddleware('userMaintenance');

    Route::get('/about', 'UserFront\HomeController@userAbout')->name('front.user.about');
    Route::get('/privacy-policy', 'UserFront\HomeController@tenantPrivacyPolicy')->name('front.user.privacy_policy');
    Route::get('/terms-and-conditions', 'UserFront\HomeController@tenantTermsConditions')->name('front.user.terms_conditions');
    Route::get('/terms-conditions', 'UserFront\HomeController@tenantTermsConditions');
    Route::get('/refund-policy', 'UserFront\HomeController@tenantRefundPolicy')->name('front.user.refund_policy');
    Route::get('/shipping-policy', 'UserFront\HomeController@tenantShippingPolicy')->name('front.user.shipping_policy');

    Route::get('/page/{slug}', 'Front\FrontendController@customPage')->name('front.user.custom.page');
    Route::post('/subscribe', 'User\SubscriberController@Usersubscribe')->name('front.user.subscribe');

    Route::get('/shop', 'UserFront\ShopController@Shop')->name('front.user.shop');
    Route::get('/shop-search', 'UserFront\ShopController@ShopSearch')->name('front.user.shop.search');
    Route::get('/shop-type', 'UserFront\ShopController@shop_type')->name('front.user.shop.shop_type');
    Route::get('/get-variation', 'UserFront\ShopController@get_variation')->name('front.user.shop.get_variation');
    Route::get('/product/{slug}', 'UserFront\ShopController@productDetails')->name('front.user.productDetails');
    Route::post('/product/quick-view/{slug}', 'UserFront\ShopController@productDetailsQuickview')->name('front.user.productDetails.quickview');

    Route::get('product-info/variation/', 'UserFront\ShopController@get_productVariation')->name('front.user.get_variation');
    Route::get('/cart/dropdown', 'UserFront\ItemController@cartDropdown')->name('front.user.cart.dropdown');
    Route::post('/cart/dropdown/count', 'UserFront\ItemController@cartDropdownCount')->name('front.user.cart.dropdownCount');
    Route::post('/compare/count', 'UserFront\ItemController@compareCount')->name('front.user.compare.Count');
    Route::post('/wishlist/count', 'UserFront\ItemController@wishlistCount')->name('front.user.compare.wishlist');

    Route::get('/cart', 'UserFront\ItemController@cart')->name('front.user.cart');
    Route::get('/add-to-cart/{id}', 'UserFront\ItemController@addToCart')->name('front.user.add.cart');
    Route::get('/add-to-wishlist/{id}', 'UserFront\ItemController@addToWishlist')->name('front.user.add.wishlist');
    Route::get('/remove-wishlist/{id}', 'UserFront\ItemController@removeToWishlist')->name('front.user.remove.wishlist');
    Route::get('/add-to-compare/{id}', 'UserFront\ItemController@addToCompare')->name('front.user.add.compare');
    Route::get('/cart/item/remove/{uid}', 'UserFront\ItemController@cartitemremove')->name('front.cart.item.remove');
    Route::get('/compare/item/remove/{uid}', 'UserFront\ItemController@compareitemremove')->name('front.compare.item.remove');
    Route::post('/cart/update', 'UserFront\ItemController@updatecart')->name('front.user.cart.update');
    Route::post('product/review/submit', 'UserFront\ReviewController@reviewsubmit')->name('item.review.submit');
    Route::post('/coupon', 'UserFront\ItemController@coupon')->name('front.coupon');

    Route::get('/contact', 'UserFront\HomeController@contactView')->name('front.user.contact');
    Route::post('/contact-message', 'UserFront\HomeController@contactMessage')->name('front.user.contact.send_message')->middleware('Demo');
    Route::get('/faqs', 'UserFront\HomeController@faqs')->name('front.user.faq');

    Route::prefix('blog')->group(function () {
        Route::get('/', 'UserFront\HomeController@userBlogs')->name('front.user.blogs');
        Route::get('/{slug}', 'UserFront\HomeController@userBlogDetail')->name('user-front.blog_details');
    });

    Route::get('item-variation-converter/{value}/{id}', function ($domain, $value, $id) {
        return currency_converter($value, $id);
    })->name('front.item.variation.currency.convert');

    Route::get('/customer-success', 'UserFront\CustomerController@onlineSuccess')->name('customer.success.page');
    Route::get('/compare', 'UserFront\ItemController@compare')->name('front.user.compare');

    Route::get('/login/google', 'UserFront\CustomerController@redirectToGoogle')->name('customer.google.login');
    Route::get('/login/google/callback', 'UserFront\CustomerController@handleGoogleCallback')->name('customer.google.callback');

    Route::prefix('/customer')->middleware(['guest:customer'])->group(function () {
        Route::get('/login', 'UserFront\CustomerController@login')->name('customer.login');
        Route::post('/login-submit', 'UserFront\CustomerController@loginSubmit')->name('customer.login_submit');
        Route::get('/forgot-password', 'UserFront\CustomerController@forgetPassword')->name('customer.forget_password');
        Route::post('/send-forget-password-mail', 'UserFront\CustomerController@sendMail')->name('customer.send_forget_password_mail')->middleware('Demo');
        Route::get('/reset-password', 'UserFront\CustomerController@resetPassword')->name('customer.reset_password');
        Route::post('/reset-password-submit', 'UserFront\CustomerController@resetPasswordSubmit')->name('customer.reset_password_submit')->middleware('Demo');
        Route::get('/signup', 'UserFront\CustomerController@signup')->name('customer.signup');
        Route::post('/signup-submit', 'UserFront\CustomerController@signupSubmit')->name('customer.signup.submit')->middleware('Demo');
        Route::get('/signup-verify/{token}', 'UserFront\CustomerController@signupVerify')->name('customer.signup.verify');
    });

    Route::prefix('/customer')->middleware(['auth:customer', 'accountStatus', 'checkWebsiteOwner', 'Demo'])->group(function () {
        Route::get('/dashboard', 'UserFront\CustomerController@redirectToDashboard')->name('customer.dashboard');
        Route::get('/shipping/details', 'UserFront\CustomerController@shippingdetails')->name('customer.shpping-details');
        Route::post('/shipping/details/update', 'UserFront\CustomerController@shippingupdate')->name('customer.shipping-update');
        Route::get('/billing/details', 'UserFront\CustomerController@billingdetails')->name('customer.billing-details');
        Route::post('/billing/details/update', 'UserFront\CustomerController@billingupdate')->name('customer.billing-update');
        Route::get('/edit-profile', 'UserFront\CustomerController@editProfile')->name('customer.edit_profile');
        Route::post('/update-profile', 'UserFront\CustomerController@updateProfile')->name('customer.update_profile');
        Route::get('/order/{id}', 'UserFront\CustomerController@orderdetails')->name('customer.orders-details');
        Route::get('/orders', 'UserFront\CustomerController@customerOrders')->name('customer.orders');
        Route::get('/order-tracking', 'UserFront\CustomerController@orderTracking')->name('customer.order-tracking');
        Route::get('/wishlist', 'UserFront\CustomerController@customerWishlist')->name('customer.wishlist');
        Route::get('/remove-from-wishlist/{id}', 'UserFront\CustomerController@removefromWish')->name('customer.removefromWish');
        Route::get('/checkout/process', 'UserFront\ItemController@checkout_process')->name('front.user.checkout');
        Route::get('/checkout', 'UserFront\ItemController@checkout')->name('front.user.checkout.final_step');
        Route::get('/change-password', 'UserFront\CustomerController@changePassword')->name('customer.change_password');
        Route::post('/update-password', 'UserFront\CustomerController@updatePassword')->name('customer.update_password');
        Route::get('/logout', 'UserFront\CustomerController@logoutSubmit')->name('customer.logout');
    });

    Route::get('/checkout/guest', 'UserFront\ItemController@checkoutGuest')->name('front.user.checkout.guest');
    Route::post('/item/payment/submit', 'UserFront\UsercheckoutController@checkout')->name('item.payment.submit')->middleware('Demo');

    Route::group(['middleware' => ['routeAccess:Testimonial']], function () {
        Route::get('/testimonial', 'Front\FrontendController@userTestimonial')->name('front.user.testimonial');
    });

    Route::group(['middleware' => ['routeAccess:Contact']], function () {
        Route::post('/contact/message', 'Front\FrontendController@contactMessage')->name('front.contact.message')->middleware('Demo');
    });

    Route::get('/user/changelanguage', 'Front\FrontendController@changeUserLanguage')->name('changeUserLanguage');
    Route::post('/product/payment/instruction', 'UserFront\UsercheckoutController@paymentInstruction')->name('product.payment.paymentInstruction');
    Route::post('/push', 'UserFront\PushController@store')->name('front.user.push-notification.store_endpoint');

    Route::prefix('order')->group(function () {
        Route::get('paypal/success', "User\Payment\PaypalController@successPayment")->name('customer.itemcheckout.paypal.success');
        Route::any('/cancel', "UserFront\UsercheckoutController@cancelPayment")->name('customer.itemcheckout.cancel');
        Route::get('paystack/success', 'User\Payment\PaystackController@successPayment')->name('customer.itemcheckout.paystack.success');
        Route::get('mercadopago/success', 'Payment\MercadopagoController@successPayment')->name('customer.itemcheckout.mercadopago.success');
        Route::post('razorpay/success', 'User\Payment\RazorpayController@successPayment')->name('customer.itemcheckout.razorpay.success');
        Route::get('instamojo/success', 'User\Payment\InstamojoController@successPayment')->name('customer.itemcheckout.instamojo.success');
        Route::post('flutterwave/success', 'User\Payment\FlutterWaveController@successPayment')->name('customer.itemcheckout.flutterwave.success');
        Route::get('/mollie/success', 'User\Payment\MollieController@successPayment')->name('customer.itemcheckout.mollie.success');
        Route::get('/yoco/success', 'User\Payment\YocoController@successPayment')->name('customer.itemcheckout.yoco.success');
        Route::get('/xendit/success', 'User\Payment\YocoController@successPayment')->name('customer.itemcheckout.xendit.success');
        Route::get('/perfect-money/success', 'User\Payment\PerfectMoneyController@successPayment')->name('customer.itemcheckout.perfect_money.success');
        Route::get('/myfatoorah/success', 'User\Payment\MyfatoorahController@successPayment')->name('customer.itemcheckout.myfatoorah.success');
        Route::get('/toyyibpay/success', 'User\Payment\ToyyibpayController@successPayment')->name('customer.itemcheckout.toyyibpay.success');
        Route::post('/paytabs/success', 'User\Payment\PaytabsController@successPayment')->name('customer.itemcheckout.paytabs.success');
        Route::post('/phonepe/success', 'User\Payment\PhonePeController@successPayment')->name('customer.itemcheckout.phonepe.success');
        Route::get('/midtrans/success', 'User\Payment\MidtransController@successPayment')->name('customer.itemcheckout.midtrans.success');
        Route::post('/iyzico/success', 'User\Payment\IyzicoController@successPayment')->name('customer.itemcheckout.iyzico.success');
        Route::get('/offline/success', 'UserFront\UsercheckoutController@offlineSuccess')->name('customer.itemcheckout.offline.success');
        Route::post('paytm/payment-status', "User\Payment\PaytmController@paymentStatus")->name('customer.itemcheckout.paytm.status');
    });
};

// ─────────────────────────────────────────────────────────────────
// Context Detection & Execution
// ─────────────────────────────────────────────────────────────────
$isTenantSubdomain = false;
$tenantSubdomainName = null;
$reservedSubdomains = ['www', 'app', 'admin', 'checkout'];

foreach ($tenantBaseHosts as $tenantBaseHost) {
    if (!empty($tenantBaseHost) && $requestHost !== $tenantBaseHost && str_ends_with($requestHost, '.' . $tenantBaseHost)) {
        $sub = explode('.', $requestHost)[0] ?? null;
        if (!empty($sub) && !in_array(strtolower($sub), $reservedSubdomains)) {
            $isTenantSubdomain = true;
            $tenantSubdomainName = $sub;
            break;
        }
    }
}

$isMainHost = in_array($requestHost, array_merge(['localhost', '127.0.0.1'], $tenantBaseHosts));
$isCustomDomain = !$isMainHost && !isAgencyDomain($requestHost) && !$isTenantSubdomain;

// ─────────────────────────────────────────────────────────────────
// Auto 301 Redirect: ecomgrocery.launchshop.in/ecomgrocery/shop -> ecomgrocery.launchshop.in/shop
// ─────────────────────────────────────────────────────────────────
if ($isTenantSubdomain && !empty($tenantSubdomainName) && !app()->runningInConsole()) {
    try {
        $requestPath = ltrim(app('request')->path(), '/');
        $pathSegments = explode('/', $requestPath);
        if (isset($pathSegments[0]) && strtolower(urldecode($pathSegments[0])) === strtolower($tenantSubdomainName)) {
            array_shift($pathSegments);
            $cleanPath = implode('/', $pathSegments);
            $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
            $targetUrl = $scheme . '://' . $requestHost . '/' . ltrim($cleanPath, '/');
            if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
                $targetUrl .= '?' . $_SERVER['QUERY_STRING'];
            }
            header("Location: " . $targetUrl, true, 301);
            exit();
        }
    } catch (\Throwable $e) {
        // Fallback gracefully if request object is unavailable
    }
}

// ─────────────────────────────────────────────────────────────────
// ROUTE REGISTRATION BASED ON CONTEXT
// ─────────────────────────────────────────────────────────────────
if ($isTenantSubdomain) {
    // Subdomain Context: ecomgrocery.launchshop.in
    foreach ($tenantBaseHosts as $tenantBaseHost) {
        if (str_ends_with($cleanRequestHost, '.' . $tenantBaseHost)) {
            Route::group([
                'domain'     => '{username}.' . $tenantBaseHost,
                'middleware' => ['userVisibilityCheck', 'userLanguage', 'userMaintenance'],
            ], $tenantRoutes);
            break;
        }
    }
} elseif ($isCustomDomain) {
    // Custom Domain Context: womenart.in
    Route::group([
        'middleware' => ['userVisibilityCheck', 'userLanguage', 'userMaintenance'],
    ], $tenantRoutes);
} else {
    // Main Host / Agency Path-based Context: launchshop.in/ecomgrocery
    Route::group([
        'prefix'     => '/{username}',
        'middleware' => ['userVisibilityCheck', 'userLanguage', 'userMaintenance'],
    ], $tenantRoutes);
}

// ─────────────────────────────────────────────────────────────────
// Fallback 404
// ─────────────────────────────────────────────────────────────────
Route::fallback(function () {
    return view('errors.404');
})->middleware('setlang');
