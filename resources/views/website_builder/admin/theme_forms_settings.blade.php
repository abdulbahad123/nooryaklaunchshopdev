@extends('website_builder.admin.layout')

@section('title', 'Theme Forms Settings')

@section('content')
<div class="container-fluid">
    <div class="card card-editor mb-4 border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h4 class="fw-bold mb-0"><i class="fa-solid fa-envelope-open-text text-primary me-2"></i>Theme Forms API & reCAPTCHA Settings</h4>
            <p class="text-muted small mt-2">Configure the global fallback reCAPTCHA API keys for all theme contact forms. If a tenant (agency) does not set their own keys, these will be used.</p>
        </div>
        <div class="card-body px-4 pb-4">
            <form action="{{ route('website-builder.admin.theme-forms.update') }}" method="POST">
                @csrf
                <div class="row g-4 mt-1">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Google reCAPTCHA Status</label>
                        <select class="form-select shadow-sm" name="enable_recaptcha">
                            <option value="1" {{ ($settings->enable_recaptcha ?? '1') == '1' ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ ($settings->enable_recaptcha ?? '1') == '0' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <!-- Spacing -->
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">reCAPTCHA Site Key</label>
                        <input type="text" class="form-control shadow-sm" name="recaptcha_site_key" value="{{ $settings->recaptcha_site_key ?? '' }}" placeholder="Enter Google reCAPTCHA Site Key">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">reCAPTCHA Secret Key</label>
                        <input type="password" class="form-control shadow-sm" name="recaptcha_secret_key" value="{{ $settings->recaptcha_secret_key ?? '' }}" placeholder="Enter Google reCAPTCHA Secret Key">
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                        <i class="fa-solid fa-save me-2"></i>Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
