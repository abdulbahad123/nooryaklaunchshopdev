@extends('front.layout')

@section('pagename')
  - {{ $pageHeading ?? __('Shops') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->listing_page_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->listing_page_meta_keyword : '')

@section('styles')
<style>
  /* Completely hide the default grey parallax breadcrumb header */
  .page-title-area {
      display: none !important;
  }
  .shops-hero {
      padding-top: 50px !important;
  }
@media (max-width: 1198px){

.shops-hero {
    padding-top: 95px !important;
}

}

@media (min-width: 1600px) {
  .shops-hero {
      padding-top: 130px !important;
  }
}


</style>
@endsection

@section('content')

  @php
    // Fetch all theme template screenshots keyed by theme name
    $themeTemplates = \App\Models\User::where('preview_template', 1)
        ->whereHas('basic_setting')
        ->with('basic_setting')
        ->get()
        ->mapWithKeys(function($item) {
            $themeName = $item->basic_setting ? $item->basic_setting->theme : null;
            return $themeName ? [$themeName => $item->template_img] : [];
        })
        ->toArray();

    // Get all user themes to prevent N+1 queries in the loop
    $userIds = $users->pluck('id')->toArray();
    $userThemes = \App\Models\User\BasicSetting::whereIn('user_id', $userIds)
        ->pluck('theme', 'user_id')
        ->toArray();

    // Friendly display labels for known themes.
    $themeLabelMap = [
      'vegetables' => ['category' => 'grocery', 'name' => __('Grocery')],
      'grocery' => ['category' => 'grocery', 'name' => __('Grocery')],
      'manti' => ['category' => 'multipurpose', 'name' => __('Multipurpose')],
      'multipurpose' => ['category' => 'multipurpose', 'name' => __('Multipurpose')],
      'fashion' => ['category' => 'fashion', 'name' => __('Fashion')],
      'apparel' => ['category' => 'fashion', 'name' => __('Fashion')],
      'jewellery' => ['category' => 'fashion', 'name' => __('Fashion')],
      'kids' => ['category' => 'fashion', 'name' => __('Fashion')],
      'electronics' => ['category' => 'electronics', 'name' => __('Electronics')],
      'gadgets' => ['category' => 'electronics', 'name' => __('Electronics')],
      'beauty' => ['category' => 'beauty', 'name' => __('Beauty')],
      'cosmetics' => ['category' => 'beauty', 'name' => __('Beauty')],
      'skinflow' => ['category' => 'beauty', 'name' => __('Beauty')],
      'furniture' => ['category' => 'furniture', 'name' => __('Furniture')],
    ];
  @endphp

  <!--====== Start Shops Hero Section ======-->
  <section class="shops-hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="shops-hero-content" data-aos="fade-right">
            <span class="shops-hero-badge">{{ __('Customer Stores') }}</span>
            <h1 class="shops-hero-title">
              Discover Stores <br>Built on <span>Launchshop.in</span>
            </h1>
            <p class="shops-hero-text">
              Real merchants. Real success. Real stories. <br>
              Explore live stores across every industry and see how entrepreneurs are growing with Launchshop.in.
            </p>
            
            <div class="shops-hero-btns d-flex align-items-center flex-wrap gap-3 mb-4">
              <a href="{{ route('front.pricing') }}" class="btn-shops-primary" style="background: #ff5a2c; color: #ffffff; padding: 12px 28px; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.25s ease; box-shadow: 0 4px 14px rgba(255, 90, 44, 0.25);" onmouseover="this.style.background='#e0451a';" onmouseout="this.style.background='#ff5a2c';">
                {{ __('Get Started') }} <i class="fas fa-arrow-right"></i>
              </a>
              <a href="#shops-grid-section" class="btn-shops-outline" onclick="document.getElementById('shops-grid-section').scrollIntoView({behavior: 'smooth'}); return false;">
                {{ __('Explore Themes') }}
              </a>
            </div>

            <div class="shops-hero-features d-flex align-items-center flex-wrap gap-4" style="margin-top: 24px;">
              <span class="d-inline-flex align-items-center gap-2" style="font-size: 14px; font-weight: 600; color: #334155;">
                <i class="fas fa-rocket" style="color: #ff5a2c; font-size: 16px;"></i> {{ __('Launch Instantly') }}
              </span>
              <span class="d-inline-flex align-items-center gap-2" style="font-size: 14px; font-weight: 600; color: #334155;">
                <i class="fas fa-shield-alt" style="color: #ff5a2c; font-size: 16px;"></i> {{ __('10-Days Money Back Guarantee') }}
              </span>
              <span class="d-inline-flex align-items-center gap-2" style="font-size: 14px; font-weight: 600; color: #334155;">
                <i class="fas fa-headset" style="color: #ff5a2c; font-size: 16px;"></i> {{ __('24/7 Expert Support') }}
              </span>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <!-- Overlapping Mockup collage representation using CSS floating frames -->
          <div class="shops-hero-mockup-wrapper" data-aos="fade-left">
            <div class="shops-mockup-frame frame-1">
              <img src="{{ asset('assets/front/img/template-previews/06d9b674bc8dc704f0a8e376252279a5081e05f8.png') }}" alt="Jewellery theme">
            </div>
            <div class="shops-mockup-frame frame-2">
              <img src="{{ asset('assets/front/img/template-previews/5087ef599467c13d3ce13bed1636a9813bc48117.png') }}" alt="Skinflow theme">
            </div>
            <div class="shops-mockup-frame frame-3">
              <img src="{{ asset('assets/front/img/template-previews/9ddfc4ab6f359c15bda0ee04b184ae092e5e3fbf.png') }}" alt="Grocery theme">
            </div>
            <div class="shops-mockup-frame frame-4">
              <img src="{{ asset('assets/front/img/template-previews/964fb0768f8e185c53dc1ea1058a3ad8dbadaacf.png') }}" alt="Fashion theme">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Shops Hero Section ======-->

  <!--====== Start Stats Banner Section ======-->
  <div class="container">
    <div class="row shops-stats-banner-row align-items-center text-center text-lg-start" data-aos="fade-up">
      <div class="col-6 col-lg-3 mb-3 mb-lg-0">
        <div class="shops-stat-item justify-content-center">
          <div class="shops-stat-icon orange">
            <i class="fas fa-store"></i>
          </div>
          <div>
            <div class="shops-stat-value" data-count="250" data-suffix="+">250+</div>
            <div class="shops-stat-label">{{ __('Stores Launched') }}</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-3 mb-3 mb-lg-0">
        <div class="shops-stat-item justify-content-center">
          <div class="shops-stat-icon" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fas fa-gem"></i>
          </div>
          <div>
            <div class="shops-stat-value" data-count="15" data-suffix="+">15+</div>
            <div class="shops-stat-label">{{ __('Premium Themes') }}</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-3 mb-3 mb-lg-0">
        <div class="shops-stat-item justify-content-center">
          <div class="shops-stat-icon green">
            <i class="fas fa-shield-alt"></i>
          </div>
          <div>
            <div class="shops-stat-value">99.99%</div>
            <div class="shops-stat-label">{{ __('Uptime Guarantee') }}</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="shops-stat-item justify-content-center">
          <div class="shops-stat-icon red">
            <i class="fas fa-user-friends"></i>
          </div>
          <div>
            <div class="shops-stat-value" data-count="500" data-suffix="+">500+</div>
            <div class="shops-stat-label">{{ __('Happy Merchants') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Stats Banner Section ======-->

  <!--====== Start Shops Grid & Filter Section ======-->
  <section class="shops-directory-list pb-80" id="shops-grid-section">
    <div class="container">
      
      <!-- Filter and Search Form -->
      <form action="{{ route('front.user.view') }}" method="GET" id="userSearchForm" class="shops-filter-search">
        <!-- Hidden category value that updates when clicking horizontal tabs -->
        <input type="hidden" name="category" id="searchCategoryInput" value="{{ request()->input('category') }}">
        
        <div class="row align-items-center g-3 mb-4">
          <!-- Dropdown Category -->
          <div class="col-lg-3 col-md-4">
            <select id="categorySelect" class="shops-select-custom" onchange="filterCategory(this.value)">
              <option value="">{{ __('All Categories') }}</option>
              @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected($category->slug == request()->input('category'))>{{ __($category->name) }}</option>
              @endforeach
            </select>
          </div>

          <!-- Keyword Search -->
          <div class="col-lg-6 col-md-5">
            <div class="shops-search-input-wrapper">
              <input type="text" name="shop_name" value="{{ request()->input('shop_name') }}" class="shops-search-input"
                placeholder="{{ __('Search stores or keywords...') }}">
              <span class="shops-search-icon-pos">
                <i class="fas fa-search"></i>
              </span>
            </div>
          </div>

          <!-- Sort by -->
          <div class="col-lg-3 col-md-3">
            <select name="sort_by" class="shops-select-custom" onchange="$('#userSearchForm').submit()">
              <option value="popular" @selected(request()->input('sort_by') == 'popular')>{{ __('Sort by: Popular') }}</option>
              <option value="newest" @selected(request()->input('sort_by') == 'newest')>{{ __('Sort by: Newest') }}</option>
              <option value="rating" @selected(request()->input('sort_by') == 'rating')>{{ __('Sort by: Rating') }}</option>
            </select>
          </div>
        </div>

        <!-- Horizontal Category Pill Buttons / Tabs -->
        <div class="shops-category-tabs-container" data-aos="fade-up">
          <a href="javascript:void(0)" onclick="filterCategory('')" class="shops-category-tab {{ empty(request()->input('category')) ? 'active' : '' }}">
            <i class="fas fa-store"></i> {{ __('All Stores') }}
          </a>
          @foreach ($categories as $cat)
            @php
              $catSlug = strtolower($cat->slug);
              $icon = 'fa-th-large';
              if (str_contains($catSlug, 'fashion')) $icon = 'fa-tshirt';
              elseif (str_contains($catSlug, 'beauty') || str_contains($catSlug, 'cosmetic')) $icon = 'fa-magic';
              elseif (str_contains($catSlug, 'grocery') || str_contains($catSlug, 'food')) $icon = 'fa-shopping-basket';
              elseif (str_contains($catSlug, 'electro')) $icon = 'fa-bolt';
              elseif (str_contains($catSlug, 'furnit')) $icon = 'fa-chair';
              elseif (str_contains($catSlug, 'kid') || str_contains($catSlug, 'toy')) $icon = 'fa-child';
              elseif (str_contains($catSlug, 'jewel')) $icon = 'fa-gem';
              elseif (str_contains($catSlug, 'multi')) $icon = 'fa-cubes';
            @endphp
            <a href="javascript:void(0)" onclick="filterCategory('{{ $cat->slug }}')" class="shops-category-tab {{ request()->input('category') == $cat->slug ? 'active' : '' }}">
              <i class="fas {{ $icon }}"></i> {{ __($cat->name) }}
            </a>
          @endforeach
        </div>
      </form>

      <!-- Shops list Grid -->
      <div id="shops-grid-wrapper">
      <div class="row">
        @if (count($users) == 0)
          <div class="bg-light text-center py-5 d-block w-100 rounded-3">
            <h3 class="my-4 text-muted">{{ __('NO STORE FOUND') }}</h3>
          </div>
        @else
          <div class="shops-grid-cols-5 col-12">
            @foreach ($users as $user)
              @php
                $shopName = $user->shop_name ?: $user->username;
                $rating = $user->landing_rating ?: '4.8';
                $desc = $user->landing_description ?: 'Modern storefront offering premium services and products.';

                $userTheme = $userThemes[$user->id] ?? null;
                $themeLabel = $userTheme && isset($themeLabelMap[$userTheme]) ? $themeLabelMap[$userTheme] : null;

                // Category Mapping: prefer theme label (English), then convert slug, never use raw DB name (may be Arabic/other lang).
                if ($themeLabel) {
                    $catName = $themeLabel['name'];
                    $catSlug = $themeLabel['category'];
                } elseif ($user->category) {
                    $catSlug = strtolower($user->category->slug);
                    $catName = $user->category->name;
                } else {
                    $catName = __('Store');
                    $catSlug = 'store';
                }
                $catClass = 'cat-' . (str_contains($catSlug, 'beauty') ? 'beauty' 
                            : (str_contains($catSlug, 'grocery') ? 'grocery' 
                            : (str_contains($catSlug, 'fashion') ? 'fashion' 
                            : (str_contains($catSlug, 'kids') ? 'kids' 
                            : (str_contains($catSlug, 'jewel') ? 'jewellery' 
                            : (str_contains($catSlug, 'electro') ? 'electronics' 
                            : (str_contains($catSlug, 'furnit') ? 'furniture' 
                            : (str_contains($catSlug, 'multi') ? 'multipurpose' : 'default'))))))));

                // Images Mapping - Automatically fetch screenshot of the theme the subdomain/shop is using
                $bannerFile = $user->template_img;
                if (empty($bannerFile)) {
                    if ($userTheme && isset($themeTemplates[$userTheme])) {
                        $bannerFile = $themeTemplates[$userTheme];
                    }
                }

                $bannerImg = !empty($bannerFile) 
                    ? asset('assets/front/img/template-previews/' . $bannerFile) 
                    : asset('assets/front/images/placeholder.png');
                
                $logoImg = !empty(optional($user->basic_setting)->logo) 
                    ? asset('assets/front/img/user/' . $user->basic_setting->logo) 
                    : (!empty($user->photo) 
                        ? asset('assets/front/img/user/' . $user->photo) 
                        : asset('assets/user/img/profile.png'));
              @endphp

              <!-- Card -->
              <div class="shop-card-modern" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 5) * 50 }}">
                <div class="shop-card-banner">
                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ $bannerImg }}" alt="{{ $shopName }}">
                </div>
                <div class="shop-card-logo-container">
                  <img class="lazyload" src="{{ asset('assets/user/img/profile.png') }}" data-src="{{ $logoImg }}" alt="{{ $shopName }} Logo">
                </div>
                <div class="shop-card-body">
                  <div class="shop-card-header">
                    <h3 class="shop-card-name" title="{{ $shopName }}">{{ $shopName }}</h3>
                    <span class="shop-card-rating">
                      {{ $rating }} <i class="fas fa-star"></i>
                    </span>
                  </div>
                  <div class="shop-card-meta">
                    <span class="shop-card-category {{ $catClass }}">{{ $catName }}</span>
                  </div>
                  <p class="shop-card-desc">{{ __($desc) }}</p>
                  <div class="shop-card-actions">
                    <a href="{{ detailsUrl($user) }}" class="btn-preview" target="_blank">{{ __('Preview Store') }}</a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Pagination -->
      <div class="pagination justify-content-center ">
        {{ $users->appends([
          'shop_name' => request()->input('shop_name'),
          'category' => request()->input('category'),
          'sort_by' => request()->input('sort_by')
        ])->links() }}
      </div>
      </div>

    </div>
  </section>
  <!--====== End Shops Grid & Filter Section ======-->

  <!--====== Start Featured Merchants Section ======-->
  <section class="shops-featured-merchants">
    <div class="container">
      <div class="row align-items-center justify-content-between mb-4">
        <div class="col-md-8">
          <h2 class="shops-section-title">{{ __('Featured Merchants') }}</h2>
          <p class="shops-section-desc">{{ __('Real stories from real entrepreneurs building successful brands.') }}</p>
        </div>
      </div>

      <!-- Merchant Slider -->
      <div class="ls-slider-wrapper" id="merchantSlider">
        <div class="ls-slider-viewport">
          <div class="ls-slider-track">

          <!-- Slide 1 -->
          <div class="ls-slide">
            <div class="merchant-profile-card">
              <div class="merchant-avatar">
                <img class="lazyload" src="{{ asset('assets/front/img/testimonials/indian_man.png') }}" data-src="{{ asset('assets/front/img/testimonials/indian_man.png') }}" alt="Nooryak Khan">
              </div>
              <div class="merchant-info">
                <h4 class="merchant-name">Nooryak Khan</h4>
                <span class="merchant-role">Founder, FreshMart</span>
                <p class="merchant-quote">"Scaled from 0 to 10K+ orders in 3 months."</p>
              </div>
            </div>
          </div>

          <!-- Slide 2 -->
          <div class="ls-slide">
            <div class="merchant-profile-card">
              <div class="merchant-avatar">
                <img class="lazyload" src="{{ asset('assets/front/img/testimonials/indian_woman.png') }}" data-src="{{ asset('assets/front/img/testimonials/indian_woman.png') }}" alt="Priya Mehta">
              </div>
              <div class="merchant-info">
                <h4 class="merchant-name">Priya Mehta</h4>
                <span class="merchant-role">Founder, Skinflow</span>
                <p class="merchant-quote">"Turned passion into a profitable brand."</p>
              </div>
            </div>
          </div>

          <!-- Slide 3 -->
          <div class="ls-slide">
            <div class="merchant-profile-card">
              <div class="merchant-avatar">
                <img class="lazyload" src="{{ asset('assets/front/img/testimonials/indian_man.png') }}" data-src="{{ asset('assets/front/img/testimonials/indian_man.png') }}" alt="Ayaan Patel">
              </div>
              <div class="merchant-info">
                <h4 class="merchant-name">Ayaan Patel</h4>
                <span class="merchant-role">Founder, Electri</span>
                <p class="merchant-quote">"Expanded to 5 cities with Launchshop.in."</p>
              </div>
            </div>
          </div>

          <!-- Slide 4 -->
          <div class="ls-slide">
            <div class="merchant-profile-card">
              <div class="merchant-avatar">
                <img class="lazyload" src="{{ asset('assets/front/img/testimonials/indian_woman.png') }}" data-src="{{ asset('assets/front/img/testimonials/indian_woman.png') }}" alt="Sneha Reddy">
              </div>
              <div class="merchant-info">
                <h4 class="merchant-name">Sneha Reddy</h4>
                <span class="merchant-role">Founder, Urban Chic</span>
                <p class="merchant-quote">"Built a fashion brand customers love."</p>
              </div>
            </div>
          </div>

          <!-- Slide 5 -->
          <div class="ls-slide">
            <div class="merchant-profile-card">
              <div class="merchant-avatar">
                <img class="lazyload" src="{{ asset('assets/front/img/testimonials/indian_man.png') }}" data-src="{{ asset('assets/front/img/testimonials/indian_man.png') }}" alt="Rahul Verma">
              </div>
              <div class="merchant-info">
                <h4 class="merchant-name">Rahul Verma</h4>
                <span class="merchant-role">Founder, TechGadgets</span>
                <p class="merchant-quote">"3x revenue growth in just 6 months."</p>
              </div>
            </div>
          </div>

          <!-- Slide 6 -->
          <div class="ls-slide">
            <div class="merchant-profile-card">
              <div class="merchant-avatar">
                <img class="lazyload" src="{{ asset('assets/front/img/testimonials/indian_woman.png') }}" data-src="{{ asset('assets/front/img/testimonials/indian_woman.png') }}" alt="Ananya Sharma">
              </div>
              <div class="merchant-info">
                <h4 class="merchant-name">Ananya Sharma</h4>
                <span class="merchant-role">Founder, DecorCraft</span>
                <p class="merchant-quote">"Super fast setup and excellent conversion rates."</p>
              </div>
            </div>
          </div>

        </div><!-- /.ls-slider-track -->
        </div><!-- /.ls-slider-viewport -->

        <!-- Arrows -->
        <button class="ls-arrow ls-prev" aria-label="Previous" onclick="lsSlide('merchantSlider', -1)"><i class="fas fa-chevron-left"></i></button>
        <button class="ls-arrow ls-next" aria-label="Next"     onclick="lsSlide('merchantSlider',  1)"><i class="fas fa-chevron-right"></i></button>

        <!-- Dots -->
        <div class="ls-dots" id="merchantSlider-dots"></div>
      </div><!-- /#merchantSlider -->

    </div>
  </section>
  <!--====== End Featured Merchants Section ======-->

  <!--====== Start Testimonials Section ======-->
  <section class="shops-loved-by-merchants">
    <div class="container">
      <h2 class="shops-section-title">{{ __('Loved by Merchants') }}</h2>
      <p class="shops-section-desc">{{ __('Hear from store owners who are growing their business with Launchshop.in.') }}</p>

      <!-- Testimonial Slider -->
      <div class="ls-slider-wrapper" id="testimonialSlider">
        <div class="ls-slider-viewport">
          <div class="ls-slider-track">

          <!-- Slide 1 -->
          <div class="ls-slide">
            <div class="testimonial-modern-card">
              <div class="testimonial-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              </div>
              <p class="testimonial-quote-text">"Launchshop.in made it incredibly easy to launch my store. The themes are beautiful and the support is outstanding."</p>
              <div class="testimonial-author">
                <div>
                  <h4 class="testimonial-author-name">Nooryak Khan</h4>
                  <span class="testimonial-author-role">Founder, FreshMart</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2 -->
          <div class="ls-slide">
            <div class="testimonial-modern-card">
              <div class="testimonial-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              </div>
              <p class="testimonial-quote-text">"I love how customizable everything is. My store looks premium and my customers trust my brand."</p>
              <div class="testimonial-author">
                <div>
                  <h4 class="testimonial-author-name">Priya Mehta</h4>
                  <span class="testimonial-author-role">Founder, Skinflow</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 3 -->
          <div class="ls-slide">
            <div class="testimonial-modern-card">
              <div class="testimonial-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              </div>
              <p class="testimonial-quote-text">"From setup to sales, Launchshop.in has been my growth partner. Highly recommended!"</p>
              <div class="testimonial-author">
                <div>
                  <h4 class="testimonial-author-name">Ayaan Patel</h4>
                  <span class="testimonial-author-role">Founder, Electri</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 4 -->
          <div class="ls-slide">
            <div class="testimonial-modern-card">
              <div class="testimonial-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              </div>
              <p class="testimonial-quote-text">"The analytics dashboard is a game changer. I know exactly where my sales are coming from every single day."</p>
              <div class="testimonial-author">
                <div>
                  <h4 class="testimonial-author-name">Sneha Reddy</h4>
                  <span class="testimonial-author-role">Founder, Urban Chic</span>
                </div>
              </div>
            </div>
          </div>

        </div><!-- /.ls-slider-track -->
        </div><!-- /.ls-slider-viewport -->

        <!-- Arrows -->
        <button class="ls-arrow ls-prev" aria-label="Previous" onclick="lsSlide('testimonialSlider', -1)"><i class="fas fa-chevron-left"></i></button>
        <button class="ls-arrow ls-next" aria-label="Next"     onclick="lsSlide('testimonialSlider',  1)"><i class="fas fa-chevron-right"></i></button>

        <!-- Dots -->
        <div class="ls-dots" id="testimonialSlider-dots"></div>
      </div><!-- /#testimonialSlider -->

    </div>
  </section>
  <!--====== End Testimonials Section ======-->

  <!--====== Start Why Choose Section ======-->
  <section class="shops-why-choose">
    <div class="container text-center">
      <h2 class="shops-section-title mb-5">{{ __('Why Choose Stores Built on Launchshop.in?') }}</h2>
      
      <div class="why-choose-grid">
        <!-- Item 1 -->
        <div class="why-choose-item" data-aos="fade-up" data-aos-delay="50">
          <div class="why-choose-icon-box orange">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <h4 class="why-choose-title">{{ __('Beautiful & Fast Themes') }}</h4>
          <p class="why-choose-desc">{{ __('Mobile-first, high-converting themes that look stunning.') }}</p>
        </div>
        <!-- Item 2 -->
        <div class="why-choose-item" data-aos="fade-up" data-aos-delay="100">
          <div class="why-choose-icon-box red">
            <i class="fas fa-sliders-h"></i>
          </div>
          <h4 class="why-choose-title">{{ __('Easy Customization') }}</h4>
          <p class="why-choose-desc">{{ __('Build your brand your way without any coding.') }}</p>
        </div>
        <!-- Item 3 -->
        <div class="why-choose-item" data-aos="fade-up" data-aos-delay="150">
          <div class="why-choose-icon-box blue">
            <i class="fas fa-rocket"></i>
          </div>
          <h4 class="why-choose-title">{{ __('Powerful Features') }}</h4>
          <p class="why-choose-desc">{{ __('Everything you need to sell, grow & succeed online.') }}</p>
        </div>
        <!-- Item 4 -->
        <div class="why-choose-item" data-aos="fade-up" data-aos-delay="200">
          <div class="why-choose-icon-box green">
            <i class="fas fa-headset"></i>
          </div>
          <h4 class="why-choose-title">{{ __('Trusted Support') }}</h4>
          <p class="why-choose-desc">{{ __('Dedicated support team whenever you need us.') }}</p>
        </div>
        <!-- Item 5 -->
        <div class="why-choose-item" data-aos="fade-up" data-aos-delay="250">
          <div class="why-choose-icon-box orange">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h4 class="why-choose-title">{{ __('Secure & Reliable') }}</h4>
          <p class="why-choose-desc">{{ __('Built on a secure platform you can count on.') }}</p>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Why Choose Section ======-->

  <!--====== Start Footer CTA Block ======-->
  <div class="container">
    <div class="shops-footer-cta-block" data-aos="zoom-in">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <h2 class="shops-footer-cta-title">{{ __('Ready to Launch Your Own Store?') }}</h2>
          <p class="shops-footer-cta-desc">{{ __('Join thousands of successful merchants and start your online journey today.') }}</p>
          
          <div class="shops-hero-btns">
            <a href="{{ route('front.templates.view') }}" class="btn-shops-outline">
              {{ __('Explore Themes') }}
            </a>
          </div>

          <div class="shops-footer-cta-features">
            <span><i class="fas fa-check-circle"></i> 10-Days Money Back Guarantee</span>
            <span><i class="fas fa-check-circle"></i> No credit card required</span>
            <span><i class="fas fa-check-circle"></i> Cancel anytime</span>
          </div>
        </div>
      </div>
      <!-- Storefront Mockup Image on CTA footer background -->
      <div class="shops-footer-cta-graphic">
        <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/front/images/banner.png') }}" alt="Storefront">
      </div>
    </div>
  </div>
  <!--====== End Footer CTA Block ======-->

@endsection

@section('scripts')
<script>
  "use strict";

  /* =========================================================
     Count-Up Animation for Stats (shops page only)
     ========================================================= */
  function lsCountUp(el) {
    if (el.dataset.counted) return;
    el.dataset.counted = '1';
    var target   = parseInt(el.getAttribute('data-count'), 10);
    var suffix   = el.getAttribute('data-suffix') || '';
    var duration = 1800;
    var startTime = null;

    function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

    function step(ts) {
      if (!startTime) startTime = ts;
      var progress = Math.min((ts - startTime) / duration, 1);
      var value    = Math.round(easeOutQuart(progress) * target);
      el.textContent = (target >= 1000 ? value.toLocaleString('en-US') : value) + suffix;
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        el.textContent = (target >= 1000 ? target.toLocaleString('en-US') : target) + suffix;
      }
    }
    requestAnimationFrame(step);
  }

  function lsInitCounters() {
    var counters = document.querySelectorAll('.shops-stat-value[data-count]');
    if (!counters.length) return;
    if ('IntersectionObserver' in window) {
      var obs = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) { lsCountUp(entry.target); obs.unobserve(entry.target); }
        });
      }, { threshold: 0.4 });
      counters.forEach(function(el) { obs.observe(el); });
    } else {
      counters.forEach(function(el) { lsCountUp(el); });
    }
  }

  /* =========================================================
     Category Filter & AJAX helpers
     ========================================================= */
  function filterCategory(slug) {
    $('#searchCategoryInput').val(slug);
    $('#categorySelect').val(slug);
    $('.shops-category-tab').removeClass('active');
    if (slug === '') {
      $('.shops-category-tab').first().addClass('active');
    } else {
      $('.shops-category-tab').each(function() {
        var oc = $(this).attr('onclick');
        if (oc && oc.indexOf("'" + slug + "'") !== -1) { $(this).addClass('active'); }
      });
    }
    $('#userSearchForm').submit();
  }

  function loadShopsAjax(url) {
    $('#shops-grid-wrapper').css('opacity', '0.5');
    $('#shops-grid-wrapper').load(url + ' #shops-grid-wrapper > *', function(response, status, xhr) {
      $('#shops-grid-wrapper').css('opacity', '1');
      if (status === 'error') {
        console.error('AJAX Error:', xhr.status, xhr.statusText);
      } else {
        $('#shops-grid-wrapper img.lazyload').each(function() {
          var s = $(this).attr('data-src');
          if (s) { $(this).attr('src', s); }
        });
        history.pushState(null, '', url);
        if (typeof AOS !== 'undefined') { (AOS.refreshHard || AOS.refresh).call(AOS); }
      }
    });
  }

  $(document).ready(function() {
    // Init sliders (lsInit comes from ls-slider.js loaded in layout)
    lsInit('merchantSlider', { autoMs: 3500 });
    lsInit('testimonialSlider', { autoMs: 4000 });

    // Count-up stats
    lsInitCounters();

    // Form submit → AJAX
    $(document).on('submit', '#userSearchForm', function(e) {
      e.preventDefault();
      $('#searchCategoryInput').val($('#categorySelect').val() || $('#searchCategoryInput').val() || '');
      loadShopsAjax($(this).attr('action') + '?' + $(this).serialize());
    });

    // Pagination → AJAX
    $(document).on('click', '#shops-grid-wrapper .pagination a', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
      if (url) { loadShopsAjax(url); }
    });

    // Live search — 500ms debounce
    var searchTimeout = null;
    $(document).on('keyup', '.shops-search-input', function() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(function() { $('#userSearchForm').submit(); }, 500);
    });
  });
</script>
@endsection

