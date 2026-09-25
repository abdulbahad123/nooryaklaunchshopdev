@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Articles & Blogs - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-newspaper text-indigo me-2" style="color: #4F46E5;"></i>Edit Articles & Blogs</h3>
    <p class="text-muted small mb-0">Manage insights, tech guides, blog post titles, category tags, author names, dates, excerpts, and images.</p>
  </div>
  <a href="{{ $liveUrl ?? (isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.blogs', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency.blogs')) }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Articles Page
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.blogs.update') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <!-- BLOG ARTICLES SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-newspaper text-success me-2"></i>Articles & Blog Posts List</h5>
        <p class="text-muted small mb-0">Add new blog articles or edit existing ones. You can upload custom cover images for each post.</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addBlog()">
        <i class="fa-solid fa-plus me-1"></i> Add Article / Blog Post
      </button>
    </div>

    @php
      $blogsData = $agency->blogs_data ?? [
        [
          'id'          => 1,
          'title'       => '10 Modern UI/UX Trends Shaping Digital Products in 2026',
          'category'    => 'Design & Tech',
          'author'      => 'Michael Roberts',
          'date'        => 'Sep 04, 2026',
          'image'       => 'assets/website_builder/wb_card_agency.png',
          'excerpt'     => 'Discover the top design trends driving higher customer engagement and conversions for digital platforms.',
        ],
        [
          'id'          => 2,
          'title'       => 'How Strategic Branding Drives Revenue Growth for Startups',
          'category'    => 'Branding',
          'author'      => 'Sarah Johnson',
          'date'        => 'Aug 28, 2026',
          'image'       => 'assets/website_builder/wb_card_portfolio.png',
          'excerpt'     => 'Learn how a cohesive brand identity instills trust and establishes a strong competitive advantage.',
        ],
        [
          'id'          => 3,
          'title'       => 'Maximizing Search Visibility with Data-Driven SEO Tactics',
          'category'    => 'SEO & Marketing',
          'author'      => 'Jessica Brown',
          'date'        => 'Aug 15, 2026',
          'image'       => 'assets/website_builder/wb_card_startup.png',
          'excerpt'     => 'A complete guide to optimizing site speed, technical SEO, and organic ranking strategies.',
        ],
      ];
    @endphp

    <div class="row g-4" id="blogsContainer">
      @foreach($blogsData as $bi => $blog)
        <div class="col-md-6 blog-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-success-subtle text-success border border-success fw-bold">Article #{{ $bi + 1 }}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeBlog(this)" title="Remove Article"><i class="fa-solid fa-trash-can"></i></button>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Article Title *</label>
                <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][title]" value="{{ $blog['title'] ?? '' }}" required>
              </div>

              <div class="row g-2 mb-2">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold mb-1">Category Tag</label>
                  <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][category]" value="{{ $blog['category'] ?? 'Design & Tech' }}" placeholder="e.g. Design & Tech">
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-semibold mb-1">Author Name</label>
                  <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][author]" value="{{ $blog['author'] ?? 'Admin' }}" placeholder="e.g. Michael Roberts">
                </div>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Publish Date</label>
                <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][date]" value="{{ $blog['date'] ?? date('M d, Y') }}" placeholder="e.g. Sep 04, 2026">
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Short Excerpt / Summary</label>
                <textarea class="form-control form-control-sm" name="blogs_data[{{ $bi }}][excerpt]" rows="2" placeholder="Brief summary for the card view">{{ $blog['excerpt'] ?? '' }}</textarea>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Cover Image</label>
                <input type="file" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][image_file]" accept="image/*">
                <input type="hidden" name="blogs_data[{{ $bi }}][image]" value="{{ $blog['image'] ?? 'assets/website_builder/wb_card_agency.png' }}">
              </div>

              @if(!empty($blog['image']))
                <div class="mt-2 d-flex align-items-center gap-2 p-2 bg-white rounded border">
                  <span class="small fw-semibold text-muted">Preview:</span>
                  <img src="{{ str_starts_with($blog['image'], 'http') ? $blog['image'] : asset($blog['image']) }}" onerror="this.src='{{ asset('assets/website_builder/wb_card_agency.png') }}';" style="height: 42px; width: 70px; object-fit: cover; border-radius: 4px;">
                </div>
              @endif
            </div>

            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-3" onclick="removeBlog(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Article
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save All Articles & Blogs
  </button>
</form>

<script>
  let blogCounter = {{ count($blogsData) }};
  function addBlog() {
    const container = document.getElementById('blogsContainer');
    const col = document.createElement('div');
    col.className = 'col-md-6 blog-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-success-subtle text-success border border-success fw-bold">New Article</span>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeBlog(this)"><i class="fa-solid fa-trash-can"></i></button>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Article Title *</label>
            <input type="text" class="form-control form-control-sm" name="blogs_data[\${blogCounter}][title]" value="New Article Title" required>
          </div>

          <div class="row g-2 mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-semibold mb-1">Category Tag</label>
              <input type="text" class="form-control form-control-sm" name="blogs_data[\${blogCounter}][category]" value="Design & Tech">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold mb-1">Author Name</label>
              <input type="text" class="form-control form-control-sm" name="blogs_data[\${blogCounter}][author]" value="Admin">
            </div>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Publish Date</label>
            <input type="text" class="form-control form-control-sm" name="blogs_data[\${blogCounter}][date]" value="${new Date().toLocaleDateString('en-US', {month:'short', day:'2-digit', year:'numeric'})}">
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Short Excerpt / Summary</label>
            <textarea class="form-control form-control-sm" name="blogs_data[\${blogCounter}][excerpt]" rows="2" placeholder="Brief summary for the card view">Discover the latest industry insights and modern digital agency trends.</textarea>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Cover Image</label>
            <input type="file" class="form-control form-control-sm" name="blogs_data[\${blogCounter}][image_file]" accept="image/*">
            <input type="hidden" name="blogs_data[\${blogCounter}][image]" value="assets/website_builder/wb_card_agency.png">
          </div>
        </div>

        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-3" onclick="removeBlog(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Article
        </button>
      </div>
    `;
    container.appendChild(col);
    blogCounter++;
  }

  function removeBlog(btn) {
    const item = btn.closest('.blog-card-item');
    if (item) {
      item.remove();
    }
  }
</script>
@endsection
