@extends('front.layout')

@section('pagename', '')

@section('styles')
@include('front.partials.pricing_styles')
<style>
  /* Give longer thumbnail preview */
  .card-image-wrap {
      height: 380px !important;
  }
  .template-card-modern:hover .scrolling-img {
      transform: translateY(calc(-100% + 380px)) !important;
  }
  /* Shorten the card preview container for the 4-column duplicated section on Home */
  #templates-duplicate .card-image-wrap {
      height: 280px !important;
  }
  #templates-duplicate .template-card-modern:hover .scrolling-img {
      transform: translateY(calc(-100% + 280px)) !important;
  }
  .btn-template-action.admin-btn {
      background: #0e1b3d;
      color: #ffffff;
      border: 1px solid #0e1b3d;
  }
  .btn-template-action.admin-btn:hover {
      background: #1e293b;
      border-color: #1e293b;
      color: #ffffff;
  }
  .btn-template-action {
      padding: 8px 4px !important;
      font-size: 13px !important;
      white-space: nowrap !important;
  }
  .card-action-row {
      display: flex !important;
      flex-direction: column !important;
      gap: 8px !important;
  }
  /* Animated Multi-Color Running Gradient Border for Dashboard Showcase */
  .simulated-dashboard-card {
    position: relative !important;
    padding: 8px !important;
    border-radius: 20px !important;
    overflow: hidden !important;
    background: linear-gradient(135deg, #C2410C, #F97316)!important;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25) !important;
    z-index: 1 !important;
  }
  .simulated-dashboard-card::before {
    content: '' !important;
    position: absolute !important;
    width: 250% !important;
    height: 250% !important;
    top: -75% !important;
    left: -75% !important;
    background: conic-gradient(
      from 0deg,
      #10b981 0deg,
      #06b6d4 45deg,
      #3b82f6 90deg,
      transparent 90deg,
      transparent 180deg,
      #f43f5e 180deg,
      #ec4899 225deg,
      #8b5cf6 270deg,
      transparent 270deg,
      transparent 360deg
    ) !important;
    animation: spinRunningBorder 10s linear infinite !important;
    z-index: 0 !important;
  }
  .simulated-dashboard-card::after {
    content: '' !important;
    position: absolute !important;
    top: -8px !important;
    left: -8px !important;
    right: -8px !important;
    bottom: -8px !important;
    border-radius: 24px !important;
    background: conic-gradient(
      from 0deg,
      #10b981 0deg,
      #06b6d4 45deg,
      #3b82f6 90deg,
      transparent 90deg,
      transparent 180deg,
      #f43f5e 180deg,
      #ec4899 225deg,
      #8b5cf6 270deg,
      transparent 270deg,
      transparent 360deg
    ) !important;
    animation: spinRunningBorder 10s linear infinite !important;
    z-index: -1 !important;
    filter: blur(20px) !important;
    opacity: 0.9 !important;
  }
  .simulated-dashboard-card img {
    position: relative !important;
    z-index: 2 !important;
    border-radius: 14px !important;
    width: 100% !important;
    height: auto !important;
    display: block !important;
    object-fit: cover !important;
  }
  @keyframes spinRunningBorder {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }

  /* Steps & Stats Section Styles */
  .steps-stats-section {
    background: #ffffff;
    padding: 15px 0;
    font-family: 'Outfit', 'Inter', sans-serif;
  }
  .rocket-visual-wrap {
    position: relative;
    display: inline-block;
  }
  .rocket-main-img {
    max-width: 100%;
    height: auto;
    animation: floatRocket 4s ease-in-out infinite;
  }
  @keyframes floatRocket {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }
  .steps-badge {
    background: #fff5f2;
    color: #ff5a2c;
    font-size: 14px;
    font-weight: 700;
    padding: 6px 16px;
    border-radius: 50px;
    border: 1px solid rgba(255, 90, 44, 0.1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
  }
  .steps-main-title {
    font-size: 38px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.25;
  }
  .step-item-custom {
    gap: 16px;
  }
  .step-icon-container {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }
  .step-icon-orange {
    background: #fff5f2;
    color: #ff5a2c;
    border: 1px solid rgba(255, 90, 44, 0.15);
  }
  .step-icon-pink {
    background: #fdf2f8;
    color: #db2777;
    border: 1px solid rgba(219, 39, 119, 0.15);
  }
  .step-icon-green {
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid rgba(22, 163, 74, 0.15);
  }
  .step-icon-blue {
    background: #f0f9ff;
    color: #2563eb;
    border: 1px solid rgba(37, 99, 235, 0.15);
  }
  .step-icon-purple {
    background: #f5f3ff;
    color: #7c3aed;
    border: 1px solid rgba(124, 58, 237, 0.15);
  }
  .step-text-container {
    display: flex;
    flex-direction: column;
    text-align: left;
  }
  .step-item-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
  }
  .step-item-desc {
    font-size: 14px;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
  }
  .btn-start-journey {
    background: #ff5a2c;
    color: #ffffff !important;
    font-size: 15px;
    font-weight: 700;
    padding: 14px 28px;
    border-radius: 50px;
    border: none;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 4px 14px rgba(255, 90, 44, 0.3);
    transition: all 0.3s ease;
    text-decoration: none !important;
  }
  .btn-start-journey:hover {
    background: #e0481d;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 90, 44, 0.4);
  }
  .stats-bar-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 24px 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
  }
  .stat-item-wrap {
    gap: 16px;
  }
  .stat-bar-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .stat-icon-orange {
    background: #fff5f2;
    color: #ff5a2c;
  }
  .stat-icon-pink {
    background: #fdf2f8;
    color: #db2777;
  }
  .stat-icon-blue {
    background: #eff6ff;
    color: #3b82f6;
  }
  .stat-icon-green {
    background: #f0fdf4;
    color: #16a34a;
  }
  .stat-bar-info {
    display: flex;
    flex-direction: column;
    text-align: left;
  }
  .stat-bar-val {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.1;
  }
  .stat-bar-lbl {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    font-weight: 500;
  }
  @media (max-width: 575px) {
    .steps-stats-section .stats-bar-card {
      padding: 16px 10px !important;
    }
    .steps-stats-section .stat-item-wrap {
      gap: 6px !important;
      justify-content: flex-start;
    }
    .steps-stats-section .stat-bar-icon-box {
      width: 32px !important;
      height: 32px !important;
      font-size: 14px !important;
      border-radius: 8px !important;
    }
    .steps-stats-section .stat-bar-val {
      font-size: 16px !important;
    }
    .steps-stats-section .stat-bar-lbl {
      font-size: 10px !important;
    }
  }

</style>
@include('front.partials.pricing_styles')
@endsection
@php
  $additional_section_status = json_decode($bs->additional_section_status, true);
@endphp
@section('meta-description', 'Your Professional Ecommerce Store in 2 Minutes.')
@section('meta-keywords', !empty($seo) ? $seo->home_meta_keywords : '')

@section('og-meta')
  <meta property="og:title" content="Launch Your Shop.">
  <meta property="og:description" content="Your Professional Ecommerce Store in 2 Minutes.">
  <meta property="og:image" content="{{ asset('images/meta_icon.jpeg') }}">
  <meta property="og:image:secure_url" content="{{ asset('images/meta_icon.jpeg') }}">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Launch Your Shop.">
  <meta name="twitter:description" content="Your Professional Ecommerce Store in 2 Minutes.">
  <meta name="twitter:image" content="{{ asset('images/meta_icon.jpeg') }}">
  <link rel="image_src" href="{{ asset('images/meta_icon.jpeg') }}">
@endsection

