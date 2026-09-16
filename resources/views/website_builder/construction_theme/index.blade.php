@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — Building Dreams, Shaping the Future')
@section('description', $agency->hero_subtitle ?? 'Quality construction, on time and within budget. From foundations to finishes, we deliver excellence.')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.contact');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.about');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.portfolio');
  $servicesUrl  = $subdomainParam ? route('website-builder.subdomain.services',  ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.services');
  $heroBg = asset($agency->hero_image ?? 'assets/website_builder/Templates/Construction_agency/herobanner_image.png');
  $stats   = $agency->stats_data ?? [];
  $services = $agency->services_data ?? [];
  $portfolio = $agency->portfolio_data ?? [];
  $testimonials = $agency->testimonials_data ?? [];
  $team = $agency->team_members_data ?? [];
  $specializations = $agency->construction_data['specializations'] ?? [];
@endphp

{{-- =====================================================================
     HERO SECTION
     ===================================================================== --}}
<section class="cn-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 620px;">
  <div class="cn-hero-overlay"></div>
  <div class="cn-container" style="width:100%;">
    <div class="cn-hero-content">
      <div class="cn-hero-badge cn-animate">
        <i class="fa-solid fa-helmet-safety"></i>
        {{ $agency->hero_badge ?? '🏗️ Award-Winning Construction Company' }}
      </div>
      <h1 class="cn-hero-title cn-animate cn-animate-delay-1">
        @php
          $titleLines = explode("\n", $agency->hero_title ?? "Building Dreams\nShaping the Future");
        @endphp
        {!! isset($titleLines[0]) ? e($titleLines[0]) : 'Building Dreams' !!}
        @if(isset($titleLines[1]))
          <br><span>{{ $titleLines[1] }}</span>
        @endif
      </h1>
      <p class="cn-hero-subtitle cn-animate cn-animate-delay-2">
        {{ $agency->hero_subtitle ?? 'Quality construction, on time and within budget. From foundations to finishes, we deliver excellence.' }}
      </p>
      <div class="cn-hero-cta cn-animate cn-animate-delay-3">
        <a href="{{ $agency->primary_btn_url ?? $contactUrl }}" class="cn-btn cn-btn-primary">
          <i class="fa-solid fa-file-lines"></i>
          {{ $agency->primary_btn_text ?? 'Get Free Quote' }}
        </a>
        <a href="{{ $agency->secondary_btn_url ?? $portfolioUrl }}" class="cn-btn cn-btn-outline">
          {{ $agency->secondary_btn_text ?? 'View Our Projects' }}
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
      <div class="cn-hero-trust">
        <div class="cn-trust-item">
          <div class="cn-trust-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <div class="cn-trust-label">ISO Certified</div>
            <div class="cn-trust-sub">Safety Standards</div>
          </div>
        </div>
        <div class="cn-trust-item">
          <div class="cn-trust-icon"><i class="fa-solid fa-clock"></i></div>
          <div>
            <div class="cn-trust-label">On-Time</div>
            <div class="cn-trust-sub">98% Delivery Rate</div>
          </div>
        </div>
        <div class="cn-trust-item">
          <div class="cn-trust-icon"><i class="fa-solid fa-award"></i></div>
          <div>
            <div class="cn-trust-label">Award-Winning</div>
            <div class="cn-trust-sub">Since 1999</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- =====================================================================
     STATS BAR
     ===================================================================== --}}
