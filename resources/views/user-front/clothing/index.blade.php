@extends('user-front.layout')

@section('meta-description', !empty($seo) ? $seo->home_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->home_meta_keywords : '')
@section('page-title', $keywords['Home'] ?? __('Home'))
@section('og-meta')
  <meta property="og:title" content="{{ $user->username }}">
  <meta property="og:image" content="{{ !empty($userBs->logo) ? asset('assets/front/img/user/' . $userBs->logo) : '' }}">
@endsection

@php
  $homepageCategories = $item_categories->take(5);
  $homepageLatestItems = isset($latest_items) ? $latest_items->take(8) : collect();
  
  $validBestItems = collect();
  if (isset($top_selling) && count($top_selling) > 0) {
      foreach ($top_selling as $selling) {
          if (!is_null($selling->item) && !is_null($selling->item->itemContents[0] ?? null)) {
              $validBestItems->push($selling);
          }
      }
  }
  
  $homepageBestItems = count($validBestItems) > 0 ? $validBestItems->take(8) : $homepageLatestItems;
@endphp

@section('styles')
<style>
/* ---- Clothing Home Overrides ---- */
.cl-section { padding: 60px 0; }
.cl-section-sm { padding: 60px 0; }

@media (max-width : 767px) {
  .cl-section { padding: 6px 0 !important; }
  .cl-section-sm { padding: 6px 0 !important; }
}

/* Section heading */
.cl-heading {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 28px;
  gap: 12px;
}
.cl-heading h2 {
  font-size: 1.35rem;
  font-weight: 800;
  letter-spacing: .06em;
  text-transform: uppercase;
  color: #111;
  margin: 0;
}
.cl-view-all {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #111;
  text-decoration: none;
  white-space: nowrap;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}
