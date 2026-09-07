@extends('website_builder.agency_template.admin.layout')

@section('title', 'Header & Footer Settings - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-sliders text-indigo me-2" style="color: #4F46E5;"></i>Header & Footer Settings</h3>
    <p class="text-muted small mb-0">Manage logo, top announcement bar, header contact details, footer description, address, map, and social media links.</p>
  </div>
  <a href="{{ $liveUrl ?? (isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency')) }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
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

  <!-- 1. HEADER & LOGO BRANDING CARD -->
  <div class="card card-editor p-4 mb-4" style="border-left: 4px solid #10B981;">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-paintbrush text-success me-2"></i>Header Logo & Announcement Bar</h5>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Upload Site Logo (PNG, JPG, SVG)</label>
        <input type="file" class="form-control" name="site_logo_file" accept="image/*">
        <div class="form-text small text-muted">Upload a logo file to display in your header & footer.</div>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Or Enter Logo Image URL / Path</label>
        <input type="text" class="form-control" name="site_logo" value="{{ $agency->site_logo ?? '' }}" placeholder="e.g. uploads/website_builder/logo.png">
      </div>
      @if(isset($agency->site_logo) && !empty($agency->site_logo))
      <div class="col-md-12">
        <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
          <span class="small fw-bold text-muted">Current Active Logo:</span>
          <img src="{{ str_starts_with($agency->site_logo, 'http') ? $agency->site_logo : asset($agency->site_logo) }}" alt="Current Logo" style="max-height: 50px; max-width: 200px; object-fit: contain;">
        </div>
      </div>
      @endif

      <div class="col-md-6">
        <label class="form-label fw-semibold small">Site Brand Name (Text Logo Fallback)</label>
        <input type="text" class="form-control" name="site_title" value="{{ $agency->site_title ?? 'DesignAGENCY' }}">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold small">Header Top Announcement Bar Text</label>
        <input type="text" class="form-control" name="top_announcement" value="{{ $agency->top_announcement ?? 'We help businesses grow with creative digital solutions.' }}">
      </div>
    </div>
  </div>

  <!-- 2. CONTACT DETAILS & FOOTER DESCRIPTION CARD -->
  <div class="card card-editor p-4 mb-4" style="border-left: 4px solid #4F46E5;">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-envelope-open-text text-indigo me-2" style="color: #4F46E5;"></i>Header & Footer Contact Information</h5>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Support Email Address</label>
        <input type="email" class="form-control" name="email" value="{{ $agency->email ?? 'info@designagency.com' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Support Phone Number</label>
        <input type="text" class="form-control" name="phone" value="{{ $agency->phone ?? '+1 (234) 567-890' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Office Address (Map Location)</label>
        <input type="text" class="form-control" id="officeAddressInput" name="address" value="{{ $agency->address ?? '123 Design Street, Creative City, CA 90403' }}" onchange="updateMapPreview(this.value)">
      </div>

      <div class="col-md-12">
        <label class="form-label fw-semibold small">Footer Description Text</label>
        <textarea class="form-control" name="footer_text" rows="3">{{ $agency->footer_text ?? 'We are a creative digital agency helping businesses grow with modern design, development & marketing solutions.' }}</textarea>
      </div>

      <div class="col-md-12 mt-3">
        <label class="form-label fw-semibold small text-success"><i class="fa-solid fa-map-location-dot me-1"></i> Live Google Map Location Preview</label>
        <div class="border rounded-3 overflow-hidden" style="height: 220px;">
          <iframe id="mapPreviewIframe" width="100%" height="220" frameborder="0" style="border:0;"
            src="https://maps.google.com/maps?width=100%25&amp;height=220&amp;hl=en&amp;q={{ urlencode($agency->address ?? '123 Design Street, Creative City, CA 90403') }}&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
            allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. SOCIAL MEDIA LINKS CARD -->
  <div class="card card-editor p-4 mb-4" style="border-left: 4px solid #F59E0B;">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-share-nodes text-warning me-2"></i>Social Media Links (Header & Footer)</h5>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold small"><i class="fa-brands fa-facebook-f text-primary me-1"></i> Facebook Page URL</label>
        <input type="text" class="form-control" name="social_links[facebook]" value="{{ $agency->social_links['facebook'] ?? '#' }}" placeholder="https://facebook.com/youragency">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small"><i class="fa-brands fa-x-twitter text-dark me-1"></i> Twitter / X Profile URL</label>
        <input type="text" class="form-control" name="social_links[twitter]" value="{{ $agency->social_links['twitter'] ?? '#' }}" placeholder="https://x.com/youragency">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small"><i class="fa-brands fa-linkedin-in text-info me-1"></i> LinkedIn Profile URL</label>
        <input type="text" class="form-control" name="social_links[linkedin]" value="{{ $agency->social_links['linkedin'] ?? '#' }}" placeholder="https://linkedin.com/company/youragency">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small"><i class="fa-brands fa-instagram text-danger me-1"></i> Instagram Profile URL</label>
        <input type="text" class="form-control" name="social_links[instagram]" value="{{ $agency->social_links['instagram'] ?? '#' }}" placeholder="https://instagram.com/youragency">
      </div>
    </div>
  </div>

  <!-- 4. FOOTER QUICK LINKS CARD -->
  <div class="card card-editor p-4 mb-4" style="border-left: 4px solid #3B82F6;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-link text-primary me-2"></i>Footer Quick Links</h5>
        <p class="text-muted small mb-0">Manage navigation links displayed in the Footer Quick Links column (Add, Edit Title/URL, or Delete).</p>
      </div>
      <button type="button" class="btn btn-sm btn-primary fw-bold px-3 rounded-pill" onclick="addQuickLink()">
        <i class="fa-solid fa-plus me-1"></i> Add Quick Link
      </button>
    </div>

    @php
      $quickLinksData = $agency->footer_quick_links ?? [
        ['title' => 'Home',       'url' => '#'],
        ['title' => 'About Us',   'url' => '#about'],
        ['title' => 'Portfolio',  'url' => '#portfolio'],
        ['title' => 'Contact Us', 'url' => '#contact'],
      ];
    @endphp

    <div class="row g-2" id="quickLinksContainer">
      @foreach($quickLinksData as $qi => $q)
        <div class="col-md-6 quick-link-item">
          <div class="p-3 bg-light border rounded-3 d-flex align-items-center gap-2">
            <input type="text" class="form-control form-control-sm" name="footer_quick_links[{{ $qi }}][title]" value="{{ $q['title'] ?? '' }}" placeholder="Link Title" required>
            <input type="text" class="form-control form-control-sm" name="footer_quick_links[{{ $qi }}][url]" value="{{ $q['url'] ?? '#' }}" placeholder="URL (e.g. #about or /page)">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLinkItem(this)" title="Delete Link"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- 5. FOOTER LEGAL & POLICIES CARD -->
  <div class="card card-editor p-4 mb-4" style="border-left: 4px solid #8B5CF6;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-shield-halved text-purple me-2" style="color: #8B5CF6;"></i>Footer Legal & Privacy Policy Links</h5>
        <p class="text-muted small mb-0">Manage legal, privacy policy, and terms links displayed in the Footer Legal & Policies column.</p>
      </div>
      <button type="button" class="btn btn-sm btn-purple fw-bold px-3 rounded-pill text-white" style="background: #8B5CF6;" onclick="addLegalLink()">
        <i class="fa-solid fa-plus me-1"></i> Add Policy Link
      </button>
    </div>

    @php
      $legalLinksData = $agency->footer_legal_links ?? [
        ['title' => 'Privacy Policy',     'url' => '#privacy'],
        ['title' => 'Terms & Conditions', 'url' => '#terms'],
        ['title' => 'Disclaimer',         'url' => '#disclaimer'],
        ['title' => 'Refund Policy',      'url' => '#refund'],
      ];
    @endphp

    <div class="row g-2" id="legalLinksContainer">
      @foreach($legalLinksData as $li => $l)
        <div class="col-md-6 legal-link-item">
          <div class="p-3 bg-light border rounded-3 d-flex align-items-center gap-2">
            <input type="text" class="form-control form-control-sm" name="footer_legal_links[{{ $li }}][title]" value="{{ $l['title'] ?? '' }}" placeholder="Policy Title" required>
            <input type="text" class="form-control form-control-sm" name="footer_legal_links[{{ $li }}][url]" value="{{ $l['url'] ?? '#' }}" placeholder="URL (e.g. #privacy or /page)">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLinkItem(this)" title="Delete Link"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <script>
    function updateMapPreview(address) {
      const iframe = document.getElementById('mapPreviewIframe');
      if (iframe && address) {
        iframe.src = 'https://maps.google.com/maps?width=100%25&height=220&hl=en&q=' + encodeURIComponent(address) + '&t=&z=14&ie=UTF8&iwloc=B&output=embed';
      }
    }

    let quickCounter = {{ count($quickLinksData) }};
    function addQuickLink() {
      const container = document.getElementById('quickLinksContainer');
      const div = document.createElement('div');
      div.className = 'col-md-6 quick-link-item';
      div.innerHTML = `
        <div class="p-3 bg-light border rounded-3 d-flex align-items-center gap-2">
          <input type="text" class="form-control form-control-sm" name="footer_quick_links[${quickCounter}][title]" value="New Link" placeholder="Link Title" required>
          <input type="text" class="form-control form-control-sm" name="footer_quick_links[${quickCounter}][url]" value="#" placeholder="URL">
          <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLinkItem(this)"><i class="fa-solid fa-trash"></i></button>
        </div>
      `;
      container.appendChild(div);
      quickCounter++;
    }

    let legalCounter = {{ count($legalLinksData) }};
    function addLegalLink() {
      const container = document.getElementById('legalLinksContainer');
      const div = document.createElement('div');
      div.className = 'col-md-6 legal-link-item';
      div.innerHTML = `
        <div class="p-3 bg-light border rounded-3 d-flex align-items-center gap-2">
          <input type="text" class="form-control form-control-sm" name="footer_legal_links[${legalCounter}][title]" value="Privacy Policy" placeholder="Policy Title" required>
          <input type="text" class="form-control form-control-sm" name="footer_legal_links[${legalCounter}][url]" value="#" placeholder="URL">
          <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLinkItem(this)"><i class="fa-solid fa-trash"></i></button>
        </div>
      `;
      container.appendChild(div);
      legalCounter++;
    }

    function removeLinkItem(btn) {
      const item = btn.closest('.quick-link-item, .legal-link-item');
      if (item) item.remove();
    }
  </script>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save All Header & Footer Settings
  </button>
</form>
@endsection
