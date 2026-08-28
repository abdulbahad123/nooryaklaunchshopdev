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
        {{ __('Popups') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
      </h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-home"></i>
          </a>
        </li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Announcement Popup') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Popups') }}</a></li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">
              <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Announcement Popups') }}</h3>
              <div style="min-width: 140px;">
                @include('admin.partials.languages')
              </div>
            </div>
            <div class="d-flex align-items-center gap-2">
              <button class="btn btn-danger btn-sm rounded-pill d-none bulk-delete"
                data-href="{{ route('admin.popup.bulk.delete') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
              <a href="{{ route('admin.popup.types') }}" class="btn-primary-purple py-2 px-4"
                style="border-radius: 10px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-plus"></i> {{ __('Add Popup') }}
              </a>
            </div>
          </div>
        </div>

        <div class="card-body p-4">
          @if (count($popups) == 0)
            <div class="text-center py-5 text-muted">
              <i class="fas fa-layer-group" style="font-size: 48px; opacity: 0.4;"></i>
              <h4 class="mt-3 font-weight-bold">{{ __('NO POPUP FOUND') }}</h4>
            </div>
          @else
            {{-- Notice Banner --}}
            <div class="popup-notice-banner mb-4">
              <div class="popup-notice-icon">
                <i class="fas fa-bullhorn"></i>
              </div>
              <p class="m-0" style="font-size: 0.9rem;">
                {{ __('All') }}
                <a href="#" class="font-weight-bold" style="color: #6366F1; text-decoration: none;">{{ __('Activated Popups') }}</a>
                {{ __('will be shown in website according to') }}
                <a href="#" class="font-weight-bold" style="color: #6366F1; text-decoration: none;">{{ __('Serial Number') }}</a>
              </p>
            </div>

            {{-- DataTable Header --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
              <div class="d-flex align-items-center gap-2" style="font-size: 0.875rem; color: var(--text-muted);">
                {{ __('Show') }}
                <select class="form-control form-control-sm" style="width: 70px; border-radius: 8px; height: 32px;">
                  <option>10</option>
                  <option>25</option>
                  <option>50</option>
                </select>
                {{ __('entries') }}
              </div>
              <div class="d-flex align-items-center gap-2">
                <span style="font-size: 0.875rem; color: var(--text-muted);">{{ __('Search:') }}</span>
                <div class="position-relative">
                  <input type="text" class="form-control form-control-sm" id="popupSearch"
                    placeholder="{{ __('Search...') }}"
                    style="border-radius: 8px; height: 34px; width: 220px; padding-left: 32px; font-size: 0.85rem; border: 1px solid var(--input-border); background: var(--input-bg); color: var(--text-main);">
                  <i class="fas fa-search position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 0.8rem;"></i>
                </div>
              </div>
            </div>

            <div class="table-responsive" style="overflow-x: auto; width: 100%;">
              <table class="table table-hover align-middle" id="basic-datatables" style="white-space: nowrap;">
                <thead>
                  <tr>
                    <th scope="col" style="width: 40px;">
                      <input type="checkbox" class="bulk-check" data-val="all">
                    </th>
                    <th scope="col">{{ __('Image') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col">{{ __('Type') }}</th>
                    <th scope="col">{{ __('Serial Number') }}</th>
                    <th scope="col" class="text-right">{{ __('Actions') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($popups as $key => $popup)
                    <tr>
                      <td>
                        <input type="checkbox" class="bulk-check" data-val="{{ $popup->id }}">
                      </td>
                      <td>
                        @if (!empty($popup->image))
                          <img src="{{ asset('assets/front/img/popups/' . $popup->image) }}"
                            style="width: 56px; height: 44px; object-fit: cover; border-radius: 8px;">
                        @elseif (!empty($popup->background_image))
                          <img src="{{ asset('assets/front/img/popups/' . $popup->background_image) }}"
                            style="width: 56px; height: 44px; object-fit: cover; border-radius: 8px;">
                        @else
                          <div style="width: 56px; height: 44px; background: var(--table-header-bg); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image text-muted"></i>
                          </div>
                        @endif
                      </td>
                      <td>
                        <span class="font-weight-600" style="font-size: 0.875rem;">
                          {{ strlen($popup->name) > 20 ? mb_substr($popup->name, 0, 20, 'utf-8') . '...' : $popup->name }}
                        </span>
                      </td>
                      <td>
                        <form id="statusForm{{ $popup->id }}" class="d-inline-block m-0"
                          action="{{ route('admin.popup.status') }}" method="post">
                          @csrf
                          <input type="hidden" name="popup_id" value="{{ $popup->id }}">
                          <select
                            class="form-control form-control-sm font-weight-bold border-0 cd-status-select {{ $popup->status == 1 ? 'cd-status-connected' : 'cd-status-rejected' }}"
                            name="status"
                            onchange="document.getElementById('statusForm{{ $popup->id }}').submit();">
                            <option value="1" {{ $popup->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ $popup->status == 0 ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                          </select>
                        </form>
                      </td>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <img width="52" style="border-radius: 8px; object-fit: cover;"
                            src="{{ asset('assets/admin/img/popups/popup-' . $popup->type . '.jpg') }}">
                          <span style="font-size: 0.8rem; color: var(--text-muted);">{{ __('Type') . ' - ' }}{{ $popup->type }}</span>
                        </div>
                      </td>
                      <td>
                        <span class="font-weight-bold" style="font-size: 0.875rem;">{{ $popup->serial_number }}</span>
                      </td>
                      <td class="text-right">
                        <div class="d-inline-flex align-items-center gap-2">
                          <a class="btn-action-square b-edit"
                            href="{{ route('admin.popup.edit', $popup->id) . '?language=' . request()->input('language') }}"
                            title="{{ __('Edit') }}">
                            <i class="fas fa-edit"></i>
                          </a>
                          <form class="deleteform d-inline-block m-0" action="{{ route('admin.popup.delete') }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="popup_id" value="{{ $popup->id }}">
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

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
              <div class="text-muted small">
                {{ __('Showing 1 to') }} {{ count($popups) }} {{ __('of') }} {{ count($popups) }} {{ __('entries') }}
              </div>
              <div class="d-flex align-items-center gap-1">
                <button class="btn btn-sm" style="border: 1px solid var(--input-border); background: var(--bg-card); color: var(--text-muted); border-radius: 6px; padding: 4px 12px;">{{ __('Previous') }}</button>
                <button class="btn btn-sm" style="background: #6366F1; color: #fff; border-radius: 6px; padding: 4px 12px; border: none;">1</button>
                <button class="btn btn-sm" style="border: 1px solid var(--input-border); background: var(--bg-card); color: var(--text-muted); border-radius: 6px; padding: 4px 12px;">{{ __('Next') }}</button>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
