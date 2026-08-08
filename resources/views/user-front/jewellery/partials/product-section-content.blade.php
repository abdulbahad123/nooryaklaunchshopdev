<!-- products Section Start -->
<section class="products-section-v7 pb-100 overflow-hidden lazy">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title title-inline flex-wrap mb-20" data-aos="fade-down" data-aos-delay="100">
          <h2 class="title h2 mb-20">{{ $tabs[$i]->name }}</h2>
          <a href="{{ route('front.user.shop', getParam()) }}" class="btn btn-md btn-primary  mb-20" title="Show All"
            target="_self">{{ $keywords['Show More'] ?? __('Show More') }}</a>
        </div>
      </div>
      <div class="col-xl-8">
        @for ($skeleton = 0; $skeleton <= 1; $skeleton++)
          <div class="border mb-20 p-2" data-aos="fade-up" data-aos-delay="100">
            <div class="row row-gap-20">
              @for ($skelet = 0; $skelet <= 1; $skelet++)
                <div class="col-6">
                  <!-- product-inline -->
                  <div class="product-default-8-inline">
                    <figure class="product-img skeleton skeleton-big-img"></figure>
                    <div class="product-details">
                      <span class="product-category skeleton skeleton-title"></span>
                      <h5 class="product-title lc-2 fw-medium mb-2 skeleton skeleton-title"></h5>
                      <!-- Rateing -->
                      <div class="d-flex justify-content-start align-items-center mb-10 skeleton skeleton-ratings">
                      </div>
                      <!-- product-price -->
                      <div class="product-price mt-10 mb-3">
                        <span class="new-price fw-semibold skeleton skeleton-price"></span>
                        <span class="old-price fw-medium text-decoration-line-through skeleton skeleton-price"></span>
                      </div>

                      <!-- btn-icon-group -->
                      <div class="btn-icon-group btn-inline">
                        <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                        <button type="button" class="btn btn-icon skeleton skeleton-btn-icon"></button>
                        <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                        <a href="javascript:void(0)" class="btn btn-icon skeleton skeleton-btn-icon"></a>
                      </div>
                    </div>
                  </div>
                </div>
              @endfor
            </div>
          </div>
        @endfor
      </div>
      @if ($ubs->right_banner_section == 1)
        @if ($banners)
          @php
            $totalBanners = $banners->where('position', 'right')->values();
            $rightBanner = $totalBanners[$i] ?? null;
          @endphp
          @if (!is_null($rightBanner))
            <div class="col-xl-4" data-aos="fade-left" data-aos-delay="100">
              <!-- banner-md -->
              <div class="banner-lg mb-30 ratio ratio-1-1">
                <img class="lazyload bg-img blur-up"
                  data-src="{{ asset('assets/front/img/user/banners/' . $rightBanner['banner_img']) }}" alt="">
                <div class="banner-content">
                  <div class="content-inner">
                    <h3 class="title fw-bold mb-3">{{ $rightBanner['title'] }}</h3>
                    <p class="mb-1 small fw-medium mb-20">{{ $rightBanner['subtitle'] }}</p>
                    @if (!is_null($rightBanner['banner_url']) && !is_null($rightBanner['button_text']))
                      <a href="{{ $rightBanner['banner_url'] }}" class="btn btn-primary btn-md text-sm fw-semibold">
                        {{ $rightBanner['button_text'] }}
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endif
        @endif
      @endif
    </div>
  </div>
</section>
<section class="products-section-v7 pb-100 overflow-hidden actual-content">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title title-inline flex-wrap mb-20" data-aos="fade-down" data-aos-delay="100">
          <h2 class="title h2 mb-20">{{ $tabs[$i]->name }}</h2>
          <a href="{{ route('front.user.shop', getParam()) }}" class="btn btn-md btn-primary  mb-20" title="Show All"
            target="_self">{{ $keywords['Show More'] ?? __('Show More') }}</a>
        </div>
      </div>
      <div class="col-xl-8">
        @php
          $products = json_decode($tabs[$i]->products, true);
        @endphp
        @if (!is_null($products))
          @foreach (array_chunk($products, 2) as $chunk)
            @php
              $product_details1 = \App\Models\User\UserItem::where('id', $chunk[0])
                  ->with([
                      'itemContents' => function ($q) use ($uLang) {
                          $q->where('language_id', '=', $uLang);
                      },
                      'sliders',
                  ])
                  ->first();

              $product_details2 = isset($chunk[1])
                  ? \App\Models\User\UserItem::where('id', $chunk[1])
                      ->with([
                          'itemContents' => function ($q) use ($uLang) {
                              $q->where('language_id', '=', $uLang);
                          },
                          'sliders',
                      ])
                      ->first()
                  : null;
            @endphp
            <div class="border mb-20 p-2" data-aos="fade-up" data-aos-delay="100">
              <div class="row row-gap-20">
                @if ($product_details1 && count($product_details1->itemContents) > 0)
                  <div class="col-6">
                    @includeIf('user-front.jewellery.partials.product-inline', [
                        'product' => $product_details1,
                    ])
                  </div>
                @endif

                @if ($product_details2 && count($product_details2->itemContents) > 0)
                  <div class="col-6">
                    <!-- product-inline -->
                    @includeIf('user-front.jewellery.partials.product-inline', [
                        'product' => $product_details2,
                    ])
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        @endif

      </div>
      @if ($ubs->right_banner_section == 1)
        @if ($banners)
          @php
            $totalBanners = $banners->where('position', 'right')->values();
            $rightBanner = $totalBanners[$i] ?? null;
          @endphp
          @if (!is_null($rightBanner))
            <div class="col-xl-4" data-aos="fade-left" data-aos-delay="100">
              <!-- banner-md -->
              <div class="banner-lg mb-30 ratio ratio-1-1">
                <img class="lazyload bg-img blur-up"
                  data-src="{{ asset('assets/front/img/user/banners/' . $rightBanner['banner_img']) }}" alt="">
                <div class="banner-content">
                  <div class="content-inner">
                    <h3 class="title fw-bold mb-3">{{ $rightBanner['title'] }}</h3>
                    <p class="mb-1 small fw-medium mb-20">{{ $rightBanner['subtitle'] }}</p>
                    @if (!is_null($rightBanner['banner_url']) && !is_null($rightBanner['button_text']))
                      <a href="{{ $rightBanner['banner_url'] }}" class="btn btn-primary btn-md text-sm fw-semibold">
                        {{ $rightBanner['button_text'] }}
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endif
        @endif
      @endif
    </div>
  </div>
</section>
<!-- products Section End -->
