<!-- Mobile Bottom Navigation Bar -->
@php
  $defaultPackage = \App\Models\Package::where('status', '1')->where('featured', '1')->first();
  $defaultPackageId = $defaultPackage ? $defaultPackage->id : 1;
@endphp
<div class="mobile-bottom-nav-bar">
  <div class="mobile-nav-items">
    <!-- 1. HOME -->
    <a href="{{ route('front.index') }}" class="mobile-nav-item {{ request()->routeIs('front.index') ? 'active' : '' }}">
      <div class="mobile-nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v6H4a1 1 0 0 1-1-1V9.5z"/>
        </svg>
      </div>
      <span class="mobile-nav-label">{{ __('HOME') }}</span>
    </a>

    <!-- 2. THEME -->
    <a href="{{ route('front.templates.view') }}" class="mobile-nav-item {{ request()->routeIs('front.templates.view') ? 'active' : '' }}">
      <div class="mobile-nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7" rx="1.5"/>
          <rect x="14" y="3" width="7" height="7" rx="1.5"/>
          <rect x="14" y="14" width="7" height="7" rx="1.5"/>
          <rect x="3" y="14" width="7" height="7" rx="1.5"/>
        </svg>
      </div>
      <span class="mobile-nav-label">{{ __('THEMES') }}</span>
    </a>

    <!-- 3. PRICING --> 
    <a href="{{ route('front.pricing') }}" class="mobile-nav-item {{ request()->routeIs('front.pricing') ? 'active' : '' }}">
      <div class="mobile-nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m20.59 13.41-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
          <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
      </div>
      <span class="mobile-nav-label">{{ __('PRICING') }}</span>
    </a>

    <!-- 4. ACCOUNT -->
    @auth
      <a href="{{ route('user-dashboard') }}" class="mobile-nav-item {{ request()->routeIs('user-dashboard') ? 'active' : '' }}">
    @else
      <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $defaultPackageId]) }}" class="mobile-nav-item {{ (request()->routeIs('user.login') || request()->routeIs('user.register') || request()->routeIs('front.register.view')) ? 'active' : '' }}">
    @endauth
      <div class="mobile-nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
      </div>
      <span class="mobile-nav-label">{{ __('ACCOUNT') }}</span>
    </a>

    <!-- 5. CONTACT -->
    <a href="{{ route('front.contact') }}" class="mobile-nav-item {{ request()->routeIs('front.contact') ? 'active' : '' }}">
      <div class="mobile-nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 18v-6a9 9 0 0 1 18 0v6"/>
          <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>
        </svg>
      </div>
      <span class="mobile-nav-label">{{ __('CONTACT') }}</span>
    </a>
  </div>

  <!-- iOS Home Indicator bar -->
  <div class="mobile-home-indicator"></div>
</div>

<style>
  /* Prevent horizontal overflow on mobile viewports */
  @media (max-width: 991px) {
    html, body {
      overflow-x: hidden !important;
      max-width: 100vw !important;
    }

    /* Floating Mobile Bottom Navigation Bar with Left & Right Gaps */
    .mobile-bottom-nav-bar {
      display: block !important;
      position: fixed !important;
      bottom: calc(10px + env(safe-area-inset-bottom, 0px)) !important;
      left: 14px !important;
      right: 14px !important;
      width: calc(100% - 28px) !important;
      max-width: 480px !important;
      margin: 0 auto !important;
      z-index: 999999 !important;
      background: #0c101d !important;
      border-radius: 20px !important;
      overflow: hidden !important;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.12) !important;
      padding: 8px 0 6px !important;
      user-select: none !important;
      -webkit-user-select: none !important;
      backdrop-filter: blur(16px) !important;
      -webkit-backdrop-filter: blur(16px) !important;
      box-sizing: border-box !important;
    }

    /* Top Multi-Color Gradient Glow Border Line across full bar width */
    .mobile-bottom-nav-bar::before {
      content: '' !important;
      position: absolute !important;
      top: 0 !important;
      left: 0 !important;
      right: 0 !important;
      width: 100% !important;
      height: 3.5px !important;
      background: linear-gradient(90deg, #ff4500 0%, #e60067 22%, #9c27b0 45%, #0070f3 72%, #00f2fe 100%) !important;
      border-radius: 20px 20px 0 0 !important;
    }

    .mobile-nav-items {
      display: flex !important;
      align-items: center !important;
      justify-content: space-around !important;
      padding-top: 4px !important;
      width: 100% !important;
      margin: 0 !important;
      box-sizing: border-box !important;
    }

    .mobile-nav-item {
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      text-decoration: none !important;
      color: #ffffff !important;
      padding: 4px 0 !important;
      transition: all 0.25s ease !important;
      flex: 1 !important;
      text-align: center !important;
    }

    .mobile-nav-icon {
      position: relative !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 26px !important;
      height: 26px !important;
      margin-bottom: 3px !important;
      color: #ffffff !important;
      transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275), color 0.25s ease, filter 0.25s ease !important;
    }

    .mobile-nav-icon svg {
      width: 22px !important;
      height: 22px !important;
    }

    .mobile-nav-label {
      font-size: 10px !important;
      font-weight: 700 !important;
      letter-spacing: 0.6px !important;
      text-transform: uppercase !important;
      color: #ffffff !important;
      transition: color 0.25s ease, text-shadow 0.25s ease !important;
      line-height: 1.2 !important;
    }

    /* Active State (Glowing Orange) */
    .mobile-nav-item.active .mobile-nav-icon {
      color: #ff5722 !important;
      transform: translateY(-2px) scale(1.08) !important;
      filter: drop-shadow(0 2px 8px rgba(255, 87, 34, 0.75)) !important;
    }

    .mobile-nav-item.active .mobile-nav-label {
      color: #ff5722 !important;
      text-shadow: 0 0 10px rgba(255, 87, 34, 0.45) !important;
    }

    /* Touch feedback */
    .mobile-nav-item:active .mobile-nav-icon {
      transform: scale(0.92) !important;
    }

    /* Home Indicator line */
    .mobile-home-indicator {
      display: none !important;
    }

    /* Reposition floating widgets above floating mobile bottom bar */
    #WAButton,
    .custom-wa-widget,
    .fab-btn,
    .go-top {
      bottom: 85px !important;
    }

    body {
      padding-bottom: 85px !important;
    }
  }

  @media (min-width: 992px) {
    .mobile-bottom-nav-bar {
      display: none !important;
    }
  }
</style>
