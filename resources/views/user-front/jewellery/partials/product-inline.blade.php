<!-- product-inline -->
  <div class="product-default-8-inline">
    <figure class="product-img">
      <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => @$product->itemContents[0]->slug]) }}"
        class="ratio ratio-1-1">
        <img class="lazyload blur-up default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product->thumbnail) }}" alt="Product">
        <img class="lazyload blur-up hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product->thumbnail) }}"
          alt="Product">
      </a>
    </figure>
    <div class="product-details">
      <span class="product-category">
        <a
          href="{{ route('front.user.shop', ['category' => @$product->itemContents[0]->category->slug, getParam()]) }}">{{ @$product->itemContents[0]->category->name }}</a>
      </span>
      @if (count($product->itemContents) > 0)
        <h5 class="product-title lc-2 fw-medium mb-2">
          <a
            href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product->itemContents[0]->slug]) }}">{{ $product->itemContents[0]->title }}</a>
        </h5>
      @endif
      @if ($shop_settings->item_rating_system == 1)
        <!-- Rateing -->
        <div class="d-flex justify-content-start align-items-center mb-10">
          <div class="product-ratings rate text-xsm">
            <div class="rating" style="width:{{ $product->rating * 20 }}%"></div>
          </div>
          <span class="ratings-total">({{ reviewCount($product->id) }})</span>
        </div>
      @endif
      <!-- product-price -->
      @php
        $flash_info = flashAmountStatus($product->id, $product->current_price);
        $product_current_price = $flash_info['amount'];
        $flash_status = $flash_info['status'];
      @endphp
      <div class="product-price mt-10 mb-3">
        @if ($flash_status == true)
          <span class="new-price fw-semibold">
            {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
          </span>
          <span class="old-price fw-medium text-decoration-line-through">
            {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product->current_price)) }}
          </span>
        @else
          <span class="new-price fw-semibold">
            {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product->current_price)) }}
          </span>
          <span class="old-price fw-medium text-decoration-line-through">
            {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product->previous_price)) }}
          </span>
        @endif
      </div>

      <!-- btn-icon-group -->
      <div class="btn-icon-group btn-inline">
        <a href="javascript:void(0)" class="btn btn-icon" data-bs-toggle="tooltip"
          onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product->id, getParam()]) }}')"
          data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}">
          <i class="fal fa-random"></i>
        </a>

        <button type="button" class="btn btn-icon quick-view-link" data-bs-toggle="tooltip" data-bs-placement="top"
          data-slug="{{ $product->itemContents[0]->slug }}"
          data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product->itemContents[0]->slug, getParam()]) }}"
          title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
          <i class="fal fa-eye"></i>
        </button>

        @php
          $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
          $checkWishList = $customer_id ? checkWishList($product->id, $customer_id) : false;
        @endphp
        <a href="javascript:void(0)"
          class="btn btn-icon  {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"data-bs-toggle="tooltip"
          data-bs-placement="top" data-item_id="{{ $product->id }}"
          data-href="{{ route('front.user.add.wishlist', ['id' => $product->id, getParam()]) }}"
          data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product->id, getParam()]) }}"
          title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}">
          <i class="fal fa-heart"></i>
        </a>
        @if ($shop_settings->catalog_mode != 1)
          <a href="javascript:void(0)" class="btn btn-icon cart-link"
            data-title="{{ $product->itemContents[0]->title }}"
            data-current_price="{{ currency_converter($product_current_price) }}"
            data-item_id="{{ $product->id }}" data-language_id="{{ $uLang }}"
            data-totalVari="{{ check_variation($product->id) }}"
            data-variations="{{ check_variation($product->id) > 0 ? 'yes' : null }}"
            data-href="{{ route('front.user.add.cart', ['id' => $product->id, getParam()]) }}"
            data-bs-toggle="tooltip" data-placement="top" title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}">
            <i class="fal fa-shopping-bag"></i>
          </a>
        @endif
      </div>
    </div>
  </div>
