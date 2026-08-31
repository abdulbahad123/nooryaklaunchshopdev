<!DOCTYPE html>
<html lang="en">
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
    @endphp
    <title>{{ $pageTitle }} — {{ $agency->name }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 py-4 px-6 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center space-x-3">
                @if(!empty($agency->logo))
                    <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" class="h-8 w-auto">
                @else
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                    </div>
                    <span class="font-bold text-lg text-slate-900 font-heading">{{ $agency->name }}</span>
                @endif
            </a>
            <a href="/" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center space-x-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Back to Home</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-12 flex-1 w-full">
        <div class="bg-white border border-slate-200/80 rounded-3xl p-8 sm:p-12 shadow-sm space-y-6">
            
            <div class="border-b border-slate-100 pb-6">
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading">{{ $pageTitle }}</h1>
                <p class="text-xs text-slate-500 mt-1">Last updated: {{ date('F d, Y') }} — {{ $agency->name }}</p>
            </div>

            <div class="prose prose-slate max-w-none text-xs sm:text-sm leading-relaxed space-y-4">
                @if($type === 'privacy')
                    {!! $agency->privacy_policy ?? "<h2>Privacy Policy for {$agency->name}</h2><p>At {$agency->name}, accessible from " . request()->getHost() . ", we prioritize your privacy and protect data collected during service usage.</p><h3>Information Collection</h3><p>We collect essential information to process orders and improve customer experience.</p><h3>Contact Us</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'terms')
                    {!! $agency->terms_conditions ?? "<h2>Terms & Conditions for {$agency->name}</h2><p>Welcome to {$agency->name}! These terms regulate the usage of our platform and SaaS products.</p><h3>Acceptance</h3><p>By registering or making a purchase, you agree to these terms.</p><h3>Contact Us</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'shipping')
                    {!! $agency->shipping_policy ?? "<h2>Shipping & Delivery Policy for {$agency->name}</h2><p>All SaaS products, digital tools, and subscriptions purchased from {$agency->name} are fulfilled electronically via instant email confirmation and portal access credentials within 15 minutes of successful payment.</p><h3>Contact Us</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'refund')
                    {!! $agency->refund_policy ?? "<h2>Cancellation & Refund Policy for {$agency->name}</h2><p>We offer a 7-day money-back guarantee for subscription packages. Once approved, refunds are processed back to your original payment method within 5 to 7 business days.</p><h3>Contact Support</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'cookie')
                    {!! $agency->cookie_policy ?? "<h2>Cookie Policy for {$agency->name}</h2><p>This site uses cookies to personalize user sessions and optimize website navigation.</p><h3>Contact Us</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'about')
                    <div>
                        <h2>About {{ $agency->name }}</h2>
                        <p>{{ $agency->about_content ?? "{$agency->name} is a leading digital software platform empowering local businesses to grow, automate sales, manage customer reviews, and scale seamlessly." }}</p>
                        @if(!empty($agency->about_image))
                            <img src="{{ asset($agency->about_image) }}" alt="About {{ $agency->name }}" class="rounded-2xl mt-4 max-h-80 object-cover">
                        @endif
                    </div>
                @elseif($type === 'contact')
                    <div class="space-y-4">
                        <h2>Contact Us</h2>
                        <p>Have questions or need assistance? Reach out to our dedicated support team.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                                <h4 class="font-bold text-slate-900 text-sm">Support Email</h4>
                                <p class="text-xs text-slate-600 mt-1">{{ $agency->contact_email ?? $agency->email }}</p>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                                <h4 class="font-bold text-slate-900 text-sm">Phone Number</h4>
                                <p class="text-xs text-slate-600 mt-1">{{ $agency->contact_phone ?? $agency->phone ?? '+91 98765 43210' }}</p>
                            </div>
                        </div>
                        @if(!empty($agency->contact_address))
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mt-2">
                                <h4 class="font-bold text-slate-900 text-sm">Office Address</h4>
                                <p class="text-xs text-slate-600 mt-1">{{ $agency->contact_address }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        © 2026 {{ $agency->name }}. All rights reserved.
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
