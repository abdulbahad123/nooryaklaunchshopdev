@extends('website_builder.interior_template.layout')

@section('title', 'InterioCRAFT - Bespoke Architecture & Interior Design Studio')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.portfolio');
@endphp

<style>
  @media (max-width: 767.98px) {
    .ic-mobile-slider {
      display: flex !important;
      overflow-x: auto !important;
      scroll-snap-type: x mandatory !important;
      gap: 16px !important;
      padding-bottom: 12px !important;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
    }
    .ic-mobile-slider::-webkit-scrollbar {
      display: none;
    }
    .ic-mobile-slider > [class*="col-"] {
      flex: 0 0 85% !important;
      max-width: 85% !important;
      scroll-snap-align: center !important;
    }
  }
</style>

<!-- ===== HERO SECTION ===== -->
<section class="ic-hero position-relative overflow-hidden" style="background-color: #F7F7F5; padding: 75px 0 85px;">
  @php
    $defaultHomeHero = asset('assets/website_builder/Templates/Interior_agency/homepage_hero.png');
    $heroImg = $interior->hero_image ?? '';
    $isOldAgencyOrUnsplash = empty($heroImg) 
      || str_contains($heroImg, 'unsplash.com') 
      || str_contains($heroImg, 'agency_template') 
      || str_contains($heroImg, 'herobanner_right')
      || str_contains($heroImg, 'photo-1618221195710');
    $homeHeroSrc = !$isOldAgencyOrUnsplash ? (str_starts_with($heroImg, 'http') ? $heroImg : asset(ltrim($heroImg, '/'))) : $defaultHomeHero;
  @endphp

  <!-- Right Side Full Height Cover Background Image -->
  <div class="position-absolute top-0 end-0 bottom-0 d-none d-lg-block" style="width: 55%; z-index: 1;">
    <img src="{{ $homeHeroSrc }}"
         onerror="this.src='{{ $defaultHomeHero }}';"
         alt="{{ $interior->site_title ?? 'InterioCRAFT Showcase' }}" style="width: 100%; height: 100%; object-fit: cover; object-position: right center; display: block;">
    <div style="position: absolute; top:0; left:0; bottom:0; width: 35%; background: linear-gradient(to right, #F7F7F5 0%, rgba(247,247,245,0) 100%);"></div>
  </div>

  <div class="ic-container position-relative" style="z-index: 2;">
    <div class="ic-hero-grid">
      <div>
        <span class="ic-pill-badge">
          {{ $interior->hero_badge ?? 'BESPOKE INTERIOR DESIGN & ARCHITECTURE' }}
        </span>
        <h1 class="ic-heading ic-hero-title">
          Spaces We Design,<br>Stories We <span class="ic-cursive" style="font-size: 64px; color: var(--ic-primary);">Create</span>
        </h1>
        <p class="ic-hero-subtitle">
          {{ $interior->hero_subtitle ?? 'We specialize in luxury residential, commercial, and architectural spatial planning that reflects your unique lifestyle and functional elegance.' }}
        </p>

        <div class="ic-hero-actions">
          <a href="{{ $interior->primary_btn_url ?? $contactUrl }}" class="ic-btn ic-btn-dark">
            {{ $interior->primary_btn_text ?? 'View Our Projects' }} <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="{{ $interior->secondary_btn_url ?? '#video' }}" class="ic-btn-video">
            <div class="ic-play-icon"><i class="fa-solid fa-play ms-1"></i></div>
            <div>
              <div>Watch Our Story</div>
              <div style="font-size: 11.5px; font-weight: 500; color: var(--ic-text-muted);">2 min video</div>
            </div>
          </a>
        </div>

        <!-- Single Row 4-Stats Floating Box (Non-collapsing) -->
        <div class="ic-hero-stats" style="background: #ffffff; border-radius: 20px; padding: 18px 24px; border: 1px solid #EAE6DF; box-shadow: 0 10px 30px rgba(0,0,0,0.06); width: 100%; max-width: 860px;">
          @php
            $stats = $interior->stats_data ?? [
              ['number' => '8+',   'label' => 'Years of Experience', 'icon' => 'fa-trophy'],
              ['number' => '250+', 'label' => 'Projects Completed',  'icon' => 'fa-house'],
              ['number' => '98%',  'label' => 'Client Satisfaction', 'icon' => 'fa-star'],
              ['number' => '24/7', 'label' => 'Design Support',      'icon' => 'fa-headset'],
            ];
          @endphp

          <div class="d-flex align-items-center justify-content-between w-100 flex-wrap flex-md-nowrap gap-3">
            @foreach($stats as $index => $st)
              <div class="d-flex align-items-center gap-3 flex-grow-1" style="min-width: 150px;">
                <div class="ic-stat-circle" style="width: 44px; height: 44px; border-radius: 50%; background: #F2F5F3; color: var(--ic-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                  <i class="fa-solid {{ $st['icon'] ?? 'fa-house' }}"></i>
                </div>
                <div>
                  <div style="font-size: 18px; font-weight: 800; color: #111; line-height: 1.1;">{{ $st['number'] ?? $st['num'] ?? '' }}</div>
                  <div style="font-size: 11.5px; color: #666; font-weight: 500; white-space: nowrap;">{{ $st['label'] ?? '' }}</div>
                </div>
              </div>
              @if($index < count($stats) - 1)
                <div class="d-none d-md-block" style="width: 1px; height: 32px; background: #E2E8F0; flex-shrink: 0;"></div>
              @endif
            @endforeach
          </div>
        </div>
      </div>

      <!-- Mobile Image View -->
      <div class="d-block d-lg-none mt-4">
        <div class="rounded-4 overflow-hidden shadow-lg border" style="height: 380px;">
          <img src="{{ $homeHeroSrc }}" onerror="this.src='{{ $defaultHomeHero }}';" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== SERVICES SECTION (WHAT WE DO) ===== -->
