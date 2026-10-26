@extends('website_builder.admin.layout')

@section('title', 'Contact Submitted')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1"><i class="fa-solid fa-inbox text-primary me-2"></i>Contact Submitted</h3>
      <p class="text-muted small mb-0">Visitor inquiries submitted via the Website Builder landing page and customer websites.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-4" role="alert">
      <i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Stats Overview -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card p-3 shadow-sm border-0" style="background: linear-gradient(135deg, #EEF2FF, #E0E7FF); border-left: 4px solid #4F46E5 !important;">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold">TOTAL SUBMISSIONS</div>
            <div class="fs-3 fw-bold text-dark mt-1">{{ number_format($totalCount) }}</div>
          </div>
          <div style="width: 48px; height: 48px; border-radius: 12px; background: #4F46E5; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-envelope-open-text"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3 shadow-sm border-0" style="background: linear-gradient(135deg, #DCFCE7, #BBF7D0); border-left: 4px solid #16A34A !important;">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold">TODAY'S INQUIRIES</div>
            <div class="fs-3 fw-bold text-success mt-1">{{ number_format($todayCount) }}</div>
          </div>
          <div style="width: 48px; height: 48px; border-radius: 12px; background: #16A34A; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-calendar-day"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3 shadow-sm border-0" style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); border-left: 4px solid #D97706 !important;">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold">YESTERDAY'S INQUIRIES</div>
            <div class="fs-3 fw-bold text-warning mt-1">{{ number_format($yesterdayCount) }}</div>
          </div>
          <div style="width: 48px; height: 48px; border-radius: 12px; background: #D97706; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-clock-rotate-left"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Search Bar -->
  <div class="card p-3 mb-4 shadow-sm border-0">
    <form action="{{ route('website-builder.admin.inquiries.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
      <div class="col-lg-3 col-md-4">
        <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-calendar me-1"></i> Date Filter</label>
        <select name="date_filter" class="form-select form-select-sm fw-semibold" id="dateFilterSelect" onchange="toggleCustomDates(this.value)">
          <option value="">All Time</option>
          <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
          <option value="yesterday" {{ request('date_filter') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
          <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
        </select>
      </div>

      <div class="col-lg-2 col-md-4 custom-date-col" style="{{ request('date_filter') == 'custom' ? '' : 'display:none;' }}">
        <label class="form-label small fw-bold text-muted mb-1">Start Date</label>
        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
      </div>

      <div class="col-lg-2 col-md-4 custom-date-col" style="{{ request('date_filter') == 'custom' ? '' : 'display:none;' }}">
        <label class="form-label small fw-bold text-muted mb-1">End Date</label>
        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
      </div>

      <div class="col-lg-3 col-md-8">
        <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-search me-1"></i> Search Inquiries</label>
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search by name, email, subject..." value="{{ request('q') }}">
      </div>

      <div class="col-lg-2 col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm fw-bold flex-grow-1">
          <i class="fa-solid fa-filter me-1"></i> Filter
        </button>
        @if(request()->hasAny(['date_filter', 'start_date', 'end_date', 'q']))
          <a href="{{ route('website-builder.admin.inquiries.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters">
            <i class="fa-solid fa-rotate-left"></i>
          </a>
        @endif
      </div>
    </form>
  </div>

  <script>
    function toggleCustomDates(val) {
      const cols = document.querySelectorAll('.custom-date-col');
      cols.forEach(col => {
        col.style.display = val === 'custom' ? 'block' : 'none';
      });
    }
  </script>

  <!-- Inquiries Table Card -->
  <div class="card p-4 shadow-sm border-0">
    @if(isset($inquiries) && count($inquiries) > 0)
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light small text-uppercase">
            <tr>
              <th style="width: 50px;">#</th>
              <th>Submitted Date</th>
              <th>Client Name</th>
              <th>Contact Details</th>
              <th>Subject</th>
              <th>Message Preview</th>
              <th class="text-end" style="width: 140px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($inquiries as $idx => $inq)
              <tr>
                <td class="fw-bold text-muted">{{ $inquiries->firstItem() + $idx }}</td>
                <td>
                  <div class="fw-semibold text-dark small">{{ $inq->created_at ? $inq->created_at->format('M d, Y') : 'N/A' }}</div>
                  <div class="text-muted" style="font-size: 11.5px;">{{ $inq->created_at ? $inq->created_at->format('h:i A') : '' }}</div>
                </td>
                <td>
                  <div class="fw-bold text-dark">{{ $inq->name }}</div>
                  @if(empty($inq->customer_id))
                    <span class="badge bg-info text-dark" style="font-size: 10px;">Landing Page</span>
                  @else
                    <span class="badge bg-secondary" style="font-size: 10px;">Customer Site</span>
                  @endif
                </td>
                <td>
                  <div><a href="mailto:{{ $inq->email }}" class="text-decoration-none fw-semibold small"><i class="fa-solid fa-envelope me-1 text-muted"></i>{{ $inq->email }}</a></div>
                  @if(!empty($inq->phone))
                    <div><a href="tel:{{ $inq->phone }}" class="text-decoration-none text-muted small"><i class="fa-solid fa-phone me-1"></i>{{ $inq->phone }}</a></div>
                  @endif
                </td>
                <td>
                  <span class="badge bg-light text-dark border px-2 py-1 fw-semibold">{{ $inq->subject ?? 'General Inquiry' }}</span>
                </td>
                <td>
                  <div class="text-muted small" style="max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $inq->message }}
                  </div>
                </td>
                <td class="text-end">
                  <div class="d-inline-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#inquiryModal{{ $inq->id }}" title="View Full Message">
                      <i class="fa-solid fa-eye"></i>
                    </button>
                    <form action="{{ route('website-builder.admin.inquiries.destroy', $inq->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this submission?');" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Submission">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </form>
                  </div>

                  <!-- Details Modal -->
                  <div class="modal fade text-start" id="inquiryModal{{ $inq->id }}" tabindex="-1" aria-labelledby="inquiryModalLabel{{ $inq->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title fw-bold" id="inquiryModalLabel{{ $inq->id }}">
                            <i class="fa-solid fa-envelope-open text-primary me-2"></i>Inquiry Details
                          </h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3 p-3 bg-light rounded-3">
                            <div class="row g-2">
                              <div class="col-6">
                                <label class="text-muted small fw-bold">Sender Name</label>
                                <div class="fw-semibold">{{ $inq->name }}</div>
                              </div>
                              <div class="col-6">
                                <label class="text-muted small fw-bold">Date & Time</label>
                                <div class="fw-semibold">{{ $inq->created_at ? $inq->created_at->format('M d, Y h:i A') : 'N/A' }}</div>
                              </div>
                              <div class="col-6">
                                <label class="text-muted small fw-bold">Email</label>
                                <div><a href="mailto:{{ $inq->email }}" class="text-decoration-none fw-semibold">{{ $inq->email }}</a></div>
                              </div>
                              <div class="col-6">
                                <label class="text-muted small fw-bold">Phone</label>
                                <div>{{ $inq->phone ?: 'Not provided' }}</div>
                              </div>
                              <div class="col-12 mt-2">
                                <label class="text-muted small fw-bold">Subject</label>
                                <div class="fw-bold text-primary">{{ $inq->subject ?: 'General Inquiry' }}</div>
                              </div>
                            </div>
                          </div>

                          <div>
                            <label class="text-muted small fw-bold mb-2">Message Content</label>
                            <div class="p-3 border rounded-3 bg-white" style="white-space: pre-wrap; font-size: 14px; line-height: 1.6;">{{ $inq->message }}</div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <a href="mailto:{{ $inq->email }}?subject=Re: {{ urlencode($inq->subject ?? 'Inquiry') }}" class="btn btn-primary btn-sm fw-bold">
                            <i class="fa-solid fa-reply me-1"></i> Reply via Email
                          </a>
                          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted small">
          Showing <strong>{{ $inquiries->firstItem() }}</strong> to <strong>{{ $inquiries->lastItem() }}</strong> of <strong>{{ $inquiries->total() }}</strong> submissions
        </div>
        <div>
          {{ $inquiries->links('pagination::bootstrap-5') }}
        </div>
      </div>
    @else
      <div class="text-center py-5">
        <div class="text-muted mb-3" style="font-size: 48px;"><i class="fa-solid fa-envelope-open-text opacity-50"></i></div>
        <h5 class="fw-bold text-muted">No Contact Submissions Found</h5>
        <p class="text-muted small">There are no inquiries matching your current search or date filter.</p>
        @if(request()->hasAny(['date_filter', 'start_date', 'end_date', 'q']))
          <a href="{{ route('website-builder.admin.inquiries.index') }}" class="btn btn-outline-primary btn-sm fw-semibold mt-2">
            <i class="fa-solid fa-rotate-left me-1"></i> Reset All Filters
          </a>
        @endif
      </div>
    @endif
  </div>
@endsection
