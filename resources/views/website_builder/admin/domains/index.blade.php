@extends('website_builder.admin.layout')

@section('title', 'Custom Domains & Subdomains Manager')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Custom Domains & Subdomains Requests</h3>
      <p class="text-muted small mb-0">Approve, connect, or reject domain connection requests from clients.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success py-2 mb-3"><i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}</div>
  @endif

  <!-- Filter Tabs -->
  <div class="d-flex gap-2 mb-4">
    <a href="{{ route('website-builder.admin.domains.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">All Requests</a>
    <a href="{{ route('website-builder.admin.domains.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status == 'pending' ? 'btn-warning text-white' : 'btn-outline-secondary' }}">Pending Requests</a>
    <a href="{{ route('website-builder.admin.domains.index', ['status' => 'connected']) }}" class="btn btn-sm {{ $status == 'connected' ? 'btn-success' : 'btn-outline-secondary' }}">Connected Requests</a>
    <a href="{{ route('website-builder.admin.domains.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status == 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }}">Rejected Requests</a>
  </div>

  <div class="card p-4">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Client</th>
            <th>Requested Custom Domain</th>
            <th>Current Subdomain</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($domains as $d)
            <tr>
              <td>
                <span class="fw-bold">{{ $d->user->username ?? 'Client' }}</span><br>
                <small class="text-muted">{{ $d->user->email ?? '' }}</small>
              </td>
              <td><span class="fw-semibold text-primary"><i class="fa-solid fa-globe me-1"></i>{{ $d->requested_domain ?? $d->domain }}</span></td>
              <td><code>{{ $d->user->username ?? 'sub' }}.websitebuilder.com</code></td>
              <td>
                @if($d->status == 1)
                  <span class="badge bg-success">Connected</span>
                @elseif($d->status == 2)
                  <span class="badge bg-danger">Rejected</span>
                @else
                  <span class="badge bg-warning text-dark">Pending Approval</span>
                @endif
              </td>
              <td>
                <form action="{{ route('website-builder.admin.domains.status', $d->id) }}" method="POST" class="d-inline-flex gap-1">
                  @csrf
                  <input type="hidden" name="status" value="1">
                  <button type="submit" class="btn btn-sm btn-outline-success" {{ $d->status == 1 ? 'disabled' : '' }}><i class="fa-solid fa-check me-1"></i> Connect</button>
                </form>
                <form action="{{ route('website-builder.admin.domains.status', $d->id) }}" method="POST" class="d-inline-flex gap-1">
                  @csrf
                  <input type="hidden" name="status" value="2">
                  <button type="submit" class="btn btn-sm btn-outline-danger" {{ $d->status == 2 ? 'disabled' : '' }}><i class="fa-solid fa-xmark me-1"></i> Reject</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">No domain connection requests found for status '{{ $status }}'.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">
      {{ $domains->appends(request()->query())->links() }}
    </div>
  </div>
@endsection
