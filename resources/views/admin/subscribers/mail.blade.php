@extends('admin.layout')

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        <i class="fas fa-envelope text-primary" style="font-size: 1.2rem;"></i> {{ __('Mail to Subscribers') }}
      </h4>
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
          <a href="#">{{ __('Mail to Subscribers') }}</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-card);">
        
        <!-- Card Top Banner (Matching Screenshot 4) -->
        <div class="card-header border-0 p-4 position-relative" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.04) 0%, rgba(238, 242, 255, 0.4) 100%);">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
              <span class="user-avatar-initials a-blue m-0" style="width: 48px; height: 48px; border-radius: 14px; background: #DBEAFE; color: #2563EB;">
                <i class="fas fa-paper-plane" style="font-size: 1.25rem;"></i>
              </span>
              <div>
                <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.35rem; color: var(--text-main);">{{ __('Send Mail') }}</h3>
                <p class="text-muted small m-0 mt-1" style="font-size: 0.85rem;">{{ __('Send email to all your subscribers') }}</p>
              </div>
            </div>
            
            <div class="d-none d-md-block opacity-75">
              <i class="fas fa-paper-plane" style="font-size: 64px; color: #6366F1; opacity: 0.15; transform: rotate(-15deg);"></i>
            </div>
          </div>
        </div>

        <form action="{{ route('admin.subscribers.sendmail') }}" method="post">
          @csrf
          <div class="card-body p-4 p-md-5">
            <div class="row">
              <div class="col-lg-11 m-auto">
                
                <!-- Subject Input with Icon (Task 1 Alignment Fix) -->
                <div class="form-group px-0 mb-4">
                  <label for="mail-subject" class="font-weight-bold mb-2" style="font-size: 0.875rem; color: var(--text-main);">
                    {{ __('Subject') }} <span class="text-danger">*</span>
                  </label>
                  <div class="position-relative">
                    <input type="text" id="mail-subject" class="form-control" name="subject" value=""
                      placeholder="{{ __('Enter subject of E-mail') }}" style="border-radius: 12px; height: 46px; font-size: 0.875rem; border: 1px solid var(--input-border); padding-left: 44px !important;">
                    <i class="far fa-envelope position-absolute text-muted" style="left: 16px; top: 50%; transform: translateY(-50%); font-size: 1.05rem; z-index: 2; pointer-events: none;"></i>
                  </div>
                  @if ($errors->has('subject'))
                    <p class="text-danger small mb-0 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('subject') }}</p>
                  @endif
                </div>

                <!-- Message Editor -->
                <div class="form-group px-0 mb-4">
                  <label for="mail-message" class="font-weight-bold mb-2" style="font-size: 0.875rem; color: var(--text-main);">
                    {{ __('Message') }} <span class="text-danger">*</span>
                  </label>
                  <textarea id="mail-message" name="message" class="summernote form-control" data-height="220" placeholder="{{ __('Compose your email message here...') }}"></textarea>
                  @if ($errors->has('message'))
                    <p class="text-danger small mb-0 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('message') }}</p>
                  @endif
                </div>

              </div>
            </div>
          </div>

          <!-- Card Footer Centered Submit Button -->
          <div class="card-footer border-0 bg-transparent text-center pb-5 pt-0">
            <button type="submit" class="btn text-white font-weight-bold px-5 py-3" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius: 12px; font-size: 0.95rem; box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.4); border: none; transition: transform 0.2s ease;">
              <i class="fas fa-paper-plane mr-2"></i> {{ __('Send Mail') }}
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
@endsection
