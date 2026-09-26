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
        $pkgType = is_array($requestData) ? ($requestData['package_type'] ?? 'monthly') : 'monthly';
        $pkgId = is_array($requestData) ? ($requestData['package_id'] ?? null) : null;
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $pkgType, 'id' => $pkgId])->withInput(is_array($requestData) ? $requestData : []);
        } else {
            return redirect()->route('user.plan.extend.checkout', ['package_id' => $pkgId])->withInput(is_array($requestData) ? $requestData : []);
        }
    }
}
