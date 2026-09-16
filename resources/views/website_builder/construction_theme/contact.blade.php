@extends('website_builder.construction_theme.layout')

@section('title', 'Contact Us — ' . ($agency->site_title ?? 'BuildCraft Construction'))
@section('description', 'Get in touch with our construction engineers and project management team for a free consultation and project estimate.')

@section('content')

@php
  $heroBg = asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
  $homeUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.site', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction');
@endphp

{{-- Page Hero Banner --}}
<section class="cn-page-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 320px; position: relative;">
  <div class="cn-page-hero-overlay"></div>
  <div class="cn-container" style="position: relative; z-index: 2; width: 100%;">
    <div class="cn-page-hero-content">
      <div class="cn-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
        <span>Contact</span>
      </div>
      <h1 class="cn-page-hero-title">{{ $agency->contact_title ?? 'Start Your Construction Journey' }}</h1>
      <p class="cn-page-hero-subtitle">{{ $agency->contact_subtitle ?? 'Tell us about your project and get a free consultation from our expert team.' }}</p>
    </div>
  </div>
</section>

{{-- Contact Section (Interior-style Layout with Clean Light Surface) --}}
<section class="cn-contact-hero-section">
  <div class="cn-container">
    <div class="row g-5 align-items-start">

      {{-- Left Side: Contact Info & Bullet Cards --}}
      <div class="col-lg-5">
        <div class="cn-contact-badge-pill">
          <span class="dot" style="width:7px;height:7px;border-radius:50%;background:#FFB800;display:inline-block;"></span>
          📍 GET IN TOUCH
        </div>

        <h2 class="cn-contact-hero-title">
          We'd Love to <span class="cn-text-yellow">Hear From You</span>
        </h2>

        <p class="cn-contact-hero-desc">
          Whether you have a new construction project in mind, need a detailed cost estimate, or want to learn more about our services — our engineering team is ready to help.
        </p>

        {{-- Bullet 1: Office Address --}}
        <div class="cn-contact-bullet-item">
          <div class="cn-contact-bullet-icon">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <div>
            <div class="cn-contact-bullet-title">OUR OFFICE</div>
            <div class="cn-contact-bullet-sub">{{ $agency->address ?? '123 Construction Avenue, New York, NY 10001' }}</div>
          </div>
        </div>

        {{-- Bullet 2: Phone Number --}}
        <div class="cn-contact-bullet-item">
          <div class="cn-contact-bullet-icon">
            <i class="fa-solid fa-phone"></i>
          </div>
          <div>
            <div class="cn-contact-bullet-title">CALL US</div>
            <div class="cn-contact-bullet-sub">
              <a href="tel:{{ $agency->phone ?? '+1234567890' }}" class="text-decoration-none text-dark fw-semibold">
                {{ $agency->phone ?? '+1 (234) 567-890' }}
              </a>
            </div>
          </div>
        </div>

        {{-- Bullet 3: Email --}}
        <div class="cn-contact-bullet-item">
          <div class="cn-contact-bullet-icon">
            <i class="fa-solid fa-envelope"></i>
          </div>
          <div>
            <div class="cn-contact-bullet-title">EMAIL US</div>
            <div class="cn-contact-bullet-sub">
              <a href="mailto:{{ $agency->email ?? 'info@buildcraft.com' }}" class="text-decoration-none text-dark fw-semibold">
                {{ $agency->email ?? 'info@buildcraft.com' }}
              </a>
            </div>
          </div>
        </div>

        {{-- Bullet 4: Working Hours --}}
        <div class="cn-contact-bullet-item">
          <div class="cn-contact-bullet-icon">
            <i class="fa-solid fa-clock"></i>
          </div>
          <div>
            <div class="cn-contact-bullet-title">WORKING HOURS</div>
            <div class="cn-contact-bullet-sub">Monday – Saturday: 8:00 AM – 6:00 PM</div>
          </div>
        </div>
      </div>

      {{-- Right Side: Clean White Form Card --}}
      <div class="col-lg-7">
        <div class="cn-contact-form-card">
          <h3 class="cn-form-card-title">Send Us a <span class="cn-text-yellow">Message</span></h3>
          <p class="cn-form-card-sub">Fill out the form below and our project managers will respond within 24 hours.</p>

          @if(session('contact_success'))
            <div class="alert alert-success rounded-3 mb-4">
              <i class="fa-solid fa-circle-check me-2"></i> {{ session('contact_success') }}
            </div>
          @endif

          <form method="POST" action="{{ route('website-builder.templates.digital_agency.contact.submit') }}" id="cn-contact-form">
            @csrf
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-dark small mb-1">Full Name *</label>
                <div class="cn-input-wrap">
                  <i class="fa-solid fa-user"></i>
                  <input type="text" name="name" class="cn-custom-form-input" placeholder="John Anderson" required>
                </div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-dark small mb-1">Email Address *</label>
                <div class="cn-input-wrap">
                  <i class="fa-solid fa-envelope"></i>
                  <input type="email" name="email" class="cn-custom-form-input" placeholder="john@company.com" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-dark small mb-1">Phone Number</label>
                <div class="cn-input-wrap">
                  <i class="fa-solid fa-phone"></i>
                  <input type="tel" name="phone" class="cn-custom-form-input" placeholder="+1 (234) 567-890">
                </div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-dark small mb-1">Project Type</label>
                <div class="cn-input-wrap">
                  <i class="fa-solid fa-helmet-safety"></i>
                  <select name="project_type" class="cn-custom-form-input" style="appearance: auto;">
                    <option value="">Select project type...</option>
                    @foreach($agency->construction_data['project_types'] ?? ['Residential','Commercial','Infrastructure','Luxury','Industrial'] as $pt)
                      <option value="{{ $pt }}">{{ $pt }} Construction</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold text-dark small mb-1">Subject</label>
              <div class="cn-input-wrap">
                <i class="fa-solid fa-heading"></i>
                <input type="text" name="subject" class="cn-custom-form-input" placeholder="Project consultation request">
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark small mb-1">Project Details *</label>
              <div class="cn-input-wrap">
                <i class="fa-solid fa-message"></i>
                <textarea name="message" class="cn-custom-form-input" rows="4" placeholder="Tell us about your project, location, timeline, and requirements..." required></textarea>
              </div>
            </div>

            <button type="submit" class="cn-btn cn-btn-yellow w-100 justify-content-center py-3 fs-6">
              Send Message <i class="fa-solid fa-paper-plane ms-2"></i>
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
