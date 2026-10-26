@extends('website_builder.admin.layout')

@section('title', 'Staff Management & Permissions')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Staff Members & Roles</h3>
      <p class="text-muted small mb-0">Role-based staff access control (RBAC) for Website Builder admin panel.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('website-builder.admin.roles.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-user-shield me-1"></i> Manage Roles</a>
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStaffModal"><i class="fa-solid fa-plus me-1"></i> Add Staff Member</button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success py-2 mb-3"><i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}</div>
  @endif

  @if(session('alert'))
    <div class="alert alert-danger py-2 mb-3"><i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('alert') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger py-2 mb-3">
      <ul class="mb-0 small">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card p-4">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Staff Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Permissions</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($staffMembers as $s)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 14px;">
                    {{ strtoupper(substr($s->name, 0, 1)) }}
                  </div>
                  <span class="fw-bold">{{ $s->name }}</span>
                </div>
              </td>
              <td>{{ $s->email }}</td>
              <td><span class="badge bg-primary px-2 py-1">{{ strtoupper($s->role) }}</span></td>
              <td>
                @php
                  $perms = is_string($s->permissions) ? json_decode($s->permissions, true) : ($s->permissions ?? []);
                  if (!is_array($perms)) $perms = [];
                @endphp
                @if(count($perms) > 0)
                  <div class="d-flex flex-wrap gap-1" style="max-width: 320px;">
                    @foreach(array_slice($perms, 0, 3) as $p)
                      <span class="badge bg-light text-dark border small" style="font-size: 11px;">{{ $p }}</span>
                    @endforeach
                    @if(count($perms) > 3)
                      <span class="badge bg-secondary small" style="font-size: 10px;">+{{ count($perms) - 3 }} more</span>
                    @endif
                  </div>
                @else
                  <span class="text-muted small">All Modules (Full Admin)</span>
                @endif
              </td>
              <td><span class="badge {{ $s->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
              <td>
                <form action="{{ route('website-builder.admin.staff.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Delete staff account for {{ $s->name }}?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Staff Account"><i class="fa-solid fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">No staff members created yet. Click "Add Staff Member" above to create one.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Staff Modal -->
  <div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('website-builder.admin.staff.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-plus me-2 text-primary"></i>Add Staff Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-semibold small">Full Name</label>
              <input type="text" class="form-control" name="name" placeholder="e.g. John Doe" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold small">Email Address</label>
              <input type="email" class="form-control" name="email" placeholder="staff@example.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold small">Password</label>
              <input type="password" class="form-control" name="password" placeholder="At least 6 characters" required>
            </div>
            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label fw-semibold small mb-0">Role</label>
                <a href="{{ route('website-builder.admin.roles.index') }}" target="_blank" class="small text-primary text-decoration-none"><i class="fa-solid fa-plus me-1"></i>New Role</a>
              </div>
              <select class="form-select" name="role" id="staffRoleSelect" required>
                <option value="" disabled selected>-- Select Assigned Role --</option>
                @if(isset($roles) && count($roles) > 0)
                  @foreach($roles as $r)
                    <option value="{{ $r->name }}" data-role-id="{{ $r->id }}" {{ old('role') == $r->name ? 'selected' : '' }}>
                      {{ $r->name }}
                    </option>
                  @endforeach
                @else
                  <option value="Super Admin">Super Admin (Full Access)</option>
                  <option value="Support Agent">Support Agent</option>
                  <option value="Template Manager">Template Manager</option>
                @endif
              </select>
              <input type="hidden" name="role_id" id="staffRoleIdInput" value="">
              <small class="text-muted mt-1 d-block">The staff member will inherit permissions assigned to this role.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Create Staff Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('staffRoleSelect')?.addEventListener('change', function() {
      const selectedOpt = this.options[this.selectedIndex];
      const roleId = selectedOpt ? selectedOpt.getAttribute('data-role-id') : '';
      const hiddenInput = document.getElementById('staffRoleIdInput');
      if (hiddenInput) hiddenInput.value = roleId || '';
    });
  </script>
@endsection
