<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Website Builder Super Admin')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
    .sidebar { width: 270px; min-height: 100vh; background: #0B0F19; color: #94a3b8; position: fixed; display: flex; flex-direction: column; justify-content: space-between; padding: 24px 16px; overflow-y: auto; z-index: 1000; }
    .sidebar .nav-link { color: #94a3b8; padding: 12px 16px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border-radius: 12px; margin-bottom: 4px; transition: all 0.2s; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #4F46E5; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35); }
    .sidebar-sub-menu { padding-left: 18px; margin-top: 4px; margin-bottom: 8px; }
    .sidebar-sub-link { display: flex; align-items: center; gap: 8px; color: #94a3b8; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 13.5px; margin-bottom: 4px; transition: all 0.2s; }
    .sidebar-sub-link:hover, .sidebar-sub-link.active { color: #fff; background: rgba(255,255,255,0.08); }
    .sidebar-sub-link::before { content: '•'; color: #4F46E5; font-size: 16px; font-weight: bold; }
    .main-content { margin-left: 270px; padding: 30px; }
    .card { border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .brand-title { padding: 8px 12px 20px; font-size: 19px; font-weight: 800; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
    .sidebar-bottom-link { background: rgba(16, 185, 129, 0.12); color: #34D399 !important; border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 12px; padding: 12px 16px; text-decoration: none; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s; margin-top: 20px; }
    .sidebar-bottom-link:hover { background: #10B981; color: #ffffff !important; }
  </style>
</head>
<body>

  <!-- SLEEK BLACK ACCORDION SIDEBAR (Matching Ref Image 2) -->
  <div class="sidebar">
    <div>
      <div class="brand-title">
        <div class="p-2 rounded-3 text-white" style="background: #4F46E5;"><i class="fa-solid fa-layer-group"></i></div>
        <span>WB Super Admin</span>
      </div>

      <nav class="nav flex-column">
        <!-- DASHBOARD -->
        <a href="{{ route('website-builder.admin.dashboard') }}" class="nav-link {{ request()->routeIs('website-builder.admin.dashboard') ? 'active' : '' }}">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-chart-pie me-1"></i>
            <span>Dashboard</span>
          </div>
        </a>

        <!-- PAGES / BUILDER DROPDOWN MENU -->
        <div>
          <a class="nav-link {{ request()->routeIs('website-builder.admin.landing-settings.*') || request()->routeIs('website-builder.admin.templates.*') || request()->routeIs('website-builder.admin.packages.*') ? 'active' : '' }}" 
             data-bs-toggle="collapse" 
             href="#builderPagesSubMenu" 
             role="button" 
             aria-expanded="true">
            <div class="d-flex align-items-center gap-2">
              <i class="fa-solid fa-file-lines me-1"></i>
              <span>Pages & Engine</span>
            </div>
            <i class="fa-solid fa-chevron-down small"></i>
          </a>

          <div class="collapse show sidebar-sub-menu" id="builderPagesSubMenu">
            <a href="{{ route('website-builder.admin.landing-settings.edit') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.admin.landing-settings.*') ? 'active' : '' }}">
              Landing & Colors
            </a>
            <a href="{{ route('website-builder.admin.templates.index') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.admin.templates.*') ? 'active' : '' }}">
              Templates Engine
            </a>
            <a href="{{ route('website-builder.admin.packages.index') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.admin.packages.*') ? 'active' : '' }}">
              Packages & Pricing
            </a>
          </div>
        </div>

        <!-- CLIENTS & USERS DROPDOWN MENU -->
        <div>
          <a class="nav-link {{ request()->routeIs('website-builder.admin.customers.*') || request()->routeIs('website-builder.admin.staff.*') || request()->routeIs('website-builder.admin.roles.*') ? 'active' : '' }}" 
             data-bs-toggle="collapse" 
             href="#usersSubMenu" 
             role="button" 
             aria-expanded="true">
            <div class="d-flex align-items-center gap-2">
              <i class="fa-solid fa-users me-1"></i>
              <span>Clients & Users</span>
            </div>
            <i class="fa-solid fa-chevron-down small"></i>
          </a>

          <div class="collapse show sidebar-sub-menu" id="usersSubMenu">
            <a href="{{ route('website-builder.admin.customers.index') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.admin.customers.*') ? 'active' : '' }}">
              Registered Clients
            </a>
            <a href="{{ route('website-builder.admin.staff.index') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.admin.staff.*') ? 'active' : '' }}">
              Registered Admins
            </a>
            <a href="{{ route('website-builder.admin.roles.index') }}" class="sidebar-sub-link {{ request()->routeIs('website-builder.admin.roles.*') ? 'active' : '' }}">
              Role & Permissions
            </a>
          </div>
        </div>

        <!-- DOMAINS & SYSTEM -->
        <a href="{{ route('website-builder.admin.domains.index') }}" class="nav-link {{ request()->routeIs('website-builder.admin.domains.*') ? 'active' : '' }}">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-link me-1"></i>
            <span>Custom Domains</span>
          </div>
        </a>
        <a href="{{ route('website-builder.admin.payment-gateways.index') }}" class="nav-link {{ request()->routeIs('website-builder.admin.payment-gateways.*') ? 'active' : '' }}">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-credit-card me-1"></i>
            <span>Payment Gateways</span>
          </div>
        </a>
        <a href="{{ route('website-builder.admin.agency-access') }}" class="nav-link {{ request()->routeIs('website-builder.admin.agency-access') ? 'active' : '' }}">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-building-user me-1"></i>
            <span>Agency SaaS Access</span>
          </div>
        </a>
        <a href="{{ route('website-builder.agency-admin.index') }}" target="_blank" class="nav-link text-emerald fw-bold" style="color: #34D399;">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-paintbrush me-1"></i>
            <span>Agency Template Admin</span>
          </div>
          <i class="fa-solid fa-arrow-up-right-from-square small"></i>
        </a>
      </nav>
    </div>

    <!-- BOTTOM LIVE WEBSITE LINK -->
    <div>
      <a href="{{ route('website-builder.index') }}" target="_blank" class="sidebar-bottom-link">
        <span><i class="fa-solid fa-globe me-2"></i> Live Website</span>
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
      </a>
      <form action="{{ route('website-builder.admin.logout') }}" method="POST" class="mt-2">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-danger w-100 text-start px-3 py-2 fw-semibold" style="border-radius: 10px;">
          <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
        </button>
      </form>
    </div>
  </div>

  <div class="main-content">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show rounded-3 fw-bold" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @yield('scripts')
</body>
</html>
