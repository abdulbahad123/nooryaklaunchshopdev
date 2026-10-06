@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Services Page - User Dashboard Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-layer-group text-primary me-2"></i>Edit Services Page</h3>
    <p class="text-muted small mb-0">Update services hero banner, title, subtitle, and perform CRUD operations on services grid cards.</p>
  </div>
  <a href="{{ $liveUrl ?? route('website-builder.templates.digital_agency') }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Live Services Page
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.services.update') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="template_type" value="{{ $agency->template_type ?? session('demo_template', 'digital_agency') }}">
  <input type="hidden" name="services_data_present" value="1">

  <!-- SERVICES HERO BANNER SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-heading me-2 text-primary"></i>Services Hero Banner Section</h5>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label small fw-semibold text-muted">Hero Badge / Tag</label>
        <input type="text" class="form-control rounded-3" name="services_badge" value="{{ $agency->services_badge ?? 'OUR SERVICES' }}" placeholder="e.g. OUR SERVICES">
      </div>
      <div class="col-md-8">
        <label class="form-label small fw-semibold text-muted">Hero Main Title</label>
        <input type="text" class="form-control rounded-3" name="services_title" value="{{ $agency->services_title ?? 'High-Impact Digital & Technology Services' }}" placeholder="e.g. High-Impact Digital Services">
      </div>
      <div class="col-12">
        <label class="form-label small fw-semibold text-muted">Hero Subtitle / Description</label>
        <textarea class="form-control rounded-3" name="services_subtitle" rows="2" placeholder="e.g. We craft bespoke digital experiences, strategic marketing, and high-performance applications.">{{ $agency->services_subtitle ?? '' }}</textarea>
      </div>
    </div>
  </div>

  <!-- SERVICES GRID CRUD SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-cubes text-success me-2"></i>Services List CRUD Manager</h5>
        <p class="text-muted small mb-0">Add, edit service titles, descriptions, icons, and upload service cover images.</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addServiceItem()">
        <i class="fa-solid fa-plus me-1"></i> Add New Service
      </button>
    </div>

    @php
      $servicesData = $agency->services_data ?? [
        ['title' => 'Web Design & Development', 'desc' => 'Custom responsive websites engineered for high speed, conversion, and brand growth.', 'icon' => 'fa-laptop-code', 'image' => 'assets/website_builder/wb_card_agency.png'],
        ['title' => 'UI/UX Mobile App Design', 'desc' => 'User-centric mobile app designs with intuitive UX journeys and sleek modern UI aesthetics.', 'icon' => 'fa-mobile-screen-button', 'image' => 'assets/website_builder/wb_card_startup.png'],
        ['title' => 'Brand Identity & Strategy', 'desc' => 'Cohesive brand identity, logo design, style guides, and positioning strategy for startups.', 'icon' => 'fa-bullhorn', 'image' => 'assets/website_builder/wb_card_portfolio.png'],
      ];
    @endphp

    <div class="row g-3" id="servicesContainer">
      @foreach($servicesData as $si => $srv)
        <div class="col-md-4 service-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-primary-subtle text-primary border border-primary fw-bold">Service #{{ $loop->iteration }}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeServiceItem(this)" title="Remove Service"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Service Title *</label>
                <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][title]" value="{{ $srv['title'] ?? '' }}" required>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Service Icon Class (FontAwesome)</label>
                <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][icon]" value="{{ $srv['icon'] ?? 'fa-cube' }}" placeholder="e.g. fa-laptop-code or fa-couch">
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Service Description</label>
                <textarea class="form-control form-control-sm" name="services_data[{{ $si }}][desc]" rows="3" placeholder="Brief description of the service">{{ $srv['desc'] ?? $srv['description'] ?? '' }}</textarea>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Upload Service Image</label>
                <input type="file" class="form-control form-control-sm" name="services_data[{{ $si }}][image_file]" accept="image/*">
                <input type="hidden" name="services_data[{{ $si }}][image]" value="{{ $srv['image'] ?? 'assets/website_builder/wb_card_agency.png' }}">
              </div>

              @if(!empty($srv['image']))
                <div class="mt-2 d-flex align-items-center gap-2 p-2 bg-white rounded border">
                  <span class="small fw-semibold text-muted">Preview:</span>
                  <img src="{{ str_starts_with($srv['image'], 'http') ? $srv['image'] : asset(ltrim($srv['image'], '/')) }}" onerror="this.src='{{ asset('assets/website_builder/wb_card_agency.png') }}';" style="height: 38px; width: 60px; object-fit: cover; border-radius: 4px;">
                </div>
              @endif
            </div>

            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-3" onclick="removeServiceItem(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Service
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save All Services Changes
  </button>
</form>

<script>
  let serviceCounter = {{ count($servicesData) }};
  function addServiceItem() {
    const container = document.getElementById('servicesContainer');
    const col = document.createElement('div');
    col.className = 'col-md-4 service-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-success-subtle text-success border border-success fw-bold">New Service</span>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeServiceItem(this)"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Service Title *</label>
            <input type="text" class="form-control form-control-sm" name="services_data[\${serviceCounter}][title]" value="New Service Title" required>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Service Icon Class (FontAwesome)</label>
            <input type="text" class="form-control form-control-sm" name="services_data[\${serviceCounter}][icon]" value="fa-cube" placeholder="e.g. fa-laptop-code">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Service Description</label>
            <textarea class="form-control form-control-sm" name="services_data[\${serviceCounter}][desc]" rows="3" placeholder="Brief description of the service"></textarea>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Upload Service Image</label>
            <input type="file" class="form-control form-control-sm" name="services_data[\${serviceCounter}][image_file]" accept="image/*">
            <input type="hidden" name="services_data[\${serviceCounter}][image]" value="assets/website_builder/wb_card_agency.png">
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-3" onclick="removeServiceItem(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Service
        </button>
      </div>
    `;
    container.appendChild(col);
    serviceCounter++;
  }

  function removeServiceItem(btn) {
    const item = btn.closest('.service-card-item');
    if (item) {
      item.remove();
    }
  }
</script>
@endsection
