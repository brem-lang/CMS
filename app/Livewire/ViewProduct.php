<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\View\Components\Layout\App;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::guest()) {
            session()->put('url.intended', url()->current());

            return redirect()->route('login')->with('message', 'Please log in to add items to your cart.');
        }

        // Check if product already exists in cart with pending status
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->where('status', 'pending')
            ->first();

        if ($existingCart) {
            // Increment quantity by the selected quantity
            $existingCart->increment('quantity', $this->quantity);
            $this->dispatch('cartUpdated', message: 'Cart updated! Quantity increased.');
            $this->quantity = 1;

            return;
        }

        // Add to cart with selected quantity
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'quantity' => $this->quantity,
            'status' => 'pending',
        ]);

        $this->dispatch('cartUpdated', message: 'Product added to cart successfully!');
        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.view-product');
    }
}
