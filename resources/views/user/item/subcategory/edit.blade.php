@extends('user.layout')

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Subcategory') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a
          href="{{ route('user.itemsubcategory.index') . '?language=' . request()->input('language') }}">{{ __('Subcategories') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Subcategory') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-0 pb-3 pt-4 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap">
          <div class="d-flex align-items-center mb-2 mb-lg-0">
            <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 110, 253, 0.1);">
              <i class="fas fa-layer-group text-primary" style="font-size: 18px;"></i>
            </span>
            <div>
              <h4 class="card-title font-weight-bold mb-1" style="font-size: 18px;">{{ __('Subcategory Information') }}</h4>
              <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Update the details of your subcategory.') }}</p>
            </div>
          </div>
          <a class="btn btn-outline-primary btn-sm font-weight-bold d-inline-flex align-items-center" style="border-radius: 8px; padding: 6px 16px; height: 36px;"
            href="{{ route('user.itemsubcategory.index') . '?language=' . request()->input('language') }}">
            <i class="fas fa-arrow-left mr-2"></i> {{ __('Back') }}
          </a>
        </div>
        <div class="card-body px-4 pt-4 pb-5">
          <div class="row">
            <div class="col-lg-10 mx-auto">
              <form id="ajaxForm" action="{{ route('user.itemsubcategory.update') }}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <div class="form-group p-0">
                      <label class="font-weight-bold mb-2" style="font-size: 14px;">{{ __('Category') }} <span class="text-danger">*</span></label>
                      <select id="language" name="category_id" class="form-control font-weight-bold" style="border-radius: 8px; height: 42px; border: 1px solid rgba(0,0,0,0.15);">
                        <option value="" selected disabled>{{ __('Select Category') }}</option>
                        @foreach ($categories as $category)
                          <option {{ $data->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">
                            {{ $category->name }}</option>
                        @endforeach
                      </select>
                      <p id="errcategory_id" class="mb-0 text-danger em"></p>
                    </div>
                  </div>

                  @foreach ($languages as $lang)
                    @php
                      $subcategory = App\Models\User\UserItemSubCategory::where([
                          ['language_id', $lang->id],
                          ['unique_id', $data->unique_id],
                      ])->first();
                    @endphp
                    <input type="hidden" name="{{ $lang->code }}_id" value="{{ @$subcategory->id }}">
                    <div class="col-md-6 mb-3">
                      <div class="form-group p-0">
                        <label class="font-weight-bold mb-2" style="font-size: 14px;">{{ __('Name') }} ({{ $lang->name }}) <span class="text-danger">*</span></label>
                        <input type="text"
                          class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                          style="border-radius: 8px; height: 42px; border: 1px solid rgba(0,0,0,0.15);"
                          name="{{ $lang->code }}_name" value="{{ @$subcategory->name }}"
                          placeholder="{{ __('Enter name') }}">
                        <p id="err{{ $lang->code }}_name" class="mb-0 text-danger em"></p>
                        @if ($lang->is_default != 1 && !empty($subcategory->name))
                          <p class="mt-1 mb-0" style="color: #fd7e14; font-size: 13px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ __('You cannot remove the subcategory name for') . ' ' . $lang->name . '. ' . __('Delete data manually.') }}
                          </p>
                        @endif
                      </div>
                    </div>
                  @endforeach
                  <input type="hidden" name="subcategory_id" value="{{ $data->id }}">

                  <div class="col-md-6 mb-3">
                    <div class="form-group p-0">
                      <label class="font-weight-bold mb-2" style="font-size: 14px;">{{ __('Status') }} <span class="text-danger">*</span></label>
                      <select class="form-control font-weight-bold" style="border-radius: 8px; height: 42px; border: 1px solid rgba(0,0,0,0.15);" name="status">
                        <option value="1" class="text-success font-weight-bold" {{ $data->status == 1 ? 'selected' : '' }}>&#9679; {{ __('Active') }}</option>
                        <option value="0" class="text-danger font-weight-bold" {{ $data->status == 0 ? 'selected' : '' }}>&#9679; {{ __('Deactive') }}</option>
                      </select>
                      <p id="errstatus" class="mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-md-6 mb-3">
                    <div class="form-group p-0">
                      <label class="font-weight-bold mb-2" style="font-size: 14px;">{{ __('Serial Number') }} <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" style="border-radius: 8px; height: 42px; border: 1px solid rgba(0,0,0,0.15);" name="serial_number" value="{{ $data->serial_number }}"
                        placeholder="{{ __('Enter Serial Number') }}">
                      <p id="errserial_number" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer border-0 pt-0 pb-4 px-4 bg-transparent">
          <div class="row">
            <div class="col-12 text-center d-flex justify-content-center gap-2">
              <button type="submit" id="submitBtn" class="btn btn-success font-weight-bold d-inline-flex align-items-center mr-2" style="border-radius: 8px; padding: 10px 24px; background: #28a745; border-color: #28a745;">
                <i class="fas fa-check mr-2"></i> {{ __('Update Subcategory') }}
              </button>
              <button type="button" class="btn btn-outline-secondary font-weight-bold d-inline-flex align-items-center" style="border-radius: 8px; padding: 10px 24px; background: #fff; border: 1px solid rgba(0,0,0,0.15); color: #495057;" onclick="window.location.reload();">
                <i class="fas fa-redo mr-2"></i> {{ __('Reset') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
