@extends('website_builder.construction_theme.layout')

@section('no_cta')@endsection

@section('title', 'About Us - ' . ($agency->site_title ?? 'BuildCraft Construction'))

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

<!-- ===== ABOUT HERO SECTION (MATCHING TAXIGO ABOUT REF IMAGE 1) ===== -->
@php
  $heroBannerBg = asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
@endphp

<section class="cn-hero" style="background: url('{{ $heroBannerBg }}') no-repeat center right / cover; min-height: 520px; position: relative;">
  <div class="cn-hero-overlay"></div>
  <div class="cn-container" style="position: relative; z-index: 2;">
    <div class="row align-items-center justify-content-start">
      <!-- Left Content -->
      <div class="col-lg-6 py-4 text-start" style="text-align: left !important;">
        <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left !important;">
          <span class="cn-pill-badge mb-3" style="background: #FFF8E6; color: #945B00; display: inline-flex; align-items: center; align-self: flex-start;">
            {{ $agency->about_badge ?? 'ABOUT US' }}
          </span>
          <h1 class="cn-heading cn-hero-title mb-2" style="text-align: left !important; margin-left: 0 !important;">
            {!! nl2br(e($agency->about_hero_title ?? "More Than Buildings,\nWe Construct Futures")) !!}
          </h1>
          <p class="cn-hero-subtitle mb-4" style="text-align: left !important; margin-left: 0 !important;">
            {{ $agency->about_hero_subtitle ?? "We're on a mission to make every project safer, smarter, and built to stand the test of time. From commercial complexes to residential developments, BuildCraft is always with you." }}
          </p>

          <!-- Actions -->
          <div class="d-flex align-items-center justify-content-start gap-2 gap-sm-3 mb-4 w-100 flex-wrap" style="justify-content: flex-start !important;">
            <a href="{{ $servicesUrl }}" class="cn-btn cn-btn-yellow px-4 py-3 fw-bold">
              {{ $agency->about_primary_btn_text ?? 'Our Services' }} <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
            <a href="{{ $contactUrl }}" class="cn-btn cn-btn-outline-dark px-4 py-3 fw-bold">
              Contact Us
            </a>
          </div>

          <!-- Trusted by Thousands Avatars -->
          <div class="d-flex align-items-center gap-3 pt-2" style="justify-content: flex-start !important;">
            <div class="d-flex align-items-center">
              <img src="{{ asset('assets/website_builder/Templates/Construction_agency/team_1.png') }}" class="rounded-circle border border-2 border-white" style="width: 38px; height: 38px; object-fit: cover; margin-right: -10px;">
              <img src="{{ asset('assets/website_builder/Templates/Construction_agency/team_2.png') }}" class="rounded-circle border border-2 border-white" style="width: 38px; height: 38px; object-fit: cover; margin-right: -10px;">
              <img src="{{ asset('assets/website_builder/Templates/Construction_agency/team_3.png') }}" class="rounded-circle border border-2 border-white" style="width: 38px; height: 38px; object-fit: cover; margin-right: -10px;">
              <img src="{{ asset('assets/website_builder/Templates/Construction_agency/team_4.png') }}" class="rounded-circle border border-2 border-white" style="width: 38px; height: 38px; object-fit: cover;">
            </div>
            <div>
              <div class="fw-bold text-dark" style="font-size: 13px; line-height: 1.2; text-align: left;">Trusted by</div>
              <div class="text-muted fw-semibold" style="font-size: 12px; text-align: left;">Thousands of Clients</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== OUR STORY SECTION ===== -->
