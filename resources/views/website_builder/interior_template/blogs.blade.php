@extends('website_builder.interior_template.layout')

@section('title', 'Articles & Blog - ' . ($interior->site_title ?? 'InterioCRAFT'))

@section('content')
@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');

  $blogs = ($agency ?? $interior)->blogs_data ?? [
    [
      'id'       => 1,
      'title'    => '10 Simple Ways to Make Your Home Look Expensive',
      'category' => 'Interior Tips',
      'author'   => 'Emma Carter',
      'date'     => 'Sep 12, 2024',
      'image'    => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=800&auto=format&fit=crop',
      'excerpt'  => 'Transform your space with these easy and affordable interior design tips that instantly elevate your home.',
    ],
    [
      'id'       => 2,
      'title'    => 'Top Interior Design Trends for 2025',
      'category' => 'Design Trends',
      'author'   => 'Daniel Lee',
      'date'     => 'Aug 28, 2024',
      'image'    => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=800&auto=format&fit=crop',
      'excerpt'  => 'Explore the latest design trends that are shaping modern interiors this year.',
    ],
    [
      'id'       => 3,
      'title'    => 'How to Maximize Small Spaces with Smart Design',
      'category' => 'Space Planning',
      'author'   => 'Sofia Martinez',
      'date'     => 'Aug 15, 2024',
      'image'    => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?q=80&w=800&auto=format&fit=crop',
      'excerpt'  => 'Practical ideas to make the most of your space without compromising style.',
    ],
  ];
@endphp

<style>
  .ic-blogs-hero { background: #F7F7F5; padding: 72px 0 48px; text-align: center; }
  .ic-blogs-hero h1 { font-size: clamp(28px, 4vw, 46px); font-weight: 800; color: #1a1a1a; margin-bottom: 14px; line-height: 1.2; }
  .ic-blogs-hero p { font-size: 17px; color: #666; max-width: 580px; margin: 0 auto; }
  .ic-blog-card { background: #fff; border-radius: 18px; overflow: hidden; border: 1px solid #ede9e4; box-shadow: 0 4px 18px rgba(0,0,0,0.04); transition: transform 0.3s ease, box-shadow 0.3s ease; height: 100%; display: flex; flex-direction: column; }
  .ic-blog-card:hover { transform: translateY(-6px); box-shadow: 0 20px 42px rgba(0,0,0,0.09); }
  .ic-blog-card-img { width: 100%; height: 230px; object-fit: cover; transition: transform 0.5s ease; }
  .ic-blog-card:hover .ic-blog-card-img { transform: scale(1.05); }
  .ic-blog-card-img-wrap { position: relative; overflow: hidden; height: 230px; background: #111; }
  .ic-blog-cat-badge { position: absolute; top: 14px; left: 14px; background: var(--ic-primary, #8B6F47); color: #fff; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 30px; }
  .ic-blog-card-body { padding: 24px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
  .ic-blog-card-title { font-size: 19px; font-weight: 800; color: #1a1a1a; margin-bottom: 10px; line-height: 1.35; }
  .ic-blog-card-excerpt { font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 18px; flex-grow: 1; }
  .ic-btn-read { color: var(--ic-primary, #8B6F47); font-weight: 700; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease; }
  .ic-btn-read:hover { color: #6a5035; transform: translateX(4px); }
  .ic-blog-meta { font-size: 12px; color: #999; font-weight: 600; margin-bottom: 10px; display: flex; gap: 14px; flex-wrap: wrap; }
</style>

{{-- HERO --}}
<section class="ic-blogs-hero">
  <div class="ic-container">
    <span class="ic-pill-badge mb-3"><i class="fa-solid fa-newspaper me-1"></i> Our Blog & Insights</span>
    <h1>Latest Articles & Design Ideas</h1>
    <p>Get inspired with expert tips, trends, and ideas to create beautiful spaces.</p>
  </div>
</section>

{{-- BLOGS GRID --}}
<section style="background:#fff; padding:50px 0 120px;">
  <div class="ic-container">
    @if(empty($blogs))
      <div class="text-center py-5 text-muted">
        <i class="fa-solid fa-newspaper fs-1 mb-3 d-block opacity-25"></i>
        <p class="fw-semibold">No articles published yet. Check back soon!</p>
      </div>
    @else
      <div class="row g-4">
        @foreach($blogs as $bi => $blog)
          @php
            $blogId   = $blog['id'] ?? ($bi + 1);
            $blogUrl  = $subdomainParam
              ? route('website-builder.subdomain.blog', ['subdomain' => $subdomainParam, 'id' => $blogId])
              : route('website-builder.templates.interior.blog', ['id' => $blogId]);
            $imgSrc   = str_starts_with($blog['image'] ?? '', 'http') ? $blog['image'] : asset($blog['image'] ?? 'assets/website_builder/Templates/Interior_agency/homepage_hero.png');
          @endphp
          <div class="col-lg-4 col-md-6">
            <div class="ic-blog-card">
              <div class="ic-blog-card-img-wrap">
                <span class="ic-blog-cat-badge">{{ $blog['category'] ?? 'Article' }}</span>
                <a href="{{ $blogUrl }}">
                  <img src="{{ $imgSrc }}"
                       onerror="this.src='{{ asset('assets/website_builder/Templates/Interior_agency/homepage_hero.png') }}';"
                       alt="{{ $blog['title'] ?? 'Article' }}"
                       class="ic-blog-card-img">
                </a>
              </div>
              <div class="ic-blog-card-body">
                <div>
                  <div class="ic-blog-meta">
                    <span><i class="fa-solid fa-user me-1" style="color:var(--ic-primary,#8B6F47);"></i>{{ $blog['author'] ?? 'Admin' }}</span>
                    <span><i class="fa-solid fa-calendar-days me-1" style="color:var(--ic-primary,#8B6F47);"></i>{{ $blog['date'] ?? date('M d, Y') }}</span>
                  </div>
                  <h4 class="ic-blog-card-title">
                    <a href="{{ $blogUrl }}" class="text-decoration-none text-dark">{{ $blog['title'] ?? 'Blog Article Title' }}</a>
                  </h4>
                  <p class="ic-blog-card-excerpt">{{ $blog['excerpt'] ?? '' }}</p>
                </div>
                <a href="{{ $blogUrl }}" class="ic-btn-read">
                  Read Article <i class="fa-solid fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
@endsection
