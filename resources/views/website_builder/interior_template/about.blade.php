@extends('website_builder.interior_template.layout')

@section('title', 'About Us - InterioCRAFT Architectural & Interior Design Studio')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.portfolio');
@endphp

<!-- ===== ABOUT HERO SECTION ===== -->
<section class="ic-hero">
  <div class="ic-container">
    <div class="ic-hero-grid">
      <div>
        <span class="ic-pill-badge">ABOUT US ——</span>
        <h1 class="ic-heading ic-hero-title">
          We Design More Than Spaces,<br>We Design <span class="ic-cursive" style="font-size: 64px; color: var(--ic-primary);">Better Lives</span>
        </h1>
        <p class="ic-hero-subtitle">
          {{ $interior->about_hero_subtitle ?? 'We help individuals and businesses transform their spaces through thoughtful design, creativity, and a deep understanding of modern living.' }}
        </p>

        <div class="ic-hero-actions">
          <a href="{{ $portfolioUrl }}" class="ic-btn ic-btn-dark">
            Our Portfolio <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="{{ $contactUrl }}" class="ic-btn ic-btn-outline" style="border-radius: var(--ic-radius-pill); border: 1.5px solid var(--ic-border); color: var(--ic-text-dark); padding: 12px 26px; font-weight: 700; text-decoration: none;">
            Contact Us
          </a>
        </div>

        <!-- Social Proof / Avatars Row -->
        <div class="d-flex align-items-center gap-3 pt-3 border-top">
          <div class="d-flex" style="margin-left: 10px;">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white shadow-sm" style="width: 40px; height: 40px; margin-left: -12px; object-fit: cover;" alt="Client">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white shadow-sm" style="width: 40px; height: 40px; margin-left: -12px; object-fit: cover;" alt="Client">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white shadow-sm" style="width: 40px; height: 40px; margin-left: -12px; object-fit: cover;" alt="Client">
            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=100&auto=format&fit=crop" class="rounded-circle border border-2 border-white shadow-sm" style="width: 40px; height: 40px; margin-left: -12px; object-fit: cover;" alt="Client">
          </div>
          <div>
            <div style="font-weight: 800; font-size: 16px; color: var(--ic-text-dark); line-height: 1.1;">250+</div>
            <div style="font-size: 12px; color: var(--ic-text-muted);">Happy Homeowners</div>
          </div>
          <div class="border-start ps-3 ms-2">
            <div style="font-size: 12.5px; color: var(--ic-text-muted); font-weight: 600; max-width: 180px; line-height: 1.3;">
              Trusted by Families,<br>Businesses & Builders
            </div>
          </div>
        </div>
      </div>

      <!-- Right Showcase Photo & Floating Badge -->
      <div class="position-relative">
        <div class="rounded-4 overflow-hidden shadow-lg border" style="background: #EAE6DF; height: 480px; position: relative;">
          <img src="{{ str_starts_with($interior->about_hero_image ?? '', 'http') ? $interior->about_hero_image : asset(ltrim($interior->about_hero_image ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1000&auto=format&fit=crop', '/')) }}"
               onerror="this.src='https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1000&auto=format&fit=crop';"
               alt="About InterioCRAFT" style="width: 100%; height: 100%; object-fit: cover;">

          <!-- Art Frame -->
          <div class="position-absolute top-0 end-0 m-4 p-3 bg-white rounded-3 shadow-sm border text-center" style="width: 120px;">
            <div style="font-size: 12px; font-weight: 700; color: #333; line-height: 1.3;">
              Good<br>Spaces<br><span style="color: var(--ic-primary);">Brighter</span><br>Lives
            </div>
          </div>

          <!-- Bottom Floating Badge (8+ Years Experience) -->
          <div class="position-absolute bottom-0 end-0 m-4 p-3 bg-white rounded-4 shadow-lg border d-flex align-items-center gap-3" style="max-width: 290px;">
            <div class="ic-stat-circle" style="width: 48px; height: 48px; font-size: 20px;">
              <i class="fa-solid fa-couch"></i>
            </div>
            <div>
              <div style="font-size: 22px; font-weight: 800; color: var(--ic-text-dark); line-height: 1;">8+</div>
              <div style="font-size: 12px; color: var(--ic-text-muted);">Years of Experience</div>
            </div>
            <div class="border-start ps-2 text-center">
              <span class="ic-cursive" style="font-size: 20px; color: #333; line-height: 1;">Redefining<br>Interiors</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== OUR STORY & MISSION/VISION/VALUES ===== -->
