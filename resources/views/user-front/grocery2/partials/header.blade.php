<!-- Header Start -->
<header class="grocery2-header">
  <!-- Top Promotion Bar -->
  <div class="grocery2-topbar">
    <div class="grocery2-header-container">
      <div class="grocery2-topbar-wrapper">
        <div class="topbar-item">🌾 {{ __('Vegetables 20% OFF Today') }}</div>
        <div class="topbar-item">🍎 {{ __('Buy 2 Get 1 Free on Fruits') }}</div>
        <div class="topbar-item">🥛 {{ __('Dairy Products Starting at $1.99') }}</div>
        <div class="topbar-item">🍞 {{ __('Fresh Bakery Items Daily') }}</div>
        <div class="topbar-item">🎉 {{ __('Weekend Special: Extra 15% OFF on All Items') }}</div>
      </div>
    </div>
  </div>

  <!-- Main Header Area -->
  <div class="grocery2-main-header">
    <div class="grocery2-header-container">
      <div class="grocery2-header-row">
        <!-- Logo -->
        <div class="grocery2-logo-col">
          <a href="{{ route('front.user.detail.view', getParam()) }}" class="grocery2-logo d-inline-flex align-items-center">
            @if(!empty(@$userBs->logo))
              <img src="{{ asset('assets/front/img/user/' . @$userBs->logo) }}" alt="{{ $user->shop_name ?? 'Logo' }}" style="max-height: 42px; width: auto; object-fit: contain;">
            @else
              <img src="{{ asset('assets/front/img/user/redesign_ecom_logo.png') }}" alt="{{ $user->shop_name ?? 'Logo' }}" style="max-height: 42px; width: auto; object-fit: contain;">
            @endif
          </a>
        </div>

        <!-- Search & Categories Bar -->
        <div class="grocery2-search-col">
          <form action="{{ route('front.user.shop', getParam()) }}" method="get" class="grocery2-search-form">
            <div class="grocery2-cat-select">
              <select name="category">
                <option value="">{{ $keywords['All Category'] ?? __('All Category') }}</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->slug }}" @selected(request()->input('category') == $category->slug)>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="grocery2-search-input-wrapper">
              <input type="text" name="keyword" value="{{ request()->input('keyword') }}" placeholder="{{ $keywords['Search for products, categories'] ?? __('Search for products, categories') }}...">
              <button type="submit" class="grocery2-search-btn">
                <i class="fal fa-search"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Nav & Quick Links -->
        <div class="grocery2-nav-col">
          <nav class="grocery2-navbar">
            <ul class="grocery2-nav-list">
              @php
                $links = json_decode($userMenus, true) ?? [];
              @endphp
              @if(empty($links))
                <li class="grocery2-nav-item"><a href="{{ route('front.user.detail.view', getParam()) }}" class="grocery2-nav-link active">Home <i class="fal fa-angle-down"></i></a></li>
                <li class="grocery2-nav-item"><a href="{{ route('front.user.shop', getParam()) }}" class="grocery2-nav-link">Category <i class="fal fa-angle-down"></i></a></li>
                <li class="grocery2-nav-item"><a href="{{ route('front.user.shop', getParam()) }}" class="grocery2-nav-link">Shop <i class="fal fa-angle-down"></i></a></li>
                <li class="grocery2-nav-item"><a href="{{ route('front.user.shop', getParam()) }}" class="grocery2-nav-link">Vendors <i class="fal fa-angle-down"></i></a></li>
                <li class="grocery2-nav-item"><a href="{{ route('front.user.shop', getParam()) }}" class="grocery2-nav-link">Contact</a></li>
              @else
                @foreach ($links as $link)
                  @php
                    $href = getUserHref($link, $userCurrentLang->id);
                  @endphp
                  <li class="grocery2-nav-item">
                    <a href="{{ $href }}" class="grocery2-nav-link {{ url()->current() == $href ? 'active' : '' }}" target="{{ $link['target'] ?? '_self' }}">
                      {{ $link['text'] }}
                    </a>
                  </li>
                @endforeach
              @endif
            </ul>
          </nav>
        </div>

        <!-- User Accounts & Cart -->
        <div class="grocery2-actions-col">
          <div class="grocery2-actions">
            <!-- Account -->
            <div class="grocery2-action-item grocery2-account-dropdown">
              <a href="javascript:void(0)" class="grocery2-action-link">
                <i class="fal fa-user"></i>
                <div class="grocery2-action-text-wrapper">
                  <span class="grocery2-action-subtitle">My Account</span>
                  <span class="grocery2-action-title">
                    @auth('customer')
                      {{ Auth::guard('customer')->user()->username }}
                    @else
                      Greetings, log in
                    @endauth
                  </span>
                </div>
              </a>
              <ul class="grocery2-dropdown-menu">
                @guest('customer')
                  <li><a href="{{ route('customer.login', getParam()) }}">{{ $keywords['Login'] ?? __('Login') }}</a></li>
                  <li><a href="{{ route('customer.signup', getParam()) }}">{{ $keywords['Signup'] ?? __('Signup') }}</a></li>
                @else
                  <li><a href="{{ route('customer.dashboard', getParam()) }}">{{ $keywords['Dashboard'] ?? __('Dashboard') }}</a></li>
                  <li><a href="{{ route('customer.logout', getParam()) }}">{{ $keywords['Logout'] ?? __('Logout') }}</a></li>
                @endauth
              </ul>
            </div>

            <!-- Wishlist -->
            <div class="grocery2-action-item">
              <a href="{{ route('customer.wishlist', getParam()) }}" class="grocery2-action-link" title="Wishlist">
                <div class="grocery2-icon-badge">
                  <i class="fal fa-heart"></i>
                  <span class="badge wishlist-count">{{ $wishListCount }}</span>
                </div>
              </a>
            </div>

            <!-- Compare -->
            <div class="grocery2-action-item">
              <a href="{{ route('front.user.compare', getParam()) }}" class="grocery2-action-link" title="Compare">
                <div class="grocery2-icon-badge">
                  <i class="fal fa-random"></i>
                  <span class="badge" id="compare-count">{{ $compareCount }}</span>
                </div>
              </a>
            </div>

            <!-- Cart -->
            @if ($shop_settings->catalog_mode != 1)
              <div class="grocery2-action-item">
                <a href="{{ route('front.user.cart', getParam()) }}" class="grocery2-action-link cart-sidebar-toggle" title="Cart">
                  <div class="grocery2-icon-badge">
                    <i class="fal fa-shopping-cart"></i>
                    <span class="badge cart-dropdown-count">{{ $cartCount }}</span>
                  </div>
                </a>
              </div>
            @endif

            <!-- Location Dropdown Badge -->
            <div class="grocery2-action-item grocery2-location-badge">
              <a href="javascript:void(0)" class="grocery2-location-btn">
                <i class="fal fa-map-marker-alt"></i>
                <span>{{ $keywords['Location'] ?? __('Location') }}</span>
              </a>
            </div>
          </div>
        </div>

        <!-- Mobile Menu Trigger -->
        <button class="mobile-menu-toggler d-xl-none" type="button">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</header>
