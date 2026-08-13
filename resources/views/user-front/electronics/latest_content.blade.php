<!-- Product List Start -->
<section class="products pb-100 lazy">
  <div class="container">
    <div class="row gx-xl-4">
      <div class="col-lg-5">
        @if ($ubs->bottom_left_banner_section == 1)
          @if ($banners)
            @php
              $leftBanners = $banners->where('position', 'bottom_left')->take(2);
            @endphp
            @if ($leftBanners)
              @foreach ($leftBanners as $banner)
                <div class="banner-sm lazy-container radius-lg mb-30 ratio ratio-2-3">
                  <img class="lazyload h-100 blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                  <div class="banner-content mw-80">
                    <div class="content-inner">
                      <span class="sub-title">{{ $banner->title }}</span>
                      <h2 class="title-md">{{ $banner->subtitle }}
                      </h2>
                      @if ($banner->banner_url && $banner->button_text)
                        <a href="{{ $banner->banner_url }}"
                          class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banner->button_text }}
                          <span class="icon"><i class="fal fa-arrow-right"></i></span>
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          @endif
        @endif
      </div>

      <div class="col-lg-7">
        <div class="section-title title-inline title-bottom-line mb-10">
          <h2 class="title title-sm mb-0">
            {{ $userSec->latest_product_section_title ?? ($keywords['Latest Item'] ?? __('Latest Item')) }}</h2>
          <div class="slider-arrow-inline style-2" id="product-list-slider-skeleton-1-arrows">
          </div>
        </div>
        <div class="product-list mb-30">
          <div>
            <div class="mb-30">
              <div class="row">
                @for ($skeleton = 1; $skeleton <= 6; $skeleton++)
                  <div class="col-lg-6">
                    <div class="product-default product-inline product-inline-2 mt-20">
                      <figure class="product-img skeleton skeleton-img"></figure>
                      <div class="product-details">
                        <div class="skeleton skeleton-category"></div>
                        <div class="skeleton skeleton-title"></div>

                        <div class="skeleton skeleton-ratings"></div>

                        <div class="product-price mt-1 mb-10">
                          <span class="new-price skeleton skeleton-price"></span>
                          <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                        </div>

                        <div class="d-flex">
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
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Product List End -->

<!-- Product List Start -->
<section class="products pb-100 actual-content">
  <div class="container">
    <div class="row gx-xl-4">
      <div class="col-lg-5">
        @if ($ubs->bottom_left_banner_section == 1)
          @if ($banners)
            @php
              $leftBanners = $banners->where('position', 'bottom_left')->take(2);
            @endphp
            @if ($leftBanners)
              @foreach ($leftBanners as $banner)
                <div class="banner-sm lazy-container radius-lg mb-30 ratio ratio-2-3">
                  <img class="lazyload h-100 blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                  <div class="banner-content mw-80">
                    <div class="content-inner">
                      <span class="sub-title">{{ $banner->title }}</span>
                      <h2 class="title-md">{{ $banner->subtitle }}
                      </h2>
                      @if ($banner->banner_url && $banner->button_text)
                        <a href="{{ $banner->banner_url }}"
                          class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banner->button_text }}
                          <span class="icon"><i class="fal fa-arrow-right"></i></span>
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          @endif
        @endif

      </div>
      <div class="col-lg-7">
        <div class="section-title title-inline title-bottom-line mb-10">
          <h2 class="title title-sm mb-0">
            {{ $userSec->latest_product_section_title ?? ($keywords['Latest Item'] ?? __('Latest Item')) }}</h2>
          <div class="slider-arrow-inline style-2" id="product-list-slider-1-arrows">
          </div>
        </div>
        <style>
          .latest-product-card-responsive {
            display: flex;
            flex-direction: row;
            align-items: center;
            border-radius: 14px;
            border: 1px solid #eef0f4;
            padding: 10px;
            background: #ffffff;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
          }
          .latest-product-card-responsive:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            border-color: var(--color-primary, #4f46e5);
          }
          .latest-product-card-responsive .product-img-box {
            width: 105px;
            flex: 0 0 105px;
            height: 105px;
            border-radius: 10px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0;
          }
          .latest-product-card-responsive .product-img-box img {
            max-width: 90%;
            max-height: 90%;
            width: auto;
            height: auto;
            object-fit: contain;
          }
          .latest-product-card-responsive .product-details-box {
            padding-left: 10px;
            flex: 1;
            text-align: left;
            min-width: 0;
          }
          .latest-product-card-responsive .product-title-text {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.3;
            margin-top: 2px;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
          }
          .latest-product-card-responsive .product-title-text a {
            color: #1e293b;
            text-decoration: none;
          }
          .latest-product-card-responsive .product-title-text a:hover {
            color: var(--color-primary, #4f46e5);
          }

          @media (max-width: 767.98px) {
            .latest-product-card-responsive {
              flex-direction: column;
              align-items: center;
              text-align: center;
              padding: 10px 6px;
            }
            .latest-product-card-responsive .product-img-box {
              width: 100%;
              flex: 0 0 110px;
              height: 110px;
              margin-bottom: 8px;
            }
            .latest-product-card-responsive .product-details-box {
              padding-left: 0;
              width: 100%;
              text-align: center;
            }
            .latest-product-card-responsive .product-details-box .d-flex {
              justify-content: center;
            }
            .latest-product-card-responsive .btn-icon-group {
              justify-content: center !important;
              margin-top: 4px;
            }
          }
        </style>
        <div class="product-list mb-30">
          @php
            $latest_items = isset($latest_items) ? $latest_items->take(4) : collect();
          @endphp
          @if (count($latest_items) == 0)
            <h5 class="title text-center mt-30">
              {{ $userSec->category_section_title ?? ($keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND')) }}
            </h5>
          @else
            <div class="row g-2 g-md-3">
              @foreach ($latest_items as $item)
                @if (count(@$item->itemContents) > 0)
                  <div class="col-xl-6 col-lg-6 col-md-6 col-6 px-1 px-sm-2 mb-3">
                    <div class="latest-product-card-responsive">
                      <figure class="product-img-box">
                        <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->itemContents[0]->slug]) }}">
                          <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                            alt="{{ $item->itemContents[0]->title }}">
                        </a>
                      </figure>
                      <div class="product-details-box">
                        <a href="{{ route('front.user.shop', ['category' => $item->itemContents[0]->category->slug, getParam()]) }}">
                          <span class="product-category text-muted" style="font-size:11px; font-weight:500;">
                            {{ $item->itemContents[0]->category->name }}
                          </span>
                        </a>
                        <h4 class="product-title-text">
                          <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->itemContents[0]->slug]) }}">
                            {{ $item->itemContents[0]->title }}
                          </a>
                        </h4>

                        @if ($shop_settings->item_rating_system == 1)
                          <div class="d-flex align-items-center mb-1">
                            <div class="product-ratings rate text-xsm">
                              <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                            </div>
                            <span class="ratings-total ms-1" style="font-size:11px;">({{ reviewCount($item->id) }})</span>
                          </div>
                        @endif

                        <div class="product-price mt-1 mb-2" style="font-size:13px;">
                          @php
                            $flash_info = flashAmountStatus($item->id, $item->current_price);
                            $product_current_price = $flash_info['amount'];
                            $flash_status = $flash_info['status'];
                          @endphp
                          @if ($flash_status == true)
                            <span class="new-price font-weight-bold" style="color:var(--color-primary, #4f46e5); font-weight:700;">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
                            </span>
                            <span class="old-price text-decoration-line-through text-muted ms-1" style="font-size:11px;">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
                            </span>
                          @else
                            <span class="new-price font-weight-bold" style="color:var(--color-primary, #4f46e5); font-weight:700;">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
                            </span>
                            @if ($item->previous_price > 0)
                              <span class="old-price text-decoration-line-through text-muted ms-1" style="font-size:11px;">
                                {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->previous_price)) }}
                              </span>
                            @endif
                          @endif
                        </div>

                        <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex align-items-center">
                          @if ($shop_settings->catalog_mode != 1)
                            <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                              data-title="{{ $item->itemContents[0]->title }}"
                              data-current_price="{{ currency_converter($product_current_price) }}"
                              data-item_id="{{ $item->id }}" data-language_id="{{ $uLang }}"
                              data-totalVari="{{ check_variation($item->id) }}"
                              data-variations="{{ check_variation($item->id) > 0 ? 'yes' : null }}"
                              data-href="{{ route('front.user.add.cart', ['id' => $item->id, getParam()]) }}"
                              data-bs-toggle="tooltip" data-placement="top"
                              title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                class="far fa-shopping-cart"></i></a>
                          @endif

                          <a href="javascript:void(0)" class="btn btn-icon rounded-pill quick-view-link"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-slug="{{ $item->itemContents[0]->slug }}"
                            data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->itemContents[0]->slug, getParam()]) }}"
                            title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i>
                          </a>

                          <a class="btn btn-icon rounded-pill" data-bs-toggle="tooltip"
                            onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->id, getParam()]) }}')"
                            data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                              class="fal fa-random"></i></a>
                          @php
                            $customer_id = Auth::guard('customer')->check()
                                ? Auth::guard('customer')->user()->id
                                : null;
                            $checkWishList = $customer_id
                                ? checkWishList($item->id, $customer_id)
                                : false;
                          @endphp
                          <a href="javascript:void(0)"
                            class="btn btn-icon rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-item_id="{{ $item->id }}"
                            data-href="{{ route('front.user.add.wishlist', ['id' => $item->id, getParam()]) }}"
                            data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->id, getParam()]) }}"
                            title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                              class="fal fa-heart"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Product List End -->
