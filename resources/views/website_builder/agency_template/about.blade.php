@extends('website_builder.agency_template.layout')

@section('title', 'About Us - DesignAGENCY')

@section('content')
<!-- ===== ABOUT HERO SECTION (Ref Image 2 Match) ===== -->
<section style="background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%); padding: 70px 0 60px;">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="agency-label-pill">
          <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> About Us
        </div>
        <h1 class="agency-heading" style="font-size: clamp(34px, 5vw, 50px);">
          We Are A Creative<br>
          <span class="highlight">Digital Solutions</span> Agency
        </h1>
        <p class="agency-subtitle mb-4">
          {{ $agency->about_hero_subtitle ?? 'We help brands thrive in the digital world through innovative design, smart strategy, and cutting-edge technology.' }}
        </p>
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <a href="{{ route('website-builder.templates.design-agency') }}#portfolio" class="btn-agency-register" style="padding: 13px 28px; font-size: 14px;">
            Our Portfolio <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
          <a href="{{ route('website-builder.templates.design-agency.contact') }}" class="btn-agency-login" style="padding: 13px 26px; font-size: 14px;">
            Contact Us <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Right Image Frame with 8+ Years overlay -->
      <div class="col-lg-6">
        <div class="position-relative">
          <img src="{{ asset('assets/website_builder/agency_team_meeting.png') }}" 
               onerror="this.src='https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=800&auto=format&fit=crop';" 
               alt="DesignAGENCY Team Meeting" 
               style="width: 100%; height: auto; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">

          <!-- Experience Overlay Card -->
          <div class="position-absolute card border-0 p-3 shadow-lg" style="bottom: -20px; left: -20px; border-radius: 16px; background: #ffffff; min-width: 180px;">
            <div class="text-center">
              <h2 class="fw-extrabold mb-0 text-success" style="font-size: 36px;">8+</h2>
              <div class="small text-muted fw-bold">Years of Experience</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== OUR STORY & MISSION/VISION/VALUES ===== -->
