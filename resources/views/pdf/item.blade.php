@php
  $language = App\Models\User\Language::where([
      ['user_id', $user->id],
      ['code', session()->get('user_lang_' . $user->username)],
  ])->first();
  if (empty($language)) {
      $language = App\Models\User\Language::where([['user_id', $user->id], ['is_default', 1]])->first();
  }
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ $language->rtl == 1 ? 'rtl' : '' }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Invoice') }}</title>
  @php
    $font_family = 'DejaVu Sans, sans-serif';
    $primary_color = '#' . str_replace('#', '', $userBs->base_color);
    if (!is_null(getUserNullCheck())) {
        $keywords = App\Http\Helpers\Common::get_keywords($user->id);
    }
  @endphp

  <style>
    body {
      font-family: {{ $font_family }} !important;
      font-size: 13px;
      color: #334155;
      line-height: 1.6;
      margin: 0;
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td {
      vertical-align: top;
    }

    /* Utilities */
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .text-white { color: #ffffff !important; }
    .bold { font-weight: bold; }
    
    .invoice-title {
      font-size: 32px;
      font-weight: bold;
      color: {{ $primary_color }};
      line-height: 1;
      text-transform: uppercase;
    }

    /* Layout structure */
    .header-table {
      margin-bottom: 25px;
    }

    .accent-bar {
      height: 4px;
      background-color: {{ $primary_color }};
      margin-bottom: 30px;
    }

    .meta-card {
      background-color: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 15px 20px;
      margin-bottom: 35px;
    }

    .meta-table td {
      font-size: 12px;
      color: #64748b;
    }

    .meta-table td strong {
      color: #1e293b;
    }

    .info-section {
      margin-bottom: 40px;
    }

    .info-title {
      font-size: 14px;
      font-weight: bold;
      color: {{ $primary_color }};
      border-bottom: 2px solid #f1f5f9;
      padding-bottom: 6px;
      margin-bottom: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .info-text {
      font-size: 13px;
      color: #475569;
      margin-bottom: 5px;
    }

    /* Items Table */
    .items-table {
      margin-top: 10px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      overflow: hidden;
    }

    .items-table thead {
      background-color: {{ $primary_color }};
    }

    .items-table th {
      color: #ffffff;
      font-weight: bold;
      font-size: 12px;
      text-transform: uppercase;
      padding: 12px 15px;
      border: none;
    }

    .items-table td {
      padding: 14px 15px;
      border-bottom: 1px solid #e2e8f0;
      color: #334155;
      font-size: 13px;
    }

    .items-table tr:last-child td {
      border-bottom: none;
    }

    /* Summary Footer */
    .summary-section {
      margin-top: 25px;
    }

    .summary-table {
      width: 280px;
      float: right;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      overflow: hidden;
    }

    .summary-table td {
      padding: 8px 15px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 12px;
    }

    .summary-table tr:last-child td {
      border-bottom: none;
    }

    .summary-total {
      background-color: #f8fafc;
      font-weight: bold;
      color: #0f172a;
    }

    .summary-total td {
      font-size: 13px;
      padding: 12px 15px;
    }

    /* Regards */
    .regards-section {
      margin-top: 60px;
      clear: both;
    }

    .regards-text {
      font-size: 13px;
      color: #64748b;
    }

    .regards-brand {
      font-size: 16px;
      font-weight: bold;
      color: #0f172a;
      margin-top: 5px;
    }
    
    .item-var-info {
      font-size: 11px;
      color: #64748b;
      margin-top: 4px;
      line-height: 1.4;
    }
  </style>
</head>

<body dir="{{ $language->rtl == 1 ? 'rtl' : '' }}">
  <div class="main">
    
    <!-- HEADER -->
    <table class="header-table">
      <tr>
        <td>
          @if ($userBs->logo)
            <img src="{{ asset('/assets/front/img/user/' . $userBs->logo) }}" height="42" style="display: block; border: 0;">
          @else
            <img src="{{ asset('assets/admin/img/noimage.jpg') }}" height="42" style="display: block; border: 0;">
          @endif
        </td>
        <td class="text-right">
          <div class="invoice-title">{{ $keywords['INVOICE'] ?? __('INVOICE') }}</div>
        </td>
      </tr>
    </table>

    <!-- ACCENT BAR -->
    <div class="accent-bar"></div>

    <!-- METADATA CARD -->
    <div class="meta-card">
      <table class="meta-table">
        <tr>
          <td width="35%">
            <strong>{{ $keywords['Payment Method'] ?? __('Payment Method') }}:</strong>
            <span>{{ $keywords[$order->method] ?? $order->method }}</span>
          </td>
          <td width="35%" class="text-center">
            <strong>{{ $keywords['Invoice No'] ?? __('Invoice No') }}:</strong>
            <span>#{{ $order->order_number }}</span>
          </td>
          <td width="30%" class="text-right">
            <strong>{{ $keywords['Date'] ?? __('Date') }}:</strong>
            <span>{{ \Carbon\Carbon::parse($order->created_at)->locale('en')->isoFormat('Do MMMM YYYY') }}</span>
          </td>
        </tr>
      </table>
    </div>

    <!-- BILL TO & ORDER DETAILS -->
    <table class="info-section">
      <tr>
        <td width="48%">
          <div class="info-title">{{ $keywords['Bill to'] ?? __('Bill to') }}</div>
          <div class="info-text">
            <strong>{{ $keywords['Name'] ?? __('Name') }}:</strong> {{ ucfirst($order->billing_fname) }} {{ ucfirst($order->billing_lname) }}
          </div>
          <div class="info-text">
            <strong>{{ $keywords['Address'] ?? __('Address') }}:</strong> {{ $order->billing_address }}
          </div>
          <div class="info-text">
            <strong>{{ $keywords['City'] ?? __('City') }}:</strong> {{ $order->billing_city }}@if(!is_null($order->billing_state)), {{ $order->billing_state }}@endif
          </div>
          <div class="info-text">
            <strong>{{ $keywords['Country'] ?? __('Country') }}:</strong> {{ $order->billing_country }}
          </div>
          <div class="info-text">
            <strong>{{ $keywords['Email'] ?? __('Email') }}:</strong> {{ $order->billing_email }}
          </div>
        </td>
        <td width="4%"></td>
        <td width="48%">
          <div class="info-title">{{ $keywords['Order Details'] ?? __('Order Details') }}</div>
          @if (!is_null($order->discount))
            <div class="info-text">
              <strong>{{ $keywords['Discount'] ?? __('Discount') }}:</strong> {{ currencyTextPrice($order->currency_id, $order->discount) }}
            </div>
          @endif
          @if ($order->tax > 0)
          <div class="info-text">
            <strong>{{ $keywords['Tax'] ?? __('Tax') }}:</strong> {{ currencyTextPrice($order->currency_id, $order->tax) }}
          </div>
          @endif
          <div class="info-text">
            <strong>{{ $keywords['Paid Amount'] ?? __('Paid Amount') }}:</strong> {{ currencyTextPrice($order->currency_id, $order->total) }}
          </div>
          <div class="info-text">
            <strong>{{ $keywords['Payment Status'] ?? __('Payment Status') }}:</strong> 
            <span style="color: {{ strtolower($order->payment_status) == 'pending' ? '#f59e0b' : '#10b981' }}; font-weight: bold;">
              {{ $keywords[$order->payment_status] ?? $order->payment_status }}
            </span>
          </div>
          <div class="info-text">
            <strong>{{ $keywords['Order Status'] ?? __('Order Status') }}:</strong>
            <span>{{ $keywords[ucfirst($order->order_status)] ?? ucfirst($order->order_status) }}</span>
          </div>
        </td>
      </tr>
    </table>

    <!-- ITEMS TABLE -->
    <table class="items-table">
      <thead>
        <tr>
          <th class="text-left" width="60%">{{ $keywords['Title'] ?? __('Title') }}</th>
          <th class="text-center" width="20%">{{ $keywords['Quantity'] ?? __('Quantity') }}</th>
          <th class="text-right" width="20%">{{ $keywords['Price'] ?? __('Price') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->orderitems as $item)
          <tr>
            <td>
              <div class="bold">{{ $item->title }}</div>
              @php
                $variations = json_decode($item->variations);
              @endphp
              @if (!is_null($variations))
                <div class="item-var-info">
                  @foreach ($variations as $k => $vitm)
                    @php
                      $name = isset($vitm->name) ? $vitm->name : '';
                      $key = is_string($k) ? $k : '';
                    @endphp
                    <div>
                      <span>{{ $name }} ({{ $key }}) :</span>
                      <span>{{ currencyTextPrice($order->currency_id, round($vitm->price, 2)) }}</span>
                    </div>
                  @endforeach
                </div>
              @endif
            </td>
            <td class="text-center">{{ $item->qty }}</td>
            <td class="text-right bold">{{ currencyTextPrice($order->currency_id, round($item->price, 2)) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- SUMMARY SECTION -->
    <div class="summary-section">
      <table class="summary-table">
        <tr>
          <td>{{ $keywords['Subtotal'] ?? __('Subtotal') }}</td>
          <td class="text-right bold">{{ currencyTextPrice($order->currency_id, $order->cart_total) }}</td>
        </tr>
        @if ($order->tax > 0)
        <tr>
          <td>{{ $keywords['Tax'] ?? __('Tax') }}</td>
          <td class="text-right bold">{{ currencyTextPrice($order->currency_id, $order->tax) }}</td>
        </tr>
        @endif
        <tr>
          <td>{{ $keywords['Shipping Charge'] ?? __('Shipping Charge') }}</td>
          <td class="text-right bold">{{ currencyTextPrice($order->currency_id, $order->shipping_charge) }}</td>
        </tr>
        <tr class="summary-total" style="background-color: {{ $primary_color }}; color: #ffffff;">
          <td style="color: #ffffff; font-weight: bold;">{{ $keywords['Paid Amount'] ?? __('Paid Amount') }}</td>
          <td class="text-right bold" style="color: #ffffff; font-weight: bold;">
            {{ currencyTextPrice($order->currency_id, $order->total) }}
          </td>
        </tr>
      </table>
    </div>

    <!-- REGARDS -->
    <table class="regards-section">
      <tr>
        <td class="text-right">
          <div class="regards-text">{{ $keywords['Thanks & Regards'] ?? __('Thanks & Regards') }},</div>
          <div class="regards-brand">
            @php
              $website_title = $user->shop_name ?? $user->username;
            @endphp
            {{ $website_title }}
          </div>
        </td>
      </tr>
    </table>

  </div>
</body>

</html>
