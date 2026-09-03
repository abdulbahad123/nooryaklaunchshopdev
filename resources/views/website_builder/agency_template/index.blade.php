@extends('website_builder.agency_template.layout')

@section('title', 'DesignAGENCY - Creative Digital Solutions Agency')

@section('content')
<!-- ===== HERO SECTION (Ref Image 1 Match) ===== -->
<section style="background: linear-gradient(180deg, #F0FDF4 0%, #FFFFFF 100%); padding: 75px 0 60px; position: relative;">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="agency-label-pill" style="background: #ECFDF5; color: #059669; border-radius: 30px; padding: 6px 18px;">
          <span style="font-size: 14px; font-weight: bold; margin-right: 4px;">•</span> Creative Digital Solutions
        </div>
        <h1 class="agency-heading my-3" style="font-size: clamp(36px, 5.2vw, 56px); font-weight: 800; line-height: 1.15; color: #0F172A; letter-spacing: -1px;">
          Increase Your<br>
          Customers <span style="color: #10B981; position: relative; display: inline-block;">Loyalty<svg style="position: absolute; bottom: -6px; left: 0; width: 100%; height: 8px;" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0,15 Q50,0 100,15" stroke="#10B981" stroke-width="4" fill="none"/></svg></span><br>
          and Satisfaction
        </h1>
        <p class="agency-subtitle mb-4 text-secondary" style="font-size: 16px; line-height: 1.65; max-width: 540px;">
          {{ $agency->hero_subtitle ?? 'We help businesses like yours earn more customers, stand out from competitors, and grow your revenue.' }}
        </p>
        <div class="d-flex align-items-center gap-3 flex-wrap mb-5">
          <a href="{{ route('website-builder.templates.design-agency.contact') }}" class="btn-agency-register rounded-pill" style="padding: 14px 34px; font-size: 15px; font-weight: 700; background: #10B981; color: #fff;">
            Get Started <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
          <a href="#portfolio" class="btn-agency-login rounded-pill" style="padding: 14px 28px; font-size: 15px; font-weight: 700; border-color: #10B981; color: #10B981;">
            View Our Work <i class="fa-regular fa-circle-play ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Right Hero Graphic / Hero Photo -->
      <div class="col-lg-6">
        <div class="position-relative text-center">
          <img src="{{ asset($agency->hero_image ?? 'assets/website_builder/Templates/Digital_agency/hero_banner.png') }}" 
               onerror="this.src='{{ asset('assets/website_builder/Templates/Digital_agency/hero_banner.png') }}';" 
               alt="Creative Digital Solutions Agency" 
               style="max-width: 100%; height: auto; max-height: 520px; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.06));">
        </div>
      </div>
    </div>

    <!-- Floating 4 Stats Bar Box (Ref Image 1 Match) -->
    <div class="card border-0 shadow-lg mt-5" style="border-radius: 24px; padding: 32px 24px; background: #ffffff;">
      <div class="row g-4 text-center">
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ECFDF5; color: #10B981; font-size: 22px;">
              <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="text-start">
              <h3 class="fw-extrabold mb-0 text-slate-900" style="font-size: 26px;">8+</h3>
              <div class="small text-muted fw-semibold" style="font-size: 13px;">Years of Experience</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ECFDF5; color: #10B981; font-size: 22px;">
              <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="text-start">
              <h3 class="fw-extrabold mb-0 text-slate-900" style="font-size: 26px;">120+</h3>
              <div class="small text-muted fw-semibold" style="font-size: 13px;">Projects Completed</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ECFDF5; color: #10B981; font-size: 22px;">
              <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="text-start">
              <h3 class="fw-extrabold mb-0 text-slate-900" style="font-size: 26px;">98%</h3>
              <div class="small text-muted fw-semibold" style="font-size: 13px;">Client Satisfaction</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ECFDF5; color: #10B981; font-size: 22px;">
              <i class="fa-solid fa-headset"></i>
            </div>
            <div class="text-start">
              <h3 class="fw-extrabold mb-0 text-slate-900" style="font-size: 26px;">24/7</h3>
              <div class="small text-muted fw-semibold" style="font-size: 13px;">Support Available</div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ===== OUR SERVICES SECTION (Ref Image 2 Match) ===== -->
<section id="services" style="padding: 90px 0; background: #FFFFFF; position: relative;">
  <div class="container">
    <div class="text-center mb-5">
      <div class="agency-label-pill mx-auto" style="background: #ECFDF5; color: #059669; border-radius: 30px; padding: 5px 16px;">WHAT WE DO</div>
      <h2 class="agency-heading fw-extrabold" style="font-size: 38px;">Our Services</h2>
      <p class="agency-subtitle mx-auto text-secondary" style="max-width: 580px; font-size: 15px;">
        We provide a wide range of digital services to help your business grow, stand out, and succeed in the digital world.
      </p>
    </div>

    <!-- 6 Cards Grid with Navigation Arrows (Ref Image 2 Match) -->
    <div class="position-relative">
      <!-- Left Arrow -->
      <button class="btn btn-light rounded-circle shadow-sm border position-absolute top-50 start-0 translate-middle-y d-none d-xl-flex align-items-center justify-content-center" style="width: 44px; height: 44px; left: -24px; z-index: 10;">
        <i class="fa-solid fa-chevron-left text-success"></i>
      </button>

      <!-- Right Arrow -->
      <button class="btn btn-light rounded-circle shadow-sm border position-absolute top-50 end-0 translate-middle-y d-none d-xl-flex align-items-center justify-content-center" style="width: 44px; height: 44px; right: -24px; z-index: 10;">
        <i class="fa-solid fa-chevron-right text-success"></i>
      </button>

      <div class="row g-4">
        @php
          $services = $agency->services_data ?? [
            ['icon' => 'fa-laptop-code',     'title' => 'Web Design',       'desc' => 'Beautiful, modern, and responsive websites that drive results.'],
            ['icon' => 'fa-layer-group',     'title' => 'UI/UX Design',     'desc' => 'User-centered designs that create seamless digital experiences.'],
            ['icon' => 'fa-bezier-curve',    'title' => 'Branding',         'desc' => 'Unique brand identities that make your business memorable.'],
            ['icon' => 'fa-bullhorn',        'title' => 'Digital Marketing','desc' => 'Data-driven marketing strategies that boost your visibility.'],
            ['icon' => 'fa-magnifying-glass','title' => 'SEO Optimization', 'desc' => 'Improve your search rankings and drive organic traffic.'],
            ['icon' => 'fa-mobile-screen',   'title' => 'App Development',  'desc' => 'Powerful and scalable apps for iOS & Android platforms.'],
          ];
        @endphp

        @foreach($services as $srv)
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card h-100 border p-4 text-center" style="background: #FFFFFF; border-color: #F1F5F9; border-radius: 20px; box-shadow: 0 4px 18px rgba(0,0,0,0.02); transition: all 0.3s;" onmouseover="this.style.boxShadow='0 16px 36px rgba(16,185,129,0.14)'; this.style.transform='translateY(-4px)';" onmouseout="this.style.boxShadow='0 4px 18px rgba(0,0,0,0.02)'; this.style.transform='none';">
              <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 58px; height: 58px; background: #ECFDF5; color: #10B981; font-size: 22px;">
                <i class="fa-solid {{ $srv['icon'] }}"></i>
              </div>
              <h5 class="fw-bold fs-6 text-slate-900 mb-2">{{ $srv['title'] }}</h5>
              <p class="text-muted small mb-4 flex-grow-1" style="font-size: 12.5px; line-height: 1.55;">{{ $srv['desc'] }}</p>
              <a href="#" class="fw-bold text-decoration-none small mt-auto" style="color: #10B981;">Learn More <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- ===== OUR RECENT WORK (PORTFOLIO) ===== -->
