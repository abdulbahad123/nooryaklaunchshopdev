<!-- Add Admin Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">

      <div class="modal-header border-0 pb-0 pt-4 px-4" style="background: var(--bg-card);">
        <h5 class="modal-title font-weight-bold" id="createModalTitle" style="font-size: 1.15rem; color: var(--text-main);">{{ __('Add Admin') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: var(--text-muted); font-size: 1.4rem; background: none; border: none; padding: 0; cursor: pointer;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body px-4 pt-3 pb-1" style="background: var(--bg-card);">
        <form id="ajaxForm" action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- Image Upload --}}
          <div class="form-group mb-4">
            <label class="admin-field-label">{{ __('Image') }} <span class="text-danger">**</span></label>
            <div class="admin-modal-upload-box showImage upload-btn" id="image" role="button">
              <i class="fas fa-image admin-modal-img-icon"></i>
              <p class="admin-modal-img-text mb-1">{{ __('Drag & drop your image here') }}</p>
              <p class="admin-modal-img-or mb-3">{{ __('or') }}</p>
              <div class="btn-primary-purple py-2 px-4" style="border-radius: 10px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 7px;">
                {{ __('Choose Image') }}
              </div>
              <p class="admin-modal-img-hint mt-3 mb-0">{{ __('Supported formats: JPG, JPEG, PNG') }}<br>{{ __('Recommended size: 512x512px') }}</p>
              <input type="file" class="img-input" name="image" style="position: absolute; opacity: 0; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;">
            </div>
            <p id="errimage" class="mb-0 text-danger em small mt-1"></p>
          </div>

          {{-- Username + Email --}}
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label class="admin-field-label">{{ __('Username') }} <span class="text-danger">**</span></label>
                <div class="admin-input-wrap">
                  <i class="fas fa-user admin-input-icon"></i>
                  <input type="text" class="form-control admin-field-input" name="username"
                    placeholder="{{ __('Enter username') }}" value="">
                </div>
                <p id="errusername" class="mb-0 text-danger em small mt-1"></p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label class="admin-field-label">{{ __('Email') }} <span class="text-danger">**</span></label>
                <div class="admin-input-wrap">
                  <i class="fas fa-envelope admin-input-icon"></i>
                  <input type="text" class="form-control admin-field-input" name="email"
                    placeholder="{{ __('Enter email') }}" value="">
                </div>
                <p id="erremail" class="mb-0 text-danger em small mt-1"></p>
              </div>
            </div>
          </div>

          {{-- First + Last Name --}}
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label class="admin-field-label">{{ __('First Name') }} <span class="text-danger">**</span></label>
                <div class="admin-input-wrap">
                  <i class="fas fa-user admin-input-icon"></i>
                  <input type="text" class="form-control admin-field-input" name="first_name"
                    placeholder="{{ __('Enter first name') }}" value="">
                </div>
                <p id="errfirst_name" class="mb-0 text-danger em small mt-1"></p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label class="admin-field-label">{{ __('Last Name') }} <span class="text-danger">**</span></label>
                <div class="admin-input-wrap">
                  <i class="fas fa-user admin-input-icon"></i>
                  <input type="text" class="form-control admin-field-input" name="last_name"
                    placeholder="{{ __('Enter last name') }}" value="">
                </div>
                <p id="errlast_name" class="mb-0 text-danger em small mt-1"></p>
              </div>
            </div>
          </div>

          {{-- Password + Re-type --}}
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label class="admin-field-label">{{ __('Password') }} <span class="text-danger">**</span></label>
                <div class="admin-input-wrap">
                  <i class="fas fa-lock admin-input-icon"></i>
                  <input type="password" class="form-control admin-field-input" name="password"
                    placeholder="{{ __('Enter password') }}" value="">
                </div>
                <p id="errpassword" class="mb-0 text-danger em small mt-1"></p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label class="admin-field-label">{{ __('Re-type Password') }} <span class="text-danger">**</span></label>
                <div class="admin-input-wrap">
                  <i class="fas fa-lock admin-input-icon"></i>
                  <input type="password" class="form-control admin-field-input" name="password_confirmation"
                    placeholder="{{ __('Enter your password again') }}" value="">
                </div>
                <p id="errpassword_confirmation" class="mb-0 text-danger em small mt-1"></p>
              </div>
            </div>
          </div>

          {{-- Role --}}
          <div class="form-group mb-4">
            <label class="admin-field-label">{{ __('Role') }} <span class="text-danger">**</span></label>
            <div class="admin-input-wrap">
              <i class="fas fa-shield-alt admin-input-icon"></i>
              <select class="form-control admin-field-input" name="role_id" style="padding-left: 36px !important;">
                <option value="" selected disabled>{{ __('Select a role') }}</option>
                @foreach ($roles as $key => $role)
                  <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </div>
            <p id="errrole_id" class="mb-0 text-danger em small mt-1"></p>
          </div>

        </form>
      </div>

      <div class="modal-footer border-0 pb-4 px-4 pt-3" style="background: var(--bg-card); border-radius: 0 0 20px 20px; gap: 10px;">
        <button type="button" class="btn" data-dismiss="modal"
          style="border: 1.5px solid var(--input-border); background: transparent; color: var(--text-main); border-radius: 10px; padding: 8px 24px; font-size: 0.875rem; font-weight: 600;">
          {{ __('Close') }}
        </button>
        <button id="submitBtn" type="button" class="btn-primary-purple py-2 px-4"
          style="border-radius: 10px; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 7px;">
          <i class="fas fa-check"></i> {{ __('Submit') }}
        </button>
      </div>

    </div>
  </div>
</div>
