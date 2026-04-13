<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 0;
            padding: 24px;
            background: #ffffff;
        }
        .invoice {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }
        .header {
            background: #0f172a;
            color: #ffffff;
            padding: 18px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            line-height: 1.2;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #cbd5e1;
        }
        .section {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .section:last-child {
            border-bottom: none;
        }
        .grid {
            width: 100%;
        }
        .grid td {
            vertical-align: top;
            width: 50%;
            padding-right: 16px;
        }
        .label {
            color: #6b7280;
            font-size: 11px;
            margin-bottom: 4px;
        }
        .value {
            color: #111827;
            font-size: 12px;
            line-height: 1.5;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th {
            text-align: left;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 11px;
            color: #334155;
        }
        .table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 11px;
            color: #111827;
        }
        .right { text-align: right; }
        .summary {
            width: 320px;
            margin-left: auto;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .summary td {
            padding: 6px 0;
            font-size: 12px;
        }
        .summary .muted { color: #6b7280; }
        .summary .total td {
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .footer {
            padding: 14px 20px;
            background: #f8fafc;
            color: #475569;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <h1>{{ app_setting('project_name', config('app.name', 'Invoice')) }} - Tax Invoice</h1>
            <p>Order {{ $order->order_number }} | Date {{ optional($order->placed_at)->format('d M Y, h:i A') ?? $order->created_at->format('d M Y, h:i A') }}</p>
        </div>

        <div class="section">
            <table class="grid" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div class="label">Bill To</div>
                        <div class="value">
                            <strong>{{ $order->customer_name }}</strong><br>
                            {{ $order->customer_phone }}<br>
                            @if($order->customer_email)
                                {{ $order->customer_email }}<br>
                            @endif
                            {{ $order->address_line1 }}{{ $order->address_line2 ? ', ' . $order->address_line2 : '' }}<br>
                            {{ $order->city }}, {{ $order->state }}, {{ $order->country }} - {{ $order->pincode }}
                        </div>
                    </td>
                    <td>
                        <div class="label">Order Details</div>
                        <div class="value">
                            <strong>Order ID:</strong> #{{ $order->id }}<br>
                            <strong>Order Number:</strong> {{ $order->order_number }}<br>
                            <strong>Payment:</strong> {{ strtoupper($order->payment_method) }} ({{ ucfirst($order->payment_status) }})<br>
                            <strong>Status:</strong> {{ ucwords(str_replace('-', ' ', $order->status)) }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 46%;">Product</th>
                        <th style="width: 14%;" class="right">Qty</th>
                        <th style="width: 20%;" class="right">Unit Price</th>
                        <th style="width: 20%;" class="right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->sku)
                                    <br><span style="color:#64748b;">SKU: {{ $item->sku }}</span>
                                @endif
                            </td>
                            <td class="right">{{ (int) $item->quantity }}</td>
                            <td class="right">Rs {{ number_format((float) $item->price, 2) }}</td>
                            <td class="right">Rs {{ number_format((float) $item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="summary">
                <tr>
                    <td class="muted">Subtotal</td>
                    <td class="right">Rs {{ number_format((float) $order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="muted">Discount</td>
                    <td class="right">- Rs {{ number_format((float) $order->discount, 2) }}</td>
                </tr>
                <tr>
                    <td class="muted">Shipping</td>
                    <td class="right">Rs {{ number_format((float) $order->shipping_amount, 2) }}</td>
                </tr>
                <tr class="total">
                    <td>Total</td>
                    <td class="right">Rs {{ number_format((float) $order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            This is a system generated invoice and does not require a signature.
        </div>
    </div>
</body>
</html>
