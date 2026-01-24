<?php

namespace App\Console\Commands;

use App\Http\Controllers\PayMongoWebhookController;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestPayMongoWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymongo:test-webhook {order} {event=paid} {--source-id=} {--intent-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate PayMongo webhook events for testing (payment.paid or payment.failed)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order');
        $event = $this->argument('event');
        $sourceId = $this->option('source-id');
        $intentId = $this->option('intent-id');

        // Validate event type
        if (!in_array($event, ['paid', 'failed'])) {
            $this->error("Event must be 'paid' or 'failed'");
            return 1;
        }

        // Find the order
        $order = Order::find($orderId);
        if (!$order) {
            $this->error("Order #{$orderId} not found");
            return 1;
        }

        $this->info("Testing webhook for Order #{$order->order_number} (ID: {$orderId})");
        $this->info("Event: payment.{$event}");

        // Use existing payment IDs from order if not provided
        $paymentIntentId = $intentId ?: $order->payment_intent_id;
        $paymentSourceId = $sourceId ?: $order->payment_source_id;

        // Build webhook payload structure matching PayMongo format
        $webhookPayload = [
            'data' => [
                'type' => "payment.{$event}",
                'attributes' => [
                    'data' => [
                        'attributes' => [
                            'payment_intent_id' => $paymentIntentId,
                            'source' => [
                                'id' => $paymentSourceId,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // Create a mock request
        $request = Request::create('/webhooks/paymongo', 'POST', [], [], [], [], json_encode($webhookPayload));
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Paymongo-Signature', 'test-signature-local-development');

        // Process the webhook
        $controller = new PayMongoWebhookController();
        $response = $controller->handle($request);

        // Check response
        if ($response->getStatusCode() === 200) {
            $this->info("✓ Webhook processed successfully");
            
            // Refresh order to see updated status
            $order->refresh();
            $this->info("Order status: {$order->status}");
            $this->info("Payment status: {$order->payment_status}");
            
            return 0;
        } else {
            $this->error("✗ Webhook processing failed");
            return 1;
        }
    }
}
