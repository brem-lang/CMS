<?php

namespace App\Livewire;

use App\Models\Order;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class CheckoutFailed extends Component
{
    public $order;

    public function mount($order)
    {
        $this->order = Order::findOrFail($order);
        
        // Update order status to failed
        if ($this->order->payment_status !== 'failed') {
            $this->order->update([
                'payment_status' => 'failed',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.checkout-failed');
    }
}
