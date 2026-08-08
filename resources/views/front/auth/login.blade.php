@extends('front.layout')

@section('meta-description', !empty($seo) ? $seo->login_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->login_meta_keywords : '')

@section('pagename')
  - {{ $pageHeading ?? __('Login') }}
@endsection
@section('breadcrumb-title')
  {{ $pageHeading ?? __('Login') }}
@endsection
@section('breadcrumb-link')
  {{ $pageHeading ?? __('Login') }}
@endsection

@section('styles')
<style>
  /* Hide default breadcrumb */
  .page-title-area {
    display: none !important;
  }

  /* ========== Login Split-Screen Layout ========== */
  .login-split-area {
    background: #f8fafc;
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 60px 0;
  }

  .login-split-wrapper {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    border: 1px solid #eef2f6;
    width: 100%;
    display: flex;
    min-height: 600px;
  }

  /* ========== Left Illustration Panel ========== */
  .login-illustration-panel {
    background: linear-gradient(135deg, #0e1b3d 0%, #1a3060 60%, #ff5a2c 150%);
    flex: 0 0 45%;
    max-width: 45%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 40px;
    position: relative;
    overflow: hidden;
  }

  .login-illustration-panel::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-image: radial-gradient(rgba(255,255,255,0.06) 1.5px, transparent 1.5px);
    background-size: 26px 26px;
    pointer-events: none;
  }

  .login-illustration-panel::after {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(255, 90, 44, 0.25) 0%, transparent 70%);
    bottom: -100px; right: -100px;
    border-radius: 50%;
    pointer-events: none;
  }

  .login-brand-logo {
    position: relative;
    z-index: 1;
    margin-bottom: 40px;
  }

  .login-brand-logo img {
    height: 40px;
    filter: brightness(0) invert(1);
  }

  .login-illustration-img {
    position: relative;
    z-index: 1;
    display: block;
    margin: 0 auto 40px;
    width: auto;
    height: auto;
    max-width: 90%;
    max-height: 360px;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
  }

  .login-panel-heading {
    position: relative;
    z-index: 1;
    text-align: center;
    color: #ffffff;
  }

  .login-panel-heading h2 {
    font-size: 26px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
    line-height: 1.3;
  }

  .login-panel-heading p {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
    max-width: 280px;
    margin: 0 auto;
  }

  .login-panel-trust-badges {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 16px;
    margin-top: 32px;
    flex-wrap: wrap;
    justify-content: center;
  }

  .login-trust-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 50px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
  }

  .login-trust-badge i {
    color: #10b981;
    font-size: 12px;
  }

  /* ========== Right Form Panel ========== */
  .login-form-panel {
    flex: 0 0 55%;
    max-width: 55%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 60px 60px;
  }

  .login-form-header {
    margin-bottom: 40px;
  }

  .login-form-header .badge-label {
    display: inline-block;
    font-size: 11px;
    font-weight: 800;
    color: #ff5a2c;
    background: rgba(255, 90, 44, 0.08);
    padding: 5px 14px;
    border-radius: 50px;
    letter-spacing: 1px;
    margin-bottom: 16px;
    text-transform: uppercase;
    border: 1px solid rgba(255, 90, 44, 0.12);
  }

  .login-form-header h1 {
    font-size: 34px;
    font-weight: 800;
    color: #0e1b3d;
    margin-bottom: 10px;
    letter-spacing: -0.5px;
    line-height: 1.2;
  }

  .login-form-header p {
    font-size: 15px;
    color: #64748b;
    margin: 0;
    line-height: 1.6;
  }

  /* ========== Form Inputs ========== */
  .login-field-group {
    margin-bottom: 20px;
  }

  .login-field-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 8px;
    letter-spacing: 0.3px;
  }

  .login-input-wrap {
    position: relative;
  }

  .login-input-wrap .field-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: #94a3b8;
    pointer-events: none;
    transition: color 0.2s;
  }

  .login-input-field {
    width: 100%;
    height: 52px;
    padding: 0 16px 0 46px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 15px;
    color: #1e293b;
    background: #f8fafc;
    transition: all 0.2s;
    outline: none;
    font-family: inherit;
  }

  .login-input-field:focus {
    border-color: #ff5a2c;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(255, 90, 44, 0.08);
  }

  .login-input-wrap:focus-within .field-icon {
    color: #ff5a2c;
  }

  /* Password toggle */
  .pass-toggle-btn {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    font-size: 15px;
    padding: 4px;
    transition: color 0.2s;
  }
  .pass-toggle-btn:hover {
    color: #ff5a2c;
  }

  /* ========== Links Row ========== */
  .login-links-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 8px;
  }

  .login-forgot-link {
    font-size: 13px;
    font-weight: 600;
    color: #ff5a2c;
    text-decoration: none !important;
    transition: opacity 0.2s;
  }

  .login-forgot-link:hover {
    opacity: 0.8;
  }

  .login-signup-text {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
  }

  .login-signup-text a {
    color: #ff5a2c;
    font-weight: 700;
    text-decoration: none !important;
    transition: opacity 0.2s;
  }

  .login-signup-text a:hover {
    opacity: 0.8;
  }

  /* ========== Submit Button ========== */
  .login-submit-btn {
    width: 100%;
    height: 56px;
    background: #ff5a2c;
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.25s;
    font-family: inherit;
    box-shadow: 0 4px 14px rgba(255, 90, 44, 0.25);
  }

  .login-submit-btn:hover {
    background: #e0451a;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(255, 90, 44, 0.35);
  }

  .login-submit-btn:active {
    transform: translateY(0);
  }

  /* ========== Responsive ========== */
  @media (max-width: 991.98px) {
    .login-illustration-panel {
      display: none;
    }
    .login-form-panel {
      flex: 0 0 100%;
      max-width: 100%;
      padding: 40px;
    }
    .login-split-wrapper {
      border-radius: 0;
      border: none;
      box-shadow: none;
      min-height: 100vh;
    }
    .login-split-area {
      padding: 50px 0 40px 0;
      background: #ffffff;
    }
  }

  @media (max-width: 575.98px) {
    .login-form-panel {
      padding: 30px 24px;
    }
    .login-form-header h1 {
      font-size: 28px;
    }
  }
