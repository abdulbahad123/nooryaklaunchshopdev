@extends('website_builder.construction_theme.layout')

@section('title', ($agency->site_title ?? 'BuildCraft Construction') . ' — Contact Us')

@section('content')

@php
  $heroBg = asset($agency->contact_image ?? 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1200&auto=format&fit=crop');
  $homeUrl = isset($subdomain) && $subdomain
    ? route('website-builder.subdomain.site', ['subdomain' => $subdomain])
    : route('website-builder.templates.construction');
@endphp

{{-- Page Hero --}}
<section class="cn-page-hero" style="background: url('{{ $heroBg }}') no-repeat center center / cover; min-height: 300px;">
  <div class="cn-page-hero-overlay"></div>
  <div class="cn-container" style="width:100%;">
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

{{-- Contact Grid --}}
<section class="cn-section">
  <div class="cn-container">
    <div class="cn-contact-grid">

      {{-- Info --}}
      <div>
        <div class="cn-section-tag" style="margin-bottom:12px;"><i class="fa-solid fa-location-dot"></i> Get In Touch</div>
        <h2 class="cn-section-title" style="margin-bottom:8px;">We'd Love to <span>Hear From You</span></h2>
        <div class="cn-divider"></div>
        <p style="font-size:14px;color:var(--cn-text-muted);margin-bottom:28px;line-height:1.7;">
          Whether you have a new project in mind, need a cost estimate, or want to learn more about our services — our team is ready to help.
        </p>
        <div class="cn-contact-info-card">
          <div class="cn-contact-info-item">
            <div class="cn-contact-icon"><i class="fa-solid fa-location-dot"></i></div>
            <div>
              <div class="cn-contact-info-label">Our Office</div>
              <div class="cn-contact-info-value">{{ $agency->address ?? '45 Builder Street, Industrial Park, NY 10001' }}</div>
            </div>
          </div>
          <div class="cn-contact-info-item">
            <div class="cn-contact-icon"><i class="fa-solid fa-phone"></i></div>
            <div>
              <div class="cn-contact-info-label">Call Us</div>
              <div class="cn-contact-info-value">
                <a href="tel:{{ $agency->phone ?? '+18002845348' }}">{{ $agency->phone ?? '+1 (800) 284-5348' }}</a>
              </div>
            </div>
          </div>
          <div class="cn-contact-info-item">
            <div class="cn-contact-icon"><i class="fa-solid fa-envelope"></i></div>
            <div>
              <div class="cn-contact-info-label">Email Us</div>
              <div class="cn-contact-info-value">
                <a href="mailto:{{ $agency->email ?? 'hello@buildcraft.com' }}">{{ $agency->email ?? 'hello@buildcraft.com' }}</a>
              </div>
            </div>
          </div>
          <div class="cn-contact-info-item">
            <div class="cn-contact-icon"><i class="fa-solid fa-clock"></i></div>
            <div>
              <div class="cn-contact-info-label">Working Hours</div>
              <div class="cn-contact-info-value">Mon – Sat: 8:00 AM – 6:00 PM</div>
            </div>
          </div>
        </div>

        {{-- Quick Contact Highlights --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:20px;">
          @foreach([['fa-helmet-safety','25+ Years Experience'],['fa-shield-halved','ISO 45001 Certified'],['fa-star','500+ Projects Done'],['fa-face-smile','1200+ Happy Clients']] as $badge)
          <div style="background:rgba(255,107,0,0.06);border:1px solid rgba(255,107,0,0.15);border-radius:8px;padding:12px;display:flex;align-items:center;gap:10px;">
            <i class="fa-solid {{ $badge[0] }}" style="color:var(--cn-primary);font-size:16px;"></i>
            <span style="font-size:13px;color:var(--cn-text-light);font-weight:500;">{{ $badge[1] }}</span>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Contact Form --}}
      <div class="cn-contact-form-card">
        <h3 style="font-family:'Barlow Condensed','Inter',sans-serif;font-size:24px;font-weight:800;color:var(--cn-white);margin-bottom:24px;">
          Send Us a <span style="color:var(--cn-primary);">Message</span>
        </h3>

        @if(session('contact_success'))
          <div class="cn-alert cn-alert-success">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('contact_success') }}
          </div>
        @endif

        <form method="POST" action="{{ isset($subdomain) ? '#' : '#' }}" id="cn-contact-form">
          @csrf
          <div class="row">
            <div class="col-sm-6">
              <div class="cn-form-group">
                <label class="cn-form-label" for="cn_name">Full Name *</label>
                <input type="text" id="cn_name" name="name" class="cn-form-control" placeholder="John Anderson" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="cn-form-group">
                <label class="cn-form-label" for="cn_email">Email Address *</label>
                <input type="email" id="cn_email" name="email" class="cn-form-control" placeholder="john@company.com" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="cn-form-group">
                <label class="cn-form-label" for="cn_phone">Phone Number</label>
                <input type="tel" id="cn_phone" name="phone" class="cn-form-control" placeholder="+1 (555) 000-0000">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="cn-form-group">
                <label class="cn-form-label" for="cn_project_type">Project Type</label>
                <select id="cn_project_type" name="project_type" class="cn-form-control">
                  <option value="" style="background:#1e1e1e;">Select project type...</option>
                  @foreach($agency->construction_data['project_types'] ?? ['Residential','Commercial','Industrial','Infrastructure','Renovation'] as $pt)
                    <option value="{{ $pt }}" style="background:#1e1e1e;">{{ $pt }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="cn-form-group">
            <label class="cn-form-label" for="cn_subject">Subject</label>
            <input type="text" id="cn_subject" name="subject" class="cn-form-control" placeholder="Project consultation request">
          </div>
          <div class="cn-form-group">
            <label class="cn-form-label" for="cn_message">Project Details *</label>
            <textarea id="cn_message" name="message" class="cn-form-control" rows="5" placeholder="Tell us about your project, timeline, and requirements..." required></textarea>
          </div>
          <button type="submit" class="cn-btn cn-btn-primary" style="width:100%;justify-content:center;padding:14px;">
            <i class="fa-solid fa-paper-plane"></i> Send Message &amp; Get Free Quote
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

{{-- Map Placeholder --}}
<div style="background:var(--cn-dark-2);height:300px;display:flex;align-items:center;justify-content:center;border-top:1px solid rgba(255,107,0,0.1);">
  <div style="text-align:center;">
    <i class="fa-solid fa-map-location-dot" style="font-size:48px;color:var(--cn-primary);opacity:0.4;margin-bottom:12px;display:block;"></i>
    <div style="color:var(--cn-text-muted);font-size:15px;">{{ $agency->address ?? '45 Builder Street, Industrial Park, NY 10001' }}</div>
  </div>
</div>

@endsection
