@extends('admin.layout')

@section('styles')
<style>
  .popup-editor-tabs .nav-link {
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
  .popup-editor-tabs .nav-link:hover {
    background: #eef2f7;
    color: #0b5ed7;
  }
  .popup-editor-tabs .nav-link.active {
    background: linear-gradient(135deg, #ff5a2c 0%, #d4380d 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(255, 90, 44, 0.3);
    border-color: #d4380d;
  }
  .popup-card {
    border-radius: 14px;
    border: 1px solid #eef0f3;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    margin-bottom: 24px;
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
  }
  .upload-btn input[type="file"],
  .upload-btn .img-input {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    opacity: 0 !important;
    cursor: pointer !important;
    z-index: 10 !important;
  }
</style>
@endsection

@section('content')
  <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
      <h4 class="page-title font-weight-bold mb-1">{{ __('Custom VIP Deal Popup') }}</h4>
      <ul class="breadcrumbs p-0 m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
        </li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="{{ route('admin.popup.index') }}">{{ __('Announcement Popup') }}</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="#">{{ __('Custom Popup') }}</a></li>
      </ul>
    </div>
    <div style="min-width: 170px;">
      @include('admin.partials.languages')
    </div>
  </div>

  {{-- Top Navigation Tabs between Standard Popups and Custom VIP Popup --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
      <ul class="nav nav-pills hero-editor-tabs m-0">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.popup.index') . '?language=' . request()->input('language', 'en') }}">
            <i class="fas fa-list text-muted"></i>
            <span>{{ __('All Popups List') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{{ route('admin.custom_popup') . '?language=' . request()->input('language', 'en') }}">
            <i class="fas fa-crown text-warning"></i>
            <span>{{ __('Custom VIP Deal Popup (Live Model)') }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <form action="{{ route('admin.custom_popup.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="language_id" value="{{ $lang_id ?? 1 }}">

    {{-- Editor Section Tabs --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
      <div class="card-body p-3">
        <ul class="nav nav-pills popup-editor-tabs m-0" id="popupEditorTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="tab-header-btn" data-toggle="pill" href="#tab-header" role="tab" aria-controls="tab-header" aria-selected="true">
              <i class="fas fa-heading text-warning"></i>
              <span>{{ __('1. Header & Timer') }}</span>
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab-standard-btn" data-toggle="pill" href="#tab-standard" role="tab" aria-controls="tab-standard" aria-selected="false">
              <i class="fas fa-star text-primary"></i>
              <span>{{ __('2. Ecom Standard Card') }}</span>
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab-premium-btn" data-toggle="pill" href="#tab-premium" role="tab" aria-controls="tab-premium" aria-selected="false">
              <i class="fas fa-crown text-danger"></i>
              <span>{{ __('3. Ecom Premium Card') }}</span>
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab-actions-btn" data-toggle="pill" href="#tab-actions" role="tab" aria-controls="tab-actions" aria-selected="false">
              <i class="fas fa-cog text-secondary"></i>
              <span>{{ __('4. Popup Controls & Dismiss') }}</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="tab-content" id="popupEditorTabsContent">

      {{-- ======================================================== --}}
      {{-- TAB 1: POPUP HEADER & TIMER                              --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade show active" id="tab-header" role="tabpanel" aria-labelledby="tab-header-btn">
        <div class="card popup-card">
          <div class="card-header bg-white py-3">
            <h5 class="font-weight-bold text-dark mb-0">
              <i class="fas fa-heading text-warning mr-1"></i> {{ __('Modal Header, Ribbon & Timer Settings') }}
            </h5>
          </div>
          <div class="card-body p-4">
            <div class="row">
              <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="preview-box">
                  <label class="font-weight-bold text-dark d-block mb-2">{{ __('3D Gift Box Graphic') }}</label>
                  <div class="showImage mb-3">
                    <img src="{{ !empty(@$customPopup->popup_giftbox_img) ? asset('assets/front/img/' . @$customPopup->popup_giftbox_img) : asset('images/orange_percent_giftbox.png') }}" alt="Gift Box" class="img-thumbnail" style="max-height: 160px; object-fit: contain;">
                  </div>
                  <div role="button" class="btn btn-outline-primary btn-sm upload-btn">
                    <i class="fas fa-cloud-upload-alt mr-1"></i> {{ __('Change Gift Box Image') }}
                    <input type="file" class="img-input" name="popup_giftbox_img">
                  </div>
                  <small class="text-muted d-block mt-1">{{ __('Appears overlapping the top-left of the modal.') }}</small>
                </div>
              </div>

              <div class="col-lg-8">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Top Ribbon Badge Text') }}</label>
                  <input type="text" name="popup_ribbon_text" class="form-control" value="{{ @$customPopup->popup_ribbon_text ?? 'VIP ACCESS' }}" placeholder="VIP ACCESS">
                  <small class="text-muted">{{ __('Orange pill badge at the very top of the popup.') }}</small>
                </div>
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Main Headline / Title') }}</label>
                  <input type="text" name="popup_title" class="form-control" value="{{ @$customPopup->popup_title ?? 'Exclusive VIP Access' }}" placeholder="Exclusive VIP Access">
                </div>
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Subtitle / Description') }}</label>
                  <textarea name="popup_text" rows="2" class="form-control" placeholder="Unlock early access to our latest products and exclusive discounts. Become a VIP today!">{{ @$customPopup->popup_text ?? 'Unlock early access to our latest products and exclusive discounts. Become a VIP today!' }}</textarea>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label class="font-weight-bold">{{ __('Timer Hours') }}</label>
                      <input type="text" name="popup_timer_hours" class="form-control" value="{{ @$customPopup->popup_timer_hours ?? '00' }}" placeholder="00">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label class="font-weight-bold">{{ __('Timer Minutes') }}</label>
                      <input type="text" name="popup_timer_mins" class="form-control" value="{{ @$customPopup->popup_timer_mins ?? '27' }}" placeholder="27">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label class="font-weight-bold">{{ __('Timer Seconds') }}</label>
                      <input type="text" name="popup_timer_secs" class="form-control" value="{{ @$customPopup->popup_timer_secs ?? '33' }}" placeholder="33">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 2: ECOM STANDARD CARD                                --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-standard" role="tabpanel" aria-labelledby="tab-standard-btn">
        <div class="card popup-card">
          <div class="card-header bg-white py-3">
            <h5 class="font-weight-bold text-dark mb-0">
              <i class="fas fa-star text-primary mr-1"></i> {{ __('Left Card (Ecom Standard Plan)') }}
            </h5>
          </div>
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Plan Name') }}</label>
                  <input type="text" name="popup_std_name" class="form-control" value="{{ @$customPopup->popup_std_name ?? 'Ecom Standard' }}" placeholder="Ecom Standard">
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Tagline / Subtitle') }}</label>
                  <input type="text" name="popup_std_tagline" class="form-control" value="{{ @$customPopup->popup_std_tagline ?? 'Smart choice for growing brands' }}" placeholder="Smart choice for growing brands">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Highlight Badge') }}</label>
                  <input type="text" name="popup_std_pill" class="form-control" value="{{ @$customPopup->popup_std_pill ?? '🎉 Great Value' }}" placeholder="🎉 Great Value">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Original Price (Strikethrough)') }}</label>
                  <input type="text" name="popup_std_orig_price" class="form-control" value="{{ @$customPopup->popup_std_orig_price ?? '₹14,283/year' }}" placeholder="₹14,283/year">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Savings Badge') }}</label>
                  <input type="text" name="popup_std_save_tag" class="form-control" value="{{ @$customPopup->popup_std_save_tag ?? 'Save ₹9,284' }}" placeholder="Save ₹9,284">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Offer Price') }}</label>
                  <input type="text" name="popup_std_price" class="form-control" value="{{ @$customPopup->popup_std_price ?? '₹3,999' }}" placeholder="₹3,999">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Billing Interval') }}</label>
                  <input type="text" name="popup_std_billed" class="form-control" value="{{ @$customPopup->popup_std_billed ?? 'Billed annually' }}" placeholder="Billed annually">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Feature Items List (One item per line)') }}</label>
                  <textarea name="popup_std_features" rows="6" class="form-control" placeholder="Custom Domain&#10;Unlimited Orders&#10;Custom Pages&#10;Inventory Management&#10;Advanced Report&#10;Google Login&#10;Gst Billing.">{{ @$customPopup->popup_std_features ?? "Custom Domain\nUnlimited Orders\nCustom Pages\nInventory Management\nAdvanced Report\nGoogle Login\nGst Billing." }}</textarea>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Action Button Text') }}</label>
                  <input type="text" name="popup_std_btn_text" class="form-control" value="{{ @$customPopup->popup_std_btn_text ?? 'Choose Standard →' }}" placeholder="Choose Standard →">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Action Button URL') }}</label>
                  <input type="text" name="popup_std_btn_url" class="form-control" value="{{ @$customPopup->popup_std_btn_url ?? '/pricing?term=yearly' }}" placeholder="/pricing?term=yearly">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 3: ECOM PREMIUM CARD                                 --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-premium" role="tabpanel" aria-labelledby="tab-premium-btn">
        <div class="card popup-card">
          <div class="card-header bg-white py-3">
            <h5 class="font-weight-bold text-dark mb-0">
              <i class="fas fa-crown text-danger mr-1"></i> {{ __('Right Card (Ecom Premium Plan)') }}
            </h5>
          </div>
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Floating Circle Badge') }}</label>
                  <input type="text" name="popup_pre_badge" class="form-control" value="{{ @$customPopup->popup_pre_badge ?? 'UPTO 65% OFF' }}" placeholder="UPTO 65% OFF">
                  <small class="text-muted">{{ __('Orange round badge overlapping top-right.') }}</small>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Plan Name') }}</label>
                  <input type="text" name="popup_pre_name" class="form-control" value="{{ @$customPopup->popup_pre_name ?? 'Ecom Premium' }}" placeholder="Ecom Premium">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Tagline / Subtitle') }}</label>
                  <input type="text" name="popup_pre_tagline" class="form-control" value="{{ @$customPopup->popup_pre_tagline ?? 'Best choice for advanced features' }}" placeholder="Best choice for advanced features">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Highlight Badge') }}</label>
                  <input type="text" name="popup_pre_pill" class="form-control" value="{{ @$customPopup->popup_pre_pill ?? '😍 Most Popular' }}" placeholder="😍 Most Popular">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Original Price (Strikethrough)') }}</label>
                  <input type="text" name="popup_pre_orig_price" class="form-control" value="{{ @$customPopup->popup_pre_orig_price ?? '₹28,569/year' }}" placeholder="₹28,569/year">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Savings Badge') }}</label>
                  <input type="text" name="popup_pre_save_tag" class="form-control" value="{{ @$customPopup->popup_pre_save_tag ?? 'Save ₹18,570' }}" placeholder="Save ₹18,570">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Offer Price') }}</label>
                  <input type="text" name="popup_pre_price" class="form-control" value="{{ @$customPopup->popup_pre_price ?? '₹8,999' }}" placeholder="₹8,999">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Free Domain Highlight Text') }}</label>
                  <input type="text" name="popup_pre_free_domain" class="form-control" value="{{ @$customPopup->popup_pre_free_domain ?? 'Free .in Domain' }}" placeholder="Free .in Domain">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Feature Items List (One item per line)') }}</label>
                  <textarea name="popup_pre_features" rows="6" class="form-control" placeholder="All Standard Features&#10;Premium Themes&#10;Staff Management&#10;Push Notification&#10;Payment Option">{{ @$customPopup->popup_pre_features ?? "All Standard Features\nPremium Themes\nStaff Management\nPush Notification\nPayment Option" }}</textarea>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Action Button Text') }}</label>
                  <input type="text" name="popup_pre_btn_text" class="form-control" value="{{ @$customPopup->popup_pre_btn_text ?? 'Select Premium →' }}" placeholder="Select Premium →">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Action Button URL') }}</label>
                  <input type="text" name="popup_pre_btn_url" class="form-control" value="{{ @$customPopup->popup_pre_btn_url ?? '/pricing?term=yearly' }}" placeholder="/pricing?term=yearly">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ======================================================== --}}
      {{-- TAB 4: CONTROLS & DISMISS                                --}}
      {{-- ======================================================== --}}
      <div class="tab-pane fade" id="tab-actions" role="tabpanel" aria-labelledby="tab-actions-btn">
        <div class="card popup-card">
          <div class="card-header bg-white py-3">
            <h5 class="font-weight-bold text-dark mb-0">
              <i class="fas fa-cog text-secondary mr-1"></i> {{ __('Popup Visibility & Dismiss Settings') }}
            </h5>
          </div>
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Popup Status') }}</label>
                  <select name="popup_status" class="form-control">
                    <option value="1" {{ (!isset($customPopup->popup_status) || $customPopup->popup_status == 1) ? 'selected' : '' }}>{{ __('Active (Display on Frontend)') }}</option>
                    <option value="0" {{ (isset($customPopup->popup_status) && $customPopup->popup_status == 0) ? 'selected' : '' }}>{{ __('Deactive (Hide on Frontend)') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="font-weight-bold">{{ __('Footer Dismiss Link Text') }}</label>
                  <input type="text" name="popup_dismiss_text" class="form-control" value="{{ @$customPopup->popup_dismiss_text ?? 'No Thanks, Maybe Later' }}" placeholder="No Thanks, Maybe Later">
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
        {{ __('All fields are pre-filled with the live VIP popup content and will save directly to the database.') }}
      </span>
      <button type="submit" class="btn btn-success font-weight-bold px-4" id="submitBtn">
        <i class="fas fa-save mr-1"></i> {{ __('Save & Update Custom Popup') }}
      </button>
    </div>
  </form>
@endsection