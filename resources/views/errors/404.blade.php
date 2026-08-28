@php
  $layoutDirectory = null;
  $pageTitle = App\Models\Admin\Heading::where('language_id', $currentLang->id)->pluck('not_found_title')->first();
  $user = App\Models\User::where('username', getParam())->first();
  $userCurrentLang = null;
  if ($user) {
    $userCurrentLang = app('userCurrentLang');
    if (empty($userCurrentLang)) {
      $userCurrentLang = App\Models\User\Language::where('user_id', $user->id)->orderBy('id', 'asc')->first();
    }

      if ($userCurrentLang) {
          $pageTitle = App\Models\User\UserHeading::where([
              ['language_id', $userCurrentLang->id],
              ['user_id', $user->id],
          ])
              ->pluck('not_found_page')
              ->first();
      }
  }
  $layoutDirectory = !is_null($user) && !empty($userCurrentLang) ? 'user-front.layout' : 'front.layout';
  $breadcrumb_title = !is_null($user) ? 'breadcrumb_title' : 'breadcrumb-title';
  $breadcrumb_link = !is_null($user) ? 'page-title' : 'breadcrumb-link';
@endphp
@extends($layoutDirectory)

@section('pagename')
  - {{ $pageTitle ?? __('404') }}
@endsection

@section($breadcrumb_title)
  {{ $pageTitle ?? __('404') }}
@endsection

@section($breadcrumb_link)
  {{ $pageTitle ?? __('404') }}
@endsection

