<?php

namespace App\Http\Controllers\WebsiteBuilder;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbAgencySetting;
use App\Models\WebsiteBuilder\WbAgencyInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AgencyAdminController extends Controller
{
    private function getAuthenticatedCustomerId()
    {
        $id = \Illuminate\Support\Facades\Auth::guard('wb_customer')->id() ?? session('wb_customer_id');

        if (!$id && Schema::hasTable('wb_customers')) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $id = \Illuminate\Support\Facades\Auth::id();
            } elseif (session()->has('wb_customer_email')) {
                $c = \App\Models\WebsiteBuilder\WbCustomer::where('email', session('wb_customer_email'))->first();
                if ($c) {
                    $id = $c->id;
                    session(['wb_customer_id' => $id]);
                }
            }
        }

        if (!$id && Schema::hasTable('wb_customers')) {
            $lastCustomer = \App\Models\WebsiteBuilder\WbCustomer::latest()->first();
            if ($lastCustomer) {
                $id = $lastCustomer->id;
            }
        }

        return $id;
    }

    private function getAuthenticatedCustomer()
    {
        $customerId = $this->getAuthenticatedCustomerId();
        if ($customerId && Schema::hasTable('wb_customers')) {
            return \App\Models\WebsiteBuilder\WbCustomer::find($customerId);
        }
        return null;
    }

    private function getLiveUrl($customer = null)
    {
        if (!$customer) {
            $customer = $this->getAuthenticatedCustomer();
        }
        if ($customer && !empty($customer->subdomain)) {
            return route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]);
        }
        return route('website-builder.templates.digital_agency');
    }

    private function getAgencySetting()
    {
        $customerId = $this->getAuthenticatedCustomerId();
        return WbAgencySetting::getDefaults($customerId);
    }

    public function dashboard()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
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

        return view('website_builder.agency_template.admin.dashboard', compact('agency', 'customer', 'liveUrl', 'inquiriesCount', 'recentInquiries'));
    }

    public function homePage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.home', compact('agency', 'customer', 'liveUrl'));
    }

    public function aboutPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.about', compact('agency', 'customer', 'liveUrl'));
    }

    public function contactPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.contact', compact('agency', 'customer', 'liveUrl'));
    }

    public function footerPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.footer', compact('agency', 'customer', 'liveUrl'));
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
        $customerId = $this->getAuthenticatedCustomerId();

        $setting = null;
        if ($customerId) {
            $setting = WbAgencySetting::where('customer_id', $customerId)->first();
            if (!$setting) {
                $setting = WbAgencySetting::getDefaults($customerId);
            }
        } else {
            $setting = WbAgencySetting::whereNull('customer_id')->first() ?? WbAgencySetting::first();
        }

        if (!$setting) {
            $setting = new WbAgencySetting();
        }

        if ($customerId) {
            $setting->customer_id = $customerId;
        }


        // Handle Site Logo File Upload or Text
        if ($request->hasFile('site_logo_file')) {
            $file = $request->file('site_logo_file');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/website_builder'), $fileName);
            $setting->site_logo = 'uploads/website_builder/' . $fileName;
        } elseif ($request->has('site_logo') && !empty($request->input('site_logo'))) {
            $setting->site_logo = $request->input('site_logo');
        }

        // Handle Hero Image File Upload
        if ($request->hasFile('hero_image_file')) {
            $file = $request->file('hero_image_file');
            $fileName = 'hero_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/website_builder'), $fileName);
            $setting->hero_image = 'uploads/website_builder/' . $fileName;
        } elseif ($request->has('hero_image') && !empty($request->input('hero_image'))) {
            $setting->hero_image = $request->input('hero_image');
        }

        if ($request->has('site_title'))         $setting->site_title         = $request->input('site_title');
        if ($request->has('top_announcement'))   $setting->top_announcement   = $request->input('top_announcement');
        if ($request->has('email'))              $setting->email              = $request->input('email');
        if ($request->has('phone'))              $setting->phone              = $request->input('phone');
        if ($request->has('address'))            $setting->address            = $request->input('address');
        if ($request->has('hero_badge'))         $setting->hero_badge         = $request->input('hero_badge');
        if ($request->has('hero_title'))         $setting->hero_title         = $request->input('hero_title');
        if ($request->has('hero_subtitle'))      $setting->hero_subtitle      = $request->input('hero_subtitle');
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
            $portfolioData = array_values($request->input('portfolio_data', []));
            if ($request->hasFile('portfolio_data')) {
                $files = $request->file('portfolio_data');
                foreach ($files as $pi => $fileData) {
                    if (isset($fileData['image_file']) && $fileData['image_file']->isValid()) {
                        $f = $fileData['image_file'];
                        $fileName = 'port_' . $pi . '_' . time() . '_' . rand(100, 999) . '.' . $f->getClientOriginalExtension();
                        $f->move(public_path('uploads/website_builder'), $fileName);
                        $portfolioData[$pi]['image'] = 'uploads/website_builder/' . $fileName;
                    }
                }
            }
            $setting->portfolio_data = $portfolioData;
        }
        if ($request->has('testimonials_data')) {
            $setting->testimonials_data = array_values($request->input('testimonials_data', []));
        }
        if ($request->has('team_members_data')) {
            $teamData = array_values($request->input('team_members_data', []));
            if ($request->hasFile('team_members_data')) {
                $files = $request->file('team_members_data');
                foreach ($files as $ti => $fileData) {
                    if (isset($fileData['image_file']) && $fileData['image_file']->isValid()) {
                        $f = $fileData['image_file'];
                        $fileName = 'team_' . $ti . '_' . time() . '_' . rand(100, 999) . '.' . $f->getClientOriginalExtension();
                        $f->move(public_path('uploads/website_builder'), $fileName);
                        $teamData[$ti]['image'] = 'uploads/website_builder/' . $fileName;
                    }
                }
            }
            $setting->team_members_data = $teamData;
        }
        if ($request->has('faqs_data')) {
            $setting->faqs_data = array_values($request->input('faqs_data', []));
        }

        $setting->save();

        return redirect()->back()->with('success', 'Template content, services, projects, and images updated successfully!');
    }

    public function customDomainPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.custom_domain', compact('agency', 'customer', 'liveUrl'));
    }

    public function submitCustomDomainRequest(Request $request)
    {
        $request->validate([
            'custom_domain' => 'required|string|max:255',
        ]);

        $domain = trim($request->input('custom_domain'));
        $domain = preg_replace('#^https?://#', '', $domain);
        $domain = rtrim($domain, '/');

        if (empty($domain)) {
            return redirect()->back()->with('error', 'Please enter a valid custom domain format (e.g. domain.com or www.domain.com).');
        }

        $agency = $this->getAgencySetting();
        $agency->custom_domain = $domain;
        $agency->custom_domain_status = 0; // 0 = Pending
        $agency->save();

        // Also record in LaunchShop's user_custom_domains table if available
        try {
            $customer = $this->getAuthenticatedCustomer();
            if (Schema::hasTable('user_custom_domains') && $customer) {
                \App\Models\User\UserCustomDomain::create([
                    'user_id'          => $customer->id,
                    'requested_domain' => $domain,
                    'status'           => 0,
                ]);
            }
        } catch (\Throwable $e) {}

        return redirect()->back()->with('success', "Custom domain request for {$domain} submitted successfully! Please add the CNAME DNS record as instructed below.");
    }

    public function blogsPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.blogs', compact('agency', 'customer', 'liveUrl'));
    }

    public function updateBlogs(Request $request)
    {
        $agency = $this->getAgencySetting();
        $blogsData = array_values($request->input('blogs_data', []));

        if ($request->hasFile('blogs_data')) {
            $files = $request->file('blogs_data');
            foreach ($files as $bi => $fileData) {
                if (isset($fileData['image_file']) && $fileData['image_file']->isValid()) {
                    $f = $fileData['image_file'];
                    $fileName = 'blog_' . $bi . '_' . time() . '_' . rand(100, 999) . '.' . $f->getClientOriginalExtension();
                    $f->move(public_path('uploads/website_builder'), $fileName);
                    $blogsData[$bi]['image'] = 'uploads/website_builder/' . $fileName;
                }
            }
        }

        $agency->blogs_data = $blogsData;
        $agency->save();

        return redirect()->back()->with('success', 'All articles and blogs updated successfully!');
    }
}


