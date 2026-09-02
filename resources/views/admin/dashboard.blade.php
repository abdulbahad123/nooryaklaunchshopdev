@extends('admin.layout')

@php
    $admin = Auth::guard('admin')->user();
    if (!empty($admin->role)) {
        $permissions = $admin->role->permissions;
        $permissions = json_decode($permissions, true);
    }

    $tokenTooltipText = '<strong>' . __('AI Token Statistics') . ' (' . __('All Active Tenants') . ')</strong><br><br>' .
        '<strong>Scope:</strong> ' . __('Calculated by combining all ACTIVE tenant memberships only.') . '<br>' .
        '<strong>Required AI Tokens:</strong> ' . __('Total allocated tokens for this AI engine across all active tenant plans.') . '<br>' .
        '<strong>Used AI Tokens:</strong> ' . __('Total number of tokens already used by tenant users.') . '<br>' .
        '<strong>Remaining AI Tokens:</strong> ' . __('Remaining available tokens for all tenants combined.') . '<br><br>' .
        '<em>' . __('Important: Combined usage across all tenants, not individual limits.') . '</em>';

    $imageTooltipText = '<strong>' . __('AI Image Statistics') . ' (' . __('All Active Tenants') . ')</strong><br><br>' .
        '<strong>Scope:</strong> ' . __('Calculated by combining all ACTIVE tenant memberships only.') . '<br>' .
        '<strong>Required AI Images:</strong> ' . __('Total image quota allocated for this AI engine across active tenants.') . '<br>' .
        '<strong>Used AI Images:</strong> ' . __('Total number of AI-generated images already created.') . '<br>' .
        '<strong>Remaining AI Images:</strong> ' . __('Remaining available images for all tenants combined.') . '<br><br>' .
        '<em>' . __('Important: Combined usage across all tenants, not individual limits.') . '</em>';
@endphp

