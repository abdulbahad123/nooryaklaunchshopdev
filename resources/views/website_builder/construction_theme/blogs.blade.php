@extends('website_builder.construction_theme.layout')

@section('title', 'Articles & Blog - ' . ($agency->site_title ?? 'BuildCraft'))

@section('content')
<style>
  .cn-blogs-hero {
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    padding: 80px 0 56px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .cn-blogs-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23F59E0B' fill-opacity='0.06'%3E%3Cpath d='M20 0L25 10H15L20 0zM0 20L10 15V25L0 20zM40 20L30 15V25L40 20zM20 40L25 30H15L20 40z'/%3E%3C/g%3E%3C/svg%3E") repeat;
  }
  .cn-blogs-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--cn-primary, #F59E0B);
    color: #1a1a1a;
    font-weight: 800;
    font-size: 11px;
    padding: 5px 16px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 20px;
  }
  .cn-blogs-hero-title {
    font-size: clamp(30px, 4.5vw, 52px);
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 16px;
    line-height: 1.15;
    font-family: 'Barlow Condensed', 'Inter', sans-serif;
  }
  .cn-blogs-hero-title span {
    color: var(--cn-primary, #F59E0B);
  }
  .cn-blogs-hero-desc {
    font-size: 17px;
    color: rgba(255,255,255,0.55);
    max-width: 540px;
    margin: 0 auto;
  }
  .cn-blog-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    border: 1.5px solid #E2E8F0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: transform 0.32s ease, box-shadow 0.32s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  .cn-blog-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
  }
  .cn-blog-card-img-wrap {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: #1a1a1a;
  }
  .cn-blog-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .cn-blog-card:hover .cn-blog-card-img {
    transform: scale(1.07);
  }
  .cn-blog-category-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: var(--cn-primary, #F59E0B);
    color: #1a1a1a;
    font-weight: 800;
    font-size: 11px;
    padding: 4px 12px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .cn-blog-card-body {
    padding: 22px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
  }
  .cn-blog-meta {
    display: flex;
    align-items: center;
    gap: 14px;
    font-size: 12px;
    color: #94A3B8;
    font-weight: 600;
    margin-bottom: 10px;
  }
  .cn-blog-meta i {
    color: var(--cn-primary, #F59E0B);
  }
  .cn-blog-card-title {
    font-size: 18px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 10px;
    line-height: 1.35;
    font-family: 'Barlow Condensed', 'Inter', sans-serif;
  }
  .cn-blog-card-excerpt {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.65;
    margin-bottom: 18px;
  }
  .cn-blog-read-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #1a1a1a;
    font-weight: 800;
    font-size: 12px;
    background: var(--cn-primary, #F59E0B);
    padding: 9px 18px;
    border-radius: 4px;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.2s ease;
    align-self: flex-start;
  }
  .cn-blog-read-btn:hover {
    background: #d97706;
    transform: translateX(3px);
    color: #1a1a1a;
  }
</style>

<!-- ===== BLOGS HERO ===== -->
<section class="cn-blogs-hero">
  <div class="cn-container" style="position: relative; z-index: 2;">
    <div class="cn-blogs-hero-badge">
      <i class="fa-solid fa-newspaper"></i> Articles & Insights
    </div>
    <h1 class="cn-blogs-hero-title">
      Latest Construction <span>News</span> & Tips
    </h1>
    <p class="cn-blogs-hero-desc">
      Expert construction insights, project management tips, and industry news from our team.
    </p>
  </div>
</section>

<!-- ===== BLOGS GRID ===== -->
<section style="background: #F8FAFC; padding: 56px 0 100px;">
  <div class="cn-container">
    @php
      $blogs = $agency->blogs_data ?? [
        [
          'id'      => 1,
          'title'   => '7 Key Phases of a Successful Construction Project',
          'category'=> 'Project Management',
          'author'  => 'Robert Harrison',
          'date'    => 'Sep 04, 2026',
          'image'   => 'assets/website_builder/Templates/Construction_agency/herobanner_image.png',
          'excerpt' => 'A complete walkthrough of how our team manages construction projects from groundbreaking to handover.',
        ],
        [
          'id'      => 2,
          'title'   => 'Top Building Materials for Energy-Efficient Homes in 2026',
          'category'=> 'Building Materials',
          'author'  => 'Lisa Turner',
          'date'    => 'Aug 22, 2026',
          'image'   => 'assets/website_builder/Templates/Construction_agency/about_image.png',
          'excerpt' => 'Explore the latest energy-efficient building materials that reduce costs and improve sustainability.',
        ],
        [
          'id'      => 3,
          'title'   => 'Why Safety Culture Is the Foundation of Every Great Construction Firm',
          'category'=> 'Safety & Standards',
          'author'  => 'Michael Evans',
          'date'    => 'Aug 08, 2026',
          'image'   => 'assets/website_builder/Templates/Construction_agency/herobanner_image.png',
          'excerpt' => 'Discover how prioritizing site safety leads to better project outcomes and team morale.',
        ],
      ];

      $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
    @endphp

    <div class="row g-4">
      @foreach($blogs as $bi => $blog)
        @php
          $blogId = $blog['id'] ?? ($loop->iteration);
          $detailUrl = $subdomainParam
            ? route('website-builder.subdomain.blog', ['subdomain' => $subdomainParam, 'id' => $blogId])
            : route('website-builder.templates.construction.blog', ['id' => $blogId]);
        @endphp
        <div class="col-lg-4 col-md-6">
          <div class="cn-blog-card">
            <div class="cn-blog-card-img-wrap">
              <span class="cn-blog-category-badge">{{ $blog['category'] ?? 'Article' }}</span>
              <a href="{{ $detailUrl }}">
                <img src="{{ str_starts_with($blog['image'] ?? '', 'http') ? $blog['image'] : asset($blog['image'] ?? 'assets/website_builder/wb_card_agency.png') }}"
                     onerror="this.src='{{ asset('assets/website_builder/wb_card_agency.png') }}';"
                     alt="{{ $blog['title'] ?? 'Article' }}"
                     class="cn-blog-card-img">
              </a>
            </div>
            <div class="cn-blog-card-body">
              <div>
                <div class="cn-blog-meta">
                  <span><i class="fa-solid fa-user me-1"></i>{{ $blog['author'] ?? 'Admin' }}</span>
                  <span><i class="fa-solid fa-calendar-days me-1"></i>{{ $blog['date'] ?? date('M d, Y') }}</span>
                </div>
                <h4 class="cn-blog-card-title">
                  <a href="{{ $detailUrl }}" class="text-decoration-none" style="color: inherit;">
                    {{ $blog['title'] ?? 'Blog Article Title' }}
                  </a>
                </h4>
                <p class="cn-blog-card-excerpt">
                  {{ $blog['excerpt'] ?? 'Short excerpt summarizing the key insights of this article.' }}
                </p>
              </div>
              <a href="{{ $detailUrl }}" class="cn-blog-read-btn">
                Read More <i class="fa-solid fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
