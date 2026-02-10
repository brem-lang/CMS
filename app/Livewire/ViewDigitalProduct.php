<?php

namespace App\Livewire;

use App\Models\DigitalProduct;
use App\Services\CartService;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class ViewDigitalProduct extends Component
{
    public $product;

    public function mount($id)
    {
        $this->product = DigitalProduct::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function addToCart()
    {
        try {
            app(CartService::class)->addDigitalProductToCart($this->product->id, 1);
            session()->flash('message', 'Added to cart!');
            $this->dispatch('cartUpdated');
        } catch (\InvalidArgumentException $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function buyNow()
    {
        try {
            app(CartService::class)->addDigitalProductToCart($this->product->id, 1);
            $this->dispatch('cartUpdated');

            return redirect()->route('checkout');
        } catch (\InvalidArgumentException $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.view-digital-product');
    }
}
