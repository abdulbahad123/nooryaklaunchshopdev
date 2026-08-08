<!-- products Tab Section Start -->
<section class="products-tab-8 pt-70 pb-100 overfollow-hidden lazy">
  <div class="container">
    <div class="row">
      <div class="col-12" data-aos="fade-up" data-aos-delay="100">
        <div class="section-title title-inline flex-column justify-content-center flex-wrap mb-20">
          <h2 class="title h2 mb-20">
            {{ $userSec->featured_section_title ?? ($keywords['Featured Products'] ?? __('Featured Products')) }}</h2>
          <!-- tabs-navigation -->
          <div class="tabs-navigation tabs-navigation-5 text-center">
            <ul class="nav nav-tabs gap-10" data-hover="fancyHover">
              @foreach ($featuredCategories as $key => $category)
                <li class="nav-item {{ $key == 0 ? 'active' : '' }}">
                  <button class="nav-link hover-effect {{ $key == 0 ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#tab_category_{{ $category->id }}" type="button">{{ $category->name }}</button>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="tab-content" data-aos="fade-up" data-aos-delay="100">
      <div class="tab-pane fade show active" id="face_serum">
        <div class="row">
          @for ($skeleton = 0; $skeleton <= 5; $skeleton++)
            <div class="col-lg-4 col-md-6 col-6">
              <!-- product-default -->
              <div class="product-default-tab-card radius-md mb-20">
                <figure class="product-img skeleton skeleton-big-img">
                </figure>
                <div class="product-details text-center">
                  <!-- btn-icon-group -->
                  <div class="btn-icon-group justify-content-center btn-inline text-center mb-3">
                    <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                    <button type="button" class="btn btn-icon skeleton skeleton-btn-icon"></button>
                    <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
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
  </div>
</section>

<section class="products-tab-8 pt-70 pb-100 overfollow-hidden actual-content">
  <div class="container">
    <div class="row">
      <div class="col-12" data-aos="fade-up" data-aos-delay="100">
        <div class="section-title title-inline flex-column justify-content-center flex-wrap mb-20">
          <h2 class="title h2 mb-20">
            {{ $userSec->featured_section_title ?? ($keywords['Featured Products'] ?? __('Featured Products')) }}</h2>
          <!-- tabs-navigation -->
          <div class="tabs-navigation tabs-navigation-5 text-center">
            <ul class="nav nav-tabs gap-10" data-hover="fancyHover">
              @foreach ($featuredCategories as $key => $category)
                <li class="nav-item {{ $key == 0 ? 'active' : '' }}">
                  <button class="nav-link hover-effect {{ $key == 0 ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#tab_category_{{ $category->id }}" type="button">{{ $category->name }}</button>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="tab-content" data-aos="fade-up" data-aos-delay="100">
      @if (count($featuredCategories) == 0)
        <h5 class="title text-center mb-20">
          {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
        </h5>
      @else
        @foreach ($featuredCategories as $key => $cat)
          <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="tab_category_{{ $cat->id }}">
            <div class="row">
              @foreach ($cat->items as $single_item)
                @php
                  $product_details = \App\Models\User\UserItem::where([
                      ['id', $single_item->item_id],
                      ['is_feature', 1],
                  ])
                      ->with([
                          'itemContents' => function ($q) use ($uLang) {
                              $q->where('language_id', '=', $uLang);
                          },
                          'sliders',
                      ])
                      ->first();
                @endphp
                @if (!is_null(@$product_details->itemContents[0]->slug))
                  @php
                    $flash_info = flashAmountStatus($product_details->id, $product_details->current_price);
                    $product_current_price = $flash_info['amount'];
                    $flash_status = $flash_info['status'];
                  @endphp
                  <div class="col-lg-4 col-md-6 col-6">
                    <!-- product-default -->
                    <div class="product-default-tab-card radius-md mb-20">
                      <figure class="product-img">
                        <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}"
                          class="ratio ratio-1-1">
                          <img class="lazyload blur-up default-img"
                            src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                            alt="Product">
                          <img class="lazyload blur-up hover-img"
                            src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                            alt="Product">
                        </a>
                      </figure>
                      <div class="product-details text-center">
                        <!-- btn-icon-group -->
                        <div class="btn-icon-group justify-content-center btn-inline text-center mb-3">
                          <a class="btn btn-icon" href="javascript:void(0)"
                            onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details->id, getParam()]) }}')"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                            data-bs-original-title="{{ $keywords['Compare'] ?? __('Compare') }}">
                            <i class="fal fa-random"></i>
                          </a>

                          <button type="button" class="btn btn-icon  quick-view-link"
                            data-slug="{{ $product_details->itemContents[0]->slug }}"
                            data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product_details->itemContents[0]->slug, getParam()]) }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                            data-bs-original-title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                            <i class="fal fa-eye"></i>
                          </button>

                          @php
                            $customer_id = Auth::guard('customer')->check()
                                ? Auth::guard('customer')->user()->id
                                : null;
                            $checkWishList = $customer_id ? checkWishList($product_details->id, $customer_id) : false;
                          @endphp
                          <a href="#"
                            class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"data-item_id="{{ $product_details->id }}"
                            data-href="{{ route('front.user.add.wishlist', ['id' => $product_details->id, getParam()]) }}"
                            data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details->id, getParam()]) }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                            data-bs-original-title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}">
                            <i class="fal fa-heart"></i>
                          </a>
                          @if ($shop_settings->catalog_mode != 1)
                            <a href="javscript:void(0)"
                              class="btn btn-icon cart-link"data-title="{{ $product_details->itemContents[0]->title }}"
                              data-current_price="{{ currency_converter($product_current_price) }}"
                              data-item_id="{{ $product_details->id }}" data-language_id="{{ $uLang }}"
                              data-totalVari="{{ check_variation($product_details->id) }}"
                              data-variations="{{ check_variation($product_details->id) > 0 ? 'yes' : null }}"
                              data-href="{{ route('front.user.add.cart', ['id' => $product_details->id, getParam()]) }}"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                              data-bs-original-title="{{ $keywords['Add to Cart'] ?? __('Add to Cart') }}">
                              <i class="fal fa-shopping-bag"></i>
                            </a>
                          @endif
                        </div>
                        <h5 class="product-title fw-medium lc-2 mt-1 mb-2">
                          <a
                            href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}">{{ $product_details->itemContents[0]->title }}</a>
                        </h5>
                        <div class="product-price justify-content-center">
                          @if ($flash_status == true)
                            <span class="new-price fw-semibold">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
                            </span>
                            <span class="old-price fw-medium text-decoration-line-through">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details->current_price)) }}
                            </span>
                          @else
                            <span class="new-price fw-semibold">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details->current_price)) }}
                            </span>
                            <span class="old-price fw-medium text-decoration-line-through">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details->previous_price)) }}
                            </span>
                          @endif
                        </div>
                      </div>
                      @php
                        $item_label = DB::table('labels')
                            ->where('id', @$product_details->itemContents[0]->label_id)
                            ->first();
                        $label = $item_label->name ?? null;
                        $color = $item_label->color ?? null;
                      @endphp
                      @if ($item_label)
                        <span class="label label-2"
                          style="background-color: #{{ $color }}">{{ $label }}</span>
                      @endif

                      @if ($flash_status == true)
                        <span class="label-discount-percentage">
                          <x-flash-icon></x-flash-icon>{{ $product_details->flash_amount }}%
                        </span>
                      @endif

                    </div>
                  </div>
                @endif
              @endforeach

            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</section>
<!-- products Tab Section End -->
