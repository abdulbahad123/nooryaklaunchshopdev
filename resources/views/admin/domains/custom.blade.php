@extends('admin.layout')

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ empty(request()->input('type')) ? __('All Custom Domains') : __(ucfirst(request()->input('type')) . ' Custom Domains') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
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
          <a href="#">{{ empty(request()->input('type')) ? __('All Requests') : __(ucfirst(request()->input('type')) . ' Requests') }}</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
              <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('All Custom Domains') }}</h3>
            </div>
            
            <div class="d-flex align-items-center gap-3">
              <button class="btn btn-danger btn-sm rounded-pill d-none bulk-delete mr-2"
                data-href="{{ route('admin.custom-domain.bulk.delete') }}">
                <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
              </button>

              <form action="{{ request()->url() }}" class="d-flex align-items-center gap-2 m-0">
                @if (!empty(request()->input('type')))
                  <input type="hidden" name="type" value="{{ request()->input('type') }}">
                @endif
                <div class="position-relative" style="width: 220px;">
                  <input name="username" class="form-control" type="text"
                    placeholder="{{ __('Search by Username') }}" value="{{ request()->input('username') }}"
                    style="border-radius: 10px; height: 40px; font-size: 0.85rem; padding-left: 36px; border: 1px solid var(--input-border);">
                  <i class="fas fa-user position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
                </div>
                <div class="position-relative" style="width: 220px;">
                  <input name="domain" class="form-control" type="text"
                    placeholder="{{ __('Search by Domain') }}" value="{{ request()->input('domain') }}"
                    style="border-radius: 10px; height: 40px; font-size: 0.85rem; padding-left: 36px; border: 1px solid var(--input-border);">
                  <i class="fas fa-globe position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
                </div>
                <button type="submit" class="d-none"></button>
              </form>
            </div>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="row">
            <div class="col-lg-12">
              @if (count($rcDomains) == 0)
                <div class="text-center py-5 text-muted">
                  <i class="fas fa-link-slash" style="font-size: 48px; opacity: 0.5;"></i>
                  <h4 class="mt-3 font-weight-bold">{{ __('NO REQUEST FOUND') }}</h4>
                </div>
              @else
                <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                  <table class="table table-hover align-middle" style="white-space: nowrap !important;">
                    <thead>
                      <tr>
                        <th scope="col" style="width: 40px;">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Requested Domain') }}</th>
                        <th scope="col">{{ __('Current Domain') }}</th>
                        <th scope="col" style="width: 140px;">{{ __('Status') }}</th>
                        <th scope="col" class="text-right" style="width: 130px;">{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($rcDomains as $rcDomain)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $rcDomain->id }}">
                          </td>
                          <td>
                            @if (!empty($rcDomain->user))
                              @php
                                $uname = $rcDomain->user->username;
                                $initials = strtoupper(substr($uname, 0, 2));
                                $bgColors = ['#E0E7FF', '#F3E8FF', '#ECFDF5', '#FEF3C7', '#FFE4E6'];
                                $textColors = ['#4F46E5', '#7C3AED', '#059669', '#D97706', '#E11D48'];
                                $colorIdx = crc32($uname) % 5;
                              @endphp
                              <div class="d-inline-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center font-weight-bold" 
                                      style="width: 34px; height: 34px; border-radius: 50%; background: {{ $bgColors[$colorIdx] }}; color: {{ $textColors[$colorIdx] }}; font-size: 0.78rem;">
                                  {{ $initials }}
                                </span>
                                <a href="{{ route('register.user.view', $rcDomain->user->id) }}"
                                  target="_blank" class="font-weight-bold" style="color: #6366F1; text-decoration: none; font-size: 0.875rem;">
                                  {{ $rcDomain->user->username }}
                                </a>
                              </div>
                            @else
                              <span class="text-muted">-</span>
                            @endif
                          </td>
                          <td>
                            @if (!empty($rcDomain->requested_domain))
                              <a href="//{{ $rcDomain->requested_domain }}"
                                target="_blank" style="color: #6366F1; font-weight: 500; font-size: 0.85rem; text-decoration: none;">
                                {{ $rcDomain->requested_domain }}
                              </a>
                            @else
                              <span class="text-muted">-</span>
                            @endif
                          </td>
                          <td>
                            @if (!empty($rcDomain->current_domain))
                              <a href="//{{ $rcDomain->current_domain }}"
                                target="_blank" style="color: #6366F1; font-weight: 500; font-size: 0.85rem; text-decoration: none;">
                                {{ $rcDomain->current_domain }}
                              </a>
                            @else
                              <span class="text-muted">-</span>
                            @endif
                          </td>
                          <td>
                            <form id="statusForm{{ $rcDomain->id }}" action="{{ route('admin.custom-domain.status') }}"
                              method="POST" class="m-0">
                              @csrf
                              <input type="hidden" name="domain_id" value="{{ $rcDomain->id }}">
                              <div class="position-relative">
                                <select
                                  class="form-control form-control-sm font-weight-bold border-0"
                                  style="border-radius: 20px; height: 32px; font-size: 0.78rem; padding: 4px 14px; cursor: pointer;
                                         @if ($rcDomain->status == 0) background: #FFFBEB; color: #D97706;
                                         @elseif($rcDomain->status == 1) background: #ECFDF5; color: #10B981;
                                         @elseif($rcDomain->status == 2) background: #FEF2F2; color: #EF4444;
                                         @elseif($rcDomain->status == 3) background: #F1F5F9; color: #64748B; @endif"
                                  name="status"
                                  onchange="document.getElementById('statusForm{{ $rcDomain->id }}').submit();">
                                  <option value="0" {{ $rcDomain->status == 0 ? 'selected' : '' }}>• Pending</option>
                                  <option value="1" {{ $rcDomain->status == 1 ? 'selected' : '' }}>• Connect</option>
                                  <option value="2" {{ $rcDomain->status == 2 ? 'selected' : '' }}>• Reject</option>
                                  <option value="3" {{ $rcDomain->status == 3 ? 'selected' : '' }}>• Removed</option>
                                </select>
                              </div>
                            </form>
                          </td>
                          <td class="text-right">
                            <div class="d-inline-flex align-items-center gap-2">
                              <button class="btn-primary-purple editBtn py-1 px-3" 
                                style="font-size: 0.78rem; border-radius: 8px; background: #6366F1 !important; border: none !important;" 
                                data-toggle="modal"
                                data-target="#mailModal"
                                data-email="{{ !empty($rcDomain->user) ? $rcDomain->user->email : '' }}">
                                <i class="fas fa-envelope mr-1" style="font-size: 0.75rem;"></i> {{ __('Mail') }}
                              </button>

                              <form class="d-inline-block deleteform m-0" action="{{ route('admin.custom-domain.delete') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="domain_id" value="{{ $rcDomain->id }}">
                                <button type="submit" class="btn-action-square b-delete deletebtn" title="{{ __('Delete') }}">
                                  <i class="fas fa-trash-alt"></i>
                                </button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2 border-0 bg-transparent py-3 px-4">
          <div class="text-muted small">
            {{ __('Showing') }} 1 {{ __('to') }} {{ count($rcDomains) }} {{ __('of') }} {{ count($rcDomains) }} {{ __('entries') }}
          </div>
          <div>
            {{ $rcDomains->appends(['type' => request()->input('type'), 'username' => request()->input('username'), 'domain' => request()->input('domain')])->links() }}
          </div>
        </div>

        <!-- Send Mail Modal -->
        <div class="modal fade" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: var(--shadow-card);">
              <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold" id="exampleModalLongTitle">{{ __('Send Mail') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body p-4">
                <form id="ajaxEditForm" action="{{ route('admin.custom-domain.mail') }}" method="POST">
                  @csrf
                  <div class="form-group px-0 mb-3">
                    <label for="inemail" class="font-weight-bold mb-1" style="font-size: 0.85rem;">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                    <input id="inemail" type="text" class="form-control" name="email" value=""
                      placeholder="{{ __('Enter email') }}" style="border-radius: 10px; height: 42px; font-size: 0.85rem;">
                    <p id="eerremail" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                  <div class="form-group px-0 mb-3">
                    <label for="insubject" class="font-weight-bold mb-1" style="font-size: 0.85rem;">{{ __('Subject') }} <span class="text-danger">*</span></label>
                    <input id="insubject" type="text" class="form-control" name="subject" value=""
                      placeholder="{{ __('Enter subject') }}" style="border-radius: 10px; height: 42px; font-size: 0.85rem;">
                    <p id="eerrsubject" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                  <div class="form-group px-0 mb-3">
                    <label for="inmessage" class="font-weight-bold mb-1" style="font-size: 0.85rem;">{{ __('Message') }} <span class="text-danger">*</span></label>
                    <textarea id="inmessage" class="form-control summernote" name="message" placeholder="{{ __('Enter message') }}"
                      data-height="150"></textarea>
                    <p id="eerrmessage" class="mb-0 text-danger em small mt-1"></p>
                  </div>
                </form>
              </div>
              <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ __('Close') }}</button>
                <button id="updateBtn" type="button" class="btn-primary-purple py-2 px-4" style="border-radius: 10px;">{{ __('Send Mail') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
