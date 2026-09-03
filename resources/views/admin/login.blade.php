<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <title>{{ $bs->website_title }}</title>
  <link rel="icon" href="{{ asset('assets/front/img/' . $bs->favicon) }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/login.css') }}">
</head>

<body>
  <div class="login-page">
    <div class="text-center mb-4">
      <img class="login-logo" src="{{ asset('assets/front/img/' . $bs->logo) }}" alt="">
    </div>
    <div class="form">
      @if (session()->has('alert'))
        <div class="alert alert-danger fade show" role="alert">
          <strong>{{ __('Oops') . '!' }}</strong> {{ session('alert') }}
        </div>
      @endif
      <form class="login-form" action="{{ route('admin.auth') }}" method="POST">
        @csrf
        <input type="text" name="username" placeholder="{{ __('username') }}"/>
        @if ($errors->has('username'))
          <p class="text-danger text-left">{{ $errors->first('username') }}</p>
        @endif
        <input type="password" name="password" placeholder="{{ __('password') }}"/>
        @if ($errors->has('password'))
          <p class="text-danger text-left">{{ $errors->first('password') }}</p>
        @endif
        <button type="submit">{{ __('login') }}</button>
      </form>

      <div class="mt-3 text-center">
        <div class="divider text-muted mb-3" style="font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
          <span style="flex:1; height:1px; background:#e2e8f0;"></span>
          <span>{{ __('OR QUICK ACCESS') }}</span>
          <span style="flex:1; height:1px; background:#e2e8f0;"></span>
        </div>
        <form action="{{ route('admin.quick_autologin') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-block text-white font-weight-bold shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border: none; border-radius: 6px; padding: 12px; font-size: 0.95rem; cursor: pointer; transition: all 0.2s ease-in-out;">
            <i class="fas fa-bolt mr-2"></i> {{ __('Auto Login as Admin') }}
          </button>
        </form>
      </div>

      <div class="mt-3 text-center">
        <a class="forget-link" href="{{ route('admin.forget.form') }}">{{ __('Forgot Password / Username') . '?' }}</a>
      </div>
    </div>
  </div>


  <!-- jquery js -->
  <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
  <!-- popper js -->
  <script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
  <!-- bootstrap js -->
  <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
  <!-- Bootstrap Notify -->
  <script src="{{ asset('assets/admin/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

</body>

</html>
