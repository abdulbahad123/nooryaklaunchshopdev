<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class Demo
{
    /**
     * Handle an incoming request.
     *
     * Block all write operations (POST / PUT / PATCH / DELETE) when either:
     *   1. The app is running in DEMO_MODE (env), OR
     *   2. The visitor is browsing via the template "Admin" button (secrect_login session).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Never restrict super admin actions in the admin portal
        if (Auth::guard('admin')->check() || $request->is('X9_AdMiN-Portal_V7*')) {
            return $next($request);
        }

        // Check if the authenticated user is a demo store (preview_template == 1)
        $user = Auth::guard('web')->user();
        $isDemoTheme = ($user && isset($user->preview_template) && $user->preview_template == 1);

        if ($isDemoTheme && in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Allow logout
            if ($request->is('user/logout') || $request->routeIs('user-logout')) {
                return $next($request);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('This is a Demo Theme. CRUD modifications are disabled in demo mode.')
                ], 403);
            }

            Session::flash('warning', __('This is a Demo Theme. CRUD modifications are disabled in demo mode.'));
            return back();
        }

        return $next($request);
    }
}
