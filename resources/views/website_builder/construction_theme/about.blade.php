@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — About Us')
@section('description', $agency->about_hero_subtitle ?? 'Over 25 years of excellence in construction — delivering quality, safety, and innovation across every project.')

@section('content')

@php
  $contactUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.contact', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction.contact');
  $homeUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.site', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction');
  $heroBg = asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
  $stats   = $agency->stats_data ?? [];
  $team    = $agency->team_members_data ?? [];
  $specializations = $agency->construction_data['specializations'] ?? [];
@endphp

{{-- Page Hero --}}
<section class="cn-page-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 340px; position: relative;">
  <div class="cn-page-hero-overlay"></div>
  <div class="cn-container" style="position: relative; z-index: 2; width: 100%;">
    <div class="cn-page-hero-content">
      <div class="cn-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        <span>About Us</span>
      </div>
      <h1 class="cn-page-hero-title">{{ $agency->about_hero_title ?? 'More Than Just Construction We Build Better Lives' }}</h1>
      <p class="cn-page-hero-subtitle">{{ $agency->about_hero_subtitle ?? 'Over 25 years of excellence in construction — delivering quality, safety, and innovation.' }}</p>
    </div>
  </div>
</section>

{{-- Stats Strip --}}
@if(count($stats) > 0)
<div class="cn-dark-stats-bar my-0 rounded-0" style="background: #111111;">
  <div class="cn-container">
    <div class="row text-center g-3">
      @foreach($stats as $stat)
      <div class="col-6 col-md-3">
        <div class="cn-dark-stat-item">
          <div class="cn-dark-stat-icon"><i class="fa-solid {{ $stat['icon'] ?? 'fa-star' }}"></i></div>
          <div class="cn-dark-stat-num">{{ $stat['number'] ?? '' }}</div>
          <div class="cn-dark-stat-label">{{ $stat['label'] ?? '' }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endif

{{-- Our Story --}}
<section class="cn-section cn-about-ref-section">
  <div class="cn-container">
    <div class="cn-about-story-grid">
      <div class="cn-about-badge-wrap">
        <img src="{{ asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png') }}"
             alt="Our Story" class="cn-about-img">
        <div class="cn-about-years-badge">
          <div class="cn-about-years-num">25+</div>
          <div class="cn-about-years-label">Years of<br>Excellence</div>
        </div>
      </div>
      <div>
        <div class="cn-section-label">OUR STORY</div>
        <h2 class="cn-section-heading" style="color: #111111;">{{ $agency->story_title ?? 'Building Excellence Since 2008' }}</h2>
        <div class="cn-divider"></div>
        <p style="font-size:15px; color:#555555; line-height:1.8; margin-bottom:24px;">
          {{ $agency->story_text ?? 'BuildCraft was founded with a single mission: to redefine construction standards through safety, precision, and architectural innovation.' }}
        </p>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:28px;">
          @foreach(['Safety First', 'Premium Quality', 'On-Time Delivery', 'Client-Centric'] as $point)
          <div style="display:flex; align-items:center; gap:10px; font-size:14px; color:#111111; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#FFB800; font-size:16px;"></i>
            {{ $point }}
          </div>
          @endforeach
        </div>
        <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
          Get Free Consultation <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>
</section>

{{-- Specializations / Why Choose Us --}}
@if(count($specializations) > 0)
<section class="cn-section cn-section-light">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-label">WHY BUILDCRAFT</div>
      <h2 class="cn-section-heading">What Sets Us <span class="cn-text-yellow">Apart</span></h2>
      <div class="cn-divider cn-divider-center"></div>
    </div>
    <div class="cn-why-grid">
      @foreach($specializations as $item)
      <div class="cn-why-card">
        <div class="cn-why-icon"><i class="fa-solid {{ $item['icon'] ?? 'fa-star' }}"></i></div>
        <div class="cn-why-title">{{ $item['title'] }}</div>
        <div class="cn-why-desc">{{ $item['desc'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Team --}}
@if(count($team) > 0)
<section class="cn-section cn-section-alt">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-label">LEADERSHIP</div>
      <h2 class="cn-section-heading">Meet Our <span class="cn-text-yellow">Expert Team</span></h2>
      <div class="cn-divider cn-divider-center"></div>
    </div>
    <div class="cn-team-grid">
      @foreach($team as $member)
      <div class="cn-team-card">
        <div class="cn-team-img-wrap">
          <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="cn-team-img" loading="lazy">
          <div class="cn-team-overlay">
            @if(!empty($member['social']['linkedin']))
              <a href="{{ $member['social']['linkedin'] }}" class="cn-social-btn"><i class="fab fa-linkedin-in"></i></a>
            @endif
          </div>
        </div>
        <div class="cn-team-body">
          <div class="cn-team-name" style="color:#111111;">{{ $member['name'] }}</div>
          <div class="cn-team-role" style="color:#FFB800;">{{ $member['role'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Footer CTA Banner --}}
<section class="cn-footer-cta-banner" style="background: url('{{ $heroBg }}') no-repeat center center / cover; position: relative;">
  <div class="cn-cta-overlay"></div>
  <div class="cn-container text-center" style="position: relative; z-index: 2;">
    <h2 class="cn-cta-title text-white">Let's Build Something <span class="cn-text-yellow">Extraordinary</span></h2>
    <p class="text-white-50 mb-4" style="max-width: 580px; margin: 0 auto 24px;">Partner with BuildCraft for your next project and experience the difference of true construction excellence.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
        Get a Free Quote <i class="fa-solid fa-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>

@endsection
