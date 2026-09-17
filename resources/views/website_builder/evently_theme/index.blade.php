@extends('website_builder.evently_theme.layout')

@section('title', ($interior->site_title ?? 'Evently') . ' - Events Beyond Expectations')

@section('content')

@php
  $evData = $interior ?? $agency ?? null;
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;

  $homeUrl      = $subdomainParam ? route('website-builder.subdomain.site',      ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.about');
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.portfolio');

  $defaultHero = asset('assets/website_builder/Templates/Interior_agency/homepage_hero.png');
  $heroImg = $evData->hero_image ?? '';
  $isDefault = empty($heroImg) || str_contains($heroImg, 'unsplash.com') || str_contains($heroImg, 'agency_template') || str_contains($heroImg, 'herobanner_right');
  $heroSrc = !$isDefault ? (str_starts_with($heroImg, 'http') ? $heroImg : asset(ltrim($heroImg, '/'))) : $defaultHero;
@endphp

<!-- ===== HERO SECTION ===== -->
<section class="ev-hero">
  <div class="ev-container">
    <div class="ev-hero-grid">

      <!-- LEFT: Content -->
      <div>
        <div class="ev-hero-badge">
          <span class="ev-dot"></span>
          Make Every Moment Extraordinary
        </div>

        <h1 class="ev-hero-title">
          Events That Bring<br>
          <span class="ev-purple-line">People Together</span>
        </h1>

        <p class="ev-hero-subtitle">
          {{ $evData->hero_subtitle ?? 'From intimate gatherings to grand celebrations, we create unforgettable experiences tailored to your vision.' }}
        </p>

        <div class="ev-hero-actions">
          <a href="{{ $evData->primary_btn_url ?? $contactUrl }}" class="ev-btn ev-btn-primary">
            {{ $evData->primary_btn_text ?? 'Plan Your Event' }}
            <i class="fa-solid fa-arrow-right"></i>
          </a>
          <a href="{{ $portfolioUrl }}" class="ev-btn-video">
            <span class="ev-play-circle">
              <i class="fa-solid fa-play" style="font-size: 10px; margin-left: 2px;"></i>
            </span>
            Watch Our Story
          </a>
        </div>

        <!-- Trust Bar -->
        <div class="ev-hero-trust">
          <div class="ev-trust-avatars">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" alt="Client">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100&auto=format&fit=crop" alt="Client">
            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=100&auto=format&fit=crop" alt="Client">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=100&auto=format&fit=crop" alt="Client">
          </div>
          <div class="ev-trust-text">
            Trusted by <strong>10K+</strong> Happy Clients
          </div>
        </div>
      </div>

      <!-- RIGHT: Hero Image with Floating Cards -->
      <div class="ev-hero-img-col">

        <!-- Floating Top-Right Badge -->
        <div class="ev-float-badge-top">
          <div class="ev-float-icon">
            <i class="fa-solid fa-heart"></i>
          </div>
          <span>Memorable<br>Events</span>
        </div>

        <!-- Main Round/Oval Image -->
        <div style="position: relative; display: inline-block; width: 100%;">
          <img src="{{ $heroSrc }}"
               onerror="this.src='{{ $defaultHero }}';"
               alt="{{ $evData->site_title ?? 'Evently' }}"
               class="ev-hero-main-img">

          <!-- Cursive Overlay -->
          <div class="ev-img-cursive-overlay">
            <div class="ev-img-cursive-text">Events<br>Create<br>Stories</div>
          </div>

          <!-- Bottom-Left Info Float -->
          <div class="ev-float-card-bottom">
            <div class="ev-float-card-icon">
              <i class="fa-regular fa-calendar-check"></i>
            </div>
            <div class="ev-float-card-text">
              Celebrations<br>Conferences<br>Weddings<br>& More
            </div>
          </div>

          <!-- Right Float Card -->
          <div class="ev-float-ideas">
            <div class="ev-float-ideas-icon">
              <i class="fa-solid fa-lightbulb"></i>
            </div>
            <div class="ev-float-ideas-text">Turning Ideas Into Extraordinary Experiences</div>
            <a href="{{ $contactUrl }}" class="ev-float-ideas-arrow">
              <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FEATURES + STATS BAR ===== -->
<section class="ev-features-bar">
  <div class="ev-container">
    <div class="ev-features-grid">
      <div class="ev-feature-item">
        <div class="ev-feature-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
        <div class="ev-feature-title">Creative<br>Planning</div>
      </div>
      <div class="ev-feature-item">
        <div class="ev-feature-icon"><i class="fa-solid fa-users"></i></div>
        <div class="ev-feature-title">Dedicated<br>Support</div>
      </div>
      <div class="ev-feature-item">
        <div class="ev-feature-icon"><i class="fa-solid fa-box-open"></i></div>
        <div class="ev-feature-title">Customizable<br>Packages</div>
      </div>
      <div class="ev-feature-item">
        <div class="ev-feature-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div class="ev-feature-title">Seamless<br>Execution</div>
      </div>

      <!-- Dark Stats Card -->
      <div class="ev-stats-dark-card">
        <div class="ev-stats-dark-label">OUR IMPACT</div>
        <div class="ev-stats-dark-title">In Numbers</div>
        @php
          $stats = $evData->stats_data ?? [
            ['number' => '500+', 'label' => 'Events Managed',    'icon' => 'fa-calendar-check'],
            ['number' => '50K+', 'label' => 'Happy Attendees',   'icon' => 'fa-users'],
            ['number' => '98%',  'label' => 'Client Satisfaction','icon' => 'fa-star'],
            ['number' => '15+',  'label' => 'Years of Experience','icon' => 'fa-trophy'],
          ];
        @endphp
        <div class="ev-stats-dark-grid">
          @foreach($stats as $st)
            <div class="ev-stat-item-dark">
              <div class="ev-stat-icon-dark">
                <i class="fa-solid {{ $st['icon'] ?? 'fa-star' }}"></i>
              </div>
              <div class="ev-stat-num-dark ev-counter-num" data-target="{{ $st['number'] ?? '' }}">
                {{ $st['number'] ?? '' }}
              </div>
              <div class="ev-stat-lbl-dark">{{ $st['label'] ?? '' }}</div>
            </div>
          @endforeach
        </div>
        <a href="{{ $portfolioUrl }}" class="ev-stats-view-more">View More <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- ===== EVENT CATEGORIES ===== -->
<section class="ev-categories-section" id="events">
  <div class="ev-container">
    <div class="ev-categories-top">
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> Event Categories</div>
        <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">
          Explore Events<br>For <span style="color: var(--ev-primary); font-style: italic;">Every Occasion</span>
        </h2>
      </div>
      <div>
        <p class="ev-section-subtitle" style="margin-bottom: 16px;">
          Whatever the occasion, we have the expertise to make it extraordinary.
        </p>
        <div class="ev-nav-arrows">
          <button class="ev-arrow-btn" id="catPrev" type="button"><i class="fa-solid fa-arrow-left"></i></button>
          <button class="ev-arrow-btn" id="catNext" type="button"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
      </div>
    </div>

    @php
      $categories = $evData->portfolio_data ?? [
        ['title' => 'Corporate Events',        'desc' => 'Conferences, seminars, product launches and more.', 'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-building'],
        ['title' => 'Weddings & Private Events','desc' => 'Make your special day truly unforgettable.',         'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-heart'],
        ['title' => 'Social Gatherings',        'desc' => 'Birthdays, anniversaries and celebrations.',         'image' => 'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-champagne-glasses'],
        ['title' => 'Exhibitions & Trade Shows', 'desc' => 'Showcase your brand to the world.',                 'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=800&auto=format&fit=crop', 'icon' => 'fa-store'],
      ];
    @endphp

    <div class="row g-3 g-md-4" id="catSlider">
      @foreach($categories as $cat)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ev-cat-card">
            <img src="{{ str_starts_with($cat['image'] ?? '', 'http') ? ($cat['image'] ?? '') : asset(ltrim($cat['image'] ?? '', '/')) }}"
                 alt="{{ $cat['title'] ?? '' }}"
                 class="ev-cat-img">
            <div class="ev-cat-overlay"></div>
            <div class="ev-cat-content">
              <div class="ev-cat-title">{{ $cat['title'] ?? '' }}</div>
              <div class="ev-cat-desc">{{ $cat['desc'] ?? $cat['description'] ?? '' }}</div>
              <a href="{{ $portfolioUrl }}" class="ev-cat-link">
                <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== OUR SERVICES ===== -->
<section id="services" class="ev-services-section">
  <div class="ev-container">
    <div class="text-center mb-5">
      <div class="ev-pill-badge"><span class="ev-dot"></span> What We Offer</div>
      <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">Our Event Planning Services</h2>
      <p class="ev-section-subtitle mx-auto">We provide a complete range of event management solutions to make every celebration extraordinary.</p>
    </div>

    @php
      $services = $evData->services_data ?? [
        ['title' => 'Wedding Planning',   'desc' => 'Crafting your dream wedding with every detail perfected.', 'image' => asset('assets/website_builder/Templates/Interior_agency/service_residential.png'), 'icon' => 'fa-heart'],
        ['title' => 'Corporate Events',   'desc' => 'Professional conferences, launches & team-building events.', 'image' => asset('assets/website_builder/Templates/Interior_agency/service_commercial.png'), 'icon' => 'fa-briefcase'],
        ['title' => 'Birthday Parties',   'desc' => 'Unforgettable celebrations for every milestone.',          'image' => asset('assets/website_builder/Templates/Interior_agency/service_planning.png'), 'icon' => 'fa-cake-candles'],
        ['title' => 'Conference & Expo',  'desc' => 'Large-scale exhibitions and conference management.',       'image' => asset('assets/website_builder/Templates/Interior_agency/service_styling.png'), 'icon' => 'fa-microphone-lines'],
      ];
    @endphp

    <div class="row g-4 ev-mobile-slider">
      @foreach($services as $srv)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ev-service-card">
            <div class="ev-service-icon-wrap">
              <i class="fa-solid {{ $srv['icon'] ?? 'fa-star' }}"></i>
            </div>
            <div class="ev-service-title">{{ $srv['title'] ?? '' }}</div>
            <p class="ev-service-desc">{{ $srv['desc'] ?? '' }}</p>
            @if(!empty($srv['image']))
              <img src="{{ str_starts_with($srv['image'] ?? '', 'http') ? ($srv['image'] ?? '') : asset(ltrim($srv['image'] ?? '', '/')) }}"
                   alt="{{ $srv['title'] ?? '' }}"
                   class="ev-service-img">
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== WHY CHOOSE US ===== -->
<section class="ev-why-section">
  <div class="ev-container">
    <div class="ev-why-grid">
      <!-- Left: Image -->
      <div class="ev-why-img-col">
        <img src="{{ $heroSrc }}"
             onerror="this.src='{{ $defaultHero }}';"
             alt="{{ $evData->site_title ?? 'Why Evently' }}"
             class="ev-why-main-img">
        <div class="ev-why-float-stat">
          <div class="ev-why-stat-num ev-counter-num" data-target="500+">500+</div>
          <div class="ev-why-stat-lbl">Successful Events</div>
        </div>
      </div>

      <!-- Right: Features -->
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> Why Choose Us</div>
        <h2 class="ev-section-title mb-4" style="font-family: var(--ev-font-heading);">
          We Make Every<br>Event <span style="color: var(--ev-primary); font-style: italic;">Extraordinary</span>
        </h2>

        <div class="ev-why-feature">
          <div class="ev-why-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
          <div>
            <div class="ev-why-title">Creative & Innovative Design</div>
            <p class="ev-why-desc">We bring fresh ideas and creative concepts to make every event unique and visually stunning.</p>
          </div>
        </div>

        <div class="ev-why-feature">
          <div class="ev-why-icon"><i class="fa-solid fa-headset"></i></div>
          <div>
            <div class="ev-why-title">Dedicated 24/7 Support</div>
            <p class="ev-why-desc">Our team is available around the clock to ensure everything runs flawlessly from planning to execution.</p>
          </div>
        </div>

        <div class="ev-why-feature">
          <div class="ev-why-icon"><i class="fa-solid fa-box-open"></i></div>
          <div>
            <div class="ev-why-title">Customizable Packages</div>
            <p class="ev-why-desc">Flexible packages tailored to your budget and vision — no event is too big or too small.</p>
          </div>
        </div>

        <div class="ev-why-feature">
          <div class="ev-why-icon"><i class="fa-solid fa-circle-check"></i></div>
          <div>
            <div class="ev-why-title">Seamless On-Day Execution</div>
            <p class="ev-why-desc">Our experienced coordinators manage every detail so you can relax and enjoy your special day.</p>
          </div>
        </div>

        <a href="{{ $aboutUrl }}" class="ev-btn ev-btn-primary mt-3">
          Learn More About Us <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section id="testimonials" class="ev-testimonials-section">
  <div class="ev-container">
    <div class="text-center mb-5">
      <div class="ev-pill-badge"><span class="ev-dot"></span> Testimonials</div>
      <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">What Our Clients Say</h2>
      <p class="ev-section-subtitle mx-auto">We're proud to have made thousands of moments unforgettable.</p>
    </div>

    @php
      $testimonials = $evData->testimonials_data ?? [
        ['name' => 'Priya Sharma',   'role' => 'Bride',          'comment' => 'Evently made our wedding day truly magical. Every detail was perfect — from the flowers to the lighting. We couldn\'t have asked for more!', 'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200'],
        ['name' => 'Rahul Mehta',   'role' => 'CEO, TechCorp',  'comment' => 'Our annual corporate conference was flawlessly organized. The team handled everything professionally and our attendees were impressed.',    'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200'],
        ['name' => 'Anjali Verma',  'role' => 'Event Host',     'comment' => 'I hired Evently for my 30th birthday party and it exceeded all expectations. Creative, professional, and stress-free!',                     'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200'],
        ['name' => 'David Miller',  'role' => 'Brand Manager',  'comment' => 'The trade show setup was spectacular. Our booth got maximum visibility and the logistics were handled seamlessly.',                          'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200'],
        ['name' => 'Sophia Chen',   'role' => 'Hotel Director', 'comment' => 'We host all our gala dinners with Evently. Their attention to detail and creativity is unmatched in the industry.',                          'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200'],
      ];
    @endphp

    <div class="row g-4 text-start ev-mobile-slider">
      @foreach($testimonials as $t)
        <div class="col-12 col-md-4">
          <div class="ev-testimonial-card">
            <div class="ev-testimonial-stars">
              <i class="fa-solid fa-quote-left me-2 text-muted opacity-50" style="font-size: 16px;"></i>
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="ev-testimonial-quote">"{{ $t['comment'] ?? $t['quote'] ?? '' }}"</p>
            <div class="ev-testimonial-author">
              <img src="{{ $t['avatar'] ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200' }}"
                   alt="{{ $t['name'] ?? '' }}"
                   class="ev-testimonial-avatar">
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
<section id="blog" class="ev-blog-section">
  <div class="ev-container">
    <div class="d-flex align-items-end justify-content-between mb-5 flex-wrap gap-3">
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> Blog & Insights</div>
        <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">Latest Event Tips & Ideas</h2>
        <p class="ev-section-subtitle mb-0">Get inspired with expert tips, trends, and creative ideas for your next event.</p>
      </div>
      <div class="d-none d-md-flex gap-2">
        <button class="ev-arrow-btn"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="ev-arrow-btn"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $blogs = [
        ['id'=>1,'badge'=>'Planning Tips','date'=>'Sep 12, 2024','title'=>'10 Must-Know Tips for Planning a Flawless Wedding','desc'=>'A comprehensive guide to planning your dream wedding without the stress.','image'=>'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=600&auto=format&fit=crop'],
        ['id'=>2,'badge'=>'Corporate Events','date'=>'Aug 28, 2024','title'=>'How to Make Your Corporate Conference Unforgettable','desc'=>'Key strategies to engage attendees and leave a lasting impression.','image'=>'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=600&auto=format&fit=crop'],
        ['id'=>3,'badge'=>'Event Trends','date'=>'Aug 15, 2024','title'=>'Top Event Decoration Trends for 2025','desc'=>'The hottest event design trends shaping celebrations this year.','image'=>'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?q=80&w=600&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-4">
      @foreach($blogs as $b)
        <div class="col-12 col-md-4">
          <div class="ev-blog-card">
            <div class="ev-blog-img-wrap">
              <img src="{{ $b['image'] }}" alt="{{ $b['title'] }}" class="ev-blog-img">
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

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Category slider arrows
  var catSlider = document.getElementById('catSlider');
  document.getElementById('catPrev')?.addEventListener('click', function() {
    catSlider.scrollBy({ left: -(catSlider.clientWidth / 4 + 16), behavior: 'smooth' });
  });
  document.getElementById('catNext')?.addEventListener('click', function() {
    catSlider.scrollBy({ left: catSlider.clientWidth / 4 + 16, behavior: 'smooth' });
  });
});
</script>
@endsection
