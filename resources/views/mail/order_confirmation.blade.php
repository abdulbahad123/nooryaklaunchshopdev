<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? ('Order Confirmation #' . $order->order_number) }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.6;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f1f5f9;
            padding: 30px 10px;
        }
        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header-bar {
            background-color: {{ !empty($userBs->base_color) ? '#' . str_replace('#', '', $userBs->base_color) : '#059669' }};
            height: 6px;
        }
        .email-header {
            padding: 28px 32px 20px 32px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }
        .brand-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .hero-banner {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border-bottom: 1px solid #d1fae5;
            padding: 24px 32px;
            text-align: center;
        }
        .hero-badge {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 9999px;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .hero-title {
            font-size: 22px;
            font-weight: 700;
            color: #065f46;
            margin: 0 0 6px 0;
        }
        .hero-subtitle {
            font-size: 14px;
            color: #047857;
            margin: 0;
        }
        .content-body {
            padding: 32px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .info-grid td {
            padding: 12px 16px;
            font-size: 13px;
            color: #475569;
            vertical-align: top;
        }
        .info-grid strong {
            color: #0f172a;
        }
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 14px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 6px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .items-table th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            border-bottom: 2px solid #e2e8f0;
        }
        .items-table td {
            padding: 14px 12px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .item-title {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .item-variation {
            font-size: 12px;
            color: #64748b;
        }
        .totals-table {
            width: 100%;
            max-width: 320px;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 28px;
        }
        .totals-table td {
            padding: 6px 12px;
            font-size: 13px;
            color: #475569;
        }
        .totals-table .total-row td {
            border-top: 2px solid #0f172a;
            padding-top: 10px;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }
        .address-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
        }
        .cta-box {
            text-align: center;
            padding: 24px 0 10px 0;
            border-top: 1px solid #e2e8f0;
            margin-top: 24px;
        }
        .btn-invoice {
            display: inline-block;
            background-color: {{ !empty($userBs->base_color) ? '#' . str_replace('#', '', $userBs->base_color) : '#059669' }};
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .footer-note {
            font-size: 12px;
            color: #64748b;
            margin-top: 14px;
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Accent Top Bar -->
            <div class="header-bar"></div>

            <!-- Brand Header -->
            <div class="email-header">
                @if(!empty($userBs->logo))
                    <img src="{{ asset('/assets/front/img/user/' . $userBs->logo) }}" alt="{{ $website_title }}" height="40" style="max-height: 40px; display: inline-block;" />
                @else
                    <h1 class="brand-title">{{ $website_title }}</h1>
                @endif
            </div>

            <!-- Hero Confirmation Banner -->
            <div class="hero-banner">
                <span class="hero-badge">&#10003; Order Confirmed</span>
                <h2 class="hero-title">Thank You for Your Order!</h2>
                <p class="hero-subtitle">Hi <strong>{{ $customerName }}</strong>, we have received your order and are preparing it for shipment.</p>
            </div>

            <!-- Content Body -->
            <div class="content-body">
                <!-- Meta Info Grid -->
                <table class="info-grid">
                    <tr>
                        <td width="50%">
                            <strong>Order Number:</strong><br>
                            #{{ $order->order_number }}
                        </td>
                        <td width="50%" class="text-right">
                            <strong>Order Date:</strong><br>
                            {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Payment Method:</strong><br>
                            {{ ucfirst($order->method) }}
                        </td>
                        <td class="text-right">
                            <strong>Payment Status:</strong><br>
                            <span style="color: {{ strtolower($order->payment_status) == 'completed' ? '#059669' : '#d97706' }}; font-weight: bold;">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                </table>

                <!-- Items Table -->
                <div class="section-title">Ordered Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th class="text-left" width="55%">Item</th>
                            <th class="text-center" width="15%">Qty</th>
                            <th class="text-right" width="15%">Price</th>
                            <th class="text-right" width="15%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currSign = $order->currency_sign ?? '₹';
                            $items = $order->orderitems ?? [];
                        @endphp
                        @forelse($items as $item)
                            @php
                                $itemPrice = (float)($item->price ?? 0);
                                $itemQty = (int)($item->qty ?? 1);
                                $lineTotal = $itemPrice * $itemQty;
                                $variations = !empty($item->variations) ? json_decode($item->variations, true) : null;
                            @endphp
                            <tr>
                                <td>
                                    <div class="item-title">{{ $item->title }}</div>
                                    @if(!empty($variations) && is_array($variations))
                                        <div class="item-variation">
                                            @foreach($variations as $varKey => $varVal)
                                                @php
                                                    $optName = is_array($varVal) ? ($varVal['name'] ?? '') : (is_object($varVal) ? ($varVal->name ?? '') : '');
                                                    $optKey = is_string($varKey) ? $varKey : '';
                                                @endphp
                                                <span>{{ $optKey ? $optKey . ': ' : '' }}{{ $optName }}</span>@if(!$loop->last), @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">{{ $itemQty }}</td>
                                <td class="text-right">{{ $currSign }}{{ number_format($itemPrice, 2) }}</td>
                                <td class="text-right" style="font-weight: 600; color: #0f172a;">{{ $currSign }}{{ number_format($lineTotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center" style="color: #64748b;">Order details recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Totals Breakdown -->
                <table class="totals-table">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-right" style="font-weight: 600;">{{ $currSign }}{{ number_format((float)($order->cart_total ?? 0), 2) }}</td>
                    </tr>
                    @if(!empty($order->discount) && (float)$order->discount > 0)
                        <tr>
                            <td style="color: #059669;">Discount:</td>
                            <td class="text-right" style="color: #059669; font-weight: 600;">-{{ $currSign }}{{ number_format((float)$order->discount, 2) }}</td>
                        </tr>
                    @endif
                    @if(!empty($order->tax) && (float)$order->tax > 0)
                        <tr>
                            <td>Tax / GST{{ !empty($order->tax_percentage) ? ' (' . $order->tax_percentage . '%)' : '' }}:</td>
                            <td class="text-right">{{ $currSign }}{{ number_format((float)$order->tax, 2) }}</td>
                        </tr>
                    @endif
                    @if(!empty($order->shipping_charge) && (float)$order->shipping_charge > 0)
                        <tr>
                            <td>Shipping{{ !empty($order->shipping_method) ? ' (' . $order->shipping_method . ')' : '' }}:</td>
                            <td class="text-right">{{ $currSign }}{{ number_format((float)$order->shipping_charge, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td>Total Paid:</td>
                        <td class="text-right">{{ $currSign }}{{ number_format((float)($order->total ?? 0), 2) }}</td>
                    </tr>
                </table>

                <!-- Addresses Grid -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                    <tr>
                        <td width="48%" valign="top">
                            <div class="section-title">Billing Address</div>
                            <div class="address-box">
                                <strong>{{ $order->billing_fname }} {{ $order->billing_lname }}</strong><br>
                                {{ $order->billing_address }}<br>
                                {{ $order->billing_city }}@if(!empty($order->billing_state)), {{ $order->billing_state }}@endif<br>
                                {{ $order->billing_country }}<br>
                                &#128222; {{ $order->billing_number }}<br>
                                &#9993; {{ $order->billing_email }}
                            </div>
                        </td>
                        <td width="4%">&nbsp;</td>
                        <td width="48%" valign="top">
                            <div class="section-title">Shipping Address</div>
                            <div class="address-box">
                                <strong>{{ $order->shipping_fname ?: $order->billing_fname }} {{ $order->shipping_lname ?: $order->billing_lname }}</strong><br>
                                {{ $order->shipping_address ?: $order->billing_address }}<br>
                                {{ $order->shipping_city ?: $order->billing_city }}@if(!empty($order->shipping_state || $order->billing_state)), {{ $order->shipping_state ?: $order->billing_state }}@endif<br>
                                {{ $order->shipping_country ?: $order->billing_country }}<br>
                                &#128222; {{ $order->shipping_number ?: $order->billing_number }}
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Download Invoice CTA Button -->
                <div class="cta-box">
                    @if(!empty($order->invoice_number))
                        <a href="{{ asset('assets/front/invoices/' . $order->invoice_number) }}" target="_blank" class="btn-invoice">
                            &#128196; Download Official Invoice (PDF)
                        </a>
                        <div class="footer-note">
                            Your official PDF invoice is also attached to this email for your convenience.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Email Footer -->
            <div class="email-footer">
                <p style="margin: 0 0 6px 0; font-weight: 600; color: #64748b;">
                    {{ $website_title }}
                </p>
                <p style="margin: 0;">
                    If you have any questions about your order, please reply to this email.
                </p>
                <p style="margin: 8px 0 0 0; font-size: 11px; color: #cbd5e1;">
                    &copy; {{ date('Y') }} {{ $website_title }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
