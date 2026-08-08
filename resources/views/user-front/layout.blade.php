<!DOCTYPE html>
<html lang="{{ $userCurrentLang->code }}" dir="{{ $userCurrentLang->rtl == 1 ? 'rtl' : '' }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

  <title>@yield('page-title') | {{ $user->username }} </title>
  <link rel="icon" href="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}">

  <meta name="description" content="@yield('meta-description')">
  <meta name="keywords" content="@yield('meta-keywords')">
  <link rel="canonical" href="{{ canonicalUrl() }}">
  <link rel="manifest" href="{{ url('/manifest.json?u=' . $user->username) }}">
  <link rel="apple-touch-icon" href="{{ url('/pwa-icon/192?u=' . $user->username) }}">
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

  <div class="wrapper">
    {{-- top navbar area start --}}
    @if ($userBs->theme == 'electronics')
      @includeif('user-front.electronics.partials.header')
    @elseif($userBs->theme == 'vegetables' || $userBs->theme == 'grocery')
      @includeif('user-front.grocery.partials.header')
    @elseif($userBs->theme == 'fashion')
      @includeif('user-front.fashion.partials.header')
    @elseif($userBs->theme == 'furniture')
      @includeif('user-front.furniture.partials.header')
    @elseif($userBs->theme == 'kids')
      @includeif('user-front.kids.partials.header')
    @elseif($userBs->theme == 'manti')
      @includeif('user-front.manti.partials.header')
    @elseif($userBs->theme == 'pet')
      @includeif('user-front.pet.partials.header')
    @elseif($userBs->theme == 'skinflow')
      @includeif('user-front.skinflow.partials.header')
    @elseif($userBs->theme == 'jewellery')
      @includeif('user-front.jewellery.partials.header')
    @elseif($userBs->theme == 'clothing')
      @includeif('user-front.clothing.partials.header')
    @endif


    @if (!request()->routeIs('front.user.detail.view') && !request()->routeIs('customer.success.page') && !request()->routeIs('customer.itemcheckout.offline.success'))
      @includeIf('user-front.partials.breadcrumb')
    @endif

    <div class="main-panel">
      <div class="content">
        <div class="page-inner">
          @yield('content')
        </div>
      </div>

      @if ($userBs->footer_section == 1)
        @if ($userBs->theme == 'electronics')
          @includeif('user-front.electronics.partials.footer')
        @elseif($userBs->theme == 'vegetables' || $userBs->theme == 'grocery')
          @includeif('user-front.grocery.partials.footer')
        @elseif($userBs->theme == 'fashion')
          @includeif('user-front.fashion.partials.footer')
        @elseif($userBs->theme == 'furniture')
          @includeif('user-front.furniture.partials.footer')
        @elseif($userBs->theme == 'kids')
          @includeif('user-front.kids.partials.footer')
        @elseif($userBs->theme == 'manti')
          @includeif('user-front.manti.partials.footer')
        @elseif($userBs->theme == 'pet')
          @includeif('user-front.pet.partials.footer')
        @elseif($userBs->theme == 'skinflow')
          @includeif('user-front.skinflow.partials.footer')
        @elseif($userBs->theme == 'jewellery')
          @includeif('user-front.jewellery.partials.footer')
        @elseif($userBs->theme == 'clothing')
          @includeif('user-front.clothing.partials.footer')
        @endif
      @endif
    </div>
  </div>

  <div class="go-top active"><i class="fal fa-angle-double-up"></i></div>
  @if (@$userBe->cookie_alert_status == 1)
    <div class="cookie">
      @include('cookie-consent::index')
    </div>
  @endif

  @if ($userBs->theme == 'pet')
    @includeIf('user-front.pet.partials.mobile-menu')
  @elseif ($userBs->theme == 'skinflow')
    @includeIf('user-front.skinflow.partials.mobile-menu')
  @elseif ($userBs->theme == 'jewellery')
    @includeIf('user-front.jewellery.partials.mobile-menu')
  @elseif ($userBs->theme == 'clothing')
    @includeIf('user-front.clothing.partials.mobile-menu')
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
