<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (str_starts_with($host, 'launchshop.') || str_starts_with($host, 'checkout.') || str_starts_with($host, 'websitebuilder.') || str_starts_with($host, 'website-builder.')) {
            return $next($request);
        }

        $user = getUser();
        if (empty($user) || !is_object($user) || !isset($user->id)) {
            if (\Illuminate\Support\Facades\Route::has('front.index')) {
                $routeUrl = route('front.index');
                if ($request->url() !== $routeUrl) {
                    return redirect()->route('front.index');
                }
            }
            return response()->view('errors.404', [], 404);
        }
        if (Auth::check() && Auth::user()->id != $user->id && $user->online_status != 1 && $user->preview_template != 1) {
            if (\Illuminate\Support\Facades\Route::has('front.index')) {
                $routeUrl = route('front.index');
                if ($request->url() !== $routeUrl) {
                    return redirect()->route('front.index');
                }
            }
            return response()->view('errors.404', [], 404);
        } elseif (!Auth::check() && $user->online_status != 1 && $user->preview_template != 1) {
            if (\Illuminate\Support\Facades\Route::has('front.index')) {
                $routeUrl = route('front.index');
                if ($request->url() !== $routeUrl) {
                    return redirect()->route('front.index');
                }
            }
            return response()->view('errors.404', [], 404);
        }
        return $next($request);
    }
}