<section id="portfolio" style="padding: 90px 0; background: #F8FAFC;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <div class="agency-label-pill">OUR WORK</div>
        <h2 class="agency-heading mb-0">Our Recent Work</h2>
      </div>
      <a href="{{ route('website-builder.templates.design-agency') }}#portfolio" class="btn btn-outline-success fw-bold px-4 rounded-pill" style="border-width: 1.5px;">View All Projects <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i></a>
    </div>

    <!-- Category Filter Tabs (Ref Image 1 Match) -->
    <div class="d-flex align-items-center gap-2 flex-wrap mb-4">
      <button type="button" class="btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background: #10B981; font-size: 13.5px;">All</button>
      <button type="button" class="btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;">Web Design</button>
      <button type="button" class="btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;">UI/UX Design</button>
      <button type="button" class="btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;">Branding</button>
      <button type="button" class="btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;">App Design</button>
      <button type="button" class="btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;">Marketing</button>
    </div>

    @php
      $portfolio = $agency->portfolio_data ?? [
        ['title' => 'Fintech Website Redesign', 'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'E-commerce Skincare Store', 'category' => 'Web Design',   'image' => 'assets/website_builder/wb_card_ecommerce.png'],
        ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Brand Identity Design',    'category' => 'Branding',      'image' => 'assets/website_builder/wb_card_portfolio.png'],
        ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_restaurant.png'],
        ['title' => 'Travel Website',           'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_events.png'],
        ['title' => 'Fitness App Design',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Digital Marketing Campaign','category' => 'Marketing',    'image' => 'assets/website_builder/wb_card_agency.png'],
      ];
    @endphp

    <div class="row g-4">
      @foreach($portfolio as $port)
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 h-100 overflow-hidden shadow-sm" style="border-radius: 16px;">
            <div style="height: 190px; overflow: hidden; background: #0F172A;" class="position-relative">
              <img src="{{ asset($port['image']) }}" 
                   onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=600&auto=format&fit=crop';"
                   alt="{{ $port['title'] }}" 
                   style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                   onmouseover="this.style.transform='scale(1.05)';"
                   onmouseout="this.style.transform='scale(1)';"
                   loading="lazy">
            </div>
            <div class="p-3 bg-white">
              <h5 class="fw-bold fs-6 mb-1 text-slate-900">{{ $port['title'] }}</h5>
              <div class="text-muted" style="font-size: 12px; font-weight: 600;">{{ $port['category'] }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS SECTION ===== -->
<section style="padding: 90px 0; background: #FFFFFF;">
  <div class="container">
    <div class="text-center mb-5">
      <div class="agency-label-pill mx-auto">TESTIMONIALS</div>
      <h2 class="agency-heading">What Our Clients Say</h2>
      <p class="agency-subtitle mx-auto">
        We're proud to have helped so many businesses grow and succeed.
      </p>
    </div>

    <div class="row g-4">
      @php
        $testimonials = $agency->testimonials_data ?? [
          ['name' => 'John Smith',    'role' => 'CEO, Fineva',       'rating' => 5, 'comment' => '"DesignAGENCY transformed our website and brand identity. The team is professional, creative, and results-driven!"'],
          ['name' => 'Sarah Johnson', 'role' => 'Marketing Director, Digitech', 'rating' => 5, 'comment' => '"Amazing experience from start to finish. They understood our needs and delivered beyond our expectations."'],
          ['name' => 'David Brown',   'role' => 'Founder, Shopious', 'rating' => 5, 'comment' => '"Their designs are modern, clean, and user-friendly. Our customers love the new experience!"'],
        ];
      @endphp

      @foreach($testimonials as $t)
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 border-0 p-4 position-relative" style="background: #F8FAFC; border-radius: 18px;">
            <div class="fs-1 fw-bold text-success opacity-50 mb-1" style="color: #10B981; line-height: 1;">“</div>
            <p class="text-slate-700 fst-italic mb-4 flex-grow-1" style="font-size: 14px; line-height: 1.6;">
              {{ $t['comment'] }}
            </p>
            <div class="mb-3 text-warning">
              @for($s=0; $s<($t['rating'] ?? 5); $s++)
                <i class="fa-solid fa-star"></i>
              @endfor
            </div>
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white fs-5" style="width: 44px; height: 44px; background: #10B981;">
                {{ strtoupper(substr($t['name'], 0, 1)) }}
              </div>
              <div>
                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 14px;">{{ $t['name'] }}</h6>
                <div class="text-muted" style="font-size: 12px;">{{ $t['role'] }}</div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Carousel Dots & Nav Arrows (Ref Image 1 Match) -->
    <div class="d-flex justify-content-between align-items-center mt-5">
      <div class="d-flex gap-2 mx-auto">
        <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background: #10B981;"></span>
        <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background: #CBD5E1;"></span>
        <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background: #CBD5E1;"></span>
      </div>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fa-solid fa-chevron-left text-muted"></i></button>
        <button type="button" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fa-solid fa-chevron-right text-muted"></i></button>
      </div>
    </div>
  </div>
</section>
@endsection
