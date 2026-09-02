<?php

namespace App\Http\Controllers\WebsiteBuilder\User;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbPage;
use App\Models\WebsiteBuilder\WbSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BuilderController extends Controller
{
    public function dashboard()
    {
        $customer = Auth::guard('wb_customer')->user();
        $pages = WbPage::where('customer_id', $customer ? $customer->id : 1)->orderBy('sort_order', 'asc')->get();

        return view('website_builder.user.dashboard', compact('customer', 'pages'));
    }

    public function pages()
    {
        $customer = Auth::guard('wb_customer')->user();
        $pages = WbPage::where('customer_id', $customer ? $customer->id : 1)->orderBy('sort_order', 'asc')->get();

        return view('website_builder.user.pages.index', compact('customer', 'pages'));
    }

    public function storePage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $customer = Auth::guard('wb_customer')->user();

        $page = WbPage::create([
            'customer_id'  => $customer ? $customer->id : 1,
            'title'        => $request->title,
            'slug'         => Str::slug($request->title),
            'seo_title'    => $request->title,
            'is_home'      => false,
            'is_published' => true,
            'sort_order'   => WbPage::count() + 1,
        ]);

        // Default Hero Section
        WbSection::create([
            'page_id'   => $page->id,
            'type'      => 'hero',
            'content'   => [
                'headline' => 'Welcome to ' . $page->title,
                'subtitle' => 'Customized portfolio section built with Website Builder.',
            ],
            'sort_order'=> 1,
            'is_visible'=> true,
        ]);

        return redirect()->route('website-builder.user.pages.editor', $page->id)->with('success', 'Page created successfully.');
    }

    public function editor($id)
    {
        $page = WbPage::with('sections')->findOrFail($id);

        return view('website_builder.user.pages.editor', compact('page'));
    }

    public function updateSection(Request $request, $id)
    {
        $section = WbSection::findOrFail($id);
        $section->update([
            'content' => $request->content,
            'styles'  => $request->styles,
        ]);

        return response()->json(['success' => true, 'message' => 'Section updated']);
    }
}
