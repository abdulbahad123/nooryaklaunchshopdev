@extends('layouts.app')

@section('pagetitle', 'WhatsApp Operations, Campaigns, and Automation')

@section('content')
<style>
/* ==========================================================================
   WHATSAPPBULK PURE SELF-CONTAINED STYLES (NO TAILWIND DEPENDENCY)
   ========================================================================== */
.wab-wrap * {
    box-sizing: border-box;
}
.wab-wrap {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #0f172a;
    background-color: #ffffff;
    overflow-x: hidden;
    width: 100%;
}
.wab-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
}

/* Hero Section */
.wab-hero {
    position: relative;
    padding: 48px 0 72px 0;
    background: radial-gradient(circle at 85% 20%, rgba(255, 120, 50, 0.20) 0%, rgba(255, 245, 238, 0.65) 42%, #ffffff 78%);
    overflow: hidden;
}
.wab-hero-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
}
.wab-hero-left {
    flex: 0 0 48%;
    max-width: 48%;
}
.wab-hero-right {
    flex: 0 0 52%;
    max-width: 52%;
    position: relative;
}

/* Badges */
.wab-pill-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff2ea;
    color: #ff5500;
    border: 1px solid #ffd8c2;
    padding: 6px 18px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 22px;
}
.wab-pill-badge-center {
    display: table;
    margin: 0 auto 16px auto;
}

/* Typography */
.wab-hero-title {
    font-size: 52px;
    font-weight: 800;
    line-height: 1.14;
    color: #0f172a;
    margin: 0 0 20px 0;
    letter-spacing: -1.2px;
}
.wab-hero-title span {
    color: #ff5500;
}
.wab-hero-desc {
    font-size: 16.5px;
    line-height: 1.65;
    color: #475569;
    margin-bottom: 30px;
    max-width: 530px;
}
.wab-hero-desc strong {
    color: #1e293b;
    font-weight: 700;
}

/* Buttons */
.wab-btn-row {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 38px;
}
.wab-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #ff5500;
    color: #ffffff !important;
    font-weight: 700;
    font-size: 16px;
    padding: 15px 32px;
    border-radius: 9999px;
    text-decoration: none !important;
    box-shadow: 0 10px 24px rgba(255, 85, 0, 0.35);
    transition: all 0.25s ease;
    border: none;
    cursor: pointer;
}
.wab-btn-primary:hover {
    background: #e64d00;
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(255, 85, 0, 0.42);
}
.wab-btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    color: #1e293b !important;
    font-weight: 600;
    font-size: 16px;
    padding: 15px 30px;
    border-radius: 9999px;
    text-decoration: none !important;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.25s ease;
    cursor: pointer;
}
.wab-btn-secondary:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    transform: translateY(-2px);
}

/* Hero Stats Row */
.wab-hero-stats {
    display: flex;
    align-items: stretch;
    gap: 14px;
}
.wab-stat-box {
    flex: 1;
    background: #ffffff;
    border: 1.5px solid #edf2f7;
    border-radius: 18px;
    padding: 16px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
    transition: transform 0.2s ease;
}
.wab-stat-box:hover {
    transform: translateY(-2px);
    border-color: #ffd8c2;
}
.wab-stat-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;
    border-radius: 12px;
    background: #fff2ea;
    color: #ff5500;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.wab-stat-val {
    font-size: 23px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}
.wab-stat-label {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    line-height: 1.3;
    margin-top: 2px;
}

/* Tablet Device Mockup */
.wab-tablet-frame {
    position: relative;
    border-radius: 28px;
    background: #ffffff;
    border: 10px solid #1e293b;
    box-shadow: 0 25px 65px -15px rgba(0, 0, 0, 0.20), 0 0 0 2px #cbd5e1;
    overflow: hidden;
    width: 100%;
}
.wab-tablet-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.wab-tablet-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 800;
    font-size: 14px;
    color: #0f172a;
}
.wab-tablet-logo img {
    height: 20px;
    width: auto;
}
.wab-tablet-user {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
}
.wab-tablet-avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #ff5500;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
}
.wab-tablet-body {
    display: flex;
    background: #ffffff;
    min-height: 380px;
}
.wab-tablet-sidebar {
    width: 135px;
    background: #fafaf9;
    border-right: 1px solid #f1f5f9;
    padding: 12px 8px;
    flex-shrink: 0;
}
.wab-side-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.wab-side-item.active {
    background: #fff2ea;
    color: #ff5500;
    font-weight: 700;
}
.wab-side-item i {
    font-size: 12px;
    width: 16px;
    text-align: center;
}
.wab-tablet-main {
    flex: 1;
    padding: 14px 16px;
    background: #ffffff;
    overflow: hidden;
}
.wab-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-bottom: 14px;
}
.wab-metric-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.wab-metric-title {
    font-size: 9.5px;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 2px;
}
.wab-metric-val {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}
.wab-metric-trend {
    font-size: 9px;
    color: #10b981;
    font-weight: 700;
    margin-top: 2px;
}

/* Tablet Chart Preview */
.wab-chart-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 12px;
    margin-bottom: 12px;
}
.wab-chart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.wab-chart-title {
    font-size: 11px;
    font-weight: 700;
    color: #1e293b;
}
.wab-chart-badge {
    font-size: 9px;
    color: #ff5500;
    background: #fff2ea;
    padding: 2px 8px;
    border-radius: 20px;
    font-weight: 600;
}
.wab-chart-svg {
    width: 100%;
    height: 65px;
}

/* Tablet Campaigns Table */
.wab-table-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 12px;
}
.wab-table-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px solid #f8fafc;
    font-size: 10px;
}
.wab-table-row:last-child {
    border-bottom: none;
}
.wab-badge-success {
    background: #ecfdf5;
    color: #059669;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 8.5px;
    font-weight: 700;
}
.wab-badge-warning {
    background: #fff7ed;
    color: #ea580c;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 8.5px;
    font-weight: 700;
}
.wab-badge-info {
    background: #eff6ff;
    color: #2563eb;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 8.5px;
    font-weight: 700;
}

/* Overlapping Phone Mockup */
.wab-phone-mockup {
    position: absolute;
    right: -14px;
    bottom: -22px;
    width: 170px;
    background: #ffffff;
    border: 7px solid #0f172a;
    border-radius: 32px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    z-index: 10;
    padding: 10px 8px;
    overflow: hidden;
}
.wab-phone-notch {
    width: 50px;
    height: 4px;
    background: #0f172a;
    border-radius: 4px;
    margin: 0 auto 8px auto;
}
.wab-phone-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #25d366;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin: 4px auto 6px auto;
    box-shadow: 0 4px 10px rgba(37, 211, 102, 0.35);
}
.wab-phone-title {
    font-size: 10px;
    font-weight: 800;
    text-align: center;
    color: #0f172a;
    margin-bottom: 2px;
}
.wab-phone-rate {
    font-size: 18px;
    font-weight: 800;
    text-align: center;
    color: #ff5500;
    line-height: 1.1;
    margin-bottom: 6px;
}
.wab-phone-bars {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 4px;
    height: 45px;
    padding-top: 8px;
}
.wab-phone-bar {
    width: 8px;
    background: #ff5500;
    border-radius: 4px 4px 0 0;
    opacity: 0.85;
}
.wab-phone-fab {
    position: absolute;
    right: 8px;
    bottom: 8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #25d366;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

/* ==========================================================================
   SECTION 2: LIVE MODULE STACK (REFERENCE IMAGE 4 TOP)
   ========================================================================== */
.wab-section-stack {
    padding: 72px 0 60px 0;
    background: #ffffff;
}
.wab-section-title {
    font-size: 40px;
    font-weight: 800;
    text-align: center;
    color: #0f172a;
    margin: 0 0 6px 0;
    letter-spacing: -0.8px;
}
.wab-section-title span {
    color: #ff5500;
}
.wab-section-subhead {
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    color: #1e293b;
    margin: 0 0 42px 0;
}

.wab-stack-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}
.wab-stack-card {
    background: #ffffff;
    border: 1.5px solid #edf2f7;
    border-radius: 22px;
    padding: 28px 24px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    transition: all 0.25s ease;
}
.wab-stack-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(255, 85, 0, 0.12);
    border-color: #ffd8c2;
}
.wab-stack-icon-box {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    background: #ff5500;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 20px;
    box-shadow: 0 10px 20px rgba(255, 85, 0, 0.28);
}
.wab-stack-tag {
    font-size: 12px;
    font-weight: 600;
    color: #94a3b8;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.wab-stack-name {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 10px 0;
    line-height: 1.25;
}
.wab-stack-desc {
    font-size: 14px;
    line-height: 1.6;
    color: #64748b;
    margin-bottom: 22px;
    flex-grow: 1;
}
.wab-stack-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #ff5500;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none !important;
    box-shadow: 0 4px 12px rgba(255, 85, 0, 0.3);
    transition: transform 0.2s ease;
}
.wab-stack-btn:hover {
    transform: scale(1.1);
    color: #ffffff !important;
}

