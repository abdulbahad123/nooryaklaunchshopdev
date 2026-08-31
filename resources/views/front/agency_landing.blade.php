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

        $heroImg  = !empty($agency->hero_image)  ? asset($agency->hero_image)  : asset('assets/landing_page/herobanner_dashboard.png');
        $aboutImg = !empty($agency->about_image) ? asset($agency->about_image) : asset('assets/landing_page/features_leftside.png');
        $ctaImg   = !empty($agency->cta_image)   ? asset($agency->cta_image)   : asset('assets/landing_page/footer_card.png');

        /* KB stats — dynamically editable */
        $kbStats = is_array($agency->kb_stats ?? null)
            ? $agency->kb_stats
            : (json_decode($agency->kb_stats ?? '[]', true) ?: [
                ['value' => '10,000+', 'label' => 'Happy Businesses',  'icon' => 'users'],
                ['value' => '1M+',     'label' => 'Orders Processed',  'icon' => 'package'],
                ['value' => '500K+',   'label' => 'Active Customers',  'icon' => 'shield'],
                ['value' => '99.8%',   'label' => 'Uptime & Secure',   'icon' => 'sparkles'],
            ]);

        $services = is_array($agency->services_data ?? null)
            ? $agency->services_data
            : (json_decode($agency->services_data ?? '[]', true) ?: [
                ['title' => 'AI Reviews + CRM',    'desc' => 'Get more 5-star reviews & manage customers easily',     'icon' => 'star'],
                ['title' => 'Website Builder',      'desc' => 'Create stunning websites in minutes with AI',           'icon' => 'monitor'],
                ['title' => 'Digital V-Card',       'desc' => 'Share your business digitally, smartly',               'icon' => 'user'],
                ['title' => 'QR Menu & Ordering',   'desc' => 'Contactless menu for restaurants & cafes',             'icon' => 'qr-code'],
                ['title' => 'Loyalty Program',      'desc' => 'Reward your customers and increase repeat sales',      'icon' => 'gift'],
                ['title' => 'Business Analytics',   'desc' => 'Track growth with real-time insights',                 'icon' => 'bar-chart-3'],
            ]);

        $testimonials = is_array($agency->testimonials_data ?? null)
            ? $agency->testimonials_data
            : (json_decode($agency->testimonials_data ?? '[]', true) ?: [
                ['name' => 'Rahul Sharma', 'role' => 'Restaurant Owner, Delhi',  'rating' => 5, 'comment' => "{$agency->name} helped us get 3x more online orders in just 2 months. The QR menu and reviews feature is amazing!"],
                ['name' => 'Priya Mehta',  'role' => 'Salon Owner, Mumbai',      'rating' => 5, 'comment' => 'Super easy to use and really effective. Our customer engagement has never been better!'],
                ['name' => 'Amit Verma',   'role' => 'Clinic Owner, Bengaluru',  'rating' => 5, 'comment' => 'The digital tools, CRM and reminders have saved us hours of work every week.'],
            ]);

        $features = is_array($agency->features_data ?? null)
            ? $agency->features_data
            : (json_decode($agency->features_data ?? '[]', true) ?: [
                ['title' => 'Get More Customers', 'desc' => 'Build trust with reviews, smart websites and digital presence.', 'icon' => 'rocket',       'bg' => '#ede9fe', 'color' => '#7c3aed'],
                ['title' => 'Save Time & Effort',  'desc' => 'Automate repetitive tasks and focus on what matters most.',      'icon' => 'clock',        'bg' => '#d1fae5', 'color' => '#059669'],
                ['title' => 'Increase Revenue',    'desc' => 'Drive repeat business with loyalty programs & digital tools.',   'icon' => 'trending-up',  'bg' => '#ffedd5', 'color' => '#ea580c'],
                ['title' => 'Reliable & Secure',   'desc' => 'Your business data is safe with enterprise-grade security.',     'icon' => 'shield-check', 'bg' => '#dbeafe', 'color' => '#1d4ed8'],
            ]);

        $faqs = is_array($agency->faq_data ?? null)
            ? $agency->faq_data
            : (json_decode($agency->faq_data ?? '[]', true) ?: [
                ['q' => 'How does the platform work?',                   'a' => 'Our platform provides an all-in-one suite of growth tools to help local businesses manage orders, reviews, websites, and customer retention from one place.'],
                ['q' => 'Can I customize the features for my business?', 'a' => 'Yes, you can enable and configure the exact tools you need in just a few clicks from your dashboard.'],
                ['q' => 'Is technical knowledge required?',              'a' => 'Not at all! Our software is built for non-technical business owners with clean, easy-to-use interfaces.'],
            ]);

        $categories = is_array($agency->categories_data ?? null)
            ? $agency->categories_data
            : (json_decode($agency->categories_data ?? '[]', true) ?: [
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
            @foreach([['#products','Products'],['#features','Solutions'],['#about-section','About Us'],['#how-it-works','How It Works'],['#testimonials','Reviews'],['#faq','FAQ']] as [$href,$label])
                <a href="{{ $href }}" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#475569'">{{ $label }}</a>
            @endforeach
        </nav>

        {{-- Desktop CTAs --}}
        <div class="desktop-ctas" style="display:flex;align-items:center;gap:12px">
            <a href="{{ $agency->cta_url ?? '/login' }}" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;padding:6px 10px">Login</a>
            <a href="{{ $agency->cta_url ?? '/login' }}" class="btn-brand" style="border-radius:999px;padding:10px 22px;font-size:13px">
                {{ $agency->cta_text ?? 'Start Free Today' }}
                <i data-lucide="arrow-right" style="width:14px;height:14px"></i>
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
            @foreach([['#products','Products'],['#features','Solutions'],['#about-section','About Us'],['#how-it-works','How It Works'],['#testimonials','Reviews'],['#faq','FAQ']] as [$href,$label])
                <a href="{{ $href }}" onclick="toggleMobileMenu()" style="font-size:14px;font-weight:700;color:#334155;text-decoration:none">{{ $label }}</a>
            @endforeach
        </nav>
        <div style="display:flex;flex-direction:column;gap:10px;padding-top:16px;border-top:1px solid #f1f5f9">
            <a href="{{ $agency->cta_url ?? '/login' }}" style="text-align:center;font-size:14px;font-weight:700;color:#334155;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:12px;text-decoration:none">Login</a>
            <a href="{{ $agency->cta_url ?? '/login' }}" class="btn-brand" style="justify-content:center;border-radius:14px">{{ $agency->cta_text ?? 'Start Free Today' }}</a>
        </div>
    </div>
</header>

{{-- ══ HERO SECTION ═══════════════════════════════════════ --}}
<section style="background:linear-gradient(180deg,#f5f4ff 0%,#fff 60%);padding:64px 0 80px">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center" class="hero-grid">

            {{-- Left --}}
            <div style="display:flex;flex-direction:column;gap:24px">
                <span style="display:inline-flex;align-items:center;gap:8px;background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;padding:6px 14px;border-radius:999px;font-size:11px;font-weight:700;width:fit-content">
                    ⚡ All-in-One Growth Platform for Indian Businesses
                </span>

                <h1 style="font-size:clamp(2rem,4vw,3.2rem);font-weight:900;color:#0f172a;line-height:1.13;letter-spacing:-.5px">
                    {{ $agency->hero_title ?? 'Build. Automate.' }}
                    <span class="text-brand" style="display:block;margin-top:4px">Scale. All in One</span>
                </h1>

                <p style="font-size:15px;color:#64748b;line-height:1.75;max-width:480px">
                    {{ $agency->hero_subtitle ?? ($agency->name . ' helps Indian businesses grow faster with powerful tools for marketing, sales, customer loyalty, and automation — all in one place.') }}
                </p>

                <div style="display:flex;gap:12px;flex-wrap:wrap">
                    <a href="{{ $agency->cta_url ?? '/login' }}" class="btn-brand">
                        Start Your Free Trial
                        <i data-lucide="arrow-right" style="width:15px;height:15px"></i>
                    </a>
                    <a href="#how-it-works" class="btn-outline">
                        <i data-lucide="play-circle" style="width:16px;height:16px;color:#4f46e5"></i>
                        Book a Live Demo
                    </a>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:20px;font-size:12px;font-weight:700;color:#64748b">
                    <span style="display:flex;align-items:center;gap:6px"><i data-lucide="check-circle" style="width:14px;height:14px;color:#4f46e5"></i> No Credit Card</span>
                    <span style="display:flex;align-items:center;gap:6px"><i data-lucide="zap" style="width:14px;height:14px;color:#4f46e5"></i> Easy Setup</span>
                    <span style="display:flex;align-items:center;gap:6px"><i data-lucide="refresh-cw" style="width:14px;height:14px;color:#4f46e5"></i> Cancel Anytime</span>
                </div>
            </div>

            {{-- Right --}}
            <div style="display:flex;justify-content:flex-end">
                <img src="{{ $heroImg }}" alt="{{ $agency->name }} Dashboard"
                     style="width:100%;max-width:580px;height:auto;border-radius:24px;box-shadow:0 24px 60px -12px rgba(79,70,229,.18);border:1px solid rgba(226,232,240,.7);object-fit:contain;transition:transform .3s"
                     onmouseover="this.style.transform='scale(1.01)'" onmouseout="this.style.transform='scale(1)'">
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

{{-- ══ WHY CHOOSE — 4 Feature Cards ═════════════════════ --}}
<section id="features" style="background:#f8fafc;padding:72px 0">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="text-align:center;margin-bottom:48px">
            <h2 style="font-size:clamp(1.5rem,3vw,2.2rem);font-weight:800;color:#0f172a;margin-bottom:10px">Why Choose {{ $agency->name }}?</h2>
            <p style="font-size:14px;color:#64748b;max-width:520px;margin:0 auto;line-height:1.7">
                Everything you need to run, grow and scale your business — without juggling multiple tools.
            </p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px" class="feat-grid">
            @foreach($features as $f)
                <div class="feat-card">
                    <div class="feat-icon-box" style="background:{{ $f['bg'] ?? '#ede9fe' }}">
                        <i data-lucide="{{ $f['icon'] ?? 'zap' }}" style="width:24px;height:24px;color:{{ $f['color'] ?? '#7c3aed' }}"></i>
                    </div>
                    <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:8px">{{ $f['title'] }}</h3>
                    <p style="font-size:12px;color:#64748b;line-height:1.7">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ SMART TOOLS — Products 3×2 Grid ══════════════════ --}}
