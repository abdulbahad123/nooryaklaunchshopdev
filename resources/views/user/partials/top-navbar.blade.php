@php
  use App\Http\Helpers\LimitCheck as LimitCheck;
  use App\Models\Membership;
  use Carbon\Carbon;

  $userId = Auth::guard('web')->user()->id;
  $currPackage = LimitCheck::current_package($userId);
  $featureCount = LimitCheck::packageFeaturesCount($userId);
  $infoIcon = false;

  //product category limit
  $catLimit = LimitCheck::catLimit($userId);
  $totalCat = $featureCount['categories'];
  $canAddCat = $catLimit - $totalCat;
  //product subcategory limit
  $subcatLimit = LimitCheck::subcatLimit($userId);
  $totalSubcat = $featureCount['subcategories'];
  $canAddSubcat = $subcatLimit - $totalSubcat;
  //product limit
  $itemLimit = LimitCheck::itemLimit($userId);
  $totalItem = $featureCount['items'];
  $canAddItem = $itemLimit - $totalItem;
  //blog limit
  $blogLimit = LimitCheck::blogLimit($userId);
  $totalBlog = $featureCount['blogs'];
  $canAddBlog = $blogLimit - $totalBlog;
  //language limit
  $langLimit = LimitCheck::langLimit($userId);
  $totalLang = $featureCount['languages'];
  $canAddLang = $langLimit - $totalLang;
  //custompage limit
  $pageLimit = LimitCheck::pageLimit($userId);
  $totalCustomPage = $featureCount['custome_page'];
  $canAddPage = $pageLimit - $totalCustomPage;
  //order limit
  $orderLimit = LimitCheck::orderLimit($userId);
  $totalOrder = $featureCount['orders'];
  $canAddOrder = $orderLimit - $totalOrder;
  //coupon limit
  $couponLimit = LimitCheck::couponLimit($userId);
  $totalCoupon = $featureCount['coupons'];
  $canAddCoupon = $couponLimit - $totalCoupon;

  $today = Carbon::now()->toDateString();
  $aiMembership = Membership::query()->select([
      'id',
      'user_id',
      'ai_engine',
      'ai_token_limit',
      'ai_image_limit',
      'ai_used_tokens',
      'ai_used_images',
      'ai_token_purchased',
      'ai_image_purchased',
  ])->where([
      ['user_id', $userId],
      ['start_date', '<=', $today],
      ['expire_date', '>=', $today],
  ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();

  $aiEngine = $aiMembership?->ai_engine;
  $aiUsedTokens = max(0, (int) ($aiMembership?->ai_used_tokens ?? 0));
  $aiTokenLimit = max(0, (int) ($aiMembership?->ai_token_limit ?? 0));
  $aiTokenPurchased = max(0, (int) ($aiMembership?->ai_token_purchased ?? 0));
  $aiTokenTotalLimit = $aiTokenLimit + $aiTokenPurchased;

  $aiUsedImages = max(0, (int) ($aiMembership?->ai_used_images ?? 0));
  $aiImageLimit = max(0, (int) ($aiMembership?->ai_image_limit ?? 0));
  $aiImagePurchased = max(0, (int) ($aiMembership?->ai_image_purchased ?? 0));
  $aiImageTotalLimit = $aiImageLimit + $aiImagePurchased;

  if ($canAddCat < 0 || $canAddSubcat < 0 || $canAddItem < 0 || $canAddBlog < 0 || $canAddLang < 0 || $canAddPage < 0 || $canAddCoupon < 0) {
      $infoIcon = true;
  }
@endphp

<style>
  /* ── Responsive Topbar Nav Alignment Fix ── */
  .navbar-header {
    padding: 0 15px !important;
  }
  .navbar-nav.topbar-nav {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: flex-end !important;
    gap: 12px !important;
    margin-left: auto !important;
    padding: 0 !important;
    margin-bottom: 0 !important;
  }
  .navbar-nav.topbar-nav .nav-item {
    display: flex !important;
    align-items: center !important;
    margin: 0 !important;
  }
  #limitBtn {
    display: inline-flex !important;
    align-items: center !important;
    white-space: nowrap !important;
  }
  @media (max-width: 991px) {
    .navbar-nav.topbar-nav {
      gap: 8px !important;
      flex-wrap: wrap !important;
      justify-content: flex-end !important;
    }
    .navbar-header {
      padding: 0 8px !important;
    }
    .dropdown-user {
      right: 0 !important;
      left: auto !important;
    }
  }
  @media (max-width: 576px) {
    .navbar-nav.topbar-nav {
      gap: 6px !important;
    }
    #limitBtn {
      padding: 4px 10px !important;
      font-size: 11px !important;
    }
  }
</style>

