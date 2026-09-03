<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbLandingSetting;
use Illuminate\Http\Request;

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
            'hero_badge'        => 'required|string|max:255',
            'hero_title'        => 'required|string|max:255',
            'hero_subtitle'     => 'required|string',
            'cta_primary_text'  => 'required|string|max:255',
            'cta_primary_url'   => 'required|string|max:255',
            'cta_secondary_text'=> 'required|string|max:255',
            'cta_secondary_url' => 'required|string|max:255',
            'primary_color'     => 'required|string|max:20',
            'secondary_color'   => 'required|string|max:20',
            'contact_email'     => 'required|email|max:255',
            'contact_phone'     => 'nullable|string|max:50',
            'contact_address'   => 'nullable|string|max:500',
            'footer_text'       => 'nullable|string',
            'custom_css'        => 'nullable|string',
            'hero_image'        => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
        ]);

        if ($request->hasFile('hero_image')) {
            $file = $request->file('hero_image');
            $filename = 'hero_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/front/img/website_builder/'), $filename);
            $validated['hero_image'] = 'assets/front/img/website_builder/' . $filename;
        }

        if ($request->filled('features_json')) {
            $features = json_decode($request->features_json, true);
            if (is_array($features)) {
                $validated['features_data'] = $features;
            }
        }

        if ($request->filled('process_json')) {
            $process = json_decode($request->process_json, true);
            if (is_array($process)) {
                $validated['process_data'] = $process;
            }
        }

        $settings->update($validated);

        return redirect()->back()->with('success', 'Website Builder landing page dynamic data and image uploaded successfully!');
    }
}
