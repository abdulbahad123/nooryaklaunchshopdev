<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        // if the admin is logged in & he has a role defined then this check will be applied
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->role)) {
            $admin = Auth::guard('admin')->user();
            $permissions = json_decode($admin->role->permissions, true);
            if (is_null($permissions)) {
                return redirect()->route('admin.dashboard');
            }

            $permsRequired = explode('|', $permission);
            $hasPermission = false;

            foreach ($permsRequired as $perm) {
                $perm = trim($perm);
                if (is_array($permissions) && in_array($perm, $permissions)) {
                    $hasPermission = true;
                    break;
                }

                // If checking master 'Users Management', allow if admin has any sub-permission
                if ($perm === 'Users Management') {
                    $userSubPerms = ['Categories', 'Registered Users', 'Non-Verified Users', 'Subscribers', 'Mail to Subscribers'];
                    foreach ($userSubPerms as $subP) {
                        if (is_array($permissions) && in_array($subP, $permissions)) {
                            $hasPermission = true;
                            break 2;
                        }
                    }
                }
            }

            if (!$hasPermission) {
                return redirect()->route('admin.dashboard');
            }
        }
        return $next($request);
    }
}
