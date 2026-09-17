@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — About Us')
@section('description', $agency->about_hero_subtitle ?? 'Over 25 years of excellence in construction — delivering quality, safety, and innovation across every project.')

@section('content')

@php
  $contactUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.contact', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction.contact');
  $portfolioUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction.portfolio');
  $servicesUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.services', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction.services');
  $homeUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.site', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction');

  $heroBg = asset('assets/website_builder/Templates/Construction_agency/about_hero.png');
  $footerCtaBg = asset('assets/website_builder/Templates/Construction_agency/construction_footercta.png');

  $stats = $agency->stats_data ?? [
    ['icon' => 'fa-building-user', 'number' => '250+', 'label' => 'Projects Completed'],
    ['icon' => 'fa-helmet-safety', 'number' => '100+', 'label' => 'Skilled Professionals'],
    ['icon' => 'fa-trophy',        'number' => '98%',  'label' => 'Client Satisfaction'],
    ['icon' => 'fa-award',         'number' => '15+',  'label' => 'Years of Experience'],
  ];

  $team = [
    [
      'name' => 'Michael Carter',
      'role' => 'Founder & CEO',
      'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&auto=format&fit=crop&q=80',
      'social' => ['linkedin' => '#', 'facebook' => '#', 'instagram' => '#', 'twitter' => '#']
    ],
    [
      'name' => 'Sarah Mitchell',
      'role' => 'Project Manager',
      'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&auto=format&fit=crop&q=80',
      'social' => ['linkedin' => '#', 'facebook' => '#', 'instagram' => '#', 'twitter' => '#']
    ],
    [
      'name' => 'David Thompson',
      'role' => 'Site Engineer',
      'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80',
      'social' => ['linkedin' => '#', 'facebook' => '#', 'instagram' => '#', 'twitter' => '#']
    ],
    [
      'name' => 'Emily Davis',
      'role' => 'Architect',
      'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&auto=format&fit=crop&q=80',
      'social' => ['linkedin' => '#', 'facebook' => '#', 'instagram' => '#', 'twitter' => '#']
    ]
  ];

  $testimonials = [
    [
      'name' => 'James Anderson',
      'role' => 'Business Owner',
      'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=120&auto=format&fit=crop&q=80',
      'comment' => 'BuildCraft delivered our project beyond expectations. Professional, reliable, and highly skilled team!'
    ],
    [
      'name' => 'Sophia Martinez',
      'role' => 'Homeowner',
      'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=120&auto=format&fit=crop&q=80',
      'comment' => 'Exceptional quality and attention to detail. They truly understand client needs.'
    ],
    [
      'name' => 'Robert Wilson',
      'role' => 'Real Estate Developer',
      'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=120&auto=format&fit=crop&q=80',
      'comment' => 'From planning to completion, everything was seamless. I highly recommend BuildCraft!'
    ]
  ];
@endphp

{{-- =====================================================================
     1. HERO SECTION (Ref Image Match)
     ===================================================================== --}}
<section class="cn-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 600px; position: relative;">
  <div class="cn-hero-overlay"></div>
  
  {{-- Script Text --}}
  <div class="cn-hero-script-overlay d-none d-lg-block">
    Construct Innovate Elevate
  </div>

  <div class="cn-container" style="position: relative; z-index: 2; width: 100%;">
    <div class="cn-hero-content">
      <div class="cn-hero-badge">
        <i class="fa-solid fa-shield-halved"></i> TRUSTED CONSTRUCTION PARTNER
      </div>

      <h1 class="cn-hero-title">
        Building Dreams Into <span class="cn-text-yellow">Reality</span>
      </h1>

      <p class="cn-hero-subtitle">
        We deliver innovative construction solutions with quality, safety, and integrity. From concept to completion, we build spaces that inspire and stand the test of time.
      </p>

      <div class="cn-hero-cta">
        <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
          Our Services <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <a href="{{ $portfolioUrl }}" class="cn-btn cn-btn-pill-dark">
          <span class="cn-play-icon"><i class="fa-solid fa-play"></i></span> Watch Video
        </a>
      </div>

      {{-- Bottom Trust Highlights --}}
      <div class="cn-hero-trust-bar mt-4">
        <div class="cn-trust-item">
          <div class="cn-trust-icon-yellow"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <div class="cn-trust-title">Safe &</div>
            <div class="cn-trust-sub">Reliable</div>
          </div>
        </div>
        <div class="cn-trust-item">
          <div class="cn-trust-icon-yellow"><i class="fa-solid fa-users"></i></div>
          <div>
            <div class="cn-trust-title">Experienced</div>
            <div class="cn-trust-sub">Professionals</div>
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
    <i class="fa-solid fa-award me-2 text-warning fs-3"></i>
    <div>
      <div class="fw-bold text-white fs-5" style="line-height:1.1;">15+</div>
      <div class="text-warning small fw-semibold">Years of Excellence</div>
    </div>
  </div>
