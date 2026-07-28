<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\PaymentTransaction;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentWebhookService
{
    /**
     * Called when the payment gateway confirms a successful payment.
     * $gatewayEventId must be unique per webhook event (enforced by DB constraint)
     * so a duplicate/retried webhook can't double-decrement stock.
     */
public function markPaid(int $orderId, string $gatewayEventId, int $amountMinor): void
{
    DB::transaction(function () use ($orderId, $gatewayEventId, $amountMinor) {
        $order = Order::lockForUpdate()->findOrFail($orderId);

        if ($order->payment_status === 'paid') {
            return;
        }

        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
        ]);

        $order->payment()->update(['status' => 'paid']);

        PaymentTransaction::create([
            'payment_id' => $order->payment->id,
            'transaction_type' => 'payment',
            'gateway_event_id' => $gatewayEventId,
            'amount_minor' => $amountMinor,
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'confirmed',
            'changed_by' => null,
        ]);
    });
} 
}