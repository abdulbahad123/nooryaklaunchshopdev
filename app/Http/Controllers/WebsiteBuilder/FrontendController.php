<?php

namespace App\Http\Controllers\WebsiteBuilder;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbLandingSetting;
use App\Models\WebsiteBuilder\WbTemplate;
use App\Models\WebsiteBuilder\WbPackage;
use App\Models\WebsiteBuilder\WbCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function index()
    {
        $settings = WbLandingSetting::getSettings();
        $templates = WbTemplate::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $packages = WbPackage::where('is_active', true)->get();

        $razorpayGateway = \App\Models\PaymentGateway::where('keyword', 'razorpay')->first();
        $razorpayKey = 'rzp_test_T9UaATIMf1qeO8';
        if ($razorpayGateway && $razorpayGateway->information) {
            $info = json_decode($razorpayGateway->information, true);
            if (!empty($info['key'])) {
                $razorpayKey = $info['key'];
            }
        }

        return view('website_builder.front.index', compact('settings', 'templates', 'packages', 'razorpayKey', 'razorpayGateway'));
    }

    public function templates()
    {
        $settings = WbLandingSetting::getSettings();
        $templates = WbTemplate::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('website_builder.front.templates', compact('settings', 'templates'));
    }

    public function pricing()
    {
        $settings = WbLandingSetting::getSettings();
        $packages = WbPackage::where('is_active', true)->get();

        return view('website_builder.front.pricing', compact('settings', 'packages'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:wb_customers,email',
            'password'     => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
        ]);

        $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($request->name)) . rand(100, 999);

        $customer = WbCustomer::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'company_name' => $request->company_name ?? $request->name,
            'subdomain'    => $subdomain,
            'status'       => 1,
        ]);

        Auth::guard('wb_customer')->login($customer);

        return redirect()->route('website-builder.user.dashboard')->with('success', 'Account created successfully!');
    }

    public function secretLogin(Request $request)
    {
        $email     = $request->query('email');
        $expires   = $request->query('expires');
        $signature = $request->query('signature');

        if (!$email || !$expires || !$signature) {
            return redirect()->route('website-builder.index')->with('error', 'Invalid secret login request parameters.');
        }

        if (time() > (int)$expires) {
            return redirect()->route('website-builder.index')->with('error', 'Secret login link has expired.');
        }

        $secretKey = config('app.key', 'WebsiteBuilderSecretKey2026_Secure');
        $expectedSignature = hash_hmac('sha256', "{$email}|{$expires}", $secretKey);

        if (!hash_equals($expectedSignature, $signature)) {
            return redirect()->route('website-builder.index')->with('error', 'Secret login HMAC verification failed.');
        }

        $customer = WbCustomer::where('email', $email)->first();
        if (!$customer) {
            return redirect()->route('website-builder.index')->with('error', 'Customer account not found.');
        }

        Auth::guard('wb_customer')->login($customer);
        session(['is_secret_logged_in' => true]);

        return redirect()->route('website-builder.user.dashboard')->with('success', 'Logged in via Secret Admin Access.');
    }

    public function processRazorpay(Request $request)
    {
        $request->validate([
            'package_id' => 'required|integer',
            'email'      => 'required|email',
            'name'       => 'required|string',
        ]);

        $package = WbPackage::find($request->package_id);
        if (!$package) {
            return response()->json(['success' => false, 'message' => 'Package not found.'], 404);
        }

        $razorpayGateway = \App\Models\PaymentGateway::where('keyword', 'razorpay')->first();
        $keyId = 'rzp_test_T9UaATIMf1qeO8';
        $keySecret = 'BQ9Z865NgRQrrIMCusfzmskZ';

        if ($razorpayGateway && $razorpayGateway->information) {
            $info = json_decode($razorpayGateway->information, true);
            if (!empty($info['key'])) $keyId = $info['key'];
            if (!empty($info['secret'])) $keySecret = $info['secret'];
        }

        $amountInPaise = (int)round($package->monthly_price * 100);
        if ($amountInPaise <= 0) $amountInPaise = 100;

        try {
            $api = new \Razorpay\Api\Api($keyId, $keySecret);
            $order = $api->order->create([
                'receipt'         => 'wb_pkg_' . $package->id . '_' . time(),
                'amount'          => $amountInPaise,
                'currency'        => 'INR',
                'payment_capture' => 1
            ]);

            return response()->json([
                'success'     => true,
                'order_id'    => $order['id'],
                'key'         => $keyId,
                'amount'      => $amountInPaise,
                'name'        => $package->name . ' Plan',
                'description' => 'Subscription to ' . $package->name . ' tier',
                'prefill'     => [
                    'name'    => $request->name,
                    'email'   => $request->email,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function razorpayCallback(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $razorpayGateway = \App\Models\PaymentGateway::where('keyword', 'razorpay')->first();
        $keySecret = 'BQ9Z865NgRQrrIMCusfzmskZ';

        if ($razorpayGateway && $razorpayGateway->information) {
            $info = json_decode($razorpayGateway->information, true);
            if (!empty($info['secret'])) $keySecret = $info['secret'];
        }

        $expectedSignature = hash_hmac('sha256', $request->razorpay_order_id . '|' . $request->razorpay_payment_id, $keySecret);

        if (hash_equals($expectedSignature, $request->razorpay_signature)) {
            return response()->json([
                'success'    => true,
                'message'    => 'Payment verified successfully! Your subscription is now active.',
                'payment_id' => $request->razorpay_payment_id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment signature verification failed.'
        ], 400);
    }
}
