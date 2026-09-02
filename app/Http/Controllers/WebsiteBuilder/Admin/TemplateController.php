<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = WbTemplate::orderBy('sort_order', 'asc')->paginate(15);
        $totalCount = WbTemplate::count();
        $activeCount = WbTemplate::where('is_active', true)->count();

        return view('website_builder.admin.templates.index', compact('templates', 'totalCount', 'activeCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'required|string|max:100',
            'description'   => 'nullable|string',
            'demo_url'      => 'nullable|url',
            'price'         => 'nullable|numeric|min:0',
            'preview_image' => 'nullable|string',
        ]);

        WbTemplate::create([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'category'      => $request->category,
            'description'   => $request->description,
            'demo_url'      => $request->demo_url,
            'price'         => $request->price ?? 0,
            'is_free'       => ($request->price == 0),
            'preview_image' => $request->preview_image ?? 'images/template-preview.jpg',
            'is_active'     => true,
            'sort_order'    => WbTemplate::max('sort_order') + 1,
        ]);

        return redirect()->back()->with('success', 'Template added successfully.');
    }

    public function toggleStatus($id)
    {
        $template = WbTemplate::findOrFail($id);
        $template->is_active = !$template->is_active;
        $template->save();

        return redirect()->back()->with('success', 'Template status updated.');
    }

    public function destroy($id)
    {
        $template = WbTemplate::findOrFail($id);
        $template->delete();

        return redirect()->back()->with('success', 'Template removed.');
    }
}
