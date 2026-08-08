@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Staff Role') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('user-dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('user.role.index') }}">{{ __('Roles & Permissions') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Role') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Role & Permissions') }}</div>
          <a class="btn btn-info btn-sm float-right" href="{{ route('user.role.index') }}">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row justify-content-center">
            <div class="col-lg-10">
              <form id="ajaxForm" action="{{ route('user.role.update') }}" method="POST">
                @csrf
                <input type="hidden" name="role_id" value="{{ $role->id }}">

                <div class="form-group">
                  <label for="">{{ __('Role Name') }} *</label>
                  <input type="text" class="form-control" name="name" value="{{ $role->name }}" placeholder="{{ __('Enter role name') }}">
                  <p id="errname" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label class="form-label"><strong>{{ __('Assign Granular Permissions') }} *</strong></label>
                  <p id="errpermissions" class="mb-2 text-danger em"></p>

                  @php
                    $rolePermissions = !empty($role->permissions) ? json_decode($role->permissions, true) : [];
                    $permissionGroups = [
                        'Shop & Catalog Management' => [
                            'Shop Management' => 'Full Shop Management (All items below)',
                            'Products' => 'Full Products Access',
                            'Categories' => 'Categories',
                            'Subcategories' => 'Subcategories',
                            'Product Labels' => 'Product Labels',
                            'Product Variants' => 'Product Variants',
                            'Products / Items' => 'Products / Items List',
                            'Orders' => 'Orders Management',
                            'Sales Report' => 'Sales Report',
                        ],
                        'Store Configuration & Marketing' => [
                            'Coupons' => 'Coupons',
                            'Shipping Charges' => 'Shipping Charges',
                            'Shipping Gateways' => 'Shipping Gateways',
                            'Currencies' => 'Currencies',
                            'Shop Settings' => 'Shop Settings',
                        ],
                        'Customers & Administration' => [
                            'Registered Customers' => 'Registered Customers',
                            'Pages' => 'Pages & Content',
                            'Subscribers' => 'Subscribers',
                            'Push Notification' => 'Push Notification',
                            'Staff Management' => 'Staff Management',
                        ]
                    ];
                  @endphp

                  @foreach ($permissionGroups as $groupTitle => $groupItems)
                    <div class="border rounded p-3 mb-3 bg-light">
                      <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">{{ __($groupTitle) }}</h6>
                      <div class="row">
                        @foreach ($groupItems as $key => $label)
                          <div class="col-md-6 mb-2">
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input" id="edit_perm_{{ Str::slug($key) }}" name="permissions[]" value="{{ $key }}" {{ is_array($rolePermissions) && in_array($key, $rolePermissions) ? 'checked' : '' }}>
                              <label class="custom-control-label" for="edit_perm_{{ Str::slug($key) }}">{{ __($label) }}</label>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer text-center">
          <button id="submitBtn" type="button" class="btn btn-success">{{ __('Update Role') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