.cl-view-all:hover { color: #555; }
</style>
@endsection

@section('content')
<div class="clothing-home">

  {{-- ═══════════════════════════════════════════
       HERO SECTION
  ═══════════════════════════════════════════ --}}
  @if($ubs->hero_section == 1)
  <section class="header-next" style="padding: 0; margin-bottom: 40px; margin-top: 140px;">
    <div class="container">
      @php
        $heroSlides = collect();

        if (isset($sliders) && count($sliders) > 0) {
          $heroSlides = $sliders->map(function ($slide) {
            return [
              'kicker' => __('NEW SEASON'),
              'title' => $slide->title,
              'title_html' => null,
              'subtitle' => $slide->subtitle,
              'text' => $slide->text,
              'primary_btn_name' => $slide->btn_name,
              'primary_btn_url' => route('front.user.shop', getParam()),
              'image_url' => !empty($slide->img) ? asset('assets/front/img/hero_slider/' . $slide->img) : null
            ];
          });
        }

        if ($heroSlides->isEmpty()) {
          $heroSlides = collect([
            [
              'kicker' => $keywords['New Season'] ?? __('NEW SEASON'),
              'title' => null,
              'title_html' => !is_null(@$static_hero_section->title) ? $static_hero_section->title : 'Style <em>Redefined</em> For You',
              'subtitle' => @$static_hero_section->subtitle,
              'text' => null,
              'primary_btn_name' => __('SHOP MEN'),
              'primary_btn_url' => route('front.user.shop', getParam()),
              'image_url' => !is_null(@$static_hero_section->background_image)
                ? asset('assets/front/img/hero-section/' . $static_hero_section->background_image)
                : asset('assets/user-front/images/fashion/banner/banner-1.jpg')
            ]
          ]);
        }

        $heroAutoplay = $heroSlides->count() > 1;
      @endphp

      <div class="clothing-hero-slider" data-hero-autoplay="{{ $heroAutoplay ? 'true' : 'false' }}" data-aos="fade-up" data-aos-delay="60">
        @foreach($heroSlides as $heroSlide)
          <div class="clothing-hero-slide">
            <div class="clothing-hero-shell">
              <div class="clothing-hero-copy">
                <span class="clothing-hero-kicker">{{ $heroSlide['kicker'] }}</span>

                @if(!empty($heroSlide['title_html']))
                  <h1>{!! $heroSlide['title_html'] !!}</h1>
                @elseif(!empty($heroSlide['title']))
                  <h1>{{ $heroSlide['title'] }}</h1>
                @else
                  <h1>Style <em>Redefined</em> For You</h1>
                @endif

                <p>
                  @if(!empty($heroSlide['subtitle']))
                    {{ $heroSlide['subtitle'] }}
                  @elseif(!empty($heroSlide['text']))
                    {{ $heroSlide['text'] }}
                  @else
                    Timeless designs. Premium fabrics.<br>Made for the modern you.
                  @endif
                </p>

                <div class="hero-visual-frame">
                  @if(!empty($heroSlide['image_url']))
                    <img class="lazyload blur-up"
                      src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ $heroSlide['image_url'] }}"
                      alt="hero banner">
                  @else
                    <img class="lazyload blur-up"
                      src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/user-front/images/fashion/banner/banner-1.jpg') }}"
                      alt="hero banner">
                  @endif
                </div>

                <div class="clothing-hero-actions">
                  <a href="{{ route('front.user.shop', getParam()) }}" class="clothing-btn-dark">
                    {{ !empty($heroSlide['primary_btn_name']) ? $heroSlide['primary_btn_name'] : __('SHOP MEN') }} <i class="fal fa-arrow-right"></i>
                  </a>
                  <a href="{{ route('front.user.shop', getParam()) }}" class="clothing-btn-light">{{ __('SHOP WOMEN') }} <i class="fal fa-arrow-right"></i></a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- after_hero additional sections --}}
  @if(count($after_hero) > 0)
    @foreach($after_hero as $cusHero)
      @php $cusHeroContent = App\Models\User\AdditionalSectionContent::where([['language_id',$uLang],['addition_section_id',$cusHero->id]])->first(); @endphp
      @includeIf('user-front.additional-section', ['data'=>$cusHeroContent,'possition'=>$cusHero->possition])
    @endforeach
  @endif

  {{-- ═══════════════════════════════════════════
       CATEGORIES SECTION
  ═══════════════════════════════════════════ --}}
  <section class="cl-section">
    <div class="container">
      <div class="cl-heading" data-aos="fade-up">
        <h2>{{ $keywords['Categories'] ?? 'CATEGORIES' }}</h2>
        <a class="cl-view-all" href="{{ route('front.user.shop', getParam()) }}">VIEW ALL <i class="fal fa-arrow-right"></i></a>
      </div>

      @if(count($homepageCategories) == 0)
        <p class="text-center text-muted py-4">{{ $keywords['No Categories Found'] ?? 'No Categories Found' }}</p>
      @else
        <div class="clothing-categories-grid">
          @foreach($homepageCategories as $cat)
            <a href="{{ route('front.user.shop', [getParam(), 'category='.$cat->slug]) }}"
               class="clothing-category-card" style="min-height:200px;"
               data-aos="fade-up" data-aos-delay="{{ $loop->index * 70 }}">
              <img class="lazyload blur-up"
                src="{{ !empty($cat->image) ? asset('assets/front/img/user/items/categories/'.$cat->image) : asset('assets/front/images/placeholder.png') }}"
                data-src="{{ asset('assets/front/img/user/items/categories/'.$cat->image) }}"
                alt="{{ $cat->name }}"
                style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
              <div class="clothing-card-overlay">
                <h3>{{ strtoupper($cat->name) }}</h3>
                <span>SHOP NOW <i class="fal fa-arrow-right"></i></span>
              </div>
            </a>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       NEW ARRIVALS
  ═══════════════════════════════════════════ --}}
  <section class="cl-section" style="padding-top:0;">
    <div class="container">
      <div class="cl-heading" data-aos="fade-up">
        <h2>{{ $keywords['New Arrivals'] ?? 'NEW ARRIVALS' }}</h2>
        <a class="cl-view-all" href="{{ route('front.user.shop', getParam()) }}">VIEW ALL <i class="fal fa-arrow-right"></i></a>
      </div>

      @if(count($homepageLatestItems) == 0)
        <p class="text-center text-muted py-4">{{ $keywords['No Products Found'] ?? 'No Products Found' }}</p>
      @else
        <div class="products-row-clothing">
          @foreach($homepageLatestItems as $product)
            @php
              $pContent = $product->itemContents[0] ?? null;
              if(is_null($pContent)) continue;
              $fi = flashAmountStatus($product->id, $product->current_price);
              $dp = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol,
                    currency_converter($fi['status'] ? $fi['amount'] : $product->current_price));
            @endphp
            <div class="product-default-10" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
              {{-- Product Image --}}
              <div class="product-img">
                <a href="{{ route('front.user.productDetails',[getParam(),'slug'=>$pContent->slug]) }}">
                  <img class="lazyload blur-up default-img"
                    src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/'.$product->thumbnail) }}"
                    alt="{{ $pContent->title }}">
                </a>
                {{-- Badge --}}
                <div class="product-badge">
                  <span class="badge-new">NEW</span>
                  @if(@$fi['status'])
                    <span class="badge-sale">SALE</span>
                  @endif
                </div>
              </div>
              {{-- Product details --}}
              <div class="product-details">
                <h3 class="product-title">
                  <a href="{{ route('front.user.productDetails',[getParam(),'slug'=>$pContent->slug]) }}">{{ $pContent->title }}</a>
                </h3>
                <div class="product-price">
                  <span class="new-price">{{ $dp }}</span>
                  @if($product->previous_price > $product->current_price)
                    <span class="old-price">{{ symbolPrice($userCurrentCurr->symbol_position,$userCurrentCurr->symbol,currency_converter($product->previous_price)) }}</span>
                  @endif
                </div>
              </div>
              {{-- Action icons --}}
              <div class="btn-icon-group btn-inline">
                @if($shop_settings->catalog_mode != 1)
                  <a href="javascript:void(0)"
                    class="btn-icon cart-link"
                    data-href="{{ route('front.user.add.cart', ['id' => $product->id, getParam()]) }}"
                    data-title="{{ $pContent->title }}"
                    data-item_id="{{ $product->id }}"
                    data-current_price="{{ currency_converter($fi['status'] ? $fi['amount'] : $product->current_price) }}"
                    data-variations="{{ check_variation($product->id) > 0 ? 'yes' : 'no' }}"
                    data-totalvari="{{ check_variation($product->id) }}"
                    data-language_id="{{ $uLang }}"
                    title="Add to Cart"><i class="far fa-shopping-cart"></i></a>
                @endif
                <a href="javascript:void(0)" data-url="{{ route('front.user.add.wishlist', ['id' => $product->id, getParam()]) }}" class="btn-icon btn-wishlist" title="Wishlist"><i class="fal fa-heart"></i></a>
                <a href="javascript:void(0)" class="btn-icon quick-view-link" data-url="{{ route('front.user.productDetails.quickview', ['slug' => $pContent->slug, getParam()]) }}" data-slug="{{ $pContent->slug }}" title="Quick View"><i class="fal fa-eye"></i></a>
                <a href="javascript:void(0)" onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product->id, getParam()]) }}')" class="btn-icon" title="Compare"><i class="fal fa-random"></i></a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       COLLECTION BANNERS (4 COLUMNS)
  ═══════════════════════════════════════════ --}}
  <section class="cl-section" style="padding-top:0;">
    <div class="container">
      @php
        $collectionLabels = ['SUMMER COLLECTION', 'STREETWEAR EDIT', 'FORMAL WEAR', 'SPORTSWEAR'];
        $collectionCats = $item_categories;
      @endphp
      @if(count($collectionCats) > 0)
        <div class="clothing-collection-slider" data-aos="fade-up" data-aos-delay="70">
          @foreach($collectionCats as $colCat)
            @php $colLabel = $collectionLabels[$loop->index] ?? strtoupper($colCat->name); @endphp
            <div class="px-2">
              <a href="{{ route('front.user.shop', [getParam(), 'category='.$colCat->slug]) }}" 
                 class="clothing-collection-card">
                <img class="lazyload blur-up"
                  src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/front/img/user/items/categories/'.$colCat->image) }}"
                  alt="{{ $colLabel }}">
                <div class="clothing-collection-content">
                  <h3>{{ $colLabel }}</h3>
                  <span class="clothing-collection-link">SHOP NOW <i class="fal fa-arrow-right"></i></span>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       FLASH / PROMO BANNER (special offer timer)
  ═══════════════════════════════════════════ --}}
  @if($ubs->flash_section == 1)
    @includeIf('user-front.clothing.partials.flash_content')
  @endif

  {{-- ═══════════════════════════════════════════
       BEST SELLERS
  ═══════════════════════════════════════════ --}}
  <section class="cl-section" style="background:#f8f5f0;padding-top:6px;">
    <div class="container">
      <div class="cl-heading" data-aos="fade-up">
        <h2>{{ $keywords['Best Sellers'] ?? 'BEST SELLERS' }}</h2>
        <a class="cl-view-all" href="{{ route('front.user.shop', getParam()) }}">VIEW ALL <i class="fal fa-arrow-right"></i></a>
      </div>

      @if(count($homepageBestItems) == 0)
        <p class="text-center text-muted py-4">{{ $keywords['No Products Found'] ?? 'No Products Found' }}</p>
      @else
        <div class="products-row-clothing">
          @foreach($homepageBestItems as $selling)
            @php
              $bProd = isset($selling->item) ? $selling->item : $selling;
              $bCont = !is_null($bProd) ? ($bProd->itemContents[0] ?? null) : null;
              if(is_null($bProd) || is_null($bCont)) continue;
              $fi2 = flashAmountStatus($bProd->id, $bProd->current_price);
              $dp2 = symbolPrice($userCurrentCurr->symbol_position, $userCurrentCurr->symbol,
                     currency_converter($fi2['status'] ? $fi2['amount'] : $bProd->current_price));
            @endphp
            <div class="product-default-10" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
              <div class="product-img">
                <a href="{{ route('front.user.productDetails',[getParam(),'slug'=>$bCont->slug]) }}">
                  <img class="lazyload blur-up default-img"
                    src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/'.$bProd->thumbnail) }}"
                    alt="{{ $bCont->title }}">
                </a>
                <div class="product-badge">
                  @if($bProd->previous_price > $bProd->current_price)
                    <span class="badge-sale">SALE</span>
                  @endif
                </div>
              </div>
              <div class="product-details">
                <h3 class="product-title">
                  <a href="{{ route('front.user.productDetails',[getParam(),'slug'=>$bCont->slug]) }}">{{ $bCont->title }}</a>
                </h3>
                <div class="product-price">
                  <span class="new-price">{{ $dp2 }}</span>
                  @if($bProd->previous_price > $bProd->current_price)
                    <span class="old-price">{{ symbolPrice($userCurrentCurr->symbol_position,$userCurrentCurr->symbol,currency_converter($bProd->previous_price)) }}</span>
                  @endif
                </div>
              </div>
              <div class="btn-icon-group btn-inline">
                @if($shop_settings->catalog_mode != 1)
                  <a href="javascript:void(0)"
                    class="btn-icon cart-link"
                    data-href="{{ route('front.user.add.cart', ['id' => $bProd->id, getParam()]) }}"
                    data-title="{{ $bCont->title }}"
                    data-item_id="{{ $bProd->id }}"
                    data-current_price="{{ currency_converter($fi2['status'] ? $fi2['amount'] : $bProd->current_price) }}"
                    data-variations="{{ check_variation($bProd->id) > 0 ? 'yes' : 'no' }}"
                    data-totalvari="{{ check_variation($bProd->id) }}"
                    data-language_id="{{ $uLang }}"
                    title="Add to Cart"><i class="far fa-shopping-cart"></i></a>
                @endif
                <a href="javascript:void(0)" class="btn-icon quick-view-link" data-url="{{ route('front.user.productDetails.quickview', ['slug' => $bCont->slug, getParam()]) }}" data-slug="{{ $bCont->slug }}" title="Quick View"><i class="fal fa-eye"></i></a>
                <a href="javascript:void(0)" data-url="{{ route('front.user.add.wishlist', ['id' => $bProd->id, getParam()]) }}" class="btn-icon btn-wishlist" title="Wishlist"><i class="fal fa-heart"></i></a>
                <a href="javascript:void(0)" onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $bProd->id, getParam()]) }}')" class="btn-icon" title="Compare"><i class="fal fa-random"></i></a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       TRUST STRIP (free shipping section)
  ═══════════════════════════════════════════ --}}
  <section style="padding:0;">
    <div class="container-fluid px-0">
      <div class="clothing-trust-strip">
        @foreach([
          ['icon'=>'fal fa-truck',  'title'=>'FREE SHIPPING',   'text'=>'Free shipping on orders over ₹75'],
          ['icon'=>'fal fa-undo',   'title'=>'EASY RETURNS',    'text'=>'30-day return policy hassle free'],
          ['icon'=>'fal fa-lock',   'title'=>'SECURE PAYMENT',  'text'=>'100% secure payment guaranteed'],
          ['icon'=>'fal fa-award',  'title'=>'PREMIUM QUALITY', 'text'=>'High quality materials you can trust'],
        ] as $tc)
          <div class="clothing-trust-item">
            <i class="{{ $tc['icon'] }}"></i>
            <div>
              <h4>{{ $tc['title'] }}</h4>
              <p>{{ $tc['text'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       TESTIMONIALS (Reviews auto scroll)
  ═══════════════════════════════════════════ --}}
  @php
    $clTestimonials = [
      [
        'rating' => 5,
        'comment' => 'Absolutely love the quality of their clothing! The fabrics are premium, and the fit is perfect. Highly recommend!',
        'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=150&auto=format&fit=crop',
        'name' => 'Sarah Jenkins',
        'occupation' => 'Fashion Designer'
      ],
      [
        'rating' => 5,
        'comment' => 'The customer service is outstanding, and shipping was incredibly fast. The dress fits like a glove and feels so luxurious.',
        'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150&auto=format&fit=crop',
        'name' => 'Michael Chang',
        'occupation' => 'Creative Director'
      ],
      [
        'rating' => 5,
        'comment' => 'I have bought multiple items from them, and each time the quality exceeds my expectations. Truly style redefined.',
        'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=150&auto=format&fit=crop',
        'name' => 'Emma Watson',
        'occupation' => 'Stylist'
      ],
      [
        'rating' => 5,
        'comment' => 'Very comfortable denim jacket and the sunglasses are top tier. Will definitely be ordering from here again soon.',
        'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=150&auto=format&fit=crop',
        'name' => 'David Miller',
        'occupation' => 'Photographer'
      ],
      [
        'rating' => 5,
        'comment' => 'Premium quality fabric, minimalist aesthetic, and fast checkout. The online shopping experience was very smooth.',
        'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=150&auto=format&fit=crop',
        'name' => 'Sophia Martinez',
        'occupation' => 'Content Creator'
      ],
      [
        'rating' => 5,
        'comment' => 'I am very picky about my streetwear, but their designs nailed the fit and details. 10/10 purchase.',
        'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=150&auto=format&fit=crop',
        'name' => 'Alex Rivera',
        'occupation' => 'Music Producer'
      ],
      [
        'rating' => 5,
        'comment' => 'A wonderful selection of winter and summer season arrivals. Easy exchange process when I needed a different size.',
        'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=150&auto=format&fit=crop',
        'name' => 'Olivia Taylor',
        'occupation' => 'Model'
      ],
      [
        'rating' => 5,
        'comment' => 'The attention to detail in their packaging and apparel is impressive. Feels like a luxury boutique at fair prices.',
        'image' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=150&auto=format&fit=crop',
        'name' => 'James Harrison',
        'occupation' => 'Marketing Manager'
      ]
    ];
  @endphp
  @if(count($clTestimonials) > 0)
  <section class="cl-section">
    <div class="container">
      <div class="cl-heading" style="justify-content:center; margin-bottom: 35px;" data-aos="fade-up">
        <h2>WHAT OUR CUSTOMERS SAY</h2>
      </div>
      <div class="testimonial-slider-clothing" data-aos="fade-up" data-aos-delay="80">
        @foreach($clTestimonials as $t)
          <div class="px-2">
            <div style="background:#fff;border:1px solid #e5e1db;padding:28px;height:100%;border-radius:8px;">
              <div style="color:#f0c040;margin-bottom:12px;font-size:13px;">
                @for($s=0;$s<($t['rating']??5);$s++)<i class="fas fa-star"></i>@endfor
              </div>
              <p style="color:#555;font-size:14px;line-height:1.7;margin-bottom:20px;min-height:80px;">"{{ $t['comment'] }}"</p>
              <div style="display:flex;align-items:center;gap:12px;">
                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ $t['image'] }}"
                  alt="{{ $t['name'] }}"
                  style="width:46px;height:46px;border-radius:50%;object-fit:cover;border:2px solid #e5e1db;">
                <div>
                  <strong style="font-size:14px;color:#111;">{{ $t['name'] }}</strong><br>
                  <span style="font-size:12px;color:#888;">{{ $t['occupation'] }}</span>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- ═══════════════════════════════════════════
       FOLLOW US ON INSTAGRAM
  ═══════════════════════════════════════════ --}}
  <section class="cl-section" style="padding-bottom:6px;padding-top:0;">
    <div class="container">
      <div class="cl-heading" style="justify-content:center; flex-direction:column; text-align:center; margin-bottom:35px;" data-aos="fade-up">
        <span style="font-size:11px; font-weight:700; letter-spacing:2px; color:#8c7f70; text-transform:uppercase; margin-bottom:8px;">INSTAGRAM</span>
        <h2 style="font-size:1.8rem; font-weight:700; font-family:'Jost', sans-serif; margin-bottom:4px;">FOLLOW OUR JOURNAL</h2>
        <a href="https://instagram.com" target="_blank" style="font-size:13px; color:#8c7f70; font-weight:500; text-decoration:none;">@ecom builder_store</a>
      </div>
      
      @php
        $instaCats = $item_categories->take(5);
      @endphp
      @if(count($instaCats) > 0)
        <div class="clothing-instagram-grid" data-aos="fade-up" data-aos-delay="80">
          @foreach($instaCats as $iCat)
            <a href="https://instagram.com" target="_blank" class="instagram-image-card">
              <img class="lazyload blur-up"
                src="{{ asset('assets/front/images/placeholder.png') }}"
                data-src="{{ asset('assets/front/img/user/items/categories/'.$iCat->image) }}"
                alt="instagram">
              <div class="insta-overlay">
                <i class="fab fa-instagram"></i>
              </div>
            </a>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  {{-- Modals --}}
  @include('user-front.partials.variation-modal')
  <div class="modal custom-modal quick-view-modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content radius-sm">
        <button type="button" class="close_modal_btn" data-bs-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
        <div class="modal-body">
          <div class="product-single-default">
            <div class="row gx-0" id="quickViewModalContent"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>{{-- .clothing-home --}}
