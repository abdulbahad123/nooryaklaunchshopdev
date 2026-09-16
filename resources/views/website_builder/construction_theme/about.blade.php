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
  $heroBg = asset($agency->about_hero_image ?? 'assets/website_builder/Templates/Construction_agency/herobanner_image.png');
  $stats   = $agency->stats_data ?? [];
  $team    = $agency->team_members_data ?? [];
  $specializations = $agency->construction_data['specializations'] ?? [];
@endphp

{{-- Page Hero --}}
<section class="cn-page-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 340px;">
  <div class="cn-page-hero-overlay"></div>
  <div class="cn-container" style="width:100%;">
    <div class="cn-page-hero-content">
      <div class="cn-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        <span>About Us</span>
      </div>
      <h1 class="cn-page-hero-title">{{ $agency->about_hero_title ?? 'Building the Future, One Project at a Time' }}</h1>
      <p class="cn-page-hero-subtitle">{{ $agency->about_hero_subtitle ?? 'Over 25 years of excellence in construction — delivering quality, safety, and innovation.' }}</p>
    </div>
  </div>
</section>

{{-- Stats Strip --}}
@if(count($stats) > 0)
<div class="cn-stats-bar">
  <div class="cn-container" style="padding:0;">
    <div class="cn-stats-inner">
      @foreach($stats as $stat)
      <div class="cn-stat-item">
        <div class="cn-stat-number">{{ $stat['number'] ?? '' }}</div>
        <div class="cn-stat-label">{{ $stat['label'] ?? '' }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endif

{{-- Our Story --}}
<section class="cn-section">
  <div class="cn-container">
    <div class="cn-about-story-grid">
      <div class="cn-about-badge-wrap">
        <img src="{{ asset($agency->about_hero_image ?? 'assets/website_builder/Templates/Construction_agency/herobanner_image.png') }}"
             alt="Our Story" class="cn-about-img">
        <div class="cn-about-years-badge">
          <div class="cn-about-years-num">25+</div>
          <div class="cn-about-years-label">Years of<br>Excellence</div>
        </div>
      </div>
      <div>
        <div class="cn-section-tag"><i class="fa-solid fa-book-open"></i> Our Story</div>
        <h2 class="cn-section-title">{{ $agency->story_title ?? 'Our Story' }}</h2>
        <div class="cn-divider"></div>
        <p style="font-size:15px;color:var(--cn-text-muted);line-height:1.8;margin-bottom:24px;">
          {{ $agency->story_text ?? 'Founded in 1999, BuildCraft Construction began as a small residential builder and has grown into one of the most trusted names in the construction industry.' }}
        </p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:28px;">
          @foreach(['Safety First', 'Premium Quality', 'On-Time Delivery', 'Client-Centric'] as $point)
          <div style="display:flex;align-items:center;gap:10px;font-size:14px;color:var(--cn-text-light);">
            <i class="fa-solid fa-circle-check" style="color:var(--cn-primary);font-size:16px;"></i>
            {{ $point }}
          </div>
          @endforeach
        </div>
        <a href="{{ $contactUrl }}" class="cn-btn cn-btn-primary">
          <i class="fa-solid fa-file-lines"></i> Get Free Consultation
        </a>
      </div>
    </div>
  </div>
</section>

{{-- Specializations / Why Choose Us --}}
@if(count($specializations) > 0)
<section class="cn-section cn-section-alt">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-tag"><i class="fa-solid fa-star"></i> Why BuildCraft</div>
      <h2 class="cn-section-title">What Sets Us <span>Apart</span></h2>
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
<section class="cn-section">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-tag"><i class="fa-solid fa-users"></i> Leadership</div>
      <h2 class="cn-section-title">Meet Our <span>Expert Team</span></h2>
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
          <div class="cn-team-name">{{ $member['name'] }}</div>
          <div class="cn-team-role">{{ $member['role'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- CTA --}}
<section class="cn-cta-section">
  <div class="cn-container text-center">
    <h2 class="cn-cta-title">Let's Build Something <span style="color:var(--cn-dark);">Extraordinary</span></h2>
    <p class="cn-cta-subtitle">Partner with BuildCraft for your next project and experience the difference of true construction excellence.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="{{ $contactUrl }}" class="cn-btn cn-btn-cta-dark">
        <i class="fa-solid fa-file-lines"></i> Get Free Quote
      </a>
    </div>
  </div>
</section>

@endsection
