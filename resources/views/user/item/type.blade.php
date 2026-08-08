@extends('user.layout')
@php
  $default = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
@endphp

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Choose Item Type') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="#">
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
        <a href="#">{{ __('Choose Item Type') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-0 pb-0 pt-4 px-4 bg-transparent d-flex align-items-center">
          <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; border-radius: 12px; background: rgba(13, 110, 253, 0.1); flex-shrink: 0;">
            <i class="fas fa-shopping-bag text-primary" style="font-size: 20px;"></i>
          </span>
          <div>
            <h4 class="card-title font-weight-bold mb-1" style="font-size: 18px;">{{ __('Choose Item Type') }}</h4>
            <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Select the type of item you want to add to your store.') }}</p>
          </div>
        </div>
        <div class="card-body p-4 mt-2">
          <div class="row">
            <div class="col-lg-6 mb-4">
              <a href="{{ route('user.item.create') . '?language=' . $default->code . '&type=physical' }}"
                class="d-block text-decoration-none item-type-card-link">
                <div class="card h-100 mb-0 border-0 text-center py-5 px-4" style="border-radius: 16px; background: #ffffff; border: 2px solid #0d6efd !important; box-shadow: 0 10px 30px rgba(13, 110, 253, 0.08); transition: all 0.3s ease;">
                  <div class="d-flex justify-content-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border-radius: 50%; background: rgba(13, 110, 253, 0.1);">
                      <div class="d-inline-flex align-items-center justify-content-center" style="width: 76px; height: 76px; border-radius: 50%; background: #0d6efd; color: #ffffff; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4);">
                        <i class="fas fa-cube" style="font-size: 32px;"></i>
                      </div>
                    </div>
                  </div>
                  <h3 class="font-weight-bold mb-2" style="font-size: 22px; color: #212529;">{{ __('Physical Product') }}</h3>
                  <p class="text-muted mb-4 mx-auto" style="font-size: 14px; max-width: 280px;">{{ __('Products that require shipping or physical delivery') }}</p>
                  <div class="pt-2 border-top mx-auto d-flex align-items-center justify-content-center" style="max-width: 200px; border-color: rgba(0,0,0,0.05) !important;">
                    <span class="text-muted font-weight-500 mr-2" style="font-size: 14px;">{{ __('Total Items') }}</span>
                    <span class="badge font-weight-bold" style="background: rgba(13, 110, 253, 0.15); color: #0d6efd; border-radius: 20px; padding: 6px 14px; font-size: 13px;">{{ $physicalCount }}</span>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-lg-6 mb-4">
              <a href="{{ route('user.item.create') . '?language=' . $default->code . '&type=digital' }}"
                class="d-block text-decoration-none item-type-card-link">
                <div class="card h-100 mb-0 border-0 text-center py-5 px-4" style="border-radius: 16px; background: #ffffff; border: 2px solid #10b981 !important; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.08); transition: all 0.3s ease;">
                  <div class="d-flex justify-content-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border-radius: 50%; background: rgba(16, 185, 129, 0.1);">
                      <div class="d-inline-flex align-items-center justify-content-center" style="width: 76px; height: 76px; border-radius: 50%; background: #10b981; color: #ffffff; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                        <i class="fas fa-desktop" style="font-size: 32px;"></i>
                      </div>
                    </div>
                  </div>
                  <h3 class="font-weight-bold mb-2" style="font-size: 22px; color: #212529;">{{ __('Digital Product') }}</h3>
                  <p class="text-muted mb-4 mx-auto" style="font-size: 14px; max-width: 280px;">{{ __('Products that are digital or downloadable') }}</p>
                  <div class="pt-2 border-top mx-auto d-flex align-items-center justify-content-center" style="max-width: 200px; border-color: rgba(0,0,0,0.05) !important;">
                    <span class="text-muted font-weight-500 mr-2" style="font-size: 14px;">{{ __('Total Items') }}</span>
                    <span class="badge font-weight-bold" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border-radius: 20px; padding: 6px 14px; font-size: 13px;">{{ $digitalCount }}</span>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <style>
    .item-type-card-link:hover .card {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
    }
  </style>
@endsection
