<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $titles = [
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms & Conditions',
            'shipping' => 'Shipping & Delivery Policy',
            'refund' => 'Cancellation & Refund Policy',
            'cookie' => 'Cookie Policy',
            'about' => 'About Us',
            'contact' => 'Contact Us',
        ];
        $pageTitle = $titles[$type] ?? 'Policy Document';
        $primaryColor = $agency->primary_color ?? '#4f46e5';
        $secondaryColor = $agency->secondary_color ?? '#9333ea';
        $accentColor = $agency->accent_color ?? '#3b82f6';
        $aboutImg = !empty($agency->about_image) ? asset($agency->about_image) : asset('assets/landing_page/features_leftside.png');
    @endphp
    <title>{{ $pageTitle }} — {{ $agency->name }}</title>
    <meta name="description" content="{{ $pageTitle }} for {{ $agency->name }}. Powering the growth of local businesses.">

    @if(!empty($agency->favicon))
        <link rel="icon" type="image/png" href="{{ asset($agency->favicon) }}">
    @endif

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: {{ $primaryColor }};
            --brand-secondary: {{ $secondaryColor }};
            --brand-accent: {{ $accentColor }};
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
        .bg-brand-gradient {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
        }
        .text-brand-gradient {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased min-h-screen flex flex-col justify-between overflow-x-hidden">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-purple-900 text-white text-[11px] font-semibold py-1.5 px-4 text-center">
        <span>🎉 Special Offer: Get Started with <strong>{{ $agency->name }}</strong> Today & Automate Your Business!</span>
    </div>

    <!-- Header Navigation (Matching Landing Page Header) -->
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
                <a href="/#products" class="hover:text-indigo-600 transition">Products</a>
                <a href="/#features" class="hover:text-indigo-600 transition">Solutions</a>
                <a href="/about" class="hover:text-indigo-600 transition {{ $type === 'about' ? 'text-indigo-600 font-black' : '' }}">About Us</a>
                <a href="/#how-it-works" class="hover:text-indigo-600 transition">How It Works</a>
                <a href="/#testimonials" class="hover:text-indigo-600 transition">Reviews</a>
                <a href="/#faq" class="hover:text-indigo-600 transition">FAQ</a>
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
                <a href="/#products" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">Products</a>
                <a href="/#features" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">Solutions</a>
                <a href="/about" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">About Us</a>
                <a href="/#how-it-works" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">How It Works</a>
                <a href="/#testimonials" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">Reviews</a>
                <a href="/#faq" onclick="toggleMobileMenu()" class="hover:text-indigo-600 py-1.5">FAQ</a>
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

    <!-- Main Content Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 flex-1 w-full">
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-12 shadow-sm space-y-8">
            
            <!-- Page Title Header -->
            <div class="border-b border-slate-100 pb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="px-3.5 py-1 bg-indigo-50 text-indigo-700 font-bold text-xs rounded-full inline-block mb-2">
                        {{ $agency->name }} Information
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">{{ $pageTitle }}</h1>
                    <p class="text-xs text-slate-500 mt-1">Last updated: {{ date('F d, Y') }} — {{ $agency->name }}</p>
                </div>
                <a href="/" class="self-start sm:self-auto inline-flex items-center space-x-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Back to Home</span>
                </a>
            </div>

            <!-- Page Specific Body Content -->
            <div class="prose prose-slate max-w-none text-xs sm:text-sm leading-relaxed text-slate-700 space-y-6">
                
                @if($type === 'about')
                    <!-- ABOUT US PAGE CONTENT -->
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <div class="lg:col-span-7 space-y-4">
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading">
                                    Built for entrepreneurs, by entrepreneurs.
                                </h2>
                                <p class="text-slate-600 leading-relaxed">
                                    {{ $agency->about_content ?? ("At " . $agency->name . ", we understand the challenges of growing a business in India. That's why we built an all-in-one platform to make digital technology simple, affordable, and accessible for local business owners.") }}
                                </p>
                                <p class="text-slate-600 leading-relaxed">
                                    Our mission is to help Indian entrepreneurs automate repetitive operations, boost sales revenue, build customer loyalty, and scale seamlessly without juggling multiple expensive tools.
                                </p>
                            </div>
                            <div class="lg:col-span-5">
                                <img src="{{ $aboutImg }}" alt="About {{ $agency->name }}" class="w-full h-auto rounded-2xl border border-slate-200 shadow-md object-cover">
                            </div>
                        </div>

                        <!-- 4 Stats Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-100">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-heading text-brand-gradient">10,000+</h4>
                                <p class="text-[11px] font-bold text-slate-500">Happy Businesses</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-heading text-brand-gradient">1M+</h4>
                                <p class="text-[11px] font-bold text-slate-500">Orders Processed</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-heading text-brand-gradient">500K+</h4>
                                <p class="text-[11px] font-bold text-slate-500">Active Customers</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-heading text-brand-gradient">99.8%</h4>
                                <p class="text-[11px] font-bold text-slate-500">Uptime & Security</p>
                            </div>
                        </div>
                    </div>

                @elseif($type === 'contact')
                    <!-- CONTACT US PAGE CONTENT -->
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Card 1: Email -->
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-heading">Email Support</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $agency->contact_email ?? $agency->email }}</p>
                            </div>

                            <!-- Card 2: Phone -->
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-heading">Phone Number</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $agency->contact_phone ?? $agency->phone ?? '+91 98765 43210' }}</p>
                            </div>

                            <!-- Card 3: Address -->
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-heading">Office Address</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $agency->contact_address ?? 'India' }}</p>
                            </div>

                        </div>

                        <!-- Interactive Contact Form -->
                        <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6">
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading">Send Us a Message</h3>
                            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you! Your message has been sent to {{ $agency->name }} support team.');" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name</label>
                                        <input type="text" required placeholder="e.g. Rahul Sharma" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                                        <input type="email" required placeholder="name@company.com" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-indigo-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject</label>
                                    <input type="text" required placeholder="How can we help your business?" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Message</label>
                                    <textarea rows="4" required placeholder="Write your query details here..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-indigo-500"></textarea>
                                </div>
                                <button type="submit" class="bg-brand-gradient text-white text-xs font-extrabold px-8 py-3.5 rounded-2xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition">
                                    Submit Message →
                                </button>
                            </form>
                        </div>
                    </div>

                @elseif($type === 'privacy')
                    {!! $agency->privacy_policy ?? "<h2>Privacy Policy for {$agency->name}</h2><p>At {$agency->name}, accessible from " . request()->getHost() . ", we prioritize your privacy and protect data collected during service usage.</p><h3>1. Information We Collect</h3><p>We collect essential business information, account registration details, and contact details to process orders and improve customer service experience.</p><h3>2. Data Security</h3><p>Your business data is safe with enterprise-grade security encryption standards.</p><h3>3. Contact Information</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'terms')
                    {!! $agency->terms_conditions ?? "<h2>Terms & Conditions for {$agency->name}</h2><p>Welcome to {$agency->name}! These terms regulate the usage of our platform and SaaS products.</p><h3>1. User Account Responsibility</h3><p>By registering or making a purchase on {$agency->name}, you agree to maintain valid account details and comply with usage policies.</p><h3>2. Contact Support</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'shipping')
                    {!! $agency->shipping_policy ?? "<h2>Shipping & Delivery Policy for {$agency->name}</h2><p>All SaaS products, digital tools, and subscriptions purchased from {$agency->name} are fulfilled electronically via instant email confirmation and portal access credentials within 15 minutes of successful payment.</p><h3>Digital Delivery Guarantee</h3><p>No physical shipping is required. You can log into your account dashboard immediately after purchase.</p><h3>Contact Support</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'refund')
                    {!! $agency->refund_policy ?? "<h2>Cancellation & Refund Policy for {$agency->name}</h2><p>We offer a 7-day money-back guarantee for subscription packages. Once approved, refunds are processed back to your original payment method within 5 to 7 business days.</p><h3>How to Request Refund</h3><p>Please contact our support team at " . ($agency->contact_email ?? $agency->email) . " with your account details.</p>" !!}
                @elseif($type === 'cookie')
                    {!! $agency->cookie_policy ?? "<h2>Cookie Policy for {$agency->name}</h2><p>This site uses cookies to personalize user sessions and optimize website navigation experience.</p><h3>Contact Support</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @endif

            </div>

        </div>
    </main>

    <!-- Footer (Matching Landing Page Dark Footer) -->
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
                        <li><a href="/#products" class="hover:text-white transition">AI Reviews + CRM</a></li>
                        <li><a href="/#products" class="hover:text-white transition">Website Builder</a></li>
                        <li><a href="/#products" class="hover:text-white transition">Digital V-Card</a></li>
                    </ul>
                </div>

                <!-- Col 3: Legal & Policies -->
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-white">Legal & Policies</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/about" class="hover:text-white transition">About Us</a></li>
                        <li><a href="/contact" class="hover:text-white transition">Contact Us</a></li>
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
