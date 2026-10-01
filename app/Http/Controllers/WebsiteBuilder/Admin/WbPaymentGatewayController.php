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
                'keyword'     => 'razorpay',
                'information' => json_encode([
                    'key'      => 'rzp_test_T9UaATIMf1qeO8',
                    'secret'   => 'BQ9Z865NgRQrrIMCusfzmskZ',
                    'currency' => 'INR',
                    'status'   => 1
                ])
            ]);
        }

        $upi = PaymentGateway::where('name', 'UPI')->first() ?? PaymentGateway::where('keyword', 'upi')->first();
        if (!$upi) {
            $upi = PaymentGateway::create([
                'title'       => 'UPI / QR Code Payment',
                'name'        => 'UPI',
                'type'        => 'manual',
                'keyword'     => 'upi',
                'information' => json_encode([
                    'upi_id'        => 'launchshop@ybl',
                    'holder_name'   => 'Website Builder',
                    'qr_code_image' => '',
                    'instructions'  => 'Scan the QR code using any UPI App (Google Pay, PhonePe, Paytm, BHIM) or send payment directly to the UPI ID above. Upload your transaction proof screenshot and enter the 12-digit UTR/Ref number to finish.',
                    'status'        => 1
                ])
            ]);
        }

        $info = is_string($razorpay->information) ? json_decode($razorpay->information, true) : ($razorpay->information ?? []);
        $upiInfo = is_string($upi->information) ? json_decode($upi->information, true) : ($upi->information ?? []);

        return view('website_builder.admin.payments.index', compact('razorpay', 'info', 'upi', 'upiInfo'));
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
        if ($request->has('gateway_type') && $request->gateway_type === 'upi') {
            return $this->updateUpi($request);
        }

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
            $razorpay->status = $info['status'];
            $razorpay->save();
        }

        return redirect()->back()->with('success', __('Razorpay gateway credentials updated successfully.'));
    }

    public function updateUpi(Request $request)
    {
        $request->validate([
            'upi_id'      => 'required|string|max:255',
            'holder_name' => 'required|string|max:255',
            'instructions'=> 'nullable|string',
            'qr_code'     => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        $upi = PaymentGateway::where('name', 'UPI')->first() ?? PaymentGateway::where('keyword', 'upi')->first();
        if (!$upi) {
            $upi = new PaymentGateway();
            $upi->name = 'UPI';
            $upi->title = 'UPI / QR Code Payment';
            $upi->type = 'manual';
            $upi->keyword = 'upi';
        }

        $currentInfo = is_string($upi->information) ? json_decode($upi->information, true) : ($upi->information ?? []);

        $qrCodePath = $currentInfo['qr_code_image'] ?? '';
        if ($request->hasFile('qr_code')) {
            $file = $request->file('qr_code');
            $fileName = 'upi_qr_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads/gateways/'), $fileName);
            $qrCodePath = 'assets/uploads/gateways/' . $fileName;
        }

        $newInfo = [
            'upi_id'        => trim($request->upi_id),
            'holder_name'   => trim($request->holder_name),
            'qr_code_image' => $qrCodePath,
            'instructions'  => trim($request->instructions ?? ''),
            'status'        => $request->has('status') ? 1 : 0
        ];

        $upi->information = json_encode($newInfo);
        $upi->status = $newInfo['status'];
        $upi->save();

        return redirect()->back()->with('success', __('UPI Payment Gateway configured successfully!'));
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
