<!DOCTYPE html>
<html lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <title>{{ $bs->website_title }} - {{ __('User Dashboard') }}</title>
    <link rel="icon" href="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}">
    @includeif('user.partials.styles')
    @php
        $selLang = App\Models\Language::where('code', request()->input('language'))->first();
    @endphp
  
    @yield('styles')

</head>

<body @if (request()->cookie('user-theme') == 'dark') data-background-color="dark" @endif
    data-form-language="{{ @$selLang->rtl == 1 ? 'rtl' : '' }}"
    data-dashboard-language="{{ $dashboard_language->rtl == 1 ? 'rtl' : '' }}">
    <div class="wrapper">

        {{-- top navbar area start --}}
        @includeif('user.partials.top-navbar')
        {{-- top navbar area end --}}

        {{-- side navbar area start --}}
        @includeif('user.partials.side-navbar')
        {{-- side navbar area end --}}

        @include('user.partials.ai-image-modal')
        @include('user.partials.ai-content-modal')
        
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    @php
                        $pendingUpiMemb = null;
                        try {
                            $lsUid = Auth::guard('web')->id() ?? Auth::id() ?? session('user_id');
                            if ($lsUid && class_exists('\App\Models\Membership')) {
                                $hasActiveApproved = \App\Models\Membership::where('user_id', $lsUid)
                                    ->where('status', 1)
                                    ->where('expire_date', '>=', \Carbon\Carbon::now()->toDateString())
                                    ->exists();

                                $pendingUpiMemb = \App\Models\Membership::where('user_id', $lsUid)
                                    ->where(function($q) {
                                        $q->where('status', 0)->orWhere('status', '0')->orWhere('status', 'pending');
                                    })
                                    ->whereNotNull('transaction_id')
                                    ->where('transaction_id', '!=', '')
                                    ->latest('id')
                                    ->first();

                                if ($pendingUpiMemb && $hasActiveApproved) {
                                    $latestActiveMemb = \App\Models\Membership::where('user_id', $lsUid)
                                        ->where('status', 1)
                                        ->latest('id')
                                        ->first();
                                    if ($latestActiveMemb && $latestActiveMemb->id >= $pendingUpiMemb->id) {
                                        $pendingUpiMemb = null; // Approved package is active, hide pending notice!
                                    }
                                }
                            }
                        } catch (\Throwable $e) {}

                        $lsDaysLeft = null;
                        $lsIsExpiringSoon = false;
                        $lsIsExpired = false;
                        $lsIsLocked = false;
                        $lsExpiryNotice = "";
                        try {
                            $lsLatestMemb = null;
                            if ($lsUid && class_exists('\App\Models\Membership')) {
                                $hasActive = \App\Models\Membership::where('user_id', $lsUid)
                                    ->where('status', 1)
                                    ->where('start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                                    ->where('expire_date', '>=', \Carbon\Carbon::now()->toDateTimeString())
                                    ->exists();
                                if (!$hasActive) {
                                    $lsLatestMemb = \App\Models\Membership::where('user_id', $lsUid)
                                        ->where('status', '<>', 2)
                                        ->orderBy('expire_date', 'desc')
                                        ->first();
                                } else {
                                    $lsLatestMemb = \App\Models\Membership::where('user_id', $lsUid)
                                        ->where('status', 1)
                                        ->orderBy('expire_date', 'desc')
                                        ->first();
                                }
                            }

                            if (!empty($lsLatestMemb) && !empty($lsLatestMemb->expire_date) && strpos($lsLatestMemb->expire_date, '9999') === false) {
                                $expDate = \Carbon\Carbon::parse($lsLatestMemb->expire_date);
                                $now = \Carbon\Carbon::now();
                                $diffSeconds = $now->diffInSeconds($expDate, false);

                                $warnDays = 7;
                                try {
                                    $bs = \App\Models\BasicSetting::first();
                                    if ($bs && !empty($bs->recurring_warning_first_days)) {
                                        $warnDays = (int)$bs->recurring_warning_first_days;
                                    }
                                } catch (\Throwable $te) {}

                                if ($diffSeconds <= 0) {
                                    $lsIsExpired = true;
                                    if ($diffSeconds < -120) {
                                        $lsIsLocked = true;
                                    }
                                    $lsExpiryNotice = "Your store package has expired on " . $expDate->format('M d, Y h:i A');
                                } elseif ($diffSeconds <= ($warnDays * 86400)) {
                                    $lsIsExpiringSoon = true;
                                    if ($diffSeconds < 3600) {
                                        $mins = max(1, (int)ceil($diffSeconds / 60));
                                        $lsExpiryNotice = "Your store package will expire in {$mins} minute(s) on " . $expDate->format('M d, Y h:i A');
                                    } elseif ($diffSeconds < 86400) {
                                        $hrs = (int)ceil($diffSeconds / 3600);
                                        $lsExpiryNotice = "Your store package will expire in {$hrs} hour(s) on " . $expDate->format('M d, Y h:i A');
                                    } else {
                                        $days = (int)ceil($diffSeconds / 86400);
                                        $lsExpiryNotice = "Your store package will expire in {$days} day(s) on " . $expDate->format('M d, Y');
                                    }
                                }
                            }
                        } catch (\Throwable $e) {}
                    @endphp

                    @if($pendingUpiMemb && !empty($pendingUpiMemb->transaction_id))
                        <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm" style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; color: #92400e;">
                            <div class="d-flex align-items-center gap-3">
                                <div style="font-size: 22px; color: #d97706;"><i class="fa-solid fa-clock-rotate-left"></i></div>
                                <div>
                                    <div class="fw-bold" style="font-size: 14.5px;">Notice: Your UPI payment (UTR: <code style="background:#fef3c7; color:#92400e; padding:2px 7px; border-radius:4px; font-weight:700;">{{ $pendingUpiMemb->transaction_id }}</code>) is pending for admin verification.</div>
                                    <div class="small" style="color: #b45309;">Your store features remain active while our admin team reviews your payment reference.</div>
                                </div>
                            </div>
                            <span class="badge badge-warning px-3 py-2 rounded-pill fw-bold" style="font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px;">Pending Verification</span>
                        </div>
                    @endif

                    @if($lsIsExpired || $lsIsExpiringSoon)
                        <div id="ls-expire-header-banner" style="{{ $lsIsLocked ? 'display:flex !important;' : 'display:none;' }} background: {{ $lsIsExpired ? '#fef2f2' : '#fffbeb' }}; border: {{ $lsIsLocked ? '2px solid #ef4444' : '1.5px solid ' . ($lsIsExpired ? '#fecaca' : '#fde68a') }}; border-radius: 12px; color: {{ $lsIsExpired ? '#991b1b' : '#92400e' }};" class="alert {{ $lsIsExpired ? 'alert-danger' : 'alert-warning' }} d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm">
                            <div class="d-flex align-items-center gap-3">
                                <div style="font-size: 24px; color: {{ $lsIsExpired ? '#dc2626' : '#d97706' }};">
                                    <i class="fa-solid {{ $lsIsLocked ? 'fa-lock' : 'fa-triangle-exclamation' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 14.5px;">{{ $lsIsLocked ? '🔒 Account Locked: ' : ($lsIsExpired ? 'Urgent: ' : 'Warning: ') }}{{ $lsExpiryNotice }}.</div>
                                    <div class="small" style="color: {{ $lsIsExpired ? '#b91c1c' : '#b45309' }};">
                                        {{ $lsIsLocked ? 'All navigation links are locked. Please renew your plan now to restore full access.' : ($lsIsExpired ? 'Please renew your plan now to restore your store and avoid public suspension.' : 'Please renew your package now to avoid store suspension.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('user.plan.extend.index') }}" class="btn btn-sm {{ $lsIsExpired ? 'btn-danger' : 'btn-warning text-dark' }} px-3 py-2 fw-bold rounded-pill text-white" style="font-size: 12.5px; text-decoration: none; white-space: nowrap;">
                                    <i class="fas fa-sync-alt me-1"></i> {{ $lsIsLocked ? 'Renew Plan Now' : 'Renew Plan' }}
                                </a>
                                @if(!$lsIsLocked)
                                    <button type="button" class="btn btn-sm btn-link text-muted p-1 text-decoration-none" onclick="dismissLsExpireBanner()" title="Close Notification" style="font-size: 22px; line-height: 1; border: none; background: transparent; cursor: pointer;">
                                        &times;
                                    </button>
                                @endif
                            </div>
                        </div>
                        <script>
                            (function() {
                                @if(!$lsIsLocked)
                                    if (!sessionStorage.getItem('ls_dismiss_expire_banner')) {
                                        var el = document.getElementById('ls-expire-header-banner');
                                        if (el) el.style.display = 'flex';
                                    }
                                @else
                                    var el = document.getElementById('ls-expire-header-banner');
                                    if (el) el.style.display = 'flex';
                                @endif
                            })();
                            function dismissLsExpireBanner() {
                                sessionStorage.setItem('ls_dismiss_expire_banner', '1');
                                var el = document.getElementById('ls-expire-header-banner');
                                if (el) el.style.display = 'none';
                            }
                        </script>
                        @if($lsIsLocked)
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.querySelectorAll('.sidebar a').forEach(function(link) {
                                    var href = (link.getAttribute('href') || '').toLowerCase();
                                    var isExempt = link.closest('.ls-nav-exempt') !== null 
                                        || href.indexOf('extend-membership') !== -1 
                                        || href.indexOf('payment') !== -1 
                                        || href.indexOf('logout') !== -1;
                                    if (!isExempt && href && href !== '#' && href !== 'javascript:void(0)') {
                                        link.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            window.location.href = "{{ route('user.plan.extend.index') }}";
                                            return false;
                                        });
                                    }
                                });
                            });
                        </script>
                        @endif
                    @endif
                    @yield('content')
                </div>
            </div>
            @includeif('user.partials.footer')
        </div>
    </div>

    @includeif('user.partials.scripts')

    {{-- Loader --}}
    <div class="request-loader">
        <img src="{{ asset('assets/admin/img/loader.gif') }}" alt="">
    </div>
    {{-- Loader --}}
</body>

</html>
