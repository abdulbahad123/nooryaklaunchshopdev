<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WbSuperAdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('website-builder.admin.login')
                ->with('alert', 'Please login to access the Super Admin Dashboard.');
        }

        return $next($request);
    }
}
