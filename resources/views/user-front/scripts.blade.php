<script>
  "use strict";
  var mainurl = "{{ route('front.user.detail.view', getParam()) }}";
  var vapid_public_key = "{{ is_array($packagePermissions) && in_array('Push Notification', $packagePermissions) ? env('VAPID_PUBLIC_KEY') : '' }}";
  var textPosition = "{{ $userBs->base_currency_text_position }}";
  var currSymbol = "{{ currency_sign() }}";
  var currValue = "{{ currency_value() }}";

  var position = "{{ $userBs->base_currency_symbol_position }}";
  var variation_url = "{{ route('front.user.get_variation', getParam()) }}";
  var show_more = "{{ $keywords['Show More'] ?? __('Show More') }}";
  var show_less = "{{ $keywords['Show Less'] ?? __('Show Less') }}";
  var show_variations = "{{ $keywords['View Variations'] ?? __('View Variations') }}";
  var less_variations = "{{ $keywords['Less Variations'] ?? __('Less Variations') }}";
  var stock_unavailable = "{{ $keywords['stock unavailable'] ?? __('stock unavailable') }}";
  var select_a_variant = "{{ $keywords['Select A variation first'] ?? __('Select A variation first') }}";
  var success = "{{ $keywords['Success'] ?? __('Success') }}";
  var nextText = "{{ $keywords['Next'] ?? __('Next') }}";
  var previousText = "{{ $keywords['Previous'] ?? __('Previous') }}";
  var showText = "{{ $keywords['Show'] ?? __('Show') }}";
  var entriesText = "{{ $keywords['entries'] ?? __('entries') }}";
  var Search = "{{ $keywords['Search'] ?? __('Search') }}";
  var Showing = "{{ $keywords['Showing'] ?? __('Showing') }}";
  var to = "{{ $keywords['to'] ?? __('to') }}";
  var ofText = "{{ $keywords['of'] ?? __('of') }}";
  var currentTime = "{{ \Carbon\Carbon::now($userBs->timezone)->toDateTimeString() }}";
</script>


<!-- Jquery JS -->
<script src="{{ asset('assets/user-front/js/plugins.js') }}"></script>

{{-- aos.min.js --}}
<script src="{{ asset('assets/user-front/js/aos.min.js') }}"></script>

<!-- Main script JS -->
<script src="{{ asset('assets/user-front/js/shop.js?v=1.0.3') }}"></script>

<script src="{{ asset('assets/user-front/js/script.js?v=1.0.5') }}"></script>
<script src="{{ asset('assets/user-front/js/cart.js') }}"></script>
@if (is_array($packagePermissions) && in_array('Push Notification', $packagePermissions))
  <script src="{{ asset('assets/front/js/push-notification.js') }}"></script>
@endif

<!-- Custom Category Slider Script -->
<script>
  $(document).ready(function() {
    // Duplicate categories for seamless infinite loop
    if ($('#categories-nav-slider').length > 0) {
      var slider = $('#categories-nav-slider');
      var originalContent = slider.html();
      slider.append(originalContent); // Duplicate for seamless loop
      
      // Pause on hover
      slider.on('mouseenter', function() {
        $(this).css('animation-play-state', 'paused');
      }).on('mouseleave', function() {
        $(this).css('animation-play-state', 'running');
      });
    }
    
    // Toggle category dropdown on button click (mobile)
    $('.category-menu-toggle').on('click', function(e) {
      e.preventDefault();
      $(this).siblings('.category-dropdown').slideToggle();
    });
  });
</script>

@if (session()->has('success'))
  <script>
    "use strict";
    toastr['success']("{{ session('success') }}");
  </script>
@endif

@if (session()->has('error'))
  <script>
    "use strict";
    toastr['error']("{{ session('error') }}");
  </script>
@endif

@if (session()->has('warning'))
  <script>
    "use strict";
    toastr['warning']("{{ session('warning') }}");
  </script>
@endif
