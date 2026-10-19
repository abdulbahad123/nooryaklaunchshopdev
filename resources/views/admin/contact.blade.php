@extends('admin.layout')

@php
  $selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Contact Page & Form Leads') }}</h4>
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
        <a href="#">{{ __('Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Contact') }}</a>
      </li>
    </ul>
  </div>

  <style>
    .contact-nav-tabs .nav-link {
      font-size: 14px;
      font-weight: 600;
      padding: 12px 24px;
      color: #64748b;
      border: 1px solid transparent;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .contact-nav-tabs .nav-link:hover {
      color: #0f172a;
      background: #f8fafc;
    }
    .contact-nav-tabs .nav-link.active {
      color: #ff5a2c !important;
      background: #ffffff;
      border-color: #e2e8f0 #e2e8f0 #ffffff;
      border-top: 3px solid #ff5a2c;
    }
    .lead-metric-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 18px 20px;
      display: flex;
      align-items: center;
      gap: 16px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .lead-metric-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }
    .lead-metric-icon.orange { background: #fff5f2; color: #ff5a2c; }
    .lead-metric-icon.blue   { background: #eff6ff; color: #2563eb; }
    .lead-metric-icon.green  { background: #f0fdf4; color: #16a34a; }
    .lead-metric-val {
      font-size: 22px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1.2;
    }
    .lead-metric-lbl {
      font-size: 12px;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .badge-unread-lead {
      background: #ff5a2c;
      color: #ffffff;
      font-size: 11px;
      font-weight: 700;
      padding: 3px 8px;
      border-radius: 20px;
    }
    .badge-read-lead {
      background: #e2e8f0;
      color: #475569;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 8px;
      border-radius: 20px;
    }
    .recaptcha-box-preview {
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 10px;
      padding: 16px;
      margin-top: 10px;
    }
  </style>

  <div class="row">
    <div class="col-md-12">

      <!-- Navigation Tabs -->
      <ul class="nav nav-tabs contact-nav-tabs mb-4" id="contactTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="settings-tab" data-toggle="tab" href="#tab-settings" role="tab" aria-controls="tab-settings" aria-selected="true">
            <i class="fas fa-sliders-h"></i> {{ __('Contact Details & reCAPTCHA Settings') }}
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="messages-tab" data-toggle="tab" href="#tab-messages" role="tab" aria-controls="tab-messages" aria-selected="false">
            <i class="fas fa-envelope-open-text"></i> {{ __('Submitted Form Leads') }}
            @if ($unread_count > 0)
              <span class="badge badge-danger ml-1" id="unreadBadgeNav">{{ $unread_count }}</span>
            @endif
          </a>
        </li>
      </ul>

      <div class="tab-content" id="contactTabContent">

        <!-- ==========================================
             TAB 1: CONTACT DETAILS & RECAPTCHA
             ========================================== -->
        <div class="tab-pane fade show active" id="tab-settings" role="tabpanel" aria-labelledby="settings-tab">
          <div class="card">
            <form enctype="multipart/form-data" action="{{ route('admin.contact.update', $lang_id) }}" method="POST">
              @csrf
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-lg-8">
                    <h3 class="card-title font-weight-bold">{{ __('Contact Information & Security Settings') }}</h3>
                    <p class="text-muted mb-0 font-sm">{{ __('Configure your public contact information and Google reCAPTCHA v2 protection for the frontend contact form.') }}</p>
                  </div>
                  <div class="col-lg-4 text-right">
                    @include('admin.partials.languages')
                  </div>
                </div>
              </div>

              <div class="card-body">
                <div class="row">
                  
                  <!-- Left Side: Contact Information -->
                  <div class="col-lg-6">
                    <h4 class="font-weight-bold text-dark mb-3"><i class="fas fa-address-card text-primary mr-2"></i>{{ __('Contact Information') }}</h4>

                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Office Address') }} <span class="text-danger">*</span></label>
                      <textarea class="form-control" name="contact_addresses" rows="4" placeholder="{{ __('Enter Address') }}" required>{{ $abe->contact_addresses }}</textarea>
                      <small class="form-text text-muted">{{ __('Use a newline to separate multiple addresses.') }}</small>
                      @if ($errors->has('contact_addresses'))
                        <p class="mb-0 text-danger">{{ $errors->first('contact_addresses') }}</p>
                      @endif
                    </div>

                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Phone Numbers') }} <span class="text-danger">*</span></label>
                      <input class="form-control" data-role="tagsinput" name="contact_numbers"
                        value="{{ $abe->contact_numbers }}" placeholder="{{ __('Enter Phone Number') }}" required>
                      <small class="form-text text-muted">{{ __('Use comma (,) to add multiple phone numbers.') }}</small>
                      @if ($errors->has('contact_numbers'))
                        <p class="mb-0 text-danger">{{ $errors->first('contact_numbers') }}</p>
                      @endif
                    </div>

                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Email Addresses') }} <span class="text-danger">*</span></label>
                      <input class="form-control ltr" data-role="tagsinput" name="contact_mails"
                        value="{{ $abe->contact_mails }}" placeholder="{{ __('Enter Email Addresses') }}" required>
                      <small class="form-text text-muted">{{ __('Use comma (,) to add multiple email addresses.') }}</small>
                      @if ($errors->has('contact_mails'))
                        <p class="mb-0 text-danger">{{ $errors->first('contact_mails') }}</p>
                      @endif
                    </div>
                  </div>

                  <!-- Right Side: Google reCAPTCHA Configuration -->
                  <div class="col-lg-6">
                    <h4 class="font-weight-bold text-dark mb-3"><i class="fas fa-shield-alt text-success mr-2"></i>{{ __('Google reCAPTCHA v2 Protection') }}</h4>

                    <div class="form-group">
                      <label class="font-weight-bold d-block">{{ __('reCAPTCHA Status') }} <span class="text-danger">*</span></label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="is_recaptcha" value="1" class="selectgroup-input" {{ $abs->is_recaptcha == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button"><i class="fas fa-check-circle text-success mr-1"></i> {{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="is_recaptcha" value="0" class="selectgroup-input" {{ $abs->is_recaptcha == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button"><i class="fas fa-times-circle text-danger mr-1"></i> {{ __('Deactive') }}</span>
                        </label>
                      </div>
                      <small class="form-text text-muted">{{ __('When enabled, users must complete Google reCAPTCHA before submitting the contact form.') }}</small>
                    </div>

                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Google reCAPTCHA Site Key') }}</label>
                      <input class="form-control" name="google_recaptcha_site_key" value="{{ $abs->google_recaptcha_site_key }}" placeholder="6LeIx0cD...">
                      <small class="form-text text-muted">{{ __('Public key provided by Google reCAPTCHA admin console.') }}</small>
                    </div>

                    <div class="form-group">
                      <label class="font-weight-bold">{{ __('Google reCAPTCHA Secret Key') }}</label>
                      <input class="form-control" name="google_recaptcha_secret_key" value="{{ $abs->google_recaptcha_secret_key }}" placeholder="6LeIx0cD...">
                      <small class="form-text text-muted">{{ __('Private key used to verify user response tokens.') }}</small>
                    </div>

                    <div class="recaptcha-box-preview">
                      <div class="d-flex align-items-center gap-2 mb-2">
                        <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" width="24" height="24" alt="reCAPTCHA">
                        <strong class="font-sm text-dark">{{ __('How to obtain reCAPTCHA v2 keys?') }}</strong>
                      </div>
                      <p class="font-sm text-muted mb-0">
                        Visit <a href="https://www.google.com/recaptcha/admin" target="_blank" class="text-primary font-weight-bold">Google reCAPTCHA Admin Console <i class="fas fa-external-link-alt font-xs"></i></a>, register your domain, select <strong>reCAPTCHA v2 ("I'm not a robot" Checkbox)</strong>, and copy your Site & Secret Keys here.
                      </p>
                    </div>

                  </div>

                </div>
              </div>

              <div class="card-footer bg-light py-3">
                <div class="row">
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success px-5 font-weight-bold">
                      <i class="fas fa-save mr-1"></i> {{ __('Save Contact & reCAPTCHA Settings') }}
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- ==========================================
             TAB 2: SUBMITTED FORM LEADS
             ========================================== -->
        <div class="tab-pane fade" id="tab-messages" role="tabpanel" aria-labelledby="messages-tab">
          
          <!-- Metric Summaries -->
          <div class="row mb-4">
            <div class="col-md-4">
              <div class="lead-metric-card">
                <div class="lead-metric-icon orange">
                  <i class="fas fa-inbox"></i>
                </div>
                <div>
                  <div class="lead-metric-val">{{ $total_count }}</div>
                  <div class="lead-metric-lbl">{{ __('Total Inquiries') }}</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="lead-metric-card">
                <div class="lead-metric-icon blue">
                  <i class="fas fa-calendar-day"></i>
                </div>
                <div>
                  <div class="lead-metric-val">{{ $today_count }}</div>
                  <div class="lead-metric-lbl">{{ __('Today Inquiries') }}</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="lead-metric-card">
                <div class="lead-metric-icon green">
                  <i class="fas fa-envelope"></i>
                </div>
                <div>
                  <div class="lead-metric-val">{{ $unread_count }}</div>
                  <div class="lead-metric-lbl">{{ __('Unread Inquiries') }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Main Table Card -->
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-lg-6">
                  <h3 class="card-title font-weight-bold m-0">{{ __('Customer Inquiries & Leads') }}</h3>
                  <small class="text-muted">{{ __('Inquiries submitted via the website contact form') }}</small>
                </div>
                <div class="col-lg-6 text-right">
                  <button class="btn btn-danger btn-sm bulk-delete-btn" id="bulkDeleteBtn" style="display: none;">
                    <i class="fas fa-trash mr-1"></i> {{ __('Delete Selected') }}
                  </button>
                </div>
              </div>

              <!-- Filter Bar -->
              <form action="{{ route('admin.contact.index') }}" method="GET" class="mt-3 pt-3 border-top" id="filterForm">
                <input type="hidden" name="tab" value="messages">
                <div class="row g-2 align-items-center">

                  <!-- Quick Date Filter -->
                  <div class="col-md-3 mb-2">
                    <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Date Filter') }}</label>
                    <select name="date_filter" id="dateFilterSelect" class="form-control form-control-sm">
                      <option value="" {{ empty(request()->date_filter) ? 'selected' : '' }}>{{ __('All Dates') }}</option>
                      <option value="today" {{ request()->date_filter == 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                      <option value="yesterday" {{ request()->date_filter == 'yesterday' ? 'selected' : '' }}>{{ __('Yesterday') }}</option>
                      <option value="this_week" {{ request()->date_filter == 'this_week' ? 'selected' : '' }}>{{ __('This Week') }}</option>
                      <option value="this_month" {{ request()->date_filter == 'this_month' ? 'selected' : '' }}>{{ __('This Month') }}</option>
                      <option value="custom" {{ request()->date_filter == 'custom' ? 'selected' : '' }}>{{ __('Custom Date Range') }}</option>
                    </select>
                  </div>

                  <!-- Custom Date Range -->
                  <div class="col-md-2 mb-2 custom-date-box" style="{{ request()->date_filter == 'custom' ? '' : 'display: none;' }}">
                    <label class="font-xs font-weight-bold text-muted mb-1">{{ __('From Date') }}</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request()->start_date }}">
                  </div>
                  <div class="col-md-2 mb-2 custom-date-box" style="{{ request()->date_filter == 'custom' ? '' : 'display: none;' }}">
                    <label class="font-xs font-weight-bold text-muted mb-1">{{ __('To Date') }}</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request()->end_date }}">
                  </div>

                  <!-- Search keyword -->
                  <div class="col-md-3 mb-2">
                    <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Search Keyword') }}</label>
                    <input type="text" name="term" class="form-control form-control-sm" placeholder="{{ __('Name, Email, Phone...') }}" value="{{ request()->term }}">
                  </div>

                  <!-- Status -->
                  <div class="col-md-2 mb-2">
                    <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Status') }}</label>
                    <select name="status" class="form-control form-control-sm">
                      <option value="">{{ __('All Status') }}</option>
                      <option value="unread" {{ request()->status == 'unread' ? 'selected' : '' }}>{{ __('Unread') }}</option>
                      <option value="read" {{ request()->status == 'read' ? 'selected' : '' }}>{{ __('Read') }}</option>
                    </select>
                  </div>

                  <!-- Buttons -->
                  <div class="col-md-12 text-right mt-1">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                      <i class="fas fa-filter mr-1"></i> {{ __('Apply Filter') }}
                    </button>
                    <a href="{{ route('admin.contact.index') }}#tab-messages" class="btn btn-secondary btn-sm px-3 ml-1">
                      <i class="fas fa-undo mr-1"></i> {{ __('Reset') }}
                    </a>
                  </div>

                </div>
              </form>
            </div>

            <div class="card-body p-0">
              @if ($messages->count() == 0)
                <div class="text-center py-5">
                  <div class="text-muted" style="font-size: 48px;"><i class="fas fa-inbox"></i></div>
                  <h4 class="font-weight-bold text-dark mt-3">{{ __('No Inquiries Found') }}</h4>
                  <p class="text-muted font-sm">{{ __('No submitted contact leads match the current filter.') }}</p>
                </div>
              @else
                <div class="table-responsive">
                  <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                      <tr>
                        <th width="40">
                          <input type="checkbox" id="checkAll">
                        </th>
                        <th width="70">#ID</th>
                        <th>{{ __('Date & Time') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Issue / Subject') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right" width="160">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($messages as $msg)
                        <tr id="row-{{ $msg->id }}" class="{{ $msg->status == 'unread' ? 'font-weight-bold bg-light' : '' }}">
                          <td>
                            <input type="checkbox" class="bulk-item" value="{{ $msg->id }}">
                          </td>
                          <td>{{ $msg->id }}</td>
                          <td>
                            <span class="d-block text-dark">{{ $msg->created_at ? $msg->created_at->format('d M, Y') : '-' }}</span>
                            <small class="text-muted">{{ $msg->created_at ? $msg->created_at->format('h:i A') : '' }}</small>
                          </td>
                          <td>{{ $msg->name }}</td>
                          <td>
                            <a href="mailto:{{ $msg->email }}" class="text-primary font-weight-normal">
                              {{ $msg->email }}
                            </a>
                          </td>
                          <td>
                            @if (!empty($msg->phone))
                              <a href="tel:{{ $msg->phone }}" class="text-dark font-weight-normal">
                                {{ $msg->phone }}
                              </a>
                              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone) }}" target="_blank" class="text-success ml-1" title="Chat on WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                              </a>
                            @else
                              <span class="text-muted">-</span>
                            @endif
                          </td>
                          <td>
                            <span class="d-block text-dark">{{ $msg->subject }}</span>
                            @if (!empty($msg->describe_issues))
                              <small class="text-muted text-truncate d-inline-block" style="max-width: 180px;">{{ $msg->describe_issues }}</small>
                            @endif
                          </td>
                          <td>
                            @if ($msg->status == 'unread')
                              <span class="badge-unread-lead" id="status-badge-{{ $msg->id }}">{{ __('Unread') }}</span>
                            @else
                              <span class="badge-read-lead" id="status-badge-{{ $msg->id }}">{{ __('Read') }}</span>
                            @endif
                          </td>
                          <td class="text-right">
                            <button type="button" class="btn btn-info btn-sm view-lead-btn" data-id="{{ $msg->id }}" title="{{ __('View Details') }}">
                              <i class="fas fa-eye"></i>
                            </button>
                            <form action="{{ route('admin.contact.message.delete', $msg->id) }}" method="POST" class="d-inline-block deleteform" onsubmit="return confirm('{{ __('Are you sure you want to delete this inquiry?') }}');">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm" title="{{ __('Delete') }}">
                                <i class="fas fa-trash"></i>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light py-3">
                  <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="text-muted font-sm">
                      {{ __('Showing') }} {{ $messages->firstItem() ?? 0 }} {{ __('to') }} {{ $messages->lastItem() ?? 0 }} {{ __('of') }} {{ $messages->total() }} {{ __('entries') }}
                    </div>
                    <div>
                      {{ $messages->appends(request()->query())->links() }}
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>

  <!-- View Lead Modal -->
  <div class="modal fade" id="viewLeadModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title font-weight-bold" id="leadModalTitle">
            <i class="fas fa-envelope-open mr-2 text-primary"></i> {{ __('Inquiry Details') }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Customer Name') }}</label>
              <div class="font-weight-bold text-dark" id="modalLeadName">-</div>
            </div>
            <div class="col-md-6">
              <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Email Address') }}</label>
              <div><a href="#" id="modalLeadEmail" class="text-primary font-weight-bold">-</a></div>
            </div>
            <div class="col-md-6 mt-3">
              <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Phone Number') }}</label>
              <div id="modalLeadPhone">-</div>
            </div>
            <div class="col-md-6 mt-3">
              <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Date & Time') }}</label>
              <div class="text-muted" id="modalLeadDate">-</div>
            </div>
            <div class="col-12 mt-3">
              <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Describe Issues / Service Required') }}</label>
              <div class="p-2 bg-light rounded text-dark" id="modalLeadIssues">-</div>
            </div>
            <div class="col-12 mt-3">
              <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Subject') }}</label>
              <div class="font-weight-bold text-dark" id="modalLeadSubject">-</div>
            </div>
            <div class="col-12 mt-3">
              <label class="font-xs font-weight-bold text-muted mb-1">{{ __('Full Message Body') }}</label>
              <div class="p-3 bg-light rounded text-dark" style="white-space: pre-wrap; max-height: 250px; overflow-y: auto;" id="modalLeadMessage">-</div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <a href="#" id="modalReplyMailBtn" class="btn btn-primary btn-sm">
            <i class="fas fa-reply mr-1"></i> {{ __('Reply via Email') }}
          </a>
          <a href="#" id="modalReplyWhatsAppBtn" target="_blank" class="btn btn-success btn-sm">
            <i class="fab fa-whatsapp mr-1"></i> {{ __('Chat on WhatsApp') }}
          </a>
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    
    // Hash based Tab Persistence
    var hash = window.location.hash;
    var urlParams = new URLSearchParams(window.location.search);
    if (hash === '#tab-messages' || urlParams.get('tab') === 'messages') {
      $('#contactTab a[href="#tab-messages"]').tab('show');
    } else {
      $('#contactTab a[href="#tab-settings"]').tab('show');
    }

    $('#contactTab a').on('shown.bs.tab', function(e) {
      window.location.hash = e.target.hash;
    });

    // Date Filter toggle custom fields
    $('#dateFilterSelect').on('change', function() {
      if ($(this).val() === 'custom') {
        $('.custom-date-box').show();
      } else {
        $('.custom-date-box').hide();
      }
    });

    // Bulk Select logic
    $('#checkAll').on('change', function() {
      $('.bulk-item').prop('checked', $(this).is(':checked'));
      toggleBulkBtn();
    });

    $('.bulk-item').on('change', function() {
      toggleBulkBtn();
    });

    function toggleBulkBtn() {
      var count = $('.bulk-item:checked').length;
      if (count > 0) {
        $('#bulkDeleteBtn').text("{{ __('Delete Selected') }} (" + count + ")").show();
      } else {
        $('#bulkDeleteBtn').hide();
      }
    }

    // Bulk Delete Action
    $('#bulkDeleteBtn').on('click', function(e) {
      e.preventDefault();
      var selectedIds = [];
      $('.bulk-item:checked').each(function() {
        selectedIds.push($(this).val());
      });

      if (selectedIds.length === 0) return;

      if (!confirm("{{ __('Are you sure you want to delete selected inquiries?') }}")) return;

      $.ajax({
        url: "{{ route('admin.contact.messages.bulk_delete') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          ids: selectedIds
        },
        success: function(res) {
          window.location.hash = "#tab-messages";
          window.location.reload();
        },
        error: function(err) {
          alert("Error deleting inquiries");
        }
      });
    });

    // View Lead Modal via AJAX
    $('.view-lead-btn').on('click', function() {
      var id = $(this).data('id');
      var url = "{{ url('admin/contact/message') }}/" + id;

      $.get(url, function(res) {
        if (res.status === 'success') {
          var item = res.data;
          $('#modalLeadName').text(item.name || '-');
          $('#modalLeadEmail').text(item.email || '-').attr('href', 'mailto:' + (item.email || ''));
          $('#modalLeadPhone').html((item.phone ? '<a href="tel:' + item.phone + '">' + item.phone + '</a>' : '-') );
          $('#modalLeadDate').text(item.created_at + ' (' + item.time_ago + ')');
          $('#modalLeadIssues').text(item.describe_issues || 'None');
          $('#modalLeadSubject').text(item.subject || '-');
          $('#modalLeadMessage').text(item.message || '-');

          // Action links
          $('#modalReplyMailBtn').attr('href', 'mailto:' + (item.email || '') + '?subject=Re: ' + encodeURIComponent(item.subject || ''));
          if (item.phone) {
            var cleanPhone = item.phone.replace(/[^0-9]/g, '');
            $('#modalReplyWhatsAppBtn').attr('href', 'https://wa.me/' + cleanPhone).show();
          } else {
            $('#modalReplyWhatsAppBtn').hide();
          }

          // Mark row as read visually
          $('#row-' + id).removeClass('font-weight-bold bg-light');
          $('#status-badge-' + id).removeClass('badge-unread-lead').addClass('badge-read-lead').text("{{ __('Read') }}");

          // Open Modal
          $('#viewLeadModal').modal('show');
        }
      });
    });

  });
</script>
@endsection