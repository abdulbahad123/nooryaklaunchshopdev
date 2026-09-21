@extends('website_builder.agency_template.layout')

@section('title', 'About Us - ' . ($agency->site_title ?? 'DesignAGENCY'))

@section('content')
@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $portfolioUrl = $agency->about_primary_btn_url ?? ($subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.portfolio'));
  $contactUrl = $agency->about_secondary_btn_url ?? ($subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.contact'));
@endphp

<!-- ===== ABOUT HERO SECTION ===== -->
<section style="background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%); padding: 55px 0 40px;">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="agency-label-pill">
          <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> {{ $agency->about_badge ?? 'About Us' }}
        </div>
        <h1 class="agency-heading" style="font-size: clamp(34px, 5vw, 50px);">
          {!! nl2br(e($agency->about_hero_title ?? "We Are A Creative Digital Solutions Agency")) !!}
        </h1>
        <p class="agency-subtitle mb-4">
          {{ $agency->about_hero_subtitle ?? 'We help brands thrive in the digital world through innovative design, smart strategy, and cutting-edge technology.' }}
        </p>
        <div class="d-flex align-items-center gap-3 flex-wrap agency-hero-actions">
          <a href="{{ $portfolioUrl }}" class="btn-agency-register" style="padding: 13px 28px; font-size: 14px;">
            {{ $agency->about_primary_btn_text ?? 'Our Portfolio' }} <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
          <a href="{{ $contactUrl }}" class="btn-agency-login" style="padding: 13px 26px; font-size: 14px;">
            {{ $agency->about_secondary_btn_text ?? 'Contact Us' }} <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Right Image Frame -->
      <div class="col-lg-6">
        <div class="position-relative">
          @php
            $aboutHeroImg = !empty($agency->about_hero_image) ? $agency->about_hero_image : 'assets/website_builder/agency_team_meeting.png';
            $aboutHeroUrl = str_starts_with($aboutHeroImg, 'http') ? $aboutHeroImg : asset(ltrim($aboutHeroImg, '/'));
          @endphp
          <img src="{{ $aboutHeroUrl }}" 
               onerror="this.src='https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=800&auto=format&fit=crop';" 
               alt="{{ $agency->site_title ?? 'Agency' }} Team" 
               style="width: 100%; height: auto; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">

          <!-- Experience Overlay Card -->
          <div class="position-absolute card border-0 p-3 shadow-lg" style="bottom: -20px; left: -20px; border-radius: 16px; background: #ffffff; min-width: 180px;">
            <div class="text-center">
              <h2 class="fw-extrabold mb-0 text-success" style="font-size: 36px;">{{ $agency->stats_data[0]['number'] ?? '8+' }}</h2>
              <div class="small text-muted fw-bold">{{ $agency->stats_data[0]['label'] ?? 'Years of Experience' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== OUR STORY & MISSION/VISION/VALUES ===== -->
<section style="padding: 50px 0; background: #FFFFFF;">
  <div class="container">
    <div class="row g-5">
      <!-- Left: Our Story -->
      <div class="col-lg-5">
        <div class="agency-label-pill">{{ $agency->story_badge ?? 'OUR STORY' }}</div>
        <h2 class="agency-heading">{!! nl2br(e($agency->story_title ?? 'Our Journey Started With A Simple Idea')) !!}</h2>
        <p class="text-slate-600 mb-3" style="font-size: 14.5px; line-height: 1.7;">
          {{ $agency->story_text ?? 'DesignAGENCY was founded in 2016 with a mission to empower businesses with smart digital solutions.' }}
        </p>

        <!-- Signature -->
        <div class="pt-2 border-top">
          <div class="fw-bold fs-5 text-slate-900 fst-italic">{{ $agency->founder_name ?? 'Michael Roberts' }}</div>
          <div class="small text-muted fw-semibold">{{ $agency->founder_role ?? 'Founder & CEO' }}</div>
        </div>
      </div>

      <!-- Right: Mission, Vision, Values cards -->
      <div class="col-lg-7">
        <div class="row g-2 g-md-3">
          <div class="col-6 col-md-4">
            <div class="card h-100 border-0 p-2.5 p-sm-4 text-center" style="background: #F8FAFC; border-radius: 18px;">
              <div class="d-inline-flex align-items-center justify-content-center p-2 p-sm-3 rounded-circle mx-auto mb-2 mb-sm-3" style="width: 44px; height: 44px; background: #ECFDF5; color: #10B981; font-size: 18px;">
                <i class="fa-solid fa-crosshairs"></i>
              </div>
              <h5 class="fw-bold fs-6 mb-1 mb-sm-2">{{ $agency->mission_title ?? 'Our Mission' }}</h5>
              <p class="text-muted small mb-0" style="line-height: 1.45; font-size: 11.5px;">{{ $agency->mission_text ?? 'To deliver innovative digital solutions that help businesses grow, connect, and succeed in a competitive world.' }}</p>
            </div>
          </div>

          <div class="col-6 col-md-4">
            <div class="card h-100 border-0 p-2.5 p-sm-4 text-center" style="background: #F8FAFC; border-radius: 18px;">
              <div class="d-inline-flex align-items-center justify-content-center p-2 p-sm-3 rounded-circle mx-auto mb-2 mb-sm-3" style="width: 44px; height: 44px; background: #ECFDF5; color: #10B981; font-size: 18px;">
                <i class="fa-solid fa-eye"></i>
              </div>
              <h5 class="fw-bold fs-6 mb-1 mb-sm-2">{{ $agency->vision_title ?? 'Our Vision' }}</h5>
              <p class="text-muted small mb-0" style="line-height: 1.45; font-size: 11.5px;">{{ $agency->vision_text ?? 'To be a global leader in digital innovation, known for creativity, reliability, and measurable impact.' }}</p>
            </div>
          </div>

          <div class="col-6 col-md-4">
            <div class="card h-100 border-0 p-2.5 p-sm-4 text-start" style="background: #F8FAFC; border-radius: 18px;">
              <div class="d-inline-flex align-items-center justify-content-center p-2 p-sm-3 rounded-circle mb-2 mb-sm-3" style="width: 44px; height: 44px; background: #ECFDF5; color: #10B981; font-size: 18px;">
                <i class="fa-solid fa-gem"></i>
              </div>
              <h5 class="fw-bold fs-6 mb-1 mb-sm-2">{{ $agency->values_title ?? 'Our Values' }}</h5>
              @php
                $valText = $agency->values_text ?? 'Client Success First, Innovation & Creativity, Integrity & Transparency, Quality & Excellence';
                $valItems = array_map('trim', explode(',', $valText));
              @endphp
              <ul class="list-unstyled small text-muted mb-0 space-y-1" style="font-size: 11px;">
                @foreach($valItems as $valItem)
                  <li><i class="fa-solid fa-circle-check text-success me-1"></i> {{ $valItem }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Bar -->
    @php
      $stats = $agency->stats_data ?? [
        ['number' => '8+',   'label' => 'Years of Experience'],
        ['number' => '250+', 'label' => 'Projects Completed'],
        ['number' => '98%',  'label' => 'Client Satisfaction'],
        ['number' => '50+',  'label' => 'Expert Team Members'],
      ];
    @endphp
    <div class="card border-0 shadow-sm mt-5" style="border-radius: 20px; padding: 30px 20px; background: #F8FAFC;">
      <div class="row g-4 text-center">
        @foreach($stats as $st)
          <div class="col-md-3 col-6">
            <h3 class="fw-extrabold mb-0 text-success fs-2">{{ $st['number'] ?? '' }}</h3>
            <div class="small text-muted fw-bold">{{ $st['label'] ?? '' }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- ===== MEET OUR TEAM SECTION ===== -->
<section style="padding: 50px 0 120px; background: #F8FAFC;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <div class="agency-label-pill">{{ $agency->team_badge ?? 'MEET OUR TEAM' }}</div>
        <h2 class="agency-heading mb-0">{{ $agency->team_title ?? 'The People Behind Our Success' }}</h2>
        <p class="agency-subtitle text-muted mb-0" style="max-width: 500px; font-size: 14.5px;">{{ $agency->team_subtitle ?? 'Our team is made up of passionate creatives, strategists, and problem-solvers who love turning ideas into reality.' }}</p>
      </div>

      <!-- Navigation Arrows for Manual Slide -->
      <div class="d-flex align-items-center gap-2">
        <button type="button" id="agencyTeamPrevBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" aria-label="Previous Team Member"><i class="fa-solid fa-chevron-left text-dark"></i></button>
        <button type="button" id="agencyTeamNextBtn" class="btn btn-light rounded-circle border p-0 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" aria-label="Next Team Member"><i class="fa-solid fa-chevron-right text-dark"></i></button>
      </div>
    </div>

    <div class="row g-4 agency-mobile-slider" id="agencyTeamSliderTrack">
      @php
        $team = $agency->team_members_data ?? [
          ['name' => 'Michael Roberts', 'role' => 'Founder & CEO',       'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop'],
          ['name' => 'Sarah Johnson',   'role' => 'Creative Director',    'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'],
          ['name' => 'Daniel Smith',    'role' => 'Head of Development', 'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop'],
          ['name' => 'Jessica Brown',   'role' => 'Marketing Manager',    'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=400&auto=format&fit=crop'],
        ];
      @endphp

      @foreach($team as $m)
        <div class="col-12 col-md-6 col-lg-3">
          <div class="card border-0 h-100 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div style="height: 240px; overflow: hidden; background: #0F172A;">
              <img src="{{ str_starts_with($m['image'] ?? '', 'http') ? $m['image'] : asset(ltrim($m['image'] ?? '', '/')) }}" 
                   onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop';"
                   alt="{{ $m['name'] ?? '' }}" 
                   style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
            </div>
            <div class="p-3 text-center bg-white">
              <h5 class="fw-bold fs-6 mb-1 text-slate-900">{{ $m['name'] ?? '' }}</h5>
              <div class="text-muted mb-3" style="font-size: 12.5px; font-weight: 600;">{{ $m['role'] ?? '' }}</div>
              <div class="d-flex justify-content-center gap-2">
                <a href="#" class="btn btn-light btn-sm rounded-circle"><i class="fa-brands fa-facebook-f text-muted"></i></a>
                <a href="#" class="btn btn-light btn-sm rounded-circle"><i class="fa-brands fa-x-twitter text-muted"></i></a>
                <a href="#" class="btn btn-light btn-sm rounded-circle"><i class="fa-brands fa-linkedin-in text-muted"></i></a>
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
    var teamTrack = document.getElementById('agencyTeamSliderTrack');
    var prevBtn = document.getElementById('agencyTeamPrevBtn');
    var nextBtn = document.getElementById('agencyTeamNextBtn');

    if (teamTrack) {
      if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
          e.preventDefault();
          var step = teamTrack.clientWidth || 300;
          teamTrack.scrollBy({ left: -step, behavior: 'smooth' });
        });
      }
      if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
          e.preventDefault();
          var step = teamTrack.clientWidth || 300;
          var maxScroll = teamTrack.scrollWidth - teamTrack.clientWidth;
          if (teamTrack.scrollLeft >= maxScroll - 10) {
            teamTrack.scrollTo({ left: 0, behavior: 'smooth' });
          } else {
            teamTrack.scrollBy({ left: step, behavior: 'smooth' });
          }
        });
      }

      let autoTimer;
      function startAutoSlide() {
        autoTimer = setInterval(function() {
          if (window.innerWidth < 992) {
            var maxScroll = teamTrack.scrollWidth - teamTrack.clientWidth;
            if (teamTrack.scrollLeft >= maxScroll - 10) {
              teamTrack.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
              var step = teamTrack.clientWidth || 300;
              teamTrack.scrollBy({ left: step, behavior: 'smooth' });
            }
          }
        }, 3500);
      }
      startAutoSlide();
      teamTrack.addEventListener('mouseenter', function() { clearInterval(autoTimer); });
      teamTrack.addEventListener('mouseleave', startAutoSlide);
      teamTrack.addEventListener('touchstart', function() { clearInterval(autoTimer); }, { passive: true });
      teamTrack.addEventListener('touchend', startAutoSlide, { passive: true });
    }
  });
</script>
@endsection
@endsection

