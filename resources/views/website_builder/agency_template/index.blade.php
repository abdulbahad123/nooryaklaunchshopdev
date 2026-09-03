@extends('website_builder.agency_template.layout')

@section('title', 'DesignAGENCY - Creative Digital Solutions Agency')

@section('content')
<!-- ===== HERO SECTION ===== -->
<section style="background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%); padding: 70px 0 60px; position: relative;">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="agency-label-pill">
          <i class="fa-solid fa-sparkles"></i> Creative Digital Solutions
        </div>
        <h1 class="agency-heading" style="font-size: clamp(34px, 5vw, 54px);">
          Increase Your<br>
          Customers <span class="highlight">Loyalty</span><br>
          and Satisfaction
        </h1>
        <p class="agency-subtitle mb-4">
          {{ $agency->hero_subtitle ?? 'We help businesses like yours earn more customers, stand out from competitors, and grow your revenue.' }}
        </p>
        <div class="d-flex align-items-center gap-3 flex-wrap mb-5">
          <a href="{{ route('website-builder.templates.design-agency.contact') }}" class="btn-agency-register" style="padding: 14px 32px; font-size: 15px;">
            Get Started <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
          <a href="#portfolio" class="btn-agency-login" style="padding: 14px 28px; font-size: 15px;">
            View Our Work <i class="fa-regular fa-circle-play ms-1"></i>
          </a>
        </div>

        <!-- Trusted by Logos -->
        <div class="pt-2">
          <div class="small fw-semibold text-muted mb-3">Trusted by the world's best teams:</div>
          <div class="d-flex align-items-center gap-4 flex-wrap opacity-75">
            <span class="fw-extrabold fs-4" style="color: #4285F4;"><i class="fa-brands fa-google me-1"></i> Google</span>
            <span class="fw-extrabold fs-4" style="color: #0052CC;"><i class="fa-brands fa-trello me-1"></i> Trello</span>
            <span class="fw-extrabold fs-4" style="color: #FF3E6C;"><i class="fa-solid fa-m me-1"></i> monday.com</span>
            <span class="fw-extrabold fs-4" style="color: #000000;"><i class="fa-solid fa-cube me-1"></i> Notion</span>
            <span class="fw-extrabold fs-4" style="color: #4A154B;"><i class="fa-brands fa-slack me-1"></i> slack</span>
          </div>
        </div>
      </div>

      <!-- Right Hero Graphic / Hero Photo -->
      <div class="col-lg-6">
        <div class="position-relative text-center">
          <img src="{{ asset($agency->hero_image ?? 'assets/website_builder/Templates/Digital_agency/hero_banner.png') }}" 
               onerror="this.src='{{ asset('assets/website_builder/Templates/Digital_agency/hero_banner.png') }}';" 
               alt="Creative Digital Solutions Agency" 
               style="max-width: 100%; height: auto; max-height: 520px; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.08));">

          <!-- Floating Badges -->
          <div class="position-absolute" style="top: 20px; left: 10px; background: #FF6B35; color: #fff; padding: 12px; border-radius: 14px; box-shadow: 0 10px 25px rgba(255,107,53,0.3);">
            <i class="fa-solid fa-star fs-5"></i>
          </div>
          <div class="position-absolute" style="top: 30px; right: 20px; background: #10B981; color: #fff; padding: 12px; border-radius: 14px; box-shadow: 0 10px 25px rgba(16,185,129,0.3);">
            <i class="fa-solid fa-check fs-5"></i>
          </div>
          <div class="position-absolute" style="bottom: 40px; left: 30px; background: #059669; color: #fff; padding: 12px; border-radius: 14px; box-shadow: 0 10px 25px rgba(5,150,105,0.3);">
            <i class="fa-solid fa-layer-group fs-5"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating 4 Stats Bar (Ref Image 1) -->
    <div class="card border-0 shadow-lg mt-5" style="border-radius: 20px; padding: 30px 20px;">
      <div class="row g-4 text-center divide-x">
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="p-3 rounded-circle" style="background: #ECFDF5; color: #10B981;"><i class="fa-solid fa-building-columns fs-4"></i></div>
            <div class="text-start">
              <h3 class="fw-extrabold mb-0 text-slate-900">8+</h3>
              <div class="small text-muted fw-semibold">Years of Experience</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="p-3 rounded-circle" style="background: #ECFDF5; color: #10B981;"><i class="fa-solid fa-briefcase fs-4"></i></div>
            <div class="text-start">
              <h3 class="fw-extrabold mb-0 text-slate-900">120+</h3>
              <div class="small text-muted fw-semibold">Projects Completed</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="p-3 rounded-circle" style="background: #ECFDF5; color: #10B981;"><i class="fa-solid fa-circle-check fs-4"></i></div>
            <div class="text-start">
              <h3 class="fw-extrabold mb-0 text-slate-900">98%</h3>
              <div class="small text-muted fw-semibold">Client Satisfaction</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="p-3 rounded-circle" style="background: #ECFDF5; color: #10B981;"><i class="fa-solid fa-headset fs-4"></i></div>
            <div class="text-start">
              <h3 class="fw-extrabold mb-0 text-slate-900">24/7</h3>
              <div class="small text-muted fw-semibold">Support Available</div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ===== OUR SERVICES SECTION ===== -->
<section id="services" style="padding: 90px 0; background: #FFFFFF;">
  <div class="container">
    <div class="text-center mb-5">
      <div class="agency-label-pill mx-auto">WHAT WE DO</div>
      <h2 class="agency-heading">Our Services</h2>
      <p class="agency-subtitle mx-auto">
        We provide a wide range of digital services to help your business grow, stand out, and succeed in the digital world.
      </p>
    </div>

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
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 border-0 p-4" style="background: #F8FAFC; border-radius: 18px; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 16px 36px rgba(16,185,129,0.12)'; this.style.transform='translateY(-4px)';" onmouseout="this.style.boxShadow='none'; this.style.transform='none';">
            <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-4 mb-4" style="width: 58px; height: 58px; background: #ECFDF5; color: #10B981; font-size: 22px;">
              <i class="fa-solid {{ $srv['icon'] }}"></i>
            </div>
            <h4 class="fw-bold fs-5 text-slate-900 mb-2">{{ $srv['title'] }}</h4>
            <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">{{ $srv['desc'] }}</p>
            <a href="#" class="fw-bold text-decoration-none" style="color: #10B981; font-size: 13.5px;">Learn More <i class="fa-solid fa-arrow-right ms-1"></i></a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== OUR RECENT WORK (PORTFOLIO) ===== -->
<section id="portfolio" style="padding: 90px 0; background: #F8FAFC;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <div class="agency-label-pill">OUR WORK</div>
        <h2 class="agency-heading mb-0">Our Recent Work</h2>
      </div>
      <a href="#" class="btn btn-outline-success fw-bold px-4 rounded-3" style="border-width: 1.5px;">View All Projects <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i></a>
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
          <div class="card h-100 border-0 p-4" style="background: #F8FAFC; border-radius: 18px;">
            <div class="mb-3 text-warning">
              @for($s=0; $s<($t['rating'] ?? 5); $s++)
                <i class="fa-solid fa-star"></i>
              @endfor
            </div>
            <p class="text-slate-700 fst-italic mb-4 flex-grow-1" style="font-size: 14px; line-height: 1.6;">
              {{ $t['comment'] }}
            </p>
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
  </div>
</section>
@endsection
