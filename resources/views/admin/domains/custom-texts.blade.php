@extends('admin.layout')

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Request Page Texts') }} <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
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
          <a href="#">{{ __('Custom Domains') }}</a>
        </li>
        <li class="separator">
          <i class="fas fa-chevron-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Request Page Texts') }}</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Request Page Texts') }}</h3>
        </div>

        <div class="card-body p-4">
          <div class="row">
            <div class="col-lg-9 m-auto">
              <form id="textsForm" action="{{ route('admin.custom-domain.texts') }}" method="POST">
                @csrf
                <div class="form-group px-0 mb-4">
                  <label class="font-weight-bold mb-2" style="font-size: 0.875rem; color: var(--text-main);">
                    {{ __('Message After Domain Request') }} <span class="text-danger">**</span>
                  </label>
                  <textarea name="success_message" rows="3" class="form-control" 
                    style="border-radius: 12px; font-size: 0.875rem; border: 1px solid var(--input-border); padding: 12px;">{{ $abe->domain_request_success_message }}</textarea>
                  @if ($errors->has('success_message'))
                    <p class="text-danger small mb-0 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('success_message') }}</p>
                  @endif
                </div>

                <div class="form-group px-0 mb-4">
                  <label class="font-weight-bold mb-2" style="font-size: 0.875rem; color: var(--text-main);">
                    {{ __('CNAME Record Section Title') }} <span class="text-danger">**</span>
                  </label>
                  <input type="text" name="cname_record_section_title" class="form-control"
                    value="{{ $abe->cname_record_section_title }}"
                    style="border-radius: 12px; height: 46px; font-size: 0.875rem; border: 1px solid var(--input-border); padding: 0 16px;">
                  @if ($errors->has('cname_record_section_title'))
                    <p class="text-danger small mb-0 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('cname_record_section_title') }}</p>
                  @endif
                </div>

                <div class="form-group px-0 mb-4">
                  <label class="font-weight-bold mb-2" style="font-size: 0.875rem; color: var(--text-main);">
                    {{ __('CNAME Record Section Text') }} <span class="text-danger">**</span>
                  </label>
                  <textarea class="summernote" name="cname_record_section_text" data-height="200" class="form-control">{!! $abe->cname_record_section_text !!}</textarea>
                  @if ($errors->has('cname_record_section_text'))
                    <p class="text-danger small mb-0 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('cname_record_section_text') }}</p>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer border-0 pt-0 pb-4 text-center">
          <button type="submit" form="textsForm" class="btn-primary-purple py-2 px-5" 
            style="background: #10B981 !important; border: none; border-radius: 10px; font-weight: 700; font-size: 0.9rem; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);">
            <i class="fas fa-check mr-2"></i>{{ __('Update') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection
