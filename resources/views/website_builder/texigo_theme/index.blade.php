@extends('website_builder.texigo_theme.layout')

@section('title', 'TaxiGo - #1 Trusted Taxi & Cab Mobility Service')

@section('content')

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
  <div style="position:absolute;inset:0;background:linear-gradient(to right, rgba(255,255,255,0.96) 42%, rgba(255,255,255,0.55) 65%, rgba(255,255,255,0.05) 100%);z-index:1;"></div>
  <div class="tx-container py-5" style="position:relative;z-index:2;">
    <div class="row align-items-center" style="min-height:480px;">
      <div class="col-lg-6 py-4">
        <span class="tx-pill-badge">
          {{ $agency->hero_badge ?? '🚖 #1 Trusted Taxi Service' }}
        </span>
        <h1 class="tx-heading tx-hero-title mb-3">
          Your Journey<br>Our <span style="color:var(--tx-primary);">Priority</span>
        </h1>
        <p class="tx-hero-subtitle">
          {{ $agency->hero_subtitle ?? 'Reliable. Safe. Affordable. Get where you need to go with comfort and peace of mind.' }}
        </p>

        <!-- CTA Buttons -->
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
          <a href="{{ $agency->primary_btn_url ?? $contactUrl }}" class="tx-btn tx-btn-yellow px-5 py-3 fw-bold fs-6">
            {{ $agency->primary_btn_text ?? 'Book Your Ride' }} <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="#services" class="tx-btn tx-btn-outline-dark px-5 py-3 fw-bold fs-6">
            {{ $agency->secondary_btn_text ?? 'Explore Services' }}
          </a>
        </div>

        <!-- 3 Trust Badges -->
        <div class="d-flex align-items-center gap-4 pt-2 flex-wrap">
          <div class="d-flex align-items-center gap-2">
            <div class="tx-hero-badge-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div>
              <div style="font-size:12px;font-weight:700;color:var(--tx-text-dark);line-height:1.2;">Safe &</div>
              <div style="font-size:11px;font-weight:500;color:var(--tx-text-muted);">Secure Rides</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="tx-hero-badge-icon"><i class="fa-solid fa-headset"></i></div>
            <div>
              <div style="font-size:12px;font-weight:700;color:var(--tx-text-dark);line-height:1.2;">24/7</div>
              <div style="font-size:11px;font-weight:500;color:var(--tx-text-muted);">Customer Support</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="tx-hero-badge-icon"><i class="fa-solid fa-calculator"></i></div>
            <div>
              <div style="font-size:12px;font-weight:700;color:var(--tx-text-dark);line-height:1.2;">Affordable</div>
              <div style="font-size:11px;font-weight:500;color:var(--tx-text-muted);">& Transparent Pricing</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- =====================================================================
     ABOUT US SECTION — Reference Image 3 (pixel-perfect)
     Left: badge + h2 + desc + "Learn More" btn
     Right: 3 cards (Mission / Vision / Values) in equal columns
     ===================================================================== --}}
<section id="about" style="background:#ffffff; padding:72px 0;">
  <div class="tx-container">
    <div class="row g-5 align-items-start">

      {{-- LEFT COLUMN --}}
      <div class="col-lg-5">
        <span class="tx-pill-badge">ABOUT US</span>
        <h2 class="tx-heading mb-4" style="font-size:clamp(28px,3.5vw,40px);line-height:1.15;">
          More Than Just a Ride<br>We Drive <span style="color:var(--tx-primary);">Better Journeys</span>
        </h2>
        <p class="mb-4" style="color:var(--tx-text-muted);line-height:1.75;font-size:14.5px;max-width:380px;">
          TaxiGo is committed to providing safe, reliable, and comfortable transportation for everyone. Whether it's a quick city ride, an airport transfer, or a business trip, we make your journey smooth and hassle-free.
        </p>
        <a href="{{ $aboutUrl }}" class="tx-btn tx-btn-yellow px-4 py-3 fw-bold">
          Learn More <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>

      {{-- RIGHT COLUMN — 3 equal cards --}}
      <div class="col-lg-7">
        <div class="row g-3 h-100">

          {{-- Mission card --}}
          <div class="col-md-4">
            <div class="tx-mvv-card h-100">
              <div class="tx-mvv-icon">
                <i class="fa-solid fa-bullseye" style="color:var(--tx-primary-dark);font-size:22px;"></i>
              </div>
              <h3 class="tx-mvv-title">Our Mission</h3>
              <p class="tx-mvv-desc">To provide safe, reliable, and convenient rides for everyone, everywhere.</p>
            </div>
          </div>

          {{-- Vision card --}}
          <div class="col-md-4">
            <div class="tx-mvv-card h-100">
              <div class="tx-mvv-icon">
                <i class="fa-regular fa-eye" style="color:var(--tx-primary-dark);font-size:22px;"></i>
              </div>
              <h3 class="tx-mvv-title">Our Vision</h3>
              <p class="tx-mvv-desc">To be the most trusted global mobility platform, connecting people and places.</p>
            </div>
          </div>

          {{-- Values card --}}
          <div class="col-md-4">
            <div class="tx-mvv-card h-100">
              <div class="tx-mvv-icon">
                <i class="fa-solid fa-gem" style="color:var(--tx-primary-dark);font-size:22px;"></i>
              </div>
              <h3 class="tx-mvv-title">Our Values</h3>
              <ul class="list-unstyled mb-0" style="font-size:12.5px;line-height:1.85;color:var(--tx-text-muted);">
                <li><i class="fa-solid fa-circle-check text-warning me-1" style="font-size:11px;"></i> Customer First</li>
                <li><i class="fa-solid fa-circle-check text-warning me-1" style="font-size:11px;"></i> Safety &amp; Reliability</li>
                <li><i class="fa-solid fa-circle-check text-warning me-1" style="font-size:11px;"></i> Integrity &amp; Transparency</li>
                <li><i class="fa-solid fa-circle-check text-warning me-1" style="font-size:11px;"></i> Innovation</li>
                <li><i class="fa-solid fa-circle-check text-warning me-1" style="font-size:11px;"></i> Sustainable Mobility</li>
              </ul>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

