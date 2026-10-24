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

// WhatsApp Bulk SSO Login (Instant Auto-Login from checkout.youverse.in after payment)
Route::get('/auth/sso_login', function (\Illuminate\Http\Request $request) {
    $uid = $request->query('uid');
    $token = $request->query('token');
    $planId = $request->query('plan_id');
    $planName = $request->query('plan_name') ?: $request->query('plan');
    $paymentId = $request->query('payment_id') ?: $request->query('razorpay_payment_id');

    if ($uid && $token) {
        $user = \App\Models\User::find($uid);
        if ($user && ($user->secret_key === $token || hash_equals((string)$user->secret_key, (string)$token))) {
            // Activate Plan via WhatsApp Bulk's own Plan Service!
            try {
                $targetPlan = null;
                if ($planId) {
                    $targetPlan = class_exists('\Modules\AdminPlans\Models\Plans') ? \Modules\AdminPlans\Models\Plans::find($planId) : null;
                }
                if (!$targetPlan && $planName && class_exists('\Modules\AdminPlans\Models\Plans')) {
                    $targetPlan = \Modules\AdminPlans\Models\Plans::whereRaw('LOWER(name) = ?', [strtolower($planName)])
                        ->orWhere('name', 'LIKE', '%' . $planName . '%')
                        ->first();
                }
                if (!$targetPlan && (!$user->plan_id || $user->plan_id == 0) && class_exists('\Modules\AdminPlans\Models\Plans')) {
                    $targetPlan = \Modules\AdminPlans\Models\Plans::where('free_plan', 0)->first() 
                               ?? \Modules\AdminPlans\Models\Plans::first();
                }

                if ($targetPlan) {
                    $user->plan_id = $targetPlan->id;
                    $cycleDays = ($targetPlan->type == 1) ? 30 : 365;
                    $user->expiration_date = time() + (86400 * $cycleDays);
                    if (class_exists('\Plan') && method_exists('\Plan', 'updatePlanForTeam')) {
                        \Plan::updatePlanForTeam($targetPlan->id, $user->id);
                    }
                }

                if (\Schema::hasColumn('users', 'payment_method')) {
                    $user->payment_method = 'razorpay';
                    $user->payment_status = 'approved';
                    if ($paymentId) {
                        $user->payment_reference = $paymentId;
                    }
                    $user->status = 2;
                }
                $user->save();

                // Ensure Manual Payment record exists
                if ($paymentId && class_exists('\Modules\AdminManualPayments\Models\PaymentManual')) {
                    $exists = \Modules\AdminManualPayments\Models\PaymentManual::where('payment_id', $paymentId)->first();
                    if (!$exists) {
                        \Modules\AdminManualPayments\Models\PaymentManual::create([
                            'id_secure'    => rand_string(),
                            'uid'          => $user->id,
                            'plan_id'      => $targetPlan ? $targetPlan->id : null,
                            'payment_id'   => $paymentId,
                            'payment_info' => 'Razorpay Payment ID: ' . $paymentId,
                            'amount'       => $targetPlan ? $targetPlan->price : 39,
                            'currency'     => 'INR',
                            'notes'        => 'Online payment completed via checkout.youverse.in Razorpay',
                            'status'       => 1, // 1 = approved
                            'created'      => time(),
                            'changed'      => time(),
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                \Log::error("SSO Login plan activation error: " . $e->getMessage());
            }

            Auth::login($user, true);
            session(['login_as' => 'client']);
            $user->last_login = time();
            $user->save();
            return redirect('/app/dashboard');
        }
    }

    return redirect('/auth/login');
});

Route::get('/dashboard', function () {
    if (Auth::check()) {
        session(['login_as' => 'client']);
        return redirect('/app/dashboard');
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