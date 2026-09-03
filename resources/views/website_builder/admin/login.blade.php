<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Website Builder Admin Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #090d16;
      color: #ffffff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .login-card {
      background: #131b2e;
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    }
    .brand-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
      justify-content: center;
    }
    .brand-icon {
      width: 44px;
      height: 44px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }
    .form-control {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.15);
      color: #fff;
      padding: 12px 16px;
      border-radius: 10px;
    }
    .form-control:focus {
      background: rgba(255, 255, 255, 0.08);
      border-color: #6366f1;
      color: #fff;
      box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
    }
    .btn-gradient {
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      color: #fff;
      font-weight: 700;
      padding: 12px;
      border-radius: 10px;
      border: none;
      width: 100%;
      transition: opacity 0.2s;
    }
    .btn-gradient:hover {
      opacity: 0.95;
      color: #fff;
    }
    .btn-auto-login {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
      font-weight: 700;
      padding: 14px;
      border-radius: 10px;
      border: none;
      width: 100%;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
      transition: transform 0.2s;
    }
    .btn-auto-login:hover {
      transform: translateY(-2px);
      color: #fff;
    }
    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      color: #64748b;
      margin: 24px 0;
      font-size: 13px;
    }
    .divider::before, .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .divider::before { margin-right: 12px; }
    .divider::after { margin-left: 12px; }
  </style>
</head>
<body>

<div class="login-card">
  <div class="brand-logo">
    <div class="brand-icon"><i class="fa-solid fa-layer-group"></i></div>
    <span class="fs-4 fw-extrabold text-white">WB Admin Portal</span>
  </div>

  <p class="text-center text-muted mb-4 small">Sign in to manage Website Builder platform, themes, and client packages.</p>

  @if(session('alert'))
    <div class="alert alert-danger py-2 fs-6">{{ session('alert') }}</div>
  @endif

  @if(session('success'))
    <div class="alert alert-success py-2 fs-6">{{ session('success') }}</div>
  @endif

  <!-- 1-Click Auto Login Button -->
  <div class="mb-3">
    <a href="{{ route('website-builder.admin.auto-login') }}" class="btn-auto-login">
      <i class="fa-solid fa-bolt fs-5"></i> ⚡ Click to Auto Login (Super Admin)
    </a>
  </div>

  <div class="divider">OR LOGIN WITH CREDENTIALS</div>

  <form action="{{ route('website-builder.admin.authenticate') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label text-muted small fw-semibold">Username or Email</label>
      <input type="text" name="username" class="form-control" placeholder="admin" required autofocus>
    </div>
    <div class="mb-4">
      <label class="form-label text-muted small fw-semibold">Password</label>
      <input type="password" name="password" class="form-control" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn-gradient">Sign In to Dashboard <i class="fa-solid fa-arrow-right ms-2"></i></button>
  </form>
</div>

</body>
</html>
