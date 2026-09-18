@extends('website_builder.evently_theme.layout')

@section('title', ($policy['title'] ?? 'Legal Policy') . ' - ' . ($evData->site_title ?? 'Evently'))

@section('content')
<!-- ===== POLICY HERO HEADER ===== -->
<section style="background: linear-gradient(135deg, #0B0F19 0%, #171E2E 100%); padding: 75px 0 40px; color: #ffffff;">
  <div class="ev-container text-center">
    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: rgba(108,60,225,0.2); color: #A78BFA; font-weight: 700; font-size: 13.5px;">
      <i class="fa-solid fa-shield-halved"></i> Legal & Policy Information
    </div>
    <h1 class="ev-section-title mb-3" style="font-size: clamp(32px, 4.5vw, 48px); color: #ffffff; font-family: var(--ev-font-heading);">
      {{ $policy['title'] ?? 'Legal Policy' }}
    </h1>
    <p class="text-white-50 small mb-0">Official policy documentation for {{ $evData->site_title ?? 'Evently' }}</p>
  </div>
</section>

<!-- ===== POLICY BODY CONTENT ===== -->
<section style="padding: 40px 0 120px; background: #F8FAFC;">
  <div class="ev-container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="card border-0 shadow-sm p-4 p-md-5 mb-4" style="border-radius: 20px; background: #FFFFFF;">
          <div class="policy-body-content text-slate-800" style="font-size: 16.5px; line-height: 1.85; white-space: pre-line;">
            {!! e($policy['content'] ?? 'No policy content provided.') !!}
          </div>
        </div>

        <div class="pt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
          @php
            $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
            $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently');
            $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.contact');
          @endphp
          <a href="{{ $homeUrl }}" class="ev-btn" style="border: 1.5px solid #0F172A; background: #ffffff; color: #0F172A;">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Home
          </a>
          <a href="{{ $contactUrl }}" class="ev-btn ev-btn-primary">
            Have Questions? Contact Us <i class="fa-solid fa-envelope ms-2"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
