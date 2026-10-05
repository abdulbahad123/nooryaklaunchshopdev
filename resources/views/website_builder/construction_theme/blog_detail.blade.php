@extends('website_builder.construction_theme.layout')

@section('title', ($blog['title'] ?? 'Blog Article') . ' - ' . ($agency->site_title ?? 'BuildCraft'))

@section('content')
<style>
  .cn-blog-detail-hero {
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    padding: 80px 0 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .cn-blog-detail-category {
    display: inline-block;
    background: #ff5e14;
    color: #ffffff;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    padding: 6px 18px;
    border-radius: 4px;
    margin-bottom: 20px;
  }
  .cn-blog-detail-title {
    font-size: clamp(26px, 4vw, 44px);
    font-weight: 900;
    color: #ffffff;
    line-height: 1.25;
    margin-bottom: 24px;
    text-transform: uppercase;
    font-family: var(--cn-font-heading, 'Montserrat', sans-serif);
  }
  .cn-blog-detail-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
    color: rgba(255,255,255,0.7);
    font-size: 13px;
    font-weight: 600;
  }
  .cn-blog-detail-meta i {
    color: #ff5e14;
    margin-right: 6px;
  }
  .cn-blog-article-body {
    font-size: 17px;
    line-height: 1.85;
    color: #334155;
    letter-spacing: -0.2px;
  }
</style>

<!-- ===== BLOG ARTICLE HERO ===== -->
<section class="cn-blog-detail-hero">
  <div class="cn-container" style="position: relative; z-index: 2;">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="cn-blog-detail-category">
          <i class="fa-solid fa-hard-hat me-1"></i> {{ $blog['category'] ?? 'Construction' }}
        </div>
        <h1 class="cn-blog-detail-title">
          {{ $blog['title'] ?? 'Blog Article Title' }}
        </h1>
        <div class="cn-blog-detail-meta">
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
  <div class="cn-container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <!-- Cover Image -->
        <div class="position-relative rounded-3 overflow-hidden shadow-lg mb-5" style="max-height: 480px; background: #1a1a1a;">
          <img src="{{ str_starts_with($blog['image'] ?? '', 'http') ? $blog['image'] : asset($blog['image'] ?? 'assets/website_builder/Templates/Construction_agency/herobanner_image.png') }}"
               onerror="this.src='{{ asset('assets/website_builder/Templates/Construction_agency/herobanner_image.png') }}';"
               alt="{{ $blog['title'] ?? 'Article Cover' }}"
               style="width: 100%; height: 100%; object-fit: cover; max-height: 480px;">
        </div>

        <!-- Excerpt Highlight -->
        @if(!empty($blog['excerpt']))
          <div class="p-4 rounded-3 mb-5 shadow-sm" style="background: #FFF7ED; border-left: 4px solid #ff5e14;">
            <p class="fs-5 mb-0 fst-italic" style="color: #1E293B; line-height: 1.7;">
              "{{ $blog['excerpt'] }}"
            </p>
          </div>
        @endif

        <!-- Article Content -->
        <div class="cn-blog-article-body mb-5">
          {!! nl2br(e($blog['content'] ?? 'No article content available.')) !!}
        </div>

        <!-- Navigation Buttons -->
        <div class="pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
          @php
            $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
            $backUrl  = $subdomainParam ? route('website-builder.subdomain.site',  ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction');
            $blogsUrl = $subdomainParam ? route('website-builder.subdomain.blogs', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.blogs');
          @endphp
          <a href="{{ $blogsUrl }}" class="cn-btn" style="background:#F1F5F9; color:#0D0F12; font-weight:700; padding:10px 22px; border-radius:4px; text-decoration:none;">
            <i class="fa-solid fa-arrow-left me-2"></i> All Articles
          </a>
          <a href="{{ $backUrl }}" class="cn-btn" style="background:#ff5e14; color:#fff; padding:10px 22px; font-weight:700; border-radius:4px; text-decoration:none;">
            Back to Home <i class="fa-solid fa-arrow-right ms-2"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
