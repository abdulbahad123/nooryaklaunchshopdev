<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Website Builder Admin')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
    .sidebar { width: 260px; min-height: 100vh; background: #0f172a; color: #cbd5e1; position: fixed; }
    .sidebar .nav-link { color: #94a3b8; padding: 12px 20px; font-weight: 500; display: flex; align-items: center; gap: 12px; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #1e293b; border-left: 4px solid #6366f1; }
    .main-content { margin-left: 260px; padding: 30px; }
    .card { border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .brand-title { padding: 20px; font-size: 18px; font-weight: 800; color: #fff; border-bottom: 1px solid #1e293b; display: flex; align-items: center; gap: 10px; }
  </style>
</head>
<body>

  <div class="sidebar">
    <div class="brand-title">
      <i class="fa-solid fa-layer-group text-indigo"></i>
      <span>WB Admin</span>
    </div>
    <nav class="nav flex-column mt-3">
      <a href="{{ route('website-builder.admin.dashboard') }}" class="nav-link {{ request()->routeIs('website-builder.admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
      <a href="{{ route('website-builder.admin.landing-settings.edit') }}" class="nav-link {{ request()->routeIs('website-builder.admin.landing-settings.*') ? 'active' : '' }}"><i class="fa-solid fa-palette"></i> Landing & Colors</a>
      <a href="{{ route('website-builder.admin.customers.index') }}" class="nav-link {{ request()->routeIs('website-builder.admin.customers.*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Registered Clients</a>
      <a href="{{ route('website-builder.admin.templates.index') }}" class="nav-link {{ request()->routeIs('website-builder.admin.templates.*') ? 'active' : '' }}"><i class="fa-solid fa-cubes"></i> Templates Engine</a>
      <a href="{{ route('website-builder.admin.packages.index') }}" class="nav-link {{ request()->routeIs('website-builder.admin.packages.*') ? 'active' : '' }}"><i class="fa-solid fa-tags"></i> Packages & Plans</a>
      
      <!-- Staff & Roles -->
      <a href="{{ route('website-builder.admin.staff.index') }}" class="nav-link {{ request()->routeIs('website-builder.admin.staff.index') ? 'active' : '' }}"><i class="fa-solid fa-user-shield"></i> Registered Admins</a>
      <a href="{{ route('website-builder.admin.roles.index') }}" class="nav-link {{ request()->routeIs('website-builder.admin.roles.*') ? 'active' : '' }}"><i class="fa-solid fa-user-gear"></i> Role & Permissions</a>

      <!-- Domain & Payments -->
      <a href="{{ route('website-builder.admin.domains.index') }}" class="nav-link {{ request()->routeIs('website-builder.admin.domains.*') ? 'active' : '' }}"><i class="fa-solid fa-link"></i> Custom Domains</a>
      <a href="{{ route('website-builder.admin.payment-gateways.index') }}" class="nav-link {{ request()->routeIs('website-builder.admin.payment-gateways.*') ? 'active' : '' }}"><i class="fa-solid fa-credit-card"></i> Payment Gateways</a>
      <a href="{{ route('website-builder.admin.agency-access') }}" class="nav-link {{ request()->routeIs('website-builder.admin.agency-access') ? 'active' : '' }}"><i class="fa-solid fa-building-user"></i> Agency SaaS Access</a>
      <a href="{{ route('website-builder.agency-admin.index') }}" target="_blank" class="nav-link text-success fw-bold"><i class="fa-solid fa-paintbrush"></i> Agency Template Admin <i class="fa-solid fa-external-link ms-auto small"></i></a>
      
      <a href="{{ route('website-builder.index') }}" target="_blank" class="nav-link mt-4 text-info"><i class="fa-solid fa-globe"></i> View Public Site <i class="fa-solid fa-external-link ms-auto small"></i></a>
      <form action="{{ route('website-builder.admin.logout') }}" method="POST" class="mt-2 px-3">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-danger w-100 text-start"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</button>
      </form>
    </nav>
  </div>

  <div class="main-content">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
