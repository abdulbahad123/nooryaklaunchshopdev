@extends('website_builder.interior_template.layout')

@section('title', 'Contact Us - ' . ($interior->site_title ?? 'InterioCRAFT'))

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.portfolio');
@endphp
<style>
  /* ===== CONTACT HERO SECTION ===== */
  .ic-contact-hero-section {
    background: linear-gradient(180deg, var(--ic-bg-light) 0%, #FFFFFF 100%);
    padding: 60px 0 50px;
    position: relative;
    overflow: hidden;
  }
  .ic-contact-badge-pill {
    background: var(--ic-badge-bg);
    color: var(--ic-secondary);
    font-weight: 700;
    font-size: 13px;
    padding: 6px 16px;
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 20px;
  }
  .ic-contact-badge-pill .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--ic-primary);
    display: inline-block;
  }
  .ic-contact-hero-title {
    font-size: clamp(34px, 4.8vw, 52px);
    font-weight: 800;
    line-height: 1.15;
    color: var(--ic-text-dark);
    margin-bottom: 18px;
    letter-spacing: -0.8px;
  }
  .ic-contact-hero-title .text-interior {
    color: var(--ic-primary) !important;
    position: relative;
    display: inline-block;
  }
  .ic-contact-hero-title .text-interior::after {
    content: '';
    display: block;
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--ic-primary);
    border-radius: 2px;
  }
  .ic-contact-hero-desc {
    font-size: 16px;
    color: var(--ic-text-muted);
    line-height: 1.65;
    margin-bottom: 32px;
    max-width: 480px;
  }

  /* Bullet Item with rounded icon box */
  .ic-contact-bullet-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
  }
  .ic-contact-bullet-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: var(--ic-badge-bg);
    color: var(--ic-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .ic-contact-bullet-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--ic-text-dark);
    margin-bottom: 2px;
  }
  .ic-contact-bullet-sub {
    font-size: 13.5px;
    color: var(--ic-text-muted);
    line-height: 1.5;
  }

  /* ===== CONTACT FORM CARD ===== */
  .ic-contact-form-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 44px 48px;
    border: 1px solid var(--ic-border-light);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
  }
  .ic-form-card-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--ic-text-dark);
    margin-bottom: 4px;
  }
  .ic-form-card-sub {
    font-size: 14px;
    color: var(--ic-text-muted);
    margin-bottom: 28px;
  }
  .ic-input-wrap {
    position: relative;
    margin-bottom: 18px;
  }
  .ic-input-wrap i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--ic-text-light);
    font-size: 14px;
    pointer-events: none;
  }
  .ic-input-wrap textarea + i,
  .ic-input-wrap-textarea i {
    top: 20px;
    transform: none;
  }
  .ic-custom-form-input {
    width: 100%;
    background: var(--ic-bg-light);
    border: 1.5px solid var(--ic-border);
    border-radius: 12px;
    padding: 12px 18px 12px 46px;
    font-size: 14px;
    color: var(--ic-text-dark);
    transition: all 0.25s ease;
    outline: none;
  }
  .ic-custom-form-input:focus {
    background: #ffffff;
    border-color: var(--ic-primary);
    box-shadow: 0 0 0 4px rgba(78, 122, 87, 0.15);
  }
  .ic-btn-submit-interior {
    width: 100%;
    background: var(--ic-secondary);
    color: #ffffff;
    font-weight: 700;
    font-size: 16px;
    padding: 14px 28px;
    border-radius: 12px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.25s ease;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(32, 54, 39, 0.25);
  }
  .ic-btn-submit-interior:hover {
    background: var(--ic-secondary-dark);
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(32, 54, 39, 0.35);
  }

  /* Decorative plane icon at top right of form card */
  .ic-plane-graphic-decor {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 110px;
    opacity: 0.85;
    pointer-events: none;
  }
  .ic-green-dot-decor {
    position: absolute;
    bottom: 30px;
    right: -15px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--ic-primary);
  }

  /* ===== 4 LOCATION / INFO CARDS ===== */
  .ic-info-cards-section {
    padding: 40px 0;
    background: #ffffff;
  }
  .ic-info-card-item {
    background: var(--ic-bg-light);
    border-radius: 18px;
    padding: 24px 22px;
    border: 1px solid var(--ic-border-light);
    height: 100%;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    transition: all 0.25s ease;
  }
  .ic-info-card-item:hover {
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    transform: translateY(-3px);
  }
  .ic-info-card-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: var(--ic-badge-bg);
    color: var(--ic-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .ic-info-card-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--ic-text-dark);
    margin-bottom: 4px;
  }
  .ic-info-card-desc {
    font-size: 13px;
    color: var(--ic-text-muted);
    line-height: 1.55;
    margin-bottom: 0;
  }

  /* ===== MAP SECTION WITH CENTER FLOATING CARD ===== */
  .ic-map-section {
    padding: 20px 0 50px;
    background: #ffffff;
  }
  .ic-map-container-relative {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    height: 420px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  }
  .ic-map-floating-card {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #ffffff;
    border-radius: 20px;
    padding: 28px 36px;
    text-align: center;
    box-shadow: 0 14px 40px rgba(0, 0, 0, 0.15);
    z-index: 10;
    max-width: 320px;
    width: 90%;
  }
  .ic-map-pin-badge {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--ic-badge-bg);
    color: var(--ic-secondary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 12px;
  }
  .ic-map-card-title {
    font-size: 20px;
    font-weight: 800;
    color: var(--ic-text-dark);
    margin-bottom: 6px;
  }
  .ic-map-card-desc {
    font-size: 13px;
    color: var(--ic-text-muted);
    line-height: 1.5;
    margin-bottom: 0;
  }

  /* ===== FAQS & CONSULTANT CARD SECTION ===== */
  .ic-faqs-section {
    padding: 30px 0 120px;
    background: #ffffff;
  }
  .ic-faqs-badge {
    color: var(--ic-primary);
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    text-transform: uppercase;
  }
  .ic-faqs-title {
    font-size: 32px;
    font-weight: 800;
    color: var(--ic-text-dark);
    margin-bottom: 28px;
    letter-spacing: -0.5px;
  }
  .ic-custom-faq-item {
    border: 1.5px solid var(--ic-border-light);
    border-radius: 16px !important;
    margin-bottom: 14px;
    overflow: hidden;
    background: #ffffff;
    transition: all 0.25s ease;
  }
  .ic-custom-faq-button {
    background: #ffffff;
    color: var(--ic-text-dark);
    font-weight: 700;
    font-size: 15px;
    padding: 18px 24px;
    border: none;
    width: 100%;
    text-align: left;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: none !important;
  }
  .ic-custom-faq-button.active-faq {
    color: var(--ic-secondary);
    background: var(--ic-bg-light);
  }
  .ic-faq-icon-toggle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--ic-border-light);
    color: var(--ic-text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    transition: all 0.25s ease;
  }
  .active-faq .ic-faq-icon-toggle {
    background: var(--ic-secondary);
    color: #ffffff;
  }
  .ic-faq-body-text {
    padding: 0 24px 20px;
    font-size: 14px;
    color: var(--ic-text-muted);
    line-height: 1.6;
    background: var(--ic-bg-light);
  }

  /* ===== Consultant Support Card Right ===== */
  .ic-consultant-card {
    background: linear-gradient(135deg, var(--ic-bg-light) 0%, var(--ic-badge-bg) 100%);
    border-radius: 24px;
    padding: 0;
    height: 100%;
    min-height: 360px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: row;
    align-items: stretch;
  }
  .ic-consultant-card-content {
    flex: 1;
    padding: 44px 36px 44px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    z-index: 2;
    position: relative;
  }
  .ic-consultant-title {
    font-size: clamp(26px, 3.2vw, 36px);
    font-weight: 800;
    color: var(--ic-text-dark);
    line-height: 1.2;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
  }
  .ic-consultant-title .text-interior {
    color: var(--ic-primary) !important;
  }
  .ic-consultant-desc {
    font-size: 14px;
    color: var(--ic-text-muted);
    line-height: 1.65;
    margin-bottom: 28px;
    max-width: 260px;
  }
  .ic-btn-get-touch {
    background: var(--ic-secondary);
    color: #ffffff;
    font-weight: 700;
    font-size: 14.5px;
    padding: 12px 26px;
    border-radius: 30px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
    box-shadow: 0 8px 20px rgba(32, 54, 39, 0.25);
    align-self: flex-start;
  }
  .ic-btn-get-touch:hover {
    background: var(--ic-secondary-dark);
    color: #ffffff;
    transform: translateY(-2px);
  }
  .ic-consultant-img-wrap {
    flex-shrink: 0;
    width: 48%;
    position: relative;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    overflow: hidden;
  }
  .ic-consultant-img {
    width: 100%;
    height: 100%;
    max-height: 400px;
    display: block;
    object-fit: contain;
    object-position: bottom center;
  }

  @media (max-width: 991px) {
    .ic-contact-form-card { padding: 32px 24px; }
    .ic-consultant-card { margin-top: 24px; flex-direction: row; }
  }
  @media (max-width: 575px) {
    .ic-consultant-card { flex-direction: column; }
    .ic-consultant-img-wrap { width: 100%; height: 240px; }
  }
