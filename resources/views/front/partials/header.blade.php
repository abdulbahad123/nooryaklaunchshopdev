<!-- Header Start -->
<header class="header-area header-inner-page">
  <div class="main-responsive-nav">
    <div class="container">
      <div class="main-responsive-menu">
        <div class="logo">
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.index') ? route('front.index') : url('/') }}">
            <img src="{{ asset('images/launchshop_icon.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'" alt="Ecom Builder Logo" style="max-height: 45px; width: auto;">
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
          <img src="{{ asset('images/launchshop_icon.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'" alt="Ecom Builder Logo" style="max-height: 45px; width: auto;">
        </a>
        <!-- Navigation items -->
        <div class="collapse navbar-collapse mean-menu">
          <ul id="mainMenu" class="navbar-nav mx-auto">
            @php
              $links = json_decode($menus, true);
            @endphp
            @foreach ($links as $link)
              @php
                $href = getHref($link);
              @endphp
              @if (!array_key_exists('children', $link))
                <li class="nav-item">
                  <a class="nav-link " target="{{ $link['target'] }}" href="{{ $href }}">{{ str_replace('Store Designs', 'Store Themes', $link['text']) }}</a>
                </li>
              @else
                <li class="nav-item has-submenu">
                  <a class="nav-link " target="{{ $link['target'] }}" href="{{ $href }}">{{ str_replace('Store Designs', 'Store Themes', $link['text']) }} <i class="fal fa-plus"></i></a>
                  <ul class="menu-dropdown">
                    @foreach ($link['children'] as $level2)
                      @php
                        $l2Href = getHref($level2);
                      @endphp
                      <li class="nav-item">
                        <a class="nav-link" href="{{ $l2Href }}"
                          target="{{ $level2['target'] }}">{{ str_replace('Store Designs', 'Store Themes', $level2['text']) }}</a>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @endif
            @endforeach
          </ul>

        </div>

        <div class="side-option">
          @guest
            <div class="item">
              <a href="{{ \Illuminate\Support\Facades\Route::has('front.contact') ? route('front.contact') : url('/contact') }}" class="btn-ls-outline btn-sm">
                <span>{{ __('Book Demo') }}</span>
              </a>
            </div>
            <div class="item">
              <a href="{{ \Illuminate\Support\Facades\Route::has('user.login') ? route('user.login') : url('/login') }}" class="btn-ls-primary btn-sm">
                <span>{{ __('Login') }}</span>
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
