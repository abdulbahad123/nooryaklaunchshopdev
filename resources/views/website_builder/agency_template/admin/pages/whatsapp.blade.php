@extends('website_builder.agency_template.admin.layout')

@section('title', 'WhatsApp Floating Chatbot Widget - Website Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-brands fa-whatsapp text-success me-2"></i>WhatsApp Floating Chatbot Widget</h3>
    <p class="text-muted small mb-0">Manage and customize your interactive floating WhatsApp Chatbot popup widget for your client website.</p>
  </div>
  <a href="{{ $liveUrl ?? route('website-builder.templates.digital_agency') }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Live Website
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 fw-bold mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST">
  @csrf
  <input type="hidden" name="call_whatsapp_present" value="1">
  <input type="hidden" name="template_type" value="{{ $agency->template_type ?? session('demo_template', 'digital_agency') }}">

  <!-- WHATSAPP CHATBOT WIDGET SETTINGS CARD -->
  <div class="card card-editor p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-brands fa-whatsapp text-success me-2"></i>WhatsApp Widget CRUD Settings</h5>
        <p class="text-muted small mb-0">Enable or disable the floating chat widget, set the receiver WhatsApp number, screen position, and preset welcome message.</p>
      </div>
      <div class="form-check form-switch fs-5">
        <input class="form-check-input" type="checkbox" name="enable_whatsapp_btn" value="1" id="enableWaWidget" {{ ($agency->enable_whatsapp_btn ?? true) ? 'checked' : '' }}>
        <label class="form-check-label fw-bold fs-6 text-dark ms-2" for="enableWaWidget">Enable Widget</label>
      </div>
    </div>

    <div class="row g-4">
      <!-- WhatsApp Phone Number -->
      <div class="col-md-6">
        <label class="form-label fw-semibold text-dark">WhatsApp Phone Number (With Country Code) *</label>
        <div class="input-group">
          <span class="input-group-text bg-light text-success fw-bold"><i class="fa-brands fa-whatsapp"></i></span>
          <input type="text" class="form-control" name="whatsapp_number" value="{{ $agency->whatsapp_number ?? $agency->phone ?? '' }}" placeholder="e.g. 919876543210" required>
        </div>
        <div class="form-text">Include your country code without '+' or spaces (e.g., 919876543210 for India).</div>
      </div>

      <!-- Screen Position -->
      <div class="col-md-6">
        <label class="form-label fw-semibold text-dark">Widget Position on Screen</label>
        <select class="form-select" name="whatsapp_btn_position">
          <option value="left" {{ ($agency->whatsapp_btn_position ?? 'left') === 'left' ? 'selected' : '' }}>Bottom Left (Recommended)</option>
          <option value="right" {{ ($agency->whatsapp_btn_position ?? 'left') === 'right' ? 'selected' : '' }}>Bottom Right</option>
        </select>
        <div class="form-text">Controls where the floating WhatsApp action icon and popup window will appear on client devices.</div>
      </div>

      <!-- Chatbot Popup Header Title -->
      <div class="col-md-12">
        <label class="form-label fw-semibold text-dark">WhatsApp Chatbot Header Title / Support Name</label>
        <input type="text" class="form-control" name="whatsapp_header_title" value="{{ $agency->whatsapp_header_title ?? ($agency->site_title ? $agency->site_title . ' Customer Support' : 'Customer Support') }}" placeholder="e.g. InterioCRAFT Customer Support">
        <div class="form-text">The title/author header displayed at the top of the WhatsApp chatbot bubble window.</div>
      </div>

      <!-- Welcome Popup Message -->
      <div class="col-12">
        <label class="form-label fw-semibold text-dark">WhatsApp Chatbot Welcome Message (Inside Popup)</label>
        <textarea class="form-control" name="whatsapp_popup_message" rows="3" placeholder="e.g. Hi there! 👋 Welcome to InterioCRAFT. How can we help you today? Type your message below or pick an option:">{{ $agency->whatsapp_popup_message ?? ('Hi there! 👋 Welcome to ' . ($agency->site_title ?? 'InterioCRAFT') . '. How can we help you today? Type your message below or pick an option:') }}</textarea>
        <div class="form-text">The main greeting message displayed inside the WhatsApp popup chat bubble when opened by visitors.</div>
      </div>

      <!-- Pre-filled Message -->
      <div class="col-12">
        <label class="form-label fw-semibold text-dark">Default Chat Pre-filled Message</label>
        <textarea class="form-control" name="whatsapp_default_msg" rows="2" placeholder="e.g. Hello! I am interested in your services.">{{ $agency->whatsapp_default_msg ?? 'Hello! I am interested in your services.' }}</textarea>
        <div class="form-text">This text will be pre-loaded into the user's chat input when they click the WhatsApp button.</div>
      </div>
    </div>

    <!-- FLOATING CALL BUTTON OPTIONAL TOGGLE -->
    <div class="mt-4 pt-4 border-top">
      <h6 class="fw-bold mb-3"><i class="fa-solid fa-phone text-primary me-2"></i>Floating Direct Call Button Settings</h6>
      <div class="row g-3">
        <div class="col-md-6">
          <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="enable_call_btn" value="1" id="enableCallBtn" {{ ($agency->enable_call_btn ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold text-dark" for="enableCallBtn">Enable Direct Phone Call Floating Button</label>
          </div>
          <input type="text" class="form-control" name="call_phone_number" value="{{ $agency->call_phone_number ?? $agency->phone ?? '' }}" placeholder="e.g. +91 98765 43210">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold text-dark">Call Button Position</label>
          <select class="form-select" name="call_btn_position">
            <option value="right" {{ ($agency->call_btn_position ?? 'right') === 'right' ? 'selected' : '' }}>Bottom Right</option>
            <option value="left" {{ ($agency->call_btn_position ?? 'right') === 'left' ? 'selected' : '' }}>Bottom Left</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save WhatsApp Widget Settings
  </button>
</form>
@endsection
