<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function index()
    {
        return view('track-order');
    }
    
    public function search(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
        ]);
        
        $order = Order::where('order_number', $request->order_number)->first();
        
        if (!$order) {
            return redirect()->route('track-order')
                ->with('error', 'Order not found. Please check your order reference number.')
                ->withInput();
        }
        
        return view('track-order', compact('order'));
    }
}
