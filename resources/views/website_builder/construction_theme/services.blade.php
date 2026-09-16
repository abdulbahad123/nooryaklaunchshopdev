@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — Our Services')
@section('description', 'Comprehensive construction services across residential, commercial, road, infrastructure, and remodeling projects.')

@section('content')

@php
  $heroBg = asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
  $services = $agency->services_data ?? [];
  $specializations = $agency->construction_data['specializations'] ?? [];
  $contactUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.contact', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction.contact');
  $homeUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.site', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction');
@endphp

{{-- Page Hero --}}
<section class="cn-page-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 320px; position: relative;">
  <div class="cn-page-hero-overlay"></div>
  <div class="cn-container" style="position: relative; z-index: 2; width: 100%;">
    <div class="cn-page-hero-content">
      <div class="cn-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        <span>Our Services</span>
      </div>
      <h1 class="cn-page-hero-title">Comprehensive <span class="cn-text-yellow">Construction Services</span></h1>
      <p class="cn-page-hero-subtitle">End-to-end construction solutions across residential, commercial, industrial, and infrastructure sectors.</p>
    </div>
  </div>
</section>

{{-- Services List --}}
@if(count($services) > 0)
<section class="cn-section cn-section-light">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-label">OUR SERVICES</div>
      <h2 class="cn-section-heading">What We <span class="cn-text-yellow">Build</span></h2>
      <div class="cn-divider cn-divider-center"></div>
      <p class="cn-section-subtitle">We deliver integrated construction solutions with precision, safety, and on-time execution at every stage.</p>
    </div>
    <div class="cn-services-page-list">
      @foreach($services as $index => $service)
      @php $reverse = ($index % 2 !== 0); @endphp
      <div class="cn-service-row" style="{{ $reverse ? 'direction:rtl;' : '' }} background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
        @if(!empty($service['image']))
          <img src="{{ asset($service['image']) }}" alt="{{ $service['title'] }}" class="cn-service-row-img" style="{{ $reverse ? 'direction:ltr;' : '' }}" loading="lazy">
        @endif
        <div class="cn-service-row-body" style="{{ $reverse ? 'direction:ltr;padding-left:32px;padding-right:0;' : '' }}">
          <div class="cn-service-icon-wrap" style="margin-bottom:12px; background: rgba(255,184,0,0.15); color:#FFB800;">
            <i class="fa-solid {{ $service['icon'] ?? 'fa-building' }}"></i>
          </div>
          <div class="cn-service-row-title" style="color:#111111;">{{ $service['title'] }}</div>
          <div class="cn-service-row-desc" style="color:#666666;">{{ $service['desc'] }}</div>
          <div class="cn-service-features">
            <div class="cn-service-feature" style="color:#444444;"><i class="fa-solid fa-circle-check" style="color:#FFB800;"></i> Licensed & Insured</div>
            <div class="cn-service-feature" style="color:#444444;"><i class="fa-solid fa-circle-check" style="color:#FFB800;"></i> On-Time Delivery</div>
            <div class="cn-service-feature" style="color:#444444;"><i class="fa-solid fa-circle-check" style="color:#FFB800;"></i> ISO Certified Quality</div>
            <div class="cn-service-feature" style="color:#444444;"><i class="fa-solid fa-circle-check" style="color:#FFB800;"></i> Transparent Pricing</div>
          </div>
          <div style="margin-top:20px;">
            <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow" style="padding:10px 22px; font-size:13px;">
              Get a Quote <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Specializations --}}
@if(count($specializations) > 0)
<section class="cn-section cn-section-alt">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-label">COMMITMENT</div>
      <h2 class="cn-section-heading">The BuildCraft <span class="cn-text-yellow">Advantage</span></h2>
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

{{-- CTA --}}
<section class="cn-footer-cta-banner" style="background: url('{{ $heroBg }}') no-repeat center center / cover; position: relative;">
  <div class="cn-cta-overlay"></div>
  <div class="cn-container text-center" style="position: relative; z-index: 2;">
    <h2 class="cn-cta-title text-white">Ready to Start Your <span class="cn-text-yellow">Project?</span></h2>
    <p class="text-white-50 mb-4" style="max-width: 540px; margin: 0 auto 24px;">Contact us today for a free consultation and project estimate from our expert construction team.</p>
    <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
      Get Free Quote <i class="fa-solid fa-arrow-right ms-1"></i>
    </a>
  </div>
</section>

@endsection