<section style="padding: 90px 0; background: #FFFFFF;">
  <div class="container">
    <div class="row g-5">
      <!-- Left: Our Story -->
      <div class="col-lg-5">
        <div class="agency-label-pill">OUR STORY</div>
        <h2 class="agency-heading">Our Journey Started With A Simple Idea</h2>
        <p class="text-slate-600 mb-3" style="font-size: 14.5px; line-height: 1.7;">
          DesignAGENCY was founded in 2016 with a mission to empower businesses with smart digital solutions. What began as a small team of creatives has grown into a full-service agency trusted by clients worldwide.
        </p>
        <p class="text-slate-600 mb-4" style="font-size: 14.5px; line-height: 1.7;">
          We believe in building long-term relationships with our clients by delivering measurable results and exceptional experiences.
        </p>

        <!-- Signature -->
        <div class="pt-2 border-top">
          <div class="fw-bold fs-5 text-slate-900 fst-italic">Michael Roberts</div>
          <div class="small text-muted fw-semibold">Founder & CEO</div>
        </div>
      </div>

      <!-- Right: Mission, Vision, Values cards -->
      <div class="col-lg-7">
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card h-100 border-0 p-4 text-center" style="background: #F8FAFC; border-radius: 18px;">
              <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mx-auto mb-3" style="width: 54px; height: 54px; background: #ECFDF5; color: #10B981; font-size: 20px;">
                <i class="fa-solid fa-crosshairs"></i>
              </div>
              <h5 class="fw-bold fs-6 mb-2">Our Mission</h5>
              <p class="text-muted small mb-0" style="line-height: 1.55;">To deliver innovative digital solutions that help businesses grow, connect, and succeed in a competitive world.</p>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card h-100 border-0 p-4 text-center" style="background: #F8FAFC; border-radius: 18px;">
              <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mx-auto mb-3" style="width: 54px; height: 54px; background: #ECFDF5; color: #10B981; font-size: 20px;">
                <i class="fa-solid fa-eye"></i>
              </div>
              <h5 class="fw-bold fs-6 mb-2">Our Vision</h5>
              <p class="text-muted small mb-0" style="line-height: 1.55;">To be a global leader in digital innovation, known for creativity, reliability, and measurable impact.</p>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card h-100 border-0 p-4 text-start" style="background: #F8FAFC; border-radius: 18px;">
              <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3" style="width: 54px; height: 54px; background: #ECFDF5; color: #10B981; font-size: 20px;">
                <i class="fa-solid fa-gem"></i>
              </div>
              <h5 class="fw-bold fs-6 mb-2">Our Values</h5>
              <ul class="list-unstyled small text-muted mb-0 space-y-1">
                <li><i class="fa-solid fa-circle-check text-success me-1"></i> Client Success First</li>
                <li><i class="fa-solid fa-circle-check text-success me-1"></i> Innovation & Creativity</li>
                <li><i class="fa-solid fa-circle-check text-success me-1"></i> Integrity & Transparency</li>
                <li><i class="fa-solid fa-circle-check text-success me-1"></i> Quality & Excellence</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Bar -->
    <div class="card border-0 shadow-sm mt-5" style="border-radius: 20px; padding: 30px 20px; background: #F8FAFC;">
      <div class="row g-4 text-center">
        <div class="col-md-3 col-6">
          <h3 class="fw-extrabold mb-0 text-success fs-2">8+</h3>
          <div class="small text-muted fw-bold">Years of Experience</div>
        </div>
        <div class="col-md-3 col-6">
          <h3 class="fw-extrabold mb-0 text-success fs-2">250+</h3>
          <div class="small text-muted fw-bold">Projects Completed</div>
        </div>
        <div class="col-md-3 col-6">
          <h3 class="fw-extrabold mb-0 text-success fs-2">98%</h3>
          <div class="small text-muted fw-bold">Client Satisfaction</div>
        </div>
        <div class="col-md-3 col-6">
          <h3 class="fw-extrabold mb-0 text-success fs-2">50+</h3>
          <div class="small text-muted fw-bold">Expert Team Members</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== MEET OUR TEAM SECTION ===== -->
<section style="padding: 90px 0; background: #F8FAFC;">
  <div class="container">
    <div class="text-center mb-5">
      <div class="agency-label-pill mx-auto">MEET OUR TEAM</div>
      <h2 class="agency-heading">The People Behind Our Success</h2>
      <p class="agency-subtitle mx-auto">Our team is made up of passionate creatives, strategists, and problem-solvers who love turning ideas into reality.</p>
    </div>

    <div class="row g-4">
      @php
        $team = $agency->team_members_data ?? [
          ['name' => 'Michael Roberts', 'role' => 'Founder & CEO',       'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop'],
          ['name' => 'Sarah Johnson',   'role' => 'Creative Director',    'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'],
          ['name' => 'Daniel Smith',    'role' => 'Head of Development', 'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop'],
          ['name' => 'Jessica Brown',   'role' => 'Marketing Manager',    'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=400&auto=format&fit=crop'],
        ];
      @endphp

      @foreach($team as $m)
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 h-100 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div style="height: 240px; overflow: hidden; background: #0F172A;">
              <img src="{{ asset($m['image']) }}" 
                   onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop';"
                   alt="{{ $m['name'] }}" 
                   style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
            </div>
            <div class="p-3 text-center bg-white">
              <h5 class="fw-bold fs-6 mb-1 text-slate-900">{{ $m['name'] }}</h5>
              <div class="text-muted mb-3" style="font-size: 12.5px; font-weight: 600;">{{ $m['role'] }}</div>
              <div class="d-flex justify-content-center gap-2">
                <a href="#" class="btn btn-light btn-sm rounded-circle"><i class="fa-brands fa-facebook-f text-muted"></i></a>
                <a href="#" class="btn btn-light btn-sm rounded-circle"><i class="fa-brands fa-x-twitter text-muted"></i></a>
                <a href="#" class="btn btn-light btn-sm rounded-circle"><i class="fa-brands fa-linkedin-in text-muted"></i></a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
