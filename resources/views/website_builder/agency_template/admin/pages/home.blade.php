@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Home Page - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-house text-indigo me-2" style="color: #4F46E5;"></i>Edit Home Page</h3>
    <p class="text-muted small mb-0">Update Hero badge, main titles, photo graphic, 6 service cards, and 8 portfolio projects.</p>
  </div>
  <a href="{{ route('website-builder.templates.design-agency') }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Home Page
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST">
  @csrf

  <!-- HERO SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-wand-magic-sparkles text-success me-2"></i>Main Hero Section</h5>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Hero Label Pill</label>
        <input type="text" class="form-control" name="hero_badge" value="{{ $agency->hero_badge ?? 'Creative Digital Solutions' }}">
      </div>
      <div class="col-md-8">
        <label class="form-label fw-semibold small">Hero Main Title (use line breaks)</label>
        <textarea class="form-control" name="hero_title" rows="2">{{ $agency->hero_title ?? "Increase Your\nCustomers Loyalty\nand Satisfaction" }}</textarea>
      </div>
      <div class="col-md-12">
        <label class="form-label fw-semibold small">Hero Subtitle Paragraph</label>
        <textarea class="form-control" name="hero_subtitle" rows="2">{{ $agency->hero_subtitle ?? 'We help businesses like yours earn more customers, stand out from competitors, and grow your revenue.' }}</textarea>
      </div>
      <div class="col-md-12">
        <label class="form-label fw-semibold small">Hero Photo Graphic Asset URL</label>
        <input type="text" class="form-control" name="hero_image" value="{{ $agency->hero_image ?? 'assets/website_builder/agency_hero_woman.png' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Primary Button Text</label>
        <input type="text" class="form-control" name="primary_btn_text" value="{{ $agency->primary_btn_text ?? 'Get Started' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Primary Button Link</label>
        <input type="text" class="form-control" name="primary_btn_url" value="{{ $agency->primary_btn_url ?? '#contact' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Secondary Button Text</label>
        <input type="text" class="form-control" name="secondary_btn_text" value="{{ $agency->secondary_btn_text ?? 'View Our Work' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Secondary Button Link</label>
        <input type="text" class="form-control" name="secondary_btn_url" value="{{ $agency->secondary_btn_url ?? '#portfolio' }}">
      </div>
    </div>
  </div>

  <!-- SERVICES SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-1"><i class="fa-solid fa-grid-2 text-success me-2"></i>Our Services Grid (6 Cards)</h5>
    <p class="text-muted small mb-4">Edit the 6 service cards displayed on the home page.</p>

    @php
      $servicesData = $agency->services_data ?? [
        ['icon' => 'fa-laptop-code',     'title' => 'Web Design',       'desc' => 'Beautiful, modern, and responsive websites that drive results.'],
        ['icon' => 'fa-layer-group',     'title' => 'UI/UX Design',     'desc' => 'User-centered designs that create seamless digital experiences.'],
        ['icon' => 'fa-bezier-curve',    'title' => 'Branding',         'desc' => 'Unique brand identities that make your business memorable.'],
        ['icon' => 'fa-bullhorn',        'title' => 'Digital Marketing','desc' => 'Data-driven marketing strategies that boost your visibility.'],
        ['icon' => 'fa-magnifying-glass','title' => 'SEO Optimization', 'desc' => 'Improve your search rankings and drive organic traffic.'],
        ['icon' => 'fa-mobile-screen',   'title' => 'App Development',  'desc' => 'Powerful and scalable apps for iOS & Android platforms.'],
      ];
    @endphp

    <div class="row g-3">
      @foreach($servicesData as $si => $srv)
        <div class="col-md-4">
          <div class="border rounded-3 p-3 bg-light">
            <div class="fw-bold small text-success mb-2">Service {{ $si + 1 }}</div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">Icon Class</label>
              <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][icon]" value="{{ $srv['icon'] }}">
            </div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">Title</label>
              <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][title]" value="{{ $srv['title'] }}">
            </div>
            <div>
              <label class="form-label small fw-semibold">Description</label>
              <textarea class="form-control form-control-sm" name="services_data[{{ $si }}][desc]" rows="2">{{ $srv['desc'] }}</textarea>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- PORTFOLIO SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-1"><i class="fa-solid fa-briefcase text-success me-2"></i>Our Recent Work (8 Projects)</h5>
    <p class="text-muted small mb-4">Edit the 8 project cards displayed on the portfolio section.</p>

    @php
      $portfolioData = $agency->portfolio_data ?? [
        ['title' => 'Fintech Website Redesign', 'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'E-commerce Skincare Store', 'category' => 'Web Design',   'image' => 'assets/website_builder/wb_card_ecommerce.png'],
        ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Brand Identity Design',    'category' => 'Branding',      'image' => 'assets/website_builder/wb_card_portfolio.png'],
        ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_restaurant.png'],
        ['title' => 'Travel Website',           'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_events.png'],
        ['title' => 'Fitness App Design',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Digital Marketing Campaign','category' => 'Marketing',    'image' => 'assets/website_builder/wb_card_agency.png'],
      ];
    @endphp

    <div class="row g-3">
      @foreach($portfolioData as $pi => $port)
        <div class="col-md-3">
          <div class="border rounded-3 p-3 bg-light">
            <div class="fw-bold small text-success mb-2">Project {{ $pi + 1 }}</div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">Title</label>
              <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][title]" value="{{ $port['title'] }}">
            </div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">Category Tag</label>
              <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][category]" value="{{ $port['category'] }}">
            </div>
            <div>
              <label class="form-label small fw-semibold">Image URL</label>
              <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][image]" value="{{ $port['image'] }}">
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save Home Page
  </button>
</form>
@endsection
