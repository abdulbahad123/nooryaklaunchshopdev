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

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @php
        $primaryColor   = $agency->primary_color   ?? '#4f46e5';
        $secondaryColor = $agency->secondary_color ?? '#9333ea';
        $accentColor    = $agency->accent_color    ?? '#3b82f6';

        $heroImg  = !empty($agency->hero_image)  ? asset($agency->hero_image)  : asset('assets/landing_page/herobanner_dashboard.png');
        $aboutImg = !empty($agency->about_image) ? asset($agency->about_image) : asset('assets/landing_page/features_leftside.png');
        $ctaImg   = !empty($agency->cta_image)   ? asset($agency->cta_image)   : asset('assets/landing_page/footer_card.png');

        $services = is_array($agency->services_data ?? null)
            ? $agency->services_data
            : (json_decode($agency->services_data ?? '[]', true) ?: [
                ['title' => 'AI Reviews + CRM',      'desc' => 'Get more 5-star reviews & manage customers easily',       'icon' => 'star'],
                ['title' => 'Website Builder',         'desc' => 'Create stunning websites in minutes with AI',             'icon' => 'monitor'],
                ['title' => 'Digital V-Card',          'desc' => 'Share your business digitally, smartly',                  'icon' => 'user'],
                ['title' => 'QR Menu & Ordering',      'desc' => 'Contactless menu for restaurants & cafes',                'icon' => 'qr-code'],
                ['title' => 'Loyalty Program',         'desc' => 'Reward your customers and increase repeat sales',         'icon' => 'gift'],
                ['title' => 'Business Analytics',      'desc' => 'Track growth with real-time insights',                    'icon' => 'bar-chart-3'],
            ]);

        $testimonials = is_array($agency->testimonials_data ?? null)
            ? $agency->testimonials_data
            : (json_decode($agency->testimonials_data ?? '[]', true) ?: [
                ['name' => 'Rahul Sharma', 'role' => 'Restaurant Owner, Delhi',   'rating' => 5, 'comment' => "{$agency->name} helped us get 3x more online orders in just 2 months. The QR menu and reviews feature is amazing!"],
                ['name' => 'Priya Mehta',  'role' => 'Salon Owner, Mumbai',       'rating' => 5, 'comment' => 'Super easy to use and really effective. Our customer engagement has never been better!'],
                ['name' => 'Amit Verma',   'role' => 'Clinic Owner, Bengaluru',   'rating' => 5, 'comment' => 'The digital tools, CRM and reminders have saved us hours of work every week.'],
            ]);

        $features = is_array($agency->features_data ?? null)
            ? $agency->features_data
            : (json_decode($agency->features_data ?? '[]', true) ?: [
                ['title' => 'Get More Customers', 'desc' => 'Build trust with reviews, smart websites and digital presence.', 'icon' => 'rocket',       'gradient' => 'from-purple-500 to-indigo-500'],
                ['title' => 'Save Time & Effort',  'desc' => 'Automate repetitive tasks and focus on what matters most.',      'icon' => 'clock',        'gradient' => 'from-teal-400 to-emerald-500'],
                ['title' => 'Increase Revenue',    'desc' => 'Drive repeat business with loyalty programs & digital tools.',   'icon' => 'trending-up',  'gradient' => 'from-orange-400 to-red-500'],
                ['title' => 'Reliable & Secure',   'desc' => 'Your business data is safe with enterprise-grade security.',     'icon' => 'shield-check', 'gradient' => 'from-blue-400 to-indigo-500'],
            ]);

        $faqs = is_array($agency->faq_data ?? null)
            ? $agency->faq_data
            : (json_decode($agency->faq_data ?? '[]', true) ?: [
                ['q' => 'How does the platform work?',                   'a' => 'Our platform provides an all-in-one suite of growth tools designed to help local businesses manage orders, reviews, websites, and customer retention from a single place.'],
                ['q' => 'Can I customize the features for my business?', 'a' => 'Yes, you can enable and configure the exact tools you need in just a few clicks from your dashboard.'],
                ['q' => 'Is technical knowledge required?',              'a' => 'Not at all! Our software is built for non-technical business owners with clean, easy-to-use interfaces.'],
            ]);

        $categories = is_array($agency->categories_data ?? null)
            ? $agency->categories_data
            : (json_decode($agency->categories_data ?? '[]', true) ?: [
                ['label' => 'Restaurants',   'icon' => '🍽️'],
                ['label' => 'Clinics',       'icon' => '🏥'],
                ['label' => 'Salons & Spas', 'icon' => '💇'],
                ['label' => 'Retail Shops',  'icon' => '🛍️'],
                ['label' => 'Hotels',        'icon' => '🏨'],
                ['label' => 'Gyms & Fitness','icon' => '🏋️'],
                ['label' => 'Real Estate',   'icon' => '🏠'],
                ['label' => '& Many More',   'icon' => '✨'],
            ]);
    @endphp

    <style>
        :root {
            --brand-primary:   {{ $primaryColor }};
            --brand-secondary: {{ $secondaryColor }};
            --brand-accent:    {{ $accentColor }};
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; }
        h1, h2, h3, h4, h5, .font-heading { font-family: 'Outfit', sans-serif; }

        .bg-brand-gradient {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
        }
        .text-brand-gradient {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-bg-glow {
            background: linear-gradient(180deg, #f5f4ff 0%, #fff 60%);
        }

        /* Announcement ticker */
        .ticker-bar { background: linear-gradient(90deg,#1e3a8a,#312e81,#4c1d95); }

        /* Header */
        .site-header { box-shadow: 0 1px 0 #e2e8f0; }

        /* Category pill */
        .cat-pill {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: #fff;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            transition: all .2s;
            min-width: 72px;
            cursor: pointer;
        }
        .cat-pill:hover { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; transform: translateY(-2px); }
        .cat-pill .cat-icon { font-size: 22px; }

        /* Feature card */
        .feature-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 28px;
            transition: all .25s cubic-bezier(.4,0,.2,1);
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px -12px rgba(79,70,229,.13); }
        .feature-icon-wrap {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
        }

        /* Product card */
        .product-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            transition: all .25s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column; gap: 12px;
        }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px -8px rgba(79,70,229,.10); }
        .product-card-header { display: flex; align-items: center; justify-content: space-between; }
        .product-icon-box { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .product-arrow-btn {
            width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9;
            display: flex; align-items: center; justify-content: center; transition: all .2s;
            color: #64748b;
        }
        .product-card:hover .product-arrow-btn { background: var(--brand-primary); color: #fff; }

        /* How it works step */
        .step-card {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            padding: 32px 24px;
            text-align: center;
            position: relative;
            display: flex; flex-direction: column; align-items: center; gap: 14px;
        }
        .step-num {
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 14px; color: #fff;
            box-shadow: 0 4px 14px -4px rgba(79,70,229,.4);
        }
        .step-icon-circle {
            width: 64px; height: 64px; border-radius: 18px;
            background: #f1f5f9; display: flex; align-items: center; justify-content: center;
        }

        /* Testimonial card */
        .review-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            transition: all .25s;
        }
        .review-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px -8px rgba(0,0,0,.08); }
        .reviewer-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            object-fit: cover; border: 2px solid #e2e8f0;
        }

        /* CTA banner */
        .cta-banner {
            background: linear-gradient(120deg, #1e2d6b 0%, #312e81 40%, #4c1d95 100%);
            border-radius: 24px;
            position: relative;
            overflow: hidden;
        }
        .cta-banner::before {
            content:'';
            position: absolute; inset: 0;
            background: radial-gradient(circle at 30% 50%, rgba(99,102,241,.25) 0%, transparent 70%);
        }

        /* Footer */
        .site-footer { background: #0f172a; }
        .footer-social a {
            width: 32px; height: 32px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            background: #1e293b; color: #94a3b8; transition: all .2s;
        }
        .footer-social a:hover { background: var(--brand-primary); color: #fff; }

        /* Newsletter */
        .newsletter-form { display: flex; gap: 8px; }
        .newsletter-input {
            flex: 1; background: #1e293b; border: 1px solid #334155;
            color: #fff; padding: 10px 14px; border-radius: 10px; font-size: 12px;
            outline: none;
        }
        .newsletter-input::placeholder { color: #64748b; }
        .newsletter-btn {
            width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; cursor: pointer; border: none;
        }

        /* Dashed connector (desktop only) */
        @media(min-width:768px) {
            .step-connector {
                position: absolute;
                top: 50%;
                width: 60px;
                border-top: 2px dashed #c7d2fe;
                z-index: 0;
            }
        }

        /* Responsive tweaks */
        @media(max-width:640px){
            .cat-pill { padding: 8px 10px; min-width: 60px; font-size: 10px; }
            .cat-pill .cat-icon { font-size: 18px; }
        }

        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased overflow-x-hidden">

    {{-- ════════════════════════════════════════════════════════════
         TOP ANNOUNCEMENT BAR
    ════════════════════════════════════════════════════════════ --}}
    <div class="ticker-bar text-white text-[11px] font-semibold py-1.5 px-4 text-center">
        🎉 Special Offer: Get Started with <strong>{{ $agency->name }}</strong> Today & Automate Your Business!
    </div>

    {{-- ════════════════════════════════════════════════════════════
         HEADER / NAV
    ════════════════════════════════════════════════════════════ --}}
    <header class="site-header sticky top-0 z-50 bg-white/95 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[68px] flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="/" class="flex items-center space-x-2.5 shrink-0 group">
                @if(!empty($agency->logo))
                    <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" class="h-9 w-auto object-contain">
                @else
                    <div class="w-9 h-9 rounded-2xl bg-brand-gradient text-white flex items-center justify-center font-black text-sm shadow-md shadow-indigo-500/25 group-hover:scale-105 transition-transform shrink-0">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                    <span class="font-black text-lg text-slate-900 font-heading tracking-tight">{{ $agency->name }}</span>
                @endif
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-7 text-[13px] font-bold text-slate-600">
                <a href="#products"     class="hover:text-indigo-600 transition">Products</a>
                <a href="#features"     class="hover:text-indigo-600 transition">Solutions</a>
                <a href="/about"        class="hover:text-indigo-600 transition">About Us</a>
                <a href="#how-it-works" class="hover:text-indigo-600 transition">How It Works</a>
                <a href="#testimonials" class="hover:text-indigo-600 transition">Reviews</a>
                <a href="#faq"          class="hover:text-indigo-600 transition">FAQ</a>
            </nav>

            {{-- CTA --}}
            <div class="hidden sm:flex items-center gap-3">
                <a href="{{ $agency->cta_url ?? '/login' }}" class="text-[13px] font-bold text-slate-600 hover:text-indigo-600 transition px-2 py-1.5">Login</a>
                <a href="{{ $agency->cta_url ?? '/login' }}" class="bg-brand-gradient text-white text-xs font-extrabold px-5 py-2.5 rounded-full shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-[1.03] active:scale-[0.98] transition-all flex items-center gap-1.5 whitespace-nowrap">
                    {{ $agency->cta_text ?? 'Get Started Free' }}
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            {{-- Mobile Hamburger --}}
            <button onclick="toggleMobileMenu()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                <i data-lucide="menu" id="hamburger-icon" class="w-6 h-6"></i>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-100 px-5 py-5 space-y-4 shadow-xl">
            <nav class="flex flex-col gap-3 text-sm font-bold text-slate-700">
                <a href="#products"     onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1">Products</a>
                <a href="#features"     onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1">Solutions</a>
                <a href="/about"        onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1">About Us</a>
                <a href="#how-it-works" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1">How It Works</a>
                <a href="#testimonials" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1">Reviews</a>
                <a href="#faq"          onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1">FAQ</a>
            </nav>
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2.5">
                <a href="{{ $agency->cta_url ?? '/login' }}" class="w-full text-center text-sm font-bold text-slate-800 bg-slate-100 py-3 rounded-xl">Login</a>
                <a href="{{ $agency->cta_url ?? '/login' }}" class="w-full text-center text-sm font-extrabold text-white bg-brand-gradient py-3 rounded-xl">{{ $agency->cta_text ?? 'Get Started Free' }} →</a>
            </div>
        </div>
    </header>

    {{-- ════════════════════════════════════════════════════════════
         HERO SECTION
    ════════════════════════════════════════════════════════════ --}}
    <section class="hero-bg-glow pt-10 pb-16 sm:pt-16 sm:pb-24 lg:pt-20 lg:pb-28 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-12 items-center">

                {{-- Left text --}}
                <div class="space-y-6 text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 text-indigo-700 px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm">
                        ⚡ All-in-One Growth Platform for Indian Businesses
                    </span>

                    <h1 class="font-heading text-[2.2rem] sm:text-5xl lg:text-[3.4rem] font-black text-slate-950 leading-[1.12] tracking-tight">
                        {{ $agency->hero_title ?? 'Build. Automate.' }}
                        <span class="text-brand-gradient block mt-1">Scale. All in One</span>
                    </h1>

                    <p class="text-sm sm:text-[15px] lg:text-base text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        {{ $agency->hero_subtitle ?? ($agency->name . ' helps Indian businesses grow faster with powerful tools for marketing, sales, customer loyalty, and automation — all in one place.') }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3">
                        <a href="{{ $agency->cta_url ?? '/login' }}" class="w-full sm:w-auto bg-brand-gradient text-white text-sm font-extrabold px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            Start Your Free Trial
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        <a href="#how-it-works" class="w-full sm:w-auto bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 text-sm font-bold px-7 py-4 rounded-2xl shadow-sm transition-all flex items-center justify-center gap-2">
                            <i data-lucide="play-circle" class="w-4 h-4 text-indigo-600"></i>
                            Book a Live Demo
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-5 text-xs text-slate-500 font-bold">
                        <span class="flex items-center gap-1.5"><i data-lucide="check-circle" class="w-3.5 h-3.5 text-indigo-500"></i> No Credit Card</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="zap" class="w-3.5 h-3.5 text-indigo-500"></i> Easy Setup</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-indigo-500"></i> Cancel Anytime</span>
                    </div>
                </div>

                {{-- Right hero image --}}
                <div class="flex justify-center lg:justify-end">
                    <img src="{{ $heroImg }}" alt="{{ $agency->name }} Dashboard"
                         class="w-full max-w-lg lg:max-w-none h-auto rounded-3xl shadow-2xl border border-slate-200/60 object-contain hover:scale-[1.01] transition-transform duration-300">
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         TRUST BAR — Trusted by 10,000+ + Category Icons
    ════════════════════════════════════════════════════════════ --}}
    <section class="py-8 sm:py-10 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
            <p class="text-xs font-extrabold uppercase tracking-widest text-slate-400">
                Trusted by 10,000+ Local Businesses Across India
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                @foreach($categories as $cat)
                    <div class="cat-pill">
                        <span class="cat-icon">{{ $cat['icon'] }}</span>
                        <span>{{ $cat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         WHY CHOOSE — 4 Feature Cards
    ════════════════════════════════════════════════════════════ --}}
    <section id="features" class="py-16 sm:py-20 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-2 max-w-2xl mx-auto">
                <h2 class="font-heading text-2xl sm:text-[2.2rem] font-extrabold text-slate-900 leading-tight">
                    Why Choose {{ $agency->name }}?
                </h2>
                <p class="text-sm text-slate-500">
                    Everything you need to run, grow and scale your business — without juggling multiple tools.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                @foreach($features as $f)
                    <div class="feature-card">
                        <div class="feature-icon-wrap mb-4 bg-gradient-to-br {{ $f['gradient'] ?? 'from-indigo-500 to-purple-600' }}">
                            <i data-lucide="{{ $f['icon'] ?? 'zap' }}" class="w-6 h-6 text-white"></i>
                        </div>
                        <h3 class="font-heading text-[15px] font-bold text-slate-900 mb-1.5">{{ $f['title'] }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         SMART TOOLS FOR SMARTER BUSINESSES
    ════════════════════════════════════════════════════════════ --}}
    <section id="products" class="py-16 sm:py-24 bg-[#f4f3ff]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">

                {{-- Left info column --}}
                <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-24">
                    <span class="inline-block px-3.5 py-1 bg-indigo-100 text-indigo-700 font-bold text-xs rounded-full">Our Products</span>
                    <h2 class="font-heading text-[1.85rem] sm:text-[2.3rem] font-extrabold text-slate-900 leading-tight">
                        Smart Tools for
                        <span class="text-brand-gradient">Smarter Businesses</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        A complete suite of business growth tools designed for Indian entrepreneurs and local businesses.
                    </p>
                    <a href="{{ $agency->cta_url ?? '/login' }}" class="inline-flex items-center gap-2 bg-brand-gradient text-white text-xs font-extrabold px-6 py-3 rounded-2xl shadow-lg shadow-indigo-500/25 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Explore All Products
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                {{-- Right 2×3 product grid --}}
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $productIcons = [
                            'AI Reviews + CRM'    => ['icon' => 'star',         'bg' => '#ede9fe', 'color' => '#7c3aed'],
                            'Website Builder'      => ['icon' => 'monitor',     'bg' => '#dbeafe', 'color' => '#1d4ed8'],
                            'Digital V-Card'       => ['icon' => 'user',        'bg' => '#d1fae5', 'color' => '#059669'],
                            'QR Menu & Ordering'   => ['icon' => 'qr-code',    'bg' => '#fce7f3', 'color' => '#be185d'],
                            'Loyalty Program'      => ['icon' => 'gift',        'bg' => '#fef3c7', 'color' => '#d97706'],
                            'Business Analytics'   => ['icon' => 'bar-chart-3', 'bg' => '#ccfbf1', 'color' => '#0d9488'],
                        ];
                    @endphp
                    @foreach($services as $s)
                        @php
                            $pStyle = $productIcons[$s['title']] ?? ['icon' => $s['icon'] ?? 'box', 'bg' => '#ede9fe', 'color' => '#7c3aed'];
                        @endphp
                        <div class="product-card">
                            <div class="product-card-header">
                                <div class="product-icon-box" style="background:{{ $pStyle['bg'] }}">
                                    <i data-lucide="{{ $pStyle['icon'] }}" class="w-5 h-5" style="color:{{ $pStyle['color'] }}"></i>
                                </div>
                                <div class="product-arrow-btn">
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-heading text-sm font-bold text-slate-900 mb-1">{{ $s['title'] }}</h3>
                                <p class="text-[11px] text-slate-500 leading-relaxed">{{ $s['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         HOW IT WORKS — 3 Steps
    ════════════════════════════════════════════════════════════ --}}
    <section id="how-it-works" class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-2 max-w-2xl mx-auto">
                <h2 class="font-heading text-2xl sm:text-[2.2rem] font-extrabold text-slate-900">How It Works?</h2>
                <p class="text-sm text-slate-500">Get started in 3 simple steps and transform your business today.</p>
            </div>

            <div class="relative">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 relative">

                    {{-- Dashed connector lines (hidden on mobile) --}}
                    <div class="hidden md:block absolute top-1/2 left-[calc(33.33%-10px)] w-[calc(33.33%+20px)] -translate-y-[40px] z-0 pointer-events-none" style="border-top:2px dashed #c7d2fe;"></div>
                    <div class="hidden md:block absolute top-1/2 left-[calc(66.66%-10px)] w-[calc(33.33%+10px)] -translate-y-[40px] z-0 pointer-events-none" style="border-top:2px dashed #c7d2fe;"></div>

                    {{-- Step 1 --}}
                    <div class="step-card z-10">
                        <div class="step-num" style="background:linear-gradient(135deg,#4f46e5,#7c3aed)">01</div>
                        <div class="step-icon-circle">
                            <i data-lucide="user-plus" class="w-7 h-7 text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="font-heading text-base font-bold text-slate-900 mb-1">Sign Up</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Create your account in<br>less than 2 minutes.</p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="step-card z-10">
                        <div class="step-num" style="background:linear-gradient(135deg,#2563eb,#4f46e5)">02</div>
                        <div class="step-icon-circle">
                            <i data-lucide="monitor" class="w-7 h-7 text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-heading text-base font-bold text-slate-900 mb-1">Set Up Your Business</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Choose the tools you need<br>and customize in minutes.</p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="step-card z-10">
                        <div class="step-num" style="background:linear-gradient(135deg,#0d9488,#059669)">03</div>
                        <div class="step-icon-circle">
                            <i data-lucide="trending-up" class="w-7 h-7 text-teal-600"></i>
                        </div>
                        <div>
                            <h3 class="font-heading text-base font-bold text-slate-900 mb-1">Grow Faster</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Get more customers, more<br>reviews and more revenue.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         TESTIMONIALS — "Loved by Business Owners"
    ════════════════════════════════════════════════════════════ --}}
    <section id="testimonials" class="py-16 sm:py-20 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                {{-- Left heading block --}}
                <div class="lg:col-span-4 space-y-4 lg:sticky lg:top-24">
                    <h2 class="font-heading text-[1.8rem] sm:text-[2.2rem] font-extrabold text-slate-900 leading-tight">
                        Loved by<br>Business Owners
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500">See what our customers say about their growth with {{ $agency->name }}.</p>
                    <div class="flex items-center gap-3 pt-2">
                        <button id="prev-testimonial" onclick="prevTestimonial()" class="w-10 h-10 rounded-full border-2 border-slate-200 flex items-center justify-center text-slate-500 hover:border-indigo-500 hover:text-indigo-600 transition">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                        <button id="next-testimonial" onclick="nextTestimonial()" class="w-10 h-10 rounded-full bg-brand-gradient text-white flex items-center justify-center shadow-md hover:scale-105 transition">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                {{-- Right: testimonial cards --}}
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4" id="testimonial-grid">
                    @foreach($testimonials as $idx => $t)
                        <div class="review-card {{ $idx >= 3 ? 'hidden xl:block' : '' }}">
                            {{-- Stars --}}
                            <div class="flex text-amber-400 gap-0.5 mb-3">
                                @for($i = 0; $i < ($t['rating'] ?? 5); $i++)
                                    <i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i>
                                @endfor
                            </div>

                            {{-- Reviewer identity --}}
                            <div class="flex items-center gap-3 mb-3">
                                @if(!empty($t['avatar']))
                                    <img src="{{ asset($t['avatar']) }}" alt="{{ $t['name'] }}" class="reviewer-avatar">
                                @else
                                    <div class="reviewer-avatar bg-brand-gradient text-white font-black flex items-center justify-center text-xs" style="border:none">
                                        {{ strtoupper(substr($t['name'] ?? 'O', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-heading text-[13px] font-bold text-slate-900">{{ $t['name'] }}</h4>
                                    <p class="text-[10px] text-slate-400">{{ $t['role'] }}</p>
                                </div>
                            </div>

                            {{-- Comment --}}
                            <p class="text-[12px] text-slate-600 leading-relaxed">"{{ $t['comment'] }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         FAQ SECTION
    ════════════════════════════════════════════════════════════ --}}
    <section id="faq" class="py-16 sm:py-20 bg-white border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center space-y-2">
                <h2 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Frequently Asked Questions</h2>
                <p class="text-sm text-slate-500">Have questions? We are here to help.</p>
            </div>
            <div class="space-y-3">
                @foreach($faqs as $item)
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 sm:p-6 space-y-2">
                        <h4 class="font-heading text-sm font-bold text-slate-900">{{ $item['q'] }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $item['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         CTA BANNER — "Ready to Take Your Business to the Next Level?"
    ════════════════════════════════════════════════════════════ --}}
    <section class="py-12 sm:py-16 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="cta-banner px-6 sm:px-12 lg:px-16 py-10 sm:py-14 flex flex-col lg:flex-row items-center justify-between gap-8 sm:gap-10">

                {{-- Left text --}}
                <div class="space-y-4 text-center lg:text-left max-w-xl z-10">
                    <h2 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight">
                        Ready to Take Your Business<br class="hidden sm:block"> to the Next Level?
                    </h2>
                    <p class="text-sm text-indigo-200">
                        Join thousands of growing businesses with {{ $agency->name }} today.
                    </p>
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4 text-xs font-bold text-indigo-200">
                        <span class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5"></i> Quick Setup</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5"></i> No Credit Card Required</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5"></i> 24/7 Support</span>
                    </div>
                    <div class="pt-2">
                        <a href="{{ $agency->cta_url ?? '/login' }}" class="inline-flex items-center gap-2 bg-white text-slate-900 hover:bg-slate-50 text-sm font-extrabold px-7 py-3.5 rounded-2xl shadow-xl transition-all hover:scale-[1.02]">
                            Get Started Free
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                {{-- Right image --}}
                <div class="shrink-0 flex justify-center z-10">
                    <img src="{{ $ctaImg }}" alt="Grow with {{ $agency->name }}"
                         class="max-w-[220px] sm:max-w-xs lg:max-w-sm w-full h-auto object-contain drop-shadow-2xl hover:scale-[1.02] transition-transform duration-300">
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════
         FOOTER
    ════════════════════════════════════════════════════════════ --}}
    <footer class="site-footer text-slate-400 pt-14 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8 pb-10 border-b border-slate-800">

                {{-- Col 1: Brand + Social --}}
                <div class="col-span-2 sm:col-span-1 space-y-4">
                    <div class="flex items-center gap-2.5">
                        @if(!empty($agency->logo))
                            <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" class="h-8 w-auto">
                        @else
                            <div class="w-8 h-8 rounded-xl bg-brand-gradient text-white flex items-center justify-center font-black text-sm shrink-0">
                                <i data-lucide="layers" class="w-4 h-4"></i>
                            </div>
                            <span class="font-heading font-black text-base text-white">{{ $agency->name }}</span>
                        @endif
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400 max-w-[200px]">
                        {{ $agency->footer_content ?? 'Powering the growth of Indian local businesses with smart digital solutions.' }}
                    </p>
                    <div class="footer-social flex items-center gap-2 pt-1">
                        @if(!empty($agency->facebook_url))
                            <a href="{{ $agency->facebook_url }}" target="_blank"><i data-lucide="facebook" class="w-3.5 h-3.5"></i></a>
                        @else
                            <a href="#"><i data-lucide="facebook" class="w-3.5 h-3.5"></i></a>
                        @endif
                        @if(!empty($agency->instagram_url))
                            <a href="{{ $agency->instagram_url }}" target="_blank"><i data-lucide="instagram" class="w-3.5 h-3.5"></i></a>
                        @else
                            <a href="#"><i data-lucide="instagram" class="w-3.5 h-3.5"></i></a>
                        @endif
                        @if(!empty($agency->youtube_url))
                            <a href="{{ $agency->youtube_url }}" target="_blank"><i data-lucide="youtube" class="w-3.5 h-3.5"></i></a>
                        @else
                            <a href="#"><i data-lucide="youtube" class="w-3.5 h-3.5"></i></a>
                        @endif
                        @if(!empty($agency->linkedin_url))
                            <a href="{{ $agency->linkedin_url }}" target="_blank"><i data-lucide="linkedin" class="w-3.5 h-3.5"></i></a>
                        @else
                            <a href="#"><i data-lucide="linkedin" class="w-3.5 h-3.5"></i></a>
                        @endif
                        @if(!empty($agency->twitter_url))
                            <a href="{{ $agency->twitter_url }}" target="_blank"><i data-lucide="twitter" class="w-3.5 h-3.5"></i></a>
                        @else
                            <a href="#"><i data-lucide="twitter" class="w-3.5 h-3.5"></i></a>
                        @endif
                    </div>
                </div>

                {{-- Col 2: Products --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Products</h4>
                    <ul class="space-y-2 text-xs">
                        @foreach($services as $s)
                            <li><a href="#products" class="flex items-center gap-1.5 hover:text-white transition"><i data-lucide="chevron-right" class="w-3 h-3"></i>{{ $s['title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Col 3: Solutions --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Solutions</h4>
                    <ul class="space-y-2 text-xs">
                        @foreach($categories as $cat)
                            @if($cat['label'] !== '& Many More')
                                <li><a href="#" class="flex items-center gap-1.5 hover:text-white transition"><i data-lucide="chevron-right" class="w-3 h-3"></i>{{ $cat['label'] }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                {{-- Col 4: Company --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Company</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/about"            class="flex items-center gap-1.5 hover:text-white transition"><i data-lucide="chevron-right" class="w-3 h-3"></i>About Us</a></li>
                        <li><a href="#features"         class="flex items-center gap-1.5 hover:text-white transition"><i data-lucide="chevron-right" class="w-3 h-3"></i>Pricing</a></li>
                        <li><a href="#"                 class="flex items-center gap-1.5 hover:text-white transition"><i data-lucide="chevron-right" class="w-3 h-3"></i>Blog</a></li>
                        <li><a href="#"                 class="flex items-center gap-1.5 hover:text-white transition"><i data-lucide="chevron-right" class="w-3 h-3"></i>Careers</a></li>
                        <li><a href="/contact"          class="flex items-center gap-1.5 hover:text-white transition"><i data-lucide="chevron-right" class="w-3 h-3"></i>Contact Us</a></li>
                    </ul>
                </div>

                {{-- Col 5: Newsletter --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Subscribe to our newsletter</h4>
                    <p class="text-xs text-slate-500">Get updates, tips and offers.</p>
                    <div class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="Enter your email">
                        <button type="button" class="newsletter-btn bg-brand-gradient" onclick="alert('Thank you for subscribing!')">
                            <i data-lucide="send" class="w-4 h-4 text-white"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="pt-7 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-500 gap-3">
                <div>© {{ date('Y') }} {{ $agency->name }}. All rights reserved.</div>
                <div class="flex items-center gap-1">Made with <span class="text-red-500 mx-0.5">❤️</span> in India 🇮🇳</div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }

        // Simple testimonial navigation (carousel feel)
        let currentSlide = 0;
        function nextTestimonial() {
            const cards = document.querySelectorAll('#testimonial-grid .review-card');
            if (!cards.length) return;
            currentSlide = (currentSlide + 1) % cards.length;
            // On mobile, scroll to next card
            cards[currentSlide].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
        function prevTestimonial() {
            const cards = document.querySelectorAll('#testimonial-grid .review-card');
            if (!cards.length) return;
            currentSlide = (currentSlide - 1 + cards.length) % cards.length;
            cards[currentSlide].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    </script>
</body>
</html>
