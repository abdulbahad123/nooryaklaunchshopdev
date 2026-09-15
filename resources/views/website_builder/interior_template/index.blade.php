@extends('website_builder.interior_template.layout')

@section('title', 'InteriorCRAFT - Bespoke Architecture & Interior Design Studio')

@section('content')

<!-- ===== HERO SECTION ===== -->
<section class="ic-hero">
  <div class="ic-container">
    <div class="ic-hero-grid">
      <div>
        <span class="ic-sub-badge">
          <i class="fa-solid fa-gem"></i> {{ $interior->hero_badge ?? 'BESPOKE INTERIOR DESIGN & ARCHITECTURE' }}
        </span>
        <h1 class="ic-heading-serif ic-hero-title">
          {!! nl2br(e($interior->hero_title ?? "Crafting Living\nSpaces Into Timeless\nWorks of Art")) !!}
        </h1>
        <p class="ic-hero-subtitle">
          {{ $interior->hero_subtitle ?? 'We specialize in luxury residential, commercial, and architectural spatial planning that reflects your unique lifestyle and functional elegance.' }}
        </p>

        <div class="ic-hero-btns">
          <a href="{{ $interior->primary_btn_url ?? '#portfolio' }}" class="ic-btn ic-btn-primary">
            {{ $interior->primary_btn_text ?? 'View Our Projects' }} <i class="fa-solid fa-arrow-right"></i>
          </a>
          <a href="{{ $interior->secondary_btn_url ?? '#contact' }}" class="ic-btn ic-btn-outline">
            {{ $interior->secondary_btn_text ?? 'Book Consultation' }} <i class="fa-regular fa-calendar"></i>
          </a>
        </div>

        <div class="ic-hero-meta">
          <div class="ic-hero-meta-item">
            <span class="ic-hero-meta-num">15+</span>
            <span class="ic-hero-meta-label">Years of Excellence</span>
          </div>
          <div class="ic-hero-meta-item">
            <span class="ic-hero-meta-num">350+</span>
            <span class="ic-hero-meta-label">Completed Spaces</span>
          </div>
          <div class="ic-hero-meta-item">
            <span class="ic-hero-meta-num">28</span>
            <span class="ic-hero-meta-label">Design Awards</span>
          </div>
        </div>
      </div>

      <!-- Hero Mosaic Images -->
      <div class="ic-hero-mosaic">
        @php
          $heroMain = $interior->hero_image ?? 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1000&auto=format&fit=crop';
          $heroSub = 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=600&auto=format&fit=crop';
        @endphp
        <img src="{{ str_starts_with($heroMain, 'http') ? $heroMain : asset(ltrim($heroMain, '/')) }}"
             alt="Luxury Interior Hero" class="ic-hero-img-main">
        <img src="{{ $heroSub }}" alt="Interior Detail" class="ic-hero-img-sub">

        <div class="ic-hero-badge-float">
          <div class="ic-hero-badge-icon">
            <i class="fa-solid fa-trophy"></i>
          </div>
          <div>
            <div style="font-weight: 700; font-size: 15px;">Award Winning Studio</div>
            <div style="font-size: 12px; color: var(--ic-text-muted);">Top 10 Interior Architects 2025</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FLOATING STATS SECTION ===== -->
<div class="ic-container ic-stats-wrap">
  <div class="ic-stats-card">
    @php
      $stats = $interior->stats_data ?? [
        ['number' => '15+',   'label' => 'Years Experience', 'icon' => 'fa-building-columns'],
        ['number' => '350+', 'label' => 'Projects Completed', 'icon' => 'fa-kaaba'],
        ['number' => '99%',  'label' => 'Client Satisfaction', 'icon' => 'fa-star'],
        ['number' => '24/7', 'label' => 'Design Support',     'icon' => 'fa-headset'],
      ];
    @endphp

    @foreach($stats as $st)
      <div class="ic-stat-item">
        <div class="ic-stat-icon-box">
          <i class="fa-solid {{ $st['icon'] ?? 'fa-couch' }}"></i>
        </div>
        <div>
          <div class="ic-stat-number">{{ $st['number'] ?? $st['num'] ?? '' }}</div>
          <div class="ic-stat-label">{{ $st['label'] ?? '' }}</div>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- ===== SERVICES SECTION ===== -->
<section id="services" class="ic-section">
  <div class="ic-container">
    <div class="ic-section-header">
      <span class="ic-sub-badge">WHAT WE DO</span>
      <h2 class="ic-heading-serif ic-section-title">Our Interior Design Services</h2>
      <p class="ic-section-subtitle">
        From conceptual spatial design to turnkey installation, we offer end-to-end interior architectural services tailored to your aesthetic vision.
      </p>
    </div>

    <div class="ic-services-grid">
      @php
        $services = $interior->services_data ?? [
          [
            'title' => 'Residential Design',
            'desc'  => 'Bespoke living rooms, luxury master suites, modern kitchens, and private estate interiors.',
            'image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=600&auto=format&fit=crop'
          ],
          [
            'title' => 'Commercial Architecture',
            'desc'  => 'Sophisticated office spaces, luxury retail boutiques, hospitality suites, and corporate lounges.',
            'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600&auto=format&fit=crop'
          ],
          [
            'title' => 'Space Planning & Layout',
            'desc'  => 'Optimizing spatial ergonomics, natural light flow, structural layouts, and functional zoning.',
            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop'
          ],
          [
            'title' => 'Custom Furniture & Styling',
            'desc'  => 'Handcrafted timber pieces, curated textiles, custom lighting fixtures, and art curation.',
            'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=600&auto=format&fit=crop'
          ],
        ];
      @endphp

      @foreach($services as $srv)
        <div class="ic-service-card">
          <div class="ic-service-img-wrap">
            <img src="{{ str_starts_with($srv['image'] ?? '', 'http') ? $srv['image'] : asset($srv['image']) }}" alt="{{ $srv['title'] }}" class="ic-service-img">
          </div>
          <div class="ic-service-content">
            <h3 class="ic-heading-serif ic-service-title">{{ $srv['title'] ?? '' }}</h3>
            <p class="ic-service-desc">{{ $srv['desc'] ?? '' }}</p>
            <a href="{{ $contactUrl }}" class="ic-service-link">
              Explore Service <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== PORTFOLIO / FEATURED WORK ===== -->
