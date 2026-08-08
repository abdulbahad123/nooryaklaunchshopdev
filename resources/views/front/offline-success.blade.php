@extends('front.layout')

@section('pagename') - {{ __('Registration Successful') }} @endsection
@section('breadcrumb-title') {{ __('Success') }} @endsection
@section('breadcrumb-link') {{ __('Success') }} @endsection

@section('styles')
<style>
  /* Hide breadcrumb */
  .page-title-area { display: none !important; }

  /* ── Full-page wrapper ── */
  .success-fullpage {
    min-height: 100vh;
    background: linear-gradient(145deg, #f0f4ff 0%, #fdf6f2 55%, #fff8f5 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    position: relative;
    overflow: hidden;
  }
  .success-blob-1 {
    position: absolute;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(255, 90, 44, 0.08) 0%, transparent 70%);
    top: -100px; right: -100px;
    border-radius: 50%;
    pointer-events: none;
  }
  .success-blob-2 {
    position: absolute;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(14, 27, 61, 0.06) 0%, transparent 70%);
    bottom: -80px; left: -80px;
    border-radius: 50%;
    pointer-events: none;
  }

  /* ── Card ── */
  .success-card {
    position: relative;
    background: #ffffff;
    border-radius: 28px;
    border: 1px solid #eef2f6;
    box-shadow: 0 20px 70px rgba(14, 27, 61, 0.08);
    padding: 60px 50px;
    max-width: 560px;
    width: 100%;
    text-align: center;
    animation: successCardIn 0.65s cubic-bezier(0.16, 1, 0.3, 1) both;
  }
  @keyframes successCardIn {
    from { opacity: 0; transform: translateY(30px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* ── Animated icon (clock / pending) ── */
  .success-icon-ring {
    width: 108px; height: 108px;
    margin: 0 auto 36px;
    position: relative;
  }
  .success-ring-circle {
    width: 108px; height: 108px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 10px 32px rgba(245, 158, 11, 0.35);
    animation: circlePop 0.5s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
    position: relative;
  }
  @keyframes circlePop {
    from { transform: scale(0.3); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
  }
  .success-ring-circle i {
    font-size: 42px;
    color: #ffffff;
    animation: checkIn 0.4s 0.6s ease both;
  }
  @keyframes checkIn {
    from { transform: scale(0) rotate(-30deg); opacity: 0; }
    to   { transform: scale(1) rotate(0); opacity: 1; }
  }
  .success-ring-circle::before {
    content: '';
    position: absolute;
    width: 140px; height: 140px;
    border: 2px solid rgba(245, 158, 11, 0.25);
    border-radius: 50%;
    top: -16px; left: -16px;
    animation: pulsering 2s ease-out infinite;
  }
  .success-ring-circle::after {
    content: '';
    position: absolute;
    width: 172px; height: 172px;
    border: 2px solid rgba(245, 158, 11, 0.12);
    border-radius: 50%;
    top: -32px; left: -32px;
    animation: pulsering 2s 0.5s ease-out infinite;
  }
  @keyframes pulsering {
    0%   { transform: scale(0.85); opacity: 0.7; }
    80%  { transform: scale(1.1); opacity: 0; }
    100% { transform: scale(1.1); opacity: 0; }
  }

  /* ── Text ── */
  .success-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 800;
    color: #d97706;
    background: rgba(245, 158, 11, 0.08);
    border: 1px solid rgba(245, 158, 11, 0.18);
    padding: 4px 16px;
    border-radius: 50px;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 14px;
    animation: fadeUp 0.5s 0.45s ease both;
  }
  .success-card h1 {
    font-size: 32px;
    font-weight: 800;
    color: #0e1b3d;
    letter-spacing: -0.5px;
    margin-bottom: 12px;
    animation: fadeUp 0.5s 0.55s ease both;
  }
  .success-message {
    font-size: 15px;
    color: #64748b;
    line-height: 1.7;
    animation: fadeUp 0.5s 0.62s ease both;
  }
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Pending info box */
  .pending-info-box {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 12px;
    padding: 16px 20px;
    margin: 20px 0;
    font-size: 13.5px;
    color: #92400e;
    font-weight: 500;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    text-align: left;
    animation: fadeUp 0.5s 0.68s ease both;
  }
  .pending-info-box i {
    color: #d97706;
    font-size: 16px;
    margin-top: 2px;
    flex-shrink: 0;
  }

  /* ── Divider ── */
  .success-divider {
    width: 56px; height: 3px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
    border-radius: 3px;
    margin: 24px auto;
    animation: fadeUp 0.5s 0.72s ease both;
  }

  /* ── Countdown ── */
  .success-countdown-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 28px;
    animation: fadeUp 0.5s 0.78s ease both;
  }
  .success-countdown-pill {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    padding: 9px 20px;
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
  }
  #success-timer {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px; height: 32px;
    background: #f59e0b;
    color: #fff;
    border-radius: 50%;
    font-size: 14px;
    font-weight: 800;
  }

  /* ── Progress ── */
  .success-progress-track {
    height: 4px;
    background: #eef2f6;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 32px;
    animation: fadeUp 0.5s 0.82s ease both;
  }
  .success-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
    border-radius: 4px;
    width: 100%;
    animation: drainProgress 5s linear forwards;
  }
  @keyframes drainProgress {
    from { width: 100%; }
    to   { width: 0%; }
  }

  /* ── CTA ── */
  .success-cta-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    height: 56px;
    background: #ff5a2c;
    color: #ffffff !important;
    border: none;
    border-radius: 14px;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    letter-spacing: 0.3px;
    text-decoration: none !important;
    transition: all 0.25s;
    box-shadow: 0 6px 20px rgba(255, 90, 44, 0.3);
    animation: fadeUp 0.5s 0.88s ease both;
    font-family: inherit;
  }
  .success-cta-btn:hover {
    background: #e0451a;
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(255, 90, 44, 0.38);
    color: #fff !important;
  }

  /* ── Info chips ── */
  .success-info-chips {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 28px;
    flex-wrap: wrap;
    animation: fadeUp 0.5s 0.95s ease both;
  }
  .success-info-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    padding: 5px 14px;
  }
  .success-info-chip i { color: #10b981; font-size: 11px; }

  /* Responsive */
  @media (max-width: 575.98px) {
    .success-card { padding: 40px 24px; border-radius: 20px; }
    .success-card h1 { font-size: 26px; }
  }
</style>
@endsection

@section('content')
<div class="success-fullpage">
  <div class="success-blob-1"></div>
  <div class="success-blob-2"></div>

  <div class="success-card">

    {{-- Animated icon (clock/pending theme) --}}
    <div class="success-icon-ring">
      <div class="success-ring-circle">
        <i class="fas fa-paper-plane"></i>
      </div>
    </div>

    <span class="success-badge">{{ __('Pending Approval') }}</span>
    <h1>{{ __('Request Submitted!') }}</h1>
    <p class="success-message">{{ __('Your payment request has been submitted successfully.') }}</p>

    <div class="pending-info-box">
      <i class="fas fa-info-circle"></i>
      <span>{{ __('Your order is pending admin approval. You will receive an email confirmation once your account has been activated. This typically takes 1–24 hours.') }}</span>
    </div>

    <div class="success-divider"></div>

    <div class="success-countdown-wrap">
      <div class="success-countdown-pill">
        <span id="success-timer">5</span>
        {{ __('Redirecting you automatically...') }}
      </div>
    </div>

    <div class="success-progress-track">
      <div class="success-progress-bar"></div>
    </div>

    <a href="#" id="success-cta-link" class="success-cta-btn">
      <i class="fas fa-home"></i>
      <span id="success-cta-text">{{ __('Go Home') }}</span>
    </a>

    <div class="success-info-chips">
      <span class="success-info-chip"><i class="fas fa-envelope"></i> {{ __('Email Confirmation Sent') }}</span>
      <span class="success-info-chip"><i class="fas fa-clock"></i> {{ __('Pending Review') }}</span>
      <span class="success-info-chip"><i class="fas fa-headset"></i> {{ __('24/7 Support') }}</span>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
  'use strict';
  @php
    $username     = $new_user_username ?? (auth()->check() ? auth()->user()->username : null);
    $websiteHost  = env('WEBSITE_HOST', request()->getHost());
    $subdomainUrl = $username ? 'https://' . $username . '.' . $websiteHost : null;
    $isGuest      = !auth()->check();
    $storeUrl     = ($isGuest && $subdomainUrl) ? $subdomainUrl : ($isGuest ? route('front.index') : route('user-dashboard'));
    $hasSubdomain = $isGuest && !empty($username);
  @endphp

  const storeUrl     = @json($storeUrl);
  const hasSubdomain = @json($hasSubdomain);
  const username     = @json($username);
  const countdown    = 5;

  document.addEventListener('DOMContentLoaded', function () {
    const ctaLink   = document.getElementById('success-cta-link');
    const ctaText   = document.getElementById('success-cta-text');
    const timerEl   = document.getElementById('success-timer');

    ctaLink.href = storeUrl;

    if (hasSubdomain) {
      ctaText.textContent = '{{ __("Open My Store Now") }}';
    }

    // Countdown
    let seconds = countdown;
    const interval = setInterval(function () {
      seconds--;
      if (timerEl) timerEl.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(interval);
        window.location.href = storeUrl;
      }
    }, 1000);
  });
</script>
@endsection
