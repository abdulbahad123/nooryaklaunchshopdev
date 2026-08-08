<!-- products Section Start -->
<section class="products-section-v7 pt-100 pb-100 lazy">
  <div class="container">
    <div class="row">
      <div class="col-12" data-aos="fade-down" data-aos-delay="100">
        <div class="section-title title-inline flex-wrap mb-20">
          <h2 class="title mb-20">{{ $tabs[$i]->name }}</h2>
          <a href="{{ route('front.user.shop', getParam()) }}" class="btn btn-md btn-primary radius-30 mb-20"
            title="Show All" target="_self">{{ $keywords['Shop More'] ?? __('Shop More') }}</a>
        </div>
      </div>
      <div class="col-xl-8" data-aos="fade-up" data-aos-delay="100">
        <div class="row">
          @for ($skeleton = 1; $skeleton <= 4; $skeleton++)
            <div class="col-6">
              <!-- product-inline -->
              <div class="product-default-7-inline mb-30 radius-sm skeleton">
                <figure class="product-img">
                  <a href="javascript:void(0)" class="ratio ratio-1-1 radius-md skeleton skeleton-big-img">

                  </a>
                </figure>
                <div class="product-details">
                  <span class="product-category skeleton skeleton-category"></span>
                  <h5 class="product-title lc-2 mb-2 skeleton skeleton-title">
                  </h5>
                  <!-- Rateing -->
                  <div class="d-flex justify-content-start align-items-center mb-10 skeleton skeleton-ratings">
                  </div>
                  <!-- product-price -->
                  <div class="product-price mb-10">
                    <span class="new-price fw-semibold skeleton skeleton-price"></span>
                    <span class="old-price fw-medium text-decoration-line-through skeleton skeleton-price"></span>
                  </div>

                  <!-- btn-icon-group -->
                  <div class="btn-icon-group btn-inline">
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                  </div>
                </div>
              </div>
            </div>
          @endfor
        </div>
      </div>
      <div class="col-xl-4" data-aos="fade-up" data-aos-delay="100">
        <div class="banner-md-vertical radius-md mb-30 ratio ratio-1-1">
          <div class="banner-content skeleton">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="shape">
    <img class="shape-1 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/products/shape/shape-1.png') }}"
      alt="shape">
    <img class="shape-2 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/products/shape/shape-2.png') }}"
      alt="shape">
    <img class="shape-3 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/products/shape/shape-3.png') }}"
      alt="shape">
    <img class="shape-4 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/products/shape/shape-4.png') }}"
      alt="shape">
    <img class="shape-5 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/products/shape/shape-5.png') }}"
      alt="shape">
    <img class="shape-6 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/products/shape/shape-6.png') }}"
      alt="shape">
  </div>
</section>

