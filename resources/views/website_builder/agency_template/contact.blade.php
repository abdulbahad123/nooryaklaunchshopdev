@extends('website_builder.agency_template.layout')

@section('title', 'Contact Us - ' . ($agency->site_title ?? 'DesignAGENCY'))

@section('content')
<style>
  /* ===== CONTACT HERO SECTION ===== */
  .contact-hero-section {
    background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);
    padding: 60px 0 50px;
    position: relative;
    overflow: hidden;
  }
  .contact-badge-pill {
    background: #ECFDF5;
    color: #059669;
    font-weight: 700;
    font-size: 13px;
    padding: 6px 16px;
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 20px;
  }
  .contact-badge-pill .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10B981;
    display: inline-block;
  }
  .contact-hero-title {
    font-size: clamp(34px, 4.8vw, 52px);
    font-weight: 800;
    line-height: 1.15;
    color: #0F172A;
    margin-bottom: 18px;
    letter-spacing: -0.8px;
  }
  .contact-hero-title .text-emerald {
    color: #10B981 !important;
    position: relative;
    display: inline-block;
  }
  .contact-hero-desc {
    font-size: 16px;
    color: #475569;
    line-height: 1.65;
    margin-bottom: 32px;
    max-width: 480px;
  }

  /* Bullet Item with rounded icon box */
  .contact-bullet-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
  }
  .contact-bullet-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: #ECFDF5;
    color: #10B981;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .contact-bullet-title {
    font-size: 16px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 2px;
  }
  .contact-bullet-sub {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.5;
  }

  /* ===== CONTACT FORM CARD ===== */
  .contact-form-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 44px 48px;
    border: 1px solid #F1F5F9;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
  }
  .form-card-title {
    font-size: 24px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 4px;
  }
  .form-card-sub {
    font-size: 14px;
    color: #64748B;
    margin-bottom: 28px;
  }
  .input-wrap {
    position: relative;
    margin-bottom: 18px;
  }
  .input-wrap i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: 14px;
    pointer-events: none;
  }
  .input-wrap textarea + i,
  .input-wrap-textarea i {
    top: 20px;
    transform: none;
  }
  .custom-form-input {
    width: 100%;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 12px 18px 12px 46px;
    font-size: 14px;
    color: #0F172A;
    transition: all 0.25s ease;
    outline: none;
  }
  .custom-form-input:focus {
    background: #ffffff;
    border-color: #10B981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
  }
  .btn-submit-green {
    width: 100%;
    background: #10B981;
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
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
  }
  .btn-submit-green:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(16, 185, 129, 0.4);
  }

  /* Decorative green plane icon at top right of form card */
  .plane-graphic-decor {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 110px;
    opacity: 0.85;
    pointer-events: none;
  }
  .green-dot-decor {
    position: absolute;
    bottom: 30px;
    right: -15px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #10B981;
  }

  /* ===== 4 LOCATION / INFO CARDS ===== */
  .info-cards-section {
    padding: 40px 0;
    background: #ffffff;
  }
  .info-card-item {
    background: #F8FAFC;
    border-radius: 18px;
    padding: 24px 22px;
    border: 1px solid #F1F5F9;
    height: 100%;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    transition: all 0.25s ease;
  }
  .info-card-item:hover {
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    transform: translateY(-3px);
  }
  .info-card-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: #ECFDF5;
    color: #10B981;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .info-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 4px;
  }
  .info-card-desc {
    font-size: 13px;
    color: #64748B;
    line-height: 1.55;
    margin-bottom: 0;
  }

  /* ===== MAP SECTION WITH CENTER FLOATING CARD ===== */
  .map-section {
    padding: 20px 0 50px;
    background: #ffffff;
  }
  .map-container-relative {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    height: 420px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  }
  .map-floating-card {
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
  .map-pin-badge {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #ECFDF5;
    color: #10B981;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 12px;
  }
  .map-card-title {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 6px;
  }
  .map-card-desc {
    font-size: 13px;
    color: #64748B;
    line-height: 1.5;
    margin-bottom: 0;
  }

  /* ===== FAQS & CONSULTANT CARD SECTION ===== */
  .faqs-section {
    padding: 30px 0 120px;
    background: #ffffff;
  }
  .faqs-badge {
    color: #10B981;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    text-transform: uppercase;
  }
  .faqs-title {
    font-size: 32px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 28px;
    letter-spacing: -0.5px;
  }
  .custom-faq-item {
    border: 1.5px solid #F1F5F9;
    border-radius: 16px !important;
    margin-bottom: 14px;
    overflow: hidden;
    background: #ffffff;
    transition: all 0.25s ease;
  }
  .custom-faq-button {
    background: #ffffff;
    color: #0F172A;
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
  .custom-faq-button.active-faq {
    color: #10B981;
    background: #F0FDF4;
  }
  .faq-icon-toggle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #F1F5F9;
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    transition: all 0.25s ease;
  }
  .active-faq .faq-icon-toggle {
    background: #10B981;
    color: #ffffff;
  }
  .faq-body-text {
    padding: 0 24px 20px;
    font-size: 14px;
    color: #475569;
    line-height: 1.6;
    background: #F0FDF4;
  }

  /* Consultant Support Card Right */
  .consultant-card {
    background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
    border-radius: 24px;
    padding: 40px 40px 0;
    height: 100%;
    min-height: 340px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: row;
    align-items: flex-end;
    justify-content: space-between;
    gap: 16px;
  }
  .consultant-card-content {
    flex: 1;
    padding-bottom: 40px;
    z-index: 2;
    position: relative;
  }
  .consultant-title {
    font-size: clamp(24px, 3vw, 32px);
    font-weight: 800;
    color: #0F172A;
    line-height: 1.2;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
  }
  .consultant-title .text-emerald {
    color: #10B981 !important;
  }
  .consultant-desc {
    font-size: 14px;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 24px;
    max-width: 280px;
  }
  .btn-get-touch {
    background: #10B981;
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
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
  }
  .btn-get-touch:hover {
    background: #059669;
    color: #ffffff;
    transform: translateY(-2px);
  }
  .consultant-img-wrap {
    flex-shrink: 0;
    align-self: flex-end;
    position: relative;
    z-index: 2;
    line-height: 0;
    margin-right: -2px;
  }
  .consultant-img {
    height: 280px;
    width: auto;
    max-width: 220px;
    display: block;
    object-fit: contain;
    object-position: bottom center;
  }

  /* RESPONSIVE */
  @media (max-width: 1199px) {
    .consultant-img { height: 240px; max-width: 180px; }
  }
  @media (max-width: 991px) {
    .contact-form-card { padding: 32px 24px; }
    .info-card-item { padding: 20px 16px; }
    .consultant-card {
      padding: 32px 28px 0;
      margin-top: 24px;
      min-height: 300px;
      flex-direction: row;
      align-items: flex-end;
    }
    .consultant-img { height: 220px; max-width: 160px; }
    .consultant-card-content { padding-bottom: 32px; }
  }
  @media (max-width: 767px) {
    .consultant-card {
      padding: 28px 24px 0;
      min-height: auto;
      flex-direction: row;
      align-items: flex-end;
      gap: 12px;
    }
    .consultant-img {
      height: 180px;
      max-width: 130px;
    }
    .consultant-title { font-size: 22px; }
    .consultant-card-content { padding-bottom: 28px; }
  }
  @media (max-width: 480px) {
    .consultant-card {
      flex-direction: column;
      align-items: stretch;
      padding: 24px 20px 0;
    }
    .consultant-card-content { padding-bottom: 0; margin-bottom: 16px; }
    .consultant-img-wrap { text-align: right; }
    .consultant-img { height: 200px; max-width: 160px; margin-left: auto; }
  }
</style>

<!-- ===== CONTACT HERO & FORM SECTION ===== -->
<section class="contact-hero-section">
  <div class="container">
    <div class="row g-5 align-items-center">

      <!-- LEFT: Bullet List Info Column -->
      <div class="col-lg-5">
        <div class="contact-badge-pill">
          <span class="dot"></span> Contact Us
        </div>
        <h1 class="contact-hero-title">
          Let’s Build Something<br>
          Amazing <span class="text-emerald">Together!</span>
        </h1>
        <p class="contact-hero-desc">
          {{ $agency->contact_subtitle ?? "Have a project in mind or just want to say hello? We'd love to hear from you." }}
        </p>

        <!-- Bullet List -->
        <div>
          <!-- Bullet 1: Quick Response -->
          <div class="contact-bullet-item">
            <div class="contact-bullet-icon">
              <i class="fa-regular fa-clock"></i>
            </div>
            <div>
              <div class="contact-bullet-title">Quick Response</div>
              <div class="contact-bullet-sub">We reply to all inquiries within 24 hours.</div>
            </div>
          </div>

          <!-- Bullet 2: Expert Support -->
          <div class="contact-bullet-item">
            <div class="contact-bullet-icon">
              <i class="fa-solid fa-headset"></i>
            </div>
            <div>
              <div class="contact-bullet-title">Expert Support</div>
              <div class="contact-bullet-sub">Our team is here to help you 24/7.</div>
            </div>
          </div>

          <!-- Bullet 3: Start Your Project -->
          <div class="contact-bullet-item">
            <div class="contact-bullet-icon">
              <i class="fa-solid fa-rocket"></i>
            </div>
            <div>
              <div class="contact-bullet-title">Start Your Project</div>
              <div class="contact-bullet-sub">Let's turn your ideas into a digital reality.</div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Form Card (Pixel-Match with Ref Image) -->
      <div class="col-lg-7">
        <div class="contact-form-card">
          <!-- Top Right Decorative Flight Trail Graphic -->
          <svg class="plane-graphic-decor d-none d-sm-block" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 110 C40 80, 80 50, 100 20" stroke="#10B981" stroke-width="1.5" stroke-dasharray="4 4"/>
            <path d="M100 20 L115 15 L108 30 Z" fill="#10B981"/>
          </svg>
          <div class="green-dot-decor d-none d-sm-block"></div>

          <div class="form-card-title">Send Us a Message</div>
          <div class="form-card-sub">Fill out the form below and we'll get back to you soon.</div>

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 small fw-bold mb-4" role="alert">
              <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          @php
            try {
              $submitUrl = route('website-builder.templates.digital_agency.contact.submit');
            } catch (\Throwable $e) {
              $submitUrl = url('/website-builder/templates/digital_agency/contact');
            }
          @endphp

          <form action="{{ $submitUrl }}" method="POST">
            @csrf
            <div class="row g-3">
              <!-- Name & Email (2 Cols) -->
              <div class="col-md-6">
                <div class="input-wrap">
                  <i class="fa-regular fa-user"></i>
                  <input type="text" class="custom-form-input" name="name" placeholder="Your Name" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-wrap">
                  <i class="fa-regular fa-envelope"></i>
                  <input type="email" class="custom-form-input" name="email" placeholder="Your Email" required>
                </div>
              </div>

              <!-- Phone Number -->
              <div class="col-12">
                <div class="input-wrap">
                  <i class="fa-solid fa-phone"></i>
                  <input type="text" class="custom-form-input" name="phone" placeholder="Phone Number">
                </div>
              </div>

              <!-- Subject -->
              <div class="col-12">
                <div class="input-wrap">
                  <i class="fa-solid fa-tag"></i>
                  <input type="text" class="custom-form-input" name="subject" placeholder="Subject">
                </div>
              </div>

              <!-- Message -->
              <div class="col-12">
                <div class="input-wrap input-wrap-textarea">
                  <i class="fa-regular fa-pen-to-square"></i>
                  <textarea class="custom-form-input" name="message" rows="4" placeholder="Your Message" required></textarea>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="col-12 pt-2">
                <button type="submit" class="btn-submit-green">
                  Send Message <i class="fa-solid fa-arrow-up-right ms-1" style="font-size: 13px;"></i>
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
<section class="info-cards-section">
  <div class="container">
    <div class="row g-4">
      <!-- Col 1: Our Location -->
      <div class="col-lg-3 col-md-6">
        <div class="info-card-item">
          <div class="info-card-icon">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <div>
            <div class="info-card-title">Our Location</div>
            <div class="info-card-desc">
              {{ $agency->address ?? '123 Design Street, Creative City, CA 90403, United States' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Col 2: Call Us -->
      <div class="col-lg-3 col-md-6">
        <div class="info-card-item">
          <div class="info-card-icon">
            <i class="fa-solid fa-phone"></i>
          </div>
          <div>
            <div class="info-card-title">Call Us</div>
            <div class="info-card-desc">
              {{ $agency->phone ?? '+1 (234) 567-890' }}<br>Mon - Fri: 9AM - 6PM
            </div>
          </div>
        </div>
      </div>

      <!-- Col 3: Email Us -->
      <div class="col-lg-3 col-md-6">
        <div class="info-card-item">
          <div class="info-card-icon">
            <i class="fa-regular fa-envelope"></i>
          </div>
          <div>
            <div class="info-card-title">Email Us</div>
            <div class="info-card-desc">
              {{ $agency->email ?? 'info@designagency.com' }}<br>support@designagency.com
            </div>
          </div>
        </div>
      </div>

      <!-- Col 4: Working Hours -->
      <div class="col-lg-3 col-md-6">
        <div class="info-card-item">
          <div class="info-card-icon">
            <i class="fa-regular fa-clock"></i>
          </div>
          <div>
            <div class="info-card-title">Working Hours</div>
            <div class="info-card-desc">
              Monday – Friday<br>9:00 AM – 6:00 PM
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== MAP SECTION WITH FLOATING CENTER CARD ===== -->
<section class="map-section">
  <div class="container">
    <div class="map-container-relative">
      <iframe width="100%" height="420" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
              style="border: 0; filter: contrast(1.02);"
              src="https://maps.google.com/maps?width=100%25&amp;height=420&amp;hl=en&amp;q={{ urlencode($agency->address ?? '123 Design Street, Creative City, CA') }}&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
              allowfullscreen="" loading="lazy"></iframe>

      <!-- Floating Centered White Card (Ref Image Pixel-Match) -->
      <div class="map-floating-card">
        <div class="map-pin-badge">
          <i class="fa-solid fa-location-dot"></i>
        </div>
        <div class="map-card-title">We’re Here!</div>
        <div class="map-card-desc">Visit our office or drop us a message anytime you want.</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FAQS & CONSULTANT CARD SECTION ===== -->
<section class="faqs-section">
  <div class="container">
    <div class="row g-5">
      <!-- LEFT: Accordion FAQs -->
      <div class="col-lg-7">
        <div class="faqs-badge">FAQS</div>
        <h2 class="faqs-title">Frequently Asked Questions</h2>

        @php
          $faqs = $agency->faqs_data ?? [
            ['q' => 'How soon can we start our project?', 'a' => 'Once we understand your requirements, we can typically start within 2–3 business days.'],
            ['q' => 'What information do you need to get started?', 'a' => 'We will need your brand assets, project goals, target audience, and any content guidelines.'],
            ['q' => 'Do you offer ongoing support?', 'a' => 'Yes! We offer comprehensive maintenance, updates, and ongoing digital strategy support.'],
            ['q' => 'How do I know if my project is a good fit?', 'a' => 'Feel free to send us a quick message or book a discovery call, and our team will evaluate your needs!'],
          ];
        @endphp

        <div id="faqAccordionCustom">
          @foreach($faqs as $fi => $f)
            <div class="custom-faq-item">
              <button class="custom-faq-button {{ $fi == 0 ? 'active-faq' : '' }}"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#faqCollapseItem{{ $fi }}"
                      aria-expanded="{{ $fi == 0 ? 'true' : 'false' }}">
                <span>{{ $f['q'] }}</span>
                <span class="faq-icon-toggle">
                  <i class="fa-solid {{ $fi == 0 ? 'fa-minus' : 'fa-plus' }}"></i>
                </span>
              </button>

              <div id="faqCollapseItem{{ $fi }}"
                   class="collapse {{ $fi == 0 ? 'show' : '' }}"
                   data-bs-parent="#faqAccordionCustom">
                <div class="faq-body-text">
                  {{ $f['a'] }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- RIGHT: Ready to Start Your Project? Consultant Banner Card -->
      <div class="col-lg-5">
        <div class="consultant-card">
          <!-- Text Content (left side) -->
          <div class="consultant-card-content">
            <div class="consultant-title">
              Ready to Start<br><span class="text-emerald">Your Project?</span>
            </div>
            <div class="consultant-desc">
              Let's discuss how we can help your business grow with digital solutions.
            </div>
            @php
              $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
              $ctaContactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.contact');
            @endphp
            <a href="{{ $ctaContactUrl }}" class="btn-get-touch">
              Get In Touch <i class="fa-solid fa-arrow-up-right"></i>
            </a>
          </div>

          <!-- Support Specialist Image (right side, sitting at bottom) -->
          <div class="consultant-img-wrap">
            <img src="{{ asset('assets/website_builder/Templates/Digital_agency/contact_footer.png') }}"
                 onerror="this.src='https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop';"
                 alt="Customer Support Representative"
                 class="consultant-img">
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- FAQ TOGGLE ICON SCRIPT -->
@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const faqButtons = document.querySelectorAll('#faqAccordionCustom .custom-faq-button');
    faqButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        faqButtons.forEach(b => {
          b.classList.remove('active-faq');
          const icon = b.querySelector('.faq-icon-toggle i');
          if (icon) {
            icon.className = 'fa-solid fa-plus';
          }
        });
        const targetId = this.getAttribute('data-bs-target');
        const targetCollapse = document.querySelector(targetId);
        if (!targetCollapse.classList.contains('show')) {
          this.classList.add('active-faq');
          const icon = this.querySelector('.faq-icon-toggle i');
          if (icon) {
            icon.className = 'fa-solid fa-minus';
          }
        }
      });
    });
  });
</script>
@endsection
@endsection
