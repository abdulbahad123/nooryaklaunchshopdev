@extends('website_builder.interior_template.layout')

@section('title', 'About Us - InteriorCRAFT Architectural & Design Studio')

@section('content')

<!-- Header Banner -->
<section class="py-5" style="background: var(--ic-secondary-dark); color: #ffffff;">
  <div class="ic-container text-center py-4">
    <span class="ic-sub-badge" style="background: rgba(255,255,255,0.15); color: #fff;">OUR PHILOSOPHY</span>
    <h1 class="ic-heading-serif text-white display-4 mb-3">About InteriorCRAFT</h1>
    <p class="text-white-50 mx-auto" style="max-width: 600px; font-size: 16px;">
      We bridge structural architecture with refined interior aesthetics, crafting spaces that inspire and endure.
    </p>
  </div>
</section>

<!-- Our Story / Vision -->
<section class="ic-section">
  <div class="ic-container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="position-relative">
          <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1000&auto=format&fit=crop" alt="Interior Architecture Design" class="img-fluid rounded-4 shadow-lg">
          <div class="position-absolute bottom-0 end-0 m-4 p-4 bg-white rounded-3 shadow-sm border d-none d-sm-block" style="max-width: 240px;">
            <div class="ic-heading-serif text-dark fs-2 mb-0">15+</div>
            <div class="text-muted small">Years of bespoke design excellence</div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <span class="ic-sub-badge">OUR STORY</span>
        <h2 class="ic-heading-serif ic-section-title">Designing Spaces That Reflect Pure Harmony</h2>
        <p class="text-muted mb-4" style="line-height: 1.8;">
          Founded in 2011, InteriorCRAFT began with a simple belief: interior design is not merely decoration—it is the art of curating atmosphere, spatial rhythm, and personal identity. Over the past decade, our multidisciplinary team of interior architects, timber craftsmen, and lighting designers have transformed over 350 luxury residences and commercial headquarters.
        </p>
        <p class="text-muted mb-4" style="line-height: 1.8;">
          Every project starts with deep listening. We analyze natural light vectors, spatial proportions, and material acoustics to ensure your environment is as functional as it is breathtaking.
        </p>

        <div class="row g-3">
          <div class="col-sm-6">
            <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-3 border">
              <div class="ic-stat-icon-box" style="width: 44px; height: 44px; font-size: 18px;"><i class="fa-solid fa-leaf"></i></div>
              <div>
                <div class="fw-bold fs-6">Sustainable Materials</div>
                <div class="text-muted small">FSC-Certified Timber</div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-3 border">
              <div class="ic-stat-icon-box" style="width: 44px; height: 44px; font-size: 18px;"><i class="fa-solid fa-cubes"></i></div>
              <div>
                <div class="fw-bold fs-6">3D Photorealism</div>
                <div class="text-muted small">Virtual Render Walkthroughs</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Design Values -->
<section class="ic-section" style="background: #F4F2EE;">
  <div class="ic-container">
    <div class="ic-section-header">
      <span class="ic-sub-badge">OUR PRINCIPLES</span>
      <h2 class="ic-heading-serif ic-section-title">Driven by Timeless Design Values</h2>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 border-0 p-4 bg-white rounded-4 shadow-sm text-center">
          <div class="ic-stat-icon-box mx-auto mb-3" style="width: 60px; height: 60px; font-size: 24px;">
            <i class="fa-solid fa-compass-drafting"></i>
          </div>
          <h3 class="ic-heading-serif fs-5 mb-2">Bespoke Precision</h3>
          <p class="text-muted small">We create custom cabinetry, tailored textiles, and bespoke architectural details engineered specifically for your footprint.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 border-0 p-4 bg-white rounded-4 shadow-sm text-center">
          <div class="ic-stat-icon-box mx-auto mb-3" style="width: 60px; height: 60px; font-size: 24px;">
            <i class="fa-solid fa-sun"></i>
          </div>
          <h3 class="ic-heading-serif fs-5 mb-2">Natural Light Flow</h3>
          <p class="text-muted small">Optimizing windows, glass partitions, and reflective surfaces to maximize sunlight and reduce artificial energy draw.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 border-0 p-4 bg-white rounded-4 shadow-sm text-center">
          <div class="ic-stat-icon-box mx-auto mb-3" style="width: 60px; height: 60px; font-size: 24px;">
            <i class="fa-solid fa-feather-pointed"></i>
          </div>
          <h3 class="ic-heading-serif fs-5 mb-2">Enduring Elegance</h3>
          <p class="text-muted small">Avoiding short-lived trends in favor of natural marble, warm oak, brushed brass, and rich textures that age gracefully.</p>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
