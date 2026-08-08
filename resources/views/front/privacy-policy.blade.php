@extends('front.layout')

@section('pagename')
  - {{ __('Privacy Policy') }}
@endsection

@section('breadcrumb-title')
{{ __('Privacy Policy') }}
@endsection
@section('breadcrumb-link')
{{ __('Privacy Policy') }}
@endsection

@section('content')
  <div class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid #e2e8f0;">
            <div class="card-body">
              <h2 class="mb-4" style="font-weight: 800; color: #0f172a; font-size: 28px;">{{ __('Privacy Policy') }}</h2>
              <div class="content-text" style="color: #475569; line-height: 1.8; font-size: 15px;">
                <p>Welcome to Launchshop.in. Your privacy is of paramount importance to us. This Privacy Policy document details the types of personal information that is collected and recorded by Launchshop.in and how we use it.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">1. Information We Collect</h4>
                <p>We collect personal details that you voluntarily provide to us when registering, such as your name, business name, phone number, email address, billing details, and payment information. We also automatically collect server logs, browser details, and device configurations through cookies to optimize user experience.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">2. How We Use Your Information</h4>
                <p>The information we collect is utilized to operate, maintain, and provide the platform's features. This includes managing subscription billing, facilitating store configurations, communicating updates, and analyzing traffic to enhance store performance and safety.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">3. Cookies and Tracking Technologies</h4>
                <p>Launchshop.in uses standard tracking technologies and cookies to store session states, save user dashboard settings, and track referral and traffic sources to optimize service delivery. You can disable cookies in your browser settings, though some features may fail to function correctly.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">4. Third-Party Services</h4>
                <p>We integrate secure third-party payment gateways (like Razorpay, Stripe, Paytm, and PayPal) to process transaction fees. Launchshop.in does not store card numbers or banking passwords on our servers. All payment transactions comply with secure industry protocols.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">5. Contact Us</h4>
                <p>If you have any additional questions or require more information about our Privacy Policy, please do not hesitate to contact us at support@launchshop.in.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
