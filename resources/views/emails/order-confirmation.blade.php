<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: -apple-system, sans-serif; background: #f4ede2; padding: 32px; margin: 0;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px;">
        <h2 style="margin: 0 0 4px; color: #1a1a1a;">Thank you for your order!</h2>
        <p style="color: #666; font-size: 13px; margin: 0 0 24px;">Order #{{ $order->order_number }} has been confirmed and is being prepared.</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
            @foreach ($order->items as $item)
                <tr>
                    <td style="padding: 6px 0; font-size: 14px;">{{ $item->product_name }} ({{ $item->weight }}) &times;{{ $item->quantity }}</td>
                    <td style="padding: 6px 0; font-size: 14px; text-align: right;">&#8377;{{ number_format($item->total_price_minor / 100, 2) }}</td>
                </tr>
            @endforeach
        </table>

        <div style="border-top: 1px solid #eee; padding-top: 16px; display: flex; justify-content: space-between; margin-bottom: 20px;">
            <span style="font-size: 15px; font-weight: 600;">Total Paid</span>
            <span style="font-size: 15px; font-weight: 600;">&#8377;{{ number_format($order->total_minor / 100, 2) }}</span>
        </div>

        <p style="color: #666; font-size: 13px; margin-bottom: 4px;">Delivering to:</p>
        <p style="font-size: 14px; margin: 0 0 20px;">
            {{ $order->shipping_full_name }}<br>
            {{ $order->shipping_address_line_1 }}{{ $order->shipping_address_line_2 ? ', ' . $order->shipping_address_line_2 : '' }},
            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
        </p>

        <p style="color: #888; font-size: 12px; margin: 0;">Your invoice is attached to this email as a PDF.</p>
    </div>
</body>
</html>