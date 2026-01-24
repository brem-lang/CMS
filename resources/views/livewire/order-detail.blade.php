<div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Order Details</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('orders') }}">My Orders</a>
                            <span>Order #{{ $order->order_number }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Order Detail Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout__form">
                        <h6 class="checkout__title">Order Information</h6>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p><strong>Order Number:</strong></p>
                                    <p>{{ $order->order_number }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p><strong>Order Date:</strong></p>
                                    <p>{{ $order->created_at->format('F d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p><strong>Payment Status:</strong></p>
                                    @if ($order->payment_status === 'paid')
                                        <span class="badge badge-success"
                                            style="background: #28a745; color: white; padding: 5px 10px; border-radius: 3px;">Paid</span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="badge badge-danger"
                                            style="background: #dc3545; color: white; padding: 5px 10px; border-radius: 3px;">Failed</span>
                                    @else
                                        <span class="badge badge-warning"
                                            style="background: #ffc107; color: #111; padding: 5px 10px; border-radius: 3px;">Pending</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p><strong>Order Status:</strong></p>
                                    @if ($order->status === 'delivered')
                                        <span class="badge badge-success"
                                            style="background: #28a745; color: white; padding: 5px 10px; border-radius: 3px;">Delivered</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="badge badge-info"
                                            style="background: #17a2b8; color: white; padding: 5px 10px; border-radius: 3px;">Shipped</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge badge-primary"
                                            style="background: #007bff; color: white; padding: 5px 10px; border-radius: 3px;">Processing</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge badge-danger"
                                            style="background: #dc3545; color: white; padding: 5px 10px; border-radius: 3px;">Cancelled</span>
                                    @else
                                        <span class="badge badge-warning"
                                            style="background: #ffc107; color: #111; padding: 5px 10px; border-radius: 3px;">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="checkout__input">
                            <p><strong>Payment Method:</strong></p>
                            <p>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>

                        <h6 class="checkout__title" style="margin-top: 30px;">Shipping Information</h6>
                        <div class="checkout__input">
                            <p><strong>Full Name:</strong></p>
                            <p>{{ $order->full_name }}</p>
                        </div>
                        <div class="checkout__input">
                            <p><strong>Email:</strong></p>
                            <p>{{ $order->email }}</p>
                        </div>
                        <div class="checkout__input">
                            <p><strong>Phone:</strong></p>
                            <p>{{ $order->phone }}</p>
                        </div>
                        <div class="checkout__input">
                            <p><strong>Address:</strong></p>
                            <p>{{ $order->address }}, {{ $order->town }}, {{ $order->state }}
                                {{ $order->postcode }}, {{ $order->country }}</p>
                        </div>
                        @if ($order->order_notes)
                            <div class="checkout__input">
                                <p><strong>Order Notes:</strong></p>
                                <p>{{ $order->order_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkout__order">
                        <h4 class="order__title">Order Items</h4>
                        <div class="checkout__order__products"
                            style="display: grid; grid-template-columns: 1fr 0.8fr 0.8fr; gap: 10px; padding: 10px 0; border-bottom: 2px solid #e5e5e5; font-weight: 600; color: #111111;">
                            <span>Product</span>
                            <span style="text-align: center;">Quantity</span>
                            <span style="text-align: right;">Subtotal</span>
                        </div>
                        <ul class="checkout__total__products" style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($order->orderItems as $item)
                                <li
                                    style="display: grid; grid-template-columns: 1fr 0.8fr 0.8fr; gap: 10px; padding: 12px 0; border-bottom: 1px solid #f0f0f0; align-items: center;">
                                    <div>
                                        <span
                                            style="font-size: 14px; color: #666; font-weight: 600;">{{ $item->product->name }}</span>
                                        <p style="font-size: 12px; color: #999; margin: 5px 0 0 0;">
                                            ₱{{ number_format($item->price, 2) }} each</p>
                                    </div>
                                    <span
                                        style="text-align: center; font-size: 14px; color: #666;">x{{ $item->quantity }}</span>
                                    <span
                                        style="text-align: right; font-weight: 600; color: #e53637;">₱{{ number_format($item->subtotal, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <ul class="checkout__total__all"
                            style="list-style: none; padding: 0; margin: 0; margin-top: 15px; border-top: 2px solid #e5e5e5; padding-top: 15px;">
                            <li
                                style="display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: #666;">
                                <span>Subtotal</span>
                                <span>₱{{ number_format($order->subtotal, 2) }}</span>
                            </li>
                            <li
                                style="display: flex; justify-content: space-between; padding: 12px 0; font-size: 16px; font-weight: 700; color: #111111; border-top: 1px solid #e5e5e5; padding-top: 15px;">
                                <span>Total</span>
                                <span style="color: #e53637;">₱{{ number_format($order->total, 2) }}</span>
                            </li>
                        </ul>
                        <a href="{{ route('orders') }}" class="site-btn"
                            style="width: 100%; margin-top: 20px; display: block; text-align: center; text-decoration: none;">Back
                            to Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Order Detail Section End -->

</div>
