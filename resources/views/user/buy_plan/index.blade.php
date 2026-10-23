@extends('user.layout')
@section('styles')
  <style>
    :root {
      --color-green: #22c55e;
      --color-green-soft: #f0fdf4;
      --color-blue: #0052ff;
      --color-blue-soft: #eff6ff;
      --color-purple: #6366f1;
      --color-purple-soft: #eef2ff;
      --color-orange: #f97316;
      --color-orange-soft: #fff7ed;
    }

    .theme-color-0 {
      --theme-color: var(--color-green);
      --theme-bg-soft: var(--color-green-soft);
      --theme-color-rgb: 34, 197, 94;
    }
    .theme-color-1 {
      --theme-color: var(--color-blue);
      --theme-bg-soft: var(--color-blue-soft);
      --theme-color-rgb: 0, 82, 255;
    }
    .theme-color-2 {
      --theme-color: var(--color-purple);
      --theme-bg-soft: var(--color-purple-soft);
      --theme-color-rgb: 99, 102, 241;
    }
    .theme-color-3 {
      --theme-color: var(--color-orange);
      --theme-bg-soft: var(--color-orange-soft);
      --theme-color-rgb: 249, 115, 22;
    }

    .plans-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
      margin-bottom: 40px;
    }
    @media (max-width: 1200px) {
      .plans-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    @media (max-width: 768px) {
      .plans-grid {
        grid-template-columns: 1fr;
      }
    }

    .card-premium-plan {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 32px 24px;
      display: flex;
      flex-direction: column;
      position: relative;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      height: 100%;
    }

    .card-premium-plan:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      border-color: var(--theme-color);
    }

    .card-premium-plan.is-current {
      border-color: #22c55e;
      box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.1), 0 4px 6px -2px rgba(34, 197, 94, 0.05);
    }

    .plan-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }

    .plan-title {
      font-size: 22px;
      font-weight: 700;
      color: #0f172a;
      margin: 0;
    }

    .plan-badge {
      font-size: 11px;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 9999px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .plan-badge-current {
      background: #22c55e;
      color: white;
    }

    .plan-badge-next {
      background: #f59e0b;
      color: white;
    }

    .hexagon-outer {
      background: var(--theme-color);
      width: 100px;
      height: 110px;
      clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 30px auto;
      transition: transform 0.3s ease;
    }

    .card-premium-plan:hover .hexagon-outer {
      transform: scale(1.05);
    }

    .hexagon-inner {
      background: var(--theme-bg-soft);
      width: 96px;
      height: 106px;
      clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 10px;
    }

    .hex-price-val {
      font-size: 22px;
      font-weight: 800;
      color: var(--theme-color);
      line-height: 1.1;
    }

    .hex-price-term {
      font-size: 11px;
      font-weight: 600;
      color: var(--theme-color);
      opacity: 0.85;
      margin-top: 2px;
    }

    .features-list {
      list-style: none;
      padding: 0;
      margin: 0 0 30px 0;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .feature-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 13.5px;
      color: #475569;
      line-height: 1.4;
      font-weight: 500;
    }

    .feature-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: var(--theme-bg-soft);
      color: var(--theme-color);
      font-size: 9px;
      flex-shrink: 0;
      margin-top: 1px;
    }

    .btn-plan-action {
      width: 100%;
      padding: 12px 24px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
      text-decoration: none !important;
    }

    .btn-plan-action-current {
      background: #E8F8F0;
      color: #22C55E;
    }

    .btn-plan-action-current:hover {
      background: #D1F2E1;
    }

    .btn-plan-action-buy {
      background: var(--theme-color);
      color: white;
      box-shadow: 0 4px 6px -1px rgba(var(--theme-color-rgb), 0.2);
    }

    .btn-plan-action-buy:hover {
      filter: brightness(0.9);
      transform: translateY(-1px);
    }

    .trust-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-top: 40px;
      margin-bottom: 20px;
    }
    @media (max-width: 1024px) {
      .trust-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    @media (max-width: 640px) {
      .trust-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endsection
@php
  $user = Auth::guard('web')->user();
  $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
@endphp

@section('content')
  @if (is_null($package))
    @php
      $pendingMemb = \App\Models\Membership::query()
          ->where([['user_id', '=', Auth::id()], ['status', 0]])
          ->whereYear('start_date', '<>', '9999')
          ->orderBy('id', 'DESC')
          ->first();
      $pendingPackage = isset($pendingMemb) ? \App\Models\Package::query()->findOrFail($pendingMemb->package_id) : null;
    @endphp

    @if ($pendingPackage)
      <div class="alert alert-warning mb-4" style="border-radius: 12px;">
        {{ __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.') }}
      </div>
      <div class="alert alert-warning mb-4" style="border-radius: 12px;">
        <strong>{{ __('Pending Package') . ':' }} </strong> {{ $pendingPackage->title }}
        <span class="badge badge-secondary ml-1">{{ __($pendingPackage->term) }}</span>
        <span class="badge badge-warning ml-1">{{ __('Decision Pending') }}</span>
      </div>
    @else
      <div class="alert alert-warning mb-4" style="border-radius: 12px;">
        {{ __('Your membership is expired. Please purchase a new package / extend the current package.') }}
      </div>
    @endif
  @else
    <div class="alert d-flex flex-column p-4 mb-4" style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 12px; font-weight: 500;">
      @if ($package_count >= 2)
        @if ($next_membership->status == 0)
          <div class="alert alert-danger mb-3" style="border-radius: 8px;">
            {{ __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.') }}
          </div>
        @elseif ($next_membership->status == 1)
          <div class="alert alert-danger mb-3" style="border-radius: 8px;">
            {{ __('You have another package to activate after the current package expires. You cannot purchase / extend any package, until the next package is activated') }}
          </div>
        @endif
      @endif

      @if (!empty($current_package))
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="icon-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; background: #2563EB; color: white; font-size: 16px; flex-shrink: 0;">
            <i class="fas fa-crown"></i>
          </div>
          <div style="font-size: 15px; color: #1E3A8A; line-height: 1.5;">
            <span style="font-weight: 700;">{{ __('Current Package') }}:</span> {{ $current_package->title ?? '' }}
            @if(!empty($current_package->term))
              <span class="badge badge-secondary ml-1" style="background: #2563EB; color: white; font-size: 11px; font-weight: 700;">{{ __($current_package->term) }}</span>
            @endif
            @if (!empty($current_membership) && !empty($current_membership->expire_date))
              <span style="margin: 0 10px; color: #93C5FD;">|</span>
              <span style="font-weight: 700;">{{ __('Expire Date') }}:</span>
              @if ($current_membership->is_trial == 1)
                {{ Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }} <span class="badge badge-primary">{{ __('Trial') }}</span>
              @else
                {{ ($current_package->term ?? '') === 'lifetime' ? __('Lifetime') : Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }}
              @endif
            @endif
          </div>
        </div>
      </div>
      @endif

      @if ($package_count >= 2 && !empty($next_package))
        <div class="mt-3 pt-3 border-top" style="font-size: 13.5px; color: #475569; border-color: #BFDBFE !important;">
          <span style="font-weight: 700; color: #1E293B;">{{ __('Next Package To Activate') }}:</span>
          {{ $next_package->title }}
          <span class="badge badge-secondary ml-1">{{ __($next_package->term ?? '') }}</span>
          @if (!empty($current_package) && ($current_package->term ?? '') != 'lifetime' && (!empty($current_membership) && $current_membership->is_trial != 1) && !empty($next_membership))
            (
            {{ __('Activation Date') . ':' }}
            {{ Carbon\Carbon::parse($next_membership->start_date)->format('M-d-Y') }},
            {{ __('Expire Date') . ':' }}
            {{ ($next_package->term ?? '') === 'lifetime' ? __('Lifetime') : Carbon\Carbon::parse($next_membership->expire_date)->format('M-d-Y') }})
          @endif
          @if (!empty($next_membership) && $next_membership->status == 0)
            <span class="badge badge-warning ml-1">{{ __('Decision Pending') }}</span>
          @endif
        </div>
      @endif
    </div>
  @endif

  @if (!empty($is_expired))
  <div class="alert alert-danger shadow-sm mb-4 p-4" style="border-radius: 14px; border-left: 6px solid #ef4444; background: #fff;">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div>
        <h4 class="font-weight-bold text-danger mb-1">
          <i class="fas fa-ban mr-1"></i> {{ __('Your Subscription Has Expired') }}
        </h4>
        <p class="mb-0 text-muted font-weight-bold">
          {{ __('Your plan expired on') }} {{ !empty($expired_membership->expire_date) ? \Carbon\Carbon::parse($expired_membership->expire_date)->format('M d, Y h:i A') : 'recently' }}. {{ __('Renew your plan or select a package below to immediately restore full store access.') }}
        </p>
      </div>
      @if (!empty($expired_package))
        <a href="{{ route('user.plan.extend.checkout', $expired_package->id) }}" class="btn btn-danger font-weight-bold px-4 py-2 mt-2 mt-md-0" style="border-radius: 10px;">
          <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew :pkg Now', ['pkg' => $expired_package->title]) }}
        </a>
      @endif
    </div>
  </div>
@endif

@php
  // Smart Tier Filtering for LaunchShop / Ecombuilder
  $currPkg = $current_package ?? ($expired_package ?? null);
  $currPrice = $currPkg ? (float)($currPkg->price ?? 0) : 0;
  $renewPlan = $currPkg;

  // Filter packages: ONLY higher packages are eligible for upgrade. Lesser packages are NEVER shown!
  $upgradePlans = $packages->filter(function($p) use ($currPkg, $currPrice) {
      if (!$currPkg) return true;
      if ($p->id == $currPkg->id) return false;
      return (float)($p->price ?? 0) > $currPrice;
  })->values();

  if (!$renewPlan && count($packages) > 0) {
      $renewPlan = $packages->first();
  }
@endphp

{{-- SECTION 1: RENEW CURRENT PLAN (HORIZONTAL RECTANGLE CARD) --}}
<div class="mb-5">
  <div class="d-flex align-items-center gap-2 mb-3">
    <div style="width: 4px; height: 26px; background: #ef4444; border-radius: 4px;"></div>
    <h3 class="h4 font-weight-bold text-dark mb-0">
      {{ __('Please renew your current plan to use the website') }}
    </h3>
  </div>

  @if($renewPlan)
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px; background: #ffffff; border: 2px solid #ef4444 !important; box-shadow: 0 10px 30px rgba(239, 68, 68, 0.08);">
      <div class="card-body p-4 p-md-5">
        <div class="row align-items-center">
          <!-- Left: Title, Badges & Feature Highlights -->
          <div class="col-lg-7 col-md-12 mb-4 mb-lg-0 border-end-lg pe-lg-4">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
              <span class="badge badge-danger px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 12px; background: #ef4444; color: #fff;">
                <i class="fas fa-crown mr-1"></i> {{ __('Current Plan') }}
              </span>
              <h2 class="h3 font-weight-bold text-dark mb-0">{{ $renewPlan->title }}</h2>
              @if(!empty($renewPlan->term))
                <span class="badge badge-light text-muted font-weight-bold ml-2" style="border-radius: 8px; font-size: 12px; border: 1px solid #e2e8f0;">
                  {{ __($renewPlan->term) }}
                </span>
              @endif
            </div>
            <p class="text-muted mb-3 font-weight-medium">
              {{ __('Your subscription has expired. Renew your current plan now to immediately keep your live store online and restore full access.') }}
            </p>

            <div class="row g-2 pt-2">
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-success mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('Categories Limit') }}:</strong> {{ $renewPlan->categories_limit != '999999' ? $renewPlan->categories_limit : __('Unlimited') }}</span>
                </div>
              </div>
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-success mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('Products Limit') }}:</strong> {{ $renewPlan->product_limit != '999999' ? $renewPlan->product_limit : __('Unlimited') }}</span>
                </div>
              </div>
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-success mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('Orders Limit') }}:</strong> {{ $renewPlan->order_limit != '999999' ? $renewPlan->order_limit : __('Unlimited') }}</span>
                </div>
              </div>
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-success mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('Custom Pages') }}:</strong> {{ $renewPlan->number_of_custom_page != '999999' ? $renewPlan->number_of_custom_page : __('Unlimited') }}</span>
                </div>
              </div>
              @if(!empty($renewPlan->ai_engine))
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-success mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('AI Generator Engine') }}:</strong> {{ strtoupper($renewPlan->ai_engine) }}</span>
                </div>
              </div>
              @endif
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-success mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('Languages') }}:</strong> {{ $renewPlan->language_limit != '999999' ? $renewPlan->language_limit : __('Unlimited') }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Price & Pay Now Button -->
          <div class="col-lg-5 col-md-12 text-center text-lg-end ps-lg-4">
            <div class="mb-2">
              <span class="h1 font-weight-bold text-dark" style="font-size: 44px; color: #1e293b;">
                {{ $renewPlan->price == 0 ? __('Free') : '₹' . round($renewPlan->price) }}
              </span>
              <span class="text-muted font-weight-bold" style="font-size: 16px;">/{{ __($renewPlan->term) }}</span>
              @if(stripos($renewPlan->term ?? '', 'year') !== false || stripos($renewPlan->term ?? '', 'annu') !== false)
                <span class="badge ml-2" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; color: #ffffff !important; font-size: 12px; font-weight: 800; padding: 4px 10px; border-radius: 10px; vertical-align: middle; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);">
                  <i class="fas fa-tag mr-1"></i>20% OFF
                </span>
              @endif
            </div>
            <div class="text-muted small mb-3">
              {{ __('Instant automatic reactivation upon payment confirmation') }}
            </div>
            <a href="{{ route('user.plan.extend.checkout', $renewPlan->id) }}" class="btn btn-danger font-weight-bold px-5 py-3 shadow" style="border-radius: 12px; font-size: 16px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; letter-spacing: 0.3px; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); text-decoration: none;">
              <i class="fas fa-bolt mr-2"></i> {{ __('Pay Now & Renew Plan') }}
            </a>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>

{{-- SECTION 2: UPGRADE PLAN TO EXPLORE NEW FEATURES (HIGHER TIERS ONLY) --}}
@if(count($upgradePlans) > 0)
<div class="mb-5">
  <div class="d-flex align-items-center gap-2 mb-3">
    <div style="width: 4px; height: 26px; background: #6366f1; border-radius: 4px;"></div>
    <h3 class="h4 font-weight-bold text-dark mb-0">
      {{ __('Upgrade your plan to explore new features') }}
    </h3>
  </div>

  @foreach ($upgradePlans as $upPkg)
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px; background: #ffffff; border: 2px solid #6366f1 !important; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.08);">
      <div class="card-body p-4 p-md-5">
        <div class="row align-items-center">
          <!-- Left: Title, Badges & Feature Highlights -->
          <div class="col-lg-7 col-md-12 mb-4 mb-lg-0 border-end-lg pe-lg-4">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
              <span class="badge badge-primary px-3 py-1 font-weight-bold" style="border-radius: 8px; font-size: 12px; background: #6366f1; color: #fff;">
                <i class="fas fa-arrow-up mr-1"></i> {{ __('Upgrade Option') }}
              </span>
              <h2 class="h3 font-weight-bold text-dark mb-0">{{ $upPkg->title }}</h2>
              @if(!empty($upPkg->term))
                <span class="badge badge-light text-muted font-weight-bold ml-2" style="border-radius: 8px; font-size: 12px; border: 1px solid #e2e8f0;">
                  {{ __($upPkg->term) }}
                </span>
              @endif
            </div>
            <p class="text-muted mb-3 font-weight-medium">
              {{ __('Upgrade to this plan to unlock higher product limits, unlimited orders, and advanced features for your store.') }}
            </p>

            <div class="row g-2 pt-2">
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-primary mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('Categories Limit') }}:</strong> {{ $upPkg->categories_limit != '999999' ? $upPkg->categories_limit : __('Unlimited') }}</span>
                </div>
              </div>
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-primary mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('Products Limit') }}:</strong> {{ $upPkg->product_limit != '999999' ? $upPkg->product_limit : __('Unlimited') }}</span>
                </div>
              </div>
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-primary mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('Orders Limit') }}:</strong> {{ $upPkg->order_limit != '999999' ? $upPkg->order_limit : __('Unlimited') }}</span>
                </div>
              </div>
              <div class="col-sm-6 mb-2">
                <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                  <i class="fas fa-check-circle text-primary mr-2" style="font-size: 17px;"></i>
                  <span><strong>{{ __('Custom Pages') }}:</strong> {{ $upPkg->number_of_custom_page != '999999' ? $upPkg->number_of_custom_page : __('Unlimited') }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Price & Upgrade Button -->
          <div class="col-lg-5 col-md-12 text-center text-lg-end ps-lg-4">
            <div class="mb-2">
              <span class="h1 font-weight-bold text-dark" style="font-size: 44px; color: #1e293b;">
                {{ $upPkg->price == 0 ? __('Free') : '₹' . round($upPkg->price) }}
              </span>
              <span class="text-muted font-weight-bold" style="font-size: 16px;">/{{ __($upPkg->term) }}</span>
              @if(stripos($upPkg->term ?? '', 'year') !== false || stripos($upPkg->term ?? '', 'annu') !== false)
                <span class="badge ml-2" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; color: #ffffff !important; font-size: 12px; font-weight: 800; padding: 4px 10px; border-radius: 10px; vertical-align: middle; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);">
                  <i class="fas fa-tag mr-1"></i>20% OFF
                </span>
              @endif
            </div>
            <div class="text-muted small mb-3">
              {{ __('Switch plan instantly with seamless feature upgrade') }}
            </div>
            <a href="{{ route('user.plan.extend.checkout', $upPkg->id) }}" class="btn btn-primary font-weight-bold px-5 py-3 shadow" style="border-radius: 12px; font-size: 16px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; letter-spacing: 0.3px; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); text-decoration: none;">
              <i class="fas fa-rocket mr-2"></i> {{ __('Upgrade & Pay Now') }}
            </a>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>
@endif
  <!-- Trust / Support Elements -->
  <div class="row">
    <div class="col-12">
      <div class="trust-grid">
        <!-- Feature 1 -->
        <div class="trust-card" style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 12px; padding: 20px; display: flex; align-items: flex-start; gap: 16px;">
          <div class="trust-icon-wrap" style="background: #EFF6FF; color: #2563EB; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
            <i class="fas fa-shield-alt"></i>
          </div>
          <div>
            <h4 style="font-size: 14px; font-weight: 700; color: #0F172A; margin: 0 0 4px 0;">Secure & Reliable</h4>
            <p style="font-size: 12px; color: #64748B; margin: 0; line-height: 1.4;">Your data is secure with enterprise-grade protection.</p>
          </div>
        </div>
        <!-- Feature 2 -->
        <div class="trust-card" style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 12px; padding: 20px; display: flex; align-items: flex-start; gap: 16px;">
          <div class="trust-icon-wrap" style="background: #ECFDF5; color: #059669; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
            <i class="fas fa-rocket"></i>
          </div>
          <div>
            <h4 style="font-size: 14px; font-weight: 700; color: #0F172A; margin: 0 0 4px 0;">Powerful Features</h4>
            <p style="font-size: 12px; color: #64748B; margin: 0; line-height: 1.4;">Everything you need to grow your business online.</p>
          </div>
        </div>
        <!-- Feature 3 -->
        <div class="trust-card" style="background: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 12px; padding: 20px; display: flex; align-items: flex-start; gap: 16px;">
          <div class="trust-icon-wrap" style="background: #F5F3FF; color: #7C3AED; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
            <i class="fas fa-headset"></i>
          </div>
          <div>
            <h4 style="font-size: 14px; font-weight: 700; color: #0F172A; margin: 0 0 4px 0;">24/7 Support</h4>
            <p style="font-size: 12px; color: #64748B; margin: 0; line-height: 1.4;">Our support team is always ready to help you.</p>
          </div>
        </div>
        <!-- Feature 4 -->
        <div class="trust-card" style="background: #FFF7ED; border: 1px solid #FFE4E6; border-radius: 12px; padding: 20px; display: flex; align-items: flex-start; gap: 16px;">
          <div class="trust-icon-wrap" style="background: #FFF7ED; color: #EA580C; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
            <i class="fas fa-chart-line"></i>
          </div>
          <div>
            <h4 style="font-size: 14px; font-weight: 700; color: #0F172A; margin: 0 0 4px 0;">Scale Your Business</h4>
            <p style="font-size: 12px; color: #64748B; margin: 0; line-height: 1.4;">Flexible plans that grow with your business.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
