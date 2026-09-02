<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Page Section Editor - {{ $page->title }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
    .editor-header { background: #1e293b; padding: 16px 24px; border-bottom: 1px solid #334155; }
    .section-preview { background: #1e293b; border: 1px dashed #475569; border-radius: 12px; padding: 30px; margin-bottom: 24px; }
  </style>
</head>
<body>

  <div class="editor-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
      <a href="{{ route('website-builder.user.dashboard') }}" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard</a>
      <h5 class="fw-bold mb-0">Editing: {{ $page->title }}</h5>
    </div>
    <div>
      <span class="badge bg-success me-2">Live Sync</span>
      <a href="{{ route('website-builder.index') }}" target="_blank" class="btn btn-sm btn-primary"><i class="fa-solid fa-eye me-1"></i> Preview Live Site</a>
    </div>
  </div>

  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0">Drag & Drop Page Sections</h4>
      <button class="btn btn-sm btn-outline-primary" onclick="alert('Section block added!');"><i class="fa-solid fa-plus me-1"></i> Add New Section Block</button>
    </div>

    @forelse($page->sections as $sec)
      <div class="section-preview">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge bg-info text-dark text-uppercase fw-bold">{{ $sec->type }} Section</span>
          <button class="btn btn-sm btn-outline-light" onclick="alert('Editing content for section {{ $sec->id }}');"><i class="fa-solid fa-sliders me-1"></i> Edit Section Properties</button>
        </div>
        <div class="p-4 bg-dark rounded-3 border border-secondary">
          <h3 class="fw-bold">{{ $sec->content['headline'] ?? 'Section Title' }}</h3>
          <p class="text-muted">{{ $sec->content['subtitle'] ?? 'Section description and content content details.' }}</p>
        </div>
      </div>
    @empty
      <div class="section-preview text-center text-muted">
        No section blocks added to this page yet.
      </div>
    @endforelse
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
