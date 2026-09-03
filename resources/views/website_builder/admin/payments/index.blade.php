@extends('website_builder.admin.layout')

@section('title', 'Payment Gateways & Razorpay Settings')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Payment Gateways</h3>
      <p class="text-muted small mb-0">Configure payment processor credentials for subscription checkout.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success py-2 mb-3"><i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}</div>
  @endif

  <div class="row g-4">
    <!-- Razorpay Configuration Card -->
    <div class="col-md-8">
      <div class="card p-4">
        <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
          <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
            <i class="fa-solid fa-credit-card fs-3"></i>
          </div>
          <div>
            <h5 class="fw-bold mb-0">Razorpay Payment Gateway</h5>
            <span class="badge {{ ($info['status'] ?? 0) ? 'bg-success' : 'bg-secondary' }}">
              {{ ($info['status'] ?? 0) ? 'Active' : 'Disabled' }}
            </span>
          </div>
        </div>

        <form action="{{ route('website-builder.admin.payment-gateways.update') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-semibold">Razorpay Key ID</label>
            <input type="text" name="key" class="form-control" value="{{ $info['key'] ?? '' }}" placeholder="rzp_live_..." required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Razorpay Key Secret</label>
            <input type="password" name="secret" class="form-control" value="{{ $info['secret'] ?? '' }}" placeholder="••••••••••••••••" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Currency Code</label>
            <input type="text" name="currency" class="form-control" value="{{ $info['currency'] ?? 'INR' }}" placeholder="INR" required>
          </div>

          <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="status" id="rzp_status" {{ ($info['status'] ?? 0) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="rzp_status">Enable Razorpay Checkout for Website Builder Tiers</label>
          </div>

          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Save Gateway Configuration</button>
        </form>
      </div>
    </div>

    <!-- Payment Security Info Card -->
    <div class="col-md-4">
      <div class="card p-4 bg-light border-0">
        <h6 class="fw-bold mb-2"><i class="fa-solid fa-shield-halved text-success me-1"></i> Secure Checkout Standard</h6>
        <p class="text-muted small mb-3">All transactions process through Razorpay's PCI-DSS compliant checkout window with server-side HMAC SHA-256 signature verification.</p>
        <hr>
        <div class="small text-secondary">
          <strong>Supported Currencies:</strong> INR, USD, EUR, etc.<br>
          <strong>Verification Method:</strong> Server-Side SHA256
        </div>
      </div>
    </div>
  </div>
@endsection