</style>

<!-- ===== CONTACT HERO & FORM SECTION ===== -->
<section class="ic-contact-hero-section">
  <div class="ic-container">
    <div class="row g-5 align-items-center">

      <!-- LEFT: Bullet List Info Column -->
      <div class="col-lg-5">
        <div class="ic-contact-badge-pill">
          <span class="dot"></span> Contact Us
        </div>
        <h1 class="ic-contact-hero-title">
          Let’s Build Something<br>
          Amazing <span class="text-interior">Together!</span>
        </h1>
        <p class="ic-contact-hero-desc">
          {{ $interior->contact_subtitle ?? "Have an interior design or architectural project in mind? We'd love to hear from you." }}
        </p>

        <!-- Bullet List -->
        <div>
          <div class="ic-contact-bullet-item">
            <div class="ic-contact-bullet-icon">
              <i class="fa-regular fa-clock"></i>
            </div>
            <div>
              <div class="ic-contact-bullet-title">Quick Response</div>
              <div class="ic-contact-bullet-sub">We reply to all inquiries within 24 hours.</div>
            </div>
          </div>

          <div class="ic-contact-bullet-item">
            <div class="ic-contact-bullet-icon">
              <i class="fa-solid fa-headset"></i>
            </div>
            <div>
              <div class="ic-contact-bullet-title">Expert Support</div>
              <div class="ic-contact-bullet-sub">Our lead interior architects are here to help you 24/7.</div>
            </div>
          </div>

          <div class="ic-contact-bullet-item">
            <div class="ic-contact-bullet-icon">
              <i class="fa-solid fa-compass-drafting"></i>
            </div>
            <div>
              <div class="ic-contact-bullet-title">Start Your Project</div>
              <div class="ic-contact-bullet-sub">Let's turn your spatial vision into architectural reality.</div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Form Card -->
      <div class="col-lg-7">
        <div class="ic-contact-form-card">
          <svg class="ic-plane-graphic-decor d-none d-sm-block" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 110 C40 80, 80 50, 100 20" stroke="var(--ic-primary)" stroke-width="1.5" stroke-dasharray="4 4"/>
            <path d="M100 20 L115 15 L108 30 Z" fill="var(--ic-primary)"/>
          </svg>
          <div class="ic-green-dot-decor d-none d-sm-block"></div>

          <div class="ic-form-card-title">Send Us a Message</div>
          <div class="ic-form-card-sub">Fill out the form below and our design team will contact you shortly.</div>

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 small fw-bold mb-4" role="alert" style="background: var(--ic-secondary); color: #fff;">
              <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you! Your message has been sent successfully. Our interior design team will contact you within 24 hours.');">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <div class="ic-input-wrap">
                  <i class="fa-regular fa-user"></i>
                  <input type="text" class="ic-custom-form-input" name="name" placeholder="Your Name" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="ic-input-wrap">
                  <i class="fa-regular fa-envelope"></i>
                  <input type="email" class="ic-custom-form-input" name="email" placeholder="Your Email" required>
                </div>
              </div>

              <div class="col-12">
                <div class="ic-input-wrap">
                  <i class="fa-solid fa-phone"></i>
                  <input type="text" class="ic-custom-form-input" name="phone" placeholder="Phone Number">
                </div>
              </div>

              <div class="col-12">
                <div class="ic-input-wrap">
                  <i class="fa-solid fa-tag"></i>
                  <input type="text" class="ic-custom-form-input" name="subject" placeholder="Project Type / Subject">
                </div>
              </div>

              <div class="col-12">
                <div class="ic-input-wrap ic-input-wrap-textarea">
                  <i class="fa-regular fa-pen-to-square"></i>
                  <textarea class="ic-custom-form-input" name="message" rows="4" placeholder="Tell us about your space, timeline, and vision..." required></textarea>
                </div>
              </div>

              <div class="col-12 pt-2">
                <button type="submit" class="ic-btn-submit-interior">
                  Send Message <i class="fa-solid fa-arrow-right ms-1"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ===== 4 LOCATION CARDS ROW ===== -->
