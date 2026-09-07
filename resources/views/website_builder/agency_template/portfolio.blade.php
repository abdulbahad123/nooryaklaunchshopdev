@extends('website_builder.agency_template.layout')

@section('title', 'Our Portfolio - ' . ($agency->site_title ?? 'DesignAGENCY'))

@section('content')
<style>
  /* ===== PORTFOLIO HERO ===== */
  .portfolio-hero-section {
    background: linear-gradient(180deg, #F0FDF4 0%, #FFFFFF 100%);
    padding: 70px 0 0;
    position: relative;
    overflow: hidden;
  }
  .portfolio-badge {
    background: #D1FAE5;
    color: #059669;
    font-weight: 700;
    font-size: 13px;
    padding: 6px 16px;
    border-radius: 30px;
    display: inline-block;
    margin-bottom: 20px;
    letter-spacing: 0.2px;
  }
  .portfolio-hero-title {
    font-size: clamp(36px, 4.5vw, 56px);
    font-weight: 800;
    line-height: 1.1;
    color: #0F172A;
    margin-bottom: 20px;
    letter-spacing: -1px;
  }
  .portfolio-hero-title .text-emerald {
    color: #10B981 !important;
    font-style: italic;
    text-decoration: underline;
    text-decoration-color: #10B981;
    text-underline-offset: 4px;
  }
  .portfolio-hero-desc {
    font-size: 16px;
    color: #475569;
    line-height: 1.65;
    margin-bottom: 32px;
    max-width: 440px;
  }
  .btn-start-project {
    background: #10B981;
    color: #ffffff;
    font-weight: 700;
    font-size: 15px;
    padding: 13px 28px;
    border-radius: 30px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 8px 22px -4px rgba(16, 185, 129, 0.45);
    text-decoration: none;
    transition: all 0.3s ease;
  }
  .btn-start-project:hover {
    background: #059669;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 12px 28px -4px rgba(16, 185, 129, 0.55);
  }

  /* ===== HERO RIGHT GRAPHIC ===== */
  .portfolio-hero-graphic {
    position: relative;
    width: 100%;
  }
  .hero-img-main {
    width: 100%;
    display: block;
    border-radius: 0;
    object-fit: cover;
  }
  /* Floating stat cards */
  .stat-card-floating {
    position: absolute;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 10;
    white-space: nowrap;
  }
  .stat-card-floating .stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .stat-card-floating .stat-number {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    margin-bottom: 2px;
  }
  .stat-card-floating .stat-label {
    font-size: 12px;
    color: #64748B;
    font-weight: 500;
    line-height: 1.2;
  }
  .stat-card-left {
    top: 16%;
    left: -30px;
  }
  .stat-card-right {
    top: 10%;
    right: -20px;
  }

  /* "Ideas Design Results" cursive text badge */
  .ideas-badge {
    position: absolute;
    right: -10px;
    top: 8%;
    writing-mode: horizontal-tb;
    text-align: center;
    z-index: 11;
  }
  .ideas-badge-inner {
    background: #ffffff;
    border-radius: 16px;
    padding: 12px 14px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.10);
    font-family: 'Georgia', serif;
    font-style: italic;
    font-size: 16px;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.3;
  }

  /* ===== FILTER + SEARCH BAR ===== */
  .portfolio-filter-section {
    padding: 24px 0 24px;
    border-bottom: 1px solid #F1F5F9;
    margin-bottom: 28px;
  }
  .portfolio-filter-pill {
    background: #F1F5F9;
    color: #475569;
    font-weight: 600;
    font-size: 14px;
    padding: 9px 20px;
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
  .portfolio-search-wrap {
    position: relative;
    flex-shrink: 0;
  }
  .portfolio-search-wrap i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: 13px;
    pointer-events: none;
  }
  .portfolio-search-input {
    border-radius: 30px;
    border: 1.5px solid #E2E8F0;
    padding: 10px 18px 10px 40px;
    font-size: 14px;
    width: 240px;
    transition: all 0.25s ease;
    outline: none;
    color: #334155;
    background: #F8FAFC;
  }
  .portfolio-search-input:focus {
    border-color: #10B981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    background: #ffffff;
  }

  /* ===== PORTFOLIO GRID CARDS ===== */
  .portfolio-card-item {
    transition: all 0.3s ease;
  }
  .portfolio-card-inner {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #F1F5F9;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.04);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .portfolio-card-inner:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.10);
  }
  .portfolio-card-img-wrapper {
    position: relative;
    overflow: hidden;
    height: 240px;
    background: #F8FAFC;
  }
  .portfolio-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .portfolio-card-inner:hover .portfolio-card-img {
    transform: scale(1.06);
  }
  .portfolio-card-body {
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }
  .portfolio-card-title {
    font-size: 17px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 4px;
    line-height: 1.3;
  }
  .portfolio-card-meta {
    font-size: 12.5px;
    color: #64748B;
    font-weight: 500;
  }
  .portfolio-card-meta .dot-sep {
    color: #CBD5E1;
    margin: 0 4px;
  }
  .btn-arrow-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    color: #334155;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    flex-shrink: 0;
    text-decoration: none;
    cursor: pointer;
  }
  .portfolio-card-inner:hover .btn-arrow-circle {
    background: #10B981;
    border-color: #10B981;
    color: #ffffff;
    transform: rotate(45deg);
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1199px) {
    .stat-card-left { left: -10px; }
    .stat-card-right { right: 0; }
  }
  @media (max-width: 991px) {
    .portfolio-hero-section { padding: 50px 0 0; }
    .stat-card-floating { display: none; }
    .ideas-badge { display: none; }
    .portfolio-hero-desc { max-width: 100%; }
    .portfolio-search-input { width: 100%; }
    .portfolio-filter-section .d-flex { flex-wrap: wrap; gap: 10px !important; }
  }
  @media (max-width: 767px) {
    .portfolio-hero-title { font-size: 32px; }
    .portfolio-card-img-wrapper { height: 200px; }
    .portfolio-filter-pill { font-size: 13px; padding: 8px 16px; }
  }
