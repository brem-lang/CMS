<?php

namespace App\Livewire;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
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

    public function removeItem($cartId)
    {
        if (Auth::check()) {
            Cart::where('id', $cartId)
                ->where('user_id', Auth::id())
                ->delete();
            
            $this->dispatch('cartUpdated');
            session()->flash('message', 'Item removed from cart!');
        }
    }

    public function updateQuantity($cartId, $quantity)
    {
        if (Auth::check() && $quantity > 0) {
            Cart::where('id', $cartId)
                ->where('user_id', Auth::id())
                ->update(['quantity' => $quantity]);
            
            $this->dispatch('cartUpdated');
        }
    }

    public function render()
    {
        $cartItems = collect();
        $total = 0;

        if (Auth::check()) {
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->get();
            
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
        }

        return view('livewire.cart-sidebar', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }
}
