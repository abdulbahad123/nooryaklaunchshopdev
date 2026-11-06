<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Completed Orders History') }}</title>
  @php
    $font_family = 'DejaVu Sans, sans-serif';
    $primary_color = !empty($userBs->base_color) ? ('#' . str_replace('#', '', $userBs->base_color)) : '#1e3a8a';
    $currSymbol = '₹';
    $currPos = 'left';
    
    function fmtPrice($amt, $sym = '₹', $pos = 'left') {
        $val = number_format((float)$amt, 2, '.', ',');
        return '₹ ' . $val;
    }
  @endphp
  <style>
    @page {
      margin: 18mm 12mm;
    }
    * {
      font-family: 'DejaVu Sans', sans-serif !important;
    }
    body {
      font-family: 'DejaVu Sans', sans-serif !important;
      font-size: 11px;
      color: #1e293b;
      line-height: 1.5;
      margin: 0;
      padding: 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      vertical-align: middle;
      padding: 7px 8px;
    }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .bold { font-weight: bold; }
    
    .header-table {
      margin-bottom: 20px;
      border-bottom: 2px solid #e2e8f0;
      padding-bottom: 12px;
    }
    .report-title {
      font-size: 20px;
      font-weight: bold;
      color: {{ $primary_color }};
      letter-spacing: -0.3px;
      margin: 0 0 4px 0;
    }
    .report-subtitle {
      font-size: 11px;
      color: #64748b;
      margin: 0;
    }

    /* KPI Summary Cards */
    .kpi-table {
      margin-bottom: 18px;
    }
    .kpi-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: 10px 14px;
      text-align: center;
    }
    .kpi-label {
      font-size: 9px;
      font-weight: bold;
      text-transform: uppercase;
      color: #64748b;
      margin-bottom: 3px;
    }
    .kpi-value {
      font-size: 14px;
      font-weight: bold;
      color: #0f172a;
    }

    /* Orders Table */
    .orders-table {
      margin-top: 10px;
      border: 1px solid #cbd5e1;
    }
    .orders-table th {
      background-color: #0f172a;
      color: #ffffff;
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border: 1px solid #0f172a;
    }
    .orders-table td {
      border: 1px solid #e2e8f0;
      font-size: 10px;
    }
    .orders-table tr:nth-child(even) {
      background-color: #f8fafc;
    }
    .badge {
      display: inline-block;
      padding: 2px 7px;
      font-size: 9px;
      font-weight: bold;
      border-radius: 4px;
    }
    .badge-success {
      background-color: #dcfce7;
      color: #15803d;
    }
    .badge-completed {
      background-color: #e0e7ff;
      color: #3730a3;
    }
    .footer-total-row {
      background-color: #f1f5f9 !important;
      font-weight: bold;
      border-top: 2px solid #94a3b8;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <table class="header-table">
    <tr>
      <td style="width: 60%;">
        <h1 class="report-title">{{ $userBs->website_title ?? ($user->username ?? 'Store') }}</h1>
        <p class="report-subtitle">Completed Orders History & Revenue Summary</p>
      </td>
      <td style="width: 40%;" class="text-right">
        <div style="font-size: 10px; color: #64748b;">
          <strong>Filter:</strong> Order Status = Completed &bull; Payment = Completed<br>
          <strong>Date Range:</strong> <span style="color: #0f172a; font-weight: bold;">{{ $rangeLabel ?? 'All Time' }}</span><br>
          <strong>Generated At:</strong> {{ $generatedAt }}<br>
          <strong>Total Records:</strong> {{ count($orders) }} orders
        </div>
      </td>
    </tr>
  </table>

  <!-- KPI Metrics -->
  <table class="kpi-table" style="width: 100%;">
    <tr>
      <td style="width: 25%; padding-right: 6px;">
        <div class="kpi-card" style="border-left: 4px solid #10b981;">
          <div class="kpi-label">Total Revenue</div>
          <div class="kpi-value" style="color: #047857;">{{ fmtPrice($totalRevenue, $currSymbol, $currPos) }}</div>
        </div>
      </td>
      <td style="width: 25%; padding: 0 3px;">
        <div class="kpi-card" style="border-left: 4px solid #3b82f6;">
          <div class="kpi-label">Total Completed Orders</div>
          <div class="kpi-value" style="color: #1d4ed8;">{{ count($orders) }}</div>
        </div>
      </td>
      <td style="width: 25%; padding: 0 3px;">
        <div class="kpi-card" style="border-left: 4px solid #f59e0b;">
          <div class="kpi-label">Total GST Tax</div>
          <div class="kpi-value" style="color: #b45309;">{{ fmtPrice($totalTax, $currSymbol, $currPos) }}</div>
        </div>
      </td>
      <td style="width: 25%; padding-left: 6px;">
        <div class="kpi-card" style="border-left: 4px solid #6366f1;">
          <div class="kpi-label">Total Shipping</div>
          <div class="kpi-value" style="color: #4338ca;">{{ fmtPrice($totalShipping, $currSymbol, $currPos) }}</div>
        </div>
      </td>
    </tr>
  </table>

  <!-- Orders Data Table -->
  <table class="orders-table">
    <thead>
      <tr>
        <th class="text-center" style="width: 4%;">#</th>
        <th class="text-left" style="width: 14%;">Order #</th>
        <th class="text-left" style="width: 11%;">Date</th>
        <th class="text-left" style="width: 17%;">Customer Name</th>
        <th class="text-left" style="width: 11%;">Gateway</th>
        <th class="text-right" style="width: 9%;">Subtotal</th>
        <th class="text-right" style="width: 9%;">GST Tax</th>
        <th class="text-right" style="width: 8%;">Shipping</th>
        <th class="text-right" style="width: 10%;">Total</th>
        <th class="text-center" style="width: 7%;">Status</th>
      </tr>
    </thead>
    <tbody>
      @php
        $sumSubtotal = 0;
        $sumTax = 0;
        $sumShipping = 0;
        $sumTotal = 0;
      @endphp
      @foreach ($orders as $index => $order)
        @php
          $subtotal = $order->total - ($order->tax ?? 0) - ($order->shipping_charge ?? 0);
          $sumSubtotal += $subtotal;
          $sumTax += ($order->tax ?? 0);
          $sumShipping += ($order->shipping_charge ?? 0);
          $sumTotal += $order->total;
          
          $custName = !empty($order->billing_fname) 
              ? ($order->billing_fname . ' ' . ($order->billing_lname ?? ''))
              : (!empty($order->shipping_fname) ? $order->shipping_fname : 'Customer');
        @endphp
        <tr>
          <td class="text-center">{{ $index + 1 }}</td>
          <td class="bold text-left" style="color: #0f172a;">#{{ $order->order_number }}</td>
          <td class="text-left">{{ \Carbon\Carbon::parse($order->created_at)->format('d-M-Y') }}</td>
          <td class="text-left">{{ $custName }}</td>
          <td class="text-left">{{ $order->method ?? 'Online' }}</td>
          <td class="text-right">{{ fmtPrice($subtotal, $currSymbol, $currPos) }}</td>
          <td class="text-right">{{ fmtPrice($order->tax ?? 0, $currSymbol, $currPos) }}</td>
          <td class="text-right">{{ fmtPrice($order->shipping_charge ?? 0, $currSymbol, $currPos) }}</td>
          <td class="text-right bold" style="color: #047857;">{{ fmtPrice($order->total, $currSymbol, $currPos) }}</td>
          <td class="text-center">
            <span class="badge badge-success">Completed</span>
          </td>
        </tr>
      @endforeach
      <!-- Grand Totals Row -->
      <tr class="footer-total-row">
        <td colspan="5" class="text-right bold" style="font-size: 11px;">Grand Totals:</td>
        <td class="text-right bold">{{ fmtPrice($sumSubtotal, $currSymbol, $currPos) }}</td>
        <td class="text-right bold">{{ fmtPrice($sumTax, $currSymbol, $currPos) }}</td>
        <td class="text-right bold">{{ fmtPrice($sumShipping, $currSymbol, $currPos) }}</td>
        <td class="text-right bold" style="color: #047857; font-size: 11px;">{{ fmtPrice($sumTotal, $currSymbol, $currPos) }}</td>
        <td class="text-center bold">{{ count($orders) }} Orders</td>
      </tr>
    </tbody>
  </table>

  <!-- Footer note -->
  <div style="margin-top: 15px; text-align: center; font-size: 9px; color: #94a3b8;">
    This is an automated order history export generated from {{ $userBs->website_title ?? 'LaunchShop / EcomBuilder' }} &bull; Confirmed Paid Orders Only
  </div>

</body>
</html>

