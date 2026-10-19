@extends('layouts.app')

@section('content')
<div class="border-bottom mb-4 py-4 bg-polygon">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="fw-7 fs-22 mb-1 text-primary-700">
                    <i class="fa-light fa-bell-exclamation me-2 text-warning"></i>{{ __('Expire Alert') }}
                </div>
                <div class="fw-5 text-gray-700">{{ __('Configure plan expiry alert days and send daily WhatsApp renewal notices.') }}</div>
            </div>
            <div>
                <a href="{{ url('admin/dashboard') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                    <i class="fa-regular fa-arrow-left me-1"></i> {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="fa-regular fa-circle-check fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="fa-regular fa-triangle-exclamation fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Overview Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 5px solid #ef4444 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-danger fw-bold small text-uppercase mb-1">{{ __('Expired Accounts') }}</div>
                        <div class="fs-28 fw-bold text-danger">{{ count($expiredUsers) }}</div>
                    </div>
                    <div class="size-48 rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fs-20">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-left: 5px solid #f59e0b !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-warning-800 fw-bold small text-uppercase mb-1" style="color:#b45309;">{{ __('Expiring Within Alert Window') }}</div>
                        <div class="fs-28 fw-bold" style="color:#b45309;">{{ count($expiringSoonUsers) }}</div>
                    </div>
                    <div class="size-48 rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fs-20">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 5px solid #10b981 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-success fw-bold small text-uppercase mb-1">{{ __('Active / Lifetime Accounts') }}</div>
                        <div class="fs-28 fw-bold text-success">{{ count($activeUsers) }}</div>
                    </div>
                    <div class="size-48 rounded-circle bg-success text-white d-flex align-items-center justify-content-center fs-20">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Settings Form --}}
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="fw-bold fs-16 text-dark d-flex align-items-center gap-2">
                <i class="fa-light fa-sliders text-primary"></i> {{ __('Recurring Expiry Alert Settings') }}
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ url('admin/expire-alert/update') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('Recurring Expiry Alert (Days Before Expiry)') }}</label>
                        <div class="input-group">
                            <input type="number" name="expire_alert_days" class="form-control form-control-lg rounded-start-3" value="{{ old('expire_alert_days', $alertDays) }}" min="1" max="365" required>
                            <span class="input-group-text bg-light fw-medium">{{ __('Days Before') }}</span>
                        </div>
                        <small class="text-muted d-block mt-1">{{ __('Example: Enter 7 for 1 week before expiry for monthly or yearly plans.') }}</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('Critical Countdown Alert (Urgent State)') }}</label>
                        <div class="input-group">
                            <input type="number" name="expire_alert_daily_days" class="form-control form-control-lg rounded-start-3" value="{{ old('expire_alert_daily_days', $dailyDays) }}" min="1" max="365" required>
                            <span class="input-group-text bg-light fw-medium">{{ __('Days Before') }}</span>
                        </div>
                        <small class="text-muted d-block mt-1">{{ __('Triggers high-urgency banner state (Default: 3 days).') }}</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('Custom WhatsApp & Banner Warning Message') }}</label>
                        <textarea name="expire_alert_message" class="form-control rounded-3" rows="3" placeholder="⚠️ Subscription Notice: Your plan expires in {days} day(s) on {date}. Renew your plan now: {link}">{{ old('expire_alert_message', $customMessage) }}</textarea>
                        <small class="text-muted">{{ __('Available placeholders:') }} <code>{name}</code>, <code>{days}</code>, <code>{date}</code>, {{ __('and') }} <code>{link}</code> ({{ __('direct one-click renewal link') }}).</small>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input" type="checkbox" name="expire_alert_status" value="1" id="alertStatusToggle" {{ $alertStatus == 1 ? 'checked' : '' }}>
                            <label class="form-check-label fs-6 fw-normal" for="alertStatusToggle">
                                {{ __('Enable automated daily WhatsApp reminders & closable dashboard notifications') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-12 text-end pt-2">
                        <button type="submit" class="btn btn-success btn-lg px-4 rounded-pill fw-bold" style="background: linear-gradient(135deg, #22c55e 0%, #15803d 100%); border:none;">
                            <i class="fa-solid fa-save me-1"></i> {{ __('Save Expire Alert Settings') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Expired & Expiring Accounts List --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <div class="fw-bold fs-16 text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-users text-primary"></i> {{ __('Expired & Upcoming Expiry Accounts') }}
            </div>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium">
                {{ count($expiredUsers) + count($expiringSoonUsers) }} {{ __('Accounts Actionable') }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">{{ __('User') }}</th>
                            <th>{{ __('WhatsApp Phone') }}</th>
                            <th>{{ __('Expiration Date') }}</th>
                            <th>{{ __('Alert Status') }}</th>
                            <th class="text-end pe-4">{{ __('Quick Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(array_merge($expiredUsers, $expiringSoonUsers) as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $item->fullname ?: $item->username }}</div>
                                    <div class="small text-muted">{{ $item->email }}</div>
                                </td>
                                <td>
                                    @if(!empty($item->phone))
                                        <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                            <i class="fa-brands fa-whatsapp text-success me-1"></i>{{ $item->phone }}
                                        </span>
                                    @else
                                        <span class="text-muted small">{{ __('No phone') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $item->exp_formatted ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $item->status_badge }} px-3 py-2 rounded-pill fw-bold">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        {{-- WhatsApp Alert Button --}}
                                        <form action="{{ url('admin/expire-alert/send-whatsapp') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-medium" title="Send WhatsApp alert with direct renewal link">
                                                <i class="fa-brands fa-whatsapp me-1"></i> {{ __('Send Alert') }}
                                            </button>
                                        </form>

                                        {{-- Quick 30 Days Extend --}}
                                        <form action="{{ url('admin/expire-alert/extend-plan') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $item->id }}">
                                            <input type="hidden" name="days" value="30">
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-medium">
                                                <i class="fa-regular fa-arrows-rotate me-1"></i> {{ __('+30 Days') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-regular fa-circle-check fs-2 text-success d-block mb-2"></i>
                                    {{ __('No users currently expired or expiring within the alert window.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection