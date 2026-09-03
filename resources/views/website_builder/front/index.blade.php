<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $settings->hero_title }} - Website Builder</title>
  <meta name="description" content="{{ $settings->hero_subtitle }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

  <style>
    :root {
      --brand-primary: {{ $settings->primary_color ?? '#6366f1' }};
      --brand-secondary: {{ $settings->secondary_color ?? '#8b5cf6' }};
      --bg-dark-hero: #0b0f19;
      --bg-light-body: #f8fafc;
      --card-bg: #ffffff;
      --text-dark: #0f172a;
      --text-muted: #64748b;
      --border-color: #e2e8f0;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--bg-light-body);
      color: var(--text-dark);
      overflow-x: hidden;
    }

    /* Navbar */
    .wb-navbar {
      background: rgba(11, 15, 25, 0.92);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding: 16px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .wb-brand {
      font-weight: 800;
      font-size: 20px;
      color: #ffffff;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .wb-brand-icon {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      color: #fff;
    }
    .nav-link {
      color: #94a3b8;
      font-weight: 500;
      font-size: 15px;
      margin: 0 14px;
      transition: color 0.2s ease;
    }
    .nav-link:hover {
      color: #ffffff;
    }

    /* Hero Section - Matching Reference Image 2 Dark Header Glow */
    .hero-section {
      background: radial-gradient(circle at 50% 20%, #2e1065 0%, #0b0f19 75%);
      color: #ffffff;
      padding: 80px 0 100px;
      position: relative;
    }
    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(99, 102, 241, 0.15);
      border: 1px solid rgba(165, 180, 252, 0.3);
      color: #c7d2fe;
      padding: 6px 16px;
      border-radius: 30px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 24px;
    }
    .hero-title {
      font-size: 54px;
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 20px;
      letter-spacing: -1px;
    }
    .hero-title .gradient-text {
      background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .hero-subtitle {
      font-size: 17px;
      color: #94a3b8;
      max-width: 520px;
      line-height: 1.6;
      margin-bottom: 32px;
    }
    .btn-gradient-primary {
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      color: #ffffff;
      font-weight: 700;
      padding: 12px 28px;
      border-radius: 10px;
      border: none;
      box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
    }
    .btn-gradient-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 30px rgba(99, 102, 241, 0.5);
      color: #fff;
    }
    .btn-hero-outline {
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #ffffff;
      font-weight: 600;
      padding: 12px 24px;
      border-radius: 10px;
      text-decoration: none;
      display: inline-block;
      margin-left: 12px;
      transition: background 0.2s ease;
    }
    .btn-hero-outline:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #ffffff;
    }
    .hero-trust-list {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 40px;
      color: #cbd5e1;
      font-size: 13px;
      font-weight: 500;
    }
    .hero-trust-item {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* Mockup Container (Ref Image 2 Right Side) */
    .mockup-window {
      background: #0f172a;
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
      position: relative;
    }
    .mockup-header {
      background: #1e293b;
      padding: 10px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .mockup-dots span {
      display: inline-block;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      margin-right: 4px;
    }
    .dot-red { background: #ef4444; }
    .dot-yellow { background: #f59e0b; }
    .dot-green { background: #10b981; }

    /* Page Section Under Hero Section - Light Theme (Ref Image 2 Concept) */
    .section-light {
      background-color: #ffffff;
      padding: 70px 0;
    }
    .section-grey {
      background-color: #f8fafc;
      padding: 70px 0;
    }
    .section-subtitle-tag {
      color: #6366f1;
      font-size: 13px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
      display: block;
    }
    .section-heading {
      font-size: 34px;
      font-weight: 800;
      color: var(--text-dark);
      margin-bottom: 14px;
    }
    .section-description {
      color: var(--text-muted);
      font-size: 15px;
      max-width: 600px;
    }

    /* Target Audience Icon Cards (Ref Image 2) */
    .audience-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 20px 12px;
      text-align: center;
      transition: all 0.2s ease;
      height: 100%;
      cursor: pointer;
    }
    .audience-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 20px rgba(99, 102, 241, 0.08);
      border-color: #c7d2fe;
    }
    .audience-icon {
      width: 48px;
      height: 48px;
      background: #f1f5f9;
      color: #6366f1;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      margin: 0 auto 12px;
    }

    /* Visionaries Showcase Thumbnails (Ref Image 2 Right Side) */
    .showcase-thumb-card {
      background: #0f172a;
      border-radius: 12px;
      overflow: hidden;
      position: relative;
      height: 180px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: transform 0.2s ease;
    }
    .showcase-thumb-card:hover {
      transform: scale(1.03);
    }
    .showcase-thumb-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .showcase-badge {
      position: absolute;
      bottom: 12px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(15, 23, 42, 0.85);
      backdrop-filter: blur(6px);
      color: #ffffff;
      padding: 4px 14px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* Process Step Cards (Ref Image 2 3-Step Section) */
    .step-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 32px 24px;
      height: 100%;
      position: relative;
    }
    .step-number {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 20px;
      font-size: 16px;
    }
    .step-1 { background: #6366f1; }
    .step-2 { background: #3b82f6; }
    .step-3 { background: #10b981; }

    /* Feature Grid Cards */
    .feature-box {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 24px;
      height: 100%;
      transition: all 0.2s ease;
    }
    .feature-box:hover {
      border-color: #818cf8;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.04);
    }
    .feature-icon-wrapper {
      width: 42px;
      height: 42px;
      border-radius: 8px;
      background: #eff6ff;
      color: #3b82f6;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      margin-bottom: 14px;
    }

    /* Templates Showcase Cards */
    .template-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      overflow: hidden;
      transition: all 0.2s ease;
      height: 100%;
    }
    .template-card:hover {
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
      transform: translateY(-3px);
    }
    .template-preview-img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-bottom: 1px solid #f1f5f9;
    }

    /* Pricing Section (Ref Image 2 Match) */
    .pricing-toggle-box {
      display: inline-flex;
      background: #e2e8f0;
      padding: 4px;
      border-radius: 30px;
      gap: 4px;
      margin-bottom: 40px;
    }
    .pricing-toggle-btn {
      border: none;
      background: transparent;
      padding: 6px 18px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 700;
      color: #64748b;
      cursor: pointer;
    }
    .pricing-toggle-btn.active {
      background: #ffffff;
      color: #0f172a;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .wb-pricing-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 32px 28px;
      position: relative;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .wb-pricing-card.highlighted {
      border: 2px solid #6366f1;
      box-shadow: 0 10px 30px rgba(99, 102, 241, 0.15);
    }
    .popular-tag {
      position: absolute;
      top: -12px;
      right: 20px;
      background: #6366f1;
      color: #ffffff;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 12px;
    }
    .price-number {
      font-size: 40px;
      font-weight: 800;
      color: #0f172a;
    }

    /* Testimonials Section */
    .testimonial-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 28px;
      height: 100%;
    }
    .rating-stars {
      color: #f59e0b;
      margin-bottom: 12px;
      font-size: 14px;
    }

    /* Rocket Banner CTA */
    .rocket-cta-container {
      background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
      border-radius: 20px;
      padding: 50px 40px;
      color: #ffffff;
      position: relative;
      overflow: hidden;
    }
    .rocket-icon-graphic {
      font-size: 100px;
      position: absolute;
      right: 40px;
      bottom: -10px;
      opacity: 0.85;
      transform: rotate(-15deg);
    }

    /* Footer Light Contact Cards */
    .contact-card-item {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 20px;
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .contact-icon-box {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: #f1f5f9;
      color: #6366f1;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }

    {!! $settings->custom_css !!}
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="wb-navbar">
    <div class="container d-flex align-items-center justify-content-between">
      <a href="{{ route('website-builder.index') }}" class="wb-brand">
        <div class="wb-brand-icon"><i class="fa-solid fa-layer-group"></i></div>
        <span>website builder</span>
      </a>
      <div class="d-none d-lg-flex align-items-center">
        <a href="#who" class="nav-link">For You</a>
        <a href="#process" class="nav-link">Process</a>
        <a href="#features" class="nav-link">Features</a>
        <a href="#templates" class="nav-link">Templates</a>
        <a href="#pricing" class="nav-link">Pricing</a>
        <a href="#contact" class="nav-link">Contact</a>
      </div>
      <div>
        <a href="{{ route('website-builder.user.dashboard') }}" class="btn btn-sm btn-hero-outline text-white me-2">Log In</a>
        <a href="#pricing" class="btn btn-sm btn-gradient-primary">Get Started</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="hero-badge"><i class="fa-solid fa-bolt"></i> {{ $settings->hero_badge }}</div>
          <h1 class="hero-title">{!! str_replace('Few Minutes', '<span class="gradient-text">Few Minutes</span>', e($settings->hero_title)) !!}</h1>
          <p class="hero-subtitle">{{ $settings->hero_subtitle }}</p>
          <div>
            <a href="{{ $settings->cta_primary_url }}" class="btn-gradient-primary">{{ $settings->cta_primary_text }} <i class="fa-solid fa-arrow-right"></i></a>
            <a href="{{ $settings->cta_secondary_url }}" class="btn-hero-outline">{{ $settings->cta_secondary_text }}</a>
          </div>
          <div class="hero-trust-list">
            @foreach($settings->trust_badges as $tb)
              <div class="hero-trust-item">
                <i class="fa-solid fa-circle-check text-indigo" style="color:#818cf8;"></i>
                <span>{{ $tb['text'] }}</span>
              </div>
            @endforeach
          </div>
        </div>

        <div class="col-lg-6 mt-5 mt-lg-0">
          <div class="mockup-window">
            <div class="mockup-header">
              <div class="mockup-dots">
                <span class="dot-red"></span>
                <span class="dot-yellow"></span>
                <span class="dot-green"></span>
              </div>
              <div class="text-white-50 small font-monospace">websitebuilder.com/editor</div>
              <span class="badge bg-primary px-3 py-1">Publish</span>
            </div>
            <div class="p-4" style="background:#0f172a; min-height: 280px;">
              <div class="bg-dark text-white p-4 rounded-3 border border-secondary text-center mb-3" style="background: radial-gradient(circle, #312e81, #0f172a) !important;">
                <h4 class="fw-bold mb-2">Create Your Dream Website</h4>
                <p class="text-white-50 small mb-3">Build, Customize, Publish in seconds</p>
                <button class="btn btn-sm btn-indigo text-white px-3" style="background:#6366f1;">Get Started</button>
              </div>

              <!-- Interactive Floating Editor Controls Pill -->
              <div class="d-flex justify-content-between align-items-center bg-slate-800 p-2 rounded border border-slate-700" style="background:#1e293b;">
                <div class="d-flex gap-2">
                  <span class="small text-white-50">Colors:</span>
                  <span style="width:16px;height:16px;border-radius:50%;background:#3b82f6;display:inline-block;"></span>
                  <span style="width:16px;height:16px;border-radius:50%;background:#8b5cf6;display:inline-block;"></span>
                  <span style="width:16px;height:16px;border-radius:50%;background:#06b6d4;display:inline-block;"></span>
                </div>
                <div class="small text-white-50">
                  <i class="fa-solid fa-font me-1"></i> Poppins / Open Sans
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section Under Hero (Light Theme Concept - Pixel Match Ref Image 2) -->
  <section id="who" class="section-light">
    <div class="container">
      <div class="row g-4">
        <!-- Left Side: Target Audience Grid -->
        <div class="col-lg-5">
          <span class="section-subtitle-tag">Who it's for</span>
          <h2 class="section-heading">Who Is website builder For?</h2>
          <p class="section-description mb-4">
            Perfect for Every Business & Creator. Whether you're launching a personal brand, a portfolio, a local business, or online store — website builder makes it simple.
          </p>
          <div class="row g-3">
            @php
              $audiences = [
                ['icon' => 'fa-user-tie', 'title' => 'Freelancers'],
                ['icon' => 'fa-rocket', 'title' => 'Startups'],
                ['icon' => 'fa-building', 'title' => 'Agencies'],
                ['icon' => 'fa-store', 'title' => 'Shops'],
                ['icon' => 'fa-pen-to-square', 'title' => 'Bloggers'],
                ['icon' => 'fa-calendar-check', 'title' => 'Events'],
              ];
            @endphp
            @foreach($audiences as $aud)
              <div class="col-4">
                <div class="audience-card">
                  <div class="audience-icon"><i class="fa-solid {{ $aud['icon'] }}"></i></div>
                  <div class="fw-bold small text-dark">{{ $aud['title'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Right Side: Visionaries Showcase Cards -->
        <div class="col-lg-7">
          <div class="ps-lg-4">
            <span class="section-subtitle-tag">Use Cases</span>
            <h2 class="section-heading">Built for Visionaries</h2>
            <p class="section-description mb-4">Whether you're a freelancer or a founder, we have the perfect starting point.</p>

            <div class="row g-3">
              @php
                $useCases = [
                  ['title' => 'Portfolio',  'color' => '#8b5cf6', 'icon' => 'fa-paint-brush'],
                  ['title' => 'Startup',    'color' => '#3b82f6', 'icon' => 'fa-bolt'],
                  ['title' => 'Agency',     'color' => '#ec4899', 'icon' => 'fa-layer-group'],
                  ['title' => 'eCommerce',  'color' => '#14b8a6', 'icon' => 'fa-cart-shopping'],
                  ['title' => 'Restaurant', 'color' => '#f59e0b', 'icon' => 'fa-utensils'],
                  ['title' => 'Events',     'color' => '#a855f7', 'icon' => 'fa-ticket'],
                ];
              @endphp
              @foreach($useCases as $uc)
                <div class="col-4 col-md-4">
                  <div class="showcase-thumb-card">
                    <div style="background: radial-gradient(circle, {{ $uc['color'] }} 0%, #0f172a 100%); width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                      <i class="fa-solid {{ $uc['icon'] }}" style="font-size:36px; color:rgba(255,255,255,0.7);"></i>
                    </div>
                    <div class="showcase-badge">
                      <span style="width:8px; height:8px; border-radius:50%; background:{{ $uc['color'] }}; display:inline-block;"></span>
                      {{ $uc['title'] }}
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Process Section (3 Simple Steps - Ref Image 2) -->
  <section id="process" class="section-grey">
    <div class="container">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
        <div>
          <span class="section-subtitle-tag">Process</span>
          <h2 class="section-heading mb-1">Launch in 3 Simple Steps</h2>
          <p class="section-description mb-0">Stop wrestling with code. Our visual editor makes website building as easy as editing a document.</p>
        </div>
        <a href="#pricing" class="btn btn-outline-primary fw-bold rounded-pill px-4 mt-3 mt-md-0">Start Building <i class="fa-solid fa-arrow-right ms-1"></i></a>
      </div>

      <div class="row g-4">
        @foreach($settings->process_data as $index => $step)
          <div class="col-md-4">
            <div class="step-card">
              <div class="step-number step-{{ $index + 1 }}">{{ $step['step'] }}</div>
              <h4 class="fw-bold mb-2">{{ $step['title'] }}</h4>
              <p class="text-muted small mb-0">{{ $step['desc'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Features Section (8-Card Grid) -->
  <section id="features" class="section-light">
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-subtitle-tag">Features</span>
        <h2 class="section-heading">Everything You Need</h2>
        <p class="section-description mx-auto">We've packed all the technical heavy lifting into a simple interface.</p>
      </div>

      <div class="row g-4">
        @foreach($settings->features_data as $feat)
          <div class="col-6 col-md-3">
            <div class="feature-box">
              <div class="feature-icon-wrapper"><i class="fa-solid fa-layer-group"></i></div>
              <h5 class="fw-bold fs-6 mb-2">{{ $feat['title'] }}</h5>
              <p class="text-muted small mb-0">{{ $feat['desc'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Templates Showcase Section -->
  <section id="templates" class="section-grey">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
          <span class="section-subtitle-tag">Templates</span>
          <h2 class="section-heading mb-1">Start with a Professional Template</h2>
          <p class="section-description mb-0">Choose a design you love and make it yours.</p>
        </div>
        <a href="{{ route('website-builder.templates') }}" class="btn btn-sm btn-outline-dark fw-bold px-3">View All Templates <i class="fa-solid fa-arrow-right ms-1"></i></a>
      </div>

      <div class="row g-4">
        @foreach($templates->take(6) as $tmpl)
          <div class="col-md-4">
            <div class="template-card">
              <img src="{{ asset($tmpl->preview_image) }}" onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80'" class="template-preview-img" alt="{{ $tmpl->name }}">
              <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h5 class="fw-bold mb-0 fs-6">{{ $tmpl->name }}</h5>
                  <span class="badge bg-indigo-subtle text-primary border border-indigo-subtle">{{ $tmpl->category }}</span>
                </div>
                <p class="text-muted small mb-4">{{ Str::limit($tmpl->description, 80) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                  <a href="{{ $tmpl->demo_url ?? '#' }}" target="_blank" class="btn btn-sm btn-light border fw-bold">View Demo</a>
                  <button class="btn btn-sm btn-primary fw-bold text-white px-3" onclick="triggerRazorpayModal(1, '{{ $tmpl->name }}', {{ $tmpl->price > 0 ? $tmpl->price : 9 }})">
                    {{ $tmpl->price > 0 ? 'Purchase - $' . number_format($tmpl->price, 0) : 'Purchase - $49' }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Pricing Section (Ref Image 2 Match with Razorpay Checkout Integration) -->
  <section id="pricing" class="section-light">
    <div class="container text-center">
      <span class="section-subtitle-tag">Pricing</span>
      <h2 class="section-heading">Simple, Transparent Pricing</h2>
      <p class="section-description mx-auto mb-4">Choose the perfect plan for your needs.</p>

      <div class="pricing-toggle-box">
        <button class="pricing-toggle-btn active" id="btn-monthly" onclick="toggleBilling('monthly')">Monthly</button>
        <button class="pricing-toggle-btn" id="btn-yearly" onclick="toggleBilling('yearly')">Yearly (Save 20%)</button>
      </div>

      <div class="row g-4 text-start justify-content-center">
        @foreach($packages as $pkg)
          <div class="col-md-4">
            <div class="wb-pricing-card {{ $pkg->is_popular ? 'highlighted' : '' }}">
              @if($pkg->is_popular)
                <div class="popular-tag">Most Popular</div>
              @endif
              <div>
                <h4 class="fw-bold mb-1">{{ $pkg->name }}</h4>
                <p class="text-muted small mb-3">Perfect for growing websites</p>
                <div class="mb-4">
                  <span class="price-number monthly-price">${{ number_format($pkg->monthly_price, 0) }}</span>
                  <span class="price-number yearly-price d-none">${{ number_format($pkg->yearly_price, 0) }}</span>
                  <span class="text-muted small">/ month</span>
                </div>
                <ul class="list-unstyled text-muted small mb-4">
                  <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> {{ $pkg->max_websites }} Website(s)</li>
                  <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> {{ number_format($pkg->storage_limit_mb) }} MB Storage</li>
                  <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Custom Domain Support</li>
                  <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> 24/7 Priority Support</li>
                </ul>
              </div>
              <button onclick="triggerRazorpayModal({{ $pkg->id }}, '{{ $pkg->name }}', {{ $pkg->monthly_price }})" class="btn w-100 {{ $pkg->is_popular ? 'btn-gradient-primary' : 'btn-outline-dark fw-bold' }}">
                Purchase Now
              </button>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Testimonials Section (Ref Image 2 Match) -->
  <section class="section-grey">
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-subtitle-tag">Testimonials</span>
        <h2 class="section-heading">Loved by Thousands of Customers</h2>
      </div>

      <div class="row g-4">
        @foreach($settings->testimonials_data as $testi)
          <div class="col-md-4">
            <div class="testimonial-card">
              <div class="rating-stars">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
              <p class="text-muted small mb-4">"{{ $testi['comment'] }}"</p>
              <div class="d-flex align-items-center gap-3">
                <div style="width:40px;height:40px;border-radius:50%;background:#6366f1;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">
                  {{ substr($testi['name'], 0, 1) }}
                </div>
                <div>
                  <h6 class="fw-bold mb-0 small">{{ $testi['name'] }}</h6>
                  <span class="text-muted small" style="font-size:12px;">{{ $testi['role'] }}</span>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Rocket CTA Banner (Ref Image 2 Match) -->
  <section class="section-light">
    <div class="container">
      <div class="rocket-cta-container text-center">
        <div class="rocket-icon-graphic">🚀</div>
        <h2 class="fw-extrabold fs-2 text-white mb-3">Start Your Professional Website Today</h2>
        <p class="text-white-50 max-w-600 mx-auto mb-4">
          Join thousands of successful businesses who trust website builder for their online presence.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
          <a href="#pricing" class="btn btn-light text-indigo fw-bold px-4 py-2" style="color:#4338ca;">Get Started Free <i class="fa-solid fa-arrow-right ms-1"></i></a>
          <a href="#templates" class="btn btn-outline-light fw-bold px-4 py-2">View Templates</a>
        </div>
        <div class="d-flex justify-content-center flex-wrap gap-4 text-white-50 small">
          <span><i class="fa-solid fa-check text-emerald-400 me-1"></i> No credit card required</span>
          <span><i class="fa-solid fa-check text-emerald-400 me-1"></i> Free forever plan</span>
          <span><i class="fa-solid fa-check text-emerald-400 me-1"></i> Cancel anytime</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Cards Section -->
  <section id="contact" class="section-grey">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-heading">Let's Build Something Amazing Together</h2>
        <p class="section-description mx-auto">Have questions? We're here to help!</p>
      </div>

      <div class="row g-4">
        <div class="col-md-3">
          <div class="contact-card-item">
            <div class="contact-icon-box"><i class="fa-solid fa-envelope"></i></div>
            <div>
              <div class="small fw-bold">Email Us</div>
              <div class="text-muted small">{{ $settings->contact_email }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="contact-card-item">
            <div class="contact-icon-box"><i class="fa-solid fa-phone"></i></div>
            <div>
              <div class="small fw-bold">Call Us</div>
              <div class="text-muted small">{{ $settings->contact_phone }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="contact-card-item">
            <div class="contact-icon-box"><i class="fa-solid fa-comments"></i></div>
            <div>
              <div class="small fw-bold">Live Chat</div>
              <div class="text-muted small">Available 24/7</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="contact-card-item">
            <div class="contact-icon-box"><i class="fa-solid fa-location-dot"></i></div>
            <div>
              <div class="small fw-bold">Visit Us</div>
              <div class="text-muted small">{{ Str::limit($settings->contact_address, 25) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-5" style="background:#090d16; color:#94a3b8;">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <a href="#" class="wb-brand mb-3">
            <div class="wb-brand-icon"><i class="fa-solid fa-layer-group"></i></div>
            <span>website builder</span>
          </a>
          <p class="small text-muted mb-0">{{ $settings->footer_text }}</p>
        </div>
        <div class="col-md-2">
          <h6 class="text-white fw-bold mb-3 small">Product</h6>
          <ul class="list-unstyled small">
            <li class="mb-2"><a href="#features" class="text-muted text-decoration-none">Features</a></li>
            <li class="mb-2"><a href="#templates" class="text-muted text-decoration-none">Templates</a></li>
            <li class="mb-2"><a href="#pricing" class="text-muted text-decoration-none">Pricing</a></li>
          </ul>
        </div>
        <div class="col-md-2">
          <h6 class="text-white fw-bold mb-3 small">Company</h6>
          <ul class="list-unstyled small">
            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">About Us</a></li>
            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Careers</a></li>
            <li class="mb-2"><a href="#contact" class="text-muted text-decoration-none">Contact</a></li>
          </ul>
        </div>
        <div class="col-md-2">
          <h6 class="text-white fw-bold mb-3 small">Support</h6>
          <ul class="list-unstyled small">
            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Help Center</a></li>
            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Documentation</a></li>
            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Status</a></li>
          </ul>
        </div>
        <div class="col-md-2">
          <h6 class="text-white fw-bold mb-3 small">Legal</h6>
          <ul class="list-unstyled small">
            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Privacy Policy</a></li>
            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Terms of Service</a></li>
            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Refund Policy</a></li>
          </ul>
        </div>
      </div>
      <div class="text-center small border-top border-secondary pt-4 mt-5">
        © {{ date('Y') }} Website Builder. All rights reserved.
      </div>
    </div>
  </footer>

  <!-- Razorpay Checkout Integration Script -->
  <script>
    function toggleBilling(mode) {
      document.querySelectorAll('.pricing-toggle-btn').forEach(btn => btn.classList.remove('active'));
      if (mode === 'monthly') {
        document.getElementById('btn-monthly').classList.add('active');
        document.querySelectorAll('.monthly-price').forEach(el => el.classList.remove('d-none'));
        document.querySelectorAll('.yearly-price').forEach(el => el.classList.add('d-none'));
      } else {
        document.getElementById('btn-yearly').classList.add('active');
        document.querySelectorAll('.monthly-price').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.yearly-price').forEach(el => el.classList.remove('d-none'));
      }
    }

    function triggerRazorpayModal(packageId, packageName, price) {
      fetch('{{ route("website-builder.razorpay.process") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          package_id: packageId,
          name: 'Customer User',
          email: 'customer@example.com'
        })
      })
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          alert('Payment Initialization Failed: ' + (data.message || 'Error occurred'));
          return;
        }

        var options = {
          "key": data.key,
          "amount": data.amount,
          "currency": "INR",
          "name": data.name,
          "description": data.description,
          "order_id": data.order_id,
          "handler": function (response) {
            fetch('{{ route("website-builder.razorpay.callback") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({
                razorpay_order_id: response.razorpay_order_id,
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_signature: response.razorpay_signature
              })
            })
            .then(res => res.json())
            .then(verifyData => {
              if (verifyData.success) {
                alert('Payment Success! ' + verifyData.message);
                window.location.href = '{{ route("website-builder.user.dashboard") }}';
              } else {
                alert('Verification Error: ' + verifyData.message);
              }
            });
          },
          "prefill": data.prefill,
          "theme": {
            "color": "#6366f1"
          }
        };
        var rzp = new Razorpay(options);
        rzp.open();
      })
      .catch(err => {
        alert('Payment Request Exception: ' + err.message);
      });
    }
  </script>
</body>
</html>
