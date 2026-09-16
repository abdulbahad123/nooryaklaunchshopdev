@extends('website_builder.texigo_theme.layout')

@section('no_cta')@endsection

@section('title', 'Portfolio & Services - ' . ($agency->site_title ?? 'TaxiGo Mobility'))

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.portfolio');
  $agency = $agency ?? $interior ?? null;
@endphp

<!-- ===== HERO SECTION (MATCHING REF IMAGE 2) ===== -->
@php
  $heroBannerBg = asset('assets/website_builder/Templates/Texigo_agency/herobanner_image.png');
@endphp

<section class="tx-hero" style="background: url('{{ $heroBannerBg }}') no-repeat center right / cover; min-height: 520px; position: relative;">
  <div class="tx-container py-4">
    <div class="row align-items-center">
      <!-- Left Content -->
      <div class="col-lg-6 py-3">
        <span class="tx-pill-badge" style="background: #FFF8E6; color: #945B00;">
          OUR FLEET
        </span>
        <h1 class="tx-heading tx-hero-title">
          Rides For<br><span style="color: var(--tx-primary);">Every Moment</span>
        </h1>
        <p class="tx-hero-subtitle">
          Safe. Reliable. Affordable. Get where you need to go with comfort and peace of mind.
        </p>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-2 gap-sm-3 mb-4 w-100 flex-wrap">
          <a href="{{ $contactUrl }}" class="tx-btn tx-btn-yellow px-4 py-3 fw-bold">
            Book Your Ride <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="#services-grid" class="tx-btn tx-btn-outline-dark px-4 py-3 fw-bold">
            Our Services
          </a>
        </div>

        <!-- 3 Feature Badges Below Buttons -->
        <div class="d-flex align-items-center gap-3 pt-3 flex-wrap">
          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #FFF8E6; color: var(--tx-primary-dark); font-size: 16px;">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div style="font-size: 12px; font-weight: 700; color: var(--tx-text-dark); line-height: 1.2;">
              Safe &<br><span class="text-muted fw-semibold" style="font-size: 11px;">Secure Rides</span>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #FFF8E6; color: var(--tx-primary-dark); font-size: 16px;">
              <i class="fa-solid fa-headset"></i>
            </div>
            <div style="font-size: 12px; font-weight: 700; color: var(--tx-text-dark); line-height: 1.2;">
              24/7<br><span class="text-muted fw-semibold" style="font-size: 11px;">Customer Support</span>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #FFF8E6; color: var(--tx-primary-dark); font-size: 16px;">
              <i class="fa-solid fa-calculator"></i>
            </div>
            <div style="font-size: 12px; font-weight: 700; color: var(--tx-text-dark); line-height: 1.2;">
              Affordable<br><span class="text-muted fw-semibold" style="font-size: 11px;">& Transparent Pricing</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== SERVICES FILTER TABS & SEARCH BAR ===== -->
