<!-- Flash Sale (Clothing) Start -->
<section class="flash-sale-clothing">
  <div class="container">

    <!-- Premium Mockup Countdown Banner -->
    <div class="clothing-countdown-banner" style="background:#f5efe6;padding:50px 60px;margin-bottom:50px;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:30px;border-radius:0;">
      <!-- Leaf shadow background decoration -->
      <div class="leaf-deco" style="position:absolute;right:0;top:0;bottom:0;width:40%;background-image:url('https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?q=80&w=600&auto=format&fit=crop');background-size:cover;background-position:center;opacity:0.07;pointer-events:none;"></div>
      
      <div style="flex:1;min-width:300px;position:relative;z-index:2;">
        <span style="font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:#888;display:block;margin-bottom:12px;">SPECIAL OFFER</span>
        <h2 style="font-family:var(--clothing-heading-font);font-size:clamp(32px, 4vw, 54px);color:#000000;line-height:1.1;margin-bottom:28px;font-weight:300;">
          {!! $userSec->flash_section_title ?? 'UP TO 50% OFF<br>On Summer Collection' !!}
        </h2>
        <a href="{{ route('front.user.shop', getParam()) }}" class="btn" style="background:#0d0d0d;color:#fff;border-radius:0;padding:12px 30px;font-size:11px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;">
          {{ $keywords['Shop Now'] ?? __('Shop Now') }}
        </a>
      </div>

      <!-- Countdown Counters -->
      @if(count($flash_items) > 0)
        @php
          $endDateTime = $flash_items->first()->end_date . ' ' . $flash_items->first()->end_time;
        @endphp
        <div class="clothing-countdown-wrapper" style="position:relative;z-index:2;">
          <div class="clothing-countdown" id="clothing-flash-countdown" data-end="{{ $endDateTime }}" style="display:flex;align-items:center;gap:20px;">
            <div class="time-block" style="text-align:center;">
              <span class="num" id="cf-days" style="font-family:var(--clothing-body-font);font-size:42px;font-weight:400;color:#ffffff;display:block;line-height:1;">00</span>
              <span class="unit" style="font-size:9px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,0.7);margin-top:8px;display:block;">DAYS</span>
            </div>
            <span style="font-size:32px;font-weight:300;color:#ccc;vertical-align:middle;margin-top:-15px;">|</span>
            <div class="time-block" style="text-align:center;">
              <span class="num" id="cf-hours" style="font-family:var(--clothing-body-font);font-size:42px;font-weight:400;color:#ffffff;display:block;line-height:1;">00</span>
              <span class="unit" style="font-size:9px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,0.7);margin-top:8px;display:block;">HOURS</span>
            </div>
            <span style="font-size:32px;font-weight:300;color:#ccc;vertical-align:middle;margin-top:-15px;">|</span>
            <div class="time-block" style="text-align:center;">
              <span class="num" id="cf-minutes" style="font-family:var(--clothing-body-font);font-size:42px;font-weight:400;color:#ffffff;display:block;line-height:1;">00</span>
              <span class="unit" style="font-size:9px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,0.7);margin-top:8px;display:block;">MINS</span>
            </div>
            <span style="font-size:32px;font-weight:300;color:#ccc;vertical-align:middle;margin-top:-15px;">|</span>
            <div class="time-block" style="text-align:center;">
              <span class="num" id="cf-seconds" style="font-family:var(--clothing-body-font);font-size:42px;font-weight:400;color:#ffffff;display:block;line-height:1;">00</span>
              <span class="unit" style="font-size:9px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,0.7);margin-top:8px;display:block;">SECS</span>
            </div>
          </div>
        </div>
      @endif
    </div>

    <!-- Products Grid -->
    @if(count($flash_items) > 0)
      <div class="products-row-clothing" id="clothing-flash-products">
        @foreach($flash_items as $flash_item)
          @php
            $flash_info   = flashAmountStatus($flash_item->item_id, $flash_item->current_price);
            $flash_status = $flash_info['status'];

            if($flash_status == true) {
              $new_price = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($flash_info['amount']));
              $old_price = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($flash_item->current_price));
            } else {
              $new_price = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($flash_item->current_price));
              $old_price = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($flash_item->previous_price));
            }
          @endphp

          <div class="product-default-10">
            <figure class="product-img">
              <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $flash_item->slug]) }}">
                <img class="lazyload blur-up default-img"
                  src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $flash_item->thumbnail) }}"
                  alt="{{ $flash_item->title }}">
                <img class="lazyload blur-up hover-img"
                  src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $flash_item->thumbnail) }}"
                  alt="{{ $flash_item->title }}">
              </a>

              <!-- Badges -->
              <div class="product-badge">
                <span class="badge-sale">{{ $keywords['SALE'] ?? __('SALE') }}</span>
              </div>
            </figure>

            <div class="product-details">
              <h3 class="product-title lc-1">
                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $flash_item->slug]) }}">
                  {{ $flash_item->title }}
                </a>
              </h3>
              <div class="product-price">
                <span class="new-price">{{ $new_price }}</span>
                @if($flash_item->previous_price > 0)
                  <span class="old-price">{{ $old_price }}</span>
                @endif
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="btn-icon-group btn-inline">
              @if((isset($shop_settings) ? $shop_settings->catalog_mode : ($shopSet->catalog_mode ?? 0)) != 1)
                <a href="javascript:void(0)"
                  class="btn btn-icon cart-link"
                  data-href="{{ route('front.user.add.cart', ['id' => $flash_item->item_id, getParam()]) }}"
                  data-title="{{ $flash_item->title }}"
                  data-item_id="{{ $flash_item->item_id }}"
                  data-current_price="{{ currency_converter($flash_info['status'] ? $flash_info['amount'] : $flash_item->current_price) }}"
                  data-variations="{{ check_variation($flash_item->item_id) > 0 ? 'yes' : 'no' }}"
                  data-totalvari="{{ check_variation($flash_item->item_id) }}"
                  data-language_id="{{ $uLang }}"
                  title="{{ $keywords['Add to Cart'] ?? __('Add to Cart') }}">
                  <i class="far fa-shopping-cart"></i>
                </a>
              @endif
              <a href="javascript:void(0)"
                class="btn btn-icon btn-wishlist"
                data-url="{{ route('front.user.add.wishlist', ['id' => $flash_item->item_id, getParam()]) }}"
                title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}">
                <i class="fal fa-heart"></i>
              </a>
              <a href="javascript:void(0)"
                class="btn btn-icon quick-view-link"
                data-url="{{ route('front.user.productDetails.quickview', ['slug' => $flash_item->slug, getParam()]) }}"
                data-slug="{{ $flash_item->slug }}"
                title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                <i class="fal fa-eye"></i>
              </a>
              <a href="javascript:void(0)"
                onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $flash_item->item_id, getParam()]) }}')"
                class="btn btn-icon"
                title="{{ $keywords['Add to Compare'] ?? __('Add to Compare') }}">
                <i class="fal fa-random"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>

      <div class="text-center mt-50">
        <a href="{{ route('front.user.shop', getParam()) }}" class="btn btn-outline-primary">
          {{ $keywords['View All Deals'] ?? __('View All Deals') }}
          <i class="fal fa-arrow-right" style="margin-left:8px;"></i>
        </a>
      </div>
    @else
      <div class="text-center py-5">
        <i class="fal fa-tag" style="font-size:48px;color:#ccc;margin-bottom:16px;display:block;"></i>
        <h5 style="color:#888;">{{ $keywords['No Flash Sale Items Found'] ?? __('No Flash Sale Items Found') }}</h5>
      </div>
    @endif
  </div>
