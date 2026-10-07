@extends('website_builder.agency_template.admin.layout')

@section('title', 'Form Config (SMTP & reCAPTCHA) - ' . ($agency->site_title ?? 'DesignAGENCY'))

@section('content')
<div class="container-fluid mb-5 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Form Config (SMTP & reCAPTCHA)</h3>
            <p class="text-muted small mb-0">Configure your email delivery settings and protect your contact forms from spam.</p>
        </div>
    </div>

    <form action="{{ route('website-builder.agency-admin.update') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- RECAPTCHA SETTINGS -->
            <div class="col-lg-6">
                <div class="card card-editor h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                        <h5 class="fw-bold mb-0 text-success"><i class="fa-solid fa-shield-halved me-2"></i>Google reCAPTCHA v2 API</h5>
                        <p class="text-muted small mt-1 mb-0">Protect your contact form submissions from spam bots. If left empty, the system will fall back to default Superadmin keys.</p>
                    </div>
                    <div class="card-body px-4 pb-4 pt-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Site Key</label>
                            <input type="text" class="form-control form-control-sm" name="recaptcha_site_key" value="{{ $agency->recaptcha_site_key ?? '' }}" placeholder="e.g. 6Ld...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Secret Key</label>
                            <input type="password" class="form-control form-control-sm" name="recaptcha_secret_key" value="{{ $agency->recaptcha_secret_key ?? '' }}" placeholder="Enter your Secret Key">
                        </div>
                        <div class="alert alert-info py-2 px-3 small border-0 bg-opacity-10 mt-3 mb-0" style="background-color: #0dcaf022; color: #055160;">
                            <i class="fa-solid fa-circle-info me-1"></i> Get keys from the <a href="https://www.google.com/recaptcha/admin/create" target="_blank" class="fw-bold text-decoration-none">Google reCAPTCHA Console</a>.
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMTP SETTINGS -->
            <div class="col-lg-6">
                <div class="card card-editor h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                        <h5 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-envelope-circle-check me-2"></i>SMTP Credentials</h5>
                        <p class="text-muted small mt-1 mb-0">Send a copy of incoming contact inquiries directly to your email inbox using SMTP.</p>
                    </div>
                    <div class="card-body px-4 pb-4 pt-3">
                        <div class="row g-2">
                            <div class="col-md-8 mb-2">
                                <label class="form-label fw-bold small text-secondary">SMTP Host</label>
                                <input type="text" class="form-control form-control-sm" name="smtp_host" value="{{ $agency->smtp_host ?? '' }}" placeholder="e.g. smtp.gmail.com">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-bold small text-secondary">SMTP Port</label>
                                <input type="text" class="form-control form-control-sm" name="smtp_port" value="{{ $agency->smtp_port ?? '' }}" placeholder="e.g. 465 or 587">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold small text-secondary">SMTP Username</label>
                                <input type="text" class="form-control form-control-sm" name="smtp_username" value="{{ $agency->smtp_username ?? '' }}" placeholder="e.g. email@domain.com">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold small text-secondary">SMTP Password</label>
                                <input type="password" class="form-control form-control-sm" name="smtp_password" value="{{ $agency->smtp_password ?? '' }}" placeholder="SMTP Password">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold small text-secondary">Encryption Type</label>
                                <select class="form-select form-select-sm" name="smtp_encryption">
                                    <option value="" {{ empty($agency->smtp_encryption) ? 'selected' : '' }}>None</option>
                                    <option value="tls" {{ ($agency->smtp_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ ($agency->smtp_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="border-top pt-3 mt-1">
                            <h6 class="fw-bold fs-6 mb-2 text-dark">Inquiry Forwarding (Receiver)</h6>
                            <div class="mb-2">
                                <label class="form-label fw-bold small text-secondary">Forwarding Email Address</label>
                                <input type="email" class="form-control form-control-sm" name="contact_receiver_email" value="{{ $agency->contact_receiver_email ?? '' }}" placeholder="e.g. my-inbox@agency.com">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold small text-secondary">Email Subject prefix</label>
                                <input type="text" class="form-control form-control-sm" name="contact_receiver_subject" value="{{ $agency->contact_receiver_subject ?? 'New Lead: Contact Form Submission' }}" placeholder="e.g. New Lead: Contact Form Submission">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SAVE BUTTON -->
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary fw-bold px-5 py-2 shadow-sm rounded-pill" style="border-radius: 12px; font-size: 15px;">
                <i class="fa-solid fa-floppy-disk me-2"></i> Save Configuration
            </button>
        </div>
    </form>
</div>
@endsection
