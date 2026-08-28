@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Non-Verified Users') }}
    </h4>
    <ul class="breadcrumbs">
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
        <a href="#">{{ __('Non-Verified Users') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <!-- Card Header (Matching Reference Image 1 & Task 1) -->
        <div class="card-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 border-0 pb-0">
          <div class="d-flex align-items-center gap-3">
            <span class="cat-icon-badge i-purple m-0" style="width:42px; height:42px; font-size:1.1rem;">
              <i class="fas fa-user-clock"></i>
            </span>
            <div>
              <h5 class="m-0 font-weight-bold" style="font-size: 1.15rem; color: var(--text-main);">{{ __('Non-Verified Users') }}</h5>
              <small class="text-muted" style="font-size: 0.8rem;">{{ __('Users who requested OTP but never completed verification') }}</small>
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-end">
            <form action="{{ url()->full() }}" class="m-0">
              <div class="position-relative" style="width: 320px;">
                <input type="text" name="term" class="form-control" value="{{ request()->input('term') }}"
                  placeholder="{{ __('Search by Name / Phone / Email...') }}" style="border-radius: 10px; height: 40px; font-size: 0.85rem; padding-left: 36px;">
                <i class="fas fa-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
              </div>
            </form>
          </div>
        </div>

        <div class="card-body">
          @if ($verifiedLeads->count() === 0)
            <h3 class="text-center text-muted py-4">
              <i class="fas fa-user-slash d-block mb-2" style="font-size:48px;"></i>
              {{ __('No non-verified users found') }}
            </h3>
          @else
            <div class="table-responsive">
              <table class="table table-striped align-middle">
                <thead>
                  <tr>
                    <th scope="col" style="width: 40px;">
                      <input type="checkbox" class="bulk-check" data-val="all">
                    </th>
                    <th scope="col" style="width: 50px;">#</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Phone Number') }}</th>
                    <th scope="col">{{ __('Country Code') }}</th>
                    <th scope="col">{{ __('Email') }}</th>
                    <th scope="col">{{ __('Plan Status') }}</th>
                    <th scope="col">{{ __('OTP Sent At') }}</th>
                    <th scope="col" class="text-right" style="width: 110px;">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($verifiedLeads as $key => $lead)
                    @php
                        $uInitials = !empty($lead->name) ? strtoupper(substr($lead->name, 0, 2)) : 'NV';
                        $avatarClasses = ['a-purple', 'a-orange', 'a-green', 'a-blue'];
                        $avatarClass = $avatarClasses[$key % 4];
                        $flag = '🇮🇳';
                        if ($lead->country_code == '+966') { $flag = '🇸🇦'; }
                        elseif ($lead->country_code == '+1') { $flag = '🇺🇸'; }
                        elseif ($lead->country_code == '+44') { $flag = '🇬🇧'; }
                        elseif ($lead->country_code == '+971') { $flag = '🇦🇪'; }
                    @endphp
                  <tr id="lead-row-{{ $lead->id }}">
                    <td>
                      <input type="checkbox" class="bulk-check" data-val="{{ $lead->id }}">
                    </td>
                    <td>{{ $lead->id }}</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <span class="user-avatar-initials {{ $avatarClass }}">
                          {{ $uInitials }}
                        </span>
                        <span class="font-weight-bold">{{ $lead->name ?: '-' }}</span>
                      </div>
                    </td>
                    <td>
                      <span class="font-weight-bold text-dark">{{ $lead->phone }}</span>
                    </td>
                    <td>
                      <span class="d-inline-flex align-items-center gap-1 font-weight-bold text-muted">
                        <span>{{ $flag }}</span>
                        <span>{{ $lead->country_code ?: '+91' }}</span>
                      </span>
                    </td>
                    <td>{{ $lead->email ?: '-' }}</td>
                    <td id="lead-status-cell-{{ $lead->id }}">
                      @if($lead->purchased || strtolower($lead->status) === 'purchased')
                        <span class="status-pill-active">
                          <i class="fas fa-check-circle"></i> {{ __('Purchased') }}
                        </span>
                      @else
                        <span class="status-pill-warning">
                          <i class="fas fa-exclamation-triangle"></i> {{ __('Not Purchased') }}
                        </span>
                      @endif
                    </td>
                    <td>{{ $lead->otp_sent_at ? \Carbon\Carbon::parse($lead->otp_sent_at, 'UTC')->setTimezone(config('app.timezone', 'Asia/Kolkata'))->format('d M Y, h:i A') : '-' }}</td>
                    <td class="text-right">
                      <div class="d-inline-flex align-items-center gap-2">
                        <button class="btn-action-square b-view view-lead-btn" 
                                data-id="{{ $lead->id }}"
                                data-name="{{ $lead->name }}"
                                data-phone="{{ $lead->phone }}"
                                data-country_code="{{ $lead->country_code }}"
                                data-email="{{ $lead->email }}"
                                data-purchased="{{ $lead->purchased ? 1 : 0 }}"
                                data-status="{{ $lead->status ?: 'Not Purchased' }}"
                                data-status_date="{{ $lead->status_date ? \Carbon\Carbon::parse($lead->status_date, 'UTC')->setTimezone(config('app.timezone', 'Asia/Kolkata'))->format('Y-m-d\TH:i') : '' }}"
                                data-otp_sent_at="{{ $lead->otp_sent_at ? \Carbon\Carbon::parse($lead->otp_sent_at, 'UTC')->setTimezone(config('app.timezone', 'Asia/Kolkata'))->format('d M Y, h:i A') : '-' }}"
                                data-toggle="modal" 
                                data-target="#viewLeadModal"
                                title="{{ __('View Details') }}">
                          <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action-square b-more" type="button">
                          <i class="fas fa-ellipsis-v"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
        @if(isset($verifiedLeads) && $verifiedLeads->total() > 0)
        <div class="card-footer d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div class="text-muted font-weight-bold" style="font-size: 0.825rem;">
            {{ __('Showing') }} {{ $verifiedLeads->firstItem() }} {{ __('to') }} {{ $verifiedLeads->lastItem() }} {{ __('of') }} {{ $verifiedLeads->total() }} {{ __('entries') }}
          </div>
          <div class="m-0">
            {{ $verifiedLeads->appends(['term' => request()->input('term')])->links() }}
          </div>
        </div>
        @endif
      </div>

    </div>
  </div>

  <!-- View Lead Details Modal (Fixed Layout for Task 2) -->
  <div class="modal fade" id="viewLeadModal" tabindex="-1" role="dialog" aria-labelledby="viewLeadModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content lead-modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
        <div class="modal-header border-0 pb-2 pt-4 px-4 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-3">
            <span class="cat-icon-badge i-purple m-0" style="width:44px; height:44px; font-size:1.15rem; border-radius: 12px;">
              <i class="fas fa-user-clock"></i>
            </span>
            <div>
              <h5 class="modal-title font-weight-bold m-0" id="viewLeadModalTitle" style="font-size: 1.15rem; color: var(--text-main);">{{ __('Lead Details') }}</h5>
              <small class="text-muted" style="font-size: 0.8rem;">{{ __('View and manage lead information') }}</small>
            </div>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; color: var(--text-muted); opacity: 0.8;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <div class="alert alert-success d-none" id="lead-success-alert"></div>
          <div class="alert alert-danger d-none" id="lead-error-alert"></div>
          
          <!-- Top Grid Information Card (Task 2 Clean Grid Layout) -->
          <div class="lead-modal-grid-card mb-4" style="background: var(--table-header-bg); border-radius: 14px; padding: 20px; border: 1px solid var(--border-card);">
            <div class="row g-3">
              <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-purple" style="width:36px; height:36px; border-radius:10px; background:#F3E8FF; color:#7C3AED; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-user" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Name') }}</span>
                    <span class="font-weight-bold" id="lead-detail-name" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-orange" style="width:36px; height:36px; border-radius:10px; background:#FFEDD5; color:#EA580C; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-envelope" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Email') }}</span>
                    <span class="font-weight-bold" id="lead-detail-email" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-green" style="width:36px; height:36px; border-radius:10px; background:#DCFCE7; color:#16A34A; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-phone" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Phone Number') }}</span>
                    <span class="font-weight-bold" id="lead-detail-phone" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-blue" style="width:36px; height:36px; border-radius:10px; background:#DBEAFE; color:#2563EB; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-globe" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('Country Code') }}</span>
                    <span class="font-weight-bold" id="lead-detail-country-code" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box i-cyan" style="width:36px; height:36px; border-radius:10px; background:#E0F2FE; color:#0284C7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-clock" style="font-size:0.85rem;"></i>
                  </div>
                  <div>
                    <span class="text-muted d-block" style="font-size:0.75rem; font-weight:600;">{{ __('OTP Sent At') }}</span>
                    <span class="font-weight-bold" id="lead-detail-otp-sent" style="font-size:0.9rem; color: var(--text-main);">-</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <form id="leadUpdateForm">
            @csrf
            <input type="hidden" name="id" id="lead-id-input">
            
            <div class="form-group px-0 mb-3">
              <label for="lead-status-select" style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">{{ __('Plan Status') }}</label>
              <select name="status" id="lead-status-select" class="form-control" style="border-radius: 10px; height: 44px; font-size: 0.875rem;">
                <option value="Purchased">⚠️ {{ __('Purchased') }}</option>
                <option value="Not Purchased">⚠️ {{ __('Not Purchased') }}</option>
                <option value="Follow Up">⚠️ {{ __('Follow Up') }}</option>
                <option value="Interested">⚠️ {{ __('Interested') }}</option>
                <option value="Not Interested">⚠️ {{ __('Not Interested') }}</option>
              </select>
            </div>
            
            <div class="form-group px-0 mb-4">
              <label for="lead-status-date-input" style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">{{ __('Status Date') }}</label>
              <input type="datetime-local" name="status_date" id="lead-status-date-input" class="form-control" style="border-radius: 10px; height: 44px; font-size: 0.875rem;">
              <small class="text-warning-note d-block mt-1">{{ __('For follow up status, the date must be today or in the future.') }}</small>
            </div>
            
            <div class="d-flex justify-content-between align-items-center pt-2">
              <button type="button" class="btn btn-outline-danger btn-sm" id="deleteLeadBtn" style="border-radius: 10px; padding: 8px 16px; font-weight: 600;">
                <i class="fas fa-trash mr-1"></i> {{ __('Delete Lead') }}
              </button>
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-light btn-sm" data-dismiss="modal" style="border-radius: 10px; padding: 8px 18px; font-weight: 600;">{{ __('Close') }}</button>
                <button type="submit" class="btn-primary-purple m-0 py-2 px-4" id="saveLeadBtn" style="border-radius: 10px; font-size: 0.875rem;">
                  <i class="fas fa-save mr-1"></i> {{ __('Save Changes') }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      $(document).on('click', '.view-lead-btn', function () {
        var btn = $(this);
        var id = btn.data('id');
        var name = btn.data('name') || '-';
        var email = btn.data('email') || '-';
        var phone = btn.data('phone') || '-';
        var countryCode = btn.data('country_code') || '-';
        var otpSentAt = btn.data('otp_sent_at') || '-';
        var status = btn.data('status') || 'Not Purchased';
        var statusDate = btn.data('status_date') || '';
        
        $('#lead-id-input').val(id);
        $('#lead-detail-name').text(name);
        $('#lead-detail-email').text(email);
        $('#lead-detail-phone').text(phone);
        $('#lead-detail-country-code').text(countryCode);
        $('#lead-detail-otp-sent').text(otpSentAt);
        $('#lead-status-select').val(status);
        $('#lead-status-date-input').val(statusDate);
        
        $('#lead-success-alert').addClass('d-none').text('');
        $('#lead-error-alert').addClass('d-none').text('');
        $('#saveLeadBtn').prop('disabled', false);
      });
      
      $('#leadUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = $('#saveLeadBtn');
        
        $('#lead-success-alert').addClass('d-none').text('');
        $('#lead-error-alert').addClass('d-none').text('');
        submitBtn.prop('disabled', true);
        
        $.ajax({
          url: "{{ route('admin.register.lead.updateStatus') }}",
          method: "POST",
          data: form.serialize(),
          success: function (response) {
            if (response.success) {
              $('#lead-success-alert').removeClass('d-none').text(response.message);
              
              var leadBtn = $('.view-lead-btn[data-id="' + response.lead.id + '"]');
              leadBtn.data('status', response.lead.status);
              leadBtn.data('status_date', response.lead.status_date);
              leadBtn.data('purchased', response.lead.purchased);
              
              var statusCell = $('#lead-status-cell-' + response.lead.id);
              if (statusCell.length) {
                var badgesHtml = '';
                if (response.lead.purchased || response.lead.status === 'Purchased') {
                  badgesHtml = '<span class="status-pill-active"><i class="fas fa-check-circle"></i> Purchased</span>';
                } else {
                  badgesHtml = '<span class="status-pill-warning"><i class="fas fa-exclamation-triangle"></i> Not Purchased</span>';
                }
                statusCell.html(badgesHtml);
              }
              
              setTimeout(function () {
                $('#viewLeadModal').modal('hide');
              }, 1000);
            }
          },
          error: function (xhr) {
            submitBtn.prop('disabled', false);
            var errorMsg = "{{ __('An error occurred. Please try again.') }}";
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMsg = xhr.responseJSON.message;
            }
            $('#lead-error-alert').removeClass('d-none').text(errorMsg);
          }
        });
      });
      
      $('#deleteLeadBtn').on('click', function () {
        var id = $('#lead-id-input').val();
        if (confirm("{{ __('Are you sure you want to delete this lead? This will perform a soft delete.') }}")) {
          var btn = $(this);
          btn.prop('disabled', true);
          
          $.ajax({
            url: "{{ route('admin.register.lead.delete') }}",
            method: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              id: id
            },
            success: function (response) {
              if (response.success) {
                $('#lead-success-alert').removeClass('d-none').text(response.message);
                $('#lead-row-' + id).remove();
                setTimeout(function () {
                  $('#viewLeadModal').modal('hide');
                  btn.prop('disabled', false);
                }, 1000);
              }
            },
            error: function (xhr) {
              btn.prop('disabled', false);
              var errorMsg = "{{ __('Failed to delete lead. Please try again.') }}";
              if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
              }
              $('#lead-error-alert').removeClass('d-none').text(errorMsg);
            }
          });
        }
      });
    });
  </script>
@endsection
