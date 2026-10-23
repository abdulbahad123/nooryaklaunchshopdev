@php
    $plan = $user->plan;
    if($plan){
        $plan_detail = \Pricing::plansWithFeatures($plan->id);
    }

    $pricing = \Pricing::plansWithFeatures();
    $planTypes = \Modules\AdminPlans\Facades\Plan::getTypes();

    $expired = false;
    if ($user->expiration_date && $user->expiration_date > 0) {
        $expired = $user->expiration_date < time();
    }

    $credit_summary = Credit::getCreditUsageSummary();
@endphp

@extends('layouts.app')

@section('content')
    @include("appprofile::partials.profile-header")
    
    <div class="container py-5 pricing">

        @include("components.main-message")

        <div class="mb-5">
            <x-sub-header
                title="{{ __('Subscription Plan') }}"
                description="{{ __('Manage your plan. Upgrade for more features!') }}"
            />
        </div>

        <div class="card shadow-sm rounded-4 mb-5 mx-auto">
            <div class="card-body p-0">
                <div class="d-flex flex-column flex-md-row">
                    <!-- Left: Plan Info -->
                    <div class="flex-fill border-end-md mb-4 mb-md-0">
                        <div class="border-bottom fw-semibold fs-14 text-uppercase px-4 py-3">
                            {{ __("Your Plan") }}
                        </div>
                        <div class="p-4 fs-14">
                            <div class="size-50 d-flex gap-10 align-items-center bg-gray-100 border border-gray-200 fs-25 justify-content-center b-r-20 mb-2">
                                <i class="fa-light fa-user-crown text-warning"></i>
                            </div>
                            <div class="mb-2 fw-semibold fs-20"><b>{{ $plan->name ?? __("No Plan") }}</b>
                                @if($plan && $plan->free_plan)
                                    <span class="badge badge-outline badge-light badge-pill badge-sm position-relative t--5">
                                        {{ __("Free Plan") }}
                                    </span>
                                @endif
                            </div>
                            <div class="mb-2">
                                {{ __('Expiration date :') }}
                                <b class="{{ $expired ? 'text-danger' : 'text-success' }}">
                                    @if($user->expiration_date == -1)
                                        {{ __('Unlimited') }}
                                    @elseif($user->expiration_date)
                                        {{ date_show($user->expiration_date) }}
                                    @else
                                        {{ __('N/A') }}
                                    @endif
                                </b>
                            </div>

                            <div class="mt-4 mb-1 d-flex justify-content-between align-items-center">
                                <span class="fs-12">{{ __('Credits used') }}</span>
                                <span class="small fw-bold text-primary">{{ $credit_summary['progress_label'] }}</span>
                            </div>
                            <div class="progress wp-100 h-10" style="background: #eee">
                                <div class="progress-bar {{ $credit_summary['is_unlimited'] ? 'bg-success' : 'bg-dark' }}"
                                     style="width: {{ $credit_summary['progress_value'] }}%;">
                                </div>
                            </div>
                            <div class="small mt-1">
                                {{ __('Used:') }} <b>{{ number_format($credit_summary['used']) }}</b>
                                {{ $credit_summary['is_unlimited'] ? '' : '/ ' . number_format($credit_summary['limit']) }}
                                ({{ __('Left:') }} <b>{{ $credit_summary['is_unlimited'] ? __('Unlimited') : number_format($credit_summary['remaining']) }}</b>)
                            </div>
                            @if($credit_summary['quota_reached'])
                                <div class="text-danger small mt-1">{{ $credit_summary['message'] }}</div>
                            @endif

                            
                            <div class="d-flex gap-10 mt-4 flex-wrap">
                                @if($plan && Plan::hasSubscription())
                                <a href="{{ route("payment.cancel_subscription") }}" class="btn btn-outline btn-danger btn-md actionItem" data-confirm="{{ __("Are you sure you want to cancel your subscription?") }}" data-redirect="">{{ __("Cancel Subscription") }}</a>
                                @else
                                    @if($plan && !$plan->free_plan)
                                    <a href="#pricingTab" class="btn btn-warning btn-md">{{ __("Upgrade Plan") }}</a>
                                    <a href="{{ route('payment.index', $plan->id_secure) }}" class="btn btn-dark btn-md">{{ __("Renew Plan") }}</a>
                                    @endif
                                @endif
                            </div>
                            
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="border-bottom fw-semibold fs-14 text-uppercase px-4 py-3">
                            {{ __("Plan Permissions") }}
                        </div>
                        <div class="p-4">
                            @foreach($plan_detail['features']??[] as $feature)
                                <li class="mb-2 d-flex align-items-center gap-1">
                                    <span class="d-flex align-items-center justify-content-center size-20 d-block bg-gray-100 border border-gray-300 b-r-50 fs-13 me-2 {{ $feature['check'] ? 'text-success' : 'text-danger' }}">
                                        <i class="fa-regular fa-{{ $feature['check'] ? 'check' : 'xmark' }}"></i>
                                    </span>

                                    {{ __($feature['label']) }}
                                    {{-- Popup info icon --}}
                                    @if(!empty($feature['subfeature']))
                                        <div class="feature-popup-wrapper position-relative ms-1 d-inline-block">
                                            <span class="info-hover-icon" tabindex="0">
                                                <i class="fa fa-info-circle text-primary" style="cursor:pointer;"></i>
                                            </span>
                                            <div class="features-popup shadow-lg">
                                                @foreach($feature['subfeature'] as $group)
                                                    <div class="fw-bold mb-1 small px-3 pt-2">
                                                        {{ __($group['tab_name'] ?? '') }}
                                                    </div>
                                                    <ul class="list-unstyled mb-2 px-3">
                                                        @foreach($group['items'] as $item)
                                                            <li class="d-flex align-items-center">
                                                                <span class="d-flex align-items-center justify-content-center size-20 d-block bg-gray-100 border border-gray-300 b-r-50 fs-13 me-2 {{ $feature['check'] ? 'text-success' : 'text-gray-600' }}">
                                                                    <i class="fa-regular fa-{{$feature['check'] ? 'check' : 'xmark' }}"></i>
                                                                </span>
                                                                {{ __($item['label']) }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </li>
                            @endforeach

                            @empty($plan_detail)
                                <div class="text-center py-5">
                                    <span class="d-inline-block mb-2 fs-70">
                                        <i class="fa fa-box-open text-primary"></i>
                                    </span>
                                    <div class="fw-semibold text-gray-700" style="opacity:.85">
                                        {{ __("No features available for this plan.") }}
                                    </div>
                                </div>
                            @endempty

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2 fs-35">{{ __('Choose a plan that suits you. Grow business fast.') }}</h2>
            <p class="mx-auto text-lg text-muted max-w-500">
                {{ __('Choose an affordable plan packed with top features to engage your audience, create loyalty, and boost sales.') }}
            </p>
        </div>

        <div class="d-flex mx-auto justify-content-center mb-5">
            <ul class="nav nav-tabs justify-content-center mb-4 gap-0 b-r-20 border border-gray-300 overflow-hidden" id="pricingTab" role="tablist">
                @foreach($planTypes as $typeKey => $typeLabel)
                    <li class="nav-item {{ $typeKey==2?"border-start border-end border-gray-300":"" }}" role="presentation">
                        <button
                            class="nav-link px-4 border-bottom-0 py-2 fw-bold bg-active-gray-200 text-active-gray-800 @if($loop->first) active @endif"
                            id="tab-{{ $typeKey }}"
                            data-bs-toggle="tab"
                            data-bs-target="#content-{{ $typeKey }}"
                            type="button"
                            role="tab"
                            aria-controls="content-{{ $typeKey }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        >
                            {{ __($typeLabel) }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Plans content --}}
        <div class="tab-content" id="pricingTabContent">
            @foreach($planTypes as $typeKey => $typeLabel)
                <div class="tab-pane fade @if($loop->first) show active @endif" 
                    id="content-{{ $typeKey }}" 
                    role="tabpanel"
                    aria-labelledby="tab-{{ $typeKey }}">
                    
                    @php
                        $tabPlans = collect($pricing[$typeKey] ?? []);
                        // Current plan for user
                        $renewPlan = $tabPlans->firstWhere('id', $user->plan_id ?? 0);
                        if (!$renewPlan && $tabPlans->isNotEmpty()) {
                            $renewPlan = $tabPlans->first();
                        }
                        $currPrice = $renewPlan ? (float)($renewPlan['price'] ?? 0) : 0;

                        // Upgrade plans: strictly higher price than current plan. Lesser packages are NEVER shown!
                        $upgradePlans = $tabPlans->filter(function($p) use ($renewPlan, $currPrice) {
                            if (!$renewPlan) return true;
                            if ($p['id'] == $renewPlan['id']) return false;
                            return (float)($p['price'] ?? 0) > $currPrice;
                        })->values();
                    @endphp

                    {{-- SECTION 1: RENEW CURRENT PLAN (HORIZONTAL RECTANGLE CARD) --}}
                    @if($renewPlan)
                    <div class="mb-5">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width: 4px; height: 26px; background: #ef4444; border-radius: 4px;"></div>
                            <h3 class="h4 fw-bold text-dark mb-0">
                                {{ __('Please renew your current plan to use the website') }}
                            </h3>
                        </div>

                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px; background: #ffffff; border: 2px solid #ef4444 !important; box-shadow: 0 10px 30px rgba(239, 68, 68, 0.08);">
                            <div class="card-body p-4 p-md-5">
                                <div class="row align-items-center">
                                    <!-- Left: Title, Badges & Feature Highlights -->
                                    <div class="col-lg-7 col-md-12 mb-4 mb-lg-0 border-end-lg pe-lg-4">
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                            <span class="badge bg-danger px-3 py-1 fw-bold text-white" style="border-radius: 8px; font-size: 12px; background: #ef4444 !important;">
                                                <i class="fa-solid fa-crown me-1"></i> {{ __('Current Plan') }}
                                            </span>
                                            <h2 class="h3 fw-bold text-dark mb-0">{{ $renewPlan['name'] }}</h2>
                                        </div>
                                        <p class="text-muted mb-3 fw-medium">
                                            {{ $renewPlan['desc'] ?? __('Your subscription has expired. Renew your current plan now to immediately restore full access to WhatsApp campaigns, automation, and messages.') }}
                                        </p>

                                        <div class="row g-2 pt-2">
                                            @foreach(array_slice($renewPlan['features'] ?? [], 0, 6) as $feature)
                                                <div class="col-sm-6 mb-2">
                                                    <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                                                        <span class="d-flex align-items-center justify-content-center size-20 d-block bg-gray-100 border border-gray-300 b-r-50 fs-13 me-2 {{ $feature['check'] ? 'text-success' : 'text-danger' }}">
                                                            <i class="fa-regular fa-{{ $feature['check'] ? 'check' : 'xmark' }}"></i>
                                                        </span>
                                                        <span>{{ __($feature['label']) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Right: Price & Pay Now Button -->
                                    <div class="col-lg-5 col-md-12 text-center text-lg-end ps-lg-4">
                                        <div class="mb-2">
                                            <span class="h1 fw-bold text-dark" style="font-size: 42px; color: #1e293b;">
                                                {{ !empty($renewPlan['free_plan']) ? price(0) : price($renewPlan['price'] ?? 0) }}
                                            </span>
                                            <span class="text-muted fw-bold" style="font-size: 16px;">/{{ strtolower(__($typeLabel)) }}</span>
                                        </div>
                                        <div class="text-muted small mb-3">
                                            {{ __('Instant automatic reactivation upon payment confirmation') }}
                                        </div>
                                        <a href="{{ route('payment.index', $renewPlan['id_secure']) }}" class="btn btn-danger fw-bold px-5 py-3 shadow" style="border-radius: 12px; font-size: 16px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; letter-spacing: 0.3px; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); text-decoration: none; color: #fff;">
                                            <i class="fa-regular fa-bolt me-2"></i> {{ __('Pay Now & Renew Plan') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- SECTION 2: UPGRADE PLAN TO EXPLORE NEW FEATURES (HIGHER TIERS ONLY) --}}
                    @if(count($upgradePlans) > 0)
                    <div class="mb-5">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width: 4px; height: 26px; background: #6366f1; border-radius: 4px;"></div>
                            <h3 class="h4 fw-bold text-dark mb-0">
                                {{ __('Upgrade your plan to explore new features') }}
                            </h3>
                        </div>

                        @foreach($upgradePlans as $upPlan)
                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px; background: #ffffff; border: 2px solid #6366f1 !important; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.08);">
                            <div class="card-body p-4 p-md-5">
                                <div class="row align-items-center">
                                    <!-- Left: Title, Badges & Feature Highlights -->
                                    <div class="col-lg-7 col-md-12 mb-4 mb-lg-0 border-end-lg pe-lg-4">
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                            <span class="badge bg-primary px-3 py-1 fw-bold text-white" style="border-radius: 8px; font-size: 12px; background: #6366f1 !important;">
                                                <i class="fa-solid fa-arrow-up me-1"></i> {{ __('Upgrade Option') }}
                                            </span>
                                            <h2 class="h3 fw-bold text-dark mb-0">{{ $upPlan['name'] }}</h2>
                                        </div>
                                        <p class="text-muted mb-3 fw-medium">
                                            {{ $upPlan['desc'] ?? __('Upgrade to this plan to unlock more WhatsApp messaging credits, faster automation, and premium features.') }}
                                        </p>

                                        <div class="row g-2 pt-2">
                                            @foreach(array_slice($upPlan['features'] ?? [], 0, 6) as $feature)
                                                <div class="col-sm-6 mb-2">
                                                    <div class="d-flex align-items-center text-dark" style="font-size: 14.5px;">
                                                        <span class="d-flex align-items-center justify-content-center size-20 d-block bg-gray-100 border border-gray-300 b-r-50 fs-13 me-2 {{ $feature['check'] ? 'text-success' : 'text-danger' }}">
                                                            <i class="fa-regular fa-{{ $feature['check'] ? 'check' : 'xmark' }}"></i>
                                                        </span>
                                                        <span>{{ __($feature['label']) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Right: Price & Upgrade Button -->
                                    <div class="col-lg-5 col-md-12 text-center text-lg-end ps-lg-4">
                                        <div class="mb-2">
                                            <span class="h1 fw-bold text-dark" style="font-size: 42px; color: #1e293b;">
                                                {{ !empty($upPlan['free_plan']) ? price(0) : price($upPlan['price'] ?? 0) }}
                                            </span>
                                            <span class="text-muted fw-bold" style="font-size: 16px;">/{{ strtolower(__($typeLabel)) }}</span>
                                        </div>
                                        <div class="text-muted small mb-3">
                                            {{ __('Switch plan instantly with seamless feature upgrade') }}
                                        </div>
                                        <a href="{{ route('payment.index', $upPlan['id_secure']) }}" class="btn btn-primary fw-bold px-5 py-3 shadow" style="border-radius: 12px; font-size: 16px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; letter-spacing: 0.3px; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); text-decoration: none; color: #fff;">
                                            <i class="fa-regular fa-rocket me-2"></i> {{ __('Upgrade & Pay Now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if(!$renewPlan && count($upgradePlans) == 0)
                        <div class="text-center text-muted py-5">{{ __('No plans available.') }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
