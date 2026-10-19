<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbCustomer;
use App\Models\WebsiteBuilder\WbPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
        public function index()
    {
        WbCustomer::ensureColumnsExist();
        \App\Models\WebsiteBuilder\WbTemplatePurchase::ensureColumnsExist();

        // Auto-heal: If any WbCustomer has no WbTemplatePurchase, find or create one so payment record is never missing!
        $this->autoHealMissingPurchases();

        $customers = WbCustomer::with(['package', 'agencySetting'])->orderBy('created_at', 'desc')->paginate(15);
        $packages = WbPackage::where('is_active', true)->get();

        return view('website_builder.admin.customers.index', compact('customers', 'packages'));
    }

    private function autoHealMissingPurchases(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('wb_customers') || !\Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                return;
            }
            $customers = WbCustomer::all();
            foreach ($customers as $c) {
                if (!$c->latestPurchase) {
                    // Check if any proof file exists in public/uploads/payment_proofs/
                    $proofFile = null;
                    $proofDir = public_path('uploads/payment_proofs');
                    if (is_dir($proofDir)) {
                        $files = glob($proofDir . '/*.*');
                        if (!empty($files)) {
                            usort($files, function($a, $b) { return filemtime($b) - filemtime($a); });
                            $latestFile = $files[0];
                            if (filemtime($latestFile) >= (strtotime($c->created_at) - 3600)) {
                                $proofFile = 'uploads/payment_proofs/' . basename($latestFile);
                            }
                        }
                    }

                    \App\Models\WebsiteBuilder\WbTemplatePurchase::create([
                        'customer_id'    => $c->id,
                        'customer_name'  => $c->name,
                        'customer_email' => strtolower(trim($c->email)),
                        'customer_phone' => $c->phone,
                        'template_slug'  => $c->agencySetting->template_type ?? 'digital_agency',
                        'template_name'  => ucfirst(str_replace('_', ' ', $c->agencySetting->template_type ?? 'Digital Agency')),
                        'package_id'     => $c->package_id,
                        'payment_method' => 'UPI',
                        'transaction_id' => 'PENDING_' . strtoupper(\Illuminate\Support\Str::random(8)),
                        'payment_proof'  => $proofFile,
                        'amount'         => $c->package ? ($c->package->monthly_price ?? 499) : 499.00,
                        'currency'       => 'INR',
                        'status'         => 'Pending Verification',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("autoHealMissingPurchases error: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:wb_customers,email',
            'password'     => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'package_id'   => 'nullable|exists:wb_packages,id',
        ]);

        $subdomain = preg_replace('/[^a-z0-9]/', '', strtolower($request->name)) . rand(100, 999);

        WbCustomer::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'company_name' => $request->company_name,
            'subdomain'    => $subdomain,
            'package_id'   => $request->package_id,
            'status'       => 1,
        ]);

        return redirect()->back()->with('success', 'Customer registered successfully.');
    }

    public function approvePayment($purchaseId)
    {
        $purchase = \App\Models\WebsiteBuilder\WbTemplatePurchase::findOrFail($purchaseId);
        $purchase->status = 'completed';
        $purchase->save();

        return redirect()->back()->with('success', "Payment for {$purchase->customer_name} verified and approved successfully.");
    }

    public function secretLogin($id)
    {
        $customer = WbCustomer::findOrFail($id);

        $expires = time() + 300; // 5 minutes valid
        $secretKey = config('app.key', 'WebsiteBuilderSecretKey2026_Secure');
        $signature = hash_hmac('sha256', "{$customer->email}|{$expires}", $secretKey);

        $ssoUrl = route('website-builder.secret-login', [
            'email'     => $customer->email,
            'expires'   => $expires,
            'signature' => $signature,
            'agency'    => $customer->subdomain ?: $customer->company_name,
        ]);

        return redirect($ssoUrl);
    }

    public function updateExtraLimits(Request $request, $id)
    {
        WbCustomer::ensureColumnsExist();
        $customer = WbCustomer::findOrFail($id);

        $request->validate([
            'extra_portfolio_limit' => 'nullable|integer|min:0',
            'extra_services_limit'  => 'nullable|integer|min:0',
            'extra_blog_limit'      => 'nullable|integer|min:0',
        ]);

        $customer->update([
            'extra_portfolio_limit' => (int)$request->input('extra_portfolio_limit', 0),
            'extra_services_limit'  => (int)$request->input('extra_services_limit', 0),
            'extra_blog_limit'      => (int)$request->input('extra_blog_limit', 0),
        ]);

        return redirect()->back()->with('success', "Extra limits updated for {$customer->name} successfully.");
    }

    public function destroy($id)
    {
        $customer = WbCustomer::findOrFail($id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer account deleted.');
    }
}
