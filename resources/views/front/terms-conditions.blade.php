@extends('front.layout')

@section('pagename')
  - {{ __('Terms & Conditions') }}
@endsection

@section('breadcrumb-title')
{{ __('Terms & Conditions') }}
@endsection
@section('breadcrumb-link')
{{ __('Terms & Conditions') }}
@endsection

@section('content')
  <div class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid #e2e8f0;">
            <div class="card-body">
              <h2 class="mb-4" style="font-weight: 800; color: #0f172a; font-size: 28px;">{{ __('Terms & Conditions') }}</h2>
              <div class="content-text" style="color: #475569; line-height: 1.8; font-size: 15px;">
                <p>Welcome to Launchshop.in. These Terms & Conditions outline the rules and regulations for the use of Launchshop's Website and services.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">1. Terms</h4>
                <p>By accessing this website, we assume you accept these Terms & Conditions in full. Do not continue to use Launchshop.in if you do not agree to take all of the terms stated on this page.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">2. Subscriptions and Billing</h4>
                <p>Subscriptions on Launchshop.in are billed on a recurring monthly or yearly basis, or as a lifetime fee, depending on the plan selected during checkout. It is the store owner's responsibility to maintain active payment configurations to prevent suspension of their online storefront.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">3. User Content & Conduct</h4>
                <p>As a store owner, you are fully responsible for the products, product descriptions, pricing, customer interactions, and lawful compliance of your store. Any storefront violating local laws, copyright policies, or hosting illegal products will be terminated without prior notice.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">4. Limitation of Liability</h4>
                <p>Launchshop.in is provided "as is," without warranties of any kind. Under no circumstances shall Launchshop.in be held liable for store downtime, order processing errors, payment gateway failures, loss of profits, or data leaks resulting from third-party hosting issues.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">5. Revisions and Amendments</h4>
                <p>We reserve the right to amend these Terms & Conditions at any time. Continued usage of the platform following updates represents your acknowledgment and agreement to comply with the revised terms.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
