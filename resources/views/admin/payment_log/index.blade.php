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
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Payment Logs') }} <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
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
          <a href="#">{{ __('Payment Logs') }}</a>
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
              <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Payment Logs') }}</h3>
            </div>
            
            <div class="d-flex align-items-center justify-content-end">
              <form action="{{ url()->current() }}" class="m-0">
                <div class="position-relative" style="width: 280px;">
                  <input class="form-control" type="text" name="search"
                    placeholder="{{ __('Search by Transaction ID') }}"
                    value="{{ request()->input('search') ? request()->input('search') : '' }}"
                    style="border-radius: 10px; height: 40px; font-size: 0.85rem; padding-right: 36px; border: 1px solid var(--input-border);">
                  <i class="fas fa-search position-absolute text-muted" style="right: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="row">
            <div class="col-lg-12">
              @if (count($memberships) == 0)
                <div class="text-center py-5 text-muted">
                  <i class="fas fa-receipt" style="font-size: 48px; opacity: 0.5;"></i>
                  <h4 class="mt-3 font-weight-bold">{{ __('NO MEMBERSHIP FOUND') }}</h4>
                </div>
              @else
                <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                  <table class="table table-hover align-middle" style="white-space: nowrap !important;">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Transaction Id') }}</th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Package') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col" style="width: 140px;">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Payment Method') }}</th>
                        <th scope="col">{{ __('Receipt') }}</th>
                        <th scope="col" class="text-right" style="width: 100px;">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($memberships as $key => $membership)
                        <tr>
                          <td class="font-weight-bold text-dark" style="font-size: 0.875rem;">
                            {{ strlen($membership->transaction_id) > 30 ? mb_substr($membership->transaction_id, 0, 30, 'UTF-8') . '...' : $membership->transaction_id }}
                          </td>
                          <td>
                            <a target="_blank" href="{{ route('register.user.view', $membership->user_id) }}" 
                               class="font-weight-bold" style="color: #6366F1; text-decoration: none; font-size: 0.875rem;">
                              {{ @$membership->user->username }}
                            </a>
                          </td>
                          <td>
                            <a target="_blank" href="{{ route('admin.package.edit', $membership->package_id) }}"
                               style="color: #3B82F6; font-weight: 500; text-decoration: none; font-size: 0.85rem;">
                              {{ $membership->package->title }} [{{ $membership->package->term }}]
                            </a>
                          </td>
                          @php
                            $bex = json_decode($membership->settings);
                          @endphp
                          <td class="font-weight-bold" style="font-size: 0.875rem;">
                            @if ($membership->price == 0)
                              {{ __('Free') }}
                            @else
                              {{ format_price($membership->price) }}
                            @endif
                          </td>
                          <td>
                            @if (json_decode($membership->transaction_details) !== 'offline')
                              @if ($membership->status == 1)
                                <span class="status-pill-active" style="background: #ECFDF5 !important; color: #10B981 !important; font-weight: 700; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem;">
                                  <i class="fas fa-check mr-1" style="font-size: 0.7rem;"></i> {{ __('Success') }}
                                </span>
                              @elseif ($membership->status == 0)
                                <span class="status-pill-warning" style="background: #FFFBEB !important; color: #D97706 !important; font-weight: 700; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem;">
                                  <i class="fas fa-clock mr-1" style="font-size: 0.7rem;"></i> {{ __('Pending') }}
                                </span>
                              @elseif ($membership->status == 2)
                                <span class="status-pill-deactive" style="background: #FEF2F2 !important; color: #EF4444 !important; font-weight: 700; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem;">
                                  <i class="fas fa-times mr-1" style="font-size: 0.7rem;"></i> {{ __('Rejected') }}
                                </span>
                              @endif
                            @else
                              <form id="statusForm{{ $membership->id }}" class="d-inline-block m-0"
                                action="{{ route('admin.payment-log.update') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $membership->id }}">
                                <select
                                  class="form-control form-control-sm font-weight-bold border-0"
                                  style="border-radius: 20px; height: 32px; font-size: 0.78rem; padding: 4px 12px; cursor: pointer;
                                         @if ($membership->status == 1) background: #ECFDF5; color: #10B981;
                                         @elseif ($membership->status == 0) background: #FFFBEB; color: #D97706;
                                         @elseif ($membership->status == 2) background: #FEF2F2; color: #EF4444; @endif"
                                  name="status"
                                  onchange="document.getElementById('statusForm{{ $membership->id }}').submit();">
                                  <option value=0 {{ $membership->status == 0 ? 'selected' : '' }}>• {{ __('Pending') }}</option>
                                  <option value=1 {{ $membership->status == 1 ? 'selected' : '' }}>• {{ __('Success') }}</option>
                                  <option value=2 {{ $membership->status == 2 ? 'selected' : '' }}>• {{ __('Rejected') }}</option>
                                </select>
                              </form>
                            @endif
                          </td>
                          <td class="text-muted" style="font-size: 0.85rem; font-weight: 500;">
                            {{ $membership->payment_method }}
                          </td>
                          <td>
                            @if (!empty($membership->receipt))
                              <a class="btn btn-sm btn-light font-weight-bold rounded-pill px-3 py-1" style="font-size: 0.78rem;" href="#" data-toggle="modal"
                                data-target="#receiptModal{{ $membership->id }}">{{ __('Show') }}</a>
                            @else
                              <span class="text-muted">-</span>
                            @endif
                          </td>
                          <td class="text-right">
                            @if (!empty($membership->name !== 'anonymous'))
                              <a class="btn-primary-purple py-1 px-3" style="background: #3B82F6 !important; font-size: 0.78rem; border-radius: 8px; text-decoration: none;" href="#" data-toggle="modal"
                                data-target="#detailsModal{{ $membership->id }}">
                                <i class="fas fa-eye mr-1" style="font-size: 0.75rem;"></i> {{ __('Detail') }}
                              </a>
                            @else
                              <span class="text-muted">-</span>
                            @endif
                          </td>
                        </tr>

                        <!-- Receipt Modal -->
                        <div class="modal fade" id="receiptModal{{ $membership->id }}" tabindex="-1" role="dialog"
                          aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="border-radius: 20px; border: none;">
                              <div class="modal-header border-0 pb-0 pt-4 px-4">
                                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">{{ __('Receipt Image') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body p-4 text-center">
                                <img src="{{ asset('assets/front/img/membership/receipt/' . $membership->receipt) }}"
                                  class="w-100 rounded" style="max-height: 400px; object-fit: contain;">
                              </div>
                              <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ __('Close') }}</button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Details Modal -->
                        <div class="modal fade" id="detailsModal{{ $membership->id }}" tabindex="-1" role="dialog"
                          aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="border-radius: 20px; border: none;">
                              <div class="modal-header border-0 pb-0 pt-4 px-4">
                                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">{{ __('Owner Details') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body p-4">
                                <h6 class="font-weight-bold mb-3" style="color: #6366F1;">{{ __('Member details') }}</h6>
                                <div class="row mb-2">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Name') }}:</div>
                                  <div class="col-8 small">{{ @$membership->user->shop_name }}</div>
                                </div>
                                <div class="row mb-2">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Email') }}:</div>
                                  <div class="col-8 small">{{ @$membership->user->email }}</div>
                                </div>
                                <div class="row mb-3">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Phone') }}:</div>
                                  <div class="col-8 small">{{ @$membership->user->phone }}</div>
                                </div>

                                <h6 class="font-weight-bold mb-3 mt-4" style="color: #6366F1;">{{ __('Payment details') }}</h6>
                                <div class="row mb-2">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Cost') }}:</div>
                                  <div class="col-8 small font-weight-bold">{{ $membership->price == 0 ? __('Free') : $membership->price }}</div>
                                </div>
                                <div class="row mb-2">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Currency') }}:</div>
                                  <div class="col-8 small">{{ $membership->currency }}</div>
                                </div>
                                <div class="row mb-3">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Method') }}:</div>
                                  <div class="col-8 small">{{ $membership->payment_method }}</div>
                                </div>

                                <h6 class="font-weight-bold mb-3 mt-4" style="color: #6366F1;">{{ __('Package Details') }}</h6>
                                <div class="row mb-2">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Title') }}:</div>
                                  <div class="col-8 small">{{ $membership->package->title }}</div>
                                </div>
                                <div class="row mb-2">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Term') }}:</div>
                                  <div class="col-8 small">{{ $membership->package->term }}</div>
                                </div>
                                <div class="row mb-2">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Start Date') }}:</div>
                                  <div class="col-8 small">
                                    @if (\Illuminate\Support\Carbon::parse($membership->start_date)->format('Y') == '9999')
                                      <span class="badge badge-danger">{{ __('Never Activated') }}</span>
                                    @else
                                      {{ \Illuminate\Support\Carbon::parse($membership->start_date)->format('M-d-Y') }}
                                    @endif
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Expire Date') }}:</div>
                                  <div class="col-8 small">
                                    @if (\Illuminate\Support\Carbon::parse($membership->start_date)->format('Y') == '9999')
                                      -
                                    @else
                                      @if ($membership->modified == 1)
                                        {{ \Illuminate\Support\Carbon::parse($membership->expire_date)->addDay()->format('M-d-Y') }}
                                        <span class="badge badge-primary btn-xs">{{ __('modified by Admin') }}</span>
                                      @else
                                        {{ $membership->package->term == 'lifetime' ? __('Lifetime') : \Illuminate\Support\Carbon::parse($membership->expire_date)->format('M-d-Y') }}
                                      @endif
                                    @endif
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <div class="col-4 font-weight-bold text-muted small">{{ __('Purchase Type') }}:</div>
                                  <div class="col-8 small">
                                    @if ($membership->is_trial == 1)
                                      {{ __('Trial') }}
                                    @else
                                      {{ $membership->price == 0 ? __('Free') : __('Regular') }}
                                    @endif
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ __('Close') }}</button>
                              </div>
                            </div>
                          </div>
                        </div>
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
            {{ __('Showing 1 to') }} {{ count($memberships) }} {{ __('of') }} {{ count($memberships) }} {{ __('entries') }}
          </div>
          <div>
            {{ $memberships->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
