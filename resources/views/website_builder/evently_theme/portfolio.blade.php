@extends('website_builder.evently_theme.layout')

@section('title', 'Our Events - ' . ($interior->site_title ?? 'Evently'))

@section('content')

@php
  $evData = $interior ?? $agency ?? null;
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;

  $homeUrl      = $subdomainParam ? route('website-builder.subdomain.site',      ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.about');
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.portfolio');
@endphp

<!-- ===== PORTFOLIO HERO BANNER ===== -->
<section class="ev-page-hero">
  <div class="ev-container">
    <div class="ev-page-hero-content">
      <div class="ev-page-hero-badge">
        <i class="fa-solid fa-calendar-check"></i> Our Events
      </div>
      <h1 class="ev-page-hero-title">
        Events We've<br>
        <span style="color: var(--ev-primary-light); font-style: italic;">Brought to Life</span>
      </h1>
      <p class="ev-page-hero-sub">
        Browse our portfolio of unforgettable events — from intimate weddings to grand corporate galas.
      </p>
      <div class="ev-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <span class="separator"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
        <span class="current">Our Events</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== EVENTS PORTFOLIO GRID ===== -->
<section class="ev-portfolio-section" id="portfolio">
  <div class="ev-container">

    <!-- Filter Bar -->
    <div class="ev-filter-bar mb-5">
      <div class="ev-filter-tabs" id="evPortfolioTabs" style="overflow-x: auto; flex-wrap: nowrap; scrollbar-width: none;">
        <button type="button" class="ev-filter-tab active flex-shrink-0" onclick="evFilterProjects('all', this)">All Events</button>
        <button type="button" class="ev-filter-tab flex-shrink-0" onclick="evFilterProjects('wedding', this)">Weddings</button>
        <button type="button" class="ev-filter-tab flex-shrink-0" onclick="evFilterProjects('corporate', this)">Corporate</button>
        <button type="button" class="ev-filter-tab flex-shrink-0" onclick="evFilterProjects('birthday', this)">Birthdays</button>
        <button type="button" class="ev-filter-tab flex-shrink-0" onclick="evFilterProjects('conference', this)">Conferences</button>
        <button type="button" class="ev-filter-tab flex-shrink-0" onclick="evFilterProjects('exhibition', this)">Exhibitions</button>
        <button type="button" class="ev-filter-tab flex-shrink-0" onclick="evFilterProjects('social', this)">Social</button>
      </div>

      <div class="ev-search-box flex-shrink-0 mt-2 mt-md-0">
        <i class="fa-solid fa-magnifying-glass ev-search-icon"></i>
        <input type="text" class="ev-search-input" placeholder="Search events..." id="evSearchInput" onkeyup="evSearchProjects()">
      </div>
    </div>

    @php
      $portfolio = $evData->portfolio_data ?? [
        ['title' => 'Grand Wedding Gala',      'category' => 'Wedding',     'desc' => 'A dreamy outdoor wedding with 500 guests.',          'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-heart'],
        ['title' => 'TechCorp Annual Summit',  'category' => 'Corporate',   'desc' => 'Full-scale corporate conference with live streaming.', 'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-briefcase'],
        ['title' => 'Rooftop Birthday Bash',   'category' => 'Birthday',    'desc' => 'Exclusive rooftop celebration with live music.',       'image' => 'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-cake-candles'],
        ['title' => 'Global Trade Exhibition', 'category' => 'Exhibition',  'desc' => 'International trade show for 200+ brands.',           'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-store'],
        ['title' => 'Leadership Conference',   'category' => 'Conference',  'desc' => 'Three-day leadership summit with keynote speakers.',   'image' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-microphone-lines'],
        ['title' => 'Anniversary Celebration', 'category' => 'Social',      'desc' => 'Romantic 25th anniversary dinner for 80 guests.',     'image' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-champagne-glasses'],
        ['title' => 'Beach Wedding Ceremony',  'category' => 'Wedding',     'desc' => 'A stunning sunset beach wedding experience.',          'image' => 'https://images.unsplash.com/photo-1606216794074-735e91aa2c92?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-heart'],
        ['title' => 'Product Launch Event',    'category' => 'Corporate',   'desc' => 'Exciting product reveal with media and press.',        'image' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-rocket'],
        ['title' => 'Kids Birthday Party',     'category' => 'Birthday',    'desc' => 'Magical themed party for the little ones.',            'image' => 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-cake-candles'],
      ];
    @endphp

    <!-- Projects Grid -->
    <div class="ev-projects-grid" id="evProjectsContainer">
      @foreach($portfolio as $proj)
        <div class="ev-project-card ev-project-item"
             data-category="{{ strtolower($proj['category'] ?? '') }}"
             data-title="{{ strtolower($proj['title'] ?? '') }}">
          <div class="ev-project-thumb">
            <img src="{{ str_starts_with($proj['image'] ?? '', 'http') ? ($proj['image'] ?? '') : asset(ltrim($proj['image'] ?? '', '/')) }}"
                 alt="{{ $proj['title'] ?? '' }}"
                 class="ev-project-img">
            <span class="ev-project-cat-badge">
              <i class="fa-solid {{ $proj['icon'] ?? 'fa-calendar' }}"></i>
              {{ $proj['category'] ?? 'Event' }}
            </span>
          </div>
          <div class="ev-project-body">
            <div>
              <h3 class="ev-project-title">{{ $proj['title'] ?? '' }}</h3>
              <p class="ev-project-desc">{{ $proj['desc'] ?? $proj['description'] ?? '' }}</p>
            </div>
            <a href="{{ $contactUrl }}" class="ev-project-arrow" aria-label="View Event">
              <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<script>
  function evFilterProjects(cat, btn) {
    document.querySelectorAll('#evPortfolioTabs .ev-filter-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    cat = cat.toLowerCase().trim();
    document.querySelectorAll('#evProjectsContainer .ev-project-item').forEach(item => {
      var itemCat = item.getAttribute('data-category');
      item.style.display = (cat === 'all' || itemCat.includes(cat) || cat.includes(itemCat)) ? 'block' : 'none';
    });
  }

  function evSearchProjects() {
    var input = document.getElementById('evSearchInput').value.toLowerCase().trim();
    document.querySelectorAll('#evProjectsContainer .ev-project-item').forEach(item => {
      var title = item.getAttribute('data-title');
      var cat   = item.getAttribute('data-category');
      item.style.display = (title.includes(input) || cat.includes(input)) ? 'block' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    var tabs = document.getElementById('evPortfolioTabs');
    if (tabs) {
      let t;
      function startTabSlide() {
        t = setInterval(() => {
          var max = tabs.scrollWidth - tabs.clientWidth;
          if (max > 0) {
            if (tabs.scrollLeft >= max - 5) tabs.scrollTo({ left: 0, behavior: 'smooth' });
            else tabs.scrollBy({ left: 140, behavior: 'smooth' });
          }
        }, 3200);
      }
      startTabSlide();
      tabs.addEventListener('touchstart', () => clearInterval(t), { passive: true });
      tabs.addEventListener('touchend', startTabSlide, { passive: true });
    }
  });
</script>

@endsection
