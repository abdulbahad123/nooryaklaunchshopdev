<section class="home-hero home-hero-9 overfollow-hidden header-next pb-70">
  <div class="home-hero-area">
    <div class="container-fuild p-0">
      @if (count($hero_sliders) > 0)
        <div class="slider-area">
          <div class="hero-center-slider" id="hero-center-slider">
            <!-- slide-item -->
            @foreach ($hero_sliders as $hero_slider)
              <div class="slide-item">
                <div class="hero-content">
                  <h2 class=" title mb-10">{{ $hero_slider->title }}</h2>
                  <p class="description lc-2 mb-20">{{ $hero_slider->subtitle }}</p>
                  @if (!is_null($hero_slider->btn_name))
                    <a href="{{ (!empty($hero_slider->btn_url) && $hero_slider->btn_url !== '#') ? $hero_slider->btn_url : route('front.user.shop', getParam()) }}"
                      class="btn btn-primary btn-md radius-30">{{ $hero_slider->btn_name }}</a>
                  @endif
                </div>
                <div class="hero-image ">
                  <img class="lazyload" data-src="{{ asset('assets/front/img/hero_slider/' . $hero_slider->img) }}"
                    alt="">
                </div>
              </div>
            @endforeach

          </div>
          <div class="slider-arrow" id="hero-center-slider-arrows"></div>
        </div>
      @else
        <h5 class="title mb-20 text-center">
          {{ $keywords['NO DATA FOUND'] ?? __('NO DATA FOUND') }}
        </h5>
      @endif
    </div>
  </div>
</section>
