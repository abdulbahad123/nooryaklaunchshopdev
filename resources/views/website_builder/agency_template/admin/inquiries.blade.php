@extends('website_builder.agency_template.admin.layout')

@section('title', 'Contact Submissions - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-envelope-open-text text-indigo me-2" style="color: #4F46E5;"></i>Contact Submissions</h3>
    <p class="text-muted small mb-0">Messages submitted by visitors through the Contact Us form on your frontend template.</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="card card-editor p-4">
  @if(isset($inquiries) && count($inquiries) > 0)
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small text-uppercase">
          <tr>
            <th>#</th>
            <th>Submitted Date</th>
            <th>Client Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($inquiries as $idx => $inq)
            <tr>
              <td class="fw-bold text-muted">{{ $idx + 1 }}</td>
              <td class="small text-muted fw-semibold">{{ $inq->created_at->format('M d, Y h:i A') }}</td>
              <td class="fw-bold text-slate-900">{{ $inq->name }}</td>
              <td><a href="mailto:{{ $inq->email }}" class="text-decoration-none fw-semibold">{{ $inq->email }}</a></td>
              <td>{{ $inq->phone ?? 'N/A' }}</td>
              <td><span class="badge bg-light text-dark border">{{ $inq->subject ?? 'General Inquiry' }}</span></td>
              <td class="small text-slate-700" style="max-width: 300px;">{{ $inq->message }}</td>
              <td>
                <form action="{{ route('website-builder.agency-admin.inquiries.delete', $inq->id) }}" method="POST" onsubmit="return confirm('Delete this message?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Message"><i class="fa-solid fa-trash"></i> Delete</button>
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
