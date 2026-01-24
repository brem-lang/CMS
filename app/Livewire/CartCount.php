<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;

class CartCount extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        $count = app(CartService::class)->getCartCount();

        return view('livewire.cart-count', [
            'count' => $count,
        ]);
    }
}
