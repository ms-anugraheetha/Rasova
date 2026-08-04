<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: -apple-system, sans-serif; background: #f4ede2; padding: 32px; margin: 0;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px;">
        <h2 style="margin: 0 0 4px; color: #1a1a1a;">New order placed</h2>
        <p style="color: #666; font-size: 13px; margin: 0 0 24px;">Order #{{ $order->order_number }}</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 8px 0; color: #888; font-size: 13px; width: 110px;">Customer</td>
                <td style="padding: 8px 0; font-size: 14px;">{{ $order->shipping_full_name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #888; font-size: 13px;">Email</td>
                <td style="padding: 8px 0; font-size: 14px;">{{ $order->user->email ?? $order->guest_email ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #888; font-size: 13px;">Phone</td>
                <td style="padding: 8px 0; font-size: 14px;">{{ $order->shipping_phone }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #888; font-size: 13px;">Delivery to</td>
                <td style="padding: 8px 0; font-size: 14px;">
                    {{ $order->shipping_address_line_1 }}{{ $order->shipping_address_line_2 ? ', ' . $order->shipping_address_line_2 : '' }},
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
                </td>
            </tr>
        </table>

        <div style="border-top: 1px solid #eee; padding-top: 16px; margin-bottom: 16px;">
            <p style="color: #888; font-size: 13px; margin: 0 0 10px;">Items</p>
            <table style="width: 100%; border-collapse: collapse;">
                @foreach ($order->items as $item)
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px;">{{ $item->product_name }} ({{ $item->weight }}) &times;{{ $item->quantity }}</td>
                        <td style="padding: 6px 0; font-size: 14px; text-align: right;">&#8377;{{ number_format($item->total_price_minor / 100, 2) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div style="border-top: 1px solid #eee; padding-top: 16px; display: flex; justify-content: space-between;">
            <span style="font-size: 15px; font-weight: 600;">Total</span>
            <span style="font-size: 15px; font-weight: 600;">&#8377;{{ number_format($order->total_minor / 100, 2) }}</span>
        </div>

        <p style="color: #888; font-size: 12px; margin-top: 20px;">Payment status: {{ ucfirst($order->payment_status) }}</p>
    </div>
</body>
</html>