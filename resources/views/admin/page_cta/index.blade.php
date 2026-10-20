@extends('admin.layout')

@if (!empty(@$language) && @$language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('styles')
<style>
  .cta-editor-tabs .nav-link {
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
  .cta-editor-tabs .nav-link:hover {
    background: #eef2f7;
    color: #0b5ed7;
  }
  .cta-editor-tabs .nav-link.active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    border-color: #059669;
  }
  .cta-editor-tabs .nav-link i {
    font-size: 1.1rem;
  }
  .cta-section-card {
    border-radius: 14px;
    border: 1px solid #eef0f3;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    margin-bottom: 24px;
  }
  .cta-section-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #f0f2f5;
    padding: 18px 24px;
    border-radius: 14px 14px 0 0;
  }
  .cta-section-card .card-body {
    padding: 24px;
  }
  .cta-card-title {
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
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  }
  .preview-box {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
  }
  .preview-box img {
    max-height: 140px;
    width: auto;
    object-fit: contain;
    border-radius: 8px;
    background: #ffffff;
    padding: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }
  .upload-btn {
    position: relative;
    overflow: hidden;
    cursor: pointer;
  }
  .upload-btn input[type="file"] {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    opacity: 0;
    cursor: pointer;
  }
</style>
@endsection

@section('content')
  <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
      <h4 class="page-title font-weight-bold mb-1">{{ __('Call To Action (CTA) Banners') }}</h4>
      <ul class="breadcrumbs p-0 m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
        </li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Pages') }}</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Call To Action (CTA)') }}</a></li>
      </ul>
    </div>
    <div style="min-width: 170px;">
      @include('admin.partials.languages')
    </div>
  </div>

  @php
    $tabDefs = [
      'global'    => ['title' => __('Global / Default CTA'), 'icon' => 'fas fa-globe', 'desc' => __('Base default CTA used across all pages unless specifically overridden.')],
      'home'      => ['title' => __('Home Page CTA'), 'icon' => 'fas fa-home', 'desc' => __('Bottom CTA shown at the bottom of the public homepage.')],
      'pricing'   => ['title' => __('Pricing Page CTA'), 'icon' => 'fas fa-tags', 'desc' => __('Bottom CTA shown below the plan cards on the Pricing page.')],
      'templates' => ['title' => __('Store Themes CTA'), 'icon' => 'fas fa-th-large', 'desc' => __('Bottom CTA shown on the Store Themes / Templates page.')],
      'shops'     => ['title' => __('Stores Launched CTA'), 'icon' => 'fas fa-store', 'desc' => __('Bottom CTA shown on the Customer Stores launched page.')],
      'about'     => ['title' => __('About Us CTA'), 'icon' => 'fas fa-info-circle', 'desc' => __('Bottom CTA shown on the About Us page.')],
      'contact'   => ['title' => __('Contact Page CTA'), 'icon' => 'fas fa-headset', 'desc' => __('Optional bottom CTA banner on the Contact Us page.')],
    ];
  @endphp

  {{-- Page Tabs --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
      <ul class="nav nav-pills cta-editor-tabs m-0" id="ctaEditorTabs" role="tablist">
        @foreach ($tabDefs as $key => $tab)
          <li class="nav-item" role="presentation">
            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $key }}-btn" data-toggle="pill" href="#tab-{{ $key }}" role="tab" aria-controls="tab-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
              <i class="{{ $tab['icon'] }}"></i>
              <span>{{ $tab['title'] }}</span>
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  <form id="ajaxForm" action="{{ route('admin.page_cta.update', $lang_id) }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="tab-content" id="ctaEditorTabsContent">
      @foreach ($tabDefs as $key => $tab)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}-btn">
          <div class="card cta-section-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
              <h5 class="cta-card-title">
                <i class="{{ $tab['icon'] }} text-success"></i>
                {{ $tab['title'] }}
              </h5>
              <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal">{{ $tab['desc'] }}</span>
            </div>
            <div class="card-body">
              <div class="row">
                
                {{-- Status --}}
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('Banner Status') }}</label>
                  <select name="cta_{{ $key }}_status" class="form-control">
                    <option value="1" {{ (@$homeSec->{"cta_{$key}_status"} ?? '1') == '1' ? 'selected' : '' }}>{{ __('Active / Visible') }}</option>
                    <option value="0" {{ (@$homeSec->{"cta_{$key}_status"} ?? '1') == '0' ? 'selected' : '' }}>{{ __('Deactive / Hidden') }}</option>
                  </select>
                </div>

                {{-- Heading Title --}}
                <div class="col-md-8 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('CTA Heading / Title') }}</label>
                  <input type="text" name="cta_{{ $key }}_title" class="form-control" 
                    value="{{ @$homeSec->{"cta_{$key}_title"} ?? ($key == 'pricing' ? 'Ready to Launch Your Dream Store?' : 'Ready to Launch Your Own Store?') }}" 
                    placeholder="Ready to Launch Your Own Store?">
                </div>

                {{-- Subtitle / Description --}}
                <div class="col-12 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('CTA Subtitle / Description') }}</label>
                  <textarea name="cta_{{ $key }}_subtitle" rows="2" class="form-control" 
                    placeholder="Join thousands of successful merchants and start your online journey today.">{{ @$homeSec->{"cta_{$key}_subtitle"} ?? 'Join thousands of successful merchants and start your online journey today.' }}</textarea>
                </div>

                {{-- Button Text --}}
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('Button Text') }}</label>
                  <input type="text" name="cta_{{ $key }}_btn_text" class="form-control" 
                    value="{{ @$homeSec->{"cta_{$key}_btn_text"} ?? ($key == 'pricing' || $key == 'home' ? 'Launch Your Store' : 'Explore Themes') }}" 
                    placeholder="Explore Themes">
                </div>

                {{-- Button Target URL --}}
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('Button Target Link') }}</label>
                  <input type="text" name="cta_{{ $key }}_btn_url" class="form-control" 
                    value="{{ @$homeSec->{"cta_{$key}_btn_url"} ?? ($key == 'pricing' || $key == 'home' ? '/pricing' : '/templates') }}" 
                    placeholder="/templates">
                </div>

                {{-- Feature 1 --}}
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('Feature Checkmark 1') }}</label>
                  <input type="text" name="cta_{{ $key }}_feat1" class="form-control form-control-sm" 
                    value="{{ @$homeSec->{"cta_{$key}_feat1"} ?? '10-Days Money Back Guarantee' }}" 
                    placeholder="10-Days Money Back Guarantee">
                </div>

                {{-- Feature 2 --}}
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('Feature Checkmark 2') }}</label>
                  <input type="text" name="cta_{{ $key }}_feat2" class="form-control form-control-sm" 
                    value="{{ @$homeSec->{"cta_{$key}_feat2"} ?? 'No credit card required' }}" 
                    placeholder="No credit card required">
                </div>

                {{-- Feature 3 --}}
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark">{{ __('Feature Checkmark 3') }}</label>
                  <input type="text" name="cta_{{ $key }}_feat3" class="form-control form-control-sm" 
                    value="{{ @$homeSec->{"cta_{$key}_feat3"} ?? 'Cancel anytime' }}" 
                    placeholder="Cancel anytime">
                </div>

                {{-- Graphic Image Upload --}}
                <div class="col-12 mt-2">
                  <label class="font-weight-bold text-dark">{{ __('CTA Right Graphic Image') }}</label>
                  <div class="row align-items-center">
                    <div class="col-md-4 col-sm-6 mb-3">
                      <div class="preview-box">
                        @php
                          $imgCol = "cta_{$key}_image";
                          $hasImg = !empty(@$homeSec->$imgCol) && file_exists(public_path('assets/front/img/' . @$homeSec->$imgCol));
                        @endphp
                        <div class="showImage mb-2">
                          @if ($hasImg)
                            <img src="{{ asset('assets/front/img/' . @$homeSec->$imgCol) }}" alt="CTA Graphic">
                          @else
                            <img src="{{ asset('images/footer_right.png') }}" alt="Default Graphic">
                          @endif
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                          <label class="btn btn-primary btn-sm upload-btn mb-0 px-3">
                            <i class="fas fa-upload mr-1"></i> {{ __('Choose Image') }}
                            <input type="file" name="cta_{{ $key }}_image" class="img-input">
                          </label>
                          @if ($hasImg)
                            <a href="{{ route('admin.page_cta.removeImg', [$lang_id, "cta_{$key}_image"]) }}" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('Revert to default template graphic?') }}');">
                              <i class="fas fa-trash"></i>
                            </a>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8 col-sm-6 mb-3">
                      <div class="text-muted font-sm">
                        <p class="mb-1"><strong>{{ __('Image Specifications:') }}</strong></p>
                        <ul class="pl-3 mb-0">
                          <li>{{ __('Recommended formats: PNG with transparent background or high quality JPG/WEBP.') }}</li>
                          <li>{{ __('Recommended dimensions: 700px × 450px for optimum display sharpness.') }}</li>
                          <li>{{ __('If no image is uploaded, the default vector illustration is rendered automatically.') }}</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Sticky Bottom Save Bar --}}
    <div class="sticky-save-bar d-flex align-items-center justify-content-between">
      <span class="text-muted small">
        <i class="fas fa-info-circle text-primary mr-1"></i>
        {{ __('Changes made to any tab will be saved together.') }}
      </span>
      <button type="submit" class="btn btn-success font-weight-bold px-4" id="submitBtn">
        <i class="fas fa-save mr-1"></i> {{ __('Save All CTA Changes') }}
      </button>
    </div>

  </form>
@endsection

@section('scripts')
  <script>
    // Live image preview
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
      }
    });

    // Hash-based Tab Persistence
    function activateTabFromHash() {
      var hash = window.location.hash;
      if (hash && $('.cta-editor-tabs a[href="' + hash + '"]').length) {
        $('.cta-editor-tabs a[href="' + hash + '"]').tab('show');
      }
    }
    $(document).ready(function() {
      activateTabFromHash();
      $(window).on('hashchange', activateTabFromHash);
      $('.cta-editor-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if (history.pushState) {
          history.pushState(null, null, e.target.hash);
        }
      });
    });
  </script>
@endsection