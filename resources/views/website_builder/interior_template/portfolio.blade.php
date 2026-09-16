@extends('website_builder.interior_template.layout')

@section('title', 'Portfolio - InterioCRAFT Architectural & Interior Design Showcase')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.portfolio');
@endphp

<!-- ===== HERO SECTION (PORTFOLIO SHOWCASE) ===== -->
@php
  $defaultPortHero = asset('assets/website_builder/Templates/Interior_agency/portfolio_hero.png');
  $heroImg = $interior->hero_image ?? '';
  $isOldAgencyOrUnsplash = empty($heroImg) 
    || str_contains($heroImg, 'unsplash.com') 
    || str_contains($heroImg, 'agency_template') 
    || str_contains($heroImg, 'herobanner_right')
    || str_contains($heroImg, 'photo-1618221195710');
  $portHeroSrc = !$isOldAgencyOrUnsplash ? (str_starts_with($heroImg, 'http') ? $heroImg : asset(ltrim($heroImg, '/'))) : $defaultPortHero;
@endphp

<section class="ic-hero position-relative overflow-hidden ic-hero-mobile-bg" style="background-color: #F7F7F5; padding: 75px 0 85px; background-image: url('{{ $portHeroSrc }}');">

  <!-- Right Side Full Height Cover Background Image (Desktop) -->
  <div class="position-absolute top-0 end-0 bottom-0 d-none d-lg-block" style="width: 55%; z-index: 1;">
    <img src="{{ $portHeroSrc }}"
         onerror="this.src='{{ $defaultPortHero }}';"
         alt="{{ $interior->site_title ?? 'InterioCRAFT Portfolio' }}" style="width: 100%; height: 100%; object-fit: cover; object-position: right center; display: block;">
    <div style="position: absolute; top:0; left:0; bottom:0; width: 35%; background: linear-gradient(to right, #F7F7F5 0%, rgba(247,247,245,0) 100%);"></div>
  </div>

  <div class="ic-container position-relative" style="z-index: 2;">
    <div class="ic-hero-grid">
      <div>
        <span class="ic-pill-badge">
          {{ $interior->hero_badge ?? 'Our Portfolio' }}
        </span>
        <h1 class="ic-heading ic-hero-title">
          Spaces We Design,<br>Stories We <span class="ic-cursive" style="font-size: 64px; color: var(--ic-primary);">Create</span>
        </h1>
        <p class="ic-hero-subtitle">
          {{ $interior->hero_subtitle ?? 'Explore our latest interior design projects and see how we turn ideas into beautiful, functional spaces.' }}
        </p>

        <div class="ic-hero-actions d-flex align-items-center gap-1.5 gap-sm-3 mb-4 w-100">
          <a href="{{ $interior->primary_btn_url ?? $contactUrl }}" class="ic-btn ic-btn-dark py-2 py-sm-3 px-1.5 px-sm-4 flex-fill text-center fw-bold d-inline-flex align-items-center justify-content-center gap-1 gap-sm-2" style="font-size: 12px; white-space: nowrap;">
            {{ $interior->primary_btn_text ?? 'Start Your Project' }}
            <span class="rounded-circle d-inline-flex align-items-center justify-content-center bg-white text-dark ms-1" style="width: 24px; height: 24px; flex-shrink: 0;">
              <i class="fa-solid fa-arrow-right" style="font-size: 9px;"></i>
            </span>
          </a>
          <a href="{{ $interior->secondary_btn_url ?? '#video' }}" class="ic-btn py-2 py-sm-3 px-1.5 px-sm-4 flex-fill text-center fw-bold d-inline-flex align-items-center justify-content-center gap-1 gap-sm-2" style="border: 1.5px solid var(--ic-secondary); background: #ffffff; color: var(--ic-text-dark); border-radius: 9999px; font-size: 12px; white-space: nowrap;">
            Watch Our Story
            <span class="rounded-circle d-inline-flex align-items-center justify-content-center ms-1" style="width: 24px; height: 24px; background: #F2F5F3; color: #111; flex-shrink: 0;">
              <i class="fa-solid fa-play" style="font-size: 8px;"></i>
            </span>
          </a>
        </div>
      </div>
    </div>

    <!-- 4 Floating Stat Card Boxes (Matches Homepage Hero) -->
    @php
      $stats = $interior->stats_data ?? [
        ['number' => '8+',   'label' => 'Years of Experience', 'icon' => 'fa-trophy'],
        ['number' => '250+', 'label' => 'Projects Completed',  'icon' => 'fa-house'],
        ['number' => '98%',  'label' => 'Client Satisfaction', 'icon' => 'fa-star'],
        ['number' => '24/7', 'label' => 'Design Support',      'icon' => 'fa-headset'],
      ];
    @endphp

    <div class="row g-2 g-md-3 mt-3 mt-md-4">
      @foreach($stats as $st)
        <div class="col-6 col-lg-3">
          <div class="card border-0 shadow-sm rounded-4 p-2 p-md-3 bg-white h-100 d-flex flex-row align-items-center gap-2 gap-md-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 ic-stat-icon-circle">
              <i class="fa-solid {{ $st['icon'] ?? 'fa-house' }}"></i>
            </div>
            <div class="overflow-hidden">
              <div class="ic-counter-num fw-bold text-dark mb-0 ic-stat-counter-num" data-target="{{ $st['number'] ?? $st['num'] ?? '' }}">
                {{ $st['number'] ?? $st['num'] ?? '' }}
              </div>
              <div class="text-muted fw-semibold ic-stat-label-text text-truncate">
                {{ $st['label'] ?? '' }}
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== PORTFOLIO PROJECTS SECTION ===== -->
<section id="portfolio" class="py-4" style="background: #ffffff;">
  <div class="ic-container">
    <!-- Filter Bar & Search Input (Single Row Horizontally Scrollable & Auto Slider) -->
    <div class="ic-filter-bar mb-4">
      <div class="ic-filter-tabs flex-nowrap overflow-auto py-1" id="portfolioTabs" style="scrollbar-width: none; -ms-overflow-style: none;">
        <button type="button" class="ic-filter-tab active flex-shrink-0" onclick="filterProjects('all', this)">All Projects</button>
        <button type="button" class="ic-filter-tab flex-shrink-0" onclick="filterProjects('residential', this)">Residential</button>
        <button type="button" class="ic-filter-tab flex-shrink-0" onclick="filterProjects('commercial', this)">Commercial</button>
        <button type="button" class="ic-filter-tab flex-shrink-0" onclick="filterProjects('office spaces', this)">Office Spaces</button>
        <button type="button" class="ic-filter-tab flex-shrink-0" onclick="filterProjects('hospitality', this)">Hospitality</button>
        <button type="button" class="ic-filter-tab flex-shrink-0" onclick="filterProjects('renovation', this)">Renovation</button>
        <button type="button" class="ic-filter-tab flex-shrink-0" onclick="filterProjects('space planning', this)">Space Planning</button>
        <button type="button" class="ic-filter-tab flex-shrink-0" onclick="filterProjects('interior styling', this)">Interior Styling</button>
      </div>

      <div class="ic-search-box flex-shrink-0 mt-2 mt-md-0">
        <i class="fa-solid fa-magnifying-glass ic-search-icon"></i>
        <input type="text" class="ic-search-input" placeholder="Search projects..." id="projectSearchInput" onkeyup="searchProjects()">
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
          'title'    => 'Elegant Modular Kitchen',
          'category' => 'Residential',
          'desc'     => 'Functional design for modern homes.',
          'image'    => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-utensils'
        ],
        [
          'title'    => 'Modern Office Space',
          'category' => 'Commercial',
          'desc'     => 'Productive spaces for growing businesses.',
          'image'    => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-building'
        ],
        [
          'title'    => 'Luxury Bedroom',
          'category' => 'Residential',
          'desc'     => 'A peaceful retreat for your everyday life.',
          'image'    => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-bed'
        ],
        [
          'title'    => 'Stylish Restaurant',
          'category' => 'Hospitality',
          'desc'     => 'Inviting spaces that leave a lasting impression.',
          'image'    => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-utensils'
        ],
        [
          'title'    => 'Retail Store Design',
          'category' => 'Commercial',
          'desc'     => 'Creative interiors for modern brands.',
          'image'    => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-bag-shopping'
        ],
        [
          'title'    => 'Bathroom Makeover',
          'category' => 'Renovation',
          'desc'     => 'Transforming spaces with elegant details.',
          'image'    => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-shower'
        ],
        [
          'title'    => 'Home Styling',
          'category' => 'Interior Styling',
          'desc'     => 'Thoughtful details that make a difference.',
          'image'    => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-couch'
        ],
        [
          'title'    => 'Outdoor Living Space',
          'category' => 'Space Planning',
          'desc'     => 'Beautiful spaces beyond your walls.',
          'image'    => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=800&auto=format&fit=crop',
          'icon'     => 'fa-tree'
        ],
      ];
    @endphp

    <!-- 3-Column Projects Grid (9 Cards) -->
    <div class="ic-projects-grid" id="projectsContainer">
      @foreach($portfolio as $proj)
        <div class="ic-project-card project-card-item" data-category="{{ strtolower($proj['category'] ?? '') }}" data-title="{{ strtolower($proj['title'] ?? '') }}">
          <div class="ic-project-thumb">
            <img src="{{ str_starts_with($proj['image'] ?? '', 'http') ? ($proj['image'] ?? '') : asset(ltrim($proj['image'] ?? '', '/')) }}" alt="{{ $proj['title'] ?? '' }}" class="ic-project-img">
            <span class="ic-project-cat-badge">
              <i class="fa-solid {{ $proj['icon'] ?? 'fa-tag' }}"></i> {{ $proj['category'] ?? 'Design' }}
            </span>
          </div>
          <div class="ic-project-body">
            <div>
              <h3 class="ic-project-title">{{ $proj['title'] ?? '' }}</h3>
              <p class="ic-project-desc">{{ $proj['desc'] ?? $proj['description'] ?? '' }}</p>
            </div>
            <a href="{{ $contactUrl }}" class="ic-arrow-btn" aria-label="View Project">
              <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>



