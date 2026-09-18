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
        <i class="fa-solid fa-calendar-check"></i> {{ $evData->portfolio_badge ?? 'Our Events' }}
      </div>
      <h1 class="ev-page-hero-title">
        {!! nl2br(e($evData->portfolio_title ?? "Events We've\nBrought to Life")) !!}
      </h1>
      <p class="ev-page-hero-sub">
        {{ $evData->portfolio_subtitle ?? 'Browse our portfolio of unforgettable events — from intimate weddings to grand corporate galas.' }}
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

    @php
      $portfolio = $evData->portfolio_data ?? [
        ['title' => 'Grand Wedding Gala',      'category' => 'Wedding',     'desc' => 'A dreamy outdoor wedding with 500 guests.',          'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-heart'],
        ['title' => 'TechCorp Annual Summit',  'category' => 'Corporate',   'desc' => 'Full-scale corporate conference with live streaming.', 'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-briefcase'],
        ['title' => 'Rooftop Birthday Bash',   'category' => 'Birthday',    'desc' => 'Exclusive rooftop celebration with live music.',       'image' => 'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-cake-candles'],
      ];

      $dynamicCategories = [];
      foreach ($portfolio as $item) {
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

    <!-- Filter Bar -->
    <div class="ev-filter-bar mb-5">
      <div class="ev-filter-tabs" id="evPortfolioTabs" style="overflow-x: auto; flex-wrap: nowrap; scrollbar-width: none;">
        <button type="button" class="ev-filter-tab active flex-shrink-0" onclick="evFilterProjects('all', this)">All Events</button>
        @foreach($dynamicCategories as $slug => $catName)
          <button type="button" class="ev-filter-tab flex-shrink-0" onclick="evFilterProjects('{{ $slug }}', this)">{{ $catName }}</button>
        @endforeach
      </div>

      <div class="ev-search-box flex-shrink-0 mt-2 mt-md-0">
        <i class="fa-solid fa-magnifying-glass ev-search-icon"></i>
        <input type="text" class="ev-search-input" placeholder="Search events..." id="evSearchInput" onkeyup="evSearchProjects()">
      </div>
    </div>

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
  function slugifyText(str) {
    return (str || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
  }

  function evFilterProjects(cat, btn) {
    document.querySelectorAll('#evPortfolioTabs .ev-filter-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    var targetSlug = slugifyText(cat);
    document.querySelectorAll('#evProjectsContainer .ev-project-item').forEach(item => {
      var itemCat = item.getAttribute('data-category') || '';
      var itemSlug = slugifyText(itemCat);
      var match = (targetSlug === 'all' || itemSlug.includes(targetSlug) || targetSlug.includes(itemSlug));
      item.style.display = match ? 'block' : 'none';
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
