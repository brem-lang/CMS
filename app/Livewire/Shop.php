<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout(App::class)]
class Shop extends Component
{
    use WithPagination;

    public $search = '';

    public $minPrice = null;

    public $maxPrice = null;

    public $sortBy = 'name'; // 'name', 'price_low', 'price_high'

    public $priceRange = null; // '0-50', '50-100', '100-150', '150-200', '200-250', '250+'

    public function mount()
    {
        // Initialize filters if needed
    }

    public function updatedPriceRange($value)
    {
        if ($value) {
            $this->applyPriceRange($value);
        } else {
            $this->minPrice = null;
            $this->maxPrice = null;
        }
        $this->resetPage(); // Reset to first page when filter changes
    }

    public function updatedSearch()
    {
        $this->resetPage(); // Reset to first page when search changes
    }

    public function updatedSortBy()
    {
        $this->resetPage(); // Reset to first page when sort changes
    }

    public function applyPriceRange($range)
    {
        switch ($range) {
            case '0-50':
                $this->minPrice = 0;
                $this->maxPrice = 50;
                break;
            case '50-100':
                $this->minPrice = 50;
                $this->maxPrice = 100;
                break;
            case '100-150':
                $this->minPrice = 100;
                $this->maxPrice = 150;
                break;
            case '150-200':
                $this->minPrice = 150;
                $this->maxPrice = 200;
                break;
            case '200-250':
                $this->minPrice = 200;
                $this->maxPrice = 250;
                break;
            case '250+':
                $this->minPrice = 250;
                $this->maxPrice = null;
                break;
            default:
                $this->minPrice = null;
                $this->maxPrice = null;
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->priceRange = null;
        $this->sortBy = 'name';
    }

    public function selectProduct($id)
    {
        return redirect()->route('product.view', $id);
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        app(CartService::class)->addToCart($id, 1);
        $this->dispatch('cartUpdated', message: 'Product added to cart successfully!');
    }

    public function render()
    {
        $query = Product::where('status', true);

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        // Price range filter
        if ($this->minPrice !== null) {
            $query->where('price', '>=', $this->minPrice);
        }
        if ($this->maxPrice !== null) {
            $query->where('price', '<=', $this->maxPrice);
        }

        // Sorting - accessing $this->sortBy ensures Livewire tracks it
        switch ($this->sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $products = $query->paginate(9);

        return view('livewire.shop', [
            'products' => $products,
        ]);
    }
}
