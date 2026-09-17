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

<!-- ===== MEET OUR TEAM ===== -->
<section class="ev-section ev-section-light">
  <div class="ev-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> Meet Our Team</div>
        <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">
          The Creative Minds<br>Behind Every Event
        </h2>
        <p class="text-muted mb-0" style="max-width: 500px; font-size: 14.5px;">
          Our team is made up of passionate event planners, designers, and coordinators who live and breathe creativity.
        </p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ $contactUrl }}" class="ev-btn ev-btn-outline ms-2">Contact Us <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>

    @php
      $team = $evData->team_members_data ?? [
        ['name' => 'Priya Sharma',  'role' => 'Founder & CEO',      'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Rahul Mehta',   'role' => 'Creative Director',  'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Anjali Verma',  'role' => 'Event Coordinator',  'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Daniel Smith',  'role' => 'Operations Manager', 'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=400&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-4 ev-mobile-slider">
      @foreach($team as $tm)
        <div class="col-12 col-md-6 col-lg-3">
          <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm text-center">
            <div style="height: 260px; overflow: hidden; background: var(--ev-badge-bg);">
              <img src="{{ str_starts_with($tm['image'] ?? '', 'http') ? ($tm['image'] ?? '') : asset(ltrim($tm['image'] ?? '', '/')) }}"
                   alt="{{ $tm['name'] ?? '' }}"
                   style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="p-3 bg-white">
              <h4 class="fw-bold fs-6 mb-1 text-dark">{{ $tm['name'] ?? '' }}</h4>
              <div class="text-muted small mb-3">{{ $tm['role'] ?? '' }}</div>
              <div class="d-flex justify-content-center gap-2">
                <a href="#" class="ev-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: var(--ev-badge-bg); color: var(--ev-primary);" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="ev-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: var(--ev-badge-bg); color: var(--ev-primary);" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#" class="ev-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: var(--ev-badge-bg); color: var(--ev-primary);" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#" class="ev-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: var(--ev-badge-bg); color: var(--ev-primary);" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="ev-testimonials-section">
  <div class="ev-container">
    <div class="text-center mb-5">
      <div class="ev-pill-badge"><span class="ev-dot"></span> Testimonials</div>
      <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">What Our Clients Say</h2>
      <p class="ev-section-subtitle mx-auto">Trusted by thousands of happy clients across all event types.</p>
    </div>

    @php
      $testimonials = $evData->testimonials_data ?? [
        ['name' => 'Priya Sharma',  'role' => 'Bride',         'comment' => 'Evently made our wedding day truly magical. Every detail was perfect — from the flowers to the lighting.', 'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200'],
        ['name' => 'Rahul Mehta',   'role' => 'CEO, TechCorp', 'comment' => 'Our annual corporate conference was flawlessly organized. The team handled everything with true professionalism.', 'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200'],
        ['name' => 'Anjali Verma',  'role' => 'Event Host',    'comment' => 'My birthday party exceeded all expectations. Creative, professional, and completely stress-free!', 'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200'],
      ];
    @endphp

    <div class="row g-4 text-start ev-mobile-slider">
      @foreach($testimonials as $t)
        <div class="col-12 col-md-4">
          <div class="ev-testimonial-card">
            <div class="ev-testimonial-stars">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="ev-testimonial-quote">"{{ $t['comment'] ?? $t['quote'] ?? '' }}"</p>
            <div class="ev-testimonial-author">
              <img src="{{ str_starts_with($t['avatar'] ?? '', 'http') ? ($t['avatar'] ?? '') : asset(ltrim($t['avatar'] ?? '', '/')) }}"
                   alt="{{ $t['name'] ?? '' }}" class="ev-testimonial-avatar">
              <div>
                <div class="ev-testimonial-name">{{ $t['name'] ?? '' }}</div>
                <div class="ev-testimonial-role">{{ $t['role'] ?? '' }}</div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== BLOG & INSIGHTS ===== -->
<section class="ev-blog-section">
  <div class="ev-container">
    <div class="d-flex align-items-end justify-content-between mb-5 flex-wrap gap-3">
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> Blog & Insights</div>
        <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">Latest Event Tips & Ideas</h2>
        <p class="ev-section-subtitle mb-0">Get inspired with expert tips, trends, and creative ideas for your next event.</p>
      </div>
    </div>

    @php
      $blogs = [
        ['id'=>1,'badge'=>'Planning Tips','date'=>'Sep 12, 2024','title'=>'10 Must-Know Tips for Planning a Flawless Wedding','desc'=>'A comprehensive guide to planning your dream wedding without the stress.','image'=> asset('assets/website_builder/Templates/Evently/event_grand_wedding.png')],
        ['id'=>2,'badge'=>'Corporate Events','date'=>'Aug 28, 2024','title'=>'How to Make Your Corporate Conference Unforgettable','desc'=>'Key strategies to engage attendees and leave a lasting impression.','image'=> asset('assets/website_builder/Templates/Evently/event_business_summit.png')],
        ['id'=>3,'badge'=>'Event Trends','date'=>'Aug 15, 2024','title'=>'Top Event Decoration Trends for 2025','desc'=>'The hottest event design trends shaping celebrations this year.','image'=> asset('assets/website_builder/Templates/Evently/event_music_fest.png')],
      ];
    @endphp

    <div class="row g-4">
      @foreach($blogs as $b)
        <div class="col-12 col-md-4">
          <div class="ev-blog-card">
            <div class="ev-blog-img-wrap">
              <img src="{{ $b['image'] }}" alt="{{ $b['title'] }}" class="ev-blog-img"
                   onerror="this.src='{{ asset('assets/website_builder/Templates/Evently/event_corporate_gala.png') }}';">
              <span class="ev-blog-badge">{{ $b['badge'] }}</span>
            </div>
            <div class="ev-blog-body">
              <div class="ev-blog-date"><i class="fa-regular fa-calendar me-1"></i> {{ $b['date'] }}</div>
              <div class="ev-blog-title">{{ $b['title'] }}</div>
              <div class="ev-blog-desc d-none d-md-block">{{ $b['desc'] }}</div>
              <a href="{{ $contactUrl }}" class="ev-blog-link">Read Article <i class="fa-solid fa-arrow-right"></i></a>
            </div>
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
