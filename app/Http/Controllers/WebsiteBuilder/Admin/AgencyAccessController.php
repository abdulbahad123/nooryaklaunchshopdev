<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;

class AgencyAccessController extends Controller
{
    public function index()
    {
        $product = [
            'name'       => 'Website Builder',
            'slug'       => 'website-builder',
            'status'     => 'Active',
            'launch_url' => url('/website-builder'),
            'preview_url'=> route('website-builder.index'),
            'secret_url' => route('website-builder.admin.customers.index'),
        ];

        return view('website_builder.admin.agency_access', compact('product'));
    }
}
