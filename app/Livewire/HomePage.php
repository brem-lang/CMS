<?php

namespace App\Livewire;

use App\Models\Blog;
use App\Models\Product;
use App\Services\CartService;
use App\View\Components\Layout\App;
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
        $product = Product::findOrFail($id);
        app(CartService::class)->addToCart($id, 1);
        $this->dispatch('cartUpdated', message: 'Product added to cart successfully!');
    }

    public function render()
    {
        return view('livewire.home-page');
    }
}
