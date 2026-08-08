<!-- Featured Section Start -->
<section class="featured-section-v9-style-2 pb-100 lazy">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-4 col-xl-5" data-aos="fade-left" data-aos-delay="100">
        @if ($ubs->middle_right_banner_section == 1)
          @if ($banners)
            @php
              $rightBanners = $banners->where('position', 'left')->values();
              $banner = $rightBanners[$i] ?? null;
            @endphp
            @if ($banner)
              <figure class="featured-img radius-lg overfollow-hidden mb-30">
                <a href="{{ $banner->banner_url }}" class="ratio ratio-1-1 radius-lg">
                  <img class="lazyload" data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}"
                    alt="featured">
                </a>
              </figure>
            @endif
          @endif
        @endif
      </div>
      <div class="col-lg-8 col-xl-7" data-aos="fade-right" data-aos-delay="100">
        <h2 class="h2 mb-30 text-center">{{ $tabs[$i]->name }}</h2>

        <div id="product-inline-slider-2{{ $i }}" class="product-inline-slider"
          data-slick='{"dots": true, "slidesToShow": 2}'>
          @for ($skeleton = 0; $skeleton <= 1; $skeleton++)
            <div class="slider-item">
              <div class="d-flex flex-column gap-3">
                @for ($skel = 1; $skel <= 2; $skel++)
                  <div class="product-default-9-inline">
                    <figure class="product-img-2 radius-md">
                      <a href="#" class="ratio ratio-1-1 skeleton skeleton-big-img"></a>
                    </figure>
                    <div class="product-details">
                      <span class="product-category skeleton skeleton-price"></span>
                      <h5 class="product-title lc-1 fw-medium mb-2 skeleton skeleton-title"></h5>
                      <!-- Rateing -->
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
                        <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                        <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                      </div>
                    </div>
                  </div>
                @endfor
              </div>
            </div>
          @endfor
        </div>
      </div>
    </div>
  </div>
