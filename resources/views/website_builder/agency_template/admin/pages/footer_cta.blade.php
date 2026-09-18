@extends('website_builder.agency_template.admin.layout')

@section('title', 'Footer CTA Banner — Admin Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-extrabold text-dark mb-1">Footer CTA Banner Settings</h3>
    <p class="text-secondary small mb-0">Manage the full-width Call-To-Action banner displayed right above the footer across all themes.</p>
  </div>
  @if(isset($customer) && !empty($customer->subdomain))
    <a href="{{ route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
      <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Site
    </a>
  @endif
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-bullhorn text-primary me-2"></i> CTA Banner Content</h5>
    
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Top Badge / Tagline</label>
        <input type="text" name="cta_banner_badge" class="form-control" value="{{ old('cta_banner_badge', $agency->cta_banner_badge ?? '') }}" placeholder="e.g. LET'S BUILD TOGETHER / LET'S RIDE TOGETHER">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Main CTA Title</label>
        <input type="text" name="cta_banner_title" class="form-control" value="{{ old('cta_banner_title', $agency->cta_banner_title ?? '') }}" placeholder="e.g. Ready to Build Your Vision? / Ready to Book Your Next Ride?">
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">CTA Subtitle / Description</label>
        <textarea name="cta_banner_subtitle" rows="3" class="form-control" placeholder="From concept to completion, we are here to bring your ideas to life.">{{ old('cta_banner_subtitle', $agency->cta_banner_subtitle ?? '') }}</textarea>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Button Text</label>
        <input type="text" name="cta_banner_btn_text" class="form-control" value="{{ old('cta_banner_btn_text', $agency->cta_banner_btn_text ?? '') }}" placeholder="e.g. Get a Quote / Book Now / Contact Us">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Button URL</label>
        <input type="text" name="cta_banner_btn_url" class="form-control" value="{{ old('cta_banner_btn_url', $agency->cta_banner_btn_url ?? '') }}" placeholder="e.g. /contact or tel:9360157880">
      </div>

      <div class="col-12 mt-4">
        <label class="form-label fw-semibold">CTA Background Image</label>
        @if(!empty($agency->cta_banner_image))
          <div class="mb-2">
            <img src="{{ str_starts_with($agency->cta_banner_image, 'http') ? $agency->cta_banner_image : asset(ltrim($agency->cta_banner_image, '/')) }}" alt="CTA Background" class="rounded-3 shadow-sm" style="max-height: 120px; object-fit: cover;">
          </div>
        @endif
        <input type="file" name="cta_banner_image_file" class="form-control" accept="image/*">
        <input type="hidden" name="cta_banner_image" value="{{ $agency->cta_banner_image ?? '' }}">
        <div class="form-text">Upload a high quality background image for the CTA banner.</div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
      <i class="fa-solid fa-floppy-disk me-2"></i> Save Footer CTA Banner
    </button>
  </div>
</form>
@endsection