</section>


{{-- =====================================================================
     2. ABOUT US SECTION (Building More Than Structures)
     ===================================================================== --}}
<section class="cn-section cn-about-ref-section" id="about">
  <div class="cn-container">
    <div class="row align-items-center g-4 g-lg-5 mb-5">
      <div class="col-lg-6">
        <div class="cn-section-label">ABOUT US</div>
        <h2 class="cn-section-heading">
          Building More Than <span class="cn-text-yellow">Structures</span>
        </h2>
        <p class="cn-section-sub mb-4">
          At BuildCraft, we believe in creating spaces that improve lives. With a focus on innovation, sustainability, and quality, we turn ideas into landmark projects that shape a better tomorrow.
        </p>
        <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
          Learn More <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>

      {{-- Mission, Vision, Values Cards --}}
      <div class="col-lg-6">
        <div class="cn-about-cards-grid">
          <div class="cn-about-card">
            <div class="cn-about-card-icon"><i class="fa-solid fa-bullseye"></i></div>
            <h4 class="cn-about-card-title">Our Mission</h4>
            <p class="cn-about-card-desc">To deliver exceptional construction solutions that create lasting value for our clients and communities.</p>
          </div>
          <div class="cn-about-card">
            <div class="cn-about-card-icon"><i class="fa-solid fa-eye"></i></div>
            <h4 class="cn-about-card-title">Our Vision</h4>
            <p class="cn-about-card-desc">To be a global leader in construction, known for innovation, integrity, and a commitment to a sustainable future.</p>
          </div>
          <div class="cn-about-card cn-about-card-full">
            <div class="cn-about-card-icon"><i class="fa-solid fa-gem"></i></div>
            <h4 class="cn-about-card-title">Our Values</h4>
            <div class="cn-values-list">
              <span><i class="fa-solid fa-circle-check text-warning me-1"></i> Integrity & Transparency</span>
              <span><i class="fa-solid fa-circle-check text-warning me-1"></i> Quality Excellence</span>
              <span><i class="fa-solid fa-circle-check text-warning me-1"></i> Safety First</span>
              <span><i class="fa-solid fa-circle-check text-warning me-1"></i> Sustainable Construction</span>
              <span><i class="fa-solid fa-circle-check text-warning me-1"></i> Client-Centric Approach</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Dark Stats Bar (4 columns) --}}
    <div class="cn-dark-stats-bar">
      <div class="row text-center g-3">
        <div class="col-6 col-md-3">
          <div class="cn-dark-stat-item">
            <div class="cn-dark-stat-icon"><i class="fa-solid fa-building"></i></div>
            <div class="cn-dark-stat-num">250+</div>
            <div class="cn-dark-stat-label">Projects Completed</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="cn-dark-stat-item">
            <div class="cn-dark-stat-icon"><i class="fa-solid fa-helmet-safety"></i></div>
            <div class="cn-dark-stat-num">100+</div>
            <div class="cn-dark-stat-label">Skilled Professionals</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="cn-dark-stat-item">
            <div class="cn-dark-stat-icon"><i class="fa-solid fa-trophy"></i></div>
            <div class="cn-dark-stat-num">98%</div>
            <div class="cn-dark-stat-label">Client Satisfaction</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="cn-dark-stat-item">
            <div class="cn-dark-stat-icon"><i class="fa-solid fa-award"></i></div>
            <div class="cn-dark-stat-num">15+</div>
            <div class="cn-dark-stat-label">Years of Experience</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- =====================================================================
     3. MEET OUR TEAM SECTION (Ref Image Match)
     ===================================================================== --}}