/* ==========================================================================
   SECTION 3: PRODUCT MODULES (REFERENCE IMAGE 4 BOTTOM)
   ========================================================================== */
.wab-section-modules {
    padding: 60px 0 80px 0;
    background: #fbfbfc;
    border-top: 1px solid #f1f5f9;
}
.wab-modules-heading {
    font-size: 38px;
    font-weight: 800;
    text-align: center;
    color: #0f172a;
    margin: 0 0 10px 0;
    letter-spacing: -0.8px;
}
.wab-modules-heading span {
    color: #ff5500;
}
.wab-modules-lead {
    font-size: 16px;
    text-align: center;
    color: #64748b;
    max-width: 680px;
    margin: 0 auto 46px auto;
    line-height: 1.6;
}

.wab-modules-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
.wab-module-card {
    background: #ffffff;
    border: 1.5px solid #edf2f7;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    display: flex;
    flex-direction: column;
    position: relative;
    transition: all 0.25s ease;
}
.wab-module-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 26px rgba(0, 0, 0, 0.07);
    border-color: #ffd8c2;
}
.wab-module-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 16px;
    color: #ffffff;
}
.wab-module-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 10px 0;
}
.wab-module-desc {
    font-size: 13.5px;
    line-height: 1.6;
    color: #64748b;
    margin-bottom: 18px;
    flex-grow: 1;
}
.wab-module-arrow {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    align-self: flex-end;
    transition: all 0.2s ease;
}
.wab-module-card:hover .wab-module-arrow {
    background: #ff5500;
    color: #ffffff;
    border-color: #ff5500;
}

/* ==========================================================================
   SECTION 4: HIGH-CONVERSION CTA BANNER
   ========================================================================== */
.wab-section-cta {
    padding: 0 0 80px 0;
}
.wab-cta-box {
    background: linear-gradient(135deg, #ff5500 0%, #ff7733 100%);
    border-radius: 28px;
    padding: 56px 40px;
    text-align: center;
    color: #ffffff;
    box-shadow: 0 20px 45px rgba(255, 85, 0, 0.28);
    position: relative;
    overflow: hidden;
}
.wab-cta-title {
    font-size: 38px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 14px 0;
    letter-spacing: -0.6px;
}
.wab-cta-desc {
    font-size: 17px;
    color: rgba(255, 255, 255, 0.92);
    max-width: 620px;
    margin: 0 auto 32px auto;
    line-height: 1.6;
}
.wab-cta-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
}
.wab-cta-btn-white {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #ffffff;
    color: #ff5500 !important;
    font-weight: 800;
    font-size: 16px;
    padding: 15px 34px;
    border-radius: 9999px;
    text-decoration: none !important;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    transition: all 0.25s ease;
}
.wab-cta-btn-white:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 26px rgba(0, 0, 0, 0.22);
}
.wab-cta-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff !important;
    font-weight: 700;
    font-size: 16px;
    padding: 15px 30px;
    border-radius: 9999px;
    border: 1.5px solid rgba(255, 255, 255, 0.5);
    text-decoration: none !important;
    transition: all 0.25s ease;
}
.wab-cta-btn-outline:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

/* ==========================================================================
   RESPONSIVE QUERIES
   ========================================================================== */
