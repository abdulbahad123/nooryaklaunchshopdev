@extends('website_builder.admin.layout')

@section('title', 'Dynamic Landing Content & Colors Editor')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Landing Page Content & Color Editor</h3>
      <p class="text-muted small mb-0">Dynamic handling of text, titles, call-to-actions, and brand colors.</p>
    </div>
  </div>

  <form action="{{ route('website-builder.admin.landing-settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card p-4 mb-4">
      <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-palette me-2"></i> Brand Theme & Colors</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-bold">Primary Brand Color</label>
          <div class="input-group">
            <input type="color" class="form-control form-control-color" name="primary_color" value="{{ $settings->primary_color }}">
            <input type="text" class="form-control" value="{{ $settings->primary_color }}" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold">Secondary Accent Color</label>
          <div class="input-group">
            <input type="color" class="form-control form-control-color" name="secondary_color" value="{{ $settings->secondary_color }}">
            <input type="text" class="form-control" value="{{ $settings->secondary_color }}" readonly>
          </div>
        </div>
      </div>
    </div>

    <div class="card p-4 mb-4">
      <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-heading me-2"></i> Hero Banner & Media Settings</h5>
      <div class="row g-3">
        <div class="col-md-12">
          <label class="form-label fw-bold">Hero Badge Text</label>
          <input type="text" class="form-control" name="hero_badge" value="{{ $settings->hero_badge }}" required>
        </div>
        <div class="col-md-12">
          <label class="form-label fw-bold">Hero Main Title</label>
          <input type="text" class="form-control" name="hero_title" value="{{ $settings->hero_title }}" required>
        </div>
        <div class="col-md-12">
          <label class="form-label fw-bold">Hero Subtitle</label>
          <textarea class="form-control" name="hero_subtitle" rows="3" required>{{ $settings->hero_subtitle }}</textarea>
        </div>
        <div class="col-md-12">
          <label class="form-label fw-bold">Upload Hero Graphic / Mockup Image</label>
          <input type="file" class="form-control" name="hero_image_file" accept="image/*">
          @if($settings->hero_image)
            <div class="mt-2">
              <span class="small text-muted me-2">Current Hero Image:</span>
              <img src="{{ asset($settings->hero_image) }}" style="height: 60px; border-radius: 8px; border: 1px solid #ccc;">
            </div>
          @endif
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold">Primary CTA Text</label>
          <input type="text" class="form-control" name="cta_primary_text" value="{{ $settings->cta_primary_text }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold">Primary CTA Target URL</label>
          <input type="text" class="form-control" name="cta_primary_url" value="{{ $settings->cta_primary_url }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold">Secondary CTA Text</label>
          <input type="text" class="form-control" name="cta_secondary_text" value="{{ $settings->cta_secondary_text }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold">Secondary CTA Target URL</label>
          <input type="text" class="form-control" name="cta_secondary_url" value="{{ $settings->cta_secondary_url }}" required>
        </div>
      </div>
    </div>

    <div class="card p-4 mb-4">
      <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-envelope me-2"></i> Support & Contact Info</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-bold">Support Email</label>
          <input type="email" class="form-control" name="contact_email" value="{{ $settings->contact_email }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold">Support Phone</label>
          <input type="text" class="form-control" name="contact_phone" value="{{ $settings->contact_phone }}">
        </div>
        <div class="col-md-12">
          <label class="form-label fw-bold">Office Address</label>
          <input type="text" class="form-control" name="contact_address" value="{{ $settings->contact_address }}">
        </div>
        <div class="col-md-12">
          <label class="form-label fw-bold">Footer Sub-Text</label>
          <textarea class="form-control" name="footer_text" rows="2">{{ $settings->footer_text }}</textarea>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg fw-bold"><i class="fa-solid fa-save me-2"></i> Save Dynamic Changes</button>
  </form>
@endsection
