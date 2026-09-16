@extends('website_builder.texigo_theme.layout')

@section('title', 'About Us - TaxiGo #1 Trusted Taxi Service')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.contact');
  $agency = $agency ?? $interior ?? null;
@endphp

<!-- ===== ABOUT HERO SECTION ===== -->
<section class="tx-hero">
  <div class="tx-container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="tx-pill-badge">ABOUT TAXIGO</span>
        <h1 class="tx-heading display-5 mb-3">
          Redefining City Mobility<br>With <span style="color: var(--tx-primary);">Trust & Comfort</span>
        </h1>
        <p class="text-muted mb-4" style="line-height: 1.7; font-size: 15px;">
          Founded with a mission to make local and long-distance travel effortless, TaxiGo provides 24/7 safe, transparent, and luxury-tier cab services for commuters, families, and corporate clients.
        </p>

        <a href="{{ $contactUrl }}" class="tx-btn tx-btn-yellow">
          Book Your Ride <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="col-lg-6">
        <div class="rounded-4 overflow-hidden shadow-lg border position-relative" style="height: 380px;">
          <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=800&auto=format&fit=crop" alt="TaxiGo Mobility" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== OUR MISSION / VISION / VALUES ===== -->
<section class="py-5" style="background: #ffffff;">
  <div class="tx-container py-4">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
          <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 text-dark" style="width: 48px; height: 48px; background: #FFF8E6; font-size: 20px;">
            <i class="fa-solid fa-bullseye" style="color: #945B00;"></i>
          </div>
          <h3 class="fw-bold fs-5 mb-2 text-dark">Our Mission</h3>
          <p class="text-muted small mb-0" style="line-height: 1.6;">
            To provide safe, reliable, and convenient rides for everyone, everywhere.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
          <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 text-dark" style="width: 48px; height: 48px; background: #FFF8E6; font-size: 20px;">
            <i class="fa-regular fa-eye" style="color: #945B00;"></i>
          </div>
          <h3 class="fw-bold fs-5 mb-2 text-dark">Our Vision</h3>
          <p class="text-muted small mb-0" style="line-height: 1.6;">
            To be the most trusted global mobility platform, connecting people and places seamlessly.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
          <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 text-dark" style="width: 48px; height: 48px; background: #FFF8E6; font-size: 20px;">
            <i class="fa-solid fa-gem" style="color: #945B00;"></i>
          </div>
          <h3 class="fw-bold fs-5 mb-2 text-dark">Our Values</h3>
          <p class="text-muted small mb-0" style="line-height: 1.6;">
            Customer first, safety & reliability, integrity, innovation, and sustainable mobility.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
