@extends('layouts.app')

@section('content')

@php
    // Dynamic Hero Title with automatic sanitization of legacy orange colors
    $heroTitle = get_option('landing_hero_title', "WhatsApp operations,<br><span class=\"text-[#059669]\">campaigns,</span> and<br>automation");
    $heroTitle = str_replace(
        ['#ff5500', '#f97316', 'text-orange-500', 'color: #ff5500', 'color:#ff5500', 'color: #f97316', 'color:#f97316'],
        ['#059669', '#059669', 'text-[#059669]', 'color: #059669', 'color:#059669', 'color: #059669', 'color:#059669'],
        $heroTitle
    );

    // Dynamic Live Stack Title with automatic sanitization of legacy orange colors
    $stackTitle = get_option('landing_stack_title', 'Whatsappbulk <span class="text-[#059669]">Workspace</span>');
    $stackTitle = str_replace(
        ['#ff5500', '#f97316', 'text-orange-500', 'color: #ff5500', 'color:#ff5500', 'color: #f97316', 'color:#f97316'],
        ['#059669', '#059669', 'text-[#059669]', 'color: #059669', 'color:#059669', 'color: #059669', 'color:#059669'],
        $stackTitle
    );

    // Dynamic Live Stack Subtitle
    $stackSubtitle = get_option('landing_stack_subtitle', 'Everything you need to run WhatsApp at scale');
    if ($stackSubtitle === 'Product overview' || empty($stackSubtitle)) {
        $stackSubtitle = 'Everything you need to run WhatsApp at scale';
    }
@endphp

