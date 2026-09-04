@extends('website_builder.agency_template.layout')

@section('title', ($blog['title'] ?? 'Blog Article') . ' - ' . ($agency->site_title ?? 'DesignAGENCY'))

@section('content')
<!-- ===== BLOG ARTICLE HERO HEADER ===== -->
<section style="background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%); padding: 75px 0 50px;">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-9">
        <div class="agency-label-pill mx-auto mb-3" style="background: #ECFDF5; color: #059669; border-radius: 30px; padding: 6px 20px;">
          <i class="fa-solid fa-bookmark me-1"></i> {{ $blog['category'] ?? 'Article' }}
        </div>
        <h1 class="agency-heading fw-extrabold mb-4" style="font-size: clamp(30px, 4.5vw, 48px); line-height: 1.25; color: #0F172A;">
          {{ $blog['title'] ?? 'Blog Article Title' }}
        </h1>
        <div class="d-flex align-items-center justify-content-center gap-4 text-muted small flex-wrap">
          <span class="fw-semibold"><i class="fa-solid fa-user text-success me-1"></i> By {{ $blog['author'] ?? 'Admin' }}</span>
          <span class="fw-semibold"><i class="fa-solid fa-calendar-days text-success me-1"></i> Published on {{ $blog['date'] ?? date('M d, Y') }}</span>
          <span class="fw-semibold"><i class="fa-solid fa-clock text-success me-1"></i> 5 min read</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== BLOG COVER IMAGE & CONTENT BODY ===== -->
<section style="padding: 40px 0 90px; background: #FFFFFF;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <!-- Featured Image -->
        <div class="position-relative rounded-4 overflow-hidden shadow-lg mb-5" style="max-height: 480px; background: #0F172A;">
          <img src="{{ str_starts_with($blog['image'] ?? '', 'http') ? $blog['image'] : asset($blog['image'] ?? 'assets/website_builder/wb_card_agency.png') }}"
               onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=800&auto=format&fit=crop';"
               alt="{{ $blog['title'] ?? 'Article Cover' }}"
               style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        <!-- Excerpt Highlight Card -->
        @if(!empty($blog['excerpt']))
          <div class="p-4 rounded-4 mb-5 border-start border-4 border-success shadow-sm" style="background: #F0FDF4;">
            <p class="fs-5 text-slate-800 fst-italic mb-0" style="line-height: 1.6;">
              "{{ $blog['excerpt'] }}"
            </p>
          </div>
        @endif

        <!-- Main Body Content -->
        <div class="blog-article-content text-slate-700 mb-5" style="font-size: 17px; line-height: 1.85; letter-spacing: -0.2px;">
          {!! nl2br(e($blog['content'] ?? 'No article content available.')) !!}
        </div>

        <!-- Share & Back Navigation -->
        <div class="pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
          <a href="{{ isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency') }}" class="btn btn-outline-success fw-bold rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Home Page
          </a>
          <a href="{{ isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.contact', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency.contact') }}" class="btn btn-success fw-bold rounded-pill px-4 text-white">
            Get in Touch <i class="fa-solid fa-arrow-right ms-2"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
