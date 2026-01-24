<?php

namespace App\Livewire;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartCount extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        $count = 0;
        if (Auth::check()) {
            $count = Cart::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->sum('quantity');
        }

        return view('livewire.cart-count', [
            'count' => $count,
        ]);
    }
}
