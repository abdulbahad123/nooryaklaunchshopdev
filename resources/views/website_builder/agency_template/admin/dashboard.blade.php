@extends('website_builder.agency_template.admin.layout')

@section('title', 'Template Dashboard - DesignAGENCY')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-chart-pie text-indigo me-2" style="color: #4F46E5;"></i>Template Admin Dashboard</h3>
    <p class="text-muted small mb-0">Overview of DesignAGENCY template analytics, pages & customer contact submissions.</p>
  </div>
  <a href="{{ route('website-builder.templates.design-agency') }}" target="_blank" class="btn btn-emerald text-white fw-bold px-4" style="background: #10B981; border-radius: 10px;">
    <i class="fa-solid fa-globe me-2"></i> Live Website
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<!-- METRIC CARDS -->
<div class="row g-4 mb-4">
  <div class="col-md-3">
    <div class="card card-editor p-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 11px;">Contact Inquiries</div>
          <h2 class="fw-extrabold text-slate-900 mb-0">{{ $inquiriesCount ?? 0 }}</h2>
        </div>
        <div class="p-3 rounded-circle text-indigo" style="background: #EEF2FF; color: #4F46E5;"><i class="fa-solid fa-envelope-open-text fs-4"></i></div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-editor p-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 11px;">Active Services</div>
          <h2 class="fw-extrabold text-slate-900 mb-0">6</h2>
        </div>
        <div class="p-3 rounded-circle text-emerald" style="background: #ECFDF5; color: #10B981;"><i class="fa-solid fa-grid-2 fs-4"></i></div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-editor p-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 11px;">Portfolio Items</div>
          <h2 class="fw-extrabold text-slate-900 mb-0">8</h2>
        </div>
        <div class="p-3 rounded-circle text-purple" style="background: #F3E8FF; color: #9333EA;"><i class="fa-solid fa-briefcase fs-4"></i></div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-editor p-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 11px;">Team Members</div>
          <h2 class="fw-extrabold text-slate-900 mb-0">4</h2>
        </div>
        <div class="p-3 rounded-circle text-amber" style="background: #FEF3C7; color: #D97706;"><i class="fa-solid fa-user-group fs-4"></i></div>
      </div>
    </div>
  </div>
</div>

<!-- USER SUBMITTED CONTACT INQUIRIES TABLE (User Task 2 Match) -->
<div class="card card-editor p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="fw-bold mb-1"><i class="fa-solid fa-inbox text-indigo me-2"></i>Recent Contact Form Submissions</h5>
      <p class="text-muted small mb-0">User submitted data received directly from the frontend contact form.</p>
    </div>
    <a href="{{ route('website-builder.agency-admin.inquiries') }}" class="btn btn-sm btn-outline-primary fw-bold">View All Submissions →</a>
  </div>

  @if(isset($recentInquiries) && count($recentInquiries) > 0)
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small text-uppercase">
          <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recentInquiries as $inq)
            <tr>
              <td class="small text-muted fw-semibold">{{ $inq->created_at->format('M d, Y h:i A') }}</td>
              <td class="fw-bold text-slate-900">{{ $inq->name }}</td>
              <td><a href="mailto:{{ $inq->email }}" class="text-decoration-none">{{ $inq->email }}</a></td>
              <td>{{ $inq->phone ?? 'N/A' }}</td>
              <td><span class="badge bg-light text-dark border">{{ $inq->subject ?? 'General Inquiry' }}</span></td>
              <td class="small text-muted" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $inq->message }}</td>
              <td>
                <form action="{{ route('website-builder.agency-admin.inquiries.delete', $inq->id) }}" method="POST" onsubmit="return confirm('Delete this message?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="text-center py-5 bg-light rounded-3">
      <i class="fa-solid fa-envelope-open text-muted fs-1 mb-2"></i>
      <h6 class="fw-bold text-muted mb-1">No contact submissions received yet.</h6>
      <p class="small text-muted mb-0">When visitors fill out the Contact Us form on your live site, their messages will show up here.</p>
    </div>
  @endif
</div>
@endsection
