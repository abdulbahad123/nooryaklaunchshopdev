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

        if (session()->has('user_lang_' . $username)) {

            if (!is_null(getUserNullCheck())) {

                // Website language
                app()->setLocale(session()->get('user_lang_' . $username));
            } else {

                // Dashboard language
                if (Cookie::has($cookieName)) {

                    $cookieLangCode = Cookie::get($cookieName);

                    $isLang = UserLanguage::where([
                        ['code', $cookieLangCode],
                        ['user_id', $userId]
                    ])->exists();

                    if ($isLang) {

                        $userDashboardLang = UserLanguage::where([
                            ['code', $cookieLangCode],
                            ['user_id', $userId]
                        ])->first();
                    } else {

                        $userDashboardLang = UserLanguage::where('user_id', $userId)->first();

                        Cookie::queue($cookieName, $userDashboardLang->code, 60 * 24 * 30);
                    }
                } else {

                    $userDashboardLang = UserLanguage::where('user_id', $userId)->first();

                    Cookie::queue($cookieName, $userDashboardLang->code, 60 * 24 * 30);
                }

                if (!empty($userDashboardLang)) {
                    app()->setLocale('user_' . $userDashboardLang->code);
                }
            }
        } else {

            $default = Language::where('is_default', 1)->first();

            if (!empty($default)) {
                app()->setLocale('user_' . $default->code);
            }
        }

        return $next($request);
    }
}
