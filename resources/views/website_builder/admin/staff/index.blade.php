@extends('website_builder.admin.layout')

@section('title', 'Staff Management & Permissions')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Staff Members & Roles</h3>
      <p class="text-muted small mb-0">Role-based staff access control (RBAC) for Website Builder admin panel.</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStaffModal"><i class="fa-solid fa-user-shield me-1"></i> Add Staff Member</button>
  </div>

  <div class="card p-4">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Staff Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($staffMembers as $s)
            <tr>
              <td><span class="fw-bold">{{ $s->name }}</span></td>
              <td>{{ $s->email }}</td>
              <td><span class="badge bg-primary">{{ strtoupper($s->role) }}</span></td>
              <td><span class="badge {{ $s->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
              <td>
                <form action="{{ route('website-builder.admin.staff.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Delete staff account?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">No staff members created yet.</td>
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
            <h5 class="modal-title fw-bold">Add Staff Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select class="form-select" name="role" required>
                <option value="Super Admin">Super Admin</option>
                <option value="Support Agent">Support Agent</option>
                <option value="Template Manager">Template Manager</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create Staff Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
