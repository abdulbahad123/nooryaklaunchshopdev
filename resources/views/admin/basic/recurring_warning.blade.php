@extends('admin.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title"><i class="fal fa-bell text-warning mr-2"></i>{{ __('Subscription Expiry Alert & Dynamic Renewal') }}</h4>
  <ul class="breadcrumbs">
    <li class="nav-home">
      <a href="{{ route('admin.dashboard') }}">
        <i class="flaticon-home"></i>
      </a>
    </li>
    <li class="separator"><i class="flaticon-right-arrow"></i></li>
    <li class="nav-item"><a href="#">{{ __('Subscribers') }}</a></li>
    <li class="separator"><i class="flaticon-right-arrow"></i></li>
    <li class="nav-item"><a href="#">{{ __('Expiry Alert') }}</a></li>
  </ul>
</div>

@if (session()->has('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong><i class="fas fa-check-circle mr-1"></i> {{ __('Success!') }}</strong> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if (session()->has('warning'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong><i class="fas fa-exclamation-triangle mr-1"></i> {{ __('Notice:') }}</strong> {{ session('warning') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

<div class="card shadow-sm border-0">
  <div class="card-header bg-white py-3">
    <!-- Navigation Tabs -->
    <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active font-weight-bold" id="pills-subscribers-tab" data-toggle="pill" href="#pills-subscribers" role="tab" aria-controls="pills-subscribers" aria-selected="true">
          <i class="fas fa-users-clock mr-1"></i> {{ __('Expiring Subscribers & Alerts') }}
          <span class="badge badge-light ml-1">{{ count($subscribers ?? []) }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link font-weight-bold" id="pills-format-tab" data-toggle="pill" href="#pills-format" role="tab" aria-controls="pills-format" aria-selected="false">
          <i class="fas fa-comment-alt-lines mr-1"></i> {{ __('Expiry Sending Format') }}
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link font-weight-bold" id="pills-pricing-tab" data-toggle="pill" href="#pills-pricing" role="tab" aria-controls="pills-pricing" aria-selected="false">
          <i class="fas fa-chart-line-up mr-1"></i> {{ __('Usage-Based Dynamic Pricing') }}
          @if(($basic->usage_pricing_status ?? '1') == '1')
            <span class="badge badge-success ml-1">{{ __('Active') }}</span>
          @else
            <span class="badge badge-secondary ml-1">{{ __('Disabled') }}</span>
          @endif
        </a>
      </li>
    </ul>
  </div>

  <div class="card-body">
    <div class="tab-content mt-2 mb-3" id="pills-tabContent">

      <!-- ========================================== -->
      <!-- TAB 1: EXPIRING SUBSCRIBERS & SEND ALERT   -->
      <!-- ========================================== -->
      <div class="tab-pane fade show active" id="pills-subscribers" role="tabpanel" aria-labelledby="pills-subscribers-tab">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
          <div>
            <h5 class="font-weight-bold text-dark mb-1">{{ __('Merchant Subscriptions & Expiry Management') }}</h5>
            <small class="text-muted">{{ __('Monitor store usage and send expiry reminders with dynamically calculated renewal pricing.') }}</small>
          </div>
          <div class="mt-2 mt-md-0">
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" id="btnBulkSend">
              <i class="fas fa-paper-plane mr-1"></i> {{ __('Send Alert to Selected') }}
            </button>
          </div>
        </div>

        <!-- Filter Buttons -->
        <div class="mb-3 d-flex flex-wrap" style="gap: 6px;">
          <button type="button" class="btn btn-outline-secondary btn-sm active filter-btn" data-filter="all">{{ __('All Merchants') }}</button>
          <button type="button" class="btn btn-outline-danger btn-sm filter-btn" data-filter="expired">{{ __('Expired') }}</button>
          <button type="button" class="btn btn-outline-warning btn-sm filter-btn" data-filter="exp-7">{{ __('Expiring in 7 Days') }}</button>
          <button type="button" class="btn btn-outline-info btn-sm filter-btn" data-filter="exp-30">{{ __('Expiring in 30 Days') }}</button>
          <button type="button" class="btn btn-outline-success btn-sm filter-btn" data-filter="surge">{{ __('High Usage Surge (2x)') }}</button>
        </div>

        <form id="bulkSendForm" action="{{ route('admin.recurring-warning.bulk-send') }}" method="POST">
          @csrf
          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="subscribersTable">
              <thead class="bg-light">
                <tr>
                  <th width="40"><input type="checkbox" id="selectAll"></th>
                  <th>{{ __('Merchant & Store') }}</th>
                  <th>{{ __('Contact Info') }}</th>
                  <th>{{ __('Plan & Expiry') }}</th>
                  <th>{{ __('Store Usage') }}</th>
                  <th>{{ __('Base Price') }}</th>
                  <th>{{ __('Dynamic Renewal') }}</th>
                  <th class="text-right">{{ __('Send Alert') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($subscribers as $row)
                  @php
                    $u = $row['user'];
                    $pkg = $row['package'];
                    $pricing = $row['pricing'];
                    $days = $row['days_left'];
                    $isExpired = $row['is_expired'];
                    $isSurge = $pricing['is_surge'];
                    
                    // Filter tag
                    $tag = 'normal';
                    if ($isExpired) $tag .= ' expired';
                    if ($days >= 0 && $days <= 7) $tag .= ' exp-7';
                    if ($days >= 0 && $days <= 30) $tag .= ' exp-30';
                    if ($isSurge) $tag .= ' surge';
                  @endphp
                  <tr class="subscriber-row" data-tags="{{ $tag }}">
                    <td>
                      <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" class="sub-check">
                    </td>
                    <td>
                      <div class="font-weight-bold text-dark">{{ $u->shop_name ?: ($u->first_name . ' ' . $u->last_name) }}</div>
                      <small class="text-muted"><i class="fas fa-store mr-1"></i>{{ $u->username }}</small>
                    </td>
                    <td>
                      <div>
                        @if(!empty($u->phone))
                          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $u->phone) }}" target="_blank" class="text-success font-weight-bold">
                            <i class="fab fa-whatsapp mr-1"></i>{{ $u->phone }}
                          </a>
                        @else
                          <span class="text-muted">{{ __('No phone') }}</span>
                        @endif
                      </div>
                      <small class="text-muted"><i class="fas fa-envelope mr-1"></i>{{ $u->email }}</small>
                    </td>
                    <td>
                      <div class="font-weight-bold text-primary">{{ $pkg ? $pkg->title : __('Store Plan') }}</div>
                      <div>
                        @if($row['is_lifetime'])
                          <span class="badge badge-info">{{ __('Lifetime') }}</span>
                        @elseif($isExpired)
                          <span class="badge badge-danger">{{ __('Expired') }} ({{ abs($days) }}d ago)</span>
                        @elseif($days <= 7)
                          <span class="badge badge-warning text-dark font-weight-bold">{{ $days }} {{ __('days left') }}</span>
                        @else
                          <span class="badge badge-light border text-dark">{{ $days }} {{ __('days left') }}</span>
                        @endif
                      </div>
                      <small class="text-muted">{{ $row['expire_date']->format('d M Y') }}</small>
                    </td>
                    <td>
                      <div><strong>{{ $pricing['order_count'] }}</strong> <span class="text-muted">orders</span> / <strong>{{ $pricing['product_count'] }}</strong> <span class="text-muted">products</span></div>
                      @if($pricing['increase_percent'] > 0)
                        <small class="font-weight-bold {{ $isSurge ? 'text-danger' : 'text-success' }}">
                          <i class="fas fa-arrow-up mr-1"></i>+{{ $pricing['increase_percent'] }}% {{ __('over baseline') }}
                        </small>
                      @else
                        <small class="text-muted">{{ __('Normal usage') }}</small>
                      @endif
                    </td>
                    <td>
                      <span class="font-weight-bold">{{ $pricing['currency_symbol'] }}{{ number_format($pricing['base_price'], 2) }}</span>
                    </td>
                    <td>
                      @if($isSurge)
                        <div class="text-danger font-weight-bold" style="font-size: 15px;">
                          {{ $pricing['currency_symbol'] }}{{ number_format($pricing['renewal_price'], 2) }}
                        </div>
                        <span class="badge badge-danger px-2 py-1 font-weight-bold">
                          <i class="fas fa-fire mr-1"></i> {{ $pricing['multiplier'] }}x {{ __('Surge Applied') }}
                        </span>
                      @else
                        <div class="text-success font-weight-bold" style="font-size: 15px;">
                          {{ $pricing['currency_symbol'] }}{{ number_format($pricing['renewal_price'], 2) }}
                        </div>
                        <span class="badge badge-light border text-muted">{{ __('Standard Price') }}</span>
                      @endif
                    </td>
                    <td class="text-right">
                      <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-paper-plane mr-1"></i> {{ __('Send Alert') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0">
                          <form action="{{ route('admin.recurring-warning.send-alert', $u->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="channel" value="both">
                            <button type="submit" class="dropdown-item py-2">
                              <i class="fas fa-bolt text-warning mr-2"></i> {{ __('Send WhatsApp + Email') }}
                            </button>
                          </form>
                          <form action="{{ route('admin.recurring-warning.send-alert', $u->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="channel" value="whatsapp">
                            <button type="submit" class="dropdown-item py-2">
                              <i class="fab fa-whatsapp text-success mr-2"></i> {{ __('Send WhatsApp Only') }}
                            </button>
                          </form>
                          <form action="{{ route('admin.recurring-warning.send-alert', $u->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="channel" value="email">
                            <button type="submit" class="dropdown-item py-2">
                              <i class="fas fa-envelope text-primary mr-2"></i> {{ __('Send Email Only') }}
                            </button>
                          </form>
                        </div>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                      {{ __('No subscribers found in database.') }}
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </form>
      </div>

      <!-- ========================================== -->
      <!-- TAB 2: EXPIRY ALERT FORMAT & TEMPLATES     -->
      <!-- ========================================== -->
      <div class="tab-pane fade" id="pills-format" role="tabpanel" aria-labelledby="pills-format-tab">
        <form action="{{ route('admin.recurring-warning.update-template') }}" method="POST">
          @csrf

          <div class="row">
            <!-- Left Side: Templates -->
            <div class="col-lg-8">
              <div class="card border mb-4">
                <div class="card-header bg-light d-flex align-items-center justify-content-between py-3">
                  <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fab fa-whatsapp text-success mr-2"></i>{{ __('WhatsApp Expiry Alert Message Template') }}
                  </h6>
                  <span class="badge badge-success">{{ __('Direct WhatsApp Gateway') }}</span>
                </div>
                <div class="card-body">
                  <div class="form-group px-0">
                    <label class="font-weight-bold">{{ __('Message Format') }} *</label>
                    <textarea name="recurring_warning_whatsapp_template" id="waTemplateInput" rows="9" class="form-control" style="font-family: monospace; font-size: 13px;">{{ $basic->recurring_warning_whatsapp_template }}</textarea>
                    <small class="text-muted d-block mt-2">
                      {{ __('Click any tag below to insert into the message:') }}
                    </small>
                    <div class="mt-2 d-flex flex-wrap tag-container" style="gap: 5px;">
                      <button type="button" class="btn btn-xs btn-outline-primary insert-tag" data-tag="{user_name}">{user_name}</button>
                      <button type="button" class="btn btn-xs btn-outline-primary insert-tag" data-tag="{store_name}">{store_name}</button>
                      <button type="button" class="btn btn-xs btn-outline-primary insert-tag" data-tag="{plan_name}">{plan_name}</button>
                      <button type="button" class="btn btn-xs btn-outline-primary insert-tag" data-tag="{expire_date}">{expire_date}</button>
                      <button type="button" class="btn btn-xs btn-outline-primary insert-tag" data-tag="{days_left}">{days_left}</button>
                      <button type="button" class="btn btn-xs btn-outline-danger insert-tag font-weight-bold" data-tag="{base_price}">{base_price}</button>
                      <button type="button" class="btn btn-xs btn-outline-success insert-tag font-weight-bold" data-tag="{renewal_price}">{renewal_price}</button>
                      <button type="button" class="btn btn-xs btn-outline-secondary insert-tag" data-tag="{surge_badge}">{surge_badge}</button>
                      <button type="button" class="btn btn-xs btn-outline-secondary insert-tag" data-tag="{usage_summary}">{usage_summary}</button>
                      <button type="button" class="btn btn-xs btn-outline-primary insert-tag" data-tag="{renewal_url}">{renewal_url}</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card border mb-4">
                <div class="card-header bg-light d-flex align-items-center justify-content-between py-3">
                  <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-envelope text-primary mr-2"></i>{{ __('Email Expiry Alert Message Template') }}
                  </h6>
                  <span class="badge badge-primary">{{ __('SMTP / PHP Mailer') }}</span>
                </div>
                <div class="card-body">
                  <div class="form-group px-0 mb-3">
                    <label class="font-weight-bold">{{ __('Email Subject Line') }} *</label>
                    <input type="text" name="recurring_warning_email_subject" class="form-control" value="{{ $basic->recurring_warning_email_subject }}">
                  </div>

                  <div class="form-group px-0">
                    <label class="font-weight-bold">{{ __('Email HTML Body') }} *</label>
                    <textarea name="recurring_warning_email_template" rows="9" class="form-control" style="font-family: monospace; font-size: 13px;">{{ $basic->recurring_warning_email_template }}</textarea>
                    <small class="text-muted d-block mt-2">
                      {{ __('Supports full HTML styling and all placeholder tags ({user_name}, {store_name}, {plan_name}, {expire_date}, {days_left}, {base_price}, {renewal_price}, {surge_badge}, {usage_summary}, {renewal_url}).') }}
                    </small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Side: Dispatch Intervals & Activation -->
            <div class="col-lg-4">
              <div class="card border mb-4">
                <div class="card-header bg-light py-3">
                  <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-sliders-h text-secondary mr-2"></i>{{ __('Notification Trigger Timings') }}
                  </h6>
                </div>
                <div class="card-body">
                  <div class="p-3 border rounded bg-light mb-3 d-flex align-items-center justify-content-between">
                    <div>
                      <h6 class="font-weight-bold mb-1">{{ __('Automated Expiry Alerts') }}</h6>
                      <small class="text-muted">{{ __('Send warnings via cron task') }}</small>
                    </div>
                    <label class="switch mb-0">
                      <input type="checkbox" name="recurring_warning_status" value="1" {{ ($basic->recurring_warning_status ?? '1') == '1' ? 'checked' : '' }}>
                      <span class="slider round"></span>
                    </label>
                  </div>

                  <div class="form-group px-0 mb-3">
                    <label class="font-weight-bold">{{ __('First Reminder Notice') }}</label>
                    <div class="input-group">
                      <input type="number" name="recurring_warning_first_days" class="form-control" value="{{ $basic->recurring_warning_first_days ?? '30' }}" min="1">
                      <div class="input-group-append"><span class="input-group-text">{{ __('Days Before') }}</span></div>
                    </div>
                  </div>

                  <div class="form-group px-0 mb-3">
                    <label class="font-weight-bold">{{ __('Second Reminder Notice') }}</label>
                    <div class="input-group">
                      <input type="number" name="recurring_warning_second_days" class="form-control" value="{{ $basic->recurring_warning_second_days ?? '7' }}" min="1">
                      <div class="input-group-append"><span class="input-group-text">{{ __('Days Before') }}</span></div>
                    </div>
                  </div>

                  <div class="form-group px-0 mb-3">
                    <label class="font-weight-bold">{{ __('Daily Urgent Countdown') }}</label>
                    <div class="input-group">
                      <input type="number" name="recurring_warning_daily_days" class="form-control" value="{{ $basic->recurring_warning_daily_days ?? '3' }}" min="1">
                      <div class="input-group-append"><span class="input-group-text">{{ __('Days Before') }}</span></div>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-success btn-block font-weight-bold mt-4 shadow-sm">
                    <i class="fas fa-save mr-1"></i> {{ __('Save Sending Formats & Timings') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- ========================================== -->
      <!-- TAB 3: USAGE-BASED DYNAMIC RENEWAL PRICING -->
      <!-- ========================================== -->
      <div class="tab-pane fade" id="pills-pricing" role="tabpanel" aria-labelledby="pills-pricing-tab">
        <form action="{{ route('admin.recurring-warning.update-pricing') }}" method="POST">
          @csrf

          <div class="row">
            <div class="col-lg-7">
              <div class="card border mb-4">
                <div class="card-header bg-light d-flex align-items-center justify-content-between py-3">
                  <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-calculator-alt text-primary mr-2"></i>{{ __('Dynamic Renewal Pricing Configuration') }}
                  </h6>
                  <span class="badge badge-info">{{ __('Usage-Based Surge') }}</span>
                </div>
                <div class="card-body">
                  <div class="p-3 border rounded bg-light mb-4 d-flex align-items-center justify-content-between">
                    <div>
                      <h6 class="font-weight-bold mb-1">{{ __('Enable Usage-Based Dynamic Pricing') }}</h6>
                      <small class="text-muted">{{ __('Automatically scale renewal price when store activity exceeds baseline') }}</small>
                    </div>
                    <label class="switch mb-0">
                      <input type="checkbox" name="usage_pricing_status" value="1" {{ ($basic->usage_pricing_status ?? '1') == '1' ? 'checked' : '' }}>
                      <span class="slider round"></span>
                    </label>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="font-weight-bold">{{ __('Usage Increase Threshold (%)') }} *</label>
                      <div class="input-group">
                        <input type="number" step="1" name="usage_increase_threshold" id="cfgThreshold" class="form-control" value="{{ $basic->usage_increase_threshold ?? '40' }}" min="1" max="500" required>
                        <div class="input-group-append"><span class="input-group-text">%</span></div>
                      </div>
                      <small class="text-muted">{{ __('E.g. 40% activity surge over plan baseline') }}</small>
                    </div>

                    <div class="col-md-6 mb-3">
                      <label class="font-weight-bold">{{ __('Price Escalation Multiplier') }} *</label>
                      <div class="input-group">
                        <input type="number" step="0.1" name="usage_price_multiplier" id="cfgMultiplier" class="form-control" value="{{ $basic->usage_price_multiplier ?? '2.0' }}" min="1.0" max="10.0" required>
                        <div class="input-group-append"><span class="input-group-text">x</span></div>
                      </div>
                      <small class="text-muted">{{ __('E.g. 2.0x (Original 2k renewal becomes 4k)') }}</small>
                    </div>

                    <div class="col-md-6 mb-3">
                      <label class="font-weight-bold">{{ __('Baseline Store Orders') }} *</label>
                      <input type="number" name="usage_baseline_orders" id="cfgOrders" class="form-control" value="{{ $basic->usage_baseline_orders ?? '100' }}" min="1" required>
                      <small class="text-muted">{{ __('Standard orders threshold for plan') }}</small>
                    </div>

                    <div class="col-md-6 mb-3">
                      <label class="font-weight-bold">{{ __('Baseline Store Products') }} *</label>
                      <input type="number" name="usage_baseline_products" id="cfgProducts" class="form-control" value="{{ $basic->usage_baseline_products ?? '50' }}" min="1" required>
                      <small class="text-muted">{{ __('Standard products threshold for plan') }}</small>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary font-weight-bold mt-3 shadow-sm">
                    <i class="fas fa-save mr-1"></i> {{ __('Save Dynamic Pricing Rules') }}
                  </button>
                </div>
              </div>
            </div>

            <!-- Right Side: Interactive Live Simulator -->
            <div class="col-lg-5">
              <div class="card border border-primary mb-4" style="background-color: #f8fafc;">
                <div class="card-header bg-primary text-white py-3">
                  <h6 class="font-weight-bold mb-0">
                    <i class="fas fa-magic mr-2"></i>{{ __('Interactive Renewal Price Calculator') }}
                  </h6>
                </div>
                <div class="card-body">
                  <p class="text-muted small mb-3">
                    {{ __('Test how your pricing rules work in real-time. Enter merchant sample data below to simulate the calculated renewal price:') }}
                  </p>

                  <div class="form-group px-0 mb-2">
                    <label class="small font-weight-bold">{{ __('Base Subscription Price (₹)') }}:</label>
                    <input type="number" id="simBasePrice" class="form-control form-control-sm" value="2000">
                  </div>

                  <div class="form-group px-0 mb-3">
                    <label class="small font-weight-bold">{{ __('Store Actual Orders Handled') }}:</label>
                    <input type="number" id="simActualOrders" class="form-control form-control-sm" value="145">
                    <small class="text-muted">{{ __('Baseline orders: 100 (+45% activity increase)') }}</small>
                  </div>

                  <div class="p-3 bg-white rounded border">
                    <div class="d-flex justify-content-between mb-2">
                      <span class="text-muted">{{ __('Calculated Activity Surge:') }}</span>
                      <strong id="simSurgePct" class="text-danger">+45.0%</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                      <span class="text-muted">{{ __('Threshold Required:') }}</span>
                      <span id="simThresholdText">40.0%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                      <span class="text-muted">{{ __('Multiplier Applied:') }}</span>
                      <strong id="simMultiplierText" class="text-primary">2.0x</strong>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="font-weight-bold text-dark">{{ __('Final Renewal Price:') }}</span>
                      <span id="simFinalPrice" class="font-weight-bold text-success" style="font-size: 20px;">₹4,000.00</span>
                    </div>
                  </div>

                  <div class="mt-3 p-2 bg-light rounded text-muted small border">
                    <i class="fas fa-info-circle text-info mr-1"></i>
                    {{ __('When the expiry reminder alert is sent to WhatsApp or Email, the price badge automatically computes and displays this dynamically escalated renewal price.') }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<style>
  .switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
  }
  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 24px;
  }
  .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
  }
  input:checked + .slider {
    background-color: #059669;
  }
  input:checked + .slider:before {
    transform: translateX(24px);
  }
  .tag-container .btn-xs {
    padding: 3px 8px;
    font-size: 11px;
    font-family: monospace;
    border-radius: 4px;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Select All Checkbox
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
      selectAll.addEventListener('change', function () {
        document.querySelectorAll('.sub-check').forEach(cb => {
          cb.checked = selectAll.checked;
        });
      });
    }

    // Bulk Send Button
    const btnBulk = document.getElementById('btnBulkSend');
    if (btnBulk) {
      btnBulk.addEventListener('click', function () {
        const checked = document.querySelectorAll('.sub-check:checked');
        if (checked.length === 0) {
          alert('{{ __("Please select at least one subscriber.") }}');
          return;
        }
        if (confirm('{{ __("Send expiry alerts with dynamic renewal pricing to ") }}' + checked.length + ' {{ __("selected subscribers?") }}')) {
          document.getElementById('bulkSendForm').submit();
        }
      });
    }

    // Filter Buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.getAttribute('data-filter');

        document.querySelectorAll('.subscriber-row').forEach(row => {
          if (filter === 'all') {
            row.style.display = '';
          } else {
            const tags = row.getAttribute('data-tags') || '';
            row.style.display = tags.includes(filter) ? '' : 'none';
          }
        });
      });
    });

    // Tag inserters for WhatsApp template
    document.querySelectorAll('.insert-tag').forEach(tagBtn => {
      tagBtn.addEventListener('click', function () {
        const tag = this.getAttribute('data-tag');
        const textarea = document.getElementById('waTemplateInput');
        if (textarea) {
          const start = textarea.selectionStart;
          const end = textarea.selectionEnd;
          const val = textarea.value;
          textarea.value = val.substring(0, start) + tag + val.substring(end);
          textarea.focus();
          textarea.selectionStart = textarea.selectionEnd = start + tag.length;
        }
      });
    });

    // Interactive Simulator
    function updateSimulation() {
      const basePrice = parseFloat(document.getElementById('simBasePrice').value) || 0;
      const actualOrders = parseFloat(document.getElementById('simActualOrders').value) || 0;
      const threshold = parseFloat(document.getElementById('cfgThreshold').value) || 40;
      const multiplier = parseFloat(document.getElementById('cfgMultiplier').value) || 2.0;
      const baselineOrders = parseFloat(document.getElementById('cfgOrders').value) || 100;

      const usageRatio = (actualOrders / baselineOrders) * 100;
      const increasePct = Math.max(0, usageRatio - 100);

      document.getElementById('simSurgePct').textContent = '+' + increasePct.toFixed(1) + '%';
      document.getElementById('simThresholdText').textContent = threshold.toFixed(1) + '%';

      let finalPrice = basePrice;
      if (increasePct >= threshold) {
        finalPrice = basePrice * multiplier;
        document.getElementById('simMultiplierText').textContent = multiplier.toFixed(1) + 'x (Surge Active)';
        document.getElementById('simSurgePct').className = 'text-danger font-weight-bold';
      } else {
        document.getElementById('simMultiplierText').textContent = '1.0x (Standard)';
        document.getElementById('simSurgePct').className = 'text-success font-weight-bold';
      }

      document.getElementById('simFinalPrice').textContent = '₹' + finalPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    const simInputs = ['simBasePrice', 'simActualOrders', 'cfgThreshold', 'cfgMultiplier', 'cfgOrders'];
    simInputs.forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('input', updateSimulation);
    });
    updateSimulation();
  });
</script>
@endsection
