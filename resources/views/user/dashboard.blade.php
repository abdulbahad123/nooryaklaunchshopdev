@extends('user.layout')
@php
  $default = \App\Models\User\Language::where('is_default', 1)->first();
  $user = Auth::guard('web')->user();
  $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
  if (!empty($user)) {
      $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
      $permissions = json_decode($permissions, true);
  }
@endphp

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ===== BASE ===== */
.dash-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #0F172A;
    padding-bottom: 40px;
}

/* ===== PLAN BANNER ===== */
.plan-banner {
    display: flex;
    gap: 20px;
    margin-bottom: 24px;
}
.plan-current-card {
    flex: 1;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 24px 28px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.plan-limit-card {
    flex: 1.2;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 24px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.plan-left {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}
.plan-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #EFF3FF;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.plan-icon-box img, .plan-icon-box svg {
    width: 26px;
    height: 26px;
}
.plan-details .plan-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 4px;
}
.plan-name-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}
.plan-name {
    font-size: 24px;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.2;
}
.plan-term-badge {
    background: #6366F1;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    text-transform: capitalize;
}
.plan-expire {
    font-size: 13px;
    color: #6B7280;
    margin-bottom: 14px;
}
.plan-manage-btn {
    display: inline-block;
    border: 1.5px solid #3B82F6;
    color: #3B82F6;
    font-size: 13px;
    font-weight: 600;
    padding: 7px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s;
}
.plan-manage-btn:hover {
    background: #3B82F6;
    color: #fff;
    text-decoration: none;
}
.plan-features-col {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.plan-feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #374151;
    font-weight: 500;
}
.plan-feature-item .chk {
    color: #10B981;
    font-size: 12px;
    flex-shrink: 0;
}
.limit-right-img {
    height: 70px;
    width: auto;
    object-fit: contain;
    flex-shrink: 0;
}

