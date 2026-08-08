@extends('user-front.layout')
@section('meta-description', !empty($seo) ? $seo->home_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->home_meta_keywords : '')
@section('page-title', $keywords['Home'] ?? __('Home'))
@section('og-meta')
  <!--- For Social Media Share Thumbnail --->
  <meta property="og:title" content="{{ $user->username }}">
  <meta property="og:image"
    content="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
  <!--- For Social Media Share Thumbnail --->
@endsection

@php
  $additional_section_status = json_decode($userBs->additional_section_status, true);
@endphp

@section('content')

  @if ($ubs->slider_section == 1)
    <!-- Home Slider Start -->
    <section class="home-slider home-slider-4 pt-40 header-next">
      <div class="container">
        <div class="row">
          <div class="{{ $ubs->top_right_banner_section == 1 ? 'col-lg-8' : 'col-lg-12' }} mb-30">
            <div class="animated-slider" id="home-slider-4"
              data-slick='{
            "dots": false,
            "speed": 500,
            "autoplaySpeed": 6000,
            "dots": true
          }'>
              @if (count($sliders) > 0)
                @foreach ($sliders->where('is_static', 0) as $slider)
                  <div class="slider-item ratio">
                    <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/hero_slider/' . $slider->img) }}" alt="Banner">
                    <div class="d-flex align-items-center">
                      <div class="slider-content">
                        <span class="sub-title" data-animation="animate__fadeInUp"
                          data-delay=".3s">{{ $slider->title }}</span>
                        <h1 class="title lc-2" data-animation="animate__fadeInDown" data-delay=".1s">
                          {{ $slider->subtitle }}
                        </h1>
                        <p class="text-lg lc-3" data-animation="animate__fadeInDown" data-delay=".2s">{{ $slider->text }}
                        </p>
                        @if ($slider->btn_url && $slider->btn_name)
                          <a href="{{ $slider->btn_url }}" class="btn btn-lg btn-primary rounded-pill icon-end"
                            data-animation="animate__fadeInUp" data-delay=".3s">{{ $slider->btn_name }}
                            <span class="icon"><i class="fal fa-arrow-right"></i></span>
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                <div class="slider-item ratio">
                  <img class="lazyload bg-img blur-up" src="{{ asset('assets/user-front/images/electri/hero.jpg') }}"
                    alt="Banner">
                  <div class="d-flex align-items-center">
                    <div class="slider-content">
                      <span class="sub-title" data-animation="animate__fadeInUp"
                        data-delay=".3s">{{ $keywords['Slider title'] ?? __('Slider title') }}</span>
                      <h1 class="title" data-animation="animate__fadeInDown" data-delay=".1s">
                        {{ $keywords['Slider subtitle'] ?? __('Slider subtitle') }}
                      </h1>
                      <p class="text-lg" data-animation="animate__fadeInDown" data-delay=".2s">
                        {{ $keywords['Lorem ipsum dolor sit amet consectetur'] ?? __('Lorem ipsum dolor sit amet consectetur') }}
                      </p>
                      <a href="#" class="btn btn-lg btn-primary rounded-pill icon-end"
                        data-animation="animate__fadeInUp"
                        data-delay=".3s">{{ $keywords['Explore More'] ?? __('Explore More') }}
                        <span class="icon"><i class="fal fa-arrow-right"></i></span>
                      </a>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>

          @if ($ubs->top_right_banner_section == 1)
            <div class="col-lg-4 d-none d-lg-block">
              <div class="row">

                @if ($banners)
                  @for ($i = 0; $i < count($banners); $i++)
                    @if ($banners[$i]->position == 'top_right')
                      <div class="col-xl-12 col-lg-12 col-md-6">
                        <div class="banner-sm mb-20 ratio ratio-21-9">
                          <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                            alt="Banner">
                          <div class="banner-content">
                            <div class="content-inner">
                              <span class="sub-title mb-2 text-xsm">{{ $banners[$i]->title }}</span>
                              <h3 class="mb-10">{{ $banners[$i]->subtitle }} </h3>
                              @if ($banners[$i]->button_text && $banners[$i]->banner_url)
                                <a href="{{ $banners[$i]->banner_url }}"
                                  class="btn btn-sm btn-primary rounded-pill icon-end shadow">{{ $banners[$i]->button_text }}
                                  <span class="icon"><i class="fal fa-arrow-right"></i></span>
                                </a>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endfor
                @endif
              </div>
            </div>

            @if ($banners)
              @php
                $topRightBanners = $banners->where('position', 'top_right')->values();
              @endphp
              @if (count($topRightBanners) > 0)
                <div class="col-12 d-block d-lg-none mb-30">
                  <div class="banner-slider-mobile" id="banner-slider-mobile-electronics">
                    @foreach ($topRightBanners as $banner)
                      <div class="px-2">
                        <div class="banner-sm ratio ratio-21-9">
                          <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}"
                            alt="Banner">
                          <div class="banner-content">
                            <div class="content-inner">
                              <span class="sub-title mb-2 text-xsm">{{ $banner->title }}</span>
                              <h3 class="mb-10">{{ $banner->subtitle }} </h3>
                              @if ($banner->button_text && $banner->banner_url)
                                <a href="{{ $banner->banner_url }}"
                                  class="btn btn-sm btn-primary rounded-pill icon-end shadow">{{ $banner->button_text }}
                                  <span class="icon"><i class="fal fa-arrow-right"></i></span>
                                </a>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            @endif
          @endif
        </div>
      </div>
    </section>
    <!-- Home Slider End -->
  @endif

  @if (count($after_hero) > 0)
    @foreach ($after_hero as $cusHero)
      @if (isset($additional_section_status[$cusHero->id]))
        @if ($additional_section_status[$cusHero->id] == 1)
          @php
            $cusHeroContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusHero->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusHeroContent,
              'possition' => $cusHero->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->featuers_section == 1)
    <section class="featured featured-2  pb-70">
      <div class="container">
        <div class="row align-items-center gx-xl-5">
          @foreach ($how_work_steps as $how_work_step)
            <div class="col-xxl-1-5 col-xl-4 col-lg-4 col-sm-6 mb-30">
              <div class="featured-item">
                <div class="icon color-primary">
                  <i class="{{ $how_work_step->icon }}"></i>
                </div>
                <div class="content">
                  <h5>{{ $how_work_step->title }}</h5>
                  <p class="text-sm lc-2">{{ $how_work_step->text }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
    <!-- Featured End -->
  @endif

  @if (count($after_featuers) > 0)
    @foreach ($after_featuers as $cusHowWork)
      @if (isset($additional_section_status[$cusHowWork->id]))
        @if ($additional_section_status[$cusHowWork->id] == 1)
          @php
            $cusHowWorkContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusHowWork->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusHowWorkContent,
              'possition' => $cusHowWork->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->category_section == 1)
    <!-- Category Start -->
    <section class="category pb-100">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-center mb-40">
              <h2 class="title mb-20">
                {{ $userSec->category_section_title ?? ($keywords['Categories'] ?? __('Categories')) }}
              </h2>
              <p class="text mx-auto mb-0">{{ $userSec->category_section_subtitle ?? '' }}</p>
            </div>
          </div>

          <div class="col-12">
            @if (count($item_categories) == 0)
              <h5 class="title text-center mb-20">
                {{ $userSec->category_section_title ?? ($keywords['NO CATEGORIES FOUND'] ?? __('NO CATEGORIES FOUND')) }}
              </h5>
            @else
              {{-- Slick slider: links straight to shop filtered by category --}}
              <div class="category-slider" id="cat-slider-electronics"
                data-slick='{"dots": true, "arrows": true, "autoplay": true, "autoplaySpeed": 3000, "slidesToShow": 5, "slidesToScroll": 1,
                  "responsive": [
                    {"breakpoint": 1200, "settings": {"slidesToShow": 4}},
                    {"breakpoint": 992,  "settings": {"slidesToShow": 3}},
                    {"breakpoint": 768,  "settings": {"slidesToShow": 2}},
                    {"breakpoint": 576,  "settings": {"slidesToShow": 1}}
                  ]
                }'>
                @foreach ($item_categories as $cat)
                  <div class="px-2">
                    <a href="{{ route('front.user.shop', [getParam(), 'category=' . $cat->slug]) }}"
                       class="d-block text-center text-decoration-none category-item category-inline mb-30">
                      <div class="category-img mb-15" style="border-radius:12px;overflow:hidden;">
                        <img class="lazyload blur-up absolute-img"
                          src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/categories/' . $cat->image) }}"
                          alt="{{ $cat->name }}"
                          style="border-radius:12px;">
                      </div>
                      <h4 class="category-title lc-1" style="font-size:14px; font-weight:600;">{{ $cat->name }}</h4>
                      <span class="text-muted" style="font-size:12px;">{{ count($cat->items) }}+ {{ $keywords['Items'] ?? __('Items') }}</span>
                    </a>
                  </div>
                @endforeach
              </div>

              <div class="text-center mt-20">
                <a href="{{ route('front.user.shop', getParam()) }}"
                   class="btn btn-lg btn-primary rounded-pill icon-end shadow">
                  {{ $keywords['Explore More'] ?? __('Explore More') }}
                  <span class="icon"><i class="fal fa-arrow-right"></i></span>
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>
    <!-- Category End -->
  @endif

  @if (count($after_category) > 0)
    @foreach ($after_category as $cusCategory)
      @if (isset($additional_section_status[$cusCategory->id]))
        @if ($additional_section_status[$cusCategory->id] == 1)
          @php
            $cusCategoryContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusCategory->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusCategoryContent,
              'possition' => $cusCategory->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->flash_section == 1)
    @include('user-front.electronics.flash_content')
  @endif

  @if (count($after_flash) > 0)
    @foreach ($after_flash as $cusFlash)
      @if (isset($additional_section_status[$cusFlash->id]))
        @if ($additional_section_status[$cusFlash->id] == 1)
          @php
            $cusFlashContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusFlash->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusFlashContent,
              'possition' => $cusFlash->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->middle_banner_section == 1)
    <!-- Banner Collection Start -->
    <div class="banner-collection pb-70">
      <div class="container">
        <div class="row">
          @if ($banners)
            @for ($i = 0; $i < count($banners); $i++)
              @if ($banners[$i]->position == 'middle')
                <div class="col-sm-6">
                  <div class="banner-sm radius-lg mb-30 ratio ratio-21-9">
                    <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                      alt="Banner">
                    <div class="banner-content mw-80">
                      <div class="content-inner">
                        <span class="sub-title">{{ $banners[$i]->title }}</span>
                        <h3 class="title">{{ $banners[$i]->subtitle }}
                        </h3>
                        @if ($banners[$i]->banner_url && $banners[$i]->button_text)
                          <a href="{{ $banners[$i]->banner_url }}"
                            class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banners[$i]->button_text }}
                            <span class="icon"><i class="fal fa-arrow-right"></i></span>
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            @endfor
          @endif
        </div>
      </div>
    </div>
    <!-- Banner Collection End -->
  @endif

  @if (count($after_middle_banner) > 0)
    @foreach ($after_middle_banner as $cusMiddleBanner)
      @if (isset($additional_section_status[$cusMiddleBanner->id]))
        @if ($additional_section_status[$cusMiddleBanner->id] == 1)
          @php
            $cusMiddleBannerContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusMiddleBanner->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusMiddleBannerContent,
              'possition' => $cusMiddleBanner->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->categoryProduct_section == 1)
    @include('user-front.electronics.cat_content')
  @endif

  @if (count($after_category_product) > 0)
    @foreach ($after_category_product as $cusCategoryProduct)
      @if (isset($additional_section_status[$cusCategoryProduct->id]))
        @if ($additional_section_status[$cusCategoryProduct->id] == 1)
          @php
            $cusCategoryProductContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusCategoryProduct->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusCategoryProductContent,
              'possition' => $cusCategoryProduct->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->tab_section == 1)
    @include('user-front.electronics.tab_content')
  @endif

  @if (count($after_tab) > 0)
    @foreach ($after_tab as $cusTab)
      @if (isset($additional_section_status[$cusTab->id]))
        @if ($additional_section_status[$cusTab->id] == 1)
          @php
            $cusTabContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusTab->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusTabContent,
              'possition' => $cusTab->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->latest_product_section == 1)
    @include('user-front.electronics.latest_content')
  @endif

  @if (count($after_latest_product) > 0)
    @foreach ($after_latest_product as $cusLatestProduct)
      @if (isset($additional_section_status[$cusLatestProduct->id]))
        @if ($additional_section_status[$cusLatestProduct->id] == 1)
          @php
            $cusLatestProductContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusLatestProduct->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusLatestProductContent,
              'possition' => $cusLatestProduct->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->cta_section_status == 1)
    <!-- Newsletter Start -->
    <section class="newsletter pb-100">
      <div class="container">
        <div class="image-inner rounded-pill">
          <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
            data-src="{{ @$userSec->action_section_background_image ? asset('assets/front/img/cta/' . @$userSec->action_section_background_image) : asset('assets/user-front/images/electri/electi_call_to_action.jpg') }}"
            alt="">
          <!-- Background Image -->
          <div class="row justify-content-center">
            <div class="col-md-10">
              <div class="row align-items-center">
                <div class="col-lg-6">
                  <div class="content mw-100 ptb-40">
                    <h3 class="title text-white mb-20">
                      {{ @$userSec->call_to_action_section_title ?? __('Section Title') }}</h3>
                    <div class="text text-lg mb-20 text-white">{{ @$userSec->call_to_action_section_text }}</div>
                    @if (!is_null(@$userSec->call_to_action_section_button_url))
                      <a href="{{ $userSec->call_to_action_section_button_url }}"
                        class="btn btn-md btn-primary rounded-pill">{{ $userSec->call_to_action_section_button_text }}</a>
                    @endif
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="image mt-n-30">
                    <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ @$userSec->action_section_side_image ? asset('assets/front/img/cta/' . @$userSec->action_section_side_image) : asset('assets/user-front/images/electri/call_side_default.png') }}"
                      alt="Image">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Newsletter End -->
  @endif

  @if (count($after_call_to_action) > 0)
    @foreach ($after_call_to_action as $cusSubscribe)
      @if (isset($additional_section_status[$cusSubscribe->id]))
        @if ($additional_section_status[$cusSubscribe->id] == 1)
          @php
            $cusSubscribeContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusSubscribe->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusSubscribeContent,
              'possition' => $cusSubscribe->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  {{-- Variation Modal Starts --}}
  @include('user-front.partials.variation-modal')
  {{-- Variation Modal Ends --}}

  <!-- Quick View Modal Start -->
  <div class="modal custom-modal quick-view-modal fade" id="quickViewModal" tabindex="-1"
    aria-labelledby="quickViewModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content radius-sm">
        <button type="button" class="close_modal_btn" data-bs-dismiss="modal" aria-label="Close"><i
            class="fal fa-times"></i></button>
        <div class="modal-body">
          <div class="product-single-default">
            <div class="row" id="quickViewModalContent">


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Quick View Modal End -->

