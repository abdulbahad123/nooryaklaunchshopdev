@extends('website_builder.evently_theme.layout')

@section('title', ($interior->site_title ?? 'Evently') . ' - Events Beyond Expectations')

@section('styles')
<style>
/* ===== UPCOMING EVENTS SECTION ===== */
.ev-upcoming-section { padding: 72px 0; background: #ffffff; }

.ev-event-date-card {
  background: #ffffff;
  border-radius: 18px;
  overflow: hidden;
  border: 1px solid var(--ev-border-light);
  box-shadow: var(--ev-shadow-sm);
  transition: var(--ev-transition);
  height: 100%;
}

.ev-event-date-card:hover { transform: translateY(-5px); box-shadow: var(--ev-shadow-md); }

.ev-event-img-wrap { position: relative; height: 200px; overflow: hidden; }
.ev-event-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.ev-event-date-card:hover .ev-event-img { transform: scale(1.06); }

.ev-event-date-badge {
  position: absolute;
  top: 14px;
  left: 14px;
  background: #ffffff;
  border-radius: 12px;
  padding: 8px 12px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
  min-width: 52px;
}
.ev-event-date-num { font-size: 22px; font-weight: 800; color: var(--ev-primary); line-height: 1; }
.ev-event-date-month { font-size: 10px; font-weight: 700; color: var(--ev-text-muted); text-transform: uppercase; letter-spacing: 1px; }

.ev-event-body { padding: 18px 18px 16px; }
.ev-event-location { font-size: 12px; color: var(--ev-text-muted); margin-bottom: 8px; display: flex; align-items: center; gap: 4px; }
.ev-event-name { font-size: 16px; font-weight: 800; color: var(--ev-text-dark); margin-bottom: 10px; font-family: var(--ev-font-heading); line-height: 1.3; }

.ev-event-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
.ev-event-tag {
  font-size: 11px; font-weight: 600;
  padding: 3px 10px; border-radius: 30px;
  background: var(--ev-badge-bg); color: var(--ev-primary);
}

.ev-event-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding-top: 10px; border-top: 1px solid var(--ev-border-light);
}
.ev-event-link {
  width: 30px; height: 30px; border-radius: 50%;
  background: var(--ev-primary); color: #ffffff;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 12px; text-decoration: none; transition: var(--ev-transition);
}
.ev-event-date-card:hover .ev-event-link { background: var(--ev-primary-dark); transform: rotate(-45deg); }

/* ===== WHY EVENTLY SECTION ===== */
.ev-why-evently { padding: 72px 0; background: var(--ev-bg-light); }

.ev-why-grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}

.ev-why-features-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-top: 28px;
}

.ev-why-feat-box {
  background: #ffffff;
  border-radius: 16px;
  padding: 20px;
  border: 1px solid var(--ev-border-light);
  transition: var(--ev-transition);
}
.ev-why-feat-box:hover { border-color: var(--ev-primary-light); box-shadow: var(--ev-shadow-sm); }

.ev-why-feat-icon {
  width: 44px; height: 44px;
  border-radius: 12px;
  background: var(--ev-badge-bg);
  color: var(--ev-primary);
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; margin-bottom: 12px;
}

.ev-why-feat-title { font-size: 14px; font-weight: 800; color: var(--ev-text-dark); margin-bottom: 4px; }
.ev-why-feat-desc { font-size: 12px; color: var(--ev-text-muted); line-height: 1.55; margin: 0; }

.ev-why-right-img {
  position: relative;
  border-radius: var(--ev-radius-lg);
  overflow: hidden;
  height: 480px;
}

.ev-why-right-img img {
  width: 100%; height: 100%; object-fit: cover;
}

.ev-why-img-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(15,11,30,0.7) 0%, rgba(15,11,30,0.1) 60%, transparent 100%);
}

.ev-why-cursive-block {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  z-index: 2;
}

