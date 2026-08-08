@extends('user-front.layout')
@section('breadcrumb_title', $keywords['Order Tracking'] ?? __('Order Tracking'))
@section('page-title', $keywords['Order Tracking'] ?? __('Order Tracking'))

@section('content')
<!-- Order Tracking Page -->
<section class="user-dashboard pt-100 pb-70">
  <div class="container">
    <div class="row gx-xl-5">
      @includeIf('user-front.customer.side-navbar')

      <div class="col-lg-9">

        {{-- ===========================
             STEP 1: Order List
             =========================== --}}
        @if(!isset($trackOrder))
        <div class="account-info radius-md mb-30">
          <div class="title">
            <h3>{{ $keywords['Order Tracking'] ?? __('Order Tracking') }}</h3>
          </div>

          {{-- Empty state --}}
          @if($orders->count() == 0)
          <div class="ot-empty-state text-center py-5">
            <div class="ot-empty-icon mb-3">
              <i class="fal fa-box-open" style="font-size:56px;color:#e2e8f0;"></i>
            </div>
            <h5 class="mb-1" style="color:#64748b;">{{ $keywords['No Orders'] ?? __('No Orders Found') }}</h5>
            <p style="color:#94a3b8;font-size:14px;">{{ $keywords['You have not placed any orders yet.'] ?? __('You have not placed any orders yet.') }}</p>
          </div>
          @else
          <div class="ot-order-list">
            @foreach($orders as $order)
            @php
              $statusClass = 'ot-status-pending';
              $statusIcon  = 'fa-clock';
              if($order->order_status == 'processing') { $statusClass = 'ot-status-processing'; $statusIcon = 'fa-sync-alt'; }
              if($order->order_status == 'completed')  { $statusClass = 'ot-status-completed';  $statusIcon = 'fa-check-circle'; }
              if($order->order_status == 'rejected')   { $statusClass = 'ot-status-rejected';   $statusIcon = 'fa-times-circle'; }
            @endphp
            <a href="{{ route('customer.order-tracking', ['order_id' => $order->id, getParam()]) }}"
               class="ot-order-row text-decoration-none d-block">
              <div class="ot-order-card d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                  <div class="ot-order-icon-box">
                    <i class="fal fa-box"></i>
                  </div>
                  <div>
                    <div class="ot-order-number">{{ $order->order_number }}</div>
                    <div class="ot-order-date">{{ $order->created_at->format('d M, Y') }}</div>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                  <div class="text-end">
                    <div class="ot-order-amount">{{ userSymbolPrice($order->total, $order->currency_position, $order->currency_sign) }}</div>
                    <div class="ot-order-items-count">{{ $order->orderitems->count() }} {{ $keywords['Items'] ?? __('Items') }}</div>
                  </div>
                  <span class="ot-status-badge {{ $statusClass }}">
                    <i class="fas {{ $statusIcon }} me-1"></i>
                    {{ ucfirst($order->order_status) }}
                  </span>
                  <i class="fal fa-chevron-right ot-chevron"></i>
                </div>
              </div>
            </a>
            @endforeach
          </div>

          {{-- Pagination --}}
          @if($orders->hasPages())
          <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
          </div>
          @endif
          @endif
        </div>

        {{-- ===========================
             STEP 2: Single Order Tracking Detail
             =========================== --}}
        @else
        @php
          $data = $trackOrder;
          $statusSteps = [
            ['key' => 'pending',    'label' => $keywords['Pending'] ?? __('Pending'),    'icon' => 'fa-clipboard-list'],
            ['key' => 'processing', 'label' => $keywords['Processing'] ?? __('Processing'), 'icon' => 'fa-sync-alt'],
            ['key' => 'completed',  'label' => $keywords['Completed'] ?? __('Completed'),  'icon' => 'fa-check-circle'],
          ];
          $rejectedMode = $data->order_status == 'rejected';
          if($rejectedMode) {
            $statusSteps[2] = ['key' => 'rejected', 'label' => $keywords['Rejected'] ?? __('Rejected'), 'icon' => 'fa-times-circle'];
          }

          $currentStep = 0;
          foreach($statusSteps as $idx => $step) {
            if($data->order_status == $step['key']) { $currentStep = $idx; break; }
          }

          // Timeline events based on status
          $events = [];
          if(in_array($data->order_status, ['completed','processing','rejected'])) {
            $events[] = ['title' => $keywords['Order Placed'] ?? __('Order Placed'), 'desc' => $keywords['Your order was placed successfully.'] ?? __('Your order was placed successfully.'), 'time' => $data->created_at->format('M d, Y · h:i A'), 'done' => true];
          } else {
            $events[] = ['title' => $keywords['Order Placed'] ?? __('Order Placed'), 'desc' => $keywords['Your order was placed successfully.'] ?? __('Your order was placed successfully.'), 'time' => $data->created_at->format('M d, Y · h:i A'), 'done' => true];
          }
          if(in_array($data->order_status, ['processing','completed'])) {
            $events[] = ['title' => $keywords['Order Processing'] ?? __('Order Processing'), 'desc' => $keywords['Your order is being processed.'] ?? __('Your order is being processed.'), 'time' => $data->updated_at->format('M d, Y · h:i A'), 'done' => true];
          }
          if($rejectedMode) {
            $events[] = ['title' => $keywords['Order Rejected'] ?? __('Order Rejected'), 'desc' => $keywords['Your order has been rejected.'] ?? __('Your order has been rejected.'), 'time' => $data->updated_at->format('M d, Y · h:i A'), 'done' => true, 'rejected' => true];
          }
          if($data->order_status == 'completed') {
            $events[] = ['title' => $keywords['Order Completed'] ?? __('Order Completed'), 'desc' => $keywords['Your order has been completed.'] ?? __('Your order has been completed.'), 'time' => $data->updated_at->format('M d, Y · h:i A'), 'done' => true];
          }
          $events = array_reverse($events);
        @endphp

        {{-- Back button --}}
        <div class="mb-3">
          <a href="{{ route('customer.order-tracking', getParam()) }}" class="ot-back-btn">
            <i class="fal fa-arrow-left me-2"></i>{{ $keywords['All Orders'] ?? __('All Orders') }}
          </a>
        </div>

        {{-- Order Header Card --}}
        <div class="ot-detail-card mb-4">
          <div class="d-flex align-items-center gap-3 flex-wrap justify-content-between">
            <div class="d-flex align-items-center gap-3">
              <div class="ot-order-icon-box ot-order-icon-box--lg">
                <i class="fal fa-box"></i>
              </div>
              <div>
                <div class="ot-detail-order-number">{{ $keywords['Order'] ?? __('Order') }} #{{ $data->order_number }}</div>
                <div class="ot-detail-order-date">{{ $keywords['Placed on'] ?? __('Placed on') }} {{ $data->created_at->format('d M Y, h:i A') }}</div>
                @php
                  $pStatusClass = $data->payment_status == 'Completed' ? 'ot-pay-completed' : 'ot-pay-pending';
                @endphp
                <span class="ot-pay-badge {{ $pStatusClass }} mt-1 d-inline-block">{{ $data->payment_status }}</span>
              </div>
            </div>
            <a href="{{ asset('assets/front/invoices/' . $data->invoice_number) }}"
               download="{{ $data->invoice_number }}.pdf"
               class="ot-invoice-btn">
              <i class="fas fa-download me-1"></i>{{ $keywords['Invoice'] ?? __('Invoice') }}
            </a>
          </div>
        </div>

        {{-- Progress Steps --}}
        <div class="ot-progress-card mb-4">
          <div class="ot-steps-track">
            @foreach($statusSteps as $idx => $step)
            @php
              $isPast   = $idx < $currentStep;
              $isCurrent = $idx == $currentStep;
              $isFuture = $idx > $currentStep;
              $stepClass = $isPast ? 'ot-step-done' : ($isCurrent ? 'ot-step-active' : 'ot-step-future');
              $connClass = $isPast ? 'ot-conn-done' : 'ot-conn-future';
              if($rejectedMode && $idx == 2) { $stepClass .= ' ot-step-rejected'; }
            @endphp
            <div class="ot-step {{ $stepClass }}">
              <div class="ot-step-circle">
                @if($isPast)
                  <i class="fas fa-check"></i>
                @elseif($isCurrent && $rejectedMode && $idx == 2)
                  <i class="fas fa-times"></i>
                @else
                  <i class="fas {{ $step['icon'] }}"></i>
                @endif
              </div>
              <div class="ot-step-label">{{ $step['label'] }}</div>
            </div>
            @if(!$loop->last)
            <div class="ot-connector {{ $connClass }}"></div>
            @endif
            @endforeach
          </div>
        </div>

        {{-- Timeline Events --}}
        <div class="ot-detail-card mb-4">
          <div class="ot-section-title mb-3">
            <i class="fal fa-history me-2" style="color:#ff5a2c;"></i>
            {{ $keywords['Order Activity'] ?? __('Order Activity') }}
          </div>
          <div class="ot-timeline">
            @foreach($events as $event)
            <div class="ot-timeline-item {{ isset($event['rejected']) && $event['rejected'] ? 'ot-timeline-rejected' : ($event['done'] ? 'ot-timeline-done' : 'ot-timeline-future') }}">
              <div class="ot-tl-dot"></div>
              <div class="ot-tl-content">
                <div class="ot-tl-title">{{ $event['title'] }}</div>
                <div class="ot-tl-desc">{{ $event['desc'] }}</div>
                <div class="ot-tl-time">{{ $event['time'] }}</div>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Order Items --}}
        <div class="ot-detail-card mb-4">
          <div class="ot-section-title mb-3">
            <i class="fal fa-shopping-bag me-2" style="color:#ff5a2c;"></i>
            {{ $keywords['Ordered Products'] ?? __('Ordered Products') }}
          </div>
          <div class="ot-items-list">
            @foreach($data->orderitems as $item)
            @php
              $itemcontent = App\Models\User\UserItemContent::where('item_id', $item->item_id)
                  ->where('language_id', $currentLanguage->id)
                  ->first();
            @endphp
            <div class="ot-item-row d-flex align-items-center gap-3 flex-wrap">
              <div class="ot-item-img-wrap flex-shrink-0">
                <img src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->item->thumbnail) }}"
                     alt="{{ $item->title }}" class="ot-item-img">
              </div>
              <div class="flex-grow-1">
                <div class="ot-item-name">
                  @if($itemcontent && $itemcontent->slug)
                    <a href="{{ route('front.user.productDetails', ['slug' => $itemcontent->slug, getParam()]) }}" target="_blank">
                      {{ $item->title }}
                    </a>
                  @else
                    {{ $item->title }}
                  @endif
                </div>
                <div class="ot-item-meta">
                  {{ $keywords['Qty'] ?? __('Qty') }}: {{ $item->qty }}
                  &nbsp;·&nbsp;
                  {{ userSymbolPrice($item->price, $data->currency_position, $data->currency_sign) }} {{ $keywords['each'] ?? __('each') }}
                </div>
              </div>
              <div class="ot-item-total ms-auto text-end">
                {{ userSymbolPrice($item->price * $item->qty, $data->currency_position, $data->currency_sign) }}
              </div>
            </div>
            @endforeach

            {{-- Order totals --}}
            <div class="ot-totals mt-3">
              <div class="ot-total-row d-flex justify-content-between">
                <span>{{ $keywords['Subtotal'] ?? __('Subtotal') }}</span>
                <span>{{ userSymbolPrice($data->cart_total, $data->currency_position, $data->currency_sign) }}</span>
              </div>
              @if($data->discount > 0)
              <div class="ot-total-row d-flex justify-content-between">
                <span>{{ $keywords['Discount'] ?? __('Discount') }}</span>
                <span class="text-success">- {{ userSymbolPrice($data->discount, $data->currency_position, $data->currency_sign) }}</span>
              </div>
              @endif
              <div class="ot-total-row d-flex justify-content-between">
                <span>{{ $keywords['Shipping'] ?? __('Shipping') }}</span>
                <span>{{ userSymbolPrice($data->shipping_charge, $data->currency_position, $data->currency_sign) }}</span>
              </div>
              @if($data->tax > 0)
              <div class="ot-total-row d-flex justify-content-between">
                <span>{{ $keywords['Tax'] ?? __('Tax') }} ({{ $data->tax_percentage ?? 0 }}%)</span>
                <span>{{ userSymbolPrice($data->tax, $data->currency_position, $data->currency_sign) }}</span>
              </div>
              @endif
              <div class="ot-total-row ot-total-grand d-flex justify-content-between">
                <span>{{ $keywords['Total'] ?? __('Total') }}</span>
                <span>{{ userSymbolPrice($data->total, $data->currency_position, $data->currency_sign) }}</span>
              </div>
            </div>
          </div>
        </div>

        {{-- Shipping Address --}}
        <div class="ot-detail-card mb-4">
          <div class="ot-section-title mb-3">
            <i class="fal fa-map-marker-alt me-2" style="color:#ff5a2c;"></i>
            {{ $keywords['Shipping Address'] ?? __('Shipping Address') }}
          </div>
          <div class="ot-address-block">
            <div class="ot-address-line">{{ $data->shipping_fname }} {{ $data->shipping_lname }}</div>
            <div class="ot-address-line text-muted">{{ $data->shipping_address }}</div>
            <div class="ot-address-line text-muted">{{ $data->shipping_city }}, {{ $data->shipping_country }}</div>
            <div class="ot-address-line text-muted">{{ $data->shipping_number }}</div>
            @if(!empty($data->tracking_number))
            <div class="ot-tracking-chip mt-2">
              <i class="fas fa-barcode me-1"></i>
              {{ $keywords['Tracking Number'] ?? __('Tracking Number') }}:
              @if(!empty($data->tracking_url))
                <a href="{{ $data->tracking_url }}" target="_blank">{{ $data->tracking_number }}</a>
              @else
                {{ $data->tracking_number }}
              @endif
            </div>
            @endif
          </div>
        </div>

        @endif {{-- end trackOrder --}}

      </div>{{-- col-lg-9 --}}
    </div>{{-- row --}}
  </div>{{-- container --}}
