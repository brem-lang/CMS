<?php

namespace App\Livewire;

use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layout.app')]
class Checkout extends Component
{
    public $fullName = '';

    public $country = '';

    public $address = '';

    public $addressDetails = '';

    public $town = '';

    public $state = '';

    public $postcode = '';

    public $phone = '';

    public $email = '';

    public $password = '';

    public $orderNotes = '';

    public $paymentMethod = 'check';

    public $cartItems = [];

    public $subtotal = 0;

    public $total = 0;

    public function mount()
    {
        $this->loadCartItems();
        
        if (Auth::check()) {
            $auth = Auth::user();
            $this->email = $auth->email ?? '';
            $this->fullName = $auth->name ?? '';
        }
    }

    public function loadCartItems()
    {
        $cartService = app(CartService::class);
        $this->cartItems = $cartService->getCartItems();
        
        $this->subtotal = $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        
        $this->total = $this->subtotal;
    }

    // public function placeOrder()
    // {
    //     // Validate form
    //     $this->validate([
    //         'fullName' => 'required|string|max:255',
    //         'country' => 'required|string|max:255',
    //         'address' => 'required|string|max:255',
    //         'town' => 'required|string|max:255',
    //         'state' => 'required|string|max:255',
    //         'postcode' => 'required|string|max:20',
    //         'phone' => 'required|string|max:20',
    //         'email' => 'required|email',
    //         'paymentMethod' => 'required|in:check,paypal',
    //     ]);

    //     // Here you would create the order in the database
    //     // For now, just show success message
    //     session()->flash('message', 'Order placed successfully! Thank you for your purchase.');

    //     // Clear cart items after placing order
    //     Cart::where('user_id', Auth::id())
    //         ->where('status', 'pending')
    //         ->update(['status' => 'completed']);

    //     // Redirect to order confirmation page
    //     return redirect()->route('home');
    // }

    public function render()
    {
        return view('livewire.checkout');
    }
}
