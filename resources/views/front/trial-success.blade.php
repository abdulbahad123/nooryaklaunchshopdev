<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ __('Registration Successful') }} — {{ $bs->website_title ?? 'LaunchShop' }}</title>
  <link rel="stylesheet" href="{{ asset('assets/front/css/plugin.min.css') }}">
  <link href="{{ asset('assets/front/css/style-base-color.php') . '?color=' . $bs->base_color }}" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --primary: #ff5a2c;
      --dark:    #0e1b3d;
      --green:   #10b981;
      --muted:   #64748b;
      --border:  #e2e8f0;
      --bg:      #f0f4ff;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(145deg, #f0f4ff 0%, #fdf6f2 55%, #fff8f5 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
      position: relative;
      overflow-x: hidden;
    }

    /* Decorative blobs */
    .blob { position: fixed; border-radius: 50%; pointer-events: none; }
    .blob-1 {
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(255,90,44,0.08) 0%, transparent 70%);
      top: -150px; right: -150px;
    }
    .blob-2 {
      width: 450px; height: 450px;
      background: radial-gradient(circle, rgba(14,27,61,0.06) 0%, transparent 70%);
      bottom: -100px; left: -100px;
    }

    /* Card */
    .success-card {
      position: relative;
      background: #ffffff;
      border-radius: 28px;
      border: 1px solid var(--border);
      box-shadow: 0 24px 80px rgba(14, 27, 61, 0.09);
      padding: 60px 50px;
      max-width: 560px;
      width: 100%;
      text-align: center;
      animation: cardIn 0.65s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes cardIn {
      from { opacity: 0; transform: translateY(40px) scale(0.95); }
      to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Animated checkmark */
    .icon-ring {
      width: 108px; height: 108px;
      margin: 0 auto 36px;
      position: relative;
    }
    .icon-circle {
      width: 108px; height: 108px;
      border-radius: 50%;
      background: linear-gradient(135deg, #10b981, #34d399);
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 10px 32px rgba(16, 185, 129, 0.35);
      animation: circlePop 0.5s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
      position: relative;
    }
    @keyframes circlePop {
      from { transform: scale(0.3); opacity: 0; }
      to   { transform: scale(1);   opacity: 1; }
    }
    .icon-circle i {
      font-size: 42px;
      color: #ffffff;
      animation: checkIn 0.4s 0.6s ease both;
    }
    @keyframes checkIn {
      from { transform: scale(0) rotate(-40deg); opacity: 0; }
      to   { transform: scale(1) rotate(0);       opacity: 1; }
    }
    .icon-circle::before {
      content: '';
      position: absolute;
      width: 140px; height: 140px;
      border: 2px solid rgba(16, 185, 129, 0.2);
      border-radius: 50%;
      top: -16px; left: -16px;
      animation: pulse 2s ease-out infinite;
    }
    .icon-circle::after {
      content: '';
      position: absolute;
      width: 172px; height: 172px;
      border: 2px solid rgba(16, 185, 129, 0.1);
      border-radius: 50%;
      top: -32px; left: -32px;
      animation: pulse 2s 0.5s ease-out infinite;
    }
    @keyframes pulse {
      0%   { transform: scale(0.85); opacity: 0.7; }
      80%  { transform: scale(1.1);  opacity: 0; }
      100% { transform: scale(1.1);  opacity: 0; }
    }

    /* Badge */
    .success-badge {
      display: inline-block;
      font-size: 11px;
      font-weight: 800;
      color: var(--green);
      background: rgba(16, 185, 129, 0.08);
      border: 1px solid rgba(16, 185, 129, 0.15);
      padding: 4px 16px;
      border-radius: 50px;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 14px;
      animation: fadeUp 0.5s 0.45s ease both;
    }

    h1 {
      font-size: 32px;
      font-weight: 900;
      color: var(--dark);
      letter-spacing: -0.5px;
      margin-bottom: 12px;
      animation: fadeUp 0.5s 0.55s ease both;
    }

    .success-msg {
      font-size: 15px;
      color: var(--muted);
      line-height: 1.7;
      animation: fadeUp 0.5s 0.62s ease both;
    }

    .store-url-pill {
      display: inline-block;
      font-size: 14px;
      font-weight: 700;
      color: var(--primary);
      background: rgba(255, 90, 44, 0.06);
      border: 1px solid rgba(255, 90, 44, 0.14);
      padding: 7px 16px;
      border-radius: 10px;
      margin-top: 12px;
      word-break: break-all;
      animation: fadeUp 0.5s 0.68s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(14px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* Divider */
    .divider {
      width: 56px; height: 3px;
      background: linear-gradient(90deg, var(--primary), #ffaa88);
      border-radius: 3px;
      margin: 24px auto;
      animation: fadeUp 0.5s 0.72s ease both;
    }

    /* Countdown */
    .countdown-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 28px;
      animation: fadeUp 0.5s 0.78s ease both;
    }
    .countdown-pill {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: #f8fafc;
      border: 1px solid var(--border);
      border-radius: 50px;
      padding: 9px 20px;
      font-size: 14px;
      color: var(--muted);
      font-weight: 500;
    }
    #js-timer {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px; height: 32px;
      background: var(--primary);
      color: #fff;
      border-radius: 50%;
      font-size: 15px;
      font-weight: 800;
    }

    /* Progress bar */
    .progress-track {
      height: 4px;
      background: #eef2f6;
      border-radius: 4px;
      overflow: hidden;
      margin-bottom: 32px;
      animation: fadeUp 0.5s 0.82s ease both;
    }
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--primary), #ffaa88);
      border-radius: 4px;
      width: 100%;
      animation: drain 5s linear forwards;
    }
    @keyframes drain {
      from { width: 100%; }
      to   { width: 0%; }
    }

    /* CTA */
    .cta-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      width: 100%;
      height: 58px;
      background: var(--primary);
      color: #ffffff !important;
      border: none;
      border-radius: 14px;
      font-size: 16px;
      font-weight: 800;
      cursor: pointer;
      letter-spacing: 0.3px;
      text-decoration: none !important;
      transition: all 0.25s;
      box-shadow: 0 6px 22px rgba(255, 90, 44, 0.3);
      animation: fadeUp 0.5s 0.88s ease both;
      font-family: 'Inter', sans-serif;
    }
    .cta-btn:hover {
      background: #e0451a;
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(255, 90, 44, 0.38);
      color: #fff !important;
    }

    /* Trust chips */
    .trust-chips {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-top: 28px;
      flex-wrap: wrap;
      animation: fadeUp 0.5s 0.96s ease both;
    }
    .trust-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      background: #f8fafc;
      border: 1px solid var(--border);
      border-radius: 50px;
      padding: 5px 14px;
    }
    .trust-chip .ic { color: var(--green); font-size: 11px; }

    /* Confetti */
    .confetti {
      position: fixed;
      width: 8px; height: 8px;
      border-radius: 2px;
      pointer-events: none;
      top: -10px;
      animation: fall linear forwards;
      z-index: 9999;
    }
    @keyframes fall {
      0%   { transform: translateY(0)      rotate(0deg);   opacity: 1; }
      100% { transform: translateY(110vh)  rotate(720deg); opacity: 0; }
    }

    @media (max-width: 575px) {
      .success-card { padding: 40px 22px; border-radius: 20px; }
      h1 { font-size: 26px; }
    }
  </style>
