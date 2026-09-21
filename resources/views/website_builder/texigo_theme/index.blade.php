@extends('website_builder.texigo_theme.layout')

@section('title', 'TaxiGo - #1 Trusted Taxi & Cab Mobility Service')

@section('content')

<style>
  @media (min-width: 992px) {
    .tx-srv-card-wrap, .tx-service-slide-card, .tx-tst-card-wrap {
      flex: 0 0 calc(25% - 18px) !important;
      width: calc(25% - 18px) !important;
      min-width: calc(25% - 18px) !important;
      max-width: calc(25% - 18px) !important;
    }
  }
  @media (max-width: 767.98px) {
    .tx-srv-card-wrap, .tx-service-slide-card, .tx-srv-track > * {
      flex: 0 0 100% !important;
      width: 100% !important;
      min-width: 100% !important;
      max-width: 100% !important;
      scroll-snap-align: center !important;
    }
  }
</style>


@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl    = $subdomainParam ? route('website-builder.subdomain.site',    ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
  $aboutUrl   = $subdomainParam ? route('website-builder.subdomain.about',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.contact');
  $agency     = $agency ?? $interior ?? null;
@endphp

{{-- =====================================================================
     HERO SECTION (Reference Image 1 – full background, left-side content)
     ===================================================================== --}}
@php $heroBannerBg = asset('assets/website_builder/Templates/Texigo_agency/herobanner_image.png'); @endphp

<section class="tx-hero" style="background: url('{{ $heroBannerBg }}') no-repeat center center / cover; min-height: 540px; position: relative;">
  <!-- Left dark gradient overlay so text is readable -->
  <div class="tx-hero-overlay"></div>
  <div class="tx-container py-5" style="position:relative;z-index:2;">
    <div class="row align-items-center" style="min-height:480px;">
      <div class="col-lg-6 py-4">
        <span class="tx-pill-badge">
          {{ $agency->hero_badge ?? '🚖 #1 Trusted Taxi Service' }}
        </span>
        <h1 class="tx-heading tx-hero-title mb-3">
          {!! nl2br(e($agency->hero_title ?? "Your Journey\nOur Priority")) !!}
        </h1>
        <p class="tx-hero-subtitle">
          {{ $agency->hero_subtitle ?? 'Reliable. Safe. Affordable. Get where you need to go with comfort and peace of mind.' }}
        </p>

        <!-- CTA Buttons -->
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
          <a href="{{ $agency->primary_btn_url ?? $contactUrl }}" class="tx-btn tx-btn-yellow px-5 py-3 fw-bold fs-6">
            {{ $agency->primary_btn_text ?? 'Book Your Ride' }} <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="{{ $agency->secondary_btn_url ?? '#services' }}" class="tx-btn tx-btn-outline-dark px-5 py-3 fw-bold fs-6">
            {{ $agency->secondary_btn_text ?? 'Explore Services' }}
          </a>
        </div>

        <!-- 3 Trust Badges -->
        <div class="d-flex align-items-center gap-4 pt-2 flex-wrap">
          <div class="d-flex align-items-center gap-2">
            <div class="tx-hero-badge-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div>
              <div style="font-size:12px;font-weight:700;color:var(--tx-text-dark);line-height:1.2;">{{ $agency->hero_bullet_1_title ?? 'Safe &' }}</div>
              <div style="font-size:11px;font-weight:500;color:var(--tx-text-muted);">{{ $agency->hero_bullet_1_text ?? 'Secure Rides' }}</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="tx-hero-badge-icon"><i class="fa-solid fa-headset"></i></div>
            <div>
              <div style="font-size:12px;font-weight:700;color:var(--tx-text-dark);line-height:1.2;">{{ $agency->hero_bullet_2_title ?? '24/7' }}</div>
              <div style="font-size:11px;font-weight:500;color:var(--tx-text-muted);">{{ $agency->hero_bullet_2_text ?? 'Customer Support' }}</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="tx-hero-badge-icon"><i class="fa-solid fa-calculator"></i></div>
            <div>
              <div style="font-size:12px;font-weight:700;color:var(--tx-text-dark);line-height:1.2;">{{ $agency->hero_bullet_3_title ?? 'Affordable' }}</div>
              <div style="font-size:11px;font-weight:500;color:var(--tx-text-muted);">{{ $agency->hero_bullet_3_text ?? '& Transparent Pricing' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- =====================================================================
     TAXI FARE CALCULATOR SECTION
     ===================================================================== --}}
@php
  $calcBadge = $agency->fare_calculator_data['badge'] ?? 'CAB FARE CALCULATOR';
  $calcTitle = $agency->fare_calculator_data['title'] ?? 'Estimate Your Trip Fare';
  $calcSubtitle = $agency->fare_calculator_data['subtitle'] ?? 'Instant, transparent pricing with no hidden charges. Select your route and vehicle.';
  $calcVehicles = $agency->fare_calculator_data['vehicles'] ?? [
    ['name' => 'Sedan',     'rate' => 20, 'base_fare' => 50, 'seats' => '4 Seats', 'bags' => '3 Bags', 'icon' => 'fa-car'],
    ['name' => 'SUV',       'rate' => 30, 'base_fare' => 50, 'seats' => '6 Seats', 'bags' => '4 Bags', 'icon' => 'fa-truck-monster'],
    ['name' => 'Premium',   'rate' => 50, 'base_fare' => 50, 'seats' => '4 Seats', 'bags' => '3 Bags', 'icon' => 'fa-crown'],
    ['name' => 'Hatchback', 'rate' => 15, 'base_fare' => 50, 'seats' => '4 Seats', 'bags' => '2 Bags', 'icon' => 'fa-car-side'],
  ];
@endphp

<section id="fare-calculator" class="tx-fare-calc-section">
  <div class="tx-container">
    <div class="tx-fare-calc-card">
      <div class="text-center mb-4">
        <span class="tx-pill-badge" style="background: #FFF8E6; color: #945B00;">
          <i class="fa-solid fa-calculator me-1"></i> {{ $calcBadge }}
        </span>
        <h2 class="tx-heading mb-2" style="font-size: clamp(24px, 3vw, 36px);">{{ $calcTitle }}</h2>
        <p class="text-muted small mb-0" style="font-size: 14px;">{{ $calcSubtitle }}</p>
      </div>

      <div class="row g-4 align-items-stretch">
        <!-- Left: Route Inputs & Vehicle Selection -->
        <div class="col-lg-7">
          <!-- Locations -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold text-dark small mb-1">Pickup Location</label>
              <div class="tx-fare-input-group">
                <i class="fa-solid fa-location-dot tx-fare-input-icon text-success"></i>
                <input type="text" id="txCalcPickup" class="form-control tx-fare-input" placeholder="Enter pickup address..." value="City Center Mall" oninput="calculateFare()">
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold text-dark small mb-1">Drop-off Location</label>
              <div class="tx-fare-input-group">
                <i class="fa-solid fa-location-crosshairs tx-fare-input-icon text-danger"></i>
                <input type="text" id="txCalcDrop" class="form-control tx-fare-input" placeholder="Enter destination address..." value="International Airport" oninput="calculateFare()">
              </div>
            </div>

            <div class="col-12">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <label class="form-label fw-bold text-dark small mb-0">Estimated Distance (KM)</label>
                <span class="text-muted x-small" style="font-size: 11px;"><i class="fa-solid fa-info-circle me-1"></i>Auto-calculated route distance</span>
              </div>
              <div class="tx-fare-input-group">
                <i class="fa-solid fa-route tx-fare-input-icon text-warning"></i>
                <input type="number" id="txCalcDistance" class="form-control tx-fare-input" value="15" min="1" max="1000" oninput="calculateFare()">
              </div>
            </div>
          </div>

          <!-- Vehicle Type Selector -->
          <label class="form-label fw-bold text-dark small mb-2">Select Vehicle Type</label>
          <div class="row g-2" id="txVehicleCardsContainer">
            @foreach($calcVehicles as $idx => $v)
              @php
                $vRate = $v['rate'] ?? $v['price_per_km'] ?? 20;
                $vBase = $v['base_fare'] ?? $v['base'] ?? 50;
                $vSeats = $v['seats'] ?? '4 Seats';
                $vBags = $v['bags'] ?? '2 Bags';
                $vName = $v['name'] ?? 'Vehicle';
                $vIcon = $v['icon'] ?? 'fa-car';
              @endphp
              <div class="col-6 col-md-3">
                <div class="tx-vehicle-select-card {{ $idx === 0 ? 'active' : '' }}" 
                     data-id="{{ $vName }}" 
                     data-name="{{ $vName }}" 
                     data-rate="{{ $vRate }}" 
                     data-base="{{ $vBase }}"
                     onclick="selectVehicle(this)">
                  <div class="d-flex align-items-center justify-content-between mb-1">
                    <i class="fa-solid {{ $vIcon }} fs-5 text-dark"></i>
                    <span class="badge rounded-pill bg-warning text-dark fw-bold" style="font-size: 10px;">₹{{ $vRate }}/km</span>
                  </div>
                  <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ $vName }}</div>
                  <div class="text-muted" style="font-size: 10.5px;">{{ $vSeats }} · {{ $vBags }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Right: Fare Summary Card -->
        <div class="col-lg-5">
          <div class="tx-fare-summary-box h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center justify-content-between border-bottom border-secondary pb-3 mb-3">
                <span class="text-uppercase fw-bold text-warning small" style="letter-spacing: 1px;">FARE SUMMARY</span>
                <span class="badge bg-secondary text-light fw-normal" style="font-size: 11px;">Instant Estimate</span>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-2 text-white-50 small">
                <span>Trip Distance</span>
                <span class="text-white fw-bold" id="txSummaryDistance">15 KM</span>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-2 text-white-50 small">
                <span>Selected Vehicle</span>
                <span class="text-white fw-bold" id="txSummaryVehicle">Sedan</span>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-2 text-white-50 small">
                <span>Base Fare</span>
                <span class="text-white fw-bold" id="txSummaryBase">₹50</span>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-3 text-white-50 small">
                <span>Distance Charge</span>
                <span class="text-white fw-bold" id="txSummaryDistCharge">₹300</span>
              </div>

              <div class="pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                <div>
                  <div class="text-white-50 small">Total Estimated Fare</div>
                  <div class="text-warning fw-extrabold display-6 mb-0" id="txSummaryTotal">₹350</div>
                </div>
                <i class="fa-solid fa-calculator text-warning fs-1 opacity-25"></i>
              </div>
            </div>

            <div class="pt-4">
              <a href="{{ $contactUrl }}" id="txBookRideBtn" class="tx-btn tx-btn-yellow w-100 py-3 fw-bold fs-6 text-center text-decoration-none d-block">
                Book This Ride <i class="fa-solid fa-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- =====================================================================
     ABOUT US SECTION
     ===================================================================== --}}
