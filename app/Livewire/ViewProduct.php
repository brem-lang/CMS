<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
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
        $maxQuantity = $this->product->stock_quantity ?? 0;
        if ($this->quantity < $maxQuantity) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        // Check if product is out of stock
        if (($this->product->stock_quantity ?? 0) <= 0) {
            $this->dispatch('cartUpdated', message: 'This product is currently out of stock.', type: 'error');
            return;
        }

        // Check if requested quantity exceeds available stock
        if ($this->quantity > $this->product->stock_quantity) {
            $this->dispatch('cartUpdated', message: 'Requested quantity exceeds available stock.', type: 'error');
            return;
        }

        app(CartService::class)->addToCart($this->product->id, $this->quantity);
        $this->dispatch('cartUpdated', message: 'Product added to cart successfully!');
        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.view-product');
    }
}
