@extends('website_builder.interior_template.layout')

@section('title', 'Contact Us - InteriorCRAFT Design Studio')

@section('content')

<!-- Header Banner -->
<section class="py-5" style="background: var(--ic-secondary-dark); color: #ffffff;">
  <div class="ic-container text-center py-4">
    <span class="ic-sub-badge" style="background: rgba(255,255,255,0.15); color: #fff;">GET IN TOUCH</span>
    <h1 class="ic-heading-serif text-white display-4 mb-3">Book a Design Consultation</h1>
    <p class="text-white-50 mx-auto" style="max-width: 600px; font-size: 16px;">
      Whether you are planning a luxury home renovation or a corporate commercial project, our architects are ready to collaborate.
    </p>
  </div>
</section>

<!-- Contact Form & Details -->
<section class="ic-section">
  <div class="ic-container">
    <div class="row g-5">
      <!-- Contact Info Sidebar -->
      <div class="col-lg-5">
        <span class="ic-sub-badge">REACH OUR STUDIO</span>
        <h2 class="ic-heading-serif ic-section-title">We'd Love to Hear From You</h2>
        <p class="text-muted mb-4" style="line-height: 1.7;">
          Visit our design showroom or schedule an in-person consultation with our lead interior architects.
        </p>

        <div class="d-flex flex-column gap-4 mb-5">
          <div class="d-flex align-items-start gap-3">
            <div class="ic-stat-icon-box"><i class="fa-solid fa-location-dot"></i></div>
            <div>
              <h4 class="ic-heading-serif fs-6 mb-1">Studio Address</h4>
              <p class="text-muted mb-0 small">450 Design Avenue, Suite 800<br>New York, NY 10001, United States</p>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3">
            <div class="ic-stat-icon-box"><i class="fa-solid fa-phone"></i></div>
            <div>
              <h4 class="ic-heading-serif fs-6 mb-1">Telephone Inquiry</h4>
              <p class="text-muted mb-0 small">{{ $interior->phone ?? '+1 (800) 456-7890' }}</p>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3">
            <div class="ic-stat-icon-box"><i class="fa-solid fa-envelope"></i></div>
            <div>
              <h4 class="ic-heading-serif fs-6 mb-1">Email Correspondence</h4>
              <p class="text-muted mb-0 small">{{ $interior->email ?? 'hello@interiorcraft.com' }}</p>
            </div>
          </div>
        </div>

        <div class="p-4 rounded-4 bg-light border">
          <h4 class="ic-heading-serif fs-6 mb-2">Studio Hours</h4>
          <div class="d-flex justify-content-between text-muted small mb-1">
            <span>Monday - Friday:</span>
            <span class="fw-semibold text-dark">9:00 AM - 6:00 PM EST</span>
          </div>
          <div class="d-flex justify-content-between text-muted small">
            <span>Saturday - Sunday:</span>
            <span class="fw-semibold text-dark">By Private Appointment</span>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="col-lg-7">
        <div class="p-5 bg-white rounded-4 shadow-sm border">
          <h3 class="ic-heading-serif fs-4 mb-4">Send Us a Message</h3>

          <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you! Your message has been sent successfully. Our interior design team will contact you within 24 hours.');">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-muted">Your Full Name</label>
                <input type="text" class="form-control form-control-lg fs-6" placeholder="e.g. Eleanor Vance" required style="border-radius: 10px; border-color: var(--ic-border);">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-muted">Email Address</label>
                <input type="email" class="form-control form-control-lg fs-6" placeholder="e.g. eleanor@example.com" required style="border-radius: 10px; border-color: var(--ic-border);">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-muted">Phone Number</label>
                <input type="tel" class="form-control form-control-lg fs-6" placeholder="+1 (555) 000-0000" style="border-radius: 10px; border-color: var(--ic-border);">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-muted">Project Type</label>
                <select class="form-select form-select-lg fs-6" style="border-radius: 10px; border-color: var(--ic-border);">
                  <option value="residential">Residential Living Space</option>
                  <option value="commercial">Commercial Office / HQ</option>
                  <option value="hospitality">Restaurant / Boutique Hotel</option>
                  <option value="furniture">Custom Furniture & Styling</option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold small text-muted">Project Details & Vision</label>
                <textarea class="form-control form-control-lg fs-6" rows="5" placeholder="Tell us about your space, approximate square footage, timeline, and aesthetic preferences..." style="border-radius: 10px; border-color: var(--ic-border);"></textarea>
              </div>
              <div class="col-12 mt-4">
                <button type="submit" class="ic-btn ic-btn-primary w-100 py-3 fs-6">
                  Submit Project Request <i class="fa-solid fa-paper-plane ms-2"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
