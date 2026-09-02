@extends('website_builder.admin.layout')

@section('title', 'Authorized SaaS Products')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Authorized SaaS Products</h3>
      <p class="text-muted small mb-0">Enable or configure white-label products for your client network.</p>
    </div>
  </div>

  <div class="row g-4">
    <!-- Website Builder Product Card (Ref Image 2 Match) -->
    <div class="col-md-6">
      <div class="card p-4 h-100 shadow-sm border-primary">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="d-flex align-items-center gap-3">
            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 fs-3">
              <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
              <h4 class="fw-bold mb-0">Website Builder</h4>
              <span class="badge bg-success rounded-pill px-3 py-1 mt-1">Active</span>
            </div>
          </div>
        </div>

        <p class="text-muted small mb-3">Multi-page portfolio and corporate website builder SaaS engine.</p>

        <div class="bg-light p-3 rounded-3 mb-4 border">
          <label class="form-label small text-muted fw-bold mb-1">Subdomain App Launch URL:</label>
          <div class="fw-bold text-primary text-break">
            <i class="fa-solid fa-globe me-1"></i> {{ $product['launch_url'] }}
          </div>
        </div>

        <div class="d-flex align-items-center gap-3 mt-auto">
          <a href="{{ $product['preview_url'] }}" target="_blank" class="btn btn-outline-secondary flex-fill fw-bold">
            <i class="fa-solid fa-eye me-1"></i> Live Preview
          </a>
          <a href="{{ route('website-builder.admin.customers.index') }}" class="btn btn-primary flex-fill fw-bold">
            <i class="fa-solid fa-user-shield me-1"></i> Admin Access
          </a>
        </div>
      </div>
    </div>

    <!-- AI Engines Card (Ref Image 2 Match) -->
    <div class="col-md-6">
      <div class="card p-4 h-100 shadow-sm">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="p-3 bg-purple bg-opacity-10 text-purple rounded-3 fs-3" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
            <i class="fa-solid fa-microchip"></i>
          </div>
          <div>
            <h4 class="fw-bold mb-0">AI Engines & API Keys</h4>
            <span class="badge bg-secondary rounded-pill px-3 py-1 mt-1">Gemini & OpenAI</span>
          </div>
        </div>
        <p class="text-muted small mb-3">Configure your agency's Gemini and OpenAI API keys for white-label client sites.</p>
        <div class="bg-light p-3 rounded-3 mb-4 border small text-muted">
          <i class="fa-solid fa-key me-1"></i> Gemini: Default System Key | OpenAI: Default System Key
        </div>
        <button class="btn btn-primary w-100 fw-bold mt-auto" style="background: #8b5cf6; border-color: #8b5cf6;">
          <i class="fa-solid fa-sliders me-1"></i> Configure AI API Keys
        </button>
      </div>
    </div>
  </div>
@endsection
