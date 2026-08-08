@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      @if (request()->routeIs('user.all.item.orders'))
        {{ __('All') }}
      @elseif (request()->routeIs('user.pending.item.orders'))
        {{ __('Pending') }}
      @elseif (request()->routeIs('user.processing.item.orders'))
        {{ __('Processing') }}
      @elseif (request()->routeIs('user.completed.item.orders'))
        {{ __('Completed') }}
      @elseif (request()->routeIs('user.rejected.item.orders'))
        {{ __('Rejcted') }}
      @endif
      {{ __('Orders') }}
    </h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('user-dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">
          @if (request()->routeIs('user.all.item.orders'))
            {{ __('All') }}
          @elseif (request()->routeIs('user.pending.item.orders'))
            {{ __('Pending') }}
          @elseif (request()->routeIs('user.processing.item.orders'))
            {{ __('Processing') }}
          @elseif (request()->routeIs('user.completed.item.orders'))
            {{ __('Completed') }}
          @elseif (request()->routeIs('user.rejected.item.orders'))
            {{ __('Rejcted') }}
          @elseif (request()->path() == 'admin/product/search/orders')
            {{ __('Search') }}
          @endif
          {{ __('Orders') }}
        </a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      @php
        $userId = Auth::guard('web')->user()->id;
        $userBs = App\Models\User\BasicSetting::where('user_id', $userId)->first();
        
        $totalOrders = isset($totalOrders) ? $totalOrders : App\Models\User\UserOrder::where('user_id', $userId)->count();
        $totalRevenue = isset($totalRevenue) ? $totalRevenue : App\Models\User\UserOrder::where('user_id', $userId)->where('payment_status', 'Completed')->sum('total');
        $pendingOrders = isset($pendingOrders) ? $pendingOrders : App\Models\User\UserOrder::where('user_id', $userId)->where('order_status', 'pending')->count();
        $processingOrders = isset($processingOrders) ? $processingOrders : App\Models\User\UserOrder::where('user_id', $userId)->where('order_status', 'processing')->count();
        $completedOrders = isset($completedOrders) ? $completedOrders : App\Models\User\UserOrder::where('user_id', $userId)->where('order_status', 'completed')->count();
        $rejectedOrders = isset($rejectedOrders) ? $rejectedOrders : App\Models\User\UserOrder::where('user_id', $userId)->where('order_status', 'rejected')->count();
      @endphp

      <!-- Stats Date Range Filter Dropdown -->
      <div class="row align-items-center mb-3">
        <div class="col-lg-12">
          <form id="filterForm" action="{{ url()->current() }}" method="GET" class="form-inline float-right">
            @if(request()->has('search'))
              <input type="hidden" name="search" value="{{ request()->input('search') }}">
            @endif
            
            <div class="d-flex align-items-center" style="gap: 10px; flex-wrap: wrap;">
              <!-- Custom Date Range pickers -->
              <div id="customDateRange" class="d-none align-items-center" style="gap: 6px;">
                <input type="date" name="start_date" id="startDateInput" class="form-control form-control-sm" value="{{ request()->input('start_date') }}" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1;">
                <span class="text-muted" style="font-size: 13px;">to</span>
                <input type="date" name="end_date" id="endDateInput" class="form-control form-control-sm" value="{{ request()->input('end_date') }}" style="height: 38px; border-radius: 8px; border: 1px solid #cbd5e1;">
                <button type="submit" class="btn btn-primary btn-sm" style="height: 38px; border-radius: 8px; font-weight: 600; padding: 0 15px;">{{ __('Apply') }}</button>
              </div>

              <!-- Main Filter Dropdown -->
              <select name="range" id="dateRangeSelect" class="form-control" style="height: 38px; border-radius: 8px; font-weight: 600; color: #1e293b; border: 1px solid #cbd5e1; cursor: pointer; padding: 0 10px;" onchange="handleRangeChange(this)">
                <option value="all" {{ request()->input('range') == 'all' || !request()->has('range') ? 'selected' : '' }}>{{ __('All Time') }}</option>
                <option value="today" {{ request()->input('range') == 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                <option value="yesterday" {{ request()->input('range') == 'yesterday' ? 'selected' : '' }}>{{ __('Yesterday') }}</option>
                <option value="7" {{ request()->input('range') == '7' ? 'selected' : '' }}>{{ __('7 Days') }}</option>
                <option value="30" {{ request()->input('range') == '30' ? 'selected' : '' }}>{{ __('30 Days') }}</option>
                <option value="90" {{ request()->input('range') == '90' ? 'selected' : '' }}>{{ __('90 Days') }}</option>
                <option value="365" {{ request()->input('range') == '365' ? 'selected' : '' }}>{{ __('This Year') }}</option>
                <option value="custom" {{ request()->input('range') == 'custom' ? 'selected' : '' }}>{{ __('Custom Date') }}</option>
              </select>
            </div>
          </form>
        </div>
      </div>

      <!-- Stats Grid Row -->
      <div class="row mb-3">
        <!-- Total Orders Card -->
        <div class="col-sm-6 col-md-4 col-xl-2">
          <div class="stat-card-premium">
            <div class="icon-circle icon-blue">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="content">
              <div class="label">{{ __('Total Orders') }}</div>
              <div class="value">{{ $totalOrders }}</div>
            </div>
          </div>
        </div>
 
        <!-- Total Revenue Card -->
        <div class="col-sm-6 col-md-4 col-xl-2">
          <div class="stat-card-premium">
            <div class="icon-circle icon-purple">
              <i class="fas fa-wallet"></i>
            </div>
            <div class="content">
              <div class="label">{{ __('Total Revenue') }}</div>
                @php
                  $symbol = $userBs ? $userBs->base_currency_symbol : '';
                  if ($symbol == '$') {
                      $symbol = '₹';
                  }
                @endphp
                {{ $userBs && $userBs->base_currency_symbol_position == 'left' ? $symbol : '' }}{{ number_format($totalRevenue, 2) }}{{ $userBs && $userBs->base_currency_symbol_position == 'right' ? $symbol : '' }}
            </div>
          </div>
        </div>
 
        <!-- Pending Orders Card -->
        <div class="col-sm-6 col-md-4 col-xl-2">
          <div class="stat-card-premium">
            <div class="icon-circle icon-orange">
              <i class="fas fa-clock"></i>
            </div>
            <div class="content">
              <div class="label">{{ __('Pending Orders') }}</div>
              <div class="value">{{ $pendingOrders }}</div>
            </div>
          </div>
        </div>

        <!-- Processing Orders Card -->
        <div class="col-sm-6 col-md-4 col-xl-2">
          <div class="stat-card-premium">
            <div class="icon-circle icon-yellow">
              <i class="fas fa-spinner"></i>
            </div>
            <div class="content">
              <div class="label">{{ __('Processing Orders') }}</div>
              <div class="value">{{ $processingOrders }}</div>
            </div>
          </div>
        </div>
 
        <!-- Completed Orders Card -->
        <div class="col-sm-6 col-md-4 col-xl-2">
          <div class="stat-card-premium">
            <div class="icon-circle icon-green">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="content">
              <div class="label">{{ __('Completed Orders') }}</div>
              <div class="value">{{ $completedOrders }}</div>
            </div>
          </div>
        </div>

        <!-- Rejected Orders Card -->
        <div class="col-sm-6 col-md-4 col-xl-2">
          <div class="stat-card-premium">
            <div class="icon-circle icon-red">
              <i class="fas fa-times-circle"></i>
            </div>
            <div class="content">
              <div class="label">{{ __('Rejected Orders') }}</div>
              <div class="value">{{ $rejectedOrders }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-premium">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-lg-6 d-flex align-items-center">
              <div class="card-icon-wrap" style="background: #eff6ff; color: #2563eb; margin-right: 12px;">
                <i class="fas fa-shopping-bag"></i>
              </div>
              <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">
                @if (request()->routeIs('user.all.item.orders'))
                  {{ __('All') }}
                @elseif (request()->routeIs('user.pending.item.orders'))
                  {{ __('Pending') }}
                @elseif (request()->routeIs('user.processing.item.orders'))
                  {{ __('Processing') }}
                @elseif (request()->routeIs('user.completed.item.orders'))
                  {{ __('Completed') }}
                @elseif (request()->routeIs('user.rejected.item.orders'))
                  {{ __('Rejected') }}
                @elseif (request()->path() == 'admin/item/search/orders')
                  {{ __('Search') }}
                @endif
                {{ __('Orders') }}
              </div>
            </div>
            <div class="col-lg-6">
              <button
                class="btn btn-danger float-right btn-md d-none bulk-delete btn-sm {{ $dashboard_language->rtl == 1 ? 'mr-4' : 'ml-4' }}"
                data-href="{{ route('user.item.order.bulk.delete') }}" style="border-radius: 8px;"><i class="fas fa-trash mr-1"></i>
                {{ __('Delete') }}</button>
              <button
                class="btn btn-primary float-right btn-md d-none bulk-processing btn-sm {{ $dashboard_language->rtl == 1 ? 'mr-4' : 'ml-4' }}"
                data-href="{{ route('user.item.order.bulk.processing') }}" style="border-radius: 8px;"><i class="fas fa-sync-alt mr-1"></i>
                {{ __('Bulk Processing') }}</button>
              <form action="{{ url()->current() }}" class="d-inline-block float-right index-search-form">
                <div class="input-icon-wrapper" style="width: 250px;">
                  <i class="fas fa-search input-icon-prefix"></i>
                  <input class="form-control" type="text" name="search" placeholder="{{ __('Search by Order Number') }}"
                    value="{{ request()->input('search') ? request()->input('search') : '' }}" style="height: 38px; padding-left: 36px !important;">
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) == 0)
                <h3 class="text-center">{{ __('NO ORDER FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-premium mt-3">
                    <thead>
                      <tr>
                        <th scope="col" style="width: 40px;">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>

                        <th scope="col">{{ __('Order Number') }}</th>
                        <th scope="col">{{ __('Gateway') }}</th>
                        <th scope="col">{{ __('Total') }}</th>
                        <th scope="col">{{ __('Order Status') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col" class="text-center">{{ __('Receipt') }}</th>
                        <th scope="col" class="text-center">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $key => $order)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $order->id }}">
                          </td>
                          <td style="font-weight: 600;"><a href="{{ route('user.item.details', $order->id) }}" style="color: #0052FF; text-decoration: none; transition: color 0.15s ease-in-out;">#{{ $order->order_number }}</a></td>
                          <td style="text-transform: capitalize;">{{ $order->method }}</td>
                          <td style="font-weight: 600; color: #0052FF;">
                            {{ textPrice($order->currency_text_position, $order->currency_code, round($order->total, 2)) }}
                          </td>
                          <td>
                            @if ($order->order_status != 'rejected')
                              <form id="statusForm{{ $order->id }}" class="d-inline-block"
                                action="{{ route('user.item.orders.status') }}" method="post">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <select
                                  class="form-control form-control-sm
                              @if ($order->order_status == 'pending') bg-warning
                              @elseif ($order->order_status == 'processing')
                                bg-primary
                              @elseif ($order->order_status == 'completed')
                                bg-success
                              @elseif ($order->order_status == 'rejected')
                                bg-danger @endif
                              "
                                  name="order_status"
                                  onchange="document.getElementById('statusForm{{ $order->id }}').submit();"
                                  style="border-radius: 30px !important; font-weight: 600; padding: 4px 20px 4px 10px !important; height: auto !important; border: 1px solid transparent !important; cursor: pointer; color: #ffffff !important;">
                                  <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }} style="color: #333;">
                                    {{ __('Pending') }}</option>
                                  <option value="processing"
                                    {{ $order->order_status == 'processing' ? 'selected' : '' }} style="color: #333;">
                                    {{ __('Processing') }}</option>
                                  <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }} style="color: #333;">
                                    {{ __('Completed') }}</option>
                                  <option value="rejected" {{ $order->order_status == 'rejected' ? 'selected' : '' }} style="color: #333;">
                                    {{ __('Rejected') }}</option>
                                </select>
                              </form>
                            @else
                              <span class="badge-status-pill order-rejected" style="background: #ef4444 !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-times-circle"></i> {{ __('Rejected') }}</span>
                            @endif
                          </td>

                          <td>
                            @if ($order->gateway_type != 'offline')
                              @if ($order->payment_status == 'Completed')
                                <span class="badge-status-pill payment-completed" style="background: #22c55e !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-check"></i> {{ __('Completed') }}</span>
                              @elseif($order->payment_status == 'Pending')
                                <span class="badge-status-pill payment-pending" style="background: #f59e0b !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-spinner"></i> {{ __('Pending') }}</span>
                              @elseif($order->payment_status == 'Rejected')
                                <span class="badge-status-pill payment-rejected" style="background: #ef4444 !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-times"></i> {{ __('Rejected') }}</span>
                              @endif
                            @elseif ($order->gateway_type == 'offline')
                              @if ($order->payment_status == 'Rejected')
                                <span class="badge-status-pill payment-rejected" style="background: #ef4444 !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-times"></i> {{ __('Rejected') }}</span>
                              @else
                                <form action="{{ route('user.item.paymentStatus') }}"
                                  id="paymentStatusForm{{ $order->id }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="order_id" value="{{ $order->id }}">
                                  <select
                                    class="form-control form-control-sm
                                    @if ($order->payment_status == 'Completed') bg-success
                                    @elseif($order->payment_status == 'Pending')
                                        bg-warning @endif
                                    "
                                    name="payment_status"
                                    onchange="document.getElementById('paymentStatusForm{{ $order->id }}').submit();"
                                    style="border-radius: 30px !important; font-weight: 600; padding: 4px 20px 4px 10px !important; height: auto !important; border: 1px solid transparent !important; cursor: pointer; color: #ffffff !important;">
                                    <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }} style="color: #333;">
                                      {{ __('Pending') }}</option>
                                    <option value="Completed"
                                      {{ $order->payment_status == 'Completed' ? 'selected' : '' }} style="color: #333;">
                                      {{ __('Completed') }}</option>
                                    <option value="Rejected"
                                      {{ $order->payment_status == 'Rejected' ? 'selected' : '' }} style="color: #333;">
                                      {{ __('Rejected') }}</option>
                                  </select>
                                </form>
                              @endif
                            @endif
                          </td>

                          <td class="text-center">
                            @if (!empty($order->invoice_number))
                              <a class="btn-action-more" href="{{ asset('assets/front/invoices/' . $order->invoice_number) }}" target="_blank" title="{{ __('Show Invoice') }}">
                                <i class="fas fa-receipt text-info"></i>
                              </a>
                            @else
                              -
                            @endif
                          </td>

                          <td>
                            <div class="d-flex align-items-center justify-content-center">
                              <div class="dropdown">
                                <button class="btn-action-more" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                  <a class="dropdown-item" href="{{ route('user.item.details', $order->id) }}" style="font-size: 13px; font-weight: 500;">
                                    <i class="fas fa-eye mr-2 text-muted" style="width: 16px;"></i> {{ __('Details') }}
                                  </a>
                                  <a class="dropdown-item" href="{{ asset('assets/front/invoices/' . $order->invoice_number) }}" target="_blank" style="font-size: 13px; font-weight: 500;">
                                    <i class="fas fa-file-invoice mr-2 text-muted" style="width: 16px;"></i> {{ __('Invoice') }}
                                  </a>
                                  <div class="dropdown-divider"></div>
                                  <form class="deleteform d-block" action="{{ route('user.item.order.delete') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <button type="submit" class="dropdown-item text-danger deletebtn" style="font-size: 13px; font-weight: 500;">
                                      <i class="fas fa-trash-alt mr-2" style="width: 16px;"></i> {{ __('Delete') }}
                                    </button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>

                        <div class="modal fade" id="receiptModal{{ $order->id }}" tabindex="-1" role="dialog"
                          aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                  {{ __('Receipt Image') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <img src="{{ asset('assets/front/receipt/' . $order->receipt) }}" alt="Receipt"
                                  class="w-100">
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                  data-dismiss="modal">{{ __('Close') }}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <!-- Send Mail Modal -->
                <div class="modal fade" id="mailModal" tabindex="-1" role="dialog"
                  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                          {{ __('Send Mail') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="ajaxEditForm" class="" action="{{ route('user.orders.mail') }}"
                          method="POST">
                          @csrf
                          <div class="form-group">
                            <label for="">{{ __('Client Mail') }} <span class="text-danger">**</span></label>
                            <input id="inemail" type="text" class="form-control" name="email" value=""
                              placeholder="{{ __('Enter email') }}">
                            <p id="eerremail" class="mb-0 text-danger em"></p>
                          </div>
                          <div class="form-group">
                            <label for="">{{ __('Subject') }} <span class="text-danger">**</span></label>
                            <input id="insubject" type="text" class="form-control" name="subject" value=""
                              placeholder="{{ __('Enter subject') }}">
                            <p id="eerrsubject" class="mb-0 text-danger em"></p>
                          </div>
                          <div class="form-group">
                            <label for="">{{ __('Message') }} <span class="text-danger">**</span></label>
                            <textarea id="inmessage" class="form-control summernote" name="message" placeholder="{{ __('Enter message') }}"
                              data-height="150"></textarea>
                            <p id="eerrmessage" class="mb-0 text-danger em"></p>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                          data-dismiss="modal">{{ __('Close') }}</button>
                        <button id="updateBtn" type="button" class="btn btn-primary">{{ __('Send Mail') }}</button>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $orders->withQueryString()->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
  function handleRangeChange(select) {
    var range = select.value;
    var customDiv = document.getElementById('customDateRange');
    if (range === 'custom') {
      customDiv.classList.remove('d-none');
      customDiv.classList.add('d-flex');
    } else {
      customDiv.classList.remove('d-flex');
      customDiv.classList.add('d-none');
      document.getElementById('filterForm').submit();
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    var select = document.getElementById('dateRangeSelect');
    if (select && select.value === 'custom') {
      var customDiv = document.getElementById('customDateRange');
      if (customDiv) {
        customDiv.classList.remove('d-none');
        customDiv.classList.add('d-flex');
      }
    }
  });
</script>
@endsection
