@extends('website_builder.evently_theme.layout')

@section('title', 'Services - ' . ($interior->site_title ?? 'Evently'))

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.contact');
  $evObj = $interior ?? $agency ?? null;
  $services = $evObj->services_data ?? [];
@endphp

<!-- ===== HERO BANNER SECTION ===== -->
<section class="ev-page-hero">
  <div class="ev-container text-center">
    <div class="ev-page-hero-badge"><i class="fa-solid fa-gem"></i> {{ $evObj->services_badge ?? 'OUR SERVICES' }}</div>
    <h1 class="ev-page-hero-title">{!! nl2br(e($evObj->services_title ?? "Crafting Unforgettable\nEvent Experiences")) !!}</h1>
    <p class="ev-page-hero-sub">{{ $evObj->services_subtitle ?? 'Corporate galas, luxury weddings, concerts, summits, private VIP dining, and exhibitions.' }}</p>
  </div>
</section>

<!-- ===== SERVICES GRID ===== -->
<section class="py-5" style="background: #ffffff;">
  <div class="ev-container">
    <div class="row g-4">
      @foreach($services as $srv)
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column justify-content-between" style="border: 1px solid #E9D5FF !important; background: #FAF5FF;">
            <div>
              @if(!empty($srv['image']))
                <div class="mb-3 rounded-4 overflow-hidden" style="height: 200px;">
                  <img src="{{ str_starts_with($srv['image'], 'http') ? $srv['image'] : asset(ltrim($srv['image'], '/')) }}" alt="{{ $srv['title'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
              @endif
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: #6C3CE1; color: #ffffff; font-size: 20px;">
                <i class="fa-solid {{ $srv['icon'] ?? 'fa-gem' }}"></i>
              </div>
              <h3 class="fw-bold fs-5 text-dark mb-2">{{ $srv['title'] ?? '' }}</h3>
              <p class="text-muted small mb-4">{{ $srv['desc'] ?? $srv['description'] ?? '' }}</p>
            </div>
            <a href="{{ $contactUrl }}" class="btn btn-primary btn-sm rounded-pill fw-bold py-2.5 px-4" style="background: #6C3CE1; border: none;">
              Book Consultation <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
