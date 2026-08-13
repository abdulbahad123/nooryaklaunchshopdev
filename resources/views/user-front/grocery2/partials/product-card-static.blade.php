<div class="col">
  <div class="g2-product-card">
    <!-- Badges -->
    @if ($badge)
      <span class="g2-badge-tag {{ $badge_class }}">{{ $badge }}</span>
    @endif

    <!-- Product Image -->
    <div class="g2-product-image">
      <a href="{{ route('front.user.shop', getParam()) }}">
        <img src="{{ $img }}" alt="{{ $title }}">
      </a>
      
      <!-- Hover Quick Action Overlay Icons -->
      <div class="g2-card-actions">
        <a href="javascript:void(0)" class="g2-action-btn" title="Quick View"><i class="fal fa-eye"></i></a>
        <a href="javascript:void(0)" class="g2-action-btn" title="Compare"><i class="fal fa-random"></i></a>
        <a href="javascript:void(0)" class="g2-action-btn" title="Wishlist"><i class="fal fa-heart"></i></a>
      </div>
    </div>

    <!-- Card Content -->
    <div class="g2-product-info">
      <span class="g2-prod-category">{{ $category }}</span>
      <h3 class="g2-prod-title">
        <a href="{{ route('front.user.shop', getParam()) }}">
          {{ $title }}
        </a>
      </h3>

      <!-- Rating -->
      <div class="g2-rating-stars">
        <div class="g2-stars-outer">
          <div class="g2-stars-inner" style="width: 80%;"></div>
        </div>
        <span class="rating-total">(12)</span>
      </div>

      <!-- Price -->
      <div class="g2-price-row">
        <span class="g2-new-price">{{ explode(' ', $price)[0] }}</span>
        @if(count(explode(' ', $price)) > 1)
          <span class="g2-old-price">{{ explode(' ', $price)[1] }}</span>
        @endif
      </div>

      <!-- Qty and Add to Cart -->
      <div class="g2-cart-actions-footer">
        <div class="g2-qty-selector">
          <button class="g2-qty-btn qty-minus">-</button>
          <input type="text" class="g2-qty-input" value="1" readonly>
          <button class="g2-qty-btn qty-plus">+</button>
        </div>
        
        <button class="btn g2-add-to-cart-btn" onclick="location.href='{{ route('front.user.shop', getParam()) }}'">
          <i class="fal fa-shopping-cart"></i> Buy Now
        </button>
      </div>
    </div>
  </div>
</div>
