<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Session;
use App\Models\Language;
use Config;
use App\Models\BasicSetting as BS;
use App\Models\Seo;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
        $this->middleware('setlang');
        $bs = BS::first();

        Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
        Config::set('captcha.secret', $bs->google_recaptcha_secret_key);
    }

    public function showLoginForm()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        return view('front.auth.login', $data);
    }

    public function login(Request $request)
    {

        if (Session::has('link')) {
            $redirectUrl = Session::get('link');
            Session::forget('link');
        } else {
            $redirectUrl = route('user-dashboard');
        }


        //--- Validation Section
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;



        $rules = [
            'email'    => 'required',
            'password' => 'required'
        ];

        if ($bs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
        $messages = [
            'g-recaptcha-response.required' => __('Please verify that you are not a robot.'),
            'g-recaptcha-response.captcha' => __('Captcha error! try again later or contact site admin.'),
        ];
        $request->validate($rules, $messages);
        //--- Validation Section Ends

        // Resolve user by Email or Phone number
        $loginField = $request->email;
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $loginField,
                'password' => $request->password
            ];
        } else {
            // Find by phone number
            $phone = preg_replace('/[^0-9]/', '', $loginField);
            $user = User::where('phone', $phone)
                ->orWhere(DB::raw("CONCAT(country_code, phone)"), $phone)
                ->orWhere(DB::raw("CONCAT(REPLACE(country_code, '+', ''), phone)"), $phone)
                ->first();
            
            if ($user) {
                $credentials = [
                    'email' => $user->email,
                    'password' => $request->password
                ];
            } else {
                $credentials = [
                    'email' => '',
                    'password' => $request->password
                ];
            }
        }

        // Attempt to log the user in
        if (Auth::guard('web')->attempt($credentials)) {
            Session::forget(['staff_id', 'staff_name', 'staff_role', 'staff_permissions']);

            // Check If Email is verified or not
            if (Auth::guard('web')->user()->email_verified == 0) {
                Auth::guard('web')->logout();

                return back()->with('err', __('Your Email is not Verified').'!');
            }
            if (Auth::guard('web')->user()->status == '0') {
                Auth::guard('web')->logout();

                return back()->with('err', __('Your account is disabled'));
            }
            return redirect($redirectUrl);
        }

        // Attempt staff login
        $staff = \App\Models\User\UserStaff::where('email', $loginField)
            ->orWhere('username', $loginField)
            ->first();

        if ($staff && \Illuminate\Support\Facades\Hash::check($request->password, $staff->password)) {
            if ($staff->status == 0) {
                return back()->with('err', __('Your staff account is disabled'));
            }

            $merchant = $staff->merchant;
            if ($merchant && $merchant->status == 1) {
                Auth::guard('web')->login($merchant);

                $permissions = [];
                if ($staff->role && !empty($staff->role->permissions)) {
                    $permissions = json_decode($staff->role->permissions, true);
                }

                $staff->last_login_at = \Carbon\Carbon::now();
                $staff->save();

                Session::put('staff_id', $staff->id);
                Session::put('staff_name', trim($staff->first_name . ' ' . $staff->last_name));
                Session::put('staff_role', $staff->role ? $staff->role->name : 'Staff');
                Session::put('staff_permissions', $permissions);

                return redirect($redirectUrl);
            }
        }

        // if unsuccessful, then redirect back to the login with the form data
        return back()->with('err', __("Credentials Doesn't Match!"))->withInput();
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }

    public function loginWithOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'otp'   => 'required'
        ]);

        $phone = $request->phone;
        $otp = $request->otp;

        $sessionOtp = Session::get('otp_code');
        $sessionPhone = Session::get('otp_phone');
        $sessionExpiresAt = Session::get('otp_expires_at');

        if (!$sessionOtp || !$sessionPhone || !$sessionExpiresAt) {
            return response()->json([
                'success' => false,
                'message' => __('No OTP request found. Please request a new OTP.')
            ], 400);
        }

        if (time() > $sessionExpiresAt) {
            return response()->json([
                'success' => false,
                'message' => __('OTP has expired. Please request a new OTP.')
            ], 400);
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $cleanSessionPhone = preg_replace('/[^0-9]/', '', $sessionPhone);

        if ($otp != $sessionOtp || substr($cleanPhone, -10) !== substr($cleanSessionPhone, -10)) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid OTP. Please try again.')
            ], 400);
        }

        // Find user by phone number
        $user = User::where('phone', $cleanPhone)
            ->orWhere(DB::raw("CONCAT(country_code, phone)"), $cleanPhone)
            ->orWhere(DB::raw("CONCAT(REPLACE(country_code, '+', ''), phone)"), $cleanPhone)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('No account found with this phone number.')
            ], 400);
        }

        if ($user->status == '0') {
            return response()->json([
                'success' => false,
                'message' => __('Your account is disabled.')
            ], 400);
        }

        // Log the user in
        Auth::guard('web')->login($user);

        // Clear OTP sessions
        Session::forget(['otp_code', 'otp_phone', 'otp_expires_at']);

        if (Session::has('link')) {
            $redirectUrl = Session::get('link');
            Session::forget('link');
        } else {
            $redirectUrl = route('user-dashboard');
        }

        return response()->json([
            'success' => true,
            'redirect' => $redirectUrl
        ]);
    }
}
