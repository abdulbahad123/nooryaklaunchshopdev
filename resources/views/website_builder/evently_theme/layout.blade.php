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
          ['title' => 'Events', 'url' => $portfolioUrl],
          ['title' => 'Contact', 'url' => $contactUrl],
        ];
        $navLinks = !empty($evData->header_nav_links) && is_array($evData->header_nav_links) ? $evData->header_nav_links : $defaultNav;
      @endphp
      <ul class="ev-nav d-none d-lg-flex">
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
            <li><a href="{{ $nl['url'] ?? '#' }}" class="ev-nav-link {{ $isActive ? 'active' : '' }}">{{ $nl['title'] }}</a></li>
          @endif
        @endforeach
      </ul>

      <!-- Desktop Right Actions -->
      <div class="ev-header-actions d-none d-lg-flex">
        <button class="ev-search-btn" type="button" aria-label="Search">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <a href="{{ $evData->primary_btn_url ?? $contactUrl }}" class="ev-btn ev-btn-primary" style="padding: 10px 22px; font-size: 13.5px;">
          {{ $evData->primary_btn_text ?? 'Plan Your Event' }} <i class="fa-solid fa-arrow-right"></i>
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
      <li class="py-2 border-bottom"><a href="{{ $homeUrl }}"      class="text-decoration-none fw-semibold text-dark fs-6">{{ $navLinks['home'] ?? 'Home' }}</a></li>
      <li class="py-2 border-bottom"><a href="{{ $aboutUrl }}"     class="text-decoration-none fw-semibold text-dark fs-6">{{ $navLinks['about'] ?? 'About Us' }}</a></li>
      <li class="py-2 border-bottom"><a href="{{ $portfolioUrl }}" class="text-decoration-none fw-semibold text-dark fs-6">{{ $navLinks['portfolio'] ?? 'Events' }}</a></li>
      <li class="py-2 border-bottom"><a href="{{ $contactUrl }}"   class="text-decoration-none fw-semibold text-dark fs-6">{{ $navLinks['contact'] ?? 'Contact' }}</a></li>
    </ul>
    <div class="pt-4 border-top">
      <a href="{{ $evData->primary_btn_url ?? $contactUrl }}" class="ev-btn ev-btn-primary w-100 mb-3 justify-content-center">
        {{ $evData->primary_btn_text ?? 'Plan Your Event' }} <i class="fa-solid fa-arrow-right"></i>
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
@php
  $ctaBg = !empty($evData->cta_banner_image) ? (str_starts_with($evData->cta_banner_image, 'http') ? $evData->cta_banner_image : asset(ltrim($evData->cta_banner_image, '/'))) : asset('assets/website_builder/Templates/Evently/event_cta_bg.png');
