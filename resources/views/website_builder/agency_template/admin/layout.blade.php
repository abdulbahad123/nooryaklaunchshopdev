<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'DesignAGENCY Template Admin')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --sidebar-bg: #0B0F19;
      --sidebar-card: #151C2C;
      --sidebar-text: #94A3B8;
      --sidebar-text-active: #FFFFFF;
      --primary-accent: #4F46E5;
      --body-bg: #F8FAFC;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--body-bg);
      color: #0F172A;
    }

    /* SLEEK BLACK SIDEBAR MATCHING REF IMAGE 2 */
    .agency-admin-sidebar {
      width: 270px;
      min-height: 100vh;
      background: var(--sidebar-bg);
      color: var(--sidebar-text);
      position: fixed;
      top: 0; left: 0; bottom: 0;
      z-index: 1000;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 24px 16px;
      overflow-y: auto;
    }

    .agency-brand-title {
      font-size: 20px;
      font-weight: 800;
      color: #ffffff;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 12px 24px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      margin-bottom: 20px;
    }
    .agency-brand-title span.accent { color: #10B981; }

    /* SIDEBAR NAVIGATION LINKS */
    .sidebar-nav-link {
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #94A3B8;
      padding: 12px 16px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14.5px;
      margin-bottom: 6px;
      transition: all 0.2s;
    }
    .sidebar-nav-link:hover,
    .sidebar-nav-link.active {
      color: #ffffff;
      background: var(--primary-accent);
      box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
    }
    .sidebar-nav-link i.link-icon {
      font-size: 17px;
      width: 24px;
    }
    .sidebar-nav-link.wb-nav-locked,
    .sidebar-sub-link.wb-nav-locked,
    .sidebar-bottom-link.wb-nav-locked {
      pointer-events: none !important;
      opacity: 0.42 !important;
      cursor: not-allowed !important;
      filter: grayscale(0.8) !important;
    }
    .sidebar-nav-link.wb-nav-locked:hover,
    .sidebar-sub-link.wb-nav-locked:hover,
    .sidebar-bottom-link.wb-nav-locked:hover {
      background: transparent !important;
      color: #94A3B8 !important;
      box-shadow: none !important;
    }

    /* COLLAPSIBLE SUB MENU MATCHING REF IMAGE 2 */
    .sidebar-sub-menu {
      padding-left: 20px;
      margin-top: 4px;
      margin-bottom: 8px;
    }
    .sidebar-sub-link {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #94A3B8;
      padding: 9px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      font-size: 13.5px;
      margin-bottom: 4px;
      transition: all 0.2s;
    }
    .sidebar-sub-link:hover,
    .sidebar-sub-link.active {
      color: #ffffff;
      background: rgba(255,255,255,0.08);
    }
    .sidebar-sub-link::before {
      content: '•';
      color: #4F46E5;
      font-size: 16px;
      font-weight: bold;
    }

    .agency-admin-main {
      margin-left: 270px;
      padding: 32px 40px;
    }

    .card-editor {
      border: 1px solid #E2E8F0;
      border-radius: 18px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.03);
      background: #ffffff;
    }

    /* BOTTOM LIVE WEBSITE LINK */
    .sidebar-bottom-link {
      background: rgba(16, 185, 129, 0.12);
      color: #34D399 !important;
      border: 1px solid rgba(16, 185, 129, 0.25);
      border-radius: 12px;
      padding: 12px 16px;
      text-decoration: none;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: all 0.2s;
      margin-top: 20px;
    }
    .sidebar-bottom-link:hover {
      background: #10B981;
      color: #ffffff !important;
    }

    @media (max-width: 991px) {
      .agency-admin-sidebar { width: 100%; position: relative; min-height: auto; }
      .agency-admin-main { margin-left: 0; padding: 20px; }
    }
  </style>
</head>
<body>

<!-- SLEEK BLACK SIDEBAR (Ref Image 2 Match) -->
<aside class="agency-admin-sidebar">
  <div>
    <a href="{{ route('website-builder.agency-admin.index') }}" class="agency-brand-title">
      @php
        $logoType = $agency->logo_type ?? 'image';
        $siteTitle = $agency->site_title ?? 'DesignAGENCY';
        $hasLogoImg = isset($agency) && !empty($agency->site_logo);
      @endphp
      @if($logoType === 'image' && $hasLogoImg)
        <img src="{{ str_starts_with($agency->site_logo, 'http') ? $agency->site_logo : asset(ltrim($agency->site_logo, '/')) }}" alt="Dashboard Logo" style="max-height: 40px; max-width: 170px; object-fit: contain;">
      @else
        <div class="d-flex align-items-center gap-2">
          <div class="p-2 rounded-3 text-white" style="background: #10B981;"><i class="fa-solid fa-paintbrush"></i></div>
          <span class="fw-bold text-white fs-5">{{ $siteTitle }}</span>
        </div>
      @endif
    </a>

    @php
      $activeDemoTmpl = session('demo_template', $agency->template_type ?? 'digital_agency');
    @endphp

    @if(Auth::guard('wb_customer')->check() || session('is_secret_logged_in'))
      <!-- REAL / SUPER ADMIN SINGLE TEMPLATE BADGE -->
      @php
        $tmplNameMap = [
          'digital_agency' => 'Digital Agency',
          'interior'       => 'InteriorCRAFT',
          'texigo'         => 'TaxiGo Mobility',
          'construction'   => 'BuildCraft Construction',
          'evently'        => 'Evently',
        ];
        $currTmplSlug = $agency->template_type ?? session('demo_template', 'digital_agency');
        $currTmplName = $tmplNameMap[$currTmplSlug] ?? 'Active Template';
      @endphp
      <div class="mb-3 p-2 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);">
        <div class="text-white-50 small mb-1 px-1 fw-bold" style="font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase;">
          <i class="fa-solid fa-shield-halved text-success me-1"></i> Active Template:
        </div>
        <div class="badge bg-success text-white p-2 w-100 text-start d-flex align-items-center justify-content-between" style="font-size: 12px; font-weight: 700;">
          <span><i class="fa-solid fa-circle-check me-1"></i> {{ $currTmplName }}</span>
        </div>
      </div>
    @elseif(session('wb_demo_admin'))
      <!-- DEMO THEME SWITCHER -->
      <div class="mb-3 p-2 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);">
        <div class="text-white-50 small mb-2 px-1 fw-bold" style="font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase;">
          <i class="fa-solid fa-layer-group text-warning me-1"></i> Demo Admin Access:
        </div>
        <div class="d-flex flex-column gap-1">
          <a href="{{ route('website-builder.demo-admin', ['template' => 'digital_agency']) }}" 
             class="badge text-decoration-none p-2 text-start d-flex align-items-center justify-content-between {{ $activeDemoTmpl === 'digital_agency' ? 'bg-primary text-white' : 'bg-dark text-white-50' }}" style="font-size: 11.5px; font-weight: 600;">
            <span><i class="fa-solid fa-laptop-code me-1"></i> Digital Agency</span>
            @if($activeDemoTmpl === 'digital_agency')<i class="fa-solid fa-check text-white"></i>@endif
          </a>
          <a href="{{ route('website-builder.demo-admin', ['template' => 'interior']) }}" 
             class="badge text-decoration-none p-2 text-start d-flex align-items-center justify-content-between {{ $activeDemoTmpl === 'interior' ? 'bg-primary text-white' : 'bg-dark text-white-50' }}" style="font-size: 11.5px; font-weight: 600;">
            <span><i class="fa-solid fa-couch me-1"></i> InteriorCRAFT</span>
            @if($activeDemoTmpl === 'interior')<i class="fa-solid fa-check text-white"></i>@endif
          </a>
          <a href="{{ route('website-builder.demo-admin', ['template' => 'texigo']) }}" 
             class="badge text-decoration-none p-2 text-start d-flex align-items-center justify-content-between {{ $activeDemoTmpl === 'texigo' ? 'bg-warning text-dark fw-bold' : 'bg-dark text-white-50' }}" style="font-size: 11.5px; font-weight: 600;">
            <span><i class="fa-solid fa-taxi me-1"></i> TaxiGo Mobility</span>
            @if($activeDemoTmpl === 'texigo')<i class="fa-solid fa-check text-dark"></i>@endif
          </a>
          <a href="{{ route('website-builder.demo-admin', ['template' => 'construction']) }}" 
             class="badge text-decoration-none p-2 text-start d-flex align-items-center justify-content-between {{ $activeDemoTmpl === 'construction' ? 'bg-danger text-white fw-bold' : 'bg-dark text-white-50' }}" style="font-size: 11.5px; font-weight: 600;">
            <span><i class="fa-solid fa-helmet-safety me-1"></i> BuildCraft Construction</span>
            @if($activeDemoTmpl === 'construction')<i class="fa-solid fa-check text-white"></i>@endif
          </a>
          <a href="{{ route('website-builder.demo-admin', ['template' => 'evently']) }}" 
             class="badge text-decoration-none p-2 text-start d-flex align-items-center justify-content-between {{ $activeDemoTmpl === 'evently' ? 'text-white fw-bold' : 'bg-dark text-white-50' }}" style="font-size: 11.5px; font-weight: 600; {{ $activeDemoTmpl === 'evently' ? 'background: #6C3CE1 !important;' : '' }}">
            <span><i class="fa-solid fa-gem me-1"></i> Evently</span>
            @if($activeDemoTmpl === 'evently')<i class="fa-solid fa-check text-white"></i>@endif
          </a>
        </div>
      </div>
    @endif    @php
      $activeDemoTmpl = session('demo_template', 'digital_agency');
      $isFiveTheme = session('wb_demo_admin') ? true : false;

      $wbDaysLeft = null;
      $wbIsExpiringSoon = false;
      $wbIsExpired = false;
      $wbIsLocked = false;
      $wbExpiryNotice = "";
      if (!$isFiveTheme && isset($customer) && !empty($customer->expire_date) && !session('wb_demo_admin')) {
          try {
              $expDate = \Carbon\Carbon::parse($customer->expire_date);
              $now = \Carbon\Carbon::now();
              $diffSeconds = $now->diffInSeconds($expDate, false);

              // After expired 2 minutes (-120 seconds), automatically lock all nav links
              if ($diffSeconds < -120) {
                  $wbIsExpired = true;
                  $wbIsLocked = true;
                  $wbExpiryNotice = "Your website package expired on " . $expDate->format('M d, Y h:i A') . ". All navigation links are locked.";
              } elseif ($diffSeconds <= 0) {
                  $wbIsExpired = true;
                  $wbIsLocked = false;
                  $wbExpiryNotice = "Your website package has expired on " . $expDate->format('M d, Y h:i A');
              } else {
                  $wbWarnDays = 7;
                  if (class_exists('\App\Models\WebsiteBuilder\WbLandingSetting')) {
                      $lSetting = \App\Models\WebsiteBuilder\WbLandingSetting::first();
                      if ($lSetting && !empty($lSetting->recurring_warning_first_days)) {
                          $wbWarnDays = (int)$lSetting->recurring_warning_first_days;
                      }
                  }
                  if ($diffSeconds <= ($wbWarnDays * 86400)) {
                      $wbIsExpiringSoon = true;
                      if ($diffSeconds < 3600) {
                          $mins = max(1, (int)ceil($diffSeconds / 60));
                          $wbExpiryNotice = "Your website package will expire in {$mins} minute(s) on " . $expDate->format('M d, Y h:i A');
                      } elseif ($diffSeconds < 86400) {
                          $hrs = (int)ceil($diffSeconds / 3600);
                          $wbExpiryNotice = "Your website package will expire in {$hrs} hour(s) on " . $expDate->format('M d, Y h:i A');
                      } else {
                          $days = (int)ceil($diffSeconds / 86400);
                          $wbExpiryNotice = "Your website package will expire in {$days} day(s) on " . $expDate->format('M d, Y');
                      }
                  }
              }
          } catch (\Throwable $e) {}
      }
    @endphp

    <!-- DASHBOARD LINK -->
    <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.index') }}" class="sidebar-nav-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.index') ? 'active' : '' }}">
      <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-chart-pie link-icon"></i>
        <span>Dashboard</span>
      </div>
      @if($wbIsLocked)
        <span class="badge bg-danger ms-auto" style="font-size:9.5px; padding:2px 6px;"><i class="fa-solid fa-lock"></i> Locked</span>
      @endif
    </a>

    <!-- PAGES DROPDOWN SUB NAV MENU (Ref Image 2 Match) -->
    <div class="mb-2">
      <a class="sidebar-nav-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.home') || request()->routeIs('website-builder.agency-admin.about') || request()->routeIs('website-builder.agency-admin.contact') || request()->routeIs('website-builder.agency-admin.footer') || request()->routeIs('website-builder.agency-admin.counter') ? 'active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#pagesSubMenu" 
         role="button" 
         aria-expanded="{{ $wbIsLocked ? 'false' : 'true' }}">
        <div class="d-flex align-items-center gap-2">
          <i class="fa-solid fa-file-lines link-icon"></i>
          <span>Pages</span>
        </div>
        @if($wbIsLocked)
          <span class="badge bg-danger ms-auto me-1" style="font-size:9.5px; padding:2px 6px;"><i class="fa-solid fa-lock"></i> Locked</span>
        @endif
        <i class="fa-solid fa-chevron-down small"></i>
      </a>

      <div class="collapse {{ $wbIsLocked ? '' : 'show' }} sidebar-sub-menu" id="pagesSubMenu">
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.home') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.home') ? 'active' : '' }}">
          Home Page
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.about') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.about') ? 'active' : '' }}">
          About Us
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.services') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.services') ? 'active' : '' }}">
          Services Page
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        @php
          $isCustomDomainAllowed = isset($agency) && method_exists($agency, 'isFeatureEnabled') ? $agency->isFeatureEnabled('custom_domain', $customer ?? null) : true;
          $isContactFormAllowed   = isset($agency) && method_exists($agency, 'isFeatureEnabled') ? $agency->isFeatureEnabled('contact_form', $customer ?? null) : true;
          $isBlogAllowed          = isset($agency) && method_exists($agency, 'isFeatureEnabled') ? $agency->isFeatureEnabled('blog', $customer ?? null) : true;
        @endphp

        @if($isContactFormAllowed)
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.contact') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.contact') ? 'active' : '' }}">
          Contact Page
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.form-settings') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.form-settings') ? 'active' : '' }}">
          Form Config (SMTP & reCAPTCHA)
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        @endif
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.counter') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.counter') ? 'active' : '' }}">
          Counter Section
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.footer') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.footer') ? 'active' : '' }}">
          Header & Footer
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.whatsapp') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.whatsapp') ? 'active' : '' }}">
          WhatsApp Chatbot Widget
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.footer-cta') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.footer-cta') ? 'active' : '' }}">
          Footer CTA Banner
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.events') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.events') ? 'active' : '' }}">
          Upcoming Events
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.testimonials') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.testimonials') ? 'active' : '' }}">
          Testimonials
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.portfolio') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.portfolio') ? 'active' : '' }}">
          Portfolio Page
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        @if($isBlogAllowed)
        <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.blogs') }}" class="sidebar-sub-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.blogs') ? 'active' : '' }}">
          Articles & Blogs
          @if($wbIsLocked)<i class="fa-solid fa-lock text-danger ms-auto" style="font-size:10px;"></i>@endif
        </a>
        @endif
      </div>
    </div>

    <!-- CUSTOM DOMAIN LINK -->
    <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.custom-domain') }}" class="sidebar-nav-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.custom-domain') ? 'active' : '' }}">
      <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-globe link-icon"></i>
        <span>Custom Domain</span>
      </div>
      @if($wbIsLocked)
        <span class="badge bg-danger" style="font-size:10px; padding:3px 6px;"><i class="fa-solid fa-lock me-1"></i> Locked</span>
      @elseif(!$isCustomDomainAllowed)
        <span class="badge bg-danger" style="font-size:10px; padding:3px 6px;"><i class="fa-solid fa-lock me-1"></i> Disabled</span>
      @endif
    </a>

    <!-- CONTACT SUBMISSIONS / INQUIRIES LINK -->
    @if($isContactFormAllowed)
    <!-- SUBSCRIPTION & PLANS LINK (ALWAYS ENABLED FOR RENEWAL) -->
    <a href="{{ route('website-builder.agency-admin.subscription') }}" class="sidebar-nav-link {{ request()->routeIs('website-builder.agency-admin.subscription') ? 'active' : '' }}" style="{{ $wbIsLocked ? 'background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(99, 102, 241, 0.25)); border: 1.5px solid #ef4444; box-shadow: 0 0 14px rgba(239, 68, 68, 0.4);' : 'background: rgba(99, 102, 241, 0.12); border-left: 3px solid #6366f1;' }} margin: 6px 0;">
      <div class="d-flex align-items-center gap-2">
        <i class="fas fa-crown text-warning"></i>
        <span class="font-weight-bold" style="color: {{ $wbIsLocked ? '#ef4444' : '#4f46e5' }};">{{ __('Subscription & Plans') }}</span>
      </div>
      @if($wbIsLocked)
        <span class="badge bg-danger text-white fw-bold ms-auto" style="font-size:10px; padding:3px 7px;"><i class="fa-solid fa-bolt me-1"></i> Renew Now</span>
      @endif
    </a>
    <a href="{{ $wbIsLocked ? 'javascript:void(0)' : route('website-builder.agency-admin.inquiries') }}" class="sidebar-nav-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }} {{ request()->routeIs('website-builder.agency-admin.inquiries') ? 'active' : '' }}">
      <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-envelope-open-text link-icon"></i>
        <span>Contact Submissions</span>
      </div>
      @if($wbIsLocked)
        <span class="badge bg-danger ms-auto" style="font-size:9.5px; padding:2px 6px;"><i class="fa-solid fa-lock"></i> Locked</span>
      @endif
    </a>
    @endif


    <!-- LOGOUT BUTTON ON DASHBOARD (ALWAYS ENABLED) -->
    <a href="{{ route('website-builder.agency-admin.logout') }}" class="sidebar-nav-link text-danger mt-3" style="border: 1px solid rgba(239,68,68,0.25); background: rgba(239,68,68,0.1);">
      <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-right-from-bracket link-icon text-danger"></i>
        <span class="text-danger fw-bold">Logout</span>
      </div>
    </a>
  </div>

  <!-- RENAME TO LIVE WEBSITE & PLACE AT BOTTOM (User Task 2 Match) -->
  <div>
    @php
      $demoTmplRouteMap = [
        'digital_agency' => route('website-builder.templates.digital_agency'),
        'interior'       => route('website-builder.templates.interior'),
        'texigo'         => route('website-builder.templates.texigo'),
        'construction'   => route('website-builder.templates.construction'),
        'evently'        => route('website-builder.templates.evently'),
      ];
      if (session('wb_demo_admin')) {
          $customerLiveUrl = $demoTmplRouteMap[$activeDemoTmpl] ?? route('website-builder.templates.digital_agency');
      } else {
          $customerLiveUrl = isset($liveUrl) && $liveUrl ? $liveUrl : (isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency'));
      }
    @endphp
    <a href="{{ $wbIsLocked ? 'javascript:void(0)' : $customerLiveUrl }}" {{ $wbIsLocked ? '' : 'target="_blank"' }} class="sidebar-bottom-link {{ $wbIsLocked ? 'wb-nav-locked' : '' }}">
      <span><i class="fa-solid fa-globe me-2"></i> Live Website</span>
      @if($wbIsLocked)
        <span class="badge bg-danger ms-auto" style="font-size:9.5px; padding:2px 6px;"><i class="fa-solid fa-lock"></i> Locked</span>
      @else
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
      @endif
    </a>
  </div>
</aside>

<!-- MAIN CONTENT -->
<main class="agency-admin-main">
  @php
      $pendingUpiPayment = null;
      try {
          $targetCust = $customer ?? null;
          if (!$targetCust) {
              $wbCustId = \Illuminate\Support\Facades\Auth::guard('wb_customer')->id() ?? session('wb_customer_id');
              if ($wbCustId && class_exists('\App\Models\WebsiteBuilder\WbCustomer')) {
                  $targetCust = \App\Models\WebsiteBuilder\WbCustomer::find($wbCustId);
              }
          }

          if ($targetCust) {
              $purch = $targetCust->latestPurchase;
              if ($purch && (stripos($purch->status ?? '', 'pending') !== false || ($purch->status ?? '') === 'Pending Verification' || ($purch->payment_status ?? '') === 'pending')) {
                  $pendingUpiPayment = (object)[
                      'utr_number' => $purch->transaction_id ?? $purch->payment_id ?? $purch->utr ?? 'Pending'
                  ];
              }
          }

          if (!$pendingUpiPayment && class_exists('\App\Models\WebsiteBuilder\WbTemplatePurchase')) {
              $custEmail = isset($targetCust) && !empty($targetCust->email) ? strtolower(trim($targetCust->email)) : strtolower(trim(session('wb_customer_email') ?? ''));
              if ($custEmail) {
                  $purch = \App\Models\WebsiteBuilder\WbTemplatePurchase::whereRaw('LOWER(TRIM(customer_email)) = ?', [$custEmail])
                      ->where(function($q) {
                          $q->where('status', 'like', '%pending%')
                            ->orWhere('status', 'Pending Verification');
                      })
                      ->latest()
                      ->first();
                  if ($purch) {
                      $pendingUpiPayment = (object)[
                          'utr_number' => $purch->transaction_id ?? $purch->payment_id ?? 'Pending'
                      ];
                  }
              }
          }
      } catch (\Throwable $e) {}

      $wbDaysLeft = null;
      $wbIsExpiringSoon = false;
      $wbIsExpired = false;
      $wbExpiryNotice = "";
      if (isset($customer) && !empty($customer->expire_date)) {
          try {
              $expDate = \Carbon\Carbon::parse($customer->expire_date);
              $now = \Carbon\Carbon::now();
              $diffSeconds = $now->diffInSeconds($expDate, false);

              // Use configured first warning days (default 7)
              $wbWarnDays = 7;
              try {
                  if (class_exists('\App\Models\WebsiteBuilder\WbLandingSetting')) {
                      $lSetting = \App\Models\WebsiteBuilder\WbLandingSetting::first();
                      if ($lSetting && !empty($lSetting->recurring_warning_first_days)) {
                          $wbWarnDays = (int)$lSetting->recurring_warning_first_days;
                      }
                  }
              } catch (\Throwable $te) {}

              if ($diffSeconds <= 0) {
                  $wbIsExpired = true;
                  if ($diffSeconds < -120) {
                      $wbIsLocked = true;
                  }
                  $wbExpiryNotice = "Your website package has expired on " . $expDate->format('M d, Y h:i A');
              } elseif ($diffSeconds <= ($wbWarnDays * 86400)) {
                  $wbIsExpiringSoon = true;
                  if ($diffSeconds < 3600) {
                      $mins = max(1, (int)ceil($diffSeconds / 60));
                      $wbExpiryNotice = "Your website package will expire in {$mins} minute(s) on " . $expDate->format('M d, Y h:i A');
                  } elseif ($diffSeconds < 86400) {
                      $hrs = (int)ceil($diffSeconds / 3600);
                      $wbExpiryNotice = "Your website package will expire in {$hrs} hour(s) on " . $expDate->format('M d, Y h:i A');
                  } else {
                      $days = (int)ceil($diffSeconds / 86400);
                      $wbExpiryNotice = "Your website package will expire in {$days} day(s) on " . $expDate->format('M d, Y');
                  }
              }
          } catch (\Throwable $e) {}
      }
  @endphp

  @if($pendingUpiPayment && !empty($pendingUpiPayment->utr_number))
    <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm" style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; color: #92400e;">
      <div class="d-flex align-items-center gap-3">
        <div style="font-size: 22px; color: #d97706;"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div>
          <div class="fw-bold" style="font-size: 14.5px;">Notice: Your UPI payment (UTR: <code style="background:#fef3c7; color:#92400e; padding:2px 7px; border-radius:4px; font-weight:700;">{{ $pendingUpiPayment->utr_number }}</code>) is pending for admin verification.</div>
          <div class="small" style="color: #b45309;">Your website builder features remain fully active while our admin team reviews your transaction reference.</div>
        </div>
      </div>
      <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px;">Pending Verification</span>
    </div>
  @endif

  @if($wbIsExpired || $wbIsExpiringSoon)
    <div id="wb-expire-header-banner" style="{{ $wbIsLocked ? 'display:flex !important;' : 'display:none;' }} background: {{ $wbIsExpired ? '#fef2f2' : '#fffbeb' }}; border: {{ $wbIsLocked ? '2px solid #ef4444' : '1.5px solid ' . ($wbIsExpired ? '#fecaca' : '#fde68a') }}; border-radius: 12px; color: {{ $wbIsExpired ? '#991b1b' : '#92400e' }};" class="alert {{ $wbIsExpired ? 'alert-danger' : 'alert-warning' }} d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm">
      <div class="d-flex align-items-center gap-3">
        <div style="font-size: 24px; color: {{ $wbIsExpired ? '#dc2626' : '#d97706' }};">
          <i class="fa-solid {{ $wbIsLocked ? 'fa-lock' : 'fa-triangle-exclamation' }}"></i>
        </div>
        <div>
          <div class="fw-bold" style="font-size: 14.5px;">{{ $wbIsLocked ? '🔒 Account Locked: ' : ($wbIsExpired ? 'Urgent: ' : 'Warning: ') }}{{ $wbExpiryNotice }}.</div>
          <div class="small" style="color: {{ $wbIsExpired ? '#b91c1c' : '#b45309' }};">
            {{ $wbIsLocked ? 'All navigation links are locked. Please renew your plan now to restore full access.' : ($wbIsExpired ? 'Please renew your plan now to restore your live website and avoid service interruption.' : 'Please renew your package now to ensure uninterrupted live website service.') }}
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('website-builder.agency-admin.subscription') }}" class="btn btn-sm {{ $wbIsExpired ? 'btn-danger' : 'btn-warning text-dark' }} px-3 py-2 fw-bold rounded-pill text-white" style="font-size: 12.5px; text-decoration: none; white-space: nowrap;">
          <i class="fas fa-sync-alt me-1"></i> {{ $wbIsLocked ? 'Renew Plan Now' : 'Renew Plan' }}
        </a>
        @if(!$wbIsLocked)
          <button type="button" class="btn btn-sm btn-link text-muted p-1 text-decoration-none" onclick="dismissWbExpireBanner()" title="Close Notification" style="font-size: 22px; line-height: 1; border: none; background: transparent; cursor: pointer;">
            &times;
          </button>
        @endif
      </div>
    </div>
    <script>
      (function() {
        @if(!$wbIsLocked)
          if (!sessionStorage.getItem('wb_dismiss_expire_banner')) {
            var el = document.getElementById('wb-expire-header-banner');
            if (el) el.style.display = 'flex';
          }
        @else
          var el = document.getElementById('wb-expire-header-banner');
          if (el) el.style.display = 'flex';
        @endif
      })();
      function dismissWbExpireBanner() {
        sessionStorage.setItem('wb_dismiss_expire_banner', '1');
        var el = document.getElementById('wb-expire-header-banner');
        if (el) el.style.display = 'none';
      }
    </script>
    @if($wbIsLocked)
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.agency-admin-sidebar a.wb-nav-locked').forEach(function(link) {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = "{{ route('website-builder.agency-admin.subscription') }}";
            return false;
          });
        });
      });
    </script>
    @endif
  @endif
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toastify JS for Notifications -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
      @if(session('success'))
          Toastify({
              text: "{!! addslashes(session('success')) !!}",
              duration: 5000,
              close: true,
              gravity: "top", 
              position: "right",
              style: {
                  background: "#10B981",
                  borderRadius: "8px",
                  fontWeight: "600",
                  padding: "16px 20px",
                  boxShadow: "0 10px 25px rgba(0,0,0,0.15)"
              }
          }).showToast();
      @endif

      @if(session('error'))
          Toastify({
              text: "{!! addslashes(session('error')) !!}",
              duration: 8000,
              close: true,
              gravity: "top", 
              position: "right",
              style: {
                  background: "#EF4444",
                  borderRadius: "8px",
                  fontWeight: "600",
                  padding: "16px 20px",
                  boxShadow: "0 10px 25px rgba(0,0,0,0.15)"
              }
          }).showToast();
      @endif
  });
</script>

@yield('scripts')
</body>
</html>
