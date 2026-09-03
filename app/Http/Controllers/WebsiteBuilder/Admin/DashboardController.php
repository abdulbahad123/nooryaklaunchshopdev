<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbCustomer;
use App\Models\WebsiteBuilder\WbTemplate;
use App\Models\WebsiteBuilder\WbPackage;
use App\Models\WebsiteBuilder\WbTemplatePurchase;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = 0;
        $totalTemplates = 0;
        $totalPackages  = 0;
        $totalPurchases = 0;
        $recentCustomers = collect([]);
        $recentPurchases = collect([]);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $totalCustomers = WbCustomer::count();
                $recentCustomers = WbCustomer::orderBy('created_at', 'desc')->take(5)->get();
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_templates')) {
                $totalTemplates = WbTemplate::count();
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_packages')) {
                $totalPackages = WbPackage::count();
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                $totalPurchases = WbTemplatePurchase::count();
                $recentPurchases = WbTemplatePurchase::orderBy('created_at', 'desc')->take(5)->get();
            }
        } catch (\Throwable $e) {
            // Fail-safe fallback
        }

        return view('website_builder.admin.dashboard', compact(
            'totalCustomers',
            'totalTemplates',
            'totalPackages',
            'totalPurchases',
            'recentCustomers',
            'recentPurchases'
        ));
    }
}
