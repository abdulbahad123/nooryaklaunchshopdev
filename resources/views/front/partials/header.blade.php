<style>
  /* Header Active Nav Link Indicator */
  .header-area .main-navbar .navbar-nav .nav-item .nav-link.active,
  .header-area .main-navbar .navbar-nav .nav-item.active > .nav-link {
    color: #ff5a2c !important;
    font-weight: 700 !important;
    position: relative;
  }
  .header-area .main-navbar .navbar-nav .nav-item .nav-link.active::after,
  .header-area .main-navbar .navbar-nav .nav-item.active > .nav-link::after {
    content: '';
    position: absolute;
    bottom: 20px;
    left: 15%;
    width: 70%;
    height: 3px;
    background: #ff5a2c;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(255, 90, 44, 0.4);
  }
  @media (max-width: 991px) {
    .header-area .main-navbar .navbar-nav .nav-item .nav-link.active::after,
    .header-area .main-navbar .navbar-nav .nav-item.active > .nav-link::after {
      display: none;
    }
  }
  @media (min-width: 1600px) {
    .header-area .main-navbar .navbar-nav .nav-item .nav-link.active::after,
    .header-area .main-navbar .navbar-nav .nav-item.active > .nav-link::after {
      bottom: -4px !important;
      top: auto !important;
    }
  }
</style>
<!-- Header Start -->
<header class="header-area header-inner-page">
  <div class="main-responsive-nav">
    <div class="container">
      <div class="main-responsive-menu">
        @php
          $hdrLogoImg = !empty($bs->logo) && file_exists(public_path('assets/front/img/' . $bs->logo))
                      ? asset('assets/front/img/' . $bs->logo)
                      : asset('images/launchshop_icon.png');
        @endphp
        <div class="logo">
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.index') ? route('front.index') : url('/') }}">
            <img src="{{ $hdrLogoImg }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'" alt="{{ $bs->website_title ?? 'Logo' }}" style="max-height: 45px; width: auto;">
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="main-navbar">
    <div class="container-fluid px-lg-5 px-3">
      <nav class="navbar navbar-expand-lg">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ \Illuminate\Support\Facades\Route::has('front.index') ? route('front.index') : url('/') }}">
          <img src="{{ $hdrLogoImg }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'" alt="{{ $bs->website_title ?? 'Logo' }}" style="max-height: 45px; width: auto;">
        </a>
        <!-- Navigation items -->
        <div class="collapse navbar-collapse mean-menu">
          <ul id="mainMenu" class="navbar-nav mx-auto">
                        @php
              $links = !empty($menus) ? json_decode($menus, true) : [];
              $cleanNavLabel = function($label) {
                  $t = trim((string)$label);
                  $map = [
                      'Store Designs' => 'Store Themes',
                      'Templates' => 'Store Themes',
                      'Shops' => 'Stores Launched',
                      'Live Stores' => 'Stores Launched',
                      'About' => 'About Us',
                      'About us' => 'About Us',
                      'About Us us' => 'About Us',
                      'Contact' => 'Contact Us',
                      'Contact us' => 'Contact Us',
                      'Contact Us us' => 'Contact Us',
                  ];
                  if (isset($map[$t])) {
                      return $map[$t];
                  }
                  $t = preg_replace('/\bAbout\s+Us\s+us\b/i', 'About Us', $t);
                  $t = preg_replace('/\bContact\s+Us\s+us\b/i', 'Contact Us', $t);
                  return $t;
              };

              // Active Nav Link Matcher
              $isNavLinkActive = function($href, $label = '') {
                  $currentUrl = url()->current();
                  $currPath = trim(request()->path(), '/');
                  $linkPath = trim(parse_url($href, PHP_URL_PATH) ?? '', '/');
                  $lbl = strtolower(trim((string)$label));

                  // Home link check
                  $isHomeHref = ($linkPath === '' || $linkPath === '/' || $href === url('/') || $lbl === 'home');
                  if ($isHomeHref) {
                      return ($currPath === '' || request()->is('/') || request()->routeIs('front.index'));
                  }

                  // Direct path match
                  if ($currPath !== '' && $linkPath !== '' && $currPath === $linkPath) {
                      return true;
                  }

                  // Full URL match
                  if (rtrim($currentUrl, '/') === rtrim($href, '/')) {
                      return true;
                  }

                  // Route & label aliases
                  if (($lbl === 'contact us' || $lbl === 'contact') && (request()->routeIs('front.contact') || $currPath === 'contact')) {
                      return true;
                  }
                  if ($lbl === 'pricing' && (request()->routeIs('front.pricing') || $currPath === 'pricing')) {
                      return true;
                  }
                  if (($lbl === 'store themes' || $lbl === 'templates') && (request()->routeIs('front.templates*') || $currPath === 'templates')) {
                      return true;
                  }
                  if (($lbl === 'stores launched' || $lbl === 'shops') && (request()->routeIs('front.user.view') || $currPath === 'shops')) {
                      return true;
                  }
                  if (($lbl === 'about us' || $lbl === 'about') && (request()->routeIs('front.about') || $currPath === 'about')) {
                      return true;
                  }

                  return false;
              };
            @endphp
            @if(!empty($links) && is_array($links))
              @foreach ($links as $link)
                @php
                  $href = getHref($link);
                  $displayLabel = $cleanNavLabel($link['text'] ?? '');
                  $isActive = $isNavLinkActive($href, $displayLabel);
                @endphp
                @if (!array_key_exists('children', $link))
                  <li class="nav-item {{ $isActive ? 'active' : '' }}">
                    <a class="nav-link {{ $isActive ? 'active' : '' }}" target="{{ $link['target'] ?? '_self' }}" href="{{ $href }}">{{ $displayLabel }}</a>
                  </li>
                @else
                  <li class="nav-item has-submenu {{ $isActive ? 'active' : '' }}">
                    <a class="nav-link {{ $isActive ? 'active' : '' }}" target="{{ $link['target'] ?? '_self' }}" href="{{ $href }}">{{ $displayLabel }} <i class="fal fa-plus"></i></a>
                    <ul class="menu-dropdown">
                      @foreach ($link['children'] as $level2)
                        @php
                          $l2Href = getHref($level2);
                          $l2DisplayLabel = $cleanNavLabel($level2['text'] ?? '');
                          $isL2Active = $isNavLinkActive($l2Href, $l2DisplayLabel);
                        @endphp
                        <li class="nav-item {{ $isL2Active ? 'active' : '' }}">
                          <a class="nav-link {{ $isL2Active ? 'active' : '' }}" href="{{ $l2Href }}"
                            target="{{ $level2['target'] ?? '_self' }}">{{ $l2DisplayLabel }}</a>
                        </li>
                      @endforeach
                    </ul>
                  </li>
                @endif
              @endforeach
            @else
              <li class="nav-item {{ $isNavLinkActive(url('/'), 'Home') ? 'active' : '' }}"><a class="nav-link {{ $isNavLinkActive(url('/'), 'Home') ? 'active' : '' }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
              <li class="nav-item {{ $isNavLinkActive(url('/shops'), 'Stores Launched') ? 'active' : '' }}"><a class="nav-link {{ $isNavLinkActive(url('/shops'), 'Stores Launched') ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('front.user.view') ? route('front.user.view') : url('/shops') }}">{{ __('Stores Launched') }}</a></li>
              <li class="nav-item {{ $isNavLinkActive(url('/about'), 'About Us') ? 'active' : '' }}"><a class="nav-link {{ $isNavLinkActive(url('/about'), 'About Us') ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('front.about') ? route('front.about') : url('/about') }}">{{ __('About Us') }}</a></li>
              <li class="nav-item {{ $isNavLinkActive(url('/pricing'), 'Pricing') ? 'active' : '' }}"><a class="nav-link {{ $isNavLinkActive(url('/pricing'), 'Pricing') ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('front.pricing') ? route('front.pricing') : url('/pricing') }}">{{ __('Pricing') }}</a></li>
              <li class="nav-item {{ $isNavLinkActive(url('/templates'), 'Store Themes') ? 'active' : '' }}"><a class="nav-link {{ $isNavLinkActive(url('/templates'), 'Store Themes') ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('front.templates.view') ? route('front.templates.view') : url('/templates') }}">{{ __('Store Themes') }}</a></li>
              <li class="nav-item {{ $isNavLinkActive(url('/contact'), 'Contact Us') ? 'active' : '' }}"><a class="nav-link {{ $isNavLinkActive(url('/contact'), 'Contact Us') ? 'active' : '' }}" href="{{ \Illuminate\Support\Facades\Route::has('front.contact') ? route('front.contact') : url('/contact') }}">{{ __('Contact Us') }}</a></li>
