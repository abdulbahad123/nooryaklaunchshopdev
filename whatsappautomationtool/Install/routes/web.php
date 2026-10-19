<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Direct Admin Route - ALWAYS opens the Admin Dashboard
Route::get('/admin', function () {
    if (Auth::check() && (int)Auth::user()->role === 2) {
        session(['login_as' => 'admin']);
        return redirect('/admin/dashboard');
    }

    $admin = User::where('role', 2)->first();
    if ($admin) {
        Auth::login($admin, true);
        session(['login_as' => 'admin']);
        return redirect('/admin/dashboard');
    }

    return redirect('/auth/login');
});

// Admin SSO Login Route (Direct One-Click Admin Access from SaaS Admin / Agency Portal)
Route::get('/admin/sso-login', function (\Illuminate\Http\Request $request) {
    $admin = User::where('role', 2)->first();
    if ($admin) {
        Auth::login($admin, true);
        session(['login_as' => 'admin']);
        return redirect('/admin/dashboard');
    }
    return redirect('/auth/login');
});

// Sidebar Switcher to Admin Dashboard
Route::get('/auth/login-as-admin', function () {
    if (Auth::check() && (int)Auth::user()->role === 2) {
        session(['login_as' => 'admin']);
        return redirect('/admin/dashboard');
    }

    $admin = User::where('role', 2)->first();
    if ($admin) {
        Auth::login($admin, true);
        session(['login_as' => 'admin']);
        return redirect('/admin/dashboard');
    }

    return redirect('/auth/login');
});

// Sidebar Switcher to User Dashboard
Route::get('/auth/login-as-user', function () {
    if (Auth::check()) {
        session(['login_as' => 'client']);
        return redirect('/app/dashboard');
    }
    return redirect('/auth/login');
});
// Google Pay & UPI Auto-Sync Webhook
Route::match(['get', 'post'], '/api/gpay-sync', [\App\Http\Controllers\UpiSyncController::class, 'handleSync']);
Route::match(['get', 'post'], '/api/upi-sync', [\App\Http\Controllers\UpiSyncController::class, 'handleSync']);
