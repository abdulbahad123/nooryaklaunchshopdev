<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
        $routeUsername = $request->route('username');
        if (!empty($routeUsername)) {
            $user = \App\Models\User::where('username', $routeUsername)->first();
            if (!$user) {
                abort(404);
            }
            if ($user->status == 0 && $user->online_status != 1 && $user->preview_template != 1) {
                if (!Auth::check() || Auth::user()->id != $user->id) {
                    abort(404);
                }
            }
            app()->instance('user', $user);
            return $next($request);
        }

        $user = getUser();
        if (empty($user) || !is_object($user) || !isset($user->id)) {
            abort(404);
        }

        // On custom domains, cleanly 301 redirect legacy trailing ?{username} queries (e.g. /shop?mercado -> /shop)
        if ($request->isMethod('GET') && function_exists('isTenantCustomDomainRequest') && isTenantCustomDomainRequest() && !empty($user->username)) {
            $rawQuery = $request->getQueryString();
            if ($rawQuery !== null && $rawQuery !== '') {
                $u = strtolower($user->username);
                if (strtolower($rawQuery) === $u || str_starts_with(strtolower($rawQuery), $u . '&') || preg_match('/^' . preg_quote($u, '/') . '(=|&|$)/i', $rawQuery)) {
                    parse_str($rawQuery, $queryParams);
                    unset($queryParams[$user->username], $queryParams[$u]);
                    $cleanUrl = $request->url() . (!empty($queryParams) ? '?' . http_build_query($queryParams) : '');
                    return redirect()->to($cleanUrl, 301);
                }
            }
        }

        if ($user->online_status != 1 && $user->preview_template != 1) {
            // Store owner can preview their own store
            if (Auth::check() && Auth::user()->id == $user->id) {
                return $next($request);
            }

            // Redirect to main platform index if available and not circular
            if (Route::has('front.index')) {
                $target = route('front.index');
                if ($request->url() !== $target) {
                    return redirect($target);
                }
            }

            // On custom domains where front.index isn't the current route or is unavailable, abort 404 cleanly
            abort(404);
        }

        return $next($request);
    }
}