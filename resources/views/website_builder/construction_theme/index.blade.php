@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — Building Stronger Futures')
@section('description', $agency->hero_subtitle ?? 'Reliable construction, renovation, and infrastructure solutions built on quality, safety, and trust.')

@section('content')

<style>
  @media (min-width: 992px) {
    .cn-service-card-ref, .cn-service-slide-card, .cn-testimonial-ref-card {
      flex: 0 0 calc(25% - 18px) !important;
      width: calc(25% - 18px) !important;
      min-width: calc(25% - 18px) !important;
      max-width: calc(25% - 18px) !important;
    }
  }
  @media (max-width: 767.98px) {
    .cn-service-card-ref, .cn-service-slide-card, .service-scroll-track > * {
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
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.contact');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.about');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.portfolio');
  $servicesUrl  = $subdomainParam ? route('website-builder.subdomain.services',  ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.services');

  $heroBg = !empty($agency->hero_image) ? (str_starts_with($agency->hero_image, 'http') ? $agency->hero_image : asset(ltrim($agency->hero_image, '/'))) : asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
  $footerCtaBg = asset('assets/website_builder/Templates/Construction_agency/construction_footercta.png');

  $stats        = $agency->stats_data ?? [];
  $services     = $agency->services_data ?? [];
  $portfolio    = $agency->portfolio_data ?? [];
  $testimonials = $agency->testimonials_data ?? [];
  $specializations = $agency->construction_data['specializations'] ?? [];
@endphp

{{-- =====================================================================
     1. HERO SECTION (Ref Screenshot 2 Match)
     ===================================================================== --}}
<section class="cn-hero" style="background: url('{{ $heroBg }}') no-repeat center right / cover; min-height: 520px; position: relative;">
  <div class="cn-hero-overlay"></div>
  
  {{-- Floating Top Right Script Text --}}
  <div class="cn-hero-script-overlay d-none d-lg-block">
    Construct Innovate Elevate
  </div>

  <div class="cn-container" style="position: relative; z-index: 2; width: 100%;">
    <div class="cn-hero-content">
      <div class="cn-hero-badge cn-animate">
        <i class="fa-solid fa-shield-halved"></i>
        {{ $agency->hero_badge ?? '🛡️ Trusted Construction Partner' }}
      </div>

      <h1 class="cn-hero-title cn-animate cn-animate-delay-1">
        {!! nl2br(e($agency->hero_title ?? 'Building Stronger Futures')) !!}
      </h1>

      <p class="cn-hero-subtitle cn-animate cn-animate-delay-2">
        {{ $agency->hero_subtitle ?? 'Reliable construction, renovation, and infrastructure solutions built on quality, safety, and trust. We turn visions into extraordinary spaces.' }}
      </p>

      <div class="cn-hero-cta cn-hero-actions cn-animate cn-animate-delay-3">
        <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
          Get a Quote <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <a href="{{ $portfolioUrl }}" class="cn-btn cn-btn-pill-dark">
          <span class="cn-play-icon"><i class="fa-solid fa-play"></i></span> Watch Video
        </a>
      </div>

      {{-- Bottom Hero Trust Highlights Bar --}}
      @php
        $trustBar = $agency->trust_bar_data ?? [
          ['title' => 'Safe & Quality', 'sub' => 'Construction'],
          ['title' => 'Experienced', 'sub' => 'Professional Team'],
          ['title' => 'On-Time', 'sub' => 'Project Delivery'],
        ];
      @endphp
      <div class="cn-hero-trust-bar mt-4">
        @foreach($trustBar as $ti => $tb)
          <div class="cn-trust-item">
            <div class="cn-trust-icon-yellow"><i class="fa-solid {{ $ti == 0 ? 'fa-shield-halved' : ($ti == 1 ? 'fa-users' : 'fa-clock') }}"></i></div>
            <div>
              <div class="cn-trust-title">{{ $tb['title'] ?? '' }}</div>
              <div class="cn-trust-sub">{{ $tb['sub'] ?? '' }}</div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Floating Bottom Right Badge --}}
  <div class="cn-hero-bottom-badge d-none d-xl-flex">
    <i class="fa-solid fa-building-user me-2 text-warning fs-4"></i>
    <div>
      <div class="fw-bold text-white fs-6">Shaping A Better</div>
      <div class="text-warning small fw-semibold">Tomorrow</div>
    </div>
  </div>
