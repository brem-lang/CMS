<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartDigitalProduct;
use App\Models\DigitalProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get cart items (from session for guests, database for authenticated)
     * Returns unified collection: each item has type ('product'|'digital') and either product + product_id or digitalProduct + digital_product_id
     */
    public function getCartItems()
    {
        $productItems = $this->getProductCartItems();
        $digitalItems = $this->getDigitalCartItems();

        return $productItems->concat($digitalItems);
    }

    /**
     * Get product (physical) cart items with type set
     */
    protected function getProductCartItems()
    {
        if (Auth::check()) {
            $carts = Cart::with('product')
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->get();
            return $carts->map(function ($cart) {
                $item = (object) [
                    'type' => 'product',
                    'id' => 'cart_' . $cart->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'selected_size' => $cart->selected_size,
                    'selected_color' => $cart->selected_color,
                    'product' => $cart->product,
                ];
                return $item;
            });
        }

        $cart = Session::get('guest_cart', []);
        $items = collect();
        foreach ($cart as $key => $itemData) {
            $parts = explode('_', $key, 3);
            $productId = $parts[0];
            if (is_array($itemData)) {
                $quantity = $itemData['quantity'] ?? 1;
                $selectedSize = $itemData['size'] ?? (isset($parts[1]) && $parts[1] !== 'null' ? $parts[1] : null);
                $selectedColor = $itemData['color'] ?? (isset($parts[2]) && $parts[2] !== 'null' ? $parts[2] : null);
            } else {
                $quantity = $itemData;
                $selectedSize = isset($parts[1]) && $parts[1] !== 'null' ? $parts[1] : null;
                $selectedColor = isset($parts[2]) && $parts[2] !== 'null' ? $parts[2] : null;
            }
            $product = Product::find($productId);
            if ($product) {
                $items->push((object) [
                    'type' => 'product',
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
     * Get digital product cart items with type set
     */
    protected function getDigitalCartItems()
    {
        if (Auth::check()) {
            $rows = CartDigitalProduct::with('digitalProduct')
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->get();
            return $rows->map(function ($row) {
                return (object) [
                    'type' => 'digital',
                    'id' => 'digital_' . $row->id,
                    'digital_product_id' => $row->digital_product_id,
                    'quantity' => $row->quantity,
                    'digitalProduct' => $row->digitalProduct,
                ];
            });
        }

        $cart = Session::get('guest_cart_digital', []);
        $items = collect();
        foreach ($cart as $digitalProductId => $quantity) {
            $digitalProduct = DigitalProduct::find($digitalProductId);
            if ($digitalProduct) {
                $items->push((object) [
                    'type' => 'digital',
                    'id' => 'session_digital_' . $digitalProductId,
                    'digital_product_id' => (int) $digitalProductId,
                    'quantity' => is_array($quantity) ? ($quantity['quantity'] ?? 1) : (int) $quantity,
                    'digitalProduct' => $digitalProduct,
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
     * Add digital product to cart (paid only; free products are downloaded directly).
     * Digital products are limited to one quantity per product.
     */
    public function addDigitalProductToCart($digitalProductId, $quantity = 1)
    {
        $digitalProduct = DigitalProduct::where('id', $digitalProductId)->where('is_active', true)->first();
        if (! $digitalProduct) {
            throw new \InvalidArgumentException('Digital product not found or not active.');
        }
        if ($digitalProduct->is_free) {
            throw new \InvalidArgumentException('Free digital products are available for direct download and cannot be added to cart.');
        }

        // Digital products: only one quantity per product
        $quantity = 1;

        if (Auth::check()) {
            $existing = CartDigitalProduct::where('user_id', Auth::id())
                ->where('digital_product_id', $digitalProductId)
                ->where('status', 'pending')
                ->first();
            if ($existing) {
                $existing->update(['quantity' => 1]);
                return;
            }
            CartDigitalProduct::create([
                'user_id' => Auth::id(),
                'digital_product_id' => $digitalProductId,
                'quantity' => 1,
                'status' => 'pending',
            ]);
        } else {
            $cart = Session::get('guest_cart_digital', []);
            $cart[$digitalProductId] = 1;
            Session::put('guest_cart_digital', $cart);
        }
    }

    /**
     * Remove digital product from cart
     */
    public function removeDigitalProductFromCart($digitalProductId)
    {
        if (Auth::check()) {
            CartDigitalProduct::where('user_id', Auth::id())
                ->where('digital_product_id', $digitalProductId)
                ->where('status', 'pending')
                ->delete();
        } else {
            $cart = Session::get('guest_cart_digital', []);
            unset($cart[$digitalProductId]);
            Session::put('guest_cart_digital', $cart);
        }
    }

    /**
     * Update digital product quantity in cart.
     * Digital products are limited to one quantity; any value > 1 is capped at 1.
     */
    public function updateDigitalQuantity($digitalProductId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeDigitalProductFromCart($digitalProductId);
            return;
        }
        // Digital products: max quantity 1
        $quantity = 1;
        if (Auth::check()) {
            CartDigitalProduct::where('user_id', Auth::id())
                ->where('digital_product_id', $digitalProductId)
                ->where('status', 'pending')
                ->update(['quantity' => 1]);
        } else {
            $cart = Session::get('guest_cart_digital', []);
            $cart[$digitalProductId] = 1;
            Session::put('guest_cart_digital', $cart);
        }
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
        $count = 0;
        if (Auth::check()) {
            $count += Cart::where('user_id', Auth::id())->where('status', 'pending')->sum('quantity');
            $count += CartDigitalProduct::where('user_id', Auth::id())->where('status', 'pending')->sum('quantity');
            return $count;
        }
        $cart = Session::get('guest_cart', []);
        foreach ($cart as $itemData) {
            if (is_array($itemData)) {
                $count += $itemData['quantity'] ?? 0;
            } else {
                $count += $itemData;
            }
        }
        $cartDigital = Session::get('guest_cart_digital', []);
        foreach ($cartDigital as $qty) {
            $count += is_array($qty) ? ($qty['quantity'] ?? 0) : (int) $qty;
        }
        return $count;
    }

    /**
     * Calculate total amount to pay for all cart items
     */
    public function calculateTotal()
    {
        $cartItems = $this->getCartItems();
        $total = 0;
        foreach ($cartItems as $item) {
            if ($item->type === 'product' && $item->product && isset($item->product->price)) {
                $total += $item->quantity * $item->product->price;
            }
            if ($item->type === 'digital' && $item->digitalProduct) {
                $total += $item->quantity * (float) ($item->digitalProduct->price ?? 0);
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

        $guestCartDigital = Session::get('guest_cart_digital', []);
        foreach ($guestCartDigital as $digitalProductId => $quantity) {
            $qty = is_array($quantity) ? ($quantity['quantity'] ?? 1) : (int) $quantity;
            $existing = CartDigitalProduct::where('user_id', $userId)
                ->where('digital_product_id', $digitalProductId)
                ->where('status', 'pending')
                ->first();
            if ($existing) {
                $existing->increment('quantity', $qty);
            } else {
                CartDigitalProduct::create([
                    'user_id' => $userId,
                    'digital_product_id' => $digitalProductId,
                    'quantity' => $qty,
                    'status' => 'pending',
                ]);
            }
        }
        Session::forget('guest_cart_digital');
        Session::forget('guest_cart');
    }

    /**
     * Clear cart after successful order
     */
    public function clearCart($userId = null)
    {
        if ($userId) {
            Cart::where('user_id', $userId)->where('status', 'pending')->delete();
            CartDigitalProduct::where('user_id', $userId)->where('status', 'pending')->delete();
        } else {
            Session::forget('guest_cart');
            Session::forget('guest_cart_digital');
        }
    }
}
