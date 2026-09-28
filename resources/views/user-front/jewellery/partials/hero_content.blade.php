@if (isset($hero_sliders) && count($hero_sliders) > 0)
  <section class="home-hero home-hero-8 overfollow-hidden header-next p-0">
    <div class="hero-slider-8">
      @foreach ($hero_sliders as $slider)
        <div class="hero-slide-item position-relative py-5">
          <img class="lazyload bg-img blur-up"
            data-src="{{ !empty($slider->img) ? asset('assets/front/img/hero_slider/' . $slider->img) : (!is_null(@$static_hero_section->background_image) ? asset('assets/front/img/hero-section/' . @$static_hero_section->background_image) : asset('assets/user-front/images/jewellery/bg-image.png')) }}"
            alt="Banner">
          <div class="container py-4">
            <div class="row">
              <div class="col-12">
                <div class="hero-card-wrapper" data-aos="zoom-in" data-aos-delay="100">
                  <div class="hero-card">
                    @if (!empty($slider->title))
                      <h2 class="mb-20">{{ $slider->title }}</h2>
                    @endif
                    @if (!empty($slider->subtitle) || !empty($slider->text))
                      <p class="mb-30">{{ $slider->subtitle ?? $slider->text }}</p>
                    @endif
                    @if (!empty($slider->btn_name) && !empty($slider->btn_url))
                      <a href="{{ $slider->btn_url }}" class="btn btn-lg btn-primary">{{ $slider->btn_name }}</a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </section>
@else
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
@endif

