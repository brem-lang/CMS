<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;

class CartSidebar extends Component
{
    public $show = false;

    protected $listeners = [
        'cartUpdated' => '$refresh',
        'openCartSidebar' => 'open'
    ];

    public function mount()
    {
        $this->show = false;
    }

    public function open()
    {
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
    }

    public function removeItem($productId, $selectedSize = null, $selectedColor = null)
    {
        app(CartService::class)->removeFromCart($productId, $selectedSize, $selectedColor);
        $this->dispatch('cartUpdated', message: 'Item removed from cart!');
        session()->flash('message', 'Item removed from cart!');
    }

    public function updateQuantity($productId, $quantity, $selectedSize = null, $selectedColor = null)
    {
        app(CartService::class)->updateQuantity($productId, $quantity, $selectedSize, $selectedColor);
        $this->dispatch('cartUpdated', message: 'Cart updated successfully!');
    }

    public function render()
    {
        $cartService = app(CartService::class);
        $cartItems = $cartService->getCartItems();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        
        return view('livewire.cart-sidebar', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }
}
