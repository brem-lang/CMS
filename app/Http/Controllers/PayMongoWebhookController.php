<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
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

            Log::info('PayMongo Webhook: Processing event', [
                'event_type' => $eventType,
            ]);

            if ($eventType === 'payment.paid' || $eventType === 'checkout_session.payment.paid') {
                return $this->handlePaymentPaid($event, $eventType);
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

    private function handlePaymentPaid($event, $eventType = 'payment.paid')
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
            ]);

            // Idempotency check: skip if already processed
            if ($order->payment_status === 'paid' && $order->status === 'processing') {
                Log::info('PayMongo Webhook: Order already processed, skipping', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);

                return response()->json(['status' => 'already_processed', 'order_id' => $order->id]);
            }

            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);

            Log::info('PayMongo Webhook: Order updated successfully', [
                'order_id' => $order->id,
                'new_status' => $order->status,
                'new_payment_status' => $order->payment_status,
            ]);

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
                Log::warning('PayMongo Webhook: Order not found (failed)', [
                    'event_type' => $eventType,
                    'payment_intent_id' => $paymentIntentId,
                    'source_id' => $sourceId,
                    'checkout_session_id' => $checkoutSessionId,
                    'order_id_from_metadata' => $orderIdFromMetadata,
                ]);

                // Return 200 OK to prevent PayMongo from retrying
                return response()->json(['status' => 'order_not_found'], 200);
            }

            Log::info('PayMongo Webhook: Order found (failed)', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'current_payment_status' => $order->payment_status,
            ]);

            // Idempotency check: skip if already marked as failed
            if ($order->payment_status === 'failed') {
                Log::info('PayMongo Webhook: Order already marked as failed, skipping', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);

                return response()->json(['status' => 'already_processed', 'order_id' => $order->id]);
            }

            $order->update([
                'payment_status' => 'failed',
            ]);

            Log::info('PayMongo Webhook: Order marked as failed', [
                'order_id' => $order->id,
            ]);

            return response()->json(['status' => 'success', 'order_id' => $order->id]);
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
     * PayMongo signature format: t=timestamp,v1=signature
     * Signed payload: timestamp.raw_payload
     * Signature: HMAC SHA256 of signed payload using webhook secret
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
        // PayMongo uses 'te' for the signature hash
        $providedSignature = $signatureParts['te'] ?? null;

        if (! $timestamp || ! $providedSignature) {
            Log::error('PayMongo Webhook: Invalid signature format', [
                'signature_header' => $signatureHeader,
                'parsed_parts' => $signatureParts,
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
            ]);
        }

        return $isValid;
    }
}
