@extends('admin.layout')
@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title">{{ __('User Details') }}</h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-home"></i>
          </a>
        </li>
        <li class="separator">
          <i class="fas fa-chevron-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Users Management') }}</a>
        </li>
        <li class="separator">
          <i class="fas fa-chevron-right"></i>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.register.user') }}">{{ __('Registered Users') }}</a>
        </li>
        <li class="separator">
          <i class="fas fa-chevron-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ $user->username }}</a>
        </li>
        <li class="separator">
          <i class="fas fa-chevron-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('User Details') }}</a>
        </li>
      </ul>
    </div>

    <a href="{{ route('admin.register.user') }}" class="btn-back-pill m-0">
      <i class="fas fa-arrow-left"></i> {{ __('Back') }}
    </a>
  </div>

  <div class="row">
    <!-- Left Column: User Profile Overview (Matching Reference Image 3) -->
    <div class="col-lg-4 col-md-5">
      <div class="card text-center p-4">
        <div class="user-profile-avatar-container">
          <img
            src="{{ !empty($user->photo) ? asset('assets/front/img/user/' . $user->photo) : asset('assets/user/img/profile.png') }}"
            alt="{{ $user->username }}">
          <div class="verified-shield" title="{{ __('Verified User') }}">
            <i class="fas fa-check"></i>
          </div>
        </div>

        <h4 class="font-weight-bold text-dark mb-1" style="font-size: 1.35rem;">{{ $user->username }}</h4>
        <div class="mb-4">
          @if ($user->email_verified == 1)
            <span class="status-pill-active">
              <i class="fas fa-user-check"></i> {{ __('Verified User') }}
            </span>
          @else
            <span class="status-pill-warning">
              <i class="fas fa-user-clock"></i> {{ __('Non-Verified User') }}
            </span>
          @endif
        </div>

        <div class="text-left pt-3" style="border-top: 1px solid var(--border-card);">
          <div class="meta-info-item">
            <div class="icon-circle" style="background: #F3E8FF; color: #7C3AED;">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
              <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Registered At') }}</span>
              <span class="font-weight-bold text-dark" style="font-size:0.875rem;">{{ $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-' }}</span>
            </div>
          </div>

          <div class="meta-info-item">
            <div class="icon-circle" style="background: #DCFCE7; color: #16A34A;">
              <i class="fas fa-clock"></i>
            </div>
            <div>
              <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Last Updated') }}</span>
              <span class="font-weight-bold text-dark" style="font-size:0.875rem;">{{ $user->updated_at ? $user->updated_at->format('d-m-Y H:i') : '-' }}</span>
            </div>
          </div>

          <div class="meta-info-item">
            <div class="icon-circle" style="background: #DBEAFE; color: #2563EB;">
              <i class="fas fa-id-card"></i>
            </div>
            <div>
              <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('User ID') }}</span>
              <span class="font-weight-bold text-dark" style="font-size:0.875rem;">#USR-{{ 1000 + $user->id }}</span>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button class="btn-back-pill w-100 justify-content-center py-2" style="background: #F3E8FF !important; color: #7C3AED !important;">
            <i class="fas fa-envelope"></i> {{ __('Send Verification Email') }}
          </button>
        </div>
      </div>
    </div>

    <!-- Right Column: User Details Grid (Matching Reference Image 3) -->
    <div class="col-lg-8 col-md-7">
      @if (session()->has('membership_warning'))
        <div class="alert alert-warning text-dark mb-3" style="border-radius: 14px;">
          <i class="fas fa-exclamation-triangle mr-1"></i> {{ session()->get('membership_warning') }}
        </div>
      @endif

      <div class="card">
        <div class="card-header d-flex align-items-center gap-3">
          <span class="cat-icon-badge i-purple m-0" style="width:36px; height:36px; font-size:0.95rem;">
            <i class="fas fa-user"></i>
          </span>
          <div class="card-title m-0">{{ __('User Information') }}</div>
        </div>
        
        <div class="card-body p-4">
          <div class="row">
            <!-- Left Side Details -->
            <div class="col-lg-6 pr-lg-4" style="border-right: 1px solid var(--border-card);">
              <div class="user-info-row">
                <span class="lbl">{{ __('Username') }}</span>
                <span class="val font-weight-bold">{{ $user->username ?? '-' }}</span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Path Based URL') }}</span>
                <span class="val">
                  <a href="//{{ env('WEBSITE_HOST') . '/' . $user->username }}" target="_blank" class="text-primary font-weight-bold">
                    {{ env('WEBSITE_HOST') . '/' . $user->username }} <i class="fas fa-external-link-alt ml-1" style="font-size:0.75rem;"></i>
                  </a>
                </span>
              </div>

              @php
                $features = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
                $features = json_decode($features, true);
              @endphp

              @if (!empty($features) && is_array($features) && in_array('Subdomain', $features))
                @php
                  $subdomain = strtolower($user->username) . '.' . env('WEBSITE_HOST');
                @endphp
                <div class="user-info-row">
                  <span class="lbl">{{ __('Subdomain') }}</span>
                  <span class="val">
                    <a href="//{{ $subdomain }}" target="_blank" class="text-primary font-weight-bold">
                      {{ $subdomain }} <i class="fas fa-external-link-alt ml-1" style="font-size:0.75rem;"></i>
                    </a>
                  </span>
                </div>
              @endif

              @php
                $currPackage = \App\Http\Helpers\UserPermissionHelper::currPackageOrPending($user->id);
                $currMemb = \App\Http\Helpers\UserPermissionHelper::currMembOrPending($user->id);
              @endphp
              <div class="user-info-row">
                <span class="lbl">{{ __('Current Package') }}</span>
                <span class="val">
                  @if ($currPackage)
                    <div class="d-inline-flex align-items-center gap-1">
                      <span class="btn-primary-purple py-1 px-3" style="font-size:0.75rem; border-radius:8px;">{{ __($currPackage->title) }}</span>
                      <span class="btn-back-pill py-1 px-2" style="font-size:0.75rem; border-radius:8px;">{{ __($currPackage->term) }}</span>
                      <button type="button" class="btn-action-square b-edit" data-toggle="modal" data-target="#editCurrentPackage" title="{{ __('Edit Package') }}">
                        <i class="fas fa-pencil-alt"></i>
                      </button>
                      <form action="{{ route('user.currPackage.remove') }}" class="d-inline-block deleteform" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <button type="submit" class="btn-action-square b-delete deletebtn" title="{{ __('Remove Package') }}">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                    <small class="text-muted d-block text-right mt-1">
                      ({{ __('Expire Date') }}: {{ $currPackage->term === 'lifetime' ? __('Lifetime') : Carbon\Carbon::parse($currMemb->expire_date)->format('M d, Y') }})
                    </small>
                  @else
                    <a data-target="#addCurrentPackage" data-toggle="modal" class="btn-primary-purple text-white py-1 px-3" style="font-size:0.78rem;">
                      <i class="fas fa-plus"></i> {{ __('Add Package') }}
                    </a>
                  @endif
                </span>
              </div>

              @php
                $nextPackage = \App\Http\Helpers\UserPermissionHelper::nextPackage($user->id);
                $nextMemb = \App\Http\Helpers\UserPermissionHelper::nextMembership($user->id);
              @endphp
              <div class="user-info-row">
                <span class="lbl">{{ __('Next Package') }}</span>
                <span class="val">
                  @if ($nextPackage)
                    <div class="d-inline-flex align-items-center gap-1">
                      <span class="btn-primary-purple py-1 px-3" style="font-size:0.75rem; border-radius:8px;">{{ __($nextPackage->title) }}</span>
                      <span class="btn-back-pill py-1 px-2" style="font-size:0.75rem; border-radius:8px;">{{ __($nextPackage->term) }}</span>
                      <button type="button" class="btn-action-square b-edit" data-toggle="modal" data-target="#editNextPackage">
                        <i class="fas fa-pencil-alt"></i>
                      </button>
                    </div>
                  @else
                    @if (!empty($currPackage))
                      <a class="btn-back-pill py-1 px-3" data-toggle="modal" data-target="#addNextPackage" style="font-size:0.78rem; border: 1px dashed #6366F1 !important; cursor:pointer;">
                        <i class="fas fa-plus"></i> {{ __('Add Package') }}
                      </a>
                    @else
                      -
                    @endif
                  @endif
                </span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Shop Name') }}</span>
                <span class="val font-weight-bold">{{ $user->shop_name ?? '-' }}</span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Shop Category') }}</span>
                <span class="val font-weight-bold">{{ $category ?? '-' }}</span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Email') }}</span>
                <span class="val text-muted">{{ $user->email ?? '-' }}</span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Phone Number') }}</span>
                <span class="val font-weight-bold">{{ $user->phone ?? '-' }}</span>
              </div>
            </div>

            <!-- Right Side Details -->
            <div class="col-lg-6 pl-lg-4">
              <div class="user-info-row">
                <span class="lbl">{{ __('City') }}</span>
                <span class="val text-muted">{{ $user->city ?? '-' }}</span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('State') }}</span>
                <span class="val text-muted">{{ $user->state ?? '-' }}</span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Country') }}</span>
                <span class="val text-muted">{{ $user->country ?? '-' }}</span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Address') }}</span>
                <span class="val text-muted">{{ $user->address ?? '-' }}</span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Country Code') }}</span>
                <span class="val font-weight-bold">+91</span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Email Status') }}</span>
                <span class="val">
                  @if ($user->email_verified == 1)
                    <span class="status-pill-active"><i class="fas fa-check-circle"></i> {{ __('Verified') }}</span>
                  @else
                    <span class="status-pill-deactive"><i class="fas fa-times-circle"></i> {{ __('Not Verified') }}</span>
                  @endif
                </span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Account Status') }}</span>
                <span class="val">
                  @if ($user->status == 1)
                    <span class="status-pill-active"><i class="fas fa-check-circle"></i> {{ __('Active') }}</span>
                  @else
                    <span class="status-pill-deactive"><i class="fas fa-user-slash"></i> {{ __('Banned') }}</span>
                  @endif
                </span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('Plan Status') }}</span>
                <span class="val">
                  <span class="status-pill-warning"><i class="fas fa-exclamation-triangle"></i> {{ __('Not Purchased') }}</span>
                </span>
              </div>

              <div class="user-info-row">
                <span class="lbl">{{ __('OTP Sent At') }}</span>
                <span class="val text-muted" style="font-size:0.8rem;">17 Aug 2026, 10:50 PM</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @includeIf('admin.register_user.edit-current-package')
  @includeIf('admin.register_user.add-current-package')
  @includeIf('admin.register_user.edit-next-package')
  @includeIf('admin.register_user.add-next-package')
@endsection
