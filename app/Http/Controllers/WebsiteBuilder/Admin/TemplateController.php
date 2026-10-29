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
                $officialTemplates = [
                    [
                        'slug'          => 'digital_agency',
                        'name'          => 'Digital Agency',
                        'category'      => 'Agency / Portfolio',
                        'description'   => 'Creative digital solutions agency multipage template with dynamic hero, services, portfolio, team, and contact form.',
                        'preview_image' => 'assets/website_builder/Templates/Digital_agency/hero_banner.png',
                        'demo_url'      => route('website-builder.templates.digital_agency'),
                        'price'         => 0.00,
                        'is_free'       => true,
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
                        'price'         => 0.00,
                        'is_free'       => true,
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
                        'price'         => 0.00,
                        'is_free'       => true,
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
                        'price'         => 0.00,
                        'is_free'       => true,
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
                        'price'         => 0.00,
                        'is_free'       => true,
                        'is_featured'   => true,
                        'is_active'     => true,
                        'sort_order'    => 5,
                    ],
                ];

                $officialSlugs = ['digital_agency', 'interior', 'texigo', 'construction', 'evently'];

                // Automatically purge obsolete dummy/starter templates across all agency tenant databases
                WbTemplate::whereNotIn('slug', $officialSlugs)->delete();

                foreach ($officialTemplates as $tmpl) {
                    $record = WbTemplate::firstOrCreate(['slug' => $tmpl['slug']], $tmpl);
                    if ($record && ($record->price > 0 || !$record->is_free)) {
                        $record->update(['price' => 0.00, 'is_free' => true]);
                    }
                }
                WbTemplate::whereIn('slug', $officialSlugs)->update(['price' => 0.00, 'is_free' => true]);
            }
        } catch (\Throwable $e) {
        }

        $officialSlugs = ['digital_agency', 'interior', 'texigo', 'construction', 'evently'];
        $templates = WbTemplate::whereIn('slug', $officialSlugs)->orderBy('sort_order', 'asc')->get();
        $totalCount = $templates->count();
        $activeCount = $templates->where('is_active', true)->count();

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

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (WbTemplate::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        WbTemplate::create([
            'name'          => $request->name,
            'slug'          => $slug,
            'category'      => $request->category,
            'description'   => $request->description,
            'demo_url'      => $request->demo_url,
            'price'         => $request->price ?? 0,
            'is_free'       => ($request->price == 0),
            'preview_image' => $request->preview_image ?? 'images/template-preview.jpg',
            'is_active'     => true,
            'sort_order'    => (WbTemplate::max('sort_order') ?? 0) + 1,
        ]);

        return redirect()->back()->with('success', 'Template added successfully.');
    }

    public function update(Request $request, $id)
    {
        $template = WbTemplate::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'required|string|max:100',
            'description'   => 'nullable|string',
            'demo_url'      => 'nullable|url',
            'price'         => 'nullable|numeric|min:0',
            'preview_image' => 'nullable|string',
            'is_active'     => 'nullable',
        ]);

        $template->update([
            'name'          => $request->name,
            'category'      => $request->category,
            'description'   => $request->description,
            'demo_url'      => $request->demo_url,
            'price'         => $request->price ?? 0,
            'is_free'       => ($request->price == 0),
            'preview_image' => $request->preview_image ?? $template->preview_image,
            'is_active'     => $request->has('is_active') ? (bool)$request->is_active : $template->is_active,
        ]);

        return redirect()->back()->with('success', 'Template updated successfully.');
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
