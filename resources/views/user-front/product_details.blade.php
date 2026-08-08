@extends('user-front.layout')
@section('page-title', $product->title ?? ($keywords['Product_Details'] ?? __('Product Details')))
@section('breadcrumb_title', $product->title ?? ($keywords['Product_Details'] ?? __('Product Details')))
@section('breadcrumb_second_title', $keywords['Product_Details'] ?? __('Product Details'))
@section('og-meta')
  <!--- For Social Media Share Thumbnail --->
  <meta property="og:title" content="{{ $product->title . ' | ' . $user->username }}">
  <meta property="og:description" content="{{ $product->summary }}">
  <meta property="og:image" content="{{ asset('assets/front/img/user/items/thumbnail/' . $product->item->thumbnail) }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
  <meta name="twitter:card" content="summary_large_image">
@endsection
@section('content')

  <!-- Shop Start -->
  <div class="product-single pt-100 pb-70 overflow-hidden">
    <div class="container">
      <div class="product-single-default">
        <div class="row ">
          <div class="col-lg-6">
            @if ($product->item->sliders)
              <input type="hidden" id="details_item_id" value="{{ $product->item->id }}">
              <div class="product-single-gallery">
                <div class="slider-thumbnails2">

                  @foreach ($product->item->sliders as $slide)
                    <div class="thumbnail-img radius-md lazy-container ratio ratio-1-1">
                      <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/items/slider-images/' . $slide->image) }}"
                        alt="product image" />
                    </div>
                  @endforeach

                </div>
                <div class="product-single-slider2">
                  @foreach ($product->item->sliders as $slide)
                    <div class="product-single-single-item">
                      <figure class="radius-lg lazy-container ratio ratio-1-1">
                        <a src="{{ asset('assets/front/img/user/items/slider-images/' . $slide->image) }}">
                          <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/items/slider-images/' . $slide->image) }}"
                            alt="product image" />
                        </a>
                      </figure>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
          <div class="col-lg-6">
            <div class="product-single-details">
              @php
                $item_label = DB::table('labels')->where('id', $product->label_id)->first();
                $label = $item_label->name ?? null;
                $color = $item_label->color ?? null;
              @endphp

              <h2 class="product-title">{{ $product->title }} <span class="label label-2"
                  style="background-color: #{{ $color }}">{{ $label }}</span> </h2>
              <ul>
                @php
                  $avgreview = \App\Models\User\UserItemReview::where('item_id', $product->item->id)->avg('review');
                @endphp

                <div class="d-flex align-items-center gap-10 mb-2">
                  @if ($shop_settings->item_rating_system == 1)
                    <div class="rating-wrapper d-flex gap-2 align-items-center">
                      <div class="rate-icon mt-n3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="23" viewBox="0 0 24 23"
                          fill="none">
                          <path
                            d="M12 0.5L14.6942 8.7918H23.4127L16.3593 13.9164L19.0534 22.2082L12 17.0836L4.94658 22.2082L7.64074 13.9164L0.587322 8.7918H9.30583L12 0.5Z"
                            fill="#EEAE0B"></path>
                        </svg>
                      </div>

                      <span>
                        {{ number_format($avgreview, 1) }}
                        ({{ reviewCount($product->item->id) }}
                        {{ reviewCount($product->item->id) == 1 ? $keywords['Review'] ?? __('Review') : $keywords['Reviews'] ?? __('Reviews') }})
                      </span>

                    </div>
                  @endif
                  <div class="stock-status">
                    @if ($product->item->type == 'physical')
                      @php
                        $varitaion_stock = VariationStock($product->item->id);
                      @endphp
                      @if ($varitaion_stock['has_variation'] == 'yes')
                        @if ($varitaion_stock['stock'] == 'yes')
                          <span class="badge bg-success"><i class="fa fa-check"></i>
                            {{ $keywords['In Stock'] ?? __('In Stock') }}</span>
                        @else
                          <span class="badge bg-danger"><i class="fa fa-times"></i>
                            {{ $keywords['Out of Stock'] ?? __('Out of Stock') }}</span>
                        @endif
                      @else
                        @if ($product->item->stock > 0)
                          <span class="badge bg-success"><i class="fa fa-check"></i>
                            {{ $keywords['In Stock'] ?? __('In Stock') }}</span>
                        @else
                          <span class="badge bg-danger"><i class="fa fa-times"></i>
                            {{ $keywords['Out of Stock'] ?? __('Out of Stock') }}</span>
                        @endif
                      @endif
                    @endif
                  </div>
                </div>
              </ul>
              @php
                $flash_info = flashAmountStatus($product->item_id, $product->item->current_price);
                $product_current_price = $flash_info['amount'];
                $flash_status = $flash_info['status'];
              @endphp

              <div class="product-price">
                @if ($flash_status == true)
                  <div class="new-price-area d-flex color-primary">
                    <span
                      class="new-price-currency">{{ $userCurrentCurr->symbol_position == 'left' ? $userCurrentCurr->symbol : '' }}</span>
                    <span class="new-price" id="details_new-price"
                      data-base_price="{{ currency_converter($product_current_price) }}">{{ currency_converter($product_current_price) }}</span>
                    {{ $userCurrentCurr->symbol_position == 'right' ? $userCurrentCurr->symbol : '' }}
                  </div>

                  <div class="old-price-area d-flex">
                    {{ $userCurrentCurr->symbol_position == 'left' ? $userCurrentCurr->symbol : '' }}
                    <span class="old-price" id="details_old-price"
                      data-old_price="{{ currency_converter($product->item->current_price) }}">
                      {{ currency_converter($product->item->current_price) }}</span>
                    {{ $userCurrentCurr->symbol_position == 'right' ? $userCurrentCurr->symbol : '' }}
                  </div>

                  <span class="discountoff">{{ $product->item->flash_amount }}%
                    {{ $keywords['OFF'] ?? __('OFF') }}</span>
                @else
                  <div class="new-price-area d-flex color-primary ">
                    {{ $userCurrentCurr->symbol_position == 'left' ? $userCurrentCurr->symbol : '' }}
                    <span class="new-price" id="details_new-price"
                      data-base_price="{{ currency_converter($product->item->current_price) }}">{{ currency_converter($product->item->current_price) }}</span>
                    {{ $userCurrentCurr->symbol_position == 'right' ? $userCurrentCurr->symbol : '' }}
                  </div>

                  <div class="old-price-area d-flex">
                    @if ($product->item->previous_price > 0)
                      {{ $userCurrentCurr->symbol_position == 'left' ? $userCurrentCurr->symbol : '' }}
                      <span class="old-price" id="details_old-price"
                        data-old_price="{{ currency_converter($product->item->previous_price) }}">
                        {{ currency_converter($product->item->previous_price) }}</span>
                      {{ $userCurrentCurr->symbol_position == 'right' ? $userCurrentCurr->symbol : '' }}
                    @endif
                  </div>
                @endif
              </div>
              <p class="product-text mb-3 lc-3">
                {{ $product->summary }}
              </p>
              @if ($product->item->sku)
                <div class="sku-code mb-10">
                  <span class="text-dark fw-semibold">{{ $keywords['SKU'] ?? __('SKU') }} :</span>
                  <span class="text-dark">{{ $product->item->sku }}</span>
                </div>
              @endif


              @if ($flash_status == true)
                <div class="product-countdown mt-3" data-end_time="{{ $product->item->end_time }}"
                  data-end_date="{{ $product->item->end_date }}" data-item_id="{{ $product->id }}">
                  <div id="" class="count radius-sm days">
                    <span class="count-value_{{ $product->id }}"></span>
                    <span class="count-period">{{ $keywords['Days'] ?? __('Days') }} </span>
                  </div>
                  <div id="" class="count radius-sm hours">
                    <span class="count-value_{{ $product->id }}"></span>
                    <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
                  </div>
                  <div id="" class="count radius-sm minutes">
                    <span class="count-value_{{ $product->id }}"></span>
                    <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
                  </div>
                  <div id="" class="count radius-sm seconds">
                    <span class="count-value_{{ $product->id }}"></span>
                    <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
                  </div>
                  <div data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ $keywords['Flash_sale'] ?? __('Flash sale') }}">
                    <div class="details-label-discount-percentage">
                      <div class="percentage-text">
                        <x-flash-icon></x-flash-icon> <span>{{ $product->item->flash_amount }}%</span>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
              <div class="product-action">
                @if (count($product_variations) > 0)
                  <ul class="product-list-group mb-20" id="variantListULDetails">
                    @foreach ($product_variations as $product_variation)
                      @php
                        $product_variation_contents = App\Models\User\ProductVariationContent::where([
                            ['product_variation_id', $product_variation->id],
                            ['language_id', $uLang],
                        ])->get();
                        $variant_content_options = App\Models\User\ProductVariantOption::where([
                            ['product_variation_id', $product_variation->id],
                        ])->get();
                      @endphp

                      @foreach ($product_variation_contents as $product_variation_content)
                        @php
                          $variant_content = App\Models\VariantContent::where(
                              'id',
                              $product_variation_content->variation_name,
                          )->first();
                        @endphp
                        <li class="list-item" data-variant_name="{{ @$variant_content->name }}">
                          <h4 class="list-item-title color-primary mb-1">{{ @$variant_content->name }}:</h4>
                          <ul class="custom-radio variantUL" id="variantUL">
                            @foreach ($variant_content_options as $variant_content_option)
                              @php
                                $variant_option_contents = App\Models\User\ProductVariantOptionContent::where([
                                    ['product_variant_option_id', $variant_content_option->id],
                                    ['language_id', $uLang],
                                ])->first();

                                $sup_option_content = make_input_name(
                                    @$variant_option_contents->option_content->option_name,
                                );

                                $id_name = make_input_name(@$variant_option_contents->option_content->option_name);
                                $main_id = 'detail_' . $sup_option_content . '_' . $id_name;
                              @endphp
                              <li>
                                <input id="radio_{{ $main_id }}" type="radio"
                                  name="{{ make_input_name(@$variant_content->name) }}[]"
                                  class="{{ $main_id }} product-variant input-radio"
                                  value="{{ $variant_option_contents->option_content->option_name }}:{{ currency_converter($variant_content_option->price) }}:{{ $variant_content_option->stock }}:{{ $variant_content_option->id }}:{{ $product_variation->id }}">
                                <label class="form-radio-label" for="radio_{{ $main_id }}"><span
                                    class="details_view_variants_price">{{ $variant_option_contents->option_content->option_name }}
                                    (<i
                                      class="fas fa-plus"></i>{{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($variant_content_option->price)) }})
                                  </span></label>
                              </li>
                            @endforeach
                          </ul>
                        </li>
                      @endforeach
                    @endforeach
                  </ul>
                @endif
                @if ($shop_settings->catalog_mode != 1)
                  <div class="d-flex flex-wrap align-items-center gap-10 mb-20">
                    <div class="quantity-input d-flex item_quantity_details">
                      <div class="quantity-down quantity-btn minus" data-item_id="{{ $product->id }}">
                        <i class="fal fa-minus"></i>
                      </div>
                      <input class="quantity_field" type="number" name="cart-amount" value="1" min="1">
                      <div class="quantity-up quantity-btn plus" data-item_id="{{ $product->id }}">
                        <i class="fal fa-plus"></i>
                      </div>
                    </div>

                    <div class="btn-icon-group btn-inline">
                      <a class="btn btn-icon radius-sm"
                        onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product->item_id, getParam()]) }}')"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i>
                      </a>

                      @php
                        $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                        $checkWishList = $customer_id ? checkWishList($product->item_id, $customer_id) : false;
                      @endphp
                      <a href="#"
                        class="btn btn-icon radius-sm {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-url="{{ route('front.user.add.wishlist', ['id' => $product->item_id, getParam()]) }}"
                        data-removeUrl="{{ route('front.user.remove.wishlist', ['id' => $product->item_id, getParam()]) }}"
                        title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                          class="fal fa-heart"></i>
                      </a>
                    </div>

                  </div>

                  <input type="hidden" name="final-price" id="details_final-price" class="form-control final-price">

                  <button class="btn btn-sm btn-primary radius-md" type="button" aria-label="Add to cart"
                    data-bs-toggle="tooltip" data-placement="top"
                    title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}" onclick="addToCartDetails2()">
                    <i class="fas fa-cart-plus"></i><span>{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }} </span>
                  </button>

                  <div class="d-flex align-items-center flex-wrap gap-10 mt-20">
                    <span class="text-dark fw-semibold">{{ $keywords['Share Now'] ?? __('Share Now') }} :</span>
                    <div class="social-link ">
                      <a href="https://www.instagram.com/stories/create/?text={{ urlencode('Check out this product: ' . url()->current()) }}"
                        target="_blank" title="Share on Instagram">
                        <i class="fab fa-instagram"></i>
                      </a>
                      <a href="//x.com/intent/tweet?text={{ urlencode('Check this out! ' . url()->current()) }}"
                        target="_blank" title="Share on Twitter">
                        <i class="fab fa-twitter"></i>
                      </a>
                      <a href="//www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&src=sdkpreparse"
                        target="_blank" title="Share on Facebook">
                        <i class="fab fa-facebook-f"></i>
                      </a>
                      <a href="https://wa.me/?text={{ urlencode('Check this out: ' . url()->current()) }}"
                        target="_blank" title="Share on WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                      </a>
                    </div>
                  </div>
                @endif

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="product-single-tab pt-70">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc"
              type="button">{{ $keywords['Description'] ?? __('Description') }} </button>
          </li>

          @if ($shop_settings->item_rating_system == 1)
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews"
                type="button">{{ $keywords['Reviews'] ?? __('Reviews') }} </button>
            </li>
          @endif
          @if ($shop_settings->disqus_comment_system == 1)
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Disqus"
                type="button">{{ $keywords['Comments'] ?? __('Comments') }} </button>
            </li>
          @endif
        </ul>
        <div class="tab-content radius-lg">
          <div class="tab-pane fade active show" id="desc">
            <div class="tab-description">
              <div class="row align-items-center">
                <div class="col-md-12">
                  <div class="content mb-30 tinymce-content">
                    {!! replaceBaseUrl($product->description ?? null) !!}
                  </div>
                </div>
              </div>

            </div>
          </div>
          @if ($shop_settings->item_rating_system == 1)
            <div class="tab-pane fade" id="reviews">
              <div class="tav-review mb-30">
                <h4 class="mb-15">{{ $keywords['Customer Reviews'] ?? __('Customer Reviews') }}</h4>
                <ul class="comment-list">
                  @foreach ($reviews as $review)
                    <ul class="comment-list">
                      <li class="comment mb-20">
                        <div class="comment-body">
                          <div class="author">
                            <img class="radius-sm lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ is_null(@$review->customer->image) ? asset('assets/user-front/images/avatar-1.jpg') : asset('assets/user-front/images/users/' . @$review->customer->image) }}"
                              alt="">
                          </div>
                          <div class="content">
                            <h6>
                              {{ !empty(convertUtf8(@$review->customer)) ? convertUtf8(@$review->customer->username) : '' }}
                              <span>( {{ @$review->created_at->format('F j, Y') }} )</span>
                            </h6>
                            <div class="rate text-xsm">
                              <div class="rating" style="width:{{ @$review->review * 20 }}%"></div>
                            </div>
                            <p>{{ nl2br(convertUtf8(@$review->comment)) }}
                            </p>
                          </div>
                        </div>
                      </li>


                    </ul>
                  @endforeach
                </ul>

                @if (Auth::guard('customer')->user())
                  @if (App\Models\User\UserOrderItem::where('customer_id', Auth::guard('customer')->user()->id)->where('item_id', $product->item->id)->exists())
                    <div class="comment-form">
                      @error('error')
                        <p class="text-danger my-2">{{ Session::get('error') }}</p>
                      @enderror
                      <h4 class="mb-10">{{ $keywords['Add Your review'] ?? __('Add Your review') }}</h4>
                      <form action="{{ route('item.review.submit', getParam()) }}" method="POST">
                        @csrf
                        <input type="hidden" value="" id="reviewValue" name="review">
                        <input type="hidden" value="{{ $product->item->id }}" name="item_id">

                        <div class="input-box mb-3">
                          <div class="review-content mt-3">
                            <ul class="review-value review-1">
                              <li><a class="cursor-pointer" data-href="1"><i class="far fa-star"></i></a></li>
                            </ul>
                            <ul class="review-value review-2">
                              <li><a class="cursor-pointer" data-href="2"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="2"><i class="far fa-star"></i></a></li>
                            </ul>
                            <ul class="review-value review-3">
                              <li><a class="cursor-pointer" data-href="3"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="3"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="3"><i class="far fa-star"></i></a></li>
                            </ul>
                            <ul class="review-value review-4">
                              <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                            </ul>
                            <ul class="review-value review-5">
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                            </ul>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12">
                            <div class="form-group mb-30">
                              <textarea class="form-control" name="comment" id="comment" cols="30" rows="9"
                                placeholder="{{ $keywords['Comment'] ?? __('Comment') }} *"></textarea>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <button type="submit"
                            class="btn btn-lg btn-primary radius-md">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                        </div>
                      </form>
                    </div>
                  @endif
                @else
                  <div class="review-login">
                    <a class="boxed-btn d-inline-block mr-2"
                      href="{{ route('customer.login', getParam()) }}">{{ $keywords['Login'] ?? __('Login') }}</a>
                    {{ $keywords['to leave a rating'] ?? __('to leave a rating') }}
                  </div>
                @endif
              </div>
            </div>
          @endif
          @if ($shop_settings->disqus_comment_system == 1)
            <div class="tab-pane fade" id="Disqus">
              <div id="disqus_thread"></div>
            </div>
          @endif
        </div>
      </div>
      <!-- Flash Sale Start -->
      <div class="product-details-related-products">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-inline mb-10">
              <h2 class="title fs-1 mb-20">{{ $keywords['Related Products'] ?? __('Related Products') }} </h2>
              @if (count($related_product) > 0)
                <a href="{{ route('front.user.shop', [getParam(), 'category' => $category_slug]) }}"
                  class="btn btn-md btn-primary radius-sm mb-20">{{ $keywords['More_Items'] ?? __('More Items') }} </a>
              @endif
            </div>
          </div>
          <div class="col-12">
            @if (count($related_product) > 0)
              @php
                $slidesToShowMobile = ($userBs->theme == 'clothing') ? 2 : 1;
              @endphp
              <div class="product-slider mb-30 pb-10" id="product-details-slider"
                data-slick='{"dots": true, "slidesToShow": 4, "slidesToScroll": 1, "responsive": [{"breakpoint": 1200, "settings": {"slidesToShow": 3, "slidesToScroll": 1}}, {"breakpoint": 992, "settings": {"slidesToShow": 2, "slidesToScroll": 1}}, {"breakpoint": 575, "settings": {"slidesToShow": {{ $slidesToShowMobile }}, "slidesToScroll": 1}}]}'>
                @foreach ($related_product as $item)
                  @if ($userBs->theme == 'clothing')
                    @php
                      $itemContent = $item;
                      $product = $item->item;
                      $fi = flashAmountStatus($product->id, $product->current_price);
                      if($fi['status']) {
                        $p_new = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($fi['amount']));
                        $p_old = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product->current_price));
                      } else {
                        $p_new = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product->current_price));
                        $p_old = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product->previous_price));
                      }
                    @endphp
                    <div class="product-default-10">
                      <figure class="product-img">
                        <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $itemContent->slug]) }}">
                          <img class="lazyload blur-up default-img"
                            src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product->thumbnail) }}"
                            alt="{{ $itemContent->title }}">
                        </a>
                        <div class="product-badge">
                          @if($product->previous_price > $product->current_price)
                            <span class="badge-sale">SALE</span>
                          @endif
                        </div>
                      </figure>
                      <div class="product-details">
                        <h3 class="product-title">
                          <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $itemContent->slug]) }}">{{ $itemContent->title }}</a>
                        </h3>
                        <div class="product-price">
                          <span class="new-price">{{ $p_new }}</span>
                          @if($product->previous_price > $product->current_price)
                            <span class="old-price">{{ $p_old }}</span>
                          @endif
                        </div>
                      </div>
                      <div class="btn-icon-group btn-inline">
                        @if ($shop_settings->catalog_mode != 1)
                          <a href="javascript:void(0)"
                            class="btn-icon cart-link"
                            data-href="{{ route('front.user.add.cart', ['id' => $product->id, getParam()]) }}"
                            data-title="{{ $itemContent->title }}"
                            data-item_id="{{ $product->id }}"
                            data-current_price="{{ currency_converter($fi['status'] ? $fi['amount'] : $product->current_price) }}"
                            data-variations="{{ check_variation($product->id) > 0 ? 'yes' : 'no' }}"
                            data-totalvari="{{ check_variation($product->id) }}"
                            data-language_id="{{ $uLang }}"
                            title="Add to Cart"><i class="far fa-shopping-cart"></i></a>
                        @endif
                        <a href="javascript:void(0)" class="btn-icon quick-view-link" data-url="{{ route('front.user.productDetails.quickview', ['slug' => $itemContent->slug, getParam()]) }}" data-slug="{{ $itemContent->slug }}" title="Quick View"><i class="fal fa-eye"></i></a>
                        <a href="javascript:void(0)" data-url="{{ route('front.user.add.wishlist', ['id' => $product->id, getParam()]) }}" class="btn-icon btn-wishlist" title="Wishlist"><i class="fal fa-heart"></i></a>
                        <a href="javascript:void(0)" onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product->id, getParam()]) }}')" class="btn-icon" title="Compare"><i class="fal fa-random"></i></a>
                      </div>
                    </div>
                  @else
                    <div class="product-default product-center radius-xl">
                      <figure class="product-img">
                        <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                          class="lazy-container ratio ratio-1-1">
                          <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->item->thumbnail) }}"
                            alt="Product">
                          <img class="lazyload hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->item->thumbnail) }}"
                            alt="Product">
                        </a>
                      </figure>
                      <div class="product-details">
                        <a href="{{ route('front.user.shop', ['category' => $item->category->slug, getParam()]) }}">
                          <span class="product-category text-sm">{{ $item->category->name }}</span>
                        </a>
                        <h3 class="product-title">
                          <a
                            href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ truncateString($item->title, 30) }}</a>
                        </h3>

                        @if ($shop_settings->item_rating_system == 1)
                          <div class="d-flex justify-content-center align-items-center">
                            <div class="product-ratings rate text-xsm">
                              <div class="rating" style="width:{{ $item->item->rating * 20 }}%"></div>
                            </div>
                            <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                          </div>
                        @endif

                        @php
                          $flash_info = flashAmountStatus($item->item->id, $item->item->current_price);
                          $product_current_price = $flash_info['amount'];
                          $flash_status = $flash_info['status'];
                        @endphp

                        <div class="product-price">
                          @if ($flash_status == true)
                            <span class="new-price">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($product_current_price)) }}
                            </span>

                            <span class="old-price ms-1 line_through">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->item->current_price)) }}
                            </span>

                            <span class="old-price">{{ $item->item->flash_amount }}%
                              {{ $keywords['OFF'] ?? __('OFF') }}</span>
                          @else
                            <span class="new-price">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->item->current_price)) }}
                            </span>
                            <span class="old-price line_through">
                              {{ symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol, currency_converter($item->item->previous_price)) }}
                            </span>
                          @endif
                        </div>
                      </div>

                      <div class="btn-icon-group btn-inline">

                        @if ($shop_settings->catalog_mode != 1)
                          <a class=" btn btn-icon radius-sm cart-link cursor-pointer" data-title="{{ $item->title }}"
                            data-current_price="{{ currency_converter($product_current_price) }}"
                            data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                            data-totalVari="{{ check_variation($item->item_id) }}"
                            data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                            data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                              class="far fa-shopping-cart "></i></a>
                        @endif

                        @php
                          $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                          $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                        @endphp
                        <a href="#"
                          class="btn btn-icon radius-sm {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                          data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                          data-href="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          data-removeUrl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                            class="fal fa-heart"></i>
                        </a>

                        <a class="btn btn-icon radius-sm quick-view-link" data-bs-toggle="tooltip"
                          data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                          data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                          title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i>
                        </a>

                        <a onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                          class="btn btn-icon radius-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                          title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i>
                        </a>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            @else
              <h3 class="text-center">{{ $keywords['No related product found'] ?? __('No related product found') }}
              </h3>
            @endif
          </div>
        </div>
      </div>
      <!-- Flash Sale End -->
    </div>
  </div>
  <!-- Shop End -->
  {{-- Variation Modal Starts --}}
  @include('user-front.partials.variation-modal')
  {{-- Variation Modal Ends --}}

  <!-- Quick View Modal Start -->
  <div class="modal custom-modal quick-view-modal fade" id="quickViewModal" tabindex="-1"
    aria-labelledby="quickViewModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content radius-sm">
        <button type="button" class="close_modal_btn" data-bs-dismiss="modal" aria-label="Close"><i
            class="fal fa-times"></i></button>
        <div class="modal-body">
          <div class="product-single-default">
            <div class="row" id="quickViewModalContent">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Quick View Modal End -->

  @if ($shop_settings->catalog_mode != 1)
    @php
      $is_xl_breakpoint = in_array($userBs->theme, ['pet', 'skinflow', 'jewellery', 'clothing']);
      $breakpoint_class = $is_xl_breakpoint ? 'd-xl-none' : 'd-lg-none';
    @endphp
    <!-- Sticky Bottom Cart Bar on Mobile -->
    <div class="sticky-bottom-cart-bar {{ $breakpoint_class }}">
      <div class="container">
        <div class="sticky-cart-wrapper">
          <!-- Product Details / Name -->
          <div class="sticky-product-info text-center">
            <span class="sticky-product-title">{{ $product->title }}</span>
            <span class="sticky-product-variant-sep d-none"> — </span>
            <span class="sticky-product-selected-variant"></span>
          </div>
          
          <!-- Controls Row -->
          <div class="sticky-cart-controls">
            <!-- Quantity selector -->
            <div class="sticky-quantity">
              <div class="quantity-btn minus">
                <i class="fal fa-minus"></i>
              </div>
              <input class="quantity_field sticky_quantity_field" type="number" name="sticky-cart-amount" value="1" min="1" readonly>
              <div class="quantity-btn plus">
                <i class="fal fa-plus"></i>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="sticky-actions">
              <button class="sticky-add-to-cart" type="button" onclick="addToCartDetails2()">
                {{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}
              </button>
              <button class="sticky-buy-now" type="button" onclick="buyNowDetails()">
                {{ $keywords['Buy Now'] ?? __('Buy Now') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('styles')
  @if ($shop_settings->catalog_mode != 1)
    <style>
      .sticky-bottom-cart-bar {
        position: fixed;
        bottom: -180px;
        left: 0;
        width: 100%;
        background: #ffffff;
        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.12);
        z-index: 998;
        padding: 8px 12px;
        transition: bottom 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-top: 1px solid #eaeaea;
      }
      .sticky-bottom-cart-bar.show {
        bottom: 56px;
      }
      .sticky-cart-wrapper {
        max-width: 540px;
        margin: 0 auto;
        overflow: hidden;
      }
      .sticky-product-info {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        margin-bottom: 4px;
        line-height: 1.2;
        text-align: center;
      }
      .sticky-product-title {
        font-size: 13px;
        font-weight: 600;
        color: #333333;
        display: inline;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .sticky-product-variant-sep {
        color: #999;
        margin: 0 4px;
      }
      .sticky-product-selected-variant {
        font-size: 12px;
        font-weight: 500;
        color: #666666;
      }
      .sticky-cart-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-top: 2px;
      }
      .sticky-quantity {
        display: flex;
        align-items: center;
        border: 1px solid #e1e1e1;
        border-radius: 4px;
        height: 36px;
        background: #ffffff;
        flex-shrink: 0;
      }
      .sticky-quantity .quantity-btn {
        width: 28px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 11px;
        color: #333333;
        user-select: none;
      }
      .sticky-quantity .quantity_field {
        width: 30px;
        height: 100%;
        border: none;
        text-align: center;
        font-size: 12px;
        font-weight: 600;
        color: #333333;
        padding: 0;
        background: transparent;
        outline: none;
      }
      .sticky-actions {
        flex: 1;
        display: flex;
        gap: 6px;
        min-width: 0;
      }
      .sticky-actions button {
        flex: 1;
        height: 36px;
        border: none;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.2px;
        text-transform: uppercase;
        padding: 0 6px;
        border-radius: 4px;
        transition: all 0.2s ease-in-out;
        outline: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .sticky-add-to-cart {
        background-color: #1e1e1e;
        color: #ffffff;
      }
      .sticky-add-to-cart:hover {
        background-color: #333333;
      }
      .sticky-buy-now {
        background-color: #0d6efd;
        color: #ffffff;
      }
      .sticky-buy-now:hover {
        background-color: #0b5ed7;
      }

      @media (max-width: 380px) {
        .sticky-bottom-cart-bar {
          padding: 6px 8px;
        }
        .sticky-product-title {
          font-size: 11px;
        }
        .sticky-quantity {
          height: 32px;
        }
        .sticky-quantity .quantity-btn {
          width: 24px;
        }
        .sticky-quantity .quantity_field {
          width: 26px;
          font-size: 11px;
        }
        .sticky-actions button {
          height: 32px;
          font-size: 10px;
          padding: 0 4px;
          letter-spacing: 0;
        }
      }
    </style>
  @endif
@endsection

@section('scripts')
  @if ($userBs->is_disqus == 1 && in_array('Disqus', $packagePermissions) && $shop_settings->disqus_comment_system == 1)
    <script>
      "use strict";
      (function() {
        var d = document,
          s = d.createElement('script');
        s.src = 'https://{{ $userBs->disqus_shortname }}.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
      })();
    </script>
  @endif

  @if ($shop_settings->catalog_mode != 1)
    <script>
      $(document).ready(function() {
        // Sync main quantity to sticky quantity
        $(document).on('click', '.item_quantity_details .plus, .item_quantity_details .minus', function() {
          setTimeout(function() {
            var mainQty = $(".item_quantity_details input[name='cart-amount']").val();
            $(".sticky_quantity_field").val(mainQty);
          }, 50);
        });

        // Sync sticky quantity to main quantity and run update price
        $(document).on('click', '.sticky-quantity .plus', function() {
          var $input = $(".sticky_quantity_field");
          var currval = parseInt($input.val());
          var newval = currval + 1;
          $input.val(newval);
          $(".item_quantity_details input[name='cart-amount']").val(newval);
          totalPriceDetails2(newval);
        });

        $(document).on('click', '.sticky-quantity .minus', function() {
          var $input = $(".sticky_quantity_field");
          var currval = parseInt($input.val());
          if (currval > 1) {
            var newval = currval - 1;
            $input.val(newval);
            $(".item_quantity_details input[name='cart-amount']").val(newval);
            totalPriceDetails2(newval);
          }
        });

        $(document).on('input', '.sticky_quantity_field', function() {
          var val = $(this).val();
          $(".item_quantity_details input[name='cart-amount']").val(val);
          totalPriceDetails2(val);
        });

        // Function to update selected variant names in sticky bottom bar
        function updateStickySelectedVariant() {
          var selected = [];
          $('#variantListULDetails .variantUL li input:checked').each(function() {
            var val = $(this).val();
            var parts = val.split(":");
            selected.push(parts[0]);
          });
          if (selected.length > 0) {
            $('.sticky-product-variant-sep').removeClass('d-none');
            $('.sticky-product-selected-variant').text(selected.join(', '));
          } else {
            $('.sticky-product-variant-sep').addClass('d-none');
            $('.sticky-product-selected-variant').text('');
          }
        }

        // Initialize and listen to change
        updateStickySelectedVariant();
        $(document).on('change', '.product-variant', function() {
          updateStickySelectedVariant();
        });

        // Scroll listener to show/hide sticky bar
        $(window).scroll(function() {
          var mainBtn = $('.item_quantity_details');
          if (mainBtn.length) {
            var btnTop = mainBtn.offset().top + mainBtn.outerHeight();
            if ($(window).scrollTop() > btnTop) {
              $('.sticky-bottom-cart-bar').addClass('show');
            } else {
              $('.sticky-bottom-cart-bar').removeClass('show');
            }
          }
        });
      });

      // Buy Now functionality
      function buyNowDetails() {
        $(".request-loader").addClass("show");
        let $input = $(".item_quantity_details input");
        let qty = parseInt($input.val());
        let item_id = $('#details_item_id').val();
        let url = mainurl + "/add-to-cart/" + item_id;
        let final_price = totalPriceDetails2(qty);

        if (stErr > 0) {
          stErrMsg.forEach(msg => {
            toastr["error"](msg);
          });
          $(".request-loader").removeClass("show");
        } else {
          let cartUrl = url;

          $.get(cartUrl + ',,,' + qty + ',,,' + final_price + ',,,' + JSON.stringify(variant), function (res) {
            if (res.message) {
              window.location.href = "{{ route('front.user.checkout', getParam()) }}";
            } else {
              toastr["error"](res.error);
              $(".request-loader").removeClass("show");
            }
          });
        }
      }
    </script>
  @endif
@endsection
