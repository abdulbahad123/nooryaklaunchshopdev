@extends('website_builder.interior_template.layout')

@section('title', 'About Us - InterioCRAFT Architectural & Interior Design Studio')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.portfolio');
@endphp

<!-- ===== ABOUT HERO SECTION ===== -->
<section class="ic-hero">
  <div class="ic-container">
    <div class="ic-hero-grid">
      <div>
        <span class="ic-pill-badge">{{ $interior->about_badge ?? 'ABOUT US' }} ——</span>
        <h1 class="ic-heading ic-hero-title">
          {!! nl2br(e($interior->about_hero_title ?? "We Design More Than Spaces,\nWe Design Better Lives")) !!}
        </h1>
        <p class="ic-hero-subtitle">
          {{ $interior->about_hero_subtitle ?? 'We help individuals and businesses transform their spaces through thoughtful design, creativity, and a deep understanding of modern living.' }}
        </p>

        <div class="ic-hero-actions d-flex align-items-center gap-3 flex-wrap mb-4">
          <a href="{{ $interior->about_primary_btn_url ?? $portfolioUrl }}" class="ic-btn ic-btn-dark">
            {{ $interior->about_primary_btn_text ?? 'Our Portfolio' }} <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="{{ $interior->about_secondary_btn_url ?? $contactUrl }}" class="ic-btn ic-btn-outline" style="border-radius: var(--ic-radius-pill); border: 1.5px solid var(--ic-border); color: var(--ic-text-dark); padding: 12px 26px; font-weight: 700; text-decoration: none;">
            {{ $interior->about_secondary_btn_text ?? 'Contact Us' }}
          </a>
        </div>

        <!-- Social Proof / Avatars Row -->
        <div class="d-flex align-items-center gap-3 pt-3 border-top">
          <div class="d-flex" style="margin-left: 10px;">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white shadow-sm" style="width: 40px; height: 40px; margin-left: -12px; object-fit: cover;" alt="Client">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white shadow-sm" style="width: 40px; height: 40px; margin-left: -12px; object-fit: cover;" alt="Client">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white shadow-sm" style="width: 40px; height: 40px; margin-left: -12px; object-fit: cover;" alt="Client">
            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white shadow-sm" style="width: 40px; height: 40px; margin-left: -12px; object-fit: cover;" alt="Client">
          </div>
          <div>
            <div style="font-weight: 800; font-size: 16px; color: var(--ic-text-dark); line-height: 1.1;">250+</div>
            <div style="font-size: 12px; color: var(--ic-text-muted);">Happy Homeowners</div>
          </div>
          <div class="border-start ps-3 ms-2">
            <div style="font-size: 12.5px; color: var(--ic-text-muted); font-weight: 600; max-width: 180px; line-height: 1.3;">
              Trusted by Families,<br>Businesses & Builders
            </div>
          </div>
        </div>
      </div>

      <!-- Right Showcase Photo & Floating Badge -->
      <div class="position-relative">
        <div class="rounded-4 overflow-hidden shadow-lg border" style="background: #EAE6DF; height: 480px; position: relative;">
          @php
            $defaultAboutHero = asset('assets/website_builder/Templates/Interior_agency/aboutus_hero.png');
            $aboutImg = $interior->about_hero_image ?? ($interior->hero_image ?? '');
            $isOldAgencyOrUnsplash = empty($aboutImg) 
              || str_contains($aboutImg, 'unsplash.com') 
              || str_contains($aboutImg, 'agency_template') 
              || str_contains($aboutImg, 'herobanner_right')
              || str_contains($aboutImg, 'photo-1618221195710');
            $aboutHeroSrc = !$isOldAgencyOrUnsplash ? (str_starts_with($aboutImg, 'http') ? $aboutImg : asset(ltrim($aboutImg, '/'))) : $defaultAboutHero;
          @endphp
          <img src="{{ $aboutHeroSrc }}"
               onerror="this.src='{{ $defaultAboutHero }}';"
               alt="{{ $interior->site_title ?? 'About InterioCRAFT' }}" style="width: 100%; height: 100%; object-fit: cover;">

          <!-- Bottom Floating Badge (8+ Years Experience) -->
          <div class="position-absolute bottom-0 end-0 m-4 p-3 bg-white rounded-4 shadow-lg border d-flex align-items-center gap-3" style="max-width: 320px; z-index: 5;">
            <div class="ic-stat-circle" style="width: 48px; height: 48px; font-size: 20px; background-color: #e8f3ec; color: var(--ic-primary, #2A4836); display: flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0;">
              <i class="fa-solid fa-couch"></i>
            </div>
            <div style="flex-grow: 1;">
              <div style="font-size: 22px; font-weight: 800; color: #111827; line-height: 1.1;">8+</div>
              <div style="font-size: 12px; font-weight: 600; color: #4B5563; line-height: 1.2;">Years of Experience</div>
            </div>
            <div class="border-start ps-3 text-center ms-auto" style="border-color: #e5e7eb !important;">
              <span class="ic-cursive" style="font-size: 18px; color: #111827; line-height: 1.1; display: block; font-weight: 600;">Redefining<br>Interiors</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== OUR STORY & MISSION/VISION/VALUES ===== -->
