<!-- Product Section v10 (Clothing) Skeleton -->
<section class="clothing-collection pb-100 lazy">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title-clothing">
          <h2 class="skeleton" style="height:40px;max-width:300px;margin:0 auto 16px;background:#f0f0f0;"></h2>
        </div>
      </div>
    </div>
    <div class="products-row-clothing">
      @for($sk = 0; $sk < 4; $sk++)
        <div class="product-default-10">
          <div class="product-img skeleton-clothing" style="aspect-ratio:3/4;"></div>
          <div class="product-details">
            <div style="height:12px;background:#f0f0f0;width:40%;margin-bottom:8px;"></div>
            <div style="height:18px;background:#f0f0f0;width:75%;margin-bottom:10px;"></div>
            <div style="height:15px;background:#f0f0f0;width:35%;"></div>
          </div>
        </div>
      @endfor
    </div>
  </div>
</section>

<!-- Product Section v10 (Clothing) Actual -->
<section class="clothing-collection pb-100 actual-content">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title-clothing" data-aos="fade-up" data-aos-delay="80">
          <h2>{{ $tabs[$i]->name }}</h2>
          <a href="{{ route('front.user.shop', getParam()) }}"
            class="btn btn-primary mt-20"
            style="margin-top:20px;display:inline-flex;align-items:center;gap:8px;">
            {{ $keywords['View All'] ?? __('View All') }}
            <i class="fal fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>

    @php $products = json_decode($tabs[$i]->products, true); @endphp

    @if(!is_null($products) && count($products) > 0)
      <div class="products-row-clothing" data-aos="fade-up" data-aos-delay="100">
        @foreach($products as $prod_id)
          @php
            $product = \App\Models\User\UserItem::where('id', $prod_id)
              ->with([
                'itemContents' => function($q) use ($uLang) {
                  $q->where('language_id', '=', $uLang);
                },
                'sliders',
              ])
              ->first();
          @endphp

          @if(!is_null($product) && count($product->itemContents) > 0)
            @php
              $pContent  = $product->itemContents->first();
              $flash_i   = flashAmountStatus($product->id, $product->current_price);
              $flash_s   = $flash_i['status'];
              if($flash_s) {
                $p_new = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($flash_i['amount']));
                $p_old = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product->current_price));
              } else {
                $p_new = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product->current_price));
                $p_old = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product->previous_price));
              }
            @endphp

            <div class="product-default-10" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
              <figure class="product-img">
                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $pContent->slug]) }}">
                  @php $sliders = $product->sliders; @endphp
                  <img class="lazyload blur-up default-img"
                    src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product->thumbnail) }}"
                    alt="{{ $pContent->title }}">
                  @if(count($sliders) > 0)
                    <img class="lazyload blur-up hover-img"
                      src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/items/images/' . $sliders->first()->image) }}"
                      alt="{{ $pContent->title }}">
                  @else
                    <img class="lazyload blur-up hover-img"
                      src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product->thumbnail) }}"
                      alt="{{ $pContent->title }}">
                  @endif
                </a>

                <!-- Badge -->
                <div class="product-badge">
                  @if($product->is_new == 1)
                    <span class="badge-new">{{ $keywords['NEW'] ?? __('NEW') }}</span>
                  @endif
                  @if($flash_s)
                    <span class="badge-sale">{{ $keywords['SALE'] ?? __('SALE') }}</span>
                  @endif
                </div>
              </figure>

              <div class="product-details">
                <h3 class="product-title lc-1">
                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $pContent->slug]) }}">
                    {{ $pContent->title }}
                  </a>
                </h3>
                <div class="product-price">
                  <span class="new-price">{{ $p_new }}</span>
                  @if($product->previous_price > 0)
                    <span class="old-price">{{ $p_old }}</span>
                  @endif
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="btn-icon-group btn-inline">
                @if($shopSet->catalog_mode != 1)
                  <a href="javascript:void(0)"
                    class="btn btn-icon cart-link"
                    data-href="{{ route('front.user.add.cart', ['id' => $product->id, getParam()]) }}"
                    data-title="{{ $pContent->title }}"
                    data-item_id="{{ $product->id }}"
                    data-current_price="{{ currency_converter($flash_i['status'] ? $flash_i['amount'] : $product->current_price) }}"
                    data-variations="{{ check_variation($product->id) > 0 ? 'yes' : 'no' }}"
                    data-totalvari="{{ check_variation($product->id) }}"
                    data-language_id="{{ $uLang }}"
                    title="{{ $keywords['Add to Cart'] ?? __('Add to Cart') }}">
                    <i class="far fa-shopping-cart"></i>
                  </a>
                @endif
                <a href="{{ route('customer.wishlist', getParam()) }}"
                  class="btn btn-icon add_to_wishlist"
                  data-item_id="{{ $product->id }}"
                  title="{{ $keywords['Wishlist'] ?? __('Wishlist') }}">
                  <i class="fal fa-heart"></i>
                </a>
                <a href="javascript:void(0)"
                  class="btn btn-icon quick-view"
                  data-item_id="{{ $product->id }}"
                  title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                  <i class="fal fa-eye"></i>
                </a>
                <a href="{{ route('front.user.compare', getParam()) }}"
                  class="btn btn-icon add_to_compare"
                  data-id="{{ $product->id }}"
                  title="{{ $keywords['Compare'] ?? __('Compare') }}">
                  <i class="fal fa-random"></i>
                </a>
              </div>
            </div>
          @endif
        @endforeach
      </div>
    @else
      <div class="text-center py-5">
        <p style="color:#888;">{{ $keywords['No products found'] ?? __('No products found') }}</p>
      </div>
    @endif
  </div>
</section>
<!-- Product Section v10 (Clothing) End -->