</section>


{{-- =====================================================================
     2. OUR SERVICES SECTION ("Comprehensive Construction Solutions")
     ===================================================================== --}}
@if(count($services) > 0)
<section class="cn-section cn-section-light" id="services">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <div class="cn-section-label">{{ $agency->services_badge ?? 'OUR SERVICES' }}</div>
        <h2 class="cn-section-heading">{!! nl2br(e($agency->services_title ?? 'Comprehensive Construction Solutions')) !!}</h2>
        <p class="cn-section-sub">{{ $agency->services_subtitle ?? 'From innovative buildings to critical infrastructure, we deliver excellence in every project.' }}</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <button class="cn-nav-arrow" type="button" aria-label="Previous" onclick="document.getElementById('cnServicesTrack').scrollBy({left: -320, behavior: 'smooth'});"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="cn-nav-arrow" type="button" aria-label="Next" onclick="document.getElementById('cnServicesTrack').scrollBy({left: 320, behavior: 'smooth'});"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      if (empty($services)) {
        $services = [
          ['icon' => 'fa-building',          'title' => 'Commercial Construction',   'desc' => 'High-rise office complexes, retail centers, and modern corporate headquarters.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_commercial.png'],
          ['icon' => 'fa-house-chimney',     'title' => 'Residential Contracting',  'desc' => 'Custom luxury villas, housing developments, and private family residences.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_residential.png'],
          ['icon' => 'fa-city',              'title' => 'Civil Infrastructure',       'desc' => 'Skyscraper developments, bridges, highways, and municipal projects.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_civil.png'],
          ['icon' => 'fa-hammer',            'title' => 'Structural Renovation',    'desc' => 'Historic building restoration, structural retrofitting, and modern upgrades.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_renovation.png'],
          ['icon' => 'fa-compass-drafting',  'title' => 'Architectural Engineering', 'desc' => 'BIM modeling, structural engineering blueprints, and site planning.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_industrial.png'],
          ['icon' => 'fa-helmet-safety',     'title' => 'Project Supervision',      'desc' => 'Turnkey site management, safety compliance, and quality auditing.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_infrastructure.png'],
          ['icon' => 'fa-ruler-combined',    'title' => 'Interior Fit-Out',         'desc' => 'Luxury interior acoustic ceiling, partitions, and custom millwork.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_commercial.png'],
          ['icon' => 'fa-leaf',              'title' => 'Green Sustainable Build',  'desc' => 'LEED-certified eco-friendly building materials and solar integration.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_residential.png'],
        ];
      }
    @endphp

    <div class="d-flex gap-3 overflow-auto py-2 service-scroll-track" id="cnServicesTrack" style="scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;">
      @foreach($services as $service)
      @php
        $srvImg = $service['image'] ?? 'assets/website_builder/Templates/Construction_agency/service_residential.png';
        $srvImgUrl = str_starts_with($srvImg, 'http') ? $srvImg : asset(ltrim($srvImg, '/'));
      @endphp
      <div class="cn-service-card-ref flex-shrink-0" style="flex: 0 0 calc(25% - 18px); min-width: 270px; max-width: 310px;">
        <div class="cn-service-img-wrap" style="transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.03)';" onmouseout="this.style.transform='none';">
          <img src="{{ $srvImgUrl }}" alt="{{ $service['title'] ?? '' }}" class="cn-service-ref-img" loading="lazy">
          <div class="cn-service-icon-badge">
            <i class="fa-solid {{ $service['icon'] ?? 'fa-building' }}"></i>
          </div>
        </div>
        <div class="cn-service-ref-body">
          <h3 class="cn-service-ref-title">{{ $service['title'] ?? '' }}</h3>
          <p class="cn-service-ref-desc">{{ $service['desc'] ?? '' }}</p>
          <a href="{{ $servicesUrl }}" class="cn-service-ref-arrow">
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif


