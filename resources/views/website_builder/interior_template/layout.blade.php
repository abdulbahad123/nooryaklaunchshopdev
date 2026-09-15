<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'InteriorCRAFT - Luxury Architecture & Interior Design')</title>
  <meta name="description" content="Transform your living and commercial spaces with bespoke interior design, architectural planning, and modern styling solutions.">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

  <!-- Bootstrap & FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Interior Theme Isolated Stylesheet -->
  <link rel="stylesheet" href="{{ asset('css/website_builder/interior_theme.css') }}">

  @yield('styles')
</head>
<body class="ic-body">

@php
  $interior = $interior ?? $agency ?? null;
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;

  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.portfolio');
@endphp

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-0 mb-0 py-3 text-center border-0 fw-bold fs-6 shadow-sm" style="background: var(--ic-secondary); color: #ffffff; z-index: 9999;">
  <i class="fa-solid fa-gem me-2"></i> {{ session('success') }}
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- TOP ANNOUNCEMENT BAR -->
<div class="py-2 d-none d-lg-block" style="background: var(--ic-secondary-dark); color: rgba(255,255,255,0.8); font-size: 13px;">
  <div class="ic-container">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <i class="fa-solid fa-compass me-1" style="color: var(--ic-primary-light);"></i>
        {{ $interior->top_announcement ?? 'Elevating Architecture & Bespoke Interior Design Worldwide' }}
      </div>
      <div class="d-flex align-items-center gap-4">
        <span><i class="fa-solid fa-envelope me-1" style="color: var(--ic-primary-light);"></i> <a href="mailto:{{ $interior->email ?? 'hello@interiorcraft.com' }}" style="color: rgba(255,255,255,0.85); text-decoration: none;">{{ $interior->email ?? 'hello@interiorcraft.com' }}</a></span>
        <span><i class="fa-solid fa-phone me-1" style="color: var(--ic-primary-light);"></i> {{ $interior->phone ?? '+1 (800) 456-7890' }}</span>
        <div class="d-flex gap-3 ms-2">
          <a href="{{ $interior->social_links['instagram'] ?? '#' }}" target="_blank" style="color: #fff;"><i class="fa-brands fa-instagram"></i></a>
          <a href="{{ $interior->social_links['pinterest'] ?? '#' }}" target="_blank" style="color: #fff;"><i class="fa-brands fa-pinterest-p"></i></a>
          <a href="{{ $interior->social_links['facebook'] ?? '#' }}" target="_blank" style="color: #fff;"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="{{ $interior->social_links['houzz'] ?? '#' }}" target="_blank" style="color: #fff;"><i class="fa-solid fa-house"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- HEADER / NAVIGATION -->
<header class="ic-header">
  <div class="ic-container">
    <div class="ic-header-inner">
      <a href="{{ $homeUrl }}" class="ic-logo">
        @php
          $logoType = $interior->logo_type ?? 'text';
          $siteTitle = $interior->site_title ?? 'InteriorCRAFT';
          $hasLogoImg = !empty($interior->site_logo);
        @endphp
        @if($logoType === 'image' && $hasLogoImg)
          @php $logoSrc = str_starts_with($interior->site_logo, 'http') ? $interior->site_logo : asset(ltrim($interior->site_logo, '/')); @endphp
          <img src="{{ $logoSrc }}" alt="{{ $siteTitle }}" style="max-height: 46px; object-fit: contain;">
        @else
          <div class="ic-logo-icon">
            <i class="fa-solid fa-couch"></i>
          </div>
          <span class="ic-logo-text">Interior<span>CRAFT</span></span>
        @endif
      </a>

      <ul class="ic-nav d-none d-lg-flex">
        <li><a href="{{ $homeUrl }}" class="ic-nav-link {{ request()->routeIs('website-builder.templates.interior') || request()->routeIs('website-builder.subdomain.site') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ $aboutUrl }}" class="ic-nav-link {{ request()->routeIs('website-builder.templates.interior.about') || request()->routeIs('website-builder.subdomain.about') ? 'active' : '' }}">About Us</a></li>
        <li><a href="{{ $portfolioUrl }}" class="ic-nav-link {{ request()->routeIs('website-builder.templates.interior.portfolio') || request()->routeIs('website-builder.subdomain.portfolio') ? 'active' : '' }}">Portfolio</a></li>
        <li><a href="{{ $contactUrl }}" class="ic-nav-link {{ request()->routeIs('website-builder.templates.interior.contact') || request()->routeIs('website-builder.subdomain.contact') ? 'active' : '' }}">Contact Us</a></li>
      </ul>

      <div class="d-none d-lg-flex align-items-center gap-3">
        <a href="{{ $contactUrl }}" class="ic-btn ic-btn-primary">
          Get Started <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <button class="ic-mobile-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#icMobileNav">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>
  </div>
</header>

