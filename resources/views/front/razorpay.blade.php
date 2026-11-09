@php
  $reqHost = strtolower(trim((string)(request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? ''))));
  $reqHost = preg_replace('/:\d+$/', '', $reqHost);
  $reqHost = preg_replace('/^www\./i', '', $reqHost);
  $cleanAgencyHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder|whatsappbulk|whatsapp-bulk|whatsapp|wa|ecombuilder|ecom-builder)\./i', '', $reqHost);

  if ($cleanAgencyHost === 'in' || $cleanAgencyHost === 'com' || str_ends_with($reqHost, 'launchshop.in') || in_array($reqHost, ['launchshop.in', 'www.launchshop.in'])) {
      $cleanAgencyHost = 'launchshop.in';
  }
  $scheme = (request()->secure() || str_contains(request()->fullUrl(), 'https://') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')) ? 'https://' : 'http://';

  $prevUrl = url()->previous();
  $isWb = session()->has('wb_checkout_req') || request('product_type') == 'website_builder' || request('is_website_builder');

  if (empty($prevUrl) || $prevUrl === url()->current() || str_contains($prevUrl, 'checkout.')) {
      if (!empty($cleanAgencyHost) && $cleanAgencyHost !== 'localhost' && $cleanAgencyHost !== '127.0.0.1') {
          $prevUrl = $isWb 
              ? "{$scheme}websitebuilder.{$cleanAgencyHost}/website-builder/checkout"
              : "{$scheme}ecombuilder.{$cleanAgencyHost}/registration/final-step";
      } else {
          $prevUrl = $isWb ? route('website-builder.checkout') : url('/');
      }
  }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Secure Checkout &bull; Launching Your Live Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #0b0f19 0%, #111827 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #ffffff;
            overflow-x: hidden;
        }
        .launcher-card {
            background: #ffffff;
            color: #0f172a;
            border-radius: 28px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
            max-width: 460px;
            width: 100%;
            overflow: hidden;
            text-align: center;
            position: relative;
            padding: 40px 32px;
            animation: cardPopIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes cardPopIn {
            0% { opacity: 0; transform: scale(0.92) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .top-gradient-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #2563eb, #10b981, #f59e0b, #ec4899, #2563eb);
            background-size: 200% 100%;
            animation: barGlow 2s linear infinite;
        }
        @keyframes barGlow {
            0% { background-position: 0% 0%; }
            100% { background-position: 200% 0%; }
        }
        .icon-circle {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 38px;
            transition: all 0.4s ease;
        }
        .icon-circle.init {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #2563eb;
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.2);
        }
        .icon-circle.launching {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #10b981;
            box-shadow: 0 12px 28px rgba(16, 185, 129, 0.25);
            animation: pulseGlow 1.5s infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.06); }
        }
        .title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }
        .subtitle {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.55;
            margin-bottom: 24px;
        }
        .progress-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 20px;
            text-align: left;
        }
        .progress-label-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }
        .progress-track {
            height: 8px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 999px;
            width: 30%;
            transition: width 0.5s ease;
        }
        .badge-info {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }
        .badge-info i {
            color: #10b981;
        }
        .btn-cancel {
            margin-top: 18px;
            display: inline-block;
            font-size: 12.5px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .btn-cancel:hover {
            color: #ef4444;
        }
    </style>
</head>
<body>

<div class="launcher-card">
    <div class="top-gradient-bar"></div>

    <div id="statusIconWrap" class="icon-circle init">
        <i id="statusIcon" class="fa-solid fa-shield-halved"></i>
    </div>

    <h2 id="statusTitle" class="title">Initializing Secure Gateway</h2>
    <p id="statusDesc" class="subtitle">Opening payment window. Please complete payment to automatically launch your live website.</p>

    <div class="progress-box">
        <div class="progress-label-wrap">
            <span id="stepText"><i class="fa-solid fa-spinner fa-spin me-1 text-primary"></i> Connecting to payment server...</span>
            <span id="percentText" class="text-primary font-weight-bold">35%</span>
        </div>
        <div class="progress-track">
            <div id="progressBar" class="progress-fill" style="width: 35%;"></div>
        </div>
    </div>

    <div class="badge-info">
        <i class="fa-solid fa-lock"></i>
        <span>256-Bit SSL Encrypted &bull; Automated Instant Setup</span>
    </div>

    <div>
        <a href="{!! $prevUrl !!}" class="btn-cancel"><i class="fa-solid fa-arrow-left me-1"></i> Cancel and Return to Checkout</a>
    </div>
</div>

<form name='razorpayform' action="{{ $notify_url }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >

    {{-- Website Builder Checkout session payload backup --}}
    @if(session()->has('wb_checkout_req'))
        @foreach(session('wb_checkout_req') as $wbKey => $wbVal)
            @if(is_scalar($wbVal))
                <input type="hidden" name="{{ $wbKey }}" value="{{ $wbVal }}">
            @endif
        @endforeach
    @endif

    {{-- LaunchShop Membership Checkout session payload backup --}}
    @if(session()->has('request'))
        @foreach(session('request') as $lsKey => $lsVal)
            @if(is_scalar($lsVal))
                <input type="hidden" name="{{ $lsKey }}" value="{{ $lsVal }}">
            @endif
        @endforeach
    @endif

    @if(session()->has('wb_checkout_req') || request('product_type') == 'website_builder' || request('is_website_builder'))
        <input type="hidden" name="product_type" value="website_builder">
        <input type="hidden" name="is_website_builder" value="1">
    @endif
</form>

<script>
// Checkout details as a json
var options = {!! is_string($json) ? $json : json_encode($json) !!};

function triggerLaunchCelebration() {
    if (typeof confetti === 'function') {
        confetti({ particleCount: 80, spread: 60, origin: { y: 0.6 } });
    }
}

options.handler = function (response){
    // Immediately transform UI into full-screen Live Store Launching mode!
    var iconWrap = document.getElementById('statusIconWrap');
    var icon = document.getElementById('statusIcon');
    var title = document.getElementById('statusTitle');
    var desc = document.getElementById('statusDesc');
    var stepTxt = document.getElementById('stepText');
    var pctTxt = document.getElementById('percentText');
    var pBar = document.getElementById('progressBar');

    if (iconWrap) {
        iconWrap.className = 'icon-circle launching';
    }
    if (icon) {
        icon.className = 'fa-solid fa-rocket fa-bounce';
    }
    if (title) {
        title.innerText = 'Payment Confirmed! Launching Store...';
    }
    if (desc) {
        desc.innerText = 'Provisioning your live domain, database, and templates. Please hold on!';
    }
    triggerLaunchCelebration();

    var currentPct = 45;
    var steps = [
        "Allocating dedicated database & custom subdomain...",
        "Configuring live SSL & security certificate...",
        "Building theme layout, pages & store catalog...",
        "Delivering credentials to your WhatsApp & Email...",
        "Finalizing setup & launching live store..."
    ];
    var stepIdx = 0;

    var launchInterval = setInterval(function() {
        currentPct += 12;
        if (currentPct > 96) currentPct = 96;
        if (pBar) pBar.style.width = currentPct + '%';
        if (pctTxt) pctTxt.innerText = currentPct + '%';
        if (stepIdx < steps.length && stepTxt) {
            stepTxt.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1 text-success"></i> ' + steps[stepIdx];
            stepIdx++;
        }
        if (currentPct >= 70 && currentPct <= 85) {
            triggerLaunchCelebration();
        }
    }, 900);

    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    if (document.getElementById('razorpay_signature')) {
        document.getElementById('razorpay_signature').value = response.razorpay_signature || '';
    }

    // 1. Client-side fallback for Website Builder from localStorage
    try {
        var form = document.razorpayform;
        var wbStr = localStorage.getItem('wb_pending_checkout_customer');
        if (wbStr) {
            var wbObj = JSON.parse(wbStr);
            for (var k in wbObj) {
                if (wbObj.hasOwnProperty(k) && !form.elements[k]) {
                    var inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = k;
                    inp.value = wbObj[k];
                    form.appendChild(inp);
                }
            }
        }
        var wbTmpl = localStorage.getItem('selected_wb_template');
        if (wbTmpl) {
            ['template', 'template_slug', 'theme', 'selected_template'].forEach(function(fn) {
                if (!form.elements[fn]) {
                    var inpTmpl = document.createElement('input');
                    inpTmpl.type = 'hidden';
                    inpTmpl.name = fn;
                    inpTmpl.value = wbTmpl;
                    form.appendChild(inpTmpl);
                }
            });
        }
    } catch(e){}

    // 2. Client-side fallback for LaunchShop template from localStorage
    try {
        var form = document.razorpayform;
        var lsTmpl = localStorage.getItem('launchshop_selected_template') || localStorage.getItem('selected_template');
        if (lsTmpl && !form.elements['selected_template']) {
            var inpLs = document.createElement('input');
            inpLs.type = 'hidden';
            inpLs.name = 'selected_template';
            inpLs.value = lsTmpl;
            form.appendChild(inpLs);
        }
    } catch(e){}

    document.razorpayform.submit();
};

if (!options.theme) {
    options.theme = {};
}
options.theme.image_padding = false;

options.modal = {
    ondismiss: function() {
        var title = document.getElementById('statusTitle');
        var desc = document.getElementById('statusDesc');
        var stepTxt = document.getElementById('stepText');
        if (title) title.innerText = 'Payment Cancelled';
        if (desc) desc.innerText = 'Redirecting you back to checkout...';
        if (stepTxt) stepTxt.innerText = 'Returning to checkout page...';
        window.location.assign("{!! $prevUrl !!}");
    },
    escape: true,
    backdropclose: false
};

var rzp = new Razorpay(options);

// Automatically open Razorpay after short delay to let browser complete initial rendering
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        try {
            rzp.open();
        } catch(e) {
            var desc = document.getElementById('statusDesc');
            if (desc) desc.innerHTML = 'Click below if payment popup did not open automatically:<br><button onclick="rzp.open()" class="btn btn-sm btn-primary mt-2" style="background:#2563eb;color:#fff;border:none;padding:8px 16px;border-radius:8px;font-weight:700;cursor:pointer;">Open Payment Window</button>';
        }
    }, 400);
});
</script>
</body>
</html>