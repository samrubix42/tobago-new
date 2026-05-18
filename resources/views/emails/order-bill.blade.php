<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Invoice - Tobac-Go Hookah Store</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f6f9fc;
            margin: 0;
            padding: 0;
            color: #333333;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f6f9fc;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #eef2f5;
        }
        .header {
            background-color: #0b0d0f;
            padding: 32px;
            text-align: center;
            border-bottom: 4px solid #f97316; /* Orange brand accent */
        }
        .logo-text {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 2px;
            margin: 0;
            text-transform: uppercase;
        }
        .logo-sub {
            color: #f97316;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 4px 0 0 0;
            text-transform: uppercase;
        }
        .body {
            padding: 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 8px;
            color: #0b0d0f;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.5;
            color: #64748b;
            margin-bottom: 24px;
        }
        .order-summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .summary-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-top: 0;
            margin-bottom: 12px;
            font-weight: bold;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            padding: 4px 0;
            font-size: 14px;
        }
        .summary-label {
            color: #64748b;
            font-weight: 500;
        }
        .summary-value {
            color: #0b0d0f;
            font-weight: 600;
            text-align: right;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .items-table th {
            text-align: left;
            padding: 12px 8px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            border-bottom: 2px solid #e2e8f0;
        }
        .items-table td {
            padding: 16px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            vertical-align: middle;
        }
        .product-info {
            display: flex;
            align-items: center;
        }
        .product-name {
            font-weight: 600;
            color: #0b0d0f;
            margin: 0;
        }
        .product-cat {
            font-size: 11px;
            color: #94a3b8;
            margin: 2px 0 0 0;
        }
        .item-qty {
            color: #64748b;
            text-align: center;
        }
        .item-total {
            font-weight: 600;
            color: #0b0d0f;
            text-align: right;
        }
        .pricing-section {
            width: 100%;
            margin-top: 16px;
            border-top: 2px solid #e2e8f0;
            padding-top: 16px;
        }
        .pricing-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .pricing-label {
            display: table-cell;
            font-size: 14px;
            color: #64748b;
        }
        .pricing-value {
            display: table-cell;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
            color: #0b0d0f;
        }
        .pricing-grand-row {
            display: table;
            width: 100%;
            margin-top: 12px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 12px;
        }
        .pricing-grand-label {
            display: table-cell;
            font-size: 16px;
            font-weight: bold;
            color: #0b0d0f;
        }
        .pricing-grand-value {
            display: table-cell;
            font-size: 18px;
            font-weight: 800;
            text-align: right;
            color: #f97316;
        }
        .shipping-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .shipping-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-top: 0;
            margin-bottom: 12px;
            font-weight: bold;
        }
        .address-name {
            font-weight: 600;
            color: #0b0d0f;
            font-size: 14px;
            margin: 0 0 6px 0;
        }
        .address-line {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
            margin: 0 0 4px 0;
        }
        .address-phone {
            font-size: 13px;
            color: #475569;
            margin: 8px 0 0 0;
            font-weight: 500;
        }
        .cta-container {
            text-align: center;
            margin: 32px 0 16px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #0b0d0f;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .footer {
            background-color: #0b0d0f;
            padding: 24px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
            line-height: 1.5;
        }
        .footer-links {
            margin-bottom: 12px;
        }
        .footer-link {
            color: #f97316;
            text-decoration: none;
            font-weight: bold;
            margin: 0 8px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="logo-text">Tobac-Go</div>
                <div class="logo-sub">Premium Hookah Store</div>
            </div>

            <!-- Body -->
            <div class="body">
                <h1 class="greeting">Hi {{ $order->customer_name }},</h1>
                <p class="intro-text">Thank you for your order! Your payment has been successfully processed, and your order is now confirmed. Below you'll find the details of your purchase and your official receipt.</p>

                <!-- Order Info -->
                <div class="order-summary-box">
                    <h2 class="summary-title">Order Details</h2>
                    <div class="summary-grid">
                        <div class="summary-row">
                            <div class="summary-cell summary-label">Order Number</div>
                            <div class="summary-cell summary-value">{{ $order->order_number }}</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-cell summary-label">Date Placed</div>
                            <div class="summary-cell summary-value">{{ $order->placed_at ? $order->placed_at->format('d M, Y h:i A') : now()->format('d M, Y h:i A') }}</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-cell summary-label">Payment Method</div>
                            <div class="summary-cell summary-value">Online (PhonePe)</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-cell summary-label">Payment Status</div>
                            <div class="summary-cell summary-value" style="color: #10b981;">Paid</div>
                        </div>
                    </div>
                </div>

                <!-- Items Purchased -->
                <h2 class="summary-title" style="margin-bottom: 8px;">Items Ordered</h2>
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
                                <div class="product-info">
                                    <div>
                                        <p class="product-name">{{ $item->product_name }}</p>
                                        <p class="product-cat">{{ $item->product_category }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="item-qty">{{ (int) $item->quantity }}</td>
                            <td class="item-total">Rs {{ number_format((float) $item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pricing Calculation -->
                <div class="pricing-section">
                    <div class="pricing-row">
                        <div class="pricing-label">Subtotal</div>
                        <div class="pricing-value">Rs {{ number_format((float) $order->subtotal, 2) }}</div>
                    </div>
                    @if((float) $order->discount > 0)
                    <div class="pricing-row">
                        <div class="pricing-label">Discount @if($order->coupon_code) ({{ $order->coupon_code }}) @endif</div>
                        <div class="pricing-value">- Rs {{ number_format((float) $order->discount, 2) }}</div>
                    </div>
                    @endif
                    <div class="pricing-row">
                        <div class="pricing-label">Shipping Amount</div>
                        <div class="pricing-value">Rs {{ number_format((float) $order->shipping_amount, 2) }}</div>
                    </div>
                    <div class="pricing-grand-row">
                        <div class="pricing-grand-label">Grand Total</div>
                        <div class="pricing-grand-value">Rs {{ number_format((float) $order->total, 2) }}</div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="shipping-card" style="margin-top: 32px;">
                    <h2 class="shipping-title">Delivery Address</h2>
                    <p class="address-name">{{ $order->customer_name }}</p>
                    <p class="address-line">{{ $order->address_line1 }}</p>
                    @if($order->address_line2)
                        <p class="address-line">{{ $order->address_line2 }}</p>
                    @endif
                    @if($order->landmark)
                        <p class="address-line">Landmark: {{ $order->landmark }}</p>
                    @endif
                    <p class="address-line">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                    <p class="address-line">{{ $order->country }}</p>
                    <p class="address-phone">Phone: +91 {{ $order->customer_phone }}</p>
                </div>

                <!-- CTA -->
                <div class="cta-container">
                    <a href="{{ config('app.url') }}" class="cta-button" target="_blank">Shop More Hookahs</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-links">
                    <a href="{{ config('app.url') }}" class="footer-link">Home</a>
                    <a href="{{ config('app.url') }}/products" class="footer-link">All Products</a>
                </div>
                <p style="margin: 0;">&copy; {{ date('Y') }} Tobac-Go Hookah Store. All rights reserved.</p>
                <p style="margin: 8px 0 0 0; color: #64748b;">If you have any questions or feedback about your order, please do not hesitate to contact our customer support team.</p>
            </div>
        </div>
    </div>
</body>
</html>
