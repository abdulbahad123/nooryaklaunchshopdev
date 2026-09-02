<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Dashboard - Website Builder</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('website-builder.user.dashboard') }}"><i class="fa-solid fa-layer-group text-primary me-2"></i> {{ $customer->company_name ?? 'My Website' }}</a>
      <div class="d-flex align-items-center gap-3">
        <span class="text-white-50 small">Logged in as: <strong>{{ $customer->name ?? 'Client Admin' }}</strong></span>
        <a href="{{ route('website-builder.index') }}" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-right-from-bracket me-1"></i> Exit Panel</a>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="fw-bold">My Multi-Page Website</h3>
        <p class="text-muted small">Manage your pages, custom domain, and website sections.</p>
      </div>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newPageModal"><i class="fa-solid fa-plus me-1"></i> Create New Page</button>
    </div>

    @if(session('is_secret_logged_in'))
      <div class="alert alert-warning border-warning d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-shield-halved fs-4"></i>
        <div>
          <strong>Admin Secret Access Mode:</strong> You are currently managing this customer website via single-click Admin Secret Access.
        </div>
      </div>
    @endif

    <div class="row g-4">
      @forelse($pages as $p)
        <div class="col-md-4">
          <div class="card p-4 h-100 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <h5 class="fw-bold mb-0">{{ $p->title }}</h5>
              @if($p->is_home)
                <span class="badge bg-primary">Homepage</span>
              @endif
            </div>
            <p class="text-muted small">URL Slug: <code>/{{ $p->slug }}</code></p>
            <a href="{{ route('website-builder.user.pages.editor', $p->id) }}" class="btn btn-outline-primary btn-sm mt-auto fw-bold"><i class="fa-solid fa-pen-to-square me-1"></i> Edit Page Sections</a>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="card p-5 text-center text-muted">
            No pages created yet. Click "Create New Page" to start building.
          </div>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Create Page Modal -->
  <div class="modal fade" id="newPageModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('website-builder.user.pages.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Create New Page</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Page Title</label>
              <input type="text" class="form-control" name="title" placeholder="e.g. About Us" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create & Launch Editor</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
