@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Push Notification Settings') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('user-dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Push Notification') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">

        <div class="card-header">
          <div class="card-title">{{ __('Push Notification Settings') }}</div>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="pushSettingsForm" action="{{ route('user.pushnotification.updateSettings') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <div class="col-12 mb-2 pl-0 pr-0">
                    <label for="image"><strong>{{ __('Icon Image') }} <span
                          class="text-danger">**</span></strong></label>
                  </div>
                  @php
                    $user_id = Auth::user()->id;
                    $filename = 'push_icon_' . $user_id . '.png';
                    $userIcon = 'assets/front/img/user/' . $filename;
                    $iconUrl = file_exists(public_path($userIcon)) ? asset($userIcon) : asset('assets/front/img/pushnotification_icon.png');
                  @endphp
                  <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                    <img src="{{ $iconUrl . '?' . time() }}"
                      alt="..." class="img-thumbnail" style="max-width: 120px;">
                  </div><br>
                  <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                    {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="file">
                  </div>

                  @if ($errors->has('file'))
                    <p class="mb-0 text-danger em">{{ $errors->first('file') }}</p>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="form-group from-show-notify row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success" form="pushSettingsForm">{{ __('Update') }}</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