/* Orders This Month card */
.orders-month-card {
    flex: 1;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 22px 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.orders-month-label {
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 10px;
}
.orders-month-value {
    font-size: 26px;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.2;
    margin-bottom: 12px;
}
.orders-month-value span {
    font-size: 16px;
    font-weight: 500;
    color: #6B7280;
}
.orders-progress-bar-wrap {
    height: 5px;
    background: #F1F5F9;
    border-radius: 4px;
    margin-bottom: 6px;
    overflow: hidden;
}
.orders-progress-bar-fill {
    height: 100%;
    background: #3B82F6;
    border-radius: 4px;
}
.orders-percent-label {
    font-size: 12px;
    color: #94A3B8;
    font-weight: 500;
    text-align: right;
    margin-bottom: 14px;
}
.view-usage-link {
    font-size: 13px;
    font-weight: 600;
    color: #3B82F6;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
}
.view-usage-link:hover { text-decoration: underline; }

/* ===== STATS CARDS ===== */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 16px;
}
.stat-card {
    border-radius: 12px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid transparent;
    transition: all 0.2s;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.stat-card-left {
    display: flex;
    flex-direction: column;
}
.stat-card-title {
    font-size: 13.5px;
    font-weight: 600;
    margin: 0 0 6px 0;
}
.stat-card-value {
    font-size: 28px;
    font-weight: 700;
    line-height: 1.1;
    margin: 0 0 8px 0;
}
.stat-card-trend {
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
}
.stat-card-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

/* Card Variations (Theme Backgrounds) */
.stat-card.c-blue {
    background: #EFF6FF !important;
    border-color: rgba(59, 130, 246, 0.1) !important;
}
.stat-card.c-purple {
    background: #F5F3FF !important;
    border-color: rgba(139, 92, 246, 0.1) !important;
}
.stat-card.c-teal {
    background: #F0FDFA !important;
    border-color: rgba(13, 148, 136, 0.1) !important;
}
.stat-card.c-green {
    background: #F0FDF4 !important;
    border-color: rgba(22, 163, 74, 0.1) !important;
}
.stat-card.c-orange {
    background: #FFF7ED !important;
    border-color: rgba(234, 88, 12, 0.1) !important;
}
.stat-card.c-red {
    background: #FFF5F5 !important;
    border-color: rgba(239, 68, 68, 0.1) !important;
}
.stat-card.c-indigo {
    background: #EEF2FF !important;
    border-color: rgba(79, 70, 229, 0.1) !important;
}

/* Title text variations */
.c-blue .stat-card-title { color: #2563EB !important; }
.c-purple .stat-card-title { color: #7C3AED !important; }
.c-teal .stat-card-title { color: #0D9488 !important; }
.c-green .stat-card-title { color: #16A34A !important; }
.c-orange .stat-card-title { color: #EA580C !important; }
.c-red .stat-card-title { color: #E11D48 !important; }
.c-indigo .stat-card-title { color: #4F46E5 !important; }

/* Value text variations */
.c-blue .stat-card-value { color: #1E3A8A !important; }
.c-purple .stat-card-value { color: #5B21B6 !important; }
.c-teal .stat-card-value { color: #115E59 !important; }
.c-green .stat-card-value { color: #14532D !important; }
.c-orange .stat-card-value { color: #7C2D12 !important; }
.c-red .stat-card-value { color: #9F1239 !important; }
.c-indigo .stat-card-value { color: #3730A3 !important; }

/* Icon Container variations */
.c-blue .stat-card-icon { background: #DBEAFE !important; color: #2563EB !important; }
.c-purple .stat-card-icon { background: #EDE9FE !important; color: #7C3AED !important; }
.c-teal .stat-card-icon { background: #CCFBF1 !important; color: #0D9488 !important; }
.c-green .stat-card-icon { background: #DCFCE7 !important; color: #16A34A !important; }
.c-orange .stat-card-icon { background: #FFEDD5 !important; color: #EA580C !important; }
.c-red .stat-card-icon { background: #FFE4E6 !important; color: #E11D48 !important; }
.c-indigo .stat-card-icon { background: #E0E7FF !important; color: #4F46E5 !important; }

/* Trend text variations */
.c-blue .stat-card-trend { color: #2563EB !important; }
.c-purple .stat-card-trend { color: #7C3AED !important; }
.c-teal .stat-card-trend { color: #0D9488 !important; }
.c-green .stat-card-trend { color: #16A34A !important; }
.c-orange .stat-card-trend { color: #EA580C !important; }
.c-red .stat-card-trend { color: #E11D48 !important; }
.c-indigo .stat-card-trend { color: #4F46E5 !important; }

.c-blue .trend-neutral, .c-purple .trend-neutral, .c-teal .trend-neutral, .c-green .trend-neutral, .c-orange .trend-neutral, .c-red .trend-neutral, .c-indigo .trend-neutral {
    color: #94A3B8 !important;
}

/* ===== CHARTS ROW ===== */
.charts-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 16px;
}
.chart-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.chart-card-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.chart-card-title .chart-subtitle {
    font-size: 11.5px;
    color: #94A3B8;
    font-weight: 500;
}

/* ===== BOTTOM ROW ===== */
.bottom-row {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 16px;
}
.bottom-row .bottom-card {
    grid-column: span 6;
}

/* For laptop screens (1201px to 1600px) */
@media (min-width: 1201px) and (max-width: 1600px) {
    .bottom-row .bottom-card:nth-child(1),
    .bottom-row .bottom-card:nth-child(2) {
        grid-column: span 3; /* Row 1: 2 columns */
    }
    .bottom-row .bottom-card:nth-child(3),
    .bottom-row .bottom-card:nth-child(4),
    .bottom-row .bottom-card:nth-child(5) {
        grid-column: span 2; /* Row 2: 3 columns */
    }
}

/* For large desktop screens (above 1600px) */
@media (min-width: 1601px) {
    .bottom-row {
        grid-template-columns: repeat(5, 1fr);
    }
    .bottom-row .bottom-card {
        grid-column: span 1 !important;
    }
}
.bottom-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 18px 18px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.bottom-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.bottom-card-title {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13.5px;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
}
.bottom-card-title .title-icon {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.view-all-btn {
    font-size: 12px;
    font-weight: 600;
    color: #3B82F6;
    text-decoration: none;
}
.view-all-btn:hover { text-decoration: underline; }

/* Compact Table */
.compact-table { width: 100%; border-collapse: collapse; }
.compact-table th {
    font-size: 11px;
    font-weight: 600;
    color: #9CA3AF;
    text-transform: uppercase;
    padding: 0 6px 8px;
    border-bottom: 1px solid #F1F5F9;
    white-space: nowrap;
}
.compact-table td {
    font-size: 12px;
    color: #374151;
    padding: 8px 6px;
    border-bottom: 1px solid #F9FAFB;
    vertical-align: middle;
}
.status-badge {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 5px;
    font-size: 10.5px;
    font-weight: 600;
    white-space: nowrap;
}
.s-completed  { background:#DCFCE7; color:#15803D; }
.s-processing { background:#DBEAFE; color:#1D4ED8; }
.s-pending    { background:#FEF9C3; color:#A16207; }
.s-rejected   { background:#FEE2E2; color:#B91C1C; }
.s-success    { background:#DCFCE7; color:#15803D; }
.s-low-stock  { background:#FEF3C7; color:#D97706; }
.s-out-stock  { background:#FEE2E2; color:#B91C1C; }

/* Customer list */
.cust-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #F9FAFB;
}
.cust-left {
    display: flex;
    align-items: center;
    gap: 9px;
}
.cust-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366F1, #8B5CF6);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
}
.cust-name  { font-size: 12.5px; font-weight: 600; color: #0F172A; margin: 0; }
.cust-email { font-size: 11px; color: #6B7280; margin: 0; }
.cust-date  { font-size: 11px; color: #9CA3AF; white-space: nowrap; }

/* Quick actions */
.quick-action-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px;
    border: 1px solid #F1F5F9;
    border-radius: 9px;
    margin-bottom: 8px;
    text-decoration: none !important;
    color: #374151 !important;
    transition: all 0.18s;
    font-size: 12.5px;
    font-weight: 600;
}
.quick-action-row:hover {
    background: #F8FAFC;
    border-color: #E2E8F0;
}
.quick-action-row .qa-left {
    display: flex;
    align-items: center;
    gap: 9px;
}
.qa-icon {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

/* Stock thumbnail */
.stock-thumb {
    width: 28px;
    height: 28px;
    border-radius: 5px;
    object-fit: cover;
    border: 1px solid #E2E8F0;
    flex-shrink: 0;
}

/* ===== RESPONSIVE ===== */
/* Tablet & Small Desktop: stack plan cards */
@media (max-width: 1200px) {
    .plan-banner { flex-direction: column; gap: 16px; }
    .plan-current-card, .plan-limit-card, .orders-month-card { width: 100%; flex: unset; }
    .bottom-row { grid-template-columns: repeat(2, 1fr); }
    .bottom-row .bottom-card { grid-column: span 1 !important; }
    .charts-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 991px) {
    .stats-row { grid-template-columns: repeat(2, 1fr); }
}
/* Mobile: 2-col stats, 1-col everything else */
@media (max-width: 767px) {
    .plan-left { flex-direction: column; gap: 10px; }
    .stats-row { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .stat-card { padding: 14px 14px 12px; }
    .stat-card-value { font-size: 22px; }
    .charts-row { grid-template-columns: 1fr; }
    .bottom-row { grid-template-columns: 1fr; }
    .bottom-row .bottom-card { grid-column: span 1 !important; }
}
@media (max-width: 576px) {
    .plan-limit-card { flex-direction: column; align-items: flex-start; gap: 16px; }
    .limit-right-img { align-self: flex-end; }
}
@media (max-width: 480px) {
    .plan-banner { gap: 12px; }
    .stats-row { gap: 10px; }
/* ===== DARK THEME OVERRIDES ===== */
body[data-background-color="dark"] {
    color: #f8fafc !important;
}
body[data-background-color="dark"] .dash-wrapper {
    color: #f8fafc !important;
}
body[data-background-color="dark"] .mb-4 h2 {
    color: #ffffff !important;
}
body[data-background-color="dark"] .plan-current-card,
body[data-background-color="dark"] .plan-limit-card,
body[data-background-color="dark"] .orders-month-card,
body[data-background-color="dark"] .chart-card,
body[data-background-color="dark"] .bottom-card {
    background: #1a2035 !important;
    border-color: #2f374b !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
    color: #f8fafc !important;
}
body[data-background-color="dark"] .plan-name,
body[data-background-color="dark"] .orders-month-value,
body[data-background-color="dark"] .chart-card-title,
body[data-background-color="dark"] .bottom-card-title {
    color: #ffffff !important;
}
body[data-background-color="dark"] .plan-expire,
body[data-background-color="dark"] .plan-feature-item,
body[data-background-color="dark"] .orders-month-value span,
body[data-background-color="dark"] .orders-percent-label {
    color: #cbd5e1 !important;
}
body[data-background-color="dark"] .plan-icon-box {
    background: #232a45 !important;
}
body[data-background-color="dark"] .orders-progress-bar-wrap {
    background: #2e3856 !important;
}
body[data-background-color="dark"] .orders-progress-bar-fill {
    background: #3b82f6 !important;
}

/* Bottom elements dark mode */
body[data-background-color="dark"] .compact-table th {
    color: #94a3b8 !important;
    border-bottom: 1px solid #2f374b !important;
}
body[data-background-color="dark"] .compact-table td {
    color: #cbd5e1 !important;
    border-bottom: 1px solid #232a45 !important;
}
body[data-background-color="dark"] .cust-row {
    border-bottom: 1px solid #232a45 !important;
}
body[data-background-color="dark"] .cust-name {
    color: #ffffff !important;
}
body[data-background-color="dark"] .cust-email {
    color: #94a3b8 !important;
}
body[data-background-color="dark"] .quick-action-row {
    border: 1px solid #2f374b !important;
    color: #cbd5e1 !important;
}
body[data-background-color="dark"] .quick-action-row:hover {
    background: #232a45 !important;
    border-color: #3f4a73 !important;
}
body[data-background-color="dark"] .stock-thumb {
    border-color: #2f374b !important;
}

/* Stats Cards dark theme overrides (translucent background colors) */
body[data-background-color="dark"] .stat-card.c-blue {
    background: rgba(59, 130, 246, 0.12) !important;
    border-color: rgba(59, 130, 246, 0.25) !important;
}
body[data-background-color="dark"] .stat-card.c-purple {
    background: rgba(139, 92, 246, 0.12) !important;
    border-color: rgba(139, 92, 246, 0.25) !important;
}
body[data-background-color="dark"] .stat-card.c-teal {
    background: rgba(13, 148, 136, 0.12) !important;
    border-color: rgba(13, 148, 136, 0.25) !important;
}
body[data-background-color="dark"] .stat-card.c-green {
    background: rgba(22, 163, 74, 0.12) !important;
    border-color: rgba(22, 163, 74, 0.25) !important;
}
body[data-background-color="dark"] .stat-card.c-orange {
    background: rgba(234, 88, 12, 0.12) !important;
    border-color: rgba(234, 88, 12, 0.25) !important;
}
body[data-background-color="dark"] .stat-card.c-red {
    background: rgba(239, 68, 68, 0.12) !important;
    border-color: rgba(239, 68, 68, 0.25) !important;
}
body[data-background-color="dark"] .stat-card.c-indigo {
    background: rgba(79, 70, 229, 0.12) !important;
    border-color: rgba(79, 70, 229, 0.25) !important;
}

body[data-background-color="dark"] .stat-card-value {
    color: #ffffff !important;
}

body[data-background-color="dark"] .c-blue .stat-card-icon { background: rgba(59, 130, 246, 0.25) !important; color: #60a5fa !important; }
body[data-background-color="dark"] .c-purple .stat-card-icon { background: rgba(139, 92, 246, 0.25) !important; color: #a78bfa !important; }
body[data-background-color="dark"] .c-teal .stat-card-icon { background: rgba(13, 148, 136, 0.25) !important; color: #2dd4bf !important; }
body[data-background-color="dark"] .c-green .stat-card-icon { background: rgba(22, 163, 74, 0.25) !important; color: #4ade80 !important; }
body[data-background-color="dark"] .c-orange .stat-card-icon { background: rgba(234, 88, 12, 0.25) !important; color: #fb923c !important; }
body[data-background-color="dark"] .c-red .stat-card-icon { background: rgba(239, 68, 68, 0.25) !important; color: #f87171 !important; }
body[data-background-color="dark"] .c-indigo .stat-card-icon { background: rgba(79, 70, 229, 0.25) !important; color: #818cf8 !important; }

body[data-background-color="dark"] .c-blue .stat-card-title { color: #60a5fa !important; }
body[data-background-color="dark"] .c-purple .stat-card-title { color: #c084fc !important; }
body[data-background-color="dark"] .c-teal .stat-card-title { color: #2dd4bf !important; }
body[data-background-color="dark"] .c-green .stat-card-title { color: #4ade80 !important; }
body[data-background-color="dark"] .c-orange .stat-card-title { color: #fb923c !important; }
body[data-background-color="dark"] .c-red .stat-card-title { color: #f87171 !important; }
body[data-background-color="dark"] .c-indigo .stat-card-title { color: #818cf8 !important; }

body[data-background-color="dark"] .c-blue .stat-card-trend { color: #60a5fa !important; }
body[data-background-color="dark"] .c-purple .stat-card-trend { color: #c084fc !important; }
body[data-background-color="dark"] .c-teal .stat-card-trend { color: #2dd4bf !important; }
body[data-background-color="dark"] .c-green .stat-card-trend { color: #4ade80 !important; }
body[data-background-color="dark"] .c-orange .stat-card-trend { color: #fb923c !important; }
body[data-background-color="dark"] .c-red .stat-card-trend { color: #f87171 !important; }
body[data-background-color="dark"] .c-indigo .stat-card-trend { color: #818cf8 !important; }

body[data-background-color="dark"] .c-blue .trend-neutral, 
body[data-background-color="dark"] .c-purple .trend-neutral, 
body[data-background-color="dark"] .c-teal .trend-neutral, 
body[data-background-color="dark"] .c-green .trend-neutral, 
body[data-background-color="dark"] .c-orange .trend-neutral, 
body[data-background-color="dark"] .c-red .trend-neutral, 
body[data-background-color="dark"] .c-indigo .trend-neutral {
    color: #94a3b8 !important;
}
</style>
@endsection

@section('content')
<div class="dash-wrapper">

  {{-- ===== WELCOME ===== --}}
  <div class="mb-4">
    <h2 style="font-size:24px;font-weight:700;color:#0F172A;margin-bottom:4px;">
      {{ __('Welcome back') }}, {{ $user->first_name ?? $user->username }}! 👋
    </h2>
    <p style="font-size:14px;color:#6B7280;margin:0;">{{ __("Here's what's happening with your store today.") }}</p>
  </div>

  @if (is_null($package))
    @php
      $pendingMemb = \App\Models\Membership::query()
          ->where([['user_id', '=', Auth::id()], ['status', 0]])
          ->whereYear('start_date', '<>', '9999')
          ->orderBy('id', 'DESC')->first();
      $pendingPackage = isset($pendingMemb) ? \App\Models\Package::query()->findOrFail($pendingMemb->package_id) : null;
    @endphp
    @if ($pendingPackage)
      <div class="alert alert-warning">{{ __('You have requested a package which needs an action (Approval / Rejection) by Admin.') }}</div>
      <div class="alert alert-warning">
        <strong>{{ __('Pending Package:') }}</strong> {{ $pendingPackage->title }}
        <span class="badge badge-secondary">{{ $pendingPackage->term }}</span>
        <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
      </div>
    @else
      <div class="alert alert-warning">{{ __('Your membership is expired. Please purchase a new package / extend the current package.') }}</div>
    @endif
  @else
    @php
      $pLimitVal      = $current_package->product_limit;
      $oLimitVal      = $current_package->order_limit;
      $prodLimitLabel = $pLimitVal == 999999
          ? __('Unlimited Products')
          : __('Up to :count Products', ['count' => number_format($pLimitVal)]);
      $orderLimitLabel = $oLimitVal == 999999
          ? __('Unlimited Orders')
          : __('Up to :count Orders', ['count' => number_format($oLimitVal)]);
      $packageFeatures = json_decode($current_package->features, true) ?? [];
      $isUnlimitedOrders = ($oLimitVal == 999999);
      $orderPercent = $isUnlimitedOrders ? 0 : min(($total_orders / $oLimitVal) * 100, 100);
    @endphp

    {{-- ===== PLAN BANNER ===== --}}
    <div class="plan-banner">

      {{-- Container 1: Current Plan Details --}}
      <div class="plan-current-card">
        <div class="plan-icon-box">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="3" y="3" width="18" height="18" rx="3" fill="#EFF3FF"/>
            <path d="M7 7h10M7 12h10M7 17h6" stroke="#6366F1" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </div>
        <div class="plan-details">
          <div class="plan-label">{{ __('Current Plan') }}</div>
          <div class="plan-name-row">
            <span class="plan-name">{{ $current_package->title }}</span>
            <span class="plan-term-badge">{{ ucfirst($current_package->term) }}</span>
          </div>
          <div class="plan-expire">
            {{ __('Expires on') }}
            {{ $current_package->term === 'lifetime' ? __('Lifetime') : \Carbon\Carbon::parse($current_membership->expire_date)->format('d M, Y') }}
          </div>
          <a href="{{ route('user.plan.extend.index') }}" class="plan-manage-btn">{{ __('Manage Plan') }}</a>
        </div>
      </div>

      {{-- Container 2: Limit Container --}}
      <div class="plan-limit-card">
        @php
          if (\Schema::hasTable('package_features')) {
              $allFeatures = \App\Models\PackageFeature::orderBy('serial_number', 'asc')->get();
          } else {
              $allFeatures = collect();
          }
          
          $activeFeatureLabels = [];
          
          if ($allFeatures->isEmpty()) {
              $activeFeatureLabels[] = $prodLimitLabel;
              $activeFeatureLabels[] = $orderLimitLabel;
              
              if (is_array($packageFeatures) && in_array('Custom Domain', $packageFeatures)) {
                  $activeFeatureLabels[] = __('Custom Domain');
              }
              if (is_array($packageFeatures) && in_array('Subdomain', $packageFeatures)) {
                  $activeFeatureLabels[] = __('Subdomain');
              }
          } else {
              foreach ($allFeatures as $feature) {
                  if ($feature->type === 'limit') {
                      $limitVal = $current_package->{$feature->limit_key} ?? 0;
                      if ($limitVal > 0 || $limitVal == 999999) {
                          $formattedVal = ($limitVal == 999999) ? __('Unlimited') : $limitVal;
                          if ($feature->limit_key === 'order_limit' && $limitVal != 999999) {
                              $formattedVal .= '/' . ($current_package->term == 'monthly' ? 'm' : 'yr');
                          }
                          $activeFeatureLabels[] = str_replace('{limit}', $formattedVal, $feature->name);
                      }
                  } elseif ($feature->type === 'standard') {
                      if (is_array($packageFeatures) && in_array($feature->keyword, $packageFeatures)) {
                          $text = $feature->name;
                          if (!empty($feature->limit_key)) {
                              $limitVal = $current_package->{$feature->limit_key} ?? 0;
                              $formattedVal = ($limitVal == 999999) ? __('Unlimited') : $limitVal;
                              $text = str_replace('{limit}', $formattedVal, $text);
                          }
                          $activeFeatureLabels[] = $text;
                      }
                  } elseif ($feature->type === 'custom') {
                      if (is_array($packageFeatures) && in_array($feature->name, $packageFeatures)) {
                          $activeFeatureLabels[] = $feature->name;
                      }
                  }
              }
          }
        @endphp
        <div class="plan-features-col" style="max-height: 120px; overflow-y: auto; padding-right: 4px;">
          @foreach($activeFeatureLabels as $label)
            <div class="plan-feature-item">
              <i class="fas fa-check-circle chk"></i>
              <span>{{ __($label) }}</span>
            </div>
          @endforeach
        </div>
        <img src="{{ asset('images/right_side.png') }}" class="limit-right-img" alt="">
      </div>

      {{-- Container 3: Orders This Month --}}
      <div class="orders-month-card">
        <div class="orders-month-label">{{ __('Orders This Month') }}</div>
        <div class="orders-month-value">
          {{ $total_orders }}
          <span>/ {{ $isUnlimitedOrders ? __('Unlimited') : number_format($oLimitVal) }}</span>
        </div>
        <div class="orders-progress-bar-wrap">
          <div class="orders-progress-bar-fill" style="width: {{ $orderPercent }}%;"></div>
        </div>
        <div class="orders-percent-label">
          @if($isUnlimitedOrders) {{ __('Unlimited') }}
          @else {{ number_format($orderPercent, 1) }}%
          @endif
        </div>
        <a href="{{ route('user.all.item.orders') }}" class="view-usage-link">
          {{ __('View Usage Details') }} <i class="fas fa-arrow-right" style="font-size:10px;"></i>
        </a>
      </div>

    </div>{{-- end plan-banner --}}
  @endif

  {{-- ===== STATS ROW 1 (Total Products, Orders, Customers, Revenue) ===== --}}
  <div class="stats-row">
    <div class="stat-card c-blue">
      <div class="stat-card-left">
        <p class="stat-card-title">{{ __('Total Products') }}</p>
        <div class="stat-card-value">{{ $total_items }}</div>

      </div>
      <div class="stat-card-icon">
        <i class="fas fa-cube"></i>
      </div>
    </div>

    <div class="stat-card c-purple">
      <div class="stat-card-left">
        <p class="stat-card-title">{{ __('Orders') }}</p>
        <div class="stat-card-value">{{ $total_orders }}</div>

      </div>
      <div class="stat-card-icon">
        <i class="fas fa-shopping-bag"></i>
      </div>
    </div>

    <div class="stat-card c-teal">
      <div class="stat-card-left">
        <p class="stat-card-title">{{ __('Customers') }}</p>
        <div class="stat-card-value">{{ $total_customers }}</div>

      </div>
      <div class="stat-card-icon">
        <i class="fas fa-users"></i>
      </div>
    </div>

    <div class="stat-card c-green">
      <div class="stat-card-left">
        <p class="stat-card-title">{{ __('Revenue') }}</p>
        <div class="stat-card-value">{{ $user_currency ? $user_currency->symbol : '₹' }}{{ number_format($total_revenue, 2) }}</div>

      </div>
      <div class="stat-card-icon">
        <i class="fas fa-rupee-sign"></i>
      </div>
    </div>
  </div>

  {{-- ===== STATS ROW 2 (Conversion Rate, Subscribers, Blogs, Custom Pages) ===== --}}
  <div class="stats-row" style="margin-bottom:24px;">
    <div class="stat-card c-orange">
      <div class="stat-card-left">
        <p class="stat-card-title">{{ __('Conversion Rate') }}</p>
        <div class="stat-card-value">{{ $conversion_rate }}%</div>

      </div>
      <div class="stat-card-icon">
        <i class="fas fa-chart-line"></i>
      </div>
    </div>

    <div class="stat-card c-red">
      <div class="stat-card-left">
        <p class="stat-card-title">{{ __('Subscribers') }}</p>
        <div class="stat-card-value">{{ $total_subscribers }}</div>

      </div>
      <div class="stat-card-icon">
        <i class="fas fa-envelope"></i>
      </div>
    </div>

    <div class="stat-card c-teal">
      <div class="stat-card-left">
        <p class="stat-card-title">{{ __('Blogs') }}</p>
        <div class="stat-card-value">{{ $blogs }}</div>

      </div>
      <div class="stat-card-icon">
        <i class="fas fa-file-alt"></i>
      </div>
    </div>

    <div class="stat-card c-indigo">
      <div class="stat-card-left">
        <p class="stat-card-title">{{ __('Custom Pages') }}</p>
        <div class="stat-card-value">{{ $total_custom_pages }}</div>

      </div>
      <div class="stat-card-icon">
        <i class="fas fa-file"></i>
      </div>
    </div>
  </div>

  {{-- ===== CHARTS ROW ===== --}}
  <div class="charts-row">
    {{-- Sales Overview --}}
    <div class="chart-card">
      <div class="chart-card-title-wrap" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;width:100%;flex-wrap:wrap;gap:8px;">
        <div class="chart-card-title" style="margin:0;">
          {{ __('Sales Overview') }}
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
          <div class="custom-date-container d-none" style="align-items: center; gap: 4px;">
            <input type="date" class="form-control form-control-sm start-date" style="width: 105px; font-size: 10px; height: 25px; padding: 2px 4px;">
            <span style="font-size: 10px; color: #6b7280;">to</span>
            <input type="date" class="form-control form-control-sm end-date" style="width: 105px; font-size: 10px; height: 25px; padding: 2px 4px;">
            <button type="button" class="btn btn-primary btn-sm apply-custom-date" style="font-size: 10px; height: 25px; padding: 2px 6px; line-height: 1.2;">Apply</button>
          </div>
          <select class="chart-filter-select" data-chart-type="sales_overview" style="font-size:11px;font-weight:600;color:#4B5563;border:1px solid #E5E7EB;border-radius:6px;padding:4px 8px;background:#FFF;outline:none;cursor:pointer;">
            <option value="today">{{ __('Today') }}</option>
            <option value="yesterday">{{ __('Yesterday') }}</option>
            <option value="7">{{ __('7 Days') }}</option>
            <option value="30" selected>{{ __('30 Days') }}</option>
            <option value="90">{{ __('90 Days') }}</option>
            <option value="365">{{ __('This Year') }}</option>
            <option value="custom">{{ __('Custom Date') }}</option>
          </select>
        </div>
      </div>
      <div style="position:relative;height:180px;width:100%;">
        <canvas id="salesOverviewChart"></canvas>
      </div>
    </div>

    {{-- Revenue Analytics --}}
    <div class="chart-card">
      <div class="chart-card-title-wrap" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;width:100%;flex-wrap:wrap;gap:8px;">
        <div class="chart-card-title" style="margin:0;">{{ __('Revenue Analytics') }}</div>
        <div class="d-flex align-items-center" style="gap: 8px;">
          <div class="custom-date-container d-none" style="align-items: center; gap: 4px;">
            <input type="date" class="form-control form-control-sm start-date" style="width: 105px; font-size: 10px; height: 25px; padding: 2px 4px;">
            <span style="font-size: 10px; color: #6b7280;">to</span>
            <input type="date" class="form-control form-control-sm end-date" style="width: 105px; font-size: 10px; height: 25px; padding: 2px 4px;">
            <button type="button" class="btn btn-primary btn-sm apply-custom-date" style="font-size: 10px; height: 25px; padding: 2px 6px; line-height: 1.2;">Apply</button>
          </div>
          <select class="chart-filter-select" data-chart-type="revenue_analytics" style="font-size:11px;font-weight:600;color:#4B5563;border:1px solid #E5E7EB;border-radius:6px;padding:4px 8px;background:#FFF;outline:none;cursor:pointer;">
            <option value="today">{{ __('Today') }}</option>
            <option value="yesterday">{{ __('Yesterday') }}</option>
            <option value="7">{{ __('7 Days') }}</option>
            <option value="30" selected>{{ __('30 Days') }}</option>
            <option value="90">{{ __('90 Days') }}</option>
            <option value="365">{{ __('This Year') }}</option>
            <option value="custom">{{ __('Custom Date') }}</option>
          </select>
        </div>
      </div>
      <div style="position:relative;height:155px;width:100%;">
        <canvas id="revenueAnalyticsChart"></canvas>
      </div>
    </div>

    {{-- Order Trend --}}
    <div class="chart-card">
      <div class="chart-card-title-wrap" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;width:100%;flex-wrap:wrap;gap:8px;">
        <div class="chart-card-title" style="margin:0;">{{ __('Order Trend') }}</div>
        <div class="d-flex align-items-center" style="gap: 8px;">
          <div class="custom-date-container d-none" style="align-items: center; gap: 4px;">
            <input type="date" class="form-control form-control-sm start-date" style="width: 105px; font-size: 10px; height: 25px; padding: 2px 4px;">
            <span style="font-size: 10px; color: #6b7280;">to</span>
            <input type="date" class="form-control form-control-sm end-date" style="width: 105px; font-size: 10px; height: 25px; padding: 2px 4px;">
            <button type="button" class="btn btn-primary btn-sm apply-custom-date" style="font-size: 10px; height: 25px; padding: 2px 6px; line-height: 1.2;">Apply</button>
          </div>
          <select class="chart-filter-select" data-chart-type="order_trend" style="font-size:11px;font-weight:600;color:#4B5563;border:1px solid #E5E7EB;border-radius:6px;padding:4px 8px;background:#FFF;outline:none;cursor:pointer;">
            <option value="today">{{ __('Today') }}</option>
            <option value="yesterday">{{ __('Yesterday') }}</option>
            <option value="7">{{ __('7 Days') }}</option>
            <option value="30" selected>{{ __('30 Days') }}</option>
            <option value="90">{{ __('90 Days') }}</option>
            <option value="365">{{ __('This Year') }}</option>
            <option value="custom">{{ __('Custom Date') }}</option>
          </select>
        </div>
      </div>
      <div style="position:relative;height:180px;width:100%;">
        <canvas id="orderTrendChart"></canvas>
      </div>
    </div>

    {{-- Traffic Sources --}}
    <div class="chart-card">
      <div class="chart-card-title-wrap" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;width:100%;flex-wrap:wrap;gap:8px;">
        <div class="chart-card-title" style="margin:0;">{{ __('Traffic Sources') }}</div>
        <div class="d-flex align-items-center" style="gap: 8px;">
          <div class="custom-date-container d-none" style="align-items: center; gap: 4px;">
            <input type="date" class="form-control form-control-sm start-date" style="width: 105px; font-size: 10px; height: 25px; padding: 2px 4px;">
            <span style="font-size: 10px; color: #6b7280;">to</span>
            <input type="date" class="form-control form-control-sm end-date" style="width: 105px; font-size: 10px; height: 25px; padding: 2px 4px;">
            <button type="button" class="btn btn-primary btn-sm apply-custom-date" style="font-size: 10px; height: 25px; padding: 2px 6px; line-height: 1.2;">Apply</button>
          </div>
          <select class="chart-filter-select" data-chart-type="traffic_sources" style="font-size:11px;font-weight:600;color:#4B5563;border:1px solid #E5E7EB;border-radius:6px;padding:4px 8px;background:#FFF;outline:none;cursor:pointer;">
            <option value="today">{{ __('Today') }}</option>
            <option value="yesterday">{{ __('Yesterday') }}</option>
            <option value="7">{{ __('7 Days') }}</option>
            <option value="30" selected>{{ __('30 Days') }}</option>
            <option value="90">{{ __('90 Days') }}</option>
            <option value="365">{{ __('This Year') }}</option>
            <option value="custom">{{ __('Custom Date') }}</option>
          </select>
        </div>
      </div>
      <div style="position:relative;height:155px;width:100%;">
        <canvas id="trafficSourcesChart"></canvas>
      </div>
    </div>
  </div>

  {{-- ===== BOTTOM ROW: 5 columns ===== --}}
  <div class="bottom-row">

    {{-- Recent Orders --}}
    <div class="bottom-card">
      <div class="bottom-card-header">
        <h5 class="bottom-card-title">
          <span class="title-icon icon-blue"><i class="fas fa-shopping-bag" style="font-size:11px;"></i></span>
          {{ __('Recent Orders') }}
        </h5>
        <a href="{{ route('user.all.item.orders') }}" class="view-all-btn">{{ __('View All') }}</a>
      </div>
      @if(count($orders) == 0)
        <p class="text-muted" style="font-size:12px;">{{ __('No orders yet.') }}</p>
      @else
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
          <table class="compact-table">
            <thead>
              <tr>
                <th>{{ __('Order') }}</th>
                <th>{{ __('Customer') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Date') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders->take(5) as $order)
                <tr>
                  <td><strong>#{{ $order->order_number }}</strong></td>
                  <td>{{ $order->billing_fname }} {{ $order->billing_lname }}</td>
                  <td>{{ round($order->total, 2) }}<br><small style="color:#9CA3AF;">{{ $order->currency_code }}</small></td>
                  <td><span class="status-badge s-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span></td>
                  <td style="color:#9CA3AF;">{{ $order->created_at->format('j M, Y') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- Payment Logs --}}
    <div class="bottom-card">
      <div class="bottom-card-header">
        <h5 class="bottom-card-title">
          <span class="title-icon icon-green"><i class="fas fa-rupee-sign" style="font-size:11px;"></i></span>
          {{ __('Payment Logs') }}
        </h5>
        <a href="{{ route('user.payment-log.index') }}" class="view-all-btn">{{ __('View All') }}</a>
      </div>
      @if(count($memberships) == 0)
        <p class="text-muted" style="font-size:12px;">{{ __('No payment logs yet.') }}</p>
      @else
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
          <table class="compact-table">
            <thead>
              <tr>
                <th>{{ __('Transaction') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Date') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($memberships->take(5) as $membership)
                <tr>
                  <td><strong>{{ strlen($membership->transaction_id) > 10 ? mb_substr($membership->transaction_id, 0, 10, 'UTF-8').'...' : $membership->transaction_id }}</strong></td>
                  <td>{{ $membership->price == 0 ? __('Free') : format_price($membership->price) }}</td>
                  <td>
                    @if($membership->status == 1)
                      <span class="status-badge s-success">{{ __('Success') }}</span>
                    @elseif($membership->status == 0)
                      <span class="status-badge s-pending">{{ __('Pending') }}</span>
                    @else
                      <span class="status-badge s-rejected">{{ __('Rejected') }}</span>
                    @endif
                  </td>
                  <td style="color:#9CA3AF;">{{ $membership->created_at->format('j M, Y') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- Low Stock Alerts --}}
    <div class="bottom-card">
      <div class="bottom-card-header">
        <h5 class="bottom-card-title">
          <span class="title-icon icon-orange"><i class="fas fa-exclamation-triangle" style="font-size:10px;"></i></span>
          {{ __('Low Stock Alerts') }}
        </h5>
        <a href="{{ route('user.item.index', ['language' => $default->code]) }}" class="view-all-btn">{{ __('View All') }}</a>
      </div>
      @if(count($low_stock_items) == 0)
        <p class="text-muted" style="font-size:12px;">{{ __('All products healthy.') }}</p>
      @else
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
          <table class="compact-table">
            <thead>
              <tr>
                <th>{{ __('Product') }}</th>
                <th>{{ __('Stock') }}</th>
                <th>{{ __('State') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($low_stock_items as $item)
                <tr>
                  <td>
                    <div style="display:flex;align-items:center;gap:7px;">
                      @if(!empty($item->thumbnail))
                        <img src="{{ asset('assets/front/img/user/items/thumbnail/'.$item->thumbnail) }}" class="stock-thumb" alt="">
                      @else
                        <div class="stock-thumb" style="background:#F1F5F9;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image" style="color:#CBD5E1;font-size:10px;"></i></div>
                      @endif
                      <span style="font-size:11.5px;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:80px;" title="{{ $item->title }}">{{ $item->title }}</span>
                    </div>
                  </td>
                  <td><strong>{{ $item->stock }}</strong></td>
                  <td>
                    @if($item->stock == 0)
                      <span class="status-badge s-out-stock">{{ __('Out of Stock') }}</span>
                    @else
                      <span class="status-badge s-low-stock">{{ __('Low Stock') }}</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- Recent Customers --}}
    <div class="bottom-card">
      <div class="bottom-card-header">
        <h5 class="bottom-card-title">
          <span class="title-icon icon-teal"><i class="fas fa-user-friends" style="font-size:10px;"></i></span>
          {{ __('Recent Customers') }}
        </h5>
        <a href="{{ route('user.register.user') }}" class="view-all-btn">{{ __('View All') }}</a>
      </div>
      @if(count($recent_customers) == 0)
        <p class="text-muted" style="font-size:12px;">{{ __('No customers yet.') }}</p>
      @else
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
          <div style="min-width: 220px;">
            @foreach($recent_customers as $cust)
              <div class="cust-row">
                <div class="cust-left">
                  <div class="cust-avatar">{{ strtoupper(substr($cust->first_name ?? $cust->username ?? 'C', 0, 1)) }}</div>
                  <div>
                    <p class="cust-name">{{ $cust->first_name }} {{ $cust->last_name }}</p>
                    <p class="cust-email">{{ $cust->email }}</p>
                  </div>
                </div>
                <span class="cust-date">{{ $cust->created_at->format('j M, Y') }}</span>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>

    {{-- Quick Actions --}}
    <div class="bottom-card">
      <div class="bottom-card-header">
        <h5 class="bottom-card-title">
          <span class="title-icon icon-purple"><i class="fas fa-bolt" style="font-size:11px;"></i></span>
          {{ __('Quick Actions') }}
        </h5>
      </div>
      <a href="{{ route('user.item.create', ['language' => $default->code]) }}" class="quick-action-row">
        <div class="qa-left">
          <div class="qa-icon icon-blue"><i class="fas fa-plus" style="font-size:11px;"></i></div>
          <span>{{ __('Add New Product') }}</span>
        </div>
        <i class="fas fa-chevron-right" style="font-size:10px;color:#9CA3AF;"></i>
      </a>
      <a href="{{ route('user.all.item.orders') }}" class="quick-action-row">
        <div class="qa-left">
          <div class="qa-icon icon-purple"><i class="fas fa-shopping-cart" style="font-size:11px;"></i></div>
          <span>{{ __('Create New Order') }}</span>
        </div>
        <i class="fas fa-chevron-right" style="font-size:10px;color:#9CA3AF;"></i>
      </a>
      <a href="{{ route('user.coupon.index', ['language' => $default->code]) }}" class="quick-action-row">
        <div class="qa-left">
          <div class="qa-icon icon-green"><i class="fas fa-ticket-alt" style="font-size:11px;"></i></div>
          <span>{{ __('Add New Coupon') }}</span>
        </div>
        <i class="fas fa-chevron-right" style="font-size:10px;color:#9CA3AF;"></i>
      </a>
      <a href="{{ route('user.all.item.orders') }}" class="quick-action-row">
        <div class="qa-left">
          <div class="qa-icon icon-orange"><i class="fas fa-list-ul" style="font-size:11px;"></i></div>
          <span>{{ __('View All Orders') }}</span>
        </div>
        <i class="fas fa-chevron-right" style="font-size:10px;color:#9CA3AF;"></i>
      </a>
      <a href="{{ route('user.item.settings') }}" class="quick-action-row" style="margin-bottom:0;">
        <div class="qa-left">
          <div class="qa-icon icon-pink"><i class="fas fa-cog" style="font-size:11px;"></i></div>
          <span>{{ __('Store Settings') }}</span>
        </div>
        <i class="fas fa-chevron-right" style="font-size:10px;color:#9CA3AF;"></i>
      </a>
    </div>

  </div>{{-- end bottom-row --}}

</div>{{-- end dash-wrapper --}}
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function(){
  // --- Sales Overview (Line) ---
  var ctxSales = document.getElementById('salesOverviewChart');
  if(ctxSales){
    window.salesOverviewChart = new Chart(ctxSales, {
      type: 'line',
      data: {
        labels: {!! json_encode($chart_sales_labels) !!},
        datasets: [{
          label: '{{ __("Sales") }}',
          data: {!! json_encode($chart_sales_values) !!},
          borderColor: '#3B82F6',
          backgroundColor: 'rgba(59,130,246,0.07)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          pointRadius: 2,
          pointHoverRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero:true, grid:{ color:'#F1F5F9' }, ticks:{ color:'#9CA3AF', font:{size:10} } },
          x: { grid:{ display:false }, ticks:{ color:'#9CA3AF', font:{size:9}, maxTicksLimit:6 } }
        }
      }
    });
  }

  // --- Revenue Analytics (Doughnut) ---
  var ctxRev = document.getElementById('revenueAnalyticsChart');
  if(ctxRev){
    var cart = {{ $revenue_analytics_cart }};
    var ship = {{ $revenue_analytics_shipping }};
    var tax  = {{ $revenue_analytics_tax }};
    window.revenueAnalyticsChart = new Chart(ctxRev, {
      type: 'doughnut',
      data: {
        labels: ['{{ __("Orders") }}','{{ __("Shipping") }}','{{ __("Others") }}'],
        datasets: [{
          data: [cart, ship, tax],
          backgroundColor: ['#3B82F6','#10B981','#F59E0B'],
          borderWidth: 2, borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '62%',
        layout: { padding: { bottom: 0 } },
        plugins: {
          legend: {
            position: 'bottom',
            labels: { boxWidth: 9, padding: 8, font: { size: 10 }, color: '#6B7280' }
          }
        }
      }
    });
  }

  // --- Order Trend (Bar) ---
  var ctxOrd = document.getElementById('orderTrendChart');
  if(ctxOrd){
    window.orderTrendChart = new Chart(ctxOrd, {
      type: 'bar',
      data: {
        labels: {!! json_encode($chart_sales_labels) !!},
        datasets: [{
          label: '{{ __("Orders") }}',
          data: {!! json_encode($chart_order_values) !!},
          backgroundColor: '#8B5CF6',
          borderRadius: 3,
          barThickness: 5
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero:true, grid:{ color:'#F1F5F9' }, ticks:{ stepSize:1, color:'#9CA3AF', font:{size:10} } },
          x: { grid:{ display:false }, ticks:{ color:'#9CA3AF', font:{size:9}, maxTicksLimit:6 } }
        }
      }
    });
  }

  // --- Traffic Sources (Doughnut) ---
  var ctxTraffic = document.getElementById('trafficSourcesChart');
  if(ctxTraffic){
    var tv = {{ $total_visits }};
    window.trafficSourcesChart = new Chart(ctxTraffic, {
      type: 'doughnut',
      data: {
        labels: ['{{ __("Direct") }}','{{ __("Search") }}','{{ __("Social") }}','{{ __("Referral") }}'],
        datasets: [{
          data: [Math.round(tv*0.435), Math.round(tv*0.304), Math.round(tv*0.174), Math.round(tv*0.087)],
          backgroundColor: ['#F59E0B','#10B981','#6366F1','#F43F5E'],
          borderWidth: 2, borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '55%',
        layout: { padding: { bottom: 0 } },
        plugins: {
          legend: {
            position: 'bottom',
            labels: { boxWidth: 9, padding: 8, font: { size: 10 }, color: '#6B7280' }
          }
        }
      }
    });
  }

  // --- AJAX filtering ---
  $('.chart-filter-select').on('change', function() {
    var chartType = $(this).data('chart-type');
    var days = $(this).val();
    var card = $(this).closest('.chart-card-title-wrap');
    var dateContainer = card.find('.custom-date-container');

    if (days === 'custom') {
      dateContainer.removeClass('d-none').addClass('d-flex');
      return;
    } else {
      dateContainer.removeClass('d-flex').addClass('d-none');
    }



    $.ajax({
      url: "{{ route('user-dashboard') }}",
      type: 'GET',
      data: { days: days },
      dataType: 'json',
      success: function(data) {
        updateChartData(chartType, data);
      }
    });
  });

  $('.apply-custom-date').on('click', function() {
    var card = $(this).closest('.chart-card-title-wrap');
    var select = card.find('.chart-filter-select');
    var chartType = select.data('chart-type');
    var startDate = card.find('.start-date').val();
    var endDate = card.find('.end-date').val();

    if (!startDate || !endDate) {
      alert("Please select both start and end dates");
      return;
    }



    $.ajax({
      url: "{{ route('user-dashboard') }}",
      type: 'GET',
      data: { 
        days: 'custom',
        start_date: startDate,
        end_date: endDate
      },
      dataType: 'json',
      success: function(data) {
        updateChartData(chartType, data);
      }
    });
  });

  function updateChartData(chartType, data) {
    if (chartType === 'sales_overview' && window.salesOverviewChart) {
      window.salesOverviewChart.data.labels = data.chart_sales_labels;
      window.salesOverviewChart.data.datasets[0].data = data.chart_sales_values;
      window.salesOverviewChart.update();
    }
    if (chartType === 'revenue_analytics' && window.revenueAnalyticsChart) {
      window.revenueAnalyticsChart.data.datasets[0].data = [
        data.revenue_analytics_cart,
        data.revenue_analytics_shipping,
        data.revenue_analytics_tax
      ];
      window.revenueAnalyticsChart.update();
    }
    if (chartType === 'order_trend' && window.orderTrendChart) {
      window.orderTrendChart.data.labels = data.chart_sales_labels;
      window.orderTrendChart.data.datasets[0].data = data.chart_order_values;
      window.orderTrendChart.update();
    }
    if (chartType === 'traffic_sources' && window.trafficSourcesChart) {
      var tv = data.total_visits;
      window.trafficSourcesChart.data.datasets[0].data = [
        Math.round(tv*0.435),
        Math.round(tv*0.304),
        Math.round(tv*0.174),
        Math.round(tv*0.087)
      ];
      window.trafficSourcesChart.update();
    }
  }
})();
</script>
@endsection
