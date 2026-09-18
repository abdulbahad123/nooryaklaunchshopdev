@extends('website_builder.construction_theme.layout')

@section('title', ($policy['title'] ?? 'Legal Policy') . ' - ' . ($agency->site_title ?? 'BuildCraft Construction'))

@section('content')
<!-- ===== POLICY HERO HEADER ===== -->
<section style="background: #111827; padding: 75px 0 40px; color: #ffffff;">
  <div class="cn-container text-center">
    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: rgba(254,198,1,0.15); color: #FEC601; font-weight: 700; font-size: 13.5px;">
      <i class="fa-solid fa-shield-halved"></i> Legal & Policy Information
    </div>
    <h1 class="cn-section-heading mb-3" style="font-size: clamp(32px, 4.5vw, 48px); color: #ffffff;">
      {{ $policy['title'] ?? 'Legal Policy' }}
    </h1>
    <p class="text-white-50 small mb-0">Official policy documentation for {{ $agency->site_title ?? 'BuildCraft Construction' }}</p>
  </div>
</section>

<!-- ===== POLICY BODY CONTENT ===== -->
<section style="padding: 40px 0 120px; background: #F9FAFB;">
  <div class="cn-container">
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
            $homeUrl = $subdomainParam ? route('website-builder.subdomain.site', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction');
            $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.construction.contact');
          @endphp
          <a href="{{ $homeUrl }}" class="cn-btn cn-btn-outline" style="border: 1.5px solid #111827; color: #111827;">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Home
          </a>
          <a href="{{ $contactUrl }}" class="cn-btn cn-btn-yellow">
            Have Questions? Contact Us <i class="fa-solid fa-envelope ms-2"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
