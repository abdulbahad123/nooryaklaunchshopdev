<!-- Featured Section Start -->

<!-- Skeleton Start -->
<section class="featured-section-v9 pb-100 lazy">
  <div class="container">
    <div class="row gx-xl-5">
      <div class="col-lg-8 col-xl-7" data-aos="fade-up" data-aos-delay="100">
        <div class="featured-left-area mb-100">
          <div class="section-heading-area">
            <h2 class="h2 mb-20 ">{{ @$userSec->top_rated_product_section_title ?? __('Top Rated') }}</h2>
            <p class="small fw-medium mb-40">{{ @$userSec->top_rated_product_section_subtitle }}</p>
          </div>

          <div class="product-inline-slider-wrapper">
            @if (count($top_rated) == 0)
              <h4 class="title mb-20 mt-20 text-center">
                {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
              </h4>
            @else
              <div id="product-inline-slider-10" class="product-inline-slider"
                data-slick='{"dots": true, "slidesToShow": 2}'>
                @for ($skeleton = 0; $skeleton < 2; $skeleton++)
                  <div class="slider-item">
                    <div class="product-default-9-inline">
                      <figure class="product-img border radius-md skeleton skeleton-big-img">

                      </figure>
                      <div class="product-details">
                        <span class="product-category skeleton skeleton-title"></span>
                        <h5 class="product-title lc-1 fw-medium mb-2 skeleton skeleton-title"></h5>
                        <div class="d-flex justify-content-start align-items-center mb-10 skeleton skeleton-ratings">
                        </div>
                        <!-- product-price -->
                        <div class="product-price mt-10 mb-3">
                          <span class="new-price fw-semibold skeleton skeleton-price"></span>
                          <span class="old-price fw-medium text-decoration-line-through skeleton skeleton-price"></span>
                        </div>

                        <!-- btn-icon-group -->
                        <div class="btn-icon-group btn-inline">
                          <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                          <button type="button" class="btn btn-icon skeleton skeleton-btn-icon"></button>
                          <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon">
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endfor
              </div>
            @endif
          </div>
        </div>
      </div>

      @if ($ubs->right_banner_section == 1)
        @if ($banners)
          @php
            $rightBanners = $banners->where('position', 'right')->values();
            $banner = $rightBanners[0] ?? null;
          @endphp
          @if ($banner)
            <div class="col-lg-4 col-xl-5" data-aos="fade-up" data-aos-delay="100">
              <div class="featured-right-area">

                <div class="banner-md radius-lg mb-30 ratio ratio-21-9 skeleton skeleton-big-img">
                </div>
              </div>
            </div>
          @endif
        @endif
      @endif
    </div>
  </div>
</section>
<!-- Skeleton End -->

<!-- Orginal Content Start -->
<section class="featured-section-v9 pb-100 actual-content">
  <div class="container">
    <div class="row gx-xl-5">
      <div class="col-lg-8 col-xl-7" data-aos="fade-up" data-aos-delay="100">
        <div class="featured-left-area mb-100">
          <div class="section-heading-area">
            <h2 class="h2 mb-20 ">{{ @$userSec->top_rated_product_section_title ?? __('Top Rated') }}</h2>
            <p class="small fw-medium mb-40">{{ @$userSec->top_rated_product_section_subtitle }}</p>
          </div>

          <div class="product-inline-slider-wrapper">
            @if (count($top_rated) == 0)
              <h4 class="title mb-20 mt-20 text-center">
                {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
              </h4>
            @else
              <div id="product-inline-slider-11" class="product-inline-slider"
                data-slick='{"dots": true, "slidesToShow": 2}'>
                @for ($k = 0; $k < count($top_rated); $k++)
                  @if ($top_rated[$k]->itemContents != '[]')
                    @if (isset($top_rated[$k]))
                      @php
                        $flash_info = flashAmountStatus($top_rated[$k]->id, $top_rated[$k]->current_price);
                        $product_current_price = $flash_info['amount'];
                        $flash_status = $flash_info['status'];
                      @endphp
                      <!-- product-inline -->
                      <div class="slider-item">
                        <div class="product-default-9-inline">
                          <figure class="product-img border radius-md">
                            <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k]->itemContents[0]->slug]) }}"
                              class="ratio ratio-1-1">
                              <img class="lazyload blur-up default-img"
                                src="{{ asset('assets/front/images/placeholder.png') }}"
                                data-src="{{ asset('assets/front/img/user/items/thumbnail/' . @$top_rated[$k]->thumbnail) }}"
                                alt="Product">
                              <img class="lazyload blur-up hover-img"
                                src="{{ asset('assets/front/images/placeholder.png') }}"
                                data-src="{{ asset('assets/front/img/user/items/thumbnail/' . @$top_rated[$k]->thumbnail) }}"
                                alt="Product">
                            </a>
                          </figure>
                          <div class="product-details">
                            <span class="product-category">
                              <a
                                href="{{ route('front.user.shop', ['category' => @$top_rated[$k]->itemContents[0]->category->slug, getParam()]) }}">{{ @$top_rated[$k]->itemContents[0]->category->name }}</a>
                            </span>
                            <h5 class="product-title lc-1 fw-medium mb-2">
                              <a
                                href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k]->itemContents[0]->slug]) }}">
                                {{ $top_rated[$k]->itemContents[0]->title }}
                              </a>
                            </h5>
                            @if ($shop_settings->item_rating_system == 1)
                              <!-- Rateing -->
                              <div class="d-flex justify-content-start align-items-center mb-10">
                                <div class="product-ratings rate text-xsm">
                                  <div class="rating" style="width:{{ $top_rated[$k]->rating * 20 }}%"></div>
                                </div>
                                <span class="ratings-total">({{ reviewCount($top_rated[$k]->id) }})</span>
                              </div>
                            @endif
                            <!-- product-price -->
                            <div class="product-price mt-10 mb-3">
                              @if ($flash_status == true)
                                <span class="new-price fw-semibold">
                                  {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
                                </span>
                                <span class="old-price fw-medium text-decoration-line-through">
                                  {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($top_rated[$k]->current_price)) }}
                                </span>
                              @else
                                <span class="new-price fw-semibold">
                                  {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($top_rated[$k]->current_price)) }}
                                </span>
                                <span class="old-price fw-medium text-decoration-line-through">
                                  {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($top_rated[$k]->previous_price)) }}
                                </span>
                              @endif
                            </div>

                            <!-- btn-icon-group -->
                            <div class="btn-icon-group btn-inline">
                              <a href="javascript:void(0)" class="btn btn-icon" data-bs-toggle="tooltip"
                                onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_rated[$k]->id, getParam()]) }}')"
                                data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                  class="fal fa-random"></i>
                              </a>

                              <button type="button" class="btn btn-icon  quick-view-link" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-slug="{{ $top_rated[$k]->itemContents[0]->slug }}"
                                data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_rated[$k]->itemContents[0]->slug, getParam()]) }}"
                                title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                                <i class="fal fa-eye"></i>
                              </button>
                              @php
                                $customer_id = Auth::guard('customer')->check()
                                    ? Auth::guard('customer')->user()->id
                                    : null;
                                $checkWishList = $customer_id ? checkWishList($top_rated[$k]->id, $customer_id) : false;
                              @endphp
                              <a href="javascript:void(0)"
                                class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                data-item_id="{{ $top_rated[$k]->id }}"
                                data-href="{{ route('front.user.add.wishlist', ['id' => $top_rated[$k]->id, getParam()]) }}"
                                data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_rated[$k]->id, getParam()]) }}"
                                title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}">
                                <i class="fal fa-heart"></i>
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
                                  title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                    class="fal fa-shopping-bag"></i>
                                </a>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endif
                @endfor
              </div>
            @endif
          </div>
        </div>
      </div>

      @if ($ubs->right_banner_section == 1)
        @if ($banners)
          @php
            $rightBanners = $banners->where('position', 'right')->values();
            $banner = $rightBanners[0] ?? null;
          @endphp
          @if ($banner)
            <div class="col-lg-4 col-xl-5" data-aos="fade-up" data-aos-delay="100">
              <div class="featured-right-area">

                <div class="banner-md radius-lg mb-30 ratio ratio-21-9">
                  <img class="lazyload bg-img"
                    data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                  <div class="banner-content">
                    <div class="content-inner">
                      <h4 class="title text-white fw-bold mb-2">{{ $banner->title }}</h5>
                      <p class="desc mb-1 small text-white">{{ $banner->subtitle }}</p>
                    </div>
                    <!-- btn-icon-group -->
                    @if ($banner->banner_url && $banner->button_text)
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
</section>
<!-- Featured Section End -->
