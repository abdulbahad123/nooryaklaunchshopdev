<div class="mobile-menu-overlay"></div>
<!-- Responsive Mobile Menu -->
<div class="mobile-menu">
  <div class="mobile-menu-wrapper">
    <div class="mobile-menu-top">
      <div class="logo">
        <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
          <img
            src="{{ !empty(@$userBs->logo) ? asset('assets/front/img/user/' . @$userBs->logo) : asset('assets/front/img/logo.png') }}"
            alt="logo">
        </a>
      </div>
      <span class="mobile-menu-close"><i class="fal fa-times"></i></span>
    </div>
    <!-- mobile-search -->
    <div class="header-search mobile-search">
      <form class="header-search-form rounded-pill" autocomplete="off"
        action="{{ route('front.user.shop', getParam()) }}">
        <div class="search-input">
          <input type="text" class="form-control" value=""
            placeholder="{{ $keywords['Im searching for'] ?? __('Im searching for') }}..." name="keyword">
        </div>
        <button class="btn btn-icon btn-icon-2" type="submit"><i class="fal fa-search"></i></button>
      </form>
    </div>
  </div>

  <!-- menu-action-item-area -->
  <div class="menu-action-item-area">
    <ul class="menu-action-item-wrapper">
      <li class="menu-action-item">
        <a href="javascript:void(0)">
          <span class="icon"><i class="fal fa-globe"></i></span>{{ convertUtf8($userCurrentLang->name) }}
          <span class="plus-icon"><i class="fal fa-plus"></i></span>
        </a>
        <ul class="setting-dropdown">
          @foreach ($userLangs as $userLang)
            <li>
              <a href="{{ route('front.user.changeUserLanguage', ['code' => $userLang->code, getParam()]) }}"
                class="menu-link">{{ convertUtf8($userLang->name) }}</a>
            </li>
          @endforeach
        </ul>
      </li>
      <li class="menu-action-item">
        <a href="javascript:void(0)">
          <span class="icon">
            <i>{{ $userCurrentCurr->symbol }}</i>
          </span>{{ convertUtf8($userCurrentCurr->text) }}<span class="plus-icon"><i class="fal fa-plus"></i></span>
        </a>
        <ul class="setting-dropdown">
          @foreach ($userCurrency as $userCurr)
            <li>
              <a href="{{ route('front.user.changeUserCurrency', ['id' => $userCurr->id, getParam()]) }}"
                class="menu-link">{{ $userCurr->text }}</a>
            </li>
          @endforeach
        </ul>
      </li>
      <li class="menu-action-item">
        <a href="javascript:void(0)">
          <span class="icon"><i class="fal fa-user"></i></span>
          {{ $keywords['My Account'] ?? __('My Account') }}
          <span class="plus-icon"><i class="fal fa-plus"></i></span>
        </a>
        <ul class="setting-dropdown">
          @guest('customer')
            <li>
              <a class="menu-link"
                href="{{ route('customer.login', getParam()) }}">{{ $keywords['Login'] ?? __('Login') }}</a>
            </li>
            <li>
              <a class="menu-link"
                href="{{ route('customer.signup', getParam()) }}">{{ $keywords['Signup'] ?? __('Signup') }}</a>
            </li>
          @endguest
          @auth('customer')
            <li>
              <a class="menu-link"
                href="{{ route('customer.dashboard', getParam()) }}">{{ $keywords['Dashboard'] ?? __('Dashboard') }}</a>
            </li>
            <li>
              <a class="menu-link"
                href="{{ route('customer.logout', getParam()) }}">{{ $keywords['Logout'] ?? __('Logout') }}</a>
            </li>
          @endguest
        </ul>
      </li>
    </ul>
  </div>
</div>
<!-- Responsive Mobile Menu -->

<!-- Responsive Bottom Toolbar -->
<div class="mobile-bottom-toolbar d-block d-xl-none">
  <div class="container">
    <nav class="toolbar" id="nav">
      <ul class="toolbar-nav">
        <li class="tolbar-item">
          <a class="{{ request()->routeIs('front.user.detail.view') ? 'active' : '' }}"
            href="{{ route('front.user.detail.view', getParam()) }}" title="" target="_self">
            <i class="fal fa-home"></i>
            <span>{{ $keywords['Home'] ?? __('Home') }}</span>
          </a>
        </li>
        <li class="tolbar-item">
          <a class="{{ request()->routeIs('front.user.shop') ? 'active' : '' }}"
            href="{{ route('front.user.shop', getParam()) }}" target="_self">
            <i class="fal fa-shopping-cart"></i>
            <span>{{ $keywords['Shop'] ?? __('Shop') }}</span>
          </a>
        </li>
        <li class="tolbar-item">
          <a class="{{ request()->routeIs('front.user.cart') ? 'active' : '' }} cart-sidebar-toggle"
            href="{{ route('front.user.cart', getParam()) }}" title="" target="_self">
            <i class="fal fa-shopping-bag"></i>
            {{ $keywords['Cart'] ?? __('Cart') }}
            <span class="badge cart-dropdown-count">{{ $cartCount }}</span>
          </a>
        </li>
        <li class="tolbar-item">
          <a class="{{ request()->routeIs('customer.wishlist') ? 'active' : '' }}"
            href="{{ route('customer.wishlist', getParam()) }}" title="" target="_self">
            <i class="fal fa-heart"></i>
            {{ $keywords['Wishlist'] ?? __('Wishlist') }}
            <span class="badge wishlist-count">{{ $wishListCount }}</span>
          </a>
        </li>
        <li class="tolbar-item">
          <a class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
            href="{{ route('customer.dashboard', getParam()) }}" title="" target="_self">
            <i class="fal fa-user"></i>
            <span>{{ $keywords['Account'] ?? __('Account') }}</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</div>
<!-- Responsive Bottom Toolbar -->
