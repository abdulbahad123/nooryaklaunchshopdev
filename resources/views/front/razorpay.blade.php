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
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
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