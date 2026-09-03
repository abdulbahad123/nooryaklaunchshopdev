<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $settings->hero_title ?? 'Build Your Website in Just Few Minutes' }} - Website Builder</title>
  <meta name="description" content="{{ $settings->hero_subtitle ?? 'Create beautiful, professional websites in minutes with our intuitive drag-and-drop builder and AI-powered features.' }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --primary: {{ $settings->primary_color ?? '#5B4BF5' }};
      --primary-light: {{ $settings->secondary_color ?? '#7C6CF8' }};
      --primary-soft: #EEF0FD;
      --text-dark: #0F0E17;
      --text-body: #3D3D5C;
      --text-muted: #7B7B9D;
      --border: #E8E8F0;
      --bg-light: #F8F8FC;
      --bg-white: #FFFFFF;
      --hero-dark: #0B0B1E;
      --success-green: #22C55E;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--text-dark);
      overflow-x: hidden;
      line-height: 1.6;
    }

    /* ============================================
       NAVBAR
    ============================================ */
    .wb-nav {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: rgba(11, 11, 30, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255,255,255,0.08);
      padding: 14px 0;
    }
    .wb-nav .container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }
    .wb-logo {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      font-weight: 800;
      font-size: 18px;
      color: #fff;
      white-space: nowrap;
    }
    .wb-logo-icon {
      width: 34px;
      height: 34px;
      background: var(--primary);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      color: #fff;
      flex-shrink: 0;
    }
    .wb-nav-links {
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .wb-nav-links a {
      color: rgba(255,255,255,0.75);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      padding: 6px 14px;
      border-radius: 6px;
      transition: all 0.2s;
    }
    .wb-nav-links a:hover { color: #fff; background: rgba(255,255,255,0.08); }
    .wb-nav-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .btn-login {
      border: 1px solid rgba(255,255,255,0.2);
      color: #fff;
      background: transparent;
      padding: 8px 20px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-login:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .btn-getstarted {
      background: var(--primary);
      color: #fff;
      padding: 8px 20px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.2s;
      white-space: nowrap;
    }
    .btn-getstarted:hover { background: var(--primary-light); color: #fff; transform: translateY(-1px); }
    .wb-hamburger {
      display: none;
      background: none;
      border: none;
      color: #fff;
      font-size: 22px;
      cursor: pointer;
      padding: 4px;
    }
    .mobile-menu {
      display: none;
      flex-direction: column;
      gap: 4px;
      padding: 12px 0;
      border-top: 1px solid rgba(255,255,255,0.08);
      margin-top: 12px;
    }
    .mobile-menu a {
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      padding: 10px 16px;
      border-radius: 8px;
      transition: all 0.2s;
    }
    .mobile-menu a:hover { color: #fff; background: rgba(255,255,255,0.08); }
    .mobile-menu.active { display: flex; }

    /* ============================================
       HERO SECTION
    ============================================ */
    .hero-section {
      background: linear-gradient(135deg, #0B0B1E 0%, #1a1040 40%, #0d1a3a 100%);
      min-height: 600px;
      padding: 70px 0 80px;
      position: relative;
      overflow: hidden;
    }
    .hero-section::before {
      content: '';
      position: absolute;
      top: -200px; left: 50%;
      transform: translateX(-50%);
      width: 900px; height: 900px;
      background: radial-gradient(circle, rgba(91,75,245,0.2) 0%, transparent 65%);
      pointer-events: none;
    }
    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(91,75,245,0.15);
      border: 1px solid rgba(91,75,245,0.35);
      color: #a799ff;
      font-size: 13px;
      font-weight: 600;
      padding: 5px 14px;
      border-radius: 20px;
      margin-bottom: 22px;
    }
    .hero-title {
      font-size: clamp(38px, 5vw, 62px);
      font-weight: 900;
      line-height: 1.1;
      color: #fff;
      margin-bottom: 20px;
      letter-spacing: -1.5px;
    }
    .hero-title .highlight {
      background: linear-gradient(90deg, #6B8EFF 0%, #9B7AFF 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .hero-sub {
      font-size: 16px;
      color: rgba(255,255,255,0.65);
      max-width: 480px;
      line-height: 1.7;
      margin-bottom: 34px;
    }
    .hero-cta-row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 12px;
      margin-bottom: 36px;
    }
    .btn-hero-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--primary);
      color: #fff;
      font-weight: 700;
      font-size: 15px;
      padding: 13px 26px;
      border-radius: 10px;
      text-decoration: none;
      box-shadow: 0 8px 24px rgba(91,75,245,0.4);
      transition: all 0.25s;
    }
    .btn-hero-primary:hover { background: var(--primary-light); color: #fff; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(91,75,245,0.5); }
    .btn-hero-secondary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.25);
      color: #fff;
      font-weight: 600;
      font-size: 15px;
      padding: 13px 26px;
      border-radius: 10px;
      text-decoration: none;
      transition: all 0.25s;
    }
    .btn-hero-secondary:hover { background: rgba(255,255,255,0.18); color: #fff; }
    .hero-trust-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 24px;
    }
    .trust-badge-item {
      display: flex;
      align-items: center;
      gap: 8px;
      color: rgba(255,255,255,0.65);
      font-size: 13px;
      font-weight: 500;
    }
    .trust-badge-item i { color: rgba(255,255,255,0.4); font-size: 15px; }

    /* Hero Mockup */
    .hero-mockup-wrap {
      position: relative;
      padding-left: 0;
    }
    .hero-mockup {
      border-radius: 18px;
      overflow: hidden;
      box-shadow: none;
      position: relative;
      line-height: 0;
    }
    .hero-mockup img {
      width: 100%;
      display: block;
      border-radius: 18px;
    }

    /* ============================================
       WHO IS IT FOR SECTION (light bg, 2-col)
    ============================================ */
    .who-section {
      background: #fff;
      padding: 60px 0;
    }
    .section-label {
      display: inline-block;
      font-size: 12px;
      font-weight: 600;
      color: var(--primary);
      background: #eef0fe;
      padding: 5px 16px;
      border-radius: 20px;
      margin-bottom: 16px;
      letter-spacing: 0;
      text-transform: none;
    }
    .section-heading {
      font-size: clamp(26px, 4vw, 35px);
      font-weight: 800;
      color: var(--text-dark);
      line-height: 1.2;
      margin-bottom: 12px;
    }
    .section-heading span { color: var(--primary); }
    .section-sub {
      font-size: 14px;
      color: var(--text-muted);
      max-width: 440px;
      line-height: 1.6;
      margin-bottom: 32px;
    }
    .audience-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }
    .audience-item {
      background: #fff;
      border:  1.5px solid #d2d4da;
      border-radius: 16px;
      padding: 24px 12px 18px;
      text-align: center;
      transition: all 0.25s;
      box-shadow: 0 4px 16px rgba(0,0,0,0.02);
      cursor: default;
    }
    .audience-item:hover {
      border-color: var(--primary);
      box-shadow: 0 8px 24px rgba(91,75,245,0.12);
      transform: translateY(-3px);
    }
    .audience-icon {
      width: 50px; height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
      font-size: 22px;
    }
    .audience-label { font-size: 13px; font-weight: 700; color: var(--text-dark); }

    /* Visionaries right column */
    .visionaries-title { font-size: clamp(24px,4vw,36px); font-weight: 800; color: var(--text-dark); margin-bottom: 8px; }
    .visionaries-sub { font-size: 14px; color: var(--text-muted); margin-bottom: 24px; max-width: 380px; line-height: 1.55; }
    /* 6 cards in a single row matching left side height */
    .visionaries-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 12px;
    }
    .visionary-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      cursor: pointer;
      transition: transform 0.25s;
    }
    .visionary-card:hover { transform: translateY(-4px); }
    .visionary-card-img-wrap {
      width: 100%;
      height: 275px;
      border-radius: 16px;
      overflow: hidden;
      position: relative;
      background: #0f172a;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .visionary-card-img-wrap img {
      width: 100%; height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.3s;
    }
    .visionary-card:hover .visionary-card-img-wrap img { transform: scale(1.06); }
    .visionary-card-img-wrap::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.6) 100%);
    }
    .visionary-card-icon {
      position: absolute;
      bottom: 12px;
      left: 50%;
      transform: translateX(-50%);
      width: 38px; height: 38px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      color: #fff;
      z-index: 2;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .visionary-card-label {
      font-size: 12px;
      font-weight: 700;
      color: var(--text-dark);
      margin-top: 10px;
      text-align: center;
    }

    /* ============================================
       PROCESS SECTION
    ============================================ */
    .process-section {
      background: #f8f8fd;
      padding: 60px 0;
    }
    .process-header-wrap {
      position: relative;
      text-align: center;
      margin-bottom: 44px;
    }
    .process-header-wrap .btn-start-building {
      position: absolute;
      right: 0;
      top: 0;
    }
    .process-card-col {
      position: relative;
    }
    .process-arrow-next {
      position: absolute;
      right: -14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary);
      font-size: 16px;
      z-index: 5;
    }
    .process-card {
      background: #fff;
      border: 1.5px solid #eef0f6;
      border-radius: 16px;
      padding: 28px 24px;
      height: 100%;
      transition: all 0.25s;
      box-shadow: 0 4px 16px rgba(0,0,0,0.02);
      display: flex;
      flex-direction: column;
    }
    .process-card:hover {
      box-shadow: 0 8px 30px rgba(91,75,245,0.1);
      transform: translateY(-3px);
      border-color: var(--primary);
    }
    .process-card-top {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
    }
    .process-step-num {
      width: 42px; height: 42px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      font-weight: 800;
      color: #fff;
      flex-shrink: 0;
    }
    .process-icon-box {
      width: 44px; height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }
    .process-title {
      font-size: 16px;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 8px;
    }
    .process-desc {
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.55;
      margin: 0;
    }

    /* ============================================
       FEATURES SECTION
    ============================================ */
    .features-section { background: #fff; padding: 40px 0; }
    .feature-item {
      display: flex;
      align-items: flex-start;
      gap: 16px;
      padding: 20px;
      border-radius: 12px;
      transition: all 0.2s;
    }
    .feature-item:hover { background: var(--bg-light); }
    .feature-icon-wrap {
      width: 48px; height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
      background: var(--primary-soft);
      color: var(--primary);
    }
    .feature-icon-wrap.green { background: #DCFCE7; color: #16A34A; }
    .feature-icon-wrap.blue { background: #DBEAFE; color: #2563EB; }
    .feature-icon-wrap.orange { background: #FEF3C7; color: #D97706; }
    .feature-icon-wrap.red { background: #FEE2E2; color: #DC2626; }
    .feature-icon-wrap.teal { background: #CCFBF1; color: #0D9488; }
    .feature-icon-wrap.purple { background: #F3E8FF; color: #9333EA; }
    .feature-title { font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
    .feature-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; }

    /* ============================================
       TEMPLATES SECTION
    ============================================ */
    .templates-section { background: var(--bg-light); padding: 60px 0; }
    .templates-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 40px;
      flex-wrap: wrap;
      gap: 16px;
    }
    .template-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      transition: all 0.25s;
      height: 100%;
    }
    .template-card:hover { box-shadow: 0 12px 36px rgba(91,75,245,0.12); transform: translateY(-4px); }
    .template-thumb {
      height: 200px;
      overflow: hidden;
      position: relative;
      background: #1a1a2e;
    }
    .template-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s;
    }
    .template-card:hover .template-thumb img { transform: scale(1.04); }
    .template-new-badge {
      position: absolute;
      top: 10px; left: 10px;
      background: var(--primary);
      color: #fff;
      font-size: 10px;
      font-weight: 700;
      padding: 3px 8px;
      border-radius: 4px;
    }
    .template-body { padding: 18px 20px 20px; }
    .template-name { font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px; }
    .template-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 16px; }
    .template-actions { display: flex; gap: 8px; }
    .btn-view-demo {
      flex: 1;
      text-align: center;
      border: 1.5px solid var(--border);
      color: var(--text-dark);
      background: transparent;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-view-demo:hover { border-color: var(--primary); color: var(--primary); }
    .btn-purchase {
      flex: 1;
      text-align: center;
      background: var(--primary);
      color: #fff;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-purchase:hover { background: var(--primary-light); color: #fff; }

    /* ============================================
       PRICING SECTION
    ============================================ */
    .pricing-section { background: #fff; padding: 80px 0; }
    .pricing-toggle {
      display: inline-flex;
      align-items: center;
      background: var(--bg-light);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 4px;
      margin-bottom: 48px;
    }
    .pricing-toggle button {
      border: none;
      background: transparent;
      color: var(--text-muted);
      font-size: 14px;
      font-weight: 600;
      padding: 8px 20px;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .pricing-toggle button.active { background: #fff; color: var(--text-dark); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .pricing-card-wrap {
      background: #fff;
      border: 1.5px solid var(--border);
      border-radius: 20px;
      padding: 36px 32px;
      height: 100%;
      position: relative;
      transition: all 0.25s;
    }
    .pricing-card-wrap:hover { box-shadow: 0 12px 36px rgba(91,75,245,0.1); }
    .pricing-card-wrap.popular {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(91,75,245,0.08), 0 16px 48px rgba(91,75,245,0.15);
    }
    .pricing-popular-badge {
      position: absolute;
      top: -14px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--primary);
      color: #fff;
      font-size: 12px;
      font-weight: 700;
      padding: 4px 16px;
      border-radius: 20px;
      white-space: nowrap;
    }
    .pricing-tier-name { font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px; }
    .pricing-tier-sub { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; }
    .pricing-price { font-size: 46px; font-weight: 900; color: var(--text-dark); line-height: 1; }
    .pricing-price sup { font-size: 22px; vertical-align: super; font-weight: 700; }
    .pricing-period { font-size: 14px; color: var(--text-muted); font-weight: 500; margin-left: 4px; }
    .pricing-billing { font-size: 12px; color: var(--text-muted); margin-top: 4px; margin-bottom: 24px; }
    .pricing-divider { border: none; border-top: 1px solid var(--border); margin: 24px 0; }
    .pricing-features { list-style: none; padding: 0; margin-bottom: 28px; }
    .pricing-features li {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      color: var(--text-body);
      margin-bottom: 12px;
    }
    .pricing-features li i { color: var(--primary); font-size: 14px; flex-shrink: 0; }
    .btn-pricing {
      display: block;
      text-align: center;
      padding: 13px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 15px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-pricing.outline { border: 1.5px solid var(--border); color: var(--text-dark); }
    .btn-pricing.outline:hover { border-color: var(--primary); color: var(--primary); }
    .btn-pricing.filled { background: var(--primary); color: #fff; box-shadow: 0 6px 20px rgba(91,75,245,0.3); }
    .btn-pricing.filled:hover { background: var(--primary-light); color: #fff; transform: translateY(-1px); }

    /* ============================================
       TESTIMONIALS SECTION
    ============================================ */
    .testimonials-section { background: var(--bg-light); padding: 60px 0; }
    .testimonial-slider-wrap { position: relative; overflow: hidden; }
    .testimonial-slide {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }
    .testimonial-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px;
      transition: all 0.25s;
    }
    .testimonial-card:hover { box-shadow: 0 8px 28px rgba(91,75,245,0.08); transform: translateY(-3px); }
    .testi-stars { color: #F59E0B; font-size: 14px; margin-bottom: 14px; }
    .testi-text { font-size: 14px; color: var(--text-body); line-height: 1.6; margin-bottom: 18px; font-style: italic; }
    .testi-author { display: flex; align-items: center; gap: 12px; }
    .testi-avatar {
      width: 40px; height: 40px;
      border-radius: 50%;
      background: var(--primary);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-weight: 700;
      font-size: 15px;
      flex-shrink: 0;
      overflow: hidden;
    }
    .testi-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .testi-name { font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .testi-role { font-size: 12px; color: var(--text-muted); }

    /* ============================================
       CTA ROCKET BANNER
    ============================================ */
    .cta-banner-section { background: #fff; padding: 80px 0; }
    .cta-banner {
      background: linear-gradient(135deg, var(--primary) 0%, #7C6CF8 50%, #8b5cf6 100%);
      border-radius: 24px;
      padding: 8px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 32px;
      position: relative;
      overflow: hidden;
    }
    .cta-banner::before {
      content: '';
      position: absolute;
      top: -100px; right: -100px;
      width: 400px; height: 400px;
      background: rgba(255,255,255,0.06);
      border-radius: 50%;
    }
    .cta-banner-content { position: relative; z-index: 1; }
    .cta-banner-title { font-size: clamp(20px, 2.5vw, 28px); font-weight: 900; color: #fff; margin-bottom: 8px; line-height: 1.2; }
    .cta-banner-sub { font-size: 14px; color: rgba(255,255,255,0.8); margin-bottom: 20px; max-width: 420px; }
    .cta-banner-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; }
    .btn-cta-white {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      color: var(--primary);
      font-weight: 700;
      font-size: 15px;
      padding: 12px 24px;
      border-radius: 10px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-cta-white:hover { background: #f0efff; color: var(--primary); transform: translateY(-2px); }
    .btn-cta-outline {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border: 2px solid rgba(255,255,255,0.5);
      color: #fff;
      font-weight: 600;
      font-size: 15px;
      padding: 12px 24px;
      border-radius: 10px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-cta-outline:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .cta-trust-row {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
    }
    .cta-trust-item { display: flex; align-items: center; gap: 6px; color: rgba(255,255,255,0.8); font-size: 13px; font-weight: 500; }
    .cta-trust-item i { color: rgba(255,255,255,0.6); }
    .cta-rocket-art { font-size: 120px; position: relative; z-index: 1; flex-shrink: 0; line-height: 1; }

    /* ============================================
       CONTACT SUPPORT SECTION
    ============================================ */
    .contact-section { background: var(--bg-light); padding: 60px 0; }
    .contact-section .section-heading { text-align: center; margin-bottom: 8px; }
    .contact-section .section-sub { text-align: center; margin: 0 auto 40px; }
    .contact-info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
    .contact-info-item {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 20px;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 12px;
      transition: all 0.2s;
    }
    .contact-info-item:hover { box-shadow: 0 4px 20px rgba(91,75,245,0.08); border-color: var(--primary); }
    .contact-icon-wrap {
      width: 44px; height: 44px;
      border-radius: 10px;
      background: var(--primary-soft);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: var(--primary);
      flex-shrink: 0;
    }
    .contact-info-label { font-size: 13px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
    .contact-info-value { font-size: 13px; color: var(--text-muted); }

    /* ============================================
       FOOTER
    ============================================ */
    .wb-footer { background: var(--hero-dark); padding: 60px 0 32px; }
    .footer-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; font-weight: 800; font-size: 16px; color: #fff; margin-bottom: 14px; }
    .footer-logo-icon { width: 30px; height: 30px; background: var(--primary); border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 14px; color: #fff; }
    .footer-desc { font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.6; max-width: 220px; margin-bottom: 20px; }
    .footer-social { display: flex; gap: 10px; }
    .footer-social a {
      width: 32px; height: 32px;
      border-radius: 8px;
      background: rgba(255,255,255,0.08);
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255,255,255,0.6);
      font-size: 13px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .footer-social a:hover { background: var(--primary); color: #fff; }
    .footer-col-title { font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 16px; }
    .footer-links { list-style: none; padding: 0; }
    .footer-links li { margin-bottom: 10px; }
    .footer-links a { font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s; }
    .footer-links a:hover { color: #fff; }
    .footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.08); margin: 40px 0 24px; }
    .footer-bottom { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
    .footer-bottom span { font-size: 13px; color: rgba(255,255,255,0.35); }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 1200px) {
      .visionaries-grid { grid-template-columns: repeat(6, 1fr); gap: 8px; }
      .visionary-card-img-wrap { height: 230px; }
    }
    @media (max-width: 991px) {
      .visionaries-grid { grid-template-columns: repeat(3, 1fr); gap: 12px; }
      .visionary-card-img-wrap { height: 220px; }
      .process-header-wrap .btn-start-building { position: static; display: inline-block; margin-top: 14px; }
    }
    @media (max-width: 768px) {
      .wb-nav-links { display: none; }
      .wb-hamburger { display: block; }
      .hero-section { padding: 40px 0 50px; }
      .hero-mockup-wrap { padding-left: 0; margin-top: 30px; }
      .who-section { padding: 40px 0; }
      .audience-grid { grid-template-columns: repeat(3, 1fr); gap: 10px; }
      .audience-item { padding: 16px 8px 14px; }
      .audience-icon { width: 42px; height: 44px; font-size: 18px; margin-bottom: 8px; }
      .audience-label { font-size: 12px; }
      .visionaries-grid { grid-template-columns: repeat(3, 1fr); gap: 10px; }
      .visionary-card-img-wrap { height: 180px; }
      .visionary-card-icon { width: 32px; height: 32px; font-size: 14px; bottom: 8px; }
      .visionary-card-label { font-size: 11px; margin-top: 6px; }
      .process-section { padding: 40px 0; }
      .process-arrow-next { display: none; }
      .cta-banner { flex-direction: column; text-align: center; padding: 32px 20px; }
      .cta-banner-sub { max-width: 100%; }
      .cta-trust-row { justify-content: center; }
      .contact-info-grid { grid-template-columns: repeat(2, 1fr); }
      .footer-bottom { flex-direction: column; text-align: center; }
    }
    @media (max-width: 480px) {
      .audience-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
      .visionaries-grid { grid-template-columns: repeat(3, 1fr); gap: 8px; }
      .visionary-card-img-wrap { height: 150px; border-radius: 12px; }
      .visionary-card-icon { width: 28px; height: 28px; font-size: 12px; bottom: 6px; }
      .visionary-card-label { font-size: 10px; margin-top: 4px; }
      .contact-info-grid { grid-template-columns: 1fr; }
      .hero-title { font-size: 28px; }
      .hero-sub { font-size: 14px; }
      .hero-cta-row { flex-direction: column; align-items: stretch; width: 100%; }
      .btn-hero-primary, .btn-hero-secondary { width: 100%; text-align: center; justify-content: center; }
      .cta-banner { padding: 24px 16px; }
      .cta-banner-actions { flex-direction: column; width: 100%; }
      .btn-cta-white, .btn-cta-outline { width: 100%; justify-content: center; }
    }
  </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="wb-nav">
  <div class="container">
    <a href="{{ route('website-builder.index') }}" class="wb-logo">
      <div class="wb-logo-icon"><i class="fa-solid fa-tv"></i></div>
      <span>website builder</span>
    </a>
    <div class="wb-nav-links">
      <a href="#who">For You</a>
      <a href="#process">Process</a>
      <a href="#features">Features</a>
      <a href="#templates">Templates</a>
      <a href="#pricing">Pricing</a>
      <a href="#contact">Contact</a>
    </div>
    <div class="wb-nav-actions">
      <a href="{{ route('website-builder.user.dashboard') }}" class="btn-login">Log In</a>
      <a href="#pricing" class="btn-getstarted">Get Started</a>
      <button class="wb-hamburger" onclick="toggleMobileMenu(this)" aria-label="Menu">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>
  </div>
  <div class="container">
    <div class="mobile-menu" id="mobileMenu">
      <a href="#who">For You</a>
      <a href="#process">Process</a>
      <a href="#features">Features</a>
      <a href="#templates">Templates</a>
      <a href="#pricing">Pricing</a>
      <a href="#contact">Contact</a>
      <a href="{{ route('website-builder.user.dashboard') }}">Log In</a>
      <a href="#pricing" style="background: var(--primary); color: #fff; font-weight: 700;">Get Started</a>
    </div>
  </div>
</nav>

<!-- ===== HERO SECTION ===== -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="hero-badge">
          <i class="fa-solid fa-bolt"></i>
          {{ $settings->hero_badge ?? '⚡ No-coding required' }}
        </div>
        <h1 class="hero-title">
          @php
            $heroTitle = $settings->hero_title ?? "Build Your Website\nin Just Few Minutes";
            // Highlight "Few Minutes" in the title
            $heroTitle = str_replace('Few Minutes', '<span class="highlight">Few Minutes</span>', e($heroTitle));
          @endphp
          {!! nl2br($heroTitle) !!}
        </h1>
        <p class="hero-sub">{{ $settings->hero_subtitle ?? 'Create beautiful, professional websites in minutes with our intuitive drag-and-drop builder and AI-powered features.' }}</p>
        <div class="hero-cta-row">
          <a href="{{ $settings->cta_primary_url ?? '#pricing' }}" class="btn-hero-primary">
            {{ $settings->cta_primary_text ?? 'Get Started Free' }}
            <i class="fa-solid fa-arrow-right"></i>
          </a>
          <a href="{{ $settings->cta_secondary_url ?? '#templates' }}" class="btn-hero-secondary">
            {{ $settings->cta_secondary_text ?? 'View Templates' }}
          </a>
        </div>
        <div class="hero-trust-badges">
          @php $trustBadges = $settings->trust_badges ?? [['icon' => 'shield-check', 'text' => 'No Technical Skills Required'], ['icon' => 'zap', 'text' => 'Instant Setup'], ['icon' => 'layers', 'text' => '10k+ Business Templates']]; @endphp
          @foreach($trustBadges as $badge)
            <div class="trust-badge-item">
              <i class="fa-solid fa-check"></i>
              <span>{{ $badge['text'] }}</span>
            </div>
          @endforeach
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-mockup-wrap">
          <!-- Main Hero Screenshot Image -->
          <div class="hero-mockup">
            @if($settings->hero_image ?? null)
              <img src="{{ asset($settings->hero_image) }}" alt="Website Builder Dashboard Preview" loading="eager">
            @else
              <img src="{{ asset('assets/website_builder/hero_banner.png') }}" alt="Website Builder Dashboard Preview" loading="eager"
                   onerror="this.style.display='none'; this.parentElement.style.minHeight='380px'; this.parentElement.innerHTML += '<div style=\'background:linear-gradient(135deg,#1a1040,#0d1a3a);height:380px;display:flex;align-items:center;justify-content:center;\'><i class=\'fa-solid fa-image\' style=\'font-size:60px;color:rgba(255,255,255,0.2);\'></i></div>';">
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== WHO IS IT FOR + VISIONARIES (split 2-col) ===== -->
<section id="who" class="who-section">
  <div class="container">
    <div class="row g-5">
      <!-- Left: Who Is It For -->
      <div class="col-lg-5">
        <span class="section-label">{{ $settings->who_label ?? "Who it's for" }}</span>
        <h2 class="section-heading">Who Is <span>{{ $settings->who_brand_name ?? 'website builder' }}</span> For?</h2>
        <p style="font-size:14px;font-weight:700;color:var(--text-dark);margin-bottom:10px;">{{ $settings->who_subtitle ?? 'Perfect for Every Business & Creator' }}</p>
        <p class="section-sub" style="margin-bottom:28px;">{{ $settings->who_description ?? "Whether you're launching a personal brand, a portfolio, a local business site, or online store — website builder makes it simple." }}</p>
        <div class="audience-grid">
          @php
            $audiences = $settings->audiences_data ?? [
              ['icon' => 'fa-user-circle', 'title' => 'Freelancers', 'color' => '#5B4BF5'],
              ['icon' => 'fa-rocket',      'title' => 'Startups',    'color' => '#06B6D4'],
              ['icon' => 'fa-briefcase',   'title' => 'Agencies',    'color' => '#F59E0B'],
              ['icon' => 'fa-store',       'title' => 'Shops',       'color' => '#EC4899'],
              ['icon' => 'fa-pen-nib',     'title' => 'Bloggers',    'color' => '#8B5CF6'],
              ['icon' => 'fa-calendar-days','title' => 'Events',     'color' => '#EF4444'],
            ];
          @endphp
          @foreach($audiences as $aud)
            <div class="audience-item">
              <div class="audience-icon" style="background:{{ $aud['color'] }}18;color:{{ $aud['color'] }};">
                <i class="fa-solid {{ $aud['icon'] }}"></i>
              </div>
              <div class="audience-label">{{ $aud['title'] }}</div>
            </div>
          @endforeach
        </div>
      </div>
      <!-- Right: Built for Visionaries — 6 cards in single row -->
      <div class="col-lg-7">
        <span class="section-label">{{ $settings->usecases_label ?? 'Use Cases' }}</span>
        <h2 class="visionaries-title">{{ $settings->usecases_title ?? 'Built for Visionaries' }}</h2>
        <p class="visionaries-sub">{{ $settings->usecases_subtitle ?? "Whether you're a freelancer or a founder, we have the perfect starting point." }}</p>
        <div class="visionaries-grid">
          @php
            $usecases = $settings->usecases_data ?? [
              ['label' => 'Portfolio',  'icon' => 'fa-image',         'color' => '#8B5CF6', 'image' => 'assets/website_builder/wb_card_portfolio.png'],
              ['label' => 'Startup',    'icon' => 'fa-rocket',        'color' => '#06B6D4', 'image' => 'assets/website_builder/wb_card_startup.png'],
              ['label' => 'Agency',     'icon' => 'fa-briefcase',     'color' => '#F59E0B', 'image' => 'assets/website_builder/wb_card_agency.png'],
              ['label' => 'eCommerce',  'icon' => 'fa-cart-shopping', 'color' => '#EC4899', 'image' => 'assets/website_builder/wb_card_ecommerce.png'],
              ['label' => 'Restaurant', 'icon' => 'fa-utensils',      'color' => '#EF4444', 'image' => 'assets/website_builder/wb_card_restaurant.png'],
              ['label' => 'Events',     'icon' => 'fa-calendar',      'color' => '#22C55E', 'image' => 'assets/website_builder/wb_card_events.png'],
            ];
          @endphp
          @foreach($usecases as $uc)
            <div class="visionary-card">
              <div class="visionary-card-img-wrap">
                <img src="{{ asset($uc['image'] ?? '') }}" alt="{{ $uc['label'] }}" loading="lazy"
                     onerror="this.style.display='none'">
                <div class="visionary-card-icon" style="background:{{ $uc['color'] }};">
                  <i class="fa-solid {{ $uc['icon'] }}"></i>
                </div>
              </div>
              <span class="visionary-card-label">{{ $uc['label'] }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== PROCESS SECTION ===== -->
<section id="process" class="process-section">
  <div class="container">
    <div class="process-header-wrap">
      <span class="section-label">Process</span>
      <h2 class="section-heading" style="margin-bottom: 8px;">Launch in 3 Simple Steps</h2>
      <p class="section-sub" style="margin: 0 auto; max-width: 520px;">Stop wrestling with code. Our visual editor makes website building as easy as editing a document.</p>
      <a href="#pricing" class="btn-outline-primary-custom btn-start-building">Start Building <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4 align-items-stretch">
      @php
        $processData = $settings->process_data ?? [
          ['step' => '01', 'title' => 'Choose a Template', 'desc' => 'Select from our gallery of professionally designed, conversion-optimized templates.'],
          ['step' => '02', 'title' => 'Customize Content', 'desc' => 'Use our visual editor to update text, images, and colors to match your brand.'],
          ['step' => '03', 'title' => 'Publish to World', 'desc' => 'Connect your custom domain and go live with a single click. SSL included.'],
        ];
        $stepColors = ['#5B4BF5', '#22C55E', '#00B8D9'];
        $iconBgs    = ['#f0effe', '#e0f2fe', '#dcfce7'];
        $iconColors = ['#5B4BF5', '#0284c7', '#16a34a'];
        $icons      = ['fa-shapes', 'fa-wand-magic-sparkles', 'fa-chart-line'];
      @endphp
      @foreach($processData as $idx => $step)
        <div class="col-md-4 process-card-col mb-3 mb-md-0">
          @if(!$loop->last)
            <div class="process-arrow-next d-none d-md-block"><i class="fa-solid fa-arrow-right"></i></div>
          @endif
          <div class="process-card">
            <div class="process-card-top">
              <div class="process-step-num" style="background: {{ $stepColors[$idx] ?? '#5B4BF5' }}">{{ $step['step'] }}</div>
              <div class="process-icon-box" style="background: {{ $iconBgs[$idx] ?? '#f0effe' }}; color: {{ $iconColors[$idx] ?? '#5B4BF5' }}">
                <i class="fa-solid {{ $icons[$idx] ?? 'fa-cube' }}"></i>
              </div>
            </div>
            <h4 class="process-title">{{ $step['title'] }}</h4>
            <p class="process-desc">{{ $step['desc'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== FEATURES SECTION ===== -->
<section id="features" class="features-section">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-label">Features</span>
      <h2 class="section-heading" style="margin-bottom: 10px;">Everything You Need</h2>
      <p style="color: var(--text-muted); font-size: 15px;">We've packed all the technical heavy lifting into a simple interface.</p>
    </div>
    @php
      $featuresData = $settings->features_data ?? [
        ['icon' => 'fa-mobile-screen', 'title' => 'Mobile Optimized',         'desc' => 'Looks perfect on every screen size.'],
        ['icon' => 'fa-magnifying-glass','title' => 'SEO Ready',              'desc' => 'Built to rank high on Google search.'],
        ['icon' => 'fa-globe',          'title' => 'Custom Domain',           'desc' => 'Connect your own .com instantly.'],
        ['icon' => 'fa-bolt',           'title' => 'Fast Hosting',            'desc' => 'Lightning-fast load times globally.'],
        ['icon' => 'fa-shield-halved',  'title' => 'Secure (SSL)',            'desc' => 'Free security certificate included.'],
        ['icon' => 'fa-chart-line',     'title' => 'Analytics',               'desc' => 'Track your visitors easily.'],
        ['icon' => 'fa-wand-magic-sparkles','title' => 'AI Page Rewriter',   'desc' => 'Regenerate or improve any section content anytime.'],
        ['icon' => 'fa-award',          'title' => 'Client-Ready White Label','desc' => 'Create & manage websites under your own brand.'],
      ];
      $iconColors = ['purple','green','blue','orange','purple','teal','red','green'];
    @endphp
    <div class="row g-2">
      @foreach($featuresData as $i => $feat)
        <div class="col-md-3 col-6">
          <div class="feature-item">
            <div class="feature-icon-wrap {{ $iconColors[$i % count($iconColors)] ?? 'purple' }}">
              <i class="fa-solid {{ $feat['icon'] ?? 'fa-cube' }}"></i>
            </div>
            <div>
              <div class="feature-title">{{ $feat['title'] }}</div>
              <div class="feature-desc">{{ $feat['desc'] }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== TEMPLATES SECTION ===== -->
<section id="templates" class="templates-section">
  <div class="container">
    <div class="templates-header">
      <div>
        <span class="section-label">Templates</span>
        <h2 class="section-heading" style="margin-bottom: 6px;">Start with a Professional Template</h2>
        <p style="color: var(--text-muted); font-size: 14px;">Choose a design you love and make it yours.</p>
      </div>
      <a href="{{ route('website-builder.templates') }}" class="btn-outline-primary-custom">View All Templates <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="row g-4">
      @forelse($templates->take(5) as $tmpl)
        <div class="col-lg-{{ $loop->index < 2 ? '6' : '4' }} col-md-6">
          <div class="template-card">
            <div class="template-thumb">
              <img src="{{ asset($tmpl->preview_image ?? 'images/hero-section.png') }}"
                   onerror="this.style.display='none'; this.parentElement.style.background='linear-gradient(135deg,#1a1040,#0d1a3a)'"
                   alt="{{ $tmpl->name }}" loading="lazy">
              @if($tmpl->is_new ?? false)
                <span class="template-new-badge">NEW</span>
              @endif
            </div>
            <div class="template-body">
              <div class="template-name">{{ $tmpl->name }}</div>
              <div class="template-desc">{{ $tmpl->description ?? 'Professional template with clean design.' }}</div>
              <div class="template-actions">
                <a href="{{ $tmpl->demo_url ?? '#' }}" target="_blank" class="btn-view-demo">View Demo</a>
                <a href="#pricing" class="btn-purchase">Purchase – ${{ $tmpl->price ?? '49' }}</a>
              </div>
            </div>
          </div>
        </div>
      @empty
        @for($i = 0; $i < 5; $i++)
          <div class="col-lg-{{ $i < 2 ? '6' : '4' }} col-md-6">
            <div class="template-card">
              <div class="template-thumb" style="background: linear-gradient(135deg, {{ ['#1a1040','#0d2433','#1a0a3a','#2d1b69','#0d1a3a'][$i] }}, #060b18);">
                <div style="height: 100%; display: flex; align-items: center; justify-content: center;">
                  <i class="fa-solid fa-image" style="font-size: 40px; color: rgba(255,255,255,0.2);"></i>
                </div>
              </div>
              <div class="template-body">
                <div class="template-name">{{ ['Business Classic','Startup Launch','Modern Business','Simple Landing','Creative Agency'][$i] }}</div>
                <div class="template-desc">{{ ['Professional business website template with clean design.','Modern startup template with problem-solution approach.','Contemporary business template with modern design.','Minimal and professional landing template with clean design.','Bold and creative template for agencies and studios.'][$i] }}</div>
                <div class="template-actions">
                  <a href="#" class="btn-view-demo">View Demo</a>
                  <a href="#pricing" class="btn-purchase">Purchase – ${{ [49,49,39,47,51][$i] }}</a>
                </div>
              </div>
            </div>
          </div>
        @endfor
      @endforelse
    </div>
  </div>
</section>

<!-- ===== PRICING SECTION ===== -->
<section id="pricing" class="pricing-section">
  <div class="container">
    <div class="text-center mb-3">
      <span class="section-label">Pricing</span>
      <h2 class="section-heading" style="margin-bottom: 8px;">Simple, Transparent Pricing</h2>
      <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Choose the perfect plan for your needs</p>
      <div class="pricing-toggle">
        <button class="active" id="toggleMonthly" onclick="setPricingMode('monthly')">Monthly</button>
        <button id="toggleYearly" onclick="setPricingMode('yearly')">Yearly <span style="color: #22C55E; font-size: 12px; margin-left: 4px;">(Save 20%)</span></button>
      </div>
    </div>
    <div class="row g-4 justify-content-center align-items-start">
      @php
        $packages = $packages ?? collect([]);
        $defaultPackages = [
          ['name' => 'Starter', 'sub' => 'Perfect for getting started', 'monthly_price' => 9, 'yearly_price' => 7, 'is_popular' => false, 'features' => ['1 Website', '5 GB Storage', 'Custom Domain', 'Basic Support']],
          ['name' => 'Pro', 'sub' => 'Best for growing businesses', 'monthly_price' => 19, 'yearly_price' => 15, 'is_popular' => true, 'features' => ['10 Websites', '50 GB Storage', 'Premium Templates', 'Priority Support']],
          ['name' => 'Business', 'sub' => 'For large businesses & agencies', 'monthly_price' => 39, 'yearly_price' => 31, 'is_popular' => false, 'features' => ['Unlimited Websites', 'Unlimited Storage', 'White Label', '24/7 Support']],
        ];
      @endphp
      @if($packages->count() > 0)
        @foreach($packages as $pkg)
          <div class="col-lg-4 col-md-6">
            <div class="pricing-card-wrap {{ $pkg->is_popular ? 'popular' : '' }}">
              @if($pkg->is_popular)
                <div class="pricing-popular-badge">⭐ Most Popular</div>
              @endif
              <div class="pricing-tier-name">{{ $pkg->name }}</div>
              <div class="pricing-tier-sub">{{ $pkg->description ?? 'Perfect plan for your needs' }}</div>
              <div>
                <span class="pricing-price"><sup>$</sup><span class="price-display" data-monthly="{{ $pkg->monthly_price }}" data-yearly="{{ round($pkg->monthly_price * 0.8) }}">{{ $pkg->monthly_price }}</span></span>
                <span class="pricing-period">/month</span>
              </div>
              <div class="pricing-billing">Billed monthly</div>
              <hr class="pricing-divider">
              <ul class="pricing-features">
                <li><i class="fa-solid fa-check-circle"></i> {{ $pkg->max_websites > 100 ? 'Unlimited' : $pkg->max_websites }} Website(s)</li>
                <li><i class="fa-solid fa-check-circle"></i> {{ $pkg->storage_limit_mb > 100000 ? 'Unlimited' : $pkg->storage_limit_mb.'MB' }} Storage</li>
                <li><i class="fa-solid fa-check-circle"></i> Custom Domain</li>
                <li><i class="fa-solid fa-check-circle"></i> 24/7 Support</li>
              </ul>
              <a href="{{ route('website-builder.user.dashboard') }}" class="btn-pricing {{ $pkg->is_popular ? 'filled' : 'outline' }}">Purchase Now</a>
            </div>
          </div>
        @endforeach
      @else
        @foreach($defaultPackages as $pkg)
          <div class="col-lg-4 col-md-6">
            <div class="pricing-card-wrap {{ $pkg['is_popular'] ? 'popular' : '' }}">
              @if($pkg['is_popular'])
                <div class="pricing-popular-badge">⭐ Most Popular</div>
              @endif
              <div class="pricing-tier-name">{{ $pkg['name'] }}</div>
              <div class="pricing-tier-sub">{{ $pkg['sub'] }}</div>
              <div>
                <span class="pricing-price"><sup>$</sup><span class="price-display" data-monthly="{{ $pkg['monthly_price'] }}" data-yearly="{{ $pkg['yearly_price'] }}">{{ $pkg['monthly_price'] }}</span></span>
                <span class="pricing-period">/month</span>
              </div>
              <div class="pricing-billing">Billed monthly</div>
              <hr class="pricing-divider">
              <ul class="pricing-features">
                @foreach($pkg['features'] as $feat)
                  <li><i class="fa-solid fa-check-circle"></i> {{ $feat }}</li>
                @endforeach
              </ul>
              <a href="#" class="btn-pricing {{ $pkg['is_popular'] ? 'filled' : 'outline' }}">Purchase Now</a>
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS SECTION ===== -->
<section class="testimonials-section">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-label">Testimonials</span>
      <h2 class="section-heading" style="margin-bottom: 8px;">Loved by Thousands of Customers</h2>
    </div>
    @php
      $testimonials = $settings->testimonials_data ?? [
        ['name' => 'Sarah Johnson', 'role' => 'Small Business Owner', 'rating' => 5, 'comment' => '"website builder made it so easy to create our business website. The templates are beautiful and the support is excellent!"', 'avatar' => null],
        ['name' => 'Mike Chen', 'role' => 'Freelance Designer', 'rating' => 5, 'comment' => '"As a freelancer, I needed a professional portfolio fast. website builder delivered exactly what I needed."', 'avatar' => null],
        ['name' => 'Emily Davis', 'role' => 'Marketing Manager', 'rating' => 5, 'comment' => '"The AI tools and ease of use are incredible. I built my entire website in just a few hours!"', 'avatar' => null],
      ];
    @endphp
    <div class="testimonial-slide">
      @foreach($testimonials as $testi)
        <div class="testimonial-card">
          <div class="testi-stars">
            @for($s = 0; $s < ($testi['rating'] ?? 5); $s++)
              <i class="fa-solid fa-star"></i>
            @endfor
          </div>
          <p class="testi-text">{{ $testi['comment'] }}</p>
          <div class="testi-author">
            <div class="testi-avatar">
              @if(isset($testi['avatar']) && $testi['avatar'])
                <img src="{{ asset($testi['avatar']) }}" alt="{{ $testi['name'] }}">
              @else
                {{ strtoupper(substr($testi['name'], 0, 1)) }}
              @endif
            </div>
            <div>
              <div class="testi-name">{{ $testi['name'] }}</div>
              <div class="testi-role">{{ $testi['role'] }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== CTA ROCKET BANNER ===== -->
<section class="cta-banner-section">
  <div class="container">
    <div class="cta-banner">
      <div class="cta-banner-content">
        <h2 class="cta-banner-title">Start Your Professional Website Today</h2>
        <p class="cta-banner-sub">Join thousands of successful businesses who trust website builder for their online presence.</p>
        <div class="cta-banner-actions">
          <a href="{{ $settings->cta_primary_url ?? '#pricing' }}" class="btn-cta-white">Get Started Free <i class="fa-solid fa-arrow-right"></i></a>
          <a href="{{ route('website-builder.templates') }}" class="btn-cta-outline">View Templates</a>
        </div>
        <div class="cta-trust-row">
          <div class="cta-trust-item"><i class="fa-solid fa-check"></i> No credit card required</div>
          <div class="cta-trust-item"><i class="fa-solid fa-check"></i> Free forever plan</div>
          <div class="cta-trust-item"><i class="fa-solid fa-check"></i> Cancel anytime</div>
        </div>
      </div>
      <!-- footer_cta.png as the right-side visual -->
      <div class="cta-right-image" style="position:relative;z-index:1;flex-shrink:0;">
        <img src="{{ asset('assets/website_builder/footer_cta.png') }}"
             alt="Website Builder CTA"
             style="max-width:500px;width:100%;border-radius:16px;">
      </div>
    </div>
  </div>
</section>

<!-- ===== CONTACT & SUPPORT SECTION ===== -->
<section id="contact" class="contact-section">
  <div class="container">
    <h2 class="section-heading text-center" style="margin-bottom: 8px;">Let's Build Something Amazing Together</h2>
    <p class="section-sub text-center mx-auto">Have questions? We're here to help!</p>
    <div class="contact-info-grid">
      <div class="contact-info-item">
        <div class="contact-icon-wrap"><i class="fa-solid fa-envelope"></i></div>
        <div>
          <div class="contact-info-label">Email Us</div>
          <div class="contact-info-value">{{ $settings->contact_email ?? 'hello@websitebuilder.com' }}</div>
        </div>
      </div>
      <div class="contact-info-item">
        <div class="contact-icon-wrap" style="background: #DCFCE7; color: #16A34A;"><i class="fa-solid fa-phone"></i></div>
        <div>
          <div class="contact-info-label">Call Us</div>
          <div class="contact-info-value">{{ $settings->contact_phone ?? '+1 (800) 123-4567' }}</div>
        </div>
      </div>
      <div class="contact-info-item">
        <div class="contact-icon-wrap" style="background: #DBEAFE; color: #2563EB;"><i class="fa-solid fa-comments"></i></div>
        <div>
          <div class="contact-info-label">Live Chat</div>
          <div class="contact-info-value">Available 24/7</div>
        </div>
      </div>
      <div class="contact-info-item">
        <div class="contact-icon-wrap" style="background: #FEF3C7; color: #D97706;"><i class="fa-solid fa-location-dot"></i></div>
        <div>
          <div class="contact-info-label">Visit Us</div>
          <div class="contact-info-value">{{ $settings->contact_address ?? '123 Business St, New York, NY 10001' }}</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="wb-footer">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <a href="{{ route('website-builder.index') }}" class="footer-logo">
          <div class="footer-logo-icon"><i class="fa-solid fa-tv"></i></div>
          <span>website builder</span>
        </a>
        <p class="footer-desc">{{ $settings->footer_text ?? 'The easiest way to build professional websites. No coding required.' }}</p>
        <div class="footer-social">
          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#"><i class="fa-brands fa-twitter"></i></a>
          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="#"><i class="fa-brands fa-instagram"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <div class="footer-col-title">Product</div>
        <ul class="footer-links">
          <li><a href="#features">Features</a></li>
          <li><a href="#templates">Templates</a></li>
          <li><a href="#pricing">Pricing</a></li>
          <li><a href="#">Updates</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <div class="footer-col-title">Company</div>
        <ul class="footer-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="#">Careers</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <div class="footer-col-title">Support</div>
        <ul class="footer-links">
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Documentation</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">Status</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <div class="footer-col-title">Legal</div>
        <ul class="footer-links">
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
          <li><a href="#">Refund Policy</a></li>
        </ul>
      </div>
    </div>
    <hr class="footer-divider">
    <div class="footer-bottom">
      <span>© {{ date('Y') }} website builder. All rights reserved.</span>
      <span>Made with ❤️ for builders everywhere</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Mobile Menu Toggle
  function toggleMobileMenu(btn) {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('active');
    btn.querySelector('i').className = menu.classList.contains('active') ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
  }

  // Close mobile menu when clicking a link
  document.querySelectorAll('#mobileMenu a').forEach(link => {
    link.addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.remove('active');
      document.querySelector('.wb-hamburger i').className = 'fa-solid fa-bars';
    });
  });

  // Pricing Toggle Monthly / Yearly
  function setPricingMode(mode) {
    const isYearly = mode === 'yearly';
    document.getElementById('toggleMonthly').classList.toggle('active', !isYearly);
    document.getElementById('toggleYearly').classList.toggle('active', isYearly);
    document.querySelectorAll('.price-display').forEach(el => {
      el.textContent = isYearly ? el.dataset.yearly : el.dataset.monthly;
    });
    document.querySelectorAll('.pricing-billing').forEach(el => {
      el.textContent = isYearly ? 'Billed yearly' : 'Billed monthly';
    });
  }

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Active nav link on scroll
  const sections = document.querySelectorAll('section[id]');
  window.addEventListener('scroll', () => {
    const scrollY = window.scrollY + 100;
    sections.forEach(sec => {
      const offset = sec.offsetTop;
      const height = sec.offsetHeight;
      const id = sec.getAttribute('id');
      const link = document.querySelector(`.wb-nav-links a[href="#${id}"]`);
      if (link) {
        link.style.color = (scrollY >= offset && scrollY < offset + height) ? '#fff' : '';
      }
    });
  });
</script>

@if($settings->custom_css ?? null)
<style>{!! $settings->custom_css !!}</style>
@endif

</body>
</html>
