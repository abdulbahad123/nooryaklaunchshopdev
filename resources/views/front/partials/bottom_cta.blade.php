@php
  $pageKey = !empty($page) ? $page : 'global';
  
  $currLang = app('currentLang');
  $langId = ($currLang && is_object($currLang) && isset($currLang->id)) ? $currLang->id : 1;

  $pageCta = null;
  if (\Illuminate\Support\Facades\Schema::hasTable('page_ctas')) {
      $pageCta = \App\Models\PageCta::where('language_id', $langId)->where('page', $pageKey)->first()
              ?? \App\Models\PageCta::where('page', $pageKey)->first()
              ?? \App\Models\PageCta::where('language_id', $langId)->where('page', 'global')->first()
              ?? \App\Models\PageCta::where('page', 'global')->first();
  }

  $status = $pageCta ? $pageCta->status : '1';
@endphp

@if ($status != '0')
@php
  $ctaTitle = !empty($pageCta->title) 
              ? $pageCta->title 
              : ($pageKey === 'pricing' ? __('Ready to Launch Your Dream Store?') : __('Ready to Launch Your Own Store?'));

  $ctaSubtitle = !empty($pageCta->subtitle) 
                 ? $pageCta->subtitle 
                 : __('Join thousands of successful merchants and start your online journey today.');

  $defaultBtnText = ($pageKey === 'pricing' || $pageKey === 'home') ? __('Launch Your Store') : __('Explore Themes');
  $ctaBtnText = !empty($pageCta->btn_text) ? $pageCta->btn_text : $defaultBtnText;

  $defaultPackage = \App\Models\Package::where('status', '1')->where('featured', '1')->first();
  $defaultPackageId = $defaultPackage ? $defaultPackage->id : 1;
  $defaultBtnUrl = ($pageKey === 'pricing' || $pageKey === 'home') 
                 ? route('front.register.view', ['status' => 'regular', 'id' => $defaultPackageId]) 
                 : route('front.templates.view');
  $ctaBtnUrl = !empty($pageCta->btn_url) ? $pageCta->btn_url : $defaultBtnUrl;

  $ctaFeat1 = !empty($pageCta->feat1) ? $pageCta->feat1 : __('10-Days Money Back Guarantee');
  $ctaFeat2 = !empty($pageCta->feat2) ? $pageCta->feat2 : __('No credit card required');
  $ctaFeat3 = !empty($pageCta->feat3) ? $pageCta->feat3 : __('Cancel anytime');

  if (!empty($pageCta->image) && file_exists(public_path('assets/front/img/' . $pageCta->image))) {
      $ctaImgSrc = asset('assets/front/img/' . $pageCta->image);
  } else {
      $ctaImgSrc = asset('images/footer_right.png');
  }
@endphp

<section class="bottom-cta-revamp-shared py-5" style="background: transparent;">
  <div class="container position-relative">
    <div class="cta-blue-card-shared" data-aos="zoom-in" style="background: #0f172a; border-radius: 24px; padding: 48px 40px; position: relative; overflow: hidden; box-shadow: 0 20px 45px rgba(15, 23, 42, 0.25);">
      <div class="row align-items-center g-4">
        
        <!-- Left Content -->
        <div class="col-lg-7 col-md-12 text-center text-lg-start" style="position: relative; z-index: 2;">
          <h2 class="cta-title-shared" style="color: #ffffff; font-size: 38px; font-weight: 800; line-height: 1.25; margin-bottom: 16px;">
            {!! $ctaTitle !!}
          </h2>
          <p class="cta-subtitle-shared" style="color: #94a3b8; font-size: 16px; line-height: 1.6; margin-bottom: 28px; max-width: 580px;">
            {{ $ctaSubtitle }}
          </p>

          <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start mb-4">
            <a href="{{ $ctaBtnUrl }}" class="btn-cta-launch-shared px-4 py-3 d-inline-flex align-items-center gap-2" style="background: #ff5a2c; color: #ffffff; font-weight: 700; font-size: 15px; border-radius: 50px; text-decoration: none; box-shadow: 0 6px 20px rgba(255, 90, 44, 0.4); transition: transform 0.2s, background 0.2s;">
              {{ $ctaBtnText }} <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>

          <div class="cta-features-badges-shared d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-3" style="color: #cbd5e1; font-size: 13px; font-weight: 600;">
            <span class="d-inline-flex align-items-center gap-1"><i class="fas fa-check-circle" style="color: #ff5a2c;"></i> {{ $ctaFeat1 }}</span>
            <span class="d-inline-flex align-items-center gap-1"><i class="fas fa-check-circle" style="color: #ff5a2c;"></i> {{ $ctaFeat2 }}</span>
            <span class="d-inline-flex align-items-center gap-1"><i class="fas fa-check-circle" style="color: #ff5a2c;"></i> {{ $ctaFeat3 }}</span>
          </div>
        </div>

        <!-- Right Illustration Graphic -->
        <div class="col-lg-5 col-md-12 text-center d-flex align-items-center justify-content-center" style="position: relative; z-index: 1;">
          <img src="{{ $ctaImgSrc }}" alt="{{ strip_tags($ctaTitle) }}" class="img-fluid cta-shared-img" style="max-height: 320px; width: auto; object-fit: contain; filter: drop-shadow(0 10px 25px rgba(0,0,0,0.3));">
        </div>

      </div>
    </div>
  </div>
</section>
@endif