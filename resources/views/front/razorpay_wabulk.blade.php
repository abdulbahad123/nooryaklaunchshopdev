<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whatsappbulk Checkout • Pay with Razorpay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f0fdfa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1f2937;
        }
        .checkout-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px -15px rgba(22, 101, 52, 0.12), 0 0 0 1px rgba(22, 101, 52, 0.08);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            text-align: center;
            position: relative;
        }
        .card-header {
            background: linear-gradient(135deg, #15803d 0%, #1f7a45 100%);
            padding: 32px 24px 28px;
            color: #ffffff;
            position: relative;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 20px;
            background: #ffffff;
            border-radius: 20px 20px 0 0;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .card-body {
            padding: 24px 28px 32px;
        }
        .plan-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            text-align: left;
        }
        .plan-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .plan-row:last-child {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 1px dashed #cbd5e1;
        }
        .price-badge {
            font-size: 26px;
            font-weight: 900;
            color: #15803d;
        }
        .btn-pay {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 10px 20px -5px rgba(22, 163, 74, 0.4);
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 24px -5px rgba(22, 163, 74, 0.5);
        }
        .btn-cancel {
            display: inline-block;
            margin-top: 14px;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
        .btn-cancel:hover {
            color: #0f172a;
        }
        .security-badge {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="checkout-card">
    <div class="card-header">
        <div class="brand-badge">
            <i class="fa-brands fa-whatsapp fs-16"></i> Whatsappbulk Official Gateway
        </div>
        <h2 style="font-size: 22px; font-weight: 800; margin-bottom: 4px;">Secure Razorpay Checkout</h2>
        <p style="font-size: 13px; opacity: 0.9;">Complete your payment to activate your plan instantly</p>
    </div>

    <div class="card-body">
        <div class="plan-box">
            <div class="plan-row">
                <span style="font-size: 13px; font-weight: 600; color: #64748b;">Selected Plan</span>
                <span style="font-size: 14px; font-weight: 800; color: #0f172a;">{{ $planName }}</span>
            </div>
            @if(!empty($customerName))
            <div class="plan-row">
                <span style="font-size: 13px; font-weight: 600; color: #64748b;">Subscriber</span>
                <span style="font-size: 13px; font-weight: 700; color: #334155;">{{ $customerName }}</span>
            </div>
            @endif
            @if(!empty($customerEmail))
            <div class="plan-row">
                <span style="font-size: 13px; font-weight: 600; color: #64748b;">Email Address</span>
                <span style="font-size: 13px; font-weight: 600; color: #334155;">{{ $customerEmail }}</span>
            </div>
            @endif
            <div class="plan-row">
                <span style="font-size: 14px; font-weight: 700; color: #0f172a;">Total Payable</span>
                <span class="price-badge">₹{{ number_format($price, 2) }}</span>
            </div>
        </div>

        <button type="button" class="btn-pay" id="btn-rzp-trigger" onclick="openRazorpay()">
            <i class="fa-solid fa-lock"></i> Pay ₹{{ number_format($price, 2) }} via Razorpay
        </button>

        <div>
            <a href="{{ $prevUrl }}" class="btn-cancel">
                <i class="fa-solid fa-arrow-left me-1"></i> Cancel and Return to Whatsappbulk
            </a>
        </div>

        <div class="security-badge">
            <i class="fa-solid fa-shield-halved text-success"></i>
            <span>256-Bit SSL Encrypted • Powered by Razorpay</span>
        </div>
    </div>
</div>

<form name="razorpayform" id="razorpayform" action="{{ $notify_url }}" method="POST">
    @csrf
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    <input type="hidden" name="uid" value="{{ $uid }}">
    <input type="hidden" name="plan_id" value="{{ $data['notes']['plan_id'] ?? '' }}">
    <input type="hidden" name="plan_name" value="{{ $planName }}">
    <input type="hidden" name="price" value="{{ $price }}">
    <input type="hidden" name="token" value="{{ $token }}">
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {!! is_string($json) ? $json : json_encode($json) !!};

options.handler = function (response) {
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    if (document.getElementById('razorpay_signature')) {
        document.getElementById('razorpay_signature').value = response.razorpay_signature || '';
    }
    const btn = document.getElementById('btn-rzp-trigger');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Activating Subscription...';
    }
    document.getElementById('razorpayform').submit();
};

if (!options.theme) {
    options.theme = {};
}
options.theme.color = "#15803d";

options.modal = {
    ondismiss: function() {
        console.log('Payment modal dismissed by user.');
    },
    escape: true,
    backdropclose: false
};

var rzp = new Razorpay(options);

function openRazorpay() {
    rzp.open();
}

// Automatically trigger Razorpay checkout modal upon loading
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        openRazorpay();
    }, 400);
});
</script>

</body>
</html>
