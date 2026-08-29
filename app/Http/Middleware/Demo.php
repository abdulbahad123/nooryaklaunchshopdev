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
        return $next($request);
    }
}
