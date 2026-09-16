@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — Our Projects')
@section('description', 'A showcase of landmark completed construction projects across residential, commercial, industrial, and infrastructure sectors.')

@section('content')

@php
  $heroBg = asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
  $portfolio = $agency->portfolio_data ?? [];
  $contactUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.contact', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction.contact');
  $homeUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.site', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction');
  $cats = array_unique(array_column($portfolio, 'category'));
@endphp

{{-- Page Hero --}}
<section class="cn-page-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 320px; position: relative;">
  <div class="cn-page-hero-overlay"></div>
  <div class="cn-container" style="position: relative; z-index: 2; width: 100%;">
    <div class="cn-page-hero-content">
      <div class="cn-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        <span>Our Projects</span>
      </div>
      <h1 class="cn-page-hero-title">Featured <span class="cn-text-yellow">Completed Projects</span></h1>
      <p class="cn-page-hero-subtitle">A showcase of landmark constructions delivered across residential, commercial, industrial, and infrastructure sectors.</p>
    </div>
  </div>
</section>

{{-- Projects Grid Section --}}
<section class="cn-section cn-section-light">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:32px;">
      <div class="cn-section-label">PORTFOLIO</div>
      <h2 class="cn-section-heading">Our Engineering <span class="cn-text-yellow">Excellence</span></h2>
      <div class="cn-divider cn-divider-center"></div>
    </div>

    {{-- Filter Tabs --}}
    @if(count($cats) > 0)
    <div class="cn-filter-tabs mb-4" style="justify-content:center;">
      <button class="cn-filter-tab active" data-cat="all">All Projects</button>
      @foreach($cats as $cat)
        <button class="cn-filter-tab" data-cat="{{ $cat }}">{{ $cat }}</button>
      @endforeach
    </div>
    @endif

    @if(count($portfolio) > 0)
    <div class="row g-4">
      @foreach($portfolio as $project)
      <div class="col-md-4 col-sm-6 cn-project-card" data-cat="{{ $project['category'] ?? 'Other' }}">
        <div class="cn-project-card-ref h-100">
          <div class="cn-project-img-wrap" style="height:200px;">
            <img src="{{ asset($project['image']) }}" alt="{{ $project['title'] }}" class="cn-project-ref-img" loading="lazy">
            <span class="cn-project-badge">{{ $project['category'] ?? 'Construction' }}</span>
          </div>
          <div class="cn-project-ref-body p-3">
            <h4 class="cn-project-ref-title mb-1" style="color:#111111;">{{ $project['title'] }}</h4>
            <div class="cn-project-ref-loc small text-muted mb-2">
              <i class="fa-solid fa-location-dot me-1 text-warning"></i>{{ $project['location'] ?? 'USA' }}
              @if(!empty($project['year']))
                &nbsp;·&nbsp;<i class="fa-solid fa-calendar me-1"></i>{{ $project['year'] }}
              @endif
            </div>
            @if(!empty($project['desc']))
              <p class="small text-muted mb-0" style="line-height:1.5;">{{ $project['desc'] }}</p>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <div class="text-center py-5">
      <i class="fa-solid fa-folder-open" style="font-size:64px; color:#CCCCCC; margin-bottom:16px; display:block;"></i>
      <p style="color:#666666; font-size:16px;">No projects available yet. Projects will appear here once added from the dashboard.</p>
    </div>
    @endif
  </div>
</section>

{{-- CTA Banner --}}
<section class="cn-footer-cta-banner" style="background: url('{{ $heroBg }}') no-repeat center center / cover; position: relative;">
  <div class="cn-cta-overlay"></div>
  <div class="cn-container text-center" style="position: relative; z-index: 2;">
    <h2 class="cn-cta-title text-white">Your Project Could Be <span class="cn-text-yellow">Next</span></h2>
    <p class="text-white-50 mb-4" style="max-width: 520px; margin: 0 auto 24px;">Join 320+ satisfied clients who trusted BuildCraft with their construction vision.</p>
    <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
      Start Your Project <i class="fa-solid fa-arrow-right ms-1"></i>
    </a>
  </div>
</section>

@endsection
