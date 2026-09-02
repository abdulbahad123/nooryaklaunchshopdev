<?php

use App\Models\User;

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
    'localhost',
    '127.0.0.1',
])));

$isTenantSubdomain = false;
$reservedSubdomains = ['www', 'app', 'admin', 'checkout'];

foreach ($tenantBaseHosts as $tenantBaseHost) {
    if (!empty($tenantBaseHost) && $requestHost !== $tenantBaseHost && str_ends_with($requestHost, '.' . $tenantBaseHost)) {
        $sub = explode('.', $requestHost)[0] ?? null;
        if (!empty($sub) && !in_array(strtolower($sub), $reservedSubdomains)) {
            $isTenantSubdomain = true;
            break;
        }
    }
}

$isMainHost = in_array($requestHost, $tenantBaseHosts);
$isCustomDomain = !$isMainHost && !isAgencyDomain($requestHost) && !$isTenantSubdomain;

Route::get('/midtrans/bank-notify', 'MidtransBankNotifyController@bank_notify')->name('midtrans.bank_notify');
Route::get('/check-payment', 'CronJobController@check_payment')->name('cron.check_payment');

Route::get('/myfatoorah/callback', 'MyFatoorahController@callback')->name('myfatoorah.success');
Route::get('myfatoorah/cancel', 'MyFatoorahController@cancel')->name('myfatoorah.cancel');

Route::get('/manifest.json', 'PwaController@manifest');
Route::get('/pwa-icon/{size}', 'PwaController@icon')->where('size', '[0-9]+');

Route::get('/invoice', 'Front\FrontendController@invoice')->name('front.invoice');

Route::get('/changelanguage/{lang}', 'Front\FrontendController@changeLanguage')->name('changeLanguage');
Route::get('/subcheck', 'CronJobController@expired')->name('cron.expired');
Route::post('/push', 'Front\PushController@store')->name('push-notification.store_endpoint');

/*=======================================================
******************** Admin Dashboard Routes **********************
=======================================================*/

Route::group(['prefix' => 'X9_AdMiN-Portal_V7', 'middleware' => 'guest:admin'], function () {
    Route::get('/', 'Admin\LoginController@login')->name('admin.login');
    Route::get('/sso-login', 'Admin\LoginController@ssoLogin')->name('admin.sso_login');
    Route::post('/login', 'Admin\LoginController@authenticate')->name('admin.auth');

    Route::get('/mail-form', 'Admin\ForgetController@mailForm')->name('admin.forget.form');
    Route::post('/sendmail', 'Admin\ForgetController@sendmail')->name('admin.forget.mail')->middleware('Demo');
});

Route::get('/sso-agency-login', 'User\Auth\LoginController@ssoAgencyLogin')->name('user.sso_login');

// Always ensure front.index route exists globally to prevent RouteNotFoundException in admin/error views
if ($isTenantSubdomain || $isCustomDomain) {
    Route::get('/platform-home', 'Front\FrontendController@index')->name('front.index');
}

// Only register main landing page routes if NOT on a tenant subdomain or custom domain!
if (!$isTenantSubdomain && !$isCustomDomain) {
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
        Route::get('/contact', 'Front\FrontendController@contact')->name('front.contact');
        Route::get('/privacy-policy', 'Front\FrontendController@privacyPolicy')->name('front.privacy-policy');
        Route::get('/terms-conditions', 'Front\FrontendController@termsConditions')->name('front.terms-conditions');
        Route::get('/cookie-policy', 'Front\FrontendController@cookiePolicy')->name('front.cookie-policy');
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

        Route::post('/forgot', 'User\Auth\ForgotPasswordController@forgetPasswordMail')->name('user-forgot-submit')->middleware('Demo');
    });

    Route::group(['middleware' => ['web', 'setlang']], function () {
        Route::post('/membership/checkout', 'Front\CheckoutController@checkout')->name('front.membership.checkout')->middleware('Demo');
        Route::post('/payment/instructions', 'Front\FrontendController@paymentInstruction')->name('front.payment.instructions');
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

            Route::get('/offline/success', 'Front\CheckoutController@offlineSuccess')->name('membership.offline.success');
            Route::get('/trial/success', 'Front\CheckoutController@trialSuccess')->name('membership.trial.success');
        });

        Route::any('membership/cancel', 'Front\CheckoutController@cancelPayment')->name('membership.cancel');
    });
}
