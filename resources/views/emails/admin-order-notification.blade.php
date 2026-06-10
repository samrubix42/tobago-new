<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification - Tobac-Go</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #08090c; /* Sleek dark environment */
            margin: 0;
            padding: 0;
            color: #d1d5db;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #08090c;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #111318; /* Premium dark card background */
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #222530;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }
        .header {
            background: linear-gradient(135deg, #181b24 0%, #0c0e12 100%);
            padding: 35px 32px;
            text-align: center;
            border-bottom: 2px solid #312e81; /* Premium Indigo accent border */
            position: relative;
        }
        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); /* Vibrant Amber/Orange gradient */
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 6px 14px;
            border-radius: 9999px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }
        .logo-text {
            color: #ffffff;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 2px;
            margin: 0;
            text-transform: uppercase;
        }
        .logo-sub {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 3px;
            margin: 6px 0 0 0;
            text-transform: uppercase;
        }
        .body {
            padding: 32px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 8px;
            color: #ffffff;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 28px;
        }
        
        /* Two Column Layout using Tables for Email Compatibility */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .info-column {
            width: 50%;
            vertical-align: top;
            padding-right: 12px;
        }
        .info-column-last {
            width: 50%;
            vertical-align: top;
            padding-left: 12px;
        }
        
        .card {
            background-color: #171b22;
            border: 1px solid #242a36;
            border-radius: 14px;
            padding: 18px 20px;
            height: 100%;
            box-sizing: border-box;
        }
        .card-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #f97316; /* Orange brand color */
            margin-top: 0;
            margin-bottom: 12px;
            font-weight: 700;
        }
        
        /* Grid style info rows */
        .meta-row {
            margin-bottom: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        .meta-label {
            color: #64748b;
            font-weight: 500;
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .meta-value {
            color: #f1f5f9;
            font-weight: 600;
        }
        
        .address-name {
            font-weight: 700;
            color: #ffffff;
            font-size: 14px;
            margin: 0 0 6px 0;
        }
        .address-line {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
            margin: 0 0 4px 0;
        }
        .contact-info-p {
            font-size: 13px;
            color: #cbd5e1;
            margin: 8px 0 0 0;
            line-height: 1.4;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 24px;
        }
        .items-table th {
            text-align: left;
            padding: 12px 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            border-bottom: 1px solid #242a36;
            font-weight: 700;
        }
        .items-table td {
            padding: 14px 10px;
            border-bottom: 1px solid #1c212c;
            font-size: 13px;
            vertical-align: middle;
        }
        .product-name {
            font-weight: 600;
            color: #ffffff;
            margin: 0;
            font-size: 13.5px;
        }
        .product-cat {
            font-size: 11px;
            color: #64748b;
            margin: 3px 0 0 0;
        }
        .item-qty {
            color: #cbd5e1;
            text-align: center;
            font-weight: 600;
        }
        .item-total {
            font-weight: 600;
            color: #ffffff;
            text-align: right;
        }
        
        /* Pricing Calculations */
        .pricing-box {
            background-color: #171b22;
            border: 1px solid #242a36;
            border-radius: 14px;
            padding: 18px 20px;
            margin-bottom: 28px;
        }
        .pricing-row {
            width: 100%;
            margin-bottom: 8px;
            font-size: 13.5px;
        }
        .pricing-row td {
            padding: 3px 0;
        }
        .pricing-label {
            color: #94a3b8;
            font-weight: 500;
        }
        .pricing-value {
            font-weight: 600;
            text-align: right;
            color: #f1f5f9;
        }
        .pricing-grand-row td {
            border-top: 1px dashed #334155;
            padding-top: 12px;
            margin-top: 8px;
        }
        .pricing-grand-label {
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
        }
        .pricing-grand-value {
            font-size: 18px;
            font-weight: 800;
            text-align: right;
            color: #f97316; /* Vibrant brand color accent */
        }
        
        /* Button */
        .cta-container {
            text-align: center;
            margin: 32px 0 10px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 15px 32px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 12px;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.25);
            transition: all 0.3s ease;
        }
        .footer {
            background-color: #0c0e12;
            padding: 28px;
            text-align: center;
            color: #475569;
            font-size: 11px;
            line-height: 1.6;
            border-top: 1px solid #1c212c;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <span class="badge">Paid Success</span>
                <div class="logo-text">Tobac-Go</div>
                <div class="logo-sub">Store Administration</div>
            </div>

            <!-- Body -->
            <div class="body">
                <h1 class="greeting">Hello Admin,</h1>
                <p class="intro-text">Good news! A new order has been placed and payment is verified via PhonePe. Below are the order and fulfillment details:</p>

                <!-- Order and Customer Grid -->
                <table class="info-table">
                    <tr>
                        <!-- Left Column: Order Details -->
                        <td class="info-column">
                            <div class="card">
                                <h2 class="card-title">Order Info</h2>
                                
                                <div class="meta-row">
                                    <span class="meta-label">Order Number</span>
                                    <span class="meta-value">{{ $order->order_number }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label">Date Placed</span>
                                    <span class="meta-value" style="font-size: 12px;">{{ $order->placed_at ? $order->placed_at->format('d M, Y h:i A') : now()->format('d M, Y h:i A') }}</span>
                                </div>
                                <div class="meta-row" style="margin-bottom: 0;">
                                    <span class="meta-label">Gateway Trans ID</span>
                                    <span class="meta-value" style="font-size: 11px; word-break: break-all;">{{ $order->payment_gateway_transaction_id ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Right Column: Shipping Details -->
                        <td class="info-column-last">
                            <div class="card">
                                <h2 class="card-title">Delivery Info</h2>
                                <p class="address-name">{{ $order->customer_name }}</p>
                                <p class="address-line">{{ $order->address_line1 }}</p>
                                @if($order->address_line2)
                                    <p class="address-line">{{ $order->address_line2 }}</p>
                                @endif
                                @if($order->landmark)
                                    <p class="address-line">Landmark: {{ $order->landmark }}</p>
                                @endif
                                <p class="address-line" style="margin-bottom: 6px;">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                                
                                <div style="border-top: 1px solid #242a36; padding-top: 6px; margin-top: 6px;">
                                    <p class="contact-info-p" style="margin: 0; font-size: 12px;"><strong>Phone:</strong> +91 {{ $order->customer_phone }}</p>
                                    @if($order->customer_email)
                                        <p class="contact-info-p" style="margin: 2px 0 0 0; font-size: 12px; word-break: break-all;"><strong>Email:</strong> {{ $order->customer_email }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Items Ordered -->
                <h2 class="card-title" style="margin-bottom: 8px; padding-left: 10px;">Items Ordered</h2>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th style="text-align: center; width: 60px;">Qty</th>
                            <th style="text-align: right; width: 100px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <div>
                                    <p class="product-name">{{ $item->product_name }}</p>
                                    <p class="product-cat">{{ $item->product_category }} @if($item->sku) (SKU: {{ $item->sku }}) @endif</p>
                                </div>
                            </td>
                            <td class="item-qty">{{ (int) $item->quantity }}</td>
                            <td class="item-total">₹{{ number_format((float) $item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pricing Summary Card -->
                <table style="width: 100%; border-collapse: collapse;" class="pricing-box">
                    <tr class="pricing-row">
                        <td class="pricing-label">Subtotal</td>
                        <td class="pricing-value">₹{{ number_format((float) $order->subtotal, 2) }}</td>
                    </tr>
                    @if((float) $order->discount > 0)
                    <tr class="pricing-row">
                        <td class="pricing-label">Discount @if($order->coupon_code) ({{ $order->coupon_code }}) @endif</td>
                        <td class="pricing-value" style="color: #10b981;">- ₹{{ number_format((float) $order->discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="pricing-row">
                        <td class="pricing-label">Shipping & Delivery</td>
                        <td class="pricing-value">₹{{ number_format((float) $order->shipping_amount, 2) }}</td>
                    </tr>
                    <tr class="pricing-grand-row">
                        <td class="pricing-grand-label">Grand Total</td>
                        <td class="pricing-grand-value">₹{{ number_format((float) $order->total, 2) }}</td>
                    </tr>
                </table>

                <!-- CTA Button -->
                <div class="cta-container">
                    <a href="{{ route('admin.orders.manage', $order->id) }}" class="cta-button" target="_blank">Manage Order Details</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0;">&copy; {{ date('Y') }} Tobac-Go Administration Panel.</p>
                <p style="margin: 4px 0 0 0; color: #334155;">This is an automated administrative notification. Please do not reply to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>