@endif
          </ul>

        </div>

        @php
          $hdrDemoTxt = !empty($bs->header_demo_text) ? $bs->header_demo_text : __('Book Demo');
          $hdrDemoUrl = !empty($bs->header_demo_url) ? $bs->header_demo_url : (\Illuminate\Support\Facades\Route::has('front.contact') ? route('front.contact') : url('/contact'));
          $hdrLoginTxt = !empty($bs->header_login_text) ? $bs->header_login_text : __('Login');
          $hdrLoginUrl = !empty($bs->header_login_url) ? $bs->header_login_url : (\Illuminate\Support\Facades\Route::has('user.login') ? route('user.login') : url('/login'));
        @endphp
        <div class="side-option">
          @guest
            <div class="item">
              <a href="{{ $hdrDemoUrl }}" class="btn-ls-outline btn-sm">
                <span>{{ $hdrDemoTxt }}</span>
              </a>
            </div>
            <div class="item">
              <a href="{{ $hdrLoginUrl }}" class="btn-ls-primary btn-sm">
                <span>{{ $hdrLoginTxt }}</span>
              </a>
            </div>
          @endguest
          @auth
            <div class="item">
              <a href="{{ \Illuminate\Support\Facades\Route::has('user-dashboard') ? route('user-dashboard') : url('/user/dashboard') }}" class="btn-ls-primary btn-sm">
                <span>{{ __('Dashboard') }}</span>
              </a>
            </div>
          @endauth
        </div>
      </nav>
    </div>
  </div>
</header>
<!-- Header End -->
