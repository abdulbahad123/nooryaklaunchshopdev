@extends('website_builder.texigo_theme.layout')

@section('no_cta')@endsection

@section('title', 'About Us - ' . ($agency->site_title ?? 'TaxiGo Mobility'))

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.portfolio');
  $agency = $agency ?? $interior ?? null;
@endphp

<!-- ===== ABOUT HERO SECTION (MATCHING REF IMAGE 1) ===== -->
@php
  $heroBannerBg = asset('assets/website_builder/Templates/Texigo_agency/herobanner_image.png');
@endphp

<section class="tx-hero" style="background: url('{{ $heroBannerBg }}') no-repeat center right / cover; min-height: 520px; position: relative;">
  <div class="tx-container py-4">
    <div class="row align-items-center">
      <!-- Left Content -->
      <div class="col-lg-6 py-3">
        <span class="tx-pill-badge" style="background: #FFF8E6; color: #945B00;">
          ABOUT US
        </span>
        <h1 class="tx-heading tx-hero-title">
          More Than Rides,<br>We <span style="color: var(--tx-primary);">Move People</span>
        </h1>
        <p class="tx-hero-subtitle">
          We're on a mission to make every journey safer, smarter, and more comfortable. From daily commutes to special moments, TaxiGo is always with you.
        </p>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-2 gap-sm-3 mb-4 w-100 flex-wrap">
          <a href="{{ $portfolioUrl }}" class="tx-btn tx-btn-yellow px-4 py-3 fw-bold">
            Our Services <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="{{ $contactUrl }}" class="tx-btn tx-btn-outline-dark px-4 py-3 fw-bold">
            Contact Us
          </a>
        </div>

        <!-- Trusted by Thousands Avatars -->
        <div class="d-flex align-items-center gap-3 pt-2">
          <div class="d-flex align-items-center">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white" style="width: 38px; height: 38px; object-fit: cover; margin-right: -10px;">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white" style="width: 38px; height: 38px; object-fit: cover; margin-right: -10px;">
            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white" style="width: 38px; height: 38px; object-fit: cover; margin-right: -10px;">
            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white" style="width: 38px; height: 38px; object-fit: cover;">
          </div>
          <div>
            <div class="fw-bold text-dark" style="font-size: 13px; line-height: 1.2;">Trusted by</div>
            <div class="text-muted fw-semibold" style="font-size: 12px;">Thousands of Riders</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== OUR STORY SECTION ===== -->
<section id="our-story" class="py-5" style="background: #ffffff;">
  <div class="tx-container py-4">
    <div class="row g-5 align-items-start">
      <!-- Left Story Column -->
      <div class="col-lg-5">
        <span class="tx-pill-badge" style="background: #FFF8E6; color: #945B00;">OUR STORY</span>
        <h2 class="tx-heading display-6 mb-3">
          A Journey Driven<br>By <span style="color: var(--tx-primary);">People</span>
        </h2>
        <p class="text-muted mb-4" style="line-height: 1.75; font-size: 14.5px;">
          TaxiGo was founded with a simple idea — to make transportation more accessible, reliable, and human. What started as a small team of mobility enthusiasts has grown into a trusted platform serving thousands of riders every day.
        </p>

        <!-- Founder Signature Badge -->
        <div class="d-flex align-items-center gap-3 pt-2">
          <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop" alt="Rahul Mehta" class="rounded-circle" style="width: 52px; height: 52px; object-fit: cover;">
          <div>
            <h4 class="fw-bold text-dark fs-6 mb-0">Rahul Mehta</h4>
            <span class="text-muted small">Founder & CEO</span>
          </div>
          <div class="ms-auto d-none d-sm-block">
            <span class="tx-cursive text-dark" style="font-size: 28px; font-weight: 700; opacity: 0.85;">Rahul Mehta</span>
          </div>
        </div>
      </div>

      <!-- Right 3 Cards (Mission, Vision, Values) -->
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
                To provide safe, affordable, and convenient rides for everyone, everywhere.
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
                To be the most trusted global mobility platform, connecting people and places.
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

<!-- ===== FULL WIDTH 4-STATS DARK BAR ===== -->
@php
  $stats = $agency->stats_data ?? [
    ['number' => '8+',    'label' => 'Years of Experience',   'icon' => 'fa-users'],
    ['number' => '250K+', 'label' => 'Rides Completed',       'icon' => 'fa-car'],
    ['number' => '98%',   'label' => 'Customer Satisfaction', 'icon' => 'fa-star'],
    ['number' => '50+',   'label' => 'Professional Drivers',  'icon' => 'fa-user-tie'],
  ];
@endphp

<div class="tx-container">
  <div class="tx-light-stats-bar">
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

