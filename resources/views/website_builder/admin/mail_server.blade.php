@extends('website_builder.admin.layout')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
  <div>
    <h3 class="fw-bold text-dark mb-1">
      <i class="fa-solid fa-server me-2 text-primary"></i> Mail Server (SMTP) Settings
    </h3>
    <p class="text-muted small mb-0">Manage outbound SMTP credentials for Website Builder landing inquiries, customer OTP verification, invoices, and password resets.</p>
  </div>
  <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold" onclick="restoreSaasDefaults()">
    <i class="fa-solid fa-rotate-left me-1"></i> Load saasreselling Defaults
  </button>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="fa-solid fa-circle-check fs-5 text-success"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
    <div>{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
    <ul class="mb-0 ps-3">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="row g-4">
  <!-- LEFT COLUMN: SMTP CONFIGURATION FORM -->
  <div class="col-lg-8">
    <form action="{{ route('website-builder.admin.mail-server.update') }}" method="POST">
      @csrf
      <div class="card p-4 shadow-sm border-0 rounded-4 mb-4">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
          <h5 class="fw-bold text-dark mb-0">
            <i class="fa-solid fa-envelope-circle-check me-2 text-success"></i> SMTP Server Configuration
          </h5>
          <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" role="switch" id="is_smtp" name="is_smtp" value="1" {{ old('is_smtp', $setting->is_smtp ?? '1') == '1' ? 'checked' : '' }}>
            <label class="form-check-label fw-bold text-dark small" for="is_smtp">Enable SMTP Delivery</label>
          </div>
        </div>

        <!-- HOST & PORT -->
        <div class="row g-3 mb-3">
          <div class="col-md-8">
            <label for="smtp_host" class="form-label fw-semibold text-secondary small">SMTP Host <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-globe"></i></span>
              <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="{{ old('smtp_host', $setting->smtp_host ?? 'mail.saasreselling.com') }}" placeholder="mail.saasreselling.com" required>
            </div>
            <small class="text-muted">Domain mail host for outbound connections.</small>
          </div>
          <div class="col-md-4">
            <label for="smtp_port" class="form-label fw-semibold text-secondary small">Port <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-network-wired"></i></span>
              <input type="number" id="smtp_port" name="smtp_port" class="form-control" value="{{ old('smtp_port', $setting->smtp_port ?? '465') }}" placeholder="465" required>
            </div>
            <small class="text-muted">SSL: 465, TLS: 587</small>
          </div>
        </div>

        <!-- ENCRYPTION -->
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="smtp_encryption" class="form-label fw-semibold text-secondary small">Encryption Type <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-shield-halved"></i></span>
              <select id="smtp_encryption" name="smtp_encryption" class="form-select" required>
                <option value="ssl" {{ old('smtp_encryption', strtolower($setting->smtp_encryption ?? 'ssl')) === 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                <option value="tls" {{ old('smtp_encryption', strtolower($setting->smtp_encryption ?? 'ssl')) === 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
                <option value="none" {{ old('smtp_encryption', strtolower($setting->smtp_encryption ?? 'ssl')) === 'none' ? 'selected' : '' }}>None (Port 25)</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label for="smtp_username" class="form-label fw-semibold text-secondary small">SMTP Username <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user"></i></span>
              <input type="text" id="smtp_username" name="smtp_username" class="form-control" value="{{ old('smtp_username', $setting->smtp_username ?? 'admin@saasreselling.com') }}" placeholder="admin@saasreselling.com" required>
            </div>
          </div>
        </div>

        <!-- PASSWORD -->
        <div class="mb-4">
          <label for="smtp_password" class="form-label fw-semibold text-secondary small">SMTP Password <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
            <input type="password" id="smtp_password" name="smtp_password" class="form-control" value="{{ old('smtp_password', $setting->smtp_password ?? 'Admin@nooryak') }}" placeholder="Enter password" required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()">
              <i class="fa-solid fa-eye" id="password-toggle-icon"></i>
            </button>
          </div>
        </div>

        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-2">
          <i class="fa-solid fa-paper-plane me-2 text-info"></i> Sender Identity (From Header)
        </h5>

        <!-- FROM ADDRESS & FROM NAME -->
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="smtp_from_address" class="form-label fw-semibold text-secondary small">From Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-at"></i></span>
              <input type="email" id="smtp_from_address" name="smtp_from_address" class="form-control" value="{{ old('smtp_from_address', $setting->smtp_from_address ?? 'admin@saasreselling.com') }}" placeholder="admin@saasreselling.com" required>
            </div>
          </div>
          <div class="col-md-6">
            <label for="smtp_from_name" class="form-label fw-semibold text-secondary small">From Sender Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-signature"></i></span>
              <input type="text" id="smtp_from_name" name="smtp_from_name" class="form-control" value="{{ old('smtp_from_name', $setting->smtp_from_name ?? 'Website Builder') }}" placeholder="Website Builder" required>
            </div>
          </div>
        </div>

        <div class="pt-3 border-top text-end">
          <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border:none;">
            <i class="fa-solid fa-floppy-disk me-1"></i> Save SMTP Configuration
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- RIGHT COLUMN: QUICK TEST EMAIL & INFO -->
  <div class="col-lg-4">
    <!-- TEST EMAIL BOX -->
    <div class="card p-4 shadow-sm border-0 rounded-4 mb-4" style="background: #ffffff; border-top: 4px solid #4f46e5 !important;">
      <h6 class="fw-bold mb-2 text-dark"><i class="fa-solid fa-envelope-circle-check text-primary me-2"></i> Quick Test Mail</h6>
      <p class="text-muted small mb-3">Send a test email using current settings to verify server connectivity.</p>
      
      <form action="{{ route('website-builder.admin.mail-server.test') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label class="form-label fw-semibold small text-secondary">Recipient Email</label>
          <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-inbox"></i></span>
            <input type="email" name="test_email" class="form-control form-control-sm" value="{{ old('test_email', 'lenog41980@agaexpress.com') }}" placeholder="test@example.com" required>
          </div>
        </div>
        <button type="submit" class="btn btn-outline-success btn-sm w-100 rounded-pill fw-bold">
          <i class="fa-solid fa-paper-plane me-1"></i> Send Test Message
        </button>
      </form>
    </div>

    <!-- HOW IT WORKS INFO CARD -->
    <div class="card p-4 shadow-sm border-0 rounded-4 bg-light">
      <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-circle-info text-info me-2"></i> Mail Engine Integrations</h6>
      <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
        <li><b>Landing Inquiries:</b> Dispatches customer contact requests submitted via agency landing pages.</li>
        <li><b>Client Signups & OTP:</b> Sends 6-digit WhatsApp/Email verification codes on checkout.</li>
        <li><b>Invoices & Receipts:</b> Automatically generates and emails branded purchase confirmation receipts.</li>
        <li><b>Password Reset:</b> Dispatches password recovery links directly to client emails.</li>
        <li><b>Multi-tier Failover:</b> If external SMTP connection handshakes time out, the server automatically falls back to local delivery so no email is dropped.</li>
      </ul>
    </div>
  </div>
</div>

<script>
function togglePasswordVisibility() {
  const passInput = document.getElementById('smtp_password');
  const icon = document.getElementById('password-toggle-icon');
  if (passInput.type === 'password') {
    passInput.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    passInput.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}

function restoreSaasDefaults() {
  document.getElementById('smtp_host').value = 'mail.saasreselling.com';
  document.getElementById('smtp_port').value = '465';
  document.getElementById('smtp_encryption').value = 'ssl';
  document.getElementById('smtp_username').value = 'admin@saasreselling.com';
  document.getElementById('smtp_password').value = 'Admin@nooryak';
  document.getElementById('smtp_from_address').value = 'admin@saasreselling.com';
  document.getElementById('smtp_from_name').value = 'Website Builder';
  document.getElementById('is_smtp').checked = true;
}
</script>
@endsection
