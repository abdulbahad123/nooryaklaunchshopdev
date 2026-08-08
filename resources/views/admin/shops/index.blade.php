@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Shops') }}
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
        <a href="#">{{ __('Shops') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-6">
              <div class="card-title">
                {{ __('Shops List') }}
              </div>
            </div>
            <div class="col-lg-6 mt-2 mt-lg-0 d-block d-lg-flex justify-content-end gap-3">
              <form action="{{ url()->full() }}" class="float-none mt-2 mt-lg-0">
                <input type="text" name="term" class="form-control min-w-250" value="{{ request()->input('term') }}"
                  placeholder="{{ __('Search by Shop Name / Username / Email') }}">
              </form>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($shops) == 0)
                <h3 class="text-center">{{ __('NO SHOP FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col"></th>
                        <th scope="col">{{ __('Logo') }}</th>
                        <th scope="col">{{ __('Thumbnail') }}</th>
                        <th scope="col">{{ __('Shop Name') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Rating') }}</th>
                        <th scope="col">{{ __('Sort Order') }}</th>
                        <th scope="col">{{ __('Approve Status') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody id="sortable-shops">
                      @foreach ($shops as $key => $shop)
                        <tr class="sortable-row" data-id="{{ $shop->id }}">
                          <td>
                            <i class="fas fa-arrows-alt reorder-handle" style="cursor: move; color: #888;"></i>
                          </td>
                          <td>
                            @if (!empty($shop->photo))
                              <img src="{{ asset('assets/front/img/user/' . $shop->photo) }}" 
                                alt="{{ $shop->shop_name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #ddd;">
                            @else
                              <img src="{{ asset('assets/user/img/profile.png') }}" 
                                alt="Default" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #ddd;">
                            @endif
                          </td>
                          <td>
                            @if (!empty($shop->template_img))
                              <img src="{{ asset('assets/front/img/template-previews/' . $shop->template_img) }}" 
                                alt="{{ $shop->shop_name }}" style="width: 90px; height: 55px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                            @else
                              <span class="badge badge-warning">{{ __('Theme Default') }}</span>
                            @endif
                          </td>
                          <td>{{ $shop->shop_name ?: __('N/A') }}</td>
                          <td>{{ $shop->category ? $shop->category->name : __('N/A') }}</td>
                          <td>{{ $shop->username }}</td>
                          <td>{{ $shop->email }}</td>
                          <td>
                            <span class="badge badge-info">{{ $shop->landing_rating ?: '4.80' }} <i class="fas fa-star text-warning"></i></span>
                          </td>
                          <td class="sort-order-cell">{{ $shop->landing_order }}</td>
                          <td>
                            <form id="statusForm{{ $shop->id }}" class="d-inline-block"
                              action="{{ route('admin.shops.status') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $shop->landing_status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="landing_status"
                                onchange="document.getElementById('statusForm{{ $shop->id }}').submit();">
                                <option value="1" {{ $shop->landing_status == 1 ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                <option value="0" {{ $shop->landing_status == 0 ? 'selected' : '' }}>{{ __('Pending/Hidden') }}</option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $shop->id }}">
                            </form>
                          </td>
                          <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.shops.edit', $shop->id) }}">
                              <i class="fas fa-edit"></i> {{ __('Edit') }}
                            </a>
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
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $shops->appends(['term' => request()->input('term')])->links() }}
            </div>
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
