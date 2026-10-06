@extends('website_builder.texigo_theme.layout')

@section('title', ($blog['title'] ?? 'Blog Article') . ' - ' . ($agency->site_title ?? 'TaxiGo'))

@section('content')
<style>
  .tx-blog-detail-hero {
    background: linear-gradient(180deg, #0D0F12 0%, #1a1d22 100%);
    padding: 80px 0 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .tx-blog-detail-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23F5C518' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  }
  .tx-blog-detail-category {
    display: inline-block;
    background: var(--tx-primary, #F5C518);
    color: #0D0F12;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 5px 16px;
    border-radius: 30px;
    margin-bottom: 20px;
  }
  .tx-blog-detail-title {
    font-size: clamp(26px, 4vw, 46px);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.2;
    margin-bottom: 24px;
    font-family: var(--tx-font-heading, 'Outfit', sans-serif);
  }
  .tx-blog-detail-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
    color: rgba(255,255,255,0.6);
    font-size: 13px;
    font-weight: 600;
  }
  .tx-blog-detail-meta i {
    color: var(--tx-primary, #F5C518);
    margin-right: 5px;
  }
  .tx-blog-article-body {
    font-size: 17px;
    line-height: 1.85;
    color: #334155;
    letter-spacing: -0.2px;
  }
</style>

<!-- ===== BLOG ARTICLE HERO ===== -->
<section class="tx-blog-detail-hero">
  <div class="tx-container" style="position: relative; z-index: 2;">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="tx-blog-detail-category">
          <i class="fa-solid fa-tag me-1"></i> {{ $blog['category'] ?? 'Article' }}
        </div>
        <h1 class="tx-blog-detail-title">
          {{ $blog['title'] ?? 'Blog Article Title' }}
        </h1>
        <div class="tx-blog-detail-meta">
          <span><i class="fa-solid fa-user"></i>{{ $blog['author'] ?? 'Admin' }}</span>
          <span><i class="fa-solid fa-calendar-days"></i>{{ $blog['date'] ?? date('M d, Y') }}</span>
          <span><i class="fa-solid fa-clock"></i>5 min read</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== BLOG CONTENT ===== -->
<section style="padding: 56px 0 100px; background: #FFFFFF;">
  <div class="tx-container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <!-- Cover Image -->
        <div class="position-relative rounded-4 overflow-hidden shadow-lg mb-5" style="max-height: 480px; background: #0D0F12;">
          <img src="{{ str_starts_with($blog['image'] ?? '', 'http') ? $blog['image'] : asset($blog['image'] ?? 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png') }}"
               onerror="this.src='{{ asset('assets/website_builder/Templates/Texigo_agency/herobanner_image.png') }}';"
               alt="{{ $blog['title'] ?? 'Article Cover' }}"
               style="width: 100%; height: 100%; object-fit: cover; max-height: 480px;">
        </div>

        <!-- Excerpt Highlight -->
        @if(!empty($blog['excerpt']))
          <div class="p-4 rounded-4 mb-5 shadow-sm" style="background: #FFFBEB; border-left: 4px solid var(--tx-primary, #F5C518);">
            <p class="fs-5 mb-0 fst-italic" style="color: #1E293B; line-height: 1.7;">
              "{{ $blog['excerpt'] }}"
            </p>
          </div>
        @endif

        <!-- Article Content -->
        @if(!empty($blog['content']))
          <div class="tx-blog-article-body mb-5">
            {!! nl2br(e($blog['content'])) !!}
          </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
          @php
            $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
            $backUrl  = $subdomainParam ? route('website-builder.subdomain.site',  ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
            $blogsUrl = $subdomainParam ? route('website-builder.subdomain.blogs', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.blogs');
          @endphp
          <a href="{{ $blogsUrl }}" class="tx-btn" style="background:#F1F5F9; color:#0D0F12; font-weight:700; padding:10px 22px; border-radius:8px; text-decoration:none;">
            <i class="fa-solid fa-arrow-left me-2"></i> All Articles
          </a>
          <a href="{{ $backUrl }}" class="tx-btn tx-btn-yellow" style="padding:10px 22px; font-weight:700; border-radius:8px; text-decoration:none;">
            Back to Home <i class="fa-solid fa-arrow-right ms-2"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
