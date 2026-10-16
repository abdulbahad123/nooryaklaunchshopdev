@extends('website_builder.agency_template.admin.layout')

@section('title', 'Subscription & Packages | Website Builder')

@section('content')
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
    <a href="{{ route('website-builder.checkout') }}" class="btn btn-outline-primary font-weight-bold px-4 py-2" style="border-radius: 10px;">
      <i class="fas fa-shopping-cart mr-1"></i> {{ __('All Packages') }}
    </a>
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
            {{ __('Your plan expired on') }} <strong>{{ !empty($customer->expire_date) ? \Carbon\Carbon::parse($customer->expire_date)->format('M d, Y h:i A') : 'recently' }}</strong>. {{ __('Renew your plan now to immediately keep your live website online.') }}
          </p>
        </div>
      </div>
      @if (!empty($currentPackage))
        <a href="{{ route('website-builder.checkout') }}?plan={{ $currentPackage->id ?? 1 }}&term=monthly" class="btn btn-danger font-weight-bold px-4 py-2 mt-2 mt-md-0 shadow-sm" style="border-radius: 10px;">
          <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew Plan Now') }}
        </a>
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
            {{ __('Current Plan:') }} <strong>{{ $currentPackage->name ?? 'Website Plan' }}</strong> — {{ __('Valid until') }} <strong>{{ !empty($customer->expire_date) ? \Carbon\Carbon::parse($customer->expire_date)->format('M d, Y h:i A') : 'Unlimited' }}</strong>.
          </p>
        </div>
      </div>
      <div>
        <span class="badge badge-success px-3 py-2 font-weight-bold" style="border-radius: 20px; font-size: 13px;">
          <i class="fas fa-shield-alt mr-1"></i> {{ __('Plan Active') }}
        </span>
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
            <span class="h2 font-weight-bold text-dark pkg-price-val" data-monthly="{{ $monthlyPrice }}" data-yearly="{{ $yearlyPrice }}">${{ $monthlyPrice }}</span>
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
              {{-- Only display Renew Plan when the plan is expired --}}
              <a href="{{ route('website-builder.checkout') }}?plan={{ $pkg->id }}&term=monthly" class="btn btn-danger btn-block font-weight-bold py-3 shadow-sm wb-pkg-btn" style="border-radius: 12px; font-size: 1rem;" data-plan-id="{{ $pkg->id }}">
                <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew Plan') }}
              </a>
            @elseif ($isCurrentPlan)
              {{-- Otherwise display Current Plan indicator (no renew/extend button while active) --}}
              <button type="button" class="btn btn-block font-weight-bold py-3 shadow-none" style="border-radius: 12px; font-size: 1rem; border: 2px solid #10b981; color: #059669; background: #ecfdf5; cursor: default;" disabled>
                <i class="fas fa-check-circle mr-1"></i> {{ __('Current Plan') }}
              </button>
            @else
              {{-- Other packages are available for upgrade/checkout with Razorpay --}}
              <a href="{{ route('website-builder.checkout') }}?plan={{ $pkg->id }}&term=monthly" class="btn btn-outline-primary btn-block font-weight-bold py-3 wb-pkg-btn" style="border-radius: 12px; font-size: 1rem;" data-plan-id="{{ $pkg->id }}">
                <i class="fas fa-arrow-up mr-1"></i> {{ __('Buy Now / Upgrade') }}
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

<script>
  var currentWbBilling = 'monthly';

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

    // Update all price tags
    var priceEls = document.querySelectorAll('.pkg-price-val');
    priceEls.forEach(function(el) {
      var val = cycle === 'yearly' ? el.getAttribute('data-yearly') : el.getAttribute('data-monthly');
      el.textContent = '$' + val;
    });

    // Update period labels
    var periodEls = document.querySelectorAll('.pkg-period-label');
    periodEls.forEach(function(el) {
      el.textContent = cycle === 'yearly' ? '/year' : '/month';
    });

    // Update checkout links
    var btnEls = document.querySelectorAll('.wb-pkg-btn');
    btnEls.forEach(function(btn) {
      var planId = btn.getAttribute('data-plan-id');
      if (planId) {
        btn.href = '{{ route("website-builder.checkout") }}?plan=' + planId + '&term=' + cycle;
      }
    });
  }
</script>
@endsection