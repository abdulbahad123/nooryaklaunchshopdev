@extends('admin.layout')

@if (!empty(@$data->language) && @$data->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('styles')
<style>
  .home-editor-tabs .nav-link {
    font-weight: 600;
    padding: 12px 20px;
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
  }
  .home-editor-tabs .nav-link:hover {
    background: #eef2f7;
    color: #0b5ed7;
  }
  .home-editor-tabs .nav-link.active {
    background: linear-gradient(135deg, #1269db 0%, #084298 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(18, 105, 219, 0.3);
    border-color: #084298;
  }
  .home-editor-tabs .nav-link i {
    font-size: 1.1rem;
  }
  .editor-section-card {
    border-radius: 14px;
    border: 1px solid #eef0f3;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    margin-bottom: 24px;
  }
  .editor-section-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #f0f2f5;
    padding: 18px 24px;
    border-radius: 14px 14px 0 0;
  }
  .editor-section-card .card-body {
    padding: 24px;
  }
  .editor-section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .field-help-badge {
    font-size: 0.78rem;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #475569;
  }
  .preview-box {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 10px;
    padding: 16px;
    text-align: center;
  }
  .sticky-save-bar {
    position: sticky;
    bottom: 20px;
    z-index: 99;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  }
</style>
@endsection

@section('content')
  <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
      <h4 class="page-title font-weight-bold mb-1">{{ __('Home Page Visual Editor') }}</h4>
      <ul class="breadcrumbs p-0 m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
        </li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Pages') }}</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Home Page') }}</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Images & Texts') }}</a></li>
      </ul>
    </div>
    <div style="min-width: 170px;">
      @include('admin.partials.languages')
    </div>
  </div>

  {{-- Interactive Section Tabs --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
      <ul class="nav nav-pills home-editor-tabs m-0" id="homeEditorTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="tab-hero-btn" data-toggle="pill" href="#tab-hero" role="tab" aria-controls="tab-hero" aria-selected="true">
            <i class="fas fa-desktop text-primary"></i>
            <span>{{ __('Hero Banner & Badges') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-process-btn" data-toggle="pill" href="#tab-process" role="tab" aria-controls="tab-process" aria-selected="false">
            <i class="fas fa-rocket text-danger"></i>
            <span>{{ __('Work Process (5 Steps)') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-dashboard-btn" data-toggle="pill" href="#tab-dashboard" role="tab" aria-controls="tab-dashboard" aria-selected="false">
            <i class="fas fa-tachometer-alt text-info"></i>
            <span>{{ __('Explore Dashboard') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-templates-btn" data-toggle="pill" href="#tab-templates" role="tab" aria-controls="tab-templates" aria-selected="false">
            <i class="fas fa-th-large text-warning"></i>
            <span>{{ __('Store Themes') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-extra-btn" data-toggle="pill" href="#tab-extra" role="tab" aria-controls="tab-extra" aria-selected="false">
            <i class="fas fa-layer-group text-success"></i>
            <span>{{ __('Pricing & Other Sections') }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <form id="ajaxForm" action="{{ route('admin.herosection.update', $lang_id) }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="tab-content" id="homeEditorTabsContent">

      {{-- ======================================================== --}}
      {{-- TAB 1: HERO BANNER & BADGES                              --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade show active" id="tab-hero" role="tabpanel" aria-labelledby="tab-hero-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card editor-section-card">
              <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="editor-section-title">
                  <i class="fas fa-desktop text-primary"></i>
                  {{ __('Hero Section (Main Banner, Header & Trust Badges)') }}
                </h5>
                <span class="field-help-badge">{{ __('Top of Homepage') }}</span>
              </div>
              <div class="card-body">
                <div class="row">
                  {{-- Hero Image --}}
                  <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="preview-box">
                      <label class="font-weight-bold text-dark d-block mb-2">{{ __('Hero Banner Graphic') }}</label>
                      <div class="showImage mb-3">
                        <img
                          src="{{ !empty(@$data->image) ? asset('assets/front/img/' . @$data->image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="Hero Graphic" class="img-thumbnail w-100" style="max-height: 240px; object-fit: contain;">

                        @if (!empty(@$data->image))
                          <div class="mt-2">
                            <x-remove-button
                              url="{{ route('admin.herosection.removeImg', ['language_id' => $lang_id]) }}"
                              name="image" type="image"/>
                          </div>
                        @endif
                      </div>
                      <div role="button" class="btn btn-outline-primary btn-sm upload-btn" id="image">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> {{ __('Choose Graphic') }}
                        <input type="file" class="img-input" name="image">
                      </div>
                      <p id="errimage" class="mb-0 text-danger em"></p>
                      <small class="text-muted d-block mt-2">{{ __('Recommended: 755x570 PNG / JPG') }}</small>
                    </div>
                  </div>

                  {{-- Hero Text Inputs --}}
                  <div class="col-lg-8">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Top Floating Pill / Badge Text') }}</label>
                          <input name="hero_section_desc" class="form-control" value="{{ @$data->hero_section_desc }}" placeholder="#1 Ecommerce Builder For Sales & Growth 🚀">
                          <p id="errhero_section_desc" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Main Headline / Title') }}</label>
                          <input name="hero_section_title" class="form-control" value="{{ @$data->hero_section_title }}" placeholder="Launch your high-converting online store in minutes">
                          <p id="errhero_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Hero Subtitle / Description Text') }}</label>
                          <textarea name="hero_section_text" rows="3" class="form-control" placeholder="All-in-one eCommerce engine with automated orders, WhatsApp notifications, and premium design templates.">{{ @$data->hero_section_text }}</textarea>
                          <p id="errhero_section_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Primary Button Text') }}</label>
                          <input type="text" class="form-control" name="hero_section_button_text" value="{{ @$data->hero_section_button_text }}" placeholder="Start 14-Day Free Trial">
                          <p id="errhero_section_button_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Primary Button URL') }}</label>
                          <input type="text" class="form-control ltr" name="hero_section_button_url" value="{{ @$data->hero_section_button_url }}" placeholder="/pricing">
                          <p id="errhero_section_button_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Optional Video URL (YouTube / Vimeo / Demo video)') }}</label>
                          <input type="text" class="form-control ltr" name="hero_section_video_url" value="{{ @$data->hero_section_video_url }}" placeholder="https://www.youtube.com/watch?v=...">
                          <p id="errhero_section_video_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- Hero Trust Badges --}}
                <div class="border-top pt-4 mt-3">
                  <h6 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-shield-alt text-primary mr-2"></i>{{ __('Hero Trust Badges (Displayed right below CTA buttons)') }}
                  </h6>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="p-3 bg-light rounded mb-3 mb-md-0">
                        <span class="badge badge-primary mb-2">{{ __('Badge 1') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Title') }}</label>
                          <input name="hero_badge1_title" class="form-control form-control-sm" value="{{ @$data->hero_badge1_title }}" placeholder="10-Days Money">
                          <p id="errhero_badge1_title" class="em text-danger mb-0"></p>
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Subtitle') }}</label>
                          <input name="hero_badge1_subtitle" class="form-control form-control-sm" value="{{ @$data->hero_badge1_subtitle }}" placeholder="Back Guarantee">
                          <p id="errhero_badge1_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="p-3 bg-light rounded mb-3 mb-md-0">
                        <span class="badge badge-success mb-2">{{ __('Badge 2') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Title') }}</label>
                          <input name="hero_badge2_title" class="form-control form-control-sm" value="{{ @$data->hero_badge2_title }}" placeholder="Launch store">
                          <p id="errhero_badge2_title" class="em text-danger mb-0"></p>
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Subtitle') }}</label>
                          <input name="hero_badge2_subtitle" class="form-control form-control-sm" value="{{ @$data->hero_badge2_subtitle }}" placeholder="Instantly">
                          <p id="errhero_badge2_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="p-3 bg-light rounded">
                        <span class="badge badge-info mb-2">{{ __('Badge 3') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Title') }}</label>
                          <input name="hero_badge3_title" class="form-control form-control-sm" value="{{ @$data->hero_badge3_title }}" placeholder="24/7 Expert">
                          <p id="errhero_badge3_title" class="em text-danger mb-0"></p>
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Subtitle') }}</label>
                          <input name="hero_badge3_subtitle" class="form-control form-control-sm" value="{{ @$data->hero_badge3_subtitle }}" placeholder="Support">
                          <p id="errhero_badge3_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 2: WORK PROCESS (5 SIMPLE STEPS - SCREENSHOT 1)       --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-process" role="tabpanel" aria-labelledby="tab-process-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card editor-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="editor-section-title">
                  <i class="fas fa-rocket text-danger"></i>
                  {{ __('Work Process Section (5 Simple Steps - Screenshot 1 Layout)') }}
                </h5>
                <div>
                  <a href="{{ route('admin.process.index') . '?language=' . request()->input('language', 'en') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list-ol mr-1"></i> {{ __('Manage Individual Steps (1 to 5)') }}
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  {{-- Side Rocket Graphic --}}
                  <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="preview-box">
                      <label class="font-weight-bold text-dark d-block mb-2">{{ __('Work Process Side Graphic') }}</label>
                      <div class="showImage mb-3">
                        <img
                          src="{{ !empty(@$data->work_process_image) ? asset('assets/front/img/' . @$data->work_process_image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="Work Process Rocket" class="img-thumbnail w-100" style="max-height: 240px; object-fit: contain;">

                        @if (!empty(@$data->work_process_image))
                          <div class="mt-2">
                            <x-remove-button
                              url="{{ route('admin.herosection.removeImg', ['language_id' => $lang_id]) }}"
                              name="work_process_image" type="image"/>
                          </div>
                        @endif
                      </div>
                      <div role="button" class="btn btn-outline-primary btn-sm upload-btn" id="work_process_image">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> {{ __('Choose Graphic') }}
                        <input type="file" class="img-input" name="work_process_image">
                      </div>
                      <p id="errwork_process_image" class="mb-0 text-danger em"></p>
                      <small class="text-muted d-block mt-2">{{ __('Recommended: 600x600 Transparent PNG (e.g. 3D Rocket mockup)') }}</small>
                    </div>
                  </div>

                  {{-- Process Texts --}}
                  <div class="col-lg-8">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Process Pill / Badge Subtitle') }}</label>
                          <input name="work_process_section_subtitle" class="form-control"
                            value="{{ @$data->work_process_section_subtitle }}" placeholder="SIMPLE STEPS. BIG RESULTS.">
                          <p id="errwork_process_section_subtitle" class="em text-danger mb-0"></p>
                          <small class="text-muted">{{ __('E.g. "SIMPLE STEPS. BIG RESULTS." (as shown in Screenshot 1)') }}</small>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Process Main Title') }}</label>
                          <input name="work_process_section_title" class="form-control"
                            value="{{ @$data->work_process_section_title }}" placeholder="Launch your dream store in five simple steps.">
                          <p id="errwork_process_section_title" class="em text-danger mb-0"></p>
                          <small class="text-muted">{{ __('E.g. "Launch your dream store in five simple steps."') }}</small>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Process Action Button Text') }}</label>
                          <input name="work_process_btn_text" class="form-control"
                            value="{{ @$data->work_process_btn_text }}" placeholder="Start Your Journey">
                          <p id="errwork_process_btn_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Process Action Button URL') }}</label>
                          <input name="work_process_btn_url" class="form-control ltr"
                            value="{{ @$data->work_process_btn_url }}" placeholder="/pricing">
                          <p id="errwork_process_btn_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      {{-- Overview of the 5 steps --}}
                      <div class="col-lg-12 mt-3">
                        <div class="p-3 bg-light rounded border">
                          <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold text-dark"><i class="fas fa-check-circle text-success mr-1"></i> {{ __('Default 5 Simple Steps (Click "Manage Individual Steps" to customize each item)') }}:</span>
                          </div>
                          <ol class="mb-0 pl-3 text-muted">
                            <li><strong>{{ __('1. Select Your Plan') }}</strong> &mdash; {{ __('Choose the perfect pricing plan that fits your business needs.') }}</li>
                            <li><strong>{{ __('2. Choose Your Theme') }}</strong> &mdash; {{ __('Pick a professional theme that matches your brand.') }}</li>
                            <li><strong>{{ __('3. Customize & Setup') }}</strong> &mdash; {{ __('Add your products, customize design & settings.') }}</li>
                            <li><strong>{{ __('4. Configure Payments') }}</strong> &mdash; {{ __('Set up payment methods and shipping options.') }}</li>
                            <li><strong>{{ __('5. Launch Your Store') }}</strong> &mdash; {{ __('Your store is live! Start selling and growing.') }}</li>
                          </ol>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 3: EXPLORE DASHBOARD (SCREENSHOT 2)                  --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-dashboard" role="tabpanel" aria-labelledby="tab-dashboard-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card editor-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="editor-section-title">
                  <i class="fas fa-tachometer-alt text-info"></i>
                  {{ __('Explore Dashboard Section (Screenshot 2 Layout)') }}
                </h5>
                <div>
                  <a href="{{ route('admin.feature.index') . '?language=' . request()->input('language', 'en') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-cubes mr-1"></i> {{ __('Manage 4 Right Feature Cards') }}
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  {{-- Center Dashboard Mockup Image --}}
                  <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="preview-box">
                      <label class="font-weight-bold text-dark d-block mb-2">{{ __('Center Dashboard Graphic') }}</label>
                      <div class="showImage mb-3">
                        @php
                          $hasCustomDash = !empty(@$data->dashboard_image) && file_exists(public_path('assets/front/img/' . @$data->dashboard_image));
                        @endphp
                        <img
                          src="{{ $hasCustomDash ? asset('assets/front/img/' . @$data->dashboard_image) : asset('images/user_dashboard.png') }}"
                          alt="Dashboard Preview Mockup" class="img-thumbnail w-100" style="max-height: 240px; object-fit: contain;">

                        @if (!empty(@$data->dashboard_image))
                          <div class="mt-2">
                            <x-remove-button
                              url="{{ route('admin.herosection.removeImg', ['language_id' => $lang_id]) }}"
                              name="dashboard_image" type="image"/>
                          </div>
                        @endif
                      </div>
                      <div role="button" class="btn btn-outline-info btn-sm upload-btn" id="dashboard_image">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> {{ __('Choose Dashboard Image') }}
                        <input type="file" class="img-input" name="dashboard_image">
                      </div>
                      <p id="errdashboard_image" class="mb-0 text-danger em"></p>
                      <small class="text-muted d-block mt-2">{{ __('Recommended: 1200x700 PNG / JPG (Replaces the center dashboard card in Screenshot 2)') }}</small>
                    </div>
                  </div>

                  {{-- Dashboard Texts & Bullets --}}
                  <div class="col-lg-8">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Badge / Subtitle Pill') }}</label>
                          <input name="features_section_subtitle" class="form-control"
                            value="{{ @$data->features_section_subtitle }}" placeholder="EVERYTHING UNDER ONE ROOF">
                          <p id="errfeatures_section_subtitle" class="em text-danger mb-0"></p>
                          <small class="text-muted">{{ __('E.g. "EVERYTHING UNDER ONE ROOF"') }}</small>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Dashboard Section Title') }}</label>
                          <input name="features_section_title" class="form-control"
                            value="{{ @$data->features_section_title }}" placeholder="Powerful Dashboard Built for Growth">
                          <p id="errfeatures_section_title" class="em text-danger mb-0"></p>
                          <small class="text-muted">{{ __('E.g. "Powerful Dashboard Built for Growth"') }}</small>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Dashboard Description') }}</label>
                          <textarea name="features_section_text" rows="3" class="form-control" placeholder="From analytics to orders, products to customers, manage every part of your business from one smart dashboard.">{{ @$data->features_section_text }}</textarea>
                          <p id="errfeatures_section_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('CTA Button Text') }}</label>
                          <input type="text" class="form-control" name="features_section_btn_text"
                            value="{{ @$data->features_section_btn_text }}" placeholder="Explore Dashboard">
                          <p id="errfeatures_section_btn_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('CTA Button URL') }}</label>
                          <input type="text" class="form-control ltr" name="features_section_btn_url"
                            value="{{ @$data->features_section_btn_url }}" placeholder="/pricing">
                          <p id="errfeatures_section_btn_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Optional Video URL') }}</label>
                          <input type="text" class="form-control ltr" name="features_section_video_url"
                            value="{{ @$data->features_section_video_url }}" placeholder="https://www.youtube.com/watch?v=...">
                          <p id="errfeatures_section_video_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- 4 Checklist Bullets --}}
                <div class="border-top pt-4 mt-3">
                  <h6 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-check-double text-success mr-2"></i>{{ __('4 Checklist Bullets (Displayed on the left side below description)') }}
                  </h6>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="font-weight-bold"><span class="badge badge-success mr-1">✓</span> {{ __('Bullet 1') }}</label>
                        <input name="features_bullet1" class="form-control" value="{{ @$data->features_bullet1 }}" placeholder="Real-time sales & analytics">
                        <p id="errfeatures_bullet1" class="em text-danger mb-0"></p>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="font-weight-bold"><span class="badge badge-success mr-1">✓</span> {{ __('Bullet 2') }}</label>
                        <input name="features_bullet2" class="form-control" value="{{ @$data->features_bullet2 }}" placeholder="Manage products & inventory">
                        <p id="errfeatures_bullet2" class="em text-danger mb-0"></p>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="font-weight-bold"><span class="badge badge-success mr-1">✓</span> {{ __('Bullet 3') }}</label>
                        <input name="features_bullet3" class="form-control" value="{{ @$data->features_bullet3 }}" placeholder="Track orders, payments & shipping">
                        <p id="errfeatures_bullet3" class="em text-danger mb-0"></p>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="font-weight-bold"><span class="badge badge-success mr-1">✓</span> {{ __('Bullet 4') }}</label>
                        <input name="features_bullet4" class="form-control" value="{{ @$data->features_bullet4 }}" placeholder="Customers, coupons & reports">
                        <p id="errfeatures_bullet4" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 4: STORE THEMES & TEMPLATES (SCREENSHOT 3)           --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-templates" role="tabpanel" aria-labelledby="tab-templates-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card editor-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="editor-section-title">
                  <i class="fas fa-th-large text-warning"></i>
                  {{ __('Store Themes & Preview Templates Section (Screenshot 3)') }}
                </h5>
                <div>
                  <a href="{{ route('admin.theme.version') }}" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-palette mr-1"></i> {{ __('Theme Settings & Catalog') }}
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Section Title') }}</label>
                      <input name="template_section_title" class="form-control"
                        value="{{ @$data->template_section_title }}" placeholder="See Our Modern Ecom Store Designs">
                      <p id="errtemplate_section_title" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Section Subtitle / Description') }}</label>
                      <input name="template_section_subtitle" class="form-control"
                        value="{{ @$data->template_section_subtitle }}" placeholder="Pick from modern responsive themes built for higher conversions">
                      <p id="errtemplate_section_subtitle" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-12 mt-2">
                    <div class="alert alert-info border-0 rounded" style="background: #e0f2fe; color: #0369a1;">
                      <i class="fas fa-info-circle mr-1"></i>
                      {{ __('Admin preview buttons have been cleanly removed from the theme cards on both the home page and /templates page per your design instructions.') }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 5: PRICING & OTHER SECTIONS                          --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-extra" role="tabpanel" aria-labelledby="tab-extra-btn">
        <div class="row">
          {{-- Pricing --}}
          <div class="col-lg-6">
            <div class="card editor-section-card">
              <div class="card-header">
                <h5 class="editor-section-title">
                  <i class="fas fa-tags text-success"></i>
                  {{ __('Pricing Section') }}
                </h5>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Pricing Title') }}</label>
                  <input name="pricing_section_title" class="form-control"
                    value="{{ @$data->pricing_section_title }}" placeholder="Flexible Plans for Every Stage">
                  <p id="errpricing_section_title" class="em text-danger mb-0"></p>
                </div>
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Pricing Subtitle') }}</label>
                  <input name="pricing_section_subtitle" class="form-control"
                    value="{{ @$data->pricing_section_subtitle }}" placeholder="Transparent pricing with zero hidden fees">
                  <p id="errpricing_section_subtitle" class="em text-danger mb-0"></p>
                </div>
              </div>
            </div>
          </div>

          {{-- Bottom CTA Banner --}}
          <div class="col-lg-6">
            <div class="card editor-section-card">
              <div class="card-header">
                <h5 class="editor-section-title">
                  <i class="fas fa-bullhorn text-primary"></i>
                  {{ __('Bottom CTA Banner (Ready to Launch?)') }}
                </h5>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('CTA Heading') }}</label>
                  <input name="featured_shop_section_title" class="form-control"
                    value="{{ @$data->featured_shop_section_title }}" placeholder="Ready to Launch Your Dream Store?">
                  <p id="errfeatured_shop_section_title" class="em text-danger mb-0"></p>
                </div>
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('CTA Subtitle') }}</label>
                  <input name="featured_shop_section_subtitle" class="form-control"
                    value="{{ @$data->featured_shop_section_subtitle }}" placeholder="Join hundreds of successful store owners today">
                  <p id="errfeatured_shop_section_subtitle" class="em text-danger mb-0"></p>
                </div>
              </div>
            </div>
          </div>

          {{-- Partners Section --}}
          <div class="col-lg-4">
            <div class="card editor-section-card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="editor-section-title">
                  <i class="fas fa-handshake text-muted"></i>
                  {{ __('Partners') }}
                </h5>
                <a href="{{ route('admin.partner.index') . '?language=' . request()->input('language', 'en') }}" class="btn btn-xs btn-outline-secondary">
                  {{ __('Logos') }}
                </a>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Title') }}</label>
                  <input name="partner_section_title" class="form-control"
                    value="{{ @$data->partner_section_title }}">
                  <p id="errpartner_section_title" class="em text-danger mb-0"></p>
                </div>
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Subtitle') }}</label>
                  <input name="partner_section_subtitle" class="form-control"
                    value="{{ @$data->partner_section_subtitle }}">
                  <p id="errpartner_section_subtitle" class="em text-danger mb-0"></p>
                </div>
              </div>
            </div>
          </div>

          {{-- Testimonials --}}
          <div class="col-lg-4">
            <div class="card editor-section-card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="editor-section-title">
                  <i class="fas fa-comment-dots text-info"></i>
                  {{ __('Testimonials') }}
                </h5>
                <a href="{{ route('admin.testimonial.index') . '?language=' . request()->input('language', 'en') }}" class="btn btn-xs btn-outline-secondary">
                  {{ __('Reviews') }}
                </a>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Title') }}</label>
                  <input name="testimonial_section_title" class="form-control"
                    value="{{ @$data->testimonial_section_title }}">
                  <p id="errtestimonial_section_title" class="em text-danger mb-0"></p>
                </div>
              </div>
            </div>
          </div>

          {{-- Blog --}}
          <div class="col-lg-4">
            <div class="card editor-section-card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="editor-section-title">
                  <i class="fas fa-newspaper text-warning"></i>
                  {{ __('Blog') }}
                </h5>
                <a href="{{ route('admin.blog.index') . '?language=' . request()->input('language', 'en') }}" class="btn btn-xs btn-outline-secondary">
                  {{ __('Posts') }}
                </a>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Title') }}</label>
                  <input name="blog_section_title" class="form-control"
                    value="{{ @$data->blog_section_title }}">
                  <p id="errblog_section_title" class="em text-danger mb-0"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- Sticky Bottom Save Bar --}}
    <div class="sticky-save-bar d-flex align-items-center justify-content-between">
      <span class="text-muted small">
        <i class="fas fa-info-circle text-primary mr-1"></i>
        {{ __('Changes made to any tab will be saved together.') }}
      </span>
      <button type="submit" class="btn btn-success font-weight-bold px-4" id="submitBtn">
        <i class="fas fa-save mr-1"></i> {{ __('Save & Update Changes') }}
      </button>
    </div>

  </form>
@endsection

@section('scripts')
  <script src="{{ asset('assets/user/js/image-text.js') }}"></script>
  <script>
    $(document).ready(function() {
      // Activate tab based on URL hash (e.g. #tab-dashboard or #tab-process)
      var hash = window.location.hash;
      if (hash) {
        $('.home-editor-tabs a[href="' + hash + '"]').tab('show');
      }
      $('.home-editor-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if (history.pushState) {
          history.pushState(null, null, e.target.hash);
        } else {
          window.location.hash = e.target.hash;
        }
      });
    });
  </script>
@endsection