@extends('website_builder.texigo_theme.layout')

@section('no_cta')@endsection

@section('title', 'Contact Us - ' . ($agency->site_title ?? 'TaxiGo Mobility'))

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
  $aboutUrl = $subdomainParam ? route('website-builder.subdomain.about', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.about');
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.contact');
  $agency = $agency ?? $interior ?? null;
@endphp
<style>
  /* ===== CONTACT HERO SECTION ===== */
  .tx-contact-hero-section {
    background: linear-gradient(180deg, var(--tx-bg-light) 0%, #FFFFFF 100%);
    padding: 36px 0 28px;
    position: relative;
    overflow: hidden;
  }
  .tx-contact-badge-pill {
    background: var(--tx-badge-bg);
    color: var(--tx-secondary);
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
  .tx-contact-badge-pill .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--tx-primary);
    display: inline-block;
  }
  .tx-contact-hero-title {
    font-size: clamp(34px, 4.8vw, 52px);
    font-weight: 800;
    line-height: 1.15;
    color: var(--tx-text-dark);
    margin-bottom: 18px;
    letter-spacing: -0.8px;
  }
  .tx-contact-hero-title .text-yellow {
    color: var(--tx-primary) !important;
    position: relative;
    display: inline-block;
  }
  .tx-contact-hero-desc {
    font-size: 16px;
    color: var(--tx-text-muted);
    line-height: 1.65;
    margin-bottom: 32px;
    max-width: 480px;
  }

  /* Bullet Item */
  .tx-contact-bullet-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
  }
  .tx-contact-bullet-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: #FFF8E6;
    color: var(--tx-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .tx-contact-bullet-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--tx-text-dark);
    margin-bottom: 2px;
  }
  .tx-contact-bullet-sub {
    font-size: 13.5px;
    color: var(--tx-text-muted);
    line-height: 1.5;
  }

  /* ===== CONTACT FORM CARD ===== */
  .tx-contact-form-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 44px 48px;
    border: 1px solid var(--tx-border);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
  }
  .tx-form-card-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--tx-text-dark);
    margin-bottom: 4px;
  }
  .tx-form-card-sub {
    font-size: 14px;
    color: var(--tx-text-muted);
    margin-bottom: 28px;
  }
  .tx-input-wrap {
    position: relative;
    margin-bottom: 18px;
  }
  .tx-input-wrap i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--tx-text-muted);
    font-size: 14px;
    pointer-events: none;
  }
  .tx-input-wrap textarea + i,
  .tx-input-wrap-textarea i {
    top: 20px;
    transform: none;
  }
  .tx-custom-form-input {
    width: 100%;
    background: var(--tx-bg-light);
    border: 1.5px solid var(--tx-border);
    border-radius: 12px;
    padding: 12px 18px 12px 46px;
    font-size: 14px;
    color: var(--tx-text-dark);
    transition: all 0.25s ease;
    outline: none;
  }
  .tx-custom-form-input:focus {
    background: #ffffff;
    border-color: var(--tx-primary);
    box-shadow: 0 0 0 4px rgba(255, 184, 0, 0.2);
  }
  .tx-btn-submit {
    width: 100%;
    background: var(--tx-primary);
    color: var(--tx-secondary);
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
  .tx-btn-submit:hover {
    background: var(--tx-primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(255, 184, 0, 0.4);
  }

  /* Decorative yellow dot decor */
  .tx-yellow-dot-decor {
    position: absolute;
    bottom: 30px;
    right: -15px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--tx-primary);
  }

  /* ===== 4 LOCATION CARDS ROW ===== */
  .tx-info-cards-section {
    padding: 40px 0;
    background: #ffffff;
  }
  .tx-info-card-item {
    background: var(--tx-bg-light);
    border-radius: 18px;
    padding: 24px 22px;
    border: 1px solid var(--tx-border-light);
    height: 100%;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    transition: all 0.25s ease;
  }
  .tx-info-card-item:hover {
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    transform: translateY(-3px);
  }
  .tx-info-card-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: #FFF8E6;
    color: var(--tx-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .tx-info-card-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--tx-text-dark);
    margin-bottom: 4px;
  }
  .tx-info-card-desc {
    font-size: 13px;
    color: var(--tx-text-muted);
    line-height: 1.55;
    margin-bottom: 0;
  }

  /* ===== MAP SECTION WITH CENTER FLOATING CARD ===== */
  .tx-map-section {
    padding: 20px 0 50px;
    background: #ffffff;
  }
  .tx-map-container-relative {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    height: 420px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  }
  .tx-map-floating-card {
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
  .tx-map-pin-badge {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #FFF8E6;
    color: var(--tx-secondary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 12px;
  }
  .tx-map-card-title {
    font-size: 20px;
    font-weight: 800;
    color: var(--tx-text-dark);
    margin-bottom: 6px;
  }
  .tx-map-card-desc {
    font-size: 13px;
    color: var(--tx-text-muted);
    line-height: 1.5;
    margin-bottom: 0;
  }

  /* ===== FAQS SECTION ===== */
  .tx-faqs-section {
    padding: 24px 0 40px;
    background: #ffffff;
  }
  .tx-faqs-badge {
    color: #945B00;
    font-weight: 800;
    font-size: 12px;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    text-transform: uppercase;
  }
  .tx-faqs-title {
    font-size: 32px;
    font-weight: 800;
    color: var(--tx-text-dark);
    margin-bottom: 28px;
    letter-spacing: -0.5px;
  }
  .tx-custom-faq-item {
    border: 1.5px solid var(--tx-border-light);
    border-radius: 16px !important;
    margin-bottom: 14px;
    overflow: hidden;
    background: #ffffff;
    transition: all 0.25s ease;
  }
  .tx-custom-faq-button {
    background: #ffffff;
    color: var(--tx-text-dark);
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
  .tx-custom-faq-button.active-faq {
    color: var(--tx-secondary);
    background: var(--tx-bg-light);
  }
  .tx-faq-icon-toggle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--tx-border-light);
    color: var(--tx-text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    transition: all 0.25s ease;
  }
  .active-faq .tx-faq-icon-toggle {
    background: var(--tx-primary);
    color: var(--tx-secondary);
  }
  .tx-faq-body-text {
    padding: 0 24px 20px;
    font-size: 14px;
    color: var(--tx-text-muted);
    line-height: 1.6;
    background: var(--tx-bg-light);
  }

  /* Consultant Card Right */
  .tx-consultant-card {
    background: var(--tx-secondary);
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
  .tx-consultant-title {
    font-size: clamp(26px, 3.2vw, 36px);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.2;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
  }
  .tx-consultant-title .text-yellow {
    color: var(--tx-primary) !important;
  }
  .tx-consultant-desc {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.65;
    margin-bottom: 28px;
    max-width: 320px;
  }

  @media (max-width: 991px) {
    .tx-contact-form-card { padding: 32px 24px; }
    .tx-consultant-card { margin-top: 24px; }
  }
</style>

<!-- ===== CONTACT HERO & FORM SECTION ===== -->
<section class="tx-contact-hero-section">
  <div class="tx-container">
    <div class="row g-5 align-items-center">

      <!-- LEFT: Info Column -->
      <div class="col-lg-5">
        <div class="tx-contact-badge-pill">
          <span class="dot"></span> {{ $agency->contact_badge ?? 'Contact Us' }}
        </div>
        <h1 class="tx-contact-hero-title">
          {!! nl2br(e($agency->contact_title ?? "Let’s Book Your\nNext Ride Together!")) !!}
        </h1>
        <p class="tx-contact-hero-desc">
          {{ $agency->contact_subtitle ?? "Have questions about our taxi services, airport transfers, or corporate mobility? We're available 24/7 to help you." }}
        </p>

        <!-- Bullet List -->
        <div>
          @php
            $bullets = $agency->contact_bullets_data ?? [
              ['title' => $agency->contact_bullet_1_title ?? 'Quick Response', 'text' => $agency->contact_bullet_1_text ?? 'We reply to all ride inquiries within minutes.'],
              ['title' => $agency->contact_bullet_2_title ?? '24/7 Dispatch Support', 'text' => $agency->contact_bullet_2_text ?? 'Our customer mobility support team is here to assist you 24 hours a day.'],
              ['title' => $agency->contact_bullet_3_title ?? 'Transparent Pricing', 'text' => $agency->contact_bullet_3_text ?? 'Instant booking with guaranteed upfront rates and zero hidden fees.'],
            ];
          @endphp
          @foreach($bullets as $bi => $bullet)
            <div class="tx-contact-bullet-item">
              <div class="tx-contact-bullet-icon">
                <i class="fa-solid {{ $bi == 0 ? 'fa-clock' : ($bi == 1 ? 'fa-headset' : 'fa-taxi') }}"></i>
              </div>
              <div>
                <div class="tx-contact-bullet-title">{{ $bullet['title'] }}</div>
                <div class="tx-contact-bullet-sub">{{ $bullet['text'] }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- RIGHT: Form Card -->
      <div class="col-lg-7">
        <div class="tx-contact-form-card">
          <div class="tx-yellow-dot-decor d-none d-sm-block"></div>

          <div class="tx-form-card-title">{{ $agency->contact_form_title ?? 'Send Us a Message' }}</div>
          <div class="tx-form-card-sub">{{ $agency->contact_form_subtitle ?? 'Fill out the form below and our TaxiGo team will assist you immediately.' }}</div>

          <form action="{{ route('website-builder.templates.design-agency.contact.submit') }}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <div class="tx-input-wrap">
                  <i class="fa-regular fa-user"></i>
                  <input type="text" name="name" class="tx-custom-form-input" placeholder="Your Full Name" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="tx-input-wrap">
                  <i class="fa-regular fa-envelope"></i>
                  <input type="email" name="email" class="tx-custom-form-input" placeholder="Your Email Address" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="tx-input-wrap">
                  <i class="fa-solid fa-phone"></i>
                  <input type="tel" name="phone" class="tx-custom-form-input" placeholder="Phone Number">
                </div>
              </div>

              <div class="col-md-6">
                <div class="tx-input-wrap">
                  <i class="fa-solid fa-taxi"></i>
                  <input type="text" name="subject" class="tx-custom-form-input" placeholder="Ride / Service Required">
                </div>
              </div>

              <div class="col-12">
                <div class="tx-input-wrap tx-input-wrap-textarea">
                  <i class="fa-regular fa-comment-dots"></i>
                  <textarea name="message" rows="4" class="tx-custom-form-input" placeholder="Tell us pickup location, destination, and timing..." required></textarea>
                </div>
              </div>

              <div class="col-12">
                <button type="submit" class="tx-btn tx-btn-yellow w-100 py-3 fs-6">
                  Send Booking Inquiry <i class="fa-solid fa-paper-plane ms-2"></i>
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
<section class="tx-info-cards-section">
  <div class="tx-container">
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <div class="tx-info-card-item">
          <div class="tx-info-card-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div>
            <div class="tx-info-card-title">Our Location</div>
            <div class="tx-info-card-desc">
              {{ $agency->address ?? '123 Mobility Way, City Center, NY 10001' }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="tx-info-card-item">
          <div class="tx-info-card-icon"><i class="fa-solid fa-phone"></i></div>
          <div>
            <div class="tx-info-card-title">Call Us</div>
            <div class="tx-info-card-desc">
              {{ $agency->phone ?? '+1 (234) 567-890' }}<br>24/7 Hotline
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="tx-info-card-item">
          <div class="tx-info-card-icon"><i class="fa-regular fa-envelope"></i></div>
          <div>
            <div class="tx-info-card-title">Email Us</div>
            <div class="tx-info-card-desc">
              {{ $agency->email ?? 'hello@taxigo.com' }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="tx-info-card-item">
          <div class="tx-info-card-icon"><i class="fa-regular fa-clock"></i></div>
          <div>
            <div class="tx-info-card-title">Working Hours</div>
            <div class="tx-info-card-desc">
              {!! nl2br(e($agency->working_hours ?? "Monday – Sunday\n24 Hours Available")) !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== MAP SECTION WITH FLOATING CENTER CARD ===== -->
<section class="tx-map-section">
  <div class="tx-container">
    <div class="tx-map-container-relative">
      <iframe width="100%" height="420" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
              style="border: 0; filter: contrast(1.02);"
              src="https://maps.google.com/maps?width=100%25&amp;height=420&amp;hl=en&amp;q={{ urlencode($agency->address ?? '123 Mobility Way, City Center, NY 10001') }}&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
              allowfullscreen="" loading="lazy"></iframe>

      <div class="tx-map-floating-card">
        <div class="tx-map-pin-badge">
          <i class="fa-solid fa-taxi"></i>
        </div>
        <div class="tx-map-card-title">We’re Here!</div>
        <div class="tx-map-card-desc">Visit our city dispatch hub or book your ride online anytime.</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FAQS & CONSULTANT CARD SECTION ===== -->
<section class="tx-faqs-section">
  <div class="tx-container">
    <div class="row g-5">
      <!-- LEFT: Accordion FAQs -->
      <div class="col-lg-7">
        <div class="tx-faqs-badge">{{ $agency->faqs_badge ?? 'FAQS' }}</div>
        <h2 class="tx-faqs-title">{!! nl2br(e($agency->faqs_title ?? 'Frequently Asked Questions')) !!}</h2>

        @php
          $faqs = $agency->faqs_data ?? [
            ['q' => 'How quickly can a driver arrive at my location?', 'a' => 'Our automated smart dispatch assigns the nearest driver, typically arriving within 5 to 10 minutes.'],
            ['q' => 'Can I schedule an airport transfer in advance?', 'a' => 'Yes! You can pre-book airport pickups and drop-offs days or weeks in advance with guaranteed flight tracking.'],
            ['q' => 'Are there any hidden fees or surge pricing?', 'a' => 'No. TaxiGo provides transparent, fixed upfront pricing with zero hidden charges.'],
            ['q' => 'What payment options are accepted?', 'a' => 'We accept credit/debit cards, online mobile wallets, Razorpay, and cash.'],
          ];
        @endphp

        <div id="faqAccordionCustom">
          @foreach($faqs as $fi => $f)
            <div class="tx-custom-faq-item">
              <button class="tx-custom-faq-button {{ $fi == 0 ? 'active-faq' : '' }}"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#faqCollapseItem{{ $fi }}"
                      aria-expanded="{{ $fi == 0 ? 'true' : 'false' }}">
                <span>{{ $f['q'] }}</span>
                <span class="tx-faq-icon-toggle">
                  <i class="fa-solid {{ $fi == 0 ? 'fa-minus' : 'fa-plus' }}"></i>
                </span>
              </button>

              <div id="faqCollapseItem{{ $fi }}"
                   class="collapse {{ $fi == 0 ? 'show' : '' }}"
                   data-bs-parent="#faqAccordionCustom">
                <div class="tx-faq-body-text">
                  {{ $f['a'] }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- RIGHT: Consultant Banner Card -->
      <div class="col-lg-5">
        <div class="tx-consultant-card">
          <div class="tx-consultant-title">{!! nl2br(e($agency->helpline_title ?? $agency->consultant_title ?? "Need Immediate\nAssistance?")) !!}</div>
          <div class="tx-consultant-desc">{{ $agency->helpline_desc ?? $agency->consultant_desc ?? 'Speak directly with our 24/7 taxi dispatch helpline for quick support.' }}</div>
          <a href="{{ $agency->helpline_btn_url ?? ('tel:' . ($agency->phone ?? '+1 (234) 567-890')) }}" class="tx-btn tx-btn-yellow mt-2">
            {{ $agency->helpline_btn_text ?? 'Call Dispatch Now' }} <i class="fa-solid fa-phone ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
