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
        $customers = WbCustomer::with('package')->orderBy('created_at', 'desc')->paginate(15);
        $packages = WbPackage::where('is_active', true)->get();

        return view('website_builder.admin.customers.index', compact('customers', 'packages'));
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
        ]);

        return redirect($ssoUrl);
    }

    public function destroy($id)
    {
        $customer = WbCustomer::findOrFail($id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer account deleted.');
    }
}
