<?php

namespace App\Http\Controllers\WebsiteBuilder;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbAgencySetting;
use App\Models\WebsiteBuilder\WbAgencyInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AgencyAdminController extends Controller
{
    public function dashboard()
    {
        $agency = WbAgencySetting::getDefaults();
        $inquiriesCount = 0;
        $recentInquiries = [];

        try {
            if (Schema::hasTable('wb_agency_inquiries')) {
                $inquiriesCount = WbAgencyInquiry::count();
                $recentInquiries = WbAgencyInquiry::latest()->take(5)->get();
            }
        } catch (\Throwable $e) {
            // handle fallback
        }

        return view('website_builder.agency_template.admin.dashboard', compact('agency', 'inquiriesCount', 'recentInquiries'));
    }

    public function homePage()
    {
        $agency = WbAgencySetting::getDefaults();
        return view('website_builder.agency_template.admin.pages.home', compact('agency'));
    }

    public function aboutPage()
    {
        $agency = WbAgencySetting::getDefaults();
        return view('website_builder.agency_template.admin.pages.about', compact('agency'));
    }

    public function contactPage()
    {
        $agency = WbAgencySetting::getDefaults();
        return view('website_builder.agency_template.admin.pages.contact', compact('agency'));
    }

    public function footerPage()
    {
        $agency = WbAgencySetting::getDefaults();
        return view('website_builder.agency_template.admin.pages.footer', compact('agency'));
    }

    public function inquiriesPage()
    {
        $inquiries = [];
        try {
            if (Schema::hasTable('wb_agency_inquiries')) {
                $inquiries = WbAgencyInquiry::latest()->get();
            }
        } catch (\Throwable $e) {
            // handle fallback
        }

        return view('website_builder.agency_template.admin.inquiries', compact('inquiries'));
    }

    public function deleteInquiry($id)
    {
        try {
            if (Schema::hasTable('wb_agency_inquiries')) {
                WbAgencyInquiry::where('id', $id)->delete();
            }
        } catch (\Throwable $e) {
            // handle fallback
        }

        return redirect()->back()->with('success', 'Contact message deleted successfully.');
    }

    public function logout(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Auth::guard('wb_customer')->logout();
            \Illuminate\Support\Facades\Auth::guard('wb_admin')->logout();
        } catch (\Throwable $e) {}

        session()->forget(['wb_customer_id', 'wb_customer_email', 'is_secret_logged_in']);

        return redirect()->route('website-builder.login')
            ->with('success', 'You have been logged out successfully from your dashboard.');
    }

    public function update(Request $request)
    {
        $setting = WbAgencySetting::first();
        if (!$setting) {
            $setting = new WbAgencySetting();
        }

        // Handle Site Logo File Upload or Text (Task 5 Match)
        if ($request->hasFile('site_logo_file')) {
            $file = $request->file('site_logo_file');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/website_builder'), $fileName);
            $setting->site_logo = 'uploads/website_builder/' . $fileName;
        } elseif ($request->has('site_logo') && !empty($request->input('site_logo'))) {
            $setting->site_logo = $request->input('site_logo');
        }

        if ($request->has('site_title'))         $setting->site_title         = $request->input('site_title');
        if ($request->has('top_announcement'))   $setting->top_announcement   = $request->input('top_announcement');
        if ($request->has('email'))              $setting->email              = $request->input('email');
        if ($request->has('phone'))              $setting->phone              = $request->input('phone');
        if ($request->has('address'))            $setting->address            = $request->input('address');
        if ($request->has('hero_badge'))         $setting->hero_badge         = $request->input('hero_badge');
        if ($request->has('hero_title'))         $setting->hero_title         = $request->input('hero_title');
        if ($request->has('hero_subtitle'))      $setting->hero_subtitle      = $request->input('hero_subtitle');
        if ($request->has('hero_image'))         $setting->hero_image         = $request->input('hero_image');
        if ($request->has('primary_btn_text'))   $setting->primary_btn_text   = $request->input('primary_btn_text');
        if ($request->has('primary_btn_url'))    $setting->primary_btn_url    = $request->input('primary_btn_url');
        if ($request->has('secondary_btn_text')) $setting->secondary_btn_text = $request->input('secondary_btn_text');
        if ($request->has('secondary_btn_url'))  $setting->secondary_btn_url  = $request->input('secondary_btn_url');
        if ($request->has('about_hero_title'))   $setting->about_hero_title   = $request->input('about_hero_title');
        if ($request->has('about_hero_subtitle'))$setting->about_hero_subtitle= $request->input('about_hero_subtitle');
        if ($request->has('story_title'))        $setting->story_title        = $request->input('story_title');
        if ($request->has('story_text'))         $setting->story_text         = $request->input('story_text');
        if ($request->has('contact_title'))      $setting->contact_title      = $request->input('contact_title');
        if ($request->has('contact_subtitle'))   $setting->contact_subtitle   = $request->input('contact_subtitle');
        if ($request->has('footer_text'))        $setting->footer_text        = $request->input('footer_text');

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

        return redirect()->back()->with('success', 'Template content and logo updated successfully!');
    }
}
