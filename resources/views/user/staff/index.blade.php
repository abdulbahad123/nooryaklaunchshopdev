@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Staff Members') }}</h4>
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
        <a href="#">{{ __('Manage Staff') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Staff Members List') }}</div>
          <a class="btn btn-primary btn-sm float-right" href="{{ route('user.staff.create') }}">
            <i class="fas fa-plus"></i> {{ __('Add Staff Member') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($staffs) == 0)
                <h3 class="text-center py-4">{{ __('NO STAFF MEMBER FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Role') }}</th>
                        <th scope="col">{{ __('Account Status') }}</th>
                        <th scope="col">{{ __('Login Indicator') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($staffs as $key => $staff)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            @if (!empty($staff->image))
                              <img src="{{ asset('assets/front/img/user/' . $staff->image) }}" alt="" width="45" class="rounded-circle">
                            @else
                              <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="" width="45" class="rounded-circle">
                            @endif
                          </td>
                          <td><strong>{{ $staff->first_name . ' ' . $staff->last_name }}</strong></td>
                          <td><code>{{ $staff->username }}</code></td>
                          <td>{{ $staff->email }}</td>
                          <td>
                            @if ($staff->role)
                              <span class="badge badge-info">{{ $staff->role->name }}</span>
                            @else
                              <span class="badge badge-secondary">{{ __('No Role') }}</span>
                            @endif
                          </td>
                          <td>
                            <form class="d-inline-block" action="{{ route('user.staff.status') }}" method="post">
                              @csrf
                              <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                              <select class="form-control form-control-sm {{ $staff->status == 1 ? 'bg-success text-white' : 'bg-danger text-white' }}" name="status" onchange="this.form.submit()">
                                <option value="1" {{ $staff->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="0" {{ $staff->status == 0 ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                              </select>
                            </form>
                          </td>
                          <td>
                            @if (!empty($staff->last_login_at))
                              <span class="badge badge-success"><i class="fas fa-check-circle"></i> {{ __('Logged In') }}</span>
                              <br>
                              <small class="text-muted"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($staff->last_login_at)->diffForHumans() }}</small>
                            @else
                              <span class="badge badge-warning text-dark"><i class="fas fa-exclamation-triangle"></i> {{ __('Pending (Never Logged In)') }}</span>
                            @endif
                          </td>
                          <td>
                            <a href="{{ route('user.staff.edit', $staff->id) }}" class="btn btn-secondary btn-sm editbtn me-1">
                              <i class="fas fa-edit"></i> {{ __('Edit') }}
                            </a>
                            <form class="deleteform d-inline-block" action="{{ route('user.staff.delete') }}" method="post">
                              @csrf
                              <input type="hidden" name="staff_id" value="{{ $staff->id }}">
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
@endsection
