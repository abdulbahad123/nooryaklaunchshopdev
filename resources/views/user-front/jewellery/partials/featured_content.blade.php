<!-- products Tab Section Start -->
<section class="products-tab-8 pb-100 overfollow-hidden lazy">
  <div class="container">
    <div class="products-tab-area" data-aos="fade-up" data-aos-delay="100">
      <div class="row">
        <div class="col-12">
          <div class="section-title title-inline flex-column justify-content-center flex-wrap mb-20">
            <h2 class="title fw-bold mb-20">
              {{ $userSec->featured_section_title ?? ($keywords['Featured Products'] ?? __('Featured Products')) }}</h2>
            <!-- tabs-navigation -->
            <div class="tabs-navigation tabs-navigation-4 text-center">
              <ul class="nav nav-tabs gap-10" data-hover="fancyHover">
                @for ($skeleton = 1; $skeleton <= 5; $skeleton++)
                  <li class="nav-item skeleton skeleton-btn-icon w-90 h-45px">
                  </li>
                @endfor
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab_category32131">
          <div class="row justify-content-center">
            @for ($skeleton = 1; $skeleton <= 4; $skeleton++)
              <div class="col-xl-3 col-lg-4 col-6">
                <!-- product-default -->
                <div class="product-default-8 mb-30">
                  <figure class="product-img skeleton skeleton-big-img"></figure>
                  <div class="product-details border-0 text-center">
                    <!-- btn-icon-group -->
                    <div class="btn-icon-group justify-content-center btn-inline text-center mb-3">
                      <a class="btn btn-icon skeleton skeleton-btn-icon" href="javascript:void(0)">
                      </a>
                      <button type="button" class="btn btn-icon skeleton skeleton-btn-icon">
                      </button>
                      <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                      <a href="javscript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon">
                      </a>
                    </div>
                    <span class="product-category text-sm skeleton skeleton-title"></span>
                    <h5 class="product-title fw-medium lc-2 mt-1 mb-2 skeleton skeleton-title">
                    </h5>
                    <!-- Rateing -->
                    <div class="d-flex align-items-center justify-content-center mb-10 skeleton skeleton-ratings">
                    </div>
                    <div class="product-price justify-content-center">
                      <span class="new-price fw-bold skeleton skeleton-price"></span>
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
  </div>
</section>
<section class="products-tab-8 pb-100 overfollow-hidden actual-content">
  <div class="container">
    <div class="products-tab-area" data-aos="fade-up" data-aos-delay="100">
      <div class="row">
        <div class="col-12">
          <div class="section-title title-inline flex-column justify-content-center flex-wrap mb-20">
            <h2 class="title fw-bold mb-20">
              {{ $userSec->featured_section_title ?? ($keywords['Featured Products'] ?? __('Featured Products')) }}
            </h2>
            <!-- tabs-navigation -->
            <div class="tabs-navigation tabs-navigation-4 text-center">
              <ul class="nav nav-tabs gap-10" data-hover="fancyHover">
                @foreach ($featuredCategories as $key => $category)
                  @if ($category->is_feature == 1)
                    <li class="nav-item {{ $key == 0 ? 'active' : '' }}">
                      <button class="nav-link hover-effect {{ $key == 0 ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#tab_category_{{ $category->id }}" type="button">{{ $category->name }}</button>
                    </li>
                  @endif
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
      @if (count($featuredCategories) == 0)
        <h5 class="title text-center pb-20">
          {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
        </h5>
      @else
        <div class="tab-content">
          @foreach ($featuredCategories as $key => $cat)
            <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="tab_category_{{ $cat->id }}">
              <div class="row justify-content-center">
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
                    <div class="col-xl-3 col-lg-4 col-6">
                      <!-- product-default -->
                      <div class="product-default-8 mb-30">
                        <figure class="product-img">
                          <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}"
                            class="ratio ratio-1-1">
                            <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                              alt="">
                            <img class="lazyload hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                              alt="">
                          </a>
                        </figure>
                        <div class="product-details border-0 text-center">
                          <!-- btn-icon-group -->
                          <div class="btn-icon-group justify-content-center btn-inline text-center mb-3">
                            <a class="btn btn-icon" href="javascript:void(0)"
                              onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details->id, getParam()]) }}')"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                              data-bs-original-title="{{ $keywords['Compare'] ?? __('Compare') }}">
                              <i class="fal fa-random"></i>
                            </a>

                            <button type="button" class="btn btn-icon quick-view-link"
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
                            <a href="javascript:void(0)"
                              class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"data-item_id="{{ $product_details->id }}"
                              data-href="{{ route('front.user.add.wishlist', ['id' => $product_details->id, getParam()]) }}"
                              data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details->id, getParam()]) }}"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                              data-bs-original-title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}">
                              <i class="fal fa-heart"></i>
                            </a>
                            @if ($shop_settings->catalog_mode != 1)
                              <a href="javscript:void(0)" class="btn btn-icon cart-link"
                                data-title="{{ $product_details->itemContents[0]->title }}"
                                data-current_price="{{ currency_converter($product_current_price) }}"
                                data-item_id="{{ $product_details->id }}" data-language_id="{{ $uLang }}"
                                data-totalVari="{{ check_variation($product_details->id) }}"
                                data-variations="{{ check_variation($product_details->id) > 0 ? 'yes' : null }}"
                                data-href="{{ route('front.user.add.cart', ['id' => $product_details->id, getParam()]) }}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                                data-bs-original-title="{{ $keywords['Add to Cart'] ?? __('Add to Cart') }}"><i
                                  class="fal fa-shopping-bag"></i>
                              </a>
                            @endif
                          </div>
                          <span class="product-category text-sm">
                            <a
                              href="{{ route('front.user.shop', ['category' => $product_details->itemContents[0]->category->slug, getParam()]) }}">{{ $product_details->itemContents[0]->category->name }}</a>
                          </span>
                          <h5 class="product-title fw-medium lc-2 mt-1 mb-2">
                            <a
                              href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}">{{ $product_details->itemContents[0]->title }}</a>
                          </h5>
                          @if ($shop_settings->item_rating_system == 1)
                            <!-- Rateing -->
                            <div class="d-flex align-items-center justify-content-center mb-10">
                              <div class="product-ratings rate text-xsm">
                                <div class="rating" style="width:{{ $product_details->rating * 20 }}"></div>
                              </div>
                              <span class="ratings-total">({{ reviewCount($product_details->id) }})</span>
                            </div>
                          @endif
                          <div class="product-price justify-content-center">
                            @if ($flash_status == true)
                              <span class="new-price fw-bold">
                                {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
                              </span>
                              <span class="old-price fw-medium text-decoration-line-through">
                                {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_details->current_price)) }}
                              </span>
                            @else
                              <span class="new-price fw-bold">
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
        </div>
      @endif
    </div>
  </div>
</section>
<!-- products Tab Section End -->
