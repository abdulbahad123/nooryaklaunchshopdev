<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('website-builder.admin.dashboard');
        }
        return view('website_builder.admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('website-builder.admin.dashboard');
        }

        // Also fallback to email authentication
        if (Auth::guard('admin')->attempt(['email' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('website-builder.admin.dashboard');
        }

        return redirect()->back()->with('alert', __('Invalid username/email or password credentials.'));
    }

    public function autoLogin(Request $request)
    {
        // 1-Click Auto Login for Website Builder Admin
        $admin = Admin::first();
        if (!$admin) {
            return redirect()->route('website-builder.admin.login')->with('alert', __('No Admin account found in the system.'));
        }

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->route('website-builder.admin.dashboard')->with('success', __('Auto-logged in successfully as Website Builder Admin.'));
    }

    public function ssoLogin(Request $request)
    {
        $user = $request->query('user');
        $expires = $request->query('expires');
        $nonce = $request->query('nonce');
        $signature = $request->query('signature');

        if (!$user || !$expires || !$nonce || !$signature) {
            return redirect()->route('website-builder.admin.login')->with('alert', __('Invalid SSO parameters.'));
        }

        if (time() > (int)$expires) {
            return redirect()->route('website-builder.admin.login')->with('alert', __('SSO link expired. Please try auto-login again.'));
        }

        $secret = env('SSO_SECRET_KEY', 'LaunchshopSaaS_SSO_SecretKey_2026_SecureKey');
        $expectedSignature = hash_hmac('sha256', "{$user}|{$expires}|{$nonce}", $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            return redirect()->route('website-builder.admin.login')->with('alert', __('SSO signature verification failed.'));
        }

        $admin = Admin::where('username', $user)->orWhere('email', $user)->first() ?? Admin::first();

        if (!$admin) {
            return redirect()->route('website-builder.admin.login')->with('alert', __('Admin user not found.'));
        }

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->route('website-builder.admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('website-builder.admin.login');
    }
}