@section('content')
    <!-- Top Welcome Banner -->
    <div class="welcome-banner-v2">
        <div>
            <h1 class="welcome-title-v2">{{ __('Welcome back,') }} <span class="grad-purple">Ecom Builder Admin!</span> 👋</h1>
            <div class="welcome-accent-line"></div>
            <p class="welcome-subtitle-v2">{{ __('Here\'s what\'s happening with your platform today.') }}</p>
        </div>
        <div class="welcome-rocket-box d-none d-lg-flex">
            <div class="r-labels">
                <span class="l1">Build</span>
                <span class="l2">Launch</span>
                <span class="l3">Grow</span>
            </div>
            <span style="font-size: 2.8rem; filter: drop-shadow(0 10px 20px rgba(124, 58, 237, 0.4));">🚀</span>
        </div>
    </div>

    <!-- 6 Main Stat Cards Grid (Mobile 2-Columns Per Row Responsive) -->
    <div class="stat-card-grid-v2">
        @if (empty($admin->role) || (!empty($permissions) && in_array('Users Management', $permissions)))
            <a class="stat-card-pixel c-blue" href="{{ route('admin.register.user') }}">
                <div class="row-top">
                    <div class="icon-circle">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="arrow-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div>
                    <div class="c-category">{{ __('Registered Users') }}</div>
                    <div class="c-number">{{ App\Models\User::count() }}</div>
                </div>
                <div class="row-bottom">
                    <div class="growth-text">
                        <span class="growth-val"><i class="fas fa-arrow-up"></i> 12%</span>
                        <span class="growth-lbl">from last month</span>
                    </div>
                    <div class="sparkline-graphic">
                        <span style="height: 35%"></span>
                        <span style="height: 55%"></span>
                        <span style="height: 40%"></span>
                        <span style="height: 75%"></span>
                        <span style="height: 100%"></span>
                    </div>
                </div>
            </a>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Package Management', $permissions)))
            <a class="stat-card-pixel c-green" href="{{ route('admin.package.index') }}">
                <div class="row-top">
                    <div class="icon-circle">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div class="arrow-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div>
                    <div class="c-category">{{ __('Packages') }}</div>
                    <div class="c-number">{{ App\Models\Package::count() }}</div>
                </div>
                <div class="row-bottom">
                    <div class="growth-text">
                        <span class="growth-val"><i class="fas fa-arrow-up"></i> 33%</span>
                        <span class="growth-lbl">from last month</span>
                    </div>
                    <div class="sparkline-graphic">
                        <span style="height: 45%"></span>
                        <span style="height: 60%"></span>
                        <span style="height: 50%"></span>
                        <span style="height: 85%"></span>
                        <span style="height: 100%"></span>
                    </div>
                </div>
            </a>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Payment Log', $permissions)))
            <a class="stat-card-pixel c-red" href="{{ route('admin.payment-log.index') }}">
                <div class="row-top">
                    <div class="icon-circle">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="arrow-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div>
                    <div class="c-category">{{ __('Payment Logs') }}</div>
                    <div class="c-number">{{ App\Models\Membership::count() }}</div>
                </div>
                <div class="row-bottom">
                    <div class="growth-text">
                        <span class="growth-val"><i class="fas fa-arrow-up"></i> 18%</span>
                        <span class="growth-lbl">from last month</span>
                    </div>
                    <div class="sparkline-graphic">
                        <span style="height: 30%"></span>
                        <span style="height: 50%"></span>
                        <span style="height: 45%"></span>
                        <span style="height: 70%"></span>
                        <span style="height: 95%"></span>
                    </div>
                </div>
            </a>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Admins Management', $permissions)))
            <a class="stat-card-pixel c-purple" href="{{ route('admin.user.index') }}">
                <div class="row-top">
                    <div class="icon-circle">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="arrow-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div>
                    <div class="c-category">{{ __('Registered Admins') }}</div>
                    <div class="c-number">{{ App\Models\Admin::count() }}</div>
                </div>
                <div class="row-bottom">
                    <div class="growth-text">
                        <span class="growth-val"><i class="fas fa-arrow-up"></i> 0%</span>
                        <span class="growth-lbl">from last month</span>
                    </div>
                    <div class="sparkline-graphic">
                        <span style="height: 40%"></span>
                        <span style="height: 40%"></span>
                        <span style="height: 60%"></span>
                        <span style="height: 80%"></span>
                        <span style="height: 90%"></span>
                    </div>
                </div>
            </a>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Pages', $permissions)))
            <a class="stat-card-pixel c-cyan" href="{{ route('admin.blog.index', ['language' => $defaultLang ? $defaultLang->code : 'en']) }}">
                <div class="row-top">
                    <div class="icon-circle">
                        <i class="fas fa-rss"></i>
                    </div>
                    <div class="arrow-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div>
                    <div class="c-category">{{ __('Blog') }}</div>
                    <div class="c-number">{{ $defaultLang ? $defaultLang->blogs()->count() : 0 }}</div>
                </div>
                <div class="row-bottom">
                    <div class="growth-text">
                        <span class="growth-val"><i class="fas fa-arrow-up"></i> 25%</span>
                        <span class="growth-lbl">from last month</span>
                    </div>
                    <div class="sparkline-graphic">
                        <span style="height: 30%"></span>
                        <span style="height: 50%"></span>
                        <span style="height: 65%"></span>
                        <span style="height: 70%"></span>
                        <span style="height: 100%"></span>
                    </div>
                </div>
            </a>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Users Management', $permissions)))
            <a class="stat-card-pixel c-orange" href="{{ route('admin.subscriber.index') }}">
                <div class="row-top">
                    <div class="icon-circle">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div class="arrow-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div>
                    <div class="c-category">{{ __('Subscribers') }}</div>
                    <div class="c-number">{{ App\Models\Subscriber::count() }}</div>
                </div>
                <div class="row-bottom">
                    <div class="growth-text">
                        <span class="growth-val"><i class="fas fa-arrow-up"></i> 50%</span>
                        <span class="growth-lbl">from last month</span>
                    </div>
                    <div class="sparkline-graphic">
                        <span style="height: 25%"></span>
                        <span style="height: 45%"></span>
                        <span style="height: 55%"></span>
                        <span style="height: 80%"></span>
                        <span style="height: 100%"></span>
                    </div>
                </div>
            </a>
        @endif
    </div>

    <!-- AI Engine Cards Grid with Hover Tooltip Display -->
    <div class="stat-card-grid-v2 mb-4">
        @if (!empty($aiEngineStats))
            @foreach ($aiEngineStats as $key => $stat)
                @php
                    $engineName = strtoupper($stat['engine']);
                    $cardClass = str_contains($engineName, 'OPENAI') ? 'p-orange' : (str_contains($engineName, 'POLLINATIONS') ? 'p-cyan' : 'p-purple');
                @endphp
                <div class="ai-card-pixel {{ $cardClass }}"
                    data-toggle="tooltip" data-placement="top" data-html="true"
                    title="{!! $tokenTooltipText !!}">
                    <div class="ai-head">
                        <div class="icon-circle" style="width:36px; height:36px; font-size:0.95rem;">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="ai-title">{{ __('AI Engine') }} : {{ $engineName }}</div>
                    </div>
                    <div class="ai-metric">Required AI Tokens : {{ $stat['token_required'] }}</div>
                    <div class="ai-metric">Used AI Tokens : {{ $stat['token_used'] }}</div>
                    <div class="ai-metric">Remaining AI Tokens : {{ $stat['token_remaining'] }}</div>
                    <div class="ai-progress-row">
                        <div class="ai-progress-bar-bg">
                            <div class="ai-progress-bar-fill" style="width: 0%;"></div>
                        </div>
                        <span class="ai-progress-percent">0%</span>
                    </div>
                </div>
            @endforeach
        @endif

        @if (!empty($aiEngineStats))
            @foreach ($aiEngineStats as $key => $stat)
                @php
                    $engineName = strtoupper($stat['engine']);
                    $cardClass = str_contains($engineName, 'OPENAI') ? 'p-blue' : (str_contains($engineName, 'POLLINATIONS') ? 'p-red' : 'p-indigo');
                @endphp
                <div class="ai-card-pixel {{ $cardClass }}"
                    data-toggle="tooltip" data-placement="top" data-html="true"
                    title="{!! $imageTooltipText !!}">
                    <div class="ai-head">
                        <div class="icon-circle" style="width:36px; height:36px; font-size:0.95rem;">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="ai-title">{{ __('AI Image Engine') }} : {{ $engineName }}</div>
                    </div>
                    <div class="ai-metric">Required AI Images : {{ $stat['image_required'] }}</div>
                    <div class="ai-metric">Used AI Images : {{ $stat['image_used'] }}</div>
                    <div class="ai-metric">Remaining AI Images : {{ $stat['image_remaining'] }}</div>
                    <div class="ai-progress-row">
                        <div class="ai-progress-bar-bg">
                            <div class="ai-progress-bar-fill" style="width: 0%;"></div>
                        </div>
                        <span class="ai-progress-percent">0%</span>
                    </div>
                </div>
            @endforeach
        @endif

    </div>


    <!-- Monthly Charts Section -->
    <div class="row">
        @if (empty($admin->role) || (!empty($permissions) && in_array('Payment Log', $permissions)))
            <div class="col-lg-6 mb-4">
                <div class="card-chart-pixel">
                    <div class="c-head">
                        <div class="c-title">
                            <i class="far fa-calendar-alt text-primary"></i>
                            <span>{{ __('Monthly Income') }} ({{ date('Y') }})</span>
                        </div>
                        <select class="c-select">
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                    <div class="p-4">
                        <div class="chart-container" style="position: relative; height: 300px;">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Users Management', $permissions)))
            <div class="col-lg-6 mb-4">
                <div class="card-chart-pixel">
                    <div class="c-head">
                        <div class="c-title">
                            <i class="far fa-calendar-check text-purple"></i>
                            <span>{{ __('Monthly Premium Users') }} ({{ date('Y') }})</span>
                        </div>
                        <select class="c-select">
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                    <div class="p-4">
                        <div class="chart-container" style="position: relative; height: 300px;">
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
    $userTotals = [];

    for ($i = 1; $i <= 12; $i++) {
        $monthNum = $i;
        $dateObj = DateTime::createFromFormat('!m', $monthNum);
        $months[] = $dateObj->format('M');

        $inFound = 0;
        foreach ($incomes as $key => $income) {
            if ($income->month == $i) {
                $inTotals[] = (float)$income->total;
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
                $userTotals[] = (int)$user->total;
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

        $(document).ready(function() {
            if ($.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip({
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });
    </script>
    <script src="{{ asset('assets/admin/js/chart-init.js') }}"></script>
@endsection