</style>

<!-- ===== PORTFOLIO HERO ===== -->
<section class="portfolio-hero-section">
  <div class="container">
    <div class="row align-items-center g-4">
      <!-- LEFT: Text Content -->
      <div class="col-lg-5 pb-4">
        <div class="portfolio-badge">Our Portfolio</div>
        <h1 class="portfolio-hero-title">
          Our Work Speaks<br>For <span class="text-emerald">Itself</span>
        </h1>
        <p class="portfolio-hero-desc">
          Explore our latest projects and see how we turn ideas into impactful digital experiences.
        </p>
        @php
          $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
          $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.contact');
        @endphp
        <a href="{{ $contactUrl }}" class="btn-start-project">
          Start Your Project <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <!-- RIGHT: Hero Graphic with floating stat cards -->
      <div class="col-lg-7 position-relative">
        <div class="portfolio-hero-graphic">
          <!-- Floating Stat Card LEFT: 150+ Successful Projects -->
          <div class="stat-card-floating stat-card-left d-none d-xl-flex">
            <div class="stat-icon bg-light">
              <i class="fa-solid fa-chart-bar text-success"></i>
            </div>
            <div>
              <div class="stat-number">150+</div>
              <div class="stat-label">Successful Projects</div>
            </div>
          </div>

          <!-- Floating Stat Card RIGHT: 98% Client Satisfaction -->
          <div class="stat-card-floating stat-card-right d-none d-xl-flex">
            <div class="stat-icon" style="background: #FFF1F2;">
              <i class="fa-solid fa-heart" style="color: #F43F5E;"></i>
            </div>
            <div>
              <div class="stat-number">98%</div>
              <div class="stat-label">Client Satisfaction</div>
            </div>
          </div>

          <!-- Ideas Design Results badge (top-right) -->
          <div class="ideas-badge d-none d-xl-block">
            <div class="ideas-badge-inner">
              Ideas<br>Design<br>Results
            </div>
          </div>

          <!-- Main Portfolio Image -->
          <img src="{{ asset('assets/website_builder/Templates/Digital_agency/portfolio_herobanner.png') }}"
               onerror="this.src='{{ asset('assets/website_builder/Templates/Digital_agency/hero_banner.png') }}';"
               alt="Portfolio Showcase"
               class="hero-img-main">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FILTER TABS + SEARCH + PORTFOLIO GRID ===== -->
