@extends('admin.layout')

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Shops') }} <i class="fas fa-store text-muted" style="font-size: 1.1rem;"></i>
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
          <a href="#">{{ __('Shops') }}</a>
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
              <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Shops List') }}</h3>
              <p class="text-muted small m-0 mt-1">{{ __('Manage all shops in your system') }}</p>
            </div>
            
            <div class="d-flex align-items-center justify-content-end">
              <form action="{{ url()->full() }}" class="m-0">
                <div class="position-relative" style="width: 320px;">
                  <input type="text" name="term" class="form-control" value="{{ request()->input('term') }}"
                    placeholder="{{ __('Search by Shop Name / Username...') }}" style="border-radius: 10px; height: 42px; font-size: 0.85rem; padding-left: 38px; border: 1px solid var(--input-border); background: var(--input-bg); color: var(--text-main);">
                  <i class="fas fa-search position-absolute text-muted" style="left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.85rem; pointer-events: none;"></i>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-body pt-3">
          @if (count($shops) == 0)
            <div class="text-center py-5 text-muted">
              <i class="fas fa-store-slash" style="font-size: 48px; opacity: 0.5;"></i>
              <h4 class="mt-3 font-weight-bold">{{ __('NO SHOP FOUND') }}</h4>
            </div>
          @else
            <!-- Horizontal X-Direction Scrollable Table Container -->
            <div class="table-responsive" style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
              <table class="table table-hover align-middle" style="white-space: nowrap !important; min-width: 900px;">
                <thead>
                  <tr>
                    <th scope="col" style="width: 30px; white-space: nowrap !important;"></th>
                    <th scope="col" style="width: 60px; white-space: nowrap !important;">{{ __('Logo') }}</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('Shop Name') }}</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('Category') }}</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('Username') }}</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('Email') }}</th>
                    <th scope="col" style="white-space: nowrap !important;">{{ __('Rating') }}</th>
                    <th scope="col" style="width: 100px; white-space: nowrap !important;">
                      {{ __('Sort Order') }} <i class="fas fa-sort text-muted ml-1" style="font-size: 0.75rem;"></i>
                    </th>
                    <th scope="col" style="width: 130px; white-space: nowrap !important;">
                      {{ __('Approve Status') }} <i class="fas fa-sort text-muted ml-1" style="font-size: 0.75rem;"></i>
                    </th>
                    <th scope="col" class="text-right" style="width: 120px; white-space: nowrap !important;">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody id="sortable-shops">
                  @foreach ($shops as $key => $shop)
                    <tr class="sortable-row" data-id="{{ $shop->id }}">
                      <td class="text-muted" style="white-space: nowrap !important;">
                        <i class="fas fa-ellipsis-v reorder-handle mr-1" style="cursor: move; opacity: 0.5;"></i>
                        <i class="fas fa-ellipsis-v reorder-handle" style="cursor: move; opacity: 0.5;"></i>
                      </td>
                      <td style="white-space: nowrap !important;">
                        @if (!empty($shop->photo))
                          <img src="{{ asset('assets/front/img/user/' . $shop->photo) }}" 
                            alt="{{ $shop->shop_name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #E2E8F0;">
                        @else
                          <img src="{{ asset('assets/user/img/profile.png') }}" 
                            alt="Default" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #E2E8F0;">
                        @endif
                      </td>
                      <td style="white-space: nowrap !important;">
                        <div class="d-inline-flex flex-column align-items-start">
                          <span class="font-weight-bold text-dark" style="font-size: 0.875rem; white-space: nowrap;">{{ $shop->shop_name ?: __('N/A') }}</span>
                          <span class="status-pill-warning mt-1 py-0 px-2" style="font-size: 0.68rem; border-radius: 6px; white-space: nowrap;">
                            {{ __('Theme Default') }}
                          </span>
                        </div>
                      </td>
                      <td style="font-size: 0.85rem; font-weight: 500; white-space: nowrap !important;">
                        {{ $shop->category ? $shop->category->name : __('Grocery Shop') }}
                      </td>
                      <td style="font-size: 0.85rem; font-weight: 600; white-space: nowrap !important;">
                        {{ $shop->username }}
                      </td>
                      <td class="text-muted" style="font-size: 0.85rem; white-space: nowrap !important;">
                        {{ $shop->email }}
                      </td>
                      <td style="white-space: nowrap !important;">
                        <span class="badge badge-pill text-white font-weight-bold px-2 py-1" style="background: #3B82F6; font-size: 0.75rem; border-radius: 20px; white-space: nowrap;">
                          {{ $shop->landing_rating ?: '4.80' }} <i class="fas fa-star text-warning ml-1" style="font-size: 0.7rem;"></i>
                        </span>
                      </td>
                      <td class="sort-order-cell font-weight-bold" style="font-size: 0.875rem; white-space: nowrap !important;">
                        {{ $shop->landing_order ?? 0 }}
                      </td>
                      <td style="white-space: nowrap !important;">
                        <form id="statusForm{{ $shop->id }}" class="d-inline-block m-0" action="{{ route('admin.shops.status') }}" method="post">
                          @csrf
                          <div class="position-relative">
                            <select
                              class="form-control form-control-sm font-weight-bold border-0"
                              style="border-radius: 10px; height: 32px; font-size: 0.78rem; padding-right: 24px; cursor: pointer; {{ $shop->landing_status == 1 ? 'background: #ECFDF5; color: #10B981;' : 'background: #FFFBEB; color: #D97706;' }}"
                              name="landing_status"
                              onchange="document.getElementById('statusForm{{ $shop->id }}').submit();">
                              <option value="1" {{ $shop->landing_status == 1 ? 'selected' : '' }}>{{ __('Approved') }}</option>
                              <option value="0" {{ $shop->landing_status == 0 ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            </select>
                          </div>
                          <input type="hidden" name="user_id" value="{{ $shop->id }}">
                        </form>
                      </td>
                      <td class="text-right" style="white-space: nowrap !important;">
                        <div class="d-inline-flex align-items-center gap-2" style="white-space: nowrap;">
                          <a class="btn-primary-purple py-1 px-3" style="font-size: 0.78rem; border-radius: 8px; text-decoration: none; white-space: nowrap;" href="{{ route('admin.shops.edit', $shop->id) }}">
                            <i class="fas fa-pencil-alt mr-1" style="font-size: 0.75rem;"></i> {{ __('Edit') }}
                          </a>
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
          <div>
            {{ $shops->appends(['term' => request()->input('term')])->links() }}
          </div>
          <div>
            <select class="form-control form-control-sm" style="border-radius: 8px; font-size: 0.8rem; height: 34px;">
              <option value="10">10 / page</option>
              <option value="25">25 / page</option>
              <option value="50">50 / page</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
  $(function() {
    $("#sortable-shops").sortable({
      handle: '.reorder-handle',
      placeholder: "ui-state-highlight",
      update: function(event, ui) {
        var sortedIDs = [];
        $("#sortable-shops tr.sortable-row").each(function() {
          sortedIDs.push($(this).data('id'));
        });
        
        // Show request loader
        $(".request-loader").addClass("show");
        
        $.ajax({
          url: "{{ route('admin.shops.reorder') }}",
          type: "POST",
          data: {
            ids: sortedIDs,
            _token: "{{ csrf_token() }}"
          },
          success: function(response) {
            $(".request-loader").removeClass("show");
            if (response.status === 'success') {
              // Update Sort Order column text in the table dynamically
              $("#sortable-shops tr.sortable-row").each(function(index) {
                $(this).find('.sort-order-cell').text(index);
              });
              
              // Show notification
              var content = {};
              content.message = response.message;
              content.title = "{{ __('Success') }}";
              content.icon = 'fa fa-bell';
              $.notify(content, {
                type: 'success',
                placement: {
                  from: 'top',
                  align: 'right'
                },
                time: 1000,
                delay: 3000,
              });
            }
          },
          error: function(xhr) {
            $(".request-loader").removeClass("show");
            var content = {};
            content.message = "{{ __('Something went wrong!') }}";
            content.title = "{{ __('Error') }}";
            content.icon = 'fa fa-bell';
            $.notify(content, {
              type: 'danger',
              placement: {
                from: 'top',
                align: 'right'
              },
              time: 1000,
              delay: 3000,
            });
          }
        });
      }
    });
  });
</script>
@endsection
