@extends('website_builder.interior_template.layout')

@section('title', ($policy['title'] ?? 'Legal Policy') . ' - ' . ($interior->site_title ?? 'InteriorCRAFT'))

@section('content')
<!-- ===== POLICY HERO HEADER ===== -->
<section style="background: #F7F7F5; padding: 75px 0 40px;">
  <div class="ic-container text-center">
    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: #E8EBE9; color: #10B981; font-weight: 700; font-size: 13.5px;">
      <i class="fa-solid fa-shield-halved"></i> Legal & Policy Information
    </div>
    <h1 class="ic-heading mb-3" style="font-size: clamp(32px, 4.5vw, 48px); color: #0F172A;">
      {{ $policy['title'] ?? 'Legal Policy' }}
    </h1>
    <p class="text-muted small mb-0">Official policy documentation for {{ $interior->site_title ?? 'InteriorCRAFT' }}</p>
  </div>
</section>

<!-- ===== POLICY BODY CONTENT ===== -->
<section style="padding: 30px 0 120px; background: #FFFFFF;">
  <div class="ic-container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="card border-0 shadow-sm p-4 p-md-5 mb-4" style="border-radius: 20px; background: #F7F7F5;">
          <div class="policy-body-content text-slate-800" style="font-size: 16.5px; line-height: 1.85; white-space: pre-line;">
            {!! e($policy['content'] ?? 'No policy content provided.') !!}
          </div>
        </div>

        <div class="pt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
          @php
            $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
            $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior');
            $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.interior.contact');
          @endphp
          <a href="{{ $homeUrl }}" class="ic-btn py-2 px-4 fw-bold d-inline-flex align-items-center gap-2" style="border: 1.5px solid #0F172A; background: #ffffff; color: #0F172A; border-radius: 9999px;">
            <i class="fa-solid fa-arrow-left"></i> Back to Home
          </a>
          <a href="{{ $contactUrl }}" class="ic-btn ic-btn-dark py-2 px-4 fw-bold d-inline-flex align-items-center gap-2">
            Have Questions? Contact Us <i class="fa-solid fa-envelope"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
