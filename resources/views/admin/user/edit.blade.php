@extends('admin.layout')
@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Edit Admin') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
      </h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i></a>
        </li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Admin Management') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="{{ route('admin.user.index') }}">{{ __('Registered Admins') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Edit Admin') }}</a></li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <div class="d-flex align-items-center justify-content-between gap-3">
            <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Edit Admin') }}</h3>
            <a href="{{ route('admin.user.index') }}" class="btn-action-square b-edit"
              style="width: auto; padding: 6px 18px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
              <i class="fas fa-arrow-left" style="font-size: 0.75rem;"></i> {{ __('Back') }}
            </a>
          </div>
        </div>

        <div class="card-body p-4">
          <form id="ajaxForm" action="{{ route('admin.user.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="row align-items-start">
              {{-- Left: Image Preview --}}
              <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="form-group">
                  <label class="admin-field-label">{{ __('Image') }} <span class="text-danger">*</span></label>
                  <div class="admin-img-card showImage">
                    <img
                      src="{{ $user->image ? asset('assets/admin/img/propics/' . $user->image) : asset('assets/admin/img/noimage.jpg') }}"
                      alt="Admin Image" class="admin-img-preview">
                  </div>
                  <div role="button" class="btn-primary-purple py-2 px-4 upload-btn mt-3"
                    id="image" style="border-radius: 10px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 7px; cursor: pointer;">
                    <i class="fas fa-cloud-upload-alt"></i> {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="image">
                  </div>
                  <p id="errimage" class="mb-0 text-danger em small mt-1"></p>
                </div>
              </div>

              {{-- Right: Form Fields --}}
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="admin-field-label">{{ __('Username') }} <span class="text-danger">*</span></label>
                      <div class="admin-input-wrap">
                        <i class="fas fa-user admin-input-icon"></i>
                        <input type="text" class="form-control admin-field-input" name="username"
                          placeholder="{{ __('Enter username') }}" value="{{ $user->username }}">
                      </div>
                      <p id="errusername" class="mb-0 text-danger em small mt-1"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="admin-field-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                      <div class="admin-input-wrap">
                        <i class="fas fa-envelope admin-input-icon"></i>
                        <input type="text" class="form-control admin-field-input" name="email"
                          placeholder="{{ __('Enter email') }}" value="{{ $user->email }}">
                      </div>
                      <p id="erremail" class="mb-0 text-danger em small mt-1"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="admin-field-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                      <div class="admin-input-wrap">
                        <i class="fas fa-user admin-input-icon"></i>
                        <input type="text" class="form-control admin-field-input" name="first_name"
                          placeholder="{{ __('Enter first name') }}" value="{{ $user->first_name }}">
                      </div>
                      <p id="errfirst_name" class="mb-0 text-danger em small mt-1"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="admin-field-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                      <div class="admin-input-wrap">
                        <i class="fas fa-user admin-input-icon"></i>
                        <input type="text" class="form-control admin-field-input" name="last_name"
                          placeholder="{{ __('Enter last name') }}" value="{{ $user->last_name }}">
                      </div>
                      <p id="errlast_name" class="mb-0 text-danger em small mt-1"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="admin-field-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                      <div class="admin-input-wrap">
                        <i class="fas fa-circle admin-input-icon" style="color: #10B981; font-size: 0.6rem;"></i>
                        <select class="form-control admin-field-input" name="status" style="padding-left: 36px !important;">
                          <option value="" selected disabled>{{ __('Select a status') }}</option>
                          <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                          <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                        </select>
                      </div>
                      <p id="errstatus" class="mb-0 text-danger em small mt-1"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label class="admin-field-label">{{ __('Role') }} <span class="text-danger">*</span></label>
                      <div class="admin-input-wrap">
                        <i class="fas fa-shield-alt admin-input-icon"></i>
                        <select class="form-control admin-field-input" name="role_id" style="padding-left: 36px !important;">
                          <option value="" selected disabled>{{ __('Select a Role') }}</option>
                          @foreach ($roles as $key => $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                              {{ $role->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <p id="errrole_id" class="mb-0 text-danger em small mt-1"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="card-footer border-0 bg-transparent pb-4 pt-0 text-center">
          <button type="submit" id="submitBtn" form="ajaxForm" class="btn-gs-update">
            <i class="fas fa-check"></i> {{ __('Update') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection
