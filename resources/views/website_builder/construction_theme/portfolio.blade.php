@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — Our Projects')

@section('content')

@php
  $heroBg = asset($agency->hero_image ?? 'assets/website_builder/Templates/Construction_agency/herobanner_image.png');
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
<section class="cn-page-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 300px;">
  <div class="cn-page-hero-overlay"></div>
  <div class="cn-container" style="width:100%;">
    <div class="cn-page-hero-content">
      <div class="cn-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        <span>Our Projects</span>
      </div>
      <h1 class="cn-page-hero-title">Our <span style="color:var(--cn-primary);">Projects</span></h1>
      <p class="cn-page-hero-subtitle">A showcase of landmark constructions delivered across residential, commercial, industrial, and infrastructure sectors.</p>
    </div>
  </div>
</section>

{{-- Projects --}}
<section class="cn-section">
  <div class="cn-container">
    <div class="cn-section-header-center" style="margin-bottom:32px;">
      <div class="cn-section-tag"><i class="fa-solid fa-folder-open"></i> Portfolio</div>
      <h2 class="cn-section-title">Featured <span>Completed Projects</span></h2>
      <div class="cn-divider cn-divider-center"></div>
    </div>

    {{-- Filter Tabs --}}
    @if(count($cats) > 0)
    <div class="cn-filter-tabs" style="justify-content:center;">
      <button class="cn-filter-tab active" data-cat="all">All Projects</button>
      @foreach($cats as $cat)
        <button class="cn-filter-tab" data-cat="{{ $cat }}">{{ $cat }}</button>
      @endforeach
    </div>
    @endif

    @if(count($portfolio) > 0)
    <div class="cn-projects-grid">
      @foreach($portfolio as $project)
      <div class="cn-project-card" data-cat="{{ $project['category'] ?? 'Other' }}">
        <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}" class="cn-project-img" loading="lazy">
        <div class="cn-project-overlay"></div>
        <div class="cn-project-body">
          <span class="cn-project-cat">{{ $project['category'] ?? 'Construction' }}</span>
          <div class="cn-project-title">{{ $project['title'] }}</div>
          <div class="cn-project-meta">
            @if(!empty($project['location']))
              <i class="fa-solid fa-location-dot me-1"></i>{{ $project['location'] }}
            @endif
            @if(!empty($project['year']))
              &nbsp;·&nbsp;<i class="fa-solid fa-calendar me-1"></i>{{ $project['year'] }}
            @endif
          </div>
          @if(!empty($project['desc']))
            <div class="cn-project-desc">{{ $project['desc'] }}</div>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @else
    <div class="text-center py-5">
      <i class="fa-solid fa-folder-open" style="font-size:64px;color:var(--cn-text-dim);margin-bottom:16px;display:block;"></i>
      <p style="color:var(--cn-text-muted);font-size:16px;">No projects available yet. Projects will appear here once added from the dashboard.</p>
    </div>
    @endif
  </div>
</section>

{{-- CTA --}}
<section class="cn-cta-section">
  <div class="cn-container text-center">
    <h2 class="cn-cta-title">Your Project Could Be <span style="color:var(--cn-dark);">Next</span></h2>
    <p class="cn-cta-subtitle">Join 1,200+ satisfied clients who trusted BuildCraft with their construction vision.</p>
    <a href="{{ $contactUrl }}" class="cn-btn cn-btn-cta-dark">
      <i class="fa-solid fa-file-lines"></i> Start Your Project
    </a>
  </div>
</section>

@endsection
