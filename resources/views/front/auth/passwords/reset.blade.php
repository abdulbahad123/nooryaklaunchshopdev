@extends('front.layout')

@section('pagename')
  - {{ $pageHeading ?? __('Reset Password') }}
@endsection
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
    min-height: 580px;
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

  /* Animated key icon */
  .rp-key-icon {
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
    box-shadow: 0 12px 40px rgba(0,0,0,0.2);
    animation: keyFloat 3s ease-in-out infinite;
  }
  .rp-key-icon i { font-size: 46px; color: #ffffff; }
  @keyframes keyFloat {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50%       { transform: translateY(-8px) rotate(5deg); }
  }
  .rp-key-icon::before {
    content: '';
    position: absolute;
    width: 150px; height: 150px;
    border: 2px solid rgba(255,255,255,0.12);
    border-radius: 50%;
    animation: rpPulse 2.5s ease-out infinite;
  }
  .rp-key-icon::after {
    content: '';
    position: absolute;
    width: 192px; height: 192px;
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

  .rp-panel-steps {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
    max-width: 260px;
    margin: 0 auto;
  }
  .rp-panel-step {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    font-weight: 600;
    color: rgba(255,255,255,0.88);
  }
  .rp-step-num {
    width: 26px; height: 26px;
    background: rgba(255,255,255,0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 800;
    color: #fff;
    flex-shrink: 0;
    border: 1px solid rgba(255,255,255,0.25);
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

  .rp-form-header { margin-bottom: 36px; }
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

  /* Password strength meter */
  .rp-strength-track {
    height: 4px;
    background: #eef2f6;
    border-radius: 4px;
    margin-top: 8px;
    overflow: hidden;
  }
  .rp-strength-bar {
    height: 100%;
    width: 0%;
    border-radius: 4px;
    transition: width 0.4s, background 0.4s;
  }
  .rp-strength-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #94a3b8;
    margin-top: 4px;
  }

  /* Fields */
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
    padding: 0 48px 0 46px;
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
  .rp-eye-btn {
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
  .rp-eye-btn:hover { color: #ff5a2c; }

  /* Submit */
  .rp-submit-btn {
    width: 100%;
    height: 56px;
    background: #ff5a2c;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.25s;
    font-family: inherit;
    box-shadow: 0 4px 16px rgba(255, 90, 44, 0.28);
    margin-top: 8px;
  }
  .rp-submit-btn:hover {
    background: #e0451a;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(255, 90, 44, 0.38);
  }
  .rp-submit-btn:active { transform: translateY(0); }

  /* Back link */
  .rp-back-row {
    margin-bottom: 20px;
    font-size: 13.5px;
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
        <div class="rp-key-icon">
          <i class="fal fa-key"></i>
        </div>
        <div class="rp-panel-text">
          <h2>{{ __('Set a New Password') }}</h2>
          <p>{{ __('Choose a strong password to keep your store account safe and secure.') }}</p>
        </div>
        <div class="rp-panel-steps">
          <div class="rp-panel-step">
            <div class="rp-step-num">1</div>
            {{ __('At least 8 characters') }}
          </div>
          <div class="rp-panel-step">
            <div class="rp-step-num">2</div>
            {{ __('Mix letters, numbers & symbols') }}
          </div>
          <div class="rp-panel-step">
            <div class="rp-step-num">3</div>
            {{ __('Confirm your new password') }}
          </div>
        </div>
      </div>

      {{-- Right Form Panel --}}
      <div class="rp-right-panel">
        <div class="rp-form-header">
          <span class="badge-label">{{ __('Secure Reset') }}</span>
          <h1>{{ __('Create New Password') }}</h1>
          <p>{{ __('Enter and confirm your new password below. Make sure it\'s strong and unique.') }}</p>
        </div>

        <form class="login-form" action="{{ route('user.reset.password.submit') }}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">

          {{-- Email (read-only) --}}
          <div class="rp-field">
            <label class="rp-label" for="rpEmail">{{ __('Email Address') }}</label>
            <div class="rp-input-wrap">
              <i class="fal fa-envelope rp-input-icon"></i>
              <input type="email" id="rpEmail" name="email" class="rp-input"
                placeholder="{{ __('email') }}" value="{{ $email }}" style="padding-right:16px;">
            </div>
            @error('email')
              <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- New Password --}}
          <div class="rp-field">
            <label class="rp-label" for="rpPassword">{{ __('New Password') }}</label>
            <div class="rp-input-wrap">
              <i class="fal fa-lock rp-input-icon"></i>
              <input type="password" id="rpPassword" name="password" class="rp-input"
                placeholder="{{ __('Enter new password') }}" value="{{ old('password') }}"
                required oninput="checkStrength(this.value)">
              <button type="button" class="rp-eye-btn" onclick="togglePass('rpPassword','eyeIcon1')">
                <i class="fal fa-eye" id="eyeIcon1"></i>
              </button>
            </div>
            <div class="rp-strength-track">
              <div class="rp-strength-bar" id="strengthBar"></div>
            </div>
            <div class="rp-strength-label" id="strengthLabel"></div>
            @error('password')
              <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Confirm Password --}}
          <div class="rp-field">
            <label class="rp-label" for="rpConfirm">{{ __('Confirm New Password') }}</label>
            <div class="rp-input-wrap">
              <i class="fal fa-lock-alt rp-input-icon"></i>
              <input id="rpConfirm" type="password" class="rp-input"
                placeholder="{{ __('Confirm new password') }}" name="password_confirmation"
                required autocomplete="new-password">
              <button type="button" class="rp-eye-btn" onclick="togglePass('rpConfirm','eyeIcon2')">
                <i class="fal fa-eye" id="eyeIcon2"></i>
              </button>
            </div>
          </div>

          <div class="rp-back-row">
            <a href="{{ route('user.login') }}"><i class="fal fa-arrow-left"></i> {{ __('Back to Login') }}</a>
          </div>

          <button type="submit" class="rp-submit-btn">
            <i class="fal fa-check-circle"></i> {{ __('Reset Password') }}
          </button>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function togglePass(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    if (f.type === 'password') {
      f.type = 'text';
      i.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      f.type = 'password';
      i.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }

  function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)         score++;
    if (/[A-Z]/.test(val))       score++;
    if (/[0-9]/.test(val))       score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
      { w: '0%',   bg: 'transparent', txt: '' },
      { w: '25%',  bg: '#ef4444',     txt: 'Weak' },
      { w: '50%',  bg: '#f59e0b',     txt: 'Fair' },
      { w: '75%',  bg: '#3b82f6',     txt: 'Good' },
      { w: '100%', bg: '#10b981',     txt: 'Strong' },
    ];
    bar.style.width      = levels[score].w;
    bar.style.background = levels[score].bg;
    label.textContent    = levels[score].txt;
    label.style.color    = levels[score].bg;
  }
</script>
@endsection
