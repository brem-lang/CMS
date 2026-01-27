<?php

namespace App\Livewire;

use App\Models\Order;
use App\View\Components\Layout\App;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class CheckoutFailed extends Component
{
    public $orderNumber;
    public $orderTotal;

    public function mount($order)
    {
        $orderModel = Order::find($order);
        
        if ($orderModel) {
            // Store order details before deletion
            $this->orderNumber = $orderModel->order_number;
            $this->orderTotal = $orderModel->total;
            
            // Update order status to failed before deletion
            if ($orderModel->payment_status !== 'failed') {
                $orderModel->update([
                    'payment_status' => 'failed',
                ]);
            }
            
            // Delete the order and its related records to prevent issues
            // OrderItems and OrderStatusHistory will be cascade deleted automatically
            try {
                $orderModel->delete();
                Log::info('Order #'.$this->orderNumber.' deleted due to payment failure');
            } catch (\Exception $deleteException) {
                Log::error('Failed to delete order after payment failure: '.$deleteException->getMessage());
            }
        } else {
            // Order not found - set defaults
            $this->orderNumber = 'N/A';
            $this->orderTotal = 0;
        }
    }

    public function render()
    {
        return view('livewire.checkout-failed');
    }
}
