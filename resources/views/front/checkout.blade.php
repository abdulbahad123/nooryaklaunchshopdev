@extends('front.layout')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/front/css/checkout.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    /* ================================================
       Premium Checkout Page — Inline Overrides
    ================================================ */
    :root {
      --co-primary: var(--color-primary, #ff5a2c);
      --co-dark:    #0e1b3d;
      --co-text:    #1e293b;
      --co-muted:   #64748b;
      --co-border:  #e2e8f0;
      --co-bg:      #f8fafc;
    }

    /* Hide default breadcrumb */
    .page-title-area { display: none !important; }

    /* ── Page wrapper ── */
    .co-page {
      background: var(--co-bg);
      min-height: 100vh;
      padding: 60px 0 80px;
    }

    /* ── Section header ── */
    .co-page-header {
      text-align: center;
      margin-bottom: 48px;
    }
    .co-page-header .label-chip {
      display: inline-block;
      font-size: 11px;
      font-weight: 800;
      color: var(--co-primary);
      background: rgba(255, 90, 44, 0.08);
      border: 1px solid rgba(255, 90, 44, 0.14);
      padding: 5px 16px;
      border-radius: 50px;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 14px;
    }
    .co-page-header h1 {
      font-size: 36px;
      font-weight: 800;
      color: var(--co-dark);
      letter-spacing: -0.5px;
      margin-bottom: 10px;
    }
    .co-page-header p {
      font-size: 15px;
      color: var(--co-muted);
      max-width: 480px;
      margin: 0 auto;
    }

    /* ── Step indicators ── */
    .co-steps {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0;
      margin-bottom: 44px;
    }
    .co-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
    }
    .co-step-num {
      width: 40px; height: 40px;
      border-radius: 50%;
      background: var(--co-primary);
      color: #fff;
      font-size: 15px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 14px rgba(255, 90, 44, 0.3);
    }
    .co-step.inactive .co-step-num {
      background: #e2e8f0;
      color: #94a3b8;
      box-shadow: none;
    }
    .co-step-label {
      font-size: 11px;
      font-weight: 700;
      color: var(--co-dark);
      margin-top: 6px;
      letter-spacing: 0.3px;
      white-space: nowrap;
    }
    .co-step.inactive .co-step-label { color: #94a3b8; }
    .co-step-connector {
      width: 70px;
      height: 2px;
      background: #e2e8f0;
      margin-bottom: 28px;
    }
    .co-step-connector.active { background: var(--co-primary); }

    /* ── Card base ── */
    .co-card {
      background: #ffffff;
      border-radius: 18px;
      border: 1px solid var(--co-border);
      box-shadow: 0 2px 16px rgba(0, 0, 0, 0.04);
      padding: 36px;
      margin-bottom: 24px;
    }
    .co-card-title {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 28px;
      padding-bottom: 20px;
      border-bottom: 1px solid #f1f5f9;
    }
    .co-card-title-icon {
      width: 42px; height: 42px;
      background: rgba(255, 90, 44, 0.08);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
      color: var(--co-primary);
    }
    .co-card-title h2 {
      font-size: 18px;
      font-weight: 800;
      color: var(--co-dark);
      margin: 0;
    }

    /* ── Form fields ── */
    .co-field {
      margin-bottom: 20px;
    }
    .co-label {
      display: block;
      font-size: 12.5px;
      font-weight: 700;
      color: #374151;
      margin-bottom: 7px;
      letter-spacing: 0.3px;
    }
    .co-input-wrap {
      position: relative;
    }
    .co-input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 14px;
      pointer-events: none;
      transition: color 0.2s;
    }
    .co-input {
      width: 100%;
      height: 50px;
      padding: 0 16px 0 44px;
      border: 1.5px solid var(--co-border);
      border-radius: 11px;
      font-size: 14.5px;
      color: var(--co-text);
      background: #f8fafc;
      transition: all 0.2s;
      outline: none;
      font-family: inherit;
    }
    .co-input:focus {
      border-color: var(--co-primary);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(255, 90, 44, 0.08);
    }
    .co-input-wrap:focus-within .co-input-icon {
      color: var(--co-primary);
    }
    .co-input[disabled] {
      background: #f1f5f9;
      color: var(--co-muted);
      cursor: not-allowed;
    }

    /* ── Payment method select ── */
    .co-select-wrap {
      position: relative;
    }
    .co-select {
      width: 100%;
      height: 52px;
      padding: 0 44px 0 16px;
      border: 1.5px solid var(--co-border);
      border-radius: 11px;
      font-size: 14.5px;
      color: var(--co-text);
      background: #f8fafc;
      outline: none;
      cursor: pointer;
      appearance: none;
      font-family: inherit;
      transition: all 0.2s;
    }
    .co-select:focus {
      border-color: var(--co-primary);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(255, 90, 44, 0.08);
    }
    .co-select-arrow {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 13px;
      pointer-events: none;
    }

    /* ── Payment method cards (radio style) ── */
    .co-payment-cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
      gap: 12px;
      margin-bottom: 20px;
    }
    .co-payment-card-opt {
      display: none;
    }
    .co-payment-card-label {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 16px 12px;
      border: 1.5px solid var(--co-border);
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.2s;
      background: #f8fafc;
      text-align: center;
    }
    .co-payment-card-label:hover {
      border-color: var(--co-primary);
      background: #fff;
    }
    .co-payment-card-opt:checked + .co-payment-card-label {
      border-color: var(--co-primary);
      background: rgba(255, 90, 44, 0.04);
      box-shadow: 0 0 0 3px rgba(255, 90, 44, 0.1);
    }
    .co-payment-card-icon {
      width: 36px; height: 36px;
      background: rgba(255, 90, 44, 0.08);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      color: var(--co-primary);
    }
    .co-payment-card-name {
      font-size: 12px;
      font-weight: 700;
      color: var(--co-dark);
    }

    /* ── Order Summary Sidebar ── */
    .co-summary-card {
      background: #ffffff;
      border-radius: 18px;
      border: 1px solid var(--co-border);
      box-shadow: 0 2px 16px rgba(0, 0, 0, 0.04);
      padding: 32px;
      position: sticky;
      top: 24px;
    }
    .co-summary-title {
      font-size: 17px;
      font-weight: 800;
      color: var(--co-dark);
      margin-bottom: 24px;
      padding-bottom: 16px;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .co-summary-title i {
      color: var(--co-primary);
    }

    /* Package badge */
    .co-pkg-badge {
      background: linear-gradient(135deg, var(--co-dark) 0%, #1a3060 100%);
      border-radius: 14px;
      padding: 20px;
      margin-bottom: 24px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .co-pkg-badge::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(circle at 80% 20%, rgba(255, 90, 44, 0.3) 0%, transparent 50%);
      pointer-events: none;
    }
    .co-pkg-label {
      font-size: 10px;
      font-weight: 800;
      color: rgba(255, 255, 255, 0.6);
      letter-spacing: 1.5px;
      text-transform: uppercase;
      display: block;
      margin-bottom: 6px;
    }
    .co-pkg-name {
      font-size: 22px;
      font-weight: 800;
      color: #ffffff;
      display: block;
      margin-bottom: 4px;
    }
    .co-pkg-term {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.65);
    }

    /* Summary rows */
    .co-summary-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px dashed #f1f5f9;
      font-size: 14px;
      color: var(--co-muted);
    }
    .co-summary-row:last-of-type {
      border-bottom: none;
    }
    .co-summary-row .key {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
    }
    .co-summary-row .key i {
      color: var(--co-primary);
      font-size: 13px;
      width: 16px;
    }
    .co-summary-row .val {
      font-weight: 700;
      color: var(--co-dark);
    }

    /* Total row */
    .co-total-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(255, 90, 44, 0.05);
      border: 1px solid rgba(255, 90, 44, 0.12);
      border-radius: 12px;
      padding: 16px 20px;
      margin-top: 20px;
    }
    .co-total-label {
      font-size: 14px;
      font-weight: 800;
      color: var(--co-dark);
      letter-spacing: 0.3px;
    }
    .co-total-amount {
      font-size: 26px;
      font-weight: 900;
      color: var(--co-primary);
      letter-spacing: -0.5px;
    }

    /* Trust section */
    .co-trust-list {
      margin-top: 24px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .co-trust-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 12.5px;
      font-weight: 600;
      color: var(--co-muted);
    }
    .co-trust-item i {
      color: #10b981;
      font-size: 14px;
    }

    /* ── Submit Button ── */
    .co-submit-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      width: 100%;
      height: 58px;
      background: var(--co-primary);
      color: #ffffff;
      border: none;
      border-radius: 14px;
      font-size: 16px;
      font-weight: 800;
      cursor: pointer;
      letter-spacing: 0.3px;
      transition: all 0.25s;
      font-family: inherit;
      box-shadow: 0 6px 20px rgba(255, 90, 44, 0.3);
      margin-top: 24px;
    }
    .co-submit-btn:hover {
      background: #e0451a;
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(255, 90, 44, 0.38);
    }
    .co-submit-btn:active {
      transform: translateY(0);
    }

    /* ── Stripe element wrapper ── */
    #stripe-element {
      padding: 14px 16px;
      border: 1.5px solid var(--co-border);
      border-radius: 11px;
      background: #f8fafc;
      margin-bottom: 8px;
    }

    /* ── Validation error highlight ── */
    .co-input.is-invalid {
      border-color: #ef4444 !important;
      background: #fdf2f2 !important;
    }
    .co-input.is-invalid:focus {
      box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08) !important;
    }
    .is-invalid-grid .co-pay-method-card {
      border-color: rgba(239, 68, 68, 0.4) !important;
    }

    /* ── Payment methods grid ── */
    .co-payment-methods-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }
    .co-pay-method-card {
      position: relative;
      background: #ffffff;
      border: 1.5px solid var(--co-border);
      border-radius: 14px;
      padding: 20px 16px;
      text-align: center;
      cursor: pointer;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }
    .co-pay-method-card:hover {
      border-color: var(--co-primary);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
    }
    .co-pay-method-card.active {
      border-color: var(--co-primary);
      background: rgba(255, 90, 44, 0.03);
      box-shadow: 0 0 0 4px rgba(255, 90, 44, 0.08);
    }
    .co-pay-method-card-icon {
      font-size: 26px;
      color: #64748b;
      transition: color 0.2s;
    }
    .co-pay-method-card:hover .co-pay-method-card-icon,
    .co-pay-method-card.active .co-pay-method-card-icon {
      color: var(--co-primary);
    }
    .co-pay-method-card-title {
      font-size: 13px;
      font-weight: 700;
      color: var(--co-dark);
    }
    .co-pay-method-card-check {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 14px;
      color: var(--co-primary);
      opacity: 0;
      transform: scale(0.6);
      transition: all 0.2s ease;
    }
    .co-pay-method-card.active .co-pay-method-card-check {
      opacity: 1;
      transform: scale(1);
    }

    /* ── Offline instructions UI overrides ── */
    #instructions {
      margin-top: 16px;
    }
    #instructions .gateway-desc {
      background: #f8fafc;
      border: 1px solid var(--co-border);
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 16px;
      font-size: 14px;
      color: var(--co-text);
      line-height: 1.6;
    }
    #instructions .form-element label {
      font-size: 12.5px;
      font-weight: 700;
      color: #374151 !important;
      margin-bottom: 7px;
    }
    #instructions .file-input {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px dashed var(--co-border);
      border-radius: 11px;
      background: #f8fafc;
      font-size: 14px;
      color: var(--co-text);
      outline: none;
      transition: all 0.2s;
    }
    #instructions .file-input:focus {
      border-color: var(--co-primary);
    }

    /* ── Premium SweetAlert2 overrides ── */
    .swal2-premium-popup {
      border-radius: 20px !important;
      padding: 30px !important;
      font-family: inherit !important;
    }
    .swal2-premium-title {
      font-size: 20px !important;
      font-weight: 800 !important;
      color: var(--co-dark) !important;
    }
    .swal2-premium-confirm {
      background-color: var(--co-primary) !important;
      border-radius: 10px !important;
      padding: 12px 30px !important;
      font-weight: 700 !important;
      font-size: 15px !important;
    }

    /* ── Responsive ── */
    @media (max-width: 991.98px) {
      .co-summary-card { position: static; }
      .co-page { padding: 40px 0 60px; }
      .co-card { padding: 24px; }
    }
    @media (max-width: 767.98px) {
      .co-page-header h1 { font-size: 28px; }
      .co-steps { gap: 0; }
      .co-step-connector { width: 40px; }
      .co-card { padding: 20px 16px; }
    }
    @media (max-width: 575.98px) {
      .co-page-header h1 { font-size: 24px; }
    }
  </style>
