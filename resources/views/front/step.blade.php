@extends('front.layout')

@section('pagename')
  - {{ __($package->title) }}
@endsection

@section('meta-description', !empty($package) ? $package->meta_keywords : '')
@section('meta-keywords', !empty($package) ? $package->meta_description : '')

@section('styles')
<style>
  :root {
    --primary-color: var(--color-primary, #ff5a2c);
  }

  /* Hide default breadcrumb area for a focused signup page */
  .page-title-area {
    display: none !important;
  }

  /* Custom split-screen styles */
  .signup-split-area {
    background: #f8fafc;
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 60px 0;
  }
  
  .signup-split-wrapper {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    border: 1px solid #eef2f6;
    width: 100%;
  }

  .signup-illustration-wrapper {
    height: 100%;
    min-height: 620px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    position: relative;
    overflow: hidden;
  }

  .signup-illustration {
    width: auto;
    height: auto;
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    object-position: center;
  }

  .signup-form-card {
    padding: 50px;
    border: none !important;
    box-shadow: none !important;
    margin: 0 !important;
    max-width: 100% !important;
  }

  .signup-form-card .title h3 {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 6px;
  }

  .signup-form-card .title p {
    font-size: 15px;
    color: #64748b;
  }

  /* Login Link styling next to Signup */
  .btn-login-link {
    background: #ffffff;
    border: 1.5px solid var(--primary-color, #ff5a2c);
    color: var(--primary-color, #ff5a2c) !important;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 4px rgba(255, 90, 44, 0.04);
  }

  .btn-login-link:hover {
    background: var(--primary-color, #ff5a2c);
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(255, 90, 44, 0.15);
    transform: translateY(-1px);
  }

  .btn-login-link:active {
    transform: translateY(0);
  }

  .btn-login-link i {
    font-size: 14px;
  }

  @media (max-width: 767px) {
    .signup-split-area {
      padding-top: 90px !important;
      padding-bottom: 40px !important;
      background: #ffffff !important;
    }
    .signup-split-area .container {
      padding-left: 0 !important;
      padding-right: 0 !important;
      max-width: 100% !important;
    }
    .signup-split-wrapper {
      border-radius: 0 !important;
      border: none !important;
      box-shadow: none !important;
      margin: 0 !important;
    }
    .signup-form-card {
      padding: 30px 20px !important;
    }
    .signup-form-card .title {
      flex-direction: column;
      align-items: flex-start !important;
      gap: 15px;
    }
    .signup-form-card .title > div:last-child {
      align-self: flex-start;
    }
  }

  /* Plan Switcher Styling */
  .selected-plan-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    transition: all 0.3s ease;
  }

  .selected-plan-card:hover {
    border-color: #cbd5e1;
  }

  .plan-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #94a3b8;
    display: block;
    margin-bottom: 4px;
  }

  .plan-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 2px 0;
  }

  .plan-price-term {
    font-size: 14px;
    color: #64748b;
    margin: 0;
  }

  .btn-change-plan {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .btn-change-plan:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #1e293b;
  }

  .plan-picker-panel {
    border-top: 1px solid #e2e8f0;
    padding-top: 16px;
    animation: slideDown 0.3s ease-out;
  }

  @keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .plan-options-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-height: 250px;
    overflow-y: auto;
    padding-right: 4px;
    margin-top: 8px;
  }

  .plan-option-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .plan-option-item:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
  }

  .plan-option-item.active {
    border-color: var(--primary-color, #6c63ff);
    background: rgba(108, 99, 255, 0.03);
    box-shadow: 0 0 0 1px var(--primary-color, #6c63ff);
  }

  .plan-opt-name {
    font-weight: 700;
    font-size: 14px;
    color: #1e293b;
  }

  .plan-opt-price {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
  }

  /* Term Badge Styles */
  .term-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 6px;
  }
  .badge-monthly,
  .badge-month {
    background-color: #ffe8e2;
    color: #ff5a2c;
  }
  .badge-yearly,
  .badge-year {
    background-color: #ecfdf5;
    color: #10b981;
  }
  .badge-lifetime {
    background-color: #f5f3ff;
    color: #7c3aed;
  }

  /* Segmented Radio Switches for Free Trial / Purchase */
  .plan-opt-type-toggle {
    display: inline-flex !important;
    background: #f1f5f9;
    border-radius: 8px;
    padding: 3px;
    gap: 0 !important;
    border: none !important;
    margin-top: 8px;
  }

  .toggle-btn-label {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    margin: 0 !important;
    padding: 0 !important;
  }

  .toggle-btn-label input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    display: none !important;
  }

  .toggle-btn-label span {
    display: inline-block;
    padding: 6px 14px;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    border-radius: 6px;
    transition: all 0.2s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .toggle-btn-label input[type="radio"]:checked + span {
    background: #ffffff;
    color: #ff5a2c;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
  }

  .price-amount {
    font-size: 18px;
    font-weight: 800;
    color: #ff5a2c;
  }

  /* Form control improvements */
  .signup-form-card .form-control {
    height: 50px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    padding: 10px 18px;
    font-size: 15px;
    transition: all 0.2s ease;
    background-color: #ffffff;
  }

  .signup-form-card .form-control:focus {
    border-color: var(--primary-color, #6c63ff);
    box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
    color: #1f2937;
  }

  /* Small helpers */
  #subdomain-helper-wrapper {
    background: rgba(108, 99, 255, 0.04);
    border-left: 3px solid var(--primary-color, #6c63ff);
    padding: 8px 12px;
    border-radius: 0 6px 6px 0;
  }

  /* intl-tel-input customizations */
  .iti {
    display: block !important;
    width: 100%;
  }
  .iti__country-list {
    text-align: left;
  }
  .iti--separate-dial-code .iti__selected-dial-code {
    font-size: 14px;
    color: #475569;
    font-weight: 600;
  }

  /* WhatsApp OTP Sent Badge */
  .whatsapp-badge-wrapper {
    display: flex;
    align-items: center;
    gap: 16px;
    background: #f0fdf4;
    border: 2px dashed #22c55e;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.05);
    transition: all 0.3s ease;
    text-align: left;
  }
  
  .whatsapp-badge-logo {
    flex-shrink: 0;
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .whatsapp-badge-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
  }

  .whatsapp-badge-content {
    text-align: left;
  }

  .whatsapp-badge-content .badge-desc {
    font-size: 15px;
    color: #166534;
    margin: 0;
    font-weight: 700;
    line-height: 1.5;
    text-align: left;
    padding: 4px 8px;
    border-radius: 6px;
    display: inline-block;
  }

  @media (max-width: 575.98px) {
    .whatsapp-badge-wrapper {
      padding: 12px;
      gap: 12px;
    }
    .whatsapp-badge-logo {
      width: 56px;
      height: 56px;
      padding: 4px;
    }
    .whatsapp-badge-content .badge-desc {
      font-size: 13px;
      padding: 3px 6px;
    }
  }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
@endsection

@section('content')
  <!--====== Start user-form-section ======-->
  <div class="signup-split-area">
    <div class="container">
      <div class="signup-split-wrapper">
        <div class="row no-gutters align-items-stretch">
          
          <!-- Left side image/illustration -->
          <div class="col-lg-6 d-none d-lg-block">
            <div class="signup-illustration-wrapper">
              <img src="{{ asset('images/signup.gif') }}" alt="{{ __('Signup Illustration') }}" class="signup-illustration">
            </div>
          </div>
          
          <!-- Right side form -->
          <div class="col-lg-6 col-md-12">
            <div class="main-form signup-form-card">
              <form id="authForm" action="{{ route('front.checkout.view') }}" method="post" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                  <div class="alert alert-danger mb-20" style="border-radius: 10px; text-align: left; font-size: 14px; font-weight: 600; background-color: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.15); color: #ef4444; padding: 15px;">
                    <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <!-- Screen 1: Mobile Verification -->
                <div id="screen-1" class="{{ session('phone_verified') ? 'd-none' : '' }}">
                  <div class="title mb-25 d-flex justify-content-between align-items-center">
                    <div>
                      <h3 class="mb-0">{{ __('Create an account !') }}</h3>
                      <p class="mb-0 mt-1">{{ __('Register to continue to') }} {{ $bs->website_title }}.</p>
                    </div>
                    <div>
                      <a href="{{ route('user.login') }}" class="btn-login-link">
                        <i class="fal fa-sign-in-alt"></i> {{ __('Login') }}
                      </a>
                    </div>
                  </div>

                  <!-- Name Input -->
                  <div class="form-group mb-20">
                    <label class="form-label font-weight-bold small mb-2" style="color: #475569; display: block; text-align: left;">
                      {{ __('Name') }} *
                    </label>
                    <div style="position: relative;">
                      <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 15px;"><i class="fal fa-user"></i></span>
                      <input class="form-control" type="text" name="first_name" id="first_name" placeholder="{{ __('Enter your name') }}" value="{{ old('first_name', session('otp_name')) }}" required style="padding-left: 46px !important;">
                    </div>
                    @error('first_name')
                      <p class="text-danger small mb-1 mt-1">{{ $message }}</p>
                    @enderror
                  </div>

                  <!-- Country Code + Phone Input -->
                  <div class="form-group mb-20 text-start" style="text-align: left;">
                    <label class="form-label font-weight-bold small mb-2" style="color: #475569; display: block; text-align: left;">
                      {{ __('Phone Number') }} *
                    </label>
                    <div style="text-align: left;">
                      <input type="hidden" name="country_code" id="country_code" value="{{ old('country_code', session('otp_country_code', '+91')) }}">
                      <input class="form-control" type="tel" name="phone" id="phone_number" value="{{ old('phone', session('phone_verified') ? session('verified_phone') : session('otp_phone')) }}"
                        placeholder="81234 56789" required style="width: 100%; text-align: left;" inputmode="numeric">
                      @error('phone')
                        <p class="text-danger small mb-1 mt-1">{{ $message }}</p>
                      @enderror
                    </div>
                    <div id="phone-feedback" class="small mt-2" style="font-weight: 600; text-align: left;"></div>
                  </div>

                  <!-- Email Input -->
                  <div class="form-group mb-20">
                    <label class="form-label font-weight-bold small mb-2" style="color: #475569; display: block; text-align: left;">
                      {{ __('Email Address') }} *
                    </label>
                    <div style="position: relative;">
                      <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 15px;"><i class="fal fa-envelope"></i></span>
                      <input class="form-control" type="email" name="email" id="email" placeholder="{{ __('Email Address') }}" value="{{ old('email', session('phone_verified') ? session('verified_email') : session('otp_email')) }}" required style="padding-left: 46px !important;">
                    </div>
                    @error('email')
                      <p class="text-danger small mb-1 mt-1">{{ $message }}</p>
                    @enderror
                  </div>

                  <!-- Get OTP Button -->
                  <button type="button" class="btn primary-btn w-100 py-3 mb-20" id="btn-send-otp" style="font-size: 16px; font-weight: 600; border-radius: 8px;">
                    {{ __('Get OTP') }}
                  </button>

                  <!-- WhatsApp OTP Sent Badge -->
                  <div id="otp-whatsapp-badge" class="d-none mb-20">
                    <div class="whatsapp-badge-wrapper">
                      <div class="whatsapp-badge-logo">
                        <img src="{{ asset('images/Logo.png') }}" alt="Logo">
                      </div>
                      <div class="whatsapp-badge-content">
                        <p class="badge-desc">{{ __('Check Your Whatsapp For Launchshop Verification | Otp Recieve From Nooryak Technologies.') }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- OTP Input Field (hidden initially) -->
                  <div class="form-group mb-20 d-none" id="otp-group">
                    <label class="form-label font-weight-bold small mb-2" style="color: #475569; display: block; text-align: left;">
                      {{ __('Enter OTP sent to your Whatsapp Number') }} *
                    </label>
                    <div class="row g-2 align-items-center">
                      <div class="col-8 col-sm-9">
                        <input class="form-control" type="text" id="otp_code" placeholder="Enter 6-digit OTP" maxlength="6" inputmode="numeric">
                      </div>
                      <div class="col-4 col-sm-3">
                        <button type="button" class="btn w-100 py-2" id="btn-verify-otp" style="height: 50px !important; border-radius: 8px; font-weight: 600; font-size: 14px; background-color: var(--primary-color, #ff5a2c); border-color: var(--primary-color, #ff5a2c); color: #fff;">
                          {{ __('Submit') }}
                        </button>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                      <span id="otp-timer" class="small text-muted" style="font-weight: 600;"></span>
                      <button type="button" class="btn btn-link p-0 small d-none" id="btn-resend-otp" style="color: var(--primary-color, #ff5a2c); font-weight: 600; text-decoration: none;">
                        {{ __('Resend OTP') }}
                      </button>
                    </div>
                    <div id="otp-feedback" class="small mt-2" style="font-weight: 600; text-align: left;"></div>
                  </div>
                </div>

                <!-- Screen 2: Shop Details & Plan Selection -->
                <div id="screen-2" class="{{ session('phone_verified') ? '' : 'd-none' }}">
                  <div class="title mb-25">
                    <h3 class="mb-0">{{ __('Complete Signup') }}</h3>
                    <p class="mb-0 mt-1{{ session('phone_verified') ? ' d-none' : '' }}">{{ __('Provide your shop and account details') }}</p>
                  </div>

                  <!-- Verified Summary alert -->
                  <div class="alert alert-success d-flex justify-content-between align-items-center p-3 mb-20" style="border-radius: 10px; background-color: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.15); margin-bottom: 20px;">
                    <div style="text-align: left;">
                      <p class="mb-0 small text-muted font-weight-bold" style="font-size: 10px; text-transform: uppercase;">{{ __('VERIFIED CONTACT') }}</p>
                      <div class="d-flex align-items-center" style="gap: 8px; margin-top: 2px; flex-wrap: wrap;">
                        <h6 class="mb-0 font-weight-bold text-dark" id="summary-verified-info" style="font-size: 14px;">
                          {{ session('otp_name') }} ({{ session('otp_country_code') }} {{ session('verified_phone') }})
                        </h6>
                        <span class="d-inline-flex align-items-center" style="color: #10b981; font-weight: 700; font-size: 13px; gap: 4px;">
                          <i class="fas fa-check-circle" style="font-size: 14px; color: #10b981;"></i>
                          {{ __('Verified') }}
                        </span>
                      </div>
                    </div>
                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none font-weight-bold" id="btn-edit-contact" style="color: var(--primary-color, #ff5a2c);">
                      {{ __('Edit') }}
                    </button>
                  </div>

                  <!-- Plan Switcher Card -->
                  <div class="selected-plan-card mb-15">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <span class="plan-label">{{ __('Selected Plan') }}</span>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                          <h4 class="plan-title" id="display-plan-title" style="margin: 0;">{{ __($package->title) }}</h4>
                          <span id="display-plan-term-badge" class="term-badge badge-{{ strtolower($package->term) }}">{{ __($package->term) }}</span>
                        </div>
                        <div class="plan-price-display mt-2" id="display-plan-price-wrap">
                          @if($status == 'trial')
                            <span class="price-amount" style="font-size: 24px; font-weight: 800; color: #ff5a2c;">{{ __('Free Trial') }}</span>
                            <span class="price-term text-muted" style="font-size: 14px; font-weight: 500;">({{ $package->trial_days }} {{ __('days') }})</span>
                          @else
                            <span class="price-amount" style="font-size: 24px; font-weight: 800; color: #ff5a2c;">{{ $package->price == 0 ? __('Free') : format_price($package->price) }}</span>
                            <span class="price-term text-muted" style="font-size: 14px; font-weight: 500;">/ {{ __($package->term) }}</span>
                          @endif
                        </div>
                      </div>
                      <button type="button" class="btn-change-plan btn-sm" id="btn-toggle-plans">
                        {{ __('Change Plan') }}
                      </button>
                    </div>
                    
                    <!-- Expanded Plan Picker Panel -->
                    <div class="plan-picker-panel mt-15 d-none" id="plan-picker-panel">
                      <label class="form-label small font-weight-bold mb-2 text-secondary">{{ __('Choose Another Plan') }}</label>
                      <div class="plan-options-list">
                        @foreach($packages as $pkg)
                          @php
                            $features = !empty($pkg->features) ? json_decode($pkg->features, true) : [];
                            $pkgHasSubdomain = is_array($features) && in_array('Subdomain', $features);
                          @endphp
                          <div class="plan-option-item {{ $pkg->id == $package->id ? 'active' : '' }}" 
                               data-id="{{ $pkg->id }}"
                               data-title="{{ $pkg->title }}"
                               data-price-val="{{ $pkg->price }}"
                               data-term="{{ __($pkg->term) }}"
                               data-is-trial="{{ $pkg->is_trial }}"
                               data-trial-days="{{ $pkg->trial_days }}"
                               data-price-formatted="{{ format_price($pkg->price) }}"
                               data-trial-price-formatted="{{ __('Free Trial') }} ({{ $pkg->trial_days }} {{ __('days') }})"
                               data-has-subdomain="{{ $pkgHasSubdomain ? 'true' : 'false' }}">
                            <div class="plan-option-header d-flex justify-content-between align-items-center">
                              <div class="d-flex align-items-center" style="gap: 8px;">
                                <span class="plan-opt-name">{{ $pkg->title }}</span>
                                <span class="term-badge badge-{{ strtolower($pkg->term) }}">{{ __($pkg->term) }}</span>
                              </div>
                              <span class="plan-opt-price">
                                <span class="price-amount" style="font-size: 16px;">{{ $pkg->price == 0 ? __('Free') : format_price($pkg->price) }}</span>
                              </span>
                            </div>
                            
                            <!-- Switch for Trial vs Regular if trial is supported -->
                            @if($pkg->is_trial == 1 && $pkg->price > 0)
                              <div class="plan-opt-type-toggle mt-2 d-flex justify-content-end" style="gap: 12px;">
                                <label class="toggle-btn-label">
                                  <input type="radio" name="plan_type_opt_{{ $pkg->id }}" value="trial" {{ ($pkg->id == $package->id && $status == 'trial') ? 'checked' : '' }}>
                                  <span>{{ __('Free Trial') }}</span>
                                </label>
                                <label class="toggle-btn-label">
                                  <input type="radio" name="plan_type_opt_{{ $pkg->id }}" value="regular" {{ ($pkg->id == $package->id && $status == 'regular') ? 'checked' : '' }} {{ ($pkg->id != $package->id) ? 'checked' : '' }}>
                                  <span>{{ __('Purchase') }}</span>
                                </label>
                              </div>
                            @endif
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  <br>

                  <!-- Template Switcher Card -->
                  <div class="selected-plan-card mb-20" id="selected-template-card">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center" style="gap: 12px;">
                        <!-- Image Thumbnail wrapper -->
                        <img src="{{ !empty($selectedTemplateImg) ? asset('assets/front/img/template-previews/' . $selectedTemplateImg) : '' }}" 
                             alt="{{ $selectedTemplateName }}" 
                             class="selected-template-thumb"
                             id="display-template-img"
                             style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0; {{ empty($selectedTemplateImg) ? 'display: none;' : '' }}">
                        
                        <!-- Fallback storefront icon wrapper -->
                        <div class="selected-template-icon-wrap" 
                             id="display-template-icon"
                             style="width: 48px; height: 48px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #475569; border: 1px solid #e2e8f0; {{ !empty($selectedTemplateImg) ? 'display: none;' : '' }}">
                          <i class="fal fa-store" style="font-size: 20px;"></i>
                        </div>
                        
                        <div>
                          <span class="plan-label">{{ __('Selected Template') }}</span>
                          <h4 class="plan-title" id="display-template-name" style="font-size: 16px;">{{ $selectedTemplateName }}</h4>
                        </div>
                      </div>
                      <button type="button" class="btn-change-plan btn-sm" id="btn-toggle-templates">
                        {{ __('Change') }}
                      </button>
                    </div>

                    <!-- Expanded Template Picker Panel -->
                    <div class="plan-picker-panel mt-15 d-none" id="template-picker-panel">
                      <label class="form-label small font-weight-bold mb-2 text-secondary">{{ __('Choose Another Template') }}</label>
                      <div class="plan-options-list">
                        @foreach($templates as $tpl)
                          <div class="plan-option-item template-option-item {{ $tpl->username == $selected_template ? 'active' : '' }}" 
                               data-username="{{ $tpl->username }}"
                               data-name="{{ $tpl->display_name }}"
                               data-img="{{ !empty($tpl->template_img) ? asset('assets/front/img/template-previews/' . $tpl->template_img) : '' }}">
                            <div class="plan-option-header d-flex justify-content-between align-items-center">
                              <span class="plan-opt-name">{{ $tpl->display_name }}</span>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>

                  <!-- Subdomain Input Group -->
                  <div class="form-group mb-20">
                    <label class="form-label font-weight-bold small mb-2" style="color: #475569; display: block; text-align: left;">
                      <span id="subdomain-label-text">{{ $hasSubdomain ? __('Create Your Subdomain') : __('Username') }}</span> *
                    </label>
                    <div class="subdomain-input-group" id="subdomain-input-group" style="border: 1px solid #cbd5e1; box-shadow: none;">
                      <span class="domain-prefix" id="subdomain-prefix" style="padding: 12px 10px 12px 15px; font-size: 14px; background: #f8fafc; border-right: 1px solid #cbd5e1; color: #94a3b8; font-weight: 500; display: {{ $hasSubdomain ? 'flex' : 'none' }}; align-items: center;">https://</span>
                      <input type="text" class="form-control" name="username" placeholder="{{ $hasSubdomain ? __('mystore') : __('Username') }}"
                        value="{{ old('username') }}" required autocomplete="off" style="border: none !important; height: auto !important; padding: 12px 15px !important; font-size: 14px !important; flex-grow: 1; outline: none !important; box-shadow: none !important;">
                      <span class="domain-ext" id="subdomain-ext" style="padding: 12px 15px; font-size: 14px; color: #475569; font-weight: 700; background: #f8fafc; border-left: 1px solid #cbd5e1; display: {{ $hasSubdomain ? 'flex' : 'none' }}; align-items: center;">.{{ env('WEBSITE_HOST') }}</span>
                    </div>
                    <div id="usernameAvailable" class="small mt-2" style="font-weight: 600; text-align: left;"></div>
                    @error('username')
                      <p class="text-danger small mb-0 mt-1" style="text-align: left;">{{ $message }}</p>
                    @enderror
                  </div>


                  <!-- Password Input -->
                  <div class="form-group mb-20">
                    <div style="position: relative;">
                      <input class="form-control" id="password" type="password" name="password"
                        placeholder="{{ __('Password') }}" required style="padding-right: 44px;">
                      <button type="button" class="btn-pwd-toggle" onclick="togglePasswordVisibility('password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px; font-size: 16px; transition: color 0.2s;" onmouseover="this.style.color='#ff5a2c'" onmouseout="this.style.color='#94a3b8'">
                        <i class="fal fa-eye" id="password-eye-icon"></i>
                      </button>
                    </div>
                    @error('password')
                      <p class="text-danger small mb-1 mt-1">{{ $message }}</p>
                    @enderror
                  </div>

                  <!-- Confirm Password Input -->
                  <div class="form-group mb-25">
                    <div style="position: relative;">
                      <input class="form-control" id="password-confirm" type="password"
                        placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required
                        autocomplete="new-password" style="padding-right: 44px;">
                      <button type="button" class="btn-pwd-toggle" onclick="togglePasswordVisibility('password-confirm')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px; font-size: 16px; transition: color 0.2s;" onmouseover="this.style.color='#ff5a2c'" onmouseout="this.style.color='#94a3b8'">
                        <i class="fal fa-eye" id="password-confirm-eye-icon"></i>
                      </button>
                    </div>
                    @error('password')
                      <p class="text-danger small mb-1 mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                  <br>

                  <!-- Hidden Values -->
                  <div>
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="selected_template" value="{{ $selected_template ?? '' }}">
                  </div>

                  <button type="submit" class="btn primary-btn w-100 py-3" style="font-size: 16px; font-weight: 600; border-radius: 8px;"> {{ __('Continue') }} </button>
                </div>
              </form>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
  <!--====== End user-form-section ======-->

@php
  $uniqueToken = __('This username is already taken') . '.';
@endphp
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
  <script>
    'use strict';
    let isPhoneVerified = {!! session('phone_verified') ? 'true' : 'false' !!};
    $(document).ready(function() {
      // Clean leading zeros on load before intl-tel-input initialization
      let initialPhoneVal = $('#phone_number').val();
      if (initialPhoneVal) {
        $('#phone_number').val(initialPhoneVal.trim().replace(/^0+/, ''));
      }

      // Initialize intl-tel-input
      const phoneInput = document.querySelector("#phone_number");
      const countryCodeInput = document.querySelector("#country_code");

      // Strip leading zeros in real-time on typing, pasting, or autofill
      $('#phone_number').on('input change blur keyup', function() {
        let val = $(this).val();
        let cleanVal = val.replace(/^0+/, '');
        if (val !== cleanVal) {
          $(this).val(cleanVal);
          // Dispatch native events for intl-tel-input to pick up
          this.dispatchEvent(new Event('input'));
          this.dispatchEvent(new Event('change'));
        }
      });
      
      const iti = window.intlTelInput(phoneInput, {
        initialCountry: "in",
        separateDialCode: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js"
      });

      // Remove required attribute if phone is already verified to prevent hidden form control validation errors
      if (isPhoneVerified) {
        $('#first_name').removeAttr('required').prop('readonly', true);
        $('#phone_number').removeAttr('required').prop('readonly', true);
        $('#country_code').prop('readonly', true);
        $('#email').removeAttr('required').prop('readonly', true);
      }

      // Update hidden country_code input on country change
      phoneInput.addEventListener("countrychange", function() {
        const countryData = iti.getSelectedCountryData();
        countryCodeInput.value = "+" + countryData.dialCode;
      });

      // Initialize value on load / fallback
      setTimeout(function() {
        // Strip leading zeros in case browser autofilled it after ready
        let currentPhoneVal = $('#phone_number').val();
        if (currentPhoneVal) {
          let cleanPhoneVal = currentPhoneVal.replace(/^0+/, '');
          if (currentPhoneVal !== cleanPhoneVal) {
            $('#phone_number').val(cleanPhoneVal);
            phoneInput.dispatchEvent(new Event('input'));
            phoneInput.dispatchEvent(new Event('change'));
          }
        }

        let oldCode = countryCodeInput.value;
        if (oldCode) {
          let dialCode = oldCode.replace('+', '');
          let countryDataList = (window.intlTelInputGlobals && typeof window.intlTelInputGlobals.getCountryData === 'function') ? window.intlTelInputGlobals.getCountryData() : [];
          let matchedCountry = countryDataList.find(c => c.dialCode === dialCode);
          if (matchedCountry) {
            iti.setCountry(matchedCountry.iso2);
          }
        }
        const countryData = iti.getSelectedCountryData();
        if (countryData) {
          countryCodeInput.value = "+" + countryData.dialCode;
        }
      }, 500);

      // Toggle plans picker panel
      $('#btn-toggle-plans').on('click', function(e) {
        e.preventDefault();
        $('#plan-picker-panel').toggleClass('d-none');
        let isHidden = $('#plan-picker-panel').hasClass('d-none');
        $(this).text(isHidden ? '{{ __("Change Plan") }}' : '{{ __("Close") }}');
      });

      // Toggle templates picker panel
      $('#btn-toggle-templates').on('click', function(e) {
        e.preventDefault();
        $('#template-picker-panel').toggleClass('d-none');
        let isHidden = $('#template-picker-panel').hasClass('d-none');
        $(this).text(isHidden ? '{{ __("Change") }}' : '{{ __("Close") }}');
      });

      // Handle plan selection click
      $('.plan-options-list').on('click', '.plan-option-item', function(e) {
        // If user clicked inside the toggle inputs, let that be handled by radio listener
        if ($(e.target).is('input, label, span') || $(e.target).parents('.plan-opt-type-toggle').length > 0 || $(this).hasClass('template-option-item')) {
          return;
        }
        selectPlan($(this));
      });

      // Handle radio toggle change for Trial vs Regular
      $('.plan-options-list').on('change', '.plan-option-item input[type="radio"]', function() {
        let item = $(this).closest('.plan-option-item');
        selectPlan(item);
      });

      // Handle template selection click
      $('.plan-options-list').on('click', '.template-option-item', function(e) {
        $('.template-option-item').removeClass('active');
        $(this).addClass('active');

        let username = $(this).data('username');
        let name = $(this).data('name');
        let img = $(this).data('img');

        // Update hidden field
        $('input[name="selected_template"]').val(username);

        // Update display text
        $('#display-template-name').text(name);

        // Update visual thumbnail
        if (img) {
          $('#display-template-img').attr('src', img).show();
          $('#display-template-icon').hide();
        } else {
          $('#display-template-img').hide();
          $('#display-template-icon').show();
        }

        // Close the panel
        $('#template-picker-panel').addClass('d-none');
        $('#btn-toggle-templates').text('{{ __("Change") }}');
      });

      function selectPlan(item) {
        $('.plan-option-item').removeClass('active');
        item.addClass('active');

        let pkgId = item.data('id');
        let title = item.data('title');
        let priceVal = parseFloat(item.data('price-val'));
        let term = item.data('term');
        let isTrial = item.data('is-trial') == 1;
        let priceFormatted = item.data('price-formatted');
        let trialPriceFormatted = item.data('trial-price-formatted');
        let hasSubdomain = item.data('has-subdomain') === true || item.data('has-subdomain') === 'true';

        // Determine type: trial or regular
        let type = 'regular';
        if (isTrial && priceVal > 0) {
          let selectedRadio = item.find('input[type="radio"]:checked').val();
          if (selectedRadio) {
            type = selectedRadio;
          } else {
            type = 'trial'; // default fallback
          }
        } else if (priceVal === 0) {
          type = 'regular';
        }

        // Update hidden fields
        $('input[name="id"]').val(pkgId);
        $('input[name="status"]').val(type);

        // Update display card details
        $('#display-plan-title').text(title);
        
        let termClass = term.toLowerCase().replace(/[^a-z]/g, '');
        $('#display-plan-term-badge')
            .removeClass('badge-monthly badge-month badge-yearly badge-year badge-lifetime')
            .addClass('badge-' + termClass)
            .text(term);

        if (type === 'trial') {
          let trialDays = item.data('trial-days');
          $('#display-plan-price-wrap').html(
            '<span class="price-amount" style="font-size: 24px; font-weight: 800; color: #ff5a2c;">' + '{{ __("Free Trial") }}' + '</span>' +
            '<span class="price-term text-muted" style="font-size: 14px; font-weight: 500;"> (' + trialDays + ' ' + '{{ __("days") }}' + ')</span>'
          );
        } else {
          $('#display-plan-price-wrap').html(
            '<span class="price-amount" style="font-size: 24px; font-weight: 800; color: #ff5a2c;">' + priceFormatted + '</span>' +
            '<span class="price-term text-muted" style="font-size: 14px; font-weight: 500;"> / ' + term + '</span>'
          );
        }

        // Update subdomain helper visibility
        if (hasSubdomain) {
          $('#subdomain-prefix').css('display', 'flex');
          $('#subdomain-ext').css('display', 'flex');
          $('#subdomain-label-text').text('{{ __("Create Your Subdomain") }}');
          $('input[name="username"]').attr('placeholder', '{{ __("mystore") }}');
        } else {
          $('#subdomain-prefix').css('display', 'none');
          $('#subdomain-ext').css('display', 'none');
          $('#subdomain-label-text').text('{{ __("Username") }}');
          $('input[name="username"]').attr('placeholder', '{{ __("Username") }}');
        }
      }

      // Real-time username availability check with debounce
      let checkTimeout;
      $("input[name='username']").on('input', function() {
        let username = $(this).val();
        
        // Remove spaces and special characters for subdomains
        username = username.toLowerCase().replace(/[^a-z0-9]/g, '');
        $(this).val(username);

        clearTimeout(checkTimeout);

        if (username.length > 0) {
          $("#usernameAvailable").html('<span style="color: #64748b;"><i class="fas fa-spinner fa-spin"></i> Checking availability...</span>');
          
          checkTimeout = setTimeout(function() {
            $.get("{{ url('/') }}/check/" + username + '/username', function(data) {
              if (data == true) {
                $("#usernameAvailable").html('<span class="text-danger"><i class="fas fa-times-circle"></i> {{ __("This username is already taken") }}.</span>');
              } else {
                $("#usernameAvailable").html('<span class="text-success"><i class="fas fa-check-circle"></i> {{ __("Username is available!") }}</span>');
              }
            });
          }, 300); // 300ms debounce
        } else {
          $("#usernameAvailable").html('');
        }
      });

      // Phone OTP Verification Logic
      let countdownSeconds = 120;
      let otpTimer = null;

      function startOtpTimer() {
        clearInterval(otpTimer);
        countdownSeconds = 120;
        $('#otp-timer').removeClass('d-none').text('{{ __("Resend OTP in") }} ' + countdownSeconds + 's');
        $('#btn-resend-otp').addClass('d-none');
        
        otpTimer = setInterval(function() {
          countdownSeconds--;
          if (countdownSeconds <= 0) {
            clearInterval(otpTimer);
            $('#otp-timer').addClass('d-none');
            $('#btn-resend-otp').removeClass('d-none');
          } else {
            $('#otp-timer').text('{{ __("Resend OTP in") }} ' + countdownSeconds + 's');
          }
        }, 1000);
      }

      $('#btn-send-otp').on('click', function(e) {
        e.preventDefault();
        let nameVal = $('#first_name').val().trim();
        let phoneVal = $('#phone_number').val().trim();
        let countryCode = $('#country_code').val().trim();
        let emailVal = $('#email').val().trim();

        if (!nameVal) {
          $('#phone-feedback').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ __("Please enter your name first.") }}</span>');
          $('#first_name').addClass('is-invalid');
          return;
        }
        $('#first_name').removeClass('is-invalid');

        if (!phoneVal) {
          $('#phone-feedback').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ __("Please enter a valid phone number.") }}</span>');
          $('#phone_number').addClass('is-invalid');
          return;
        }
        $('#phone_number').removeClass('is-invalid');

        if (!emailVal) {
          $('#phone-feedback').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ __("Please enter your email address first.") }}</span>');
          $('#email').addClass('is-invalid');
          return;
        }
        let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (!emailReg.test(emailVal)) {
          $('#phone-feedback').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ __("Please enter a valid email address.") }}</span>');
          $('#email').addClass('is-invalid');
          return;
        }
        $('#email').removeClass('is-invalid');

        $('#phone-feedback').html('<span class="text-info"><i class="fas fa-spinner fa-spin"></i> {{ __("Sending OTP...") }}</span>');
        let $btn = $(this);
        $btn.prop('disabled', true);

        $.post("{{ route('front.otp.send') }}", {
          _token: "{{ csrf_token() }}",
          phone_number: phoneVal,
          country_code: countryCode,
          name: nameVal,
          email: emailVal
        }, function(response) {
          if (response.success) {
            $('#phone-feedback').html('<span class="text-success"><i class="fas fa-check-circle"></i> ' + response.message + '</span>');
            $('#otp-whatsapp-badge').removeClass('d-none');
            $('#otp-group').removeClass('d-none');
            $btn.text('{{ __("Sent") }}');
            startOtpTimer();
          } else {
            $('#phone-feedback').html('<span class="text-danger"><i class="fas fa-times-circle"></i> ' + response.message + '</span>');
            $btn.prop('disabled', false);
          }
        }).fail(function(xhr) {
          let msg = '{{ __("Failed to send OTP. Please try again.") }}';
          if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
              let firstKey = Object.keys(xhr.responseJSON.errors)[0];
              msg = xhr.responseJSON.errors[firstKey][0];
            } else if (xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
          }
          $('#phone-feedback').html('<span class="text-danger"><i class="fas fa-times-circle"></i> ' + msg + '</span>');
          $btn.prop('disabled', false);
        });
      });

      $('#btn-resend-otp').on('click', function(e) {
        e.preventDefault();
        let phoneVal = $('#phone_number').val().trim();
        let countryCode = $('#country_code').val().trim();
        let emailVal = $('#email').val().trim();

        $('#otp-feedback').html('');
        $('#phone-feedback').html('<span class="text-info"><i class="fas fa-spinner fa-spin"></i> {{ __("Resending OTP...") }}</span>');
        let $btn = $(this);
        $btn.addClass('d-none');

        $.post("{{ route('front.otp.send') }}", {
          _token: "{{ csrf_token() }}",
          phone_number: phoneVal,
          country_code: countryCode,
          name: $('#first_name').val().trim(),
          email: emailVal
        }, function(response) {
          if (response.success) {
            $('#phone-feedback').html('<span class="text-success"><i class="fas fa-check-circle"></i> ' + response.message + '</span>');
            $('#otp-whatsapp-badge').removeClass('d-none');
            startOtpTimer();
          } else {
            $('#phone-feedback').html('<span class="text-danger"><i class="fas fa-times-circle"></i> ' + response.message + '</span>');
            $btn.removeClass('d-none');
          }
        }).fail(function(xhr) {
          let msg = '{{ __("Failed to resend OTP.") }}';
          if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
              let firstKey = Object.keys(xhr.responseJSON.errors)[0];
              msg = xhr.responseJSON.errors[firstKey][0];
            } else if (xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
          }
          $('#phone-feedback').html('<span class="text-danger"><i class="fas fa-times-circle"></i> ' + msg + '</span>');
          $btn.removeClass('d-none');
        });
      });

      $('#btn-verify-otp').on('click', function(e) {
        e.preventDefault();
        let enteredOtp = $('#otp_code').val().trim();
        let phoneVal = $('#phone_number').val().trim();
        let countryCode = $('#country_code').val().trim();
        let nameVal = $('#first_name').val().trim();
        let emailVal = $('#email').val().trim();

        if (!enteredOtp) {
          $('#otp-feedback').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ __("Please enter the OTP.") }}</span>');
          return;
        }

        $('#otp-feedback').html('<span class="text-info"><i class="fas fa-spinner fa-spin"></i> {{ __("Verifying OTP...") }}</span>');

        $.post("{{ route('front.otp.verify') }}", {
          _token: "{{ csrf_token() }}",
          otp: enteredOtp,
          phone_number: phoneVal
        }, function(response) {
          if (response.success) {
            isPhoneVerified = true;
            clearInterval(otpTimer);
            $('#otp-group').addClass('d-none');
            $('#phone-feedback').html('<span class="text-success" style="font-size: 15px;"><i class="fas fa-check-circle"></i> ' + response.message + '</span>');
            
            // Set verified info summary
            $('#summary-verified-info').text(nameVal + ' (' + countryCode + ' ' + phoneVal + ')');

            // Make fields readonly and change verify button state
            $('#phone_number').prop('readonly', true).removeAttr('required');
            $('#country_code').prop('readonly', true);
            $('#first_name').prop('readonly', true).removeAttr('required');
            $('#email').prop('readonly', true).removeAttr('required');
            
            $('#btn-send-otp')
              .prop('disabled', true)
              .text('{{ __("Verified") }}')
              .css({
                'border-color': '#10b981',
                'color': '#10b981',
                'background-color': 'rgba(16, 185, 129, 0.05)'
              });

            // Smooth transition to screen-2
            setTimeout(function() {
              $('#screen-1').addClass('d-none');
              $('#screen-2').removeClass('d-none');
            }, 800);
          } else {
            $('#otp-feedback').html('<span class="text-danger"><i class="fas fa-times-circle"></i> ' + response.message + '</span>');
          }
        }).fail(function(xhr) {
          let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : '{{ __("Invalid OTP. Please try again.") }}';
          $('#otp-feedback').html('<span class="text-danger"><i class="fas fa-times-circle"></i> ' + msg + '</span>');
        });
      });

      // Edit contact callback to transition back to screen-1
      $('#btn-edit-contact').on('click', function(e) {
        e.preventDefault();
        isPhoneVerified = false;
        
        // Reset inputs
        $('#phone_number').prop('readonly', false).attr('required', true);
        $('#country_code').prop('readonly', false);
        $('#first_name').prop('readonly', false).attr('required', true);
        $('#email').prop('readonly', false).attr('required', true);
        
        $('#btn-send-otp')
          .prop('disabled', false)
          .text('{{ __("Get OTP") }}')
          .css({
            'border-color': '',
            'color': '',
            'background-color': ''
          });
          
        $('#phone-feedback').html('');
        $('#otp-feedback').html('');
        $('#otp_code').val('');
        $('#otp-whatsapp-badge').addClass('d-none');
        
        // Switch screens
        $('#screen-2').addClass('d-none');
        $('#screen-1').removeClass('d-none');
      });

      // Prevent signup form submission if phone is not verified
      $('#authForm').on('submit', function(e) {
        if (!isPhoneVerified) {
          e.preventDefault();
          $('#phone-feedback').html('<span class="text-danger" style="font-size: 15px;"><i class="fas fa-exclamation-triangle"></i> {{ __("Please verify your phone number before continuing.") }}</span>');
          
          // Scroll to the phone verification area
          $('html, body').animate({
            scrollTop: $('#phone_number').offset().top - 120
          }, 500);
          
          $('#phone_number').addClass('is-invalid');
        }
      });
    });

    // Password visibility toggle function
    function togglePasswordVisibility(fieldId) {
      const field = document.getElementById(fieldId);
      const eyeIcon = document.getElementById(fieldId + '-eye-icon');
      
      if (field.type === 'password') {
        field.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        field.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    }
  </script>
@endsection
