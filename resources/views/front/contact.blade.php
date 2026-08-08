@extends('front.layout')

@section('pagename')
  - {{ $pageHeading ?? __('Contact') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->contact_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->contact_meta_keywords : '')

@section('styles')
<style>
  /* Disable the default page breadcrumb area */
  .page-title-area {
      display: none !important;
  }
  
  /* Force uniform height on all text inputs under input-wrapper */
  .form-group-custom .input-wrapper .form-control {
      height: 42px !important;
  }

  /* Make sure icons are positioned above the input backgrounds */
  .form-group-custom .input-wrapper .icon {
      z-index: 2 !important;
  }

    .contact-phone-symbol {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #ff5a2c;
      font-size: 20px;
      line-height: 1;
      font-weight: 700;
    }

    .form-group-custom .input-wrapper .contact-phone-symbol {
      width: 18px;
      height: 18px;
      font-size: 18px;
      z-index: 2 !important;
    }

  /* Fix nice-select styling inside custom form fields on contact page */
  .form-group-custom .input-wrapper .nice-select.form-control {
      width: 100% !important;
      height: 42px !important;
      line-height: 40px !important;
      padding-left: 30px !important;
      padding-right: 30px !important;
      text-align: center !important;
      background: #ffffff !important;
      border-radius: 8px !important;
      border: 1px solid #cbd5e1 !important;
      font-size: 13px !important;
      color: #1e2335 !important;
      font-weight: 500 !important;
      float: none !important;
      display: inline-block !important;
  }
  .mb-5 {
    margin-bottom: 3rem !important;
    margin-top: 60px;
}

  .form-group-custom .input-wrapper .nice-select.form-control:after {
      right: 18px !important;
      border-color: #64748b !important;
      margin-top: -4px !important;
  }

  .form-group-custom .input-wrapper .nice-select.form-control .current {
      display: block !important;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      text-align: center !important;
  }

  /* Nice select placeholder visual treatment */
  .form-group-custom .input-wrapper .nice-select.form-control.placeholder-selected .current {
      color: #94a3b8 !important;
  }

  /* Add focus styles to nice-select to match text fields */
  .form-group-custom .input-wrapper .nice-select.form-control:focus,
  .form-group-custom .input-wrapper .nice-select.form-control.open {
      border-color: #ff5a2c !important;
      box-shadow: 0 0 0 3px rgba(255, 90, 44, 0.1) !important;
  }

  .form-group-custom .input-wrapper .nice-select .list {
      width: 100% !important;
      border-radius: 8px !important;
      border-color: #cbd5e1 !important;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
  }

  /* ===================================================
     MOBILE RESPONSIVE FIXES — OVERFLOW & LAYOUT
  =================================================== */

  /* ── Critical: kill right-side gap caused by overflowing elements ── */
  html, body {
      overflow-x: hidden !important;
      max-width: 100% !important;
  }
  .modern-contact-page-wrapper,
  .modern-contact-page-wrapper .container,
  .modern-contact-page-wrapper .row {
      overflow-x: hidden !important;
  }

  /* ── Hero row spacing ── */
  .contact-hero-row {
      margin-bottom: 48px !important;
  }

  /* ── Hide the CSS device collage on mobile (it overflows viewport) ── */
  .contact-device-collage-wrapper {
      position: relative;
      overflow: hidden;
  }

  /* ── Mobile collage: show a clean feature card instead of the collage ── */
  .contact-mobile-feature-card {
      display: none;
  }
  .contact-split-row{
        margin-top: 1.2rem;
  }

  @media (max-width: 991.98px) {
      /* Text alignment */
      .contact-hero-row {
          margin-bottom: 28px !important;
          text-align: center;
      }
      .contact-trust-pill-banner {
          justify-content: center;
          display: flex;
      }
      .contact-hero-title {
          font-size: 26px !important;
          line-height: 1.3 !important;
      }
      .contact-hero-desc {
          font-size: 14px !important;
      }

        .contact-split-row{
        margin-top: 0;
  }
      /* Hide the complex CSS device collage — it causes horizontal overflow */
      .contact-device-collage-wrapper {
          display: none !important;
      }

      /* Show the mobile-friendly replacement card */
      .contact-mobile-feature-card {
          display: flex !important;
          align-items: center;
          justify-content: center;
          flex-wrap: wrap;
          gap: 12px;
          /* background: linear-gradient(135deg, #0e1b3d 0%, #1a3060 80%, #ff5a2c 200%); */
          border-radius: 18px;
          padding: 28px 20px;
          margin: 0 auto 0px;
          max-width: 460px;
          text-align: center;
          position: relative;
          overflow: hidden;
          padding-bottom: 0px;
      }
      .contact-mobile-feature-card::before {
          content: '';
          position: absolute;
          inset: 0;
          background-image: radial-gradient(rgba(255,255,255,0.05) 1.5px, transparent 1.5px);
          background-size: 22px 22px;
          pointer-events: none;
      }
      .contact-mfc-item {
          display: flex;
          flex-direction: column;
          align-items: center;
          gap: 6px;
          width: calc(50% - 6px);
          position: relative;
          z-index: 1;
      }
      .contact-mfc-icon {
          width: 46px; height: 46px;
          background: rgb(183 0 0 / 12%);
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 18px;
          color: #ff5a2c;
          border: 1px solid rgba(255,255,255,0.15);
      }
      .contact-mfc-label {
          font-size: 12px;
          font-weight: 700;
          color: rgba(0, 0, 0, 0.9);
          line-height: 1.3;
      }
  }

  /* ── Info cards on mobile ── */
  @media (max-width: 575.98px) {
      .contact-info-card {
          flex-direction: row !important;
          align-items: flex-start !important;
          text-align: left !important;
          gap: 14px !important;
          padding: 14px !important;
      }
      .contact-info-card .icon-circle {
          flex-shrink: 0;
          width: 42px !important;
          height: 42px !important;
          font-size: 17px !important;
      }
  }
  @media (max-width: 767.98px) {
      .contact-info-card {
          margin-bottom: 14px;
      }
  }

  /* ── Contact split row (form + support card) ── */
  @media (max-width: 991.98px) {
      .contact-split-row {
          flex-direction: column;
      }
      .product-preview-card {
          padding: 20px !important;
          height: auto !important;
      }
      .gif-wrapper {
          max-height: 200px;
          overflow: hidden;
      }
      .gif-wrapper img {
          max-height: 200px;
          object-fit: cover;
      }
  }

  /* ── Highlights banner ── */
  @media (max-width: 767.98px) {
      .contact-highlights-banner {
          flex-wrap: wrap !important;
          gap: 12px !important;
          padding: 20px 16px !important;
      }
      .highlight-item {
          width: calc(50% - 6px) !important;
          min-width: unset !important;
      }
  }
  @media (max-width: 480px) {
      .highlight-item {
          width: 100% !important;
      }
  }

  /* ── Form card padding ── */
  @media (max-width: 575.98px) {
      .contact-form-card {
          padding: 20px 14px !important;
          border-radius: 14px !important;
      }
      .form-title {
          font-size: 20px !important;
      }
  }

  /* ── Page wrapper padding ── */
  @media (max-width: 767.98px) {
      .modern-contact-page-wrapper {
          padding-top: 20px !important;
          padding-bottom: 0px !important;
      }
  }

  /* ── Extra-large screens (≥ 1600px) ── */
  @media (min-width: 1600px) {
      .contact-hero-row {
          padding-top: 33px;
      }
      .modern-contact-page-wrapper {
          padding-top: 51px;
      }
  }

</style>
@endsection

@section('content')

  <!-- Modern Contact Page Wrapper -->
  <div class="modern-contact-page-wrapper pt-40 pb-120">
    <div class="container">

      <!-- Subtle Breadcrumbs -->
      <!-- <nav aria-label="breadcrumb" class="custom-contact-breadcrumb mb-4" data-aos="fade-down">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $pageHeading ?? __('Contact') }}</li>
        </ol>
      </nav> -->

      <!-- Hero Header Section -->
      <div class="row align-items-center   contact-hero-row" >
        <div class="col-lg-6 mb-5 mb-lg-0" style="margin-bottom: 0rem !important;">
          <div class="contact-hero-content" data-aos="fade-right">
            <h1 class="contact-hero-title">
              Let's Talk About <br><span>Your Store Launch</span>
            </h1>
            <p class="contact-hero-desc">
              Have a question or need expert guidance? We're here to help you build, launch, and grow your online store with Launchshop.in.
            </p>
            <div class="contact-trust-pill-banner">
              <span class="trust-pill"><i class="fas fa-heart text-orange"></i> Trusted by 5,000+ merchants across India</span>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6">
          {{-- Mobile-only feature card (shown when device collage is hidden on mobile) --}}
          <div class="contact-mobile-feature-card">
            <div class="contact-mfc-item">
              <div class="contact-mfc-icon"><i class="fas fa-bolt"></i></div>
              <span class="contact-mfc-label">Quick Response</span>
            </div>
            <div class="contact-mfc-item">
              <div class="contact-mfc-icon"><i class="fas fa-shield-alt"></i></div>
              <span class="contact-mfc-label">Secure & Private</span>
            </div>
            <div class="contact-mfc-item">
              <div class="contact-mfc-icon"><i class="fas fa-headset"></i></div>
              <span class="contact-mfc-label">24/7 Support</span>
            </div>
            <div class="contact-mfc-item">
              <div class="contact-mfc-icon"><i class="fas fa-rocket"></i></div>
              <span class="contact-mfc-label">Launch Ready</span>
            </div>
          </div>
          <div class="contact-device-collage-wrapper" data-aos="fade-left">
            <div class="collage-bg-glow"></div>
            <!-- Interactive Live Device Collage -->
            <div class="css-device-collage">
              <!-- Laptop Mockup -->
              <div class="collage-laptop">
                <div class="laptop-screen">
                  <div class="screen-dashboard-preview">
                    <div class="bar-chart-preview">
                      <span style="height: 40%"></span>
                      <span style="height: 65%"></span>
                      <span style="height: 50%"></span>
                      <span style="height: 85%"></span>
                      <span style="height: 70%"></span>
                    </div>
                    <div class="store-dashboard-header">
                      <div class="circ"></div><div class="line"></div>
                    </div>
                  </div>
                </div>
                <div class="laptop-base"></div>
              </div>
              
              <!-- Mobile Phone Mockup -->
              <div class="collage-phone">
                <div class="phone-screen">
                  <div class="phone-checkout-preview">
                    <span class="chk-icon"><i class="fas fa-shopping-cart"></i></span>
                    <span class="chk-bar"></span>
                    <span class="chk-bar short"></span>
                    <button class="chk-btn">Checkout</button>
                  </div>
                </div>
              </div>
              
              <!-- Shopping Bag Mockup -->
              <div class="collage-bag">
                <div class="bag-body">
                  <i class="fas fa-shopping-bag"></i>
                  <span class="bag-logo">Launch</span>
                </div>
                <div class="bag-handle"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Cards Row -->
      <div class="row g-4 mb-80 justify-content-center" data-aos="fade-up">
        @php
          $phones = explode(',', str_replace('6374913298', '72007 70351', $be->contact_numbers ?? '72007 70351'));
          $mails = explode(',', $be->contact_mails);
          $addresses = explode(PHP_EOL, $be->contact_addresses);
        @endphp
        
        <!-- Phone Card -->
        <div class="col-md-6 col-lg-3">
          <div class="contact-info-card">
            <div class="icon-circle phone">
              <span class="contact-phone-symbol" aria-hidden="true">☎</span>
            </div>
            <div class="card-details">
              <h4>{{ __('Phone') }}</h4>
              @foreach ($phones as $phone)
                <p><a href="tel:{{ trim($phone) }}">{{ trim($phone) }}</a></p>
              @endforeach
              <span class="sub-label">Mon–Sat, 9:30 AM – 6:30 PM</span>
            </div>
          </div>
        </div>
        
        <!-- Email Card -->
        <div class="col-md-6 col-lg-3">
          <div class="contact-info-card">
            <div class="icon-circle email">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="card-details">
              <h4>{{ __('Email') }}</h4>
              @foreach ($mails as $mail)
                <p><a href="mailto:{{ trim($mail) }}">{{ trim($mail) }}</a></p>
              @endforeach
              <span class="sub-label">We reply within a few hours</span>
            </div>
          </div>
        </div>
        
        <!-- Address Card -->
        <div class="col-md-6 col-lg-3">
          <div class="contact-info-card">
            <div class="icon-circle address">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="card-details">
              <h4>{{ __('Office Address') }}</h4>
              @foreach ($addresses as $address)
                <p class="address-text">{{ trim($address) }}</p>
              @endforeach
              <span class="sub-label">India</span>
            </div>
          </div>
        </div>
        
        <!-- Technical Support Card -->
        <div class="col-md-6 col-lg-3">
          <div class="contact-info-card">
            <div class="icon-circle support">
              <i class="fas fa-headset"></i>
            </div>
            <div class="card-details">
              <h4>{{ __('Technical Support') }}</h4>
              <p>Mon – Sat: 9:30 AM – 6:30 PM</p>
              <p>Sunday: Closed</p>
              <span class="sub-label">(IST)</span>
            </div>
          </div>
        </div>
      </div>
