<!-- Product Area Start -->
<section class="products-section-v9 pb-100 overfollow-hidden lazy" data-aos="fade-up" data-aos-delay="100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-title text-center">
          <h2 class="title h2 mb-40">
            {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}</h2>
        </div>
      </div>
    </div>

    <div class="row gx-0 border justify-content-center">
      @for ($skeleton = 1; $skeleton <= 3; $skeleton++)
        <div class="col-6 col-sm-6 col-lg-4">
          <div class="product-default-9 {{ $skeleton == 2 ? 'active' : '' }}">
            <figure class="product-img skeleton skeleton-big-img">
            </figure>
            </a>
            <div class="product-details text-center">
              <!-- btn-icon-group -->
              <div class="btn-icon-group justify-content-center btn-inline text-center mb-3">
                <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon">
                </a>
                <button type="button" class="btn btn-icon skeleton skeleton-btn-icon"></button>
                <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon">
                </a>
                <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
              </div>
              <h5 class="product-title fw-medium lc-2 mt-1 mb-2 skeleton skeleton-title"></h5>

              <div class="product-price justify-content-center">
                <span class="new-price fw-semibold skeleton skeleton-price"></span>
                <span class="old-price fw-medium text-decoration-line-through skeleton skeleton-price"></span>
              </div>
            </div>
          </div>
        </div>
      @endfor
    </div>
  </div>
  </div>
</section>
<section class="products-section-v9 pb-100 overfollow-hidden actual-content" data-aos="fade-up" data-aos-delay="100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-title text-center">
          <h2 class="title h2 mb-40">
            {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}</h2>
        </div>
      </div>
    </div>

    <div class="row gx-0 border justify-content-center">
      @if (count($flash_items) > 0)
        @foreach ($flash_items as $item)
          <div class="col-6 col-sm-6 col-lg-4">
            <!-- product-default -->
            @php $i = $loop->iteration; @endphp

            <div class="product-default-9 {{ $i == 2 || ($i >= 5 && $i % 2 != 0) ? 'active' : '' }}">
              <figure class="product-img">
                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                  class="ratio ratio-1-1">
                  <img class="lazyload blur-up default-img" src="assets/images/placeholder.png"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}" alt="Product">
                </a>
              </figure>
              <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}" class="hover-img">
                <img class="lazyload blur-up" src="{{ asset('assets/user-front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}" alt="Product">
              </a>
              <div class="product-details text-center">
                <div class="btn-icon-group-area mb-10">
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
                    <div class="btn-icon-group btn-icon-group-2 justify-content-center btn-inline text-center">
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
                      <a href="javascript:void(0)"
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
                  </div>
                </div>

                <h5 class="product-title fw-medium lc-2 mt-1 mb-2">
                  <a
                    href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ $item->title }}</a>
                </h5>

                <div class="product-price justify-content-center">
                  @if ($item->flash == 1)
                    <span class="new-price fw-semibold">
                      {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price - $item->current_price * ($item->flash_amount / 100))) }}
                    </span>
                    <span class="old-price fw-medium text-decoration-line-through">
                      {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
                    </span>
                  @else
                    <span class="new-price fw-semibold">
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
        @endforeach
      @else
        <h5 class="title text-center mt-20 mb-20">
          {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
        </h5>
      @endif

    </div>
  </div>
</section>
<!-- Product Area End -->
