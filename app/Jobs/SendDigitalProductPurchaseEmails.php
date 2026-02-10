<?php

namespace App\Jobs;

use App\Mail\DigitalProductPurchaseMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendDigitalProductPurchaseEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $orderId;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle(): void
    {
        $order = Order::with(['orderItems.digitalProduct'])->find($this->orderId);

        if (! $order) {
            Log::warning('SendDigitalProductPurchaseEmails: Order not found', ['order_id' => $this->orderId]);

            return;
        }

        if ($order->payment_status !== 'paid' || $order->status !== 'processing') {
            Log::info('SendDigitalProductPurchaseEmails: Order not paid/processing, skipping', [
                'order_id' => $order->id,
                'payment_status' => $order->payment_status,
                'status' => $order->status,
            ]);

            return;
        }

        $digitalItems = $order->orderItems->filter(fn ($item) => $item->digital_product_id !== null && $item->digitalProduct);
        $hasPhysicalItems = $order->orderItems->contains(fn ($item) => $item->product_id !== null);

        foreach ($digitalItems as $orderItem) {
            try {
                if (! $orderItem->receipt_id) {
                    $orderItem->receipt_id = 'RCP-' . $order->order_number . '-' . $orderItem->id;
                    $orderItem->save();
                }

                // Do not append any query params after this — it would break the signed URL signature
                $downloadUrl = URL::temporarySignedRoute(
                    'digital-product.download.paid',
                    now()->addDays(30),
                    ['orderItem' => $orderItem->id],
                    absolute: true
                );

                Mail::to($order->email)->send(new DigitalProductPurchaseMail(
                    $orderItem,
                    $orderItem->receipt_id,
                    $downloadUrl
                ));

                Log::info('SendDigitalProductPurchaseEmails: Sent digital product email', [
                    'order_id' => $order->id,
                    'order_item_id' => $orderItem->id,
                    'receipt_id' => $orderItem->receipt_id,
                    'email' => $order->email,
                ]);
            } catch (\Throwable $e) {
                Log::error('SendDigitalProductPurchaseEmails: Failed to send email for order item', [
                    'order_id' => $order->id,
                    'order_item_id' => $orderItem->id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }

        // Digital-only orders: set status to delivered (Complete) after sending emails
        if (! $hasPhysicalItems && $digitalItems->isNotEmpty()) {
            $order->update(['status' => 'delivered']);
            Log::info('SendDigitalProductPurchaseEmails: Order marked delivered (digital only)', [
                'order_id' => $order->id,
            ]);
        }
    }
}