<!-- <br><br> -->
      <!-- Split section: Form & Calendar Booking -->
      <div class="row g-5 contact-split-row" >
        
        <!-- Contact Message Form -->
        <div class="col-lg-7" style="margin-top: 0px;">
          <div class="contact-form-card" data-aos="fade-right">
            <h3 class="form-title">Send Us a Message</h3>
            <p class="form-subtitle">Fill out the form and our team will get back to you shortly.</p>
            
            @if (Session::has('success'))
              <div class="alert alert-success mb-3">{{ Session::get('success') }}</div>
            @endif
            
            <form id="contactForm" action="{{ route('front.admin.contact.message') }}" method="post" enctype="multipart/form-data">
              @csrf
              
              <!-- Real fields hidden mapping helper -->
              <input type="hidden" name="message" id="message">
              
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="form-group-custom">
                    <label>Full Name *</label>
                    <div class="input-wrapper">
                      <i class="far fa-user icon"></i>
                      <input type="text" name="name" class="form-control" placeholder="Enter your full name" required value="{{ old('name') }}">
                    </div>
                    @if ($errors->has('name'))
                      <span class="text-danger font-sm">{{ $errors->first('name') }}</span>
                    @endif
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group-custom">
                    <label>Business Email *</label>
                    <div class="input-wrapper">
                      <i class="far fa-envelope icon"></i>
                      <input type="email" name="email" class="form-control" placeholder="you@yourstore.com" required value="{{ old('email') }}">
                    </div>
                    @if ($errors->has('email'))
                      <span class="text-danger font-sm">{{ $errors->first('email') }}</span>
                    @endif
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group-custom">
                    <label>Phone Number *</label>
                    <div class="input-wrapper">
                      <span class="contact-phone-symbol icon" aria-hidden="true">☎</span>
                      <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group-custom">
                    <label>Describe Issues *</label>
                    <div class="input-wrapper">
                      <i class="far fa-clipboard-list icon"></i>
                      <input type="text" name="describe_issues" class="form-control" placeholder="Describe your issues" required>
                    </div>
                  </div>
                </div>
                
                <div class="col-12">
                  <div class="form-group-custom">
                    <label>Subject *</label>
                    <div class="input-wrapper">
                      <i class="far fa-keyboard icon"></i>
                      <input type="text" name="subject" class="form-control" placeholder="How can we help you?" required value="{{ old('subject') }}">
                    </div>
                    @if ($errors->has('subject'))
                      <span class="text-danger font-sm">{{ $errors->first('subject') }}</span>
                    @endif
                  </div>
                </div>
                
                <div class="col-12">
                  <div class="form-group-custom">
                    <label>Message *</label>
                    <textarea id="temp_message" class="form-control textarea-custom" rows="6" placeholder="Tell us about your requirements..." required></textarea>
                    @if ($errors->has('message'))
                      <span class="text-danger font-sm">{{ $errors->first('message') }}</span>
                    @endif
                  </div>
                </div>
                
                <div class="col-12">
                  @if ($recaptchaInfo->is_recaptcha == 1)
                    <div class="d-block mb-3">
                      {!! NoCaptcha::renderJs() !!}
                      {!! NoCaptcha::display() !!}
                      @if ($errors->has('g-recaptcha-response'))
                        <span class="text-danger font-sm d-block mt-2">{{ __($errors->first('g-recaptcha-response')) }}</span>
                      @endif
                    </div>
                  @endif
                </div>
                
                <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
                  <button type="submit" class="btn btn-orange-filled px-4 py-2">
                    Send Message <i class="fas fa-arrow-right ms-2"></i>
                  </button>
                  <span class="notice-text text-muted"><i class="fas fa-shield-alt text-success me-1"></i> Your information is safe with us.</span>
                </div>
              </div>
            </form>
          </div>
        </div>
        
        <!-- Online Support Agent Card -->
        <div class="col-lg-5" style="margin-top: 20px;">
          <div class="product-preview-card text-center" data-aos="fade-left" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.01); height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <div class="gif-wrapper mb-4" style="width: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;">
              <img src="{{ asset('assets/front/img/support-agent.png') }}" alt="Support Agent Online" style="width: 100%; height: auto; display: block; object-fit: cover;">
            </div>
            <h3 class="product-preview-title" style="font-size: 20px; font-weight: 800; color: #1e2335; margin-bottom: 8px;">Our Support Team is Online</h3>
            <p class="product-preview-subtitle" style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 0;">Have any questions or facing technical issues? Message us or start a chat. Real support, real people.</p>
            <a href="https://wa.me/917200770351?text=Hi,%20I%20want%20to%20enquire%20about%20the%20LaunchShop.%20Please%20help%20me%20get%20started." target="_blank" class="btn-whatsapp-chat-page mt-4 d-inline-flex align-items-center justify-content-center gap-2">
              <i class="fab fa-whatsapp"></i> Chat With Us
            </a>
          </div>
        </div>
      </div>
