@extends('admin.layout')
@php
  $type = $popup->type;
@endphp
@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Announcement Popup') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
      </h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-home"></i>
          </a>
        </li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Basic Settings') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Announcement Popup') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Edit Popup') }} ({{ __('Type') }} - {{ $type }})</a></li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">
              {{ __('Edit Popup') }} ({{ __('Type') }} - {{ $type }})
            </h3>
            <a href="{{ route('admin.popup.index') . '?language=' . request()->input('language') }}"
              class="btn-action-square b-edit" style="width: auto; padding: 6px 18px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; gap: 5px; display: inline-flex; align-items: center;">
              <i class="fas fa-arrow-left" style="font-size: 0.75rem;"></i> {{ __('Back') }}
            </a>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="row">
            <div class="col-lg-6 m-auto">

              <form id="ajaxForm" class="modal-form" action="{{ route('admin.popup.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="popup_id" value="{{ $popup->id }}">
                <input type="hidden" name="type" value="{{ $type }}">

                @if ($type == 1 || $type == 4 || $type == 5 || $type == 7)
                  {{-- Image Part --}}
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Image') }}</label>
                    <div class="popup-image-preview-wrap">
                      <div class="popup-img-left">
                        <img class="showImage-img img-fluid"
                          src="{{ $popup->image ? asset('assets/front/img/popups/' . $popup->image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="Popup Image" style="border-radius: 12px; max-width: 100%; max-height: 220px; object-fit: cover;">
                      </div>
                      <div class="popup-img-right">
                        <div class="popup-upload-box">
                          <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #6366F1; margin-bottom: 10px;"></i>
                          <div role="button" class="btn-primary-purple py-2 px-4 upload-btn" id="image"
                            style="border-radius: 10px; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            {{ __('Choose Image') }}
                            <input type="file" class="img-input" name="image">
                          </div>
                          <p class="mt-2 mb-0 text-muted" style="font-size: 0.78rem;">{{ __('Only png, jpg, jpeg image is allowed') }}</p>
                        </div>
                      </div>
                    </div>
                    <p id="errimage" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                @endif

                @if ($type == 2 || $type == 3 || $type == 6)
                  {{-- Background Image Part --}}
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Background Image') }}</label>
                    <div class="popup-image-preview-wrap">
                      <div class="popup-img-left">
                        <img class="showImage-img img-fluid"
                          src="{{ $popup->background_image ? asset('assets/front/img/popups/' . $popup->background_image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="Background Image" style="border-radius: 12px; max-width: 100%; max-height: 220px; object-fit: cover;">
                      </div>
                      <div class="popup-img-right">
                        <div class="popup-upload-box">
                          <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #6366F1; margin-bottom: 10px;"></i>
                          <div role="button" class="btn-primary-purple py-2 px-4 upload-btn" id="image"
                            style="border-radius: 10px; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            {{ __('Choose Image') }}
                            <input type="file" class="img-input" name="background_image">
                          </div>
                          <p class="mt-2 mb-0 text-muted" style="font-size: 0.78rem;">{{ __('Only png, jpg, jpeg image is allowed') }}</p>
                        </div>
                      </div>
                    </div>
                    <p id="errimage" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                @endif

                {{-- Popup Name --}}
                <div class="form-group mb-4">
                  <label class="popup-field-label">{{ __('Popup Name') }} <span class="text-danger">*</span></label>
                  <input type="text" class="form-control popup-field-input" name="name" value="{{ $popup->name }}"
                    placeholder="{{ __('Enter name') }}">
                  <p class="popup-field-hint mt-1">
                    {{ __('This will not be shown in the popup in Website, it will help you to indentify the popup in Admin Panel.') }}
                  </p>
                  <p id="errname" class="mb-0 text-danger em small"></p>
                </div>

                @if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Title') }}</label>
                    <input type="text" class="form-control popup-field-input" name="title" value="{{ $popup->title }}"
                      placeholder="{{ __('Enter Title') }}">
                    <p id="errtitle" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Text') }}</label>
                    <textarea class="form-control popup-field-input" name="text" cols="30" rows="3"
                      placeholder="{{ __('Enter Text') }}">{{ $popup->text }}</textarea>
                    <p id="errtext" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                @endif

                @if ($type == 6 || $type == 7)
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                        <div class="position-relative">
                          <input type="text" class="form-control popup-field-input ltr datepicker" name="end_date"
                            value="{{ $popup->end_date }}" placeholder="{{ __('Enter End Date') }}" autocomplete="off">
                          <i class="fas fa-calendar-alt position-absolute text-muted" style="right: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem; pointer-events: none;"></i>
                        </div>
                        <p id="errend_date" class="mb-0 text-danger em small mt-1"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('End Time') }} <span class="text-danger">*</span></label>
                        <div class="position-relative">
                          <input type="text" class="form-control popup-field-input ltr flatpickr" name="end_time"
                            value="{{ $popup->end_time }}" placeholder="{{ __('Enter End Time') }}" autocomplete="off">
                          <i class="fas fa-clock position-absolute text-muted" style="right: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem; pointer-events: none;"></i>
                        </div>
                        <p id="errend_time" class="mb-0 text-danger em small mt-1"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($type == 2 || $type == 3)
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('Background Color Code') }} <span class="text-danger">*</span></label>
                        <div class="popup-color-input-wrap">
                          <input class="jscolor form-control popup-field-input ltr" name="background_color"
                            value="{{ $popup->background_color }}">
                        </div>
                        <p class="em text-danger mb-0 small" id="errbackground_color"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('Background Color Opacity') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control popup-field-input ltr" name="background_opacity"
                          value="{{ $popup->background_opacity }}" placeholder="{{ __('Enter Opacity Value') }}">
                        <p id="errbackground_opacity" class="mb-0 text-danger em small"></p>
                        <p class="popup-field-hint mt-1">{{ __('Value must be between 0 to 1') }}</p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($type == 7)
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Background Color Code') }} <span class="text-danger">*</span></label>
                    <div class="popup-color-input-wrap">
                      <input class="jscolor form-control popup-field-input ltr" name="background_color"
                        value="{{ $popup->background_color }}">
                    </div>
                    <p class="em text-danger mb-0 small" id="errbackground_color"></p>
                  </div>
                @endif

                @if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('Button Text') }}</label>
                        <input type="text" class="form-control popup-field-input" name="button_text"
                          value="{{ $popup->button_text }}" placeholder="{{ __('Enter Button Text') }}">
                        <p id="errbutton_text" class="mb-0 text-danger em small mt-1"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('Button Color') }}</label>
                        <div class="popup-color-input-wrap">
                          <input type="text" class="form-control popup-field-input jscolor ltr" name="button_color"
                            value="{{ $popup->button_color }}" placeholder="{{ __('Enter Button Color') }}">
                        </div>
                        <p id="errbutton_color" class="mb-0 text-danger em small mt-1"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($type == 2 || $type == 4 || $type == 6 || $type == 7)
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Button URL') }}</label>
                    <input type="text" class="form-control popup-field-input ltr" name="button_url"
                      value="{{ $popup->button_url }}" placeholder="{{ __('Enter Button URL') }}">
                    <p id="errbutton_url" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                @endif

                <div class="form-group mb-4">
                  <label class="popup-field-label">{{ __('Delay (miliseconds)') }} <span class="text-danger">*</span></label>
                  <input type="number" class="form-control popup-field-input ltr" name="delay"
                    value="{{ $popup->delay }}" placeholder="{{ __('Enter Delay (miliseconds)') }}">
                  <p id="errdelay" class="mb-0 text-danger em small mt-1"></p>
                  <p class="popup-field-hint mt-1">{{ __('This will decide the delay time to show the popup') }}</p>
                </div>

                <div class="form-group mb-4">
                  <label class="popup-field-label">{{ __('Serial Number') }} <span class="text-danger">*</span></label>
                  <input type="number" class="form-control popup-field-input ltr" name="serial_number"
                    value="{{ $popup->serial_number }}" placeholder="{{ __('Enter Serial Number') }}">
                  <p id="errserial_number" class="mb-0 text-danger em small mt-1"></p>

                  <div class="popup-notice-small mt-3">
                    <i class="fas fa-info-circle" style="color: #6366F1; font-size: 1.1rem; flex-shrink: 0;"></i>
                    <ul class="m-0 pl-3" style="font-size: 0.82rem;">
                      <li class="mb-1">{{ __('If there are') }}
                        <strong style="color: #6366F1;">{{ __('Multiple Active Popups') }}</strong>
                        {{ __(', then the popups will be shown in the website according to') }}
                        <strong style="color: #6366F1;">{{ __('Serial Number') }}</strong>
                      </li>
                      <li>{{ __('The higher the serial number, the later the popups will be visible in Website') }}</li>
                    </ul>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>

        <div class="card-footer border-0 bg-transparent pb-4 pt-0 text-center">
          <button id="submitBtn" type="button" class="btn-primary-purple py-2 px-5"
            style="border-radius: 10px; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-paper-plane"></i> {{ __('Submit') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection
