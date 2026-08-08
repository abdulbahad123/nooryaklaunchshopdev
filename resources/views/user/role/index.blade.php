@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Staff Roles & Permissions') }}</h4>
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
        <a href="#">{{ __('Staff Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Roles & Permissions') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Roles & Permissions') }}</div>
          <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#createModal">
            <i class="fas fa-plus"></i> {{ __('Add Role') }}
          </button>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($roles) == 0)
                <h3 class="text-center py-4">{{ __('NO ROLE FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Role Name') }}</th>
                        <th scope="col">{{ __('Permissions') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($roles as $key => $role)
                        @php
                          $permissions = !empty($role->permissions) ? json_decode($role->permissions, true) : [];
                        @endphp
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td><strong>{{ $role->name }}</strong></td>
                          <td>
                            @if (!empty($permissions) && is_array($permissions))
                              @foreach ($permissions as $perm)
                                <span class="badge badge-info mb-1 mr-1">{{ $perm }}</span>
                              @endforeach
                            @else
                              <span class="text-muted">{{ __('No permissions') }}</span>
                            @endif
                          </td>
                          <td>
                            <a href="{{ route('user.role.edit', $role->id) }}" class="btn btn-secondary btn-sm editbtn me-1">
                              <i class="fas fa-edit"></i> {{ __('Edit') }}
                            </a>
                            <form class="deleteform d-inline-block" action="{{ route('user.role.delete') }}" method="post">
                              @csrf
                              <input type="hidden" name="role_id" value="{{ $role->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                              </button>
                            </form>
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
      </div>
    </div>
  </div>

  <!-- Create Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createModalTitle">{{ __('Add Staff Role') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form id="ajaxForm" class="modal-form" action="{{ route('user.role.store') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Role Name') }} *</label>
              <input type="text" class="form-control" name="name" placeholder="{{ __('Enter role name e.g. Catalog Manager') }}">
              <p id="errname" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label class="form-label"><strong>{{ __('Assign Granular Permissions') }} *</strong></label>
              <p id="errpermissions" class="mb-2 text-danger em"></p>

              @php
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
                          <input type="checkbox" class="custom-control-input" id="perm_{{ Str::slug($key) }}" name="permissions[]" value="{{ $key }}">
                          <label class="custom-control-label" for="perm_{{ Str::slug($key) }}">{{ __($label) }}</label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
