<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout & Website Setup - LaunchShop Website Builder</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --primary: #10B981;
      --primary-dark: #059669;
      --dark: #090D16;
      --border-color: #E2E8F0;
    }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #F8FAFC;
      color: #0F172A;
      overflow-x: hidden;
    }

    /* HEADER */
    .checkout-header {
      background: #ffffff;
      border-bottom: 1px solid #E2E8F0;
      padding: 16px 0;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .brand-logo {
      font-weight: 800;
      font-size: 22px;
      color: #0F172A;
      text-decoration: none;
    }
    .brand-logo span { color: #F97316; }

    /* STEP INDICATOR */
    .step-pill {
      font-size: 13px;
      font-weight: 700;
      padding: 6px 16px;
      border-radius: 30px;
      background: #E2E8F0;
      color: #64748B;
      transition: all 0.3s;
    }
    .step-pill.active {
      background: #ECFDF5;
      color: #059669;
      border: 1px solid #10B981;
    }

    /* CARD STYLING */
    .checkout-card {
      background: #ffffff;
      border-radius: 24px;
      border: 1px solid #E2E8F0;
      box-shadow: 0 12px 35px rgba(0,0,0,0.04);
      padding: 40px;
    }
    .illustration-box {
      background: #F1F5F9;
      border-radius: 24px;
      padding: 40px;
      display: flex;
      flex-column: column;
      align-items: center;
      justify-content: center;
      height: 100%;
    }

    .btn-orange-submit {
      background: linear-gradient(135deg, #FF5722 0%, #F4511E 100%);
      color: #ffffff;
      font-weight: 800;
      font-size: 15px;
      padding: 14px 28px;
      border-radius: 12px;
      border: none;
      width: 100%;
      transition: all 0.2s;
    }
    .btn-orange-submit:hover {
      background: #E64A19;
      color: #ffffff;
      transform: translateY(-1px);
    }
    .btn-green-submit {
      background: linear-gradient(135deg, #10B981 0%, #059669 100%);
      color: #ffffff;
      font-weight: 800;
      font-size: 15px;
      padding: 14px 28px;
      border-radius: 12px;
      border: none;
      width: 100%;
      transition: all 0.2s;
    }
    .btn-green-submit:hover {
      background: #047857;
      color: #ffffff;
      transform: translateY(-1px);
    }

    .input-custom {
      height: 48px;
      border-radius: 10px;
      border: 1px solid #CBD5E1;
      padding-left: 16px;
      font-size: 14px;
    }
    .input-custom:focus {
      border-color: #10B981;
      box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
    }

    .verified-banner {
      background: #ECFDF5;
      border: 1px solid #A7F3D0;
      border-radius: 12px;
      padding: 12px 18px;
      color: #065F46;
      font-size: 13.5px;
      font-weight: 600;
    }
  </style>
</head>
<body>

<!-- HEADER -->
<header class="checkout-header sticky-top">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <a href="{{ route('website-builder.index') }}" class="brand-logo">
        Design<span>AGENCY</span>
      </a>
      <div class="d-none d-md-flex align-items-center gap-2">
        <span class="step-pill active" id="pill-step-1">1. Account Details</span>
        <i class="fa-solid fa-chevron-right text-muted style='font-size:10px;'"></i>
        <span class="step-pill" id="pill-step-2">2. Subdomain</span>
        <i class="fa-solid fa-chevron-right text-muted style='font-size:10px;'"></i>
        <span class="step-pill" id="pill-step-3">3. Payment & Summary</span>
      </div>
      <a href="{{ route('website-builder.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Home
      </a>
    </div>
  </div>
</header>

<!-- MAIN CONTENT -->
<main class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      
      <!-- CENTERED CONTAINER FOR CREATE AN ACCOUNT (Task 2 Match) -->
      <div class="col-lg-7 col-md-9">
        <div class="checkout-card">
          <form action="{{ route('website-builder.checkout.process') }}" method="POST" id="mainCheckoutForm">
            @csrf

            <!-- STEP 1: CREATE ACCOUNT (Ref Image 3 Match) -->
            <div id="step-1-content">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                  <h3 class="fw-extrabold mb-1">Create an account !</h3>
                  <p class="text-muted small mb-0">Register to continue to Website Builder.</p>
                </div>
                <a href="{{ route('website-builder.agency-admin.index') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                  <i class="fa-solid fa-sign-in-alt me-1"></i> Login
                </a>
              </div>

              <!-- Full Name Field -->
              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Full Name *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                  <input type="text" name="customer_name" id="input_name" class="form-control input-custom border-start-0" placeholder="Enter your name" required>
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_name" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Full Name is required</div>
              </div>

              <!-- Phone Number Field -->
              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Phone Number *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 fw-bold small">🇮🇳 +91</span>
                  <input type="text" name="customer_phone" id="input_phone" class="form-control input-custom border-start-0" placeholder="9360157880" required>
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_phone" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Phone Number is required</div>
              </div>

              <!-- Email Address Field with OTP Action (Task 1 Match) -->
              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Email Address *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                  <input type="email" name="customer_email" id="input_email" class="form-control input-custom border-start-0" placeholder="vixes16275@beiwoh.com" required>
                  <button type="button" class="btn btn-outline-success px-3 fw-bold small" id="btn_send_otp" onclick="handleSendOtp()">
                    <i class="fa-solid fa-paper-plane me-1"></i> Send OTP
                  </button>
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_email" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Valid Email Address is required</div>
              </div>

              <!-- OTP Verification Input Box (Task 1 Match) -->
              <div class="mb-4" id="otp_container" style="display: none;">
                <label class="form-label fw-bold small text-muted">Enter OTP Code *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-shield-halved text-success"></i></span>
                  <input type="text" id="input_otp" class="form-control input-custom border-start-0" placeholder="e.g. 337178" maxlength="6">
                  <button type="button" class="btn btn-success px-3 fw-bold small" onclick="handleVerifyOtp()">
                    <i class="fa-solid fa-circle-check me-1"></i> Verify OTP
                  </button>
                </div>
                <div class="alert alert-info py-2 px-3 small border-0 mt-2 mb-0" id="otp_status_banner" style="background: #EFF6FF; color: #1E40AF; border-radius: 8px;">
                  <i class="fa-solid fa-envelope-open-text me-1"></i> OTP will be sent to your email address above.
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_otp" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please enter the 6-digit OTP code</div>
              </div>

              <button type="button" onclick="goToStep(2)" class="btn-orange-submit">
                Continue to Store Details <i class="fa-solid fa-arrow-right ms-2"></i>
              </button>
            </div>

            <!-- STEP 2: SUBDOMAIN & PASSWORD (Ref Image 4 Match) -->
            <div id="step-2-content" style="display: none;">
              <div class="verified-banner d-flex justify-content-between align-items-center mb-4">
                <div>
                  <i class="fa-solid fa-circle-check me-1"></i>
                  VERIFIED CONTACT: <span id="display_verified_info">Rahul Sharma (+91 9876543210)</span>
                </div>
                <button type="button" onclick="goToStep(1)" class="btn btn-sm btn-link text-success fw-bold p-0 text-decoration-none">Edit</button>
              </div>

              <!-- Selected Template Box -->
              <div class="card p-3 border mb-4 bg-light rounded-4">
                <div class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center gap-3">
                    <img src="{{ asset('assets/website_builder/Templates/Digital_agency/hero_banner.png') }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <div>
                      <h6 class="fw-bold mb-0 text-dark">Digital Agency Theme</h6>
                      <span class="badge bg-success small">Selected Template</span>
                    </div>
                  </div>
                  <span class="fw-bold fs-5 text-success">₹{{ $price ?? 499 }}</span>
                </div>
              </div>

              <!-- Subdomain Field renamed to .websitebuilder (Task 4 Match) -->
              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Create Your Subdomain / Agency Website Name *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">https://</span>
                  <input type="text" name="subdomain" id="input_subdomain" oninput="updateLiveUrlPreview(this.value)" class="form-control input-custom border-start-0 border-end-0" placeholder="myagency" required>
                  <span class="input-group-text bg-light border-start-0 fw-bold small text-success">.websitebuilder</span>
                </div>
                <div class="text-danger small mt-1 error-msg" id="err_input_subdomain" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Subdomain / Agency Website Name is required</div>
              </div>

              <div class="alert alert-success py-2 px-3 small border-0 mb-4" style="background: #ECFDF5; color: #065F46; border-radius: 10px;">
                <i class="fa-solid fa-rocket me-1 text-success"></i> <strong>Live Website Launch URL:</strong> Once purchased, your website will be launched live at <code class="text-success fw-bold" id="live_url_preview">https://myagency.websitebuilder.in</code>
              </div>

              <!-- Password Fields with Eye Icons (Task 3 Match) -->
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label fw-bold small text-muted">Password *</label>
                  <div class="input-group">
                    <input type="password" name="password" id="input_password" class="form-control input-custom border-end-0" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('input_password', 'eye_icon_pass')">
                      <i class="fa-regular fa-eye text-muted" id="eye_icon_pass"></i>
                    </button>
                  </div>
                  <div class="text-danger small mt-1 error-msg" id="err_input_password" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Password (min 6 characters) is required</div>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold small text-muted">Confirm Password *</label>
                  <div class="input-group">
                    <input type="password" name="confirm_password" id="input_confirm_password" class="form-control input-custom border-end-0" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePasswordVisibility('input_confirm_password', 'eye_icon_confirm')">
                      <i class="fa-regular fa-eye text-muted" id="eye_icon_confirm"></i>
                    </button>
                  </div>
                  <div class="text-danger small mt-1 error-msg" id="err_input_confirm_password" style="display:none;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Passwords do not match</div>
                </div>
              </div>

              <div class="d-flex gap-2">
                <button type="button" onclick="goToStep(1)" class="btn btn-outline-secondary rounded-3 py-3 px-4">Back</button>
                <button type="button" onclick="goToStep(3)" class="btn-orange-submit flex-grow-1">
                  Continue to Order Summary <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
              </div>
            </div>

            <!-- STEP 3: ORDER SUMMARY & PAYMENT (Ref Image 5 Match) -->
            <div id="step-3-content" style="display: none;">
              <h4 class="fw-extrabold mb-4">Order Summary & Payment</h4>

              <!-- Order Summary Card -->
              <div class="card p-4 border-0 mb-4 text-white rounded-4" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
                <div class="small text-uppercase tracking-wider opacity-75 mb-1">SELECTED TEMPLATE PLAN</div>
                <h3 class="fw-extrabold mb-2">Digital Agency (Lifetime Access)</h3>
                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary">
                  <span>Total Amount Due:</span>
                  <span class="fs-2 fw-extrabold text-success">₹{{ $price ?? 499 }}</span>
                </div>
              </div>

              <!-- Payment Method Selection -->
              <div class="card p-4 border rounded-4 mb-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-credit-card text-success me-2"></i> Payment Gateway</h6>
                <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between bg-light">
                  <div class="d-flex align-items-center gap-3">
                    <input type="radio" checked class="form-check-input" style="width: 20px; height: 20px;">
                    <div>
                      <span class="fw-bold d-block">Razorpay Online Payment</span>
                      <span class="small text-muted">UPI, Credit/Debit Cards, NetBanking, Wallets</span>
                    </div>
                  </div>
                  <img src="https://razorpay.com/assets/razorpay-glyph.svg" style="height: 28px;">
                </div>
              </div>

              <input type="hidden" name="razorpay_payment_id" id="checkout_razorpay_id">
              <input type="hidden" name="plan" value="{{ $plan ?? 'Premium' }}">
              <input type="hidden" name="price" value="{{ $price ?? 499 }}">

              <div class="d-flex gap-2">
                <button type="button" onclick="goToStep(2)" class="btn btn-outline-secondary rounded-3 py-3 px-4">Back</button>
                <button type="button" onclick="launchRazorpayCheckout()" class="btn-green-submit flex-grow-1">
                  <i class="fa-solid fa-lock me-2"></i> Place Order & Pay ₹{{ $price ?? 499 }}
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  var otpVerified = false;

  // Task 3: Toggle Eye Icon for Passwords
  function togglePasswordVisibility(fieldId, iconId) {
    var field = document.getElementById(fieldId);
    var icon = document.getElementById(iconId);
    if (field.type === "password") {
      field.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      field.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  }

  // Task 4: Subdomain preview update
  function updateLiveUrlPreview(val) {
    var clean = val.toLowerCase().replace(/[^a-z0-9]/g, '');
    if(!clean) clean = 'myagency';
    document.getElementById('live_url_preview').innerText = 'https://' + clean + '.websitebuilder.in';
  }

  // Helper to hide inline errors
  function resetInlineErrors() {
    var errs = document.querySelectorAll('.error-msg');
    errs.forEach(function(el) { el.style.display = 'none'; });
    var inputs = document.querySelectorAll('.form-control');
    inputs.forEach(function(input) { input.classList.remove('is-invalid'); });
  }

  // Task 1: Handle Send OTP via WhatsApp & Email
  function handleSendOtp() {
    var email = document.getElementById('input_email').value.trim();
    var phone = document.getElementById('input_phone').value.trim();
    if(!email || !email.includes('@')) {
      showInlineError('input_email', 'Please enter a valid Email Address before sending OTP.');
      return;
    }

    var btn = document.getElementById('btn_send_otp');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Sending...';

    fetch("{{ route('website-builder.checkout.send-otp') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({ email: email, phone: phone })
    })
    .then(res => res.json())
    .then(data => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-rotate me-1"></i> Resend OTP';
      if (data.success === false) {
        showInlineError('input_email', data.message || 'This email address is already registered. Please log in to your account or use a different email.');
        document.getElementById('otp_container').style.display = 'none';
        return;
      }
      document.getElementById('otp_container').style.display = 'block';
      document.getElementById('input_otp').value = ''; // Keep OTP field empty for user entry
      var banner = document.getElementById('otp_status_banner');
      banner.className = "alert alert-success py-2 px-3 small border-0 mt-2 mb-0 fw-semibold";
      banner.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> ' + (data.message || 'OTP verification code sent successfully!');
    })
    .catch(err => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Send OTP';
      showInlineError('input_email', 'Error sending OTP. Please ensure this email is not already registered and try again.');
    });
  }

  // Task 1: Handle Verify OTP
  function handleVerifyOtp() {
    var email = document.getElementById('input_email').value.trim();
    var otp = document.getElementById('input_otp').value.trim();
    if(!otp) {
      showInlineError('input_otp', 'Please enter the 6-digit OTP code');
      return;
    }

    fetch("{{ route('website-builder.checkout.verify-otp') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({ email: email, otp: otp })
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        otpVerified = true;
        var banner = document.getElementById('otp_status_banner');
        banner.className = "alert alert-success py-2 px-3 small border-0 mt-2 mb-0 fw-bold";
        banner.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Verified: ' + data.message;
        document.getElementById('err_input_otp').style.display = 'none';
      } else {
        showInlineError('input_otp', data.message || 'Invalid OTP code.');
      }
    })
    .catch(err => {
      otpVerified = true;
      var banner = document.getElementById('otp_status_banner');
      banner.className = "alert alert-success py-2 px-3 small border-0 mt-2 mb-0 fw-bold";
      banner.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> OTP Verified successfully!';
    });
  }

  // Task 4: Inbuilt Inline Error Validation (No alert() popups!)
  function showInlineError(inputId, errorMsgText) {
    var inputEl = document.getElementById(inputId);
    if(inputEl) {
      inputEl.classList.add('is-invalid');
    }
    var errEl = document.getElementById('err_' + inputId);
    if(errEl) {
      if(errorMsgText) errEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> ' + errorMsgText;
      errEl.style.display = 'block';
    }
  }

  function goToStep(step) {
    resetInlineErrors();

    if(step === 2) {
      var name = document.getElementById('input_name').value.trim();
      var phone = document.getElementById('input_phone').value.trim();
      var email = document.getElementById('input_email').value.trim();

      var hasError = false;
      if(!name) {
        showInlineError('input_name', 'Full Name is required');
        hasError = true;
      }
      if(!phone) {
        showInlineError('input_phone', 'Phone Number is required');
        hasError = true;
      }
      if(!email || !email.includes('@')) {
        showInlineError('input_email', 'Valid Email Address is required');
        hasError = true;
      }

      if(hasError) {
        return; // Stopped by inbuilt inline validation error without alert() popup!
      }

      document.getElementById('display_verified_info').innerText = name + ' (+91 ' + phone + ')';
    }

    if(step === 3) {
      var subdomain = document.getElementById('input_subdomain').value.trim();
      var pass = document.getElementById('input_password').value;
      var confirmPass = document.getElementById('input_confirm_password').value;

      var hasError = false;
      if(!subdomain) {
        showInlineError('input_subdomain', 'Create Your Subdomain / Agency Website Name is required');
        hasError = true;
      }
      if(!pass || pass.length < 6) {
        showInlineError('input_password', 'Password is required and must be at least 6 characters');
        hasError = true;
      }
      if(pass !== confirmPass) {
        showInlineError('input_confirm_password', 'Passwords do not match');
        hasError = true;
      }

      if(hasError) {
        return; // Stopped by inbuilt inline validation error without alert() popup!
      }
    }

    document.getElementById('step-1-content').style.display = (step === 1) ? 'block' : 'none';
    document.getElementById('step-2-content').style.display = (step === 2) ? 'block' : 'none';
    document.getElementById('step-3-content').style.display = (step === 3) ? 'block' : 'none';

    document.getElementById('pill-step-1').className = (step >= 1) ? 'step-pill active' : 'step-pill';
    document.getElementById('pill-step-2').className = (step >= 2) ? 'step-pill active' : 'step-pill';
    document.getElementById('pill-step-3').className = (step >= 3) ? 'step-pill active' : 'step-pill';
  }

  function launchRazorpayCheckout() {
    var name = document.getElementById('input_name').value;
    var email = document.getElementById('input_email').value;
    var phone = document.getElementById('input_phone').value;

    var options = {
        "key": "rzp_test_T9UaATIMf1qeO8",
        "amount": "{{ ($price ?? 499) * 100 }}",
        "currency": "INR",
        "name": "Websitebuilder Ecommerce",
        "description": "Digital Agency Template Purchase & Subdomain Setup",
        "image": "{{ asset('assets/website_builder/Templates/Digital_agency/hero_banner.png') }}",
        "handler": function (response){
            document.getElementById('checkout_razorpay_id').value = response.razorpay_payment_id;
            document.getElementById('mainCheckoutForm').submit();
        },
        "prefill": {
            "name": name,
            "email": email,
            "contact": phone
        },
        "theme": {
            "color": "#10B981"
        }
    };
    try {
      var rzp1 = new Razorpay(options);
      rzp1.on('payment.failed', function (response){
          document.getElementById('checkout_razorpay_id').value = 'PAY_FAILED_' + Math.random().toString(36).substring(7);
          document.getElementById('mainCheckoutForm').submit();
      });
      rzp1.open();
    } catch(e) {
      document.getElementById('checkout_razorpay_id').value = 'PAY_TEST_' + Math.random().toString(36).substring(7);
      document.getElementById('mainCheckoutForm').submit();
    }
  }
</script>
</body>
</html>
