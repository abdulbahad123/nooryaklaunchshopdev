<!-- Footer Start -->
<footer class="footer-area footer-v7">
  <!-- Background Image -->
  <img class="lazyload blur-up bg-img"
    data-src="{{ !is_null(@$footer->background_image) ? asset('assets/front/img/footer/' . $footer->background_image) : asset('assets/user-front/images/jewellery/footer-bg.jpg') }}"
    alt="">
  <div class="footer-top pt-100 pb-70">
    <div class="container">
      <div class="row gx-xl-5">
        <div class="col-lg-4 col-md-12">
          <div class="footer-widget">
            <div class="navbar-brand">
              <a href="{{ route('front.user.detail.view', getParam()) }}">
                <img
                  src="{{ !is_null(@$footer->footer_logo) ? asset('assets/front/img/footer/' . @$footer->footer_logo) : asset('assets/front/img/logo.png') }}"
                  alt="Logo">
              </a>
            </div>
            <p class="text fw-medium">
              {{ @$footer->footer_text ?? '' }}
            </p>
            @if (strlen(@$footer->footer_text) > 250)
              <span class="show-more-footer">{{ $keywords['Show More'] ?? __('Show More') }} +</span>
            @endif

            @if (count($social_medias) > 0)
              <div class="social-link">
                @foreach ($social_medias as $social)
                  @php
                    $url = preg_match('/^https?:\/\//', $social->url) ? $social->url : 'http://' . $social->url;
                  @endphp
                  <a style="background: #{{ $social->background_color }}" href="{{ $url }}" target="_blank">
                    <i class="{{ $social->icon }}"></i>
                  </a>
                @endforeach
              </div>
            @endif

          </div>
        </div>
        <div class="col-xl-2 col-md-6">
          <div class="footer-widget">
            <h3>{{ $footer->useful_links_title ?? __('Useful Links') }}</h3>
            @if (count($ulinks) == 0)
              <h6 class="title mb-20">
                {{ $keywords['NO LINKS FOUND'] ?? __('NO LINKS FOUND') }}
              </h6>
            @else
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
            @endif
          </div>
        </div>
        <div class="col-xl-2 col-md-6">
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
              @if (count($addresses) > 0)
                <li>
                  @foreach ($addresses as $address)
                    <span>{{ $address }}</span>
                  @endforeach

                </li>
              @endif
              @if (count($emails) > 0)
                @foreach ($emails as $email)
                  <li>
                    <a href="mailto: {{ $email }}">{{ $email }}</a>
                  </li>
                @endforeach
              @endif
              @if (count($phone_numbers) > 0)
                @foreach ($phone_numbers as $phone_number)
                  <li>
                    <a href="tel: {{ $phone_number }}">{{ $phone_number }}</a>
                  </li>
                @endforeach
              @endif
            </ul>
          </div>
        </div>
        <div class="col-xl-4 col-md-6">
          <div class="footer-widget">
            <h3>{{ @$footer->subscriber_title ?? ($keywords['Subscribe'] ?? __('Subscribe')) }}</h3>
            <p>{{ @$footer->subscriber_text }}</p>
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
  <div class="copy-right-area border-top-medium">
    <div class="container">
      <div class="copy-right-content">
        <span>
          {!! replaceBaseUrl(@$footer->copyright_text ?? null) ?: ('Copyright &copy; ' . date('Y') . ' ' . ($userBs->website_title ?? 'Metroshop') . '. All Rights Reserved.') !!}
          <span class="powered-by-link"> | Powered by <a href="https://launchshop.in/" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: underline; font-weight: 600;">Launchshop</a></span>
        </span>
      </div>
    </div>
  </div>
</footer>
<!-- Footer End -->

@includeIf('user-front.jewellery.partials.mobile-menu')
