@extends('user-front.layout')
@section('meta-description', !empty($seo) ? $seo->login_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->login_meta_keywords : '')
@section('breadcrumb_title', $pageHeading->login_page ?? __('Login'))
@section('page-title', $pageHeading->login_page ?? __('Login'))

@section('content')
  @php
    $input = request()->input('redirected');
  @endphp

  <!-- Authentication Start -->
  <div class="authentication-area ptb-80">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">

          @if (!onlyDigitalItemsInCart() && $input == 'checkout')
            <!-- Instant Order (Guest Checkout) Card -->
            <div class="instant-order-box mb-4">
              <a href="{{ route('front.user.checkout', [getParam(), 'type' => 'guest']) }}" class="btn-instant-order-cta">
                <div class="instant-order-badge">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                  </svg>
                </div>
                <div class="instant-order-info">
                  <span class="instant-order-headline">{{ $keywords['Instant Order'] ?? __('Instant Order') }}</span>
                  <span class="instant-order-tagline">{{ $keywords['Fast Checkout without Login'] ?? __('Fast Checkout — No Account or Password Needed') }}</span>
                </div>
                <div class="instant-order-chevron">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                  </svg>
                </div>
              </a>
            </div>

            <!-- Sleek Modern OR Divider -->
            <div class="auth-divider-clean my-4">
              <span class="auth-divider-line"></span>
              <span class="auth-divider-label">{{ $keywords['OR'] ?? __('OR CONTINUE WITH ACCOUNT') }}</span>
              <span class="auth-divider-line"></span>
            </div>
          @endif

          <div class="modern-auth-card">
            @if (Session::has('error'))
              <div class="alert alert-danger modern-alert text-danger mb-4">
                <i class="fal fa-exclamation-circle mr-2"></i> {{ Session::get('error') }}
              </div>
            @endif
            @if (Session::has('success'))
              <div class="alert alert-success modern-alert mb-4">
                <i class="fal fa-check-circle mr-2"></i> {{ Session::get('success') }}
              </div>
            @endif

            <div class="auth-card-header mb-4 text-center">
              <div class="auth-icon-circle mx-auto mb-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </div>
              <h3 class="auth-card-title mb-1">{{ $keywords['Login'] ?? __('Customer Login') }}</h3>
              <p class="auth-card-subtitle mb-0 text-muted">{{ __('Enter your credentials to access your account & orders') }}</p>
            </div>

            <form id="authForm" action="{{ route('customer.login_submit', getParam()) }}" method="POST">
              @csrf
              <input type="hidden" name="user_id" value="{{ $user->id }}">

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
                  <input type="email" placeholder="{{ $keywords['Enter_Email_Address'] ?? __('Enter Email Address') }}"
                    class="auth-input-field" name="email" value="{{ old('email') }}" required>
                </div>
                @error('email')
                  <p class="text-danger small mt-1 mb-0">{{ $message }}</p>
                @enderror
              </div>

              <!-- Password Field with Eye Toggle -->
              <div class="form-group modern-input-group mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <label class="modern-input-label mb-0">{{ $keywords['Password'] ?? __('Password') }} *</label>
                  <a href="{{ route('customer.forget_password', getParam()) }}" class="auth-forgot-link">
                    {{ $keywords['Forgot your password'] ?? __('Forgot password?') }}
                  </a>
                </div>
                <div class="auth-input-wrapper">
                  <span class="auth-input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                  </span>
                  <input type="password" id="customerLoginPassword" class="auth-input-field" name="password"
                    placeholder="{{ $keywords['Enter Password'] ?? __('Enter Password') }}" required autocomplete="current-password">
                  <button type="button" class="btn-auth-eye" onclick="togglePasswordVisibility('customerLoginPassword', 'loginEyeIcon')" aria-label="Toggle Password Visibility">
                    <span id="loginEyeIcon">
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

              <!-- Submit Login Button -->
              <button type="submit" class="btn-modern-auth w-100 mt-2 mb-3">
                <span>{{ $keywords['Login'] ?? __('Login to Account') }}</span>
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
                  <span>{{ $keywords['Login with Google'] ?? __('Continue with Google') }}</span>
                </a>
              @endif
            </form>

            <!-- Prominent & Attractive Sign Up Banner -->
            <div class="auth-signup-callout mt-4 pt-3 border-top">
              <div class="d-flex align-items-center justify-content-between p-3 rounded auth-signup-banner">
                <div class="text-left pr-2">
                  <span class="auth-signup-title d-block">{{ $keywords['Dont_have_an_account'] ?? __("Don't have an account?") }}</span>
                  <small class="auth-signup-desc text-muted">{{ __('Sign up to track orders & save wishlist') }}</small>
                </div>
                <a href="{{ route('customer.signup', getParam()) }}" class="btn-auth-signup-cta text-nowrap">
                  <span>{{ $keywords['Signup'] ?? __('Sign Up') }}</span>
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

    /* ========== Instant Order (Guest Checkout) CTA ========== */
    .instant-order-box {
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 12px 30px -5px rgba(15, 23, 42, 0.18);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .instant-order-box:hover {
      transform: translateY(-2px);
      box-shadow: 0 18px 36px -6px rgba(15, 23, 42, 0.25);
    }
    .btn-instant-order-cta {
      display: flex;
      align-items: center;
      gap: 14px;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      padding: 18px 22px;
      text-decoration: none !important;
      color: #ffffff !important;
      transition: all 0.25s ease;
    }
    .instant-order-badge {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: rgba(255, 184, 0, 0.18);
      color: #ffb800;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      border: 1px solid rgba(255, 184, 0, 0.3);
    }
    .instant-order-info {
      flex: 1;
      display: flex;
      flex-direction: column;
      text-align: left;
    }
    .instant-order-headline {
      font-size: 17px;
      font-weight: 800;
      color: #ffffff;
      line-height: 1.25;
      letter-spacing: -0.2px;
    }
    .instant-order-tagline {
      font-size: 12px;
      color: #94a3b8;
      margin-top: 2px;
      line-height: 1.3;
    }
    .instant-order-chevron {
      color: #94a3b8;
      display: flex;
      align-items: center;
      transition: transform 0.25s ease, color 0.25s ease;
    }
    .btn-instant-order-cta:hover .instant-order-chevron {
      color: #ffffff;
      transform: translateX(4px);
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
    .auth-forgot-link {
      font-size: 12px;
      font-weight: 600;
      color: #64748b;
      text-decoration: none;
      transition: color 0.2s ease;
    }
    .auth-forgot-link:hover {
      color: #0f172a;
      text-decoration: underline;
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

    /* ========== Attractive Sign Up Callout Box ========== */
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