<section id="services" class="py-5" style="background: #ffffff;">
  <div class="ic-container py-4">
    <div class="text-center mb-5 max-w-700 mx-auto">
      <span class="ic-pill-badge">—— WHAT WE DO ——</span>
      <h2 class="ic-heading fs-1 mt-2 mb-3">Our Interior Design Services</h2>
      <p class="text-muted fs-6">We provide a complete range of interior design solutions to transform your space into something extraordinary.</p>
    </div>

    @php
      $services = $interior->services_data ?? [
        [
          'title' => 'Residential Design',
          'desc'  => 'Create cozy and stylish homes that reflect your personality.',
          'image' => asset('assets/website_builder/Templates/Interior_agency/service_residential.png'),
          'icon'  => 'fa-couch'
        ],
        [
          'title' => 'Commercial Design',
          'desc'  => 'Functional and impressive workspaces for modern businesses.',
          'image' => asset('assets/website_builder/Templates/Interior_agency/service_commercial.png'),
          'icon'  => 'fa-building'
        ],
        [
          'title' => 'Space Planning',
          'desc'  => 'Smart layouts to maximize your space and comfort.',
          'image' => asset('assets/website_builder/Templates/Interior_agency/service_planning.png'),
          'icon'  => 'fa-leaf'
        ],
        [
          'title' => 'Interior Styling',
          'desc'  => 'Thoughtful details that bring your space to life.',
          'image' => asset('assets/website_builder/Templates/Interior_agency/service_styling.png'),
          'icon'  => 'fa-pen-ruler'
        ],
      ];
    @endphp

    <div class="row g-4 ic-mobile-slider">
      @foreach($services as $srv)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="card h-100 p-3 rounded-4 bg-white" style="border: 1px solid #E2E8F0 !important; transition: all 0.3s ease;">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div style="width: 44px; height: 44px; border-radius: 12px; background: #F2F5F3; color: var(--ic-primary); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid {{ $srv['icon'] ?? 'fa-couch' }}"></i>
              </div>
            </div>
            <h3 class="fw-bold fs-5 mb-2 text-dark">{{ $srv['title'] ?? '' }}</h3>
            <p class="text-muted small mb-3 flex-grow-1" style="font-size: 13px; line-height: 1.5;">{{ $srv['desc'] ?? '' }}</p>

            <div class="rounded-3 overflow-hidden position-relative" style="height: 180px;">
              <img src="{{ str_starts_with($srv['image'] ?? '', 'http') ? ($srv['image'] ?? '') : asset(ltrim($srv['image'] ?? '', '/')) }}" 
                   alt="{{ $srv['title'] ?? '' }}" 
                   style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== FEATURED PROJECTS SECTION ===== -->
