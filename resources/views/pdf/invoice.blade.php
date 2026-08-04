<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #1a1a1a; font-size: 13px; margin: 0; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; border-bottom: 2px solid #b3132d; padding-bottom: 16px; }
        .header h1 { color: #b3132d; font-size: 24px; margin: 0; }
        .header .invoice-meta { text-align: right; font-size: 12px; color: #666; }
        .section { margin-bottom: 24px; }
        .section-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #888; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { text-align: left; border-bottom: 1px solid #ddd; padding: 8px 4px; font-size: 11px; text-transform: uppercase; color: #888; }
        td { padding: 8px 4px; border-bottom: 1px solid #f0f0f0; font-size: 13px; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; font-size: 15px; border-top: 2px solid #1a1a1a; border-bottom: none; }
        .footer { margin-top: 40px; font-size: 11px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>Rasova</h1>
            <p style="margin: 4px 0 0; color: #666;">Homemade Kerala Pickles</p>
        </div>
        <div class="invoice-meta">
            <p style="margin: 0;"><strong>Invoice</strong></p>
            <p style="margin: 2px 0;">Order #{{ $order->order_number }}</p>
            <p style="margin: 2px 0;">{{ $order->created_at->format('M j, Y') }}</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Billed To</div>
        <p style="margin: 0;">{{ $order->shipping_full_name }}</p>
        <p style="margin: 0;">{{ $order->shipping_phone }}</p>
        <p style="margin: 0;">
            {{ $order->shipping_address_line_1 }}{{ $order->shipping_address_line_2 ? ', ' . $order->shipping_address_line_2 : '' }},
            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Weight</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->weight }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">&#8377;{{ number_format($item->unit_price_minor / 100, 2) }}</td>
                    <td class="text-right">&#8377;{{ number_format($item->total_price_minor / 100, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right">Subtotal</td>
                <td class="text-right">&#8377;{{ number_format($order->subtotal_minor / 100, 2) }}</td>
            </tr>
            @if ($order->shipping_fee_minor > 0)
                <tr>
                    <td colspan="4" class="text-right">Shipping</td>
                    <td class="text-right">&#8377;{{ number_format($order->shipping_fee_minor / 100, 2) }}</td>
                </tr>
            @endif
            @if ($order->gst_amount_minor > 0)
                <tr>
                    <td colspan="4" class="text-right">GST</td>
                    <td class="text-right">&#8377;{{ number_format($order->gst_amount_minor / 100, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="4" class="text-right">Total Paid</td>
                <td class="text-right">&#8377;{{ number_format($order->total_minor / 100, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Thank you for choosing Rasova — homemade Kerala pickles, made fresh for every order.</p>
    </div>
</body>
</html>