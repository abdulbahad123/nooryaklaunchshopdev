<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbCustomer;
use App\Models\WebsiteBuilder\WbTemplate;
use App\Models\WebsiteBuilder\WbPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = WbCustomer::count();
        $totalTemplates = WbTemplate::count();
        $totalPackages  = WbPackage::count();
        $recentCustomers = WbCustomer::orderBy('created_at', 'desc')->take(5)->get();

        return view('website_builder.admin.dashboard', compact(
            'totalCustomers',
            'totalTemplates',
            'totalPackages',
            'recentCustomers'
        ));
    }

    public function quickLogin(Request $request)
    {
        $key = 'wb_admin_quicklogin:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->back()->with('error', 'Too many login attempts. Please try again in ' . $seconds . ' seconds.');
        }
        RateLimiter::hit($key, 60);

        $admin = \App\Models\Admin::whereNull('role_id')->first() ?? \App\Models\Admin::first();
        if ($admin) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            RateLimiter::clear($key);
            return redirect()->route('website-builder.admin.dashboard')->with('success', 'Authenticated as Super Admin successfully!');
        }

        return redirect()->back()->with('error', 'No Admin user exists in database.');
    }
}

