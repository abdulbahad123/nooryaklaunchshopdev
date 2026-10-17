@extends('website_builder.agency_template.admin.layout')

@section('title', 'Subscription & Packages | Website Builder')

@section('content')
@php
  $renewActionUrl = url('agency-admin/subscription/renew');
  try {
      if (\Illuminate\Support\Facades\Route::has('website-builder.agency-admin.subscription.renew')) {
          $renewActionUrl = route('website-builder.agency-admin.subscription.renew');
      } elseif (\Illuminate\Support\Facades\Route::has('wb-subdomain.agency-admin.subscription.renew')) {
          $renewActionUrl = route('wb-subdomain.agency-admin.subscription.renew');
      }
  } catch (\Throwable $e) {}

  $currExpireFormatted = !empty($customer->expire_date) ? \Carbon\Carbon::parse($customer->expire_date)->format('M d, Y h:i A') : 'Unlimited';
  $currExpireIso = !empty($customer->expire_date) ? \Carbon\Carbon::parse($customer->expire_date)->toIso8601String() : '';
@endphp

<div class="content-header d-flex align-items-center justify-content-between mb-4 flex-wrap" style="gap: 16px;">
  <div>
    <h2 class="h4 font-weight-bold mb-1 text-dark">
      <i class="fas fa-crown text-warning mr-2"></i> {{ __('Subscription & Packages') }}
    </h2>
    <p class="text-muted mb-0 font-weight-medium">
      {{ __('Manage your website subscription, renew your plan, or switch between Monthly and Yearly packages.') }}
    </p>
  </div>
  <div>
    @if (!empty($currentPackage))
      <button type="button" class="btn btn-primary font-weight-bold px-4 py-2 shadow-sm" style="border-radius: 10px; background: #6366f1; border-color: #6366f1;" onclick="openWbRenewModal({{ $currentPackage->id ?? 1 }}, '{{ addslashes($currentPackage->name ?? 'Current Plan') }}', {{ round($currentPackage->monthly_price ?? 39) }}, {{ round($currentPackage->yearly_price ?? 390) }}, 'monthly')">
        <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew Plan') }}
      </button>
    @endif
  </div>
</div>

{{-- Top Status Banner --}}
@if ($isExpired)
  <div class="alert alert-danger shadow-sm mb-4 p-4" style="border-radius: 16px; border-left: 6px solid #ef4444; background: #fff; border-top: 1px solid #fee2e2; border-right: 1px solid #fee2e2; border-bottom: 1px solid #fee2e2;">
    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 16px;">
      <div class="d-flex align-items-center" style="gap: 14px;">
        <div style="width: 48px; height: 48px; min-width: 48px; border-radius: 12px; background: rgba(239, 68, 68, 0.12); display: flex; align-items: center; justify-content: center; font-size: 22px; color: #ef4444;">
          <i class="fas fa-ban"></i>
        </div>
        <div>
          <h5 class="font-weight-bold text-danger mb-1">
            {{ __('Your Subscription Has Expired') }}
          </h5>
          <p class="mb-0 text-muted font-weight-medium">
            {{ __('Your plan expired on') }} <strong>{{ $currExpireFormatted }}</strong>. {{ __('Renew your plan now to immediately keep your live website online.') }}
          </p>
        </div>
      </div>
      @if (!empty($currentPackage))
        <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
          <button type="button" class="btn btn-danger font-weight-bold px-4 py-2 shadow-sm" style="border-radius: 10px;" onclick="openWbRenewModal({{ $currentPackage->id ?? 1 }}, '{{ addslashes($currentPackage->name ?? 'Current Plan') }}', {{ round($currentPackage->monthly_price ?? 39) }}, {{ round($currentPackage->yearly_price ?? 390) }}, 'monthly')">
            <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew Plan Now') }}
          </button>
          <button type="button" class="btn btn-outline-danger font-weight-bold px-4 py-2 shadow-sm" style="border-radius: 10px; background: #fff;" onclick="openWbRenewModal({{ $currentPackage->id ?? 1 }}, '{{ addslashes($currentPackage->name ?? 'Current Plan') }}', {{ round($currentPackage->monthly_price ?? 39) }}, {{ round($currentPackage->yearly_price ?? 390) }}, 'yearly')">
            <i class="fas fa-calendar-plus mr-1"></i> {{ __('Extend Plan (Yearly)') }}
          </button>
        </div>
      @endif
    </div>
  </div>
