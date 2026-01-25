<?php

use App\Livewire\About;
use App\Livewire\Blog;
use App\Livewire\Checkout;
use App\Livewire\Contact;
use App\Livewire\HomePage;
use App\Livewire\ReturnAndRefund;
use App\Livewire\Shop;
use App\Livewire\ViewBlog;
use App\Livewire\ViewCart;
use App\Livewire\ViewProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::redirect('/app', '/admin');

Route::get('/', HomePage::class)->name('home');

Route::get('/shop', Shop::class)->name('shop');

Route::get('/product/{id}', ViewProduct::class)->name('product.view');

Route::get('/about', About::class)->name('about');

Route::get('/contact', Contact::class)->name('contact');

Route::get('/blog', Blog::class)->name('blog');

Route::get('/blog/{id}', ViewBlog::class)->name('blog.view');

Route::get('/return-and-refund', ReturnAndRefund::class)->name('return-and-refund');

Route::get('/view-cart', ViewCart::class)->name('view-cart');

Route::get('/orders', \App\Livewire\Orders::class)->name('orders')->middleware('auth');
Route::get('/orders/{id}', \App\Livewire\OrderDetail::class)->name('order.detail')->middleware('auth');

Route::get('/track-order', [\App\Http\Controllers\TrackOrderController::class, 'index'])->name('track-order');
Route::post('/track-order', [\App\Http\Controllers\TrackOrderController::class, 'search'])->name('track-order.search');

Route::get('/checkout', Checkout::class)->name('checkout');

Route::get('/checkout/success/{order}', \App\Livewire\CheckoutSuccess::class)->name('checkout.success');
Route::get('/checkout/failed/{order}', \App\Livewire\CheckoutFailed::class)->name('checkout.failed');
Route::get('/checkout/bank-transfer/{order}', \App\Livewire\BankTransferInstructions::class)->name('checkout.bank-transfer');
Route::post('/webhooks/paymongo', [\App\Http\Controllers\PayMongoWebhookController::class, 'handle'])->name('webhooks.paymongo');

// Manual webhook testing route (local development only)
if (app()->environment('local')) {
    Route::get('/test-webhook/{order}', function ($order, Request $request) {
        $event = $request->query('event', 'paid');
        $sourceId = $request->query('source_id');
        $intentId = $request->query('intent_id');
        
        $orderModel = \App\Models\Order::find($order);
        if (!$orderModel) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        // Use existing payment IDs from order if not provided
        $paymentIntentId = $intentId ?: $orderModel->payment_intent_id;
        $paymentSourceId = $sourceId ?: $orderModel->payment_source_id;
        
        // Build webhook payload
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
        $mockRequest = Request::create('/webhooks/paymongo', 'POST', [], [], [], [], json_encode($webhookPayload));
        $mockRequest->headers->set('Content-Type', 'application/json');
        $mockRequest->headers->set('Paymongo-Signature', 'test-signature-local-development');
        
        // Process the webhook
        $controller = new \App\Http\Controllers\PayMongoWebhookController();
        $response = $controller->handle($mockRequest);
        
        // Refresh order to get updated status
        $orderModel->refresh();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Webhook processed',
            'order' => [
                'id' => $orderModel->id,
                'order_number' => $orderModel->order_number,
                'status' => $orderModel->status,
                'payment_status' => $orderModel->payment_status,
            ],
            'webhook_response' => json_decode($response->getContent(), true),
        ]);
    })->name('test.webhook');
}

// Authentication routes (using Breeze's secure authentication)
require __DIR__.'/auth.php';

// Route to serve private storage files
Route::get('/storage/private/{path}', function ($path) {
    $file = Storage::disk('local')->get($path);
    $mimeType = Storage::disk('local')->mimeType($path);

    return response($file, 200)
        ->header('Content-Type', $mimeType);
})->where('path', '.*');
