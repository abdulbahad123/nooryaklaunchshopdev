@extends('website_builder.admin.layout')

@section('title', 'Role & Permissions')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Role & Permissions</h3>
      <p class="text-muted small mb-0">Define staff access roles and assign menu permissions.</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
      <i class="fa-solid fa-plus me-1"></i> Add New Role
    </button>
  </div>

  @if(session('success'))
    <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
  @endif

  @if(session('alert'))
    <div class="alert alert-danger py-2 mb-3">{{ session('alert') }}</div>
  @endif

  <div class="row g-4">
    @forelse($roles as $role)
      <div class="col-md-6">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-user-shield me-2 text-primary"></i>{{ $role->name }}</h5>
            <form action="{{ route('website-builder.admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Delete role?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
            </form>
          </div>

          <form action="{{ route('website-builder.admin.roles.permissions', $role->id) }}" method="POST">
            @csrf
            <h6 class="fw-semibold text-muted fs-6 mb-3">Module Permissions:</h6>
            @php
              $rolePerms = is_string($role->permissions) ? json_decode($role->permissions, true) : ($role->permissions ?? []);
              if(!is_array($rolePerms)) $rolePerms = [];
              $allModules = [
                'Landing & Colors',
                'Registered Clients',
                'Templates Engine',
                'Packages & Plans',
                'Staff & Roles',
                'Agency Access',
                'Custom Domains',
                'Payment Gateways'
              ];
            @endphp

            <div class="row g-2 mb-4">
              @foreach($allModules as $mod)
                <div class="col-6">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $mod }}" id="perm_{{ $role->id }}_{{ Str::slug($mod) }}" {{ in_array($mod, $rolePerms) ? 'checked' : '' }}>
                    <label class="form-check-label small" for="perm_{{ $role->id }}_{{ Str::slug($mod) }}">
                      {{ $mod }}
                    </label>
                  </div>
                </div>
              @endforeach
            </div>

            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fa-solid fa-save me-1"></i> Update Permissions</button>
          </form>
        </div>
      </div>
    @empty
      <div class="col-12 text-center text-muted py-4">No custom roles defined yet.</div>
    @endforelse
  </div>

  <!-- Add Role Modal -->
  <div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('website-builder.admin.roles.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Create New Role</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Role Name</label>
              <input type="text" class="form-control" name="name" placeholder="e.g. Sales Manager" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Role</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
