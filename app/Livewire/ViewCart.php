<?php

namespace App\Livewire;

use App\Services\CartService;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class ViewCart extends Component
{
    protected $listeners = [
        'cartUpdated' => '$refresh',
        'removeItem' => 'removeItem',
    ];

    public function removeItem($productId)
    {
        app(CartService::class)->removeFromCart($productId);
        $this->dispatch('cartUpdated', message: 'Item removed from cart!');
    }

    public function updateQuantity($productId, $quantity)
    {
        app(CartService::class)->updateQuantity($productId, $quantity);
        $this->dispatch('cartUpdated', message: 'Cart updated successfully!');
    }

    public function render()
    {
        $cartService = app(CartService::class);
        $cartItems = $cartService->getCartItems();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        
        return view('livewire.view-cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);
    }
}
