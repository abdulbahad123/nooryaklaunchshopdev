@extends('website_builder.texigo_theme.layout')

@section('title', 'Articles & Blog - ' . ($agency->site_title ?? 'TaxiGo'))

@section('content')
<style>
  .tx-blogs-hero {
    background: linear-gradient(180deg, #0D0F12 0%, #1a1d22 100%);
    padding: 72px 0 48px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .tx-blogs-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23F5C518' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    opacity: 1;
  }
  .tx-blogs-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--tx-primary, #F5C518);
    color: #0D0F12;
    font-weight: 700;
    font-size: 12px;
    padding: 5px 16px;
    border-radius: 30px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 18px;
  }
  .tx-blogs-hero-title {
    font-size: clamp(30px, 4.5vw, 52px);
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 16px;
    line-height: 1.2;
    font-family: var(--tx-font-heading, 'Outfit', sans-serif);
  }
  .tx-blogs-hero-title span {
    color: var(--tx-primary, #F5C518);
  }
  .tx-blogs-hero-desc {
    font-size: 17px;
    color: rgba(255,255,255,0.6);
    max-width: 560px;
    margin: 0 auto;
  }
  .tx-blog-card {
    background: #ffffff;
    border-radius: 18px;
    overflow: hidden;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    transition: transform 0.32s ease, box-shadow 0.32s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  .tx-blog-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 48px rgba(0,0,0,0.12);
  }
  .tx-blog-card-img-wrap {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: #0D0F12;
  }
  .tx-blog-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .tx-blog-card:hover .tx-blog-card-img {
    transform: scale(1.07);
  }
  .tx-blog-category-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: var(--tx-primary, #F5C518);
    color: #0D0F12;
    font-weight: 700;
    font-size: 11px;
    padding: 4px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .tx-blog-card-body {
    padding: 22px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
  }
  .tx-blog-meta {
    display: flex;
    align-items: center;
    gap: 14px;
    font-size: 12px;
    color: #94A3B8;
    font-weight: 600;
    margin-bottom: 10px;
  }
  .tx-blog-meta i {
    color: var(--tx-primary, #F5C518);
  }
  .tx-blog-card-title {
    font-size: 18px;
    font-weight: 800;
    color: #0D0F12;
    margin-bottom: 10px;
    line-height: 1.4;
    font-family: var(--tx-font-heading, 'Outfit', sans-serif);
  }
  .tx-blog-card-excerpt {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.65;
    margin-bottom: 18px;
  }
  .tx-blog-read-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #0D0F12;
    font-weight: 700;
    font-size: 13px;
    background: var(--tx-primary, #F5C518);
    padding: 8px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    align-self: flex-start;
  }
  .tx-blog-read-btn:hover {
    background: #e5b800;
    transform: translateX(3px);
    color: #0D0F12;
  }
</style>

<!-- ===== BLOGS HERO ===== -->
<section class="tx-blogs-hero">
  <div class="tx-container" style="position: relative; z-index: 2;">
    <div class="tx-blogs-hero-badge">
      <i class="fa-solid fa-newspaper"></i> Articles & News
    </div>
    <h1 class="tx-blogs-hero-title">
      Latest <span>Updates</span> & Industry Insights
    </h1>
    <p class="tx-blogs-hero-desc">
      Explore taxi industry trends, travel tips, and mobility insights from our expert team.
    </p>
  </div>
</section>

<!-- ===== BLOGS GRID ===== -->
<section style="background: #F8FAFC; padding: 56px 0 100px;">
  <div class="tx-container">
    @php
      $blogs = $agency->blogs_data ?? [
        [
          'id'      => 1,
          'title'   => '5 Tips for a Safe and Comfortable Taxi Ride',
          'category'=> 'Travel Tips',
          'author'  => 'David Mitchell',
          'date'    => 'Sep 04, 2026',
          'image'   => 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png',
          'excerpt' => 'Learn how to make every taxi ride a safe, comfortable, and stress-free experience with our expert tips.',
        ],
        [
          'id'      => 2,
          'title'   => 'How Electric Vehicles Are Transforming Taxi Services',
          'category'=> 'Industry News',
          'author'  => 'Sarah Johnson',
          'date'    => 'Aug 22, 2026',
          'image'   => 'assets/website_builder/Templates/Texigo_agency/about_image.png',
          'excerpt' => 'Discover how electric vehicles are reshaping modern taxi fleets and reducing operational costs.',
        ],
        [
          'id'      => 3,
          'title'   => 'Airport Transfer Guide: What to Expect From Your Cab',
          'category'=> 'Airport Travel',
          'author'  => 'Jessica Brown',
          'date'    => 'Aug 08, 2026',
          'image'   => 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png',
          'excerpt' => 'A complete guide to booking airport transfers and ensuring timely, stress-free arrivals.',
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
            : route('website-builder.templates.texigo.blog', ['id' => $blogId]);
        @endphp
        <div class="col-lg-4 col-md-6">
          <div class="tx-blog-card">
            <div class="tx-blog-card-img-wrap">
              <span class="tx-blog-category-badge">{{ $blog['category'] ?? 'Article' }}</span>
              <a href="{{ $detailUrl }}">
                <img src="{{ str_starts_with($blog['image'] ?? '', 'http') ? $blog['image'] : asset($blog['image'] ?? 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png') }}"
                     onerror="this.src='{{ asset('assets/website_builder/Templates/Texigo_agency/herobanner_image.png') }}';"
                     alt="{{ $blog['title'] ?? 'Article' }}"
                     class="tx-blog-card-img">
              </a>
            </div>
            <div class="tx-blog-card-body">
              <div>
                <div class="tx-blog-meta">
                  <span><i class="fa-solid fa-user me-1"></i>{{ $blog['author'] ?? 'Admin' }}</span>
                  <span><i class="fa-solid fa-calendar-days me-1"></i>{{ $blog['date'] ?? date('M d, Y') }}</span>
                </div>
                <h4 class="tx-blog-card-title">
                  <a href="{{ $detailUrl }}" class="text-decoration-none" style="color: inherit;">
                    {{ $blog['title'] ?? 'Blog Article Title' }}
                  </a>
                </h4>
                <p class="tx-blog-card-excerpt">
                  {{ $blog['excerpt'] ?? 'Short excerpt summarizing the key insights of this article.' }}
                </p>
              </div>
              <a href="{{ $detailUrl }}" class="tx-blog-read-btn">
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
