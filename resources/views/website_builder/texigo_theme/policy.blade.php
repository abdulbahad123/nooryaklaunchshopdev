@extends('website_builder.texigo_theme.layout')

@section('title', ($policy['title'] ?? 'Legal Policy') . ' - ' . ($agency->site_title ?? 'TaxiGo Mobility'))

@section('content')
<!-- ===== POLICY HERO HEADER ===== -->
<section style="background: #F8F9FA; padding: 75px 0 40px; border-bottom: 1px solid #E9ECEF;">
  <div class="tx-container text-center">
    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: #FFF3CD; color: #856404; font-weight: 700; font-size: 13.5px;">
      <i class="fa-solid fa-shield-halved"></i> Legal & Policy Information
    </div>
    <h1 class="tx-heading mb-3" style="font-size: clamp(32px, 4.5vw, 48px); color: #0F172A;">
      {{ $policy['title'] ?? 'Legal Policy' }}
    </h1>
    <p class="text-muted small mb-0">Official policy documentation for {{ $agency->site_title ?? 'TaxiGo Mobility' }}</p>
  </div>
</section>

<!-- ===== POLICY BODY CONTENT ===== -->
<section style="padding: 30px 0 120px; background: #FFFFFF;">
  <div class="tx-container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="card border-0 shadow-sm p-4 p-md-5 mb-4" style="border-radius: 20px; background: #F8F9FA;">
          <div class="policy-body-content text-slate-800" style="font-size: 16.5px; line-height: 1.85; white-space: pre-line;">
            {!! e($policy['content'] ?? 'No policy content provided.') !!}
          </div>
        </div>

        <div class="pt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
          @php
            $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
            $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo');
            $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.contact');
          @endphp
          <a href="{{ $homeUrl }}" class="tx-btn tx-btn-outline-dark px-4 py-2 fw-bold">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Home
          </a>
          <a href="{{ $contactUrl }}" class="tx-btn tx-btn-yellow px-4 py-2 fw-bold">
            Have Questions? Contact Us <i class="fa-solid fa-envelope ms-2"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
