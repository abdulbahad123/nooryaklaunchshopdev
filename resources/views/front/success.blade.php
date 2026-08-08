@extends('front.layout')

@section('pagename') - {{ __('Registration Successful') }} @endsection
@section('breadcrumb-title') {{ __('Success') }} @endsection
@section('breadcrumb-link') {{ __('Success') }} @endsection

@section('styles')
<style>
  /* Hide breadcrumb */
  .page-title-area { display: none !important; }

  /* ── Full-page success wrapper ── */
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

  /* Decorative blobs */
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
    animation: successCardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
  }

  @keyframes successCardIn {
    from { opacity: 0; transform: translateY(30px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* ── Animated checkmark ring ── */
  .success-icon-ring {
    width: 100px; height: 100px;
    margin: 0 auto 32px;
    position: relative;
  }

  .success-ring-circle {
    width: 100px; height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #34d399);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 28px rgba(16, 185, 129, 0.35);
    animation: ringPop 0.5s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
  }

  @keyframes ringPop {
    from { transform: scale(0.4); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
  }

  .success-ring-circle i {
    font-size: 40px;
    color: #ffffff;
    animation: checkIn 0.4s 0.55s ease both;
  }

  @keyframes checkIn {
    from { transform: scale(0) rotate(-30deg); opacity: 0; }
    to   { transform: scale(1) rotate(0);       opacity: 1; }
  }

  /* Pulse ring */
  .success-ring-circle::before {
    content: '';
    position: absolute;
    width: 130px; height: 130px;
    border: 2px solid rgba(16, 185, 129, 0.2);
    border-radius: 50%;
    top: -15px; left: -15px;
    animation: pulsering 2s ease-out infinite;
  }
  .success-ring-circle::after {
    content: '';
    position: absolute;
    width: 160px; height: 160px;
    border: 2px solid rgba(16, 185, 129, 0.1);
    border-radius: 50%;
    top: -30px; left: -30px;
    animation: pulsering 2s 0.4s ease-out infinite;
  }

  @keyframes pulsering {
    0%   { transform: scale(0.8); opacity: 0.7; }
    80%  { transform: scale(1.1); opacity: 0; }
    100% { transform: scale(1.1); opacity: 0; }
  }

  /* ── Text ── */
  .success-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 800;
    color: #10b981;
    background: rgba(16, 185, 129, 0.08);
    border: 1px solid rgba(16, 185, 129, 0.15);
    padding: 4px 14px;
    border-radius: 50px;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 14px;
    animation: fadeSlideUp 0.5s 0.4s ease both;
  }

  .success-card h1 {
    font-size: 32px;
    font-weight: 800;
    color: #0e1b3d;
    letter-spacing: -0.5px;
    margin-bottom: 12px;
    animation: fadeSlideUp 0.5s 0.5s ease both;
  }

  .success-message {
    font-size: 15px;
    color: #64748b;
    line-height: 1.7;
    margin-bottom: 0;
    animation: fadeSlideUp 0.5s 0.6s ease both;
  }

  .success-store-url {
    display: inline-block;
    font-size: 14px;
    font-weight: 700;
    color: #ff5a2c;
    background: rgba(255, 90, 44, 0.06);
    border: 1px solid rgba(255, 90, 44, 0.12);
    padding: 6px 14px;
    border-radius: 8px;
    margin-top: 10px;
    word-break: break-all;
    animation: fadeSlideUp 0.5s 0.65s ease both;
  }

  @keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── Divider ── */
  .success-divider {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #ff5a2c, #ffaa88);
    border-radius: 2px;
    margin: 24px auto;
    animation: fadeSlideUp 0.5s 0.7s ease both;
  }

  /* ── Countdown pill ── */
  .success-countdown-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 28px;
    animation: fadeSlideUp 0.5s 0.75s ease both;
  }
  .success-countdown-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    padding: 8px 18px;
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
  }
  #success-timer {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px; height: 30px;
    background: #ff5a2c;
    color: #fff;
    border-radius: 50%;
    font-size: 14px;
    font-weight: 800;
    line-height: 1;
  }

  /* ── Progress bar ── */
  .success-progress-track {
    height: 4px;
    background: #eef2f6;
    border-radius: 4px;
    margin-bottom: 32px;
    overflow: hidden;
    animation: fadeSlideUp 0.5s 0.8s ease both;
  }
  .success-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ff5a2c, #ffaa88);
    border-radius: 4px;
    width: 100%;
    animation: drainProgress 5s linear forwards;
  }
  @keyframes drainProgress {
    from { width: 100%; }
    to   { width: 0%; }
  }

  /* ── CTA Button ── */
  .success-cta-btn {
    display: inline-flex;
    align-items: center;
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
    justify-content: center;
    text-decoration: none !important;
    transition: all 0.25s;
    box-shadow: 0 6px 20px rgba(255, 90, 44, 0.3);
    animation: fadeSlideUp 0.5s 0.85s ease both;
    font-family: inherit;
  }
  .success-cta-btn:hover {
    background: #e0451a;
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(255, 90, 44, 0.38);
    color: #ffffff !important;
  }

  /* ── Info chips ── */
  .success-info-chips {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 28px;
    flex-wrap: wrap;
    animation: fadeSlideUp 0.5s 0.95s ease both;
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

  /* ── Confetti particles ── */
  .confetti-particle {
    position: fixed;
    width: 8px;
    height: 8px;
    border-radius: 2px;
    pointer-events: none;
    animation: confettiFall linear forwards;
    z-index: 9999;
    top: -10px;
  }
  @keyframes confettiFall {
    0%   { transform: translateY(0) rotate(0deg); opacity: 1; }
    100% { transform: translateY(110vh) rotate(720deg); opacity: 0; }
  }

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

    {{-- Animated checkmark --}}
    <div class="success-icon-ring">
      <div class="success-ring-circle">
        <i class="fas fa-check"></i>
      </div>
    </div>

    {{-- Badge --}}
    <span class="success-badge">{{ __('Payment Confirmed') }}</span>

    {{-- Heading --}}
    <h1 id="success-heading">{{ __('Registered Successfully!') }}</h1>
    <p class="success-message" id="success-message">{{ __('Payment successful. Your account is now active.') }}</p>
    <div id="success-store-url-wrap" style="display:none;">
      <span class="success-store-url" id="success-store-url-text"></span>
    </div>

    <div class="success-divider"></div>

    {{-- Countdown --}}
    <div class="success-countdown-wrap">
      <div class="success-countdown-pill">
        <span id="success-timer">5</span>
        {{ __('Redirecting you automatically...') }}
      </div>
    </div>

    {{-- Progress drain --}}
    <div class="success-progress-track">
      <div class="success-progress-bar" id="success-progress-bar"></div>
    </div>

    {{-- CTA --}}
    <a href="#" class="success-cta-btn" id="success-cta-link">
      <i class="fas fa-rocket"></i>
      <span id="success-cta-text">{{ __('Open My Store') }}</span>
    </a>

    {{-- Info chips --}}
    <div class="success-info-chips">
      <span class="success-info-chip"><i class="fas fa-shield-alt"></i> {{ __('Secure & Encrypted') }}</span>
      <span class="success-info-chip"><i class="fas fa-check-circle"></i> {{ __('Store Activated') }}</span>
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
    $storeUrl     = $subdomainUrl ?? (auth()->check() ? route('user-dashboard') : route('front.index'));
    $hasSubdomain = !empty($username);
  @endphp

  const storeUrl     = @json($storeUrl);
  const hasSubdomain = @json($hasSubdomain);
  const username     = @json($username);
  const countdown    = 5;

  // Update text based on subdomain presence
  document.addEventListener('DOMContentLoaded', function () {
    const ctaLink    = document.getElementById('success-cta-link');
    const ctaText    = document.getElementById('success-cta-text');
    const msgEl      = document.getElementById('success-message');
    const storeWrap  = document.getElementById('success-store-url-wrap');
    const storeUrlEl = document.getElementById('success-store-url-text');

    ctaLink.href = storeUrl;

    if (hasSubdomain) {
      msgEl.textContent = '{{ __("Your store is ready!") }}';
      ctaText.textContent = '{{ __("Open My Store Now") }}';
      storeWrap.style.display = 'block';
      storeUrlEl.textContent = username + '.{{ env("WEBSITE_HOST") }}';
    } else {
      msgEl.textContent = '{{ __("Payment successful. Your account is now active.") }}';
      ctaText.textContent = '{{ __("Go to Dashboard") }}';
    }

    // Countdown timer
    let seconds = countdown;
    const timerEl = document.getElementById('success-timer');
    const interval = setInterval(function () {
      seconds--;
      if (timerEl) timerEl.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(interval);
        window.location.href = storeUrl;
      }
    }, 1000);

    // Confetti burst
    const colors = ['#ff5a2c', '#0e1b3d', '#10b981', '#f59e0b', '#6366f1', '#ec4899'];
    for (let i = 0; i < 60; i++) {
      setTimeout(function () {
        const el = document.createElement('div');
        el.classList.add('confetti-particle');
        el.style.left  = Math.random() * 100 + 'vw';
        el.style.background = colors[Math.floor(Math.random() * colors.length)];
        el.style.animationDuration = (Math.random() * 2.5 + 1.5) + 's';
        el.style.animationDelay    = (Math.random() * 0.5) + 's';
        el.style.width  = (Math.random() * 8 + 5) + 'px';
        el.style.height = (Math.random() * 8 + 5) + 'px';
        el.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
        document.body.appendChild(el);
        setTimeout(function () { el.remove(); }, 4000);
      }, i * 30);
    }
  });
</script>
@endsection
