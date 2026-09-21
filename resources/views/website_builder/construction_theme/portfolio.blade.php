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
  <div class="cn-container" style="position: relative; z-index: 2;">
    <div class="row align-items-center justify-content-start">
      <!-- Left Content -->
      <div class="col-lg-7 py-4 text-start" style="text-align: left !important;">
        <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left !important;">
          <span class="cn-pill-badge mb-3" style="background: #FFF8E6; color: #945B00; display: inline-flex; align-items: center; align-self: flex-start;">
            {{ $agency->portfolio_badge ?? 'OUR PROJECTS' }}
          </span>
          <h1 class="cn-heading cn-hero-title mb-2" style="font-size: clamp(30px, 4.2vw, 48px); line-height: 1.15; text-align: left !important; margin-left: 0 !important;">
            {!! nl2br(e($agency->portfolio_title ?? "Projects Built For\nEvery Need")) !!}
          </h1>
          <p class="cn-hero-subtitle mb-3" style="font-size: 15px; max-width: 520px; text-align: left !important; margin-left: 0 !important;">
            {{ $agency->portfolio_subtitle ?? 'Discover our showcase of completed commercial, residential, industrial, and infrastructure landmark constructions.' }}
          </p>

          <!-- Actions -->
          <div class="d-flex align-items-center justify-content-start gap-2 gap-sm-3 mb-2 w-100 flex-wrap" style="justify-content: flex-start !important;">
            <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow px-4 py-2.5 fw-bold">
              {{ $agency->primary_btn_text ?? 'Start Your Project' }} <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
            <a href="#projects-grid" class="cn-btn cn-btn-outline-dark px-4 py-2.5 fw-bold">
              {{ $agency->secondary_btn_text ?? 'Explore Portfolio' }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@php
  $items = $portfolio ?? $agency->portfolio_data ?? [];
  if (empty($items)) {
    $items = [
      ['category' => 'Commercial',    'title' => 'Skyline Commercial Tower',   'desc' => 'State-of-the-art 35-story corporate headquarters.',    'image' => asset('assets/website_builder/Templates/Construction_agency/service_commercial.png'), 'icon' => 'fa-building'],
      ['category' => 'Residential',   'title' => 'Horizon Luxury Apartments', 'desc' => 'Modern residential complex featuring 120 luxury units.',  'image' => asset('assets/website_builder/Templates/Construction_agency/service_residential.png'), 'icon' => 'fa-house-chimney'],
      ['category' => 'Infrastructure','title' => 'Metro Expressway Bridge',   'desc' => 'Engineered 6-lane elevated highway bridge system.',     'image' => asset('assets/website_builder/Templates/Construction_agency/service_infra.png'), 'icon' => 'fa-bridge'],
    ];
  }

  $dynamicCategories = [];
  foreach ($items as $item) {
    $catStr = $item['category'] ?? '';
    if (!empty($catStr)) {
      $cats = preg_split('/[•,]+/', $catStr);
      foreach ($cats as $c) {
        $trimmed = trim($c);
        if ($trimmed !== '') {
          $slug = \Illuminate\Support\Str::slug($trimmed);
          $dynamicCategories[$slug] = $trimmed;
        }
      }
    }
  }
@endphp

<!-- ===== PROJECTS FILTER TABS & SEARCH BAR ===== -->
<section id="projects-grid" class="cn-section" style="background: #ffffff;">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <!-- Dynamic Filter Pills -->
      <div class="cn-category-scroll-track" id="cnFilterTrack">
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold active-filter" data-filter="all" onclick="filterProjects('all', this)" style="background: var(--cn-primary); color: #0D0F12;">All Projects</button>
        @foreach($dynamicCategories as $slug => $catName)
          <button class="btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border" data-filter="{{ $slug }}" onclick="filterProjects('{{ $slug }}', this)">{{ $catName }}</button>
        @endforeach
      </div>

      <!-- Search Input -->
      <div class="position-relative" style="width: 240px;">
        <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 13px;"></i>
        <input type="text" id="cnSearchInput" class="form-control form-control-sm rounded-pill ps-5 py-2 border" placeholder="Search projects..." onkeyup="searchProjects(this.value)">
      </div>
    </div>

    <!-- PROJECTS GRID -->
    <div class="row g-4" id="cnProjectsCardsContainer">
      @foreach($items as $prj)
        @php
          $catStr = $prj['category'] ?? '';
          $catSlugs = [];
          if (!empty($catStr)) {
            foreach (preg_split('/[•,]+/', $catStr) as $c) {
              $t = trim($c);
              if ($t !== '') $catSlugs[] = \Illuminate\Support\Str::slug($t);
            }
          }
          $dataCatAttr = implode(' ', $catSlugs);
          $prjImg = $prj['image'] ?? $prj['img'] ?? asset('assets/website_builder/Templates/Construction_agency/service_commercial.png');
          $prjIcon = $prj['icon'] ?? 'fa-building';
        @endphp
        <div class="col-12 col-md-6 col-lg-4 cn-project-card-item" data-category="{{ $dataCatAttr }}" data-title="{{ strtolower($prj['title'] ?? '') }}">
          <div class="cn-portfolio-card">
            <div class="cn-portfolio-img-wrap">
              <img src="{{ str_starts_with($prjImg, 'http') ? $prjImg : asset(ltrim($prjImg, '/')) }}" alt="{{ $prj['title'] ?? '' }}">
            </div>
            <div class="cn-portfolio-info">
              <div class="cn-portfolio-icon">
                <i class="fa-solid {{ $prjIcon }}"></i>
              </div>
              <div class="cn-portfolio-text">
                <h3 class="cn-portfolio-title">{{ $prj['title'] ?? '' }}</h3>
                <p class="cn-portfolio-desc">{{ $prj['desc'] ?? $prj['description'] ?? '' }}</p>
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
<section class="cn-footer-cta-wrapper">
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
      b.classList.remove('active-filter', 'active');
      b.className = 'btn btn-sm rounded-pill px-3 py-2 fw-bold btn-light border';
      b.removeAttribute('style');
    });
    btnEl.className = 'btn btn-sm rounded-pill px-3 py-2 fw-bold active-filter';
    btnEl.setAttribute('style', 'background: var(--cn-primary) !important; color: #0D0F12 !important;');

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
