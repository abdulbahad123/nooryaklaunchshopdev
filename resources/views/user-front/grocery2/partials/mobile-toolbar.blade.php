<!-- Mobile Bottom Persistent Navigation Bar -->
<div class="g2-mobile-bottom-bar d-xl-none">
  <a href="{{ route('front.user.detail.view', getParam()) }}" class="g2-bar-item {{ request()->routeIs('front.user.detail.view') ? 'active' : '' }}">
    <i class="fal fa-home"></i>
    <span>{{ $keywords['Home'] ?? __('Home') }}</span>
  </a>
  <a href="javascript:void(0)" class="g2-bar-item mobile-menu-toggler">
    <i class="fal fa-th"></i>
    <span>{{ $keywords['Categories'] ?? __('Categories') }}</span>
  </a>
  <a href="{{ route('front.user.shop', getParam()) }}" class="g2-bar-item {{ request()->routeIs('front.user.shop') ? 'active' : '' }}">
    <i class="fal fa-search"></i>
    <span>{{ $keywords['Search'] ?? __('Search') }}</span>
  </a>
  <a href="javascript:void(0)" class="g2-bar-item grocery2-location-btn">
    <i class="fal fa-map-marker-alt"></i>
    <span>{{ $keywords['Select Location'] ?? __('Select Location') }}</span>
  </a>
  <a href="javascript:void(0)" class="g2-bar-item cart-sidebar-toggle">
    <div class="g2-bar-icon-wrap">
      <i class="fal fa-shopping-bag"></i>
      <span class="badge cart-dropdown-count">{{ $cartCount ?? 0 }}</span>
    </div>
    <span>{{ $keywords['Cart'] ?? __('Cart') }}</span>
  </a>
  <a href="{{ route('customer.dashboard', getParam()) }}" class="g2-bar-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
    <i class="fal fa-user"></i>
    <span>{{ $keywords['Account'] ?? __('Account') }}</span>
  </a>
</div>
