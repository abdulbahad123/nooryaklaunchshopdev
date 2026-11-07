@extends('user-front.layout')

@section('meta-description', !empty($seo) ? $seo->signup_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->signup_meta_keywords : '')
@section('breadcrumb_title', $pageHeading->signup_page ?? __('Signup'))
@section('page-title', $pageHeading->signup_page ?? __('Signup'))

@section('content')
  <!-- Authentication Start -->
  <div class="authentication-area ptb-80">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">

          <div class="modern-auth-card">
            @if (Session::has('warning'))
              <div class="alert alert-danger modern-alert text-danger mb-4">
                <i class="fal fa-exclamation-circle mr-2"></i> {{ Session::get('warning') }}
              </div>
            @endif
            @if (Session::has('sendmail'))
              <div class="alert alert-success modern-alert mb-4">
                <i class="fal fa-envelope mr-2"></i> {{ __(Session::get('sendmail')) }}
              </div>
            @endif

            <div class="auth-card-header mb-4 text-center">
              <div class="auth-icon-circle mx-auto mb-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="8.5" cy="7" r="4"></circle>
                  <line x1="20" y1="8" x2="20" y2="14"></line>
                  <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
              </div>
              <h3 class="auth-card-title mb-1">{{ $keywords['Signup'] ?? __('Create Customer Account') }}</h3>
              <p class="auth-card-subtitle mb-0 text-muted">{{ __('Sign up for faster checkout, order tracking & wishlists') }}</p>
            </div>

            <form action="{{ route('customer.signup.submit', getParam()) }}" method="POST">
              @csrf

              <!-- Username Field -->
              <div class="form-group modern-input-group mb-3">
                <label class="modern-input-label">{{ $keywords['Username'] ?? __('Username') }} *</label>
                <div class="auth-input-wrapper">
                  <span class="auth-input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                  </span>
                  <input type="text" placeholder="{{ $keywords['Username'] ?? __('Enter Username') }}"
                    class="auth-input-field" name="username" value="{{ old('username') }}" required>
                </div>
                @error('username')
                  <p class="text-danger small mt-1 mb-0">{{ $message }}</p>
                @enderror
              </div>

              <!-- Email Field -->
              <div class="form-group modern-input-group mb-3">
                <label class="modern-input-label">{{ $keywords['Email_Address'] ?? __('Email Address') }} *</label>
                <div class="auth-input-wrapper">
                  <span class="auth-input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                      <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                  </span>
                  <input type="email" placeholder="{{ $keywords['Email_Address'] ?? __('Enter Email Address') }}"
                    class="auth-input-field" name="email" value="{{ old('email') }}" required>
                </div>
                @error('email')
                  <p class="text-danger small mt-1 mb-0">{{ $message }}</p>
                @enderror
              </div>

              <!-- Password Field with Eye Toggle -->
              <div class="form-group modern-input-group mb-3">
                <label class="modern-input-label">{{ $keywords['Enter Password'] ?? __('Password') }} *</label>
                <div class="auth-input-wrapper">
                  <span class="auth-input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                  </span>
                  <input type="password" id="signupPassword" class="auth-input-field" name="password"
                    placeholder="{{ $keywords['Enter Password'] ?? __('Enter Password') }}" required autocomplete="new-password">
                  <button type="button" class="btn-auth-eye" onclick="togglePasswordVisibility('signupPassword', 'signupEyeIcon')" aria-label="Toggle Password Visibility">
                    <span id="signupEyeIcon">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                    </span>
                  </button>
                </div>
                @error('password')
                  <p class="text-danger small mt-1 mb-0">{{ $message }}</p>
                @enderror
              </div>

              <!-- Confirm Password Field with Eye Toggle -->
              <div class="form-group modern-input-group mb-3">
                <label class="modern-input-label">{{ $keywords['Enter Confirm Password'] ?? __('Confirm Password') }} *</label>
                <div class="auth-input-wrapper">
                  <span class="auth-input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                  </span>
                  <input type="password" id="signupConfirmPassword" class="auth-input-field" name="password_confirmation"
                    placeholder="{{ $keywords['Enter Confirm Password'] ?? __('Confirm Password') }}" required autocomplete="new-password">
                  <button type="button" class="btn-auth-eye" onclick="togglePasswordVisibility('signupConfirmPassword', 'signupConfirmEyeIcon')" aria-label="Toggle Confirm Password Visibility">
                    <span id="signupConfirmEyeIcon">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                    </span>
                  </button>
                </div>
              </div>

              @if ($userBs->is_recaptcha == 1 && in_array('Google Recaptcha', $packagePermissions))
                <div class="form-group mb-3">
                  <div class="d-block">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                      @php
                        $errmsg = $errors->first('g-recaptcha-response');
                      @endphp
                      <p class="text-danger mb-0 mt-2">{{ __("$errmsg") }}</p>
                    @endif
                  </div>
                </div>
              @endif

              <!-- Submit Button -->
              <button type="submit" class="btn-modern-auth w-100 mt-2 mb-3">
                <span>{{ $keywords['Signup'] ?? __('Create My Account') }}</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                  <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
              </button>

              @if (in_array('Google Login', $packagePermissions) && $userBs->is_google_login == 1)
                <div class="auth-divider-clean my-3">
                  <span class="auth-divider-line"></span>
                  <span class="auth-divider-label">{{ $keywords['or'] ?? __('OR') }}</span>
                  <span class="auth-divider-line"></span>
                </div>
                <a href="{{ route('customer.google.login', getParam()) }}" class="btn-google-auth w-100">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                    <path fill="#fbc02d" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                    <path fill="#e53935" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                    <path fill="#4caf50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                    <path fill="#1565c0" d="M43.611,20.083L43.595,20L42,20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                  </svg>
                  <span>{{ $keywords['Login with Google'] ?? __('Sign up with Google') }}</span>
                </a>
              @endif
            </form>

            <!-- Prominent & Attractive Log In Callout Banner -->
            <div class="auth-signup-callout mt-4 pt-3 border-top">
              <div class="d-flex align-items-center justify-content-between p-3 rounded auth-signup-banner">
                <div class="text-left pr-2">
                  <span class="auth-signup-title d-block">{{ $keywords['Already_have_an_account'] ?? __('Already have an account?') }}</span>
                  <small class="auth-signup-desc text-muted">{{ __('Log in to access your saved details') }}</small>
                </div>
                <a href="{{ route('customer.login', getParam()) }}" class="btn-auth-signup-cta text-nowrap">
                  <span>{{ $keywords['Login'] ?? __('Log In') }}</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                  </svg>
                </a>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Authentication End -->

  <style>
    /* ========== Modern Auth Layout Styles ========== */
    .modern-auth-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      padding: 36px 30px;
      box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03);
      position: relative;
    }
    .auth-icon-circle {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: rgba(15, 23, 42, 0.05);
      color: #0f172a;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .auth-card-title {
      font-size: 22px;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: -0.3px;
    }
    .auth-card-subtitle {
      font-size: 13px;
      color: #64748b;
    }

    /* ========== Clean Divider ========== */
    .auth-divider-clean {
      display: flex;
      align-items: center;
      gap: 12px;
      text-align: center;
    }
    .auth-divider-line {
      flex: 1;
      height: 1px;
      background: #e2e8f0;
    }
    .auth-divider-label {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #94a3b8;
      padding: 0 4px;
    }

    /* ========== Input Groups & Fields ========== */
    .modern-input-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: #334155;
      margin-bottom: 6px;
      text-align: left;
    }
    .auth-input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      width: 100%;
    }
    .auth-input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      display: flex;
      align-items: center;
      pointer-events: none;
      z-index: 2;
    }
    .auth-input-field {
      width: 100%;
      height: 48px;
      padding: 10px 44px 10px 42px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      font-size: 14px;
      color: #0f172a;
      background: #f8fafc;
      transition: all 0.2s ease;
      outline: none;
    }
    .auth-input-field:focus {
      background: #ffffff;
      border-color: #0f172a;
      box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
    }
    .btn-auth-eye {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      padding: 6px 8px;
      color: #94a3b8;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      transition: color 0.2s ease;
      z-index: 3;
    }
    .btn-auth-eye:hover {
      color: #0f172a;
    }

    /* ========== Buttons ========== */
    .btn-modern-auth {
      height: 48px;
      border-radius: 10px;
      background: #0f172a;
      color: #ffffff;
      font-size: 14px;
      font-weight: 700;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      cursor: pointer;
      box-shadow: 0 8px 18px -4px rgba(15, 23, 42, 0.25);
      transition: all 0.2s ease;
    }
    .btn-modern-auth:hover {
      background: #1e293b;
      color: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.35);
    }
    .btn-google-auth {
      height: 46px;
      border-radius: 10px;
      background: #ffffff;
      border: 1.5px solid #e2e8f0;
      color: #334155;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      text-decoration: none !important;
      transition: all 0.2s ease;
    }
    .btn-google-auth:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      color: #0f172a;
    }

    /* ========== Attractive Callout Banner ========== */
    .auth-signup-banner {
      background: #f8fafc;
      border: 1.5px solid #e2e8f0;
      border-radius: 12px !important;
    }
    .auth-signup-title {
      font-weight: 700;
      font-size: 13px;
      color: #0f172a;
      line-height: 1.3;
    }
    .auth-signup-desc {
      font-size: 11px;
      color: #64748b;
      line-height: 1.3;
    }
    .btn-auth-signup-cta {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: #0f172a;
      color: #ffffff !important;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      text-decoration: none !important;
      box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
      transition: all 0.2s ease;
    }
    .btn-auth-signup-cta:hover {
      background: #1e293b;
      color: #ffffff !important;
      transform: translateY(-1px);
      box-shadow: 0 6px 14px rgba(15, 23, 42, 0.22);
    }
  </style>

  <script>
    function togglePasswordVisibility(inputId, iconContainerId) {
      var input = document.getElementById(inputId);
      var container = document.getElementById(iconContainerId);
      if (!input) return;

      var eyeSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
      var eyeSlashSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';

      if (input.type === 'password') {
        input.type = 'text';
        if (container) container.innerHTML = eyeSlashSvg;
      } else {
        input.type = 'password';
        if (container) container.innerHTML = eyeSvg;
      }
    }
  </script>
@endsection
