@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Portfolio Page - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-briefcase text-indigo me-2" style="color: #4F46E5;"></i>Edit Portfolio Page</h3>
    <p class="text-muted small mb-0">Update portfolio hero badge, projects grid items, category tags, and cover images.</p>
  </div>
  <a href="{{ $liveUrl ?? (isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.portfolio', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency.portfolio')) }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Portfolio Page
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.portfolio.update') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <!-- PORTFOLIO PROJECTS SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-grid-2 text-success me-2"></i>Portfolio Projects Gallery</h5>
        <p class="text-muted small mb-0">Add, edit titles, set category tags (e.g. Web Design, UI/UX Design, Branding, Mobile App), and upload project images.</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addPortfolio()">
        <i class="fa-solid fa-plus me-1"></i> Add Portfolio Project
      </button>
    </div>

    @php
      $portfolioData = $agency->portfolio_data ?? [
        ['title' => 'Fintech Website Redesign', 'category' => 'Web Design • UI/UX',          'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'E-commerce Website',       'category' => 'Web Design • E-commerce',      'image' => 'assets/website_builder/wb_card_ecommerce.png'],
        ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Brand Identity Design',    'category' => 'Branding • Graphic Design',   'image' => 'assets/website_builder/wb_card_portfolio.png'],
        ['title' => 'Travel Website',           'category' => 'Web Design • UI/UX',          'image' => 'assets/website_builder/wb_card_events.png'],
        ['title' => 'Fitness App Design',       'category' => 'UI/UX Design • Mobile App',   'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design • Web App',      'image' => 'assets/website_builder/wb_card_restaurant.png'],
        ['title' => 'Digital Marketing Campaign','category' => 'Marketing • Social Media',   'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'Restaurant Website',       'category' => 'Web Design • E-commerce',      'image' => 'assets/website_builder/wb_card_ecommerce.png'],
      ];
    @endphp

    <div class="row g-3" id="portfolioContainer">
      @foreach($portfolioData as $pi => $port)
        <div class="col-md-4 portfolio-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-success-subtle text-success border border-success fw-bold">Project #{{ $pi + 1 }}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removePortfolio(this)" title="Remove Project"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Project Title</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][title]" value="{{ $port['title'] ?? '' }}" required>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Category Tag(s)</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][category]" value="{{ $port['category'] ?? 'Web Design' }}" placeholder="e.g. Web Design • UI/UX">
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Upload Project Image</label>
                <input type="file" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][image_file]" accept="image/*">
                <input type="hidden" name="portfolio_data[{{ $pi }}][image]" value="{{ $port['image'] ?? 'assets/website_builder/wb_card_agency.png' }}">
              </div>

              @if(!empty($port['image']))
                <div class="mt-2 d-flex align-items-center gap-2 p-2 bg-white rounded border">
                  <span class="small fw-semibold text-muted">Preview:</span>
                  <img src="{{ str_starts_with($port['image'], 'http') ? $port['image'] : asset($port['image']) }}" onerror="this.src='{{ asset('assets/website_builder/wb_card_agency.png') }}';" style="height: 38px; width: 60px; object-fit: cover; border-radius: 4px;">
                </div>
              @endif
            </div>

            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-3" onclick="removePortfolio(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Project
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save All Portfolio Changes
  </button>
</form>

<script>
  let portfolioCounter = {{ count($portfolioData) }};
  function addPortfolio() {
    const container = document.getElementById('portfolioContainer');
    const col = document.createElement('div');
    col.className = 'col-md-4 portfolio-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-success-subtle text-success border border-success fw-bold">New Project</span>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removePortfolio(this)"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Project Title</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][title]" value="New Project Title" required>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Category Tag(s)</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][category]" value="Web Design • UI/UX">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Upload Project Image</label>
            <input type="file" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][image_file]" accept="image/*">
            <input type="hidden" name="portfolio_data[${portfolioCounter}][image]" value="assets/website_builder/wb_card_agency.png">
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-3" onclick="removePortfolio(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Project
        </button>
      </div>
    `;
    container.appendChild(col);
    portfolioCounter++;
  }

  function removePortfolio(btn) {
    const item = btn.closest('.portfolio-card-item');
    if (item) {
      item.remove();
    }
  }
</script>
@endsection