<section id="products" style="background:#eef2ff;padding:72px 0">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="display:grid;grid-template-columns:300px 1fr;gap:48px;align-items:start" class="products-grid">

            {{-- Left sticky column --}}
            <div style="display:flex;flex-direction:column;gap:18px;position:sticky;top:80px">
                <span style="background:#e0e7ff;color:#4338ca;font-size:11px;font-weight:800;padding:5px 14px;border-radius:999px;width:fit-content">Our Products</span>
                <h2 style="font-size:clamp(1.6rem,3vw,2.3rem);font-weight:900;color:#0f172a;line-height:1.18;letter-spacing:-.3px">
                    Smart Tools for <span class="text-brand">Smarter Businesses</span>
                </h2>
                <p style="font-size:13px;color:#64748b;line-height:1.75">
                    A complete suite of business growth tools designed for Indian entrepreneurs and local businesses.
                </p>
                <a href="{{ $agency->cta_url ?? '/login' }}" class="btn-brand" style="width:fit-content;border-radius:14px;padding:12px 24px">
                    Explore All Products
                    <i data-lucide="arrow-right" style="width:15px;height:15px"></i>
                </a>
            </div>

            {{-- Right 3×2 product grid --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
                @foreach($services as $s)
                    @php $ps = $pIconMap[$s['title']] ?? ['icon' => $s['icon'] ?? 'box', 'bg' => '#ede9fe', 'clr' => '#7c3aed']; @endphp
                    <div class="prod-card">
                        <div class="prod-card-top">
                            <div class="prod-icon" style="background:{{ $ps['bg'] }}">
                                <i data-lucide="{{ $ps['icon'] }}" style="width:18px;height:18px;color:{{ $ps['clr'] }}"></i>
                            </div>
                            <div class="prod-arrow">
                                <i data-lucide="arrow-right" style="width:13px;height:13px"></i>
                            </div>
                        </div>
                        <div>
                            <h3 style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:5px">{{ $s['title'] }}</h3>
                            <p style="font-size:11px;color:#64748b;line-height:1.6">{{ $s['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══ HOW IT WORKS — 3 Steps ════════════════════════════ --}}
<section id="how-it-works" style="background:#fff;padding:72px 0">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="text-align:center;margin-bottom:48px">
            <h2 style="font-size:clamp(1.5rem,3vw,2.2rem);font-weight:800;color:#0f172a;margin-bottom:10px">How It Works?</h2>
            <p style="font-size:14px;color:#64748b">Get started in 3 simple steps and transform your business today.</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;position:relative" class="steps-grid">
            {{-- dashed connector (desktop) --}}
            <div class="step-connector-1" style="position:absolute;top:54px;left:calc(33.33% - 4px);width:calc(33.33% + 8px);border-top:2px dashed #c7d2fe;z-index:0"></div>
            <div class="step-connector-2" style="position:absolute;top:54px;left:calc(66.66% - 4px);width:calc(33.33% + 4px);border-top:2px dashed #c7d2fe;z-index:0"></div>

            {{-- Step 1 --}}
            <div class="step-card">
                <div class="step-num" style="background:linear-gradient(135deg,#4f46e5,#7c3aed)">01</div>
                <div class="step-icon-wrap">
                    <i data-lucide="user-plus" style="width:28px;height:28px;color:#4f46e5"></i>
                </div>
                <h3 style="font-size:15px;font-weight:700;color:#0f172a">Sign Up</h3>
                <p style="font-size:12px;color:#64748b;line-height:1.7">Create your account in<br>less than 2 minutes.</p>
            </div>

            {{-- Step 2 --}}
            <div class="step-card">
                <div class="step-num" style="background:linear-gradient(135deg,#2563eb,#4f46e5)">02</div>
                <div class="step-icon-wrap">
                    <i data-lucide="monitor" style="width:28px;height:28px;color:#2563eb"></i>
                </div>
                <h3 style="font-size:15px;font-weight:700;color:#0f172a">Set Up Your Business</h3>
                <p style="font-size:12px;color:#64748b;line-height:1.7">Choose the tools you need<br>and customize in minutes.</p>
            </div>

            {{-- Step 3 --}}
            <div class="step-card">
                <div class="step-num" style="background:linear-gradient(135deg,#0d9488,#059669)">03</div>
                <div class="step-icon-wrap">
                    <i data-lucide="trending-up" style="width:28px;height:28px;color:#0d9488"></i>
                </div>
                <h3 style="font-size:15px;font-weight:700;color:#0f172a">Grow Faster</h3>
                <p style="font-size:12px;color:#64748b;line-height:1.7">Get more customers, more<br>reviews and more revenue.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══ TESTIMONIALS — Loved by Business Owners ════════════ --}}
<section id="testimonials" style="background:#f8fafc;padding:72px 0">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="display:grid;grid-template-columns:260px 1fr;gap:40px;align-items:start" class="reviews-grid">

            {{-- Left heading --}}
            <div style="display:flex;flex-direction:column;gap:14px;position:sticky;top:80px">
                <h2 style="font-size:clamp(1.5rem,2.5vw,2rem);font-weight:900;color:#0f172a;line-height:1.2">
                    Loved by<br>Business Owners
                </h2>
                <p style="font-size:13px;color:#64748b;line-height:1.7">See what our customers say about their growth with {{ $agency->name }}.</p>
                <div style="display:flex;gap:10px;margin-top:6px">
                    <button onclick="prevRev()" style="width:38px;height:38px;border-radius:50%;border:2px solid #e2e8f0;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;transition:all .2s" onmouseover="this.style.borderColor='#4f46e5';this.style.color='#4f46e5'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
                        <i data-lucide="chevron-left" style="width:18px;height:18px"></i>
                    </button>
                    <button onclick="nextRev()" class="bg-brand" style="width:38px;height:38px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 4px 14px rgba(79,70,229,.3)" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i data-lucide="chevron-right" style="width:18px;height:18px"></i>
                    </button>
                </div>
            </div>

            {{-- Right review cards --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px" id="rev-grid">
                @foreach($testimonials as $t)
                    <div class="review-card">
                        {{-- Reviewer top --}}
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                            @if(!empty($t['avatar']))
                                <img src="{{ asset($t['avatar']) }}" alt="{{ $t['name'] }}" class="rev-avatar">
                            @else
                                <div class="rev-initials">{{ strtoupper(substr($t['name'] ?? 'O', 0, 2)) }}</div>
                            @endif
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#0f172a">{{ $t['name'] }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $t['role'] }}</div>
                            </div>
                        </div>
                        {{-- Stars --}}
                        <div style="display:flex;gap:2px;margin-bottom:10px">
                            @for($i = 0; $i < ($t['rating'] ?? 5); $i++)
                                <i data-lucide="star" style="width:13px;height:13px;color:#f59e0b;fill:#f59e0b"></i>
                            @endfor
                        </div>
                        {{-- Quote --}}
                        <p style="font-size:12px;color:#475569;line-height:1.75">"{{ $t['comment'] }}"</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══ BUILT FOR ENTREPRENEURS — features_leftside + KB Stats ══ --}}
<section id="about-section" class="about-section" style="padding:72px 0">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center" class="about-grid">

            {{-- Left image --}}
            <div style="display:flex;justify-content:center;align-items:center">
                <img src="{{ $aboutImg }}" alt="Built for Entrepreneurs"
                     style="width:100%;max-width:520px;height:auto;object-fit:contain;border-radius:20px;filter:drop-shadow(0 16px 40px rgba(79,70,229,.15));transition:transform .3s"
                     onmouseover="this.style.transform='scale(1.01)'" onmouseout="this.style.transform='scale(1)'">
            </div>

            {{-- Right content --}}
            <div style="display:flex;flex-direction:column;gap:28px">

                {{-- KB Stats row — dynamically from $kbStats --}}
                <div style="display:flex;align-items:center;gap:0;flex-wrap:wrap;gap-y:16px">
                    @foreach($kbStats as $idx => $stat)
                        @if($idx > 0)
                            <div class="stat-divider" style="margin:0 20px"></div>
                        @endif
                        <div style="display:flex;flex-direction:column;gap:4px;align-items:flex-start">
                            <span style="font-family:'Outfit',sans-serif;font-size:clamp(1.4rem,2.5vw,2rem);font-weight:900;color:#0f172a;line-height:1">{{ $stat['value'] }}</span>
                            <span style="font-size:11px;font-weight:600;color:#64748b;white-space:nowrap">{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Heading & Body --}}
                <div style="display:flex;flex-direction:column;gap:14px">
                    <h2 style="font-size:clamp(1.4rem,2.5vw,1.85rem);font-weight:800;color:#0f172a;line-height:1.2">
                        Built for entrepreneurs, by entrepreneurs.
                    </h2>
                    <p style="font-size:14px;color:#64748b;line-height:1.8;max-width:440px">
                        {{ $agency->about_content ?? ("We understand the challenges of growing a business in India. That's why we built " . $agency->name . " — to make technology simple, affordable, and accessible for everyone.") }}
                    </p>
                </div>

                {{-- CTA --}}
                <a href="{{ $agency->cta_url ?? '/login' }}" class="btn-brand" style="width:fit-content;border-radius:14px;padding:13px 28px">
                    Explore All Features
                    <i data-lucide="arrow-right" style="width:15px;height:15px"></i>
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

{{-- ══ CTA BANNER ══════════════════════════════════════════ --}}
<section style="background:#f8fafc;padding:48px 0 56px">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div class="cta-band" style="padding:48px 56px;display:flex;align-items:center;justify-content:space-between;gap:32px;flex-wrap:wrap">

            {{-- Left text --}}
            <div style="display:flex;flex-direction:column;gap:18px;max-width:540px;position:relative;z-index:1">
                <h2 style="font-size:clamp(1.5rem,3vw,2.3rem);font-weight:900;color:#fff;line-height:1.2;letter-spacing:-.3px">
                    Ready to Take Your Business<br>to the Next Level?
                </h2>
                <p style="font-size:14px;color:rgba(199,210,254,.9);line-height:1.7">
                    Join thousands of growing businesses with {{ $agency->name }} today.
                </p>
                <div style="display:flex;flex-wrap:wrap;gap:20px;font-size:12px;font-weight:700;color:rgba(199,210,254,.85)">
                    <span style="display:flex;align-items:center;gap:6px"><i data-lucide="check" style="width:14px;height:14px"></i> Quick Setup</span>
                    <span style="display:flex;align-items:center;gap:6px"><i data-lucide="check" style="width:14px;height:14px"></i> No Credit Card Required</span>
                    <span style="display:flex;align-items:center;gap:6px"><i data-lucide="check" style="width:14px;height:14px"></i> 24/7 Support</span>
                </div>
                <div>
                    <a href="{{ $agency->cta_url ?? '/login' }}" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#0f172a;font-weight:800;font-size:14px;padding:13px 28px;border-radius:14px;text-decoration:none;box-shadow:0 8px 24px rgba(0,0,0,.2);transition:transform .2s" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        Get Started Free
                        <i data-lucide="arrow-right" style="width:15px;height:15px"></i>
                    </a>
                </div>
            </div>

            {{-- Right image --}}
            <div style="position:relative;z-index:1;flex-shrink:0">
                <img src="{{ $ctaImg }}" alt="Grow with {{ $agency->name }}"
                     style="max-width:260px;width:100%;height:auto;object-fit:contain;filter:drop-shadow(0 12px 30px rgba(0,0,0,.3));transition:transform .3s"
                     onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
            </div>
        </div>
    </div>
</section>

{{-- ══ FOOTER ══════════════════════════════════════════════ --}}
<footer class="site-footer" style="padding:56px 0 32px">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">

        {{-- 4-column grid: Brand | Products | Policies | Newsletter --}}
        <div style="display:grid;grid-template-columns:220px 1fr 1fr 220px;gap:40px;padding-bottom:40px;border-bottom:1px solid #1e293b" class="footer-grid">

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
                    {{ $agency->footer_content ?? 'Powering the growth of Indian local businesses with smart digital solutions.' }}
                </p>
                <div style="display:flex;gap:8px;margin-top:4px">
                    <a href="{{ $agency->facebook_url ?? '#' }}" class="footer-social-btn"><i data-lucide="facebook" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agency->instagram_url ?? '#' }}" class="footer-social-btn"><i data-lucide="instagram" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agency->youtube_url ?? '#' }}" class="footer-social-btn"><i data-lucide="youtube" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agency->linkedin_url ?? '#' }}" class="footer-social-btn"><i data-lucide="linkedin" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agency->twitter_url ?? '#' }}" class="footer-social-btn"><i data-lucide="twitter" style="width:14px;height:14px"></i></a>
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

            {{-- Col 3: Policies (replaces Solutions + Company) --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Policies</h4>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:9px">
                    @foreach([
                        ['/about',           'About Us'],
                        ['/contact',         'Contact Us'],
                        ['/privacy-policy',  'Privacy Policy'],
                        ['/refund-policy',   'Refund Policy'],
                        ['/shipping-policy', 'Shipping Policy'],
                        ['/terms-conditions','Terms & Conditions'],
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
    @media (max-width: 1024px) {
        .footer-grid  { grid-template-columns: 1fr 1fr !important; }
        .products-grid{ grid-template-columns: 1fr !important; }
        .products-grid > div:first-child { position: static !important; }
        .about-grid   { grid-template-columns: 1fr !important; }
        .reviews-grid { grid-template-columns: 1fr !important; }
        .reviews-grid > div:first-child { position: static !important; }
        .step-connector-1, .step-connector-2 { display: none !important; }
    }
    @media (max-width: 768px) {
        .hero-grid  { grid-template-columns: 1fr !important; }
        .hero-grid > div:last-child { display: none; }
        .feat-grid  { grid-template-columns: 1fr 1fr !important; }
        .steps-grid { grid-template-columns: 1fr !important; }
        .footer-grid{ grid-template-columns: 1fr 1fr !important; }
        .cta-band   { flex-direction: column; padding: 36px 28px !important; align-items: flex-start !important; }
        .cta-band img { max-width: 200px; }
        .lg-nav, .desktop-ctas { display: none !important; }
        .mobile-ham { display: flex !important; }
        .prod-grid-3 { grid-template-columns: 1fr 1fr !important; }
    }
    @media (max-width: 480px) {
        .feat-grid  { grid-template-columns: 1fr !important; }
        .footer-grid{ grid-template-columns: 1fr !important; }
        .steps-grid { grid-template-columns: 1fr !important; }
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
        const cards = document.querySelectorAll('#rev-grid .review-card');
        if (!cards.length) return;
        curRev = (curRev + 1) % cards.length;
        cards[curRev].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
    function prevRev() {
        const cards = document.querySelectorAll('#rev-grid .review-card');
        if (!cards.length) return;
        curRev = (curRev - 1 + cards.length) % cards.length;
        cards[curRev].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
</script>
</body>
</html>
