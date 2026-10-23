<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __("Your Plan is Expired") }} - {{ isset($user) && !empty($user->shop_name) ? $user->shop_name : (isset($user) ? $user->username : "Store") }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --text-primary: #ffffff;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --brand-primary: #10b981;
            --brand-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #070b14;
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient glowing backdrop elements */
        .ambient-bg {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.45;
            animation: float 14s infinite alternate ease-in-out;
        }
        .glow-orb-1 {
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, #ef4444 0%, rgba(239, 68, 68, 0) 70%);
            top: -10%;
            right: 15%;
        }
        .glow-orb-2 {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
            bottom: -5%;
            left: 10%;
            animation-delay: -5s;
        }
        .glow-orb-3 {
            width: 340px;
            height: 340px;
            background: radial-gradient(circle, #10b981 0%, rgba(16, 185, 129, 0) 70%);
            top: 40%;
            left: 45%;
            transform: translate(-50%, -50%);
            opacity: 0.25;
            animation-delay: -9s;
        }

        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.08); }
            100% { transform: translateY(20px) scale(0.96); }
        }

        /* Pure Ultra-Modern Glassmorphism Container */
        .glass-card {
            max-width: 540px;
            width: 100%;
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(28px) saturate(200%);
            -webkit-backdrop-filter: blur(28px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 28px;
            padding: 48px 36px;
            text-align: center;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.8),
                        inset 0 1px 1px 0 rgba(255, 255, 255, 0.25),
                        0 0 40px -10px rgba(239, 68, 68, 0.25);
            position: relative;
            z-index: 10;
            animation: cardAppear 0.55s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(22px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Glass Icon Orb */
        .icon-orb {
            width: 88px;
            height: 88px;
            margin: 0 auto 24px;
            border-radius: 26px;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            box-shadow: 0 12px 30px -8px rgba(239, 68, 68, 0.4),
                        inset 0 1px 1px 0 rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            color: #ef4444;
            position: relative;
            transition: transform 0.3s ease;
        }

        .icon-orb:hover {
            transform: scale(1.05);
        }

        /* Frosted Status Pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 14px;
            border-radius: 9999px;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #ef4444;
            box-shadow: 0 0 10px #ef4444;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.3); }
        }

        h1.title {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -0.6px;
            margin-bottom: 12px;
            line-height: 1.25;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
        }

        .store-tag {
            background: linear-gradient(135deg, #a5b4fc 0%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        p.message {
            font-size: 15.5px;
            color: var(--text-secondary);
            line-height: 1.65;
            margin-bottom: 26px;
            font-weight: 500;
        }

        /* Glass Details Container */
        .glass-detail-box {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 18px 20px;
            margin-bottom: 28px;
            text-align: left;
            font-size: 13.5px;
        }

        .glass-detail-box .heading {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .glass-detail-box .body-text {
            color: #cbd5e1;
            line-height: 1.5;
            margin: 0;
            font-size: 13px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12.5px;
            padding-top: 10px;
            margin-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .detail-row .label {
            color: var(--text-muted);
        }

        .detail-row .value {
            color: #f87171;
            font-weight: 700;
        }

        /* Glass Actions */
        .actions-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 16px;
            font-size: 14.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s ease;
            cursor: pointer;
            border: none;
            outline: none;
        }

        .btn-primary-renew {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.45);
        }

        .btn-primary-renew:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            transform: translateY(-2px);
            box-shadow: 0 14px 30px -5px rgba(16, 185, 129, 0.55);
            color: #ffffff;
        }

        .btn-glass-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
        }

        .btn-glass-secondary:hover {
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .footer-caption {
            margin-top: 22px;
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        @media (max-width: 500px) {
            .glass-card {
                padding: 36px 22px;
                border-radius: 24px;
            }
            h1.title {
                font-size: 24px;
            }
            p.message {
                font-size: 14.5px;
            }
            .icon-orb {
                width: 76px;
                height: 76px;
                font-size: 32px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient glowing lights in background -->
    <div class="ambient-bg">
        <div class="glow-orb glow-orb-1"></div>
        <div class="glow-orb glow-orb-2"></div>
        <div class="glow-orb glow-orb-3"></div>
    </div>

    <!-- Centered Glassmorphism Card -->
    <div class="glass-card">
        
        <div class="icon-orb">
            <i class="fa-solid fa-lock"></i>
        </div>

        <div class="status-pill">
            <span class="pulse-dot"></span>
            <span>{{ __("Subscription Expired") }}</span>
        </div>

        <h1 class="title">{{ __("Your Plan is Expired") }}</h1>

        <p class="message">
            {{ __("The plan for") }}
            <span class="store-tag">{{ isset($user) && !empty($user->shop_name) ? $user->shop_name : (isset($user) ? $user->username : 'this store') }}</span>
            {{ __("has expired. Please contact the admin or renew your plan to reactivate the store.") }}
        </p>

        <div class="glass-detail-box">
            <div class="heading">
                <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                <span>{{ __("Notice for Store Owner") }}</span>
            </div>
            <p class="body-text">
                {{ __("Your catalog, customer records, and orders are preserved safely. Renew your active subscription below to immediately unlock your dashboard and bring your public store back online.") }}
            </p>

            @if(isset($latestMembership) && !empty($latestMembership->expire_date))
                <div class="detail-row">
                    <span class="label">{{ __("Expired On") }}</span>
                    <span class="value">{{ \Carbon\Carbon::parse($latestMembership->expire_date)->format('M d, Y h:i A') }}</span>
                </div>
            @endif
        </div>

        @php
            $loginUrl = url('/user/login');
        @endphp

        <div class="actions-stack">
            <a href="{{ $loginUrl }}" class="btn-action btn-primary-renew">
                <i class="fa-solid fa-bolt"></i>
                <span>{{ __("Renew Plan / Sign In") }}</span>
            </a>

            <a href="{{ url('/contact') }}" class="btn-action btn-glass-secondary">
                <i class="fa-regular fa-envelope"></i>
                <span>{{ __("Contact Admin Support") }}</span>
            </a>

            <a href="{{ url('/') }}" class="btn-action btn-glass-secondary" style="font-size: 13px; padding: 10px 18px;">
                <i class="fa-solid fa-arrow-left"></i>
                <span>{{ __("Return to Home") }}</span>
            </a>
        </div>

        <div class="footer-caption">
            <i class="fa-solid fa-shield-check text-emerald-500"></i>
            <span>{{ __("Protected by Platform Security & Subscription Management") }}</span>
        </div>

    </div>

</body>
</html>