@else
  <div class="alert alert-success shadow-sm mb-4 p-4" style="border-radius: 16px; border-left: 6px solid #10b981; background: #fff; border-top: 1px solid #d1fae5; border-right: 1px solid #d1fae5; border-bottom: 1px solid #d1fae5;">
    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 16px;">
      <div class="d-flex align-items-center" style="gap: 14px;">
        <div style="width: 48px; height: 48px; min-width: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: center; font-size: 22px; color: #10b981;">
          <i class="fas fa-check-circle"></i>
        </div>
        <div>
          <h5 class="font-weight-bold text-success mb-1">
            {{ __('Your Subscription is Active') }}
          </h5>
          <p class="mb-0 text-muted font-weight-medium">
            {{ __('Current Plan:') }} <strong>{{ $currentPackage->name ?? 'Website Plan' }}</strong> â€” {{ __('Valid until') }} <strong>{{ $currExpireFormatted }}</strong>.
          </p>
        </div>
      </div>
      <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
        <span class="badge badge-success px-3 py-2 font-weight-bold mr-2" style="border-radius: 20px; font-size: 13px; background: #d1fae5; color: #065f46;">
          <i class="fas fa-shield-alt mr-1"></i> {{ __('Plan Active') }}
        </span>
        @if (!empty($currentPackage))
          <button type="button" class="btn btn-primary font-weight-bold px-3 py-2 shadow-sm" style="border-radius: 10px; font-size: 13px; background: #6366f1; border-color: #6366f1;" onclick="openWbRenewModal({{ $currentPackage->id ?? 1 }}, '{{ addslashes($currentPackage->name ?? 'Current Plan') }}', {{ round($currentPackage->monthly_price ?? 39) }}, {{ round($currentPackage->yearly_price ?? 390) }}, 'monthly')">
            <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew Plan') }}
          </button>
          <button type="button" class="btn btn-success font-weight-bold px-3 py-2 shadow-sm" style="border-radius: 10px; font-size: 13px; background: #10b981; border-color: #10b981;" onclick="openWbRenewModal({{ $currentPackage->id ?? 1 }}, '{{ addslashes($currentPackage->name ?? 'Current Plan') }}', {{ round($currentPackage->monthly_price ?? 39) }}, {{ round($currentPackage->yearly_price ?? 390) }}, 'yearly')">
            <i class="fas fa-calendar-plus mr-1"></i> {{ __('Extend Plan') }}
          </button>
        @endif
      </div>
    </div>
  </div>
@endif

{{-- Monthly / Yearly Billing Cycle Switcher --}}
<div class="text-center mb-4">
  <div class="d-inline-flex align-items-center p-1 bg-white shadow-sm" style="border-radius: 30px; border: 1.5px solid #e2e8f0;">
    <button type="button" id="btnMonthly" class="btn btn-sm px-4 py-2 font-weight-bold" style="border-radius: 25px; background: #6366f1; color: #ffffff;" onclick="switchWbBilling('monthly')">
      {{ __('Monthly Billing') }}
    </button>
    <button type="button" id="btnYearly" class="btn btn-sm px-4 py-2 font-weight-bold text-muted" style="border-radius: 25px; background: transparent;" onclick="switchWbBilling('yearly')">
      {{ __('Yearly Billing') }} <span class="badge badge-success ml-1" style="border-radius: 10px; font-size: 10px; padding: 3px 7px;">{{ __('Save 20%') }}</span>
    </button>
  </div>
</div>

