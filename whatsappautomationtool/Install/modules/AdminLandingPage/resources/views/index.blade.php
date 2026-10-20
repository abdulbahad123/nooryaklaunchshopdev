@extends('layouts.app')

@section('sub_header')
    <x-sub-header
        title="{{ __('Landing Page Settings') }}"
        description="{{ __('Manage and customize every section of your public WhatsApp Bulk marketing page') }}"
    >
    </x-sub-header>
@endsection

@section('content')

<div class="container max-w-1000 pb-5">
    <form class="actionForm" action="{{ url_admin('settings/save') }}" method="POST">
        @csrf

        {{-- Nav Tabs --}}
        <ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 border gap-2 shadow-xs" id="landingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-6 fs-13" id="tab-hero" data-bs-toggle="pill" data-bs-target="#content-hero" type="button" role="tab">
                    <i class="fa-light fa-sparkles me-1 text-primary"></i> {{ __('Hero & Metrics') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-stack" data-bs-toggle="pill" data-bs-target="#content-stack" type="button" role="tab">
                    <i class="fa-light fa-layer-group me-1 text-warning"></i> {{ __('Module Stack (4 Cards)') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-modules" data-bs-toggle="pill" data-bs-target="#content-modules" type="button" role="tab">
                    <i class="fa-light fa-grid-2 me-1 text-success"></i> {{ __('8 Product Modules') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-workflow" data-bs-toggle="pill" data-bs-target="#content-workflow" type="button" role="tab">
                    <i class="fa-light fa-diagram-project me-1 text-info"></i> {{ __('System Workflow') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-usecases" data-bs-toggle="pill" data-bs-target="#content-usecases" type="button" role="tab">
                    <i class="fa-light fa-bullseye-arrow me-1 text-danger"></i> {{ __('Core Use Cases') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-6 fs-13" id="tab-faqs" data-bs-toggle="pill" data-bs-target="#content-faqs" type="button" role="tab">
                    <i class="fa-light fa-circle-question me-1 text-secondary"></i> {{ __('FAQs & Bottom Banner') }}
                </button>
            </li>
        </ul>

        {{-- Tab Content Panes --}}
        <div class="tab-content" id="landingTabsContent">
            
            {{-- TAB 1: HERO & METRICS --}}
            <div class="tab-pane fade show active" id="content-hero" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-heading me-1 text-primary"></i> {{ __('Hero Section Content') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Hero Pill Badge') }}</label>
                                <input class="form-control" name="landing_hero_badge" value="{{ get_option('landing_hero_badge', 'All-in-One WhatsApp Marketing Tool') }}" placeholder="e.g. All-in-One WhatsApp Marketing Tool">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Main Headline (HTML / linebreaks allowed)') }}</label>
                                <textarea class="form-control" name="landing_hero_title" rows="3">{{ get_option('landing_hero_title', "WhatsApp operations,\n<span style=\"color: #ff5500;\">campaigns,</span> and\nautomation") }}</textarea>
                                <div class="form-text fs-12 text-muted">{{ __('Use <span style="color: #ff5500;">word</span> for brand accent color.') }}</div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Bold Subheadline') }}</label>
                                <input class="form-control" name="landing_hero_subtitle_bold" value="{{ get_option('landing_hero_subtitle_bold', 'One WhatsApp workspace for campaigns, replies, bots, contacts, and reports.') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Detailed Paragraph Description') }}</label>
                                <textarea class="form-control" name="landing_hero_subtitle_desc" rows="3">{{ get_option('landing_hero_subtitle_desc', 'Built around the modules teams actually use every day: bulk outreach, smart replies, auto replies, chatbots, contacts, participant export, profile lookups, and reporting.') }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Primary Button Text') }}</label>
                                <input class="form-control" name="landing_hero_btn1_text" value="{{ get_option('landing_hero_btn1_text', 'Create your workspace') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Primary Button URL') }}</label>
                                <input class="form-control" name="landing_hero_btn1_url" value="{{ get_option('landing_hero_btn1_url', 'auth/signup') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Secondary Button Text') }}</label>
                                <input class="form-control" name="landing_hero_btn2_text" value="{{ get_option('landing_hero_btn2_text', 'Explore modules') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Secondary Button URL') }}</label>
                                <input class="form-control" name="landing_hero_btn2_url" value="{{ get_option('landing_hero_btn2_url', '#modules') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3 Metrics Bar --}}
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-chart-simple me-1 text-primary"></i> {{ __('3 Metric Highlights Bar (Under Hero CTAs)') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Metric 1 Number') }}</label>
                                <input class="form-control mb-1" name="landing_stat1_number" value="{{ get_option('landing_stat1_number', '8') }}">
                                <label class="form-label fw-6 fs-12 text-muted">{{ __('Metric 1 Label') }}</label>
                                <input class="form-control" name="landing_stat1_label" value="{{ get_option('landing_stat1_label', 'Operational modules') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Metric 2 Number') }}</label>
                                <input class="form-control mb-1" name="landing_stat2_number" value="{{ get_option('landing_stat2_number', '1') }}">
                                <label class="form-label fw-6 fs-12 text-muted">{{ __('Metric 2 Label') }}</label>
                                <input class="form-control" name="landing_stat2_label" value="{{ get_option('landing_stat2_label', 'Unified workflow') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Metric 3 Number') }}</label>
                                <input class="form-control mb-1" name="landing_stat3_number" value="{{ get_option('landing_stat3_number', '24/7') }}">
                                <label class="form-label fw-6 fs-12 text-muted">{{ __('Metric 3 Label') }}</label>
                                <input class="form-control" name="landing_stat3_label" value="{{ get_option('landing_stat3_label', 'Automation coverage') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: MODULE STACK (4 CARDS) --}}
            <div class="tab-pane fade" id="content-stack" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-layer-group me-1 text-warning"></i> {{ __('Section Header') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Section Pill Badge') }}</label>
                                <input class="form-control" name="landing_stack_badge" value="{{ get_option('landing_stack_badge', 'LIVE MODULE STACK') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Section Title') }}</label>
                                <input class="form-control" name="landing_stack_title" value="{{ get_option('landing_stack_title', 'Whatsappbulk <span style=\"color: #ff5500;\">Workspace</span>') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Section Subtitle') }}</label>
                                <input class="form-control" name="landing_stack_subtitle" value="{{ get_option('landing_stack_subtitle', 'Product overview') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Card 1 --}}
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13 text-primary">{{ __('Card 1: Bulk Campaigns') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Category / Sub-tag') }}</label>
                                    <input class="form-control form-control-sm" name="landing_stack1_tag" value="{{ get_option('landing_stack1_tag', 'Campaign control') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Card Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_stack1_title" value="{{ get_option('landing_stack1_title', 'Bulk Campaigns') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_stack1_desc" rows="2">{{ get_option('landing_stack1_desc', 'Segment contacts, send at scale, and track response flow.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13 text-primary">{{ __('Card 2: AI + Auto Reply') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Category / Sub-tag') }}</label>
                                    <input class="form-control form-control-sm" name="landing_stack2_tag" value="{{ get_option('landing_stack2_tag', 'Inbound handling') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Card Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_stack2_title" value="{{ get_option('landing_stack2_title', 'AI + Auto Reply') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_stack2_desc" rows="2">{{ get_option('landing_stack2_desc', 'Combine automation with faster human response quality.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3 --}}
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13 text-primary">{{ __('Card 3: Chatbot + Contacts') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Category / Sub-tag') }}</label>
                                    <input class="form-control form-control-sm" name="landing_stack3_tag" value="{{ get_option('landing_stack3_tag', 'Lead flow') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Card Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_stack3_title" value="{{ get_option('landing_stack3_title', 'Chatbot + Contacts') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_stack3_desc" rows="2">{{ get_option('landing_stack3_desc', 'Capture, qualify, and manage audience data in one place.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 4 --}}
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13 text-primary">{{ __('Card 4: Reports + Export') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Category / Sub-tag') }}</label>
                                    <input class="form-control form-control-sm" name="landing_stack4_tag" value="{{ get_option('landing_stack4_tag', 'Visibility') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Card Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_stack4_title" value="{{ get_option('landing_stack4_title', 'Reports + Export') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_stack4_desc" rows="2">{{ get_option('landing_stack4_desc', 'Export participants and measure what the team actively ships.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 3: 8 PRODUCT MODULES --}}
            <div class="tab-pane fade" id="content-modules" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-grid-2 me-1 text-success"></i> {{ __('Section Header') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Section Pill Badge') }}</label>
                                <input class="form-control" name="landing_modules_badge" value="{{ get_option('landing_modules_badge', 'Product modules') }}">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Section Title') }}</label>
                                <input class="form-control" name="landing_modules_title" value="{{ get_option('landing_modules_title', 'Built from the <span style=\"color: #ff5500;\">WhatsApp features</span> teams actually need') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Section Subtitle / Description') }}</label>
                                <input class="form-control" name="landing_modules_subtitle" value="{{ get_option('landing_modules_subtitle', 'Every module supports a real operational job, from outreach and automation to contact management and reporting.') }}">
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $moduleDefaults = [
                        ['id' => 1, 'title' => 'AI Smart Reply', 'desc' => 'Generate fast, context-aware WhatsApp replies so agents can handle conversations with less manual effort.'],
                        ['id' => 2, 'title' => 'Auto Reply', 'desc' => 'Trigger automatic responses for inbound messages, off-hours support, lead capture, and common request handling.'],
                        ['id' => 3, 'title' => 'Bulk Campaigns', 'desc' => 'Launch high-volume WhatsApp campaigns to segmented audiences while keeping timing and response flow under control.'],
                        ['id' => 4, 'title' => 'Chatbot', 'desc' => 'Build conversational flows that qualify leads, answer questions, and move contacts into the next step automatically.'],
                        ['id' => 5, 'title' => 'Contacts', 'desc' => 'Manage contact lists, segments, and campaign targets from one structured database.'],
                        ['id' => 6, 'title' => 'Export Participants', 'desc' => 'Extract participants from WhatsApp groups for outreach, qualification, migration, and audience building workflows.'],
                        ['id' => 7, 'title' => 'Profile Info', 'desc' => 'Review WhatsApp profile information quickly to enrich lead context and improve handoff quality.'],
                        ['id' => 8, 'title' => 'Reports', 'desc' => 'Track campaign output, reply activity, and workflow performance with clear reporting for real operational decisions.'],
                    ];
                @endphp

                <div class="row">
                    @foreach($moduleDefaults as $mod)
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-none border h-100">
                                <div class="card-header bg-light py-2">
                                    <span class="badge bg-secondary me-1">#{{ $mod['id'] }}</span>
                                    <span class="fw-6 fs-13">{{ $mod['title'] }}</span>
                                </div>
                                <div class="card-body py-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-12 fw-6">{{ __('Title') }}</label>
                                        <input class="form-control form-control-sm" name="landing_mod_{{ $mod['id'] }}_title" value="{{ get_option('landing_mod_'.$mod['id'].'_title', $mod['title']) }}">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                        <textarea class="form-control form-control-sm" name="landing_mod_{{ $mod['id'] }}_desc" rows="2">{{ get_option('landing_mod_'.$mod['id'].'_desc', $mod['desc']) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TAB 4: SYSTEM WORKFLOW & NODES --}}
            <div class="tab-pane fade" id="content-workflow" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-diagram-project me-1 text-info"></i> {{ __('Workflow Section Header & Checklist') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Section Pill Badge') }}</label>
                                <input class="form-control" name="landing_workflow_badge" value="{{ get_option('landing_workflow_badge', 'How the modules work together') }}">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Section Title') }}</label>
                                <input class="form-control" name="landing_workflow_title" value="{{ get_option('landing_workflow_title', 'Designed as one <span style=\"color: #ff5500;\">WhatsApp system</span>, not a pile of disconnected tools') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Paragraph Description') }}</label>
                                <textarea class="form-control" name="landing_workflow_desc" rows="2">{{ get_option('landing_workflow_desc', 'The value comes from how these modules connect: contacts feed campaigns, bots reduce manual load, AI supports agent replies, and reports show what is actually working.') }}</textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label fw-6 fs-13">{{ __('Checklist Bullet 1') }}</label>
                                <input class="form-control" name="landing_wf_check_1" value="{{ get_option('landing_wf_check_1', 'Run outbound WhatsApp campaigns with contact segmentation and participant export.') }}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label fw-6 fs-13">{{ __('Checklist Bullet 2') }}</label>
                                <input class="form-control" name="landing_wf_check_2" value="{{ get_option('landing_wf_check_2', 'Reduce response time using auto replies, chatbots, and AI-assisted answer generation.') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Checklist Bullet 3') }}</label>
                                <input class="form-control" name="landing_wf_check_3" value="{{ get_option('landing_wf_check_3', 'See performance clearly with reporting that connects activity, campaigns, and reply outcomes.') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3 Workflow Node Cards --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13 text-primary">{{ __('Flow Node 1: Outbound') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Tag') }}</label>
                                    <input class="form-control form-control-sm" name="landing_wf_node1_tag" value="{{ get_option('landing_wf_node1_tag', 'Outbound Flow') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_wf_node1_title" value="{{ get_option('landing_wf_node1_title', 'Contacts → Bulk Campaigns') }}">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_wf_node1_desc" rows="2">{{ get_option('landing_wf_node1_desc', 'Segment contacts, launch campaigns, and control message flow without leaving the workspace.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13 text-success">{{ __('Flow Node 2: Inbound') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Tag') }}</label>
                                    <input class="form-control form-control-sm" name="landing_wf_node2_tag" value="{{ get_option('landing_wf_node2_tag', 'Inbound Flow') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_wf_node2_title" value="{{ get_option('landing_wf_node2_title', 'Chatbot → Auto Reply → AI Smart Reply') }}">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_wf_node2_desc" rows="2">{{ get_option('landing_wf_node2_desc', 'Handle common messages automatically, then assist agents with higher-quality responses when context matters.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13 text-warning">{{ __('Flow Node 3: Operations') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Tag') }}</label>
                                    <input class="form-control form-control-sm" name="landing_wf_node3_tag" value="{{ get_option('landing_wf_node3_tag', 'Operations Flow') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_wf_node3_title" value="{{ get_option('landing_wf_node3_title', 'Profile Info → Reports → Export Participants') }}">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_wf_node3_desc" rows="2">{{ get_option('landing_wf_node3_desc', 'Inspect lead context, review performance, and export WhatsApp group participants for new growth loops.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 5: CORE USE CASES --}}
            <div class="tab-pane fade" id="content-usecases" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-bullseye-arrow me-1 text-danger"></i> {{ __('Use Cases Header') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Pill Badge') }}</label>
                                <input class="form-control" name="landing_usecase_badge" value="{{ get_option('landing_usecase_badge', 'Core use cases') }}">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Title') }}</label>
                                <input class="form-control" name="landing_usecase_title" value="{{ get_option('landing_usecase_title', 'Use the platform for growth, support, and daily <span style=\"color: #ff5500;\">WhatsApp operations</span>') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13">{{ __('Case 1: Outbound Marketing') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_usecase1_title" value="{{ get_option('landing_usecase1_title', 'Outbound Marketing') }}">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_usecase1_desc" rows="3">{{ get_option('landing_usecase1_desc', 'Build segmented contact lists, run bulk campaigns, track responses, and improve conversion using report data.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13">{{ __('Case 2: Inbound Automation') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_usecase2_title" value="{{ get_option('landing_usecase2_title', 'Inbound Automation') }}">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_usecase2_desc" rows="3">{{ get_option('landing_usecase2_desc', 'Use chatbot and auto reply logic to handle repetitive inbound conversations without keeping agents on every message.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light fw-6 fs-13">{{ __('Case 3: Performance Visibility') }}</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fs-12 fw-6">{{ __('Title') }}</label>
                                    <input class="form-control form-control-sm" name="landing_usecase3_title" value="{{ get_option('landing_usecase3_title', 'Performance Visibility') }}">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fs-12 fw-6">{{ __('Description') }}</label>
                                    <textarea class="form-control form-control-sm" name="landing_usecase3_desc" rows="3">{{ get_option('landing_usecase3_desc', 'Use profile lookups, reports, and participant export to move from scattered chats to measurable operational workflows.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 6: FAQS & BOTTOM BANNER --}}
            <div class="tab-pane fade" id="content-faqs" role="tabpanel">
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-circle-question me-1 text-secondary"></i> {{ __('FAQs Section Heading') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Pill Badge') }}</label>
                                <input class="form-control" name="landing_faq_badge" value="{{ get_option('landing_faq_badge', 'CLEAR ANSWERS FOR TEAMS RUNNING WHATSAPP AT SCALE') }}">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Title') }}</label>
                                <input class="form-control" name="landing_faq_title" value="{{ get_option('landing_faq_title', 'Clear <span style=\"color: #ff5500;\">answers</span> for teams running WhatsApp at scale') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Subtitle / Description') }}</label>
                                <textarea class="form-control" name="landing_faq_subtitle" rows="2">{{ get_option('landing_faq_subtitle', 'Everything here is written for real operators: lead capture, inbox routing, campaign sending, and account safety.') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bottom Call to Action Mint/Green Banner --}}
                <div class="card shadow-none border mb-4">
                    <div class="card-header bg-light">
                        <div class="fw-6 fs-15 text-gray-800"><i class="fa-light fa-bullhorn me-1 text-success"></i> {{ __('Bottom CTA Banner') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Banner Headline') }}</label>
                                <input class="form-control" name="landing_cta_title" value="{{ get_option('landing_cta_title', 'Scale your WhatsApp communication without the chaos') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Banner Subtitle') }}</label>
                                <input class="form-control" name="landing_cta_subtitle" value="{{ get_option('landing_cta_subtitle', 'Connect with thousands of customers with automated campaigns, smart chatbot replies, and real-time reports.') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Button Text') }}</label>
                                <input class="form-control" name="landing_cta_btn_text" value="{{ get_option('landing_cta_btn_text', 'Start Free Trial') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-6 fs-13">{{ __('Button Link') }}</label>
                                <input class="form-control" name="landing_cta_btn_url" value="{{ get_option('landing_cta_btn_url', 'auth/signup') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sticky Save Changes Button --}}
        <div class="sticky-bottom bg-white p-3 rounded-3 border shadow-sm mt-4 d-flex justify-content-between align-items-center">
            <span class="fs-13 text-muted">
                <i class="fa-light fa-circle-check text-success me-1"></i> {{ __('All changes are saved directly into the application database.') }}
            </span>
            <button type="submit" class="btn btn-dark b-r-10 px-5 py-2 fw-6">
                {{ __('Save changes') }}
            </button>
        </div>

    </form>
</div>

@endsection