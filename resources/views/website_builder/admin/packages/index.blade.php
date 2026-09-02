@extends('website_builder.admin.layout')

@section('title', 'Subscription Packages')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Website Builder Subscription Tiers</h3>
      <p class="text-muted small mb-0">Configure pricing packages (Starter, Pro, Business) and feature flags.</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPackageModal"><i class="fa-solid fa-plus me-1"></i> Add Package Tier</button>
  </div>

  <div class="row g-4">
    @forelse($packages as $p)
      <div class="col-md-4">
        <div class="card p-4 h-100 position-relative">
          @if($p->is_popular)
            <span class="badge bg-primary position-absolute top-0 end-0 m-3">Most Popular</span>
          @endif
          <h4 class="fw-bold">{{ $p->name }}</h4>
          <h2 class="fw-extrabold my-2">${{ $p->monthly_price }} <span class="fs-6 text-muted font-normal">/ month</span></h2>
          <p class="text-muted small">Yearly: ${{ $p->yearly_price }} / yr</p>
          <hr>
          <ul class="list-unstyled text-muted small">
            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Max Websites: {{ $p->max_websites }}</li>
            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Storage Limit: {{ $p->storage_limit_mb }} MB</li>
            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Custom Domain: {{ $p->custom_domain_allowed ? 'Yes' : 'No' }}</li>
          </ul>
          <form action="{{ route('website-builder.admin.packages.destroy', $p->id) }}" method="POST" class="mt-auto" onsubmit="return confirm('Delete package?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger w-100 btn-sm"><i class="fa-solid fa-trash me-1"></i> Remove Tier</button>
          </form>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="card p-5 text-center text-muted">
          No subscription package tiers configured yet.
        </div>
      </div>
    @endforelse
  </div>

  <!-- Add Package Modal -->
  <div class="modal fade" id="addPackageModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('website-builder.admin.packages.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Create Subscription Tier</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Package Name</label>
              <input type="text" class="form-control" name="name" placeholder="e.g. Pro Plan" required>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label">Monthly Price ($)</label>
                <input type="number" step="0.01" class="form-control" name="monthly_price" required>
              </div>
              <div class="col-6">
                <label class="form-label">Yearly Price ($)</label>
                <input type="number" step="0.01" class="form-control" name="yearly_price" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Max Websites Allowed</label>
              <input type="number" class="form-control" name="max_websites" value="1" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create Package</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
