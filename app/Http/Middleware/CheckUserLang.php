<?php

namespace App\Http\Middleware;

use App\Models\Language;
use App\Models\User\Language as UserLanguage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;


class CheckUserLang
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user() ?? getUser();
        if (empty($user) || !is_object($user) || !isset($user->id)) {
            return $next($request);
        }
        $username = $user->username;
        $userId = $user->id;

        $cookieName = 'userDashboardLang_' . $userId;

        $isDashboard = is_null(getUserNullCheck()) || $request->is('user/*') || $request->is('user');

        if ($isDashboard) {
            // Dashboard language
            $dashboardLangCode = null;
            if (Cookie::has($cookieName)) {
                $dashboardLangCode = Cookie::get($cookieName);
            } elseif (session()->has('userDashboardLang')) {
                $dashboardLangCode = session()->get('userDashboardLang');
            }

            $userDashboardLang = null;
            if (!empty($dashboardLangCode)) {
                $userDashboardLang = UserLanguage::where('code', $dashboardLangCode)
                    ->where('user_id', $userId)
                    ->first();
            }

            if (empty($userDashboardLang)) {
                $userDashboardLang = UserLanguage::where('dashboard_default', 1)
                    ->where('user_id', $userId)
                    ->first();
            }

            if (empty($userDashboardLang)) {
                $userDashboardLang = UserLanguage::where('user_id', $userId)->first();
            }

            if (!empty($userDashboardLang)) {
                Cookie::queue($cookieName, $userDashboardLang->code, 60 * 24 * 30);
                app()->setLocale('user_' . $userDashboardLang->code);
            } else {
                $default = Language::where('is_default', 1)->first();
                if (!empty($default)) {
                    app()->setLocale('user_' . $default->code);
                }
            }
        } else {
            // Front store website language
            $userCurrentLang = app('userCurrentLang');
            if (!empty($userCurrentLang) && !empty($userCurrentLang->code)) {
                app()->setLocale($userCurrentLang->code);
            } elseif (session()->has('user_lang_' . $username)) {
                app()->setLocale(session()->get('user_lang_' . $username));
            }
        }

        return $next($request);
    }
}
