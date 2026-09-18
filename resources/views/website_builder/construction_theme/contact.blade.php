@extends('website_builder.construction_theme.layout')

@section('no_cta')@endsection

@section('title', 'Contact Us - ' . ($agency->site_title ?? 'BuildCraft Construction'))

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.contact');
  $agency = $agency ?? $interior ?? null;
  $heroBannerBg = asset('assets/website_builder/Templates/Construction_agency/construction_herobanner.png');
  $footerCtaBg = asset('assets/website_builder/Templates/Construction_agency/construction_footercta.png');
@endphp

<style>
  /* ===== CONTACT HERO & FORM SECTION ===== */
  .cn-contact-hero-section {
    background: linear-gradient(180deg, #F8F9FA 0%, #FFFFFF 100%);
    padding: 56px 0 !important;
    position: relative;
    overflow: hidden;
  }
  .cn-contact-badge-pill {
    background: #FFF8E6;
    color: #945B00;
    font-weight: 700;
    font-size: 13px;
    padding: 6px 16px;
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 20px;
    border: 1px solid rgba(255, 184, 0, 0.4);
  }
  .cn-contact-badge-pill .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #FFB800;
    display: inline-block;
  }
  .cn-contact-hero-title {
    font-size: clamp(34px, 4.8vw, 52px);
    font-weight: 800;
    line-height: 1.15;
    color: #0D0F12;
    margin-bottom: 18px;
    letter-spacing: -0.8px;
    font-family: 'Barlow Condensed', 'Inter', sans-serif;
  }
  .cn-contact-hero-desc {
    font-size: 16px;
    color: #64748B;
    line-height: 1.65;
    margin-bottom: 32px;
    max-width: 480px;
  }

  /* Bullet Item */
  .cn-contact-bullet-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
  }
  .cn-contact-bullet-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: #FFF8E6;
    color: #111111;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    border: 1px solid rgba(255,184,0,0.3);
  }
  .cn-contact-bullet-title {
    font-size: 16px;
    font-weight: 800;
    color: #0D0F12;
    margin-bottom: 2px;
  }
  .cn-contact-bullet-sub {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.5;
  }

  /* ===== CONTACT FORM CARD ===== */
  .cn-contact-form-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 44px 48px;
    border: 1.5px solid #E2E8F0;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
  }
  .cn-form-card-title {
    font-size: 24px;
    font-weight: 800;
    color: #0D0F12;
    margin-bottom: 4px;
    font-family: 'Barlow Condensed', 'Inter', sans-serif;
  }
  .cn-form-card-sub {
    font-size: 14px;
    color: #64748B;
    margin-bottom: 28px;
  }
  .cn-input-wrap {
    position: relative;
    margin-bottom: 18px;
  }
  .cn-input-wrap i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748B;
    font-size: 14px;
    pointer-events: none;
  }
  .cn-input-wrap textarea + i,
  .cn-input-wrap-textarea i {
    top: 20px;
    transform: none;
  }
  .cn-custom-form-input {
    width: 100%;
    background: #F8F9FA;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 12px 18px 12px 46px;
    font-size: 14px;
    color: #0D0F12;
    transition: all 0.25s ease;
    outline: none;
  }
  .cn-custom-form-input:focus {
    background: #ffffff;
    border-color: #FFB800;
    box-shadow: 0 0 0 4px rgba(255, 184, 0, 0.2);
  }
  .cn-btn-submit {
    width: 100%;
    background: #FFB800;
    color: #111111;
    font-weight: 800;
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
    box-shadow: 0 8px 20px rgba(255, 184, 0, 0.3);
  }
  .cn-btn-submit:hover {
    background: #E0A200;
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(255, 184, 0, 0.4);
  }

  /* Decorative yellow dot decor */
  .cn-yellow-dot-decor {
    position: absolute;
    bottom: 30px;
    right: -15px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #FFB800;
  }

  /* ===== 4 LOCATION CARDS ROW ===== */
  .cn-info-cards-section {
    padding: 56px 0 !important;
    background: #ffffff;
  }
  .cn-info-card-item {
    background: #F8F9FA;
    border-radius: 18px;
    padding: 24px 22px;
    border: 1px solid #F1F5F9;
    height: 100%;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    transition: all 0.25s ease;
  }
  .cn-info-card-item:hover {
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    transform: translateY(-3px);
  }
  .cn-info-card-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: #FFF8E6;
    color: #111111;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    border: 1px solid rgba(255,184,0,0.3);
  }
  .cn-info-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #0D0F12;
    margin-bottom: 4px;
    font-family: 'Barlow Condensed', 'Inter', sans-serif;
  }
  .cn-info-card-desc {
    font-size: 13px;
    color: #64748B;
    line-height: 1.55;
    margin-bottom: 0;
  }

  /* ===== MAP SECTION WITH CENTER FLOATING CARD ===== */
  .cn-map-section {
    padding: 56px 0 !important;
    background: #ffffff;
  }
  .cn-map-container-relative {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    height: 420px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  }
  .cn-map-floating-card {
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
  .cn-map-pin-badge {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #FFF8E6;
    color: #111111;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 12px;
    border: 1px solid rgba(255,184,0,0.4);
  }
  .cn-map-card-title {
    font-size: 20px;
    font-weight: 800;
    color: #0D0F12;
    margin-bottom: 6px;
    font-family: 'Barlow Condensed', 'Inter', sans-serif;
  }
  .cn-map-card-desc {
    font-size: 13px;
    color: #64748B;
    line-height: 1.5;
    margin-bottom: 0;
  }

  /* ===== FAQS SECTION ===== */
  .cn-faqs-section {
    padding: 56px 0 !important;
    background: #ffffff;
  }
  .cn-faqs-badge {
    color: #945B00;
    font-weight: 800;
    font-size: 12px;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    text-transform: uppercase;
  }
  .cn-faqs-title {
    font-size: 32px;
    font-weight: 800;
    color: #0D0F12;
    margin-bottom: 28px;
    letter-spacing: -0.5px;
    font-family: 'Barlow Condensed', 'Inter', sans-serif;
  }
  .cn-custom-faq-item {
    border: 1.5px solid #F1F5F9;
    border-radius: 16px !important;
    margin-bottom: 14px;
    overflow: hidden;
    background: #ffffff;
    transition: all 0.25s ease;
  }
  .cn-custom-faq-button {
    background: #ffffff;
    color: #0D0F12;
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
  .cn-custom-faq-button.active-faq {
    color: #0D0F12;
    background: #F8F9FA;
  }
  .cn-faq-icon-toggle {
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
  .active-faq .cn-faq-icon-toggle {
    background: #FFB800;
    color: #111111;
  }
  .cn-faq-body-text {
    padding: 0 24px 20px;
    font-size: 14px;
    color: #64748B;
    line-height: 1.6;
    background: #F8F9FA;
  }

  /* Consultant Card Right */
  .cn-consultant-card {
    background: #111111;
    color: #ffffff;
    border-radius: 24px;
    padding: 44px 36px;
    height: 100%;
    min-height: 360px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .cn-consultant-title {
    font-size: clamp(26px, 3.2vw, 36px);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.2;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
    font-family: 'Barlow Condensed', 'Inter', sans-serif;
  }

  @media (max-width: 991px) {
    .cn-contact-form-card { padding: 32px 24px; }
    .cn-consultant-card { margin-top: 24px; }
  }
</style>

<!-- ===== CONTACT HERO & FORM SECTION ===== -->
<section class="cn-contact-hero-section" style="background: url('{{ $heroBannerBg }}') no-repeat center right / cover; position: relative;">
  <div class="cn-hero-overlay" style="position: absolute; inset: 0; background: rgba(255,255,255,0.92); z-index: 1;"></div>
  
  <div class="cn-container" style="position: relative; z-index: 2;">
    <div class="row g-5 align-items-center">

      <!-- LEFT: Info Column -->
      <div class="col-lg-5">
        <div class="cn-contact-badge-pill">
          <span class="dot"></span> {{ $agency->contact_badge ?? 'Contact Us' }}
        </div>
        <h1 class="cn-contact-hero-title">
          {!! nl2br(e($agency->contact_title ?? "Let’s Build Your\nNext Project Together!")) !!}
        </h1>
        <p class="cn-contact-hero-desc">
          {{ $agency->contact_subtitle ?? "Have questions about our construction, engineering, or renovation services? We're available to assist you." }}
        </p>

        <!-- Bullet List -->
        <div>
          <div class="cn-contact-bullet-item">
            <div class="cn-contact-bullet-icon">
              <i class="fa-regular fa-clock"></i>
            </div>
            <div>
              <div class="cn-contact-bullet-title">{{ $agency->contact_bullet_1_title ?? 'Quick Response' }}</div>
              <div class="cn-contact-bullet-sub">{{ $agency->contact_bullet_1_text ?? 'We reply to all project inquiries within hours.' }}</div>
            </div>
          </div>

          <div class="cn-contact-bullet-item">
            <div class="cn-contact-bullet-icon">
              <i class="fa-solid fa-headset"></i>
            </div>
            <div>
              <div class="cn-contact-bullet-title">{{ $agency->contact_bullet_2_title ?? '24/7 Site Support' }}</div>
              <div class="cn-contact-bullet-sub">{{ $agency->contact_bullet_2_text ?? 'Our project management team is ready to help round the clock.' }}</div>
            </div>
          </div>

          <div class="cn-contact-bullet-item">
            <div class="cn-contact-bullet-icon">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
              <div class="cn-contact-bullet-title">{{ $agency->contact_bullet_3_title ?? 'Transparent Pricing' }}</div>
              <div class="cn-contact-bullet-sub">{{ $agency->contact_bullet_3_text ?? 'Guaranteed upfront estimates with zero hidden costs.' }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Form Card -->
      <div class="col-lg-7">
        <div class="cn-contact-form-card">
          <div class="cn-yellow-dot-decor d-none d-sm-block"></div>

          <div class="cn-form-card-title">{{ $agency->contact_form_title ?? 'Send Us a Message' }}</div>
          <div class="cn-form-card-sub">{{ $agency->contact_form_subtitle ?? 'Fill out the form below and our BuildCraft team will assist you immediately.' }}</div>

          @if(session('success'))
            <div class="alert alert-warning alert-dismissible fade show rounded-3 small fw-bold mb-4" role="alert" style="background: #FFB800; color: #0D0F12;">
              <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you! Your message has been sent successfully. Our BuildCraft team will contact you shortly.');">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <div class="cn-input-wrap">
                  <i class="fa-regular fa-user"></i>
                  <input type="text" class="cn-custom-form-input" name="name" placeholder="Your Name" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="cn-input-wrap">
                  <i class="fa-regular fa-envelope"></i>
                  <input type="email" class="cn-custom-form-input" name="email" placeholder="Your Email" required>
                </div>
              </div>

              <div class="col-12">
                <div class="cn-input-wrap">
                  <i class="fa-solid fa-phone"></i>
                  <input type="text" class="cn-custom-form-input" name="phone" placeholder="Phone Number">
                </div>
              </div>

              <div class="col-12">
                <div class="cn-input-wrap">
                  <i class="fa-solid fa-building"></i>
                  <input type="text" class="cn-custom-form-input" name="subject" placeholder="Project Type / Inquiry Subject">
                </div>
              </div>

              <div class="col-12">
                <div class="cn-input-wrap cn-input-wrap-textarea">
                  <i class="fa-regular fa-pen-to-square"></i>
                  <textarea class="cn-custom-form-input" name="message" rows="4" placeholder="Tell us about your project requirements, location, estimated budget..." required></textarea>
                </div>
              </div>

              <div class="col-12 pt-2">
                <button type="submit" class="cn-btn-submit">
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
<section class="cn-info-cards-section">
  <div class="cn-container">
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <div class="cn-info-card-item">
          <div class="cn-info-card-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div>
            <div class="cn-info-card-title">Our Location</div>
            <div class="cn-info-card-desc">
              {{ $agency->address ?? '123 Construction Avenue, New York, NY 10001' }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="cn-info-card-item">
          <div class="cn-info-card-icon"><i class="fa-solid fa-phone"></i></div>
          <div>
            <div class="cn-info-card-title">Call Us</div>
            <div class="cn-info-card-desc">
              {{ $agency->phone ?? '+1 (234) 567-890' }}<br>Support Available
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="cn-info-card-item">
          <div class="cn-info-card-icon"><i class="fa-regular fa-envelope"></i></div>
          <div>
            <div class="cn-info-card-title">Email Us</div>
            <div class="cn-info-card-desc">
              {{ $agency->email ?? 'info@buildcraft.com' }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="cn-info-card-item">
          <div class="cn-info-card-icon"><i class="fa-regular fa-clock"></i></div>
          <div>
            <div class="cn-info-card-title">Working Hours</div>
            <div class="cn-info-card-desc">
              Monday – Saturday<br>8:00 AM – 6:00 PM
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== MAP SECTION WITH FLOATING CENTER CARD ===== -->
<section class="cn-map-section">
  <div class="cn-container">
    <div class="cn-map-container-relative">
      <iframe width="100%" height="420" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
              style="border: 0; filter: contrast(1.02);"
              src="https://maps.google.com/maps?width=100%25&amp;height=420&amp;hl=en&amp;q={{ urlencode($agency->address ?? '123 Construction Avenue, New York, NY 10001') }}&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
              allowfullscreen="" loading="lazy"></iframe>

      <div class="cn-map-floating-card">
        <div class="cn-map-pin-badge">
          <i class="fa-solid fa-helmet-safety"></i>
        </div>
        <div class="cn-map-card-title">We’re Here!</div>
        <div class="cn-map-card-desc">Visit our head office or submit your inquiry online anytime.</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FAQS & CONSULTANT CARD SECTION ===== -->
<section class="cn-faqs-section">
  <div class="cn-container">
    <div class="row g-5">
      <!-- LEFT: Accordion FAQs -->
      <div class="col-lg-7">
        <div class="cn-faqs-badge">{{ $agency->faqs_badge ?? 'FAQS' }}</div>
        <h2 class="cn-faqs-title">{!! nl2br(e($agency->faqs_title ?? 'Frequently Asked Questions')) !!}</h2>

        @php
          $faqs = $agency->faqs_data ?? [
            ['q' => 'How quickly can you provide a project cost estimate?', 'a' => 'Our project estimation team reviews architectural plans and provides detailed cost breakdowns within 24–48 hours.'],
            ['q' => 'Do you handle both commercial and residential projects?', 'a' => 'Yes! We specialize in residential housing, commercial offices, industrial complexes, and civil infrastructure.'],
            ['q' => 'Are your construction projects fully insured and compliant?', 'a' => 'Absolute zero-hazard site compliance with complete liability insurance and safety certifications on every build.'],
            ['q' => 'What payment structures do you accept for major builds?', 'a' => 'We offer milestone-based payment schedules transparently aligned with completion stages.'],
          ];
        @endphp

        <div id="faqAccordionCustom">
          @foreach($faqs as $fi => $f)
            <div class="cn-custom-faq-item">
              <button class="cn-custom-faq-button {{ $fi == 0 ? 'active-faq' : '' }}"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#faqCollapseItem{{ $fi }}"
                      aria-expanded="{{ $fi == 0 ? 'true' : 'false' }}">
                <span>{{ $f['q'] }}</span>
                <span class="cn-faq-icon-toggle">
                  <i class="fa-solid {{ $fi == 0 ? 'fa-minus' : 'fa-plus' }}"></i>
                </span>
              </button>

              <div id="faqCollapseItem{{ $fi }}"
                   class="collapse {{ $fi == 0 ? 'show' : '' }}"
                   data-bs-parent="#faqAccordionCustom">
                <div class="cn-faq-body-text">
                  {{ $f['a'] }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- RIGHT: Consultant Banner Card -->
      <div class="col-lg-5">
        <div class="cn-consultant-card">
          <div class="cn-consultant-title">{!! nl2br(e($agency->consultant_title ?? "Need Immediate\nConsultation?")) !!}</div>
          <div class="tx-consultant-desc" style="font-size: 14px; color: rgba(255,255,255,0.7); line-height: 1.65; margin-bottom: 28px;">{{ $agency->consultant_desc ?? 'Speak directly with our chief site engineers and project managers.' }}</div>
          <a href="tel:{{ $agency->phone ?? '+1 (234) 567-890' }}" class="cn-btn cn-btn-yellow mt-2">
            Call Engineering Desk <i class="fa-solid fa-phone ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FOOTER CTA BANNER ===== -->
<section class="cn-footer-cta-wrapper py-4">
  <div class="cn-container">
    <div class="cn-footer-cta-card" style="background: url('{{ $footerCtaBg }}') no-repeat center center / cover;">
      <div class="cn-cta-overlay"></div>
      
      <div style="position: relative; z-index: 2;">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="cn-pill-badge mb-2" style="background: rgba(255,184,0,0.2); color: #FFB800;">LET'S BUILD TOGETHER</div>
            <h2 class="cn-cta-title text-white fw-extrabold mb-3" style="font-family: 'Barlow Condensed', sans-serif; font-size: clamp(28px, 4vw, 44px);">
              Turn Your Ideas Into <span class="cn-text-yellow" style="color: #FFB800;">Reality</span>
            </h2>
            <p class="cn-cta-sub mb-4 text-white-50">
              Partner with BuildCraft for innovative, reliable, and sustainable construction solutions.
            </p>
            <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
              Get a Quote <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>

          <!-- Right Side Vertical Step Column -->
          <div class="col-lg-5 mt-4 mt-lg-0 d-none d-md-block">
            <div class="cn-cta-steps-vertical">
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-bullseye"></i></div>
                <div class="cn-step-text">Quality Construction</div>
              </div>
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-clock"></i></div>
                <div class="cn-step-text">On-Time Delivery</div>
              </div>
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-user-gear"></i></div>
                <div class="cn-step-text">Expert Team</div>
              </div>
              <div class="cn-step-v-item">
                <div class="cn-step-icon"><i class="fa-solid fa-leaf"></i></div>
                <div class="cn-step-text">Sustainable Solutions</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
