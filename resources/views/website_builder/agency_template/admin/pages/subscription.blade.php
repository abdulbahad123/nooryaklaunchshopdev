@extends('website_builder.agency_template.admin.layout')

@section('title', 'Subscription & Packages | Website Builder')

@section('content')
<div class="content-header d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="h4 font-weight-bold mb-1 text-dark">
      <i class="fas fa-crown text-warning mr-2"></i> {{ __('Subscription & Packages') }}
    </h2>
    <p class="text-muted mb-0 font-weight-medium">
      {{ __('Manage your website subscription, renew your plan, or upgrade to unlock higher limits and premium features.') }}
    </p>
  </div>
  <div>
    <a href="{{ route('website-builder.checkout') }}" class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius: 10px;">
      <i class="fas fa-shopping-cart mr-1"></i> {{ __('All Packages') }}
    </a>
  </div>
</div>

@if ($isExpired)
  <div class="alert alert-danger shadow-sm mb-4 p-4" style="border-radius: 14px; border-left: 6px solid #ef4444; background: #fff;">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div>
        <h5 class="font-weight-bold text-danger mb-1">
          <i class="fas fa-ban mr-1"></i> {{ __('Your Subscription Has Expired') }}
        </h5>
        <p class="mb-0 text-muted font-weight-bold">
          {{ __('Your website subscription expired on') }} {{ !empty($customer->expire_date) ? \Carbon\Carbon::parse($customer->expire_date)->format('M d, Y h:i A') : 'recently' }}. {{ __('Renew your plan now to immediately keep your live website online without interruption.') }}
        </p>
      </div>
      @if (!empty($currentPackage))
        <a href="{{ route('website-builder.checkout') }}?plan={{ $currentPackage->id ?? 1 }}" class="btn btn-danger font-weight-bold px-4 py-2 mt-2 mt-md-0" style="border-radius: 10px;">
          <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew :pkg Now', ['pkg' => $currentPackage->name ?? 'Plan']) }}
        </a>
      @endif
    </div>
  </div>
@else
  <div class="alert alert-success shadow-sm mb-4 p-4" style="border-radius: 14px; border-left: 6px solid #10b981; background: #fff;">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div>
        <h5 class="font-weight-bold text-success mb-1">
          <i class="fas fa-check-circle mr-1"></i> {{ __('Your Subscription is Active') }}
        </h5>
        <p class="mb-0 text-muted font-weight-bold">
          {{ __('Current Plan:') }} <strong>{{ $currentPackage->name ?? 'Website Plan' }}</strong> — {{ __('Valid until') }} {{ !empty($customer->expire_date) ? \Carbon\Carbon::parse($customer->expire_date)->format('M d, Y h:i A') : 'Unlimited' }}.
        </p>
      </div>
      <a href="{{ route('website-builder.checkout') }}?plan={{ $currentPackage->id ?? 1 }}" class="btn btn-success font-weight-bold px-4 py-2 mt-2 mt-md-0" style="border-radius: 10px;">
        <i class="fas fa-crown mr-1"></i> {{ __('Extend Membership') }}
      </a>
    </div>
  </div>
@endif

<div class="row">
  @foreach ($packages as $pkg)
    @php
      $isCurrentPlan = (!empty($customer->package_id) && $customer->package_id == $pkg->id) || ($loop->first && empty($customer->package_id));
      $isExpiredThis = $isExpired && $isCurrentPlan;
    @endphp
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 shadow-sm" style="border-radius: 16px; border: 2px solid {{ $isCurrentPlan ? '#6366f1' : '#e2e8f0' }}; background: #fff;">
        <div class="card-body p-4 d-flex flex-column">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="font-weight-bold mb-0 text-dark">{{ $pkg->name }}</h4>
            @if ($isExpiredThis)
              <span class="badge badge-danger px-3 py-2" style="border-radius: 20px;">{{ __('Expired') }}</span>
            @elseif ($isCurrentPlan)
              <span class="badge badge-primary px-3 py-2" style="border-radius: 20px;">{{ __('Current Plan') }}</span>
            @endif
          </div>

          <div class="mb-4">
            <span class="h2 font-weight-bold text-dark">${{ round($pkg->monthly_price ?? 29) }}</span>
            <span class="text-muted font-weight-bold">/{{ __('month') }}</span>
          </div>

          <ul class="list-unstyled mb-4 flex-grow-1" style="font-size: 0.95rem;">
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
              <a href="{{ route('website-builder.checkout') }}?plan={{ $pkg->id }}" class="btn btn-success btn-block font-weight-bold py-3 shadow-sm" style="border-radius: 12px; font-size: 1rem;">
                <i class="fas fa-sync-alt mr-1"></i> {{ __('Renew Plan') }}
              </a>
            @elseif ($isCurrentPlan)
              <a href="{{ route('website-builder.checkout') }}?plan={{ $pkg->id }}" class="btn btn-primary btn-block font-weight-bold py-3 shadow-sm" style="border-radius: 12px; font-size: 1rem;">
                <i class="fas fa-crown mr-1"></i> {{ __('Extend Plan') }}
              </a>
            @else
              <a href="{{ route('website-builder.checkout') }}?plan={{ $pkg->id }}" class="btn btn-outline-primary btn-block font-weight-bold py-3" style="border-radius: 12px; font-size: 1rem;">
                <i class="fas fa-shopping-cart mr-1"></i> {{ __('Buy Now / Upgrade') }}
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection