<!-- Header Start -->
<header class="header header-area header-v8 header-mt-fix header-fixed">
  <div class="header-top">
    <div class="container">
      <div class="row">
        <div class="col-xl-6 col-lg-6">
          <div class="header-left">
            <ul>
              <li>
                {{ preg_replace('/\bHours\b\s*/i', '', $header->header_text ?? '') }}
                @php
                  $emails = !empty(@$userBs->email) ? [$userBs->email] : (!empty(@$user->email) ? [$user->email] : (!empty(@$userContact->contact_mails) ? explode(',', $userContact->contact_mails) : []));
                @endphp
                @if (count($emails) > 0)
                  <span class="mx-2">|</span>
                  @foreach ($emails as $email)
                    <i class="fal fa-envelope"></i>
                    <a href="mailTo:{{ trim($email) }}" style="color: inherit;">{{ trim($email) }}</a>{{ !$loop->last ? ', ' : '' }}
                  @endforeach
                @endif
              </li>
            </ul>
          </div>
        </div>

        <div class="col-xl-6 col-lg-6">
          <div class="header-right">
            <ul class="menu sf-js-enabled" style="touch-action: pan-y;">
              <li class="menu-item">
                @if ($userCurrentLang->id)
                  <a href="javascript:void(0)" class="sf-with-ul"><i
                      class="fal fa-globe"></i>{{ convertUtf8($userCurrentLang->name) }}</a>
                @endif
                <ul class="setting-dropdown" style="display: none;">
                  @foreach ($userLangs as $userLang)
                    <li>
                      <a href="{{ route('front.user.changeUserLanguage', ['code' => $userLang->code, getParam()]) }}"
                        class="menu-link">
                        {{ convertUtf8($userLang->name) }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </li>
              {{-- Currency Switcher hidden --}}
              {{-- <li class="menu-item">
                @if ($userCurrentCurr->id)
                  <a href="#" class="sf-with-ul">{{ $userCurrentCurr->symbol }}
                    &nbsp;{{ convertUtf8($userCurrentCurr->text) }}</a>
                @endif
                <ul class="setting-dropdown" style="display: none;">
                  @foreach ($userCurrency as $userCurr)
                    <li>
                      <a href="{{ route('front.user.changeUserCurrency', ['id' => $userCurr->id, getParam()]) }}"
                        class="menu-link">{{ $userCurr->text }}</a>
                    </li>
                  @endforeach
                </ul>
              </li> --}}
              <li class="menu-item">
                <a href="#" class="sf-with-ul"><i
                    class="fal fa-user"></i>{{ $keywords['My Account'] ?? __('My Account') }}</a>
                <ul class="setting-dropdown" style="display: none;">
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
                    @php $authUserInfo = Auth::guard('customer')->user(); @endphp
                    <li>
                      <a href="{{ route('customer.dashboard', getParam()) }}"
                        class="menu-link">{{ $keywords['Dashboard'] ?? __('Dashboard') }}</a>
                    </li>
                    <li>
                      <a href="{{ route('customer.logout', getParam()) }}"
                        class="menu-link">{{ $keywords['Logout'] ?? __('Logout') }}</a>
                    </li>
                  @endauth
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Navbar -->
  <div class="mobile-navbar d-block d-xl-none">
    <div class="container">
      <div class="mobile-navbar-inner">
        <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
          <img
            src="{{ !empty(@$userBs->logo) ? asset('assets/front/img/user/' . @$userBs->logo) : asset('assets/front/img/logo.png') }}"
            alt="logo">
        </a>
        <button class="mobile-menu-toggler" type="button">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>

  <div class="header-middle sticky-header">
    <div class="container">
      <div class="header-left">
        <div class="brand-logo">
          <a href="{{ route('front.user.detail.view', getParam()) }}" title="" target="_self">
            <img
              src="{{ !empty(@$userBs->logo) ? asset('assets/front/img/user/' . @$userBs->logo) : asset('assets/front/img/logo.png') }}"
              alt="Logo">
          </a>
        </div>
      </div>
      <div class="header-center">
        <nav class="menu mobile-nav">
          <ul class="menu-right me-auto">
            @php
              $links = json_decode($userMenus, true);
            @endphp
            @foreach ($links as $link)
              @php
                $href = getUserHref($link, $userCurrentLang->id);
              @endphp
              @if (!array_key_exists('children', $link))
                <li class="nav-item">
                  <a class="nav-link  {{ url()->current() == $href ? 'active' : '' }}" target="{{ $link['target'] }}"
                    href="{{ $href }}">{{ $link['text'] }}</a>
                </li>
              @else
                <li class="nav-item">
                  <a href="{{ $href }}" target="{{ $link['target'] }}"
                    class="nav-link {{ url()->current() == $href ? 'active' : '' }}">{{ $link['text'] }}<i
                      class="fal fa-plus"></i></a>
                  <ul class="submenu">
                    @foreach ($link['children'] as $level2)
                      @php
                        $l2Href = getUserHref($level2, $userCurrentLang->id);
                      @endphp
                      <li><a href="{{ $l2Href }}" target="{{ $level2['target'] }}">{{ $level2['text'] }}</a>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @endif
            @endforeach
          </ul>
        </nav>
      </div>
      <div class="header-right">
        <ul class="menu">
          <li class="menu-item menu-search">
            <form action="{{ route('front.user.shop', getParam()) }}">
              <input type="text" class="search-text" name="keyword"
                placeholder="{{ $keywords['Search Product'] ?? __('Search Product') }}" required>
              <button type="button" class="search-btn">
                <i class="far fa-search"></i>
              </button>
            </form>
          </li>
          <li class="menu-item menu-wishlist">
            <a href="{{ route('customer.wishlist', getParam()) }}" class="menu-link">
              <i class="fal fa-heart">
                <span class="badge wishlist-count">{{ $wishListCount }}</span>
              </i>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{ route('front.user.compare', getParam()) }}" class="menu-link ">
              <i class="fal fa-random">
                <span class="badge" id="compare-count">{{ $compareCount }}</span>
              </i>
            </a>
          </li>
          @if ($shop_settings->catalog_mode != 1)
            <li class="menu-item">
              <a href="{{ route('front.user.cart', getParam()) }}" class="menu-link cart-sidebar-toggle"><i class="fal fa-shopping-cart"><span
                    class="badge cart-dropdown-count">{{ $cartCount }}</span></i></a>
              <div class="cart-dropdown" id="cart-dropdown-header">

              </div>
            </li>
          @endif
        </ul>
      </div>
    </div>
  </div>
</header>
<!-- Header End -->
