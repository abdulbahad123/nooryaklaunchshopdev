@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Portfolio Page - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-briefcase text-indigo me-2" style="color: #4F46E5;"></i>Edit Portfolio Page</h3>
    <p class="text-muted small mb-0">Update portfolio hero badge, projects grid items, category tags, and cover images.</p>
  </div>
  <a href="{{ $liveUrl ?? $customerLiveUrl ?? route('website-builder.templates.digital_agency') }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Portfolio Page
  </a>
</div>



<form action="{{ route('website-builder.agency-admin.portfolio.update') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="template_type" value="{{ $agency->template_type ?? session('demo_template', 'digital_agency') }}">
  <input type="hidden" name="portfolio_data_present" value="1">

  <!-- PORTFOLIO HERO SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-heading me-2" style="color: #4F46E5;"></i>Portfolio Hero Section</h5>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label small fw-semibold text-muted">Hero Badge / Tag</label>
        <input type="text" class="form-control rounded-3" name="portfolio_badge" value="{{ $agency->portfolio_badge ?? 'Portfolio' }}" placeholder="e.g. Portfolio">
      </div>
      <div class="col-md-8">
        <label class="form-label small fw-semibold text-muted">Hero Main Title</label>
        <input type="text" class="form-control rounded-3" name="portfolio_title" value="{{ $agency->portfolio_title ?? 'Our Latest Work & Projects' }}" placeholder="e.g. Our Latest Work & Projects">
      </div>
      <div class="col-12">
        <label class="form-label small fw-semibold text-muted">Hero Subtitle / Description</label>
        <textarea class="form-control rounded-3" name="portfolio_subtitle" rows="2" placeholder="e.g. Explore our recent digital agency projects">{{ $agency->portfolio_subtitle ?? '' }}</textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label small fw-semibold text-muted">Hero Primary Button Text</label>
        <input type="text" class="form-control rounded-3" name="primary_btn_text" value="{{ $agency->primary_btn_text ?? 'Start Your Project' }}" placeholder="e.g. Start Your Project">
      </div>
      <div class="col-md-6">
        <label class="form-label small fw-semibold text-muted">Hero Primary Button Link / URL</label>
        <input type="text" class="form-control rounded-3" name="primary_btn_url" value="{{ $agency->primary_btn_url ?? '#contact' }}" placeholder="e.g. #contact or /contact">
      </div>
      <div class="col-12">
        <label class="form-label small fw-semibold text-muted">Portfolio Hero Banner / Background Image</label>
        <input type="file" class="form-control rounded-3" name="portfolio_hero_image_file" accept="image/*">
        <input type="hidden" name="portfolio_hero_image" value="{{ $agency->portfolio_hero_image ?? '' }}">
        @if(!empty($agency->portfolio_hero_image))
          <div class="mt-2 d-flex align-items-center gap-2 p-2 bg-white rounded border">
            <span class="small fw-semibold text-muted">Current Hero Image Preview:</span>
            <img src="{{ str_starts_with($agency->portfolio_hero_image, 'http') ? $agency->portfolio_hero_image : asset($agency->portfolio_hero_image) }}" style="height: 45px; max-width: 140px; object-fit: cover; border-radius: 6px;">
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- PORTFOLIO PROJECTS SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-grid-2 text-success me-2"></i>Portfolio Projects Gallery</h5>
        <p class="text-muted small mb-0">Add, edit titles, set category tags (e.g. Web Design, UI/UX Design, Branding, Mobile App), and upload project images.</p>
      </div>
      <div class="d-flex gap-2">
        <label class="btn btn-sm btn-outline-success fw-bold px-3 rounded-pill mb-0" style="cursor: pointer;">
          <i class="fa-solid fa-upload me-1"></i> Bulk Upload
          <input type="file" multiple accept="image/*" class="d-none" onchange="handlePortfolioBulkUpload(event)">
        </label>
        <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addPortfolio()">
          <i class="fa-solid fa-plus me-1"></i> Add Portfolio Project
        </button>
      </div>
    </div>

    @php
      $portfolioData = $agency->portfolio_data ?? [];
      if (empty($portfolioData)) {
        $currTmpl = $agency->template_type ?? session('demo_template', 'digital_agency');
        $dummyTmplAgency = \App\Models\WebsiteBuilder\WbAgencySetting::getDemoDefaults($currTmpl);
        $portfolioData = $dummyTmplAgency->portfolio_data ?? [];
      }
    @endphp

    <div class="row g-3" id="portfolioContainer">
      @foreach($portfolioData as $pi => $port)
        <div class="col-md-4 portfolio-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-success-subtle text-success border border-success fw-bold">Project #{{ $loop->iteration }}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removePortfolio(this)" title="Remove Project"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Project Title *</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][title]" value="{{ $port['title'] ?? '' }}" required>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Category Tag(s)</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][category]" value="{{ $port['category'] ?? 'Web Design' }}" placeholder="e.g. Web Design • UI/UX">
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Project Description / Details</label>
                <textarea class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][desc]" rows="2" placeholder="Brief project overview">{{ $port['desc'] ?? $port['description'] ?? '' }}</textarea>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Button Text</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][btn_text]" value="{{ $port['btn_text'] ?? 'View Project' }}" placeholder="e.g. View Project or Book Ride">
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Live Project Link URL</label>
                <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][link]" value="{{ $port['link'] ?? '#' }}" placeholder="https://example.com or #">
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
  
  function handlePortfolioBulkUpload(event) {
    const files = event.target.files;
    if (!files || files.length === 0) return;
    for(let i=0; i<files.length; i++) {
        addPortfolio(files[i]);
    }
    event.target.value = '';
  }

  function addPortfolio(file = null) {
    const container = document.getElementById('portfolioContainer');
    const col = document.createElement('div');
    col.className = 'col-md-4 portfolio-card-item';
    
    let defaultTitle = file ? file.name.split('.').slice(0, -1).join('.') : "New Project Title";

    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-success-subtle text-success border border-success fw-bold">New Project</span>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removePortfolio(this)"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Project Title *</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][title]" value="${defaultTitle}" required>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Category Tag(s)</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][category]" value="Web Design • UI/UX">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Project Description / Details</label>
            <textarea class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][desc]" rows="2" placeholder="Brief project overview"></textarea>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Button Text</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][btn_text]" value="View Project" placeholder="e.g. View Project or Book Ride">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Live Project Link URL</label>
            <input type="text" class="form-control form-control-sm" name="portfolio_data[${portfolioCounter}][link]" value="#" placeholder="https://example.com or #">
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
    
    if (file) {
        const fileInput = col.querySelector('input[type="file"]');
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
    }

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
