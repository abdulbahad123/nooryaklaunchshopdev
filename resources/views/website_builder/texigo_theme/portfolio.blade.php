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
  $defaultTexBg = asset('assets/website_builder/Templates/Texigo_agency/herobanner_image.png');
  $portHeroImg = $agency->portfolio_hero_image ?? $agency->hero_image ?? '';
  $heroBannerBg = !empty($portHeroImg)
    ? (str_starts_with($portHeroImg, 'http') ? $portHeroImg : asset(ltrim($portHeroImg, '/')))
    : $defaultTexBg;
@endphp

<section class="tx-hero" style="background: url('{{ $heroBannerBg }}') no-repeat center right / cover; min-height: 480px; position: relative;">
  <div class="tx-hero-overlay"></div>
  <div class="tx-container py-4" style="position: relative; z-index: 2;">
    <div class="text-center" style="max-width: 750px; margin: 0 auto;">
      <span class="tx-pill-badge mb-3" style="background: #FFF8E6; color: #945B00;">
        {{ $agency->portfolio_badge ?? 'OUR FLEET' }}
      </span>
      <h1 class="tx-heading tx-hero-title mb-3">
        {!! nl2br(e($agency->portfolio_title ?? "Rides For\nEvery Moment")) !!}
      </h1>
      <p class="tx-hero-subtitle mb-4" style="max-width: 650px; margin: 0 auto;">
        {{ $agency->portfolio_subtitle ?? 'Safe. Reliable. Affordable. Get where you need to go with comfort and peace of mind.' }}
      </p>

      <!-- Actions -->
      <div class="d-inline-flex align-items-center justify-content-center gap-2 gap-sm-3 mb-4 flex-wrap">
        <a href="{{ $agency->primary_btn_url ?? $contactUrl }}" class="tx-btn tx-btn-yellow px-4 py-3 fw-bold">
          {{ $agency->primary_btn_text ?? 'Book Your Ride' }} <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>
</section>

@php
  $items = $portfolio ?? $agency->portfolio_data ?? [];
  if (empty($items)) {
    $items = [
      ['category' => 'City Rides',        'title' => 'City Rides',            'desc' => 'Quick and affordable rides within your city.',          'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_city_rides.png'), 'icon' => 'fa-city'],
      ['category' => 'Airport Transfer', 'title' => 'Airport Transfers',     'desc' => 'On-time pickups and drop-offs for airport travel.',     'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_airport_transfers.png'), 'icon' => 'fa-plane-departure'],
      ['category' => 'Outstation',       'title' => 'Outstation Trips',      'desc' => 'Comfortable rides to any destination.',                 'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_outstation_trips.png'), 'icon' => 'fa-route'],
      ['category' => 'Corporate',        'title' => 'Corporate Travel',      'desc' => 'Reliable rides for business professionals.',            'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_corporate_travel.png'), 'icon' => 'fa-briefcase'],
      ['category' => 'Parcel Delivery',   'title' => 'Parcel Delivery',       'desc' => 'Fast and secure delivery service.',                     'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_parcel_delivery.png'), 'icon' => 'fa-box'],
      ['category' => 'Luxury',           'title' => 'Premium Rides',         'desc' => 'Experience luxury on every journey.',                   'image' => asset('assets/website_builder/Templates/Texigo_agency/services/service_premium_rides.png'), 'icon' => 'fa-crown'],
    ];
  }

  $dynamicCategories = [];
  foreach ($items as $item) {
    $catStr = $item['category'] ?? '';
    if (!empty($catStr)) {
      $cats = preg_split('/[•,]+/', $catStr);
      foreach ($cats as $c) {
        $trimmed = trim($c);
        if ($trimmed !== '') {
          $slug = \Illuminate\Support\Str::slug($trimmed);
          $dynamicCategories[$slug] = $trimmed;
        }
      }
    }
  }
@endphp

<!-- ===== SERVICES FILTER TABS & SEARCH BAR ===== -->
<section id="services-grid" class="py-3" style="background: #ffffff;">
  <div class="tx-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <!-- Dynamic Filter Pills -->
      <div class="tx-category-scroll-track" id="txFilterTrack">
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold active-filter" data-filter="all" onclick="filterServices('all', this)" style="background: var(--tx-primary); color: #0D0F12;">All</button>
        @foreach($dynamicCategories as $slug => $catName)
          <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="{{ $slug }}" onclick="filterServices('{{ $slug }}', this)">{{ $catName }}</button>
        @endforeach
      </div>

      <!-- Search Input -->
      <div class="position-relative" style="width: 240px;">
        <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 13px;"></i>
        <input type="text" id="txSearchInput" class="form-control form-control-sm rounded-pill ps-5 py-2 border" placeholder="Search services..." onkeyup="searchServices(this.value)">
      </div>
    </div>

    <!-- SERVICES GRID -->
    <div class="row g-4" id="txServicesCardsContainer">
      @foreach($items as $srv)
        @php
          $catStr = $srv['category'] ?? '';
          $catSlugs = [];
          if (!empty($catStr)) {
            foreach (preg_split('/[•,]+/', $catStr) as $c) {
              $t = trim($c);
              if ($t !== '') $catSlugs[] = \Illuminate\Support\Str::slug($t);
            }
          }
          $dataCatAttr = implode(' ', $catSlugs);
          $srvImage = $srv['image'] ?? $srv['img'] ?? asset('assets/website_builder/Templates/Texigo_agency/services/service_city_rides.png');
          $srvIcon = $srv['icon'] ?? 'fa-taxi';
        @endphp
        <div class="col-12 col-md-6 col-lg-4 tx-service-card-item" data-category="{{ $dataCatAttr }}" data-title="{{ strtolower($srv['title'] ?? '') }}">
          <div class="tx-portfolio-card">
            <div class="tx-portfolio-img-wrap">
              <img src="{{ str_starts_with($srvImage, 'http') ? $srvImage : asset(ltrim($srvImage, '/')) }}" alt="{{ $srv['title'] ?? '' }}">
            </div>
            <div class="tx-portfolio-info">
              <div class="tx-portfolio-icon">
                <i class="fa-solid {{ $srvIcon }}"></i>
              </div>
              <div class="tx-portfolio-text">
                <h3 class="tx-portfolio-title">{{ $srv['title'] ?? '' }}</h3>
                <p class="tx-portfolio-desc">{{ $srv['desc'] ?? $srv['description'] ?? '' }}</p>
              </div>
              <a href="{{ $contactUrl }}" class="tx-portfolio-arrow" aria-label="Book Service">
                <i class="fa-solid fa-arrow-right"></i>
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
      b.removeAttribute('style');
    });
    btnEl.className = 'btn btn-sm rounded-pill px-3 py-2 fw-bold active-filter';
    btnEl.setAttribute('style', 'background: var(--tx-primary) !important; color: #0D0F12 !important;');

    const cards = document.querySelectorAll('.tx-service-card-item');
    cards.forEach(card => {
      const cats = (card.getAttribute('data-category') || '').split(' ');
      if (category === 'all' || cats.includes(category)) {
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
