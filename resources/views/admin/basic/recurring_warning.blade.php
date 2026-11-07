@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Expire Alert') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Expire Alert') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.recurring-warning.update') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="card-title">{{ __('Subscription Expire Alert Settings') }}</div>
            <p class="text-muted small mb-0">{{ __('Configure automated subscription plan expiration warnings and reminders for monthly and yearly plans.') }}</p>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="form-group mb-4">
                  <label class="font-weight-bold">{{ __('Recurring Expiry Alert (Days Before)') }}</label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="recurring_warning_first_days" value="{{ $basic->recurring_warning_first_days ?? 7 }}" min="1" max="365" required>
                    <div class="input-group-append">
                      <span class="input-group-text font-weight-bold">{{ __('Days Before Expiry') }}</span>
                    </div>
                  </div>
                  <small class="text-muted d-block mt-2">{{ __('Enter number of days before expiry (e.g. 7 or 30 days) to trigger subscription renewal notices.') }}</small>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success px-5 font-weight-bold">
                  <i class="fas fa-save mr-1"></i> {{ __('Save Expire Alert Settings') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection