<?php

namespace App\Exceptions;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Do not intercept validation errors or auth exceptions
        if ($exception instanceof \Illuminate\Validation\ValidationException ||
            $exception instanceof \Illuminate\Auth\AuthenticationException) {
            return parent::render($request, $exception);
        }

        // Check if current context is Admin or Debug mode
        $isAdminContext = false;
        try {
            $isAdminContext = \Illuminate\Support\Facades\Auth::guard('admin')->check()
                || $request->is('*AdMiN*')
                || $request->is('*admin*')
                || $request->has('debug')
                || $request->has('debug_error');
        } catch (\Throwable $authEx) {
            $isAdminContext = $request->is('*AdMiN*') || $request->is('*admin*');
        }

        // When in Admin portal OR APP_DEBUG is true:
        // Always display full developer diagnostics showing exact error, file, and line!
        if ($isAdminContext || config('app.debug')) {
            if (!($exception instanceof ModelNotFoundException) && !($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)) {
                try {
                    return response()->view('errors.admin_dev_error', [
                        'exception' => $exception,
                        'message' => $exception->getMessage(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTraceAsString(),
                    ], 500);
                } catch (\Throwable $renderEx) {
                    return parent::render($request, $exception);
                }
            }
        }

        // When APP_DEBUG is false (Production mode for normal visitors / customer stores):
        if (!config('app.debug')) {
            // If the request is for an expired user/store, show the branded Plan Expired page instead of generic error
            try {
                $tenantUser = null;
                if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
                    $tenantUser = \Illuminate\Support\Facades\Auth::guard('web')->user();
                } elseif (function_exists('getUser')) {
                    $u = getUser();
                    if (!empty($u) && is_object($u) && !empty($u->id)) {
                        $tenantUser = $u;
                    }
                }
                if ($tenantUser) {
                    $nowStr = \Carbon\Carbon::now()->toDateTimeString();
                    $hasActivePlan = \App\Models\Membership::where('user_id', $tenantUser->id)
                        ->where('status', 1)
                        ->where('start_date', '<=', $nowStr)
                        ->where('expire_date', '>=', $nowStr)
                        ->exists();
                    if (!$hasActivePlan && view()->exists('errors.store_expired')) {
                        $latestMembership = \App\Models\Membership::where('user_id', $tenantUser->id)
                            ->orderBy('expire_date', 'desc')
                            ->first();
                        return response()->view('errors.store_expired', [
                            'user' => $tenantUser,
                            'latestMembership' => $latestMembership,
                            'exception' => $exception
                        ], 402);
                    }
                }
            } catch (\Throwable $guardEx) {}

            // Handle 404 exceptions
            if ($exception instanceof ModelNotFoundException || $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                try {
                    if (view()->exists('errors.404')) {
                        return response()->view('errors.404', ['exception' => $exception], 404);
                    }
                } catch (\Throwable $e) {}
            }

            // For all public store / product crashes in production, display user-friendly "We Are Working On It"
            try {
                if (view()->exists('errors.working_on_it')) {
                    return response()->view('errors.working_on_it', ['exception' => $exception], 500);
                }
            } catch (\Throwable $e) {}
        }

        // When APP_DEBUG is true (Developer mode), render original detailed Laravel stack trace error page
        if ($exception instanceof ModelNotFoundException) {
            // normal 404 view page feedback

            // path based user URL
            if ((str_replace("www.", "", Request::getHost()) == env('WEBSITE_HOST') && strpos(Request::route()->getPrefix(), '{username}') !== false)) {
                $user = User::where('username', Request::route('username'))->where('online_status', 1);
                if ($user->count() > 0) {

                    $user = $user->first();
                    $userBs = $user->basic_setting;
                    return response()->view('errors.404', ['userBs' => $userBs, 'exception' => $exception], 404);
                } else {
                    return response()->view('errors.404', ['exception' => $exception], 404);
                }
            }

            // custom domain & subdomain based user URL
            elseif (Request::getHost() != env('WEBSITE_HOST')) {

                // if its a subdomain
                if (strpos(Request::getHost(), env('WEBSITE_HOST')) !== false) {
                    // if subdomain based URL, get username & fetch user & user_basic_settings
                    $host = Request::getHost();
                    $host = str_replace("www.", "", $host);
                    $hostArr = explode('.', $host);
                    $username = $hostArr[0];
                    $user = User::where('username', $username);
                    if ($user->count() > 0) {
                        $userBs = $user->first()->basic_setting;
                        return response()->view('errors.404', ['userBs' => $userBs, 'exception' => $exception], 404);
                    }
                } else {
                    $host = Request::getHost();
                    // Always include 'www.' at the begining of host
                    if (substr($host, 0, 4) == 'www.') {
                        $host = $host;
                    } else {
                        $host = 'www.' . $host;
                    }

                    $user = User::whereHas('user_custom_domains', function ($q) use ($host) {
                        $q->where('status', '=', 1)
                            ->where(function ($query) use ($host) {
                                $query->where('requested_domain', '=', $host)
                                    ->orWhere('requested_domain', '=', str_replace("www.", "", $host));
                            });
                        // fetch the custom domain , if it matches 'with www.' URL or 'without www.' URL
                    });

                    if ($user->count() > 0) {
                        $user = $user->first();
                        $userBs = $user->basic_setting;
                        return response()->view('errors.404', ['userBs' => $userBs, 'exception' => $exception], 404);
                    } else {
                        return response()->view('errors.404', ['exception' => $exception], 404);
                    }
                }
            }
            // main website 404 page
            else {
                return response()->view('errors.404', ['exception' => $exception], 404);
            }
        }
        return parent::render($request, $exception);
    }
}
