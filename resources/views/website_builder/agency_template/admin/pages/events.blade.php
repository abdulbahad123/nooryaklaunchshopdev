@extends('website_builder.agency_template.admin.layout')

@section('title', 'Upcoming Events Management — Admin Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-extrabold text-dark mb-1">Upcoming Events Management</h3>
    <p class="text-secondary small mb-0">Add, edit, or remove upcoming events rendered on the Evently theme homepage and portfolio pages.</p>
  </div>
  @if(isset($customer) && !empty($customer->subdomain))
    <a href="{{ route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
      <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Site
    </a>
  @endif
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST" enctype="multipart/form-data">
  @csrf

  @php
    $events = $agency->events_data ?? [
      ['date_day' => '24', 'date_month' => 'AUG', 'title' => 'Business Growth Summit 2024', 'location' => 'New York, USA', 'categories' => 'Conference, Business', 'desc' => 'Discover strategies for corporate growth.'],
      ['date_day' => '15', 'date_month' => 'SEP', 'title' => 'The Grand Wedding Expo', 'location' => 'Los Angeles, USA', 'categories' => 'Wedding, Exhibition', 'desc' => 'Showcasing luxury wedding designs.'],
      ['date_day' => '10', 'date_month' => 'OCT', 'title' => 'Music Fest 2024', 'location' => 'Chicago, USA', 'categories' => 'Concert, Entertainment', 'desc' => 'Annual live musical extravaganza.'],
      ['date_day' => '22', 'date_month' => 'NOV', 'title' => 'Annual Corporate Gala Night', 'location' => 'Miami, USA', 'categories' => 'Corporate, Networking', 'desc' => 'Exclusive networking gala.'],
    ];
  @endphp

  <div class="card card-editor p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-calendar-days text-primary me-2"></i> Upcoming Events List</h5>
      <button type="button" class="btn btn-sm btn-outline-success rounded-pill fw-semibold" onclick="addEventRow()">
        <i class="fa-solid fa-plus me-1"></i> Add New Event
      </button>
    </div>

    <div id="eventsContainer">
      @foreach($events as $index => $item)
        <div class="event-item-card p-3 mb-3 border rounded-3 bg-light position-relative" id="event_row_{{ $index }}">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-secondary">Event #{{ $index + 1 }}</span>
            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeEventRow({{ $index }})">
              <i class="fa-solid fa-trash-can me-1"></i> Remove
            </button>
          </div>

          <div class="row g-3">
            <div class="col-md-2">
              <label class="form-label small fw-semibold">Day (Number)</label>
              <input type="text" name="events_data[{{ $index }}][date_day]" class="form-control form-control-sm" value="{{ $item['date_day'] ?? '' }}" placeholder="e.g. 24">
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-semibold">Month (Short)</label>
              <input type="text" name="events_data[{{ $index }}][date_month]" class="form-control form-control-sm" value="{{ $item['date_month'] ?? '' }}" placeholder="e.g. AUG">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Event Title</label>
              <input type="text" name="events_data[{{ $index }}][title]" class="form-control form-control-sm" value="{{ $item['title'] ?? '' }}" placeholder="Event Title">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Location</label>
              <input type="text" name="events_data[{{ $index }}][location]" class="form-control form-control-sm" value="{{ $item['location'] ?? '' }}" placeholder="e.g. New York, USA">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Categories (Comma separated)</label>
              <input type="text" name="events_data[{{ $index }}][categories]" class="form-control form-control-sm" value="{{ $item['categories'] ?? '' }}" placeholder="e.g. Conference, Business">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Event Image Upload</label>
              <input type="file" name="events_data[{{ $index }}][image_file]" class="form-control form-control-sm" accept="image/*">
              <input type="hidden" name="events_data[{{ $index }}][image]" value="{{ $item['image'] ?? '' }}">
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
      <i class="fa-solid fa-floppy-disk me-2"></i> Save Upcoming Events
    </button>
  </div>
</form>

@section('scripts')
<script>
  let eventCount = {{ count($events) }};

  function addEventRow() {
    const container = document.getElementById('eventsContainer');
    const div = document.createElement('div');
    div.className = 'event-item-card p-3 mb-3 border rounded-3 bg-light position-relative';
    div.id = `event_row_${eventCount}`;
    div.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="badge bg-secondary">Event #${eventCount + 1}</span>
        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeEventRow(${eventCount})">
          <i class="fa-solid fa-trash-can me-1"></i> Remove
        </button>
      </div>
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label small fw-semibold">Day (Number)</label>
          <input type="text" name="events_data[${eventCount}][date_day]" class="form-control form-control-sm" placeholder="e.g. 24">
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-semibold">Month (Short)</label>
          <input type="text" name="events_data[${eventCount}][date_month]" class="form-control form-control-sm" placeholder="e.g. AUG">
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Event Title</label>
          <input type="text" name="events_data[${eventCount}][title]" class="form-control form-control-sm" placeholder="Event Title">
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Location</label>
          <input type="text" name="events_data[${eventCount}][location]" class="form-control form-control-sm" placeholder="e.g. New York, USA">
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Categories (Comma separated)</label>
          <input type="text" name="events_data[${eventCount}][categories]" class="form-control form-control-sm" placeholder="e.g. Conference, Business">
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-semibold">Event Image Upload</label>
          <input type="file" name="events_data[${eventCount}][image_file]" class="form-control form-control-sm" accept="image/*">
        </div>
      </div>
    `;
    container.appendChild(div);
    eventCount++;
  }

  function removeEventRow(id) {
    const row = document.getElementById(`event_row_${id}`);
    if (row) {
      row.remove();
    }
  }
</script>
@endsection
@endsection
