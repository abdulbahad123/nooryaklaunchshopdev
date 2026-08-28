<?php

namespace App\Http\Middleware;

use App\Models\User\BasicSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserMaintenance
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
        try {
            $user = getUser();

            // If user could not be resolved, just pass through — another middleware handles visibility
            if (empty($user) || !is_object($user) || !isset($user->id)) {
                return $next($request);
            }

            $basicSetting = $user->basic_setting;

            // If no basic_setting record exists, the store is not in maintenance — pass through
            if (empty($basicSetting)) {
                return $next($request);
            }

            $maintenanceStatus = $basicSetting->maintenance_status ? true : false;
            $token = $basicSetting->bypass_token;

            if ($maintenanceStatus == 1) {
                if (session()->has('user-bypass-token') && session()->get('user-bypass-token') == $token) {
                    return $next($request);
                }
                $userBs = BasicSetting::select('maintenance_msg', 'maintenance_img', 'favicon')
                    ->where('user_id', $user->id)
                    ->first();
                $data['userBs'] = $userBs;
                return response()->view('errors.user-503', $data);
            }

            return $next($request);
        } catch (\Throwable $th) {
            // Log the error but DO NOT abort(404) — let the request continue normally.
            // Aborting here causes hard-refresh 404s when DB connection is briefly unavailable.
            Log::warning('UserMaintenance middleware error: ' . $th->getMessage(), [
                'url'  => $request->fullUrl(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);
            return $next($request);
        }
    }
}
