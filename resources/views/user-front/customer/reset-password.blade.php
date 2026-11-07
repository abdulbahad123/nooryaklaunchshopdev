@extends('user-front.layout')

@section('breadcrumb_title', $keywords['Reset password'] ?? __('Reset password'))
@section('page-title', $keywords['Reset password'] ?? __('Reset password'))

@section('content')
  <div class="authentication-area ptb-80">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">
          <div class="modern-auth-card">
            @if (Session::has('error'))
              <div class="alert alert-danger modern-alert text-danger mb-4">
                <i class="fal fa-exclamation-circle mr-2"></i> {{ Session::get('error') }}
              </div>
            @endif

            <div class="auth-card-header mb-4 text-center">
              <div class="auth-icon-circle mx-auto mb-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 2l-2 2m-1-1l-3 3m-2-2l-3 3m-2-2l-3 3m-2-2l-3 3"></path>
                  <circle cx="7" cy="17" r="5"></circle>
                </svg>
              </div>
              <h3 class="auth-card-title mb-1">{{ $keywords['Reset password'] ?? __('Reset Password') }}</h3>
              <p class="auth-card-subtitle mb-0 text-muted">{{ __('Create a new secure password for your account') }}</p>
            </div>

            <form id="authForm" action="{{ route('customer.reset_password_submit', getParam()) }}" method="POST">
              @csrf
              <input type="hidden" name="user_id" value="{{ $user->id }}">
              <input type="hidden" name="token" value="{{ request()->input('token') }}">

              <!-- New Password -->
              <div class="form-group modern-input-group mb-3">
                <label class="modern-input-label">{{ $keywords['New Password'] ?? __('New Password') }} *</label>
                <div class="auth-input-wrapper">
                  <span class="auth-input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                  </span>
                  <input type="password" id="resetNewPass" class="auth-input-field" name="new_password"
                    placeholder="{{ $keywords['New Password'] ?? __('New Password') }}" value="{{ old('new_password') }}" required autocomplete="new-password">
                  <button type="button" class="btn-auth-eye" onclick="togglePasswordVisibility('resetNewPass', 'resetNewPassEye')" aria-label="Toggle Password Visibility">
                    <span id="resetNewPassEye">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                    </span>
                  </button>
                </div>
                @error('new_password')
                  <p class="text-danger small mt-1 mb-0">{{ $message }}</p>
                @enderror
              </div>

              <!-- Confirm New Password -->
              <div class="form-group modern-input-group mb-4">
                <label class="modern-input-label">{{ $keywords['Confirm New Password'] ?? __('Confirm New Password') }} *</label>
                <div class="auth-input-wrapper">
                  <span class="auth-input-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                  </span>
                  <input type="password" id="resetConfirmPass" class="auth-input-field" name="new_password_confirmation"
                    placeholder="{{ $keywords['Confirm New Password'] ?? __('Confirm New Password') }}" value="{{ old('new_password_confirmation') }}" required autocomplete="new-password">
                  <button type="button" class="btn-auth-eye" onclick="togglePasswordVisibility('resetConfirmPass', 'resetConfirmPassEye')" aria-label="Toggle Password Visibility">
                    <span id="resetConfirmPassEye">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                    </span>
                  </button>
                </div>
                @error('new_password_confirmation')
                  <p class="text-danger small mt-1 mb-0">{{ $message }}</p>
                @enderror
              </div>

              <button type="submit" class="btn-modern-auth w-100 mb-2">
                <span>{{ $keywords['Update'] ?? __('Update Password') }}</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .modern-auth-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      padding: 36px 30px;
      box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .auth-icon-circle {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: rgba(15, 23, 42, 0.05);
      color: #0f172a;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .auth-card-title {
      font-size: 22px;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: -0.3px;
    }
    .auth-card-subtitle {
      font-size: 13px;
      color: #64748b;
    }
    .modern-input-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: #334155;
      margin-bottom: 6px;
      text-align: left;
    }
    .auth-input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      width: 100%;
    }
    .auth-input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      display: flex;
      align-items: center;
      pointer-events: none;
      z-index: 2;
    }
    .auth-input-field {
      width: 100%;
      height: 48px;
      padding: 10px 44px 10px 42px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      font-size: 14px;
      color: #0f172a;
      background: #f8fafc;
      transition: all 0.2s ease;
      outline: none;
    }
    .auth-input-field:focus {
      background: #ffffff;
      border-color: #0f172a;
      box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
    }
    .btn-auth-eye {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      padding: 6px 8px;
      color: #94a3b8;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      transition: color 0.2s ease;
      z-index: 3;
    }
    .btn-auth-eye:hover {
      color: #0f172a;
    }
    .btn-modern-auth {
      height: 48px;
      border-radius: 10px;
      background: #0f172a;
      color: #ffffff;
      font-size: 14px;
      font-weight: 700;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 8px 18px -4px rgba(15, 23, 42, 0.25);
      transition: all 0.2s ease;
    }
    .btn-modern-auth:hover {
      background: #1e293b;
      color: #ffffff;
      transform: translateY(-1px);
    }
  </style>

  <script>
    function togglePasswordVisibility(inputId, iconContainerId) {
      var input = document.getElementById(inputId);
      var container = document.getElementById(iconContainerId);
      if (!input) return;

      var eyeSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
      var eyeSlashSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';

      if (input.type === 'password') {
        input.type = 'text';
        if (container) container.innerHTML = eyeSlashSvg;
      } else {
        input.type = 'password';
        if (container) container.innerHTML = eyeSvg;
      }
    }
  </script>
@endsection
