@extends('website_builder.admin.layout')

@section('title', 'Registered Clients & Secret Login')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Registered Clients</h3>
      <p class="text-muted small mb-0">View registered customers and execute secret one-click SSO login into client panels.</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addClientModal"><i class="fa-solid fa-user-plus me-1"></i> Register Client</button>
  </div>

  <div class="card p-4">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Client Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Subdomain</th>
            <th>Package</th>
            <th>Secret Login</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($customers as $c)
            <tr>
              <td><span class="fw-bold">{{ $c->name }}</span></td>
              <td>{{ $c->email }}</td>
              <td>{{ $c->company_name ?? 'Personal' }}</td>
              <td><span class="badge bg-secondary">{{ $c->subdomain }}</span></td>
              <td><span class="badge bg-info text-dark">{{ $c->package ? $c->package->name : 'Free Tier' }}</span></td>
              <td>
                <a href="{{ route('website-builder.admin.customers.secret-login', $c->id) }}" target="_blank" class="btn btn-sm btn-warning fw-bold">
                  <i class="fa-solid fa-key me-1"></i> Secret Login
                </a>
              </td>
              <td>
                <form action="{{ route('website-builder.admin.customers.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Delete client account?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">No registered client accounts found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $customers->links() }}
  </div>

  <!-- Register Client Modal -->
  <div class="modal fade" id="addClientModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('website-builder.admin.customers.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Register Client Account</h5>
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
              <label class="form-label">Company Name (Optional)</label>
              <input type="text" class="form-control" name="company_name">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create Client Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
