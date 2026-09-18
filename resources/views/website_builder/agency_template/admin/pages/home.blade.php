@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Home Page - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-house text-indigo me-2" style="color: #4F46E5;"></i>Edit Home Page</h3>
    <p class="text-muted small mb-0">Update Hero badge, main titles, photo graphic, 6 service cards, and 8 portfolio projects.</p>
  </div>
  <a href="{{ $liveUrl ?? (isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency')) }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Home Page
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <!-- HERO SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-wand-magic-sparkles text-success me-2"></i>Main Hero Section</h5>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Hero Label Pill</label>
        <input type="text" class="form-control" name="hero_badge" value="{{ $agency->hero_badge ?? 'Creative Digital Solutions' }}">
      </div>
      <div class="col-md-8">
        <label class="form-label fw-semibold small">Hero Main Title (use line breaks)</label>
        <textarea class="form-control" name="hero_title" rows="2">{{ $agency->hero_title ?? "Increase Your\nCustomers Loyalty\nand Satisfaction" }}</textarea>
      </div>
      <div class="col-md-12">
        <label class="form-label fw-semibold small">Hero Subtitle Paragraph</label>
        <textarea class="form-control" name="hero_subtitle" rows="2">{{ $agency->hero_subtitle ?? 'We help businesses like yours earn more customers, stand out from competitors, and grow your revenue.' }}</textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Upload Hero Image File</label>
        <input type="file" class="form-control" name="hero_image_file" accept="image/*">
        <div class="form-text small text-muted">Upload a photo/graphic (PNG, JPG, WebP) to display in the Hero section.</div>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Or Hero Image Path / URL</label>
        <input type="text" class="form-control" name="hero_image" value="{{ $agency->hero_image ?? 'assets/website_builder/agency_hero_woman.png' }}">
        @if(!empty($agency->hero_image))
          <div class="mt-2 d-flex align-items-center gap-2">
            <span class="small fw-semibold text-muted">Current Preview:</span>
            <img src="{{ str_starts_with($agency->hero_image, 'http') ? $agency->hero_image : asset($agency->hero_image) }}" onerror="this.src='{{ asset('assets/website_builder/agency_hero_woman.png') }}';" style="height: 44px; width: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
          </div>
        @endif
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Primary Button Text</label>
        <input type="text" class="form-control" name="primary_btn_text" value="{{ $agency->primary_btn_text ?? 'Get Started' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Primary Button Link</label>
        <input type="text" class="form-control" name="primary_btn_url" value="{{ $agency->primary_btn_url ?? '#contact' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Secondary Button Text</label>
        <input type="text" class="form-control" name="secondary_btn_text" value="{{ $agency->secondary_btn_text ?? 'View Our Work' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Secondary Button Link</label>
        <input type="text" class="form-control" name="secondary_btn_url" value="{{ $agency->secondary_btn_url ?? '#portfolio' }}">
      </div>
    </div>
  </div>

  <!-- COUNTER / STATS SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-calculator text-primary me-2"></i>Homepage Counter & Stats Bar</h5>
        <p class="text-muted small mb-0">Update stats counter numbers, labels, and icons (e.g. 8+ Years, 120+ Projects, 98% Satisfaction, 24/7 Support).</p>
      </div>
      <button type="button" class="btn btn-sm btn-outline-primary fw-bold px-3 rounded-pill" onclick="addHomeCounterItem()">
        <i class="fa-solid fa-plus me-1"></i> Add Counter Item
      </button>
    </div>

    @php
      $homeStats = $agency->stats_data ?? [
        ['number' => '8+',   'label' => 'Years of Experience', 'icon' => 'fa-building-columns'],
        ['number' => '120+', 'label' => 'Projects Completed',   'icon' => 'fa-envelope'],
        ['number' => '98%',  'label' => 'Client Satisfaction',  'icon' => 'fa-circle-check'],
        ['number' => '24/7', 'label' => 'Support Available',   'icon' => 'fa-headset'],
      ];
    @endphp

    <div class="row g-3" id="homeCounterContainer">
      @foreach($homeStats as $hci => $hst)
        <div class="col-md-3 home-counter-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between shadow-sm">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold small text-primary">Counter #{{ $hci + 1 }}</div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeHomeCounterItem(this)" title="Remove Counter"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Number / Count</label>
                <input type="text" class="form-control form-control-sm fw-bold" name="stats_data[{{ $hci }}][number]" value="{{ $hst['number'] ?? ($hst['num'] ?? '') }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Label / Title</label>
                <input type="text" class="form-control form-control-sm" name="stats_data[{{ $hci }}][label]" value="{{ $hst['label'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Icon Class</label>
                <input type="text" class="form-control form-control-sm" name="stats_data[{{ $hci }}][icon]" value="{{ $hst['icon'] ?? 'fa-chart-line' }}">
              </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeHomeCounterItem(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Item
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  @php
    $isTexigoTheme = (($agency->template_type ?? '') === 'texigo' || session('demo_template') === 'texigo');
  @endphp

  @if($isTexigoTheme)
  <!-- CAB FARE CALCULATOR & VEHICLE PRICING SECTION (TaxiGo Theme Only) -->
  <div class="card card-editor p-4 mb-4 border-warning" style="border-width: 2px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1 text-dark">
          <span class="badge bg-warning text-dark me-2 px-2 py-1"><i class="fa-solid fa-taxi me-1"></i> TaxiGo Theme</span>
          Cab Fare Calculator & Vehicle Pricing Settings
        </h5>
        <p class="text-muted small mb-0">Manage vehicle types, price per KM, seats, bags, and base fare dynamically for your taxi website fare calculator.</p>
      </div>
      <button type="button" class="btn btn-sm btn-warning fw-bold px-3 rounded-pill text-dark" onclick="addVehicleType()">
        <i class="fa-solid fa-plus me-1"></i> Add Vehicle Type
      </button>
    </div>

    @php
      $calcTitle = $agency->fare_calculator_data['title'] ?? 'Estimate Your Trip Fare';
      $calcSubtitle = $agency->fare_calculator_data['subtitle'] ?? 'Instant, transparent pricing with no hidden charges. Select your route and vehicle.';
      $calcBadge = $agency->fare_calculator_data['badge'] ?? 'CAB FARE CALCULATOR';
      $calcVehicles = $agency->fare_calculator_data['vehicles'] ?? [
        ['name' => 'Sedan',     'rate' => 20, 'base_fare' => 50, 'seats' => '4 Seats', 'bags' => '3 Bags', 'icon' => 'fa-car'],
        ['name' => 'SUV',       'rate' => 30, 'base_fare' => 50, 'seats' => '6 Seats', 'bags' => '4 Bags', 'icon' => 'fa-truck-monster'],
        ['name' => 'Premium',   'rate' => 50, 'base_fare' => 50, 'seats' => '4 Seats', 'bags' => '3 Bags', 'icon' => 'fa-crown'],
        ['name' => 'Hatchback', 'rate' => 15, 'base_fare' => 50, 'seats' => '4 Seats', 'bags' => '2 Bags', 'icon' => 'fa-car-side'],
      ];
    @endphp

    <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Calculator Badge</label>
        <input type="text" class="form-control" name="fare_calculator_data[badge]" value="{{ $calcBadge }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Section Heading</label>
        <input type="text" class="form-control" name="fare_calculator_data[title]" value="{{ $calcTitle }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Section Subtitle</label>
        <input type="text" class="form-control" name="fare_calculator_data[subtitle]" value="{{ $calcSubtitle }}">
      </div>
    </div>

    <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-car-side me-2 text-warning"></i>Manage Vehicles & Per-KM Rates</h6>

    <div class="row g-3" id="vehiclesContainer">
      @foreach($calcVehicles as $vi => $veh)
        <div class="col-md-6 vehicle-card-item">
          <div class="border rounded-3 p-3 bg-white position-relative shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="fw-bold small text-warning text-dark"><i class="fa-solid fa-car me-1"></i> Vehicle #{{ $vi + 1 }}</div>
              <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeVehicleType(this)" title="Remove Vehicle"><i class="fa-solid fa-trash-can"></i></button>
            </div>
            <div class="row g-2">
              <div class="col-md-6">
                <label class="form-label small fw-semibold mb-1">Vehicle Name</label>
                <input type="text" class="form-control form-control-sm" name="fare_calculator_data[vehicles][{{ $vi }}][name]" value="{{ $veh['name'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold mb-1">Price Per KM (₹)</label>
                <input type="number" step="0.5" class="form-control form-control-sm fw-bold text-success" name="fare_calculator_data[vehicles][{{ $vi }}][rate]" value="{{ $veh['rate'] ?? $veh['price_per_km'] ?? 20 }}">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Seats Info</label>
                <input type="text" class="form-control form-control-sm" name="fare_calculator_data[vehicles][{{ $vi }}][seats]" value="{{ $veh['seats'] ?? '4 Seats' }}">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Bags Info</label>
                <input type="text" class="form-control form-control-sm" name="fare_calculator_data[vehicles][{{ $vi }}][bags]" value="{{ $veh['bags'] ?? '2 Bags' }}">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Base Fare (₹)</label>
                <input type="number" step="1" class="form-control form-control-sm" name="fare_calculator_data[vehicles][{{ $vi }}][base_fare]" value="{{ $veh['base_fare'] ?? $veh['base'] ?? 50 }}">
              </div>
              <div class="col-md-12">
                <label class="form-label small fw-semibold mb-1">Icon (FontAwesome Class)</label>
                <input type="text" class="form-control form-control-sm" name="fare_calculator_data[vehicles][{{ $vi }}][icon]" value="{{ $veh['icon'] ?? 'fa-car' }}">
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  @endif

  <!-- SERVICES SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-grid-2 text-success me-2"></i>Our Services Section & Cards</h5>
        <p class="text-muted small mb-0">Update section heading, subtitle, and service cards with custom icons & images.</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addService()">
        <i class="fa-solid fa-plus me-1"></i> Add Service Card
      </button>
    </div>

    <!-- Section Heading Settings -->
    <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Services Badge Pill</label>
        <input type="text" class="form-control" name="services_badge" value="{{ $agency->services_badge ?? 'WHAT WE DO' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Services Section Title</label>
        <input type="text" class="form-control" name="services_title" value="{{ $agency->services_title ?? 'Our Services' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Services Section Subtitle</label>
        <input type="text" class="form-control" name="services_subtitle" value="{{ $agency->services_subtitle ?? 'We provide a complete range of design and digital solutions to transform your business.' }}">
      </div>
    </div>

    @php
      $servicesData = $agency->services_data ?? [
        ['icon' => 'fa-laptop-code',     'title' => 'Web Design',       'desc' => 'Beautiful, modern, and responsive websites that drive results.'],
        ['icon' => 'fa-layer-group',     'title' => 'UI/UX Design',     'desc' => 'User-centered designs that create seamless digital experiences.'],
        ['icon' => 'fa-bezier-curve',    'title' => 'Branding',         'desc' => 'Unique brand identities that make your business memorable.'],
        ['icon' => 'fa-bullhorn',        'title' => 'Digital Marketing','desc' => 'Data-driven marketing strategies that boost your visibility.'],
        ['icon' => 'fa-magnifying-glass','title' => 'SEO Optimization', 'desc' => 'Improve your search rankings and drive organic traffic.'],
        ['icon' => 'fa-mobile-screen',   'title' => 'App Development',  'desc' => 'Powerful and scalable apps for iOS & Android platforms.'],
      ];
    @endphp

    <div class="row g-3" id="servicesContainer">
      @foreach($servicesData as $si => $srv)
        <div class="col-md-4 service-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold small text-success">Service #{{ $si + 1 }}</div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeService(this)" title="Remove Service"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">FontAwesome Icon Class</label>
                <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][icon]" value="{{ $srv['icon'] ?? 'fa-laptop-code' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Title</label>
                <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][title]" value="{{ $srv['title'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Description</label>
                <textarea class="form-control form-control-sm" name="services_data[{{ $si }}][desc]" rows="2">{{ $srv['desc'] ?? '' }}</textarea>
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Upload Service Image</label>
                <input type="file" class="form-control form-control-sm" name="services_data[{{ $si }}][image_file]" accept="image/*">
                <input type="hidden" name="services_data[{{ $si }}][image]" value="{{ $srv['image'] ?? '' }}">
              </div>
              @if(!empty($srv['image']))
                <div class="mt-2 d-flex align-items-center gap-2">
                  <span class="small fw-semibold text-muted">Preview:</span>
                  <img src="{{ str_starts_with($srv['image'], 'http') ? $srv['image'] : asset($srv['image']) }}" onerror="this.src='https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=400&auto=format&fit=crop';" style="height: 38px; width: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1;">
                </div>
              @endif
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeService(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Service
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- PORTFOLIO SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-briefcase text-success me-2"></i>Our Recent Work (Portfolio Projects)</h5>
        <p class="text-muted small mb-0">Update portfolio heading, subtitle, and project cards.</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addPortfolio()">
        <i class="fa-solid fa-plus me-1"></i> Add Project
      </button>
    </div>

  <!-- CONSTRUCTION TRUST HIGHLIGHTS BAR (Construction Theme Only) -->
  <div class="card card-editor p-4 mb-4 border-danger" style="border-width: 1px;">
    <h5 class="fw-bold mb-2 text-dark">
      <span class="badge bg-danger text-white me-2 px-2 py-1"><i class="fa-solid fa-helmet-safety me-1"></i> Construction Theme</span>
      Trust Highlights Bar (3 Items Below Hero)
    </h5>
    <p class="text-muted small mb-3">Edit the 3 trust highlights rendered at the bottom of the Construction theme hero banner.</p>

    @php
      $trustBar = $agency->trust_bar_data ?? [
        ['title' => 'Safe & Quality', 'sub' => 'Construction'],
        ['title' => 'Experienced', 'sub' => 'Professional Team'],
        ['title' => 'On-Time', 'sub' => 'Project Delivery'],
      ];
    @endphp

    <div class="row g-3">
      @foreach($trustBar as $ti => $tb)
        <div class="col-md-4">
          <div class="p-3 border rounded-3 bg-light">
            <div class="fw-bold small text-danger mb-2">Item {{ $ti + 1 }}</div>
            <div class="mb-2">
              <label class="form-label small fw-semibold mb-1">Title</label>
              <input type="text" class="form-control form-control-sm" name="trust_bar_data[{{ $ti }}][title]" value="{{ $tb['title'] ?? '' }}">
            </div>
            <div>
              <label class="form-label small fw-semibold mb-1">Subtitle</label>
              <input type="text" class="form-control form-control-sm" name="trust_bar_data[{{ $ti }}][sub]" value="{{ $tb['sub'] ?? '' }}">
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- EVENTLY OUR IMPACT FEATURES (Evently Theme Only) -->
  <div class="card card-editor p-4 mb-4 border-purple" style="border: 1px solid #6C3CE1;">
    <h5 class="fw-bold mb-2 text-dark">
      <span class="badge text-white me-2 px-2 py-1" style="background: #6C3CE1;"><i class="fa-solid fa-gem me-1"></i> Evently Theme</span>
      'Our Impact' Feature Cards (4 Badges Below Hero)
    </h5>
    <p class="text-muted small mb-3">Edit the 4 feature pill badges on the Evently homepage.</p>

    @php
      $impactFeatures = $agency->impact_features_data ?? [
        ['title' => 'Creative Planning',   'icon' => 'fa-wand-magic-sparkles'],
        ['title' => 'Dedicated Support',   'icon' => 'fa-users-gear'],
        ['title' => 'Customizable Packages', 'icon' => 'fa-box-open'],
        ['title' => 'Seamless Execution',  'icon' => 'fa-heart'],
      ];
    @endphp

    <div class="row g-3">
      @foreach($impactFeatures as $ii => $imp)
        <div class="col-md-3">
          <div class="p-3 border rounded-3 bg-light">
            <div class="fw-bold small text-primary mb-2">Card {{ $ii + 1 }}</div>
            <div class="mb-2">
              <label class="form-label small fw-semibold mb-1">Title</label>
              <input type="text" class="form-control form-control-sm" name="impact_features_data[{{ $ii }}][title]" value="{{ $imp['title'] ?? '' }}">
            </div>
            <div>
              <label class="form-label small fw-semibold mb-1">Icon Class</label>
              <input type="text" class="form-control form-control-sm" name="impact_features_data[{{ $ii }}][icon]" value="{{ $imp['icon'] ?? 'fa-star' }}">
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- Section Heading Settings -->
  <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
    <div class="col-md-4">
      <label class="form-label fw-semibold small">Projects Badge Pill</label>
      <input type="text" class="form-control" name="portfolio_badge" value="{{ $agency->portfolio_badge ?? 'OUR WORK' }}">
    </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Projects Section Title</label>
        <input type="text" class="form-control" name="portfolio_title" value="{{ $agency->portfolio_title ?? 'Featured Projects' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Projects Section Subtitle</label>
        <input type="text" class="form-control" name="portfolio_subtitle" value="{{ $agency->portfolio_subtitle ?? 'Explore some of our latest projects that bring ideas to life with style and functionality.' }}">
      </div>
    </div>

    @php
      $portfolioData = $agency->portfolio_data ?? [
        ['title' => 'Fintech Website Redesign', 'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'E-commerce Skincare Store', 'category' => 'Web Design',   'image' => 'assets/website_builder/wb_card_ecommerce.png'],
        ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Brand Identity Design',    'category' => 'Branding',      'image' => 'assets/website_builder/wb_card_portfolio.png'],
        ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_restaurant.png'],
        ['title' => 'Travel Website',           'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_events.png'],
        ['title' => 'Fitness App Design',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Digital Marketing Campaign','category' => 'Marketing',    'image' => 'assets/website_builder/wb_card_agency.png'],
      ];
    @endphp

    <div class="row g-3" id="portfolioContainerAdmin">
      @foreach($portfolioData as $pi => $port)
        <div class="col-md-3 portfolio-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold small text-success">Project #{{ $pi + 1 }}</div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removePortfolio(this)" title="Remove Project"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Title</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][title]" value="{{ $port['title'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Category Tag</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][category]" value="{{ $port['category'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Upload Project Image</label>
                <input type="file" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][image_file]" accept="image/*">
                <input type="hidden" name="portfolio_data[{{ $pi }}][image]" value="{{ $port['image'] ?? 'assets/website_builder/wb_card_agency.png' }}">
              </div>
              @if(!empty($port['image']))
                <div class="mt-2 d-flex align-items-center gap-2">
                  <span class="small fw-semibold text-muted">Preview:</span>
                  <img src="{{ str_starts_with($port['image'], 'http') ? $port['image'] : asset($port['image']) }}" onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=400&auto=format&fit=crop';" style="height: 38px; width: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1;">
                </div>
              @endif
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removePortfolio(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Project
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save Home Page
  </button>
</form>

<script>
  let serviceCounter = {{ count($servicesData) }};
  function addService() {
    const container = document.getElementById('servicesContainer');
    const col = document.createElement('div');
    col.className = 'col-md-4 service-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-bold small text-success">New Service</div>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeService(this)" title="Remove Service"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">FontAwesome Icon Class</label>
            <input type="text" class="form-control form-control-sm" name="services_data[\${serviceCounter}][icon]" value="fa-chart-line">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Title</label>
            <input type="text" class="form-control form-control-sm" name="services_data[\${serviceCounter}][title]" value="New Service Title">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Description</label>
            <textarea class="form-control form-control-sm" name="services_data[\${serviceCounter}][desc]" rows="2">Custom service description details go here.</textarea>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Upload Service Image</label>
            <input type="file" class="form-control form-control-sm" name="services_data[\${serviceCounter}][image_file]" accept="image/*">
            <input type="hidden" name="services_data[\${serviceCounter}][image]" value="">
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeService(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Service
        </button>
      </div>
    `;
    container.appendChild(col);
    serviceCounter++;
  }

  function removeService(btn) {
    const cardItem = btn.closest('.service-card-item');
    if (cardItem) {
      cardItem.remove();
    }
  }

  let portfolioCounter = {{ count($portfolioData) }};
  function addPortfolio() {
    const container = document.getElementById('portfolioContainerAdmin');
    const col = document.createElement('div');
    col.className = 'col-md-3 portfolio-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-bold small text-success">New Project</div>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removePortfolio(this)" title="Remove Project"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Title</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][title]" value="New Project Title">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Category Tag</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][category]" value="Web Design">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Upload Project Image</label>
            <input type="file" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][image_file]" accept="image/*">
            <input type="hidden" name="portfolio_data[${portfolioCounter}][image]" value="assets/website_builder/wb_card_agency.png">
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removePortfolio(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Project
        </button>
      </div>
    `;
    container.appendChild(col);
    portfolioCounter++;
  }

  function removePortfolio(btn) {
    const cardItem = btn.closest('.portfolio-card-item');
    if (cardItem) {
      cardItem.remove();
    }
  }

  let vehicleCounter = {{ isset($calcVehicles) ? count($calcVehicles) : 0 }};
  function addVehicleType() {
    const container = document.getElementById('vehiclesContainer');
    if (!container) return;
    const col = document.createElement('div');
    col.className = 'col-md-6 vehicle-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-white position-relative shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="fw-bold small text-warning text-dark"><i class="fa-solid fa-car me-1"></i> New Vehicle</div>
          <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeVehicleType(this)" title="Remove Vehicle"><i class="fa-solid fa-trash-can"></i></button>
        </div>
        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label small fw-semibold mb-1">Vehicle Name</label>
            <input type="text" class="form-control form-control-sm" name="fare_calculator_data[vehicles][${vehicleCounter}][name]" value="Mini Cab">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold mb-1">Price Per KM (₹)</label>
            <input type="number" step="0.5" class="form-control form-control-sm fw-bold text-success" name="fare_calculator_data[vehicles][${vehicleCounter}][rate]" value="12">
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold mb-1">Seats Info</label>
            <input type="text" class="form-control form-control-sm" name="fare_calculator_data[vehicles][${vehicleCounter}][seats]" value="4 Seats">
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold mb-1">Bags Info</label>
            <input type="text" class="form-control form-control-sm" name="fare_calculator_data[vehicles][${vehicleCounter}][bags]" value="1 Bag">
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold mb-1">Base Fare (₹)</label>
            <input type="number" step="1" class="form-control form-control-sm" name="fare_calculator_data[vehicles][${vehicleCounter}][base_fare]" value="40">
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-semibold mb-1">Icon (FontAwesome Class)</label>
            <input type="text" class="form-control form-control-sm" name="fare_calculator_data[vehicles][${vehicleCounter}][icon]" value="fa-car">
          </div>
        </div>
      </div>
    `;
    container.appendChild(col);
    vehicleCounter++;
  }

  function removeVehicleType(btn) {
    const cardItem = btn.closest('.vehicle-card-item');
    if (cardItem) {
      cardItem.remove();
    }
  }

  let homeCounterIndex = {{ isset($homeStats) ? count($homeStats) : 4 }};
  function addHomeCounterItem() {
    const container = document.getElementById('homeCounterContainer');
    if (!container) return;
    const col = document.createElement('div');
    col.className = 'col-md-3 home-counter-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between shadow-sm">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-bold small text-primary">New Counter</div>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeHomeCounterItem(this)" title="Remove Counter"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Number / Count</label>
            <input type="text" class="form-control form-control-sm fw-bold" name="stats_data[\${homeCounterIndex}][number]" value="100+">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Label / Title</label>
            <input type="text" class="form-control form-control-sm" name="stats_data[\${homeCounterIndex}][label]" value="Happy Clients">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Icon Class</label>
            <input type="text" class="form-control form-control-sm" name="stats_data[\${homeCounterIndex}][icon]" value="fa-users">
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeHomeCounterItem(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Item
        </button>
      </div>
    `;
    container.appendChild(col);
    homeCounterIndex++;
  }

  function removeHomeCounterItem(btn) {
    const cardItem = btn.closest('.home-counter-item');
    if (cardItem) {
      cardItem.remove();
    }
  }
</script>

@endsection
