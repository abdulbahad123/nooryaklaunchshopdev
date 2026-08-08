@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Shop Landing Details') }}</h4>
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
        <a href="{{ route('admin.shops.index') }}">{{ __('Shops') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Shop') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Shop') }}: {{ $shop->username }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.shops.index') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 m-auto">
              <form action="{{ route('admin.shops.update', $shop->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                
                {{-- Preview Image Upload --}}
                <div class="form-group">
                  <label for="">{{ __('Custom Shop Card Thumbnail') }}</label>
                  <div class="col-md-12 mb-3">
                    @if (!empty($shop->template_img))
                      <img class="img-thumbnail" src="{{ asset('assets/front/img/template-previews/' . $shop->template_img) }}" 
                        alt="Current Thumbnail" style="max-width: 250px; display: block; margin-bottom: 10px;">
                    @else
                      <span class="badge badge-warning mb-2 d-inline-block">{{ __('Theme Default Screenshot') }}</span>
                    @endif
                  </div>
                  <input type="file" class="form-control" name="preview_image">
                  <p class="text-warning mb-0">{{ __('Only png, jpg, jpeg images under 2MB are allowed.') }}</p>
                  @error('preview_image')
                    <p class="text-danger mb-0">{{ $message }}</p>
                  @enderror
                </div>

                {{-- Shop Logo --}}
                <div class="form-group">
                  <label for="">{{ __('Shop Logo') }}</label>
                  <div class="col-md-12 mb-3 pl-0">
                    @if (!empty($shop->photo))
                      <img class="img-thumbnail rounded-circle" src="{{ asset('assets/front/img/user/' . $shop->photo) }}" 
                        alt="Current Logo" style="width: 80px; height: 80px; object-fit: cover; display: block; margin-bottom: 10px;">
                    @else
                      <img class="img-thumbnail rounded-circle" src="{{ asset('assets/user/img/profile.png') }}" 
                        alt="Default Profile" style="width: 80px; height: 80px; object-fit: cover; display: block; margin-bottom: 10px;">
                    @endif
                  </div>
                  <input type="file" class="form-control" name="logo">
                  <p class="text-warning mb-0">{{ __('Only png, jpg, jpeg images under 2MB are allowed.') }}</p>
                  @error('logo')
                    <p class="text-danger mb-0">{{ $message }}</p>
                  @enderror
                </div>

                {{-- Category Select --}}
                <div class="form-group">
                  <label for="">{{ __('Category') }}</label>
                  <select name="category" class="form-control">
                    <option value="" selected>{{ __('Select Category') }}</option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->unique_id }}" {{ old('category', $shop->category_id) == $category->unique_id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                  </select>
                  @error('category')
                    <p class="text-danger mb-0">{{ $message }}</p>
                  @enderror
                </div>

                {{-- Shop Name --}}
                <div class="form-group">
                  <label for="">{{ __('Shop Name') }} <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="shop_name" value="{{ old('shop_name', $shop->shop_name) }}" placeholder="{{ __('Enter Shop Name') }}">
                  @error('shop_name')
                    <p class="text-danger mb-0">{{ $message }}</p>
                  @enderror
                </div>

                {{-- Landing Rating --}}
                <div class="form-group">
                  <label for="">{{ __('Landing Page Rating') }} <span class="text-danger">*</span></label>
                  <input type="number" step="0.01" min="0" max="5" class="form-control" name="landing_rating" value="{{ old('landing_rating', $shop->landing_rating ?: '4.80') }}">
                  <small class="text-muted">{{ __('Numeric value between 0.00 and 5.00') }}</small>
                  @error('landing_rating')
                    <p class="text-danger mb-0">{{ $message }}</p>
                  @enderror
                </div>

                {{-- Sorting Order --}}
                <div class="form-group">
                  <label for="">{{ __('Sort Order') }} <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" name="landing_order" value="{{ old('landing_order', $shop->landing_order ?: 0) }}">
                  <small class="text-muted">{{ __('Lower number will appear first in the directory') }}</small>
                  @error('landing_order')
                    <p class="text-danger mb-0">{{ $message }}</p>
                  @enderror
                </div>

                {{-- Landing Status --}}
                <div class="form-group">
                  <label for="">{{ __('Landing Directory Approval Status') }} <span class="text-danger">*</span></label>
                  <select name="landing_status" class="form-control">
                    <option value="1" {{ old('landing_status', $shop->landing_status) == 1 ? 'selected' : '' }}>{{ __('Approved (Visible)') }}</option>
                    <option value="0" {{ old('landing_status', $shop->landing_status) == 0 ? 'selected' : '' }}>{{ __('Pending / Hidden (Rejected)') }}</option>
                  </select>
                  @error('landing_status')
                    <p class="text-danger mb-0">{{ $message }}</p>
                  @enderror
                </div>

                {{-- Description --}}
                <div class="form-group">
                  <label for="">{{ __('Short Description') }}</label>
                  <textarea class="form-control" name="landing_description" rows="4" placeholder="{{ __('Enter shop summary or tagline for directory card') }}">{{ old('landing_description', $shop->landing_description) }}</textarea>
                  @error('landing_description')
                    <p class="text-danger mb-0">{{ $message }}</p>
                  @enderror
                </div>

                <div class="card-footer text-center">
                  <button type="submit" class="btn btn-success">{{ __('Update Shop') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
