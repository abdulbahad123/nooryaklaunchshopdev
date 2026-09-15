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
          {{ $interior->hero_badge ?? 'Our Portfolio' }}
        </span>
        <h1 class="ic-heading ic-hero-title">
          Spaces We Design,<br>Stories We <span class="ic-cursive" style="font-size: 64px; color: var(--ic-primary);">Create</span>
        </h1>
        <p class="ic-hero-subtitle">
          {{ $interior->hero_subtitle ?? 'Explore our latest interior design projects and see how we turn ideas into beautiful, functional spaces.' }}
        </p>

        <div class="ic-hero-actions">
          <a href="{{ $interior->primary_btn_url ?? '#contact' }}" class="ic-btn ic-btn-dark">
            {{ $interior->primary_btn_text ?? 'Start Your Project' }} <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="{{ $interior->secondary_btn_url ?? '#video' }}" class="ic-btn-video">
            <div class="ic-play-icon"><i class="fa-solid fa-play ms-1"></i></div>
            <div>
              <div>Watch Our Story</div>
              <div style="font-size: 11.5px; font-weight: 500; color: var(--ic-text-muted);">2 min video</div>
            </div>
          </a>
        </div>

        <!-- 3-Stats Floating Box -->
        <div class="ic-hero-stats">
          @php
            $stats = $interior->stats_data ?? [
              ['number' => '250+', 'label' => 'Projects Completed', 'icon' => 'fa-house'],
              ['number' => '98%',  'label' => 'Client Satisfaction',  'icon' => 'fa-star'],
              ['number' => '120+', 'label' => 'Happy Homeowners',   'icon' => 'fa-users'],
            ];
          @endphp

          @foreach($stats as $st)
            <div class="ic-stat-box">
              <div class="ic-stat-circle">
                <i class="fa-solid {{ $st['icon'] ?? 'fa-house' }}"></i>
              </div>
              <div>
                <div class="ic-stat-num">{{ $st['number'] ?? $st['num'] ?? '' }}</div>
                <div class="ic-stat-lbl">{{ $st['label'] ?? '' }}</div>
              </div>
            </div>
          @endforeach
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

<!-- ===== PORTFOLIO PROJECTS SECTION ===== -->
<section id="portfolio" class="py-5" style="background: #ffffff;">
  <div class="ic-container">
    <!-- Filter Bar & Search Input -->
    <div class="ic-filter-bar">
      <div class="ic-filter-tabs" id="portfolioTabs">
        <button type="button" class="ic-filter-tab active" onclick="filterProjects('all', this)">All Projects</button>
        <button type="button" class="ic-filter-tab" onclick="filterProjects('residential', this)">Residential</button>
        <button type="button" class="ic-filter-tab" onclick="filterProjects('commercial', this)">Commercial</button>
        <button type="button" class="ic-filter-tab" onclick="filterProjects('office spaces', this)">Office Spaces</button>
        <button type="button" class="ic-filter-tab" onclick="filterProjects('hospitality', this)">Hospitality</button>
        <button type="button" class="ic-filter-tab" onclick="filterProjects('renovation', this)">Renovation</button>
        <button type="button" class="ic-filter-tab" onclick="filterProjects('space planning', this)">Space Planning</button>
        <button type="button" class="ic-filter-tab" onclick="filterProjects('interior styling', this)">Interior Styling</button>
      </div>

      <div class="ic-search-box">
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

<!-- ===== CALL TO ACTION BANNER ===== -->
<div class="ic-container">
  <div class="ic-cta-box">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="ic-cta-eyebrow">LET'S DESIGN TOGETHER</div>
        <h2 class="ic-cta-title">{{ $interior->contact_title ?? 'Ready to Transform Your Space?' }}</h2>
        <p class="ic-cta-sub">
          {{ $interior->contact_subtitle ?? "Let's work together to create a space that reflects your style and enhances your everyday life." }}
        </p>

        <a href="{{ $contactUrl }}" class="ic-btn ic-btn-light fs-6">
          Get in Touch <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>

        <!-- 3 Feature Badges Below Button -->
        <div class="ic-cta-features">
          <div class="ic-cta-feat-item">
            <div class="ic-cta-feat-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
              <div class="ic-cta-feat-title">Free Consultation</div>
              <div class="ic-cta-feat-sub">Let's discuss your ideas</div>
            </div>
          </div>

          <div class="ic-cta-feat-item">
            <div class="ic-cta-feat-icon"><i class="fa-solid fa-compass-drafting"></i></div>
            <div>
              <div class="ic-cta-feat-title">Custom Design Plans</div>
              <div class="ic-cta-feat-sub">Tailored to your needs</div>
            </div>
          </div>

          <div class="ic-cta-feat-item">
            <div class="ic-cta-feat-icon"><i class="fa-solid fa-clock-check"></i></div>
            <div>
              <div class="ic-cta-feat-title">On-Time Delivery</div>
              <div class="ic-cta-feat-sub">Hassle-free experience</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Armchair Image + Cursive Overlay -->
      <div class="col-lg-5 d-none d-lg-block position-relative text-end">
        @php
          $defaultCtaImg = asset('assets/website_builder/Templates/Interior_agency/cta_footer.png');
          $ctaImgSrc = !empty($interior->contact_image) ? (str_starts_with($interior->contact_image, 'http') ? $interior->contact_image : asset(ltrim($interior->contact_image, '/'))) : $defaultCtaImg;
        @endphp
        <img src="{{ $ctaImgSrc }}"
             onerror="this.src='{{ $defaultCtaImg }}';"
             alt="Luxury Interior Chair" class="rounded-4 shadow-lg border" style="max-height: 360px; width: 85%; object-fit: cover;">
        <div class="position-absolute bottom-0 start-0 mb-4 ms-3">
          <span class="ic-cursive" style="font-size: 34px; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.6);">
            Your Vision<br>Our Design
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

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

    items.forEach(function(item) {
      var title = item.getAttribute('data-title');
      var cat = item.getAttribute('data-category');
      if (title.includes(input) || cat.includes(input)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  }
</script>

@endsection
