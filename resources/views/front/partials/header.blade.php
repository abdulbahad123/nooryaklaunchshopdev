<!-- Header Start -->
<header class="header-area header-inner-page">
  <div class="main-responsive-nav">
    <div class="container">
      <div class="main-responsive-menu">
        <div class="logo">
          <a href="{{ route('front.index') }}">
            <img src="{{ asset('assets/front/img/' . $bs->logo) }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'" alt="logo">
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="main-navbar">
    <div class="container-fluid px-lg-5 px-3">
      <nav class="navbar navbar-expand-lg">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('front.index') }}">
          <img src="{{ asset('assets/front/img/' . $bs->logo) }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'" alt="Logo">
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
            <!-- <li class="nav-item mobile-menu-actions-li">
              <div class="menu-action-item-wrapper">
                @guest
                  <div class="menu-action-item">
                    <a href="{{ route('front.contact') }}" class="btn-ls-outline">
                      <span>{{ __('Book Demo') }}</span>
                    </a>
                  </div>
                  <div class="menu-action-item">
                    <a href="{{ route('front.pricing') }}" class="btn-ls-primary">
                      <span>{{ __('Start Free Trial') }}</span>
                    </a>
                  </div>
                @endguest
                @auth
                  <div class="menu-action-item">
                    <a href="{{ route('user-dashboard') }}" class="btn-ls-primary">
                      <span>{{ __('Dashboard') }}</span>
                    </a>
                  </div>
                @endauth
              </div>
            </li> -->

            <!-- <li class="mobile-menu-footer">
              <div class="mobile-menu-footer-content">
                <h3>{{ __('INFORMATION') }}</h3>
                <ul class="info-list">
                  @if (!empty($be->contact_numbers))
                    @php
                      $numbers = explode(',', $be->contact_numbers);
                    @endphp
                    <li>
                      <i class="fal fa-phone"></i>
                      <span>
                        {!! implode(
                            '<br>',
                            array_map(fn($num) => '<a href="tel:' . trim($num) . '">' . trim($num) . '</a>', $numbers),
                        ) !!}
                      </span>
                    </li>
                  @endif
                  @if (!empty($be->contact_mails))
                    <li>
                      <i class="fal fa-envelope"></i>
                      <span>
                        <a href="mailto:{{ $be->contact_mails }}">{{ $be->contact_mails }}</a>
                      </span>
                    </li>
                  @endif
                  @if (!empty($be->contact_addresses))
                    <li>
                      <i class="fal fa-map-marker-alt"></i>
                      <span>{{ $be->contact_addresses }}</span>
                    </li>
                  @endif
                </ul>

                @if (isset($socials) && $socials->count() > 0)
                  <h3>{{ __('FOLLOW US') }}</h3>
                  <div class="social-links">
                    @foreach ($socials as $social)
                      @php $url = preg_match('/^https?:\/\//', $social->url) ? $social->url : 'http://' . $social->url; @endphp
                      <a href="{{ $url }}" target="_blank"><i class="{{ $social->icon }}"></i></a>
                    @endforeach
                  </div>
                @endif
              </div>
            </li> -->
          </ul>

        </div>

        <div class="side-option">
          @guest
            <div class="item">
              <a href="{{ route('front.contact') }}" class="btn-ls-outline btn-sm">
                <span>{{ __('Book Demo') }}</span>
              </a>
            </div>
            <div class="item">
              <a href="{{ route('user.login') }}" class="btn-ls-primary btn-sm">
                <span>{{ __('Login') }}</span>
              </a>
            </div>
          @endguest
          @auth
            <div class="item">
              <a href="{{ route('user-dashboard') }}" class="btn-ls-primary btn-sm">
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
