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
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_templates')) {
                WbTemplate::whereNotIn('slug', ['digital_agency', 'interior', 'texigo', 'construction', 'evently'])->delete();
                
                $officialTemplates = [
                    [
                        'slug'          => 'digital_agency',
                        'name'          => 'Digital Agency',
                        'category'      => 'Agency / Portfolio',
                        'description'   => 'Creative digital solutions agency multipage template with dynamic hero, services, portfolio, team, and contact form.',
                        'preview_image' => 'assets/website_builder/Templates/Digital_agency/hero_banner.png',
                        'demo_url'      => route('website-builder.templates.digital_agency'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => true,
                        'is_active'     => true,
                        'sort_order'    => 1,
                    ],
                    [
                        'slug'          => 'interior',
                        'name'          => 'InteriorCRAFT',
                        'category'      => 'Architecture & Design',
                        'description'   => 'Luxury architecture & interior design template with serif typography, bespoke spatial gallery, project portfolio, and consultation booking.',
                        'preview_image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=800&auto=format&fit=crop',
                        'demo_url'      => route('website-builder.templates.interior'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => true,
                        'is_active'     => true,
                        'sort_order'    => 2,
                    ],
                    [
                        'slug'          => 'texigo',
                        'name'          => 'TaxiGo Mobility',
                        'category'      => 'Transport & Mobility',
                        'description'   => 'Taxi & cab booking mobility template with dynamic hero, fleet vehicles, trip services, customer testimonials, and quick booking.',
                        'preview_image' => 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png',
                        'demo_url'      => route('website-builder.templates.texigo'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => false,
                        'is_active'     => true,
                        'sort_order'    => 3,
                    ],
                    [
                        'slug'          => 'construction',
                        'name'          => 'BuildCraft Construction',
                        'category'      => 'Construction & Engineering',
                        'description'   => 'Premium construction company template with dynamic hero, services, project portfolio, team, client testimonials, and contact form.',
                        'preview_image' => 'assets/website_builder/Templates/Construction_agency/construction_herobanner.png',
                        'demo_url'      => route('website-builder.templates.construction'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => false,
                        'is_active'     => true,
                        'sort_order'    => 4,
                    ],
                    [
                        'slug'          => 'evently',
                        'name'          => 'Evently',
                        'category'      => 'Events & Celebrations',
                        'description'   => 'Luxury event management & celebration template with vibrant hero, countdown, speaker highlights, and consultation booking.',
                        'preview_image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop',
                        'demo_url'      => route('website-builder.templates.evently'),
                        'price'         => 499.00,
                        'is_free'       => false,
                        'is_featured'   => true,
                        'is_active'     => true,
                        'sort_order'    => 5,
                    ],
                ];

                foreach ($officialTemplates as $tmpl) {
                    WbTemplate::updateOrCreate(['slug' => $tmpl['slug']], $tmpl);
                }
            }
        } catch (\Throwable $e) {
        }

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