<section id="portfolio" class="py-5" style="background: #F7F7F5;">
  <div class="ic-container py-4">
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-5">
      <div>
        <span class="ic-pill-badge">—— OUR WORK ——</span>
        <h2 class="ic-heading fs-1 mt-2 mb-2">Featured Projects</h2>
        <p class="text-muted fs-6 mb-0">Explore some of our latest interior design projects that bring ideas to life with style and functionality.</p>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="{{ $portfolioUrl }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold fs-6">
          View All Projects <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    @php
      $portfolio = $interior->portfolio_data ?? [
        [
          'title'    => 'Modern Living Room',
          'category' => 'Residential',
          'desc'     => 'A perfect blend of comfort and style.',
          'image'    => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-house'
        ],
        [
          'title'    => 'Luxury Bedroom',
          'category' => 'Residential',
          'desc'     => 'A peaceful retreat for your everyday life.',
          'image'    => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-bed'
        ],
        [
          'title'    => 'Office Workspace',
          'category' => 'Commercial',
          'desc'     => 'Productive spaces for growing businesses.',
          'image'    => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-building'
        ],
        [
          'title'    => 'Modern Kitchen',
          'category' => 'Residential',
          'desc'     => 'Functional design for modern homes.',
          'image'    => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-utensils'
        ],
      ];
    @endphp

    <div class="row g-4">
      @foreach(array_slice($portfolio, 0, 4) as $proj)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="position-relative" style="height: 220px;">
              <img src="{{ str_starts_with($proj['image'] ?? '', 'http') ? ($proj['image'] ?? '') : asset(ltrim($proj['image'] ?? '', '/')) }}" 
                   alt="{{ $proj['title'] ?? '' }}" 
                   style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
              <div>
                <h3 class="fw-bold fs-6 mb-1 text-dark">{{ $proj['title'] ?? '' }}</h3>
                <span class="text-muted small" style="font-size: 12px;">{{ $proj['category'] ?? 'Residential' }}</span>
              </div>
              <a href="{{ $portfolioUrl }}" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center border-0" style="width: 32px; height: 32px; background: #F2F5F3; color: #111;">
                <i class="fa-solid fa-arrow-right" style="font-size: 12px;"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== BLOG & INSIGHTS SECTION ===== -->
<section id="blog" class="py-5" style="background: #ffffff;">
  <div class="ic-container py-4">
    <div class="d-flex align-items-end justify-content-between mb-5">
      <div>
        <span class="ic-pill-badge">—— OUR BLOG & INSIGHTS ——</span>
        <h2 class="ic-heading fs-1 mt-2 mb-2">Latest Articles & Design Ideas</h2>
        <p class="text-muted fs-6 mb-0">Get inspired with expert tips, trends, and ideas to create beautiful spaces.</p>
      </div>
      <div class="d-none d-md-flex gap-2">
        <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px;"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $blogs = [
        [
          'id'     => 1,
          'badge'  => 'Interior Tips',
          'date'   => 'Sep 12, 2024',
          'author' => 'Emma Carter',
          'title'  => '10 Simple Ways to Make Your Home Look Expensive',
          'desc'   => 'Transform your space with these easy and affordable interior design tips.',
          'image'  => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=600&auto=format&fit=crop'
        ],
        [
          'id'     => 2,
          'badge'  => 'Design Trends',
          'date'   => 'Aug 28, 2024',
          'author' => 'Daniel Lee',
          'title'  => 'Top Interior Design Trends for 2025',
          'desc'   => 'Explore the latest design trends that are shaping modern interiors this year.',
          'image'  => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=600&auto=format&fit=crop'
        ],
        [
          'id'     => 3,
          'badge'  => 'Space Planning',
          'date'   => 'Aug 15, 2024',
          'author' => 'Sofia Martinez',
          'title'  => 'How to Maximize Small Spaces with Smart Design',
          'desc'   => 'Practical ideas to make the most of your space without compromising style.',
          'image'  => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?q=80&w=600&auto=format&fit=crop'
        ],
      ];
    @endphp

    <div class="row g-4">
      @foreach($blogs as $bi => $b)
        @php
          $blogId = $b['id'] ?? ($bi + 1);
          $blogDetailUrl = $subdomainParam 
            ? route('website-builder.subdomain.blog', ['subdomain' => $subdomainParam, 'id' => $blogId]) 
            : route('website-builder.templates.interior.blog', ['id' => $blogId]);
        @endphp
        <div class="col-12 col-md-4">
          <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="position-relative" style="height: 220px;">
              <a href="{{ $blogDetailUrl }}">
                <img src="{{ $b['image'] }}" alt="{{ $b['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
              </a>
              <span class="position-absolute top-0 start-0 m-3 badge bg-dark text-white rounded-pill px-3 py-2 fw-normal" style="font-size: 11px;">
                {{ $b['badge'] }}
              </span>
            </div>
            <div class="card-body p-4 d-flex flex-column">
              <div class="d-flex align-items-center gap-3 text-muted mb-2" style="font-size: 12px;">
                <span><i class="fa-regular fa-calendar me-1"></i> {{ $b['date'] }}</span>
                <span><i class="fa-regular fa-user me-1"></i> by {{ $b['author'] }}</span>
              </div>
              <h3 class="fw-bold fs-5 mb-2">
                <a href="{{ $blogDetailUrl }}" class="text-dark text-decoration-none">{{ $b['title'] }}</a>
              </h3>
              <p class="text-muted small mb-3 flex-grow-1" style="font-size: 13px; line-height: 1.5;">{{ $b['desc'] }}</p>
              <a href="{{ $blogDetailUrl }}" class="fw-bold text-dark text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 13.5px;">
                Read Full Article <i class="fa-solid fa-arrow-right fs-6" style="color: var(--ic-primary);"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS SECTION ===== -->