<section id="about" style="background:#ffffff; padding:36px 0;">
  <div class="tx-container">
    <div class="row g-5 align-items-start">

      {{-- LEFT COLUMN --}}
      <div class="col-lg-5">
        <span class="tx-pill-badge">{{ $agency->about_badge ?? 'ABOUT US' }}</span>
        <h2 class="tx-heading mb-4" style="font-size:clamp(28px,3.5vw,40px);line-height:1.15;">
          {!! nl2br(e($agency->about_hero_title ?? "More Than Just a Ride\nWe Drive Better Journeys")) !!}
        </h2>
        <p class="mb-4" style="color:var(--tx-text-muted);line-height:1.75;font-size:14.5px;max-width:380px;">
          {{ $agency->about_hero_subtitle ?? 'TaxiGo is committed to providing safe, reliable, and comfortable transportation for everyone.' }}
        </p>
        <a href="{{ $aboutUrl }}" class="tx-btn tx-btn-yellow px-4 py-3 fw-bold">
          {{ $agency->about_primary_btn_text ?? 'Learn More' }} <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>

      {{-- RIGHT COLUMN — 3 equal cards --}}
      <div class="col-lg-7">
        <div class="row g-3 h-100">

          {{-- Mission card --}}
          <div class="col-6 col-md-4">
            <div class="tx-mvv-card h-100">
              <div class="tx-mvv-icon">
                <i class="fa-solid fa-bullseye" style="color:var(--tx-primary-dark);font-size:22px;"></i>
              </div>
              <h3 class="tx-mvv-title">{{ $agency->mission_title ?? 'Our Mission' }}</h3>
              <p class="tx-mvv-desc">{{ $agency->mission_text ?? 'To provide safe, reliable, and convenient rides for everyone, everywhere.' }}</p>
            </div>
          </div>

          {{-- Vision card --}}
          <div class="col-6 col-md-4">
            <div class="tx-mvv-card h-100">
              <div class="tx-mvv-icon">
                <i class="fa-regular fa-eye" style="color:var(--tx-primary-dark);font-size:22px;"></i>
              </div>
              <h3 class="tx-mvv-title">{{ $agency->vision_title ?? 'Our Vision' }}</h3>
              <p class="tx-mvv-desc">{{ $agency->vision_text ?? 'To be the most trusted global mobility platform, connecting people and places.' }}</p>
            </div>
          </div>

          {{-- Values card --}}
          <div class="col-12 col-md-4">
            <div class="tx-mvv-card h-100">
              <div class="tx-mvv-icon">
                <i class="fa-solid fa-gem" style="color:var(--tx-primary-dark);font-size:22px;"></i>
              </div>
              <h3 class="tx-mvv-title">{{ $agency->values_title ?? 'Our Values' }}</h3>
              @php
                $valText = $agency->values_text ?? 'Customer First, Safety & Reliability, Integrity & Transparency, Innovation, Sustainable Mobility';
                $valItems = array_map('trim', explode(',', $valText));
              @endphp
              <ul class="list-unstyled mb-0" style="font-size:12.5px;line-height:1.85;color:var(--tx-text-muted);">
                @foreach($valItems as $vi)
                  <li><i class="fa-solid fa-circle-check text-warning me-1" style="font-size:11px;"></i> {{ $vi }}</li>
                @endforeach
              </ul>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

