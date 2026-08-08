<?php

namespace App\Http\Controllers\User\Payment;

use Razorpay\Api\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends Controller
{
    public function __construct()
    {
        $_userCtx = getUser(); if (!$_userCtx) { return; }
        $user = $_userCtx; if (!$user) { return; }
        $data = UserPaymentGeteway::where('keyword', 'razorpay')->where('user_id', $_userCtx->id)->first();

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


    public function paymentProcess(Request $request, $_amount, $_item_number, $_cancel_url, $_success_url, $_title, $_description, $bs)
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
        Session::put('user_request', $request->all());
        Session::put('user_order_payment_id', $razorpayOrder['id']);

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
                "name" => $request->billing_fname,
                "email" => $request->billing_email,
                "contact" => $request->billing_number,
            ],
            "notes" => [
                "address" => $request->razorpay_address,
                "merchant_order_id" => $_item_number,
            ],
            "theme" => [
                "color" => "{{dddd}}"
            ],
            "order_id" => $razorpayOrder['id'],
        ];

        if ($bs->name !== 'INR') {
            $data['display_currency'] = $bs->sign;
            $data['display_amount'] = $displayAmount;
        }

        $json = json_encode($data);
        $displayCurrency = $bs->name;

        return view('user-front.razorpay', compact('data', 'displayCurrency', 'json', 'notify_url'));
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');

        $user = getUser();

        /** Get the payment ID before session clear **/
        $payment_id = Session::get('user_order_payment_id');
        $success = true;

        if (empty($request['razorpay_payment_id']) === false) {
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
            $txnId = UserPermissionHelper::uniqidReal(8);
            $chargeId = $request->paymentId;
            $order = Common::saveOrder($requestData, $txnId, $chargeId, 'Completed', 'online', $user->id);
            $order_id = $order->id;

            Common::saveOrderedItems($order_id);
            Common::generateInvoice($order, $user);
            $order = UserOrder::where('id', $order_id)->first();
            Common::OrderCompletedMail($order, $user);
            session()->flash('success', __('successful_payment'));
            Session::forget('user_request');
            Session::forget('cart_'.$user->username);
            Session::forget('user_amount');
            Session::forget('user_paypal_payment_id');
            return redirect()->route('customer.success.page', getParam());
        }
        return redirect()->route('customer.itemcheckout.cancel', getParam());
    }
}
