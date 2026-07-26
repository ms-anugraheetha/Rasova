<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RazorpayWebhookController extends Controller
{
    public function __construct(private PaymentWebhookService $paymentWebhookService) {}

    public function handle(Request $request)
    {
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();
        $secret = config('services.razorpay.webhook_secret');

        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($expectedSignature, (string) $signature)) {
            Log::warning('Razorpay webhook: signature mismatch', ['ip' => $request->ip()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? null;

        // Only act on successful payment capture; ignore other event types for now
        if ($event !== 'payment.captured') {
            return response()->json(['status' => 'ignored']);
        }

        $paymentEntity = $data['payload']['payment']['entity'] ?? null;
        if (!$paymentEntity) {
            return response()->json(['error' => 'Malformed payload'], 400);
        }

        $gatewayOrderId = $paymentEntity['order_id'];
        $gatewayPaymentId = $paymentEntity['id'];
        $amountMinor = $paymentEntity['amount']; // Razorpay already sends this in paise

        $payment = Payment::where('gateway_order_id', $gatewayOrderId)->first();

        if (!$payment) {
            Log::error('Razorpay webhook: no matching payment found', ['gateway_order_id' => $gatewayOrderId]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        $payment->update(['gateway_payment_id' => $gatewayPaymentId]);

        $this->paymentWebhookService->markPaid($payment->order_id, $gatewayPaymentId, $amountMinor);

        return response()->json(['status' => 'processed']);
    }
}