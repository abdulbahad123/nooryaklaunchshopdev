@extends('website_builder.interior_template.layout')

@section('title', ($blog['title'] ?? 'Blog Article') . ' - InterioCRAFT')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.contact');
@endphp

<!-- ===== BLOG ARTICLE HERO HEADER ===== -->
<section style="background: #F7F7F5; padding: 75px 0 50px;">
  <div class="ic-container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-9">
        <span class="ic-pill-badge mb-3">
          <i class="fa-solid fa-bookmark me-1"></i> {{ $blog['category'] ?? 'Interior Design' }}
        </span>
        <h1 class="ic-heading mb-4" style="font-size: clamp(30px, 4.5vw, 48px); line-height: 1.25;">
          {{ $blog['title'] ?? 'Blog Article Title' }}
        </h1>
        <div class="d-flex align-items-center justify-content-center gap-4 text-muted small flex-wrap">
          <span class="fw-semibold"><i class="fa-solid fa-user me-1" style="color: var(--ic-primary);"></i> By {{ $blog['author'] ?? 'Emma Carter' }}</span>
          <span class="fw-semibold"><i class="fa-solid fa-calendar-days me-1" style="color: var(--ic-primary);"></i> {{ $blog['date'] ?? date('M d, Y') }}</span>
          <span class="fw-semibold"><i class="fa-solid fa-clock me-1" style="color: var(--ic-primary);"></i> 5 min read</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== BLOG COVER IMAGE & CONTENT BODY ===== -->
<section style="padding: 40px 0 100px; background: #FFFFFF;">
  <div class="ic-container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <!-- Featured Image -->
        <div class="position-relative rounded-4 overflow-hidden shadow-lg mb-5" style="max-height: 480px; background: #111;">
          <img src="{{ str_starts_with($blog['image'] ?? '', 'http') ? $blog['image'] : asset($blog['image'] ?? 'assets/website_builder/Templates/Interior_agency/homepage_hero.png') }}"
               onerror="this.src='{{ asset('assets/website_builder/Templates/Interior_agency/homepage_hero.png') }}';"
               alt="{{ $blog['title'] ?? 'Article Cover' }}"
               style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        <!-- Excerpt Highlight Card -->
        @if(!empty($blog['excerpt']))
          <div class="p-4 rounded-4 mb-5 border-start border-4 border-success shadow-sm" style="background: #F2F5F3; border-color: var(--ic-primary) !important;">
            <p class="fs-5 text-dark fst-italic mb-0" style="line-height: 1.6;">
              "{{ $blog['excerpt'] }}"
            </p>
          </div>
        @endif

        <!-- Main Body Content -->
        <div class="blog-article-content text-dark mb-5" style="font-size: 17px; line-height: 1.85; letter-spacing: -0.2px;">
          {!! nl2br(e($blog['content'] ?? 'No article content available.')) !!}
        </div>

        <!-- Share & Back Navigation -->
        <div class="pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
          <a href="{{ $homeUrl }}" class="ic-btn ic-btn-dark">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Home Page
          </a>
          <a href="{{ $contactUrl }}" class="ic-btn ic-btn-dark">
            Get in Touch <i class="fa-solid fa-arrow-right ms-2"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
