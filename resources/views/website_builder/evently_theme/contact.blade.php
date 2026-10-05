@extends('website_builder.evently_theme.layout')

@section('title', 'Contact Us - ' . ($interior->site_title ?? 'Evently'))

@section('no_cta', true)

@section('content')

@php
  $evData = $interior ?? $agency ?? null;
  $isContactFormAllowed = isset($evData) && method_exists($evData, 'isFeatureEnabled') ? $evData->isFeatureEnabled('contact_form', $customer ?? null) : true;
  $isMapAllowed         = isset($evData) && method_exists($evData, 'isFeatureEnabled') ? $evData->isFeatureEnabled('map_section', $customer ?? null) : true;

  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;

  $homeUrl      = $subdomainParam ? route('website-builder.subdomain.site',      ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.about');
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.portfolio');
@endphp

<!-- ===== CONTACT HERO BANNER ===== -->
<section class="ev-page-hero">
  <div class="ev-container">
    <div class="ev-page-hero-content">
      <div class="ev-page-hero-badge">
        <i class="fa-solid fa-paper-plane"></i> {{ $evData->contact_badge ?? 'Contact Us' }}
      </div>
      <h1 class="ev-page-hero-title">
        {!! nl2br(e($evData->contact_title ?? "Let's Plan Something\nAmazing Together!")) !!}
      </h1>
      <p class="ev-page-hero-sub">
        {{ $evData->contact_subtitle ?? "Have an event in mind? We'd love to hear from you. Get in touch and let's make it extraordinary." }}
      </p>
      <div class="ev-breadcrumb">
        <a href="{{ $homeUrl }}">Home</a>
        <span class="separator"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
        <span class="current">Contact Us</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== CONTACT FORM & INFO ===== -->
<section class="ev-contact-hero-section">
  <div class="ev-container">
    <div class="row g-5 align-items-center">

      <!-- LEFT: Bullet List Info -->
      <div class="col-lg-5">
        <div class="ev-pill-badge mb-3"><span class="ev-dot"></span> {{ $evData->contact_badge ?? 'Get In Touch' }}</div>
        <h2 class="ev-section-title mb-3" style="font-family: var(--ev-font-heading);">
          {!! nl2br(e($evData->contact_title ?? "Let's Build Something\nAmazing Together!")) !!}
        </h2>
        <p class="ev-section-subtitle mb-4">
          {{ $evData->contact_subtitle ?? "Have an event project in mind? We'd love to hear from you." }}
        </p>

        <div class="ev-contact-bullet">
          <div class="ev-contact-bullet-icon"><i class="fa-regular fa-clock"></i></div>
          <div>
            <div class="ev-contact-bullet-title">{{ $evData->contact_bullet_1_title ?? 'Quick Response' }}</div>
            <div class="ev-contact-bullet-sub">{{ $evData->contact_bullet_1_text ?? 'We reply to all inquiries within 24 hours.' }}</div>
          </div>
        </div>

        <div class="ev-contact-bullet">
          <div class="ev-contact-bullet-icon"><i class="fa-solid fa-headset"></i></div>
          <div>
            <div class="ev-contact-bullet-title">{{ $evData->contact_bullet_2_title ?? 'Expert Support' }}</div>
            <div class="ev-contact-bullet-sub">{{ $evData->contact_bullet_2_text ?? 'Our lead event planners are here to help you 24/7.' }}</div>
          </div>
        </div>

        <div class="ev-contact-bullet">
          <div class="ev-contact-bullet-icon"><i class="fa-solid fa-calendar-check"></i></div>
          <div>
            <div class="ev-contact-bullet-title">{{ $evData->contact_bullet_3_title ?? 'Plan Your Event' }}</div>
            <div class="ev-contact-bullet-sub">{{ $evData->contact_bullet_3_text ?? "Let's turn your event vision into an extraordinary reality." }}</div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Contact Form -->
      <div class="col-lg-7">
        <div class="ev-contact-form-card">
          <div class="ev-form-card-title">{{ $evData->contact_form_title ?? 'Send Us a Message' }}</div>
          <div class="ev-form-card-sub">{{ $evData->contact_form_subtitle ?? 'Fill out the form below and our events team will contact you shortly.' }}</div>

          @if(!$isContactFormAllowed)
            <div class="alert alert-warning border border-warning rounded-3 small fw-bold mb-4" role="alert">
              <i class="fa-solid fa-lock text-warning fs-6 me-2"></i>
              <span>Contact form messaging is disabled for this subscription package plan.</span>
            </div>
          @endif

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 small fw-bold mb-4" style="background: var(--ev-primary); color: #fff;">
              <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 small fw-bold mb-4" role="alert">
              <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          @php
            try {
              $submitUrl = route('website-builder.templates.evently.contact.submit');
            } catch (\Throwable $e) {
              $submitUrl = route('website-builder.templates.digital_agency.contact.submit');
            }
          @endphp

          <form action="{{ $submitUrl }}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <div class="ev-input-wrap">
                  <i class="fa-regular fa-user"></i>
                  <input type="text" class="ev-custom-input" name="name" placeholder="Your Name" required {{ !$isContactFormAllowed ? 'disabled' : '' }}>
                </div>
              </div>
              <div class="col-md-6">
                <div class="ev-input-wrap">
                  <i class="fa-regular fa-envelope"></i>
                  <input type="email" class="ev-custom-input" name="email" placeholder="Your Email" required {{ !$isContactFormAllowed ? 'disabled' : '' }}>
                </div>
              </div>
              <div class="col-12">
                <div class="ev-input-wrap">
                  <i class="fa-solid fa-phone"></i>
                  <input type="text" class="ev-custom-input" name="phone" placeholder="Phone Number" {{ !$isContactFormAllowed ? 'disabled' : '' }}>
                </div>
              </div>
              <div class="col-12">
                <div class="ev-input-wrap">
                  <i class="fa-solid fa-calendar-check"></i>
                  <input type="text" class="ev-custom-input" name="subject" placeholder="Event Type / Subject" {{ !$isContactFormAllowed ? 'disabled' : '' }}>
                </div>
              </div>
              <div class="col-12">
                <div class="ev-input-wrap ev-input-wrap-textarea">
                  <i class="fa-regular fa-pen-to-square"></i>
                  <textarea class="ev-custom-input" name="message" rows="4" placeholder="Tell us about your event, date, venue, and guest count..." required style="padding-top: 14px;" {{ !$isContactFormAllowed ? 'disabled' : '' }}></textarea>
                </div>
              </div>

              <!-- reCAPTCHA Security Verification -->
              <div class="col-12">
                <div class="p-3 bg-light rounded-3 border d-flex flex-column gap-1">
                  <label class="form-label small fw-bold text-muted mb-1 d-flex align-items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-purple" style="color: #6C3CE1;"></i> Security Verification (reCAPTCHA)
                  </label>
                  <div class="ev-input-wrap mb-0">
                    <i class="fa-solid fa-lock"></i>
                    <input type="text" class="ev-custom-input" name="captcha" placeholder="Security Code Answer: Enter 5 + 3 = ?" required {{ !$isContactFormAllowed ? 'disabled' : '' }}>
                  </div>
                </div>
              </div>

              <div class="col-12 pt-2">
                <button type="submit" class="ev-btn-submit" {{ !$isContactFormAllowed ? 'disabled style=opacity:0.65;cursor:not-allowed;' : '' }}>
                  @if($isContactFormAllowed)
                    Send Message <i class="fa-solid fa-arrow-right ms-1"></i>
                  @else
                    <i class="fa-solid fa-lock me-1"></i> Contact Form Disabled (Subscription Plan)
                  @endif
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== 4 INFO CARDS ===== -->
<section class="ev-info-cards-section">
  <div class="ev-container">
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <div class="ev-info-card">
          <div class="ev-info-card-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div>
            <div class="ev-info-card-title">Our Location</div>
            <div class="ev-info-card-desc">{{ $evData->address ?? '123 Event Avenue, Creative City, CA 94043' }}</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="ev-info-card">
          <div class="ev-info-card-icon"><i class="fa-solid fa-phone"></i></div>
          <div>
            <div class="ev-info-card-title">Call Us</div>
            <div class="ev-info-card-desc">{{ $evData->phone ?? '+1 (234) 567-890' }}<br>Mon - Sat: 9AM - 7PM</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="ev-info-card">
          <div class="ev-info-card-icon"><i class="fa-regular fa-envelope"></i></div>
          <div>
            <div class="ev-info-card-title">Email Us</div>
            <div class="ev-info-card-desc">{{ $evData->email ?? 'hello@evently.com' }}</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="ev-info-card">
          <div class="ev-info-card-icon"><i class="fa-regular fa-clock"></i></div>
          <div>
            <div class="ev-info-card-title">Working Hours</div>
            <div class="ev-info-card-desc">Monday – Saturday<br>9:00 AM – 7:00 PM</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== MAP SECTION ===== -->
@if($isMapAllowed)
<section class="ev-map-section">
  <div class="ev-container">
    <div class="ev-map-container">
      <iframe width="100%" height="420" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
              style="border: 0; filter: contrast(1.02);"
              src="https://maps.google.com/maps?width=100%25&height=420&hl=en&q={{ urlencode($evData->address ?? '123 Event Avenue, Creative City, CA 94043') }}&t=&z=13&ie=UTF8&iwloc=B&output=embed"
              allowfullscreen="" loading="lazy"></iframe>

      <div class="ev-map-float-card">
        <div class="ev-map-pin-icon"><i class="fa-solid fa-location-dot"></i></div>
        <div style="font-size: 18px; font-weight: 800; color: var(--ev-text-dark); margin-bottom: 6px;">We're Here!</div>
        <div style="font-size: 13px; color: var(--ev-text-muted);">Visit our event planning studio or schedule a free consultation.</div>
      </div>
    </div>
  </div>
</section>
@endif

<!-- ===== FAQS & CONSULTANT CARD ===== -->
<section class="ev-faqs-section">
  <div class="ev-container">
    <div class="row g-5">

      <!-- LEFT: FAQ Accordion -->
      <div class="col-lg-7">
        <div class="ev-pill-badge mb-2"><span class="ev-dot"></span> {{ $evData->faqs_badge ?? 'FAQs' }}</div>
        <h2 class="ev-section-title mb-4" style="font-family: var(--ev-font-heading);">{!! nl2br(e($evData->faqs_title ?? 'Frequently Asked Questions')) !!}</h2>

        @php
          $faqs = $evData->faqs_data ?? [
            ['q' => 'How soon can we start planning our event?',       'a' => 'Once we receive your event requirements, we typically begin initial planning within 2–3 business days.'],
            ['q' => 'What details do you need for a consultation?',    'a' => 'Guest count estimates, event date, venue preferences, budget range, and any style references you love.'],
            ['q' => 'Do you provide end-to-end event management?',     'a' => 'Yes! We handle everything from concept and design to vendor coordination and on-day execution.'],
            ['q' => 'How do I know if my event is a good fit?',        'a' => 'Send us a message or request a free discovery call with our lead event planners!'],
          ];
        @endphp

        <div id="evFaqAccordion">
          @foreach($faqs as $fi => $f)
            <div class="ev-faq-item">
              <button class="ev-faq-btn {{ $fi == 0 ? '' : 'collapsed' }}"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#evFaqItem{{ $fi }}"
                      aria-expanded="{{ $fi == 0 ? 'true' : 'false' }}">
                <span>{{ $f['q'] }}</span>
                <span class="ev-faq-toggle-icon">
                  <i class="fa-solid fa-plus faq-icon-plus"></i>
                  <i class="fa-solid fa-minus faq-icon-minus"></i>
                </span>
              </button>
              <div id="evFaqItem{{ $fi }}"
                   class="collapse {{ $fi == 0 ? 'show' : '' }}"
                   data-bs-parent="#evFaqAccordion">
                <div class="ev-faq-body">{{ $f['a'] }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- RIGHT: Consultant / Helpline Box -->
      <div class="col-lg-5">
        <div class="ev-consultant-card">
          <div class="ev-consultant-content">
            <div class="ev-consultant-title">
              {!! nl2br(e($evData->helpline_title ?? $evData->consultant_title ?? "Have Questions?\nTalk to Us")) !!}
            </div>
            <div class="ev-consultant-desc">{{ $evData->helpline_desc ?? $evData->consultant_desc ?? 'Schedule a private session with our lead event planners today.' }}</div>
            <a href="{{ $evData->helpline_btn_url ?? 'tel:' . ($evData->phone ?? '+12345678901') }}" class="ev-btn-call">
              {{ $evData->helpline_btn_text ?? 'Call Us Now' }} <i class="fa-solid fa-phone ms-1"></i>
            </a>
          </div>
          @php
            $defaultContactImg = asset('assets/website_builder/Templates/Interior_agency/contact_footer.png');
            $cImg = $evData->contact_image ?? '';
            $isOld = empty($cImg) || str_contains($cImg, 'unsplash.com') || str_contains($cImg, 'agency_template');
            $contactImgSrc = !$isOld ? (str_starts_with($cImg, 'http') ? $cImg : asset(ltrim($cImg, '/'))) : $defaultContactImg;
          @endphp
          <div class="ev-consultant-img-col">
            <img src="{{ $contactImgSrc }}"
                 onerror="this.src='{{ $defaultContactImg }}';"
                 class="ev-consultant-img"
                 alt="Event Consultant">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
