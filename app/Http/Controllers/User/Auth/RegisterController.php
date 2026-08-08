<?php

namespace App\Http\Controllers\User\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Models\User;
use Auth;
use Session;
use App\Models\Language;
use Config;
use App\Models\BasicSetting as BS;
use App\Models\BasicExtended as BE;
use Illuminate\Support\Facades\Artisan;


class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('setlang');
        $bs = BS::first();

        Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
        Config::set('captcha.secret', $bs->google_recaptcha_secret_key);
    }

    public function register(Request $request)
    {

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;

        $messages = [
            'g-recaptcha-response.required' => __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => __('Captcha error! try again later or contact site admin'),
        ];

        $rules = [
            'email'   => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed'
        ];

        if ($bs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules, $messages);

        $user = new User;
        $input = $request->all();
        $input['status'] = 1;
        $input['password'] = bcrypt($request['password']);
        $token = md5(time() . $request->name . $request->email);
        $input['verification_link'] = $token;
        $user->fill($input)->save();


        $mailer = new MegaMailer();
        $data = [
            'toMail' => $user->email,
            'toName' => $user->fname,
            'customer_name' => $user->fname,
            'verification_link' => "<a href='" . url('register/verify/' . $token) . "' style=\"display: inline-block; padding: 10px 20px; font-family: sans-serif; font-size: 14px; font-weight: bold; color: #ffffff; background-color: #007bff; border-radius: 6px; text-decoration: none;\">Click Here</a>",
            'website_title' => $bs->website_title,
            'templateType' => 'email_verification',
            'type' => 'emailVerification',
            'password' => $request->password,
            'login_link' => route('user.login')
        ];
        $mailer->mailFromAdmin($data);

        return back()->with('sendmail', __('We need to verify your email address. We have sent an email to') . ' ' . $request->email . ' ' . __('to verify your email address. Please click link in that email to continue'));
    }


    public function token($mode, $token)
    {
        $user = User::where('verification_link', $token)->first();
        if (isset($user)) {
            $user->email_verified = 1;
            $user->update();
            
            $sourceUsername = session('data.selected_template', session('selected_template', ''));
            $seedArgs = [
                'user' => $user->id,
                '--force' => true,
            ];

            if (!empty($sourceUsername)) {
                $seedArgs['--source'] = $sourceUsername;
            }

            // Automatically seed template catalog for new user.
            try {
                Artisan::call('template:seed-user', $seedArgs);
            } catch (\Exception $e) {
                \Log::warning('Template seeding failed for user ' . $user->id . ': ' . $e->getMessage());
            }
            
            Session::flash('success', __('Email Verified Successfully'));
            if ($mode === "online") {
                Auth::guard('web')->login($user);
                return redirect()->route('user-dashboard');
            } else {
                return redirect()->route('user.login');
            }
        }
    }
}