<section id="our-story" class="cn-section" style="background: #ffffff;">
  <div class="cn-container">
    <div class="row g-5 align-items-start">
      <!-- Left Story Column -->
      <div class="col-lg-5">
        <span class="cn-pill-badge" style="background: #FFF8E6; color: #945B00;">{{ $agency->story_badge ?? 'OUR STORY' }}</span>
        <h2 class="cn-heading display-6 mb-3">
          {!! $agency->story_title ?? 'A Journey Driven<br>By <span style="color: var(--cn-primary);">Excellence</span>' !!}
        </h2>
        <p class="text-muted mb-4" style="line-height: 1.75; font-size: 14.5px;">
          {{ $agency->story_text ?? 'BuildCraft was founded with a simple idea — to make construction more reliable, sustainable, and human-centric. What started as a small team of engineering enthusiasts has grown into a trusted platform serving hundreds of clients across the country.' }}
        </p>

        <!-- Founder Signature Badge -->
        <div class="d-flex align-items-center gap-3 pt-2">
          <img src="{{ $agency->founder_image ?? asset('assets/website_builder/Templates/Construction_agency/team_1.png') }}" alt="{{ $agency->founder_name ?? 'Michael Carter' }}" class="rounded-circle" style="width: 52px; height: 52px; object-fit: cover;">
          <div>
            <h4 class="fw-bold text-dark fs-6 mb-0">{{ $agency->founder_name ?? 'Michael Carter' }}</h4>
            <span class="text-muted small">{{ $agency->founder_role ?? 'Founder & CEO' }}</span>
          </div>
          <div class="ms-auto d-none d-sm-block">
            <span class="cn-cursive text-dark" style="font-family: 'Caveat', cursive; font-size: 28px; font-weight: 700; opacity: 0.85;">{{ $agency->founder_name ?? 'Michael Carter' }}</span>
          </div>
        </div>
      </div>

      <!-- Right 3 Cards (Mission, Vision, Values) -->
      <div class="col-lg-7">
        <div class="row g-2 g-md-3">
          <!-- Card 1: Our Mission -->
          <div class="col-6 col-md-4">
            <div class="card h-100 border p-2.5 p-sm-4 rounded-4 shadow-sm bg-white">
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 mb-sm-3 text-dark" style="width: 40px; height: 40px; background: #FFF8E6; font-size: 16px;">
                <i class="fa-solid fa-bullseye" style="color: #945B00;"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-1 mb-sm-2 text-dark">Our Mission</h3>
              <p class="text-muted small mb-0" style="line-height: 1.45; font-size: 11.5px;">
                {{ $agency->mission_text ?? 'To provide safe, sustainable, and top-tier construction services for everyone, everywhere.' }}
              </p>
            </div>
          </div>

          <!-- Card 2: Our Vision -->
          <div class="col-6 col-md-4">
            <div class="card h-100 border p-2.5 p-sm-4 rounded-4 shadow-sm bg-white">
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 mb-sm-3 text-dark" style="width: 40px; height: 40px; background: #FFF8E6; font-size: 16px;">
                <i class="fa-regular fa-eye" style="color: #945B00;"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-1 mb-sm-2 text-dark">Our Vision</h3>
              <p class="text-muted small mb-0" style="line-height: 1.45; font-size: 11.5px;">
                {{ $agency->vision_text ?? 'To be the most trusted global construction platform, building iconic skylines.' }}
              </p>
            </div>
          </div>

          <!-- Card 3: Our Values -->
          <div class="col-6 col-md-4">
            <div class="card h-100 border p-2.5 p-sm-4 rounded-4 shadow-sm bg-white">
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 mb-sm-3 text-dark" style="width: 40px; height: 40px; background: #FFF8E6; font-size: 16px;">
                <i class="fa-solid fa-gem" style="color: #945B00;"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-1 mb-sm-2 text-dark">Our Values</h3>
              <ul class="list-unstyled text-muted small mb-0" style="line-height: 1.5; font-size: 11px;">
                <li class="mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> Quality First</li>
                <li class="mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> Safety & Reliability</li>
                <li class="mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> Transparency</li>
                <li class="mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> Sustainability</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FULL WIDTH 4-STATS LIGHT BAR ===== -->
@php
  $stats = $agency->stats_data ?? [
    ['number' => '15+',   'label' => 'Years of Experience',   'icon' => 'fa-award'],
    ['number' => '250+',  'label' => 'Projects Completed',    'icon' => 'fa-building'],
    ['number' => '98%',   'label' => 'Client Satisfaction',   'icon' => 'fa-star'],
    ['number' => '100+',  'label' => 'Skilled Professionals', 'icon' => 'fa-helmet-safety'],
  ];
@endphp

