@extends('website_builder.construction_theme.layout')

@section('no_cta')@endsection

@section('title', 'Projects & Portfolio - ' . ($agency->site_title ?? 'BuildCraft Construction'))

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.portfolio');
  $servicesUrl = $subdomainParam ? route('website-builder.subdomain.services', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.services');
  $agency = $agency ?? $interior ?? null;
@endphp

<!-- ===== HERO SECTION (MATCHING TAXIGO PORTFOLIO REF IMAGE) ===== -->
@php
  $heroBannerBg = asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
@endphp

<section class="cn-hero" style="background: url('{{ $heroBannerBg }}') no-repeat center right / cover; min-height: 520px; position: relative;">
  <div class="cn-hero-overlay"></div>
  <div class="cn-container py-4" style="position: relative; z-index: 2;">
    <div class="row align-items-center">
      <!-- Left Content -->
      <div class="col-lg-6 py-3">
        <span class="cn-pill-badge" style="background: #FFF8E6; color: #945B00;">
          OUR PROJECTS
        </span>
        <h1 class="cn-heading cn-hero-title">
          Projects Built For<br><span style="color: var(--cn-primary);">Every Need</span>
        </h1>
        <p class="cn-hero-subtitle">
          Safe. Reliable. Sustainable. Discover our showcase of completed commercial, residential, industrial, and infrastructure landmark constructions.
        </p>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-2 gap-sm-3 mb-4 w-100 flex-wrap">
          <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow px-4 py-3 fw-bold">
            Start Your Project <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="#projects-grid" class="cn-btn cn-btn-outline-dark px-4 py-3 fw-bold">
            Explore Portfolio
          </a>
        </div>

        <!-- 3 Feature Badges Below Buttons -->
        <div class="d-flex align-items-center gap-3 pt-3 flex-wrap">
          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #FFF8E6; color: var(--cn-primary-dark); font-size: 16px;">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div style="font-size: 12px; font-weight: 700; color: #0D0F12; line-height: 1.2;">
              Safe &<br><span class="text-muted fw-semibold" style="font-size: 11px;">Quality Built</span>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #FFF8E6; color: var(--cn-primary-dark); font-size: 16px;">
              <i class="fa-solid fa-headset"></i>
            </div>
            <div style="font-size: 12px; font-weight: 700; color: #0D0F12; line-height: 1.2;">
              24/7<br><span class="text-muted fw-semibold" style="font-size: 11px;">Site Support</span>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #FFF8E6; color: var(--cn-primary-dark); font-size: 16px;">
              <i class="fa-solid fa-calculator"></i>
            </div>
            <div style="font-size: 12px; font-weight: 700; color: #0D0F12; line-height: 1.2;">
              Transparent<br><span class="text-muted fw-semibold" style="font-size: 11px;">& Clear Pricing</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== PROJECTS FILTER TABS & SEARCH BAR ===== -->
<section id="projects-grid" class="py-3" style="background: #ffffff;">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <!-- Filter Pills -->
      <div class="cn-category-scroll-track" id="cnFilterTrack">
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold active-filter" data-filter="all" onclick="filterProjects('all', this)" style="background: var(--cn-primary); color: #0D0F12;">All Projects</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="commercial" onclick="filterProjects('commercial', this)">Commercial</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="residential" onclick="filterProjects('residential', this)">Residential</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="industrial" onclick="filterProjects('industrial', this)">Industrial</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="infrastructure" onclick="filterProjects('infrastructure', this)">Infrastructure</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="renovation" onclick="filterProjects('renovation', this)">Renovation</button>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="architecture" onclick="filterProjects('architecture', this)">Modern Arch</button>
      </div>

      <!-- Search Input -->
      <div class="position-relative" style="width: 240px;">
        <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 13px;"></i>
        <input type="text" id="cnSearchInput" class="form-control form-control-sm rounded-pill ps-5 py-2 border" placeholder="Search projects..." onkeyup="searchProjects(this.value)">
      </div>
    </div>

    <!-- 9 PROJECTS GRID (3 PER ROW) -->
    @php
      $allProjects = $agency->portfolio_data ?? [
        ['category' => 'commercial',    'title' => 'Skyline Commercial Tower',   'desc' => 'State-of-the-art 35-story corporate headquarters.',    'image' => asset('assets/website_builder/Templates/Construction_agency/service_commercial.png'), 'icon' => 'fa-building'],
        ['category' => 'residential',   'title' => 'Horizon Luxury Apartments', 'desc' => 'Modern residential complex featuring 120 luxury units.',  'image' => asset('assets/website_builder/Templates/Construction_agency/service_residential.png'), 'icon' => 'fa-house-chimney'],
        ['category' => 'infrastructure','title' => 'Metro Expressway Bridge',   'desc' => 'Engineered 6-lane elevated highway bridge system.',     'image' => asset('assets/website_builder/Templates/Construction_agency/service_infra.png'), 'icon' => 'fa-bridge'],
        ['category' => 'industrial',    'title' => 'Apex Logistics Facility',   'desc' => '250,000 sq ft smart distribution and warehouse center.','image' => asset('assets/website_builder/Templates/Construction_agency/service_commercial.png'), 'icon' => 'fa-warehouse'],
        ['category' => 'renovation',    'title' => 'Grand Heritage Hotel',      'desc' => 'Full structural restoration and modern interior revamp.','image' => asset('assets/website_builder/Templates/Construction_agency/service_residential.png'), 'icon' => 'fa-hammer'],
        ['category' => 'architecture',  'title' => 'Eco-Tech Civic Center',     'desc' => 'LEED Platinum certified community innovation hub.',     'image' => asset('assets/website_builder/Templates/Construction_agency/service_infra.png'), 'icon' => 'fa-city'],
        ['category' => 'commercial',    'title' => 'Plaza Retail Center',        'desc' => 'Vibrant shopping mall and entertainment destination.', 'image' => asset('assets/website_builder/Templates/Construction_agency/service_commercial.png'), 'icon' => 'fa-store'],
        ['category' => 'residential',   'title' => 'Green Valley Eco-Villas',   'desc' => 'Sustainable solar-powered residential villa community.','image' => asset('assets/website_builder/Templates/Construction_agency/service_residential.png'), 'icon' => 'fa-tree-city'],
        ['category' => 'infrastructure','title' => 'Central Harbor Expansion', 'desc' => 'Deepwater port facility and marine terminal engineering.','image' => asset('assets/website_builder/Templates/Construction_agency/service_infra.png'), 'icon' => 'fa-ship'],
      ];
    @endphp

    <div class="row g-4" id="cnProjectsCardsContainer">
      @foreach($allProjects as $prj)
        <div class="col-12 col-md-6 col-lg-4 cn-project-card-item" data-category="{{ $prj['category'] ?? 'commercial' }}" data-title="{{ strtolower($prj['title'] ?? '') }}">
          <div class="cn-portfolio-card">
            <div class="cn-portfolio-img-wrap">
              <img src="{{ str_starts_with($prj['image'] ?? '', 'http') ? ($prj['image'] ?? '') : asset(ltrim($prj['image'] ?? '', '/')) }}" alt="{{ $prj['title'] ?? '' }}">
            </div>
            <div class="cn-portfolio-info">
              <div class="cn-portfolio-icon">
                <i class="fa-solid {{ $prj['icon'] ?? 'fa-building' }}"></i>
              </div>
              <div class="cn-portfolio-text">
                <h3 class="cn-portfolio-title">{{ $prj['title'] ?? '' }}</h3>
                <p class="cn-portfolio-desc">{{ $prj['desc'] ?? '' }}</p>
              </div>
              <a href="{{ $contactUrl }}" class="cn-portfolio-arrow" aria-label="View Project">
                <i class="fa-solid fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== FOOTER CTA BANNER ===== -->
@php
  $footerCtaBg = asset('assets/website_builder/Templates/Construction_agency/construction_footercta.png');
@endphp
<section class="cn-footer-cta-wrapper py-4">
  <div class="cn-container">
    <div class="cn-footer-cta-card" style="background: url('{{ $footerCtaBg }}') no-repeat center center / cover;">
      <div class="cn-cta-overlay"></div>
      
      <div style="position: relative; z-index: 2;">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="cn-pill-badge mb-2" style="background: rgba(255,184,0,0.2); color: #FFB800;">LET'S BUILD TOGETHER</div>
            <h2 class="cn-cta-title text-white fw-extrabold mb-3" style="font-family: 'Barlow Condensed', sans-serif; font-size: clamp(28px, 4vw, 44px);">
              Turn Your Ideas Into <span class="cn-text-yellow" style="color: #FFB800;">Reality</span>
            </h2>
            <p class="cn-cta-sub mb-4 text-white-50">
              Partner with BuildCraft for innovative, reliable, and sustainable construction solutions.
            </p>
            <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
              Get a Quote <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>

          <!-- Right Side Vertical Step Column -->
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

@section('scripts')
<script>
  function filterProjects(category, btnEl) {
    document.querySelectorAll('#cnFilterTrack button').forEach(b => {
      b.classList.remove('active-filter');
      b.className = 'btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border';
    });
    btnEl.className = 'btn btn-sm rounded-pill px-3 py-2 fw-bold active-filter';
    btnEl.style.background = 'var(--cn-primary)';
    btnEl.style.color = '#0D0F12';

    const cards = document.querySelectorAll('.cn-project-card-item');
    cards.forEach(card => {
      if (category === 'all' || card.getAttribute('data-category') === category) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function searchProjects(query) {
    const q = query.toLowerCase().trim();
    const cards = document.querySelectorAll('.cn-project-card-item');
    cards.forEach(card => {
      const title = card.getAttribute('data-title') || '';
      if (title.includes(q)) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }
</script>
@endsection

@endsection
