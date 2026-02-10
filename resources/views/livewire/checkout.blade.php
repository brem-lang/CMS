<div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Check Out</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('shop') }}">Shop</a>
                            <span>Check Out</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 30px;">
                    <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($cartItems->isEmpty())
                <div style="text-align: center; padding: 50px 20px;">
                    <i class="fa fa-shopping-cart" style="font-size: 64px; color: #e5e5e5; margin-bottom: 20px;"></i>
                    <p style="color: #666; font-size: 16px; margin-bottom: 20px;">Your cart is empty</p>
                    <a href="{{ route('shop') }}" class="site-btn"
                        style="display: inline-block; text-decoration: none; padding: 12px 30px;">Start Shopping</a>
                </div>
            @else
                <div class="checkout__form">
                    <form wire:submit.prevent="placeOrder">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <h6 class="coupon__code"><span class="icon_tag_alt"></span> Have a coupon? <a
                                        href="{{ route('view-cart') }}">Click
                                        here</a> to enter your code</h6>
                                <h6 class="checkout__title">Billing Details</h6>
                                <div class="alert alert-warning" role="alert"
                                    style="background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 5px; padding: 15px; margin-bottom: 25px;">
                                    <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                                        <i class="fa fa-exclamation-triangle"
                                            style="margin-right: 8px; color: #ffc107;"></i>
                                        <strong>Important:</strong> Please ensure all information below is accurate and
                                        correct. Incorrect shipping details may result in delivery delays or failed
                                        deliveries. Double-check your name, address, phone number, and email before
                                        placing your order.
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="checkout__input">
                                            <p>Full Name<span>*</span></p>
                                            <input type="text" wire:model="fullName" required name="fullName"
                                                @if (auth()->check()) readonly style="background-color: #f5f5f5; cursor: not-allowed;" @endif>
                                            @error('fullName')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="checkout__input">
                                            <p>Country<span>*</span></p>
                                            <input type="text" wire:model="country" required name="country">
                                            @error('country')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout__input">
                                    <p>Address<span>*</span></p>
                                    <input type="text" wire:model="address" placeholder="Street Address"
                                        name="address" class="checkout__input__add" required>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="checkout__input">
                                    <p>Address Details</p>
                                    <input type="text" wire:model="addressDetails" name="addressDetails"
                                        placeholder="Apartment, suite, unite etc (optional)">
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="checkout__input">
                                            <p>City<span>*</span></p>
                                            <input type="text" wire:model="town" required name="town">
                                            @error('town')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="checkout__input">
                                            <p>Province<span>*</span></p>
                                            <input type="text" wire:model="state" required name="state">
                                            @error('state')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="checkout__input">
                                            <p>Postcode / ZIP<span>*</span></p>
                                            <input type="text" wire:model="postcode" required name="postcode">
                                            @error('postcode')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="checkout__input">
                                            <p>Phone<span>*</span></p>
                                            <input type="text" wire:model="phone" required name="phone">
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="checkout__input">
                                            <p>Email<span>*</span></p>
                                            <input type="email" wire:model="email" required name="email"
                                                @if (auth()->check()) readonly style="background-color: #f5f5f5; cursor: not-allowed;" @endif>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="checkout__input">
                                            <p>Order notes</p>
                                            <input type="text" wire:model="orderNotes" name="orderNotes"
                                                placeholder="Notes about your order, e.g. special notes for delivery.">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="checkout__order">
                                    <h4 class="order__title">Your order</h4>
                                    <div class="checkout__order__products"
                                        style="display: grid; grid-template-columns: 1fr 1fr 0.8fr 0.8fr; gap: 10px; padding: 10px 0; border-bottom: 2px solid #e5e5e5; font-weight: 600; color: #111111;">
                                        <span>Product</span>
                                        <span>Size/Color</span>
                                        <span style="text-align: center;">Quantity</span>
                                        <span style="text-align: right;">Total</span>
                                    </div>
                                    <ul class="checkout__total__products"
                                        style="list-style: none; padding: 0; margin: 0;">
                                        @forelse($cartItems as $index => $item)
                                            <li
                                                style="display: grid; grid-template-columns: 1fr 1fr 0.8fr 0.8fr; gap: 10px; padding: 12px 0; border-bottom: 1px solid #f0f0f0; align-items: center;">
                                                <span style="text-align: left; font-size: 14px; color: #666;">
                                                    {{ $item->product_name }}
                                                    @if ($item->type === 'digital')
                                                        <span style="font-size: 11px; color: #999;">(Digital)</span>
                                                    @endif
                                                </span>
                                                <div style="text-align: left;">
                                                    @if ($item->type === 'product')
                                                        @if ($item->selected_size ?? null)
                                                            <div
                                                                style="font-size: 12px; color: #999; margin-bottom: 4px;">
                                                                Size: {{ $item->selected_size }}</div>
                                                        @endif
                                                        @if ($item->selected_color ?? null)
                                                            <div
                                                                style="display: flex; align-items: center; gap: 6px; line-height: 1;">
                                                                <span
                                                                    style="font-size: 12px; color: #999;">Color:</span>
                                                                <span
                                                                    style="display: inline-block; width: 14px; height: 14px; border-radius: 50%; background: {{ $item->selected_color }}; border: 1px solid #e5e5e5;"></span>
                                                            </div>
                                                        @endif
                                                    @else
                                                        {{-- <span style="font-size: 12px; color: #999;">—</span> --}}
                                                    @endif
                                                </div>
                                                <span
                                                    style="text-align: center; font-size: 14px; color: #666;">x{{ $item->quantity }}</span>
                                                <span style="text-align: right; font-weight: 600; color: #e53637;">
                                                    ₱{{ number_format($item->quantity * $item->price, 2) }}
                                                </span>
                                            </li>
                                        @empty
                                            <li style="padding: 15px 0; text-align: center; color: #999;">No items in
                                                cart
                                            </li>
                                        @endforelse
                                    </ul>
                                    <ul class="checkout__total__all"
                                        style="list-style: none; padding: 0; margin: 0; margin-top: 15px; border-top: 2px solid #e5e5e5; padding-top: 15px;">
                                        <li
                                            style="display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: #666;">
                                            <span>Subtotal</span>
                                            <span>₱{{ number_format($subtotal, 2) }}</span>
                                        </li>
                                        <li
                                            style="display: flex; justify-content: space-between; padding: 12px 0; font-size: 16px; font-weight: 700; color: #111111; border-top: 1px solid #e5e5e5; padding-top: 15px;">
                                            <span>Total</span>
                                            <span style="color: #e53637;">₱{{ number_format($total, 2) }}</span>
                                        </li>
                                    </ul>
                                    <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e5e5e5;">
                                        <p
                                            style="font-size: 16px; font-weight: 600; color: #111111; margin-bottom: 15px;">
                                            Payment Method</p>
                                        <div
                                            style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px;">
                                            <p style="color: #666; font-size: 14px; margin: 0;">
                                                <i class="fa fa-info-circle"
                                                    style="margin-right: 8px; color: #0070ba;"></i>
                                                You will be redirected to PayMongo checkout where you can choose your
                                                preferred payment method (GCash, GrabPay, or Maya).
                                            </p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="quantity" value="{{ $cartItems->sum('quantity') }}">
                                    <input type="hidden" name="total_amount" value="{{ (int) ($total * 100) }}">
                                    <input type="hidden" name="items" value="{{ $cartItems->toJson() }}">
                                    <button type="submit" class="site-btn"
                                        style="width: 100%; margin-top: 20px;">PLACE
                                        ORDER</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </section>
    <!-- Checkout Section End -->
</div>
