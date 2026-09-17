<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'BuildCraft Construction — Building Dreams, Shaping the Future')</title>
  <meta name="description" content="@yield('description', 'Premier construction company delivering residential, commercial, and industrial projects with quality, safety, and on-time execution.')">

  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Caveat:wght@600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  {{-- Bootstrap & FontAwesome --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  {{-- Construction Theme Stylesheet --}}
  <link rel="stylesheet" href="{{ asset('css/website_builder/construction_theme.css') }}">

  @yield('styles')
</head>
<body class="cn-body">

@php
  $agency = $agency ?? null;
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;

  $homeUrl      = $subdomainParam ? route('website-builder.subdomain.site',      ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.about');
  $servicesUrl  = $subdomainParam ? route('website-builder.subdomain.services',  ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.services');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.portfolio');
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.contact');

  $isHome      = request()->routeIs('website-builder.templates.construction')          || request()->routeIs('website-builder.subdomain.site');
  $isAbout     = request()->routeIs('website-builder.templates.construction.about')    || request()->routeIs('website-builder.subdomain.about');
  $isServices  = request()->routeIs('website-builder.templates.construction.services') || request()->routeIs('website-builder.subdomain.services');
  $isPortfolio = request()->routeIs('website-builder.templates.construction.portfolio')|| request()->routeIs('website-builder.subdomain.portfolio');
  $isContact   = request()->routeIs('website-builder.templates.construction.contact')  || request()->routeIs('website-builder.subdomain.contact');
@endphp

@if(session('success'))
<div style="background:var(--cn-primary);color:#fff;text-align:center;padding:12px 20px;font-weight:700;font-size:14px;position:relative;z-index:9999;">
  <i class="fa-solid fa-helmet-safety me-2"></i> {{ session('success') }}
  <button onclick="this.parentElement.remove()" style="background:none;border:none;color:#fff;float:right;cursor:pointer;font-size:18px;line-height:1;">&times;</button>
</div>
@endif

{{-- ============================================================
     TOPBAR — Desktop Only
     ============================================================ --}}
<div class="cn-topbar d-none d-lg-block">
  <div class="cn-container">
    <div class="d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-3">
        <span class="cn-topbar-pill"><i class="fa-solid fa-helmet-safety"></i> #1 Trusted Construction Company</span>
        <span class="cn-topbar-tagline">Quality. Safety. Innovation. On Time, Every Time.</span>
      </div>
      <div class="d-flex align-items-center gap-4">
        <a href="mailto:{{ $agency->email ?? 'hello@buildcraft.com' }}" class="cn-topbar-link">
          <i class="fa-solid fa-envelope me-1"></i>{{ $agency->email ?? 'hello@buildcraft.com' }}
        </a>
        <span class="cn-topbar-divider"></span>
        <a href="tel:{{ $agency->phone ?? '+18002845348' }}" class="cn-topbar-link">
          <i class="fa-solid fa-phone me-1"></i>{{ $agency->phone ?? '+1 (800) 284-5348' }}
        </a>
      </div>
    </div>
  </div>
</div>

{{-- ============================================================
     MAIN HEADER
     ============================================================ --}}
<header class="cn-header" id="cn-header">
  <div class="cn-container">
    <div class="cn-header-inner">

      {{-- Logo --}}
      <a href="{{ $homeUrl }}" class="cn-logo">
        @php
          $hLogo = !empty($agency->header_logo) ? $agency->header_logo : (!empty($agency->site_logo) ? $agency->site_logo : 'assets/website_builder/Templates/Construction_agency/header_logo.png');
        @endphp
        @if(file_exists(public_path($hLogo)) || str_starts_with($hLogo, 'assets/'))
          <img src="{{ asset($hLogo) }}" alt="{{ $agency->site_title ?? 'BuildCraft' }}" class="cn-logo-img">
        @else
          <div class="cn-logo-icon"><i class="fa-solid fa-helmet-safety"></i></div>
          <div class="cn-logo-text">
            Build<span>Craft</span>
          </div>
        @endif
      </a>

      {{-- Desktop Nav --}}
      <nav>
        <ul class="cn-nav">
          <li><a href="{{ $homeUrl }}"     class="cn-nav-link {{ $isHome      ? 'active' : '' }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}"    class="cn-nav-link {{ $isAbout     ? 'active' : '' }}">About</a></li>
          <li><a href="{{ $portfolioUrl }}"class="cn-nav-link {{ $isPortfolio ? 'active' : '' }}">Projects</a></li>
          <li><a href="{{ $contactUrl }}"  class="cn-nav-link {{ $isContact   ? 'active' : '' }}">Contact</a></li>
        </ul>
      </nav>

      {{-- CTA Button --}}
      <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow d-none d-lg-inline-flex" style="padding:10px 22px; font-size:13px;">
        <i class="fa-solid fa-file-lines"></i> Get Free Quote
      </a>

      {{-- Hamburger --}}
      <button class="cn-hamburger" id="cn-hamburger-btn" aria-label="Toggle menu">
        <i class="fa-solid fa-bars" id="cn-ham-icon"></i>
      </button>
    </div>
  </div>

  {{-- Mobile Nav --}}
  <div class="cn-mobile-nav" id="cn-mobile-nav">
    <a href="{{ $homeUrl }}"      class="cn-mobile-nav-link {{ $isHome      ? 'active' : '' }}">Home</a>
    <a href="{{ $aboutUrl }}"     class="cn-mobile-nav-link {{ $isAbout     ? 'active' : '' }}">About</a>
    <a href="{{ $portfolioUrl }}" class="cn-mobile-nav-link {{ $isPortfolio ? 'active' : '' }}">Projects</a>
    <a href="{{ $contactUrl }}"   class="cn-mobile-nav-link {{ $isContact   ? 'active' : '' }}">Contact</a>
    <div style="padding:16px 24px;">
      <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow" style="width:100%; justify-content:center; display:flex;">
        <i class="fa-solid fa-file-lines"></i> Get Free Quote
      </a>
    </div>
  </div>
</header>

{{-- Page Content --}}
@yield('content')

{{-- ============================================================
     FOOTER
     ============================================================ --}}
<footer class="cn-footer">
  <div class="cn-container">
    <div class="cn-footer-grid">

      {{-- Brand Col --}}
      <div class="cn-footer-col-brand">
        <a href="{{ $homeUrl }}" class="cn-logo mb-3" style="display:inline-flex;">
          @php
            $fLogo = !empty($agency->footer_logo) ? $agency->footer_logo : (!empty($agency->site_logo) ? $agency->site_logo : 'assets/website_builder/Templates/Construction_agency/footer_logo.png');
          @endphp
          @if(file_exists(public_path($fLogo)) || str_starts_with($fLogo, 'assets/'))
            <img src="{{ asset($fLogo) }}" alt="{{ $agency->site_title ?? 'BuildCraft' }}" class="cn-logo-img" style="height:44px;">
          @else
            <div class="cn-logo-icon"><i class="fa-solid fa-helmet-safety"></i></div>
            <div class="cn-logo-text">Build<span>Craft</span></div>
          @endif
        </a>
        <p class="cn-footer-brand-desc">{{ $agency->footer_text ?? 'We create spaces that inspire, strengthen communities, and build a brighter tomorrow.' }}</p>
        <div class="cn-footer-social">
          @php $social = $agency->social_links ?? []; @endphp
          <a href="{{ $social['facebook']  ?? '#' }}" class="cn-footer-social-btn" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="{{ $social['twitter']   ?? '#' }}" class="cn-footer-social-btn" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
          <a href="{{ $social['linkedin']  ?? '#' }}" class="cn-footer-social-btn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
          <a href="{{ $social['instagram'] ?? '#' }}" class="cn-footer-social-btn" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="{{ $social['youtube']   ?? '#' }}" class="cn-footer-social-btn" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
      </div>

      {{-- Quick Links --}}
      <div>
        <div class="cn-footer-heading">Quick Links</div>
        <ul class="cn-footer-links">
          <li><a href="{{ $homeUrl }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}">About Us</a></li>
          <li><a href="{{ $portfolioUrl }}">Projects</a></li>
          <li><a href="{{ $contactUrl }}">Contact</a></li>
        </ul>
      </div>

      {{-- Our Services --}}
      <div>
        <div class="cn-footer-heading">Our Services</div>
        <ul class="cn-footer-links">
          <li><a href="{{ $servicesUrl }}">Residential Construction</a></li>
          <li><a href="{{ $servicesUrl }}">Commercial Buildings</a></li>
          <li><a href="{{ $servicesUrl }}">Infrastructure</a></li>
          <li><a href="{{ $servicesUrl }}">Renovation & Remodeling</a></li>
          <li><a href="{{ $servicesUrl }}">Project Management</a></li>
          <li><a href="{{ $servicesUrl }}">General Contracting</a></li>
        </ul>
      </div>

      {{-- Support --}}
      <div>
        <div class="cn-footer-heading">Support</div>
        <ul class="cn-footer-links">
          <li><a href="#">FAQs</a></li>
          <li><a href="#">Terms & Conditions</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Disclaimer</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Sitemap</a></li>
        </ul>
      </div>

      {{-- Contact Us --}}
      <div>
        <div class="cn-footer-heading">Contact Us</div>
        <div class="cn-footer-contact-item">
          <i class="fa-solid fa-location-dot cn-footer-contact-icon"></i>
          <div class="cn-footer-contact-text">{{ $agency->address ?? '123 Construction Avenue, New York, NY 10001' }}</div>
        </div>
        <div class="cn-footer-contact-item">
          <i class="fa-solid fa-phone cn-footer-contact-icon"></i>
          <div class="cn-footer-contact-text"><a href="tel:{{ $agency->phone ?? '+1234567890' }}">{{ $agency->phone ?? '+1 (234) 567-890' }}</a></div>
        </div>
        <div class="cn-footer-contact-item">
          <i class="fa-solid fa-envelope cn-footer-contact-icon"></i>
          <div class="cn-footer-contact-text"><a href="mailto:{{ $agency->email ?? 'info@buildcraft.com' }}">{{ $agency->email ?? 'info@buildcraft.com' }}</a></div>
        </div>
        <div class="cn-footer-contact-item">
          <i class="fa-solid fa-clock cn-footer-contact-icon"></i>
          <div class="cn-footer-contact-text">Mon - Fri: 9AM - 6PM</div>
        </div>
      </div>
    </div>

    <div class="cn-footer-bottom">
      <div class="cn-footer-copy">
        &copy; {{ date('Y') }} {{ $agency->site_title ?? 'BuildCraft' }}. All rights reserved.
      </div>
      <div class="cn-footer-copy text-end">
        Building Today for a Better Tomorrow. <span class="cn-footer-yellow-line"></span>
      </div>
    </div>
  </div>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Mobile nav toggle with smooth slide
(function(){
  var btn = document.getElementById('cn-hamburger-btn');
  var nav = document.getElementById('cn-mobile-nav');
  var ico = document.getElementById('cn-ham-icon');
  if(btn && nav){
    btn.addEventListener('click', function(e){
      e.stopPropagation();
      nav.classList.toggle('open');
      if (ico) {
        ico.className = nav.classList.contains('open') ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
      }
    });
  }
})();

// Filter tabs for projects grid
document.querySelectorAll('.cn-filter-tab').forEach(function(tab){
  tab.addEventListener('click', function(){
    document.querySelectorAll('.cn-filter-tab').forEach(function(t){ t.classList.remove('active'); });
    tab.classList.add('active');
    var cat = tab.dataset.cat;
    document.querySelectorAll('.cn-project-card').forEach(function(card){
      if(cat === 'all' || card.dataset.cat === cat){
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });
  });
});

// Task 6: Scroll Animations & Counter Animation for Stats Numbers
document.addEventListener('DOMContentLoaded', function(){
  // IntersectionObserver for continuous scroll animations
  var animElements = document.querySelectorAll('.cn-animate, .cn-section-label, .cn-section-heading, .cn-service-card-ref, .cn-project-card-ref, .cn-testimonial-ref-card, .cn-about-card, .cn-team-card');
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('cn-animate');
          entry.target.style.opacity = '1';
        }
      });
    }, { threshold: 0.1 });

    animElements.forEach(function(el) {
      observer.observe(el);
    });
  }

  // Counter Animation for Stats Numbers
  var statNumbers = document.querySelectorAll('.cn-stat-num, .cn-dark-stat-num, .tx-counter-num, .cn-stat-number');
  var counterObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting && !entry.target.dataset.counted) {
        entry.target.dataset.counted = 'true';
        var el = entry.target;
        var text = el.innerText.trim();
        var match = text.match(/(\d+)/);
        if (match) {
          var targetNum = parseInt(match[1], 10);
          var prefix = text.substring(0, match.index);
          var suffix = text.substring(match.index + match[0].length);
          var count = 0;
          var duration = 1600;
          var stepTime = Math.max(16, Math.floor(duration / targetNum));
          var timer = setInterval(function() {
            count += Math.max(1, Math.ceil(targetNum / 35));
            if (count >= targetNum) {
              count = targetNum;
              clearInterval(timer);
            }
            el.innerText = prefix + count + suffix;
          }, stepTime);
        }
      }
    });
  }, { threshold: 0.3 });

  statNumbers.forEach(function(el) {
    counterObserver.observe(el);
  });

  // Task 4: Auto & Manual Sliders for Mobile/Tablet (<992px)
  function setupAutoSlider(trackEl, prevBtn, nextBtn) {
    if (!trackEl) return;
    var autoTimer;

    function stepNext() {
      var cardWidth = trackEl.firstElementChild ? trackEl.firstElementChild.clientWidth : 280;
      var maxScroll = trackEl.scrollWidth - trackEl.clientWidth;
      if (maxScroll <= 10) return;
      if (trackEl.scrollLeft >= maxScroll - 15) {
        trackEl.scrollTo({ left: 0, behavior: 'smooth' });
      } else {
        trackEl.scrollBy({ left: cardWidth, behavior: 'smooth' });
      }
    }

    function stepPrev() {
      var cardWidth = trackEl.firstElementChild ? trackEl.firstElementChild.clientWidth : 280;
      trackEl.scrollBy({ left: -cardWidth, behavior: 'smooth' });
    }

    if (prevBtn) prevBtn.addEventListener('click', stepPrev);
    if (nextBtn) nextBtn.addEventListener('click', stepNext);

    function startTimer() {
      if (window.innerWidth <= 991) {
        autoTimer = setInterval(stepNext, 3500);
      }
    }
    function stopTimer() {
      clearInterval(autoTimer);
    }

    startTimer();
    trackEl.addEventListener('mouseenter', stopTimer);
    trackEl.addEventListener('mouseleave', startTimer);
    trackEl.addEventListener('touchstart', stopTimer, { passive: true });
    trackEl.addEventListener('touchend', startTimer, { passive: true });
  }

  // Bind slider tracks across all pages
  document.querySelectorAll('.cn-services-grid-5, .cn-projects-grid-5, .cn-testimonials-grid, .cn-team-grid, .tx-mobile-slider').forEach(function(track) {
    var parent = track.closest('section') || track.parentElement;
    var prevBtn = parent ? parent.querySelector('.cn-nav-arrow[aria-label="Previous"], #srvPrevBtn, #teamPrevBtn, #tstPrevBtn') : null;
    var nextBtn = parent ? parent.querySelector('.cn-nav-arrow[aria-label="Next"], #srvNextBtn, #teamNextBtn, #tstNextBtn') : null;
    setupAutoSlider(track, prevBtn, nextBtn);
  });
});
</script>

@yield('scripts')
</body>
</html>
