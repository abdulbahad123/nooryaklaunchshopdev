<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Evently - Events Beyond Expectations')</title>
  <meta name="description" content="From intimate gatherings to grand celebrations, we create unforgettable experiences tailored to your vision.">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Inter:wght@300;400;500;600;700;800&family=Dancing+Script:wght@600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Evently Theme CSS -->
  <link rel="stylesheet" href="{{ asset('css/website_builder/evently_theme.css') }}">

  @yield('styles')
</head>
<body class="ev-body">

@php
  $evData = $interior ?? $agency ?? null;
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;

  $homeUrl      = $subdomainParam ? route('website-builder.subdomain.site',      ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.about');
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.portfolio');

  $siteTitle    = $evData->site_title ?? 'Evently';
  $defaultLogo  = asset('assets/website_builder/Templates/Evently/logo.png');
  $logoSrc      = !empty($evData->site_logo)
    ? (str_starts_with($evData->site_logo, 'http') ? $evData->site_logo : asset(ltrim($evData->site_logo, '/')))
    : $defaultLogo;
@endphp

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-0 mb-0 py-3 text-center border-0 fw-bold shadow-sm" style="background: var(--ev-primary); color: #fff; z-index: 9999; font-size: 14px;">
  <i class="fa-solid fa-star me-2"></i> {{ session('success') }}
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- ===== HEADER ===== -->
<header class="ev-header">
  <div class="ev-container">
    <div class="ev-header-inner">

      <!-- Logo -->
      <a href="{{ $homeUrl }}" class="ev-logo" style="text-decoration: none;">
        <img src="{{ $logoSrc }}" alt="{{ $siteTitle }}" style="max-height: 48px; width: auto; object-fit: contain;" onerror="this.onerror=null; this.src='{{ asset('assets/website_builder/Templates/Evently/logo.png') }}';">
      </a>

      <!-- Desktop Nav -->
      <ul class="ev-nav d-none d-lg-flex">
        <li><a href="{{ $homeUrl }}"      class="ev-nav-link {{ request()->routeIs('website-builder.templates.evently') || request()->routeIs('website-builder.subdomain.site') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ $aboutUrl }}"     class="ev-nav-link {{ request()->routeIs('website-builder.templates.evently.about') || request()->routeIs('website-builder.subdomain.about') ? 'active' : '' }}">About Us</a></li>
        <li><a href="{{ $portfolioUrl }}" class="ev-nav-link {{ request()->routeIs('website-builder.templates.evently.portfolio') || request()->routeIs('website-builder.subdomain.portfolio') ? 'active' : '' }}">Events</a></li>
        <li><a href="{{ $contactUrl }}"   class="ev-nav-link {{ request()->routeIs('website-builder.templates.evently.contact') || request()->routeIs('website-builder.subdomain.contact') ? 'active' : '' }}">Contact</a></li>
      </ul>

      <!-- Desktop Right Actions -->
      <div class="ev-header-actions d-none d-lg-flex">
        <button class="ev-search-btn" type="button" aria-label="Search">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <a href="{{ $contactUrl }}" class="ev-btn ev-btn-primary" style="padding: 10px 22px; font-size: 13.5px;">
          Plan Your Event <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <!-- Mobile Toggle -->
      <button class="ev-mobile-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#evMobileNav" aria-label="Toggle navigation">
        <span class="ev-burger-span"></span>
        <span class="ev-burger-span"></span>
        <span class="ev-burger-span"></span>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Offcanvas Nav -->
<div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="evMobileNav" style="width: 300px;">
  <div class="offcanvas-header border-bottom py-3">
    <a href="{{ $homeUrl }}" class="ev-logo">
      <img src="{{ $logoSrc }}" alt="{{ $siteTitle }}" style="max-height: 40px; width: auto; object-fit: contain;" onerror="this.onerror=null; this.src='{{ asset('assets/website_builder/Templates/Evently/logo.png') }}';">
    </a>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between">
    <ul class="list-unstyled">
      <li class="py-2 border-bottom"><a href="{{ $homeUrl }}"      class="text-decoration-none fw-semibold text-dark fs-6">Home</a></li>
      <li class="py-2 border-bottom"><a href="{{ $aboutUrl }}"     class="text-decoration-none fw-semibold text-dark fs-6">About Us</a></li>
      <li class="py-2 border-bottom"><a href="{{ $portfolioUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">Events</a></li>
      <li class="py-2 border-bottom"><a href="{{ $contactUrl }}"   class="text-decoration-none fw-semibold text-dark fs-6">Contact</a></li>
    </ul>
    <div class="pt-4 border-top">
      <a href="{{ $contactUrl }}" class="ev-btn ev-btn-primary w-100 mb-3 justify-content-center">
        Plan Your Event <i class="fa-solid fa-arrow-right"></i>
      </a>
      <div class="text-muted small">
        <div class="mb-1"><i class="fa-solid fa-envelope me-1" style="color: var(--ev-primary);"></i> {{ $evData->email ?? 'hello@evently.com' }}</div>
        <div><i class="fa-solid fa-phone me-1" style="color: var(--ev-primary);"></i> {{ $evData->phone ?? '+1 (234) 567-890' }}</div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<main>
  @yield('content')
</main>

<!-- ===== COMMON FOOTER CTA BANNER ===== -->
@hasSection('no_cta')
  <!-- CTA disabled -->
@else
<section class="ev-full-bg-cta" style="background: url('{{ asset('assets/website_builder/Templates/Evently/event_cta_bg.png') }}') no-repeat center / cover;">
  <div class="ev-full-bg-cta-overlay"></div>
  <div class="ev-container" style="position: relative; z-index: 2;">
    <div class="row align-items-center">
      
      <!-- Left Content -->
      <div class="col-12 col-lg-8">
        <div class="ev-cta-gold-eyebrow">LET'S CREATE SOMETHING AMAZING</div>
        <h2 class="ev-cta-title-gold">
          Ready to Plan Your <span>Next Event?</span>
        </h2>
        <p class="ev-cta-sub-white">
          {{ $evData->cta_subtitle ?? 'From concept to celebration, we\'re here to make it extraordinary.' }}
        </p>

        <div class="d-flex align-items-center gap-4 flex-wrap">
          <a href="{{ $contactUrl }}" class="ev-btn ev-btn-primary" style="font-size: 15px; padding: 14px 32px;">
            Get a Free Consultation <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>

          <div class="ev-cta-gold-badges">
            <div class="ev-cta-gold-item">
              <div class="ev-cta-gold-icon"><i class="fa-solid fa-comments"></i></div>
              <span>Free Consultation</span>
            </div>
            <div class="ev-cta-gold-item">
              <div class="ev-cta-gold-icon"><i class="fa-solid fa-box-open"></i></div>
              <span>Custom Packages</span>
            </div>
            <div class="ev-cta-gold-item">
              <div class="ev-cta-gold-icon"><i class="fa-solid fa-headset"></i></div>
              <span>24/7 Support</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Script Accent -->
      <div class="col-12 col-lg-4 d-none d-lg-block text-end">
        <div class="ev-cta-script-right">
          Your Event<br>
          <span style="color: #F59E0B; text-decoration: underline;">Our Passion</span>
        </div>
      </div>

    </div>
  </div>
</section>
@endif

<!-- ===== FOOTER ===== -->
<footer class="ev-footer">
  <div class="ev-container">
    <div class="ev-footer-grid">
      <!-- Col 1: Brand -->
      <div>
        <div class="mb-3">
          <a href="{{ $homeUrl }}" class="d-inline-block">
            <img src="{{ $logoSrc }}" alt="{{ $siteTitle }}" style="max-height: 48px; width: auto; object-fit: contain;" onerror="this.onerror=null; this.src='{{ asset('assets/website_builder/Templates/Evently/logo.png') }}';">
          </a>
        </div>
        <p class="ev-footer-bio">
          {{ $evData->footer_text ?? 'We create unforgettable event experiences that bring people together and leave lasting memories.' }}
        </p>
        <div class="ev-footer-socials">
          <a href="{{ $evData->social_links['facebook']  ?? '#' }}" class="ev-social-icon" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="{{ $evData->social_links['instagram'] ?? '#' }}" class="ev-social-icon" target="_blank"><i class="fa-brands fa-instagram"></i></a>
          <a href="{{ $evData->social_links['linkedin']  ?? '#' }}" class="ev-social-icon" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="{{ $evData->social_links['youtube']   ?? '#' }}" class="ev-social-icon" target="_blank"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div>
        <h4 class="ev-footer-heading">Quick Links</h4>
        <ul class="ev-footer-list">
          <li><a href="{{ $homeUrl }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}">About Us</a></li>
          <li><a href="{{ $portfolioUrl }}">Our Events</a></li>
          <li><a href="{{ $contactUrl }}">Contact Us</a></li>
        </ul>
      </div>

      <!-- Col 3: Support -->
      <div>
        <h4 class="ev-footer-heading">Support</h4>
        <ul class="ev-footer-list">
          <li><a href="{{ $homeUrl }}">Privacy Policy</a></li>
          <li><a href="{{ $homeUrl }}">Terms & Conditions</a></li>
          <li><a href="{{ $homeUrl }}">FAQs</a></li>
          <li><a href="{{ $homeUrl }}">Disclaimer</a></li>
          <li><a href="{{ $homeUrl }}">Refund Policy</a></li>
        </ul>
      </div>

      <!-- Col 4: Contact -->
      <div>
        <h4 class="ev-footer-heading">Contact Us</h4>
        <div class="ev-footer-contact-row">
          <i class="fa-solid fa-location-dot"></i>
          <div>{{ $evData->address ?? '123 Event Avenue, City, CA 94043' }}</div>
        </div>
        <div class="ev-footer-contact-row">
          <i class="fa-solid fa-phone"></i>
          <div>{{ $evData->phone ?? '+1 (234) 567-890' }}</div>
        </div>
        <div class="ev-footer-contact-row">
          <i class="fa-solid fa-envelope"></i>
          <div>{{ $evData->email ?? 'hello@evently.com' }}</div>
        </div>
        <div class="ev-footer-contact-row">
          <i class="fa-solid fa-clock"></i>
          <div>Mon - Fri: 9AM - 7PM</div>
        </div>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="ev-footer-bottom">
      <div>&copy; {{ date('Y') }} {{ $siteTitle }}. All rights reserved.</div>
      <div>Events Beyond Expectations <i class="fa-solid fa-gem ms-1" style="color: var(--ev-primary);"></i></div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // ---- Scroll Reveal Animation ----
  const animTargets = document.querySelectorAll('section, .ev-cta-banner, .card, .ev-service-card, .ev-cat-card, .ev-testimonial-card, .ev-blog-card, .ev-project-card, .ev-info-card, .ev-why-feature, .ev-stat-circle');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('ev-revealed');
      }
    });
  }, { threshold: 0.05, rootMargin: '50px' });

  animTargets.forEach((el, i) => {
    if (!el.classList.contains('ev-reveal') && !el.classList.contains('ev-reveal-left') && !el.classList.contains('ev-reveal-right') && !el.classList.contains('ev-reveal-zoom')) {
      if (i % 4 === 0) el.classList.add('ev-reveal-left');
      else if (i % 4 === 1) el.classList.add('ev-reveal');
      else if (i % 4 === 2) el.classList.add('ev-reveal-right');
      else el.classList.add('ev-reveal-zoom');
    }
    observer.observe(el);
  });

  // Fallback: Reveal all elements after 500ms to guarantee no blank/hidden content on mobile iframe/views
  setTimeout(() => {
    animTargets.forEach(el => el.classList.add('ev-revealed'));
  }, 500);

  // ---- Counter Animation ----
  function animateCounter(el) {
    const raw = (el.getAttribute('data-target') || el.innerText || '').trim();
    if (!raw || el.dataset.animating === 'true') return;
    const match = raw.match(/^([^\d]*)([\d.]+)(.*)$/);
    if (!match) return;
    const prefix = match[1] || '';
    const num    = parseFloat(match[2]);
    const suffix = match[3] || '';
    if (isNaN(num)) return;
    el.dataset.animating = 'true';
    let cur = 0;
    const steps = 40;
    const inc = num / steps;
    const timer = setInterval(() => {
      cur += inc;
      if (cur >= num) {
        el.innerText = prefix + Math.round(num) + suffix;
        clearInterval(timer);
        el.dataset.animating = 'false';
      } else {
        el.innerText = prefix + Math.round(cur) + suffix;
      }
    }, 30);
  }

  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) animateCounter(entry.target);
    });
  }, { threshold: 0.3 });

  document.querySelectorAll('.ev-counter-num').forEach(el => counterObserver.observe(el));

  // ---- Mobile Sliders (auto + manual) ----
  document.querySelectorAll('.ev-mobile-slider').forEach(slider => {
    let timer;
    function startAuto() {
      timer = setInterval(() => {
        if (window.innerWidth < 992) {
          const maxL = slider.scrollWidth - slider.clientWidth;
          if (slider.scrollLeft >= maxL - 10) {
            slider.scrollTo({ left: 0, behavior: 'smooth' });
          } else {
            const card = slider.querySelector('[class*="col-"]');
            const w = card ? card.offsetWidth + 16 : 280;
            slider.scrollBy({ left: w, behavior: 'smooth' });
          }
        }
      }, 3500);
    }
    startAuto();
    slider.addEventListener('touchstart', () => clearInterval(timer), { passive: true });
    slider.addEventListener('touchend', startAuto, { passive: true });
  });

  // ---- FAQ Toggle ----
  document.querySelectorAll('.ev-faq-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const wasActive = this.classList.contains('active-faq');
      document.querySelectorAll('.ev-faq-btn').forEach(b => {
        b.classList.remove('active-faq');
        const icon = b.querySelector('i');
        if (icon) { icon.classList.remove('fa-minus'); icon.classList.add('fa-plus'); }
      });
      if (!wasActive) {
        this.classList.add('active-faq');
        const icon = this.querySelector('i');
        if (icon) { icon.classList.remove('fa-plus'); icon.classList.add('fa-minus'); }
      }
    });
  });
});
</script>
@yield('scripts')
</body>
</html>
