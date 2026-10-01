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
    <div class="col-lg-6">
      <div class="card p-4 h-100">
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
          <input type="hidden" name="gateway_type" value="razorpay">
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
            <label class="form-check-label fw-semibold" for="rzp_status">Enable Razorpay Online Checkout</label>
          </div>

          <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-save me-1"></i> Save Razorpay Gateway</button>
        </form>
      </div>
    </div>

    <!-- UPI / QR Code Configuration Card -->
    <div class="col-lg-6">
      <div class="card p-4 h-100 border-success border-opacity-25">
        <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
          <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
            <i class="fa-solid fa-qrcode fs-3"></i>
          </div>
          <div>
            <h5 class="fw-bold mb-0">UPI / QR Code Gateway</h5>
            <span class="badge {{ ($upiInfo['status'] ?? 0) ? 'bg-success' : 'bg-secondary' }}">
              {{ ($upiInfo['status'] ?? 0) ? 'Active' : 'Disabled' }}
            </span>
          </div>
        </div>

        <form action="{{ route('website-builder.admin.payment-gateways.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="gateway_type" value="upi">

          <div class="mb-3">
            <label class="form-label fw-semibold">UPI VPA / ID <span class="text-danger">*</span></label>
            <input type="text" name="upi_id" class="form-control" value="{{ $upiInfo['upi_id'] ?? 'launchshop@ybl' }}" placeholder="e.g. 9360157880@ybl" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Account / Merchant Holder Name <span class="text-danger">*</span></label>
            <input type="text" name="holder_name" class="form-control" value="{{ $upiInfo['holder_name'] ?? 'Website Builder' }}" placeholder="e.g. Nooryak LaunchShop" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">UPI QR Code Image (Optional Custom Image)</label>
            @if(!empty($upiInfo['qr_code_image']))
              <div class="mb-2">
                <img src="{{ asset($upiInfo['qr_code_image']) }}" class="img-thumbnail rounded" style="max-height: 100px;">
              </div>
            @endif
            <input type="file" name="qr_code" class="form-control" accept="image/*">
            <small class="text-muted">If uploaded, this QR image will be displayed to customers. Otherwise, a dynamic QR code will be generated live based on the selected plan price.</small>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Payment Instructions for Customer</label>
            <textarea name="instructions" class="form-control" rows="2" placeholder="Instructions for customer when making UPI payment">{{ $upiInfo['instructions'] ?? 'Scan QR code or send to UPI ID, enter UTR number and upload screenshot proof.' }}</textarea>
          </div>

          <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="status" id="upi_status" {{ ($upiInfo['status'] ?? 0) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="upi_status">Enable UPI / QR Code Payment Option at Checkout</label>
          </div>

          <button type="submit" class="btn btn-success w-100"><i class="fa-solid fa-save me-1"></i> Save UPI Gateway Settings</button>
        </form>
      </div>
    </div>
  </div>
@endsection
