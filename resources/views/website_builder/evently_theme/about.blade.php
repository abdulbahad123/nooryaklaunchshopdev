@extends('website_builder.evently_theme.layout')

@section('title', 'About Us - ' . ($interior->site_title ?? 'Evently'))

@section('content')

@php
  $evData = $interior ?? $agency ?? null;
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;

  $homeUrl      = $subdomainParam ? route('website-builder.subdomain.site',      ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.about');
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.portfolio');
@endphp

<!-- ===== ABOUT HERO BANNER ===== -->
<section class="ev-page-hero">
  <div class="ev-container">
    <div class="ev-page-hero-content">
      <div class="ev-page-hero-badge">
        <i class="fa-solid fa-gem"></i> {{ $evData->about_badge ?? 'About Us' }}
      </div>
      <h1 class="ev-page-hero-title">
        {!! nl2br(e($evData->about_hero_title ?? "We Design More Than Events,\nWe Create Memories")) !!}
      </h1>
      <p class="ev-page-hero-sub">
        {{ $evData->about_hero_subtitle ?? 'We help individuals and businesses transform their ideas into unforgettable event experiences through creativity, passion, and flawless execution.' }}
      </p>
      <div class="ev-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <span class="separator"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
        <span class="current">About Us</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== OUR STORY & MISSION/VISION/VALUES ===== -->
<section class="ev-story-section">
  <div class="ev-container">
    <div class="row g-5 align-items-start">

      <!-- Left Story Content -->
      <div class="col-lg-5">
        <div class="ev-pill-badge"><span class="ev-dot"></span> {{ $evData->story_badge ?? 'Our Story' }}</div>
        <h2 class="ev-section-title mb-4" style="font-family: var(--ev-font-heading);">
          {!! nl2br(e($evData->story_title ?? 'A Journey Built On Passion & Purpose')) !!}
        </h2>
        <p class="text-muted mb-3" style="line-height: 1.7; font-size: 14.5px;">
          {{ $evData->story_text ?? 'Evently was founded with a simple idea — to make exceptional event management accessible to everyone, from intimate birthday parties to grand corporate galas.' }}
        </p>

        <!-- Founder Bio -->
        <div class="d-flex align-items-center gap-3 p-3 rounded-4 border" style="background: var(--ev-bg-light);">
          <img src="{{ $evData->founder_image ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=150&auto=format&fit=crop' }}"
               class="rounded-circle object-fit-cover" style="width: 56px; height: 56px;" alt="{{ $evData->founder_name ?? 'Priya Sharma' }}">
          <div>
            <div class="fw-bold fs-6 text-dark">{{ $evData->founder_name ?? 'Priya Sharma' }}</div>
            <div class="text-muted small">{{ $evData->founder_role ?? 'Founder & CEO' }}</div>
          </div>
          <div class="ms-auto pe-2">
            <span style="font-family: var(--ev-font-cursive); font-size: 24px; color: var(--ev-primary);">{{ $evData->founder_name ?? 'Priya Sharma' }}</span>
          </div>
        </div>
      </div>

      <!-- Right: Mission / Vision / Values -->
      <div class="col-lg-7">
        <div class="row g-2 g-md-3 ev-mission-vision-row">
          <div class="col-6 col-md-4">
            <div class="card h-100 border p-2.5 p-sm-4 rounded-4 shadow-sm bg-white" style="padding: 14px 12px !important;">
              <div class="ev-stat-circle mb-2 mb-sm-3" style="width: 40px; height: 40px; font-size: 16px;">
                <i class="fa-solid fa-bullseye"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-1 mb-sm-2 text-dark" style="font-size: 14px !important;">{{ $evData->mission_title ?? 'Our Mission' }}</h3>
              <p class="text-muted small mb-0" style="line-height: 1.45; font-size: 11.5px; word-break: normal; hyphens: none;">
                {{ $evData->mission_text ?? 'To create meaningful, beautiful, and unforgettable event experiences that bring people together.' }}
              </p>
            </div>
          </div>
          <div class="col-6 col-md-4">
            <div class="card h-100 border p-2.5 p-sm-4 rounded-4 shadow-sm bg-white" style="padding: 14px 12px !important;">
              <div class="ev-stat-circle mb-2 mb-sm-3" style="width: 40px; height: 40px; font-size: 16px;">
                <i class="fa-regular fa-eye"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-1 mb-sm-2 text-dark" style="font-size: 14px !important;">{{ $evData->vision_title ?? 'Our Vision' }}</h3>
              <p class="text-muted small mb-0" style="line-height: 1.45; font-size: 11.5px; word-break: normal; hyphens: none;">
                {{ $evData->vision_text ?? "To be the world's most trusted event management brand known for innovation and people-first planning." }}
              </p>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="card h-100 border p-3 p-sm-4 rounded-4 shadow-sm bg-white">
              <div class="ev-stat-circle mb-2 mb-sm-3" style="width: 40px; height: 40px; font-size: 16px;">
                <i class="fa-solid fa-gem"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-1 mb-sm-2 text-dark" style="font-size: 14px !important;">{{ $evData->values_title ?? 'Our Values' }}</h3>
              @php
                $valText = $evData->values_text ?? 'Client Happiness First, Creativity & Innovation, Flawless Execution, Integrity & Transparency, Quality in Every Detail';
                $valItems = array_map('trim', explode(',', $valText));
              @endphp
              <ul class="list-unstyled text-muted small mb-0" style="line-height: 1.5; font-size: 11.5px;">
                @foreach($valItems as $vi)
                  <li class="mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> {{ $vi }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Full-Width 4-Stats Bar -->
    <div class="ev-stats-bar">
      <div class="row g-4 text-center">
        @php
          $aboutStats = $evData->stats_data ?? [
            ['number' => '500+', 'label' => 'Events Managed',     'icon' => 'fa-calendar-check'],
            ['number' => '50K+', 'label' => 'Happy Attendees',    'icon' => 'fa-users'],
            ['number' => '98%',  'label' => 'Client Satisfaction','icon' => 'fa-star'],
            ['number' => '15+',  'label' => 'Years of Experience','icon' => 'fa-trophy'],
          ];
        @endphp
        @foreach($aboutStats as $st)
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center justify-content-center gap-3">
              <div class="ev-stat-circle"><i class="fa-solid {{ $st['icon'] ?? 'fa-star' }}"></i></div>
              <div class="text-start">
                <div class="fw-bold fs-4 text-dark mb-0 ev-counter-num" data-target="{{ $st['number'] ?? '' }}">{{ $st['number'] ?? '' }}</div>
                <div class="text-muted small">{{ $st['label'] ?? '' }}</div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- ===== MEET OUR TEAM ===== -->
<section class="ev-section ev-section-light">
  <div class="ev-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> {{ $evData->team_badge ?? 'Meet Our Team' }}</div>
        <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">
          {!! nl2br(e($evData->team_title ?? "The Creative Minds\nBehind Every Event")) !!}
        </h2>
        <p class="text-muted mb-0" style="max-width: 500px; font-size: 14.5px;">
          {{ $evData->team_subtitle ?? 'Our team is made up of passionate event planners, designers, and coordinators who live and breathe creativity.' }}
        </p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <button type="button" id="teamPrevBtn" class="ev-arrow-btn" aria-label="Previous"><i class="fa-solid fa-arrow-left"></i></button>
        <button type="button" id="teamNextBtn" class="ev-arrow-btn" aria-label="Next"><i class="fa-solid fa-arrow-right"></i></button>
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

    <div class="row g-4 ev-mobile-slider" id="evTeamSlider">
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
      <div class="ev-pill-badge"><span class="ev-dot"></span> {{ $evData->testimonials_badge ?? 'Testimonials' }}</div>
      <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">{!! nl2br(e($evData->testimonials_title ?? 'What Our Clients Say')) !!}</h2>
      <p class="ev-section-subtitle mx-auto">{{ $evData->testimonials_subtitle ?? 'Trusted by thousands of happy clients across all event types.' }}</p>
    </div>

    @php
      $testimonials = $evData->testimonials_data ?? [
        ['name' => 'Priya Sharma',  'role' => 'Bride',         'comment' => 'Evently made our wedding day truly magical. Every detail was perfect — from the flowers to the lighting.', 'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200'],
        ['name' => 'Rahul Mehta',   'role' => 'CEO, TechCorp', 'comment' => 'Our annual corporate conference was flawlessly organized. The team handled everything with true professionalism.', 'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200'],
        ['name' => 'Anjali Verma',  'role' => 'Event Host',    'comment' => 'My birthday party exceeded all expectations. Creative, professional, and completely stress-free!', 'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200'],
        ['name' => 'David Miller',  'role' => 'Brand Manager', 'comment' => 'The trade show setup was spectacular. Our booth got maximum visibility and the logistics were perfect.', 'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200'],
        ['name' => 'Sophia Chen',   'role' => 'Hotel Director','comment' => 'We host all our gala dinners with Evently. Their creativity and attention to detail is unmatched.', 'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200'],
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var teamSlider = document.getElementById('evTeamSlider');
  document.getElementById('teamPrevBtn')?.addEventListener('click', function() {
    teamSlider?.scrollBy({ left: -(teamSlider.clientWidth), behavior: 'smooth' });
  });
  document.getElementById('teamNextBtn')?.addEventListener('click', function() {
    teamSlider?.scrollBy({ left: teamSlider.clientWidth, behavior: 'smooth' });
  });

  // Auto-slide team
  if (teamSlider) {
    let t;
    function startTeam() {
      t = setInterval(() => {
        if (window.innerWidth < 992) {
          var max = teamSlider.scrollWidth - teamSlider.clientWidth;
          if (teamSlider.scrollLeft >= max - 10) teamSlider.scrollTo({ left: 0, behavior: 'smooth' });
          else teamSlider.scrollBy({ left: teamSlider.clientWidth, behavior: 'smooth' });
        }
      }, 3500);
    }
    startTeam();
    teamSlider.addEventListener('touchstart', () => clearInterval(t), { passive: true });
    teamSlider.addEventListener('touchend', startTeam, { passive: true });
  }
});
</script>
@endsection

@endsection
