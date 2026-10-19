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
                                $pendingUpiMemb = \App\Models\Membership::where('user_id', $lsUid)
                                    ->where(function($q) {
                                        $q->where('status', 0)->orWhere('status', '0')->orWhere('status', 'pending');
                                    })
                                    ->whereNotNull('transaction_id')
                                    ->where('transaction_id', '!=', '')
                                    ->latest('id')
                                    ->first();
                            }
                        } catch (\Throwable $e) {}

                        $lsDaysLeft = null;
                        $lsIsExpiringSoon = false;
                        try {
                            if (!empty($currPackage) && !empty($currPackage->expire_date)) {
                                $expDate = \Carbon\Carbon::parse($currPackage->expire_date);
                                $now = \Carbon\Carbon::now();
                                $diffDays = $now->diffInDays($expDate, false);
                                if ($diffDays >= 0 && $diffDays <= 30) {
                                    $lsDaysLeft = max(1, (int)ceil($diffDays));
                                    $lsIsExpiringSoon = true;
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

                    @if($lsIsExpiringSoon)
                        <div class="alert alert-danger d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm" style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 12px; color: #991b1b;">
                            <div class="d-flex align-items-center gap-3">
                                <div style="font-size: 22px; color: #dc2626;"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                <div>
                                    <div class="fw-bold" style="font-size: 14.5px;">Warning: Your package will expire in {{ $lsDaysLeft }} day(s) on {{ \Carbon\Carbon::parse($currPackage->expire_date)->format('M d, Y') }}.</div>
                                    <div class="small" style="color: #b91c1c;">Please renew your package now to avoid store suspension.</div>
                                </div>
                            </div>
                            <a href="{{ route('user.plan.extend.index') }}" class="btn btn-sm btn-danger px-3 py-2 fw-bold rounded-pill text-white" style="font-size: 12px; text-decoration: none;">Renew Now</a>
                        </div>
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
