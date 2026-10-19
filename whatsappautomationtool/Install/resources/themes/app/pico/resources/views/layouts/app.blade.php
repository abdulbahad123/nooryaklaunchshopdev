@php
    $optionSidebarSmall = get_option('backend_sidebar_type', 1);
    $hasSidebarSmall = UserInfo::getDataUser("sidebar-small", $optionSidebarSmall);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  dir="{{ Language::getCurrent('dir') }}" class="{{ $hasSidebarSmall ? 'sidebar-small' : ($optionSidebarSmall == 1 ? 'sidebar-small' : '') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="{{ get_option("website_keyword", config('site.keywords')) }}">
    <meta name="description" content="{{ get_option("website_description", config('site.description')) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ url( get_option("website_favicon", asset('public/img/favicon.png')) ) }}">
    <title>
        @hasSection('pagetitle')
            @yield('pagetitle')
        @else
            {{ (__(module("name")) ?? '' ) . " | " . get_option("website_title", config('site.title')) }}
        @endif
    </title>

    @yield('css')
    {!! Script::renderCss() !!}
    {!! Script::globals() !!}
    <link rel="stylesheet" href="{{ theme_public_asset('css/main.css') }}?version=9.0.3">
    @yield('head_embed_code')
</head>
<body>
    <div class="loading">
        <div class="d-flex justify-content-center align-items-center hp-100">
            <div class="loader"></div>
        </div>
    </div>

    <div id="drag-overlay">
        <div class="overlay-box">
            <i class="fas fa-cloud-upload-alt"></i>
            <h2 class="mb-2">{{ __('Drag & Drop your files here') }}</h2>
            <p class="mb-0 text-muted">
                {{ __('Supported formats:') }}
                <span class="text-uppercase">
                    {{ get_option('file_allowed_file_types', 'jpeg,gif,png,jpg,webp,mp4,csv,pdf,mp3,wmv,json') }}
                </span>
            </p>
        </div>
    </div>

    @include('partials.header')
    @include('partials.sidebar')
    
    <div class="main">
        @php
            $pendingManualPay = null;
            try {
                $waUid = Auth::id();
                if ($waUid && class_exists('\Modules\AdminManualPayments\Models\PaymentManual')) {
                    $pendingManualPay = \Modules\AdminManualPayments\Models\PaymentManual::where('uid', $waUid)
                        ->where('status', 0)
                        ->latest()
                        ->first();
                }
            } catch (\Throwable $e) {}

            $waDaysLeft = null;
            $waIsExpiringSoon = false;
            try {
                $team = \Modules\AdminUsers\Models\Teams::where('owner', Auth::id())->first();
                if ($team && !empty($team->expiration_date)) {
                    $expDate = \Carbon\Carbon::parse($team->expiration_date);
                    $now = \Carbon\Carbon::now();
                    $diffDays = $now->diffInDays($expDate, false);
                    if ($diffDays >= 0 && $diffDays <= 2) {
                        $waDaysLeft = max(1, (int)ceil($diffDays));
                        $waIsExpiringSoon = true;
                    }
                }
            } catch (\Throwable $e) {}
        @endphp

        @if($pendingManualPay && !empty($pendingManualPay->payment_id))
            <div class="container-fluid pt-3">
                <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 mb-3 shadow-sm" style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; color: #92400e;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 22px; color: #d97706;"><i class="fal fa-clock"></i></div>
                        <div>
                            <div class="fw-bold" style="font-size: 14.5px;">Notice: Your UPI payment (UTR: <code style="background:#fef3c7; color:#92400e; padding:2px 7px; border-radius:4px; font-weight:700;">{{ $pendingManualPay->payment_id }}</code>) is pending for admin verification.</div>
                            <div class="small" style="color: #b45309;">Your workspace features remain fully active while our admin team confirms your transaction.</div>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px;">Pending Verification</span>
                </div>
            </div>
        @endif

        @if($waIsExpiringSoon)
            <div class="container-fluid pt-2">
                <div class="alert alert-danger d-flex align-items-center justify-content-between p-3 mb-3 shadow-sm" style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 12px; color: #991b1b;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 22px; color: #dc2626;"><i class="fal fa-exclamation-triangle"></i></div>
                        <div>
                            <div class="fw-bold" style="font-size: 14.5px;">Warning: Your plan will expire in {{ $waDaysLeft }} day(s) on {{ \Carbon\Carbon::parse($team->expiration_date)->format('M d, Y') }}.</div>
                            <div class="small" style="color: #b91c1c;">Please renew your subscription now to avoid campaign interruption.</div>
                        </div>
                    </div>
                    <a href="{{ url('pricing') }}" class="btn btn-sm btn-danger px-3 py-2 fw-bold rounded-pill text-white" style="font-size: 12px; text-decoration: none;">Renew Plan</a>
                </div>
            </div>
        @endif

         @if(View::hasSection('sub_header') && View::hasSection('content') && View::hasSection('form'))
            @php
                // Decode the JSON attributes if provided
                $attributes = @json_decode( htmlspecialchars_decode( View::yieldContent('form') ), true) ?? [];
                // Convert to string format: key="value"
                $attributesString = collect($attributes)->map(function ($value, $key) {
                    return $key . '="' . e($value) . '"';
                })->implode(' ');
            @endphp

            <form {!! $attributesString !!}>
                @csrf

                @hasSection('sub_header')
                    <div class="border-bottom mb-5 bg-polygon">
                        <div class="container">
                            <div class="pt-4 pb-4">
                                @yield('sub_header')
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </form>
        @else
            @hasSection('sub_header')
                <div class="border-bottom mb-5 bg-polygon">
                    <div class="container">
                        <div class="pt-4 pb-4">
                            @yield('sub_header')
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        @endif
    </div>

    @include('partials.footer')

    <!-- FOOTER END -->
    <script type="text/javascript" src="{{ theme_public_asset('plugins/highcharts/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/highcharts/modules/exporting.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/highcharts/modules/map.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/codemirror5/lib/codemirror.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/codemirror5/mode/htmlmixed/htmlmixed.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/codemirror5/mode/php/php.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/codemirror5/mode/css/css.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/codemirror5/mode/javascript/javascript.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/codemirror5/mode/xml/xml.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/izitoast/js/iziToast.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/lodash/lodash.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/emojionearea/emojionearea.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/datetimepicker/timepicker-addon.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/ion.rangeSlider/js/ion.rangeSlider.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/inputTags/inputTags.jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/lazysizes/lazysizes.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('plugins/fullcalendar/index.global.min.js') }}"></script>
    <script type="text/javascript" src="{{ theme_public_asset('js/main.js') }}?version=9.0.3"></script>
    @yield('script')
    {!! Script::renderJs() !!}
    {!! Script::renderRaw() !!}
</body>
</html>