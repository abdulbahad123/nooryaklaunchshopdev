<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Notifications\PushDemo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Notification;
use Session;

class PushController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!\hasStaffPerm('Push Notification')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function settings()
    {
        return view('user.pushnotification.settings');
    }

    public function updateSettings(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $request->validate([
            'file' => [
                'required',
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
        ]);

        if ($request->hasFile('file')) {
            $user_id = Auth::user()->id;
            $filename = 'push_icon_' . $user_id . '.png';
            @unlink(public_path("assets/front/img/user/" . $filename));
            $dir = public_path('assets/front/img/user/');
            @mkdir($dir, 0775, true);
            $request->file('file')->move($dir, $filename);
        }

        session()->flash('success', __('Updated Successfully'));
        return back();
    }

    public function send()
    {
        $user_id = Auth::user()->id;
        $subscribers_count = Guest::where('user_id', $user_id)
            ->whereHas('pushSubscriptions')
            ->count();

        return view('user.pushnotification.send', compact('subscribers_count'));
    }

    public function push(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'button_url' => 'required',
            'button_text' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $title = $request->title;
        $message = $request->message;
        $buttonText = $request->button_text;
        $buttonURL = $request->button_url;

        $subscribers = Guest::where('user_id', $user_id)
            ->whereHas('pushSubscriptions')
            ->get();

        if ($subscribers->isEmpty()) {
            Session::flash('warning', __('No subscribers found for your store.'));
            return redirect()->route('user.pushnotification.send');
        }

        Notification::send($subscribers, new PushDemo($title, $message, $buttonText, $buttonURL));

        Session::flash('success', __('Push notification has been sent successfully'));
        return redirect()->route('user.pushnotification.send');
    }
}
