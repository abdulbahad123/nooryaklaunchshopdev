@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Counter / Stats Section - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-calculator text-indigo me-2" style="color: #4F46E5;"></i>Edit Counter / Stats Section</h3>
    <p class="text-muted small mb-0">Update statistical numbers, badges, labels, and icons displayed across all theme homepages and about pages.</p>
  </div>
  <a href="{{ $liveUrl ?? (isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency')) }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Live Website
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST">
  @csrf

  <div class="card card-editor p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-arrow-up-right-dots text-success me-2"></i>Manage Counter & Achievements</h5>
        <p class="text-muted small mb-0">Add, edit or remove stat counters (e.g. 8+ Years of Experience, 120+ Projects Completed, 98% Satisfaction, 24/7 Support).</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addCounterItem()">
        <i class="fa-solid fa-plus me-1"></i> Add Counter Item
      </button>
    </div>

    @php
      $statsData = $agency->stats_data ?? [
        ['number' => '8+',   'label' => 'Years of Experience', 'icon' => 'fa-building-columns'],
        ['number' => '120+', 'label' => 'Projects Completed',   'icon' => 'fa-envelope'],
        ['number' => '98%',  'label' => 'Client Satisfaction',  'icon' => 'fa-circle-check'],
        ['number' => '24/7', 'label' => 'Support Available',   'icon' => 'fa-headset'],
      ];
    @endphp

    <div class="row g-3" id="counterItemsContainer">
      @foreach($statsData as $ci => $st)
        <div class="col-md-3 counter-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between shadow-sm">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold small text-success"><i class="fa-solid fa-hashtag me-1"></i> Counter #{{ $ci + 1 }}</div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeCounterItem(this)" title="Remove Counter"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Number / Count (e.g. 8+, 120+)</label>
                <input type="text" class="form-control form-control-sm fw-bold text-dark" name="stats_data[{{ $ci }}][number]" value="{{ $st['number'] ?? ($st['num'] ?? '') }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Stat Label / Title</label>
                <input type="text" class="form-control form-control-sm" name="stats_data[{{ $ci }}][label]" value="{{ $st['label'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Icon Class (FontAwesome)</label>
                <input type="text" class="form-control form-control-sm" name="stats_data[{{ $ci }}][icon]" value="{{ $st['icon'] ?? 'fa-chart-line' }}">
              </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeCounterItem(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Item
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save Counter Section
  </button>
</form>

<script>
  let counterItemIndex = {{ count($statsData) }};
  function addCounterItem() {
    const container = document.getElementById('counterItemsContainer');
    const col = document.createElement('div');
    col.className = 'col-md-3 counter-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between shadow-sm">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-bold small text-success"><i class="fa-solid fa-hashtag me-1"></i> New Counter</div>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeCounterItem(this)" title="Remove Counter"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Number / Count (e.g. 50+)</label>
            <input type="text" class="form-control form-control-sm fw-bold text-dark" name="stats_data[${counterItemIndex}][number]" value="100+">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Stat Label / Title</label>
            <input type="text" class="form-control form-control-sm" name="stats_data[${counterItemIndex}][label]" value="Happy Clients">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Icon Class (FontAwesome)</label>
            <input type="text" class="form-control form-control-sm" name="stats_data[${counterItemIndex}][icon]" value="fa-users">
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeCounterItem(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Item
        </button>
      </div>
    `;
    container.appendChild(col);
    counterItemIndex++;
  }

  function removeCounterItem(btn) {
    const item = btn.closest('.counter-card-item');
    if (item) {
      item.remove();
    }
  }
</script>
@endsection
