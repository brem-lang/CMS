<div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shopping Cart</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('shop') }}">Shop</a>
                            <span>Shopping Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle"></i> {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($cartItems->count() > 0)
                <div class="row">
                    <div class="col-lg-8">
                        <div class="shopping__cart__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        <tr>
                                            <td class="product__cart__item">
                                                <div class="product__cart__item__pic">
                                                    <img src="{{ $item->product->image_url }}"
                                                        alt="{{ $item->product->name }}">
                                                </div>
                                                <div class="product__cart__item__text">
                                                    <h6>{{ $item->product->name }}</h6>
                                                    <h5>₱{{ number_format($item->product->price, 2) }}</h5>
                                                </div>
                                            </td>
                                            <td class="quantity__item">
                                                <div class="quantity">
                                                    <div
                                                        style="display: flex; align-items: center; border: 1px solid #e5e5e5; border-radius: 3px; width: fit-content;">
                                                        <button
                                                            wire:click="updateQuantity({{ $item->product_id }}, {{ $item->quantity - 1 }})"
                                                            style="background: #f5f5f5; border: none; padding: 8px 12px; cursor: pointer; font-size: 16px; font-weight: 600; color: #111111; transition: all 0.3s ease;"
                                                            onmouseover="this.style.background='#e5e5e5'; this.style.transform='scale(1.1)'"
                                                            onmouseout="this.style.background='#f5f5f5'; this.style.transform='scale(1)'">−</button>
                                                        <input type="text" value="{{ $item->quantity }}" readonly
                                                            style="border: none; background: none; text-align: center; width: 50px; font-weight: 600; color: #111111;">
                                                        <button
                                                            wire:click="updateQuantity({{ $item->product_id }}, {{ $item->quantity + 1 }})"
                                                            style="background: #f5f5f5; border: none; padding: 8px 12px; cursor: pointer; font-size: 16px; font-weight: 600; color: #111111; transition: all 0.3s ease;"
                                                            onmouseover="this.style.background='#e5e5e5'; this.style.transform='scale(1.1)'"
                                                            onmouseout="this.style.background='#f5f5f5'; this.style.transform='scale(1)'">+</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="cart__price">
                                                ₱{{ number_format($item->quantity * $item->product->price, 2) }}</td>
                                            <td class="cart__close">
                                                <button type="button"
                                                    onclick="openRemoveModal(event, {{ $item->product_id }}, '{{ addslashes($item->product->name) }}')"
                                                    style="background: none; border: none; cursor: pointer; font-size: 18px; color: #999; transition: all 0.3s ease; padding: 5px 10px;"
                                                    onmouseover="this.style.color='#e53637'; this.style.transform='scale(1.3)'"
                                                    onmouseout="this.style.color='#999'; this.style.transform='scale(1)'">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="continue__btn">
                                    <a href="{{ route('shop') }}"
                                        class="site-btn d-inline-block text-decoration-none shadow-sm transition-all"
                                        onmouseover="this.style.opacity='0.75'; this.classList.replace('shadow-sm', 'shadow-lg'); this.style.transform='translateY(-2px)'"
                                        onmouseout="this.style.opacity='1'; this.classList.replace('shadow-lg', 'shadow-sm'); this.style.transform='translateY(0)'"
                                        style="background: #111111; color: #ffffff; padding: 14px 30px; text-transform: uppercase; font-weight: 700; letter-spacing: 2px; transition: all 0.3s ease; display: inline-block;">
                                        Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="cart__discount">
                            <h6>Discount codes</h6>
                            <form action="#">
                                <input type="text" placeholder="Coupon code">
                                <button type="submit">Apply</button>
                            </form>
                        </div>
                        <div class="cart__total">
                            <h6>Cart total</h6>
                            <ul>
                                <li>Subtotal <span>₱{{ number_format($subtotal, 2) }}</span></li>
                                <li>Total <span>₱{{ number_format($total, 2) }}</span></li>
                            </ul>
                            <a href="{{ route('checkout') }}" class="site-btn"
                                style="display: block; text-align: center; text-decoration: none; padding: 15px; background: #e53637;">Checkout</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="padding: 50px 20px;">
                            <i class="fa fa-shopping-cart"
                                style="font-size: 64px; color: #e5e5e5; margin-bottom: 20px; display: block;"></i>
                            <p style="color: #666; font-size: 16px; margin-bottom: 20px;">Your cart is empty</p>
                            <a href="{{ route('shop') }}" class="site-btn"
                                style="display: inline-block; text-decoration: none; padding: 12px 30px;">Start
                                Shopping</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Shopping Cart Section End -->

    <!-- Remove Item Confirmation Modal -->
    <div id="removeConfirmationModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 10000; align-items: center; justify-content: center;">
        <div
            style="background: white; padding: 40px; border-radius: 8px; max-width: 420px; box-shadow: 0 15px 50px rgba(0,0,0,0.3); animation: modalSlideIn 0.3s ease;">
            <div style="margin-bottom: 20px;">
                <i class="fa fa-exclamation-circle"
                    style="font-size: 48px; color: #e53637; display: block; text-align: center; margin-bottom: 15px;"></i>
            </div>
            <h5 style="margin: 0 0 10px 0; font-weight: 700; color: #111111; font-size: 20px; text-align: center;">
                Remove Item?</h5>
            <p id="removeProductName" style="margin: 0 0 25px 0; color: #666; font-size: 14px; text-align: center;"></p>
            <p style="margin: 0 0 30px 0; color: #999; font-size: 13px; text-align: center;">Are you sure you want to
                remove this product from your cart?</p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" onclick="closeRemoveModal()"
                    style="padding: 12px 30px; background: #f5f5f5; border: 1px solid #e5e5e5; border-radius: 4px; cursor: pointer; font-weight: 600; color: #111111; font-size: 14px; transition: all 0.3s ease; min-width: 120px;"
                    onmouseover="this.style.background='#e5e5e5'" onmouseout="this.style.background='#f5f5f5'">
                    Cancel
                </button>
                <button type="button" id="confirmRemoveBtn" onclick="confirmRemoveItem()"
                    style="padding: 12px 30px; background: #e53637; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; color: white; font-size: 14px; transition: all 0.3s ease; min-width: 120px;"
                    onmouseover="this.style.background='#d32f2f'; this.style.transform='scale(1.02)'"
                    onmouseout="this.style.background='#e53637'; this.style.transform='scale(1)'">
                    Remove
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>

    <script>
        let removeItemData = null;

        function openRemoveModal(event, itemId, productName) {
            event.preventDefault();
            removeItemData = {
                itemId: itemId,
                productName: productName
            };

            document.getElementById('removeProductName').textContent = 'Product: ' + productName;
            document.getElementById('removeConfirmationModal').style.display = 'flex';
        }

        function closeRemoveModal() {
            document.getElementById('removeConfirmationModal').style.display = 'none';
            removeItemData = null;
        }

        function confirmRemoveItem() {
            if (removeItemData && window.Livewire) {
                @this.removeItem(removeItemData.itemId);
                closeRemoveModal();
            }
        }

        // Close modal when clicking outside
        document.getElementById('removeConfirmationModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeRemoveModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && document.getElementById('removeConfirmationModal').style.display ===
                'flex') {
                closeRemoveModal();
            }
        });
    </script>

</div>