<div class="main-header">
  <!-- Logo Header -->
  <div class="logo-header" data-background-color="dark2">
    <a href="{{ route('front.index') }}" class="logo" target="_blank">
      <img
        src="{{ !empty($userBs->logo) ? asset('assets/front/img/user/' . $userBs->logo) : asset('assets/front/img/logo.png') }}"
        alt="Logo" class="navbar-brand">
    </a>
    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon">
        <i class="icon-menu"></i>
      </span>
    </button>
    <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
    <div class="nav-toggle">
      <button class="btn btn-toggle toggle-sidebar">
        <i class="icon-menu"></i>
      </button>
    </div>
  </div>
  <!-- End Logo Header -->
  <!-- Navbar Header -->
  <nav class="navbar navbar-header navbar-expand-lg"
    @if (request()->cookie('user-theme') == 'dark') data-background-color="dark" @endif>
    <div class="container-fluid">
      <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
        <li class="nav-item" id="limitDiv">
          <a class="btn btn-{{ $infoIcon == true ? 'danger' : 'primary' }} whitespace-nowrap btn-sm font-weight-bold"
            style="border-radius: 8px; padding: 6px 14px; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);"
            data-toggle="modal" data-target="#limitModal" href="javascript:void(0)" id="limitBtn">
            @if ($infoIcon == true)
              <span class="text-danger mr-1">
                <i class="fas fa-exclamation-triangle text-white"></i>
              </span>
            @endif
            {{ __('Check Limit') }}
          </a>
        </li>
        <li class="nav-item">
          <form action="{{ route('user.theme.change') }}" class="form-inline" id="adminThemeForm">
            <div class="form-group py-0">
              <div class="selectgroup selectgroup-secondary selectgroup-pills d-flex align-items-center">
                <label class="selectgroup-item mb-0 mr-1">
                  <input type="radio" name="theme" value="light" class="selectgroup-input"
                    {{ empty(request()->cookie('user-theme')) || request()->cookie('user-theme') == 'light' ? 'checked' : '' }}
                    onchange="document.getElementById('adminThemeForm').submit();">
                  <span class="selectgroup-button selectgroup-button-icon theme-circle-btn"><i class="fa fa-sun"></i></span>
                </label>
                <label class="selectgroup-item mb-0">
                  <input type="radio" name="theme" value="dark" class="selectgroup-input"
                    {{ request()->cookie('user-theme') == 'dark' ? 'checked' : '' }}
                    onchange="document.getElementById('adminThemeForm').submit();">
                  <span class="selectgroup-button selectgroup-button-icon theme-circle-btn"><i class="fa fa-moon"></i></span>
                </label>
              </div>
            </div>
          </form>
        </li>
        <li class="nav-item">
          <a class="btn btn-sm btn-round d-inline-flex align-items-center justify-content-center profile-circle-btn" target="_blank"
            style="width: 36px; height: 36px; border-radius: 50%; background: #ffffff; border: 1px solid #e2e8f0; color: #0d6efd; box-shadow: 0 2px 6px rgba(0,0,0,0.03);"
            href="{{ detailsUrl(Auth::user()) }}" title="View Profile">
            <i class="fas fa-eye" style="font-size: 13px;"></i>
          </a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <label class="switch mb-0">
            <input type="checkbox" name="online_status" id="toggle-btn" data-toggle="toggle" data-on="1"
              data-off="0" @if (Auth::user()->online_status == 1) checked @endif>
            <span class="slider round"></span>
          </label>
          @if (Auth::user()->online_status == 1)
            <h5 class="mb-0 ml-1 font-weight-bold d-none d-sm-inline-block @if (request()->cookie('user-theme') == 'dark') text-white @endif">
              {{ __('Active') }}
            </h5>
          @else
            <h5 class="mb-0 ml-1 font-weight-bold d-none d-sm-inline-block @if (request()->cookie('user-theme') == 'dark') text-white @endif">
              {{ __('Deactive') }}
            </h5>
          @endif
        </li>
        <li class="nav-item dropdown hidden-caret">
          <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
            <div class="avatar-sm">
              @if (Session::has('staff_id'))
                <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..."
                  class="avatar-img rounded-circle">
              @elseif (!empty(Auth::user()->photo))
                <img src="{{ asset('assets/front/img/user/' . Auth::user()->photo) }}" alt="..."
                  class="avatar-img rounded-circle">
              @else
                <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..."
                  class="avatar-img rounded-circle">
              @endif
            </div>
          </a>
          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    @if (Session::has('staff_id'))
                      <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..."
                        class="avatar-img rounded">
                    @elseif (!empty(Auth::user()->photo))
                      <img src="{{ asset('assets/front/img/user/' . Auth::user()->photo) }}" alt="..."
                        class="avatar-img rounded">
                    @else
                      <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..."
                        class="avatar-img rounded">
                    @endif
                  </div>
                  <div class="u-text">
                    @if (Session::has('staff_id'))
                      <h4>{{ Session::get('staff_name') }}</h4>
                      <p class="text-muted"><span class="badge badge-info">{{ Session::get('staff_role') }}</span></p>
                    @else
                      <h4>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                      <p class="text-muted">{{ Auth::user()->email }}</p>
                      <a href="{{ route('user-profile-update') }}"
                        class="btn btn-xs btn-secondary btn-sm">{{ __('Edit Profile') }}</a>
                    @endif
                  </div>
                </div>
              </li>
              <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('user-profile-update') }}">{{ __('Edit Profile') }}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('user.changePass') }}">{{ __('Change Password') }}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('user-logout') }}">{{ __('Logout') }}</a>
              </li>
            </div>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <!-- End Navbar -->
</div>
@includeIf('user.partials.limit-modal')