</section>
<section class="featured-section-v9-style-2 pb-100 actual-content">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-4 col-xl-5" data-aos="fade-left" data-aos-delay="100">
        @if ($ubs->middle_right_banner_section == 1)
          @if ($banners)
            @php
              $rightBanners = $banners->where('position', 'left')->values();
              $banner = $rightBanners[$i] ?? null;
            @endphp
            @if ($banner)
              <figure class="featured-img radius-lg overfollow-hidden mb-30">
                <a href="{{ $banner->banner_url }}" class="ratio ratio-1-1 radius-lg">
                  <img class="lazyload" data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}"
                    alt="featured">
                </a>
              </figure>
            @endif
          @endif
        @endif
      </div>
      <div class="col-lg-8 col-xl-7" data-aos="fade-right" data-aos-delay="100">
        <h2 class="h2 mb-30 text-center">{{ $tabs[$i]->name }}</h2>

        <div id="product-inline-slider-3{{ $i }}" class="product-inline-slider"
          data-slick='{"dots": true, "slidesToShow": 2}'>
          <!-- product-inline -->
          @php
            $products = json_decode($tabs[$i]->products, true);
          @endphp
          @if (!is_null($products))
            @for ($k = 0; $k < count($products); $k += 2)
              @if ($k < count($products) - 1)
                @php
                  $product_details1 = \App\Models\User\UserItem::where('id', $products[$k])
                      ->with([
                          'itemContents' => function ($q) use ($uLang) {
                              $q->where('language_id', '=', $uLang);
                          },
                          'sliders',
                      ])
                      ->first();
                  $product_details2 = \App\Models\User\UserItem::where('id', $products[$k + 1])
                      ->with([
                          'itemContents' => function ($q) use ($uLang) {
                              $q->where('language_id', '=', $uLang);
                          },
                          'sliders',
                      ])
                      ->first();
                @endphp
                <div class="slider-item">
                  <div class="d-flex flex-column gap-3">
                    @if (isset($product_details1) && count($product_details1->itemContents) > 0)
                      <div class="product-default-9-inline">
                        <figure class="product-img-2 radius-md">
                          <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => @$product_details1->itemContents[0]->slug]) }}"
                            class="ratio ratio-1-1">
                            <img class="lazyload blur-up default-img"
                              src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details1->thumbnail) }}"
                              alt="Product">
                            <img class="lazyload blur-up hover-img"
                              src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details1->thumbnail) }}"
                              alt="Product">
                          </a>
                        </figure>
                        <div class="product-details">
                          <span class="product-category">
                            <a
                              href="{{ route('front.user.shop', ['category' => @$product_details1->itemContents[0]->category->slug, getParam()]) }}">{{ @$product_details1->itemContents[0]->category->name }}</a>
                          </span>
                          @if (count($product_details1->itemContents) > 0)
                            <h5 class="product-title lc-1 fw-medium mb-2">
                              <a
                                href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details1->itemContents[0]->slug]) }}">
                                {{ $product_details1->itemContents[0]->title }}</a>
                            </h5>
                          @endif
                          <!-- Rateing -->
                          @if ($shop_settings->item_rating_system == 1)
                            <div class="d-flex justify-content-start align-items-center mb-10">
                              <div class="product-ratings rate text-xsm">
                                <div class="rating" style="width:{{ $product_details1->rating * 20 }}"></div>
                              </div>
                              <span class="ratings-total">({{ reviewCount($product_details1->id) }})</span>
                            </div>
                          @endif
                          @php
                            $flash_info = flashAmountStatus($product_details1->id, $product_details1->current_price);
                            $product_current_price = $flash_info['amount'];
                            $flash_status = $flash_info['status'];
                          @endphp
                          <!-- product-price -->
                          <div class="product-price mt-10 mb-3">
                            @if ($flash_status == true)
                              <span class="new-price fw-semibold">
                                {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
                              </span>
                              <span class="old-price fw-medium text-decoration-line-through">
                                {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details1->current_price)) }}</span>
                            @else
                              <span class="new-price fw-semibold">
                                {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details1->current_price)) }}
                              </span>
                              <span
                                class="old-price fw-medium text-decoration-line-through">{{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details1->previous_price)) }}</span>
                            @endif
                          </div>

                          <!-- btn-icon-group -->
                          <div class="btn-icon-group btn-inline">
                            <a href="javascript:void(0)" class="btn btn-icon" data-bs-toggle="tooltip"
                              onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details1->id, getParam()]) }}')"
                              data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}">
                              <i class="fal fa-random"></i>
                            </a>
                            <button type="button" class="btn btn-icon quick-view-link" data-bs-toggle="tooltip"
                              data-bs-placement="top" data-slug="{{ $product_details1->itemContents[0]->slug }}"
                              data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product_details1->itemContents[0]->slug, getParam()]) }}"
                              title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                              <i class="fal fa-eye"></i>
                            </button>
                            @php
                              $customer_id = Auth::guard('customer')->check()
                                  ? Auth::guard('customer')->user()->id
                                  : null;
                              $checkWishList = $customer_id
                                  ? checkWishList($product_details1->id, $customer_id)
                                  : false;
                            @endphp
                            <a href="javascript:void(0)"
                              class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"data-bs-toggle="tooltip"
                              data-bs-placement="top" data-item_id="{{ $product_details1->id }}"
                              data-href="{{ route('front.user.add.wishlist', ['id' => $product_details1->id, getParam()]) }}"
                              data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details1->id, getParam()]) }}"
                              title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}">
                              <i class="fal fa-heart"></i>
                            </a>
                            @if ($shop_settings->catalog_mode != 1)
                              <a href="javascript:void(0)" class="btn btn-icon cart-link"
                                data-title="{{ $product_details1->itemContents[0]->title }}"
                                data-current_price="{{ currency_converter($product_current_price) }}"
                                data-item_id="{{ $product_details1->id }}" data-language_id="{{ $uLang }}"
                                data-totalVari="{{ check_variation($product_details1->id) }}"
                                data-variations="{{ check_variation($product_details1->id) > 0 ? 'yes' : null }}"
                                data-href="{{ route('front.user.add.cart', ['id' => $product_details1->id, getParam()]) }}"
                                data-bs-toggle="tooltip" data-placement="top"
                                title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                  class="fal fa-shopping-bag"></i>
                              </a>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endif

                    @if (isset($product_details2) && count($product_details2->itemContents) > 0)
                      <div class="product-default-9-inline">
                        <figure class="product-img-2 radius-md">
                          <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => @$product_details2->itemContents[0]->slug]) }}"
                            class="ratio ratio-1-1">
                            <img class="lazyload blur-up default-img"
                              src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details2->thumbnail) }}"
                              alt="Product">
                            <img class="lazyload blur-up hover-img"
                              src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details2->thumbnail) }}"
                              alt="Product">
                          </a>
                        </figure>
                        <div class="product-details">
                          <span class="product-category">
                            <a
                              href="{{ route('front.user.shop', ['category' => @$product_details2->itemContents[0]->category->slug, getParam()]) }}">{{ @$product_details2->itemContents[0]->category->name }}</a>
                          </span>
                          @if (count($product_details2->itemContents) > 0)
                            <h5 class="product-title lc-1 fw-medium mb-2">
                              <a
                                href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details2->itemContents[0]->slug]) }}">
                                {{ $product_details2->itemContents[0]->title }}</a>
                            </h5>
                          @endif
                          <!-- Rateing -->
                          @if ($shop_settings->item_rating_system == 1)
                            <div class="d-flex justify-content-start align-items-center mb-10">
                              <div class="product-ratings rate text-xsm">
                                <div class="rating" style="width:{{ $product_details2->rating * 20 }}"></div>
                              </div>
                              <span class="ratings-total">({{ reviewCount($product_details2->id) }})</span>
                            </div>
                          @endif
                          @php
                            $flash_info = flashAmountStatus($product_details2->id, $product_details2->current_price);
                            $product_current_price = $flash_info['amount'];
                            $flash_status = $flash_info['status'];
                          @endphp
                          <!-- product-price -->
                          <div class="product-price mt-10 mb-3">
                            @if ($flash_status == true)
                              <span class="new-price fw-semibold">
                                {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
                              </span>
                              <span
                                class="old-price fw-medium text-decoration-line-through">{{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details2->current_price)) }}</span>
                            @else
                              <span class="new-price fw-semibold">
                                {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details2->current_price)) }}
                              </span>
                              <span
                                class="old-price fw-medium text-decoration-line-through">{{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details2->previous_price)) }}</span>
                            @endif
                          </div>

                          <!-- btn-icon-group -->
                          <div class="btn-icon-group btn-inline">
                            <a href="javascript:void(0)" class="btn btn-icon" data-bs-toggle="tooltip"
                              onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details2->id, getParam()]) }}')"
                              data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}">
                              <i class="fal fa-random"></i>
                            </a>
                            <button type="button" class="btn btn-icon quick-view-link" data-bs-toggle="tooltip"
                              data-bs-placement="top" data-slug="{{ $product_details2->itemContents[0]->slug }}"
                              data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product_details2->itemContents[0]->slug, getParam()]) }}"
                              title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                              <i class="fal fa-eye"></i>
                            </button>
                            @php
                              $customer_id = Auth::guard('customer')->check()
                                  ? Auth::guard('customer')->user()->id
                                  : null;
                              $checkWishList = $customer_id
                                  ? checkWishList($product_details2->id, $customer_id)
                                  : false;
                            @endphp
                            <a href="javascript:void(0)"
                              class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"data-bs-toggle="tooltip"
                              data-bs-placement="top" data-item_id="{{ $product_details2->id }}"
                              data-href="{{ route('front.user.add.wishlist', ['id' => $product_details2->id, getParam()]) }}"
                              data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details2->id, getParam()]) }}"
                              title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}">
                              <i class="fal fa-heart"></i>
                            </a>
                            @if ($shop_settings->catalog_mode != 1)
                              <a href="javascript:void(0)" class="btn btn-icon cart-link"
                                data-title="{{ $product_details2->itemContents[0]->title }}"
                                data-current_price="{{ currency_converter($product_current_price) }}"
                                data-item_id="{{ $product_details2->id }}" data-language_id="{{ $uLang }}"
                                data-totalVari="{{ check_variation($product_details2->id) }}"
                                data-variations="{{ check_variation($product_details2->id) > 0 ? 'yes' : null }}"
                                data-href="{{ route('front.user.add.cart', ['id' => $product_details2->id, getParam()]) }}"
                                data-bs-toggle="tooltip" data-placement="top"
                                title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                  class="fal fa-shopping-bag"></i>
                              </a>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
              @endif
            @endfor
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Featured Section End -->
