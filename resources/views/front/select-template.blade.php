@extends('front.layout')

@section('pagename')
  - {{ __('Choose a Template') }}
@endsection

@section('breadcrumb-title')
  {{ __('Choose a Template') }}
@endsection
@section('breadcrumb-link')
  {{ __('Choose a Template') }}
@endsection

@section('styles')
<style>
  /* Disable the default page breadcrumb area */
  .page-title-area {
      display: none !important;
  }
  
  .feature-box-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #eef2f6;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 8px 30px rgba(14, 27, 61, 0.02);
  }
  .feature-box-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
    border-color: #cbd5e1;
  }
  .feature-box-card .icon-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto;
  }
  .feature-box-card .icon-circle.starter {
    background: #eff6ff;
    color: #3b82f6;
  }
  .feature-box-card .icon-circle.growth {
    background: #ecfdf5;
    color: #10b981;
  }
  .feature-box-card .icon-circle.scale {
    background: #fef3c7;
    color: #d97706;
  }
  .feature-box-card h4 {
    font-size: 18px;
    font-weight: 800;
    margin-bottom: 12px;
    color: #0e1b3d;
  }
  .feature-box-card p {
    font-size: 14px;
    color: #64748b;
    line-height: 1.6;
    margin: 0;
  }
  
  .how-works-card.active .step-number {
    background: #ff5a2c !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(255, 90, 44, 0.3);
  }
  
  /* Give longer thumbnail preview */
  .card-image-wrap {
      height: 380px !important;
  }
  .template-card-modern:hover .scrolling-img {
      transform: translateY(calc(-100% + 380px)) !important;
  }
</style>
@endsection

