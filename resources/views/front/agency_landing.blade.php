<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agency->meta_title ?? ($agency->name . ' — All-in-One Business Growth Platform') }}</title>
    <meta name="description" content="{{ $agency->meta_description ?? ($agency->hero_subtitle ?? 'Empowering Indian businesses with smart digital tools.') }}">

    @if(!empty($agency->favicon))
        <link rel="icon" type="image/png" href="{{ asset($agency->favicon) }}">
    @endif
    <meta property="og:title" content="{{ $agency->meta_title ?? $agency->name }}">
    <meta property="og:description" content="{{ $agency->meta_description ?? $agency->hero_subtitle }}">
    @if(!empty($agency->og_image ?? $agency->hero_image))
        <meta property="og:image" content="{{ asset($agency->og_image ?? $agency->hero_image) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @php
        $primaryColor   = $agency->primary_color   ?? '#4f46e5';
        $secondaryColor = $agency->secondary_color ?? '#9333ea';

        $heroImg  = !empty($agency->hero_image)  ? asset(ltrim($agency->hero_image, '/'))  : asset('assets/landing_page/herobanner_dashboard.png');
        $aboutImg = !empty($agency->about_image) ? asset(ltrim($agency->about_image, '/')) : asset('assets/landing_page/features_leftside.png');
        $ctaImg   = !empty($agency->cta_image)   ? asset(ltrim($agency->cta_image, '/'))   : asset('assets/landing_page/footer_card.png');

        /* Safely read property from stdClass or Eloquent model */
        $agencyGet = function($key, $default = null) use ($agency) {
            if (is_object($agency)) {
                return isset($agency->$key) ? $agency->$key : $default;
            }
            return $default;
        };

        /* KB stats — dynamically editable */
        $kbStatsRaw = $agencyGet('kb_stats');
        $kbStats = is_array($kbStatsRaw)
            ? $kbStatsRaw
            : (json_decode($kbStatsRaw ?? '[]', true) ?: [
                ['value' => '10,000+', 'label' => 'Happy Businesses',  'icon' => 'user'],
                ['value' => '1M+',     'label' => 'Orders Processed',  'icon' => 'package'],
                ['value' => '500K+',   'label' => 'Active Customers',  'icon' => 'shield'],
                ['value' => '99.8%',   'label' => 'Uptime & Secure',   'icon' => 'sparkles'],
            ]);

        $kbFloatingRaw = $agencyGet('kb_floating_data');
        $kbFloating = is_array($kbFloatingRaw)
            ? $kbFloatingRaw
            : (json_decode($kbFloatingRaw ?? '[]', true) ?: [
                'order_num'       => '#ORD-125',
                'revenue'         => '₹24,50,000',
                'revenue_growth'  => '12.5%',
                'customers'       => '1,245',
                'customer_growth' => '18.2%',
                'customer_month'  => '+192 this month',
            ]);

        $servicesRaw = $agencyGet('services_data');
        $services = is_array($servicesRaw)
            ? $servicesRaw
            : (json_decode($servicesRaw ?? '[]', true) ?: [
                ['title' => 'AI Reviews + CRM',    'desc' => 'Get more 5-star reviews & manage customers easily',     'icon' => 'star'],
                ['title' => 'Website Builder',      'desc' => 'Create stunning websites in minutes with AI',           'icon' => 'monitor'],
                ['title' => 'Digital V-Card',       'desc' => 'Share your business digitally, smartly',               'icon' => 'user'],
                ['title' => 'QR Menu & Ordering',   'desc' => 'Contactless menu for restaurants & cafes',             'icon' => 'qr-code'],
                ['title' => 'Loyalty Program',      'desc' => 'Reward your customers and increase repeat sales',      'icon' => 'gift'],
                ['title' => 'Business Analytics',   'desc' => 'Track growth with real-time insights',                 'icon' => 'bar-chart-3'],
            ]);

        $testimonialsRaw = $agencyGet('testimonials_data');
        $testimonials = is_array($testimonialsRaw)
            ? $testimonialsRaw
            : (json_decode($testimonialsRaw ?? '[]', true) ?: [
                ['name' => 'Rahul Sharma', 'role' => 'Restaurant Owner, Delhi',  'rating' => 5, 'comment' => "{$agency->name} helped us get 3x more online orders in just 2 months. The QR menu and reviews feature is amazing!"],
                ['name' => 'Priya Mehta',  'role' => 'Salon Owner, Mumbai',      'rating' => 5, 'comment' => 'Super easy to use and really effective. Our customer engagement has never been better!'],
                ['name' => 'Amit Verma',   'role' => 'Clinic Owner, Bengaluru',  'rating' => 5, 'comment' => 'The digital tools, CRM and reminders have saved us hours of work every week.'],
            ]);

        $featuresRaw = $agencyGet('features_data');
        $features = is_array($featuresRaw)
            ? $featuresRaw
            : (json_decode($featuresRaw ?? '[]', true) ?: [
                ['title' => 'Get More Customers', 'desc' => 'Build trust with reviews, smart websites and digital presence.', 'icon' => 'rocket',       'bg' => '#ede9fe', 'color' => '#7c3aed'],
                ['title' => 'Save Time & Effort',  'desc' => 'Automate repetitive tasks and focus on what matters most.',      'icon' => 'clock',        'bg' => '#d1fae5', 'color' => '#059669'],
                ['title' => 'Increase Revenue',    'desc' => 'Drive repeat business with loyalty programs & digital tools.',   'icon' => 'trending-up',  'bg' => '#ffedd5', 'color' => '#ea580c'],
                ['title' => 'Reliable & Secure',   'desc' => 'Your business data is safe with enterprise-grade security.',     'icon' => 'shield-check', 'bg' => '#dbeafe', 'color' => '#1d4ed8'],
            ]);

        $faqsRaw = $agencyGet('faq_data');
        $faqs = is_array($faqsRaw)
            ? $faqsRaw
            : (json_decode($faqsRaw ?? '[]', true) ?: [
                ['q' => 'How does the platform work?',                   'a' => 'Our platform provides an all-in-one suite of growth tools to help local businesses manage orders, reviews, websites, and customer retention from one place.'],
                ['q' => 'Can I customize the features for my business?', 'a' => 'Yes, you can enable and configure the exact tools you need in just a few clicks from your dashboard.'],
                ['q' => 'Is technical knowledge required?',              'a' => 'Not at all! Our software is built for non-technical business owners with clean, easy-to-use interfaces.'],
            ]);

        $categoriesRaw = $agencyGet('categories_data');
        $categories = is_array($categoriesRaw)
            ? $categoriesRaw
            : (json_decode($categoriesRaw ?? '[]', true) ?: [
                ['label' => 'Restaurants',    'icon' => '🍽️'],
                ['label' => 'Clinics',        'icon' => '🏥'],
                ['label' => 'Salons & Spas',  'icon' => '💇'],
                ['label' => 'Retail Shops',   'icon' => '🛍️'],
                ['label' => 'Hotels',         'icon' => '🏨'],
                ['label' => 'Gyms & Fitness', 'icon' => '🏋️'],
                ['label' => 'Real Estate',    'icon' => '🏠'],
                ['label' => '& Many More',    'icon' => '✨'],
            ]);

        /* product icon map */
        $pIconMap = [
            'AI Reviews + CRM'  => ['icon' => 'star',         'bg' => '#ede9fe', 'clr' => '#7c3aed'],
            'Website Builder'   => ['icon' => 'monitor',      'bg' => '#dbeafe', 'clr' => '#1d4ed8'],
            'Digital V-Card'    => ['icon' => 'user',         'bg' => '#d1fae5', 'clr' => '#059669'],
            'QR Menu & Ordering'=> ['icon' => 'qr-code',     'bg' => '#fce7f3', 'clr' => '#be185d'],
            'Loyalty Program'   => ['icon' => 'gift',         'bg' => '#fef3c7', 'clr' => '#d97706'],
            'Business Analytics'=> ['icon' => 'bar-chart-3',  'bg' => '#ccfbf1', 'clr' => '#0d9488'],
        ];
    @endphp

    <style>
        :root {
            --brand-primary:   {{ $primaryColor }};
            --brand-secondary: {{ $secondaryColor }};
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body  { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; background: #fff; overflow-x: hidden; }
        h1, h2, h3, h4, h5 { font-family: 'Outfit', sans-serif; }

        /* ── BRAND UTILITIES ── */
        .bg-brand  { background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%); }
        .text-brand {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-brand {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            color: #fff; font-weight: 800; border-radius: 999px;
            padding: 12px 28px; font-size: 13px; line-height: 1;
            box-shadow: 0 8px 24px -6px rgba(79,70,229,.38);
            transition: transform .2s, box-shadow .2s;
            text-decoration: none; white-space: nowrap;
        }
        .btn-brand:hover { transform: scale(1.03); box-shadow: 0 12px 32px -6px rgba(79,70,229,.5); }
        .btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; color: #1e293b; font-weight: 700;
            border: 1.5px solid #e2e8f0; border-radius: 999px;
            padding: 12px 26px; font-size: 13px; line-height: 1;
            text-decoration: none; transition: background .2s;
        }
        .btn-outline:hover { background: #f8fafc; }

        /* ── HEADER ── */
        .site-header {
            position: sticky; top: 0; z-index: 50;
            background: rgba(255,255,255,.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,.04);
        }
        .header-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; height: 68px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }

        /* ── CATEGORY PILL ── */
        .cat-pill {
            display: flex; flex-direction: column; align-items: center; gap: 5px;
            min-width: 76px; padding: 10px 14px; border-radius: 14px;
            border: 1px solid #e2e8f0; background: #fff;
            font-size: 11px; font-weight: 700; color: #64748b;
            transition: all .2s; cursor: default;
        }
        .cat-pill:hover { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; transform: translateY(-2px); }
        .cat-icon { font-size: 22px; line-height: 1; }

        /* ── FEATURE CARDS ── */
        .feat-card {
            background: #fff; border: 1px solid #e9eef4; border-radius: 18px;
            padding: 28px 24px;
            transition: transform .25s, box-shadow .25s;
        }
        .feat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px -12px rgba(79,70,229,.12); }
        .feat-icon-box { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }

        /* ── PRODUCT CARD ── */
        .prod-card {
            background: #fff; border: 1px solid #e9eef4; border-radius: 16px;
            padding: 20px; transition: transform .25s, box-shadow .25s;
            display: flex; flex-direction: column; gap: 12px;
        }
        .prod-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px -8px rgba(79,70,229,.11); }
        .prod-card-top { display: flex; align-items: center; justify-content: space-between; }
        .prod-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .prod-arrow {
            width: 30px; height: 30px; border-radius: 50%; background: #f1f5f9;
            display: flex; align-items: center; justify-content: center; color: #94a3b8;
            transition: background .2s, color .2s;
        }
        .prod-card:hover .prod-arrow { background: var(--brand-primary); color: #fff; }

        /* ── STEP CARD (How It Works) ── */
        .step-card {
            background: #fff; border: 1.5px solid #e9eef4; border-radius: 20px;
            padding: 32px 20px 28px; text-align: center;
            display: flex; flex-direction: column; align-items: center; gap: 14px;
            position: relative; z-index: 1;
        }
        .step-num {
            width: 38px; height: 38px; border-radius: 50%; color: #fff;
            font-weight: 900; font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 14px -4px rgba(79,70,229,.4);
        }
        .step-icon-wrap { width: 64px; height: 64px; border-radius: 18px; background: #f4f3ff; display: flex; align-items: center; justify-content: center; }

        /* ── REVIEW CARD ── */
        .review-card {
            background: #fff; border: 1px solid #e9eef4; border-radius: 18px;
            padding: 22px;
            transition: transform .25s, box-shadow .25s;
        }
        .review-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px -8px rgba(0,0,0,.08); }
        .rev-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            object-fit: cover; border: 2px solid #e2e8f0; flex-shrink: 0;
        }
        .rev-initials {
            width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            color: #fff; font-weight: 900; font-size: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── CTA BANNER ── */
        .cta-band {
            border-radius: 22px;
            background: linear-gradient(120deg, #1a237e 0%, #283593 35%, #4527a0 100%);
            position: relative; overflow: hidden;
        }
        .cta-band::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 25% 60%, rgba(99,102,241,.28) 0%, transparent 65%);
        }

        /* ── FEATURES/ABOUT SECTION ── */
        .about-section { background: linear-gradient(135deg, #f5f4ff 0%, #eef2ff 100%); }
        .stat-divider { width: 1px; background: #d4d4d8; height: 48px; flex-shrink: 0; }

        /* ── FOOTER ── */
        .site-footer { background: #0f172a; color: #94a3b8; }
        .footer-social-btn {
            width: 32px; height: 32px; border-radius: 50%; background: #1e293b;
            display: inline-flex; align-items: center; justify-content: center;
            color: #64748b; transition: background .2s, color .2s;
        }
        .footer-social-btn:hover { background: var(--brand-primary); color: #fff; }
        .newsletter-wrap { display: flex; gap: 8px; }
        .newsletter-input {
            flex: 1; background: #1e293b; border: 1px solid #334155;
            color: #e2e8f0; padding: 10px 14px; border-radius: 10px;
            font-size: 12px; outline: none; font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .newsletter-input::placeholder { color: #64748b; }
        .newsletter-send {
            width: 40px; height: 40px; border-radius: 10px; border: none; cursor: pointer;
            flex-shrink: 0; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
        }

        /* ── MOBILE MENU ── */
        #mobile-menu { display: none; }
        #mobile-menu.open { display: block; }

        @media(max-width: 640px) {
            .cat-pill { min-width: 62px; padding: 8px 10px; font-size: 10px; }
            .cat-icon { font-size: 18px; }
            .header-inner { padding: 0 16px; }
        }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body>

{{-- ══ ANNOUNCEMENT BAR ══════════════════════════════════ --}}
<div style="background:linear-gradient(90deg,#1e3a8a,#312e81,#4c1d95)" class="text-white text-center py-1.5 px-4 text-[11px] font-semibold">
    🎉 Special Offer: Get Started with <strong>{{ $agency->name }}</strong> Today &amp; Automate Your Business!
</div>

{{-- ══ HEADER ════════════════════════════════════════════ --}}
<header class="site-header">
    <div class="header-inner">

        {{-- Logo --}}
        <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0">
            @if(!empty($agency->logo))
                <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" style="height:36px;width:auto;object-fit:contain">
            @else
                <div class="bg-brand" style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center">
                    <i data-lucide="layers" style="width:18px;height:18px;color:#fff"></i>
                </div>
                <span style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:900;color:#0f172a;letter-spacing:-.5px">{{ $agency->name }}</span>
            @endif
        </a>

        {{-- Desktop Nav --}}
        <nav style="display:none" class="lg-nav">
            @foreach([['#products','Products'],['#about-section','About Us'],['#how-it-works','How It Works'],['#testimonials','Reviews'],['#faq','FAQ']] as [$href,$label])
                <a href="{{ $href }}" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#475569'">{{ $label }}</a>
            @endforeach
        </nav>

        {{-- Desktop CTAs --}}
        <div class="desktop-ctas" style="display:flex;align-items:center;gap:10px">
            <button style="width:38px;height:38px;border-radius:50%;border:1.5px solid #e2e8f0;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:all .2s;flex-shrink:0;" title="Toggle Dark Mode" onmouseover="this.style.borderColor='#4f46e5'" onmouseout="this.style.borderColor='#e2e8f0'">
                <i data-lucide="moon" style="width:16px;height:16px"></i>
            </button>
            <a href="{{ $agency->cta_url ?? '/login' }}" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;padding:6px 12px">Login</a>
            <a href="{{ $agency->cta_url ?? '/login' }}" class="btn-brand" style="border-radius:10px;padding:11px 20px;font-size:13px;font-weight:800">
                {{ $agency->cta_text ?? 'Get Started Free' }}
            </a>
        </div>

        {{-- Mobile hamburger --}}
        <button onclick="toggleMobileMenu()" class="mobile-ham" style="display:none;background:none;border:none;cursor:pointer;padding:6px;border-radius:10px" aria-label="Menu">
            <i data-lucide="menu" style="width:24px;height:24px;color:#475569" id="ham-icon"></i>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" style="background:#fff;border-top:1px solid #f1f5f9;padding:20px 20px 24px">
        <nav style="display:flex;flex-direction:column;gap:14px;margin-bottom:20px">
            @foreach([['#products','Products'],['#about-section','About Us'],['#how-it-works','How It Works'],['#testimonials','Reviews'],['#faq','FAQ']] as [$href,$label])
                <a href="{{ $href }}" onclick="toggleMobileMenu()" style="font-size:14px;font-weight:700;color:#334155;text-decoration:none">{{ $label }}</a>
            @endforeach
        </nav>
        <div style="display:flex;flex-direction:column;gap:10px;padding-top:16px;border-top:1px solid #f1f5f9">
            <a href="{{ $agency->cta_url ?? '/login' }}" style="text-align:center;font-size:14px;font-weight:700;color:#334155;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:12px;text-decoration:none">Login</a>
            <a href="{{ $agency->cta_url ?? '/login' }}" class="btn-brand" style="justify-content:center;border-radius:12px">{{ $agency->cta_text ?? 'Get Started Free' }}</a>
        </div>
    </div>
</header>

{{-- ══ HERO SECTION — Pixel-Perfect 2nd Reference Match ══════════════════════════════════════════ --}}
<section style="background:#f0efff; padding:72px 0 90px; overflow:hidden; position:relative;">
    {{-- Soft decorative glow blobs --}}
    <div style="position:absolute;top:-120px;right:-80px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(167,139,250,.18) 0%,transparent 70%);pointer-events:none;z-index:0;"></div>
    <div style="position:absolute;bottom:-80px;left:-60px;width:360px;height:360px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.10) 0%,transparent 70%);pointer-events:none;z-index:0;"></div>

    <div style="max-width:1200px; margin:0 auto; padding:0 24px; position:relative; z-index:1;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:center;" class="hero-grid">

            {{-- Left --}}
            <div style="display:flex; flex-direction:column; gap:28px;">

                {{-- Badge --}}
                <span style="display:inline-flex; align-items:center; gap:8px; background:rgba(109,40,217,.1); border:1px solid rgba(109,40,217,.2); color:#5b21b6; padding:7px 16px; border-radius:999px; font-size:12px; font-weight:700; width:fit-content; letter-spacing:.01em">
                    ⚡ All-in-One Growth Platform for Indian Businesses
                </span>

                {{-- Headline --}}
                <h1 style="font-size:clamp(2.2rem,4.5vw,3.6rem); font-weight:900; color:#0f172a; line-height:1.08; letter-spacing:-.8px; margin:0">
                    {{ $agency->hero_title ?? 'Build. Automate.' }}
                    <span class="text-brand" style="display:block; margin-top:2px">Scale. All in One</span>
                </h1>

                {{-- Subtitle --}}
                <p style="font-size:15px; color:#475569; line-height:1.8; max-width:460px; margin:0">
                    {{ $agency->hero_subtitle ?? ($agency->name . ' helps Indian businesses grow faster with powerful tools for marketing, sales, customer loyalty, and automation – all in one place.') }}
                </p>

                {{-- CTA Buttons --}}
                <div style="display:flex; gap:14px; flex-wrap:wrap; align-items:center;">
                    <a href="{{ $agency->cta_url ?? '/login' }}"
                       style="display:inline-flex; align-items:center; gap:9px; background:linear-gradient(135deg,var(--brand-primary),var(--brand-secondary)); color:#fff; font-weight:800; font-size:14px; padding:15px 30px; border-radius:12px; text-decoration:none; box-shadow:0 8px 28px -6px rgba(79,70,229,.4); transition:transform .2s, box-shadow .2s; white-space:nowrap;"
                       onmouseover="this.style.transform='scale(1.02)';this.style.boxShadow='0 12px 36px -6px rgba(79,70,229,.5)'"
                       onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 8px 28px -6px rgba(79,70,229,.4)'">
                        Start Your Free Trial
                        <i data-lucide="arrow-right" style="width:16px; height:16px"></i>
                    </a>
                    <a href="#how-it-works"
                       style="display:inline-flex; align-items:center; gap:10px; background:#fff; color:#1e293b; font-weight:700; font-size:14px; padding:15px 28px; border-radius:12px; text-decoration:none; border:1.5px solid #e2e8f0; transition:all .2s; white-space:nowrap;"
                       onmouseover="this.style.borderColor='#a5b4fc';this.style.background='#fafafa'"
                       onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fff'">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:var(--brand-primary);"><i data-lucide="play" style="width:10px;height:10px;color:#fff;margin-left:2px"></i></span>
                        Book a Live Demo
                    </a>
                </div>

                {{-- Trust badges --}}
                <div style="display:flex; flex-wrap:wrap; gap:22px; font-size:12.5px; font-weight:700; color:#64748b; margin-top:-4px;">
                    <span style="display:flex;align-items:center;gap:7px"><i data-lucide="check-circle" style="width:15px;height:15px;color:var(--brand-primary)"></i> No Credit Card</span>
                    <span style="display:flex;align-items:center;gap:7px"><i data-lucide="zap" style="width:15px;height:15px;color:var(--brand-primary)"></i> Easy Setup</span>
                    <span style="display:flex;align-items:center;gap:7px"><i data-lucide="refresh-cw" style="width:15px;height:15px;color:var(--brand-primary)"></i> Cancel Anytime</span>
                </div>
            </div>

            {{-- Right: Dashboard image with slight perspective tilt --}}
            <div style="display:flex; justify-content:flex-end; position:relative;">
                {{-- Glow behind the image --}}
                <div style="position:absolute;inset:-24px;background:radial-gradient(ellipse at 60% 50%,rgba(139,92,246,.12) 0%,transparent 70%);border-radius:32px;z-index:0;"></div>
                <img src="{{ $heroImg }}" alt="{{ $agency->name }} Dashboard"
                     style="position:relative;z-index:1;width:100%;max-width:600px;height:auto;border-radius:20px;box-shadow:0 32px 80px -16px rgba(79,70,229,.22),0 0 0 1px rgba(226,232,240,.5);object-fit:contain;transform:perspective(1200px) rotateY(-4deg) rotateX(2deg);transition:transform .4s;"
                     onmouseover="this.style.transform='perspective(1200px) rotateY(-1deg) rotateX(0deg) scale(1.01)'"
                     onmouseout="this.style.transform='perspective(1200px) rotateY(-4deg) rotateX(2deg)'">

                {{-- Floating brand badge --}}
                <div style="position:absolute; bottom:-18px; right:-10px; z-index:2; width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg,var(--brand-primary),var(--brand-secondary)); display:flex; align-items:center; justify-content:center; box-shadow:0 8px 24px rgba(79,70,229,.35); border:3px solid #fff; animation:floatAnim 3.5s ease-in-out infinite;">
                    @if(!empty($agency->logo))
                        <img src="{{ asset($agency->logo) }}" alt="" style="width:32px;height:32px;object-fit:contain;border-radius:50%;">
                    @else
                        <i data-lucide="layers" style="width:22px;height:22px;color:#fff"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ TRUST BAR — Category Pills ════════════════════════ --}}
<section style="background:#fff;border-top:1px solid #f1f5f9;border-bottom:1px solid #f1f5f9;padding:36px 0">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px;text-align:center">
        <p style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin-bottom:20px">
            Trusted by 10,000+ Local Businesses Across India
        </p>
        <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:10px">
            @foreach($categories as $cat)
                <div class="cat-pill">
                    <span class="cat-icon">{{ $cat['icon'] }}</span>
                    <span>{{ $cat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ WHY CHOOSE — 4 Feature Cards ═════════ --}}
<section id="features" style="background:#f5f4ff; padding:72px 0">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="text-align:center;margin-bottom:52px">
            <h2 style="font-size:clamp(1.6rem,3vw,2.4rem);font-weight:900;color:#0f172a;margin-bottom:12px">Why Choose {{ $agency->name }}?</h2>
            <p style="font-size:14px;color:#64748b;max-width:600px;margin:0 auto;line-height:1.7">
                Everything you need to run, grow and scale your business — without juggling
                <span style="color:#4f46e5;font-weight:600">multiple tools</span>.
            </p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px" class="feat-grid">
            @foreach($features as $feat)
                <div class="feat-card">
                    <div class="feat-icon-box" style="background:{{ $feat['bg'] ?? '#ede9fe' }};">
                        <i data-lucide="{{ $feat['icon'] ?? 'star' }}" style="width:24px;height:24px;color:{{ $feat['color'] ?? '#7c3aed' }}"></i>
                    </div>
                    <h3 style="font-size:16px;font-weight:800;color:#0f172a;margin-bottom:8px;">{{ $feat['title'] }}</h3>
                    <p style="font-size:13px;color:#64748b;line-height:1.6;">{{ $feat['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ BUILT FOR ENTREPRENEURS — features_leftside + KB Floating Elements (BELOW FEATURES SECTION) ══ --}}
<section id="about-section" class="about-section" style="padding:72px 0; background: linear-gradient(135deg, #f5f4ff 0%, #eef2ff 100%); overflow: hidden;">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center" class="about-grid">

            {{-- LEFT SIDE: Man image with soft circular background & floating KB UI badges --}}
            <div style="position:relative; display:flex; justify-content:center; align-items:center; width:100%; min-height:460px;">

                {{-- Soft purple background circle --}}
                <div style="position:absolute; width:360px; height:360px; border-radius:50%; background:linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); z-index:0; top:50%; left:50%; transform:translate(-50%, -50%); opacity:0.85;"></div>

                {{-- Man holding tablet image --}}
                <img src="{{ $aboutImg }}" alt="Built for Entrepreneurs"
                     style="position:relative; z-index:2; width:100%; max-width:350px; height:auto; object-fit:contain; filter:drop-shadow(0 20px 40px rgba(79,70,229,.18)); transition:transform .3s"
                     onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">

                {{-- Floating Badge 1 (Top-Left): New Order #ORD-125 --}}
                <div class="badge-top-left" style="position:absolute; top:28px; left:-10px; z-index:10; background:#fff; border-radius:16px; padding:12px 18px; box-shadow:0 12px 32px rgba(79,70,229,0.12); border:1px solid rgba(226,232,240,0.8); display:flex; align-items:center; gap:14px; animation: floatAnim 4s ease-in-out infinite;">
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.02em;">New Order</span>
                        <span style="font-size:13px; font-weight:900; color:#0f172a; margin-top:2px;">{{ $kbFloating['order_num'] ?? '#ORD-125' }}</span>
                    </div>
                    <div style="width:28px; height:28px; border-radius:50%; background:#10b981; display:flex; align-items:center; justify-content:center; color:#fff; flex-shrink:0;">
                        <i data-lucide="check" style="width:16px; height:16px; stroke-width:3;"></i>
                    </div>
                </div>

                {{-- Floating Badge 2 (Top-Right): Total Revenue ₹24,50,000 +12.5% --}}
                <div class="badge-top-right" style="position:absolute; top:84px; right:-15px; z-index:10; background:#fff; border-radius:16px; padding:14px 20px; box-shadow:0 12px 32px rgba(79,70,229,0.12); border:1px solid rgba(226,232,240,0.8); display:flex; flex-direction:column; gap:4px; animation: floatAnim 4s ease-in-out 1s infinite;">
                    <span style="font-size:11px; font-weight:700; color:#64748b;">Total Revenue</span>
                    <span style="font-family:'Outfit',sans-serif; font-size:18px; font-weight:900; color:#0f172a; line-height:1;">{{ $kbFloating['revenue'] ?? '₹24,50,000' }}</span>
                    <div style="display:flex; align-items:center; gap:4px; margin-top:2px;">
                        <span style="font-size:11px; font-weight:800; color:#10b981;">↑ {{ $kbFloating['revenue_growth'] ?? '12.5%' }}</span>
                        <span style="font-size:10px; color:#94a3b8;">from last month</span>
                    </div>
                </div>

                {{-- Floating Badge 3 (Middle-Left): Colorful Bar Chart Widget --}}
                <div class="badge-mid-left" style="position:absolute; top:210px; left:-25px; z-index:10; background:#fff; border-radius:50%; width:54px; height:54px; box-shadow:0 12px 32px rgba(79,70,229,0.14); border:1px solid rgba(226,232,240,0.8); display:flex; align-items:center; justify-content:center; animation: floatAnim 4s ease-in-out 2s infinite;">
                    <div style="display:flex; align-items:flex-end; gap:3px; height:24px;">
                        <div style="width:5px; height:14px; background:#8b5cf6; border-radius:3px;"></div>
                        <div style="width:5px; height:22px; background:#6366f1; border-radius:3px;"></div>
                        <div style="width:5px; height:10px; background:#f97316; border-radius:3px;"></div>
                        <div style="width:5px; height:18px; background:#06b6d4; border-radius:3px;"></div>
                    </div>
                </div>

                {{-- Floating Badge 4 (Bottom-Left): Customers 1,245 +18.2% --}}
                <div class="badge-bot-left" style="position:absolute; bottom:20px; left:-15px; z-index:10; background:#fff; border-radius:16px; padding:14px 20px; box-shadow:0 12px 32px rgba(79,70,229,0.12); border:1px solid rgba(226,232,240,0.8); display:flex; flex-direction:column; gap:4px; animation: floatAnim 4s ease-in-out 1.5s infinite;">
                    <span style="font-size:11px; font-weight:700; color:#64748b;">Customers</span>
                    <span style="font-family:'Outfit',sans-serif; font-size:19px; font-weight:900; color:#0f172a; line-height:1;">{{ $kbFloating['customers'] ?? '1,245' }}</span>
                    <div style="display:flex; align-items:center; gap:6px; margin-top:2px;">
                        <span style="background:#d1fae5; color:#059669; font-size:10px; font-weight:800; padding:2px 6px; border-radius:6px;">↑ {{ $kbFloating['customer_growth'] ?? '18.2%' }}</span>
                        <span style="font-size:10px; color:#94a3b8;">{{ $kbFloating['customer_month'] ?? '+192 this month' }}</span>
                    </div>
                </div>

            </div>

            {{-- RIGHT SIDE: Stats Row + Heading + Subtitle + CTA --}}
            <div style="display:flex; flex-direction:column; gap:28px;">

                {{-- KB Stats row with icon boxes — dynamically from $kbStats --}}
                <div style="display:flex; align-items:center; gap:0; flex-wrap:wrap; gap-y:20px;" class="kb-stats-container">
                    @foreach($kbStats as $idx => $stat)
                        @if($idx > 0)
                            <div class="stat-divider" style="margin:0 18px; width:1px; height:44px; background:#cbd5e1; flex-shrink:0;"></div>
                        @endif
                        <div style="display:flex; flex-direction:column; gap:10px; align-items:flex-start;">
                            {{-- Icon container --}}
                            <div style="width:42px; height:42px; border-radius:12px; background:#eeddff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i data-lucide="{{ $stat['icon'] ?? 'star' }}" style="width:20px; height:20px; color:#7c3aed;"></i>
                            </div>
                            <div style="display:flex; flex-direction:column; gap:2px;">
                                <span style="font-family:'Outfit',sans-serif; font-size:clamp(1.2rem,2vw,1.75rem); font-weight:900; color:#0f172a; line-height:1;">{{ $stat['value'] }}</span>
                                <span style="font-size:11px; font-weight:700; color:#64748b; white-space:nowrap;">{{ $stat['label'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Heading & Body --}}
                <div style="display:flex; flex-direction:column; gap:14px;">
                    <h2 style="font-size:clamp(1.5rem,2.8vw,2.1rem); font-weight:900; color:#0f172a; line-height:1.2; letter-spacing:-0.3px;">
                        Built for entrepreneurs, by entrepreneurs.
                    </h2>
                    <p style="font-size:14px; color:#64748b; line-height:1.8; max-width:480px;">
                        {{ $agencyGet('about_content') ?? ("We understand the challenges of growing a business in India. That's why we built " . $agency->name . " — to make technology simple, affordable, and accessible for everyone.") }}
                    </p>
                </div>

                {{-- CTA Button --}}
                <a href="{{ $agencyGet('cta_url') ?? '/login' }}" class="btn-brand" style="width:fit-content; border-radius:14px; padding:14px 30px; font-size:14px; font-weight:800;">
                    Explore All Features
                    <i data-lucide="arrow-right" style="width:16px; height:16px;"></i>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ══ SMART TOOLS — Products 3×2 Grid Container Card (MATCHING 2ND REFERENCE IMAGE) ══════════════════ --}}
<section id="products" style="padding:48px 0; background:#f8fafc;">
    <div style="max-width:1200px; margin:0 auto; padding:0 24px;">
        <div class="products-container-card" style="background: linear-gradient(135deg, #f5f4ff 0%, #eef2ff 100%); border-radius:28px; padding:48px 40px; box-shadow:0 10px 40px rgba(79,70,229,0.06); border:1px solid rgba(226,232,240,0.8);">
            <div style="display:grid; grid-template-columns:300px 1fr; gap:48px; align-items:start" class="products-grid">

                {{-- Left column --}}
                <div style="display:flex; flex-direction:column; gap:18px; position:sticky; top:80px">
                    <span style="background:#e0e7ff; color:#4338ca; font-size:11px; font-weight:800; padding:5px 14px; border-radius:999px; width:fit-content">Our Products</span>
                    <h2 style="font-size:clamp(1.6rem,3vw,2.3rem); font-weight:900; color:#0f172a; line-height:1.18; letter-spacing:-.3px">
                        Smart Tools for <span class="text-brand">Smarter Businesses</span>
                    </h2>
                    <p style="font-size:13px; color:#64748b; line-height:1.75">
                        A complete suite of business growth tools designed for Indian entrepreneurs and local businesses.
                    </p>
                    <a href="{{ $agencyGet('cta_url') ?? '/login' }}" class="btn-brand" style="width:fit-content; border-radius:14px; padding:12px 24px">
                        Explore All Products
                        <i data-lucide="arrow-right" style="width:15px; height:15px"></i>
                    </a>
                </div>

                {{-- Right 3×2 product grid --}}
                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px" class="prod-cards-3x2">
                    @foreach($services as $s)
                        @php $ps = $pIconMap[$s['title']] ?? ['icon' => $s['icon'] ?? 'box', 'bg' => '#ede9fe', 'clr' => '#7c3aed']; @endphp
                        <div class="prod-card" style="background:#fff; border:1px solid #f1f5f9; border-radius:20px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.03); transition:transform .25s, box-shadow .25s;">
                            <div class="prod-card-top" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
                                <div class="prod-icon" style="width:42px; height:42px; border-radius:12px; background:{{ $ps['bg'] }}; display:flex; align-items:center; justify-content:center;">
                                    <i data-lucide="{{ $ps['icon'] }}" style="width:20px; height:20px; color:{{ $ps['clr'] }}"></i>
                                </div>
                                <div class="prod-arrow" style="width:30px; height:30px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                                    <i data-lucide="arrow-right" style="width:14px; height:14px"></i>
                                </div>
                            </div>
                            <div>
                                <h3 style="font-size:14px; font-weight:800; color:#0f172a; margin-bottom:6px">{{ $s['title'] }}</h3>
                                <p style="font-size:12px; color:#64748b; line-height:1.6">{{ $s['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ HOW IT WORKS — 3 Steps (Pixel-Perfect 3rd Reference) ══════════════════════ --}}
<section id="how-it-works" style="background:#f0efff; padding:80px 0; overflow:visible;">
    <div style="max-width:1100px; margin:0 auto; padding:0 24px">
        <div style="text-align:center; margin-bottom:60px">
            <h2 style="font-size:clamp(1.75rem,3.2vw,2.4rem); font-weight:900; color:#0f172a; margin-bottom:12px; letter-spacing:-0.5px;">How It Works?</h2>
            <p style="font-size:14px; color:#64748b; font-weight:500;">Get started in 3 simple steps and transform your <span style="color:#4f46e5;font-weight:600;">business</span> today.</p>
        </div>

        {{-- Steps row --}}
        <div style="display:flex; align-items:stretch; gap:0; position:relative;" class="steps-row">

            {{-- Step 1 --}}
            <div class="step-card-2" style="flex:1; background:#fff; border:1px solid rgba(220,224,238,0.6); border-radius:22px; padding:44px 28px 38px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:18px; position:relative; z-index:1; box-shadow:0 8px 32px rgba(79,70,229,0.05); transition:transform .25s, box-shadow .25s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(79,70,229,.1)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 32px rgba(79,70,229,.05)'">
                <div style="position:absolute; top:-17px; left:20px; width:38px; height:38px; border-radius:50%; background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff; font-weight:900; font-size:13px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(124,58,237,.4); border:2px solid #fff; letter-spacing:.5px;">01</div>
                <div style="width:64px; height:64px; border-radius:50%; background:#ede9fe; display:flex; align-items:center; justify-content:center; margin-top:8px;">
                    <i data-lucide="user-plus" style="width:28px; height:28px; color:#7c3aed"></i>
                </div>
                <h3 style="font-size:16px; font-weight:900; color:#0f172a; margin:0;">Sign Up</h3>
                <p style="font-size:13px; color:#64748b; line-height:1.7; margin:0">Create your account in<br>less than 2 minutes.</p>
            </div>

            {{-- Connector 1→2 --}}
            <div class="hiw-connector" style="width:80px; flex-shrink:0; display:flex; align-items:center; justify-content:center; position:relative; z-index:0;">
                <svg width="80" height="20" viewBox="0 0 80 20" fill="none">
                    <path d="M 4 10 Q 20 3 40 10 T 76 10" stroke="#a5b4fc" stroke-width="2" stroke-dasharray="5 4" fill="none" stroke-linecap="round"/>
                    <path d="M 72 7 L 76 10 L 72 13" stroke="#a5b4fc" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            {{-- Step 2 --}}
            <div class="step-card-2" style="flex:1; background:#fff; border:1px solid rgba(220,224,238,0.6); border-radius:22px; padding:44px 28px 38px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:18px; position:relative; z-index:1; box-shadow:0 8px 32px rgba(79,70,229,0.05); transition:transform .25s, box-shadow .25s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(79,70,229,.1)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 32px rgba(79,70,229,.05)'">
                <div style="position:absolute; top:-17px; left:20px; width:38px; height:38px; border-radius:50%; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-weight:900; font-size:13px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(37,99,235,.4); border:2px solid #fff; letter-spacing:.5px;">02</div>
                <div style="width:64px; height:64px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center; margin-top:8px;">
                    <i data-lucide="monitor" style="width:28px; height:28px; color:#2563eb"></i>
                </div>
                <h3 style="font-size:16px; font-weight:900; color:#0f172a; margin:0;">Set Up Your Business</h3>
                <p style="font-size:13px; color:#64748b; line-height:1.7; margin:0">Choose the tools you need<br>and customize in minutes.</p>
            </div>

            {{-- Connector 2→3 --}}
            <div class="hiw-connector" style="width:80px; flex-shrink:0; display:flex; align-items:center; justify-content:center; position:relative; z-index:0;">
                <svg width="80" height="20" viewBox="0 0 80 20" fill="none">
                    <path d="M 4 10 Q 20 3 40 10 T 76 10" stroke="#a5b4fc" stroke-width="2" stroke-dasharray="5 4" fill="none" stroke-linecap="round"/>
                    <path d="M 72 7 L 76 10 L 72 13" stroke="#a5b4fc" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            {{-- Step 3 --}}
            <div class="step-card-2" style="flex:1; background:#fff; border:1px solid rgba(220,224,238,0.6); border-radius:22px; padding:44px 28px 38px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:18px; position:relative; z-index:1; box-shadow:0 8px 32px rgba(79,70,229,0.05); transition:transform .25s, box-shadow .25s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(79,70,229,.1)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 32px rgba(79,70,229,.05)'">
                <div style="position:absolute; top:-17px; left:20px; width:38px; height:38px; border-radius:50%; background:linear-gradient(135deg,#10b981,#059669); color:#fff; font-weight:900; font-size:13px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(16,185,129,.4); border:2px solid #fff; letter-spacing:.5px;">03</div>
                <div style="width:64px; height:64px; border-radius:50%; background:#d1fae5; display:flex; align-items:center; justify-content:center; margin-top:8px;">
                    <i data-lucide="bar-chart-2" style="width:28px; height:28px; color:#059669"></i>
                </div>
                <h3 style="font-size:16px; font-weight:900; color:#0f172a; margin:0;">Grow Faster</h3>
                <p style="font-size:13px; color:#64748b; line-height:1.7; margin:0">Get more customers, more<br>reviews and more revenue.</p>
            </div>

            {{-- End dark circle --}}
            <div class="hiw-arrow-end" style="width:64px; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                <div style="width:44px; height:44px; border-radius:50%; background:#1e1b4b; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 20px rgba(30,27,75,.3);">
                    <i data-lucide="arrow-up-right" style="width:20px;height:20px;color:#fff"></i>
                </div>
            </div>

        </div>
    </div>
</section>



{{-- ══ TESTIMONIALS — Pixel-Perfect 2nd Reference Match ════════════ --}}
<section id="testimonials" style="padding:56px 0; background:#f0efff;">
    <div style="max-width:1160px; margin:0 auto; padding:0 24px;">
        <div class="reviews-container-card" style="background:#fff; border-radius:24px; padding:44px 40px; box-shadow:0 6px 32px rgba(79,70,229,0.07); border:1px solid rgba(220,220,255,0.4);">
            <div style="display:grid; grid-template-columns:240px 1fr; gap:40px; align-items:start" class="reviews-grid">

                {{-- Left heading + arrows --}}
                <div style="display:flex; flex-direction:column; gap:18px;">
                    <h2 style="font-size:clamp(1.6rem,2.6vw,2.1rem); font-weight:900; color:#0f172a; line-height:1.18; margin:0">
                        Loved by<br>Business Owners
                    </h2>
                    <p style="font-size:13px; color:#64748b; line-height:1.75; margin:0">See what our customers say about their growth with {{ $agency->name }}.</p>
                    <div style="display:flex; gap:10px; margin-top:4px">
                        <button onclick="prevRev()" style="width:38px; height:38px; border-radius:50%; border:2px solid #e2e8f0; background:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; transition:all .2s; flex-shrink:0;" onmouseover="this.style.borderColor='#6d28d9';this.style.color='#6d28d9'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
                            <i data-lucide="chevron-left" style="width:18px; height:18px"></i>
                        </button>
                        <button onclick="nextRev()" style="width:38px; height:38px; border-radius:50%; border:none; background:linear-gradient(135deg,#7c3aed,#6d28d9); cursor:pointer; display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 4px 14px rgba(109,40,217,.35); flex-shrink:0; transition:transform .2s" onmouseover="this.style.transform='scale(1.07)'" onmouseout="this.style.transform='scale(1)'">
                            <i data-lucide="chevron-right" style="width:18px; height:18px"></i>
                        </button>
                    </div>
                </div>

                {{-- Right: 3 review cards --}}
                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px" id="rev-grid" class="rev-cards-3">
                    @foreach($testimonials as $t)
                        @php
                            $initials = strtoupper(substr(trim($t['name'] ?? 'U'), 0, 1));
                            $parts = explode(' ', trim($t['name'] ?? 'U'));
                            $initials = strtoupper(substr($parts[0],0,1) . (isset($parts[1]) ? substr($parts[1],0,1) : ''));
                        @endphp
                        <div class="review-card" style="background:#fff; border:1px solid #f1f5f9; border-radius:18px; padding:22px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04); transition:transform .25s, box-shadow .25s; display:flex; flex-direction:column; gap:0;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 30px rgba(79,70,229,.09)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.04)'">
                            {{-- Reviewer top: avatar + name/role --}}
                            <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px">
                                @if(!empty($t['avatar']))
                                    <img src="{{ asset($t['avatar']) }}" alt="{{ $t['name'] }}"
                                         style="width:46px; height:46px; border-radius:50%; object-fit:cover; flex-shrink:0; border:2px solid #f1f5f9;">
                                @else
                                    <div style="width:46px; height:46px; border-radius:50%; flex-shrink:0; background:linear-gradient(135deg,#7c3aed 0%,#6d28d9 100%); color:#fff; font-weight:900; font-size:14px; display:flex; align-items:center; justify-content:center; letter-spacing:.5px;">{{ $initials }}</div>
                                @endif
                                <div style="min-width:0;">
                                    <div style="font-size:13.5px; font-weight:800; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $t['name'] }}</div>
                                    <div style="font-size:11px; color:#64748b; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $t['role'] ?? $t['designation'] ?? '' }}</div>
                                </div>
                            </div>
                            {{-- Stars --}}
                            <div style="display:flex; gap:2px; margin-bottom:10px">
                                @for($i = 0; $i < ($t['rating'] ?? 5); $i++)
                                    <span style="color:#f59e0b; font-size:14px; line-height:1">★</span>
                                @endfor
                            </div>
                            {{-- Quote --}}
                            <p style="font-size:12.5px; color:#475569; line-height:1.75; margin:0;">"{{ $t['comment'] }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ BUILT FOR ENTREPRENEURS — features_leftside + KB Floating Elements ══ --}}
<section id="about-section" class="about-section" style="padding:72px 0; background: linear-gradient(135deg, #f5f4ff 0%, #eef2ff 100%); overflow: hidden;">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center" class="about-grid">

            {{-- LEFT SIDE: Man image with soft circular background & floating KB UI badges --}}
            <div style="position:relative; display:flex; justify-content:center; align-items:center; width:100%; min-height:460px;">

                {{-- Soft purple background circle --}}
                <div style="position:absolute; width:360px; height:360px; border-radius:50%; background:linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); z-index:0; top:50%; left:50%; transform:translate(-50%, -50%); opacity:0.85;"></div>

                {{-- Man holding tablet image --}}
                <img src="{{ $aboutImg }}" alt="Built for Entrepreneurs"
                     style="position:relative; z-index:2; width:100%; max-width:350px; height:auto; object-fit:contain; filter:drop-shadow(0 20px 40px rgba(79,70,229,.18)); transition:transform .3s"
                     onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">

                {{-- Floating Badge 1 (Top-Left): New Order #ORD-125 --}}
                <div style="position:absolute; top:28px; left:-10px; z-index:10; background:#fff; border-radius:16px; padding:12px 18px; box-shadow:0 12px 32px rgba(79,70,229,0.12); border:1px solid rgba(226,232,240,0.8); display:flex; align-items:center; gap:14px; animation: floatAnim 4s ease-in-out infinite;">
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.02em;">New Order</span>
                        <span style="font-size:13px; font-weight:900; color:#0f172a; margin-top:2px;">{{ $kbFloating['order_num'] ?? '#ORD-125' }}</span>
                    </div>
                    <div style="width:28px; height:28px; border-radius:50%; background:#10b981; display:flex; align-items:center; justify-content:center; color:#fff; flex-shrink:0;">
                        <i data-lucide="check" style="width:16px; height:16px; stroke-width:3;"></i>
                    </div>
                </div>

                {{-- Floating Badge 2 (Top-Right): Total Revenue ₹24,50,000 +12.5% --}}
                <div style="position:absolute; top:84px; right:-15px; z-index:10; background:#fff; border-radius:16px; padding:14px 20px; box-shadow:0 12px 32px rgba(79,70,229,0.12); border:1px solid rgba(226,232,240,0.8); display:flex; flex-direction:column; gap:4px; animation: floatAnim 4s ease-in-out 1s infinite;">
                    <span style="font-size:11px; font-weight:700; color:#64748b;">Total Revenue</span>
                    <span style="font-family:'Outfit',sans-serif; font-size:18px; font-weight:900; color:#0f172a; line-height:1;">{{ $kbFloating['revenue'] ?? '₹24,50,000' }}</span>
                    <div style="display:flex; align-items:center; gap:4px; margin-top:2px;">
                        <span style="font-size:11px; font-weight:800; color:#10b981;">↑ {{ $kbFloating['revenue_growth'] ?? '12.5%' }}</span>
                        <span style="font-size:10px; color:#94a3b8;">from last month</span>
                    </div>
                </div>

                {{-- Floating Badge 3 (Middle-Left): Colorful Bar Chart Widget --}}
                <div style="position:absolute; top:210px; left:-25px; z-index:10; background:#fff; border-radius:50%; width:54px; height:54px; box-shadow:0 12px 32px rgba(79,70,229,0.14); border:1px solid rgba(226,232,240,0.8); display:flex; align-items:center; justify-content:center; animation: floatAnim 4s ease-in-out 2s infinite;">
                    <div style="display:flex; align-items:flex-end; gap:3px; height:24px;">
                        <div style="width:5px; height:14px; background:#8b5cf6; border-radius:3px;"></div>
                        <div style="width:5px; height:22px; background:#6366f1; border-radius:3px;"></div>
                        <div style="width:5px; height:10px; background:#f97316; border-radius:3px;"></div>
                        <div style="width:5px; height:18px; background:#06b6d4; border-radius:3px;"></div>
                    </div>
                </div>

                {{-- Floating Badge 4 (Bottom-Left): Customers 1,245 +18.2% --}}
                <div style="position:absolute; bottom:20px; left:-15px; z-index:10; background:#fff; border-radius:16px; padding:14px 20px; box-shadow:0 12px 32px rgba(79,70,229,0.12); border:1px solid rgba(226,232,240,0.8); display:flex; flex-direction:column; gap:4px; animation: floatAnim 4s ease-in-out 1.5s infinite;">
                    <span style="font-size:11px; font-weight:700; color:#64748b;">Customers</span>
                    <span style="font-family:'Outfit',sans-serif; font-size:19px; font-weight:900; color:#0f172a; line-height:1;">{{ $kbFloating['customers'] ?? '1,245' }}</span>
                    <div style="display:flex; align-items:center; gap:6px; margin-top:2px;">
                        <span style="background:#d1fae5; color:#059669; font-size:10px; font-weight:800; padding:2px 6px; border-radius:6px;">↑ {{ $kbFloating['customer_growth'] ?? '18.2%' }}</span>
                        <span style="font-size:10px; color:#94a3b8;">{{ $kbFloating['customer_month'] ?? '+192 this month' }}</span>
                    </div>
                </div>

            </div>

            {{-- RIGHT SIDE: Stats Row + Heading + Subtitle + CTA --}}
            <div style="display:flex; flex-direction:column; gap:28px;">

                {{-- KB Stats row with icon boxes — dynamically from $kbStats --}}
                <div style="display:flex; align-items:center; gap:0; flex-wrap:wrap; gap-y:20px;" class="kb-stats-container">
                    @foreach($kbStats as $idx => $stat)
                        @if($idx > 0)
                            <div class="stat-divider" style="margin:0 18px; width:1px; height:44px; background:#cbd5e1; flex-shrink:0;"></div>
                        @endif
                        <div style="display:flex; flex-direction:column; gap:10px; align-items:flex-start;">
                            {{-- Icon container --}}
                            <div style="width:42px; height:42px; border-radius:12px; background:#eeddff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i data-lucide="{{ $stat['icon'] ?? 'star' }}" style="width:20px; height:20px; color:#7c3aed;"></i>
                            </div>
                            <div style="display:flex; flex-direction:column; gap:2px;">
                                <span style="font-family:'Outfit',sans-serif; font-size:clamp(1.2rem,2vw,1.75rem); font-weight:900; color:#0f172a; line-height:1;">{{ $stat['value'] }}</span>
                                <span style="font-size:11px; font-weight:700; color:#64748b; white-space:nowrap;">{{ $stat['label'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Heading & Body --}}
                <div style="display:flex; flex-direction:column; gap:14px;">
                    <h2 style="font-size:clamp(1.5rem,2.8vw,2.1rem); font-weight:900; color:#0f172a; line-height:1.2; letter-spacing:-0.3px;">
                        Built for entrepreneurs, by entrepreneurs.
                    </h2>
                    <p style="font-size:14px; color:#64748b; line-height:1.8; max-width:480px;">
                        {{ $agencyGet('about_content') ?? ("We understand the challenges of growing a business in India. That's why we built " . $agency->name . " — to make technology simple, affordable, and accessible for everyone.") }}
                    </p>
                </div>

                {{-- CTA Button --}}
                <a href="{{ $agencyGet('cta_url') ?? '/login' }}" class="btn-brand" style="width:fit-content; border-radius:14px; padding:14px 30px; font-size:14px; font-weight:800;">
                    Explore All Features
                    <i data-lucide="arrow-right" style="width:16px; height:16px;"></i>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ══ FAQ ════════════════════════════════════════════════ --}}
<section id="faq" style="background:#fff;padding:64px 0;border-top:1px solid #f1f5f9">
    <div style="max-width:760px;margin:0 auto;padding:0 24px">
        <div style="text-align:center;margin-bottom:40px">
            <h2 style="font-size:clamp(1.4rem,2.5vw,2rem);font-weight:800;color:#0f172a;margin-bottom:8px">Frequently Asked Questions</h2>
            <p style="font-size:14px;color:#64748b">Have questions? We are here to help.</p>
        </div>
        <div style="display:flex;flex-direction:column;gap:12px">
            @foreach($faqs as $item)
                <div style="background:#f8fafc;border:1px solid #e9eef4;border-radius:16px;padding:20px 22px">
                    <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:8px">{{ $item['q'] }}</h4>
                    <p style="font-size:13px;color:#64748b;line-height:1.75">{{ $item['a'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA BANNER — Pixel-Perfect 2nd Reference Match ══════════════════════════════════════════ --}}
<section id="cta" style="background:#f8fafc; padding:48px 0 56px">
    <div style="max-width:1160px; margin:0 auto; padding:0 24px">
        <div class="cta-band" style="position:relative; border-radius:22px; overflow:hidden; background:linear-gradient(120deg,#3730a3 0%,#4f46e5 45%,#6d28d9 100%); min-height:160px; display:flex; align-items:center; justify-content:space-between; gap:20px; padding:40px 48px; box-shadow:0 16px 48px rgba(79,70,229,.22);">

            {{-- Decorative circles --}}
            <div style="position:absolute;inset:0;overflow:hidden;pointer-events:none;">
                <div style="position:absolute;top:-60px;left:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
                <div style="position:absolute;bottom:-40px;right:280px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.05);"></div>
            </div>

            {{-- Left: heading + sub + checkmarks --}}
            <div style="display:flex; flex-direction:column; gap:12px; max-width:380px; position:relative; z-index:2; flex-shrink:0;">
                <h2 style="font-size:clamp(1.2rem,2.2vw,1.65rem); font-weight:900; color:#fff; line-height:1.25; letter-spacing:-.2px; margin:0">
                    Ready to Take Your Business to the Next Level?
                </h2>
                <p style="font-size:13px; color:rgba(199,210,254,.9); line-height:1.6; margin:0">
                    Join thousands of growing businesses with {{ $agency->name }} today.
                </p>
                <div style="display:flex; flex-wrap:wrap; gap:16px; font-size:12px; font-weight:600; color:rgba(199,210,254,.85); margin-top:2px;">
                    <span style="display:flex;align-items:center;gap:5px"><i data-lucide="check" style="width:13px;height:13px"></i> Quick Setup</span>
                    <span style="display:flex;align-items:center;gap:5px"><i data-lucide="check" style="width:13px;height:13px"></i> No Credit Card Required</span>
                    <span style="display:flex;align-items:center;gap:5px"><i data-lucide="check" style="width:13px;height:13px"></i> 24/7 Support</span>
                </div>
            </div>

            {{-- Center: woman image (cuts into section, overflows bottom) --}}
            <div style="position:relative; z-index:2; flex-shrink:0; align-self:flex-end; margin-bottom:-40px;">
                <img src="{{ $ctaImg }}" alt="Grow with {{ $agency->name }}"
                     style="height:220px; width:auto; object-fit:contain; filter:drop-shadow(0 8px 24px rgba(0,0,0,.28)); display:block;"
                     onerror="this.style.display='none'">
            </div>

            {{-- Right: CTA button --}}
            <div style="position:relative; z-index:2; flex-shrink:0;">
                <a href="{{ $agency->cta_url ?? '/login' }}"
                   style="display:inline-flex; align-items:center; gap:9px; background:#fff; color:#1e1b4b; font-weight:800; font-size:14px; padding:14px 26px; border-radius:12px; text-decoration:none; box-shadow:0 6px 22px rgba(0,0,0,.18); white-space:nowrap; transition:transform .2s, box-shadow .2s"
                   onmouseover="this.style.transform='scale(1.03)';this.style.boxShadow='0 10px 30px rgba(0,0,0,.22)'"
                   onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 6px 22px rgba(0,0,0,.18)'">
                    Get Started Free
                    <i data-lucide="arrow-right" style="width:15px; height:15px"></i>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ══ FOOTER ══════════════════════════════════════════════ --}}
<footer class="site-footer" style="padding:56px 0 32px">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">

        {{-- 4-column grid: Brand | Products | Legal | Newsletter --}}
        <div style="display:grid;grid-template-columns:200px 1fr 1fr 200px;gap:32px;padding-bottom:40px;border-bottom:1px solid #1e293b" class="footer-grid">

            {{-- Col 1: Brand + Social --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <div style="display:flex;align-items:center;gap:10px">
                    @if(!empty($agency->logo))
                        <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" style="height:32px;width:auto">
                    @else
                        <div class="bg-brand" style="width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i data-lucide="layers" style="width:16px;height:16px;color:#fff"></i>
                        </div>
                        <span style="font-family:'Outfit',sans-serif;font-size:17px;font-weight:900;color:#e2e8f0">{{ $agency->name }}</span>
                    @endif
                </div>
                <p style="font-size:12px;color:#64748b;line-height:1.7;max-width:190px">
                    {{ $agencyGet('footer_content') ?? 'Powering the growth of Indian local businesses with smart digital solutions.' }}
                </p>
                <div style="display:flex;gap:8px;margin-top:4px">
                    <a href="{{ $agencyGet('facebook_url') ?? '#' }}" class="footer-social-btn"><i data-lucide="facebook" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agencyGet('instagram_url') ?? '#' }}" class="footer-social-btn"><i data-lucide="instagram" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agencyGet('youtube_url') ?? '#' }}" class="footer-social-btn"><i data-lucide="youtube" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agencyGet('linkedin_url') ?? '#' }}" class="footer-social-btn"><i data-lucide="linkedin" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agencyGet('twitter_url') ?? '#' }}" class="footer-social-btn"><i data-lucide="twitter" style="width:14px;height:14px"></i></a>
                </div>
            </div>

            {{-- Col 2: Products --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Products</h4>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:9px">
                    @foreach($services as $s)
                        <li>
                            <a href="#products" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'">
                                <i data-lucide="chevron-right" style="width:12px;height:12px;flex-shrink:0"></i>
                                {{ $s['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 3: Legal Policies --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Legal</h4>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:9px">
                    @foreach([
                        ['/about',           'About Us'],
                        ['/contact',         'Contact Us'],
                        ['/privacy-policy',  'Privacy Policy'],
                        ['/terms',           'Terms & Conditions'],
                        ['/shipping-policy', 'Shipping Policy'],
                        ['/refund-policy',   'Refund Policy'],
                    ] as [$href, $label])
                        <li>
                            <a href="{{ $href }}" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'">
                                <i data-lucide="chevron-right" style="width:12px;height:12px;flex-shrink:0"></i>
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 4: Newsletter --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Subscribe to our newsletter</h4>
                <p style="font-size:12px;color:#64748b">Get updates, tips and offers.</p>
                <div class="newsletter-wrap">
                    <input type="email" class="newsletter-input" placeholder="Enter your email">
                    <button class="newsletter-send" onclick="alert('Thank you for subscribing!')" type="button">
                        <i data-lucide="send" style="width:16px;height:16px;color:#fff"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div style="padding-top:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <p style="font-size:11px;color:#475569">© {{ date('Y') }} {{ $agency->name }}. All rights reserved.</p>
            <p style="font-size:11px;color:#475569;display:flex;align-items:center;gap:4px">Made with <span style="color:#f43f5e">❤️</span> in India 🇮🇳</p>
        </div>
    </div>
</footer>

<style>
    /* ── Responsive overrides ── */
    @media (max-width: 1280px) {
        .footer-grid  { grid-template-columns: 1fr 1fr 1fr !important; }
    }
    @media (max-width: 1024px) {
        .about-grid { grid-template-columns: 1fr !important; gap: 36px !important; }
        .about-grid > div:first-child { min-height: 400px !important; }
        .products-container-card { padding: 36px 24px !important; }
        .products-grid { grid-template-columns: 1fr !important; gap: 32px !important; }
        .products-grid > div:first-child { position: static !important; }
        .reviews-container-card { padding: 36px 24px !important; }
        .reviews-grid { grid-template-columns: 1fr !important; gap: 28px !important; }
        .reviews-grid > div:first-child { position: static !important; }
        .footer-grid { grid-template-columns: 1fr 1fr 1fr !important; }
        .step-connector-svg { display: none !important; }
        .hiw-connector { display: none !important; }
        .hiw-arrow-end { display: none !important; }
        .steps-row { flex-direction: column !important; gap: 28px !important; }
        .step-card-2 { flex: none !important; }
    }
    @media (max-width: 768px) {
        .hero-grid { grid-template-columns: 1fr !important; text-align: center; }
        .hero-grid > div:first-child { align-items: center !important; }
        .hero-grid > div:last-child { justify-content: center !important; margin-top: 24px; }
        .feat-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
        .feat-grid > div { padding: 18px 14px !important; }
        .steps-grid { grid-template-columns: 1fr !important; gap: 24px !important; }
        .prod-cards-3x2 { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
        .prod-cards-3x2 .prod-card { padding: 16px 14px !important; }

        /* Single row slider for review section on mobile */
        .rev-cards-3 {
            display: flex !important;
            grid-template-columns: none !important;
            overflow-x: auto !important;
            scroll-snap-type: x mandatory !important;
            scroll-behavior: smooth !important;
            -webkit-overflow-scrolling: touch !important;
            gap: 16px !important;
            padding-bottom: 12px !important;
        }
        .rev-cards-3::-webkit-scrollbar { display: none; }
        .rev-cards-3 .review-card {
            flex: 0 0 88% !important;
            min-width: 88% !important;
            scroll-snap-align: center !important;
        }

        /* Ready to take your business CTA container image centered & larger on mobile only */
        .cta-band {
            flex-direction: column !important;
            padding: 36px 20px !important;
            align-items: center !important;
            text-align: center !important;
        }
        .cta-band > div:nth-child(2) {
            max-width: 100% !important;
            align-items: center !important;
            text-align: center !important;
        }
        .cta-band > div:nth-child(2) > div {
            justify-content: center !important;
        }
        .cta-band > div:nth-child(3) {
            align-self: center !important;
            margin: 20px auto 12px auto !important;
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
        }
        .cta-band img {
            height: 290px !important;
            max-height: 320px !important;
            width: auto !important;
            margin: 0 auto !important;
            align-self: center !important;
            display: block !important;
        }

        .footer-grid { grid-template-columns: 1fr 1fr !important; gap: 24px !important; }
        .lg-nav, .desktop-ctas { display: none !important; }
        .mobile-ham { display: flex !important; }
        .kb-stats-container { justify-content: center !important; }
        .stat-divider { display: none !important; }
    }
    @media (max-width: 520px) {
        .feat-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
        .feat-grid > div { padding: 16px 10px !important; }
        .prod-cards-3x2 { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
        .prod-cards-3x2 .prod-card { padding: 14px 10px !important; }
        .footer-grid { grid-template-columns: 1fr !important; }
        .badge-top-left { top: 10px !important; left: -5px !important; transform: scale(0.85); transform-origin: top left; }
        .badge-top-right { top: 70px !important; right: -5px !important; transform: scale(0.85); transform-origin: top right; }
        .badge-mid-left { top: 180px !important; left: -10px !important; transform: scale(0.85); transform-origin: middle left; }
        .badge-bot-left { bottom: 10px !important; left: -5px !important; transform: scale(0.85); transform-origin: bottom left; }
    }
    @media (min-width: 1025px) {
        .lg-nav { display: flex !important; align-items: center; gap: 28px; }
        .desktop-ctas { display: flex !important; }
        .mobile-ham { display: none !important; }
    }
</style>

<script>
    lucide.createIcons();

    function toggleMobileMenu() {
        const m = document.getElementById('mobile-menu');
        m.classList.toggle('open');
    }

    let curRev = 0;
    function nextRev() {
        const revGrid = document.getElementById('rev-grid');
        if (!revGrid) return;
        const cardWidth = revGrid.querySelector('.review-card')?.offsetWidth || 300;
        if (revGrid.scrollLeft + revGrid.clientWidth >= revGrid.scrollWidth - 10) {
            revGrid.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            revGrid.scrollBy({ left: cardWidth, behavior: 'smooth' });
        }
    }
    function prevRev() {
        const revGrid = document.getElementById('rev-grid');
        if (!revGrid) return;
        const cardWidth = revGrid.querySelector('.review-card')?.offsetWidth || 300;
        if (revGrid.scrollLeft <= 10) {
            revGrid.scrollTo({ left: revGrid.scrollWidth, behavior: 'smooth' });
        } else {
            revGrid.scrollBy({ left: -cardWidth, behavior: 'smooth' });
        }
    }

    // Auto-slide reviews every 3.5 seconds
    let autoRevTimer = setInterval(nextRev, 3500);
    const revGridEl = document.getElementById('rev-grid');
    if (revGridEl) {
        revGridEl.addEventListener('touchstart', () => clearInterval(autoRevTimer), {passive: true});
        revGridEl.addEventListener('mouseenter', () => clearInterval(autoRevTimer));
        revGridEl.addEventListener('mouseleave', () => {
            clearInterval(autoRevTimer);
            autoRevTimer = setInterval(nextRev, 3500);
        });
    }
</script>
</body>
</html>
