@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — Our Services')
@section('description', 'Comprehensive construction services across residential, commercial, road, infrastructure, and remodeling projects.')

@section('content')

@php
  $heroBg = asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
  $services = (!empty($agency->services_data) && is_array($agency->services_data) && count($agency->services_data) > 0)
    ? $agency->services_data
    : [
        ['title' => 'Residential Construction', 'desc' => 'Dream homes built with precision.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_residential.png', 'icon' => 'fa-house'],
        ['title' => 'Commercial Buildings', 'desc' => 'Functional spaces for growing businesses.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_commercial.png', 'icon' => 'fa-building'],
        ['title' => 'Road & Infrastructure', 'desc' => 'Building stronger communities.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_infra.png', 'icon' => 'fa-road'],
        ['title' => 'Renovation & Remodeling', 'desc' => 'Transforming spaces for a better tomorrow.', 'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-hammer'],
        ['title' => 'Project Management', 'desc' => 'On-time. On-budget. Beyond expectations.', 'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-clipboard-check'],
        ['title' => 'Structural Engineering & Design', 'desc' => 'Advanced structural calculations, foundation design, and architectural engineering.', 'image' => 'assets/website_builder/Templates/Construction_agency/service_structural.png', 'icon' => 'fa-drafting-compass'],
      ];
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
    <div class="cn-page-hero-content text-center" style="max-width: 700px; margin: 0 auto;">
      <div class="cn-breadcrumb d-flex justify-content-center align-items-center gap-2 mb-2">
        <a href="{{ $homeUrl }}">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        <span>Our Services</span>
      </div>
      <h1 class="cn-page-hero-title mb-2">{!! nl2br(e($agency->services_title ?? "Comprehensive Construction &\nEngineering Services")) !!}</h1>
      <p class="cn-page-hero-subtitle">{{ $agency->services_subtitle ?? 'End-to-end construction solutions across residential, commercial, industrial, and infrastructure sectors.' }}</p>
    </div>
  </div>
</section>

{{-- Services List (3 columns per row on laptop view) --}}
@if(count($services) > 0)
<section class="cn-section cn-section-light" style="background: #ffffff; padding: 60px 0 100px;">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:48px;">
      <div class="cn-section-label">{{ $agency->services_badge ?? 'OUR SERVICES' }}</div>
      <h2 class="cn-section-heading">{!! nl2br(e($agency->services_title ?? 'What We Build')) !!}</h2>
      <div class="cn-divider cn-divider-center"></div>
      <p class="cn-section-subtitle">{{ $agency->services_subtitle ?? 'We deliver integrated construction solutions with precision, safety, and on-time execution at every stage.' }}</p>
    </div>

    <div class="row g-4">
      @foreach($services as $service)
        @php
          $srvImgSrc = resolveWebsiteBuilderImage($service['image'] ?? '', 'assets/website_builder/Templates/Construction_agency/service_residential.png');
        @endphp
        <div class="col-12 col-md-4 col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column justify-content-between" style="background: #FFFFFF; border: 1px solid #E5E7EB !important; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
            <div>
              @if(!empty($srvImgSrc))
                <div class="mb-3 rounded-3 overflow-hidden" style="height: 190px;">
                  <img src="{{ $srvImgSrc }}" alt="{{ $service['title'] ?? 'Service' }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
              @endif
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; background: rgba(255,184,0,0.15); color: #FFB800; font-size: 20px;">
                <i class="fa-solid {{ $service['icon'] ?? 'fa-building' }}"></i>
              </div>
              <h3 class="fw-bold fs-5 text-dark mb-2">{{ $service['title'] ?? '' }}</h3>
              <p class="text-muted small mb-4">{{ $service['desc'] ?? $service['description'] ?? '' }}</p>
            </div>
            <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow w-100 text-center fw-bold py-2.5" style="padding: 10px 22px; font-size: 13px;">
              {{ $service['btn_text'] ?? 'Get a Quote' }} <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif


@endsection
