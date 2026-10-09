@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Articles & Blogs - Website Admin')

@section('content')
@php
  $templateType = strtolower(trim($agency->template_type ?? session('demo_template', 'digital_agency')));
  if (!empty($agency->blogs_data)) {
      $blogsData = $agency->blogs_data;
  } else {
      if (in_array($templateType, ['texigo', 'taxigo', 'taxi'])) {
          $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createTexigoDefaultInstance();
      } elseif (in_array($templateType, ['construction', 'buildcraft', 'build'])) {
          $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createConstructionDefaultInstance();
      } elseif (in_array($templateType, ['interior', 'interiorcraft'])) {
          $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createInteriorDefaultInstance();
      } elseif (in_array($templateType, ['evently', 'event'])) {
          $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createEventlyDefaultInstance();
      } else {
          $dummy = \App\Models\WebsiteBuilder\WbAgencySetting::createDefaultInstance();
      }
      $blogsData = $dummy->blogs_data ?? [];
  }

  $defaultCat = match($templateType) {
      'texigo', 'taxigo', 'taxi' => 'Taxi & Travel',
      'construction', 'buildcraft', 'build' => 'Construction & Build',
      'interior', 'interiorcraft' => 'Interior Tips',
      'evently', 'event' => 'Event Planning',
      default => 'Design & Tech'
  };
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-newspaper text-indigo me-2" style="color: #4F46E5;"></i>Edit Articles & Blogs</h3>
    <p class="text-muted small mb-0">Manage blog post titles, category tags, author names, dates, excerpts, and images for your site.</p>
  </div>
  <div class="d-flex gap-2 align-items-center">
    <button type="submit" form="adminSettingsForm" class="btn btn-success btn-sm fw-bold shadow-sm"><i class="fa-solid fa-floppy-disk me-1"></i> Save Changes</button>
    <a href="{{ $liveUrl ?? (isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.blogs', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency.blogs')) }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Articles Page
  </a>
  </div>
</div>



<form action="{{ route('website-builder.agency-admin.blogs.update') }}" method="POST" enctype="multipart/form-data" id="adminSettingsForm">
  @csrf
  <input type="hidden" name="template_type" value="{{ $agency->template_type ?? session('demo_template', 'digital_agency') }}">

  <!-- BLOG ARTICLES SECTION CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-newspaper text-success me-2"></i>Articles & Blog Posts List</h5>
        <p class="text-muted small mb-0">Add new blog articles or edit existing ones. You can upload custom cover images for each post.</p>
      </div>
      <div class="d-flex gap-2">
        <label class="btn btn-sm btn-outline-success fw-bold px-3 rounded-pill mb-0" style="cursor: pointer;">
          <i class="fa-solid fa-upload me-1"></i> Bulk Upload
          <input type="file" multiple accept="image/*" class="d-none" onchange="handleBlogsBulkUpload(event)">
        </label>
        <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addBlog()">
          <i class="fa-solid fa-plus me-1"></i> Add Article / Blog Post
        </button>
      </div>
    </div>

    <div class="row g-4" id="blogsContainer">
      @foreach($blogsData as $bi => $blog)
        <div class="col-md-6 blog-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-success-subtle text-success border border-success fw-bold">Article #{{ $loop->iteration }}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeBlog(this)" title="Remove Article"><i class="fa-solid fa-trash-can"></i></button>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Article Title *</label>
                <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][title]" value="{{ $blog['title'] ?? '' }}" required>
              </div>

              <div class="row g-2 mb-2">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold mb-1">Category Tag</label>
                  <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][category]" value="{{ $blog['category'] ?? $defaultCat }}" placeholder="e.g. {{ $defaultCat }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-semibold mb-1">Author Name</label>
                  <input type="text" class="form-control form-control-sm" name="blogs_data[{{ $bi }}][author]" value="{{ $blog['author'] ?? 'Admin' }}" placeholder="e.g. Admin">
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

    <!-- Pagination Controls -->
    <div id="paginationControls" class="d-flex justify-content-center mt-4 gap-2"></div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save All Articles & Blogs
  </button>
</form>

<script>
  let blogCounter = {{ count($blogsData) }};
  const defaultCategoryTag = @json($defaultCat);
  const defaultImageCover  = @json($templateType === 'texigo' ? 'assets/website_builder/Templates/Texigo_agency/herobanner_image.png' : ($templateType === 'construction' ? 'assets/website_builder/Templates/Construction_agency/herobanner_image.png' : ($templateType === 'interior' ? 'assets/website_builder/Templates/Interior_agency/homepage_hero.png' : 'assets/website_builder/wb_card_agency.png')));

  function handleBlogsBulkUpload(event) {
    const files = event.target.files;
    if (!files || files.length === 0) return;
    for(let i=0; i<files.length; i++) {
        addBlog(files[i]);
    }
    event.target.value = '';
  }

  function addBlog(file = null) {
    const container = document.getElementById('blogsContainer');
    const col = document.createElement('div');
    col.className = 'col-md-6 blog-card-item';

    let defaultTitle = file ? file.name.split('.').slice(0, -1).join('.') : "New Article Title";

    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-success-subtle text-success border border-success fw-bold">New Article</span>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeBlog(this)"><i class="fa-solid fa-trash-can"></i></button>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Article Title *</label>
            <input type="text" class="form-control form-control-sm" name="blogs_data[${blogCounter}][title]" value="${defaultTitle}" required>
          </div>

          <div class="row g-2 mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-semibold mb-1">Category Tag</label>
              <input type="text" class="form-control form-control-sm" name="blogs_data[${blogCounter}][category]" value="${defaultCategoryTag}">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold mb-1">Author Name</label>
              <input type="text" class="form-control form-control-sm" name="blogs_data[${blogCounter}][author]" value="Admin">
            </div>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Publish Date</label>
            <input type="text" class="form-control form-control-sm" name="blogs_data[${blogCounter}][date]" value="${new Date().toLocaleDateString('en-US', {month:'short', day:'2-digit', year:'numeric'})}">
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Short Excerpt / Summary</label>
            <textarea class="form-control form-control-sm" name="blogs_data[${blogCounter}][excerpt]" rows="2" placeholder="Brief summary for the card view">Discover the latest industry insights and updates.</textarea>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Cover Image</label>
            <input type="file" class="form-control form-control-sm" name="blogs_data[${blogCounter}][image_file]" accept="image/*">
            <input type="hidden" name="blogs_data[${blogCounter}][image]" value="${defaultImageCover}">
          </div>
        </div>

        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-3" onclick="removeBlog(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Article
        </button>
      </div>
    `;
    container.appendChild(col);

    if (file) {
        const fileInput = col.querySelector('input[type="file"]');
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
    }

    blogCounter++;
    if (typeof renderPagination === 'function') {
        renderPagination();
    }
  }

  function removeBlog(btn) {
    const item = btn.closest('.blog-card-item');
    if (item) {
      item.remove();
      if (typeof renderPagination === 'function') {
          renderPagination();
      }
    }
  }

  const ITEMS_PER_PAGE = 4;
  let currentPage = 1;

  function renderPagination() {
      const items = document.querySelectorAll('#blogsContainer .blog-card-item');
      const totalItems = items.length;
      const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);

      if (currentPage > totalPages && totalPages > 0) {
          currentPage = totalPages;
      } else if (totalPages === 0) {
          currentPage = 1;
      }

      const paginationContainer = document.getElementById('paginationControls');
      if (paginationContainer) {
          paginationContainer.innerHTML = '';
          if (totalPages > 1) {
              for (let i = 1; i <= totalPages; i++) {
                  const btn = document.createElement('button');
                  btn.type = 'button';
                  btn.className = `btn btn-sm ${i === currentPage ? 'btn-success fw-bold' : 'btn-outline-success'}`;
                  btn.textContent = i;
                  btn.onclick = () => {
                      currentPage = i;
                      renderPagination();
                  };
                  paginationContainer.appendChild(btn);
              }
          }
      }

      showPage();
  }

  function showPage() {
      const items = document.querySelectorAll('#blogsContainer .blog-card-item');
      items.forEach((item, index) => {
          const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
          const endIndex = startIndex + ITEMS_PER_PAGE;

          if (index >= startIndex && index < endIndex) {
              item.style.display = '';
          } else {
              item.style.display = 'none';
          }
      });
  }

  document.addEventListener('DOMContentLoaded', () => {
      renderPagination();
  });
</script>
@endsection
