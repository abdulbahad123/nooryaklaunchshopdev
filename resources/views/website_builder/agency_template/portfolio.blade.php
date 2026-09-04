@extends('website_builder.agency_template.layout')

@section('title', 'Our Portfolio - ' . ($agency->site_title ?? 'DesignAGENCY'))

@section('content')
<style>
  /* PORTFOLIO HERO SECTION STYLES */
  .portfolio-hero-section {
    background: linear-gradient(180deg, #F0FDF4 0%, #FFFFFF 100%);
    padding: 70px 0 60px;
    position: relative;
    overflow: hidden;
  }
  .portfolio-badge {
    background: #D1FAE5;
    color: #059669;
    font-weight: 700;
    font-size: 14px;
    padding: 6px 18px;
    border-radius: 30px;
    display: inline-block;
    margin-bottom: 20px;
  }
  .portfolio-hero-title {
    font-size: clamp(34px, 4.5vw, 54px);
    font-weight: 800;
    line-height: 1.15;
    color: #0F172A;
    margin-bottom: 20px;
    letter-spacing: -0.5px;
  }
  .portfolio-hero-title .text-emerald {
    color: #10B981 !important;
  }
  .portfolio-hero-desc {
    font-size: 18px;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 32px;
    max-width: 520px;
  }
  .btn-start-project {
    background: #10B981;
    color: #ffffff;
    font-weight: 700;
    font-size: 16px;
    padding: 14px 32px;
    border-radius: 30px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
    text-decoration: none;
    transition: all 0.3s ease;
  }
  .btn-start-project:hover {
    background: #059669;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 14px 30px -5px rgba(16, 185, 129, 0.5);
  }

  /* HERO GRAPHIC SHOWCASE COMPOSITION */
  .portfolio-hero-graphic {
    position: relative;
    width: 100%;
    max-width: 100%;
    padding: 0;
  }
  .hero-graphic-bg {
    background: transparent;
    border-radius: 0;
    padding: 0;
    position: relative;
    width: 100%;
  }
  .hero-mockup-img {
    width: 100%;
    border-radius: 24px;
    box-shadow: none !important;
    object-fit: cover;
    display: block;
  }

  /* CATEGORY FILTER & SEARCH BAR STYLES */
  .portfolio-filter-section {
    padding: 20px 0 40px;
  }
  .portfolio-filter-pill {
    background: #F1F5F9;
    color: #475569;
    font-weight: 600;
    font-size: 14px;
    padding: 10px 22px;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-block;
  }
  .portfolio-filter-pill:hover,
  .portfolio-filter-pill.active {
    background: #10B981;
    color: #ffffff;
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.3);
  }
  .portfolio-search-input {
    border-radius: 30px;
    border: 1px solid #E2E8F0;
    padding: 10px 20px 10px 44px;
    font-size: 14px;
    width: 260px;
    transition: all 0.25s ease;
  }
  .portfolio-search-input:focus {
    border-color: #10B981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    outline: none;
  }
  .search-wrapper {
    position: relative;
  }
  .search-wrapper i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
  }

  /* 3-COLUMN PORTFOLIO CARDS GRID */
  .portfolio-card-item {
    transition: all 0.35s ease;
  }
  .portfolio-card-inner {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #F1F5F9;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-column: column;
  }
  .portfolio-card-inner:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
  }
  .portfolio-card-img-wrapper {
    position: relative;
    overflow: hidden;
    height: 250px;
    background: #F8FAFC;
  }
  .portfolio-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .portfolio-card-inner:hover .portfolio-card-img {
    transform: scale(1.05);
  }
  .portfolio-card-body {
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: #ffffff;
  }
  .portfolio-card-title {
    font-size: 18px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 4px;
    line-height: 1.3;
  }
  .portfolio-card-meta {
    font-size: 13px;
    color: #64748B;
    font-weight: 500;
  }
  .btn-arrow-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #E2E8F0;
    color: #0F172A;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    flex-shrink: 0;
    text-decoration: none;
  }
  .portfolio-card-inner:hover .btn-arrow-circle {
    background: #10B981;
    border-color: #10B981;
    color: #ffffff;
    transform: rotate(45deg);
  }

  /* BOTTOM DARK GREEN CTA BANNER */
  .portfolio-cta-banner {
    background: #064E3B;
    border-radius: 24px;
    padding: 48px 56px;
    color: #ffffff;
    position: relative;
    overflow: hidden;
  }
  .portfolio-cta-title {
    font-size: clamp(26px, 3.5vw, 36px);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 12px;
  }
  .portfolio-cta-desc {
    color: #A7F3D0;
    font-size: 16px;
    margin-bottom: 0;
  }
  .btn-cta-white {
    background: #ffffff;
    color: #064E3B;
    font-weight: 700;
    font-size: 15px;
    padding: 12px 28px;
    border-radius: 30px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  }
  .btn-cta-white:hover {
    background: #F0FDF4;
    color: #059669;
    transform: translateY(-2px);
  }
  .happy-clients-card {
    background: #ffffff;
    color: #0F172A;
    border-radius: 16px;
    padding: 10px 18px;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  }
  .avatar-group {
    display: flex;
    align-items: center;
  }
  .avatar-group img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    margin-left: -8px;
    object-fit: cover;
  }
  .avatar-group img:first-child {
    margin-left: 0;
  }
