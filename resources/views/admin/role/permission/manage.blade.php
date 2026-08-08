@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Roles & Permissions') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Admins Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Roles & Permissions') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Permissions Management') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">"{{ $role->name }}" - {{ __('Permissions Management') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.role.index') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body">
          <form id="permissionsForm" action="{{ route('admin.role.permissions.update') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="role_id" value="{{ Request::route('id') }}">
            <input type="hidden" name="permissions[]" value="Dashboard">

            @php
              $permissions = $role->permissions;
              if (!empty($role->permissions)) {
                  $permissions = json_decode($permissions, true);
              }
            @endphp

            <!-- Group 1: Users Management -->
            <div class="card border mb-4" style="background-color: #fafbfc; border-radius: 8px;">
              <div class="card-header bg-transparent" style="border-bottom: 1px solid #ebedf2;">
                <h5 class="mb-0 font-weight-bold text-primary">{{ __('Users Management & Submenus') }}</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Users Management" id="perm_users_mgmt" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Users Management', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_users_mgmt">
                        {{ __('Full Users Management Access') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Categories" id="perm_categories" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Categories', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_categories">
                        {{ __('Categories') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Registered Users" id="perm_registered_users" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Registered Users', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_registered_users">
                        {{ __('Registered Users') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Non-Verified Users" id="perm_nonverified_users" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Non-Verified Users', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_nonverified_users">
                        {{ __('Non-Verified Users') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Subscribers" id="perm_subscribers" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Subscribers', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_subscribers">
                        {{ __('Subscribers') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Mail to Subscribers" id="perm_mail_subscribers" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Mail to Subscribers', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_mail_subscribers">
                        {{ __('Mail to Subscribers') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Group 2: Package & Financial Management -->
            <div class="card border mb-4" style="background-color: #fafbfc; border-radius: 8px;">
              <div class="card-header bg-transparent" style="border-bottom: 1px solid #ebedf2;">
                <h5 class="mb-0 font-weight-bold text-primary">{{ __('Package & Financial Management') }}</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Package Management" id="perm_package_mgmt" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Package Management', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_package_mgmt">
                        {{ __('Package Management') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Payment Logs" id="perm_payment_logs" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Payment Logs', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_payment_logs">
                        {{ __('Payment Logs') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Group 3: Store Configuration & Marketing -->
            <div class="card border mb-4" style="background-color: #fafbfc; border-radius: 8px;">
              <div class="card-header bg-transparent" style="border-bottom: 1px solid #ebedf2;">
                <h5 class="mb-0 font-weight-bold text-primary">{{ __('Store Configuration & Marketing') }}</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Custom Domains" id="perm_custom_domains" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Custom Domains', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_custom_domains">
                        {{ __('Custom Domains') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Subdomains" id="perm_subdomains" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Subdomains', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_subdomains">
                        {{ __('Subdomains') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Menu Builder" id="perm_menu_builder" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Menu Builder', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_menu_builder">
                        {{ __('Menu Builder') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Pages" id="perm_pages" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Pages', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_pages">
                        {{ __('Pages') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Announcement Popup" id="perm_announcement_popup" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Announcement Popup', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_announcement_popup">
                        {{ __('Announcement Popup') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Push Notification" id="perm_push_notification" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Push Notification', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_push_notification">
                        {{ __('Push Notification') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Sitemaps" id="perm_sitemaps" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Sitemaps', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_sitemaps">
                        {{ __('Sitemaps') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Group 4: Store & System Administration -->
            <div class="card border mb-4" style="background-color: #fafbfc; border-radius: 8px;">
              <div class="card-header bg-transparent" style="border-bottom: 1px solid #ebedf2;">
                <h5 class="mb-0 font-weight-bold text-primary">{{ __('Store & System Administration') }}</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Shops" id="perm_shops" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Shops', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_shops">
                        {{ __('Shops') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Settings" id="perm_settings" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Settings', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_settings">
                        {{ __('Settings') }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="permissions[]" value="Admins Management" id="perm_admins_mgmt" class="custom-control-input"
                        @if (is_array($permissions) && in_array('Admins Management', $permissions)) checked @endif>
                      <label class="custom-control-label font-weight-bold text-dark" for="perm_admins_mgmt">
                        {{ __('Admins Management') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </form>
        </div>
        <div class="card-footer text-center">
          <button type="submit" onclick="document.getElementById('permissionsForm').submit();" class="btn btn-success px-5 font-weight-bold">
            {{ __('Update Permissions') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection
