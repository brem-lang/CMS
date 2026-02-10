<div>
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Digital Product</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('digital-products') }}">Digital Products</a>
                            <span>{{ $product->title }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-hero spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-9 text-center">
                    <div class="blog__hero__text">
                        <h2>{{ $product->title }}</h2>
                        <ul>
                            <li>{{ $product->file_type === 'pdf' ? 'PDF' : 'Audio' }}</li>
                            @if ($product->is_free)
                                <li><span class="text-success">Free</span></li>
                            @else
                                <li>₱{{ number_format($product->price, 2) }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-details spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                @if ($product->thumbnail_url)
                    <div class="col-lg-12 mb-4">
                        <div class="blog__details__pic">
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" class="img-fluid"
                                style="max-height: 400px; object-fit: contain; border-radius: 8px;">
                        </div>
                    </div>
                @endif
                <div class="col-lg-8">
                    <div class="blog__details__content">
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
                        <div class="blog__details__text mb-4">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                        @if ($product->is_free)
                            <a href="{{ route('digital-product.download', $product->id) }}" target="_blank" rel="noopener"
                                class="btn border-0 rounded-pill py-3 px-4 text-white fw-semibold d-inline-flex align-items-center justify-content-center gap-2 text-decoration-none"
                                style="font-size: 1rem; cursor: pointer; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); box-shadow: 0 3px 12px rgba(40, 167, 69, 0.4);">
                                <i class="fa fa-download" aria-hidden="true"></i>
                                <span>Download Free {{ $product->file_type === 'pdf' ? 'PDF' : 'Audio' }}</span>
                            </a>
                        @else
                            <button type="button" wire:click="addToCart"
                                class="btn border-0 rounded-pill py-3 px-4 text-white fw-semibold d-inline-flex align-items-center justify-content-center gap-2"
                                style="font-size: 1rem; cursor: pointer; background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); box-shadow: 0 3px 12px rgba(13, 110, 253, 0.4);">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span>Add to cart</span>
                            </button>
                        @endif
                        <div class="mt-3">
                            <a href="{{ route('digital-products') }}" class="text-muted" style="font-size: 14px;">
                                <i class="fa fa-arrow-left"></i> Back to Digital Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
