<x-layout.app>
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Download Your File</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Digital Download</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="checkout__form" style="background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                        <div style="text-align: center; margin-bottom: 25px;">
                            <i class="fa fa-download" style="font-size: 48px; color: #e53637;"></i>
                            <h5 class="checkout__title" style="margin-top: 15px;">Enter your receipt ID to download</h5>
                            <p class="text-muted mb-0">{{ $productTitle }}</p>
                        </div>

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                style="margin-bottom: 20px;">
                                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if ($errors->has('receipt_id'))
                            <div class="alert alert-danger" style="margin-bottom: 20px;">
                                {{ $errors->first('receipt_id') }}
                            </div>
                        @endif

                        <form action="{{ route('digital-product.download.verify', $orderItem) }}" method="POST">
                            @csrf
                            <div class="checkout__input">
                                <p>Receipt ID <span>*</span></p>
                                <input type="text"
                                    name="receipt_id"
                                    value="{{ old('receipt_id') }}"
                                    placeholder="e.g., RCP-ORD-20260125-123"
                                    required
                                    autocomplete="off"
                                    style="width: 100%; padding: 14px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 16px;">
                            </div>
                            <div style="margin-top: 20px;">
                                <button type="submit" class="site-btn" style="width: 100%; padding: 14px;">
                                    <i class="fa fa-download" style="margin-right: 8px;"></i> Download file
                                </button>
                            </div>
                        </form>

                        <p class="text-muted text-center" style="margin-top: 20px; font-size: 13px;">
                            Your receipt ID was sent to your email. Too many attempts may temporarily limit access.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout.app>
