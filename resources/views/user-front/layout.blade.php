@php
  $user = $user ?? (app('user') ?? (object)['username' => 'LaunchShop', 'id' => 1]);
  $userBs = $userBs ?? (app('userBs') ?? (object)['theme' => 'grocery', 'base_color' => '007bff', 'website_title' => 'LaunchShop', 'is_analytics' => 0, 'preloader_status' => 0]);
  $userCurrentLang = $userCurrentLang ?? (app('userCurrentLang') ?? (object)['code' => 'en', 'rtl' => 0]);
  $packagePermissions = $packagePermissions ?? [];
@endphp
<!DOCTYPE html>
<html lang="{{ $userCurrentLang->code ?? 'en' }}" dir="{{ (isset($userCurrentLang->rtl) && $userCurrentLang->rtl == 1) ? 'rtl' : '' }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

  <title>@yield('page-title') | {{ $user->username ?? 'LaunchShop' }} </title>
  <link rel="icon" href="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}">

  <meta name="description" content="@yield('meta-description')">
  <meta name="keywords" content="@yield('meta-keywords')">
  <link rel="canonical" href="{{ canonicalUrl() }}">
  <link rel="manifest" href="{{ url('/manifest.json?u=' . ($user->username ?? 'LaunchShop')) }}">
  <link rel="apple-touch-icon" href="{{ url('/pwa-icon/192?u=' . ($user->username ?? 'LaunchShop')) }}">
  <meta name="theme-color" content="#{{ $userBs->base_color ?? '007bff' }}">

  {{-- PWA: capture beforeinstallprompt EARLY (fires before DOM ready, before bottom scripts) --}}
  @if (empty($user->preview_template) || $user->preview_template != 1 || request()->getHost() != env('WEBSITE_HOST'))
  <script>
    window.deferredPwaPrompt = null;
    window.addEventListener('beforeinstallprompt', function(e) {
      e.preventDefault();
      window.deferredPwaPrompt = e;
      var banner = document.getElementById('pwa-install-banner');
      if (banner && !localStorage.getItem('pwa_dismissed')) {
        banner.style.display = 'flex';
      }
    });
    window.addEventListener('appinstalled', function() {
      window.deferredPwaPrompt = null;
      var banner = document.getElementById('pwa-install-banner');
      if (banner) banner.style.display = 'none';
    });
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/sw.js').catch(function() {});
    }
  </script>
  @endif
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="{{ $userBs->website_title ?? $user->username }}">
  @yield('og-meta')
  @includeif('user-front.styles')
  @php
    $selLang = App\Models\Language::where('code', request()->input('language'))->first();
  @endphp

  <style>
    :root {
      --color-primary: #{{ $userBs->base_color }};
      --color-primary-rgb: {{ hexToRgba($userBs->base_color) }}
    }
  </style>

  @yield('styles')

  @if ($userBs->is_analytics == 1 && in_array('Google Analytics', $packagePermissions))
    <script async src="//www.googletagmanager.com/gtag/js?id={{ $userBs->measurement_id }}"></script>
    <script>
      "use strict";
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());

      gtag('config', '{{ $userBs->measurement_id }}');
    </script>
  @endif

</head>

<body @if (request()->cookie('user-theme') == 'dark') data-background-color="dark" @endif>
  {{-- Loader --}}
  <div class="request-loader">
    <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
      data-src="{{ asset('assets/admin/img/loader.gif') }}" alt="">
  </div>
  {{-- Loader --}}

  <!-- Preloader Start -->
  @if ($userBs->preloader_status == 1)
    <div class="preloader">
      <div class="preloader-wrapper">
        <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ !is_null($userBs->preloader) ? asset('assets/front/img/user/' . $userBs->preloader) : asset('assets/user-front/images/preloader.gif') }}"
          alt="preloder-image">
      </div>
    </div>
  @endif
  <!-- Preloader End -->

  @php
    $activeTheme = $userBs->theme ?? 'grocery';
  @endphp
  <div class="wrapper theme-{{ $activeTheme }}">
    {{-- top navbar area start --}}
    @if ($activeTheme == 'electronics')
      @includeif('user-front.electronics.partials.header')
    @elseif($activeTheme == 'vegetables' || $activeTheme == 'grocery')
      @includeif('user-front.grocery.partials.header')
    @elseif($activeTheme == 'fashion')
      @includeif('user-front.fashion.partials.header')
    @elseif($activeTheme == 'furniture')
      @includeif('user-front.furniture.partials.header')
    @elseif($activeTheme == 'kids')
      @includeif('user-front.kids.partials.header')
    @elseif($activeTheme == 'manti')
      @includeif('user-front.manti.partials.header')
    @elseif($activeTheme == 'pet')
      @includeif('user-front.pet.partials.header')
    @elseif($activeTheme == 'skinflow')
      @includeif('user-front.skinflow.partials.header')
    @elseif($activeTheme == 'jewellery')
      @includeif('user-front.jewellery.partials.header')
    @elseif($activeTheme == 'clothing')
      @includeif('user-front.clothing.partials.header')
    @elseif($activeTheme == 'grocery2')
      @includeif('user-front.grocery2.partials.header')
    @else
      @includeif('user-front.grocery.partials.header')
    @endif


    @php
      $userCtx = getUser();
      $currentPathClean = trim(request()->path(), '/');
      $isHomePage = request()->routeIs('front.user.detail.view') 
        || empty($currentPathClean) 
        || ($userCtx && (strtolower($currentPathClean) === strtolower($userCtx->username)));
    @endphp

    @if (!$isHomePage && !request()->routeIs('customer.success.page') && !request()->routeIs('customer.itemcheckout.offline.success'))
      @includeIf('user-front.partials.breadcrumb')
    @endif

    <div class="main-panel">
      <div class="content">
        <div class="page-inner">
          @yield('content')
        </div>
      </div>

        @if ($activeTheme == 'electronics')
          @includeif('user-front.electronics.partials.footer')
        @elseif($activeTheme == 'vegetables' || $activeTheme == 'grocery')
          @includeif('user-front.grocery.partials.footer')
        @elseif($activeTheme == 'fashion')
          @includeif('user-front.fashion.partials.footer')
        @elseif($activeTheme == 'furniture')
          @includeif('user-front.furniture.partials.footer')
        @elseif($activeTheme == 'kids')
          @includeif('user-front.kids.partials.footer')
        @elseif($activeTheme == 'manti')
          @includeif('user-front.manti.partials.footer')
        @elseif($activeTheme == 'pet')
          @includeif('user-front.pet.partials.footer')
        @elseif($activeTheme == 'skinflow')
          @includeif('user-front.skinflow.partials.footer')
        @elseif($activeTheme == 'jewellery')
          @includeif('user-front.jewellery.partials.footer')
        @elseif($activeTheme == 'clothing')
          @includeif('user-front.clothing.partials.footer')
        @elseif($activeTheme == 'grocery2')
          @includeif('user-front.grocery2.partials.footer')
        @else
          @includeif('user-front.grocery.partials.footer')
        @endif
    </div>
  </div>

  <div class="go-top active"><i class="fal fa-angle-double-up"></i></div>
  @if (@$userBe->cookie_alert_status == 1)
    <div class="cookie">
      @include('cookie-consent::index')
    </div>
  @endif

  @if ($activeTheme == 'pet')
    @includeIf('user-front.pet.partials.mobile-menu')
  @elseif ($activeTheme == 'skinflow')
    @includeIf('user-front.skinflow.partials.mobile-menu')
  @elseif ($activeTheme == 'jewellery')
    @includeIf('user-front.jewellery.partials.mobile-menu')
  @elseif ($activeTheme == 'clothing')
    @includeIf('user-front.clothing.partials.mobile-menu')
  @elseif ($activeTheme == 'grocery2')
    @includeIf('user-front.grocery2.partials.mobile-menu')
  @else
    @includeIf('user-front.partials.mobile-footer-menu')
  @endif

  <!-- WhatsApp Chat Button -->
  <div id="WAButton"></div>

  <div class="cart-dropdown" id="cart-dropdown-mobile"></div>
  <div class="cart-sidebar-overlay"></div>

  @includeif('user-front.scripts')
  @yield('scripts')
  @includeIf('user-front.partials.plugins')
  @includeIf('user-front.partials.pwa-banner')
</body>

</html>
