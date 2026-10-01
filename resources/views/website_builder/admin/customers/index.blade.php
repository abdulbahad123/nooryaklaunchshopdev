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
            <th>Company</th>
            <th>Active Theme</th>
            <th>Payment & Proof</th>
            <th>Registered</th>
            <th>Secret Login</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($customers as $c)
            @php $purch = $c->latestPurchase; @endphp
            <tr>
              <td><span class="fw-bold text-dark">{{ $c->name }}</span></td>
              <td>
                <div class="fw-semibold small">{{ $c->email }}</div>
                <span class="badge bg-secondary" style="font-size: 11px;">https://{{ $c->subdomain }}</span>
              </td>
              <td>{{ $c->company_name ?? 'Personal' }}</td>
              <td>
                @php
                  $tType = $c->agencySetting->template_type ?? 'digital_agency';
                  $themeLabels = [
                    'digital_agency' => ['Digital Agency', 'bg-primary'],
                    'texigo'         => ['Texigo',         'bg-warning text-dark'],
                    'construction'   => ['Construction',   'bg-danger'],
                    'interior'       => ['Interior',       'bg-success'],
                    'evently'        => ['Evently',        'text-white'],
                  ];
                  $tLabel = $themeLabels[$tType] ?? [ucfirst($tType), 'bg-secondary'];
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
                      @if(!empty($purch->payment_proof))
                        <button type="button" class="btn btn-xs btn-outline-info rounded-pill px-2 py-0" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#proofModal_{{ $purch->id }}">
                          <i class="fa-solid fa-image me-1"></i> View Receipt
                        </button>

                        <!-- Payment Proof Modal -->
                        <div class="modal fade" id="proofModal_{{ $purch->id }}" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header py-2">
                                <h6 class="modal-title fw-bold"><i class="fa-solid fa-receipt me-1 text-success"></i> Payment Receipt Screenshot</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body text-center bg-light">
                                <img src="{{ asset($purch->payment_proof) }}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 400px;">
                                <div class="small text-muted text-start">
                                  <strong>Customer:</strong> {{ $purch->customer_name }} ({{ $purch->customer_email }})<br>
                                  <strong>Payment Method:</strong> {{ strtoupper($purch->payment_method) }}<br>
                                  <strong>UTR / Ref Number:</strong> <code>{{ $purch->transaction_id }}</code><br>
                                  <strong>Amount:</strong> ₹{{ number_format($purch->amount, 2) }}
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
                      @endif

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
                <a href="{{ route('website-builder.admin.customers.secret-login', $c->id) }}" target="_blank" class="btn btn-sm btn-warning fw-bold">
                  <i class="fa-solid fa-key me-1"></i> Secret Login
                </a>
              </td>
              <td>
                <form action="{{ route('website-builder.admin.customers.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Delete client account?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">No registered client accounts found.</td>
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
@endsection