@section('content')
  <!--    Error section start   -->
  <div class="error-section pt-100 pb-100 pb-90 pt-90">
    <div class="container">
      <div class="row align-items-center justify-content-center">
        <div class="col-lg-8">
          <div class="not-found text-center mb-30">
            @if ($layoutDirectory == 'user-front.layout')
              @php
                $user = app('user');
              $data = null;
              $image = null;
              if ($user && !empty($userCurrentLang)) {
                $data = DB::table('user_basic_extendes')
                  ->where([['user_id', $user->id], ['language_id', $userCurrentLang->id]])
                  ->select('user_not_found_title', 'user_not_found_subtitle')
                  ->first();
                $image = DB::table('user_basic_settings')
                  ->where('user_id', $user->id)
                  ->pluck('page_not_found_image')
                  ->first();
              }
              @endphp
              @if (!is_null($image))
                <img src="{{ asset('assets/user-front/images/' . @$image) }}" alt="">
              @else
                <img src="{{ asset('assets/front/img/404.png') }}" alt="">
              @endif
            @else
              <img src="{{ asset('assets/front/img/404.png') }}" alt="">
            @endif
          </div>

          <div class="error-txt text-center mb-20">
            @if ($layoutDirectory == 'user-front.layout')
              @php
                $keywords = App\Http\Helpers\Common::get_keywords($user->id);
              @endphp
              <h2>{{ $data->user_not_found_title ?? ($keywords['youare_lost'] ?? __("You're lost")) }}...</h2>
              <p>
                {{ $data->user_not_found_subtitle ?? ($keywords['The page you are looking for might have been moved, renamed, or might never have existed.'] ?? __('The page you are looking for might have been moved, renamed, or might never have existed.')) }}
              </p>

              <a href="{{ route('front.user.detail.view', getParam()) }}"
                class="btn btn-md btn-primary radius-sm">{{ $keywords['Back Home'] ?? __('Back Home') }}</a>
            @else
              @php
                if (session()->has('lang')) {
                    app()->setLocale(session()->get('lang'));
                } else {
                    $defaultLang = App\Models\Language::where('is_default', 1)->first();
                    if (!empty($defaultLang)) {
                        app()->setLocale($defaultLang->code);
                    }
                }
              @endphp
              <h2>{{ __("You're lost") }}...</h2>
              <p>{{ __('The page you are looking for might have been moved, renamed, or might never existed.') }}</p>
              <a href="{{ route('front.index') }}" class="btn btn-md btn-primary radius-sm">{{ __('Back Home') }}</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--    Error section end   -->

  @php
    $dbStatusInfo = 'Unknown';
    $activeDbName = 'Unknown';
    $usersCountInActiveDb = 0;
    try {
        DB::connection()->getPdo();
        $dbStatusInfo = '<span class="badge bg-success">CONNECTED & ONLINE</span>';
        $activeDbName = DB::connection()->getDatabaseName();
        $usersCountInActiveDb = DB::table('users')->count();
    } catch (\Throwable $dbe) {
        $dbStatusInfo = '<span class="badge bg-danger">DB ERROR: ' . e($dbe->getMessage()) . '</span>';
    }

    $paramVal = getParam();
    $foundUserObj = app()->bound('user') ? app('user') : null;
    $foundUserDb = null;
    $userLangCount = 0;
    $userCurrCount = 0;
    $hasBsSetting = 'NO';

    try {
        if ($foundUserObj) {
            $foundUserDb = $foundUserObj;
        } elseif (!empty($paramVal)) {
            $foundUserDb = App\Models\User::where('username', $paramVal)->first();
        }
        if ($foundUserDb) {
            $userLangCount = App\Models\User\Language::where('user_id', $foundUserDb->id)->count();
            $userCurrCount = App\Models\User\UserCurrency::where('user_id', $foundUserDb->id)->count();
            $hasBsSetting = App\Models\User\BasicSetting::where('user_id', $foundUserDb->id)->exists() ? 'YES' : 'NO';
        }
    } catch (\Throwable $uerr) {}
  @endphp

  @if (config('app.debug') || request()->has('debug') || true)
    <div class="container my-4 p-4 bg-dark text-white rounded shadow-lg" style="font-size: 13px; font-family: monospace; z-index: 9999; position: relative; border-left: 5px solid #ffc107;">
      <h5 class="text-warning mb-3">🐞 Live Database & System Diagnostics (404 Debug)</h5>
      
      <div class="row">
        <div class="col-md-6 mb-2">
          <p class="mb-1"><strong>🔌 Database Connection:</strong> {!! $dbStatusInfo !!}</p>
          <p class="mb-1"><strong>🗄️ Active Database Name:</strong> <code class="text-info">{{ $activeDbName }}</code></p>
          <p class="mb-1"><strong>👥 Total Users in Active DB:</strong> {{ $usersCountInActiveDb }}</p>
          <p class="mb-1"><strong>🌐 HTTP Host:</strong> {{ $_SERVER['HTTP_HOST'] ?? 'N/A' }}</p>
          <p class="mb-1"><strong>🔗 Request Path:</strong> {{ request()->path() }}</p>
          <p class="mb-1"><strong>🔍 URL Parameter (getParam):</strong> <code>{{ json_encode($paramVal) }}</code></p>
        </div>

        <div class="col-md-6 mb-2">
          <p class="mb-1"><strong>👤 Tenant User Status:</strong> 
            @if ($foundUserDb)
              <span class="text-success">FOUND (ID: {{ $foundUserDb->id }}, Username: {{ $foundUserDb->username }})</span>
            @else
              <span class="text-danger">NOT FOUND in Active Database</span>
            @endif
          </p>
          @if ($foundUserDb)
            <p class="mb-1"><strong>🌐 User Languages Count:</strong> {{ $userLangCount }}</p>
            <p class="mb-1"><strong>💱 User Currencies Count:</strong> {{ $userCurrCount }}</p>
            <p class="mb-1"><strong>⚙️ Basic Settings Record:</strong> {{ $hasBsSetting }}</p>
            <p class="mb-1"><strong>🎨 Preview Template:</strong> {{ $foundUserDb->preview_template ?? 0 }}</p>
          @endif
        </div>
      </div>

      @if (isset($exception) && $exception instanceof \Throwable)
        <hr class="border-secondary my-3">
        <div>
          <h6 class="text-warning mb-2">⚠️ Detailed Exception & Trace:</h6>
          <p class="mb-1 text-light"><strong>Exception Class:</strong> <code class="text-warning">{{ get_class($exception) }}</code></p>
          <p class="mb-1 text-light"><strong>Error Message:</strong> <span class="bg-danger text-white px-2 py-1 rounded">{{ $exception->getMessage() }}</span></p>
          <p class="mb-1 text-light"><strong>Triggered In:</strong> <code class="text-info">{{ $exception->getFile() }}:{{ $exception->getLine() }}</code></p>
          <p class="mb-1 text-light"><strong>Route Name:</strong> <code>{{ request()->route() ? request()->route()->getName() : 'N/A' }}</code></p>
          <p class="mb-1 text-light"><strong>Action Controller:</strong> <code>{{ request()->route() ? request()->route()->getActionName() : 'N/A' }}</code></p>
          
          <p class="mt-3 mb-1 text-warning"><strong>📜 Call Stack Trace:</strong></p>
          <pre class="bg-black text-warning p-3 rounded shadow-inner" style="max-height: 400px; overflow-y: auto; font-size: 11px; white-space: pre-wrap; word-break: break-all; border: 1px solid #444;">{{ $exception->getTraceAsString() }}</pre>
        </div>
      @endif
    </div>
  @endif
@endsection

