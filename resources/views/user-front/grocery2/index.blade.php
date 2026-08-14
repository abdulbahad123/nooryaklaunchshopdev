@extends('user-front.layout')
@section('meta-description', !empty($seo) ? $seo->home_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->home_meta_keywords : '')
@section('page-title', $keywords['Home'] ?? __('Home'))
@section('og-meta')
  <meta property="og:title" content="{{ $user->username }}">
  <meta property="og:image" content="{{ !empty($userBs->logo) ? asset('assets/front/img/user/' . $userBs->logo) : '' }}">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
@endsection

@php
  $additional_section_status = json_decode($userBs->additional_section_status, true) ?? [];
@endphp

@section('content')
  <!-- ==================== HERO SLIDER AREA ==================== -->
  <section class="g2-hero-section pt-3">
    <div class="container">
      <div class="row">
        <!-- Main Hero Slider -->
        <div class="col-xl-8 col-lg-12 mb-4">
          <div class="g2-hero-slider" id="g2-main-slider">
            @if (count($sliders) > 0)
              @foreach ($sliders as $slider)
                <div class="g2-slider-item" style="background-image: url('{{ asset('assets/front/img/hero_slider/' . $slider->img) }}');">
                  <div class="g2-slider-content">
                    <span class="g2-badge">TRENDING NOW</span>
                    <h1 class="g2-title">{{ $slider->subtitle }}</h1>
                    <p class="g2-text">{{ $slider->text }}</p>
                    <div class="g2-slider-btns">
                      @if ($slider->btn_url && $slider->btn_name)
                        <a href="{{ $slider->btn_url }}" class="btn g2-btn-primary">{{ $slider->btn_name }}</a>
                      @else
                        <a href="{{ route('front.user.shop', getParam()) }}" class="btn g2-btn-primary">Buy Now</a>
                      @endif
                      <a href="{{ route('front.user.shop', getParam()) }}" class="btn g2-btn-secondary">Learn More</a>
                    </div>
                  </div>
                </div>
              @endforeach
            @else
              <div class="g2-slider-item" style="background-image: url('{{ asset('assets/front/img/hero_slider/ecom_grocery_banner_clean.png') }}');">
                <div class="g2-slider-content">
                  <h1 class="g2-title">{{ $user->shop_name ?? 'Welcome to Our Store' }}</h1>
                  <p class="g2-text">{{ __('Shop the latest deals and products') }}</p>
                  <div class="g2-slider-btns">
                    <a href="{{ route('front.user.shop', getParam()) }}" class="btn g2-btn-primary">{{ __('Shop Now') }}</a>
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Right Side Promo Stack -->
        @if(isset($banners) && count($banners) > 0)
          <div class="col-xl-4 col-lg-12 mb-4 d-none d-xl-block">
            <div class="g2-promo-stack">
              @foreach($banners->take(2) as $b)
                <div class="g2-side-promo" style="background-image: url('{{ asset('assets/front/img/user/banners/' . $b->banner_img) }}');">
                  <div class="g2-side-promo-content">
                    @if(!empty($b->title)) <h3>{{ $b->title }}</h3> @endif
                    <a href="{{ $b->banner_url ?? $b->url ?? route('front.user.shop', getParam()) }}" class="g2-link-btn">{{ __('Shop Now') }} <i class="far fa-long-arrow-right"></i></a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
 
  <!-- ==================== FEATURED CATEGORIES ==================== -->
  @if ($ubs->category_section == 1)
    <section class="g2-categories-section py-4">
      <div class="container">
        <div class="g2-section-header">
          <h2 class="g2-section-title">Featured Categories</h2>
          <div class="g2-arrow-nav">
            <button class="g2-arrow-btn cat-prev"><i class="fal fa-chevron-left"></i></button>
            <button class="g2-arrow-btn cat-next"><i class="fal fa-chevron-right"></i></button>
          </div>
        </div>
        
        <div class="g2-categories-slider" id="g2-categories-carousel">
          @if (count($item_categories) > 0)
            @foreach ($item_categories as $category)
              <div class="g2-category-card-wrapper">
                <a href="{{ route('front.user.shop', [getParam(), 'category' => $category->slug]) }}" class="g2-category-card">
                  <div class="g2-category-img-circle">
                    @if($category->image)
                      <img src="{{ asset('assets/front/img/user/items/categories/' . $category->image) }}" alt="{{ $category->name }}">
                    @else
                      <img src="{{ asset('assets/front/images/placeholder.png') }}" alt="{{ $category->name }}">
                    @endif
                  </div>
                  <h3>{{ $category->name }}</h3>
                  @php
                    $item_count = ProductCountByCategory($uLang, $category->id);
                  @endphp
                  <span class="count">{{ $item_count }} {{ $item_count == 1 ? 'Item' : 'Items' }}</span>
                </a>
              </div>
            @endforeach
          @else
            <div class="col-12 text-center py-4">
              <p class="text-muted">{{ __('No categories found') }}</p>
            </div>
          @endif
        </div>
      </div>
    </section>
  @endif

  <!-- ==================== POPULAR PRODUCTS ==================== -->
  <section class="g2-popular-products py-4">
    <div class="container">
      <div class="g2-section-header align-items-center">
        <h2 class="g2-section-title mb-0">Popular Products</h2>
        <!-- Filters list -->
        <div class="g2-product-filters">
          <ul class="nav nav-tabs border-0" id="g2ProductTabs" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#tab-all" type="button" role="tab">All</button>
            </li>
            @php $count = 0; @endphp
            @foreach($categories->take(5) as $cat)
              <li class="nav-item">
                <button class="nav-link" id="cat-{{ $cat->id }}-tab" data-bs-toggle="tab" data-bs-target="#tab-cat-{{ $cat->id }}" type="button" role="tab">{{ $cat->name }}</button>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <!-- Tab Contents -->
      <div class="tab-content mt-4" id="g2ProductTabsContent">
        <!-- ALL TAB -->
        <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
            @php
              $all_items = (isset($latest_items) && count($latest_items) > 0) ? $latest_items : [];
            @endphp
            @if(count($all_items) > 0)
              @foreach($all_items->take(12) as $item)
                @include('user-front.grocery2.partials.product-card', ['item' => $item])
              @endforeach
            @else
              <div class="col-12 text-center py-4">
                <p class="text-muted">{{ __('No products found') }}</p>
              </div>
            @endif
          </div>
        </div>

        <!-- CATEGORIES TABS -->
        @foreach($categories->take(5) as $cat)
          <div class="tab-pane fade" id="tab-cat-{{ $cat->id }}" role="tabpanel">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
              @php
                $cat_items = App\Models\User\UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                  ->where('user_items.user_id', $user->id)
                  ->where('user_item_contents.language_id', $uLang)
                  ->where('user_item_contents.category_id', $cat->id)
                  ->select('user_items.*', 'user_item_contents.title', 'user_item_contents.slug', 'user_item_contents.summary', 'user_item_contents.description')
                  ->orderBy('user_items.id', 'desc')
                  ->get();
              @endphp
              @if(count($cat_items) > 0)
                @foreach($cat_items->take(12) as $item)
                  @include('user-front.grocery2.partials.product-card', ['item' => $item])
                @endforeach
              @else
                <div class="col-12 text-center py-4">
                  <p class="text-muted">{{ __('No products found in this category') }}</p>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ==================== MIDDLE PROMO CARDS (4 Column Grid) ==================== -->
  @if(isset($banners) && count($banners) >= 6)
    <section class="g2-middle-banners py-4">
      <div class="container">
        <div class="row g-3">
          @php
            $midBanners = $banners->slice(2, 4);
            $bgClasses = ['bg-cream-light', 'bg-blue-light', 'bg-green-light', 'bg-pink-light'];
            $i = 0;
          @endphp
          @foreach($midBanners as $b)
            <div class="col-xl-3 col-lg-6 col-md-6">
              <div class="g2-mid-card {{ $bgClasses[$i % 4] }}">
                <div class="g2-mid-card-img">
                  <img src="{{ asset('assets/front/img/user/banners/' . $b->banner_img) }}" alt="Banner">
                </div>
                <div class="g2-mid-card-text">
                  <h3>{{ $b->title }}</h3>
                  <a href="{{ $b->banner_url ?? $b->url ?? route('front.user.shop', getParam()) }}" class="g2-supplier-link">Go To Supplier <i class="fas fa-caret-right"></i></a>
                </div>
              </div>
            </div>
            @php $i++; @endphp
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <!-- ==================== SIDEBAR BANNER + POPULAR ITEMS GRID SECTION ==================== -->
  <section class="g2-sidebar-grid py-4">
    <div class="container">
      <div class="row">
        <!-- Left vertical banner (Loaded dynamically from $banners) -->
        @if(isset($banners) && count($banners) >= 7)
          @php $vertBanner = $banners->get(6) ?? $banners->last(); @endphp
          <div class="col-xl-3 col-lg-4 mb-4">
            <div class="g2-vertical-juice-card">
              <div class="g2-vert-content">
                <h3>{{ $vertBanner->title }}</h3>
                <a href="{{ $vertBanner->banner_url ?? $vertBanner->url ?? route('front.user.shop', getParam()) }}" class="btn g2-btn-orange">Shop Now <i class="fas fa-caret-right"></i></a>
              </div>
              <div class="g2-vert-img">
                <img src="{{ asset('assets/front/img/user/banners/' . $vertBanner->banner_img) }}" alt="Vertical Promo">
              </div>
            </div>
          </div>
        @else
          <div class="col-xl-3 col-lg-4 mb-4">
            <div class="g2-vertical-juice-card">
              <div class="g2-vert-content">
                <h3>Everyday Fresh Clean with Our Products</h3>
                <a href="{{ route('front.user.shop', getParam()) }}" class="btn g2-btn-orange">Shop Now <i class="fas fa-caret-right"></i></a>
              </div>
              <div class="g2-vert-img">
                <img src="{{ asset('assets/front/img/user/banners/redesign_ecom_vertical_juice_promo.png') }}" alt="Juice Promo">
              </div>
            </div>
          </div>
        @endif

        <!-- Right Products Grid -->
        <div class="col-xl-9 col-lg-8">
          <div class="g2-section-header align-items-center mb-3">
            <div class="d-flex align-items-center gap-3">
              <div class="g2-arrow-nav me-2">
                <button class="g2-arrow-btn grid-prev"><i class="fal fa-chevron-left"></i></button>
                <button class="g2-arrow-btn grid-next"><i class="fal fa-chevron-right"></i></button>
              </div>
              <h2 class="g2-section-title mb-0">Popular Items</h2>
            </div>

            <!-- Filters list -->
            <div class="g2-product-filters d-none d-md-block">
              <ul class="nav nav-tabs border-0" id="g2PopularItemTabs" role="tablist">
                <li class="nav-item">
                  <button class="nav-link active" id="pop-all-tab" data-bs-toggle="tab" data-bs-target="#pop-tab-all" type="button" role="tab">All</button>
                </li>
                @foreach($categories->take(5) as $cat)
                  <li class="nav-item">
                    <button class="nav-link" id="pop-cat-{{ $cat->id }}-tab" data-bs-toggle="tab" data-bs-target="#pop-tab-cat-{{ $cat->id }}" type="button" role="tab">{{ $cat->name }}</button>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>

          <div class="tab-content" id="g2PopularItemTabsContent">
            <!-- ALL TAB -->
            <div class="tab-pane fade show active" id="pop-tab-all" role="tabpanel">
              <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-3">
                @if(count($all_items) > 0)
                  @foreach($all_items->skip(4)->take(4) as $item)
                    @include('user-front.grocery2.partials.product-card', ['item' => $item])
                  @endforeach
                @else
                  <div class="col-12 text-center py-4">
                    <p class="text-muted">{{ __('No products found') }}</p>
                  </div>
                @endif
              </div>
            </div>

            <!-- CATEGORIES TABS -->
            @foreach($categories->take(5) as $cat)
              <div class="tab-pane fade" id="pop-tab-cat-{{ $cat->id }}" role="tabpanel">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-3">
                  @php
                    $cat_items2 = App\Models\User\UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                      ->where('user_items.user_id', $user->id)
                      ->where('user_item_contents.language_id', $uLang)
                      ->where('user_item_contents.category_id', $cat->id)
                      ->select('user_items.*')
                      ->get();
                  @endphp
                  @if(count($cat_items2) > 0)
                    @foreach($cat_items2->take(4) as $item)
                      @include('user-front.grocery2.partials.product-card', ['item' => $item])
                    @endforeach
                  @else
                    <div class="col-12 text-center py-4">
                      <p class="text-muted">{{ __('No products found in this category') }}</p>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>



  {{-- Variation Modal & Quick View Modal --}}
  @include('user-front.partials.variation-modal')
  <div class="modal custom-modal quick-view-modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content radius-sm">
        <button type="button" class="close_modal_btn" data-bs-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
        <div class="modal-body">
          <div class="product-single-default">
            <div class="row gx-0" id="quickViewModalContent"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ==================== NEWSLETTER BANNER SECTION ==================== -->
  <section class="g2-newsletter-section py-4">
    <div class="container">
      <div class="g2-newsletter-card">
        <div class="row align-items-center g-0">
          <div class="col-lg-6 col-md-12 p-4 p-md-5">
            <h2 class="g2-news-title text-white fw-bold mb-2">Stay home & get your daily needs from our shop</h2>
            <p class="g2-news-subtitle text-white-50 mb-4 fs-6">Start Your Daily Shopping with Ecom Mart</p>
            <form action="{{ route('front.user.subscribe', getParam()) }}" method="POST" class="g2-news-form d-flex align-items-center bg-white p-2 rounded-pill shadow-sm" style="max-width: 440px;">
              @csrf
              <input type="email" name="email" class="form-control border-0 bg-transparent px-3 text-dark shadow-none" placeholder="Your email address" required style="outline: none;">
              <button type="submit" class="btn btn-primary border-0 rounded-pill px-4 py-2 fw-semibold text-white" style="background-color: #2c3e50; font-size: 14px; white-space: nowrap;">Sign up</button>
            </form>
          </div>
          <div class="col-lg-6 col-md-12 d-none d-lg-block text-end pe-4">
            <img src="{{ asset('assets/front/img/user/banners/ecom_grocery_banner.png') }}" alt="Grocery Cart" class="img-fluid g2-news-img" style="max-height: 280px; object-fit: contain;">
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Preloader auto-hide fallback
    $('.preloader').addClass('hidden').fadeOut(300);
    $(window).on('load', function() {
      $('.preloader').addClass('hidden').fadeOut(300);
    });
    setTimeout(function() {
      $('.preloader').addClass('hidden').fadeOut(300);
    }, 1200);

    // Main Hero Slider Carousel
    if ($('#g2-main-slider').length > 0) {
      $('#g2-main-slider').slick({
        dots: true,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev g2-slider-arrow"><i class="fal fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next g2-slider-arrow"><i class="fal fa-chevron-right"></i></button>',
        autoplay: true,
        autoplaySpeed: 3500,
        speed: 700,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        pauseOnHover: false,
        fade: true,
        cssEase: 'ease-in-out',
        rtl: $('html').attr('dir') === 'rtl'
      });
    }

    // Categories Carousel
    if ($('#g2-categories-carousel').length > 0) {
      var catSlider = $('#g2-categories-carousel').slick({
        dots: false,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 7,
        slidesToScroll: 1,
        infinite: true,
        responsive: [
          { breakpoint: 1200, settings: { slidesToShow: 5 } },
          { breakpoint: 992, settings: { slidesToShow: 4 } },
          { breakpoint: 768, settings: { slidesToShow: 3 } },
          { breakpoint: 576, settings: { slidesToShow: 2 } }
        ],
        rtl: $('html').attr('dir') === 'rtl'
      });

      $('.cat-prev').on('click', function() {
        catSlider.slick('slickPrev');
      });
      $('.cat-next').on('click', function() {
        catSlider.slick('slickNext');
      });
    }
  });
</script>
@endsection
