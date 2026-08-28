@extends('admin.layout')

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Registered Admins') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
      </h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i></a>
        </li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Admins Management') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Registered Admins') }}</a></li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Registered Admins') }}</h3>
            <a href="#" class="btn-lang-action btn-lang-primary" data-toggle="modal" data-target="#createModal">
              <i class="fas fa-plus"></i> {{ __('Add Admin') }}
            </a>
          </div>
        </div>

        <div class="card-body p-4">
          @if (count($users) == 0)
            <div class="text-center py-5 text-muted">
              <i class="fas fa-users" style="font-size: 48px; opacity: 0.4;"></i>
              <h4 class="mt-3 font-weight-bold">{{ __('NO USER FOUND') }}</h4>
            </div>
          @else
            {{-- DataTable Header --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
              <div class="d-flex align-items-center gap-2" style="font-size: 0.875rem; color: var(--text-muted);">
                {{ __('Show') }}
                <select class="form-control form-control-sm" style="width: 70px; border-radius: 8px; height: 32px;">
                  <option>10</option><option>25</option><option>50</option>
                </select>
                {{ __('entries') }}
              </div>
              <div class="d-flex align-items-center gap-2">
                <span style="font-size: 0.875rem; color: var(--text-muted);">{{ __('Search:') }}</span>
                <div class="position-relative">
                  <input type="text" class="form-control form-control-sm" id="adminSearch"
                    placeholder="{{ __('Search...') }}"
                    style="border-radius: 8px; height: 34px; width: 220px; padding-left: 32px; font-size: 0.85rem; border: 1px solid var(--input-border); background: var(--input-bg); color: var(--text-main);">
                  <i class="fas fa-search position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 0.8rem;"></i>
                </div>
              </div>
            </div>

            <div class="table-responsive" style="overflow-x: auto; width: 100%;">
              <table class="table table-hover align-middle" id="basic-datatables" style="white-space: nowrap;">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{ __('Picture') }}</th>
                    <th scope="col">{{ __('Username') }}</th>
                    <th scope="col">{{ __('Email') }}</th>
                    <th scope="col">{{ __('First Name') }}</th>
                    <th scope="col">{{ __('Last Name') }}</th>
                    <th scope="col">{{ __('Role') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" class="text-right">{{ __('Actions') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $key => $user)
                    @if ($user->id != Auth::guard('admin')->user()->id)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                          <img class="table-image"
                            src="{{ isset($user->image) ? asset('assets/admin/img/propics/' . $user->image) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-card);">
                        </td>
                        <td><span class="font-weight-600" style="font-size: 0.875rem;">{{ $user->username }}</span></td>
                        <td><span style="font-size: 0.875rem; color: var(--text-muted);">{{ $user->email }}</span></td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>
                          <span class="lang-code-badge">{{ @$user->role->name }}</span>
                        </td>
                        <td>
                          @if ($user->status == 1)
                            <span class="lang-status-badge lang-status-default">{{ __('Active') }}</span>
                          @elseif ($user->status == 0)
                            <span class="cd-status-select cd-status-rejected" style="display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 0.78rem;">{{ __('Deactive') }}</span>
                          @endif
                        </td>
                        <td class="text-right">
                          <div class="d-inline-flex align-items-center gap-2">
                            <a class="btn-action-square b-edit" href="{{ route('admin.user.edit', $user->id) }}" title="{{ __('Edit') }}">
                              <i class="fas fa-edit"></i>
                            </a>
                            <form class="deleteform d-inline-block m-0" action="{{ route('admin.user.delete') }}" method="post">
                              @csrf
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                              <button type="submit" class="btn-action-square b-delete deletebtn" title="{{ __('Delete') }}">
                                <i class="fas fa-trash-alt"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
              <div class="text-muted small">
                {{ __('Showing 1 to') }} {{ count($users) - 1 }} {{ __('of') }} {{ count($users) - 1 }} {{ __('entries') }}
              </div>
              <div class="d-flex align-items-center gap-1">
                <button class="btn btn-sm" style="border: 1px solid var(--input-border); background: var(--bg-card); color: var(--text-muted); border-radius: 6px; padding: 4px 12px;">&laquo;</button>
                <button class="btn btn-sm" style="background: #6366F1; color: #fff; border-radius: 6px; padding: 4px 14px; border: none;">1</button>
                <button class="btn btn-sm" style="border: 1px solid var(--input-border); background: var(--bg-card); color: var(--text-muted); border-radius: 6px; padding: 4px 12px;">&raquo;</button>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Create Admin Modal -->
  @includeif('admin.user.create')
@endsection
