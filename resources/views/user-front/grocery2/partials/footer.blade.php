<!-- Footer Start -->
<footer class="grocery2-footer">
  <!-- 5-Feature Trust Badges Bar -->
  <div class="grocery2-trust-badges py-4">
    <div class="container">
      <div class="row g-3">
        @php
          $user_features = [];
          if (\Illuminate\Support\Facades\Schema::hasTable('user_features')) {
              try {
                  $user_features = \App\Models\User\UserFeature::where('user_id', $user->id)
                      ->where('language_id', $userCurrentLang->id)
                      ->orderBy('serial_number', 'ASC')
                      ->get();
              } catch (\Throwable $e) {
                  $user_features = [];
              }
          }
        @endphp
        @if (count($user_features) > 0)
          @foreach ($user_features as $feature)
            <div class="col-lg-2-4 col-md-4 col-sm-6">
              <div class="g2-feature-card d-flex align-items-center gap-3 p-3 rounded-3" style="background-color: #f4f6fa; border: 1px solid #e9ecef;">
                <div class="g2-feature-icon text-primary fs-3">
                  <i class="{{ $feature->icon ?? 'fal fa-paper-plane' }}" style="color: #3b5998;"></i>
                </div>
                <div class="g2-feature-text">
                  <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">{{ $feature->title }}</h6>
                  <small class="text-muted" style="font-size: 11px;">{{ $feature->text }}</small>
                </div>
              </div>
            </div>
          @endforeach
        @else
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
              <div class="g2-feature-icon text-primary fs-3"><i class="fal fa-undo" style="color: #3b5998;"></i></div>
              <div class="g2-feature-text">
                <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">Easy Returns</h6>
                <small class="text-muted" style="font-size: 11px;">30 Days Return Policy</small>
              </div>
            </div>
          </div>
          <div class="col-lg-2-4 col-md-4 col-sm-6">
            <div class="g2-feature-card d-flex align-items-center gap-3 p-3 rounded-3" style="background-color: #f4f6fa; border: 1px solid #e9ecef;">
              <div class="g2-feature-icon text-primary fs-3"><i class="fal fa-shield-check" style="color: #3b5998;"></i></div>
              <div class="g2-feature-text">
                <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">Secure Payment</h6>
                <small class="text-muted" style="font-size: 11px;">100% Safe Checkout</small>
              </div>
            </div>
          </div>
          <div class="col-lg-2-4 col-md-4 col-sm-6">
            <div class="g2-feature-card d-flex align-items-center gap-3 p-3 rounded-3" style="background-color: #f4f6fa; border: 1px solid #e9ecef;">
              <div class="g2-feature-icon text-primary fs-3"><i class="fal fa-headset" style="color: #3b5998;"></i></div>
              <div class="g2-feature-text">
                <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">24/7 Support</h6>
                <small class="text-muted" style="font-size: 11px;">Dedicated Assistance</small>
              </div>
            </div>
          </div>
          <div class="col-lg-2-4 col-md-4 col-sm-6">
            <div class="g2-feature-card d-flex align-items-center gap-3 p-3 rounded-3" style="background-color: #f4f6fa; border: 1px solid #e9ecef;">
              <div class="g2-feature-icon text-primary fs-3"><i class="fal fa-gift" style="color: #3b5998;"></i></div>
              <div class="g2-feature-text">
                <h6 class="mb-0 fw-bold" style="color: #2c3e50; font-size: 14px;">Best Offers</h6>
                <small class="text-muted" style="font-size: 11px;">Great Discounts Daily</small>
              </div>
            </div>
          </div>
        @endif
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

              $userPages = \App\Models\User\UserPage::join('user_page_contents', 'user_pages.id', '=', 'user_page_contents.page_id')
                  ->where('user_pages.user_id', $user->id)
                  ->where('user_pages.status', 1)
                  ->where('user_page_contents.language_id', $userCurrentLang->id)
                  ->select('user_page_contents.title', 'user_page_contents.slug')
                  ->get();
            @endphp
            @if(!empty(@$footer->footer_logo))
              <div class="footer-logo mb-3">
                <a href="{{ route('front.user.detail.view', getParam()) }}">
                  <img src="{{ asset('assets/front/img/footer/' . @$footer->footer_logo) }}" alt="Logo" style="max-height: 42px; width: auto; object-fit: contain;">
                </a>
              </div>
            @elseif(!empty(@$userBs->logo))
              <div class="footer-logo mb-3">
                <a href="{{ route('front.user.detail.view', getParam()) }}">
                  <img src="{{ asset('assets/front/img/user/' . @$userBs->logo) }}" alt="Logo" style="max-height: 42px; width: auto; object-fit: contain;">
                </a>
              </div>
            @else
              <h3 class="widget-title mb-4">About Company</h3>
            @endif
            <p class="company-desc text-muted mb-3" style="font-size: 13px;">{!! replaceBaseUrl($about_company) !!}</p>
            <ul class="contact-info-list list-unstyled m-0 p-0" style="font-size: 13px;">
              @if(!empty($address))
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="fas fa-map-marker-alt mt-1" style="color: #3bb77e;"></i>
                  <span>{{ $address }}</span>
                </li>
              @endif
              @if(!empty($phone))
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="fas fa-phone-alt mt-1" style="color: #3bb77e;"></i>
                  <span><strong>Need help? Call Us:</strong> <a href="tel:{{ $phone }}" class="text-decoration-none text-muted">{{ $phone }}</a></span>
                </li>
              @endif
              @if(!empty($email))
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="fas fa-envelope mt-1" style="color: #3bb77e;"></i>
                  <span><strong>Just Mail Us:</strong> <a href="mailto:{{ $email }}" class="text-decoration-none text-muted">{{ $email }}</a></span>
                </li>
              @endif
              <li class="d-flex align-items-start gap-2 mb-2">
                <i class="fas fa-clock mt-1" style="color: #3bb77e;"></i>
                <span><strong>Hours :</strong> 10:00 - 18:00, Mon - Sat</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Widget 2: Company / Useful Links -->
        <div class="col-lg-2 col-md-6">
          <div class="grocery2-footer-widget link-widget">
            <h3 class="widget-title mb-4">{{ $footer->useful_links_title ?? ($keywords['Useful Links'] ?? __('Useful Links')) }}</h3>
            <ul class="widget-links list-unstyled m-0 p-0" style="font-size: 14px; line-height: 2;">
              @if(!empty($ulinks) && count($ulinks) > 0)
                @foreach($ulinks as $link)
                  <li><a href="{{ $link->url }}" class="text-decoration-none text-dark">{{ $link->name }}</a></li>
                @endforeach
              @else
                <li><a href="{{ route('front.user.about', getParam()) }}" class="text-decoration-none text-dark">About Us</a></li>
                <li><a href="{{ route('front.user.contact', getParam()) }}" class="text-decoration-none text-dark">Contact Us</a></li>
                <li><a href="{{ route('front.user.privacy_policy', getParam()) }}" class="text-decoration-none text-dark">Privacy Policy</a></li>
                <li><a href="{{ route('front.user.blogs', getParam()) }}" class="text-decoration-none text-dark">Blog</a></li>
              @endif
            </ul>
          </div>
        </div>

        <!-- Widget 3: Pages / Corporate -->
        <div class="col-lg-2 col-md-6">
          <div class="grocery2-footer-widget link-widget">
            <h3 class="widget-title mb-4">{{ $keywords['Pages'] ?? __('Pages') }}</h3>
            <ul class="widget-links list-unstyled m-0 p-0" style="font-size: 14px; line-height: 2;">
              @if(count($userPages) > 0)
                @foreach($userPages->take(5) as $upage)
                  <li><a href="{{ route('front.dynamicPage', [getParam(), 'slug' => $upage->slug]) }}" class="text-decoration-none text-dark">{{ $upage->title }}</a></li>
                @endforeach
              @else
                <li><a href="{{ route('front.user.privacy_policy', getParam()) }}" class="text-decoration-none text-dark">Privacy Policy</a></li>
                <li><a href="{{ route('front.user.terms_conditions', getParam()) }}" class="text-decoration-none text-dark">Terms & Conditions</a></li>
              @endif
            </ul>
          </div>
        </div>

        <!-- Widget 4: Categories / Popular -->
        <div class="col-lg-2 col-md-6">
          <div class="grocery2-footer-widget link-widget">
            <h3 class="widget-title mb-4">{{ $keywords['Categories'] ?? __('Categories') }}</h3>
            <ul class="widget-links list-unstyled m-0 p-0" style="font-size: 14px; line-height: 2;">
              @foreach($categories->take(5) as $cat)
                <li><a href="{{ route('front.user.shop', [getParam(), 'category' => $cat->slug]) }}" class="text-decoration-none text-dark">{{ $cat->name }}</a></li>
              @endforeach
            </ul>
          </div>
        </div>

        <!-- Widget 5: Install App -->
        <div class="col-lg-3 col-md-6">
          <div class="grocery2-footer-widget app-widget">
            <h3 class="widget-title mb-4">{{ $footer->subscriber_title ?? ($keywords['Install App'] ?? __('Install App')) }}</h3>
            
            <div class="get-our-app-widget my-3">
              <span class="d-block text-muted small font-weight-bold text-uppercase mb-2" style="letter-spacing: 1px; font-size: 11px; color: #888;">{{ $keywords['GET OUR APP'] ?? __('GET OUR APP') }}</span>
              <button type="button" onclick="typeof triggerPwaInstall === 'function' ? triggerPwaInstall() : alert('App installation is not supported on this device/browser');" class="btn-install-our-app d-inline-flex align-items-center px-3 py-2 border-0 shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; text-decoration: none; outline: none;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" style="margin-right: 10px; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg">
                  <path d="M3.60938 1.8125C3.3125 1.95312 3 2.25 3 2.65625V21.3438C3 21.75 3.3125 22.0469 3.60938 22.1875L13.8281 12L3.60938 1.8125Z" fill="#00D2FF"/>
                  <path d="M17.1562 8.67188L14.7188 11.1094L14.7188 12.8906L17.1562 15.3281L20.8125 13.2656C21.4688 12.8906 21.4688 12.1094 20.8125 11.7344L17.1562 8.67188Z" fill="#FFC107"/>
                  <path d="M14.7188 12.8906L13.8281 12L3.60938 22.1875C3.79688 22.2812 4.07812 22.2812 4.35938 22.1406L14.7188 16.3125L14.7188 12.8906Z" fill="#FF3D00"/>
                  <path d="M14.7188 11.1094L4.35938 5.28125C4.07812 5.14062 3.79688 5.14062 3.60938 5.23438L13.8281 12L14.7188 11.1094Z" fill="#4CAF50"/>
                </svg>
                <div style="text-align: left; line-height: 1.1;">
                  <div style="font-size: 8px; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">{{ $keywords['DOWNLOAD & INSTALL'] ?? __('DOWNLOAD & INSTALL') }}</div>
                  <div style="font-size: 13px; font-weight: 700; white-space: nowrap;">{{ $keywords['Our Mobile App'] ?? __('Our Mobile App') }}</div>
                </div>
              </button>
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