<section style="background: #ffffff; padding-top: 0;">
  <div class="container">

    <!-- Filter + Search Row -->
    <div class="portfolio-filter-section">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3" id="portfolioCategoryFilters">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <button class="portfolio-filter-pill active" data-category="all">All</button>
          <button class="portfolio-filter-pill" data-category="web-design">Web Design</button>
          <button class="portfolio-filter-pill" data-category="ui-ux">UI/UX Design</button>
          <button class="portfolio-filter-pill" data-category="branding">Branding</button>
          <button class="portfolio-filter-pill" data-category="mobile-app">Mobile App</button>
          <button class="portfolio-filter-pill" data-category="e-commerce">E-commerce</button>
          <button class="portfolio-filter-pill" data-category="marketing">Marketing</button>
        </div>
        <div class="portfolio-search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="portfolioSearchInput" class="portfolio-search-input" placeholder="Search projects...">
        </div>
      </div>
    </div>

    <!-- Portfolio Grid -->
    @php
      $projects = $agency->portfolio_data ?? [
        ['title' => 'Fintech Website Redesign', 'category' => 'Web Design • UI/UX',         'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'E-commerce Website',       'category' => 'Web Design • E-commerce',     'image' => 'assets/website_builder/wb_card_ecommerce.png'],
        ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Brand Identity Design',    'category' => 'Branding • Graphic Design',   'image' => 'assets/website_builder/wb_card_portfolio.png'],
        ['title' => 'Travel Website',           'category' => 'Web Design • UI/UX',          'image' => 'assets/website_builder/wb_card_events.png'],
        ['title' => 'Fitness App Design',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design • Web App',      'image' => 'assets/website_builder/wb_card_restaurant.png'],
        ['title' => 'Digital Marketing Campaign','category' => 'Marketing • Social Media',   'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'Restaurant Website',       'category' => 'Web Design • E-commerce',     'image' => 'assets/website_builder/wb_card_ecommerce.png'],
      ];
    @endphp

    <div class="row g-4" id="portfolioGrid">
      @foreach($projects as $project)
        @php
          $catLower = strtolower($project['category'] ?? '');
          $dataCat = 'web-design';
          if (str_contains($catLower, 'ui') || str_contains($catLower, 'ux')) $dataCat = 'ui-ux';
          elseif (str_contains($catLower, 'brand')) $dataCat = 'branding';
          elseif (str_contains($catLower, 'mobile') || str_contains($catLower, 'app')) $dataCat = 'mobile-app';
          elseif (str_contains($catLower, 'e-commerce') || str_contains($catLower, 'shop')) $dataCat = 'e-commerce';
          elseif (str_contains($catLower, 'market')) $dataCat = 'marketing';
          // split category on "•" for display
          $catParts = array_map('trim', explode('•', $project['category'] ?? 'Web Design'));
        @endphp
        <div class="col-lg-4 col-md-6 portfolio-card-item"
             data-category="{{ $dataCat }}"
             data-title="{{ strtolower($project['title'] ?? '') }} {{ $catLower }}">
          <div class="portfolio-card-inner">
            <div class="portfolio-card-img-wrapper">
              <img src="{{ str_starts_with($project['image'] ?? '', 'http') ? $project['image'] : asset($project['image'] ?? 'assets/website_builder/wb_card_agency.png') }}"
                   onerror="this.src='{{ asset('assets/website_builder/wb_card_agency.png') }}';"
                   alt="{{ $project['title'] ?? 'Project' }}"
                   class="portfolio-card-img">
            </div>
            <div class="portfolio-card-body">
              <div>
                <div class="portfolio-card-title">{{ $project['title'] ?? 'Project Title' }}</div>
                <div class="portfolio-card-meta">
                  @foreach($catParts as $i => $part)
                    @if($i > 0)<span class="dot-sep">•</span>@endif
                    {{ $part }}
                  @endforeach
                </div>
              </div>
              <a href="{{ $contactUrl }}" class="btn-arrow-circle" title="View Project">
                <i class="fa-solid fa-arrow-up-right"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- FILTER + SEARCH JAVASCRIPT -->
@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const pills = document.querySelectorAll('#portfolioCategoryFilters .portfolio-filter-pill');
    const searchInput = document.getElementById('portfolioSearchInput');
    const cards = document.querySelectorAll('#portfolioGrid .portfolio-card-item');

    let activeCat = 'all';
    let searchQuery = '';

    function filterCards() {
      cards.forEach(card => {
        const cat = card.getAttribute('data-category');
        const title = card.getAttribute('data-title');
        const catMatch = activeCat === 'all' || cat === activeCat || title.includes(activeCat.replace('-', ' '));
        const searchMatch = !searchQuery || title.includes(searchQuery);
        if (catMatch && searchMatch) {
          card.style.display = '';
          card.style.opacity = '1';
        } else {
          card.style.display = 'none';
        }
      });
    }

    pills.forEach(pill => {
      pill.addEventListener('click', function () {
        pills.forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        activeCat = this.getAttribute('data-category');
        filterCards();
      });
    });

    if (searchInput) {
      searchInput.addEventListener('input', function () {
        searchQuery = this.value.toLowerCase().trim();
        filterCards();
      });
    }
  });
</script>
@endsection
@endsection
