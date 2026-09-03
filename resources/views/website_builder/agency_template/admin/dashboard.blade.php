@extends('website_builder.agency_template.admin.layout')

@section('title', 'DesignAGENCY Template Admin - Content & Media Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1"><i class="fa-solid fa-palette text-success me-2"></i>DesignAGENCY Template Admin Dashboard</h3>
    <p class="text-muted small mb-0">Manage multipage template content, images, services, portfolio, team, and contact info dynamically.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('website-builder.templates.design-agency') }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
      <i class="fa-solid fa-external-link me-1"></i> Preview Live Home
    </a>
    <a href="{{ route('website-builder.templates.design-agency.about') }}" target="_blank" class="btn btn-outline-secondary btn-sm fw-bold">
      <i class="fa-solid fa-users me-1"></i> About Us
    </a>
    <a href="{{ route('website-builder.templates.design-agency.contact') }}" target="_blank" class="btn btn-outline-secondary btn-sm fw-bold">
      <i class="fa-solid fa-envelope me-1"></i> Contact Us
    </a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST">
  @csrf

  <!-- EDITOR NAV TABS -->
  <ul class="nav nav-pills bg-white p-2 rounded-4 shadow-sm mb-4 gap-2 border" id="agencyAdminTabs" role="tablist">
    <li class="nav-item">
      <button class="nav-link active fw-bold small" id="tab-general-btn" data-bs-toggle="tab" data-bs-target="#tab-general" type="button">
        <i class="fa-solid fa-sliders me-1"></i> General & Branding
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link fw-bold small" id="tab-hero-btn" data-bs-toggle="tab" data-bs-target="#tab-hero" type="button">
        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Hero Section
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link fw-bold small" id="tab-services-btn" data-bs-toggle="tab" data-bs-target="#tab-services" type="button">
        <i class="fa-solid fa-grid-2 me-1"></i> Services (6 Items)
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link fw-bold small" id="tab-portfolio-btn" data-bs-toggle="tab" data-bs-target="#tab-portfolio" type="button">
        <i class="fa-solid fa-briefcase me-1"></i> Portfolio Projects
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link fw-bold small" id="tab-about-btn" data-bs-toggle="tab" data-bs-target="#tab-about" type="button">
        <i class="fa-solid fa-users me-1"></i> About & Team
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link fw-bold small" id="tab-contact-btn" data-bs-toggle="tab" data-bs-target="#tab-contact" type="button">
        <i class="fa-solid fa-envelope me-1"></i> Contact & FAQs
      </button>
    </li>
  </ul>

  <!-- TAB PANELS CONTENT -->
  <div class="tab-content" id="agencyAdminTabContent">

    <!-- ===== TAB 1: GENERAL & BRANDING ===== -->
    <div class="tab-pane fade show active" id="tab-general">
      <div class="card card-editor p-4">
        <h5 class="fw-bold mb-3"><i class="fa-solid fa-globe text-success me-2"></i>Header, Contact & Branding</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold small">Site Brand Name</label>
            <input type="text" class="form-control" name="site_title" value="{{ $agency->site_title ?? 'DesignAGENCY' }}">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small">Top Announcement Text</label>
            <input type="text" class="form-control" name="top_announcement" value="{{ $agency->top_announcement ?? 'We help businesses grow with creative digital solutions.' }}">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Support Email</label>
            <input type="email" class="form-control" name="email" value="{{ $agency->email ?? 'info@designagency.com' }}">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Support Phone</label>
            <input type="text" class="form-control" name="phone" value="{{ $agency->phone ?? '+1 (234) 567-890' }}">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Office Address</label>
            <input type="text" class="form-control" name="address" value="{{ $agency->address ?? '123 Design Street, Creative City, CA 90403' }}">
          </div>
          <div class="col-md-12">
            <label class="form-label fw-semibold small">Footer Description Text</label>
            <textarea class="form-control" name="footer_text" rows="2">{{ $agency->footer_text ?? 'We are a creative digital agency helping businesses grow with modern design, development & marketing solutions.' }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== TAB 2: HERO SECTION ===== -->
    <div class="tab-pane fade" id="tab-hero">
      <div class="card card-editor p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="fa-solid fa-star text-success me-2"></i>Main Hero Section Content</h5>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Hero Label Pill</label>
            <input type="text" class="form-control" name="hero_badge" value="{{ $agency->hero_badge ?? 'Creative Digital Solutions' }}">
          </div>
          <div class="col-md-8">
            <label class="form-label fw-semibold small">Hero Main Title (use line breaks)</label>
            <textarea class="form-control" name="hero_title" rows="2">{{ $agency->hero_title ?? "Increase Your\nCustomers Loyalty\nand Satisfaction" }}</textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label fw-semibold small">Hero Subtitle Paragraph</label>
            <textarea class="form-control" name="hero_subtitle" rows="2">{{ $agency->hero_subtitle ?? 'We help businesses like yours earn more customers, stand out from competitors, and grow your revenue.' }}</textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label fw-semibold small">Hero Graphic / Photo Asset URL</label>
            <input type="text" class="form-control" name="hero_image" value="{{ $agency->hero_image ?? 'assets/website_builder/agency_hero_woman.png' }}" placeholder="assets/website_builder/agency_hero_woman.png">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold small">Primary Button Text</label>
            <input type="text" class="form-control" name="primary_btn_text" value="{{ $agency->primary_btn_text ?? 'Get Started' }}">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold small">Primary Button Link</label>
            <input type="text" class="form-control" name="primary_btn_url" value="{{ $agency->primary_btn_url ?? '#contact' }}">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold small">Secondary Button Text</label>
            <input type="text" class="form-control" name="secondary_btn_text" value="{{ $agency->secondary_btn_text ?? 'View Our Work' }}">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold small">Secondary Button Link</label>
            <input type="text" class="form-control" name="secondary_btn_url" value="{{ $agency->secondary_btn_url ?? '#portfolio' }}">
          </div>
        </div>
      </div>
    </div>

    <!-- ===== TAB 3: SERVICES MANAGER ===== -->
    <div class="tab-pane fade" id="tab-services">
      <div class="card card-editor p-4">
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-list-check text-success me-2"></i>Services Grid (6 Items)</h5>
        <p class="text-muted small mb-4">Edit the 6 service items shown on the Home & Services pages. Use FontAwesome icons (e.g. <code>fa-laptop-code</code>).</p>

        @php
          $servicesData = $agency->services_data ?? [
            ['icon' => 'fa-laptop-code',     'title' => 'Web Design',       'desc' => 'Beautiful, modern, and responsive websites that drive results.'],
            ['icon' => 'fa-layer-group',     'title' => 'UI/UX Design',     'desc' => 'User-centered designs that create seamless digital experiences.'],
            ['icon' => 'fa-bezier-curve',    'title' => 'Branding',         'desc' => 'Unique brand identities that make your business memorable.'],
            ['icon' => 'fa-bullhorn',        'title' => 'Digital Marketing','desc' => 'Data-driven marketing strategies that boost your visibility.'],
            ['icon' => 'fa-magnifying-glass','title' => 'SEO Optimization', 'desc' => 'Improve your search rankings and drive organic traffic.'],
            ['icon' => 'fa-mobile-screen',   'title' => 'App Development',  'desc' => 'Powerful and scalable apps for iOS & Android platforms.'],
          ];
        @endphp

        <div class="row g-3">
          @foreach($servicesData as $si => $srv)
            <div class="col-md-4">
              <div class="border rounded-3 p-3 bg-light">
                <div class="fw-bold small text-success mb-2">Service {{ $si + 1 }}</div>
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Icon Class</label>
                  <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][icon]" value="{{ $srv['icon'] }}">
                </div>
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Service Title</label>
                  <input type="text" class="form-control form-control-sm" name="services_data[{{ $si }}][title]" value="{{ $srv['title'] }}">
                </div>
                <div>
                  <label class="form-label small fw-semibold">Description</label>
                  <textarea class="form-control form-control-sm" name="services_data[{{ $si }}][desc]" rows="2">{{ $srv['desc'] }}</textarea>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- ===== TAB 4: PORTFOLIO PROJECTS ===== -->
    <div class="tab-pane fade" id="tab-portfolio">
      <div class="card card-editor p-4">
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-image text-success me-2"></i>Portfolio Items (8 Projects)</h5>
        <p class="text-muted small mb-4">Edit the 8 project cards shown on the Home & Portfolio sections.</p>

        @php
          $portfolioData = $agency->portfolio_data ?? [
            ['title' => 'Fintech Website Redesign', 'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_agency.png'],
            ['title' => 'E-commerce Skincare Store', 'category' => 'Web Design',   'image' => 'assets/website_builder/wb_card_ecommerce.png'],
            ['title' => 'Mobile Banking App',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
            ['title' => 'Brand Identity Design',    'category' => 'Branding',      'image' => 'assets/website_builder/wb_card_portfolio.png'],
            ['title' => 'SaaS Dashboard Design',    'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_restaurant.png'],
            ['title' => 'Travel Website',           'category' => 'Web Design',    'image' => 'assets/website_builder/wb_card_events.png'],
            ['title' => 'Fitness App Design',       'category' => 'UI/UX Design',  'image' => 'assets/website_builder/wb_card_startup.png'],
            ['title' => 'Digital Marketing Campaign','category' => 'Marketing',    'image' => 'assets/website_builder/wb_card_agency.png'],
          ];
        @endphp

        <div class="row g-3">
          @foreach($portfolioData as $pi => $port)
            <div class="col-md-3">
              <div class="border rounded-3 p-3 bg-light">
                <div class="fw-bold small text-success mb-2">Project {{ $pi + 1 }}</div>
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Title</label>
                  <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][title]" value="{{ $port['title'] }}">
                </div>
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Category Tag</label>
                  <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][category]" value="{{ $port['category'] }}">
                </div>
                <div>
                  <label class="form-label small fw-semibold">Image URL</label>
                  <input type="text" class="form-control form-control-sm" name="portfolio_data[{{ $pi }}][image]" value="{{ $port['image'] }}">
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- ===== TAB 5: ABOUT & TEAM ===== -->
    <div class="tab-pane fade" id="tab-about">
      <div class="card card-editor p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="fa-solid fa-users text-success me-2"></i>About Us Section Content</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold small">About Hero Title</label>
            <input type="text" class="form-control" name="about_hero_title" value="{{ $agency->about_hero_title ?? 'We Are A Creative Digital Solutions Agency' }}">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small">Story Title</label>
            <input type="text" class="form-control" name="story_title" value="{{ $agency->story_title ?? 'Our Journey Started With A Simple Idea' }}">
          </div>
          <div class="col-md-12">
            <label class="form-label fw-semibold small">Story Paragraph Text</label>
            <textarea class="form-control" name="story_text" rows="3">{{ $agency->story_text ?? "DesignAGENCY was founded in 2016 with a mission to empower businesses with smart digital solutions." }}</textarea>
          </div>
        </div>
      </div>

      <div class="card card-editor p-4">
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-user-group text-success me-2"></i>Team Members (4 Cards)</h5>
        <p class="text-muted small mb-4">Edit the 4 team members shown on the About Us page.</p>

        @php
          $teamData = $agency->team_members_data ?? [
            ['name' => 'Michael Roberts', 'role' => 'Founder & CEO',        'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Sarah Johnson',   'role' => 'Creative Director',     'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Daniel Smith',    'role' => 'Head of Development',  'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Jessica Brown',   'role' => 'Marketing Manager',     'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=400&auto=format&fit=crop'],
          ];
        @endphp

        <div class="row g-3">
          @foreach($teamData as $ti => $tm)
            <div class="col-md-3">
              <div class="border rounded-3 p-3 bg-light">
                <div class="fw-bold small text-success mb-2">Member {{ $ti + 1 }}</div>
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Name</label>
                  <input type="text" class="form-control form-control-sm" name="team_members_data[{{ $ti }}][name]" value="{{ $tm['name'] }}">
                </div>
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Role / Title</label>
                  <input type="text" class="form-control form-control-sm" name="team_members_data[{{ $ti }}][role]" value="{{ $tm['role'] }}">
                </div>
                <div>
                  <label class="form-label small fw-semibold">Photo Image URL</label>
                  <input type="text" class="form-control form-control-sm" name="team_members_data[{{ $ti }}][image]" value="{{ $tm['image'] }}">
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- ===== TAB 6: CONTACT & FAQS ===== -->
    <div class="tab-pane fade" id="tab-contact">
      <div class="card card-editor p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="fa-solid fa-headset text-success me-2"></i>Contact Us Headings</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold small">Contact Title</label>
            <input type="text" class="form-control" name="contact_title" value="{{ $agency->contact_title ?? "Let's Build Something Amazing Together!" }}">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small">Contact Subtitle</label>
            <input type="text" class="form-control" name="contact_subtitle" value="{{ $agency->contact_subtitle ?? "Have a project in mind or just want to say hello? We'd love to hear from you." }}">
          </div>
        </div>
      </div>

      <div class="card card-editor p-4">
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-circle-question text-success me-2"></i>FAQs (4 Items)</h5>
        <p class="text-muted small mb-4">Edit the 4 questions and answers shown in the contact page FAQ accordion.</p>

        @php
          $faqsData = $agency->faqs_data ?? [
            ['q' => 'How soon can we start our project?', 'a' => 'Once we understand your requirements, we can typically start within 2–3 business days.'],
            ['q' => 'What information do you need to get started?', 'a' => 'We will need your brand assets, project goals, target audience, and any content guidelines.'],
            ['q' => 'Do you offer ongoing support?', 'a' => 'Yes! We offer comprehensive maintenance, updates, and ongoing digital strategy support.'],
            ['q' => 'How do I know if my project is a good fit?', 'a' => 'Feel free to send us a quick message or book a discovery call, and our team will evaluate your needs!'],
          ];
        @endphp

        <div class="row g-3">
          @foreach($faqsData as $fi => $faq)
            <div class="col-md-6">
              <div class="border rounded-3 p-3 bg-light">
                <div class="fw-bold small text-success mb-2">FAQ {{ $fi + 1 }}</div>
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Question</label>
                  <input type="text" class="form-control form-control-sm" name="faqs_data[{{ $fi }}][q]" value="{{ $faq['q'] }}">
                </div>
                <div>
                  <label class="form-label small fw-semibold">Answer</label>
                  <textarea class="form-control form-control-sm" name="faqs_data[{{ $fi }}][a]" rows="2">{{ $faq['a'] }}</textarea>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>

  <div class="mt-4 d-flex gap-3">
    <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
      <i class="fa-solid fa-floppy-disk me-2"></i> Save All Changes
    </button>
  </div>
</form>
@endsection
