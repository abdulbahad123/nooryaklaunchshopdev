@extends('user.layout')
@section('content')
  @php
    $type = request()->input('type');
    $user = Auth::guard('web')->user();
    $permissions = [];
    if (!empty($user)) {
        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
        $permissions = json_decode($permissions, true);
    }
  @endphp
  <div class="page-header">
    <h4 class="page-title">{{ __('Shop Settings') }}</h4>
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
        <a href="#">{{ __('Shop Settings') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-0 pb-0 pt-4 px-4 bg-transparent d-flex align-items-center">
          <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 110, 253, 0.1); flex-shrink: 0;">
            <i class="fas fa-cog text-primary" style="font-size: 20px;"></i>
          </span>
          <div>
            <h4 class="card-title font-weight-bold mb-1" style="font-size: 18px;">{{ __('Shop Settings') }}</h4>
            <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Manage your store preferences and system configurations.') }}</p>
          </div>
        </div>
        <div class="card-body p-4 mt-3">
          <form id="settingsForm" action="{{ route('user.item.settings_update') }}" method="post" enctype="multipart/form-data">
            @csrf

            <!-- Catalog Mode -->
            <div class="py-4 border-bottom" style="border-color: rgba(0,0,0,0.05) !important;">
              <div class="row align-items-center">
                <div class="col-lg-5 mb-3 mb-lg-0 d-flex align-items-start">
                  <span class="d-inline-flex align-items-center justify-content-center mr-3 mt-1" style="width: 44px; height: 44px; border-radius: 50%; background: rgba(13, 110, 253, 0.1); flex-shrink: 0;">
                    <i class="fas fa-shopping-bag text-primary" style="font-size: 18px;"></i>
                  </span>
                  <div>
                    <h5 class="font-weight-bold mb-1" style="font-size: 15px; color: #212529;">{{ __('Catalog Mode') }} <span class="text-danger">*</span></h5>
                    <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Enable or disable catalog mode on your store.') }}</p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="selectgroup w-100 mb-0 shadow-sm" style="border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.1);">
                    <label class="selectgroup-item mb-0">
                      <input type="radio" name="catalog_mode" value="1" class="selectgroup-input" @if ($shopsettings) {{ $shopsettings->catalog_mode == 1 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button font-weight-bold py-2" style="border-radius: 0; border: none;">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item mb-0">
                      <input type="radio" name="catalog_mode" value="0" class="selectgroup-input" @if ($shopsettings) {{ $shopsettings->catalog_mode == 0 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button font-weight-bold py-2" style="border-radius: 0; border: none;">{{ __('Deactivate') }}</span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-12">
                  <div class="p-3 d-flex align-items-center" style="background: rgba(13, 110, 253, 0.06); border-radius: 8px; color: #0d6efd; font-size: 13px;">
                    <i class="fas fa-info-circle mr-3" style="font-size: 16px; flex-shrink: 0;"></i>
                    <span>{{ __('If you enable catalog mode, then pricing, cart, checkout option of items will be removed. But item & item details page will remain.') }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Rating System -->
            <div class="py-4 border-bottom" style="border-color: rgba(0,0,0,0.05) !important;">
              <div class="row align-items-center">
                <div class="col-lg-5 mb-3 mb-lg-0 d-flex align-items-start">
                  <span class="d-inline-flex align-items-center justify-content-center mr-3 mt-1" style="width: 44px; height: 44px; border-radius: 50%; background: rgba(111, 66, 193, 0.1); flex-shrink: 0;">
                    <i class="fas fa-star" style="color: #6f42c1; font-size: 18px;"></i>
                  </span>
                  <div>
                    <h5 class="font-weight-bold mb-1" style="font-size: 15px; color: #212529;">{{ __('Rating System') }} <span class="text-danger">*</span></h5>
                    <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Enable or disable customer rating system.') }}</p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="selectgroup w-100 mb-0 shadow-sm" style="border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.1);">
                    <label class="selectgroup-item mb-0">
                      <input type="radio" name="item_rating_system" value="1" class="selectgroup-input" @if ($shopsettings) {{ $shopsettings->item_rating_system == 1 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button font-weight-bold py-2" style="border-radius: 0; border: none;">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item mb-0">
                      <input type="radio" name="item_rating_system" value="0" class="selectgroup-input" @if ($shopsettings) {{ $shopsettings->item_rating_system == 0 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button font-weight-bold py-2" style="border-radius: 0; border: none;">{{ __('Deactivate') }}</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Disqus Comment System -->
            <div class="py-4 border-bottom" style="border-color: rgba(0,0,0,0.05) !important;">
              <div class="row align-items-center">
                <div class="col-lg-5 mb-3 mb-lg-0 d-flex align-items-start">
                  <span class="d-inline-flex align-items-center justify-content-center mr-3 mt-1" style="width: 44px; height: 44px; border-radius: 50%; background: rgba(40, 167, 69, 0.1); flex-shrink: 0;">
                    <i class="fas fa-comments text-success" style="font-size: 18px;"></i>
                  </span>
                  <div>
                    <h5 class="font-weight-bold mb-1" style="font-size: 15px; color: #212529;">{{ __('Disqus Comment System') }} <span class="text-danger">*</span></h5>
                    <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Enable or disable Disqus comment system.') }}</p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="selectgroup w-100 mb-0 shadow-sm" style="border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.1);">
                    <label class="selectgroup-item mb-0">
                      <input type="radio" name="disqus_comment_system" value="1" class="selectgroup-input" @if ($shopsettings) {{ $shopsettings->disqus_comment_system == 1 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button font-weight-bold py-2" style="border-radius: 0; border: none;">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item mb-0">
                      <input type="radio" name="disqus_comment_system" value="0" class="selectgroup-input" @if ($shopsettings) {{ $shopsettings->disqus_comment_system == 0 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button font-weight-bold py-2" style="border-radius: 0; border: none;">{{ __('Deactivate') }}</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Time Format -->
            <div class="py-4 border-bottom" style="border-color: rgba(0,0,0,0.05) !important;">
              <div class="row align-items-center">
                <div class="col-lg-5 mb-3 mb-lg-0 d-flex align-items-start">
                  <span class="d-inline-flex align-items-center justify-content-center mr-3 mt-1" style="width: 44px; height: 44px; border-radius: 50%; background: rgba(253, 126, 20, 0.1); flex-shrink: 0;">
                    <i class="fas fa-clock" style="color: #fd7e14; font-size: 18px;"></i>
                  </span>
                  <div>
                    <h5 class="font-weight-bold mb-1" style="font-size: 15px; color: #212529;">{{ __('Time Format') }} <span class="text-danger">*</span></h5>
                    <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Select time format for your store.') }}</p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <select name="time_format" class="form-control font-weight-bold shadow-sm" style="border-radius: 8px; height: 42px; border: 1px solid rgba(0,0,0,0.15);">
                    <option value="12" @selected($shopsettings->time_format == 12)>{{ __('12 Hour') }}</option>
                    <option value="24" @selected($shopsettings->time_format == 24)>{{ __('24 Hour') }}</option>
                  </select>
                  <p id="errtime_format" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>

            @if (is_array($permissions) && in_array('GST Billing', $permissions))
            <!-- Tax (%) -->
            <div class="py-4">
              <div class="row align-items-center">
                <div class="col-lg-5 mb-3 mb-lg-0 d-flex align-items-start">
                  <span class="d-inline-flex align-items-center justify-content-center mr-3 mt-1" style="width: 44px; height: 44px; border-radius: 50%; background: rgba(40, 167, 69, 0.1); flex-shrink: 0;">
                    <i class="fas fa-percent text-success" style="font-size: 18px;"></i>
                  </span>
                  <div>
                    <h5 class="font-weight-bold mb-1" style="font-size: 15px; color: #212529;">{{ __('Tax') }} (%) <span class="text-danger">*</span></h5>
                    <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Set tax percentage for your store.') }}</p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <input type="text" class="form-control font-weight-bold shadow-sm" style="border-radius: 8px; height: 42px; border: 1px solid rgba(0,0,0,0.15);" name="tax"
                    value="{{ $shopsettings ? $shopsettings->tax : '' }}" placeholder="{{ __('Enter tax percentage') }}">
                  <p id="errtax" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>
            @endif

          </form>
        </div>
        <div class="card-footer border-0 pt-0 pb-5 px-4 bg-transparent text-center">
          <button type="submit" form="settingsForm" class="btn btn-success font-weight-bold px-4 py-2 shadow-sm" style="border-radius: 8px; font-size: 14px; background: #28a745; border-color: #28a745;">
            <i class="fas fa-save mr-2"></i> {{ __('Save Changes') }}
          </button>
        </div>
      </div>
    </div>
  </div>
  <style>
    .selectgroup-input:checked + .selectgroup-button {
      background-color: #0d6efd !important;
      color: #ffffff !important;
      border-color: #0d6efd !important;
      z-index: 1 !important;
    }
  </style>
@endsection
