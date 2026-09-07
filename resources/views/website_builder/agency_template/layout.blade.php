<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'DesignAGENCY - Creative Digital Solutions Agency')</title>
  <meta name="description" content="We help brands thrive in the digital world through innovative design, smart strategy, and cutting-edge technology.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --agency-primary: #10B981;
      --agency-primary-dark: #059669;
      --agency-primary-soft: #ECFDF5;
      --agency-dark: #090D16;
      --agency-dark-card: #111827;
      --agency-text-dark: #0F172A;
      --agency-text-body: #475569;
      --agency-text-muted: #64748B;
      --agency-bg-light: #F8FAFC;
      --agency-border: #E2E8F0;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--agency-text-dark);
      background-color: #090D16;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      line-height: 1.6;
    }

    /* TOP ANNOUNCEMENT BAR */
    .top-announcement {
      background: #F0FDF4;
      border-bottom: 1px solid #E6F4ED;
      padding: 7px 0;
      font-size: 12.5px;
      color: #334155;
    }
    .top-announcement a { color: #334155; text-decoration: none; transition: color 0.2s; }
    .top-announcement a:hover { color: var(--agency-primary); }

    /* MAIN NAVBAR */
    .agency-nav {
      background: #ffffff;
      sticky: top;
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid #F1F5F9;
      padding: 14px 0;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .agency-logo {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      font-weight: 800;
      font-size: 22px;
      color: #0F172A;
      letter-spacing: -0.5px;
    }
    .agency-logo span.brand-accent { color: #F97316; }
    .agency-logo span.brand-name { color: #0F172A; }
    .agency-nav-links {
      display: flex;
      align-items: center;
      gap: 28px;
      list-style: none;
      margin: 0;
    }
    .agency-nav-links a {
      color: #334155;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.2s;
      position: relative;
      padding: 4px 0;
    }
    .agency-nav-links a:hover,
    .agency-nav-links a.active {
      color: var(--agency-primary);
    }
    .agency-nav-links a.active::after {
      content: '';
      position: absolute;
      bottom: -2px; left: 0; right: 0;
      height: 2.5px;
      background: var(--agency-primary);
      border-radius: 2px;
    }

    .btn-agency-login {
      border: 1.5px solid var(--agency-primary);
      color: var(--agency-primary);
      background: transparent;
      padding: 7px 18px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 13.5px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-agency-login:hover { background: var(--agency-primary-soft); color: var(--agency-primary-dark); }
    .btn-agency-register {
      background: var(--agency-primary);
      color: #ffffff;
      padding: 8px 20px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 13.5px;
      text-decoration: none;
      transition: all 0.2s;
      box-shadow: 0 4px 14px rgba(16,185,129,0.3);
    }
    .btn-agency-register:hover { background: var(--agency-primary-dark); color: #ffffff; transform: translateY(-1px); }

    /* SECTION LABELS & HEADINGS */
    .agency-label-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #ECFDF5;
      color: #059669;
      font-size: 12px;
      font-weight: 700;
      padding: 5px 14px;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 12px;
    }
    .agency-heading {
      font-size: clamp(28px, 4vw, 42px);
      font-weight: 800;
      color: #0F172A;
      line-height: 1.18;
      letter-spacing: -0.8px;
      margin-bottom: 12px;
    }
    .agency-heading span.highlight { color: var(--agency-primary); }
    .agency-subtitle {
      font-size: 15px;
      color: var(--agency-text-muted);
      max-width: 580px;
      line-height: 1.65;
    }

    /* GREEN ROCKET CTA BANNER & FOOTER */
    /* CTA wrapper sits BETWEEN main and footer, bridging both */
    .agency-cta-outer {
      position: relative;
      z-index: 20;
      margin-top: -80px;   /* pull up into white main area by 80px */
      margin-bottom: 0;
    }
    .agency-footer {
      background-color: #080C14;
      color: #94A3B8;
      padding-top: 120px;  /* space for the bottom half of the CTA banner */
      padding-bottom: 36px;
      font-size: 13.5px;
      position: relative;
    }
    .agency-cta-banner {
      background: #22C55E;
      background: linear-gradient(135deg, #22C55E 0%, #16A34A 100%);
      border-radius: 20px;
      padding: 44px 50px;
      color: #ffffff;
      position: relative;
      overflow: hidden;
    }
    .btn-cta-white {
      background: #ffffff;
      color: #16A34A;
      font-weight: 700;
      font-size: 14px;
      padding: 12px 26px;
      border-radius: 10px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease-in-out;
    }
    .btn-cta-white:hover {
      background: #ffffff;
      color: #15803D;
      transform: translateY(-2px);
    }
    .footer-brand-title {
      font-weight: 800;
      font-size: 22px;
      color: #ffffff;
      letter-spacing: -0.4px;
    }
    .footer-col-heading {
      color: #ffffff;
      font-weight: 700;
      font-size: 15px;
      margin-bottom: 20px;
    }
    .footer-links-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .footer-links-list li {
      margin-bottom: 11px;
    }
    .footer-links-list a {
      color: #94A3B8;
      text-decoration: none;
      font-size: 13.5px;
      transition: color 0.2s ease;
    }
    .footer-links-list a:hover {
      color: #ffffff;
    }
    .footer-contact-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      color: #94A3B8;
      margin-bottom: 14px;
      font-size: 13.5px;
      line-height: 1.5;
    }
    .footer-contact-item i {
      color: #94A3B8;
      font-size: 14px;
      margin-top: 3px;
      flex-shrink: 0;
    }
    .footer-social-icon {
      width: 36px;
      height: 36px;
      background: rgba(255,255,255,0.06);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #94A3B8;
      text-decoration: none;
      margin-right: 8px;
      font-size: 13px;
      font-weight: 600;
      transition: all 0.2s ease;
    }
    .footer-social-icon:hover {
      background: #22C55E;
      color: #ffffff;
    }
    .footer-bottom-bar {
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      padding-top: 24px;
      font-size: 13px;
      color: #94A3B8;
    }

    /* RESPONSIVE */
    @media (max-width: 991px) {
      .agency-nav-links { display: none; }
      .agency-cta-banner { padding: 32px 28px; }
      .agency-cta-outer { margin-top: -60px; }
      .agency-footer { padding-top: 100px; }
    }
    @media (max-width: 767px) {
      .agency-cta-outer { margin-top: -40px; }
      .agency-footer { padding-top: 80px; }
      .footer-col-heading { margin-bottom: 14px; }
    }
  </style>
</head>
<body>

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.portfolio');
  $blogUrl = $subdomainParam ? route('website-builder.subdomain.blogs', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.blogs');
@endphp

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-0 mb-0 py-3 text-center border-0 fw-bold fs-6 shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10B981 100%); color: #ffffff; z-index: 9999;">
  <i class="fa-solid fa-rocket me-2"></i> {{ session('success') }}
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- TOP ANNOUNCEMENT BAR -->
<div class="top-announcement">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        <i class="fa-solid fa-bullhorn text-success me-1"></i>
        {{ $agency->top_announcement ?? 'We help businesses grow with creative digital solutions.' }}
      </div>
      <div class="d-flex align-items-center gap-4 flex-wrap">
        <span><i class="fa-solid fa-envelope text-success me-1"></i> <a href="mailto:{{ $agency->email ?? 'info@designagency.com' }}">{{ $agency->email ?? 'info@designagency.com' }}</a></span>
        <span><i class="fa-solid fa-phone text-success me-1"></i> {{ $agency->phone ?? '+1 (234) 567-890' }}</span>
        <div class="d-flex gap-2 ms-2">
          <a href="{{ $agency->social_links['facebook'] ?? '#' }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="{{ $agency->social_links['twitter'] ?? '#' }}" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="{{ $agency->social_links['linkedin'] ?? '#' }}" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="{{ $agency->social_links['instagram'] ?? '#' }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- NAVBAR -->
<nav class="agency-nav">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <a href="{{ $homeUrl }}" class="agency-logo">
        @if(isset($agency->site_logo) && !empty($agency->site_logo))
          <img src="{{ asset($agency->site_logo) }}" alt="{{ $agency->site_title ?? 'Logo' }}" style="max-height: 42px; max-width: 180px; object-fit: contain;">
        @else
          <span class="brand-name">Design</span><span class="brand-accent">AGENCY</span>
        @endif
      </a>

      <ul class="agency-nav-links d-none d-lg-flex">
        <li><a href="{{ $homeUrl }}" class="{{ request()->routeIs('website-builder.templates.digital_agency') || request()->routeIs('website-builder.subdomain.site') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ $homeUrl }}#services">Services</a></li>
        <li><a href="{{ $aboutUrl }}" class="{{ request()->routeIs('website-builder.templates.digital_agency.about') || request()->routeIs('website-builder.subdomain.about') ? 'active' : '' }}">About Us</a></li>
        <li><a href="{{ $portfolioUrl }}" class="{{ request()->routeIs('website-builder.templates.digital_agency.portfolio') || request()->routeIs('website-builder.subdomain.portfolio') ? 'active' : '' }}">Portfolio</a></li>
        <li><a href="{{ $contactUrl }}" class="{{ request()->routeIs('website-builder.templates.digital_agency.contact') || request()->routeIs('website-builder.subdomain.contact') ? 'active' : '' }}">Contact Us</a></li>
      </ul>

      <div class="d-none d-lg-flex align-items-center gap-2">
        <a href="{{ route('website-builder.login') }}" class="btn-agency-login">Login</a>
        <a href="{{ $contactUrl }}" class="btn-agency-register">Get Started</a>
      </div>

      <!-- Mobile Hamburger Button -->
      <button class="btn btn-light d-lg-none border-0 fs-4 p-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#agencyMobileMenu">
        <i class="fa-solid fa-bars text-dark"></i>
      </button>
    </div>
  </div>
</nav>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="agencyMobileMenu" style="width: 280px;">
  <div class="offcanvas-header border-bottom">
    <a href="{{ $homeUrl }}" class="agency-logo fs-4">
      @if(isset($agency->site_logo) && !empty($agency->site_logo))
        <img src="{{ asset($agency->site_logo) }}" alt="Logo" style="max-height: 38px; max-width: 160px; object-fit: contain;">
      @else
        <span class="brand-name">Design</span><span class="brand-accent">AGENCY</span>
      @endif
    </a>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between">
    <ul class="list-unstyled mb-4">
      <li class="mb-3"><a href="{{ $homeUrl }}" class="text-decoration-none fw-bold text-dark fs-6 d-block py-1">Home</a></li>
      <li class="mb-3"><a href="{{ $homeUrl }}#services" class="text-decoration-none fw-bold text-dark fs-6 d-block py-1">Services</a></li>
      <li class="mb-3"><a href="{{ $aboutUrl }}" class="text-decoration-none fw-bold text-dark fs-6 d-block py-1">About Us</a></li>
      <li class="mb-3"><a href="{{ $portfolioUrl }}" class="text-decoration-none fw-bold text-dark fs-6 d-block py-1">Portfolio</a></li>
      <li class="mb-3"><a href="{{ $contactUrl }}" class="text-decoration-none fw-bold text-dark fs-6 d-block py-1">Contact Us</a></li>
    </ul>

    <div class="d-grid gap-2">
      <a href="{{ route('website-builder.login') }}" class="btn-agency-login text-center py-2">Login</a>
      <a href="{{ $contactUrl }}" class="btn-agency-register text-center py-2">Get Started</a>
    </div>
  </div>
</div>

<!-- MAIN PAGE CONTENT -->
<main style="background-color: #ffffff;">
  @yield('content')
</main>

<!-- CTA BANNER: bridges white main area (top) and dark footer (bottom) -->
<div class="container agency-cta-outer">
  <div class="agency-cta-banner">
    <!-- CTA Background Graphic -->
    <img src="{{ asset('assets/website_builder/Templates/Digital_agency/footer_cta.png') }}"
         onerror="this.style.display='none';"
         alt="Footer CTA Graphic"
         style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); max-height: 100%; width: auto; object-fit: contain; pointer-events: none; opacity: 1;"
         class="d-none d-lg-block">

    <div class="row align-items-center position-relative" style="z-index: 2;">
      <div class="col-lg-7 col-md-9">
        <!-- Row 1: Icon Badge + Headline -->
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="d-inline-flex align-items-center justify-content-center bg-white p-2" style="width: 48px; height: 48px; font-size: 20px; border-radius: 12px; flex-shrink: 0; color: #16A34A;">
            <i class="fa-solid fa-chart-line"></i>
          </div>
          <h2 class="fw-bold text-white mb-0" style="font-size: 28px; letter-spacing: -0.4px; line-height: 1.25;">{{ $agency->contact_title ?? 'Ready to Grow Your Business?' }}</h2>
        </div>

        <!-- Row 2: Subtitle -->
        <p class="text-white mb-4" style="font-size: 14.5px; font-weight: 400; line-height: 1.6; max-width: 500px; opacity: 0.88;">
          {{ $agency->contact_subtitle ?? "Let's work together to create something amazing for your brand." }}
        </p>

        <!-- Row 3: Button -->
        <a href="{{ $contactUrl }}" class="btn-cta-white">
          Get In Touch <i class="fa-solid fa-arrow-up-right ms-1" style="font-size: 12px;"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER (dark, starts below CTA, with padding-top to accommodate CTA bottom half) -->
<footer class="agency-footer">
  <div class="container">
    <div class="row g-4 mb-4">
      <!-- Col 1: Brand Info -->
      <div class="col-lg-3 col-md-6">
        <div class="footer-brand-title mb-3">
          <span>Design</span><span style="color: #F97316;">AGENCY</span>
        </div>
        <p class="mb-4 text-slate-400" style="line-height: 1.65; max-width: 320px;">
          {{ $agency->footer_text ?? "We're a creative digital agency helping businesses grow with modern design, development & marketing solutions." }}
        </p>
        <div class="d-flex align-items-center gap-1">
          <a href="{{ $agency->social_links['facebook'] ?? '#' }}" class="footer-social-icon" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="{{ $agency->social_links['twitter'] ?? '#' }}" class="footer-social-icon" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="{{ $agency->social_links['linkedin'] ?? '#' }}" class="footer-social-icon" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="{{ $agency->social_links['instagram'] ?? '#' }}" class="footer-social-icon" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-col-heading">Quick Links</div>
        <ul class="footer-links-list">
          <li><a href="{{ $homeUrl }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}">About Us</a></li>
          <li><a href="{{ $homeUrl }}#services">Services</a></li>
          <li><a href="{{ $portfolioUrl }}">Portfolio</a></li>
          <li><a href="{{ $blogUrl }}">Blog</a></li>
          <li><a href="{{ $contactUrl }}">Contact Us</a></li>
        </ul>
      </div>

      <!-- Col 3: Services -->
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-col-heading">Services</div>
        <ul class="footer-links-list">
          <li><a href="{{ $homeUrl }}#services">Web Design</a></li>
          <li><a href="{{ $homeUrl }}#services">UI/UX Design</a></li>
          <li><a href="{{ $homeUrl }}#services">Branding</a></li>
          <li><a href="{{ $homeUrl }}#services">Digital Marketing</a></li>
          <li><a href="{{ $homeUrl }}#services">SEO Optimization</a></li>
          <li><a href="{{ $homeUrl }}#services">App Development</a></li>
        </ul>
      </div>

      <!-- Col 4: Resources -->
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-col-heading">Resources</div>
        <ul class="footer-links-list">
          <li><a href="{{ $portfolioUrl }}">Case Studies</a></li>
          <li><a href="{{ $homeUrl }}#testimonials">Testimonials</a></li>
          <li><a href="{{ $contactUrl }}#faqs">FAQ's</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms &amp; Conditions</a></li>
          <li><a href="#">Support</a></li>
        </ul>
      </div>

      <!-- Col 5: Contact Us -->
      <div class="col-lg-3 col-md-6 col-12">
        <div class="footer-col-heading">Contact Us</div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-location-dot"></i>
          <div>123 Design Street,<br>Creative City, CA 94043</div>
        </div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-phone"></i>
          <div>{{ $agency->phone ?? '+1 (234) 567-890' }}</div>
        </div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-envelope"></i>
          <div>{{ $agency->email ?? 'info@designagency.com' }}</div>
        </div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-clock"></i>
          <div>Mon - Fri: 9AM - 6PM</div>
        </div>
      </div>
    </div>

    <!-- BOTTOM COPYRIGHT BAR -->
    <div class="footer-bottom-bar d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        &copy; 2025 <span class="fw-bold text-white">DesignAGENCY</span>. All Rights Reserved.
      </div>
      <div>
        Made with <i class="fa-solid fa-heart text-danger mx-1"></i> for your business growth.
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
