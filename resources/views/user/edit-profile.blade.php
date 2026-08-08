@extends('user.layout')

@php
  $selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/cropper.css') }}">
@endsection
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Profile') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('user-dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Profile') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card dark-card" style="border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: none;">
        <div class="card-header dark-border" style="padding: 20px 25px; border-bottom: 1px solid rgba(0,0,0,0.05);">
          <div class="d-flex align-items-center">
            <div class="stat-card-icon-box" style="width: 48px; height: 48px; border-radius: 50%; background: rgba(13, 110, 253, 0.1); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
              <i class="fas fa-user-circle"></i>
            </div>
            <div class="ml-3">
              <h4 class="card-title mb-1 dark-label" style="font-weight: 700; font-size: 18px;">{{ __('Edit Profile') }}</h4>
              <p class="text-muted mb-0 dark-text" style="font-size: 13px;">{{ __('Update your shop profile information.') }}</p>
            </div>
          </div>
        </div>
        <div class="card-body" style="padding: 30px 25px;">
          <form id="ajaxForm" action="{{ route('user-profile-update') }}" method="post" enctype="multipart/form-data">
            @csrf
            
            <!-- Profile Image Section -->
            <div class="form-group mb-4" style="padding: 0;">
              <label class="dark-label mb-3" style="font-weight: 600; font-size: 14px;">{{ __('Profile Image') }}</label>
              <div class="row align-items-center">
                <div class="col-sm-auto mb-3 mb-sm-0">
                  <div class="dark-border d-inline-block" style="padding: 15px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.08); background: rgba(0,0,0,0.01); position: relative;">
                    <div class="showImage d-flex align-items-center justify-content-center" style="width: 130px; height: 130px; border-radius: 50%; background: rgba(13, 110, 253, 0.08); overflow: hidden; margin: 0 auto;">
                      @if ($user->photo)
                        <img src="{{ asset('assets/front/img/user/' . $user->photo) }}" alt="..." style="width: 100%; height: 100%; object-fit: cover;">
                      @else
                        <i class="fas fa-store" style="font-size: 52px; color: #0d6efd;"></i>
                      @endif
                    </div>
                    <div style="position: absolute; top: 15px; right: 15px; width: 32px; height: 32px; border-radius: 50%; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="$('#photo_input').click();">
                      <i class="fas fa-pen text-muted" style="font-size: 12px;"></i>
                    </div>
                  </div>
                </div>
                <div class="col-sm-auto mb-3 mb-sm-0">
                  <div style="border: 2px dashed rgba(13, 110, 253, 0.3); border-radius: 12px; padding: 25px 35px; text-align: center; background: rgba(13, 110, 253, 0.02); min-width: 240px; position: relative;">
                    <i class="fas fa-cloud-upload-alt mb-2 text-muted" style="font-size: 28px;"></i>
                    <p class="text-muted mb-2 dark-text" style="font-size: 13px; line-height: 1.4;">{{ __('Drag & drop an image here') }}<br>{{ __('or') }}</p>
                    <div role="button" class="btn btn-primary btn-sm rounded-pill upload-btn" id="image" style="padding: 6px 20px; font-weight: 600; position: relative; overflow: hidden;">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" id="photo_input" name="photo" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    </div>
                  </div>
                </div>
              </div>
              <p class="text-muted mt-2 mb-0 dark-text" style="font-size: 12px;">{{ __('Image Size: 100 x 100 px') }} &bull; {{ __('JPG, PNG or WEBP') }}</p>
              <p id="errphoto" class="mb-0 text-danger em"></p>
            </div>

            <hr class="dark-border my-4" style="border-top: 1px solid rgba(0,0,0,0.05);">

            <!-- Form Fields Grid -->
            <div class="row">
              <div class="col-md-4 mb-3">
                <div class="form-group" style="padding: 0;">
                  <label class="dark-label mb-2" style="font-weight: 600; font-size: 13px;">{{ __('Shop Name') }} <span class="text-danger">*</span></label>
                  <div class="d-flex align-items-center">
                    <div class="stat-card-icon-box mr-2" style="width: 42px; height: 42px; border-radius: 8px; background: rgba(13, 110, 253, 0.08); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                      <i class="fas fa-store"></i>
                    </div>
                    <input type="text" class="form-control dark-input" style="border-radius: 8px; height: 42px; font-size: 14px;" name="shop_name" value="{{ $user->shop_name }}" placeholder="{{ __('Enter shop name') }}">
                  </div>
                  <p id="errshop_name" class="mb-0 text-danger em mt-1"></p>
                </div>
              </div>

              <div class="col-md-4 mb-3">
                <div class="form-group" style="padding: 0;">
                  <label class="dark-label mb-2" style="font-weight: 600; font-size: 13px;">{{ __('Username') }} <span class="text-danger">*</span></label>
                  <div class="d-flex align-items-center">
                    <div class="stat-card-icon-box mr-2" style="width: 42px; height: 42px; border-radius: 8px; background: rgba(13, 110, 253, 0.08); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                      <i class="fas fa-user"></i>
                    </div>
                    <input type="text" class="form-control dark-input" style="border-radius: 8px; height: 42px; font-size: 14px;" name="username" value="{{ $user->username }}" placeholder="{{ __('Enter username') }}">
                  </div>
                  <p id="errusername" class="mb-0 text-danger em mt-1"></p>
                </div>
              </div>

              <div class="col-md-4 mb-3">
                <div class="form-group" style="padding: 0;">
                  <label class="dark-label mb-2" style="font-weight: 600; font-size: 13px;">{{ __('Phone') }} <span class="text-danger">*</span></label>
                  <div class="d-flex align-items-center">
                    <div class="stat-card-icon-box mr-2" style="width: 42px; height: 42px; border-radius: 8px; background: rgba(13, 110, 253, 0.08); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                      <i class="fas fa-phone-alt"></i>
                    </div>
                    <input type="text" class="form-control dark-input" style="border-radius: 8px; height: 42px; font-size: 14px;" name="phone" value="{{ $user->phone }}" placeholder="{{ __('Enter phone') }}">
                  </div>
                  <p id="errphone" class="mb-0 text-danger em mt-1"></p>
                </div>
              </div>

              <div class="col-md-4 mb-3">
                <div class="form-group" style="padding: 0;">
                  <label class="dark-label mb-2" style="font-weight: 600; font-size: 13px;">{{ __('City') }} <span class="text-danger">*</span></label>
                  <div class="d-flex align-items-center">
                    <div class="stat-card-icon-box mr-2" style="width: 42px; height: 42px; border-radius: 8px; background: rgba(13, 110, 253, 0.08); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                      <i class="fas fa-building"></i>
                    </div>
                    <input type="text" class="form-control dark-input" style="border-radius: 8px; height: 42px; font-size: 14px;" name="city" value="{{ $user->city }}" placeholder="{{ __('Enter city') }}">
                  </div>
                  <p id="errcity" class="mb-0 text-danger em mt-1"></p>
                </div>
              </div>

              <div class="col-md-4 mb-3">
                <div class="form-group" style="padding: 0;">
                  <label class="dark-label mb-2" style="font-weight: 600; font-size: 13px;">{{ __('State') }}</label>
                  <div class="d-flex align-items-center">
                    <div class="stat-card-icon-box mr-2" style="width: 42px; height: 42px; border-radius: 8px; background: rgba(13, 110, 253, 0.08); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                      <i class="fas fa-map"></i>
                    </div>
                    <input type="text" class="form-control dark-input" style="border-radius: 8px; height: 42px; font-size: 14px;" name="state" value="{{ $user->state }}" placeholder="{{ __('Enter state') }}">
                  </div>
                </div>
              </div>

              <div class="col-md-4 mb-3">
                <div class="form-group" style="padding: 0;">
                  <label class="dark-label mb-2" style="font-weight: 600; font-size: 13px;">{{ __('Country') }} <span class="text-danger">*</span></label>
                  <div class="d-flex align-items-center">
                    <div class="stat-card-icon-box mr-2" style="width: 42px; height: 42px; border-radius: 8px; background: rgba(13, 110, 253, 0.08); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                      <i class="fas fa-globe"></i>
                    </div>
                    <input type="text" class="form-control dark-input" style="border-radius: 8px; height: 42px; font-size: 14px;" name="country" value="{{ $user->country }}" placeholder="{{ __('Enter country') }}">
                  </div>
                  <p id="errcountry" class="mb-0 text-danger em mt-1"></p>
                </div>
              </div>

              <div class="col-md-4 mb-3">
                <div class="form-group" style="padding: 0;">
                  <label class="dark-label mb-2" style="font-weight: 600; font-size: 13px;">{{ __('Category') }}</label>
                  <div class="d-flex align-items-center">
                    <div class="stat-card-icon-box mr-2" style="width: 42px; height: 42px; border-radius: 8px; background: rgba(13, 110, 253, 0.08); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                      <i class="fas fa-tag"></i>
                    </div>
                    <select name="category_id" class="form-control select2 dark-input" style="border-radius: 8px; height: 42px; font-size: 14px;">
                      <option value="">{{ __('Select Shop Category') }}</option>
                      @foreach ($categories as $category)
                        <option value="{{ $category->unique_id }}" @selected($category->unique_id == $user->category_id)>{{ $category->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-md-12 mb-3">
                <div class="form-group" style="padding: 0;">
                  <label class="dark-label mb-2" style="font-weight: 600; font-size: 13px;">{{ __('Address') }} <span class="text-danger">*</span></label>
                  <div class="d-flex">
                    <div class="stat-card-icon-box mr-2" style="width: 42px; height: 42px; border-radius: 8px; background: rgba(13, 110, 253, 0.08); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                      <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <textarea class="form-control dark-input" style="border-radius: 8px; font-size: 14px;" name="address" rows="3" placeholder="{{ __('Enter address') }}">{{ $user->address }}</textarea>
                  </div>
                  <p id="erraddress" class="mb-0 text-danger em mt-1"></p>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer dark-border" style="background: transparent; border-top: 1px solid rgba(0,0,0,0.05); padding: 25px; text-align: center;">
          <button type="button" onclick="$('#ajaxForm').submit();" id="submitBtn" class="btn btn-success" style="border-radius: 8px; padding: 10px 32px; font-weight: 600; font-size: 15px;">
            <i class="fas fa-save mr-2"></i> {{ __('Update Profile') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection
