<div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Payment Success</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Payment Success</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Success Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div style="text-align: center; padding: 50px 20px; background: #fff; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <div style="margin-bottom: 30px;">
                            <i class="fa fa-check-circle" style="font-size: 80px; color: #28a745;"></i>
                        </div>
                        <h2 style="color: #28a745; margin-bottom: 20px;">Payment Successful!</h2>
                        <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
                            Thank you for your order. Your payment has been processed successfully.
                        </p>
                        
                        <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; margin-bottom: 30px; text-align: left;">
                            <h5 style="margin-bottom: 20px; color: #111;">Order Details</h5>
                            <div style="display: grid; gap: 10px;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #666;">Order Number:</span>
                                    <span style="font-weight: 600; color: #111;">{{ $order->order_number }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #666;">Total Amount:</span>
                                    <span style="font-weight: 600; color: #e53637;">₱{{ number_format($order->total, 2) }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #666;">Payment Method:</span>
                                    <span style="font-weight: 600; color: #111;">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #666;">Status:</span>
                                    <span style="font-weight: 600; color: #28a745;">{{ ucfirst($order->status) }}</span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                            <a href="{{ route('home') }}" class="site-btn" style="display: inline-block; text-decoration: none; padding: 12px 30px;">
                                Continue Shopping
                            </a>
                            <a href="{{ route('shop') }}" class="site-btn" style="display: inline-block; text-decoration: none; padding: 12px 30px; background: #111;">
                                View Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Success Section End -->
</div>
