<div>
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('digital-products') }}">Digital Products</a>
                            <span>{{ $product->title }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                        <div class="product__details__pic__item">
                            @if ($product->thumbnail_url)
                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}"
                                    style="width: 100%; height: auto; max-height: 500px; object-fit: contain;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                    style="min-height: 300px;">
                                    <i class="fa fa-file-{{ $product->file_type === 'pdf' ? 'pdf' : 'audio' }}-o text-muted"
                                        style="font-size: 80px;"></i>
                                </div>
                            @endif
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
                            <h4>{{ $product->title }}</h4>
                            <h3
                                @if ($product->is_free) class="text-success" style="text-decoration: none;" @endif>
                                @if ($product->is_free)
                                    Free
                                @else
                                    ₱{{ number_format($product->price, 2) }}
                                @endif
                            </h3>
                            <p>{!! nl2br(e($product->description)) !!}</p>

                            @if (session('message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa fa-check-circle"></i> {{ session('message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div
                                style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; justify-content: center; margin-top: 20px;">
                                @if ($product->is_free)
                                    <a href="{{ route('digital-product.download', $product->id) }}" target="_blank"
                                        rel="noopener" class="primary-btn"
                                        style="display: inline-block; text-decoration: none;">
                                        <i class="fa fa-download" style="margin-right: 8px;"></i>Download Free
                                        {{ $product->file_type === 'pdf' ? 'PDF' : 'Audio' }}
                                    </a>
                                @else
                                    <a href="#" wire:click.prevent="addToCart"
                                        class="primary-btn buy-now-btn-cart">
                                        <i class="fa fa-shopping-cart" style="margin-right: 8px;"></i>Add to cart
                                    </a>
                                    <a href="#" wire:click.prevent="buyNow" class="primary-btn buy-now-btn">buy
                                        now</a>
                                @endif
                            </div>
                        </div>
                        <div class="product__details__last__option"
                            style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; justify-content: center; margin-top: 20px;">
                            <ul>
                                <li><span>Type:</span> {{ $product->file_type === 'pdf' ? 'PDF' : 'Audio' }}</li>
                                <li><span>Price:</span>
                                    @if ($product->is_free)
                                        <span class="text-success">Free</span>
                                    @else
                                        ₱{{ number_format($product->price, 2) }}
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs flex-nowrap overflow-auto justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#digital-tab-desc"
                                        role="tab">Description</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="digital-tab-desc" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <div class="product__details__tab__content__item">
                                            <h5>Product Information</h5>
                                            <p>{!! nl2br(e($product->description)) !!}</p>
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
    <style>
        .buy-now-btn-cart {
            transition: all 0.3s ease;
        }

        .buy-now-btn-cart:hover {
            background-color: #111 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .buy-now-btn {
            background-color: #e53637;
            border-color: #e53637;
            transition: all 0.3s ease;
        }

        .buy-now-btn:hover {
            background-color: #c42d2e !important;
            border-color: #c42d2e !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(229, 54, 55, 0.3);
        }
    </style>
</div>
