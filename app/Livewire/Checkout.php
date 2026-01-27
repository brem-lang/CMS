<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PayMongoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $this->validate([
            'fullName' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postcode' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
        ]);
        
        if ($this->cartItems->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }
        
        // Create order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => Auth::id(),
            'email' => $this->email,
            'full_name' => $this->fullName,
            'phone' => $this->phone,
            'address' => $this->address . ($this->addressDetails ? ', ' . $this->addressDetails : ''),
            'town' => $this->town,
            'state' => $this->state,
            'postcode' => $this->postcode,
            'country' => $this->country ?: 'Philippines',
            'order_notes' => $this->orderNotes,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'payment_method' => 'checkout_session', // Using checkout session
            'items' => $this->cartItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->quantity * $item->product->price,
                ];
            })->toArray(),
        ]);
        
        // Create order items (pivot table records)
        foreach ($this->cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'subtotal' => $item->quantity * $item->product->price,
            ]);
        }
        
        // Process payment using Checkout Session
        try {
            $paymongoService = app(PayMongoService::class);
            
            // Prepare line items for checkout session
            $lineItems = $this->cartItems->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'amount' => $item->quantity * $item->product->price,
                    'currency' => 'PHP',
                ];
            })->toArray();
            
            // Create checkout session
            // Valid PayMongo checkout session payment method types: gcash, grab_pay, paymaya, card
            $checkoutSession = $paymongoService->createCheckoutSession(
                $lineItems,
                route('checkout.success', ['order' => $order->id]),
                route('checkout.failed', ['order' => $order->id]),
                ['gcash', 'grab_pay', 'paymaya'], // Payment method types (shopeepay not supported in checkout sessions)
                "Order #{$order->order_number}",
                [
                    'order_id' => (string)$order->id,
                    'order_number' => $order->order_number,
                ],
                [
                    'name' => $this->fullName,
                    'email' => $this->email,
                    'phone' => $this->phone,
                ]
            );
            
            $order->update([
                'checkout_session_id' => $checkoutSession['id'],
            ]);
            
            // Store order ID in session for guest users (so we can clear cart after webhook confirms payment)
            if (!Auth::check()) {
                session()->put('pending_order_id', $order->id);
            }
            
            // Redirect to checkout session URL
            return redirect($checkoutSession['attributes']['checkout_url']);
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            session()->flash('error', 'Payment processing failed. Please try again.');
            return;
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