<!-- MOBILE NAV OFFCANVAS -->
<div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="icMobileNav" style="width: 300px;">
  <div class="offcanvas-header border-bottom">
    <a href="{{ $homeUrl }}" class="ic-logo">
      <div class="ic-logo-icon" style="width: 34px; height: 34px; font-size: 16px;">
        <i class="fa-solid fa-couch"></i>
      </div>
      <span class="ic-logo-text" style="font-size: 20px;">Interior<span>CRAFT</span></span>
    </a>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between">
    <ul class="list-unstyled">
      <li class="py-2 border-bottom"><a href="{{ $homeUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">Home</a></li>
      <li class="py-2 border-bottom"><a href="{{ $aboutUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">About Us</a></li>
      <li class="py-2 border-bottom"><a href="{{ $portfolioUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">Portfolio</a></li>
      <li class="py-2 border-bottom"><a href="{{ $contactUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">Contact Us</a></li>
    </ul>

    <div class="pt-4 border-top">
      <a href="{{ $contactUrl }}" class="ic-btn ic-btn-primary w-100 mb-3">
        Get Started <i class="fa-solid fa-arrow-right"></i>
      </a>
      <div class="text-muted small">
        <div class="mb-1"><i class="fa-solid fa-envelope me-1"></i> {{ $interior->email ?? 'hello@interiorcraft.com' }}</div>
        <div><i class="fa-solid fa-phone me-1"></i> {{ $interior->phone ?? '+1 (800) 456-7890' }}</div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<main>
  @yield('content')
</main>

<!-- CALL TO ACTION BANNER -->
<div class="ic-container">
  <div class="ic-cta-banner">
    <span class="ic-sub-badge" style="background: rgba(255,255,255,0.15); color: #fff;">BEAUTIFUL INTERIORS AWAIT</span>
    <h2>{{ $interior->contact_title ?? 'Ready to Transform Your Space?' }}</h2>
    <p>{{ $interior->contact_subtitle ?? 'Schedule a complimentary interior design consultation with our lead architects today.' }}</p>
    <a href="{{ $contactUrl }}" class="ic-btn ic-btn-accent" style="background: #ffffff; color: var(--ic-secondary-dark);">
      Schedule Consultation <i class="fa-solid fa-calendar-check"></i>
    </a>
  </div>
</div>

<!-- FOOTER -->
<footer class="ic-footer">
  <div class="ic-container">
    <div class="ic-footer-grid">
      <!-- Col 1: Brand Info -->
      <div>
        <div class="ic-footer-logo">
          <i class="fa-solid fa-couch" style="color: var(--ic-primary-light);"></i>
          <span>Interior<span style="color: var(--ic-primary-light);">CRAFT</span></span>
        </div>
        <p class="ic-footer-desc">
          {{ $interior->footer_text ?? 'We curate luxury residential & commercial interiors tailored to your personality, combining timeless aesthetic with functional living.' }}
        </p>
        <div class="d-flex gap-3 fs-5">
          <a href="{{ $interior->social_links['instagram'] ?? '#' }}" style="color: rgba(255,255,255,0.7);"><i class="fa-brands fa-instagram"></i></a>
          <a href="{{ $interior->social_links['pinterest'] ?? '#' }}" style="color: rgba(255,255,255,0.7);"><i class="fa-brands fa-pinterest-p"></i></a>
          <a href="{{ $interior->social_links['facebook'] ?? '#' }}" style="color: rgba(255,255,255,0.7);"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="{{ $interior->social_links['linkedin'] ?? '#' }}" style="color: rgba(255,255,255,0.7);"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
      </div>

      <!-- Col 2: Navigation -->
      <div>
        <h4 class="ic-footer-col-title">Navigation</h4>
        <ul class="ic-footer-links">
          <li><a href="{{ $homeUrl }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}">About Us</a></li>
          <li><a href="{{ $portfolioUrl }}">Design Portfolio</a></li>
          <li><a href="{{ $contactUrl }}">Contact Us</a></li>
        </ul>
      </div>

      <!-- Col 3: Services -->
      <div>
        <h4 class="ic-footer-col-title">Our Services</h4>
        <ul class="ic-footer-links">
          <li><a href="{{ $homeUrl }}#services">Residential Design</a></li>
          <li><a href="{{ $homeUrl }}#services">Commercial Architecture</a></li>
          <li><a href="{{ $homeUrl }}#services">Space Planning</a></li>
          <li><a href="{{ $homeUrl }}#services">Custom Furniture & Styling</a></li>
          <li><a href="{{ $homeUrl }}#services">3D Visualization Renderings</a></li>
        </ul>
      </div>

      <!-- Col 4: Contact -->
      <div>
        <h4 class="ic-footer-col-title">Studio Location</h4>
        <div class="mb-2" style="font-size: 14px;"><i class="fa-solid fa-location-dot me-2" style="color: var(--ic-primary-light);"></i> 450 Design Avenue, Suite 800, New York, NY 10001</div>
        <div class="mb-2" style="font-size: 14px;"><i class="fa-solid fa-phone me-2" style="color: var(--ic-primary-light);"></i> {{ $interior->phone ?? '+1 (800) 456-7890' }}</div>
        <div class="mb-2" style="font-size: 14px;"><i class="fa-solid fa-envelope me-2" style="color: var(--ic-primary-light);"></i> {{ $interior->email ?? 'hello@interiorcraft.com' }}</div>
        <div style="font-size: 14px;"><i class="fa-solid fa-clock me-2" style="color: var(--ic-primary-light);"></i> Mon - Fri: 9:00 AM - 6:00 PM EST</div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="ic-footer-bottom">
      <div>&copy; {{ date('Y') }} InteriorCRAFT Studio. All rights reserved.</div>
      <div>Designed with elegance for discerning spaces.</div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
