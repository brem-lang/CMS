<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;

class CartTotal extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        $total = app(CartService::class)->calculateTotal();

        return view('livewire.cart-total', [
            'count' => $total,
        ]);
    }
}