@if(count($stats) > 0)
<div class="cn-stats-bar">
  <div class="cn-container" style="padding:0;">
    <div class="cn-stats-inner">
      @foreach($stats as $stat)
      <div class="cn-stat-item">
        <div class="cn-stat-number">{{ $stat['number'] ?? '500+' }}</div>
        <div class="cn-stat-label">{{ $stat['label'] ?? 'Projects' }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endif

{{-- =====================================================================
     SERVICES SECTION
     ===================================================================== --}}
@if(count($services) > 0)
<section class="cn-section cn-section-alt" id="services">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-tag"><i class="fa-solid fa-gear"></i> What We Build</div>
      <h2 class="cn-section-title">Our Core <span>Services</span></h2>
      <div class="cn-divider cn-divider-center"></div>
      <p class="cn-section-subtitle">From groundbreaking to finishing — we handle every aspect of construction with precision and expertise.</p>
    </div>
    <div class="cn-services-grid">
      @foreach($services as $service)
      <div class="cn-service-card">
        @if(!empty($service['image']))
          <img src="{{ $service['image'] }}" alt="{{ $service['title'] }}" class="cn-service-img" loading="lazy">
        @endif
        <div class="cn-service-body">
          <div class="cn-service-icon-wrap">
            <i class="fa-solid {{ $service['icon'] ?? 'fa-building' }}"></i>
          </div>
          <div class="cn-service-title">{{ $service['title'] }}</div>
          <div class="cn-service-desc">{{ $service['desc'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="text-center mt-5">
      <a href="{{ $servicesUrl }}" class="cn-btn cn-btn-outline-orange">
        View All Services <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>
@endif

{{-- =====================================================================
     PROJECTS PORTFOLIO SECTION
     ===================================================================== --}}
@if(count($portfolio) > 0)
<section class="cn-section" id="projects">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <div class="cn-section-tag"><i class="fa-solid fa-folder-open"></i> Our Work</div>
        <h2 class="cn-section-title">Featured <span>Projects</span></h2>
        <div class="cn-divider"></div>
      </div>
      <a href="{{ $portfolioUrl }}" class="cn-btn cn-btn-outline-orange">
        View All Projects <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    {{-- Filter tabs --}}
    <div class="cn-filter-tabs">
      <button class="cn-filter-tab active" data-cat="all">All Projects</button>
      @php
        $cats = array_unique(array_column($portfolio, 'category'));
      @endphp
      @foreach($cats as $cat)
        <button class="cn-filter-tab" data-cat="{{ $cat }}">{{ $cat }}</button>
      @endforeach
    </div>

    <div class="cn-projects-grid">
      @foreach($portfolio as $project)
      <div class="cn-project-card" data-cat="{{ $project['category'] ?? 'Other' }}">
        <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}" class="cn-project-img" loading="lazy">
        <div class="cn-project-overlay"></div>
        <div class="cn-project-body">
          <span class="cn-project-cat">{{ $project['category'] ?? 'Construction' }}</span>
          <div class="cn-project-title">{{ $project['title'] }}</div>
          <div class="cn-project-meta">
            <i class="fa-solid fa-location-dot me-1"></i>{{ $project['location'] ?? '' }}
            @if(!empty($project['year']))
              &nbsp;·&nbsp;<i class="fa-solid fa-calendar me-1"></i>{{ $project['year'] }}
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- =====================================================================
     WHY CHOOSE US — SPECIALIZATIONS
     ===================================================================== --}}
@if(count($specializations) > 0)
<section class="cn-section cn-section-alt" id="why-us">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-tag"><i class="fa-solid fa-star"></i> Why Choose Us</div>
      <h2 class="cn-section-title">Built on <span>Excellence</span></h2>
      <div class="cn-divider cn-divider-center"></div>
      <p class="cn-section-subtitle">We combine decades of expertise with cutting-edge technology to deliver construction that stands the test of time.</p>
    </div>
    <div class="cn-why-grid">
      @foreach($specializations as $item)
      <div class="cn-why-card">
        <div class="cn-why-icon">
          <i class="fa-solid {{ $item['icon'] ?? 'fa-star' }}"></i>
        </div>
        <div class="cn-why-title">{{ $item['title'] }}</div>
        <div class="cn-why-desc">{{ $item['desc'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- =====================================================================
     TESTIMONIALS
     ===================================================================== --}}
@if(count($testimonials) > 0)
<section class="cn-section" id="testimonials">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-tag"><i class="fa-solid fa-comments"></i> Client Reviews</div>
      <h2 class="cn-section-title">What Our Clients <span>Say</span></h2>
      <div class="cn-divider cn-divider-center"></div>
      <p class="cn-section-subtitle">Don't just take our word for it — hear from the clients whose projects we've brought to life.</p>
    </div>
    <div class="cn-testimonials-grid">
      @foreach($testimonials as $t)
      <div class="cn-testimonial-card">
        <div class="cn-stars">
          @for($i=0;$i<5;$i++)<i class="fa-solid fa-star"></i>@endfor
        </div>
        <p class="cn-testimonial-text">"{{ $t['comment'] }}"</p>
        <div class="cn-testimonial-author">
          <img src="{{ $t['avatar'] }}" alt="{{ $t['name'] }}" class="cn-author-avatar" loading="lazy">
          <div>
            <div class="cn-author-name">{{ $t['name'] }}</div>
            <div class="cn-author-role">{{ $t['role'] }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- =====================================================================
     TEAM MEMBERS
     ===================================================================== --}}
@if(count($team) > 0)
<section class="cn-section cn-section-alt" id="team">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-tag"><i class="fa-solid fa-users"></i> Expert Team</div>
      <h2 class="cn-section-title">Meet Our <span>Leaders</span></h2>
      <div class="cn-divider cn-divider-center"></div>
      <p class="cn-section-subtitle">Our team of engineers, architects, and project managers bring decades of expertise to every project.</p>
    </div>
    <div class="cn-team-grid">
      @foreach($team as $member)
      <div class="cn-team-card">
        <div class="cn-team-img-wrap">
          <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="cn-team-img" loading="lazy">
          <div class="cn-team-overlay">
            @if(!empty($member['social']['linkedin']))
              <a href="{{ $member['social']['linkedin'] }}" class="cn-social-btn"><i class="fab fa-linkedin-in"></i></a>
            @endif
            @if(!empty($member['social']['twitter']))
              <a href="{{ $member['social']['twitter'] }}" class="cn-social-btn"><i class="fab fa-twitter"></i></a>
            @endif
          </div>
        </div>
        <div class="cn-team-body">
          <div class="cn-team-name">{{ $member['name'] }}</div>
          <div class="cn-team-role">{{ $member['role'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- =====================================================================
     CTA BANNER
     ===================================================================== --}}
<section class="cn-cta-section" id="contact">
  <div class="cn-container">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h2 class="cn-cta-title">Ready to Start Your <br>Dream Construction Project?</h2>
        <p class="cn-cta-subtitle">Talk to our experts today and get a free consultation and project estimate. Let's build something extraordinary together.</p>
      </div>
      <div class="col-lg-4 text-lg-end d-flex gap-3 justify-content-lg-end flex-wrap mt-3 mt-lg-0">
        <a href="{{ $contactUrl }}" class="cn-btn cn-btn-cta-dark">
          <i class="fa-solid fa-file-lines"></i> Get Free Quote
        </a>
        <a href="tel:{{ $agency->phone ?? '+18002845348' }}" class="cn-btn cn-btn-cta-white">
          <i class="fa-solid fa-phone"></i> Call Us Now
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
