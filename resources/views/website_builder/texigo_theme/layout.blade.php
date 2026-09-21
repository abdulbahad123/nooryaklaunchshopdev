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
      <a href="{{ $homeUrl }}" class="tx-logo-img-wrap">
        <img src="{{ asset('assets/website_builder/Templates/Texigo_agency/header_logo.png') }}" alt="TaxiGo" class="tx-header-logo-img">
      </a>

      <!-- Desktop Nav (centered via flex margin auto) -->
      @php
        $currentPath = request()->path();
        $currentRoute = request()->route() ? request()->route()->getName() : '';

        $isAbout = str_contains($currentRoute, '.about') || str_ends_with($currentPath, '/about');
        $isPortfolio = str_contains($currentRoute, '.portfolio') || str_ends_with($currentPath, '/portfolio') || str_ends_with($currentPath, '/projects') || str_ends_with($currentPath, '/events');
        $isContact = str_contains($currentRoute, '.contact') || str_ends_with($currentPath, '/contact');
        $isServices = str_contains($currentRoute, '.services') || str_ends_with($currentPath, '/services');
        $isHome = !$isAbout && !$isPortfolio && !$isContact && !$isServices;

        $defaultNav = [
          ['title' => 'Home', 'url' => $homeUrl],
          ['title' => 'About Us', 'url' => $aboutUrl],
          ['title' => 'Portfolio', 'url' => $portfolioUrl],
          ['title' => 'Contact Us', 'url' => $contactUrl],
        ];
        $navLinks = !empty($agency->header_nav_links) && is_array($agency->header_nav_links) ? $agency->header_nav_links : $defaultNav;
      @endphp
      <nav class="d-none d-lg-block">
        <ul class="tx-nav">
          @foreach($navLinks as $nl)
            @if(is_array($nl) && isset($nl['title']))
              @php
                $urlStr = strtolower($nl['url'] ?? '');
                $titleStr = strtolower($nl['title'] ?? '');
                $isActive = false;
                if (($isAbout && (str_contains($urlStr, 'about') || str_contains($titleStr, 'about'))) ||
                    ($isPortfolio && (str_contains($urlStr, 'portfolio') || str_contains($urlStr, 'project') || str_contains($urlStr, 'event') || str_contains($titleStr, 'portfolio') || str_contains($titleStr, 'project') || str_contains($titleStr, 'event'))) ||
                    ($isContact && (str_contains($urlStr, 'contact') || str_contains($titleStr, 'contact'))) ||
                    ($isServices && (str_contains($urlStr, 'service') || str_contains($titleStr, 'service'))) ||
                    ($isHome && ($urlStr === 'home' || $urlStr === '#' || str_contains($titleStr, 'home')))) {
                  $isActive = true;
                }
              @endphp
              <li><a href="{{ $nl['url'] ?? '#' }}" class="tx-nav-link {{ $isActive ? 'active' : '' }}">{{ $nl['title'] }}</a></li>
            @endif
          @endforeach
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
        <a href="{{ $agency->primary_btn_url ?? $contactUrl }}" class="tx-btn tx-btn-yellow">
          {{ $agency->primary_btn_text ?? 'Book a Ride' }} <i class="fa-solid fa-arrow-right ms-1"></i>
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
    <a href="{{ $homeUrl }}" class="tx-logo-img-wrap">
      <img src="{{ asset('assets/website_builder/Templates/Texigo_agency/header_logo.png') }}" alt="TaxiGo" class="tx-header-logo-img" style="height:36px;">
    </a>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between px-4">
    <ul class="list-unstyled mt-2">
      <li class="border-bottom py-3"><a href="{{ $homeUrl }}"      class="text-decoration-none fw-semibold text-dark fs-6 {{ $isHome      ? 'text-warning' : '' }}">{{ $navLinks['home'] ?? 'Home' }}</a></li>
      <li class="border-bottom py-3"><a href="{{ $aboutUrl }}"     class="text-decoration-none fw-semibold text-dark fs-6 {{ $isAbout     ? 'text-warning' : '' }}">{{ $navLinks['about'] ?? 'About Us' }}</a></li>
      <li class="border-bottom py-3"><a href="{{ $portfolioUrl }}" class="text-decoration-none fw-semibold text-dark fs-6 {{ $isPortfolio ? 'text-warning' : '' }}">{{ $navLinks['portfolio'] ?? 'Portfolio' }}</a></li>
      <li class="border-bottom py-3"><a href="{{ $contactUrl }}"   class="text-decoration-none fw-semibold text-dark fs-6 {{ $isContact   ? 'text-warning' : '' }}">{{ $navLinks['contact'] ?? 'Contact Us' }}</a></li>
    </ul>
    <div class="pt-4 border-top pb-4">
      <a href="{{ $agency->primary_btn_url ?? $contactUrl }}" class="tx-btn tx-btn-yellow w-100 mb-3 text-center">
        {{ $agency->primary_btn_text ?? 'Book a Ride' }} <i class="fa-solid fa-arrow-right ms-1"></i>
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
@php
  $ctaBg = !empty($agency->cta_banner_image) ? (str_starts_with($agency->cta_banner_image, 'http') ? $agency->cta_banner_image : asset(ltrim($agency->cta_banner_image, '/'))) : asset('assets/website_builder/Templates/Texigo_agency/footer_cta.png');
