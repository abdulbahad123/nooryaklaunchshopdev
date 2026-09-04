@extends('website_builder.agency_template.layout')

@section('title', 'DesignAGENCY - Creative Digital Solutions Agency')

@section('content')
<!-- ===== HERO SECTION (Ref Image 1 Match) ===== -->
<section style="background: linear-gradient(180deg, #F0FDF4 0%, #FFFFFF 100%); padding: 75px 0 60px; position: relative;">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="agency-label-pill" style="background: #ECFDF5; color: #059669; border-radius: 30px; padding: 6px 18px;">
          <span style="font-size: 14px; font-weight: bold; margin-right: 4px;">•</span> {{ $agency->hero_badge ?? 'Creative Digital Solutions' }}
        </div>
        <h1 class="agency-heading my-3" style="font-size: clamp(36px, 5.2vw, 56px); font-weight: 800; line-height: 1.15; color: #0F172A; letter-spacing: -1px;">
          {!! nl2br(e($agency->hero_title ?? "Increase Your\nCustomers Loyalty\nand Satisfaction")) !!}
        </h1>
        <p class="agency-subtitle mb-4 text-secondary" style="font-size: 16px; line-height: 1.65; max-width: 540px;">
          {{ $agency->hero_subtitle ?? 'We help businesses like yours earn more customers, stand out from competitors, and grow your revenue.' }}
        </p>
        <div class="d-flex align-items-center gap-3 flex-wrap mb-5">
          <a href="{{ $agency->primary_btn_url ?? '#contact' }}" class="btn-agency-register rounded-pill" style="padding: 14px 34px; font-size: 15px; font-weight: 700; background: #10B981; color: #fff;">
            {{ $agency->primary_btn_text ?? 'Get Started' }} <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
          <a href="{{ $agency->secondary_btn_url ?? '#portfolio' }}" class="btn-agency-login rounded-pill" style="padding: 14px 28px; font-size: 15px; font-weight: 700; border-color: #10B981; color: #10B981;">
            {{ $agency->secondary_btn_text ?? 'View Our Work' }} <i class="fa-regular fa-circle-play ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Right Hero Graphic / Hero Photo -->
      <div class="col-lg-6">
        <div class="position-relative text-center">
          <img src="{{ asset($agency->hero_image ?? 'assets/website_builder/Templates/Digital_agency/hero_banner.png') }}" 
               onerror="this.src='{{ asset('assets/website_builder/Templates/Digital_agency/hero_banner.png') }}';" 
               alt="{{ $agency->hero_badge ?? 'Creative Digital Solutions Agency' }}" 
               style="max-width: 100%; height: auto; max-height: 520px; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.06));">
        </div>
      </div>
    </div>

    <!-- Floating 4 Stats Bar Box (Ref Image 1 Match) -->
    <div class="card border-0 shadow-lg " style="border-radius: 24px; padding: 32px 24px; background: #ffffff;">
      <div class="row g-4 text-center">
        @php
          $statsData = $agency->stats_data ?? [
            ['number' => '8+',   'label' => 'Years of Experience', 'icon' => 'fa-building-columns'],
            ['number' => '120+', 'label' => 'Projects Completed',   'icon' => 'fa-envelope'],
            ['number' => '98%',  'label' => 'Client Satisfaction',  'icon' => 'fa-circle-check'],
            ['number' => '24/7', 'label' => 'Support Available',   'icon' => 'fa-headset'],
          ];
        @endphp

        @foreach($statsData as $st)
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center justify-content-center gap-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ECFDF5; color: #10B981; font-size: 22px;">
                <i class="fa-solid {{ $st['icon'] ?? 'fa-chart-line' }}"></i>
              </div>
              <div class="text-start">
                <h3 class="fw-extrabold mb-0 text-slate-900" style="font-size: 26px;">{{ $st['number'] ?? ($st['num'] ?? '') }}</h3>
                <div class="small text-muted fw-semibold" style="font-size: 13px;">{{ $st['label'] ?? '' }}</div>
              </div>
            </div>
          </div>
        @endforeach
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

    <!-- 6 Cards Slide Track with Navigation Arrows -->
    <div class="position-relative">
      <!-- Left Arrow -->
      <button type="button" class="btn btn-light rounded-circle shadow border position-absolute top-50 start-0 translate-middle-y d-none d-md-flex align-items-center justify-content-center" style="width: 46px; height: 46px; left: -22px; z-index: 10;" onclick="scrollServicesTrack(-320)">
        <i class="fa-solid fa-chevron-left text-success fs-5"></i>
      </button>

      <!-- Right Arrow -->
      <button type="button" class="btn btn-light rounded-circle shadow border position-absolute top-50 end-0 translate-middle-y d-none d-md-flex align-items-center justify-content-center" style="width: 46px; height: 46px; right: -22px; z-index: 10;" onclick="scrollServicesTrack(320)">
        <i class="fa-solid fa-chevron-right text-success fs-5"></i>
      </button>

      <div class="d-flex gap-4 overflow-auto py-3 px-2 service-scroll-track" id="servicesScrollTrack" style="scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;">
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
          <div class="service-slide-card flex-shrink-0" style="width: 280px;">
            <div class="card h-100 border p-4 text-center" style="background: #FFFFFF; border-color: #F1F5F9; border-radius: 20px; box-shadow: 0 4px 18px rgba(0,0,0,0.03); transition: all 0.3s;" onmouseover="this.style.boxShadow='0 16px 36px rgba(16,185,129,0.14)'; this.style.transform='translateY(-6px)';" onmouseout="this.style.boxShadow='0 4px 18px rgba(0,0,0,0.03)'; this.style.transform='none';">
              <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 58px; height: 58px; background: #ECFDF5; color: #10B981; font-size: 22px;">
                <i class="fa-solid {{ $srv['icon'] ?? 'fa-laptop-code' }}"></i>
              </div>
              <h5 class="fw-bold fs-6 text-slate-900 mb-2">{{ $srv['title'] ?? '' }}</h5>
              <p class="text-muted small mb-0 flex-grow-1" style="font-size: 12.5px; line-height: 1.55;">{{ $srv['desc'] ?? '' }}</p>
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
    <div class="d-flex align-items-center gap-2 flex-wrap mb-4" id="portfolioFilterGroup">
      <button type="button" class="btn filter-btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background: #10B981; font-size: 13.5px;" onclick="filterPortfolio('all', this)">All</button>
      <button type="button" class="btn filter-btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;" onclick="filterPortfolio('web design', this)">Web Design</button>
      <button type="button" class="btn filter-btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;" onclick="filterPortfolio('ui/ux design', this)">UI/UX Design</button>
      <button type="button" class="btn filter-btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;" onclick="filterPortfolio('branding', this)">Branding</button>
      <button type="button" class="btn filter-btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;" onclick="filterPortfolio('app design', this)">App Design</button>
      <button type="button" class="btn filter-btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill" style="font-size: 13.5px;" onclick="filterPortfolio('marketing', this)">Marketing</button>
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

    <div class="row g-4" id="portfolioContainer">
      @foreach($portfolio as $port)
        <div class="col-lg-3 col-md-6 portfolio-item" data-category="{{ strtolower($port['category'] ?? '') }}">
          <div class="card border-0 h-100 overflow-hidden shadow-sm" style="border-radius: 16px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-6px)';" onmouseout="this.style.transform='none';">
            <div style="height: 195px; overflow: hidden; background: #0F172A;" class="position-relative">
              <img src="{{ str_starts_with($port['image'] ?? '', 'http') ? $port['image'] : asset($port['image'] ?? 'assets/website_builder/wb_card_agency.png') }}" 
                   onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=600&auto=format&fit=crop';"
                   alt="{{ $port['title'] ?? '' }}" 
                   style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                   onmouseover="this.style.transform='scale(1.06)';"
                   onmouseout="this.style.transform='scale(1)';"
                   loading="lazy">
            </div>
            <div class="p-3 bg-white">
              <h5 class="fw-bold fs-6 mb-1 text-slate-900">{{ $port['title'] ?? '' }}</h5>
              <div class="text-muted" style="font-size: 12px; font-weight: 600;">{{ $port['category'] ?? '' }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

  </div>
</section>

<!-- ===== LATEST NEWS & BLOGS SLIDER SECTION ===== -->
<section id="blogs" style="padding: 90px 0; background: #FFFFFF;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <div>
        <div class="agency-label-pill">OUR BLOG & INSIGHTS</div>
        <h2 class="agency-heading mb-0">Latest Articles & Insights</h2>
      </div>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-light rounded-circle shadow border p-0 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;" onclick="scrollBlogsTrack(-350)">
          <i class="fa-solid fa-chevron-left text-success"></i>
        </button>
        <button type="button" class="btn btn-light rounded-circle shadow border p-0 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;" onclick="scrollBlogsTrack(350)">
          <i class="fa-solid fa-chevron-right text-success"></i>
        </button>
      </div>
    </div>

    @php
      $blogs = $agency->blogs_data ?? [
        [
          'id'          => 1,
          'title'       => '10 Modern UI/UX Trends Shaping Digital Products in 2026',
          'category'    => 'Design & Tech',
          'author'      => 'Michael Roberts',
          'date'        => 'Sep 04, 2026',
          'image'       => 'assets/website_builder/wb_card_agency.png',
          'excerpt'     => 'Discover the top design trends driving higher customer engagement and conversions for digital platforms.',
        ],
        [
          'id'          => 2,
          'title'       => 'How Strategic Branding Drives Revenue Growth for Startups',
          'category'    => 'Branding',
          'author'      => 'Sarah Johnson',
          'date'        => 'Aug 28, 2026',
          'image'       => 'assets/website_builder/wb_card_portfolio.png',
          'excerpt'     => 'Learn how a cohesive brand identity instills trust and establishes a strong competitive advantage.',
        ],
        [
          'id'          => 3,
          'title'       => 'Maximizing Search Visibility with Data-Driven SEO Tactics',
          'category'    => 'SEO & Marketing',
          'author'      => 'Jessica Brown',
          'date'        => 'Aug 15, 2026',
          'image'       => 'assets/website_builder/wb_card_startup.png',
          'excerpt'     => 'A complete guide to optimizing site speed, technical SEO, and organic ranking strategies.',
        ],
      ];

      $subdomainSlug = $subdomain ?? (isset($customer) && !empty($customer->subdomain) ? $customer->subdomain : null);
    @endphp

    <div class="d-flex gap-4 overflow-auto py-3 px-1 blog-scroll-track" id="blogsScrollTrack" style="scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;">
      @foreach($blogs as $bi => $b)
        @php
          $blogId = $b['id'] ?? ($bi + 1);
          if ($subdomainSlug) {
            $blogDetailUrl = route('website-builder.subdomain.blog', ['subdomain' => $subdomainSlug, 'id' => $blogId]);
          } else {
            $blogDetailUrl = url('/website-builder/templates/digital_agency/blog/' . $blogId);
          }
        @endphp
        <div class="blog-slide-card flex-shrink-0" style="width: 350px;">
          <div class="card border-0 h-100 shadow-sm overflow-hidden" style="border-radius: 20px; background: #FFFFFF; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-6px)';" onmouseout="this.style.transform='none';">
            <div style="height: 200px; overflow: hidden; background: #0F172A;" class="position-relative">
              <img src="{{ str_starts_with($b['image'] ?? '', 'http') ? $b['image'] : asset($b['image'] ?? 'assets/website_builder/wb_card_agency.png') }}"
                   onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=600&auto=format&fit=crop';"
                   alt="{{ $b['title'] ?? '' }}"
                   style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
              <span class="position-absolute top-0 start-0 m-3 badge text-white px-3 py-2 fw-bold" style="background: #10B981; border-radius: 30px; font-size: 11.5px;">
                {{ $b['category'] ?? 'Article' }}
              </span>
            </div>
            <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
              <div>
                <div class="d-flex align-items-center gap-3 text-muted small mb-2" style="font-size: 12.5px;">
                  <span><i class="fa-regular fa-calendar me-1 text-success"></i> {{ $b['date'] ?? date('M d, Y') }}</span>
                  <span><i class="fa-regular fa-user me-1 text-success"></i> {{ $b['author'] ?? 'Admin' }}</span>
                </div>
                <h5 class="fw-bold fs-6 text-slate-900 mb-2" style="line-height: 1.4;">{{ $b['title'] ?? '' }}</h5>
                <p class="text-muted small mb-4" style="font-size: 13px; line-height: 1.6;">{{ $b['excerpt'] ?? '' }}</p>
              </div>
              <a href="{{ $blogDetailUrl }}" class="fw-bold text-decoration-none small mt-auto" style="color: #10B981;">
                Read Full Article <i class="fa-solid fa-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS SECTION ===== -->
<section id="testimonials" style="padding: 90px 0; background: #FFFFFF;">
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

<style>
  .service-scroll-track::-webkit-scrollbar,
  .blog-scroll-track::-webkit-scrollbar {
    display: none;
  }
  .service-scroll-track,
  .blog-scroll-track {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
</style>

<script>
  function scrollServicesTrack(amount) {
    var track = document.getElementById('servicesScrollTrack');
    if (track) {
      track.scrollBy({ left: amount, behavior: 'smooth' });
    }
  }

  function scrollBlogsTrack(amount) {
    var track = document.getElementById('blogsScrollTrack');
    if (track) {
      track.scrollBy({ left: amount, behavior: 'smooth' });
    }
  }

  function filterPortfolio(cat, btn) {
    var buttons = document.querySelectorAll('#portfolioFilterGroup .filter-btn');
    buttons.forEach(function(b) {
      b.className = 'btn filter-btn btn-light fw-semibold text-secondary px-4 py-2 rounded-pill';
      b.style.background = '';
    });
    btn.className = 'btn filter-btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm';
    btn.style.background = '#10B981';

    var items = document.querySelectorAll('#portfolioContainer .portfolio-item');
    cat = cat.toLowerCase();
    items.forEach(function(item) {
      var itemCat = item.getAttribute('data-category');
      if (cat === 'all' || itemCat.includes(cat) || cat.includes(itemCat)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  }
</script>
@endsection

