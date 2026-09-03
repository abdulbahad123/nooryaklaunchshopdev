@extends('website_builder.admin.layout')

@section('title', 'Website Builder Admin - Overview')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Website Builder Admin Dashboard</h3>
      <p class="text-muted small mb-0">Manage landing page, dynamic colors, client secret login, templates, and packages.</p>
    </div>
    <a href="{{ route('website-builder.index') }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-external-link me-1"></i> Live Landing Page</a>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card p-4">
        <div class="d-flex align-items-center gap-3">
          <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 fs-3"><i class="fa-solid fa-users"></i></div>
          <div>
            <h6 class="text-muted small fw-semibold text-uppercase mb-1">Registered Clients</h6>
            <h2 class="fw-extrabold mb-0">{{ $totalCustomers }}</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-4">
        <div class="d-flex align-items-center gap-3">
          <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 fs-3"><i class="fa-solid fa-shopping-cart"></i></div>
          <div>
            <h6 class="text-muted small fw-semibold text-uppercase mb-1">Template Purchases</h6>
            <h2 class="fw-extrabold mb-0">{{ $totalPurchases ?? 0 }}</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-4">
        <div class="d-flex align-items-center gap-3">
          <div class="p-3 bg-info bg-opacity-10 text-info rounded-3 fs-3"><i class="fa-solid fa-cubes"></i></div>
          <div>
            <h6 class="text-muted small fw-semibold text-uppercase mb-1">Active Templates</h6>
            <h2 class="fw-extrabold mb-0">{{ $totalTemplates }}</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-4">
        <div class="d-flex align-items-center gap-3">
          <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3 fs-3"><i class="fa-solid fa-tags"></i></div>
          <div>
            <h6 class="text-muted small fw-semibold text-uppercase mb-1">Pricing Tiers</h6>
            <h2 class="fw-extrabold mb-0">{{ $totalPackages }}</h2>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Template Purchases Table -->
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0">Recent Template Purchases (Razorpay)</h5>
      <span class="badge bg-success">Digital Agency</span>
    </div>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Template</th>
            <th>Payment ID</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentPurchases ?? [] as $p)
            <tr>
              <td><span class="fw-bold">{{ $p->customer_name }}</span></td>
              <td>{{ $p->customer_email }}</td>
              <td><span class="badge bg-primary">{{ $p->template_name }}</span></td>
              <td><code>{{ $p->razorpay_payment_id }}</code></td>
              <td><span class="fw-bold text-success">₹{{ number_format($p->amount, 2) }}</span></td>
              <td><span class="badge bg-success">{{ ucfirst($p->status) }}</span></td>
              <td>{{ $p->created_at ? $p->created_at->format('M d, Y H:i') : 'Just now' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">No template purchases recorded yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card p-4">
    <h5 class="fw-bold mb-3">Recent Registered Clients</h5>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Subdomain</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentCustomers as $c)
            <tr>
              <td><span class="fw-bold">{{ $c->name }}</span></td>
              <td>{{ $c->email }}</td>
              <td>{{ $c->company_name ?? 'N/A' }}</td>
              <td><code>{{ $c->subdomain }}</code></td>
              <td>
                <a href="{{ route('website-builder.admin.customers.secret-login', $c->id) }}" target="_blank" class="btn btn-sm btn-warning fw-bold">
                  <i class="fa-solid fa-key me-1"></i> Secret Login
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">No registered clients yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
