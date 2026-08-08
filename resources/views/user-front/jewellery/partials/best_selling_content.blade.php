<!-- Product Section Start -->
<section class="products-section-v8 pt-100 pb-100 overflow-hidden lazy">
  <div class="container">
    <div class="row justify-content-center">
      @if ($ubs->left_banner_section == 1)
        @if ($banners)
          @php
            $totalBanners = $banners->where('position', 'left')->values();
            $rightBanners = $totalBanners->take(1);
          @endphp
          @foreach ($rightBanners as $banner)
            <div class="col-xl-7 col-lg-6 mb-30" data-aos="fade-left" data-aos-delay="100">
              <div class="product-im h-100">
                <img class="blur-up lazyload h-100"
                  data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="product">
              </div>
            </div>
          @endforeach
        @endif
      @endif
      <div class="{{ $ubs->left_banner_section == 1 ? 'col-xl-5 col-lg-6' : 'col-md-12' }}" data-aos="fade-right"
        data-aos-delay="100">
        <h2 class="h2 mb-20">{{ $userSec->top_rated_product_section_title ?? ' ' }}</h2>
        <p class="mb-30">{{ $userSec->top_selling_product_section_title ?? ' ' }}</p>

        <div class="row">
          @for ($skeleton = 1; $skeleton <= 2; $skeleton++)
            <div class="col-6">
              <!-- product-default -->
              <div class="product-default-8 product-default-style-2 mb-30">
                <figure class="product-img skeleton skeleton-big-img">

                </figure>
                <div class="product-details border-0">
                  <!-- btn-icon-group -->
                  <div class="btn-icon-group justify-content-center btn-inline text-center mb-3">
                    <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                    <button type="button" class="btn btn-icon skeleton skeleton-btn-icon">
                    </button>
                    <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                    <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                  </div>
                  <span class="product-category text-sm skeleton skeleton-title"></span>
                  <h5 class="product-title fw-medium lc-2 mt-1 mb-2 skeleton skeleton-title"></h5>
                  <!-- Rateing -->
                  <div class="d-flex align-items-center mb-10 skeleton skeleton-ratings"></div>
                  <div class="product-price">
                    <span class="new-price fw-bold skeleton skeleton-price"></span>
                    <span class="old-price fw-medium text-decoration-line-through skeleton skeleton-price"></span>
                  </div>
                </div>
              </div>
            </div>
          @endfor
        </div>
      </div>
    </div>
  </div>
</section>
<section class="products-section-v8 pt-100 pb-100 overflow-hidden actual-content">
  <div class="container">
    <div class="row justify-content-center">
      @if ($ubs->left_banner_section == 1)
        @if ($banners)
          @php
            $totalBanners = $banners->where('position', 'left')->values();
            $rightBanners = $totalBanners->take(1);
          @endphp
          @foreach ($rightBanners as $banner)
            <div class="col-xl-7 col-lg-6 mb-30" data-aos="fade-left" data-aos-delay="100">
              <div class="product-im h-100">
                <img class="blur-up lazyload h-100"
                  data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="product">
              </div>
            </div>
          @endforeach
        @endif
      @endif
      <div class="{{ $ubs->left_banner_section == 1 ? 'col-xl-5 col-lg-6' : 'col-md-12' }}" data-aos="fade-right"
        data-aos-delay="100">
        <h2 class="h2 mb-20">{{ $userSec->top_rated_product_section_title ?? ' ' }}</h2>
        <p class="mb-30">{{ $userSec->top_selling_product_section_title ?? ' '}}</p>
        @if (count($top_selling) == 0)
          <h3 class="title text-center mt-30 mb-20">
            {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
          </h3>
        @else
          <div class="row">
            <div id="product-inline-slider-11" class="product-inline-slider"
              data-slick='{"dots": true, "slidesToShow": 2}'>
              @foreach ($top_selling as $top_sell)
                @if (@$top_sell->item->itemContents != '[]')
                  @if (@$top_sell->item->status == 1)
                    @if (isset($top_sell))
                      @php
                        $flash_info = flashAmountStatus($top_sell->item->id, $top_sell->item->current_price);
                        $product_current_price = $flash_info['amount'];
                        $flash_status = $flash_info['status'];
                      @endphp
                      <div class="col-6 slider-item">
                        <!-- product-default -->
                        <div class="product-default-8 product-default-style-2 mb-30">
                          <figure class="product-img">
                            <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_sell->item->itemContents[0]->slug]) }}"
                              class="ratio ratio-1-1">
                              <img class="lazyload blur-up default-img"
                                src="{{ asset('assets/front/images/placeholder.png') }}"
                                data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_sell->item->thumbnail) }}"
                                alt="Product">
                              <img class="lazyload blur-up hover-img"
                                src="{{ asset('assets/front/images/placeholder.png') }}"
                                data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_sell->item->thumbnail) }}"
                                alt="Product">
                            </a>
                          </figure>
                          <div class="product-details border-0">
                            <!-- btn-icon-group -->
                            <div class="btn-icon-group justify-content-center btn-inline text-center mb-3">
                              <a href="javascript:void(0)" class="btn btn-icon" data-bs-toggle="tooltip"
                                onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_sell->item->id, getParam()]) }}')"
                                data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                  class="fal fa-random"></i>
                              </a>

                              <button type="button" class="btn btn-icon quick-view-link" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-slug="{{ $top_sell->item->itemContents[0]->slug }}"
                                data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_sell->item->itemContents[0]->slug, getParam()]) }}"
                                title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                                <i class="fal fa-eye"></i>
                              </button>
                              @php
                                $customer_id = Auth::guard('customer')->check()
                                    ? Auth::guard('customer')->user()->id
                                    : null;
                                $checkWishList = $customer_id
                                    ? checkWishList($top_sell->item->id, $customer_id)
                                    : false;
                              @endphp

                              <a href="javascript:void(0)"
                                class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                data-item_id="{{ $top_sell->item->id }}"
                                data-href="{{ route('front.user.add.wishlist', ['id' => $top_sell->item->id, getParam()]) }}"
                                data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_sell->item->id, getParam()]) }}"
                                title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}">
                                <i class="fal fa-heart"></i>
                              </a>
                              @if ($shop_settings->catalog_mode != 1)
                                <a href="javascript:void(0)" class="btn btn-icon cart-link"
                                  data-title="{{ $top_sell->item->itemContents[0]->title }}"
                                  data-current_price="{{ currency_converter($product_current_price) }}"
                                  data-item_id="{{ $top_sell->item->id }}" data-language_id="{{ $uLang }}"
                                  data-totalVari="{{ check_variation($top_sell->item->id) }}"
                                  data-variations="{{ check_variation($top_sell->item->id) > 0 ? 'yes' : null }}"
                                  data-href="{{ route('front.user.add.cart', ['id' => $top_sell->item->id, getParam()]) }}"
                                  data-bs-toggle="tooltip" data-placement="top"
                                  title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}">
                                  <i class="fal fa-shopping-bag"></i>
                                </a>
                              @endif
                            </div>
                            <span class="product-category text-sm">
                              <a
                                href="{{ route('front.user.shop', ['category' => $top_sell->item->itemContents[0]->category->slug, getParam()]) }}">{{ @$top_sell->item->itemContents[0]->category->name }}</a>
                            </span>
                            <h5 class="product-title fw-medium lc-2 mt-1 mb-2">
                              <a
                                href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_sell->item->itemContents[0]->slug]) }}">{{ $top_sell->item->itemContents[0]->title }}</a>
                            </h5>
                            @if ($shop_settings->item_rating_system == 1)
                              <!-- Rateing -->
                              <div class="d-flex align-items-center mb-10">
                                <div class="product-ratings rate text-xsm">
                                  <div class="rating" style="width:{{ $top_sell->item->rating * 20 }}%"></div>
                                </div>
                                <span class="ratings-total">({{ reviewCount($top_sell->item->id) }})</span>
                              </div>
                            @endif
                            <div class="product-price">
                              @if ($flash_status == true)
                                <span class="new-price fw-bold">
                                  {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
                                </span>
                                <span class="old-price fw-medium text-decoration-line-through">
                                  {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($top_sell->item->current_price)) }}
                                </span>
                              @else
                                <span class="new-price fw-bold">
                                  {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($top_sell->item->current_price)) }}
                                </span>
                                <span class="old-price fw-medium text-decoration-line-through">
                                  {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($top_sell->item->previous_price)) }}
                                </span>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endif
                @endif
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
<!-- Product Section End -->
