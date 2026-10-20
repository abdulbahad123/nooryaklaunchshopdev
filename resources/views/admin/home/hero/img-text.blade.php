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
    border-radius: 12px;
    padding: 18px 16px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .upload-btn {
    position: relative !important;
    overflow: hidden !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    cursor: pointer !important;
    padding: 9px 22px !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    text-align: center !important;
    margin: 8px auto !important;
    transition: all 0.2s ease !important;
  }
  .upload-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(11, 94, 215, 0.15);
  }
  .upload-btn input[type="file"],
  .upload-btn .img-input,
  .upload-btn .thumbnail-input,
  .form-group .upload-btn .img-input,
  .form-group .upload-btn .thumbnail-input {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100% !important;
    height: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    border: 0 !important;
    opacity: 0 !important;
    cursor: pointer !important;
    z-index: 10 !important;
    font-size: 0 !important;
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
      <h4 class="page-title font-weight-bold mb-1">{{ __('Home Section') }}</h4>
      <ul class="breadcrumbs p-0 m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
        </li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Pages') }}</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Home Page') }}</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Home Section') }}</a></li>
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
                          <label class="font-weight-bold">{{ __('Optional Video URL') }}</label>
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

                {{-- Hero Counter Section (4 Stats Banner below Hero) --}}
                <div class="border-top pt-4 mt-4">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="font-weight-bold text-dark m-0">
                      <i class="fas fa-chart-line text-warning mr-2"></i>{{ __('Hero Counter Section (4 Floating Stat Cards below Hero)') }}
                    </h6>
                    <span class="field-help-badge">{{ __('Shown directly under the Hero Banner on the homepage') }}</span>
                  </div>
                  <div class="row">
                    {{-- Hero Stat 1 --}}
                    <div class="col-lg-3 col-md-6 mb-3">
                      <div class="p-3 bg-light rounded h-100 border">
                        <span class="badge badge-warning text-white mb-2">{{ __('Counter 1 (Stores Launched)') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Value / Number') }}</label>
                          <input name="hero_stat1_value" class="form-control form-control-sm" value="{{ @$data->hero_stat1_value ?? '250+' }}" placeholder="250+">
                        </div>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Label / Title') }}</label>
                          <input name="hero_stat1_label" class="form-control form-control-sm" value="{{ @$data->hero_stat1_label ?? 'Stores Launched' }}" placeholder="Stores Launched">
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                          <input name="hero_stat1_icon" class="form-control form-control-sm" value="{{ @$data->hero_stat1_icon ?? 'fas fa-store' }}" placeholder="fas fa-store">
                        </div>
                      </div>
                    </div>

                    {{-- Hero Stat 2 --}}
                    <div class="col-lg-3 col-md-6 mb-3">
                      <div class="p-3 bg-light rounded h-100 border">
                        <span class="badge badge-primary text-white mb-2" style="background:#7c3aed;">{{ __('Counter 2 (Premium Themes)') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Value / Number') }}</label>
                          <input name="hero_stat2_value" class="form-control form-control-sm" value="{{ @$data->hero_stat2_value ?? '15+' }}" placeholder="15+">
                        </div>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Label / Title') }}</label>
                          <input name="hero_stat2_label" class="form-control form-control-sm" value="{{ @$data->hero_stat2_label ?? 'Premium Themes' }}" placeholder="Premium Themes">
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                          <input name="hero_stat2_icon" class="form-control form-control-sm" value="{{ @$data->hero_stat2_icon ?? 'fas fa-gem' }}" placeholder="fas fa-gem">
                        </div>
                      </div>
                    </div>

                    {{-- Hero Stat 3 --}}
                    <div class="col-lg-3 col-md-6 mb-3">
                      <div class="p-3 bg-light rounded h-100 border">
                        <span class="badge badge-success mb-2">{{ __('Counter 3 (Uptime Guarantee)') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Value / Number') }}</label>
                          <input name="hero_stat3_value" class="form-control form-control-sm" value="{{ @$data->hero_stat3_value ?? '99.99%' }}" placeholder="99.99%">
                        </div>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Label / Title') }}</label>
                          <input name="hero_stat3_label" class="form-control form-control-sm" value="{{ @$data->hero_stat3_label ?? 'Uptime Guarantee' }}" placeholder="Uptime Guarantee">
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                          <input name="hero_stat3_icon" class="form-control form-control-sm" value="{{ @$data->hero_stat3_icon ?? 'fas fa-shield-alt' }}" placeholder="fas fa-shield-alt">
                        </div>
                      </div>
                    </div>

                    {{-- Hero Stat 4 --}}
                    <div class="col-lg-3 col-md-6 mb-3">
                      <div class="p-3 bg-light rounded h-100 border">
                        <span class="badge badge-info mb-2">{{ __('Counter 4 (Happy Merchants)') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Value / Number') }}</label>
                          <input name="hero_stat4_value" class="form-control form-control-sm" value="{{ @$data->hero_stat4_value ?? '500+' }}" placeholder="500+">
                        </div>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Label / Title') }}</label>
                          <input name="hero_stat4_label" class="form-control form-control-sm" value="{{ @$data->hero_stat4_label ?? 'Happy Merchants' }}" placeholder="Happy Merchants">
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                          <input name="hero_stat4_icon" class="form-control form-control-sm" value="{{ @$data->hero_stat4_icon ?? 'fas fa-user-friends' }}" placeholder="fas fa-user-friends">
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
      {{-- TAB 2: WORK PROCESS (5 SIMPLE STEPS - SCREENSHOT 3)       --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-process" role="tabpanel" aria-labelledby="tab-process-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card editor-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="editor-section-title">
                  <i class="fas fa-rocket text-danger"></i>
                  {{ __('Work Process Section (5 Simple Steps - Screenshot 3 Layout)') }}
                </h5>
                <div>
                  <a href="{{ route('admin.process.index') . '?language=' . request()->input('language', 'en') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list-ol mr-1"></i> {{ __('Manage in Full Steps List') }}
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  {{-- Side Rocket Graphic --}}
                  <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="preview-box">
                      <label class="font-weight-bold text-dark d-block mb-2">{{ __('Work Process Rocket Graphic') }}</label>
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
                      <small class="text-muted d-block mt-2">{{ __('Recommended: 600x600 Transparent PNG (e.g. 3D Rocket mockup from Screenshot 3)') }}</small>
                    </div>
                  </div>

                  {{-- Process Texts --}}
                  <div class="col-lg-8">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Process Pill / Badge Subtitle') }}</label>
                          <input name="work_process_section_subtitle" class="form-control"
                            value="{{ @$data->work_process_section_subtitle ?? 'SIMPLE STEPS. BIG RESULTS.' }}" placeholder="SIMPLE STEPS. BIG RESULTS.">
                          <p id="errwork_process_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Process Main Title') }}</label>
                          <input name="work_process_section_title" class="form-control"
                            value="{{ @$data->work_process_section_title ?? 'Launch your dream store in five simple steps.' }}" placeholder="Launch your dream store in five simple steps.">
                          <p id="errwork_process_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Process Action Button Text') }}</label>
                          <input name="work_process_btn_text" class="form-control"
                            value="{{ @$data->work_process_btn_text ?? 'Start Your Journey' }}" placeholder="Start Your Journey">
                          <p id="errwork_process_btn_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Process Action Button URL') }}</label>
                          <input name="work_process_btn_url" class="form-control ltr"
                            value="{{ @$data->work_process_btn_url ?? '/pricing' }}" placeholder="/pricing">
                          <p id="errwork_process_btn_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- Editable 5 Steps (Screenshot 3 Content) --}}
                <div class="border-top pt-4 mt-3">
                  <h6 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-list-ol text-danger mr-2"></i>{{ __('5 Simple Steps Content (Screenshot 3 - Editable)') }}
                  </h6>
                  <div class="row">
                    @if(isset($processes) && count($processes) > 0)
                      @foreach($processes->take(5) as $sIdx => $st)
                        <div class="col-lg-12 mb-3">
                          <div class="p-3 bg-light rounded border">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                              <span class="badge badge-danger font-weight-bold">{{ __('Step') }} {{ $sIdx + 1 }}</span>
                              <span class="small text-muted"><i class="{{ $st->icon }}"></i> {{ $st->icon }}</span>
                            </div>
                            <div class="row">
                              <div class="col-md-5">
                                <label class="small text-muted font-weight-bold">{{ __('Step Title') }}</label>
                                <input type="text" class="form-control form-control-sm" name="process_steps[{{ $st->id }}][title]" value="{{ $st->title }}">
                              </div>
                              <div class="col-md-7">
                                <label class="small text-muted font-weight-bold">{{ __('Step Description') }}</label>
                                <input type="text" class="form-control form-control-sm" name="process_steps[{{ $st->id }}][text]" value="{{ $st->text }}">
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    @else
                      <div class="col-12">
                        <p class="text-muted small">{{ __('Default 5 steps will be auto-seeded on save.') }}</p>
                      </div>
                    @endif
                  </div>
                </div>

                {{-- Work Process Counter Bar (4 Stats below 5 Steps) --}}
                <div class="border-top pt-4 mt-4">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="font-weight-bold text-dark m-0">
                      <i class="fas fa-chart-bar text-danger mr-2"></i>{{ __('Work Process Counter Stats (4 Stats below 5 Steps)') }}
                    </h6>
                    <span class="field-help-badge">{{ __('Bottom of How It Works Section on homepage') }}</span>
                  </div>
                  <div class="row">
                    {{-- Process Stat 1 --}}
                    <div class="col-lg-3 col-md-6 mb-3">
                      <div class="p-3 bg-light rounded h-100 border">
                        <span class="badge badge-warning text-white mb-2">{{ __('Counter 1 (Live Stores)') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Value / Number') }}</label>
                          <input name="process_stat1_value" class="form-control form-control-sm" value="{{ @$data->process_stat1_value ?? '250+' }}" placeholder="250+">
                        </div>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Label / Title') }}</label>
                          <input name="process_stat1_label" class="form-control form-control-sm" value="{{ @$data->process_stat1_label ?? 'Live Stores' }}" placeholder="Live Stores">
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                          <input name="process_stat1_icon" class="form-control form-control-sm" value="{{ @$data->process_stat1_icon ?? 'fas fa-store' }}" placeholder="fas fa-store">
                        </div>
                      </div>
                    </div>

                    {{-- Process Stat 2 --}}
                    <div class="col-lg-3 col-md-6 mb-3">
                      <div class="p-3 bg-light rounded h-100 border">
                        <span class="badge text-white mb-2" style="background:#ec4899;">{{ __('Counter 2 (Happy Merchants)') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Value / Number') }}</label>
                          <input name="process_stat2_value" class="form-control form-control-sm" value="{{ @$data->process_stat2_value ?? '500+' }}" placeholder="500+">
                        </div>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Label / Title') }}</label>
                          <input name="process_stat2_label" class="form-control form-control-sm" value="{{ @$data->process_stat2_label ?? 'Happy Merchants' }}" placeholder="Happy Merchants">
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                          <input name="process_stat2_icon" class="form-control form-control-sm" value="{{ @$data->process_stat2_icon ?? 'fas fa-users' }}" placeholder="fas fa-users">
                        </div>
                      </div>
                    </div>

                    {{-- Process Stat 3 --}}
                    <div class="col-lg-3 col-md-6 mb-3">
                      <div class="p-3 bg-light rounded h-100 border">
                        <span class="badge badge-info mb-2">{{ __('Counter 3 (Premium Themes)') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Value / Number') }}</label>
                          <input name="process_stat3_value" class="form-control form-control-sm" value="{{ @$data->process_stat3_value ?? '15+' }}" placeholder="15+">
                        </div>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Label / Title') }}</label>
                          <input name="process_stat3_label" class="form-control form-control-sm" value="{{ @$data->process_stat3_label ?? 'Premium Themes' }}" placeholder="Premium Themes">
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                          <input name="process_stat3_icon" class="form-control form-control-sm" value="{{ @$data->process_stat3_icon ?? 'fas fa-gem' }}" placeholder="fas fa-gem">
                        </div>
                      </div>
                    </div>

                    {{-- Process Stat 4 --}}
                    <div class="col-lg-3 col-md-6 mb-3">
                      <div class="p-3 bg-light rounded h-100 border">
                        <span class="badge badge-success mb-2">{{ __('Counter 4 (Customer Satisfaction)') }}</span>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Value / Number') }}</label>
                          <input name="process_stat4_value" class="form-control form-control-sm" value="{{ @$data->process_stat4_value ?? '99.99%' }}" placeholder="99.99%">
                        </div>
                        <div class="form-group p-0 mb-2">
                          <label class="small text-muted font-weight-bold">{{ __('Label / Title') }}</label>
                          <input name="process_stat4_label" class="form-control form-control-sm" value="{{ @$data->process_stat4_label ?? 'Customer Satisfaction' }}" placeholder="Customer Satisfaction">
                        </div>
                        <div class="form-group p-0 mb-0">
                          <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                          <input name="process_stat4_icon" class="form-control form-control-sm" value="{{ @$data->process_stat4_icon ?? 'fas fa-check-circle' }}" placeholder="fas fa-check-circle">
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
      {{-- TAB 3: EXPLORE DASHBOARD (SCREENSHOT 4)                  --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-dashboard" role="tabpanel" aria-labelledby="tab-dashboard-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card editor-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="editor-section-title">
                  <i class="fas fa-tachometer-alt text-info"></i>
                  {{ __('Explore Dashboard Section (Screenshot 4 Layout)') }}
                </h5>
                <div>
                  <a href="{{ route('admin.feature.index') . '?language=' . request()->input('language', 'en') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-cubes mr-1"></i> {{ __('Manage in Full Cards List') }}
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
                      <small class="text-muted d-block mt-2">{{ __('Recommended: 1200x700 PNG / JPG (Replaces the center dashboard in Screenshot 4)') }}</small>
                    </div>
                  </div>

                  {{-- Dashboard Texts & Bullets --}}
                  <div class="col-lg-8">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Badge / Subtitle Pill') }}</label>
                          <input name="features_section_subtitle" class="form-control"
                            value="{{ @$data->features_section_subtitle ?? 'EVERYTHING UNDER ONE ROOF' }}" placeholder="EVERYTHING UNDER ONE ROOF">
                          <p id="errfeatures_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Dashboard Section Title') }}</label>
                          <input name="features_section_title" class="form-control"
                            value="{{ @$data->features_section_title ?? 'Powerful Dashboard Built for Growth' }}" placeholder="Powerful Dashboard Built for Growth">
                          <p id="errfeatures_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('Dashboard Description') }}</label>
                          <textarea name="features_section_text" rows="3" class="form-control" placeholder="From analytics to orders, products to customers, manage every part of your business from one smart dashboard.">{{ @$data->features_section_text ?? 'From analytics to orders, products to customers, manage every part of your business from one smart dashboard.' }}</textarea>
                          <p id="errfeatures_section_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('CTA Button Text') }}</label>
                          <input type="text" class="form-control" name="features_section_btn_text"
                            value="{{ @$data->features_section_btn_text ?? 'Explore Dashboard' }}" placeholder="Explore Dashboard">
                          <p id="errfeatures_section_btn_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">{{ __('CTA Button URL') }}</label>
                          <input type="text" class="form-control ltr" name="features_section_btn_url"
                            value="{{ @$data->features_section_btn_url ?? '/pricing' }}" placeholder="/pricing">
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
                    <i class="fas fa-check-double text-success mr-2"></i>{{ __('4 Checklist Bullets (Left side below description)') }}
                  </h6>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="font-weight-bold"><span class="badge badge-success mr-1">✓</span> {{ __('Bullet 1') }}</label>
                        <input name="features_bullet1" class="form-control" value="{{ @$data->features_bullet1 ?? 'Real-time sales & analytics' }}" placeholder="Real-time sales & analytics">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="font-weight-bold"><span class="badge badge-success mr-1">✓</span> {{ __('Bullet 2') }}</label>
                        <input name="features_bullet2" class="form-control" value="{{ @$data->features_bullet2 ?? 'Manage products & inventory' }}" placeholder="Manage products & inventory">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="font-weight-bold"><span class="badge badge-success mr-1">✓</span> {{ __('Bullet 3') }}</label>
                        <input name="features_bullet3" class="form-control" value="{{ @$data->features_bullet3 ?? 'Track orders, payments & shipping' }}" placeholder="Track orders, payments & shipping">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="font-weight-bold"><span class="badge badge-success mr-1">✓</span> {{ __('Bullet 4') }}</label>
                        <input name="features_bullet4" class="form-control" value="{{ @$data->features_bullet4 ?? 'Customers, coupons & reports' }}" placeholder="Customers, coupons & reports">
                      </div>
                    </div>
                  </div>
                </div>

                {{-- Editable 4 Feature Cards (Screenshot 4 Content) --}}
                <div class="border-top pt-4 mt-3">
                  <h6 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-cubes text-info mr-2"></i>{{ __('4 Right-Side Feature Cards (Screenshot 4 - Editable)') }}
                  </h6>
                  <div class="row">
                    @if(isset($features) && count($features) > 0)
                      @foreach($features->take(4) as $cIdx => $ft)
                        <div class="col-md-6 mb-3">
                          <div class="p-3 bg-light rounded border">
                            <span class="badge badge-info font-weight-bold mb-2">{{ __('Card') }} {{ $cIdx + 1 }}</span>
                            <div class="form-group p-0 mb-2">
                              <label class="small text-muted font-weight-bold">{{ __('Title') }}</label>
                              <input type="text" class="form-control form-control-sm" name="feature_cards[{{ $ft->id }}][title]" value="{{ $ft->title }}">
                            </div>
                            <div class="form-group p-0 mb-0">
                              <label class="small text-muted font-weight-bold">{{ __('Text') }}</label>
                              <input type="text" class="form-control form-control-sm" name="feature_cards[{{ $ft->id }}][text]" value="{{ $ft->text }}">
                            </div>
                          </div>
                        </div>
                      @endforeach
                    @else
                      <div class="col-12">
                        <p class="text-muted small">{{ __('Default 4 cards will be auto-seeded on save.') }}</p>
                      </div>
                    @endif
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 4: STORE THEMES & TEMPLATES                          --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-templates" role="tabpanel" aria-labelledby="tab-templates-btn">
        <div class="row">
          <div class="col-lg-12">
            <div class="card editor-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="editor-section-title">
                  <i class="fas fa-th-large text-warning"></i>
                  {{ __('Store Themes & Preview Templates Section') }}
                </h5>
                <div>
                  <a href="{{ \Illuminate\Support\Facades\Route::has('front.templates.view') ? route('front.templates.view') : url('/templates') }}" target="_blank" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-eye mr-1"></i> {{ __('View Themes Page') }}
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

          {{-- Trust Badges Banner (Screenshot 4: 5 Trust Items) --}}
          <div class="col-lg-12">
            <div class="card editor-section-card">
              <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h5 class="editor-section-title">
                  <i class="fas fa-award text-success"></i>
                  {{ __('Trust Badges Banner (5 Trust Points below Bottom CTA / Pricing)') }}
                </h5>
                <span class="field-help-badge">{{ __('Featured on Homepage & Pricing Page') }}</span>
              </div>
              <div class="card-body">
                <div class="row">
                  {{-- Point 1 --}}
                  <div class="col-md-4 mb-3">
                    <div class="p-3 bg-light rounded h-100 border">
                      <span class="badge badge-warning text-white mb-2">{{ __('Point 1 (Premium Stores)') }}</span>
                      <div class="form-group p-0 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Text / Label') }}</label>
                        <input name="bottom_feat1_text" class="form-control form-control-sm" value="{{ @$data->bottom_feat1_text ?? 'Premium Stores' }}" placeholder="Premium Stores">
                      </div>
                      <div class="form-group p-0 mb-0">
                        <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                        <input name="bottom_feat1_icon" class="form-control form-control-sm" value="{{ @$data->bottom_feat1_icon ?? 'fas fa-crown' }}" placeholder="fas fa-crown">
                      </div>
                    </div>
                  </div>

                  {{-- Point 2 --}}
                  <div class="col-md-4 mb-3">
                    <div class="p-3 bg-light rounded h-100 border">
                      <span class="badge badge-primary mb-2">{{ __('Point 2 (Launch Instantly)') }}</span>
                      <div class="form-group p-0 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Text / Label') }}</label>
                        <input name="bottom_feat2_text" class="form-control form-control-sm" value="{{ @$data->bottom_feat2_text ?? 'Launch Instantly' }}" placeholder="Launch Instantly">
                      </div>
                      <div class="form-group p-0 mb-0">
                        <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                        <input name="bottom_feat2_icon" class="form-control form-control-sm" value="{{ @$data->bottom_feat2_icon ?? 'fas fa-rocket' }}" placeholder="fas fa-rocket">
                      </div>
                    </div>
                  </div>

                  {{-- Point 3 --}}
                  <div class="col-md-4 mb-3">
                    <div class="p-3 bg-light rounded h-100 border">
                      <span class="badge badge-success mb-2">{{ __('Point 3 (Money Back Guarantee)') }}</span>
                      <div class="form-group p-0 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Text / Label') }}</label>
                        <input name="bottom_feat3_text" class="form-control form-control-sm" value="{{ @$data->bottom_feat3_text ?? '10-Days Money Back Guarantee' }}" placeholder="10-Days Money Back Guarantee">
                      </div>
                      <div class="form-group p-0 mb-0">
                        <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                        <input name="bottom_feat3_icon" class="form-control form-control-sm" value="{{ @$data->bottom_feat3_icon ?? 'fas fa-shield-alt' }}" placeholder="fas fa-shield-alt">
                      </div>
                    </div>
                  </div>

                  {{-- Point 4 --}}
                  <div class="col-md-6 mb-3">
                    <div class="p-3 bg-light rounded h-100 border">
                      <span class="badge badge-info mb-2">{{ __('Point 4 (High Conversions)') }}</span>
                      <div class="form-group p-0 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Text / Label') }}</label>
                        <input name="bottom_feat4_text" class="form-control form-control-sm" value="{{ @$data->bottom_feat4_text ?? 'Get High Conversions' }}" placeholder="Get High Conversions">
                      </div>
                      <div class="form-group p-0 mb-0">
                        <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                        <input name="bottom_feat4_icon" class="form-control form-control-sm" value="{{ @$data->bottom_feat4_icon ?? 'fas fa-chart-line' }}" placeholder="fas fa-chart-line">
                      </div>
                    </div>
                  </div>

                  {{-- Point 5 --}}
                  <div class="col-md-6 mb-3">
                    <div class="p-3 bg-light rounded h-100 border">
                      <span class="badge badge-danger text-white mb-2">{{ __('Point 5 (24/7 Support)') }}</span>
                      <div class="form-group p-0 mb-2">
                        <label class="small text-muted font-weight-bold">{{ __('Text / Label') }}</label>
                        <input name="bottom_feat5_text" class="form-control form-control-sm" value="{{ @$data->bottom_feat5_text ?? '24/7 Expert Support' }}" placeholder="24/7 Expert Support">
                      </div>
                      <div class="form-group p-0 mb-0">
                        <label class="small text-muted font-weight-bold">{{ __('Icon Class') }}</label>
                        <input name="bottom_feat5_icon" class="form-control form-control-sm" value="{{ @$data->bottom_feat5_icon ?? 'fas fa-headset' }}" placeholder="fas fa-headset">
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
    $(document).on('change', '.img-input', function(e) {
      var file = this.files && this.files[0];
      if (file) {
        var $box = $(this).closest('.preview-box');
        var $img = $box.find('.showImage img');
        var reader = new FileReader();
        reader.onload = function(evt) {
          if ($img.length) {
            $img.attr('src', evt.target.result);
          }
        };
        reader.readAsDataURL(file);
        var $btn = $(this).closest('.upload-btn');
        var fileName = file.name;
        if (fileName.length > 20) {
          fileName = fileName.substring(0, 18) + '...';
        }
        var inputHtml = '<input type="file" class="img-input" name="' + $(this).attr('name') + '">';
        $btn.html('<i class="fas fa-check-circle text-success mr-1"></i> ' + fileName + inputHtml);
      }
    });

    function activateTabFromHash() {
      var hash = window.location.hash;
      if (hash && $('.home-editor-tabs a[href="' + hash + '"]').length) {
        $('.home-editor-tabs a[href="' + hash + '"]').tab('show');
        $('html, body').animate({ scrollTop: 0 }, 150);
      }
    }
    $(document).ready(function() {
      activateTabFromHash();
      $(window).on('hashchange', activateTabFromHash);
      $('.home-editor-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if (history.pushState) {
          history.pushState(null, null, e.target.hash);
        }
      });
    });
  </script>
@endsection