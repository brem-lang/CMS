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
                        
                        <!-- Order Number Highlight Section -->
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 10px; margin-bottom: 30px; text-align: center; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);">
                            <div style="margin-bottom: 15px;">
                                <i class="fa fa-info-circle" style="font-size: 24px; color: #fff; margin-right: 8px;"></i>
                                <span style="color: #fff; font-size: 16px; font-weight: 600;">Save Your Order Number</span>
                            </div>
                            <div style="background: rgba(255, 255, 255, 0.95); padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                                <p style="color: #666; font-size: 14px; margin-bottom: 10px; font-weight: 600;">Order Number:</p>
                                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                    <span id="order-number" style="font-size: 24px; font-weight: 700; color: #667eea; letter-spacing: 1px; font-family: 'Courier New', monospace;">{{ $order->order_number }}</span>
                                    <button onclick="copyOrderNumber(this)" style="background: #667eea; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s;" onmouseover="this.style.background='#5568d3'" onmouseout="this.style.background='#667eea'">
                                        <i class="fa fa-copy"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <p style="color: rgba(255, 255, 255, 0.95); font-size: 14px; margin: 0; line-height: 1.6;">
                                <i class="fa fa-exclamation-triangle" style="margin-right: 5px;"></i>
                                Please save this order number to track your order status. You can use it on the 
                                <a href="{{ route('track-order') }}" style="color: #fff; text-decoration: underline; font-weight: 600;">Track Order</a> page.
                            </p>
                        </div>

                        <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; margin-bottom: 30px; text-align: left;">
                            <h5 style="margin-bottom: 20px; color: #111;">Order Details</h5>
                            <div style="display: grid; gap: 10px;">
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
                            <a href="{{ route('track-order') }}" class="site-btn" style="display: inline-block; text-decoration: none; padding: 12px 30px; background: #667eea;">
                                <i class="fa fa-search" style="margin-right: 8px;"></i> Track Order
                            </a>
                            <a href="{{ route('home') }}" class="site-btn" style="display: inline-block; text-decoration: none; padding: 12px 30px;">
                                Continue Shopping
                            </a>
                            @auth
                            <a href="{{ route('orders') }}" class="site-btn" style="display: inline-block; text-decoration: none; padding: 12px 30px; background: #111;">
                                View Orders
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Success Section End -->
</div>

<script>
    function copyOrderNumber(button) {
        const orderNumber = document.getElementById('order-number').textContent;
        const originalText = button.innerHTML;
        
        navigator.clipboard.writeText(orderNumber).then(function() {
            // Show feedback
            button.innerHTML = '<i class="fa fa-check"></i> Copied!';
            button.style.background = '#28a745';
            
            setTimeout(function() {
                button.innerHTML = originalText;
                button.style.background = '#667eea';
            }, 2000);
        }).catch(function(err) {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = orderNumber;
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            button.innerHTML = '<i class="fa fa-check"></i> Copied!';
            button.style.background = '#28a745';
            
            setTimeout(function() {
                button.innerHTML = originalText;
                button.style.background = '#667eea';
            }, 2000);
        });
    }
</script>
