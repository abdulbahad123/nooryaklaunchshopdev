<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;

class WbPaymentGatewayController extends Controller
{
    public function index()
    {
        $this->ensurePaymentGatewaysTableExists();
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

    private function ensurePaymentGatewaysTableExists(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('payment_gateways')) {
                \Illuminate\Support\Facades\DB::statement("
                    CREATE TABLE IF NOT EXISTS `payment_gateways` (
                      `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                      `subtitle` text DEFAULT NULL,
                      `title` varchar(255) DEFAULT NULL,
                      `details` text DEFAULT NULL,
                      `name` varchar(255) DEFAULT NULL,
                      `type` varchar(255) DEFAULT NULL,
                      `information` text DEFAULT NULL,
                      `keyword` varchar(255) DEFAULT NULL,
                      `status` tinyint(4) NOT NULL DEFAULT 1,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ");
            }
        } catch (\Throwable $e) {}
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