@endphp
<div class="tx-container my-4">
  <div class="tx-cta-box-edge" style="background: url('{{ $ctaBg }}') no-repeat center right / cover; min-height: 250px; border-radius: 24px; padding: 44px 48px; position: relative; color: #ffffff; overflow: hidden;">
    <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(13,15,18,0.85) 0%, rgba(13,15,18,0.70) 100%); border-radius: inherit; z-index: 1;"></div>
    <div class="row align-items-center" style="position: relative; z-index: 2;">
      <div class="col-lg-8">
        <div class="text-uppercase fw-bold mb-2" style="color: var(--tx-primary); font-size: 12px; letter-spacing: 2px;">{{ $agency->cta_banner_badge ?? "LET'S RIDE TOGETHER" }}</div>
        <h2 class="fw-extrabold mb-3 text-white" style="font-size: clamp(26px, 3.8vw, 42px); font-family: var(--tx-font-heading); font-weight: 800; line-height: 1.15;">
          {!! nl2br(e($agency->cta_banner_title ?? "Ready to Book Your Next Ride?")) !!}
        </h2>
        <p class="mb-4" style="color: rgba(255,255,255,0.75); font-size: 15px; max-width: 440px;">
          {{ $agency->cta_banner_subtitle ?? 'Safe Rides. Happy Journeys. Always.' }}
        </p>
        <a href="{{ $agency->cta_banner_btn_url ?? $contactUrl }}" class="tx-btn tx-btn-yellow px-4 py-3 fw-bold fs-6">
          {{ $agency->cta_banner_btn_text ?? 'Book Now' }} <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
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
        <div class="mb-4">
          <img src="{{ asset('assets/website_builder/Templates/Texigo_agency/footer_logo.png') }}" alt="TaxiGo" class="tx-footer-logo-img">
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
          @if(!empty($agency->footer_quick_links) && is_array($agency->footer_quick_links))
            @foreach($agency->footer_quick_links as $ql)
              @php
                $qTitle = $ql['title'] ?? '';
                $qUrlTarget = strtolower(trim($ql['url'] ?? ''));
                $qHref = $homeUrl;
                if ($qUrlTarget === 'about' || str_contains($qUrlTarget, 'about')) $qHref = $aboutUrl;
                elseif ($qUrlTarget === 'portfolio' || $qUrlTarget === 'projects' || str_contains($qUrlTarget, 'portfolio') || str_contains($qUrlTarget, 'project')) $qHref = $portfolioUrl;
                elseif ($qUrlTarget === 'contact' || str_contains($qUrlTarget, 'contact')) $qHref = $contactUrl;
                elseif (str_starts_with($qUrlTarget, 'http')) $qHref = $ql['url'];
              @endphp
              <li><a href="{{ $qHref }}">{{ $qTitle }}</a></li>
            @endforeach
          @else
            <li><a href="{{ $homeUrl }}">Home</a></li>
            <li><a href="{{ $aboutUrl }}">About Us</a></li>
            <li><a href="{{ $portfolioUrl }}">Portfolio</a></li>
            <li><a href="{{ $contactUrl }}">Contact Us</a></li>
          @endif
        </ul>
      </div>

      <!-- Col 3: Services -->
      <div>
        <h4 class="tx-footer-heading">Our Services</h4>
        <ul class="tx-footer-list">
          @php $servicesList = $agency->services_data ?? []; @endphp
          @if(count($servicesList) > 0)
            @foreach(array_slice($servicesList, 0, 5) as $srv)
              <li><a href="{{ $servicesUrl }}">{{ $srv['title'] ?? '' }}</a></li>
            @endforeach
          @else
            <li><a href="{{ $servicesUrl }}">City Rides</a></li>
            <li><a href="{{ $servicesUrl }}">Airport Transfers</a></li>
            <li><a href="{{ $servicesUrl }}">Outstation Trips</a></li>
            <li><a href="{{ $servicesUrl }}">Corporate Travel</a></li>
            <li><a href="{{ $servicesUrl }}">Parcel Delivery</a></li>
          @endif
        </ul>
      </div>

      <!-- Col 4: Support & Policies -->
      @php
        $resolveLegalUrl = function($l) use ($subdomainParam) {
          $title = $l['title'] ?? 'Policy';
          $slug = $l['slug'] ?? $l['url'] ?? \Illuminate\Support\Str::slug($title);
          $slugClean = strtolower(trim(ltrim($slug, '#/')));
          if (empty($slugClean) || $slugClean === 'privacy-policy') $slugClean = 'privacy';
          if ($slugClean === 'terms--conditions') $slugClean = 'terms';

          if ($subdomainParam) {
            return route('website-builder.subdomain.policy', ['subdomain' => $subdomainParam, 'slug' => $slugClean]);
          } else {
            return route('website-builder.templates.texigo.policy', ['slug' => $slugClean]);
          }
        };

        $defaultLegal = [
          ['title' => 'Privacy Policy',     'slug' => 'privacy'],
          ['title' => 'Terms & Conditions', 'slug' => 'terms'],
          ['title' => 'Disclaimer',         'slug' => 'disclaimer'],
          ['title' => 'Refund Policy',      'slug' => 'refund'],
        ];
        $legalLinks = (isset($agency) && !empty($agency->footer_legal_links)) ? $agency->footer_legal_links : $defaultLegal;
      @endphp
      <div>
        <h4 class="tx-footer-heading">Legal & Policies</h4>
        <ul class="tx-footer-list">
          @foreach($legalLinks as $llink)
            <li><a href="{{ $resolveLegalUrl($llink) }}">{{ $llink['title'] ?? '' }}</a></li>
          @endforeach
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
          <div>{!! nl2br(e($agency->working_hours ?? "24/7 Mobility Support")) !!}</div>
        </div>
      </div>
    </div>

    <!-- Copyright Bar -->
    <div class="pt-4 border-top border-secondary d-flex flex-column flex-md-row justify-content-between align-items-center small text-white-50">
      <div>&copy; {{ date('Y') }} {{ $agency->site_title ?? 'TaxiGo' }}. {{ $agency->copyright_text ?? 'All rights reserved.' }}</div>
      <div>Reliable, Safe &amp; Affordable Mobility Solutions. <i class="fa-solid fa-taxi text-warning ms-1"></i></div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Scroll reveal animation
    const animTargets = document.querySelectorAll('section, .tx-cta-box-edge, .card, .tx-card, .tx-stat-item, .tx-heading, .tx-pill-badge, .tx-mvv-card, .tx-fleet-card, .tx-portfolio-card');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('tx-revealed', 'ic-revealed', 'cn-revealed', 'ev-revealed', 'agency-revealed');
          entry.target.style.opacity = '1';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.05, rootMargin: '0px 0px -20px 0px' });

    animTargets.forEach((el, i) => {
      if (!el.classList.contains('tx-reveal') && !el.classList.contains('tx-reveal-left') && !el.classList.contains('tx-reveal-right')) {
        if (i % 3 === 0) el.classList.add('tx-reveal-left');
        else if (i % 3 === 1) el.classList.add('tx-reveal-right');
        else el.classList.add('tx-reveal');
      }
      observer.observe(el);
    });

    setTimeout(() => {
      animTargets.forEach(el => {
        el.classList.add('tx-revealed', 'ic-revealed', 'cn-revealed', 'ev-revealed', 'agency-revealed');
        el.style.opacity = '1';
      });
    }, 1200);

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
<script>
(function() {
  try {
    var rawData = localStorage.getItem('wb_pending_checkout_customer');
    var rawTmpl = localStorage.getItem('selected_wb_template');
    if (rawData) {
      var data = JSON.parse(rawData);
      if (rawTmpl && !data.template) data.template = rawTmpl;
      if (rawTmpl && !data.template_slug) data.template_slug = rawTmpl;
      if (data && (data.email || data.customer_email || data.subdomain)) {
        fetch('/checkout/client-sync', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify(data)
        })
        .then(function(res) { return res.json(); })
        .then(function(resData) {
          localStorage.removeItem('wb_pending_checkout_customer');
          localStorage.removeItem('selected_wb_template');
          if (resData && resData.success) {
            window.location.reload();
          }
        })
        .catch(function(err) {
          localStorage.removeItem('wb_pending_checkout_customer');
          localStorage.removeItem('selected_wb_template');
        });
      } else {
        localStorage.removeItem('wb_pending_checkout_customer');
        localStorage.removeItem('selected_wb_template');
      }
    } else if (rawTmpl) {
      localStorage.removeItem('selected_wb_template');
    }
  } catch(e) {}
})();
</script>

@yield('scripts')
</body>
</html>
