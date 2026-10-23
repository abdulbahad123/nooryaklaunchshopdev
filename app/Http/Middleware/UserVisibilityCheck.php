<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserVisibilityCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $host = strtolower($request->getHost());

        $user = getUser();
        if (empty($user) || !is_object($user) || !isset($user->id)) {
            // Allow platform root landing pages, checkout, and platform subdomains
            if (str_starts_with($host, 'launchshop.') || str_starts_with($host, 'checkout.') || str_starts_with($host, 'websitebuilder.') || str_starts_with($host, 'website-builder.')) {
                return $next($request);
            }
            if (\Illuminate\Support\Facades\Route::has('front.index')) {
                $routeUrl = route('front.index');
                if ($request->url() !== $routeUrl) {
                    return redirect()->route('front.index');
                }
            }
            return response()->view('errors.404', [], 404);
        }

        // 1. Demo / Template Preview: Always allow access
        if (isset($user->preview_template) && $user->preview_template == 1) {
            return $next($request);
        }

        // 2. Secret Login by Super Admin: Always allow
        if (Session::get('secrect_login') == true) {
            return $next($request);
        }

        // 3. User Banned or Deactivated by Admin
        if (isset($user->status) && $user->status == 0) {
            return response()->view('errors.store_expired', compact('user'), 402);
        }

        // 4. Online Status check (maintenance or offline toggle by user)
        if (Auth::check() && Auth::user()->id != $user->id && $user->online_status != 1) {
            if (\Illuminate\Support\Facades\Route::has('front.index')) {
                $routeUrl = route('front.index');
                if ($request->url() !== $routeUrl) {
                    return redirect()->route('front.index');
                }
            }
            return response()->view('errors.404', [], 404);
        } elseif (!Auth::check() && $user->online_status != 1) {
            if (\Illuminate\Support\Facades\Route::has('front.index')) {
                $routeUrl = route('front.index');
                if ($request->url() !== $routeUrl) {
                    return redirect()->route('front.index');
                }
            }
            return response()->view('errors.404', [], 404);
        }

        // 5. Subscription Expiry Check (Store Suspension Gatekeeper)
        try {
            $now = Carbon::now();

            // Check if there is an active membership with future expiration date
            $activeMembership = Membership::query()
                ->where('user_id', $user->id)
                ->where('status', 1)
                ->where('expire_date', '>', $now->toDateTimeString())
                ->first();

            // If no active future membership, the store is expired!
            if (!$activeMembership) {
                $latestMembership = Membership::query()
                    ->where('user_id', $user->id)
                    ->where('status', 1)
                    ->orderBy('expire_date', 'desc')
                    ->first();

                // If membership expired or missing, immediately suspend the public storefront
                $isSuspended = true;
                if ($latestMembership && !empty($latestMembership->expire_date)) {
                    $expireCarbon = Carbon::parse($latestMembership->expire_date);
                    // If still in the future, don't suspend
                    if ($expireCarbon->isFuture()) {
                        $isSuspended = false;
                    }
                }

                if ($isSuspended) {
                    return response()->view('errors.store_expired', [
                        'user' => $user,
                        'latestMembership' => $latestMembership ?? null,
                    ], 402);
                }
            }
        } catch (\Throwable $subCheckEx) {
            Log::warning("UserVisibilityCheck subscription check failed: " . $subCheckEx->getMessage());
        }

        return $next($request);
    }
}