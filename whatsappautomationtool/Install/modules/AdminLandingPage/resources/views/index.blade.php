@extends('layouts.app')

@section('sub_header')
    <x-sub-header
        title="{{ __('Landing Page Settings') }}"
        description="{{ __('Manage and customize every section of your public WhatsApp Bulk marketing page with instant live updates') }}"
    >
    </x-sub-header>
@endsection

@section('content')

<div class="container max-w-1000 pb-5">
    <form class="actionForm" action="{{ route('admin.landing-page.save') }}" method="POST">
        @csrf
@php
    $adminHeroTitle = get_option('landing_hero_title', "WhatsApp operations,<br><span class=\"text-[#059669]\">campaigns,</span> and<br>automation");
    $adminHeroTitle = str_replace(
        ['#ff5500', '#f97316', 'text-orange-500', 'color: #ff5500', 'color:#ff5500', 'color: #f97316', 'color:#f97316'],
        ['#059669', '#059669', 'text-[#059669]', 'color: #059669', 'color:#059669', 'color: #059669', 'color:#059669'],
        $adminHeroTitle
    );

    $adminStackTitle = get_option('landing_stack_title', 'Whatsappbulk <span class="text-[#059669]">Workspace</span>');
    $adminStackTitle = str_replace(
        ['#ff5500', '#f97316', 'text-orange-500', 'color: #ff5500', 'color:#ff5500', 'color: #f97316', 'color:#f97316'],
        ['#059669', '#059669', 'text-[#059669]', 'color: #059669', 'color:#059669', 'color: #059669', 'color:#059669'],
        $adminStackTitle
    );

    $adminStackSubtitle = get_option('landing_stack_subtitle', 'Everything you need to run WhatsApp at scale');
    if ($adminStackSubtitle === 'Product overview' || empty($adminStackSubtitle)) {
        $adminStackSubtitle = 'Everything you need to run WhatsApp at scale';
    }
@endphp

        {{-- Nav Tabs --}}
        <ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 border gap-2 shadow-xs" id="landingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-6 fs-13" id="tab-hero" data-bs-toggle="pill" data-bs-target="#content-hero" type="button" role="tab">
                    <i class="fa-light fa-sparkles me-1 text-success"></i> {{ __('Hero & Stats') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-stack" data-bs-toggle="pill" data-bs-target="#content-stack" type="button" role="tab">
                    <i class="fa-light fa-layer-group me-1 text-primary"></i> {{ __('Live Module Stack') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-modules" data-bs-toggle="pill" data-bs-target="#content-modules" type="button" role="tab">
                    <i class="fa-light fa-grid-2 me-1 text-warning"></i> {{ __('8 Product Modules') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-pricing" data-bs-toggle="pill" data-bs-target="#content-pricing" type="button" role="tab">
                    <i class="fa-light fa-tags me-1 text-info"></i> {{ __('Pricing (4 Tiers)') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-testi" data-bs-toggle="pill" data-bs-target="#content-testi" type="button" role="tab">
                    <i class="fa-light fa-comments me-1 text-danger"></i> {{ __('Testimonials') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-faqs" data-bs-toggle="pill" data-bs-target="#content-faqs" type="button" role="tab">
                    <i class="fa-light fa-circle-question me-1 text-secondary"></i> {{ __('FAQs') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-cta" data-bs-toggle="pill" data-bs-target="#content-cta" type="button" role="tab">
                    <i class="fa-light fa-bullhorn me-1 text-success"></i> {{ __('Bottom CTA & Footer') }}
                </button>
            </li>
        </ul>

        {{-- Tab Content Panes --}}
        <div class="tab-content" id="landingTabsContent">
            
            {{-- TAB 1: HERO & METRICS --}}
            <div class="tab-pane fade show active" id="content-hero" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-heading me-1 text-success"></i> {{ __('Hero Section Content') }}</div>
                    </div>
                    <div class="card-body">
@php
    $adminHeroBadge = get_option('landing_hero_badge', 'ALL-IN-ONE WHATSAPP MARKETING TOOL');
    $adminHeroBadge = trim(strip_tags((string)$adminHeroBadge));
    $adminHeroBadge = rtrim($adminHeroBadge, '<> ');
    if (preg_match('/toolk$/i', $adminHeroBadge) || preg_match('/tool<$/i', $adminHeroBadge)) {
        $adminHeroBadge = preg_replace('/tool[k<]$/i', 'TOOL', $adminHeroBadge);
    }
    if (empty($adminHeroBadge)) {
        $adminHeroBadge = 'ALL-IN-ONE WHATSAPP MARKETING TOOL';
    }
@endphp
                        <div class="mb-3">
                            <label class="form-label fw-6 fs-13">{{ __('Top Pill Badge') }}</label>
                            <input type="text" class="form-control" name="landing_hero_badge" value="{{ $adminHeroBadge }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-6 fs-13">{{ __('Main Headline (Supports HTML span for green highlight)') }}</label>
                            <textarea class="form-control" name="landing_hero_title" rows="2">{{ $adminHeroTitle }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-6 fs-13">{{ __('Bold Subtitle') }}</label>
                            <input type="text" class="form-control" name="landing_hero_subtitle_bold" value="{{ get_option('landing_hero_subtitle_bold', 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-6 fs-13">{{ __('Descriptive Paragraph') }}</label>
                            <textarea class="form-control" name="landing_hero_subtitle_desc" rows="2">{{ get_option('landing_hero_subtitle_desc', 'Built around the modules teams actually use every day: bulk outreach, smart replies, auto replies, chatbots, contacts, participant export, profile lookups, and reporting.') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Primary CTA Button Text') }}</label>
                                <input type="text" class="form-control" name="landing_hero_btn1_text" value="{{ get_option('landing_hero_btn1_text', 'Create your workspace') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Primary CTA Button URL') }}</label>
                                <input type="text" class="form-control" name="landing_hero_btn1_url" value="{{ get_option('landing_hero_btn1_url', 'auth/signup') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Secondary CTA Button Text') }}</label>
                                <input type="text" class="form-control" name="landing_hero_btn2_text" value="{{ get_option('landing_hero_btn2_text', 'Explore modules') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Secondary CTA Button URL') }}</label>
                                <input type="text" class="form-control" name="landing_hero_btn2_url" value="{{ get_option('landing_hero_btn2_url', '#modules') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Metric Highlights --}}
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-chart-line me-1 text-success"></i> {{ __('3 Hero Metric Cards') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Card 1 Icon (FontAwesome)') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_stat1_icon" value="{{ get_option('landing_stat1_icon', 'fas fa-cube') }}">
                                <label class="form-label fw-6 fs-12">{{ __('Card 1 Number') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_stat1_number" value="{{ get_option('landing_stat1_number', '8') }}">
                                <label class="form-label fw-6 fs-12">{{ __('Card 1 Label') }}</label>
                                <input type="text" class="form-control" name="landing_stat1_label" value="{{ get_option('landing_stat1_label', 'Operational modules included') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Card 2 Icon (FontAwesome)') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_stat2_icon" value="{{ get_option('landing_stat2_icon', 'fas fa-link') }}">
                                <label class="form-label fw-6 fs-12">{{ __('Card 2 Number') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_stat2_number" value="{{ get_option('landing_stat2_number', '1') }}">
                                <label class="form-label fw-6 fs-12">{{ __('Card 2 Label') }}</label>
                                <input type="text" class="form-control" name="landing_stat2_label" value="{{ get_option('landing_stat2_label', 'Unified WhatsApp workflow') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Card 3 Icon (FontAwesome)') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_stat3_icon" value="{{ get_option('landing_stat3_icon', 'fas fa-bolt') }}">
                                <label class="form-label fw-6 fs-12">{{ __('Card 3 Number') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_stat3_number" value="{{ get_option('landing_stat3_number', '24/7') }}">
                                <label class="form-label fw-6 fs-12">{{ __('Card 3 Label') }}</label>
                                <input type="text" class="form-control" name="landing_stat3_label" value="{{ get_option('landing_stat3_label', 'Automation & reply coverage') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dashboard & Phone Preview Controls --}}
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-display me-1 text-success"></i> {{ __('Dashboard & Smartphone Live Preview Controls') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Dashboard User Display') }}</label>
                                <input type="text" class="form-control" name="landing_dash_user" value="{{ get_option('landing_dash_user', 'HI, Admin') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Performance Chart Title') }}</label>
                                <input type="text" class="form-control" name="landing_dash_chart_title" value="{{ get_option('landing_dash_chart_title', 'Message Performance') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Total Messages Value') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_dash_m1_val" value="{{ get_option('landing_dash_m1_val', '125,890') }}">
                                <input type="text" class="form-control text-muted fs-11" name="landing_dash_m1_trend" value="{{ get_option('landing_dash_m1_trend', '▲ 20.4%') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Delivered Value') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_dash_m2_val" value="{{ get_option('landing_dash_m2_val', '118,420') }}">
                                <input type="text" class="form-control text-muted fs-11" name="landing_dash_m2_trend" value="{{ get_option('landing_dash_m2_trend', '▲ 16.1%') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Replied Value') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_dash_m3_val" value="{{ get_option('landing_dash_m3_val', '28,460') }}">
                                <input type="text" class="form-control text-muted fs-11" name="landing_dash_m3_trend" value="{{ get_option('landing_dash_m3_trend', '▲ 14.2%') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Active Contacts Value') }}</label>
                                <input type="text" class="form-control mb-1" name="landing_dash_m4_val" value="{{ get_option('landing_dash_m4_val', '96,350') }}">
                                <input type="text" class="form-control text-muted fs-11" name="landing_dash_m4_trend" value="{{ get_option('landing_dash_m4_trend', '▲ 12.6%') }}">
                            </div>
                        </div>
                        <div class="row border-top pt-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Smartphone Preview Heading') }}</label>
                                <input type="text" class="form-control" name="landing_phone_title" value="{{ get_option('landing_phone_title', 'Campaign Overview') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-12">{{ __('Smartphone Performance Percentage') }}</label>
                                <input type="text" class="form-control" name="landing_phone_percent" value="{{ get_option('landing_phone_percent', '95.8%') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: LIVE MODULE STACK (4 Cards) --}}
            <div class="tab-pane fade" id="content-stack" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-layer-group me-1 text-primary"></i> {{ __('Section Headings') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Badge') }}</label>
                                <input type="text" class="form-control" name="landing_stack_badge" value="{{ get_option('landing_stack_badge', 'LIVE MODULE STACK') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Title') }}</label>
                                <input type="text" class="form-control" name="landing_stack_title" value="{{ $adminStackTitle }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Subtitle') }}</label>
                                <input type="text" class="form-control" name="landing_stack_subtitle" value="{{ $adminStackSubtitle }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @for($i = 1; $i <= 4; $i++)
                        @php
                            $tags = [1 => 'CAMPAIGN CONTROL', 2 => 'INBOUND HANDLING', 3 => 'LEAD FLOW', 4 => 'VISIBILITY'];
                            $titles = [1 => 'Bulk Campaigns', 2 => 'AI + Auto Reply', 3 => 'Chatbot + Contacts', 4 => 'Reports + Export'];
                            $descs = [
                                1 => 'Segment contacts, send at scale, and track response flow.',
                                2 => 'Combine automation with faster human response quality.',
                                3 => 'Capture, qualify, and manage audience data in one place.',
                                4 => 'Export participants and measure what the team actually ships.'
                            ];
                        @endphp
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-none border h-100">
                                <div class="card-header bg-light py-2">
                                    <span class="fw-bold fs-13">Card {{ $i }}: {{ $titles[$i] }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Tag') }}</label>
                                        <input type="text" class="form-control" name="landing_stack{{ $i }}_tag" value="{{ get_option('landing_stack'.$i.'_tag', $tags[$i]) }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Title') }}</label>
                                        <input type="text" class="form-control" name="landing_stack{{ $i }}_title" value="{{ get_option('landing_stack'.$i.'_title', $titles[$i]) }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Description') }}</label>
                                        <textarea class="form-control" name="landing_stack{{ $i }}_desc" rows="2">{{ get_option('landing_stack'.$i.'_desc', $descs[$i]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- TAB 3: 8 PRODUCT MODULES --}}
            <div class="tab-pane fade" id="content-modules" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-cubes me-1 text-warning"></i> {{ __('Product Modules Section Headings') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Badge') }}</label>
                                <input type="text" class="form-control" name="landing_modules_badge" value="{{ get_option('landing_modules_badge', 'PRODUCT MODULES') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Title') }}</label>
                                <input type="text" class="form-control" name="landing_modules_title" value="{{ get_option('landing_modules_title', 'Built from the <span class=\"text-[#059669]\">WhatsApp features</span> teams actually need') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Subtitle') }}</label>
                                <input type="text" class="form-control" name="landing_modules_subtitle" value="{{ get_option('landing_modules_subtitle', 'Every module supports a real operational job, from outreach and automation to contact management and reporting.') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $modDefaults = [
                            1 => ['AI Smart Reply', 'Generate fast, context-aware WhatsApp replies so agents can handle conversations with less manual effort.'],
                            2 => ['Auto Reply', 'Trigger automatic responses for inbound messages, off-hours support, lead capture, and common request handling.'],
                            3 => ['Bulk Campaigns', 'Launch high-volume WhatsApp campaigns to segmented audiences while keeping timing and response flow under control.'],
                            4 => ['Chatbot', 'Build conversational flows that qualify leads, answer questions, and move contacts into the next step automatically.'],
                            5 => ['Contacts', 'Manage contact lists, segments, and campaign targets from one structured database.'],
                            6 => ['Export Participants', 'Extract participants from WhatsApp groups for outreach, qualification, migration, and audience building workflows.'],
                            7 => ['Profile Info', 'Retrieve WhatsApp profile information quickly to enrich lead context and improve handoff quality.'],
                            8 => ['Reports', 'Track campaign output, reply activity, and workflow performance with clear reporting for real operational decisions.']
                        ];
                    @endphp
                    @foreach($modDefaults as $idx => $m)
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-none border h-100">
                                <div class="card-header bg-light py-2">
                                    <span class="fw-bold fs-13">Module {{ $idx }}: {{ $m[0] }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Title') }}</label>
                                        <input type="text" class="form-control" name="landing_mod{{ $idx }}_title" value="{{ get_option('landing_mod'.$idx.'_title', $m[0]) }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Description') }}</label>
                                        <textarea class="form-control" name="landing_mod{{ $idx }}_desc" rows="2">{{ get_option('landing_mod'.$idx.'_desc', $m[1]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TAB 4: PRICING (4 Tiers) --}}
            <div class="tab-pane fade" id="content-pricing" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-tags me-1 text-info"></i> {{ __('Pricing Section Headings') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Badge') }}</label>
                                <input type="text" class="form-control" name="landing_pricing_badge" value="{{ get_option('landing_pricing_badge', 'PRICING') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Title') }}</label>
                                <input type="text" class="form-control" name="landing_pricing_title" value="{{ get_option('landing_pricing_title', 'Simple and transparent pricing') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Subtitle') }}</label>
                                <input type="text" class="form-control" name="landing_pricing_subtitle" value="{{ get_option('landing_pricing_subtitle', 'Choose the plan that fits your business needs. Upgrade or downgrade anytime.') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $pDefaults = [
                            1 => ['Starter', '$19', 'For individuals and small businesses'],
                            2 => ['Professional', '$49', 'For growing teams (Most Popular)'],
                            3 => ['Business', '$99', 'For established businesses'],
                            4 => ['Enterprise', 'Custom', 'For large organizations']
                        ];
                    @endphp
                    @foreach($pDefaults as $pidx => $p)
                        @php
                            $savedPrice = get_option('landing_price'.$pidx.'_price', $p[1]);
                        @endphp
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-none border h-100">
                                <div class="card-header bg-light py-2">
                                    <span class="fw-bold fs-13">Plan {{ $pidx }}: {{ $p[0] }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Plan Name') }}</label>
                                        <input type="text" class="form-control" name="landing_price{{ $pidx }}_name" value="{{ get_option('landing_price'.$pidx.'_name', $p[0]) }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Price') }}</label>
                                        <input type="text" class="form-control" name="landing_price{{ $pidx }}_price" value="{{ $savedPrice }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Description / Subtitle') }}</label>
                                        <input type="text" class="form-control" name="landing_price{{ $pidx }}_desc" value="{{ get_option('landing_price'.$pidx.'_desc', $p[2]) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TAB 5: TESTIMONIALS (3 Cards) --}}
            <div class="tab-pane fade" id="content-testi" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-comments me-1 text-danger"></i> {{ __('Testimonials Headings') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Badge') }}</label>
                                <input type="text" class="form-control" name="landing_testi_badge" value="{{ get_option('landing_testi_badge', 'TESTIMONIALS') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Title') }}</label>
                                <input type="text" class="form-control" name="landing_testi_title" value="{{ get_option('landing_testi_title', 'What our customers say') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Subtitle') }}</label>
                                <input type="text" class="form-control" name="landing_testi_subtitle" value="{{ get_option('landing_testi_subtitle', 'Trusted by businesses worldwide to streamline their WhatsApp operations.') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $tDefaults = [
                            1 => ['Priya Sharma', 'Marketing Manager, RetailHub', 'Whatsappbulk has completely transformed how we handle customer communication. Our response time improved by 70%!'],
                            2 => ['Rahul Mehta', 'Founder, GrowKart', 'The bulk campaign feature is a game-changer. We can now reach thousands of customers in minutes with excellent delivery rates.'],
                            3 => ['Sneha Patel', 'Operations Head, Foodies', 'Easy to use, powerful features, and great support. Highly recommended for any business using WhatsApp.'],
                            4 => ['Vikram Malhotra', 'E-commerce Director, UrbanStyle', 'Whatsappbulk automated our entire client follow-up workflow. We closed 35% more orders in our very first week with instant replies.']
                        ];
                    @endphp
                    @foreach($tDefaults as $tidx => $t)
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-none border h-100">
                                <div class="card-header bg-light py-2">
                                    <span class="fw-bold fs-13">Customer {{ $tidx }}: {{ $t[0] }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Name') }}</label>
                                        <input type="text" class="form-control" name="landing_testi{{ $tidx }}_name" value="{{ get_option('landing_testi'.$tidx.'_name', $t[0]) }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Role / Company') }}</label>
                                        <input type="text" class="form-control" name="landing_testi{{ $tidx }}_role" value="{{ get_option('landing_testi'.$tidx.'_role', $t[1]) }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Review Quote') }}</label>
                                        <textarea class="form-control" name="landing_testi{{ $tidx }}_text" rows="3">{{ get_option('landing_testi'.$tidx.'_text', $t[2]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TAB 6: FAQS (7 Questions) --}}
            <div class="tab-pane fade" id="content-faqs" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-circle-question me-1 text-secondary"></i> {{ __('FAQs Section Headings') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Badge') }}</label>
                                <input type="text" class="form-control" name="landing_faqs_badge" value="{{ get_option('landing_faqs_badge', 'FAQS') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Title') }}</label>
                                <input type="text" class="form-control" name="landing_faqs_title" value="{{ get_option('landing_faqs_title', 'Frequently asked questions') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Subtitle') }}</label>
                                <input type="text" class="form-control" name="landing_faqs_subtitle" value="{{ get_option('landing_faqs_subtitle', 'Get answers to common questions about Whatsappbulk.') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $faqDefaults = [
                            1 => ['What is Whatsappbulk?', 'Whatsappbulk is an all-in-one WhatsApp marketing tool that helps you manage campaigns, automate replies, handle contacts, and track performance — all in one workspace.'],
                            2 => ['Is it safe to use?', 'Yes, our platform employs anti-ban mechanisms, customizable message delays, and adheres to standard messaging policies to protect your accounts.'],
                            3 => ['Can I upgrade my plan later?', 'Absolutely. You can upgrade, downgrade, or cancel your subscription at any time directly from your billing settings.'],
                            4 => ['Do you offer a free trial?', 'Yes, we provide a free starter tier so you can test campaigns and automation features before committing.'],
                            5 => ['What payment methods do you accept?', 'We accept major credit and debit cards, UPI, PayPal, Stripe, and multiple international payment gateways.'],
                            6 => ['Can I use multiple WhatsApp numbers?', 'Yes, our Professional and Business plans support multiple concurrent WhatsApp numbers and team members.'],
                            7 => ['Do you provide customer support?', 'Our dedicated support team is available 24/7 via live chat, email, and WhatsApp to help you set up and scale.']
                        ];
                    @endphp
                    @foreach($faqDefaults as $fidx => $f)
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-none border h-100">
                                <div class="card-header bg-light py-2">
                                    <span class="fw-bold fs-13">FAQ {{ $fidx }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Question') }}</label>
                                        <input type="text" class="form-control" name="landing_faq{{ $fidx }}_q" value="{{ get_option('landing_faq'.$fidx.'_q', $f[0]) }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-6 fs-12">{{ __('Answer') }}</label>
                                        <textarea class="form-control" name="landing_faq{{ $fidx }}_a" rows="3">{{ get_option('landing_faq'.$fidx.'_a', $f[1]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TAB 7: BOTTOM CTA & FOOTER --}}
            <div class="tab-pane fade" id="content-cta" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-bullhorn me-1 text-success"></i> {{ __('Bottom Call to Action Banner') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-6 fs-13">{{ __('Headline') }}</label>
                            <input type="text" class="form-control" name="landing_cta_title" value="{{ get_option('landing_cta_title', 'Ready to scale your WhatsApp marketing?') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-6 fs-13">{{ __('Subtitle') }}</label>
                            <textarea class="form-control" name="landing_cta_subtitle" rows="2">{{ get_option('landing_cta_subtitle', 'Connect your WhatsApp account in seconds. Start sending bulk campaigns, automating customer replies, and converting more leads with zero coding required.') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Button Text') }}</label>
                                <input type="text" class="form-control" name="landing_cta_btn_text" value="{{ get_option('landing_cta_btn_text', 'Get Started Free') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Button URL') }}</label>
                                <input type="text" class="form-control" name="landing_cta_btn_url" value="{{ get_option('landing_cta_btn_url', 'auth/signup') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-window-minimize me-1 text-secondary"></i> {{ __('Footer Information') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-6 fs-13">{{ __('Footer Tagline') }}</label>
                            <input type="text" class="form-control" name="landing_footer_tagline" value="{{ get_option('landing_footer_tagline', 'WhatsApp Marketing & Automation Tool') }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Floating / Fixed Save Button --}}
        <div class="card shadow-sm border bg-white mt-4 sticky-bottom p-3 text-end rounded-3">
            <button type="submit" class="btn btn-success fw-bold px-5 py-2">
                <i class="fa-light fa-floppy-disk me-1"></i> {{ __('Save Landing Page Settings') }}
            </button>
        </div>

    </form>
</div>

@endsection