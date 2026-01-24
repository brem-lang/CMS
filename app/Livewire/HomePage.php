<?php

namespace App\Livewire;

use App\Models\Blog;
use App\Models\Cart;
use App\Models\Product;
use App\View\Components\Layout\App;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class HomePage extends Component
{
    public $products;

    public $blogs;

    public function mount()
    {
        $this->products = Product::where('status', true)
            ->latest()
            ->limit(4)
            ->get();

        $this->blogs = Blog::where('status', true)
            ->latest()
            ->limit(3)
            ->get();
    }

    public function selectProduct($id)
    {
        return redirect()->route('product.view', $id);
    }

    public function openBlog($id)
    {
        return redirect()->route('blog.view', $id);
    }

    public function addToCart($id)
    {
        if (Auth::guest()) {
            session()->put('url.intended', url()->current());

            return redirect()->route('login')->with('message', 'Please log in to add items to your cart.');
        }

        $product = Product::findOrFail($id);

        // Check if product already exists in cart with pending status
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->where('status', 'pending')
            ->first();

        if ($existingCart) {
            // Increment quantity if item already exists
            $existingCart->increment('quantity');
            $this->dispatch('cartUpdated', message: 'Cart updated! Quantity increased.');

            return;
        }

        // Add to cart with quantity 1
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $id,
            'quantity' => 1,
            'status' => 'pending',
        ]);

        $this->dispatch('cartUpdated', message: 'Product added to cart successfully!');
    }

    public function render()
    {
        return view('livewire.home-page');
    }
}
