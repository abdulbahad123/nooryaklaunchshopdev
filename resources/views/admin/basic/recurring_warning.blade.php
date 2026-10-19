@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Payment Recurring Warning') }}</h4>
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
        <a href="#">{{ __('Payment Recurring Warning') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.recurring-warning.update') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="card-title">{{ __('WhiteLabel Package Expiry Alert Schedules') }}</div>
            <p class="text-muted small mb-0">{{ __('Configure automated warning alert thresholds before WhiteLabel client packages expire.') }}</p>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-8 offset-lg-2">
                <div class="row mb-4">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('First Warning (Days Before)') }}</label>
                      <input type="number" class="form-control" name="recurring_warning_first_days" value="{{ $basic->recurring_warning_first_days ?? 30 }}" min="1" max="365">
                      <small class="text-muted">{{ __('Default: 30 days before') }}</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Second Warning (Days Before)') }}</label>
                      <input type="number" class="form-control" name="recurring_warning_second_days" value="{{ $basic->recurring_warning_second_days ?? 7 }}" min="1" max="365">
                      <small class="text-muted">{{ __('Default: 7 days (1 week)') }}</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Daily Alert (Days Before)') }}</label>
                      <input type="number" class="form-control" name="recurring_warning_daily_days" value="{{ $basic->recurring_warning_daily_days ?? 3 }}" min="1" max="365">
                      <small class="text-muted">{{ __('Persistent daily countdown') }}</small>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-4">
                  <label>{{ __('Custom Warning Banner Message') }}</label>
                  <textarea class="form-control" name="recurring_warning_message" rows="3" placeholder="Warning: Your package will expire in {days} day(s) on {date}. Please renew now.">{{ $basic->recurring_warning_message ?? '' }}</textarea>
                  <small class="text-muted">{{ __('Available placeholders: {days}, {date}') }}</small>
                </div>

                <div class="form-group mb-4">
                  <label>{{ __('Warning Notice Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="checkbox" name="recurring_warning_status" value="1" class="selectgroup-input" {{ ($basic->recurring_warning_status ?? 1) == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable Expiry Warning Notices for WhiteLabel Clients') }}</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update Settings') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection