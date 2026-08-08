<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * PostController (Ads/Reports)
 *
 * Handles advertisement post reports for the user dashboard.
 * This is a stub controller — the feature was referenced in routes
 * but the controller was missing from the codebase.
 */
class PostController extends Controller
{
    public function viewReports()
    {
        Session::flash('info', __('Ads Reports feature is not yet configured.'));
        return redirect()->route('user-dashboard');
    }
}
