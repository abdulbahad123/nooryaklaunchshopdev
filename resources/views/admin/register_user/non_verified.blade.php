@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Non-Verified Users') }}
    </h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Users Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Non-Verified Users') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="card-title">
                <i class="fas fa-phone-slash mr-1 text-warning"></i>
                {{ __('Non-Verified Users') }}
                <small class="text-muted ml-2">{{ __('Users who requested OTP but never completed verification') }}</small>
              </div>
            </div>
            <div class="col-lg-6 mt-2 mt-lg-0 d-block d-lg-flex justify-content-end gap-3">
              <form action="{{ url()->full() }}" class="float-none mt-2 mt-lg-0">
                <input type="text" name="term" class="form-control min-w-250" value="{{ request()->input('term') }}"
                  placeholder="{{ __('Search by Name / Phone / Email') }}">
              </form>
            </div>
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
              <table class="table table-striped mt-3">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Phone Number') }}</th>
                    <th>{{ __('Country Code') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Plan Status') }}</th>
                    <th>{{ __('OTP Sent At') }}</th>
                    <th>{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($verifiedLeads as $lead)
                  <tr id="lead-row-{{ $lead->id }}">
                    <td>{{ $lead->id }}</td>
                    <td>{{ $lead->name ?: '-' }}</td>
                    <td>
                      <strong>{{ $lead->phone }}</strong>
                    </td>
                    <td>{{ $lead->country_code ?: '-' }}</td>
                    <td>{{ $lead->email ?: '-' }}</td>
                    <td id="lead-status-cell-{{ $lead->id }}">
                      @if($lead->status === 'Purchased')
                        <span class="badge badge-success px-2 py-1 lead-purchased-badge">
                          <i class="fas fa-check mr-1"></i>{{ __('Purchased') }}
                        </span>
                      @elseif($lead->status === 'Follow Up')
                        <span class="badge badge-warning px-2 py-1 lead-status-badge">
                          {{ __('Follow Up') }}
                        </span>
                      @elseif($lead->status === 'Interested')
                        <span class="badge badge-info px-2 py-1 lead-status-badge">
                          {{ __('Interested') }}
                        </span>
                      @elseif($lead->status === 'Not Interested')
                        <span class="badge badge-danger px-2 py-1 lead-status-badge">
                          {{ __('Not Interested') }}
                        </span>
                      @endif
                    </td>
                    <td>{{ $lead->otp_sent_at ? \Carbon\Carbon::parse($lead->otp_sent_at, 'UTC')->setTimezone(config('app.timezone', 'Asia/Kolkata'))->format('d M Y, h:i A') : '-' }}</td>
                    <td>
                      <button class="btn btn-sm btn-info view-lead-btn" 
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
                              data-target="#viewLeadModal">
                        <i class="fas fa-eye"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
        @if(isset($verifiedLeads) && $verifiedLeads->total() > 0)
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $verifiedLeads->appends(['term' => request()->input('term')])->links() }}
            </div>
          </div>
        </div>
        @endif
      </div>

    </div>
  </div>

  <!-- View Lead Details Modal -->
  <div class="modal fade" id="viewLeadModal" tabindex="-1" role="dialog" aria-labelledby="viewLeadModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewLeadModalTitle">{{ __('Lead Details') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success d-none" id="lead-success-alert"></div>
          <div class="alert alert-danger d-none" id="lead-error-alert"></div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <strong>{{ __('Name') }}:</strong> <span id="lead-detail-name"></span>
            </div>
            <div class="col-md-6">
              <strong>{{ __('Email') }}:</strong> <span id="lead-detail-email"></span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <strong>{{ __('Phone') }}:</strong> <span id="lead-detail-phone"></span>
            </div>
            <div class="col-md-6">
              <strong>{{ __('Country Code') }}:</strong> <span id="lead-detail-country-code"></span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <strong>{{ __('OTP Sent At') }}:</strong> <span id="lead-detail-otp-sent"></span>
            </div>
          </div>
          
          <hr>
          
          <form id="leadUpdateForm">
            @csrf
            <input type="hidden" name="id" id="lead-id-input">
            
            <div class="form-group">
              <label for="lead-status-select"><strong>{{ __('Plan Status') }}</strong></label>
              <select name="status" id="lead-status-select" class="form-control">
                <option value="Purchased">{{ __('Purchased') }}</option>
                <option value="Not Purchased">{{ __('Not Purchased') }}</option>
                <option value="Follow Up">{{ __('Follow Up') }}</option>
                <option value="Interested">{{ __('Interested') }}</option>
                <option value="Not Interested">{{ __('Not Interested') }}</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="lead-status-date-input"><strong>{{ __('Status Date') }}</strong></label>
              <input type="datetime-local" name="status_date" id="lead-status-date-input" class="form-control">
              <small class="form-text text-muted">{{ __('For Follow Up status, the date must be today or in the future.') }}</small>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
              <button type="button" class="btn btn-danger" id="deleteLeadBtn">
                <i class="fas fa-trash mr-1"></i> {{ __('Delete Lead') }}
              </button>
              <div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-primary" id="saveLeadBtn">
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
        var createdAt = btn.data('created_at') || '-';
        var status = btn.data('status') || 'Not Purchased';
        var statusDate = btn.data('status_date') || '';
        
        $('#lead-id-input').val(id);
        $('#lead-detail-name').text(name);
        $('#lead-detail-email').text(email);
        $('#lead-detail-phone').text(phone);
        $('#lead-detail-country-code').text(countryCode);
        $('#lead-detail-otp-sent').text(otpSentAt);
        $('#lead-detail-created-at').text(createdAt);
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
              
              // Update triggering button data attributes
              var leadBtn = $('.view-lead-btn[data-id="' + response.lead.id + '"]');
              leadBtn.data('status', response.lead.status);
              leadBtn.data('status_date', response.lead.status_date);
              leadBtn.data('purchased', response.lead.purchased);
              
              // Update the plan status cell badge in the row
              var statusCell = $('#lead-status-cell-' + response.lead.id);
              if (statusCell.length) {
                var badgesHtml = '';
                if (response.lead.status === 'Purchased') {
                  badgesHtml = '<span class="badge badge-success px-2 py-1 lead-purchased-badge"><i class="fas fa-check mr-1"></i>Purchased</span>';
                } else if (response.lead.status === 'Follow Up') {
                  badgesHtml = '<span class="badge badge-warning px-2 py-1 lead-status-badge">Follow Up</span>';
                } else if (response.lead.status === 'Interested') {
                  badgesHtml = '<span class="badge badge-info px-2 py-1 lead-status-badge">Interested</span>';
                } else if (response.lead.status === 'Not Interested') {
                  badgesHtml = '<span class="badge badge-danger px-2 py-1 lead-status-badge">Not Interested</span>';
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
