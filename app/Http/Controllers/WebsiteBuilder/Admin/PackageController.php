<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index()
    {
        WbPackage::ensureColumnsExist();
        $landing = \App\Models\WebsiteBuilder\WbLandingSetting::first();
        $defaultServices  = (int)($landing->default_services_limit ?? 100);
        $defaultPortfolio = (int)($landing->default_portfolio_limit ?? 100);
        $defaultBlogs     = (int)($landing->default_blog_limit ?? 300);

        // Auto-sync packages that are null or at old hardcoded default 10 to platform defaults (100 services/portfolio, 300 blogs)
        try {
            WbPackage::whereNull('services_limit')
                ->orWhere('services_limit', '<=', 10)
                ->update(['services_limit' => $defaultServices]);

            WbPackage::whereNull('portfolio_limit')
                ->orWhere('portfolio_limit', '<=', 10)
                ->update(['portfolio_limit' => $defaultPortfolio]);

            WbPackage::whereNull('blog_limit')
                ->orWhere('blog_limit', '<=', 10)
                ->update(['blog_limit' => $defaultBlogs]);
        } catch (\Throwable $e) {}

        $packages = WbPackage::orderBy('id', 'asc')->get();
        return view('website_builder.admin.packages.index', compact('packages', 'defaultServices', 'defaultPortfolio', 'defaultBlogs'));
    }

    public function store(Request $request)
    {
        WbPackage::ensureColumnsExist();
        $request->validate([
            'name'          => 'required|string|max:255',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price'  => 'required|numeric|min:0',
        ]);

        $landing = \App\Models\WebsiteBuilder\WbLandingSetting::first();
        $defaultServices  = (int)($landing->default_services_limit ?? 100);
        $defaultPortfolio = (int)($landing->default_portfolio_limit ?? 100);
        $defaultBlogs     = (int)($landing->default_blog_limit ?? 300);

        WbPackage::create([
            'name'                  => $request->name,
            'slug'                  => Str::slug($request->name),
            'monthly_price'         => $request->monthly_price,
            'yearly_price'          => $request->yearly_price,
            'max_websites'          => 1,
            'theme_limit'           => $request->input('theme_limit', 10),
            'storage_limit_mb'      => $request->storage_limit_mb ?? 5000,
            'portfolio_limit'       => $request->input('portfolio_limit', $defaultPortfolio),
            'services_limit'        => $request->input('services_limit', $defaultServices),
            'blog_limit'           => $request->input('blog_limit', $defaultBlogs),
            'contact_form_allowed'  => $request->has('contact_form_allowed'),
            'map_section_allowed'    => $request->has('map_section_allowed'),
            'custom_domain_allowed' => $request->has('custom_domain_allowed'),
            'call_whatsapp_allowed' => $request->has('call_whatsapp_allowed'),
            'blog_allowed'          => $request->has('blog_allowed'),
            'is_active'             => true,
        ]);

        return redirect()->back()->with('success', 'Package created successfully.');
    }

    public function update(Request $request, $id)
    {
        WbPackage::ensureColumnsExist();
        $package = WbPackage::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price'  => 'required|numeric|min:0',
        ]);

        $landing = \App\Models\WebsiteBuilder\WbLandingSetting::first();
        $defaultServices  = (int)($landing->default_services_limit ?? 100);
        $defaultPortfolio = (int)($landing->default_portfolio_limit ?? 100);
        $defaultBlogs     = (int)($landing->default_blog_limit ?? 300);

        $package->update([
            'name'                  => $request->name,
            'slug'                  => Str::slug($request->name),
            'monthly_price'         => $request->monthly_price,
            'yearly_price'          => $request->yearly_price,
            'theme_limit'           => $request->input('theme_limit', 10),
            'storage_limit_mb'      => $request->storage_limit_mb ?? 5000,
            'portfolio_limit'       => $request->input('portfolio_limit', $defaultPortfolio),
            'services_limit'        => $request->input('services_limit', $defaultServices),
            'blog_limit'           => $request->input('blog_limit', $defaultBlogs),
            'contact_form_allowed'  => $request->has('contact_form_allowed'),
            'map_section_allowed'    => $request->has('map_section_allowed'),
            'custom_domain_allowed' => $request->has('custom_domain_allowed'),
            'call_whatsapp_allowed' => $request->has('call_whatsapp_allowed'),
            'blog_allowed'          => $request->has('blog_allowed'),
            'is_active'             => $request->has('is_active') ? true : $package->is_active,
        ]);

        return redirect()->back()->with('success', 'Package updated successfully.');
    }

    public function destroy($id)
    {
        $package = WbPackage::findOrFail($id);
        $package->delete();

        return redirect()->back()->with('success', 'Package deleted.');
    }
}
