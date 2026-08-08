@extends('admin.layout')

@php
    $admin = Auth::guard('admin')->user();
    if (!empty($admin->role)) {
        $permissions = $admin->role->permissions;
        $permissions = json_decode($permissions, true);
    }
@endphp

@section('content')
    <div class="mt-2 mb-4">
        <h2 class="{{ request()->cookie('admin-theme') == 'dark' ? 'text-white' : 'text-dark' }} pb-2">
            {{ __('Welcome back,') }}
            {{ Auth::guard('admin')->user()->first_name }}
            {{ Auth::guard('admin')->user()->last_name }}!</h2>
    </div>
    <div class="row">
        @if (empty($admin->role) || (!empty($permissions) && in_array('Users Management', $permissions)))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-info card-round" href="{{ route('admin.register.user') }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Registered Users') }}</p>
                                    <h4 class="card-title">{{ App\Models\User::count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Package Management', $permissions)))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-success card-round" href="{{ route('admin.package.index') }}">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-list-ul"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Packages') }}</p>
                                    <h4 class="card-title">{{ App\Models\Package::count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif


        @if (empty($admin->role) || (!empty($permissions) && in_array('Payment Log', $permissions)))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-danger card-round" href="{{ route('admin.payment-log.index') }}">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-money-check-alt"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Payment Logs') }}</p>
                                    <h4 class="card-title">{{ App\Models\Membership::count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Admins Management', $permissions)))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-secondary card-round" href="{{ route('admin.user.index') }}">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Registerd Admins') }}</p>
                                    <h4 class="card-title">{{ App\Models\Admin::count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Pages', $permissions)))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-primary card-round"
                    href="{{ route('admin.blog.index', ['language' => $defaultLang->code]) }}">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-blog"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Blog') }}</p>
                                    <h4 class="card-title">{{ $defaultLang->blogs()->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Users Management', $permissions)))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-warning card-round" href="{{ route('admin.subscriber.index') }}">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-mail-bulk"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Subscribers') }}</p>
                                    <h4 class="card-title">{{ App\Models\Subscriber::count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>

    {{-- Required AI Tokens Box --}}

    @php
        $tokenTooltipText =
            '<strong>' .
            __('AI Token Statistics') .
            ' (' .
            __('All Active Tenants') .
            ')' .
            '</strong><br>' .
            '<strong>' .
            __('Scope') .
            ':</strong> ' .
            __('This data is calculated by combining all ACTIVE tenant memberships only') .'.'.
            '<br><br>' .
            '<strong>' .
            __('Required AI Tokens') .
            ':</strong> ' .
            __('Total allocated tokens for this AI engine across all active tenant plans') .'.'.
            '<br><br>' .
            '<strong>' .
            __('Used AI Tokens') .
            ':</strong> ' .
            __('Total number of tokens already used by all tenant users under this AI engine') .'.'.
            '<br><br>' .
            '<strong>' .
            __('Remaining AI Tokens') .
            ':</strong> ' .
            __('Remaining available tokens for all tenants combined') .'.'.
            '<br><br>' .
            '<strong>' .
            __('Important') .
            ':</strong> ' .
            __('These numbers represent combined usage across all tenants, not individual user limits') . '.';
    @endphp

    <div class="row">
        @if (!empty($aiEngineStats))
            @foreach ($aiEngineStats as $key => $stat)
                <div class="col-sm-6 col-md-4">
                    <div class="card ai-card-v2 ai-card-{{ $key }} card-stats card-secondary card-round card-tooltip-trigger p-2"
                        href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-html="true"
                        title="{!! $tokenTooltipText !!}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                </div>
                                <div class="col-7 ai-engine-text">
                                    <h5 class="mb-1">
                                        {{ __('AI Engine') }} :
                                        <strong>{{ strtoupper($stat['engine']) }}</strong>
                                    </h5>
                                    <p class="mb-1"><strong>{{ __('Required AI Tokens') }} :</strong>
                                        {{ $stat['token_required'] }}</p>
                                    <p class="mb-1"><strong>{{ __('Used AI Tokens') }} :</strong>
                                        {{ $stat['token_used'] }}</p>
                                    <p class="mb-0">
                                        <strong>{{ __('Remaining AI Tokens') }} :</strong>
                                        <span>
                                            {{ $stat['token_remaining'] }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @php
            $imageTooltipText =
                '<strong>' .
                __('AI Image Statistics') .
                ' (' .
                __('All Active Tenants') .
                ')' .
                '</strong><br>' .
                '<strong>' .
                __('Scope') .
                ':</strong> ' .
                __('This data is calculated by combining all ACTIVE tenant memberships only') .'.'.
                '<br><br>' .
                '<strong>' .
                __('Required AI Images') .
                ':</strong> ' .
                __('Total image generation quota allocated for this AI engine across all active tenants') . '.' .
                '<br><br>' .
                '<strong>' .
                __('Used AI Images') .
                ':</strong> ' .
                __('Total number of AI-generated images already created by all tenant users') . '.' .
                '<br><br>' .
                '<strong>' .
                __('Remaining AI Images') .
                ':</strong> ' .
                __('Remaining available images for all tenants combined') . '.' .
                '<br><br>' .
                '<strong>' .
                __('Important') .
                ':</strong> ' .
                __('These numbers represent combined usage across all tenants, not individual user limits') . '.';
        @endphp

        @if (!empty($aiEngineStats))
            @foreach ($aiEngineStats as $key => $stat)
                <div class="col-sm-6 col-md-4">
                    <div class="card ai-card-v2 aiimg ai-card-{{ $key }} card-stats card-secondary card-round card-tooltip-trigger p-2"
                        href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-html="true"
                        title="{!! $imageTooltipText !!}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-image"></i>
                                    </div>
                                </div>
                                <div class="col-7 ai-engine-text">
                                    <h5 class="mb-1">
                                        {{ __('AI Image Engine') }} :
                                        <strong>{{ strtoupper($stat['engine']) }}</strong>
                                    </h5>
                                    <p class="mb-1"><strong>{{ __('Required AI Images') }} :</strong>
                                        {{ $stat['image_required'] }}</p>
                                    <p class="mb-1"><strong>{{ __('Used AI Images') }} :</strong>
                                        {{ $stat['image_used'] }}</p>
                                    <p class="mb-0">
                                        <strong>{{ __('Remaining AI Images') }} :</strong>
                                        <span>
                                            {{ $stat['image_remaining'] }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    {{-- AI Token Stats (one card per engine) --}}



    <div class="row">
        @if (empty($admin->role) || (!empty($permissions) && in_array('Payment Log', $permissions)))
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{ __('Monthly Income') }} ({{ date('Y') }})</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Users Management', $permissions)))
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{ __('Monthly Premium Users') }} ({{ date('Y') }})</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@php
    $months = [];
    $inTotals = [];

    for ($i = 1; $i <= 12; $i++) {
        $monthNum = $i;
        $dateObj = DateTime::createFromFormat('!m', $monthNum);
        $months[] = $dateObj->format('M');

        $inFound = 0;
        foreach ($incomes as $key => $income) {
            if ($income->month == $i) {
                $inTotals[] = $income->total;
                $inFound = 1;
                break;
            }
        }
        if ($inFound == 0) {
            $inTotals[] = 0;
        }

        $userFound = 0;
        foreach ($users as $key => $user) {
            if ($user->month == $i) {
                $userTotals[] = $user->total;
                $userFound = 1;
                break;
            }
        }
        if ($userFound == 0) {
            $userTotals[] = 0;
        }
    }

@endphp
@section('scripts')
    <!-- Chart JS -->
    <script src="{{ asset('assets/admin/js/plugin/chart.min.js') }}"></script>
    <script>
        "use strict";
        var months = {!! json_encode($months) !!};
        var inTotals = {{ json_encode($inTotals) }};
        var userTotals = {{ json_encode($userTotals) }};
        var Monthly_Income = "{{ __('Monthly Income') }}";
        var Monthly_Premium_Users = "{{ __('Monthly Premium Users') }}";
    </script>
    <script src="{{ asset('assets/admin/js/chart-init.js') }}"></script>
@endsection
