<div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>My Orders</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>My Orders</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Orders Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            @if ($orders->count() > 0)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="shopping__cart__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="product__cart__item">
                                                <div class="product__cart__item__text">
                                                    <h6 style="font-weight: 600; color: #111111;">
                                                        {{ $order->order_number }}</h6>
                                                </div>
                                            </td>
                                            <td class="quantity__item">
                                                <span
                                                    style="color: #666;">{{ $order->created_at->format('M d, Y') }}</span>
                                            </td>
                                            <td class="cart__price">
                                                <span style="color: #666;">{{ $order->orderItems->sum('quantity') }}
                                                    item(s)</span>
                                            </td>
                                            <td class="cart__price">
                                                <span
                                                    style="font-weight: 600; color: #e53637;">₱{{ number_format($order->total, 2) }}</span>
                                            </td>
                                            <td class="cart__price">
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
                                            </td>
                                            <td class="cart__price">
                                                @if ($order->status === 'delivered')
                                                    <span class="badge badge-success"
                                                        style="background: #28a745; color: white; padding: 5px 10px; border-radius: 3px;">Delivered</span>
                                                @elseif($order->status === 'shipped')
                                                    <span class="badge badge-info"
                                                        style="background: #17a2b8; color: white; padding: 5px 10px; border-radius: 3px;">Shipped</span>
                                                @elseif($order->status === 'confirm')
                                                    <span class="badge badge-success"
                                                        style="background: #28a745; color: white; padding: 5px 10px; border-radius: 3px;">Order Confirmed</span>
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
                                            </td>
                                            <td class="cart__close">
                                                <a href="{{ route('order.detail', $order->id) }}" class="site-btn"
                                                    style="padding: 8px 15px; font-size: 12px; text-decoration: none; display: inline-block;border-radius: 3px;">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product__pagination">
                                    {{ $orders->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="padding: 50px 20px;">
                            <i class="fa fa-shopping-bag"
                                style="font-size: 64px; color: #e5e5e5; margin-bottom: 20px; display: block;"></i>
                            <p style="color: #666; font-size: 16px; margin-bottom: 20px;">You have no orders yet</p>
                            <a href="{{ route('shop') }}" class="site-btn"
                                style="display: inline-block; text-decoration: none; padding: 12px 30px;">Start
                                Shopping</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Orders Section End -->

</div>
