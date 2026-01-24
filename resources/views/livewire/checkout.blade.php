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
            <div class="checkout__form">
                <form method="POST" action="{{ route('checkout.create') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <h6 class="coupon__code"><span class="icon_tag_alt"></span> Have a coupon? <a
                                    href="{{ route('view-cart') }}">Click
                                    here</a> to enter your code</h6>
                            <h6 class="checkout__title">Billing Details</h6>
                            <div class="checkout__input">
                                <p>Full Name<span>*</span></p>
                                <input type="text" wire:model="fullName" required name="fullName">
                                @error('fullName')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="checkout__input">
                                <p>Country<span>*</span></p>
                                <input type="text" wire:model="country" required name="country">
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="checkout__input">
                                <p>Address<span>*</span></p>
                                <input type="text" wire:model="address" placeholder="Street Address" name="address"
                                    class="checkout__input__add" required>
                                <input type="text" wire:model="addressDetails" name="addressDetails"
                                    placeholder="Apartment, suite, unite etc (optional)">
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="checkout__input">
                                <p>Town/City<span>*</span></p>
                                <input type="text" wire:model="town" required name="town">
                                @error('town')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="checkout__input">
                                <p>Country/State<span>*</span></p>
                                <input type="text" wire:model="state" required name="state">
                                @error('state')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="checkout__input">
                                <p>Postcode / ZIP<span>*</span></p>
                                <input type="text" wire:model="postcode" required name="postcode">
                                @error('postcode')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Phone<span>*</span></p>
                                        <input type="text" wire:model="phone" required name="phone">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email<span>*</span></p>
                                        <input type="email" wire:model="email" required name="email">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Order notes</p>
                                <input type="text" wire:model="orderNotes" name="orderNotes"
                                    placeholder="Notes about your order, e.g. special notes for delivery.">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4 class="order__title">Your order</h4>
                                <div class="checkout__order__products"
                                    style="display: grid; grid-template-columns: 1fr 0.8fr 0.8fr; gap: 10px; padding: 10px 0; border-bottom: 2px solid #e5e5e5; font-weight: 600; color: #111111;">
                                    <span>Product</span>
                                    <span style="text-align: center;">Quantity</span>
                                    <span style="text-align: right;">Total</span>
                                </div>
                                <ul class="checkout__total__products" style="list-style: none; padding: 0; margin: 0;">
                                    @forelse($cartItems as $index => $item)
                                        <li
                                            style="display: grid; grid-template-columns: 1fr 0.8fr 0.8fr; gap: 10px; padding: 12px 0; border-bottom: 1px solid #f0f0f0; align-items: center;">
                                            <span
                                                style="font-size: 14px; color: #666;">{{ $item->product->name }}</span>
                                            <span
                                                style="text-align: center; font-size: 14px; color: #666;">x{{ $item->quantity }}</span>
                                            <span
                                                style="text-align: right; font-weight: 600; color: #e53637;">₱{{ number_format($item->quantity * $item->product->price, 2) }}</span>
                                        </li>
                                    @empty
                                        <li style="padding: 15px 0; text-align: center; color: #999;">No items in cart
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
                                <input type="hidden" name="quantity" value="{{ $cartItems->sum('quantity') }}">
                                <input type="hidden" name="total_amount" value="{{ (int) ($total * 100) }}">
                                <input type="hidden" name="items" value="{{ $cartItems->toJson() }}">
                                <button type="submit" class="site-btn" style="width: 100%; margin-top: 20px;">PLACE
                                    ORDER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
</div>
