<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: -apple-system, sans-serif; background: #f4ede2; padding: 32px; margin: 0;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px;">
        <h2 style="margin: 0 0 4px; color: #1a1a1a;">Payment confirmed</h2>
        <p style="color: #666; font-size: 13px; margin: 0 0 24px;">Order #{{ $order->order_number }} — payment successfully received.</p>

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
                <td style="padding: 8px 0; color: #888; font-size: 13px;">Amount</td>
                <td style="padding: 8px 0; font-size: 14px;">&#8377;{{ number_format($order->total_minor / 100, 2) }}</td>
            </tr>
        </table>

        <p style="color: #888; font-size: 12px; margin: 0;">Full invoice attached as a PDF.</p>
    </div>
</body>
</html>