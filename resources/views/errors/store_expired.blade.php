<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __("Your Plan is Expired") }} - {{ isset($user) && !empty($user->shop_name) ? $user->shop_name : (isset($user) ? $user->username : "Store") }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(17, 24, 39, 0.85);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-primary: #f9fafb;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            --accent-primary: #6366f1;
            --accent-hover: #4f46e5;
            --warning-bg: rgba(245, 158, 11, 0.12);
            --warning-border: rgba(245, 158, 11, 0.3);
            --warning-text: #fbbf24;
            --danger-glow: rgba(239, 68, 68, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        .ambient-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(239, 68, 68, 0.08) 50%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 560px;
            width: 100%;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 44px 36px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 40px -10px var(--danger-glow);
            position: relative;
            z-index: 1;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(245, 158, 11, 0.15));
            border: 1px solid rgba(239, 68, 68, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .icon-wrapper::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 24px;
            border: 1px solid rgba(239, 68, 68, 0.2);
            animation: pulseGlow 2.5s infinite ease-in-out;
        }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 0.4; }
            50% { transform: scale(1.08); opacity: 0.9; }
        }

        .icon-wrapper svg {
            width: 40px;
            height: 40px;
            color: #ef4444;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            background: var(--warning-bg);
            border: 1px solid var(--warning-border);
            color: var(--warning-text);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .badge-status .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #f59e0b;
            box-shadow: 0 0 8px #f59e0b;
        }

        h1 {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
            line-height: 1.25;
            color: #ffffff;
        }

        .store-highlight {
            background: linear-gradient(135deg, #a5b4fc 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p.description {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .info-card h4 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .info-card p {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.5;
            margin: 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            padding-top: 12px;
            margin-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .info-row span:first-child {
            color: var(--text-muted);
        }

        .info-row span:last-child {
            color: var(--text-primary);
            font-weight: 600;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            transform: translateY(-2px);
            box-shadow: 0 14px 24px -5px rgba(99, 102, 241, 0.5);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .footer-note {
            margin-top: 24px;
            font-size: 12px;
            color: var(--text-muted);
        }

        @media (max-width: 480px) {
            .container {
                padding: 32px 20px;
                border-radius: 20px;
            }
            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="ambient-glow"></div>

    <div class="container">
        <div class="icon-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>

        <div class="badge-status">
            <span class="dot"></span>
            {{ __('Plan Expired') }}
        </div>

        <h1>{{ __('Your Plan is Expired') }}</h1>

        <p class="description" style="font-size: 16px; font-weight: 500;">
            {{ __('The plan for') }}
            <strong class="store-highlight">{{ isset($user) && !empty($user->shop_name) ? $user->shop_name : (isset($user) ? $user->username : 'this store') }}</strong>
            {{ __('has expired. Please contact the admin or renew your plan to reactivate the store.') }}
        </p>

        <div class="info-card">
            <h4>{{ __('Notice for Store Owner') }}</h4>
            <p>
                {{ __('Your store catalog, orders, and customer details are safely preserved. If you are the store owner, please sign in to renew your plan, or contact the system administrator to reactivate your store.') }}
            </p>

            @if(isset($latestMembership) && !empty($latestMembership->expire_date))
                <div class="info-row">
                    <span>{{ __('Expired On') }}</span>
                    <span>{{ \Carbon\Carbon::parse($latestMembership->expire_date)->format('M d, Y h:i A') }}</span>
                </div>
            @endif
        </div>

        @php
            $loginUrl = url('/user/login');
        @endphp

        <div class="actions">
            <a href="{{ $loginUrl }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" y1="12" x2="3" y2="12"></line>
                </svg>
                {{ __('Renew Plan / Sign In') }}
            </a>
            
            <a href="{{ url('/contact') }}" class="btn btn-secondary" style="border-color: rgba(99, 102, 241, 0.4); color: #a5b4fc;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                {{ __('Contact Admin Support') }}
            </a>

            <a href="{{ url('/') }}" class="btn btn-secondary">
                {{ __('Return to Home') }}
            </a>
        </div>

        <div class="footer-note">
            {{ __('Need assistance? Contact support or your account administrator.') }}
        </div>
    </div>
</body>
</html>