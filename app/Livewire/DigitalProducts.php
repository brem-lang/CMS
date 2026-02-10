<?php

namespace App\Livewire;

use App\Models\DigitalProduct;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout(App::class)]
class DigitalProducts extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DigitalProduct::where('is_active', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        $digitalProducts = $query->orderBy('title')->paginate(9);

        return view('livewire.digital-products', [
            'digitalProducts' => $digitalProducts,
        ]);
    }
}
