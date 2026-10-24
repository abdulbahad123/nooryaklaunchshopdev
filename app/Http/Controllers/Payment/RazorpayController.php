<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Helpers\UserPermissionHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Http\Helpers\MegaMailer;
use App\Models\Language;
use App\Models\Package;
use App\Models\PaymentGateway;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends Controller
{
    public function __construct()
    {
        $data = PaymentGateway::whereKeyword('razorpay')->first();
        $keyId = 'rzp_test_T9UaATIMf1qeO8';
        $keySecret = 'BQ9Z865NgRQrrIMCusfzmskZ';

        if ($data) {
            $paydata = $data->convertAutoData();
            if (!empty($paydata['key'])) {
                $keyId = $paydata['key'];
            }
            if (!empty($paydata['secret'])) {
                $keySecret = $paydata['secret'];
            }
        }

        $this->keyId = $keyId;
        $this->keySecret = $keySecret;
        $this->api = new Api($this->keyId, $this->keySecret);
    }


    public function paymentProcess(Request $request, $_amount, $_item_number, $_cancel_url, $_success_url, $_title, $_description, $bs, $bex)
    {
        $cancel_url = $_cancel_url;
        $notify_url = $_success_url;

        $orderData = [
            'receipt' => $_title,
            'amount' => (int)round($_amount * 100),
            'currency' => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $this->api->order->create($orderData);
        Session::put('request', $request->all());
        Session::put('order_payment_id', $razorpayOrder['id']);

        $displayAmount = $amount = $_amount;

        $checkout = 'automatic';

        if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
            $checkout = $_GET['checkout'];
        }

        $data = [
            "key" => $this->keyId,
            "amount" => $_amount,
            "name" => $_title,
            "description" => $_description,
            "prefill" => [
                "name" => $request->first_name ?? $request->shop_name ?? $request->name ?? '',
                "email" => $request->email ?? '',
                "contact" => !empty($request->phone) ? (($request->country_code ?? '') . $request->phone) : ($request->razorpay_phone ?? ''),
            ],
            "notes" => [
                "address" => $request->address ?? $request->razorpay_address ?? '',
                "merchant_order_id" => $_item_number,
            ],
            "theme" => [
                "color" => "{{$bs->base_color}}"
            ],
            "order_id" => $razorpayOrder['id'],
        ];

        if ($bex->base_currency_text !== 'INR') {
            $data['display_currency'] = $bex->base_currency_text;
            $data['display_amount'] = $displayAmount;
        }

        $json = json_encode($data);
        $displayCurrency = $bex->base_currency_text;

        return view('front.razorpay', compact('data', 'displayCurrency', 'json', 'notify_url'));
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('request');
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $be = $currentLang ? $currentLang->basic_extended : null;
        $bs = $currentLang ? $currentLang->basic_setting : null;
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('order_payment_id');
        $success = true;
        if (empty($request['razorpay_payment_id']) === false && !empty($payment_id)) {

            try {
                $attributes = array(
                    'razorpay_order_id' => $payment_id,
                    'razorpay_payment_id' => $request['razorpay_payment_id'],
                    'razorpay_signature' => $request['razorpay_signature']
                );

                $this->api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
            }
        }

        if ($success === true) {
            if (is_array($requestData) && isWebsiteBuilderCheckout($requestData)) {
                $wbReqData = array_merge($requestData, [
                    'customer_name'  => $requestData['customer_name'] ?? $requestData['first_name'] ?? $requestData['shop_name'] ?? 'Store Owner',
                    'customer_email' => $requestData['customer_email'] ?? $requestData['email'] ?? '',
                    'customer_phone' => $requestData['customer_phone'] ?? $requestData['phone'] ?? '',
                    'subdomain'      => $requestData['subdomain'] ?? $requestData['username'] ?? '',
                    'password'       => $requestData['password'] ?? 'Password@123',
                    'razorpay_payment_id' => $request['razorpay_payment_id'] ?? ('PAY_' . strtoupper(\Illuminate\Support\Str::random(10))),
                ]);
                $wbReq = \Illuminate\Http\Request::create('/checkout/process', 'POST', $wbReqData);
                $wbReq->merge($wbReqData);
                $wbFrontend = new \App\Http\Controllers\WebsiteBuilder\FrontendController();
                return $wbFrontend->processCheckout($wbReq);
            }

            if (!is_array($requestData)) {
                session()->flash('success', __('successful_payment'));
                return redirect()->route('success.page');
            }

            $package_id = $requestData['package_id'] ?? null;
            $package = $package_id ? Package::find($package_id) : null;
            $paymentFor = Session::get('paymentFor');
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($request->all());

            $currencySymbolPos = $be ? $be->base_currency_symbol_position : 'left';
            $currencySymbol = $be ? $be->base_currency_symbol : '₹';
            $currencyText = $be ? $be->base_currency_text : 'INR';
            $currencyTextPos = $be ? $be->base_currency_text_position : 'left';
            $websiteTitle = $bs ? $bs->website_title : 'Launchshop';
            $packageTitle = $package ? $package->title : 'Package';
            $packagePrice = $package ? $package->price : ($requestData['price'] ?? 0);

            if ($paymentFor == "membership") {
                $amount = $requestData['price'] ?? 0;
                $password = $requestData['password'] ?? 'Password@123';
                $checkout = new CheckoutController();
                $requestData['status'] = 1;
                $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);

                $lastMemb = $user ? $user->memberships()->orderBy('id', 'DESC')->first() : null;
                $activation = $lastMemb ? Carbon::parse($lastMemb->start_date) : Carbon::now();
                $expire = $lastMemb ? Carbon::parse($lastMemb->expire_date) : Carbon::now();
                $phone = $requestData['phone'] ?? ($user ? $user->phone : '');
                $file_name = Common::makeInvoice($requestData, "membership", $user, $password, $amount, "Razorpay", $phone, $currencySymbolPos, $currencySymbol, $currencyText, $transaction_id, $packageTitle, 1);

                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $user ? $user->email : ($requestData['email'] ?? ''),
                    'toName' => $user ? ($user->first_name ?? $user->fname ?? 'Customer') : 'Customer',
                    'username' => $user ? $user->username : '',
                    'package_title' => $packageTitle,
                    'package_price' => ($currencyTextPos == 'left' ? $currencyText . ' ' : '') . $packagePrice . ($currencyTextPos == 'right' ? ' ' . $currencyText : ''),
                    'activation_date' => $activation->toFormattedDateString(),
                    'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                    'membership_invoice' => $file_name,
                    'website_title' => $websiteTitle,
                    'templateType' => 'registration_with_premium_package',
                    'type' => 'registrationWithPremiumPackage'
                ];
                $mailer->mailFromAdmin($data);

                session()->flash('success', __('successful_payment'));
                if ($user) {
                    session()->flash('new_user_username', $user->username);
                }
                Session::forget('request');
                Session::forget('paymentFor');
                return redirect()->route('success.page');
            } elseif ($paymentFor == "extend") {
                $amount = $requestData['price'] ?? 0;
                $password = uniqid('qrcode');
                $checkout = new UserCheckoutController();
                $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);

                $lastMemb = $user ? $user->memberships()->orderBy('id', 'DESC')->first() : null;
                $activation = $lastMemb ? Carbon::parse($lastMemb->start_date) : Carbon::now();
                $expire = $lastMemb ? Carbon::parse($lastMemb->expire_date) : Carbon::now();
                $phone = $user ? $user->phone_number : ($requestData['phone'] ?? '');
                $file_name = Common::makeInvoice($requestData, "extend", $user, $password, $amount, $requestData["payment_method"] ?? 'Razorpay', $phone, $currencySymbolPos, $currencySymbol, $currencyText, $transaction_id, $packageTitle, 1);

                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $user ? $user->email : '',
                    'toName' => $user ? $user->fname : '',
                    'username' => $user ? $user->username : '',
                    'package_title' => $packageTitle,
                    'package_price' => ($currencyTextPos == 'left' ? $currencyText . ' ' : '') . $packagePrice . ($currencyTextPos == 'right' ? ' ' . $currencyText : ''),
                    'activation_date' => $activation->toFormattedDateString(),
                    'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                    'membership_invoice' => $file_name,
                    'website_title' => $websiteTitle,
                    'templateType' => 'membership_extend',
                    'type' => 'membershipExtend'
                ];
                $mailer->mailFromAdmin($data);

                session()->flash('success', __('successful_payment'));
                Session::forget('request');
                Session::forget('paymentFor');
                return redirect()->route('success.page');
            }
            session()->flash('success', __('successful_payment'));
            Session::forget('request');
            Session::forget('paymentFor');
            return redirect()->route('success.page');
        }

        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', __('cancel_payment'));
        $pkgType = is_array($requestData) ? ($requestData['package_type'] ?? 'regular') : 'regular';
        $pkgId = is_array($requestData) ? ($requestData['package_id'] ?? null) : null;
        if ($paymentFor == "membership") {
            $reqHost = strtolower(trim((string)(request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? ''))));
            $reqHost = preg_replace('/:\d+$/', '', $reqHost);
            $reqHost = preg_replace('/^www\./i', '', $reqHost);
            $cleanAgencyHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder|whatsappbulk|whatsapp-bulk|whatsapp|wa)\./i', '', $reqHost);
            $scheme = (request()->secure() || str_contains(request()->fullUrl(), 'https://') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')) ? 'https://' : 'http://';

            if (!empty($cleanAgencyHost) && $cleanAgencyHost !== 'localhost' && $cleanAgencyHost !== '127.0.0.1') {
                $cancelUrl = "{$scheme}launchshop.{$cleanAgencyHost}/registration/step-1/{$pkgType}/{$pkgId}";
                return redirect()->to($cancelUrl)->withInput(is_array($requestData) ? $requestData : []);
            }

            return redirect()->route('front.register.view', ['status' => $pkgType, 'id' => $pkgId])->withInput(is_array($requestData) ? $requestData : []);
        } else {
            return redirect()->route('user.plan.extend.checkout', ['package_id' => $pkgId])->withInput(is_array($requestData) ? $requestData : []);
        }
    }

    public function whatsappBulkCheckout(\Illuminate\Http\Request $request)
    {
        $uid = $request->query('uid');
        $planName = $request->query('plan', 'Waziper Starter');
        $planId = $request->query('plan_id', 2);
        $price = (float)$request->query('price', 99);
        $token = $request->query('token', '');
        $customerName = $request->query('name', 'Subscriber');
        $customerEmail = $request->query('email', '');
        $customerPhone = $request->query('phone', '');

        if ($price <= 0) {
            $price = 99;
        }

        $cycle = $request->query('cycle') ?: (session('wabulk_checkout.cycle') ?: 'yearly');
        $source = $request->query('source', 'signup');

        $reqHost = strtolower(trim((string)(request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? ''))));
        $reqHost = preg_replace('/:\d+$/', '', $reqHost);
        $reqHost = preg_replace('/^www\./i', '', $reqHost);
        $cleanAgencyHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder|whatsappbulk|whatsapp-bulk|whatsapp|wa)\./i', '', $reqHost);
        $scheme = (request()->secure() || str_contains(request()->fullUrl(), 'https://') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')) ? 'https://' : 'http://';
        if (empty($cleanAgencyHost) || $cleanAgencyHost === 'localhost' || $cleanAgencyHost === '127.0.0.1') {
            $cleanAgencyHost = 'youverse.in';
        }

        if ($source === 'renewal' || $source === 'profile') {
            $cancel_url = "{$scheme}whatsappbulk.{$cleanAgencyHost}/app/profile/plan?plan=" . urlencode($planName) . "&cycle=" . urlencode($cycle) . "&plan_id=" . urlencode($planId);
        } else {
            $cancel_url = "{$scheme}whatsappbulk.{$cleanAgencyHost}/auth/signup?plan=" . urlencode($planName) . "&cycle=" . urlencode($cycle) . "&plan_id=" . urlencode($planId);
        }
        $notify_url = route('checkout.whatsappbulk.success');

        $orderData = [
            'receipt' => 'WABULK_' . ($uid ?: time()),
            'amount' => (int)round($price * 100),
            'currency' => 'INR',
            'payment_capture' => 1
        ];

        $razorpayOrderId = '';
        try {
            $razorpayOrder = $this->api->order->create($orderData);
            $razorpayOrderId = $razorpayOrder['id'] ?? '';
            session(['wb_razorpay_order_id' => $razorpayOrderId]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Razorpay order create error: ' . $e->getMessage());
        }

        session([
            'wabulk_checkout' => [
                'uid'            => $uid,
                'plan_name'      => $planName,
                'plan_id'        => $planId,
                'cycle'          => $cycle,
                'source'         => $source,
                'price'          => $price,
                'token'          => $token,
                'customer_name'  => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
            ]
        ]);

        $data = [
            "key" => $this->keyId,
            "amount" => (int)round($price * 100),
            "currency" => "INR",
            "name" => "Whatsappbulk",
            "description" => "Whatsappbulk {$planName} Subscription",
            "prefill" => [
                "name" => $customerName,
                "email" => $customerEmail,
                "contact" => $customerPhone,
            ],
            "notes" => [
                "merchant_order_id" => 'WABULK_' . ($uid ?: time()),
                "user_id" => $uid,
                "plan_id" => $planId,
                "cycle" => $cycle,
                "source" => $source,
            ],
            "theme" => [
                "color" => "#1f7a45"
            ],
        ];

        if (!empty($razorpayOrderId)) {
            $data['order_id'] = $razorpayOrderId;
        }

        $json = json_encode($data);
        $prevUrl = $cancel_url;

        return view('front.razorpay_wabulk', compact('data', 'json', 'notify_url', 'prevUrl', 'price', 'planName', 'customerName', 'customerEmail', 'customerPhone', 'uid', 'token', 'cycle', 'source'));
    }

    public function whatsappBulkSuccess(\Illuminate\Http\Request $request)
    {
        $sessionData = session('wabulk_checkout', []);
        $uid = $request->input('uid', $sessionData['uid'] ?? null);
        $planId = $request->input('plan_id', $sessionData['plan_id'] ?? null);
        $planName = $request->input('plan_name', $sessionData['plan_name'] ?? 'Whatsappbulk');
        $price = (float)$request->input('price', $sessionData['price'] ?? 0);
        $token = $request->input('token', $sessionData['token'] ?? '');
        $cycle = $request->input('cycle', $sessionData['cycle'] ?? 'yearly');
        $source = $request->input('source', $sessionData['source'] ?? 'signup');
        $razorpayPaymentId = $request->input('razorpay_payment_id') ?: ('PAY_' . strtoupper(\Illuminate\Support\Str::random(10)));

        if ($uid) {
            try {
                // Dynamically detect WhatsApp Bulk database name on current host
                $waDb = 'replix_automation';
                $candidateDbs = [
                    'nooryak_replix_automation',
                    'replix_automation',
                    'nooryak_whatautomation',
                    'nooryak_whatsappbulk',
                    'nooryak_ps_youverse_whatsappbulk',
                    'whatsappautomation',
                ];
                foreach ($candidateDbs as $cdb) {
                    try {
                        $chk = \Illuminate\Support\Facades\DB::select("SELECT 1 FROM `{$cdb}`.users LIMIT 1");
                        if (!empty($chk)) {
                            $waDb = $cdb;
                            break;
                        }
                    } catch (\Throwable $e) {}
                }

                $durationDays = ($cycle === 'monthly') ? 30 : 365;
                $plans = \Illuminate\Support\Facades\DB::select("SELECT * FROM `{$waDb}`.plans WHERE id = ? OR name LIKE ? LIMIT 1", [$planId ?: 0, "%{$planName}%"]);
                $planObj = $plans[0] ?? null;
                if ($planObj) {
                    $planId = $planObj->id;
                    $durationDays = ($planObj->type == 1) ? 30 : 365;
                }

                // Check existing expiration date to extend if renewal
                $userRows = \Illuminate\Support\Facades\DB::select("SELECT expiration_date, secret_key FROM `{$waDb}`.users WHERE id = ? LIMIT 1", [$uid]);
                $currExp = !empty($userRows[0]->expiration_date) ? (int)$userRows[0]->expiration_date : 0;
                $baseTime = ($currExp > time()) ? $currExp : time();
                $expDate = $baseTime + (86400 * $durationDays);

                // 1. Update user record (status = 2 is Active in Stackposts)
                \Illuminate\Support\Facades\DB::update(
                    "UPDATE `{$waDb}`.users SET plan_id = ?, expiration_date = ?, status = 2, last_login = ? WHERE id = ?",
                    [$planId, $expDate, time(), $uid]
                );

                // 2. Set payment columns on user if present
                try {
                    \Illuminate\Support\Facades\DB::update(
                        "UPDATE `{$waDb}`.users SET payment_method = 'razorpay', payment_status = 'approved', payment_reference = ? WHERE id = ?",
                        [$razorpayPaymentId, $uid]
                    );
                } catch (\Throwable $e) {}

                // 3. Assign plan permissions to user's team in teams
                if ($planObj && !empty($planObj->permissions)) {
                    try {
                        \Illuminate\Support\Facades\DB::update(
                            "UPDATE `{$waDb}`.teams SET permissions = ? WHERE owner = ?",
                            [$planObj->permissions, $uid]
                        );
                    } catch (\Throwable $e) {}
                }

                // 4. Record manual payment entry
                try {
                    $idSecure = \Illuminate\Support\Str::random(32);
                    \Illuminate\Support\Facades\DB::insert(
                        "INSERT INTO `{$waDb}`.payment_manual (id_secure, uid, plan_id, payment_id, payment_info, amount, currency, notes, status, changed, created)
                         VALUES (?, ?, ?, ?, ?, ?, 'INR', 'Online payment completed via checkout.youverse.in Razorpay', 1, ?, ?)",
                        [$idSecure, $uid, $planId, $razorpayPaymentId, 'Razorpay Payment ID: ' . $razorpayPaymentId, $price, time(), time()]
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("WABulk PaymentManual insert error: " . $e->getMessage());
                }

                // 5. Ensure secret_key matches token for instant SSO auto-login
                if (!empty($userRows[0]->secret_key)) {
                    $token = $userRows[0]->secret_key;
                } elseif (!empty($token)) {
                    \Illuminate\Support\Facades\DB::update("UPDATE `{$waDb}`.users SET secret_key = ? WHERE id = ?", [$token, $uid]);
                } else {
                    $token = \Illuminate\Support\Str::random(32);
                    \Illuminate\Support\Facades\DB::update("UPDATE `{$waDb}`.users SET secret_key = ? WHERE id = ?", [$token, $uid]);
                }
            } catch (\Throwable $ex) {
                \Illuminate\Support\Facades\Log::error("WABulk Razorpay Success DB activation error: " . $ex->getMessage());
            }
        }

        session()->forget(['wabulk_checkout', 'wb_razorpay_order_id']);

        $reqHost = strtolower(trim((string)(request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? ''))));
        $reqHost = preg_replace('/:\d+$/', '', $reqHost);
        $reqHost = preg_replace('/^www\./i', '', $reqHost);
        $cleanAgencyHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder|whatsappbulk|whatsapp-bulk|whatsapp|wa)\./i', '', $reqHost);
        $scheme = (request()->secure() || str_contains(request()->fullUrl(), 'https://') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')) ? 'https://' : 'http://';
        if (empty($cleanAgencyHost) || $cleanAgencyHost === 'localhost' || $cleanAgencyHost === '127.0.0.1') {
            $cleanAgencyHost = 'youverse.in';
        }

        // Redirect destination based on origin
        $redirectDest = ($source === 'renewal' || $source === 'profile') ? '/app/profile/plan' : '/app/dashboard';

        // Redirect directly to WhatsApp Bulk SSO login straight to the dashboard or profile with plan activation parameters!
        $ssoUrl = "{$scheme}whatsappbulk.{$cleanAgencyHost}/auth/sso_login?uid=" . $uid . "&token=" . urlencode($token) . "&plan_id=" . urlencode($planId) . "&plan_name=" . urlencode($planName) . "&payment_id=" . urlencode($razorpayPaymentId) . "&redirect=" . urlencode($redirectDest);
        return redirect()->away($ssoUrl);
    }
}