{{-- =====================================================================
     DARK STATS BAR — Reference Image 3 (bottom strip)
     4 stats: 8+ Years | 250K+ Rides | 98% Satisfaction | 50+ Drivers
     ===================================================================== --}}
@php
  $stats = $agency->stats_data ?? [
    ['number' => '8+',    'label' => 'Years of Experience',   'icon' => 'fa-users'],
    ['number' => '250K+', 'label' => 'Rides Completed',       'icon' => 'fa-car'],
    ['number' => '98%',   'label' => 'Customer Satisfaction', 'icon' => 'fa-star'],
    ['number' => '50+',   'label' => 'Professional Drivers',  'icon' => 'fa-user-tie'],
  ];
@endphp

<div class="tx-dark-stats-bar">
  <div class="tx-container">
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
     OUR SERVICES SECTION — Reference Image 4 (top half)
     Header: badge + h2 + subtitle (left) | "View All Services" + < > arrows (right)
     5 cards: tall image top, then icon+title+desc+arrow at bottom
     ===================================================================== --}}
<section id="services" style="background:#ffffff; padding:72px 0;">
  <div class="tx-container">

    {{-- Section header --}}
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <span class="tx-pill-badge">OUR SERVICES</span>
        <h2 class="tx-heading mb-2" style="font-size:clamp(26px,3.2vw,38px);">Ride for Every Occasion</h2>
        <p style="color:var(--tx-text-muted);font-size:14.5px;margin:0;">From daily commutes to special trips, we have the right ride for you.</p>
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
        ['title'=>'City Rides',        'desc'=>'Quick and affordable rides within the city.',           'image'=>'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-city'],
        ['title'=>'Airport Transfers', 'desc'=>'On-time pickups and drop-offs.',                        'image'=>'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-plane-departure'],
        ['title'=>'Outstation Trips',  'desc'=>'Comfortable rides to any destination.',                 'image'=>'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-route'],
        ['title'=>'Corporate Travel',  'desc'=>'Reliable rides for business professionals.',            'image'=>'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-briefcase'],
        ['title'=>'Parcel Delivery',   'desc'=>'Fast and secure delivery service.',                     'image'=>'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=600&auto=format&fit=crop', 'icon'=>'fa-box'],
      ];
    @endphp

    {{-- Slider track: overflows horizontally, scrolled by JS --}}
    <div class="tx-srv-slider-wrap">
      <div class="tx-srv-track" id="srvSliderTrack">
        @foreach($services as $srv)
        <div class="tx-srv-card-wrap">
          <div class="tx-srv-card">
            {{-- Tall image --}}
            <div class="tx-srv-img">
              <img src="{{ str_starts_with($srv['image'] ?? '', 'http') ? $srv['image'] : asset(ltrim($srv['image'] ?? '', '/')) }}"
                   alt="{{ $srv['title'] ?? '' }}" loading="lazy">
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
     FLEET SECTION — Reference Image 4 (bottom half)
     4 vehicle cards on light grey bg: car image (no crop border) + title + seats/bags
     ===================================================================== --}}
<section id="fleet" style="background:#F8F9FA; padding:72px 0;">
  <div class="tx-container">

    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <span class="tx-pill-badge">OUR FLEET</span>
        <h2 class="tx-heading mb-2" style="font-size:clamp(26px,3.2vw,38px);">Choose Your Perfect Ride</h2>
        <p style="color:var(--tx-text-muted);font-size:14.5px;margin:0;">A wide range of vehicles to suit your needs and budget.</p>
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

    <div class="row g-4 tx-mobile-slider" id="fleetSliderTrack">
      @foreach($fleet as $fl)
      <div class="col-12 col-sm-6 col-lg-3">
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
     TESTIMONIALS SECTION — Reference Image 5 (top portion)
     Header: badge+h2+subtitle left | < > arrows right
     3 cards: large quote mark (yellow), italic comment, stars, avatar+name+role
     ===================================================================== --}}
<section id="testimonials" style="background:#ffffff; padding:72px 0;">
  <div class="tx-container">

    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <span class="tx-pill-badge">TESTIMONIALS</span>
        <h2 class="tx-heading mb-2" style="font-size:clamp(26px,3.2vw,38px);">What Our Customers Say</h2>
        <p style="color:var(--tx-text-muted);font-size:14.5px;margin:0;">Real stories from people who ride with TaxiGo every day.</p>
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

    <div class="row g-4 tx-mobile-slider" id="tstSliderTrack">
      @foreach($testimonials as $t)
      <div class="col-12 col-md-4">
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
});
</script>
@endsection