<section class="py-5" style="background: #ffffff;">
  <div class="ic-container">
    <div class="row g-5 align-items-start">
      <!-- Left Story Content -->
      <div class="col-lg-5">
        <span class="ic-pill-badge">{{ $interior->story_badge ?? 'OUR STORY' }} ——</span>
        <h2 class="ic-heading display-6 mb-4">{!! nl2br(e($interior->story_title ?? 'A Journey Built On Passion & Purpose')) !!}</h2>
        <div class="text-muted mb-4" style="line-height: 1.7; font-size: 14.5px;">
          {!! nl2br(e($interior->story_text ?? 'InterioCRAFT was founded in 2018 with a simple idea — to make exceptional interior design accessible to everyone.')) !!}
        </div>

        <!-- Founder Bio Box -->
        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-4 border">
          <img src="{{ $interior->founder_image ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=150&auto=format&fit=crop' }}" class="rounded-circle object-fit-cover" style="width: 56px; height: 56px;" alt="{{ $interior->founder_name ?? 'Priya Sharma' }}">
          <div>
            <div class="fw-bold fs-6 text-dark">{{ $interior->founder_name ?? 'Priya Sharma' }}</div>
            <div class="text-muted small">{{ $interior->founder_role ?? 'Founder & CEO' }}</div>
          </div>
          <div class="ms-auto pe-2">
            <span class="ic-cursive" style="font-size: 26px; color: var(--ic-secondary);">{{ $interior->founder_name ?? 'Priya Sharma' }}</span>
          </div>
        </div>
      </div>

      <!-- Right 3 Vertical Cards (Mission, Vision, Values) -->
      <div class="col-lg-7">
        <div class="row g-3">
          <!-- Card 1: Our Mission -->
          <div class="col-md-4">
            <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
              <div class="ic-stat-circle mb-3">
                <i class="fa-solid fa-bullseye"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-2 text-dark">{{ $interior->mission_title ?? 'Our Mission' }}</h3>
              <p class="text-muted small mb-0" style="line-height: 1.6;">
                {{ $interior->mission_text ?? 'To create functional, beautiful, and meaningful spaces that enhance everyday living.' }}
              </p>
            </div>
          </div>

          <!-- Card 2: Our Vision -->
          <div class="col-md-4">
            <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
              <div class="ic-stat-circle mb-3">
                <i class="fa-regular fa-eye"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-2 text-dark">{{ $interior->vision_title ?? 'Our Vision' }}</h3>
              <p class="text-muted small mb-0" style="line-height: 1.6;">
                {{ $interior->vision_text ?? 'To be a leading global interior design brand, known for innovation, sustainability, and people-centric design.' }}
              </p>
            </div>
          </div>

          <!-- Card 3: Our Values -->
          <div class="col-md-4">
            <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
              <div class="ic-stat-circle mb-3">
                <i class="fa-solid fa-gem"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-2 text-dark">{{ $interior->values_title ?? 'Our Values' }}</h3>
              @if(!empty($interior->values_text))
                <div class="text-muted small mb-0" style="line-height: 1.7;">
                  {!! nl2br(e($interior->values_text)) !!}
                </div>
              @else
                <ul class="list-unstyled text-muted small mb-0" style="line-height: 1.7;">
                  <li class="mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> Client's Happiness First</li>
                  <li class="mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> Creativity & Innovation</li>
                  <li class="mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> Sustainable Design</li>
                  <li class="mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> Integrity & Transparency</li>
                  <li><i class="fa-solid fa-circle-check text-success me-1"></i> Quality in Every Detail</li>
                </ul>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Full-Width 4-Stats Bar -->
    @php
      $stats = $interior->stats_data ?? [
        ['number' => '8+',   'label' => 'Years of Experience',   'icon' => 'fa-users'],
        ['number' => '250+', 'label' => 'Projects Completed',    'icon' => 'fa-file-lines'],
        ['number' => '98%',  'label' => 'Client Satisfaction',   'icon' => 'fa-star'],
        ['number' => '50+',  'label' => 'Expert Team Members',   'icon' => 'fa-user-group'],
      ];
    @endphp
    <div class="p-4 bg-light rounded-4 border mt-5">
      <div class="row g-4 text-center">
        @foreach($stats as $st)
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center justify-content-center gap-3">
              <div class="ic-stat-circle"><i class="fa-solid {{ $st['icon'] ?? 'fa-star' }}"></i></div>
              <div class="text-start">
                <div class="fw-bold fs-4 text-dark mb-0">{{ $st['number'] ?? '' }}</div>
                <div class="text-muted small">{{ $st['label'] ?? '' }}</div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- ===== MEET OUR TEAM SECTION ===== -->