</head>
<body>
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>

  <div class="success-card">

    <div class="icon-ring">
      <div class="icon-circle">
        <i class="fas fa-check"></i>
      </div>
    </div>

    <span class="success-badge">{{ __('Registration Confirmed') }}</span>
    <h1 id="js-heading">{{ __('Registered Successfully!') }}</h1>
    <p class="success-msg" id="js-msg">{{ __('Your trial has been activated. Welcome aboard!') }}</p>
    <div id="js-store-wrap" style="display:none">
      <span class="store-url-pill" id="js-store-url"></span>
    </div>

    <div class="divider"></div>

    <div class="countdown-wrap">
      <div class="countdown-pill">
        <span id="js-timer">5</span>
        {{ __('Redirecting you automatically...') }}
      </div>
    </div>

    <div class="progress-track">
      <div class="progress-fill"></div>
    </div>

    <a href="#" id="js-cta" class="cta-btn">
      <i class="fas fa-rocket"></i>
      <span id="js-cta-text">{{ __('Open My Store Now') }}</span>
    </a>

    <div class="trust-chips">
      <span class="trust-chip"><i class="fas fa-shield-alt ic"></i> {{ __('Secure & Encrypted') }}</span>
      <span class="trust-chip"><i class="fas fa-check-circle ic"></i> {{ __('Store Activated') }}</span>
      <span class="trust-chip"><i class="fas fa-headset ic"></i> {{ __('24/7 Support') }}</span>
    </div>

  </div>

  {{-- Font Awesome --}}
  <link rel="stylesheet" href="{{ asset('assets/front/css/plugin.min.css') }}">

  <script>
    'use strict';
    @php
      $username        = $new_user_username ?? (auth()->check() ? auth()->user()->username : null);
      $websiteHost     = env('WEBSITE_HOST', request()->getHost());
      $subdomainUrl    = $username ? 'https://' . $username . '.' . $websiteHost : null;
      $storeUrl        = $subdomainUrl ?? route('front.index');
      $hasSubdomain    = !empty($username);
    @endphp

    const storeUrl    = @json($storeUrl);
    const hasSubdomain = @json($hasSubdomain);
    const username    = @json($username);
    const countdown   = 5;

    document.addEventListener('DOMContentLoaded', function () {
      const cta     = document.getElementById('js-cta');
      const ctaTxt  = document.getElementById('js-cta-text');
      const msgEl   = document.getElementById('js-msg');
      const storeWrap = document.getElementById('js-store-wrap');
      const storeUrlEl= document.getElementById('js-store-url');
      const timerEl = document.getElementById('js-timer');

      cta.href = storeUrl;

      if (hasSubdomain) {
        msgEl.textContent = '{{ __("Your store is ready! You're being redirected to your new store.") }}';
        ctaTxt.textContent = '{{ __("Open My Store Now") }}';
        storeWrap.style.display = 'block';
        storeUrlEl.textContent = username + '.{{ env("WEBSITE_HOST") }}';
      } else {
        msgEl.textContent = '{{ __("You have registered successfully. We sent you an email with a verify link. Please check your inbox.") }}';
        ctaTxt.textContent = '{{ __("Go Home") }}';
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

      // Confetti burst
      const colors = ['#ff5a2c','#0e1b3d','#10b981','#f59e0b','#6366f1','#ec4899'];
      for (let i = 0; i < 70; i++) {
        setTimeout(function () {
          const el = document.createElement('div');
          el.classList.add('confetti');
          el.style.left = Math.random() * 100 + 'vw';
          el.style.background = colors[Math.floor(Math.random() * colors.length)];
          el.style.animationDuration = (Math.random() * 2.5 + 1.5) + 's';
          el.style.animationDelay    = (Math.random() * 0.5) + 's';
          el.style.width  = (Math.random() * 8 + 4) + 'px';
          el.style.height = (Math.random() * 10 + 4) + 'px';
          el.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
          document.body.appendChild(el);
          setTimeout(function () { el.remove(); }, 4500);
        }, i * 25);
      }
    });
  </script>
</body>
</html>
