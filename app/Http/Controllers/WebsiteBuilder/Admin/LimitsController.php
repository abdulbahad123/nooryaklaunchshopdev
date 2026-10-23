<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbCustomer;
use App\Models\WebsiteBuilder\WbLandingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LimitsController extends Controller
{
    public function index(Request $request)
    {
        WbCustomer::ensureColumnsExist();
        self::ensureDefaultLimitColumns();

        $landing = WbLandingSetting::first();
        $defaultServices  = (int)($landing->default_services_limit ?? 100);
        $defaultPortfolio = (int)($landing->default_portfolio_limit ?? 100);
        $defaultBlogs     = (int)($landing->default_blog_limit ?? 300);

        // Fetch all white-label clients using Website Builder
        $whitelabels = WbCustomer::orderBy('name', 'asc')->get();

        $selectedWhitelabel = null;
        if ($request->has('whitelabel_id')) {
            $selectedWhitelabel = WbCustomer::find($request->whitelabel_id);
        }

        return view('website_builder.admin.limits.index', compact(
            'whitelabels',
            'defaultServices',
            'defaultPortfolio',
            'defaultBlogs',
            'selectedWhitelabel'
        ));
    }

    public function updateDefaults(Request $request)
    {
        $request->validate([
            'default_services_limit'  => 'required|integer|min:1|max:100',
            'default_portfolio_limit' => 'required|integer|min:1|max:100',
            'default_blog_limit'      => 'required|integer|min:1|max:300',
        ], [
            'default_services_limit.max'  => 'Limit Warning: Not able to give that limit. Default services limit cannot exceed 100.',
            'default_portfolio_limit.max' => 'Limit Warning: Not able to give that limit. Default portfolio limit cannot exceed 100.',
            'default_blog_limit.max'      => 'Limit Warning: Not able to give that limit. Default blog articles limit cannot exceed 300.',
        ]);

        self::ensureDefaultLimitColumns();

        $landing = WbLandingSetting::first() ?? new WbLandingSetting();
        $landing->default_services_limit  = min(100, max(1, (int)$request->input('default_services_limit', 100)));
        $landing->default_portfolio_limit = min(100, max(1, (int)$request->input('default_portfolio_limit', 100)));
        $landing->default_blog_limit      = min(300, max(1, (int)$request->input('default_blog_limit', 300)));
        $landing->save();

        // Also update packages in database that have default / initial limit (<= 10 or null)
        try {
            \App\Models\WebsiteBuilder\WbPackage::whereNull('services_limit')
                ->orWhere('services_limit', '<=', 10)
                ->update(['services_limit' => $landing->default_services_limit]);

            \App\Models\WebsiteBuilder\WbPackage::where('services_limit', '>', 100)
                ->update(['services_limit' => 100]);

            \App\Models\WebsiteBuilder\WbPackage::whereNull('portfolio_limit')
                ->orWhere('portfolio_limit', '<=', 10)
                ->update(['portfolio_limit' => $landing->default_portfolio_limit]);

            \App\Models\WebsiteBuilder\WbPackage::where('portfolio_limit', '>', 100)
                ->update(['portfolio_limit' => 100]);

            \App\Models\WebsiteBuilder\WbPackage::whereNull('blog_limit')
                ->orWhere('blog_limit', '<=', 10)
                ->update(['blog_limit' => $landing->default_blog_limit]);

            \App\Models\WebsiteBuilder\WbPackage::where('blog_limit', '>', 300)
                ->update(['blog_limit' => 300]);
        } catch (\Throwable $e) {}

        return redirect()->route('website-builder.admin.limits.index')
            ->with('success', 'Global default limits updated successfully! All white-labels and packages will default to ' . $landing->default_services_limit . ' services, ' . $landing->default_portfolio_limit . ' portfolio items, and ' . $landing->default_blog_limit . ' blogs.');
    }

    public function updateWhitelabel(Request $request)
    {
        $request->validate([
            'whitelabel_id'         => 'required|exists:wb_customers,id',
            'extra_services_limit'  => 'required|integer|min:0|max:100',
            'extra_portfolio_limit' => 'required|integer|min:0|max:100',
            'extra_blog_limit'      => 'required|integer|min:0|max:300',
        ], [
            'extra_services_limit.max'  => 'Limit Warning: Not able to give that limit. Extra services limit cannot exceed 100.',
            'extra_portfolio_limit.max' => 'Limit Warning: Not able to give that limit. Extra portfolio limit cannot exceed 100.',
            'extra_blog_limit.max'      => 'Limit Warning: Not able to give that limit. Extra blog articles limit cannot exceed 300.',
        ]);

        WbCustomer::ensureColumnsExist();

        $customer = WbCustomer::findOrFail($request->input('whitelabel_id'));
        $customer->extra_services_limit  = min(100, max(0, (int)$request->input('extra_services_limit', 0)));
        $customer->extra_portfolio_limit = min(100, max(0, (int)$request->input('extra_portfolio_limit', 0)));
        $customer->extra_blog_limit      = min(300, max(0, (int)$request->input('extra_blog_limit', 0)));
        $customer->save();

        $name = $customer->company_name ?: ($customer->name ?: 'White-label');

        return redirect()->route('website-builder.admin.limits.index', ['whitelabel_id' => $customer->id])
            ->with('success', 'Custom limits updated successfully for white-label "' . $name . '"!');
    }

    public static function ensureDefaultLimitColumns(): void
    {
        try {
            if (Schema::hasTable('wb_landing_settings')) {
                Schema::table('wb_landing_settings', function ($table) {
                    if (!Schema::hasColumn('wb_landing_settings', 'default_services_limit')) {
                        $table->integer('default_services_limit')->default(100);
                    }
                    if (!Schema::hasColumn('wb_landing_settings', 'default_portfolio_limit')) {
                        $table->integer('default_portfolio_limit')->default(100);
                    }
                    if (!Schema::hasColumn('wb_landing_settings', 'default_blog_limit')) {
                        $table->integer('default_blog_limit')->default(300);
                    }
                });
            }
        } catch (\Throwable $e) {}
    }
}