<section class="py-5" style="background: #ffffff;">
  <div class="ic-container">
    <div class="row g-5 align-items-start">
      <!-- Left Story Content -->
      <div class="col-lg-5">
        <span class="ic-pill-badge">OUR STORY ——</span>
        <h2 class="ic-heading display-6 mb-4">A Journey Built On Passion & Purpose</h2>
        <p class="text-muted mb-3" style="line-height: 1.7; font-size: 14.5px;">
          {{ $interior->story_text ?? 'InterioCRAFT was founded in 2018 with a simple idea — to make exceptional interior design accessible to everyone.' }}
        </p>
        <p class="text-muted mb-4" style="line-height: 1.7; font-size: 14.5px;">
          What started as a small team of design enthusiasts has now grown into a full-service interior design studio, trusted by homeowners, businesses, and developers across the country.
        </p>

        <!-- Founder Bio Box -->
        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-4 border">
          <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=150&auto=format&fit=crop" class="rounded-circle object-fit-cover" style="width: 56px; height: 56px;" alt="Priya Sharma">
          <div>
            <div class="fw-bold fs-6 text-dark">Priya Sharma</div>
            <div class="text-muted small">Founder & CEO</div>
          </div>
          <div class="ms-auto pe-2">
            <span class="ic-cursive" style="font-size: 26px; color: var(--ic-secondary);">Priya Sharma</span>
          </div>
        </div>
      </div>

      <!-- Right 3 Vertical Cards (Mission, Vision, Values) -->
      <div class="col-lg-7">
        <div class="row g-3">
          <!-- Card 1: Our Mission -->
          <div class="col-md-4">
            <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
              <div class="ic-stat-circle mb-3">
                <i class="fa-solid fa-bullseye"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-2 text-dark">Our Mission</h3>
              <p class="text-muted small mb-0" style="line-height: 1.6;">
                To create functional, beautiful, and meaningful spaces that enhance everyday living.
              </p>
            </div>
          </div>

          <!-- Card 2: Our Vision -->
          <div class="col-md-4">
            <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
              <div class="ic-stat-circle mb-3">
                <i class="fa-regular fa-eye"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-2 text-dark">Our Vision</h3>
              <p class="text-muted small mb-0" style="line-height: 1.6;">
                To be a leading global interior design brand, known for innovation, sustainability, and people-centric design.
              </p>
            </div>
          </div>

          <!-- Card 3: Our Values -->
          <div class="col-md-4">
            <div class="card h-100 border p-4 rounded-4 shadow-sm bg-white">
              <div class="ic-stat-circle mb-3">
                <i class="fa-solid fa-gem"></i>
              </div>
              <h3 class="fw-bold fs-6 mb-2 text-dark">Our Values</h3>
              <ul class="list-unstyled text-muted small mb-0" style="line-height: 1.7;">
                <li class="mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> Client's Happiness First</li>
                <li class="mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> Creativity & Innovation</li>
                <li class="mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> Sustainable Design</li>
                <li class="mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> Integrity & Transparency</li>
                <li><i class="fa-solid fa-circle-check text-success me-1"></i> Quality in Every Detail</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Full-Width 4-Stats Bar -->
    <div class="p-4 bg-light rounded-4 border mt-5">
      <div class="row g-4 text-center">
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="ic-stat-circle"><i class="fa-solid fa-users"></i></div>
            <div class="text-start">
              <div class="fw-bold fs-4 text-dark mb-0">8+</div>
              <div class="text-muted small">Years of Experience</div>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="ic-stat-circle"><i class="fa-solid fa-file-lines"></i></div>
            <div class="text-start">
              <div class="fw-bold fs-4 text-dark mb-0">250+</div>
              <div class="text-muted small">Projects Completed</div>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="ic-stat-circle"><i class="fa-solid fa-star"></i></div>
            <div class="text-start">
              <div class="fw-bold fs-4 text-dark mb-0">98%</div>
              <div class="text-muted small">Client Satisfaction</div>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <div class="ic-stat-circle"><i class="fa-solid fa-user-group"></i></div>
            <div class="text-start">
              <div class="fw-bold fs-4 text-dark mb-0">50+</div>
              <div class="text-muted small">Expert Team Members</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== MEET OUR TEAM SECTION ===== -->
<section class="py-5" style="background: #ffffff;">
  <div class="ic-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <span class="ic-pill-badge">MEET OUR TEAM ——</span>
        <h2 class="ic-heading display-6 mb-2">The Creative Minds<br>Behind Your Dream Space</h2>
        <p class="text-muted mb-0" style="max-width: 540px; font-size: 14.5px;">
          Our team is made up of passionate designers, space planners, and problem-solvers who love turning ideas into beautiful, functional realities.
        </p>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-light rounded-circle border d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fa-solid fa-arrow-left"></i></button>
        <button type="button" class="btn btn-light rounded-circle border d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fa-solid fa-arrow-right"></i></button>
        <a href="{{ $contactUrl }}" class="ic-btn ic-btn-outline ms-2" style="border-radius: var(--ic-radius-pill); border: 1.5px solid var(--ic-border); color: var(--ic-text-dark); padding: 10px 22px; font-weight: 700; text-decoration: none;">
          View All Team Members <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <!-- 4 Team Members Grid -->
    @php
      $team = $interior->team_members_data ?? [
        ['name' => 'Priya Sharma',  'role' => 'Founder & CEO',     'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Rahul Mehta',   'role' => 'Creative Director', 'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Anjali Verma',  'role' => 'Head of Design',    'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Daniel Smith',  'role' => 'Project Manager',   'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=400&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-4">
      @foreach($team as $tm)
        <div class="col-lg-3 col-md-6">
          <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm text-center">
            <div style="height: 260px; overflow: hidden; background: #EAE6DF;">
              <img src="{{ str_starts_with($tm['image'] ?? '', 'http') ? $tm['image'] : asset(ltrim($tm['image'], '/')) }}" alt="{{ $tm['name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="p-3 bg-white">
              <h4 class="fw-bold fs-6 mb-1 text-dark">{{ $tm['name'] }}</h4>
              <div class="text-muted small mb-3">{{ $tm['role'] }}</div>
              <div class="d-flex justify-content-center gap-2 fs-6">
                <a href="#" class="ic-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: #F3F4F6; color: #4B5563;"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="ic-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: #F3F4F6; color: #4B5563;"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#" class="ic-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: #F3F4F6; color: #4B5563;"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#" class="ic-social-icon" style="width: 30px; height: 30px; font-size: 12px; background: #F3F4F6; color: #4B5563;"><i class="fa-brands fa-instagram"></i></a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== CALL TO ACTION BANNER ===== -->
<div class="ic-container">
  <div class="ic-cta-box">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="ic-cta-eyebrow">LET'S CREATE TOGETHER</div>
        <h2 class="ic-cta-title">{{ $interior->contact_title ?? 'Ready to Transform Your Space?' }}</h2>
        <p class="ic-cta-sub">
          {{ $interior->contact_subtitle ?? "Let's work together to create a home or workspace that reflects your style and inspires you every day." }}
        </p>

        <div class="d-flex align-items-center gap-3 flex-wrap">
          <a href="{{ $contactUrl }}" class="ic-btn ic-btn-light fs-6">
            Get Started <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="{{ $contactUrl }}" class="ic-btn" style="border: 1.5px solid rgba(255,255,255,0.4); color: #fff; background: transparent; font-weight: 700; border-radius: var(--ic-radius-pill);">
            <i class="fa-solid fa-calendar-check me-1"></i> Schedule a Free Consultation
          </a>
        </div>
      </div>

      <!-- Right Armchair Image + Cursive Overlay -->
      <div class="col-lg-5 d-none d-lg-block position-relative text-end">
        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=600&auto=format&fit=crop"
             alt="Luxury Interior Chair" class="rounded-4 shadow-lg border" style="max-height: 360px; width: 85%; object-fit: cover;">
        <div class="position-absolute bottom-0 start-0 mb-4 ms-3">
          <span class="ic-cursive" style="font-size: 34px; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.6);">
            Your Vision<br>Our Design
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