<!-- ============================================================== -->
<!-- SECTION 1: HERO & DASHBOARD MOCKUP (Image 2 Match)             -->
<!-- ============================================================== -->
<section class="relative pt-12 pb-16 md:pt-16 md:pb-24 overflow-hidden bg-gradient-to-b from-[#e8fbf3] via-[#f2fdf7] to-[#eafbf3]">
    <!-- Ambient Green Background Glows -->
    <div class="absolute -top-20 -right-20 w-[600px] h-[600px] bg-gradient-to-bl from-emerald-200/50 via-teal-100/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-28 left-8 w-80 h-80 bg-emerald-200/35 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            
            <!-- Left Hero Text Content -->
            <div class="lg:col-span-6">
                <!-- Pill Badge -->
                <div class="mb-5">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100/90 text-[#065f46] border border-emerald-300/70 shadow-xs">
                        <i class="fas fa-bolt text-[11px] text-[#059669]"></i>
                        <span>{!! get_option('landing_hero_badge', 'ALL-IN-ONE WHATSAPP MARKETING TOOL') !!}</span>
                    </span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-[54px] font-black text-slate-900 tracking-tight leading-[1.12] mb-5">
                    {!! $heroTitle !!}
                </h1>

                <!-- Subtitles -->
                <p class="text-base sm:text-lg font-bold text-slate-800 mb-2 leading-snug">
                    {{ get_option('landing_hero_subtitle_bold', 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.') }}
                </p>
                <p class="text-sm text-slate-500 font-normal leading-relaxed mb-8 max-w-lg">
                    {{ get_option('landing_hero_subtitle_desc', 'Built around the modules teams actually use every day: bulk outreach, smart replies, auto replies, chatbots, contacts, participant export, profile lookups, and reporting.') }}
                </p>

                <!-- Dual CTA Buttons -->
                <div class="flex flex-wrap items-center gap-4 mb-10">
                    <a href="{{ url(get_option('landing_hero_btn1_url', 'auth/signup')) }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full text-white font-bold text-sm bg-[#059669] hover:bg-[#047857] shadow-lg shadow-emerald-600/30 transition transform hover:-translate-y-0.5">
                        <span>{{ get_option('landing_hero_btn1_text', 'Create your workspace') }}</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    <a href="{{ get_option('landing_hero_btn2_url', '#modules') }}" class="inline-flex items-center px-7 py-3.5 rounded-full text-slate-800 font-bold text-sm bg-white border border-slate-200 hover:bg-slate-50 transition shadow-xs">
                        {{ get_option('landing_hero_btn2_text', 'Explore modules') }}
                    </a>
                </div>

                <!-- 3 Metric Highlight Cards (Pixel-by-pixel match with Image 2) -->
                <div class="grid grid-cols-3 gap-3 max-w-xl">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-2xl p-3 sm:p-3.5 border border-emerald-100 shadow-sm flex items-center gap-2.5 sm:gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#059669] flex items-center justify-center shrink-0 text-base">
                            <i class="fas fa-box-archive"></i>
                        </div>
                        <div>
                            <span class="block text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ get_option('landing_stat1_number', '8') }}</span>
                            <span class="text-[10px] sm:text-[11px] font-semibold text-slate-500 leading-tight block mt-0.5">{{ get_option('landing_stat1_label', 'Operational modules included') }}</span>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-white rounded-2xl p-3 sm:p-3.5 border border-emerald-100 shadow-sm flex items-center gap-2.5 sm:gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#059669] flex items-center justify-center shrink-0 text-base">
                            <i class="fas fa-link"></i>
                        </div>
                        <div>
                            <span class="block text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ get_option('landing_stat2_number', '1') }}</span>
                            <span class="text-[10px] sm:text-[11px] font-semibold text-slate-500 leading-tight block mt-0.5">{{ get_option('landing_stat2_label', 'Unified WhatsApp workflow') }}</span>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-white rounded-2xl p-3 sm:p-3.5 border border-emerald-100 shadow-sm flex items-center gap-2.5 sm:gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#059669] flex items-center justify-center shrink-0 text-base">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <span class="block text-xl sm:text-2xl font-black text-slate-900 leading-none">{{ get_option('landing_stat3_number', '24/7') }}</span>
                            <span class="text-[10px] sm:text-[11px] font-semibold text-slate-500 leading-tight block mt-0.5">{{ get_option('landing_stat3_label', 'Automation & reply coverage') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Hero Visual: Realistic Tablet + Smartphone + Hand-drawn Doodles -->
            <div class="lg:col-span-6 relative pt-4 pb-8 sm:pb-12">
                
                <!-- Hand-drawn SVG Doodles (Pixel-by-pixel match of Image 2) -->
                <!-- 1. Crown Squiggle above Tablet -->
                <svg class="absolute -top-3 left-1/3 w-12 h-8 text-[#059669] pointer-events-none z-20" viewBox="0 0 48 30" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 22 L14 8 L24 20 L34 8 L44 22" />
                </svg>

                <!-- 2. Sparkle Star 1 (Top Left) -->
                <svg class="absolute -top-1 left-16 w-5 h-5 text-[#059669] pointer-events-none z-20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0L14 9L23 12L14 15L12 24L10 15L1 12L10 9Z" />
                </svg>

                <!-- 3. Sparkle Star 2 (Right of Phone) -->
                <svg class="absolute top-1/2 -right-4 w-6 h-6 text-[#059669] pointer-events-none z-20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0L14 9L23 12L14 15L12 24L10 15L1 12L10 9Z" />
                </svg>
                <svg class="absolute top-[60%] -right-1 w-3.5 h-3.5 text-[#059669]/70 pointer-events-none z-20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0L14 9L23 12L14 15L12 24L10 15L1 12L10 9Z" />
                </svg>

                <!-- 4. Curly Loop Doodle (Bottom Left of Phone) -->
                <svg class="absolute bottom-2 right-48 sm:right-56 w-12 h-10 text-[#059669] pointer-events-none z-20 hidden sm:block" viewBox="0 0 50 40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <path d="M5 25 Q15 5 25 18 T38 20 T46 32" />
                </svg>

                <!-- Outer Realistic Tablet Frame with Dark Slate Bezel (Image 2) -->
                <div class="border-[7px] border-slate-900 rounded-[34px] bg-slate-900 shadow-[0_30px_70px_rgba(15,23,42,0.22)] overflow-hidden">
                    <!-- Tablet Screen Content -->
                    <div class="bg-white rounded-[26px] overflow-hidden">
                        
                        <!-- Screen Top Header Bar -->
                        <div class="bg-white border-b border-slate-100 px-5 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <span class="text-sm font-extrabold text-[#059669]">Whatsappbulk</span>
                                <span class="text-slate-300 font-light">|</span>
                                <span class="text-xs font-bold text-slate-800">Dashboard</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-orange-500 text-white flex items-center justify-center text-[11px] font-bold">A</div>
                                <span class="text-[11px] font-bold text-slate-700">HI, Admin</span>
                                <i class="fas fa-chevron-down text-[9px] text-slate-400"></i>
                            </div>
                        </div>

                        <!-- Inner Two-Column Dashboard Layout -->
                        <div class="grid grid-cols-12 gap-3.5 p-4 bg-white">
                            <!-- Left Mini App Sidebar -->
                            <div class="col-span-4 border-r border-slate-100 pr-2.5 space-y-1 text-[11px] font-semibold text-slate-600">
                                <div class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-[#059669] font-bold flex items-center gap-2">
                                    <i class="fas fa-chart-pie text-xs"></i> Dashboard
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-500">
                                    <i class="fas fa-bullhorn text-xs text-slate-400"></i> Bulk Campaigns
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-500">
                                    <i class="fas fa-wand-magic-sparkles text-xs text-slate-400"></i> AI Smart Reply
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-500">
                                    <i class="fas fa-rotate-left text-xs text-slate-400"></i> Auto Reply
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-500">
                                    <i class="fas fa-robot text-xs text-slate-400"></i> Chatbot
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-500">
                                    <i class="fas fa-address-book text-xs text-slate-400"></i> Contacts
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-500">
                                    <i class="fas fa-file-export text-xs text-slate-400"></i> Export Participants
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-500">
                                    <i class="fas fa-id-card text-xs text-slate-400"></i> Profile Info
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-500">
                                    <i class="fas fa-chart-column text-xs text-slate-400"></i> Reports
                                </div>
                            </div>

                            <!-- Right Live Metrics & Graph View -->
                            <div class="col-span-8 pl-1">
                                <!-- 4 Stats Cards in 2x2 Grid -->
                                <div class="grid grid-cols-2 gap-2 mb-3">
                                    <div class="bg-slate-50/90 rounded-xl p-2.5 border border-slate-100">
                                        <span class="text-[9px] text-slate-400 font-bold block">Total Messages</span>
                                        <span class="text-sm sm:text-base font-black text-slate-900 block">125,890</span>
                                        <span class="text-[9px] font-bold text-emerald-600 block">▲ 20.4%</span>
                                    </div>
                                    <div class="bg-slate-50/90 rounded-xl p-2.5 border border-slate-100">
                                        <span class="text-[9px] text-slate-400 font-bold block">Delivered</span>
                                        <span class="text-sm sm:text-base font-black text-slate-900 block">118,420</span>
                                        <span class="text-[9px] font-bold text-emerald-600 block">▲ 16.1%</span>
                                    </div>
                                    <div class="bg-slate-50/90 rounded-xl p-2.5 border border-slate-100">
                                        <span class="text-[9px] text-slate-400 font-bold block">Replied</span>
                                        <span class="text-sm sm:text-base font-black text-slate-900 block">28,460</span>
                                        <span class="text-[9px] font-bold text-emerald-600 block">▲ 14.2%</span>
                                    </div>
                                    <div class="bg-slate-50/90 rounded-xl p-2.5 border border-slate-100">
                                        <span class="text-[9px] text-slate-400 font-bold block">Active Contacts</span>
                                        <span class="text-sm sm:text-base font-black text-slate-900 block">96,350</span>
                                        <span class="text-[9px] font-bold text-emerald-600 block">▲ 12.6%</span>
                                    </div>
                                </div>

                                <!-- Message Performance Area Chart -->
                                <div class="bg-slate-50/90 rounded-xl p-2.5 border border-slate-100 mb-3">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[11px] font-bold text-slate-700">Message Performance</span>
                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-orange-100 text-orange-600">Last 30 Days</span>
                                    </div>
                                    <svg class="w-full h-12" viewBox="0 0 300 65" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="heroAreaGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                                <stop offset="0%" stop-color="#f97316" stop-opacity="0.35"/>
                                                <stop offset="100%" stop-color="#f97316" stop-opacity="0.0"/>
                                            </linearGradient>
                                        </defs>
                                        <path d="M0,50 Q40,40 80,25 T160,30 T240,15 T300,28 L300,65 L0,65 Z" fill="url(#heroAreaGrad)"/>
                                        <path d="M0,50 Q40,40 80,25 T160,30 T240,15 T300,28" fill="none" stroke="#f97316" stroke-width="2.5" stroke-linecap="round"/>
                                    </svg>
                                </div>

                                <!-- Recent Campaigns Table -->
                                <div class="space-y-1.5 text-[10px]">
                                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                                        <span class="font-bold text-slate-800"><i class="fas fa-star text-amber-500 mr-1 text-[9px]"></i> Product Launch</span>
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[9px]">Completed</span>
                                        <span class="font-bold text-slate-600">12,450</span>
                                    </div>
                                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                                        <span class="font-bold text-slate-800"><i class="fas fa-bullhorn text-orange-500 mr-1 text-[9px]"></i> Festival Offer</span>
                                        <span class="px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 font-bold text-[9px]">Sending</span>
                                        <span class="font-bold text-slate-600">25,600</span>
                                    </div>
                                    <div class="flex items-center justify-between py-1">
                                        <span class="font-bold text-slate-800"><i class="fas fa-headset text-blue-500 mr-1 text-[9px]"></i> Customer Support</span>
                                        <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold text-[9px]">Active</span>
                                        <span class="font-bold text-slate-600">18,220</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overlapping Vertical Smartphone Mockup (Pixel-by-pixel match with Image 2) -->
                <div class="absolute -bottom-6 -right-3 w-48 sm:w-52 border-[6px] border-slate-900 rounded-[38px] bg-white shadow-2xl p-3.5 hidden sm:block z-30">
                    <!-- Top Speaker Bar Notch -->
                    <div class="w-12 h-1 bg-slate-900 rounded-full mx-auto mb-3"></div>

                    <!-- Inner Phone Screen -->
                    <div class="text-center pt-1 pb-2">
                        <!-- Green WhatsApp Icon -->
                        <div class="w-11 h-11 rounded-full bg-emerald-500 text-white mx-auto flex items-center justify-center text-2xl mb-1.5 shadow-md shadow-emerald-500/30">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-500 block">Campaign Overview</span>
                        <span class="text-3xl font-black text-orange-500 block tracking-tight my-0.5">95.8%</span>
                    </div>

                    <!-- Multi-bar Chart with Green and Orange Bars -->
                    <div class="flex items-end justify-center gap-1.5 h-12 pt-2 border-t border-slate-100">
                        <div class="w-3 bg-orange-400 rounded-t h-5"></div>
                        <div class="w-3 bg-orange-500 rounded-t h-9"></div>
                        <div class="w-3 bg-orange-400 rounded-t h-6"></div>
                        <div class="w-3 bg-orange-600 rounded-t h-11"></div>
                        <div class="w-3 bg-orange-500 rounded-t h-8"></div>
                        <div class="w-3 bg-emerald-500 rounded-t h-12"></div>
                    </div>

                    <!-- Floating Green WhatsApp Circle Badge with Telephone Icon -->
                    <div class="absolute -bottom-3 -right-3 w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-lg text-lg border-2 border-white">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 2: LIVE MODULE STACK (Image 2 Bottom Match)             -->
<!-- ============================================================== -->
<section class="py-20 bg-[#f0fbf5]">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-14">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-[#065f46] border border-emerald-300/60 mb-3">
                <i class="fas fa-layer-group text-[11px] text-[#059669]"></i>
                <span>{!! get_option('landing_stack_badge', 'LIVE MODULE STACK') !!}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-3">
                {!! $stackTitle !!}
            </h2>
            <p class="text-sm md:text-base text-slate-600 font-medium">
                {{ $stackSubtitle }}
            </p>
        </div>

        <!-- 4 Rounded White Cards Grid on Soft Mint Background -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Stack Card 1: Bulk Campaigns -->
            <div class="bg-white rounded-[26px] p-7 border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition duration-300 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl mb-6 shadow-sm">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1.5">
                        {{ get_option('landing_stack1_tag', 'CAMPAIGN CONTROL') }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 mb-2">
                        {{ get_option('landing_stack1_title', 'Bulk Campaigns') }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        {{ get_option('landing_stack1_desc', 'Segment contacts, send at scale, and track response flow.') }}
                    </p>
                </div>
                <!-- Green Circle Arrow Button Aligned to Bottom Left (Image 2) -->
                <div class="mt-8 flex justify-start">
                    <span class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center text-xs group-hover:scale-110 transition shadow-sm">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>

            <!-- Stack Card 2: AI + Auto Reply -->
            <div class="bg-white rounded-[26px] p-7 border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition duration-300 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl mb-6 shadow-sm">
                        <i class="fas fa-comments"></i>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1.5">
                        {{ get_option('landing_stack2_tag', 'INBOUND HANDLING') }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 mb-2">
                        {{ get_option('landing_stack2_title', 'AI + Auto Reply') }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        {{ get_option('landing_stack2_desc', 'Combine automation with faster human response quality.') }}
                    </p>
                </div>
                <!-- Green Circle Arrow Button Aligned to Bottom Left (Image 2) -->
                <div class="mt-8 flex justify-start">
                    <span class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center text-xs group-hover:scale-110 transition shadow-sm">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>

            <!-- Stack Card 3: Chatbot + Contacts -->
            <div class="bg-white rounded-[26px] p-7 border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition duration-300 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl mb-6 shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1.5">
                        {{ get_option('landing_stack3_tag', 'LEAD FLOW') }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 mb-2">
                        {{ get_option('landing_stack3_title', 'Chatbot + Contacts') }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        {{ get_option('landing_stack3_desc', 'Capture, qualify, and manage audience data in one place.') }}
                    </p>
                </div>
                <!-- Green Circle Arrow Button Aligned to Bottom Left (Image 2) -->
                <div class="mt-8 flex justify-start">
                    <span class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center text-xs group-hover:scale-110 transition shadow-sm">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>

            <!-- Stack Card 4: Reports + Export -->
            <div class="bg-white rounded-[26px] p-7 border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition duration-300 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl mb-6 shadow-sm">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1.5">
                        {{ get_option('landing_stack4_tag', 'VISIBILITY') }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 mb-2">
                        {{ get_option('landing_stack4_title', 'Reports + Export') }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        {{ get_option('landing_stack4_desc', 'Export participants and measure what the team actually ships.') }}
                    </p>
                </div>
                <!-- Green Circle Arrow Button Aligned to Bottom Left (Image 2) -->
                <div class="mt-8 flex justify-start">
                    <span class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center text-xs group-hover:scale-110 transition shadow-sm">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 3: PRODUCT MODULES (8 Cards, Screenshot 3)              -->
<!-- ============================================================== -->
<section id="modules" class="py-24 bg-white border-t border-slate-100">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-16">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-[#065f46] border border-emerald-300/60 mb-3">
                <i class="fas fa-cubes text-[11px] text-[#059669]"></i>
                <span>{!! get_option('landing_modules_badge', 'PRODUCT MODULES') !!}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-3">
                {!! get_option('landing_modules_title', 'Built from the <span class="text-[#059669]">WhatsApp features</span> teams actually need') !!}
            </h2>
            <p class="text-sm md:text-base text-slate-500 font-medium">
                {{ get_option('landing_modules_subtitle', 'Every module supports a real operational job, from outreach and automation to contact management and reporting.') }}
            </p>
        </div>

        @php
            $productModules = [
                ['key' => 1, 'title' => get_option('landing_mod1_title', 'AI Smart Reply'), 'desc' => get_option('landing_mod1_desc', 'Generate fast, context-aware WhatsApp replies so agents can handle conversations with less manual effort.'), 'icon' => 'fas fa-wand-magic-sparkles', 'bg' => 'bg-emerald-500'],
                ['key' => 2, 'title' => get_option('landing_mod2_title', 'Auto Reply'), 'desc' => get_option('landing_mod2_desc', 'Trigger automatic responses for inbound messages, off-hours support, lead capture, and common request handling.'), 'icon' => 'fas fa-rotate-left', 'bg' => 'bg-orange-500'],
                ['key' => 3, 'title' => get_option('landing_mod3_title', 'Bulk Campaigns'), 'desc' => get_option('landing_mod3_desc', 'Launch high-volume WhatsApp campaigns to segmented audiences while keeping timing and response flow under control.'), 'icon' => 'fas fa-bullhorn', 'bg' => 'bg-orange-500'],
                ['key' => 4, 'title' => get_option('landing_mod4_title', 'Chatbot'), 'desc' => get_option('landing_mod4_desc', 'Build conversational flows that qualify leads, answer questions, and move contacts into the next step automatically.'), 'icon' => 'fas fa-robot', 'bg' => 'bg-amber-500'],
                ['key' => 5, 'title' => get_option('landing_mod5_title', 'Contacts'), 'desc' => get_option('landing_mod5_desc', 'Manage contact lists, segments, and campaign targets from one structured database.'), 'icon' => 'fas fa-address-book', 'bg' => 'bg-orange-500'],
                ['key' => 6, 'title' => get_option('landing_mod6_title', 'Export Participants'), 'desc' => get_option('landing_mod6_desc', 'Extract participants from WhatsApp groups for outreach, qualification, migration, and audience building workflows.'), 'icon' => 'fas fa-users', 'bg' => 'bg-emerald-500'],
                ['key' => 7, 'title' => get_option('landing_mod7_title', 'Profile Info'), 'desc' => get_option('landing_mod7_desc', 'Retrieve WhatsApp profile information quickly to enrich lead context and improve handoff quality.'), 'icon' => 'fas fa-id-card', 'bg' => 'bg-orange-500'],
                ['key' => 8, 'title' => get_option('landing_mod8_title', 'Reports'), 'desc' => get_option('landing_mod8_desc', 'Track campaign output, reply activity, and workflow performance with clear reporting for real operational decisions.'), 'icon' => 'fas fa-chart-simple', 'bg' => 'bg-orange-500'],
            ];
        @endphp

        <!-- 8 Cards Grid (2 rows x 4 cols) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($productModules as $pm)
                <div class="bg-white rounded-[24px] p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl {{ $pm['bg'] }} text-white flex items-center justify-center text-xl mb-5 shadow-sm">
                            <i class="{{ $pm['icon'] }}"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 mb-2">{{ $pm['title'] }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed font-medium">{{ $pm['desc'] }}</p>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs group-hover:bg-[#059669] group-hover:text-white transition">
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 4: PRICING (4 Tier Cards, Screenshot 4 Top)             -->
<!-- ============================================================== -->
<section id="pricing" class="py-24 bg-[#f8fdfa] relative border-t border-slate-100">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header with Curved Hand-Drawn Arrows (Image 1 Match) -->
        <div class="max-w-3xl mx-auto text-center mb-16 relative">
            <!-- Left curved hand-drawn arrow -->
            <svg class="absolute top-1/2 left-2 sm:left-6 md:left-12 -translate-y-1/2 w-12 sm:w-16 h-8 text-[#059669] hidden sm:block pointer-events-none" viewBox="0 0 60 30" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M55,22 C38,6 20,6 8,22 M8,22 L15,14 M8,22 L18,24" />
            </svg>

            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-[#065f46] border border-emerald-300/60 mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-[#059669]"></span>
                <span>{!! get_option('landing_pricing_badge', 'PRICING') !!}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-3">
                {{ get_option('landing_pricing_title', 'Simple and transparent pricing') }}
            </h2>
            <p class="text-sm md:text-base text-slate-500 font-medium">
                {{ get_option('landing_pricing_subtitle', 'Choose the plan that fits your business needs. Upgrade or downgrade anytime.') }}
            </p>

            <!-- Right curved hand-drawn arrow -->
            <svg class="absolute top-1/2 right-2 sm:right-6 md:right-12 -translate-y-1/2 w-12 sm:w-16 h-8 text-[#059669] hidden sm:block pointer-events-none" viewBox="0 0 60 30" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M5,22 C22,6 40,6 52,22 M52,22 L45,14 M52,22 L42,24" />
            </svg>
        </div>

        <!-- 4 Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
            
            <!-- Plan 1: Starter -->
            <div class="bg-white rounded-[24px] p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between hover:shadow-xl transition">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#059669] flex items-center justify-center text-lg mb-4">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-1">{{ get_option('landing_price1_name', 'Starter') }}</h3>
                    <p class="text-xs text-slate-400 mb-5">{{ get_option('landing_price1_desc', 'For individuals and small businesses') }}</p>
                    <div class="mb-6">
                        <span class="text-4xl font-black text-slate-900">{{ get_option('landing_price1_price', '$19') }}</span>
                        <span class="text-xs font-semibold text-slate-400">/month</span>
                    </div>
                    <ul class="space-y-3 text-xs text-slate-600 font-medium mb-8">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> 1 WhatsApp number</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> 5,000 messages/month</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Basic automation</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Standard support</li>
                    </ul>
                </div>
                <a href="{{ url('auth/signup') }}" class="w-full text-center py-3 rounded-xl border border-slate-200 text-slate-800 font-bold text-sm hover:bg-slate-50 transition">
                    {{ __('Get Started') }}
                </a>
            </div>

            <!-- Plan 2: Professional (HIGHLIGHTED WITH MOST POPULAR) -->
            <div class="bg-white rounded-[24px] p-7 border-2 border-emerald-500 shadow-xl shadow-emerald-500/10 flex flex-col justify-between relative transform lg:-translate-y-2">
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#059669] text-white text-[11px] font-extrabold uppercase px-3.5 py-1 rounded-full shadow-sm">
                    {{ __('Most Popular') }}
                </div>
                <div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#059669] flex items-center justify-center text-lg mb-4">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-1">{{ get_option('landing_price2_name', 'Professional') }}</h3>
                    <p class="text-xs text-slate-400 mb-5">{{ get_option('landing_price2_desc', 'For growing teams') }}</p>
                    <div class="mb-6">
                        <span class="text-4xl font-black text-slate-900">{{ get_option('landing_price2_price', '$49') }}</span>
                        <span class="text-xs font-semibold text-slate-400">/month</span>
                    </div>
                    <ul class="space-y-3 text-xs text-slate-600 font-medium mb-8">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> 3 WhatsApp numbers</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> 25,000 messages/month</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> All modules included</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Advanced automation</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Priority support</li>
                    </ul>
                </div>
                <a href="{{ url('auth/signup') }}" class="w-full text-center py-3 rounded-xl bg-[#059669] text-white font-bold text-sm hover:bg-[#047857] shadow-md shadow-emerald-600/30 transition">
                    {{ __('Get Started') }}
                </a>
            </div>

            <!-- Plan 3: Business -->
            <div class="bg-white rounded-[24px] p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between hover:shadow-xl transition">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg mb-4">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-1">{{ get_option('landing_price3_name', 'Business') }}</h3>
                    <p class="text-xs text-slate-400 mb-5">{{ get_option('landing_price3_desc', 'For established businesses') }}</p>
                    <div class="mb-6">
                        <span class="text-4xl font-black text-slate-900">{{ get_option('landing_price3_price', '$99') }}</span>
                        <span class="text-xs font-semibold text-slate-400">/month</span>
                    </div>
                    <ul class="space-y-3 text-xs text-slate-600 font-medium mb-8">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> 10 WhatsApp numbers</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> 100,000 messages/month</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> All modules included</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Team collaboration</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Advanced reporting</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Priority support</li>
                    </ul>
                </div>
                <a href="{{ url('auth/signup') }}" class="w-full text-center py-3 rounded-xl border border-slate-200 text-slate-800 font-bold text-sm hover:bg-slate-50 transition">
                    {{ __('Get Started') }}
                </a>
            </div>

            <!-- Plan 4: Enterprise -->
            <div class="bg-white rounded-[24px] p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between hover:shadow-xl transition">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg mb-4">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-1">{{ get_option('landing_price4_name', 'Enterprise') }}</h3>
                    <p class="text-xs text-slate-400 mb-5">{{ get_option('landing_price4_desc', 'For large organizations') }}</p>
                    <div class="mb-6">
                        <span class="text-4xl font-black text-slate-900">{{ get_option('landing_price4_price', 'Custom') }}</span>
                    </div>
                    <ul class="space-y-3 text-xs text-slate-600 font-medium mb-8">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Unlimited numbers</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Custom message volume</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> All modules included</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Dedicated manager</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> Custom integrations</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs"></i> SLA & premium support</li>
                    </ul>
                </div>
                <a href="{{ url('contact') }}" class="w-full text-center py-3 rounded-xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition">
                    {{ __('Contact Sales') }}
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 5: TESTIMONIALS (3 Cards, Screenshot 4 Bottom)          -->
<!-- ============================================================== -->
<section class="py-24 bg-white border-t border-slate-100">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-16">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-[#065f46] border border-emerald-300/60 mb-3">
                <i class="fas fa-comment-dots text-[11px] text-[#059669]"></i>
                <span>{!! get_option('landing_testi_badge', 'TESTIMONIALS') !!}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-3">
                {{ get_option('landing_testi_title', 'What our customers say') }}
            </h2>
            <p class="text-sm md:text-base text-slate-500 font-medium">
                {{ get_option('landing_testi_subtitle', 'Trusted by businesses worldwide to streamline their WhatsApp operations.') }}
            </p>
        </div>

        <!-- 3 Review Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <!-- Review 1 -->
            <div class="bg-white rounded-[24px] p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-5xl font-serif text-slate-300 leading-none block mb-3 font-bold">“</span>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium mb-6">
                        "{{ get_option('landing_testi1_text', 'Whatsappbulk has completely transformed how we handle customer communication. Our response time improved by 70%!') }}"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <img class="w-11 h-11 rounded-full object-cover border-2 border-emerald-200" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=120&auto=format&fit=crop&q=80" alt="Priya Sharma">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 leading-tight">{{ get_option('landing_testi1_name', 'Priya Sharma') }}</h4>
                        <span class="text-xs text-slate-400 font-medium">{{ get_option('landing_testi1_role', 'Marketing Manager, RetailHub') }}</span>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="bg-white rounded-[24px] p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-5xl font-serif text-slate-300 leading-none block mb-3 font-bold">“</span>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium mb-6">
                        "{{ get_option('landing_testi2_text', 'The bulk campaign feature is a game-changer. We can now reach thousands of customers in minutes with excellent delivery rates.') }}"
                    </p>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <img class="w-11 h-11 rounded-full object-cover border-2 border-emerald-200" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=120&auto=format&fit=crop&q=80" alt="Rahul Mehta">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 leading-tight">{{ get_option('landing_testi2_name', 'Rahul Mehta') }}</h4>
                            <span class="text-xs text-slate-400 font-medium">{{ get_option('landing_testi2_role', 'Founder, GrowKart') }}</span>
                        </div>
                    </div>
                    <div class="text-amber-400 text-xs flex gap-0.5">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="bg-white rounded-[24px] p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-5xl font-serif text-slate-300 leading-none block mb-3 font-bold">“</span>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium mb-6">
                        "{{ get_option('landing_testi3_text', 'Easy to use, powerful features, and great support. Highly recommended for any business using WhatsApp.') }}"
                    </p>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <img class="w-11 h-11 rounded-full object-cover border-2 border-emerald-200" src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=120&auto=format&fit=crop&q=80" alt="Sneha Patel">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 leading-tight">{{ get_option('landing_testi3_name', 'Sneha Patel') }}</h4>
                            <span class="text-xs text-slate-400 font-medium">{{ get_option('landing_testi3_role', 'Operations Head, Foodies') }}</span>
                        </div>
                    </div>
                    <div class="text-amber-400 text-xs flex gap-0.5">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Slider Dots -->
        <div class="flex items-center justify-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
            <span class="w-6 h-2.5 rounded-full bg-[#059669]"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 6: FAQS (2-Column Accordion, Screenshot 5 Top)          -->
<!-- ============================================================== -->
<section id="faqs" class="py-24 bg-[#f8fdfa] border-t border-slate-100" x-data="{ activeFaq: 1 }">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-16">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-[#065f46] border border-emerald-300/60 mb-3">
                <i class="fas fa-circle-question text-[11px] text-[#059669]"></i>
                <span>{!! get_option('landing_faqs_badge', 'FAQS') !!}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-3">
                {{ get_option('landing_faqs_title', 'Frequently asked questions') }}
            </h2>
            <p class="text-sm md:text-base text-slate-500 font-medium">
                {{ get_option('landing_faqs_subtitle', 'Get answers to common questions about Whatsappbulk.') }}
            </p>
        </div>

        <!-- 2 Columns Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
            
            <!-- Left Column FAQs -->
            <div class="space-y-4">
                <!-- FAQ 1 (Open by default) -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm text-slate-900">
                        <span>{{ get_option('landing_faq1_q', 'What is Whatsappbulk?') }}</span>
                        <span class="text-slate-400 font-bold text-lg" x-text="activeFaq === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed font-medium border-t border-slate-100 pt-3">
                        {{ get_option('landing_faq1_a', 'Whatsappbulk is an all-in-one WhatsApp marketing tool that helps you manage campaigns, automate replies, handle contacts, and track performance — all in one workspace.') }}
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm text-slate-900">
                        <span>{{ get_option('landing_faq2_q', 'Is it safe to use?') }}</span>
                        <span class="text-slate-400 font-bold text-lg" x-text="activeFaq === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed font-medium border-t border-slate-100 pt-3">
                        {{ get_option('landing_faq2_a', 'Yes, our platform employs anti-ban mechanisms, customizable message delays, and adheres to standard messaging policies to protect your accounts.') }}
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm text-slate-900">
                        <span>{{ get_option('landing_faq3_q', 'Can I upgrade my plan later?') }}</span>
                        <span class="text-slate-400 font-bold text-lg" x-text="activeFaq === 3 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed font-medium border-t border-slate-100 pt-3">
                        {{ get_option('landing_faq3_a', 'Absolutely. You can upgrade, downgrade, or cancel your subscription at any time directly from your billing settings.') }}
                    </div>
                </div>
            </div>

            <!-- Right Column FAQs -->
            <div class="space-y-4">
                <!-- FAQ 4 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 4 ? null : 4)" class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm text-slate-900">
                        <span>{{ get_option('landing_faq4_q', 'Do you offer a free trial?') }}</span>
                        <span class="text-slate-400 font-bold text-lg" x-text="activeFaq === 4 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed font-medium border-t border-slate-100 pt-3">
                        {{ get_option('landing_faq4_a', 'Yes, we provide a free starter tier so you can test campaigns and automation features before committing.') }}
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 5 ? null : 5)" class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm text-slate-900">
                        <span>{{ get_option('landing_faq5_q', 'What payment methods do you accept?') }}</span>
                        <span class="text-slate-400 font-bold text-lg" x-text="activeFaq === 5 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 5" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed font-medium border-t border-slate-100 pt-3">
                        {{ get_option('landing_faq5_a', 'We accept major credit and debit cards, UPI, PayPal, Stripe, and multiple international payment gateways.') }}
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 6 ? null : 6)" class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm text-slate-900">
                        <span>{{ get_option('landing_faq6_q', 'Can I use multiple WhatsApp numbers?') }}</span>
                        <span class="text-slate-400 font-bold text-lg" x-text="activeFaq === 6 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 6" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed font-medium border-t border-slate-100 pt-3">
                        {{ get_option('landing_faq6_a', 'Yes, our Professional and Business plans support multiple concurrent WhatsApp numbers and team members.') }}
                    </div>
                </div>

                <!-- FAQ 7 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 7 ? null : 7)" class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm text-slate-900">
                        <span>{{ get_option('landing_faq7_q', 'Do you provide customer support?') }}</span>
                        <span class="text-slate-400 font-bold text-lg" x-text="activeFaq === 7 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 7" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed font-medium border-t border-slate-100 pt-3">
                        {{ get_option('landing_faq7_a', 'Our dedicated support team is available 24/7 via live chat, email, and WhatsApp to help you set up and scale.') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 7: BOTTOM CTA BANNER (Screenshot 5 Middle)              -->
<!-- ============================================================== -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="bg-gradient-to-r from-[#047857] via-[#065f46] to-[#047857] rounded-[32px] p-8 sm:p-12 md:p-14 relative overflow-hidden shadow-2xl shadow-emerald-900/20 text-white">
            
            <!-- Soft Ambient Glow -->
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-emerald-400/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-teal-400/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <!-- Large Circular WhatsApp Logo (Left) -->
                <div class="lg:col-span-2 flex justify-center lg:justify-start">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-4xl md:text-5xl text-white shadow-inner">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                </div>

                <!-- Text Content (Center) -->
                <div class="lg:col-span-6 text-center lg:text-left">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight mb-3 leading-snug">
                        {{ get_option('landing_cta_title', 'Ready to scale your WhatsApp marketing?') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-emerald-100 font-medium leading-relaxed max-w-xl">
                        {{ get_option('landing_cta_subtitle', 'Connect your WhatsApp account in seconds. Start sending bulk campaigns, automating customer replies, and converting more leads with zero coding required.') }}
                    </p>
                </div>

                <!-- Right Action Buttons -->
                <div class="lg:col-span-4 flex flex-col items-center lg:items-end justify-center gap-3">
                    <a href="{{ url(get_option('landing_cta_btn_url', 'auth/signup')) }}" class="px-8 py-4 rounded-full font-black text-sm bg-white text-emerald-900 shadow-xl hover:bg-slate-100 transition transform hover:-translate-y-0.5 inline-flex items-center gap-2">
                        <span>{{ get_option('landing_cta_btn_text', 'Get Started Free') }}</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    <a href="#pricing" class="text-xs font-semibold text-emerald-100 underline hover:text-white transition">
                        {{ __('View Pricing Plans →') }}
                    </a>
                </div>
            </div>

            <!-- Floating Green WhatsApp Badge on Corner -->
            <div class="absolute bottom-4 right-4 w-9 h-9 rounded-full bg-emerald-500/80 backdrop-blur text-white flex items-center justify-center text-sm shadow-md hidden md:flex">
                <i class="fab fa-whatsapp"></i>
            </div>
        </div>
    </div>
</section>

@endsection