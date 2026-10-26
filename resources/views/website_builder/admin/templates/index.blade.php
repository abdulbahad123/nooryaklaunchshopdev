@extends('website_builder.admin.layout')

@section('title', 'Template Engine & Catalog')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Website Builder Templates</h3>
      <p class="text-muted small mb-0">Total active templates count & template creation manager.</p>
    </div>
    <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
      <i class="fa-solid fa-plus me-1"></i> Add Template
    </button>
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
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($templates as $t)
            <tr>
              <td>
                <span class="fw-bold">{{ $t->name }}</span>
                @if($t->price > 0)
                  <span class="badge bg-light text-dark ms-1">₹{{ number_format($t->price, 2) }}</span>
                @else
                  <span class="badge bg-info-subtle text-info ms-1">Free</span>
                @endif
              </td>
              <td><span class="badge bg-secondary">{{ $t->category }}</span></td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  @if($t->demo_url)
                    <a href="{{ $t->demo_url }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 12px;"><i class="fa-solid fa-external-link me-1"></i> Live Demo</a>
                  @endif
                  <a href="{{ route('website-builder.demo-admin', ['template' => $t->slug ?? 'digital_agency']) }}" target="_blank" class="btn btn-sm btn-success py-1 px-2 text-white fw-bold" style="font-size: 12px;"><i class="fa-solid fa-user-shield me-1"></i> Admin</a>
                </div>
              </td>
              <td>
                <span class="badge {{ $t->is_active ? 'bg-success' : 'bg-danger' }}">{{ $t->is_active ? 'Active' : 'Disabled' }}</span>
              </td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#editTemplateModal{{ $t->id }}">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                  </button>
                  <form action="{{ route('website-builder.admin.templates.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this template?');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size: 12px;">
                      <i class="fa-solid fa-trash me-1"></i> Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>

            <!-- Edit Template Modal -->
            <div class="modal fade" id="editTemplateModal{{ $t->id }}" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="{{ route('website-builder.admin.templates.update', $t->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                      <h5 class="modal-title fw-bold">Edit Template: {{ $t->name }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label">Template Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $t->name }}" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" name="category" value="{{ $t->category }}" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Live Demo URL</label>
                        <input type="url" class="form-control" name="demo_url" value="{{ $t->demo_url }}" placeholder="https://example.com/demo">
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="price" value="{{ $t->price }}">
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2">{{ $t->description }}</textarea>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="is_active">
                          <option value="1" {{ $t->is_active ? 'selected' : '' }}>Active</option>
                          <option value="0" {{ !$t->is_active ? 'selected' : '' }}>Disabled</option>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Update Template</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">No website builder templates registered yet.</td>
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
                <option value="Agency / Portfolio">Agency / Portfolio</option>
                <option value="Architecture & Design">Architecture & Design</option>
                <option value="Transport & Mobility">Transport & Mobility</option>
                <option value="Construction & Engineering">Construction & Engineering</option>
                <option value="Events & Celebrations">Events & Celebrations</option>
                <option value="Portfolio">Portfolio</option>
                <option value="Startup">Startup</option>
                <option value="eCommerce">eCommerce</option>
                <option value="Restaurant">Restaurant</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Live Demo URL</label>
              <input type="url" class="form-control" name="demo_url" placeholder="https://example.com/demo">
            </div>
            <div class="mb-3">
              <label class="form-label">Price (₹)</label>
              <input type="number" step="0.01" min="0" class="form-control" name="price" value="499.00">
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea class="form-control" name="description" rows="2"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Template</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
