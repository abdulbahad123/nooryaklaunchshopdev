@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Registered Users') }}
    </h4>
    <ul class="breadcrumbs">
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
        <a href="#">{{ __('Registered Users') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">

      {{-- Tab Navigation --}}
      <div class="d-flex align-items-center gap-3 mb-4">
        <a class="btn {{ request()->input('active_tab') !== 'verified' ? 'btn-primary-purple' : 'btn-light text-muted' }} px-4 py-2"
          id="registered-tab" href="{{ route('admin.register.user') }}" style="border-radius: 12px; font-weight: 700;">
          <i class="fas fa-users mr-2"></i> {{ __('Registered Customers') }}
          @if(isset($users) && method_exists($users, 'total'))
            <span class="badge badge-light text-primary ml-2 px-2 py-1" style="border-radius: 20px;">{{ $users->total() }}</span>
          @endif
        </a>

        <a class="btn {{ request()->input('active_tab') === 'verified' ? 'btn-primary-purple' : 'btn-light text-muted' }} px-4 py-2"
          id="verified-tab" href="{{ route('admin.register.user', ['active_tab' => 'verified', 'lead_filter' => request()->input('lead_filter', 'all')]) }}" style="border-radius: 12px; font-weight: 700;">
          <i class="fas fa-phone-square-alt mr-2"></i> {{ __('Verified Users') }}
          @php
            try { $leadCount = \App\Models\VerifiedPhoneLead::count(); } catch(\Exception $e) { $leadCount = 0; }
          @endphp
          @if($leadCount > 0)
            <span class="badge badge-success ml-2 px-2 py-1" style="border-radius: 20px;">{{ $leadCount }}</span>
          @endif
        </a>
      </div>

      {{-- ============================================================= --}}
      {{-- TAB 1 : Registered Customers --}}
      {{-- ============================================================= --}}
      @if(request()->input('active_tab') !== 'verified')
      <div class="card">
        <div class="card-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
          <div class="card-title m-0">
            {{ __('Registered Users') }}
            @if(isset($users) && method_exists($users, 'total'))
              <span class="badge badge-pill badge-primary font-weight-bold ml-2 px-3 py-1" style="border-radius: 20px; font-size: 0.78rem;">{{ $users->total() }} {{ __('Clients Total') }}</span>
            @endif
          </div>
          <div class="d-flex align-items-center gap-3">
            <button class="btn btn-danger btn-sm d-none bulk-delete"
              data-href="{{ route('register.user.bulk.delete') }}"><i class="flaticon-interface-5"></i>
              {{ __('Delete') }}</button>
            <form action="{{ url()->full() }}" class="m-0">
              <input type="text" name="term" class="form-control" value="{{ request()->input('term') }}"
                placeholder="{{ __('Search by Username / Email') }}" style="min-width: 260px;">
            </form>
            <button class="btn-primary-purple m-0" data-toggle="modal" data-target="#addUserModal" style="white-space: nowrap !important;">
              <i class="fas fa-plus"></i> {{ __('Add User') }}
            </button>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($users) == 0)
                <h3 class="text-center py-4 text-muted">{{ __('NO USER FOUND') }}</h3>
              @else
                <div class="table-responsive" style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch; min-height: 400px;">
                  <table class="table table-striped align-middle" style="white-space: nowrap !important; min-width: 1200px;">
                    <thead>
                      <tr>
                        <th scope="col" style="width: 40px; white-space: nowrap !important;">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col" style="white-space: nowrap !important;">{{ __('Username') }}</th>
                        <th scope="col" style="white-space: nowrap !important;">{{ __('Email') }}</th>
                        <th scope="col" style="white-space: nowrap !important;">{{ __('Product') }}</th>
                        <th scope="col" style="white-space: nowrap !important;">{{ __('Featured') }}</th>
                        <th scope="col" style="white-space: nowrap !important;">{{ __('Preview Template') }}</th>
                        <th scope="col" style="white-space: nowrap !important;">{{ __('WhatsApp') }}</th>
                        <th scope="col" style="white-space: nowrap !important;">{{ __('Email Status') }}</th>
                        <th scope="col" style="white-space: nowrap !important;">{{ __('Account') }}</th>
                        <th scope="col" class="text-right" style="white-space: nowrap !important;">{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($users as $key => $user)
                        @php
                            $uInitials = strtoupper(substr($user->username, 0, 2));
                            $avatarClasses = ['a-purple', 'a-orange', 'a-green', 'a-blue'];
                            $avatarClass = $avatarClasses[$key % 4];
                        @endphp
                        <tr>
                          <td style="white-space: nowrap !important;">
                            <input type="checkbox" class="bulk-check" data-val="{{ $user->id }}">
                          </td>
                          <td style="white-space: nowrap !important;">
                            <div class="d-flex align-items-center">
                              <span class="user-avatar-initials {{ $avatarClass }}">
                                {{ $uInitials }}
                              </span>
                              <span class="font-weight-bold">{{ $user->username }}</span>
                            </div>
                          </td>
                          <td style="white-space: nowrap !important;">{{ $user->email }}</td>
                          <td style="white-space: nowrap !important;">
                            <span class="product-tag-pill">
                              <i class="fas fa-shopping-bag"></i> Launchshop
                            </span>
                          </td>

                          <td style="white-space: nowrap !important;">
                            <form id="userFrom{{ $user->id }}" class="d-inline-block"
                              action="{{ route('register.user.featured') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $user->featured == 1 ? 'status-pill-active' : 'status-pill-deactive' }}"
                                name="featured"
                                onchange="document.getElementById('userFrom{{ $user->id }}').submit();" style="height:32px;">
                                <option value="1" {{ $user->featured == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                <option value="0" {{ $user->featured == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                          </td>

                          <td style="white-space: nowrap !important;">
                            <div class="d-inline-block">
                              <select data-user_id="{{ $user->id }}"
                                class="template-select form-control form-control-sm {{ $user->preview_template == 1 ? 'status-pill-active' : 'status-pill-deactive' }}"
                                name="preview_template" style="height:32px;">
                                <option value="1" {{ $user->preview_template == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                <option value="0" {{ $user->preview_template == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                              </select>
                            </div>
                            @if ($user->preview_template == 1)
                              <button type="button" class="btn btn-primary btn-sm ml-1" data-toggle="modal"
                                data-target="#templateImgModal{{ $user->id }}">{{ __('Edit') }}</button>
                            @endif
                          </td>

                          @includeIf('admin.register_user.template-modal')
                          @includeIf('admin.register_user.template-image-modal')

                          <td style="white-space: nowrap !important;">
                            <form id="whatsappForm{{ $user->id }}" class="d-inline-block"
                              action="{{ route('register.user.whatsapp') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $user->whatsapp_status == 1 ? 'status-pill-active' : 'status-pill-deactive' }}"
                                name="whatsapp_status"
                                onchange="document.getElementById('whatsappForm{{ $user->id }}').submit();" style="height:32px;">
                                <option value="1" {{ $user->whatsapp_status == 1 ? 'selected' : '' }}>{{ __('Enable') }}</option>
                                <option value="0" {{ $user->whatsapp_status == 0 ? 'selected' : '' }}>{{ __('Disable') }}</option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                          </td>

                          <td style="white-space: nowrap !important;">
                            <form id="emailForm{{ $user->id }}" class="d-inline-block"
                              action="{{ route('register.user.email') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ strtolower($user->email_verified) == 1 ? 'status-pill-active' : 'status-pill-deactive' }}"
                                name="email_verified"
                                onchange="document.getElementById('emailForm{{ $user->id }}').submit();" style="height:32px;">
                                <option value="1" {{ strtolower($user->email_verified) == 1 ? 'selected' : '' }}>{{ __('Verified') }}</option>
                                <option value="0" {{ strtolower($user->email_verified) == 0 ? 'selected' : '' }}>{{ __('Unverified') }}</option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                          </td>

                          <td style="white-space: nowrap !important;">
                            <form id="statusForm{{ $user->id }}" class="d-inline-block"
                              action="{{ route('register.user.ban') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $user->status == 1 ? 'status-pill-active' : 'status-pill-deactive' }}"
                                name="status"
                                onchange="document.getElementById('statusForm{{ $user->id }}').submit();" style="height:32px;">
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                          </td>
                          <td class="text-right" style="white-space: nowrap !important;">
                            <div class="dropdown d-inline-block">
                              <button class="btn btn-primary-purple btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" style="padding: 0.35rem 0.85rem; font-size: 0.8rem; white-space: nowrap;">
                                {{ __('Actions') }}
                              </button>
                              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('register.user.view', $user->id) }}"><i class="fas fa-info-circle mr-2"></i>{{ __('Details') }}</a>
                                <a class="dropdown-item" href="{{ route('register.user.changePass', $user->id) }}"><i class="fas fa-key mr-2"></i>{{ __('Change Password') }}</a>
                                <button class="dropdown-item editbtn editBtn" data-toggle="modal" data-target="#mailModal" data-email="{{ $user->email }}"><i class="fas fa-envelope mr-2"></i>{{ __('Mail') }}</button>
                                <form class="deleteform d-block" action="{{ route('register.user.delete') }}" method="post">
                                  @csrf
                                  <input type="hidden" name="user_id" value="{{ $user->id }}">
                                  <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-trash mr-2"></i>{{ __('Delete') }}
                                  </button>
                                </form>
                                <a target="_blank" class="dropdown-item" href="{{ route('register.user.secret_login', $user->id) }}"><i class="fas fa-sign-in-alt mr-2"></i>{{ __('Secret Login') }}</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $users->appends(['term' => request()->input('term')])->links() }}
            </div>
          </div>
        </div>
      </div>
      @endif

      {{-- ============================================================= --}}
      {{-- TAB 2 : Verified Users --}}
      {{-- ============================================================= --}}
      @if(request()->input('active_tab') === 'verified')
      <div class="card">
        <div class="card-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
          <div class="card-title m-0">
            <i class="fas fa-phone-square-alt mr-2 text-info"></i>
            {{ __('Verified Users') }}
            <small class="text-muted ml-2">{{ __('Users who requested OTP during registration') }}</small>
          </div>
          <div class="d-flex align-items-center gap-3">
            <form action="{{ route('admin.register.user') }}" method="GET" class="d-flex align-items-center gap-2 m-0">
              <input type="hidden" name="active_tab" value="verified">
              <select name="lead_filter" class="form-control form-control-sm" onchange="this.form.submit()" style="min-width:170px;">
                <option value="all" {{ $leadFilter === 'all' ? 'selected' : '' }}>{{ __('All Verified Users') }}</option>
                <option value="purchased" {{ $leadFilter === 'purchased' ? 'selected' : '' }}>{{ __('Purchased Plan') }}</option>
                <option value="not_purchased" {{ $leadFilter === 'not_purchased' ? 'selected' : '' }}>{{ __('Not Purchased') }}</option>
              </select>
            </form>
          </div>
        </div>
        <div class="card-body">
          @php
            $tableReady = true;
            try { \App\Models\VerifiedPhoneLead::first(); } catch(\Exception $e) { $tableReady = false; }
          @endphp
          @if(!$tableReady)
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle mr-1"></i>
              {{ __('The verified_phone_leads table does not exist yet.') }}
            </div>
          @elseif($verifiedLeads->total() === 0)
            <h3 class="text-center text-muted py-4">
              <i class="fas fa-phone-slash d-block mb-2" style="font-size:48px;"></i>
              {{ __('No verified phone leads found') }}
            </h3>
          @else
            <div class="table-responsive" style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch; min-height: 360px;">
              <table class="table table-striped align-middle" style="white-space: nowrap !important; min-width: 900px;">
                <thead>
                  <tr>
                    <th scope="col" style="white-space: nowrap !important;">#</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('Name') }}</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('Phone Number') }}</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('Country Code') }}</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('Plan Status') }}</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('OTP Sent At') }}</th>
                    <th scope="col" class="text-right" style="white-space: nowrap !important;">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($verifiedLeads as $lead)
                  <tr id="lead-row-{{ $lead->id }}">
                    <td style="white-space: nowrap !important;">{{ $lead->id }}</td>
                    <td style="white-space: nowrap !important;">{{ $lead->name ?: '-' }}</td>
                    <td style="white-space: nowrap !important;"><strong>{{ $lead->phone }}</strong></td>
                    <td style="white-space: nowrap !important;">{{ $lead->country_code ?: '-' }}</td>
                    <td id="lead-status-cell-{{ $lead->id }}" style="white-space: nowrap !important;">
                      @if($lead->purchased)
                        <span class="status-pill-active"><i class="fas fa-check mr-1"></i>{{ __('Purchased') }}</span>
                      @else
                        <span class="status-pill-deactive"><i class="fas fa-clock mr-1"></i>{{ __('Not Purchased') }}</span>
                      @endif
                    </td>
                    <td style="white-space: nowrap !important;">{{ $lead->otp_sent_at ? \Carbon\Carbon::parse($lead->otp_sent_at, 'UTC')->setTimezone(config('app.timezone', 'Asia/Kolkata'))->format('d M Y, h:i A') : '-' }}</td>
                    <td class="text-right" style="white-space: nowrap !important;">
                      <button class="btn-action-square b-edit view-lead-btn" 
                              data-id="{{ $lead->id }}"
                              data-name="{{ $lead->name }}"
                              data-phone="{{ $lead->phone }}"
                              data-country_code="{{ $lead->country_code }}"
                              data-email="{{ $lead->email }}"
                              data-purchased="{{ $lead->purchased ? 1 : 0 }}"
                              data-status="{{ $lead->status ?: 'Not Purchased' }}"
                              data-status_date="{{ $lead->status_date ? \Carbon\Carbon::parse($lead->status_date, 'UTC')->setTimezone(config('app.timezone', 'Asia/Kolkata'))->format('Y-m-d\TH:i') : '' }}"
                              data-otp_sent_at="{{ $lead->otp_sent_at ? \Carbon\Carbon::parse($lead->otp_sent_at, 'UTC')->setTimezone(config('app.timezone', 'Asia/Kolkata'))->format('d M Y, h:i A') : '-' }}"
                              data-toggle="modal" 
                              data-target="#viewLeadModal"
                              title="{{ __('View Details') }}">
                        <i class="fas fa-eye"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
        @if(isset($verifiedLeads) && $verifiedLeads->total() > 0)
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $verifiedLeads->appends(['active_tab' => 'verified', 'lead_filter' => $leadFilter])->links() }}
            </div>
          </div>
        </div>
        @endif
      </div>
      @endif

    </div>
  </div>

  <!-- View Lead Details Modal (Fixes Task 4: View button working) -->
  <div class="modal fade" id="viewLeadModal" tabindex="-1" role="dialog" aria-labelledby="viewLeadModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content lead-modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
        <div class="modal-header border-0 pb-2 pt-4 px-4 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-3">
            <span class="cat-icon-badge i-purple m-0" style="width:44px; height:44px; font-size:1.15rem; border-radius: 12px;">
              <i class="fas fa-user-clock"></i>
            </span>
            <div>
              <h5 class="modal-title font-weight-bold m-0" id="viewLeadModalTitle" style="font-size: 1.15rem; color: var(--text-main);">{{ __('Lead Details') }}</h5>
              <small class="text-muted" style="font-size: 0.8rem;">{{ __('View and manage lead information') }}</small>
            </div>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; color: var(--text-muted); opacity: 0.8;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <div class="alert alert-success d-none" id="lead-success-alert"></div>
          <div class="alert alert-danger d-none" id="lead-error-alert"></div>
          
          <div class="lead-modal-grid-card mb-4" style="background: var(--table-header-bg); border-radius: 14px; padding: 20px; border: 1px solid var(--border-card);">
            <div class="row g-3">
              <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-purple" style="width:36px; height:36px; border-radius:10px; background:#F3E8FF; color:#7C3AED; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-user" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Name') }}</span>
                    <span class="font-weight-bold" id="lead-detail-name" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-orange" style="width:36px; height:36px; border-radius:10px; background:#FFEDD5; color:#EA580C; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-envelope" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Email') }}</span>
                    <span class="font-weight-bold" id="lead-detail-email" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-green" style="width:36px; height:36px; border-radius:10px; background:#DCFCE7; color:#16A34A; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-phone" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Phone Number') }}</span>
                    <span class="font-weight-bold" id="lead-detail-phone" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-blue" style="width:36px; height:36px; border-radius:10px; background:#DBEAFE; color:#2563EB; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-globe" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Country Code') }}</span>
                    <span class="font-weight-bold" id="lead-detail-country-code" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-cyan" style="width:36px; height:36px; border-radius:10px; background:#E0F2FE; color:#0284C7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-clock" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('OTP Sent At') }}</span>
                    <span class="font-weight-bold" id="lead-detail-otp-sent" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <form id="leadUpdateForm">
            @csrf
            <input type="hidden" name="id" id="lead-id-input">
            
            <div class="form-group px-0 mb-3">
              <label for="lead-status-select" style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">{{ __('Plan Status') }}</label>
              <select name="status" id="lead-status-select" class="form-control" style="border-radius: 10px; height: 44px; font-size: 0.875rem;">
                <option value="Purchased">⚠️ {{ __('Purchased') }}</option>
                <option value="Not Purchased">⚠️ {{ __('Not Purchased') }}</option>
                <option value="Follow Up">⚠️ {{ __('Follow Up') }}</option>
                <option value="Interested">⚠️ {{ __('Interested') }}</option>
                <option value="Not Interested">⚠️ {{ __('Not Interested') }}</option>
              </select>
            </div>
            
            <div class="form-group px-0 mb-4">
              <label for="lead-status-date-input" style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">{{ __('Status Date') }}</label>
              <input type="datetime-local" name="status_date" id="lead-status-date-input" class="form-control" style="border-radius: 10px; height: 44px; font-size: 0.875rem;">
              <small class="text-warning-note d-block mt-1">{{ __('For follow up status, the date must be today or in the future.') }}</small>
            </div>
            
            <div class="d-flex justify-content-between align-items-center pt-2">
              <button type="button" class="btn btn-outline-danger btn-sm" id="deleteLeadBtn" style="border-radius: 10px; padding: 8px 16px; font-weight: 600;">
                <i class="fas fa-trash mr-1"></i> {{ __('Delete Lead') }}
              </button>
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-light btn-sm" data-dismiss="modal" style="border-radius: 10px; padding: 8px 18px; font-weight: 600;">{{ __('Close') }}</button>
                <button type="submit" class="btn-primary-purple m-0 py-2 px-4" id="saveLeadBtn" style="border-radius: 10px; font-size: 0.875rem;">
                  <i class="fas fa-save mr-1"></i> {{ __('Save Changes') }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      $(document).on('click', '.view-lead-btn', function () {
        var btn = $(this);
        var id = btn.data('id');
        var name = btn.data('name') || '-';
        var email = btn.data('email') || '-';
        var phone = btn.data('phone') || '-';
        var countryCode = btn.data('country_code') || '-';
        var otpSentAt = btn.data('otp_sent_at') || '-';
        var status = btn.data('status') || 'Not Purchased';
        var statusDate = btn.data('status_date') || '';
        
        $('#lead-id-input').val(id);
        $('#lead-detail-name').text(name);
        $('#lead-detail-email').text(email);
        $('#lead-detail-phone').text(phone);
        $('#lead-detail-country-code').text(countryCode);
        $('#lead-detail-otp-sent').text(otpSentAt);
        $('#lead-status-select').val(status);
        $('#lead-status-date-input').val(statusDate);
        
        $('#lead-success-alert').addClass('d-none').text('');
        $('#lead-error-alert').addClass('d-none').text('');
        $('#saveLeadBtn').prop('disabled', false);
      });
      
      $('#leadUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = $('#saveLeadBtn');
        
        $('#lead-success-alert').addClass('d-none').text('');
        $('#lead-error-alert').addClass('d-none').text('');
        submitBtn.prop('disabled', true);
        
        $.ajax({
          url: "{{ route('admin.register.lead.updateStatus') }}",
          method: "POST",
          data: form.serialize(),
          success: function (response) {
            if (response.success) {
              $('#lead-success-alert').removeClass('d-none').text(response.message);
              
              var leadBtn = $('.view-lead-btn[data-id="' + response.lead.id + '"]');
              leadBtn.data('status', response.lead.status);
              leadBtn.data('status_date', response.lead.status_date);
              leadBtn.data('purchased', response.lead.purchased);
              
              var statusCell = $('#lead-status-cell-' + response.lead.id);
              if (statusCell.length) {
                var badgesHtml = '';
                if (response.lead.purchased || response.lead.status === 'Purchased') {
                  badgesHtml = '<span class="status-pill-active"><i class="fas fa-check-circle"></i> Purchased</span>';
                } else {
                  badgesHtml = '<span class="status-pill-warning"><i class="fas fa-exclamation-triangle"></i> Not Purchased</span>';
                }
                statusCell.html(badgesHtml);
              }
              
              setTimeout(function () {
                $('#viewLeadModal').modal('hide');
              }, 1000);
            }
          },
          error: function (xhr) {
            submitBtn.prop('disabled', false);
            var errorMsg = "{{ __('An error occurred. Please try again.') }}";
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMsg = xhr.responseJSON.message;
            }
            $('#lead-error-alert').removeClass('d-none').text(errorMsg);
          }
        });
      });
      
      $('#deleteLeadBtn').on('click', function () {
        var id = $('#lead-id-input').val();
        if (confirm("{{ __('Are you sure you want to delete this lead? This will perform a soft delete.') }}")) {
          var btn = $(this);
          btn.prop('disabled', true);
          
          $.ajax({
            url: "{{ route('admin.register.lead.delete') }}",
            method: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              id: id
            },
            success: function (response) {
              if (response.success) {
                $('#lead-success-alert').removeClass('d-none').text(response.message);
                $('#lead-row-' + id).remove();
                setTimeout(function () {
                  $('#viewLeadModal').modal('hide');
                  btn.prop('disabled', false);
                }, 1000);
              }
            },
            error: function (xhr) {
              btn.prop('disabled', false);
              var errorMsg = "{{ __('Failed to delete lead. Please try again.') }}";
              if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
              }
              $('#lead-error-alert').removeClass('d-none').text(errorMsg);
            }
          });
        }
      });
    });
  </script>
@endsection
