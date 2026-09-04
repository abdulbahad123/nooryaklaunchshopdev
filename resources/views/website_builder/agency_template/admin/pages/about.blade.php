@extends('website_builder.agency_template.admin.layout')

@section('title', 'Edit About Us - DesignAGENCY Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-extrabold mb-1"><i class="fa-solid fa-users text-indigo me-2" style="color: #4F46E5;"></i>Edit About Us Page</h3>
    <p class="text-muted small mb-0">Update About Hero title, Story paragraph text, and 4 team member cards.</p>
  </div>
  <a href="{{ isset($customer) && !empty($customer->subdomain) ? route('website-builder.subdomain.about', ['subdomain' => $customer->subdomain]) : route('website-builder.templates.digital_agency.about') }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold">
    <i class="fa-solid fa-eye me-1"></i> Preview About Us Page
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

  <!-- ABOUT HERO & STORY -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-book-open text-success me-2"></i>About Hero & Story</h5>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold small">About Hero Main Title</label>
        <input type="text" class="form-control" name="about_hero_title" value="{{ $agency->about_hero_title ?? 'We Are A Creative Digital Solutions Agency' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold small">Our Story Title</label>
        <input type="text" class="form-control" name="story_title" value="{{ $agency->story_title ?? 'Our Journey Started With A Simple Idea' }}">
      </div>
      <div class="col-md-12">
        <label class="form-label fw-semibold small">Story Paragraph Content</label>
        <textarea class="form-control" name="story_text" rows="4">{{ $agency->story_text ?? "DesignAGENCY was founded in 2016 with a mission to empower businesses with smart digital solutions." }}</textarea>
      </div>
    </div>
  </div>

  <!-- TEAM MEMBERS -->
  <div class="card card-editor p-4 mb-4">
    <h5 class="fw-bold mb-1"><i class="fa-solid fa-user-group text-success me-2"></i>Meet Our Team (4 Members)</h5>
    <p class="text-muted small mb-4">Edit member photos, names, and job roles.</p>

    @php
      $teamData = $agency->team_members_data ?? [
        ['name' => 'Michael Roberts', 'role' => 'Founder & CEO',        'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Sarah Johnson',   'role' => 'Creative Director',     'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Daniel Smith',    'role' => 'Head of Development',  'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Jessica Brown',   'role' => 'Marketing Manager',     'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=400&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-3">
      @foreach($teamData as $ti => $tm)
        <div class="col-md-3">
          <div class="border rounded-3 p-3 bg-light">
            <div class="fw-bold small text-success mb-2">Member {{ $ti + 1 }}</div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">Name</label>
              <input type="text" class="form-control form-control-sm" name="team_members_data[{{ $ti }}][name]" value="{{ $tm['name'] }}">
            </div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">Role / Title</label>
              <input type="text" class="form-control form-control-sm" name="team_members_data[{{ $ti }}][role]" value="{{ $tm['role'] }}">
            </div>
            <div>
              <label class="form-label small fw-semibold">Photo Image URL</label>
              <input type="text" class="form-control form-control-sm" name="team_members_data[{{ $ti }}][image]" value="{{ $tm['image'] }}">
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save About Us Page
  </button>
</form>
@endsection
