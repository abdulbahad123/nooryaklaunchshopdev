@extends('website_builder.texigo_theme.layout')

@section('title', 'Contact Us - TaxiGo #1 Trusted Taxi Service')

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
  $agency = $agency ?? $interior ?? null;
@endphp

<!-- ===== CONTACT HERO SECTION ===== -->
<section class="tx-hero">
  <div class="tx-container text-center max-w-700 mx-auto">
    <span class="tx-pill-badge">CONTACT US</span>
    <h1 class="tx-heading display-5 mb-3">Get in Touch With <span style="color: var(--tx-primary);">TaxiGo</span></h1>
    <p class="text-muted fs-6">Have questions or need assistance booking a ride? Our 24/7 mobility support team is here to help.</p>
  </div>
</section>

<section class="py-5" style="background: #ffffff;">
  <div class="tx-container">
    <div class="row g-5">
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-light mb-4">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-dark" style="width: 44px; height: 44px; background: var(--tx-primary); font-size: 18px;">
              <i class="fa-solid fa-phone"></i>
            </div>
            <div>
              <div class="fw-bold fs-6 text-dark">Phone Number</div>
              <div class="text-muted small">{{ $agency->phone ?? '+1 (234) 567-890' }}</div>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-dark" style="width: 44px; height: 44px; background: var(--tx-primary); font-size: 18px;">
              <i class="fa-solid fa-envelope"></i>
            </div>
            <div>
              <div class="fw-bold fs-6 text-dark">Email Address</div>
              <div class="text-muted small">{{ $agency->email ?? 'hello@taxigo.com' }}</div>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-dark" style="width: 44px; height: 44px; background: var(--tx-primary); font-size: 18px;">
              <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
              <div class="fw-bold fs-6 text-dark">Office Location</div>
              <div class="text-muted small">{{ $agency->address ?? '123 Mobility Way, City Center, NY 10001' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card border p-4 p-md-5 rounded-4 shadow-sm bg-white">
          <h3 class="fw-bold fs-4 mb-3 text-dark">Book Your Ride or Send an Inquiry</h3>
          <form action="#" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark">Your Name</label>
                <input type="text" class="form-control rounded-3 py-2.5" placeholder="John Doe" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark">Phone Number</label>
                <input type="tel" class="form-control rounded-3 py-2.5" placeholder="+1 (234) 567-890" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark">Pickup Location</label>
                <input type="text" class="form-control rounded-3 py-2.5" placeholder="City Airport / Address" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark">Dropoff Location</label>
                <input type="text" class="form-control rounded-3 py-2.5" placeholder="Destination Address" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold small text-dark">Special Instructions</label>
                <textarea class="form-control rounded-3" rows="4" placeholder="Flight details or luggage requirement..."></textarea>
              </div>
              <div class="col-12 mt-4">
                <button type="submit" class="tx-btn tx-btn-yellow w-100 py-3 fw-bold">Submit Booking Inquiry <i class="fa-solid fa-paper-plane ms-1"></i></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
