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

      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="icon-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; background: #2563EB; color: white; font-size: 16px; flex-shrink: 0;">
            <i class="fas fa-crown"></i>
          </div>
          <div style="font-size: 15px; color: #1E3A8A; line-height: 1.5;">
            <span style="font-weight: 700;">{{ __('Current Package') }}:</span> {{ $current_package->title }}
            <span class="badge badge-secondary ml-1" style="background: #2563EB; color: white; font-size: 11px; font-weight: 700;">{{ __($current_package->term) }}</span>
            <span style="margin: 0 10px; color: #93C5FD;">|</span>
            <span style="font-weight: 700;">{{ __('Expire Date') }}:</span>
            @if ($current_membership->is_trial == 1)
              {{ Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }} <span class="badge badge-primary">{{ __('Trial') }}</span>
            @else
              {{ $current_package->term === 'lifetime' ? __('Lifetime') : Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }}
            @endif
          </div>
        </div>
      </div>

      @if ($package_count >= 2)
        <div class="mt-3 pt-3 border-top" style="font-size: 13.5px; color: #475569; border-color: #BFDBFE !important;">
          <span style="font-weight: 700; color: #1E293B;">{{ __('Next Package To Activate') }}:</span>
          {{ $next_package->title }}
          <span class="badge badge-secondary ml-1">{{ __($next_package->term) }}</span>
          @if ($current_package->term != 'lifetime' && $current_membership->is_trial != 1)
            (
            {{ __('Activation Date') . ':' }}
            {{ Carbon\Carbon::parse($next_membership->start_date)->format('M-d-Y') }},
            {{ __('Expire Date') . ':' }}
            {{ $next_package->term === 'lifetime' ? __('Lifetime') : Carbon\Carbon::parse($next_membership->expire_date)->format('M-d-Y') }})
          @endif
          @if ($next_membership->status == 0)
            <span class="badge badge-warning ml-1">{{ __('Decision Pending') }}</span>
          @endif
        </div>
      @endif
    </div>
  @endif

  <div class="plans-grid">
    @foreach ($packages as $key => $package)
      @php
        $colorClass = 'theme-color-' . ($key % 4);
        $isCurrent = isset($current_package->id) && $current_package->id === $package->id;
        $isNext = $package_count >= 2 && $next_package->id == $package->id && $next_membership->status == 1;
      @endphp
      <div class="card-premium-plan {{ $colorClass }} {{ $isCurrent ? 'is-current' : '' }}">
        <div class="plan-header">
          <h3 class="plan-title">
            {{ __($package->title) }}
          </h3>
          @if ($isCurrent)
            <span class="plan-badge plan-badge-current">{{ __('Current') }}</span>
          @elseif ($isNext)
            <span class="plan-badge plan-badge-next">{{ __('Next') }}</span>
          @endif
        </div>

        <div class="hexagon-outer">
          <div class="hexagon-inner">
            <span class="hex-price-val">
              {{ $package->price == 0 ? __('Free') : (($bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '') . round($package->price) . ($bex->base_currency_symbol_position == 'right' ? ' ' . $bex->base_currency_symbol : '')) }}
            </span>
            <span class="hex-price-term">/{{ __($package->term) }}</span>
          </div>
        </div>

        <ul class="features-list">
          <li class="feature-item">
            <span class="feature-icon"><i class="fas fa-check"></i></span>
            <span>{{ __('Categories Limit') }} : {{ $package->categories_limit != '999999' ? $package->categories_limit : __('Unlimited') }}</span>
          </li>
          <li class="feature-item">
            <span class="feature-icon"><i class="fas fa-check"></i></span>
            <span>{{ __('Products Limit') }} : {{ $package->product_limit != '999999' ? $package->product_limit : __('Unlimited') }}</span>
          </li>
          <li class="feature-item">
            <span class="feature-icon"><i class="fas fa-check"></i></span>
            <span>{{ __('Orders Limit') }} : {{ $package->order_limit != '999999' ? $package->order_limit : __('Unlimited') }}</span>
          </li>
          <li class="feature-item">
            <span class="feature-icon"><i class="fas fa-check"></i></span>
            <span>{{ __('Additional Languages') }} : {{ $package->language_limit != '999999' ? $package->language_limit : __('Unlimited') }}</span>
          </li>
          @php
            $features = json_decode($package->features, true);
          @endphp
          @if (is_array($features) && in_array('Blog', $features))
            <li class="feature-item">
              <span class="feature-icon"><i class="fas fa-check"></i></span>
              <span>{{ __('Posts Limit') }} : {{ $package->post_limit != '999999' ? $package->post_limit : __('Unlimited') }}</span>
            </li>
          @endif
          @if (is_array($features) && in_array('Custom Page', $features))
            <li class="feature-item">
              <span class="feature-icon"><i class="fas fa-check"></i></span>
              <span>{{ __('Custom Pages Limit') }} : {{ $package->number_of_custom_page != '999999' ? $package->number_of_custom_page : __('Unlimited') }}</span>
            </li>
          @endif
          @php
            $aiFeatureEnabled =
                is_array($features) &&
                in_array('AI Content & Image Generator', $features);
            $aiTokenLimitLabel =
                $package->ai_token_limit != '999999' ? $package->ai_token_limit : __('Unlimited');
            $aiImageLimitLabel =
                $package->ai_image_limit != '999999' ? $package->ai_image_limit : __('Unlimited');
          @endphp
          @if (is_array($allPfeatures) && in_array('AI Content & Image Generator', $allPfeatures))
            @if ($aiFeatureEnabled)
              <li class="feature-item">
                <span class="feature-icon"><i class="fas fa-check"></i></span>
                <span>{{ __('AI Content & Image Generator') }}</span>
              </li>
              <li class="feature-item" style="padding-left: 20px;">
                <span class="feature-icon"><i class="fas fa-angle-right"></i></span>
                <span>{{ __('AI Engine') }} : {{ $package->ai_engine ? strtoupper($package->ai_engine) : __('N/A') }}</span>
              </li>
              <li class="feature-item" style="padding-left: 20px;">
                <span class="feature-icon"><i class="fas fa-angle-right"></i></span>
                <span>{{ __('AI Token Limit') }} : {{ $aiTokenLimitLabel }}</span>
              </li>
              <li class="feature-item" style="padding-left: 20px;">
                <span class="feature-icon"><i class="fas fa-angle-right"></i></span>
                <span>{{ __('AI Image Limit') }} : {{ $aiImageLimitLabel }}</span>
              </li>
            @endif
          @endif

          @if (!is_null($features))
            @foreach ($features as $feature)
              @if ($feature !== 'AI Content & Image Generator')
                <li class="feature-item">
                  <span class="feature-icon"><i class="fas fa-check"></i></span>
                  <span>{{ __($feature) }}</span>
                </li>
              @endif
            @endforeach
          @endif
        </ul>

        @php
          $hasPendingMemb = \App\Http\Helpers\UserPermissionHelper::hasPendingMembership(Auth::id());
        @endphp
        @if ($package_count < 2 && !$hasPendingMemb)
          <div class="mt-auto">
            @if ($isCurrent)
              @if ($package->term != 'lifetime' || $current_membership->is_trial == 1)
                <a href="{{ route('user.plan.extend.checkout', $package->id) }}"
                  class="btn-plan-action btn-plan-action-current">
                  <i class="fas fa-crown"></i> {{ __('Extend') }}
                </a>
              @else
                <button type="button" class="btn-plan-action btn-plan-action-current" style="cursor: default;" disabled>
                  <i class="fas fa-crown"></i> {{ __('Current Plan') }}
                </button>
              @endif
            @else
              <a href="{{ route('user.plan.extend.checkout', $package->id) }}"
                class="btn-plan-action btn-plan-action-buy">
                <i class="fas fa-shopping-cart"></i> {{ __('Buy Now') }}
              </a>
            @endif
          </div>
        @else
          <div class="mt-auto">
            @if ($isCurrent)
              <button type="button" class="btn-plan-action btn-plan-action-current" style="cursor: default;" disabled>
                <i class="fas fa-crown"></i> {{ __('Current Plan') }}
              </button>
            @else
              <button type="button" class="btn-plan-action btn-plan-action-buy" style="opacity: 0.6; cursor: not-allowed;" disabled>
                <i class="fas fa-shopping-cart"></i> {{ __('Buy Now') }}
              </button>
            @endif
          </div>
        @endif
      </div>
    @endforeach
  </div>

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
