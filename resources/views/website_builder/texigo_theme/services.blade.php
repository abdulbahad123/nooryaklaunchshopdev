@extends('website_builder.texigo_theme.layout')

@section('title', 'Services - ' . ($agency->site_title ?? 'TaxiGo Mobility'))

@section('content')

@php
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;
  $contactUrl = $subdomainParam ? route('website-builder.subdomain.contact', ['subdomain' => $subdomainParam]) : route('website-builder.templates.texigo.contact');
  $services = (!empty($agency->services_data) && is_array($agency->services_data) && count($agency->services_data) > 0)
    ? $agency->services_data
    : [
        ['title' => 'City Rides', 'desc' => 'Quick and affordable rides within your city.', 'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_city_rides.png', 'icon' => 'fa-city'],
        ['title' => 'Airport Transfers', 'desc' => 'On-time pickups and drop-offs for airport travel.', 'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_airport_transfers.png', 'icon' => 'fa-plane-departure'],
        ['title' => 'Outstation Trips', 'desc' => 'Comfortable rides to any destination.', 'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_outstation_trips.png', 'icon' => 'fa-route'],
        ['title' => 'Corporate Travel', 'desc' => 'Reliable rides for business professionals.', 'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_corporate_travel.png', 'icon' => 'fa-briefcase'],
        ['title' => 'Parcel Delivery', 'desc' => 'Fast and secure delivery service.', 'image' => 'assets/website_builder/Templates/Texigo_agency/services/service_parcel_delivery.png', 'icon' => 'fa-box'],
        ['title' => 'Luxury Chauffeur Service', 'desc' => 'Premium high-end vehicles with professional chauffeurs for VIP travel.', 'image' => 'assets/website_builder/Templates/Texigo_agency/service_luxury.png', 'icon' => 'fa-user-tie'],
      ];
@endphp

<!-- ===== HERO BANNER SECTION ===== -->
<section class="tx-hero" style="background: #FFF8E6; padding: 60px 0 50px;">
  <div class="tx-container text-center" style="max-width: 700px; margin: 0 auto;">
    <span class="tx-pill-badge mb-3" style="background: #FFB800; color: #0D0F12;">{{ $agency->services_badge ?? 'OUR SERVICES' }}</span>
    <h1 class="tx-heading tx-hero-title mb-3">{!! nl2br(e($agency->services_title ?? "Reliable & Comfortable\nMobility Services")) !!}</h1>
    <p class="tx-hero-subtitle">{{ $agency->services_subtitle ?? 'Quick city rides, airport transfers, outstation trips, corporate travel, and parcel delivery.' }}</p>
  </div>
</section>

<!-- ===== SERVICES GRID ===== -->
<section class="py-5" style="background: #ffffff;">
  <div class="tx-container">
    <div class="row g-4">
      @foreach($services as $srv)
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column justify-content-between" style="background: #FFFDF8; border: 1px solid #FFE6A8 !important;">
            <div>
              @if(!empty($srv['image']))
                <div class="mb-3 rounded-3 overflow-hidden" style="height: 180px;">
                  <img src="{{ resolveWebsiteBuilderImage($srv['image'] ?? '', 'assets/website_builder/Templates/Texigo_agency/services/service_city_rides.png') }}" alt="{{ $srv['title'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
              @endif
              <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; background: #FFB800; color: #0D0F12; font-size: 20px;">
                <i class="fa-solid {{ $srv['icon'] ?? 'fa-taxi' }}"></i>
              </div>
              <h3 class="fw-bold fs-5 text-dark mb-2">{{ $srv['title'] ?? '' }}</h3>
              <p class="text-muted small mb-4">{{ $srv['desc'] ?? $srv['description'] ?? '' }}</p>
            </div>
            <a href="{{ $contactUrl }}" class="tx-btn tx-btn-yellow w-100 text-center fw-bold py-2.5">
              Book Ride Now <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
