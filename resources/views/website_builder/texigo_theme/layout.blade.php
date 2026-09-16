<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'TaxiGo - #1 Trusted Taxi & Cab Mobility Service')</title>
  <meta name="description" content="Reliable, Safe, Affordable taxi & cab service. Get where you need to go with comfort and peace of mind.">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap & FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- TaxiGo Isolated Stylesheet -->
  <link rel="stylesheet" href="{{ asset('css/website_builder/texigo_theme.css') }}">

  @yield('styles')
</head>
<body class="tx-body">

@php
  $agency = $agency ?? $interior ?? null;
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;

  $homeUrl      = $subdomainParam ? route('website-builder.subdomain.site',      ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.about');
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.portfolio');
  $servicesUrl  = $homeUrl . '#services';
  $fleetUrl     = $homeUrl . '#fleet';

  $isHome      = request()->routeIs('website-builder.templates.texigo')          || request()->routeIs('website-builder.subdomain.site');
  $isAbout     = request()->routeIs('website-builder.templates.texigo.about')    || request()->routeIs('website-builder.subdomain.about');
  $isPortfolio = request()->routeIs('website-builder.templates.texigo.portfolio')|| request()->routeIs('website-builder.subdomain.portfolio');
  $isContact   = request()->routeIs('website-builder.templates.texigo.contact')  || request()->routeIs('website-builder.subdomain.contact');
@endphp

@if(session('success'))
<div class="alert alert-warning alert-dismissible fade show rounded-0 mb-0 py-3 text-center border-0 fw-bold fs-6 shadow-sm" style="background: var(--tx-primary); color: #0D0F12; z-index: 9999;">
  <i class="fa-solid fa-taxi me-2"></i> {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- ============================================================
     TOP ANNOUNCEMENT BAR  (mirrors InteriorCRAFT topbar)
     Left: tagline pill + text | Right: email · phone · socials
     ============================================================ -->
<div class="tx-topbar d-none d-lg-block">
  <div class="tx-container">
    <div class="d-flex justify-content-between align-items-center">
      <!-- Left tagline -->
      <div class="d-flex align-items-center gap-2">
        <span class="tx-topbar-pill">
          <i class="fa-solid fa-taxi"></i> #1 Trusted Taxi Service
        </span>
        <span class="tx-topbar-tagline">Reliable, Safe &amp; Affordable Mobility Solutions</span>
      </div>
      <!-- Right: email · phone -->
      <div class="d-flex align-items-center gap-4">
        <a href="mailto:{{ $agency->email ?? 'hello@taxigo.com' }}" class="tx-topbar-link">
          <i class="fa-solid fa-envelope me-1"></i>{{ $agency->email ?? 'hello@taxigo.com' }}
        </a>
        <span class="tx-topbar-divider"></span>
        <a href="tel:{{ $agency->phone ?? '+1234567890' }}" class="tx-topbar-link">
          <i class="fa-solid fa-phone me-1"></i>{{ $agency->phone ?? '+1 (234) 567-890' }}
        </a>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================
     MAIN HEADER  (Logo left · Nav center · Phone+CTA right)
     ============================================================ -->
<header class="tx-header" id="txHeader">
  <div class="tx-container">
    <div class="tx-header-inner">

      <!-- Logo -->
      <a href="{{ $homeUrl }}" class="tx-logo">
        <span class="tx-logo-icon"><i class="fa-solid fa-taxi"></i></span>
        Taxi<span>Go</span>
      </a>

      <!-- Desktop Nav (centered via flex margin auto) -->
      <nav class="d-none d-lg-block">
        <ul class="tx-nav">
          <li><a href="{{ $homeUrl }}"      class="tx-nav-link {{ $isHome      ? 'active' : '' }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}"     class="tx-nav-link {{ $isAbout     ? 'active' : '' }}">About Us</a></li>
          <li><a href="{{ $portfolioUrl }}" class="tx-nav-link {{ $isPortfolio ? 'active' : '' }}">Portfolio</a></li>
          <li><a href="{{ $contactUrl }}"   class="tx-nav-link {{ $isContact   ? 'active' : '' }}">Contact Us</a></li>
        </ul>
      </nav>

      <!-- Right: Phone info + CTA -->
      <div class="d-none d-lg-flex align-items-center gap-3">
        <div class="tx-header-phone">
          <i class="fa-solid fa-phone tx-header-phone-icon"></i>
          <div>
            <div class="tx-header-phone-label">24/7 Support</div>
            <div class="tx-header-phone-num">{{ $agency->phone ?? '+1 (234) 567-890' }}</div>
          </div>
        </div>
        <a href="{{ $contactUrl }}" class="tx-btn tx-btn-yellow">
          Book a Ride <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>

      <!-- Mobile Hamburger -->
      <button class="tx-hamburger d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#txMobileNav" aria-label="Toggle navigation">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<!-- ============================================================
     MOBILE NAV OFFCANVAS
     ============================================================ -->
<div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="txMobileNav" style="width:300px;">
  <div class="offcanvas-header border-bottom py-3 px-4">
    <a href="{{ $homeUrl }}" class="tx-logo">
      <span class="tx-logo-icon" style="width:34px;height:34px;font-size:16px;"><i class="fa-solid fa-taxi"></i></span>
      Taxi<span>Go</span>
    </a>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between px-4">
    <ul class="list-unstyled mt-2">
      <li class="border-bottom py-3"><a href="{{ $homeUrl }}"      class="text-decoration-none fw-semibold text-dark fs-6 {{ $isHome      ? 'text-warning' : '' }}">Home</a></li>
      <li class="border-bottom py-3"><a href="{{ $aboutUrl }}"     class="text-decoration-none fw-semibold text-dark fs-6 {{ $isAbout     ? 'text-warning' : '' }}">About Us</a></li>
      <li class="border-bottom py-3"><a href="{{ $portfolioUrl }}" class="text-decoration-none fw-semibold text-dark fs-6 {{ $isPortfolio ? 'text-warning' : '' }}">Portfolio</a></li>
      <li class="border-bottom py-3"><a href="{{ $contactUrl }}"   class="text-decoration-none fw-semibold text-dark fs-6 {{ $isContact   ? 'text-warning' : '' }}">Contact Us</a></li>
    </ul>
    <div class="pt-4 border-top pb-4">
      <a href="{{ $contactUrl }}" class="tx-btn tx-btn-yellow w-100 mb-3 text-center">
        Book a Ride <i class="fa-solid fa-arrow-right ms-1"></i>
      </a>
      <div class="text-muted small mt-3">
        <div class="mb-1"><i class="fa-solid fa-phone me-2 text-warning"></i>{{ $agency->phone ?? '+1 (234) 567-890' }}</div>
        <div><i class="fa-solid fa-envelope me-2 text-warning"></i>{{ $agency->email ?? 'hello@taxigo.com' }}</div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<main>
  @yield('content')
</main>

<!-- ============================================================
     GLOBAL FOOTER CTA BANNER
     ============================================================ -->
@hasSection('no_cta')
  <!-- CTA Banner disabled for this page -->
@else
<div class="tx-container my-4">
  <div class="tx-cta-box-edge" style="background: url('{{ asset('assets/website_builder/Templates/Texigo_agency/footer_cta.png') }}') no-repeat center right / cover; min-height: 250px; border-radius: 24px; padding: 52px 60px; position: relative; color: #ffffff;">
    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(13,15,18,0.78) 45%, rgba(13,15,18,0.15) 100%); border-radius: 24px; z-index: 1;"></div>
    <div class="row align-items-center" style="position: relative; z-index: 2;">
      <div class="col-lg-7">
        <div class="text-uppercase fw-bold mb-2" style="color: var(--tx-primary); font-size: 12px; letter-spacing: 2px;">LET'S RIDE TOGETHER</div>
        <h2 class="fw-extrabold mb-3 text-white" style="font-size: clamp(28px, 4vw, 46px); font-family: var(--tx-font-heading); font-weight: 800; line-height: 1.1;">
          Ready to Book Your <span style="color: var(--tx-primary);">Next Ride?</span>
        </h2>
        <p class="mb-4" style="color: rgba(255,255,255,0.7); font-size: 15px; max-width: 440px;">
          Safe Rides. Happy Journeys. Always.
        </p>
        <a href="{{ $contactUrl }}" class="tx-btn tx-btn-yellow px-5 py-3 fw-bold fs-6">
          Book Now <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
        <!-- Cursive script watermark -->
        <div style="position: absolute; right: 60px; top: 50%; transform: translateY(-50%); font-family: var(--tx-font-cursive); font-size: 30px; color: rgba(255,255,255,0.5); line-height: 1.3; pointer-events: none; z-index: 2; white-space: nowrap;">
          Always<br>On Your Way
        </div>
      </div>
    </div>
  </div>
</div>
@endif

<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer class="tx-footer">
  <div class="tx-container">
    <div class="tx-footer-grid">
      <!-- Col 1: Brand -->
      <div>
        <div class="tx-logo mb-3" style="color:#fff;">
          <span class="tx-logo-icon" style="font-size:18px;width:38px;height:38px;"><i class="fa-solid fa-taxi"></i></span>
          Taxi<span style="color:var(--tx-primary);">Go</span>
        </div>
        <p class="text-white-50 small mb-4" style="line-height:1.65; max-width:280px;">
          {{ $agency->footer_text ?? 'Providing safe, reliable, and comfortable transportation for everyone, anytime, anywhere.' }}
        </p>
        <div class="d-flex gap-2">
          <a href="#" class="tx-social-icon"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="tx-social-icon"><i class="fa-brands fa-twitter"></i></a>
          <a href="#" class="tx-social-icon"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="tx-social-icon"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div>
        <h4 class="tx-footer-heading">Quick Links</h4>
        <ul class="tx-footer-list">
          <li><a href="{{ $homeUrl }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}">About Us</a></li>
          <li><a href="{{ $servicesUrl }}">Services</a></li>
          <li><a href="{{ $fleetUrl }}">Our Fleet</a></li>
          <li><a href="{{ $contactUrl }}">Contact Us</a></li>
        </ul>
      </div>

      <!-- Col 3: Services -->
      <div>
        <h4 class="tx-footer-heading">Our Services</h4>
        <ul class="tx-footer-list">
          <li><a href="{{ $servicesUrl }}">City Rides</a></li>
          <li><a href="{{ $servicesUrl }}">Airport Transfers</a></li>
          <li><a href="{{ $servicesUrl }}">Outstation Trips</a></li>
          <li><a href="{{ $servicesUrl }}">Corporate Travel</a></li>
          <li><a href="{{ $servicesUrl }}">Parcel Delivery</a></li>
        </ul>
      </div>

      <!-- Col 4: Support -->
      <div>
        <h4 class="tx-footer-heading">Support</h4>
        <ul class="tx-footer-list">
          <li><a href="{{ $homeUrl }}">Privacy Policy</a></li>
          <li><a href="{{ $homeUrl }}">Terms &amp; Conditions</a></li>
          <li><a href="{{ $homeUrl }}">FAQs</a></li>
          <li><a href="{{ $homeUrl }}">Disclaimer</a></li>
          <li><a href="{{ $homeUrl }}">Refund Policy</a></li>
        </ul>
      </div>

      <!-- Col 5: Contact -->
      <div>
        <h4 class="tx-footer-heading">Contact Us</h4>
        <div class="d-flex align-items-start gap-2 mb-2 text-white-50 small">
          <i class="fa-solid fa-location-dot text-warning mt-1"></i>
          <div>{{ $agency->address ?? '123 Mobility Way, City Center, NY 10001' }}</div>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2 text-white-50 small">
          <i class="fa-solid fa-phone text-warning"></i>
          <div>{{ $agency->phone ?? '+1 (234) 567-890' }}</div>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2 text-white-50 small">
          <i class="fa-solid fa-envelope text-warning"></i>
          <div>{{ $agency->email ?? 'hello@taxigo.com' }}</div>
        </div>
        <div class="d-flex align-items-center gap-2 text-white-50 small">
          <i class="fa-solid fa-clock text-warning"></i>
          <div>24/7 Mobility Support</div>
        </div>
      </div>
    </div>

    <!-- Copyright Bar -->
    <div class="pt-4 border-top border-secondary d-flex flex-column flex-md-row justify-content-between align-items-center small text-white-50">
      <div>&copy; {{ date('Y') }} TaxiGo. All rights reserved.</div>
      <div>Reliable, Safe &amp; Affordable Mobility Solutions. <i class="fa-solid fa-taxi text-warning ms-1"></i></div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Scroll reveal animation
    const animTargets = document.querySelectorAll('section, .tx-cta-box-edge, .card, .tx-card, .tx-stat-item, .tx-heading, .tx-pill-badge');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('tx-revealed');
        } else {
          entry.target.classList.remove('tx-revealed');
        }
      });
    }, { threshold: 0.10, rootMargin: '0px 0px -20px 0px' });

    animTargets.forEach((el) => {
      if (!el.classList.contains('tx-reveal')) el.classList.add('tx-reveal');
      observer.observe(el);
    });

    // Running counter animation
    function animateCounter(el) {
      const targetText = (el.getAttribute('data-target') || el.innerText || '').trim();
      if (!targetText || el.dataset.animating === 'true') return;
      const match = targetText.match(/^([^\d]*)([\d.]+)(.*)$/);
      if (!match) return;
      const prefix = match[1] || '';
      const numericValue = parseFloat(match[2]);
      const suffix = match[3] || '';
      if (isNaN(numericValue)) return;
      el.dataset.animating = 'true';
      let current = 0;
      const duration = 1400;
      const stepTime = 30;
      const steps = duration / stepTime;
      const increment = numericValue / steps;
      const timer = setInterval(() => {
        current += increment;
        if (current >= numericValue) {
          el.innerText = prefix + Math.round(numericValue) + suffix;
          clearInterval(timer);
          el.dataset.animating = 'false';
        } else {
          el.innerText = prefix + Math.round(current) + suffix;
        }
      }, stepTime);
    }

    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => { if (entry.isIntersecting) animateCounter(entry.target); });
    }, { threshold: 0.2 });
    document.querySelectorAll('.tx-counter-num').forEach(el => counterObserver.observe(el));

    // Sticky header shadow on scroll
    const header = document.getElementById('txHeader');
    if (header) {
      window.addEventListener('scroll', function() {
        if (window.scrollY > 40) {
          header.classList.add('tx-header-scrolled');
        } else {
          header.classList.remove('tx-header-scrolled');
        }
      });
    }
  });
</script>
@yield('scripts')
</body>
</html>
