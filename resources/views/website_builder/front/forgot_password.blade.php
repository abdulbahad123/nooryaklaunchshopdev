<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Website Builder</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --primary-orange: #ff5a2c;
      --primary-orange-hover: #e0451a;
      --dark-navy: #0e1b3d;
      --mid-navy: #1a3060;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #f8fafc;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: #1e293b;
    }

    /* Top Navigation matching reference */
    .top-header {
      background: #ffffff;
      border-bottom: 1px solid #edf2f7;
      padding: 16px 36px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .brand-logo {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-weight: 800;
      font-size: 22px;
      color: #0e1b3d;
      text-decoration: none;
      letter-spacing: -0.4px;
    }
    .brand-logo span { color: #ff5a2c; }
    .brand-icon {
      width: 36px;
      height: 36px;
      background: rgba(255, 90, 44, 0.1);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ff5a2c;
      font-size: 18px;
    }
    .header-nav {
      display: flex;
      align-items: center;
      gap: 28px;
    }
    .header-nav a {
      color: #475569;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      transition: color 0.2s;
    }
    .header-nav a:hover { color: #ff5a2c; }
    .btn-login-nav {
      background: #ff5a2c;
      color: #ffffff !important;
      padding: 8px 22px;
      border-radius: 50px;
      font-weight: 700;
      box-shadow: 0 4px 14px rgba(255, 90, 44, 0.3);
    }

    /* Content Area */
    .rp-area {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 48px 20px;
    }
    .rp-wrapper {
      background: #ffffff;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(14, 27, 61, 0.08);
      overflow: hidden;
      border: 1px solid #e2e8f0;
      width: 100%;
      max-width: 1040px;
      display: flex;
      min-height: 580px;
    }

    /* Left Decorative Panel */
    .rp-left-panel {
      flex: 0 0 46%;
      max-width: 46%;
      background: linear-gradient(135deg, #0e1b3d 0%, #1a3060 60%, #ff5a2c 160%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 60px 44px;
      position: relative;
      overflow: hidden;
    }
    .rp-left-panel::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255,255,255,0.08) 1.5px, transparent 1.5px);
      background-size: 26px 26px;
      pointer-events: none;
    }
    .rp-left-panel::after {
      content: '';
      position: absolute;
      width: 420px; height: 420px;
      background: radial-gradient(circle, rgba(255, 90, 44, 0.22) 0%, transparent 70%);
      bottom: -100px; right: -100px;
      border-radius: 50%;
      pointer-events: none;
    }

    .rp-brand-logo {
      position: relative;
      z-index: 1;
      margin-bottom: 34px;
      color: #ffffff;
      font-size: 22px;
      font-weight: 800;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
    .rp-brand-logo span { color: #ff9d7e; }

    /* Floating Lock Icon */
    .rp-lock-icon {
      position: relative;
      z-index: 1;
      width: 110px; height: 110px;
      background: rgba(255,255,255,0.1);
      border: 1.5px solid rgba(255,255,255,0.22);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 30px;
      backdrop-filter: blur(12px);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
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
    .rp-lock-icon::before {
      content: '';
      position: absolute;
      width: 154px; height: 154px;
      border: 2px solid rgba(255,255,255,0.14);
      border-radius: 50%;
      animation: rpPulse 2.5s ease-out infinite;
    }
    .rp-lock-icon::after {
      content: '';
      position: absolute;
      width: 196px; height: 196px;
      border: 2px solid rgba(255,255,255,0.08);
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
      color: rgba(255,255,255,0.72);
      line-height: 1.65;
      max-width: 270px;
      margin: 0 auto 28px;
    }

    .rp-panel-badges {
      position: relative;
      z-index: 1;
      display: flex;
      flex-direction: column;
      gap: 10px;
      width: 100%;
      max-width: 280px;
      margin: 0 auto;
    }
    .rp-panel-badge {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255,255,255,0.09);
      border: 1px solid rgba(255,255,255,0.16);
      border-radius: 12px;
      padding: 10px 16px;
      font-size: 13px;
      font-weight: 600;
      color: rgba(255,255,255,0.92);
      backdrop-filter: blur(8px);
    }
    .rp-panel-badge i {
      color: #10b981;
      font-size: 15px;
      width: 18px;
    }

    /* Right Form Panel */
    .rp-right-panel {
      flex: 0 0 54%;
      max-width: 54%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 60px 56px;
    }
    .rp-form-header {
      margin-bottom: 30px;
    }
    .badge-label {
      display: inline-block;
      font-size: 11px;
      font-weight: 800;
      color: #ff5a2c;
      background: rgba(255, 90, 44, 0.1);
      border: 1px solid rgba(255, 90, 44, 0.18);
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

    .rp-field { margin-bottom: 22px; }
    .rp-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: #374151;
      margin-bottom: 8px;
    }
    .rp-input-wrap { position: relative; }
    .rp-input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 15px;
      pointer-events: none;
      transition: color 0.2s;
    }
    .rp-input {
      width: 100%;
      height: 52px;
      padding: 0 18px 0 48px;
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
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(255, 90, 44, 0.1);
    }
    .rp-input-wrap:focus-within .rp-input-icon { color: #ff5a2c; }

    /* Status Notifications */
    .rp-success-box {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      background: rgba(16, 185, 129, 0.08);
      border: 1px solid rgba(16, 185, 129, 0.25);
      border-radius: 12px;
      padding: 14px 18px;
      font-size: 14px;
      color: #065f46;
      font-weight: 600;
      margin-bottom: 24px;
      line-height: 1.5;
    }
    .rp-success-box i { color: #10b981; font-size: 18px; margin-top: 2px; flex-shrink: 0; }

    .rp-error-box {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      background: rgba(239, 68, 68, 0.08);
      border: 1px solid rgba(239, 68, 68, 0.25);
      border-radius: 12px;
      padding: 14px 18px;
      font-size: 14px;
      color: #991b1b;
      font-weight: 600;
      margin-bottom: 24px;
      line-height: 1.5;
    }
    .rp-error-box i { color: #ef4444; font-size: 18px; margin-top: 2px; flex-shrink: 0; }

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
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: opacity 0.2s;
    }
    .rp-back-row a:hover { opacity: 0.8; }

    .rp-submit-btn {
      width: 100%;
      height: 54px;
      background: #ff5a2c;
      color: #ffffff;
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
      box-shadow: 0 4px 18px rgba(255, 90, 44, 0.32);
    }
    .rp-submit-btn:hover {
      background: #e0451a;
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(255, 90, 44, 0.42);
    }
    .rp-submit-btn:active { transform: translateY(0); }

    /* Responsive */
    @media (max-width: 991.98px) {
      .rp-left-panel { display: none; }
      .rp-right-panel { flex: 0 0 100%; max-width: 100%; padding: 44px 32px; }
      .header-nav { display: none; }
    }
    @media (max-width: 575.98px) {
      .top-header { padding: 14px 20px; }
      .rp-right-panel { padding: 32px 20px; }
      .rp-form-header h1 { font-size: 25px; }
    }
  </style>
</head>
<body>

  <!-- Top Header Navigation -->
  <header class="top-header">
    <a href="{{ route('website-builder.index') }}" class="brand-logo">
      <div class="brand-icon"><i class="fa-solid fa-layer-group"></i></div>
      Website<span>Builder</span>
    </a>
    <nav class="header-nav">
      <a href="{{ route('website-builder.index') }}">Home</a>
      <a href="{{ route('website-builder.templates') }}">Store Themes</a>
      <a href="{{ route('website-builder.pricing') }}">Pricing</a>
      <a href="{{ route('website-builder.login') }}" class="btn-login-nav">Login</a>
    </nav>
  </header>

  <!-- Main Recovery Area -->
  <main class="rp-area">
    <div class="rp-wrapper">

      <!-- Left Decorative Panel (Exact Design from Reference) -->
      <div class="rp-left-panel">
        <a href="{{ route('website-builder.index') }}" class="rp-brand-logo">
          <i class="fa-solid fa-layer-group text-warning"></i> Website<span>Builder</span>
        </a>

        <div class="rp-lock-icon">
          <i class="fa-solid fa-lock"></i>
        </div>

        <div class="rp-panel-text">
          <h2>Forgot Your Password?</h2>
          <p>No worries! Enter your email and we'll send you a reset link right away.</p>
        </div>

        <div class="rp-panel-badges">
          <div class="rp-panel-badge">
            <i class="fa-solid fa-paper-plane"></i> Reset link via WhatsApp & Email
          </div>
          <div class="rp-panel-badge">
            <i class="fa-solid fa-shield-halved"></i> Secure & encrypted
          </div>
          <div class="rp-panel-badge">
            <i class="fa-solid fa-clock"></i> Link expires in 60 minutes
          </div>
        </div>
      </div>

      <!-- Right Form Panel -->
      <div class="rp-right-panel">
        <div class="rp-form-header">
          <span class="badge-label">ACCOUNT RECOVERY</span>
          <h1>Reset Your Password</h1>
          <p>Enter the email address associated with your account and we'll send you a password reset link.</p>
        </div>

        @if(session('status'))
          <div class="rp-success-box">
            <i class="fa-solid fa-circle-check"></i>
            <div>{{ session('status') }}</div>
          </div>
        @endif

        @if(session('error'))
          <div class="rp-error-box">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>{{ session('error') }}</div>
          </div>
        @endif

        <form action="{{ route('website-builder.password.email') }}" method="POST">
          @csrf

          <div class="rp-field">
            <label class="rp-label" for="rpEmail">Email Address</label>
            <div class="rp-input-wrap">
              <i class="fa-solid fa-envelope rp-input-icon"></i>
              <input 
                type="email" 
                id="rpEmail" 
                name="email" 
                class="rp-input @error('email') is-invalid @enderror" 
                placeholder="you@example.com" 
                value="{{ old('email') }}" 
                required 
                autofocus
              >
            </div>
            @error('email')
              <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
          </div>

          <div class="rp-back-row">
            <a href="{{ route('website-builder.login') }}">
              <i class="fa-solid fa-arrow-left"></i> Back to Login
            </a>
          </div>

          <button type="submit" class="rp-submit-btn">
            <i class="fa-solid fa-paper-plane"></i> Send Reset Link
          </button>
        </form>
      </div>

    </div>
  </main>

</body>
</html>
