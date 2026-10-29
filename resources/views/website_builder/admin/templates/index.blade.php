@extends('website_builder.admin.layout')

@section('title', 'Template Engine & Catalog')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Website Builder Templates</h3>
      <p class="text-muted small mb-0">Total active templates count & catalog overview.</p>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-5 col-lg-4">
      <div class="card p-3 d-flex flex-row align-items-center gap-3">
        <div class="p-3 bg-primary text-white rounded-3 fs-3"><i class="fa-solid fa-layer-group"></i></div>
        <div>
          <h6 class="text-muted small mb-0">Total Templates Registered</h6>
          <h3 class="fw-bold mb-0">{{ $totalCount }}</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="card p-4">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Template Name</th>
            <th>Category</th>
            <th>Demo & Admin Access</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($templates as $t)
            <tr>
              <td>
                <span class="fw-bold">{{ $t->name }}</span>
                <span class="badge bg-success-subtle text-success ms-1" style="font-size: 11px; font-weight: 700;">Included</span>
              </td>
              <td><span class="badge bg-secondary">{{ $t->category }}</span></td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  @php
                    $demoLink = route('website-builder.templates.' . ($t->slug ?? 'digital_agency'));
                  @endphp
                  <a href="{{ $demoLink }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 12px;"><i class="fa-solid fa-external-link me-1"></i> Live Demo</a>
                  <a href="{{ route('website-builder.demo-admin', ['template' => $t->slug ?? 'digital_agency']) }}" target="_blank" class="btn btn-sm btn-success py-1 px-2 text-white fw-bold" style="font-size: 12px;"><i class="fa-solid fa-user-shield me-1"></i> Admin</a>
                </div>
              </td>
              <td>
                <span class="badge {{ $t->is_active ? 'bg-success' : 'bg-danger' }}">{{ $t->is_active ? 'Active' : 'Disabled' }}</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">No website builder templates registered yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection