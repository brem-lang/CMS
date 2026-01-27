<div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Payment Failed</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Payment Failed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Failed Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div style="text-align: center; padding: 50px 20px; background: #fff; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <div style="margin-bottom: 30px;">
                            <i class="fa fa-times-circle" style="font-size: 80px; color: #dc3545;"></i>
                        </div>
                        <h2 style="color: #dc3545; margin-bottom: 20px;">Payment Failed</h2>
                        <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
                            Unfortunately, your payment could not be processed. Please try again or choose a different payment method.
                        </p>
                        
                        <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; margin-bottom: 30px; text-align: left;">
                            <h5 style="margin-bottom: 20px; color: #111;">Order Details</h5>
                            <div style="display: grid; gap: 10px;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #666;">Order Number:</span>
                                    <span style="font-weight: 600; color: #111;">{{ $orderNumber }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #666;">Total Amount:</span>
                                    <span style="font-weight: 600; color: #e53637;">₱{{ number_format($orderTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                            <a href="{{ route('checkout') }}" class="site-btn" style="display: inline-block; text-decoration: none; padding: 12px 30px;">
                                Try Again
                            </a>
                            <a href="{{ route('view-cart') }}" class="site-btn" style="display: inline-block; text-decoration: none; padding: 12px 30px; background: #111;">
                                Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Failed Section End -->
</div>