</style>
@endsection

@section('content')
<div class="login-split-area">
  <div class="container-fluid p-0">
    <div class="login-split-wrapper">

      {{-- Left: Illustration Panel --}}
      <div class="login-illustration-panel">
        <div class="login-brand-logo">
          <img src="{{ asset('assets/front/img/' . $bs->logo) }}" alt="{{ $bs->website_title }}">
        </div>
        <img class="login-illustration-img"
          src="{{ asset('images/signup.gif') }}"
          alt="Welcome back">
        <div class="login-panel-heading">
          <h2>{{ __('Welcome Back!') }}</h2>
          <p>{{ __('Sign in to manage your store, track orders, and grow your business.') }}</p>
        </div>
        <div class="login-panel-trust-badges">
          <span class="login-trust-badge"><i class="fas fa-check-circle"></i> {{ __('Secure Login') }}</span>
          <span class="login-trust-badge"><i class="fas fa-check-circle"></i> {{ __('24/7 Support') }}</span>
          <span class="login-trust-badge"><i class="fas fa-check-circle"></i> {{ __('Trusted Platform') }}</span>
        </div>
      </div>

      {{-- Right: Form Panel --}}
      <div class="login-form-panel">
        <div class="login-form-header">
          <span class="badge-label">{{ __('Merchant Portal') }}</span>
          <h1>{{ __('Sign In to Your Store') }}</h1>
          <p>{{ __("Enter your credentials to access your store's dashboard and tools.") }}</p>
        </div>

        <form id="authForm" action="{{ route('user.login') }}" method="post" enctype="multipart/form-data">
          @csrf

          {{-- Email or Phone Number --}}
          <div class="login-field-group">
            <label class="login-field-label" for="loginEmail">{{ __('Email or Phone Number') }}</label>
            <div class="login-input-wrap">
              <i class="fal fa-envelope field-icon"></i>
              <input type="text" id="loginEmail" name="email" class="login-input-field"
                placeholder="{{ __('Enter email or phone number') }}" required>
            </div>
            @if (Session::has('err'))
              <p class="text-danger small mt-1">{{ Session::get('err') }}</p>
            @endif
            @error('email')
              <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Password --}}
          <div class="login-field-group">
            <label class="login-field-label" for="loginPassword">{{ __('Password') }}</label>
            <div class="login-input-wrap">
              <i class="fal fa-lock field-icon"></i>
              <input type="password" id="loginPassword" name="password" class="login-input-field"
                placeholder="{{ __('Enter your password') }}" required>
              <button type="button" class="pass-toggle-btn" onclick="togglePassword()">
                <i class="fal fa-eye" id="passEyeIcon"></i>
              </button>
            </div>
            @error('password')
              <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Recaptcha --}}
          @if ($bs->is_recaptcha == 1)
            <div class="mb-4">
              {!! NoCaptcha::renderJs() !!}
              {!! NoCaptcha::display() !!}
              @if ($errors->has('g-recaptcha-response'))
                @php $errmsg = $errors->first('g-recaptcha-response'); @endphp
                <p class="text-danger small mt-2">{{ __($errmsg) }}</p>
              @endif
            </div>
          @endif

          {{-- Links Row --}}
          <div class="login-links-row">
            <a href="{{ route('user.forgot.password.form') }}" class="login-forgot-link">
              {{ __('Forgot your password?') }}
            </a>
            <span class="login-signup-text">
              {{ __("Don't have an account?") }}
              <a href="{{ route('front.pricing') }}">{{ __('Sign Up') }}</a>
            </span>
          </div>

          {{-- Submit --}}
          <button type="submit" class="login-submit-btn">
            <i class="fas fa-sign-in-alt"></i> {{ __('Sign In') }}
          </button>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function togglePassword() {
    var field = document.getElementById('loginPassword');
    var icon = document.getElementById('passEyeIcon');
    if (field.type === 'password') {
      field.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      field.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }
</script>
@endsection
