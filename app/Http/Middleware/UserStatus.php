<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class UserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::get('secrect_login') != true) {
            if (Auth::user()->status != 1) {
                Auth::guard('web')->logout();
                Session::flash('error', 'Your account has been banned!');
                return redirect(route('front.index'));
            }
        }

        // Post-expiration 1-day lock: After 24 hours of plan expiration, lock all navigation except renewal/payment
        $user = Auth::guard('web')->user();
        if ($user && empty($user->preview_template)) {
            try {
                $now = \Carbon\Carbon::now();
                $hasActiveMembership = \App\Models\Membership::where('user_id', $user->id)
                    ->where('status', 1)
                    ->where('start_date', '<=', $now->format('Y-m-d'))
                    ->where('expire_date', '>=', $now->toDateTimeString())
                    ->exists();

                if (!$hasActiveMembership) {
                    $latestMembership = \App\Models\Membership::where('user_id', $user->id)
                        ->where('status', '<>', 2)
                        ->orderBy('expire_date', 'desc')
                        ->first();

                    if ($latestMembership && !empty($latestMembership->expire_date) && strpos($latestMembership->expire_date, '9999') === false) {
                        $expireCarbon = \Carbon\Carbon::parse($latestMembership->expire_date);
                        $diffSeconds = $now->diffInSeconds($expireCarbon, false);

                        // Locked if expired for more than 2 minutes (120 seconds)
                        if ($diffSeconds < -120) {
                            $routeName = $request->route() ? $request->route()->getName() : '';
                            $allowedRoutes = [
                                'user-dashboard',
                                'user.plan.extend.index',
                                'user.plan.extend.checkout',
                                'user.plan.checkout',
                                'user.payment-log.index',
                                'user-logout',
                                'success.page',
                                'cancel.page',
                            ];

                            $isAllowed = in_array($routeName, $allowedRoutes)
                                || strpos($routeName, 'membership.') === 0
                                || strpos($routeName, 'user.payment.') === 0
                                || $request->is('user/dashboard')
                                || $request->is('user/membership*')
                                || $request->is('user/extend-membership*')
                                || $request->is('user/package/checkout*')
                                || $request->is('membership/*')
                                || $request->is('*/payment/*')
                                || $request->is('user/payment-log*')
                                || $request->is('user/logout');

                            if (!$isAllowed) {
                                Session::flash('warning', '🔒 Your store subscription has expired. Navigation has been locked. Please renew your plan now to restore full access.');
                                return redirect()->route('user.plan.extend.index');
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Log::warning("UserStatus expiry check error: " . $e->getMessage());
            }
        }
        return $next($request);
    }
}