<section class="cn-section cn-section-light" id="team">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <div class="cn-section-label">MEET OUR TEAM</div>
        <h2 class="cn-section-heading">The People Who Build <span class="cn-text-yellow">Your Vision</span></h2>
        <p class="cn-section-sub">Our team of experts is committed to delivering excellence in every project.</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ $contactUrl }}" class="cn-btn cn-btn-outline-dark">
          View All Team Members <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <button class="cn-nav-arrow" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="cn-nav-arrow" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    <div class="cn-team-grid">
      @foreach($team as $member)
      <div class="cn-team-card-ref">
        <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="cn-team-card-img" loading="lazy">
        <div class="cn-team-card-body">
          <h4 class="cn-team-card-name">{{ $member['name'] }}</h4>
          <p class="cn-team-card-role">{{ $member['role'] }}</p>
          <div class="cn-team-social-row">
            <a href="#" class="cn-team-social-btn-sm" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="cn-team-social-btn-sm" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="cn-team-social-btn-sm" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="cn-team-social-btn-sm" aria-label="X"><i class="fab fa-x-twitter"></i></a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- =====================================================================
     4. TESTIMONIALS SECTION ("What Our Clients Say")
     ===================================================================== --}}
<section class="cn-section cn-section-grey" id="testimonials">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <div class="cn-section-label">CLIENT TESTIMONIALS</div>
        <h2 class="cn-section-heading">What Our Clients Say</h2>
        <p class="cn-section-sub">Real stories from satisfied clients.</p>
      </div>
      <div class="d-flex gap-2">
        <button class="cn-nav-arrow" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="cn-nav-arrow" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    <div class="cn-testimonials-grid">
      @foreach($testimonials as $t)
      <div class="cn-testimonial-ref-card">
        <div class="cn-quote-mark"><i class="fa-solid fa-quote-left text-warning"></i></div>
        <p class="cn-testimonial-quote">"{{ $t['comment'] }}"</p>
        <div class="cn-stars text-warning my-2">
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
        </div>
        <div class="d-flex align-items-center gap-3 mt-3">
          <img src="{{ $t['avatar'] }}" alt="{{ $t['name'] }}" class="cn-testimonial-avatar">
          <div>
            <h5 class="cn-testimonial-name mb-0">{{ $t['name'] }}</h5>
            <span class="cn-testimonial-role">{{ $t['role'] }}</span>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- =====================================================================
     5. FOOTER CTA BANNER ("Turn Your Ideas Into Reality")
     ===================================================================== --}}
<section class="cn-footer-cta-wrapper">
  <div class="cn-container">
    <div class="cn-footer-cta-card" style="background: url('{{ $footerCtaBg }}') no-repeat center center / cover;">
      <div class="cn-cta-overlay"></div>
      
      <div style="position: relative; z-index: 2;">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="cn-section-label text-warning mb-2">LET'S BUILD TOGETHER</div>
            <h2 class="cn-cta-title text-white fw-extrabold mb-3">
              Turn Your Ideas Into <span class="cn-text-yellow">Reality</span>
            </h2>
            <p class="cn-cta-sub mb-4">
              Partner with BuildCraft for innovative, reliable, and sustainable construction solutions.
            </p>
            <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
              Get a Quote <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>

          {{-- Right Side Vertical Step Column --}}
          <div class="col-lg-5 mt-4 mt-lg-0 d-none d-md-block">
            <div class="cn-cta-steps-vertical">
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-bullseye"></i></div>
                <div class="cn-step-text">Quality Construction</div>
              </div>
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-clock"></i></div>
                <div class="cn-step-text">On-Time Delivery</div>
              </div>
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-user-gear"></i></div>
                <div class="cn-step-text">Expert Team</div>
              </div>
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-leaf"></i></div>
                <div class="cn-step-text">Sustainable Solutions</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