<!-- <br><br> -->
      <!-- Trust Factors Highlights -->
      <div class="contact-highlights-banner " data-aos="fade-up">
        <div class="highlight-item">
          <div class="icon-box"><i class="fas fa-bolt"></i></div>
          <div class="text-box">
            <strong>Quick Response</strong>
            <span>We typically reply within a few hours</span>
          </div>
        </div>
        <div class="highlight-item">
          <div class="icon-box"><i class="far fa-shield-check"></i></div>
          <div class="text-box">
            <strong>Secure & Private</strong>
            <span>Your details are 100% protected</span>
          </div>
        </div>
        <div class="highlight-item">
          <div class="icon-box"><i class="far fa-heart"></i></div>
          <div class="text-box">
            <strong>Here to Help</strong>
            <span>Real people. Real support.</span>
          </div>
        </div>
        <div class="highlight-item">
          <div class="icon-box"><i class="fas fa-headset"></i></div>
          <div class="text-box">
            <strong>Technical Support</strong>
            <span>24/7 dedicated assistance</span>
          </div>
        </div>
      </div>

      <!-- How We Help You Succeed cards -->
      <!-- <div class="success-help-section mt-100" data-aos="fade-up">
        <h2 class="section-title text-center">How We Help You Succeed</h2>
        <p class="section-subtitle text-center mb-5">From idea to launch and beyond – we're with you at every step.</p>
        
        <div class="row g-4">
          <div class="col-md-6 col-lg-3">
            <div class="success-help-card">
              <div class="icon-box orange"><i class="fas fa-shopping-bag"></i></div>
              <h4>Sales & Onboarding</h4>
              <p>Get expert advice on plans, features and finding the right solution for your business.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="success-help-card">
              <div class="icon-box blue"><i class="fas fa-palette"></i></div>
              <h4>Theme & Design Guidance</h4>
              <p>Need help choosing or customizing a theme? Our design guides have got you covered.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="success-help-card">
              <div class="icon-box red"><i class="fas fa-rocket"></i></div>
              <h4>Store Setup Assistance</h4>
              <p>We help you configure store settings, add products, and link payment checkouts.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="success-help-card">
              <div class="icon-box green"><i class="fas fa-headset"></i></div>
              <h4>Technical Support</h4>
              <p>Facing an issue? Our dedicated support team is always standing by to assist.</p>
            </div>
          </div>
        </div>
      </div> -->

    </div>
  </div>

@endsection

@section('scripts')
<script>
  $(document).ready(function() {
      // Form submit hook: merge phone and store type details into the hidden message body
      $('#contactForm').on('submit', function(e) {
          var phone = $('[name="phone"]').val();
        var describeIssues = $('[name="describe_issues"]').val();
          var originalMsg = $('#temp_message').val();
          
          var additionalInfo = "";
          if (phone) {
              additionalInfo += "\nPhone Number: " + phone;
          }
        if (describeIssues) {
          additionalInfo += "\nDescribe Issues: " + describeIssues;
          }
          
          if (additionalInfo) {
              $('#message').val(originalMsg + "\n\n--- Additional Information ---" + additionalInfo);
          } else {
              $('#message').val(originalMsg);
          }
      });
      
  });
</script>
@endsection
