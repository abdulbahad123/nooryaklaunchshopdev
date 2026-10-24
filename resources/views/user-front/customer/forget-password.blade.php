@extends('user-front.layout')

@section('meta-description', !empty($seo) ? $seo->forget_password_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->forget_password_meta_keywords : '')

@section('breadcrumb_title', $pageHeading->forget_password_page ?? __('Forgot password'))
@section('page-title', $pageHeading->forget_password_page ?? __('Forgot password'))

@section('content')
  <!--======FORGET PASSWORD PART START ======-->
  <div class="authentication-area ptb-100">
    <div class="container">

      <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">
          <div class="auth-form p-30 border radius-md">
            @if (Session::has('error'))
              <div class="alert alert-danger text-danger">{{ Session::get('error') }}</div>
            @endif
            @if (Session::has('success'))
              <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            <form id="#authForm" action="{{ route('customer.send_forget_password_mail', getParam()) }}" method="POST">
              @csrf
              <input type="hidden" name="user_id" value="{{ $user->id }}">
              <div class="title">
                <h3 class="mb-20">{{ $keywords['Forgot Password'] ?? __('Forgot Password') }}</h3>
              </div>
              <div class="form-group mb-30">
                <input type="text"
                  placeholder="{{ $keywords['Email_Address_or_Phone'] ?? __('Email Address or Phone Number') }}*"
                  class="form-control" name="email" value="{{ old('email') }}" required>
                @error('email')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>



              <button type="submit" class="btn btn-lg btn-primary radius-md w-100">
                {{ $keywords['Proceed'] ?? __('Proceed') }}
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!--====== FORGET PASSWORD PART ENDS ======-->
@endsection
