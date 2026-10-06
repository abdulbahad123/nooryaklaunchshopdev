@extends('website_builder.interior_template.layout')

@section('title', 'Services - InterioCRAFT Architectural & Interior Design')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.contact');
  $interiorObj = $interior ?? $agency ?? null;
  $services = (!empty($interiorObj->services_data) && is_array($interiorObj->services_data) && count($interiorObj->services_data) > 0)
    ? $interiorObj->services_data
    : [
        ['title' => 'Residential Design', 'desc' => 'Bespoke living rooms, luxury master suites, modern kitchens, and private estate interiors.', 'image' => 'assets/website_builder/Templates/Interior_agency/service_residential.png', 'icon' => 'fa-couch'],
        ['title' => 'Commercial Architecture', 'desc' => 'Sophisticated office spaces, luxury retail boutiques, hospitality suites, and corporate lounges.', 'image' => 'assets/website_builder/Templates/Interior_agency/service_commercial.png', 'icon' => 'fa-building'],
        ['title' => 'Space Planning & Layout', 'desc' => 'Optimizing spatial ergonomics, natural light flow, structural layouts, and functional zoning.', 'image' => 'assets/website_builder/Templates/Interior_agency/service_planning.png', 'icon' => 'fa-ruler-combined'],
        ['title' => 'Custom Furniture & Styling', 'desc' => 'Handcrafted timber pieces, curated textiles, custom lighting fixtures, and art curation.', 'image' => 'assets/website_builder/Templates/Interior_agency/service_styling.png', 'icon' => 'fa-pen-ruler'],
        ['title' => 'Lighting & Smart Home Design', 'desc' => 'Architectural lighting plans, automated ambient controls, and smart space integrations.', 'image' => 'assets/website_builder/Templates/Interior_agency/service_smart_home.png', 'icon' => 'fa-lightbulb'],
        ['title' => 'Landscape & Outdoor Living', 'desc' => 'Luxury patio concepts, terrace styling, outdoor lounges, and biophilic garden designs.', 'image' => 'assets/website_builder/Templates/Interior_agency/service_landscape.png', 'icon' => 'fa-tree'],
      ];
@endphp

<!-- ===== HERO BANNER SECTION ===== -->
<section class="ic-hero position-relative overflow-hidden" style="background-color: #F7F7F5; padding: 75px 0 60px;">
  <div class="ic-container">
    <div class="text-center" style="max-width: 700px; margin: 0 auto;">
      <span class="ic-pill-badge mb-3">{{ $interiorObj->services_badge ?? 'OUR SERVICES' }}</span>
      <h1 class="ic-heading ic-hero-title mb-3">{!! nl2br(e($interiorObj->services_title ?? "Crafting Exceptional\nArchitectural & Interior Spaces")) !!}</h1>
      <p class="ic-hero-subtitle">{{ $interiorObj->services_subtitle ?? 'From spatial planning and 3D renderings to custom furniture styling, we deliver tailored interior design services.' }}</p>
    </div>
  </div>
</section>

<!-- ===== SERVICES GRID ===== -->
<section class="py-5" style="background: #ffffff;">
  <div class="ic-container">
    <div class="row g-4">
      @foreach($services as $srv)
        <div class="col-12 col-md-6 col-lg-4">
          <div class="ic-service-card card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column justify-content-between">
            <div>
              @if(!empty($srv['image']))
                <div class="mb-3 rounded-3 overflow-hidden" style="height: 200px;">
                  <img src="{{ str_starts_with($srv['image'], 'http') ? $srv['image'] : asset(ltrim($srv['image'], '/')) }}" alt="{{ $srv['title'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
              @endif
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: #F2F5F3; color: #111; font-size: 20px;">
                <i class="fa-solid {{ $srv['icon'] ?? 'fa-couch' }}"></i>
              </div>
              <h3 class="fw-bold fs-5 text-dark mb-2">{{ $srv['title'] ?? '' }}</h3>
              <p class="text-muted small leading-relaxed mb-4">{{ $srv['desc'] ?? $srv['description'] ?? '' }}</p>
            </div>
            <a href="{{ $contactUrl }}" class="btn btn-dark btn-sm rounded-pill fw-bold px-4 py-2 d-inline-flex align-items-center justify-content-center gap-2">
              Book Consultation <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
