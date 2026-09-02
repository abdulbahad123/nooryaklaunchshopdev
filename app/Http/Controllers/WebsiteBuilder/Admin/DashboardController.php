<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbCustomer;
use App\Models\WebsiteBuilder\WbTemplate;
use App\Models\WebsiteBuilder\WbPackage;

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
}
