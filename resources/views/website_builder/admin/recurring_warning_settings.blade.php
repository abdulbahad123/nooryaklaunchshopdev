@extends('website_builder.admin.layout')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">
      <i class="fa-solid fa-bell-exclamation me-2 text-warning"></i> Expire Alert Settings
    </h3>
    <p class="text-muted small mb-0">Configure automated subscription plan expiration warnings and reminders for monthly and yearly plans.</p>
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
        <p class="text-muted small mb-4">Set how many days before a user's monthly or yearly plan expires to trigger subscription renewal notices.</p>

        <div class="mb-4">
          <label class="form-label fw-semibold">Recurring Expiry Alert (Days Before)</label>
          <div class="input-group">
            <input type="number" name="recurring_warning_first_days" class="form-control form-control-lg" value="{{ old('recurring_warning_first_days', $settings->recurring_warning_first_days ?? 7) }}" min="1" max="365" required>
            <span class="input-group-text bg-light fw-medium">Days Before Expiry</span>
          </div>
          <small class="text-muted d-block mt-2">Example: Enter <b>7</b> for 1 week before expiry.</small>
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
          <li><b>Top Header Alert:</b> When a user's plan is within the alert window, a notification banner appears on their dashboard header.</li>
          <li><b>WhatsApp Reminder:</b> Automated alert message with direct renewal link is dispatched to their registered phone number.</li>
          <li><b>One-Click Renewal:</b> Direct renewal button redirects clients straight to checkout.</li>
        </ul>
      </div>
    </div>
  </div>
</form>
@endsection