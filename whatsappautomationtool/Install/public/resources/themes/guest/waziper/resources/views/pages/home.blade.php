@php
    $modules = [
        [
            'title' => 'AI Smart Reply',
            'icon' => 'fa-sparkles',
            'color' => '#10b981',
            'bg' => '#ecfdf5',
            'desc' => 'Generate fast, context-aware WhatsApp replies so agents can handle conversations with less manual effort.',
        ],
        [
            'title' => 'Auto Reply',
            'icon' => 'fa-clock-rotate-left',
            'color' => '#f97316',
            'bg' => '#fff7ed',
            'desc' => 'Trigger automatic responses for inbound messages, off-hours support, lead capture, and common request handling.',
        ],
        [
            'title' => 'Bulk Campaigns',
            'icon' => 'fa-bullhorn',
            'color' => '#ff5500',
            'bg' => '#fff2ea',
            'desc' => 'Launch high-volume WhatsApp campaigns to segmented audiences while keeping timing and response flow under control.',
        ],
        [
            'title' => 'Chatbot',
            'icon' => 'fa-robot',
            'color' => '#eab308',
            'bg' => '#fefce8',
            'desc' => 'Build conversational flows that qualify leads, answer questions, and move contacts into the next step automatically.',
        ],
        [
            'title' => 'Contacts',
            'icon' => 'fa-address-book',
            'color' => '#ff5500',
            'bg' => '#fff2ea',
            'desc' => 'Manage contact lists, segments, and campaign targets from one structured database.',
        ],
        [
            'title' => 'Export Participants',
            'icon' => 'fa-users',
            'color' => '#10b981',
            'bg' => '#ecfdf5',
            'desc' => 'Extract participants from WhatsApp groups for outreach, qualification, migration, and audience building workflows.',
        ],
        [
            'title' => 'Profile Info',
            'icon' => 'fa-id-card',
            'color' => '#f97316',
            'bg' => '#fff7ed',
            'desc' => 'Review WhatsApp profile information quickly to enrich lead context and improve handoff quality.',
        ],
        [
            'title' => 'Reports',
            'icon' => 'fa-chart-column',
            'color' => '#ff5500',
            'bg' => '#fff2ea',
            'desc' => 'Track campaign output, reply activity, and workflow performance with clear reporting for real operational decisions.',
        ],
    ];
@endphp