@endphp
<section class="ev-full-bg-cta" style="background: url('{{ $ctaBg }}') no-repeat center / cover;">
  <div class="ev-full-bg-cta-overlay"></div>
  <div class="ev-container" style="position: relative; z-index: 2;">
    <div class="row align-items-center">
      
      <!-- Left Content -->
      <div class="col-12 col-lg-8">
        <div class="ev-cta-gold-eyebrow">{{ $evData->cta_banner_badge ?? 'LET\'S CREATE SOMETHING AMAZING' }}</div>
        <h2 class="ev-cta-title-gold">
          {!! nl2br(e($evData->cta_banner_title ?? 'Ready to Plan Your Next Event?')) !!}
        </h2>
        <p class="ev-cta-sub-white">
          {{ $evData->cta_banner_subtitle ?? $evData->cta_subtitle ?? "From concept to celebration, we're here to make it extraordinary." }}
        </p>

        <div class="d-flex align-items-center gap-4 flex-wrap">
          <a href="{{ $evData->cta_banner_btn_url ?? $contactUrl }}" class="ev-btn ev-btn-primary" style="font-size: 15px; padding: 14px 32px;">
            {{ $evData->cta_banner_btn_text ?? 'Get a Free Consultation' }} <i class="fa-solid fa-arrow-right ms-1"></i>
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
          @if(!empty($evData->footer_quick_links) && is_array($evData->footer_quick_links))
            @foreach($evData->footer_quick_links as $ql)
              @php
                $qTitle = $ql['title'] ?? '';
                $qUrlTarget = strtolower(trim($ql['url'] ?? ''));
                $qHref = $homeUrl;
                if ($qUrlTarget === 'about' || str_contains($qUrlTarget, 'about')) $qHref = $aboutUrl;
                elseif ($qUrlTarget === 'portfolio' || $qUrlTarget === 'projects' || $qUrlTarget === 'events' || str_contains($qUrlTarget, 'portfolio') || str_contains($qUrlTarget, 'event')) $qHref = $portfolioUrl;
                elseif ($qUrlTarget === 'contact' || str_contains($qUrlTarget, 'contact')) $qHref = $contactUrl;
                elseif (str_starts_with($qUrlTarget, 'http')) $qHref = $ql['url'];
              @endphp
              <li><a href="{{ $qHref }}">{{ $qTitle }}</a></li>
            @endforeach
          @else
            <li><a href="{{ $homeUrl }}">Home</a></li>
            <li><a href="{{ $aboutUrl }}">About Us</a></li>
            <li><a href="{{ $portfolioUrl }}">Our Events</a></li>
            <li><a href="{{ $contactUrl }}">Contact Us</a></li>
          @endif
        </ul>
      </div>

      <!-- Col 3: Services -->
      <div>
        <h4 class="ev-footer-heading">Our Services</h4>
        <ul class="ev-footer-list">
          @php $servicesList = $evData->services_data ?? []; @endphp
          @if(count($servicesList) > 0)
            @foreach(array_slice($servicesList, 0, 5) as $srv)
              <li><a href="{{ $portfolioUrl }}">{{ $srv['title'] ?? '' }}</a></li>
            @endforeach
          @else
            <li><a href="{{ $portfolioUrl }}">Corporate Events</a></li>
            <li><a href="{{ $portfolioUrl }}">Weddings & Galas</a></li>
            <li><a href="{{ $portfolioUrl }}">Private Parties</a></li>
            <li><a href="{{ $portfolioUrl }}">Concerts & Festivals</a></li>
            <li><a href="{{ $portfolioUrl }}">Brand Activations</a></li>
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
            return route('website-builder.templates.evently.policy', ['slug' => $slugClean]);
          }
        };

        $defaultLegal = [
          ['title' => 'Privacy Policy',     'slug' => 'privacy'],
          ['title' => 'Terms & Conditions', 'slug' => 'terms'],
          ['title' => 'Disclaimer',         'slug' => 'disclaimer'],
          ['title' => 'Refund Policy',      'slug' => 'refund'],
        ];
        $legalLinks = (isset($evData) && !empty($evData->footer_legal_links)) ? $evData->footer_legal_links : ((isset($agency) && !empty($agency->footer_legal_links)) ? $agency->footer_legal_links : $defaultLegal);
      @endphp
      <div>
        <h4 class="ev-footer-heading">Legal & Policies</h4>
        <ul class="ev-footer-list">
          @foreach($legalLinks as $llink)
            <li><a href="{{ $resolveLegalUrl($llink) }}">{{ $llink['title'] ?? '' }}</a></li>
          @endforeach
        </ul>
      </div>

      <!-- Col 5: Contact -->
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
          <div>{!! nl2br(e($evData->working_hours ?? "Mon - Fri: 9AM - 7PM")) !!}</div>
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
        entry.target.classList.add('ev-revealed', 'ic-revealed', 'cn-revealed', 'tx-revealed', 'agency-revealed');
        entry.target.style.opacity = '1';
      } else {
        entry.target.classList.remove('ev-revealed', 'ic-revealed', 'cn-revealed', 'tx-revealed', 'agency-revealed');
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -10px 0px' });

  animTargets.forEach((el, i) => {
    if (!el.classList.contains('ev-reveal') && !el.classList.contains('ev-reveal-left') && !el.classList.contains('ev-reveal-right') && !el.classList.contains('ev-reveal-zoom')) {
      if (i % 4 === 0) el.classList.add('ev-reveal-left');
      else if (i % 4 === 1) el.classList.add('ev-reveal');
      else if (i % 4 === 2) el.classList.add('ev-reveal-right');
      else el.classList.add('ev-reveal-zoom');
    }
    observer.observe(el);
  });

  setTimeout(() => {
    animTargets.forEach(el => {
      el.classList.add('ev-revealed', 'ic-revealed', 'cn-revealed', 'tx-revealed', 'agency-revealed');
      el.style.opacity = '1';
    });
  }, 600);

  // ---- Counter Animation (Replays on re-entry into viewport) ----
  var statNumbers = document.querySelectorAll('.ev-counter-num, .cn-stat-num, .tx-counter-num, .ic-stat-counter-num, .agency-counter-num, [data-target]');
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

  // ---- Mobile Sliders (auto + manual smooth slider) ----
  document.querySelectorAll('.ev-mobile-slider').forEach(slider => {
    let timer = null;

    function startAuto() {
      if (timer) clearInterval(timer);
      timer = setInterval(() => {
        if (window.innerWidth < 992) {
          const maxScroll = slider.scrollWidth - slider.clientWidth;
          if (maxScroll > 10) {
            if (slider.scrollLeft >= maxScroll - 15) {
              slider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
              const card = slider.querySelector('[class*="col-"]');
              const cardWidth = card ? card.offsetWidth + 16 : 280;
              slider.scrollBy({ left: cardWidth, behavior: 'smooth' });
            }
          }
        }
      }, 3500);
    }

    startAuto();

    // Pause on user manual touch/drag and resume smoothly on release
    slider.addEventListener('touchstart', () => { if (timer) clearInterval(timer); }, { passive: true });
    slider.addEventListener('touchend', () => { setTimeout(startAuto, 2000); }, { passive: true });
    slider.addEventListener('mouseenter', () => { if (timer) clearInterval(timer); });
    slider.addEventListener('mouseleave', () => { startAuto(); });
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
    }
  } catch(e) {}
})();
</script>
@yield('scripts')
</body>
</html>