@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    if ($('#banner-slider-mobile-electronics').length > 0) {
      $('#banner-slider-mobile-electronics').slick({
        dots: false,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 600,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        rtl: $('html').attr('dir') === 'rtl'
      });
    }

    if ($('.product-slider-electronics').length > 0) {
      $(".product-slider-electronics").each(function () {
        var $this = $(this);
        var id = $this.attr("id");
        var sliderId = "#" + id;
        var appendArrowsClassName = "#" + id + "-arrows";
        
        var desktopSlides = 3;
        try {
          var dataSlick = $this.data('slick');
          if (dataSlick && dataSlick.slidesToShow) {
            desktopSlides = dataSlick.slidesToShow;
          }
        } catch(e) {}

        $(sliderId).slick({
          speed: 600,
          arrows: true,
          dots: false,
          autoplay: false,
          slidesToShow: desktopSlides,
          infinite: false,
          rtl: $('html').attr('dir') === 'rtl',
          responsive: [
            {
              breakpoint: 1200,
              settings: {
                slidesToShow: desktopSlides >= 4 ? 4 : desktopSlides,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 992,
              settings: {
                slidesToShow: desktopSlides >= 3 ? 3 : 2,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 575,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            }
          ],
          prevArrow: '<button type="button" class="btn-icon slider-btn slider-prev"><i class="fal fa-angle-left"></i></button>',
          nextArrow: '<button type="button" class="btn-icon slider-btn slider-next"><i class="fal fa-angle-right"></i></button>',
          appendArrows: appendArrowsClassName
        });

        $('[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
          $(sliderId).slick('setPosition');
        });
      });
    }
  });
</script>
@endsection