{{-- Package Cards Grid --}}
<div class="row">
  @foreach ($packages as $pkg)
    @php
      $isCurrentPlan = (!empty($customer->package_id) && $customer->package_id == $pkg->id) || ($loop->first && empty($customer->package_id));
      $isExpiredThis = $isExpired && $isCurrentPlan;
      $monthlyPrice  = round($pkg->monthly_price ?? 29);
      $yearlyPrice   = round($pkg->yearly_price ?? ($monthlyPrice * 10));
    @endphp
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 shadow-sm" style="border-radius: 18px; border: 2px solid {{ $isCurrentPlan ? '#6366f1' : '#e2e8f0' }}; background: #fff; transition: all 0.25s ease;">
        <div class="card-body p-4 d-flex flex-column">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="font-weight-bold mb-0 text-dark">{{ $pkg->name }}</h4>
            @if ($isExpiredThis)
              <span class="badge badge-danger px-3 py-2 font-weight-bold" style="border-radius: 20px; font-size: 11px;">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ __('Expired') }}
              </span>
            @elseif ($isCurrentPlan)
              <span class="badge badge-primary px-3 py-2 font-weight-bold" style="border-radius: 20px; font-size: 11px; background: #6366f1;">
                <i class="fas fa-check mr-1"></i> {{ __('Current Plan') }}
              </span>
            @endif
          </div>

          <div class="mb-4">
            <span class="h2 font-weight-bold text-dark pkg-price-val" data-monthly="{{ $monthlyPrice }}" data-yearly="{{ $yearlyPrice }}">â‚¹{{ $monthlyPrice }}</span>
            <span class="text-muted font-weight-bold pkg-period-label">/{{ __('month') }}</span>
          </div>

          <ul class="list-unstyled mb-4 flex-grow-1" style="font-size: 0.95rem; line-height: 2;">
            <li class="mb-2 text-dark">
              <i class="fas fa-check-circle text-success mr-2"></i>
              <strong>{{ __('Portfolio Projects') }}:</strong> {{ $pkg->portfolio_limit ?? 10 }}
            </li>
            <li class="mb-2 text-dark">
              <i class="fas fa-check-circle text-success mr-2"></i>
              <strong>{{ __('Services Listed') }}:</strong> {{ $pkg->services_limit ?? 10 }}
            </li>
            <li class="mb-2 text-dark">
              <i class="fas fa-check-circle text-success mr-2"></i>
              <strong>{{ __('Blog Articles') }}:</strong> {{ $pkg->blog_limit ?? 10 }}
            </li>
            <li class="mb-2 text-dark">
              <i class="fas fa-check-circle text-success mr-2"></i>
              <strong>{{ __('Custom Domain') }}:</strong> {{ __('Supported') }}
            </li>
            <li class="mb-2 text-dark">
              <i class="fas fa-check-circle text-success mr-2"></i>
              <strong>{{ __('Contact Form Leads') }}:</strong> {{ __('Instant Storage') }}
            </li>
          </ul>

          <div class="mt-auto">
            @if ($isExpiredThis)
              {{-- When plan is expired, display Renew Plan and Extend Plan modal triggers --}}
              <div class="d-flex flex-column" style="gap: 8px;">
                <button type="button" class="btn btn-danger btn-block font-weight-bold py-2 shadow-sm" style="border-radius: 12px; font-size: 0.95rem;" onclick="openWbRenewModal({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', {{ $monthlyPrice }}, {{ $yearlyPrice }}, 'monthly')">
                  <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew Plan') }}
                </button>
                <button type="button" class="btn btn-outline-danger btn-block font-weight-bold py-2 shadow-sm" style="border-radius: 12px; font-size: 0.95rem; background: #fff;" onclick="openWbRenewModal({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', {{ $monthlyPrice }}, {{ $yearlyPrice }}, 'yearly')">
                  <i class="fas fa-calendar-plus mr-1"></i> {{ __('Extend Plan (Yearly)') }}
                </button>
              </div>
            @elseif ($isCurrentPlan)
              {{-- Active current plan: display Renew Plan and Extend Plan modal triggers --}}
              <div class="d-flex flex-column" style="gap: 8px;">
                <button type="button" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm" style="border-radius: 12px; font-size: 0.95rem; background: #6366f1; border-color: #6366f1;" onclick="openWbRenewModal({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', {{ $monthlyPrice }}, {{ $yearlyPrice }}, 'monthly')">
                  <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew Plan') }}
                </button>
                <button type="button" class="btn btn-outline-success btn-block font-weight-bold py-2 shadow-sm" style="border-radius: 12px; font-size: 0.95rem; border-width: 2px;" onclick="openWbRenewModal({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', {{ $monthlyPrice }}, {{ $yearlyPrice }}, 'yearly')">
                  <i class="fas fa-calendar-plus mr-1"></i> {{ __('Extend Plan (Yearly)') }}
                </button>
              </div>
            @else
              {{-- Other packages are available for upgrade on the same page --}}
              <button type="button" class="btn btn-outline-primary btn-block font-weight-bold py-3" style="border-radius: 12px; font-size: 1rem;" onclick="openWbRenewModal({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', {{ $monthlyPrice }}, {{ $yearlyPrice }}, currentWbBilling)">
                <i class="fas fa-arrow-up mr-1"></i> {{ __('Buy Now / Upgrade') }}
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
{{-- ON-PAGE RENEWAL & PAYMENT GATEWAY MODAL (LAUNCHSHOP RENEW FLOW MATCH)       --}}
{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div class="modal fade" id="wbRenewModal" tabindex="-1" aria-labelledby="wbRenewModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg" style="border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden;">
      
      {{-- Modal Header --}}
      <div class="modal-header px-4 py-3 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);">
        <div class="d-flex align-items-center" style="gap: 12px;">
          <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 19px; color: #facc15;">
            <i class="fas fa-crown"></i>
          </div>
          <div>
            <h5 class="modal-title font-weight-bold mb-0" id="wbRenewModalLabel" style="font-size: 1.15rem; color: #fff;">
              {{ __('Renew / Extend Subscription') }}
            </h5>
            <small style="color: #cbd5e1; font-size: 0.82rem;">
              {{ __('Instant plan renewal & payment via all major payment gateways') }}
            </small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closeWbRenewModal()"></button>
      </div>

      {{-- Modal Form --}}
      <form id="wbRenewPlanForm" action="{{ $renewActionUrl }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="package_id" id="wb_form_package_id" value="{{ $currentPackage->id ?? 1 }}">
        <input type="hidden" name="term" id="wb_form_term" value="monthly">
        <input type="hidden" name="price" id="wb_form_price" value="39">
        <input type="hidden" name="razorpay_payment_id" id="renew_razorpay_payment_id" value="">

        <div class="modal-body p-4">
          <div class="row g-4">
            
            {{-- Left Column: Plan & Term Switcher --}}
            <div class="col-md-6 border-end-md">
              <label class="form-label font-weight-bold text-dark mb-2" style="font-size: 0.92rem;">
                <i class="fas fa-box text-primary mr-1"></i> {{ __('Selected Plan') }}
              </label>
              <div class="mb-3">
                <select id="modal_package_select" class="form-select form-control font-weight-bold" style="border-radius: 12px; height: 46px; border: 1.5px solid #cbd5e1;" onchange="onModalPackageSelectChanged(this)">
                  @foreach ($packages as $p)
                    @php
                      $pMon = round($p->monthly_price ?? 29);
                      $pYr = round($p->yearly_price ?? ($pMon * 10));
                    @endphp
                    <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-monthly="{{ $pMon }}" data-yearly="{{ $pYr }}" {{ (!empty($currentPackage) && $currentPackage->id == $p->id) ? 'selected' : '' }}>
                      {{ $p->name }} (â‚¹{{ $pMon }}/mo â€¢ â‚¹{{ $pYr }}/yr)
                    </option>
                  @endforeach
                </select>
              </div>

              {{-- Billing Cycle Selector Cards --}}
              <label class="form-label font-weight-bold text-dark mb-2" style="font-size: 0.92rem;">
                <i class="fas fa-clock text-primary mr-1"></i> {{ __('Billing Cycle') }}
              </label>
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <div class="p-3 text-center border cursor-pointer wb-term-card active" id="termCardMonthly" style="border-radius: 12px; transition: all 0.2s;" onclick="setModalBillingTerm('monthly')">
                    <div class="font-weight-bold text-dark mb-1" style="font-size: 0.95rem;">{{ __('Monthly') }}</div>
                    <div class="h5 font-weight-bold text-primary mb-0" id="modalTermPriceMonthly">â‚¹39</div>
                    <small class="text-muted" style="font-size: 0.75rem;">+30 Days validity</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="p-3 text-center border cursor-pointer wb-term-card" id="termCardYearly" style="border-radius: 12px; transition: all 0.2s;" onclick="setModalBillingTerm('yearly')">
                    <span class="badge badge-success mb-1" style="font-size: 9px; padding: 2px 6px;">{{ __('Save 20%') }}</span>
                    <div class="font-weight-bold text-dark mb-1" style="font-size: 0.95rem;">{{ __('Yearly') }}</div>
                    <div class="h5 font-weight-bold text-success mb-0" id="modalTermPriceYearly">â‚¹390</div>
                    <small class="text-muted" style="font-size: 0.75rem;">+365 Days validity</small>
                  </div>
                </div>
              </div>

              {{-- Specification Summary List (LaunchShop Style) --}}
              <div class="p-3 bg-light rounded-3 mb-2" style="border: 1px solid #e2e8f0; font-size: 0.88rem;">
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted">{{ __('Subscription Type') }}:</span>
                  <strong class="text-dark">{{ __('Agency Website') }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted">{{ __('Start Date') }}:</span>
                  <span class="font-weight-bold text-dark" id="modalStartDateLabel">Today</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted">{{ __('New Expiry Date') }}:</span>
                  <span class="font-weight-bold text-success" id="modalNewExpiryLabel">+30 Days</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="font-weight-bold text-dark" style="font-size: 1rem;">{{ __('Total Amount') }}:</span>
                  <span class="h4 font-weight-bold text-primary mb-0" id="modalSummaryPrice">â‚¹39</span>
                </div>
              </div>
            </div>

            {{-- Right Column: Payment Gateway Dropdown & Offline Fields --}}
            <div class="col-md-6">
              <label class="form-label font-weight-bold text-dark mb-2" style="font-size: 0.92rem;">
                <i class="fas fa-credit-card text-primary mr-1"></i> {{ __('Payment Gateway Platform') }} <span class="text-danger">*</span>
              </label>

              {{-- Payment Method Dropdown --}}
              <div class="mb-3">
                <select name="payment_method" id="wb-payment-gateway" class="form-select form-control font-weight-bold" style="border-radius: 12px; height: 46px; border: 1.5px solid #6366f1; background-color: #f8fafc;" required onchange="onPaymentGatewaySelected(this.value)">
                  @if (!empty($payment_methods) && $payment_methods->count() > 0)
                    @foreach ($payment_methods as $pm)
                      @php
                        $pmName = $pm->name ?? ($pm->title ?? 'Gateway');
                        $isRazor = strtolower($pmName) === 'razorpay' || strtolower($pm->keyword ?? '') === 'razorpay';
                      @endphp
                      <option value="{{ $pmName }}" {{ $isRazor ? 'selected' : '' }}>
                        {{ $pmName }} {{ $isRazor ? '(UPI, Cards, NetBanking, QR)' : '' }}
                      </option>
                    @endforeach
                  @else
                    <option value="Razorpay" selected>Razorpay (UPI, GPay, PhonePe, Cards, NetBanking)</option>
                    <option value="Stripe">Stripe (International Cards)</option>
                    <option value="PhonePe">PhonePe / UPI</option>
                    <option value="Paytm">Paytm</option>
                    <option value="Bank Transfer (Offline)">Bank Transfer / IMPS / NEFT (Offline)</option>
                  @endif
                </select>
                <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;">
                  <i class="fas fa-info-circle mr-1"></i> {{ __('Select any preferred payment gateway platform from the dropdown above.') }}
                </small>
              </div>

              {{-- Gateway Visual Trust Badges (Shown when Razorpay or Online Gateway is selected) --}}
              <div id="onlineGatewayHelper" class="p-3 bg-light rounded-3 mb-3" style="border: 1px dashed #cbd5e1;">
                <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                  <i class="fas fa-shield-alt text-success"></i>
                  <span class="font-weight-bold text-dark" style="font-size: 0.85rem;">{{ __('Instant Automated Payment') }}</span>
                </div>
                <p class="text-muted mb-2" style="font-size: 0.8rem; line-height: 1.4;">
                  {{ __('Supports Google Pay, PhonePe, Paytm, BHIM UPI, Debit/Credit Cards, NetBanking & QR code. Plan activates instantly upon completion.') }}
                </p>
                <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                  <span class="badge bg-white text-dark border px-2 py-1 font-weight-medium" style="font-size: 11px;">UPI</span>
                  <span class="badge bg-white text-dark border px-2 py-1 font-weight-medium" style="font-size: 11px;">GPay</span>
                  <span class="badge bg-white text-dark border px-2 py-1 font-weight-medium" style="font-size: 11px;">PhonePe</span>
                  <span class="badge bg-white text-dark border px-2 py-1 font-weight-medium" style="font-size: 11px;">Cards</span>
                  <span class="badge bg-white text-dark border px-2 py-1 font-weight-medium" style="font-size: 11px;">NetBanking</span>
                </div>
              </div>

              {{-- Offline / Bank Transfer Details Section (Conditionally Shown) --}}
              <div id="offlineGatewaySection" class="p-3 rounded-3 mb-3 d-none" style="background: #fffbeb; border: 1.5px solid #fef3c7;">
                <h6 class="font-weight-bold text-warning mb-2" style="font-size: 0.88rem; color: #b45309 !important;">
                  <i class="fas fa-university mr-1"></i> {{ __('Bank Transfer & UPI Deposit Instructions') }}
                </h6>
                <div class="p-2 bg-white rounded-2 mb-3" style="border: 1px solid #fde68a; font-size: 0.8rem; line-height: 1.5; color: #451a03;">
                  <div><strong>Bank:</strong> HDFC Bank</div>
                  <div><strong>Account Name:</strong> Website Builder Agency</div>
                  <div><strong>Account No:</strong> 50200012345678</div>
                  <div><strong>IFSC Code:</strong> HDFC0001234</div>
                  <div><strong>UPI ID:</strong> youverse@hdfcbank</div>
                </div>

                <div class="mb-2">
                  <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 0.82rem;">
                    {{ __('UTR / Transaction Reference No.') }} <span class="text-danger">*</span>
                  </label>
                  <input type="text" name="utr_number" id="wb_utr_number" class="form-control" placeholder="e.g. 329103948291" style="border-radius: 8px; font-size: 0.85rem;">
                </div>

                <div class="mb-1">
                  <label class="form-label font-weight-bold text-dark mb-1" style="font-size: 0.82rem;">
                    {{ __('Payment Receipt Screenshot (Optional)') }}
                  </label>
                  <input type="file" name="receipt" accept="image/*,.pdf" class="form-control" style="border-radius: 8px; font-size: 0.82rem;">
                </div>
              </div>

              {{-- Submit & Pay Action Button --}}
              <button type="button" id="btnSubmitRenewModal" class="btn btn-primary btn-block w-100 font-weight-bold py-3 shadow" style="border-radius: 12px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; font-size: 1.05rem;" onclick="submitWbRenewProcess()">
                <i class="fas fa-lock mr-2"></i> {{ __('Pay') }} <span id="btnModalPayPrice">â‚¹39</span> &amp; {{ __('Renew Plan Now') }}
              </button>

              <div class="text-center mt-2 text-muted" style="font-size: 0.75rem;">
                <i class="fas fa-shield-alt text-success mr-1"></i> {{ __('256-Bit SSL Encrypted â€¢ Instant Plan Activation') }}
              </div>

            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .wb-term-card.active {
    border-color: #6366f1 !important;
    background-color: #eef2ff !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
  }
  .wb-term-card:hover {
    border-color: #a5b4fc;
  }
  .cursor-pointer {
    cursor: pointer;
  }
</style>

{{-- Razorpay Checkout Client Script --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
  var currentWbBilling = 'monthly';
  var selectedModalPkgId = {{ $currentPackage->id ?? 1 }};
  var selectedModalPkgName = '{{ addslashes($currentPackage->name ?? "Business") }}';
  var selectedModalMonthlyPrice = {{ round($currentPackage->monthly_price ?? 39) }};
  var selectedModalYearlyPrice = {{ round($currentPackage->yearly_price ?? 390) }};
  var selectedModalTerm = 'monthly';

  var currentCustomerExpireIso = "{{ $currExpireIso }}";

  function switchWbBilling(cycle) {
    currentWbBilling = cycle;
    var btnM = document.getElementById('btnMonthly');
    var btnY = document.getElementById('btnYearly');

    if (cycle === 'yearly') {
      btnY.style.background = '#6366f1';
      btnY.style.color = '#ffffff';
      btnY.classList.remove('text-muted');

      btnM.style.background = 'transparent';
      btnM.style.color = '#4b5563';
      btnM.classList.add('text-muted');
    } else {
      btnM.style.background = '#6366f1';
      btnM.style.color = '#ffffff';
      btnM.classList.remove('text-muted');

      btnY.style.background = 'transparent';
      btnY.style.color = '#4b5563';
      btnY.classList.add('text-muted');
    }

    // Update all price tags with Indian Rupee symbol
    var priceEls = document.querySelectorAll('.pkg-price-val');
    priceEls.forEach(function(el) {
      var val = cycle === 'yearly' ? el.getAttribute('data-yearly') : el.getAttribute('data-monthly');
      el.textContent = 'â‚¹' + val;
    });

    // Update period labels
    var periodEls = document.querySelectorAll('.pkg-period-label');
    periodEls.forEach(function(el) {
      el.textContent = cycle === 'yearly' ? '/year' : '/month';
    });
  }

  // Open the interactive on-page renewal modal
  function openWbRenewModal(pkgId, pkgName, mPrice, yPrice, term) {
    selectedModalPkgId = pkgId;
    selectedModalPkgName = pkgName;
    selectedModalMonthlyPrice = mPrice;
    selectedModalYearlyPrice = yPrice;
    selectedModalTerm = term || 'monthly';

    // Sync package dropdown
    var pkgSelect = document.getElementById('modal_package_select');
    if (pkgSelect) {
      pkgSelect.value = pkgId;
    }

    // Sync prices displayed in the modal
    document.getElementById('modalTermPriceMonthly').textContent = 'â‚¹' + mPrice;
    document.getElementById('modalTermPriceYearly').textContent = 'â‚¹' + yPrice;

    // Set billing term
    setModalBillingTerm(selectedModalTerm);

    // Show modal using Bootstrap 5 or fallback
    var modalEl = document.getElementById('wbRenewModal');
    if (window.bootstrap && bootstrap.Modal) {
      var myModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      myModal.show();
    } else {
      modalEl.classList.add('show');
      modalEl.style.display = 'block';
      document.body.classList.add('modal-open');
    }
  }

  function closeWbRenewModal() {
    var modalEl = document.getElementById('wbRenewModal');
    if (window.bootstrap && bootstrap.Modal) {
      var myModal = bootstrap.Modal.getInstance(modalEl);
      if (myModal) myModal.hide();
    }
    modalEl.classList.remove('show');
    modalEl.style.display = 'none';
    document.body.classList.remove('modal-open');
  }

  function setModalBillingTerm(term) {
    selectedModalTerm = term;
    var cardM = document.getElementById('termCardMonthly');
    var cardY = document.getElementById('termCardYearly');

    if (term === 'yearly') {
      cardY.classList.add('active');
      cardM.classList.remove('active');
    } else {
      cardM.classList.add('active');
      cardY.classList.remove('active');
    }

    var finalPrice = (term === 'yearly') ? selectedModalYearlyPrice : selectedModalMonthlyPrice;

    // Update form hidden fields
    document.getElementById('wb_form_package_id').value = selectedModalPkgId;
    document.getElementById('wb_form_term').value = term;
    document.getElementById('wb_form_price').value = finalPrice;

    // Update UI amounts
    document.getElementById('modalSummaryPrice').textContent = 'â‚¹' + finalPrice;
    document.getElementById('btnModalPayPrice').textContent = 'â‚¹' + finalPrice;

    // Calculate dates
    calculateModalDates(term);
  }

  function calculateModalDates(term) {
    var now = new Date();
    var baseDate = now;

    if (currentCustomerExpireIso) {
      var expDate = new Date(currentCustomerExpireIso);
      if (expDate > now) {
        baseDate = expDate;
      }
    }

    var newDate = new Date(baseDate.getTime());
    if (term === 'yearly') {
      newDate.setFullYear(newDate.getFullYear() + 1);
    } else {
      newDate.setMonth(newDate.getMonth() + 1);
    }

    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var startFormatted = baseDate === now ? 'Today' : monthNames[baseDate.getMonth()] + ' ' + baseDate.getDate() + ', ' + baseDate.getFullYear();
    var newFormatted = monthNames[newDate.getMonth()] + ' ' + newDate.getDate() + ', ' + newDate.getFullYear();

    document.getElementById('modalStartDateLabel').textContent = startFormatted;
    document.getElementById('modalNewExpiryLabel').textContent = newFormatted;
  }

  function onModalPackageSelectChanged(selectEl) {
    var opt = selectEl.options[selectEl.selectedIndex];
    selectedModalPkgId = opt.value;
    selectedModalPkgName = opt.getAttribute('data-name');
    selectedModalMonthlyPrice = parseFloat(opt.getAttribute('data-monthly')) || 39;
    selectedModalYearlyPrice = parseFloat(opt.getAttribute('data-yearly')) || 390;

    document.getElementById('modalTermPriceMonthly').textContent = 'â‚¹' + selectedModalMonthlyPrice;
    document.getElementById('modalTermPriceYearly').textContent = 'â‚¹' + selectedModalYearlyPrice;

    setModalBillingTerm(selectedModalTerm);
  }

  function onPaymentGatewaySelected(gw) {
    var gwLower = (gw || '').toLowerCase();
    var isOffline = gwLower.includes('offline') || gwLower.includes('bank');

    var offlineSection = document.getElementById('offlineGatewaySection');
    var onlineHelper = document.getElementById('onlineGatewayHelper');

    if (isOffline) {
      offlineSection.classList.remove('d-none');
      onlineHelper.classList.add('d-none');
    } else {
      offlineSection.classList.add('d-none');
      onlineHelper.classList.remove('d-none');
    }
  }

  // Submit Renewal or trigger Razorpay Checkout JS
  function submitWbRenewProcess() {
    var form = document.getElementById('wbRenewPlanForm');
    var gw = document.getElementById('wb-payment-gateway').value;
    var gwLower = (gw || '').toLowerCase();
    var price = parseFloat(document.getElementById('wb_form_price').value) || 39;

    var isOffline = gwLower.includes('offline') || gwLower.includes('bank');

    if (isOffline) {
      var utr = document.getElementById('wb_utr_number').value.trim();
      if (!utr) {
        alert('Please enter your payment UTR / Transaction Reference Number.');
        document.getElementById('wb_utr_number').focus();
        return;
      }
      form.submit();
      return;
    }

    // If Razorpay, open Razorpay Checkout on the same page
    if (gwLower === 'razorpay' && typeof Razorpay !== 'undefined') {
      var rzpKey = "{{ $razorpayKey ?? 'rzp_test_T9UaATIMf1qeO8' }}";
      var options = {
        key: rzpKey,
        amount: Math.round(price * 100),
        currency: 'INR',
        name: 'Website Builder Subscription',
        description: 'Renew ' + selectedModalPkgName + ' (' + selectedModalTerm + ')',
        image: '{{ asset("img/whatsappbulklogo.png") }}',
        prefill: {
          name: "{{ $customer->name ?? 'Agency Admin' }}",
          email: "{{ $customer->email ?? '' }}",
          contact: "{{ $customer->phone ?? '' }}"
        },
        theme: {
          color: '#6366f1'
        },
        handler: function(response) {
          document.getElementById('renew_razorpay_payment_id').value = response.razorpay_payment_id;
          form.submit();
        },
        modal: {
          ondismiss: function() {
            // User closed Razorpay modal, remain on subscription page
          }
        }
      };

      try {
        var rzp = new Razorpay(options);
        rzp.open();
        return;
      } catch (err) {
        console.warn('Direct Razorpay checkout error, submitting standard form: ', err);
      }
    }

    // Direct submit for Stripe, PhonePe, Paytm, or fallback
    form.submit();
  }

  // Pre-initialize calculation on page load
  document.addEventListener('DOMContentLoaded', function() {
    calculateModalDates('monthly');
  });
</script>
@endsection