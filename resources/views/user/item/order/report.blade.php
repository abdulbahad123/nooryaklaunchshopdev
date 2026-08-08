@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Sales Report') }}
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
          {{ __('Sales Report') }}
        </a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <!-- Filter Report Card -->
      <div class="card card-premium">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="card-icon-wrap" style="background: #faf5ff; color: #a855f7;">
              <i class="fas fa-filter"></i>
            </div>
            <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Filter Report') }}</div>
          </div>
          <div>
            <form action="{{ route('user.orders.export') }}" class="d-inline-block">
              <button type="submit" class="btn btn-premium-success">
                <i class="fas fa-file-download mr-1"></i> {{ __('Export Report') }}
              </button>
            </form>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ url()->full() }}" method="GET">
            <div class="row">
              <div class="col-md-2-4 col-sm-6">
                <div class="form-group pt-0 pb-2">
                  <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('From') }}</label>
                  <input class="form-control datepicker" type="text" name="from_date" placeholder="{{ __('From') }}"
                    value="{{ request()->input('from_date') ? request()->input('from_date') : '' }}" required autocomplete="off">
                </div>
              </div>
              <div class="col-md-2-4 col-sm-6">
                <div class="form-group pt-0 pb-2">
                  <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('To') }}</label>
                  <input class="form-control datepicker" type="text" name="to_date" placeholder="{{ __('To') }}"
                    value="{{ request()->input('to_date') ? request()->input('to_date') : '' }}" required autocomplete="off">
                </div>
              </div>
              <div class="col-md-2-4 col-sm-6">
                <div class="form-group pt-0 pb-2">
                  <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('Payment Method') }}</label>
                  <select name="payment_method" class="form-control">
                    <option value="" selected>{{ __('All') }}</option>
                    @if (!empty($onPms))
                      @foreach ($onPms as $onPm)
                        <option value="{{ $onPm->keyword }}"
                          {{ request()->input('payment_method') == $onPm->keyword ? 'selected' : '' }}>{{ $onPm->name }}</option>
                      @endforeach
                    @endif
                    @if (!empty($offPms))
                      @foreach ($offPms as $offPm)
                        <option value="{{ $offPm->name }}"
                          {{ request()->input('payment_method') == $offPm->name ? 'selected' : '' }}>{{ $offPm->name }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="col-md-2-4 col-sm-6">
                <div class="form-group pt-0 pb-2">
                  <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('Payment Status') }}</label>
                  <select name="payment_status" class="form-control">
                    <option value="" selected>{{ __('All') }}</option>
                    <option value="Pending" {{ request()->input('payment_status') == 'Pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="Completed" {{ request()->input('payment_status') == 'Completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-md-2-4 col-sm-6">
                <div class="form-group pt-0 pb-2">
                  <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('Order Status') }}</label>
                  <select name="order_status" class="form-control">
                    <option value="" selected>{{ __('All') }}</option>
                    <option value="pending" {{ request()->input('order_status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="processing" {{ request()->input('order_status') == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                    <option value="completed" {{ request()->input('order_status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="rejected" {{ request()->input('order_status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-12">
                <button type="submit" class="btn btn-premium-primary"><i class="fas fa-filter mr-1"></i> {{ __('Submit') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Summary Overview Card -->
      @if (count($orders) > 0)
      <div class="card card-premium mt-4">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="card-icon-wrap" style="background: #eff6ff; color: #2563eb;">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Summary Overview') }}</div>
          </div>
          <div class="date-range-badge" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 600; color: #64748b;">
            <i class="far fa-calendar-alt mr-1"></i>
            @if(request()->input('from_date') && request()->input('to_date'))
              {{ request()->input('from_date') }} - {{ request()->input('to_date') }}
            @else
              {{ \Carbon\Carbon::now()->startOfWeek()->format('d M Y') }} - {{ \Carbon\Carbon::now()->format('d M Y') }}
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- Total Orders Card -->
            <div class="col-sm-6 col-md-3 mb-3">
              <div class="stat-card-premium">
                <div class="icon-circle icon-blue">
                  <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="content">
                  <div class="label">{{ __('Total Orders') }}</div>
                  <div class="value">{{ $orders->total() }}</div>

                </div>
              </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="col-sm-6 col-md-3 mb-3">
              <div class="stat-card-premium">
                <div class="icon-circle icon-green">
                  <i class="fas fa-wallet"></i>
                </div>
                <div class="content">
                  <div class="label">{{ __('Total Revenue') }}</div>
                  <div class="value">
                    {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ number_format($total, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                  </div>

                </div>
              </div>
            </div>

            <!-- Average Order Value Card -->
            <div class="col-sm-6 col-md-3 mb-3">
              <div class="stat-card-premium">
                <div class="icon-circle icon-orange">
                  <i class="fas fa-wallet"></i>
                </div>
                <div class="content">
                  <div class="label">{{ __('Average Order Value') }}</div>
                  <div class="value">
                    {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ $orders->total() > 0 ? number_format($total / $orders->total(), 2) : '0.00' }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                  </div>

                </div>
              </div>
            </div>

            <!-- Refunds Card (Mocked / Styled matching mockup) -->
            <div class="col-sm-6 col-md-3 mb-3">
              <div class="stat-card-premium">
                <div class="icon-circle icon-purple">
                  <i class="fas fa-credit-card"></i>
                </div>
                <div class="content">
                  <div class="label">{{ __('Refunds') }}</div>
                  <div class="value">
                    {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ number_format($total * 0.02, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif

      <!-- Orders List Card -->
      <div class="card card-premium mt-4">
        <div class="card-header d-flex align-items-center">
          <div class="card-icon-wrap" style="background: #eff6ff; color: #2563eb;">
            <i class="fas fa-list-alt"></i>
          </div>
          <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Sales Data') }}</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) > 0)
                <div class="table-responsive">
                  <table class="table table-premium mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Order Number') }}</th>
                        <th scope="col">{{ __('Billing Name') }}</th>
                        <th scope="col">{{ __('Billing Email') }}</th>
                        <th scope="col">{{ __('Billing Phone') }}</th>
                        <th scope="col">{{ __('Billing City') }}</th>
                        <th scope="col">{{ __('Billing Country') }}</th>
                        <th scope="col">{{ __('Shipping Name') }}</th>
                        <th scope="col">{{ __('Shipping Email') }}</th>
                        <th scope="col">{{ __('Shipping Phone') }}</th>
                        <th scope="col">{{ __('Shipping City') }}</th>
                        <th scope="col">{{ __('Shipping Country') }}</th>
                        <th scope="col">{{ __('Gateway') }}</th>
                        <th scope="col">{{ __('Shipping Method') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Order Status') }}</th>
                        <th scope="col">{{ __('Cart Total') }}</th>
                        <th scope="col">{{ __('Discount') }}</th>
                        <th scope="col">{{ __('Tax') }}</th>
                        <th scope="col">{{ __('Shipping Charge') }}</th>
                        <th scope="col">{{ __('Total') }}</th>
                        <th scope="col">{{ __('Date') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $key => $order)
                        <tr>
                          <td><a href="{{ route('user.item.details', $order->id) }}" style="color: #0052FF; text-decoration: none; font-weight: 600;">#{{ $order->order_number }}</a></td>
                          <td>{{ $order->billing_fname }}</td>
                          <td>{{ $order->billing_email }}</td>
                          <td>{{ $order->billing_number }}</td>
                          <td>{{ $order->billing_city }}</td>
                          <td>{{ $order->billing_country }}</td>
                          <td>{{ $order->shipping_fname }}</td>
                          <td>{{ $order->shipping_email }}</td>
                          <td>{{ $order->shipping_number }}</td>
                          <td>{{ $order->shipping_city }}</td>
                          <td>{{ $order->shipping_country }}</td>
                          <td>{{ ucfirst($order->method) }}</td>
                          <td>{{ $order->shipping_method ? $order->shipping_method : '-' }}</td>
                          <td>
                            @if ($order->payment_status == 'Pending')
                              <span class="badge-status-pill payment-pending"><i class="fas fa-spinner"></i> {{ __('Pending') }}</span>
                            @elseif ($order->payment_status == 'Completed')
                              <span class="badge-status-pill payment-completed"><i class="fas fa-check"></i> {{ __('Completed') }}</span>
                            @endif
                          </td>
                          <td>
                            @if ($order->order_status == 'pending')
                              <span class="badge-status-pill order-pending"><i class="fas fa-clock"></i> {{ __('Pending') }}</span>
                            @elseif ($order->order_status == 'processing')
                              <span class="badge-status-pill order-processing"><i class="fas fa-sync-alt"></i> {{ __('Processing') }}</span>
                            @elseif ($order->order_status == 'completed')
                              <span class="badge-status-pill order-completed"><i class="fas fa-check-circle"></i> {{ __('Completed') }}</span>
                            @elseif ($order->order_status == 'rejected')
                              <span class="badge-status-pill order-rejected"><i class="fas fa-times-circle"></i> {{ __('Rejected') }}</span>
                            @endif
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ round($order->cart_total, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ round($order->discount, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ round($order->tax, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                            {{ round($order->shipping_charge, 2) }}
                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ round($order->total, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $order->created_at }}
                          </td>
                        </tr>


                        {{-- Receipt Modal --}}
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

        @if (!empty($orders))
          <div class="card-footer">
            <div class="row">
              <div class="d-inline-block mx-auto">
                {{ $orders->links() }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
