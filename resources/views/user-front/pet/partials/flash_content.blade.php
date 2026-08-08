<section class="flash-sale flash-sale-7 pt-100 pb-100 lazy" data-aos="fade-up" data-aos-delay="100">
  <div class="container">
    <div class="row gx-0">
      <div class="col-lg-5">
        <div class="flash-image">
          <img class="lazyload blur-up"
            src="{{ !is_null(@$userSec->flash_section_background_image) ? asset('assets/front/img/user/flash_section/' . $userSec->flash_section_background_image) : asset('assets/user-front/images/pet/flash-img.png') }}"
            alt="flash-image">
        </div>
      </div>
      <div class="col-lg-7 ">
        <div class="section-title title-inline flex-wrap mb-20">
          <h2 class="title mb-20">
            {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}</h2>
          <div class="slider-arrow space-nowrap mb-20" id="flash-slider-pet-skeleton-arrows"></div>
        </div>
        <!-- flash-slider -->
        <div class="flash-slider" id="flash-slider-pet-skeleton" data-slick='{"arrows": true, "slidesToShow": 2}'>
          <!-- product-default -->
          @for ($skeleton = 0; $skeleton <= 2; $skeleton++)
            <div class="product-default-7 product-center radius-lg mb-30">
              <figure class="product-img skeleton skeleton-big-img"></figure>
              <div class="product-details">
                <!-- btn-icon-group -->
                <div class="btn-icon-group justify-content-center btn-inline mb-3">
                  <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                  <button type="button" class="btn btn-icon skeleton skeleton-btn-icon">
                  </button>
                  <a href="javascript:void(0)" class="btn btn-icon  skeleton skeleton-btn-icon"></a>
                  <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                </div>
                <!-- Rateing -->
                <div class="d-flex justify-content-center align-items-center mb-1">
                  <div class="skeleton skeleton-ratings">
                  </div>
                </div>

                <h3 class="product-title lc-2 mb-2 skeleton skeleton-title"></h3>
                <div class="product-price m-0">
                  <span class="new-price fw-bold skeleton skeleton-price"></span>
                  <span class="old-price fw-medium text-decoration-line-through skeleton skeleton-price"></span>
                </div>

                <div class="product-countdown-area mt-2">
                  <span class="small fw-medium skeleton skeleton-price"></span>
                  <div class="product-countdown justify-content-center">
                    <div class="count radius-sm skeleton skeleton-price">
                    </div>
                    <div class="count radius-sm skeleton skeleton-price">
                    </div>
                    <div class="count radius-sm skeleton skeleton-price">
                    </div>
                    <div class="count radius-sm skeleton skeleton-price">
                    </div>
                  </div>
                </div>
              </div>

            </div>
          @endfor

        </div>
      </div>
    </div>
  </div>
  <div class="shape">
    <img class="shape-1 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-1.png') }}"
      alt="shape">
    <img class="shape-2 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-2.png') }}"
      alt="shape">
    <img class="shape-3 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-3.png') }}"
      alt="shape">
    <img class="shape-4 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-4.png') }}"
      alt="shape">
    <img class="shape-5 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-5.png') }}"
      alt="shape">
    <img class="shape-6 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-6.png') }}"
      alt="shape">
  </div>
