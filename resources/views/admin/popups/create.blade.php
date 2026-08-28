@extends('admin.layout')

@section('content')
  @php $type = request()->input('type'); @endphp

  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Announcement Popup') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
      </h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i></a>
        </li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Basic Settings') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Announcement Popup') }}</a></li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">
              {{ __('Add Popup') }} ({{ __('Type') }} - {{ $type }})
            </h3>
            <a class="btn-action-square b-edit"
              href="{{ route('admin.popup.index', ['language' => @$default->code]) }}"
              style="width: auto; padding: 6px 18px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
              <i class="fas fa-list" style="font-size: 0.75rem;"></i> {{ __('All Popups') }}
            </a>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="row">
            <div class="col-lg-7 mx-auto">

              <form id="ajaxForm" class="modal-form" action="{{ route('admin.popup.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                {{-- Image Upload --}}
                @if ($type == 1 || $type == 4 || $type == 5 || $type == 7)
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Image') }} <span class="text-danger">**</span></label>
                    <div class="popup-upload-full showImage">
                      <div class="popup-upload-placeholder">
                        <i class="fas fa-image" style="font-size: 2.5rem; color: #6366F1; margin-bottom: 8px;"></i>
                        <p class="mb-1 font-weight-600" style="font-size: 0.9rem; color: var(--text-main);">{{ __('No image found') }}</p>
                        <p class="mb-3" style="font-size: 0.78rem; color: var(--text-muted);">png, jpg, jpeg only</p>
                        <div role="button" class="btn-primary-purple py-2 px-4 upload-btn" id="image"
                          style="border-radius: 10px; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; margin: 0 auto;">
                          <i class="fas fa-cloud-upload-alt"></i> {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="image">
                        </div>
                      </div>
                    </div>
                    <p class="popup-field-hint mt-2">{{ __('Only png, jpg, jpeg image is allowed') }}</p>
                    <p id="errimage" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                @endif

                @if ($type == 2 || $type == 3 || $type == 6)
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Background Image') }} <span class="text-danger">**</span></label>
                    <div class="popup-upload-full showImage2">
                      <div class="popup-upload-placeholder">
                        <i class="fas fa-image" style="font-size: 2.5rem; color: #6366F1; margin-bottom: 8px;"></i>
                        <p class="mb-1 font-weight-600" style="font-size: 0.9rem; color: var(--text-main);">{{ __('No image found') }}</p>
                        <p class="mb-3" style="font-size: 0.78rem; color: var(--text-muted);">png, jpg, jpeg only</p>
                        <div role="button" class="btn-primary-purple py-2 px-4 upload-btn" id="image2"
                          style="border-radius: 10px; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; margin: 0 auto;">
                          <i class="fas fa-cloud-upload-alt"></i> {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="background_image">
                        </div>
                      </div>
                    </div>
                    <p class="popup-field-hint mt-2">{{ __('Only png, jpg, jpeg image is allowed') }}</p>
                    <p id="errbackground_image" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                @endif

                {{-- Language + Popup Name --}}
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="popup-field-label">{{ __('Language') }} <span class="text-danger">**</span></label>
                      <select name="language_id" class="form-control popup-field-input" style="height: 44px !important;">
                        <option value="" selected disabled>{{ __('Select a Language') }}</option>
                        @foreach ($langs as $lang)
                          <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                        @endforeach
                      </select>
                      <p id="errlanguage_id" class="mb-0 text-danger em small mt-1"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="popup-field-label">{{ __('Popup Name') }} <span class="text-danger">**</span></label>
                      <input type="text" class="form-control popup-field-input" name="name" value=""
                        placeholder="{{ __('Enter name') }}">
                      <p class="popup-field-hint mt-1">
                        {{ __('This will not be shown in the popup in Website, it will help you to indentify the popup in Admin Panel.') }}
                      </p>
                      <p id="errname" class="mb-0 text-danger em small"></p>
                    </div>
                  </div>
                </div>

                @if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Title') }}</label>
                    <input type="text" class="form-control popup-field-input" name="title" value=""
                      placeholder="{{ __('Enter Title') }}">
                    <p id="errtitle" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Text') }}</label>
                    <textarea class="form-control popup-field-input" name="text" cols="30" rows="3"
                      placeholder="{{ __('Enter Text') }}"></textarea>
                    <p id="errtext" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                @endif

                @if ($type == 6 || $type == 7)
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('End Date') }} <span class="text-danger">**</span></label>
                        <div class="position-relative">
                          <input type="text" class="form-control popup-field-input ltr datepicker" name="end_date"
                            value="" placeholder="{{ __('Enter End Date') }}" readonly autocomplete="off">
                          <i class="fas fa-calendar-alt position-absolute text-muted" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                        </div>
                        <p id="errend_date" class="mb-0 text-danger em small mt-1"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('End Time') }} <span class="text-danger">**</span></label>
                        <div class="position-relative">
                          <input type="text" class="form-control popup-field-input ltr flatpickr" name="end_time"
                            value="" placeholder="{{ __('Enter End Time') }}" readonly autocomplete="off">
                          <i class="fas fa-clock position-absolute text-muted" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
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
                        <label class="popup-field-label">{{ __('Background Color Code') }} <span class="text-danger">**</span></label>
                        <div class="popup-color-input-wrap">
                          <input class="jscolor form-control popup-field-input ltr" name="background_color" value="451d53">
                        </div>
                        <p class="em text-danger mb-0 small" id="errbackground_color"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('Background Color Opacity') }} <span class="text-danger">**</span></label>
                        <input type="number" class="form-control popup-field-input ltr" name="background_opacity"
                          value="" placeholder="{{ __('Enter Opacity Value') }}">
                        <p id="errbackground_opacity" class="mb-0 text-danger em small"></p>
                        <ul class="mt-1 pl-3 mb-0">
                          <li class="popup-field-hint">{{ __('Value must be between 0 to 1') }}</li>
                          <li class="popup-field-hint">{{ __('The more the opacity value is, the less the trnsparency level will be.') }}</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($type == 7)
                  <div class="form-group mb-4">
                    <label class="popup-field-label">{{ __('Background Color Code') }} <span class="text-danger">**</span></label>
                    <div class="popup-color-input-wrap">
                      <input class="jscolor form-control popup-field-input ltr" name="background_color" value="451d53">
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
                          value="" placeholder="{{ __('Enter Button Text') }}">
                        <p id="errbutton_text" class="mb-0 text-danger em small mt-1"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mb-4">
                        <label class="popup-field-label">{{ __('Button Color') }}</label>
                        <div class="popup-color-input-wrap">
                          <input type="text" class="form-control popup-field-input jscolor ltr" name="button_color"
                            value="451d53" placeholder="{{ __('Enter Button Color') }}">
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
                      value="" placeholder="{{ __('Enter Button URL') }}">
                    <p id="errbutton_url" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                @endif

                <div class="form-group mb-4">
                  <label class="popup-field-label">{{ __('Delay (miliseconds)') }} <span class="text-danger">**</span></label>
                  <input type="number" class="form-control popup-field-input ltr" name="delay"
                    value="" placeholder="{{ __('Enter Delay (miliseconds)') }}">
                  <p id="errdelay" class="mb-0 text-danger em small mt-1"></p>
                  <p class="popup-field-hint mt-1">{{ __('This will decide the delay time to show the popup') }}</p>
                </div>

                <div class="form-group mb-4">
                  <label class="popup-field-label">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                  <input type="number" class="form-control popup-field-input ltr" name="serial_number"
                    value="" placeholder="{{ __('Enter Serial Number') }}">
                  <p id="errserial_number" class="mb-0 text-danger em small mt-1"></p>
                  <div class="popup-notice-small mt-3">
                    <i class="fas fa-info-circle" style="color: #6366F1; font-size: 1.1rem; flex-shrink: 0; margin-top: 2px;"></i>
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