@section('content')
  <!-- Modern Templates Page Wrapper -->
  <div class="modern-templates-page-wrapper pt-40 pb-120">
    <div class="container">
      
      <!-- Hero Header Section -->
      <div class="row align-items-center mb-60 mt-30 templates-hero-row">
        <div class="col-lg-7 mx-auto text-center">
          <div class="templates-hero-content" data-aos="fade-up">
            <span class="templates-hero-badge">
              {{ __($package->title) }}
              @if ($status === 'trial')
                &nbsp;— {{ __('Trial') }}
              @endif
            </span>
            <h1 class="templates-hero-title">
              Choose Your <br><span>Store Template</span>
            </h1>
            <p class="templates-hero-desc">
              Select a design below to launch your store. All templates are fully customizable, responsive, and optimized for speed and conversions.
            </p>
          </div>
        </div>
      </div>

      <!-- Templates Grid Section -->
      <div class="templates-grid-wrapper">
        @if ($templates->isEmpty())
          <div class="text-center py-5">
            <i class="fal fa-store-slash text-muted" style="font-size: 64px; margin-bottom: 20px;"></i>
            <h3>{{ __('No templates available') }}</h3>
            <p class="text-muted">{{ __('Please contact support or try again later.') }}</p>
            <a href="{{ route('front.register.view', ['status' => $status, 'id' => $id]) }}" class="btn primary-btn mt-3">
              {{ __('Continue Without Template') }}
            </a>
          </div>
        @else
          <div class="row g-4 justify-content-center">
            @foreach ($templates as $template)
              @php
                $themeName = \App\Models\User\BasicSetting::where('user_id', $template->id)->value('theme');
                
                $badgeClass = match($themeName) {
                    'vegetables', 'grocery' => 'badge-grocery',
                    'manti', 'multipurpose' => 'badge-multi',
                    'fashion', 'apparel', 'jewellery', 'kids' => 'badge-fashion',
                    'electronics', 'gadgets' => 'badge-electronics',
                    'beauty', 'cosmetics', 'skinflow' => 'badge-beauty',
                    default => 'badge-others',
                };

                $displayName = match($themeName) {
                    'vegetables' => __('Grocery & Supermarket'),
                    'grocery' => __('Grocery & Supermarket'),
                    'manti' => __('Multipurpose'),
                    'multipurpose' => __('Multipurpose'),
                    'jewellery' => __('Jewellery & Accessories'),
                    'kids' => __('Kids Fashion'),
                    'fashion' => __('Fashion & Apparel'),
                    'apparel' => __('Fashion & Apparel'),
                    'electronics' => __('Electronics & Gadgets'),
                    'gadgets' => __('Electronics & Gadgets'),
                    'skinflow' => __('Skin & Beauty Care'),
                    'beauty' => __('Beauty & Cosmetics'),
                    'cosmetics' => __('Beauty & Cosmetics'),
                    default => ucfirst($themeName ?? 'Default'),
                };

                $previewUrl = detailsUrl($template);
                $registerUrl = route('front.register.view', [
                  'status' => $status,
                  'id'     => $id,
                ]) . '?template=' . urlencode($template->username);
              @endphp

              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="50">
                <div class="template-card-modern">
                  <!-- Image Wrapper with scroll hover effect -->
                  <div class="card-image-wrap">
                    <span class="category-badge {{ $badgeClass }}">{{ $displayName }}</span>
                    <a href="{{ $previewUrl }}" target="_blank" class="image-viewport">
                      @if (!empty($template->template_img))
                        <img class="lazyload scrolling-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/template-previews/' . $template->template_img) }}"
                          alt="{{ $displayName }} Theme" />
                      @else
                        <img class="lazyload scrolling-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/template-previews/placeholder.png') }}"
                          alt="Placeholder Theme" />
                      @endif
                    </a>
                  </div>
                  
                  <!-- Card Body -->
                  <div class="card-body-wrap">
                    <h3 class="card-theme-title">{{ $displayName }} {{ __('Theme') }}</h3>
                    
                    <hr class="card-divider">
                    
                    <div class="card-action-row">
                      <a href="{{ $previewUrl }}" target="_blank" class="btn-template-action outline-btn">
                        <i class="fas fa-eye me-2"></i> {{ __('Live Preview') }}
                      </a>
                      <a href="{{ $registerUrl }}" class="btn-template-action primary-btn">
                        <i class="fas fa-check me-2"></i> {{ __('Select') }}
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Skip option (commented out per user requirement but styled) --}}
          <!-- <div class="text-center mt-5">
            <a href="{{ route('front.register.view', ['status' => $status, 'id' => $id]) }}" class="text-muted small" style="text-decoration: none; font-weight: 600;">
              {{ __('Skip and continue without selecting a template') }} →
            </a>
          </div> -->
        @endif
      </div>
      <br><br>

      <!-- Trust & eCommerce Features Section -->
      <div class="template-features-section mt-100 mb-80" data-aos="fade-up">
        <h2 class="section-title text-center mb-50">{{ __('Built for eCommerce Success') }}</h2>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="feature-box-card text-center p-4">
              <div class="icon-circle mb-3 starter">
                <i class="fas fa-mobile-alt"></i>
              </div>
              <h4>{{ __('Mobile-First Design') }}</h4>
              <p>{{ __('Over 70% of e-commerce traffic comes from smartphones. Our storefronts are pixel-perfect and optimized for all screens.') }}</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature-box-card text-center p-4">
              <div class="icon-circle mb-3 growth">
                <i class="fas fa-shopping-bag"></i>
              </div>
              <h4>{{ __('Frictionless Checkout') }}</h4>
              <p>{{ __('Streamlined single-page checkout flow minimizes cart abandonment and maximizes your online conversion rates.') }}</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature-box-card text-center p-4">
              <div class="icon-circle mb-3 scale">
                <i class="fas fa-tachometer-alt"></i>
              </div>
              <h4>{{ __('Lightning Fast Speed') }}</h4>
              <p>{{ __('Built with performance-first practices ensuring ultra-fast page response times and superior SEO search visibility.') }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Simple Setup Process Section -->
      <div class="template-how-it-works-section mt-100 mb-80" data-aos="fade-up">
        <h2 class="section-title text-center">{{ __('Quick Setup Process') }}</h2>
        <p class="section-subtitle text-center mb-50">{{ __('Get your professional e-commerce storefront live in three simple steps.') }}</p>
        
        <div class="row g-4">
          <div class="col-md-4">
            <div class="how-works-card active">
              <div class="step-number">1</div>
              <h4>{{ __('Choose a Template') }}</h4>
              <p>{{ __('Pick one of our premium storefront templates optimized specifically for your business niche.') }}</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="how-works-card">
              <div class="step-number">2</div>
              <h4>{{ __('Complete Registration') }}</h4>
              <p>{{ __('Fill in your domain name details, configure admin credentials, and choose a subscription plan.') }}</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="how-works-card">
              <div class="step-number">3</div>
              <h4>{{ __('Launch & Sell') }}</h4>
              <p>{{ __('Link your payment processors, upload your product catalog, and start accepting online orders.') }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- FAQ Section collapse grid -->
      @if (count($faqs) > 0)
        <div class="pricing-faq-section mt-100" data-aos="fade-up">
          <h2 class="section-title text-center mb-50">{{ __('Frequently Asked Questions') }}</h2>
          
          <div class="row">
            <!-- Left Column accordion -->
            <div class="col-md-6 mb-4 mb-md-0">
              <div class="accordion" id="faqAccordionLeft">
                @foreach ($faqs as $index => $faq)
                  @if ($index % 2 == 0)
                    <div class="accordion-item faq-item">
                      <h3 class="accordion-header" id="faqHL{{ $faq->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCL{{ $faq->id }}" aria-expanded="false" aria-controls="faqCL{{ $faq->id }}">
                          {{ $faq->question }}
                        </button>
                      </h3>
                      <div id="faqCL{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="faqHL{{ $faq->id }}" data-bs-parent="#faqAccordionLeft">
                        <div class="accordion-body">
                          {{ $faq->answer }}
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
            
            <!-- Right Column accordion -->
            <div class="col-md-6">
              <div class="accordion" id="faqAccordionRight">
                @foreach ($faqs as $index => $faq)
                  @if ($index % 2 != 0)
                    <div class="accordion-item faq-item">
                      <h3 class="accordion-header" id="faqHR{{ $faq->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCR{{ $faq->id }}" aria-expanded="false" aria-controls="faqCR{{ $faq->id }}">
                          {{ $faq->question }}
                        </button>
                      </h3>
                      <div id="faqCR{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="faqHR{{ $faq->id }}" data-bs-parent="#faqAccordionRight">
                        <div class="accordion-body">
                          {{ $faq->answer }}
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
          </div>
        </div>
      @endif

    </div>
  </div>
@endsection
