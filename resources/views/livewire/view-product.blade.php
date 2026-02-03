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
                    <div class="col-lg-3 col-md-3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">
                                    <div class="product__thumb__pic set-bg" data-setbg="{{ $product->image_url }}">
                                    </div>
                                </a>
                            </li>
                            @foreach ($product->additional_images ?? [] as $image)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-{{ $loop->index + 2 }}"
                                        role="tab">
                                        <div class="product__thumb__pic set-bg" data-setbg="{{ Storage::url($image) }}">
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                </div>
                            </div>
                            @foreach ($product->additional_images ?? [] as $image)
                                <div class="tab-pane" id="tabs-{{ $loop->index + 2 }}" role="tabpanel">
                                    <div class="product__details__pic__item">
                                        <img src="{{ Storage::url($image) }}" alt="{{ $product->name }}">
                                    </div>
                                </div>
                            @endforeach
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
                                @if (($product->stock_quantity ?? 0) == 0)
                                    <div class="quantity" style="opacity: 0.5; pointer-events: none;">
                                        <div
                                            style="display: flex; align-items: center; border: 1px solid #e5e5e5; border-radius: 3px; width: fit-content;">
                                            <button type="button" disabled
                                                style="background: #f5f5f5; border: none; border-right: 1px solid #e5e5e5; padding: 10px 15px; cursor: not-allowed; font-size: 14px; font-weight: 700; color: #999999;">−</button>
                                            <input type="number" value="{{ $quantity }}" readonly
                                                style="border: none; background: transparent; text-align: center; width: 45px; font-weight: 600; color: #999999; padding: 10px 0; user-select: none; -moz-appearance: textfield;">
                                            <button type="button" disabled
                                                style="background: #f5f5f5; border: none; border-left: 1px solid #e5e5e5; padding: 10px 15px; cursor: not-allowed; font-size: 14px; font-weight: 700; color: #999999;">+</button>
                                        </div>
                                    </div>
                                    <a href="#" class="primary-btn"
                                        style="opacity: 0.5; cursor: not-allowed; pointer-events: none;"
                                        onclick="return false;">add to cart</a>
                                @else
                                    <div class="quantity">
                                        <div
                                            style="display: flex; align-items: center; border: 1px solid #e5e5e5; border-radius: 3px; width: fit-content;">
                                            <button type="button" wire:click="decrementQuantity"
                                                style="background: #f5f5f5; border: none; border-right: 1px solid #e5e5e5; padding: 10px 15px; cursor: pointer; font-size: 14px; font-weight: 700; color: #111111; transition: background 0.2s ease; user-select: none;"
                                                onmouseover="this.style.background='#ebebeb'"
                                                onmouseout="this.style.background='#f5f5f5'">−</button>
                                            <input type="number" value="{{ $quantity }}" readonly
                                                style="border: none; background: transparent; text-align: center; width: 45px; font-weight: 600; color: #111111; padding: 10px 0; user-select: none; -moz-appearance: textfield;">
                                            <button type="button" wire:click="incrementQuantity"
                                                style="background: #f5f5f5; border: none; border-left: 1px solid #e5e5e5; padding: 10px 15px; cursor: pointer; font-size: 14px; font-weight: 700; color: #111111; transition: background 0.2s ease; user-select: none;"
                                                onmouseover="this.style.background='#ebebeb'"
                                                onmouseout="this.style.background='#f5f5f5'">+</button>
                                        </div>
                                    </div>
                                    <a href="#" wire:click.prevent="addToCart" class="primary-btn">add to
                                        cart</a>
                                @endif
                            </div>
                            <div class="product__details__last__option">
                                <ul>
                                    <li><span>SKU:</span> {{ $product->id }}</li>
                                    <li><span>Stock:</span>
                                        @if (($product->stock_quantity ?? 0) == 0)
                                            <span style="color: #dc3545; font-weight: bold;">Out of Stock</span>
                                        @else
                                            {{ $product->stock_quantity }} available
                                        @endif
                                    </li>
                                    <li><span>Price:</span> ₱{{ number_format($product->price, 2) }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs flex-nowrap overflow-auto justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5"
                                        role="tab">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-7" role="tab">Additional
                                        Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Customer
                                        Reviews</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                    <div class="product__details__tab__content">
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
                                        <div class="product__details__tab__content__item">
                                            <h5>Additional product information.</h5>
                                            <ul class="list-unstyled px-2">
                                                <li><span>Product ID:</span> {{ $product->id }}</li>
                                                <li><span>Name:</span> {{ $product->name }}</li>
                                                <li><span>Price:</span> ₱{{ number_format($product->price, 2) }}</li>
                                                <li><span>Stock:</span>
                                                    @if (($product->stock_quantity ?? 0) == 0)
                                                        <span style="color: #dc3545; font-weight: bold;">Out of
                                                            Stock</span>
                                                    @else
                                                        {{ $product->stock_quantity }}
                                                    @endif
                                                </li>
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