<section id="testimonials" class="py-5" style="background: #F7F7F5;">
  <div class="ic-container py-4 text-center">
    <span class="ic-pill-badge">—— TESTIMONIALS ——</span>
    <h2 class="ic-heading fs-1 mt-2 mb-2">What Our Clients Say</h2>
    <p class="text-muted fs-6 mb-5">We're proud to have helped so many homeowners and businesses create beautiful spaces.</p>

    @php
      $testimonials = $interior->testimonials_data ?? [
        [
          'name'    => 'Priya Sharma',
          'role'    => 'Homeowner',
          'comment' => 'The team transformed our home into a beautiful and functional space. Highly recommend!',
          'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'
        ],
        [
          'name'    => 'Rahul Mehta',
          'role'    => 'Business Owner',
          'comment' => 'Professional, creative, and easy to work with. They understood our vision perfectly.',
          'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'
        ],
        [
          'name'    => 'Anjali Verma',
          'role'    => 'Startup Founder',
          'comment' => 'Amazing attention to detail and a fantastic design sense. Our office looks incredible!',
          'avatar'  => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop'
        ],
        [
          'name'    => 'David Miller',
          'role'    => 'Estate Developer',
          'comment' => 'Exceptional interior craftsmanship and space planning. They delivered our villa renovation ahead of schedule.',
          'avatar'  => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200&auto=format&fit=crop'
        ],
        [
          'name'    => 'Sophia Chen',
          'role'    => 'Boutique Hotel Director',
          'comment' => 'Their design aesthetic elevated our luxury suites beyond expectations. A true pleasure to work with.',
          'avatar'  => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200&auto=format&fit=crop'
        ],
      ];
    @endphp

    <div class="row g-4 text-start ic-mobile-slider">
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
            <p class="text-muted fst-italic mb-4 flex-grow-1" style="font-size: 14px; line-height: 1.6;">"{{ $t['comment'] ?? $t['quote'] ?? '' }}"</p>
            <div class="d-flex align-items-center gap-3">
              <img src="{{ str_starts_with($t['avatar'] ?? '', 'http') ? ($t['avatar'] ?? '') : asset(ltrim($t['avatar'] ?? '', '/')) }}" 
                   alt="{{ $t['name'] ?? '' }}" 
                   class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover;">
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

<!-- ===== CALL TO ACTION BANNER ===== -->
<div class="ic-container my-4">
  <div class="ic-cta-box rounded-4 p-4 p-md-5" style="background: var(--ic-secondary, #1F3627); color: #ffffff;">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <div class="ic-cta-eyebrow text-uppercase fw-bold mb-2" style="letter-spacing: 1.5px; font-size: 12px; opacity: 0.8;">LET'S DESIGN TOGETHER</div>
        <h2 class="ic-cta-title fw-bold text-white mb-2" style="font-size: clamp(24px, 3vw, 36px);">{{ $interior->contact_title ?? 'Ready to Transform Your Space?' }}</h2>
        <p class="ic-cta-sub mb-4 text-white-50 small" style="max-width: 520px;">
          {{ $interior->contact_subtitle ?? "Schedule a complimentary interior design consultation with our lead architects today." }}
        </p>

        <a href="{{ $contactUrl }}" class="ic-btn ic-btn-light fs-6">
          Get Started Now <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>

      <!-- Right Armchair Image + Cursive Overlay -->
      <div class="col-lg-5 d-none d-lg-block position-relative text-end">
        @php
          $defaultCtaImg = asset('assets/website_builder/Templates/Interior_agency/cta_footer.png');
          $ctaImgSrc = !empty($interior->contact_image) && !str_contains($interior->contact_image, 'contact_footer') ? (str_starts_with($interior->contact_image, 'http') ? $interior->contact_image : asset(ltrim($interior->contact_image, '/'))) : $defaultCtaImg;
        @endphp
        <div class="d-inline-block position-relative rounded-4 overflow-hidden border border-white border-opacity-25 shadow-lg" style="max-height: 240px; width: 85%;">
          <img src="{{ $ctaImgSrc }}"
               onerror="this.src='{{ $defaultCtaImg }}';"
               alt="Luxury Interior Chair" style="width: 100%; height: 240px; object-fit: cover;">
          <div class="position-absolute bottom-0 start-0 m-3 text-start">
            <span class="ic-cursive" style="font-size: 26px; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.8);">
              Spaces That<br>Feel Like Home
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
