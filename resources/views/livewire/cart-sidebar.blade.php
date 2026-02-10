<div wire:key="cart-sidebar">
    <!-- Cart Sidebar Overlay -->
    <div class="cart-sidebar-overlay" wire:click="close"
        style="position: fixed; left: 0; top: 0; height: 100%; width: 100%; background: rgba(0, 0, 0, {{ $show ? '0.7' : '0' }}); z-index: 9998; transition: background 0.5s ease-in-out; pointer-events: {{ $show ? 'auto' : 'none' }};">
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar-wrapper" wire:key="cart-sidebar-content"
        style="position: fixed; right: {{ $show ? '0' : '-400px' }}; top: 0; width: 400px; max-width: 90%; height: 100%; background: #ffffff; padding: 30px; display: block; z-index: 9999; overflow-y: auto; transition: right 0.5s ease-in-out; box-shadow: -2px 0 10px rgba(0,0,0,0.1);">
        <div class="cart-sidebar-header"
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e5e5e5;">
            <h4 style="margin: 0; font-weight: 700; color: #111111;">Shopping Cart</h4>
            <button wire:click="close"
                style="background: none; border: none; font-size: 24px; cursor: pointer; color: #111111;">&times;</button>
        </div>

        @if ($cartItems->count() > 0)
            <div class="cart-sidebar-items">
                @foreach ($cartItems as $item)
                    <div class="cart-item"
                        style="display: flex; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #f0f0f0;">
                        <div class="cart-item-image"
                            style="width: 80px; height: 80px; margin-right: 15px; flex-shrink: 0;">
                            @if ($item->type === 'product')
                                @php
                                    $sidebarItemImage = $item->product->getVariantImageUrlForSelection($item->selected_size ?? null, $item->selected_color ?? null)
                                        ?? $item->product->image_url;
                                @endphp
                                <img src="{{ $sidebarItemImage }}" alt="{{ $item->product->name }}"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;">
                            @else
                                @if ($item->digitalProduct->thumbnail_url ?? null)
                                    <img src="{{ $item->digitalProduct->thumbnail_url }}" alt="{{ $item->digitalProduct->title }}"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;">
                                @else
                                    <div style="width: 100%; height: 100%; background: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-file-o" style="font-size: 24px; color: #999;"></i>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="cart-item-details" style="flex: 1;">
                            <h6 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #111111;">
                                @if ($item->type === 'product')
                                    {{ $item->product->name }}
                                @else
                                    {{ $item->digitalProduct->title }}
                                    <span style="font-size: 11px; color: #999;">(Digital)</span>
                                @endif
                            </h6>
                            @if ($item->type === 'product')
                                @if ($item->selected_size ?? null)
                                    <p style="margin: 0; font-size: 12px; color: #999;">Size: {{ $item->selected_size }}</p>
                                @endif
                                @if ($item->selected_color ?? null)
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <span style="font-size: 12px; color: #999;">Color:</span>
                                        <span style="display: inline-block; width: 16px; height: 16px; border-radius: 50%; background: {{ $item->selected_color }}; border: 1px solid #e5e5e5;"></span>
                                    </div>
                                @endif
                            @endif
                            <p style="margin: 0 0 10px 0; font-size: 14px; color: #e53637; font-weight: 700;">
                                @if ($item->type === 'product')
                                    ₱{{ number_format($item->product->price, 2) }}
                                @else
                                    {{ $item->digitalProduct->is_free ? 'Free' : '₱' . number_format($item->digitalProduct->price, 2) }}
                                @endif
                            </p>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="display: flex; align-items: center; border: 1px solid #e5e5e5; border-radius: 3px;">
                                    @if ($item->type === 'product')
                                        <button wire:click="updateQuantity({{ $item->product_id }}, {{ $item->quantity - 1 }}, '{{ $item->selected_size ?? '' }}', '{{ $item->selected_color ?? '' }}')"
                                            style="background: none; border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">-</button>
                                        <span style="padding: 5px 15px; min-width: 40px; text-align: center;">{{ $item->quantity }}</span>
                                        <button wire:click="updateQuantity({{ $item->product_id }}, {{ $item->quantity + 1 }}, '{{ $item->selected_size ?? '' }}', '{{ $item->selected_color ?? '' }}')"
                                            style="background: none; border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">+</button>
                                    @else
                                        <button wire:click="updateDigitalQuantity({{ $item->digital_product_id }}, 0)"
                                            title="Remove"
                                            style="background: none; border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">-</button>
                                        <span style="padding: 5px 15px; min-width: 40px; text-align: center;">1</span>
                                    @endif
                                </div>
                                <button
                                    @if ($item->type === 'product')
                                        wire:click="removeItem({{ $item->product_id }}, '{{ $item->selected_size ?? '' }}', '{{ $item->selected_color ?? '' }}')"
                                    @else
                                        wire:click="removeDigitalItem({{ $item->digital_product_id }})"
                                    @endif
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
                <a href="{{ route('view-cart') }}" class="site-btn btn bg-dark text-white shadow-sm border-0 d-block"
                    onmouseover="this.style.opacity='0.75'; this.classList.replace('shadow-sm', 'shadow-lg'); this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.opacity='1'; this.classList.replace('shadow-lg', 'shadow-sm'); this.style.transform='translateY(0)'"
                    style="display: block; text-align: center; text-decoration: none; padding: 15px; margin-bottom: 10px; transition: all 0.3s ease; opacity: 1;">
                    View Cart
                </a>
                <a href="{{ route('checkout') }}" class="site-btn"
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
    </div>
</div>
