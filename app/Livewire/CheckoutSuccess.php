<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class CheckoutSuccess extends Component
{
    public $order;

    public function mount($order)
    {
        $this->order = Order::findOrFail($order);
        
        // Check payment status from PayMongo if needed
        if ($this->order->payment_status === 'pending') {
            $paymongoService = app(\App\Services\PayMongoService::class);
            
            if ($this->order->payment_source_id) {
                $source = $paymongoService->getSource($this->order->payment_source_id);
                
                if ($source && isset($source['attributes']['status']) && $source['attributes']['status'] === 'paid') {
                    $this->order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                    ]);
                    
                    // Clear cart after successful payment
                    $this->clearCart();
                }
            } elseif ($this->order->payment_intent_id) {
                $paymentIntent = $paymongoService->getPaymentIntent($this->order->payment_intent_id);
                
                if ($paymentIntent && isset($paymentIntent['attributes']['status']) && $paymentIntent['attributes']['status'] === 'succeeded') {
                    $this->order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                    ]);
                    
                    // Clear cart after successful payment
                    $this->clearCart();
                }
            }
        } elseif ($this->order->payment_status === 'paid') {
            // If order is already marked as paid, ensure cart is cleared
            $this->clearCart();
        }
    }
    
    private function clearCart()
    {
        $cartService = app(CartService::class);
        if ($this->order->user_id) {
            $cartService->clearCart($this->order->user_id);
        } else {
            $cartService->clearCart();
        }
        
        // Dispatch cart updated event to refresh cart count and sidebar
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.checkout-success');
    }
}