<script>
  function filterProjects(cat, btn) {
    var tabs = document.querySelectorAll('#portfolioTabs .ic-filter-tab');
    tabs.forEach(function(t) { t.classList.remove('active'); });
    btn.classList.add('active');

    var items = document.querySelectorAll('#projectsContainer .project-card-item');
    cat = cat.toLowerCase().trim();

    items.forEach(function(item) {
      var itemCat = item.getAttribute('data-category');
      if (cat === 'all' || itemCat.includes(cat) || cat.includes(itemCat)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  }

  function searchProjects() {
    var input = document.getElementById('projectSearchInput').value.toLowerCase().trim();
    var items = document.querySelectorAll('#projectsContainer .project-card-item');

      if (title.includes(input) || cat.includes(input)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    var tabsTrack = document.getElementById('portfolioTabs');
    if (tabsTrack) {
      let autoSlideTimer;
      function startTabsAutoSlide() {
        autoSlideTimer = setInterval(function() {
          var maxScroll = tabsTrack.scrollWidth - tabsTrack.clientWidth;
          if (maxScroll > 0) {
            if (tabsTrack.scrollLeft >= maxScroll - 5) {
              tabsTrack.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
              tabsTrack.scrollBy({ left: 140, behavior: 'smooth' });
            }
          }
        }, 3200);
      }
      startTabsAutoSlide();
      tabsTrack.addEventListener('touchstart', function() { clearInterval(autoSlideTimer); }, { passive: true });
      tabsTrack.addEventListener('touchend', function() { startTabsAutoSlide(); }, { passive: true });
    }
  });
</script>

@endsection
