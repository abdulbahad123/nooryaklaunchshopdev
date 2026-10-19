@php
    $pricing = \Pricing::plansWithFeatures();
    $planTypes = \Modules\AdminPlans\Facades\Plan::getTypes();
    $activeType = 2; // Default to Yearly as per reference design
    if (!isset($planTypes[$activeType])) {
        $activeType = array_key_first($planTypes) ?: 1;
    }

    // Resolve plan secure IDs for Starter, Standard and Premium
    $starterMonthlySec = ''; $starterYearlySec = '';
    $standardMonthlySec = ''; $standardYearlySec = '';
    $premiumMonthlySec = ''; $premiumYearlySec = '';

    if (isset($pricing[1])) {
        foreach ($pricing[1] as $p) {
            $pName = strtolower($p['name'] ?? '');
            if (!empty($p['free_plan']) || strpos($pName, 'starter') !== false) {
                $starterMonthlySec = $p['id_secure'] ?? '';
            } elseif (strpos($pName, 'standard') !== false || (float)($p['price'] ?? 0) == 5) {
                $standardMonthlySec = $p['id_secure'] ?? '';
            } elseif (strpos($pName, 'premium') !== false || (float)($p['price'] ?? 0) == 10) {
                $premiumMonthlySec = $p['id_secure'] ?? '';
            }
        }
    }
    if (isset($pricing[2])) {
        foreach ($pricing[2] as $p) {
            $pName = strtolower($p['name'] ?? '');
            if (!empty($p['free_plan']) || strpos($pName, 'starter') !== false) {
                $starterYearlySec = $p['id_secure'] ?? '';
            } elseif (strpos($pName, 'standard') !== false || (float)($p['price'] ?? 0) == 39) {
                $standardYearlySec = $p['id_secure'] ?? '';
            } elseif (strpos($pName, 'premium') !== false || (float)($p['price'] ?? 0) == 89) {
                $premiumYearlySec = $p['id_secure'] ?? '';
            }
        }
    }

    if (!$starterYearlySec && $starterMonthlySec) $starterYearlySec = $starterMonthlySec;
    if (!$starterMonthlySec && $starterYearlySec) $starterMonthlySec = $starterYearlySec;
    if (!$standardYearlySec && $standardMonthlySec) $standardYearlySec = $standardMonthlySec;
    if (!$standardMonthlySec && $standardYearlySec) $standardMonthlySec = $standardYearlySec;
    if (!$premiumYearlySec && $premiumMonthlySec) $premiumYearlySec = $premiumMonthlySec;
    if (!$premiumMonthlySec && $premiumYearlySec) $premiumMonthlySec = $premiumYearlySec;

    $isLoggedIn = auth()->check();

    // Starter URLs
    $starterUrl1 = $isLoggedIn ? ($starterMonthlySec ? route('payment.index', $starterMonthlySec) : url('app/dashboard')) : url('auth/signup' . ($starterMonthlySec ? '?plan=' . $starterMonthlySec : ''));
    $starterUrl2 = $isLoggedIn ? ($starterYearlySec ? route('payment.index', $starterYearlySec) : url('app/dashboard')) : url('auth/signup' . ($starterYearlySec ? '?plan=' . $starterYearlySec : ''));

    // Standard URLs
    $standardUrl1 = $isLoggedIn ? ($standardMonthlySec ? route('payment.index', $standardMonthlySec) : url('auth/signup?plan=standard')) : url('auth/signup' . ($standardMonthlySec ? '?plan=' . $standardMonthlySec : '?plan=standard'));
    $standardUrl2 = $isLoggedIn ? ($standardYearlySec ? route('payment.index', $standardYearlySec) : url('auth/signup?plan=standard')) : url('auth/signup' . ($standardYearlySec ? '?plan=' . $standardYearlySec : '?plan=standard'));

    // Premium URLs
    $premiumUrl1 = $isLoggedIn ? ($premiumMonthlySec ? route('payment.index', $premiumMonthlySec) : url('auth/signup?plan=premium')) : url('auth/signup' . ($premiumMonthlySec ? '?plan=' . $premiumMonthlySec : '?plan=premium'));
    $premiumUrl2 = $isLoggedIn ? ($premiumYearlySec ? route('payment.index', $premiumYearlySec) : url('auth/signup?plan=premium')) : url('auth/signup' . ($premiumYearlySec ? '?plan=' . $premiumYearlySec : '?plan=premium'));
@endphp

