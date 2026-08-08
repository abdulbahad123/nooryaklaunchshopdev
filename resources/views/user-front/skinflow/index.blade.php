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
    @includeIf('user-front.skinflow.partials.hero_content')
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
          @includeIf('user-front.additional-section', [
              'data' => $cusHeroContent,
              'possition' => $cusHero->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->featuers_section == 1)
    <!-- Announcement Start -->
    <div class="announcement-area overfollow-hidden">
      <div class="container-fuild p-0">
        <div class="slider-area">
          
          <ul class="announcement-slider px-0">
              @foreach ($how_work_steps as $how_work_step)
                  <li class="slider-item">
                    <h5 class="mb-0 fw-medium">{{ $how_work_step->title }}</h5>
                    <i class="{{ $how_work_step->icon }}"></i>
                  </li>
              @endforeach
          </ul>
        </div>
      </div>
    </div>
    <!-- Announcement End -->
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

  <!-- Category Start -->
  @if ($ubs->category_section == 1)
    <section class="category category-9 pt-100 pb-100">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="section-title text-center" data-aos="fade-up" data-aos-delay="100">
              <h2 class="title h2 mb-40">
                {{ $userSec->category_section_title ?? ($keywords['Browse by Exclusive Category'] ?? __('Browse by Exclusive Category')) }}
              </h2>
            </div>
          </div>
        </div>
        <div class="col-12" data-aos="fade-up" data-aos-delay="100">
          @if (count($item_categories) == 0)
            <h5 class="title text-center mb-20">
              {{ $keywords['NO CATEGORIES FOUND'] ?? __('NO CATEGORIES FOUND') }}
            </h5>
          @else
            <div class="category-slider-wrapper">
              <div class="cat-slider-skinflow" id="cat-slider-skinflow" data-slick='{"dots": true, "autoplay": true, "autoplaySpeed": 3000, "slidesToShow": 8, "slidesToScroll": 1, "responsive": [{"breakpoint": 1200, "settings": {"slidesToShow": 6}}, {"breakpoint": 992, "settings": {"slidesToShow": 5}}, {"breakpoint": 768, "settings": {"slidesToShow": 4}}, {"breakpoint": 576, "settings": {"slidesToShow": 3}}]}'>
                @foreach ($item_categories as $category)
                  <!-- category-item -->
                  <div class="category-item">
                    <div class="category-image">
                      <img class="lazyload blur-up"
                        data-src="{{ asset('assets/front/img/user/items/categories/' . $category->image) }}"
                        alt="">
                    </div>
                    <h5 class="fw-medium title"><a
                        href="{{ route('front.user.shop', [getParam(), 'category' => $category->slug]) }}">{{ $category->name }}</a>
                    </h5>
                  </div>
                @endforeach
              </div>
              <!-- slider-arrow -->
              <div class="slider-arrow" id="cat-slider-skinflow-arrows"></div>
            </div>
          @endif
        </div>
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
          @includeIf('user-front.additional-section', [
              'data' => $cusCategoryContent,
              'possition' => $cusCategory->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->flash_section == 1)
    @includeIf('user-front.skinflow.partials.flash-content')
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


  <!--  Products Showcase Start -->
  @if ($ubs->cta_section_status == 1)
    <section class="products-showcase-section pb-100" data-aos="fade-up" data-aos-delay="100">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="section-title text-center">
              <h2 class="title h2 mb-40">{{ @$userSec->call_to_action_section_title ?? __('Section Title') }}</h2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="products-showcase">
              <h1 class="bg-title lc-2">{{ @$userSec->call_to_action_section_title ?? __('Section Title') }}</h1>
              <div class="image">
                <img class="lazyload blur-up default-img" src="assets/images/placeholder.png"
                  data-src="{{ @$userSec->action_section_background_image ? asset('assets/front/img/cta/' . @$userSec->action_section_background_image) : asset('assets/user-front/images/skinflow/showcase-1.png') }}"
                  alt="Product">
              </div>

              <div class="group-card">
                <h5 class="fw-medium mb-10">{{ @$userSec->call_to_action_section_text ?? __('Section Text') }}</h5>
                @if (!is_null(@$userSec->call_to_action_section_button_url))
                  <a href="{{ $userSec->call_to_action_section_button_url }}"
                    class="btn btn-md btn-primary rounded-pill">{{ $userSec->call_to_action_section_button_text }}</a>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!--  Products Showcase End -->

  @if ($ubs->middle_banner_section == 1)
    <!-- Banner Section Start -->
    <section class="banner-section overfollow-hidden" data-aos="fade-up" data-aos-delay="100">
      <div class="container">
        <div class="row">
          @if ($banners)
            @for ($i = 0; $i < count($banners); $i++)
              @if ($banners[$i]->position == 'middle')
                <div class="col-md-6">
                  <!-- banner-md -->
                  <div class="banner-md radius-lg mb-30 ratio ratio-21-9">
                    <img class="lazyload bg-img"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}" alt="Banner">
                    <div class="banner-content">
                      <div class="content-inner">
                        <h5 class="title fw-bold"><a href="#">{{ $banners[$i]->title }}</a></h5>
                        <p class="fw-semibold mb-0">{{ $banners[$i]->subtitle }}</p>
                      </div>
                      <!-- btn-icon-group -->
                      @if ($banners[$i]->banner_url && $banners[$i]->button_text)
                        <a href="{{ $banners[$i]->banner_url }}" class="btn btn-light text-sm fw-semibold radius-30">
                          {{ $banners[$i]->button_text }}
                        </a>
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
          <div class="mt-70">
            @includeIf('user-front.additional-section', [
                'data' => $cusMiddleBannerContent,
                'possition' => $cusMiddleBanner->possition,
            ]);
          </div>
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->featured_section == 1)
    @includeIf('user-front.skinflow.partials.featured_content')
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

  @if ($ubs->top_rated_section == 1)
    @includeIf('user-front.skinflow.partials.top_rated_content')
  @endif

  @if (count($after_top_rated) > 0)
    @foreach ($after_top_rated as $cusTopRated)
      @if (isset($additional_section_status[$cusTopRated->id]))
        @if ($additional_section_status[$cusTopRated->id] == 1)
          @php
            $cusTopRatedContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusTopRated->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusTopRatedContent,
              'possition' => $cusTopRated->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @for ($i = 0; $i < count($tabs); $i++)
    @include('user-front.skinflow.partials.product-section-content')
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
