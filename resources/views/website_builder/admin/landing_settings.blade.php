@extends('website_builder.admin.layout')

@section('title', 'Landing Page Content & Colors Editor')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Landing Page Content & Colors</h3>
    <p class="text-muted small mb-0">Edit every section that appears on the public website builder single-page landing. <a href="{{ route('website-builder.index') }}" target="_blank" class="text-primary ms-1">Preview Site <i class="fa-solid fa-external-link-alt ms-1"></i></a></p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success py-2 mb-3"><i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}</div>
@endif

<form action="{{ route('website-builder.admin.landing-settings.update') }}" method="POST" enctype="multipart/form-data">
@csrf

<!-- Nav Tabs -->
<ul class="nav nav-tabs mb-4 border-bottom flex-wrap" id="settingsTabs">
  <li class="nav-item"><button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-branding"><i class="fa-solid fa-palette me-1"></i> Branding</button></li>
  <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-hero"><i class="fa-solid fa-heading me-1"></i> Hero</button></li>
  <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-who"><i class="fa-solid fa-users me-1"></i> Who / Use Cases</button></li>
  <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-process"><i class="fa-solid fa-shoe-prints me-1"></i> Process</button></li>
  <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-features"><i class="fa-solid fa-list me-1"></i> Features</button></li>
  <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-templates"><i class="fa-solid fa-layer-group me-1"></i> Templates</button></li>
  <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-pricing"><i class="fa-solid fa-tag me-1"></i> Pricing</button></li>
  <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-testimonials"><i class="fa-solid fa-comment-dots me-1"></i> Testimonials</button></li>
  <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-cta"><i class="fa-solid fa-rocket me-1"></i> CTA Banner</button></li>
  <li class="nav-item"><button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-contact"><i class="fa-solid fa-envelope me-1"></i> Contact & Footer</button></li>
</ul>

<div class="tab-content">

  <!-- ===== BRANDING TAB ===== -->
  <div class="tab-pane fade show active" id="tab-branding">
    <div class="card p-4 mb-3">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-palette me-2 text-primary"></i>Brand Theme Colors</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Primary Brand Color <span class="text-muted">(buttons, accents)</span></label>
          <div class="input-group">
            <input type="color" class="form-control form-control-color" name="primary_color" value="{{ $settings->primary_color ?? '#5B4BF5' }}" id="primaryColorPicker">
            <input type="text" class="form-control" id="primaryColorText" value="{{ $settings->primary_color ?? '#5B4BF5' }}" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Secondary Accent Color <span class="text-muted">(hover states)</span></label>
          <div class="input-group">
            <input type="color" class="form-control form-control-color" name="secondary_color" value="{{ $settings->secondary_color ?? '#7C6CF8' }}" id="secondaryColorPicker">
            <input type="text" class="form-control" id="secondaryColorText" value="{{ $settings->secondary_color ?? '#7C6CF8' }}" readonly>
          </div>
        </div>
      </div>
    </div>
    <div class="card p-4">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-code me-2 text-primary"></i>Custom CSS <span class="text-muted small fw-normal">(Advanced)</span></h5>
      <textarea class="form-control font-monospace" name="custom_css" rows="6" placeholder="/* Add any custom overrides here */">{{ $settings->custom_css ?? '' }}</textarea>
      <small class="text-muted mt-1 d-block">This CSS is injected at the bottom of the public landing page.</small>
    </div>
  </div>

  <!-- ===== HERO TAB ===== -->
  <div class="tab-pane fade" id="tab-hero">
    <div class="card p-4 mb-3">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-heading me-2 text-primary"></i>Hero Section Content</h5>
      <div class="row g-3">
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Badge / Label Text <span class="text-muted">(small pill above title)</span></label>
          <input type="text" class="form-control" name="hero_badge" value="{{ $settings->hero_badge ?? '⚡ No-coding required' }}" required>
        </div>
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Hero Main Title <span class="text-muted">(use newline for line breaks)</span></label>
          <textarea class="form-control" name="hero_title" rows="3" required>{{ $settings->hero_title ?? "Build Your Website\nin Just Few Minutes" }}</textarea>
        </div>
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Hero Subtitle / Description</label>
          <textarea class="form-control" name="hero_subtitle" rows="3" required>{{ $settings->hero_subtitle ?? 'Create beautiful, professional websites in minutes with our intuitive drag-and-drop builder and AI-powered features.' }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Primary CTA Button Text</label>
          <input type="text" class="form-control" name="cta_primary_text" value="{{ $settings->cta_primary_text ?? 'Get Started Free' }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Primary CTA Button URL</label>
          <input type="text" class="form-control" name="cta_primary_url" value="{{ $settings->cta_primary_url ?? '#pricing' }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Secondary CTA Button Text</label>
          <input type="text" class="form-control" name="cta_secondary_text" value="{{ $settings->cta_secondary_text ?? 'View Templates' }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Secondary CTA Button URL</label>
          <input type="text" class="form-control" name="cta_secondary_url" value="{{ $settings->cta_secondary_url ?? '#templates' }}" required>
        </div>
      </div>
    </div>
    <div class="card p-4">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-image me-2 text-primary"></i>Hero Mockup / Dashboard Image</h5>
      <div class="row g-3">
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Upload Hero Graphic <span class="text-muted">(replaces the right-side mockup). PNG/JPG/WEBP, max 5MB.</span></label>
          <input type="file" class="form-control" name="hero_image_file" accept="image/jpeg,image/png,image/webp,image/gif">
          @if($settings->hero_image ?? null)
            <div class="mt-3 d-flex align-items-center gap-3">
              <img src="{{ asset($settings->hero_image) }}" style="height: 80px; border-radius: 8px; border: 1px solid #ddd; object-fit: cover;">
              <span class="small text-muted">Current hero image — upload a new one to replace it.</span>
            </div>
          @else
            <div class="alert alert-info mt-2 py-2 small"><i class="fa-solid fa-info-circle me-1"></i> No hero image uploaded yet. The built-in mockup UI will be shown instead.</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- ===== FEATURES TAB ===== -->
  <div class="tab-pane fade" id="tab-features">
    <div class="card p-4 mb-3">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-heading me-2 text-primary"></i>Section Headings</h5>
      <div class="row g-3">
        <div class="col-md-3"><label class="form-label fw-semibold small">Label (pill)</label><input type="text" class="form-control" name="features_label" value="{{ $settings->features_label ?? 'Features' }}"></div>
        <div class="col-md-9"><label class="form-label fw-semibold small">Main Heading</label><input type="text" class="form-control" name="features_heading" value="{{ $settings->features_heading ?? 'Everything You Need' }}"></div>
        <div class="col-md-12"><label class="form-label fw-semibold small">Subtitle</label><input type="text" class="form-control" name="features_subtitle" value="{{ $settings->features_subtitle ?? \"We've packed all the technical heavy lifting into a simple interface.\" }}"></div>
      </div>
    </div>
    <div class="card p-4">
      <h5 class="fw-bold mb-1"><i class="fa-solid fa-list me-2 text-primary"></i>Features Grid <span class="text-muted small fw-normal">("Everything You Need" section)</span></h5>
      <p class="text-muted small mb-4">Edit the 8 feature items shown in the features grid. Use Font Awesome class names for icons (e.g. <code>fa-mobile-screen</code>).</p>
      @php
        $featuresData = $settings->features_data ?? [
          ['icon' => 'fa-mobile-screen', 'title' => 'Mobile Optimized', 'desc' => 'Looks perfect on every screen size.'],
          ['icon' => 'fa-magnifying-glass', 'title' => 'SEO Ready', 'desc' => 'Built to rank high on Google search.'],
          ['icon' => 'fa-globe', 'title' => 'Custom Domain', 'desc' => 'Connect your own .com instantly.'],
          ['icon' => 'fa-bolt', 'title' => 'Fast Hosting', 'desc' => 'Lightning-fast load times globally.'],
          ['icon' => 'fa-shield-halved', 'title' => 'Secure (SSL)', 'desc' => 'Free security certificate included.'],
          ['icon' => 'fa-chart-line', 'title' => 'Analytics', 'desc' => 'Track your visitors easily.'],
          ['icon' => 'fa-wand-magic-sparkles', 'title' => 'AI Page Rewriter', 'desc' => 'Regenerate or improve any section content anytime.'],
          ['icon' => 'fa-award', 'title' => 'Client-Ready White Label', 'desc' => 'Create & manage websites under your own brand.'],
        ];
      @endphp
      <div class="row g-3">
        @foreach($featuresData as $fi => $feat)
          <div class="col-md-6">
            <div class="border rounded-3 p-3 bg-light">
              <div class="fw-bold small text-primary mb-2">Feature {{ $fi + 1 }}</div>
              <div class="row g-2">
                <div class="col-4">
                  <label class="form-label small fw-semibold">Icon (FA class)</label>
                  <input type="text" class="form-control form-control-sm" name="features_data[{{ $fi }}][icon]" value="{{ $feat['icon'] }}">
                </div>
                <div class="col-8">
                  <label class="form-label small fw-semibold">Title</label>
                  <input type="text" class="form-control form-control-sm" name="features_data[{{ $fi }}][title]" value="{{ $feat['title'] }}">
                </div>
                <div class="col-12">
                  <label class="form-label small fw-semibold">Description</label>
                  <input type="text" class="form-control form-control-sm" name="features_data[{{ $fi }}][desc]" value="{{ $feat['desc'] }}">
                </div>
              </div>
            </div>
  </div>

  <!-- ===== WHO / USE CASES TAB ===== -->
  <div class="tab-pane fade" id="tab-who">
    <div class="card p-4 mb-3">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-users me-2 text-primary"></i>"Who Is It For" Section</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Section Label (pill text)</label>
          <input type="text" class="form-control" name="who_label" value="{{ $settings->who_label ?? "Who it's for" }}">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Brand Name in Heading</label>
          <input type="text" class="form-control" name="who_brand_name" value="{{ $settings->who_brand_name ?? 'website builder' }}">
        </div>
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Sub-heading</label>
          <input type="text" class="form-control" name="who_subtitle" value="{{ $settings->who_subtitle ?? 'Perfect for Every Business & Creator' }}">
        </div>
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Description Paragraph</label>
          <textarea class="form-control" name="who_description" rows="3">{{ $settings->who_description ?? "Whether you're launching a personal brand, a portfolio, a local business site, or online store — website builder makes it simple." }}</textarea>
        </div>
      </div>
    </div>
    <div class="card p-4 mb-3">
      <h5 class="fw-bold mb-1"><i class="fa-solid fa-grid me-2 text-primary"></i>Audience Cards (6 tiles)</h5>
      <p class="text-muted small mb-4">Edit the 6 audience tiles shown. Use FontAwesome class names (e.g. <code>fa-rocket</code>).</p>
      @php $audiencesData = $settings->audiences_data ?? [['icon'=>'fa-user-circle','title'=>'Freelancers','color'=>'#5B4BF5'],['icon'=>'fa-rocket','title'=>'Startups','color'=>'#06B6D4'],['icon'=>'fa-briefcase','title'=>'Agencies','color'=>'#F59E0B'],['icon'=>'fa-store','title'=>'Shops','color'=>'#EC4899'],['icon'=>'fa-pen-nib','title'=>'Bloggers','color'=>'#8B5CF6'],['icon'=>'fa-calendar-days','title'=>'Events','color'=>'#EF4444']]; @endphp
      <div class="row g-2">
        @foreach($audiencesData as $ai => $aud)
          <div class="col-md-4">
            <div class="border rounded-3 p-2 bg-light">
              <div class="fw-bold small text-primary mb-1">Tile {{ $ai+1 }}</div>
              <div class="row g-1">
                <div class="col-4"><label class="form-label small fw-semibold">Icon</label><input type="text" class="form-control form-control-sm" name="audiences_data[{{ $ai }}][icon]" value="{{ $aud['icon'] }}"></div>
                <div class="col-4"><label class="form-label small fw-semibold">Title</label><input type="text" class="form-control form-control-sm" name="audiences_data[{{ $ai }}][title]" value="{{ $aud['title'] }}"></div>
                <div class="col-4"><label class="form-label small fw-semibold">Color</label><input type="color" class="form-control form-control-color" name="audiences_data[{{ $ai }}][color]" value="{{ $aud['color'] }}"></div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="card p-4">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-images me-2 text-primary"></i>"Built for Visionaries" (Use Cases) Section</h5>
      <div class="row g-3">
        <div class="col-md-4"><label class="form-label fw-semibold small">Section Label</label><input type="text" class="form-control" name="usecases_label" value="{{ $settings->usecases_label ?? 'Use Cases' }}"></div>
        <div class="col-md-8"><label class="form-label fw-semibold small">Section Title</label><input type="text" class="form-control" name="usecases_title" value="{{ $settings->usecases_title ?? 'Built for Visionaries' }}"></div>
        <div class="col-md-12"><label class="form-label fw-semibold small">Subtitle</label><input type="text" class="form-control" name="usecases_subtitle" value="{{ $settings->usecases_subtitle ?? \"Whether you're a freelancer or a founder, we have the perfect starting point.\" }}"></div>
      </div>
    </div>
  </div>

  <div class="tab-pane fade" id="tab-process">
    <div class="card p-4">
      <h5 class="fw-bold mb-1"><i class="fa-solid fa-shoe-prints me-2 text-primary"></i>Launch in 3 Simple Steps</h5>
      <p class="text-muted small mb-4">Edit the 3 process steps shown in the "Process" section.</p>
      @php
        $processData = $settings->process_data ?? [
          ['step' => '01', 'title' => 'Choose a Template', 'desc' => 'Select from our gallery of professionally designed, conversion-optimized templates.'],
          ['step' => '02', 'title' => 'Customize Content', 'desc' => 'Use our visual editor to update text, images, and colors to match your brand.'],
          ['step' => '03', 'title' => 'Publish to World', 'desc' => 'Connect your custom domain and go live with a single click. SSL included.'],
        ];
      @endphp
      <div class="row g-3">
        @foreach($processData as $pi => $step)
          <div class="col-md-4">
            <div class="border rounded-3 p-3 bg-light">
              <div class="fw-bold small text-primary mb-2">Step {{ $pi + 1 }}</div>
              <div class="mb-2">
                <label class="form-label small fw-semibold">Step Number Label</label>
                <input type="text" class="form-control form-control-sm" name="process_data[{{ $pi }}][step]" value="{{ $step['step'] }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold">Step Title</label>
                <input type="text" class="form-control form-control-sm" name="process_data[{{ $pi }}][title]" value="{{ $step['title'] }}">
              </div>
              <div>
                <label class="form-label small fw-semibold">Step Description</label>
                <textarea class="form-control form-control-sm" name="process_data[{{ $pi }}][desc]" rows="3">{{ $step['desc'] }}</textarea>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- ===== TEMPLATES TAB ===== -->
  <div class="tab-pane fade" id="tab-templates">
    <div class="card p-4">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-layer-group me-2 text-primary"></i>Templates Section Headings</h5>
      <div class="row g-3">
        <div class="col-md-3"><label class="form-label fw-semibold small">Label (pill)</label><input type="text" class="form-control" name="templates_label" value="{{ $settings->templates_label ?? 'Templates' }}"></div>
        <div class="col-md-9"><label class="form-label fw-semibold small">Main Heading</label><input type="text" class="form-control" name="templates_heading" value="{{ $settings->templates_heading ?? 'Start with a Professional Template' }}"></div>
        <div class="col-md-12"><label class="form-label fw-semibold small">Subtitle</label><input type="text" class="form-control" name="templates_subtitle" value="{{ $settings->templates_subtitle ?? 'Choose a design you love and make it yours.' }}"></div>
      </div>
      <div class="mt-3 alert alert-info py-2 small mb-0"><i class="fa-solid fa-circle-info me-1"></i> Individual templates can be added and managed under the <strong>Templates Manager</strong> in the admin sidebar.</div>
    </div>
  </div>

  <!-- ===== PRICING TAB ===== -->
  <div class="tab-pane fade" id="tab-pricing">
    <div class="card p-4">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-tag me-2 text-primary"></i>Pricing Section Headings</h5>
      <div class="row g-3">
        <div class="col-md-3"><label class="form-label fw-semibold small">Label (pill)</label><input type="text" class="form-control" name="pricing_label" value="{{ $settings->pricing_label ?? 'Pricing' }}"></div>
        <div class="col-md-9"><label class="form-label fw-semibold small">Main Heading</label><input type="text" class="form-control" name="pricing_heading" value="{{ $settings->pricing_heading ?? 'Simple, Transparent Pricing' }}"></div>
        <div class="col-md-12"><label class="form-label fw-semibold small">Subtitle</label><input type="text" class="form-control" name="pricing_subtitle" value="{{ $settings->pricing_subtitle ?? 'Choose the perfect plan for your needs' }}"></div>
      </div>
      <div class="mt-3 alert alert-info py-2 small mb-0"><i class="fa-solid fa-circle-info me-1"></i> Pricing plans/packages are managed under the <strong>Packages Manager</strong> in the admin sidebar.</div>
    </div>
  </div>

  <!-- ===== TESTIMONIALS TAB ===== -->
  <div class="tab-pane fade" id="tab-testimonials">
    <div class="card p-4 mb-3">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-heading me-2 text-primary"></i>Section Headings</h5>
      <div class="row g-3">
        <div class="col-md-3"><label class="form-label fw-semibold small">Label (pill)</label><input type="text" class="form-control" name="testimonials_label" value="{{ $settings->testimonials_label ?? 'Testimonials' }}"></div>
        <div class="col-md-9"><label class="form-label fw-semibold small">Main Heading</label><input type="text" class="form-control" name="testimonials_heading" value="{{ $settings->testimonials_heading ?? 'Loved by Thousands of Customers' }}"></div>
      </div>
    </div>
    <div class="card p-4">
      <h5 class="fw-bold mb-1"><i class="fa-solid fa-comment-dots me-2 text-primary"></i>Customer Testimonials</h5>
      <p class="text-muted small mb-4">Edit testimonials shown in the "Loved by Thousands" section. Rating 1–5 stars.</p>
      @php
        $testimonialsData = $settings->testimonials_data ?? [
          ['name' => 'Sarah Johnson', 'role' => 'Small Business Owner', 'rating' => 5, 'comment' => '"website builder made it so easy to create our business website. The templates are beautiful and the support is excellent!"', 'avatar' => null],
          ['name' => 'Mike Chen', 'role' => 'Freelance Designer', 'rating' => 5, 'comment' => '"As a freelancer, I needed a professional portfolio fast. website builder delivered exactly what I needed."', 'avatar' => null],
          ['name' => 'Emily Davis', 'role' => 'Marketing Manager', 'rating' => 5, 'comment' => '"The AI tools and ease of use are incredible. I built my entire website in just a few hours!"', 'avatar' => null],
        ];
      @endphp
      <div class="row g-3">
        @foreach($testimonialsData as $ti => $testi)
          <div class="col-md-4">
            <div class="border rounded-3 p-3 bg-light">
              <div class="fw-bold small text-primary mb-2">Testimonial {{ $ti + 1 }}</div>
              <div class="mb-2">
                <label class="form-label small fw-semibold">Customer Name</label>
                <input type="text" class="form-control form-control-sm" name="testimonials_data[{{ $ti }}][name]" value="{{ $testi['name'] }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold">Role / Title</label>
                <input type="text" class="form-control form-control-sm" name="testimonials_data[{{ $ti }}][role]" value="{{ $testi['role'] }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold">Star Rating (1–5)</label>
                <input type="number" class="form-control form-control-sm" name="testimonials_data[{{ $ti }}][rating]" value="{{ $testi['rating'] }}" min="1" max="5">
              </div>
              <div>
                <label class="form-label small fw-semibold">Review Comment</label>
                <textarea class="form-control form-control-sm" name="testimonials_data[{{ $ti }}][comment]" rows="3">{{ $testi['comment'] }}</textarea>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- ===== CTA BANNER TAB ===== -->
  <div class="tab-pane fade" id="tab-cta">
    <div class="card p-4">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-rocket me-2 text-primary"></i>Bottom Call to Action Banner</h5>
      <div class="row g-3">
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Banner Main Title</label>
          <input type="text" class="form-control" name="cta_banner_title" value="{{ $settings->cta_banner_title ?? 'Start Your Professional Website Today' }}">
        </div>
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Banner Subtitle</label>
          <textarea class="form-control" name="cta_banner_subtitle" rows="2">{{ $settings->cta_banner_subtitle ?? 'Join thousands of successful businesses who trust website builder for their online presence.' }}</textarea>
        </div>
        @php
          $trustItems = $settings->cta_banner_trust ?? ['No credit card required', 'Free forever plan', 'Cancel anytime'];
        @endphp
        <div class="col-md-4">
          <label class="form-label fw-semibold small">Trust Badge 1</label>
          <input type="text" class="form-control" name="cta_banner_trust[0]" value="{{ $trustItems[0] ?? '' }}">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold small">Trust Badge 2</label>
          <input type="text" class="form-control" name="cta_banner_trust[1]" value="{{ $trustItems[1] ?? '' }}">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold small">Trust Badge 3</label>
          <input type="text" class="form-control" name="cta_banner_trust[2]" value="{{ $trustItems[2] ?? '' }}">
        </div>
      </div>
    </div>
  </div>

  <!-- ===== CONTACT & FOOTER TAB ===== -->
  <div class="tab-pane fade" id="tab-contact">
    <div class="card p-4 mb-3">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-heading me-2 text-primary"></i>Contact Section Headings</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Contact Heading</label>
          <input type="text" class="form-control" name="contact_heading" value="{{ $settings->contact_heading ?? "Let's Build Something Amazing Together" }}">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Contact Subtitle</label>
          <input type="text" class="form-control" name="contact_subtitle" value="{{ $settings->contact_subtitle ?? "Have questions? We're here to help!" }}">
        </div>
      </div>
    </div>
    <div class="card p-4 mb-3">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-envelope me-2 text-primary"></i>Contact Info</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Support Email</label>
          <input type="email" class="form-control" name="contact_email" value="{{ $settings->contact_email ?? 'hello@websitebuilder.com' }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Support Phone</label>
          <input type="text" class="form-control" name="contact_phone" value="{{ $settings->contact_phone ?? '+1 (800) 123-4567' }}">
        </div>
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Office Address</label>
          <input type="text" class="form-control" name="contact_address" value="{{ $settings->contact_address ?? '123 Business St, Suite 100, New York, NY 10001' }}">
        </div>
      </div>
    </div>
    <div class="card p-4">
      <h5 class="fw-bold mb-3"><i class="fa-solid fa-copyright me-2 text-primary"></i>Footer Branding</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Footer Brand Name</label>
          <input type="text" class="form-control" name="footer_brand_name" value="{{ $settings->footer_brand_name ?? 'website builder' }}">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold small">Copyright Line</label>
          <input type="text" class="form-control" name="footer_copyright" value="{{ $settings->footer_copyright ?? '© ' . date('Y') . ' website builder. All rights reserved.' }}">
        </div>
        <div class="col-md-12">
          <label class="form-label fw-semibold small">Footer Description Text</label>
          <textarea class="form-control" name="footer_text" rows="2">{{ $settings->footer_text ?? 'The easiest way to build professional websites. No coding required.' }}</textarea>
        </div>
      </div>
    </div>
  </div>

</div><!-- end tab-content -->

<div class="d-flex gap-3 mt-4">
  <button type="submit" class="btn btn-primary btn-lg fw-bold px-5"><i class="fa-solid fa-save me-2"></i> Save All Changes</button>
  <a href="{{ route('website-builder.index') }}" target="_blank" class="btn btn-outline-secondary btn-lg"><i class="fa-solid fa-eye me-2"></i> Preview Live Site</a>
</div>

</form>

<script>
  // Color picker sync
  document.getElementById('primaryColorPicker')?.addEventListener('input', function() {
    document.getElementById('primaryColorText').value = this.value;
  });
  document.getElementById('secondaryColorPicker')?.addEventListener('input', function() {
    document.getElementById('secondaryColorText').value = this.value;
  });
</script>
@endsection
