@extends('layouts.app')

@section('content')

<!-- ============================================================== -->
<!-- SECTION 1: HERO & DASHBOARD MOCKUP (Screenshot 2 Top)           -->
<!-- ============================================================== -->
<section class="relative pt-12 pb-20 md:pt-16 md:pb-24 overflow-hidden bg-gradient-to-b from-[#e8fbf3] via-[#f4fdf8] to-white">
    <!-- Ambient Green Background Accents -->
    <div class="absolute top-0 right-0 w-[550px] h-[550px] bg-gradient-to-bl from-emerald-200/40 via-teal-100/20 to-transparent rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-100/30 rounded-full blur-2xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Text Content -->
            <div class="lg:col-span-6">
                <!-- Pill Badge -->
                <div class="mb-5">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-[#065f46] border border-emerald-300/60">
                        <i class="fas fa-bolt text-[11px] text-[#059669]"></i>
                        <span>{!! get_option('landing_hero_badge', 'ALL-IN-ONE WHATSAPP MARKETING TOOL') !!}</span>
                    </span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-[56px] font-black text-slate-900 tracking-tight leading-[1.12] mb-5">
                    {!! get_option('landing_hero_title', "WhatsApp operations,<br><span class=\"text-[#059669]\">campaigns,</span> and<br>automation") !!}
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

                <!-- 3 Metric Highlight Cards -->
                <div class="grid grid-cols-3 gap-3 max-w-lg">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-3 border border-emerald-100/80 shadow-xs flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-[#059669] shrink-0">
                            <i class="fa-solid fa-box text-base"></i>
                        </div>
                        <div>
                            <span class="block text-xl font-black text-slate-900 leading-none">{{ get_option('landing_stat1_number', '8') }}</span>
                            <span class="text-[10px] text-slate-500 leading-tight block mt-0.5">{{ get_option('landing_stat1_label', 'Operational modules included') }}</span>
                        </div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-3 border border-emerald-100/80 shadow-xs flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-[#059669] shrink-0">
                            <i class="fa-solid fa-link text-base"></i>
                        </div>
                        <div>
                            <span class="block text-xl font-black text-slate-900 leading-none">{{ get_option('landing_stat2_number', '1') }}</span>
                            <span class="text-[10px] text-slate-500 leading-tight block mt-0.5">{{ get_option('landing_stat2_label', 'Unified WhatsApp workflow') }}</span>
                        </div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-3 border border-emerald-100/80 shadow-xs flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-[#059669] shrink-0">
                            <i class="fa-solid fa-bolt text-base"></i>
                        </div>
                        <div>
                            <span class="block text-xl font-black text-slate-900 leading-none">{{ get_option('landing_stat3_number', '24/7') }}</span>
                            <span class="text-[10px] text-slate-500 leading-tight block mt-0.5">{{ get_option('landing_stat3_label', 'Automation & reply coverage') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Hero Visual: Browser Dashboard + Phone Mockup -->
            <div class="lg:col-span-6 relative">
                <!-- Outer Browser / Tablet Card -->
                <div class="bg-white rounded-[28px] border border-slate-200/90 shadow-[0_25px_60px_rgba(5,150,105,0.12)] overflow-hidden">
                    <!-- Browser Window Top Bar -->
                    <div class="bg-slate-50/90 border-b border-slate-100 px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                            <span class="text-[#059669] font-extrabold">Whatsappbulk</span>
                            <span class="text-slate-300">|</span>
                            <span>Dashboard</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                            <div class="w-5 h-5 rounded-full bg-amber-500 text-white flex items-center justify-center text-[10px] font-bold">A</div>
                            <span class="text-[11px] font-medium">Hi, Admin</span>
                        </div>
                    </div>

                    <!-- Inner App Layout -->
                    <div class="p-5 grid grid-cols-12 gap-4 bg-white">
                        <!-- Mini Left App Sidebar -->
                        <div class="col-span-4 border-r border-slate-100 pr-3 space-y-1.5 text-[11px] font-semibold text-slate-600">
                            <div class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-[#059669] font-bold flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-xs"></i> Dashboard
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                                <i class="fa-solid fa-bullhorn text-xs text-slate-400"></i> Bulk Campaigns
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                                <i class="fa-solid fa-wand-magic-sparkles text-xs text-slate-400"></i> AI Smart Reply
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                                <i class="fa-solid fa-clock-rotate-left text-xs text-slate-400"></i> Auto Reply
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                                <i class="fa-solid fa-robot text-xs text-slate-400"></i> Chatbot
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                                <i class="fa-solid fa-address-book text-xs text-slate-400"></i> Contacts
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                                <i class="fa-solid fa-file-export text-xs text-slate-400"></i> Export Participants
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                                <i class="fa-solid fa-id-card text-xs text-slate-400"></i> Profile Info
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                                <i class="fa-solid fa-chart-column text-xs text-slate-400"></i> Reports
                            </div>
                        </div>

                        <!-- Main Analytics View -->
                        <div class="col-span-8 pl-1">
                            <!-- 4 Stats Cards -->
                            <div class="grid grid-cols-2 gap-2.5 mb-3">
                                <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 font-bold block">Total Messages</span>
                                    <span class="text-base font-black text-slate-900">125,890</span>
                                    <span class="text-[9px] font-bold text-emerald-600 block">▲ 20.4%</span>
                                </div>
                                <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 font-bold block">Delivered</span>
                                    <span class="text-base font-black text-slate-900">118,420</span>
                                    <span class="text-[9px] font-bold text-emerald-600 block">▲ 16.1%</span>
                                </div>
                                <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 font-bold block">Replied</span>
                                    <span class="text-base font-black text-slate-900">28,460</span>
                                    <span class="text-[9px] font-bold text-emerald-600 block">▲ 14.2%</span>
                                </div>
                                <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 font-bold block">Active Contacts</span>
                                    <span class="text-base font-black text-slate-900">96,350</span>
                                    <span class="text-[9px] font-bold text-emerald-600 block">▲ 12.6%</span>
                                </div>
                            </div>

                            <!-- Performance Line Chart -->
                            <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[11px] font-bold text-slate-700">Message Performance</span>
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Last 30 Days</span>
                                </div>
                                <svg class="w-full h-14" viewBox="0 0 300 65" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="heroChartGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" stop-color="#f97316" stop-opacity="0.3"/>
                                            <stop offset="100%" stop-color="#f97316" stop-opacity="0.0"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M0,50 Q40,40 80,25 T160,30 T240,15 T300,28 L300,65 L0,65 Z" fill="url(#heroChartGrad)"/>
                                    <path d="M0,50 Q40,40 80,25 T160,30 T240,15 T300,28" fill="none" stroke="#f97316" stroke-width="2.5" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <!-- Recent Campaigns Mini Table -->
                            <div class="space-y-1.5 text-[10px]">
                                <div class="flex items-center justify-between py-1 border-b border-slate-100">
                                    <span class="font-bold text-slate-800"><i class="fas fa-star text-amber-500 mr-1 text-[9px]"></i> Product Launch</span>
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[9px]">Completed</span>
                                    <span class="font-bold text-slate-600">12,450</span>
                                </div>
                                <div class="flex items-center justify-between py-1 border-b border-slate-100">
                                    <span class="font-bold text-slate-800"><i class="fas fa-bullhorn text-orange-500 mr-1 text-[9px]"></i> Festival Offer</span>
                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-bold text-[9px]">Sending</span>
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

                <!-- Overlapping Smartphone Mockup (Screenshot 2 bottom right) -->
                <div class="absolute -bottom-8 -right-4 w-44 md:w-52 bg-white rounded-[32px] border-4 border-slate-800 shadow-2xl p-3 hidden sm:block">
                    <div class="text-center pt-1 pb-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-500 text-white mx-auto flex items-center justify-center text-xl mb-1 shadow-md shadow-emerald-500/30">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700 block">Campaign Overview</span>
                        <span class="text-2xl font-black text-orange-500 block">95.8%</span>
                    </div>
                    <!-- Mini Bar Chart -->
                    <div class="flex items-end justify-center gap-1.5 h-12 pt-2 border-t border-slate-100">
                        <div class="w-3 bg-orange-400 rounded-t h-6"></div>
                        <div class="w-3 bg-orange-500 rounded-t h-9"></div>
                        <div class="w-3 bg-orange-400 rounded-t h-5"></div>
                        <div class="w-3 bg-orange-600 rounded-t h-11"></div>
                        <div class="w-3 bg-orange-500 rounded-t h-8"></div>
                        <div class="w-3 bg-emerald-500 rounded-t h-12"></div>
                    </div>
                    <!-- Floating Corner WhatsApp Badge -->
                    <div class="absolute -bottom-3 -right-3 w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg text-lg">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- SECTION 2: LIVE MODULE STACK (4 Cards in a Row, Screenshot 2)   -->
<!-- ============================================================== -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-14">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-[#065f46] border border-emerald-300/60 mb-3">
                <i class="fas fa-layer-group text-[11px] text-[#059669]"></i>
                <span>{!! get_option('landing_stack_badge', 'LIVE MODULE STACK') !!}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-3">
                {!! get_option('landing_stack_title', 'Whatsappbulk <span class="text-[#059669]">Workspace</span>') !!}
            </h2>
            <p class="text-sm md:text-base text-slate-500 font-medium">
                {{ get_option('landing_stack_subtitle', 'Everything you need to run WhatsApp at scale') }}
            </p>
        </div>

        <!-- 4 Rounded Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Stack Card 1 -->
            <div class="bg-white rounded-[24px] p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-emerald-500/50 transition duration-300 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl mb-5 shadow-sm">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">
                        {{ get_option('landing_stack1_tag', 'CAMPAIGN CONTROL') }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 mb-2">
                        {{ get_option('landing_stack1_title', 'Bulk Campaigns') }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        {{ get_option('landing_stack1_desc', 'Segment contacts, send at scale, and track response flow.') }}
                    </p>
                </div>
                <div class="mt-8">
                    <span class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center text-xs group-hover:translate-x-1 transition shadow-sm">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>

            <!-- Stack Card 2 -->
            <div class="bg-white rounded-[24px] p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-emerald-500/50 transition duration-300 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl mb-5 shadow-sm">
                        <i class="fas fa-comments"></i>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">
                        {{ get_option('landing_stack2_tag', 'INBOUND HANDLING') }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 mb-2">
                        {{ get_option('landing_stack2_title', 'AI + Auto Reply') }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        {{ get_option('landing_stack2_desc', 'Combine automation with faster human response quality.') }}
                    </p>
                </div>
                <div class="mt-8">
                    <span class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center text-xs group-hover:translate-x-1 transition shadow-sm">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>

            <!-- Stack Card 3 -->
            <div class="bg-white rounded-[24px] p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-emerald-500/50 transition duration-300 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl mb-5 shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">
                        {{ get_option('landing_stack3_tag', 'LEAD FLOW') }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 mb-2">
                        {{ get_option('landing_stack3_title', 'Chatbot + Contacts') }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        {{ get_option('landing_stack3_desc', 'Capture, qualify, and manage audience data in one place.') }}
                    </p>
                </div>
                <div class="mt-8">
                    <span class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center text-xs group-hover:translate-x-1 transition shadow-sm">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>

            <!-- Stack Card 4 -->
            <div class="bg-white rounded-[24px] p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-emerald-500/50 transition duration-300 flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl mb-5 shadow-sm">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">
                        {{ get_option('landing_stack4_tag', 'VISIBILITY') }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 mb-2">
                        {{ get_option('landing_stack4_title', 'Reports + Export') }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        {{ get_option('landing_stack4_desc', 'Export participants and measure what the team actually ships.') }}
                    </p>
                </div>
                <div class="mt-8">
                    <span class="w-8 h-8 rounded-full bg-[#059669] text-white flex items-center justify-center text-xs group-hover:translate-x-1 transition shadow-sm">
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
<section id="modules" class="py-24 bg-[#f2fbf6]/50 border-t border-slate-100">
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
<section id="pricing" class="py-24 bg-white relative">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header with Doodle Arrows -->
        <div class="max-w-3xl mx-auto text-center mb-16 relative">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-[#065f46] border border-emerald-300/60 mb-3">
                <i class="fas fa-tag text-[11px] text-[#059669]"></i>
                <span>{!! get_option('landing_pricing_badge', 'PRICING') !!}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-3">
                {{ get_option('landing_pricing_title', 'Simple and transparent pricing') }}
            </h2>
            <p class="text-sm md:text-base text-slate-500 font-medium">
                {{ get_option('landing_pricing_subtitle', 'Choose the plan that fits your business needs. Upgrade or downgrade anytime.') }}
            </p>
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
<section class="py-24 bg-[#f2fbf6]/50 border-t border-slate-100">
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
<section id="faqs" class="py-24 bg-white" x-data="{ activeFaq: 1 }">
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