<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * AchievementController
 *
 * Manages achievements/badges for the user's storefront.
 * This is a stub controller — full implementation requires
 * the achievements module to be set up.
 */
class AchievementController extends Controller
{
    public function index()
    {
        Session::flash('info', __('Achievements feature is not yet configured.'));
        return redirect()->route('user-dashboard');
    }

    public function store(Request $request)
    {
        return response()->json(['error' => __('Achievements feature is not yet configured.')], 501);
    }

    public function edit($id)
    {
        Session::flash('info', __('Achievements feature is not yet configured.'));
        return redirect()->route('user-dashboard');
    }

    public function update(Request $request)
    {
        return response()->json(['error' => __('Achievements feature is not yet configured.')], 501);
    }

    public function delete(Request $request)
    {
        return response()->json(['error' => __('Achievements feature is not yet configured.')], 501);
    }

    public function bulkDelete(Request $request)
    {
        return response()->json(['error' => __('Achievements feature is not yet configured.')], 501);
    }
}