<section id="portfolio" class="ic-section" style="background: #F4F2EE;">
  <div class="ic-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <span class="ic-sub-badge">FEATURED WORK</span>
        <h2 class="ic-heading-serif ic-section-title mb-0">Our Signature Portfolio</h2>
      </div>
      <a href="{{ $portfolioUrl }}" class="ic-btn ic-btn-outline">
        View All Projects <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

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

<!-- ===== TESTIMONIALS SECTION ===== -->
<section class="ic-section">
  <div class="ic-container">
    <div class="ic-section-header">
      <span class="ic-sub-badge">CLIENT TESTIMONIALS</span>
      <h2 class="ic-heading-serif ic-section-title">Words From Discerning Clients</h2>
      <p class="ic-section-subtitle">
        We take pride in turning dream visions into tangible architectural realities.
      </p>
    </div>

    @php
      $testimonials = $interior->testimonials_data ?? [
        [
          'name'    => 'Eleanor Vance',
          'role'    => 'Homeowner, Manhattan Penthouse',
          'comment' => 'InteriorCRAFT transformed our raw penthouse shell into a warm, breathtaking sanctuary. Their attention to custom wood detailing and lighting flow is unparalleled.',
          'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'
        ],
        [
          'name'    => 'Marcus Sterling',
          'role'    => 'Founder, Sterling Capital',
          'comment' => 'From initial 3D renderings to final furniture delivery, the execution was flawless. Our corporate headquarters now radiates prestige and ergonomic comfort.',
          'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'
        ],
        [
          'name'    => 'Sophia Dupont',
          'role'    => 'Boutique Hotel Owner',
          'comment' => 'The team understood our brand heritage immediately. Guests constantly compliment the atmospheric interior design and bespoke furniture fixtures.',
          'avatar'  => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=200&auto=format&fit=crop'
        ],
      ];
    @endphp

    <div class="row g-4">
      @foreach($testimonials as $t)
        <div class="col-lg-4 col-md-6">
          <div class="ic-testimonial-card h-100">
            <div class="ic-quote-icon"><i class="fa-solid fa-quote-left"></i></div>
            <p class="ic-testimonial-text">"{{ $t['comment'] }}"</p>
            <div class="ic-testimonial-user">
              <img src="{{ $t['avatar'] }}" alt="{{ $t['name'] }}" class="ic-testimonial-avatar">
              <div>
                <h4 class="ic-testimonial-name">{{ $t['name'] }}</h4>
                <p class="ic-testimonial-role">{{ $t['role'] }}</p>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== BLOG / JOURNAL SECTION ===== -->
<section class="ic-section" style="background: #F9F8F6;">
  <div class="ic-container">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
      <div>
        <span class="ic-sub-badge">DESIGN JOURNAL</span>
        <h2 class="ic-heading-serif ic-section-title mb-0">Latest Articles & Design Ideas</h2>
      </div>
      <a href="{{ $contactUrl }}" class="ic-btn ic-btn-outline">
        View All Articles <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    @php
      $blogs = $interior->blogs_data ?? [
        [
          'title'   => 'Integrating Natural Light & Sustainable Timber in Modern Living Rooms',
          'date'    => 'SEP 12, 2026',
          'excerpt' => 'Explore how organic textures, biophilic accents, and passive light channels enhance spatial well-being.',
          'image'   => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=600&auto=format&fit=crop'
        ],
        [
          'title'   => 'The Art of Color Harmony: Selecting Earthy Tones for Living Spaces',
          'date'    => 'AUG 28, 2026',
          'excerpt' => 'Why warm muted greens, terracotta, and soft beige create enduring elegance in luxury residences.',
          'image'   => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=600&auto=format&fit=crop'
        ],
        [
          'title'   => 'Bespoke Furniture vs. Off-The-Shelf: Maximizing Spatial Potential',
          'date'    => 'AUG 10, 2026',
          'excerpt' => 'How custom joinery and tailored cabinetry eliminate awkward corners and optimize ergonomics.',
          'image'   => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=600&auto=format&fit=crop'
        ],
      ];
    @endphp

    <div class="ic-blog-grid">
      @foreach($blogs as $b)
        <div class="ic-blog-card">
          <div class="ic-blog-img-wrap">
            <img src="{{ str_starts_with($b['image'] ?? '', 'http') ? $b['image'] : asset($b['image']) }}" alt="{{ $b['title'] }}" class="ic-blog-img">
            <span class="ic-blog-date">{{ $b['date'] }}</span>
          </div>
          <div class="ic-blog-content">
            <h3 class="ic-heading-serif ic-blog-title"><a href="{{ $contactUrl }}">{{ $b['title'] }}</a></h3>
            <p class="ic-blog-excerpt">{{ $b['excerpt'] }}</p>
            <a href="{{ $contactUrl }}" class="ic-service-link">
              Read Article <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