<style>
.wab-pricing-wrap {
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    position: relative;
    padding: 80px 0 100px 0;
    background: radial-gradient(circle at 50% 15%, rgba(255, 85, 0, 0.08) 0%, rgba(255, 250, 247, 0.7) 40%, #ffffff 80%);
    overflow: hidden;
}
.wab-pricing-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 24px;
}
.wab-pricing-header {
    max-width: 760px;
    margin: 0 auto 48px auto;
    text-align: center;
}
.wab-pricing-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    align-items: stretch;
}
@media (max-width: 1024px) {
    .wab-pricing-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .wab-pricing-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<section id="pricing" x-data="{
        type: {{ $activeType }},
        starterUrls: { 1: '{{ $starterUrl1 }}', 2: '{{ $starterUrl2 }}' },
        standardUrls: { 1: '{{ $standardUrl1 }}', 2: '{{ $standardUrl2 }}' },
        premiumUrls: { 1: '{{ $premiumUrl1 }}', 2: '{{ $premiumUrl2 }}' }
    }" class="wab-pricing-wrap">
    <div class="wab-pricing-container">
        <!-- Section Header -->
        <div class="wab-pricing-header">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4" style="background: rgba(255, 85, 0, 0.08); color: #ff5500; border: 1px solid rgba(255, 85, 0, 0.2);">
                SIMPLE, TRANSPARENT PRICING
            </span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight leading-tight mb-4">
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

        <!-- 4 Pricing Cards Grid (Rupee Pricing + No Colliding Badges) -->
        <div class="wab-pricing-grid">
            
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
                            <span class="text-4xl md:text-5xl font-black text-gray-900">₹0</span>
                            <span class="text-sm font-semibold text-gray-400" x-text="type == 2 ? '/ year' : '/ month'">/ year</span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-1" x-text="type == 2 ? 'Billed yearly' : 'Billed monthly'">Billed yearly</span>
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

                <a :href="starterUrls[type] || '{{ $starterUrl2 }}'" class="w-full py-3.5 rounded-full text-center font-bold text-sm text-white shadow-md transition hover:opacity-95" style="background: #ff5500; box-shadow: 0 4px 14px rgba(255, 85, 0, 0.3);">
                    Start for Free <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>

            <!-- Card 2: Standard (Recommended / Most Popular) -->
            <div class="relative bg-white rounded-3xl border-2 border-[#ff5500] p-8 flex flex-col justify-between shadow-xl transition hover:-translate-y-1">
                <!-- Most Popular Floating Badge (Cleanly Positioned) -->
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 whitespace-nowrap z-10">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-xs font-bold text-white shadow-md" style="background: linear-gradient(135deg, #ff5500 0%, #ff7728 100%);">
                        <i class="fa-solid fa-fire text-[10px]"></i> Most Popular
                    </span>
                </div>

                <div>
                    <div class="flex justify-center mb-4 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-[#fff0e6] text-[#ff5500]">
                            Standard
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
                            <span class="text-4xl md:text-5xl font-black text-gray-900" x-text="type == 2 ? '₹39' : '₹5'">₹39</span>
                            <span class="text-sm font-semibold text-gray-400" x-text="type == 2 ? '/ year' : '/ month'">/ year</span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-1" x-text="type == 2 ? 'Billed yearly' : 'Billed monthly'">Billed yearly</span>
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

                <a :href="standardUrls[type] || '{{ $standardUrl2 }}'" class="w-full py-3.5 rounded-full text-center font-bold text-sm text-white shadow-lg transition hover:opacity-95" style="background: #ff5500; box-shadow: 0 6px 18px rgba(255, 85, 0, 0.4);">
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
                            <span class="text-4xl md:text-5xl font-black text-gray-900" x-text="type == 2 ? '₹89' : '₹10'">₹89</span>
                            <span class="text-sm font-semibold text-gray-400" x-text="type == 2 ? '/ year' : '/ month'">/ year</span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-1" x-text="type == 2 ? 'Billed yearly' : 'Billed monthly'">Billed yearly</span>
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

                <a :href="premiumUrls[type] || '{{ $premiumUrl2 }}'" class="w-full py-3.5 rounded-full text-center font-bold text-sm text-white shadow-md transition hover:opacity-95" style="background: #ff5500; box-shadow: 0 4px 14px rgba(255, 85, 0, 0.3);">
                    Get Started <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>

            <!-- Card 4: Enterprise -->
            <div class="relative bg-white rounded-3xl border border-gray-200 p-8 flex flex-col justify-between shadow-xs hover:shadow-xl transition hover:-translate-y-1">
                <!-- Floating Badge -->
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 whitespace-nowrap z-10">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold text-[#ff5500] bg-[#fff0e6] border border-[#ffcca8] shadow-xs">
                        <i class="fa-solid fa-star text-[10px]"></i> Enterprise Grade
                    </span>
                </div>

                <div>
                    <div class="flex justify-center mb-4 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600">
                            Custom Scale
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