</style>

<!-- ===== PORTFOLIO HERO SECTION (Pixel-Match with Ref Image 2) ===== -->
<section class="portfolio-hero-section">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-5">
        <div class="portfolio-badge">
          <i class="fa-solid fa-sparkles me-1"></i> Our Portfolio
        </div>
        <h1 class="portfolio-hero-title">
          Our Work Speaks For <span class="text-emerald">Itself</span>
        </h1>
        <p class="portfolio-hero-desc">
          Explore our latest projects and see how we turn ideas into impactful digital experiences.
        </p>
        <div>
          @php
            $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
            $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.contact');
          @endphp
          <a href="{{ $contactUrl }}" class="btn-start-project">
            Start Your Project <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="portfolio-hero-graphic">
          <div class="hero-graphic-bg">
            <!-- HERO MOCKUP GRAPHIC SHOWCASE (Ref Image 1 & 2 Match) -->
            <img src="{{ asset('assets/website_builder/Templates/Digital_agency/portfolio_herobanner.png') }}"
                 onerror="this.src='{{ asset('assets/website_builder/Templates/Digital_agency/blog_herobanner.png') }}'; this.onerror=function(){ this.src='{{ asset('assets/website_builder/Templates/Digital_agency/hero_banner.png') }}'; };"
                 alt="Portfolio Showcase"
                 class="hero-mockup-img">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== PORTFOLIO FILTER & PROJECTS GRID ===== -->
