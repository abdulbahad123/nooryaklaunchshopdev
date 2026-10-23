@extends('website_builder.admin.layout')

@section('title', 'Website Builder Limits | Super Admin')

@section('content')
<style>
  .limit-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    transition: all 0.25s ease;
  }
  .stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
  }
</style>

<div class="mb-4">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
      <h3 class="fw-bold mb-1"><i class="fa-solid fa-sliders text-primary me-2"></i>Website Builder Feature Limits</h3>
      <p class="text-muted small mb-0">Configure platform-wide default limits and customize limits for specific white-label agencies.</p>
    </div>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i> Limit Warning / Action Not Allowed:</div>
    <ul class="mb-0 small ps-3">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

{{-- CARD 1: GLOBAL PLATFORM DEFAULTS (APPLIES TO ALL WHITE-LABELS) --}}
<div class="limit-card p-4 mb-4">
  <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
    <div class="d-flex align-items-center gap-2">
      <div class="p-2 rounded-3 text-white" style="background: #4F46E5;"><i class="fa-solid fa-globe"></i></div>
      <div>
        <h5 class="fw-bold mb-0">Platform Default Limits (All White-labels)</h5>
        <small class="text-muted">These default limits automatically apply to every white-label using Website Builder</small>
      </div>
    </div>
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">
      Global Active
    </span>
  </div>

  <form action="{{ route('website-builder.admin.limits.update-defaults') }}" method="POST">
    @csrf
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-bold small text-muted">DEFAULT SERVICES LIMIT</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i class="fa-solid fa-briefcase text-primary"></i></span>
          <input type="number" name="default_services_limit" class="form-control fw-bold wb-limit-check" data-max="100" data-field="Default services" value="{{ min(100, (int)old('default_services_limit', $defaultServices)) }}" min="1" max="100" required>
        </div>
        <small class="text-muted d-block mt-1">Standard default: 100 Services (Max allowed: <strong class="text-primary">100</strong>)</small>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-bold small text-muted">DEFAULT PORTFOLIO LIMIT</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i class="fa-solid fa-images text-success"></i></span>
          <input type="number" name="default_portfolio_limit" class="form-control fw-bold wb-limit-check" data-max="100" data-field="Default portfolio" value="{{ min(100, (int)old('default_portfolio_limit', $defaultPortfolio)) }}" min="1" max="100" required>
        </div>
        <small class="text-muted d-block mt-1">Standard default: 100 Projects (Max allowed: <strong class="text-primary">100</strong>)</small>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-bold small text-muted">DEFAULT BLOG ARTICLES LIMIT</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i class="fa-solid fa-newspaper text-warning"></i></span>
          <input type="number" name="default_blog_limit" class="form-control fw-bold wb-limit-check" data-max="300" data-field="Default blog articles" value="{{ min(300, (int)old('default_blog_limit', $defaultBlogs)) }}" min="1" max="300" required>
        </div>
        <small class="text-muted d-block mt-1">Standard default: 300 Articles (Max allowed: <strong class="text-primary">300</strong>)</small>
      </div>
    </div>

    <div class="mt-4 text-end">
      <button type="submit" class="btn btn-primary px-4 fw-bold rounded-3">
        <i class="fa-solid fa-floppy-disk me-1"></i> Save Platform Default Limits
      </button>
    </div>
  </form>
</div>

{{-- CARD 2: SPECIFIC WHITE-LABEL CUSTOM LIMIT OVERRIDES --}}
<div class="limit-card p-4 mb-4">
  <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
    <div class="d-flex align-items-center gap-2">
      <div class="p-2 rounded-3 text-white" style="background: #059669;"><i class="fa-solid fa-building-user"></i></div>
      <div>
        <h5 class="fw-bold mb-0">Custom Limits for Specific White-label</h5>
        <small class="text-muted">Select a white-label from the dropdown to assign extra / dedicated limits</small>
      </div>
    </div>
  </div>

  <div class="mb-4">
    <label class="form-label fw-bold small text-muted">SELECT WHITE-LABEL CLIENT</label>
    <select class="form-select form-select-lg fw-bold" id="whitelabelSelector" onchange="window.location.href = '{{ route('website-builder.admin.limits.index') }}?whitelabel_id=' + this.value">
      <option value="">-- Choose a White-label Agency --</option>
      @foreach($whitelabels as $wl)
        <option value="{{ $wl->id }}" {{ (isset($selectedWhitelabel) && $selectedWhitelabel->id == $wl->id) ? 'selected' : '' }}>
          {{ $wl->company_name ?: ($wl->name ?: 'Agency #' . $wl->id) }} &mdash; Subdomain: {{ $wl->subdomain ?? 'N/A' }} ({{ $wl->email }})
        </option>
      @endforeach
    </select>
  </div>

  @if($selectedWhitelabel)
    @php
      $extraServ = (int)($selectedWhitelabel->extra_services_limit ?? 0);
      $extraPort = (int)($selectedWhitelabel->extra_portfolio_limit ?? 0);
      $extraBlog = (int)($selectedWhitelabel->extra_blog_limit ?? 0);
      $totServ = $selectedWhitelabel->services_limit;
      $totPort = $selectedWhitelabel->portfolio_limit;
      $totBlog = $selectedWhitelabel->blog_limit;
    @endphp

    <div class="p-3 mb-4 rounded-4" style="background: #f8fafc; border: 1.5px solid #e2e8f0;">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
          <h6 class="fw-bold mb-0 text-dark">Selected: {{ $selectedWhitelabel->company_name ?: $selectedWhitelabel->name }}</h6>
          <small class="text-muted">Subdomain: <code>{{ $selectedWhitelabel->subdomain }}</code> &bull; Email: {{ $selectedWhitelabel->email }}</small>
        </div>
        <div class="d-flex gap-2">
          <span class="stat-badge bg-info-subtle text-info">Total Services: <strong>{{ $totServ }}</strong></span>
          <span class="stat-badge bg-success-subtle text-success">Total Portfolio: <strong>{{ $totPort }}</strong></span>
          <span class="stat-badge bg-warning-subtle text-warning">Total Blogs: <strong>{{ $totBlog }}</strong></span>
        </div>
      </div>

      <form action="{{ route('website-builder.admin.limits.update-whitelabel') }}" method="POST">
        @csrf
        <input type="hidden" name="whitelabel_id" value="{{ $selectedWhitelabel->id }}">

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-bold small text-muted">EXTRA SERVICES LIMIT</label>
            <div class="input-group">
              <span class="input-group-text bg-white">+</span>
              <input type="number" name="extra_services_limit" class="form-control fw-bold wb-limit-check" data-max="100" data-field="Extra services" value="{{ min(100, (int)old('extra_services_limit', $extraServ)) }}" min="0" max="100" required>
            </div>
            <small class="text-muted d-block mt-1">Base default ({{ $defaultServices }}) + Extra = Total (Max extra: <strong class="text-primary">100</strong>)</small>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold small text-muted">EXTRA PORTFOLIO LIMIT</label>
            <div class="input-group">
              <span class="input-group-text bg-white">+</span>
              <input type="number" name="extra_portfolio_limit" class="form-control fw-bold wb-limit-check" data-max="100" data-field="Extra portfolio" value="{{ min(100, (int)old('extra_portfolio_limit', $extraPort)) }}" min="0" max="100" required>
            </div>
            <small class="text-muted d-block mt-1">Base default ({{ $defaultPortfolio }}) + Extra = Total (Max extra: <strong class="text-primary">100</strong>)</small>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold small text-muted">EXTRA BLOG ARTICLES LIMIT</label>
            <div class="input-group">
              <span class="input-group-text bg-white">+</span>
              <input type="number" name="extra_blog_limit" class="form-control fw-bold wb-limit-check" data-max="300" data-field="Extra blog articles" value="{{ min(300, (int)old('extra_blog_limit', $extraBlog)) }}" min="0" max="300" required>
            </div>
            <small class="text-muted d-block mt-1">Base default ({{ $defaultBlogs }}) + Extra = Total (Max extra: <strong class="text-primary">300</strong>)</small>
          </div>
        </div>

        <div class="mt-4 text-end">
          <button type="submit" class="btn btn-success px-4 fw-bold rounded-3">
            <i class="fa-solid fa-check me-1"></i> Update Limits for this White-label
          </button>
        </div>
      </form>
    </div>
  @endif

  {{-- DIRECTORY TABLE OF ALL WHITE-LABELS AND CURRENT LIMITS --}}
  <h6 class="fw-bold mb-3 mt-4 text-muted small text-uppercase">White-labels Overview & Limits Directory</h6>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr class="small text-uppercase text-muted">
          <th>White-label Agency</th>
          <th>Subdomain</th>
          <th>Services Limit</th>
          <th>Portfolio Limit</th>
          <th>Blog Limit</th>
          <th class="text-end">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($whitelabels as $item)
          <tr>
            <td>
              <div class="fw-bold text-dark">{{ $item->company_name ?: ($item->name ?: 'Agency #' . $item->id) }}</div>
              <small class="text-muted">{{ $item->email }}</small>
            </td>
            <td>
              <span class="badge bg-light text-dark border font-monospace">{{ $item->subdomain ?? 'N/A' }}</span>
            </td>
            <td>
              <span class="fw-bold text-primary">{{ $item->services_limit }}</span>
              @if(!empty($item->extra_services_limit))
                <small class="text-muted">(+{{ $item->extra_services_limit }} extra)</small>
              @endif
            </td>
            <td>
              <span class="fw-bold text-success">{{ $item->portfolio_limit }}</span>
              @if(!empty($item->extra_portfolio_limit))
                <small class="text-muted">(+{{ $item->extra_portfolio_limit }} extra)</small>
              @endif
            </td>
            <td>
              <span class="fw-bold text-warning">{{ $item->blog_limit }}</span>
              @if(!empty($item->extra_blog_limit))
                <small class="text-muted">(+{{ $item->extra_blog_limit }} extra)</small>
              @endif
            </td>
            <td class="text-end">
              <a href="{{ route('website-builder.admin.limits.index', ['whitelabel_id' => $item->id]) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-bold">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Limit
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center py-4 text-muted">No white-label agencies found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.wb-limit-check').forEach(function(input) {
    input.addEventListener('input', function() {
      var max = parseInt(this.getAttribute('data-max') || '100', 10);
      var field = this.getAttribute('data-field') || 'Feature';
      var val = parseInt(this.value, 10);
      if (!isNaN(val) && val > max) {
        alert('⚠️ Limit Warning: Not able to give that limit!\n\n' + field + ' limit cannot exceed the maximum allowed limit of ' + max + '. Value has been adjusted to ' + max + '.');
        this.value = max;
      }
    });
  });
});
</script>
@endsection
