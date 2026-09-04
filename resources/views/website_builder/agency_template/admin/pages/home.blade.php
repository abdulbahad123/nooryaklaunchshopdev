@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Home Page - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-house text-indigo me-2" style="color: #4F46E5;"></i>Edit Home Page</h3>
    <p class="text-muted small mb-0">Update Hero badge, main titles, photo graphic, 6 service cards, and 8 portfolio projects.</p>
  </div>
  <a href="{{ $liveUrl ?? (isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency')) }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Home Page
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST" enctype="multipart/form-data">
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
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Upload Hero Image File</label>
        <input type="file" class="form-control" name="hero_image_file" accept="image/*">
        <div class="form-text small text-muted">Upload a photo/graphic (PNG, JPG, WebP) to display in the Hero section.</div>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Or Hero Image Path / URL</label>
        <input type="text" class="form-control" name="hero_image" value="{{ $agency->hero_image ?? 'assets/website_builder/agency_hero_woman.png' }}">
        @if(!empty($agency->hero_image))
          <div class="mt-2 d-flex align-items-center gap-2">
            <span class="small fw-semibold text-muted">Current Preview:</span>
            <img src="{{ str_starts_with($agency->hero_image, 'http') ? $agency->hero_image : asset($agency->hero_image) }}" onerror="this.src='{{ asset('assets/website_builder/agency_hero_woman.png') }}';" style="height: 44px; width: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
          </div>
        @endif
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
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-grid-2 text-success me-2"></i>Our Services Cards</h5>
        <p class="text-muted small mb-0">Add, edit, or remove the service cards displayed on your home page.</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addService()">
        <i class="fa-solid fa-plus me-1"></i> Add Service Card
      </button>
    </div>

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

    <div class="row g-3" id="servicesContainer">
      @foreach($servicesData as $si => $srv)
        <div class="col-md-4 service-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold small text-success">Service #{{ $si + 1 }}</div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeService(this)" title="Remove Service"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">FontAwesome Icon Class</label>
                <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][icon]" value="{{ $srv['icon'] ?? 'fa-laptop-code' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Title</label>
                <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][title]" value="{{ $srv['title'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Description</label>
                <textarea class="form-control form-control-sm" name="services_data[{{ $si }}][desc]" rows="2">{{ $srv['desc'] ?? '' }}</textarea>
              </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeService(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Service
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- PORTFOLIO SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-briefcase text-success me-2"></i>Our Recent Work (Portfolio Projects)</h5>
        <p class="text-muted small mb-0">Add, edit, upload project images, or remove portfolio projects.</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addPortfolio()">
        <i class="fa-solid fa-plus me-1"></i> Add Project
      </button>
    </div>

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

    <div class="row g-3" id="portfolioContainerAdmin">
      @foreach($portfolioData as $pi => $port)
        <div class="col-md-3 portfolio-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold small text-success">Project #{{ $pi + 1 }}</div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removePortfolio(this)" title="Remove Project"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Title</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][title]" value="{{ $port['title'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Category Tag</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][category]" value="{{ $port['category'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Upload Project Image</label>
                <input type="file" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][image_file]" accept="image/*">
                <input type="hidden" name="portfolio_data[{{ $pi }}][image]" value="{{ $port['image'] ?? 'assets/website_builder/wb_card_agency.png' }}">
              </div>
              @if(!empty($port['image']))
                <div class="mt-2 d-flex align-items-center gap-2">
                  <span class="small fw-semibold text-muted">Preview:</span>
                  <img src="{{ str_starts_with($port['image'], 'http') ? $port['image'] : asset($port['image']) }}" onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=400&auto=format&fit=crop';" style="height: 38px; width: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1;">
                </div>
              @endif
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removePortfolio(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Project
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save Home Page
  </button>
</form>

<script>
  let serviceCounter = {{ count($servicesData) }};
  function addService() {
    const container = document.getElementById('servicesContainer');
    const col = document.createElement('div');
    col.className = 'col-md-4 service-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-bold small text-success">New Service</div>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeService(this)" title="Remove Service"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">FontAwesome Icon Class</label>
            <input type="text" class="form-control form-control-sm" name="services_data[${serviceCounter}][icon]" value="fa-chart-line">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Title</label>
            <input type="text" class="form-control form-control-sm" name="services_data[${serviceCounter}][title]" value="New Service Title">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Description</label>
            <textarea class="form-control form-control-sm" name="services_data[${serviceCounter}][desc]" rows="2">Custom service description details go here.</textarea>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeService(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Service
        </button>
      </div>
    `;
    container.appendChild(col);
    serviceCounter++;
  }

  function removeService(btn) {
    const cardItem = btn.closest('.service-card-item');
    if (cardItem) {
      cardItem.remove();
    }
  }

  let portfolioCounter = {{ count($portfolioData) }};
  function addPortfolio() {
    const container = document.getElementById('portfolioContainerAdmin');
    const col = document.createElement('div');
    col.className = 'col-md-3 portfolio-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-bold small text-success">New Project</div>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removePortfolio(this)" title="Remove Project"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Title</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][title]" value="New Project Title">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Category Tag</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][category]" value="Web Design">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Upload Project Image</label>
            <input type="file" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][image_file]" accept="image/*">
            <input type="hidden" name="portfolio_data[${portfolioCounter}][image]" value="assets/website_builder/wb_card_agency.png">
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removePortfolio(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Project
        </button>
      </div>
    `;
    container.appendChild(col);
    portfolioCounter++;
  }

  function removePortfolio(btn) {
    const cardItem = btn.closest('.portfolio-card-item');
    if (cardItem) {
      cardItem.remove();
    }
  }
</script>

@endsection
