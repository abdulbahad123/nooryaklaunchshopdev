<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;

class WbPaymentGatewayController extends Controller
{
    public function index()
    {
        $razorpay = PaymentGateway::where('name', 'Razorpay')->first() ?? PaymentGateway::where('keyword', 'razorpay')->first();
        if (!$razorpay) {
            $razorpay = PaymentGateway::create([
                'title'       => 'Razorpay',
                'name'        => 'Razorpay',
                'type'        => 'automatic',
                'information' => json_encode([
                    'key'      => 'rzp_test_samplekey123',
                    'secret'   => 'sample_secret_key_456',
                    'currency' => 'INR',
                    'status'   => 1
                ])
            ]);
        }

        $info = is_string($razorpay->information) ? json_decode($razorpay->information, true) : ($razorpay->information ?? []);
        return view('website_builder.admin.payments.index', compact('razorpay', 'info'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'key'      => 'required|string',
            'secret'   => 'required|string',
            'currency' => 'required|string',
        ]);

        $razorpay = PaymentGateway::where('name', 'Razorpay')->first() ?? PaymentGateway::where('keyword', 'razorpay')->first();
        if ($razorpay) {
            $info = [
                'key'      => trim($request->key),
                'secret'   => trim($request->secret),
                'currency' => strtoupper(trim($request->currency)),
                'status'   => $request->has('status') ? 1 : 0
            ];
            $razorpay->information = json_encode($info);
            $razorpay->save();
        }

        return redirect()->back()->with('success', __('Razorpay gateway credentials updated successfully.'));
    }

    public function verifyRazorpay(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $razorpay = PaymentGateway::where('name', 'Razorpay')->first() ?? PaymentGateway::where('keyword', 'razorpay')->first();
        $info = json_decode($razorpay->information ?? '{}', true);
        $secret = $info['secret'] ?? '';

        $generatedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . "|" . $request->razorpay_payment_id,
            $secret
        );

        if (hash_equals($generatedSignature, $request->razorpay_signature)) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Payment verified successfully.'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Razorpay signature verification failed.'
        ], 400);
    }
}
