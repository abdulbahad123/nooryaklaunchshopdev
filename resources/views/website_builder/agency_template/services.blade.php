@extends('website_builder.agency_template.layout')

@section('title', ($agency->site_title ?? 'DesignAGENCY') . ' — Services')

@section('content')
<style>
  .services-hero-section {
    background: linear-gradient(180deg, #F0FDF4 0%, #FFFFFF 100%);
    padding: 70px 0 50px;
    position: relative;
    text-align: center;
  }
  .services-badge {
    background: #D1FAE5;
    color: #059669;
    font-weight: 700;
    font-size: 13px;
    padding: 6px 16px;
    border-radius: 30px;
    display: inline-block;
    margin-bottom: 16px;
  }
  .services-hero-title {
    font-size: clamp(32px, 4vw, 52px);
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 16px;
    line-height: 1.15;
  }
  .services-hero-desc {
    font-size: 16px;
    color: #475569;
    max-width: 600px;
    margin: 0 auto 24px;
    line-height: 1.6;
  }
  .service-card-v2 {
    background: #ffffff;
    border-radius: 20px;
    padding: 32px 28px;
    border: 1px solid #F1F5F9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .service-card-v2:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(16, 185, 129, 0.12);
    border-color: #A7F3D0;
  }
  .service-icon-box {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: #ECFDF5;
    color: #10B981;
    font-size: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
  }
  .service-v2-title {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 12px;
  }
  .service-v2-desc {
    font-size: 14.5px;
    color: #64748B;
    line-height: 1.6;
    margin-bottom: 20px;
  }
</style>

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.digital_agency.contact');
  $services = $agency->services_data ?? [
    ['title' => 'Web Design & Development', 'desc' => 'Custom responsive websites engineered for high speed, conversion, and brand growth.', 'icon' => 'fa-laptop-code', 'image' => 'assets/website_builder/wb_card_agency.png'],
    ['title' => 'UI/UX Mobile App Design', 'desc' => 'User-centric mobile app designs with intuitive UX journeys and sleek modern UI aesthetics.', 'icon' => 'fa-mobile-screen-button', 'image' => 'assets/website_builder/wb_card_startup.png'],
    ['title' => 'Brand Identity & Strategy', 'desc' => 'Cohesive brand identity, logo design, style guides, and positioning strategy for startups.', 'icon' => 'fa-bullhorn', 'image' => 'assets/website_builder/wb_card_portfolio.png'],
  ];
@endphp

<!-- ===== HERO BANNER SECTION ===== -->
<section class="services-hero-section">
  <div class="container">
    <div class="services-badge">{{ $agency->services_badge ?? 'OUR SERVICES' }}</div>
    <h1 class="services-hero-title">{!! nl2br(e($agency->services_title ?? "High-Impact Digital &\nTechnology Services")) !!}</h1>
    <p class="services-hero-desc">{{ $agency->services_subtitle ?? 'We craft bespoke digital experiences, strategic marketing, and high-performance applications.' }}</p>
    <a href="{{ $contactUrl }}" class="btn-start-project" style="background: #10B981; color: #fff; padding: 12px 28px; border-radius: 30px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
      Get Started Today <i class="fa-solid fa-arrow-right"></i>
    </a>
  </div>
</section>

<!-- ===== SERVICES GRID SECTION ===== -->
<section style="background: #ffffff; padding: 60px 0 100px;">
  <div class="container">
    <div class="row g-4">
      @foreach($services as $srv)
        <div class="col-12 col-md-6 col-lg-4">
          <div class="service-card-v2">
            <div>
              @if(!empty($srv['image']))
                <div class="mb-3 rounded-4 overflow-hidden" style="height: 180px;">
                  <img src="{{ resolveWebsiteBuilderImage($srv['image'] ?? '', 'assets/website_builder/wb_card_agency.png') }}" alt="{{ $srv['title'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
              @endif
              <div class="service-icon-box">
                <i class="fa-solid {{ $srv['icon'] ?? 'fa-cube' }}"></i>
              </div>
              <h3 class="service-v2-title">{{ $srv['title'] ?? '' }}</h3>
              <p class="service-v2-desc">{{ $srv['desc'] ?? $srv['description'] ?? '' }}</p>
            </div>
            <a href="{{ $contactUrl }}" class="fw-bold text-emerald text-decoration-none d-inline-flex align-items-center gap-2" style="color: #10B981;">
              Inquire About Service <i class="fa-solid fa-arrow-right" style="font-size: 13px;"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
