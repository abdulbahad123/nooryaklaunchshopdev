@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Category') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fas fa-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="fas fa-chevron-right"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Users Management') }}</a>
      </li>
      <li class="separator">
        <i class="fas fa-chevron-right"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('register.user.category', ['language' => request()->input('language')]) }}">{{ __('Categories') }}</a>
      </li>
      <li class="separator">
        <i class="fas fa-chevron-right"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Category') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="card-title m-0">{{ __('Edit Category') }}</div>
          <a class="btn-back-pill"
            href="{{ route('register.user.category', ['language' => request()->input('language')]) }}">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-4 pb-5">
          <div class="row">
            <div class="col-lg-7 m-auto">
              <form id="ajaxForm" action="{{ route('register.user.category_update') }}" method="POST">
                @csrf
                @foreach ($langs as $language)
                  @php
                    $category = App\Models\Admin\UserCategory::where([
                        ['language_id', $language->id],
                        ['unique_id', $data->unique_id],
                    ])->first();
                    $initialChar = strtoupper(substr($language->name, 0, 1));
                    if ($language->code == 'ar') { $initialChar = 'ن'; }
                  @endphp

                  <input type="hidden" name="{{ $language->code }}_id" value="{{ @$category->id }}">
                  
                  <!-- Form Row with Left Icon Circle (Matching Reference Image 1) -->
                  <div class="form-row-icon-group">
                    <div class="form-row-icon-circle i-purple">
                      {{ $initialChar }}
                    </div>
                    <div class="flex-grow-1">
                      <label for="">{{ __('Name') }} ({{ $language->name }})
                        @if ($language->is_default == 1)
                          <span class="text-danger">*</span>
                        @endif
                      </label>
                      <input type="text"
                        class="form-control {{ $language->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                        name="{{ $language->code }}_name" value="{{ @$category->name }}"
                        placeholder="{{ __('Enter name') }}">
                      <p id="err{{ $language->code }}_name" class="mb-0 text-danger em"></p>
                      @if ($language->is_default != 1 && !empty($category->name))
                        <p class="text-warning-note mb-0">
                          {{ __('You cannot remove the category name for') . ' ' . $language->name . '. ' . __('Delete data manually.') }}
                        </p>
                      @endif
                    </div>
                  </div>
                @endforeach
                
                <input type="hidden" name="category_id" value="{{ $data->id }}">
                
                <!-- Status Row with Flag Icon Circle -->
                <div class="form-row-icon-group">
                  <div class="form-row-icon-circle i-green">
                    🚩
                  </div>
                  <div class="flex-grow-1">
                    <label for="">{{ __('Status') }} <span class="text-danger">*</span></label>
                    <select class="form-control ltr" name="status">
                      <option value="" selected disabled>{{ __('Select a status') }}</option>
                      <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                      <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                    </select>
                    <p id="errstatus" class="mb-0 text-danger em"></p>
                  </div>
                </div>

                <!-- Serial Number Row with Hash Icon Circle -->
                <div class="form-row-icon-group">
                  <div class="form-row-icon-circle i-blue">
                    #
                  </div>
                  <div class="flex-grow-1">
                    <label for="">{{ __('Serial Number') }} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control ltr" name="serial_number"
                      placeholder="{{ __('Enter Serial Number') }}" value="{{ $data->serial_number }}">
                    <p id="errserial_number" class="mb-0 text-danger em"></p>
                    <p class="text-warning-note mb-0">
                      {{ __('The higher the serial number is, the later the user category will be shown.') }}
                    </p>
                  </div>
                </div>

                <!-- Form Submit Button at Center -->
                <div class="text-center mt-4">
                  <button type="submit" id="submitBtn" class="btn-primary-purple">
                    <i class="fas fa-check-circle"></i>
                    {{ __('Update') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