{{-- =====================================================================
     3. ABOUT US SECTION (BELOW SERVICES SECTION!)
     ===================================================================== --}}
<section class="cn-section cn-about-ref-section" id="about">
  <div class="cn-container">
    <div class="row align-items-center g-4 g-lg-5 mb-5">
      <div class="col-lg-6">
        <div class="cn-section-label">{{ $agency->about_badge ?? 'ABOUT US' }}</div>
        <h2 class="cn-section-heading">
          {!! nl2br(e($agency->about_hero_title ?? "More Than Just Construction\nWe Build Better Lives")) !!}
        </h2>
        <p class="cn-section-sub mb-4">
          {{ $agency->about_hero_subtitle ?? 'BuildCraft committed to delivering exceptional construction solutions for residential, commercial, and infrastructure projects. With a focus on quality, innovation, and sustainability, we create spaces that inspire and last for generations.' }}
        </p>
        <a href="{{ $aboutUrl }}" class="cn-btn cn-btn-yellow">
          {{ $agency->about_primary_btn_text ?? 'Learn More' }} <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>

      {{-- 3 Feature Cards (Mission, Vision, Values) --}}
      <div class="col-lg-6">
        <div class="cn-about-cards-grid">
          <div class="cn-about-card">
            <div class="cn-about-card-icon"><i class="fa-solid fa-bullseye"></i></div>
            <h4 class="cn-about-card-title">{{ $agency->mission_title ?? 'Our Mission' }}</h4>
            <p class="cn-about-card-desc">{{ $agency->mission_text ?? 'To deliver high-quality construction solutions that enhance communities and create lasting value.' }}</p>
          </div>
          <div class="cn-about-card">
            <div class="cn-about-card-icon"><i class="fa-solid fa-eye"></i></div>
            <h4 class="cn-about-card-title">{{ $agency->vision_title ?? 'Our Vision' }}</h4>
            <p class="cn-about-card-desc">{{ $agency->vision_text ?? 'To be a leading global construction company known for innovation, sustainability, and excellence.' }}</p>
          </div>
          <div class="cn-about-card cn-about-card-full">
            <div class="cn-about-card-icon"><i class="fa-solid fa-gem"></i></div>
            <h4 class="cn-about-card-title">{{ $agency->values_title ?? 'Our Values' }}</h4>
            <div class="cn-values-list">
              @php
                $valText = $agency->values_text ?? 'Safety First, Integrity & Transparency, Quality in Every Detail, Customer Satisfaction, Sustainable Growth';
                $valItems = array_map('trim', explode(',', $valText));
              @endphp
              @foreach($valItems as $vi)
                <span><i class="fa-solid fa-circle-check text-warning me-1"></i> {{ $vi }}</span>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Dark Stats Bar (4 columns) --}}
    @php
      $statsBar = $agency->stats_data ?? [
        ['number' => '15+',   'label' => 'Years of Experience', 'icon' => 'fa-users'],
        ['number' => '320+',  'label' => 'Projects Completed',  'icon' => 'fa-file-lines'],
        ['number' => '98%',   'label' => 'Client Satisfaction', 'icon' => 'fa-star'],
        ['number' => '24/7',  'label' => 'Project Support',     'icon' => 'fa-headset'],
      ];
    @endphp
    <div class="cn-dark-stats-bar">
      <div class="row text-center g-3">
        @foreach($statsBar as $st)
          <div class="col-6 col-md-3">
            <div class="cn-dark-stat-item">
              <div class="cn-dark-stat-icon"><i class="fa-solid {{ $st['icon'] ?? 'fa-building' }}"></i></div>
              <div class="cn-dark-stat-num" data-target="{{ $st['number'] ?? '' }}">{{ $st['number'] ?? '' }}</div>
              <div class="cn-dark-stat-label">{{ $st['label'] ?? '' }}</div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>


{{-- =====================================================================
     4. FEATURED PROJECTS SECTION (Portfolio)
     ===================================================================== --}}
@if(count($portfolio) > 0)
<section class="cn-section cn-section-light" id="projects">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <div class="cn-section-label">{{ $agency->portfolio_badge ?? 'OUR PROJECTS' }}</div>
        <h2 class="cn-section-heading">{{ $agency->portfolio_title ?? 'Featured Projects' }}</h2>
        <p class="cn-section-sub">{{ $agency->portfolio_subtitle ?? 'Explore some of our recently completed projects across various sectors.' }}</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <button class="cn-nav-arrow" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="cn-nav-arrow" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    <div class="cn-projects-grid-5">
      @foreach(array_slice($portfolio, 0, 5) as $project)
      @php
        $prjImg = $project['image'] ?? $project['img'] ?? 'assets/website_builder/Templates/Construction_agency/service_residential.png';
      @endphp
      <div class="cn-project-card-ref">
        <div class="cn-project-img-wrap">
          <img src="{{ str_starts_with($prjImg, 'http') ? $prjImg : asset(ltrim($prjImg, '/')) }}" alt="{{ $project['title'] ?? '' }}" class="cn-project-ref-img" loading="lazy">
          <span class="cn-project-badge">{{ $project['category'] ?? 'Construction' }}</span>
        </div>
        <div class="cn-project-ref-body d-flex justify-content-between align-items-center">
          <div>
            <h4 class="cn-project-ref-title">{{ $project['title'] }}</h4>
            <div class="cn-project-ref-loc"><i class="fa-solid fa-location-dot me-1 text-warning"></i>{{ $project['location'] ?? 'USA' }}</div>
          </div>
          <a href="{{ $portfolioUrl }}" class="cn-project-arrow-btn">
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif


{{-- =====================================================================
     5. TESTIMONIALS SECTION ("What Our Clients Say")
     ===================================================================== --}}
@if(count($testimonials) > 0)
<section class="cn-section cn-section-grey" id="testimonials">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <div class="cn-section-label">{{ $agency->testimonials_badge ?? 'TESTIMONIALS' }}</div>
        <h2 class="cn-section-heading">{!! nl2br(e($agency->testimonials_title ?? 'What Our Clients Say')) !!}</h2>
        <p class="cn-section-sub">{{ $agency->testimonials_subtitle ?? 'Real stories from our valued clients who have built their dreams with us.' }}</p>
      </div>
      <div class="d-flex gap-2">
        <button class="cn-nav-arrow" type="button" aria-label="Previous" onclick="document.getElementById('cnTestiTrack').scrollBy({left: -340, behavior: 'smooth'});"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="cn-nav-arrow" type="button" aria-label="Next" onclick="document.getElementById('cnTestiTrack').scrollBy({left: 340, behavior: 'smooth'});"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    <div class="d-flex gap-3 overflow-auto py-2 testimonial-scroll-track" id="cnTestiTrack" style="scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;">
      @foreach($testimonials as $idx => $t)
      @php
        $avatar = $t['avatar'] ?? $t['image'] ?? $t['photo'] ?? '';
        if (empty($avatar) || str_contains($avatar, 'unsplash.com')) {
          $avatar = asset('assets/website_builder/Templates/Construction_agency/team_' . (($idx % 3) + 1) . '.png');
        } else {
          $avatar = str_starts_with($avatar, 'http') ? $avatar : asset(ltrim($avatar, '/'));
        }
      @endphp
      <div class="cn-testimonial-ref-card flex-shrink-0" style="width: calc((100% - 32px) / 3); min-width: 280px;">
        <div class="cn-quote-mark"><i class="fa-solid fa-quote-left text-warning"></i></div>
        <p class="cn-testimonial-quote">"{{ $t['comment'] ?? '' }}"</p>
        <div class="d-flex align-items-center gap-3 mt-4">
          <img src="{{ $avatar }}" alt="{{ $t['name'] ?? '' }}" class="cn-testimonial-avatar">
          <div>
            <h5 class="cn-testimonial-name mb-0">{{ $t['name'] ?? '' }}</h5>
            <span class="cn-testimonial-role">{{ $t['role'] ?? '' }}</span>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif


{{-- =====================================================================
     6. FOOTER CTA BANNER ("LET'S BUILD TOGETHER") — Ref Image 4 Match
     ===================================================================== --}}
