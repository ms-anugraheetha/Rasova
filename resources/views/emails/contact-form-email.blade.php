<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: -apple-system, sans-serif; background: #f4ede2; padding: 32px; margin: 0;">
    <div style="max-width: 520px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px;">
        <h2 style="margin: 0 0 4px; color: #1a1a1a;">New message from the Rasova contact form</h2>
        <p style="color: #666; font-size: 13px; margin: 0 0 24px;">Reply directly to this email to respond to {{ $senderName }}.</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 8px 0; color: #888; font-size: 13px; width: 80px;">Name</td>
                <td style="padding: 8px 0; font-size: 14px;">{{ $senderName }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #888; font-size: 13px;">Email</td>
                <td style="padding: 8px 0; font-size: 14px;"><a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a></td>
            </tr>
        </table>

        <div style="border-top: 1px solid #eee; padding-top: 16px;">
            <p style="color: #888; font-size: 13px; margin: 0 0 8px;">Message</p>
            <p style="font-size: 14px; line-height: 1.6; white-space: pre-line; margin: 0;">{{ $messageBody }}</p>
        </div>
    </div>
</body>
</html>