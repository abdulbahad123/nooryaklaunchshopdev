<!-- Header Start -->
<header class="grocery2-header">
  <!-- Top Promotion Bar -->
  <div class="grocery2-topbar">
    <div class="container-fluid">
      <div class="grocery2-topbar-wrapper">
        <div class="topbar-item"><span class="topbar-icon">🚚</span> Free Delivery on Orders Above $140</div>
        <div class="topbar-item"><span class="topbar-icon">🥬</span> Fresh vegetables 70% Off Today</div>
        <div class="topbar-item"><span class="topbar-icon">🍎</span> Buy 2 Get 1 Free on Fruits</div>
        <div class="topbar-item"><span class="topbar-icon">🍯</span> Harry Products Working at $100</div>
        <div class="topbar-item"><span class="topbar-icon">📦</span> Free delivery from shop</div>
      </div>
    </div>
  </div>

  <!-- Main Header Area -->
  <div class="grocery2-main-header">
    <div class="container">
      <div class="grocery2-header-row">
        <!-- Logo -->
        <div class="grocery2-logo-col">
          <a href="{{ route('front.user.detail.view', getParam()) }}" class="grocery2-logo">
            @if(!empty(@$userBs->logo))
              <img src="{{ asset('assets/front/img/user/' . @$userBs->logo) }}" alt="Easen">
            @else
              <span class="grocery2-logo-leaf">🍃</span><span class="grocery2-logo-text">Easen</span>
            @endif
          </a>
        </div>

        <!-- Search & Categories Bar -->
        <div class="grocery2-search-col">
          <form action="{{ route('front.user.shop', getParam()) }}" method="get" class="grocery2-search-form">
            <div class="grocery2-cat-select">
              <select name="category">
                <option value="">{{ $keywords['All Categories'] ?? __('All Categories') }}</option>
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
                      Greetings, sign in
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

            <!-- Compare -->
            <div class="grocery2-action-item">
              <a href="{{ route('front.user.compare', getParam()) }}" class="grocery2-action-link">
                <div class="grocery2-icon-badge">
                  <i class="fal fa-random"></i>
                  <span class="badge" id="compare-count">{{ $compareCount }}</span>
                </div>
              </a>
            </div>

            <!-- Wishlist -->
            <div class="grocery2-action-item">
              <a href="{{ route('customer.wishlist', getParam()) }}" class="grocery2-action-link">
                <div class="grocery2-icon-badge">
                  <i class="fal fa-heart"></i>
                  <span class="badge wishlist-count">{{ $wishListCount }}</span>
                </div>
              </a>
            </div>

            <!-- Cart -->
            @if ($shop_settings->catalog_mode != 1)
              <div class="grocery2-action-item">
                <a href="{{ route('front.user.cart', getParam()) }}" class="grocery2-action-link cart-sidebar-toggle">
                  <div class="grocery2-icon-badge">
                    <i class="fal fa-shopping-cart"></i>
                    <span class="badge cart-dropdown-count">{{ $cartCount }}</span>
                  </div>
                </a>
              </div>
            @endif
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
