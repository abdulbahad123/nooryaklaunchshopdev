@extends('admin.layout')

@if (!empty($abe->language) && $abe->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('General Settings') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
      </h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i></a>
        </li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Settings') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('General Settings') }}</a></li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <form action="{{ route('admin.general-settings.update') }}" method="post" enctype="multipart/form-data">
          @csrf

          <div class="card-header border-0 pb-0 pt-4 px-4">
            <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Update General Settings') }}</h3>
          </div>

          <div class="card-body px-4 py-4">
            <div class="row">
              <div class="col-lg-10 mx-auto">
                @csrf

                {{-- ====== INFORMATION SECTION ====== --}}
                <div class="gs-section-header mb-3">
                  <div class="gs-section-icon" style="background: #FFF7ED; color: #F59E0B;">
                    <i class="fas fa-info-circle"></i>
                  </div>
                  <h5 class="gs-section-title" style="color: #F59E0B;">{{ __('Information') }}</h5>
                </div>

                <div class="row mb-4">
                  <div class="col-md-12">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ __('Website Title') }} <span class="text-danger">**</span></label>
                      <input class="form-control gs-input" name="website_title" value="{{ $abs->website_title }}">
                      @if ($errors->has('website_title'))
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('website_title') }}</p>
                      @endif
                    </div>
                  </div>

                  {{-- Favicon --}}
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="gs-label">{{ __('Favicon') }}</label>
                      <div class="gs-image-uploader showImage showImage-sm">
                        <img src="{{ $abs->favicon ? asset('assets/front/img/' . $abs->favicon) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="Favicon" class="gs-image-preview">
                        @if (!is_null(@$abs->favicon))
                          <x-remove-button
                            url="{{ route('admin.basic_settings.removeImage', ['language_id' => $language->id]) }}"
                            name="favicon" type="logo" />
                        @endif
                      </div>
                      <div role="button" class="btn-primary-purple py-2 px-3 upload-btn mt-2"
                        id="image" style="border-radius: 8px; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="favicon">
                      </div>
                      @error('favicon')
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('favicon') }}</p>
                      @enderror
                    </div>
                  </div>

                  {{-- Logo --}}
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="gs-label">{{ __('Logo') }}</label>
                      <div class="gs-image-uploader showImage2 showImage-sm-2">
                        <img src="{{ $abs->logo ? asset('assets/front/img/' . $abs->logo) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="Logo" class="gs-image-preview">
                        @if (!is_null(@$abs->logo))
                          <x-remove-button
                            url="{{ route('admin.basic_settings.removeImage', ['language_id' => $language->id]) }}"
                            name="logo" type="logo" />
                        @endif
                      </div>
                      <div role="button" class="btn-primary-purple py-2 px-3 upload-btn mt-2"
                        id="image2" style="border-radius: 8px; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="logo">
                      </div>
                      @error('logo')
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('logo') }}</p>
                      @enderror
                    </div>
                  </div>

                  {{-- Preloader --}}
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="gs-label">{{ __('Preloader') }}</label>
                      <div class="gs-image-uploader showImage3 showImage-sm-3">
                        <img src="{{ $abs->preloader ? asset('assets/front/img/' . $abs->preloader) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="Preloader" class="gs-image-preview">
                        @if (!is_null(@$abs->preloader))
                          <x-remove-button
                            url="{{ route('admin.basic_settings.removeImage', ['language_id' => $language->id]) }}"
                            name="preloader" type="logo" />
                        @endif
                      </div>
                      <div role="button" class="btn-primary-purple py-2 px-3 upload-btn mt-2"
                        id="image3" style="border-radius: 8px; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="preloader">
                      </div>
                      @error('preloader')
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('preloader') }}</p>
                      @enderror
                      <p class="popup-field-hint mt-2">{{ __('Only GIF, JPG, JPEG, PNG file formats are allowed') }}</p>
                    </div>
                  </div>

                  {{-- Preloader Status --}}
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="gs-label">{{ __('Preloader Status') }}</label>
                      <div class="gs-toggle-group">
                        <label class="gs-toggle-option {{ $abs->preloader_status == 1 ? 'active' : '' }}">
                          <input type="radio" name="preloader_status" value="1" class="d-none"
                            {{ $abs->preloader_status == 1 ? 'checked' : '' }}>
                          {{ __('Active') }}
                        </label>
                        <label class="gs-toggle-option {{ $abs->preloader_status == 0 ? 'active' : '' }}">
                          <input type="radio" name="preloader_status" value="0" class="d-none"
                            {{ $abs->preloader_status == 0 ? 'checked' : '' }}>
                          {{ __('Deactive') }}
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="gs-divider mb-4"></div>

                {{-- ====== REGIONAL TIME PREFERENCES ====== --}}
                <div class="gs-section-header mb-3">
                  <div class="gs-section-icon" style="background: #FFF7ED; color: #F59E0B;">
                    <i class="fas fa-clock"></i>
                  </div>
                  <h5 class="gs-section-title" style="color: #F59E0B;">{{ __('Regional Time Preferences') }}</h5>
                </div>

                <div class="row mb-4">
                  <div class="col-md-6">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ __('Timezone') }} <span class="text-danger">**</span></label>
                      <select name="timezone" class="form-control gs-input select2">
                        @foreach ($timezones as $timezone)
                          <option value="{{ $timezone->timezone }}"
                            {{ $timezone->timezone == $abe->timezone ? 'selected' : '' }}>{{ $timezone->timezone }}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('timezone'))
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('timezone') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ $keywords['Time Format'] ?? __('Time Format') }} <span class="text-danger">**</span></label>
                      <select name="time_format" class="form-control gs-input select2">
                        <option value="12" @selected($abs->time_format == 12)>{{ $keywords['12 Hour'] ?? __('12 Hour') }}</option>
                        <option value="24" @selected($abs->time_format == 24)>{{ $keywords['24 Hour'] ?? __('24 Hour') }}</option>
                      </select>
                      <p id="errtop_rated_count" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="gs-divider mb-4"></div>

                {{-- ====== WEBSITE APPEARANCE ====== --}}
                <div class="gs-section-header mb-3">
                  <div class="gs-section-icon" style="background: #FFF7ED; color: #F59E0B;">
                    <i class="fas fa-palette"></i>
                  </div>
                  <h5 class="gs-section-title" style="color: #F59E0B;">{{ __('Website Appearance') }}</h5>
                </div>

                <div class="row mb-4">
                  <div class="col-md-6">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ __('Base Color Code 1') }} <span class="text-danger">**</span></label>
                      <input class="jscolor form-control gs-input ltr" name="base_color" value="{{ $abs->base_color }}">
                      @if ($errors->has('base_color'))
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('base_color') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ __('Base Color Code 2') }} <span class="text-danger">**</span></label>
                      <input class="jscolor form-control gs-input ltr" name="base_color_2" value="{{ $abs->base_color_2 }}">
                      @if ($errors->has('base_color_2'))
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('base_color_2') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="gs-divider mb-4"></div>

                {{-- ====== CURRENCY SETTINGS ====== --}}
                <div class="gs-section-header mb-3">
                  <div class="gs-section-icon" style="background: #ECFDF5; color: #10B981;">
                    <i class="fas fa-dollar-sign"></i>
                  </div>
                  <h5 class="gs-section-title" style="color: #10B981;">{{ __('Currency Settings') }}</h5>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ __('Base Currency Symbol') }} <span class="text-danger">**</span></label>
                      <select name="base_currency_symbol" class="form-control gs-input">
                        <option value="{{ $abe->base_currency_symbol }}">{{ $abe->base_currency_symbol }}</option>
                      </select>
                      @if ($errors->has('base_currency_symbol'))
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('base_currency_symbol') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ __('Base Currency Symbol Position') }} <span class="text-danger">**</span></label>
                      <select name="base_currency_symbol_position" class="form-control gs-input ltr">
                        <option value="left" {{ $abe->base_currency_symbol_position == 'left' ? 'selected' : '' }}>{{ __('Left') }}</option>
                        <option value="right" {{ $abe->base_currency_symbol_position == 'right' ? 'selected' : '' }}>{{ __('Right') }}</option>
                      </select>
                      @if ($errors->has('base_currency_symbol_position'))
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('base_currency_symbol_position') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ __('Base Currency Text') }} <span class="text-danger">**</span></label>
                      <input type="text" class="form-control gs-input ltr" name="base_currency_text"
                        value="{{ $abe->base_currency_text }}">
                      @if ($errors->has('base_currency_text'))
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('base_currency_text') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ __('Base Currency Text Position') }} <span class="text-danger">**</span></label>
                      <select name="base_currency_text_position" class="form-control gs-input ltr">
                        <option value="left" {{ $abe->base_currency_text_position == 'left' ? 'selected' : '' }}>{{ __('Left') }}</option>
                        <option value="right" {{ $abe->base_currency_text_position == 'right' ? 'selected' : '' }}>{{ __('Right') }}</option>
                      </select>
                      @if ($errors->has('base_currency_text_position'))
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('base_currency_text_position') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group mb-4">
                      <label class="gs-label">{{ __('Base Currency Rate') }} <span class="text-danger">**</span></label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text gs-input-addon">{{ __('1 USD =') }}</span>
                        </div>
                        <input type="text" name="base_currency_rate" class="form-control gs-input ltr"
                          value="{{ $abe->base_currency_rate }}">
                        <div class="input-group-append">
                          <span class="input-group-text gs-input-addon">{{ $abe->base_currency_text }}</span>
                        </div>
                      </div>
                      @if ($errors->has('base_currency_rate'))
                        <p class="mb-0 text-danger small mt-1">{{ $errors->first('base_currency_rate') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="card-footer border-0 bg-transparent pb-4 pt-0 text-center">
            <button type="submit" id="displayNotif" class="btn-gs-update">
              <i class="fas fa-check mr-2"></i> {{ __('Update') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('assets/user/js/image-text.js') }}"></script>
  <script>
    // Preloader status toggle
    document.querySelectorAll('.gs-toggle-option input').forEach(function(radio) {
      radio.addEventListener('change', function() {
        document.querySelectorAll('.gs-toggle-option').forEach(function(el) {
          el.classList.remove('active');
        });
        this.closest('.gs-toggle-option').classList.add('active');
      });
    });
  </script>
@endsection