{{-- =====================================================================
     DARK STATS BAR
     ===================================================================== --}}
@php
  $stats = $agency->stats_data ?? [
    ['number' => '8+',    'label' => 'Years of Experience',   'icon' => 'fa-users'],
    ['number' => '250K+', 'label' => 'Rides Completed',       'icon' => 'fa-car'],
    ['number' => '98%',   'label' => 'Customer Satisfaction', 'icon' => 'fa-star'],
    ['number' => '50+',   'label' => 'Professional Drivers',  'icon' => 'fa-user-tie'],
  ];
@endphp

<div class="tx-container">
  <div class="tx-dark-stats-bar">
    <div class="row g-0 align-items-center">
      @foreach($stats as $i => $st)
        <div class="col-6 col-lg-3">
          <div class="tx-stat-item {{ $i < count($stats)-1 ? 'tx-stat-border' : '' }}">
            <div class="tx-stat-icon">
              <i class="fa-solid {{ $st['icon'] ?? 'fa-car' }}"></i>
            </div>
            <div>
              <div class="tx-stat-num tx-counter-num" data-target="{{ $st['number'] ?? '' }}">{{ $st['number'] ?? '' }}</div>
              <div class="tx-stat-label">{{ $st['label'] ?? '' }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- =====================================================================
     OUR SERVICES SECTION
     ===================================================================== --}}
