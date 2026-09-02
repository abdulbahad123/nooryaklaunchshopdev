<!-- Footer Area -->
<footer class="footer-area aaaaaa">
  <div class="footer-top pt-120 ">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-12">
          <div class="footer-widget" data-aos="fade-up" data-aos-delay="100">
            <div style="margin-bottom: 8px;">
              <a href="{{ route('front.index') }}">
                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/front/img/' . $bs->footer_logo) }}" alt="Logo">
              </a>
            </div>
            <p>{{ $bs->footer_text }}</p>
            <div class="social-link mb-2" bis_skin_checked="1">
              <a href="https://www.instagram.com/ecombuilder.in/" target="_blank" title=""><i class="fab fa-instagram"></i></a>
              <a href="https://www.facebook.com/" target="_blank" title=""><i class="fab fa-facebook-f"></i></a>
              <a href="https://www.linkedin.com/" target="_blank" title=""><i class="fab fa-linkedin-in"></i></a>
              <a href="https://www.youtube.com/" target="_blank" title=""><i class="fab fa-youtube"></i></a>
              <a href="https://twitter.com/" target="_blank" title=""><i class="fab fa-twitter"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-1 col-md-1"></div>
        <div class="col-lg-2 col-md-3 col-sm-6">
          <div class="footer-widget" data-aos="fade-up" data-aos-delay="200">
            @php
              $ulinks = App\Models\Ulink::where('language_id', $currentLang->id)->orderby('id', 'desc')->get();
            @endphp
            <h3>{{ $bs->useful_links_title }}</h3>
            <ul class="footer-links">
              @foreach ($ulinks as $ulink)
                @if (stripos($ulink->name, 'privacy') === false && stripos($ulink->name, 'terms') === false)
                  <li><a href="{{ $ulink->url }}">{{ $ulink->name }}</a></li>
                @endif
              @endforeach
              <li><a href="{{ route('front.privacy-policy') }}">{{ __('Privacy Policy') }}</a></li>
              <li><a href="{{ route('front.terms-conditions') }}">{{ __('Terms & Conditions') }}</a></li>
              <li><a href="{{ route('front.refund-policy') }}">{{ __('Cancellation & Refund Policy') }}</a></li>
              <li><a href="{{ route('front.shipping-policy') }}">{{ __('Shipping & Delivery Policy') }}</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <div class="footer-widget" data-aos="fade-up" data-aos-delay="400">
            <h3> {{ $bs->contact_info_title }}</h3>

            <ul class="info-list">
              <li>
                <i class="fal fa-map-marker-alt"></i>
                <span>{{ $be->contact_addresses }}</span>
              </li>

              @php
                $numbers = explode(',', str_replace('6374913298', '72007 70351', $be->contact_numbers ?? '72007 70351'));
              @endphp
              <li>
                <i class="fal fa-phone" style="transform: scaleX(-1); display: inline-block;"></i>
                {!! implode(
                    '<br>',
                    array_map(fn($num) => '<a href="tel:' . trim($num) . '" class="me-1">' . trim($num) . '</a>', $numbers),
                ) !!}
              </li>
              <li>
                <i class="fal fa-envelope"></i>
                <a href="mailto:{{ $be->contact_mails }}">{{ $be->contact_mails }}</a>
              </li>

            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <div class="footer-widget" data-aos="fade-up" data-aos-delay="500">
            <h3>{{ $bs->newsletter_title }}</h3>
            <p>{{ $bs->newsletter_subtitle }}</p>
            <form id="footerSubscriber" action="{{ route('front.subscribe') }}" method="POST">
              @csrf
              <div class="input-group">
                <input class="form-control" placeholder="{{ __('Enter Your Email') }}" name="email"
                  autocomplete="off">
                <button class="btn btn-sm primary-btn" type="submit">{{ __('Subscribe') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if ($bs->copyright_section == 1)
    <div class="copy-right-area">
      <div class="container">
        <div class="copy-right-content">
          @if ($bs->copyright_section == 1)
            <span>
              {!! html_entity_decode(replaceBaseUrl($bs->copyright_text)) !!}
            </span>
          @endif
        </div>
      </div>
    </div>
  @endif
</footer>
<!-- Footer Area -->
