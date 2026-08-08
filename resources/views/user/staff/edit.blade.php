@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Staff Member') }}</h4>
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
        <a href="{{ route('user.staff.index') }}">{{ __('Manage Staff') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Staff') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Staff Member Details') }}</div>
          <a class="btn btn-info btn-sm float-right" href="{{ route('user.staff.index') }}">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <form id="ajaxForm" action="{{ route('user.staff.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="staff_id" value="{{ $staff->id }}">

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('First Name') }} *</label>
                      <input type="text" class="form-control" name="first_name" value="{{ $staff->first_name }}" placeholder="{{ __('Enter first name') }}">
                      <p id="errfirst_name" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Last Name') }} *</label>
                      <input type="text" class="form-control" name="last_name" value="{{ $staff->last_name }}" placeholder="{{ __('Enter last name') }}">
                      <p id="errlast_name" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Username') }} *</label>
                      <input type="text" class="form-control" name="username" value="{{ $staff->username }}" placeholder="{{ __('Enter username') }}">
                      <p id="errusername" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Email Address') }} *</label>
                      <input type="email" class="form-control" name="email" value="{{ $staff->email }}" placeholder="{{ __('Enter email address') }}">
                      <p id="erremail" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('New Password') }} <small class="text-muted">({{ __('Leave blank to keep current') }})</small></label>
                      <input type="password" class="form-control" name="password" placeholder="{{ __('Enter new password') }}">
                      <p id="errpassword" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Confirm New Password') }}</label>
                      <input type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm new password') }}">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Assign Role') }} *</label>
                      <select name="role_id" class="form-control">
                        <option value="" disabled>{{ __('Select a Role') }}</option>
                        @foreach ($roles as $role)
                          <option value="{{ $role->id }}" {{ $staff->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                      </select>
                      <p id="errrole_id" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Phone Number') }}</label>
                      <input type="text" class="form-control" name="phone" value="{{ $staff->phone }}" placeholder="{{ __('Enter phone number') }}">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Profile Image') }}</label>
                  <input type="file" class="form-control-file" name="image">
                  @if (!empty($staff->image))
                    <div class="mt-2">
                      <img src="{{ asset('assets/front/img/user/' . $staff->image) }}" alt="" width="80" class="rounded">
                    </div>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer text-center">
          <button id="submitBtn" type="button" class="btn btn-success">{{ __('Update Staff') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
