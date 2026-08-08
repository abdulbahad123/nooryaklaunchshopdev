@php
  $isUrbanTheme = (!empty($userBs) && $userBs->theme == 'clothing');
  $isFashionTheme = (!empty($userBs) && $userBs->theme == 'fashion');
  $isSkinflowTheme = (!empty($userBs) && $userBs->theme == 'skinflow');
  $isJewelleryTheme = (!empty($userBs) && $userBs->theme == 'jewellery');

  $reduceTitlePadding = ($isFashionTheme || $isSkinflowTheme || $isJewelleryTheme);

  if ($isUrbanTheme) {
    $breadcrumbBgImg = asset('assets/front/img/shop_banner_bg.png');
  } elseif ($isFashionTheme) {
    $breadcrumbBgImg = asset('assets/front/img/fashion_banner_bg.png');
  } else {
    $breadcrumbBgImg = (!is_null($userBe) && !empty($userBe->breadcrumb) && file_exists(public_path('assets/front/img/user/' . $userBe->breadcrumb)))
      ? asset('assets/front/img/user/' . $userBe->breadcrumb)
      : asset('assets/front/img/default_shop_banner.png');
  }
@endphp
<div class="page-title-area header-next" style="background-image: url('{{ $breadcrumbBgImg }}'); background-size: cover; background-position: center center; position: relative; {{ $reduceTitlePadding ? 'padding-top: 70px !important; padding-bottom: 50px !important;' : '' }}">
  @if($isUrbanTheme)
    <div class="page-title-overlay" style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, rgba(248, 245, 240, 0.65) 100%); pointer-events: none;"></div>
  @elseif($isFashionTheme)
    <div class="page-title-overlay" style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(250, 247, 242, 0.7) 100%); pointer-events: none;"></div>
  @endif
  <img class="bg-img" src="{{ $breadcrumbBgImg }}" data-src="{{ $breadcrumbBgImg }}" alt="Banner" style="display:none;">
  <div class="container position-relative" style="z-index: 2;">
    <div class="row align-items-center justify-content-between">
      <div class="col-lg-4 col-md-5 col-sm-12">
        <div class="content text-start">
          <h2 style="{{ $isUrbanTheme || $isFashionTheme ? 'font-weight: 600; color: #1a1a1a; letter-spacing: -0.3px;' : '' }}">@yield('breadcrumb_title')</h2>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-start mb-0" style="background: transparent; padding: 0;">
              <li class="breadcrumb-item"><a
                  href="{{ route('front.user.detail.view', getParam()) }}" style="{{ $isUrbanTheme || $isFashionTheme ? 'color: #666; text-decoration: none;' : '' }}">{{ $keywords['Home'] ?? __('Home') }}</a></li>
              <li class="breadcrumb-item active" aria-current="page" style="{{ $isUrbanTheme || $isFashionTheme ? 'color: #a87d52; font-weight: 500;' : '' }}">
                @if (
                    !request()->routeIs('customer.itemcheckout.offline.success') &&
                        !request()->routeIs('customer.success.page') &&
                        !request()->routeIs('front.user.productDetails') &&
                        !request()->routeIs('user-front.blog_details'))
                  @yield('breadcrumb_title')
                @else
                  @yield('breadcrumb_second_title')
                @endif
              </li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="col-lg-8 col-md-7 col-sm-12">
        @yield('breadcrumb_right')
      </div>
    </div>
  </div>
</div>
