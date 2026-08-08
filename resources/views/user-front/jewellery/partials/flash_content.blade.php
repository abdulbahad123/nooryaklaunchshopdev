<!-- Product Area Start -->
<section class="products-section-v8 pb-100 overfollow-hidden lazy">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-title text-center" data-aos="fade-up" data-aos-delay="100">
          <h2 class="title h2 fw-bold mb-40">
            {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}</h2>
        </div>
      </div>
    </div>
    <div class="row gx-xl-5 justify-content-center">
      @for ($skeleton = 1; $skeleton <= 3; $skeleton++)
        <div class="col-lg-4 col-md-6 col-6" data-aos="fade-up" data-aos-delay="100">
          <!-- product-default -->
          <div class="mb-30 product-default-8">
            <figure class="product-img skeleton skeleton-big-img"></figure>
            <div class="product-details">
              <!-- btn-icon-group -->
              <div class="btn-icon-group justify-content-center btn-inline text-center mb-3">
                <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                <button type="button" class="btn btn-icon skeleton skeleton-btn-icon"></button>

                <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>

                <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
              </div>
              <span class="product-category text-sm skeleton skeleton-title"></span>
              <h3 class="product-title lc-2 mt-1 mb-2 skeleton skeleton-title"></h3>
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
</section>


<section class="products-section-v8 flash-sale-section pb-100 overfollow-hidden actual-content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-title text-center" data-aos="fade-up" data-aos-delay="100">
          <h2 class="title h2 fw-bold mb-40">
            {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}</h2>
        </div>
      </div>
    </div>
    @if (count($flash_items) == 0)
      <h5 class="title text-center mb-20">
        {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
      </h5>
    @else
      <div class="row gx-xl-5 justify-content-center">
        @foreach ($flash_items as $item)
          @php
            $i = $loop->iteration;
          @endphp
          <div class="col-lg-4 col-md-6 col-sm-6 col-6" data-aos="fade-up" data-aos-delay="100">
            <!-- product-default -->

            @if ($i == 2 || ($i >= 5 && $i % 2 != 0))
              <div class="highlight-card  mb-30">
                <div class="img-outside">
                  <img src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}" alt="">
                </div>

                <div class="highlight-card-area">
                  <div class="highlight-card-details">
                    <div class="btn-icon-group-area mb-10 text-center">
                      <!-- product-countdown -->
                      <div class="product-countdown justify-content-center text-center hover-hide"
                        data-start_date="{{ $item->start_date }}" data-end_time="{{ $item->end_time }}"
                        data-end_date="{{ $item->end_date }}" data-item_id="{{ $item->item_id }}">
                        <div id="" class="count rounded-pill days">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Days'] ?? __('Days') }}</span>
                        </div>
                        <div id="" class="count rounded-pill hours">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
                        </div>
                        <div id="" class="count rounded-pill minutes">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
                        </div>
                        <div id="" class="count rounded-pill seconds">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
                        </div>
                      </div>
                      <!-- btn-icon-group -->
                      <div class="hover-show">
                        <div class="btn-icon-group justify-content-center text-center">
                          <a href="javascript:void(0)"
                            onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                            class="btn btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i>
                          </a>
    
                          <button type="button" class="btn btn-icon quick-view-link" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                            data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                            title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                            <i class="fal fa-eye"></i>
                          </button>
    
                          @php
                            $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                            $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                          @endphp
    
                          <a href="javascript:void(0)"
                            class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-url="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                            data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                            title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                              class="fal fa-heart"></i>
                          </a>
    
                          <a href="javascript:void(0)"
                            onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                            class="btn btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-shopping-bag"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                    
                    <span class="product-category text-sm">
                      <a
                        href="{{ route('front.user.shop', ['category' => $item->category_slug, getParam()]) }}">{{ $item->category }}</a>
                    </span>
                    <h3 class="product-title lc-1 mt-1 mb-2">
                      <a
                        href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ $item->title }}</a>
                    </h3>
                    <!-- Rateing -->
                    @if ($shop_settings->item_rating_system == 1)
                      <!-- Rateing -->
                      <div class="d-flex align-items-center mb-10">
                        <div class="product-ratings rate text-xsm">
                          <div class="rating" style="width:{{ $item->rating * 20 }}"></div>
                        </div>
                        <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                      </div>
                    @endif
                    <div class="product-price">
                      @if ($item->flash == 1)
                        <span class="new-price fw-bold">
                          {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price - $item->current_price * ($item->flash_amount / 100))) }}
                        </span>
                        <span class="old-price fw-medium text-decoration-line-through">
                          {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
                        </span>
                      @else
                        <span class="new-price fw-bold">
                          {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
                        </span>
                        <span class="old-price fw-medium text-decoration-line-through">
                          {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->previous_price)) }}
                        </span>
                      @endif
                    </div>
                  </div>

                </div>
              </div>
            @else
              <div class="mb-30 product-default-8">
                <figure class="product-img">
                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                    class="ratio ratio-1-1">
                    <img class="lazyload blur-up default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                      alt="Product">
                    <img class="lazyload blur-up hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                      alt="Product">
                  </a>
                </figure>
                <div class="product-details">
                  <div class="btn-icon-group-area mb-10 text-center">
                    <!-- product-countdown -->
                    <div class="product-countdown justify-content-center text-center hover-hide"
                      data-start_date="{{ $item->start_date }}" data-end_time="{{ $item->end_time }}"
                      data-end_date="{{ $item->end_date }}" data-item_id="{{ $item->item_id }}">
                      <div id="" class="count rounded-pill days">
                        <span class="count-value_{{ $item->item_id }}"></span>
                        <span class="count-period">{{ $keywords['Days'] ?? __('Days') }}</span>
                      </div>
                      <div id="" class="count rounded-pill hours">
                        <span class="count-value_{{ $item->item_id }}"></span>
                        <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
                      </div>
                      <div id="" class="count rounded-pill minutes">
                        <span class="count-value_{{ $item->item_id }}"></span>
                        <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
                      </div>
                      <div id="" class="count rounded-pill seconds">
                        <span class="count-value_{{ $item->item_id }}"></span>
                        <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
                      </div>
                    </div>

                    <!-- btn-icon-group -->
                    <div class="hover-show">
                      <div class="btn-icon-group justify-content-center btn-inline text-center">
                        <a href="javascript:void(0)"
                          onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                          class="btn btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                          title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i>
                        </a>
    
                        <button type="button" class="btn btn-icon quick-view-link" data-bs-toggle="tooltip"
                          data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                          data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                          title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                          <i class="fal fa-eye"></i>
                        </button>
    
                        @php
                          $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                          $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                        @endphp
    
                        <a href="javascript:void(0)"
                          class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                          data-bs-toggle="tooltip" data-bs-placement="top"
                          data-url="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                            class="fal fa-heart"></i>
                        </a>
    
                        <a href="javascript:void(0)"
                          onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                          class="btn btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                          title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-shopping-bag"></i>
                        </a>
                      </div>
                    </div>
                  </div>

                  <span class="product-category text-sm">
                    <a
                      href="{{ route('front.user.shop', ['category' => $item->category_slug, getParam()]) }}">{{ $item->category }}</a>
                  </span>
                  <h3 class="product-title lc-1 mt-1 mb-2">
                    <a
                      href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ $item->title }}</a>
                  </h3>
                  @if ($shop_settings->item_rating_system == 1)
                    <!-- Rateing -->
                    <div class="d-flex align-items-center mb-10">
                      <div class="product-ratings rate text-xsm">
                        <div class="rating" style="width:{{ $item->rating * 20 }}"></div>
                      </div>
                      <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                    </div>
                  @endif
                  <div class="product-price">
                    @if ($item->flash == 1)
                      <span class="new-price fw-bold">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price - $item->current_price * ($item->flash_amount / 100))) }}
                      </span>
                      <span class="old-price fw-medium text-decoration-line-through">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
                      </span>
                    @else
                      <span class="new-price fw-bold">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
                      </span>
                      <span class="old-price fw-medium text-decoration-line-through">
                        {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->previous_price)) }}
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
<!-- Product Area End -->
