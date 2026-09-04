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

<form action="{{ route('website-builder.agency-admin.update') }}" method="POST" enctype="multipart/form-data">
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
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="fa-solid fa-user-group text-success me-2"></i>Meet Our Team Members</h5>
        <p class="text-muted small mb-0">Add, edit, upload member photos, or remove team members.</p>
      </div>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill" onclick="addTeamMember()">
        <i class="fa-solid fa-plus me-1"></i> Add Team Member
      </button>
    </div>

    @php
      $teamData = $agency->team_members_data ?? [
        ['name' => 'Michael Roberts', 'role' => 'Founder & CEO',        'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Sarah Johnson',   'role' => 'Creative Director',     'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Daniel Smith',    'role' => 'Head of Development',  'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop'],
        ['name' => 'Jessica Brown',   'role' => 'Marketing Manager',     'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=400&auto=format&fit=crop'],
      ];
    @endphp

    <div class="row g-3" id="teamMembersContainer">
      @foreach($teamData as $ti => $tm)
        <div class="col-md-3 team-member-card-item">
          <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold small text-success">Member #{{ $ti + 1 }}</div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeTeamMember(this)" title="Remove Member"><i class="fa-solid fa-trash-can"></i></button>
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Name</label>
                <input type="text" class="form-control form-control-sm" name="team_members_data[{{ $ti }}][name]" value="{{ $tm['name'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Role / Title</label>
                <input type="text" class="form-control form-control-sm" name="team_members_data[{{ $ti }}][role]" value="{{ $tm['role'] ?? '' }}">
              </div>
              <div class="mb-2">
                <label class="form-label small fw-semibold mb-1">Upload Member Photo</label>
                <input type="file" class="form-control form-control-sm" name="team_members_data[{{ $ti }}][image_file]" accept="image/*">
                <input type="hidden" name="team_members_data[{{ $ti }}][image]" value="{{ $tm['image'] ?? '' }}">
              </div>
              @if(!empty($tm['image']))
                <div class="mt-2 d-flex align-items-center gap-2">
                  <span class="small fw-semibold text-muted">Preview:</span>
                  <img src="{{ str_starts_with($tm['image'], 'http') ? $tm['image'] : asset($tm['image']) }}" onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop';" style="height: 38px; width: 38px; object-fit: cover; border-radius: 50%; border: 1px solid #cbd5e1;">
                </div>
              @endif
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeTeamMember(this)">
              <i class="fa-solid fa-trash me-1"></i> Remove Member
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
    <i class="fa-solid fa-floppy-disk me-2"></i> Save About Us Page
  </button>
</form>

<script>
  let teamCounter = {{ count($teamData) }};
  function addTeamMember() {
    const container = document.getElementById('teamMembersContainer');
    const col = document.createElement('div');
    col.className = 'col-md-3 team-member-card-item';
    col.innerHTML = `
      <div class="border rounded-3 p-3 bg-light position-relative h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-bold small text-success">New Member</div>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 fw-bold" onclick="removeTeamMember(this)" title="Remove Member"><i class="fa-solid fa-trash-can"></i></button>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Name</label>
            <input type="text" class="form-control form-control-sm" name="team_members_data[${teamCounter}][name]" value="New Team Member">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Role / Title</label>
            <input type="text" class="form-control form-control-sm" name="team_members_data[${teamCounter}][role]" value="Specialist">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold mb-1">Upload Member Photo</label>
            <input type="file" class="form-control form-control-sm" name="team_members_data[${teamCounter}][image_file]" accept="image/*">
            <input type="hidden" name="team_members_data[${teamCounter}][image]" value="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop">
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="removeTeamMember(this)">
          <i class="fa-solid fa-trash me-1"></i> Remove Member
        </button>
      </div>
    `;
    container.appendChild(col);
    teamCounter++;
  }

  function removeTeamMember(btn) {
    const cardItem = btn.closest('.team-member-card-item');
    if (cardItem) {
      cardItem.remove();
    }
  }
</script>
@endsection

