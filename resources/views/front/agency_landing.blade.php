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

        $heroImg = !empty($agency->hero_image) ? asset($agency->hero_image) : asset('assets/landing_page/herobanner_dashboard.png');
        $aboutImg = !empty($agency->about_image) ? asset($agency->about_image) : asset('assets/landing_page/features_leftside.png');
        $ctaImg = !empty($agency->cta_image) ? asset($agency->cta_image) : asset('assets/landing_page/footer_card.png');
        
        $services = is_array($agency->services_data ?? null) ? $agency->services_data : (json_decode($agency->services_data ?? '[]', true) ?: [
            ['title' => 'AI Reviews + CRM', 'desc' => 'Get more 5-star reviews & manage customers easily', 'icon' => 'star'],
            ['title' => 'Website Builder', 'desc' => 'Create stunning websites in minutes with AI', 'icon' => 'monitor'],
            ['title' => 'Digital V-Card', 'desc' => 'Share your business digitally, smartly', 'icon' => 'user'],
            ['title' => 'QR Menu & Ordering', 'desc' => 'Contactless menu for restaurants & cafes', 'icon' => 'qr-code'],
            ['title' => 'Loyalty Program', 'desc' => 'Reward your customers and increase repeat sales', 'icon' => 'gift'],
            ['title' => 'Business Analytics', 'desc' => 'Track growth with real-time insights', 'icon' => 'bar-chart-3'],
        ]);

        $testimonials = is_array($agency->testimonials_data ?? null) ? $agency->testimonials_data : (json_decode($agency->testimonials_data ?? '[]', true) ?: [
            ['name' => 'Rahul Sharma', 'role' => 'Restaurant Owner, Delhi', 'rating' => 5, 'comment' => "{$agency->name} helped us get 3x more online orders in just 2 months. The QR menu and reviews feature is amazing!"],
            ['name' => 'Priya Mehta', 'role' => 'Salon Owner, Mumbai', 'rating' => 5, 'comment' => 'Super easy to use and really effective. Our customer engagement has never been better!'],
            ['name' => 'Amit Verma', 'role' => 'Clinic Owner, Bengaluru', 'rating' => 5, 'comment' => 'The digital tools, CRM and reminders have saved us hours of work every week.'],
        ]);

        $features = is_array($agency->features_data ?? null) ? $agency->features_data : (json_decode($agency->features_data ?? '[]', true) ?: [
            ['title' => 'Get More Customers', 'desc' => 'Build trust with reviews, smart websites and digital presence.', 'icon' => 'rocket', 'bg' => 'bg-purple-100 text-purple-600'],
            ['title' => 'Save Time & Effort', 'desc' => 'Automate repetitive tasks and focus on what matters most.', 'icon' => 'clock', 'bg' => 'bg-emerald-100 text-emerald-600'],
            ['title' => 'Increase Revenue', 'desc' => 'Drive repeat business with loyalty programs & digital tools.', 'icon' => 'trending-up', 'bg' => 'bg-orange-100 text-orange-600'],
            ['title' => 'Reliable & Secure', 'desc' => 'Your business data is safe with enterprise-grade security.', 'icon' => 'shield-check', 'bg' => 'bg-blue-100 text-blue-600'],
        ]);

        $faqs = is_array($agency->faq_data ?? null) ? $agency->faq_data : (json_decode($agency->faq_data ?? '[]', true) ?: [
            ['q' => 'How does the platform work?', 'a' => 'Our platform provides an all-in-one suite of growth tools designed to help local businesses manage orders, reviews, websites, and customer retention from a single place.'],
            ['q' => 'Can I customize the features for my business?', 'a' => 'Yes, you can enable and configure the exact tools you need in just a few clicks from your dashboard.'],
            ['q' => 'Is technical knowledge required?', 'a' => 'Not at all! Our software is built for non-technical business owners with clean, easy-to-use interfaces.'],
        ]);
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

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-purple-900 text-white text-[11px] font-semibold py-1.5 px-4 text-center">
        <span>🎉 Special Offer: Get Started with <strong>{{ $agency->name }}</strong> Today & Automate Your Business!</span>
    </div>

    <!-- Header Navigation (Fully Responsive for All Devices) -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-3 group shrink-0">
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
            <nav class="hidden lg:flex items-center space-x-8 text-xs font-bold text-slate-600">
                <a href="#products" class="hover:text-indigo-600 transition">Products</a>
                <a href="#features" class="hover:text-indigo-600 transition">Solutions</a>
                <a href="#about" class="hover:text-indigo-600 transition">About Us</a>
                <a href="#how-it-works" class="hover:text-indigo-600 transition">How It Works</a>
                <a href="#testimonials" class="hover:text-indigo-600 transition">Reviews</a>
                <a href="#faq" class="hover:text-indigo-600 transition">FAQ</a>
            </nav>

            <!-- Action Buttons (Desktop) -->
            <div class="hidden sm:flex items-center space-x-3 sm:space-x-4">
                <a href="{{ $agency->cta_url ?? '/login' }}" class="text-xs font-bold text-slate-700 hover:text-indigo-600 transition px-3 py-2">
                    Login
                </a>
                <a href="{{ $agency->cta_url ?? '/login' }}" class="bg-brand-gradient text-white text-xs font-extrabold px-5 py-2.5 rounded-full shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center space-x-2">
                    <span>{{ $agency->cta_text ?? 'Get Started Free' }}</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <!-- Mobile Hamburger Toggle Button -->
            <button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="lg:hidden p-2 rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none">
                <i data-lucide="menu" id="menu-icon" class="w-6 h-6"></i>
            </button>

        </div>

        <!-- Mobile Slide-out Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-b border-slate-200 px-6 py-6 space-y-4 shadow-xl">
            <nav class="flex flex-col space-y-3 text-sm font-bold text-slate-700">
                <a href="#products" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">Products</a>
                <a href="#features" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">Solutions</a>
                <a href="#about" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">About Us</a>
                <a href="#how-it-works" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">How It Works</a>
                <a href="#testimonials" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">Reviews</a>
                <a href="#faq" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">FAQ</a>
            </nav>
            <div class="pt-4 border-t border-slate-100 flex flex-col space-y-3">
                <a href="{{ $agency->cta_url ?? '/login' }}" class="w-full text-center text-sm font-bold text-slate-800 bg-slate-100 py-3 rounded-xl">
                    Login
                </a>
                <a href="{{ $agency->cta_url ?? '/login' }}" class="w-full text-center text-sm font-extrabold text-white bg-brand-gradient py-3 rounded-xl shadow-md">
                    {{ $agency->cta_text ?? 'Get Started Free' }} →
                </a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION (Dynamic Hero Image & Fully Mobile Responsive) -->
    <section class="relative pt-8 pb-16 sm:pt-14 sm:pb-24 lg:pt-16 lg:pb-28 hero-bg-glow overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">
                
                <!-- Left Hero Text Content -->
                <div class="lg:col-span-6 space-y-5 sm:space-y-6 text-center lg:text-left">
                    
                    <!-- Tagline Pill Badge -->
                    <div class="inline-flex items-center space-x-2 bg-indigo-50 border border-indigo-100 text-indigo-700 px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm">
                        <span>⚡ All-in-One Growth Platform for Indian Businesses</span>
                    </div>

                    <!-- Hero Main Heading -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-950 font-heading leading-[1.15] tracking-tight">
                        {{ $agency->hero_title ?? 'Build. Automate.' }}
                        <span class="text-brand-gradient block mt-1">Scale. All in One</span>
                    </h1>

                    <!-- Hero Subtitle -->
                    <p class="text-sm sm:text-base lg:text-lg text-slate-600 font-normal leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        {{ $agency->hero_subtitle ?? ($agency->name . ' helps Indian businesses grow faster with powerful tools for marketing, sales, customer loyalty, and automation — all in one place.') }}
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-2">
                        <a href="{{ $agency->cta_url ?? '/login' }}" class="w-full sm:w-auto bg-brand-gradient text-white text-sm font-extrabold px-8 py-3.5 sm:py-4 rounded-2xl shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center space-x-2">
                            <span>Start Your Free Trial</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        <a href="#how-it-works" class="w-full sm:w-auto bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 text-sm font-bold px-7 py-3.5 sm:py-4 rounded-2xl shadow-sm transition-all flex items-center justify-center space-x-2">
                            <i data-lucide="play-circle" class="w-4 h-4 text-indigo-600"></i>
                            <span>Book a Live Demo</span>
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 text-xs text-slate-500 font-bold pt-2">
                        <span class="flex items-center"><i data-lucide="percent" class="w-3.5 h-3.5 text-indigo-600 mr-1.5"></i> No Credit Card</span>
                        <span class="flex items-center"><i data-lucide="zap" class="w-3.5 h-3.5 text-indigo-600 mr-1.5"></i> Easy Setup</span>
                        <span class="flex items-center"><i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-indigo-600 mr-1.5"></i> Cancel Anytime</span>
                    </div>

                </div>

                <!-- Right Hero Graphic / Mockup (Dynamic image from user or default asset) -->
                <div class="lg:col-span-6 relative mt-4 lg:mt-0">
                    <div class="relative mx-auto max-w-lg lg:max-w-none">
                        <img src="{{ $heroImg }}" alt="{{ $agency->name }} Dashboard" class="w-full h-auto rounded-3xl shadow-2xl border border-slate-200/80 object-contain hover:scale-[1.01] transition-transform duration-300">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- TRUST BAR CATEGORIES -->
    <section class="py-8 sm:py-12 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5 sm:space-y-6">
            <p class="text-[11px] sm:text-xs font-extrabold uppercase tracking-widest text-slate-400">
                Trusted by 10,000+ Local Businesses Across India
            </p>
            <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                <span class="px-3.5 py-1.5 sm:px-4 sm:py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🍽️ Restaurants</span>
                <span class="px-3.5 py-1.5 sm:px-4 sm:py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🏥 Clinics</span>
                <span class="px-3.5 py-1.5 sm:px-4 sm:py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">💇 Salons & Spas</span>
                <span class="px-3.5 py-1.5 sm:px-4 sm:py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🛍️ Retail Shops</span>
                <span class="px-3.5 py-1.5 sm:px-4 sm:py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🏨 Hotels</span>
                <span class="px-3.5 py-1.5 sm:px-4 sm:py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🏋️ Gyms & Fitness</span>
                <span class="px-3.5 py-1.5 sm:px-4 sm:py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-700 transition">🏠 Real Estate</span>
                <span class="px-3.5 py-1.5 sm:px-4 sm:py-2 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-400">& Many More</span>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE SECTION (4 Value Cards) -->
    <section id="features" class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-16">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    Why Choose {{ $agency->name }}?
                </h2>
                <p class="text-xs sm:text-sm text-slate-600">
                    Everything you need to run, grow and scale your business — without juggling multiple tools.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                @foreach($features as $f)
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 card-hover-effect space-y-4">
                        <div class="w-12 h-12 rounded-2xl {{ $f['bg'] ?? 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center font-bold">
                            <i data-lucide="{{ $f['icon'] ?? 'zap' }}" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 font-heading">{{ $f['title'] }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SMART TOOLS FOR SMARTER BUSINESSES (6 Cards with Arrows) -->
    <section id="products" class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-16">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-3 max-w-2xl">
                    <span class="px-3.5 py-1 bg-indigo-50 text-indigo-700 font-bold text-xs rounded-full">Our Products</span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                        Smart Tools for <span class="text-brand-gradient">Smarter Businesses</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600">
                        A complete suite of business growth tools designed for Indian entrepreneurs and local businesses.
                    </p>
                </div>
                <a href="{{ $agency->cta_url ?? '/login' }}" class="bg-brand-gradient text-white text-xs font-extrabold px-6 py-3 rounded-2xl shadow-md flex items-center space-x-2 self-start md:self-auto">
                    <span>Explore All Products</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($services as $s)
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 card-hover-effect space-y-4 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                    <i data-lucide="{{ $s['icon'] ?? 'box' }}" class="w-6 h-6"></i>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition">
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 font-heading">{{ $s['title'] }}</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $s['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- BUILT FOR ENTREPRENEURS SECTION (Dynamic About Image) -->
    <section id="about" class="py-16 sm:py-24 bg-slate-50 border-t border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                
                <!-- Left Image/Graphic Column -->
                <div class="lg:col-span-6 relative">
                    <img src="{{ $aboutImg }}" alt="Built for Entrepreneurs" class="w-full h-auto rounded-3xl shadow-xl border border-slate-200 object-cover hover:scale-[1.01] transition-transform duration-300">
                </div>

                <!-- Right Content Column -->
                <div class="lg:col-span-6 space-y-6 sm:space-y-8">
                    
                    <!-- 4 Metric Cards Row -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                        <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
                            <i data-lucide="users" class="w-5 h-5 text-indigo-600"></i>
                            <h4 class="text-base sm:text-lg font-black text-slate-900 font-heading">10,000+</h4>
                            <p class="text-[10px] font-semibold text-slate-500">Happy Businesses</p>
                        </div>
                        <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
                            <i data-lucide="package" class="w-5 h-5 text-purple-600"></i>
                            <h4 class="text-base sm:text-lg font-black text-slate-900 font-heading">1M+</h4>
                            <p class="text-[10px] font-semibold text-slate-500">Orders Processed</p>
                        </div>
                        <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
                            <i data-lucide="shield" class="w-5 h-5 text-emerald-600"></i>
                            <h4 class="text-base sm:text-lg font-black text-slate-900 font-heading">500K+</h4>
                            <p class="text-[10px] font-semibold text-slate-500">Active Customers</p>
                        </div>
                        <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-1">
                            <i data-lucide="sparkles" class="w-5 h-5 text-amber-500"></i>
                            <h4 class="text-base sm:text-lg font-black text-slate-900 font-heading">99.8%</h4>
                            <p class="text-[10px] font-semibold text-slate-500">Uptime & Secure</p>
                        </div>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                            Built for entrepreneurs, by entrepreneurs.
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            {{ $agency->about_content ?? ("We understand the challenges of growing a business in India. That's why we built " . $agency->name . " — to make technology simple, affordable, and accessible for everyone.") }}
                        </p>
                    </div>

                    <a href="{{ $agency->cta_url ?? '/login' }}" class="inline-flex items-center space-x-2 bg-brand-gradient text-white text-xs font-extrabold px-7 py-3.5 rounded-2xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition">
                        <span>Explore All Features</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>

                </div>

            </div>
        </div>
    </section>

    <!-- HOW IT WORKS SECTION (3 Steps) -->
    <section id="how-it-works" class="py-16 sm:py-20 bg-white border-y border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-16">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    How It Works?
                </h2>
                <p class="text-xs sm:text-sm text-slate-600">
                    Get started in 3 simple steps and transform your business today.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 relative">
                
                <!-- Step 1 -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-6 sm:p-8 text-center space-y-4 relative">
                    <span class="w-10 h-10 rounded-full bg-indigo-600 text-white font-extrabold text-sm flex items-center justify-center mx-auto shadow-md">01</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 font-heading">Sign Up</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Create your account in less than 2 minutes and get instant access.</p>
                </div>

                <!-- Step 2 -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-6 sm:p-8 text-center space-y-4 relative">
                    <span class="w-10 h-10 rounded-full bg-purple-600 text-white font-extrabold text-sm flex items-center justify-center mx-auto shadow-md">02</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 font-heading">Set Up Your Business</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Choose the tools you need and customize in minutes.</p>
                </div>

                <!-- Step 3 -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-6 sm:p-8 text-center space-y-4 relative">
                    <span class="w-10 h-10 rounded-full bg-emerald-600 text-white font-extrabold text-sm flex items-center justify-center mx-auto shadow-md">03</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 font-heading">Grow Faster</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Get more customers, more reviews and increase your revenue.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section id="testimonials" class="py-16 sm:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-16">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    Loved by Business Owners
                </h2>
                <p class="text-xs sm:text-sm text-slate-600">
                    See what our customers say about their growth with {{ $agency->name }}.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                @foreach($testimonials as $t)
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-4 flex flex-col justify-between">
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
    <section id="faq" class="py-16 sm:py-20 bg-white border-t border-slate-200/60">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 sm:space-y-12">
            <div class="text-center space-y-3">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading">Frequently Asked Questions</h2>
                <p class="text-xs sm:text-sm text-slate-600">Have questions? We are here to help.</p>
            </div>

            <div class="space-y-4">
                @foreach($faqs as $item)
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 sm:p-6 space-y-2">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 font-heading">{{ $item['q'] }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $item['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA BANNER SECTION (Dynamic CTA Image & Fully Mobile Responsive) -->
    <section class="py-12 sm:py-16 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-gradient rounded-3xl p-6 sm:p-12 lg:p-14 shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-8 sm:gap-10">
                
                <!-- Left Banner Text -->
                <div class="space-y-4 text-center lg:text-left max-w-2xl">
                    <h2 class="text-2xl sm:text-4xl font-extrabold font-heading leading-tight">
                        Ready to Take Your Business to the Next Level?
                    </h2>
                    <p class="text-xs sm:text-sm text-white/90">
                        Join thousands of growing businesses with {{ $agency->name }} today.
                    </p>
                    <div class="flex flex-wrap justify-center lg:justify-start gap-3 sm:gap-4 text-xs font-bold pt-1">
                        <span>✓ Quick Setup</span>
                        <span>✓ No Credit Card Required</span>
                        <span>✓ 24/7 Support</span>
                    </div>
                    <div class="pt-3">
                        <a href="{{ $agency->cta_url ?? '/login' }}" class="inline-block bg-white text-slate-900 hover:bg-slate-100 text-xs sm:text-sm font-extrabold px-8 py-3.5 sm:py-4 rounded-2xl shadow-xl transition-all">
                            Get Started Free →
                        </a>
                    </div>
                </div>

                <!-- Right Banner Graphic Image (Dynamic CTA Image) -->
                <div class="w-full lg:w-auto shrink-0 flex justify-center">
                    <img src="{{ $ctaImg }}" alt="Ready to Grow" class="max-w-xs sm:max-w-sm lg:max-w-md w-full h-auto object-contain drop-shadow-xl hover:scale-[1.02] transition-transform duration-300">
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER SECTION -->
    <footer class="bg-slate-950 text-slate-400 pt-12 sm:pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                
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

                <!-- Col 3: Legal & Policies -->
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Legal & Policies</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/privacy-policy" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="/terms-conditions" class="hover:text-white transition">Terms & Conditions</a></li>
                        <li><a href="/shipping-policy" class="hover:text-white transition">Shipping Policy</a></li>
                        <li><a href="/refund-policy" class="hover:text-white transition">Cancellation & Refund Policy</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact Info -->
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Contact</h4>
                    <p class="text-xs text-slate-400">Email: {{ $agency->contact_email ?? $agency->email }}</p>
                    @if(!empty($agency->contact_phone ?? $agency->phone))
                        <p class="text-xs text-slate-400">Phone: {{ $agency->contact_phone ?? $agency->phone }}</p>
                    @endif
                </div>

            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <div>© 2026 {{ $agency->name }}. All rights reserved.</div>
                <div>Made with ❤️ in India 🇮🇳</div>
            </div>

        </div>
    </footer>

    <script>
        lucide.createIcons();

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
