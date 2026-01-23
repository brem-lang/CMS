<?php

namespace App\Livewire;

use App\Models\Blog;
use App\Models\Product;
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
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $this->blogs = Blog::where('status', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();
    }

    public function selectProduct($id)
    {
        return redirect()->route('product.view', $id);
    }

    public function openBlog($id)
    {
        dd($id);
    }

    public function addToCart($id) {}

    public function render()
    {
        return view('livewire.home-page');
    }
}
