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
        <div style="position: relative; display: flex; align-items: center; width: 100%;">
          <input type="password" id="adminPass" name="password" placeholder="{{ __('password') }}" style="width: 100%; padding-right: 44px;"/>
          <button type="button" onclick="toggleAdminPass()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 4px 8px; cursor: pointer; color: #888;">
            <span id="adminEye">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </span>
          </button>
        </div>
        @if ($errors->has('password'))
          <p class="text-danger text-left">{{ $errors->first('password') }}</p>
        @endif
        <button type="submit">{{ __('login') }}</button>
      </form>
      <a class="forget-link" href="{{ route('admin.forget.form') }}">{{ __('Forgot Password / Username') . '?' }}</a>
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
  <script>
    function toggleAdminPass() {
      var p = document.getElementById('adminPass');
      var e = document.getElementById('adminEye');
      if (!p) return;
      if (p.type === 'password') {
        p.type = 'text';
        if (e) e.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
      } else {
        p.type = 'password';
        if (e) e.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
      }
    }
  </script>

</body>

</html>
