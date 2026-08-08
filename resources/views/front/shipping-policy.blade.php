@extends('front.layout')

@section('pagename')
  - {{ __('Shipping & Delivery Policy') }}
@endsection

@section('breadcrumb-title')
{{ __('Shipping & Delivery Policy') }}
@endsection
@section('breadcrumb-link')
{{ __('Shipping & Delivery Policy') }}
@endsection

@section('content')
  <div class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid #e2e8f0;">
            <div class="card-body">
              <h2 class="mb-4" style="font-weight: 800; color: #0f172a; font-size: 28px;">{{ __('Shipping & Delivery Policy') }}</h2>
              <div class="content-text" style="color: #475569; line-height: 1.8; font-size: 15px;">
                <p>Welcome to the Shipping & Delivery Policy for Launchshop.in. This document explains the processing, shipping, and delivery guidelines for products and services obtained on our platform.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">1. Platform Nature (SaaS Platform)</h4>
                <p>Launchshop.in is a Software-as-a-Service (SaaS) provider. We provide ecommerce storefront software, custom domain setups, and related hosting services. There are no physical goods shipped directly by Launchshop.in to our platform subscribers.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">2. Digital Delivery & Setup Timeline</h4>
                <p>Upon a successful purchase of a paid plan, your Launchshop store is provisioned **instantly**. You will receive account access immediately. 
                * **Custom Domains (.in or other extensions):** Submissions for custom domains are initiated immediately. Active registration and propagation can take between **24 to 48 hours** to complete.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">3. Vendor Store Deliveries (For Customers of Stores Powered by Launchshop)</h4>
                <p>If you are a customer purchasing physical or digital products from an online store **hosted** on Launchshop.in, the delivery timeline, shipping charges, carrier options, and tracking details are managed **solely by the respective store owner (vendor)**. Launchshop.in does not handle shipping operations or package fulfillment for individual store orders.</p>
                
                <h4 class="mt-4 mb-2" style="font-weight: 700; color: #1e293b;">4. Support</h4>
                <p>For inquiries regarding your subscription setup, domain registration, or platform access, please reach out to our support team at **support@launchshop.in**.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
