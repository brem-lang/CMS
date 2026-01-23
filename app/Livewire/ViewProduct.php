<?php

namespace App\Livewire;

use App\Models\Product;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class ViewProduct extends Component
{
    public $product;

    public $selectedSize = null;

    public $selectedColor = null;

    public $quantity = 1;

    public function mount($id)
    {
        $this->product = Product::where('id', $id)
            ->where('status', true)
            ->firstOrFail();
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        // TODO: Implement add to cart functionality
        session()->flash('message', 'Product added to cart!');
    }

    public function render()
    {
        return view('livewire.view-product');
    }
}
