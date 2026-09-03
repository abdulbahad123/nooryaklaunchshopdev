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
    <div class="row g-4 align-items-stretch">
      
      <!-- LEFT COLUMN: VECTOR ILLUSTRATION (Ref Image 3 & 4 Match) -->
      <div class="col-lg-5">
        <div class="illustration-box text-center">
          <!-- Vector Illustration Graphic -->
          <svg width="260" height="200" viewBox="0 0 260 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-4">
            <rect width="260" height="200" rx="16" fill="#E2E8F0"/>
            <rect x="20" y="20" width="220" height="130" rx="10" fill="#3B82F6"/>
            <circle cx="130" cy="85" r="30" fill="#60A5FA"/>
            <rect x="40" y="165" width="180" height="15" rx="4" fill="#94A3B8"/>
            <path d="M100 135L70 180H190L160 135H100Z" fill="#CBD5E1"/>
          </svg>

          <h4 class="fw-extrabold text-slate-900 mb-2">Launch Your Digital Agency Website</h4>
          <p class="text-muted small mb-4" style="line-height: 1.6;">
            Setup your agency website in less than 60 seconds with our automated builder system.
          </p>

          <div class="w-100 text-start bg-white p-3 rounded-4 shadow-sm border">
            <div class="d-flex align-items-center gap-3 mb-2">
              <i class="fa-solid fa-shield-check text-success fs-5"></i>
              <span class="small fw-semibold">SSL Secured & Encrypted Checkout</span>
            </div>
            <div class="d-flex align-items-center gap-3 mb-2">
              <i class="fa-solid fa-bolt text-warning fs-5"></i>
              <span class="small fw-semibold">Instant Website Activation</span>
            </div>
            <div class="d-flex align-items-center gap-3">
              <i class="fa-solid fa-headset text-primary fs-5"></i>
              <span class="small fw-semibold">24/7 Dedicated Support</span>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN: MULTI-STEP FORM (Ref Images 3, 4, 5 Match) -->
      <div class="col-lg-7">
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
                <a href="{{ route('website-builder.user.dashboard') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                  <i class="fa-solid fa-sign-in-alt me-1"></i> Login
                </a>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Full Name *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                  <input type="text" name="customer_name" id="input_name" class="form-control input-custom border-start-0" placeholder="Enter your name" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Phone Number *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 fw-bold small">🇮🇳 +91</span>
                  <input type="text" name="customer_phone" id="input_phone" class="form-control input-custom border-start-0" placeholder="81234 56789" required>
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Email Address *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                  <input type="email" name="customer_email" id="input_email" class="form-control input-custom border-start-0" placeholder="Email Address" required>
                </div>
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

              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Create Your Subdomain / Agency Website Name *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">https://</span>
                  <input type="text" name="subdomain" id="input_subdomain" oninput="updateLiveUrlPreview(this.value)" class="form-control input-custom border-start-0 border-end-0" placeholder="myagency" required>
                  <span class="input-group-text bg-light border-start-0 fw-bold small">.launchshop.in</span>
                </div>
              </div>

              <div class="alert alert-success py-2 px-3 small border-0 mb-4" style="background: #ECFDF5; color: #065F46; border-radius: 10px;">
                <i class="fa-solid fa-rocket me-1 text-success"></i> <strong>Live Website Launch URL:</strong> Once purchased, your website will be launched live at <code class="text-success fw-bold" id="live_url_preview">https://cockroachjantaparty.top/website-builder/myagency</code>
              </div>

              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label fw-bold small text-muted">Password *</label>
                  <input type="password" name="password" id="input_password" class="form-control input-custom" placeholder="••••••••" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold small text-muted">Confirm Password *</label>
                  <input type="password" name="confirm_password" id="input_confirm_password" class="form-control input-custom" placeholder="••••••••" required>
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
  function updateLiveUrlPreview(val) {
    var clean = val.toLowerCase().replace(/[^a-z0-9]/g, '');
    if(!clean) clean = 'myagency';
    document.getElementById('live_url_preview').innerText = 'https://cockroachjantaparty.top/website-builder/' + clean;
  }

  function goToStep(step) {
    if(step === 2) {
      var name = document.getElementById('input_name').value;
      var phone = document.getElementById('input_phone').value;
      var email = document.getElementById('input_email').value;
      if(!name || !email) {
        alert('Please fill in your Name and Email Address.');
        return;
      }
      document.getElementById('display_verified_info').innerText = name + ' (+91 ' + phone + ')';
    }

    if(step === 3) {
      var subdomain = document.getElementById('input_subdomain').value;
      var pass = document.getElementById('input_password').value;
      if(!subdomain || !pass) {
        alert('Please enter your desired Subdomain and Password.');
        return;
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
        "name": "LaunchShop Website Builder",
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
