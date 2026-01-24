<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class OrderDetail extends Component
{
    public $order;

    public function mount($id)
    {
        $this->order = Order::with('orderItems.product', 'user')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.order-detail', [
            'order' => $this->order,
        ]);
    }
}
