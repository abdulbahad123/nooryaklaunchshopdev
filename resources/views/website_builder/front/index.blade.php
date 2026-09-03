<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $settings->hero_title }} - Website Builder</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --brand-primary: {{ $settings->primary_color ?? '#6366f1' }};
      --brand-secondary: {{ $settings->secondary_color ?? '#8b5cf6' }};
      --bg-dark: #090d16;
      --bg-card: #131b2e;
      --border-color: rgba(255, 255, 255, 0.1);
      --text-muted: #94a3b8;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--bg-dark);
      color: #ffffff;
      overflow-x: hidden;
    }

    /* Navbar */
    .wb-navbar {
      background: rgba(9, 13, 22, 0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border-color);
      padding: 18px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .wb-brand {
      font-weight: 800;
      font-size: 22px;
      color: #fff;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .wb-brand-icon {
      width: 38px;
      height: 38px;
      background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: #fff;
    }
    .nav-link {
      color: var(--text-muted);
      font-weight: 500;
      margin: 0 12px;
      transition: color 0.2s;
    }
    .nav-link:hover {
      color: #fff;
    }

    /* Hero Section */
    .hero-section {
      padding: 90px 0 120px;
      position: relative;
      background: radial-gradient(circle at 50% 20%, rgba(99, 102, 241, 0.18) 0%, transparent 60%);
    }
    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(99, 102, 241, 0.15);
      border: 1px solid rgba(99, 102, 241, 0.3);
      color: #a5b4fc;
      padding: 6px 16px;
      border-radius: 30px;
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 24px;
    }
    .hero-title {
      font-size: 56px;
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 24px;
    }
    .hero-title span {
      background: linear-gradient(135deg, #60a5fa, #c084fc);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .hero-subtitle {
      font-size: 18px;
      color: var(--text-muted);
      max-width: 540px;
      line-height: 1.6;
      margin-bottom: 36px;
    }
    .btn-gradient {
      background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
      color: #fff;
      font-weight: 700;
      padding: 14px 32px;
      border-radius: 12px;
      border: none;
      box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35);
      transition: transform 0.2s, box-shadow 0.2s;
      text-decoration: none;
      display: inline-block;
    }
    .btn-gradient:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(99, 102, 241, 0.5);
      color: #fff;
    }
    .btn-outline-custom {
      border: 1px solid var(--border-color);
      color: #fff;
      font-weight: 600;
      padding: 14px 28px;
      border-radius: 12px;
      text-decoration: none;
      display: inline-block;
      margin-left: 12px;
      transition: background 0.2s;
    }
    .btn-outline-custom:hover {
      background: rgba(255, 255, 255, 0.05);
      color: #fff;
    }

    /* Hero Mockup Card */
    .hero-mockup-box {
      background: #0f172a;
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
      position: relative;
    }

    /* Features Card */
    .wb-card {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 28px;
      height: 100%;
      transition: transform 0.2s, border-color 0.2s;
    }
    .wb-card:hover {
      transform: translateY(-4px);
      border-color: rgba(99, 102, 241, 0.4);
    }
    .wb-card-icon {
      width: 52px;
      height: 52px;
      background: rgba(99, 102, 241, 0.12);
      color: #818cf8;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      margin-bottom: 20px;
    }

    /* Section Headers */
    .section-tag {
      color: #818cf8;
      font-size: 14px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
      display: block;
    }
    .section-title {
      font-size: 38px;
      font-weight: 800;
      margin-bottom: 16px;
    }

    /* Pricing Card */
    .pricing-card {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 20px;
      padding: 36px;
      position: relative;
    }
    .pricing-card.popular {
      border: 2px solid var(--brand-primary);
      box-shadow: 0 0 40px rgba(99, 102, 241, 0.25);
    }
    .popular-badge {
      position: absolute;
      top: -14px;
      right: 24px;
      background: var(--brand-primary);
      color: #fff;
      font-size: 12px;
      font-weight: 700;
      padding: 4px 14px;
      border-radius: 20px;
    }

    /* Rocket CTA Banner */
    .cta-banner {
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      border-radius: 24px;
      padding: 60px 40px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    {!! $settings->custom_css !!}
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="wb-navbar">
    <div class="container d-flex align-items-center justify-content-between">
      <a href="{{ route('website-builder.index') }}" class="wb-brand">
        <div class="wb-brand-icon"><i class="fa-solid fa-layer-group"></i></div>
        <span>website builder</span>
      </a>
      <div class="d-none d-md-flex align-items-center">
        <a href="#who" class="nav-link">For You</a>
        <a href="#process" class="nav-link">Process</a>
        <a href="#features" class="nav-link">Features</a>
        <a href="#templates" class="nav-link">Templates</a>
        <a href="#pricing" class="nav-link">Pricing</a>
      </div>
      <div>
        <a href="{{ route('website-builder.user.dashboard') }}" class="btn btn-sm btn-outline-custom text-white me-2">Log In</a>
        <a href="#pricing" class="btn btn-sm btn-gradient">Get Started</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="hero-badge"><i class="fa-solid fa-bolt"></i> {{ $settings->hero_badge }}</div>
          <h1 class="hero-title">{!! str_replace('Few Minutes', '<span>Few Minutes</span>', e($settings->hero_title)) !!}</h1>
          <p class="hero-subtitle">{{ $settings->hero_subtitle }}</p>
          <div>
            <a href="{{ $settings->cta_primary_url }}" class="btn-gradient">{{ $settings->cta_primary_text }} <i class="fa-solid fa-arrow-right ms-2"></i></a>
            <a href="{{ $settings->cta_secondary_url }}" class="btn-outline-custom">{{ $settings->cta_secondary_text }}</a>
          </div>
          <div class="d-flex align-items-center gap-4 mt-5">
            @foreach($settings->trust_badges as $badge)
              <div class="d-flex align-items-center gap-2 text-muted fs-6">
                <i class="fa-solid fa-check-circle text-indigo"></i>
                <span>{{ $badge['text'] }}</span>
              </div>
            @endforeach
          </div>
        </div>
        <div class="col-lg-6 mt-5 mt-lg-0">
          <div class="hero-mockup-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="badge bg-indigo-subtle text-white">Create Your Dream Website</span>
              <span class="badge bg-success">Live Preview</span>
            </div>
            <img src="{{ asset($settings->hero_image ?? 'images/hero-section.png') }}" class="img-fluid rounded-3 border border-secondary" alt="Website Builder Demo">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Who Is it For Section -->
  <section id="who" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-tag">Who it's for</span>
        <h2 class="section-title">Who Is website builder For?</h2>
        <p class="text-muted">Whether you're launching a personal brand, a portfolio, a local business, or online store.</p>
      </div>
      <div class="row g-4 text-center">
        @php
          $audiences = [
            ['icon' => 'fa-user-tie', 'title' => 'Freelancers'],
            ['icon' => 'fa-rocket', 'title' => 'Startups'],
            ['icon' => 'fa-briefcase', 'title' => 'Agencies'],
            ['icon' => 'fa-store', 'title' => 'Shops'],
            ['icon' => 'fa-pen-nib', 'title' => 'Bloggers'],
            ['icon' => 'fa-calendar-days', 'title' => 'Events'],
          ];
        @endphp
        @foreach($audiences as $aud)
          <div class="col-6 col-md-2">
            <div class="wb-card p-4">
              <div class="wb-card-icon mx-auto"><i class="fa-solid {{ $aud['icon'] }}"></i></div>
              <h5 class="fw-bold mb-0 fs-6">{{ $aud['title'] }}</h5>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Process Section -->
  <section id="process" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-tag">Process</span>
        <h2 class="section-title">Launch in 3 Simple Steps</h2>
      </div>
      <div class="row g-4">
        @foreach($settings->process_data as $p)
          <div class="col-md-4">
            <div class="wb-card text-center">
              <div class="badge bg-indigo fs-5 mb-3 px-3 py-2 rounded-circle" style="background: var(--brand-primary);">{{ $p['step'] }}</div>
              <h4 class="fw-bold">{{ $p['title'] }}</h4>
              <p class="text-muted fs-6">{{ $p['desc'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-tag">Features</span>
        <h2 class="section-title">Everything You Need</h2>
        <p class="text-muted">We've packed all the technical heavy lifting into a simple interface.</p>
      </div>
      <div class="row g-4">
        @foreach($settings->features_data as $feat)
          <div class="col-md-3">
            <div class="wb-card">
              <div class="wb-card-icon"><i class="fa-solid fa-cube"></i></div>
              <h5 class="fw-bold fs-6">{{ $feat['title'] }}</h5>
              <p class="text-muted small mb-0">{{ $feat['desc'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Templates Section -->
  <section id="templates" class="py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
          <span class="section-tag">Templates</span>
          <h2 class="section-title mb-0">Start with a Professional Template</h2>
        </div>
        <a href="{{ route('website-builder.templates') }}" class="btn btn-outline-custom">View All Templates <i class="fa-solid fa-arrow-right ms-1"></i></a>
      </div>
      <div class="row g-4">
        @foreach($templates->take(6) as $tmpl)
          <div class="col-md-4">
            <div class="wb-card p-3">
              <img src="{{ asset($tmpl->preview_image) }}" onerror="this.src='{{ asset('images/hero-section.png') }}'" class="img-fluid rounded-3 mb-3" style="height: 220px; object-fit: cover; width: 100%;" alt="{{ $tmpl->name }}">
              <h5 class="fw-bold">{{ $tmpl->name }}</h5>
              <p class="text-muted small">{{ $tmpl->description }}</p>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="{{ $tmpl->demo_url ?? '#' }}" target="_blank" class="btn btn-sm btn-outline-custom">View Demo</a>
                <a href="#pricing" class="btn btn-sm btn-gradient">Select Template</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Pricing Section -->
  <section id="pricing" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-tag">Pricing</span>
        <h2 class="section-title">Simple, Transparent Pricing</h2>
      </div>
      <div class="row g-4 justify-content-center">
        @foreach($packages as $pkg)
          <div class="col-md-4">
            <div class="pricing-card {{ $pkg->is_popular ? 'popular' : '' }}">
              @if($pkg->is_popular)
                <div class="popular-badge">Most Popular</div>
              @endif
              <h4 class="fw-bold">{{ $pkg->name }}</h4>
              <div class="my-3">
                <span class="fs-1 fw-extrabold">${{ $pkg->monthly_price }}</span>
                <span class="text-muted">/ month</span>
              </div>
              <ul class="list-unstyled my-4 text-muted small">
                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> {{ $pkg->max_websites }} Website(s)</li>
                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> {{ $pkg->storage_limit_mb }}MB Storage</li>
                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Custom Domain Support</li>
                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> 24/7 Priority Support</li>
              </ul>
              <a href="{{ route('website-builder.user.dashboard') }}" class="btn w-100 {{ $pkg->is_popular ? 'btn-gradient' : 'btn-outline-custom' }}">Choose {{ $pkg->name }}</a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Rocket CTA Banner -->
  <section class="py-5">
    <div class="container">
      <div class="cta-banner">
        <h2 class="fw-extrabold fs-1 text-white mb-3">Start Your Professional Website Today</h2>
        <p class="text-white-50 fs-5 mb-4">Join thousands of successful creators and businesses who trust website builder.</p>
        <a href="#pricing" class="btn btn-light text-primary fw-bold btn-lg px-4 rounded-3">Get Started Free <i class="fa-solid fa-arrow-right ms-2"></i></a>
      </div>
    </div>
  </section>

  <!-- Footer Contact & Links -->
  <footer class="py-5 border-top border-secondary mt-5" style="background: #060911;">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <a href="#" class="wb-brand mb-3">
            <div class="wb-brand-icon"><i class="fa-solid fa-layer-group"></i></div>
            <span>website builder</span>
          </a>
          <p class="text-muted small">{{ $settings->footer_text }}</p>
        </div>
        <div class="col-md-2">
          <h6 class="fw-bold mb-3">Product</h6>
          <ul class="list-unstyled text-muted small">
            <li><a href="#features" class="text-decoration-none text-muted">Features</a></li>
            <li><a href="#templates" class="text-decoration-none text-muted">Templates</a></li>
            <li><a href="#pricing" class="text-decoration-none text-muted">Pricing</a></li>
          </ul>
        </div>
        <div class="col-md-2">
          <h6 class="fw-bold mb-3">Support</h6>
          <ul class="list-unstyled text-muted small">
            <li><span class="text-muted">{{ $settings->contact_email }}</span></li>
            <li><span class="text-muted">{{ $settings->contact_phone }}</span></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h6 class="fw-bold mb-3">Contact Us</h6>
          <p class="text-muted small">{{ $settings->contact_address }}</p>
        </div>
      </div>
      <div class="text-center text-muted small border-top border-secondary pt-4 mt-4">
        © {{ date('Y') }} Website Builder. All rights reserved.
      </div>
    </div>
  </footer>

</body>
</html>