</section>
<!-- Flash Sale (Clothing) End -->

<script>
"use strict";
(function() {
  function initCountdown() {
    var el = document.getElementById('clothing-flash-countdown');
    if (!el) return;

    var endStr = el.dataset.end;
    if (!endStr) return;

    var endParsed = endStr.indexOf('T') === -1 ? endStr.replace(/-/g, '/') : endStr;
    var endTime = new Date(endParsed).getTime();

    // Use global server time (aligned to admin timezone) from scripts.blade.php if defined
    var currentT = typeof currentTime !== 'undefined' ? currentTime : new Date().toISOString();
    var currentParsed = currentT.indexOf('T') === -1 ? currentT.replace(/-/g, '/') : currentT;
    var currentTimeDate = new Date(currentParsed);

    if (isNaN(endTime) || isNaN(currentTimeDate.getTime())) {
      console.warn("Countdown date parsing failed. End: " + endStr + ", Now: " + currentT);
      return;
    }

    function updateCountdown() {
      var now = currentTimeDate.getTime();
      var diff = endTime - now;
      if (diff <= 0) {
        el.innerHTML = '';
        clearInterval(intervalId);
        return;
      }

      var d = Math.floor(diff / 86400000);
      var h = Math.floor((diff % 86400000) / 3600000);
      var m = Math.floor((diff % 3600000) / 60000);
      var s = Math.floor((diff % 60000) / 1000);

      if (d > 2) {
        d = 2;
      }

      var fd = document.getElementById('cf-days');
      var fh = document.getElementById('cf-hours');
      var fm = document.getElementById('cf-minutes');
      var fs = document.getElementById('cf-seconds');
      if (fd) fd.textContent = String(d).padStart(2,'0');
      if (fh) fh.textContent = String(h).padStart(2,'0');
      if (fm) fm.textContent = String(m).padStart(2,'0');
      if (fs) fs.textContent = String(s).padStart(2,'0');
    }

    updateCountdown();
    var intervalId = setInterval(function() {
      // Increment the simulated server time by 1 second
      currentTimeDate.setSeconds(currentTimeDate.getSeconds() + 1);
      updateCountdown();
    }, 1000);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCountdown);
  } else {
    initCountdown();
  }
})();
</script>
