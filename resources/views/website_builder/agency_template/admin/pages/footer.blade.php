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

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST">
  @csrf

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
        <label class="form-label fw-semibold small">Office Address</label>
        <input type="text" class="form-control" name="address" value="{{ $agency->address ?? '123 Design Street, Creative City, CA 90403' }}">
      </div>
      <div class="col-md-12">
        <label class="form-label fw-semibold small">Footer Description Text</label>
        <textarea class="form-control" name="footer_text" rows="3">{{ $agency->footer_text ?? 'We are a creative digital agency helping businesses grow with modern design, development & marketing solutions.' }}</textarea>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save Footer & Branding
  </button>
</form>
@endsection
