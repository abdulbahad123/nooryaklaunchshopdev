@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit Contact Page - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-envelope text-indigo me-2" style="color: #4F46E5;"></i>Edit Contact Page</h3>
    <p class="text-muted small mb-0">Update contact headings, sub-headings, and 4 FAQs accordion questions.</p>
  </div>
  <a href="{{ isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.contact', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency.contact') }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview Contact Page
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

  <!-- CONTACT HEADINGS -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-heading text-success me-2"></i>Contact Page Headings</h5>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Contact Main Title</label>
        <input type="text" class="form-control" name="contact_title" value="{{ $agency->contact_title ?? "Let's Build Something Amazing Together!" }}">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Contact Subtitle</label>
        <input type="text" class="form-control" name="contact_subtitle" value="{{ $agency->contact_subtitle ?? "Have a project in mind or just want to say hello? We'd love to hear from you." }}">
      </div>
    </div>
  </div>

  <!-- FAQS -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-1"><i class="fa-solid fa-circle-question text-success me-2"></i>Frequently Asked Questions (4 Items)</h5>
    <p class="text-muted small mb-4">Edit questions and answers shown in the contact FAQ accordion.</p>

    @php
      $faqsData = $agency->faqs_data ?? [
        ['q' => 'How soon can we start our project?', 'a' => 'Once we understand your requirements, we can typically start within 2–3 business days.'],
        ['q' => 'What information do you need to get started?', 'a' => 'We will need your brand assets, project goals, target audience, and any content guidelines.'],
        ['q' => 'Do you offer ongoing support?', 'a' => 'Yes! We offer comprehensive maintenance, updates, and ongoing digital strategy support.'],
        ['q' => 'How do I know if my project is a good fit?', 'a' => 'Feel free to send us a quick message or book a discovery call, and our team will evaluate your needs!'],
      ];
    @endphp

    <div class="row g-3">
      @foreach($faqsData as $fi => $faq)
        <div class="col-md-6">
          <div class="border rounded-3 p-3 bg-light">
            <div class="fw-bold small text-success mb-2">FAQ {{ $fi + 1 }}</div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">Question</label>
              <input type="text" class="form-control form-control-sm" name="faqs_data[{{ $fi }}][q]" value="{{ $faq['q'] }}">
            </div>
            <div>
              <label class="form-label small fw-semibold">Answer</label>
              <textarea class="form-control form-control-sm" name="faqs_data[{{ $fi }}][a]" rows="2">{{ $faq['a'] }}</textarea>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save Contact Page
  </button>
</form>
@endsection
