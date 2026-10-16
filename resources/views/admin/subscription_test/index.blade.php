<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Subscription Expiry & Testing Hub | LaunchShop Cloud</title>
  
  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <style>
    :root {
      --primary: #4f46e5;
      --primary-hover: #4338ca;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --dark: #0f172a;
      --card-bg: #ffffff;
      --border-color: #e2e8f0;
    }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f8fafc;
      color: #1e293b;
      min-height: 100vh;
      margin: 0;
      padding-bottom: 60px;
    }
    .top-navbar {
      background: #0f172a;
      border-bottom: 1px solid #1e293b;
      padding: 16px 24px;
      color: #fff;
    }
    .brand-title {
      font-weight: 700;
      font-size: 1.15rem;
      letter-spacing: -0.02em;
      display: flex;
      align-items: center;
      gap: 10px;
      color: #fff;
      text-decoration: none !important;
    }
    .brand-badge {
      font-size: 0.7rem;
      padding: 4px 8px;
      border-radius: 6px;
      background: rgba(99, 102, 241, 0.25);
      color: #a5b4fc;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .nav-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .btn-portal-back {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      color: #f1f5f9;
      font-size: 0.85rem;
      font-weight: 600;
      padding: 7px 14px;
      border-radius: 8px;
      transition: all 0.2s ease;
      text-decoration: none !important;
    }
    .btn-portal-back:hover {
      background: rgba(255, 255, 255, 0.16);
      color: #fff;
    }
    .main-container {
      max-width: 1280px;
      margin: 32px auto;
      padding: 0 20px;
    }
    .product-pills {
      display: flex;
      gap: 10px;
      background: #fff;
      padding: 8px;
      border-radius: 14px;
      border: 1px solid var(--border-color);
      margin-bottom: 24px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
      flex-wrap: wrap;
    }
    .product-pill {
      flex: 1;
      min-width: 200px;
      padding: 12px 18px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 0.92rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none !important;
      color: #64748b;
      transition: all 0.2s ease;
    }
    .product-pill:hover {
      background: #f1f5f9;
      color: #1e293b;
    }
    .product-pill.active {
      background: var(--primary);
      color: #fff;
      box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }
    .content-card {
      background: #fff;
      border-radius: 16px;
      border: 1px solid var(--border-color);
      box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }
    .card-topbar {
      padding: 20px 28px;
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .card-topbar h2 {
      font-size: 1.25rem;
      font-weight: 700;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .card-body-inner {
      padding: 28px;
    }
    .form-label {
      font-weight: 600;
      font-size: 0.88rem;
      color: #334155;
      margin-bottom: 8px;
      display: block;
    }
    .form-control-custom {
      height: 48px;
      border-radius: 10px;
      border: 1.5px solid #cbd5e1;
      padding: 10px 16px;
      font-size: 0.95rem;
      font-weight: 500;
      transition: border-color 0.2s;
      width: 100%;
    }
    .form-control-custom:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
      outline: none;
    }
    .target-account-card {
      background: #f8fafc;
      border: 1.5px solid #e2e8f0;
      border-radius: 14px;
      padding: 20px;
      margin-top: 20px;
      margin-bottom: 24px;
    }
    .scenario-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 16px;
      margin-top: 16px;
    }
    .scenario-btn {
      border: none;
      border-radius: 14px;
      padding: 18px 20px;
      text-align: left;
      cursor: pointer;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      flex-direction: column;
      gap: 6px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }
    .scenario-btn:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
    }
    .scenario-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
    .scenario-btn-primary {
      background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
      color: #fff;
    }
    .scenario-btn-danger {
      background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
      color: #fff;
    }
    .scenario-btn-success {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      color: #fff;
    }
    .scenario-btn-info {
      background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
      color: #fff;
    }
    .scenario-btn-warning {
      background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
      color: #fff;
    }
    .scenario-btn .title {
      font-weight: 700;
      font-size: 1rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .scenario-btn .desc {
      font-size: 0.82rem;
      opacity: 0.92;
      line-height: 1.4;
    }
    .cli-box {
      background: #0f172a;
      border-radius: 12px;
      padding: 16px 20px;
      color: #e2e8f0;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
      font-size: 0.85rem;
      margin-top: 30px;
    }
    .badge-status {
      font-size: 0.78rem;
      font-weight: 700;
      padding: 6px 12px;
      border-radius: 20px;
    }
  </style>
</head>
<body>

  <!-- Top Navigation Header -->
  <header class="top-navbar">
    <div class="container-fluid d-flex align-items-center justify-content-between p-0">
      <a href="{{ url()->current() }}" class="brand-title">
        <i class="fas fa-layer-group text-primary"></i>
        <span>LaunchShop Ecosystem</span>
        <span class="brand-badge">Multi-Product Testing Hub</span>
      </a>

      <div class="nav-actions">
        <a href="{{ url('/subcheck') }}" target="_blank" class="btn-portal-back" title="Trigger Background Expiry Check">
          <i class="fas fa-clock mr-1 text-warning"></i> Run Cron (/subcheck)
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn-portal-back">
          <i class="fas fa-arrow-left mr-1"></i> Admin Portal
        </a>
        <a href="{{ url('/') }}" target="_blank" class="btn-portal-back">
          <i class="fas fa-external-link-alt mr-1"></i> Front Website
        </a>
      </div>
    </div>
  </header>

  <div class="main-container">

    <!-- Action Report Notification -->
    @if (session('test_result'))
      @php $res = session('test_result'); @endphp
      <div class="alert alert-{{ !empty($res['success']) ? 'success' : 'danger' }} alert-dismissible fade show p-4 shadow-sm" role="alert" style="border-radius: 14px; border-left: 6px solid {{ !empty($res['success']) ? '#10b981' : '#ef4444' }}; background: #fff;">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h5 class="alert-heading font-weight-bold mb-0 text-{{ !empty($res['success']) ? 'success' : 'danger' }}">
            <i class="fas fa-check-circle mr-1"></i> {{ __('Action Execution Report') }} [{{ strtoupper($res['product'] ?? 'Product') }}]
          </h5>
          <span class="badge badge-{{ !empty($res['success']) ? 'success' : 'danger' }} px-3 py-2">
            {{ strtoupper($res['action'] ?? 'RESULT') }}
          </span>
        </div>
        <p class="mb-3 font-weight-bold text-dark" style="font-size: 1.05rem;">{{ $res['message'] ?? '' }}</p>
        
        <div class="row align-items-center pt-2 border-top">
          <div class="col-sm-6 mb-2 mb-sm-0">
            <span class="text-muted d-block small font-weight-bold uppercase">{{ __('Target User') }}:</span>
            <strong class="text-dark">{{ $res['user']['name'] ?? ($res['user']['username'] ?? 'User') }}</strong> ({{ $res['user']['phone'] ?? 'No Phone' }})
          </div>
          @if (!empty($res['store_url']))
          <div class="col-sm-6 text-sm-right">
            <a href="{{ $res['store_url'] }}" target="_blank" class="btn btn-primary btn-sm font-weight-bold px-3 py-2" style="border-radius: 8px;">
              <i class="fas fa-external-link-alt mr-1"></i> {{ __('Test Live Website / Store URL') }}
            </a>
          </div>
          @endif
        </div>

        @if (!empty($res['warning_dispatch']))
          <div class="mt-3 pt-2 border-top d-flex gap-2">
            <span class="badge badge-{{ !empty($res['warning_dispatch']['whatsapp_sent']) ? 'success' : 'warning' }} p-2 mr-2">
              <i class="fab fa-whatsapp mr-1"></i> WhatsApp: {{ !empty($res['warning_dispatch']['whatsapp_sent']) ? 'DELIVERED' : 'NOT SENT' }}
            </span>
            <span class="badge badge-{{ !empty($res['warning_dispatch']['email_sent']) ? 'success' : 'warning' }} p-2">
              <i class="fas fa-envelope mr-1"></i> Email: {{ !empty($res['warning_dispatch']['email_sent']) ? 'DELIVERED' : 'NOT SENT' }}
            </span>
          </div>
        @endif

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <!-- Product Switcher Tabs -->
    <div class="product-pills">
      <a class="product-pill {{ $currentProduct === 'launchshop' ? 'active' : '' }}" href="{{ url()->current() }}?product=launchshop">
        <i class="fas fa-shopping-bag"></i> 1. LaunchShop (Ecom Builder)
      </a>
      <a class="product-pill {{ $currentProduct === 'websitebuilder' ? 'active' : '' }}" href="{{ url()->current() }}?product=websitebuilder">
        <i class="fas fa-globe"></i> 2. Website Builder (Websites)
      </a>
      <a class="product-pill {{ $currentProduct === 'whatsappbulk' ? 'active' : '' }}" href="{{ url()->current() }}?product=whatsappbulk">
        <i class="fab fa-whatsapp text-success"></i> 3. WhatsApp Bulk Automation
      </a>
    </div>

    <!-- Main Simulator Box -->
    <div class="content-card">
      <div class="card-topbar">
        <h2>
          <i class="fas fa-stopwatch text-warning"></i>
          @if ($currentProduct === 'websitebuilder')
            Website Builder Subscription Expiry & Gatekeeper Simulator
          @elseif ($currentProduct === 'whatsappbulk')
            WhatsApp Bulk Automation Subscription Expiry Simulator
          @else
            LaunchShop Ecom Subscription Expiry & Gatekeeper Simulator
          @endif
        </h2>
        <span class="badge badge-light text-dark font-weight-bold px-3 py-2" style="border-radius: 8px;">
          MODE: {{ strtoupper($currentProduct) }}
        </span>
      </div>

      <div class="card-body-inner">
        <p class="text-muted mb-4" style="font-size: 0.95rem;">
          {{ __('Simulate plan expiration in 1 to 5 minutes without waiting 30 days. Test immediate WhatsApp/Email reminders, observe live site suspension (Gatekeeper), and verify instant reactivation across all products.') }}
        </p>

        <form action="{{ url()->current() . (str_ends_with(url()->current(), '/execute') ? '' : '/execute') }}" method="POST" id="testForm">
          @csrf
          <input type="hidden" name="product" value="{{ $currentProduct }}">
          <input type="hidden" name="action" id="actionInput" value="simulate-full">

          <div class="row">
            <div class="col-lg-6 col-md-12 mb-3">
              <label class="form-label">
                @if ($currentProduct === 'websitebuilder')
                  {{ __('Select Website Builder Customer / Site') }}
                @elseif ($currentProduct === 'whatsappbulk')
                  {{ __('Select WhatsApp Bulk Platform User') }}
                @else
                  {{ __('Select LaunchShop Store / Merchant') }}
                @endif
                <span class="text-danger">*</span>
              </label>

              <select name="user_id" id="userSelect" class="form-control-custom" required onchange="window.location.href='{{ url()->current() }}?product={{ $currentProduct }}&user_id=' + this.value;">
                <option value="" disabled {{ empty($selectedUser) ? 'selected' : '' }}>-- {{ __('Select Account to Test') }} --</option>
                
                @if ($currentProduct === 'websitebuilder')
                  @foreach ($wbCustomers as $c)
                    <option value="{{ $c->id ?? '' }}" {{ (!empty($selectedUser) && ($selectedUser->id ?? 0) == ($c->id ?? 0)) ? 'selected' : '' }}>
                      {{ $c->name ?? 'User' }} (Subdomain: {{ $c->subdomain ?? 'site' }} | {{ !empty($c->phone) ? $c->phone : 'No Phone' }})
                    </option>
                  @endforeach
                @elseif ($currentProduct === 'whatsappbulk')
                  @foreach ($waUsers as $u)
                    <option value="{{ $u->id ?? '' }}" {{ (!empty($selectedUser) && ($selectedUser->id ?? 0) == ($u->id ?? 0)) ? 'selected' : '' }}>
                      {{ !empty($u->fullname) ? $u->fullname : ($u->username ?? 'User') }} ({{ $u->email ?? '' }} | {{ !empty($u->phone) ? $u->phone : 'No Phone' }})
                    </option>
                  @endforeach
                @else
                  @foreach ($lsUsers as $u)
                    <option value="{{ $u->id ?? '' }}" {{ (!empty($selectedUser) && ($selectedUser->id ?? 0) == ($u->id ?? 0)) ? 'selected' : '' }}>
                      {{ $u->username ?? 'Store' }} ({{ $u->email ?? '' }} | {{ $u->phone ?: ($u->phone_number ?: 'No Phone') }})
                    </option>
                  @endforeach
                @endif
              </select>
            </div>

            <div class="col-lg-6 col-md-12 mb-3">
              <label class="form-label">{{ __('Test Expiry Countdown (Minutes)') }}</label>
              <div class="input-group">
                <input type="number" name="minutes" class="form-control form-control-custom" value="5" min="1" max="1440">
                <div class="input-group-append">
                  <span class="input-group-text font-weight-bold" style="border-radius: 0 10px 10px 0;">{{ __('Minutes') }}</span>
                </div>
              </div>
              <small class="text-muted">{{ __('Default: 5 minutes. Set to 1-2 minutes for immediate fast testing.') }}</small>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">{{ __('Override WhatsApp Phone (Optional)') }}</label>
              <input type="text" name="test_phone" class="form-control-custom" placeholder="e.g. 919876543210 (Country code + phone)" value="{{ $selectedUser ? ($selectedUser->phone ?? ($selectedUser->phone_number ?? '')) : '' }}">
              <small class="text-muted">{{ __('Override to receive the WhatsApp expiry notice directly on your phone.') }}</small>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">{{ __('Override Email Address (Optional)') }}</label>
              <input type="email" name="test_email" class="form-control-custom" placeholder="e.g. yourname@example.com" value="{{ $selectedUser ? ($selectedUser->email ?? '') : '' }}">
              <small class="text-muted">{{ __('Override to receive the warning email and renewal invoice directly in your inbox.') }}</small>
            </div>
          </div>

          <!-- Target Account Detail Card -->
          @if ($selectedUser)
            <div class="target-account-card">
              <div class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                  <small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.72rem;">{{ __('Target Account') }}</small>
                  <span class="h6 font-weight-bold text-dark mb-0">{{ $selectedUser->name ?? ($selectedUser->username ?? 'Account') }}</span>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                  <small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.72rem;">{{ __('Current Validity') }}</small>
                  @if ($currentProduct === 'websitebuilder')
                    <span class="badge badge-info p-2 font-weight-bold">{{ !empty($selectedUser->expire_date) ? \Carbon\Carbon::parse($selectedUser->expire_date)->format('M d, Y h:i A') : 'No Date Set' }}</span>
                  @elseif ($currentProduct === 'whatsappbulk')
                    <span class="badge badge-info p-2 font-weight-bold">{{ !empty($selectedUser->expiration_date) ? (is_numeric($selectedUser->expiration_date) ? date('M d, Y h:i A', $selectedUser->expiration_date) : $selectedUser->expiration_date) : 'Unlimited / No Date' }}</span>
                  @else
                    @if ($latestMembership && !empty($latestMembership->expire_date))
                      <span class="badge badge-info p-2 font-weight-bold">{{ \Carbon\Carbon::parse($latestMembership->expire_date)->format('M d, Y h:i A') }}</span>
                    @else
                      <span class="badge badge-warning p-2 font-weight-bold">{{ __('No Active Membership') }}</span>
                    @endif
                  @endif
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                  <small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.72rem;">{{ __('Gatekeeper Status') }}</small>
                  <span class="badge badge-{{ (isset($selectedUser->status) && $selectedUser->status == 1) ? 'success' : 'danger' }} p-2 font-weight-bold">
                    {{ (isset($selectedUser->status) && $selectedUser->status == 1) ? __('Active (Store Online)') : __('Suspended / Blocked') }}
                  </span>
                </div>
                <div class="col-md-3 text-md-right">
                  @if ($currentProduct === 'websitebuilder' && !empty($selectedUser->subdomain))
                    <a href="{{ url('/website-builder/' . $selectedUser->subdomain) }}" target="_blank" class="btn btn-outline-primary btn-sm font-weight-bold px-3 py-2" style="border-radius: 8px;">
                      <i class="fas fa-globe mr-1"></i> {{ __('Open Live Website') }}
                    </a>
                  @elseif ($currentProduct === 'launchshop' && !empty($selectedUser->username))
                    @php
                      $storeHost = env('WEBSITE_HOST', 'launchshop.youverse.in');
                      $sUrl = "https://{$storeHost}/{$selectedUser->username}";
                    @endphp
                    <a href="{{ $sUrl }}" target="_blank" class="btn btn-outline-primary btn-sm font-weight-bold px-3 py-2" style="border-radius: 8px;">
                      <i class="fas fa-store mr-1"></i> {{ __('Open Live Store') }}
                    </a>
                  @endif
                </div>
              </div>
            </div>
          @endif

          <!-- Action Scenarios -->
          <div class="mt-4 pt-3 border-top">
            <h5 class="font-weight-bold mb-3 text-dark">
              <i class="fas fa-bolt text-warning mr-1"></i> {{ __('Choose Testing Scenario') }}
            </h5>

            <div class="scenario-grid">
              <!-- 1. Full Simulation -->
              <button type="button" class="scenario-btn scenario-btn-primary" onclick="submitAction('simulate-full')" {{ empty($selectedUser) ? 'disabled' : '' }}>
                <span class="title"><i class="fas fa-bolt"></i> 1. {{ __('Full 5-Minute Simulation') }}</span>
                <span class="desc">{{ __('Sets expiry to 5 min from now and instantly dispatches WhatsApp + Email warning notice.') }}</span>
              </button>

              <!-- 2. Expire Now -->
              <button type="button" class="scenario-btn scenario-btn-danger" onclick="submitAction('expire-now')" {{ empty($selectedUser) ? 'disabled' : '' }}>
                <span class="title"><i class="fas fa-ban"></i> 2. {{ __('Expire Now (Block Site / Account)') }}</span>
                <span class="desc">{{ __('Instantly marks plan expired. Gatekeeper immediately blocks public store / site with Suspended Notice.') }}</span>
              </button>

              <!-- 3. Simulate Renewal -->
              <button type="button" class="scenario-btn scenario-btn-success" onclick="submitAction('test-renewal')" {{ (empty($selectedUser) || $currentProduct !== 'launchshop') ? 'disabled' : '' }}>
                <span class="title"><i class="fas fa-credit-card"></i> 3. {{ __('Simulate Renewal (+5m & Invoice)') }}</span>
                <span class="desc">{{ __('Simulates renewal payment, extends validity, generates PDF invoice, and delivers to WhatsApp & Email.') }}</span>
              </button>

              <!-- 4. Admin Reactivate -->
              <button type="button" class="scenario-btn scenario-btn-info" onclick="submitAction('reactivate')" {{ empty($selectedUser) ? 'disabled' : '' }}>
                <span class="title"><i class="fas fa-sync-alt"></i> 4. {{ __('Admin Reactivate / Extend') }}</span>
                <span class="desc">{{ __('Manually reactivates the account, extends validity by 30 days, and unblocks public store access.') }}</span>
              </button>

              <!-- 5. Send Warning Notice Only -->
              <button type="button" class="scenario-btn scenario-btn-warning" onclick="submitAction('send-warning')" {{ empty($selectedUser) ? 'disabled' : '' }}>
                <span class="title"><i class="fab fa-whatsapp"></i> 5. {{ __('Send Expiry Warning Only') }}</span>
                <span class="desc">{{ __('Dispatches the WhatsApp warning message and warning email without altering current expiry date.') }}</span>
              </button>
            </div>
          </div>
        </form>

        <!-- CLI Cheatsheet -->
        <div class="cli-box">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <strong><i class="fas fa-terminal text-success mr-2"></i> {{ __('Artisan CLI Commands for Terminal Testing') }}</strong>
            <span class="badge badge-dark">Fast Mode</span>
          </div>
          <code>php artisan subscription:test {identifier} --product=launchshop --action=simulate-full --minutes=5</code><br>
          <code>php artisan subscription:test {subdomain} --product=wb --action=simulate-full --minutes=5</code><br>
          <code>php artisan subscription:test {username} --product=wa --action=simulate-full --minutes=5</code><br>
          <code>php artisan subscription:test {identifier} --action=expire-now</code>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function submitAction(action) {
      document.getElementById('actionInput').value = action;
      document.getElementById('testForm').submit();
    }
  </script>
</body>
</html>