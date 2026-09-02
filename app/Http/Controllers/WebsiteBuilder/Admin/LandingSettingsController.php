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
        ]);

        $settings->update($validated);

        return redirect()->back()->with('success', 'Website Builder landing page dynamic data and brand colors updated successfully!');
    }
}
