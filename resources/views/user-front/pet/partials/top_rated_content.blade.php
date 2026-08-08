<div class="col-lg-6 lazy">
  <h2 class="title fw-bold mb-40" data-aos="fade-down" data-aos-delay="100">
    {{ $userSec->top_rated_product_section_title ?? __('Top Rated') }}
  </h2>
  <!-- flash-slider -->
  <div data-aos="fade-up" data-aos-delay="100">
    <div class="row">
      @for ($skeleton = 1; $skeleton <= 2; $skeleton++)
        <div class="col-md-6">
          <div class="product-default-7-v2 radius-lg mb-30">
            <figure class="product-img skeleton skeleton-big-img">
            </figure>
            <div class="product-details">
              <!-- btn-icon-group -->
              <div class="btn-icon-group btn-inline justify-content-center mb-3">
                <span class="count-period skeleton skeleton-btn-icon"></span>
                <span class="count-period skeleton skeleton-btn-icon"></span>
                <span class="count-period skeleton skeleton-btn-icon"></span>
                <span class="count-period skeleton skeleton-btn-icon"></span>
              </div>
              <!-- Rateing -->
              <div class="d-flex  align-items-center mb-2 skeleton skeleton-ratings">
              </div>
              <h5 class="product-title fw-medium lc-2 mb-2 skeleton skeleton-title">
              </h5>
              <div class="product-price justify-content-start m-0">
                <span class="new-price skeleton skeleton-price"></span>
                <span class="old-price fw-medium text-decoration-line-through skeleton skeleton-price"></span>
              </div>
            </div>
          </div>
        </div>
      @endfor
    </div>
  </div>
</div>

<div class="col-lg-6 actual-content">
  <h2 class="title fw-bold mb-40" data-aos="fade-down" data-aos-delay="100">
    {{ $userSec->top_rated_product_section_title ?? __('Top Rated') }}
  </h2>
  <!-- flash-slider -->
  <div data-aos="fade-up" data-aos-delay="100">
    @if (count($top_rated) == 0)
      <h4 class="title mb-20 ">
        {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
      </h4>
    @else
      <div class="product-slider-md" id="product-slider-md-pet" data-slick='{"dots": true, "slidesToShow": 2}'>
        @for ($k = 0; $k < count($top_rated); $k++)
          @if ($top_rated[$k]->itemContents != '[]')
            @if (isset($top_rated[$k]))
              <!-- product-default -->
              <div class="product-default-7-v2 radius-lg mb-30">
                <figure class="product-img">
                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k]->itemContents[0]->slug]) }}"
                    class="lazy-container ratio ratio-4-3 radius-md">
                    <img class="lazyload blur-up default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . @$top_rated[$k]->thumbnail) }}"
                      alt="Product">
                    <img class="lazyload blur-up hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . @$top_rated[$k]->thumbnail) }}"
                      alt="Product">
                  </a>
                </figure>
                <div class="product-details">
                  <!-- btn-icon-group -->
                  @php
                    $flash_info = flashAmountStatus($top_rated[$k]->id, $top_rated[$k]->current_price);
                    $product_current_price = $flash_info['amount'];
                    $flash_status = $flash_info['status'];
                  @endphp
                  <div class="btn-icon-group btn-inline justify-content-center mb-3">
                    <a href="javscript:void(0)" class="btn btn-icon" data-bs-toggle="tooltip"
                      onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_rated[$k]->id, getParam()]) }}')"
                      data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}">
                      <i class="fal fa-random"></i>
                    </a>
                    <button type="button" class="btn btn-icon quick-view-link" data-bs-toggle="tooltip"
                      data-bs-placement="top" data-slug="{{ $top_rated[$k]->itemContents[0]->slug }}"
                      data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_rated[$k]->itemContents[0]->slug, getParam()]) }}"
                      title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                      <i class="fal fa-eye"></i>
                    </button>

                    @php
                      $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                      $checkWishList = $customer_id ? checkWishList($top_rated[$k]->id, $customer_id) : false;
                    @endphp

                    <a href="javascript:void(0)"
                      class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                      data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $top_rated[$k]->id }}"
                      data-href="{{ route('front.user.add.wishlist', ['id' => $top_rated[$k]->id, getParam()]) }}"
                      data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_rated[$k]->id, getParam()]) }}"
                      title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i class="fal fa-heart"></i>
                    </a>
                    @if ($shop_settings->catalog_mode != 1)
                      <a href="javascript:void(0)" class="btn btn-icon cart-link"
                        data-title="{{ $top_rated[$k]->itemContents[0]->title }}"
                        data-current_price="{{ currency_converter($product_current_price) }}"
                        data-item_id="{{ $top_rated[$k]->id }}" data-language_id="{{ $uLang }}"
                        data-totalVari="{{ check_variation($top_rated[$k]->id) }}"
                        data-variations="{{ check_variation($top_rated[$k]->id) > 0 ? 'yes' : null }}"
                        data-href="{{ route('front.user.add.cart', ['id' => $top_rated[$k]->id, getParam()]) }}"
                        data-bs-toggle="tooltip" data-placement="top"
                        title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}">
                        <i class="fal fa-shopping-bag"></i>
                      </a>
                    @endif
                  </div>
                  <!-- Rateing -->
                  @if ($shop_settings->item_rating_system == 1)
                    <div class="d-flex  align-items-center mb-2">
                      <div class="product-ratings rate text-xsm">
                        <div class="rating" style="width:{{ $top_rated[$k]->rating * 20 }}%"></div>
                      </div>
                      <span class="ratings-total">({{ reviewCount($top_rated[$k]->id) }})</span>
                    </div>
                  @endif
                  <h5 class="product-title fw-medium lc-2 mb-2">
                    <a
                      href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k]->itemContents[0]->slug]) }}">
                      {{ $top_rated[$k]->itemContents[0]->title }}
                    </a>
                  </h5>

                  <div class="product-price justify-content-start m-0">
                    @if ($flash_status == true)
                      <span class="new-price fw-bold">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
                      </span>
                      <span class="old-price fw-medium text-decoration-line-through">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($top_rated[$k]->current_price)) }}
                      </span>
                    @else
                      <span class="new-price fw-bold">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($top_rated[$k]->current_price)) }}
                      </span>
                      <span class="old-price fw-medium text-decoration-line-through">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($top_rated[$k]->previous_price)) }}
                      </span>
                    @endif
                  </div>
                </div>

                @php
                  $item_label = DB::table('labels')
                      ->where('id', $top_rated[$k]->itemContents[0]->label_id)
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
                    <x-flash-icon></x-flash-icon>{{ $top_rated[$k]->flash_amount }}%
                  </span>
                @endif

              </div>
            @endif
          @endif
        @endfor
      </div>
    @endif
  </div>
</div>
