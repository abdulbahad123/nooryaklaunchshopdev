@php
  $reqHost = strtolower(trim((string)(request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? ''))));
  $reqHost = preg_replace('/:\d+$/', '', $reqHost);
  $reqHost = preg_replace('/^www\./i', '', $reqHost);
  $cleanAgencyHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder|whatsappbulk|whatsapp-bulk|whatsapp|wa)\./i', '', $reqHost);
  $scheme = (request()->secure() || str_contains(request()->fullUrl(), 'https://') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')) ? 'https://' : 'http://';

  $prevUrl = url()->previous();
  $isWb = session()->has('wb_checkout_req') || request('product_type') == 'website_builder' || request('is_website_builder');

  if (empty($prevUrl) || $prevUrl === url()->current() || str_contains($prevUrl, 'checkout.')) {
      if (!empty($cleanAgencyHost) && $cleanAgencyHost !== 'localhost' && $cleanAgencyHost !== '127.0.0.1') {
          $prevUrl = $isWb 
              ? "{$scheme}websitebuilder.{$cleanAgencyHost}/website-builder/checkout"
              : "{$scheme}launchshop.{$cleanAgencyHost}/registration/final-step";
      } else {
          $prevUrl = $isWb ? route('website-builder.checkout') : url('/');
      }
  }
@endphp
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="{{ $notify_url }}" method="POST">
    @csrf
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >

    {{-- Website Builder Checkout session payload backup --}}
    @if(session()->has('wb_checkout_req'))
        @foreach(session('wb_checkout_req') as $wbKey => $wbVal)
            @if(is_scalar($wbVal))
                <input type="hidden" name="{{ $wbKey }}" value="{{ $wbVal }}">
            @endif
        @endforeach
    @endif

    {{-- LaunchShop Membership Checkout session payload backup --}}
    @if(session()->has('request'))
        @foreach(session('request') as $lsKey => $lsVal)
            @if(is_scalar($lsVal))
                <input type="hidden" name="{{ $lsKey }}" value="{{ $lsVal }}">
            @endif
        @endforeach
    @endif

    @if(session()->has('wb_checkout_req') || request('product_type') == 'website_builder' || request('is_website_builder'))
        <input type="hidden" name="product_type" value="website_builder">
        <input type="hidden" name="is_website_builder" value="1">
    @endif
</form>
<script>
// Checkout details as a json
var options = {!! is_string($json) ? $json : json_encode($json) !!};

/**
 * The entire list of Checkout fields is available at
 * https://docs.razorpay.com/docs/checkout-form#checkout-fields
 */
options.handler = function (response){
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    if (document.getElementById('razorpay_signature')) {
        document.getElementById('razorpay_signature').value = response.razorpay_signature || '';
    }

    // 1. Client-side fallback for Website Builder from localStorage
    try {
        var form = document.razorpayform;
        var wbStr = localStorage.getItem('wb_pending_checkout_customer');
        if (wbStr) {
            var wbObj = JSON.parse(wbStr);
            for (var k in wbObj) {
                if (wbObj.hasOwnProperty(k) && !form.elements[k]) {
                    var inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = k;
                    inp.value = wbObj[k];
                    form.appendChild(inp);
                }
            }
        }
        var wbTmpl = localStorage.getItem('selected_wb_template');
        if (wbTmpl) {
            ['template', 'template_slug', 'theme', 'selected_template'].forEach(function(fn) {
                if (!form.elements[fn]) {
                    var inpTmpl = document.createElement('input');
                    inpTmpl.type = 'hidden';
                    inpTmpl.name = fn;
                    inpTmpl.value = wbTmpl;
                    form.appendChild(inpTmpl);
                }
            });
        }
    } catch(e){}

    // 2. Client-side fallback for LaunchShop template from localStorage
    try {
        var form = document.razorpayform;
        var lsTmpl = localStorage.getItem('launchshop_selected_template') || localStorage.getItem('selected_template');
        if (lsTmpl && !form.elements['selected_template']) {
            var inpLs = document.createElement('input');
            inpLs.type = 'hidden';
            inpLs.name = 'selected_template';
            inpLs.value = lsTmpl;
            form.appendChild(inpLs);
        }
    } catch(e){}

    document.razorpayform.submit();
};

if (!options.theme) {
    options.theme = {};
}
options.theme.image_padding = false;

options.modal = {
    ondismiss: function() {
      window.location.assign("{!! $prevUrl !!}");
    },
    // Boolean indicating whether pressing escape key
    // should close the checkout form. (default: true)
    escape: true,

    // Boolean indicating whether clicking translucent blank
    // space outside checkout form should close the form. (default: false)
    backdropclose: false
};

var rzp = new Razorpay(options);

rzp.open();
</script>