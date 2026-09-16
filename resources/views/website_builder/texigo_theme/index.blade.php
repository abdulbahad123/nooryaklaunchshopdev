@extends('website_builder.texigo_theme.layout')

@section('title', 'TaxiGo - #1 Trusted Taxi & Cab Mobility Service')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.contact');
  $agency = $agency ?? $interior ?? null;
@endphp

<!-- ===== HERO SECTION (PIXEL PERFECT WITH REFERENCE IMAGE) ===== -->
@php
  $defaultHeroImg = 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=1200&auto=format&fit=crop';
  $heroSrc = !empty($agency->hero_image) ? (str_starts_with($agency->hero_image, 'http') ? $agency->hero_image : asset(ltrim($agency->hero_image, '/'))) : $defaultHeroImg;
@endphp

<section class="tx-hero">
  <div class="tx-container">
    <div class="tx-hero-grid">
      <!-- Left Content -->
      <div>
        <span class="tx-pill-badge">
          {{ $agency->hero_badge ?? '🚖 #1 Trusted Taxi Service' }}
        </span>
        <h1 class="tx-heading tx-hero-title">
          Your Journey<br>Our <span style="color: var(--tx-primary);">Priority</span>
        </h1>
        <p class="tx-hero-subtitle">
          {{ $agency->hero_subtitle ?? 'Reliable. Safe. Affordable. Get where you need to go with comfort and peace of mind.' }}
        </p>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-2 gap-sm-3 mb-4 w-100 flex-wrap">
          <a href="{{ $agency->primary_btn_url ?? $contactUrl }}" class="tx-btn tx-btn-yellow px-4 py-3 fw-bold">
            {{ $agency->primary_btn_text ?? 'Book Your Ride' }} <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="#services" class="tx-btn tx-btn-outline-dark px-4 py-3 fw-bold">
            {{ $agency->secondary_btn_text ?? 'Explore Services' }}
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

      <!-- Right Showcase Vehicle Photo & Floating Badges -->
      <div class="position-relative">
        <div class="rounded-4 overflow-hidden shadow-lg border position-relative" style="height: 480px; background: #121A14;">
          <img src="{{ $heroSrc }}" alt="{{ $agency->site_title ?? 'TaxiGo Mobility' }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
          
          <!-- Top Floating Pill: 10K+ Happy Customers -->
          <div class="position-absolute top-0 start-0 m-4 p-2.5 px-3 bg-white rounded-4 shadow-lg border d-flex align-items-center gap-3" style="z-index: 5;">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-dark" style="width: 44px; height: 44px; background: var(--tx-primary); font-size: 18px; flex-shrink: 0;">
              <i class="fa-solid fa-users"></i>
            </div>
            <div>
              <div class="fw-extrabold text-dark" style="font-size: 18px; line-height: 1.1;">10K+</div>
              <div class="text-muted fw-semibold" style="font-size: 11px;">Happy Customers</div>
            </div>
          </div>

          <!-- Bottom Floating Pill: 4.8/5 Rating -->
          <div class="position-absolute bottom-0 end-0 m-4 p-2.5 px-3 bg-white rounded-4 shadow-lg border d-flex align-items-center gap-3" style="z-index: 5;">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-dark" style="width: 42px; height: 42px; background: var(--tx-primary); font-size: 16px; flex-shrink: 0;">
              <i class="fa-solid fa-star"></i>
            </div>
            <div>
              <div class="fw-extrabold text-dark" style="font-size: 18px; line-height: 1.1;">4.8/5</div>
              <div class="text-muted fw-semibold" style="font-size: 11px;">Average Rating</div>
            </div>
          </div>

          <!-- Cursive Text Overlay -->
          <div class="position-absolute top-0 end-0 m-4 text-end d-none d-sm-block" style="z-index: 4;">
            <span class="tx-cursive" style="font-size: 34px; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.85); line-height: 1.1;">
              Travel<br>Anytime<br>Anywhere
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== ABOUT US SECTION (MORE THAN JUST A RIDE) ===== -->
<section id="about" class="py-5" style="background: #ffffff;">
  <div class="tx-container py-4">
    <div class="row g-5 align-items-start">
      <!-- Left Info -->
      <div class="col-lg-5">
        <span class="tx-pill-badge">ABOUT US</span>
        <h2 class="tx-heading display-6 mb-3">
          More Than Just a Ride<br>We Drive <span style="color: var(--tx-primary);">Better Journeys</span>
        </h2>
        <p class="text-muted mb-4" style="line-height: 1.7; font-size: 14.5px;">
          TaxiGo is committed to providing safe, reliable, and comfortable transportation for everyone. Whether it's a quick city ride, an airport transfer, or a business trip, we make your journey smooth and hassle-free.
        </p>

        <a href="{{ $aboutUrl }}" class="tx-btn tx-btn-yellow">
          Learn More <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>

      <!-- Right 3 White Cards (Mission, Vision, Values) -->
      <div class="col-lg-7">
        <div class="row g-3">
          <!-- Card 1: Our Mission -->
          <div class="col-md-4">
            <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 text-dark" style="width: 48px; height: 48px; background: #FFF8E6; font-size: 20px;">
                <i class="fa-solid fa-bullseye" style="color: #945B00;"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-2 text-dark">Our Mission</h3>
              <p class="text-muted small mb-0" style="line-height: 1.6;">
                To provide safe, reliable, and convenient rides for everyone, everywhere.
              </p>
            </div>
          </div>

          <!-- Card 2: Our Vision -->
          <div class="col-md-4">
            <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 text-dark" style="width: 48px; height: 48px; background: #FFF8E6; font-size: 20px;">
                <i class="fa-regular fa-eye" style="color: #945B00;"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-2 text-dark">Our Vision</h3>
              <p class="text-muted small mb-0" style="line-height: 1.6;">
                To be the most trusted global mobility platform, connecting people and places seamlessly.
              </p>
            </div>
          </div>

          <!-- Card 3: Our Values -->
          <div class="col-md-4">
            <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 text-dark" style="width: 48px; height: 48px; background: #FFF8E6; font-size: 20px;">
                <i class="fa-solid fa-gem" style="color: #945B00;"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-2 text-dark">Our Values</h3>
              <ul class="list-unstyled text-muted small mb-0" style="line-height: 1.7; font-size: 11.5px;">
                <li class="mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> Customer First</li>
                <li class="mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> Safety & Reliability</li>
                <li class="mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> Integrity & Transparency</li>
                <li class="mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> Innovation</li>
                <li><i class="fa-solid fa-circle-check text-warning me-1"></i> Sustainable Mobility</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FULL WIDTH 4-STATS DARK BAR (MATCHING REFERENCE IMAGE) ===== -->
