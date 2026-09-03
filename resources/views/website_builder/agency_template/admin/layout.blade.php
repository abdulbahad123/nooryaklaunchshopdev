<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'DesignAGENCY Template Admin Dashboard')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --agency-green: #10B981;
      --agency-green-dark: #059669;
      --sidebar-bg: #064E3B;
      --sidebar-dark: #022C22;
      --body-bg: #F8FAFC;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--body-bg);
      color: #0F172A;
    }

    .agency-admin-sidebar {
      width: 260px;
      min-height: 100vh;
      background: linear-gradient(180deg, var(--sidebar-bg) 0%, var(--sidebar-dark) 100%);
      color: #ECFDF5;
      position: fixed;
      top: 0; left: 0; bottom: 0;
      z-index: 100;
      padding: 24px 16px;
    }
    .agency-admin-brand {
      font-size: 20px;
      font-weight: 800;
      color: #ffffff;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 32px;
      padding-bottom: 16px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .agency-admin-brand span.highlight { color: #34D399; }
    .agency-nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      color: #A7F3D0;
      padding: 12px 16px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      margin-bottom: 6px;
      transition: all 0.2s;
    }
    .agency-nav-item:hover,
    .agency-nav-item.active {
      background: rgba(255,255,255,0.12);
      color: #ffffff;
    }

    .agency-admin-content {
      margin-left: 260px;
      padding: 30px 40px;
    }
    .card-editor {
      border: 0;
      border-radius: 18px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      background: #ffffff;
    }

    @media (max-width: 991px) {
      .agency-admin-sidebar { width: 100%; position: relative; min-height: auto; }
      .agency-admin-content { margin-left: 0; padding: 20px; }
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="agency-admin-sidebar">
  <a href="{{ route('website-builder.agency-admin.index') }}" class="agency-admin-brand">
    <div class="p-2 bg-emerald-500 rounded-3 text-white"><i class="fa-solid fa-palette"></i></div>
    <span>Design<span class="highlight">AGENCY</span> Admin</span>
  </a>

  <div class="small fw-bold text-uppercase text-emerald-300 opacity-75 mb-2 px-3" style="font-size: 11px; letter-spacing: 0.5px;">Template Control</div>

  <a href="{{ route('website-builder.agency-admin.index') }}" class="agency-nav-item active">
    <i class="fa-solid fa-sliders text-emerald-400"></i> Template Editor
  </a>
  <a href="{{ route('website-builder.templates.design-agency') }}" target="_blank" class="agency-nav-item">
    <i class="fa-solid fa-eye text-emerald-400"></i> Live Home Page
  </a>
  <a href="{{ route('website-builder.templates.design-agency.about') }}" target="_blank" class="agency-nav-item">
    <i class="fa-solid fa-users text-emerald-400"></i> About Us Page
  </a>
  <a href="{{ route('website-builder.templates.design-agency.contact') }}" target="_blank" class="agency-nav-item">
    <i class="fa-solid fa-envelope text-emerald-400"></i> Contact Us Page
  </a>

  <div class="mt-5 pt-4 border-top border-white border-opacity-10">
    <a href="{{ route('website-builder.admin.dashboard') }}" class="agency-nav-item text-white-50">
      <i class="fa-solid fa-arrow-left"></i> Super Admin Suite
    </a>
  </div>
</aside>

<!-- MAIN CONTENT -->
<main class="agency-admin-content">
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