<div class="cn-container">
  <div class="cn-light-stats-bar">
    <div class="row g-0 align-items-center">
      @foreach($stats as $i => $st)
        <div class="col-6 col-lg-3">
          <div class="cn-stat-item {{ $i < count($stats)-1 ? 'cn-stat-border' : '' }}" style="display: flex; align-items: center; gap: 16px; padding: 8px 28px;">
            <div class="cn-stat-icon" style="width: 54px; height: 54px; border-radius: 50%; background: #FFF8E6; color: #111111; border: 2px solid rgba(255,184,0,0.45); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
              <i class="fa-solid {{ $st['icon'] ?? 'fa-building' }}"></i>
            </div>
            <div>
              <div class="cn-stat-num tx-counter-num" style="font-family: 'Barlow Condensed', sans-serif; font-size: 28px; font-weight: 800; color: #111111; line-height: 1.1;">{{ $st['number'] ?? '' }}</div>
              <div class="cn-stat-label" style="font-size: 12px; color: #64748B; font-weight: 500; margin-top: 2px;">{{ $st['label'] ?? '' }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<!-- ===== MEET OUR TEAM SECTION ===== -->
<section id="team" class="cn-section" style="background: #ffffff;">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-3">
      <div>
        <span class="cn-pill-badge" style="background: #FFF8E6; color: #945B00;">{{ $agency->team_badge ?? 'MEET OUR TEAM' }}</span>
        <h2 class="cn-heading display-6 mb-2">{!! nl2br(e($agency->team_title ?? 'The People Behind BuildCraft')) !!}</h2>
        <p class="text-muted mb-0" style="font-size: 14.5px;">{{ $agency->team_subtitle ?? 'Our team is made up of passionate individuals who believe in building a safer, stronger, and more sustainable world.' }}</p>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" id="teamPrevBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button type="button" id="teamNextBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $rawTeam = $agency->team_members_data ?? [];
      $defaultTeamImages = [
        asset('assets/website_builder/Templates/Construction_agency/team_1.png'),
        asset('assets/website_builder/Templates/Construction_agency/team_2.png'),
        asset('assets/website_builder/Templates/Construction_agency/team_3.png'),
        asset('assets/website_builder/Templates/Construction_agency/team_4.png'),
      ];

      $team = [];
      if (!empty($rawTeam)) {
        foreach($rawTeam as $idx => $tm) {
          $img = $tm['image'] ?? $tm['avatar'] ?? $tm['photo'] ?? '';
          if (empty($img) || str_contains($img, 'unsplash.com') || str_contains($img, 'team_1.jpg') || str_contains($img, 'team_2.jpg') || str_contains($img, 'team_3.jpg') || str_contains($img, 'team_4.jpg')) {
            $img = $defaultTeamImages[$idx % 4];
          }
          $team[] = [
            'name'  => $tm['name'] ?? 'Team Member',
            'role'  => $tm['role'] ?? 'Specialist',
            'image' => $img,
          ];
        }
      } else {
        $team = [
          ['name' => 'Michael Carter', 'role' => 'Founder & CEO',            'image' => asset('assets/website_builder/Templates/Construction_agency/team_1.png')],
          ['name' => 'Sarah Mitchell', 'role' => 'Chief Operating Officer', 'image' => asset('assets/website_builder/Templates/Construction_agency/team_2.png')],
          ['name' => 'David Thompson', 'role' => 'Head of Engineering',     'image' => asset('assets/website_builder/Templates/Construction_agency/team_3.png')],
          ['name' => 'Emily Davis',    'role' => 'Chief Architect',         'image' => asset('assets/website_builder/Templates/Construction_agency/team_4.png')],
        ];
      }
    @endphp

    <div class="row g-3 tx-mobile-slider" id="teamSliderTrack">
      @foreach($team as $tm)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm bg-white text-center p-3">
            <div class="rounded-3 overflow-hidden mb-3" style="height: 220px;">
              <img src="{{ str_starts_with($tm['image'] ?? '', 'http') ? ($tm['image'] ?? '') : asset(ltrim($tm['image'] ?? '', '/')) }}" alt="{{ $tm['name'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
            </div>
            <h3 class="fw-bold fs-6 mb-1 text-dark">{{ $tm['name'] ?? '' }}</h3>
            <div class="text-muted small mb-3" style="font-size: 12px;">{{ $tm['role'] ?? '' }}</div>
            <div class="d-flex align-items-center justify-content-center gap-3 text-muted small">
              <a href="#" class="text-muted hover-yellow"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="#" class="text-muted hover-yellow"><i class="fa-brands fa-x-twitter"></i></a>
              <a href="#" class="text-muted hover-yellow"><i class="fa-brands fa-linkedin-in"></i></a>
              <a href="#" class="text-muted hover-yellow"><i class="fa-brands fa-instagram"></i></a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== WHAT OUR CLIENTS SAY (TESTIMONIALS) ===== -->
<section id="testimonials" class="cn-section cn-section-grey">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-3">
      <div>
        <span class="cn-pill-badge" style="background: #FFF8E6; color: #945B00;">{{ $agency->testimonials_badge ?? 'WHAT OUR CLIENTS SAY' }}</span>
        <h2 class="cn-heading display-6 mb-2">{!! nl2br(e($agency->testimonials_title ?? 'Stories From Our Happy Clients')) !!}</h2>
        <p class="text-muted mb-0" style="font-size: 14.5px;">{{ $agency->testimonials_subtitle ?? 'Real experiences from people who build with BuildCraft every day.' }}</p>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" id="tstPrevBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button type="button" id="tstNextBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $rawTestimonials = $agency->testimonials_data ?? [];
      $defaultTstAvatars = [
        asset('assets/website_builder/Templates/Construction_agency/team_1.png'),
        asset('assets/website_builder/Templates/Construction_agency/team_2.png'),
        asset('assets/website_builder/Templates/Construction_agency/team_3.png'),
      ];

      $testimonials = [];
      if (!empty($rawTestimonials)) {
        foreach($rawTestimonials as $idx => $t) {
          $avatar = $t['avatar'] ?? $t['image'] ?? $t['photo'] ?? '';
          if (empty($avatar) || str_contains($avatar, 'unsplash.com')) {
            $avatar = $defaultTstAvatars[$idx % 3];
          }
          $testimonials[] = [
            'name'    => $t['name'] ?? 'Satisfied Client',
            'role'    => $t['role'] ?? 'Client',
            'comment' => $t['comment'] ?? $t['text'] ?? '',
            'avatar'  => $avatar,
          ];
        }
      } else {
        $testimonials = [
          ['name' => 'James Anderson',  'role' => 'Commercial Client',    'comment' => 'BuildCraft made our commercial tower project so easy and stress-free. Highly recommended!', 'avatar' => asset('assets/website_builder/Templates/Construction_agency/team_1.png')],
          ['name' => 'Sophia Martinez', 'role' => 'Project Director',     'comment' => 'Reliable, affordable, and always on time. The best construction partner in the country!',  'avatar' => asset('assets/website_builder/Templates/Construction_agency/team_2.png')],
          ['name' => 'Robert Wilson',   'role' => 'Real Estate Developer', 'comment' => 'Professional engineers and excellent project delivery. Truly a great experience!',           'avatar' => asset('assets/website_builder/Templates/Construction_agency/team_3.png')],
        ];
      }
    @endphp

    <div class="row g-3 tx-mobile-slider" id="tstSliderTrack">
      @foreach($testimonials as $t)
        <div class="col-12 col-md-4">
          <div class="cn-tst-card">
            <div class="cn-tst-quote"><i class="fa-solid fa-quote-left"></i></div>
            <p class="cn-tst-text">"{{ $t['comment'] ?? '' }}"</p>
            <div class="cn-tst-stars">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
            </div>
            <div class="cn-tst-author">
              <img src="{{ str_starts_with($t['avatar'] ?? '', 'http') ? ($t['avatar'] ?? '') : asset(ltrim($t['avatar'] ?? '', '/')) }}" alt="{{ $t['name'] ?? '' }}" class="cn-tst-avatar">
              <div>
                <div class="cn-tst-name">{{ $t['name'] ?? '' }}</div>
                <div class="cn-tst-role">{{ $t['role'] ?? '' }}</div>
              </div>
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
  document.addEventListener('DOMContentLoaded', function() {
    function setupSlider(trackId, prevBtnId, nextBtnId) {
      const track = document.getElementById(trackId);
      const prev = document.getElementById(prevBtnId);
      const next = document.getElementById(nextBtnId);
      if (!track) return;

      function stepNext() {
        const step = (track.firstElementChild ? track.firstElementChild.clientWidth : 280) + 16;
        const maxScroll = track.scrollWidth - track.clientWidth;
        if (maxScroll <= 5) return;
        if (track.scrollLeft >= maxScroll - 15) {
          track.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          track.scrollBy({ left: step, behavior: 'smooth' });
        }
      }

      function stepPrev() {
        const step = (track.firstElementChild ? track.firstElementChild.clientWidth : 280) + 16;
        track.scrollBy({ left: -step, behavior: 'smooth' });
      }

      if (prev) prev.addEventListener('click', function(e) { e.preventDefault(); stepPrev(); });
      if (next) next.addEventListener('click', function(e) { e.preventDefault(); stepNext(); });

      let autoTimer;
      function startAuto() {
        autoTimer = setInterval(stepNext, 3500);
      }
      function stopAuto() {
        clearInterval(autoTimer);
      }
      startAuto();
      track.addEventListener('mouseenter', stopAuto);
      track.addEventListener('mouseleave', startAuto);
      track.addEventListener('touchstart', stopAuto, { passive: true });
      track.addEventListener('touchend', startAuto, { passive: true });
    }

    setupSlider('teamSliderTrack', 'teamPrevBtn', 'teamNextBtn');
    setupSlider('tstSliderTrack', 'tstPrevBtn', 'tstNextBtn');
  });
</script>
@endsection

@endsection