<!-- 1. HERO SECTION -->
<section class="relative pt-12 pb-24 md:pt-16 md:pb-28 overflow-hidden bg-gradient-to-b from-[#fffaf7] via-[#fffdfb] to-white">
    <!-- Subtle Ambient Glow -->
    <div class="absolute top-0 right-0 w-[600px] h-[500px] bg-gradient-to-bl from-orange-200/30 via-orange-100/20 to-transparent rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Hero Left Content -->
            <div class="lg:col-span-6">
                <!-- Badge -->
                <div class="mb-5">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider" style="background: rgba(255, 85, 0, 0.08); color: #ff5500; border: 1px solid rgba(255, 85, 0, 0.2);">
                        All-in-One WhatsApp Marketing Tool
                    </span>
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-[1.08] mb-5">
                    WhatsApp operations,<br>
                    <span style="color: #ff5500;">campaigns,</span> and<br>
                    automation
                </h1>

                <!-- Subheadlines -->
                <p class="text-base sm:text-lg font-bold text-gray-800 mb-2 leading-snug">
                    One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.
                </p>
                <p class="text-sm text-gray-500 font-medium leading-relaxed mb-8 max-w-lg">
                    Built around the modules teams actually use every day: bulk outreach, smart replies, auto replies, chatbots, contacts, participant export, profile lookups, and reporting.
                </p>

                <!-- Dual CTA Buttons -->
                <div class="flex flex-wrap items-center gap-4 mb-10">
                    <a href="{{ url('auth/signup') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full text-white font-bold text-sm shadow-lg transition hover:opacity-95" style="background: #ff5500; box-shadow: 0 8px 24px rgba(255, 85, 0, 0.35);">
                        <span>Create your workspace</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    <a href="#modules" class="inline-flex items-center px-7 py-3.5 rounded-full text-gray-700 font-bold text-sm bg-white border border-gray-300 hover:bg-gray-50 transition shadow-xs">
                        Explore modules
                    </a>
                </div>

                <!-- 3 Metric Highlights Bar -->
                <div class="grid grid-cols-3 gap-3 max-w-lg pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500]">
                            <i class="fa-solid fa-cube text-base"></i>
                        </div>
                        <div>
                            <span class="block text-xl font-black text-gray-900 leading-none">8</span>
                            <span class="text-[11px] text-gray-500 leading-tight block mt-0.5">Operational modules</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500]">
                            <i class="fa-solid fa-link text-base"></i>
                        </div>
                        <div>
                            <span class="block text-xl font-black text-gray-900 leading-none">1</span>
                            <span class="text-[11px] text-gray-500 leading-tight block mt-0.5">Unified workflow</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500]">
                            <i class="fa-solid fa-bolt text-base"></i>
                        </div>
                        <div>
                            <span class="block text-xl font-black text-gray-900 leading-none">24/7</span>
                            <span class="text-[11px] text-gray-500 leading-tight block mt-0.5">Automation coverage</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Right: Interactive Workspace Dashboard Mockup -->
            <div class="lg:col-span-6 relative">
                <!-- Outer Browser Card -->
                <div class="bg-white rounded-3xl border border-gray-200/80 shadow-[0_20px_60px_rgba(0,0,0,0.08)] overflow-hidden">
                    <!-- Top Window Header -->
                    <div class="bg-[#fafafa] border-b border-gray-100 px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-gray-700">
                            <img src="{{ theme_public_asset('images/whatsappbulklogo.png') }}" alt="" class="h-4 w-auto">
                            <span>Dashboard</span>
                        </div>
                        <div class="w-6"></div>
                    </div>

                    <!-- Inner App Layout -->
                    <div class="p-5 grid grid-cols-12 gap-4">
                        <!-- Mini Left Sidebar -->
                        <div class="col-span-4 border-r border-gray-100 pr-3 space-y-2 text-[11px] font-semibold text-gray-600">
                            <div class="px-2.5 py-1.5 rounded-lg bg-[#fff2ea] text-[#ff5500] font-bold flex items-center gap-2">
                                <i class="fa-solid fa-bullhorn text-xs"></i> Bulk Campaigns
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                                <i class="fa-solid fa-sparkles text-xs"></i> AI Smart Reply
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                                <i class="fa-solid fa-robot text-xs"></i> Chatbot
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                                <i class="fa-solid fa-address-book text-xs"></i> Contacts
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                                <i class="fa-solid fa-file-export text-xs"></i> Export
                            </div>
                            <div class="px-2.5 py-1.5 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                                <i class="fa-solid fa-chart-column text-xs"></i> Reports
                            </div>
                        </div>

                        <!-- Main Analytics View -->
                        <div class="col-span-8 pl-1">
                            <!-- 4 Stats Cards -->
                            <div class="grid grid-cols-2 gap-2.5 mb-4">
                                <div class="bg-[#fafafa] rounded-xl p-2.5 border border-gray-100">
                                    <span class="text-[10px] text-gray-400 font-bold block">Messages Sent</span>
                                    <span class="text-base font-black text-gray-900">125,890</span>
                                    <span class="text-[9px] font-bold text-green-600 block">+12.5%</span>
                                </div>
                                <div class="bg-[#fafafa] rounded-xl p-2.5 border border-gray-100">
                                    <span class="text-[10px] text-gray-400 font-bold block">Delivered</span>
                                    <span class="text-base font-black text-gray-900">118,430</span>
                                    <span class="text-[9px] font-bold text-green-600 block">98.2%</span>
                                </div>
                                <div class="bg-[#fafafa] rounded-xl p-2.5 border border-gray-100">
                                    <span class="text-[10px] text-gray-400 font-bold block">Replies</span>
                                    <span class="text-base font-black text-gray-900">20,480</span>
                                    <span class="text-[9px] font-bold text-green-600 block">+16.3%</span>
                                </div>
                                <div class="bg-[#fafafa] rounded-xl p-2.5 border border-gray-100">
                                    <span class="text-[10px] text-gray-400 font-bold block">Conversions</span>
                                    <span class="text-base font-black text-gray-900">14,350</span>
                                    <span class="text-[9px] font-bold text-green-600 block">+8.4%</span>
                                </div>
                            </div>

                            <!-- Performance Chart Mockup -->
                            <div class="bg-[#fafafa] rounded-xl p-3 border border-gray-100 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[11px] font-bold text-gray-700">Message Performance</span>
                                    <span class="text-[9px] font-bold text-[#ff5500]">Real-time</span>
                                </div>
                                <svg class="w-full h-16" viewBox="0 0 300 70" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="chartGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" stop-color="#ff5500" stop-opacity="0.35"/>
                                            <stop offset="100%" stop-color="#ff5500" stop-opacity="0.0"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M0,50 Q40,45 80,25 T160,35 T240,15 T300,30 L300,70 L0,70 Z" fill="url(#chartGrad)"/>
                                    <path d="M0,50 Q40,45 80,25 T160,35 T240,15 T300,30" fill="none" stroke="#ff5500" stroke-width="2.5" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <!-- Recent Campaigns Table -->
                            <div class="space-y-1.5 text-[10px]">
                                <div class="flex items-center justify-between py-1 border-b border-gray-100">
                                    <span class="font-bold text-gray-800">Product Launch</span>
                                    <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-bold text-[9px]">Delivered</span>
                                </div>
                                <div class="flex items-center justify-between py-1 border-b border-gray-100">
                                    <span class="font-bold text-gray-800">Festival Offer</span>
                                    <span class="px-2 py-0.5 rounded-full bg-orange-100 text-[#ff5500] font-bold text-[9px]">Running</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating Mobile Phone Preview (Overlaid matching Image 1) -->
                <div class="absolute -bottom-6 -right-4 w-44 md:w-48 bg-white rounded-3xl border-4 border-gray-800 shadow-2xl p-2.5 hidden sm:block">
                    <div class="bg-gradient-to-b from-green-600 to-green-700 rounded-2xl p-3 text-white text-center mb-2">
                        <i class="fab fa-whatsapp text-2xl mb-1"></i>
                        <span class="text-[10px] font-bold block">Campaign Overview</span>
                        <span class="text-[8px] opacity-80">Bulk broadcast live</span>
                    </div>
                    <div class="space-y-2 text-[10px] px-1">
                        <div class="flex justify-between items-center py-1 border-b border-gray-100">
                            <span class="text-gray-500">Recipients</span>
                            <span class="font-bold text-gray-800">25,120</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-gray-100">
                            <span class="text-gray-500">Replies</span>
                            <span class="font-bold text-[#ff5500]">9,109</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                            <div class="bg-[#ff5500] h-1.5 rounded-full w-4/5"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 2. SECTION 2: LIVE MODULE STACK / WORKSPACE PRODUCT OVERVIEW -->
<section class="py-20 bg-white border-t border-gray-100">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-3" style="background: rgba(255, 85, 0, 0.08); color: #ff5500; border: 1px solid rgba(255, 85, 0, 0.2);">
                LIVE MODULE STACK
            </span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight">
                Whatsappbulk <span style="color: #ff5500;">Workspace</span>
            </h2>
            <p class="text-sm md:text-base font-semibold text-gray-500 mt-1">
                Product overview
            </p>
        </div>

        <!-- 4 Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="bg-[#fafafa] rounded-3xl p-6 border border-gray-200/80 hover:bg-white hover:border-[#ff5500]/40 transition hover:shadow-lg flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-2xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500] mb-4">
                        <i class="fa-solid fa-bullhorn text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-400 block mb-1">Campaign control</span>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Bulk Campaigns</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Segment contacts, send at scale, and track response flow.
                    </p>
                </div>
                <div class="mt-6 flex justify-end">
                    <span class="w-7 h-7 rounded-full bg-[#fff0e6] text-[#ff5500] flex items-center justify-center text-xs">
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </span>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-[#fafafa] rounded-3xl p-6 border border-gray-200/80 hover:bg-white hover:border-[#ff5500]/40 transition hover:shadow-lg flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-2xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500] mb-4">
                        <i class="fa-solid fa-comments text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-400 block mb-1">Inbound handling</span>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">AI + Auto Reply</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Combine automation with faster human response quality.
                    </p>
                </div>
                <div class="mt-6 flex justify-end">
                    <span class="w-7 h-7 rounded-full bg-[#fff0e6] text-[#ff5500] flex items-center justify-center text-xs">
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </span>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-[#fafafa] rounded-3xl p-6 border border-gray-200/80 hover:bg-white hover:border-[#ff5500]/40 transition hover:shadow-lg flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-2xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500] mb-4">
                        <i class="fa-solid fa-users-gear text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-400 block mb-1">Lead flow</span>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Chatbot + Contacts</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Capture, qualify, and manage audience data in one place.
                    </p>
                </div>
                <div class="mt-6 flex justify-end">
                    <span class="w-7 h-7 rounded-full bg-[#fff0e6] text-[#ff5500] flex items-center justify-center text-xs">
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </span>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-[#fafafa] rounded-3xl p-6 border border-gray-200/80 hover:bg-white hover:border-[#ff5500]/40 transition hover:shadow-lg flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-2xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500] mb-4">
                        <i class="fa-solid fa-chart-column text-lg"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-400 block mb-1">Visibility</span>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Reports + Export</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Export participants and measure what the team actively ships.
                    </p>
                </div>
                <div class="mt-6 flex justify-end">
                    <span class="w-7 h-7 rounded-full bg-[#fff0e6] text-[#ff5500] flex items-center justify-center text-xs">
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. SECTION 3: 8 CORE WHATSAPP FEATURES -->
<section id="modules" class="py-24 bg-[#fafafa] border-t border-gray-100">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-16">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-3" style="background: rgba(255, 85, 0, 0.08); color: #ff5500; border: 1px solid rgba(255, 85, 0, 0.2);">
                Product modules
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight leading-tight mb-3">
                Built from the <span style="color: #ff5500;">WhatsApp features</span> teams actually need
            </h2>
            <p class="text-sm md:text-base text-gray-500 font-medium">
                Every module supports a real operational job, from outreach and automation to contact management and reporting.
            </p>
        </div>

        <!-- 8 Cards Grid (2 rows x 4 columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($modules as $m)
                <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs hover:border-[#ff5500]/50 hover:shadow-xl transition hover:-translate-y-1 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-5" style="background: {{ $m['bg'] }}; color: {{ $m['color'] }};">
                            <i class="fa-solid {{ $m['icon'] }} text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $m['title'] }}</h3>
                        <p class="text-xs text-gray-500 font-medium leading-relaxed">
                            {{ $m['desc'] }}
                        </p>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <span class="w-7 h-7 rounded-full bg-[#fff0e6] text-[#ff5500] flex items-center justify-center text-xs">
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 4. SECTION 4: UNIFIED WHATSAPP SYSTEM WORKFLOW PIPELINE -->
<section class="py-24 bg-white border-t border-gray-100">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Column: Value Prop & Checklist -->
            <div class="lg:col-span-6">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4" style="background: rgba(255, 85, 0, 0.08); color: #ff5500; border: 1px solid rgba(255, 85, 0, 0.2);">
                    How the modules work together
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight leading-tight mb-4">
                    Designed as one <span style="color: #ff5500;">WhatsApp system</span>, not a pile of disconnected tools
                </h2>
                <p class="text-sm md:text-base text-gray-500 font-medium leading-relaxed mb-8">
                    The value comes from how these modules connect: contacts feed campaigns, bots reduce manual load, AI supports agent replies, and reports show what is actually working.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-[#ff5500] text-white flex items-center justify-center shrink-0 mt-0.5 text-xs">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 leading-snug">
                            Run outbound WhatsApp campaigns with contact segmentation and participant export.
                        </span>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-[#ff5500] text-white flex items-center justify-center shrink-0 mt-0.5 text-xs">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 leading-snug">
                            Reduce response time using auto replies, chatbots, and AI-assisted answer generation.
                        </span>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-[#ff5500] text-white flex items-center justify-center shrink-0 mt-0.5 text-xs">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 leading-snug">
                            See performance clearly with reporting that connects activity, campaigns, and reply outcomes.
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Visual 3-Node Workflow Pipeline -->
            <div class="lg:col-span-6 space-y-4">
                
                <!-- Node 1: Outbound Flow -->
                <div class="bg-white rounded-3xl p-6 border-2 border-orange-100 shadow-md hover:border-[#ff5500] transition flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500] shrink-0 text-2xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#ff5500] block mb-0.5">Outbound Flow</span>
                        <h3 class="text-base font-bold text-gray-900">Contacts → Bulk Campaigns</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Segment contacts, launch campaigns, and control message flow without leaving the workspace.
                        </p>
                    </div>
                </div>

                <!-- Node 2: Inbound Flow -->
                <div class="bg-white rounded-3xl p-6 border-2 border-emerald-100 shadow-md hover:border-emerald-500 transition flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0 text-2xl">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 block mb-0.5">Inbound Flow</span>
                        <h3 class="text-base font-bold text-gray-900">Chatbot → Auto Reply → AI Smart Reply</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Handle common messages automatically, then assist agents with higher-quality responses when context matters.
                        </p>
                    </div>
                </div>

                <!-- Node 3: Operations Flow -->
                <div class="bg-white rounded-3xl p-6 border-2 border-amber-100 shadow-md hover:border-amber-500 transition flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0 text-2xl">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 block mb-0.5">Operations Flow</span>
                        <h3 class="text-base font-bold text-gray-900">Profile Info → Reports → Export Participants</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Inspect lead context, review performance, and export WhatsApp group participants for new growth loops.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- 5. SECTION 5: CORE USE CASES -->
<section class="py-24 bg-[#fafafa] border-t border-gray-100">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-16">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-3" style="background: rgba(255, 85, 0, 0.08); color: #ff5500; border: 1px solid rgba(255, 85, 0, 0.2);">
                Core use cases
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight leading-tight">
                Use the platform for growth, support, and daily <span style="color: #ff5500;">WhatsApp operations</span>
            </h2>
        </div>

        <!-- 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-xs hover:shadow-xl transition hover:-translate-y-1">
                <div class="w-14 h-14 rounded-2xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500] text-2xl mb-6">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Outbound Marketing</h3>
                <p class="text-sm text-gray-500 leading-relaxed font-medium">
                    Build segmented contact lists, run bulk campaigns, track responses, and improve conversion using report data.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-xs hover:shadow-xl transition hover:-translate-y-1">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl mb-6">
                    <i class="fa-solid fa-message-check"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Inbound Automation</h3>
                <p class="text-sm text-gray-500 leading-relaxed font-medium">
                    Use chatbot and auto reply logic to handle repetitive inbound conversations without keeping agents on every message.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-xs hover:shadow-xl transition hover:-translate-y-1">
                <div class="w-14 h-14 rounded-2xl bg-[#fff0e6] flex items-center justify-center text-[#ff5500] text-2xl mb-6">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Performance Visibility</h3>
                <p class="text-sm text-gray-500 leading-relaxed font-medium">
                    Use profile lookups, reports, and participant export to move from scattered chats to measurable operational workflows.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- PRICING, FAQS & BLOG PARTIALS -->
@include("partials.pricing")
@include("partials.faqs")
@include("partials.home-blog")