<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $titles = [
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms & Conditions',
            'cookie' => 'Cookie Policy',
        ];
        $pageTitle = $titles[$type] ?? 'Legal Notice';
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
                    {!! $agency->privacy_policy ?? "<h2>Privacy Policy for {$agency->name}</h2><p>At {$agency->name}, accessible from " . request()->getHost() . ", one of our main priorities is the privacy of our visitors. We respect your data privacy and protect information collected during service usage.</p><h3>Contact Us</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'terms')
                    {!! $agency->terms_conditions ?? "<h2>Terms & Conditions for {$agency->name}</h2><p>Welcome to {$agency->name}! These terms and conditions outline the rules and regulations for the use of our services.</p><h3>Contact Us</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
                @elseif($type === 'cookie')
                    {!! $agency->cookie_policy ?? "<h2>Cookie Policy for {$agency->name}</h2><p>This site uses cookies to personalize user session access and improve functionality.</p><h3>Contact Us</h3><p>Email: " . ($agency->contact_email ?? $agency->email) . "</p>" !!}
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
