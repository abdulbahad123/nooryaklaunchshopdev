@extends('user-front.layout')

@section('meta-description', !empty($seo) ? $seo->home_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->home_meta_keywords : '')
@section('page-title', $keywords['Home'] ?? __('Home'))
@section('og-meta')
  <!--- For Social Media Share Thumbnail --->
  <meta property="og:title" content="{{ $user->username }}">
  <meta property="og:image" content="{{ !empty($userBs->logo) ? asset('assets/front/img/user/' . $userBs->logo) : '' }}">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
  <!--- For Social Media Share Thumbnail --->
@endsection

@section('content')
  @php
    $additional_section_status = json_decode($userBs->additional_section_status, true);
  @endphp
  <!-- Home Hero Start -->
  @if ($ubs->hero_section == 1)
    @includeIf('user-front.pet.partials.hero_content')
  @endif
  <!-- Home Hero End -->

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
          <div class="mt-70">
            @includeIf('user-front.additional-section', [
                'data' => $cusHeroContent,
                'possition' => $cusHero->possition,
            ]);
          </div>
        @endif
      @endif
    @endforeach
  @endif

  <!-- Category Start -->
  @if ($ubs->category_section == 1)
    <section class="category category-7 pt-100 {{ $ubs->hero_section == 0 ? 'mt-70' : '' }}">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="section-title text-center" data-aos="fade-down" data-aos-delay="100">
              <h2 class="title fw-bold mb-20">
                {{ $userSec->category_section_title ?? ($keywords['Browse by Exclusive Category'] ?? __('Browse by Exclusive Category')) }}
              </h2>
            </div>
          </div>
          <div class="col-12" data-aos="fade-up" data-aos-delay="100">
            @if (count($item_categories) == 0)
              <h5 class="title text-center mb-20">
                {{ $keywords['NO CATEGORIES FOUND'] ?? __('NO CATEGORIES FOUND') }}
              </h5>
            @else
              <div class="category-slider-wrapper">
                <div class="category-slider" id="cat-slider-pet" data-slick='{"dots": true, "autoplay": true, "autoplaySpeed": 3000, "slidesToShow": 6, "slidesToScroll": 1, "responsive": [{"breakpoint": 1200, "settings": {"slidesToShow": 5}}, {"breakpoint": 992, "settings": {"slidesToShow": 4}}, {"breakpoint": 768, "settings": {"slidesToShow": 3}}, {"breakpoint": 576, "settings": {"slidesToShow": 2}}]}'>
                  @foreach ($item_categories as $category)
                    <div class="category-item">
                      <a href="{{ route('front.user.shop', [getParam(), 'category' => $category->slug]) }}">
                        <img class="category-bg-image"
                          src="{{ asset('assets/front/img/user/items/category_background/' . $category->category_background_image) }}"
                          alt="category">
                        <div class="category-img">
                          <img class="lazyload blur-up"
                            src="{{ asset('assets/front/img/user/items/categories/' . $category->image) }}"
                            alt="category">
                        </div>
                        <h5 class="mb-2 title">{{ $category->name }}</h5>
                        @php
                          $category_items_count = ProductCountByCategory($uLang, $category->id);
                        @endphp
                        <span>
                          {{ $category_items_count }}
                          {{ $category_items_count > 1 ? $keywords['Products Available'] ?? __('Products Available') : $keywords['Product Available'] ?? __('Product Available') }}
                        </span>
                      </a>
                    </div>
                  @endforeach
                </div>
                <!-- slider-arrow -->
                <div class="slider-arrow" id="cat-slider-pet-arrows"></div>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="shape">
        <img class="shape-1 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/category/shape-1.png') }}"
          alt="shape">
        <img class="shape-2 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/category/shape-2.png') }}"
          alt="shape">
        <img class="shape-3 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/category/shape-3.png') }}"
          alt="shape">
        <img class="shape-4 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/category/shape-4.png') }}"
          alt="shape">
        <img class="shape-5 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/category/shape-5.png') }}"
          alt="shape">
      </div>
    </section>
  @endif
  <!-- Category End -->

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
          <div class="mt-70">
            @includeIf('user-front.additional-section', [
                'data' => $cusCategoryContent,
                'possition' => $cusCategory->possition,
            ]);
          </div>
        @endif
      @endif
    @endforeach
  @endif

  <!-- Flash Sale Start -->
  @if ($ubs->flash_section == 1)
    @includeIf('user-front.pet.partials.flash_content')
  @endif
  <!-- Flash Sale End -->

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

  <!-- Banner Collection Start -->
  @if ($ubs->middle_banner_section == 1)
    <div class="banner-collection">
      <div class="container">
        <div class="row">
          @if ($banners)
            @for ($i = 0; $i < count($banners); $i++)
              @if ($banners[$i]->position == 'middle')
                <div class="col-md-6" data-aos="fade-left" data-aos-delay="100">
                  <!-- banner-md -->
                  <div class="banner-md radius-lg mb-30 ratio ratio-21-9">
                    <img class="lazyload bg-img blur-up"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}" alt="Banner">
                    <div class="banner-content">
                      <div class="content-inner">
                        <p class="mb-1 small  text-dark">{{ $banners[$i]->title }}</p>
                        <h3 class="title fw-bold mb-20">
                          {{ $banners[$i]->subtitle }}
                        </h3>
                        @if ($banners[$i]->banner_url && $banners[$i]->button_text)
                          <a href="{{ $banners[$i]->banner_url }}" class="btn btn-light text-sm fw-semibold radius-30">
                            {{ $banners[$i]->button_text }}
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
  @endif
  <!-- Banner Collection End -->

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

  <!-- products Tab Section Start -->
  @if ($ubs->featured_section == 1)
    @includeIf('user-front.pet.partials.featured_content')
  @endif
  <!-- products Tab Section End -->

  @if (count($after_featured) > 0)
    @foreach ($after_featured as $cusFeatured)
      @if (isset($additional_section_status[$cusFeatured->id]))
        @if ($additional_section_status[$cusFeatured->id] == 1)
          @php
            $cusFeaturedContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusFeatured->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusFeaturedContent,
              'possition' => $cusFeatured->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->featuers_section == 1)
    <!-- Featured Start -->
    <section class="featured-v7-main clearfix overflow-hidden pb-100">
      <div class=" container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="section-title text-center">
              <h2 class="title fw-bold mb-20">
                {{ @$userSec->features_section_title ?? __('Explore Our Pet Shop Features') }}</h2>
            </div>
          </div>
        </div>
      </div>
      <div class="featured-v7">
        <!-- Background Image -->
        <img class="lazyload bg-img blur-up"
          data-src="{{ asset('assets/front/img/user/feature/' . @$userSec->featured_background_img) }}" alt="Banner">
        <div class="container">
          @if (count($how_work_steps) > 0)
            <div class="row gx-xl-0 align-items-center">
              @php
                $count = ceil(count($how_work_steps) / 2);
              @endphp

              <div class="col-xl-3 col-sm-6 order-xl-1 order-1" data-aos="fade-right" data-aos-delay="100">
                <div class="featured-item-wrapper">
                  @for ($i = 0; $i < $count; $i++)
                    <div class="featured-item">
                      <div class="icon color-1">
                        <i class="{{ str_replace(['fas ', 'far '], 'fal ', $how_work_steps[$i]['icon']) }}"></i>
                      </div>
                      <div class="content">
                        <h5 class="mb-0">{{ $how_work_steps[$i]['title'] }}</h5>
                        <p class="text-sm mb-0">{{ $how_work_steps[$i]['text'] }}</p>
                      </div>
                    </div>
                  @endfor
                </div>
              </div>

              <div class="col-xl-6 col-sm-12 order-xl-2 order-3" data-aos="zoom-in" data-aos-delay="100">
                <div class="image">
                  @if (@$userSec->featured_img != null)
                    <img src="{{ asset('assets/front/img/user/feature/' . $userSec->featured_img) }}" alt="Icon">
                  @endif
                </div>
              </div>

              <div class="col-xl-3 col-sm-6 order-xl-3 order-2" data-aos="fade-left" data-aos-delay="100">
                <div class="featured-item-wrapper">
                  @for ($i = $count; $i < count($how_work_steps); $i++)
                    <div class="featured-item">
                      <div class="icon color-1">
                        <i class="{{ str_replace(['fas ', 'far '], 'fal ', $how_work_steps[$i]['icon']) }}"></i>
                      </div>
                      <div class="content">
                        <h5 class="mb-0">{{ $how_work_steps[$i]['title'] }}</h5>
                        <p class="text-sm mb-0">{{ $how_work_steps[$i]['text'] }}</p>
                      </div>
                    </div>
                  @endfor
                </div>
              </div>
            </div>
          @else
            <h5 class="title mb-20 text-center">
              {{ $keywords['NO DATA FOUND'] ?? __('NO DATA FOUND') }}
            </h5>
          @endif
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

  {{-- tab section --}}
  @for ($i = 0; $i < count($tabs); $i++)
    @include('user-front.pet.partials.product-section-content')
  @endfor

  @if (count($after_product) > 0)
    @foreach ($after_product as $cusProduct)
      @if (isset($additional_section_status[$cusProduct->id]))
        @if ($additional_section_status[$cusProduct->id] == 1)
          @php
            $cusProductContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusProduct->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusProductContent,
              'possition' => $cusProduct->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  <!-- Section Start -->
  @if ($ubs->top_rated_section == 1)
    <section class="section-product-slider-md pb-100 overfollow-hidden">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            @if ($ubs->left_banner_section == 1)
              <div class="d-flex flex-column">
                @if ($banners)
                  @php
                    $leftBanners = $banners->where('position', 'left')->values();
                  @endphp
                  @for ($i = 0; $i < count($leftBanners); $i++)
                    @php
                      $banner = $leftBanners[$i] ?? null;
                    @endphp
                    @if ($banner)
                      <!-- banner-md -->
                      <div class="banner-md radius-lg mb-30 ratio ratio-21-9" data-aos="flip-up" data-aos-delay="100">
                        <img class="lazyload bg-img blur-up"
                          data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}"
                          alt="Banner">
                        <div class="banner-content">
                          <div class="content-inner">
                            <p class="mb-1 small text-white">{{ $banner->title }}</p>
                            <h3 class="title fw-bold mb-20 text-white">
                              {{ $banner->subtitle }}
                            </h3>
                            @if ($banner->banner_url && $banner->button_text)
                              <a href="{{ $banner->banner_url }}" class="btn btn-light text-sm fw-semibold radius-30">
                                {{ $banner->button_text }}
                              </a>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endif
                  @endfor
                @endif
              </div>
            @endif
          </div>
          @includeIf('user-front.pet.partials.top_rated_content')

        </div>
      </div>

      <div class="shape">
        <img class="shape-1 lazyload blur-up"
          src="{{ asset('assets/user-front/images/pet/products/shape/shape-9.png') }}" alt="shape">
        <img class="shape-2 lazyload blur-up"
          src="{{ asset('assets/user-front/images/pet/products/shape/shape-6.png') }}" alt="shape">
        <img class="shape-3 lazyload blur-up"
          src="{{ asset('assets/user-front/images/pet/products/shape/shape-7.png') }}" alt="shape">
      </div>
    </section>
  @endif
  <!-- Section End -->

  @if (count($after_top_rated) > 0)
    @foreach ($after_top_rated as $custop_rated)
      @if (isset($additional_section_status[$custop_rated->id]))
        @if ($additional_section_status[$custop_rated->id] == 1)
          @php
            $custop_ratedContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $custop_rated->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $custop_ratedContent,
              'possition' => $custop_rated->possition,
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
            <div class="row gx-0" id="quickViewModalContent">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Quick View Modal End -->
@endsection
