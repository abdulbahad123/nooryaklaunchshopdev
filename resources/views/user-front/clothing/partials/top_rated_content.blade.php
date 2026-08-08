<!-- Top Rated (Clothing) Start -->
<section class="clothing-top-rated">
  <div class="container">
    <div class="section-title-clothing" data-aos="fade-up" data-aos-delay="80">
      <span class="pre-title">{{ $keywords['Customer Favorites'] ?? __('Customer Favorites') }}</span>
      <h2>{{ $userSec->top_rated_section_title ?? ($keywords['Top Rated Picks'] ?? __('Top Rated Picks')) }}</h2>
      <p>{{ $userSec->top_rated_section_subtitle ?? ($keywords['Loved by thousands of happy customers'] ?? __('Loved by thousands of happy customers')) }}</p>
    </div>

    @if(count($top_rated) > 0)
      <div class="top-rated-slider-clothing" id="clothing-top-rated"
        data-slick='{"slidesToShow": 4, "dots": true, "responsive": [{"breakpoint": 1200, "settings": {"slidesToShow": 3}}, {"breakpoint": 992, "settings": {"slidesToShow": 2}}, {"breakpoint": 576, "settings": {"slidesToShow": 2}}]}'>

        @foreach($top_rated as $item)
          @php
            $itemContent = $item->itemContents->first();
            if(is_null($itemContent)) continue;

            $fi = flashAmountStatus($item->id, $item->current_price);
            $fs = $fi['status'];
            if($fs) {
              $tr_new = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($fi['amount']));
              $tr_old = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price));
            } else {
              $tr_new = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price));
              $tr_old = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->previous_price));
            }
          @endphp

          <div>
            <div class="product-default-10">
              <figure class="product-img">
                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $itemContent->slug]) }}">
                  <img class="lazyload blur-up default-img"
                    src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                    alt="{{ $itemContent->title }}">
                  <img class="lazyload blur-up hover-img"
                    src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                    alt="{{ $itemContent->title }}">
                </a>

                <div class="product-badge">
                  @if($fs)
                    <span class="badge-sale">{{ $keywords['SALE'] ?? __('SALE') }}</span>
                  @endif
                </div>
              </figure>

              <div class="product-details">
                <h3 class="product-title lc-1">
                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $itemContent->slug]) }}">{{ $itemContent->title }}</a>
                </h3>
                <div class="product-price">
                  <span class="new-price">{{ $tr_new }}</span>
                  @if($item->previous_price > 0)
                    <span class="old-price">{{ $tr_old }}</span>
                  @endif
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="btn-icon-group btn-inline">
                @if($shopSet->catalog_mode != 1)
                  <a href="javascript:void(0)"
                    class="btn btn-icon cart-link"
                    data-href="{{ route('front.user.add.cart', ['id' => $item->id, getParam()]) }}"
                    data-title="{{ $itemContent->title }}"
                    data-item_id="{{ $item->id }}"
                    data-current_price="{{ currency_converter($fi['status'] ? $fi['amount'] : $item->current_price) }}"
                    data-variations="{{ check_variation($item->id) > 0 ? 'yes' : 'no' }}"
                    data-totalvari="{{ check_variation($item->id) }}"
                    data-language_id="{{ $uLang }}"
                    title="{{ $keywords['Add to Cart'] ?? __('Add to Cart') }}">
                    <i class="far fa-shopping-cart"></i>
                  </a>
                @endif
                <a href="{{ route('customer.wishlist', getParam()) }}"
                  class="btn btn-icon add_to_wishlist"
                  data-item_id="{{ $item->id }}"
                  title="{{ $keywords['Wishlist'] ?? __('Wishlist') }}">
                  <i class="fal fa-heart"></i>
                </a>
                <a href="javascript:void(0)"
                  class="btn btn-icon quick-view"
                  data-item_id="{{ $item->id }}"
                  title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                  <i class="fal fa-eye"></i>
                </a>
                <a href="{{ route('front.user.compare', getParam()) }}"
                  class="btn btn-icon add_to_compare"
                  data-id="{{ $item->id }}"
                  title="{{ $keywords['Compare'] ?? __('Compare') }}">
                  <i class="fal fa-random"></i>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="text-center py-5">
        <i class="fal fa-star" style="font-size:48px;color:#ddd;margin-bottom:16px;display:block;"></i>
        <h5 style="color:#888;">{{ $keywords['No rated products found'] ?? __('No rated products found') }}</h5>
      </div>
    @endif
  </div>
</section>
<!-- Top Rated (Clothing) End -->
