<?php

namespace App\Http\Controllers\WebsiteBuilder;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbAgencySetting;
use Illuminate\Http\Request;

class AgencyAdminController extends Controller
{
    public function index()
    {
        $agency = WbAgencySetting::getDefaults();

        return view('website_builder.agency_template.admin.dashboard', compact('agency'));
    }

    public function update(Request $request)
    {
        $setting = WbAgencySetting::first();
        if (!$setting) {
            $setting = new WbAgencySetting();
        }

        $setting->site_title         = $request->input('site_title', 'DesignAGENCY');
        $setting->top_announcement   = $request->input('top_announcement');
        $setting->email              = $request->input('email');
        $setting->phone              = $request->input('phone');
        $setting->address            = $request->input('address');
        $setting->hero_badge         = $request->input('hero_badge');
        $setting->hero_title         = $request->input('hero_title');
        $setting->hero_subtitle      = $request->input('hero_subtitle');
        $setting->hero_image         = $request->input('hero_image');
        $setting->primary_btn_text   = $request->input('primary_btn_text');
        $setting->primary_btn_url    = $request->input('primary_btn_url');
        $setting->secondary_btn_text = $request->input('secondary_btn_text');
        $setting->secondary_btn_url  = $request->input('secondary_btn_url');
        $setting->about_hero_title   = $request->input('about_hero_title');
        $setting->about_hero_subtitle= $request->input('about_hero_subtitle');
        $setting->story_title        = $request->input('story_title');
        $setting->story_text         = $request->input('story_text');
        $setting->contact_title      = $request->input('contact_title');
        $setting->contact_subtitle   = $request->input('contact_subtitle');
        $setting->footer_text        = $request->input('footer_text');

        if ($request->has('stats_data')) {
            $setting->stats_data = array_values($request->input('stats_data', []));
        }
        if ($request->has('services_data')) {
            $setting->services_data = array_values($request->input('services_data', []));
        }
        if ($request->has('portfolio_data')) {
            $setting->portfolio_data = array_values($request->input('portfolio_data', []));
        }
        if ($request->has('testimonials_data')) {
            $setting->testimonials_data = array_values($request->input('testimonials_data', []));
        }
        if ($request->has('team_members_data')) {
            $setting->team_members_data = array_values($request->input('team_members_data', []));
        }
        if ($request->has('faqs_data')) {
            $setting->faqs_data = array_values($request->input('faqs_data', []));
        }

        $setting->save();

        return redirect()->back()->with('success', 'DesignAGENCY Template settings updated successfully!');
    }
}
