@php
    $pricing = \Pricing::plansWithFeatures();
    $planTypes = \Modules\AdminPlans\Facades\Plan::getTypes();
    $hasDynamicPlans = false;
    $defaultType = 1;
    foreach($planTypes as $tk => $tl) {
        if (!empty($pricing[$tk])) {
            $hasDynamicPlans = true;
            if (!$defaultType || empty($pricing[$defaultType])) {
                $defaultType = $tk;
            }
        }
    }
    if (!$hasDynamicPlans && !empty($planTypes)) {
        $defaultType = array_key_first($planTypes) ?: 1;
    }
@endphp

<section id="pricing" x-data="{ type: {{ $defaultType }} }" class="py-24 bg-white border-y border-slate-100 relative overflow-hidden">
    <!-- Ambient Green Glow Effect -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[300px] bg-emerald-100/40 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative">
        <!-- Section Header with Curved Hand-Drawn Doodle Arrows -->
        <div class="max-w-3xl mx-auto text-center mb-14 relative">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-[#065f46] border border-emerald-300/60 mb-3">
                <i class="fas fa-tags text-[11px] text-[#059669]"></i>
                <span>{!! get_option('landing_pricing_badge', 'PRICING') !!}</span>
            </span>

            <div class="relative inline-block max-w-full">
                <!-- Left curly loop hand-drawn doodle arrow -->
                <svg class="absolute -top-3.5 -left-14 sm:-left-20 w-12 sm:w-16 h-10 sm:h-12 text-[#059669] pointer-events-none hidden sm:block overflow-visible" viewBox="0 0 60 40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6,16 C12,4 24,4 22,18 C20,28 36,32 48,24" />
                    <path d="M40,24 L48,24 L45,32" />
                </svg>

                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-3">
                    {{ get_option('landing_pricing_title', 'Simple and transparent pricing') }}
                </h2>

                <!-- Right smooth arc hand-drawn doodle arrow -->
                <svg class="absolute -top-3.5 -right-14 sm:-right-20 w-12 sm:w-16 h-10 sm:h-12 text-[#059669] pointer-events-none hidden sm:block overflow-visible" viewBox="0 0 60 40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M48,8 C42,22 28,30 12,24" />
                    <path d="M20,22 L12,24 L16,32" />
                </svg>
            </div>

            <p class="text-sm md:text-base text-slate-600 font-medium max-w-2xl mx-auto">
                {{ get_option('landing_pricing_subtitle', 'Choose the plan that fits your business needs. Upgrade or downgrade anytime.') }}
            </p>
        </div>

        @if(count($planTypes) > 1)
            <!-- Billing Period Switcher -->
            <div class="flex justify-center mb-14">
                <div class="inline-flex items-center p-1.5 rounded-full bg-[#f0fdf4] border border-emerald-200/80 shadow-xs">
                    @foreach($planTypes as $typeKey => $typeLabel)
                        <button
                            type="button"
                            class="relative px-6 py-2.5 rounded-full text-xs sm:text-sm font-bold transition flex items-center gap-2 cursor-pointer"
                            :class="type == {{ $typeKey }} ? 'bg-[#059669] text-white shadow-md' : 'text-slate-600 hover:text-slate-900'"
                            x-on:click="type = {{ $typeKey }}"
                        >
                            <span>{{ __($typeLabel) }}</span>
                            @if($typeKey == 2)
                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full transition"
                                      :class="type == 2 ? 'bg-white text-[#059669]' : 'bg-emerald-100 text-[#059669]'">
                                    Save 20%
                                </span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        @if($hasDynamicPlans)
            <!-- DYNAMIC PLANS FROM DATABASE -->
            @foreach($planTypes as $typeKey => $typeLabel)
                @php
                    $plans = $pricing[$typeKey] ?? [];
                    $planCount = count($plans);
                @endphp
                <div x-show="type == {{ $typeKey }}"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="grid grid-cols-1 {{ $planCount == 1 ? 'max-w-md mx-auto' : ($planCount == 2 ? 'md:grid-cols-2 max-w-3xl mx-auto' : ($planCount == 3 ? 'md:grid-cols-3 max-w-5xl mx-auto' : 'md:grid-cols-2 lg:grid-cols-4')) }} gap-7 items-stretch">
                    
                    @foreach($plans as $plan)
                        @php
                            $isFeatured = !empty($plan['featured']);
                            $isFree = !empty($plan['free_plan']);
                            $priceVal = (float)($plan['price'] ?? 0);
                            $planUrl = url('auth/signup?plan=' . $plan['id']);
                        @endphp

                        <div class="rounded-[28px] p-7 sm:p-8 flex flex-col justify-between transition-all duration-300 relative {{ $isFeatured ? 'bg-white border-2 border-[#059669] shadow-xl hover:shadow-2xl hover:-translate-y-1' : 'bg-[#f8fdfa] border border-emerald-100/90 shadow-[0_4px_25px_rgba(0,0,0,0.03)] hover:shadow-xl hover:border-emerald-500/40 hover:-translate-y-1' }}">
                            
                            @if($isFeatured)
                                <div class="absolute -top-3.5 right-6 sm:right-8 bg-[#059669] text-white text-[11px] font-extrabold uppercase px-3.5 py-1 rounded-full shadow-md tracking-wider flex items-center gap-1">
                                    <i class="fa-solid fa-star text-[10px]"></i> {{ __('Most Popular') }}
                                </div>
                            @endif

                            <div>
                                <div class="w-12 h-12 rounded-2xl {{ $isFeatured ? 'bg-[#059669] text-white shadow-md shadow-emerald-600/30' : 'bg-[#e6f9f0] text-[#059669] border border-emerald-100/60' }} flex items-center justify-center text-xl mb-5">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>

                                <h3 class="text-2xl font-black text-slate-900 mb-1">{{ $plan['name'] }}</h3>
                                <p class="text-xs text-slate-500 mb-6 min-h-[32px]">{{ $plan['desc'] ?: __('Full WhatsApp marketing and automation features') }}</p>

                                <div class="mb-6 pb-6 border-b border-slate-100">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-4xl sm:text-5xl font-black text-slate-900">
                                            @if($isFree)
                                                ₹0
                                            @else
                                                ₹{{ number_format($priceVal, 0) }}
                                            @endif
                                        </span>
                                        <span class="text-xs font-bold text-slate-400">/{{ strtolower($typeLabel) }}</span>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-400 block mt-1">
                                        {{ $isFree ? __('Free forever plan') : __('Billed ' . strtolower($typeLabel)) }}
                                    </span>
                                </div>

                                <!-- Real Feature Checklist from Plan Permissions -->
                                <ul class="space-y-3.5 mb-8 text-xs sm:text-sm text-slate-700 font-medium">
                                    @php
                                        $shownCount = 0;
                                    @endphp
                                    @foreach($plan['features'] ?? [] as $feat)
                                        @php
                                            $featLabel = $feat['label'] ?? ($feat['feature']['label'] ?? '');
                                            $featDisplay = $feat['display'] ?? null;
                                            $isCheck = !empty($feat['check']);
                                        @endphp
                                        @continue(empty($featLabel))
                                        @continue(is_string($featLabel) && (stripos($featLabel, 'approval') !== false || stripos($featLabel, 'approvals') !== false))
                                        @php
                                            $shownCount++;
                                        @endphp
                                        @if($shownCount <= 8)
                                            <li class="flex items-start gap-2.5">
                                                <span class="w-4 h-4 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                    <i class="fa-solid fa-check"></i>
                                                </span>
                                                <span class="text-slate-700">
                                                    <strong>{{ $featLabel }}</strong>
                                                    @if(!empty($featDisplay) && $featDisplay !== '1' && $featDisplay !== '0')
                                                        : <span class="text-[#059669] font-bold">{{ $featDisplay }}</span>
                                                    @endif
                                                </span>
                                            </li>
                                        @endif
                                    @endforeach

                                    @if($shownCount == 0)
                                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>WhatsApp bulk campaigns</span></li>
                                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>AI Smart & Auto Reply</span></li>
                                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>Chatbot & Contacts flow</span></li>
                                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>Export participants & Analytics</span></li>
                                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>Priority WhatsApp support</span></li>
                                    @endif
                                </ul>
                            </div>

                            @if($isFeatured)
                                <a href="{{ $planUrl }}" class="w-full text-center py-3.5 rounded-2xl text-white font-extrabold text-sm shadow-md shadow-emerald-700/25 transition block hover:opacity-95" style="background: linear-gradient(135deg, #01502f 0%, #036f45 45%, #059669 100%);">
                                    {{ __('Choose Plan') }} <i class="fas fa-arrow-right text-xs ml-1"></i>
                                </a>
                            @elseif($isFree)
                                <a href="{{ $planUrl }}" class="w-full text-center py-3.5 rounded-2xl border-2 border-[#059669] text-[#059669] font-extrabold text-sm hover:bg-emerald-50 transition shadow-xs block">
                                    {{ __('Start for Free') }} <i class="fas fa-arrow-right text-xs ml-1"></i>
                                </a>
                            @else
                                <a href="{{ $planUrl }}" class="w-full text-center py-3.5 rounded-2xl border border-slate-200 text-slate-800 font-extrabold text-sm hover:bg-slate-50 transition shadow-xs block">
                                    {{ __('Get Started') }} <i class="fas fa-arrow-right text-xs ml-1"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        @else
            <!-- FALLBACK CLEAN 4 TIERS WHEN NO DB PLANS EXIST -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
                <!-- Starter -->
                <div class="bg-[#f8fdfa] rounded-[26px] p-7 sm:p-8 border border-emerald-100/80 shadow-[0_4px_25px_rgba(0,0,0,0.03)] flex flex-col justify-between hover:shadow-xl transition duration-300">
                    <div>
                        <div class="w-11 h-11 rounded-2xl bg-[#e6f9f0] text-[#059669] flex items-center justify-center text-lg mb-5 border border-emerald-100/60"><i class="fas fa-camera"></i></div>
                        <h3 class="text-xl font-black text-slate-900 mb-1">{{ get_option('landing_price1_name', 'Starter') }}</h3>
                        <p class="text-xs text-slate-400 mb-5">{{ get_option('landing_price1_desc', 'For individuals and small businesses') }}</p>
                        <div class="mb-6 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-slate-900">{{ get_option('landing_price1_price', '₹1,499') }}</span>
                            <span class="text-xs font-semibold text-slate-400">/month</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-600 font-medium mb-8">
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> 1 WhatsApp number</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> 5,000 messages/month</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> Basic automation</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> Standard support</li>
                        </ul>
                    </div>
                    <a href="{{ url('auth/signup') }}" class="w-full text-center py-3.5 rounded-2xl border border-slate-200 text-slate-800 font-bold text-sm hover:bg-slate-50 transition shadow-xs block">{{ __('Get Started') }}</a>
                </div>

                <!-- Professional -->
                <div class="bg-white rounded-[26px] p-7 sm:p-8 border-2 border-[#059669] shadow-xl flex flex-col justify-between relative hover:shadow-2xl transition duration-300">
                    <div class="absolute -top-3.5 right-6 sm:right-8 bg-[#059669] text-white text-[11px] font-extrabold uppercase px-3.5 py-1 rounded-full shadow-sm tracking-wider">{{ __('Most Popular') }}</div>
                    <div>
                        <div class="w-11 h-11 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-lg mb-5 shadow-sm"><i class="fas fa-shield-halved"></i></div>
                        <h3 class="text-xl font-black text-slate-900 mb-1">{{ get_option('landing_price2_name', 'Professional') }}</h3>
                        <p class="text-xs text-slate-400 mb-5">{{ get_option('landing_price2_desc', 'For growing teams') }}</p>
                        <div class="mb-6 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-slate-900">{{ get_option('landing_price2_price', '₹3,999') }}</span>
                            <span class="text-xs font-semibold text-slate-400">/month</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-600 font-medium mb-8">
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> 3 WhatsApp numbers</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> 25,000 messages/month</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> All modules included</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> Advanced automation</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> Priority support</li>
                        </ul>
                    </div>
                    <a href="{{ url('auth/signup') }}" class="w-full text-center py-3.5 rounded-2xl text-white font-bold text-sm shadow-md shadow-emerald-600/30 transition block hover:opacity-95" style="background: linear-gradient(135deg, #01502f 0%, #036f45 45%, #059669 100%);">{{ __('Get Started') }}</a>
                </div>

                <!-- Business -->
                <div class="bg-[#f8fdfa] rounded-[26px] p-7 sm:p-8 border border-emerald-100/80 shadow-[0_4px_25px_rgba(0,0,0,0.03)] flex flex-col justify-between hover:shadow-xl transition duration-300">
                    <div>
                        <div class="w-11 h-11 rounded-2xl bg-[#fff7ed] text-[#ea580c] flex items-center justify-center text-lg mb-5 border border-orange-100"><i class="fas fa-briefcase"></i></div>
                        <h3 class="text-xl font-black text-slate-900 mb-1">{{ get_option('landing_price3_name', 'Business') }}</h3>
                        <p class="text-xs text-slate-400 mb-5">{{ get_option('landing_price3_desc', 'For established businesses') }}</p>
                        <div class="mb-6 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-slate-900">{{ get_option('landing_price3_price', '₹7,999') }}</span>
                            <span class="text-xs font-semibold text-slate-400">/month</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-600 font-medium mb-8">
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> 10 WhatsApp numbers</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> 100,000 messages/month</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> All modules included</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> Team collaboration</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> Priority support</li>
                        </ul>
                    </div>
                    <a href="{{ url('auth/signup') }}" class="w-full text-center py-3.5 rounded-2xl border border-slate-200 text-slate-800 font-bold text-sm hover:bg-slate-50 transition shadow-xs block">{{ __('Get Started') }}</a>
                </div>

                <!-- Enterprise -->
                <div class="bg-[#f8fdfa] rounded-[26px] p-7 sm:p-8 border border-emerald-100/80 shadow-[0_4px_25px_rgba(0,0,0,0.03)] flex flex-col justify-between hover:shadow-xl transition duration-300">
                    <div>
                        <div class="w-11 h-11 rounded-2xl bg-[#eff6ff] text-[#2563eb] flex items-center justify-center text-lg mb-5 border border-blue-100"><i class="fas fa-crown"></i></div>
                        <h3 class="text-xl font-black text-slate-900 mb-1">{{ get_option('landing_price4_name', 'Enterprise') }}</h3>
                        <p class="text-xs text-slate-400 mb-5">{{ get_option('landing_price4_desc', 'For large organizations') }}</p>
                        <div class="mb-6 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-slate-900">{{ get_option('landing_price4_price', 'Custom') }}</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-600 font-medium mb-8">
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> Unlimited numbers</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> Custom message volume</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> All modules included</li>
                            <li class="flex items-center gap-2.5"><i class="fas fa-check text-[#059669] text-xs"></i> SLA & premium support</li>
                        </ul>
                    </div>
                    <a href="{{ url('contact') }}" class="w-full text-center py-3.5 rounded-2xl bg-[#0f172a] text-white font-bold text-sm hover:bg-slate-800 transition block">{{ __('Contact Sales') }}</a>
                </div>
            </div>
        @endif
    </div>
</section>