</section>

<style>
/* ============================================================
   ORDER TRACKING PAGE STYLES
   ============================================================ */
.ot-order-list { padding: 0 24px 24px; }

.ot-order-row { margin-bottom: 12px; }
.ot-order-card {
  border: 1px solid #e9ecef;
  border-radius: 14px;
  padding: 16px 20px;
  background: #fff;
  transition: all 0.22s ease;
}
.ot-order-card:hover {
  border-color: #ff5a2c;
  box-shadow: 0 4px 18px rgba(255,90,44,0.10);
  transform: translateY(-2px);
}

.ot-order-icon-box {
  width: 46px;
  height: 46px;
  border-radius: 12px;
  background: #fff3ee;
  color: #ff5a2c;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  flex-shrink: 0;
  border: 1px solid rgba(255,90,44,0.15);
}
.ot-order-icon-box--lg { width: 54px; height: 54px; font-size: 22px; border-radius: 14px; }

.ot-order-number { font-size: 14px; font-weight: 700; color: #1e293b; }
.ot-order-date   { font-size: 12px; color: #94a3b8; margin-top: 2px; }
.ot-order-amount { font-size: 15px; font-weight: 700; color: #1e293b; }
.ot-order-items-count { font-size: 12px; color: #94a3b8; }

.ot-status-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 12px;
  border-radius: 50px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}
.ot-status-pending    { background: #fff8e1; color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
.ot-status-processing { background: #e0f0ff; color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); }
.ot-status-completed  { background: #dcfce7; color: #16a34a; border: 1px solid rgba(22,163,74,0.2); }
.ot-status-rejected   { background: #fee2e2; color: #dc2626; border: 1px solid rgba(220,38,38,0.2); }

.ot-chevron { color: #cbd5e1; font-size: 13px; }

/* Empty state */
.ot-empty-state { padding: 20px 24px 40px; }

/* Back button */
.ot-back-btn {
  display: inline-flex;
  align-items: center;
  font-size: 14px;
  font-weight: 600;
  color: #ff5a2c;
  text-decoration: none;
  transition: opacity 0.2s;
}
.ot-back-btn:hover { opacity: 0.75; color: #ff5a2c; }

/* Detail Cards */
.ot-detail-card {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 16px;
  padding: 24px;
}
.ot-detail-order-number { font-size: 16px; font-weight: 700; color: #1e293b; }
.ot-detail-order-date   { font-size: 13px; color: #64748b; margin-top: 3px; }
.ot-pay-badge {
  display: inline-flex;
  align-items: center;
  padding: 3px 10px;
  border-radius: 50px;
  font-size: 11px;
  font-weight: 600;
}
.ot-pay-completed { background: #dcfce7; color: #16a34a; }
.ot-pay-pending   { background: #fff8e1; color: #f59e0b; }

.ot-invoice-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: linear-gradient(90deg,#ff5a2c,#ff7a54);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 10px 18px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: opacity 0.2s;
  white-space: nowrap;
}
.ot-invoice-btn:hover { opacity: 0.88; color: #fff; }

/* ============================================================
   PROGRESS STEPS TRACKER
   ============================================================ */
.ot-progress-card { padding: 28px 24px; }

.ot-steps-track {
  display: flex;
  align-items: flex-start;
  justify-content: center;
  gap: 0;
}

.ot-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  flex: 0 0 auto;
  min-width: 80px;
}

.ot-step-circle {
  width: 46px;
  height: 46px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  border: 2px solid;
  background: #f1f5f9;
  color: #cbd5e1;
  border-color: #e2e8f0;
  transition: all 0.3s ease;
  position: relative;
  z-index: 2;
}

.ot-step-label {
  font-size: 12px;
  font-weight: 600;
  color: #94a3b8;
  margin-top: 8px;
  text-align: center;
  white-space: nowrap;
}

/* Done step */
.ot-step-done .ot-step-circle {
  background: #dcfce7;
  color: #16a34a;
  border-color: #16a34a;
}
.ot-step-done .ot-step-label { color: #16a34a; }

/* Current / Active step */
.ot-step-active .ot-step-circle {
  background: #ff5a2c;
  color: #fff;
  border-color: #ff5a2c;
  box-shadow: 0 0 0 5px rgba(255,90,44,0.18);
}
.ot-step-active .ot-step-label { color: #ff5a2c; font-weight: 700; }

/* Rejected step */
.ot-step-rejected .ot-step-circle {
  background: #fee2e2;
  color: #dc2626;
  border-color: #dc2626;
  box-shadow: 0 0 0 5px rgba(220,38,38,0.13);
}
.ot-step-rejected .ot-step-label { color: #dc2626; }

/* Connector line */
.ot-connector {
  flex: 1;
  height: 2px;
  margin-top: 22px;
  min-width: 40px;
  max-width: 120px;
}
.ot-conn-done   { background: #16a34a; }
.ot-conn-future { background: #e2e8f0; }

/* ============================================================
   TIMELINE
   ============================================================ */
.ot-section-title {
  font-size: 14px;
  font-weight: 700;
  color: #1e293b;
  display: flex;
  align-items: center;
  border-bottom: 1px solid #f1f5f9;
  padding-bottom: 12px;
}

.ot-timeline { position: relative; padding-left: 24px; }
.ot-timeline::before {
  content: '';
  position: absolute;
  left: 8px;
  top: 8px;
  bottom: 8px;
  width: 2px;
  background: #e2e8f0;
}

.ot-timeline-item {
  position: relative;
  padding-left: 24px;
  margin-bottom: 22px;
}
.ot-timeline-item:last-child { margin-bottom: 0; }

.ot-tl-dot {
  position: absolute;
  left: -28px;
  top: 5px;
  width: 14px;
  height: 14px;
  border-radius: 50%;
  border: 2px solid;
  background: #fff;
  z-index: 2;
}
.ot-timeline-done     .ot-tl-dot { border-color: #16a34a; background: #16a34a; }
.ot-timeline-rejected .ot-tl-dot { border-color: #dc2626; background: #dc2626; }
.ot-timeline-future   .ot-tl-dot { border-color: #cbd5e1; background: #f1f5f9; }

.ot-tl-title { font-size: 14px; font-weight: 700; color: #1e293b; }
.ot-tl-desc  { font-size: 13px; color: #64748b; margin-top: 2px; }
.ot-tl-time  { font-size: 11px; color: #94a3b8; margin-top: 4px; }

/* ============================================================
   ORDER ITEMS
   ============================================================ */
.ot-items-list { }
.ot-item-row {
  padding: 14px 0;
  border-bottom: 1px solid #f1f5f9;
}
.ot-item-row:last-of-type { border-bottom: none; }

.ot-item-img-wrap {
  width: 60px; height: 60px;
  border-radius: 10px;
  overflow: hidden;
  background: #f8fafc;
  border: 1px solid #e9ecef;
}
.ot-item-img { width: 100%; height: 100%; object-fit: cover; }
.ot-item-name { font-size: 14px; font-weight: 600; color: #1e293b; }
.ot-item-name a { color: #1e293b; text-decoration: none; }
.ot-item-name a:hover { color: #ff5a2c; }
.ot-item-meta { font-size: 12px; color: #94a3b8; margin-top: 3px; }
.ot-item-total { font-size: 15px; font-weight: 700; color: #1e293b; white-space: nowrap; }

/* Totals */
.ot-totals { border-top: 1px solid #f1f5f9; padding-top: 12px; }
.ot-total-row { padding: 6px 0; font-size: 13px; color: #64748b; }
.ot-total-grand {
  font-size: 15px;
  font-weight: 700;
  color: #1e293b;
  border-top: 1px solid #e2e8f0;
  margin-top: 6px;
  padding-top: 10px;
}

/* Address */
.ot-address-block { }
.ot-address-line { font-size: 13px; color: #334155; line-height: 1.7; }
.ot-tracking-chip {
  display: inline-flex;
  align-items: center;
  background: #fff3ee;
  color: #ff5a2c;
  border: 1px solid rgba(255,90,44,0.2);
  border-radius: 6px;
  padding: 5px 12px;
  font-size: 12px;
  font-weight: 600;
}
.ot-tracking-chip a { color: #ff5a2c; text-decoration: underline; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 767px) {
  .ot-order-list { padding: 0 12px 16px; }
  .ot-detail-card { padding: 16px; }
  .ot-progress-card { padding: 20px 12px; }

  .ot-steps-track {
    justify-content: space-between;
    gap: 0;
  }
  .ot-connector { min-width: 20px; max-width: 60px; }
  .ot-step { min-width: 60px; }
  .ot-step-label { font-size: 10px; }
  .ot-step-circle { width: 38px; height: 38px; font-size: 13px; }
  .ot-connector { margin-top: 18px; }

  .ot-item-img-wrap { width: 48px; height: 48px; }
  .ot-order-card { padding: 12px 14px; }

  .ot-detail-order-number { font-size: 14px; }
  .ot-invoice-btn { padding: 8px 14px; font-size: 12px; }
}

@media (max-width: 480px) {
  .ot-step-label { display: none; }
  .ot-step-circle { width: 34px; height: 34px; font-size: 12px; }
  .ot-connector { min-width: 14px; margin-top: 16px; }
}
</style>
@endsection
