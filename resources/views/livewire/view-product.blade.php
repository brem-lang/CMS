<div>
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('shop') }}">Shop</a>
                            <span>Product Details</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__pic__item" style="text-align: center;">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid"
                                style="max-width: 100%; height: auto; margin: 0 auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product__details__content">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="product__details__text">
                            <h4>{{ $product->name }}</h4>
                            <div class="rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                                <span> - 5 Reviews</span>
                            </div>
                            <h3>₱{{ number_format($product->price, 2) }}</h3>
                            <p>{{ $product->description }}</p>
                            <div class="product__details__cart__option">
                                <div class="quantity">
                                    <div class="pro-qty">
                                        <span class="fa fa-angle-up dec qtybtn" wire:click="incrementQuantity"
                                            style="cursor: pointer;"></span>
                                        <input type="text" value="{{ $quantity }}" readonly>
                                        <span class="fa fa-angle-down inc qtybtn" wire:click="decrementQuantity"
                                            style="cursor: pointer;"></span>
                                    </div>
                                </div>
                                <a href="#" wire:click="addToCart" class="primary-btn">add to cart</a>
                            </div>
                            <div class="product__details__btns__option">
                                <a href="#"><i class="fa fa-heart"></i> add to wishlist</a>
                                <a href="#"><i class="fa fa-exchange"></i> Add To Compare</a>
                            </div>
                            <div class="product__details__last__option">
                                <ul>
                                    <li><span>SKU:</span> {{ $product->id }}</li>
                                    <li><span>Stock:</span> {{ $product->stock_quantity ?? 'N/A' }} available</li>
                                    <li><span>Price:</span> ₱{{ number_format($product->price, 2) }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5"
                                        role="tab">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Customer
                                        Previews(5)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-7" role="tab">Additional
                                        information</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <p class="note">{{ $product->description }}</p>
                                        <div class="product__details__tab__content__item">
                                            <h5>Product Information</h5>
                                            <p>{{ $product->description }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-6" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <div class="product__details__tab__content__item">
                                            <h5>Customer Reviews</h5>
                                            <p>No reviews yet. Be the first to review this product!</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-7" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <p class="note">Additional product information.</p>
                                        <div class="product__details__tab__content__item">
                                            <h5>Product Details</h5>
                                            <ul>
                                                <li><span>Product ID:</span> {{ $product->id }}</li>
                                                <li><span>Name:</span> {{ $product->name }}</li>
                                                <li><span>Price:</span> ₱{{ number_format($product->price, 2) }}</li>
                                                <li><span>Stock:</span> {{ $product->stock_quantity ?? 'N/A' }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
