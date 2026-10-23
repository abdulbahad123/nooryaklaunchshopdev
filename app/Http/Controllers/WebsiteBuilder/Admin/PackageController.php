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

        // Auto-sync packages that are null to platform defaults (100 services/portfolio, 300 blogs)
        // Also clamp any packages exceeding the platform ceiling (100 services, 100 portfolio, 300 blogs)
        try {
            WbPackage::whereNull('services_limit')
                ->update(['services_limit' => min(100, $defaultServices)]);

            WbPackage::where('services_limit', '>', 100)
                ->update(['services_limit' => 100]);

            WbPackage::whereNull('portfolio_limit')
                ->update(['portfolio_limit' => min(100, $defaultPortfolio)]);

            WbPackage::where('portfolio_limit', '>', 100)
                ->update(['portfolio_limit' => 100]);

            WbPackage::whereNull('blog_limit')
                ->update(['blog_limit' => min(300, $defaultBlogs)]);

            WbPackage::where('blog_limit', '>', 300)
                ->update(['blog_limit' => 300]);
        } catch (\Throwable $e) {}

        $packages = WbPackage::orderBy('id', 'asc')->get();
        return view('website_builder.admin.packages.index', compact('packages', 'defaultServices', 'defaultPortfolio', 'defaultBlogs'));
    }

    public function store(Request $request)
    {
        WbPackage::ensureColumnsExist();
        $request->validate([
            'name'             => 'required|string|max:255',
            'monthly_price'    => 'required|numeric|min:0',
            'yearly_price'     => 'required|numeric|min:0',
            'services_limit'   => 'nullable|numeric|min:0|max:100',
            'portfolio_limit'  => 'nullable|numeric|min:0|max:100',
            'blog_limit'       => 'nullable|numeric|min:0|max:300',
        ], [
            'services_limit.max'  => 'Limit Warning: Not able to give that limit. Services limit cannot exceed the maximum allowed of 100.',
            'portfolio_limit.max' => 'Limit Warning: Not able to give that limit. Portfolio limit cannot exceed the maximum allowed of 100.',
            'blog_limit.max'      => 'Limit Warning: Not able to give that limit. Blog articles limit cannot exceed the maximum allowed of 300.',
        ]);

        $landing = \App\Models\WebsiteBuilder\WbLandingSetting::first();
        $defaultServices  = (int)($landing->default_services_limit ?? 100);
        $defaultPortfolio = (int)($landing->default_portfolio_limit ?? 100);
        $defaultBlogs     = (int)($landing->default_blog_limit ?? 300);

        $inputPortfolio = ($request->has('portfolio_limit') && $request->input('portfolio_limit') !== null && $request->input('portfolio_limit') !== '')
            ? min(100, max(0, (int)$request->input('portfolio_limit')))
            : $defaultPortfolio;
        $inputServices  = ($request->has('services_limit') && $request->input('services_limit') !== null && $request->input('services_limit') !== '')
            ? min(100, max(0, (int)$request->input('services_limit')))
            : $defaultServices;
        $inputBlogs     = ($request->has('blog_limit') && $request->input('blog_limit') !== null && $request->input('blog_limit') !== '')
            ? min(300, max(0, (int)$request->input('blog_limit')))
            : $defaultBlogs;

        WbPackage::create([
            'name'                  => $request->name,
            'slug'                  => Str::slug($request->name),
            'monthly_price'         => $request->monthly_price,
            'yearly_price'          => $request->yearly_price,
            'max_websites'          => 1,
            'theme_limit'           => (int)$request->input('theme_limit', 10),
            'storage_limit_mb'      => (int)$request->input('storage_limit_mb', 5000),
            'portfolio_limit'       => $inputPortfolio,
            'services_limit'        => $inputServices,
            'blog_limit'           => $inputBlogs,
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
            'name'             => 'required|string|max:255',
            'monthly_price'    => 'required|numeric|min:0',
            'yearly_price'     => 'required|numeric|min:0',
            'services_limit'   => 'nullable|numeric|min:0|max:100',
            'portfolio_limit'  => 'nullable|numeric|min:0|max:100',
            'blog_limit'       => 'nullable|numeric|min:0|max:300',
        ], [
            'services_limit.max'  => 'Limit Warning: Not able to give that limit. Services limit cannot exceed the maximum allowed of 100.',
            'portfolio_limit.max' => 'Limit Warning: Not able to give that limit. Portfolio limit cannot exceed the maximum allowed of 100.',
            'blog_limit.max'      => 'Limit Warning: Not able to give that limit. Blog articles limit cannot exceed the maximum allowed of 300.',
        ]);

        $landing = \App\Models\WebsiteBuilder\WbLandingSetting::first();
        $defaultServices  = (int)($landing->default_services_limit ?? 100);
        $defaultPortfolio = (int)($landing->default_portfolio_limit ?? 100);
        $defaultBlogs     = (int)($landing->default_blog_limit ?? 300);

        $inputPortfolio = ($request->has('portfolio_limit') && $request->input('portfolio_limit') !== null && $request->input('portfolio_limit') !== '')
            ? min(100, max(0, (int)$request->input('portfolio_limit')))
            : ($package->portfolio_limit ?? $defaultPortfolio);
        $inputServices  = ($request->has('services_limit') && $request->input('services_limit') !== null && $request->input('services_limit') !== '')
            ? min(100, max(0, (int)$request->input('services_limit')))
            : ($package->services_limit ?? $defaultServices);
        $inputBlogs     = ($request->has('blog_limit') && $request->input('blog_limit') !== null && $request->input('blog_limit') !== '')
            ? min(300, max(0, (int)$request->input('blog_limit')))
            : ($package->blog_limit ?? $defaultBlogs);

        $package->update([
            'name'                  => $request->name,
            'slug'                  => Str::slug($request->name),
            'monthly_price'         => $request->monthly_price,
            'yearly_price'          => $request->yearly_price,
            'theme_limit'           => $request->filled('theme_limit') ? (int)$request->input('theme_limit') : ($package->theme_limit ?? 10),
            'storage_limit_mb'      => $request->filled('storage_limit_mb') ? (int)$request->input('storage_limit_mb') : ($package->storage_limit_mb ?? 5000),
            'portfolio_limit'       => $inputPortfolio,
            'services_limit'        => $inputServices,
            'blog_limit'           => $inputBlogs,
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