</section>
<section class="flash-sale flash-sale-7 pt-100 pb-100 actual-content" data-aos="fade-up" data-aos-delay="100">
  <div class="container">
    <div class="row gx-0">
      <div class="col-lg-5">
        <div class="flash-image">
          <img class="lazyload blur-up"
            src="{{ !is_null(@$userSec->flash_section_background_image) ? asset('assets/front/img/user/flash_section/' . $userSec->flash_section_background_image) : asset('assets/user-front/images/pet/flash-img.png') }}"
            alt="flash-image">
        </div>
      </div>
      <div class="col-lg-7 ">
        <div class="section-title title-inline flex-wrap mb-20">
          <h2 class="title mb-20">
            {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}</h2>
          <div class="slider-arrow space-nowrap mb-20" id="flash-slider-pet-arrows"></div>
        </div>
        <!-- flash-slider -->
        @if (count($flash_items) > 0)
          <div class="flash-slider" id="flash-slider-pet" data-slick='{"arrows": true, "slidesToShow": 2}'>
            <!-- product-default -->
            @foreach ($flash_items as $item)
              <div class="product-default-7 product-center radius-lg mb-30">
                <figure class="product-img">
                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                    class="ratio ratio-1-1 radius-md">
                    <img class="lazyload blur-up default-img"
                      src="{{ asset('assets/user-front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                      alt="Product">
                    <img class="lazyload blur-up hover-img"
                      src="{{ asset('assets/user-front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                      alt="Product">
                  </a>
                </figure>
                <div class="product-details">
                  <!-- btn-icon-group -->
                  <div class="btn-icon-group justify-content-center btn-inline mb-3">
                    <a href="javascript:void(0)"
                      onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                      class="btn btn-icon" data-bs-toggle="tooltip" data-placement="top"
                      title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i>
                    </a>

                    <button type="button" class="btn btn-icon quick-view-link" data-bs-toggle="tooltip"
                      data-placement="top" data-item_id="{{ $item->item_id }}"
                      data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                      title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                      <i class="fal fa-eye"></i>
                    </button>

                    @php
                      $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                      $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                    @endphp
                    <a href="#"
                      class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                      data-bs-toggle="tooltip" data-placement="top"
                      data-url="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                      data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                      title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                        class="fal fa-heart"></i>
                    </a>

                    @if ($shop_settings->catalog_mode != 1)
                      @php
                        $current_price =
                            $item->flash == 1
                                ? $item->current_price - $item->current_price * ($item->flash_amount / 100)
                                : $item->current_price;
                      @endphp
                      <a href="#" class="btn btn-icon cart-link" data-bs-toggle="tooltip" data-placement="top"
                        data-title="{{ $item->title }}" data-current_price="{{ $current_price }}"
                        data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                        data-totalVari="{{ check_variation($item->item_id) }}"
                        data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                        data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                        title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                          class="fal fa-shopping-bag"></i>
                      </a>
                    @endif
                  </div>
                  <!-- Rateing -->
                  @if ($shop_settings->item_rating_system == 1)
                    <div class="d-flex justify-content-center align-items-center mb-1">
                      <div class="product-ratings rate text-xsm">
                        <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                      </div>
                      <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                    </div>
                  @endif

                  <h3 class="product-title lc-2 mb-2">
                    <a
                      href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ $item->title }}</a>
                  </h3>
                  <div class="product-price m-0">
                    @if ($item->flash == 1)
                      <span
                        class="new-price fw-bold">{{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price - $item->current_price * ($item->flash_amount / 100))) }}</span>
                      <span class="old-price fw-medium text-decoration-line-through">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
                      </span>
                    @else
                      <span
                        class="new-price fw-bold">{{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}</span>
                      <span class="old-price fw-medium text-decoration-line-through">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->previous_price)) }}
                      </span>
                    @endif
                  </div>

                  <div class="product-countdown-area mt-2">
                    <span class="small fw-medium">End offer:</span>
                    <div class="product-countdown justify-content-center" data-start_date="{{ $item->start_date }}"
                      data-end_time="{{ $item->end_time }}" data-end_date="{{ $item->end_date }}"
                      data-item_id="{{ $item->item_id }}">
                      <div class="count radius-sm days">
                        <span class="count-value count-value_{{ $item->item_id }} mb-0"></span>
                      </div>
                      <div class="count radius-sm hours">
                        <span class="count-value count-value_{{ $item->item_id }} mb-0"></span>
                      </div>
                      <div class="count radius-sm minutes">
                        <span class="count-value count-value_{{ $item->item_id }} mb-0"></span>
                      </div>
                      <div class="count radius-sm seconds">
                        <span class="count-value count-value_{{ $item->item_id }} mb-0"></span>
                      </div>
                    </div>
                  </div>
                </div>
                @php
                  $item_label = DB::table('labels')->where('id', $item->label_id)->first();
                  $label = $item_label->name ?? null;
                  $color = $item_label->color ?? null;
                @endphp
                @if ($item_label)
                  <span class="label label-2"
                    style="background-color: #{{ $color }}">{{ $label }}</span>
                @endif

                <span class="label-discount-percentage">
                  <x-flash-icon></x-flash-icon>{{ $item->flash_amount }}%
                </span>
              </div>
            @endforeach
          </div>
        @else
          <h5 class="title mb-20">
            {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
          </h5>
        @endif
      </div>
    </div>
  </div>
  <div class="shape">
    <img class="shape-1 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-1.png') }}"
      alt="shape">
    <img class="shape-2 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-2.png') }}"
      alt="shape">
    <img class="shape-3 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-3.png') }}"
      alt="shape">
    <img class="shape-4 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-4.png') }}"
      alt="shape">
    <img class="shape-5 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-5.png') }}"
      alt="shape">
    <img class="shape-6 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/flash-sale/shape-6.png') }}"
      alt="shape">
  </div>
</section>