<section id="services" style="background:#ffffff; padding:36px 0;">
  <div class="tx-container">

    {{-- Section header --}}
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <span class="tx-pill-badge">{{ $agency->services_badge ?? 'OUR SERVICES' }}</span>
        <h2 class="tx-heading mb-2" style="font-size:clamp(26px,3.2vw,38px);">{{ $agency->services_title ?? 'Ride for Every Occasion' }}</h2>
        <p style="color:var(--tx-text-muted);font-size:14.5px;margin:0;">{{ $agency->services_subtitle ?? 'From daily commutes to special trips, we have the right ride for you.' }}</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ $contactUrl }}" class="tx-btn tx-btn-outline-dark px-4 py-2" style="font-size:13.5px;font-weight:700;border-width:1.5px;">
          View All Services <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <button id="srvPrevBtn" class="tx-slider-btn"><i class="fa-solid fa-chevron-left"></i></button>
        <button id="srvNextBtn" class="tx-slider-btn"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $services = $agency->services_data ?? [
        ['title'=>'City Rides',               'desc'=>'Quick and affordable rides within the city.',           'image'=>'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-city'],
        ['title'=>'Airport Transfers',        'desc'=>'On-time pickups and drop-offs guaranteed.',              'image'=>'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-plane-departure'],
        ['title'=>'Luxury Chauffeur Service', 'desc'=>'Premium high-end vehicles with professional drivers.',  'image'=>'assets/website_builder/Templates/Texigo_agency/service_luxury.png', 'icon'=>'fa-user-tie'],
        ['title'=>'Outstation Trips',         'desc'=>'Comfortable rides to intercity destinations.',          'image'=>'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-route'],
        ['title'=>'Corporate Travel',         'desc'=>'Reliable mobility solutions for business pros.',         'image'=>'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-briefcase'],
        ['title'=>'Express Parcel Delivery',  'desc'=>'Fast, secure same-day parcel courier service.',         'image'=>'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-box'],
        ['title'=>'Wedding & Event Fleet',    'desc'=>'Luxury convoy arrangements for weddings and events.',   'image'=>'assets/website_builder/Templates/Texigo_agency/service_chauffeur.png', 'icon'=>'fa-heart'],
        ['title'=>'VIP Escort & Security',    'desc'=>'Armored luxury vehicles with trained security drivers.', 'image'=>'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-shield-halved'],
      ];
    @endphp

    {{-- Slider track: overflows horizontally, scrolled by JS --}}
    <div class="tx-srv-slider-wrap">
      <div class="tx-srv-track d-flex gap-3 overflow-auto py-2" id="srvSliderTrack" style="scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;">
        @foreach($services as $srv)
        <div class="tx-srv-card-wrap flex-shrink-0" style="flex: 0 0 calc(25% - 18px); min-width: 270px; max-width: 310px;">
          <div class="tx-srv-card" style="transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);" onmouseover="this.style.transform='translateY(-6px) scale(1.02)';" onmouseout="this.style.transform='none';">
            {{-- Tall image --}}
            <div class="tx-srv-img" style="overflow: hidden;">
              <img src="{{ str_starts_with($srv['image'] ?? '', 'http') ? $srv['image'] : asset(ltrim($srv['image'] ?? '', '/')) }}"
                   alt="{{ $srv['title'] ?? '' }}" loading="lazy" style="transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.06)';" onmouseout="this.style.transform='none';">
            </div>
            {{-- Info footer --}}
            <div class="tx-srv-info">
              <div class="d-flex align-items-start gap-2 flex-grow-1">
                <div class="tx-srv-icon">
                  <i class="fa-solid {{ $srv['icon'] ?? 'fa-taxi' }}"></i>
                </div>
                <div>
                  <h3 class="tx-srv-title">{{ $srv['title'] ?? '' }}</h3>
                  <p class="tx-srv-desc">{{ $srv['desc'] ?? '' }}</p>
                </div>
              </div>
              <a href="{{ $contactUrl }}" class="tx-srv-arrow-btn" aria-label="View service">
                <i class="fa-solid fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

  </div>
