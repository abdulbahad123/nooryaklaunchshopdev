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
    if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
      return redirect()->route('admin.dashboard');
    }
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

    return redirect('/X9_AdMiN-Portal_V7/dashboard');
  }

  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
  }
}
