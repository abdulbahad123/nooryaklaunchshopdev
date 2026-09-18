@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — Building Stronger Futures')
@section('description', $agency->hero_subtitle ?? 'Reliable construction, renovation, and infrastructure solutions built on quality, safety, and trust.')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.contact');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.about');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.portfolio');
  $servicesUrl  = $subdomainParam ? route('website-builder.subdomain.services',  ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.services');

  $heroBg = asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
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

      <div class="cn-hero-cta cn-animate cn-animate-delay-3">
        <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
          Get a Quote <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <a href="{{ $portfolioUrl }}" class="cn-btn cn-btn-pill-dark">
          <span class="cn-play-icon"><i class="fa-solid fa-play"></i></span> Watch Video
        </a>
      </div>

      {{-- Bottom Hero Trust Highlights Bar --}}
      <div class="cn-hero-trust-bar mt-4">
        <div class="cn-trust-item">
          <div class="cn-trust-icon-yellow"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <div class="cn-trust-title">Safe & Quality</div>
            <div class="cn-trust-sub">Construction</div>
          </div>
        </div>
        <div class="cn-trust-item">
          <div class="cn-trust-icon-yellow"><i class="fa-solid fa-users"></i></div>
          <div>
            <div class="cn-trust-title">Experienced</div>
            <div class="cn-trust-sub">Professional Team</div>
          </div>
        </div>
        <div class="cn-trust-item">
          <div class="cn-trust-icon-yellow"><i class="fa-solid fa-clock"></i></div>
          <div>
            <div class="cn-trust-title">On-Time</div>
            <div class="cn-trust-sub">Project Delivery</div>
          </div>
        </div>
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
        <button class="cn-nav-arrow" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="cn-nav-arrow" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    <div class="cn-services-grid-5">
      @foreach(array_slice($services, 0, 5) as $service)
      <div class="cn-service-card-ref">
        <div class="cn-service-img-wrap">
          <img src="{{ asset($service['image'] ?? 'assets/website_builder/Templates/Construction_agency/service_residential.png') }}" alt="{{ $service['title'] }}" class="cn-service-ref-img" loading="lazy">
          <div class="cn-service-icon-badge">
            <i class="fa-solid {{ $service['icon'] ?? 'fa-building' }}"></i>
          </div>
        </div>
        <div class="cn-service-ref-body">
          <h3 class="cn-service-ref-title">{{ $service['title'] }}</h3>
          <p class="cn-service-ref-desc">{{ $service['desc'] }}</p>
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
              <div class="cn-dark-stat-num">{{ $st['number'] ?? '' }}</div>
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
        <button class="cn-nav-arrow" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="cn-nav-arrow" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    <div class="cn-testimonials-grid">
      @foreach($testimonials as $idx => $t)
      @php
        $avatar = $t['avatar'] ?? $t['image'] ?? $t['photo'] ?? '';
        if (empty($avatar) || str_contains($avatar, 'unsplash.com')) {
          $avatar = asset('assets/website_builder/Templates/Construction_agency/team_' . (($idx % 3) + 1) . '.png');
        } else {
          $avatar = str_starts_with($avatar, 'http') ? $avatar : asset(ltrim($avatar, '/'));
        }
      @endphp
      <div class="cn-testimonial-ref-card">
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
    <div class="cn-footer-cta-card" style="background: url('{{ $footerCtaBg }}') no-repeat center center / cover;">
      <div class="cn-cta-overlay"></div>
      
      {{-- Script Overlay --}}
      <div class="cn-cta-script-overlay d-none d-lg-block">
        Quality Structures Brighter Tomorrow
      </div>

      <div style="position: relative; z-index: 2;">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="cn-section-label text-warning mb-2">LET'S BUILD TOGETHER</div>
            <h2 class="cn-cta-title text-white fw-extrabold mb-3">
              Ready to Build <span class="cn-text-yellow">Your Vision?</span>
            </h2>
            <p class="cn-cta-sub mb-4">
              From concept to completion, we're here to bring your ideas to life.
            </p>
            <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
              Request a Quote <i class="fa-solid fa-arrow-right ms-1"></i>
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