@php
  $stats = $agency->stats_data ?? [
    ['number' => '8+',    'label' => 'Years of Experience',   'icon' => 'fa-users'],
    ['number' => '250K+', 'label' => 'Rides Completed',       'icon' => 'fa-car'],
    ['number' => '98%',   'label' => 'Customer Satisfaction', 'icon' => 'fa-star'],
    ['number' => '50+',   'label' => 'Professional Drivers',  'icon' => 'fa-user-tie'],
  ];
@endphp

<div class="tx-container my-2">
  <div class="tx-dark-stats-bar">
    <div class="row g-4 align-items-center text-start">
      @foreach($stats as $st)
        <div class="col-6 col-lg-3">
          <div class="tx-stat-item">
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

<!-- ===== OUR SERVICES SECTION ("Ride for Every Occasion") ===== -->
<section id="services" class="py-5" style="background: #ffffff;">
  <div class="tx-container py-4">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <span class="tx-pill-badge">OUR SERVICES</span>
        <h2 class="tx-heading display-6 mb-2">Ride for Every Occasion</h2>
        <p class="text-muted mb-0" style="font-size: 14.5px;">From daily commutes to special trips, we have the right ride for you.</p>
      </div>

      <div class="d-flex align-items-center gap-2">
        <a href="{{ $contactUrl }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold fs-7">
          View All Services <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <button type="button" id="srvPrevBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button type="button" id="srvNextBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $services = $agency->services_data ?? [
        ['title' => 'City Rides',        'desc' => 'Quick and affordable rides within the city.',            'image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-city'],
        ['title' => 'Airport Transfers', 'desc' => 'On-time pickups and drop-offs for airport travel.',     'image' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-plane-departure'],
        ['title' => 'Outstation Trips',  'desc' => 'Comfortable rides to any long-distance destination.',    'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-route'],
        ['title' => 'Corporate Travel',  'desc' => 'Reliable rides for business professionals & VIPs.',      'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-briefcase'],
        ['title' => 'Parcel Delivery',   'desc' => 'Fast, express, and secure local delivery service.',      'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-box'],
      ];
    @endphp

    <div class="row g-3 tx-mobile-slider" id="srvSliderTrack">
      @foreach($services as $srv)
        <div class="col-12 col-sm-6 col-lg-2-4" style="flex: 0 0 20%; min-width: 230px;">
          <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm bg-white p-2">
            <div class="position-relative rounded-3 overflow-hidden mb-2" style="height: 140px;">
              <img src="{{ str_starts_with($srv['image'] ?? '', 'http') ? ($srv['image'] ?? '') : asset(ltrim($srv['image'] ?? '', '/')) }}" alt="{{ $srv['title'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="p-2 d-flex flex-column h-100">
              <div class="d-flex align-items-center gap-2 mb-1">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-dark flex-shrink-0" style="width: 30px; height: 30px; background: var(--tx-primary); font-size: 13px;">
                  <i class="fa-solid {{ $srv['icon'] ?? 'fa-taxi' }}"></i>
                </div>
                <h3 class="fw-bold mb-0 text-dark" style="font-size: 14px;">{{ $srv['title'] ?? '' }}</h3>
              </div>
              <p class="text-muted small mb-2 flex-grow-1" style="font-size: 11.5px; line-height: 1.4;">{{ $srv['desc'] ?? '' }}</p>
              <a href="{{ $contactUrl }}" class="btn btn-light rounded-circle border p-0 ms-auto d-flex align-items-center justify-content-center" style="width: 26px; height: 26px;">
                <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== OUR FLEET SECTION ("Choose Your Perfect Ride") ===== -->
<section id="fleet" class="py-5" style="background: #F8F9FA;">
  <div class="tx-container py-4">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <span class="tx-pill-badge">OUR FLEET</span>
        <h2 class="tx-heading display-6 mb-2">Choose Your Perfect Ride</h2>
        <p class="text-muted mb-0" style="font-size: 14.5px;">A wide range of vehicles to suit your needs and budget.</p>
      </div>

      <div class="d-flex align-items-center gap-2">
        <a href="{{ $contactUrl }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold fs-7">
          View All Vehicles <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <button type="button" id="fleetPrevBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button type="button" id="fleetNextBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $fleet = $agency->portfolio_data ?? [
        ['title' => 'Hatchback', 'category' => 'Economical',  'seats' => '4 Seats', 'bags' => '2 Bags', 'image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=800&auto=format&fit=crop'],
        ['title' => 'Sedan',     'category' => 'Comfort',     'seats' => '4 Seats', 'bags' => '3 Bags', 'image' => 'https://images.unsplash.com/photo-1550355291-bbee04a92027?q=80&w=800&auto=format&fit=crop'],
        ['title' => 'SUV',       'category' => 'Family & XL', 'seats' => '6 Seats', 'bags' => '4 Bags', 'image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=800&auto=format&fit=crop'],
        ['title' => 'Premium',   'category' => 'Luxury',      'seats' => '4 Seats', 'bags' => '3 Bags', 'image' => 'https://images.unsplash.com/photo-1563720223185-11003d516935?q=80&w=800&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-3 tx-mobile-slider" id="fleetSliderTrack">
      @foreach($fleet as $fl)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm bg-white p-3 text-center">
            <div class="position-relative rounded-3 overflow-hidden mb-3" style="height: 160px; background: #F1F5F9;">
              <img src="{{ str_starts_with($fl['image'] ?? '', 'http') ? ($fl['image'] ?? '') : asset(ltrim($fl['image'] ?? '', '/')) }}" alt="{{ $fl['title'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h3 class="fw-bold fs-5 mb-2 text-dark">{{ $fl['title'] ?? '' }}</h3>
            <div class="d-flex align-items-center justify-content-center gap-3 text-muted small">
              <span><i class="fa-solid fa-user me-1 text-warning"></i> {{ $fl['seats'] ?? '4 Seats' }}</span>
              <span><i class="fa-solid fa-suitcase me-1 text-warning"></i> {{ $fl['bags'] ?? '2 Bags' }}</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS SECTION ===== -->
<section id="testimonials" class="py-5" style="background: #ffffff;">
  <div class="tx-container py-4">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <span class="tx-pill-badge">TESTIMONIALS</span>
        <h2 class="tx-heading display-6 mb-2">What Our Customers Say</h2>
        <p class="text-muted mb-0" style="font-size: 14.5px;">Real stories from people who ride with TaxiGo every day.</p>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" id="tstPrevBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button type="button" id="tstNextBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $testimonials = $agency->testimonials_data ?? [
        ['name' => 'Emily Carter',  'role' => 'Frequent Traveler', 'comment' => 'TaxiGo made my airport transfer so easy and stress-free. Highly recommended!', 'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'],
        ['name' => 'James Walker',  'role' => 'Business Executive', 'comment' => 'Reliable, professional, and affordable. The best taxi service in the city!',    'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'],
        ['name' => 'Sophia Lee',    'role' => 'Regular Customer',  'comment' => 'Great service and very friendly drivers. I always choose TaxiGo!',            'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-3 tx-mobile-slider" id="tstSliderTrack">
      @foreach($testimonials as $t)
        <div class="col-12 col-md-4">
          <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="text-warning fs-5 mb-3">
              <i class="fa-solid fa-quote-left me-2 text-muted opacity-50"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
            </div>
            <p class="text-muted fst-italic mb-4 flex-grow-1" style="font-size: 13.5px; line-height: 1.6;">"{{ $t['comment'] ?? '' }}"</p>
            <div class="d-flex align-items-center gap-3">
              <img src="{{ str_starts_with($t['avatar'] ?? '', 'http') ? ($t['avatar'] ?? '') : asset(ltrim($t['avatar'] ?? '', '/')) }}" alt="{{ $t['name'] ?? '' }}" class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover;">
              <div>
                <h4 class="fw-bold fs-6 mb-0 text-dark">{{ $t['name'] ?? '' }}</h4>
                <span class="text-muted small" style="font-size: 12px;">{{ $t['role'] ?? '' }}</span>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Slider Controls Setup
    function setupSlider(trackId, prevBtnId, nextBtnId) {
      const track = document.getElementById(trackId);
      const prev = document.getElementById(prevBtnId);
      const next = document.getElementById(nextBtnId);
      if (!track) return;

      if (prev) {
        prev.addEventListener('click', function() {
          const step = track.clientWidth || 280;
          track.scrollBy({ left: -step, behavior: 'smooth' });
        });
      }
      if (next) {
        next.addEventListener('click', function() {
          const step = track.clientWidth || 280;
          track.scrollBy({ left: step, behavior: 'smooth' });
        });
      }

      let autoTimer;
      function startAuto() {
        autoTimer = setInterval(function() {
          var maxScroll = track.scrollWidth - track.clientWidth;
          if (maxScroll > 0) {
            if (track.scrollLeft >= maxScroll - 10) {
              track.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
              var step = track.clientWidth || 280;
              track.scrollBy({ left: step, behavior: 'smooth' });
            }
          }
        }, 3600);
      }
      startAuto();
      track.addEventListener('touchstart', function() { clearInterval(autoTimer); }, { passive: true });
      track.addEventListener('touchend', function() { startAuto(); }, { passive: true });
    }

    setupSlider('srvSliderTrack', 'srvPrevBtn', 'srvNextBtn');
    setupSlider('fleetSliderTrack', 'fleetPrevBtn', 'fleetNextBtn');
    setupSlider('tstSliderTrack', 'tstPrevBtn', 'tstNextBtn');
  });
</script>
@endsection

@endsection
