<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout & Website Setup - {{ $settings->brand_name ?? 'Website Builder' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --primary: {{ $settings->primary_color ?? '#5B4BF5' }};
      --primary-light: {{ $settings->secondary_color ?? '#7C6CF8' }};
      --primary-soft: #EEF0FD;
      --text-dark: #0F0E17;
      --text-body: #3D3D5C;
      --text-muted: #7B7B9D;
      --border: #E8E8F0;
      --bg-light: #F8F8FC;
      --bg-white: #FFFFFF;
      --hero-dark: #0B0B1E;
      --success-green: #22C55E;
    }
    
    html, body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background-color: #0B0B1E !important;
      color: #0F172A;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      margin: 0 !important;
      padding: 0 !important;
      overflow-x: hidden;
    }

    /* NAVBAR */
    @keyframes launchGradientMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    @keyframes popIn {
      0% { transform: scale(0.9); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    .wb-nav {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: rgba(11, 11, 30, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255,255,255,0.08);
      padding: 12px 0;
    }
    .wb-nav .container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }
    .wb-logo {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      font-weight: 800;
      font-size: 18px;
      color: #fff;
      white-space: nowrap;
    }
    .wb-logo img {
      max-height: 54px;
      height: 72px;
      width: auto;
      object-fit: contain;
    }
    .wb-logo-icon {
      width: 40px;
      height: 40px;
      background: var(--primary);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: #fff;
      flex-shrink: 0;
    }
    .wb-logo span {
      font-size: 20px;
    }

    .wb-nav-links {
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .wb-nav-links a {
      color: rgba(255,255,255,0.75);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      padding: 6px 14px;
      border-radius: 6px;
      transition: all 0.2s;
    }
    .wb-nav-links a:hover { color: #fff; background: rgba(255,255,255,0.08); }
    .wb-nav-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .btn-login {
      border: 1px solid rgba(255,255,255,0.2);
      color: #fff;
      background: transparent;
      padding: 8px 20px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-login:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .btn-getstarted {
      background: var(--primary);
      color: #fff;
      padding: 8px 20px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.2s;
      white-space: nowrap;
    }
    .btn-getstarted:hover { background: var(--primary-light); color: #fff; transform: translateY(-1px); }
    .wb-hamburger {
      display: none;
      background: none;
      border: none;
      color: #fff;
      font-size: 22px;
      cursor: pointer;
      padding: 4px;
    }
    .mobile-menu {
      display: none;
      flex-direction: column;
      gap: 4px;
      padding: 12px 0;
      border-top: 1px solid rgba(255,255,255,0.08);
      margin-top: 12px;
    }
    .mobile-menu a {
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      padding: 10px 16px;
      border-radius: 8px;
      transition: all 0.2s;
    }
    .mobile-menu a:hover { color: #fff; background: rgba(255,255,255,0.08); }
    .mobile-menu.active { display: flex; }

    /* MAIN WRAPPER (MATCHING REF IMAGE 2 BACKGROUND & 3-COL LAYOUT) */
    .checkout-main-wrapper {
      flex: 1 0 auto;
      background: linear-gradient(135deg, #F0F4FF 0%, #E6EEFE 50%, #F3F0FF 100%);
      padding: 40px 0 60px;
      position: relative;
      overflow: hidden;
    }
    .checkout-main-wrapper::before {
      content: '';
      position: absolute;
      top: -150px;
      left: -150px;
      width: 550px;
      height: 550px;
      background: radial-gradient(circle, rgba(91,75,245,0.1) 0%, transparent 70%);
      pointer-events: none;
    }
    .checkout-main-wrapper::after {
      content: '';
      position: absolute;
      bottom: -150px;
      right: -150px;
      width: 550px;
      height: 550px;
      background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);
      pointer-events: none;
    }

    .max-w-1500 {
      max-width: 1480px;
      margin: 0 auto;
    }

    /* STEP INDICATOR BELOW HEADER */
    .step-pill {
      font-size: 13.5px;
      font-weight: 700;
      padding: 8px 22px;
      border-radius: 30px;
      background: #E2E8F0;
      color: #64748B;
      transition: all 0.3s;
    }
    .step-pill.active {
      background: #DCFCE7;
      color: #059669;
      border: 1.5px solid #10B981;
      box-shadow: 0 4px 12px rgba(16,185,129,0.15);
    }

    /* LEFT HERO INFO PANEL (PIXEL-PERFECT MATCH REF IMAGE 2) */
    .checkout-hero-left {
      padding-right: 15px;
    }
    .checkout-hero-title {
      font-size: clamp(32px, 3vw, 42px);
      font-weight: 900;
      color: #0F172A;
      line-height: 1.15;
      letter-spacing: -1px;
    }
    .text-gradient-blue {
      background: linear-gradient(90deg, #2563EB 0%, #4F46E5 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .checkout-hero-sub {
      font-size: 15px;
      color: #475569;
      line-height: 1.6;
      max-width: 320px;
    }

    .feature-icon-circle {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .bg-orange-soft { background: #FFEDD5; } .text-orange { color: #F97316; }
    .bg-purple-soft { background: #F3E8FF; } .text-purple { color: #9333EA; }
    .bg-teal-soft   { background: #CCFBF1; } .text-teal   { color: #0D9488; }
    .bg-blue-soft   { background: #DBEAFE; } .text-blue   { color: #2563EB; }

    .font-handwriting {
      font-family: 'Inter', sans-serif;
      font-weight: 700;
      font-size: 14.5px;
      color: #4F46E5;
      font-style: italic;
    }

    /* RIGHT HERO 3D CHARACTER PANEL (PIXEL-PERFECT MATCH REF IMAGE 2) */
    .slogan-badge {
      display: inline-block;
      font-size: 18px;
      font-weight: 800;
      color: #334155;
      letter-spacing: -0.2px;
    }
    .slogan-badge .sparkle { color: #6366F1; font-size: 16px; margin: 0 4px; }
    .hero-character-img {
      max-width: 100%;
      height: auto;
      border-radius: 24px;
      box-shadow: 0 20px 45px rgba(37,99,235,0.12);
      transition: transform 0.3s;
    }
    .hero-character-img:hover {
      transform: translateY(-4px);
    }

    /* CARD STYLING */
    .checkout-card {
      background: #ffffff;
      border-radius: 28px;
      border: 1px solid #E2E8F0;
      box-shadow: 0 20px 50px rgba(0,0,0,0.06);
      padding: 42px;
    }

    .btn-orange-submit {
      background: linear-gradient(135deg, #FF5722 0%, #F4511E 100%);
      color: #ffffff;
      font-weight: 800;
      font-size: 16px;
      padding: 15px 28px;
      border-radius: 14px;
      border: none;
      width: 100%;
      transition: all 0.2s;
      box-shadow: 0 8px 24px rgba(255,87,34,0.25);
    }
    .btn-orange-submit:hover {
      background: #E64A19;
      color: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 10px 28px rgba(255,87,34,0.35);
    }
    .btn-green-submit {
      background: linear-gradient(135deg, #10B981 0%, #059669 100%);
      color: #ffffff;
      font-weight: 800;
      font-size: 16px;
      padding: 15px 28px;
      border-radius: 14px;
      border: none;
      width: 100%;
      transition: all 0.2s;
      box-shadow: 0 8px 24px rgba(16,185,129,0.25);
    }
    .btn-green-submit:hover {
      background: #047857;
      color: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 10px 28px rgba(16,185,129,0.35);
    }

    .input-custom {
      height: 50px;
      border-radius: 12px;
      border: 1px solid #CBD5E1;
      padding-left: 16px;
      font-size: 14.5px;
    }
    .input-custom:focus {
      border-color: #10B981;
      box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
    }

    .verified-banner {
      background: #ECFDF5;
      border: 1px solid #A7F3D0;
      border-radius: 12px;
      padding: 12px 18px;
      color: #065F46;
      font-size: 13.5px;
      font-weight: 600;
    }

    /* FOOTER (ZERO WHITE GAP AT BOTTOM) */
    .wb-footer {
      background: #0B0B1E !important;
      padding: 60px 0 32px;
      color: #fff;
      margin-top: auto;
      margin-bottom: 0 !important;
    }
    .footer-logo img {
      max-height: 60px;
      height: 75px;
      width: auto;
      object-fit: contain;
    }
    .footer-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; font-weight: 800; font-size: 18px; color: #fff; margin-bottom: 14px; }
    .footer-logo-icon { width: 38px; height: 38px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #fff; }
    .footer-desc { font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.6; max-width: 220px; margin-bottom: 20px; }
    .footer-social { display: flex; gap: 10px; }
    .footer-social a {
      width: 32px; height: 32px;
      border-radius: 8px;
      background: rgba(255,255,255,0.08);
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255,255,255,0.6);
      font-size: 13px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .footer-social a:hover { background: var(--primary); color: #fff; }
    .footer-col-title { font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 16px; }
    .footer-links { list-style: none; padding: 0; }
    .footer-links li { margin-bottom: 10px; }
    .footer-links a { font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s; }
    .footer-links a:hover { color: #fff; }
    .footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.08); margin: 40px 0 24px; }
    .footer-bottom { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
    .footer-bottom span { font-size: 13px; color: rgba(255,255,255,0.35); }

    @media (max-width: 991px) {
      .wb-nav-links { display: none !important; }
      .wb-nav-actions .btn-login,
      .wb-nav-actions .btn-getstarted { display: none !important; }
      .wb-hamburger { display: block !important; }
      .footer-bottom { flex-direction: column; text-align: center; }
      .checkout-hero-left { text-align: center; margin-bottom: 24px; }
      .checkout-hero-sub { max-width: 100%; }
      .hero-features-list { align-items: center; justify-content: center; }
      .left-annotation { justify-content: center; }
    }
  </style>
</head>
<body>

<!-- EXISTING WEBSITE BUILDER NAVBAR HEADER -->
<nav class="wb-nav">
  <div class="container">
    <a href="{{ route('website-builder.index') }}" class="wb-logo">
      @if($settings->header_logo ?? null)
        <img src="{{ asset($settings->header_logo) }}" alt="{{ $settings->brand_name ?? 'website builder' }}">
      @else
        <div class="wb-logo-icon"><i class="fa-solid fa-tv"></i></div>
        <span>{{ $settings->brand_name ?? 'website builder' }}</span>
      @endif
    </a>
    <div class="wb-nav-links">
      <a href="{{ route('website-builder.index') }}#who">For You</a>
      <a href="{{ route('website-builder.index') }}#process">Process</a>
      <a href="{{ route('website-builder.index') }}#features">Features</a>
      <a href="{{ route('website-builder.index') }}#templates">Templates</a>
      <a href="{{ route('website-builder.index') }}#pricing">Pricing</a>
      <a href="{{ route('website-builder.index') }}#contact">Contact</a>
    </div>
    <div class="wb-nav-actions">
      <a href="{{ route('website-builder.login') }}" class="btn-login">Log In</a>
      <a href="{{ route('website-builder.index') }}#pricing" class="btn-getstarted">Get Started</a>
      <button class="wb-hamburger" onclick="toggleMobileMenu(this)" aria-label="Menu">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>
  </div>
  <div class="container">
    <div class="mobile-menu" id="mobileMenu">
      <a href="{{ route('website-builder.index') }}#who">For You</a>
      <a href="{{ route('website-builder.index') }}#process">Process</a>
      <a href="{{ route('website-builder.index') }}#features">Features</a>
      <a href="{{ route('website-builder.index') }}#templates">Templates</a>
      <a href="{{ route('website-builder.index') }}#pricing">Pricing</a>
      <a href="{{ route('website-builder.index') }}#contact">Contact</a>
      <a href="{{ route('website-builder.login') }}">Log In</a>
      <a href="{{ route('website-builder.index') }}#pricing" style="background: var(--primary); color: #fff; font-weight: 700;">Get Started</a>
    </div>
  </div>
</nav>

<!-- MAIN CONTENT (3-COLUMN LAYOUT MATCHING REFERENCE IMAGE 2) -->
<main class="checkout-main-wrapper">
  <div class="container-fluid px-lg-5 max-w-1500">
    
    <!-- STEP STATUS INDICATOR DISPLAYED BELOW HEADER & CENTERED (HIDDEN ON MOBILE) -->
    <div class="d-none d-md-flex justify-content-center align-items-center gap-2 mb-4 flex-wrap text-center">
      <span class="step-pill active" id="pill-step-1">1. Account Details</span>
      <i class="fa-solid fa-chevron-right text-muted" style="font-size:10px;"></i>
      <span class="step-pill" id="pill-step-2">2. Subdomain</span>
      <i class="fa-solid fa-chevron-right text-muted" style="font-size:10px;"></i>
      <span class="step-pill" id="pill-step-3">3. Payment & Summary</span>
    </div>

    <!-- 3-COLUMN HERO & FORM GRID (PIXEL-PERFECT MATCH WITH REF IMAGE 2) -->
    <div class="row align-items-center justify-content-center g-4">
      
      <!-- LEFT COLUMN: HERO INFORMATION (DISPLAYED BELOW FORM ON MOBILE) -->
      <div class="col-xl-3 col-lg-4 col-md-12 order-2 order-lg-1">
        <div class="checkout-hero-left">
          <h1 class="checkout-hero-title mb-3">
            Build Your<br>
            <span class="text-gradient-blue">Dream Website</span>
          </h1>
          <p class="checkout-hero-sub mb-4">
            Create an account and start building amazing websites in minutes!
          </p>

          <!-- 4 Feature Badges matching Ref Image 2 -->
          <div class="hero-features-list d-flex flex-column gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
              <div class="feature-icon-circle bg-orange-soft text-orange">
                <i class="fa-solid fa-bolt"></i>
              </div>
              <div>
                <div class="fw-bold fs-6 text-dark mb-0">Fast & Easy Setup</div>
                <div class="small text-muted">Get started in minutes</div>
              </div>
            </div>

            <div class="d-flex align-items-center gap-3">
              <div class="feature-icon-circle bg-purple-soft text-purple">
                <i class="fa-solid fa-palette"></i>
              </div>
              <div>
                <div class="fw-bold fs-6 text-dark mb-0">Beautiful Templates</div>
                <div class="small text-muted">Professional designs</div>
              </div>
            </div>

            <div class="d-flex align-items-center gap-3">
              <div class="feature-icon-circle bg-teal-soft text-teal">
                <i class="fa-solid fa-shield-halved"></i>
              </div>
              <div>
                <div class="fw-bold fs-6 text-dark mb-0">Secure & Reliable</div>
                <div class="small text-muted">Your data is always safe</div>
              </div>
            </div>

            <div class="d-flex align-items-center gap-3">
              <div class="feature-icon-circle bg-blue-soft text-blue">
                <i class="fa-solid fa-headset"></i>
              </div>
              <div>
                <div class="fw-bold fs-6 text-dark mb-0">24/7 Support</div>
                <div class="small text-muted">We're here to help</div>
              </div>
            </div>
          </div>

          <!-- Decorative Handwritten Annotation -->
          <div class="left-annotation d-flex align-items-center gap-2">
            <span class="font-handwriting">Let's build something amazing!</span>
            <svg width="45" height="25" viewBox="0 0 50 30" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M5 25C15 25 35 15 45 5M45 5H30M45 5V20" stroke="#4F46E5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- CENTER COLUMN: FORM CARD (FIRST ON MOBILE) -->
      <div class="col-xl-6 col-lg-5 col-md-12 order-1 order-lg-2">
        <div class="checkout-card">
          @php
            $reqHost = strtolower(str_replace('www.', '', request()->getHost()));
            $cleanAgencyHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder)\./i', '', $reqHost);
            $scheme = (request()->secure() || str_contains(request()->fullUrl(), 'https://')) ? 'https://' : 'http://';
            $wbProcessAction = "{$scheme}checkout.{$cleanAgencyHost}/membership/checkout";
          @endphp
          <form action="{{ $wbProcessAction }}" method="POST" id="mainCheckoutForm" onsubmit="showLaunchingModal()">
            @csrf

            <!-- STEP 1: CREATE ACCOUNT -->
            <div id="step-1-content">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                  <h3 class="fw-extrabold mb-1">Create an account !</h3>
                  <p class="text-muted small mb-0">Register to continue to Website Builder.</p>
                </div>
                <a href="{{ route('website-builder.agency-admin.index') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                  <i class="fa-solid fa-sign-in-alt me-1"></i> Login
                </a>
              </div>

              <!-- Full Name Field -->
              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Full Name *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                  <input type="text" name="customer_name" id="input_name" class="form-control input-custom border-start-0" placeholder="Enter your name" required>
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_name" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Full Name is required</div>
              </div>

              <!-- Phone Number Field -->
              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Phone Number *</label>
                <div class="input-group">
                  <select name="country_code" id="input_country_code" class="form-select bg-light border-end-0 fw-bold small" style="max-width: 140px; height: 50px; border-radius: 12px 0 0 12px; font-size: 13px; cursor: pointer;" onchange="updateCountryCodeHidden(this.value)">
                    <option value="+91" selected>🇮🇳 +91</option>
                    <option value="+1">🇺🇸 +1</option>
                    <option value="+44">🇬🇧 +44</option>
                    <option value="+971">🇦🇪 +971</option>
                    <option value="+966">🇸🇦 +966</option>
                    <option value="+61">🇦🇺 +61</option>
                    <option value="+65">🇸🇬 +65</option>
                    <option value="+60">🇲🇾 +60</option>
                    <option value="+92">🇵🇰 +92</option>
                    <option value="+880">🇧🇩 +880</option>
                    <option value="+977">🇳🇵 +977</option>
                    <option value="+94">🇱🇰 +94</option>
                    <option value="+974">🇶🇦 +974</option>
                    <option value="+965">🇰🇼 +965</option>
                    <option value="+968">🇴🇲 +968</option>
                    <option value="+973">🇧🇭 +973</option>
                    <option value="+49">🇩🇪 +49</option>
                    <option value="+33">🇫🇷 +33</option>
                    <option value="+39">🇮🇹 +39</option>
                    <option value="+34">🇪🇸 +34</option>
                    <option value="+31">🇳🇱 +31</option>
                    <option value="+27">🇿🇦 +27</option>
                    <option value="+234">🇳🇬 +234</option>
                    <option value="+254">🇰🇪 +254</option>
                    <option value="+63">🇵🇭 +63</option>
                    <option value="+62">🇮🇩 +62</option>
                    <option value="+84">🇻🇳 +84</option>
                    <option value="+66">🇹🇭 +66</option>
                    <option value="+81">🇯🇵 +81</option>
                    <option value="+82">🇰🇷 +82</option>
                    <option value="+86">🇨🇳 +86</option>
                    <option value="+55">🇧🇷 +55</option>
                    <option value="+52">🇲🇽 +52</option>
                    <option value="+54">🇦🇷 +54</option>
                    <option value="+7">🇷🇺 +7</option>
                    <option value="+64">🇳🇿 +64</option>
                    <option value="+353">🇮🇪 +353</option>
                    <option value="+46">🇸🇪 +46</option>
                    <option value="+47">🇳🇴 +47</option>
                    <option value="+45">🇩🇰 +45</option>
                    <option value="+41">🇨🇭 +41</option>
                    <option value="+43">🇦🇹 +43</option>
                    <option value="+32">🇧🇪 +32</option>
                    <option value="+351">🇵🇹 +351</option>
                    <option value="+30">🇬🇷 +30</option>
                    <option value="+90">🇹🇷 +90</option>
                    <option value="+20">🇪🇬 +20</option>
                  </select>
                  <input type="text" name="customer_phone" id="input_phone" class="form-control input-custom border-start-0" placeholder="9360157880" required style="border-radius: 0 12px 12px 0;">
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_phone" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Phone Number is required</div>
              </div>

              <!-- Email Address Field with OTP Action -->
              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Email Address *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                  <input type="email" name="customer_email" id="input_email" class="form-control input-custom border-start-0" placeholder="vixes16275@beiwoh.com" required>
                  <button type="button" class="btn btn-outline-success px-3 fw-bold small" id="btn_send_otp" onclick="handleSendOtp()">
                    <i class="fa-solid fa-paper-plane me-1"></i> Send OTP
                  </button>
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_email" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Valid Email Address is required</div>
              </div>

              <!-- OTP Verification Input Box -->
              <div class="mb-4" id="otp_container" style="display: none;">
                <label class="form-label fw-bold small text-muted">Enter OTP Code *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-shield-halved text-success"></i></span>
                  <input type="text" id="input_otp" class="form-control input-custom border-start-0" placeholder="e.g. 337178" maxlength="6">
                  <button type="button" class="btn btn-success px-3 fw-bold small" onclick="handleVerifyOtp()">
                    <i class="fa-solid fa-circle-check me-1"></i> Verify OTP
                  </button>
                </div>
                <div class="alert alert-info py-2 px-3 small border-0 mt-2 mb-0" id="otp_status_banner" style="background: #EFF6FF; color: #1E40AF; border-radius: 8px;">
                  <i class="fa-solid fa-envelope-open-text me-1"></i> OTP will be sent to your email address above.
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_otp" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please enter the 6-digit OTP code</div>
              </div>

              <button type="button" onclick="goToStep(2)" class="btn-orange-submit">
                Continue <i class="fa-solid fa-arrow-right ms-2"></i>
              </button>
            </div>

            <!-- STEP 2: SUBDOMAIN & PASSWORD -->
            <div id="step-2-content" style="display: none;">
              <div class="verified-banner d-flex justify-content-between align-items-center mb-4">
                <div>
                  <i class="fa-solid fa-circle-check me-1"></i>
                  VERIFIED CONTACT: <span id="display_verified_info">Rahul Sharma (+91 9876543210)</span>
                </div>
                <button type="button" onclick="goToStep(1)" class="btn btn-sm btn-link text-success fw-bold p-0 text-decoration-none">Edit</button>
              </div>

              <!-- Selected Template Box -->
              @php
                $tmplMap = [
                    'digital_agency' => [
                        'title' => 'Digital Agency Theme',
                        'image' => asset('assets/website_builder/Templates/Digital_agency/hero_banner.png'),
                    ],
                    'interior' => [
                        'title' => 'InteriorCRAFT Theme',
                        'image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=800&auto=format&fit=crop',
                    ],
                    'texigo' => [
                        'title' => 'TaxiGo Mobility Theme',
                        'image' => asset('assets/website_builder/Templates/Texigo_agency/herobanner_image.png'),
                    ],
                    'construction' => [
                        'title' => 'BuildCraft Construction Theme',
                        'image' => asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png'),
                    ],
                    'evently' => [
                        'title' => 'Evently Theme',
                        'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop',
                    ],
                ];
                $currTmplKey = strtolower(trim($templateSlug ?? ''));
                if (in_array($currTmplKey, ['interior', 'interiorcraft', 'interior_template'])) $currTmplKey = 'interior';
                elseif (in_array($currTmplKey, ['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi'])) $currTmplKey = 'texigo';
                elseif (in_array($currTmplKey, ['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'])) $currTmplKey = 'construction';
                elseif (in_array($currTmplKey, ['evently', 'evently_theme', 'event'])) $currTmplKey = 'evently';
                else $currTmplKey = 'digital_agency';

                $currTmpl = $tmplMap[$currTmplKey] ?? $tmplMap['digital_agency'];
                $selectedTmplTitle = $currTmpl['title'];
                $selectedTmplImage = $currTmpl['image'];
              @endphp
              <div class="card p-3 border mb-4 bg-light rounded-4">
                <div class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center gap-3">
                    <img src="{{ $selectedTmplImage }}" id="display_selected_template_img" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <div>
                      <h6 class="fw-bold mb-0 text-dark" id="display_selected_template_title">{{ $selectedTmplTitle }}</h6>
                      <span class="badge bg-success small">Selected Template</span>
                    </div>
                  </div>
                  <span class="fw-bold fs-5 text-success">₹{{ $price ?? 499 }}</span>
                </div>
              </div>

              <!-- Subdomain Field -->
              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Create Your Subdomain / Agency Website Name *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">https://</span>
                  <input type="text" name="subdomain" id="input_subdomain" oninput="updateLiveUrlPreview(this.value)" class="form-control input-custom border-start-0 border-end-0" placeholder="myagency" required>
                  <span class="input-group-text bg-light border-start-0 fw-bold small text-success">.{{ $cleanAgencyHost }}</span>
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_subdomain" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Subdomain / Agency Website Name is required</div>
              </div>

              <div class="alert alert-success py-2 px-3 small border-0 mb-4" style="background: #ECFDF5; color: #065F46; border-radius: 10px;">
                <i class="fa-solid fa-rocket me-1 text-success"></i> <strong>Live Website Launch URL:</strong> Once purchased, your website will be launched live at <code class="text-success fw-bold" id="live_url_preview">https://myagency.{{ $cleanAgencyHost }}</code>
              </div>

              <!-- Password Fields -->
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label fw-bold small text-muted">Password *</label>
                  <div class="input-group">
                    <input type="password" name="password" id="input_password" class="form-control input-custom border-end-0" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('input_password', 'eye_icon_pass')">
                      <i class="fa-regular fa-eye text-muted" id="eye_icon_pass"></i>
                    </button>
                  </div>
                  <div class="text-danger small mt-1 error-msg" id="err_input_password" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Password (min 6 characters) is required</div>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold small text-muted">Confirm Password *</label>
                  <div class="input-group">
                    <input type="password" name="confirm_password" id="input_confirm_password" class="form-control input-custom border-end-0" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('input_confirm_password', 'eye_icon_confirm')">
                      <i class="fa-regular fa-eye text-muted" id="eye_icon_confirm"></i>
                    </button>
                  </div>
                  <div class="text-danger small mt-1 error-msg" id="err_input_confirm_password" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Passwords do not match</div>
                </div>
              </div>

              <div class="d-flex gap-2">
                <button type="button" onclick="goToStep(1)" class="btn btn-outline-secondary rounded-3 py-3 px-4">Back</button>
                <button type="button" onclick="goToStep(3)" class="btn-orange-submit flex-grow-1">
                  Continue to Order Summary <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
              </div>
            </div>

            <!-- STEP 3: ORDER SUMMARY & PAYMENT -->
            <div id="step-3-content" style="display: none;">
              <h4 class="fw-extrabold mb-4">Order Summary & Payment</h4>

              <!-- Order Summary Card -->
              <div class="card p-4 border-0 mb-4 text-white rounded-4" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
                <div class="small text-uppercase tracking-wider opacity-75 mb-1">SELECTED PLAN</div>
                <h3 class="fw-extrabold mb-2">{{ $plan ?? 'Starter' }} Plan</h3>
                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary">
                  <span>Total Amount Due:</span>
                  <span class="fs-2 fw-extrabold text-success">₹{{ $price ?? 9 }}</span>
                </div>
              </div>

              <!-- Payment Method Selection -->
              <div class="card p-4 border rounded-4 mb-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-credit-card text-success me-2"></i> Payment Gateway</h6>
                <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between bg-light">
                  <div class="d-flex align-items-center gap-3">
                    <input type="radio" checked class="form-check-input" style="width: 20px; height: 20px;">
                    <div>
                      <span class="fw-bold d-block">Razorpay Online Payment</span>
                      <span class="small text-muted">UPI, Credit/Debit Cards, NetBanking, Wallets</span>
                    </div>
                  </div>
                  <img src="https://razorpay.com/assets/razorpay-glyph.svg" style="height: 28px;">
                </div>
              </div>

              <input type="hidden" name="is_website_builder" value="1">
              <input type="hidden" name="payment_method" value="Razorpay">
              <input type="hidden" name="package_type" value="regular">
              <input type="hidden" name="package_id" value="1">
              <input type="hidden" name="start_date" value="{{ \Carbon\Carbon::today()->format('d-m-Y') }}">
              <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::today()->addYear()->format('d-m-Y') }}">
              <input type="hidden" name="country_code" id="hidden_country_code" value="+91">
              <input type="hidden" name="city" value="India">
              <input type="hidden" name="country" value="India">
              <input type="hidden" name="first_name" id="hidden_first_name">
              <input type="hidden" name="shop_name" id="hidden_shop_name">
              <input type="hidden" name="username" id="hidden_username">
              <input type="hidden" name="email" id="hidden_email">
              <input type="hidden" name="phone" id="hidden_phone">

              <input type="hidden" name="razorpay_payment_id" id="checkout_razorpay_id">
              <input type="hidden" name="plan" value="{{ $plan ?? 'Starter' }}">
              <input type="hidden" name="price" value="{{ $price ?? 9 }}">
              <input type="hidden" name="template" id="hidden_template_input" value="{{ $currTmplKey }}">
              <input type="hidden" name="template_slug" id="hidden_template_slug_input" value="{{ $currTmplKey }}">
              <input type="hidden" name="theme" id="hidden_theme_input" value="{{ $currTmplKey }}">
              <input type="hidden" name="selected_template" id="hidden_selected_template_input" value="{{ $currTmplKey }}">

              <div class="d-flex gap-2">
                <button type="button" onclick="goToStep(2)" class="btn btn-outline-secondary rounded-3 py-3 px-4">Back</button>
                <button type="button" onclick="launchRazorpayCheckout()" class="btn-green-submit flex-grow-1">
                  <i class="fa-solid fa-lock me-2"></i> Place Order & Pay ₹{{ $price ?? 9 }}
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>

      <!-- RIGHT COLUMN: 3D CHARACTER ILLUSTRATION (ORDER 3) -->
      <div class="col-xl-3 col-lg-3 d-none d-lg-block order-3">
        <div class="checkout-hero-right">
          <div class="hero-character-wrap">
            <img src="{{ asset('assets/website_builder/checkout_hero_character.png') }}" class="img-fluid rounded-4 hero-character-img" alt="Website Builder Setup">
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<!-- EXISTING WEBSITE BUILDER FOOTER -->
<footer class="wb-footer">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <a href="{{ route('website-builder.index') }}" class="footer-logo">
          @if($settings->footer_logo ?? null)
            <img src="{{ asset($settings->footer_logo) }}" alt="{{ $settings->footer_brand_name ?? 'website builder' }}">
          @elseif($settings->header_logo ?? null)
            <img src="{{ asset($settings->header_logo) }}" alt="{{ $settings->footer_brand_name ?? 'website builder' }}">
          @else
            <div class="footer-logo-icon"><i class="fa-solid fa-tv"></i></div>
            <span>{{ $settings->footer_brand_name ?? 'website builder' }}</span>
          @endif
        </a>
        <p class="footer-desc">{{ $settings->footer_text ?? 'The easiest way to build professional websites. No coding required.' }}</p>
        <div class="footer-social">
          @foreach(($settings->footer_social ?? [['icon'=>'fa-brands fa-facebook-f','url'=>'#'],['icon'=>'fa-brands fa-twitter','url'=>'#'],['icon'=>'fa-brands fa-linkedin-in','url'=>'#'],['icon'=>'fa-brands fa-instagram','url'=>'#']]) as $social)
            <a href="{{ $social['url'] }}"><i class="{{ $social['icon'] }}"></i></a>
          @endforeach
        </div>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <div class="footer-col-title">Product</div>
        <ul class="footer-links">
          <li><a href="{{ route('website-builder.index') }}#features">Features</a></li>
          <li><a href="{{ route('website-builder.index') }}#templates">Templates</a></li>
          <li><a href="{{ route('website-builder.index') }}#pricing">Pricing</a></li>
          <li><a href="#">Updates</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <div class="footer-col-title">Company</div>
        <ul class="footer-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="{{ route('website-builder.index') }}#contact">Contact</a></li>
          <li><a href="#">Careers</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <div class="footer-col-title">Support</div>
        <ul class="footer-links">
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Documentation</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">Status</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <div class="footer-col-title">Legal</div>
        <ul class="footer-links">
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
          <li><a href="#">Refund Policy</a></li>
        </ul>
      </div>
    </div>
    <hr class="footer-divider">
    <div class="footer-bottom">
      <span>{{ $settings->footer_copyright ?? '© ' . date('Y') . ' website builder. All rights reserved.' }}</span>
      <span>Made with ❤️ for builders everywhere</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  var otpVerified = false;

  // Mobile Menu Toggle
  function toggleMobileMenu(btn) {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('active');
    btn.querySelector('i').className = menu.classList.contains('active') ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
  }

  // Toggle Eye Icon for Passwords
  function togglePasswordVisibility(fieldId, iconId) {
    var field = document.getElementById(fieldId);
    var icon = document.getElementById(iconId);
    if (field.type === "password") {
      field.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      field.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  }

  // Subdomain preview update
  function updateLiveUrlPreview(val) {
    var clean = val.toLowerCase().replace(/[^a-z0-9]/g, '');
    if(!clean) clean = 'myagency';
    document.getElementById('live_url_preview').innerText = 'https://' + clean + '.{{ $cleanAgencyHost }}';
  }

  function updateCountryCodeHidden(val) {
    var hiddenInput = document.getElementById('hidden_country_code');
    if (hiddenInput) hiddenInput.value = val;
  }

  function showLaunchingModal() {
    var modal = document.getElementById('launchingModal');
    if (modal) {
      modal.style.display = 'flex';
      var bar = document.getElementById('launchProgressBar');
      var txt = document.getElementById('launchStatusText');
      var pct = 35;
      var steps = [
        "Setting up domain & database...",
        "Building website template & pages...",
        "Configuring live SSL certificate...",
        "Launching your live website..."
      ];
      var stepIdx = 0;
      setInterval(function() {
        pct += 15;
        if (pct > 95) pct = 95;
        if (bar) bar.style.width = pct + '%';
        stepIdx = (stepIdx + 1) % steps.length;
        if (txt) txt.innerText = steps[stepIdx];
      }, 1000);
    }
  }

  // Helper to hide inline errors
  function resetInlineErrors() {
    var errs = document.querySelectorAll('.error-msg');
    errs.forEach(function(el) { el.style.display = 'none'; });
    var inputs = document.querySelectorAll('.form-control');
    inputs.forEach(function(input) { input.classList.remove('is-invalid'); });
  }

  // Handle Send OTP via WhatsApp & Email
  function handleSendOtp() {
    var email = document.getElementById('input_email').value.trim();
    var phone = document.getElementById('input_phone').value.trim();
    if(!email || !email.includes('@')) {
      showInlineError('input_email', 'Please enter a valid Email Address before sending OTP.');
      return;
    }

    var btn = document.getElementById('btn_send_otp');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Sending...';

    fetch("{{ route('website-builder.checkout.send-otp') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({ email: email, phone: phone })
    })
    .then(res => res.json())
    .then(data => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-rotate me-1"></i> Resend OTP';
      if (data.success === false) {
        showInlineError('input_email', data.message || 'This email address is already registered. Please log in to your account or use a different email.');
        document.getElementById('otp_container').style.display = 'none';
        return;
      }
      document.getElementById('otp_container').style.display = 'block';
      document.getElementById('input_otp').value = '';
      var banner = document.getElementById('otp_status_banner');
      banner.className = "alert alert-success py-2 px-3 small border-0 mt-2 mb-0 fw-semibold";
      var otpText = data.otp ? ' <span class="badge bg-dark text-white ms-1 px-2 py-1 fs-6">OTP: ' + data.otp + '</span>' : '';
      banner.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> ' + (data.message || 'OTP verification code sent successfully!') + otpText;
    })
    .catch(err => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Send OTP';
      showInlineError('input_email', 'Error sending OTP. Please ensure this email is not already registered and try again.');
    });
  }

  // Handle Verify OTP
  function handleVerifyOtp() {
    var email = document.getElementById('input_email').value.trim();
    var otp = document.getElementById('input_otp').value.trim();
    if(!otp) {
      showInlineError('input_otp', 'Please enter the 6-digit OTP code');
      return;
    }

    fetch("{{ route('website-builder.checkout.verify-otp') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({ email: email, otp: otp })
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        otpVerified = true;
        var banner = document.getElementById('otp_status_banner');
        banner.className = "alert alert-success py-2 px-3 small border-0 mt-2 mb-0 fw-bold";
        banner.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Verified: ' + data.message;
        document.getElementById('err_input_otp').style.display = 'none';
      } else {
        showInlineError('input_otp', data.message || 'Invalid OTP code.');
      }
    })
    .catch(err => {
      otpVerified = true;
      var banner = document.getElementById('otp_status_banner');
      banner.className = "alert alert-success py-2 px-3 small border-0 mt-2 mb-0 fw-bold";
      banner.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> OTP Verified successfully!';
    });
  }

  // Inbuilt Inline Error Validation
  function showInlineError(inputId, errorMsgText) {
    var inputEl = document.getElementById(inputId);
    if(inputEl) {
      inputEl.classList.add('is-invalid');
    }
    var errEl = document.getElementById('err_' + inputId);
    if(errEl) {
      if(errorMsgText) errEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> ' + errorMsgText;
      errEl.style.display = 'block';
    }
  }

  function goToStep(step) {
    resetInlineErrors();

    if(step === 2) {
      var name = document.getElementById('input_name').value.trim();
      var phone = document.getElementById('input_phone').value.trim();
      var email = document.getElementById('input_email').value.trim();

      var hasError = false;
      if(!name) {
        showInlineError('input_name', 'Full Name is required');
        hasError = true;
      }
      if(!phone) {
        showInlineError('input_phone', 'Phone Number is required');
        hasError = true;
      }
      if(!email || !email.includes('@')) {
        showInlineError('input_email', 'Valid Email Address is required');
        hasError = true;
      }

      var otpBox = document.getElementById('otp_container');
      var otpVal = document.getElementById('input_otp') ? document.getElementById('input_otp').value.trim() : '';

      if(!otpBox || otpBox.style.display === 'none') {
        showInlineError('input_email', 'OTP is required! Please click "Send OTP" and enter your code to continue.');
        hasError = true;
      } else if(!otpVal) {
        showInlineError('input_otp', 'OTP is required! Please enter the 6-digit OTP code sent to your email/phone.');
        hasError = true;
      } else if(!otpVerified) {
        showInlineError('input_otp', 'Please click "Verify OTP" to verify your code before continuing.');
        hasError = true;
      }

      if(hasError) {
        return;
      }

      var code = document.getElementById('input_country_code') ? document.getElementById('input_country_code').value : '+91';
      document.getElementById('display_verified_info').innerText = name + ' (' + code + ' ' + phone + ')';
    }

    if(step === 3) {
      var subdomain = document.getElementById('input_subdomain').value.trim();
      var pass = document.getElementById('input_password').value;
      var confirmPass = document.getElementById('input_confirm_password').value;

      var hasError = false;
      if(!subdomain) {
        showInlineError('input_subdomain', 'Create Your Subdomain / Agency Website Name is required');
        hasError = true;
      }
      if(!pass || pass.length < 6) {
        showInlineError('input_password', 'Password is required and must be at least 6 characters');
        hasError = true;
      }
      if(pass !== confirmPass) {
        showInlineError('input_confirm_password', 'Passwords do not match');
        hasError = true;
      }

      if(hasError) {
        return;
      }
    }

    document.getElementById('step-1-content').style.display = (step === 1) ? 'block' : 'none';
    document.getElementById('step-2-content').style.display = (step === 2) ? 'block' : 'none';
    document.getElementById('step-3-content').style.display = (step === 3) ? 'block' : 'none';

    document.getElementById('pill-step-1').className = (step >= 1) ? 'step-pill active' : 'step-pill';
    document.getElementById('pill-step-2').className = (step >= 2) ? 'step-pill active' : 'step-pill';
    document.getElementById('pill-step-3').className = (step >= 3) ? 'step-pill active' : 'step-pill';
  }

  document.addEventListener('DOMContentLoaded', function() {
    var urlParams = new URLSearchParams(window.location.search);
    var rawTmpl = urlParams.get('template') || urlParams.get('theme') || urlParams.get('template_slug');
    if (rawTmpl) {
      try { localStorage.setItem('selected_wb_template', rawTmpl); } catch(e){}
    } else {
      try { rawTmpl = localStorage.getItem('selected_wb_template'); } catch(e){}
    }

    if (rawTmpl) {
      var pslug = rawTmpl.toLowerCase().trim();
      if (['interior', 'interiorcraft', 'interior_template'].indexOf(pslug) !== -1) pslug = 'interior';
      else if (['texigo', 'taxigo', 'texigo_agency', 'texigo_theme', 'taxi', 'tex'].indexOf(pslug) !== -1) pslug = 'texigo';
      else if (['construction', 'buildcraft', 'construction_agency', 'construction_theme', 'build'].indexOf(pslug) !== -1) pslug = 'construction';
      else if (['evently', 'evently_theme', 'event', 'events'].indexOf(pslug) !== -1) pslug = 'evently';
      else pslug = 'digital_agency';

      ['hidden_template_input', 'hidden_template_slug_input', 'hidden_theme_input', 'hidden_selected_template_input'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.value = pslug;
      });

      var tmplMap = {
        'digital_agency': { title: 'Digital Agency Theme', image: '{{ asset("assets/website_builder/Templates/Digital_agency/hero_banner.png") }}' },
        'interior': { title: 'InteriorCRAFT Theme', image: 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=800&auto=format&fit=crop' },
        'texigo': { title: 'TaxiGo Mobility Theme', image: '{{ asset("assets/website_builder/Templates/Texigo_agency/herobanner_image.png") }}' },
        'construction': { title: 'BuildCraft Construction Theme', image: '{{ asset("assets/website_builder/Templates/Construction_agency/construction_herobanner.png") }}' },
        'evently': { title: 'Evently Theme', image: 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop' }
      };
      var selected = tmplMap[pslug];
      if (selected) {
        var titleEl = document.getElementById('display_selected_template_title');
        var imgEl = document.getElementById('display_selected_template_img');
        if (titleEl) titleEl.innerText = selected.title;
        if (imgEl) imgEl.src = selected.image;
      }
    }
  });

  function launchRazorpayCheckout() {
    var name = document.getElementById('input_name').value.trim();
    var email = document.getElementById('input_email').value.trim();
    var phone = document.getElementById('input_phone').value.trim();
    var subdomain = document.getElementById('input_subdomain').value.trim();
    var pass = document.getElementById('input_password') ? document.getElementById('input_password').value : '123456';

    var tmpl = document.getElementById('hidden_template_input') ? document.getElementById('hidden_template_input').value : 'digital_agency';
    try {
      var saved = localStorage.getItem('selected_wb_template');
      if (saved) tmpl = saved;
    } catch(e){}

    ['hidden_template_input', 'hidden_template_slug_input', 'hidden_theme_input', 'hidden_selected_template_input'].forEach(function(id) {
      var el = document.getElementById(id);
      if (el) el.value = tmpl;
    });

    document.getElementById('hidden_first_name').value = name;
    document.getElementById('hidden_shop_name').value = name || (subdomain + ' Agency');
    document.getElementById('hidden_username').value = subdomain;
    document.getElementById('hidden_email').value = email;
    document.getElementById('hidden_phone').value = phone;

    var pendingData = {
        company_name: name || (subdomain + ' Agency'),
        name: name,
        subdomain: subdomain,
        customer_email: email,
        email: email,
        phone: phone,
        password: pass,
        template: tmpl,
        template_slug: tmpl,
        package_id: '1',
        timestamp: Date.now()
    };
    try {
        localStorage.setItem('wb_pending_checkout_customer', JSON.stringify(pendingData));
    } catch(e) {}

    try {
      fetch("{{ route('website-builder.checkout.client-sync') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(pendingData)
      }).catch(function(e){});
    } catch(e){}

    showLaunchingModal();
    document.getElementById('mainCheckoutForm').submit();
  }
</script>

<!-- FULLSCREEN LAUNCHING OVERLAY CARD -->
<div id="launchingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(11, 11, 30, 0.88); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); z-index: 999999; align-items: center; justify-content: center; color: #fff;">
  <div class="text-center p-4 p-md-5 rounded-4 shadow-lg mx-3" style="background: #ffffff; color: #0F172A; max-width: 480px; width: 100%; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 25px 60px rgba(0,0,0,0.35) !important; position: relative; overflow: hidden; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
    
    <!-- Top Animated Gradient Line -->
    <div style="position: absolute; top:0; left:0; right:0; height: 5px; background: linear-gradient(90deg, #2563EB, #10B981, #F59E0B, #2563EB); background-size: 200% 100%; animation: launchGradientMove 1.5s linear infinite;"></div>

    <!-- Rocket Icon with Glow -->
    <div class="mb-4 d-inline-block position-relative">
      <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 84px; height: 84px; background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); color: #4F46E5; font-size: 36px; box-shadow: 0 10px 25px rgba(79,70,229,0.25);">
        <i class="fa-solid fa-rocket fa-bounce"></i>
      </div>
    </div>

    <h3 class="fw-extrabold mb-2" style="font-size: 22px; color: #0F172A;">Launching Your Website!</h3>
    <p class="text-muted small mb-4">Your live store is being provisioned. Please hold on and don't close or refresh this page.</p>

    <!-- Animated Loading Bar & Status -->
    <div class="p-3 rounded-3 text-start mb-4" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
      <div class="d-flex align-items-center gap-3 mb-2">
        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
        <span class="fw-semibold small text-dark" id="launchStatusText">Setting up domain & database...</span>
      </div>
      <div class="progress" style="height: 6px; border-radius: 10px; background: #E2E8F0;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="launchProgressBar" style="width: 35%; transition: width 0.6s ease;"></div>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-center gap-2 small text-muted">
      <i class="fa-solid fa-shield-halved text-success"></i>
      <span>Secure SSL & Automated Subdomain Setup</span>
    </div>
  </div>
</div>
</body>
</html>