<section class="cn-footer-cta-wrapper">
  <div class="cn-container">
    @php
      $defaultCtaBg = asset('assets/website_builder/Templates/Construction_agency/construction_footercta.png');
      $cBg = $agency->cta_banner_image ?? '';
      $ctaBgUrl = !empty($cBg) ? (str_starts_with($cBg, 'http') ? $cBg : asset(ltrim($cBg, '/'))) : $defaultCtaBg;
    @endphp
    <div class="cn-footer-cta-card" style="background: url('{{ $ctaBgUrl }}') no-repeat center center / cover;">
      <div class="cn-cta-overlay"></div>
      
      {{-- Script Overlay --}}
      <div class="cn-cta-script-overlay d-none d-lg-block">
        Quality Structures Brighter Tomorrow
      </div>

      <div style="position: relative; z-index: 2;">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="cn-section-label text-warning mb-2">
              {{ $agency->cta_banner_badge ?? "LET'S BUILD TOGETHER" }}
            </div>
            <h2 class="cn-cta-title text-white fw-extrabold mb-3">
              {!! nl2br(e($agency->cta_banner_title ?? "Ready to Build Your Vision?")) !!}
            </h2>
            <p class="cn-cta-sub mb-4">
              {{ $agency->cta_banner_subtitle ?? "From concept to completion, we're here to bring your ideas to life." }}
            </p>
            <a href="{{ $agency->cta_banner_btn_url ?? $contactUrl }}" class="cn-btn cn-btn-yellow">
              {{ $agency->cta_banner_btn_text ?? 'Request a Quote' }} <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>

          {{-- Right Side Vertical Step Column (Image 4 Match) --}}
          <div class="col-lg-5 mt-4 mt-lg-0 d-none d-md-block">
            <div class="cn-cta-steps-vertical">
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-compass-drafting"></i></div>
                <div class="cn-step-text">Plan</div>
              </div>
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-helmet-safety"></i></div>
                <div class="cn-step-text">Build</div>
              </div>
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-truck-ramp-box"></i></div>
                <div class="cn-step-text">Deliver</div>
              </div>
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-trophy"></i></div>
                <div class="cn-step-text">Succeed</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var cnTrack = document.getElementById('cnServicesTrack');
    if (cnTrack) {
      var isPaused = false;
      cnTrack.addEventListener('mouseenter', function() { isPaused = true; });
      cnTrack.addEventListener('mouseleave', function() { isPaused = false; });
      cnTrack.addEventListener('touchstart', function() { isPaused = true; }, {passive: true});
      cnTrack.addEventListener('touchend', function() { isPaused = false; }, {passive: true});

      setInterval(function() {
        if (!isPaused) {
          var firstCard = cnTrack.querySelector('.cn-service-card-ref');
          var step = firstCard ? (firstCard.offsetWidth + 16) : 340;
          if (cnTrack.scrollLeft + cnTrack.clientWidth >= cnTrack.scrollWidth - 10) {
            cnTrack.scrollTo({ left: 0, behavior: 'smooth' });
          } else {
            cnTrack.scrollBy({ left: step, behavior: 'smooth' });
          }
        }
      }, 3500);
    }

    var cnTestiTrack = document.getElementById('cnTestiTrack');
    if (cnTestiTrack) {
      var isTPaused = false;
      cnTestiTrack.addEventListener('mouseenter', function() { isTPaused = true; });
      cnTestiTrack.addEventListener('mouseleave', function() { isTPaused = false; });
      cnTestiTrack.addEventListener('touchstart', function() { isTPaused = true; }, {passive: true});
      cnTestiTrack.addEventListener('touchend', function() { isTPaused = false; }, {passive: true});

      setInterval(function() {
        if (!isTPaused) {
          var firstCard = cnTestiTrack.querySelector('.cn-testimonial-ref-card');
          var step = firstCard ? (firstCard.offsetWidth + 16) : 340;
          if (cnTestiTrack.scrollLeft + cnTestiTrack.clientWidth >= cnTestiTrack.scrollWidth - 10) {
            cnTestiTrack.scrollTo({ left: 0, behavior: 'smooth' });
          } else {
            cnTestiTrack.scrollBy({ left: step, behavior: 'smooth' });
          }
        }
      }, 3500);
    }
  });
</script>
@endsection
