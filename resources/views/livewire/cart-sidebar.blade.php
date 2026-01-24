<div wire:key="cart-sidebar">
    @if ($show)
        <!-- Cart Sidebar Overlay -->
        <div class="cart-sidebar-overlay" wire:click="close"
            style="position: fixed; left: 0; top: 0; height: 100%; width: 100%; background: rgba(0, 0, 0, 0.7); z-index: 9998; transition: all 0.5s; visibility: visible;">
        </div>
    @endif

    <!-- Cart Sidebar -->
    <div class="cart-sidebar-wrapper" wire:key="cart-sidebar-content"
        style="position: fixed; right: {{ $show ? '0' : '-400px' }}; top: 0; width: 400px; max-width: 90%; height: 100%; background: #ffffff; padding: 30px; display: block; z-index: 9999; overflow-y: auto; transition: right 0.5s ease-in-out; box-shadow: -2px 0 10px rgba(0,0,0,0.1);">
        <div class="cart-sidebar-header"
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e5e5e5;">
            <h4 style="margin: 0; font-weight: 700; color: #111111;">Shopping Cart</h4>
            <button wire:click="close"
                style="background: none; border: none; font-size: 24px; cursor: pointer; color: #111111;">&times;</button>
        </div>

        @auth
            @if ($cartItems->count() > 0)
                <div class="cart-sidebar-items">
                    @foreach ($cartItems as $item)
                        <div class="cart-item"
                            style="display: flex; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #f0f0f0;">
                            <div class="cart-item-image"
                                style="width: 80px; height: 80px; margin-right: 15px; flex-shrink: 0;">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;">
                            </div>
                            <div class="cart-item-details" style="flex: 1;">
                                <h6 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #111111;">
                                    {{ $item->product->name }}</h6>
                                <p style="margin: 0 0 10px 0; font-size: 14px; color: #e53637; font-weight: 700;">
                                    ₱{{ number_format($item->product->price, 2) }}</p>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="display: flex; align-items: center; border: 1px solid #e5e5e5; border-radius: 3px;">
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            style="background: none; border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">-</button>
                                        <span
                                            style="padding: 5px 15px; min-width: 40px; text-align: center; display: inline-block;">{{ $item->quantity }}</span>
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            style="background: none; border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">+</button>
                                    </div>
                                    <button wire:click="removeItem({{ $item->id }})"
                                        style="background: none; border: none; color: #e53637; cursor: pointer; font-size: 14px; margin-left: auto;">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cart-sidebar-footer"
                    style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e5e5;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                        <span style="font-size: 18px; font-weight: 700; color: #111111;">Total:</span>
                        <span
                            style="font-size: 18px; font-weight: 700; color: #e53637;">₱{{ number_format($total, 2) }}</span>
                    </div>
                    <a href="{{ route('shop') }}" class="site-btn"
                        style="display: block; text-align: center; text-decoration: none; padding: 15px; margin-bottom: 10px;">Continue
                        Shopping</a>
                    <a href="#" class="site-btn"
                        style="display: block; text-align: center; text-decoration: none; padding: 15px; background: #e53637;">Checkout</a>
                </div>
            @else
                <div style="text-align: center; padding: 50px 20px;">
                    <i class="fa fa-shopping-cart" style="font-size: 64px; color: #e5e5e5; margin-bottom: 20px;"></i>
                    <p style="color: #666; font-size: 16px; margin-bottom: 20px;">Your cart is empty</p>
                    <a href="{{ route('shop') }}" class="site-btn"
                        style="display: inline-block; text-decoration: none; padding: 12px 30px;">Start Shopping</a>
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 50px 20px;">
                <i class="fa fa-shopping-cart" style="font-size: 64px; color: #e5e5e5; margin-bottom: 20px;"></i>
                <p style="color: #666; font-size: 16px; margin-bottom: 20px;">Please log in to view your cart</p>
                <a href="{{ route('login') }}" class="site-btn"
                    style="display: inline-block; text-decoration: none; padding: 12px 30px;">Login</a>
            </div>
        @endauth
    </div>
</div>
