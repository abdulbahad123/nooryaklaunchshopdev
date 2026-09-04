@extends('website_builder.agency_template.admin.layout')

@section('title', 'Custom Domain - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-globe text-indigo me-2" style="color: #4F46E5;"></i>Custom Domain Settings</h3>
    <p class="text-muted small mb-0">Connect your own custom domain (e.g. www.youragency.com) to your launched website.</p>
  </div>
  <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#customDomainModal">
    <i class="fa-solid fa-plus me-1"></i> Request Custom Domain
  </button>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<!-- CURRENT LIVE DOMAIN CARD -->
<div class="card card-editor p-4 mb-4" style="border-left: 4px solid #4F46E5;">
  <h5 class="fw-bold mb-2"><i class="fa-solid fa-link text-indigo me-2"></i>Current Website Address</h5>
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-3 bg-light rounded-3 border">
    <div>
      <div class="small fw-semibold text-muted mb-1">Your Default Launch Subdomain URL:</div>
      <a href="{{ $liveUrl }}" target="_blank" class="fw-bold fs-5 text-indigo text-decoration-none">
        {{ $liveUrl }} <i class="fa-solid fa-arrow-up-right-from-square ms-1 fs-6"></i>
      </a>
    </div>
    <div>
      @if(!empty($agency->custom_domain))
        <span class="badge bg-success px-3 py-2 fs-6 rounded-pill"><i class="fa-solid fa-shield-check me-1"></i> Connected: {{ $agency->custom_domain }}</span>
      @else
        <span class="badge bg-secondary px-3 py-2 fs-6 rounded-pill"><i class="fa-solid fa-clock me-1"></i> Default Subdomain Active</span>
      @endif
    </div>
  </div>
</div>

<!-- DOMAIN REQUEST STATUS TABLE -->
<div class="card card-editor p-4 mb-4">
  <h5 class="fw-bold mb-3"><i class="fa-solid fa-server text-success me-2"></i>Custom Domain Connection Requests</h5>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Requested Domain</th>
          <th>Subdomain URL</th>
          <th>Status</th>
          <th>Requested Date</th>
        </tr>
      </thead>
      <tbody>
        @if(!empty($agency->custom_domain))
          <tr>
            <td class="fw-bold text-indigo"><i class="fa-solid fa-globe me-2"></i>{{ $agency->custom_domain }}</td>
            <td><a href="{{ $liveUrl }}" target="_blank" class="text-secondary small">{{ $liveUrl }}</a></td>
            <td>
              @if(($agency->custom_domain_status ?? 0) == 1)
                <span class="badge bg-success px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Active / Connected</span>
              @elseif(($agency->custom_domain_status ?? 0) == 2)
                <span class="badge bg-danger px-3 py-2"><i class="fa-solid fa-circle-xmark me-1"></i> Request Rejected</span>
              @else
                <span class="badge bg-warning text-dark px-3 py-2"><i class="fa-solid fa-hourglass-half me-1"></i> Pending Verification</span>
              @endif
            </td>
            <td class="small text-muted">{{ $agency->updated_at ? $agency->updated_at->format('M d, Y') : date('M d, Y') }}</td>
          </tr>
        @else
          <tr>
            <td colspan="4" class="text-center py-4 text-muted">
              <i class="fa-solid fa-globe fs-2 d-block mb-2 opacity-50"></i>
              No custom domain request submitted yet. Click <strong>Request Custom Domain</strong> to connect your domain.
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>

<!-- CNAME RECORD INSTRUCTIONS CARD -->
<div class="card card-editor p-4 mb-4 bg-light border-0 shadow-sm">
  <h5 class="fw-bold text-slate-900 mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i>How to Connect Your Custom Domain (CNAME Setup)</h5>
  <p class="text-secondary small mb-3">To connect your custom domain to your website, log in to your domain provider DNS panel (e.g. GoDaddy, Namecheap, Cloudflare) and add the following CNAME record:</p>
  
  <div class="table-responsive bg-white rounded-3 border p-3">
    <table class="table table-bordered mb-0 small">
      <thead class="table-light">
        <tr>
          <th>Record Type</th>
          <th>Host / Name</th>
          <th>Points To / Value</th>
          <th>TTL</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="badge bg-primary">CNAME</span></td>
          <td><code>@</code> or <code>www</code></td>
          <td><code>cockroachjantaparty.top</code></td>
          <td>Automatic / 3600</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- REQUEST MODAL -->
<div class="modal fade" id="customDomainModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold"><i class="fa-solid fa-globe text-primary me-2"></i>Request Custom Domain</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('website-builder.agency-admin.custom-domain.submit') }}" method="POST">
        @csrf
        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label fw-semibold small">Enter Custom Domain Name</label>
            <input type="text" class="form-control" name="custom_domain" placeholder="e.g. myagency.com or www.myagency.com" required>
            <div class="form-text small text-muted mt-1">
              <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i> Do not include <strong>http://</strong> or <strong>https://</strong>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary fw-bold px-4">Submit Domain Request</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
