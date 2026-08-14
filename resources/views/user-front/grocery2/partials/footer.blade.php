<!-- Footer Start -->
<footer class="grocery2-footer">
  <!-- Trust Badges Section -->
  <div class="grocery2-trust-badges">
    <div class="container">
      <div class="grocery2-trust-wrapper">
        <div class="grocery2-trust-item">
          <div class="grocery2-trust-icon"><i class="fal fa-shipping-fast"></i></div>
          <div class="grocery2-trust-text">
            <h4>Free Delivery</h4>
            <p>From all orders over $10</p>
          </div>
        </div>
        <div class="grocery2-trust-item">
          <div class="grocery2-trust-icon"><i class="fal fa-redo"></i></div>
          <div class="grocery2-trust-text">
            <h4>Easy Returns</h4>
            <p>100% money back guarantee</p>
          </div>
        </div>
        <div class="grocery2-trust-item">
          <div class="grocery2-trust-icon"><i class="fal fa-percentage"></i></div>
          <div class="grocery2-trust-text">
            <h4>Great Daily Deals</h4>
            <p>When you sign up</p>
          </div>
        </div>
        <div class="grocery2-trust-item">
          <div class="grocery2-trust-icon"><i class="fal fa-headset"></i></div>
          <div class="grocery2-trust-text">
            <h4>24/7 Support</h4>
            <p>Dedicated support anytime</p>
          </div>
        </div>
        <div class="grocery2-trust-item">
          <div class="grocery2-trust-icon"><i class="fal fa-shield-check"></i></div>
          <div class="grocery2-trust-text">
            <h4>Secure Payments</h4>
            <p>100% protected payments</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Main Widgets -->
  <div class="grocery2-footer-widgets">
    <div class="container">
      <div class="row">
        <!-- Widget 1: About -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="grocery2-footer-widget about-widget">
            <h3 class="widget-title">About Company</h3>
            <p class="company-desc">Awesome grocery store website template.</p>
            @php
              $phone = !empty(@$userBs->contact_number) ? @$userBs->contact_number : (!empty(@$user->phone) ? @$user->phone : '');
              $email = !empty(@$userBs->email) ? @$userBs->email : (!empty(@$user->email) ? @$user->email : '');
              $address = !empty(@$userBs->address) ? @$userBs->address : (!empty(@$user->address) ? @$user->address : '');
            @endphp
            <ul class="contact-info-list">
              @if(!empty($address))
                <li>
                  <i class="fal fa-map-marker-alt"></i>
                  <span>{{ $address }}</span>
                </li>
              @endif
              @if(!empty($phone))
                <li>
                  <i class="fal fa-phone-alt"></i>
                  <span><strong>Need help? Call us:</strong> <a href="tel:{{ $phone }}">{{ $phone }}</a></span>
                </li>
              @endif
              @if(!empty($email))
                <li>
                  <i class="fal fa-envelope"></i>
                  <span><strong>Email:</strong> <a href="mailto:{{ $email }}">{{ $email }}</a></span>
                </li>
              @endif
              <li>
                <i class="fal fa-clock"></i>
                <span><strong>Hours:</strong> 10:00 - 18:00, Mon - Sat</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Widget 2: Company -->
        <div class="col-lg-2 col-md-6 mb-4">
          <div class="grocery2-footer-widget link-widget">
            <h3 class="widget-title">Company</h3>
            <ul class="widget-links">
              <li><a href="{{ route('front.user.about', getParam()) }}">About Us</a></li>
              <li><a href="#">Delivery Information</a></li>
              <li><a href="{{ route('front.user.privacy_policy', getParam()) }}">Privacy Policy</a></li>
              <li><a href="{{ route('front.user.terms_conditions', getParam()) }}">Terms & Conditions</a></li>
              <li><a href="{{ route('front.user.contact', getParam()) }}">Contact Us</a></li>
              <li><a href="#">Support Center</a></li>
              <li><a href="#">Careers</a></li>
            </ul>
          </div>
        </div>

        <!-- Widget 3: Corporate -->
        <div class="col-lg-2 col-md-6 mb-4">
          <div class="grocery2-footer-widget link-widget">
            <h3 class="widget-title">Corporate</h3>
            <ul class="widget-links">
              <li><a href="#">Become a Vendor</a></li>
              <li><a href="#">Affiliate Program</a></li>
              <li><a href="#">Farm Business</a></li>
              <li><a href="#">Farm Careers</a></li>
              <li><a href="#">Our Suppliers</a></li>
              <li><a href="#">Accessibility</a></li>
              <li><a href="#">Promotions</a></li>
            </ul>
          </div>
        </div>

        <!-- Widget 4: Popular -->
        <div class="col-lg-2 col-md-6 mb-4">
          <div class="grocery2-footer-widget link-widget">
            <h3 class="widget-title">Popular</h3>
            <ul class="widget-links">
              <li><a href="#">Milk & Flavoured Milk</a></li>
              <li><a href="#">Butter and Margarine</a></li>
              <li><a href="#">Egg Substitutes</a></li>
              <li><a href="#">Marmalades</a></li>
              <li><a href="#">Sour Cream and Dips</a></li>
              <li><a href="#">Tea & Kombucha</a></li>
              <li><a href="#">Cheese</a></li>
            </ul>
          </div>
        </div>

        <!-- Widget 5: Install App -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="grocery2-footer-widget app-widget">
            <h3 class="widget-title">Install App</h3>
            <p>From App Store or Google Play</p>
            <div class="app-badges d-flex gap-2 mb-3">
              <a href="#" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 rounded">
                <i class="fab fa-apple fa-lg"></i>
                <div class="text-start lh-1">
                  <small style="font-size: 9px; display: block;">Download on</small>
                  <strong style="font-size: 12px;">App Store</strong>
                </div>
              </a>
              <a href="#" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 rounded">
                <i class="fab fa-google-play fa-lg"></i>
                <div class="text-start lh-1">
                  <small style="font-size: 9px; display: block;">GET IT ON</small>
                  <strong style="font-size: 12px;">Google Play</strong>
                </div>
              </a>
            </div>
            <p class="payment-title">Secured Payment Gateways</p>
            <div class="payment-methods d-flex align-items-center gap-3 fs-3 text-muted">
              <i class="fab fa-cc-visa" title="Visa"></i>
              <i class="fab fa-cc-mastercard" title="Mastercard"></i>
              <i class="fab fa-cc-paypal" title="PayPal"></i>
              <i class="fab fa-cc-stripe" title="Stripe"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Copyright Bar -->
  <div class="grocery2-copyright-bar">
    <div class="container">
      <div class="grocery2-copyright-wrapper">
        <div class="copyright-left">
          <span>Copyright &copy; {{ date('Y') }} {{ $user->shop_name ?? 'Ecom' }}. All rights reserved.</span>
        </div>
        <div class="copyright-center">
          <ul class="copyright-links">
            <li><a href="{{ route('front.user.privacy_policy', getParam()) }}">Privacy Policy</a></li>
            <li><a href="{{ route('front.user.terms_conditions', getParam()) }}">Terms of Use</a></li>
            <li><a href="#">Interest-Based Ads</a></li>
          </ul>
        </div>
        <div class="copyright-right">
          @if (count($social_medias) > 0)
            <div class="social-links">
              @foreach ($social_medias as $social)
                @php
                  $url = preg_match('/^https?:\/\//', $social->url) ? $social->url : 'http://' . $social->url;
                @endphp
                <a href="{{ $url }}" target="_blank"><i class="{{ $social->icon }}"></i></a>
              @endforeach
            </div>
          @endif
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
