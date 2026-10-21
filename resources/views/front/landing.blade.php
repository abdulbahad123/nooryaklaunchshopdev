<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ strip_tags($settings['hero']['title'] ?? 'WhatsApp Operations, Campaigns, and Automation | Whatsappbulk') }}</title>
  <meta name="description" content="{{ $settings['hero']['subtitle_desc'] ?? ($settings['hero']['subtitle'] ?? 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.') }}">

  {{-- Fonts: Plus Jakarta Sans --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  {{-- FontAwesome 6 Icons --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  {{-- Bootstrap 5 Grid & Utilities --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    :root {
      --wa-primary: #059669;
      --wa-primary-hover: #047857;
      --wa-dark-green: #065f46;
      --wa-deep-green: #064e3b;
      --wa-light-mint: #ecfdf5;
      --wa-border-mint: #a7f3d0;
      --wa-bg-mint: #f0fbf5;
      --wa-bg-pricing: #f8fdfa;
      --wa-dark: #0f172a;
      --wa-slate: #1e293b;
      --wa-muted: #64748b;
      --wa-border: #e2e8f0;
      --wa-card-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
      --wa-card-shadow-hover: 0 16px 40px rgba(5, 150, 105, 0.10);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      color: var(--wa-slate);
      background-color: #ffffff;
      overflow-x: hidden;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
    }

    a {
      text-decoration: none;
      transition: all 0.2s ease;
    }

    /* Ambient Hero Background (Soft mint pastel gradient matching Image 2) */
    .hero-wrapper {
      background: linear-gradient(180deg, #e8fbf3 0%, #f2fdf7 45%, #eafbf3 100%);
      position: relative;
      overflow: hidden;
    }
    .hero-glow-1 {
      position: absolute;
      top: -120px;
      right: -100px;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(167, 243, 208, 0.45) 0%, rgba(209, 250, 229, 0.15) 50%, transparent 70%);
      border-radius: 50%;
      filter: blur(50px);
      pointer-events: none;
    }
    .hero-glow-2 {
      position: absolute;
      top: 150px;
      left: -80px;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(167, 243, 208, 0.35) 0%, transparent 70%);
      border-radius: 50%;
      filter: blur(50px);
      pointer-events: none;
    }

    /* Pill Badges */
    .section-pill-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 6px 16px;
      background: rgba(209, 250, 229, 0.9);
      border: 1px solid rgba(110, 231, 183, 0.7);
      color: #065f46;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 0.6px;
      border-radius: 50px;
      text-transform: uppercase;
      margin-bottom: 16px;
    }
    .section-pill-badge i,
    .section-pill-badge svg {
      font-size: 11px;
      color: #059669;
    }
    .section-pill-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #059669;
      display: inline-block;
    }

    /* Typography */
    .section-title {
      font-size: 38px;
      font-weight: 900;
      color: var(--wa-dark);
      line-height: 1.2;
      letter-spacing: -0.025em;
      margin-bottom: 12px;
    }
    .section-subtitle {
      font-size: 15.5px;
      color: var(--wa-muted);
      line-height: 1.6;
      max-width: 640px;
      margin: 0 auto;
      font-weight: 500;
    }
    .highlight-green {
      color: #059669 !important;
    }

    /* Navbar (Seamlessly blends into top hero gradient matching Image 2) */
    .wa-navbar {
      padding: 24px 0 16px 0;
      background: transparent;
      position: relative;
      z-index: 40;
    }
    .brand-logo-text {
      font-size: 22px;
      font-weight: 900;
      color: #0f172a;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      letter-spacing: -0.02em;
    }
    .brand-logo-icon {
      width: 34px;
      height: 34px;
      background: #25D366;
      color: #ffffff;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 19px;
      box-shadow: 0 4px 10px rgba(37, 211, 102, 0.35);
    }
    .brand-logo-text span.brand-name-bulk {
      color: #059669;
    }
    .wa-nav-links {
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .wa-nav-links a {
      color: #475569;
      font-weight: 600;
      font-size: 14.5px;
      padding: 7px 16px;
      border-radius: 30px;
      transition: all 0.2s;
    }
    .wa-nav-links a:hover {
      color: #059669;
    }
    .wa-nav-links a.active-pill {
      background: #059669;
      color: #ffffff !important;
      font-weight: 700;
      padding: 7px 22px;
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    }
    .wa-auth-group {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .btn-wa-signin {
      color: #0f172a;
      font-weight: 700;
      font-size: 14.5px;
      padding: 8px 12px;
    }
    .btn-wa-signin:hover {
      color: #059669;
    }
    .btn-wa-signup {
      background: #059669;
      color: #ffffff !important;
      font-weight: 700;
      font-size: 14px;
      padding: 10px 24px;
      border-radius: 50px;
      box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }
    .btn-wa-signup:hover {
      background: #047857;
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(5, 150, 105, 0.45);
    }
    .nav-mobile-toggle {
      background: #ffffff;
      border: 1px solid var(--wa-border);
      border-radius: 10px;
      padding: 8px 12px;
      font-size: 18px;
      color: #0f172a;
      cursor: pointer;
    }

    /* Mobile Drawer */
    #mobileMenuDrawer {
      display: none;
      background: #ffffff;
      border-radius: 18px;
      padding: 18px;
      margin-top: 14px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
      border: 1px solid var(--wa-border);
    }
    #mobileMenuDrawer.show {
      display: block;
    }
    #mobileMenuDrawer a {
      display: block;
      padding: 10px 14px;
      font-size: 14.5px;
      font-weight: 600;
      color: #334155;
      border-radius: 8px;
    }
    #mobileMenuDrawer a:hover,
    #mobileMenuDrawer a.active-pill {
      background: #ecfdf5;
      color: #059669;
    }

    /* Hero Left Elements */
    .hero-headline {
      font-size: 52px;
      font-weight: 900;
      color: #0f172a;
      line-height: 1.12;
      letter-spacing: -0.03em;
      margin-bottom: 18px;
    }
    .hero-subtitle-bold {
      font-size: 17px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 8px;
      line-height: 1.45;
    }
    .hero-subtitle-desc {
      font-size: 14px;
      color: #64748b;
      line-height: 1.65;
      max-width: 510px;
      margin-bottom: 30px;
      font-weight: 400;
    }
    .btn-hero-primary {
      background: #059669;
      color: #ffffff !important;
      font-weight: 700;
      font-size: 15px;
      padding: 14px 30px;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 10px 25px rgba(5, 150, 105, 0.35);
      transition: all 0.25s ease;
    }
    .btn-hero-primary:hover {
      background: #047857;
      transform: translateY(-2px);
      box-shadow: 0 14px 30px rgba(5, 150, 105, 0.45);
    }
    .btn-hero-secondary {
      background: #ffffff;
      color: #0f172a !important;
      font-weight: 700;
      font-size: 15px;
      padding: 14px 28px;
      border-radius: 50px;
      border: 1px solid #e2e8f0;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
      transition: all 0.25s ease;
    }
    .btn-hero-secondary:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      transform: translateY(-2px);
    }

    /* 3 Hero Metric Feature Boxes */
    .hero-stat-card {
      background: #ffffff;
      border-radius: 18px;
      padding: 12px 14px;
      border: 1px solid rgba(5, 150, 105, 0.15);
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      display: flex;
      align-items: center;
      gap: 12px;
      height: 100%;
    }
    .hero-stat-icon-box {
      width: 42px;
      height: 42px;
      min-width: 42px;
      background: #ecfdf5;
      color: #059669;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }
    .hero-stat-num {
      font-size: 24px;
      font-weight: 900;
      color: #0f172a;
      line-height: 1;
      letter-spacing: -0.02em;
    }
    .hero-stat-desc {
      font-size: 11px;
      color: #64748b;
      font-weight: 600;
      line-height: 1.25;
      margin-top: 3px;
    }

    /* Hero Right Graphic Mockup Frame & Hand-Drawn Doodles */
    .hero-mockup-wrapper {
      position: relative;
      padding: 10px 0 20px 0;
    }
    /* SVG Hand-Drawn Doodles (Image 2) */
    .doodle-crown {
      position: absolute;
      top: -12px;
      left: 36%;
      width: 48px;
      height: 30px;
      color: #059669;
      pointer-events: none;
      z-index: 20;
    }
    .doodle-star-left {
      position: absolute;
      top: -6px;
      left: 18%;
      width: 22px;
      height: 22px;
      color: #059669;
      pointer-events: none;
      z-index: 20;
    }
    .doodle-star-right {
      position: absolute;
      top: 52%;
      right: -8px;
      width: 24px;
      height: 24px;
      color: #059669;
      pointer-events: none;
      z-index: 20;
    }
    .doodle-star-right-small {
      position: absolute;
      top: 62%;
      right: 18px;
      width: 14px;
      height: 14px;
      color: #059669;
      opacity: 0.7;
      pointer-events: none;
      z-index: 20;
    }
    .doodle-curly {
      position: absolute;
      bottom: 6px;
      right: 215px;
      width: 45px;
      height: 35px;
      color: #059669;
      pointer-events: none;
      z-index: 20;
    }

    /* Tablet Bezel Outer Frame (Image 2) */
    .tablet-bezel-frame {
      border: 7px solid #0f172a;
      border-radius: 34px;
      background: #0f172a;
      box-shadow: 0 30px 70px rgba(15, 23, 42, 0.22);
      overflow: hidden;
      position: relative;
    }
    .tablet-screen {
      background: #ffffff;
      border-radius: 26px;
      overflow: hidden;
    }
    .tablet-header-bar {
      background: #ffffff;
      border-bottom: 1px solid #f1f5f9;
      padding: 10px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .tablet-header-logo {
      display: flex;
      align-items: center;
      gap: 7px;
    }
    .tablet-header-user {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      font-weight: 700;
      color: #334155;
    }
    .tablet-user-avatar {
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: #f97316;
      color: #ffffff;
      font-size: 10px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Tablet Body (Two-column layout) */
    .tablet-body-grid {
      display: grid;
      grid-template-columns: 140px 1fr;
      padding: 14px;
      gap: 12px;
      background: #ffffff;
    }
    .tablet-sidebar {
      border-right: 1px solid #f1f5f9;
      padding-right: 10px;
    }
    .tablet-nav-link {
      font-size: 11px;
      font-weight: 600;
      color: #64748b;
      padding: 5px 8px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 3px;
    }
    .tablet-nav-link.active {
      background: #ecfdf5;
      color: #059669;
      font-weight: 700;
    }
    .tablet-nav-link i {
      font-size: 10.5px;
      width: 14px;
      text-align: center;
    }

    /* Tablet Content Right */
    .tablet-stat-card {
      background: #f8fafc;
      border-radius: 10px;
      padding: 8px 10px;
      border: 1px solid #f1f5f9;
    }
    .tablet-stat-title {
      font-size: 9px;
      font-weight: 600;
      color: #94a3b8;
      text-transform: capitalize;
    }
    .tablet-stat-val {
      font-size: 15px;
      font-weight: 900;
      color: #0f172a;
      line-height: 1.1;
      margin-top: 2px;
    }
    .tablet-stat-badge {
      font-size: 9px;
      font-weight: 700;
      color: #059669;
    }

    /* Area Chart inside Mockup */
    .tablet-chart-box {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 10px;
      padding: 8px 10px;
      margin-top: 8px;
      margin-bottom: 8px;
    }
    .tablet-campaign-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 4px 0;
      border-bottom: 1px solid #f1f5f9;
      font-size: 9.5px;
      font-weight: 600;
    }

    /* Overlapping Vertical Smartphone Mockup (Bottom Right of Image 2) */
    .floating-phone-frame {
      position: absolute;
      bottom: -22px;
      right: -15px;
      width: 195px;
      border: 6px solid #0f172a;
      border-radius: 36px;
      background: #ffffff;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.22);
      padding: 12px 10px;
      z-index: 25;
      text-align: center;
    }
    .phone-speaker-notch {
      width: 44px;
      height: 4px;
      background: #0f172a;
      border-radius: 10px;
      margin: 0 auto 8px auto;
    }
    .phone-circle-wa {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: #25D366;
      color: #ffffff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);
      margin-bottom: 4px;
    }
    .phone-stat-title {
      font-size: 10px;
      font-weight: 700;
      color: #64748b;
    }
    .phone-stat-big {
      font-size: 28px;
      font-weight: 900;
      color: #f97316;
      line-height: 1;
      margin: 2px 0 6px 0;
      letter-spacing: -0.02em;
    }
    .phone-bars-container {
      display: flex;
      align-items: flex-end;
      justify-content: center;
      gap: 5px;
      height: 44px;
      padding-top: 6px;
      border-top: 1px solid #f1f5f9;
    }
    .phone-bar {
      width: 10px;
      border-radius: 3px 3px 0 0;
    }
    .phone-corner-wa-badge {
      position: absolute;
      bottom: -8px;
      right: -8px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #25D366;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      border: 2.5px solid #ffffff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
    }

    /* ============================================================== */
    /* SECTION 2: LIVE MODULE STACK (Image 3 Top Match)               */
    /* ============================================================== */
    .section-stack-wrapper {
      background: var(--wa-bg-mint);
      padding: 90px 0;
    }
    .stack-card {
      background: #ffffff;
      border-radius: 26px;
      padding: 30px 26px;
      border: 1px solid #eef2f6;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 275px;
      transition: all 0.3s ease;
    }
    .stack-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 36px rgba(5, 150, 105, 0.10);
      border-color: rgba(5, 150, 105, 0.25);
    }
    .stack-icon-box {
      width: 48px;
      height: 48px;
      border-radius: 16px;
      background: #059669;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      margin-bottom: 22px;
      box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25);
    }
    .stack-tag {
      font-size: 10px;
      font-weight: 800;
      color: #94a3b8;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      margin-bottom: 6px;
    }
    .stack-card-title {
      font-size: 20px;
      font-weight: 900;
      color: var(--wa-dark);
      margin-bottom: 8px;
      line-height: 1.25;
    }
    .stack-card-desc {
      font-size: 13px;
      color: var(--wa-muted);
      line-height: 1.55;
      font-weight: 500;
    }
    .stack-arrow-btn {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #059669;
      color: #ffffff !important;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      margin-top: 24px;
      transition: all 0.2s;
    }
    .stack-card:hover .stack-arrow-btn {
      transform: scale(1.12);
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4);
    }

    /* ============================================================== */
    /* SECTION 3: PRODUCT MODULES (Image 3 Bottom Match)              */
    /* ============================================================== */
    .section-modules-wrapper {
      background: #ffffff;
      padding: 95px 0;
      border-top: 1px solid #f1f5f9;
    }
    .module-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 22px;
      padding: 24px 22px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 215px;
      transition: all 0.25s ease;
    }
    .module-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 14px 32px rgba(0, 0, 0, 0.07);
      border-color: rgba(5, 150, 105, 0.35);
    }
    .module-icon-box {
      width: 44px;
      height: 44px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: #ffffff;
      margin-bottom: 16px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }
    .module-card-title {
      font-size: 17px;
      font-weight: 800;
      color: var(--wa-dark);
      margin-bottom: 8px;
    }
    .module-card-desc {
      font-size: 12.5px;
      color: var(--wa-muted);
      line-height: 1.55;
      font-weight: 500;
    }
    .module-arrow-mini {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #f1f5f9;
      color: #64748b;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      margin-top: 16px;
      align-self: flex-end;
      transition: all 0.2s;
    }
    .module-card:hover .module-arrow-mini {
      background: #059669;
      color: #ffffff;
      transform: scale(1.1);
    }

    /* ============================================================== */
    /* SECTION 4: PRICING (Image 4 Top Match)                         */
    /* ============================================================== */
    .section-pricing-wrapper {
      background: var(--wa-bg-pricing);
      padding: 95px 0;
      border-top: 1px solid #f1f5f9;
      position: relative;
    }
    /* Hand-Drawn Doodle Curved Arrows pointing inwards */
    .pricing-header-relative {
      position: relative;
      display: inline-block;
    }
    .pricing-arrow-svg-left {
      position: absolute;
      top: 50%;
      left: -70px;
      transform: translateY(-50%);
      width: 55px;
      height: 28px;
      color: #059669;
      pointer-events: none;
    }
    .pricing-arrow-svg-right {
      position: absolute;
      top: 50%;
      right: -70px;
      transform: translateY(-50%);
      width: 55px;
      height: 28px;
      color: #059669;
      pointer-events: none;
    }

    .pricing-tier-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 24px;
      padding: 30px 24px;
      box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      transition: all 0.3s ease;
    }
    .pricing-tier-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
    }
    .pricing-tier-card.featured-card {
      border: 2px solid #059669;
      box-shadow: 0 16px 40px rgba(5, 150, 105, 0.12);
      transform: translateY(-6px);
    }
    .pricing-popular-badge {
      position: absolute;
      top: -14px;
      left: 50%;
      transform: translateX(-50%);
      background: #059669;
      color: #ffffff;
      font-size: 11px;
      font-weight: 800;
      padding: 4px 14px;
      border-radius: 50px;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
      white-space: nowrap;
    }
    .pricing-tier-icon {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
      margin-bottom: 14px;
    }
    .pricing-tier-name {
      font-size: 21px;
      font-weight: 900;
      color: var(--wa-dark);
      margin-bottom: 2px;
    }
    .pricing-tier-sub {
      font-size: 12.5px;
      color: #94a3b8;
      font-weight: 600;
      margin-bottom: 16px;
    }
    .pricing-tier-price-row {
      display: flex;
      align-items: baseline;
      gap: 4px;
      margin-bottom: 22px;
    }
    .pricing-tier-val {
      font-size: 40px;
      font-weight: 900;
      color: var(--wa-dark);
      line-height: 1;
      letter-spacing: -0.02em;
    }
    .pricing-tier-term {
      font-size: 13.5px;
      color: #94a3b8;
      font-weight: 600;
    }
    .pricing-features-list {
      list-style: none;
      padding: 0;
      margin: 0 0 28px 0;
      flex-grow: 1;
    }
    .pricing-features-list li {
      font-size: 13px;
      color: #334155;
      font-weight: 600;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .pricing-features-list li i {
      color: #059669;
      font-size: 13px;
    }
    .btn-pricing-action {
      width: 100%;
      padding: 12px 18px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 700;
      text-align: center;
      display: block;
      transition: all 0.25s ease;
    }
    .btn-pricing-white {
      background: #ffffff;
      color: #0f172a !important;
      border: 1px solid #e2e8f0;
    }
    .btn-pricing-white:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
    }
    .btn-pricing-green {
      background: #059669;
      color: #ffffff !important;
      border: 1px solid #059669;
      box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3);
    }
    .btn-pricing-green:hover {
      background: #047857;
    }
    .btn-pricing-dark {
      background: #0f172a;
      color: #ffffff !important;
      border: 1px solid #0f172a;
    }
    .btn-pricing-dark:hover {
      background: #1e293b;
    }

    /* ============================================================== */
    /* SECTION 5: TESTIMONIALS (Image 4 Middle Match)                 */
    /* ============================================================== */
    .section-testi-wrapper {
      background: #eefbf4;
      padding: 95px 0;
      border-top: 1px solid #f1f5f9;
    }
    .testi-card {
      background: #ffffff;
      border-radius: 24px;
      padding: 28px 24px;
      border: 1px solid #eef2f6;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .testi-quote-mark {
      font-size: 38px;
      font-family: Georgia, serif;
      font-weight: 900;
      color: #0f172a;
      line-height: 1;
      margin-bottom: 12px;
      display: block;
    }
    .testi-text {
      font-size: 13.5px;
      color: #334155;
      line-height: 1.6;
      font-weight: 500;
      margin-bottom: 24px;
    }
    .testi-author-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 16px;
      border-top: 1px solid #f1f5f9;
    }
    .testi-author-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #a7f3d0;
    }
    .testi-stars-row {
      color: #f59e0b;
      font-size: 12px;
      display: flex;
      gap: 2px;
    }

    /* ============================================================== */
    /* SECTION 6: FAQS (Image 4 Bottom Match)                         */
    /* ============================================================== */
    .section-faqs-wrapper {
      background: #ffffff;
      padding: 95px 0;
      border-top: 1px solid #f1f5f9;
    }
    .faq-item-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      margin-bottom: 14px;
      overflow: hidden;
      transition: all 0.2s ease;
    }
    .faq-item-card:hover {
      border-color: #cbd5e1;
    }
    .faq-btn {
      width: 100%;
      background: none;
      border: none;
      padding: 16px 20px;
      text-align: left;
      font-size: 14.5px;
      font-weight: 700;
      color: #0f172a;
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
    }
    .faq-symbol {
      font-size: 18px;
      font-weight: 800;
      color: #94a3b8;
      width: 20px;
      text-align: center;
    }
    .faq-body {
      padding: 0 20px 18px 20px;
      font-size: 13.5px;
      color: #64748b;
      line-height: 1.6;
      border-top: 1px solid #f8fafc;
      padding-top: 12px;
      display: none;
    }
    .faq-item-card.is-open .faq-body {
      display: block;
    }

    /* ============================================================== */
    /* SECTION 7: BOTTOM CTA BANNER (Image 4 Bottom Match)            */
    /* ============================================================== */
    .section-bottom-cta-wrapper {
      background: #ffffff;
      padding: 50px 0 70px 0;
    }
    .bottom-cta-banner {
      background: linear-gradient(135deg, #047857 0%, #065f46 50%, #047857 100%);
      border-radius: 28px;
      padding: 44px 48px;
      color: #ffffff;
      position: relative;
      overflow: hidden;
      box-shadow: 0 25px 60px rgba(6, 95, 70, 0.25);
    }
    .cta-wa-circle-icon {
      width: 82px;
      height: 82px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.12);
      border: 1.5px solid rgba(255, 255, 255, 0.25);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 44px;
      color: #ffffff;
    }
    .btn-cta-getstarted {
      background: #ffffff;
      color: #064e3b !important;
      font-weight: 800;
      font-size: 14.5px;
      padding: 13px 28px;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      transition: all 0.25s;
    }
    .btn-cta-getstarted:hover {
      background: #f8fafc;
      transform: translateY(-2px);
    }
    .cta-corner-wa-badge {
      position: absolute;
      bottom: 16px;
      right: 18px;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: #25D366;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* ============================================================== */
    /* SECTION 8: FOOTER (Image 4 Bottom Match)                       */
    /* ============================================================== */
    .landing-footer {
      background: #ffffff;
      border-top: 1px solid #f1f5f9;
      padding: 50px 0 35px 0;
    }
    .footer-link-item {
      color: #475569;
      font-size: 14px;
      font-weight: 600;
      margin: 0 14px;
    }
    .footer-link-item:hover {
      color: #059669;
    }
    .footer-social-circle {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      color: #475569;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      margin-left: 8px;
      transition: all 0.2s;
    }
    .footer-social-circle:hover {
      background: #059669;
      color: #ffffff;
      border-color: #059669;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
      .hero-headline {
        font-size: 38px;
      }
      .pricing-arrow-svg-left,
      .pricing-arrow-svg-right {
        display: none;
      }
      .floating-phone-frame {
        position: relative;
        right: auto;
        bottom: auto;
        margin: 20px auto 0 auto;
      }
      .doodle-curly {
        display: none;
      }
      .bottom-cta-banner {
        padding: 32px 24px;
      }
    }
    @media (max-width: 575.98px) {
      .hero-headline {
        font-size: 30px;
      }
      .hero-stat-card {
        padding: 10px;
      }
      .hero-stat-num {
        font-size: 20px;
      }
      .hero-stat-desc {
        font-size: 10px;
      }
    }
  </style>
</head>
<body>

  @php
    $hero = $settings['hero'] ?? [];
    $stack = $settings['stack'] ?? [];
    $modules = $settings['modules'] ?? [];
    $pricing = $settings['pricing'] ?? [];
    $testimonials = $settings['testimonials'] ?? [];
    $faqs = $settings['faqs'] ?? [];
    $ctaFooter = $settings['cta_footer'] ?? [];

    // Robust fallbacks to get_option for multi-system compatibility
    $brandName = $ctaFooter['brand_name'] ?? get_option('brand_name', 'Whatsappbulk');
    $tagline   = $ctaFooter['tagline'] ?? get_option('landing_footer_tagline', 'WhatsApp Marketing & Automation Tool');

    // Title formatting: if superadmin supplies HTML tags (like <span style="..."> or <br>), render as-is;
    // Otherwise automatically highlight 'campaigns,' in vibrant emerald green (#059669)
    $rawHeroTitle = $hero['title'] ?? get_option('landing_hero_title', 'WhatsApp operations,<br><span style="color: #059669;">campaigns,</span> and<br>automation');
    if (!str_contains($rawHeroTitle, '<span') && !str_contains($rawHeroTitle, '<br')) {
        $formattedHeroTitle = str_ireplace('campaigns,', '<span class="highlight-green">campaigns,</span>', e($rawHeroTitle));
    } else {
        $formattedHeroTitle = $rawHeroTitle;
    }
  @endphp

  <!-- ============================================================
       TOP AMBIENT HERO & NAVBAR (Image 2 Match)
       ============================================================ -->
  <div class="hero-wrapper">
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>

    <!-- Live Dynamic Navbar -->
    <header class="wa-navbar">
      <div class="container">
        <div class="d-flex align-items-center justify-content-between">
          
          <!-- Brand Logo -->
          <a href="{{ route('front.landing') }}" class="brand-logo-text">
            <span class="brand-logo-icon"><i class="fab fa-whatsapp"></i></span>
            <span>Whatsapp<span class="brand-name-bulk">bulk</span></span>
          </a>

          <!-- Desktop Navigation Menu Pills -->
          <nav class="wa-nav-links d-none d-lg-flex">
            <a href="{{ route('front.landing') }}" class="active-pill">Home</a>
            <a href="#features">Features</a>
            <a href="#pricing">Pricing</a>
            <a href="#faqs">FAQs</a>
            <a href="{{ \Illuminate\Support\Facades\Route::has('front.blogs') ? route('front.blogs') : url('/blogs') }}">Blog</a>
            <a href="{{ \Illuminate\Support\Facades\Route::has('front.contact') ? route('front.contact') : url('/contact') }}">Contact</a>
          </nav>

          <!-- Right Auth Action Buttons -->
          <div class="wa-auth-group d-none d-sm-flex">
            @if(auth()->check())
              <a href="{{ url('/admin/dashboard') }}" class="btn-wa-signup">
                <span>Dashboard</span> <i class="fas fa-arrow-right"></i>
              </a>
            @else
              <a href="{{ \Illuminate\Support\Facades\Route::has('user.login') ? route('user.login') : url('/login') }}" class="btn-wa-signin">Sign In</a>
              <a href="{{ $hero['btn1_url'] ?? url('/register') }}" class="btn-wa-signup">
                <span>Sign Up</span> <i class="fas fa-arrow-right"></i>
              </a>
            @endif
          </div>

          <!-- Mobile Hamburger Toggle -->
          <div class="d-lg-none">
            <button class="nav-mobile-toggle" id="mobileToggleBtn" aria-label="Toggle navigation">
              <i class="fas fa-bars"></i>
            </button>
          </div>

        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobileMenuDrawer">
          <a href="{{ route('front.landing') }}" class="active-pill">Home</a>
          <a href="#features">Features</a>
          <a href="#pricing">Pricing</a>
          <a href="#faqs">FAQs</a>
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.blogs') ? route('front.blogs') : url('/blogs') }}">Blog</a>
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.contact') ? route('front.contact') : url('/contact') }}">Contact</a>
          <div class="pt-3 mt-2 border-top d-flex flex-column gap-2">
            <a href="{{ \Illuminate\Support\Facades\Route::has('user.login') ? route('user.login') : url('/login') }}" class="text-center font-weight-bold">Sign In</a>
            <a href="{{ $hero['btn1_url'] ?? url('/register') }}" class="btn-wa-signup justify-content-center text-white">Sign Up →</a>
          </div>
        </div>

      </div>
    </header>

    <!-- Hero Main Section -->
    <section class="pt-4 pb-5" id="hero">
      <div class="container pt-2 pb-5">
        <div class="row align-items-center g-5">
          
          <!-- Left Column: Copy & Actions -->
          <div class="col-lg-6">
            
            <!-- Pill Badge -->
            <div class="section-pill-badge">
              <i class="fas fa-bolt"></i>
              <span>{{ $hero['badge'] ?? get_option('landing_hero_badge', 'ALL-IN-ONE WHATSAPP MARKETING TOOL') }}</span>
            </div>

            <!-- Main Heading with Highlight -->
            <h1 class="hero-headline">
              {!! $formattedHeroTitle !!}
            </h1>

            <!-- Subtitles (Bold Heading + Descriptive Paragraph) -->
            <p class="hero-subtitle-bold">
              {{ $hero['subtitle_bold'] ?? get_option('landing_hero_subtitle_bold', 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.') }}
            </p>
            <p class="hero-subtitle-desc">
              {{ $hero['subtitle_desc'] ?? ($hero['subtitle'] ?? get_option('landing_hero_subtitle_desc', 'Built around the modules teams actually use every day: bulk outreach, smart replies, auto replies, chatbots, contacts, participant export, profile lookups, and reporting.')) }}
            </p>

            <!-- Dual CTA Action Buttons -->
            <div class="d-flex flex-wrap gap-3 mb-5">
              <a href="{{ $hero['btn1_url'] ?? get_option('landing_hero_btn1_url', '/pricing') }}" class="btn-hero-primary">
                <span>{{ $hero['btn1_text'] ?? get_option('landing_hero_btn1_text', 'Create your workspace') }}</span>
                <i class="fas fa-arrow-right"></i>
              </a>
              <a href="{{ $hero['btn2_url'] ?? get_option('landing_hero_btn2_url', '#modules') }}" class="btn-hero-secondary">
                <span>{{ $hero['btn2_text'] ?? get_option('landing_hero_btn2_text', 'Explore modules') }}</span>
              </a>
            </div>

            <!-- 3 Stat Feature Highlight Boxes (Image 2 Match) -->
            <div class="row g-2 g-sm-3">
              <div class="col-4">
                <div class="hero-stat-card">
                  <div class="hero-stat-icon-box"><i class="fas fa-box-archive"></i></div>
                  <div>
                    <div class="hero-stat-num">{{ $hero['stat1_val'] ?? get_option('landing_stat1_number', '8') }}</div>
                    <div class="hero-stat-desc">{{ $hero['stat1_lbl'] ?? get_option('landing_stat1_label', 'Operational modules included') }}</div>
                  </div>
                </div>
              </div>

              <div class="col-4">
                <div class="hero-stat-card">
                  <div class="hero-stat-icon-box"><i class="fas fa-link"></i></div>
                  <div>
                    <div class="hero-stat-num">{{ $hero['stat2_val'] ?? get_option('landing_stat2_number', '1') }}</div>
                    <div class="hero-stat-desc">{{ $hero['stat2_lbl'] ?? get_option('landing_stat2_label', 'Unified WhatsApp workflow') }}</div>
                  </div>
                </div>
              </div>

              <div class="col-4">
                <div class="hero-stat-card">
                  <div class="hero-stat-icon-box"><i class="fas fa-bolt"></i></div>
                  <div>
                    <div class="hero-stat-num">{{ $hero['stat3_val'] ?? get_option('landing_stat3_number', '24/7') }}</div>
                    <div class="hero-stat-desc">{{ $hero['stat3_lbl'] ?? get_option('landing_stat3_label', 'Automation & reply coverage') }}</div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- Right Column: Dashboard Mockup + Mobile Phone + Hand-drawn Doodles (Image 2) -->
          <div class="col-lg-6">
            <div class="hero-mockup-wrapper">
              
              <!-- Hand-Drawn SVG Doodles matching Image 2 -->
              <!-- 1. Crown doodle on top of tablet -->
              <svg class="doodle-crown" viewBox="0 0 48 30" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 22 L14 8 L24 20 L34 8 L44 22" />
              </svg>
              <!-- 2. Sparkle Star (top left) -->
              <svg class="doodle-star-left" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0L14 9L23 12L14 15L12 24L10 15L1 12L10 9Z" />
              </svg>
              <!-- 3. Sparkle Stars (right of phone) -->
              <svg class="doodle-star-right" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0L14 9L23 12L14 15L12 24L10 15L1 12L10 9Z" />
              </svg>
              <svg class="doodle-star-right-small" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0L14 9L23 12L14 15L12 24L10 15L1 12L10 9Z" />
              </svg>
              <!-- 4. Curly loop doodle (bottom) -->
              <svg class="doodle-curly" viewBox="0 0 50 40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M5 25 Q15 5 25 18 T38 20 T46 32" />
              </svg>

              <!-- Outer Tablet Bezel Frame -->
              <div class="tablet-bezel-frame">
                <div class="tablet-screen">
                  
                  <!-- Screen Header Bar -->
                  <div class="tablet-header-bar">
                    <div class="tablet-header-logo">
                      <div style="width: 18px; height: 18px; border-radius: 50%; background: #25D366; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 10px;">
                        <i class="fab fa-whatsapp"></i>
                      </div>
                      <span style="font-size: 12.5px; font-weight: 800; color: #059669;">Whatsappbulk</span>
                      <span style="color: #cbd5e1; font-size: 11px;">|</span>
                      <span style="font-size: 11.5px; font-weight: 700; color: #1e293b;">Dashboard</span>
                    </div>
                    <div class="tablet-header-user">
                      <div class="tablet-user-avatar">A</div>
                      <span>Hi, Admin</span>
                      <i class="fas fa-chevron-down" style="font-size: 9px; color: #94a3b8;"></i>
                    </div>
                  </div>

                  <!-- Two-Column Interior Body -->
                  <div class="tablet-body-grid">
                    
                    <!-- Left Sidebar Nav -->
                    <div class="tablet-sidebar">
                      <div class="tablet-nav-link active"><i class="fas fa-chart-pie"></i> Dashboard</div>
                      <div class="tablet-nav-link"><i class="fas fa-bullhorn"></i> Bulk Campaigns</div>
                      <div class="tablet-nav-link"><i class="fas fa-wand-magic-sparkles"></i> AI Smart Reply</div>
                      <div class="tablet-nav-link"><i class="fas fa-rotate-left"></i> Auto Reply</div>
                      <div class="tablet-nav-link"><i class="fas fa-robot"></i> Chatbot</div>
                      <div class="tablet-nav-link"><i class="fas fa-address-book"></i> Contacts</div>
                      <div class="tablet-nav-link"><i class="fas fa-file-export"></i> Export Participants</div>
                      <div class="tablet-nav-link"><i class="fas fa-id-card"></i> Profile Info</div>
                      <div class="tablet-nav-link"><i class="fas fa-chart-column"></i> Reports</div>
                    </div>

                    <!-- Right Main Content -->
                    <div>
                      <!-- 4 Stats in 2x2 Grid -->
                      <div class="row g-2">
                        <div class="col-6">
                          <div class="tablet-stat-card">
                            <div class="tablet-stat-title">Total Messages</div>
                            <div class="tablet-stat-val">{{ $hero['total_messages'] ?? '125,890' }}</div>
                            <div class="tablet-stat-badge">▲ 20.4%</div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="tablet-stat-card">
                            <div class="tablet-stat-title">Delivered</div>
                            <div class="tablet-stat-val">{{ $hero['delivered'] ?? '118,420' }}</div>
                            <div class="tablet-stat-badge">▲ 16.1%</div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="tablet-stat-card">
                            <div class="tablet-stat-title">Replied</div>
                            <div class="tablet-stat-val">{{ $hero['replied'] ?? '28,460' }}</div>
                            <div class="tablet-stat-badge">▲ 14.2%</div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="tablet-stat-card">
                            <div class="tablet-stat-title">Active Contacts</div>
                            <div class="tablet-stat-val">{{ $hero['active_contacts'] ?? '96,350' }}</div>
                            <div class="tablet-stat-badge">▲ 12.0%</div>
                          </div>
                        </div>
                      </div>

                      <!-- Message Performance Area Chart -->
                      <div class="tablet-chart-box">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <span style="font-size: 10px; font-weight: 700; color: #334155;">Message Performance</span>
                          <span style="font-size: 8.5px; font-weight: 700; padding: 2px 6px; border-radius: 12px; background: #ffedd5; color: #ea580c;">Last 30 Days</span>
                        </div>
                        <svg class="w-100" style="height: 48px;" viewBox="0 0 300 65" preserveAspectRatio="none">
                          <defs>
                            <linearGradient id="areaGradMockup" x1="0" y1="0" x2="0" y2="1">
                              <stop offset="0%" stop-color="#f97316" stop-opacity="0.35"/>
                              <stop offset="100%" stop-color="#f97316" stop-opacity="0.0"/>
                            </linearGradient>
                          </defs>
                          <path d="M0,50 Q40,40 80,25 T160,30 T240,15 T300,28 L300,65 L0,65 Z" fill="url(#areaGradMockup)"/>
                          <path d="M0,50 Q40,40 80,25 T160,30 T240,15 T300,28" fill="none" stroke="#f97316" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                      </div>

                      <!-- Recent Campaigns List -->
                      <div>
                        <div class="tablet-campaign-row">
                          <span style="color: #0f172a;"><i class="fas fa-star text-warning me-1" style="font-size: 8.5px;"></i> Product Launch</span>
                          <span class="badge" style="background: #ecfdf5; color: #059669; font-size: 8.5px;">Completed</span>
                          <span style="color: #64748b;">12,450 <i class="fas fa-check text-success" style="font-size: 8px;"></i></span>
                        </div>
                        <div class="tablet-campaign-row">
                          <span style="color: #0f172a;"><i class="fas fa-bullhorn text-warning me-1" style="font-size: 8.5px;"></i> Festival Offer</span>
                          <span class="badge" style="background: #ffedd5; color: #c2410c; font-size: 8.5px;">Sending</span>
                          <span style="color: #64748b;">25,600</span>
                        </div>
                        <div class="tablet-campaign-row" style="border: none;">
                          <span style="color: #0f172a;"><i class="fas fa-headset text-primary me-1" style="font-size: 8.5px;"></i> Customer Support</span>
                          <span class="badge" style="background: #eff6ff; color: #2563eb; font-size: 8.5px;">Active</span>
                          <span style="color: #64748b;">18,220</span>
                        </div>
                      </div>

                    </div>

                  </div>

                </div>
              </div>

              <!-- Overlapping Smartphone Mockup (Bottom Right of Image 2) -->
              <div class="floating-phone-frame">
                <div class="phone-speaker-notch"></div>
                
                <div class="phone-circle-wa">
                  <i class="fab fa-whatsapp"></i>
                </div>
                <div class="phone-stat-title">Campaign Overview</div>
                <div class="phone-stat-big">{{ $hero['delivery_rate'] ?? '95.8%' }}</div>

                <!-- Multi-bar Chart with Orange & Green bars -->
                <div class="phone-bars-container">
                  <div class="phone-bar" style="height: 18px; background: #fb923c;"></div>
                  <div class="phone-bar" style="height: 30px; background: #f97316;"></div>
                  <div class="phone-bar" style="height: 22px; background: #fb923c;"></div>
                  <div class="phone-bar" style="height: 38px; background: #ea580c;"></div>
                  <div class="phone-bar" style="height: 26px; background: #f97316;"></div>
                  <div class="phone-bar" style="height: 42px; background: #10b981;"></div>
                </div>

                <!-- Floating Corner WhatsApp Circle -->
                <div class="phone-corner-wa-badge">
                  <i class="fab fa-whatsapp"></i>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </section>

  </div>

  <!-- ============================================================
       SECTION 2: LIVE MODULE STACK (4 Cards, Image 3 Top Match)
       ============================================================ -->
  <section class="section-stack-wrapper" id="features">
    <div class="container">
      
      <!-- Section Header -->
      <div class="text-center mb-5">
        <div class="section-pill-badge">
          <span class="section-pill-dot"></span>
          <span>{{ $stack['badge'] ?? get_option('landing_stack_badge', 'LIVE MODULE STACK') }}</span>
        </div>
        <h2 class="section-title">
          {!! $stack['title'] ?? get_option('landing_stack_title', 'Whatsappbulk Workspace') !!}
        </h2>
        <p class="section-subtitle">
          {{ $stack['subtitle'] ?? get_option('landing_stack_subtitle', 'Everything you need to run WhatsApp at scale') }}
        </p>
      </div>

      <!-- 4 Stack Cards Grid -->
      <div class="row g-4">
        @php
          $defaultCards = [
              ['tag' => 'CAMPAIGN CONTROL', 'title' => 'Bulk Campaigns', 'desc' => 'Segment contacts, send at scale, and track response flow.', 'icon' => 'fas fa-bullhorn', 'url' => '#pricing'],
              ['tag' => 'INBOUND HANDLING', 'title' => 'AI + Auto Reply', 'desc' => 'Combine automation with faster human response quality.', 'icon' => 'fas fa-comments', 'url' => '#pricing'],
              ['tag' => 'LEAD FLOW', 'title' => 'Chatbot + Contacts', 'desc' => 'Capture, qualify, and manage audience data in one place.', 'icon' => 'fas fa-users', 'url' => '#pricing'],
              ['tag' => 'VISIBILITY', 'title' => 'Reports + Export', 'desc' => 'Export participants and measure what the team actually ships.', 'icon' => 'fas fa-chart-line', 'url' => '#pricing'],
          ];
          $activeCards = !empty($stack['cards']) ? $stack['cards'] : $defaultCards;
        @endphp

        @foreach ($activeCards as $cIdx => $card)
          <div class="col-md-6 col-lg-3">
            <div class="stack-card">
              <div>
                <div class="stack-icon-box">
                  <i class="{{ $card['icon'] ?? 'fas fa-bullhorn' }}"></i>
                </div>
                <div class="stack-tag">{{ $card['tag'] ?? '' }}</div>
                <h3 class="stack-card-title">{{ $card['title'] ?? '' }}</h3>
                <p class="stack-card-desc">{{ $card['desc'] ?? '' }}</p>
              </div>
              <div>
                <a href="{{ $card['url'] ?? '#pricing' }}" class="stack-arrow-btn">
                  <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 3: PRODUCT MODULES (8 Cards, Image 3 Bottom Match)
       ============================================================ -->
  <section class="section-modules-wrapper" id="modules">
    <div class="container">
      
      <!-- Section Header -->
      <div class="text-center mb-5">
        <div class="section-pill-badge">
          <span class="section-pill-dot"></span>
          <span>{{ $modules['badge'] ?? get_option('landing_modules_badge', 'PRODUCT MODULES') }}</span>
        </div>
        @php
          $rawModTitle = $modules['title'] ?? get_option('landing_modules_title', 'Built from the WhatsApp features teams actually need');
          if (!str_contains($rawModTitle, '<span')) {
              $formattedModTitle = str_ireplace('WhatsApp features', '<span class="highlight-green">WhatsApp features</span>', e($rawModTitle));
          } else {
              $formattedModTitle = $rawModTitle;
          }
        @endphp
        <h2 class="section-title">{!! $formattedModTitle !!}</h2>
        <p class="section-subtitle">
          {{ $modules['subtitle'] ?? get_option('landing_modules_subtitle', 'Every module supports a real operational job, from outreach and automation to contact management and reporting.') }}
        </p>
      </div>

      <!-- 8 Cards Grid (2 rows x 4 columns) -->
      <div class="row g-4">
        @php
          $defaultModules = [
              ['title' => 'AI Smart Reply', 'desc' => 'Generate fast, context-aware WhatsApp replies so agents can handle conversations with less manual effort.', 'icon' => 'fas fa-wand-magic-sparkles', 'color' => '#059669'],
              ['title' => 'Auto Reply', 'desc' => 'Trigger automatic responses for inbound messages, off-hours support, lead capture, and common request handling.', 'icon' => 'fas fa-rotate-left', 'color' => '#f97316'],
              ['title' => 'Bulk Campaigns', 'desc' => 'Launch high-volume WhatsApp campaigns to segmented audiences while keeping timing and response flow under control.', 'icon' => 'fas fa-bullhorn', 'color' => '#f97316'],
              ['title' => 'Chatbot', 'desc' => 'Build conversational flows that qualify leads, answer questions, and move contacts into the next step automatically.', 'icon' => 'fas fa-robot', 'color' => '#f59e0b'],
              ['title' => 'Contacts', 'desc' => 'Manage contact lists, segments, and campaign targets from one structured database.', 'icon' => 'fas fa-address-book', 'color' => '#f97316'],
              ['title' => 'Export Participants', 'desc' => 'Extract participants from WhatsApp groups for outreach, qualification, migration, and audience building workflows.', 'icon' => 'fas fa-users', 'color' => '#059669'],
              ['title' => 'Profile Info', 'desc' => 'Retrieve WhatsApp profile information quickly to enrich lead context and improve handoff quality.', 'icon' => 'fas fa-id-card', 'color' => '#f97316'],
              ['title' => 'Reports', 'desc' => 'Track campaign output, reply activity, and workflow performance with clear reporting for real operational decisions.', 'icon' => 'fas fa-chart-line', 'color' => '#f97316'],
          ];
          $activeModules = !empty($modules['items']) ? $modules['items'] : $defaultModules;
        @endphp

        @foreach ($activeModules as $mIdx => $m)
          <div class="col-md-6 col-lg-3">
            <div class="module-card">
              <div>
                <div class="module-icon-box" style="background: {{ $m['color'] ?? '#059669' }};">
                  <i class="{{ $m['icon'] ?? 'fas fa-check' }}"></i>
                </div>
                <h4 class="module-card-title">{{ $m['title'] ?? '' }}</h4>
                <p class="module-card-desc">{{ $m['desc'] ?? '' }}</p>
              </div>
              <div>
                <span class="module-arrow-mini"><i class="fas fa-arrow-right"></i></span>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 4: PRICING (4 TIERS, Image 4 Top Match)
       ============================================================ -->
  <section class="section-pricing-wrapper" id="pricing">
    <div class="container">
      
      <!-- Section Header with Inward Curved Green Doodle Arrows -->
      <div class="text-center mb-5">
        <div class="section-pill-badge">
          <span class="section-pill-dot"></span>
          <span>{{ $pricing['badge'] ?? get_option('landing_pricing_badge', 'PRICING') }}</span>
        </div>
        
        <div class="d-flex align-items-center justify-content-center">
          <div class="pricing-header-relative">
            <!-- Left curved hand-drawn arrow -->
            <svg class="pricing-arrow-svg-left d-none d-md-block" viewBox="0 0 60 30" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <path d="M55,22 C38,6 20,6 8,22 M8,22 L15,14 M8,22 L18,24" />
            </svg>

            <h2 class="section-title m-0">
              {{ $pricing['title'] ?? get_option('landing_pricing_title', 'Simple and transparent pricing') }}
            </h2>

            <!-- Right curved hand-drawn arrow -->
            <svg class="pricing-arrow-svg-right d-none d-md-block" viewBox="0 0 60 30" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <path d="M5,22 C22,6 40,6 52,22 M52,22 L45,14 M52,22 L42,24" />
            </svg>
          </div>
        </div>

        <p class="section-subtitle mt-2">
          {{ $pricing['subtitle'] ?? get_option('landing_pricing_subtitle', 'Choose the plan that fits your business needs. Upgrade or downgrade anytime.') }}
        </p>
      </div>

      <!-- 4 Pricing Cards -->
      <div class="row g-4 align-items-stretch">
        @php
          $defaultPlans = [
              [
                  'name' => 'Starter',
                  'sub' => 'For individuals and small businesses',
                  'price' => '$19',
                  'term' => '/month',
                  'featured' => false,
                  'badge' => '',
                  'icon' => 'fas fa-cube',
                  'icon_bg' => '#ecfdf5',
                  'icon_color' => '#059669',
                  'btn_text' => 'Get Started',
                  'btn_url' => url('/register'),
                  'features' => ['1 WhatsApp number', '5,000 messages/month', 'Basic automation', 'Standard support'],
              ],
              [
                  'name' => 'Professional',
                  'sub' => 'For growing teams',
                  'price' => '$49',
                  'term' => '/month',
                  'featured' => true,
                  'badge' => 'Most Popular',
                  'icon' => 'fas fa-star',
                  'icon_bg' => '#ecfdf5',
                  'icon_color' => '#059669',
                  'btn_text' => 'Get Started',
                  'btn_url' => url('/register'),
                  'features' => ['3 WhatsApp numbers', '25,000 messages/month', 'All modules included', 'Advanced automation', 'Priority support'],
              ],
              [
                  'name' => 'Business',
                  'sub' => 'For established businesses',
                  'price' => '$99',
                  'term' => '/month',
                  'featured' => false,
                  'badge' => '',
                  'icon' => 'fas fa-building',
                  'icon_bg' => '#fffbeb',
                  'icon_color' => '#d97706',
                  'btn_text' => 'Get Started',
                  'btn_url' => url('/register'),
                  'features' => ['10 WhatsApp numbers', '100,000 messages/month', 'All modules included', 'Team collaboration', 'Advanced reporting', 'Priority support'],
              ],
              [
                  'name' => 'Enterprise',
                  'sub' => 'For large organizations',
                  'price' => 'Custom',
                  'term' => '',
                  'featured' => false,
                  'badge' => '',
                  'icon' => 'fas fa-crown',
                  'icon_bg' => '#eff6ff',
                  'icon_color' => '#2563eb',
                  'btn_text' => 'Contact Sales',
                  'btn_url' => url('/contact'),
                  'features' => ['Unlimited numbers', 'Custom message volume', 'All modules included', 'Dedicated manager', 'Custom integrations', 'SLA & premium support'],
              ],
          ];
          $activePlans = !empty($pricing['plans']) ? $pricing['plans'] : $defaultPlans;
        @endphp

        @foreach ($activePlans as $pIdx => $plan)
          @php
            $isFeatured = !empty($plan['featured']) || $pIdx === 1;
            $planName = $plan['name'] ?? '';
            $isEnterprise = stripos($planName, 'enterprise') !== false || ($plan['price'] ?? '') === 'Custom';
            
            // Icon styling per tier
            $iconClass = $plan['icon'] ?? ($pIdx === 0 ? 'fas fa-cube' : ($pIdx === 1 ? 'fas fa-star' : ($pIdx === 2 ? 'fas fa-building' : 'fas fa-crown')));
            $iconBg = $plan['icon_bg'] ?? ($pIdx === 0 || $pIdx === 1 ? '#ecfdf5' : ($pIdx === 2 ? '#fffbeb' : '#eff6ff'));
            $iconColor = $plan['icon_color'] ?? ($pIdx === 0 || $pIdx === 1 ? '#059669' : ($pIdx === 2 ? '#d97706' : '#2563eb'));
          @endphp

          <div class="col-md-6 col-lg-3">
            <div class="pricing-tier-card {{ $isFeatured ? 'featured-card' : '' }}">
              
              @if ($isFeatured)
                <div class="pricing-popular-badge">{{ $plan['badge'] ?? 'Most Popular' }}</div>
              @endif

              <div>
                <div class="pricing-tier-icon" style="background: {{ $iconBg }}; color: {{ $iconColor }};">
                  <i class="{{ $iconClass }}"></i>
                </div>

                <h3 class="pricing-tier-name">{{ $plan['name'] ?? '' }}</h3>
                <div class="pricing-tier-sub">{{ $plan['sub'] ?? '' }}</div>

                <div class="pricing-tier-price-row">
                  <span class="pricing-tier-val">{{ $plan['price'] ?? '' }}</span>
                  @if (!empty($plan['term']))
                    <span class="pricing-tier-term">{{ $plan['term'] }}</span>
                  @endif
                </div>

                <ul class="pricing-features-list">
                  @foreach ($plan['features'] ?? [] as $feat)
                    <li>
                      <i class="fas fa-check"></i>
                      <span>{{ $feat }}</span>
                    </li>
                  @endforeach
                </ul>
              </div>

              <div>
                @if ($isFeatured)
                  <a href="{{ $plan['btn_url'] ?? url('/register') }}" class="btn-pricing-action btn-pricing-green">
                    {{ $plan['btn_text'] ?? 'Get Started' }}
                  </a>
                @elseif ($isEnterprise)
                  <a href="{{ $plan['btn_url'] ?? url('/contact') }}" class="btn-pricing-action btn-pricing-dark">
                    {{ $plan['btn_text'] ?? 'Contact Sales' }}
                  </a>
                @else
                  <a href="{{ $plan['btn_url'] ?? url('/register') }}" class="btn-pricing-action btn-pricing-white">
                    {{ $plan['btn_text'] ?? 'Get Started' }}
                  </a>
                @endif
              </div>

            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 5: TESTIMONIALS (3 Cards, Image 4 Middle Match)
       ============================================================ -->
  <section class="section-testi-wrapper" id="testimonials">
    <div class="container">
      
      <!-- Section Header -->
      <div class="text-center mb-5">
        <div class="section-pill-badge">
          <span class="section-pill-dot"></span>
          <span>{{ $testimonials['badge'] ?? get_option('landing_testi_badge', 'TESTIMONIALS') }}</span>
        </div>
        <h2 class="section-title">
          {{ $testimonials['title'] ?? get_option('landing_testi_title', 'What our customers say') }}
        </h2>
        <p class="section-subtitle">
          {{ $testimonials['subtitle'] ?? get_option('landing_testi_subtitle', 'Trusted by businesses worldwide to streamline their WhatsApp operations.') }}
        </p>
      </div>

      <!-- 3 Testimonial Cards -->
      <div class="row g-4">
        @php
          $defaultReviews = [
              [
                  'quote' => 'Whatsappbulk has completely transformed how we handle customer communication. Our response time improved by 70%!',
                  'name' => 'Priya Sharma',
                  'role' => 'Marketing Manager, RetailHub',
                  'stars' => 0,
                  'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=120&auto=format&fit=crop&q=80',
              ],
              [
                  'quote' => 'The bulk campaign feature is a game-changer. We can now reach thousands of customers in minutes with excellent delivery rates.',
                  'name' => 'Rahul Mehta',
                  'role' => 'Founder, GrowKart',
                  'stars' => 5,
                  'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=120&auto=format&fit=crop&q=80',
              ],
              [
                  'quote' => 'Easy to use, powerful features, and great support. Highly recommended for any business using WhatsApp.',
                  'name' => 'Sneha Patel',
                  'role' => 'Operations Head, Foodies',
                  'stars' => 5,
                  'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=120&auto=format&fit=crop&q=80',
              ],
          ];
          $activeReviews = !empty($testimonials['reviews']) ? $testimonials['reviews'] : $defaultReviews;
        @endphp

        @foreach ($activeReviews as $rIdx => $rev)
          <div class="col-md-4">
            <div class="testi-card">
              <div>
                <span class="testi-quote-mark">“</span>
                <p class="testi-text">"{{ $rev['quote'] ?? '' }}"</p>
              </div>

              <div class="testi-author-row">
                <div class="d-flex align-items-center gap-3">
                  @if (!empty($rev['avatar']))
                    <img src="{{ $rev['avatar'] }}" alt="{{ $rev['name'] ?? 'User' }}" class="testi-author-avatar">
                  @else
                    <div class="testi-author-avatar bg-light text-success d-flex align-items-center justify-content-center fw-bold">
                      {{ substr($rev['name'] ?? 'U', 0, 1) }}
                    </div>
                  @endif
                  <div>
                    <div class="fw-bold text-dark" style="font-size: 14px; line-height: 1.2;">{{ $rev['name'] ?? '' }}</div>
                    <div class="text-muted" style="font-size: 11.5px;">{{ $rev['role'] ?? '' }}</div>
                  </div>
                </div>

                @if(!empty($rev['stars']) && $rev['stars'] > 0)
                  <div class="testi-stars-row">
                    @for ($s = 0; $s < ($rev['stars'] ?? 5); $s++)
                      <i class="fas fa-star"></i>
                    @endfor
                  </div>
                @endif
              </div>

            </div>
          </div>
        @endforeach
      </div>

      <!-- Slider Dots Indicator (Image 4 Match: o • o o) -->
      <div class="d-flex justify-content-center align-items-center gap-2 mt-4 pt-3">
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
        <span style="width: 22px; height: 8px; border-radius: 20px; background: #059669; display: inline-block;"></span>
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 6: FAQS (2-Column Accordion, Image 4 Bottom Match)
       ============================================================ -->
  <section class="section-faqs-wrapper" id="faqs">
    <div class="container">
      
      <!-- Section Header -->
      <div class="text-center mb-5">
        <div class="section-pill-badge">
          <span class="section-pill-dot"></span>
          <span>{{ $faqs['badge'] ?? get_option('landing_faqs_badge', 'FAQS') }}</span>
        </div>
        <h2 class="section-title">
          {{ $faqs['title'] ?? get_option('landing_faqs_title', 'Frequently asked questions') }}
        </h2>
        <p class="section-subtitle">
          {{ $faqs['subtitle'] ?? get_option('landing_faqs_subtitle', 'Get answers to common questions about Whatsappbulk.') }}
        </p>
      </div>

      <!-- 2-Column Accordion Layout matching Image 4 -->
      <div class="row g-4">
        @php
          $defaultFaqs = [
              ['q' => 'What is Whatsappbulk?', 'a' => 'Whatsappbulk is an all-in-one WhatsApp marketing tool that helps you manage campaigns, automate replies, handle contacts, and track performance — all in one workspace.'],
              ['q' => 'Is it safe to use?', 'a' => 'Yes, our platform employs anti-ban mechanisms, customizable message delays, and adheres to standard messaging policies to protect your accounts.'],
              ['q' => 'Can I upgrade my plan later?', 'a' => 'Absolutely. You can upgrade, downgrade, or cancel your subscription at any time directly from your billing settings.'],
              ['q' => 'Do you offer a free trial?', 'a' => 'Yes, we provide a free starter tier so you can test campaigns and automation features before committing.'],
              ['q' => 'What payment methods do you accept?', 'a' => 'We accept major credit and debit cards, UPI, PayPal, Stripe, and multiple international payment gateways.'],
              ['q' => 'Can I use multiple WhatsApp numbers?', 'a' => 'Yes, our Professional and Business plans support multiple concurrent WhatsApp numbers and team members.'],
              ['q' => 'Do you provide customer support?', 'a' => 'Our dedicated support team is available 24/7 via live chat, email, and WhatsApp to help you set up and scale.'],
          ];
          $activeFaqs = !empty($faqs['items']) ? $faqs['items'] : $defaultFaqs;
          
          // Image 4 has exactly 3 questions in left column and 4 in right column
          $leftFaqs = array_slice($activeFaqs, 0, 3);
          $rightFaqs = array_slice($activeFaqs, 3);
        @endphp

        <!-- Left Column -->
        <div class="col-lg-6">
          @foreach ($leftFaqs as $lIdx => $f)
            <div class="faq-item-card {{ $lIdx === 0 ? 'is-open' : '' }}">
              <button type="button" class="faq-btn">
                <span>{{ $f['q'] ?? '' }}</span>
                <span class="faq-symbol">{{ $lIdx === 0 ? '−' : '+' }}</span>
              </button>
              <div class="faq-body">
                {{ $f['a'] ?? '' }}
              </div>
            </div>
          @endforeach
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
          @foreach ($rightFaqs as $rIdx => $f)
            <div class="faq-item-card">
              <button type="button" class="faq-btn">
                <span>{{ $f['q'] ?? '' }}</span>
                <span class="faq-symbol">+</span>
              </button>
              <div class="faq-body">
                {{ $f['a'] ?? '' }}
              </div>
            </div>
          @endforeach
        </div>

      </div>

    </div>
  </section>

  <!-- ============================================================
       SECTION 7: BOTTOM CTA BANNER (Image 4 Bottom Match)
       ============================================================ -->
  <section class="section-bottom-cta-wrapper">
    <div class="container">
      <div class="bottom-cta-banner">
        <div class="row align-items-center g-4">
          
          <!-- Left WhatsApp Circle Icon -->
          <div class="col-lg-2 text-center text-lg-start">
            <div class="cta-wa-circle-icon mx-auto mx-lg-0">
              <i class="fab fa-whatsapp"></i>
            </div>
          </div>

          <!-- Center Content -->
          <div class="col-lg-6 text-center text-lg-start">
            <h3 class="fw-bold mb-2 text-white" style="font-size: 30px; line-height: 1.25;">
              {{ $ctaFooter['cta_title'] ?? get_option('landing_cta_title', 'Ready to scale your WhatsApp marketing?') }}
            </h3>
            <p class="mb-0 text-white-50" style="font-size: 14px; line-height: 1.6; max-width: 580px;">
              {{ $ctaFooter['cta_subtitle'] ?? get_option('landing_cta_subtitle', 'Connect your WhatsApp account in seconds. Start sending bulk campaigns, automating customer replies, and converting more leads with zero coding required.') }}
            </p>
          </div>

          <!-- Right Buttons -->
          <div class="col-lg-4 text-center text-lg-end">
            <div class="d-inline-flex flex-column align-items-center align-items-lg-end gap-2">
              <a href="{{ $ctaFooter['cta_btn1_url'] ?? get_option('landing_cta_btn_url', '/pricing') }}" class="btn-cta-getstarted">
                <span>{{ $ctaFooter['cta_btn1_txt'] ?? get_option('landing_cta_btn_text', 'Get Started Free') }}</span>
                <i class="fas fa-arrow-right"></i>
              </a>
              <a href="{{ $ctaFooter['cta_btn2_url'] ?? '#pricing' }}" class="text-white-50 small text-decoration-underline" style="font-size: 13px;">
                {{ $ctaFooter['cta_btn2_txt'] ?? 'View Pricing Plans' }} &rarr;
              </a>
            </div>
          </div>

        </div>

        <!-- Floating Corner WhatsApp Badge -->
        <div class="cta-corner-wa-badge d-none d-md-flex">
          <i class="fab fa-whatsapp"></i>
        </div>

      </div>
    </div>
  </section>

  <!-- ============================================================
       SECTION 8: FOOTER (Image 4 Bottom Match)
       ============================================================ -->
  <footer class="landing-footer">
    <div class="container">
      
      <!-- Top Row: Logo, Links, Socials -->
      <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4 pb-4 border-bottom">
        
        <!-- Brand & Tagline -->
        <div class="text-center text-lg-start">
          <a href="{{ route('front.landing') }}" class="brand-logo-text mb-1 d-inline-flex">
            <span class="brand-logo-icon" style="width: 30px; height: 30px; font-size: 16px;"><i class="fab fa-whatsapp"></i></span>
            <span>Whatsapp<span class="brand-name-bulk">bulk</span></span>
          </a>
          <div class="text-muted small" style="font-size: 12px; font-weight: 600;">
            {{ $tagline }}
          </div>
        </div>

        <!-- Center Nav Links -->
        <nav class="d-flex flex-wrap align-items-center justify-content-center">
          <a href="{{ route('front.landing') }}" class="footer-link-item">Home</a>
          <a href="#features" class="footer-link-item">Features</a>
          <a href="#pricing" class="footer-link-item">Pricing</a>
          <a href="#faqs" class="footer-link-item">FAQs</a>
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.blogs') ? route('front.blogs') : url('/blogs') }}" class="footer-link-item">Blog</a>
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.contact') ? route('front.contact') : url('/contact') }}" class="footer-link-item">Contact</a>
        </nav>

        <!-- Social Media Icons (Facebook, X, LinkedIn, YouTube) -->
        <div class="d-flex align-items-center justify-content-center">
          <a href="{{ get_option('social_page_facebook', 'https://facebook.com') }}" target="_blank" rel="noopener" class="footer-social-circle" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="{{ get_option('social_page_x', 'https://twitter.com') }}" target="_blank" rel="noopener" class="footer-social-circle" aria-label="X">
            <i class="fab fa-x-twitter"></i>
          </a>
          <a href="https://linkedin.com" target="_blank" rel="noopener" class="footer-social-circle" aria-label="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a href="https://youtube.com" target="_blank" rel="noopener" class="footer-social-circle" aria-label="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
        </div>

      </div>

      <!-- Bottom Row: Copyright & Legal -->
      <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between pt-4 text-muted gap-2" style="font-size: 12.5px;">
        <div>
          {{ $ctaFooter['copyright'] ?? '© 2026 Whatsappbulk. All Rights Reserved.' }}
        </div>
        <div class="d-flex gap-3 align-items-center">
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.privacy-policy') ? route('front.privacy-policy') : url('/privacy-policy') }}" class="text-muted">Privacy Policy</a>
          <span>·</span>
          <a href="{{ \Illuminate\Support\Facades\Route::has('front.terms-conditions') ? route('front.terms-conditions') : url('/terms-conditions') }}" class="text-muted">Terms & Conditions</a>
        </div>
      </div>

    </div>
  </footer>

  <!-- ============================================================
       SCRIPTS: Accordion & Mobile Navigation
       ============================================================ -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // FAQs Accordion toggle with + and - matching Image 4
      const faqCards = document.querySelectorAll('.faq-item-card');
      faqCards.forEach(card => {
        const btn = card.querySelector('.faq-btn');
        const symbol = card.querySelector('.faq-symbol');
        
        btn.addEventListener('click', function () {
          const isOpen = card.classList.contains('is-open');
          
          if (isOpen) {
            card.classList.remove('is-open');
            if (symbol) symbol.textContent = '+';
          } else {
            card.classList.add('is-open');
            if (symbol) symbol.textContent = '−';
          }
        });
      });

      // Mobile Hamburger Toggle
      const mobileBtn = document.getElementById('mobileToggleBtn');
      const mobileDrawer = document.getElementById('mobileMenuDrawer');
      if (mobileBtn && mobileDrawer) {
        mobileBtn.addEventListener('click', function () {
          mobileDrawer.classList.toggle('show');
        });
      }
    });
  </script>

</body>
</html>