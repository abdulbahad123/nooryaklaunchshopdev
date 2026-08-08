@php
  if (session()->has('lang')) {
      $language = App\Models\Language::where('code', session()->get('lang'))->first();
  } else {
      $language = App\Models\Language::where('is_default', 1)->first();
  }
  $language_keywords = file_get_contents(resource_path() . '/lang/' . $language->code . '.json');
  $language_keywords = json_decode($language_keywords, true);
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ $language->rtl == 1 ? 'rtl' : '' }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $language_keywords['INVOICE'] ?? __('INVOICE') }}</title>
  @php
    $font_family = 'DejaVu Sans, sans-serif';
    $primary_color = '#' . str_replace('#', '', $bs->base_color);
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

    /* Regards */
    .regards-section {
      margin-top: 60px;
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
  </style>
</head>

<body dir="{{ $language->rtl == 1 ? 'rtl' : '' }}">
  <div class="main">
    
    <!-- HEADER -->
    <table class="header-table">
      <tr>
        <td>
          @if ($bs->logo)
            <img src="{{ asset('assets/front/img/' . $bs->logo) }}" height="42" style="display: block; border: 0;">
          @else
            <img src="{{ asset('assets/admin/img/noimage.jpg') }}" height="42" style="display: block; border: 0;">
          @endif
        </td>
        <td class="text-right">
          <div class="invoice-title">{{ $language_keywords['INVOICE'] ?? __('INVOICE') }}</div>
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
            <strong>{{ $language_keywords['Payment Method'] ?? __('Payment Method') }}:</strong>
            <span>{{ $request['payment_method'] }}</span>
          </td>
          <td width="35%" class="text-center">
            <strong>{{ $language_keywords['Order No'] ?? __('Order No') }}:</strong>
            <span>#{{ $order_id }}</span>
          </td>
          <td width="30%" class="text-right">
            <strong>{{ $language_keywords['Date'] ?? __('Date') }}:</strong>
            <span>{{ \Carbon\Carbon::now()->locale('en')->isoFormat('Do MMMM YYYY') }}</span>
          </td>
        </tr>
      </table>
    </div>

    <!-- BILL TO & ORDER DETAILS -->
    <table class="info-section">
      <tr>
        <td width="48%">
          <div class="info-title">{{ $language_keywords['Bill to'] ?? __('Bill to') }}</div>
          <div class="info-text">
            <strong>{{ $language_keywords['Name'] ?? __('Name') }}:</strong> {{ $member['shop_name'] }}
          </div>
          <div class="info-text">
            <strong>{{ $language_keywords['Username'] ?? __('Username') }}:</strong> {{ $member['username'] }}
          </div>
          <div class="info-text">
            <strong>{{ $language_keywords['Email'] ?? __('Email') }}:</strong> {{ $member['email'] }}
          </div>
          @if ($phone)
            <div class="info-text">
              <strong>{{ $language_keywords['Phone'] ?? __('Phone') }}:</strong> {{ $phone }}
            </div>
          @endif
        </td>
        <td width="4%"></td>
        <td width="48%">
          <div class="info-title">{{ $language_keywords['Order Details'] ?? __('Order Details') }}</div>
          <div class="info-text">
            <strong>{{ $language_keywords['Order Id'] ?? __('Order Id') }}:</strong> #{{ $order_id }}
          </div>
          <div class="info-text">
            <strong>{{ $language_keywords['Order Price'] ?? __('Order Price') }}:</strong> 
            {{ $amount == 0 ? ($language_keywords['Free'] ?? __('Free')) : textPrice($base_currency_text_position, $base_currency_text, $amount) }}
          </div>
          <div class="info-text">
            <strong>{{ $language_keywords['Payment Status'] ?? __('Payment Status') }}:</strong> 
            <span style="color: {{ $status == 2 ? '#ef4444' : '#10b981' }}; font-weight: bold;">
              {{ $status == 2 ? ($language_keywords['Rejected'] ?? __('Rejected')) : ($language_keywords['Completed'] ?? __('Completed')) }}
            </span>
          </div>
        </td>
      </tr>
    </table>

    <!-- PACKAGE INFO TABLE -->
    <table class="items-table">
      <thead>
        <tr>
          <th class="text-left" width="30%">{{ $language_keywords['Package Title'] ?? __('Package Title') }}</th>
          <th class="text-center" width="20%">{{ $language_keywords['Start Date'] ?? __('Start Date') }}</th>
          <th class="text-center" width="20%">{{ $language_keywords['Expire Date'] ?? __('Expire Date') }}</th>
          <th class="text-center" width="15%">{{ $language_keywords['Currency'] ?? __('Currency') }}</th>
          <th class="text-right" width="15%">{{ $language_keywords['Price'] ?? __('Price') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="bold">{{ $package_title }}</td>
          <td class="text-center">{{ $request['start_date'] }}</td>
          <td class="text-center">
            @if (\Carbon\Carbon::parse($request['expire_date'])->format('Y') == '9999')
              <span>{{ $language_keywords['Lifetime'] ?? __('Lifetime') }}</span>
            @else
              {{ $request['expire_date'] }}
            @endif
          </td>
          <td class="text-center">{{ $base_currency_text }}</td>
          <td class="text-right bold" style="color: {{ $primary_color }};">
            @if ($amount == 0)
              <span>{{ $language_keywords['Free'] ?? __('Free') }}</span>
            @else
              {{ textPrice($base_currency_text_position, $base_currency_text, $amount) }}
            @endif
          </td>
        </tr>
      </tbody>
    </table>

    <!-- REGARDS -->
    <table class="regards-section">
      <tr>
        <td class="text-right">
          <div class="regards-text">{{ $language_keywords['Thanks & Regards'] ?? __('Thanks & Regards') }},</div>
          <div class="regards-brand">{{ $bs->website_title }}</div>
        </td>
      </tr>
    </table>

  </div>
</body>

</html>
