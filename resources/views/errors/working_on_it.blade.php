<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We Are Working On It | System Maintenance & Recovery</title>

  <!-- Google Fonts & FontAwesome -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    :root {
      --primary-color: #4F46E5;
      --primary-hover: #4338CA;
      --accent-color: #10B981;
      --bg-dark: #0F172A;
      --card-bg: rgba(255, 255, 255, 0.96);
      --text-dark: #0F172A;
      --text-muted: #64748B;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: radial-gradient(circle at 50% 0%, #1E1B4B 0%, #0F172A 70%, #090D16 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 24px;
      color: #ffffff;
      overflow-x: hidden;
      position: relative;
    }

    /* Ambient Glowing Orbs Background */
    .glow-orb-1 {
      position: absolute;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
      top: -100px;
      left: -100px;
      animation: floatOrb 8s infinite alternate ease-in-out;
    }

    .glow-orb-2 {
      position: absolute;
      width: 450px;
      height: 450px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, rgba(0, 0, 0, 0) 70%);
      bottom: -120px;
      right: -120px;
      animation: floatOrb 10s infinite alternate ease-in-out;
    }

    @keyframes floatOrb {
      0% { transform: translate(0, 0) scale(1); }
      100% { transform: translate(40px, 30px) scale(1.1); }
    }

    /* Main Glass Card */
    .maintenance-card {
      background: var(--card-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 28px;
      padding: 48px 40px;
      max-width: 620px;
      width: 100%;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
      position: relative;
      z-index: 10;
      color: var(--text-dark);
      text-align: center;
      animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Status Pill */
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #FEF3C7;
      color: #92400E;
      font-weight: 700;
      font-size: 13px;
      padding: 7px 18px;
      border-radius: 9999px;
      border: 1px solid #FCD34D;
      margin-bottom: 24px;
    }

    .status-badge i {
      color: #D97706;
      animation: spin 3s linear infinite;
    }

    @keyframes spin {
      100% { transform: rotate(360deg); }
    }

    /* Animated Maintenance Illustration */
    .illustration-box {
      width: 110px;
      height: 110px;
      margin: 0 auto 24px;
      background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
      border-radius: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      box-shadow: 0 10px 25px rgba(79, 70, 229, 0.15);
    }

    .illustration-box i.main-icon {
      font-size: 48px;
      color: var(--primary-color);
    }

    .gear-spin {
      position: absolute;
      top: 14px;
      right: 14px;
      font-size: 20px;
      color: var(--accent-color);
      animation: spin 4s linear infinite;
    }

    .wrench-bounce {
      position: absolute;
      bottom: 14px;
      left: 14px;
      font-size: 18px;
      color: #F59E0B;
      animation: bounce 2s infinite ease-in-out;
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }

    h1.heading {
      font-size: clamp(26px, 4vw, 34px);
      font-weight: 800;
      color: #0F172A;
      margin-bottom: 12px;
      letter-spacing: -0.5px;
    }

    p.subtitle {
      font-size: 15.5px;
      color: var(--text-muted);
      line-height: 1.65;
      margin-bottom: 28px;
      max-width: 520px;
      margin-left: auto;
      margin-right: auto;
    }

    /* Progress bar */
    .progress-wrap {
      background: #F1F5F9;
      border-radius: 9999px;
      height: 8px;
      overflow: hidden;
      margin-bottom: 32px;
      position: relative;
    }

    .progress-bar-fill {
      background: linear-gradient(90deg, #4F46E5 0%, #10B981 100%);
      height: 100%;
      width: 65%;
      border-radius: 9999px;
      animation: pulseBar 2.5s infinite alternate ease-in-out;
    }

    @keyframes pulseBar {
      0% { width: 55%; }
      100% { width: 85%; }
    }

    /* Actions */
    .action-btn-group {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .btn-primary-custom {
      background: var(--primary-color);
      color: #ffffff;
      font-weight: 700;
      font-size: 14px;
      padding: 13px 28px;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border: none;
    }

    .btn-primary-custom:hover {
      background: var(--primary-hover);
      color: #ffffff;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
    }

    .btn-secondary-custom {
      background: #F1F5F9;
      color: #334155;
      font-weight: 700;
      font-size: 14px;
      padding: 13px 24px;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border: 1px solid #E2E8F0;
    }

    .btn-secondary-custom:hover {
      background: #E2E8F0;
      color: #0F172A;
      transform: translateY(-2px);
    }

    .footer-note {
      font-size: 12.5px;
      color: #94A3B8;
      margin-top: 28px;
      margin-bottom: 0;
    }
  </style>
</head>
<body>

  <!-- Background Orbs -->
  <div class="glow-orb-1"></div>
  <div class="glow-orb-2"></div>

  <div class="maintenance-card">

    <!-- Status Badge -->
    <div class="status-badge">
      <i class="fa-solid fa-gear"></i>
      <span>System Auto-Recovery Active</span>
    </div>

    <!-- Illustration Box -->
    <div class="illustration-box">
      <i class="fa-solid fa-laptop-code main-icon"></i>
      <i class="fa-solid fa-gear gear-spin"></i>
      <i class="fa-solid fa-wrench wrench-bounce"></i>
    </div>

    <!-- Heading & Text -->
    <h1 class="heading">We Are Working On It!</h1>
    <p class="subtitle">
      Sorry for the interruption. We encountered a momentary hiccup while processing this page, and our engineering team is actively resolving it to bring you back online smoothly.
    </p>

    <!-- Progress Indicator -->
    <div class="progress-wrap">
      <div class="progress-bar-fill"></div>
    </div>

    <!-- Action Buttons -->
    <div class="action-btn-group">
      @php
        $homeRoute = url('/');
        if (request()->is('website-builder*') || request()->is('agency-admin*')) {
            $homeRoute = route('website-builder.index');
        }
      @endphp
      <a href="{{ $homeRoute }}" class="btn-primary-custom">
        <i class="fa-solid fa-house"></i> Go Back to Home
      </a>
      <button onclick="window.location.reload();" class="btn-secondary-custom">
        <i class="fa-solid fa-rotate-right"></i> Refresh Page
      </button>
    </div>

    <p class="footer-note">
      <i class="fa-solid fa-shield-halved text-success me-1"></i> LaunchShop & WebsiteBuilder System Protection Active
    </p>

  </div>

</body>
</html>