</section>

{{-- =====================================================================
     FLEET SECTION
     ===================================================================== --}}
<section id="fleet" style="background:#F8F9FA; padding:36px 0;">
  <div class="tx-container">

    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <span class="tx-pill-badge">{{ $agency->portfolio_badge ?? 'OUR FLEET' }}</span>
        <h2 class="tx-heading mb-2" style="font-size:clamp(26px,3.2vw,38px);">{{ $agency->portfolio_title ?? 'Choose Your Perfect Ride' }}</h2>
        <p style="color:var(--tx-text-muted);font-size:14.5px;margin:0;">{{ $agency->portfolio_subtitle ?? 'A wide range of vehicles to suit your needs and budget.' }}</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ $contactUrl }}" class="tx-btn tx-btn-outline-dark px-4 py-2" style="font-size:13.5px;font-weight:700;border-width:1.5px;">
          View All Vehicles <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <button id="fleetPrevBtn" class="tx-slider-btn"><i class="fa-solid fa-chevron-left"></i></button>
        <button id="fleetNextBtn" class="tx-slider-btn"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $fleet = $agency->portfolio_data ?? [
        ['title'=>'Hatchback','seats'=>'4 Seats','bags'=>'2 Bags','image'=>'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=800&auto=format&fit=crop'],
        ['title'=>'Sedan',    'seats'=>'4 Seats','bags'=>'3 Bags','image'=>'https://images.unsplash.com/photo-1550355291-bbee04a92027?q=80&w=800&auto=format&fit=crop'],
        ['title'=>'SUV',      'seats'=>'6 Seats','bags'=>'4 Bags','image'=>'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=800&auto=format&fit=crop'],
        ['title'=>'Premium',  'seats'=>'4 Seats','bags'=>'3 Bags','image'=>'https://images.unsplash.com/photo-1563720223185-11003d516935?q=80&w=800&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-2 g-md-4" id="fleetGridContainer">
      @foreach($fleet as $fl)
      <div class="col-6 col-lg-3">
        <div class="tx-fleet-card">
          <div class="tx-fleet-img-wrap">
            <img src="{{ str_starts_with($fl['image'] ?? '', 'http') ? $fl['image'] : asset(ltrim($fl['image'] ?? '', '/')) }}"
                 alt="{{ $fl['title'] ?? '' }}" loading="lazy">
          </div>
          <h3 class="tx-fleet-name">{{ $fl['title'] ?? '' }}</h3>
          <div class="tx-fleet-meta">
            <span><i class="fa-solid fa-user me-1"></i>{{ $fl['seats'] ?? '4 Seats' }}</span>
            <span><i class="fa-solid fa-suitcase me-1"></i>{{ $fl['bags'] ?? '2 Bags' }}</span>
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</section>

{{-- =====================================================================
     TESTIMONIALS SECTION
     ===================================================================== --}}
<section id="testimonials" style="background:#ffffff; padding:36px 0;">
  <div class="tx-container">

    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <span class="tx-pill-badge">{{ $agency->testimonials_badge ?? 'TESTIMONIALS' }}</span>
        <h2 class="tx-heading mb-2" style="font-size:clamp(26px,3.2vw,38px);">{{ $agency->testimonials_title ?? 'What Our Customers Say' }}</h2>
        <p style="color:var(--tx-text-muted);font-size:14.5px;margin:0;">{{ $agency->testimonials_subtitle ?? 'Real stories from people who ride with TaxiGo every day.' }}</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <button id="tstPrevBtn" class="tx-slider-btn"><i class="fa-solid fa-chevron-left"></i></button>
        <button id="tstNextBtn" class="tx-slider-btn"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>


    @php
      $testimonials = $agency->testimonials_data ?? [
        ['name'=>'Emily Carter', 'role'=>'Frequent Traveler',  'comment'=>'TaxiGo made my airport transfer so easy and stress-free. Highly recommended!',         'avatar'=>'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'],
        ['name'=>'James Walker', 'role'=>'Business Executive', 'comment'=>'Reliable, professional, and affordable. The best taxi service in the city!',            'avatar'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'],
        ['name'=>'Sophia Lee',   'role'=>'Regular Customer',   'comment'=>'Great service and very friendly drivers. I always choose TaxiGo!',                      'avatar'=>'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop'],
      ];
    @endphp

    <div class="d-flex gap-3 overflow-auto py-2 testimonial-scroll-track" id="tstSliderTrack" style="scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;">
      @foreach($testimonials as $t)
      <div class="tx-tst-card-wrap flex-shrink-0" style="width: calc((100% - 32px) / 3); min-width: 280px;">
        <div class="tx-tst-card">
          {{-- Large yellow quote mark --}}
          <div class="tx-tst-quote"><i class="fa-solid fa-quote-left"></i></div>
          {{-- Review text --}}
          <p class="tx-tst-text">"{{ $t['comment'] ?? '' }}"</p>
          {{-- Stars --}}
          <div class="tx-tst-stars">
            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          {{-- Author --}}
          <div class="tx-tst-author">
            <img src="{{ str_starts_with($t['avatar'] ?? '', 'http') ? $t['avatar'] : asset(ltrim($t['avatar'] ?? '', '/')) }}"
                 alt="{{ $t['name'] ?? '' }}" class="tx-tst-avatar">
            <div>
              <div class="tx-tst-name">{{ $t['name'] ?? '' }}</div>
              <div class="tx-tst-role">{{ $t['role'] ?? '' }}</div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Universal slider setup
  function setupSlider(trackId, prevId, nextId) {
    const track = document.getElementById(trackId);
    const prev  = document.getElementById(prevId);
    const next  = document.getElementById(nextId);
    if (!track) return;

    function getStep() {
      const firstChild = track.firstElementChild;
      return firstChild ? firstChild.offsetWidth + parseInt(getComputedStyle(track).gap || 0) : 300;
    }

    if (prev) prev.addEventListener('click', () => track.scrollBy({ left: -getStep(), behavior: 'smooth' }));
    if (next) next.addEventListener('click', () => track.scrollBy({ left:  getStep(), behavior: 'smooth' }));

    // Auto-slide
    let timer = setInterval(() => {
      const maxScroll = track.scrollWidth - track.clientWidth;
      if (maxScroll > 0) {
        track.scrollLeft >= maxScroll - 5
          ? track.scrollTo({ left: 0, behavior: 'smooth' })
          : track.scrollBy({ left: getStep(), behavior: 'smooth' });
      }
    }, 4000);

    track.addEventListener('touchstart',  () => clearInterval(timer), { passive: true });
    track.addEventListener('touchend',    () => {
      timer = setInterval(() => {
        const maxScroll = track.scrollWidth - track.clientWidth;
        if (maxScroll > 0) {
          track.scrollLeft >= maxScroll - 5
            ? track.scrollTo({ left: 0, behavior: 'smooth' })
            : track.scrollBy({ left: getStep(), behavior: 'smooth' });
        }
      }, 4000);
    }, { passive: true });
  }

  setupSlider('srvSliderTrack',   'srvPrevBtn',   'srvNextBtn');
  setupSlider('fleetSliderTrack', 'fleetPrevBtn', 'fleetNextBtn');
  setupSlider('tstSliderTrack',   'tstPrevBtn',   'tstNextBtn');

  // Taxi Fare Calculator Logic
  window.selectVehicle = function(el) {
    document.querySelectorAll('.tx-vehicle-select-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    calculateFare();
  };

  window.calculateFare = function() {
    const activeCard = document.querySelector('.tx-vehicle-select-card.active') || document.querySelector('.tx-vehicle-select-card');
    if (!activeCard) return;

    const rate = parseFloat(activeCard.getAttribute('data-rate') || 20);
    const base = parseFloat(activeCard.getAttribute('data-base') || 50);
    const vName = activeCard.getAttribute('data-name') || 'Sedan';

    const distInput = document.getElementById('txCalcDistance');
    let dist = parseFloat(distInput ? distInput.value : 15);
    if (isNaN(dist) || dist < 1) dist = 1;

    const distCharge = dist * rate;
    const totalFare = base + distCharge;

    const pickup = (document.getElementById('txCalcPickup')?.value || '').trim();
    const drop = (document.getElementById('txCalcDrop')?.value || '').trim();

    // Update summary UI
    const summaryDist = document.getElementById('txSummaryDistance');
    const summaryVehicle = document.getElementById('txSummaryVehicle');
    const summaryBase = document.getElementById('txSummaryBase');
    const summaryDistCharge = document.getElementById('txSummaryDistCharge');
    const summaryTotal = document.getElementById('txSummaryTotal');

    if (summaryDist) summaryDist.innerText = dist + ' KM';
    if (summaryVehicle) summaryVehicle.innerText = vName + ' (₹' + rate + '/km)';
    if (summaryBase) summaryBase.innerText = '₹' + Math.round(base);
    if (summaryDistCharge) summaryDistCharge.innerText = '₹' + Math.round(distCharge);
    if (summaryTotal) summaryTotal.innerText = '₹' + Math.round(totalFare);

    // Update CTA button link
    const bookBtn = document.getElementById('txBookRideBtn');
    if (bookBtn) {
      const baseUrl = "{{ $contactUrl }}";
      const params = new URLSearchParams({
        pickup: pickup,
        drop: drop,
        distance: dist,
        vehicle: vName,
        fare: Math.round(totalFare)
      });
      bookBtn.href = baseUrl + '?' + params.toString();
    }
  };

  calculateFare();
});
</script>
@endsection