<!-- ===== MEET OUR TEAM SECTION ===== -->
<section id="team" class="py-5" style="background: #ffffff;">
  <div class="tx-container py-4">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <span class="tx-pill-badge" style="background: #FFF8E6; color: #945B00;">MEET OUR TEAM</span>
        <h2 class="tx-heading display-6 mb-2">The People Behind TaxiGo</h2>
        <p class="text-muted mb-0" style="font-size: 14.5px;">Our team is made up of passionate individuals who believe in building a smarter, safer, and more connected world through better mobility.</p>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" id="teamPrevBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button type="button" id="teamNextBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-right"></i></button>
        <a href="{{ $contactUrl }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold fs-7 ms-2">
          View All Team Members <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    @php
      $team = $agency->team_members_data ?? [
        ['name' => 'Priya Sharma',  'role' => 'Chief Operating Officer',       'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Rahul Mehta',   'role' => 'Founder & CEO',                 'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Anjali Verma',  'role' => 'Head of Customer Success',      'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Daniel Smith',  'role' => 'Head of Technology',            'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=400&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-3 tx-mobile-slider" id="teamSliderTrack">
      @foreach($team as $tm)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm bg-white text-center p-3">
            <div class="rounded-3 overflow-hidden mb-3" style="height: 220px;">
              <img src="{{ str_starts_with($tm['image'] ?? '', 'http') ? ($tm['image'] ?? '') : asset(ltrim($tm['image'] ?? '', '/')) }}" alt="{{ $tm['name'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
            </div>
            <h3 class="fw-bold fs-6 mb-1 text-dark">{{ $tm['name'] ?? '' }}</h3>
            <div class="text-muted small mb-3" style="font-size: 12px;">{{ $tm['role'] ?? '' }}</div>
            <div class="d-flex align-items-center justify-content-center gap-3 text-muted small">
              <a href="#" class="text-muted hover-yellow"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="#" class="text-muted hover-yellow"><i class="fa-brands fa-x-twitter"></i></a>
              <a href="#" class="text-muted hover-yellow"><i class="fa-brands fa-linkedin-in"></i></a>
              <a href="#" class="text-muted hover-yellow"><i class="fa-brands fa-instagram"></i></a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== WHAT OUR RIDERS SAY (TESTIMONIALS) ===== -->
<section id="testimonials" class="py-5" style="background: #ffffff;">
  <div class="tx-container py-4">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <span class="tx-pill-badge" style="background: #FFF8E6; color: #945B00;">WHAT OUR RIDERS SAY</span>
        <h2 class="tx-heading display-6 mb-2">Stories From Our Happy Riders</h2>
        <p class="text-muted mb-0" style="font-size: 14.5px;">Real experiences from people who ride with TaxiGo every day.</p>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" id="tstPrevBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button type="button" id="tstNextBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $testimonials = $agency->testimonials_data ?? [
        ['name' => 'Emily Carter',  'role' => 'Regular Rider',      'comment' => 'TaxiGo made my daily commute so easy and stress-free. Highly recommended!',    'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'],
        ['name' => 'James Walker',  'role' => 'Business Traveler',  'comment' => 'Reliable, affordable, and always on time. The best cab service in the city!',   'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'],
        ['name' => 'Sophia Lee',    'role' => 'Frequent Rider',     'comment' => 'Professional drivers and excellent customer support. Truly a great experience!', 'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-3 tx-mobile-slider" id="tstSliderTrack">
      @foreach($testimonials as $t)
        <div class="col-12 col-md-4">
          <div class="tx-tst-card">
            <div class="tx-tst-quote"><i class="fa-solid fa-quote-left"></i></div>
            <p class="tx-tst-text">"{{ $t['comment'] ?? '' }}"</p>
            <div class="tx-tst-stars">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
            </div>
            <div class="tx-tst-author">
              <img src="{{ str_starts_with($t['avatar'] ?? '', 'http') ? ($t['avatar'] ?? '') : asset(ltrim($t['avatar'] ?? '', '/')) }}" alt="{{ $t['name'] ?? '' }}" class="tx-tst-avatar">
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

<!-- ===== FOOTER CTA BANNER (USING about_footercta.png) ===== -->
<div class="tx-container my-4">
  <div class="tx-cta-box-edge" style="background: url('{{ asset('assets/website_builder/Templates/Texigo_agency/about_footercta.png') }}') no-repeat center right / cover; min-height: 250px; border-radius: 24px; padding: 44px 52px; position: relative; color: #ffffff;">
    <div class="row align-items-center">
      <div class="col-lg-7" style="position: relative; z-index: 3;">
        <div class="text-uppercase fw-extrabold mb-2" style="color: var(--tx-primary); font-size: 12px; letter-spacing: 2px;">LET'S RIDE TOGETHER</div>
        <h2 class="fw-extrabold mb-3 text-white display-6">Ready for Your <span style="color: var(--tx-primary);">Next Ride?</span></h2>
        <p class="text-white-50 mb-4 fs-6" style="max-width: 500px;">
          Join thousands of happy riders. Safe. Reliable. Always.
        </p>

        <a href="{{ $contactUrl }}" class="tx-btn tx-btn-yellow fs-6 px-4 py-3 fw-bold">
          Book a Ride <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>

        <!-- 3 Feature Pills Below Button -->
        <div class="d-flex align-items-center gap-4 pt-4 flex-wrap text-white">
          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255,255,255,0.15); color: var(--tx-primary);">
              <i class="fa-solid fa-shield-halved" style="font-size: 14px;"></i>
            </div>
            <span style="font-size: 13px; font-weight: 700;">Safe Rides</span>
          </div>

          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255,255,255,0.15); color: var(--tx-primary);">
              <i class="fa-solid fa-headset" style="font-size: 14px;"></i>
            </div>
            <span style="font-size: 13px; font-weight: 700;">24/7 Support</span>
          </div>

          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255,255,255,0.15); color: var(--tx-primary);">
              <i class="fa-solid fa-tag" style="font-size: 14px;"></i>
            </div>
            <span style="font-size: 13px; font-weight: 700;">Affordable Pricing</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
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

    setupSlider('teamSliderTrack', 'teamPrevBtn', 'teamNextBtn');
    setupSlider('tstSliderTrack', 'tstPrevBtn', 'tstNextBtn');
  });
</script>
@endsection

@endsection