.ev-why-cursive-text {
  font-family: var(--ev-font-cursive);
  font-size: 36px;
  color: rgba(255,255,255,0.95);
  text-shadow: 0 2px 20px rgba(0,0,0,0.5);
  line-height: 1.25;
}

.ev-play-btn-overlay {
  position: absolute;
  bottom: 28px;
  right: 28px;
  width: 56px; height: 56px;
  border-radius: 50%;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(8px);
  border: 2px solid rgba(255,255,255,0.4);
  color: #ffffff;
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; cursor: pointer;
  transition: var(--ev-transition);
  text-decoration: none;
  z-index: 3;
}
.ev-play-btn-overlay:hover { background: var(--ev-primary); border-color: var(--ev-primary); color: #ffffff; }

/* ===== TESTIMONIALS ===== */
.ev-testi-nav { display: flex; gap: 8px; }

/* ===== DARK CTA BANNER ===== */
.ev-dark-cta {
  background: linear-gradient(135deg, #0F0B1E 0%, #1A1230 50%, #2D1B6B 100%);
  padding: 64px 0;
  position: relative;
  overflow: hidden;
}

.ev-dark-cta::before {
  content: '';
  position: absolute;
  top: -100px; right: -80px;
  width: 340px; height: 340px;
  border-radius: 50%;
  background: rgba(108, 60, 225, 0.15);
}

.ev-dark-cta-grid {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 40px;
  align-items: center;
}

.ev-dark-cta-eyebrow {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--ev-primary-light);
  margin-bottom: 10px;
}

.ev-dark-cta-title {
  font-family: var(--ev-font-heading);
  font-size: clamp(28px, 4vw, 48px);
  font-weight: 800;
  color: #ffffff;
  margin-bottom: 12px;
  line-height: 1.1;
}

.ev-dark-cta-title span { color: var(--ev-primary-light); }

.ev-dark-cta-sub {
  font-size: 15px;
  color: rgba(255,255,255,0.7);
  margin-bottom: 32px;
  max-width: 520px;
  line-height: 1.6;
}

.ev-cta-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 28px;
}

.ev-cta-badge-item {
  display: flex;
  align-items: center;
  gap: 8px;
  color: rgba(255,255,255,0.8);
  font-size: 13px;
  font-weight: 600;
}

.ev-cta-badge-icon {
  width: 32px; height: 32px;
  border-radius: 50%;
  background: rgba(108, 60, 225, 0.3);
  border: 1px solid rgba(108, 60, 225, 0.5);
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; color: var(--ev-primary-light);
  flex-shrink: 0;
}

.ev-dark-cta-right {
  font-family: var(--ev-font-cursive);
  font-size: 52px;
  color: rgba(255,255,255,0.12);
  text-align: right;
  line-height: 1.1;
  white-space: nowrap;
  position: relative;
  z-index: 1;
}

@media (max-width: 991.98px) {
  .ev-why-grid-2 { grid-template-columns: 1fr; gap: 36px; }
  .ev-dark-cta-grid { grid-template-columns: 1fr; }
  .ev-dark-cta-right { display: none; }
  .ev-why-right-img { height: 320px; }
  .ev-why-features-grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 575.98px) {
  .ev-why-features-grid { grid-template-columns: 1fr; }
  .ev-cta-badges { gap: 12px; }
}
</style>
@endsection

@section('content')

@php
  $evData = $interior ?? $agency ?? null;
  $subdomainParam = isset($subdomain) && $subdomain ? $subdomain : null;

  $homeUrl      = $subdomainParam ? route('website-builder.subdomain.site',      ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently');
  $aboutUrl     = $subdomainParam ? route('website-builder.subdomain.about',     ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.about');
  $contactUrl   = $subdomainParam ? route('website-builder.subdomain.contact',   ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.contact');
  $portfolioUrl = $subdomainParam ? route('website-builder.subdomain.portfolio', ['subdomain' => $subdomainParam]) : route('website-builder.templates.evently.portfolio');

  $defaultHero = asset('assets/website_builder/Templates/Evently/hero_banner.png');
  $heroImg = $evData->hero_image ?? '';
  $isDefault = empty($heroImg) || str_contains($heroImg, 'unsplash.com') || str_contains($heroImg, 'agency_template') || str_contains($heroImg, 'herobanner_right') || str_contains($heroImg, 'homepage_hero');
  $heroSrc = !$isDefault ? (str_starts_with($heroImg, 'http') ? $heroImg : asset(ltrim($heroImg, '/'))) : $defaultHero;

  $heroSub = $evData->hero_subtitle ?? '';
  if (empty($heroSub) || str_contains(strtolower($heroSub), 'interior') || str_contains(strtolower($heroSub), 'functional spaces')) {
    $heroSub = 'From intimate gatherings to grand celebrations, we create unforgettable experiences tailored to your vision.';
  }
@endphp

<!-- ===== HERO SECTION (Full Cover Background Image End-to-End) ===== -->
<section class="ev-hero-full" style="background: url('{{ $heroSrc }}') no-repeat center center / cover; min-height: 580px; position: relative;">
  <div class="ev-hero-full-overlay"></div>

  <!-- Top Right Cursive Overlay -->
  <div class="ev-hero-script-overlay d-none d-lg-block">
    Events Create Stories
  </div>

  <div class="ev-container" style="position: relative; z-index: 2; width: 100%;">
    <div class="ev-hero-grid align-items-center">

      <!-- LEFT: Content -->
      <div class="ev-hero-content-left">
        <div class="ev-hero-badge-full">
          <span class="ev-dot-full"></span>
          Make Every Moment Extraordinary
        </div>

        <h1 class="ev-hero-title-full">
          {!! nl2br(e($evData->hero_title ?? "Events That Bring\nPeople Together")) !!}
        </h1>

        <p class="ev-hero-subtitle-full">
          {{ $heroSub }}
        </p>

        <div class="ev-hero-actions">
          <a href="{{ $evData->primary_btn_url ?? $contactUrl }}" class="ev-btn ev-btn-primary" style="padding: 14px 32px; font-size: 15px;">
            {{ $evData->primary_btn_text ?? 'Plan Your Event' }}
            <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
          <a href="{{ $portfolioUrl }}" class="ev-btn-video-full">
            <span class="ev-play-circle-full">
              <i class="fa-solid fa-play" style="font-size: 10px; margin-left: 2px;"></i>
            </span>
            Watch Our Story
          </a>
        </div>

        <!-- Trust Bar -->
        <div class="ev-hero-trust mt-4">
          <div class="ev-trust-avatars">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" alt="Client">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100&auto=format&fit=crop" alt="Client">
            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=100&auto=format&fit=crop" alt="Client">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=100&auto=format&fit=crop" alt="Client">
          </div>
          <div class="ev-trust-text-full">
            Trusted by <strong>10K+</strong> Happy Clients
          </div>
        </div>
      </div>

      <!-- RIGHT: Translucent Floating Feature Cards -->
      <div class="ev-hero-cards-right d-none d-lg-flex flex-column gap-3 align-items-end">
        
        <!-- Badge 1: Top Floating Badge -->
        <div class="ev-glass-card">
          <div class="ev-glass-icon"><i class="fa-solid fa-heart"></i></div>
          <div>
            <div class="ev-glass-title">Memorable Events</div>
            <div class="ev-glass-sub">Tailored to your vision</div>
          </div>
        </div>

        <!-- Badge 2: Middle Floating Badge -->
        <div class="ev-glass-card">
          <div class="ev-glass-icon"><i class="fa-regular fa-calendar-check"></i></div>
          <div>
            <div class="ev-glass-title">Celebrations & Conferences</div>
            <div class="ev-glass-sub">Weddings, galas & trade shows</div>
          </div>
        </div>

        <!-- Badge 3: Bottom Floating Badge -->
        <div class="ev-glass-card">
          <div class="ev-glass-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
          <div class="d-flex align-items-center gap-2">
            <div>
              <div class="ev-glass-title">Turning Ideas Into Reality</div>
              <div class="ev-glass-sub">Extraordinary experiences</div>
            </div>
            <a href="{{ $contactUrl }}" class="ev-glass-arrow">
              <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>

<!-- ===== FEATURES + STATS BAR ===== -->
<section class="ev-features-bar">
  <div class="ev-container">
    <div class="row g-4 align-items-stretch">
      
      <!-- LEFT: 4 Features Box -->
      <div class="col-12 col-xl-7">
        <div class="ev-features-pill-card">
          @php
            $impactCards = $evData->impact_features_data ?? [
              ['title' => "Creative\nPlanning",   'icon' => 'fa-wand-magic-sparkles'],
              ['title' => "Dedicated\nSupport",   'icon' => 'fa-users-gear'],
              ['title' => "Customizable\nPackages", 'icon' => 'fa-box-open'],
              ['title' => "Seamless\nExecution",  'icon' => 'fa-heart'],
            ];
          @endphp
          <div class="row g-3 w-100">
            @foreach($impactCards as $impCard)
              <div class="col-6 col-sm-3">
                <div class="ev-feature-item">
                  <div class="ev-feature-icon"><i class="fa-solid {{ $impCard['icon'] ?? 'fa-star' }}"></i></div>
                  <div class="ev-feature-title">{!! nl2br(e($impCard['title'] ?? '')) !!}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- RIGHT: Dark Impact Stats Box -->
      <div class="col-12 col-xl-5">
        <div class="ev-stats-dark-card">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <div class="ev-stats-dark-label">OUR IMPACT</div>
              <div class="ev-stats-dark-title mb-0">In Numbers</div>
            </div>
            <a href="{{ $portfolioUrl }}" class="ev-btn-outline-white">
              View More <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>

          @php
            $stats = $evData->stats_data ?? [
              ['number' => '500+', 'label' => 'Events Managed',    'icon' => 'fa-calendar-check'],
              ['number' => '50K+', 'label' => 'Happy Attendees',   'icon' => 'fa-users'],
              ['number' => '98%',  'label' => 'Client Satisfaction','icon' => 'fa-star'],
              ['number' => '15+',  'label' => 'Years of Experience','icon' => 'fa-trophy'],
            ];
          @endphp

          <div class="ev-stats-dark-grid">
            @foreach($stats as $st)
              <div class="ev-stat-item-dark">
                <div class="ev-stat-icon-dark"><i class="fa-solid {{ $st['icon'] ?? 'fa-star' }}"></i></div>
                <div class="ev-stat-num-dark ev-counter-num" data-target="{{ $st['number'] ?? '' }}">{{ $st['number'] ?? '' }}</div>
                <div class="ev-stat-lbl-dark">{{ $st['label'] ?? '' }}</div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ===== EVENT CATEGORIES ===== -->
<section class="ev-categories-section" id="events">
  <div class="ev-container">
    <div class="ev-categories-top">
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> {{ $evData->services_badge ?? 'Event Categories' }}</div>
        <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">
          {!! nl2br(e($evData->services_title ?? "Explore Events\nFor Every Occasion")) !!}
        </h2>
      </div>
      <div>
        <p class="ev-section-subtitle" style="margin-bottom: 16px;">Whatever the occasion, we have the expertise to make it extraordinary.</p>
        <div class="ev-nav-arrows">
          <button class="ev-arrow-btn" id="catPrev" type="button"><i class="fa-solid fa-arrow-left"></i></button>
          <button class="ev-arrow-btn" id="catNext" type="button"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
      </div>
    </div>

    @php
      $rawPortfolio = $evData->portfolio_data ?? [];
      $eventCategories = [];
      if (!empty($rawPortfolio) && is_array($rawPortfolio)) {
        foreach ($rawPortfolio as $catItem) {
          $t = strtolower($catItem['title'] ?? '');
          if (!preg_match('/(living|kitchen|bedroom|office|bathroom|interior|home styling|restaurant|retail)/i', $t)) {
            $eventCategories[] = $catItem;
          }
        }
      }

      if (empty($eventCategories)) {
        $eventCategories = [
          ['title' => 'Corporate Events',        'desc' => 'Conferences, seminars, product launches and more.', 'image' => asset('assets/website_builder/Templates/Evently/event_business_summit.png'), 'icon' => 'fa-building'],
          ['title' => 'Weddings & Private Events','desc' => 'Make your special day truly unforgettable.',       'image' => asset('assets/website_builder/Templates/Evently/event_grand_wedding.png'), 'icon' => 'fa-heart'],
          ['title' => 'Social Gatherings',       'desc' => 'Birthdays, anniversaries and celebrations.',        'image' => asset('assets/website_builder/Templates/Evently/event_music_fest.png'), 'icon' => 'fa-champagne-glasses'],
          ['title' => 'Exhibitions & Trade Shows','desc' => 'Showcase your brand to the world.',               'image' => asset('assets/website_builder/Templates/Evently/event_corporate_gala.png'), 'icon' => 'fa-store'],
        ];
      }
    @endphp

    <div class="row g-3 g-md-4 ev-mobile-slider" id="catSlider">
      @foreach($eventCategories as $cat)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ev-cat-card">
            <img src="{{ str_starts_with($cat['image'] ?? '', 'http') ? ($cat['image'] ?? '') : (str_starts_with($cat['image'] ?? '', asset('')) ? ($cat['image'] ?? '') : asset(ltrim($cat['image'] ?? '', '/'))) }}"
                 alt="{{ $cat['title'] ?? '' }}"
                 onerror="this.src='{{ asset('assets/website_builder/Templates/Evently/event_corporate_gala.png') }}';"
                 class="ev-cat-img">
            <div class="ev-cat-overlay"></div>
            <div class="ev-cat-content">
              <div class="ev-cat-title">{{ $cat['title'] ?? '' }}</div>
              <div class="ev-cat-desc">{{ $cat['desc'] ?? $cat['description'] ?? '' }}</div>
              <a href="{{ $portfolioUrl }}" class="ev-cat-link">
                <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== UPCOMING EVENTS ===== -->
<section class="ev-upcoming-section" id="upcoming-events">
  <div class="ev-container">
    <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-5">
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> Upcoming Events</div>
        <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">
          Events You<br><span style="color: var(--ev-primary); font-style: italic;">Would Love</span>
        </h2>
        <p class="ev-section-subtitle mb-0" style="max-width: 420px;">
          {{ $evData->upcoming_desc ?? 'Discover and be a part of our upcoming events. From business conferences to gala nights, there\'s always something exciting happening.' }}
        </p>
      </div>
      <a href="{{ $portfolioUrl }}" class="ev-btn ev-btn-primary">
        View All Events <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    @php
      $upcomingEvents = $evData->events_data ?? $evData->upcoming_events_data ?? [
        [
          'title'    => 'Business Growth Summit 2024',
          'location' => 'New York, USA',
          'date_day' => '24',
          'date_month' => 'AUG',
          'categories' => 'Conference, Business',
          'image'    => asset('assets/website_builder/Templates/Evently/event_business_summit.png'),
        ],
        [
          'title'    => 'The Grand Wedding Expo',
          'location' => 'Los Angeles, USA',
          'date_day' => '15',
          'date_month' => 'SEP',
          'categories' => 'Wedding, Exhibition',
          'image'    => asset('assets/website_builder/Templates/Evently/event_grand_wedding.png'),
        ],
        [
          'title'    => 'Music Fest 2024',
          'location' => 'Chicago, USA',
          'date_day' => '10',
          'date_month' => 'OCT',
          'categories' => 'Concert, Entertainment',
          'image'    => asset('assets/website_builder/Templates/Evently/event_music_fest.png'),
        ],
        [
          'title'    => 'Annual Corporate Gala Night',
          'location' => 'Miami, USA',
          'date_day' => '22',
          'date_month' => 'NOV',
          'categories' => 'Corporate, Networking',
          'image'    => asset('assets/website_builder/Templates/Evently/event_corporate_gala.png'),
        ],
      ];
    @endphp

    <div class="row g-4 ev-mobile-slider">
      @foreach($upcomingEvents as $ev)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ev-event-date-card">
            <div class="ev-event-img-wrap">
              <img src="{{ str_starts_with($ev['image'] ?? '', 'http') ? ($ev['image'] ?? '') : ($ev['image'] ?? asset('assets/website_builder/Templates/Evently/event_corporate_gala.png')) }}"
                   alt="{{ $ev['title'] ?? '' }}"
                   onerror="this.src='{{ asset('assets/website_builder/Templates/Evently/event_corporate_gala.png') }}';"
                   class="ev-event-img">
              <div class="ev-event-date-badge">
                <div class="ev-event-date-num">{{ $ev['date_num'] ?? '01' }}</div>
                <div class="ev-event-date-month">{{ $ev['date_mon'] ?? 'JAN' }}</div>
              </div>
            </div>
            <div class="ev-event-body">
              <div class="ev-event-location">
                <i class="fa-solid fa-location-dot" style="color: var(--ev-primary);"></i>
                {{ $ev['location'] ?? 'TBD' }}
              </div>
              <div class="ev-event-name">{{ $ev['title'] ?? '' }}</div>
              <div class="ev-event-tags">
                @foreach(($ev['tags'] ?? []) as $tag)
                  <span class="ev-event-tag">{{ $tag }}</span>
                @endforeach
              </div>
              <div class="ev-event-footer">
                <span class="text-muted" style="font-size: 12px; font-weight: 600;">Register Now</span>
                <a href="{{ $contactUrl }}" class="ev-event-link">
                  <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== WHY EVENTLY / CREATING MOMENTS (Task 1 Reference Match) ===== -->
<section class="ev-why-redesign-section" id="why-evently">
  <div class="ev-container">
    <div class="row g-4 align-items-stretch">

      <!-- LEFT: Image Card with Dark Overlay + Features Bullets + Watch Video -->
      <div class="col-12 col-xl-7">
        <div class="ev-why-left-card" style="background: url('{{ asset('assets/website_builder/Templates/Evently/why_evently.png') }}') no-repeat center / cover;">
          <div class="ev-why-left-overlay"></div>

          <!-- Top Right Cursive Script -->
          <div class="ev-why-script-text d-none d-sm-block">
            Your Vision<br>Our Passion
          </div>

          <div class="ev-why-left-content">
            <div class="ev-why-eyebrow">WHY CHOOSE EVENTLY</div>
            <h2 class="ev-why-title-white">
              More Than Events,<br>We Create Experiences
            </h2>
            <p class="ev-why-desc-white">
              {{ $evData->why_desc ?? 'We combine creativity, expertise, and passion to deliver events that leave a lasting impression.' }}
            </p>

            <div class="ev-why-bullets-grid">
              <div class="ev-why-bullet-item">
                <div class="ev-why-bullet-icon"><i class="fa-solid fa-bullseye"></i></div>
                <span>Personalized Planning</span>
              </div>
              <div class="ev-why-bullet-item">
                <div class="ev-why-bullet-icon"><i class="fa-solid fa-lightbulb"></i></div>
                <span>Innovative Concepts</span>
              </div>
              <div class="ev-why-bullet-item">
                <div class="ev-why-bullet-icon"><i class="fa-solid fa-handshake"></i></div>
                <span>Trusted Network</span>
              </div>
              <div class="ev-why-bullet-item">
                <div class="ev-why-bullet-icon"><i class="fa-solid fa-scale-balanced"></i></div>
                <span>Stress-Free Execution</span>
              </div>
            </div>

            <a href="{{ $portfolioUrl }}" class="ev-btn-watch-video">
              <span class="ev-play-circle-lg">
                <i class="fa-solid fa-play" style="margin-left: 2px;"></i>
              </span>
              Watch Video
            </a>
          </div>
        </div>
      </div>

      <!-- RIGHT: Upcoming Event Highlight Card -->
      <div class="col-12 col-xl-5">
        <div class="ev-upcoming-card-right">
          <div>
            <div class="ev-pill-badge mb-2"><span class="ev-dot"></span> UPCOMING EVENT HIGHLIGHT</div>
            <h3 class="ev-heading fs-3 mb-2" style="font-family: var(--ev-font-heading);">
              Global Business Summit 2024
            </h3>

            <div class="text-muted small fw-semibold mb-3 d-flex align-items-center gap-3">
              <span><i class="fa-solid fa-location-dot me-1" style="color: var(--ev-primary);"></i> New York, USA</span>
              <span>|</span>
              <span><i class="fa-regular fa-calendar me-1" style="color: var(--ev-primary);"></i> 24 - 26 Nov 2024</span>
            </div>

            <p class="text-muted small mb-4" style="line-height: 1.6;">
              Join industry leaders, innovators, and thinkers for three days of inspiration, networking, and growth.
            </p>

            <a href="{{ $contactUrl }}" class="ev-btn ev-btn-primary mb-3">
              Register Now <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>

          <div class="ev-event-img-stage-wrap">
            <img src="{{ asset('assets/website_builder/Templates/Evently/event_speaker_stage.png') }}"
                 alt="Global Business Summit 2024"
                 onerror="this.src='{{ asset('assets/website_builder/Templates/Evently/event_business_summit.png') }}';"
                 class="ev-event-img-stage">
            <div class="ev-event-attendees-badge">
              <i class="fa-solid fa-users"></i>
              <span>500+ Attendees Expected</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section id="testimonials" class="ev-testimonials-section">
  <div class="ev-container">
    <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-5">
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> {{ $evData->testimonials_badge ?? 'Client Testimonials' }}</div>
        <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">
          {!! nl2br(e($evData->testimonials_title ?? "What Our Clients Say")) !!}
        </h2>
        <p class="ev-section-subtitle mb-0">{{ $evData->testimonials_subtitle ?? 'Real stories. Real experiences. Real smiles.' }}</p>
      </div>
      <div class="ev-testi-nav">
        <button type="button" id="testiPrev" class="ev-arrow-btn"><i class="fa-solid fa-arrow-left"></i></button>
        <button type="button" id="testiNext" class="ev-arrow-btn"><i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>

    @php
      $testimonials = $evData->testimonials_data ?? [
        ['name' => 'Daniel Carter',  'role' => 'CEO, TechNova',      'comment' => '"Evently made our corporate event seamless and truly memorable. Their attention to detail is unmatched!!"',     'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200'],
        ['name' => 'Sophia Williams','role' => 'Bride',               'comment' => '"Our dream wedding was beyond perfect. The team understood our vision and executed it beautifully."',              'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200'],
        ['name' => 'Michael Brown',  'role' => 'Marketing Head, GlobalCorp','comment' => '"Professional, creative, and reliable. Highly recommend Evently for any kind of event!!"',             'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200'],
        ['name' => 'Priya Sharma',   'role' => 'Birthday Host',       'comment' => '"My birthday party was absolutely magical. Evently turned my dream celebration into a stunning reality."',      'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200'],
        ['name' => 'James Wilson',   'role' => 'Conference Organizer','comment' => '"Everything was handled with extreme professionalism. I would 100% hire Evently again for future events."',    'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=200'],
      ];
    @endphp

    <div class="row g-4 text-start ev-mobile-slider" id="evTestiSlider">
      @foreach($testimonials as $t)
        <div class="col-12 col-md-4">
          <div class="ev-testimonial-card">
            <div class="ev-testimonial-stars">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="ev-testimonial-quote">{{ $t['comment'] ?? $t['quote'] ?? '' }}</p>
            <div class="ev-testimonial-author">
              <img src="{{ str_starts_with($t['avatar'] ?? '', 'http') ? ($t['avatar'] ?? '') : asset(ltrim($t['avatar'] ?? '', '/')) }}"
                   alt="{{ $t['name'] ?? '' }}" class="ev-testimonial-avatar"
                   onerror="this.style.display='none';">
              <div>
                <div class="ev-testimonial-name">{{ $t['name'] ?? '' }}</div>
                <div class="ev-testimonial-role">{{ $t['role'] ?? '' }}</div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== BLOG & INSIGHTS ===== -->
<section id="blog" class="ev-blog-section">
  <div class="ev-container">
    <div class="d-flex align-items-end justify-content-between mb-5 flex-wrap gap-3">
      <div>
        <div class="ev-pill-badge"><span class="ev-dot"></span> Blog & Insights</div>
        <h2 class="ev-section-title" style="font-family: var(--ev-font-heading);">Latest Event Tips & Ideas</h2>
        <p class="ev-section-subtitle mb-0">Get inspired with expert tips, trends, and creative ideas for your next event.</p>
      </div>
      <div class="d-none d-md-flex gap-2">
        <button class="ev-arrow-btn"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="ev-arrow-btn"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    </div>

    @php
      $blogs = [
        ['id'=>1,'badge'=>'Planning Tips','date'=>'Sep 12, 2024','title'=>'10 Must-Know Tips for Planning a Flawless Wedding','desc'=>'A comprehensive guide to planning your dream wedding without the stress.','image'=> asset('assets/website_builder/Templates/Evently/event_grand_wedding.png')],
        ['id'=>2,'badge'=>'Corporate Events','date'=>'Aug 28, 2024','title'=>'How to Make Your Corporate Conference Unforgettable','desc'=>'Key strategies to engage attendees and leave a lasting impression.','image'=> asset('assets/website_builder/Templates/Evently/event_business_summit.png')],
        ['id'=>3,'badge'=>'Event Trends','date'=>'Aug 15, 2024','title'=>'Top Event Decoration Trends for 2025','desc'=>'The hottest event design trends shaping celebrations this year.','image'=> asset('assets/website_builder/Templates/Evently/event_music_fest.png')],
      ];
    @endphp

    <div class="row g-4 ev-mobile-slider" id="evBlogSlider">
      @foreach($blogs as $b)
        <div class="col-12 col-md-4">
          <div class="ev-blog-card">
            <div class="ev-blog-img-wrap">
              <img src="{{ $b['image'] }}" alt="{{ $b['title'] }}" class="ev-blog-img"
                   onerror="this.src='{{ asset('assets/website_builder/Templates/Evently/event_corporate_gala.png') }}';">
              <span class="ev-blog-badge">{{ $b['badge'] }}</span>
            </div>
            <div class="ev-blog-body">
              <div class="ev-blog-date"><i class="fa-regular fa-calendar me-1"></i> {{ $b['date'] }}</div>
              <div class="ev-blog-title">{{ $b['title'] }}</div>
              <div class="ev-blog-desc d-none d-md-block">{{ $b['desc'] }}</div>
              <a href="{{ $contactUrl }}" class="ev-blog-link">Read Article <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Category slider arrows
  var catSlider = document.getElementById('catSlider');
  document.getElementById('catPrev')?.addEventListener('click', function() {
    catSlider.scrollBy({ left: -(catSlider.clientWidth / 4 + 16), behavior: 'smooth' });
  });
  document.getElementById('catNext')?.addEventListener('click', function() {
    catSlider.scrollBy({ left: catSlider.clientWidth / 4 + 16), behavior: 'smooth' });
  });

  // Testimonial slider arrows
  var testiSlider = document.getElementById('evTestiSlider');
  document.getElementById('testiPrev')?.addEventListener('click', function() {
    testiSlider?.scrollBy({ left: -(testiSlider.clientWidth / 3 + 16), behavior: 'smooth' });
  });
  document.getElementById('testiNext')?.addEventListener('click', function() {
    testiSlider?.scrollBy({ left: testiSlider.clientWidth / 3 + 16), behavior: 'smooth' });
  });
});
</script>
@endsection
