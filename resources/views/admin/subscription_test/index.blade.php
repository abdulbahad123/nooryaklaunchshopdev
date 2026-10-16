@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Subscription Expiry & Testing Hub') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fas fa-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="fas fa-chevron-right"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Users Management') }}</a>
      </li>
      <li class="separator">
        <i class="fas fa-chevron-right"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Subscription Testing') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      @if (session('test_result'))
        @php $res = session('test_result'); @endphp
        <div class="alert alert-{{ !empty($res['success']) ? 'success' : 'danger' }} alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 6px solid #10b981;">
          <h4 class="alert-heading font-weight-bold mb-1">
            <i class="fas fa-check-circle mr-1"></i> {{ __('Action Execution Report') }}
          </h4>
          <p class="mb-2 font-weight-bold">{{ $res['message'] ?? '' }}</p>
          <hr class="my-2">
          <div class="row">
            <div class="col-sm-6">
              <small class="text-muted d-block">{{ __('Store User') }}:</small>
              <strong>{{ $res['user']['username'] ?? '' }}</strong> ({{ $res['user']['phone'] ?? 'No Phone' }})
            </div>
            <div class="col-sm-6">
              <small class="text-muted d-block">{{ __('Live Store URL') }}:</small>
              <a href="{{ $res['store_url'] ?? '#' }}" target="_blank" class="font-weight-bold text-primary">
                {{ $res['store_url'] ?? '' }} <i class="fas fa-external-link-alt ml-1"></i>
              </a>
            </div>
          </div>
          @if (!empty($res['warning_dispatch']))
            <div class="mt-2 pt-2 border-top">
              <span class="badge badge-{{ !empty($res['warning_dispatch']['whatsapp_sent']) ? 'success' : 'warning' }} mr-2 p-2">
                <i class="fab fa-whatsapp"></i> WhatsApp: {{ !empty($res['warning_dispatch']['whatsapp_sent']) ? 'DELIVERED' : 'NOT SENT' }}
              </span>
              <span class="badge badge-{{ !empty($res['warning_dispatch']['email_sent']) ? 'success' : 'warning' }} p-2">
                <i class="fas fa-envelope"></i> Email: {{ !empty($res['warning_dispatch']['email_sent']) ? 'DELIVERED' : 'NOT SENT' }}
              </span>
            </div>
          @endif
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <div class="card shadow-sm" style="border-radius: 16px; border: 1px solid rgba(0,0,0,0.06);">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
          <div class="card-title text-white font-weight-bold mb-0">
            <i class="fas fa-stopwatch mr-2"></i> {{ __('Fast 5-Minute Subscription Expiry & Gatekeeper Simulator') }}
          </div>
          <span class="badge badge-light text-primary font-weight-bold px-3 py-2">
            {{ __('Multi-Product & Agency Supported') }}
          </span>
        </div>

        <div class="card-body p-4">
          <p class="text-muted mb-4" style="font-size: 15px;">
            {{ __('Use this simulator to test subscription expiration in 1 to 5 minutes without waiting 30 days. Test immediate WhatsApp/Email reminders, observe live website blocking (Store Suspended View), and simulate customer renewal.') }}
          </p>

          <form action="{{ route('admin.subscription.test.execute') }}" method="POST" id="testForm">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group px-0">
                  <label class="font-weight-bold">{{ __('Select Store / User to Test') }} <span class="text-danger">*</span></label>
                  <select name="user_id" id="userSelect" class="form-control select2" style="width: 100%; height: 46px;" required onchange="window.location.href='{{ route('admin.subscription.test') }}?user_id=' + this.value;">
                    <option value="" disabled {{ empty($selectedUser) ? 'selected' : '' }}>-- {{ __('Select a merchant store') }} --</option>
                    @foreach ($users as $u)
                      <option value="{{ $u->id }}" {{ (!empty($selectedUser) && $selectedUser->id == $u->id) ? 'selected' : '' }}>
                        {{ $u->username }} ({{ $u->email }} | {{ $u->phone ?: ($u->phone_number ?: 'No Phone') }})
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group px-0">
                  <label class="font-weight-bold">{{ __('Test Expiry Countdown (Minutes)') }}</label>
                  <div class="input-group">
                    <input type="number" name="minutes" class="form-control" value="5" min="1" max="1440" style="height: 46px;">
                    <div class="input-group-append">
                      <span class="input-group-text font-weight-bold">{{ __('Minutes') }}</span>
                    </div>
                  </div>
                  <small class="text-muted">{{ __('Default: 5 minutes. Set to 1-2 minutes for fast instant testing.') }}</small>
                </div>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-6">
                <div class="form-group px-0">
                  <label class="font-weight-bold">{{ __('Override WhatsApp Phone (Optional for testing)') }}</label>
                  <input type="text" name="test_phone" class="form-control" placeholder="e.g. 919876543210 (Country code + number)" value="{{ $selectedUser ? ($selectedUser->phone ?: $selectedUser->phone_number) : '' }}" style="height: 46px;">
                  <small class="text-muted">{{ __('Enter your personal phone number if you want the test reminder sent to you directly.') }}</small>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group px-0">
                  <label class="font-weight-bold">{{ __('Override Email Address (Optional for testing)') }}</label>
                  <input type="email" name="test_email" class="form-control" placeholder="e.g. test@example.com" value="{{ $selectedUser ? $selectedUser->email : '' }}" style="height: 46px;">
                  <small class="text-muted">{{ __('Enter your personal email if you want the test reminder sent to you directly.') }}</small>
                </div>
              </div>
            </div>

            @if ($selectedUser)
              <div class="alert alert-secondary p-3 mt-3 mb-4 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="row align-items-center">
                  <div class="col-md-3">
                    <small class="text-muted d-block text-uppercase font-weight-bold">{{ __('Selected Merchant') }}</small>
                    <span class="h6 font-weight-bold text-dark">{{ $selectedUser->username }}</span>
                  </div>
                  <div class="col-md-3">
                    <small class="text-muted d-block text-uppercase font-weight-bold">{{ __('Current Validity') }}</small>
                    @if ($latestMembership && !empty($latestMembership->expire_date))
                      <span class="badge badge-info p-2">{{ \Carbon\Carbon::parse($latestMembership->expire_date)->format('M d, Y h:i A') }}</span>
                    @else
                      <span class="badge badge-warning p-2">{{ __('No Active Membership') }}</span>
                    @endif
                  </div>
                  <div class="col-md-3">
                    <small class="text-muted d-block text-uppercase font-weight-bold">{{ __('Account Status') }}</small>
                    <span class="badge badge-{{ $selectedUser->status == 1 ? 'success' : 'danger' }} p-2">
                      {{ $selectedUser->status == 1 ? __('Active') : __('Blocked / Inactive') }}
                    </span>
                  </div>
                  <div class="col-md-3 text-right">
                    @php
                      $storeHost = env('WEBSITE_HOST', 'launchshop.youverse.in');
                      $sUrl = "https://{$storeHost}/{$selectedUser->username}";
                    @endphp
                    <a href="{{ $sUrl }}" target="_blank" class="btn btn-outline-primary btn-sm font-weight-bold">
                      <i class="fas fa-store mr-1"></i> {{ __('Open Live Store') }}
                    </a>
                  </div>
                </div>
              </div>
            @endif

            <input type="hidden" name="action" id="actionInput" value="simulate-full">

            <div class="mt-4 pt-3 border-top">
              <h5 class="font-weight-bold mb-3 text-dark">
                <i class="fas fa-play-circle text-primary mr-1"></i> {{ __('Choose Testing Scenario') }}
              </h5>

              <div class="row">
                <div class="col-lg-4 col-md-6 mb-3">
                  <button type="button" class="btn btn-primary btn-block text-left p-3 shadow-sm h-100" onclick="submitAction('simulate-full')" {{ empty($selectedUser) ? 'disabled' : '' }}>
                    <div class="font-weight-bold mb-1" style="font-size: 15px;">
                      <i class="fas fa-bolt mr-1"></i> 1. {{ __('Full 5-Minute Simulation') }}
                    </div>
                    <small class="d-block" style="opacity: 0.9;">
                      {{ __('Sets expiry to 5 min from now & immediately sends WhatsApp + Email warning notice.') }}
                    </small>
                  </button>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                  <button type="button" class="btn btn-danger btn-block text-left p-3 shadow-sm h-100" onclick="submitAction('expire-now')" {{ empty($selectedUser) ? 'disabled' : '' }}>
                    <div class="font-weight-bold mb-1" style="font-size: 15px;">
                      <i class="fas fa-ban mr-1"></i> 2. {{ __('Expire Now (Block Website)') }}
                    </div>
                    <small class="d-block" style="opacity: 0.9;">
                      {{ __('Immediately sets plan as expired. Store is blocked; visitors see Store Suspended view.') }}
                    </small>
                  </button>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                  <button type="button" class="btn btn-success btn-block text-left p-3 shadow-sm h-100" onclick="submitAction('test-renewal')" {{ empty($selectedUser) ? 'disabled' : '' }}>
                    <div class="font-weight-bold mb-1" style="font-size: 15px;">
                      <i class="fas fa-credit-card mr-1"></i> 3. {{ __('Simulate Renewal (+5m & Invoice)') }}
                    </div>
                    <small class="d-block" style="opacity: 0.9;">
                      {{ __('Extends plan by 5 minutes, generates PDF invoice, and sends to WhatsApp & Email.') }}
                    </small>
                  </button>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                  <button type="button" class="btn btn-info btn-block text-left p-3 shadow-sm h-100" onclick="submitAction('reactivate')" {{ empty($selectedUser) ? 'disabled' : '' }}>
                    <div class="font-weight-bold mb-1" style="font-size: 15px;">
                      <i class="fas fa-sync-alt mr-1"></i> 4. {{ __('Admin Reactivate / Extend Store') }}
                    </div>
                    <small class="d-block" style="opacity: 0.9;">
                      {{ __('Manually reactivates the account, extends validity by 30 days, and brings store back online.') }}
                    </small>
                  </button>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                  <button type="button" class="btn btn-warning btn-block text-left p-3 shadow-sm h-100" onclick="submitAction('send-warning')" {{ empty($selectedUser) ? 'disabled' : '' }}>
                    <div class="font-weight-bold mb-1 text-dark" style="font-size: 15px;">
                      <i class="fab fa-whatsapp mr-1"></i> 5. {{ __('Send Expiry Warning Notice Only') }}
                    </div>
                    <small class="d-block text-dark" style="opacity: 0.9;">
                      {{ __('Dispatches the WhatsApp warning template and warning email without altering expiry date.') }}
                    </small>
                  </button>
                </div>
              </div>
            </div>
          </form>

          <div class="alert alert-light mt-4 p-3 border rounded">
            <h6 class="font-weight-bold mb-2 text-dark">
              <i class="fas fa-terminal mr-1"></i> {{ __('Also Available via CLI (Artisan Command)') }}
            </h6>
            <code>php artisan subscription:test {username} --action=simulate-full --minutes=5</code><br>
            <code>php artisan subscription:test {username} --action=expire-now</code><br>
            <code>php artisan subscription:test {username} --action=test-renewal --minutes=5</code><br>
            <code>php artisan subscription:test {username} --action=reactivate --days=30</code>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function submitAction(action) {
      document.getElementById('actionInput').value = action;
      document.getElementById('testForm').submit();
    }
  </script>
@endsection