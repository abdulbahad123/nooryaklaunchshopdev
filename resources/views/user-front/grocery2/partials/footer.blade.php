<!-- Footer Start -->
<footer class="grocery2-footer">
  <!-- 5-Feature Trust Badges Bar -->
  <div class="grocery2-trust-badges py-4">
    <div class="container">
      <div class="row g-3">
        <div class="col-lg-2-4 col-md-4 col-sm-6">
          <div class="g2-feature-card d-flex align-items-center gap-3 p-3 rounded-3" style="background-color: #f4f6fa; border: 1px solid #e9ecef;">
            <div class="g2-feature-icon text-primary fs-3"><i class="fal fa-paper-plane" style="color: #3b5998;"></i></div>
            <div class="g2-feature-text">
              <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">Free Delivery</h6>
              <small class="text-muted" style="font-size: 11px;">From all orders over $10</small>
            </div>
          </div>
        </div>
        <div class="col-lg-2-4 col-md-4 col-sm-6">
          <div class="g2-feature-card d-flex align-items-center gap-3 p-3 rounded-3" style="background-color: #f4f6fa; border: 1px solid #e9ecef;">
            <div class="g2-feature-icon text-primary fs-3"><i class="fal fa-paper-plane" style="color: #3b5998;"></i></div>
            <div class="g2-feature-text">
              <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">Free Delivery</h6>
              <small class="text-muted" style="font-size: 11px;">From all orders over $10</small>
            </div>
          </div>
        </div>
        <div class="col-lg-2-4 col-md-4 col-sm-6">
          <div class="g2-feature-card d-flex align-items-center gap-3 p-3 rounded-3" style="background-color: #f4f6fa; border: 1px solid #e9ecef;">
            <div class="g2-feature-icon text-primary fs-3"><i class="fal fa-paper-plane" style="color: #3b5998;"></i></div>
            <div class="g2-feature-text">
              <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">Free Delivery</h6>
              <small class="text-muted" style="font-size: 11px;">From all orders over $10</small>
            </div>
          </div>
        </div>
        <div class="col-lg-2-4 col-md-4 col-sm-6">
          <div class="g2-feature-card d-flex align-items-center gap-3 p-3 rounded-3" style="background-color: #f4f6fa; border: 1px solid #e9ecef;">
            <div class="g2-feature-icon text-primary fs-3"><i class="fal fa-paper-plane" style="color: #3b5998;"></i></div>
            <div class="g2-feature-text">
              <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">Free Delivery</h6>
              <small class="text-muted" style="font-size: 11px;">From all orders over $10</small>
            </div>
          </div>
        </div>
        <div class="col-lg-2-4 col-md-4 col-sm-6">
          <div class="g2-feature-card d-flex align-items-center gap-3 p-3 rounded-3" style="background-color: #f4f6fa; border: 1px solid #e9ecef;">
            <div class="g2-feature-icon text-primary fs-3"><i class="fal fa-paper-plane" style="color: #3b5998;"></i></div>
            <div class="g2-feature-text">
              <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">Free Delivery</h6>
              <small class="text-muted" style="font-size: 11px;">From all orders over $10</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Main Widgets -->
  <div class="grocery2-footer-widgets py-5">
    <div class="container">
      <div class="row g-4">
        <!-- Widget 1: About -->
        <div class="col-lg-3 col-md-6">
          <div class="grocery2-footer-widget about-widget">
            @php
              $phone = !empty(@$userBs->contact_number) ? @$userBs->contact_number : (!empty(@$user->phone) ? @$user->phone : (!empty(@$userFooter->phone) ? @$userFooter->phone : '+1800900122'));
              $email = !empty(@$userBs->email) ? @$userBs->email : (!empty(@$user->email) ? @$user->email : (!empty(@$userFooter->email) ? @$userFooter->email : 'support@gmail.com'));
              $address = !empty(@$userBs->address) ? @$userBs->address : (!empty(@$user->address) ? @$user->address : (!empty(@$userFooter->address) ? @$userFooter->address : 'H-34, R-03, S-11'));
              $about_company = !empty(@$userFooter->footer_text) ? @$userFooter->footer_text : 'Awesome grocery store website template';
              $copyright_text = !empty(@$userFooter->copyright_text) ? @$userFooter->copyright_text : 'Copyright © ' . date('Y') . ' ' . ($user->shop_name ?? 'Ecom Grocery') . '. All rights reserved.';
            @endphp
            <h3 class="widget-title mb-4">About Company</h3>
            <p class="company-desc text-muted mb-3" style="font-size: 13px;">{!! replaceBaseUrl($about_company) !!}</p>
            <ul class="contact-info-list list-unstyled m-0 p-0" style="font-size: 13px;">
              @if(!empty($address))
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="fas fa-map-marker-alt mt-1" style="color: #ff5e14;"></i>
                  <span>{{ $address }}</span>
                </li>
              @endif
              @if(!empty($phone))
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="fas fa-phone-alt mt-1" style="color: #ff5e14;"></i>
                  <span><strong>Need help? Call Us:</strong> <a href="tel:{{ $phone }}" class="text-decoration-none text-muted">{{ $phone }}</a></span>
                </li>
              @endif
              @if(!empty($email))
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="fas fa-envelope mt-1" style="color: #ff5e14;"></i>
                  <span><strong>Just Mail Us:</strong> <a href="mailto:{{ $email }}" class="text-decoration-none text-muted">{{ $email }}</a></span>
                </li>
              @endif
              <li class="d-flex align-items-start gap-2 mb-2">
                <i class="fas fa-clock mt-1" style="color: #ff5e14;"></i>
                <span><strong>Hours :</strong> 10:00 - 18:00, Mon - Sat</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Widget 2: Company -->
        <div class="col-lg-2 col-md-6">
          <div class="grocery2-footer-widget link-widget">
            <h3 class="widget-title mb-4">Company</h3>
            <ul class="widget-links list-unstyled m-0 p-0" style="font-size: 13px; line-height: 2;">
              <li><a href="{{ route('front.user.about', getParam()) }}" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>About Us</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Delivery Information</a></li>
              <li><a href="{{ route('front.user.privacy_policy', getParam()) }}" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Privacy Policy</a></li>
              <li><a href="{{ route('front.user.terms_conditions', getParam()) }}" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Terms & Conditions</a></li>
              <li><a href="{{ route('front.user.contact', getParam()) }}" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Contact Us</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Support Center</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Careers</a></li>
            </ul>
          </div>
        </div>

        <!-- Widget 3: Corporate -->
        <div class="col-lg-2 col-md-6">
          <div class="grocery2-footer-widget link-widget">
            <h3 class="widget-title mb-4">Corporate</h3>
            <ul class="widget-links list-unstyled m-0 p-0" style="font-size: 13px; line-height: 2;">
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Become a Vendor</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Affiliate Program</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Farm Business</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Farm Careers</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Our Suppliers</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Accessibility</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Promotions</a></li>
            </ul>
          </div>
        </div>

        <!-- Widget 4: Popular -->
        <div class="col-lg-2 col-md-6">
          <div class="grocery2-footer-widget link-widget">
            <h3 class="widget-title mb-4">Popular</h3>
            <ul class="widget-links list-unstyled m-0 p-0" style="font-size: 13px; line-height: 2;">
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Milk & Flavoured Milk</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Butter and Margarine</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Eggs Substitutes</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Marmalades</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Sour Cream and Dips</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Tea & Kombucha</a></li>
              <li><a href="#" class="text-decoration-none text-dark"><i class="fas fa-caret-right me-2" style="color: #ff5e14;"></i>Cheese</a></li>
            </ul>
          </div>
        </div>

        <!-- Widget 5: Install App -->
        <div class="col-lg-3 col-md-6">
          <div class="grocery2-footer-widget app-widget">
            <h3 class="widget-title mb-4">Install App</h3>
            <p class="text-muted mb-3" style="font-size: 13px;">From App Store or Google Play</p>
            <div class="app-badges d-flex gap-2 mb-4">
              <a href="#" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 rounded">
                <i class="fab fa-google-play fa-lg text-warning"></i>
                <div class="text-start lh-1">
                  <small style="font-size: 8px; display: block;" class="text-uppercase text-white-50">GET IT ON</small>
                  <strong style="font-size: 12px;" class="text-white">Google Play</strong>
                </div>
              </a>
              <a href="#" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 rounded">
                <i class="fab fa-apple fa-lg text-white"></i>
                <div class="text-start lh-1">
                  <small style="font-size: 8px; display: block;" class="text-uppercase text-white-50">Download on</small>
                  <strong style="font-size: 12px;" class="text-white">App Store</strong>
                </div>
              </a>
            </div>
            <p class="payment-title text-muted mb-2" style="font-size: 13px;">From App Store or Google Play</p>
            <div class="payment-methods d-flex align-items-center gap-2 fs-3">
              <span class="badge bg-primary text-white font-monospace px-2 py-1" style="font-size: 12px; letter-spacing: 1px;">VISA</span>
              <span class="badge bg-danger text-white font-monospace px-2 py-1" style="font-size: 12px; letter-spacing: 1px;">MasterCard</span>
              <span class="badge bg-info text-dark font-monospace px-2 py-1" style="font-size: 12px; letter-spacing: 1px;">Maestro</span>
              <span class="badge bg-secondary text-white font-monospace px-2 py-1" style="font-size: 12px; letter-spacing: 1px;">AMEX</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Copyright Bar -->
  <div class="grocery2-copyright-bar py-3" style="border-top: 1px solid #e9ecef; background-color: #ffffff;">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-4 text-center text-md-start mb-2 mb-md-0">
          <span class="text-muted" style="font-size: 13px;">{!! replaceBaseUrl($copyright_text) !!}</span>
        </div>
        <div class="col-md-4 text-center mb-2 mb-md-0">
          <ul class="copyright-links list-inline m-0 p-0" style="font-size: 13px;">
            <li class="list-inline-item"><a href="{{ route('front.user.terms_conditions', getParam()) }}" class="text-decoration-none text-muted">Conditions of Use</a></li>
            <li class="list-inline-item text-muted">|</li>
            <li class="list-inline-item"><a href="{{ route('front.user.privacy_policy', getParam()) }}" class="text-decoration-none text-muted">Privacy Notice</a></li>
            <li class="list-inline-item text-muted">|</li>
            <li class="list-inline-item"><a href="#" class="text-decoration-none text-muted">Interest-Based Ads</a></li>
          </ul>
        </div>
        <div class="col-md-4 text-center text-md-end d-flex align-items-center justify-content-center justify-content-md-end gap-3">
          <div class="social-links d-flex gap-2">
            @if(isset($userSocials) && count($userSocials) > 0)
              @foreach($userSocials as $social)
                <a href="{{ $social->url }}" target="_blank" class="btn btn-sm btn-light rounded-circle text-secondary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="{{ $social->icon }}"></i></a>
              @endforeach
            @else
              <a href="#" class="btn btn-sm btn-light rounded-circle text-secondary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="btn btn-sm btn-light rounded-circle text-secondary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fab fa-instagram"></i></a>
              <a href="#" class="btn btn-sm btn-light rounded-circle text-secondary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fab fa-linkedin-in"></i></a>
              <a href="#" class="btn btn-sm btn-light rounded-circle text-secondary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fab fa-twitter"></i></a>
              <a href="#" class="btn btn-sm btn-light rounded-circle text-secondary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fab fa-youtube"></i></a>
            @endif
          </div>
          <!-- Scroll to top button -->
          <a href="#" class="btn btn-primary rounded-circle text-white d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: #3b5998; border: none;" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;"><i class="fas fa-arrow-up"></i></a>
        </div>
      </div>
    </div>
  </div>
</footer>

<div class="mobile-menu-overlay"></div>
<div class="mobile-menu">
  <div class="mobile-menu-wrapper">
    <div class="mobile-menu-top">
      <div class="logo">
        <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
          @if(!empty(@$userBs->logo))
            <img src="{{ asset('assets/front/img/user/' . $userBs->logo) }}" alt="logo">
          @else
            <span>Easen</span>
          @endif
        </a>
      </div>
      <span class="mobile-menu-close"><i class="fal fa-times"></i></span>
    </div>
  </div>
  @includeIf('user-front.partials.mobile-menu')
</div>
