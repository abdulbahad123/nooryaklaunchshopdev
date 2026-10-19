@extends('website_builder.agency_template.admin.layout')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">
      <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i> Client Payment Recurring Warning
    </h3>
    <p class="text-muted small mb-0">Configure automated subscription expiry warnings and reminder schedules for your registered clients across all products.</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="fa-solid fa-circle-check fs-5"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.recurring-warning.update') }}" method="POST">
  @csrf
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card p-4 shadow-sm border-0 rounded-4 mb-4">
        <h5 class="fw-bold mb-3 border-bottom pb-2">
          <i class="fa-solid fa-bell me-2 text-success"></i> Registered Client Expiry Alert Thresholds
        </h5>
        <p class="text-muted small mb-4">Set the alert days before a registered client's website package expires to trigger warning banners and notifications.</p>

        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label fw-semibold">First Warning (Advance Notice)</label>
            <div class="input-group">
              <input type="number" name="client_warning_first_days" class="form-control" value="{{ old('client_warning_first_days', $agency->client_warning_first_days ?? 30) }}" min="1" max="365">
              <span class="input-group-text bg-light">Days Before</span>
            </div>
            <small class="text-muted">Default: 30 days before.</small>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Second Warning (Urgent Alert)</label>
            <div class="input-group">
              <input type="number" name="client_warning_second_days" class="form-control" value="{{ old('client_warning_second_days', $agency->client_warning_second_days ?? 7) }}" min="1" max="365">
              <span class="input-group-text bg-light">Days Before</span>
            </div>
            <small class="text-muted">Default: 7 days (1 week) before.</small>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Daily Alert (Critical Countdown)</label>
            <div class="input-group">
              <input type="number" name="client_warning_daily_days" class="form-control" value="{{ old('client_warning_daily_days', $agency->client_warning_daily_days ?? 3) }}" min="1" max="365">
              <span class="input-group-text bg-light">Days Before</span>
            </div>
            <small class="text-muted">Persistent daily alert.</small>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Custom Notice Message for Clients</label>
          <textarea name="client_warning_message" class="form-control" rows="3" placeholder="Warning: Your website package will expire in {days} day(s) on {date}. Please renew now to maintain live website service.">{{ old('client_warning_message', $agency->client_warning_message ?? '') }}</textarea>
          <small class="text-muted">Use <code>{days}</code> and <code>{date}</code> as dynamic placeholders.</small>
        </div>

        <div class="mb-2">
          <label class="form-label fw-semibold d-block">Client Expiry Warning Status</label>
          <div class="form-check form-switch fs-5">
            <input class="form-check-input" type="checkbox" name="client_warning_status" value="1" id="clientWarningStatusSwitch" {{ ($agency->client_warning_status ?? 1) == 1 ? 'checked' : '' }}>
            <label class="form-check-label fs-6 fw-normal" for="clientWarningStatusSwitch">Enable expiry warning notices for registered clients</label>
          </div>
        </div>

        <div class="mt-4 pt-3 border-top text-end">
          <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-bold" style="background: linear-gradient(135deg, #22c55e 0%, #15803d 100%); border:none;">
            <i class="fa-solid fa-save me-1"></i> Save Client Expiry Alert Settings
          </button>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card p-4 shadow-sm border-0 rounded-4 bg-light">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-success me-2"></i> WhiteLabel Overview</h6>
        <p class="text-muted small mb-0" style="line-height: 1.8;">
          Configure automated warnings for your end-clients across all products. Clients with upcoming expiry dates will see warning notices on their client panel with direct links to renew their package.
        </p>
      </div>
    </div>
  </div>
</form>
@endsection