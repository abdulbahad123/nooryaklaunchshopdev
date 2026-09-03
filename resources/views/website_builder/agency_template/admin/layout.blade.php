<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'DesignAGENCY Template Admin')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --sidebar-bg: #0B0F19;
      --sidebar-card: #151C2C;
      --sidebar-text: #94A3B8;
      --sidebar-text-active: #FFFFFF;
      --primary-accent: #4F46E5;
      --body-bg: #F8FAFC;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--body-bg);
      color: #0F172A;
    }

    /* SLEEK BLACK SIDEBAR MATCHING REF IMAGE 2 */
    .agency-admin-sidebar {
      width: 270px;
      min-height: 100vh;
      background: var(--sidebar-bg);
      color: var(--sidebar-text);
      position: fixed;
      top: 0; left: 0; bottom: 0;
      z-index: 1000;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 24px 16px;
      overflow-y: auto;
    }

    .agency-brand-title {
      font-size: 20px;
      font-weight: 800;
      color: #ffffff;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 12px 24px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      margin-bottom: 20px;
    }
    .agency-brand-title span.accent { color: #10B981; }

    /* SIDEBAR NAVIGATION LINKS */
    .sidebar-nav-link {
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #94A3B8;
      padding: 12px 16px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14.5px;
      margin-bottom: 6px;
      transition: all 0.2s;
    }
    .sidebar-nav-link:hover,
    .sidebar-nav-link.active {
      color: #ffffff;
      background: var(--primary-accent);
      box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
    }
    .sidebar-nav-link i.link-icon {
      font-size: 17px;
      width: 24px;
    }

    /* COLLAPSIBLE SUB MENU MATCHING REF IMAGE 2 */
    .sidebar-sub-menu {
      padding-left: 20px;
      margin-top: 4px;
      margin-bottom: 8px;
    }
    .sidebar-sub-link {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #94A3B8;
      padding: 9px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      font-size: 13.5px;
      margin-bottom: 4px;
      transition: all 0.2s;
    }
    .sidebar-sub-link:hover,
    .sidebar-sub-link.active {
      color: #ffffff;
      background: rgba(255,255,255,0.08);
    }
    .sidebar-sub-link::before {
      content: '•';
      color: #4F46E5;
      font-size: 16px;
      font-weight: bold;
    }

    .agency-admin-main {
      margin-left: 270px;
      padding: 32px 40px;
    }

    .card-editor {
      border: 1px solid #E2E8F0;
      border-radius: 18px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.03);
      background: #ffffff;
    }

    /* BOTTOM LIVE WEBSITE LINK */
    .sidebar-bottom-link {
      background: rgba(16, 185, 129, 0.12);
      color: #34D399 !important;
      border: 1px solid rgba(16, 185, 129, 0.25);
      border-radius: 12px;
      padding: 12px 16px;
      text-decoration: none;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: all 0.2s;
      margin-top: 20px;
    }
    .sidebar-bottom-link:hover {
      background: #10B981;
      color: #ffffff !important;
    }

    @media (max-width: 991px) {
      .agency-admin-sidebar { width: 100%; position: relative; min-height: auto; }
      .agency-admin-main { margin-left: 0; padding: 20px; }
    }
  </style>
</head>
<body>

<!-- SLEEK BLACK SIDEBAR (Ref Image 2 Match) -->
<aside class="agency-admin-sidebar">
  <div>
    <a href="{{ route('website-builder.agency-admin.index') }}" class="agency-brand-title">
      <div class="p-2 rounded-3 text-white" style="background: #10B981;"><i class="fa-solid fa-paintbrush"></i></div>
      <span>Design<span class="accent">AGENCY</span></span>
    </a>

    <!-- DASHBOARD LINK -->
    <a href="{{ route('website-builder.agency-admin.index') }}" class="sidebar-nav-link {{ request()->routeIs('website-builder.agency-admin.index') ? 'active' : '' }}">
      <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-chart-pie link-icon"></i>
        <span>Dashboard</span>
      </div>
    </a>

    <!-- PAGES DROPDOWN SUB NAV MENU (Ref Image 2 Match) -->
    <div class="mb-2">
      <a class="sidebar-nav-link {{ request()->routeIs('website-builder.agency-admin.home') || request()->routeIs('website-builder.agency-admin.about') || request()->routeIs('website-builder.agency-admin.contact') || request()->routeIs('website-builder.agency-admin.footer') ? 'active' : '' }}" 
         data-bs-toggle="collapse" 
         href="#pagesSubMenu" 
         role="button" 
         aria-expanded="true">
        <div class="d-flex align-items-center gap-2">
          <i class="fa-solid fa-file-lines link-icon"></i>
          <span>Pages</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
      </a>

      <div class="collapse show sidebar-sub-menu" id="pagesSubMenu">
        <a href="{{ route('website-builder.agency-admin.home') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.agency-admin.home') ? 'active' : '' }}">
          Home Page
        </a>
        <a href="{{ route('website-builder.agency-admin.about') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.agency-admin.about') ? 'active' : '' }}">
          About Us
        </a>
        <a href="{{ route('website-builder.agency-admin.contact') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.agency-admin.contact') ? 'active' : '' }}">
          Contact Page
        </a>
        <a href="{{ route('website-builder.agency-admin.footer') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.agency-admin.footer') ? 'active' : '' }}">
          Footer & Branding
        </a>
      </div>
    </div>

    <!-- CONTACT SUBMISSIONS / INQUIRIES LINK -->
    <a href="{{ route('website-builder.agency-admin.inquiries') }}" class="sidebar-nav-link {{ request()->routeIs('website-builder.agency-admin.inquiries') ? 'active' : '' }}">
      <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-envelope-open-text link-icon"></i>
        <span>Contact Submissions</span>
      </div>
    </a>
  </div>

  <!-- RENAME TO LIVE WEBSITE & PLACE AT BOTTOM (User Task 2 Match) -->
  <div>
    <a href="{{ route('website-builder.templates.design-agency') }}" target="_blank" class="sidebar-bottom-link">
      <span><i class="fa-solid fa-globe me-2"></i> Live Website</span>
      <i class="fa-solid fa-arrow-up-right-from-square"></i>
    </a>
  </div>
</aside>

<!-- MAIN CONTENT -->
<main class="agency-admin-main">
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