@section('content')

  @if ($bs->feature_section == 1)
    <!-- Hero Section Start-->
    <section id="home" class="hero-wrapper">
      <div class="container">
        <div class="row ">
          <div class="col-lg-5 col-xl-5 order-2 order-lg-1">
            <div class="hero-content" data-aos="fade-right">
              <div class="hero-badge">
                <span style="font-weight:800; margin-right:4px;">#1</span> Ecommerce Builder For Sales & Growth🎉
              </div>
              
              <h1 class="hero-title">Launch Your  <br><span>Online Store In</span> Just 2 minutes</h1>
              
              <p class="hero-text">{{ __('Manage products, payments, and sales effortlessly. Everything you need to grow your business is just a click away. Start today and simplify your e-commerce journey.') }}</p>
              
              <div class="d-flex align-items-center gap-3 hero-btns">
                <a href="{{ route('front.pricing') }}" class="btn-ls-primary">
                  {{ __('Get Started Now') }}
                  <svg class="ms-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle;">
                    <path d="M10 6V2.5a1.5 1.5 0 0 1 3 0v8.5a.5.5 0 0 0 .5.5.5.5 0 0 0 .5-.5V8.5a1.5 1.5 0 0 1 3 0v2.5a.5.5 0 0 0 .5.5.5.5 0 0 0 .5-.5V9.5a1.5 1.5 0 0 1 3 0v3.5a6 6 0 0 1-6 6h-2a6 6 0 0 1-6-6V11a2 2 0 0 1 2-2 1.5 1.5 0 0 1 1.5 1.5v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </a>

                <a href="{{ route('front.templates.view') }}" class="btn-ls-outline">
                  <span class="btn-play-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #0F172A; border-radius: 50%; color: white; font-size: 10px;">
                    <i class="fas fa-shopping-cart" style="transform: translate(-0.5px, 0px);"></i>
                  </span>
                  {{ __('Select a Theme') }}
                </a>
              </div>

              <div class="hero-features d-flex align-items-center">
                <div class="hero-feature-item">
                  <div class="feat-icon-box icon-orange">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" stroke="#FF5A2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M9 11L11 13L15 9" stroke="#FF5A2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <div class="feat-content">
                    <div class="feat-title">{{ __('10-Days Money') }}</div>
                    <div class="feat-sub">{{ __('Back Guarantee') }}</div>
                  </div>
                </div>
                <div class="feat-divider"></div>
                <div class="hero-feature-item">
                  <div class="feat-icon-box icon-green">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M17.887 6.113c-.933-.933-2.903-1.157-5.111-.643l-4.148 4.148c-.689.69-.747 1.776-.174 2.507l-2.617 2.617a1 1 0 000 1.414l1.414 1.414a1 1 0 001.414 0l2.617-2.617c.731.573 1.817.515 2.507-.174l4.148-4.148c.514-2.208.29-4.178-.643-5.111z" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M8.5 15.5L4.5 19.5M15.5 8.5l.01-.01" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <div class="feat-content">
                    <div class="feat-title">{{ __('Launch store') }}</div>
                    <div class="feat-sub">{{ __('Instantly') }}</div>
                  </div>
                </div>
                <div class="feat-divider"></div>
                <div class="hero-feature-item">
                  <div class="feat-icon-box icon-blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M3 18V11C3 6.02944 7.02944 2 12 2C16.9706 2 21 6.02944 21 11V18" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M21 19C21 20.1 20.1 21 19 21H17C15.9 21 15 20.1 15 19V15C15 13.9 15.9 13 17 13H21V19Z" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M3 19C3 20.1 3.9 21 5 21H7C8.1 21 9 20.1 9 15V11C9 9.9 8.1 9 7 9H3V19Z" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <div class="feat-content">
                    <div class="feat-title">{{ __('24/7 Expert') }}</div>
                    <div class="feat-sub">{{ __('Support') }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-7 col-xl-7 order-1 order-lg-2 position-relative">
            <div class="hero-mockup-wrapper text-center" data-aos="fade-left">
              <img class="img-fluid lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                data-src="{{ asset('images/hero-section.png') }}" 
                alt="Storefront Mockup" style="width: 100%; height: auto; max-width: 100%; display: block; margin: 0 auto;">

              {{-- ===== Rocket Animation ===== --}}
              <div class="hero-rocket-container" id="heroRocketAnim">
                <div class="rocket-body">
                  {{-- SVG Rocket --}}
                  <svg class="rocket-svg" viewBox="0 0 90 160" xmlns="http://www.w3.org/2000/svg">
                    {{-- Body --}}
                    <ellipse cx="45" cy="85" rx="22" ry="48" fill="#ffffff" stroke="#e2e8f0" stroke-width="2"/>
                    {{-- Nose Cone --}}
                    <path d="M45 10 Q65 35 65 65 Q45 52 25 65 Q25 35 45 10Z" fill="#FF5A2C"/>
                    {{-- Window --}}
                    <circle cx="45" cy="72" r="10" fill="#dbeafe" stroke="#93c5fd" stroke-width="2"/>
                    <circle cx="45" cy="72" r="5" fill="#bfdbfe"/>
                    {{-- Window shine --}}
                    <circle cx="42" cy="69" r="2" fill="rgba(255,255,255,0.7)"/>
                    {{-- Left Fin --}}
                    <path d="M23 108 Q6 125 12 140 L23 125Z" fill="#FF5A2C"/>
                    {{-- Right Fin --}}
                    <path d="M67 108 Q84 125 78 140 L67 125Z" fill="#FF5A2C"/>
                    {{-- Nozzle --}}
                    <rect x="37" y="128" width="16" height="14" rx="4" fill="#94a3b8"/>
                    {{-- Nozzle shine --}}
                    <rect x="39" y="130" width="5" height="10" rx="2" fill="rgba(255,255,255,0.3)"/>
                    {{-- Body stripe --}}
                    <rect x="35" y="92" width="20" height="4" rx="2" fill="#FF5A2C" opacity="0.4"/>
                  </svg>

                  {{-- Fire / Exhaust --}}
                  <div class="rocket-exhaust">
                    <div class="exhaust-flame flame-outer"></div>
                    <div class="exhaust-flame flame-mid"></div>
                    <div class="exhaust-flame flame-inner"></div>
                    <div class="exhaust-spark spark-1"></div>
                    <div class="exhaust-spark spark-2"></div>
                    <div class="exhaust-spark spark-3"></div>
                  </div>
                </div>
              </div>
              {{-- ===== End Rocket ===== --}}
            </div>
          </div>
        </div>

      </div>
    </section>
    <!-- Hero Section End -->
  @endif

  @if ($bs->process_section == 1)
    <!-- How It Works Start -->
    <section class="how-it-works-v2" id="how-it-works">
      <div class="container">

        <!-- Stats Banner Row -->
        <div class="hiw-stats-row" data-aos="fade-up">
          <!-- Stat 1 -->
          <div class="hiw-stat-card">
            <div class="hiw-stat-icon hiw-icon-orange">
              <i class="fas fa-store"></i>
            </div>
            <div class="hiw-stat-info">
              <div class="hiw-stat-value"><span class="odometer" data-count="250">0</span>+</div>
              <div class="hiw-stat-label">{{ __('Stores Launched') }}</div>
            </div>
          </div>
          <!-- Divider -->
          <div class="hiw-stat-divider"></div>
          <!-- Stat 2 -->
          <div class="hiw-stat-card">
            <div class="hiw-stat-icon hiw-icon-purple">
              <i class="fas fa-gem"></i>
            </div>
            <div class="hiw-stat-info">
              <div class="hiw-stat-value"><span class="odometer" data-count="15">0</span>+</div>
              <div class="hiw-stat-label">{{ __('Premium Themes') }}</div>
            </div>
          </div>
          <!-- Divider -->
          <div class="hiw-stat-divider"></div>
          <!-- Stat 3 -->
          <div class="hiw-stat-card">
            <div class="hiw-stat-icon hiw-icon-green">
              <i class="fas fa-shield-alt"></i>
            </div>
            <div class="hiw-stat-info">
              <div class="hiw-stat-value"><span class="odometer" data-count="99">0</span>.99%</div>
              <div class="hiw-stat-label">{{ __('Uptime Guarantee') }}</div>
            </div>
          </div>
          <!-- Divider -->
          <div class="hiw-stat-divider"></div>
          <!-- Stat 4 -->
          <div class="hiw-stat-card">
            <div class="hiw-stat-icon hiw-icon-yellow">
              <i class="fas fa-user-friends"></i>
            </div>
            <div class="hiw-stat-info">
              <div class="hiw-stat-value"><span class="odometer" data-count="500">0</span>+</div>
              <div class="hiw-stat-label">{{ __('Happy Merchants') }}</div>
            </div>
          </div>
        </div>

        <!-- Section Title -->
        <div class="hiw-title-wrap" data-aos="fade-up">
          <span class="hiw-decor-dot left"></span>
          <h2 class="hiw-section-title">{{ __('How Launchshop Works') }}</h2>
          <span class="hiw-decor-dot right"></span>
        </div>

        <!-- 6 Steps Row -->
        <div class="hiw-steps-row" data-aos="fade-up">

          <!-- Step 1 -->
          <div class="hiw-step-item">
            <div class="hiw-step-icon-wrap">
              <div class="hiw-step-circle hiw-circle-pink">
                <i class="fas fa-clipboard-list"></i>
              </div>
              <div class="hiw-step-badge">1</div>
            </div>
            <h4 class="hiw-step-title">{{ __('Select a Plan') }}</h4>
            <p class="hiw-step-desc">{{ __('Choose the perfect pricing plan that fits your business needs.') }}</p>
          </div>

          <!-- Step 2 -->
          <div class="hiw-step-item">
            <div class="hiw-step-icon-wrap">
              <div class="hiw-step-circle hiw-circle-orange">
                <i class="fas fa-paint-roller"></i>
              </div>
              <div class="hiw-step-badge">2</div>
            </div>
            <h4 class="hiw-step-title">{{ __('Choose a Theme') }}</h4>
            <p class="hiw-step-desc">{{ __('Pick a professional theme that matches your brand.') }}</p>
          </div>

          <!-- Step 3 -->
          <div class="hiw-step-item">
            <div class="hiw-step-icon-wrap">
              <div class="hiw-step-circle hiw-circle-purple">
                <i class="fas fa-cog"></i>
              </div>
              <div class="hiw-step-badge">3</div>
            </div>
            <h4 class="hiw-step-title">{{ __('Customize Your Store') }}</h4>
            <p class="hiw-step-desc">{{ __('Edit colors, fonts, layout and content easily.') }}</p>
          </div>

          <!-- Step 4 -->
          <div class="hiw-step-item">
            <div class="hiw-step-icon-wrap">
              <div class="hiw-step-circle hiw-circle-blue">
                <i class="fas fa-box-open"></i>
              </div>
              <div class="hiw-step-badge">4</div>
            </div>
            <h4 class="hiw-step-title">{{ __('Add Products') }}</h4>
            <p class="hiw-step-desc">{{ __('Upload products, set prices and manage inventory.') }}</p>
          </div>

          <!-- Step 5 -->
          <div class="hiw-step-item">
            <div class="hiw-step-icon-wrap">
              <div class="hiw-step-circle hiw-circle-green">
                <i class="fas fa-credit-card"></i>
              </div>
              <div class="hiw-step-badge">5</div>
            </div>
            <h4 class="hiw-step-title">{{ __('Accept Payments') }}</h4>
            <p class="hiw-step-desc">{{ __('Enable secure payments and shipping options.') }}</p>
          </div>

          <!-- Step 6 -->
          <div class="hiw-step-item">
            <div class="hiw-step-icon-wrap">
              <div class="hiw-step-circle hiw-circle-orange">
                <i class="fas fa-rocket"></i>
              </div>
              <div class="hiw-step-badge">6</div>
            </div>
            <h4 class="hiw-step-title">{{ __('Start Selling') }}</h4>
            <p class="hiw-step-desc">{{ __('Publish your store and start growing your business.') }}</p>
          </div>

        </div>
        <!-- /6 Steps Row -->

      </div>
    </section>
    <!-- How It Works End -->
  @endif



  @if ($bs->templates_section == 1)
    <!-- Duplicated Templates Section Start -->
    <section class="templates-section mt-80" id="templates-duplicate" style="background: #f8fafc; padding: 80px 0;">
      <div class="container">
        <div class="row align-items-center mb-2">
          <div class="col-12 text-center">
            <div class="templates-badge-wrap" data-aos="fade-up">
              <span class="templates-badge">
                <i class="fas fa-palette me-2"></i>{{ __('15+ Premium Themes') }}
              </span>
            </div>
            <h2 class="section-title mb-3 text-center" data-aos="fade-up" style="font-size: 38px; font-weight: 800; color: #1E2335;">{{ __('Professional Themes for Every Industry') }}</h2>
            <p class="section-subtitle-text" data-aos="fade-up" style="font-size: 16px; color: #718096; max-width: 600px; margin: 0 auto 10px; text-align: center !important;">{{ __('Explore our optimized scrolling templates built for speed, responsiveness, and conversions.') }}</p>
          </div>
        </div>

        <!-- Filter Buttons for the second grid -->
        <div class="row mb-50 templates-filter-section" data-aos="fade-up" data-aos-delay="100">
          <div class="col-12 theme-filter-scroll-wrapper d-flex justify-content-center align-items-center gap-2 flex-wrap">
            <button class="theme-filter-btn theme-filter-btn-duplicate active" data-filter="all">
              <i class="fas fa-th-large me-2"></i>{{ __('All Themes') }}
            </button>
            <button class="theme-filter-btn theme-filter-btn-duplicate" data-filter="fashion">
              <i class="fas fa-tshirt me-2"></i>{{ __('Fashion') }}
            </button>
            <button class="theme-filter-btn theme-filter-btn-duplicate" data-filter="clothing">
              <i class="fas fa-tshirt me-2"></i>{{ __('Clothing') }}
            </button>
            <button class="theme-filter-btn theme-filter-btn-duplicate" data-filter="grocery">
              <i class="fas fa-shopping-cart me-2"></i>{{ __('Grocery') }}
            </button>
            <button class="theme-filter-btn theme-filter-btn-duplicate" data-filter="electronics">
              <i class="fas fa-laptop me-2"></i>{{ __('Electronics') }}
            </button>
            <button class="theme-filter-btn theme-filter-btn-duplicate" data-filter="beauty">
              <i class="fas fa-magic me-2"></i>{{ __('Beauty') }}
            </button>
            <button class="theme-filter-btn theme-filter-btn-duplicate" data-filter="multipurpose">
              <i class="fas fa-shopping-bag me-2"></i>{{ __('Multipurpose') }}
            </button>
            <button class="theme-filter-btn theme-filter-btn-duplicate" data-filter="others">
              <i class="fas fa-folder me-2"></i>{{ __('Others') }}
            </button>
          </div>
        </div>

        <!-- Templates Grid Section -->
        <div class="templates-grid-wrapper">
          <div class="row g-4 justify-content-start" id="templatesGrid">
            @foreach ($templates as $template)
              @php
                $themeName = App\Models\User\BasicSetting::where('user_id', $template->id)->pluck('theme')->first();
                $category = 'others';
                $displayName = __('Theme');
                $badgeClass = 'bg-secondary';
                
                if ($themeName == 'vegetables' || $themeName == 'grocery' || $themeName == 'grocery2') {
                    $category = 'grocery';
                    $displayName = ($themeName == 'grocery2') ? __('Ecom Grocery') : __('Grocery & Supermarket');
                    $badgeClass = 'badge-grocery';
                } elseif ($themeName == 'manti' || $themeName == 'multipurpose') {
                    $category = 'multipurpose';
                    $displayName = __('Multipurpose');
                    $badgeClass = 'badge-multi';
                } elseif ($themeName == 'fashion' || $themeName == 'apparel' || $themeName == 'jewellery' || $themeName == 'kids' || $themeName == 'clothing') {
                    $category = ($themeName == 'clothing') ? 'clothing' : 'fashion';
                    $displayName = match($themeName) {
                        'jewellery' => __('Jewellery & Accessories'),
                        'kids' => __('Kids Fashion'),
                        'clothing' => __('Clothing & Apparel'),
                        default => __('Fashion & Apparel'),
                    };
                    $badgeClass = 'badge-fashion';
                } elseif ($themeName == 'electronics' || $themeName == 'gadgets') {
                    $category = 'electronics';
                    $displayName = __('Electronics & Gadgets');
                    $badgeClass = 'badge-electronics';
                } elseif ($themeName == 'beauty' || $themeName == 'cosmetics' || $themeName == 'skinflow') {
                    $category = 'beauty';
                    $displayName = match($themeName) {
                        'skinflow' => __('Skin & Beauty Care'),
                        default => __('Beauty & Cosmetics'),
                    };
                    $badgeClass = 'badge-beauty';
                } else {
                    $category = 'others';
                    $displayName = ucfirst($themeName ?? 'Default');
                    $badgeClass = 'badge-others';
                }
                
                $previewUrl = detailsUrl($template);
                $purchaseUrl = route('front.pricing') . '?template=' . urlencode($template->username);
              @endphp
              
              <div class="col-lg-3 col-md-6 col-sm-12 template-card-item mb-4" data-category="{{ $category }}" data-search="{{ strtolower(trim($displayName . ' ' . ($template->shop_name ?? '') . ' ' . $template->username . ' ' . ($themeName ?? '') . ' ' . $category)) }}">
                <div class="template-card-modern">
                  <!-- Image Wrapper with scroll hover effect -->
                  <div class="card-image-wrap" style="height: 280px !important; overflow: hidden; position: relative;">
                    <span class="category-badge {{ $badgeClass }}">{{ $displayName }}</span>
                    <a href="{{ $previewUrl }}" target="_blank" class="image-viewport" style="display: block; height: 100%; overflow: hidden;">
                      @if (!empty($template->template_img))
                        <img class="lazyload scrolling-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/template-previews/' . $template->template_img) }}"
                          alt="{{ $displayName }} Theme" style="width: 100%; transition: transform 2.5s ease-in-out;" />
                      @else
                        <img class="lazyload scrolling-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/template-previews/placeholder.png') }}"
                          alt="Placeholder Theme" style="width: 100%; transition: transform 2.5s ease-in-out;" />
                      @endif
                    </a>
                  </div>
                  
                  <!-- Card Body -->
                  <div class="card-body-wrap" style="padding: 24px; background: #ffffff;">
                    <h3 class="card-theme-title" style="font-size: 18px; font-weight: 700; color: #1e2335; margin-bottom: 12px;">{{ $displayName }} {{ __('Theme') }}</h3>
                    
                    <hr class="card-divider" style="border-top: 1px solid #e2e8f0; margin: 15px 0;">
                    
                    <div class="card-action-row" style="display: flex; flex-direction: column; gap: 8px;">
                      <div class="d-flex gap-2" style="width: 100%;">
                        <a href="{{ $previewUrl }}" target="_blank" class="btn-template-action outline-btn" style="flex: 1; justify-content: center; display: inline-flex; align-items: center; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 10px; font-size: 14px; font-weight: 600; color: #4a5568; text-decoration: none;">
                          <i class="fas fa-eye me-2"></i> {{ __('Live Preview') }}
                        </a>
                        <a href="{{ route('front.templates.autologin', $template->username) }}" target="_blank" class="btn-template-action admin-btn" style="flex: 1; justify-content: center; display: inline-flex; align-items: center; border: 1.5px solid #0e1b3d; background: #0e1b3d; color: #ffffff; border-radius: 8px; padding: 10px; font-size: 14px; font-weight: 600; text-decoration: none;">
                          <i class="fas fa-user-cog me-2"></i> {{ __('Admin') }}
                        </a>
                      </div>
                      <a href="{{ $purchaseUrl }}" class="btn-template-action primary-btn" style="width: 100%; justify-content: center; display: inline-flex; align-items: center; border: 1.5px solid #ff5a2c; background: #ff5a2c; color: #ffffff; border-radius: 8px; padding: 10px; font-size: 14px; font-weight: 600; text-decoration: none;">
                        <i class="fas fa-shopping-cart me-2"></i> {{ __('Purchase') }}
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach

           

            <!-- Theme Coming Soon 2 -->
            <div class="col-lg-3 col-md-6 col-sm-12 template-card-item mb-4" data-category="all" data-search="coming soon theme template placeholder">
              <div class="template-card-modern coming-soon-card" style="height: 100%; min-height: 480px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 40px 20px; text-align: center; transition: all 0.3s ease;">
                <div class="mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: #fff5f2; display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-magic" style="font-size: 24px; color: #ff5a2c;"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: #475569; margin-bottom: 8px;">Coming Soon</h3>
                <p style="font-size: 13px; color: #94a3b8; max-width: 180px; margin-bottom: 0;">We are crafting new premium templates for your store.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- View All Themes Button Wrap for Duplicate Grid -->
        <div class="view-all-themes-btn-wrap-duplicate d-none" data-aos="fade-up" style="display: flex; justify-content: center; margin-top: 30px;">
          <button id="view-all-themes-btn-duplicate" class="btn-view-all-themes">
            {{ __('View All Themes') }} <i class="fas fa-arrow-right ms-2"></i>
          </button>
        </div>

        @php
          $defaultPackage = \App\Models\Package::where('status', '1')->where('featured', '1')->first();
          $defaultPackageId = $defaultPackage ? $defaultPackage->id : 1;
        @endphp
        <!-- Register For More Themes Button Wrap -->
        <div class="register-more-themes-btn-wrap d-none" data-aos="fade-up" style="display: flex; justify-content: center; margin-top: 30px;">
          <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $defaultPackageId]) }}" class="btn-cta-launch px-5 py-3 d-inline-flex align-items-center gap-2" style="background:#ff5a2c; color:#fff; border-radius:30px; font-weight:700; text-decoration:none; box-shadow:0 4px 15px rgba(255,90,44,0.3);">
            {{ __('Register For More Themes') }} <i class="fas fa-arrow-right"></i>
          </a>
        </div>

      </div>
    </section>
    <!-- Duplicated Templates Section End -->
  @endif

  <!-- Steps & Stats section -->
  <section class="steps-stats-section py-80" data-aos="fade-up" style="background:#fff; border-top:1px solid #f1f5f9; padding-top:60px;">
    <div class="container">
      <div class="row align-items-center mb-60">
        <!-- Left Column: Rocket Image -->
        <div class="col-lg-6 col-md-12 text-center position-relative mb-5 mb-lg-0">
          <div class="rocket-visual-wrap">
            <img src="{{ asset('images/rocket_leftside.png') }}" alt="Rocket Launch" class="img-fluid rocket-main-img" style="max-width: 85%;">
          </div>
        </div>
        
        <!-- Right Column: 5 Simple Steps -->
        <div class="col-lg-6 col-md-12">
          <div class="steps-content-wrap ps-lg-4 text-start">
            <span class="steps-badge d-inline-block">{{ __('Simple Steps. Big Results.') }}</span>
            <h2 class="steps-main-title mb-4" style="font-weight: 800; font-size: 34px; color: #1e293b;">{{ __('Launch your dream store in five simple steps.') }}</h2>
            
            <div class="steps-list mt-30">
              <!-- Step 1 -->
              <div class="step-item-custom d-flex align-items-start mb-4" style="gap: 16px;">
                <div class="step-icon-container step-icon-purple">
                  <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="step-text-container">
                  <h4 class="step-item-title">{{ __('1. Select Your Plan') }}</h4>
                  <p class="step-item-desc">{{ __('Choose the perfect pricing plan that fits your business needs.') }}</p>
                </div>
              </div>

              <!-- Step 2 -->
              <div class="step-item-custom d-flex align-items-start mb-4" style="gap: 16px;">
                <div class="step-icon-container step-icon-orange">
                  <i class="fas fa-store"></i>
                </div>
                <div class="step-text-container">
                  <h4 class="step-item-title">{{ __('2. Choose Your Theme') }}</h4>
                  <p class="step-item-desc">{{ __('Pick a professional theme that matches your brand.') }}</p>
                </div>
              </div>
              
              <!-- Step 3 -->
              <div class="step-item-custom d-flex align-items-start mb-4" style="gap: 16px;">
                <div class="step-icon-container step-icon-pink">
                  <i class="fas fa-cog"></i>
                </div>
                <div class="step-text-container">
                  <h4 class="step-item-title">{{ __('3. Customize & Setup') }}</h4>
                  <p class="step-item-desc">{{ __('Add your products, customize design & settings.') }}</p>
                </div>
              </div>
              
              <!-- Step 4 -->
              <div class="step-item-custom d-flex align-items-start mb-4" style="gap: 16px;">
                <div class="step-icon-container step-icon-green">
                  <i class="fas fa-credit-card"></i>
                </div>
                <div class="step-text-container">
                  <h4 class="step-item-title">{{ __('4. Configure Payments') }}</h4>
                  <p class="step-item-desc">{{ __('Set up payment methods and shipping options.') }}</p>
                </div>
              </div>
              
              <!-- Step 5 -->
              <div class="step-item-custom d-flex align-items-start mb-4" style="gap: 16px;">
                <div class="step-icon-container step-icon-blue">
                  <i class="fas fa-rocket"></i>
                </div>
                <div class="step-text-container">
                  <h4 class="step-item-title">{{ __('5. Launch Your Store') }}</h4>
                  <p class="step-item-desc">{{ __('Your store is live! Start selling and growing.') }}</p>
                </div>
              </div>
            </div>
            
            <div class="mt-4" style="text-align: left;">
              <a href="{{ route('front.pricing') }}" class="btn-start-journey">
                {{ __('Start Your Journey') }} <i class="fas fa-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Stats Bar at the Bottom -->
      <div class="stats-bar-card mt-5 mb-5" style="border: 1px solid #f1f5f9; border-radius: 16px; padding: 24px 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); background: #ffffff;">
        <div class="row align-items-center justify-content-between g-4">
          <!-- Stat Item 1 -->
          <div class="col-lg-3 col-md-6 col-sm-6 col-6 d-flex align-items-center justify-content-start justify-content-lg-center">
            <div class="stat-item-wrap d-flex align-items-center" style="gap: 16px;">
              <div class="stat-bar-icon-box stat-icon-orange">
                <i class="fas fa-store"></i>
              </div>
              <div class="stat-bar-info">
                <h3 class="stat-bar-val">250+</h3>
                <p class="stat-bar-lbl">{{ __('Live Stores') }}</p>
              </div>
            </div>
          </div>
          
          <!-- Stat Item 2 -->
          <div class="col-lg-3 col-md-6 col-sm-6 col-6 d-flex align-items-center justify-content-start justify-content-lg-center">
            <div class="stat-item-wrap d-flex align-items-center" style="gap: 16px;">
              <div class="stat-bar-icon-box stat-icon-pink">
                <i class="fas fa-users"></i>
              </div>
              <div class="stat-bar-info">
                <h3 class="stat-bar-val">500+</h3>
                <p class="stat-bar-lbl">{{ __('Happy Merchants') }}</p>
              </div>
            </div>
          </div>
          
          <!-- Stat Item 3 -->
          <div class="col-lg-3 col-md-6 col-sm-6 col-6 d-flex align-items-center justify-content-start justify-content-lg-center">
            <div class="stat-item-wrap d-flex align-items-center" style="gap: 16px;">
              <div class="stat-bar-icon-box stat-icon-blue">
                <i class="fas fa-gem"></i>
              </div>
              <div class="stat-bar-info">
                <h3 class="stat-bar-val">15+</h3>
                <p class="stat-bar-lbl">{{ __('Premium Themes') }}</p>
              </div>
            </div>
          </div>
          
          <!-- Stat Item 4 -->
          <div class="col-lg-3 col-md-6 col-sm-6 col-6 d-flex align-items-center justify-content-start justify-content-lg-center">
            <div class="stat-item-wrap d-flex align-items-center" style="gap: 16px;">
              <div class="stat-bar-icon-box stat-icon-green">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="stat-bar-info">
                <h3 class="stat-bar-val">99.99%</h3>
                <p class="stat-bar-lbl">{{ __('Customer Satisfaction') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Dashboard Showcase Section Start -->
  <section class="dashboard-section py-80" id="dashboard-showcase">
    <div class="container-fluid px-lg-5">
      <div class="row align-items-center">
        
        <!-- Left Text Column -->
        <div class="col-lg-3 col-md-12 mb-5 mb-lg-0" data-aos="fade-right">
          <div class="dashboard-text-wrap">
            <span class="dashboard-subtitle mb-2 d-block">{{ __('Everything Under One Roof') }}</span>
            <h2 class="dashboard-title mb-3">{{ __('Powerful Dashboard Built for Growth') }}</h2>
            <p class="dashboard-desc mb-4">{{ __('From analytics to orders, products to customers, manage every part of your business from one smart dashboard.') }}</p>
            
            <ul class="dashboard-bullets-list list-unstyled mb-4">
              <li class="mb-3 d-flex align-items-center gap-2">
                <span class="bullet-check-icon"><i class="fas fa-check"></i></span>
                <span class="bullet-txt">{{ __('Real-time sales & analytics') }}</span>
              </li>
              <li class="mb-3 d-flex align-items-center gap-2">
                <span class="bullet-check-icon"><i class="fas fa-check"></i></span>
                <span class="bullet-txt">{{ __('Manage products & inventory') }}</span>
              </li>
              <li class="mb-3 d-flex align-items-center gap-2">
                <span class="bullet-check-icon"><i class="fas fa-check"></i></span>
                <span class="bullet-txt">{{ __('Track orders, payments & shipping') }}</span>
              </li>
              <li class="mb-3 d-flex align-items-center gap-2">
                <span class="bullet-check-icon"><i class="fas fa-check"></i></span>
                <span class="bullet-txt">{{ __('Customers, coupons & reports') }}</span>
              </li>
            </ul>
            
            @php
              $defaultPackage = \App\Models\Package::where('status', '1')->where('featured', '1')->first();
              $defaultPackageId = $defaultPackage ? $defaultPackage->id : 1;
            @endphp
            @php
              $firstTemplate = $templates->first();
            @endphp
            @if($firstTemplate)
            <a href="{{ route('front.templates.autologin', $firstTemplate->username) }}" target="_blank" class="btn-dashboard-explore px-4 py-3 d-inline-flex align-items-center gap-2">
              {{ __('Explore Dashboard ') }} <i class="fas fa-arrow-right"></i>
            </a>
            @else
            <a href="{{ route('front.templates.view') }}" class="btn-dashboard-explore px-4 py-3 d-inline-flex align-items-center gap-2">
              {{ __('Explore Dashboard ') }} <i class="fas fa-arrow-right"></i>
            </a>
            @endif
          </div>
        </div>

        <!-- Center Dashboard Column -->
        <div class="col-lg-6 col-md-12 mb-5 mb-lg-0" data-aos="fade-up">
          <div class="simulated-dashboard-card">
            <img src="{{ asset('images/user_dashboard.png') }}" alt="User Dashboard" style="width: 100%; height: auto; display: block; object-fit: cover;">
          </div>
        </div>

        <!-- Right Features Column -->
        <div class="col-lg-3 col-md-12" data-aos="fade-left">
          <div class="dashboard-side-features d-flex flex-column gap-3">
            
            <!-- Feature 1 -->
            <div class="dash-side-feat-card d-flex align-items-center">
              <div class="side-feat-icon-box purple">
                <i class="fas fa-chart-pie"></i>
              </div>
              <div class="side-feat-info">
                <h4 class="side-feat-title mb-1">{{ __('Analytics & Reports') }}</h4>
                <p class="side-feat-desc mb-0">{{ __('Make data-driven decisions') }}</p>
              </div>
            </div>

            <!-- Feature 2 -->
            <div class="dash-side-feat-card d-flex align-items-center">
              <div class="side-feat-icon-box orange">
                <i class="fas fa-boxes"></i>
              </div>
              <div class="side-feat-info">
                <h4 class="side-feat-title mb-1">{{ __('Inventory Management') }}</h4>
                <p class="side-feat-desc mb-0">{{ __('Never run out of stock') }}</p>
              </div>
            </div>

            <!-- Feature 3 -->
            <div class="dash-side-feat-card d-flex align-items-center">
              <div class="side-feat-icon-box green">
                <i class="fas fa-shield-alt"></i>
              </div>
              <div class="side-feat-info">
                <h4 class="side-feat-title mb-1">{{ __('Secure Payments') }}</h4>
                <p class="side-feat-desc mb-0">{{ __('Track all transactions in one place') }}</p>
              </div>
            </div>

            <!-- Feature 4 -->
            <div class="dash-side-feat-card d-flex align-items-center">
              <div class="side-feat-icon-box pink">
                <i class="fas fa-user-friends"></i>
              </div>
              <div class="side-feat-info">
                <h4 class="side-feat-title mb-1">{{ __('Customer Insights') }}</h4>
                <p class="side-feat-desc mb-0">{{ __('Understand your customers better') }}</p>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- Dashboard Showcase Section End -->

  @if (count($after_template) > 0)
    @foreach ($after_template as $cusTemplate)
      @if (isset($additional_section_status[$cusTemplate->id]) && $additional_section_status[$cusTemplate->id] == 1)
        @php
          $cusTemplateContent = App\Models\AdditionalSectionContent::where([
              ['language_id', $lang_id],
              ['addition_section_id', $cusTemplate->id],
          ])->first();
        @endphp
        @includeIf('front.additional-section', ['data' => $cusTemplateContent, 'possition' => $cusTemplate->possition])
      @endif
    @endforeach
  @endif




  @if (count($after_work_process) > 0)
    @foreach ($after_work_process as $cusWorkProcess)
      @if (isset($additional_section_status[$cusWorkProcess->id]) && $additional_section_status[$cusWorkProcess->id] == 1)
        @php
          $cusWorkProcessContent = App\Models\AdditionalSectionContent::where([
              ['language_id', $lang_id],
              ['addition_section_id', $cusWorkProcess->id],
          ])->first();
        @endphp
        @includeIf('front.additional-section', ['data' => $cusWorkProcessContent, 'possition' => $cusWorkProcess->possition])
      @endif
    @endforeach
  @endif


  @if ($bs->pricing_section == 1)

    <!-- Pricing Section Start -->
    <section class="pricing-section pb-120" id="home-pricing-section">
      @php
        $selectedTemplate = request()->query('template');
      @endphp
      <div class="container">

        <!-- Hero Header Section matching pricing page -->
        <div class="pricing-hero-center text-center" data-aos="fade-up">
          <div class="pricing-eyebrow-badge" style="font-size: 13px; font-weight: 800; color: #ff5a2c; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px;">SIMPLE, TRANSPARENT PRICING</div>
          <h2 class="section-title mb-3" style="font-size: 38px; font-weight: 800; color: #1e2335; line-height: 1.2;">Choose the <span style="color: #ff5a2c;">perfect plan</span> for your business</h2>
          <p class="subtitle font-weight-medium" style="font-size: 16px; color: #64748b; max-width: 600px; margin: 0 auto 24px;">Launch your store in minutes and scale as your business grows.</p>
        </div>

        @include('front.partials.pricing_cards')

      </div>
    </section>
  @endif



  @if (count($after_pricing) > 0)
    @foreach ($after_pricing as $cusPricing)
      @if (isset($additional_section_status[$cusPricing->id]) && $additional_section_status[$cusPricing->id] == 1)
        @php
          $cusPricingContent = App\Models\AdditionalSectionContent::where([
              ['language_id', $lang_id],
              ['addition_section_id', $cusPricing->id],
          ])->first();
        @endphp
        @includeIf('front.additional-section', ['data' => $cusPricingContent, 'possition' => $cusPricing->possition])
      @endif
    @endforeach
  @endif


  {{-- features-grid section removed --}}



  <!-- Claim Subdomain Banner -->
  <section class="subdomain-banner">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="subdomain-inner flex-column flex-lg-row text-center text-lg-start" data-aos="fade-up">
            <div class="d-flex align-items-center mb-4 mb-lg-0">
              <div class="subdomain-title-icon" style="width: 50px; height: 50px; border-radius: 50%; background: #ffffff; display: flex; align-items: center; justify-content: center; margin-right: 15px; flex-shrink: 0;">
                <i class="fas fa-globe" style="font-size: 24px; color: #ff5a2c;"></i>
              </div>
              <div>
                <h3 class="subdomain-title">Claim Your Branded Subdomain</h3>
                <p class="text-white-50 mb-0">Get a professional identity for your store in seconds.</p>
              </div>
            </div>
            <div class="subdomain-check-container mt-3 mt-lg-0" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
              <div class="subdomain-input-group" style="max-width: 100%; flex-grow: 1;">
                <span class="domain-prefix">https://</span>
                <input type="text" id="subdomain-input" placeholder="mystore" value="mystore">
                <span class="domain-ext">.launchshop.in</span>
                <button type="button" id="btn-check-availability">
                  <span class="d-none d-md-inline">Check Availability</span>
                  <span class="d-inline d-md-none">Check</span>
                </button>
              </div>
              @php
                $defaultPackage = \App\Models\Package::where('status', '1')->where('featured', '1')->first();
                $defaultPackageId = $defaultPackage ? $defaultPackage->id : 1;
              @endphp
              <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $defaultPackageId]) }}" class="btn-subdomain-launch-now" style="background: #ffffff; color: #ff5a2c; border: 1.5px solid #ffffff; border-radius: 8px; padding: 12px 24px; font-size: 14px; font-weight: 700; text-decoration: none; white-space: nowrap; height: 50px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease;" onmouseover="this.style.background='#ff5a2c';this.style.color='#ffffff';this.style.borderColor='#ff5a2c';" onmouseout="this.style.background='#ffffff';this.style.color='#ff5a2c';this.style.borderColor='#ffffff';">
                {{ __('Launch Now') }}
              </a>
              <div id="subdomain-status" class="subdomain-status-msg text-start mt-2" style="display: none; width: 100%;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Claim Subdomain Banner End -->


  @if ($bs->testimonial_section == 1)
    <!-- Testimonials Start -->
    <section class="testimonials-section">
      <div class="container">
        
        <div class="row">
          <div class="col-12 text-center">
            <h2 class="section-title" data-aos="fade-up">{{ @$homeSec->testimonial_section_title ?: 'Loved by Entrepreneurs. Trusted by Thousands.' }}</h2>
          </div>
        </div>

        <div class="testimonials-slider-wrapper position-relative" data-aos="fade-up">
          <div class="swiper testimonials-slider pb-40">
            <div class="swiper-wrapper">
              @foreach ($testimonials as $testimonial)
                <div class="swiper-slide">
                  <div class="testimonial-card h-100 mb-0">
                    <div class="testi-header">
                      @php
                        $isWoman = preg_match('/(sarah|priya|sneha|female|woman|lady|mary|jane|sara)/i', $testimonial->name . ' ' . $testimonial->designation);
                        $defaultImg = $isWoman ? 'indian_woman.png' : 'indian_man.png';
                        $imgFile = $testimonial->image ?: $defaultImg;
                      @endphp
                      <img class="lazyload" src="{{ asset('assets/front/img/testimonials/' . $imgFile) }}"
                        data-src="{{ asset('assets/front/img/testimonials/' . $imgFile) }}"
                        alt="{{ $testimonial->name }}">
                      <div>
                        <h4 class="testi-name">{{ $testimonial->name }}</h4>
                        <span class="testi-designation">{{ $testimonial->designation }}</span>
                      </div>
                    </div>
                    <p class="testi-text">
                      <span class="testi-quote">“</span>{{ $testimonial->comment }}”
                    </p>
                    <div class="testi-stars">
                      <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
          </div>

          <!-- Navigation Arrow Buttons flanking the carousel -->
          <div class="swiper-button-prev testimonials-prev-btn">
            <i class="fas fa-chevron-left"></i>
          </div>
          <div class="swiper-button-next testimonials-next-btn">
            <i class="fas fa-chevron-right"></i>
          </div>

        </div>

      </div>
    </section>
    <!-- Testimonials End -->
  @endif

  @if (count($after_testimonial) > 0)
    @foreach ($after_testimonial as $cusTestimonial)
      @if (isset($additional_section_status[$cusTestimonial->id]) && $additional_section_status[$cusTestimonial->id] == 1)
        @php
          $cusTestimonialContent = App\Models\AdditionalSectionContent::where([
              ['language_id', $lang_id],
              ['addition_section_id', $cusTestimonial->id],
          ])->first();
        @endphp
        @includeIf('front.additional-section', ['data' => $cusTestimonialContent, 'possition' => $cusTestimonial->possition])
      @endif
    @endforeach
  @endif

  {{-- brands-banner section removed --}}


  <!-- Bottom CTA Start -->
  <section class="bottom-cta-revamp py-80">
    <div class="container position-relative">
      
      <!-- Dot grid decorators -->
      <div class="cta-decor-dots cta-decor-left"></div>
      <div class="cta-decor-dots cta-decor-right"></div>
      
      <!-- Main Blue Card -->
      <div class="cta-blue-card mb-4" data-aos="fade-up">
        <div class="row align-items-center g-0">
          
          <!-- Left Column (Text & Buttons) -->
          <div class="col-lg-6 col-md-12 p-4 p-sm-5 text-center text-lg-start">
            <h2 class="cta-revamp-title mb-3">{{ __('Ready to Launch Your Dream Store?') }}</h2>
            <p class="cta-revamp-desc mb-4">{{ __('Join thousands of entrepreneurs and start selling online with Launchshop in just minutes.') }}</p>
            
            <!-- Desktop Button (Laptop / Desktop only) -->
            <div class="cta-revamp-btns d-none d-lg-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
              @php
                $defaultPackage = \App\Models\Package::where('status', '1')->where('featured', '1')->first();
                $defaultPackageId = $defaultPackage ? $defaultPackage->id : 1;
              @endphp
              <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $defaultPackageId]) }}" class="btn-cta-launch px-4 py-3 d-inline-flex align-items-center gap-2">
                {{ __('Launch Your Store') }} <i class="fas fa-hand-pointer animated-click-hand"></i>
              </a>
            </div>
          </div>

          <!-- Right Column (Footer Right Image + Mobile Button below image) -->
          <div class="col-lg-6 col-md-12 text-center d-flex flex-column align-items-center justify-content-end h-100 position-relative cta-right-col">
            <img src="{{ asset('images/footer_right.png') }}" class="img-fluid cta-right-img" alt="Ready to Launch">
            
            <!-- Mobile Button (Renders ONLY below the image on mobile screens) -->
            <div class="cta-revamp-btns d-lg-none d-flex justify-content-center text-center mt-3 pb-4 px-3 w-100">
              <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $defaultPackageId]) }}" class="btn-cta-launch px-4 py-3 d-inline-flex align-items-center gap-2">
                {{ __('Launch Your Store') }} <i class="fas fa-hand-pointer animated-click-hand"></i>
              </a>
            </div>
          </div>

        </div>
      </div>

      <!-- Trust Badges Banner -->
      <div class="cta-trust-banner py-4 px-3" data-aos="fade-up">
        <div class="d-flex flex-wrap align-items-center justify-content-around gap-4 text-center">
          
          <div class="trust-badge-item d-flex align-items-center justify-content-center gap-2">
            <span class="trust-icon"><i class="fas fa-crown"></i></span>
            <span class="trust-txt">{{ __('Premium Stores') }}</span>
          </div>

          <div class="trust-badge-item d-flex align-items-center justify-content-center gap-2">
            <span class="trust-icon"><i class="fas fa-rocket"></i></span>
            <span class="trust-txt">{{ __('Launch Instantly') }}</span>
          </div>

          <div class="trust-badge-item d-flex align-items-center justify-content-center gap-2">
            <span class="trust-icon"><i class="fas fa-shield-alt"></i></span>
            <span class="trust-txt">{{ __('10-Days Money Back Guarantee') }}</span>
          </div>

          <div class="trust-badge-item d-flex align-items-center justify-content-center gap-2">
            <span class="trust-icon"><i class="fas fa-chart-line"></i></span>
            <span class="trust-txt">{{ __('Get High Conversions') }}</span>
          </div>

          <div class="trust-badge-item d-flex align-items-center justify-content-center gap-2">
            <span class="trust-icon"><i class="fas fa-headset"></i></span>
            <span class="trust-txt">{{ __('24/7 Expert Support') }}</span>
          </div>

        </div>
      </div>

    </div>
  </section>
  <!-- Bottom CTA End -->

