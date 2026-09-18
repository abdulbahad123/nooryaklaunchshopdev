@extends('website_builder.agency_template.admin.layout')

@section('title', 'Manage Testimonials - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-comments text-indigo me-2" style="color: #4F46E5;"></i>Testimonials Management</h3>
    <p class="text-muted small mb-0">Manage customer reviews, testimonials badge, section title, ratings, and client avatars for all themes.</p>
  </div>
  <a href="{{ $liveUrl ?? '#' }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Live Site
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <!-- TESTIMONIALS SECTION HEADINGS -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-heading text-primary me-2"></i>Testimonials Section Headings</h5>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Section Badge</label>
        <input type="text" class="form-control" name="testimonials_badge" value="{{ $agency->testimonials_badge ?? 'TESTIMONIALS' }}" placeholder="TESTIMONIALS">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Section Main Title</label>
        <input type="text" class="form-control" name="testimonials_title" value="{{ $agency->testimonials_title ?? 'What Our Clients Say' }}" placeholder="What Our Clients Say">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Section Subtitle / Description</label>
        <input type="text" class="form-control" name="testimonials_subtitle" value="{{ $agency->testimonials_subtitle ?? 'Read feedback and reviews from happy clients across all industries.' }}" placeholder="Read feedback...">
      </div>
    </div>
  </div>

  <!-- TESTIMONIALS LIST (FULL CRUD) -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-star text-warning me-2"></i>Client Reviews & Testimonials</h5>
        <p class="text-muted small mb-0">Add, edit, or remove client testimonials displayed across your website.</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addTestimonialItem()">
        <i class="fa-solid fa-plus me-1"></i> Add Testimonial
      </button>
    </div>

    @php
      $testimonials = $agency->testimonials_data ?? [
        ['name' => 'John Smith',    'role' => 'CEO, Fineva',       'rating' => 5, 'comment' => 'Transformed our website and brand identity. Professional, creative, and results-driven!'],
        ['name' => 'Sarah Johnson', 'role' => 'Marketing Director', 'rating' => 5, 'comment' => 'Amazing experience from start to finish. Delivered beyond our expectations.'],
        ['name' => 'David Brown',   'role' => 'Founder, Shopious', 'rating' => 5, 'comment' => 'Modern, clean, and user-friendly designs. Our customers love the new experience!'],
      ];
    @endphp

    <div class="row g-3" id="testimonialsContainer">
      @foreach($testimonials as $ti => $t)
        <div class="col-md-6 testimonial-card-item">
          <div class="p-3 border rounded-3 bg-light position-relative">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge bg-warning text-dark fw-bold px-2 py-1">Review #{{ $ti + 1 }}</span>
              <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeTestimonialItem(this)" title="Delete Review"><i class="fa-solid fa-trash"></i></button>
            </div>
            <div class="row g-2 mb-2">
              <div class="col-6">
                <label class="form-label small fw-semibold mb-1">Client Name</label>
                <input type="text" class="form-control form-control-sm" name="testimonials_data[{{ $ti }}][name]" value="{{ $t['name'] ?? '' }}" placeholder="Client Name" required>
              </div>
              <div class="col-6">
                <label class="form-label small fw-semibold mb-1">Role / Designation</label>
                <input type="text" class="form-control form-control-sm" name="testimonials_data[{{ $ti }}][role]" value="{{ $t['role'] ?? '' }}" placeholder="e.g. CEO, Fineva">
              </div>
              <div class="col-6">
                <label class="form-label small fw-semibold mb-1">Rating (1 to 5)</label>
                <select class="form-select form-select-sm" name="testimonials_data[{{ $ti }}][rating]">
                  @for($r = 5; $r >= 1; $r--)
                    <option value="{{ $r }}" {{ ($t['rating'] ?? 5) == $r ? 'selected' : '' }}>{{ $r }} Stars</option>
                  @endfor
                </select>
              </div>
              <div class="col-6">
                <label class="form-label small fw-semibold mb-1">Upload Avatar</label>
                <input type="file" class="form-control form-control-sm" name="testimonials_data[{{ $ti }}][avatar_file]" accept="image/*">
              </div>
              <div class="col-12">
                <label class="form-label small fw-semibold mb-1">Or Avatar Image Path</label>
                <input type="text" class="form-control form-control-sm" name="testimonials_data[{{ $ti }}][avatar]" value="{{ $t['avatar'] ?? '' }}" placeholder="assets/website_builder/team_1.jpg">
              </div>
              <div class="col-12">
                <label class="form-label small fw-semibold mb-1">Testimonial Review Comment</label>
                <textarea class="form-control form-control-sm" name="testimonials_data[{{ $ti }}][comment]" rows="3" placeholder="Enter review text..." required>{{ $t['comment'] ?? '' }}</textarea>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <script>
    let testCounter = {{ count($testimonials) }};
    function addTestimonialItem() {
      const container = document.getElementById('testimonialsContainer');
      const div = document.createElement('div');
      div.className = 'col-md-6 testimonial-card-item';
      div.innerHTML = `
        <div class="p-3 border rounded-3 bg-light position-relative">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-warning text-dark fw-bold px-2 py-1">New Review</span>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeTestimonialItem(this)"><i class="fa-solid fa-trash"></i></button>
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label small fw-semibold mb-1">Client Name</label>
              <input type="text" class="form-control form-control-sm" name="testimonials_data[${testCounter}][name]" value="Happy Client" placeholder="Client Name" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold mb-1">Role / Designation</label>
              <input type="text" class="form-control form-control-sm" name="testimonials_data[${testCounter}][role]" value="Customer" placeholder="e.g. Founder">
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold mb-1">Rating</label>
              <select class="form-select form-select-sm" name="testimonials_data[${testCounter}][rating]">
                <option value="5" selected>5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold mb-1">Upload Avatar</label>
              <input type="file" class="form-control form-control-sm" name="testimonials_data[${testCounter}][avatar_file]" accept="image/*">
            </div>
            <div class="col-12">
              <label class="form-label small fw-semibold mb-1">Testimonial Review Comment</label>
              <textarea class="form-control form-control-sm" name="testimonials_data[${testCounter}][comment]" rows="3" placeholder="Enter review text..." required>Great service and wonderful team!</textarea>
            </div>
          </div>
        </div>
      `;
      container.appendChild(div);
      testCounter++;
    }

    function removeTestimonialItem(btn) {
      const item = btn.closest('.testimonial-card-item');
      if (item) item.remove();
    }
  </script>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save All Testimonials
  </button>
</form>
@endsection