<section id="services-grid" class="py-5" style="background: #ffffff;">
  <div class="tx-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <!-- Filter Pills -->
      <div class="d-flex align-items-center gap-2 flex-wrap" id="txFilterTrack" style="overflow-x: auto; padding-bottom: 6px;">
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold active-filter" data-filter="all" onclick="filterServices('all', this)" style="background: var(--tx-primary); color: #0D0F12;">All Services</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="city" onclick="filterServices('city', this)">City Rides</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="airport" onclick="filterServices('airport', this)">Airport Transfer</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="outstation" onclick="filterServices('outstation', this)">Outstation</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="corporate" onclick="filterServices('corporate', this)">Corporate</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="hourly" onclick="filterServices('hourly', this)">Hourly Rental</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="luxury" onclick="filterServices('luxury', this)">Luxury</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="parcel" onclick="filterServices('parcel', this)">Parcel Delivery</button>
      </div>

      <!-- Search Input -->
      <div class="position-relative" style="width: 240px;">
        <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 13px;"></i>
        <input type="text" id="txSearchInput" class="form-control form-control-sm rounded-pill ps-5 py-2 border" placeholder="Search services..." onkeyup="searchServices(this.value)">
      </div>
    </div>

    <!-- 9 SERVICES GRID (3 PER ROW) -->
    @php
      $allServices = [
        ['category' => 'city',       'title' => 'City Rides',            'desc' => 'Quick and affordable rides within your city.',          'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_city_rides.png'), 'icon' => 'fa-city'],
        ['category' => 'airport',    'title' => 'Airport Transfers',     'desc' => 'On-time pickups and drop-offs for airport travel.',     'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_airport_transfers.png'), 'icon' => 'fa-plane-departure'],
        ['category' => 'outstation', 'title' => 'Outstation Trips',      'desc' => 'Comfortable rides to any destination.',                 'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_outstation_trips.png'), 'icon' => 'fa-route'],
        ['category' => 'corporate',  'title' => 'Corporate Travel',      'desc' => 'Reliable rides for business professionals.',            'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_corporate_travel.png'), 'icon' => 'fa-briefcase'],
        ['category' => 'parcel',     'title' => 'Parcel Delivery',       'desc' => 'Fast and secure delivery service.',                     'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_parcel_delivery.png'), 'icon' => 'fa-box'],
        ['category' => 'luxury',     'title' => 'Premium Rides',         'desc' => 'Experience luxury on every journey.',                   'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_premium_rides.png'), 'icon' => 'fa-crown'],
        ['category' => 'hourly',     'title' => 'Hourly Rental',         'desc' => 'Flexible rental options for your convenience.',         'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_hourly_rental.png'), 'icon' => 'fa-clock'],
        ['category' => 'city',       'title' => 'Family Rides',          'desc' => 'Spacious and comfortable rides for your loved ones.',   'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_family_rides.png'), 'icon' => 'fa-users'],
        ['category' => 'corporate',  'title' => 'Event & Special Rides', 'desc' => 'Hassle-free travel for every occasion.',                'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_event_special_rides.png'), 'icon' => 'fa-calendar-star'],
      ];
    @endphp

    <div class="row g-4" id="txServicesCardsContainer">
      @foreach($allServices as $srv)
        <div class="col-12 col-md-6 col-lg-4 tx-service-card-item" data-category="{{ $srv['category'] }}" data-title="{{ strtolower($srv['title']) }}">
          <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm bg-white p-2">
            <div class="position-relative rounded-3 overflow-hidden mb-2" style="height: 180px;">
              <img src="{{ str_starts_with($srv['image'] ?? '', 'http') ? ($srv['image'] ?? '') : asset(ltrim($srv['image'] ?? '', '/')) }}" alt="{{ $srv['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="p-3 d-flex flex-column h-100">
              <div class="d-flex align-items-center gap-2 mb-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-dark flex-shrink-0" style="width: 34px; height: 34px; background: var(--tx-primary); font-size: 15px;">
                  <i class="fa-solid {{ $srv['icon'] }}"></i>
                </div>
                <h3 class="fw-bold mb-0 text-dark" style="font-size: 16px;">{{ $srv['title'] }}</h3>
              </div>
              <p class="text-muted small mb-3 flex-grow-1" style="font-size: 13px; line-height: 1.5;">{{ $srv['desc'] }}</p>
              <a href="{{ $contactUrl }}" class="btn btn-dark rounded-circle p-0 ms-auto d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="fa-solid fa-arrow-right" style="font-size: 12px; color: #ffffff;"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@section('scripts')
<script>
  function filterServices(category, btnEl) {
    document.querySelectorAll('#txFilterTrack button').forEach(b => {
      b.classList.remove('active-filter');
      b.className = 'btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border';
    });
    btnEl.className = 'btn btn-sm rounded-pill px-3 py-2 fw-bold active-filter';
    btnEl.style.background = 'var(--tx-primary)';
    btnEl.style.color = '#0D0F12';

    const cards = document.querySelectorAll('.tx-service-card-item');
    cards.forEach(card => {
      if (category === 'all' || card.getAttribute('data-category') === category) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function searchServices(query) {
    const q = query.toLowerCase().trim();
    const cards = document.querySelectorAll('.tx-service-card-item');
    cards.forEach(card => {
      const title = card.getAttribute('data-title') || '';
      if (title.includes(q)) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }
</script>
@endsection

@endsection