<section class="ic-info-cards-section">
  <div class="ic-container">
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <div class="ic-info-card-item">
          <div class="ic-info-card-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div>
            <div class="ic-info-card-title">Our Location</div>
            <div class="ic-info-card-desc">
              {{ $interior->address ?? '123 Design Street, Creative City, CA 94043' }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="ic-info-card-item">
          <div class="ic-info-card-icon"><i class="fa-solid fa-phone"></i></div>
          <div>
            <div class="ic-info-card-title">Call Us</div>
            <div class="ic-info-card-desc">
              {{ $interior->phone ?? '+1 (234) 567-890' }}<br>Mon - Fri: 9AM - 6PM
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="ic-info-card-item">
          <div class="ic-info-card-icon"><i class="fa-regular fa-envelope"></i></div>
          <div>
            <div class="ic-info-card-title">Email Us</div>
            <div class="ic-info-card-desc">
              {{ $interior->email ?? 'hello@interiocraft.com' }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="ic-info-card-item">
          <div class="ic-info-card-icon"><i class="fa-regular fa-clock"></i></div>
          <div>
            <div class="ic-info-card-title">Working Hours</div>
            <div class="ic-info-card-desc">
              Monday – Friday<br>9:00 AM – 6:00 PM
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== MAP SECTION WITH FLOATING CENTER CARD ===== -->
<section class="ic-map-section">
  <div class="ic-container">
    <div class="ic-map-container-relative">
      <iframe width="100%" height="420" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
              style="border: 0; filter: contrast(1.02);"
              src="https://maps.google.com/maps?width=100%25&amp;height=420&amp;hl=en&amp;q={{ urlencode($interior->address ?? '123 Design Street, Creative City, CA 94043') }}&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
              allowfullscreen="" loading="lazy"></iframe>

      <div class="ic-map-floating-card">
        <div class="ic-map-pin-badge">
          <i class="fa-solid fa-location-dot"></i>
        </div>
        <div class="ic-map-card-title">We’re Here!</div>
        <div class="ic-map-card-desc">Visit our design studio or schedule a consultation anytime.</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FAQS & CONSULTANT CARD SECTION ===== -->
<section class="ic-faqs-section">
  <div class="ic-container">
    <div class="row g-5">
      <!-- LEFT: Accordion FAQs -->
      <div class="col-lg-7">
        <div class="ic-faqs-badge">FAQS</div>
        <h2 class="ic-faqs-title">Frequently Asked Questions</h2>

        @php
          $faqs = $interior->faqs_data ?? [
            ['q' => 'How soon can we start our interior project?', 'a' => 'Once we review your space requirements, we typically initiate 3D spatial planning within 2–3 business days.'],
            ['q' => 'What details do you need for a consultation?', 'a' => 'Floor plans, square footage estimates, photo references of styles you love, and your target timeline.'],
            ['q' => 'Do you provide turnkey installation and styling?', 'a' => 'Yes! We manage end-to-end execution including custom joinery, textile sourcing, and on-site styling.'],
            ['q' => 'How do I know if my project is a good fit?', 'a' => 'Feel free to send us a message or request a complimentary discovery call with our lead architects!'],
          ];
        @endphp

        <div id="faqAccordionCustom">
          @foreach($faqs as $fi => $f)
            <div class="ic-custom-faq-item">
              <button class="ic-custom-faq-button {{ $fi == 0 ? 'active-faq' : '' }}"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#faqCollapseItem{{ $fi }}"
                      aria-expanded="{{ $fi == 0 ? 'true' : 'false' }}">
                <span>{{ $f['q'] }}</span>
                <span class="ic-faq-icon-toggle">
                  <i class="fa-solid {{ $fi == 0 ? 'fa-minus' : 'fa-plus' }}"></i>
                </span>
              </button>

              <div id="faqCollapseItem{{ $fi }}"
                   class="collapse {{ $fi == 0 ? 'show' : '' }}"
                   data-bs-parent="#faqAccordionCustom">
                <div class="ic-faq-body-text">
                  {{ $f['a'] }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- RIGHT: Consultant Banner Card -->
      <div class="col-lg-5">
        <div class="ic-consultant-card">
          <div class="ic-consultant-card-content">
            <div class="ic-consultant-title">Have Questions?<br><span class="text-interior">Talk to Us</span></div>
            <div class="ic-consultant-desc">Schedule a private session with our lead interior architects today.</div>
            <a href="tel:{{ $interior->phone ?? '+1 (234) 567-890' }}" class="ic-btn-get-touch">
              Call Us Now <i class="fa-solid fa-phone ms-1"></i>
            </a>
          </div>
          <div class="ic-consultant-img-wrap">
            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop" class="ic-consultant-img" alt="Consultant">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
