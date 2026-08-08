@extends('user.layout')

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Category') }}</h4>
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
          href="{{ route('user.itemcategory.index', ['language' => request()->input('language')]) }}">{{ __('Categories') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Category') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-0 pb-3 pt-4 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap">
          <div class="d-flex align-items-center mb-2 mb-lg-0">
            <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 110, 253, 0.1);">
              <i class="fas fa-edit text-primary" style="font-size: 18px;"></i>
            </span>
            <div>
              <h4 class="card-title font-weight-bold mb-1" style="font-size: 18px;">{{ __('Edit Category') }}</h4>
              <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Update the details of your category.') }}</p>
            </div>
          </div>
          <a class="btn btn-outline-primary btn-sm font-weight-bold d-inline-flex align-items-center" style="border-radius: 8px; padding: 6px 16px; height: 36px;"
            href="{{ route('user.itemcategory.index') . '?language=' . request()->input('language') }}">
            <i class="fas fa-arrow-left mr-2"></i> {{ __('Back') }}
          </a>
        </div>
        <div class="card-body px-4 pt-4 pb-5">
          <div class="row">
            <div class="col-lg-10 mx-auto">
              <form id="ajaxForm" action="{{ route('user.itemcategory.update') }}" method="POST">
                @csrf
                {{-- Image Part --}}
                <div class="row">
                  @php
                    $allow_background_image_theme = ['pet'];
                  @endphp
                  @if (in_array($userBs->theme, $allow_background_image_theme))
                    <div class="col-md-6 mb-4">
                      <div class="form-group p-0">
                        <label class="font-weight-bold mb-2 d-block" for="image" style="font-size: 14px;">{{ __('Category Background Image') }} <span class="text-danger">*</span></label>
                        <div class="p-3 text-center d-inline-block" style="border: 1px dashed rgba(13, 110, 253, 0.3); border-radius: 12px; background: rgba(13, 110, 253, 0.02); position: relative;">
                          <div class="showImage2 mb-2">
                            <img src="{{ $data->category_background_image ? asset('assets/front/img/user/items/category_background/' . $data->category_background_image) : asset('assets/admin/img/noimage.jpg') }}"
                              alt="..." class="img-thumbnail border-0" style="max-height: 120px; border-radius: 8px;">
                          </div>
                          <div role="button" class="btn btn-outline-primary btn-sm upload-btn font-weight-bold mt-1" id="image2" style="border-radius: 8px; padding: 6px 16px; background: rgba(13, 110, 253, 0.05);">
                            <i class="fas fa-upload mr-1"></i> {{ __('Choose Image') }}
                            <input type="file" class="img-input" name="background_image">
                          </div>
                        </div>
                        @if ($userBs->theme == 'pet')
                          <p class="text-muted mt-2 mb-0" style="font-size: 13px;">{{ __('Recommended Image size : 120X190') }}</p>
                        @elseif ($userBs->theme == 'jewellery')
                          <p class="text-muted mt-2 mb-0" style="font-size: 13px;">{{ __('Recommended Image size : 220x340') }}</p>
                        @else
                          <p class="text-muted mt-2 mb-0" style="font-size: 13px;">{{ __('Recommended Image size : 100X100') }}</p>
                        @endif
                        <p id="errbackground_image" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                  @endif
                  <div class="{{ in_array($userBs->theme, $allow_background_image_theme) ? 'col-md-6' : 'col-md-12' }} mb-4">
                    <div class="form-group p-0">
                      <label class="font-weight-bold mb-2 d-block" for="image" style="font-size: 14px;">{{ __('Category Image') }} <span class="text-danger">*</span></label>
                      <div class="p-3 text-center d-inline-block" style="border: 1px dashed rgba(13, 110, 253, 0.3); border-radius: 12px; background: rgba(13, 110, 253, 0.02); position: relative;">
                        <div class="showImage mb-2">
                          <img src="{{ $data->image ? asset('assets/front/img/user/items/categories/' . $data->image) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="..." class="img-thumbnail border-0" style="max-height: 120px; border-radius: 8px;">
                        </div>
                        <div role="button" class="btn btn-outline-primary btn-sm upload-btn font-weight-bold mt-1" id="image" style="border-radius: 8px; padding: 6px 16px; background: rgba(13, 110, 253, 0.05);">
                          <i class="fas fa-upload mr-1"></i> {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="image">
                        </div>
                      </div>
                      <p class="text-muted mt-2 mb-0" style="font-size: 13px;">{{ __('Recommended Image size : 100X100. Max size 2MB.') }}</p>
                      <p id="errimage" class="mb-0 text-danger em"></p>
                    </div>
                  </div>

                  @foreach ($languages as $language)
                    <div class="col-md-6 mb-3">
                      @php
                        $category = App\Models\User\UserItemCategory::where([
                            ['language_id', $language->id],
                            ['unique_id', $data->unique_id],
                            ['user_id', Auth::guard('web')->user()->id],
                        ])->first();
                      @endphp
                      <input type="hidden" name="{{ $language->code }}_id" value="{{ @$category->id }}">
                      <div class="form-group p-0">
                        <label class="font-weight-bold mb-2" style="font-size: 14px;">{{ __('Name') }} ({{ $language->name }}) <span class="text-danger">*</span></label>
                        <input type="text"
                          class="form-control {{ $language->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                          style="border-radius: 8px; height: 42px; border: 1px solid rgba(0,0,0,0.15);"
                          name="{{ $language->code }}_name" value="{{ @$category->name }}"
                          placeholder="{{ __('Enter name') }}">
                        <p id="err{{ $language->code }}_name" class="mb-0 text-danger em"></p>
                        @if ($language->is_default != 1 && !empty($category->name))
                          <p class="mt-1 mb-0" style="color: #fd7e14; font-size: 13px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ __('You cannot remove the category name for') . ' ' . $language->name . '. ' . __('Delete data manually.') }}
                          </p>
                        @endif
                      </div>
                    </div>
                  @endforeach
                  <input type="hidden" name="category_id" value="{{ $data->id }}">

                  @if ($userBs->theme == 'vegetables' || $userBs->theme == 'furniture')
                    <div class="col-md-6 mb-3">
                      <div class="form-group p-0">
                        <label class="font-weight-bold mb-2" style="font-size: 14px;">{{ __('Color') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control jscolor" style="border-radius: 8px; height: 42px;" name="color" value="{{ $data->color }}">
                        <p id="errcolor" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                  @endif

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
                      <input type="number" class="form-control" style="border-radius: 8px; height: 42px; border: 1px solid rgba(0,0,0,0.15);" name="serial_number"
                        value="{{ $data->serial_number }}" placeholder="{{ __('Enter Serial Number') }}">
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
                <i class="fas fa-check mr-2"></i> {{ __('Update Category') }}
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
