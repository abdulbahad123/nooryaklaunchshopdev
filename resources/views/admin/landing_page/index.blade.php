@extends('admin.layout')

@if (!empty(@$language) && @$language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('styles')
<style>
  .lp-editor-tabs .nav-link {
    font-weight: 600;
    padding: 12px 18px;
    border-radius: 10px !important;
    color: #495057;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    margin-right: 8px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
    font-size: 13.5px;
  }
  .lp-editor-tabs .nav-link:hover {
    background: #eefbf3;
    color: #059669;
  }
  .lp-editor-tabs .nav-link.active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
    border-color: #059669;
  }
  .lp-card {
    border-radius: 14px;
    border: 1px solid #eef0f3;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    margin-bottom: 24px;
  }
  .lp-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #f0f2f5;
    padding: 18px 24px;
    border-radius: 14px 14px 0 0;
  }
  .lp-card-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .sticky-save-bar {
    position: sticky;
    bottom: 20px;
    z-index: 99;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(8px);
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  }
  .sub-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 16px;
  }
</style>
@endsection

@section('content')
  <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
      <h4 class="page-title font-weight-bold mb-1">{{ __('WhatsApp Landing Page Editor') }}</h4>
      <ul class="breadcrumbs p-0 m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
        </li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Pages') }}</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Landing Page') }}</a></li>
      </ul>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('front.landing') }}" target="_blank" class="btn btn-outline-success font-weight-bold btn-sm px-3 mr-2">
        <i class="fas fa-external-link-alt mr-1"></i> {{ __('View Live Landing Page') }}
      </a>
      <div style="min-width: 170px;">
        @include('admin.partials.languages')
      </div>
    </div>
  </div>

  @php
    $hero = $settings['hero'] ?? [];
    $stack = $settings['stack'] ?? [];
    $modules = $settings['modules'] ?? [];
    $pricing = $settings['pricing'] ?? [];
    $testimonials = $settings['testimonials'] ?? [];
    $faqs = $settings['faqs'] ?? [];
    $ctaFooter = $settings['cta_footer'] ?? [];
  @endphp

  {{-- Section Tabs --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
      <ul class="nav nav-pills lp-editor-tabs m-0" id="lpTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="tab-hero-btn" data-toggle="pill" href="#tab-hero" role="tab">
            <i class="fas fa-desktop"></i>
            <span>{{ __('1. Hero Section') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-stack-btn" data-toggle="pill" href="#tab-stack" role="tab">
            <i class="fas fa-layer-group"></i>
            <span>{{ __('2. Live Module Stack') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-modules-btn" data-toggle="pill" href="#tab-modules" role="tab">
            <i class="fas fa-th"></i>
            <span>{{ __('3. Product Modules (8)') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-pricing-btn" data-toggle="pill" href="#tab-pricing" role="tab">
            <i class="fas fa-tags"></i>
            <span>{{ __('4. Pricing Plans (4)') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-reviews-btn" data-toggle="pill" href="#tab-reviews" role="tab">
            <i class="fas fa-star"></i>
            <span>{{ __('5. Testimonials') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-faqs-btn" data-toggle="pill" href="#tab-faqs" role="tab">
            <i class="fas fa-question-circle"></i>
            <span>{{ __('6. FAQs') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-cta-btn" data-toggle="pill" href="#tab-cta" role="tab">
            <i class="fas fa-bullhorn"></i>
            <span>{{ __('7. Bottom CTA & Footer') }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <form id="ajaxForm" action="{{ route('admin.landing_page.update', $lang_id) }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="tab-content" id="lpTabsContent">

      <!-- ==========================================
           TAB 1: HERO SECTION
           ========================================== -->
      <div class="tab-pane fade show active" id="tab-hero" role="tabpanel">
        <div class="card lp-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="lp-card-title">
              <i class="fas fa-desktop text-success"></i>
              {{ __('Hero Section (Headlines, Buttons & Counter Stats)') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('Top hero banner matching Reference Image 3') }}</span>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Top Pill Badge') }}</label>
                <input type="text" name="hero_badge" class="form-control" value="{{ $hero['badge'] ?? 'ALL-IN-ONE WHATSAPP MARKETING TOOL' }}">
              </div>
              <div class="col-md-8 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Hero Heading / Title (Supports HTML span for green highlight)') }}</label>
                <textarea name="hero_title" rows="2" class="form-control font-weight-bold">{{ $hero['title'] ?? 'WhatsApp operations,<br><span style="color: #059669;">campaigns,</span> and<br>automation' }}</textarea>
                <small class="text-muted">{{ __('Supports HTML tags such as &lt;span style="color: #059669;"&gt; or &lt;br&gt;.') }}</small>
              </div>

              <div class="col-md-6 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Bold Subtitle') }}</label>
                <input type="text" name="hero_subtitle_bold" class="form-control font-weight-bold" value="{{ $hero['subtitle_bold'] ?? 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.' }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Descriptive Paragraph') }}</label>
                <textarea name="hero_subtitle_desc" rows="2" class="form-control">{{ $hero['subtitle_desc'] ?? ($hero['subtitle'] ?? 'Built around the modules teams actually use every day: bulk outreach, smart replies, auto replies, chatbots, contacts, participant export, profile lookups, and reporting.') }}</textarea>
              </div>

              <div class="col-md-3 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Button 1 Text (Solid Green)') }}</label>
                <input type="text" name="hero_btn1_text" class="form-control form-control-sm" value="{{ $hero['btn1_text'] ?? 'Create your workspace' }}">
              </div>
              <div class="col-md-3 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Button 1 Target Link') }}</label>
                <input type="text" name="hero_btn1_url" class="form-control form-control-sm" value="{{ $hero['btn1_url'] ?? '/pricing' }}">
              </div>
              <div class="col-md-3 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Button 2 Text (Outline)') }}</label>
                <input type="text" name="hero_btn2_text" class="form-control form-control-sm" value="{{ $hero['btn2_text'] ?? 'Explore modules' }}">
              </div>
              <div class="col-md-3 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Button 2 Target Link') }}</label>
                <input type="text" name="hero_btn2_url" class="form-control form-control-sm" value="{{ $hero['btn2_url'] ?? '#modules' }}">
              </div>

              <div class="col-12 mt-3"><h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-chart-line text-success mr-1"></i> {{ __('Hero 3 Bottom Feature / Stat Boxes') }}</h6></div>
              <div class="col-md-4 mb-3">
                <div class="p-3 bg-light rounded border">
                  <label class="small font-weight-bold text-muted">{{ __('Box 1 (Modules Count & Label)') }}</label>
                  <input type="text" name="hero_stat1_val" class="form-control form-control-sm mb-2 font-weight-bold text-success" value="{{ $hero['stat1_val'] ?? '8' }}">
                  <input type="text" name="hero_stat1_lbl" class="form-control form-control-sm" value="{{ $hero['stat1_lbl'] ?? 'Operational modules included' }}">
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="p-3 bg-light rounded border">
                  <label class="small font-weight-bold text-muted">{{ __('Box 2 (Workflow Stat & Label)') }}</label>
                  <input type="text" name="hero_stat2_val" class="form-control form-control-sm mb-2 font-weight-bold text-success" value="{{ $hero['stat2_val'] ?? '1' }}">
                  <input type="text" name="hero_stat2_lbl" class="form-control form-control-sm" value="{{ $hero['stat2_lbl'] ?? 'Unified WhatsApp workflow' }}">
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="p-3 bg-light rounded border">
                  <label class="small font-weight-bold text-muted">{{ __('Box 3 (Coverage Stat & Label)') }}</label>
                  <input type="text" name="hero_stat3_val" class="form-control form-control-sm mb-2 font-weight-bold text-success" value="{{ $hero['stat3_val'] ?? '24/7' }}">
                  <input type="text" name="hero_stat3_lbl" class="form-control form-control-sm" value="{{ $hero['stat3_lbl'] ?? 'Automation & reply coverage' }}">
                </div>
              </div>

              <div class="col-12 mt-3"><h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-mobile-alt text-success mr-1"></i> {{ __('Dashboard & Mobile Mockup Live Metrics') }}</h6></div>
              <div class="col-md-2 mb-2">
                <label class="small text-muted">{{ __('Total Messages') }}</label>
                <input type="text" name="hero_total_messages" class="form-control form-control-sm" value="{{ $hero['total_messages'] ?? '125,890' }}">
              </div>
              <div class="col-md-2 mb-2">
                <label class="small text-muted">{{ __('Delivered') }}</label>
                <input type="text" name="hero_delivered" class="form-control form-control-sm" value="{{ $hero['delivered'] ?? '118,420' }}">
              </div>
              <div class="col-md-2 mb-2">
                <label class="small text-muted">{{ __('Replied') }}</label>
                <input type="text" name="hero_replied" class="form-control form-control-sm" value="{{ $hero['replied'] ?? '28,460' }}">
              </div>
              <div class="col-md-3 mb-2">
                <label class="small text-muted">{{ __('Active Contacts') }}</label>
                <input type="text" name="hero_active_contacts" class="form-control form-control-sm" value="{{ $hero['active_contacts'] ?? '96,350' }}">
              </div>
              <div class="col-md-3 mb-2">
                <label class="small text-muted">{{ __('Campaign Delivery Rate') }}</label>
                <input type="text" name="hero_delivery_rate" class="form-control form-control-sm font-weight-bold text-success" value="{{ $hero['delivery_rate'] ?? '95.8%' }}">
              </div>

            </div>
          </div>
        </div>
      </div>

      <!-- ==========================================
           TAB 2: LIVE MODULE STACK (4 CARDS)
           ========================================== -->
      <div class="tab-pane fade" id="tab-stack" role="tabpanel">
        <div class="card lp-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="lp-card-title">
              <i class="fas fa-layer-group text-primary"></i>
              {{ __('Live Module Stack Section (4 Cards)') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('Workspace Modules row matching Reference Image 3') }}</span>
          </div>
          <div class="card-body">
            <div class="row mb-4">
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Badge') }}</label>
                <input type="text" name="stack_badge" class="form-control" value="{{ $stack['badge'] ?? 'LIVE MODULE STACK' }}">
              </div>
              <div class="col-md-8 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Heading') }}</label>
                <input type="text" name="stack_title" class="form-control" value="{{ $stack['title'] ?? 'Whatsappbulk Workspace' }}">
              </div>
              <div class="col-12 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Subtitle') }}</label>
                <input type="text" name="stack_subtitle" class="form-control" value="{{ $stack['subtitle'] ?? 'Everything you need to run WhatsApp at scale' }}">
              </div>
            </div>

            <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-clone text-success mr-1"></i> {{ __('4 Workspace Stack Cards') }}</h6>
            <div class="row">
              @foreach ($stack['cards'] ?? [] as $i => $card)
                <div class="col-lg-6 mb-3">
                  <div class="sub-box h-100">
                    <span class="badge badge-success mb-2">{{ __('Card') }} #{{ $i + 1 }}</span>
                    <div class="row">
                      <div class="col-md-6 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Overline Tag') }}</label>
                        <input type="text" name="stack_cards[{{ $i }}][tag]" class="form-control form-control-sm" value="{{ $card['tag'] ?? '' }}">
                      </div>
                      <div class="col-md-6 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Card Title') }}</label>
                        <input type="text" name="stack_cards[{{ $i }}][title]" class="form-control form-control-sm font-weight-bold" value="{{ $card['title'] ?? '' }}">
                      </div>
                      <div class="col-12 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Description') }}</label>
                        <textarea name="stack_cards[{{ $i }}][desc]" rows="2" class="form-control form-control-sm">{{ $card['desc'] ?? '' }}</textarea>
                      </div>
                      <div class="col-md-6 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('FontAwesome Icon Class') }}</label>
                        <input type="text" name="stack_cards[{{ $i }}][icon]" class="form-control form-control-sm" value="{{ $card['icon'] ?? 'fas fa-check-circle' }}">
                      </div>
                      <div class="col-md-6 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Target Link') }}</label>
                        <input type="text" name="stack_cards[{{ $i }}][url]" class="form-control form-control-sm" value="{{ $card['url'] ?? '#pricing' }}">
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        </div>
      </div>

      <!-- ==========================================
           TAB 3: PRODUCT MODULES (8 CARDS)
           ========================================== -->
      <div class="tab-pane fade" id="tab-modules" role="tabpanel">
        <div class="card lp-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="lp-card-title">
              <i class="fas fa-th text-warning"></i>
              {{ __('Product Modules Section (8 Features Grid)') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('2x4 Grid matching Reference Image 4') }}</span>
          </div>
          <div class="card-body">
            <div class="row mb-4">
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Badge') }}</label>
                <input type="text" name="modules_badge" class="form-control" value="{{ $modules['badge'] ?? 'PRODUCT MODULES' }}">
              </div>
              <div class="col-md-8 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Heading') }}</label>
                <input type="text" name="modules_title" class="form-control font-weight-bold" value="{{ $modules['title'] ?? 'Built from the WhatsApp features teams actually need' }}">
              </div>
              <div class="col-12 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Subtitle') }}</label>
                <textarea name="modules_subtitle" rows="2" class="form-control">{{ $modules['subtitle'] ?? 'Every module supports a real operational job, from outreach and automation to contact management and reporting.' }}</textarea>
              </div>
            </div>

            <div class="row">
              @foreach ($modules['items'] ?? [] as $i => $mod)
                <div class="col-md-6 col-lg-3 mb-3">
                  <div class="sub-box h-100">
                    <span class="badge badge-secondary mb-2">{{ __('Module') }} #{{ $i + 1 }}</span>
                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Module Title') }}</label>
                      <input type="text" name="modules_items[{{ $i }}][title]" class="form-control form-control-sm font-weight-bold" value="{{ $mod['title'] ?? '' }}">
                    </div>
                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Description') }}</label>
                      <textarea name="modules_items[{{ $i }}][desc]" rows="3" class="form-control form-control-sm" style="font-size: 12px;">{{ $mod['desc'] ?? '' }}</textarea>
                    </div>
                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                      <input type="text" name="modules_items[{{ $i }}][icon]" class="form-control form-control-sm" value="{{ $mod['icon'] ?? 'fas fa-check' }}">
                    </div>
                    <div class="form-group p-0 mb-0">
                      <label class="small text-muted font-weight-bold">{{ __('Icon Color Hex') }}</label>
                      <input type="text" name="modules_items[{{ $i }}][color]" class="form-control form-control-sm" value="{{ $mod['color'] ?? '#10b981' }}">
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        </div>
      </div>

      <!-- ==========================================
           TAB 4: PRICING SECTION (4 PLANS)
           ========================================== -->
      <div class="tab-pane fade" id="tab-pricing" role="tabpanel">
        <div class="card lp-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="lp-card-title">
              <i class="fas fa-tags text-success"></i>
              {{ __('Pricing Plans Section (4 Tiers)') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('Starter, Professional, Business, Enterprise') }}</span>
          </div>
          <div class="card-body">
            <div class="row mb-4">
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Badge') }}</label>
                <input type="text" name="pricing_badge" class="form-control" value="{{ $pricing['badge'] ?? 'PRICING' }}">
              </div>
              <div class="col-md-8 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Heading') }}</label>
                <input type="text" name="pricing_title" class="form-control font-weight-bold" value="{{ $pricing['title'] ?? 'Simple and transparent pricing' }}">
              </div>
              <div class="col-12 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Subtitle') }}</label>
                <input type="text" name="pricing_subtitle" class="form-control" value="{{ $pricing['subtitle'] ?? 'Choose the plan that fits your business needs. Upgrade or downgrade anytime.' }}">
              </div>
            </div>

            <div class="row">
              @foreach ($pricing['plans'] ?? [] as $i => $plan)
                <div class="col-md-6 col-lg-3 mb-4">
                  <div class="sub-box h-100 {{ !empty($plan['featured']) ? 'border-success' : '' }}" style="{{ !empty($plan['featured']) ? 'box-shadow: 0 4px 16px rgba(16, 185, 129, 0.15);' : '' }}">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <span class="badge badge-primary font-weight-bold">{{ $plan['name'] ?? 'Plan' }}</span>
                      <label class="small text-muted mb-0">
                        <input type="checkbox" name="pricing_plans[{{ $i }}][featured]" value="1" {{ !empty($plan['featured']) ? 'checked' : '' }}>
                        {{ __('Featured / Popular') }}
                      </label>
                    </div>

                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Plan Name') }}</label>
                      <input type="text" name="pricing_plans[{{ $i }}][name]" class="form-control form-control-sm font-weight-bold" value="{{ $plan['name'] ?? '' }}">
                    </div>

                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Subtitle / Target') }}</label>
                      <input type="text" name="pricing_plans[{{ $i }}][sub]" class="form-control form-control-sm" value="{{ $plan['sub'] ?? '' }}">
                    </div>

                    <div class="row">
                      <div class="col-6 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Price') }}</label>
                        <input type="text" name="pricing_plans[{{ $i }}][price]" class="form-control form-control-sm font-weight-bold text-success" value="{{ $plan['price'] ?? '' }}">
                      </div>
                      <div class="col-6 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Term') }}</label>
                        <input type="text" name="pricing_plans[{{ $i }}][term]" class="form-control form-control-sm" value="{{ $plan['term'] ?? '/month' }}">
                      </div>
                    </div>

                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Features (one per line)') }}</label>
                      @php
                        $featsStr = is_array($plan['features'] ?? null) ? implode("\n", $plan['features']) : ($plan['features'] ?? '');
                      @endphp
                      <textarea name="pricing_plans[{{ $i }}][features]" rows="5" class="form-control form-control-sm" style="font-size: 12px;">{{ $featsStr }}</textarea>
                    </div>

                    <div class="row">
                      <div class="col-6 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Button Text') }}</label>
                        <input type="text" name="pricing_plans[{{ $i }}][btn_text]" class="form-control form-control-sm" value="{{ $plan['btn_text'] ?? 'Get Started' }}">
                      </div>
                      <div class="col-6 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Button Link') }}</label>
                        <input type="text" name="pricing_plans[{{ $i }}][btn_url]" class="form-control form-control-sm" value="{{ $plan['btn_url'] ?? '/register' }}">
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        </div>
      </div>

      <!-- ==========================================
           TAB 5: TESTIMONIALS (3 REVIEWS)
           ========================================== -->
      <div class="tab-pane fade" id="tab-reviews" role="tabpanel">
        <div class="card lp-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="lp-card-title">
              <i class="fas fa-star text-warning"></i>
              {{ __('Testimonials Section (3 Customer Reviews)') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('Matching Reference Image 5') }}</span>
          </div>
          <div class="card-body">
            <div class="row mb-4">
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Badge') }}</label>
                <input type="text" name="testimonials_badge" class="form-control" value="{{ $testimonials['badge'] ?? 'TESTIMONIALS' }}">
              </div>
              <div class="col-md-8 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Heading') }}</label>
                <input type="text" name="testimonials_title" class="form-control font-weight-bold" value="{{ $testimonials['title'] ?? 'What our customers say' }}">
              </div>
              <div class="col-12 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Subtitle') }}</label>
                <input type="text" name="testimonials_subtitle" class="form-control" value="{{ $testimonials['subtitle'] ?? 'Trusted by businesses worldwide to streamline their WhatsApp operations.' }}">
              </div>
            </div>

            <div class="row">
              @foreach ($testimonials['reviews'] ?? [] as $i => $rev)
                <div class="col-md-4 mb-3">
                  <div class="sub-box h-100">
                    <span class="badge badge-warning text-white mb-2">{{ __('Review') }} #{{ $i + 1 }}</span>
                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Customer Quote') }}</label>
                      <textarea name="testimonials_reviews[{{ $i }}][quote]" rows="4" class="form-control form-control-sm">{{ $rev['quote'] ?? '' }}</textarea>
                    </div>
                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Customer Name') }}</label>
                      <input type="text" name="testimonials_reviews[{{ $i }}][name]" class="form-control form-control-sm font-weight-bold" value="{{ $rev['name'] ?? '' }}">
                    </div>
                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Role & Company') }}</label>
                      <input type="text" name="testimonials_reviews[{{ $i }}][role]" class="form-control form-control-sm" value="{{ $rev['role'] ?? '' }}">
                    </div>
                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Avatar Image URL') }}</label>
                      <input type="text" name="testimonials_reviews[{{ $i }}][avatar]" class="form-control form-control-sm" value="{{ $rev['avatar'] ?? '' }}">
                    </div>
                    <div class="form-group p-0 mb-0">
                      <label class="small text-muted font-weight-bold">{{ __('Stars (1 to 5)') }}</label>
                      <input type="number" min="1" max="5" name="testimonials_reviews[{{ $i }}][stars]" class="form-control form-control-sm" value="{{ $rev['stars'] ?? 5 }}">
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        </div>
      </div>

      <!-- ==========================================
           TAB 6: FAQS (7 QUESTIONS)
           ========================================== -->
      <div class="tab-pane fade" id="tab-faqs" role="tabpanel">
        <div class="card lp-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="lp-card-title">
              <i class="fas fa-question-circle text-info"></i>
              {{ __('Frequently Asked Questions (FAQs)') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('2-Column Accordion matching Reference Image 5') }}</span>
          </div>
          <div class="card-body">
            <div class="row mb-4">
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Badge') }}</label>
                <input type="text" name="faqs_badge" class="form-control" value="{{ $faqs['badge'] ?? 'FAQS' }}">
              </div>
              <div class="col-md-8 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Heading') }}</label>
                <input type="text" name="faqs_title" class="form-control font-weight-bold" value="{{ $faqs['title'] ?? 'Frequently asked questions' }}">
              </div>
              <div class="col-12 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Section Subtitle') }}</label>
                <input type="text" name="faqs_subtitle" class="form-control" value="{{ $faqs['subtitle'] ?? 'Get answers to common questions about Whatsappbulk.' }}">
              </div>
            </div>

            <div class="row">
              @foreach ($faqs['items'] ?? [] as $i => $faq)
                <div class="col-lg-6 mb-3">
                  <div class="sub-box h-100">
                    <span class="badge badge-info mb-2">{{ __('Q&A') }} #{{ $i + 1 }}</span>
                    <div class="form-group p-0 mb-2">
                      <label class="small text-muted font-weight-bold">{{ __('Question') }}</label>
                      <input type="text" name="faqs_items[{{ $i }}][q]" class="form-control form-control-sm font-weight-bold" value="{{ $faq['q'] ?? '' }}">
                    </div>
                    <div class="form-group p-0 mb-0">
                      <label class="small text-muted font-weight-bold">{{ __('Answer') }}</label>
                      <textarea name="faqs_items[{{ $i }}][a]" rows="3" class="form-control form-control-sm">{{ $faq['a'] ?? '' }}</textarea>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        </div>
      </div>

      <!-- ==========================================
           TAB 7: BOTTOM CTA & FOOTER
           ========================================== -->
      <div class="tab-pane fade" id="tab-cta" role="tabpanel">
        <div class="card lp-card">
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="lp-card-title">
              <i class="fas fa-bullhorn text-success"></i>
              {{ __('Bottom CTA Banner & Landing Footer') }}
            </h5>
            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ __('Green gradient card matching Reference Image 5') }}</span>
          </div>
          <div class="card-body">
            <div class="row mb-4">
              <div class="col-md-8 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Bottom CTA Title') }}</label>
                <input type="text" name="cta_title" class="form-control font-weight-bold" value="{{ $ctaFooter['cta_title'] ?? 'Ready to scale your WhatsApp marketing?' }}">
              </div>
              <div class="col-12 mb-3">
                <label class="font-weight-bold text-dark">{{ __('Bottom CTA Subtitle') }}</label>
                <textarea name="cta_subtitle" rows="2" class="form-control">{{ $ctaFooter['cta_subtitle'] ?? 'Connect your WhatsApp account in seconds. Start sending bulk campaigns, automating customer replies, and converting more leads with zero coding required.' }}</textarea>
              </div>

              <div class="col-md-3 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Primary Button Text') }}</label>
                <input type="text" name="cta_btn1_txt" class="form-control form-control-sm font-weight-bold" value="{{ $ctaFooter['cta_btn1_txt'] ?? 'Get Started Free' }}">
              </div>
              <div class="col-md-3 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Primary Button Link') }}</label>
                <input type="text" name="cta_btn1_url" class="form-control form-control-sm" value="{{ $ctaFooter['cta_btn1_url'] ?? '/pricing' }}">
              </div>
              <div class="col-md-3 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Secondary Link Text') }}</label>
                <input type="text" name="cta_btn2_txt" class="form-control form-control-sm" value="{{ $ctaFooter['cta_btn2_txt'] ?? 'View Pricing Plans' }}">
              </div>
              <div class="col-md-3 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Secondary Target Link') }}</label>
                <input type="text" name="cta_btn2_url" class="form-control form-control-sm" value="{{ $ctaFooter['cta_btn2_url'] ?? '#pricing' }}">
              </div>

              <div class="col-12 mt-3"><h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-shoe-prints text-muted mr-1"></i> {{ __('Landing Page Footer Branding') }}</h6></div>
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Brand Name') }}</label>
                <input type="text" name="brand_name" class="form-control form-control-sm" value="{{ $ctaFooter['brand_name'] ?? 'Whatsappbulk' }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Tagline') }}</label>
                <input type="text" name="tagline" class="form-control form-control-sm" value="{{ $ctaFooter['tagline'] ?? 'WhatsApp Marketing & Automation Tool' }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="font-weight-bold text-dark small">{{ __('Copyright Text') }}</label>
                <input type="text" name="copyright" class="form-control form-control-sm" value="{{ $ctaFooter['copyright'] ?? '© 2026 Whatsappbulk. All Rights Reserved.' }}">
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- Sticky Bottom Save Bar --}}
    <div class="sticky-save-bar d-flex align-items-center justify-content-between">
      <span class="text-muted small">
        <i class="fas fa-info-circle text-success mr-1"></i>
        {{ __('All landing page sections will be updated together dynamically.') }}
      </span>
      <button type="submit" class="btn btn-success font-weight-bold px-4" id="submitBtn">
        <i class="fas fa-save mr-1"></i> {{ __('Save Landing Page Changes') }}
      </button>
    </div>

  </form>
@endsection

@section('scripts')
  <script>
    function activateTabFromHash() {
      var hash = window.location.hash;
      if (hash && $('#lpTabs a[href="' + hash + '"]').length) {
        $('#lpTabs a[href="' + hash + '"]').tab('show');
      }
    }
    $(document).ready(function() {
      activateTabFromHash();
      $(window).on('hashchange', activateTabFromHash);
      $('#lpTabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if (history.pushState) {
          history.pushState(null, null, e.target.hash);
        }
      });
    });
  </script>
@endsection