<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Suspended</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #070b14;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

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
            width: 460px;
            height: 460px;
            background: radial-gradient(circle, #ef4444 0%, rgba(239, 68, 68, 0) 70%);
            top: -10%;
            right: 15%;
        }

        .glow-orb-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
            bottom: -5%;
            left: 10%;
            animation-delay: -5s;
        }

        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-28px) scale(1.08); }
            100% { transform: translateY(18px) scale(0.96); }
        }

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
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

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
        }

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
        }

        h1.title {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -0.6px;
            margin-bottom: 12px;
            color: #ffffff;
        }

        p.message {
            font-size: 15.5px;
            color: #cbd5e1;
            line-height: 1.65;
            margin-bottom: 26px;
        }

        .glass-detail-box {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 18px 20px;
            margin-bottom: 28px;
            text-align: left;
            font-size: 13.5px;
            color: #cbd5e1;
            line-height: 1.5;
        }

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
        }

        .btn-primary-renew {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.45);
        }

        .btn-primary-renew:hover {
            transform: translateY(-2px);
            color: #ffffff;
        }

        .btn-glass-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .btn-glass-secondary:hover {
            background: rgba(255, 255, 255, 0.09);
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="ambient-bg">
        <div class="glow-orb glow-orb-1"></div>
        <div class="glow-orb glow-orb-2"></div>
    </div>

    <div class="glass-card">
        <div class="icon-orb">
            <i class="fa-solid fa-ban"></i>
        </div>

        <div class="status-pill">
            <span class="pulse-dot"></span>
            <span>Website Suspended</span>
        </div>

        <h1 class="title">This Website is Suspended</h1>

        <p class="message">
            This website has been temporarily suspended by administration.
        </p>

        <div class="glass-detail-box">
            If you are the owner of this website, please check your registered email or contact administration support to reactivate your workspace.
        </div>

        <div class="actions-stack">
            <a href="{{ url('/website-builder/agency-admin/login') }}" class="btn-action btn-primary-renew">
                <i class="fa-solid fa-key"></i>
                <span>Agency Admin Login</span>
            </a>

            <a href="{{ url('/contact') }}" class="btn-action btn-glass-secondary">
                <i class="fa-regular fa-envelope"></i>
                <span>Contact Support</span>
            </a>
        </div>
    </div>

</body>
</html>