<section class="py-5" style="background: #ffffff;">
  <div class="ic-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <span class="ic-pill-badge">{{ $interior->team_badge ?? 'MEET OUR TEAM' }} ——</span>
        <h2 class="ic-heading display-6 mb-2">{!! nl2br(e($interior->team_title ?? "The Creative Minds\nBehind Your Dream Space")) !!}</h2>
        <p class="text-muted mb-0" style="max-width: 540px; font-size: 14.5px;">
          {{ $interior->team_subtitle ?? 'Our team is made up of passionate designers, space planners, and problem-solvers who love turning ideas into beautiful, functional realities.' }}
        </p>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" id="teamPrevBtn" class="btn btn-light rounded-circle border d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" aria-label="Previous Team Member"><i class="fa-solid fa-arrow-left"></i></button>
        <button type="button" id="teamNextBtn" class="btn btn-light rounded-circle border d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" aria-label="Next Team Member"><i class="fa-solid fa-arrow-right"></i></button>
        <a href="{{ $contactUrl }}" class="ic-btn ic-btn-outline ms-2" style="border-radius: var(--ic-radius-pill); border: 1.5px solid var(--ic-border); color: var(--ic-text-dark); padding: 10px 22px; font-weight: 700; text-decoration: none;">
          View All Team Members <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <!-- 4 Team Members Grid (Single Row Mobile Slider: 1 Element Per Slide) -->
    @php
      $team = $interior->team_members_data ?? [
        ['name' => 'Priya Sharma',  'role' => 'Founder & CEO',     'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Rahul Mehta',   'role' => 'Creative Director', 'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Anjali Verma',  'role' => 'Head of Design',    'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Daniel Smith',  'role' => 'Project Manager',   'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=400&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-4 ic-mobile-slider" id="teamSliderTrack">
      @foreach($team as $tm)
        <div class="col-12 col-md-6 col-lg-3">
          <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm text-center">
            <div style="height: 260px; overflow: hidden; background: #EAE6DF;">
              <img src="{{ str_starts_with($tm['image'] ?? '', 'http') ? ($tm['image'] ?? '') : asset(ltrim($tm['image'] ?? '', '/')) }}" alt="{{ $tm['name'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="p-3 bg-white">
              <h4 class="fw-bold fs-6 mb-1 text-dark">{{ $tm['name'] ?? '' }}</h4>
              <div class="text-muted small mb-3">{{ $tm['role'] ?? '' }}</div>
              <div class="d-flex justify-content-center gap-2 fs-6">
                <a href="#" class="ic-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: #F3F4F6; color: #4B5563;" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="ic-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: #F3F4F6; color: #4B5563;" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#" class="ic-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: #F3F4F6; color: #4B5563;" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#" class="ic-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: #F3F4F6; color: #4B5563;" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
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
    var teamSlider = document.getElementById('teamSliderTrack');
    var prevBtn = document.getElementById('teamPrevBtn');
    var nextBtn = document.getElementById('teamNextBtn');

    if (teamSlider) {
      if (prevBtn) {
        prevBtn.addEventListener('click', function() {
          var step = teamSlider.clientWidth || 300;
          teamSlider.scrollBy({ left: -step, behavior: 'smooth' });
        });
      }
      if (nextBtn) {
        nextBtn.addEventListener('click', function() {
          var step = teamSlider.clientWidth || 300;
          teamSlider.scrollBy({ left: step, behavior: 'smooth' });
        });
      }

      let autoSlideTimer;
      function startTeamAutoSlide() {
        autoSlideTimer = setInterval(function() {
          if (window.innerWidth < 992) {
            var maxScroll = teamSlider.scrollWidth - teamSlider.clientWidth;
            if (teamSlider.scrollLeft >= maxScroll - 10) {
              teamSlider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
              teamSlider.scrollBy({ left: teamSlider.clientWidth, behavior: 'smooth' });
            }
          }
        }, 3500);
      }
      startTeamAutoSlide();
      teamSlider.addEventListener('touchstart', function() { clearInterval(autoSlideTimer); }, { passive: true });
      teamSlider.addEventListener('touchend', function() { startTeamAutoSlide(); }, { passive: true });
    }
  });
</script>
@endsection

@endsection
