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
        $this->loadProduct($id);
    }

    protected function loadProduct($id)
    {
        $this->product = Product::where('id', $id)
            ->where('status', true)
            ->firstOrFail();

        // Ensure arrays are properly cast
        if ($this->product->size_options && is_string($this->product->size_options)) {
            $this->product->size_options = json_decode($this->product->size_options, true) ?? [];
        }
        if ($this->product->color_options && is_string($this->product->color_options)) {
            $this->product->color_options = json_decode($this->product->color_options, true) ?? [];
        }
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

    public function selectSize($sizeName)
    {
        $this->selectedSize = $sizeName;
    }

    public function selectColor($colorName)
    {
        $this->selectedColor = $colorName;
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

        // Validate required options if enabled
        if ($this->product->has_size_options && !empty($this->product->size_options)) {
            if (empty($this->selectedSize)) {
                $this->dispatch('cartUpdated', message: 'Please select a size option.', type: 'error');

                return;
            }
        }

        if ($this->product->has_color_options && !empty($this->product->color_options)) {
            if (empty($this->selectedColor)) {
                $this->dispatch('cartUpdated', message: 'Please select a color option.', type: 'error');

                return;
            }
        }

        app(CartService::class)->addToCart($this->product->id, $this->quantity);
        $this->dispatch('cartUpdated', message: 'Product added to cart successfully!');
        $this->quantity = 1;
        $this->selectedSize = null;
        $this->selectedColor = null;
    }

    public function render()
    {
        return view('livewire.view-product');
    }
}
