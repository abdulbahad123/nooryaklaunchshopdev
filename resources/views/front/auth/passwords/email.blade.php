@extends('front.layout')

@section('pagename')
  - {{ $pageHeading ?? __('Reset Password') }}
@endsection
@section('meta-description', !empty($seo) ? $seo->forget_password_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->forget_password_meta_keywords : '')
@section('breadcrumb-title') {{ $pageHeading ?? __('Reset Password') }} @endsection
@section('breadcrumb-link')  {{ $pageHeading ?? __('Reset Password') }} @endsection

@section('styles')
<style>
  .page-title-area { display: none !important; }

  /* ── Wrapper ── */
  .rp-area {
    background: #f8fafc;
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 60px 0;
  }

  .rp-wrapper {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    border: 1px solid #eef2f6;
    width: 100%;
    display: flex;
    min-height: 560px;
  }

  /* ── Left decorative panel ── */
  .rp-left-panel {
    flex: 0 0 45%;
    max-width: 45%;
    background: linear-gradient(135deg, #0e1b3d 0%, #1a3060 60%, #ff5a2c 160%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 40px;
    position: relative;
    overflow: hidden;
  }
  .rp-left-panel::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.06) 1.5px, transparent 1.5px);
    background-size: 26px 26px;
    pointer-events: none;
  }
  .rp-left-panel::after {
    content: '';
    position: absolute;
    width: 380px; height: 380px;
    background: radial-gradient(circle, rgba(255, 90, 44, 0.22) 0%, transparent 70%);
    bottom: -90px; right: -90px;
    border-radius: 50%;
    pointer-events: none;
  }

  .rp-brand-logo {
    position: relative;
    z-index: 1;
    margin-bottom: 36px;
  }
  .rp-brand-logo img {
    height: 38px;
    filter: brightness(0) invert(1);
  }

  /* Lock icon illustration */
  .rp-lock-icon {
    position: relative;
    z-index: 1;
    width: 110px; height: 110px;
    background: rgba(255,255,255,0.1);
    border: 1.5px solid rgba(255,255,255,0.18);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 32px;
    backdrop-filter: blur(10px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    animation: lockBounce 3s ease-in-out infinite;
  }
  .rp-lock-icon i {
    font-size: 46px;
    color: #ffffff;
  }
  @keyframes lockBounce {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-8px); }
  }
  /* Pulse rings on lock */
  .rp-lock-icon::before {
    content: '';
    position: absolute;
    width: 150px; height: 150px;
    border: 2px solid rgba(255,255,255,0.12);
    border-radius: 50%;
    animation: rpPulse 2.5s ease-out infinite;
  }
  .rp-lock-icon::after {
    content: '';
    position: absolute;
    width: 190px; height: 190px;
    border: 2px solid rgba(255,255,255,0.07);
    border-radius: 50%;
    animation: rpPulse 2.5s 0.5s ease-out infinite;
  }
  @keyframes rpPulse {
    0%   { transform: scale(0.85); opacity: 0.7; }
    80%  { transform: scale(1.05); opacity: 0; }
    100% { transform: scale(1.05); opacity: 0; }
  }

  .rp-panel-text {
    position: relative;
    z-index: 1;
    text-align: center;
  }
  .rp-panel-text h2 {
    font-size: 24px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 10px;
    letter-spacing: -0.3px;
  }
  .rp-panel-text p {
    font-size: 13.5px;
    color: rgba(255,255,255,0.68);
    line-height: 1.65;
    max-width: 260px;
    margin: 0 auto 28px;
  }

  .rp-panel-badges {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
    max-width: 260px;
    margin: 0 auto;
  }
  .rp-panel-badge {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.13);
    border-radius: 10px;
    padding: 9px 14px;
    font-size: 13px;
    font-weight: 600;
    color: rgba(255,255,255,0.88);
    backdrop-filter: blur(6px);
  }
  .rp-panel-badge i {
    color: #10b981;
    font-size: 14px;
    width: 16px;
  }

  /* ── Right form panel ── */
  .rp-right-panel {
    flex: 0 0 55%;
    max-width: 55%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 60px 60px;
  }

  /* Header */
  .rp-form-header {
    margin-bottom: 36px;
  }
  .rp-form-header .badge-label {
    display: inline-block;
    font-size: 11px;
    font-weight: 800;
    color: #ff5a2c;
    background: rgba(255, 90, 44, 0.08);
    border: 1px solid rgba(255, 90, 44, 0.12);
    padding: 5px 14px;
    border-radius: 50px;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 14px;
  }
  .rp-form-header h1 {
    font-size: 30px;
    font-weight: 800;
    color: #0e1b3d;
    letter-spacing: -0.4px;
    margin-bottom: 8px;
  }
  .rp-form-header p {
    font-size: 14.5px;
    color: #64748b;
    line-height: 1.6;
    margin: 0;
  }

  /* Form fields */
  .rp-field { margin-bottom: 20px; }
  .rp-label {
    display: block;
    font-size: 12.5px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 7px;
    letter-spacing: 0.3px;
  }
  .rp-input-wrap { position: relative; }
  .rp-input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
    pointer-events: none;
    transition: color 0.2s;
  }
  .rp-input {
    width: 100%;
    height: 52px;
    padding: 0 16px 0 46px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 15px;
    color: #1e293b;
    background: #f8fafc;
    outline: none;
    font-family: inherit;
    transition: all 0.2s;
  }
  .rp-input:focus {
    border-color: #ff5a2c;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(255, 90, 44, 0.08);
  }
  .rp-input-wrap:focus-within .rp-input-icon { color: #ff5a2c; }

  /* Success alert */
  .rp-success-box {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(16, 185, 129, 0.06);
    border: 1px solid rgba(16, 185, 129, 0.18);
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 13.5px;
    color: #065f46;
    font-weight: 600;
    margin-bottom: 20px;
  }
  .rp-success-box i { color: #10b981; font-size: 18px; flex-shrink: 0; }

  /* Back to login link */
  .rp-back-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    font-size: 13.5px;
    color: #64748b;
  }
  .rp-back-row a {
    color: #ff5a2c;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: opacity 0.2s;
  }
  .rp-back-row a:hover { opacity: 0.8; }

  /* Submit button */
  .rp-submit-btn {
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
    box-shadow: 0 4px 16px rgba(255, 90, 44, 0.28);
  }
  .rp-submit-btn:hover {
    background: #e0451a;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(255, 90, 44, 0.38);
  }
  .rp-submit-btn:active { transform: translateY(0); }

  /* Responsive */
  @media (max-width: 991.98px) {
    .rp-left-panel { display: none; }
    .rp-right-panel { flex: 0 0 100%; max-width: 100%; padding: 40px; }
    .rp-wrapper { border-radius: 0; border: none; box-shadow: none; min-height: 100vh; }
    .rp-area { padding: 0; background: #fff; }
  }
  @media (max-width: 575.98px) {
    .rp-right-panel { padding: 30px 22px; }
    .rp-form-header h1 { font-size: 26px; }
  }
</style>
@endsection

@section('content')
<div class="rp-area">
  <div class="container-fluid p-0">
    <div class="rp-wrapper">

      {{-- Left Panel --}}
      <div class="rp-left-panel">
        <div class="rp-brand-logo">
          <img src="{{ asset('assets/front/img/' . $bs->logo) }}" alt="{{ $bs->website_title }}">
        </div>
        <div class="rp-lock-icon">
          <i class="fal fa-lock"></i>
        </div>
        <div class="rp-panel-text">
          <h2>{{ __('Forgot Your Password?') }}</h2>
          <p>{{ __("No worries! Enter your email and we'll send you a reset link right away.") }}</p>
        </div>
        <div class="rp-panel-badges">
          <div class="rp-panel-badge"><i class="fas fa-envelope-open-text"></i> {{ __('Reset link via email') }}</div>
          <div class="rp-panel-badge"><i class="fas fa-shield-alt"></i> {{ __('Secure & encrypted') }}</div>
          <div class="rp-panel-badge"><i class="fas fa-clock"></i> {{ __('Link expires in 60 minutes') }}</div>
        </div>
      </div>

      {{-- Right Form Panel --}}
      <div class="rp-right-panel">
        <div class="rp-form-header">
          <span class="badge-label">{{ __('Account Recovery') }}</span>
          <h1>{{ __('Reset Your Password') }}</h1>
          <p>{{ __("Enter the email address associated with your account and we'll send you a password reset link.") }}</p>
        </div>

        @if (Session::has('message') || Session::has('status'))
          <div class="rp-success-box">
            <i class="fas fa-check-circle"></i>
            <span>{{ Session::get('message') ?? Session::get('status') }}</span>
          </div>
        @endif

        <form id="authForm" action="{{ route('user.forgot.password.submit') }}" method="post" enctype="multipart/form-data">
          @csrf

          <div class="rp-field">
            <label class="rp-label" for="rpEmail">{{ __('Email Address') }}</label>
            <div class="rp-input-wrap">
              <i class="fal fa-envelope rp-input-icon"></i>
              <input type="email" id="rpEmail" name="email" class="rp-input"
                placeholder="{{ __('you@example.com') }}" value="{{ old('email') }}" required>
            </div>
            @if (Session::has('err'))
              <p class="text-danger small mt-1">{{ Session::get('err') }}</p>
            @endif
            @error('email')
              <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
          </div>

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

          <div class="rp-back-row">
            <a href="{{ route('user.login') }}"><i class="fal fa-arrow-left"></i> {{ __('Back to Login') }}</a>
          </div>

          <button type="submit" class="rp-submit-btn">
            <i class="fal fa-paper-plane"></i> {{ __('Send Reset Link') }}
          </button>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection
