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
        
        // Rely solely on webhook-updated order status
        // If order is marked as paid (by webhook), clear cart
        if ($this->order->payment_status === 'paid') {
            $this->clearCart();
            
            // Remove pending order ID from session if it exists (for guests)
            if (!$this->order->user_id) {
                session()->forget('pending_order_id');
            }
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
