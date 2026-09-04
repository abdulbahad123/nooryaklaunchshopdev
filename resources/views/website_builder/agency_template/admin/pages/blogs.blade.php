@extends('website_builder.agency_template.admin.layout')

@section('title', 'Manage Blogs - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-newspaper text-indigo me-2" style="color: #4F46E5;"></i>Manage News & Articles</h3>
    <p class="text-muted small mb-0">Add, edit, upload cover images, or delete blog articles displayed on your website.</p>
  </div>
  <button type="button" class="btn btn-success fw-bold px-3 rounded-pill" onclick="addBlog()">
    <i class="fa-solid fa-plus me-1"></i> Add New Article
  </button>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.blogs.update') }}" method="POST" enctype="multipart/form-data">
  @csrf

  @php
    $blogs = $agency->blogs_data ?? [
      [
        'id'          => 1,
        'title'       => '10 Modern UI/UX Trends Shaping Digital Products in 2026',
        'category'    => 'Design & Tech',
        'author'      => 'Michael Roberts',
        'date'        => 'Sep 04, 2026',
        'image'       => 'assets/website_builder/wb_card_agency.png',
        'excerpt'     => 'Discover the top design trends driving higher customer engagement and conversions for digital platforms.',
        'content'     => 'In 2026, user experience design continues to evolve at a breakneck pace. Modern audiences expect seamless performance, vibrant dark-mode aesthetics, micro-interactions, and instant accessibility.',
      ]
    ];
  @endphp

  <div class="row g-4" id="blogsContainer">
    @foreach($blogs as $bi => $b)
      <div class="col-md-6 blog-card-item">
        <div class="card card-editor p-4 h-100 position-relative d-flex flex-column justify-content-between">
          <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="badge bg-success-subtle text-success border border-success fw-bold px-3 py-1">Article #{{ $bi + 1 }}</span>
              <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBlog(this)"><i class="fa-solid fa-trash me-1"></i> Delete Article</button>
            </div>

            <input type="hidden" name="blogs_data[{{ $bi }}][id]" value="{{ $b['id'] ?? ($bi + 1) }}">

            <div class="row g-3">
              <div class="col-md-12">
                <label class="form-label small fw-bold">Article Title</label>
                <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][title]" value="{{ $b['title'] ?? '' }}" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold">Category Tag</label>
                <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][category]" value="{{ $b['category'] ?? 'Design & Tech' }}">
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold">Author Name</label>
                <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][author]" value="{{ $b['author'] ?? 'Admin' }}">
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold">Publish Date</label>
                <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][date]" value="{{ $b['date'] ?? date('M d, Y') }}">
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold">Upload Cover Image</label>
                <input type="file" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][image_file]" accept="image/*">
                <input type="hidden" name="blogs_data[{{ $bi }}][image]" value="{{ $b['image'] ?? 'assets/website_builder/wb_card_agency.png' }}">
              </div>

              @if(!empty($b['image']))
                <div class="col-md-12">
                  <div class="d-flex align-items-center gap-2 p-2 bg-light rounded border">
                    <span class="small fw-semibold text-muted">Thumbnail Preview:</span>
                    <img src="{{ str_starts_with($b['image'], 'http') ? $b['image'] : asset($b['image']) }}" style="height: 40px; width: 60px; object-fit: cover; border-radius: 4px;">
                  </div>
                </div>
              @endif

              <div class="col-md-12">
                <label class="form-label small fw-bold">Short Excerpt (Displayed on Homepage Slider)</label>
                <textarea class="form-control form-control-sm" name="blogs_data[{{ $bi }}][excerpt]" rows="2">{{ $b['excerpt'] ?? '' }}</textarea>
              </div>

              <div class="col-md-12">
                <label class="form-label small fw-bold">Full Article Body Content</label>
                <textarea class="form-control form-control-sm" name="blogs_data[{{ $bi }}][content]" rows="5">{{ $b['content'] ?? '' }}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5 mt-4">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save All Articles & Blogs
  </button>
</form>

<script>
  let blogCounter = {{ count($blogs) }};
  function addBlog() {
    const container = document.getElementById('blogsContainer');
    const col = document.createElement('div');
    col.className = 'col-md-6 blog-card-item';
    const newId = blogCounter + 1;
    const dateStr = new Date().toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
    col.innerHTML = `
      <div class="card card-editor p-4 h-100 position-relative d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-success-subtle text-success border border-success fw-bold px-3 py-1">New Article</span>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBlog(this)"><i class="fa-solid fa-trash me-1"></i> Delete Article</button>
          </div>
          <input type="hidden" name="blogs_data[${blogCounter}][id]" value="${newId}">
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label small fw-bold">Article Title</label>
              <input type="text" class="form-control form-control-sm" name="blogs_data[${blogCounter}][title]" value="New Article Title" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Category Tag</label>
              <input type="text" class="form-control form-control-sm" name="blogs_data[${blogCounter}][category]" value="Technology">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Author Name</label>
              <input type="text" class="form-control form-control-sm" name="blogs_data[${blogCounter}][author]" value="Admin">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Publish Date</label>
              <input type="text" class="form-control form-control-sm" name="blogs_data[${blogCounter}][date]" value="${dateStr}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Upload Cover Image</label>
              <input type="file" class="form-control form-control-sm" name="blogs_data[${blogCounter}][image_file]" accept="image/*">
              <input type="hidden" name="blogs_data[${blogCounter}][image]" value="assets/website_builder/wb_card_agency.png">
            </div>
            <div class="col-md-12">
              <label class="form-label small fw-bold">Short Excerpt (Displayed on Homepage Slider)</label>
              <textarea class="form-control form-control-sm" name="blogs_data[${blogCounter}][excerpt]" rows="2">Short excerpt summarizing the key insights of this article.</textarea>
            </div>
            <div class="col-md-12">
              <label class="form-label small fw-bold">Full Article Body Content</label>
              <textarea class="form-control form-control-sm" name="blogs_data[${blogCounter}][content]" rows="5">Write your full article body content here...</textarea>
            </div>
          </div>
        </div>
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
