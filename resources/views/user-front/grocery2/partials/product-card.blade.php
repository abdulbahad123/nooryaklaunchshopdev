@php
  $item_label = DB::table('labels')
      ->where('id', @$item->itemContents[0]->label_id)
      ->first();
  $label = $item_label->name ?? null;
  $color = $item_label->color ?? null;

  $flash_info = flashAmountStatus($item->id, $item->current_price);
  $product_current_price = $flash_info['amount'];
  $flash_status = $flash_info['status'];
@endphp

@if (!is_null(@$item->itemContents[0]->slug))
  <div class="col">
    <div class="g2-product-card">
      <!-- Badges -->
      @php
        $discountPct = 0;
        if (!empty($item->previous_price) && $item->previous_price > $item->current_price) {
            $discountPct = round((($item->previous_price - $item->current_price) / $item->previous_price) * 100);
        }
      @endphp
      @if ($label)
        <span class="g2-badge-tag label-custom" style="background-color: #{{ $color }}">{{ $label }}</span>
      @elseif ($discountPct > 0)
        <span class="g2-badge-tag badge-discount">{{ $discountPct > 15 ? 'Sale!' : $discountPct . '%' }}</span>
      @elseif ($item->flash == 1)
        <span class="g2-badge-tag badge-discount">{{ $item->flash_amount }}%</span>
      @else
        <span class="g2-badge-tag badge-discount">Sale!</span>
      @endif

      <!-- Product Image -->
      <div class="g2-product-image">
        @php
          $imgSrc1 = str_starts_with($item->thumbnail, 'http') ? $item->thumbnail : asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail);
          $secondImg = $item->sliders->first()->image ?? null;
          if ($secondImg) {
              $imgSrc2 = str_starts_with($secondImg, 'http') ? $secondImg : asset('assets/front/img/user/items/thumbnail/' . $secondImg);
          } else {
              $imgSrc2 = null;
          }
        @endphp
        <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->itemContents[0]->slug]) }}" class="g2-img-link">
          <img src="{{ $imgSrc1 }}" alt="{{ $item->itemContents[0]->title }}" class="primary-img">
          @if (!empty($imgSrc2))
            <img src="{{ $imgSrc2 }}" alt="{{ $item->itemContents[0]->title }}" class="secondary-img">
          @endif
        </a>
        
        <!-- Hover Quick Action Overlay Icons -->
        <div class="g2-card-actions">
          <!-- Wishlist -->
          @php
            $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
            $checkWishList = $customer_id ? checkWishList($item->id, $customer_id) : false;
          @endphp
          <a href="javascript:void(0)" 
             class="g2-action-btn {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
             data-item_id="{{ $item->id }}"
             data-href="{{ route('front.user.add.wishlist', ['id' => $item->id, getParam()]) }}"
             data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->id, getParam()]) }}"
             title="Wishlist">
            <i class="fal fa-heart"></i>
          </a>

          <!-- Compare -->
          <a href="javascript:void(0)" class="g2-action-btn"
             onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->id, getParam()]) }}')"
             title="Compare">
            <i class="fal fa-random"></i>
          </a>

          <!-- Quick view -->
          <a href="javascript:void(0)" class="g2-action-btn quick-view-link"
             data-slug="{{ $item->itemContents[0]->slug }}"
             data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->itemContents[0]->slug, getParam()]) }}"
             title="Quick View">
            <i class="fal fa-eye"></i>
          </a>
        </div>

        <!-- Slider Dots on hover -->
        <div class="g2-img-dots">
          <span></span><span class="active"></span><span></span>
        </div>
      </div>

      <!-- Card Content -->
      <div class="g2-product-info">
        <span class="g2-prod-category">{{ @$item->itemContents[0]->category->name }}</span>
        <h3 class="g2-prod-title">
          <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->itemContents[0]->slug]) }}">
            {{ $item->itemContents[0]->title }}
          </a>
        </h3>

        <!-- Variation Pills -->
        @if(!empty($item->variations) && count($item->variations) > 0)
          <div class="g2-variation-pills">
            @foreach($item->variations->take(4) as $var)
              <span class="g2-pill">{{ $var->name ?? $var->variant_name }}</span>
            @endforeach
          </div>
        @endif

        <!-- Rating & Stock Tag -->
        <div class="g2-rating-stock-row">
          <div class="g2-rating-stars">
            <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
            <span class="rating-total">({{ reviewCount($item->id) }})</span>
          </div>
          <span class="g2-stock-badge">{{ __('In Stock') }}</span>
        </div>

        <!-- Price -->
        <div class="g2-price-row">
          @if ($flash_status == true)
            <span class="g2-new-price">
              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
            </span>
            <span class="g2-old-price">
              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
            </span>
          @else
            <span class="g2-new-price">
              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->current_price)) }}
            </span>
            @if(!empty($item->previous_price))
              <span class="g2-old-price">
                {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->previous_price)) }}
              </span>
            @endif
          @endif
        </div>

        <!-- Add to Cart Footer Actions -->
        @if ($shop_settings->catalog_mode != 1)
          <div class="g2-cart-actions-footer">
            <div class="g2-qty-selector">
              <button class="g2-qty-btn qty-minus">-</button>
              <input type="text" class="g2-qty-input" value="1" readonly>
              <button class="g2-qty-btn qty-plus">+</button>
            </div>
            
            <button class="btn g2-add-to-cart-btn cart-link"
                    data-title="{{ $item->itemContents[0]->title }}"
                    data-current_price="{{ currency_converter($product_current_price) }}"
                    data-item_id="{{ $item->id }}" 
                    data-language_id="{{ $uLang }}"
                    data-totalVari="{{ check_variation($item->id) }}"
                    data-variations="{{ check_variation($item->id) > 0 ? 'yes' : null }}"
                    data-href="{{ route('front.user.add.cart', ['id' => $item->id, getParam()]) }}">
              <i class="fal fa-shopping-cart"></i> Buy Now
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>
@endif
