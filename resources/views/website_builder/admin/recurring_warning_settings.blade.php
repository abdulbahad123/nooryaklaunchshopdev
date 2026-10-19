@extends('website_builder.admin.layout')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">
      <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i> Payment Recurring Warning Settings
    </h3>
    <p class="text-muted small mb-0">Configure automated subscription expiration warnings and daily alert schedules for WhiteLabel Agency clients.</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="fa-solid fa-circle-check fs-5"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.admin.recurring-warning.update') }}" method="POST">
  @csrf
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card p-4 shadow-sm border-0 rounded-4 mb-4">
        <h5 class="fw-bold mb-3 border-bottom pb-2">
          <i class="fa-solid fa-bell me-2 text-primary"></i> Expiry Alert Schedules
        </h5>
        <p class="text-muted small mb-4">Set the threshold days when WhiteLabel agencies will be notified and shown the header warning banner before their SaaS package expires.</p>

        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label fw-semibold">First Warning (Early Notice)</label>
            <div class="input-group">
              <input type="number" name="recurring_warning_first_days" class="form-control" value="{{ old('recurring_warning_first_days', $settings->recurring_warning_first_days ?? 30) }}" min="1" max="365">
              <span class="input-group-text bg-light">Days Before</span>
            </div>
            <small class="text-muted">Default: 30 days before expiry.</small>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Second Warning (Urgent)</label>
            <div class="input-group">
              <input type="number" name="recurring_warning_second_days" class="form-control" value="{{ old('recurring_warning_second_days', $settings->recurring_warning_second_days ?? 7) }}" min="1" max="365">
              <span class="input-group-text bg-light">Days Before</span>
            </div>
            <small class="text-muted">Default: 7 days (1 week) before.</small>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Daily Alert (Critical)</label>
            <div class="input-group">
              <input type="number" name="recurring_warning_daily_days" class="form-control" value="{{ old('recurring_warning_daily_days', $settings->recurring_warning_daily_days ?? 3) }}" min="1" max="365">
              <span class="input-group-text bg-light">Days Before</span>
            </div>
            <small class="text-muted">Triggers persistent daily alert.</small>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Custom Warning Banner Message</label>
          <textarea name="recurring_warning_message" class="form-control" rows="3" placeholder="Warning: Your subscription will expire in {days} day(s) on {date}. Please renew your package now to avoid service interruption.">{{ old('recurring_warning_message', $settings->recurring_warning_message ?? '') }}</textarea>
          <small class="text-muted">Use <code>{days}</code> and <code>{date}</code> as dynamic placeholders.</small>
        </div>

        <div class="mb-2">
          <label class="form-label fw-semibold d-block">Warning System Status</label>
          <div class="form-check form-switch fs-5">
            <input class="form-check-input" type="checkbox" name="recurring_warning_status" value="1" id="warningStatusSwitch" {{ ($settings->recurring_warning_status ?? 1) == 1 ? 'checked' : '' }}>
            <label class="form-check-label fs-6 fw-normal" for="warningStatusSwitch">Enable recurring expiry warnings for WhiteLabel agencies</label>
          </div>
        </div>

        <div class="mt-4 pt-3 border-top text-end">
          <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold" style="background: linear-gradient(135deg, #22c55e 0%, #15803d 100%); border:none;">
            <i class="fa-solid fa-save me-1"></i> Save Expiry Alert Settings
          </button>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card p-4 shadow-sm border-0 rounded-4 bg-light">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-info me-2"></i> How It Works</h6>
        <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
          <li>When a WhiteLabel agency client's package is within the configured warning window (e.g., 30 days, 7 days), a warning notice banner automatically appears at the top of their dashboard.</li>
          <li>In the final critical days (Daily Alert threshold), the banner transitions to a high-urgency state urging immediate renewal.</li>
          <li>Agency clients can click the renewal link directly from the banner to renew their package.</li>
        </ul>
      </div>
    </div>
  </div>
</form>
@endsection