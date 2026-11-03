@extends('website_builder.agency_template.admin.layout')

@section('title', 'Upcoming Events & Showcase Section — Admin Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-extrabold text-dark mb-1">Upcoming Events & Showcase Section</h3>
    <p class="text-secondary small mb-0">Customize the left-side brand story ("Why Choose Us") and right-side featured upcoming event highlight rendered on your homepage.</p>
  </div>
  <div class="d-flex gap-2">
    <button type="submit" form="adminSettingsForm" class="btn btn-primary btn-sm fw-bold shadow-sm px-4">
      <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
    </button>
    @if(isset($customer) && !empty($customer->subdomain))
      <a href="{{ route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Site
      </a>
    @endif
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@php
  $whyEyebrow = $agency->why_eyebrow ?? 'WHY CHOOSE EVENTLY';
  $whyScript = $agency->why_script_text ?? "Your Vision\nOur Passion";
  $whyTitle = $agency->why_title ?? "More Than Events,\nWe Create Experiences";
  $whyDesc = $agency->why_desc ?? 'We combine creativity, expertise, and passion to deliver events that leave a lasting impression.';
  $whyImage = $agency->why_image ?? 'assets/website_builder/Templates/Evently/why_evently.png';
  $whyImgUrl = str_starts_with($whyImage, 'http') ? $whyImage : asset(ltrim($whyImage, '/'));

  $rawWhyBullets = $agency->why_bullets_data ?? null;
  if (is_string($rawWhyBullets)) {
      $dec = json_decode($rawWhyBullets, true);
      $rawWhyBullets = is_string($dec) ? json_decode($dec, true) : $dec;
  }
  $whyBullets = (is_array($rawWhyBullets) && count($rawWhyBullets) > 0)
    ? $rawWhyBullets
    : [
        ['icon' => 'fa-solid fa-bullseye', 'text' => 'Personalized Planning'],
        ['icon' => 'fa-solid fa-lightbulb', 'text' => 'Innovative Concepts'],
        ['icon' => 'fa-solid fa-handshake', 'text' => 'Trusted Network'],
        ['icon' => 'fa-solid fa-scale-balanced', 'text' => 'Stress-Free Execution'],
      ];

  $hlBadge = $agency->event_highlight_badge ?? 'UPCOMING EVENT HIGHLIGHT';
  $hlTitle = $agency->event_highlight_title ?? 'Global Business Summit 2024';
  $hlLocation = $agency->event_highlight_location ?? 'New York, USA';
  $hlDate = $agency->event_highlight_date ?? '24 - 26 Nov 2024';
  $hlDesc = $agency->event_highlight_desc ?? 'Join industry leaders, innovators, and thinkers for three days of inspiration, networking, and growth.';
  $hlBtnText = $agency->event_highlight_btn_text ?? 'Register Now';
  $hlBtnUrl = $agency->event_highlight_btn_url ?? '/contact';
  $hlImage = $agency->event_highlight_image ?? 'assets/website_builder/Templates/Evently/event_speaker_stage.png';
  $hlImgUrl = str_starts_with($hlImage, 'http') ? $hlImage : asset(ltrim($hlImage, '/'));
  $hlAttendees = $agency->event_highlight_attendees ?? '500+ Attendees Expected';
@endphp

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST" enctype="multipart/form-data" id="adminSettingsForm">
  @csrf
  <input type="hidden" name="template_type" value="{{ $agency->template_type ?? session('demo_template', 'evently') }}">

  <div class="row g-4">
    <!-- LEFT CARD: WHY CHOOSE US & BRAND STORY -->
    <div class="col-lg-6">
      <div class="card card-editor p-4 h-100 shadow-sm border-0" style="border-top: 4px solid #6C3CE1 !important;">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-primary-subtle text-primary rounded-3 p-2 me-2">
            <i class="fa-solid fa-wand-magic-sparkles fa-lg"></i>
          </div>
          <div>
            <h5 class="fw-bold mb-0 text-dark">Left Side Showcase</h5>
            <small class="text-muted">Why Choose Us, Brand Story & Highlight Bullets</small>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark">Section Eyebrow Badge</label>
          <input type="text" class="form-control" name="why_eyebrow" value="{{ $whyEyebrow }}" placeholder="e.g. WHY CHOOSE EVENTLY">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark">Top Cursive Script Text</label>
          <input type="text" class="form-control" name="why_script_text" value="{{ $whyScript }}" placeholder="e.g. Your Vision / Our Passion">
          <div class="form-text small text-muted">Displays in elegant cursive script in the top-right corner.</div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark">Main Section Title</label>
          <textarea class="form-control" name="why_title" rows="2" placeholder="e.g. More Than Events, We Create Experiences">{{ $whyTitle }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark">Section Subtitle / Description</label>
          <textarea class="form-control" name="why_desc" rows="3" placeholder="Enter paragraph description...">{{ $whyDesc }}</textarea>
        </div>

        <!-- Left Background Image -->
        <div class="mb-3 p-3 bg-light rounded-3 border">
          <label class="form-label small fw-bold text-dark mb-1">Background Image</label>
          <div class="row g-2 align-items-center mb-2">
            <div class="col-sm-6">
              <label class="form-label small text-muted mb-0">Upload Image File</label>
              <input type="file" class="form-control form-control-sm" name="why_image_file" accept="image/*">
            </div>
            <div class="col-sm-6">
              <label class="form-label small text-muted mb-0">Or Enter Image URL</label>
              <input type="text" class="form-control form-control-sm" name="why_image" value="{{ $whyImage }}" placeholder="e.g. assets/website_builder/Templates/Evently/why_evently.png">
            </div>
          </div>
          @if(!empty($whyImage))
            <div class="d-flex align-items-center gap-2 mt-2">
              <span class="small fw-semibold text-muted">Current:</span>
              <img src="{{ $whyImgUrl }}" alt="Why Preview" style="max-height: 48px; border-radius: 6px; object-fit: cover; border: 1px solid #ddd;">
            </div>
          @endif
        </div>

        <!-- 4 Highlight Feature Bullets -->
        <div class="p-3 bg-light rounded-3 border">
          <label class="form-label small fw-bold text-dark mb-2">4 Highlight Feature Pills</label>
          <div class="row g-2">
            @for($b = 0; $b < 4; $b++)
              @php
                $curBullet = $whyBullets[$b] ?? ['icon' => 'fa-solid fa-check', 'text' => ''];
              @endphp
              <div class="col-12 col-sm-6">
                <div class="p-2 bg-white rounded-2 border">
                  <div class="small fw-bold text-primary mb-1">Pill #{{ $b + 1 }}</div>
                  <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text"><i class="fa-solid fa-icons"></i></span>
                    <input type="text" class="form-control" name="why_bullets_data[{{ $b }}][icon]" value="{{ $curBullet['icon'] ?? 'fa-solid fa-check' }}" placeholder="FontAwesome Icon Class">
                  </div>
                  <input type="text" class="form-control form-control-sm" name="why_bullets_data[{{ $b }}][text]" value="{{ $curBullet['text'] ?? '' }}" placeholder="Pill Title">
                </div>
              </div>
            @endfor
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT CARD: UPCOMING EVENT HIGHLIGHT -->
    <div class="col-lg-6">
      <div class="card card-editor p-4 h-100 shadow-sm border-0" style="border-top: 4px solid #10B981 !important;">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-success-subtle text-success rounded-3 p-2 me-2">
            <i class="fa-solid fa-calendar-star fa-lg"></i>
          </div>
          <div>
            <h5 class="fw-bold mb-0 text-dark">Right Side Highlight Box</h5>
            <small class="text-muted">Featured Upcoming Event, Details & Registration Button</small>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark">Highlight Badge Pill</label>
          <input type="text" class="form-control" name="event_highlight_badge" value="{{ $hlBadge }}" placeholder="e.g. UPCOMING EVENT HIGHLIGHT">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark">Featured Event Title</label>
          <input type="text" class="form-control" name="event_highlight_title" value="{{ $hlTitle }}" placeholder="e.g. Global Business Summit 2024">
        </div>

        <div class="row g-2 mb-3">
          <div class="col-sm-6">
            <label class="form-label small fw-semibold text-dark">Location</label>
            <input type="text" class="form-control" name="event_highlight_location" value="{{ $hlLocation }}" placeholder="e.g. New York, USA">
          </div>
          <div class="col-sm-6">
            <label class="form-label small fw-semibold text-dark">Date / Schedule Info</label>
            <input type="text" class="form-control" name="event_highlight_date" value="{{ $hlDate }}" placeholder="e.g. 24 - 26 Nov 2024">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark">Event Description / Summary</label>
          <textarea class="form-control" name="event_highlight_desc" rows="3" placeholder="Summary of the featured event...">{{ $hlDesc }}</textarea>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-sm-6">
            <label class="form-label small fw-semibold text-dark">CTA Button Text</label>
            <input type="text" class="form-control" name="event_highlight_btn_text" value="{{ $hlBtnText }}" placeholder="e.g. Register Now">
          </div>
          <div class="col-sm-6">
            <label class="form-label small fw-semibold text-dark">CTA Button URL</label>
            <input type="text" class="form-control" name="event_highlight_btn_url" value="{{ $hlBtnUrl }}" placeholder="e.g. /contact or #contact">
          </div>
        </div>

        <!-- Right Stage Image & Attendees Badge -->
        <div class="mb-3 p-3 bg-light rounded-3 border">
          <label class="form-label small fw-bold text-dark mb-1">Featured Event Stage Photo</label>
          <div class="row g-2 align-items-center mb-2">
            <div class="col-sm-6">
              <label class="form-label small text-muted mb-0">Upload Stage Image File</label>
              <input type="file" class="form-control form-control-sm" name="event_highlight_image_file" accept="image/*">
            </div>
            <div class="col-sm-6">
              <label class="form-label small text-muted mb-0">Or Enter Stage Image URL</label>
              <input type="text" class="form-control form-control-sm" name="event_highlight_image" value="{{ $hlImage }}" placeholder="e.g. assets/website_builder/Templates/Evently/event_speaker_stage.png">
            </div>
          </div>
          @if(!empty($hlImage))
            <div class="d-flex align-items-center gap-2 mt-2">
              <span class="small fw-semibold text-muted">Current:</span>
              <img src="{{ $hlImgUrl }}" alt="Stage Preview" style="max-height: 48px; border-radius: 6px; object-fit: cover; border: 1px solid #ddd;">
            </div>
          @endif
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-dark">Attendees Pill Badge</label>
          <input type="text" class="form-control" name="event_highlight_attendees" value="{{ $hlAttendees }}" placeholder="e.g. 500+ Attendees Expected">
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-end mt-4">
    <button type="submit" class="btn btn-primary px-5 py-2.5 fw-bold rounded-pill shadow-sm">
      <i class="fa-solid fa-floppy-disk me-2"></i> Save Section Changes
    </button>
  </div>
</form>
@endsection
