@extends('website_builder.evently_theme.layout')

@section('title', 'Services - ' . ($interior->site_title ?? 'Evently'))

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.contact');
  $evObj = $interior ?? $agency ?? null;
  $services = (!empty($evObj->services_data) && is_array($evObj->services_data) && count($evObj->services_data) > 0)
    ? $evObj->services_data
    : [
        ['title' => 'Corporate Galas & Summits', 'desc' => 'Flawless execution for high-profile business conferences and award galas.', 'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-building-columns'],
        ['title' => 'Luxury Weddings', 'desc' => 'Bespoke wedding planning, floral design, lighting, and guest experiences.', 'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-gem'],
        ['title' => 'Concerts & Festivals', 'desc' => 'Stage production, sound engineering, artist management, and crowd logistics.', 'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-music'],
        ['title' => 'Private Parties & VIP Lounge', 'desc' => 'Exclusive birthday bashes, anniversary galas, and VIP private dining.', 'image' => 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?q=80&w=600&auto=format&fit=crop', 'icon' => 'fa-champagne-glasses'],
        ['title' => 'Exhibitions & Trade Shows', 'desc' => 'Custom booth designs, interactive displays, and high-footfall event coordination.', 'image' => 'assets/website_builder/Templates/Evently/service_exhibition.png', 'icon' => 'fa-display'],
        ['title' => 'Catering & Gourmet Dining', 'desc' => 'Curated multi-course banquet menus, mixology bars, and gourmet dining experiences.', 'image' => 'assets/website_builder/Templates/Evently/service_catering.png', 'icon' => 'fa-utensils'],
      ];
@endphp

<!-- ===== HERO BANNER SECTION ===== -->
<section class="ev-page-hero">
  <div class="ev-container text-center">
    <div class="ev-page-hero-badge"><i class="fa-solid fa-gem"></i> {{ $evObj->services_badge ?? 'OUR SERVICES' }}</div>
    <h1 class="ev-page-hero-title">{!! nl2br(e($evObj->services_title ?? "Crafting Unforgettable\nEvent Experiences")) !!}</h1>
    <p class="ev-page-hero-sub text-center mx-auto">{{ $evObj->services_subtitle ?? 'Corporate galas, luxury weddings, concerts, summits, private VIP dining, and exhibitions.' }}</p>
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
                  <img src="{{ resolveWebsiteBuilderImage($srv['image'] ?? '', 'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=600&auto=format&fit=crop') }}" alt="{{ $srv['title'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover;">
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
