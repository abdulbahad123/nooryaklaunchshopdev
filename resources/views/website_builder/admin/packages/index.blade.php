@extends('website_builder.admin.layout')

@section('title', 'Subscription Packages')

@section('content')
<style>
  .pkg-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }
  .pkg-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(79, 70, 229, 0.08);
    border-color: #CBD5E1;
  }
  .pkg-card.popular {
    border: 2px solid #6366F1;
    background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
  }
  .popular-ribbon {
    position: absolute;
    top: 16px;
    right: -32px;
    background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 6px 36px;
    transform: rotate(45deg);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
  }
  .price-pill {
    background: #F1F5F9;
    padding: 10px 16px;
    border-radius: 12px;
    display: inline-block;
  }
  .feature-icon-check {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #DCFCE7;
    color: #16A34A;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    margin-right: 10px;
  }
  .btn-gradient-primary {
    background: linear-gradient(135deg, #4F46E5 0%, #3730A3 100%);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 700;
    transition: all 0.2s;
  }
  .btn-gradient-primary:hover {
    background: linear-gradient(135deg, #4338CA 0%, #312E81 100%);
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
  }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
  <div>
    <h3 class="fw-bold mb-1"><i class="fa-solid fa-layer-group text-primary me-2"></i>Website Builder Subscription Tiers</h3>
    <p class="text-muted small mb-0">Configure & customize pricing packages (Starter, Pro, Business) and feature limits for your agency clients.</p>
  </div>
  <button class="btn btn-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPackageModal">
    <i class="fa-solid fa-plus me-1"></i> Add Package Tier
  </button>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<!-- Monthly / Yearly Billing Cycle Toggle Tab -->
<div class="d-flex justify-content-center mb-4">
  <div class="btn-group p-1 bg-white border rounded-pill shadow-sm" role="group" id="billingCycleGroup">
    <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold active" id="btnMonthlyTab" onclick="togglePackageBilling('monthly')">
      <i class="fa-solid fa-calendar-days me-1"></i> Monthly Plans
    </button>
    <button type="button" class="btn btn-sm btn-outline-primary border-0 rounded-pill px-4 fw-bold text-dark" id="btnYearlyTab" onclick="togglePackageBilling('yearly')">
      <i class="fa-solid fa-calendar-check me-1 text-success"></i> Yearly Plans
    </button>
  </div>
</div>

<div class="row g-4">
  @forelse($packages as $p)
    <div class="col-lg-4 col-md-6">
      <div class="pkg-card p-4 h-100 d-flex flex-column {{ $p->is_popular ? 'popular' : '' }}">
        @if($p->is_popular)
          <div class="popular-ribbon">POPULAR</div>
        @endif

        <div class="mb-3">
          <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
            <i class="fa-solid fa-crown text-warning me-1"></i> Tier #{{ $loop->iteration }}
          </span>
          <h4 class="fw-extrabold text-dark mt-2 mb-1">{{ $p->name }}</h4>
          <span class="text-muted small">{{ $p->slug }}</span>
        </div>

        <div class="price-pill mb-4">
          <div class="monthly-price-wrap">
            <div class="d-flex align-items-baseline gap-1">
              <span class="fs-4 fw-bold text-primary">₹</span>
              <span class="display-6 fw-extrabold text-dark">{{ $p->monthly_price }}</span>
              <span class="text-muted small font-normal">/ month</span>
            </div>
            <div class="text-muted small mt-1">
              Yearly Option: <strong>₹{{ $p->yearly_price }}</strong> / year
            </div>
          </div>
          <div class="yearly-price-wrap d-none">
            <div class="d-flex align-items-baseline gap-1">
              <span class="fs-4 fw-bold text-success">₹</span>
              <span class="display-6 fw-extrabold text-dark">{{ $p->yearly_price }}</span>
              <span class="text-muted small font-normal">/ year</span>
            </div>
            <div class="text-muted small mt-1 text-success fw-bold">
              <i class="fa-solid fa-tag me-1"></i> Monthly equivalent: ₹{{ round(($p->yearly_price ?? 0) / 12, 2) }}/mo
            </div>
          </div>
        </div>

        <ul class="list-unstyled mb-4 text-muted small flex-grow-1">
          <li class="mb-2.5 d-flex align-items-center">
            <span class="feature-icon-check"><i class="fa-solid fa-check"></i></span>
            <span>Portfolio Item Limit: <strong>{{ $p->portfolio_limit ?? 10 }} Items</strong></span>
          </li>
          <li class="mb-2.5 d-flex align-items-center">
            <span class="feature-icon-check"><i class="fa-solid fa-check"></i></span>
            <span>Services Limit: <strong>{{ $p->services_limit ?? 10 }} Services</strong></span>
          </li>
          <li class="mb-2.5 d-flex align-items-center">
            <span class="feature-icon-check"><i class="fa-solid fa-check"></i></span>
            <span>Blog Articles Limit: <strong>{{ $p->blog_limit ?? 10 }} Posts</strong></span>
          </li>

          <!-- Custom Domain -->
          <li class="mb-2.5 d-flex align-items-center {{ ($p->custom_domain_allowed ?? true) ? '' : 'text-muted' }}" style="{{ ($p->custom_domain_allowed ?? true) ? '' : 'text-decoration: line-through; opacity: 0.65;' }}">
            <span class="feature-icon-check {{ ($p->custom_domain_allowed ?? true) ? '' : 'bg-light text-danger' }}">
              <i class="fa-solid {{ ($p->custom_domain_allowed ?? true) ? 'fa-check' : 'fa-xmark' }}"></i>
            </span>
            <span>Custom Domain: <strong class="{{ ($p->custom_domain_allowed ?? true) ? 'text-success' : 'text-danger' }}">{{ ($p->custom_domain_allowed ?? true) ? 'Enabled' : 'Disabled' }}</strong></span>
          </li>

          <!-- Contact Form -->
          <li class="mb-2.5 d-flex align-items-center {{ ($p->contact_form_allowed ?? true) ? '' : 'text-muted' }}" style="{{ ($p->contact_form_allowed ?? true) ? '' : 'text-decoration: line-through; opacity: 0.65;' }}">
            <span class="feature-icon-check {{ ($p->contact_form_allowed ?? true) ? '' : 'bg-light text-danger' }}">
              <i class="fa-solid {{ ($p->contact_form_allowed ?? true) ? 'fa-check' : 'fa-xmark' }}"></i>
            </span>
            <span>Contact Form: <strong class="{{ ($p->contact_form_allowed ?? true) ? 'text-success' : 'text-danger' }}">{{ ($p->contact_form_allowed ?? true) ? 'Enabled' : 'Disabled' }}</strong></span>
          </li>

          <!-- Map Section -->
          <li class="mb-2.5 d-flex align-items-center {{ ($p->map_section_allowed ?? true) ? '' : 'text-muted' }}" style="{{ ($p->map_section_allowed ?? true) ? '' : 'text-decoration: line-through; opacity: 0.65;' }}">
            <span class="feature-icon-check {{ ($p->map_section_allowed ?? true) ? '' : 'bg-light text-danger' }}">
              <i class="fa-solid {{ ($p->map_section_allowed ?? true) ? 'fa-check' : 'fa-xmark' }}"></i>
            </span>
            <span>Map Section: <strong class="{{ ($p->map_section_allowed ?? true) ? 'text-success' : 'text-danger' }}">{{ ($p->map_section_allowed ?? true) ? 'Enabled' : 'Disabled' }}</strong></span>
          </li>

          <!-- Call & WhatsApp -->
          <li class="mb-2.5 d-flex align-items-center {{ ($p->call_whatsapp_allowed ?? true) ? '' : 'text-muted' }}" style="{{ ($p->call_whatsapp_allowed ?? true) ? '' : 'text-decoration: line-through; opacity: 0.65;' }}">
            <span class="feature-icon-check {{ ($p->call_whatsapp_allowed ?? true) ? '' : 'bg-light text-danger' }}">
              <i class="fa-solid {{ ($p->call_whatsapp_allowed ?? true) ? 'fa-check' : 'fa-xmark' }}"></i>
            </span>
            <span>Call & WhatsApp Buttons: <strong class="{{ ($p->call_whatsapp_allowed ?? true) ? 'text-success' : 'text-danger' }}">{{ ($p->call_whatsapp_allowed ?? true) ? 'Enabled' : 'Disabled' }}</strong></span>
          </li>

          <!-- Blog Section -->
          <li class="mb-2.5 d-flex align-items-center {{ ($p->blog_allowed ?? true) ? '' : 'text-muted' }}" style="{{ ($p->blog_allowed ?? true) ? '' : 'text-decoration: line-through; opacity: 0.65;' }}">
            <span class="feature-icon-check {{ ($p->blog_allowed ?? true) ? '' : 'bg-light text-danger' }}">
              <i class="fa-solid {{ ($p->blog_allowed ?? true) ? 'fa-check' : 'fa-xmark' }}"></i>
            </span>
            <span>Blog Section: <strong class="{{ ($p->blog_allowed ?? true) ? 'text-success' : 'text-danger' }}">{{ ($p->blog_allowed ?? true) ? 'Enabled' : 'Disabled' }}</strong></span>
          </li>
        </ul>

        <div class="d-flex gap-2 pt-3 border-top mt-auto">
          <button type="button" class="btn btn-outline-primary btn-sm rounded-3 flex-grow-1 fw-bold" 
                  data-bs-toggle="modal" data-bs-target="#editPackageModal{{ $p->id }}">
            <i class="fa-solid fa-pen-to-square me-1"></i> Edit Tier
          </button>

          <form action="{{ route('website-builder.admin.packages.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this package tier?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-3">
              <i class="fa-solid fa-trash-can"></i>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Package Modal -->
    <div class="modal fade" id="editPackageModal{{ $p->id }}" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <form action="{{ route('website-builder.admin.packages.update', $p->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header bg-light border-0 py-3 rounded-top-4">
              <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Package Tier: {{ $p->name }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Package Name *</label>
                <input type="text" class="form-control rounded-3" name="name" value="{{ $p->name }}" required>
              </div>
              <div class="row g-3 mb-3">
                <div class="col-6">
                  <label class="form-label fw-bold small text-muted">Monthly Price (₹) *</label>
                  <input type="number" step="0.01" class="form-control rounded-3" name="monthly_price" value="{{ $p->monthly_price }}" required>
                </div>
                <div class="col-6">
                  <label class="form-label fw-bold small text-muted">Yearly Price (₹) *</label>
                  <input type="number" step="0.01" class="form-control rounded-3" name="yearly_price" value="{{ $p->yearly_price }}" required>
                </div>
              </div>
              <div class="row g-3 mb-3">
                <div class="col-6">
                  <label class="form-label fw-bold small text-muted">Theme Limit (Count) *</label>
                  <input type="number" class="form-control rounded-3" name="theme_limit" value="{{ $p->theme_limit ?? 10 }}" required min="1">
                </div>
                <div class="col-6">
                  <label class="form-label fw-bold small text-muted">Storage Limit (MB)</label>
                  <input type="number" class="form-control rounded-3" name="storage_limit_mb" value="{{ $p->storage_limit_mb ?? 5000 }}">
                </div>
              </div>
              <div class="row g-3 mb-3">
                <div class="col-4">
                  <label class="form-label fw-bold small text-muted">Portfolio Limit *</label>
                  <input type="number" class="form-control rounded-3" name="portfolio_limit" value="{{ $p->portfolio_limit ?? 10 }}" required min="0">
                </div>
                <div class="col-4">
                  <label class="form-label fw-bold small text-muted">Services Limit *</label>
                  <input type="number" class="form-control rounded-3" name="services_limit" value="{{ $p->services_limit ?? 10 }}" required min="0">
                </div>
                <div class="col-4">
                  <label class="form-label fw-bold small text-muted">Blog Limit *</label>
                  <input type="number" class="form-control rounded-3" name="blog_limit" value="{{ $p->blog_limit ?? 10 }}" required min="0">
                </div>
              </div>

              <hr class="my-3">
              <p class="fw-bold small text-dark mb-2">Package Feature Controls & Toggles:</p>

              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="custom_domain_allowed" id="cd_{{ $p->id }}" {{ ($p->custom_domain_allowed ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold small text-dark" for="cd_{{ $p->id }}">Allow Custom Domains</label>
              </div>

              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="contact_form_allowed" id="cf_{{ $p->id }}" {{ ($p->contact_form_allowed ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold small text-dark" for="cf_{{ $p->id }}">Enable Contact Form</label>
              </div>

              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="map_section_allowed" id="ms_{{ $p->id }}" {{ ($p->map_section_allowed ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold small text-dark" for="ms_{{ $p->id }}">Enable Map Section</label>
              </div>

              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="call_whatsapp_allowed" id="cw_{{ $p->id }}" {{ ($p->call_whatsapp_allowed ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold small text-dark" for="cw_{{ $p->id }}">Enable Floating Call & WhatsApp Buttons</label>
              </div>

              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="blog_allowed" id="bg_{{ $p->id }}" {{ ($p->blog_allowed ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold small text-dark" for="bg_{{ $p->id }}">Enable Blog Section</label>
              </div>
            </div>
            <div class="modal-footer bg-light border-0 py-3 rounded-bottom-4">
              <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-bold">Update Tier</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12">
      <div class="card p-5 text-center text-muted rounded-4 border-0 shadow-sm">
        <i class="fa-solid fa-box-open fs-1 text-secondary mb-3"></i>
        <h5>No subscription package tiers configured yet</h5>
        <p class="small mb-3">Click the "Add Package Tier" button to create your first pricing plan.</p>
      </div>
    </div>
  @endforelse
</div>

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form action="{{ route('website-builder.admin.packages.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-light border-0 py-3 rounded-top-4">
          <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Create New Subscription Tier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Package Name *</label>
            <input type="text" class="form-control rounded-3" name="name" placeholder="e.g. Starter Plan" required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-bold small text-muted">Monthly Price (₹) *</label>
              <input type="number" step="0.01" class="form-control rounded-3" name="monthly_price" placeholder="499" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-bold small text-muted">Yearly Price (₹) *</label>
              <input type="number" step="0.01" class="form-control rounded-3" name="yearly_price" placeholder="999" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-bold small text-muted">Theme Limit (Count) *</label>
              <input type="number" class="form-control rounded-3" name="theme_limit" value="5" required min="1">
            </div>
            <div class="col-6">
              <label class="form-label fw-bold small text-muted">Storage Limit (MB)</label>
              <input type="number" class="form-control rounded-3" name="storage_limit_mb" value="5000">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-4">
              <label class="form-label fw-bold small text-muted">Portfolio Limit *</label>
              <input type="number" class="form-control rounded-3" name="portfolio_limit" value="10" required min="0">
            </div>
            <div class="col-4">
              <label class="form-label fw-bold small text-muted">Services Limit *</label>
              <input type="number" class="form-control rounded-3" name="services_limit" value="10" required min="0">
            </div>
            <div class="col-4">
              <label class="form-label fw-bold small text-muted">Blog Limit *</label>
              <input type="number" class="form-control rounded-3" name="blog_limit" value="10" required min="0">
            </div>
          </div>

          <hr class="my-3">
          <p class="fw-bold small text-dark mb-2">Package Feature Controls & Toggles:</p>

          <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="custom_domain_allowed" id="cd_new" checked>
            <label class="form-check-label fw-semibold small text-dark" for="cd_new">Allow Custom Domains</label>
          </div>

          <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="contact_form_allowed" id="cf_new" checked>
            <label class="form-check-label fw-semibold small text-dark" for="cf_new">Enable Contact Form</label>
          </div>

          <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="map_section_allowed" id="ms_new" checked>
            <label class="form-check-label fw-semibold small text-dark" for="ms_new">Enable Map Section</label>
          </div>

          <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="call_whatsapp_allowed" id="cw_new" checked>
            <label class="form-check-label fw-semibold small text-dark" for="cw_new">Enable Floating Call & WhatsApp Buttons</label>
          </div>

          <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="blog_allowed" id="bg_new" checked>
            <label class="form-check-label fw-semibold small text-dark" for="bg_new">Enable Blog Section</label>
          </div>
        </div>
        <div class="modal-footer bg-light border-0 py-3 rounded-bottom-4">
          <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-bold">Create Package</button>
        </div>
      </form>
    </div>
  </div>
</div>

@section('scripts')
<script>
function togglePackageBilling(cycle) {
  const btnMonthly = document.getElementById('btnMonthlyTab');
  const btnYearly = document.getElementById('btnYearlyTab');
  const monthlyWraps = document.querySelectorAll('.monthly-price-wrap');
  const yearlyWraps = document.querySelectorAll('.yearly-price-wrap');

  if (cycle === 'yearly') {
    monthlyWraps.forEach(el => el.classList.add('d-none'));
    yearlyWraps.forEach(el => el.classList.remove('d-none'));
    if (btnMonthly) btnMonthly.className = 'btn btn-sm btn-outline-primary border-0 rounded-pill px-4 fw-bold text-dark';
    if (btnYearly) btnYearly.className = 'btn btn-sm btn-success rounded-pill px-4 fw-bold active';
  } else {
    yearlyWraps.forEach(el => el.classList.add('d-none'));
    monthlyWraps.forEach(el => el.classList.remove('d-none'));
    if (btnYearly) btnYearly.className = 'btn btn-sm btn-outline-primary border-0 rounded-pill px-4 fw-bold text-dark';
    if (btnMonthly) btnMonthly.className = 'btn btn-sm btn-primary rounded-pill px-4 fw-bold active';
  }
}
</script>
@endsection
@endsection
