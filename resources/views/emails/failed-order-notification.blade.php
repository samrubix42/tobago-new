<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Failed Order Alert - Tobac-Go</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #ffffff;
            padding: 32px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }
        .badge {
            display: inline-block;
            background-color: #fee2e2;
            color: #ef4444;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 6px 14px;
            border-radius: 9999px;
            margin-bottom: 16px;
        }
        .logo-text {
            color: #0f172a;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .logo-sub {
            color: #64748b;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            margin: 4px 0 0 0;
            text-transform: uppercase;
        }
        .body {
            padding: 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 8px;
            color: #0f172a;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 24px;
        }
        

        
        .card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 18px;
            box-sizing: border-box;
        }
        .card-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-top: 0;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .meta-row {
            margin-bottom: 8px;
            font-size: 13px;
        }
        .meta-label {
            color: #64748b;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 2px;
        }
        .meta-value {
            color: #0f172a;
            font-weight: 600;
            display: block;
        }
        
        .address-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 13px;
            margin: 0 0 4px 0;
        }
        .address-line {
            font-size: 12px;
            color: #475569;
            line-height: 1.4;
            margin: 0 0 2px 0;
        }
        .contact-info-p {
            font-size: 12px;
            color: #334155;
            margin: 6px 0 0 0;
        }
        
        .section-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-top: 24px;
            margin-bottom: 8px;
            font-weight: 700;
            padding-left: 4px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .items-table th {
            text-align: left;
            padding: 10px 8px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 700;
        }
        .items-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            vertical-align: middle;
        }
        .product-name {
            font-weight: 600;
            color: #0f172a;
            margin: 0;
            font-size: 13px;
        }
        .product-cat {
            font-size: 11px;
            color: #64748b;
            margin: 2px 0 0 0;
        }
        .item-qty {
            color: #334155;
            text-align: center;
            font-weight: 600;
        }
        .item-total {
            font-weight: 600;
            color: #0f172a;
            text-align: right;
        }
        
        .pricing-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 24px;
        }
        .pricing-row {
            width: 100%;
            font-size: 13px;
        }
        .pricing-row td {
            padding: 4px 0;
        }
        .pricing-label {
            color: #64748b;
            font-weight: 500;
        }
        .pricing-value {
            font-weight: 600;
            text-align: right;
            color: #0f172a;
        }
        .pricing-grand-row td {
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
            margin-top: 6px;
        }
        .pricing-grand-label {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .pricing-grand-value {
            font-size: 16px;
            font-weight: 800;
            text-align: right;
            color: #ef4444;
        }
        
        .cta-container {
            text-align: center;
            margin: 24px 0 8px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 10px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
            transition: background-color 0.2s ease;
        }
        .cta-button:hover {
            background-color: #059669;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            color: #64748b;
            font-size: 11px;
            line-height: 1.5;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <span class="badge">Payment Failed</span>
                <div class="logo-text">Tobac-Go</div>
                <div class="logo-sub">Store Administration</div>
            </div>

            <!-- Body -->
            <div class="body">
                <h1 class="greeting">Hello Admin,</h1>
                <p class="intro-text">An order attempt has failed. A customer was trying to purchase the following items from the store, but the payment failed or was not completed. You can contact them using the details below:</p>

                <!-- Order and Customer Details Stack -->
                <div style="margin-bottom: 24px;">
                    <!-- Order Info -->
                    <div class="card" style="margin-bottom: 16px;">
                        <h2 class="card-title">Order Info</h2>
                        <div class="meta-row">
                            <span class="meta-label">Order Number</span>
                            <span class="meta-value">{{ $order->order_number }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Date Attempted</span>
                            <span class="meta-value" style="font-size: 12px; font-weight: 500;">{{ $order->updated_at ? $order->updated_at->format('d M, Y h:i A') : now()->format('d M, Y h:i A') }}</span>
                        </div>
                        <div class="meta-row" style="margin-bottom: 0;">
                            <span class="meta-label">Payment Status</span>
                            <span class="meta-value" style="color: #ef4444; font-size: 12px;">FAILED</span>
                        </div>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="card">
                        <h2 class="card-title">Customer Info</h2>
                        <p class="address-name">{{ $order->customer_name }}</p>
                        <p class="address-line">{{ $order->address_line1 }}</p>
                        @if($order->address_line2)
                            <p class="address-line">{{ $order->address_line2 }}</p>
                        @endif
                        <p class="address-line" style="margin-bottom: 6px;">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                        
                        <div style="border-top: 1px solid #e2e8f0; padding-top: 6px; margin-top: 6px;">
                            <p class="contact-info-p" style="margin: 0; font-size: 12px;"><strong>Phone:</strong> +91 {{ $order->customer_phone }}</p>
                            @if($order->customer_email)
                                <p class="contact-info-p" style="margin: 2px 0 0 0; font-size: 12px; word-break: break-all;"><strong>Email:</strong> {{ $order->customer_email }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Items Ordered -->
                <h2 class="section-title">Items In Cart</h2>
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

                <!-- CTA Button to call/contact -->
                <div class="cta-container">
                    <a href="tel:{{ $order->customer_phone }}" class="cta-button">Contact Customer (+91 {{ $order->customer_phone }})</a>
                </div>
                
                <div style="text-align: center; margin-top: 15px;">
                    <a href="{{ route('admin.orders.manage', $order->id) }}" style="color: #64748b; font-size: 12px; text-decoration: underline;">View details in admin panel</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0;">&copy; {{ date('Y') }} Tobac-Go Administration Panel.</p>
                <p style="margin: 4px 0 0 0; color: #94a3b8;">This is an automated administrative alert. Please do not reply to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>
