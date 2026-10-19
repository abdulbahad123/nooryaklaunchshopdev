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
            <p class="text-muted small mb-0">{{ __('Configure automated subscription plan expiration warnings and daily WhatsApp reminders for monthly and yearly plans.') }}</p>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-8 offset-lg-2">
                <div class="row mb-4">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Recurring Expiry Alert (Days Before)') }}</label>
                      <input type="number" class="form-control" name="recurring_warning_first_days" value="{{ $basic->recurring_warning_first_days ?? 7 }}" min="1" max="365">
                      <small class="text-muted">{{ __('Example: Enter 7 for 1 week before expiry. Daily alerts run every day during this period.') }}</small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Daily Alert (Critical Countdown Days)') }}</label>
                      <input type="number" class="form-control" name="recurring_warning_daily_days" value="{{ $basic->recurring_warning_daily_days ?? 3 }}" min="1" max="365">
                      <small class="text-muted">{{ __('High-urgency countdown state (Default: 3 days before)') }}</small>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-4">
                  <label>{{ __('Custom Warning Banner & WhatsApp Message') }}</label>
                  <textarea class="form-control" name="recurring_warning_message" rows="3" placeholder="Warning: Your subscription will expire in {days} day(s) on {date}. Renew your plan now: {link}">{{ $basic->recurring_warning_message ?? '' }}</textarea>
                  <small class="text-muted">{{ __('Available dynamic placeholders: {days}, {date}, and {link} (direct renewal link)') }}</small>
                </div>

                <div class="form-group mb-4">
                  <label>{{ __('Alert System Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="checkbox" name="recurring_warning_status" value="1" class="selectgroup-input" {{ ($basic->recurring_warning_status ?? 1) == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable Automated Daily WhatsApp Reminders & Closable Dashboard Notices') }}</span>
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
                  {{ __('Save Expire Alert Settings') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection