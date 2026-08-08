<section class="home-hero home-hero-7">
  <!-- Background Image -->
  <img class="lazyload bg-img blur-up"
    data-src="{{ !is_null(@$static_hero_section->background_image) ? asset('assets/front/img/hero-section/' . @$static_hero_section->background_image) : asset('assets/user-front/images/pet/hero/hero-bg.jpg') }}"
    alt="Banner">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-xl-5">
        <div class="content">
          <h1 class="text-white mb-20 title fw-black" data-aos="fade-up" data-aos-delay="100">
            {{ $static_hero_section->title ?? ($keywords['Your One Stop Shop for Quality Pet Foods'] ?? __('Your One Stop Shop for Quality Pet Foods')) }}
            <img class="ms-2" src="{{ asset('assets/user-front/images/pet/hero/shape/shape-4.png') }}" alt="shape">
          </h1>
          <p class="text-white description fw-semibold" data-aos="fade-up" data-aos-delay="20">
            {{ $static_hero_section->subtitle ?? ($keywords['Discover high-quality, nutritious pe food made with love From playful pups to curious cats, we’ve got pet'] ?? __('Discover high-quality, nutritious pe food made with love From playful pups to curious cats, we’ve got pet')) }}
          </p>
          @if (!is_null(@$static_hero_section->button_text) && !is_null(@$static_hero_section->button_url))
            <a href="{{ @$static_hero_section->button_url }}" class="btn btn-lg btn-primary radius-30"
              data-aos="fade-up" data-aos-delay="300">{{ @$static_hero_section->button_text }}</a>
          @endif
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-image" data-aos="zoom-in" data-aos-delay="100">
          <img class="lazyload blur-up"
            data-src="{{ !is_null(@$static_hero_section->hero_image) ? asset('assets/front/img/hero-section/' . @$static_hero_section->hero_image) : asset('assets/user-front/images/pet/hero/hero-img.png') }}"
            alt="hero-img">
        </div>
      </div>
    </div>
  </div>
  <div class="shape">
    <img class="shape-1 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/hero/shape/shape-1.png') }}"
      alt="shape">
    <img class="shape-2 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/hero/shape/shape-2.png') }}"
      alt="shape">
    <img class="shape-3 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/hero/shape/shape-3.png') }}"
      alt="shape">
    <img class="shape-4 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/hero/shape/shape-4.png') }}"
      alt="shape">
    <img class="shape-5 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/hero/shape/shape-5.png') }}"
      alt="shape">
    <img class="shape-6 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/hero/shape/shape-6.png') }}"
      alt="shape">
    <img class="shape-7 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/hero/shape/shape-7.png') }}"
      alt="shape">
    <img class="shape-8 lazyload blur-up" src="{{ asset('assets/user-front/images/pet/hero/shape/shape-8.png') }}"
      alt="shape">
  </div>
</section>
