@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — Our Services')

@section('content')

@php
  $heroBg = asset($agency->hero_image ?? 'assets/website_builder/Templates/Construction_agency/herobanner_image.png');
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
<section class="cn-page-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 300px;">
  <div class="cn-page-hero-overlay"></div>
  <div class="cn-container" style="width:100%;">
    <div class="cn-page-hero-content">
      <div class="cn-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        <span>Our Services</span>
      </div>
      <h1 class="cn-page-hero-title">Our <span style="color:var(--cn-primary);">Services</span></h1>
      <p class="cn-page-hero-subtitle">End-to-end construction solutions across residential, commercial, industrial, and infrastructure sectors.</p>
    </div>
  </div>
</section>

{{-- Services List --}}
@if(count($services) > 0)
<section class="cn-section">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-tag"><i class="fa-solid fa-gear"></i> What We Build</div>
      <h2 class="cn-section-title">Comprehensive <span>Construction Services</span></h2>
      <div class="cn-divider cn-divider-center"></div>
      <p class="cn-section-subtitle">We deliver integrated construction solutions with precision, safety, and on-time execution at every stage.</p>
    </div>
    <div class="cn-services-page-list">
      @foreach($services as $index => $service)
      @php $reverse = ($index % 2 !== 0); @endphp
      <div class="cn-service-row" style="{{ $reverse ? 'direction:rtl;' : '' }}">
        @if(!empty($service['image']))
          <img src="{{ $service['image'] }}" alt="{{ $service['title'] }}" class="cn-service-row-img" style="{{ $reverse ? 'direction:ltr;' : '' }}" loading="lazy">
        @endif
        <div class="cn-service-row-body" style="{{ $reverse ? 'direction:ltr;padding-left:32px;padding-right:0;' : '' }}">
          <div class="cn-service-icon-wrap" style="margin-bottom:12px;">
            <i class="fa-solid {{ $service['icon'] ?? 'fa-building' }}"></i>
          </div>
          <div class="cn-service-row-title">{{ $service['title'] }}</div>
          <div class="cn-service-row-desc">{{ $service['desc'] }}</div>
          <div class="cn-service-features">
            <div class="cn-service-feature"><i class="fa-solid fa-circle-check"></i> Licensed & Insured</div>
            <div class="cn-service-feature"><i class="fa-solid fa-circle-check"></i> On-Time Delivery</div>
            <div class="cn-service-feature"><i class="fa-solid fa-circle-check"></i> ISO Certified Quality</div>
            <div class="cn-service-feature"><i class="fa-solid fa-circle-check"></i> Transparent Pricing</div>
          </div>
          <div style="margin-top:20px;">
            <a href="{{ $contactUrl }}" class="cn-btn cn-btn-primary" style="padding:10px 22px;font-size:13px;">
              Get a Quote <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Why Choose Us --}}
@if(count($specializations) > 0)
<section class="cn-section cn-section-alt">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-tag"><i class="fa-solid fa-star"></i> Our Commitments</div>
      <h2 class="cn-section-title">The BuildCraft <span>Advantage</span></h2>
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
<section class="cn-cta-section">
  <div class="cn-container">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h2 class="cn-cta-title">Ready to Start Your Project?</h2>
        <p class="cn-cta-subtitle">Contact us today for a free consultation and project estimate from our expert construction team.</p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a href="{{ $contactUrl }}" class="cn-btn cn-btn-cta-dark">
          <i class="fa-solid fa-file-lines"></i> Get Free Quote
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
