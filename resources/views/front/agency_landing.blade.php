<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agency->meta_title ?? ($agency->name . ' — All-in-One Business Growth Platform') }}</title>
    <meta name="description" content="{{ $agency->meta_description ?? ($agency->hero_subtitle ?? 'Empowering businesses with smart digital tools.') }}">
    
    @if(!empty($agency->favicon))
        <link rel="icon" type="image/png" href="{{ asset($agency->favicon) }}">
    @endif

    <!-- OpenGraph Tags -->
    <meta property="og:title" content="{{ $agency->meta_title ?? $agency->name }}">
    <meta property="og:description" content="{{ $agency->meta_description ?? $agency->hero_subtitle }}">
    @if(!empty($agency->og_image ?? $agency->hero_image))
        <meta property="og:image" content="{{ asset($agency->og_image ?? $agency->hero_image) }}">
    @endif

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @php
        $primaryColor = $agency->primary_color ?? '#4f46e5';
        $secondaryColor = $agency->secondary_color ?? '#9333ea';
        $accentColor = $agency->accent_color ?? '#3b82f6';
        
        $services = is_array($agency->services_data ?? null) ? $agency->services_data : (json_decode($agency->services_data ?? '[]', true) ?: [
            ['title' => 'AI Reviews + CRM', 'desc' => 'Get more 5-star reviews & manage customers easily', 'icon' => 'star'],
            ['title' => 'Website Builder', 'desc' => 'Create stunning websites with AI', 'icon' => 'globe'],
            ['title' => 'Digital V-Card', 'desc' => 'Share your business digitally, smartly', 'icon' => 'credit-card'],
            ['title' => 'QR Menu & Ordering', 'desc' => 'Contactless menu for restaurants & cafes', 'icon' => 'qr-code'],
            ['title' => 'Loyalty Program', 'desc' => 'Reward your customers and increase repeat sales', 'icon' => 'gift'],
            ['title' => 'Business Analytics', 'desc' => 'Track growth with real-time insights', 'icon' => 'bar-chart'],
        ]);

        $testimonials = is_array($agency->testimonials_data ?? null) ? $agency->testimonials_data : (json_decode($agency->testimonials_data ?? '[]', true) ?: [
            ['name' => 'Rahul Sharma', 'role' => 'Restaurant Owner, Delhi', 'rating' => 5, 'comment' => "{$agency->name} helped us get 3x more online orders in just 2 months. The QR menu and reviews feature is amazing!"],
            ['name' => 'Priya Mehta', 'role' => 'Salon Owner, Mumbai', 'rating' => 5, 'comment' => 'Super easy to use and really effective. Our customer engagement has never been better!'],
            ['name' => 'Amit Verma', 'role' => 'Clinic Owner, Bengaluru', 'rating' => 5, 'comment' => 'The digital tools, CRM and reminders have saved us hours of work every week.'],
        ]);

        $features = is_array($agency->features_data ?? null) ? $agency->features_data : (json_decode($agency->features_data ?? '[]', true) ?: [
            ['title' => 'Get More Customers', 'desc' => 'Build trust with reviews, smart websites and digital presence.'],
            ['title' => 'Save Time & Effort', 'desc' => 'Automate repetitive tasks and focus on what matters most.'],
            ['title' => 'Increase Revenue', 'desc' => 'Drive repeat business with loyalty programs & digital tools.'],
            ['title' => 'Reliable & Secure', 'desc' => 'Your business data is safe with enterprise-grade security.'],
        ]);

        $socialLinks = is_array($agency->social_links ?? null) ? $agency->social_links : (json_decode($agency->social_links ?? '[]', true) ?: []);
    @endphp

    <style>
        :root {
            --brand-primary: {{ $primaryColor }};
            --brand-secondary: {{ $secondaryColor }};
            --brand-accent: {{ $accentColor }};
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .bg-brand-gradient {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
        }
        .text-brand-gradient {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-bg-glow {
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.12) 0%, rgba(168, 85, 247, 0.05) 50%, transparent 70%);
        }
        .card-hover-effect {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover-effect:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(79, 70, 229, 0.12);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased overflow-x-hidden">

    <!-- Top Announcement Bar (Optional) -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-purple-900 text-white text-[11px] font-semibold py-1.5 px-4 text-center">
        <span>🎉 Special Launch Offer: Get Started with <strong>{{ $agency->name }}</strong> Today & Automate Your Business!</span>
    </div>

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-3 group">
                @if(!empty($agency->logo))
                    <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" class="h-9 w-auto object-contain">
                @else
                    <div class="w-10 h-10 rounded-2xl bg-brand-gradient text-white flex items-center justify-center font-bold text-lg shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                    <span class="font-extrabold text-xl text-slate-900 font-heading tracking-tight">
                        {{ $agency->name }}
                    </span>
                @endif
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center space-x-8 text-xs font-bold text-slate-600">
                <a href="#products" class="hover:text-indigo-600 transition">Products</a>
                <a href="#features" class="hover:text-indigo-600 transition">Solutions</a>
                <a href="#how-it-works" class="hover:text-indigo-600 transition">How It Works</a>
                <a href="#testimonials" class="hover:text-indigo-600 transition">Reviews</a>
                <a href="#faq" class="hover:text-indigo-600 transition">FAQ</a>
            </nav>

            <!-- Header Action Buttons -->
            <div class="flex items-center space-x-4">
                <a href="{{ $agency->cta_url ?? '/login' }}" class="text-xs font-bold text-slate-700 hover:text-indigo-600 transition px-3 py-2">
                    Login
                </a>
                <a href="{{ $agency->cta_url ?? '/login' }}" class="bg-brand-gradient text-white text-xs font-extrabold px-5 py-2.5 rounded-full shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center space-x-2">
                    <span>{{ $agency->cta_text ?? 'Get Started' }}</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

        </div>
    </header>

    <!-- HERO SECTION (Matching Image 1 SaaS Yaari Pixel-Perfect Design) -->
    <section class="relative pt-12 pb-20 md:pt-16 md:pb-28 hero-bg-glow overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Hero Text Content -->
                <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
                    
                    <!-- Tagline Pill Badge -->
                    <div class="inline-flex items-center space-x-2 bg-indigo-50 border border-indigo-100 text-indigo-700 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm">
                        <span>⚡ Empowering Local Businesses</span>
                    </div>

                    <!-- Hero Main Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-950 font-heading leading-[1.15] tracking-tight">
                        {{ $agency->hero_title ?? 'Grow, Manage & Automate Your Business —' }}
                        <span class="text-brand-gradient block">All in One Place</span>
                    </h1>

                    <!-- Hero Subtitle -->
                    <p class="text-base sm:text-lg text-slate-600 font-normal leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        {{ $agency->hero_subtitle ?? 'The most powerful SaaS platform for local businesses to get more customers, save time and grow faster.' }}
                    </p>

                    <!-- Feature Bullet Pills -->
                    <div class="flex flex-wrap justify-center lg:justify-start gap-3 text-xs font-bold text-slate-700 pt-1">
                        <span class="inline-flex items-center space-x-1.5 bg-white border border-slate-200/80 px-3 py-1.5 rounded-xl shadow-sm">
                            <i data-lucide="shield-check" class="w-4 h-4 text-indigo-600"></i>
                            <span>All-in-One Business Toolkit</span>
                        </span>
                        <span class="inline-flex items-center space-x-1.5 bg-white border border-slate-200/80 px-3 py-1.5 rounded-xl shadow-sm">
                            <i data-lucide="zap" class="w-4 h-4 text-indigo-600"></i>
                            <span>Easy to Use, No Tech Skills</span>
                        </span>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-3">
                        <a href="{{ $agency->cta_url ?? '/login' }}" class="w-full sm:w-auto bg-brand-gradient text-white text-sm font-extrabold px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center space-x-2">
                            <span>{{ $agency->cta_text ?? 'Start Free Today' }}</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        <a href="#how-it-works" class="w-full sm:w-auto bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 text-sm font-bold px-7 py-4 rounded-2xl shadow-sm transition-all flex items-center justify-center space-x-2">
                            <i data-lucide="play-circle" class="w-4 h-4 text-indigo-600"></i>
                            <span>Watch Demo</span>
                        </a>
                    </div>

                    <!-- Trust Micro-Subtext -->
                    <div class="flex items-center justify-center lg:justify-start space-x-6 text-xs text-slate-500 font-medium pt-2">
                        <span class="flex items-center"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 mr-1"></i> No Credit Card Required</span>
                        <span class="flex items-center"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 mr-1"></i> Setup in 2 Minutes</span>
                    </div>

                </div>

                <!-- Right Hero Graphic / Mockup (Pixel-Perfect Dashboard Preview from Reference Image) -->
                <div class="lg:col-span-6 relative">
                    <div class="relative mx-auto max-w-lg lg:max-w-none">
                        
                        <!-- Main Dashboard Card Mockup -->
                        <div class="bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 shadow-2xl text-white border border-slate-800/80 relative z-10">
                            
                            <!-- Mockup Top Bar -->
                            <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-xl bg-brand-gradient flex items-center justify-center font-bold text-xs">
                                        <i data-lucide="layout" class="w-4 h-4"></i>
                                    </div>
                                    <span class="font-bold text-sm tracking-tight">{{ $agency->name }} Dashboard</span>
                                </div>
                                <div class="flex space-x-1.5">
                                    <div class="w-3 h-3 rounded-full bg-rose-500/80"></div>
                                    <div class="w-3 h-3 rounded-full bg-amber-500/80"></div>
                                    <div class="w-3 h-3 rounded-full bg-emerald-500/80"></div>
                                </div>
                            </div>

                            <!-- Dashboard Stats Grid Mockup -->
                            <div class="grid grid-cols-2 gap-4 mt-6">
                                <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-4">
                                    <p class="text-[11px] font-semibold text-slate-400">Total Customers</p>
                                    <h4 class="text-2xl font-black text-white font-heading mt-1">12,458</h4>
                                    <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full inline-block mt-2">↑ +12.5% vs last month</span>
                                </div>
                                <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-4">
                                    <p class="text-[11px] font-semibold text-slate-400">New Reviews</p>
                                    <h4 class="text-2xl font-black text-white font-heading mt-1">1,248</h4>
                                    <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full inline-block mt-2">↑ +18.3% vs last month</span>
                                </div>
                            </div>

                            <!-- Growth Chart Mockup Graphic -->
                            <div class="mt-6 bg-slate-800/40 border border-slate-700/40 rounded-2xl p-4">
                                <div class="flex items-center justify-between text-xs font-bold mb-3">
                                    <span>Business Growth</span>
                                    <span class="text-emerald-400">+28.4% This Month</span>
                                </div>
                                <div class="h-20 flex items-end justify-between gap-2 pt-2">
                                    <div class="w-full bg-indigo-500/20 rounded-t-lg h-[40%]"></div>
                                    <div class="w-full bg-indigo-500/30 rounded-t-lg h-[55%]"></div>
                                    <div class="w-full bg-indigo-500/40 rounded-t-lg h-[45%]"></div>
                                    <div class="w-full bg-indigo-500/60 rounded-t-lg h-[70%]"></div>
                                    <div class="w-full bg-indigo-500/80 rounded-t-lg h-[60%]"></div>
                                    <div class="w-full bg-brand-gradient rounded-t-lg h-[95%]"></div>
                                </div>
                            </div>

                        </div>

                        <!-- Floating Rating Badge (Bottom Left) -->
                        <div class="absolute -bottom-6 -left-4 sm:-left-6 bg-white border border-slate-100 rounded-2xl p-4 shadow-xl z-20 flex items-center space-x-3">
                            <span class="text-3xl font-black text-slate-900 font-heading">4.9</span>
                            <div>
                                <div class="flex text-amber-400">
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                </div>
                                <p class="text-[10px] font-bold text-slate-500 mt-0.5">2,000+ Verified Reviews</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- TRUST BAR CATEGORIES (Matching Reference Image) -->
    <section class="py-12 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <p class="text-xs font-extrabold uppercase tracking-widest text-slate-400">
                Trusted by 10,000+ Local Businesses Across India
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <span class="px-4 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🍽️ Restaurants</span>
                <span class="px-4 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🏥 Clinics</span>
                <span class="px-4 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">💇 Salons & Spas</span>
                <span class="px-4 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🛍️ Retail Shops</span>
                <span class="px-4 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🏨 Hotels</span>
                <span class="px-4 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🏋️ Gyms & Fitness</span>
                <span class="px-4 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🏠 Real Estate</span>
                <span class="px-4 py-2 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-400">& Many More</span>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE SECTION (4 Value Proposition Cards) -->
    <section id="features" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    Why Choose {{ $agency->name }}?
                </h2>
                <p class="text-sm text-slate-600">
                    Everything you need to run, grow and scale your business — without juggling multiple tools.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($features as $f)
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-8 card-hover-effect space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            <i data-lucide="zap" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">{{ $f['title'] }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SMART TOOLS / PRODUCTS SUITE SECTION -->
    <section id="products" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-3 max-w-2xl">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-bold text-xs rounded-full">Our Products</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                        Smart Tools for <span class="text-brand-gradient">Smarter Businesses</span>
                    </h2>
                    <p class="text-sm text-slate-600">
                        A complete suite of business growth tools designed for entrepreneurs and local businesses.
                    </p>
                </div>
                <a href="{{ $agency->cta_url ?? '/login' }}" class="bg-brand-gradient text-white text-xs font-bold px-6 py-3 rounded-2xl shadow-md flex items-center space-x-2 self-start md:self-auto">
                    <span>Explore All Products</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $s)
                    <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-8 card-hover-effect space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-gradient text-white flex items-center justify-center font-bold shadow-md shadow-indigo-500/20">
                            <i data-lucide="{{ $s['icon'] ?? 'box' }}" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">{{ $s['title'] }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $s['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS SECTION (3 Step Connected Workflow) -->
    <section id="how-it-works" class="py-20 bg-slate-50 border-y border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    How It Works?
                </h2>
                <p class="text-sm text-slate-600">
                    Get started in 3 simple steps and transform your business today.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                
                <!-- Step 1 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 text-center space-y-4 relative">
                    <span class="w-10 h-10 rounded-full bg-indigo-600 text-white font-extrabold text-sm flex items-center justify-center mx-auto shadow-md">01</span>
                    <h3 class="text-lg font-bold text-slate-900 font-heading">Sign Up</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Create your account in less than 2 minutes and get instant access.</p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 text-center space-y-4 relative">
                    <span class="w-10 h-10 rounded-full bg-purple-600 text-white font-extrabold text-sm flex items-center justify-center mx-auto shadow-md">02</span>
                    <h3 class="text-lg font-bold text-slate-900 font-heading">Set Up Your Business</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Choose the tools you need and customize in minutes.</p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 text-center space-y-4 relative">
                    <span class="w-10 h-10 rounded-full bg-emerald-600 text-white font-extrabold text-sm flex items-center justify-center mx-auto shadow-md">03</span>
                    <h3 class="text-lg font-bold text-slate-900 font-heading">Grow Faster</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Get more customers, more reviews and increase your revenue.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section id="testimonials" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    Loved by Business Owners
                </h2>
                <p class="text-sm text-slate-600">
                    See what our customers say about their growth with {{ $agency->name }}.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $t)
                    <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-8 space-y-4 flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex text-amber-400 space-x-1">
                                @for($i = 0; $i < ($t['rating'] ?? 5); $i++)
                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                @endfor
                            </div>
                            <p class="text-xs text-slate-700 italic leading-relaxed">"{{ $t['comment'] }}"</p>
                        </div>
                        <div class="pt-4 border-t border-slate-200/60 flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-brand-gradient text-white font-bold flex items-center justify-center text-xs shadow-sm shrink-0">
                                {{ substr($t['name'] ?? 'Owner', 0, 2) }}
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 font-heading">{{ $t['name'] }}</h4>
                                <p class="text-[10px] text-slate-500">{{ $t['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="py-20 bg-slate-50 border-t border-slate-200/60">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3">
                <h2 class="text-3xl font-extrabold text-slate-900 font-heading">Frequently Asked Questions</h2>
                <p class="text-sm text-slate-600">Have questions? We are here to help.</p>
            </div>

            @php
                $faqs = is_array($agency->faq_data ?? null) ? $agency->faq_data : (json_decode($agency->faq_data ?? '[]', true) ?: [
                    ['q' => 'How does the platform work?', 'a' => 'Our platform provides an all-in-one suite of growth tools designed to help local businesses manage orders, reviews, websites, and customer retention from a single place.'],
                    ['q' => 'Can I customize the features for my business?', 'a' => 'Yes, you can enable and configure the exact tools you need in just a few clicks from your dashboard.'],
                    ['q' => 'Is technical knowledge required?', 'a' => 'Not at all! Our software is built for non-technical business owners with clean, easy-to-use interfaces.'],
                ]);
            @endphp

            <div class="space-y-4">
                @foreach($faqs as $item)
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-2">
                        <h4 class="text-sm font-bold text-slate-900 font-heading">{{ $item['q'] }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $item['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA BANNER SECTION -->
    <section class="py-16 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-gradient rounded-3xl p-8 sm:p-14 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-3 text-center md:text-left">
                    <h2 class="text-3xl sm:text-4xl font-extrabold font-heading">
                        Ready to Take Your Business to the Next Level?
                    </h2>
                    <p class="text-sm text-white/90">
                        Join thousands of growing businesses with {{ $agency->name }} today.
                    </p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 text-xs font-bold pt-2">
                        <span>✓ Quick Setup</span>
                        <span>✓ No Credit Card Required</span>
                        <span>✓ 24/7 Support</span>
                    </div>
                </div>
                <a href="{{ $agency->cta_url ?? '/login' }}" class="bg-white text-slate-900 hover:bg-slate-100 text-sm font-extrabold px-8 py-4 rounded-2xl shadow-xl transition-all whitespace-nowrap">
                    Get Started Free →
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER SECTION -->
    <footer class="bg-slate-950 text-slate-400 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- Col 1: Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        @if(!empty($agency->logo))
                            <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" class="h-8 w-auto">
                        @else
                            <div class="w-8 h-8 rounded-xl bg-brand-gradient text-white flex items-center justify-center font-bold text-sm">
                                <i data-lucide="layers" class="w-4 h-4"></i>
                            </div>
                            <span class="font-bold text-lg text-white font-heading">{{ $agency->name }}</span>
                        @endif
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        {{ $agency->footer_content ?? 'Powering the growth of local businesses with smart digital solutions.' }}
                    </p>
                </div>

                <!-- Col 2: Navigation -->
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Products</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#products" class="hover:text-white transition">AI Reviews + CRM</a></li>
                        <li><a href="#products" class="hover:text-white transition">Website Builder</a></li>
                        <li><a href="#products" class="hover:text-white transition">Digital V-Card</a></li>
                    </ul>
                </div>

                <!-- Col 3: Legal Pages -->
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Legal & Policies</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/privacy-policy" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="/terms-conditions" class="hover:text-white transition">Terms & Conditions</a></li>
                        <li><a href="/cookie-policy" class="hover:text-white transition">Cookie Policy</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact Info -->
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Contact</h4>
                    <p class="text-xs text-slate-400">Email: {{ $agency->contact_email ?? 'support@' . request()->getHost() }}</p>
                    @if(!empty($agency->contact_phone))
                        <p class="text-xs text-slate-400">Phone: {{ $agency->contact_phone }}</p>
                    @endif
                </div>

            </div>

            <div class="pt-8 border-t border-slate-800 text-center text-xs text-slate-500">
                © 2026 {{ $agency->name }}. All rights reserved.
            </div>

        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
