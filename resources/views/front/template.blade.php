@extends('front.layout')

@section('pagename')
  - {{ str_replace('Store Designs', 'Store Themes', $pageHeading ?? __('Templates')) }}
@endsection
@php
  $additional_section_status = json_decode($bs->additional_section_status, true);
@endphp
@section('meta-description', !empty($seo) ? $seo->home_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->home_meta_keywords : '')

@section('styles')
<style>
  /* Disable the default page breadcrumb area */
  .page-title-area {
      display: none !important;
  }
  /* Give longer thumbnail preview */
  .card-image-wrap {
      height: 280px !important;
  }
  .template-card-modern:hover .scrolling-img {
      transform: translateY(calc(-100% + 280px)) !important;
  }
  .btn-template-action.admin-btn {
      background: #0e1b3d;
      color: #ffffff;
      border: 1px solid #0e1b3d;
  }
  .btn-template-action.admin-btn:hover {
      background: #1e293b;
      border-color: #1e293b;
      color: #ffffff;
  }
  .btn-template-action {
      padding: 8px 4px !important;
      font-size: 13px !important;
      white-space: nowrap !important;
  }
  .card-action-row {
      display: flex !important;
      flex-direction: column !important;
      gap: 8px !important;
  }
  /* Coming Soon Card Styles */
  .coming-soon-card {
      min-height: 440px !important;
      border: 2px dashed #cbd5e1 !important;
      background: #f8fafc !important;
      box-shadow: none !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      border-radius: 16px !important;
      padding: 30px 20px !important;
      transition: all 0.3s ease !important;
  }
  .coming-soon-card:hover {
      border-color: #ff5a2c !important;
      background: #ffffff !important;
      box-shadow: 0 10px 30px rgba(255, 90, 44, 0.08) !important;
      transform: translateY(-4px) !important;
  }
  .coming-soon-inner {
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      text-align: center !important;
  }
  .coming-soon-icon-box {
      width: 64px !important;
      height: 64px !important;
      border-radius: 50% !important;
      background: #fff5f2 !important;
      color: #ff5a2c !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      font-size: 24px !important;
      margin-bottom: 20px !important;
  }
  .coming-soon-title {
      font-size: 22px !important;
      font-weight: 700 !important;
      color: #1e293b !important;
      margin-bottom: 10px !important;
  }
  .coming-soon-desc {
      font-size: 14px !important;
      color: #64748b !important;
      margin: 0 !important;
      max-width: 260px !important;
      line-height: 1.5 !important;
  }

  /* Register For More Themes Button */
  .btn-register-more-themes {
      background: #ff5a2c !important;
      color: #ffffff !important;
      font-size: 16px !important;
      font-weight: 700 !important;
      padding: 14px 32px !important;
      border-radius: 12px !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      box-shadow: 0 4px 14px rgba(255, 90, 44, 0.3) !important;
      transition: all 0.3s ease !important;
      text-decoration: none !important;
  }
  .btn-register-more-themes:hover {
      background: #e0481d !important;
      color: #ffffff !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 20px rgba(255, 90, 44, 0.4) !important;
  }
  /* Steps & Stats Section Styles */
  .steps-stats-section {
    background: #ffffff;
    padding: 80px 0;
    font-family: 'Outfit', 'Inter', sans-serif;
  }
  .rocket-visual-wrap {
    position: relative;
    display: inline-block;
  }
  .rocket-main-img {
    max-width: 100%;
    height: auto;
    animation: floatRocket 4s ease-in-out infinite;
  }
  @keyframes floatRocket {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }
  .steps-badge {
    background: #fff5f2;
    color: #ff5a2c;
    font-size: 14px;
    font-weight: 700;
    padding: 6px 16px;
    border-radius: 50px;
    border: 1px solid rgba(255, 90, 44, 0.1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
  }
  .steps-main-title {
    font-size: 38px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.25;
  }
  .step-item-custom {
    gap: 16px;
  }
  .step-icon-container {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }
  .step-icon-orange {
    background: #fff5f2;
    color: #ff5a2c;
    border: 1px solid rgba(255, 90, 44, 0.15);
  }
  .step-icon-pink {
    background: #fdf2f8;
    color: #db2777;
    border: 1px solid rgba(219, 39, 119, 0.15);
  }
  .step-icon-green {
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid rgba(22, 163, 74, 0.15);
  }
  .step-icon-blue {
    background: #f0f9ff;
    color: #2563eb;
    border: 1px solid rgba(37, 99, 235, 0.15);
  }
  .step-text-container {
    display: flex;
    flex-direction: column;
    text-align: left;
  }
  .step-item-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
  }
  .step-item-desc {
    font-size: 14px;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
  }
  .btn-start-journey {
    background: #ff5a2c;
    color: #ffffff !important;
    font-size: 15px;
    font-weight: 700;
    padding: 14px 28px;
    border-radius: 50px;
    border: none;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 4px 14px rgba(255, 90, 44, 0.3);
    transition: all 0.3s ease;
    text-decoration: none !important;
  }
  .btn-start-journey:hover {
    background: #e0481d;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 90, 44, 0.4);
  }
  .stats-bar-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 24px 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
  }
  .stat-item-wrap {
    gap: 16px;
  }
  .stat-bar-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .stat-icon-orange {
    background: #fff5f2;
    color: #ff5a2c;
  }
  .stat-icon-pink {
    background: #fdf2f8;
    color: #db2777;
  }
  .stat-icon-blue {
    background: #eff6ff;
    color: #3b82f6;
  }
  .stat-icon-green {
    background: #f0fdf4;
    color: #16a34a;
  }
  .stat-bar-info {
    display: flex;
    flex-direction: column;
    text-align: left;
  }
  .stat-bar-val {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.1;
  }
  .stat-bar-lbl {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    font-weight: 500;
  }
  @media (max-width: 575px) {
    .steps-stats-section .stats-bar-card {
      padding: 16px 10px !important;
    }
    .steps-stats-section .stat-item-wrap {
      gap: 6px !important;
      justify-content: flex-start;
    }
    .steps-stats-section .stat-bar-icon-box {
      width: 32px !important;
      height: 32px !important;
      font-size: 14px !important;
      border-radius: 8px !important;
    }
    .steps-stats-section .stat-bar-val {
      font-size: 16px !important;
    }
    .steps-stats-section .stat-bar-lbl {
      font-size: 10px !important;
    }
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
            <span class="templates-hero-badge">{{ __('PREMIUM STORE THEMES') }}</span>
            <h1 class="templates-hero-title">
              Select Profesional Store Theme For Your <br><span>Ecommerce Shop</span>
            </h1>
            <p class="templates-hero-desc">
              Choose from our e-commerce templates built for speed, responsiveness, and conversions. Select a template and pick a plan to checkout and start selling.
            </p>
          </div>
        </div>
      </div>

      <!-- Search & Category Filter Section -->
      <div class="templates-filter-section mb-50" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-4 align-items-center">
          <div class="col-lg-4">
            <div class="search-box-custom">
              <i class="fas fa-search search-icon"></i>
              <input type="text" id="templateSearch" class="form-control" placeholder="Search templates by name...">
            </div>
          </div>
          <div class="col-lg-8">
            <div class="templates-tabs-wrapper">
              <button type="button" class="tab-btn active" data-filter="all">{{ __('All Templates') }}</button>
              <button type="button" class="tab-btn" data-filter="fashion">{{ __('Fashion') }}</button>
              <button type="button" class="tab-btn" data-filter="clothing">{{ __('Clothing') }}</button>
              <button type="button" class="tab-btn" data-filter="grocery">{{ __('Grocery') }}</button>
              <button type="button" class="tab-btn" data-filter="electronics">{{ __('Electronics') }}</button>
              <button type="button" class="tab-btn" data-filter="beauty">{{ __('Beauty') }}</button>
              <button type="button" class="tab-btn" data-filter="multipurpose">{{ __('Multipurpose') }}</button>
              <button type="button" class="tab-btn" data-filter="others">{{ __('Others') }}</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Templates Grid Section -->
      <div class="templates-grid-wrapper">
        <div class="row g-4 justify-content-start" id="templatesGrid">
          @foreach ($templates as $template)
            @php
              $themeName = App\Models\User\BasicSetting::where('user_id', $template->id)->pluck('theme')->first();
              $category = 'others';
              $displayName = __('Theme');
              $badgeClass = 'bg-secondary';
              
              if ($themeName == 'vegetables' || $themeName == 'grocery') {
                  $category = 'grocery';
                  $displayName = __('Grocery & Supermarket');
                  $badgeClass = 'badge-grocery';
              } elseif ($themeName == 'manti' || $themeName == 'multipurpose') {
                  $category = 'multipurpose';
                  $displayName = __('Multipurpose');
                  $badgeClass = 'badge-multi';
              } elseif ($themeName == 'fashion' || $themeName == 'apparel' || $themeName == 'jewellery' || $themeName == 'kids' || $themeName == 'clothing') {
                  $category = ($themeName == 'clothing') ? 'clothing' : 'fashion';
                  $displayName = match($themeName) {
                      'jewellery' => __('Jewellery & Accessories'),
                      'kids' => __('Kids Fashion'),
                      'clothing' => __('Clothing & Apparel'),
                      default => __('Fashion & Apparel'),
                  };
                  $badgeClass = 'badge-fashion';
              } elseif ($themeName == 'electronics' || $themeName == 'gadgets') {
                  $category = 'electronics';
                  $displayName = __('Electronics & Gadgets');
                  $badgeClass = 'badge-electronics';
              } elseif ($themeName == 'beauty' || $themeName == 'cosmetics' || $themeName == 'skinflow') {
                  $category = 'beauty';
                  $displayName = match($themeName) {
                      'skinflow' => __('Skin & Beauty Care'),
                      default => __('Beauty & Cosmetics'),
                  };
                  $badgeClass = 'badge-beauty';
              } else {
                  $category = 'others';
                  $displayName = ucfirst($themeName ?? 'Default');
                  $badgeClass = 'badge-others';
              }
              
              $previewUrl = detailsUrl($template);
              $purchaseUrl = route('front.pricing') . '?template=' . urlencode($template->username);
            @endphp
            
            <div class="col-lg-4 col-md-6 template-card-item" data-category="{{ $category }}" data-search="{{ strtolower(trim($displayName . ' ' . ($template->shop_name ?? '') . ' ' . $template->username . ' ' . ($themeName ?? '') . ' ' . $category)) }}">
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
                  
                  <div class="card-action-row" style="display: flex; flex-direction: column; gap: 8px;">
                    <div class="d-flex gap-2" style="width: 100%;">
                      <a href="{{ $previewUrl }}" target="_blank" class="btn-template-action outline-btn" style="flex: 1; justify-content: center; display: inline-flex; align-items: center;">
                        <i class="fas fa-eye me-2"></i> {{ __('Live Preview') }}
                      </a>
                      <a href="{{ route('front.templates.autologin', $template->username) }}" target="_blank" class="btn-template-action admin-btn" style="flex: 1; justify-content: center; display: inline-flex; align-items: center;">
                        <i class="fas fa-user-cog me-2"></i> {{ __('Admin') }}
                      </a>
                    </div>
                    <a href="{{ $purchaseUrl }}" class="btn-template-action primary-btn" style="width: 100%; justify-content: center; display: inline-flex; align-items: center;">
                      <i class="fas fa-shopping-cart me-2"></i> {{ __('Purchase') }}
                    </a>
                  </div>
                </div>
              </div>
            </div>
            
          @endforeach

          <!-- Coming Soon Cards -->
          <div class="col-lg-4 col-md-6 template-card-item" data-category="others" data-search="coming soon template new themes">
            <div class="template-card-modern coming-soon-card">
              <div class="coming-soon-inner">
                <div class="coming-soon-icon-box">
                  <i class="fas fa-magic"></i>
                </div>
                <h3 class="coming-soon-title">{{ __('Coming Soon') }}</h3>
                <p class="coming-soon-desc">{{ __('We are crafting new premium templates for your store.') }}</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 template-card-item" data-category="others" data-search="coming soon template new themes">
            <div class="template-card-modern coming-soon-card">
              <div class="coming-soon-inner">
                <div class="coming-soon-icon-box">
                  <i class="fas fa-magic"></i>
                </div>
                <h3 class="coming-soon-title">{{ __('Coming Soon') }}</h3>
                <p class="coming-soon-desc">{{ __('We are crafting new premium templates for your store.') }}</p>
              </div>
            </div>
          </div>

          <!-- Register For More Themes CTA Button -->
          <div class="col-12 text-center mt-5 mb-4">
            @php
              $defaultPackage = \App\Models\Package::where('status', '1')->where('featured', '1')->first();
              $defaultPackageId = $defaultPackage ? $defaultPackage->id : 1;
            @endphp
            <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $defaultPackageId]) }}" class="btn-register-more-themes">
              {{ __('Register For More Themes') }} <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>

          <!-- Custom Banner (Shown at bottom after all templates) -->
          <div class="col-12 templates-mid-banner-col my-4">
            <div class="templates-mid-banner">
              <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0 text-start">
                  <div class="banner-content">
                    <span class="banner-badge">{{ __('Tailored For You') }}</span>
                    <h3>{{ __('Need a Bespoke E-commerce Design?') }}</h3>
                    <p>{{ __('Can\'t find the perfect match? Our in-house design experts can build a fully custom storefront template tailored specifically to your brand\'s unique requirements.') }}</p>
                  </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                  <a href="{{ route('front.contact') }}" class="btn-banner-action">
                    <i class="fas fa-comment-alt-lines me-2"></i> {{ __('Talk to Our Designers') }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Filter Empty State -->
        <div id="noTemplatesMessage" class="text-center py-5 d-none">
          <i class="fal fa-store-slash text-muted" style="font-size: 64px; margin-bottom: 20px;"></i>
          <h3>No matching templates found</h3>
          <p class="text-muted">Try choosing another category or clearing your search filter.</p>
        </div>
      </div>

      <!-- Steps & Stats section -->
      <div class="steps-stats-section mt-100" data-aos="fade-up" style="background:#fff; border-top:1px solid #f1f5f9; padding-top:60px; margin-bottom:60px;">
        <div class="row align-items-center mb-60">
          <!-- Left Column: Rocket Image -->
          <div class="col-lg-6 col-md-12 text-center position-relative mb-5 mb-lg-0">
            <div class="rocket-visual-wrap">
              <img src="{{ asset('images/rocket_leftside.png') }}" alt="Rocket Launch" class="img-fluid rocket-main-img" style="max-width: 85%;">
            </div>
          </div>
          
          <!-- Right Column: 4 Simple Steps -->
          <div class="col-lg-6 col-md-12">
            <div class="steps-content-wrap ps-lg-4 text-start">
              <span class="steps-badge d-inline-block">{{ __('Simple Steps. Big Results.') }}</span>
              <h2 class="steps-main-title mb-4" style="font-weight: 800; font-size: 34px; color: #1e293b;">{{ __('Launch your dream store in four simple steps.') }}</h2>
              
              <div class="steps-list mt-30">
                <!-- Step 1 -->
                <div class="step-item-custom d-flex align-items-start mb-4" style="gap: 16px;">
                  <div class="step-icon-container step-icon-orange">
                    <i class="fas fa-store"></i>
                  </div>
                  <div class="step-text-container">
                    <h4 class="step-item-title">{{ __('1. Choose Your Theme') }}</h4>
                    <p class="step-item-desc">{{ __('Pick a professional theme that matches your brand.') }}</p>
                  </div>
                </div>
                
                <!-- Step 2 -->
                <div class="step-item-custom d-flex align-items-start mb-4" style="gap: 16px;">
                  <div class="step-icon-container step-icon-pink">
                    <i class="fas fa-cog"></i>
                  </div>
                  <div class="step-text-container">
                    <h4 class="step-item-title">{{ __('2. Customize & Setup') }}</h4>
                    <p class="step-item-desc">{{ __('Add your products, customize design & settings.') }}</p>
                  </div>
                </div>
                
                <!-- Step 3 -->
                <div class="step-item-custom d-flex align-items-start mb-4" style="gap: 16px;">
                  <div class="step-icon-container step-icon-green">
                    <i class="fas fa-credit-card"></i>
                  </div>
                  <div class="step-text-container">
                    <h4 class="step-item-title">{{ __('3. Configure Payments') }}</h4>
                    <p class="step-item-desc">{{ __('Set up payment methods and shipping options.') }}</p>
                  </div>
                </div>
                
                <!-- Step 4 -->
                <div class="step-item-custom d-flex align-items-start mb-4" style="gap: 16px;">
                  <div class="step-icon-container step-icon-blue">
                    <i class="fas fa-rocket"></i>
                  </div>
                  <div class="step-text-container">
                    <h4 class="step-item-title">{{ __('4. Launch Your Store') }}</h4>
                    <p class="step-item-desc">{{ __('Your store is live! Start selling and growing.') }}</p>
                  </div>
                </div>
              </div>
              
              @php
                $defaultPackage = \App\Models\Package::where('status', '1')->where('featured', '1')->first();
                $defaultPackageId = $defaultPackage ? $defaultPackage->id : 1;
              @endphp
              <div class="mt-4" style="text-align: left;">
                <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $defaultPackageId]) }}" class="btn-start-journey">
                  {{ __('Start Your Journey') }} <i class="fas fa-arrow-right ms-2"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Stats Bar at the Bottom -->
        <div class="stats-bar-card mt-5 mb-5" style="border: 1px solid #f1f5f9; border-radius: 16px; padding: 24px 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); background: #ffffff;">
          <div class="row align-items-center justify-content-between g-4">
            <!-- Stat Item 1 -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-6 d-flex align-items-center justify-content-start justify-content-lg-center">
              <div class="stat-item-wrap d-flex align-items-center" style="gap: 16px;">
                <div class="stat-bar-icon-box stat-icon-orange">
                  <i class="fas fa-store"></i>
                </div>
                <div class="stat-bar-info">
                  <h3 class="stat-bar-val">250+</h3>
                  <p class="stat-bar-lbl">{{ __('Live Stores') }}</p>
                </div>
              </div>
            </div>
            
            <!-- Stat Item 2 -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-6 d-flex align-items-center justify-content-start justify-content-lg-center">
              <div class="stat-item-wrap d-flex align-items-center" style="gap: 16px;">
                <div class="stat-bar-icon-box stat-icon-pink">
                  <i class="fas fa-users"></i>
                </div>
                <div class="stat-bar-info">
                  <h3 class="stat-bar-val">5,000+</h3>
                  <p class="stat-bar-lbl">{{ __('Happy Merchants') }}</p>
                </div>
              </div>
            </div>
            
            <!-- Stat Item 3 -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-6 d-flex align-items-center justify-content-start justify-content-lg-center">
              <div class="stat-item-wrap d-flex align-items-center" style="gap: 16px;">
                <div class="stat-bar-icon-box stat-icon-blue">
                  <i class="fas fa-gem"></i>
                </div>
                <div class="stat-bar-info">
                  <h3 class="stat-bar-val">15+</h3>
                  <p class="stat-bar-lbl">{{ __('Premium Themes') }}</p>
                </div>
              </div>
            </div>
            
            <!-- Stat Item 4 -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-6 d-flex align-items-center justify-content-start justify-content-lg-center">
              <div class="stat-item-wrap d-flex align-items-center" style="gap: 16px;">
                <div class="stat-bar-icon-box stat-icon-green">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-bar-info">
                  <h3 class="stat-bar-val">98%</h3>
                  <p class="stat-bar-lbl">{{ __('Customer Satisfaction') }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      @if (count($faqs) > 0)
        <!-- FAQ Section -->
        <div class="template-faq-section mt-20 mb-20" data-aos="fade-up">
          <h2 class="section-title text-center">{{ __('Frequently Asked Questions') }}</h2>
          <p class="section-subtitle text-center mb-5">{{ __('Everything you need to know about our e-commerce store templates.') }}</p>
          
          <div class="faq-accordion-custom">
            @foreach ($faqs as $faq)
              <!-- FAQ Item -->
              <div class="faq-item-custom">
                <div class="faq-question-custom">
                  <span>{{ $faq->question }}</span>
                  <i class="fas fa-chevron-down faq-arrow"></i>
                </div>
                <div class="faq-answer-custom">
                  <p>{{ $faq->answer }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

    </div>
  </div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
      var activeFilter = 'all';
      var searchQuery = '';

      function filterTemplates() {
          var showCount = 0;
          var searchTerms = searchQuery.split(/\s+/).filter(Boolean);

          $('.template-card-item').each(function() {
              var category = $(this).attr('data-category');
              var searchText = $(this).attr('data-search') ? $(this).attr('data-search').toLowerCase() : '';
              var matchCategory = false;
              if (activeFilter === 'all') {
                  matchCategory = true;
              } else if (activeFilter === 'clothing') {
                  matchCategory = (category === 'clothing' || category === 'fashion');
              } else {
                  matchCategory = (category === activeFilter);
              }
              
              var matchSearch = true;
              for (var i = 0; i < searchTerms.length; i++) {
                  if (searchText.indexOf(searchTerms[i]) === -1) {
                      matchSearch = false;
                      break;
                  }
              }

              if (matchCategory && matchSearch) {
                  $(this).removeClass('d-none');
                  showCount++;
              } else {
                  $(this).addClass('d-none');
              }
          });

          if (showCount === 0) {
              $('#noTemplatesMessage').removeClass('d-none');
          } else {
              $('#noTemplatesMessage').addClass('d-none');
          }

            if (typeof AOS !== 'undefined' && AOS.refreshHard) {
              AOS.refreshHard();
            } else if (typeof AOS !== 'undefined' && AOS.refresh) {
              AOS.refresh();
            }
      }

      // Handle search keyup
        $('#templateSearch').on('input', function() {
          searchQuery = $.trim($(this).val()).toLowerCase();
          
          // Automatically switch to 'all' tab when user starts searching
          if (searchQuery !== '') {
              $('.tab-btn').removeClass('active');
              $('.tab-btn[data-filter="all"]').addClass('active');
              activeFilter = 'all';
          }
          
          filterTemplates();
      });

      // Handle category tab clicks
      $('.tab-btn').on('click', function() {
          $('.tab-btn').removeClass('active');
          $(this).addClass('active');
          activeFilter = $(this).data('filter');
          searchQuery = '';
          $('#templateSearch').val('');
          filterTemplates();
      });

      // Handle FAQ accordion click
      $('.faq-question-custom').on('click', function() {
          var $item = $(this).closest('.faq-item-custom');
          var $answer = $item.find('.faq-answer-custom');
          
          if ($item.hasClass('active')) {
              $answer.slideUp(250, function() {
                  $item.removeClass('active');
              });
          } else {
              // Close other open items
              $('.faq-item-custom.active').each(function() {
                  $(this).removeClass('active').find('.faq-answer-custom').slideUp(250);
              });
              
              $item.addClass('active');
              $answer.slideDown(250);
          }
      });
  });
</script>
@endsection
