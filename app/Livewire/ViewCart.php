<?php

namespace App\Livewire;

use App\Models\Cart;
use App\View\Components\Layout\App;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class ViewCart extends Component
{
    protected $listeners = [
        'cartUpdated' => '$refresh',
        'removeItem' => 'removeItem',
    ];

    public function removeItem($cartId)
    {
        if (Auth::check()) {
            Cart::where('id', $cartId)
                ->where('user_id', Auth::id())
                ->delete();

            $this->dispatch('cartUpdated', message: 'Item removed from cart!');
        }
    }

    public function updateQuantity($cartId, $quantity)
    {
        if (Auth::check() && $quantity > 0) {
            Cart::where('id', $cartId)
                ->where('user_id', Auth::id())
                ->update(['quantity' => $quantity]);

            $this->dispatch('cartUpdated', message: 'Cart updated successfully!');
        }
    }

    public function render()
    {
        $cartItems = collect();
        $subtotal = 0;
        $total = 0;

        if (Auth::check()) {
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->get();

            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $total = $subtotal;
        }

        return view('livewire.view-cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }
}
