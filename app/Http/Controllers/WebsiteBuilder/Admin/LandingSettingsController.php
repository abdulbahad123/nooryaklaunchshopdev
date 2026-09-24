<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbLandingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandingSettingsController extends Controller
{
    public function edit()
    {
        $settings = WbLandingSetting::getSettings();
        return view('website_builder.admin.landing_settings', compact('settings'));
    }

    public function update(Request $request)
    {
        WbLandingSetting::ensureColumnsExist();
        $settings = WbLandingSetting::getSettings();

        $validated = $request->validate([
            // Branding & Logos
            'brand_name'            => 'nullable|string|max:255',
            'header_logo_file'      => 'nullable|file|max:5120',
            'footer_logo_file'      => 'nullable|file|max:5120',
            // Hero
            'hero_badge'            => 'required|string|max:255',
            'hero_title'            => 'required|string|max:500',
            'hero_subtitle'         => 'required|string',
            'cta_primary_text'      => 'required|string|max:255',
            'cta_primary_url'       => 'required|string|max:255',
            'cta_secondary_text'    => 'required|string|max:255',
            'cta_secondary_url'     => 'required|string|max:255',
            'primary_color'         => 'required|string|max:20',
            'secondary_color'       => 'required|string|max:20',
            'hero_image_file'       => 'nullable|file|max:5120',
            // Who section
            'who_label'             => 'nullable|string|max:100',
            'who_brand_name'        => 'nullable|string|max:100',
            'who_subtitle'          => 'nullable|string|max:255',
            'who_description'       => 'nullable|string|max:1000',
            'audiences_data'                => 'nullable|array',
            'audiences_data.*.icon'         => 'nullable|string|max:100',
            'audiences_data.*.title'        => 'nullable|string|max:100',
            'audiences_data.*.color'        => 'nullable|string|max:20',
            // Use Cases
            'usecases_label'        => 'nullable|string|max:100',
            'usecases_title'        => 'nullable|string|max:255',
            'usecases_subtitle'     => 'nullable|string|max:500',
            'usecases_data'                 => 'nullable|array',
            'usecases_data.*.label'         => 'nullable|string|max:100',
            'usecases_data.*.icon'          => 'nullable|string|max:100',
            'usecases_data.*.color'         => 'nullable|string|max:20',
            // Process
            'process_label'         => 'nullable|string|max:100',
            'process_heading'       => 'nullable|string|max:255',
            'process_subtitle'      => 'nullable|string|max:500',
            'process_data'                  => 'nullable|array',
            'process_data.*.step'           => 'nullable|string|max:10',
            'process_data.*.title'          => 'nullable|string|max:255',
            'process_data.*.desc'           => 'nullable|string|max:500',
            // Features
            'features_label'        => 'nullable|string|max:100',
            'features_heading'      => 'nullable|string|max:255',
            'features_subtitle'     => 'nullable|string|max:500',
            'features_data'                 => 'nullable|array',
            'features_data.*.icon'          => 'nullable|string|max:100',
            'features_data.*.title'         => 'nullable|string|max:255',
            'features_data.*.desc'          => 'nullable|string|max:500',
            // Templates
            'templates_label'       => 'nullable|string|max:100',
            'templates_heading'     => 'nullable|string|max:255',
            'templates_subtitle'    => 'nullable|string|max:500',
            // Pricing
            'pricing_label'         => 'nullable|string|max:100',
            'pricing_heading'       => 'nullable|string|max:255',
            'pricing_subtitle'      => 'nullable|string|max:500',
            // Testimonials
            'testimonials_label'    => 'nullable|string|max:100',
            'testimonials_heading'  => 'nullable|string|max:255',
            'testimonials_data'             => 'nullable|array',
            'testimonials_data.*.name'      => 'nullable|string|max:255',
            'testimonials_data.*.role'      => 'nullable|string|max:255',
            'testimonials_data.*.rating'    => 'nullable|integer|min:1|max:5',
            'testimonials_data.*.comment'   => 'nullable|string|max:1000',
            // CTA Banner
            'cta_banner_title'      => 'nullable|string|max:255',
            'cta_banner_subtitle'   => 'nullable|string|max:500',
            'cta_banner_trust'      => 'nullable|array',
            'cta_banner_trust.*'    => 'nullable|string|max:100',
            // Contact
            'contact_heading'       => 'nullable|string|max:255',
            'contact_subtitle'      => 'nullable|string|max:500',
            'contact_email'         => 'required|email|max:255',
            'contact_phone'         => 'nullable|string|max:50',
            'contact_address'       => 'nullable|string|max:500',
            // Footer
            'footer_brand_name'     => 'nullable|string|max:100',
            'footer_text'           => 'nullable|string',
            'footer_copyright'      => 'nullable|string|max:255',
            // Other
            'custom_css'            => 'nullable|string',
        ]);

        $allowedExts = ['jpeg', 'jpg', 'png', 'gif', 'svg', 'webp'];

        // Handle Header Logo upload
        if ($request->hasFile('header_logo_file')) {
            $file = $request->file('header_logo_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowedExts)) {
                return redirect()->back()->withErrors(['header_logo_file' => 'Invalid image format. Allowed: JPG, PNG, GIF, SVG, WEBP.']);
            }
            $filename = 'wb_header_logo_' . time() . '.' . $ext;
            $destDir = public_path('assets/website-builder/img');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $file->move($destDir, $filename);
            $validated['header_logo'] = 'assets/website-builder/img/' . $filename;
        }

        // Handle Footer Logo upload
        if ($request->hasFile('footer_logo_file')) {
            $file = $request->file('footer_logo_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowedExts)) {
                return redirect()->back()->withErrors(['footer_logo_file' => 'Invalid image format. Allowed: JPG, PNG, GIF, SVG, WEBP.']);
            }
            $filename = 'wb_footer_logo_' . time() . '.' . $ext;
            $destDir = public_path('assets/website-builder/img');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $file->move($destDir, $filename);
            $validated['footer_logo'] = 'assets/website-builder/img/' . $filename;
        }

        // Handle hero image upload
        if ($request->hasFile('hero_image_file')) {
            $file = $request->file('hero_image_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowedExts)) {
                return redirect()->back()->withErrors(['hero_image_file' => 'Invalid image format. Allowed: JPG, PNG, GIF, SVG, WEBP.']);
            }
            $filename = 'wb_hero_' . time() . '.' . $ext;
            $destDir = public_path('assets/website-builder/img');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $file->move($destDir, $filename);
            $validated['hero_image'] = 'assets/website-builder/img/' . $filename;
        }

        unset($validated['header_logo_file'], $validated['footer_logo_file'], $validated['hero_image_file']);

        // Save features_data as JSON
        if (isset($validated['features_data'])) {
            $validated['features_data'] = array_values(array_filter($validated['features_data'], fn($f) => !empty($f['title'])));
        }

        // Save process_data as JSON
        if (isset($validated['process_data'])) {
            $validated['process_data'] = array_values(array_filter($validated['process_data'], fn($p) => !empty($p['title'])));
        }

        // Save testimonials_data as JSON
        if (isset($validated['testimonials_data'])) {
            $validated['testimonials_data'] = array_values(array_filter($validated['testimonials_data'], fn($t) => !empty($t['name'])));
        }

        // Filter against existing table columns to prevent SQL unknown column errors
        try {
            $existingColumns = \Illuminate\Support\Facades\Schema::getColumnListing('wb_landing_settings');
            $dataToUpdate = array_intersect_key($validated, array_flip($existingColumns));
            $settings->update($dataToUpdate);
        } catch (\Throwable $e) {
            Log::error("LandingSettingsController update exception: " . $e->getMessage());
            $settings->update($validated);
        }

        return redirect()->back()
            ->with('success', 'Landing page settings updated successfully! Visit the public site to see your changes.');
    }
}