<section class="products-section-v7 pt-100 pb-100 actual-content">
  <div class="container">
    <div class="row">
      <div class="col-12" data-aos="fade-down" data-aos-delay="100">
        <div class="section-title title-inline flex-wrap mb-20">
          <h2 class="title mb-20">{{ $tabs[$i]->name }}</h2>
          <a href="{{ route('front.user.shop', getParam()) }}" class="btn btn-md btn-primary radius-30 mb-20"
            title="Show All" target="_self">{{ $keywords['Shop More'] ?? __('Shop More') }}</a>
        </div>
      </div>
      <div class="col-xl-8" data-aos="fade-up" data-aos-delay="100">
        @php
          $products = json_decode($tabs[$i]->products, true);
        @endphp
        @if (!is_null($products))
          <div class="row">
            @for ($j = 0; $j < count($products); $j++)
              @php
                $product_details = \App\Models\User\UserItem::where('id', $products[$j])
                    ->with([
                        'itemContents' => function ($q) use ($uLang) {
                            $q->where('language_id', '=', $uLang);
                        },
                        'sliders',
                    ])
                    ->first();
                if (is_null($product_details)) { continue; }
              @endphp
              @if (!is_null(@$product_details->itemContents[0]->slug))
                @php
                  $flash_info = flashAmountStatus($product_details->id, $product_details->current_price);
                  $product_current_price = $flash_info['amount'];
                  $flash_status = $flash_info['status'];
                @endphp
                <div class="col-6">
                  <!-- product-inline -->
                  <div class="product-default-7-inline mb-30 radius-sm"
                    style="background:#{{ $product_details->background_color }}">
                    <figure class="product-img">
                      <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}"
                        class="ratio ratio-1-1 radius-md">
                        <img class="lazyload blur-up default-img"
                          src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                          alt="Product">
                        <img class="lazyload blur-up hover-img"
                          src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                          alt="Product">
                      </a>
                    </figure>
                    <div class="product-details">
                      <a class="product-category"
                        href="{{ route('front.user.shop', ['category' => $product_details->itemContents[0]->category->slug, getParam()]) }}">{{ $product_details->itemContents[0]->category->name }}</a>
                      <h5 class="product-title lc-2 mb-2">
                        <a
                          href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}">
                          {{ $product_details->itemContents[0]->title }}
                        </a>
                      </h5>
                      <!-- Rateing -->
                      @if ($shop_settings->item_rating_system == 1)
                        <div class="d-flex justify-content-start align-items-center mb-10">
                          <div class="product-ratings rate text-xsm">
                            <div class="rating" style="width:{{ $product_details->rating * 20 }}%"></div>
                          </div>
                          <span class="ratings-total">({{ reviewCount($product_details->id) }})</span>
                        </div>
                      @endif
                      <!-- product-price -->

                      <div class="product-price mb-10">
                        @if ($flash_status == true)
                          <span
                            class="new-price fw-semibold">{{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}</span>
                          <span class="old-price fw-medium text-decoration-line-through">
                            {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details->current_price)) }}
                          </span>
                        @else
                          <span class="new-price fw-semibold">
                            {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details->current_price)) }}
                          </span>
                          <span class="old-price fw-medium text-decoration-line-through">
                            {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details->previous_price)) }}
                          </span>
                        @endif
                      </div>

                      <!-- btn-icon-group -->
                      <div class="btn-icon-group btn-inline">
                        <a href="javascript:void(0)" class="btn btn-icon" data-bs-toggle="tooltip"
                          data-bs-placement="top"
                          onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details->id, getParam()]) }}')"
                          title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i>
                        </a>
                        <button type="button" class="btn btn-icon quick-view-link" data-bs-toggle="tooltip"
                          data-bs-placement="top" data-slug="{{ $product_details->itemContents[0]->slug }}"
                          data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product_details->itemContents[0]->slug, getParam()]) }}"
                          title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                          <i class="fal fa-eye"></i>
                        </button>
                        @php
                          $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                          $checkWishList = $customer_id ? checkWishList($product_details->id, $customer_id) : false;
                        @endphp
                        <a href="javascript:void(0)"
                          class="btn btn-icon btn-wish {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                          data-bs-toggle="tooltip" data-bs-placement="top"
                          data-url="{{ route('front.user.add.wishlist', ['id' => $product_details->id, getParam()]) }}"
                          data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details->id, getParam()]) }}"
                          title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                            class="fal fa-heart"></i>
                        </a>
                        @if ($shop_settings->catalog_mode != 1)
                          <a href="#" class="btn btn-icon cart-link"
                            data-title="{{ $product_details->itemContents[0]->title }}"
                            data-current_price="{{ currency_converter($product_current_price) }}"
                            data-item_id="{{ $product_details->id }}" data-language_id="{{ $uLang }}"
                            data-totalVari="{{ check_variation($product_details->id) }}"
                            data-variations="{{ check_variation($product_details->id) > 0 ? 'yes' : null }}"
                            data-href="{{ route('front.user.add.cart', ['id' => $product_details->id, getParam()]) }}"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                              class="fal fa-shopping-bag"></i>
                          </a>
                        @endif
                      </div>
                    </div>

                    @php
                      $item_label = DB::table('labels')
                          ->where('id', $product_details->itemContents[0]->label_id)
                          ->first();
                      $label = $item_label->name ?? null;
                      $color = $item_label->color ?? null;
                    @endphp
                    @if ($item_label)
                      <span class="label label-2"
                        style="background-color: #{{ $color }}">{{ $label }}</span>
                    @endif

                    @if ($flash_status == true)
                      <span class="label-discount-percentage">
                        <x-flash-icon></x-flash-icon>{{ $product_details->flash_amount }}%
                      </span>
                    @endif
                  </div>
                </div>
              @endif
            @endfor
          </div>
        @endif
      </div>
      @if ($ubs->right_banner_section == 1)
        @if ($banners)
          @php
            $rightBanners = $banners->where('position', 'right')->values();
            $banner = $rightBanners[$i] ?? null;
          @endphp
          @if ($banner)
            <div class="col-xl-4 d-none d-xl-block" data-aos="fade-up" data-aos-delay="100">
              <!-- banner-md -->
              <div class="banner-md-vertical  radius-md mb-30 ratio ratio-1-1">
                <img class="lazyload bg-img blur-up"
                  data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                <div class="banner-content">
                  <div class="content-inner">
                    <p class="mb-1 small text-white">{{ $banner->title }}</p>
                    <h3 class="title text-white fw-bold mb-20">
                      {{ $banner->subtitle }}
                    </h3>
                    @if (!is_null($banner->banner_url))
                      <a href="{{ $banner->banner_url }}" class="btn btn-light text-sm fw-semibold radius-30">
                        {{ $banner->button_text }}
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endif
        @endif
      @endif
    </div>
  </div>

  <div class="shape">
    <img class="shape-1 lazyload blur-up"
      src="{{ asset('assets/user-front/images/pet/products/shape/shape-1.png') }}" alt="shape">
    <img class="shape-2 lazyload blur-up"
      src="{{ asset('assets/user-front/images/pet/products/shape/shape-2.png') }}" alt="shape">
    <img class="shape-3 lazyload blur-up"
      src="{{ asset('assets/user-front/images/pet/products/shape/shape-3.png') }}" alt="shape">
    <img class="shape-4 lazyload blur-up"
      src="{{ asset('assets/user-front/images/pet/products/shape/shape-4.png') }}" alt="shape">
    <img class="shape-5 lazyload blur-up"
      src="{{ asset('assets/user-front/images/pet/products/shape/shape-5.png') }}" alt="shape">
    <img class="shape-6 lazyload blur-up"
      src="{{ asset('assets/user-front/images/pet/products/shape/shape-6.png') }}" alt="shape">
  </div>
</section>
<!-- products Section End -->
