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
        // Log incoming webhook request
        Log::info('PayMongo Webhook Received', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'raw_payload' => $request->getContent(),
            'ip' => $request->ip(),
        ]);

        try {
            // Verify webhook signature
            $signature = $request->header('Paymongo-Signature');
            $payload = $request->getContent();
            
            // Verify signature (implement PayMongo signature verification)
            // For local development, we'll skip verification
            // In production, implement proper signature verification using PAYMONGO_WEBHOOK_SECRET
            
            $event = $request->json('data');
            
            if (!$event) {
                Log::warning('PayMongo Webhook: Invalid payload structure', [
                    'payload' => $request->all(),
                ]);
                return response()->json(['error' => 'Invalid payload'], 400);
            }

            $eventType = $event['type'] ?? null;
            
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
            
            if (!$payment) {
                Log::warning('PayMongo Webhook: payment.paid event missing payment data');
                return response()->json(['error' => 'Invalid payment data'], 400);
            }

            $paymentIntentId = $payment['attributes']['payment_intent_id'] ?? null;
            $sourceId = $payment['attributes']['source']['id'] ?? null;
            $checkoutSessionId = null;
            
            // For checkout session events, extract checkout session ID
            if ($eventType === 'checkout_session.payment.paid') {
                // Checkout session ID might be in different locations
                $checkoutSessionId = $payment['attributes']['checkout_session_id'] ?? 
                                    $event['attributes']['checkout_session_id'] ?? 
                                    $payment['checkout_session_id'] ?? null;
            }
            
            Log::info('PayMongo Webhook: Searching for order', [
                'event_type' => $eventType,
                'payment_intent_id' => $paymentIntentId,
                'source_id' => $sourceId,
                'checkout_session_id' => $checkoutSessionId,
            ]);
            
            // Find order by checkout session ID, payment intent, or source ID
            $order = null;
            if ($checkoutSessionId) {
                $order = Order::where('checkout_session_id', $checkoutSessionId)->first();
            }
            if (!$order && $paymentIntentId) {
                $order = Order::where('payment_intent_id', $paymentIntentId)->first();
            }
            if (!$order && $sourceId) {
                $order = Order::where('payment_source_id', $sourceId)->first();
            }
            
            if (!$order) {
                Log::warning('PayMongo Webhook: Order not found', [
                    'event_type' => $eventType,
                    'payment_intent_id' => $paymentIntentId,
                    'source_id' => $sourceId,
                    'checkout_session_id' => $checkoutSessionId,
                ]);
                return response()->json(['error' => 'Order not found'], 404);
            }
            
            Log::info('PayMongo Webhook: Order found', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'current_status' => $order->status,
                'current_payment_status' => $order->payment_status,
            ]);
                
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
            
            if (!$payment) {
                Log::warning('PayMongo Webhook: payment.failed event missing payment data');
                return response()->json(['error' => 'Invalid payment data'], 400);
            }

            $paymentIntentId = $payment['attributes']['payment_intent_id'] ?? null;
            $sourceId = $payment['attributes']['source']['id'] ?? null;
            $checkoutSessionId = null;
            
            // For checkout session events, extract checkout session ID
            if ($eventType === 'checkout_session.payment.failed') {
                $checkoutSessionId = $payment['attributes']['checkout_session_id'] ?? 
                                    $event['attributes']['checkout_session_id'] ?? 
                                    $payment['checkout_session_id'] ?? null;
            }
            
            Log::info('PayMongo Webhook: Searching for order (failed)', [
                'event_type' => $eventType,
                'payment_intent_id' => $paymentIntentId,
                'source_id' => $sourceId,
                'checkout_session_id' => $checkoutSessionId,
            ]);
            
            $order = null;
            if ($checkoutSessionId) {
                $order = Order::where('checkout_session_id', $checkoutSessionId)->first();
            }
            if (!$order && $paymentIntentId) {
                $order = Order::where('payment_intent_id', $paymentIntentId)->first();
            }
            if (!$order && $sourceId) {
                $order = Order::where('payment_source_id', $sourceId)->first();
            }
            
            if (!$order) {
                Log::warning('PayMongo Webhook: Order not found (failed)', [
                    'event_type' => $eventType,
                    'payment_intent_id' => $paymentIntentId,
                    'source_id' => $sourceId,
                    'checkout_session_id' => $checkoutSessionId,
                ]);
                return response()->json(['error' => 'Order not found'], 404);
            }
            
            Log::info('PayMongo Webhook: Order found (failed)', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
                
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
}
