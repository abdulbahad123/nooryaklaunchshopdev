@extends('admin.layout')

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Subscribers') }} <i class="fas fa-users text-muted" style="font-size: 1.1rem;"></i>
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
          <a href="#">{{ __('Users Management') }}</a>
        </li>
        <li class="separator">
          <i class="fas fa-chevron-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Subscribers') }}</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header border-0 pb-0">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
              <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Subscribers') }}</h3>
              <p class="text-muted small m-0 mt-1">{{ __('Manage all email subscribers') }}</p>
            </div>
            
            <div class="d-flex align-items-center gap-3">
              <form action="{{ url()->full() }}" class="m-0">
                <div class="position-relative" style="width: 240px;">
                  <input type="text" name="term" class="form-control pr-4" value="{{ request()->input('term') }}"
                    placeholder="{{ __('Search by Email...') }}" style="border-radius: 10px; height: 38px; font-size: 0.85rem;">
                  <button type="submit" class="btn p-0 border-0 position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); color: #94A3B8;">
                    <i class="fas fa-search" style="font-size: 0.85rem;"></i>
                  </button>
                </div>
              </form>

              <button class="btn btn-danger btn-sm rounded-pill d-none bulk-delete"
                data-href="{{ route('admin.subscriber.bulk.delete') }}">
                <i class="fas fa-trash mr-1"></i> {{ __('Delete Selected') }}
              </button>

              <a href="#" class="btn-primary-purple m-0 py-2 px-3" style="font-size: 0.85rem; border-radius: 10px; text-decoration: none;">
                <i class="fas fa-plus"></i> {{ __('Add Subscriber') }}
              </a>
            </div>
          </div>
        </div>

        <div class="card-body pt-3">
          @if (count($subscs) == 0)
            <div class="text-center py-5 text-muted">
              <i class="far fa-envelope-open" style="font-size: 48px; opacity: 0.5;"></i>
              <h4 class="mt-3 font-weight-bold">{{ __('NO SUBSCRIBER FOUND') }}</h4>
            </div>
          @else
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead>
                  <tr>
                    <th scope="col" style="width: 40px;">
                      <input type="checkbox" class="bulk-check" data-val="all">
                    </th>
                    <th scope="col">{{ __('Email Address') }}</th>
                    <th scope="col" style="width: 240px;">
                      {{ __('Subscribed On') }} <i class="fas fa-sliders-h ml-1 text-muted" style="font-size: 0.75rem;"></i>
                    </th>
                    <th scope="col" class="text-right" style="width: 120px;">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $colors = ['a-purple', 'a-orange', 'a-green', 'a-blue'];
                  @endphp
                  @foreach ($subscs as $key => $subsc)
                    @php
                      $bgClass = $colors[$key % count($colors)];
                    @endphp
                    <tr>
                      <td>
                        <input type="checkbox" class="bulk-check" data-val="{{ $subsc->id }}">
                      </td>
                      <td>
                        <div class="d-flex align-items-center">
                          <span class="user-avatar-initials {{ $bgClass }} m-0 mr-3" style="width: 34px; height: 34px; border-radius: 8px;">
                            <i class="fas fa-envelope" style="font-size: 0.85rem;"></i>
                          </span>
                          <span class="font-weight-bold text-dark" style="font-size: 0.875rem;">{{ $subsc->email }}</span>
                        </div>
                      </td>
                      <td class="text-muted" style="font-size: 0.85rem;">
                        {{ $subsc->created_at ? $subsc->created_at->format('d M Y, h:i A') : '19 Aug 2026, 10:45 PM' }}
                      </td>
                      <td class="text-right">
                        <div class="d-inline-flex align-items-center gap-2">
                          <form class="deleteform d-inline-block m-0" action="{{ route('admin.subscriber.delete') }}" method="post">
                            @csrf
                            <input type="hidden" name="subscriber_id" value="{{ $subsc->id }}">
                            <button type="submit" class="btn-action-square b-delete deletebtn position-relative" data-toggle="tooltip" data-placement="top" title="{{ __('Delete') }}">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </form>

                          <button type="button" class="btn-action-square b-more" title="{{ __('Options') }}">
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

        <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2 border-0 bg-transparent py-3">
          <div class="text-muted small">
            {{ __('Showing') }} {{ $subscs->firstItem() ?? 1 }} {{ __('to') }} {{ $subscs->lastItem() ?? count($subscs) }} {{ __('of') }} {{ $subscs->total() ?? count($subscs) }} {{ __('entries') }}
          </div>
          <div>
            {{ $subscs->appends(['term' => request()->input('term')])->links() }}
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
