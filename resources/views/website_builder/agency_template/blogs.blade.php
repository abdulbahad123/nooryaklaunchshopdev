@extends('website_builder.agency_template.layout')

@section('title', 'Articles & Blogs - ' . ($agency->site_title ?? 'DesignAGENCY'))

@section('content')
<style>
  .blogs-hero-section {
    background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);
    padding: 50px 0 35px;
    text-align: center;
  }
  .blogs-badge {
    background: #ECFDF5;
    color: #059669;
    font-weight: 700;
    font-size: 14px;
    padding: 6px 18px;
    border-radius: 30px;
    display: inline-block;
    margin-bottom: 16px;
  }
  .blogs-hero-title {
    font-size: clamp(32px, 4vw, 48px);
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 16px;
    line-height: 1.25;
  }
  .blogs-hero-desc {
    font-size: 18px;
    color: #475569;
    max-width: 620px;
    margin: 0 auto;
  }
  .blog-card {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #F1F5F9;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  .blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
  }
  .blog-card-img-wrapper {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: #0F172A;
  }
  .blog-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .blog-card:hover .blog-card-img {
    transform: scale(1.06);
  }
  .blog-card-category {
    position: absolute;
    top: 16px;
    left: 16px;
    background: rgba(16, 185, 129, 0.9);
    backdrop-filter: blur(8px);
    color: #ffffff;
    font-weight: 700;
    font-size: 12px;
    padding: 4px 12px;
    border-radius: 20px;
  }
  .blog-card-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
  }
  .blog-card-title {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 12px;
    line-height: 1.35;
  }
  .blog-card-excerpt {
    color: #64748B;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
  }
  .btn-read-article {
    color: #10B981;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
  }
  .btn-read-article:hover {
    color: #059669;
    transform: translateX(4px);
  }
</style>

<!-- ===== BLOGS HERO SECTION ===== -->
<section class="blogs-hero-section">
  <div class="container">
    <div class="blogs-badge">
      <i class="fa-solid fa-newspaper me-1"></i> Articles & News
    </div>
    <h1 class="blogs-hero-title">
      Latest Insights & Digital Strategy Guides
    </h1>
    <p class="blogs-hero-desc">
      Discover our curated articles on modern UI/UX design, branding strategies, growth marketing, and tech innovations.
    </p>
  </div>
</section>

<!-- ===== BLOGS GRID SECTION ===== -->
<section style="background: #ffffff; padding: 40px 0 120px;">
  <div class="container">
    @php
      $blogs = $agency->blogs_data ?? [];
      if (empty($blogs) || \App\Models\WebsiteBuilder\WbAgencySetting::isDefaultBlogPlaceholderForOtherTheme('digital_agency', $blogs)) {
          $blogs = \App\Models\WebsiteBuilder\WbAgencySetting::createDefaultInstance($agency->customer_id ?? null)->blogs_data ?? [];
      }
      $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
    @endphp

    <div class="row g-4">
      @foreach($blogs as $bi => $blog)
        @php
          $blogId = $blog['id'] ?? ($loop->iteration);
          $detailUrl = $subdomainParam ? route('website-builder.subdomain.blog', ['subdomain' => $subdomainParam, 'id' => $blogId]) : route('website-builder.templates.digital_agency.blog', ['id' => $blogId]);
        @endphp
        <div class="col-lg-4 col-md-6">
          <div class="blog-card">
            <div class="blog-card-img-wrapper">
              <span class="blog-card-category">{{ $blog['category'] ?? 'Article' }}</span>
              <a href="{{ $detailUrl }}">
                <img src="{{ str_starts_with($blog['image'] ?? '', 'http') ? $blog['image'] : asset($blog['image'] ?? 'assets/website_builder/wb_card_agency.png') }}"
                     onerror="this.src='{{ asset('assets/website_builder/wb_card_agency.png') }}';"
                     alt="{{ $blog['title'] ?? 'Article' }}"
                     class="blog-card-img">
              </a>
            </div>
            <div class="blog-card-body">
              <div>
                <div class="d-flex align-items-center gap-3 text-muted small fw-medium mb-2">
                  <span><i class="fa-solid fa-user text-success me-1"></i> {{ $blog['author'] ?? 'Admin' }}</span>
                  <span><i class="fa-solid fa-calendar-days text-success me-1"></i> {{ $blog['date'] ?? date('M d, Y') }}</span>
                </div>
                <h4 class="blog-card-title">
                  <a href="{{ $detailUrl }}" class="text-decoration-none text-dark hover-emerald">
                    {{ $blog['title'] ?? 'Blog Article Title' }}
                  </a>
                </h4>
                <p class="blog-card-excerpt">
                  {{ $blog['excerpt'] ?? 'Short excerpt summarizing the key insights of this article.' }}
                </p>
              </div>

              <div>
                <a href="{{ $detailUrl }}" class="btn-read-article">
                  Read Full Article <i class="fa-solid fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
