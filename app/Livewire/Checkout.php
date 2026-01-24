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

    public $paymentMethod = 'gcash'; // Default: gcash. Options: gcash, grabpay, maya, shopeepay
    
    /**
     * Update payment method selection
     */
    public function updatedPaymentMethod($value)
    {
        // Ensure valid payment method is selected
        if (!in_array($value, ['gcash', 'grabpay', 'maya', 'shopeepay'])) {
            $this->paymentMethod = 'gcash';
        }
    }

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
            'paymentMethod' => 'required|in:gcash,grabpay,maya,shopeepay',
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
            'payment_method' => $this->paymentMethod,
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
        
        // Process payment based on method
        try {
            $paymongoService = app(PayMongoService::class);
            
            // All payment methods (GCash, PayMaya, QR PH) use payment source
            $source = $paymongoService->createSource(
                $this->total,
                $this->paymentMethod,
                'PHP',
                [
                    'success' => route('checkout.success', ['order' => $order->id]),
                    'failed' => route('checkout.failed', ['order' => $order->id]),
                ]
            );
            
            $order->update([
                'payment_source_id' => $source['id'],
            ]);
            
            // Redirect to payment URL
            return redirect($source['attributes']['redirect']['checkout_url']);
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