@endsection

@section('scripts')
<script>
$(document).ready(function() {
  var $heroSlider = $('.clothing-hero-slider');
  if ($heroSlider.length > 0 && !$heroSlider.hasClass('slick-initialized')) {
    var enableHeroAutoplay = String($heroSlider.data('hero-autoplay')) === 'true';

    $heroSlider.slick({
      dots: enableHeroAutoplay,
      arrows: false,
      autoplay: enableHeroAutoplay,
      autoplaySpeed: 4500,
      speed: 700,
      infinite: enableHeroAutoplay,
      pauseOnHover: true,
      pauseOnFocus: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      adaptiveHeight: false,
      rtl: $('html').attr('dir') === 'rtl'
    });
  }

  if ($('.testimonial-slider-clothing').length > 0) {
    $('.testimonial-slider-clothing').slick({
      dots: true,
      arrows: false,
      autoplay: true,
      autoplaySpeed: 3000,
      slidesToShow: 3,
      slidesToScroll: 1,
      rtl: $('html').attr('dir') === 'rtl',
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
  }

  if ($('.clothing-collection-slider').length > 0) {
    $('.clothing-collection-slider').slick({
      dots: true,
      arrows: false,
      autoplay: true,
      autoplaySpeed: 3000,
      slidesToShow: 4,
      slidesToScroll: 1,
      rtl: $('html').attr('dir') === 'rtl',
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
  }
});
</script>
@endsection
