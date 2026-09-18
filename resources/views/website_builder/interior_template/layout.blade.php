<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'InterioCRAFT - Bespoke Architecture & Interior Design Studio')</title>
  <meta name="description" content="Explore our latest interior design projects and see how we turn ideas into beautiful, functional spaces.">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
  $blogUrl = $subdomainParam ? route('website-builder.subdomain.blogs', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');
@endphp

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-0 mb-0 py-3 text-center border-0 fw-bold fs-6 shadow-sm" style="background: var(--ic-secondary); color: #ffffff; z-index: 9999;">
  <i class="fa-solid fa-leaf me-2"></i> {{ session('success') }}
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- TOP ANNOUNCEMENT BAR -->
<div class="py-2 d-none d-lg-block" style="background: #ffffff; border-bottom: 1px solid var(--ic-border-light); font-size: 12.5px; color: var(--ic-text-muted);">
  <div class="ic-container">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <i class="fa-solid fa-leaf me-1" style="color: var(--ic-primary);"></i>
        {{ $interior->top_announcement ?? 'Designing spaces. Creating better lives.' }}
      </div>
      <div class="d-flex align-items-center gap-4">
        <span><i class="fa-solid fa-envelope me-1" style="color: var(--ic-primary);"></i> <a href="mailto:{{ $interior->email ?? 'hello@interiocraft.com' }}" style="color: var(--ic-text-muted); text-decoration: none;">{{ $interior->email ?? 'hello@interiocraft.com' }}</a></span>
        <span><i class="fa-solid fa-phone me-1" style="color: var(--ic-primary);"></i> {{ $interior->phone ?? '+1 (234) 567-890' }}</span>
        <div class="d-flex gap-3 ms-2">
          <a href="{{ $interior->social_links['facebook'] ?? '#' }}" target="_blank" style="color: var(--ic-text-muted);"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="{{ $interior->social_links['instagram'] ?? '#' }}" target="_blank" style="color: var(--ic-text-muted);"><i class="fa-brands fa-instagram"></i></a>
          <a href="{{ $interior->social_links['pinterest'] ?? '#' }}" target="_blank" style="color: var(--ic-text-muted);"><i class="fa-brands fa-pinterest-p"></i></a>
          <a href="{{ $interior->social_links['linkedin'] ?? '#' }}" target="_blank" style="color: var(--ic-text-muted);"><i class="fa-brands fa-linkedin-in"></i></a>
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
          $siteTitle = $interior->site_title ?? 'InterioCRAFT';
          $defaultLogoSrc = asset('assets/website_builder/Templates/Interior_agency/header_logo.png');
          $logoSrc = !empty($interior->site_logo) ? (str_starts_with($interior->site_logo, 'http') ? $interior->site_logo : asset(ltrim($interior->site_logo, '/'))) : $defaultLogoSrc;
        @endphp
        <img src="{{ $logoSrc }}" alt="{{ $siteTitle }}" style="max-height: 62px; width: auto; object-fit: contain;">
      </a>

      <ul class="ic-nav d-none d-lg-flex">
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
          $navLinks = !empty($interior->header_nav_links) && is_array($interior->header_nav_links) ? $interior->header_nav_links : $defaultNav;
        @endphp
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
            <li><a href="{{ $nl['url'] ?? '#' }}" class="ic-nav-link {{ $isActive ? 'active' : '' }}">{{ $nl['title'] }}</a></li>
          @endif
        @endforeach
      </ul>

      <div class="d-none d-lg-flex align-items-center gap-3">
        <button type="button" class="btn btn-light rounded-circle border-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: var(--ic-text-dark);" aria-label="Search">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <a href="{{ $contactUrl }}" class="ic-btn ic-btn-dark">
          Start a Project <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <button class="ic-mobile-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#icMobileNav" aria-label="Toggle navigation">
        <div class="ic-burger-icon">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </button>
    </div>
  </div>
</header>

<!-- MOBILE NAV OFFCANVAS -->
<div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="icMobileNav" style="width: 300px;">
  <div class="offcanvas-header border-bottom py-3">
    <a href="{{ $homeUrl }}" class="ic-logo">
      <img src="{{ $logoSrc }}" alt="{{ $siteTitle }}" style="max-height: 48px; width: auto; object-fit: contain;">
    </a>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between">
    <ul class="list-unstyled">
      <li class="py-2 border-bottom"><a href="{{ $homeUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">Home</a></li>
      <li class="py-2 border-bottom"><a href="{{ $aboutUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">About Us</a></li>
      <li class="py-2 border-bottom"><a href="{{ $portfolioUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">Portfolio</a></li>
      <li class="py-2 border-bottom"><a href="{{ $contactUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">Contact Us</a></li>
    </ul>

    <div class="pt-4 border-top">
      <a href="{{ $contactUrl }}" class="ic-btn ic-btn-dark w-100 mb-3">
        Start a Project <i class="fa-solid fa-arrow-right"></i>
      </a>
      <div class="text-muted small">
        <div class="mb-1"><i class="fa-solid fa-envelope me-1"></i> {{ $interior->email ?? 'hello@interiocraft.com' }}</div>
        <div><i class="fa-solid fa-phone me-1"></i> {{ $interior->phone ?? '+1 (234) 567-890' }}</div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<main>
  @yield('content')
</main>

<!-- GLOBAL CALL TO ACTION BANNER (ALL PAGES) -->
@hasSection('no_cta')
  <!-- CTA Banner disabled -->
@else
<div class="ic-container my-4">
  <div class="ic-cta-box-edge">
    <div class="row align-items-center">
      <div class="col-lg-7 ic-cta-content">
        <div class="ic-cta-eyebrow text-uppercase fw-bold mb-2">LET'S DESIGN TOGETHER</div>
        <h2 class="ic-cta-title">{{ $interior->contact_title ?? 'Ready to Transform Your Space?' }}</h2>
        <p class="ic-cta-sub">
          {{ $interior->contact_subtitle ?? "Schedule a complimentary interior design consultation with our lead architects today." }}
        </p>

        <a href="{{ $contactUrl }}" class="ic-btn ic-btn-light fs-6 px-4 py-3">
          Get Started Now <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <!-- Right Side Edge-to-Edge Cover Image + Cursive Overlay -->
    @php
      $defaultCtaImg = asset('assets/website_builder/Templates/Interior_agency/cta_footer.png');
      $ctaImgSrc = !empty($interior->contact_image) && !str_contains($interior->contact_image, 'contact_footer') ? (str_starts_with($interior->contact_image, 'http') ? $interior->contact_image : asset(ltrim($interior->contact_image, '/'))) : $defaultCtaImg;
    @endphp
    <div class="ic-cta-img-col d-none d-lg-block">
      <img src="{{ $ctaImgSrc }}" onerror="this.src='{{ $defaultCtaImg }}';" alt="Luxury Interior">
      <div class="position-absolute bottom-0 start-0 m-4" style="z-index: 3;">
        <span class="ic-cursive" style="font-size: 30px; color: #ffffff; text-shadow: 0 2px 12px rgba(0,0,0,0.85);">
          Spaces That<br>Feel Like Home
        </span>
      </div>
    </div>
  </div>
</div>
@endif

<!-- FOOTER -->
<footer class="ic-footer">
  <div class="ic-container">
    <div class="ic-footer-grid">
      <!-- Col 1: Brand Info -->
      <div>
        <div class="ic-footer-logo-title mb-3">
          @php
            $defaultFooterLogo = asset('assets/website_builder/Templates/Interior_agency/footer_logo.png');
            $footerLogoSrc = !empty($interior->site_logo) ? (str_starts_with($interior->site_logo, 'http') ? $interior->site_logo : asset(ltrim($interior->site_logo, '/'))) : $defaultFooterLogo;
          @endphp
          <img src="{{ $footerLogoSrc }}" alt="{{ $interior->site_title ?? 'InterioCRAFT' }}" style="max-height: 64px; width: auto; object-fit: contain;">
        </div>
        <p class="ic-footer-bio">
          {{ $interior->footer_text ?? 'We create beautiful, functional spaces that reflect your style and improve your everyday living.' }}
        </p>
        <div class="ic-footer-socials">
          <a href="{{ $interior->social_links['facebook'] ?? '#' }}" class="ic-social-icon" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="{{ $interior->social_links['instagram'] ?? '#' }}" class="ic-social-icon" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="{{ $interior->social_links['pinterest'] ?? '#' }}" class="ic-social-icon" aria-label="Pinterest"><i class="fa-brands fa-pinterest-p"></i></a>
          <a href="{{ $interior->social_links['linkedin'] ?? '#' }}" class="ic-social-icon" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="{{ $interior->social_links['youtube'] ?? '#' }}" class="ic-social-icon" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div>
        <h4 class="ic-footer-heading">Quick Links</h4>
        <ul class="ic-footer-list">
          <li><a href="{{ $homeUrl }}">Home</a></li>
          <li><a href="{{ $aboutUrl }}">About Us</a></li>
          <li><a href="{{ $portfolioUrl }}">Portfolio</a></li>
          <li><a href="{{ $contactUrl }}">Contact Us</a></li>
        </ul>
      </div>

      <!-- Col 3: Our Services -->
      <div>
        <h4 class="ic-footer-heading">Our Services</h4>
        <ul class="ic-footer-list">
          <li><a href="{{ $homeUrl }}#services">Residential Design</a></li>
          <li><a href="{{ $homeUrl }}#services">Commercial Design</a></li>
          <li><a href="{{ $homeUrl }}#services">Space Planning</a></li>
          <li><a href="{{ $homeUrl }}#services">Interior Styling</a></li>
          <li><a href="{{ $homeUrl }}#services">Renovation</a></li>
          <li><a href="{{ $contactUrl }}">Consultation</a></li>
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
            return route('website-builder.templates.interior.policy', ['slug' => $slugClean]);
          }
        };

        $defaultLegal = [
          ['title' => 'Privacy Policy',     'slug' => 'privacy'],
          ['title' => 'Terms & Conditions', 'slug' => 'terms'],
          ['title' => 'Disclaimer',         'slug' => 'disclaimer'],
          ['title' => 'Refund Policy',      'slug' => 'refund'],
        ];
        $legalLinks = (isset($interior) && !empty($interior->footer_legal_links)) ? $interior->footer_legal_links : $defaultLegal;
      @endphp
      <div>
        <h4 class="ic-footer-heading">Legal & Policies</h4>
        <ul class="ic-footer-list">
          @foreach($legalLinks as $llink)
            <li><a href="{{ $resolveLegalUrl($llink) }}">{{ $llink['title'] ?? '' }}</a></li>
          @endforeach
        </ul>
      </div>

      <!-- Col 5: Contact Us -->
      <div>
        <h4 class="ic-footer-heading">Contact Us</h4>
        <div class="ic-footer-contact-row">
          <i class="fa-solid fa-location-dot"></i>
          <div>123 Design Street,<br>Creative City, CA 94043</div>
        </div>
        <div class="ic-footer-contact-row">
          <i class="fa-solid fa-phone"></i>
          <div>{{ $interior->phone ?? '+1 (234) 567-890' }}</div>
        </div>
        <div class="ic-footer-contact-row">
          <i class="fa-solid fa-envelope"></i>
          <div>{{ $interior->email ?? 'hello@interiocraft.com' }}</div>
        </div>
        <div class="ic-footer-contact-row">
          <i class="fa-solid fa-clock"></i>
          <div>Mon - Fri: 9AM - 6PM</div>
        </div>
      </div>
    </div>

    <!-- Bottom Copyright Bar -->
    <div class="ic-footer-bottom-bar">
      <div>&copy; {{ date('Y') }} InterioCRAFT. All rights reserved.</div>
      <div>Designing Better Spaces for a Brighter Tomorrow. <i class="fa-solid fa-leaf text-success ms-1"></i></div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Continuous scroll animation observer (triggers all the time on scroll)
    const animTargets = document.querySelectorAll('section, .ic-cta-box-edge, .card, .ic-project-card, .ic-stat-box, .ic-heading, .ic-pill-badge');
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('ic-revealed');
        } else {
          entry.target.classList.remove('ic-revealed');
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -20px 0px' });
    
    animTargets.forEach((el, index) => {
      if (!el.classList.contains('ic-reveal') && !el.classList.contains('ic-reveal-left') && !el.classList.contains('ic-reveal-right') && !el.classList.contains('ic-reveal-zoom')) {
        if (index % 3 === 0) {
          el.classList.add('ic-reveal-left');
        } else if (index % 3 === 1) {
          el.classList.add('ic-reveal-right');
        } else {
          el.classList.add('ic-reveal');
        }
      }
      observer.observe(el);
    });

    // Dynamic Running Counter Observer (counts up from 0 dynamically)
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
      const duration = 1200;
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
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
        }
      });
    }, { threshold: 0.2 });

    document.querySelectorAll('.ic-counter-num').forEach(el => counterObserver.observe(el));
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
