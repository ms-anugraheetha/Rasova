<?php

namespace App\Services;

use App\Exceptions\OversellException;
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
        try {
            DB::transaction(function () use ($orderId, $gatewayEventId, $amountMinor) {
                // Lock the order row so two webhook calls for the same order can't both process.
                $order = Order::lockForUpdate()->findOrFail($orderId);

                if ($order->payment_status === 'paid') {
                    // Already processed — a retried/duplicate webhook. Safe no-op.
                    return;
                }

                $items = $order->items()->get();

                // Lock variant rows in a consistent order (by id ascending) to avoid deadlocks
                // when multiple orders touching overlapping variants are processed concurrently.
                $variantIds = $items->pluck('product_variant_id')->sort()->values();
                $variants = ProductVariant::whereIn('id', $variantIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($items as $item) {
                    $variant = $variants[$item->product_variant_id];
                    if ($variant->stock_quantity < $item->quantity) {
                        throw new OversellException($variant->id, $item->quantity, $variant->stock_quantity);
                    }
                }

                foreach ($items as $item) {
                    $variants[$item->product_variant_id]->decrement('stock_quantity', $item->quantity);
                }

                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'confirmed',
                ]);

                $order->payment()->update(['status' => 'paid']);

                PaymentTransaction::create([
                    'payment_id' => $order->payment->id,
                    'transaction_type' => 'payment',
                    'gateway_event_id' => $gatewayEventId, // unique constraint = idempotency guard
                    'amount_minor' => $amountMinor,
                ]);

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'confirmed',
                    'changed_by' => null, // system-triggered, not an admin action
                ]);
            });
        } catch (OversellException $e) {
            // Rare: stock sold out between checkout and payment confirmation.
            // Payment already succeeded on the gateway side, so we can't just cancel silently —
            // flag for manual refund/restock review instead of losing money silently.
            Log::error('Oversell detected during payment confirmation', [
                'order_id' => $orderId,
                'variant_id' => $e->productVariantId,
                'requested' => $e->requested,
                'available' => $e->available,
            ]);

            Order::where('id', $orderId)->update([
                'payment_status' => 'paid',
                'order_status' => 'stock_issue', // needs a new enum value + admin workflow
            ]);
        }
    }
}