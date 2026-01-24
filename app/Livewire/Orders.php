<?php

namespace App\Livewire;

use App\Models\Order;
use App\View\Components\Layout\App;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout(App::class)]
class Orders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'custom-shop';

    public function mount()
    {
        // Ensure user is authenticated
        if (! Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);

        return view('livewire.orders', [
            'orders' => $orders,
        ]);
    }
}