@media (max-width: 1024px) {
    .wab-stack-grid,
    .wab-modules-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .wab-metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 900px) {
    .wab-hero-row {
        flex-direction: column;
    }
    .wab-hero-left,
    .wab-hero-right {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .wab-hero-title {
        font-size: 40px;
    }
    .wab-phone-mockup {
        right: 10px;
        bottom: -15px;
    }
}
@media (max-width: 640px) {
    .wab-hero-title {
        font-size: 32px;
    }
    .wab-hero-stats {
        flex-direction: column;
    }
    .wab-stack-grid,
    .wab-modules-grid {
        grid-template-columns: 1fr;
    }
    .wab-section-title,
    .wab-modules-heading,
    .wab-cta-title {
        font-size: 28px;
    }
    .wab-phone-mockup {
        display: none;
    }
}
</style>

<div class="wab-wrap">

    {{-- =================================================================== --}}
    {{-- HERO SECTION (REFERENCE IMAGE 3)                                     --}}
    {{-- =================================================================== --}}
    <section class="wab-hero">
        <div class="wab-container">
            <div class="wab-hero-row">
                
                {{-- LEFT COLUMN --}}
                <div class="wab-hero-left">
                    <div class="wab-pill-badge">
                        <i class="fas fa-sparkles"></i>
                        {{ __('All-in-One WhatsApp Marketing Tool') }}
                    </div>

                    <h1 class="wab-hero-title">
                        WhatsApp operations,<br>
                        <span>campaigns</span>, and<br>
                        automation
                    </h1>

                    <p class="wab-hero-desc">
                        <strong>{{ __('One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.') }}</strong><br>
                        {{ __('Built around the modules teams actually use every day: bulk outreach, smart replies, auto replies, chatbots, contacts, participant export, profile lookups, and reporting.') }}
                    </p>

                    <div class="wab-btn-row">
                        <a href="{{ url('auth/signup') }}" class="wab-btn-primary">
                            <span>{{ __('Create your workspace') }}</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="#modules" class="wab-btn-secondary">
                            {{ __('Explore modules') }}
                        </a>
                    </div>

                    {{-- 3 Stat KPI Cards --}}
                    <div class="wab-hero-stats">
                        <div class="wab-stat-box">
                            <div class="wab-stat-icon">
                                <i class="fas fa-cube"></i>
                            </div>
                            <div>
                                <div class="wab-stat-val">8</div>
                                <div class="wab-stat-label">{{ __('Operational modules included') }}</div>
                            </div>
                        </div>

                        <div class="wab-stat-box">
                            <div class="wab-stat-icon">
                                <i class="fas fa-link"></i>
                            </div>
                            <div>
                                <div class="wab-stat-val">1</div>
                                <div class="wab-stat-label">{{ __('Unified WhatsApp workflow') }}</div>
                            </div>
                        </div>

                        <div class="wab-stat-box">
                            <div class="wab-stat-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div>
                                <div class="wab-stat-val">24/7</div>
                                <div class="wab-stat-label">{{ __('Automation & reply coverage') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: TABLET & PHONE MOCKUPS --}}
                <div class="wab-hero-right">
                    
                    {{-- Tablet Frame --}}
                    <div class="wab-tablet-frame">
                        {{-- Tablet Header --}}
                        <div class="wab-tablet-header">
                            <div class="wab-tablet-logo">
                                <img src="{{ theme_public_asset('images/whatsappbulklogo.png') }}" alt="Whatsappbulk" onerror="this.style.display='none'">
                                <span>Dashboard</span>
                            </div>
                            <div class="wab-tablet-user">
                                <div class="wab-tablet-avatar">A</div>
                                <span>Hi, Admin &rarr;</span>
                            </div>
                        </div>

                        {{-- Tablet Body --}}
                        <div class="wab-tablet-body">
                            {{-- Mini Sidebar --}}
                            <div class="wab-tablet-sidebar">
                                <div class="wab-side-item active">
                                    <i class="fas fa-gauge-high"></i> Dashboard
                                </div>
                                <div class="wab-side-item">
                                    <i class="fas fa-bullhorn"></i> Bulk Campaigns
                                </div>
                                <div class="wab-side-item">
                                    <i class="fas fa-sparkles"></i> AI Smart Reply
                                </div>
                                <div class="wab-side-item">
                                    <i class="fas fa-clock-rotate-left"></i> Auto Reply
                                </div>
                                <div class="wab-side-item">
                                    <i class="fas fa-robot"></i> Chatbot
                                </div>
                                <div class="wab-side-item">
                                    <i class="fas fa-address-book"></i> Contacts
                                </div>
                                <div class="wab-side-item">
                                    <i class="fas fa-users"></i> Export Participants
                                </div>
                                <div class="wab-side-item">
                                    <i class="fas fa-id-card"></i> Profile Info
                                </div>
                                <div class="wab-side-item">
                                    <i class="fas fa-chart-column"></i> Reports
                                </div>
                            </div>

                            {{-- Tablet Main Content --}}
                            <div class="wab-tablet-main">
                                {{-- 4 KPI Metric Cards --}}
                                <div class="wab-metrics-grid">
                                    <div class="wab-metric-card">
                                        <div class="wab-metric-title">Total Messages</div>
                                        <div class="wab-metric-val">125,890</div>
                                        <div class="wab-metric-trend">&uarr; +20.4%</div>
                                    </div>
                                    <div class="wab-metric-card">
                                        <div class="wab-metric-title">Delivered</div>
                                        <div class="wab-metric-val">118,420</div>
                                        <div class="wab-metric-trend">&uarr; +18.1%</div>
                                    </div>
                                    <div class="wab-metric-card">
                                        <div class="wab-metric-title">Replied</div>
                                        <div class="wab-metric-val">28,460</div>
                                        <div class="wab-metric-trend">&uarr; +34.2%</div>
                                    </div>
                                    <div class="wab-metric-card">
                                        <div class="wab-metric-title">Active Contacts</div>
                                        <div class="wab-metric-val">96,350</div>
                                        <div class="wab-metric-trend">&uarr; +12.6%</div>
                                    </div>
                                </div>

                                {{-- Performance Area Chart Preview --}}
                                <div class="wab-chart-box">
                                    <div class="wab-chart-header">
                                        <span class="wab-chart-title">Message Performance</span>
                                        <span class="wab-chart-badge">Last 7 Days</span>
                                    </div>
                                    <svg class="wab-chart-svg" viewBox="0 0 320 65" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="wabHeroGrad" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#ff5500" stop-opacity="0.4"/>
                                                <stop offset="100%" stop-color="#ff5500" stop-opacity="0.0"/>
                                            </linearGradient>
                                        </defs>
                                        <path d="M0,52 C40,48 80,55 120,38 C160,20 200,32 240,16 C280,24 300,10 320,14 L320,65 L0,65 Z" fill="url(#wabHeroGrad)"/>
                                        <path d="M0,52 C40,48 80,55 120,38 C160,20 200,32 240,16 C280,24 300,10 320,14" fill="none" stroke="#ff5500" stroke-width="2.5"/>
                                    </svg>
                                </div>

                                {{-- Recent Campaigns Mini Table --}}
                                <div class="wab-table-box">
                                    <div style="font-size: 11px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Recent Campaigns</div>
                                    <div class="wab-table-row">
                                        <span style="font-weight: 600; color: #1e293b;"><i class="fas fa-bullhorn" style="color: #ff5500; margin-right: 4px;"></i> Product Launch</span>
                                        <span class="wab-badge-success">Completed</span>
                                        <span style="font-weight: 700;">12,500</span>
                                        <span style="color: #64748b;">2,400</span>
                                    </div>
                                    <div class="wab-table-row">
                                        <span style="font-weight: 600; color: #1e293b;"><i class="fas fa-bullhorn" style="color: #ff5500; margin-right: 4px;"></i> Festival Offer</span>
                                        <span class="wab-badge-warning">Sending</span>
                                        <span style="font-weight: 700;">25,000</span>
                                        <span style="color: #64748b;">6,800</span>
                                    </div>
                                    <div class="wab-table-row">
                                        <span style="font-weight: 600; color: #1e293b;"><i class="fas fa-headset" style="color: #059669; margin-right: 4px;"></i> Customer Support</span>
                                        <span class="wab-badge-info">Active</span>
                                        <span style="font-weight: 700;">18,200</span>
                                        <span style="color: #64748b;">4,200</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Foreground Overlapping Smartphone Mockup --}}
                    <div class="wab-phone-mockup">
                        <div class="wab-phone-notch"></div>
                        <div class="wab-phone-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="wab-phone-title">Campaign Overview</div>
                        <div class="wab-phone-rate">95.8%</div>
                        <div class="wab-phone-bars">
                            <div class="wab-phone-bar" style="height: 18px;"></div>
                            <div class="wab-phone-bar" style="height: 32px;"></div>
                            <div class="wab-phone-bar" style="height: 24px;"></div>
                            <div class="wab-phone-bar" style="height: 42px;"></div>
                            <div class="wab-phone-bar" style="height: 38px;"></div>
                            <div class="wab-phone-bar" style="height: 28px;"></div>
                        </div>
                        <div class="wab-phone-fab">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>


    {{-- =================================================================== --}}
    {{-- SECTION 2: LIVE MODULE STACK (REFERENCE IMAGE 4 TOP)                 --}}
    {{-- =================================================================== --}}
    <section class="wab-section-stack">
        <div class="wab-container">
            <div class="wab-pill-badge wab-pill-badge-center">
                <i class="fas fa-layer-group"></i>
                {{ __('Live module stack') }}
            </div>

            <h2 class="wab-section-title">
                Whatsappbulk <span>Workspace</span>
            </h2>
            <div class="wab-section-subhead">
                {{ __('Product overview') }}
            </div>

            <div class="wab-stack-grid">
                {{-- Card 1: Bulk Campaigns --}}
                <div class="wab-stack-card">
                    <div class="wab-stack-icon-box">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="wab-stack-tag">{{ __('Campaign control') }}</div>
                    <h3 class="wab-stack-name">{{ __('Bulk Campaigns') }}</h3>
                    <p class="wab-stack-desc">{{ __('Segment contacts, send at scale, and track response flow.') }}</p>
                    <div>
                        <a href="{{ url('auth/signup') }}" class="wab-stack-btn">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Card 2: AI + Auto Reply --}}
                <div class="wab-stack-card">
                    <div class="wab-stack-icon-box">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="wab-stack-tag">{{ __('Inbound handling') }}</div>
                    <h3 class="wab-stack-name">{{ __('AI + Auto Reply') }}</h3>
                    <p class="wab-stack-desc">{{ __('Combine automation with faster human response quality.') }}</p>
                    <div>
                        <a href="{{ url('auth/signup') }}" class="wab-stack-btn">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Card 3: Chatbot + Contacts --}}
                <div class="wab-stack-card">
                    <div class="wab-stack-icon-box">
                        <i class="fas fa-users-gear"></i>
                    </div>
                    <div class="wab-stack-tag">{{ __('Lead flow') }}</div>
                    <h3 class="wab-stack-name">{{ __('Chatbot + Contacts') }}</h3>
                    <p class="wab-stack-desc">{{ __('Capture, qualify, and manage audience data in one place.') }}</p>
                    <div>
                        <a href="{{ url('auth/signup') }}" class="wab-stack-btn">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Card 4: Reports + Export --}}
                <div class="wab-stack-card">
                    <div class="wab-stack-icon-box">
                        <i class="fas fa-chart-column"></i>
                    </div>
                    <div class="wab-stack-tag">{{ __('Visibility') }}</div>
                    <h3 class="wab-stack-name">{{ __('Reports + Export') }}</h3>
                    <p class="wab-stack-desc">{{ __('Export participants and measure what the team actually ships.') }}</p>
                    <div>
                        <a href="{{ url('auth/signup') }}" class="wab-stack-btn">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- =================================================================== --}}
    {{-- SECTION 3: PRODUCT MODULES (REFERENCE IMAGE 4 BOTTOM)                --}}
    {{-- =================================================================== --}}
    <section class="wab-section-modules" id="modules">
        <div class="wab-container">
            <div class="wab-pill-badge wab-pill-badge-center">
                <i class="fas fa-cubes"></i>
                {{ __('Product modules') }}
            </div>

            <h2 class="wab-modules-heading">
                Built from the <span>WhatsApp features</span> teams actually need
            </h2>
            <p class="wab-modules-lead">
                {{ __('Every module supports a real operational job, from outreach and automation to contact management and reporting.') }}
            </p>

            <div class="wab-modules-grid">
                {{-- Module 1: AI Smart Reply --}}
                <div class="wab-module-card">
                    <div class="wab-module-icon" style="background: #10b981; box-shadow: 0 8px 18px rgba(16, 185, 129, 0.28);">
                        <i class="fas fa-sparkles"></i>
                    </div>
                    <h3 class="wab-module-title">{{ __('AI Smart Reply') }}</h3>
                    <p class="wab-module-desc">{{ __('Generate fast, context-aware WhatsApp replies so agents can handle conversations with less manual effort.') }}</p>
                    <div class="wab-module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                {{-- Module 2: Auto Reply --}}
                <div class="wab-module-card">
                    <div class="wab-module-icon" style="background: #f97316; box-shadow: 0 8px 18px rgba(249, 115, 22, 0.28);">
                        <i class="fas fa-clock-rotate-left"></i>
                    </div>
                    <h3 class="wab-module-title">{{ __('Auto Reply') }}</h3>
                    <p class="wab-module-desc">{{ __('Trigger automatic responses for inbound messages, off-hours support, lead capture, and common request handling.') }}</p>
                    <div class="wab-module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                {{-- Module 3: Bulk Campaigns --}}
                <div class="wab-module-card">
                    <div class="wab-module-icon" style="background: #ff5500; box-shadow: 0 8px 18px rgba(255, 85, 0, 0.28);">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3 class="wab-module-title">{{ __('Bulk Campaigns') }}</h3>
                    <p class="wab-module-desc">{{ __('Launch high-volume WhatsApp campaigns to segmented audiences while keeping timing and response flow under control.') }}</p>
                    <div class="wab-module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                {{-- Module 4: Chatbot --}}
                <div class="wab-module-card">
                    <div class="wab-module-icon" style="background: #f59e0b; box-shadow: 0 8px 18px rgba(245, 158, 11, 0.28);">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3 class="wab-module-title">{{ __('Chatbot') }}</h3>
                    <p class="wab-module-desc">{{ __('Build conversational flows that qualify leads, answer questions, and move contacts into the next step automatically.') }}</p>
                    <div class="wab-module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                {{-- Module 5: Contacts --}}
                <div class="wab-module-card">
                    <div class="wab-module-icon" style="background: #ff5500; box-shadow: 0 8px 18px rgba(255, 85, 0, 0.28);">
                        <i class="fas fa-address-book"></i>
                    </div>
                    <h3 class="wab-module-title">{{ __('Contacts') }}</h3>
                    <p class="wab-module-desc">{{ __('Manage contact lists, segments, and campaign targets from one structured database.') }}</p>
                    <div class="wab-module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                {{-- Module 6: Export Participants --}}
                <div class="wab-module-card">
                    <div class="wab-module-icon" style="background: #10b981; box-shadow: 0 8px 18px rgba(16, 185, 129, 0.28);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="wab-module-title">{{ __('Export Participants') }}</h3>
                    <p class="wab-module-desc">{{ __('Extract participants from WhatsApp groups for outreach, qualification, migration, and audience building workflows.') }}</p>
                    <div class="wab-module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                {{-- Module 7: Profile Info --}}
                <div class="wab-module-card">
                    <div class="wab-module-icon" style="background: #f97316; box-shadow: 0 8px 18px rgba(249, 115, 22, 0.28);">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h3 class="wab-module-title">{{ __('Profile Info') }}</h3>
                    <p class="wab-module-desc">{{ __('Review WhatsApp profile information quickly to enrich lead context and improve handoff quality.') }}</p>
                    <div class="wab-module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                {{-- Module 8: Reports --}}
                <div class="wab-module-card">
                    <div class="wab-module-icon" style="background: #ff5500; box-shadow: 0 8px 18px rgba(255, 85, 0, 0.28);">
                        <i class="fas fa-chart-column"></i>
                    </div>
                    <h3 class="wab-module-title">{{ __('Reports') }}</h3>
                    <p class="wab-module-desc">{{ __('Track campaign output, reply activity, and workflow performance with clear reporting for real operational decisions.') }}</p>
                    <div class="wab-module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- =================================================================== --}}
    {{-- SECTION 4: CALL TO ACTION BANNER                                     --}}
    {{-- =================================================================== --}}
    <section class="wab-section-cta">
        <div class="wab-container">
            <div class="wab-cta-box">
                <h2 class="wab-cta-title">
                    {{ __('Ready to scale your WhatsApp marketing?') }}
                </h2>
                <p class="wab-cta-desc">
                    {{ __('Connect your WhatsApp account in seconds. Start sending bulk campaigns, automating customer replies, and converting more leads with zero coding required.') }}
                </p>
                <div class="wab-cta-buttons">
                    <a href="{{ url('auth/signup') }}" class="wab-cta-btn-white">
                        <span>{{ __('Get Started Free') }}</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ url('pricing') }}" class="wab-cta-btn-outline">
                        <span>{{ __('View Pricing Plans') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection