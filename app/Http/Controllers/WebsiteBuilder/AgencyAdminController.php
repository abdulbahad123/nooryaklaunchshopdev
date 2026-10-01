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
            // If the customer has an active connected custom domain, prefer that as the live URL
            try {
                if (Schema::hasTable('wb_agency_settings')) {
                    $agencySetting = WbAgencySetting::where('customer_id', $customer->id)
                        ->whereNotNull('custom_domain')
                        ->where('custom_domain', '!=', '')
                        ->where('custom_domain_status', 1)
                        ->first();
                    if ($agencySetting && !empty($agencySetting->custom_domain)) {
                        $cd = normalizeWbHost($agencySetting->custom_domain);
                        if (!empty($cd)) {
                            return 'https://' . $cd;
                        }
                    }
                }
            } catch (\Throwable $e) {}
            return route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]);
        }
        return route('website-builder.templates.digital_agency');
    }

    private function getAgencySetting()
    {
        $customerId = $this->getAuthenticatedCustomerId();
        $demoTemplate = request('template') ?: session('demo_template');

        if ($demoTemplate && in_array($demoTemplate, ['digital_agency', 'interior', 'texigo', 'construction', 'evently'])) {
            session(['demo_template' => $demoTemplate]);
        } else {
            $demoTemplate = session('demo_template', 'digital_agency');
        }

        if (!$customerId || session('wb_demo_admin')) {
            return WbAgencySetting::getDemoDefaults($demoTemplate);
        }

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

    public function footerCtaPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.footer_cta', compact('agency', 'customer', 'liveUrl'));
    }

    public function counterPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.counter', compact('agency', 'customer', 'liveUrl'));
    }

    public function eventsPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.events', compact('agency', 'customer', 'liveUrl'));
    }

    public function testimonialsPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.testimonials', compact('agency', 'customer', 'liveUrl'));
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
        WbAgencySetting::ensureColumnsExist();
        $customerId = $this->getAuthenticatedCustomerId();

        $demoTemplate = $request->input('template_type') ?: (request('template') ?: session('demo_template', 'digital_agency'));
        if ($demoTemplate && in_array($demoTemplate, ['digital_agency', 'interior', 'texigo', 'construction', 'evently'])) {
            session(['demo_template' => $demoTemplate]);
        }

        $setting = null;
        if ($customerId && !session('wb_demo_admin')) {
            $setting = WbAgencySetting::where('customer_id', $customerId)->first();
            if (!$setting) {
                $setting = WbAgencySetting::getDefaults($customerId);
            }
        } else {
            $setting = WbAgencySetting::getDemoDefaults($demoTemplate);
        }

        if (!$setting) {
            $setting = new WbAgencySetting();
        }

        if ($customerId) {
            $setting->customer_id = $customerId;
        }
        $setting->template_type = $demoTemplate;

        $getFileExt = function ($file) {
            try {
                $ext = $file->getClientOriginalExtension();
                if (!empty($ext)) {
                    return $ext;
                }
            } catch (\Throwable $e) {}
            try {
                $ext = $file->guessExtension();
                if (!empty($ext)) {
                    return $ext;
                }
            } catch (\Throwable $e) {}
            return 'png';
        };

        $saveUploadedFile = function ($file, $prefix) use ($getFileExt) {
            if (!$file || !($file instanceof \Illuminate\Http\UploadedFile) || !$file->isValid()) {
                return null;
            }

            $ext = $getFileExt($file);
            $fileName = $prefix . '_' . time() . '_' . rand(1000, 9999) . '.' . $ext;

            $pubDir = public_path('uploads/website_builder');
            if (!file_exists($pubDir)) {
                @mkdir($pubDir, 0777, true);
            }

            $altDir = base_path('public_html/uploads/website_builder');
            if (!file_exists($altDir) && file_exists(base_path('public_html'))) {
                @mkdir($altDir, 0777, true);
            }

            try {
                $file->move($pubDir, $fileName);
                if (file_exists($altDir) && file_exists($pubDir . '/' . $fileName)) {
                    @copy($pubDir . '/' . $fileName, $altDir . '/' . $fileName);
                }
                return 'uploads/website_builder/' . $fileName;
            } catch (\Throwable $e) {
                if (file_exists($altDir)) {
                    try {
                        $file->move($altDir, $fileName);
                        return 'uploads/website_builder/' . $fileName;
                    } catch (\Throwable $ex) {}
                }
            }
            return null;
        };

        // Handle Site Logo File Upload or Text
        if ($request->hasFile('site_logo_file')) {
            $uploadedPath = $saveUploadedFile($request->file('site_logo_file'), 'logo');
            if ($uploadedPath) {
                $setting->site_logo = $uploadedPath;
                $setting->logo_type = 'image';
            } elseif ($request->has('site_logo') && !empty($request->input('site_logo'))) {
                $setting->site_logo = $request->input('site_logo');
            }
        } elseif ($request->has('site_logo') && !empty($request->input('site_logo'))) {
            $setting->site_logo = $request->input('site_logo');
        }

        // Handle Hero Image File Upload
        if ($request->hasFile('hero_image_file')) {
            $uploadedPath = $saveUploadedFile($request->file('hero_image_file'), 'hero');
            if ($uploadedPath) {
                $setting->hero_image = $uploadedPath;
            } elseif ($request->has('hero_image') && !empty($request->input('hero_image'))) {
                $setting->hero_image = $request->input('hero_image');
            }
        } elseif ($request->has('hero_image') && !empty($request->input('hero_image'))) {
            $setting->hero_image = $request->input('hero_image');
        }

        // Handle About Hero Image File Upload
        if ($request->hasFile('about_hero_image_file')) {
            $uploadedPath = $saveUploadedFile($request->file('about_hero_image_file'), 'about_hero');
            if ($uploadedPath) {
                $setting->about_hero_image = $uploadedPath;
            } elseif ($request->has('about_hero_image') && !empty($request->input('about_hero_image'))) {
                $setting->about_hero_image = $request->input('about_hero_image');
            }
        } elseif ($request->has('about_hero_image') && !empty($request->input('about_hero_image'))) {
            $setting->about_hero_image = $request->input('about_hero_image');
        }

        // Handle Contact Page Image File Upload
        if ($request->hasFile('contact_image_file')) {
            $uploadedPath = $saveUploadedFile($request->file('contact_image_file'), 'contact');
            if ($uploadedPath) {
                $setting->contact_image = $uploadedPath;
            } elseif ($request->has('contact_image') && !empty($request->input('contact_image'))) {
                $setting->contact_image = $request->input('contact_image');
            }
        } elseif ($request->has('contact_image') && !empty($request->input('contact_image'))) {
            $setting->contact_image = $request->input('contact_image');
        }

        // Handle CTA Banner Image Upload
        if ($request->hasFile('cta_banner_image_file')) {
            $uploadedPath = $saveUploadedFile($request->file('cta_banner_image_file'), 'cta_bg');
            if ($uploadedPath) {
                $setting->cta_banner_image = $uploadedPath;
            } elseif ($request->has('cta_banner_image') && !empty($request->input('cta_banner_image'))) {
                $setting->cta_banner_image = $request->input('cta_banner_image');
            }
        } elseif ($request->has('cta_banner_image') && !empty($request->input('cta_banner_image'))) {
            $setting->cta_banner_image = $request->input('cta_banner_image');
        }

        if ($request->has('logo_type'))          $setting->logo_type          = $request->input('logo_type');
        if ($request->has('site_title'))         $setting->site_title         = $request->input('site_title');
        if ($request->has('top_announcement'))   $setting->top_announcement   = $request->input('top_announcement');
        if ($request->has('email'))              $setting->email              = $request->input('email');
        if ($request->has('phone'))              $setting->phone              = $request->input('phone');
        if ($request->has('address'))            $setting->address            = $request->input('address');
        if ($request->has('working_hours'))      $setting->working_hours      = $request->input('working_hours');
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
        if ($request->has('about_primary_btn_text'))   $setting->about_primary_btn_text   = $request->input('about_primary_btn_text');
        if ($request->has('about_primary_btn_url'))    $setting->about_primary_btn_url    = $request->input('about_primary_btn_url');
        if ($request->has('about_secondary_btn_text')) $setting->about_secondary_btn_text = $request->input('about_secondary_btn_text');
        if ($request->has('about_secondary_btn_url'))  $setting->about_secondary_btn_url  = $request->input('about_secondary_btn_url');
        if ($request->has('services_badge'))     $setting->services_badge     = $request->input('services_badge');
        if ($request->has('services_title'))     $setting->services_title     = $request->input('services_title');
        if ($request->has('services_subtitle'))  $setting->services_subtitle  = $request->input('services_subtitle');
        if ($request->has('portfolio_badge'))    $setting->portfolio_badge    = $request->input('portfolio_badge');
        if ($request->has('portfolio_title'))    $setting->portfolio_title    = $request->input('portfolio_title');
        if ($request->has('portfolio_subtitle')) $setting->portfolio_subtitle = $request->input('portfolio_subtitle');
        if ($request->has('mission_title'))      $setting->mission_title      = $request->input('mission_title');
        if ($request->has('mission_text'))       $setting->mission_text       = $request->input('mission_text');
        if ($request->has('vision_title'))       $setting->vision_title       = $request->input('vision_title');
        if ($request->has('vision_text'))        $setting->vision_text        = $request->input('vision_text');
        if ($request->has('values_title'))       $setting->values_title       = $request->input('values_title');
        if ($request->has('values_text'))        $setting->values_text        = $request->input('values_text');
        if ($request->has('helpline_title'))     $setting->helpline_title     = $request->input('helpline_title');
        if ($request->has('helpline_desc'))      $setting->helpline_desc      = $request->input('helpline_desc');
        if ($request->has('helpline_btn_text'))  $setting->helpline_btn_text  = $request->input('helpline_btn_text');
        if ($request->has('helpline_btn_url'))   $setting->helpline_btn_url   = $request->input('helpline_btn_url');
        if ($request->has('cta_banner_badge'))   $setting->cta_banner_badge   = $request->input('cta_banner_badge');
        if ($request->has('cta_banner_title'))   $setting->cta_banner_title   = $request->input('cta_banner_title');
        if ($request->has('cta_banner_subtitle'))$setting->cta_banner_subtitle= $request->input('cta_banner_subtitle');
        if ($request->has('cta_banner_btn_text'))$setting->cta_banner_btn_text= $request->input('cta_banner_btn_text');
        if ($request->has('cta_banner_btn_url')) $setting->cta_banner_btn_url = $request->input('cta_banner_btn_url');
        if ($request->has('team_badge'))         $setting->team_badge         = $request->input('team_badge');
        if ($request->has('team_title'))         $setting->team_title         = $request->input('team_title');
        if ($request->has('team_subtitle'))      $setting->team_subtitle      = $request->input('team_subtitle');
        if ($request->has('testimonials_badge')) $setting->testimonials_badge = $request->input('testimonials_badge');
        if ($request->has('testimonials_title')) $setting->testimonials_title = $request->input('testimonials_title');
        if ($request->has('testimonials_subtitle'))$setting->testimonials_subtitle = $request->input('testimonials_subtitle');
        if ($request->has('footer_text'))        $setting->footer_text        = $request->input('footer_text');

        if ($request->has('stats_data')) {
            $setting->stats_data = array_values($request->input('stats_data', []));
        }
        if ($request->has('trust_bar_data')) {
            $setting->trust_bar_data = array_values($request->input('trust_bar_data', []));
        }
        if ($request->has('impact_features_data')) {
            $setting->impact_features_data = array_values($request->input('impact_features_data', []));
        }
        if ($request->has('contact_bullets_data')) {
            $setting->contact_bullets_data = array_values($request->input('contact_bullets_data', []));
        }
        if ($request->has('header_nav_links')) {
            $rawLinks = $request->input('header_nav_links', []);
            $formatted = [];
            if (isset($rawLinks[0]) && is_array($rawLinks[0]) && !isset($rawLinks[0]['title'])) {
                $map = $rawLinks[0];
                if (!empty($map['home']))      $formatted[] = ['title' => $map['home'], 'url' => 'home'];
                if (!empty($map['about']))     $formatted[] = ['title' => $map['about'], 'url' => 'about'];
                if (!empty($map['services']))  $formatted[] = ['title' => $map['services'], 'url' => 'services'];
                if (!empty($map['portfolio'])) $formatted[] = ['title' => $map['portfolio'], 'url' => 'portfolio'];
                if (!empty($map['contact']))   $formatted[] = ['title' => $map['contact'], 'url' => 'contact'];
            } else {
                foreach ($rawLinks as $rl) {
                    if (is_array($rl) && isset($rl['title'])) {
                        $formatted[] = $rl;
                    }
                }
            }
            if (empty($formatted)) {
                $formatted = [
                    ['title' => 'Home', 'url' => 'home'],
                    ['title' => 'About Us', 'url' => 'about'],
                    ['title' => 'Portfolio', 'url' => 'portfolio'],
                    ['title' => 'Contact Us', 'url' => 'contact'],
                ];
            }
            $setting->header_nav_links = $formatted;
        }
        if ($request->has('events_data')) {
            $rawEventsData = $request->input('events_data', []);
            $files = $request->file('events_data');
            if (!empty($files) && is_array($files)) {
                foreach ($files as $ei => $fileData) {
                    if (isset($fileData['image_file']) && $fileData['image_file'] instanceof \Illuminate\Http\UploadedFile && isset($rawEventsData[$ei])) {
                        $up = $saveUploadedFile($fileData['image_file'], 'evt_' . $ei);
                        if ($up) {
                            $rawEventsData[$ei]['image'] = $up;
                        }
                    }
                }
            }
            $setting->events_data = array_values($rawEventsData);
        }
        if ($request->has('fare_calculator_data')) {
            $calcData = $request->input('fare_calculator_data', []);
            if (isset($calcData['vehicles']) && is_array($calcData['vehicles'])) {
                $calcData['vehicles'] = array_values($calcData['vehicles']);
            }
            $setting->fare_calculator_data = $calcData;
        }
        if ($request->has('services_data')) {
            $rawServicesData = $request->input('services_data', []);
            $files = $request->file('services_data');
            if (!empty($files) && is_array($files)) {
                foreach ($files as $si => $fileData) {
                    if (isset($fileData['image_file']) && $fileData['image_file'] instanceof \Illuminate\Http\UploadedFile && isset($rawServicesData[$si])) {
                        $up = $saveUploadedFile($fileData['image_file'], 'srv_' . $si);
                        if ($up) {
                            $rawServicesData[$si]['image'] = $up;
                        }
                    }
                }
            }
            $setting->services_data = array_values($rawServicesData);
        }
        if ($request->has('portfolio_data')) {
            $rawPortfolioData = $request->input('portfolio_data', []);
            $files = $request->file('portfolio_data');
            if (!empty($files) && is_array($files)) {
                foreach ($files as $pi => $fileData) {
                    if (isset($fileData['image_file']) && $fileData['image_file'] instanceof \Illuminate\Http\UploadedFile && isset($rawPortfolioData[$pi])) {
                        $up = $saveUploadedFile($fileData['image_file'], 'port_' . $pi);
                        if ($up) {
                            $rawPortfolioData[$pi]['image'] = $up;
                        }
                    }
                }
            }
            $setting->portfolio_data = array_values($rawPortfolioData);
        }
        if ($request->has('testimonials_data')) {
            $rawTestimonialsData = $request->input('testimonials_data', []);
            $files = $request->file('testimonials_data');
            if (!empty($files) && is_array($files)) {
                foreach ($files as $tmi => $fileData) {
                    if (isset($fileData['avatar_file']) && $fileData['avatar_file'] instanceof \Illuminate\Http\UploadedFile && isset($rawTestimonialsData[$tmi])) {
                        $up = $saveUploadedFile($fileData['avatar_file'], 'tst_' . $tmi);
                        if ($up) {
                            $rawTestimonialsData[$tmi]['avatar'] = $up;
                        }
                    }
                }
            }
            $setting->testimonials_data = array_values($rawTestimonialsData);
        }
        if ($request->has('team_members_data')) {
            $rawTeamData = $request->input('team_members_data', []);
            $files = $request->file('team_members_data');
            if (!empty($files) && is_array($files)) {
                foreach ($files as $ti => $fileData) {
                    if (isset($fileData['image_file']) && $fileData['image_file'] instanceof \Illuminate\Http\UploadedFile && isset($rawTeamData[$ti])) {
                        $up = $saveUploadedFile($fileData['image_file'], 'team_' . $ti);
                        if ($up) {
                            $rawTeamData[$ti]['image'] = $up;
                        }
                    }
                }
            }
            $setting->team_members_data = array_values($rawTeamData);
        }
        if ($request->has('faqs_data')) {
            $setting->faqs_data = array_values($request->input('faqs_data', []));
        }
        if ($request->has('social_links')) {
            $setting->social_links = $request->input('social_links', []);
        }
        if ($request->has('footer_quick_links') && \Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'footer_quick_links')) {
            $setting->footer_quick_links = array_values($request->input('footer_quick_links', []));
        }
        if ($request->has('footer_legal_links') && \Illuminate\Support\Facades\Schema::hasColumn('wb_agency_settings', 'footer_legal_links')) {
            $setting->footer_legal_links = array_values($request->input('footer_legal_links', []));
        }

        WbAgencySetting::ensureColumnsExist();

        try {
            $setting->save();
        } catch (\Illuminate\Database\QueryException $e) {
            WbAgencySetting::ensureColumnsExist();
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                $existingCols = \Illuminate\Support\Facades\Schema::getColumnListing('wb_agency_settings');
                $attrs = $setting->getAttributes();
                foreach ($attrs as $col => $val) {
                    if (!in_array($col, $existingCols, true)) {
                        unset($setting->$col);
                    }
                }
            }
            $setting->save();
        }

        return redirect()->back()->with('success', 'Template content, services, projects, and images updated successfully!');
    }

    public function customDomainPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        // Dynamically resolve the CNAME target from environment or request host
        $cnameTarget = normalizeWbHost(request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? ''));
        if (in_array($cnameTarget, ['localhost', '127.0.0.1', ''], true)) {
            // Fallback: parse from APP_URL or WEBSITE_HOST env
            $cnameTarget = env('WEBSITE_HOST', '');
            if (empty($cnameTarget)) {
                $parsed = parse_url(env('APP_URL', ''));
                $cnameTarget = $parsed['host'] ?? '';
            }
            $cnameTarget = normalizeWbHost($cnameTarget);
        }
        return view('website_builder.agency_template.admin.pages.custom_domain', compact('agency', 'customer', 'liveUrl', 'cnameTarget'));
    }

    public function submitCustomDomainRequest(Request $request)
    {
        $request->validate([
            'custom_domain' => 'required|string|max:255',
        ]);

        $domain = trim($request->input('custom_domain'));
        $domain = normalizeWbHost($domain);

        if (empty($domain) || !str_contains($domain, '.')) {
            return redirect()->back()->with('error', 'Please enter a valid custom domain format (e.g. domain.com or www.domain.com).');
        }

        $agency = $this->getAgencySetting();

        // Clear custom_domain from other agencies if it was previously rejected to prevent collision
        if (!empty($domain)) {
            WbAgencySetting::where(function($q) use ($domain) {
                $q->where('custom_domain', $domain)
                  ->orWhere('custom_domain', 'www.' . $domain);
            })
            ->where('id', '!=', $agency->id)
            ->where('custom_domain_status', 2)
            ->update(['custom_domain' => null, 'custom_domain_status' => 0]);
        }

        $agency->custom_domain = $domain;
        $agency->custom_domain_status = 0; // 0 = Pending
        $agency->save();

        // Also record in LaunchShop's user_custom_domains table if available
        try {
            $customer = $this->getAuthenticatedCustomer();
            if (Schema::hasTable('user_custom_domains') && $customer) {
                \App\Models\User\UserCustomDomain::updateOrCreate(
                    ['user_id' => $customer->id],
                    [
                        'requested_domain' => $domain,
                        'status'           => 0,
                    ]
                );
            }
        } catch (\Throwable $e) {}

        return redirect()->back()->with('success', "Custom domain request for {$domain} submitted successfully! Please add the CNAME DNS record as instructed below.");
    }

    public function portfolioPage()
    {
        $agency = $this->getAgencySetting();
        $customer = $this->getAuthenticatedCustomer();
        $liveUrl = $this->getLiveUrl($customer);
        return view('website_builder.agency_template.admin.pages.portfolio', compact('agency', 'customer', 'liveUrl'));
    }

    public function updatePortfolio(Request $request)
    {
        return $this->update($request);
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

        $files = $request->file('blogs_data');
        if (!empty($files) && is_array($files)) {
            foreach ($files as $bi => $fileData) {
                if (isset($fileData['image_file']) && $fileData['image_file'] instanceof \Illuminate\Http\UploadedFile && $fileData['image_file']->isValid()) {
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


