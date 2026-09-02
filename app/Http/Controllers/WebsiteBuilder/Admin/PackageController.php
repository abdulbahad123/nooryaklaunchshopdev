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
        $packages = WbPackage::orderBy('id', 'asc')->get();
        return view('website_builder.admin.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price'  => 'required|numeric|min:0',
            'max_websites'  => 'required|integer|min:1',
            'features_list' => 'nullable|string',
        ]);

        $features = array_filter(array_map('trim', explode("\n", $request->features_list ?? '')));

        WbPackage::create([
            'name'                  => $request->name,
            'slug'                  => Str::slug($request->name),
            'monthly_price'         => $request->monthly_price,
            'yearly_price'          => $request->yearly_price,
            'max_websites'          => $request->max_websites,
            'storage_limit_mb'      => $request->storage_limit_mb ?? 5000,
            'custom_domain_allowed' => $request->has('custom_domain_allowed'),
            'white_label_allowed'   => $request->has('white_label_allowed'),
            'ai_tools_allowed'      => $request->has('ai_tools_allowed'),
            'is_popular'            => $request->has('is_popular'),
            'is_active'             => true,
            'features_list'         => array_values($features),
        ]);

        return redirect()->back()->with('success', 'Package created successfully.');
    }

    public function destroy($id)
    {
        $package = WbPackage::findOrFail($id);
        $package->delete();

        return redirect()->back()->with('success', 'Package deleted.');
    }
}
