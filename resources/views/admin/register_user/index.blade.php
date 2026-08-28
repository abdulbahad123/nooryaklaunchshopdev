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

      {{-- Tab Navigation (Matching Reference Image 2) --}}
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
            <button class="btn-primary-purple m-0" data-toggle="modal" data-target="#addUserModal">
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
                <div class="table-responsive">
                  <table class="table table-striped align-middle">
                    <thead>
                      <tr>
                        <th scope="col" style="width: 40px;">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Product') }}</th>
                        <th scope="col">{{ __('Featured') }}</th>
                        <th scope="col">{{ __('Preview Template') }}</th>
                        <th scope="col">{{ __('WhatsApp') }}</th>
                        <th scope="col">{{ __('Email Status') }}</th>
                        <th scope="col">{{ __('Account') }}</th>
                        <th scope="col" class="text-right">{{ __('Action') }}</th>
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
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $user->id }}">
                          </td>
                          <td>
                            <div class="d-flex align-items-center">
                              <span class="user-avatar-initials {{ $avatarClass }}">
                                {{ $uInitials }}
                              </span>
                              <span class="font-weight-bold">{{ $user->username }}</span>
                            </div>
                          </td>
                          <td>{{ $user->email }}</td>
                          <td>
                            <span class="product-tag-pill">
                              <i class="fas fa-shopping-bag"></i> Launchshop
                            </span>
                          </td>

                          <td>
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

                          <td>
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

                          <td>
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

                          <td>
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

                          <td>
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
                          <td class="text-right">
                            <div class="dropdown d-inline-block">
                              <button class="btn btn-primary-purple btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" style="padding: 0.35rem 0.85rem; font-size: 0.8rem;">
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
            <div class="table-responsive">
              <table class="table table-striped align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Phone Number') }}</th>
                    <th>{{ __('Country Code') }}</th>
                    <th>{{ __('Plan Status') }}</th>
                    <th>{{ __('OTP Sent At') }}</th>
                    <th class="text-right">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($verifiedLeads as $lead)
                  <tr id="lead-row-{{ $lead->id }}">
                    <td>{{ $lead->id }}</td>
                    <td>{{ $lead->name ?: '-' }}</td>
                    <td><strong>{{ $lead->phone }}</strong></td>
                    <td>{{ $lead->country_code ?: '-' }}</td>
                    <td id="lead-status-cell-{{ $lead->id }}">
                      @if($lead->purchased)
                        <span class="status-pill-active"><i class="fas fa-check mr-1"></i>{{ __('Purchased') }}</span>
                      @else
                        <span class="status-pill-deactive"><i class="fas fa-clock mr-1"></i>{{ __('Not Purchased') }}</span>
                      @endif
                    </td>
                    <td>{{ $lead->otp_sent_at ? \Carbon\Carbon::parse($lead->otp_sent_at, 'UTC')->setTimezone(config('app.timezone', 'Asia/Kolkata'))->format('d M Y, h:i A') : '-' }}</td>
                    <td class="text-right">
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
                              data-target="#viewLeadModal">
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
@endsection

