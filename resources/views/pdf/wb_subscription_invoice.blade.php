<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice #{{ $invoiceId }}</title>
  <style>
    body {
      font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
      font-size: 13px;
      color: #1e293b;
      line-height: 1.6;
      margin: 0;
      padding: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    td, th {
      vertical-align: top;
    }
    .header-table {
      margin-bottom: 25px;
      border-bottom: 3px solid #6366f1;
      padding-bottom: 15px;
    }
    .brand-title {
      font-size: 24px;
      font-weight: bold;
      color: #1e1b4b;
      margin: 0;
    }
    .brand-subtitle {
      font-size: 11px;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .invoice-badge {
      text-align: right;
    }
    .invoice-title {
      font-size: 26px;
      font-weight: bold;
      color: #6366f1;
      margin: 0;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .status-badge {
      display: inline-block;
      background: #d1fae5;
      color: #065f46;
      font-weight: bold;
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 11px;
      margin-top: 5px;
    }
    .info-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 25px;
    }
    .info-title {
      font-size: 11px;
      font-weight: bold;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 6px;
    }
    .info-val {
      font-size: 13px;
      font-weight: bold;
      color: #0f172a;
    }
    .items-table {
      width: 100%;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 25px;
    }
    .items-table th {
      background: #4f46e5;
      color: #ffffff;
      font-weight: bold;
      font-size: 12px;
      text-transform: uppercase;
      padding: 12px 14px;
      text-align: left;
    }
    .items-table td {
      padding: 14px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 12px;
    }
    .items-table tr:last-child td {
      border-bottom: none;
    }
    .totals-table {
      width: 45%;
      margin-left: auto;
      margin-bottom: 30px;
    }
    .totals-table td {
      padding: 8px 12px;
      font-size: 13px;
    }
    .total-highlight {
      font-size: 16px;
      font-weight: bold;
      color: #4f46e5;
      border-top: 2px solid #e2e8f0;
    }
    .footer-note {
      text-align: center;
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid #e2e8f0;
      color: #94a3b8;
      font-size: 11px;
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <table class="header-table">
    <tr>
      <td>
        <div class="brand-title">{{ $agency->site_title ?? ($agency->name ?? 'Website Builder') }}</div>
        <div class="brand-subtitle">Website Builder Platform</div>
      </td>
      <td class="invoice-badge">
        <div class="invoice-title">Tax Invoice</div>
        <div class="status-badge">PAYMENT COMPLETED</div>
      </td>
    </tr>
  </table>

  <!-- METADATA & CUSTOMER INFO -->
  <div class="info-card">
    <table>
      <tr>
        <td width="50%">
          <div class="info-title">Billed To (Customer)</div>
          <div class="info-val">{{ $customer->name ?? 'Agency Customer' }}</div>
          <div style="color: #475569; font-size: 12px;">{{ $customer->email ?? '' }}</div>
          @if(!empty($customer->phone))
            <div style="color: #475569; font-size: 12px;">Phone: {{ $customer->phone }}</div>
          @endif
          @if(!empty($customer->subdomain))
            <div style="color: #6366f1; font-size: 12px; font-weight: bold; margin-top: 3px;">Site: {{ $customer->subdomain }}.{{ $agency->clean_domain ?? 'saasreselling.in' }}</div>
          @endif
        </td>
        <td width="50%" style="text-align: right;">
          <div class="info-title">Invoice Details</div>
          <div style="margin-bottom: 3px;"><strong>Invoice No:</strong> #{{ $invoiceId }}</div>
          <div style="margin-bottom: 3px;"><strong>Date:</strong> {{ CarbonCarbon::now()->format('d M Y, h:i A') }}</div>
          <div style="margin-bottom: 3px;"><strong>Payment Method:</strong> {{ $paymentMethod ?? 'Razorpay' }}</div>
          @if(!empty($transactionId))
            <div><strong>Transaction Ref:</strong> {{ $transactionId }}</div>
          @endif
        </td>
      </tr>
    </table>
  </div>

  <!-- LINE ITEMS -->
  <table class="items-table">
    <thead>
      <tr>
        <th width="50%">Description</th>
        <th width="20%">Billing Cycle</th>
        <th width="30%" style="text-align: right;">Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <strong style="color: #1e1b4b; font-size: 13px;">{{ $package->name ?? 'Website Plan' }} &mdash; Subscription Renewal</strong>
          <div style="color: #64748b; font-size: 11px; margin-top: 4px;">
            Validity: {{ $baseDate->format('d M Y') }} to {{ $newExpireDate->format('d M Y') }}
          </div>
          <div style="color: #10b981; font-size: 11px; font-weight: bold; margin-top: 2px;">
            Instant plan activation with full agency builder features
          </div>
        </td>
        <td style="text-transform: capitalize; font-weight: bold; color: #475569;">
          {{ $term ?? 'Monthly' }}
        </td>
        <td style="text-align: right; font-weight: bold; font-size: 14px; color: #1e1b4b;">
          Rs. {{ number_format($price, 2) }}
        </td>
      </tr>
    </tbody>
  </table>

  <!-- TOTALS -->
  <table class="totals-table">
    <tr>
      <td style="color: #64748b;">Subtotal:</td>
      <td style="text-align: right; font-weight: bold;">Rs. {{ number_format($price, 2) }}</td>
    </tr>
    <tr>
      <td style="color: #64748b;">Tax / GST (0%):</td>
      <td style="text-align: right; font-weight: bold;">Rs. 0.00</td>
    </tr>
    <tr class="total-highlight">
      <td>Total Paid:</td>
      <td style="text-align: right;">Rs. {{ number_format($price, 2) }}</td>
    </tr>
  </table>

  <!-- FOOTER -->
  <div class="footer-note">
    <p style="margin: 0 0 4px 0;">This is a computer-generated tax invoice. No signature is required.</p>
    <p style="margin: 0;">Thank you for choosing {{ $agency->site_title ?? ($agency->name ?? 'Website Builder') }}! For support, contact {{ $agency->email ?? 'support@saasreselling.com' }}</p>
  </div>

</body>
</html>
