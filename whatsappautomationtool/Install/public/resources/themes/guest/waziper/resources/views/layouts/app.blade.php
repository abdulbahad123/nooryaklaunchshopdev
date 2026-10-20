<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ Language::getCurrent('dir') }}">
<head>
    <title>
        @hasSection('pagetitle')
            @yield('pagetitle') - Whatsappbulk
        @else
            {{ get_option("website_title") ?: "Whatsappbulk - WhatsApp operations, campaigns, and automation" }}
        @endif
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="{{ get_option("website_keyword", "whatsapp marketing, bulk campaigns, whatsapp chatbot, whatsapp automation, auto reply") }}">
    <meta name="description" content="{{ get_option("website_description", "One WhatsApp workspace for campaigns, replies, bots, contacts, and reports. High-speed bulk sender and marketing automation.") }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ theme_public_asset('images/whatsappbulklogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ theme_public_asset('css/flags/flag-icon.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN & Custom Theme Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#ff5500',
                        orange: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            500: '#ff5500',
                            600: '#ea580c',
                            700: '#c2410c',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Theme fallback stylesheet -->
    <link rel="stylesheet" type="text/css" href="{{ theme_public_asset('css/app.css') }}">
    @if(file_exists(public_path('resources/themes/guest/waziper/public/css/tailwind/tailwind.min.css')))
        <link rel="stylesheet" type="text/css" href="{{ theme_public_asset('css/tailwind/tailwind.min.css') }}">
    @endif

    {!! Script::globals() !!}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #111827;
            -webkit-font-smoothing: antialiased;
        }
        ::selection {
            background-color: #ff5500;
            color: #ffffff;
        }
        .text-orange {
            color: #ff5500 !important;
        }
        .bg-orange {
            background-color: #ff5500 !important;
        }
        .border-orange {
            border-color: #ff5500 !important;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #ffaa80;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ff5500;
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 sm:overflow-x-hidden selection:bg-[#ff5500] selection:text-white">
    @if( request()->segment(1) != "auth")
        @include('partials.header')
    @endif

    @yield('content')

    @if( request()->segment(1) != "auth")
        @include('partials.footer')
    @endif

    <!-- Cookie Notice (Minimal) -->
    <div class="fixed bottom-4 right-4 z-50 max-w-sm cookie-policy-bar hidden">
        <div class="p-4 bg-white/95 backdrop-blur-md border border-gray-200 rounded-2xl shadow-xl flex items-center justify-between gap-4 text-xs">
            <span class="text-gray-600">We use cookies to deliver the best WhatsApp bulk messaging experience.</span>
            <button class="px-3 py-1.5 rounded-full bg-[#ff5500] text-white font-bold btn-accept shrink-0">Got it</button>
        </div>
    </div>
</body>
</html>