@section('scripts')
<script>
  // ── New V2: See More Features toggle per card ──
  function togglePlanFeatures(pkgId, btn) {
    var $extra = document.getElementById('extra-' + pkgId);
    var $txt   = btn.querySelector('.see-more-txt');
    var $icon  = btn.querySelector('.see-more-icon');
    if (!$extra) return;
    if ($extra.classList.contains('open')) {
      $extra.classList.remove('open');
      $txt.textContent  = 'See More Features';
      $icon.className   = 'fas fa-arrow-right see-more-icon';
      $icon.style.fontSize = '11px';
    } else {
      $extra.classList.add('open');
      $txt.textContent  = 'See Less Features';
      $icon.className   = 'fas fa-arrow-up see-more-icon';
      $icon.style.fontSize = '11px';
    }
  }

  $(document).ready(function() {

    // ===== Duplicated Templates Grid Filtering and Limit Logic =====
    var activeFilterThemes = 'all';
    var itemsLimitThemes = 4;
    var showAllThemes = false;

    function filterTemplates() {
        // Get all template items in the duplicated section
        var filteredItems = $('.template-card-item');
        if (activeFilterThemes !== 'all') {
            if (activeFilterThemes === 'clothing') {
                filteredItems = $('.template-card-item').filter(function() {
                    var cat = $(this).attr('data-category');
                    return cat === 'clothing' || cat === 'fashion' || cat === 'kids';
                });
            } else {
                filteredItems = $('.template-card-item[data-category="' + activeFilterThemes + '"]');
            }
        }

        // Hide all items first
        $('.template-card-item').addClass('d-none');

        if (showAllThemes) {
            filteredItems.removeClass('d-none');
            $('.view-all-themes-btn-wrap-duplicate').addClass('d-none');
            $('.register-more-themes-btn-wrap').removeClass('d-none');
        } else {
            filteredItems.slice(0, itemsLimitThemes).removeClass('d-none');
            if (filteredItems.length > itemsLimitThemes) {
                $('.view-all-themes-btn-wrap-duplicate').removeClass('d-none');
            } else {
                $('.view-all-themes-btn-wrap-duplicate').addClass('d-none');
            }
            $('.register-more-themes-btn-wrap').addClass('d-none');
        }
    }

    $('.theme-filter-btn-duplicate').on('click', function(e) {
        e.preventDefault();
        $('.theme-filter-btn-duplicate').removeClass('active');
        $(this).addClass('active');
        activeFilterThemes = $(this).data('filter').toLowerCase().trim();
        showAllThemes = false;
        filterTemplates();
    });

    $('#view-all-themes-btn-duplicate').on('click', function(e) {
        e.preventDefault();
        showAllThemes = true;
        filterTemplates();
    });

    // Run initially
    filterTemplates();
    // 1. Template filter and show balance logic
    let itemsLimit = 4;
    let activeCategory = 'all';
    let showAll = false;

    function applyFilterAndLimit() {
      // Get all items in active category
      let filteredItems = $('.theme-item-col');
      if (activeCategory !== 'all') {
        filteredItems = $('.theme-item-col[data-category="' + activeCategory + '"]');
      }

      // Hide all items first
      $('.theme-item-col').addClass('d-none');

      if (showAll) {
        // Show all filtered items
        filteredItems.removeClass('d-none');
        // Hide the button
        $('.view-all-themes-btn-wrap').addClass('d-none');
      } else {
        // Show first 4 filtered items
        filteredItems.slice(0, itemsLimit).removeClass('d-none');
        
        // Show button only if there are more than itemsLimit items
        if (filteredItems.length > itemsLimit) {
          $('.view-all-themes-btn-wrap').removeClass('d-none');
        } else {
          $('.view-all-themes-btn-wrap').addClass('d-none');
        }
      }
    }

    // Category button click
    $('#templates .theme-filter-btn, #templates .theme-filter-dropdown-item').on('click', function(e) {
      e.preventDefault();
      
      // Remove active classes
      $('#templates .theme-filter-btn').removeClass('active');
      
      // If it is a dropdown item, add active to the dropdown toggle button
      if ($(this).hasClass('theme-filter-dropdown-item')) {
        $('#templates #moreCategoriesDropdown').addClass('active');
      } else {
        $(this).addClass('active');
      }

      activeCategory = $(this).data('category').toLowerCase().trim();
      showAll = false; // Reset to show only first 4 on category switch
      applyFilterAndLimit();
    });

    // "View All Themes" button click -> reveal all balance themes
    $('#view-all-themes-btn').on('click', function(e) {
      e.preventDefault();
      showAll = true;
      applyFilterAndLimit();
    });

    // Initialize on page load
    applyFilterAndLimit();

    // Shop animation loop — restart CSS animations every 6.5s
    (function loopShopAnim() {
      var svg = document.getElementById('shopAnim');
      if (!svg) return;
      function restart() {
        // Force a DOM reflow to restart CSS animations
        svg.style.display = 'none';
        void svg.offsetWidth; // trigger reflow
        svg.style.display = 'block';
        setTimeout(restart, 6500);
      }
      // Start first loop after initial animation completes
      setTimeout(restart, 6500);
    })();

    // 2. Subdomain check availability logic
    function checkSubdomain() {
      let inputVal = $('#subdomain-input').val().trim().toLowerCase();
      // Keep only alphanumeric characters
      inputVal = inputVal.replace(/[^a-z0-9]/g, '');
      $('#subdomain-input').val(inputVal);

      if (inputVal.length === 0) {
        $('#subdomain-status')
          .removeClass('text-success text-danger text-info')
          .addClass('text-warning')
          .html('<i class="fas fa-exclamation-triangle me-1"></i> Please enter a subdomain name.')
          .fadeIn(200);
        return;
      }

      // Show checking loader
      $('#subdomain-status')
        .removeClass('text-success text-danger text-warning text-info')
        .addClass('text-info')
        .html('<i class="fas fa-spinner fa-spin me-1"></i> Checking availability...')
        .fadeIn(200);

      $.get("{{ url('/') }}/check/" + inputVal + '/username', function(isTaken) {
        if (isTaken == true) {
          // Username is already taken
          $('#subdomain-status')
            .removeClass('text-success text-info text-warning')
            .addClass('text-danger')
            .html('<i class="fas fa-times-circle me-1" style="color: #ef4444;"></i> ' + inputVal + '.launchshop.in is already taken!')
            .fadeIn(200);
        } else {
          // Username is available
          $('#subdomain-status')
            .removeClass('text-danger text-info text-warning')
            .addClass('text-success')
            .html('<i class="fas fa-check-circle me-1" style="color: #22c55e;"></i> ' + inputVal + '.launchshop.in is available!')
            .fadeIn(200);
        }
      }).fail(function() {
        $('#subdomain-status')
          .removeClass('text-success text-info')
          .addClass('text-danger')
          .html('<i class="fas fa-times-circle me-1"></i> Error checking availability. Please try again.')
          .fadeIn(200);
      });
    }

    // Trigger check on button click
    $('#btn-check-availability').on('click', function(e) {
      e.preventDefault();
      checkSubdomain();
    });

    // Trigger check on Enter key
    $('#subdomain-input').on('keypress', function(e) {
      if (e.which === 13) {
        e.preventDefault();
        checkSubdomain();
      }
    });

    // Run check availability on initial load if value is present
    if ($('#subdomain-input').val().length > 0) {
      checkSubdomain();
    }

    // 3. Testimonials swiper slider auto scroll
    new Swiper('.testimonials-slider', {
      slidesPerView: 3,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: '.testimonials-next-btn',
        prevEl: '.testimonials-prev-btn',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
          spaceBetween: 15
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30
        }
      }
    });

    // 13. Smooth transition for pricing feature toggles
    $(document).on('click', '.pricing-feature-toggle', function (e) {
      e.preventDefault();
      var $btn = $(this);
      var $card = $btn.closest('.pricing-card-modern');
      var $extra = $card.find('.pricing-features-extra');
      var $moreLabel = $btn.find('.show-more-label');
      var $lessLabel = $btn.find('.show-less-label');
      
      var isExpanded = $card.hasClass('expanded');
      
      if (isExpanded) {
        $extra.slideUp(400, function() {
          $card.removeClass('expanded');
          $btn.attr('aria-expanded', 'false');
          $moreLabel.removeClass('d-none');
          $lessLabel.addClass('d-none');
        });
      } else {
        $card.addClass('expanded');
        $btn.attr('aria-expanded', 'true');
        $moreLabel.addClass('d-none');
        $lessLabel.removeClass('d-none');
        $extra.hide().slideDown(400);
      }
    });

    // How It Works Step Line Scroll Animation
    function updateStepLine() {
      const container = $('.steps-container');
      if (!container.length) return;

      const isMobile = window.innerWidth <= 991;
      const rect = container[0].getBoundingClientRect();
      const windowHeight = $(window).height();

      // Line starts growing when container's top enters 80% of screen height
      // and is fully filled when container's bottom reaches 20% of screen height
      const startPoint = windowHeight * 0.8;
      const endPoint = windowHeight * 0.2;
      const containerHeight = container.outerHeight();
      const totalDistance = containerHeight + (startPoint - endPoint);

      let progress = (startPoint - rect.top) / totalDistance;
      progress = Math.max(0, Math.min(1, progress));

      const lineFill = $('.steps-line-fill');
      if (lineFill.length) {
        $('.steps-line').css({
          opacity: progress > 0 ? 1 : 0,
          visibility: progress > 0 ? 'visible' : 'hidden'
        });
        if (isMobile) {
          lineFill.css({
            height: (progress * 100) + '%',
            width: '100%'
          });
        } else {
          lineFill.css({
            width: (progress * 100) + '%',
            height: '100%'
          });
        }
      }
    }

    $(window).on('scroll', updateStepLine);
    $(window).on('resize', updateStepLine);
    updateStepLine();
  });

  // ===== Rocket Launch Animation (Looping & Slow Launch) =====
  (function() {
    var rocketEl = document.getElementById('heroRocketAnim');
    if (!rocketEl) return;

    function startCycle() {
      rocketEl.classList.remove('rocket-launching');
      rocketEl.classList.remove('rocket-hidden');
      rocketEl.classList.remove('rocket-fade-in');
      
      // Let the rocket float/idle for 4 seconds before launch
      setTimeout(function() {
        launch();
      }, 4000);
    }

    function launch() {
      rocketEl.classList.add('rocket-launching');
      
      // Slow launch animation takes 3.0s to fly away and fade out
      setTimeout(function() {
        hideAndReset();
      }, 3000);
    }

    function hideAndReset() {
      rocketEl.classList.remove('rocket-launching');
      rocketEl.classList.add('rocket-hidden');
      
      // Keep completely hidden/reset for 2 seconds
      setTimeout(function() {
        fadeIn();
      }, 2000);
    }

    function fadeIn() {
      rocketEl.classList.remove('rocket-hidden');
      rocketEl.classList.add('rocket-fade-in');
      
      // Wait for the 1s fade-in animation to complete
      setTimeout(function() {
        startCycle();
      }, 1000);
    }

    // Initialize cycle
    startCycle();
  })();

  // ── New V2: See More Features toggle per card ──
  function togglePlanFeatures(pkgId, btn) {
    var $extra = document.getElementById('extra-' + pkgId);
    var $txt   = btn.querySelector('.see-more-txt');
    var $icon  = btn.querySelector('.see-more-icon');
    if (!$extra) return;
    if ($extra.classList.contains('open')) {
      $extra.classList.remove('open');
      $txt.textContent  = 'See More Features';
      $icon.className   = 'fas fa-arrow-right see-more-icon';
      $icon.style.fontSize = '11px';
    } else {
      $extra.classList.add('open');
      $txt.textContent  = 'See Less Features';
      $icon.className   = 'fas fa-arrow-up see-more-icon';
      $icon.style.fontSize = '11px';
    }
  }
</script>
@endsection

@include('front.partials.growth_deal_popup')
@endsection
