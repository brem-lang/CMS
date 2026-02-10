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

    public function removeDigitalItem($digitalProductId)
    {
        app(CartService::class)->removeDigitalProductFromCart($digitalProductId);
        $this->dispatch('cartUpdated', message: 'Item removed from cart!');
        session()->flash('message', 'Item removed from cart!');
    }

    public function updateQuantity($productId, $quantity, $selectedSize = null, $selectedColor = null)
    {
        app(CartService::class)->updateQuantity($productId, $quantity, $selectedSize, $selectedColor);
        $this->dispatch('cartUpdated', message: 'Cart updated successfully!');
    }

    public function updateDigitalQuantity($digitalProductId, $quantity)
    {
        app(CartService::class)->updateDigitalQuantity($digitalProductId, $quantity);
        $this->dispatch('cartUpdated', message: 'Cart updated successfully!');
    }

    public function render()
    {
        $cartService = app(CartService::class);
        $cartItems = $cartService->getCartItems();
        $total = $cartItems->sum(function ($item) {
            if ($item->type === 'product' && $item->product) {
                return $item->quantity * (float) $item->product->price;
            }
            if ($item->type === 'digital' && $item->digitalProduct) {
                return $item->quantity * (float) ($item->digitalProduct->price ?? 0);
            }
            return 0;
        });

        return view('livewire.cart-sidebar', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }
}