<section class="py-5" style="background: #ffffff;">
  <div class="container">
    <!-- CATEGORY PILLS & SEARCH BAR CONTAINER -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
      <!-- CATEGORY PILLS -->
      <div class="d-flex align-items-center gap-2 flex-wrap" id="portfolioCategoryFilters">
        <button class="portfolio-filter-pill active" data-category="all">All</button>
        <button class="portfolio-filter-pill" data-category="web-design">Web Design</button>
        <button class="portfolio-filter-pill" data-category="ui-ux">UI/UX Design</button>
        <button class="portfolio-filter-pill" data-category="branding">Branding</button>
        <button class="portfolio-filter-pill" data-category="mobile-app">Mobile App</button>
        <button class="portfolio-filter-pill" data-category="e-commerce">E-commerce</button>
        <button class="portfolio-filter-pill" data-category="marketing">Marketing</button>
      </div>

      <!-- SEARCH INPUT FIELD -->
      <div class="search-wrapper">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="portfolioSearchInput" class="portfolio-search-input" placeholder="Search projects...">
      </div>
    </div>

    @php
      $projects = $agency->portfolio_data ?? [
        ['title' => 'Fintech Website Redesign', 'category' => 'Web Design • UI/UX',          'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'E-commerce Website',       'category' => 'Web Design • E-commerce',      'image' => 'assets/website_builder/wb_card_ecommerce.png'],
        ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Brand Identity Design',    'category' => 'Branding • Graphic Design',   'image' => 'assets/website_builder/wb_card_portfolio.png'],
        ['title' => 'Travel Website',           'category' => 'Web Design • UI/UX',          'image' => 'assets/website_builder/wb_card_events.png'],
        ['title' => 'Fitness App Design',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design • Web App',      'image' => 'assets/website_builder/wb_card_restaurant.png'],
        ['title' => 'Digital Marketing Campaign','category' => 'Marketing • Social Media',   'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'Restaurant Website',       'category' => 'Web Design • E-commerce',      'image' => 'assets/website_builder/wb_card_ecommerce.png'],
      ];
    @endphp

    <!-- 3-COLUMN PORTFOLIO CARDS GRID -->
    <div class="row g-4" id="portfolioGrid">
      @foreach($projects as $pi => $project)
        @php
          $catLower = strtolower($project['category'] ?? '');
          $dataCat = 'web-design';
          if(str_contains($catLower, 'ui') || str_contains($catLower, 'ux')) $dataCat = 'ui-ux';
          elseif(str_contains($catLower, 'brand')) $dataCat = 'branding';
          elseif(str_contains($catLower, 'mobile') || str_contains($catLower, 'app')) $dataCat = 'mobile-app';
          elseif(str_contains($catLower, 'e-commerce') || str_contains($catLower, 'shop')) $dataCat = 'e-commerce';
          elseif(str_contains($catLower, 'market')) $dataCat = 'marketing';
        @endphp
        <div class="col-lg-4 col-md-6 portfolio-card-item" data-category="{{ $dataCat }}" data-title="{{ strtolower($project['title'] ?? '') }} {{ $catLower }}">
          <div class="portfolio-card-inner">
            <div class="portfolio-card-img-wrapper">
              <img src="{{ str_starts_with($project['image'] ?? '', 'http') ? $project['image'] : asset($project['image'] ?? 'assets/website_builder/wb_card_agency.png') }}"
                   onerror="this.src='{{ asset('assets/website_builder/wb_card_agency.png') }}';"
                   alt="{{ $project['title'] ?? 'Project' }}"
                   class="portfolio-card-img">
            </div>
            <div class="portfolio-card-body">
              <div>
                <h5 class="portfolio-card-title">{{ $project['title'] ?? 'Project Title' }}</h5>
                <div class="portfolio-card-meta">{{ $project['category'] ?? 'Web Design' }}</div>
              </div>
              <a href="{{ $contactUrl }}" class="btn-arrow-circle" title="View Project Details">
                <i class="fa-solid fa-arrow-up-right"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== BOTTOM DARK GREEN CTA BANNER (Pixel-Match Ref Image 2) ===== -->
<div class="container my-5 pb-4">
  <div class="portfolio-cta-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-4 position-relative" style="z-index: 2;">
      <div>
        <h2 class="portfolio-cta-title">
          Have a Project in Mind?<br>Let's Create Something Amazing Together!
        </h2>
        <p class="portfolio-cta-desc">
          We're ready to turn your ideas into reality.
        </p>
      </div>

      <div class="d-flex align-items-center gap-4 flex-wrap">
        <a href="{{ $contactUrl }}" class="btn-cta-white">
          Get In Touch <i class="fa-solid fa-arrow-right"></i>
        </a>

        <!-- HAPPY CLIENTS BADGE WITH TEAM AVATARS -->
        <div class="happy-clients-card">
          <div class="avatar-group">
            <img src="{{ asset('assets/website_builder/team_1.jpg') }}" onerror="this.src='https://i.pravatar.cc/100?img=11';" alt="Client">
            <img src="{{ asset('assets/website_builder/team_2.jpg') }}" onerror="this.src='https://i.pravatar.cc/100?img=12';" alt="Client">
            <img src="{{ asset('assets/website_builder/team_3.jpg') }}" onerror="this.src='https://i.pravatar.cc/100?img=13';" alt="Client">
          </div>
          <div>
            <div class="fw-extrabold fs-6 text-dark" style="line-height: 1.1;">50+</div>
            <div class="text-muted small fw-semibold" style="font-size: 11px;">Happy Clients</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- LIVE JAVASCRIPT CATEGORY & SEARCH FILTERING -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const filterPills = document.querySelectorAll('#portfolioCategoryFilters .portfolio-filter-pill');
    const searchInput = document.getElementById('portfolioSearchInput');
    const cardItems = document.querySelectorAll('#portfolioGrid .portfolio-card-item');

    let currentCategory = 'all';
    let currentQuery = '';

    function filterProjects() {
      cardItems.forEach(item => {
        const itemCat = item.getAttribute('data-category');
        const itemTitle = item.getAttribute('data-title');

        const matchesCat = (currentCategory === 'all' || itemCat === currentCategory || itemTitle.includes(currentCategory.replace('-', ' ')));
        const matchesSearch = (currentQuery === '' || itemTitle.includes(currentQuery));

        if (matchesCat && matchesSearch) {
          item.style.display = 'block';
          item.style.opacity = '1';
        } else {
          item.style.display = 'none';
          item.style.opacity = '0';
        }
      });
    }

    filterPills.forEach(pill => {
      pill.addEventListener('click', function () {
        filterPills.forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        currentCategory = this.getAttribute('data-category');
        filterProjects();
      });
    });

    if (searchInput) {
      searchInput.addEventListener('input', function () {
        currentQuery = this.value.toLowerCase().trim();
        filterProjects();
      });
    }
  });
</script>
@endsection
