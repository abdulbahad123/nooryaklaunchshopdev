@php
    $pricing = \Pricing::plansWithFeatures();
    $planTypes = \Modules\AdminPlans\Facades\Plan::getTypes();
    $activeType = 2; // Default to Yearly as per reference design
    if (!isset($planTypes[$activeType])) {
        $activeType = array_key_first($planTypes) ?: 1;
    }
@endphp

<section id="pricing" x-data="{ type: {{ $activeType }} }" class="relative py-24 bg-gradient-to-b from-white via-[#fffaf7] to-white overflow-hidden">
    <!-- Glow effect -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[300px] bg-orange-100/40 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-12">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4" style="background: rgba(255, 85, 0, 0.08); color: #ff5500; border: 1px solid rgba(255, 85, 0, 0.2);">
                SIMPLE, TRANSPARENT PRICING
            </span>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight leading-tight mb-4">
                Choose the <span style="color: #ff5500;">perfect plan</span> for your business
            </h2>
            <p class="text-base md:text-lg text-gray-500 font-medium">
                Launch your WhatsApp operations in minutes and scale as your business grows.
            </p>
        </div>

        <!-- Billing Period Toggle -->
        <div class="flex justify-center mb-16">
            <div class="inline-flex items-center p-1.5 rounded-full bg-white border border-gray-200 shadow-sm">
                @foreach($planTypes as $typeKey => $typeLabel)
                    <button
                        type="button"
                        class="relative px-6 py-2.5 rounded-full text-sm font-bold transition flex items-center gap-2"
                        :class="type == {{ $typeKey }} ? 'bg-[#ff5500] text-white shadow-md' : 'text-gray-600 hover:text-gray-900'"
                        x-on:click="type = {{ $typeKey }}"
                    >
                        <span>{{ __($typeLabel) }}</span>
                        @if($typeKey == 2)
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full transition"
                                  :class="type == 2 ? 'bg-white text-[#ff5500]' : 'bg-[#fff0e6] text-[#ff5500]'">
                                Save up to 20%
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <!-- 4 Pricing Cards Grid (Matching Reference Image Pixel-by-Pixel) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
            
            <!-- Card 1: Starter -->
            <div class="relative bg-white rounded-3xl border border-gray-200 p-8 flex flex-col justify-between shadow-xs hover:shadow-xl transition hover:-translate-y-1">
                <div>
                    <div class="flex justify-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600">
                            Starter
                        </span>
                    </div>
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-[#fff2ea] flex items-center justify-center text-[#ff5500] mb-3">
                            <i class="fa-solid fa-paper-plane text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Whatsappbulk Starter</h3>
                        <p class="text-xs text-gray-500 mt-1">Perfect for WhatsApp beginners</p>
                    </div>

                    <div class="text-center mb-6 pb-6 border-b border-gray-100">
                        <div class="flex items-baseline justify-center gap-1">
                            <span class="text-4xl md:text-5xl font-black text-gray-900">$0</span>
                            <span class="text-sm font-semibold text-gray-400">/ year</span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-1">Billed yearly</span>
                    </div>

                    <ul class="space-y-3.5 mb-8 text-sm text-gray-700">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>WhatsApp messages / month: 10,000</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>AI Contents</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>URL Shortener</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>1 team member</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>Premium Support</span>
                        </li>
                    </ul>
                </div>

                <a href="{{ url('auth/signup') }}" class="w-full py-3.5 rounded-full text-center font-bold text-sm text-white shadow-md transition hover:opacity-95" style="background: #ff5500; box-shadow: 0 4px 14px rgba(255, 85, 0, 0.3);">
                    Start for Free <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>

            <!-- Card 2: Standard (Recommended / Most Popular) -->
            <div class="relative bg-white rounded-3xl border-2 border-[#ff5500] p-8 flex flex-col justify-between shadow-xl transition hover:-translate-y-1">
                <!-- Most Popular Floating Badge -->
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-xs font-bold text-white shadow-md" style="background: linear-gradient(135deg, #ff5500 0%, #ff7728 100%);">
                        <i class="fa-solid fa-fire text-[10px]"></i> Most Popular
                    </span>
                </div>

                <div>
                    <div class="flex justify-center mb-4 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-[#fff0e6] text-[#ff5500]">
                            RECOMMENDED
                        </span>
                    </div>
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-[#fff2ea] flex items-center justify-center text-[#ff5500] mb-3">
                            <i class="fa-solid fa-bullhorn text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Whatsappbulk Standard</h3>
                        <p class="text-xs text-gray-500 mt-1">For growing teams</p>
                    </div>

                    <div class="text-center mb-6 pb-6 border-b border-gray-100">
                        <div class="flex items-baseline justify-center gap-1">
                            <span class="text-4xl md:text-5xl font-black text-gray-900">$39</span>
                            <span class="text-sm font-semibold text-gray-400">/ year</span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-1">Billed yearly</span>
                    </div>

                    <ul class="space-y-3.5 mb-8 text-sm text-gray-700">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>WhatsApp messages / month: 100,000</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>AI Contents</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>URL Shortener</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>5 team members</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>Priority Support</span>
                        </li>
                    </ul>
                </div>

                <a href="{{ url('auth/signup') }}" class="w-full py-3.5 rounded-full text-center font-bold text-sm text-white shadow-lg transition hover:opacity-95" style="background: #ff5500; box-shadow: 0 6px 18px rgba(255, 85, 0, 0.4);">
                    Get Started <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>

            <!-- Card 3: Premium -->
            <div class="relative bg-white rounded-3xl border border-gray-200 p-8 flex flex-col justify-between shadow-xs hover:shadow-xl transition hover:-translate-y-1">
                <div>
                    <div class="flex justify-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-[#fff0e6] text-[#ff5500]">
                            RECOMMENDED
                        </span>
                    </div>
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-[#fff2ea] flex items-center justify-center text-[#ff5500] mb-3">
                            <i class="fa-solid fa-crown text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Whatsappbulk Premium</h3>
                        <p class="text-xs text-gray-500 mt-1">For advanced businesses</p>
                    </div>

                    <div class="text-center mb-6 pb-6 border-b border-gray-100">
                        <div class="flex items-baseline justify-center gap-1">
                            <span class="text-4xl md:text-5xl font-black text-gray-900">$89</span>
                            <span class="text-sm font-semibold text-gray-400">/ year</span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-1">Billed yearly</span>
                    </div>

                    <ul class="space-y-3.5 mb-8 text-sm text-gray-700">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>WhatsApp messages / month: 500,000</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>AI Contents</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>URL Shortener</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>Unlimited team members</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>Priority Support</span>
                        </li>
                    </ul>
                </div>

                <a href="{{ url('auth/signup') }}" class="w-full py-3.5 rounded-full text-center font-bold text-sm text-white shadow-md transition hover:opacity-95" style="background: #ff5500; box-shadow: 0 4px 14px rgba(255, 85, 0, 0.3);">
                    Get Started <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>

            <!-- Card 4: Enterprise -->
            <div class="relative bg-white rounded-3xl border border-gray-200 p-8 flex flex-col justify-between shadow-xs hover:shadow-xl transition hover:-translate-y-1">
                <!-- Most People Choose Floating Badge -->
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold text-[#ff5500] bg-[#fff0e6] border border-[#ffcca8] shadow-xs">
                        <i class="fa-solid fa-star text-[10px]"></i> Most People Choose
                    </span>
                </div>

                <div>
                    <div class="flex justify-center mb-4 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600">
                            Enterprise
                        </span>
                    </div>
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center text-gray-700 mb-3">
                            <i class="fa-solid fa-building text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Enterprise</h3>
                        <p class="text-xs text-gray-500 mt-1">Tailored to your needs</p>
                    </div>

                    <div class="text-center mb-6 pb-6 border-b border-gray-100">
                        <div class="flex items-baseline justify-center gap-1">
                            <span class="text-4xl font-black text-gray-900">Custom</span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-1">Scalable infrastructure</span>
                    </div>

                    <ul class="space-y-3.5 mb-8 text-sm text-gray-700">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>Custom message volume</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>Advanced integrations</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>Dedicated account manager</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>Custom features</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-xs text-[#ff5500]"></i>
                            <span>SLAs & premium support</span>
                        </li>
                    </ul>
                </div>

                <a href="{{ url('contact') }}" class="w-full py-3.5 rounded-full text-center font-bold text-sm text-[#ff5500] border-2 border-[#ff5500] bg-white hover:bg-[#fff2ea] transition">
                    Contact Sales <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>

        </div>
    </div>
</section>