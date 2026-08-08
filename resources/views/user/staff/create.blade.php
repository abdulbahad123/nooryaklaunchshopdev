@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Staff Member') }}</h4>
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
        <a href="#">{{ __('Add Staff') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Create Staff Member Account') }}</div>
          <a class="btn btn-info btn-sm float-right" href="{{ route('user.staff.index') }}">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <form id="ajaxForm" action="{{ route('user.staff.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('First Name') }} *</label>
                      <input type="text" class="form-control" name="first_name" placeholder="{{ __('Enter first name') }}">
                      <p id="errfirst_name" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Last Name') }} *</label>
                      <input type="text" class="form-control" name="last_name" placeholder="{{ __('Enter last name') }}">
                      <p id="errlast_name" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Username') }} *</label>
                      <input type="text" class="form-control" name="username" placeholder="{{ __('Enter username for login') }}">
                      <p id="errusername" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Email Address') }} *</label>
                      <input type="email" class="form-control" name="email" placeholder="{{ __('Enter staff email address') }}">
                      <p id="erremail" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Password') }} *</label>
                      <input type="password" class="form-control" name="password" placeholder="{{ __('Enter password') }}">
                      <p id="errpassword" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Confirm Password') }} *</label>
                      <input type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm password') }}">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Assign Role') }} *</label>
                      <select name="role_id" class="form-control">
                        <option value="" selected disabled>{{ __('Select a Role') }}</option>
                        @foreach ($roles as $role)
                          <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                      </select>
                      <p id="errrole_id" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Phone Number') }}</label>
                      <input type="text" class="form-control" name="phone" placeholder="{{ __('Enter phone number') }}">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Profile Image') }}</label>
                  <input type="file" class="form-control-file" name="image">
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer text-center">
          <button id="submitBtn" type="button" class="btn btn-success">{{ __('Create Staff') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
