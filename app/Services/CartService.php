<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get cart items (from session for guests, database for authenticated)
     */
    public function getCartItems()
    {
        if (Auth::check()) {
            return Cart::with('product')
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->get();
        }
        
        // Guest cart from session
        $cart = Session::get('guest_cart', []);
        $items = collect();
        
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $items->push((object)[
                    'id' => 'session_' . $productId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'product' => $product,
                ]);
            }
        }
        
        return $items;
    }
    
    /**
     * Add item to cart
     */
    public function addToCart($productId, $quantity = 1)
    {
        if (Auth::check()) {
            // Database cart for authenticated users
            $existingCart = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('status', 'pending')
                ->first();
            
            if ($existingCart) {
                $existingCart->increment('quantity', $quantity);
                return;
            }
            
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
                'status' => 'pending',
            ]);
        } else {
            // Session cart for guests
            $cart = Session::get('guest_cart', []);
            $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
            Session::put('guest_cart', $cart);
        }
    }
    
    /**
     * Remove item from cart
     */
    public function removeFromCart($productId)
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('status', 'pending')
                ->delete();
        } else {
            $cart = Session::get('guest_cart', []);
            unset($cart[$productId]);
            Session::put('guest_cart', $cart);
        }
    }
    
    /**
     * Update item quantity
     */
    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($productId);
            return;
        }
        
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('status', 'pending')
                ->update(['quantity' => $quantity]);
        } else {
            $cart = Session::get('guest_cart', []);
            $cart[$productId] = $quantity;
            Session::put('guest_cart', $cart);
        }
    }
    
    /**
     * Get cart count
     */
    public function getCartCount()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->sum('quantity');
        }
        
        return array_sum(Session::get('guest_cart', []));
    }
    
    /**
     * Migrate guest cart to database when user logs in
     */
    public function migrateGuestCartToDatabase($userId)
    {
        $guestCart = Session::get('guest_cart', []);
        
        foreach ($guestCart as $productId => $quantity) {
            $existingCart = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('status', 'pending')
                ->first();
            
            if ($existingCart) {
                $existingCart->increment('quantity', $quantity);
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'status' => 'pending',
                ]);
            }
        }
        
        Session::forget('guest_cart');
    }
    
    /**
     * Clear cart after successful order
     */
    public function clearCart($userId = null)
    {
        if ($userId) {
            // Delete cart items instead of updating status to avoid unique constraint violation
            // Order items are already stored in order_items table, so we can safely delete cart items
            Cart::where('user_id', $userId)
                ->where('status', 'pending')
                ->delete();
        } else {
            Session::forget('guest_cart');
        }
    }
}
