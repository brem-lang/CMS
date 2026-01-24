<div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Bank Transfer Instructions</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Bank Transfer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Bank Transfer Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div style="padding: 50px 20px; background: #fff; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <h2 style="text-align: center; margin-bottom: 30px; color: #111;">Bank Transfer Instructions</h2>
                        
                        <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; margin-bottom: 30px;">
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
                            </div>
                        </div>

                        <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                            <h5 style="color: #856404; margin-bottom: 15px;">
                                <i class="fa fa-info-circle"></i> Important Instructions
                            </h5>
                            <ol style="color: #856404; padding-left: 20px;">
                                <li style="margin-bottom: 10px;">Transfer the exact amount of <strong>₱{{ number_format($order->total, 2) }}</strong> to the bank account below.</li>
                                <li style="margin-bottom: 10px;">Include your Order Number <strong>{{ $order->order_number }}</strong> in the transfer reference/notes.</li>
                                <li style="margin-bottom: 10px;">After completing the transfer, your order will be processed automatically once payment is verified.</li>
                                <li style="margin-bottom: 10px;">Payment verification may take 1-3 business days.</li>
                            </ol>
                        </div>

                        <div style="background: #e7f3ff; border: 1px solid #0066cc; padding: 30px; border-radius: 8px; margin-bottom: 30px;">
                            <h5 style="color: #0066cc; margin-bottom: 20px;">Bank Account Details</h5>
                            <div style="display: grid; gap: 15px;">
                                <div>
                                    <span style="color: #666; display: block; margin-bottom: 5px;">Bank Name:</span>
                                    <span style="font-weight: 600; color: #111; font-size: 18px;">BDO (Banco de Oro)</span>
                                </div>
                                <div>
                                    <span style="color: #666; display: block; margin-bottom: 5px;">Account Name:</span>
                                    <span style="font-weight: 600; color: #111; font-size: 18px;">Your Business Name</span>
                                </div>
                                <div>
                                    <span style="color: #666; display: block; margin-bottom: 5px;">Account Number:</span>
                                    <span style="font-weight: 600; color: #111; font-size: 18px;">1234-5678-9012</span>
                                </div>
                                <div>
                                    <span style="color: #666; display: block; margin-bottom: 5px;">Account Type:</span>
                                    <span style="font-weight: 600; color: #111;">Savings Account</span>
                                </div>
                            </div>
                            <p style="color: #666; font-size: 14px; margin-top: 20px; margin-bottom: 0;">
                                <strong>Note:</strong> Please update these bank details in your configuration file.
                            </p>
                        </div>

                        <div style="text-align: center;">
                            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                                <a href="{{ route('home') }}" class="site-btn" style="display: inline-block; text-decoration: none; padding: 12px 30px;">
                                    Continue Shopping
                                </a>
                                <a href="{{ route('view-cart') }}" class="site-btn" style="display: inline-block; text-decoration: none; padding: 12px 30px; background: #111;">
                                    Back to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Bank Transfer Section End -->
</div>
