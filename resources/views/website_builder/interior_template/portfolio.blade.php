@extends('website_builder.interior_template.layout')

@section('title', 'Portfolio - InteriorCRAFT Architectural Projects')

@section('content')

<!-- Header Banner -->
<section class="py-5" style="background: var(--ic-secondary-dark); color: #ffffff;">
  <div class="ic-container text-center py-4">
    <span class="ic-sub-badge" style="background: rgba(255,255,255,0.15); color: #fff;">ARCHITECTURAL PORTFOLIO</span>
    <h1 class="ic-heading-serif text-white display-4 mb-3">Our Completed Projects</h1>
    <p class="text-white-50 mx-auto" style="max-width: 600px; font-size: 16px;">
      Explore our showcase of luxury residential homes, penthouse renovations, and modern commercial interiors.
    </p>
  </div>
</section>

<!-- Portfolio Section -->
<section class="ic-section">
  <div class="ic-container">
    @php
      $portfolio = $interior->portfolio_data ?? [
        [
          'title'    => 'Modern Scandinavian Villa',
          'category' => 'Residential Design',
          'image'    => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=800&auto=format&fit=crop',
          'desc'     => 'Minimalist wood accents & floor-to-ceiling glass architecture.'
        ],
        [
          'title'    => 'Manhattan Penthouse Suite',
          'category' => 'Luxury Residential',
          'image'    => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=800&auto=format&fit=crop',
          'desc'     => 'Custom marble finishes & panoramic city skyline view.'
        ],
        [
          'title'    => 'Artisan Botanical Cafe',
          'category' => 'Commercial Design',
          'image'    => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=800&auto=format&fit=crop',
          'desc'     => 'Earthy interior tones with living green walls.'
        ],
        [
          'title'    => 'Zen Minimalist Loft',
          'category' => 'Space Planning',
          'image'    => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?q=80&w=800&auto=format&fit=crop',
          'desc'     => 'Japanese-inspired sliding wooden panels & low seating.'
        ],
        [
          'title'    => 'Heritage Rowhouse Renovation',
          'category' => 'Restoration & Styling',
          'image'    => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=800&auto=format&fit=crop',
          'desc'     => 'Preserving vintage brickwork paired with contemporary furniture.'
        ],
        [
          'title'    => 'Tech Executive HQ Lounge',
          'category' => 'Commercial Architecture',
          'image'    => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800&auto=format&fit=crop',
          'desc'     => 'Acoustic oak paneling and ergonomic collaborative lounges.'
        ],
      ];
    @endphp

    <div class="ic-portfolio-grid">
      @foreach($portfolio as $port)
        <div class="ic-portfolio-card">
          <img src="{{ str_starts_with($port['image'] ?? '', 'http') ? $port['image'] : asset($port['image']) }}" alt="{{ $port['title'] }}" class="ic-portfolio-img">
          <div class="ic-portfolio-overlay">
            <span class="ic-portfolio-cat">{{ $port['category'] ?? '' }}</span>
            <h3 class="ic-heading-serif ic-portfolio-title">{{ $port['title'] ?? '' }}</h3>
            <p class="ic-portfolio-desc">{{ $port['desc'] ?? '' }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
