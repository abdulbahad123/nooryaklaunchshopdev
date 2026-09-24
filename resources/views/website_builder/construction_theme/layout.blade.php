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
  $blogUrl      = $subdomainParam ? route('website-builder.subdomain.blogs',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.blogs');

  $isHome      = request()->routeIs('website-builder.templates.construction')          || request()->routeIs('website-builder.subdomain.site');
  $isAbout     = request()->routeIs('website-builder.templates.construction.about')    || request()->routeIs('website-builder.subdomain.about');
  $isServices  = request()->routeIs('website-builder.templates.construction.services') || request()->routeIs('website-builder.subdomain.services');
  $isPortfolio = request()->routeIs('website-builder.templates.construction.portfolio')|| request()->routeIs('website-builder.subdomain.portfolio');
  $isContact   = request()->routeIs('website-builder.templates.construction.contact')  || request()->routeIs('website-builder.subdomain.contact');
  $isBlogs     = request()->routeIs('website-builder.templates.construction.blogs')    || request()->routeIs('website-builder.subdomain.blogs');
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
          $hLogo = !empty($agency->header_logo) ? $agency->header_logo : (!empty($agency->site_logo) ? $agency->site_logo : null);
          $hLogoSrc = $hLogo ? (str_starts_with($hLogo, 'http') ? $hLogo : asset(ltrim($hLogo, '/'))) : null;
        @endphp
        @if($hLogoSrc)
          <img src="{{ $hLogoSrc }}" alt="{{ $agency->site_title ?? 'BuildCraft' }}" class="cn-logo-img" style="max-height:60px; max-width:220px; object-fit:contain;">
        @else
          <div class="cn-logo-icon"><i class="fa-solid fa-helmet-safety"></i></div>
          <div class="cn-logo-text">
            Build<span>Craft</span>
          </div>
        @endif
      </a>

      {{-- Desktop Nav --}}
      @php
        $currentPath = request()->path();
        $currentRoute = request()->route() ? request()->route()->getName() : '';

        $isAbout = str_contains($currentRoute, '.about') || str_ends_with($currentPath, '/about');
        $isPortfolio = str_contains($currentRoute, '.portfolio') || str_ends_with($currentPath, '/portfolio') || str_ends_with($currentPath, '/projects') || str_ends_with($currentPath, '/events');
        $isContact = str_contains($currentRoute, '.contact') || str_ends_with($currentPath, '/contact');
        $isServices = str_contains($currentRoute, '.services') || str_ends_with($currentPath, '/services');
        $isBlogs = str_contains($currentRoute, '.blogs') || str_contains($currentRoute, '.blog') || str_ends_with($currentPath, '/blogs');
        $isHome = !$isAbout && !$isPortfolio && !$isContact && !$isServices && !$isBlogs;

        $defaultNav = [
          ['title' => 'Home', 'url' => $homeUrl],
          ['title' => 'About', 'url' => $aboutUrl],
          ['title' => 'Projects', 'url' => $portfolioUrl],
          ['title' => 'Blogs', 'url' => $blogUrl],
          ['title' => 'Contact', 'url' => $contactUrl],
        ];
        $navLinks = !empty($agency->header_nav_links) && is_array($agency->header_nav_links) ? $agency->header_nav_links : $defaultNav;

        $resolveNavUrl = function($targetUrl) use ($homeUrl, $aboutUrl, $servicesUrl, $portfolioUrl, $blogUrl, $contactUrl) {
          $u = strtolower(trim($targetUrl ?? ''));
          if (empty($u) || $u === 'home' || $u === '#') return $homeUrl;
          if ($u === 'about' || str_contains($u, 'about')) return $aboutUrl;
          if ($u === 'services' || str_contains($u, 'service')) return $servicesUrl;
          if ($u === 'portfolio' || $u === 'projects' || str_contains($u, 'portfolio') || str_contains($u, 'project')) return $portfolioUrl;
          if ($u === 'blogs' || $u === 'blog' || str_contains($u, 'blog')) return $blogUrl;
          if ($u === 'contact' || str_contains($u, 'contact')) return $contactUrl;
          if (str_starts_with($u, 'http') || str_starts_with($u, '/') || str_starts_with($u, '#')) return $targetUrl;
          return $homeUrl;
        };
      @endphp
      <nav>
        <ul class="cn-nav">
          @foreach($navLinks as $nl)
            @if(is_array($nl) && isset($nl['title']))
              @php
                $urlStr = strtolower($nl['url'] ?? '');
                $titleStr = strtolower($nl['title'] ?? '');
                $targetHref = $resolveNavUrl($nl['url'] ?? '');
                $isActive = false;
                if (($isAbout && (str_contains($urlStr, 'about') || str_contains($titleStr, 'about'))) ||
                    ($isPortfolio && (str_contains($urlStr, 'portfolio') || str_contains($urlStr, 'project') || str_contains($urlStr, 'event') || str_contains($titleStr, 'portfolio') || str_contains($titleStr, 'project') || str_contains($titleStr, 'event'))) ||
                    ($isContact && (str_contains($urlStr, 'contact') || str_contains($titleStr, 'contact'))) ||
                    ($isServices && (str_contains($urlStr, 'service') || str_contains($titleStr, 'service'))) ||
                    ($isBlogs && (str_contains($urlStr, 'blog') || str_contains($titleStr, 'blog'))) ||
                    ($isHome && ($urlStr === 'home' || $urlStr === '#' || str_contains($titleStr, 'home')))) {
                  $isActive = true;
                }
              @endphp
              <li><a href="{{ $targetHref }}" class="cn-nav-link {{ $isActive ? 'active' : '' }}">{{ $nl['title'] }}</a></li>
            @endif
          @endforeach
        </ul>
      </nav>

      {{-- CTA Button --}}
      <a href="{{ $agency->primary_btn_url ?? $contactUrl }}" class="cn-btn cn-btn-yellow d-none d-lg-inline-flex" style="padding:10px 22px; font-size:13px;">
        <i class="fa-solid fa-file-lines"></i> {{ $agency->primary_btn_text ?? 'Get Free Quote' }}
      </a>

      {{-- Hamburger --}}
      <button class="cn-hamburger" id="cn-hamburger-btn" aria-label="Toggle menu">
        <i class="fa-solid fa-bars" id="cn-ham-icon"></i>
      </button>
    </div>
  </div>

  {{-- Mobile Nav --}}
  <div class="cn-mobile-nav" id="cn-mobile-nav">
    @foreach($navLinks as $nl)
      @if(is_array($nl) && isset($nl['title']))
        @php
          $urlStr = strtolower($nl['url'] ?? '');
          $titleStr = strtolower($nl['title'] ?? '');
          $targetHref = $resolveNavUrl($nl['url'] ?? '');
          $isActive = false;
          if (($isAbout && (str_contains($urlStr, 'about') || str_contains($titleStr, 'about'))) ||
              ($isPortfolio && (str_contains($urlStr, 'portfolio') || str_contains($urlStr, 'project') || str_contains($urlStr, 'event') || str_contains($titleStr, 'portfolio') || str_contains($titleStr, 'project') || str_contains($titleStr, 'event'))) ||
              ($isContact && (str_contains($urlStr, 'contact') || str_contains($titleStr, 'contact'))) ||
              ($isServices && (str_contains($urlStr, 'service') || str_contains($titleStr, 'service'))) ||
              ($isHome && ($urlStr === 'home' || $urlStr === '#' || str_contains($titleStr, 'home')))) {
            $isActive = true;
          }
        @endphp
        <a href="{{ $targetHref }}" class="cn-mobile-nav-link {{ $isActive ? 'active' : '' }}">{{ $nl['title'] }}</a>
      @endif
    @endforeach
    <div style="padding:16px 24px;">
      <a href="{{ $agency->primary_btn_url ?? $contactUrl }}" class="cn-btn cn-btn-yellow" style="width:100%; justify-content:center; display:flex;">
        <i class="fa-solid fa-file-lines"></i> {{ $agency->primary_btn_text ?? 'Get Free Quote' }}
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
            $fLogo = !empty($agency->footer_logo) ? $agency->footer_logo : (!empty($agency->site_logo) ? $agency->site_logo : null);
            $fLogoSrc = $fLogo ? (str_starts_with($fLogo, 'http') ? $fLogo : asset(ltrim($fLogo, '/'))) : null;
          @endphp
          @if($fLogoSrc)
            <img src="{{ $fLogoSrc }}" alt="{{ $agency->site_title ?? 'BuildCraft' }}" class="cn-logo-img" style="max-height:55px; max-width:220px; object-fit:contain;">
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
            <li><a href="{{ $portfolioUrl }}">Projects</a></li>
            <li><a href="{{ $contactUrl }}">Contact</a></li>
          @endif
        </ul>
      </div>

      {{-- Our Services --}}
      <div>
        <div class="cn-footer-heading">Our Services</div>
        <ul class="cn-footer-links">
          @php $servicesList = $agency->services_data ?? []; @endphp
          @if(count($servicesList) > 0)
            @foreach(array_slice($servicesList, 0, 6) as $srv)
              <li><a href="{{ $servicesUrl }}">{{ $srv['title'] ?? '' }}</a></li>
            @endforeach
          @else
            <li><a href="{{ $servicesUrl }}">Residential Construction</a></li>
            <li><a href="{{ $servicesUrl }}">Commercial Buildings</a></li>
            <li><a href="{{ $servicesUrl }}">Infrastructure</a></li>
            <li><a href="{{ $servicesUrl }}">Renovation & Remodeling</a></li>
            <li><a href="{{ $servicesUrl }}">Project Management</a></li>
            <li><a href="{{ $servicesUrl }}">General Contracting</a></li>
          @endif
        </ul>
      </div>

      {{-- Support & Policies --}}
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
            return route('website-builder.templates.construction.policy', ['slug' => $slugClean]);
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
        <div class="cn-footer-heading">Legal & Policies</div>
        <ul class="cn-footer-links">
          @foreach($legalLinks as $llink)
            <li><a href="{{ $resolveLegalUrl($llink) }}">{{ $llink['title'] ?? '' }}</a></li>
          @endforeach
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
          <div class="cn-footer-contact-text">{!! nl2br(e($agency->working_hours ?? "Mon - Fri: 9AM - 6PM")) !!}</div>
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

// Task 2: Full Page Scroll Animations (Replays on Scroll Re-entry) & Stats Counter Animation
document.addEventListener('DOMContentLoaded', function(){
  var animTargets = document.querySelectorAll('section, .cn-section, .cn-hero-title, .cn-hero-badge, .cn-pill-badge, .cn-section-label, .cn-section-heading, .cn-service-card-ref, .cn-testimonial-ref-card, .cn-about-card, .cn-team-card, .cn-tst-card');

  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('cn-revealed', 'ic-revealed', 'ev-revealed', 'tx-revealed', 'agency-revealed');
          entry.target.style.opacity = '1';
        } else {
          entry.target.classList.remove('cn-revealed', 'ic-revealed', 'ev-revealed', 'tx-revealed', 'agency-revealed');
        }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -10px 0px' });

    animTargets.forEach(function(el, idx) {
      if (!el.classList.contains('cn-reveal') && !el.classList.contains('cn-reveal-left') && !el.classList.contains('cn-reveal-right') && !el.classList.contains('cn-reveal-zoom')) {
        if (idx % 3 === 0) el.classList.add('cn-reveal-left');
        else if (idx % 3 === 1) el.classList.add('cn-reveal-right');
        else el.classList.add('cn-reveal');
      }
      observer.observe(el);
    });

    setTimeout(function() {
      animTargets.forEach(function(el) { el.classList.add('cn-revealed', 'ic-revealed', 'ev-revealed', 'tx-revealed', 'agency-revealed'); el.style.opacity = '1'; });
    }, 1200);
  }

  // Counter Animation for Stats Numbers (Replays on Re-entry)
  var statNumbers = document.querySelectorAll('.cn-stat-num, .cn-dark-stat-num, .tx-counter-num, .cn-stat-number, .ic-stat-counter-num, .ev-counter-num, .agency-counter-num, [data-target]');
  var counterObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      var el = entry.target;
      if (!el.dataset.originalText) {
        el.dataset.originalText = (el.getAttribute('data-target') || el.innerText || '').trim();
      }
      var text = el.dataset.originalText;
      if (!text) return;
      var match = text.match(/(\d+)/);
      if (!match) return;

      var targetNum = parseInt(match[1], 10);
      var prefix = text.substring(0, match.index);
      var suffix = text.substring(match.index + match[0].length);

      if (entry.isIntersecting) {
        if (!el.dataset.animating) {
          el.dataset.animating = 'true';
          var count = 0;
          var duration = 1400;
          var steps = 30;
          var increment = Math.max(1, Math.ceil(targetNum / steps));
          var stepTime = Math.floor(duration / steps);
          if (el._timer) clearInterval(el._timer);
          el.innerText = prefix + '0' + suffix;
          el._timer = setInterval(function() {
            count += increment;
            if (count >= targetNum) {
              count = targetNum;
              clearInterval(el._timer);
              el.dataset.animating = '';
            }
            el.innerText = prefix + count + suffix;
          }, stepTime);
        }
      } else {
        if (el._timer) clearInterval(el._timer);
        el.dataset.animating = '';
        el.innerText = prefix + '0' + suffix;
      }
    });
  }, { threshold: 0.1 });

  statNumbers.forEach(function(el) {
    if (!el.dataset.originalText) {
      el.dataset.originalText = (el.getAttribute('data-target') || el.innerText || '').trim();
    }
    counterObserver.observe(el);
  });

  // Task 3: Auto & Manual Sliders for About Us & All Pages (<992px)
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

    if (prevBtn) {
      prevBtn.addEventListener('click', function(e) {
        e.preventDefault();
        stepPrev();
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function(e) {
        e.preventDefault();
        stepNext();
      });
    }

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

  // Bind slider tracks across all pages (excluding projects grid)
  document.querySelectorAll('.cn-services-grid-5, .cn-testimonials-grid, .cn-team-grid, .tx-mobile-slider, #teamSliderTrack, #tstSliderTrack').forEach(function(track) {
    var parent = track.closest('section') || track.parentElement;
    var prevBtn = parent ? parent.querySelector('.cn-nav-arrow[aria-label="Previous"], #srvPrevBtn, #teamPrevBtn, #tstPrevBtn') : null;
    var nextBtn = parent ? parent.querySelector('.cn-nav-arrow[aria-label="Next"], #srvNextBtn, #teamNextBtn, #tstNextBtn') : null;
    setupAutoSlider(track, prevBtn, nextBtn);
  });
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