@endsection

@section('pagename')
  - {{ $pageHeading ?? __('Checkout') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->checkout_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->checkout_meta_keywords : '')

@php
  $d_none  = 'none';
  $d_block = 'block';

  if (!function_exists('getGatewayIcon')) {
      function getGatewayIcon($name) {
          $name = strtolower($name);
          if (strpos($name, 'paypal') !== false) return 'fab fa-paypal';
          if (strpos($name, 'stripe') !== false) return 'fab fa-stripe';
          if (strpos($name, 'razorpay') !== false) return 'fal fa-credit-card';
          if (strpos($name, 'paystack') !== false) return 'fal fa-wallet';
          if (strpos($name, 'flutterwave') !== false) return 'fal fa-money-bill-wave';
          if (strpos($name, 'authorize') !== false) return 'fal fa-university';
          if (strpos($name, 'iyzico') !== false) return 'fal fa-credit-card';
          if (strpos($name, 'paytm') !== false) return 'fal fa-money-check';
          if (strpos($name, 'offline') !== false) return 'fal fa-money-bill-wave';
          return 'fal fa-credit-card';
      }
  }
@endphp

@section('breadcrumb-title')
  {{ $pageHeading ?? __('Checkout') }}
@endsection
@section('breadcrumb-link')
  {{ $pageHeading ?? __('Checkout') }}
@endsection

@section('content')
<section class="co-page">
  <div class="container">

    {{-- Page Header --}}
    <div class="co-page-header">
      <span class="label-chip">{{ __('Secure Checkout') }}</span>
      <h1>{{ __('Complete Your Order') }}</h1>
      <p>{{ __('You\'re one step away from launching your dream store. Fill in the details below.') }}</p>
    </div>

    {{-- Step indicators --}}
    <div class="co-steps">
      <div class="co-step">
        <div class="co-step-num">1</div>
        <div class="co-step-label">{{ __('Plan') }}</div>
      </div>
      <div class="co-step-connector active"></div>
      <div class="co-step">
        <div class="co-step-num">2</div>
        <div class="co-step-label">{{ __('Details') }}</div>
      </div>
      <div class="co-step-connector"></div>
      <div class="co-step inactive">
        <div class="co-step-num">3</div>
        <div class="co-step-label">{{ __('Launch') }}</div>
      </div>
    </div>

    {{-- Main checkout form --}}
    <form action="{{ route('front.membership.checkout') }}" method="POST" enctype="multipart/form-data" id="my-checkout-form" novalidate>
      @csrf

      {{-- Hidden fields --}}
      <input type="hidden" name="first_name"        value="{{ $data['first_name'] }}">
      <input type="hidden" name="category"          value="{{ $data['category'] }}">
      <input type="hidden" name="username"          value="{{ $data['username'] }}">
      <input type="hidden" name="password"          value="{{ $data['password'] }}">
      <input type="hidden" name="package_type"      value="{{ $data['status'] }}">
      <input type="hidden" name="email"             value="{{ $data['email'] }}">
      <input type="hidden" name="price"             value="{{ $data['status'] == 'trial' ? 0 : $data['package']->price }}">
      <input type="hidden" name="package_id"        value="{{ $data['id'] }}">
      <input type="hidden" name="payment_method"    id="payment" value="{{ old('payment_method') }}">
      <input type="hidden" name="trial_days"        id="trial_days" value="{{ $data['package']->trial_days }}">
      <input type="hidden" name="start_date"        value="{{ \Carbon\Carbon::today()->format('d-m-Y') }}">
      <input type="hidden" name="selected_template" value="{{ $data['selected_template'] ?? '' }}">
      @if ($data['status'] === 'trial')
        <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::today()->addDay($data['package']->trial_days)->format('d-m-Y') }}">
      @else
        @if ($data['package']->term === 'daily')
          <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::today()->addDay()->format('d-m-Y') }}">
        @elseif($data['package']->term === 'weekly')
          <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::today()->addWeek()->format('d-m-Y') }}">
        @elseif($data['package']->term === 'monthly')
          <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::today()->addMonth()->format('d-m-Y') }}">
        @elseif($data['package']->term === 'lifetime')
          <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::maxValue()->format('d-m-Y') }}">
        @else
          <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::today()->addYear()->format('d-m-Y') }}">
        @endif
      @endif

      <div class="row g-4">

        {{-- ===================== LEFT COLUMN ===================== --}}
        <div class="col-lg-7">

          {{-- Billing Details Card --}}
          <div class="co-card">
            <div class="co-card-title">
              <div class="co-card-title-icon">
                <i class="fal fa-user-circle"></i>
              </div>
              <h2>{{ __('Billing Details') }}</h2>
            </div>

            <div class="row g-0">
              <div class="col-12 col-sm-6 pe-sm-2">
                <div class="co-field">
                  <label class="co-label" for="shop_name">{{ __('Shop Name') }} *</label>
                  <div class="co-input-wrap">
                    <i class="fal fa-store co-input-icon"></i>
                    <input id="shop_name" type="text" class="co-input" name="shop_name"
                      placeholder="{{ __('My Awesome Store') }}" value="{{ old('shop_name', ucfirst($data['username'] ?? '')) }}" required>
                  </div>
                  @if ($errors->has('shop_name'))
                    <p class="co-error">{{ $errors->first('shop_name') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-3 col-sm-2 px-sm-2">
                <div class="co-field">
                  <label class="co-label" for="country_code">{{ __('Code') }} *</label>
                  <div class="co-input-wrap">
                    <i class="fal fa-globe co-input-icon"></i>
                    <input id="country_code" type="text" class="co-input" name="country_code"
                      placeholder="{{ __('Code') }}" value="{{ old('country_code', $data['country_code'] ?? '+91') }}" required inputmode="numeric">
                  </div>
                  @if ($errors->has('country_code'))
                    <p class="co-error">{{ $errors->first('country_code') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-9 col-sm-4 ps-sm-2">
                <div class="co-field">
                  <label class="co-label" for="phone">{{ __('Phone') }} *</label>
                  <div class="co-input-wrap">
                    <i class="fal fa-phone co-input-icon"></i>
                    <input id="phone" type="number" class="co-input" name="phone"
                      placeholder="{{ __('Phone') }}" value="{{ old('phone', $data['phone'] ?? '') }}" required min="0" step="1" inputmode="numeric">
                  </div>
                  @if ($errors->has('phone'))
                    <p class="co-error">{{ $errors->first('phone') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12">
                <div class="co-field">
                  <label class="co-label" for="email">{{ __('Email Address') }} *</label>
                  <div class="co-input-wrap">
                    <i class="fal fa-envelope co-input-icon"></i>
                    <input id="email" type="email" class="co-input" name="email"
                      value="{{ $data['email'] }}" disabled>
                  </div>
                  @if ($errors->has('email'))
                    <p class="co-error">{{ $errors->first('email') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12">
                <div class="co-field">
                  <label class="co-label" for="address">{{ __('Street Address') }}</label>
                  <div class="co-input-wrap">
                    <i class="fal fa-map-marker-alt co-input-icon"></i>
                    <input id="address" type="text" class="co-input" name="address"
                      placeholder="{{ __('Street Address') }}" value="{{ old('address') }}">
                  </div>
                  @if ($errors->has('address'))
                    <p class="co-error">{{ $errors->first('address') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-sm-6 pe-sm-2">
                <div class="co-field">
                  <label class="co-label" for="city">{{ __('City') }} *</label>
                  <div class="co-input-wrap">
                    <i class="fal fa-city co-input-icon"></i>
                    <input id="city" type="text" class="co-input" name="city"
                      placeholder="{{ __('City') }}" value="{{ old('city') }}" required>
                  </div>
                  @if ($errors->has('city'))
                    <p class="co-error">{{ $errors->first('city') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-sm-6 ps-sm-2">
                <div class="co-field">
                  <label class="co-label" for="district">{{ __('State') }}</label>
                  <div class="co-input-wrap">
                    <i class="fal fa-map co-input-icon"></i>
                    <input id="district" type="text" class="co-input" name="district"
                      placeholder="{{ __('State') }}" value="{{ old('district') }}">
                  </div>
                  @if ($errors->has('district'))
                    <p class="co-error">{{ $errors->first('district') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-sm-6 pe-sm-2">
                <div class="co-field">
                  <label class="co-label" for="post_code">{{ __('Postcode / Zip') }}</label>
                  <div class="co-input-wrap">
                    <i class="fal fa-mail-bulk co-input-icon"></i>
                    <input id="post_code" type="text" class="co-input" name="post_code"
                      placeholder="{{ __('Post Code') }}" value="{{ old('post_code') }}">
                  </div>
                  @if ($errors->has('post_code'))
                    <p class="co-error">{{ $errors->first('post_code') }}</p>
                  @endif
                </div>
              </div>
              <div class="col-12 col-sm-6 ps-sm-2">
                <div class="co-field">
                  <label class="co-label" for="country">{{ __('Country') }} *</label>
                  <div class="co-input-wrap">
                    <i class="fal fa-globe co-input-icon"></i>
                    <input id="country" type="text" class="co-input" name="country"
                      placeholder="{{ __('Country') }}" value="{{ old('country') }}" required>
                  </div>
                  @if ($errors->has('country'))
                    <p class="co-error">{{ $errors->first('country') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Payment Method Card (only when not free/trial) --}}
          @if ($data['package']->price != 0 && $data['status'] != 'trial')
          <div class="co-card">
            <div class="co-card-title">
              <div class="co-card-title-icon">
                <i class="fal fa-credit-card"></i>
              </div>
              <h2>{{ __('Payment Method') }}</h2>
            </div>

            <div class="co-field">
              {{-- Visually hide but keep select functional for forms --}}
              <div class="co-select-wrap d-none">
                <select id="payment-gateway" name="payment_method" class="co-select">
                  <option value="" selected disabled>{{ __('Choose a payment method') }}</option>
                  @foreach ($data['payment_methods'] as $payment_method)
                    <option value="{{ $payment_method->name }}"
                      {{ old('payment_method') == $payment_method->name ? 'selected' : '' }}>
                      {{ __($payment_method->name) }}
                    </option>
                  @endforeach
                </select>
                <i class="fal fa-chevron-down co-select-arrow"></i>
              </div>

              {{-- Beautiful grid of visual payment methods --}}
              <div class="co-payment-methods-grid">
                @foreach ($data['payment_methods'] as $payment_method)
                  @php
                    $methodName = $payment_method->name;
                    $iconClass = getGatewayIcon($methodName);
                    $isSelected = old('payment_method') == $methodName;
                  @endphp
                  <div class="co-pay-method-card {{ $isSelected ? 'active' : '' }}" data-value="{{ $methodName }}">
                    <div class="co-pay-method-card-check">
                      <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="co-pay-method-card-icon">
                      <i class="{{ $iconClass }}"></i>
                    </div>
                    <div class="co-pay-method-card-title">
                      {{ __($methodName) }}
                    </div>
                  </div>
                @endforeach
              </div>

              @if ($errors->has('payment_method'))
                <p class="co-error"><strong>{{ $errors->first('payment_method') }}</strong></p>
              @endif
            </div>

            {{-- Iyzico --}}
            <div class="iyzico-element {{ old('payment_method') == 'Iyzico' ? '' : 'd-none' }} mt-3">
              <div class="co-input-wrap">
                <i class="fal fa-id-card co-input-icon"></i>
                <input type="text" name="identity_number" class="co-input"
                  placeholder="{{ __('Identity Number') }}" value="{{ old('identity_number') }}">
              </div>
              @error('identity_number')
                <p class="co-error">{{ $message }}</p>
              @enderror
            </div>

            {{-- Stripe --}}
            <div class="row gateway-details py-3" id="tab-stripe"
              style="display: {{ old('payment_method') == 'Stripe' ? $d_block : $d_none }};">
              <div class="col-md-12">
                <div id="stripe-element" class="mb-2"></div>
                <div id="stripe-errors" class="co-error" role="alert"></div>
              </div>
            </div>
          </div>
          @endif

          {{-- Authorize.net --}}
          <div class="row gateway-details py-3" id="tab-anet"
            style="display: {{ old('payment_method') == 'Authorize.net' ? $d_block : $d_none }}">
            <div class="co-card w-100">
              <div class="co-card-title mb-3">
                <div class="co-card-title-icon"><i class="fal fa-university"></i></div>
                <h2>{{ __('Card Details') }}</h2>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="co-input-wrap mb-3">
                    <i class="fal fa-credit-card co-input-icon"></i>
                    <input class="co-input" type="text" id="anetCardNumber" placeholder="{{ __('Card Number') }}" disabled>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="co-input-wrap mb-3">
                    <i class="fal fa-calendar co-input-icon"></i>
                    <input class="co-input" type="text" id="anetExpMonth" placeholder="{{ __('Expire Month') }}" disabled>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="co-input-wrap mb-3">
                    <i class="fal fa-calendar-alt co-input-icon"></i>
                    <input class="co-input" type="text" id="anetExpYear" placeholder="{{ __('Expire Year') }}" disabled>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="co-input-wrap mb-3">
                    <i class="fal fa-lock co-input-icon"></i>
                    <input class="co-input" type="text" id="anetCardCode" placeholder="{{ __('Card Code') }}" disabled>
                  </div>
                </div>
              </div>
              <input type="hidden" name="opaqueDataValue" id="opaqueDataValue" disabled>
              <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor" disabled>
              <ul id="anetErrors" style="display: none; color: #ef4444; list-style: none; padding: 0;"></ul>
            </div>
          </div>

          {{-- Offline Gateway Receipt --}}
          <div>
            <div id="instructions"></div>
            <input type="hidden" name="is_receipt" value="0" id="is_receipt">
            @if ($errors->has('receipt'))
              <p class="co-error"><strong>{{ $errors->first('receipt') }}</strong></p>
            @endif
          </div>

        </div>

        {{-- ===================== RIGHT COLUMN (Summary) ===================== --}}
        <div class="col-lg-5">
          <div class="co-summary-card">

            <div class="co-summary-title">
              <i class="fal fa-shopping-bag"></i>
              {{ __('Order Summary') }}
            </div>

            {{-- Package badge --}}
            <div class="co-pkg-badge">
              <span class="co-pkg-label">{{ __('Selected Plan') }}</span>
              <span class="co-pkg-name">{{ $data['package']->title }}</span>
              <span class="co-pkg-term">
                @if ($data['status'] === 'trial')
                  {{ $data['package']->trial_days }} {{ __('days free trial') }}
                @elseif($data['package']->term === 'lifetime')
                  {{ __('One-time payment') }}
                @else
                  {{ ucfirst($data['package']->term ?? 'plan') }} {{ __('subscription') }}
                @endif
              </span>
            </div>

            {{-- Details rows --}}
            <div class="co-summary-row">
              <div class="key"><i class="fal fa-calendar-check"></i> {{ __('Start Date') }}</div>
              <div class="val">{{ \Carbon\Carbon::today()->format('d-m-Y') }}</div>
            </div>
            <div class="co-summary-row">
              <div class="key"><i class="fal fa-calendar-times"></i> {{ __('Expiry Date') }}</div>
              <div class="val">
                @if ($data['status'] === 'trial')
                  {{ \Carbon\Carbon::today()->addDay($data['package']->trial_days)->format('d-m-Y') }}
                @elseif($data['package']->term === 'daily')
                  {{ \Carbon\Carbon::today()->addDay()->format('d-m-Y') }}
                @elseif($data['package']->term === 'weekly')
                  {{ \Carbon\Carbon::today()->addWeek()->format('d-m-Y') }}
                @elseif($data['package']->term === 'monthly')
                  {{ \Carbon\Carbon::today()->addMonth()->format('d-m-Y') }}
                @elseif($data['package']->term === 'lifetime')
                  <span style="color: #10b981; font-weight: 800;">{{ __('Lifetime') }}</span>
                @else
                  {{ \Carbon\Carbon::today()->addYear()->format('d-m-Y') }}
                @endif
              </div>
            </div>

            {{-- Total --}}
            <div class="co-total-row">
              <div class="co-total-label">{{ __('Total Due') }}</div>
              <div class="co-total-amount">
                @if ($data['status'] === 'trial')
                  {{ __('Free') }}
                @elseif($data['package']->price == 0)
                  {{ __('Free') }}
                @else
                  {{ format_price($data['package']->price) }}
                @endif
              </div>
            </div>

            {{-- Submit button --}}
            <button id="confirmBtn" form="my-checkout-form" class="co-submit-btn" type="submit">
              <i class="fas fa-lock"></i> {{ __('Place Order') }}
            </button>

            {{-- Trust indicators --}}
            <div class="co-trust-list">
              <div class="co-trust-item"><i class="fas fa-shield-alt"></i> {{ __('SSL secured & encrypted payment') }}</div>
              <div class="co-trust-item"><i class="fas fa-check-circle"></i> {{ __('Instant store activation') }}</div>
              <div class="co-trust-item"><i class="fas fa-headset"></i> {{ __('24/7 customer support') }}</div>
            </div>

          </div>
        </div>

      </div>
    </form>
  </div>
</section>
@endsection

@section('scripts')
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    @php
      $stripe      = App\Models\PaymentGateway::where('keyword', 'stripe')->first();
      $stripe_info = $stripe->information ? $stripe->convertAutoData() : [];

      $anet     = App\Models\PaymentGateway::find(20);
      $anerInfo = $anet ? $anet->convertAutoData() : [];

      $anetTest = $anerInfo['sandbox_check'] ?? 0;
      $anetSrc  = $anetTest == 1 ? 'https://jstest.authorize.net/v1/Accept.js' : 'https://js.authorize.net/v1/Accept.js';

      $authorize_public_key = $anerInfo['public_key']  ?? '';
      $authorize_login_id   = $anerInfo['login_id']    ?? '';
      $stripe_key           = $stripe_info['key']      ?? '';
    @endphp

    let stripe_key      = "{{ $stripe_key }}";
    let offline         = {!! json_encode($data['offline']) !!};
    var instruction_url = "{{ route('front.payment.instructions') }}";
    var processing_text = "{{ __('Processing') }}";
    var confirm_text    = "{{ __('Confirm') }}";
    var receiptTxt      = "{{ __('Receipt image must be') }}";
    var package_price   = {{ $data['status'] == 'trial' ? 0 : $data['package']->price }};
    var is_trial        = "{{ $data['status'] == 'trial' ? '1' : '0' }}";
  </script>

  <script type="text/javascript" src="{{ $anetSrc }}" charset="utf-8"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('assets/front/js/membership-checkout.js') }}"></script>
@endsection
