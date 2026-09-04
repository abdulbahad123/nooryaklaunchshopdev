@extends('website_builder.agency_template.layout')

@section('title', 'Contact Us - DesignAGENCY')

@section('content')
<!-- ===== CONTACT HERO & FORM (Ref Image 3 Match) ===== -->
<section style="background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%); padding: 70px 0 60px;">
  <div class="container">
    <div class="row g-5 align-items-center">
      <!-- Left Info Column -->
      <div class="col-lg-5">
        <div class="agency-label-pill">
          <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> Contact Us
        </div>
        <h1 class="agency-heading" style="font-size: clamp(32px, 5vw, 48px);">
          Let's Build Something<br>
          Amazing <span class="highlight">Together!</span>
        </h1>
        <p class="agency-subtitle mb-4">
          {{ $agency->contact_subtitle ?? "Have a project in mind or just want to say hello? We'd love to hear from you." }}
        </p>

        <!-- Bullet List -->
        <div class="space-y-4 mb-4">
          <div class="d-flex align-items-start gap-3">
            <div class="p-3 rounded-circle" style="background: #ECFDF5; color: #10B981;"><i class="fa-solid fa-clock-rotate-left fs-5"></i></div>
            <div>
              <h6 class="fw-bold mb-1 text-slate-900">Quick Response</h6>
              <p class="text-muted small mb-0">We reply to all inquiries within 24 hours.</p>
            </div>
          </div>
          <div class="d-flex align-items-start gap-3 mt-3">
            <div class="p-3 rounded-circle" style="background: #ECFDF5; color: #10B981;"><i class="fa-solid fa-headset fs-5"></i></div>
            <div>
              <h6 class="fw-bold mb-1 text-slate-900">Expert Support</h6>
              <p class="text-muted small mb-0">Our team is here to help you 24/7.</p>
            </div>
          </div>
          <div class="d-flex align-items-start gap-3 mt-3">
            <div class="p-3 rounded-circle" style="background: #ECFDF5; color: #10B981;"><i class="fa-solid fa-rocket fs-5"></i></div>
            <div>
              <h6 class="fw-bold mb-1 text-slate-900">Start Your Project</h6>
              <p class="text-muted small mb-0">Let's turn your ideas into a digital reality.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Contact Form Card -->
      <div class="col-lg-7">
        <div class="card border-0 shadow-lg p-4 p-md-5" style="border-radius: 20px; background: #ffffff;">
          <h4 class="fw-extrabold text-slate-900 mb-1">Send Us a Message</h4>
          <p class="text-muted small mb-4">Fill out the form below and we'll get back to you soon.</p>

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 small fw-bold mb-4" role="alert">
              <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          @php
            $submitUrl = \Illuminate\Support\Facades\Route::has('website-builder.templates.design-agency.contact.submit') 
              ? route('website-builder.templates.design-agency.contact.submit') 
              : route('website-builder.templates.digital_agency.contact.submit');
          @endphp
          <form action="{{ $submitUrl }}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-bold text-slate-700">Your Name</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                  <input type="text" class="form-control bg-light border-start-0" name="name" placeholder="John Doe" required>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold text-slate-700">Your Email</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                  <input type="email" class="form-control bg-light border-start-0" name="email" placeholder="john@example.com" required>
                </div>
              </div>
              <div class="col-md-12">
                <label class="form-label small fw-bold text-slate-700">Phone Number</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-phone"></i></span>
                  <input type="text" class="form-control bg-light border-start-0" name="phone" placeholder="+1 (234) 567-890">
                </div>
              </div>
              <div class="col-md-12">
                <label class="form-label small fw-bold text-slate-700">Subject</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-tag"></i></span>
                  <input type="text" class="form-control bg-light border-start-0" name="subject" placeholder="Project Inquiry / Web Design">
                </div>
              </div>
              <div class="col-md-12">
                <label class="form-label small fw-bold text-slate-700">Your Message</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted align-items-start pt-2"><i class="fa-solid fa-pen-to-square"></i></span>
                  <textarea class="form-control bg-light border-start-0" name="message" rows="4" placeholder="Tell us about your project goals, timeline, and budget..." required></textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-agency-register w-100 py-3 mt-2" style="font-size: 15px;">
                  Send Message <i class="fa-solid fa-paper-plane ms-2"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== 4 LOCATION CARDS (Ref Image 3 Match) ===== -->
<section style="padding: 40px 0; background: #FFFFFF;">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 p-4" style="background: #F8FAFC; border-radius: 16px;">
          <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3" style="width: 50px; height: 50px; background: #ECFDF5; color: #10B981; font-size: 20px;">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <h6 class="fw-bold mb-1 text-slate-900">Our Location</h6>
          <p class="text-muted small mb-0">{{ $agency->address ?? '123 Design Street, Creative City, CA 90403, United States' }}</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 p-4" style="background: #F8FAFC; border-radius: 16px;">
          <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3" style="width: 50px; height: 50px; background: #ECFDF5; color: #10B981; font-size: 20px;">
            <i class="fa-solid fa-phone"></i>
          </div>
          <h6 class="fw-bold mb-1 text-slate-900">Call Us</h6>
          <p class="text-muted small mb-0">{{ $agency->phone ?? '+1 (234) 567-890' }}<br>Mon - Fri: 9AM - 6PM</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 p-4" style="background: #F8FAFC; border-radius: 16px;">
          <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3" style="width: 50px; height: 50px; background: #ECFDF5; color: #10B981; font-size: 20px;">
            <i class="fa-solid fa-envelope"></i>
          </div>
          <h6 class="fw-bold mb-1 text-slate-900">Email Us</h6>
          <p class="text-muted small mb-0">{{ $agency->email ?? 'info@designagency.com' }}<br>support@designagency.com</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 p-4" style="background: #F8FAFC; border-radius: 16px;">
          <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3" style="width: 50px; height: 50px; background: #ECFDF5; color: #10B981; font-size: 20px;">
            <i class="fa-solid fa-clock"></i>
          </div>
          <h6 class="fw-bold mb-1 text-slate-900">Working Hours</h6>
          <p class="text-muted small mb-0">Monday – Friday<br>9:00 AM – 6:00 PM</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== MAP VISUAL CARD ===== -->
<section style="padding: 30px 0; background: #FFFFFF;">
  <div class="container">
    <div class="position-relative rounded-4 overflow-hidden shadow-sm" style="height: 320px; background: #E2E8F0;">
      <!-- Map Background Graphic -->
      <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1200&auto=format&fit=crop" 
           alt="DesignAGENCY Location Map" 
           style="width: 100%; height: 100%; object-fit: cover; opacity: 0.75;">

      <!-- Centered Pin Card -->
      <div class="position-absolute top-50 start-50 translate-middle card border-0 shadow-lg p-3 text-center" style="border-radius: 16px; min-width: 220px;">
        <i class="fa-solid fa-location-dot text-success fs-3 mb-1"></i>
        <h6 class="fw-extrabold mb-1">We're Here!</h6>
        <p class="text-muted small mb-0" style="font-size: 11.5px;">Visit our office or drop us a message anytime you want.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== FAQS SECTION ===== -->
<section style="padding: 80px 0; background: #FFFFFF;">
  <div class="container">
    <div class="row g-5">
      <!-- Accordion FAQs -->
      <div class="col-lg-7">
        <div class="agency-label-pill">FAQS</div>
        <h2 class="agency-heading">Frequently Asked Questions</h2>

        @php
          $faqs = $agency->faqs_data ?? [
            ['q' => 'How soon can we start our project?', 'a' => 'Once we understand your requirements, we can typically start within 2–3 business days.'],
            ['q' => 'What information do you need to get started?', 'a' => 'We will need your brand assets, project goals, target audience, and any content guidelines.'],
            ['q' => 'Do you offer ongoing support?', 'a' => 'Yes! We offer comprehensive maintenance, updates, and ongoing digital strategy support.'],
            ['q' => 'How do I know if my project is a good fit?', 'a' => 'Feel free to send us a quick message or book a discovery call, and our team will evaluate your needs!'],
          ];
        @endphp

        <div class="accordion accordion-flush mt-4" id="faqAccordion">
          @foreach($faqs as $fi => $f)
            <div class="accordion-item border rounded-3 mb-3">
              <h2 class="accordion-header" id="faqHeading{{ $fi }}">
                <button class="accordion-button {{ $fi > 0 ? 'collapsed' : '' }} fw-bold text-slate-900" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $fi }}">
                  {{ $f['q'] }}
                </button>
              </h2>
              <div id="faqCollapse{{ $fi }}" class="accordion-collapse collapse {{ $fi == 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-slate-600 small" style="line-height: 1.6;">
                  {{ $f['a'] }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Right Consultant Banner Card -->
      <div class="col-lg-5">
        <div class="card border-0 p-4 text-center h-100 d-flex flex-column justify-content-center" style="background: #ECFDF5; border-radius: 20px;">
          <h3 class="fw-extrabold text-slate-900 mb-2">Ready to Start Your Project?</h3>
          <p class="text-muted small mb-4">Let's discuss how we can help your business grow with digital solutions.</p>
          <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop" 
               alt="Consultant Support" 
               class="rounded-circle mx-auto mb-4" 
               style="width: 140px; height: 140px; object-fit: cover;">
          <a href="#" class="btn-agency-register mx-auto py-3 px-4">
            Get In Touch <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
