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
    @includeIf('user-front.jewellery.partials.hero_content')
    <!-- Home Hero End -->
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

  @if ($ubs->featuers_section == 1)
    <!-- Featured Start -->
    <section class="featured-8 pb-100" data-aos="fade-up" data-aos-delay="100">
      <div class="container">
        @if (count($how_work_steps) > 0)
          <div class="featured-item-wrap row align-items-center gx-xl-5">
            @foreach ($how_work_steps as $how_work_step)
              <div class="col-lg-3 col-sm-6 mb-30 border-end ">
                <div class="featured-item">
                  <div class="icon">
                    <i class="{{ $how_work_step->icon }}"></i>
                  </div>
                  <div class="content">
                    <p class="mb-0 fw-normal">{{ $how_work_step->title }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <h5 class="title  mt-5 mb-20 text-center">
            {{ $keywords['NO DATA FOUND'] ?? __('NO DATA FOUND') }}
          </h5>
        @endif
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
          <div class="mt-30">
            @includeIf('user-front.additional-section', [
                'data' => $cusHowWorkContent,
                'possition' => $cusHowWork->possition,
            ]);
          </div>
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->category_section == 1)
    <!-- Category Area Start -->
    <section class="category category-8 pb-100 overfollow-hidden" data-aos="fade-up" data-aos-delay="100">
      <div class="container-fluid">
        <!-- fluid-left -->
        <div class="fluid-left">
          <div class="row align-items-center justify-content-between flex-nowrap">
            <div class="col-2 col-md-1 ps-0">
              <div class="section-title vertical-title">
                <h2 class="title">
                  {!! $userSec->category_section_title ?? ($keywords['Show Our Popular'] ?? __('Show Our Popular')) !!} <br>
                  {{ is_null(@$userSec->category_section_title) ? $keywords['Categories'] ?? __('Categories') : '' }}
                  <span class="left_right_slide_anim line"></span>
                </h2>
              </div>
            </div>

            @if (count($item_categories) == 0)
              <h5 class="title text-center mb-20">
                {{ $keywords['NO CATEGORIES FOUND'] ?? __('NO CATEGORIES FOUND') }}
              </h5>
            @else
              <div class="col-auto">
                <div class="category-slider" id="cat-slider-gold"
                  data-slick='{"dots": true, "loop": true, "autoplay": true, "autoplaySpeed": 3000, "slidesToShow": {{ count($item_categories) >= 6 ? 6 : count($item_categories) }}, "slidesToScroll": 1, "responsive": [{"breakpoint": 992, "settings": {"slidesToShow": 4}}, {"breakpoint": 768, "settings": {"slidesToShow": 3}}, {"breakpoint": 576, "settings": {"slidesToShow": 2}}]}'>
                  @foreach ($item_categories as $category)
                    <!-- category-item -->
                    <div class="category-item">
                      <a href="{{ route('front.user.shop', [getParam(), 'category' => $category->slug]) }}"
                        class="lazy-container ratio ratio-1-4">
                        <img class="lazyload"
                          data-src="{{ asset('assets/front/img/user/items/categories/' . $category->image) }}"
                          alt="Category">
                        <img class="lazyload"
                          data-src="{{ asset('assets/front/img/user/items/categories/' . $category->image) }}"
                          alt="Category">
                      </a>
                      <div class="content">
                        <h5 class="title fw-medium">{{ $category->name }}</h5>
                        @php
                          $category_items_count = ProductCountByCategory($uLang, $category->id);
                        @endphp
                        <span class="count small fw-medium">{{ $category_items_count }}
                          {{ $category_items_count > 1 ? $keywords['Items'] ?? __('Items') : $keywords['Item'] ?? __('Item') }}
                          {{ $keywords['available'] ?? __('available') }}</span>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>
    <!-- Category Area End -->
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
    @include('user-front.jewellery.partials.flash_content')
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
    <!-- Banner Section Start -->
    <section class="banner-section overfollow-hidden">
      <div class="container">
        <div class="row">
          @if ($banners)
            @for ($i = 0; $i < count($banners); $i++)
              @if ($banners[$i]->position == 'middle')
                <div class="col-md-6" data-aos="fade-left" data-aos-delay="100">
                  <!-- banner-md -->
                  <div class="banner-md mb-30 ratio ratio-21-9">
                    <img class="lazyload bg-img blur-up"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}" alt="Banner">
                    <div class="banner-content">
                      <div class="content-inner">
                        <h4 class="title fw-bold">{{ $banners[$i]->title }}</h4>
                        <span class="count fw-medium">{{ $banners[$i]->subtitle }}</span>
                      </div>
                      @if ($banners[$i]->banner_url && $banners[$i]->button_text)
                        <a href="{{ $banners[$i]->banner_url }}"
                          class="btn btn-md btn-primary fw-medium ">{{ $banners[$i]->button_text }}</a>
                      @endif
                    </div>
                  </div>
                </div>
              @endif
            @endfor
          @endif
        </div>
      </div>
    </section>
    <!-- Banner Section End -->
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

  @if ($ubs->top_selling_section == 1)
    @includeIf('user-front.jewellery.partials.best_selling_content')
  @endif

  @if (count($after_top_selling) > 0)
    @foreach ($after_top_selling as $cusTopSelling)
      @if (isset($additional_section_status[$cusTopSelling->id]))
        @if ($additional_section_status[$cusTopSelling->id] == 1)
          @php
            $cusTopSellingContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusTopSelling->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusTopSellingContent,
              'possition' => $cusTopSelling->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->featured_section == 1)
    @includeIf('user-front.jewellery.partials.featured_content')
  @endif

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

  @for ($i = 0; $i < count($tabs); $i++)
    @include('user-front.jewellery.partials.product-section-content')
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
