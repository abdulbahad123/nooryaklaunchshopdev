<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In - LaunchShop Website Builder</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --primary-orange: #FF5722;
      --primary-orange-hover: #E64A19;
      --dark-navy: #0F172A;
    }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #F8FAFC;
      color: #0F172A;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 0;
    }

    .login-container {
      width: 100%;
      max-width: 1050px;
      background: #ffffff;
      border-radius: 28px;
      overflow: hidden;
      box-shadow: 0 20px 50px rgba(0,0,0,0.06);
      border: 1px solid #E2E8F0;
    }

    /* LEFT SIDE GRAPHIC BANNER (Ref Screenshot 2 Match) */
    .login-left-panel {
      background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
      padding: 48px;
      color: #ffffff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
      position: relative;
    }
    .left-logo {
      font-weight: 800;
      font-size: 24px;
      color: #ffffff;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }
    .left-logo span { color: #FF5722; }

    .illustration-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 30px;
      margin: 24px 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .badge-pill-light {
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.15);
      color: #E2E8F0;
      font-size: 12px;
      font-weight: 600;
      padding: 6px 14px;
      border-radius: 30px;
    }

    /* RIGHT SIDE FORM (Ref Screenshot 2 Match) */
    .login-right-panel {
      padding: 56px 48px;
    }
    .portal-badge {
      background: #FFF7ED;
      color: #EA580C;
      font-size: 11px;
      font-weight: 800;
      padding: 5px 12px;
      border-radius: 20px;
      letter-spacing: 0.8px;
      display: inline-block;
      margin-bottom: 16px;
    }

    .input-custom-group {
      position: relative;
    }
    .input-custom-group input {
      height: 52px;
      border-radius: 12px;
      border: 1px solid #E2E8F0;
      background: #F8FAFC;
      padding-left: 46px;
      font-size: 14.5px;
      transition: all 0.2s;
    }
    .input-custom-group input:focus {
      background: #ffffff;
      border-color: #FF5722;
      box-shadow: 0 0 0 3.5px rgba(255,87,34,0.15);
    }
    .input-custom-group i.input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #94A3B8;
      font-size: 16px;
      z-index: 5;
    }

    .btn-signin-orange {
      background: linear-gradient(135deg, #FF5722 0%, #F4511E 100%);
      color: #ffffff;
      font-weight: 800;
      font-size: 16px;
      height: 52px;
      border-radius: 12px;
      border: none;
      width: 100%;
      transition: all 0.2s;
      box-shadow: 0 6px 20px rgba(255,87,34,0.25);
    }
    .btn-signin-orange:hover {
      background: #E64A19;
      color: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 8px 25px rgba(255,87,34,0.35);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="login-container mx-auto">
    <div class="row g-0">
      
      <!-- LEFT COLUMN: GRAPHIC BANNER (Ref Screenshot 2 Match) -->
      <div class="col-lg-6 d-none d-lg-block">
        <div class="login-left-panel">
          <div>
            <a href="{{ route('website-builder.index') }}" class="left-logo">
              <i class="fa-solid fa-rocket text-warning"></i> LaunchShop<span>.in</span>
            </a>
          </div>

          <div class="illustration-card text-center">
            <svg width="220" height="170" viewBox="0 0 260 200" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="260" height="200" rx="16" fill="#F1F5F9"/>
              <rect x="20" y="20" width="220" height="130" rx="10" fill="#3B82F6"/>
              <circle cx="130" cy="85" r="30" fill="#60A5FA"/>
              <rect x="40" y="165" width="180" height="15" rx="4" fill="#94A3B8"/>
              <path d="M100 135L70 180H190L160 135H100Z" fill="#CBD5E1"/>
            </svg>
          </div>

          <div>
            <h3 class="fw-extrabold text-white mb-2">Welcome Back!</h3>
            <p class="text-slate-300 small mb-4" style="line-height: 1.6;">
              Sign in to manage your website, update content, track customer inquiries, and scale your business.
            </p>

            <div class="d-flex align-items-center gap-2 flex-wrap">
              <span class="badge-pill-light"><i class="fa-solid fa-shield-check text-success me-1"></i> Secure Login</span>
              <span class="badge-pill-light"><i class="fa-solid fa-headset text-warning me-1"></i> 24/7 Support</span>
              <span class="badge-pill-light"><i class="fa-solid fa-lock text-info me-1"></i> Trusted Platform</span>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN: SIGN IN FORM (Ref Screenshot 2 Match) -->
      <div class="col-lg-6">
        <div class="login-right-panel">
          
          <span class="portal-badge text-uppercase">CUSTOMER PORTAL</span>
          <h2 class="fw-extrabold text-slate-900 mb-1">Sign In to Your Store</h2>
          <p class="text-muted small mb-4">Enter your credentials to access your store's dashboard and tools.</p>

          @if(session('error'))
            <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
              <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
            </div>
          @endif
          @if(session('success'))
            <div class="alert alert-success py-2 px-3 small rounded-3 mb-3">
              <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
          @endif

          <form action="{{ route('website-builder.login.submit') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label class="form-label fw-bold small text-secondary">Email or Subdomain</label>
              <div class="input-custom-group">
                <i class="fa-regular fa-envelope input-icon"></i>
                <input type="text" name="login" class="form-control" placeholder="Enter email or subdomain" value="{{ old('login') }}" required autofocus>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small text-secondary">Password</label>
              <div class="input-custom-group">
                <i class="fa-solid fa-lock input-icon"></i>
                <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Enter your password" required>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <a href="#" class="text-decoration-none small text-danger fw-semibold">Forgot your password?</a>
              <a href="{{ route('website-builder.checkout') }}" class="text-decoration-none small text-muted">Don't have an account? <span class="text-danger fw-bold">Sign Up</span></a>
            </div>

            <button type="submit" class="btn-signin-orange">
              <i class="fa-solid fa-sign-in-alt me-2"></i> Sign In
            </button>
          </form>

          <div class="text-center mt-4 pt-3 border-top">
            <a href="{{ route('website-builder.index') }}" class="text-decoration-none small text-secondary fw-semibold">
              <i class="fa-solid fa-arrow-left me-1"></i> Return to Main Website
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>
