@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Shipping Gateways') }}</h4>
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
        <a href="#">{{ __('Shop Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Shipping Gateways') }}</a>
      </li>
    </ul>
  </div>
  
  <div class="row">
    {{-- Commented out AfterShip
    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('user.shipping_gateway.aftership_update') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('AfterShip') }}</div>
              </div>
            </div>
          </div>
          <div class="card-body">
            @php
              if ($aftership && !is_null($aftership->information)) {
                  $aftershipInfo = json_decode($aftership->information, true);
              } else {
                  $aftershipInfo = [];
              }
            @endphp
            <div class="form-group">
              <label>{{ __('Status') }}</label>
              <div class="selectgroup w-100">
                <label class="selectgroup-item">
                  <input type="radio" name="status" value="1" class="selectgroup-input"
                    {{ $aftership && $aftership->status == 1 ? 'checked' : '' }}>
                  <span class="selectgroup-button">{{ __('Active') }}</span>
                </label>
                <label class="selectgroup-item">
                  <input type="radio" name="status" value="0" class="selectgroup-input"
                    {{ !$aftership || $aftership->status == 0 ? 'checked' : '' }}>
                  <span class="selectgroup-button">{{ __('Deactive') }}</span>
                </label>
              </div>
            </div>
            <div class="form-group">
              <label>{{ __('AfterShip API Key') }}</label>
              <input class="form-control" name="api_key" value="{{ $aftershipInfo['api_key'] ?? '' }}" placeholder="Enter API Key">
              @if ($errors->has('api_key'))
                <p class="mb-0 text-danger">{{ $errors->first('api_key') }}</p>
              @endif
            </div>
          </div>
          <div class="card-footer text-center">
            <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
          </div>
        </form>
      </div>
    </div>
    --}}    {{-- Shiprocket --}}
    <div class="col-lg-7">
      <div class="card card-premium h-100">
        <form action="{{ route('user.shipping_gateway.shiprocket_update') }}" method="post" class="d-flex flex-column h-100" style="height: 100%;">
          @csrf
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="card-icon-wrap" style="background: transparent; padding: 0; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="18" cy="18" r="18" fill="url(#shiprocketGrad)"/>
                  <path d="M13 11L26 18L13 25V11Z" fill="white"/>
                  <defs>
                    <linearGradient id="shiprocketGrad" x1="0" y1="0" x2="36" y2="36" gradientUnits="userSpaceOnUse">
                      <stop stop-color="#7C3AED"/>
                      <stop offset="1" stop-color="#2563EB"/>
                    </linearGradient>
                  </defs>
                </svg>
              </div>
              <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Shiprocket') }}</div>
            </div>
            @if($shiprocket && $shiprocket->status == 1)
              <span class="badge" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 20px; font-size: 12px; padding: 4px 12px; display: inline-flex; align-items: center; gap: 4px;">
                <i class="fas fa-check" style="font-size: 10px;"></i> Active
              </span>
            @endif
          </div>
          <div class="card-body">
            @php
              if ($shiprocket && !is_null($shiprocket->information)) {
                  $shiprocketInfo = json_decode($shiprocket->information, true);
              } else {
                  $shiprocketInfo = [];
              }
            @endphp
            <div class="form-group pt-0">
              <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('Status') }}</label>
              <div class="status-toggle-wrapper">
                <input type="radio" name="status" id="status-active" value="1"
                  {{ $shiprocket && $shiprocket->status == 1 ? 'checked' : '' }}>
                <label for="status-active" class="toggle-btn toggle-active">{{ __('Active') }}</label>
                <input type="radio" name="status" id="status-deactive" value="0"
                  {{ !$shiprocket || $shiprocket->status == 0 ? 'checked' : '' }}>
                <label for="status-deactive" class="toggle-btn toggle-deactive">{{ __('Deactivate') }}</label>
              </div>
            </div>
            
            <div class="form-group">
              <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('Shiprocket Login Email') }}</label>
              <div class="input-icon-wrapper">
                <i class="far fa-envelope input-icon-prefix"></i>
                <input class="form-control" name="email" value="{{ $shiprocketInfo['email'] ?? '' }}" placeholder="Enter Email">
              </div>
              @if ($errors->has('email'))
                <p class="mb-0 text-danger" style="font-size: 12px; margin-top: 4px;">{{ $errors->first('email') }}</p>
              @endif
            </div>
            
            <div class="form-group">
              <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('Shiprocket Password') }}</label>
              <div class="input-icon-wrapper">
                <i class="fas fa-lock input-icon-prefix"></i>
                <input type="password" id="shiprocket-password" class="form-control" name="password" value="{{ $shiprocketInfo['password'] ?? '' }}" placeholder="Enter Password">
                <i class="far fa-eye input-icon-suffix" onclick="togglePasswordVisibility('shiprocket-password', this)"></i>
              </div>
              @if ($errors->has('password'))
                <p class="mb-0 text-danger" style="font-size: 12px; margin-top: 4px;">{{ $errors->first('password') }}</p>
              @endif
            </div>
            
            <div class="form-group mb-0">
              <button type="submit" class="btn w-100 btn-premium-gradient" style="background: linear-gradient(135deg, #2563eb, #7c3aed) !important; border: none !important; border-radius: 8px !important; padding: 12px !important; font-size: 14px !important; font-weight: 600 !important; color: #ffffff !important; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2) !important;">{{ __('Update') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- About Shiprocket --}}
    <div class="col-lg-5">
      <div class="card card-premium h-100">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <div class="card-icon-wrap" style="background: #faf5ff; color: #a855f7;">
              <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('About Shiprocket') }}</div>
          </div>
        </div>
        <div class="card-body">
          <p class="text-muted" style="font-size: 13px; line-height: 1.6; color: #64748b; margin-bottom: 20px;">
            {{ __('Shiprocket is a leading shipping solution that helps you automate your order fulfillment and manage shipments efficiently. Connect your Shiprocket account to streamline your shipping process.') }}
          </p>
          
          <div class="info-features-list">
            <div class="info-feature-item">
              <div class="info-feature-icon-box blue">
                <i class="fas fa-shield-alt"></i>
              </div>
              <div class="info-feature-content">
                <div class="info-feature-title">{{ __('Secure Connection') }}</div>
                <div class="info-feature-desc">{{ __('Your credentials are encrypted and stored securely.') }}</div>
              </div>
            </div>
            
            <div class="info-feature-item">
              <div class="info-feature-icon-box purple">
                <i class="fas fa-bolt"></i>
              </div>
              <div class="info-feature-content">
                <div class="info-feature-title">{{ __('Real-time Sync') }}</div>
                <div class="info-feature-desc">{{ __('Orders and tracking details sync in real-time.') }}</div>
              </div>
            </div>
            
            <div class="info-feature-item">
              <div class="info-feature-icon-box blue">
                <i class="fas fa-headset"></i>
              </div>
              <div class="info-feature-content">
                <div class="info-feature-title">{{ __('24/7 Support') }}</div>
                <div class="info-feature-desc">{{ __('Get help anytime from Shiprocket support.') }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @section('scripts')
  <script>
    function togglePasswordVisibility(inputId, iconEl) {
      var x = document.getElementById(inputId);
      if (x.type === "password") {
        x.type = "text";
        iconEl.classList.remove('fa-eye');
        iconEl.classList.add('fa-eye-slash');
      } else {
        x.type = "password";
        iconEl.classList.remove('fa-eye-slash');
        iconEl.classList.add('fa-eye');
      }
    }
  </script>
  @endsection
@endsection
