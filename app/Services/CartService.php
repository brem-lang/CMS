<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        
        foreach ($cart as $key => $itemData) {
            // Parse key format: productId_size_color or handle old format
            $parts = explode('_', $key, 3);
            $productId = $parts[0];
            
            // Handle both old format (productId => quantity) and new format (productId_size_color => array)
            if (is_array($itemData)) {
                $quantity = $itemData['quantity'] ?? 1;
                $selectedSize = $itemData['size'] ?? (isset($parts[1]) && $parts[1] !== 'null' ? $parts[1] : null);
                $selectedColor = $itemData['color'] ?? (isset($parts[2]) && $parts[2] !== 'null' ? $parts[2] : null);
            } else {
                // Old format - backward compatibility
                $quantity = $itemData;
                $selectedSize = isset($parts[1]) && $parts[1] !== 'null' ? $parts[1] : null;
                $selectedColor = isset($parts[2]) && $parts[2] !== 'null' ? $parts[2] : null;
            }
            
            $product = Product::find($productId);
            if ($product) {
                $items->push((object)[
                    'id' => 'session_' . $key,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'selected_size' => $selectedSize,
                    'selected_color' => $selectedColor,
                    'product' => $product,
                ]);
            }
        }
        
        return $items;
    }
    
    /**
     * Add item to cart
     * Items are uniquely identified by: user_id, product_id, status, selected_size, and selected_color
     * This allows the same product to be added multiple times with different size/color combinations
     */
    public function addToCart($productId, $quantity = 1, $selectedSize = null, $selectedColor = null)
    {
        // Normalize empty strings to null for consistency
        $selectedSize = $selectedSize === '' ? null : $selectedSize;
        $selectedColor = $selectedColor === '' ? null : $selectedColor;
        
        if (Auth::check()) {
            // Database cart for authenticated users
            // Check for existing cart item with same product, size, and color combination
            // This ensures items are properly distinguished by their variant selections
            $existingCart = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('status', 'pending')
                ->where(function ($query) use ($selectedSize, $selectedColor) {
                    $query->where(function ($q) use ($selectedSize) {
                        if ($selectedSize === null) {
                            $q->whereNull('selected_size');
                        } else {
                            $q->where('selected_size', $selectedSize);
                        }
                    })
                    ->where(function ($q) use ($selectedColor) {
                        if ($selectedColor === null) {
                            $q->whereNull('selected_color');
                        } else {
                            $q->where('selected_color', $selectedColor);
                        }
                    });
                })
                ->first();
            
            if ($existingCart) {
                // If same product with same size and color exists, increment quantity
                $existingCart->increment('quantity', $quantity);
                return;
            }
            
            // Create new cart item with specified size and color
            // The unique constraint ensures no duplicates for the same user/product/size/color combination
            try {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'selected_size' => $selectedSize, // Size variant (e.g., 'S', 'M', 'L', 'XL')
                    'selected_color' => $selectedColor, // Color variant (e.g., 'red', 'blue', 'black')
                    'status' => 'pending',
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // Handle unique constraint violation - try to find and update existing record
                // This handles race conditions where two requests try to add the same item simultaneously
                if ($e->getCode() == 23000) {
                    $existingCart = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->where('status', 'pending')
                        ->where(function ($query) use ($selectedSize, $selectedColor) {
                            $query->where(function ($q) use ($selectedSize) {
                                if ($selectedSize === null) {
                                    $q->whereNull('selected_size');
                                } else {
                                    $q->where('selected_size', $selectedSize);
                                }
                            })
                            ->where(function ($q) use ($selectedColor) {
                                if ($selectedColor === null) {
                                    $q->whereNull('selected_color');
                                } else {
                                    $q->where('selected_color', $selectedColor);
                                }
                            });
                        })
                        ->first();
                    
                    if ($existingCart) {
                        $existingCart->increment('quantity', $quantity);
                    } else {
                        throw $e; // Re-throw if we can't find the existing record
                    }
                } else {
                    throw $e; // Re-throw if it's a different error
                }
            }
        } else {
            // Session cart for guests - use composite key for product + size + color
            $cart = Session::get('guest_cart', []);
            $cartKey = $this->getGuestCartKey($productId, $selectedSize, $selectedColor);
            
            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] = ($cart[$cartKey]['quantity'] ?? 0) + $quantity;
            } else {
                $cart[$cartKey] = [
                    'quantity' => $quantity,
                    'size' => $selectedSize,
                    'color' => $selectedColor,
                ];
            }
            
            Session::put('guest_cart', $cart);
        }
    }
    
    /**
     * Generate a unique key for guest cart items based on product, size, and color
     */
    protected function getGuestCartKey($productId, $selectedSize = null, $selectedColor = null)
    {
        return $productId . '_' . ($selectedSize ?? 'null') . '_' . ($selectedColor ?? 'null');
    }
    
    /**
     * Remove item from cart
     */
    public function removeFromCart($productId, $selectedSize = null, $selectedColor = null)
    {
        // Convert empty strings to null
        $selectedSize = $selectedSize === '' ? null : $selectedSize;
        $selectedColor = $selectedColor === '' ? null : $selectedColor;
        
        if (Auth::check()) {
            $query = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('status', 'pending');
            
            if ($selectedSize === null) {
                $query->whereNull('selected_size');
            } else {
                $query->where('selected_size', $selectedSize);
            }
            
            if ($selectedColor === null) {
                $query->whereNull('selected_color');
            } else {
                $query->where('selected_color', $selectedColor);
            }
            
            $query->delete();
        } else {
            $cart = Session::get('guest_cart', []);
            $cartKey = $this->getGuestCartKey($productId, $selectedSize, $selectedColor);
            unset($cart[$cartKey]);
            Session::put('guest_cart', $cart);
        }
    }
    
    /**
     * Update item quantity
     */
    public function updateQuantity($productId, $quantity, $selectedSize = null, $selectedColor = null)
    {
        // Convert empty strings to null
        $selectedSize = $selectedSize === '' ? null : $selectedSize;
        $selectedColor = $selectedColor === '' ? null : $selectedColor;
        
        if ($quantity <= 0) {
            $this->removeFromCart($productId, $selectedSize, $selectedColor);
            return;
        }
        
        if (Auth::check()) {
            $query = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('status', 'pending');
            
            if ($selectedSize === null) {
                $query->whereNull('selected_size');
            } else {
                $query->where('selected_size', $selectedSize);
            }
            
            if ($selectedColor === null) {
                $query->whereNull('selected_color');
            } else {
                $query->where('selected_color', $selectedColor);
            }
            
            $query->update(['quantity' => $quantity]);
        } else {
            $cart = Session::get('guest_cart', []);
            $cartKey = $this->getGuestCartKey($productId, $selectedSize, $selectedColor);
            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] = $quantity;
                Session::put('guest_cart', $cart);
            }
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
        
        $cart = Session::get('guest_cart', []);
        $total = 0;
        foreach ($cart as $itemData) {
            if (is_array($itemData)) {
                $total += $itemData['quantity'] ?? 0;
            } else {
                $total += $itemData; // Backward compatibility
            }
        }
        return $total;
    }
    
    /**
     * Calculate total amount to pay for all cart items
     */
    public function calculateTotal()
    {
        $cartItems = $this->getCartItems();
        $total = 0;
        
        foreach ($cartItems as $item) {
            if ($item->product && isset($item->product->price)) {
                $total += $item->quantity * $item->product->price;
            }
        }
        
        return $total;
    }
    
    /**
     * Migrate guest cart to database when user logs in
     */
    public function migrateGuestCartToDatabase($userId)
    {
        $guestCart = Session::get('guest_cart', []);
        
        foreach ($guestCart as $key => $itemData) {
            // Parse the cart key or handle array format
            if (is_array($itemData)) {
                // Extract productId from key (format: productId_size_color)
                $parts = explode('_', $key, 3);
                $productId = $parts[0];
                $selectedSize = isset($parts[1]) && $parts[1] !== 'null' ? $parts[1] : null;
                $selectedColor = isset($parts[2]) && $parts[2] !== 'null' ? $parts[2] : null;
                $quantity = $itemData['quantity'] ?? 1;
            } else {
                // Backward compatibility - old format
                $productId = $key;
                $quantity = $itemData;
                $selectedSize = null;
                $selectedColor = null;
            }
            
            // Check for existing cart item with same product, size, and color
            $existingCart = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('status', 'pending')
                ->where(function ($query) use ($selectedSize, $selectedColor) {
                    $query->where(function ($q) use ($selectedSize) {
                        if ($selectedSize === null) {
                            $q->whereNull('selected_size');
                        } else {
                            $q->where('selected_size', $selectedSize);
                        }
                    })
                    ->where(function ($q) use ($selectedColor) {
                        if ($selectedColor === null) {
                            $q->whereNull('selected_color');
                        } else {
                            $q->where('selected_color', $selectedColor);
                        }
                    });
                })
                ->first();
            
            if ($existingCart) {
                $existingCart->increment('quantity', $quantity);
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'selected_size' => $selectedSize,
                    'selected_color' => $selectedColor,
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
