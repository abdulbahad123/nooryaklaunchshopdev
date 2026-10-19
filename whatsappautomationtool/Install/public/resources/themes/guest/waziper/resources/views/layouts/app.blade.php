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
    <link rel="stylesheet" type="text/css" href="{{ theme_public_asset('css/fontawesome/css/all.min.css') }}">
    {!! theme_vite('guest/waziper') !!}
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
    <style id="whatsappbulk-guest-light-green-theme">
        :root {
            --wab-bg: #d5f0c6;
            --wab-primary-gradient: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            --wab-primary-hover: linear-gradient(135deg, #16a34a 0%, #14532d 100%);
        }
        html, body {
            background-color: #d5f0c6 !important;
            color: #0f172a;
        }
        ::selection {
            background-color: #22c55e !important;
            color: #ffffff !important;
        }
        .text-orange {
            color: #15803d !important;
        }
        .bg-orange {
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%) !important;
            color: #ffffff !important;
        }
        .border-orange {
            border-color: #22c55e !important;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar-thumb {
            background: #22c55e;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #15803d;
        }
    </style>
</head>
<body class="antialiased bg-[#d5f0c6] text-gray-900 sm:overflow-x-hidden selection:bg-[#22c55e] selection:text-white" style="background-color: #d5f0c6 !important;">
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