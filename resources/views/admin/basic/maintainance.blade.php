@extends('admin.layout')

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Maintenance Mode') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
      </h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i></a>
        </li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Settings') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Maintenance Mode') }}</a></li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Update Maintenance Mode') }}</h3>
        </div>

        <div class="card-body p-4">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <form id="maintenanceForm" action="{{ route('admin.maintainance.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                {{-- Maintenance Image --}}
                <div class="form-group mb-4">
                  <label class="gs-label">{{ __('Maintenance Image') }} <span class="text-danger">**</span></label>
                  <div class="row align-items-center">
                    <div class="col-md-6">
                      <div class="maint-img-card showImage">
                        <img src="{{ asset('assets/front/img/' . $bs->maintenance_img) }}" alt="Maintenance Image"
                          class="maint-img-preview">
                        <div role="button" class="btn btn-light upload-btn maint-choose-btn" id="image">
                          <i class="fas fa-cloud-upload-alt mr-2"></i> {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="file">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="maint-info-box">
                        <div class="maint-info-icon">
                          <i class="fas fa-image"></i>
                        </div>
                        <div>
                          <p class="mb-1" style="font-size: 0.875rem; font-weight: 600; color: var(--text-main);">
                            {{ __('Upload an image to display during maintenance mode.') }}
                          </p>
                          <p class="mb-1 text-muted" style="font-size: 0.8rem;">
                            {{ __('Supported formats: GIF, JPG, JPEG, PNG') }}
                          </p>
                          <p class="mb-0 text-muted" style="font-size: 0.8rem;">
                            {{ __('Recommended size: 1200x800px') }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <p id="errfile" class="mb-0 text-danger em small mt-1"></p>
                </div>

                {{-- Maintenance Status --}}
                <div class="form-group mb-4">
                  <label class="gs-label">{{ __('Maintenance Status') }} <span class="text-danger">**</span></label>
                  <div class="maint-status-group">
                    <label class="maint-status-btn {{ $data->maintenance_status == 1 ? 'active' : '' }}">
                      <input type="radio" name="maintenance_status" value="1" class="d-none"
                        {{ $data->maintenance_status == 1 ? 'checked' : '' }}>
                      <i class="fas fa-check-circle mr-2"></i> {{ __('Active') }}
                    </label>
                    <label class="maint-status-btn {{ $data->maintenance_status == 0 ? 'active' : '' }}" style="background: {{ $data->maintenance_status == 0 ? '#F1F5F9' : '' }}; color: {{ $data->maintenance_status == 0 ? 'var(--text-muted)' : '' }};">
                      <input type="radio" name="maintenance_status" value="0" class="d-none"
                        {{ $data->maintenance_status == 0 ? 'checked' : '' }}>
                      <i class="fas fa-times-circle mr-2"></i> {{ __('Deactivate') }}
                    </label>
                  </div>
                  @if ($errors->has('maintenance_status'))
                    <p class="mt-2 mb-0 text-danger small">{{ $errors->first('maintenance_status') }}</p>
                  @endif
                </div>

                {{-- Maintenance Message --}}
                <div class="form-group mb-4">
                  <label class="gs-label">{{ __('Maintenance Message') }} <span class="text-danger">**</span></label>
                  <textarea class="form-control popup-field-input" name="maintainance_text" rows="4"
                    style="height: auto !important;">{{ $data->maintainance_text }}</textarea>
                  @if ($errors->has('maintainance_text'))
                    <p class="mt-2 mb-0 text-danger small">{{ $errors->first('maintainance_text') }}</p>
                  @endif
                </div>

                {{-- Secret Path --}}
                <div class="form-group mb-4">
                  <label class="gs-label">{{ __('Secret Path') }}</label>
                  <input name="secret_path" type="text" class="form-control popup-field-input"
                    value="{{ $data->secret_path }}">

                  {{-- Orange notice box --}}
                  <div class="maint-notice-box mt-3">
                    <div class="maint-notice-icon">
                      <i class="fas fa-lock"></i>
                    </div>
                    <div>
                      <p class="mb-1" style="font-size: 0.875rem;">
                        {{ __('After activating maintenance mode, You can access the website via') }}
                      </p>
                      <a href="{{ url($data->secret_path) }}" class="maint-notice-link" target="_blank">
                        {{ url('{secret_path}') }}
                      </a>
                    </div>
                  </div>

                  {{-- Green tip --}}
                  <div class="maint-tip mt-3">
                    <i class="fas fa-check-circle mr-2" style="color: #10B981;"></i>
                    <span>{{ __('Try to avoid using special characters') }}</span>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>

        <div class="card-footer border-0 bg-transparent pb-4 pt-0 text-center">
          <button type="submit" form="maintenanceForm" class="btn-gs-update">
            <i class="fas fa-check"></i> {{ __('Update') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    // Maintenance status toggle visual
    document.querySelectorAll('.maint-status-btn input').forEach(function(radio) {
      radio.addEventListener('change', function() {
        document.querySelectorAll('.maint-status-btn').forEach(function(btn) {
          btn.classList.remove('active');
          btn.style.background = '';
          btn.style.color = '';
        });
        this.closest('.maint-status-btn').classList.add('active');
        this.closest('.maint-status-btn').style.background = '';
        this.closest('.maint-status-btn').style.color = '';
      });
    });
  </script>
@endsection
