<!-- Home Hero Start -->
<section class="home-hero home-hero-8 overfollow-hidden header-next">
  <!-- Background Image -->
  <img class="lazyload bg-img blur-up"
    data-src="{{ !is_null(@$static_hero_section->background_image) ? asset('assets/front/img/hero-section/' . @$static_hero_section->background_image) : asset('assets/user-front/images/jewellery/bg-image.png') }}"
    alt="Banner">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="hero-card-wrapper" data-aos="zoom-in" data-aos-delay="100">
          <div class="hero-card">
            <h2 class="mb-20">
              {{ $static_hero_section->title ?? ($keywords['Timeless Your Elegance in all of Your Pieces.'] ?? __('Timeless Your Elegance in all of Your Pieces.')) }}
            </h2>
            <p class="mb-30">
              {{ $static_hero_section->subtitle ??
                  ($keywords['Welcome to a world of timeless beauty and refined craftsmanship. Explore our curated collections.'] ??
                      __('Welcome to a world of timeless beauty and refined craftsmanship.Explore our curated collections.')) }}
            </p>
            @if (!is_null(@$static_hero_section->button_text) && !is_null(@$static_hero_section->button_url))
              <a href="{{ @$static_hero_section->button_url }}"
                class="btn btn-lg btn-primary">{{ @$static_hero_section->button_text }}</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Home Hero End -->
