@extends('website_builder.admin.layout')

@section('title', 'Registered Clients & Secret Login')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Registered Clients</h3>
      <p class="text-muted small mb-0">View registered customers and execute secret one-click SSO login into client panels.</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addClientModal"><i class="fa-solid fa-user-plus me-1"></i> Register Client</button>
  </div>

  <div class="card p-4">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Client Name</th>
            <th>Email & Subdomain</th>
            <th>Purchased Plan & Limits</th>
            <th>Company</th>
            <th>Active Theme</th>
            <th>Payment & Proof</th>
            <th>Registered</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($customers as $c)
            @php $purch = $c->latestPurchase; @endphp
            <tr>
              <td>
                <span class="fw-bold text-dark">{{ $c->name }}</span>
              </td>
              <td>
                <div class="fw-semibold small">{{ $c->email }}</div>
                <span class="badge bg-secondary" style="font-size: 11px;">https://{{ $c->subdomain }}</span>
              </td>
              <td>
                @php
                  $planName = $c->package->name ?? null;
                  if (!$planName && $purch) {
                    if (!empty($purch->package_id)) {
                      $pkg = \App\Models\WebsiteBuilder\WbPackage::find($purch->package_id);
                      $planName = $pkg->name ?? null;
                    }
                    if (!$planName && !empty($purch->amount)) {
                      $amt = (float)$purch->amount;
                      $pkgMatch = \App\Models\WebsiteBuilder\WbPackage::where('monthly_price', $amt)->orWhere('yearly_price', $amt)->first();
                      $planName = $pkgMatch->name ?? null;
                    }
                  }
                  if (!$planName) {
                    $firstPkg = \App\Models\WebsiteBuilder\WbPackage::orderBy('id', 'asc')->first();
                    $planName = $firstPkg->name ?? 'Starter Plan';
                  }
                @endphp
                <div class="d-flex flex-column gap-1">
                  <div>
                    <span class="badge bg-indigo-subtle text-primary border border-primary-subtle fw-bold px-2.5 py-1" style="font-size: 11.5px; background: #EEF2FF; color: #4F46E5 !important;">
                      <i class="fa-solid fa-gem me-1" style="color: #6366F1;"></i> {{ $planName }}
                    </span>
                  </div>
                  <div class="text-muted" style="font-size: 10.5px; line-height: 1.3;">
                    <i class="fa-solid fa-layer-group text-primary me-1"></i> Total Allowed Limits:<br>
                    • Projects: <strong>{{ $c->portfolio_limit }}</strong> <span class="text-success">({{ $c->package->portfolio_limit ?? 10 }} + {{ $c->extra_portfolio_limit ?? 0 }} extra)</span><br>
                    • Services: <strong>{{ $c->services_limit }}</strong> <span class="text-success">({{ $c->package->services_limit ?? 10 }} + {{ $c->extra_services_limit ?? 0 }} extra)</span><br>
                    • Blogs: <strong>{{ $c->blog_limit }}</strong> <span class="text-success">({{ $c->package->blog_limit ?? 10 }} + {{ $c->extra_blog_limit ?? 0 }} extra)</span>
                  </div>
                </div>
              </td>
              <td>{{ $c->company_name ?? 'Personal' }}</td>
              <td>
                @php
                  $tType = $c->agencySetting->template_type ?? ($purch->template_slug ?? null);
                  $subLow = strtolower(trim($c->subdomain ?? ''));
                  $cleanEmail = strtolower(trim($c->email ?? ''));
                  if ($subLow === 'agentll' || $subLow === 'agent4' || in_array($cleanEmail, ['pevico4644@hopefunder.com', 'coyakef452@moblha.com'])) {
                      $tType = 'evently';
                  } elseif (empty($tType) || $tType === 'digital_agency') {
                      if (!empty($purch->template_slug) && $purch->template_slug !== 'digital_agency') {
                          $tType = $purch->template_slug;
                      } elseif (str_contains($subLow, 'evently') || str_contains($subLow, 'event')) {
                          $tType = 'evently';
                      } elseif (str_contains($subLow, 'interior')) {
                          $tType = 'interior';
                      } elseif (str_contains($subLow, 'texigo') || str_contains($subLow, 'taxi')) {
                          $tType = 'texigo';
                      } elseif (str_contains($subLow, 'construction') || str_contains($subLow, 'build')) {
                          $tType = 'construction';
                      } else {
                          $tType = 'digital_agency';
                      }
                  }
                  $themeLabels = [
                    'digital_agency' => ['Digital Agency', 'bg-primary'],
                    'texigo'         => ['Texigo',         'bg-warning text-dark'],
                    'construction'   => ['Construction',   'bg-danger'],
                    'interior'       => ['Interior',       'bg-success'],
                    'evently'        => ['Evently',        'text-white'],
                  ];
                  $tLabel = $themeLabels[$tType] ?? [ucfirst(str_replace('_', ' ', $tType)), 'bg-secondary'];
                  $tStyle = ($tType === 'evently') ? 'background:#6f42c1;' : '';
                @endphp
                <span class="badge {{ $tLabel[1] }}" style="{{ $tStyle }}">{{ $tLabel[0] }}</span>
              </td>
              <td>
                @if($purch)
                  <div class="d-flex flex-column gap-1">
                    <div>
                      <span class="badge {{ strtolower($purch->payment_method ?? '') === 'upi' ? 'bg-success' : 'bg-primary' }}">
                        <i class="{{ strtolower($purch->payment_method ?? '') === 'upi' ? 'fa-solid fa-qrcode' : 'fa-solid fa-credit-card' }} me-1"></i>
                        {{ strtoupper($purch->payment_method ?? 'Razorpay') }}
                      </span>
                      @if(($purch->status ?? '') === 'Pending Verification')
                        <span class="badge bg-warning text-dark">Pending Approval</span>
                      @else
                        <span class="badge bg-success">Verified</span>
                      @endif
                    </div>
                    @if(!empty($purch->transaction_id))
                      <small class="text-muted font-monospace" style="font-size: 11px;">Ref/UTR: {{ $purch->transaction_id }}</small>
                    @endif
                    
                    <div class="d-flex align-items-center gap-1 mt-1">
                      <button type="button" class="btn btn-xs btn-outline-info rounded-pill px-2 py-0" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#proofModal_{{ $purch->id }}">
                        <i class="fa-solid fa-receipt me-1"></i> View Receipt
                      </button>

                      <!-- Payment Proof Modal -->
                      <div class="modal fade" id="proofModal_{{ $purch->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                          <div class="modal-content text-start">
                            <div class="modal-header py-2">
                              <h6 class="modal-title fw-bold"><i class="fa-solid fa-receipt me-1 text-success"></i> Payment Receipt Details</h6>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center bg-light">
                              @if(!empty($purch->payment_proof))
                                <img src="{{ asset($purch->payment_proof) }}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 400px;" alt="Receipt Image">
                              @else
                                <div class="p-3 bg-white border rounded shadow-sm mb-3 text-center" style="border-top: 4px solid #10b981 !important;">
                                  <div class="d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; border-radius: 50%; background: #ecfdf5; color: #10b981;">
                                    <i class="fa-solid fa-receipt fs-4"></i>
                                  </div>
                                  <h6 class="fw-bold text-dark mb-1">{{ __('Digital Payment Receipt') }}</h6>
                                  <p class="small text-muted mb-2">{{ __('Online Electronic Invoice & Payment Confirmation') }}</p>
                                  <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold" style="font-size: 11px;">
                                    <i class="fa-solid fa-circle-check me-1"></i> {{ __('Verified Online Payment') }}
                                  </span>
                                </div>
                              @endif
                              <div class="small bg-white p-3 rounded border text-start">
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                  <span class="text-muted">{{ __('Receipt No / Order ID') }}:</span>
                                  <span class="fw-bold font-monospace text-primary">#{{ $purch->id }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                  <span class="text-muted">{{ __('Customer Name') }}:</span>
                                  <span class="fw-bold text-dark">{{ $purch->customer_name }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                  <span class="text-muted">{{ __('Customer Email') }}:</span>
                                  <span class="fw-bold text-dark">{{ $purch->customer_email }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                  <span class="text-muted">{{ __('Plan / Item') }}:</span>
                                  <span class="badge bg-light text-dark border">{{ $purch->template_name ?: 'Subscription Plan' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                  <span class="text-muted">{{ __('Payment Method') }}:</span>
                                  <span class="badge bg-secondary">{{ strtoupper($purch->payment_method) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                  <span class="text-muted">{{ __('Ref / UTR / Razorpay ID') }}:</span>
                                  <code class="fw-bold text-dark">{{ $purch->transaction_id ?: ($purch->razorpay_payment_id ?: 'N/A') }}</code>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                  <span class="text-muted">{{ __('Paid Amount') }}:</span>
                                  <span class="fw-bold text-success fs-6">₹{{ number_format($purch->amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                  <span class="text-muted">{{ __('Status') }}:</span>
                                  <span class="badge {{ ($purch->status ?? '') === 'Pending Verification' ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ $purch->status }}
                                  </span>
                                </div>
                              </div>
                            </div>
                            @if(($purch->status ?? '') === 'Pending Verification')
                              <div class="modal-footer py-2">
                                <form action="{{ route('website-builder.admin.customers.approve-payment', $purch->id) }}" method="POST">
                                  @csrf
                                  <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-circle-check me-1"></i> Verify & Approve Payment</button>
                                </form>
                              </div>
                            @endif
                          </div>
                        </div>
                      </div>

                      @if(($purch->status ?? '') === 'Pending Verification')
                        <form action="{{ route('website-builder.admin.customers.approve-payment', $purch->id) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-xs btn-success rounded-pill px-2 py-0" style="font-size: 11px;" title="Approve Payment">
                            <i class="fa-solid fa-check me-1"></i> Approve
                          </button>
                        </form>
                      @endif
                    </div>
                  </div>
                @else
                  <span class="text-muted small">No payment record</span>
                @endif
              </td>
              <td style="font-size:12px; white-space:nowrap;">{{ $c->created_at ? $c->created_at->format('M d, Y') : 'N/A' }}<br><span class="text-muted">{{ $c->created_at ? $c->created_at->format('h:i A') : '' }}</span></td>
              <td>
                <div class="dropdown action-dropdown d-inline-block">
                  <button class="btn btn-sm btn-primary dropdown-toggle fw-bold px-3 py-1.5 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px; font-size: 12.5px; background: #6366f1; border-color: #6366f1;">
                    <i class="fa-solid fa-gear me-1"></i> Action
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-2" style="border-radius: 12px; font-size: 13px; min-width: 175px; z-index: 1050;">
                    <li>
                      <a class="dropdown-item py-2 px-3 fw-medium d-flex align-items-center gap-2 text-dark" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#clientDetailModal_{{ $c->id }}">
                        <i class="fa-solid fa-circle-info text-info" style="width: 16px;"></i> <span>Details</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item py-2 px-3 fw-medium d-flex align-items-center gap-2 text-dark" href="{{ route('website-builder.admin.customers.secret-login', $c->id) }}" target="_blank">
                        <i class="fa-solid fa-key text-warning" style="width: 16px;"></i> <span>Secret Login</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item py-2 px-3 fw-medium d-flex align-items-center gap-2 text-dark" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#extraLimitsModal_{{ $c->id }}">
                        <i class="fa-solid fa-sliders text-primary" style="width: 16px;"></i> <span>Extra Limit</span>
                      </a>
                    </li>
                    <li>
                      @if((int)($c->status ?? 1) === 1)
                        <button type="submit" form="suspendForm_{{ $c->id }}" class="dropdown-item py-2 px-3 fw-medium d-flex align-items-center gap-2 text-warning bg-transparent border-0 w-100 text-start">
                          <i class="fa-solid fa-ban text-warning" style="width: 16px;"></i> <span>Suspend</span>
                        </button>
                      @else
                        <button type="submit" form="suspendForm_{{ $c->id }}" class="dropdown-item py-2 px-3 fw-medium d-flex align-items-center gap-2 text-success bg-transparent border-0 w-100 text-start">
                          <i class="fa-solid fa-circle-check text-success" style="width: 16px;"></i> <span>Unsuspend</span>
                        </button>
                      @endif
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                      <button type="submit" form="deleteForm_{{ $c->id }}" class="dropdown-item py-2 px-3 fw-medium d-flex align-items-center gap-2 text-danger bg-transparent border-0 w-100 text-start">
                        <i class="fa-solid fa-trash text-danger" style="width: 16px;"></i> <span>Delete</span>
                      </button>
                    </li>
                  </ul>
                </div>

                {{-- Hidden forms for Suspend & Delete --}}
                <form id="suspendForm_{{ $c->id }}" action="{{ route('website-builder.admin.customers.toggle-suspend', $c->id) }}" method="POST" onsubmit="return confirm('{{ (int)($c->status ?? 1) === 1 ? 'Suspend customer account? Live website and dashboard will be suspended and notices sent.' : 'Unsuspend customer account? Live website and dashboard will be restored.' }}');" class="d-none">
                  @csrf
                </form>
                <form id="deleteForm_{{ $c->id }}" action="{{ route('website-builder.admin.customers.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this client account?');" class="d-none">
                  @csrf
                  @method('DELETE')
                </form>

                <!-- Client Details Modal (LaunchShop Admin Style) -->
                <div class="modal fade text-start" id="clientDetailModal_{{ $c->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                      <div class="modal-header py-3 px-4 border-0" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: #ffffff;">
                        <div class="d-flex align-items-center gap-3">
                          <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fa-solid fa-id-card"></i>
                          </div>
                          <div>
                            <h5 class="modal-title fw-bold text-white mb-0">Client Details & Plan Overview</h5>
                            <small class="text-white-50">Registered customer account profile & package allocations</small>
                          </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <div class="modal-body p-4 bg-light">
                        <div class="row g-3">
                          <!-- Left Column: Profile Overview -->
                          <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-3 p-3 text-center h-100 bg-white">
                              <div class="position-relative d-inline-block mx-auto mb-2" style="width: 72px; height: 72px;">
                                <div style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 700; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);">
                                  {{ strtoupper(substr($c->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="position-absolute bottom-0 end-0 p-1 bg-white rounded-circle shadow-sm">
                                  <i class="fa-solid fa-shield-check text-success" style="font-size: 14px;"></i>
                                </span>
                              </div>

                              <h6 class="fw-bold text-dark mb-0 fs-15">{{ $c->name }}</h6>
                              <span class="text-muted small d-block mb-2">{{ $c->email }}</span>

                              <div class="mb-3">
                                @if((int)($c->status ?? 1) === 1)
                                  <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill" style="font-size: 11.5px;">
                                    <i class="fa-solid fa-circle-check me-1"></i> Active Account
                                  </span>
                                @else
                                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill" style="font-size: 11.5px;">
                                    <i class="fa-solid fa-ban me-1"></i> Suspended
                                  </span>
                                @endif
                              </div>

                              <div class="border-top pt-3 text-start d-flex flex-column gap-2.5" style="font-size: 12.5px;">
                                <div class="d-flex align-items-center gap-2">
                                  <div style="width: 32px; height: 32px; min-width: 32px; border-radius: 8px; background: #F3E8FF; color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                    <i class="fa-solid fa-calendar-check"></i>
                                  </div>
                                  <div>
                                    <span class="text-muted d-block" style="font-size: 10.5px; font-weight: 600;">REGISTERED CUSTOMER DATE</span>
                                    <span class="fw-bold text-dark">{{ $c->created_at ? $c->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                                  </div>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                  <div style="width: 32px; height: 32px; min-width: 32px; border-radius: 8px; background: #DBEAFE; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                    <i class="fa-solid fa-id-badge"></i>
                                  </div>
                                  <div>
                                    <span class="text-muted d-block" style="font-size: 10.5px; font-weight: 600;">CLIENT ID</span>
                                    <span class="fw-bold text-dark">#WB-{{ 1000 + $c->id }}</span>
                                  </div>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                  <div style="width: 32px; height: 32px; min-width: 32px; border-radius: 8px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                    <i class="fa-solid fa-crown"></i>
                                  </div>
                                  <div>
                                    <span class="text-muted d-block" style="font-size: 10.5px; font-weight: 600;">PURCHASED PLAN</span>
                                    <span class="fw-bold text-dark">{{ $planName }}</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Right Column: Details & Resource Breakdown -->
                          <div class="col-lg-8">
                            <div class="d-flex flex-column gap-3">
                              <!-- Workspace & Company Card -->
                              <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 fs-13 text-uppercase" style="letter-spacing: 0.5px;">
                                  <i class="fa-solid fa-building text-primary"></i> Client & Workspace Info
                                </h6>
                                <div class="row g-2" style="font-size: 12.5px;">
                                  <div class="col-sm-6">
                                    <span class="text-muted d-block" style="font-size: 11px;">Company Name</span>
                                    <span class="fw-bold text-dark">{{ $c->company_name ?? 'Personal' }}</span>
                                  </div>
                                  <div class="col-sm-6">
                                    <span class="text-muted d-block" style="font-size: 11px;">Active Theme</span>
                                    <span class="badge {{ $tLabel[1] }}" style="{{ $tStyle }}">{{ $tLabel[0] }}</span>
                                  </div>
                                  <div class="col-sm-12 pt-1">
                                    <span class="text-muted d-block" style="font-size: 11px;">Subdomain / Live Website</span>
                                    <a href="{{ route('website-builder.subdomain.site', ['subdomain' => $c->subdomain]) }}" target="_blank" class="text-primary fw-bold text-decoration-none">
                                      https://{{ $c->subdomain }} <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 10px;"></i>
                                    </a>
                                  </div>
                                </div>
                              </div>

                              <!-- Purchased Plan Limits Card (LaunchShop Admin Style) -->
                              <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                  <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 fs-13 text-uppercase" style="letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-layer-group text-primary"></i> Purchased Plan Limits
                                  </h6>
                                  <span class="badge bg-indigo-subtle text-primary fw-bold" style="background: #EEF2FF; color: #4F46E5 !important;">
                                    {{ $planName }}
                                  </span>
                                </div>
                                <p class="text-muted small mb-3" style="font-size: 11.5px;">
                                  Resource allocations including base plan features plus any admin-granted extra limits.
                                </p>

                                <div class="row g-2">
                                  <!-- Projects Limit -->
                                  <div class="col-md-4">
                                    <div class="p-2.5 rounded-3 border bg-light text-center">
                                      <span class="text-muted d-block" style="font-size: 11px; font-weight: 600;">PORTFOLIO PROJECTS</span>
                                      <h4 class="fw-bold text-primary mb-0 mt-1">{{ $c->portfolio_limit }}</h4>
                                      <small class="text-muted" style="font-size: 10.5px;">
                                        Base: <strong>{{ $c->package->portfolio_limit ?? 10 }}</strong> + Extra: <strong class="text-success">{{ $c->extra_portfolio_limit ?? 0 }}</strong>
                                      </small>
                                    </div>
                                  </div>

                                  <!-- Services Limit -->
                                  <div class="col-md-4">
                                    <div class="p-2.5 rounded-3 border bg-light text-center">
                                      <span class="text-muted d-block" style="font-size: 11px; font-weight: 600;">SERVICES LISTED</span>
                                      <h4 class="fw-bold text-success mb-0 mt-1">{{ $c->services_limit }}</h4>
                                      <small class="text-muted" style="font-size: 10.5px;">
                                        Base: <strong>{{ $c->package->services_limit ?? 10 }}</strong> + Extra: <strong class="text-success">{{ $c->extra_services_limit ?? 0 }}</strong>
                                      </small>
                                    </div>
                                  </div>

                                  <!-- Blog Limit -->
                                  <div class="col-md-4">
                                    <div class="p-2.5 rounded-3 border bg-light text-center">
                                      <span class="text-muted d-block" style="font-size: 11px; font-weight: 600;">BLOG ARTICLES</span>
                                      <h4 class="fw-bold text-info mb-0 mt-1">{{ $c->blog_limit }}</h4>
                                      <small class="text-muted" style="font-size: 10.5px;">
                                        Base: <strong>{{ $c->package->blog_limit ?? 10 }}</strong> + Extra: <strong class="text-success">{{ $c->extra_blog_limit ?? 0 }}</strong>
                                      </small>
                                    </div>
                                  </div>
                                </div>

                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center" style="font-size: 11.5px;">
                                  <span class="text-muted">Plan Expiry Date:</span>
                                  <span class="fw-bold text-dark">
                                    {{ !empty($c->expire_date) ? \Carbon\Carbon::parse($c->expire_date)->format('M d, Y h:i A') : 'Unlimited (Never Expires)' }}
                                  </span>
                                </div>
                              </div>

                              <!-- Payment Record Card -->
                              <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                                <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2 fs-13 text-uppercase" style="letter-spacing: 0.5px;">
                                  <i class="fa-solid fa-receipt text-primary"></i> Payment Record & Proof
                                </h6>
                                @if($purch)
                                  <div class="row g-2 align-items-center" style="font-size: 12.5px;">
                                    <div class="col-sm-4">
                                      <span class="text-muted d-block" style="font-size: 11px;">Payment Method</span>
                                      <span class="badge {{ strtolower($purch->payment_method ?? '') === 'upi' ? 'bg-success' : 'bg-primary' }}">
                                        {{ strtoupper($purch->payment_method ?? 'Razorpay') }}
                                      </span>
                                    </div>
                                    <div class="col-sm-4">
                                      <span class="text-muted d-block" style="font-size: 11px;">Amount Paid</span>
                                      <span class="fw-bold text-dark">₹{{ number_format($purch->amount, 2) }}</span>
                                    </div>
                                    <div class="col-sm-4">
                                      <span class="text-muted d-block" style="font-size: 11px;">Verification</span>
                                      <span class="badge {{ ($purch->status ?? '') === 'Pending Verification' ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ ($purch->status ?? '') === 'Pending Verification' ? 'Pending Approval' : 'Verified' }}
                                      </span>
                                    </div>
                                    @if(!empty($purch->transaction_id))
                                      <div class="col-sm-12 pt-1">
                                        <span class="text-muted d-block" style="font-size: 11px;">Reference / UTR Number</span>
                                        <code class="text-dark bg-light px-2 py-1 rounded border">{{ $purch->transaction_id }}</code>
                                      </div>
                                    @endif
                                  </div>
                                @else
                                  <span class="text-muted small">No payment record associated with this account.</span>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="modal-footer bg-white border-top py-2.5 px-4 d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-3" data-bs-dismiss="modal">Close</button>
                        <div class="d-flex align-items-center gap-2">
                          <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-3" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#extraLimitsModal_{{ $c->id }}">
                            <i class="fa-solid fa-sliders me-1"></i> Extra Limits
                          </button>
                          <a href="{{ route('website-builder.admin.customers.secret-login', $c->id) }}" target="_blank" class="btn btn-sm btn-warning fw-bold rounded-3 text-dark">
                            <i class="fa-solid fa-key me-1"></i> Secret Login
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Extra Limits Modal -->
                <div class="modal fade text-start" id="extraLimitsModal_{{ $c->id }}" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                      <form action="{{ route('website-builder.admin.customers.extra-limits', $c->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-light border-0 py-3 rounded-top-4">
                          <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-sliders text-primary me-2"></i>Custom Extra Limits: {{ $c->name }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                          <p class="small text-muted mb-3">Grant specific additional item limits to this registered client on top of their base package plan.</p>

                          <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Extra Portfolio Items Limit</label>
                            <input type="number" class="form-control rounded-3" name="extra_portfolio_limit" value="{{ $c->extra_portfolio_limit ?? 0 }}" min="0">
                            <span class="form-text text-muted" style="font-size: 11px;">Base Plan: {{ $c->package->portfolio_limit ?? 10 }} | Total: {{ $c->portfolio_limit }}</span>
                          </div>

                          <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Extra Services Limit</label>
                            <input type="number" class="form-control rounded-3" name="extra_services_limit" value="{{ $c->extra_services_limit ?? 0 }}" min="0">
                            <span class="form-text text-muted" style="font-size: 11px;">Base Plan: {{ $c->package->services_limit ?? 10 }} | Total: {{ $c->services_limit }}</span>
                          </div>

                          <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Extra Blog Articles Limit</label>
                            <input type="number" class="form-control rounded-3" name="extra_blog_limit" value="{{ $c->extra_blog_limit ?? 0 }}" min="0">
                            <span class="form-text text-muted" style="font-size: 11px;">Base Plan: {{ $c->package->blog_limit ?? 10 }} | Total: {{ $c->blog_limit }}</span>
                          </div>
                        </div>
                        <div class="modal-footer bg-light border-0 py-3 rounded-bottom-4">
                          <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-bold">Save Custom Limits</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">No registered client accounts found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $customers->links() }}
  </div>

  <!-- Register Client Modal -->
  <div class="modal fade" id="addClientModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('website-builder.admin.customers.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Register Client Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Company Name (Optional)</label>
              <input type="text" class="form-control" name="company_name">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create Client Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <style>
    .action-dropdown:hover .dropdown-menu {
      display: block;
      margin-top: 0;
    }
    .table-responsive {
      min-height: 380px;
    }
    .action-dropdown .dropdown-item:hover {
      background-color: #f1f5f9;
    }
  </style>
@endsection
