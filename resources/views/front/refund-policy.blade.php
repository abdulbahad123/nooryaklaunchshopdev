@extends('front.layout')

@section('pagename')
  - {{ __('Cancellation & Refund Policy') }}
@endsection

@section('breadcrumb-title')
{{ __('Cancellation & Refund Policy') }}
@endsection
@section('breadcrumb-link')
{{ __('Cancellation & Refund Policy') }}
@endsection

@section('content')
  <div class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid #e2e8f0;">
            <div class="card-body">
              <h2 class="mb-4" style="font-weight: 800; color: #0f172a; font-size: 28px;">{{ __('Cancellation & Refund Policy') }}</h2>
              <div class="content-text" style="color: #475569; line-height: 1.8; font-size: 15px;">
                <p>We want you to be completely satisfied with your purchase. This Cancellation & Refund Policy outlines our policies for subscription cancellations and refund requests.</p>
                                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">1. 10-Days Money Back Guarantee</h4>
                <p>We offer a **10-Days Money Back Guarantee** on all our standard paid plans (Basic, Standard, and Premium). If you decide that Launchshop.in is not a good fit for your business, you can cancel your subscription and request a full refund within 10 days of your initial purchase.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">2. Subscription Cancellation</h4>
                <p>You can cancel your subscription at any time directly through your User Dashboard or by contacting us. Upon cancellation, you will retain access to your store and platform features until the end of your current billing period. No lock-in contracts apply.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">3. Refund Requests after 10 Days</h4>
                <p>Except within the initial 10-day window under the guarantee, all subscription payments, custom domain integration fees, and add-on charges are non-refundable. We do not provide pro-rated refunds for unused portions of a billing cycle.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">4. How to Request a Refund</h4>
                <p>To request a refund under the 10-day guarantee, please send an email to **support@launchshop.in** with your account username and payment receipt. Refund requests are processed within 5-7 business days and credited to the original payment source.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
