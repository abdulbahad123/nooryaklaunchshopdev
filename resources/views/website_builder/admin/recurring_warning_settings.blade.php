@extends('website_builder.admin.layout')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">
      <i class="fa-solid fa-bell-exclamation me-2 text-warning"></i> Expire Alert Settings
    </h3>
    <p class="text-muted small mb-0">Configure automated subscription plan expiration warnings and daily WhatsApp reminders for monthly and yearly plans.</p>
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
          <i class="fa-solid fa-clock me-2 text-primary"></i> Plan Expire Alert Window
        </h5>
        <p class="text-muted small mb-4">Set how many days before a user's monthly or yearly plan expires to trigger daily WhatsApp alert messages and the closable top header notification.</p>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Recurring Expiry Alert (Days Before)</label>
            <div class="input-group">
              <input type="number" name="recurring_warning_first_days" class="form-control" value="{{ old('recurring_warning_first_days', $settings->recurring_warning_first_days ?? 7) }}" min="1" max="365">
              <span class="input-group-text bg-light">Days Before Expiry</span>
            </div>
            <small class="text-muted">Example: Enter <b>7</b> for 1 week before expiry. Daily alerts will run every day during this window.</small>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Daily Alert Threshold (Urgent Countdown)</label>
            <div class="input-group">
              <input type="number" name="recurring_warning_daily_days" class="form-control" value="{{ old('recurring_warning_daily_days', $settings->recurring_warning_daily_days ?? 3) }}" min="1" max="365">
              <span class="input-group-text bg-light">Days Before Expiry</span>
            </div>
            <small class="text-muted">High-urgency countdown state (Default: 3 days before).</small>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Custom Warning Banner & WhatsApp Message</label>
          <textarea name="recurring_warning_message" class="form-control" rows="3" placeholder="Warning: Your subscription will expire in {days} day(s) on {date}. Renew your plan now: {link}">{{ old('recurring_warning_message', $settings->recurring_warning_message ?? '') }}</textarea>
          <small class="text-muted">Available placeholders: <code>{days}</code>, <code>{date}</code>, and <code>{link}</code> (direct renewal link).</small>
        </div>

        <div class="mb-2">
          <label class="form-label fw-semibold d-block">Alert System Status</label>
          <div class="form-check form-switch fs-5">
            <input class="form-check-input" type="checkbox" name="recurring_warning_status" value="1" id="warningStatusSwitch" {{ ($settings->recurring_warning_status ?? 1) == 1 ? 'checked' : '' }}>
            <label class="form-check-label fs-6 fw-normal" for="warningStatusSwitch">Enable automated daily WhatsApp reminders & closable dashboard notifications</label>
          </div>
        </div>

        <div class="mt-4 pt-3 border-top text-end">
          <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold" style="background: linear-gradient(135deg, #22c55e 0%, #15803d 100%); border:none;">
            <i class="fa-solid fa-save me-1"></i> Save Expire Alert Settings
          </button>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card p-4 shadow-sm border-0 rounded-4 bg-light">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-info me-2"></i> How Expire Alert Works</h6>
        <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
          <li><b>Top Header Alert:</b> When a user's monthly or yearly plan is within the alert window (e.g., 7 days / 1 week), a prominent notification banner appears on their dashboard header.</li>
          <li><b>Closable Notification:</b> Clients can close/dismiss the banner using the (×) button on their dashboard header.</li>
          <li><b>Daily WhatsApp Reminder:</b> A daily WhatsApp alert message with their direct renewal link is dispatched to their registered phone number.</li>
          <li><b>One-Click Renewal:</b> The message and banner contain a direct renewal link so clients can renew instantly.</li>
        </ul>
      </div>
    </div>
  </div>
</form>
@endsection