<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $settings['hero']['title'] ?? 'WhatsApp Operations, Campaigns, and Automation | Whatsappbulk' }}</title>
  <meta name="description" content="{{ $settings['hero']['subtitle'] ?? 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.' }}">

  {{-- Fonts: Plus Jakarta Sans / Inter --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  {{-- FontAwesome Icons --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  {{-- Bootstrap 5 Grid & Utilities --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    :root {
      --wa-primary: #00a859;
      --wa-primary-hover: #008f4c;
      --wa-dark-green: #047857;
      --wa-deep-green: #064e3b;
      --wa-light-mint: #eefbf3;
      --wa-border-mint: #bbf7d0;
      --wa-bg-soft: #f7fcf9;
      --wa-dark: #0f172a;
      --wa-slate: #1e293b;
      --wa-muted: #64748b;
      --wa-border: #e2e8f0;
      --wa-card-shadow: 0 10px 30px rgba(0, 168, 89, 0.06);
      --wa-card-shadow-hover: 0 16px 40px rgba(0, 168, 89, 0.12);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: var(--wa-slate);
      background-color: #ffffff;
      overflow-x: hidden;
      line-height: 1.6;
    }

    a {
      text-decoration: none;
      transition: all 0.2s ease;
    }

    /* Ambient Hero Glow Background */
    .hero-wrapper {
      background: radial-gradient(circle at 50% -10%, #dcfce7 0%, #f0fdf4 35%, #ffffff 75%);
      position: relative;
      overflow: hidden;
    }

    /* Pill Badges */
    .section-pill-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 16px;
      background: var(--wa-light-mint);
      border: 1px solid var(--wa-border-mint);
      color: var(--wa-dark-green);
      font-size: 11.5px;
      font-weight: 700;
      letter-spacing: 0.8px;
      border-radius: 50px;
      text-transform: uppercase;
      margin-bottom: 16px;
    }
    .section-pill-badge i {
      font-size: 11px;
    }

    /* Typography */
    .section-title {
      font-size: 36px;
      font-weight: 800;
      color: var(--wa-dark);
      line-height: 1.25;
      margin-bottom: 14px;
    }
    .section-subtitle {
      font-size: 15.5px;
      color: var(--wa-muted);
      line-height: 1.6;
      max-width: 620px;
      margin: 0 auto;
    }
    .highlight-green {
      color: var(--wa-primary);
    }

    /* Navbar */
    .wa-navbar {
      padding: 20px 0;
      background: transparent;
    }
    .wa-nav-pill-box {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid var(--wa-border);
      border-radius: 60px;
      padding: 8px 18px 8px 24px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }
    .brand-logo-text {
      font-size: 21px;
      font-weight: 800;
      color: var(--wa-dark);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .brand-logo-icon {
      width: 32px;
      height: 32px;
      background: var(--wa-primary);
      color: #ffffff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }
    .wa-nav-links a {
      color: var(--wa-slate);
      font-weight: 600;
      font-size: 14.5px;
      padding: 8px 14px;
      border-radius: 30px;
    }
    .wa-nav-links a:hover {
      color: var(--wa-primary);
    }
    .btn-wa-signup {
      background: var(--wa-primary);
      color: #ffffff !important;
      font-weight: 700;
      font-size: 14px;
      padding: 9px 22px;
      border-radius: 50px;
      box-shadow: 0 4px 14px rgba(0, 168, 89, 0.35);
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-wa-signup:hover {
      background: var(--wa-primary-hover);
      transform: translateY(-1px);
    }

    /* Hero Buttons */
    .btn-hero-primary {
      background: var(--wa-primary);
      color: #ffffff;
      font-weight: 700;
      font-size: 15.5px;
      padding: 14px 30px;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 6px 20px rgba(0, 168, 89, 0.35);
      transition: all 0.25s ease;
    }
    .btn-hero-primary:hover {
      background: var(--wa-primary-hover);
      color: #ffffff;
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0, 168, 89, 0.45);
    }
    .btn-hero-secondary {
      background: #ffffff;
      color: var(--wa-dark);
      font-weight: 600;
      font-size: 15.5px;
      padding: 14px 28px;
      border-radius: 50px;
      border: 1px solid var(--wa-border);
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.25s ease;
    }
    .btn-hero-secondary:hover {
      border-color: #cbd5e1;
      background: #f8fafc;
      color: var(--wa-dark);
    }

    /* Hero Stat Boxes */
    .hero-stat-card {
      background: #ffffff;
      border: 1px solid var(--wa-border);
      border-radius: 14px;
      padding: 14px 16px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      display: flex;
      align-items: center;
      gap: 12px;
      height: 100%;
    }
    .hero-stat-icon-box {
      width: 40px;
      height: 40px;
      min-width: 40px;
      background: var(--wa-light-mint);
      color: var(--wa-primary);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
    }
    .hero-stat-num {
      font-size: 20px;
      font-weight: 800;
      color: var(--wa-dark);
      line-height: 1.1;
    }
    .hero-stat-desc {
      font-size: 11.5px;
      color: var(--wa-muted);
      font-weight: 600;
      line-height: 1.3;
    }

    /* Right Hero Dashboard Window Mockup */
    .dashboard-window-mockup {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      box-shadow: 0 25px 60px rgba(15, 23, 42, 0.09);
      position: relative;
      overflow: hidden;
    }
    .dash-topbar {
      background: #f8fafc;
      border-bottom: 1px solid #e2e8f0;
      padding: 12px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .dash-sidebar {
      width: 170px;
      background: #fcfdfd;
      border-right: 1px solid #eef2f6;
      padding: 14px 10px;
    }
    .dash-nav-item {
      font-size: 11.5px;
      font-weight: 600;
      color: #64748b;
      padding: 7px 10px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 4px;
    }
    .dash-nav-item.active {
      background: var(--wa-light-mint);
      color: var(--wa-dark-green);
      font-weight: 700;
    }
    .dash-main-body {
      flex: 1;
      padding: 16px 20px;
    }
    .metric-badge {
      font-size: 10px;
      font-weight: 700;
      color: #059669;
      background: #ecfdf5;
      padding: 2px 6px;
      border-radius: 4px;
    }

    /* Floating Phone Mockup */
    .floating-phone-mockup {
      position: absolute;
      right: -25px;
      bottom: -25px;
      width: 220px;
      background: #ffffff;
      border: 5px solid #0f172a;
      border-radius: 28px;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18);
      padding: 14px 12px;
      z-index: 10;
    }

    /* Module Stack Cards (Section 2) */
    .stack-card {
      background: #ffffff;
      border: 1px solid var(--wa-border);
      border-radius: 20px;
      padding: 28px 24px;
      box-shadow: var(--wa-card-shadow);
      transition: all 0.3s ease;
      height: 100%;
      position: relative;
    }
    .stack-card:hover {
      transform: translateY(-6px);
      box-shadow: var(--wa-card-shadow-hover);
      border-color: var(--wa-border-mint);
    }
    .stack-icon-box {
      width: 48px;
      height: 48px;
      background: var(--wa-light-mint);
      color: var(--wa-primary);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      margin-bottom: 18px;
    }
    .stack-tag {
      font-size: 11px;
      font-weight: 700;
      color: var(--wa-muted);
      letter-spacing: 0.6px;
      text-transform: uppercase;
      margin-bottom: 6px;
    }
    .stack-card-title {
      font-size: 19px;
      font-weight: 800;
      color: var(--wa-dark);
      margin-bottom: 8px;
    }
    .stack-card-desc {
      font-size: 13.5px;
      color: var(--wa-muted);
      line-height: 1.5;
      margin-bottom: 20px;
    }
    .stack-arrow-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--wa-light-mint);
      color: var(--wa-dark-green);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      transition: all 0.2s ease;
    }
    .stack-card:hover .stack-arrow-btn {
      background: var(--wa-primary);
      color: #ffffff;
      transform: translateX(4px);
    }

    /* Product Modules 8 Cards (Section 3) */
    .module-feature-card {
      background: #ffffff;
      border: 1px solid var(--wa-border);
      border-radius: 18px;
      padding: 24px 20px;
      box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
      transition: all 0.25s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .module-feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
      border-color: #cbd5e1;
    }
    .module-icon-circle {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      margin-bottom: 16px;
    }
    .module-title {
      font-size: 17px;
      font-weight: 700;
      color: var(--wa-dark);
      margin-bottom: 8px;
    }
    .module-desc {
      font-size: 13px;
      color: var(--wa-muted);
      line-height: 1.5;
      margin-bottom: 16px;
    }
    .module-mini-arrow {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #f1f5f9;
      color: #64748b;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      margin-top: auto;
    }
    .module-feature-card:hover .module-mini-arrow {
      background: var(--wa-primary);
      color: #ffffff;
    }

    /* Pricing Section (Section 4) */
    .pricing-card {
      background: #ffffff;
      border: 1px solid var(--wa-border);
      border-radius: 22px;
      padding: 32px 24px;
      box-shadow: var(--wa-card-shadow);
      transition: all 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
      position: relative;
    }
    .pricing-card.featured {
      border: 2px solid var(--wa-primary);
      box-shadow: 0 15px 45px rgba(0, 168, 89, 0.16);
      transform: scale(1.02);
    }
    .pricing-card:hover {
      transform: translateY(-6px);
    }
    .pricing-card.featured:hover {
      transform: scale(1.02) translateY(-6px);
    }
    .popular-badge {
      position: absolute;
      top: -14px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--wa-primary);
      color: #ffffff;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 14px;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .plan-price-val {
      font-size: 38px;
      font-weight: 800;
      color: var(--wa-dark);
      line-height: 1;
    }
    .plan-price-term {
      font-size: 14px;
      color: var(--wa-muted);
      font-weight: 500;
    }
    .plan-features-list {
      list-style: none;
      padding: 0;
      margin: 24px 0;
      flex-grow: 1;
    }
    .plan-features-list li {
      font-size: 13.5px;
      color: var(--wa-slate);
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .plan-features-list li i {
      color: var(--wa-primary);
      font-size: 14px;
    }
    .btn-plan-action {
      width: 100%;
      padding: 12px 20px;
      border-radius: 50px;
      font-size: 14.5px;
      font-weight: 700;
      text-align: center;
      transition: all 0.25s ease;
      text-decoration: none;
      display: block;
    }
    .btn-plan-white {
      background: #ffffff;
      color: var(--wa-dark);
      border: 1px solid var(--wa-border);
    }
    .btn-plan-white:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      color: var(--wa-dark);
    }
    .btn-plan-green {
      background: var(--wa-primary);
      color: #ffffff;
      border: 1px solid var(--wa-primary);
      box-shadow: 0 4px 16px rgba(0, 168, 89, 0.35);
    }
    .btn-plan-green:hover {
      background: var(--wa-primary-hover);
      color: #ffffff;
    }
    .btn-plan-dark {
      background: #0f172a;
      color: #ffffff;
      border: 1px solid #0f172a;
    }
    .btn-plan-dark:hover {
      background: #1e293b;
      color: #ffffff;
    }

    /* Testimonials (Section 5) */
    .testimonial-card {
      background: #ffffff;
      border: 1px solid var(--wa-border);
      border-radius: 20px;
      padding: 28px 24px;
      box-shadow: var(--wa-card-shadow);
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .testi-quote-icon {
      font-size: 28px;
      color: var(--wa-primary);
      margin-bottom: 12px;
      line-height: 1;
    }
    .testi-quote-text {
      font-size: 14px;
      color: var(--wa-slate);
      line-height: 1.6;
      margin-bottom: 24px;
      font-style: italic;
    }
    .testi-user-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      object-fit: cover;
    }

    /* FAQs (Section 6) */
    .faq-accordion-item {
      background: #ffffff;
      border: 1px solid var(--wa-border);
      border-radius: 14px;
      margin-bottom: 12px;
      overflow: hidden;
      transition: all 0.2s ease;
    }
    .faq-accordion-item:hover {
      border-color: #cbd5e1;
    }
    .faq-header-btn {
      width: 100%;
      background: none;
      border: none;
      padding: 16px 20px;
      text-align: left;
      font-size: 15px;
      font-weight: 700;
      color: var(--wa-dark);
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
    }
    .faq-toggle-icon {
      font-size: 14px;
      color: var(--wa-muted);
      transition: transform 0.2s;
    }
    .faq-content-body {
      padding: 0 20px 18px 20px;
      font-size: 14px;
      color: var(--wa-muted);
      line-height: 1.6;
      display: none;
    }
    .faq-accordion-item.open .faq-content-body {
      display: block;
    }
    .faq-accordion-item.open .faq-toggle-icon {
      transform: rotate(45deg);
      color: var(--wa-primary);
    }

    /* Bottom CTA Banner (Section 7) */
    .bottom-cta-banner-card {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      border-radius: 28px;
      padding: 48px 40px;
      color: #ffffff;
      box-shadow: 0 20px 50px rgba(4, 120, 87, 0.25);
      position: relative;
      overflow: hidden;
    }
    .btn-cta-white {
      background: #ffffff;
      color: var(--wa-dark-green);
      font-weight: 700;
      font-size: 15px;
      padding: 13px 28px;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }
    .btn-cta-white:hover {
      background: #f8fafc;
      color: var(--wa-deep-green);
      transform: translateY(-2px);
    }

    /* Footer */
    .wa-landing-footer {
      background: #ffffff;
      border-top: 1px solid var(--wa-border);
      padding: 40px 0 24px 0;
    }
    .footer-nav-links a {
      color: var(--wa-slate);
      font-size: 14px;
      font-weight: 600;
      margin: 0 12px;
    }
    .footer-nav-links a:hover {
      color: var(--wa-primary);
    }
    .footer-social-btn {
      width: 34px;
      height: 34px;
      background: #f1f5f9;
      color: var(--wa-muted);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 0 4px;
      font-size: 13px;
    }
    .footer-social-btn:hover {
      background: var(--wa-primary);
      color: #ffffff;
    }
  </style>
</head>
<body>

  @php
    $hero = $settings['hero'] ?? [];
    $stack = $settings['stack'] ?? [];
    $modules = $settings['modules'] ?? [];
    $pricing = $settings['pricing'] ?? [];
    $testimonials = $settings['testimonials'] ?? [];
    $faqs = $settings['faqs'] ?? [];
    $ctaFooter = $settings['cta_footer'] ?? [];
  @endphp

  <!-- ============================================================
       TOP AMBIENT HERO & NAVBAR
       ============================================================ -->
  <div class="hero-wrapper">
    
    <!-- Navbar -->
    <header class="wa-navbar">
      <div class="container">
        <div class="wa-nav-pill-box d-flex align-items-center justify-content-between">
          
          <!-- Logo -->
          <a href="{{ route('front.landing') }}" class="brand-logo-text">
            <span class="brand-logo-icon"><i class="fab fa-whatsapp"></i></span>
            <span>{{ $ctaFooter['brand_name'] ?? 'Whatsappbulk' }}</span>
          </a>

          <!-- Nav Links -->
          <div class="wa-nav-links d-none d-lg-flex align-items-center">
            <a href="#hero">Home</a>
            <a href="#workspace">Features</a>
            <a href="#pricing">Pricing</a>
            <a href="#faqs">FAQs</a>
            <a href="{{ \Illuminate\Support\Facades\Route::has('front.blogs') ? route('front.blogs') : url('/blogs') }}">Blog</a>
            <a href="{{ \Illuminate\Support\Facades\Route::has('front.contact') ? route('front.contact') : url('/contact') }}">Contact</a>
          </div>

          <!-- Right Action Buttons -->
          <div class="d-flex align-items-center gap-3">
            <a href="{{ \Illuminate\Support\Facades\Route::has('user.login') ? route('user.login') : url('/login') }}" class="text-dark font-weight-bold small d-none d-sm-inline" style="font-weight: 700;">Sign In</a>
            <a href="{{ $hero['btn1_url'] ?? '/pricing' }}" class="btn-wa-signup">
              <span>Sign Up</span> <i class="fas fa-arrow-right"></i>
            </a>
          </div>

        </div>
      </div>
    </header>

    <!-- Hero Content -->
    <section class="py-5" id="hero">
      <div class="container pt-3 pb-5">
        <div class="row align-items-center g-5">
          
          <!-- Left Column: Copy & Stats -->
          <div class="col-lg-6">
            
            <!-- Pill Badge -->
            <div class="section-pill-badge">
              <i class="fas fa-bullhorn"></i>
              <span>{{ $hero['badge'] ?? 'ALL-IN-ONE WHATSAPP MARKETING TOOL' }}</span>
            </div>

            <!-- Main Heading with Highlight -->
            @php
              $heroTitle = $hero['title'] ?? 'WhatsApp operations, campaigns, and automation';
              $formattedTitle = str_ireplace('campaigns,', '<span class="highlight-green">campaigns</span>,', $heroTitle);
            @endphp
            <h1 class="display-5 fw-bolder mb-3" style="color: var(--wa-dark); font-weight: 800; line-height: 1.15; font-size: 46px;">
              {!! $formattedTitle !!}
            </h1>

            <!-- Subtitle -->
            <p class="text-muted mb-4" style="font-size: 15.5px; line-height: 1.65; max-width: 520px;">
              {{ $hero['subtitle'] ?? 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports. Built around the modules teams actually use every day.' }}
            </p>

            <!-- CTA Buttons -->
            <div class="d-flex flex-wrap gap-3 mb-5">
              <a href="{{ $hero['btn1_url'] ?? '/pricing' }}" class="btn-hero-primary">
                <span>{{ $hero['btn1_text'] ?? 'Create your workspace' }}</span> <i class="fas fa-arrow-right"></i>
              </a>
              <a href="{{ $hero['btn2_url'] ?? '#modules' }}" class="btn-hero-secondary">
                <span>{{ $hero['btn2_text'] ?? 'Explore modules' }}</span>
              </a>
            </div>

            <!-- 3 Stat Feature Boxes -->
            <div class="row g-2">
              <div class="col-4">
                <div class="hero-stat-card">
                  <div class="hero-stat-icon-box"><i class="fas fa-cubes"></i></div>
                  <div>
                    <div class="hero-stat-num">{{ $hero['stat1_val'] ?? '8' }}</div>
                    <div class="hero-stat-desc">{{ $hero['stat1_lbl'] ?? 'Operational modules included' }}</div>
                  </div>
                </div>
              </div>

              <div class="col-4">
                <div class="hero-stat-card">
                  <div class="hero-stat-icon-box"><i class="fas fa-link"></i></div>
                  <div>
                    <div class="hero-stat-num">{{ $hero['stat2_val'] ?? '1' }}</div>
                    <div class="hero-stat-desc">{{ $hero['stat2_lbl'] ?? 'Unified WhatsApp workflow' }}</div>
                  </div>
                </div>
              </div>

              <div class="col-4">
                <div class="hero-stat-card">
                  <div class="hero-stat-icon-box"><i class="fas fa-bolt"></i></div>
                  <div>
                    <div class="hero-stat-num">{{ $hero['stat3_val'] ?? '24/7' }}</div>
                    <div class="hero-stat-desc">{{ $hero['stat3_lbl'] ?? 'Automation & reply coverage' }}</div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- Right Column: Dashboard & Phone Graphic Mockup -->
          <div class="col-lg-6 position-relative">
            
            <div class="dashboard-window-mockup">
              <!-- Window Topbar -->
              <div class="dash-topbar">
                <div class="d-flex align-items-center gap-2">
                  <span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                  <span style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                  <span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                  <span class="ms-2 small fw-bold text-dark"><i class="fab fa-whatsapp text-success me-1"></i> Whatsappbulk Dashboard</span>
                </div>
                <div class="badge bg-light text-dark border small px-2 py-1"><i class="fas fa-user-circle me-1"></i> Hi, Admin</div>
              </div>

              <div class="d-flex">
                <!-- Sidebar -->
                <div class="dash-sidebar d-none d-sm-block">
                  <div class="dash-nav-item active"><i class="fas fa-th-large"></i> Dashboard</div>
                  <div class="dash-nav-item"><i class="fas fa-bullhorn"></i> Bulk Campaigns</div>
                  <div class="dash-nav-item"><i class="fas fa-magic"></i> AI Smart Reply</div>
                  <div class="dash-nav-item"><i class="fas fa-clock"></i> Auto Reply</div>
                  <div class="dash-nav-item"><i class="fas fa-robot"></i> Chatbot</div>
                  <div class="dash-nav-item"><i class="fas fa-address-book"></i> Contacts</div>
                  <div class="dash-nav-item"><i class="fas fa-users"></i> Export Participants</div>
                  <div class="dash-nav-item"><i class="fas fa-id-card"></i> Profile Info</div>
                  <div class="dash-nav-item"><i class="fas fa-chart-line"></i> Reports</div>
                </div>

                <!-- Main Content -->
                <div class="dash-main-body">
                  <!-- Metrics 4-grid -->
                  <div class="row g-2 mb-3">
                    <div class="col-6">
                      <div class="p-2 border rounded bg-white">
                        <div class="text-muted" style="font-size: 10px;">Total Messages</div>
                        <div class="fw-bold" style="font-size: 15px;">{{ $hero['total_messages'] ?? '125,890' }} <span class="metric-badge">+20.4%</span></div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="p-2 border rounded bg-white">
                        <div class="text-muted" style="font-size: 10px;">Delivered</div>
                        <div class="fw-bold" style="font-size: 15px;">{{ $hero['delivered'] ?? '118,420' }} <span class="metric-badge">+18.1%</span></div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="p-2 border rounded bg-white">
                        <div class="text-muted" style="font-size: 10px;">Replied</div>
                        <div class="fw-bold" style="font-size: 15px;">{{ $hero['replied'] ?? '28,460' }} <span class="metric-badge">+24.8%</span></div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="p-2 border rounded bg-white">
                        <div class="text-muted" style="font-size: 10px;">Active Contacts</div>
                        <div class="fw-bold" style="font-size: 15px;">{{ $hero['active_contacts'] ?? '96,350' }} <span class="metric-badge">+12.0%</span></div>
                      </div>
                    </div>
                  </div>

                  <!-- Message Performance SVG Chart -->
                  <div class="p-2 border rounded bg-white mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <span class="small fw-bold" style="font-size: 11px;">Message Performance</span>
                      <span class="badge bg-light text-muted border" style="font-size: 9px;">Last 30 Days</span>
                    </div>
                    <svg viewBox="0 0 300 65" class="w-100" style="height: 60px;">
                      <defs>
                        <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                          <stop offset="0%" stop-color="#ffedd5" stop-opacity="0.8"/>
                          <stop offset="100%" stop-color="#fff" stop-opacity="0"/>
                        </linearGradient>
                      </defs>
                      <path d="M 0 50 Q 50 48, 100 35 T 200 25 T 300 12 L 300 65 L 0 65 Z" fill="url(#chartGrad)"/>
                      <path d="M 0 50 Q 50 48, 100 35 T 200 25 T 300 12" fill="none" stroke="#f97316" stroke-width="2.5"/>
                    </svg>
                  </div>

                  <!-- Mini Campaigns Table -->
                  <div class="p-2 border rounded bg-white">
                    <div class="small fw-bold mb-1" style="font-size: 11px;">Recent Campaigns</div>
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom" style="font-size: 10px;">
                      <span><i class="fas fa-bullhorn text-warning me-1"></i> Product Launch</span>
                      <span class="badge bg-success-subtle text-success">Completed (12,450)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1" style="font-size: 10px;">
                      <span><i class="fas fa-star text-warning me-1"></i> Festival Offer</span>
                      <span class="badge bg-warning-subtle text-warning">Sending (25,600)</span>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <!-- Floating Mobile Screen Mockup -->
            <div class="floating-phone-mockup d-none d-md-block">
              <div class="d-flex align-items-center justify-content-between pb-2 border-bottom mb-2">
                <span class="badge bg-success text-white rounded-pill px-2 py-1"><i class="fab fa-whatsapp me-1"></i> WhatsApp</span>
                <span class="small fw-bold" style="font-size: 10px;">Live</span>
              </div>
              <div class="text-center py-2">
                <div class="small text-muted" style="font-size: 11px;">Campaign Overview</div>
                <div class="display-6 fw-bold text-success my-1" style="font-size: 26px;">{{ $hero['delivery_rate'] ?? '95.8%' }}</div>
                <div class="small text-muted" style="font-size: 10px;">Average Delivery Rate</div>
              </div>
              <!-- Mini Bars -->
              <div class="d-flex align-items-end justify-content-around mt-2 pt-2 border-top" style="height: 45px;">
                <div style="width: 14px; height: 25px; background: #bbf7d0; border-radius: 4px;"></div>
                <div style="width: 14px; height: 35px; background: #86efac; border-radius: 4px;"></div>
                <div style="width: 14px; height: 42px; background: #22c55e; border-radius: 4px;"></div>
                <div style="width: 14px; height: 38px; background: #16a34a; border-radius: 4px;"></div>
              </div>
            </div>

          </div>

        </div>
      </div>
    </section>

  </div>

  <!-- ============================================================
       SECTION 2: LIVE MODULE STACK (4 CARDS)
       ============================================================ -->
  <section class="py-5" id="workspace" style="background: var(--wa-bg-soft);">
    <div class="container py-4">
      
      <!-- Section Header -->
      <div class="text-center mb-5">
        <div class="section-pill-badge">
          <i class="fas fa-layer-group"></i>
          <span>{{ $stack['badge'] ?? 'LIVE MODULE STACK' }}</span>
        </div>
        <h2 class="section-title">{{ $stack['title'] ?? 'Whatsappbulk Workspace' }}</h2>
        <p class="section-subtitle">{{ $stack['subtitle'] ?? 'Everything you need to run WhatsApp at scale' }}</p>
      </div>

      <!-- 4 Stack Cards Grid -->
      <div class="row g-4">
        @foreach ($stack['cards'] ?? [] as $card)
          <div class="col-md-6 col-lg-3">
            <div class="stack-card">
              <div class="stack-icon-box">
                <i class="{{ $card['icon'] ?? 'fas fa-bullhorn' }}"></i>
              </div>
              <div class="stack-tag">{{ $card['tag'] ?? '' }}</div>
              <h3 class="stack-card-title">{{ $card['title'] ?? '' }}</h3>
              <p class="stack-card-desc">{{ $card['desc'] ?? '' }}</p>
              <div>
                <a href="{{ $card['url'] ?? '#pricing' }}" class="stack-arrow-btn">
                  <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 3: PRODUCT MODULES (8 FEATURES GRID)
       ============================================================ -->
  <section class="py-5" id="modules" style="background: #ffffff;">
    <div class="container py-5">
      
      <!-- Header -->
      <div class="text-center mb-5">
        <div class="section-pill-badge">
          <i class="fas fa-th"></i>
          <span>{{ $modules['badge'] ?? 'PRODUCT MODULES' }}</span>
        </div>
        @php
          $modTitle = $modules['title'] ?? 'Built from the WhatsApp features teams actually need';
          $formattedModTitle = str_ireplace('WhatsApp features', '<span class="highlight-green">WhatsApp features</span>', $modTitle);
        @endphp
        <h2 class="section-title">{!! $formattedModTitle !!}</h2>
        <p class="section-subtitle">{{ $modules['subtitle'] ?? 'Every module supports a real operational job, from outreach and automation to contact management and reporting.' }}</p>
      </div>

      <!-- 8 Grid Cards -->
      <div class="row g-4">
        @foreach ($modules['items'] ?? [] as $item)
          <div class="col-md-6 col-lg-3">
            <div class="module-feature-card">
              <div>
                <div class="module-icon-circle" style="background: {{ ($item['color'] ?? '#10b981') }}18; color: {{ $item['color'] ?? '#10b981' }};">
                  <i class="{{ $item['icon'] ?? 'fas fa-check' }}"></i>
                </div>
                <h4 class="module-title">{{ $item['title'] ?? '' }}</h4>
                <p class="module-desc">{{ $item['desc'] ?? '' }}</p>
              </div>
              <div>
                <span class="module-mini-arrow"><i class="fas fa-arrow-right"></i></span>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 4: PRICING (4 TIERS)
       ============================================================ -->
  <section class="py-5" id="pricing" style="background: var(--wa-bg-soft);">
    <div class="container py-5">
      
      <!-- Header -->
      <div class="text-center mb-5 position-relative">
        <div class="section-pill-badge">
          <i class="fas fa-tags"></i>
          <span>{{ $pricing['badge'] ?? 'PRICING' }}</span>
        </div>
        <h2 class="section-title position-relative d-inline-block">
          {{ $pricing['title'] ?? 'Simple and transparent pricing' }}
        </h2>
        <p class="section-subtitle">{{ $pricing['subtitle'] ?? 'Choose the plan that fits your business needs. Upgrade or downgrade anytime.' }}</p>
      </div>

      <!-- 4 Pricing Cards -->
      <div class="row g-4 align-items-stretch">
        @foreach ($pricing['plans'] ?? [] as $i => $plan)
          @php
            $isFeatured = !empty($plan['featured']);
          @endphp
          <div class="col-md-6 col-lg-3">
            <div class="pricing-card {{ $isFeatured ? 'featured' : '' }}">
              
              @if ($isFeatured)
                <div class="popular-badge">{{ $plan['badge'] ?? 'Most Popular' }}</div>
              @endif

              <div class="mb-3">
                <h4 class="fw-bold mb-1" style="font-size: 20px; color: var(--wa-dark);">{{ $plan['name'] ?? '' }}</h4>
                <div class="text-muted small" style="font-size: 12.5px;">{{ $plan['sub'] ?? '' }}</div>
              </div>

              <div class="d-flex align-items-baseline gap-1 my-3">
                <span class="plan-price-val">{{ $plan['price'] ?? '' }}</span>
                @if (!empty($plan['term']))
                  <span class="plan-price-term">{{ $plan['term'] }}</span>
                @endif
              </div>

              <ul class="plan-features-list">
                @foreach ($plan['features'] ?? [] as $feat)
                  <li>
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $feat }}</span>
                  </li>
                @endforeach
              </ul>

              <div class="mt-auto pt-3">
                @if ($isFeatured)
                  <a href="{{ $plan['btn_url'] ?? '/register' }}" class="btn-plan-action btn-plan-green">
                    {{ $plan['btn_text'] ?? 'Get Started' }}
                  </a>
                @elseif (stripos($plan['name'] ?? '', 'enterprise') !== false)
                  <a href="{{ $plan['btn_url'] ?? '/contact' }}" class="btn-plan-action btn-plan-dark">
                    {{ $plan['btn_text'] ?? 'Contact Sales' }}
                  </a>
                @else
                  <a href="{{ $plan['btn_url'] ?? '/register' }}" class="btn-plan-action btn-plan-white">
                    {{ $plan['btn_text'] ?? 'Get Started' }}
                  </a>
                @endif
              </div>

            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 5: TESTIMONIALS (3 REVIEWS)
       ============================================================ -->
  <section class="py-5" id="testimonials" style="background: #ffffff;">
    <div class="container py-5">
      
      <!-- Header -->
      <div class="text-center mb-5">
        <div class="section-pill-badge">
          <i class="fas fa-star"></i>
          <span>{{ $testimonials['badge'] ?? 'TESTIMONIALS' }}</span>
        </div>
        <h2 class="section-title">{{ $testimonials['title'] ?? 'What our customers say' }}</h2>
        <p class="section-subtitle">{{ $testimonials['subtitle'] ?? 'Trusted by businesses worldwide to streamline their WhatsApp operations.' }}</p>
      </div>

      <!-- 3 Review Cards -->
      <div class="row g-4">
        @foreach ($testimonials['reviews'] ?? [] as $rev)
          <div class="col-md-4">
            <div class="testimonial-card">
              <div>
                <div class="testi-quote-icon"><i class="fas fa-quote-left"></i></div>
                <p class="testi-quote-text">"{{ $rev['quote'] ?? '' }}"</p>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                <div class="d-flex align-items-center gap-3">
                  @if (!empty($rev['avatar']))
                    <img src="{{ $rev['avatar'] }}" alt="{{ $rev['name'] ?? 'User' }}" class="testi-user-avatar">
                  @else
                    <div class="testi-user-avatar bg-light text-success d-flex align-items-center justify-content-center fw-bold">
                      {{ substr($rev['name'] ?? 'U', 0, 1) }}
                    </div>
                  @endif
                  <div>
                    <div class="fw-bold text-dark" style="font-size: 14px;">{{ $rev['name'] ?? '' }}</div>
                    <div class="text-muted small" style="font-size: 12px;">{{ $rev['role'] ?? '' }}</div>
                  </div>
                </div>

                <div class="text-warning small">
                  @for ($s = 0; $s < ($rev['stars'] ?? 5); $s++)
                    <i class="fas fa-star"></i>
                  @endfor
                </div>
              </div>

            </div>
          </div>
        @endforeach
      </div>

      <!-- Dots Indicator -->
      <div class="d-flex justify-content-center gap-2 mt-4 pt-2">
        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--wa-primary); display: inline-block;"></span>
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 6: FAQS (2-COLUMN ACCORDION)
       ============================================================ -->
  <section class="py-5" id="faqs" style="background: var(--wa-bg-soft);">
    <div class="container py-5">
      
      <!-- Header -->
      <div class="text-center mb-5">
        <div class="section-pill-badge">
          <i class="fas fa-question-circle"></i>
          <span>{{ $faqs['badge'] ?? 'FAQS' }}</span>
        </div>
        <h2 class="section-title">{{ $faqs['title'] ?? 'Frequently asked questions' }}</h2>
        <p class="section-subtitle">{{ $faqs['subtitle'] ?? 'Get answers to common questions about Whatsappbulk.' }}</p>
      </div>

      <!-- 2-Column Accordion -->
      <div class="row g-4">
        @php
          $allFaqs = $faqs['items'] ?? [];
          $half = ceil(count($allFaqs) / 2);
          $col1 = array_slice($allFaqs, 0, $half);
          $col2 = array_slice($allFaqs, $half);
        @endphp

        <!-- Left Column -->
        <div class="col-lg-6">
          @foreach ($col1 as $idx => $f)
            <div class="faq-accordion-item {{ $idx === 0 ? 'open' : '' }}">
              <button type="button" class="faq-header-btn">
                <span>{{ $f['q'] ?? '' }}</span>
                <i class="fas fa-plus faq-toggle-icon"></i>
              </button>
              <div class="faq-content-body">
                {{ $f['a'] ?? '' }}
              </div>
            </div>
          @endforeach
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
          @foreach ($col2 as $idx => $f)
            <div class="faq-accordion-item">
              <button type="button" class="faq-header-btn">
                <span>{{ $f['q'] ?? '' }}</span>
                <i class="fas fa-plus faq-toggle-icon"></i>
              </button>
              <div class="faq-content-body">
                {{ $f['a'] ?? '' }}
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 7: BOTTOM CTA BANNER
       ============================================================ -->
  <section class="py-5" style="background: #ffffff;">
    <div class="container">
      <div class="bottom-cta-banner-card">
        <div class="row align-items-center g-4">
          
          <!-- Left WhatsApp Circle Icon -->
          <div class="col-lg-2 text-center text-lg-start">
            <div style="width: 80px; height: 80px; background: rgba(255, 255, 255, 0.15); border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; color: #ffffff;">
              <i class="fab fa-whatsapp"></i>
            </div>
          </div>

          <!-- Center Content -->
          <div class="col-lg-6 text-center text-lg-start">
            <h3 class="fw-bold mb-2 text-white" style="font-size: 30px; line-height: 1.25;">
              {{ $ctaFooter['cta_title'] ?? 'Ready to scale your WhatsApp marketing?' }}
            </h3>
            <p class="mb-0 text-white-50" style="font-size: 14.5px; line-height: 1.6;">
              {{ $ctaFooter['cta_subtitle'] ?? 'Connect your WhatsApp account in seconds. Start sending bulk campaigns, automating customer replies, and converting more leads with zero coding required.' }}
            </p>
          </div>

          <!-- Right Buttons -->
          <div class="col-lg-4 text-center text-lg-end">
            <a href="{{ $ctaFooter['cta_btn1_url'] ?? '/pricing' }}" class="btn-cta-white mb-2">
              <span>{{ $ctaFooter['cta_btn1_txt'] ?? 'Get Started Free' }}</span> <i class="fas fa-arrow-right"></i>
            </a>
            <div>
              <a href="{{ $ctaFooter['cta_btn2_url'] ?? '#pricing' }}" class="text-white-50 small text-decoration-underline" style="font-size: 13px;">
                {{ $ctaFooter['cta_btn2_txt'] ?? 'View Pricing Plans' }} &rarr;
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================
       SECTION 8: FOOTER
       ============================================================ -->
  <footer class="wa-landing-footer">
    <div class="container">
      
      <!-- Top Row -->
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pb-4 border-bottom">
        
        <!-- Logo & Tagline -->
        <div>
          <a href="{{ route('front.landing') }}" class="brand-logo-text mb-1">
            <span class="brand-logo-icon"><i class="fab fa-whatsapp"></i></span>
            <span>{{ $ctaFooter['brand_name'] ?? 'Whatsappbulk' }}</span>
          </a>
          <div class="text-muted small" style="font-size: 12.5px;">{{ $ctaFooter['tagline'] ?? 'WhatsApp Marketing & Automation Tool' }}</div>
        </div>

        <!-- Center Nav Links -->
        <div class="footer-nav-links d-none d-md-flex align-items-center">
          <a href="#hero">Home</a>
          <a href="#workspace">Features</a>
          <a href="#pricing">Pricing</a>
          <a href="#faqs">FAQs</a>
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.blogs') ? route('front.blogs') : url('/blogs') }}">Blog</a>
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.contact') ? route('front.contact') : url('/contact') }}">Contact</a>
        </div>

        <!-- Social Media Icons -->
        <div class="d-flex align-items-center">
          <a href="https://facebook.com" target="_blank" class="footer-social-btn"><i class="fab fa-facebook-f"></i></a>
          <a href="https://twitter.com" target="_blank" class="footer-social-btn"><i class="fab fa-x-twitter"></i></a>
          <a href="https://linkedin.com" target="_blank" class="footer-social-btn"><i class="fab fa-linkedin-in"></i></a>
          <a href="https://youtube.com" target="_blank" class="footer-social-btn"><i class="fab fa-youtube"></i></a>
        </div>

      </div>

      <!-- Bottom Row -->
      <div class="d-flex flex-wrap align-items-center justify-content-between pt-3" style="font-size: 12.5px; color: var(--wa-muted);">
        <div>
          {{ $ctaFooter['copyright'] ?? '© 2026 Whatsappbulk. All Rights Reserved.' }}
        </div>
        <div class="d-flex gap-3">
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.privacy-policy') ? route('front.privacy-policy') : url('/privacy-policy') }}" class="text-muted">Privacy Policy</a>
          <span>·</span>
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.terms-conditions') ? route('front.terms-conditions') : url('/terms-conditions') }}" class="text-muted">Terms & Conditions</a>
        </div>
      </div>

    </div>
  </footer>

  {{-- Interactive FAQs Accordion Script --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const faqHeaders = document.querySelectorAll('.faq-header-btn');
      faqHeaders.forEach(header => {
        header.addEventListener('click', function () {
          const item = this.closest('.faq-accordion-item');
          const isOpen = item.classList.contains('open');

          // Optional: close siblings if you want single expand behavior
          // document.querySelectorAll('.faq-accordion-item').forEach(i => i.classList.remove('open'));

          if (isOpen) {
            item.classList.remove('open');
          } else {
            item.classList.add('open');
          }
        });
      });
    });
  </script>
</body>
</html>