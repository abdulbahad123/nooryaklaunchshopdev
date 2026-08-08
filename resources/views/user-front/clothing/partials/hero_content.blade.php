<!-- Hero Start -->
<section class="clothing-hero">
  <div class="container">
    <div class="clothing-hero-shell">
      <div class="clothing-hero-copy" data-aos="fade-right" data-aos-delay="100">
        <span class="clothing-hero-kicker">{{ $keywords['New Season'] ?? __('NEW SEASON') }}</span>
        <h1>Elevate Your<br>Everyday Style</h1>
        <p>Timeless designs. Premium fabrics.<br>Made for the modern you.</p>
        <div class="clothing-hero-actions">
          <a href="{{ route('front.user.shop', getParam()) }}" class="clothing-btn-dark">SHOP MEN</a>
          <a href="{{ route('front.user.shop', getParam()) }}" class="clothing-btn-light">SHOP WOMEN</a>
        </div>
      </div>

      <div class="clothing-hero-visual" data-aos="fade-left" data-aos-delay="120">
        <div class="hero-visual-frame">
          @if(!is_null(@$static_hero_section->background_image))
            <img class="lazyload blur-up"
              src="{{ asset('assets/front/images/placeholder.png') }}"
              data-src="{{ asset('assets/front/img/hero-section/' . @$static_hero_section->background_image) }}"
              alt="Banner">
          @else
            <img class="lazyload blur-up"
              src="{{ asset('assets/front/images/placeholder.png') }}"
              data-src="{{ asset('assets/user-front/images/fashion/banner/banner-1.jpg') }}"
              alt="Banner">
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Hero End -->
