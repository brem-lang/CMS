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

    public function removeItem($productId, $selectedSize = null, $selectedColor = null)
    {
        app(CartService::class)->removeFromCart($productId, $selectedSize, $selectedColor);
        $this->dispatch('cartUpdated', message: 'Item removed from cart!');
    }

    public function removeDigitalItem($digitalProductId)
    {
        app(CartService::class)->removeDigitalProductFromCart($digitalProductId);
        $this->dispatch('cartUpdated', message: 'Item removed from cart!');
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
        $subtotal = $cartItems->sum(function ($item) {
            if ($item->type === 'product' && $item->product) {
                return $item->quantity * (float) $item->product->price;
            }
            if ($item->type === 'digital' && $item->digitalProduct) {
                return $item->quantity * (float) ($item->digitalProduct->price ?? 0);
            }
            return 0;
        });

        return view('livewire.view-cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);
    }
}
