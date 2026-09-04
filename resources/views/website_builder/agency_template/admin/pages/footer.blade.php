@extends('website_builder.agency_template.admin.layout')

@section('title', 'Footer & Branding - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-sliders text-indigo me-2" style="color: #4F46E5;"></i>Footer & Branding</h3>
    <p class="text-muted small mb-0">Manage site title, announcement bar, support email, phone, address, and footer description.</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <!-- DASHBOARD LOGO & BRANDING CARD (Task 5 Match) -->
  <div class="card card-editor p-4 mb-4" style="border-left: 4px solid #10B981;">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-paintbrush text-success me-2"></i>Dashboard & Site Logo Upload</h5>
    <div class="row g-3 align-items-center">
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Upload Logo Image (PNG, JPG, SVG)</label>
        <input type="file" class="form-control" name="site_logo_file" accept="image/*">
        <div class="form-text small text-muted">Upload a custom logo to display on your Admin Dashboard and Live Website header.</div>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Or Enter Logo Image URL</label>
        <input type="text" class="form-control" name="site_logo" value="{{ $agency->site_logo ?? '' }}" placeholder="e.g. assets/website_builder/logo.png">
      </div>
      @if(isset($agency->site_logo) && !empty($agency->site_logo))
      <div class="col-md-12">
        <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
          <span class="small fw-bold text-muted">Current Active Logo:</span>
          <img src="{{ asset($agency->site_logo) }}" alt="Current Logo" style="max-height: 50px; max-width: 200px; object-fit: contain;">
        </div>
      </div>
      @endif
    </div>
  </div>

  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-globe text-success me-2"></i>Header & Contact Info</h5>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Site Brand Name</label>
        <input type="text" class="form-control" name="site_title" value="{{ $agency->site_title ?? 'DesignAGENCY' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Top Announcement Text</label>
        <input type="text" class="form-control" name="top_announcement" value="{{ $agency->top_announcement ?? 'We help businesses grow with creative digital solutions.' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Support Email</label>
        <input type="email" class="form-control" name="email" value="{{ $agency->email ?? 'info@designagency.com' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Support Phone</label>
        <input type="text" class="form-control" name="phone" value="{{ $agency->phone ?? '+1 (234) 567-890' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Office Address (Map Location)</label>
        <input type="text" class="form-control" id="officeAddressInput" name="address" value="{{ $agency->address ?? '123 Design Street, Creative City, CA 90403' }}" onchange="updateMapPreview(this.value)">
      </div>
      <div class="col-md-12 mt-3">
        <label class="form-label fw-semibold small text-success"><i class="fa-solid fa-map-location-dot me-1"></i> Live Google Map Location Preview</label>
        <div class="border rounded-3 overflow-hidden" style="height: 250px;">
          <iframe id="mapPreviewIframe" width="100%" height="250" frameborder="0" style="border:0;"
            src="https://maps.google.com/maps?width=100%25&amp;height=250&amp;hl=en&amp;q={{ urlencode($agency->address ?? '123 Design Street, Creative City, CA 90403') }}&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
            allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
      <div class="col-md-12">
        <label class="form-label fw-semibold small">Footer Description Text</label>
        <textarea class="form-control" name="footer_text" rows="3">{{ $agency->footer_text ?? 'We are a creative digital agency helping businesses grow with modern design, development & marketing solutions.' }}</textarea>
      </div>
    </div>
  </div>

  <script>
    function updateMapPreview(address) {
      const iframe = document.getElementById('mapPreviewIframe');
      if (iframe && address) {
        iframe.src = 'https://maps.google.com/maps?width=100%25&height=250&hl=en&q=' + encodeURIComponent(address) + '&t=&z=14&ie=UTF8&iwloc=B&output=embed';
      }
    }
  </script>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save Footer & Branding
  </button>
</form>
@endsection
