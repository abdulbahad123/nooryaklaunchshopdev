@php
    $pricing = \Pricing::plansWithFeatures();
    $planTypes = \Modules\AdminPlans\Facades\Plan::getTypes();
    // Exclude Lifetime plan as requested
    $cleanedTypes = [];
    foreach($planTypes as $tk => $tl) {
        if (is_string($tl) && strtolower(trim($tl)) === 'lifetime') continue;
        if ($tk == 3) continue;
        $cleanedTypes[$tk] = $tl;
    }
    $planTypes = $cleanedTypes;

    $hasDynamicPlans = false;
    foreach($planTypes as $tk => $tl) {
        if (!empty($pricing[$tk])) {
            $hasDynamicPlans = true;
            break;
        }
    }
    if (!$hasDynamicPlans && class_exists('\Modules\AdminPlans\Services\PlanService')) {
        \Modules\AdminPlans\Services\PlanService::seedDefaultPlans();
        $pricing = \Pricing::plansWithFeatures();
        foreach($planTypes as $tk => $tl) {
            if (!empty($pricing[$tk])) {
                $hasDynamicPlans = true;
                break;
            }
        }
    }
    $defaultType = 1;
    foreach($planTypes as $tk => $tl) {
        if (!empty($pricing[$tk])) {
            $defaultType = $tk;
            break;
        }
    }

    $buildDynamicPlanFeatures = function($plan) {
        $featuresList = [];
        $addedKeys = [];

        // 1. Process structured features built by PricingService
        if (!empty($plan['features']) && is_array($plan['features'])) {
            foreach ($plan['features'] as $feat) {
                $isCheck = !empty($feat['check']);
                $label = trim($feat['label'] ?? ($feat['feature']['label'] ?? ''));
                if (!$isCheck || empty($label)) {
                    continue;
                }
                if (stripos($label, 'approval') !== false) {
                    continue;
                }

                $key = strtolower(trim($feat['key'] ?? $label));
                $display = $feat['display'] ?? null;
                $displayVal = (!empty($display) && $display !== '1' && $display !== '0') ? $display : null;

                if (!in_array($key, $addedKeys, true)) {
                    $featuresList[] = [
                        'label' => $label,
                        'display' => $displayVal,
                    ];
                    $addedKeys[] = $key;
                }

                // Subfeatures under WhatsApp Unofficial, Files, etc.
                if (!empty($feat['subfeature']) && is_array($feat['subfeature'])) {
                    foreach ($feat['subfeature'] as $subGroup) {
                        $items = $subGroup['items'] ?? (is_array($subGroup) ? $subGroup : []);
                        foreach ($items as $sub) {
                            $subCheck = !empty($sub['check']);
                            $subLabel = trim($sub['label'] ?? ($sub['feature']['label'] ?? ''));
                            if (!$subCheck || empty($subLabel)) {
                                continue;
                            }
                            if (stripos($subLabel, 'approval') !== false) {
                                continue;
                            }

                            $subKey = strtolower(trim($sub['key'] ?? $subLabel));
                            $subDisplay = $sub['display'] ?? null;
                            $subDisplayVal = (!empty($subDisplay) && $subDisplay !== '1' && $subDisplay !== '0') ? $subDisplay : null;

                            if (!in_array($subKey, $addedKeys, true)) {
                                $featuresList[] = [
                                    'label' => $subLabel,
                                    'display' => $subDisplayVal,
                                ];
                                $addedKeys[] = $subKey;
                            }
                        }
                    }
                }
            }
        }

        // 2. Parse raw permissions array from Admin Plan settings to capture configured limits & permissions
        $rawPerms = $plan['permissions'] ?? [];
        if (!empty($rawPerms) && is_array($rawPerms)) {
            $permMap = [];
            foreach ($rawPerms as $p) {
                if (isset($p['key'])) {
                    $permMap[$p['key']] = [
                        'value' => $p['value'] ?? null,
                        'label' => $p['label'] ?? null,
                    ];
                }
            }

            // Real limits configured in Admin Dashboard
            $limitDefinitions = [
                'credits'                         => 'Monthly messages',
                'whatsapp_message_per_month'      => 'Monthly messages',
                'whatsapp_chatbot_item_limit'     => 'Chatbot item limit',
                'whatsapp_bulk_max_contact_group' => 'Max contact groups',
                'whatsapp_bulk_max_phone_numbers' => 'Max numbers per group',
                'max_storage_size'                => 'Max storage size',
                'max_file_size'                   => 'Max file size',
            ];

            foreach ($limitDefinitions as $permKey => $defaultLabel) {
                if (!isset($permMap[$permKey])) {
                    continue;
                }
                $val = $permMap[$permKey]['value'];
                $lbl = $permMap[$permKey]['label'] ?: $defaultLabel;
                if ($val === '-1' || $val === -1) {
                    $formattedVal = __('Unlimited');
                } elseif (is_numeric($val) && (float)$val > 0) {
                    $formattedVal = number_format((float)$val, 0) . (str_contains($permKey, 'size') ? ' MB' : '');
                } else {
                    continue;
                }

                $k = strtolower(trim($permKey));
                if (!in_array($k, $addedKeys, true)) {
                    $featuresList[] = [
                        'label' => $lbl,
                        'display' => $formattedVal,
                    ];
                    $addedKeys[] = $k;
                }
            }
        }

        return $featuresList;
    };
@endphp

<style>
@keyframes waFloatSlow {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-7px); }
}
@keyframes waFloatReverse {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(7px); }
}
.wa-float-badge-1 {
    animation: waFloatSlow 4s ease-in-out infinite;
}
.wa-float-badge-2 {
    animation: waFloatReverse 5s ease-in-out infinite;
}
.wa-float-badge-3 {
    animation: waFloatSlow 4.5s ease-in-out infinite 1s;
}
</style>

<section id="pricing" x-data="{ type: {{ $defaultType }} }" class="py-20 sm:py-24 bg-white border-y border-slate-100 relative overflow-hidden">
    <!-- Ambient Green Glow Effect -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[300px] bg-emerald-100/40 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative">
        <!-- Section Header with Curved Hand-Drawn Doodle Arrows -->
        <div class="max-w-3xl mx-auto text-center mb-12 relative">
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
            <div class="flex justify-center mb-12">
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
                    if (empty($plans)) {
                        $fallbackName = 'WhatsApp Bulk Plan';
                        $fallbackPrice = ($typeKey == 2) ? 2999 : 350;
                        $sourcePlan = !empty($pricing[1]) ? reset($pricing[1]) : (!empty($pricing) ? reset(reset($pricing)) : null);
                        if ($sourcePlan) {
                            $fallbackName = (!empty($sourcePlan['name']) && stripos($sourcePlan['name'], 'Waziper') === false) ? $sourcePlan['name'] : 'WhatsApp Bulk Plan';
                            $sourceFeatures = $sourcePlan['features'] ?? [];
                            $sourceDesc = $sourcePlan['desc'] ?? 'Full WhatsApp marketing and automation features';
                            $sourceId = $sourcePlan['id_secure'] ?: ($sourcePlan['id'] ?? 1);
                        } else {
                            $sourceFeatures = [];
                            $sourceDesc = 'Full WhatsApp marketing and automation features';
                            $sourceId = 'default_plan';
                        }

                        $plans = [
                            [
                                'id'          => 1,
                                'id_secure'   => $sourceId,
                                'name'        => $fallbackName,
                                'desc'        => $sourceDesc,
                                'price'       => $fallbackPrice,
                                'free_plan'   => 0,
                                'featured'    => 1,
                                'features'    => $sourceFeatures,
                            ]
                        ];
                    }
                    $planCount = count($plans);
                @endphp
                <div x-show="type == {{ $typeKey }}"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="w-full">
                    
                    @if($planCount <= 1)
                        {{-- Task 3: Balanced 3-Column Layout (Left: WhatsApp Overview & Capabilities, Center: Plan Card with Monthly/Yearly toggle, Right: Plan Advantages & ROI) --}}
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-7 items-stretch max-w-7xl mx-auto">
                            
                            {{-- LEFT PANEL: WhatsApp Engine Capabilities, Highlights & Metrics (4 cols) --}}
                            <div class="col-span-12 lg:col-span-4 flex flex-col justify-between bg-white/95 rounded-[28px] p-6 sm:p-7 border border-emerald-100 shadow-[0_4px_24px_rgba(0,0,0,0.04)] relative">
                                <div>
                                    {{-- Pre-heading Pill --}}
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-[#065f46] border border-emerald-200/80 mb-3.5 w-fit shadow-xs">
                                        <span>{!! get_option('landing_pricing_left_badge', '✨ All-in-One WhatsApp Engine') !!}</span>
                                    </div>

                                    {{-- Main Heading --}}
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug mb-3">
                                        {{ get_option('landing_pricing_left_title', 'Autonomous WhatsApp Marketing & AI Automation Suite') }}
                                    </h3>

                                    {{-- Description Paragraph --}}
                                    <p class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed mb-5">
                                        {{ get_option('landing_pricing_left_desc', 'Broadcast targeted bulk campaigns, automate conversational sales with AI chatbots, and engage your customers 24/7 with zero risk of bans.') }}
                                    </p>

                                    {{-- Dynamic Floating Elements / Badges --}}
                                    @php
                                        $float1Text = get_option('landing_pricing_float1', '⚡ 99.8% msg Delivery');
                                        $float1Text = str_ireplace('99.8% High Inbox Delivery', '99.8% msg Delivery', (string)$float1Text);

                                        $float2Text = get_option('landing_pricing_float2', '🤖 24/7 support');
                                        $float2Text = str_ireplace('24/7 AI Smart Bot Active', '24/7 support', (string)$float2Text);
                                    @endphp
                                    <div class="flex flex-wrap gap-2 mb-5">
                                        <div class="wa-float-badge-1 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-emerald-200 shadow-xs text-[11px] font-bold text-emerald-800">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                            <span>{!! $float1Text !!}</span>
                                        </div>
                                        <div class="wa-float-badge-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-blue-200 shadow-xs text-[11px] font-bold text-blue-800">
                                            <i class="fa-solid fa-headset text-blue-600 text-[11px]"></i>
                                            <span>{!! $float2Text !!}</span>
                                        </div>
                                    </div>

                                    {{-- Key Capability Highlights --}}
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm text-slate-700 font-medium">
                                                {!! get_option('landing_pricing_left_feat1', '<strong>Anti-Ban Protection & Safe Delays:</strong> Smart interval scheduling keeps your numbers safe.') !!}
                                            </span>
                                        </div>
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm text-slate-700 font-medium">
                                                {!! get_option('landing_pricing_left_feat2', '<strong>AI Smart Responder & Chatbot Flows:</strong> Instant contextual replies to customer queries.') !!}
                                            </span>
                                        </div>
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm text-slate-700 font-medium">
                                                {!! get_option('landing_pricing_left_feat3', '<strong>Broadcast & Bulk Campaigns:</strong> Send rich media, buttons, and documents effortlessly.') !!}
                                            </span>
                                        </div>
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm text-slate-700 font-medium">
                                                {!! get_option('landing_pricing_left_feat4', '<strong>Detailed Analytics & Contact Management:</strong> Track delivery rates and export participants.') !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Trust & Performance Metrics --}}
                                <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100">
                                    <div class="p-2.5 bg-emerald-50/60 rounded-xl border border-emerald-100/80 text-center">
                                        <div class="text-base sm:text-lg font-black text-[#059669]">{{ get_option('landing_pricing_stat1_val', '99.8%') }}</div>
                                        <div class="text-[10px] font-bold text-slate-500 mt-0.5">{{ get_option('landing_pricing_stat1_lbl', 'Inbox') }}</div>
                                    </div>
                                    <div class="p-2.5 bg-emerald-50/60 rounded-xl border border-emerald-100/80 text-center">
                                        <div class="text-base sm:text-lg font-black text-slate-900">{{ get_option('landing_pricing_stat2_val', '10M+') }}</div>
                                        <div class="text-[10px] font-bold text-slate-500 mt-0.5">{{ get_option('landing_pricing_stat2_lbl', 'Messages') }}</div>
                                    </div>
                                    <div class="p-2.5 bg-emerald-50/60 rounded-xl border border-emerald-100/80 text-center">
                                        <div class="text-base sm:text-lg font-black text-slate-900">{{ get_option('landing_pricing_stat3_val', '24/7') }}</div>
                                        <div class="text-[10px] font-bold text-slate-500 mt-0.5">{{ get_option('landing_pricing_stat3_lbl', 'Auto Bot') }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- CENTER PANEL: The Active Plan Card with Floating Badge (4 cols) --}}
                            <div class="col-span-12 lg:col-span-4 flex flex-col relative">
                                @foreach($plans as $plan)
                                    @php
                                        $isFeatured = !empty($plan['featured']) || true;
                                        $rawPlanName = $plan['name'] ?? '';
                                        // Default fallback name is WhatsApp Bulk Plan if empty or original Waziper Starter
                                        $planName = $rawPlanName;
                                        if (empty($planName) || stripos($planName, 'Waziper Starter') !== false || stripos($planName, 'Waziper') !== false) {
                                            $planName = 'WhatsApp Bulk Plan';
                                        }

                                        // Dynamic editable price with defaults ₹350/mo and ₹2999/yr across all whitelabels
                                        $rawPrice = $plan['price'] ?? null;
                                        if ($rawPrice === null || $rawPrice === '' || (float)$rawPrice <= 0 || (float)$rawPrice == 99) {
                                            $priceVal = ($typeKey == 2) ? 2999 : 350;
                                            $isFree = false;
                                        } else {
                                            $priceVal = (float)$rawPrice;
                                            $isFree = !empty($plan['free_plan']) && $priceVal <= 0;
                                        }

                                        $planCycle = ($typeKey == 2) ? 'yearly' : 'monthly';
                                        $planIdentifier = $plan['id_secure'] ?: $plan['id'];
                                        $planUrl = url('auth/signup?plan=' . $planIdentifier . '&cycle=' . $planCycle);
                                    @endphp

                                    <div class="rounded-[28px] p-6 sm:p-7 flex flex-col justify-between transition-all duration-300 relative bg-white border-2 border-[#059669] shadow-2xl hover:-translate-y-1 h-full">
                                        
                                        {{-- Top Most Popular Floating Pill --}}
                                        <div class="absolute -top-3.5 right-6 sm:right-8 bg-[#059669] text-white text-[11px] font-extrabold uppercase px-3.5 py-1 rounded-full shadow-md tracking-wider flex items-center gap-1.5">
                                            <i class="fa-solid fa-star text-[10px]"></i> <span>{{ __('Most Popular') }}</span>
                                        </div>

                                        <div>
                                            <div class="w-11 h-11 rounded-2xl bg-[#059669] text-white shadow-md shadow-emerald-600/30 flex items-center justify-center text-lg mb-4">
                                                <i class="fa-solid fa-shield-halved"></i>
                                            </div>

                                            <h3 class="text-2xl font-black text-slate-900 mb-1">{{ $planName }}</h3>
                                            <p class="text-xs text-slate-500 mb-5 min-h-[30px]">{{ $plan['desc'] ?: __('Full WhatsApp marketing and automation features') }}</p>

                                            <div class="mb-5 pb-5 border-b border-slate-100">
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

                                            @php
                                                $dynamicPlanFeatures = $buildDynamicPlanFeatures($plan);
                                            @endphp
                                            <!-- Dynamic Feature Checklist from Admin Plan Permissions -->
                                            <ul class="space-y-3 mb-7 text-xs sm:text-sm text-slate-700 font-medium">
                                                @forelse(array_slice($dynamicPlanFeatures, 0, 10) as $featItem)
                                                    <li class="flex items-start gap-2">
                                                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                            <i class="fa-solid fa-check"></i>
                                                        </span>
                                                        <span class="text-slate-700">
                                                            <strong>{{ $featItem['label'] }}</strong>
                                                            @if(!empty($featItem['display']))
                                                                : <span class="text-[#059669] font-bold">{{ $featItem['display'] }}</span>
                                                            @endif
                                                        </span>
                                                    </li>
                                                @empty
                                                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>WhatsApp bulk campaigns</span></li>
                                                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>AI Smart & Auto Reply</span></li>
                                                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>Chatbot & Contacts flow</span></li>
                                                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>Export participants & Analytics</span></li>
                                                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>Priority WhatsApp support</span></li>
                                                @endforelse
                                            </ul>
                                        </div>

                                        <div>
                                            <a href="{{ $planUrl }}" class="w-full text-center py-3.5 rounded-2xl text-white font-extrabold text-sm shadow-md shadow-emerald-700/25 transition block hover:opacity-95" style="background: linear-gradient(135deg, #01502f 0%, #036f45 45%, #059669 100%);">
                                                {{ __('Choose Plan') }} <i class="fas fa-arrow-right text-xs ml-1"></i>
                                            </a>
                                            <div class="text-center text-[11px] text-slate-400 font-semibold mt-3">
                                                ⚡ Instant Activation • Safe Delays • No Setup Fee
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                            {{-- RIGHT PANEL: Plan Advantages, Guarantees & ROI (4 cols) --}}
                            <div class="col-span-12 lg:col-span-4 flex flex-col justify-between bg-white/95 rounded-[28px] p-6 sm:p-7 border border-emerald-100 shadow-[0_4px_24px_rgba(0,0,0,0.04)] relative">
                                <div>
                                    {{-- Pre-heading Pill --}}
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-[#065f46] border border-emerald-200/80 mb-3.5 w-fit shadow-xs">
                                        <span>{!! get_option('landing_pricing_right_badge', '✨ PLAN ADVANTAGES & ROI') !!}</span>
                                    </div>

                                    {{-- Main Heading --}}
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug mb-3">
                                        {!! get_option('landing_pricing_right_title', 'Scale Conversations With Zero Risk') !!}
                                    </h3>

                                    {{-- Description Paragraph --}}
                                    <p class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed mb-5">
                                        {!! get_option('landing_pricing_right_desc', 'Everything you need to automate communications, boost customer retention, and close more sales with WhatsApp.') !!}
                                    </p>

                                    {{-- Dynamic Floating Badges --}}
                                    <div class="flex flex-wrap gap-2 mb-5">
                                        <div class="wa-float-badge-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-amber-200 shadow-xs text-[11px] font-bold text-amber-800">
                                            <i class="fa-solid fa-shield-halved text-amber-600 text-[11px]"></i>
                                            <span>{!! get_option('landing_pricing_float3', '🔒 Anti-Ban Protection Shield') !!}</span>
                                        </div>
                                        <div class="wa-float-badge-1 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-emerald-200 shadow-xs text-[11px] font-bold text-emerald-800">
                                            <i class="fa-solid fa-chart-line text-emerald-600 text-[11px]"></i>
                                            <span>{!! get_option('landing_pricing_float4', '📈 5x Higher Open Rates') !!}</span>
                                        </div>
                                        @php
                                            $float5Text = get_option('landing_pricing_float5', '🎯 WhatsApp Bulk Campaigns');
                                            $float5Text = str_ireplace('Smart Funnels Active', 'WhatsApp Bulk Campaigns', (string)$float5Text);
                                        @endphp
                                        <div class="wa-float-badge-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-blue-200 shadow-xs text-[11px] font-bold text-blue-800">
                                            <i class="fa-solid fa-bullhorn text-blue-600 text-[11px]"></i>
                                            <span>{!! $float5Text !!}</span>
                                        </div>
                                    </div>

                                    {{-- Advantages List --}}
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm text-slate-700 font-medium">
                                                {!! get_option('landing_pricing_right_feat1', '<strong>⚡ Instant Onboarding:</strong> Get set up and broadcasting within 2 minutes.') !!}
                                            </span>
                                        </div>
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm text-slate-700 font-medium">
                                                {!! get_option('landing_pricing_right_feat2', '<strong>🔒 Enterprise Safe Protocol:</strong> Humanized pacing prevents account flagging.') !!}
                                            </span>
                                        </div>
                                        @php
                                            $rightFeat3Text = get_option('landing_pricing_right_feat3', '<strong>🔄 API & Webhook Hooks:</strong> Integrate with your website.');
                                            $rightFeat3Text = str_ireplace(
                                                ['Integrate with your CRM, store, or zap flows.', 'Integrate with your CRM, store, or zap flows'],
                                                'Integrate with your website.',
                                                (string)$rightFeat3Text
                                            );
                                        @endphp
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm text-slate-700 font-medium">
                                                {!! $rightFeat3Text !!}
                                            </span>
                                        </div>
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm text-slate-700 font-medium">
                                                {!! get_option('landing_pricing_right_feat4', '<strong>📞 24/7 Priority Support:</strong> Direct WhatsApp and ticket support whenever you need.') !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Trust Guarantee Note --}}
                                @php
                                    $rawTrust = get_option('landing_pricing_right_trust', '100% Risk-Free Guarantee');
                                    $cleanTrust = str_ireplace(['• Cancel Anytime', '• Cancel or Upgrade Anytime', 'Cancel Anytime', '•'], '', (string)$rawTrust);
                                    $cleanTrust = trim($cleanTrust);
                                    if (empty($cleanTrust)) {
                                        $cleanTrust = '100% Risk-Free Guarantee';
                                    }
                                @endphp
                                <div class="p-3 bg-emerald-50/80 rounded-2xl border border-emerald-200/80 text-center mt-2">
                                    <div class="text-xs font-bold text-emerald-900 flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-shield-halved text-emerald-600 text-sm"></i>
                                        <span>{!! $cleanTrust !!}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @else
                        {{-- Multi-Plan Grid (2 or more plans) --}}
                        <div class="grid grid-cols-1 {{ $planCount == 2 ? 'md:grid-cols-2 max-w-3xl mx-auto' : ($planCount == 3 ? 'md:grid-cols-3 max-w-5xl mx-auto' : 'md:grid-cols-2 lg:grid-cols-4') }} gap-7 items-stretch">
                            @foreach($plans as $plan)
                                @php
                                    $isFeatured = !empty($plan['featured']);
                                    $rawPlanName = $plan['name'] ?? '';
                                    $planName = $rawPlanName;
                                    if (empty($planName) || stripos($planName, 'Waziper Starter') !== false || stripos($planName, 'Waziper') !== false) {
                                        $planName = 'WhatsApp Bulk Plan';
                                    }
                                    $rawPrice = $plan['price'] ?? null;
                                    if ($rawPrice === null || $rawPrice === '' || (float)$rawPrice <= 0 || (float)$rawPrice == 99) {
                                        $priceVal = ($typeKey == 2) ? 2999 : 350;
                                        $isFree = false;
                                    } else {
                                        $priceVal = (float)$rawPrice;
                                        $isFree = !empty($plan['free_plan']) && $priceVal <= 0;
                                    }
                                    $planCycle = ($typeKey == 2) ? 'yearly' : 'monthly';
                                    $planIdentifier = $plan['id_secure'] ?: $plan['id'];
                                    $planUrl = url('auth/signup?plan=' . $planIdentifier . '&cycle=' . $planCycle);
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

                                        <h3 class="text-2xl font-black text-slate-900 mb-1">{{ $planName }}</h3>
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

                                        @php
                                            $dynamicPlanFeaturesGrid = $buildDynamicPlanFeatures($plan);
                                        @endphp
                                        <!-- Dynamic Feature Checklist from Admin Plan Permissions -->
                                        <ul class="space-y-3.5 mb-8 text-xs sm:text-sm text-slate-700 font-medium">
                                            @forelse(array_slice($dynamicPlanFeaturesGrid, 0, 10) as $featItem)
                                                <li class="flex items-start gap-2.5">
                                                    <span class="w-4 h-4 rounded-full bg-emerald-100 text-[#059669] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                                        <i class="fa-solid fa-check"></i>
                                                    </span>
                                                    <span class="text-slate-700">
                                                        <strong>{{ $featItem['label'] }}</strong>
                                                        @if(!empty($featItem['display']))
                                                            : <span class="text-[#059669] font-bold">{{ $featItem['display'] }}</span>
                                                        @endif
                                                    </span>
                                                </li>
                                            @empty
                                                <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>WhatsApp bulk campaigns</span></li>
                                                <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>AI Smart & Auto Reply</span></li>
                                                <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>Chatbot & Contacts flow</span></li>
                                                <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>Export participants & Analytics</span></li>
                                                <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-[#059669] text-xs"></i> <span>Priority WhatsApp support</span></li>
                                            @endforelse
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
                    @endif

                </div>
            @endforeach
        @else
            <div class="text-center py-12 px-4 bg-emerald-50/50 rounded-3xl border border-emerald-100 max-w-xl mx-auto">
                <i class="fa-solid fa-layer-group text-3xl text-emerald-600 mb-3"></i>
                <h4 class="text-lg font-bold text-slate-900 mb-1">{{ __('Subscription Plans') }}</h4>
                <p class="text-xs text-slate-500 mb-4">{{ __('Plans are being configured in Plan Management. Please check back shortly or get started with a free account.') }}</p>
                <a href="{{ url('auth/signup') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-white font-bold text-xs shadow-sm" style="background: linear-gradient(135deg, #01502f 0%, #036f45 45%, #059669 100%);">
                    {{ __('Sign Up Now') }} <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        @endif
    </div>
</section>
