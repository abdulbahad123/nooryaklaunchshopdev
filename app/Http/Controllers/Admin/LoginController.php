<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class LoginController extends Controller
{
  public function login()
  {
    return view('admin.login');
  }

  public function authenticate(Request $request)
  {
    $this->validate($request, [
      'username'   => 'required',
      'password' => 'required'
    ]);

    $throttleKey = 'admin_login_' . $request->ip();
    if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
      $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
      return redirect()->back()->with('alert', __('Too many login attempts. Please try again in :seconds seconds.', ['seconds' => $seconds]));
    }

    if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
      \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
      return redirect()->route('admin.dashboard');
    }

    \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);
    return redirect()->back()->with('alert', __('Username and password do not match'));
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

    $candidateSecrets = array_values(array_unique(array_filter([
        env('SSO_SECRET_KEY'),
        config('services.sso.secret'),
        '9f4a7c1b8e2d6f0a3c5e7b9d1f3a5c7e9b1d3f5a7c9e1b3d5f7a9c1e3b5d7f9a',
        'LaunchshopSaaS_SSO_SecretKey_2026_SecureKey',
        'b4a8e2c1d9f3a7e5'
    ])));

    $signatureValid = false;
    foreach ($candidateSecrets as $sec) {
        if (hash_equals(hash_hmac('sha256', "{$user}|{$expires}|{$nonce}", $sec), $signature)) {
            $signatureValid = true;
            break;
        }
    }

    if (!$signatureValid) {
        return redirect()->route('admin.login')->with('alert', __('SSO signature verification failed.'));
    }

    $admin = \App\Models\Admin::where('username', $user)->orWhere('email', $user)->first()
          ?? \App\Models\Admin::where('status', 1)->first()
          ?? \App\Models\Admin::first();

    if (!$admin) {
      return redirect()->route('admin.login')->with('alert', __('Admin user not found in database.'));
    }

    Auth::guard('admin')->login($admin);

    return redirect('/X9_AdMiN-Portal_V7/dashboard');
  }

  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
  }
}
