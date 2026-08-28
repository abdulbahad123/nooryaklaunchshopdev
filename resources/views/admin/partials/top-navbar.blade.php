<div class="main-header">
  <!-- Logo Header -->
  <div class="logo-header" style="background-color: #0C0E1A !important; border-bottom: 1px solid #1E2238 !important;">
    <a href="{{ route('front.index') }}" class="logo" target="_blank">

      <img src="{{ asset('assets/front/img/' . $bs->logo) }}" alt="navbar brand" class="navbar-brand" width="120">
    </a>
    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon">
        <i class="fas fa-bars"></i>
      </span>
    </button>
    <button class="topbar-toggler more"><i class="fas fa-ellipsis-v"></i></button>
    <div class="nav-toggle">
      <button class="btn btn-toggle toggle-sidebar">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </div>
  <!-- End Logo Header -->

  <!-- Navbar Header -->
  <nav class="navbar navbar-header navbar-expand-lg"
    @if (request()->cookie('admin-theme') == 'dark') data-background-color="dark" @endif>

    <div class="container-fluid d-flex align-items-center justify-content-between">
      
      <!-- Global Search Bar -->
      <div class="topbar-search-box d-none d-md-block">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="form-control" placeholder="{{ __('Search anything...') }}">
        <span class="search-shortcut">Ctrl + /</span>
      </div>

      <ul class="navbar-nav topbar-nav ml-md-auto align-items-center flex-row" style="gap: 12px;">
        
        <!-- Sun / Moon Light & Dark Mode Switcher -->
        <li class="nav-item">
          <form action="{{ route('admin.theme.change') }}" class="form-inline m-0" id="adminThemeForm">
            <input type="hidden" name="theme" id="adminThemeInput" value="{{ request()->cookie('admin-theme') ?? 'light' }}">
            <div class="theme-switch-pill">
              <button type="button" class="theme-btn js-theme-sun {{ empty(request()->cookie('admin-theme')) || request()->cookie('admin-theme') == 'light' ? 'active' : '' }}"
                onclick="document.getElementById('adminThemeInput').value='light'; window.setAdminTheme('light'); document.getElementById('adminThemeForm').submit();"
                title="{{ __('Switch to Light Mode') }}">
                <i class="fas fa-sun"></i>
              </button>
              <button type="button" class="theme-btn js-theme-moon {{ request()->cookie('admin-theme') == 'dark' ? 'active' : '' }}"
                onclick="document.getElementById('adminThemeInput').value='dark'; window.setAdminTheme('dark'); document.getElementById('adminThemeForm').submit();"
                title="{{ __('Switch to Dark Mode') }}">
                <i class="fas fa-moon"></i>
              </button>
            </div>
          </form>
        </li>

        <!-- Notification Bell Icon -->
        <li class="nav-item">
          <a href="javascript:void(0)" class="nav-btn-circle" title="{{ __('Notifications') }}">
            <i class="far fa-bell"></i>
            <span class="badge-count">3</span>
          </a>
        </li>

        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown hidden-caret">
          <a class="dropdown-toggle profile-pic p-0" data-toggle="dropdown" href="#" aria-expanded="false">
            <div class="header-user-profile">
              @if (!empty(Auth::guard('admin')->user()->image))
                <img src="{{ asset('assets/admin/img/propics/' . Auth::guard('admin')->user()->image) }}" alt="user image">
              @else
                <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="user image">
              @endif
              <div class="user-info d-none d-sm-block text-left">
                <div class="user-name">{{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}</div>
                <div class="user-role">{{ is_null(@Auth::guard('admin')->user()->role->name) ? __('Super Admin') : @Auth::guard('admin')->user()->role->name }}</div>
              </div>
              <i class="fas fa-chevron-down text-muted ml-1" style="font-size: 0.75rem;"></i>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-user animated fadeIn dropdown-menu-right">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    @if (!empty(Auth::guard('admin')->user()->image))
                      <img src="{{ asset('assets/admin/img/propics/' . Auth::guard('admin')->user()->image) }}"
                        alt="..." class="avatar-img rounded">
                    @else
                      <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..."
                        class="avatar-img rounded">
                    @endif
                  </div>
                  <div class="u-text ml-2">
                    <h4>{{ Auth::guard('admin')->user()->first_name }}</h4>
                    <p class="text-muted mb-2" style="font-size: 0.8rem;">{{ Auth::guard('admin')->user()->email }}</p>
                    <a href="{{ route('admin.editProfile') }}" class="btn btn-xs btn-primary btn-sm rounded-pill">{{ __('Edit Profile') }}</a>
                  </div>
                </div>
              </li>
              <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.editProfile') }}"><i class="fas fa-user-cog mr-2"></i>{{ __('Edit Profile') }}</a>
                <a class="dropdown-item" href="{{ route('admin.changePass') }}"><i class="fas fa-key mr-2"></i>{{ __('Change Password') }}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i class="fas fa-sign-out-alt mr-2"></i>{{ __('Logout') }}</a>
              </li>
            </div>
          </ul>
        </li>

      </ul>
    </div>
  </nav>
  <!-- End Navbar -->
</div>
