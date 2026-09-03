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
        $settings = WbLandingSetting::getSettings();

        $validated = $request->validate([
            'hero_badge'            => 'required|string|max:255',
            'hero_title'            => 'required|string|max:500',
            'hero_subtitle'         => 'required|string',
            'cta_primary_text'      => 'required|string|max:255',
            'cta_primary_url'       => 'required|string|max:255',
            'cta_secondary_text'    => 'required|string|max:255',
            'cta_secondary_url'     => 'required|string|max:255',
            'primary_color'         => 'required|string|max:20',
            'secondary_color'       => 'required|string|max:20',
            'contact_email'         => 'required|email|max:255',
            'contact_phone'         => 'nullable|string|max:50',
            'contact_address'       => 'nullable|string|max:500',
            'footer_text'           => 'nullable|string',
            'custom_css'            => 'nullable|string',
            'hero_image_file'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'features_data'         => 'nullable|array',
            'features_data.*.icon'  => 'nullable|string|max:100',
            'features_data.*.title' => 'nullable|string|max:255',
            'features_data.*.desc'  => 'nullable|string|max:500',
            'process_data'          => 'nullable|array',
            'process_data.*.step'   => 'nullable|string|max:10',
            'process_data.*.title'  => 'nullable|string|max:255',
            'process_data.*.desc'   => 'nullable|string|max:500',
            'testimonials_data'             => 'nullable|array',
            'testimonials_data.*.name'      => 'nullable|string|max:255',
            'testimonials_data.*.role'      => 'nullable|string|max:255',
            'testimonials_data.*.rating'    => 'nullable|integer|min:1|max:5',
            'testimonials_data.*.comment'   => 'nullable|string|max:1000',
        ]);

        // Handle hero image upload
        if ($request->hasFile('hero_image_file')) {
            $file = $request->file('hero_image_file');
            $filename = 'wb_hero_' . time() . '.' . $file->getClientOriginalExtension();
            $destDir = public_path('assets/website-builder/img');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $file->move($destDir, $filename);
            $validated['hero_image'] = 'assets/website-builder/img/' . $filename;
        }

        unset($validated['hero_image_file']);

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

        $settings->update($validated);

        return redirect()->back()
            ->with('success', 'Landing page settings updated successfully! Visit the public site to see your changes.');
    }
}
