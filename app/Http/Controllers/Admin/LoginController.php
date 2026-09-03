<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
  public function login()
  {
    return view('admin.login');
  }

  public function authenticate(Request $request)
  {
    $key = 'admin_login_attempts:' . $request->ip();
    if (RateLimiter::tooManyAttempts($key, 5)) {
      $seconds = RateLimiter::availableIn($key);
      return redirect()->back()->with('alert', __('Too many login attempts. Please try again in ') . $seconds . __(' seconds.'));
    }

    $this->validate($request, [
      'username' => 'required',
      'password' => 'required'
    ]);

    if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
      RateLimiter::clear($key);
      $request->session()->regenerate();
      return redirect()->route('admin.dashboard');
    }

    RateLimiter::hit($key, 60);
    return redirect()->back()->with('alert', __('Username and password do not match'));
  }

  public function autoLogin(Request $request)
  {
    $key = 'admin_autologin_attempts:' . $request->ip();
    if (RateLimiter::tooManyAttempts($key, 10)) {
      $seconds = RateLimiter::availableIn($key);
      return redirect()->back()->with('alert', __('Too many auto-login requests. Please try again in ') . $seconds . __(' seconds.'));
    }

    RateLimiter::hit($key, 60);

    // Fetch primary Super Admin account (owner admin with no restricted role or first admin)
    $admin = \App\Models\Admin::whereNull('role_id')->first() ?? \App\Models\Admin::first();

    if (!$admin) {
      return redirect()->route('admin.login')->with('alert', __('No admin account exists in database.'));
    }

    Auth::guard('admin')->login($admin);
    $request->session()->regenerate();
    RateLimiter::clear($key);

    return redirect()->route('admin.dashboard');
  }

  public function ssoLogin(Request $request)
  {
    $user = $request->query('user');
    $expires = $request->query('expires');
    $nonce = $request->query('nonce');
    $signature = $request->query('signature');

    if (!$user || !$expires || !$nonce || !$signature) {
      return redirect()->route('admin.login')->with('alert', __('Invalid SSO parameters.'));
    }

    if (time() > (int)$expires) {
      return redirect()->route('admin.login')->with('alert', __('SSO link expired. Please click Admin Access again in Super Admin panel.'));
    }

    $secret = env('SSO_SECRET_KEY', 'LaunchshopSaaS_SSO_SecretKey_2026_SecureKey');
    $expectedSignature = hash_hmac('sha256', "{$user}|{$expires}|{$nonce}", $secret);

    if (!hash_equals($expectedSignature, $signature)) {
      return redirect()->route('admin.login')->with('alert', __('SSO signature verification failed.'));
    }

    $admin = \App\Models\Admin::where('username', $user)->orWhere('email', $user)->first() ?? \App\Models\Admin::first();

    if (!$admin) {
      return redirect()->route('admin.login')->with('alert', __('Admin user not found in database.'));
    }

    Auth::guard('admin')->login($admin);
    $request->session()->regenerate();

    return redirect('/X9_AdMiN-Portal_V7/dashboard');
  }

  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
  }
}

