<x-layout.app>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Track Your Order</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Track Your Order</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Track Order Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="checkout__form">
                        <h6 class="checkout__title">Enter Your Order Reference Number</h6>
                        
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                style="background: #dc3545; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                                    style="color: white; opacity: 0.8;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('track-order.search') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="checkout__input">
                                        <p>Order Reference Number <span>*</span></p>
                                        <input type="text" name="order_number" 
                                            value="{{ old('order_number') }}" 
                                            placeholder="e.g., ORD-20260125-ABC12345" 
                                            required
                                            style="width: 100%; padding: 12px; border: 1px solid #e5e5e5; border-radius: 5px;">
                                        @error('order_number')
                                            <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="checkout__input" style="margin-top: 30px;">
                                        <button type="submit" class="site-btn" 
                                            style="width: 100%; padding: 12px;">
                                            Track Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if (isset($order))
                <div class="row" style="margin-top: 40px;">
                    <div class="col-lg-12">
                        <div class="checkout__form">
                            <h6 class="checkout__title">Order Tracking Details</h6>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p><strong>Order Number:</strong></p>
                                        <p style="font-size: 18px; font-weight: 600; color: #111111;">
                                            {{ $order->order_number }}</p>
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
                                        <p><strong>Current Status:</strong></p>
                                        @if ($order->status === 'delivered')
                                            <span class="badge badge-success"
                                                style="background: #28a745; color: white; padding: 8px 15px; border-radius: 5px; font-size: 14px;">
                                                <i class="fa fa-check-circle"></i> Delivered
                                            </span>
                                        @elseif($order->status === 'shipped')
                                            <span class="badge badge-info"
                                                style="background: #17a2b8; color: white; padding: 8px 15px; border-radius: 5px; font-size: 14px;">
                                                <i class="fa fa-truck"></i> Shipped
                                                @if($order->courier)
                                                    ({{ $order->courier->name }})
                                                @endif
                                            </span>
                                        @elseif($order->status === 'confirm')
                                            <span class="badge badge-success"
                                                style="background: #28a745; color: white; padding: 8px 15px; border-radius: 5px; font-size: 14px;">
                                                <i class="fa fa-check-circle"></i> Order Confirmed
                                            </span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="badge badge-danger"
                                                style="background: #dc3545; color: white; padding: 8px 15px; border-radius: 5px; font-size: 14px;">
                                                <i class="fa fa-times-circle"></i> Cancelled
                                            </span>
                                        @else
                                            <span class="badge badge-warning"
                                                style="background: #ffc107; color: #111; padding: 8px 15px; border-radius: 5px; font-size: 14px;">
                                                <i class="fa fa-clock-o"></i> Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p><strong>Payment Status:</strong></p>
                                        @if ($order->payment_status === 'paid')
                                            <span class="badge badge-success"
                                                style="background: #28a745; color: white; padding: 8px 15px; border-radius: 5px; font-size: 14px;">
                                                Paid
                                            </span>
                                        @elseif($order->payment_status === 'failed')
                                            <span class="badge badge-danger"
                                                style="background: #dc3545; color: white; padding: 8px 15px; border-radius: 5px; font-size: 14px;">
                                                Failed
                                            </span>
                                        @else
                                            <span class="badge badge-warning"
                                                style="background: #ffc107; color: #111; padding: 8px 15px; border-radius: 5px; font-size: 14px;">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($order->courier && $order->status === 'shipped')
                                <div class="checkout__input">
                                    <p><strong>Courier:</strong></p>
                                    <p style="font-size: 16px; font-weight: 600; color: #17a2b8;">
                                        {{ $order->courier->name }}
                                    </p>
                                </div>
                            @endif

                            <h6 class="checkout__title" style="margin-top: 30px;">Order Status History</h6>
                            
                            <div class="order-tracking-timeline" style="position: relative; padding-left: 30px; margin-top: 20px;">
                                @php
                                    $statuses = $order->statusHistory;
                                    $statusOrder = ['pending' => 1, 'confirm' => 2, 'shipped' => 3, 'delivered' => 4, 'cancelled' => 0];
                                @endphp
                                
                                @foreach($statuses as $index => $history)
                                    <div class="timeline-item" 
                                        style="position: relative; padding-bottom: 30px; border-left: 2px solid {{ $index === count($statuses) - 1 ? '#28a745' : '#e5e5e5' }};">
                                        
                                        <div class="timeline-marker" 
                                            style="position: absolute; left: -8px; top: 0; width: 16px; height: 16px; 
                                            border-radius: 50%; background: {{ $index === count($statuses) - 1 ? '#28a745' : '#999' }}; 
                                            border: 3px solid white; box-shadow: 0 0 0 2px {{ $index === count($statuses) - 1 ? '#28a745' : '#999' }};">
                                        </div>
                                        
                                        <div class="timeline-content" style="margin-left: 25px;">
                                            <h6 style="font-weight: 600; color: #111111; margin-bottom: 5px;">
                                                @if($history->status === 'delivered')
                                                    <i class="fa fa-check-circle" style="color: #28a745;"></i> Delivered
                                                @elseif($history->status === 'shipped')
                                                    <i class="fa fa-truck" style="color: #17a2b8;"></i> Shipped
                                                    @if($history->courier)
                                                        via {{ $history->courier }}
                                                    @endif
                                                @elseif($history->status === 'confirm')
                                                    <i class="fa fa-check-circle" style="color: #28a745;"></i> Order Confirmed
                                                @elseif($history->status === 'cancelled')
                                                    <i class="fa fa-times-circle" style="color: #dc3545;"></i> Cancelled
                                                @else
                                                    <i class="fa fa-clock-o" style="color: #ffc107;"></i> Pending
                                                @endif
                                            </h6>
                                            <p style="color: #666; font-size: 14px; margin-bottom: 5px;">
                                                {{ $history->created_at->format('F d, Y h:i A') }}
                                            </p>
                                            @if($history->notes)
                                                <p style="color: #999; font-size: 13px; font-style: italic;">
                                                    {{ $history->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div style="margin-top: 30px;">
                                <a href="{{ route('track-order') }}" class="site-btn" 
                                    style="display: inline-block; text-decoration: none;">
                                    Track Another Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Track Order Section End -->
</x-layout.app>
