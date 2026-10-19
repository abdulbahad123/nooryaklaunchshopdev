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

// Expire Alert Routes
Route::get('/admin/expire-alert', [\App\Http\Controllers\ExpireAlertController::class, 'index'])->name('admin.expire_alert.index');
Route::post('/admin/expire-alert/update', [\App\Http\Controllers\ExpireAlertController::class, 'update'])->name('admin.expire_alert.update');
Route::post('/admin/expire-alert/extend-plan', [\App\Http\Controllers\ExpireAlertController::class, 'extendPlan'])->name('admin.expire_alert.extend_plan');
Route::post('/admin/expire-alert/send-whatsapp', [\App\Http\Controllers\ExpireAlertController::class, 'sendWhatsApp'])->name('admin.expire_alert.send_whatsapp');