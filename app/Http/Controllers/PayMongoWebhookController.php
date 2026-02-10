<?php

namespace App\Http\Controllers;

use App\Jobs\SendDigitalProductPurchaseEmails;
use App\Models\FailedPayment;
use App\Models\FailedPaymentItem;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayMongoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('PayMongo Webhook Received', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'raw_payload' => $request->getContent(),
            'ip' => $request->ip(),
        ]);

        try {
            $signature = $request->header('Paymongo-Signature');
            $payload = $request->getContent();

            if (! $this->verifySignature($signature, $payload)) {
                Log::warning('PayMongo Webhook: Signature verification failed', [
                    'signature_header' => $signature,
                ]);

                return response()->json(['error' => 'Invalid signature'], 401);
            }

            $event = $request->json('data');

            if (! $event) {
                Log::warning('PayMongo Webhook: Invalid payload structure', [
                    'payload' => $request->all(),
                ]);

                return response()->json(['error' => 'Invalid payload'], 400);
            }

            // Event type is at event['attributes']['type']
            // PayMongo webhook structure: { "data": { "type": "event", "attributes": { "type": "payment.paid", ... } } }
            $eventType = $event['attributes']['type'] ?? null;

            // Extract payment method based on event type
            $paymentUsed = null;
            if ($eventType === 'checkout_session.payment.paid') {
                // For checkout_session events: data.attributes.data.attributes.payment_method_used
                $paymentUsed = $event['attributes']['data']['attributes']['payment_method_used'] ?? null;
            } elseif ($eventType === 'payment.paid') {
                // For payment.paid events: payment_method_used doesn't exist, get from source.type
                $paymentUsed = $event['attributes']['data']['attributes']['source']['type'] ?? null;
            }

            Log::info('PayMongo Webhook: Processing event', [
                'event_type' => $eventType,
                'payment_method' => $paymentUsed,
            ]);

            if ($eventType === 'payment.paid' || $eventType === 'checkout_session.payment.paid') {
                return $this->handlePaymentPaid($event, $eventType, $paymentUsed);
            } elseif ($eventType === 'payment.failed' || $eventType === 'checkout_session.payment.failed') {
                return $this->handlePaymentFailed($event, $eventType);
            } else {
                Log::warning('PayMongo Webhook: Unknown event type', [
                    'event_type' => $eventType,
                ]);

                return response()->json(['status' => 'ignored', 'message' => 'Unknown event type'], 200);
            }
        } catch (\Exception $e) {
            Log::error('PayMongo Webhook: Processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle payment.paid webhook event
     *
     * When payment succeeds:
     * - Order is marked as paid (payment_status = 'paid', status = 'processing')
     * - Cart IS cleared (items removed after successful payment)
     * - Order record remains in database for tracking
     *
     * @param  array  $event
     * @param  string  $eventType
     * @param  string|null  $paymentUsed
     * @return \Illuminate\Http\JsonResponse
     */
    private function handlePaymentPaid($event, $eventType = 'payment.paid', $paymentUsed = null)
    {
        try {
            $payment = $event['attributes']['data'] ?? null;

            if (! $payment) {
                Log::warning('PayMongo Webhook: payment.paid event missing payment data');

                return response()->json(['error' => 'Invalid payment data'], 400);
            }

            $paymentIntentId = $payment['attributes']['payment_intent_id'] ?? null;
            $sourceId = $payment['attributes']['source']['id'] ?? null;
            $checkoutSessionId = null;
            $orderIdFromMetadata = null;

            // For checkout session events, extract checkout session ID from event data
            if ($eventType === 'checkout_session.payment.paid') {
                // The checkout session ID is the ID of the checkout session object itself
                $checkoutSessionId = $event['attributes']['data']['id'] ?? null;
                // Also check metadata for order_id
                $orderIdFromMetadata = $event['attributes']['data']['attributes']['metadata']['order_id'] ?? null;
            } else {
                // For payment.paid events, checkout session ID is in payment attributes
                $checkoutSessionId = $payment['attributes']['checkout_session_id'] ?? null;
                // Also check metadata for order_id
                $orderIdFromMetadata = $payment['attributes']['metadata']['order_id'] ?? null;
            }

            Log::info('PayMongo Webhook: Searching for order', [
                'event_type' => $eventType,
                'payment_intent_id' => $paymentIntentId,
                'source_id' => $sourceId,
                'checkout_session_id' => $checkoutSessionId,
                'order_id_from_metadata' => $orderIdFromMetadata,
            ]);

            // Find order by checkout session ID, payment intent, source ID, or metadata order_id
            $order = null;
            if ($checkoutSessionId) {
                $order = Order::where('checkout_session_id', $checkoutSessionId)->first();
            }
            if (! $order && $orderIdFromMetadata) {
                // Cast to integer to ensure proper lookup
                $orderIdFromMetadata = (int) $orderIdFromMetadata;
                $order = Order::find($orderIdFromMetadata);
            }
            if (! $order && $paymentIntentId) {
                $order = Order::where('payment_intent_id', $paymentIntentId)->first();
            }
            if (! $order && $sourceId) {
                $order = Order::where('payment_source_id', $sourceId)->first();
            }

            if (! $order) {
                Log::warning('PayMongo Webhook: Order not found', [
                    'event_type' => $eventType,
                    'payment_intent_id' => $paymentIntentId,
                    'source_id' => $sourceId,
                    'checkout_session_id' => $checkoutSessionId,
                    'order_id_from_metadata' => $orderIdFromMetadata,
                ]);

                // Return 200 OK to prevent PayMongo from retrying
                return response()->json(['status' => 'order_not_found'], 200);
            }

            Log::info('PayMongo Webhook: Order found', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'current_status' => $order->status,
                'current_payment_status' => $order->payment_status,
                'payment_intent_id' => $paymentIntentId,
            ]);

            // Idempotency check: skip if already processed
            if ($order->payment_status === 'paid' && $order->status === 'processing') {
                Log::info('PayMongo Webhook: Order already processed, skipping', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);

                return response()->json(['status' => 'already_processed', 'order_id' => $order->id]);
            }

            Log::info('PayMongo Webhook: Extracted payment method', [
                'order_id' => $order->id,
                'payment_method' => $paymentUsed,
                'event_type' => $eventType,
            ]);

            // Prepare update data
            $updateData = [
                'payment_status' => 'paid',
                'status' => 'processing',
                'payment_intent_id' => $paymentIntentId,
            ];
            logger($paymentIntentId);

            // Only update payment_method if we have a valid value
            if ($paymentUsed) {
                $updateData['payment_method'] = $paymentUsed;
            }

            $order->update($updateData);

            Log::info('PayMongo Webhook: Order updated successfully', [
                'order_id' => $order->id,
                'new_status' => $order->status,
                'new_payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
            ]);

            // Deduct stock quantity from products based on order items (skip digital product items)
            try {
                $order->load('orderItems.product.variants');

                DB::transaction(function () use ($order) {
                    foreach ($order->orderItems as $orderItem) {
                        // Skip digital product items (no stock to deduct)
                        if ($orderItem->digital_product_id !== null || $orderItem->product_id === null) {
                            continue;
                        }
                        $product = $orderItem->product;

                        if (! $product) {
                            Log::warning('PayMongo Webhook: Product not found for order item', [
                                'order_id' => $order->id,
                                'order_item_id' => $orderItem->id,
                                'product_id' => $orderItem->product_id,
                            ]);
                            continue;
                        }

                        $quantityToDeduct = $orderItem->quantity;
                        $selectedSize = $orderItem->selected_size;
                        $selectedColor = $orderItem->selected_color;

                        // Check if product has variants
                        $hasVariants = $product->variants()->exists();

                        if ($hasVariants && ($selectedSize || $selectedColor)) {
                            // Product has variants - deduct from specific variant
                            $variant = $product->variants()
                                ->where(function ($query) use ($selectedSize, $selectedColor) {
                                    if ($selectedSize) {
                                        $query->where('size', $selectedSize);
                                    } else {
                                        $query->whereNull('size');
                                    }
                                    if ($selectedColor) {
                                        $query->where('color', $selectedColor);
                                    } else {
                                        $query->whereNull('color');
                                    }
                                })
                                ->first();

                            if ($variant) {
                                $variantCurrentStock = $variant->quantity ?? 0;

                                // Check if sufficient variant stock exists
                                if ($variantCurrentStock < $quantityToDeduct) {
                                    Log::warning('PayMongo Webhook: Insufficient variant stock', [
                                        'order_id' => $order->id,
                                        'order_item_id' => $orderItem->id,
                                        'product_id' => $product->id,
                                        'variant_id' => $variant->id,
                                        'size' => $selectedSize,
                                        'color' => $selectedColor,
                                        'current_variant_stock' => $variantCurrentStock,
                                        'requested_quantity' => $quantityToDeduct,
                                    ]);
                                    // Still deduct what's available (or set to 0) - this is a payment success scenario
                                    $quantityToDeduct = max(0, $variantCurrentStock);
                                }

                                // Deduct from variant quantity
                                $variantRowsAffected = \App\Models\ProductVariant::where('id', $variant->id)
                                    ->where('quantity', '>=', $quantityToDeduct)
                                    ->decrement('quantity', $quantityToDeduct);

                                if ($variantRowsAffected > 0) {
                                    Log::info('PayMongo Webhook: Variant stock deducted successfully', [
                                        'order_id' => $order->id,
                                        'order_item_id' => $orderItem->id,
                                        'product_id' => $product->id,
                                        'variant_id' => $variant->id,
                                        'size' => $selectedSize,
                                        'color' => $selectedColor,
                                        'quantity_deducted' => $quantityToDeduct,
                                        'previous_variant_stock' => $variantCurrentStock,
                                        'new_variant_stock' => $variantCurrentStock - $quantityToDeduct,
                                    ]);

                                    // Recalculate and update product's total stock_quantity (sum of all variants)
                                    $totalStock = $product->variants()->sum('quantity');
                                    $product->update(['stock_quantity' => $totalStock]);

                                    Log::info('PayMongo Webhook: Product total stock updated', [
                                        'order_id' => $order->id,
                                        'product_id' => $product->id,
                                        'new_total_stock' => $totalStock,
                                    ]);
                                } else {
                                    Log::warning('PayMongo Webhook: Variant stock deduction failed - insufficient stock', [
                                        'order_id' => $order->id,
                                        'order_item_id' => $orderItem->id,
                                        'product_id' => $product->id,
                                        'variant_id' => $variant->id,
                                        'size' => $selectedSize,
                                        'color' => $selectedColor,
                                        'requested_quantity' => $quantityToDeduct,
                                        'current_variant_stock' => $variantCurrentStock,
                                    ]);
                                }
                            } else {
                                Log::warning('PayMongo Webhook: Variant not found for order item', [
                                    'order_id' => $order->id,
                                    'order_item_id' => $orderItem->id,
                                    'product_id' => $product->id,
                                    'size' => $selectedSize,
                                    'color' => $selectedColor,
                                ]);
                            }
                        } else {
                            // No variants - deduct from product's stock_quantity (existing logic)
                            $currentStock = $product->stock_quantity ?? 0;

                            // Check if sufficient stock exists
                            if ($currentStock < $quantityToDeduct) {
                                Log::warning('PayMongo Webhook: Insufficient stock for product', [
                                    'order_id' => $order->id,
                                    'order_item_id' => $orderItem->id,
                                    'product_id' => $product->id,
                                    'product_name' => $product->name,
                                    'current_stock' => $currentStock,
                                    'requested_quantity' => $quantityToDeduct,
                                ]);
                                // Still deduct what's available (or set to 0) - this is a payment success scenario
                                $quantityToDeduct = max(0, $currentStock);
                            }

                            // Use decrement() for atomic stock reduction
                            $rowsAffected = Product::where('id', $product->id)
                                ->where('stock_quantity', '>=', $quantityToDeduct)
                                ->decrement('stock_quantity', $quantityToDeduct);

                            if ($rowsAffected > 0) {
                                Log::info('PayMongo Webhook: Stock deducted successfully', [
                                    'order_id' => $order->id,
                                    'order_item_id' => $orderItem->id,
                                    'product_id' => $product->id,
                                    'product_name' => $product->name,
                                    'quantity_deducted' => $quantityToDeduct,
                                    'previous_stock' => $currentStock,
                                    'new_stock' => $currentStock - $quantityToDeduct,
                                ]);
                            } else {
                                Log::warning('PayMongo Webhook: Stock deduction failed - insufficient stock or product not found', [
                                    'order_id' => $order->id,
                                    'order_item_id' => $orderItem->id,
                                    'product_id' => $product->id,
                                    'product_name' => $product->name,
                                    'requested_quantity' => $quantityToDeduct,
                                    'current_stock' => $currentStock,
                                ]);
                            }
                        }
                    }
                });

                Log::info('PayMongo Webhook: Stock deduction completed for all order items', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'items_count' => $order->orderItems->count(),
                ]);
            } catch (\Exception $stockException) {
                Log::error('PayMongo Webhook: Error deducting stock', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'error' => $stockException->getMessage(),
                    'trace' => $stockException->getTraceAsString(),
                ]);
                // Don't throw - payment was successful, stock issue should be logged but not fail the webhook
            }

            // Send one email per digital product (receipt + signed download link)
            SendDigitalProductPurchaseEmails::dispatch($order->id);

            // Clear cart
            $cartService = app(CartService::class);
            if ($order->user_id) {
                $cartService->clearCart($order->user_id);
                Log::info('PayMongo Webhook: Cart cleared for user', [
                    'user_id' => $order->user_id,
                ]);
            } else {
                $cartService->clearCart();
                Log::info('PayMongo Webhook: Guest cart cleared');
            }

            return response()->json(['status' => 'success', 'order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('PayMongo Webhook: Error handling payment.paid', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle payment.failed webhook event
     *
     * When payment fails:
     * - Order is DELETED from the 'orders' table
     * - Order data is MIGRATED to the 'failed_payments' table
     * - OrderItems are COPIED to 'failed_payment_items'
     * - Cart is NOT cleared (items remain for retry)
     * - User can retry checkout with the same cart items
     *
     * @param  array  $event
     * @param  string  $eventType
     * @return \Illuminate\Http\JsonResponse
     */
    private function handlePaymentFailed($event, $eventType = 'payment.failed')
    {
        try {
            $payment = $event['attributes']['data'] ?? null;

            if (! $payment) {
                Log::warning('PayMongo Webhook: payment.failed event missing payment data');

                return response()->json(['error' => 'Invalid payment data'], 400);
            }

            $paymentIntentId = $payment['attributes']['payment_intent_id'] ?? null;
            $sourceId = $payment['attributes']['source']['id'] ?? null;
            $checkoutSessionId = null;
            $orderIdFromMetadata = null;

            // For checkout session events, extract checkout session ID from event data
            if ($eventType === 'checkout_session.payment.failed') {
                // The checkout session ID is the ID of the checkout session object itself
                $checkoutSessionId = $event['attributes']['data']['id'] ?? null;
                // Also check metadata for order_id
                $orderIdFromMetadata = $event['attributes']['data']['attributes']['metadata']['order_id'] ?? null;
            } else {
                // For payment.failed events, checkout session ID is in payment attributes
                $checkoutSessionId = $payment['attributes']['checkout_session_id'] ?? null;
                // Also check metadata for order_id
                $orderIdFromMetadata = $payment['attributes']['metadata']['order_id'] ?? null;
            }

            Log::info('PayMongo Webhook: Searching for order (failed)', [
                'event_type' => $eventType,
                'payment_intent_id' => $paymentIntentId,
                'source_id' => $sourceId,
                'checkout_session_id' => $checkoutSessionId,
                'order_id_from_metadata' => $orderIdFromMetadata,
            ]);

            $order = null;
            if ($checkoutSessionId) {
                $order = Order::where('checkout_session_id', $checkoutSessionId)->first();
            }
            if (! $order && $orderIdFromMetadata) {
                // Cast to integer to ensure proper lookup
                $orderIdFromMetadata = (int) $orderIdFromMetadata;
                $order = Order::find($orderIdFromMetadata);
            }
            if (! $order && $paymentIntentId) {
                $order = Order::where('payment_intent_id', $paymentIntentId)->first();
            }
            if (! $order && $sourceId) {
                $order = Order::where('payment_source_id', $sourceId)->first();
            }

            if (! $order) {
                Log::info('PayMongo Webhook: Order not found (failed) - may have already been deleted', [
                    'event_type' => $eventType,
                    'payment_intent_id' => $paymentIntentId,
                    'source_id' => $sourceId,
                    'checkout_session_id' => $checkoutSessionId,
                    'order_id_from_metadata' => $orderIdFromMetadata,
                ]);

                // Return 200 OK to prevent PayMongo from retrying
                // Order may have already been deleted, which is fine
                return response()->json(['status' => 'order_not_found_or_already_deleted'], 200);
            }

            // Eager load order items to ensure they are available for copying
            $order->load('orderItems');

            $orderId = $order->id;
            $orderNumber = $order->order_number;

            Log::info('PayMongo Webhook: Order found (failed) - migrating to failed_payments table', [
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'current_payment_status' => $order->payment_status,
            ]);

            // Extract failure reason from PayMongo payload
            $failureReason = $payment['attributes']['failed_message'] ?? null;
            $failedCode = $payment['attributes']['failed_code'] ?? null;

            // Update order with payment_intent_id from webhook if not already set
            if ($paymentIntentId && ! $order->payment_intent_id) {
                $order->update(['payment_intent_id' => $paymentIntentId]);
                Log::info('PayMongo Webhook: Updated order with payment_intent_id from webhook', [
                    'order_id' => $orderId,
                    'payment_intent_id' => $paymentIntentId,
                ]);
            }

            // Use payment_intent_id from webhook payload if order doesn't have it
            $finalPaymentIntentId = $order->payment_intent_id ?? $paymentIntentId;

            // Create FailedPayment record with all order data
            $failedPayment = null;
            try {
                $failedPayment = FailedPayment::create([
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id,
                    'email' => $order->email,
                    'full_name' => $order->full_name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'town' => $order->town,
                    'state' => $order->state,
                    'postcode' => $order->postcode,
                    'country' => $order->country,
                    'order_notes' => $order->order_notes,
                    'subtotal' => $order->subtotal,
                    'total' => $order->total,
                    'payment_method' => $order->payment_method,
                    'payment_status' => 'failed',
                    'payment_intent_id' => $finalPaymentIntentId,
                    'payment_source_id' => $order->payment_source_id ?? $sourceId,
                    'checkout_session_id' => $order->checkout_session_id ?? $checkoutSessionId,
                    'status' => 'cancelled',
                    'courier_id' => $order->courier_id,
                    'items' => $order->items,
                    'failed_at' => now(),
                    'failure_reason' => $failureReason ? ($failedCode ? "{$failedCode}: {$failureReason}" : $failureReason) : null,
                ]);

                Log::info('PayMongo Webhook: FailedPayment record created', [
                    'failed_payment_id' => $failedPayment->id,
                    'order_number' => $orderNumber,
                ]);

                // Copy OrderItems to FailedPaymentItems
                foreach ($order->orderItems as $orderItem) {
                    FailedPaymentItem::create([
                        'failed_payment_id' => $failedPayment->id,
                        'product_id' => $orderItem->product_id,
                        'quantity' => $orderItem->quantity,
                        'price' => $orderItem->price,
                        'subtotal' => $orderItem->subtotal,
                    ]);
                }

                Log::info('PayMongo Webhook: OrderItems copied to FailedPaymentItems', [
                    'failed_payment_id' => $failedPayment->id,
                    'items_count' => $order->orderItems->count(),
                ]);

                // Now delete the original order (cascades to order_items and order_status_history)
                $order->delete();

                Log::info('PayMongo Webhook: Order migrated to failed_payments and deleted from orders', [
                    'failed_payment_id' => $failedPayment->id,
                    'order_id' => $orderId,
                    'order_number' => $orderNumber,
                ]);
            } catch (\Exception $migrationException) {
                Log::error('PayMongo Webhook: Failed to migrate order to failed_payments', [
                    'order_id' => $orderId,
                    'order_number' => $orderNumber,
                    'error' => $migrationException->getMessage(),
                    'trace' => $migrationException->getTraceAsString(),
                ]);
                throw $migrationException;
            }

            // IMPORTANT: Do NOT clear the cart when payment fails
            // This allows the user to retry payment with the same items
            // The cart will only be cleared when payment succeeds (in handlePaymentPaid)
            Log::info('PayMongo Webhook: Cart preserved for failed payment - user can retry', [
                'failed_payment_id' => $failedPayment ? $failedPayment->id : null,
                'order_number' => $orderNumber,
            ]);

            return response()->json([
                'status' => 'success',
                'order_migrated' => true,
                'failed_payment_id' => $failedPayment ? $failedPayment->id : null,
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error('PayMongo Webhook: Error handling payment.failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Verify PayMongo webhook signature
     *
     * PayMongo signature format: t=timestamp,te=signature (or li=signature for line items)
     * Signed payload: timestamp.raw_payload
     * Signature: HMAC SHA256 of signed payload using webhook secret
     * 
     * Security features:
     * - Timestamp validation (5-minute window) prevents replay attacks
     * - HMAC SHA256 ensures payload integrity
     * - hash_equals() prevents timing attacks
     *
     * @param  string|null  $signatureHeader
     * @param  string  $payload
     * @return bool
     */
    private function verifySignature($signatureHeader, $payload)
    {
        // Skip verification if webhook secret is not configured (for local development)
        $webhookSecret = config('services.paymongo.webhook_secret');
        if (empty($webhookSecret)) {
            Log::warning('PayMongo Webhook: Webhook secret not configured, skipping signature verification');

            return true; // Allow in development, but log warning
        }

        if (empty($signatureHeader)) {
            Log::error('PayMongo Webhook: Missing signature header');

            return false;
        }

        // Parse signature header: format is "t=timestamp,te=signature,li=line_items"
        // PayMongo uses 'te' (timestamped event) for the signature, not 'v1'
        $signatureParts = [];
        foreach (explode(',', $signatureHeader) as $part) {
            $part = trim($part);
            if (strpos($part, '=') !== false) {
                [$key, $value] = explode('=', $part, 2);
                $signatureParts[$key] = $value;
            }
        }

        $timestamp = $signatureParts['t'] ?? null;
        // PayMongo uses 'te' for signature hash, but live webhooks may use 'li'
        // Check 'te' first, fallback to 'li' if 'te' is empty
        $providedSignature = null;
        $signatureField = null;

        if (!empty($signatureParts['te'])) {
            $providedSignature = $signatureParts['te'];
            $signatureField = 'te';
        } elseif (!empty($signatureParts['li'])) {
            $providedSignature = $signatureParts['li'];
            $signatureField = 'li';
        }

        if (! $timestamp || ! $providedSignature) {
            Log::error('PayMongo Webhook: Invalid signature format', [
                'signature_header' => $signatureHeader,
                'parsed_parts' => $signatureParts,
                'signature_field_used' => $signatureField,
            ]);

            return false;
        }

        // Check timestamp is recent (within 5 minutes) to prevent replay attacks
        $currentTime = time();
        $requestTime = (int) $timestamp;
        $timeDifference = abs($currentTime - $requestTime);

        if ($timeDifference > 300) { // 5 minutes
            Log::warning('PayMongo Webhook: Timestamp too old', [
                'timestamp' => $timestamp,
                'current_time' => $currentTime,
                'difference' => $timeDifference,
            ]);

            return false;
        }

        // Create signed payload: timestamp.raw_payload
        $signedPayload = $timestamp.'.'.$payload;

        // Compute expected signature using HMAC SHA256
        $expectedSignature = hash_hmac('sha256', $signedPayload, $webhookSecret);

        // Use hash_equals for timing-safe comparison
        $isValid = hash_equals($expectedSignature, $providedSignature);

        if (! $isValid) {
            Log::error('PayMongo Webhook: Signature mismatch', [
                'expected' => $expectedSignature,
                'provided' => $providedSignature,
                'timestamp' => $timestamp,
                'signature_field_used' => $signatureField,
            ]);
        }

        return $isValid;
    }
}
