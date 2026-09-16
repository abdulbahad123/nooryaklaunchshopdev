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
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
        @if(!empty($agency->header_logo) || !empty($agency->site_logo))
          <img src="{{ asset($agency->header_logo ?: $agency->site_logo) }}" alt="{{ $agency->site_title ?? 'BuildCraft' }}" class="cn-logo-img">
        @else
          <div class="cn-logo-icon"><i class="fa-solid fa-helmet-safety"></i></div>
          <div class="cn-logo-text">
            {{ $agency->site_title ?? 'Build<span>Craft</span>' }}
          </div>
        @endif
      </a>

      {{-- Desktop Nav --}}
      <nav>
        <ul class="cn-nav">
          <li><a href="{{ $homeUrl }}"     class="cn-nav-link {{ $isHome      ? 'active' : '' }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}"    class="cn-nav-link {{ $isAbout     ? 'active' : '' }}">About</a></li>
          <li><a href="{{ $servicesUrl }}" class="cn-nav-link {{ $isServices  ? 'active' : '' }}">Services</a></li>
          <li><a href="{{ $portfolioUrl }}"class="cn-nav-link {{ $isPortfolio ? 'active' : '' }}">Projects</a></li>
          <li><a href="{{ $contactUrl }}"  class="cn-nav-link {{ $isContact   ? 'active' : '' }}">Contact</a></li>
        </ul>
      </nav>

      {{-- CTA Button --}}
      <a href="{{ $contactUrl }}" class="cn-btn cn-btn-primary d-none d-lg-inline-flex" style="padding:10px 22px;font-size:13px;">
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
    <a href="{{ $servicesUrl }}"  class="cn-mobile-nav-link {{ $isServices  ? 'active' : '' }}">Services</a>
    <a href="{{ $portfolioUrl }}" class="cn-mobile-nav-link {{ $isPortfolio ? 'active' : '' }}">Projects</a>
    <a href="{{ $contactUrl }}"   class="cn-mobile-nav-link {{ $isContact   ? 'active' : '' }}">Contact</a>
    <div style="padding:16px 24px;">
      <a href="{{ $contactUrl }}" class="cn-btn cn-btn-primary" style="width:100%;justify-content:center;display:flex;">
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
      <div>
        <a href="{{ $homeUrl }}" class="cn-logo" style="margin-bottom:4px;display:inline-flex;">
          @if(!empty($agency->footer_logo) || !empty($agency->site_logo))
            <img src="{{ asset($agency->footer_logo ?: $agency->site_logo) }}" alt="{{ $agency->site_title ?? 'BuildCraft' }}" class="cn-logo-img" style="height:44px;">
          @else
            <div class="cn-logo-icon"><i class="fa-solid fa-helmet-safety"></i></div>
            <div class="cn-logo-text">Build<span>Craft</span></div>
          @endif
        </a>
        <p class="cn-footer-brand-desc">{{ $agency->footer_text ?? 'Building exceptional structures with quality craftsmanship, safety-first practices, and innovative engineering since 1999.' }}</p>
        <div class="cn-footer-social">
          @php $social = $agency->social_links ?? []; @endphp
          <a href="{{ $social['facebook']  ?? '#' }}" class="cn-footer-social-btn"><i class="fab fa-facebook-f"></i></a>
          <a href="{{ $social['instagram'] ?? '#' }}" class="cn-footer-social-btn"><i class="fab fa-instagram"></i></a>
          <a href="{{ $social['linkedin']  ?? '#' }}" class="cn-footer-social-btn"><i class="fab fa-linkedin-in"></i></a>
          <a href="{{ $social['youtube']   ?? '#' }}" class="cn-footer-social-btn"><i class="fab fa-youtube"></i></a>
        </div>
      </div>

      {{-- Quick Links --}}
      <div>
        <div class="cn-footer-heading">Quick Links</div>
        <ul class="cn-footer-links">
          <li><a href="{{ $homeUrl }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}">About Us</a></li>
          <li><a href="{{ $servicesUrl }}">Our Services</a></li>
          <li><a href="{{ $portfolioUrl }}">Our Projects</a></li>
          <li><a href="{{ $contactUrl }}">Contact Us</a></li>
        </ul>
      </div>

      {{-- Services --}}
      <div>
        <div class="cn-footer-heading">Our Services</div>
        <ul class="cn-footer-links">
          @php $services = $agency->services_data ?? []; @endphp
          @foreach(array_slice($services, 0, 5) as $svc)
            <li><a href="{{ $servicesUrl }}">{{ $svc['title'] }}</a></li>
          @endforeach
        </ul>
      </div>

      {{-- Contact --}}
      <div>
        <div class="cn-footer-heading">Contact Us</div>
        <div class="cn-footer-contact-item">
          <i class="fa-solid fa-location-dot cn-footer-contact-icon"></i>
          <div class="cn-footer-contact-text">{{ $agency->address ?? '45 Builder Street, Industrial Park, NY 10001' }}</div>
        </div>
        <div class="cn-footer-contact-item">
          <i class="fa-solid fa-phone cn-footer-contact-icon"></i>
          <div class="cn-footer-contact-text"><a href="tel:{{ $agency->phone ?? '+18002845348' }}">{{ $agency->phone ?? '+1 (800) 284-5348' }}</a></div>
        </div>
        <div class="cn-footer-contact-item">
          <i class="fa-solid fa-envelope cn-footer-contact-icon"></i>
          <div class="cn-footer-contact-text"><a href="mailto:{{ $agency->email ?? 'hello@buildcraft.com' }}">{{ $agency->email ?? 'hello@buildcraft.com' }}</a></div>
        </div>
        <div class="cn-footer-contact-item">
          <i class="fa-solid fa-clock cn-footer-contact-icon"></i>
          <div class="cn-footer-contact-text">Mon – Sat: 8:00 AM – 6:00 PM</div>
        </div>
      </div>
    </div>

    <div class="cn-footer-bottom">
      <div class="cn-footer-copy">
        &copy; {{ date('Y') }} <span>{{ $agency->site_title ?? 'BuildCraft Construction' }}</span>. All Rights Reserved.
      </div>
      <div class="cn-footer-copy">
        Built with <span>&#9829;</span> by <span>WebsiteBuilder</span>
      </div>
    </div>
  </div>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Mobile nav toggle
(function(){
  var btn = document.getElementById('cn-hamburger-btn');
  var nav = document.getElementById('cn-mobile-nav');
  var ico = document.getElementById('cn-ham-icon');
  if(btn && nav){
    btn.addEventListener('click', function(){
      nav.classList.toggle('open');
      ico.className = nav.classList.contains('open') ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
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
</script>

@yield('scripts')
</body>
</html>
