@extends('website_builder.admin.layout')

@section('title', 'Template Engine & Catalog')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Website Builder Templates</h3>
      <p class="text-muted small mb-0">Total active templates count & template creation manager.</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTemplateModal"><i class="fa-solid fa-plus me-1"></i> Add New Template</button>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card p-3 d-flex flex-row align-items-center gap-3">
        <div class="p-3 bg-primary text-white rounded-3 fs-3"><i class="fa-solid fa-layer-group"></i></div>
        <div>
          <h6 class="text-muted small mb-0">Total Templates Registered</h6>
          <h3 class="fw-bold mb-0">{{ $totalCount }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3 d-flex flex-row align-items-center gap-3">
        <div class="p-3 bg-success text-white rounded-3 fs-3"><i class="fa-solid fa-check-double"></i></div>
        <div>
          <h6 class="text-muted small mb-0">Active Public Templates</h6>
          <h3 class="fw-bold mb-0">{{ $activeCount }}</h3>
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
            <th>Pricing Tier</th>
            <th>Demo URL</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($templates as $t)
            <tr>
              <td><span class="fw-bold">{{ $t->name }}</span></td>
              <td><span class="badge bg-secondary">{{ $t->category }}</span></td>
              <td>{{ $t->is_free ? 'Free' : '$' . number_format($t->price, 2) }}</td>
              <td>
                @if($t->demo_url)
                  <a href="{{ $t->demo_url }}" target="_blank" class="small"><i class="fa-solid fa-external-link me-1"></i> Live Demo</a>
                @else
                  <span class="text-muted small">N/A</span>
                @endif
              </td>
              <td>
                <span class="badge {{ $t->is_active ? 'bg-success' : 'bg-danger' }}">{{ $t->is_active ? 'Active' : 'Disabled' }}</span>
              </td>
              <td>
                <form action="{{ route('website-builder.admin.templates.toggle', $t->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-sm btn-outline-secondary">{{ $t->is_active ? 'Disable' : 'Enable' }}</button>
                </form>
                <form action="{{ route('website-builder.admin.templates.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete template?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">No website builder templates registered yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Template Modal -->
  <div class="modal fade" id="addTemplateModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('website-builder.admin.templates.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Add New Template</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Template Name</label>
              <input type="text" class="form-control" name="name" placeholder="e.g. Business Classic" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Category</label>
              <select class="form-select" name="category" required>
                <option value="Portfolio">Portfolio</option>
                <option value="Startup">Startup</option>
                <option value="Agency">Agency</option>
                <option value="eCommerce">eCommerce</option>
                <option value="Restaurant">Restaurant</option>
                <option value="Events">Events</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Live Demo URL</label>
              <input type="url" class="form-control" name="demo_url" placeholder="https://example.com/demo">
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea class="form-control" name="description" rows="2"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Template</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
