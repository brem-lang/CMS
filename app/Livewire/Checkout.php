<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PayMongoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layout.app')]
class Checkout extends Component
{
    public $fullName = '';

    public $country = '';

    public $address = '';

    public $addressDetails = '';

    public $town = '';

    public $state = '';

    public $postcode = '';

    public $phone = '';

    public $email = '';

    public $password = '';

    public $orderNotes = '';

    // Payment method selection removed - Checkout Session handles this

    public $cartItems = [];

    public $subtotal = 0;

    public $total = 0;

    public function mount()
    {
        $this->loadCartItems();

        if (Auth::check()) {
            $auth = Auth::user();
            $this->email = $auth->email ?? '';
            $this->fullName = $auth->name ?? '';
        }
    }

    public function loadCartItems()
    {
        $cartService = app(CartService::class);
        $this->cartItems = $cartService->getCartItems();

        $this->subtotal = $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $this->total = $this->subtotal;
    }

    public function placeOrder()
    {
        // Rate limiting: max 5 orders per minute per IP/user
        $rateLimitKey = 'placeOrder:'.(Auth::id() ?? request()->ip());
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            session()->flash('error', "Too many checkout attempts. Please try again in {$seconds} seconds.");

            return;
        }
        RateLimiter::hit($rateLimitKey, 60); // 60 seconds window

        // Enhanced validation with security measures
        $validationRules = [
            'fullName' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'town' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postcode' => 'required|string|max:20',
            'phone' => ['required', 'string', 'max:20', 'regex:/^(\+?63|0)?[9]\d{2}[\s\-\(\)]?\d{3}[\s\-\(\)]?\d{4}$|^0\d{1,3}[\s\-\(\)]?\d{3}[\s\-\(\)]?\d{4}$/'],
            'email' => 'required|email|max:255',
        ];

        // For authenticated users, enforce email and name from account
        if (Auth::check()) {
            $user = Auth::user();
            $this->email = $user->email ?? '';
            $this->fullName = $user->name ?? '';
        }

        $this->validate($validationRules);

        if ($this->cartItems->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');

            return;
        }

        // Re-verify product prices from database to prevent price manipulation
        $verifiedCartItems = [];
        $verifiedSubtotal = 0;

        foreach ($this->cartItems as $cartItem) {
            $product = \App\Models\Product::find($cartItem->product_id);

            if ($product->stock_quantity <= 0) {
                session()->flash('error', 'One or more products in your cart are out of stock. Please update your cart.');

                return;
            }

            if (! $product || ! $product->status) {
                session()->flash('error', 'One or more products in your cart are no longer available.');

                return;
            }

            // Use verified price from database, not from cart
            $verifiedPrice = $product->price;
            $quantity = $cartItem->quantity;
            $itemSubtotal = $verifiedPrice * $quantity;

            $verifiedCartItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $verifiedPrice,
                'subtotal' => $itemSubtotal,
                'selected_size' => $cartItem->selected_size ?? null,
                'selected_color' => $cartItem->selected_color ?? null,
            ];

            $verifiedSubtotal += $itemSubtotal;
        }

        // Sanitize user input to prevent XSS
        $sanitizedData = [
            'order_number' => Order::generateOrderNumber(),
            'user_id' => Auth::id(),
            'email' => filter_var(trim($this->email), FILTER_SANITIZE_EMAIL),
            'full_name' => strip_tags(trim($this->fullName)),
            'phone' => preg_replace('/[^0-9\s\-\+\(\)]/', '', trim($this->phone)),
            'address' => strip_tags(trim($this->address.($this->addressDetails ? ', '.$this->addressDetails : ''))),
            'town' => strip_tags(trim($this->town)),
            'state' => strip_tags(trim($this->state)),
            'postcode' => strip_tags(trim($this->postcode)),
            'country' => strip_tags(trim($this->country ?: 'Philippines')),
            'order_notes' => strip_tags(trim($this->orderNotes ?? '')),
            'subtotal' => $verifiedSubtotal,
            'total' => $verifiedSubtotal,
            'payment_method' => 'checkout_session',
            'items' => $verifiedCartItems,
        ];

        // Create order with sanitized and verified data
        $order = Order::create($sanitizedData);

        // Create order items (pivot table records) with verified prices
        foreach ($verifiedCartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'selected_size' => $item['selected_size'],
                'selected_color' => $item['selected_color'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // Process payment using Checkout Session
        try {
            $paymongoService = app(PayMongoService::class);

            // Get configured payment methods from config
            $paymentMethods = config('services.paymongo.payment_methods', ['gcash', 'card']);

            // Validate that at least one payment method is configured
            if (empty($paymentMethods) || !is_array($paymentMethods)) {
                Log::error('No payment methods configured for PayMongo checkout');
                session()->flash('error', 'Payment methods are not configured. Please contact support.');

                // Delete the order if payment methods are not configured
                if (isset($order) && $order->exists) {
                    try {
                        $order->delete();
                        Log::info('Order #'.$order->order_number.' deleted due to missing payment method configuration');
                    } catch (\Exception $deleteException) {
                        Log::error('Failed to delete order after payment method configuration error: '.$deleteException->getMessage());
                    }
                }
                return;
            }

            // Prepare line items for checkout session using verified prices
            $lineItems = collect($verifiedCartItems)->map(function ($item) {
                return [
                    'name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'amount' => $item['price'],
                    'currency' => 'PHP',
                ];
            })->toArray();

            // Create checkout session
            // Valid PayMongo checkout session payment method types: gcash, grab_pay, paymaya, card, shopee_pay, qrph, etc.
            $checkoutSession = $paymongoService->createCheckoutSession(
                $lineItems,
                route('checkout.success', ['order' => $order->id]),
                route('checkout.failed', ['order' => $order->id]),
                $paymentMethods, // Use configured payment methods from config
                "Order #{$order->order_number}",
                [
                    'order_id' => (string) $order->id,
                    'order_number' => $order->order_number,
                ],
                [
                    'name' => $sanitizedData['full_name'],
                    'email' => $sanitizedData['email'],
                    'phone' => $sanitizedData['phone'],
                ]
            );

            $order->update([
                'checkout_session_id' => $checkoutSession['id'],
            ]);

            // Clear rate limiter on successful order creation
            RateLimiter::clear($rateLimitKey);

            // Store order ID in session for guest users (so we can clear cart after webhook confirms payment)
            if (! Auth::check()) {
                session()->put('pending_order_id', $order->id);
            }

            // Redirect to checkout session URL
            return redirect($checkoutSession['attributes']['checkout_url']);
        } catch (\Exception $e) {
            Log::error('Payment processing error: '.$e->getMessage());

            // Delete the order and its related records if payment processing fails
            // OrderItems and OrderStatusHistory will be cascade deleted automatically
            if (isset($order) && $order->exists) {
                try {
                    $order->delete();
                    Log::info('Order #'.$order->order_number.' deleted due to payment processing failure');
                } catch (\Exception $deleteException) {
                    Log::error('Failed to delete order after payment error: '.$deleteException->getMessage());
                }
            }

            session()->flash('error', 'Payment processing failed. Please try again.');

            return;
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
