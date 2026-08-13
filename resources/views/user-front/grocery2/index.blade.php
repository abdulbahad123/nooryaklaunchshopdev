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
              @foreach ($sliders->where('is_static', 0) as $slider)
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
              <!-- Fallback Slide matching reference image -->
              <div class="g2-slider-item" style="background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1000&q=80');">
                <div class="g2-slider-content">
                  <span class="g2-badge">TRENDING NOW</span>
                  <h1 class="g2-title">Delicious Fruits from South Africa in our Grocery deals</h1>
                  <p class="g2-text">Signup for discount coupon</p>
                  <div class="g2-slider-btns">
                    <a href="{{ route('front.user.shop', getParam()) }}" class="btn g2-btn-primary">Buy Now</a>
                    <a href="{{ route('front.user.shop', getParam()) }}" class="btn g2-btn-secondary">Learn More</a>
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Right Side Promo Stack -->
        <div class="col-xl-4 col-lg-12 mb-4 d-none d-xl-block">
          <div class="g2-promo-stack">
            <!-- Promo 1 -->
            <div class="g2-side-promo promo-onion" style="background-image: url('https://images.unsplash.com/photo-1508747703725-7197771375e0?auto=format&fit=crop&w=500&q=80');">
              <div class="g2-side-promo-content">
                <h3>Everyday Fresh &<br>Clean with Our Products</h3>
                <a href="{{ route('front.user.shop', getParam()) }}" class="g2-link-btn">Shop Now <i class="far fa-long-arrow-right"></i></a>
              </div>
            </div>
            <!-- Promo 2 -->
            <div class="g2-side-promo promo-juice" style="background-image: url('https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&w=500&q=80');">
              <div class="g2-side-promo-content">
                <h3>Everyday Fresh &<br>Clean with Our Products</h3>
                <a href="{{ route('front.user.shop', getParam()) }}" class="g2-link-btn primary-bg-btn">Shop Now <i class="far fa-long-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
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
                      <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=150&q=80" alt="{{ $category->name }}">
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
            <!-- Static Category Fallbacks matching reference image -->
            @php
              $fallback_cats = [
                ['name' => 'Milks and Dairies', 'count' => 30, 'img' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=150&q=80'],
                ['name' => 'Grocery items', 'count' => 24, 'img' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=150&q=80'],
                ['name' => 'Fresh Seafood', 'count' => 3, 'img' => 'https://images.unsplash.com/photo-1534482421-64566f976cfa?auto=format&fit=crop&w=150&q=80'],
                ['name' => 'Fresh Fruit', 'count' => 4, 'img' => 'https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?auto=format&fit=crop&w=150&q=80'],
                ['name' => 'Deals Of The Day', 'count' => 7, 'img' => 'https://images.unsplash.com/photo-1506806732259-39c2d0268443?auto=format&fit=crop&w=150&q=80'],
                ['name' => 'Clothing & beauty', 'count' => 4, 'img' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=150&q=80'],
                ['name' => 'Bread and Juice', 'count' => 4, 'img' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=150&q=80']
              ];
            @endphp
            @foreach($fallback_cats as $fc)
              <div class="g2-category-card-wrapper">
                <a href="{{ route('front.user.shop', getParam()) }}" class="g2-category-card">
                  <div class="g2-category-img-circle">
                    <img src="{{ $fc['img'] }}" alt="{{ $fc['name'] }}">
                  </div>
                  <h3>{{ $fc['name'] }}</h3>
                  <span class="count">{{ $fc['count'] }} Items</span>
                </a>
              </div>
            @endforeach
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
              <!-- Static mock items to ensure a pixel-accurate match of the reference design -->
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => 'Sale', 'badge_class' => 'badge-sale', 'category' => 'Vegetables', 'title' => 'Cauliflower is a variety of organic', 'price' => '$11.00 - $14.00', 'img' => 'https://images.unsplash.com/photo-1568584711075-3d021a7c3ecf?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => 'Hot', 'badge_class' => 'badge-hot', 'category' => 'Vegetables', 'title' => 'Onions are a versatile ingredient base', 'price' => '$14.00 - $155.00', 'img' => 'https://images.unsplash.com/photo-1508747703725-7197771375e0?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => 'New', 'badge_class' => 'badge-new', 'category' => 'Vegetables', 'title' => 'Tomato is both a fruit and a vegetable', 'price' => '$11.50 - $120.00', 'img' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '10%', 'badge_class' => 'badge-discount', 'category' => 'Baking material', 'title' => 'Organic Cage Grade A Large Eggs', 'price' => '$21.00 $30.00', 'img' => 'https://images.unsplash.com/photo-1506976785307-8732e854ad03?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '9%', 'badge_class' => 'badge-discount', 'category' => 'Dairy & Cereal', 'title' => 'Naturally Flavored Cinnamon Vanilla', 'price' => '$51.00 $55.00', 'img' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '7%', 'badge_class' => 'badge-discount', 'category' => 'Fruits', 'title' => 'Seeds of Change Organic Watermelon', 'price' => '$81.00 $88.00', 'img' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '7%', 'badge_class' => 'badge-discount', 'category' => 'Baking material', 'title' => 'Bread fruit, apricots, figs, prunes', 'price' => '$9.00 $10.00', 'img' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '8%', 'badge_class' => 'badge-discount', 'category' => 'Dairy & Cereal', 'title' => 'Pre-portioned low fat ice cream yogurt', 'price' => '$72.00 $99.00', 'img' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '22%', 'badge_class' => 'badge-discount', 'category' => 'Fresh Fruit', 'title' => 'Fresh fruit strawberry, banana', 'price' => '$75.00 $83.00', 'img' => 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '5%', 'badge_class' => 'badge-discount', 'category' => 'Baking material', 'title' => 'Canada Dry Ginger Ale - 12 Floz', 'price' => '$32.00 $35.00', 'img' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '8%', 'badge_class' => 'badge-discount', 'category' => 'Clothing & beauty', 'title' => 'Enzymes Seaweeds Hydrated Mask' , 'price' => '$85.00 $97.00', 'img' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '4%', 'badge_class' => 'badge-discount', 'category' => 'Fresh Seafood', 'title' => 'Gorton\'s Beer Battered Fish Fillets', 'price' => '$23.00 $25.00', 'img' => 'https://images.unsplash.com/photo-1534482421-64566f976cfa?auto=format&fit=crop&w=300&q=80'
              ])
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
                  ->where('user_items.category_id', $cat->id)
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
                  <p class="text-muted">No products found in this category.</p>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ==================== MIDDLE PROMO CARDS (4 Column Grid) ==================== -->
  <section class="g2-middle-banners py-4 bg-light-2">
    <div class="container">
      <div class="row g-3">
        <!-- Card 1 -->
        <div class="col-lg-3 col-md-6">
          <div class="g2-mid-promo-card" style="background-color: #f7ecdb;">
            <div class="g2-mid-promo-content">
              <h4>Everyday Fresh with Our Products</h4>
              <a href="{{ route('front.user.shop', getParam()) }}">Go To Supplier <i class="far fa-arrow-right"></i></a>
            </div>
            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=150&q=80" alt="Promo">
          </div>
        </div>
        <!-- Card 2 -->
        <div class="col-lg-3 col-md-6">
          <div class="g2-mid-promo-card" style="background-color: #dbeaf7;">
            <div class="g2-mid-promo-content">
              <h4>100% guaranteed all fresh items</h4>
              <a href="{{ route('front.user.shop', getParam()) }}">Go To Supplier <i class="far fa-arrow-right"></i></a>
            </div>
            <img src="https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?auto=format&fit=crop&w=150&q=80" alt="Promo">
          </div>
        </div>
        <!-- Card 3 -->
        <div class="col-lg-3 col-md-6">
          <div class="g2-mid-promo-card" style="background-color: #eaf7db;">
            <div class="g2-mid-promo-content">
              <h4>Special grocery sale off this month</h4>
              <a href="{{ route('front.user.shop', getParam()) }}">Go To Supplier <i class="far fa-arrow-right"></i></a>
            </div>
            <img src="https://images.unsplash.com/photo-1568584711075-3d021a7c3ecf?auto=format&fit=crop&w=150&q=80" alt="Promo">
          </div>
        </div>
        <!-- Card 4 -->
        <div class="col-lg-3 col-md-6">
          <div class="g2-mid-promo-card" style="background-color: #f7dbe0;">
            <div class="g2-mid-promo-content">
              <h4>Enjoy 75% OFF for all vegetables and fruits</h4>
              <a href="{{ route('front.user.shop', getParam()) }}">Go To Supplier <i class="far fa-arrow-right"></i></a>
            </div>
            <img src="https://images.unsplash.com/photo-1610397613050-59f20e362f3d?auto=format&fit=crop&w=150&q=80" alt="Promo">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==================== SIDEBAR BANNER + PRODUCT GRID SECTION ==================== -->
  <section class="g2-sidebar-grid py-5">
    <div class="container">
      <div class="row">
        <!-- Left vertical banner -->
        <div class="col-lg-3 col-md-4 mb-4">
          <div class="g2-vertical-banner" style="background-image: url('https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&w=400&q=80');">
            <div class="g2-vertical-banner-content">
              <h3>Everyday Fresh Clean<br>with Our Products</h3>
              <a href="{{ route('front.user.shop', getParam()) }}" class="btn g2-btn-orange">Shop Now</a>
            </div>
          </div>
        </div>

        <!-- Right Products Grid -->
        <div class="col-lg-9 col-md-8">
          <div class="g2-section-header">
            <h2 class="g2-section-title">Popular Items</h2>
            <div class="g2-arrow-nav">
              <button class="g2-arrow-btn grid-prev"><i class="fal fa-chevron-left"></i></button>
              <button class="g2-arrow-btn grid-next"><i class="fal fa-chevron-right"></i></button>
            </div>
          </div>
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-3 mt-2">
            <!-- Dynamic or Static Items matching lower reference grid -->
            @if(count($all_items) >= 4)
              @foreach($all_items->skip(1)->take(4) as $item)
                @include('user-front.grocery2.partials.product-card', ['item' => $item])
              @endforeach
            @else
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => 'Sale', 'badge_class' => 'badge-sale', 'category' => 'Vegetables', 'title' => 'Onions are a versatile ingredient base', 'price' => '$14.00 - $155.00', 'img' => 'https://images.unsplash.com/photo-1508747703725-7197771375e0?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => 'New', 'badge_class' => 'badge-new', 'category' => 'Vegetables', 'title' => 'Tomato is both a fruit and a vegetable', 'price' => '$11.50 - $120.00', 'img' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '10%', 'badge_class' => 'badge-discount', 'category' => 'Baking material', 'title' => 'Organic Cage Grade A Large Eggs', 'price' => '$21.00 $30.00', 'img' => 'https://images.unsplash.com/photo-1506976785307-8732e854ad03?auto=format&fit=crop&w=300&q=80'
              ])
              @include('user-front.grocery2.partials.product-card-static', [
                'badge' => '9%', 'badge_class' => 'badge-discount', 'category' => 'Dairy & Cereal', 'title' => 'Naturally Flavored Cinnamon Vanilla', 'price' => '$51.00 $55.00', 'img' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=300&q=80'
              ])
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==================== LARGE CTA SUBSCRIPTION BANNER ==================== -->
  <section class="g2-cta-banner py-5" style="background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80');">
    <div class="g2-cta-overlay"></div>
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-lg-8 g2-cta-content">
          <h2>Stay home & get your daily<br>needs from our shop</h2>
          <p>Start Your Daily Shopping with Easen</p>
          
          <form class="g2-cta-form" action="{{ route('front.user.subscribe', getParam()) }}" method="get">
            @csrf
            <input type="email" name="email" placeholder="Your email address" required>
            <button type="submit" class="btn g2-btn-navy">Sign up</button>
          </form>
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
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Main Hero Slider Carousel
    if ($('#g2-main-slider').length > 0) {
      $('#g2-main-slider').slick({
        dots: true,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 600,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        fade: true,
        cssEase: 'linear',
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
