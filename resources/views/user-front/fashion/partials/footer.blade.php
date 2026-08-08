<!-- Footer Start -->
<footer class="footer-area footer-fashion">
  <!-- Background Image -->
  <div class="bg-overlay">
    <img class=" bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
      data-src="{{ asset('assets/front/img/footer/' . @$footer->background_image) }}" alt="Bg-img">
  </div>
  <div class="footer-top pt-100 pb-70">
    <div class="container">
      <div class="row gx-xl-5">
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="footer-widget">
            <div class="navbar-brand">
              <a href="{{ route('front.user.detail.view', getParam()) }}">
                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ !is_null(@$footer->footer_logo) ? asset('assets/front/img/footer/' . @$footer->footer_logo) : asset('assets/front/img/logo.png') }}"
                  alt="Logo">
              </a>
            </div>
            <p class="text footer_description">{{ @$footer->footer_text ?? '' }}</p>
            @if (strlen(@$footer->footer_text) > 250)
              <span class="show-more-footer">{{ $keywords['Show More'] ?? __('Show More') }} +</span>
            @endif

          </div>
        </div>

        <div class=" {{ count($ulinks) <= 5 ? 'col-lg-2 col-md-3 col-sm-6' : 'col-lg-3 col-md-4 col-sm-6' }} ">
          <div class="footer-widget">
            <h3>{{ @$footer->useful_links_title ?? __('Useful Links') }}</h3>
            <ul class="footer-links">
              @foreach ($ulinks as $link)
                @if ($loop->iteration > 4)
                  @break
                @endif
                <li>
                  <a href="{{ $link->url }}">{{ $link->name }}</a>
                </li>
              @endforeach
              <li><a href="{{ route('front.user.privacy_policy', getParam()) }}">{{ $keywords['Privacy Policy'] ?? __('Privacy Policy') }}</a></li>
              <li><a href="{{ route('front.user.terms_conditions', getParam()) }}">{{ $keywords['Terms & Conditions'] ?? __('Terms & Conditions') }}</a></li>
              <li><a href="{{ route('front.user.refund_policy', getParam()) }}">{{ $keywords['Refund Policy'] ?? __('Refund Policy') }}</a></li>
              <li><a href="{{ route('front.user.shipping_policy', getParam()) }}">{{ $keywords['Shipping Policy'] ?? __('Shipping Policy') }}</a></li>
            </ul>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="footer-widget">
            <h3>{{ $keywords['Contact Us'] ?? __('Contact Us') }}</h3>
            @php
              $phone_numbers = !empty(@$userBs->contact_number) 
                ? explode(',', $userBs->contact_number) 
                : (!empty(@$user->phone) ? [$user->phone] : (!empty(@$userContact->contact_numbers) ? explode(',', $userContact->contact_numbers) : []));
              $emails = !empty(@$userBs->email) 
                ? explode(',', $userBs->email) 
                : (!empty(@$user->email) ? [$user->email] : (!empty(@$userContact->contact_mails) ? explode(',', $userContact->contact_mails) : []));
              $addresses = !empty(@$userBs->address) 
                ? explode(PHP_EOL, $userBs->address) 
                : (!empty(@$user->address) ? [$user->address] : (!empty(@$userContact->contact_addresses) ? explode(PHP_EOL, $userContact->contact_addresses) : []));
            @endphp

            <ul class="footer-links">

              @if (count($phone_numbers) > 0)
                <li><i class="far fa-phone mr-0"></i>
                  <div class="footer-widget-number">
                    @foreach ($phone_numbers as $phone_number)
                      <a href="tel: {{ $phone_number }}">{{ $phone_number }}</a>{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                  </div>
                </li>
              @endif
              @if (count($emails) > 0)
                <li><i class="far fa-envelope-open mr-0"></i>
                  <div class="footer-widget-email">
                    @foreach ($emails as $email)
                      <a href="mailto: {{ $email }}">{{ $email }}</a>{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                  </div>
                </li>
              @endif
              @if (count($addresses) > 0)

                @foreach ($addresses as $address)
                  <li>
                    <i class="far fa-map-marker-alt mr-0"></i> {{ $address }}
                  </li>
                @endforeach

              @endif
              @if (count($social_medias) > 0)
                <li>
                  <div class="social-link">
                    @foreach ($social_medias as $social)
                      @php
                        $url = preg_match('/^https?:\/\//', $social->url) ? $social->url : 'http://' . $social->url;
                      @endphp
                      <a href="{{ $url }}" target="_blank">
                        <i class="{{ $social->icon }}"></i>
                      </a>
                    @endforeach
                  </div>
                </li>
              @endif
            </ul>
          </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6">
          <div class="footer-widget">
            <h3>{{ @$footer->subscriber_title ?? ($keywords['Subscribe'] ?? __('Subscribe')) }}</h3>
            <p>
              {{ @$footer->subscriber_text }}
            </p>
            <form class="newsletter-form form-inline w-100" action="{{ route('front.user.subscribe', getParam()) }}">
              @csrf
              <div class="input-group p-0 rounded-pill">
                <input class="form-control"
                  placeholder="{{ $keywords['Enter_Email_Address'] ?? __('Enter Your Email') }}" type="text"
                  name="email" autocomplete="off">
                <button class="btn btn-lg btn-primary rounded-pill icon-end shadow" type="submit">
                  {{ $keywords['Subscribe'] ?? __('Subscribe') }} <i class="fal fa-paper-plane"></i>
                </button>
              </div>
            </form>
            @includeIf('user-front.partials.pwa-app-button')
          </div>
        </div>


      </div>
    </div>
  </div>
  <div class="copy-right-area border-top">
    <div class="container">
      <div class="copy-right-content">
        <span>
          {!! replaceBaseUrl($footer->copyright_text ?? null) !!}
        </span>
      </div>
    </div>
  </div>
</footer>
<!-- Footer End -->


<div class="mobile-menu-overlay"></div>
<!-- Responsive Mobile Menu -->
<div class="mobile-menu">
  <div class="mobile-menu-wrapper">
    <div class="mobile-menu-top">

      <div class="logo">
        <!-- logo -->
        <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
          <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
            data-src="{{ asset('assets/front/img/user/' . $userBs->logo) }}" alt="logo">
        </a>
      </div>
      <span class="mobile-menu-close"><i class="fal fa-times"></i></span>

    </div>
  </div>

  <!-- menu-item-action-wrapper -->
  @includeIf('user-front.partials.mobile-menu')
  <!-- menu-item-action-wrapper -->

</div>
<!-- Responsive Mobile Menu -->
