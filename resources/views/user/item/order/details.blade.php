@extends('user.layout')
@php
  $user = Auth::guard('web')->user();
  $permissions = [];
  if (!empty($user)) {
      $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
      $permissions = json_decode($permissions, true);
  }
@endphp
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Order Details') }}</h4>
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
        <a href="{{ url()->previous() }}">{{ __('All Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Order Details') }}</a>
      </li>
    </ul>
    <a href="{{ route('user.all.item.orders') }}" class="btn-md btn btn-primary ml-auto">{{ __('Back') }}</a>
  </div>



  <div class="row">
    <!-- Order Card -->
    <div class="col-md-4">
      <div class="card card-premium">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="card-icon-wrap" style="background: #eff6ff; color: #2563eb;">
              <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">
              {{ __('Order') }} #{{ $order->order_number }}
              <a href="#" onclick="copyText('{{ $order->order_number }}', this); return false;" style="margin-left: 8px; color: #94a3b8; font-size: 14px;" title="Copy Order Number">
                <i class="far fa-copy"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-3 align-items-center">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Payment Status') }} :
              </div>
               <div class="col-lg-6">
                @if ($order->payment_status == 'Pending' || $order->payment_status == 'pending')
                  <span class="badge-status-pill payment-pending" style="background: #f59e0b !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-spinner"></i> {{ convertUtf8($order->payment_status) }} </span>
                @elseif ($order->payment_status == 'Completed')
                  <span class="badge-status-pill payment-completed" style="background: #22c55e !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-check"></i> {{ convertUtf8($order->payment_status) }} </span>
                @elseif ($order->payment_status == 'Rejected')
                  <span class="badge-status-pill payment-rejected" style="background: #ef4444 !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-times"></i> {{ convertUtf8($order->payment_status) }} </span>
                @endif
              </div>
            </div>

            <div class="row mb-3 align-items-center">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Order Status') }} :
              </div>
              <div class="col-lg-6">
                @if ($order->order_status == 'pending')
                  <span class="badge-status-pill order-pending" style="background: #f59e0b !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-clock"></i> {{ ucfirst($order->order_status) }} </span>
                @elseif ($order->order_status == 'processing')
                  <span class="badge-status-pill order-processing" style="background: #3b82f6 !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-sync-alt"></i> {{ ucfirst($order->order_status) }} </span>
                @elseif ($order->order_status == 'completed')
                  <span class="badge-status-pill order-completed" style="background: #22c55e !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-check-circle"></i> {{ ucfirst($order->order_status) }} </span>
                @elseif ($order->order_status == 'rejected')
                  <span class="badge-status-pill order-rejected" style="background: #ef4444 !important; color: #ffffff !important; border: none !important; border-radius: 30px !important; padding: 4px 12px !important; font-size: 12px !important; font-weight: 600 !important; display: inline-flex !important; align-items: center; gap: 4px;"><i class="fas fa-times-circle"></i> {{ ucfirst($order->order_status) }} </span>
                @endif
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Shipping Method') }} :
              </div>
              <div class="col-lg-6" style="font-weight: 600; color: #1e293b;">
                {{ $order->shipping_method }}
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Cart Total') }} :
              </div>
              <div class="col-lg-6" style="font-weight: 600; color: #1e293b;">
                {{ textPrice($order->currency_text_position, $order->currency_code, $order->cart_total) }}
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Discount') }} :
              </div>
              <div class="col-lg-6" style="font-weight: 600; color: #10b981;">
                - @if (!empty($order->discount))
                  {{ textPrice($order->currency_text_position, $order->currency_code, $order->discount) }}
                @else
                  {{ textPrice($order->currency_text_position, $order->currency_code, 0) }}
                @endif
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Subtotal') }} :
              </div>
              <div class="col-lg-6" style="font-weight: 600; color: #1e293b;">
                {{ textPrice($order->currency_text_position, $order->currency_code, round($order->cart_total - $order->discount, 2)) }}
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Shipping Charge') }} :
              </div>
              <div class="col-lg-6" style="font-weight: 600; color: #ef4444;">
                + @if (!empty($order->shipping_charge))
                  {{ textPrice($order->currency_text_position, $order->currency_code, $order->shipping_charge) }}
                @else
                  {{ textPrice($order->currency_text_position, $order->currency_code, 0) }}
                @endif
              </div>
            </div>

            @if ($order->tax > 0)
            <div class="row mb-3">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Tax') }} ({{ is_null($order->tax_percentage) ? 0 : $order->tax_percentage }}%) :
              </div>
              <div class="col-lg-6" style="font-weight: 600; color: #ef4444;">
                + @if (!empty($order->tax))
                  {{ textPrice($order->currency_text_position, $order->currency_code, $order->tax) }}
                @else
                  {{ textPrice($order->currency_text_position, $order->currency_code, 0) }}
                @endif
              </div>
            </div>
            @endif

            <div class="row mb-3">
              <div class="col-lg-6" style="color: #64748b; font-weight: 600;">
                {{ __('Total') }} :
              </div>
              <div class="col-lg-6" style="font-weight: 700; color: #0052FF; font-size: 16px;">
                @if (!empty($order->total))
                  {{ textPrice($order->currency_text_position, $order->currency_code, $order->total) }}
                @else
                  {{ textPrice($order->currency_text_position, $order->currency_code, 0) }}
                @endif
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Payment Method') }} :
              </div>
              <div class="col-lg-6" style="font-weight: 600; color: #1e293b;">
                {{ convertUtf8($order->method) }}
              </div>
            </div>

            <div class="row mb-0">
              <div class="col-lg-6" style="color: #64748b; font-weight: 500;">
                {{ __('Order Date') }} :
              </div>
              <div class="col-lg-6" style="font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 4px;">
                <i class="far fa-calendar-alt text-muted"></i>
                {{ convertUtf8($order->created_at->format('d M Y, h:i A')) }}
              </div>
            </div>

            @if ($order->gateway_type == 'offline' && !empty($order->receipt))
            <div class="row mt-3 pt-3 border-top">
              <div class="col-12" style="color: #64748b; font-weight: 600; margin-bottom: 8px;">
                <i class="fas fa-file-invoice text-primary mr-1"></i> {{ __('Payment Receipt Proof') }} :
              </div>
              <div class="col-12 text-center">
                <a href="{{ asset('assets/front/receipt/' . $order->receipt) }}" target="_blank" title="Click to view full receipt">
                  <img src="{{ asset('assets/front/receipt/' . $order->receipt) }}" alt="Receipt Proof" 
                    style="width: 100%; max-width: 180px; height: auto; border-radius: 8px; border: 1px solid rgba(0,0,0,0.12); padding: 4px; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.05);" />
                </a>
                <p class="text-muted mt-2 mb-0" style="font-size: 11px;">{{ __('Click image to view/download full resolution') }}</p>
              </div>
            </div>
            @endif

          </div>
        </div>
      </div>
    </div>

    <!-- Shipping Details Card -->
    <div class="col-md-4">
      <div class="card card-premium">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <div class="card-icon-wrap" style="background: #eff6ff; color: #2563eb;">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Shipping Details') }}</div>
          </div>
        </div>
        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-user text-muted mr-2" style="width: 16px;"></i>{{ __('Name') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->shipping_fname . ' ' . $order->shipping_lname) }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-envelope text-muted mr-2" style="width: 16px;"></i>{{ __('Email') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->shipping_email) }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-phone text-muted mr-2" style="width: 16px;"></i>{{ __('Phone') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ $order->shipping_number }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-building text-muted mr-2" style="width: 16px;"></i>{{ __('City') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->shipping_city) }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-map text-muted mr-2" style="width: 16px;"></i>{{ __('State') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ !is_null($order->shipping_state) ? convertUtf8($order->shipping_state) : '-' }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-globe text-muted mr-2" style="width: 16px;"></i>{{ __('Country') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->shipping_country) }}</div>
            </div>

            <div class="row mb-0">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-home text-muted mr-2" style="width: 16px;"></i>{{ __('Address') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->shipping_address) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Billing Details Card -->
    <div class="col-md-4">
      <div class="card card-premium">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <div class="card-icon-wrap" style="background: #faf5ff; color: #a855f7;">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Billing Details') }}</div>
          </div>
        </div>
        <div class="card-body">
          <div class="payment-information">
            @if (!is_null(@$order->customer->username))
              <div class="row mb-3">
                <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                  <i class="fas fa-user text-muted mr-2" style="width: 16px;"></i>{{ __('Username') }} :
                </div>
                <div class="col-lg-7" style="font-weight: 600;">
                  <a target="_blank" href="{{ route('user.register.user.view', $order->customer->id) }}" style="color: #2563eb;">{{ convertUtf8(@$order->customer->username) }}</a>
                </div>
              </div>
            @endif
            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-user text-muted mr-2" style="width: 16px;"></i>{{ __('Name') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->billing_fname . ' ' . $order->billing_lname) }}</div>
            </div>
            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-envelope text-muted mr-2" style="width: 16px;"></i>{{ __('Email') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->billing_email) }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-phone text-muted mr-2" style="width: 16px;"></i>{{ __('Phone') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ $order->billing_number }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-building text-muted mr-2" style="width: 16px;"></i>{{ __('City') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->billing_city) }}</div>
            </div>
            
            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-map text-muted mr-2" style="width: 16px;"></i>{{ __('State') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ !is_null($order->billing_state) ? convertUtf8($order->billing_state) : '-' }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-globe text-muted mr-2" style="width: 16px;"></i>{{ __('Country') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->billing_country) }}</div>
            </div>

            <div class="row mb-0">
              <div class="col-lg-5 d-flex align-items-center" style="color: #64748b; font-weight: 500;">
                <i class="fas fa-home text-muted mr-2" style="width: 16px;"></i>{{ __('Address') }} :
              </div>
              <div class="col-lg-7" style="font-weight: 600; color: #1e293b;">{{ convertUtf8($order->billing_address) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">

    @php
      $shipping_gateways = App\Models\User\UserShippingGateway::where('user_id', Auth::guard('web')->user()->id)->where('status', 1)->get();
    @endphp

    @if (is_array($permissions) && in_array('Order Tracking Feature', $permissions))
    <!-- Shipment Tracking (col-md-5) -->
    <div class="col-md-5">
      <div class="card card-premium">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="card-icon-wrap" style="background: #eff6ff; color: #2563eb;">
              <i class="fas fa-truck"></i>
            </div>
            <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Shipment Tracking') }}</div>
          </div>
        </div>
        <form action="{{ route('user.item.order.tracking_update') }}" method="POST">
          @csrf
          <input type="hidden" name="order_id" value="{{ $order->id }}">
          <div class="card-body">
            <div class="form-group pt-0">
              <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('Shipping Gateway / Method') }}</label>
              <select name="shipping_gateway_keyword" class="form-control">
                <option value="">{{ __('Manual (No API)') }}</option>
                @foreach($shipping_gateways as $gateway)
                  <option value="{{ $gateway->keyword }}" @selected($order->shipping_gateway_keyword == $gateway->keyword)>{{ $gateway->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group pt-0">
              <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('Courier Partner') }}</label>
              <input type="text" name="courier_name" class="form-control" value="{{ $order->courier_name }}" placeholder="e.g. DHL, FedEx, Delhivery">
            </div>
            <div class="form-group pt-0">
              <label style="font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px;">{{ __('Tracking Number') }}</label>
              <input type="text" name="tracking_number" class="form-control" value="{{ $order->tracking_number }}" placeholder="Tracking Number">
            </div>
            @if($order->tracking_url)
            <div class="form-group pt-0 pb-0">
              <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-sm btn-link text-primary p-0">{{ __('View Tracking on Carrier Site') }}</a>
            </div>
            @endif
          </div>
          <div class="card-footer text-right" style="background: transparent; border-top: none; padding-top: 0;">
            <button type="submit" class="btn btn-premium-primary">{{ __('Save Tracking') }}</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tracking Progress (col-md-7) -->
    <div class="col-md-7">
      <div class="card card-premium">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="card-icon-wrap" style="background: #eff6ff; color: #2563eb;">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Tracking Progress') }}</div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="timeline-container">
                <div class="timeline-item">
                  <div class="timeline-marker completed">
                    <i class="fas fa-check"></i>
                  </div>
                  <div class="timeline-content">
                    <div class="timeline-title">{{ __('Order Placed') }}</div>
                    <div class="timeline-time">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                    <div class="timeline-desc">{{ __('Your order has been placed successfully.') }}</div>
                  </div>
                </div>
                
                <div class="timeline-item">
                  <div class="timeline-marker {{ $order->order_status != 'pending' ? 'completed' : 'active' }}">
                    @if($order->order_status != 'pending')
                      <i class="fas fa-check"></i>
                    @endif
                  </div>
                  <div class="timeline-content">
                    <div class="timeline-title">{{ __('Processing') }}</div>
                    <div class="timeline-time">{{ $order->order_status != 'pending' ? $order->updated_at->format('d M Y, h:i A') : '-' }}</div>
                    <div class="timeline-desc">{{ __('Your order is being prepared.') }}</div>
                  </div>
                </div>
                
                <div class="timeline-item">
                  <div class="timeline-marker {{ $order->order_status == 'completed' ? 'completed' : ($order->order_status == 'processing' ? 'active' : '') }}">
                    @if($order->order_status == 'completed')
                      <i class="fas fa-check"></i>
                    @endif
                  </div>
                  <div class="timeline-content">
                    <div class="timeline-title">{{ __('In Transit') }}</div>
                    <div class="timeline-time">{{ $order->order_status == 'completed' ? $order->updated_at->format('d M Y, h:i A') : ($order->order_status == 'processing' ? __('On the way') : '-') }}</div>
                    <div class="timeline-desc">{{ __('Your order is on the way.') }}</div>
                  </div>
                </div>
                
                <div class="timeline-item">
                  <div class="timeline-marker"></div>
                  <div class="timeline-content">
                    <div class="timeline-title">{{ __('Out for Delivery') }}</div>
                    <div class="timeline-desc">{{ __('Out for delivery soon.') }}</div>
                  </div>
                </div>
                
                <div class="timeline-item">
                  <div class="timeline-marker"></div>
                  <div class="timeline-content">
                    <div class="timeline-title">{{ __('Delivered') }}</div>
                    <div class="timeline-desc">{{ __('Package delivered to destination.') }}</div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
              <div class="map-mockup-wrapper">
                <div class="expected-delivery-badge">
                  {{ __('Expected Delivery:') }} {{ $order->created_at->addDays(3)->format('d M Y') }}
                </div>
                <svg class="map-svg" viewBox="0 0 200 150">
                  <path d="M 0,20 Q 80,40 120,20 T 200,40" fill="none" stroke="#e2e8f0" stroke-width="4"/>
                  <path d="M 30,0 Q 40,80 20,150" fill="none" stroke="#e2e8f0" stroke-width="4"/>
                  <path d="M 150,0 Q 140,80 170,150" fill="none" stroke="#e2e8f0" stroke-width="4"/>
                  <path d="M 40,90 Q 95,50 140,110" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-dasharray="3 3"/>
                  <circle cx="40" cy="90" r="5" fill="#10b981"/>
                  <circle cx="40" cy="90" r="9" fill="none" stroke="#10b981" stroke-width="1.5" opacity="0.4"/>
                  <g transform="translate(140, 110)">
                    <circle cx="0" cy="0" r="8" fill="#2563eb"/>
                    <path d="M -4,-2 L -1,-2 L 1,-4 L 4,-4 L 4,-1 L 3,1 L -4,1 Z" fill="#fff"/>
                  </g>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

    <div class="col-lg-12">
      <div class="card card-premium">
        <div class="card-header d-flex align-items-center">
          <div class="card-icon-wrap" style="background: #eff6ff; color: #2563eb;">
            <i class="fas fa-boxes"></i>
          </div>
          <h4 class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Order Item(s)') }}</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive product-list">
            <table class="table table-premium product-list-table mt-3">
              <thead>
                <tr class="border_top_1px">
                  <th>#</th>
                  <th>{{ __('Image') }}</th>
                  <th>{{ __('Name') }}</th>
                  <th class="text-center">{{ __('Price') }}</th>
                  <th class="text-center">{{ __('Qty') }}</th>
                  <th class="text-center">{{ __('Total') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->orderitems as $key => $item)
                  @php
                    $variant_total = 0;
                    $item_price = $item->price;
                    $slug = App\Models\User\UserItemContent::where([
                        ['item_id', $item->item_id],
                        ['language_id', $itemLang->id],
                    ])
                        ->pluck('slug')
                        ->first();
                  @endphp
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                      <img src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->image) }}" alt="product"
                        class="table-image" style="border-radius: 6px; width: 60px; height: auto;">
                    </td>
                    <td>
                      <a class="d-block product-title"
                        href="{{ route('front.user.productDetails', [Auth::user('web')->username, 'slug' => $slug]) }}"
                        target="_blank" style="font-weight: 600; color: #1e293b;">
                        {{ truncateString(convertUtf8($item->title), 50) }}
                      </a>
                      @php
                        $variations = json_decode($item->variations);
                      @endphp
                      @if (!is_null($variations))
                        <p class="mb-0 mt-1"><strong>{{ __('Variations') . ':' }}</strong>
                        </p>
                        <ul class="variation-list" style="list-style: none; padding-left: 0; margin-top: 4px; font-size: 12px; color: #64748b;">
                          @foreach ($variations as $k => $vitm)
                            @php
                              $name = isset($vitm->name) ? $vitm->name : '';
                              $key = is_string($k) ? $k : '';
                            @endphp
                            <li>
                              <i class="fas fa-caret-right mr-1 text-muted"></i> {{ $key }} ({{ $name }}) :
                              {{ currencyTextPrice($order->currency_id, round($vitm->price, 2)) }}
                            </li>
                          @endforeach
                        </ul>
                      @endif
                    </td>
                    <td class="text-center" style="font-weight: 500;">
                      {{ textPrice($order->currency_text_position, $order->currency_code, $item_price) }}
                    </td>
                    <td class="text-center">
                      <span class="qty-border">{{ $item->qty }}</span>
                    </td>
                    <td class="text-center" style="font-weight: 600; color: #1e293b;">
                      {{ textPrice($order->currency_text_position, $order->currency_code, round($item_price * $item->qty + $variant_total * $item->qty, 2)) }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @section('scripts')
  <script>
    function copyText(text, btn) {
      navigator.clipboard.writeText(text).then(function() {
        var icon = btn.querySelector('i');
        icon.className = 'fas fa-check text-success';
        setTimeout(function() {
          icon.className = 'far fa-copy';
        }, 1500);
      });
    }
  </script>
  @endsection
@endsection
