<!-- Header v10 (Clothing) Start -->
<header class="header header-area header-v10 header-fixed header-mt-fix sticky-header">

  <!-- Promo Strip -->
  <div class="promo-strip">
    {{ $keywords['FREE SHIPPING ON ORDERS OVER ₹75'] ?? ($keywords['FREE SHIPPING ON ORDERS OVER $75'] ?? __('FREE SHIPPING ON ORDERS OVER ₹75')) }}
  </div>

  <!-- Mobile Navbar -->
  <div class="mobile-navbar d-block d-xl-none">
    <div class="container">
      <div class="mobile-navbar-inner">
        <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
          @if(!empty(@$userBs->logo))
            <img src="{{ asset('assets/front/img/user/' . @$userBs->logo) }}" alt="logo">
          @else
            <span style="font-family:var(--clothing-body-font);font-size:20px;font-weight:700;letter-spacing:1px;color:#000;text-transform:uppercase;">{{ $user->username }}<span>.</span></span>
          @endif
        </a>
        <button class="mobile-menu-toggler" type="button">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>

  <!-- Top Bar (Hidden in mockup single-row header, but kept simple or can be integrated) -->
  <!-- Note: We hide the top bar for a cleaner single-row mockup layout on desktop -->

  <!-- Header Main Single-Row -->
  <div class="header-middle d-none d-xl-flex">
    <div class="container-fluid px-5 w-100">
      <div class="row align-items-center w-100 gx-0">
        <!-- Logo -->
        <div class="col-xl-2 col-lg-2">
          <div class="brand-logo">
            <a href="{{ route('front.user.detail.view', getParam()) }}">
              @if(!empty(@$userBs->logo))
                <img src="{{ asset('assets/front/img/user/' . @$userBs->logo) }}" alt="Logo">
              @else
                <span style="font-family:var(--clothing-body-font);font-size:26px;font-weight:700;letter-spacing:0.5px;color:#000;text-transform:uppercase;">{{ $user->username }}<span>.</span></span>
              @endif
            </a>
          </div>
        </div>

        <!-- Navigation Menu (Middle Column) -->
        <div class="col-xl-6 col-lg-6">
          <div class="main-nav justify-content-center">
            <nav class="menu mobile-nav">
              <ul class="menu-right">
                @php $links = json_decode($userMenus, true); @endphp
                @foreach($links as $link)
                  @php $href = getUserHref($link, $userCurrentLang->id); @endphp
                  @if(!array_key_exists('children', $link))
                    <li class="nav-item">
                      <a class="nav-link {{ url()->current() == $href ? 'active' : '' }}" target="{{ $link['target'] }}" href="{{ $href }}">{{ $link['text'] }}</a>
                    </li>
                  @else
                    <li class="nav-item">
                      <a href="{{ $href }}" target="{{ $link['target'] }}" class="nav-link {{ url()->current() == $href ? 'active' : '' }}">{{ $link['text'] }}<i class="fal fa-angle-down" style="margin-left:4px;font-size:11px;"></i></a>
                      <ul class="submenu">
                        @foreach($link['children'] as $level2)
                          @php $l2Href = getUserHref($level2, $userCurrentLang->id); @endphp
                          @if(trim($level2['text']) != 'Privacy Policy')
                            <li><a href="{{ $l2Href }}" target="{{ $level2['target'] }}">{{ $level2['text'] }}</a></li>
                          @endif
                        @endforeach
                        @if(strtolower(trim($link['text'])) == 'pages')
                          <li><a href="{{ route('front.user.privacy_policy', getParam()) }}">{{ $keywords['Privacy Policy'] ?? __('Privacy Policy') }}</a></li>
                          <li><a href="{{ route('front.user.terms_conditions', getParam()) }}">{{ $keywords['Terms & Conditions'] ?? __('Terms & Conditions') }}</a></li>
                          <li><a href="{{ route('front.user.refund_policy', getParam()) }}">{{ $keywords['Refund Policy'] ?? __('Refund Policy') }}</a></li>
                          <li><a href="{{ route('front.user.shipping_policy', getParam()) }}">{{ $keywords['Shipping Policy'] ?? __('Shipping Policy') }}</a></li>
                        @endif
                      </ul>
                    </li>
                  @endif
                @endforeach
              </ul>
            </nav>
          </div>
        </div>

        <!-- Search & Right Icons (Right Column) -->
        <div class="col-xl-4 col-lg-4">
          <div class="d-flex align-items-center justify-content-end" style="gap:15px;">
            <!-- Minimal Search Bar -->
            <form action="{{ route('front.user.shop', getParam()) }}" class="header-search-form-minimal">
              <input type="text" name="keyword" placeholder="{{ $keywords['Search for products...'] ?? __('Search for products...') }}">
              <button type="submit">
                <i class="far fa-search"></i>
              </button>
            </form>

            <!-- Icons Menu -->
            <ul class="menu header-icons" style="display:flex;justify-content:flex-end;gap:4px;align-items:center;list-style:none;margin-bottom:0;padding-left:0;">
              <!-- Account -->
              <li class="menu-item" style="position:relative;">
                <a href="{{ Auth::guard('customer')->check() ? route('customer.dashboard', getParam()) : route('customer.signup', getParam()) }}" class="sf-with-ul" style="display:flex;align-items:center;gap:6px;color:#0d0d0d;padding:8px 10px;font-size:13px;font-weight:500;">
                  <i class="fal fa-user" style="font-size:17px;"></i>
                </a>
                <ul class="setting-dropdown">
                  @guest('customer')
                    <li><a class="menu-link" href="{{ route('customer.login', getParam()) }}">{{ $keywords['Login'] ?? __('Login') }}</a></li>
                    <li><a class="menu-link" href="{{ route('customer.signup', getParam()) }}">{{ $keywords['Signup'] ?? __('Signup') }}</a></li>
                  @endguest
                  @auth('customer')
                    <li><a href="{{ route('customer.dashboard', getParam()) }}" class="menu-link">{{ $keywords['Dashboard'] ?? __('Dashboard') }}</a></li>
                    <li><a href="{{ route('customer.logout', getParam()) }}" class="menu-link">{{ $keywords['Logout'] ?? __('Logout') }}</a></li>
                  @endauth
                </ul>
              </li>
              <!-- Wishlist -->
              <li class="menu-item" style="position:relative;">
                <a href="{{ route('customer.wishlist', getParam()) }}" style="position:relative;color:#0d0d0d;padding:8px 10px;font-size:17px;display:flex;align-items:center;">
                  <i class="fal fa-heart"></i>
                  <span class="badge wishlist-count" style="position:absolute;top:4px;right:0px;background:#000;color:#fff;font-size:9px;min-width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;">{{ $wishListCount }}</span>
                </a>
              </li>
              <!-- Compare -->
              <li class="menu-item" style="position:relative;">
                <a href="{{ route('front.user.compare', getParam()) }}" style="position:relative;color:#0d0d0d;padding:8px 10px;font-size:17px;display:flex;align-items:center;">
                  <i class="fal fa-random"></i>
                  <span class="badge" id="compare-count" style="position:absolute;top:4px;right:0px;background:#000;color:#fff;font-size:9px;min-width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;">{{ $compareCount }}</span>
                </a>
              </li>
              @if($shop_settings->catalog_mode != 1)
                <!-- Cart -->
                <li class="menu-item" style="position:relative;">
                  <a href="{{ route('front.user.cart', getParam()) }}" class="cart-sidebar-toggle" style="position:relative;color:#0d0d0d;padding:8px 10px;font-size:17px;display:flex;align-items:center;">
                    <i class="fal fa-shopping-bag"></i>
                    <span class="badge cart-dropdown-count" style="position:absolute;top:4px;right:0px;background:#000;color:#fff;font-size:9px;min-width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;">{{ $cartCount }}</span>
                  </a>
                  <div class="cart-dropdown" id="cart-dropdown-header"></div>
                </li>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

</header>
<!-- Header v10 (Clothing) End -->
